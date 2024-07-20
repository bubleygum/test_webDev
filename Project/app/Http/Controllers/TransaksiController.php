<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Barang;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $transaksi = Transaksi::with('barang')->get();

        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            $sortOrder = $request->input('sort_order', 'desc');

            $comparisonData = Transaksi::select('barang.jenis', DB::raw('SUM(transaksi.jumlah_terjual) as total_terjual'))
                ->join('barang', 'transaksi.id_barang', '=', 'barang.id')
                ->whereBetween('transaksi.tanggal_transaksi', [$startDate, $endDate])
                ->groupBy('barang.jenis')
                ->orderBy('total_terjual', $sortOrder)
                ->get();

            return view('transaksi.index', [
                'transaksi' => $transaksi,
                'comparisonData' => $comparisonData
            ]);
        }

        return view('transaksi.index', compact('transaksi'));
    }

    public function create()
    {
        $barang = Barang::all();
        return view('transaksi.tambahTransaksi', compact('barang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'jumlah_terjual' => 'required|integer|min:1',
            'tanggal_transaksi' => 'required|date',
        ]);

        $transaksi = new Transaksi();
        $transaksi->id_barang = $request->input('barang_id');
        $transaksi->jumlah_terjual = $request->input('jumlah_terjual');
        $transaksi->tanggal_transaksi = $request->input('tanggal_transaksi');
        $transaksi->save();

        $barang = Barang::find($request->input('barang_id'));
        if ($barang) {
            $barang->stok -= $request->input('jumlah_terjual');
            $barang->save();
        }

        return redirect()->route('transaksi.index');
    }


    public function edit($id)
    {
        $transaksi = Transaksi::find($id);
        $barang = Barang::all();

        if (!$transaksi) {
            return redirect()->route('transaksi.index')->with('error', 'Transaksi not found.');
        }

        return view('transaksi.editTransaksi', compact('transaksi', 'barang'));
    }

    public function update(Request $request, $id)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'id_barang' => 'required|exists:barang,id',
            'jumlah_terjual' => 'required|integer|min:1',
            'tanggal_transaksi' => 'required|date',
        ]);

        if ($validator->fails()) {
            return redirect()->route('transaksi.edit', $id)
                ->withErrors($validator)
                ->withInput();
        }

        // Find the transaksi by ID
        $transaksi = Transaksi::find($id);

        if (!$transaksi) {
            return redirect()->route('transaksi.index')->with('error', 'Transaksi not found.');
        }

        // Get the old barang and revert its stock
        $oldBarang = Barang::find($transaksi->id_barang);
        if ($oldBarang) {
            $oldBarang->stok += $transaksi->jumlah_terjual;
            $oldBarang->save();
        }

        // Update the transaksi with new data
        $transaksi->id_barang = $request->id_barang;
        $transaksi->jumlah_terjual = $request->jumlah_terjual;
        $transaksi->tanggal_transaksi = $request->tanggal_transaksi;
        $transaksi->save();

        // Get the new barang and update its stock
        $newBarang = Barang::find($request->id_barang);
        if ($newBarang) {
            $newBarang->stok -= $request->jumlah_terjual;
            $newBarang->save();
        } else {
            return redirect()->route('transaksi.index')->with('error', 'Barang not found.');
        }

        return redirect()->route('transaksi.index')->with('success', 'Transaksi updated successfully.');
    }


    public function destroy($id)
    {
        $transaksi = Transaksi::find($id);

        if (!$transaksi) {
            return redirect()->route('transaksi.index')->with('error', 'Transaksi not found.');
        }

        $barang = Barang::find($transaksi->id_barang);

        if ($barang) {
            $barang->stok += $transaksi->jumlah_terjual;
            $barang->save();
        } else {
            return redirect()->route('transaksi.index')->with('error', 'Barang not found.');
        }

        $transaksi->delete();

        return redirect()->route('transaksi.index');
    }
}
