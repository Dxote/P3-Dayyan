<?php

namespace App\Http\Controllers;

use App\Http\Requests\GeneralRequest;
use App\Models\Sparepart;
use App\Models\Satuan;
use App\Models\Brand;
use Illuminate\Http\Request;

class SparepartController extends Controller
{
    public function index()
    {
        $sparepart = Sparepart::all();
        $satuan = Satuan::all();
        $brand = Brand::all();

        return view('sparepart.index', compact('sparepart', 'satuan', 'brand'));
    }

    public function store(GeneralRequest $request)
    {
        Sparepart::create([
            'kode_sparepart' => $request->kode_sparepart,
            'nama_sparepart' => $request->nama_sparepart,
            'stok' => $request->stok,
            'harga' => $request->harga,
            'jumlah_satuan' => $request->jumlah_satuan,
            'kode_satuan' => $request->kode_satuan,
            'kode_brand' => $request->kode_brand,
        ]);
    
        return redirect()->route('sparepart.index')->with('message', 'Data berhasil ditambahkan!');
        

        return redirect()->route('sparepart.index')->with('message', 'Data berhasil ditambahkan!');
    }

    public function edit($kode_sparepart)
    {
        $sparepart = Sparepart::findOrFail($kode_sparepart);
        $satuan = Satuan::all();
        $brand = Brand::all();
        
        return response()->json([
            'sparepart' => $sparepart,
            'satuan' => $satuan,
            'brand' => $brand,
        ]);
    }

    public function update(GeneralRequest $request, $kode_sparepart)
    {

        
        $sparepart = Sparepart::findOrFail($kode_sparepart);
        $sparepart->update([
            'kode_sparepart' => $request->kode_sparepart,
            'nama_sparepart' => $request->nama_sparepart,
            'stok' => $request->stok,
            'harga' => $request->harga,
            'jumlah_satuan' => $request->jumlah_satuan,
            'kode_satuan' => $request->kode_satuan,
            'kode_brand' => $request->kode_brand,
        ]);

        return redirect()->route('sparepart.index')->with('message', 'Data berhasil diperbarui!');
    }

    public function destroy($kode_sparepart)
    {
        Sparepart::findOrFail($kode_sparepart)->delete();
        return redirect()->route('sparepart.index')->with('message', 'Data berhasil dihapus!');
    }

    public function invoice()
    {
        $sparepart = Sparepart::all();
        return view('sparepart.invoice', compact('sparepart'));
    }
}
