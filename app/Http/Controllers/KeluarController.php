<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Keluar;
use App\Models\Sparepart;

class KeluarController extends Controller
{
    public function index()
    {
        $barangKeluar = Keluar::with('sparepart')->get();
        $sparepart = Sparepart::all(); // Ambil semua sparepart

        return view('barang_keluar.index', compact('barangKeluar', 'sparepart'));
    }

    public function create()
    {
        $spareparts = Sparepart::all();
        return view('barang_keluar.create', compact('spareparts'));
    }

    public function store(Request $request)
{
    $request->validate([
        'kode_keluar' => 'required|string|max:6|unique:barang_keluar,kode_keluar',
        'kode_sparepart' => 'required|string|max:6|exists:sparepart,kode_sparepart',
        'jumlah' => 'required|integer|min:1',
        'tanggal_keluar' => 'required|date',
    ]);

    $sparepart = Sparepart::where('kode_sparepart', $request->kode_sparepart)->first();

    if ($sparepart->stok < $request->jumlah) {
        return redirect()->back()->withErrors(['jumlah' => 'Stok tidak mencukupi'])->withInput();
    }

    // kurangin stok
    $sparepart->stok -= $request->jumlah;
    $sparepart->save();

    Keluar::create([
        'kode_keluar' => $request->kode_keluar,
        'kode_sparepart' => $request->kode_sparepart,
        'jumlah' => $request->jumlah,
        'tanggal_keluar' => $request->tanggal_keluar,
    ]);

    session()->flash('success', 'Data barang keluar berhasil ditambahkan.');
    return response()->json(['success' => true, 'message' => 'Data berhasil disimpan!']);

}




public function edit($kode_keluar)
{
    $keluar = Keluar::with('sparepart')->where('kode_keluar', $kode_keluar)->first();

    if (!$keluar) {
        return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
    }

    return response()->json(['success' => true, 'keluar' => $keluar]);
}


    public function update(Request $request, $id)
{
    $keluar = Keluar::findOrFail($id);
    $sparepart = Sparepart::where('kode_sparepart', $keluar->kode_sparepart)->first();

    // Kembalikan stok sebelum update
    $sparepart->stok += $keluar->jumlah;
    $sparepart->save();

    // Ambil sparepart baru jika kode sparepart diubah
    if ($request->kode_sparepart != $keluar->kode_sparepart) {
        $sparepartBaru = Sparepart::where('kode_sparepart', $request->kode_sparepart)->first();
    } else {
        $sparepartBaru = $sparepart;
    }

    // Cek apakah stok mencukupi
    if ($sparepartBaru->stok < $request->jumlah) {
        return response()->json([
            'success' => false,
            'message' => 'Stok tidak mencukupi',
            'errors' => ['jumlah' => 'Stok tidak mencukupi']
        ], 400);
    }

    // Kurangi stok dengan jumlah baru
    $sparepartBaru->stok -= $request->jumlah;
    $sparepartBaru->save();

    // Update data barang keluar
    $keluar->update([
        'kode_sparepart' => $request->kode_sparepart,
        'jumlah' => $request->jumlah,
        'tanggal_keluar' => $request->tanggal_keluar,
    ]);

    session()->flash('success', 'Data barang keluar berhasil diperbarui.');
    return response()->json(['success' => true, 'message' => 'Data berhasil disimpan!']);

}

    public function destroy($id)
    {
        $keluar = Keluar::findOrFail($id);
        $sparepart = Sparepart::where('kode_sparepart', $keluar->kode_sparepart)->first();

        // refresh jumlah
        $sparepart->stok += $keluar->jumlah;
        $sparepart->save();

        $keluar->delete();

        return response()->json(['success' => true, 'message' => 'Data berhasil dihapus!']);

    }

    public function invoice()
    {
        $barangKeluar = Keluar::with('sparepart')->get();
        return view('barang_keluar.invoice', compact('barangKeluar'));
    }
}
