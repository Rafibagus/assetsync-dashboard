<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    // Menampilkan halaman dashboard beserta data barang
    public function index()
    {
        $items = Item::all();
        return view('dashboard', compact('items'));
    }

    // Memproses form tambah barang dari web
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'price' => 'required|numeric|min:0',
        ]);

        Item::create($request->all());
        return redirect()->back()->with('success', 'Barang berhasil ditambahkan!');
    }

    // Fitur 2: Logika untuk menghapus barang
    public function destroy(Item $item)
    {
        $item->delete();
        return redirect()->back()->with('success', 'Barang berhasil dihapus!');
    }
}