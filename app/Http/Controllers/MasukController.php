<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Masuk;
use App\Models\Sparepart;

class MasukController extends Controller
{

    public function index()
{
    $barangMasuk = Masuk::with('sparepart')->get(); // Pastikan ada relasi dengan Sparepart
    $sparepart = Sparepart::all(); // Ambil semua sparepart

    return view('barang_masuk.index', compact('barangMasuk', 'sparepart'));
}
    

    public function create()
    {
        $spareparts = Sparepart::all();
        return view('barang_masuk.create', compact('spareparts'));
    }

    public function store(Request $request)
{
    $request->validate([
        'kode_masuk' => 'required|string|max:6|unique:barang_masuk,kode_masuk',
        'kode_sparepart' => 'required|string|max:6|exists:sparepart,kode_sparepart',
        'jumlah' => 'required|integer|min:1',
        'tanggal_masuk' => 'required|date',
    ]);

    $masuk = Masuk::create($request->all());

    // Update stok sparepart
    $sparepart = Sparepart::where('kode_sparepart', $request->kode_sparepart)->first();
    $sparepart->stok += $request->jumlah;
    $sparepart->save();

    return response()->json([
        'success' => true,
        'barang' => [
            'kode_masuk' => $masuk->kode_masuk,
            'sparepart_nama' => $sparepart->nama_sparepart,
            'jumlah' => $masuk->jumlah,
            'tanggal_masuk' => $masuk->tanggal_masuk,
        ]
    ]);
}

public function edit($kode_masuk)
{
    $barangMasuk = Masuk::where('kode_masuk', $kode_masuk)->with('sparepart')->first();

    if (!$barangMasuk) {
        return response()->json(['error' => 'Data tidak ditemukan'], 404);
    }

    return response()->json(['masuk' => $barangMasuk]);
}


public function update(Request $request, $kode_masuk)
{
    $request->validate([
        'kode_sparepart' => 'required|string|max:6|exists:sparepart,kode_sparepart',
        'jumlah' => 'required|integer|min:1',
        'tanggal_masuk' => 'required|date',
    ]);

    $masuk = Masuk::where('kode_masuk', $kode_masuk)->firstOrFail();
    $sparepart = Sparepart::where('kode_sparepart', $masuk->kode_sparepart)->first();

    // Kembalikan stok lama sebelum perubahan
    $sparepart->stok -= $masuk->jumlah;
    $sparepart->save();

    // Update data barang masuk
    $masuk->update([
        'kode_sparepart' => $request->kode_sparepart,
        'jumlah' => $request->jumlah,
        'tanggal_masuk' => $request->tanggal_masuk,
    ]);

    // Update stok sparepart dengan jumlah baru
    $sparepart->stok += $request->jumlah;
    $sparepart->save();

    return response()->json(['success' => true, 'message' => 'Data barang masuk berhasil diperbarui.']);
}


    public function destroy($id)
{
    $masuk = Masuk::findOrFail($id);
    $sparepart = Sparepart::where('kode_sparepart', $masuk->kode_sparepart)->first();
    $sparepart->stok -= $masuk->jumlah;
    $sparepart->save();
    $masuk->delete();

    return response()->json(['success' => true]);
}

    public function invoice()
    {
        $barangMasuk = Masuk::with('sparepart')->get();
        return view('barang_masuk.invoice', compact('barangMasuk'));
    }
}
