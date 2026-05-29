<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Kategori;
use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $kasir;
    private Kategori $kategori;
    private Produk $produk;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'nama' => 'Admin User',
            'username' => 'admin',
            'email' => 'admin@pos.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'status' => 'Aktif',
        ]);

        $this->kasir = User::create([
            'nama' => 'Kasir User',
            'username' => 'kasir',
            'email' => 'kasir@pos.com',
            'password' => bcrypt('password'),
            'role' => 'kasir',
            'status' => 'Aktif',
        ]);

        $this->kategori = Kategori::create([
            'nama_kategori' => 'Makanan',
        ]);

        $this->produk = Produk::create([
            'kategori_id' => $this->kategori->id,
            'sku' => 'PRD001',
            'nama_produk' => 'Nasi Goreng',
            'harga' => 15000,
            'stok' => 100,
            'lokasi' => 'Dapur',
        ]);
    }

    public function test_guest_cannot_access_reports()
    {
        $response = $this->get(route('admin.reports.index'));
        $response->assertRedirect('/login');
    }

    public function test_kasir_cannot_access_reports()
    {
        $response = $this->actingAs($this->kasir)->get(route('admin.reports.index'));
        $response->assertStatus(403);
    }

    public function test_admin_can_access_reports_and_see_transactions()
    {
        $transaksi = Transaksi::create([
            'user_id' => $this->kasir->id,
            'total_harga' => 15000,
            'metode_pembayaran' => 'Cash',
        ]);

        DetailTransaksi::create([
            'transaksi_id' => $transaksi->id,
            'produk_id' => $this->produk->id,
            'jumlah' => 1,
            'harga' => 15000,
            'subtotal' => 15000,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.reports.index'));

        $response->assertStatus(200);
        $response->assertSee('Nasi Goreng');
        $response->assertSee('Kasir User');
        $response->assertSee('Rp 15.000');
    }

    public function test_reports_filter_by_month_and_year()
    {
        // Transaksi 1: Bulan ini (Mei 2026)
        $t1 = new Transaksi([
            'user_id' => $this->kasir->id,
            'total_harga' => 15000,
            'metode_pembayaran' => 'Cash',
        ]);
        $t1->created_at = '2026-05-15 10:00:00';
        $t1->save();

        // Transaksi 2: Bulan lalu (April 2026)
        $t2 = new Transaksi([
            'user_id' => $this->kasir->id,
            'total_harga' => 30000,
            'metode_pembayaran' => 'Qris',
        ]);
        $t2->created_at = '2026-04-15 10:00:00';
        $t2->save();

        // Filter per Bulan: Mei 2026
        $response = $this->actingAs($this->admin)->get(route('admin.reports.index', [
            'filter_type' => 'bulan',
            'filter_month' => 5,
            'filter_year' => 2026,
        ]));

        $response->assertStatus(200);
        $response->assertSee('TRX-' . sprintf('%04d', $t1->id));
        $response->assertDontSee('TRX-' . sprintf('%04d', $t2->id));

        // Filter per Tahun: 2026 (both should be visible)
        $responseYear = $this->actingAs($this->admin)->get(route('admin.reports.index', [
            'filter_type' => 'tahun',
            'filter_year' => 2026,
        ]));

        $responseYear->assertStatus(200);
        $responseYear->assertSee('TRX-' . sprintf('%04d', $t1->id));
        $responseYear->assertSee('TRX-' . sprintf('%04d', $t2->id));
    }

    public function test_reports_csv_export()
    {
        $t = new Transaksi([
            'user_id' => $this->kasir->id,
            'total_harga' => 15000,
            'metode_pembayaran' => 'Cash',
        ]);
        $t->created_at = '2026-05-15 10:00:00';
        $t->save();

        DetailTransaksi::create([
            'transaksi_id' => $t->id,
            'produk_id' => $this->produk->id,
            'jumlah' => 1,
            'harga' => 15000,
            'subtotal' => 15000,
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.reports.index', [
            'filter_type' => 'bulan',
            'filter_month' => 5,
            'filter_year' => 2026,
            'export' => 'csv',
        ]));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        
        $content = $response->streamedContent();
        
        $this->assertStringContainsString('No. Transaksi', $content);
        $this->assertStringContainsString('Nasi Goreng', $content);
        $this->assertStringContainsString('TRX-' . sprintf('%04d', $t->id), $content);
    }
}
