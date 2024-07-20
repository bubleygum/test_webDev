<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;

class BarangController extends Controller
{

    public function index(Request $request)
    {
        $barang = Barang::query();

        if ($request->has('search')) {
            $barang->where('nama', 'like', "%{$request->search}%");
        }

        if ($request->has('sort_by')) {
            $barang->orderBy($request->sort_by, $request->get('sort_order', 'asc'));
        }

        $barang = $barang->get();

        return view('barang.index', compact('barang'));
    }

    public function create()
    {
        return view('barang.tambahBarang');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'stok' => 'required|integer|min:0',
            'jenis' => 'required|string|max:255',
        ]);

        $barang = new Barang();
        $barang->nama = $request->nama;
        $barang->stok = $request->stok;
        $barang->jenis = $request->jenis;
        $barang->save();

        return redirect()->route('barang.index');
    }
    public function edit($id)
    {
        $barang = Barang::find($id);
        return view('barang.editBarang', compact('barang'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'stok' => 'required|integer|min:0',
            'jenis' => 'required|string|max:255',
        ]);

        $barang = Barang::findOrFail($id);
        $barang->nama = $request->nama;
        $barang->stok = $request->stok;
        $barang->jenis = $request->jenis;
        $barang->save();

        return redirect()->route('barang.index');
    }

    public function destroy($id)
    {
        \DB::table('transaksi')->where('id_barang', $id)->delete();

        $barang = Barang::findOrFail($id);
        $barang->delete();

        return redirect()->route('barang.index');
    }

}


