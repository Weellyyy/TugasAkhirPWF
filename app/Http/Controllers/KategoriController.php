<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Kategori;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategoris = Kategori::withCount('produks')->get();
        return view('admin.kategori.index', compact('kategoris'));
    }

    public function create()
    {
        return view('admin.kategori.form');
    }

    public function store(Request $request)
    {
        $request->validate(['nama_kategori' => 'required|string|max:255']);
        Kategori::create($request->all());
        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(Kategori $kategori)
    {
        return view('admin.kategori.form', compact('kategori'));
    }

    public function update(Request $request, Kategori $kategori)
    {
        $request->validate(['nama_kategori' => 'required|string|max:255']);
        $kategori->update($request->all());
        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil diupdate.');
    }

    public function destroy(Kategori $kategori)
    {
        $kategori->delete();
        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
