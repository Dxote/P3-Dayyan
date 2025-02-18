<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\GeneralRequest;
use App\Models\Brand;

class BrandController extends Controller
{
    public function index()
    {
        $brand = Brand::all();
        return view('brand.index', compact('brand'));
    }

    public function store(GeneralRequest $request)
{
    Brand::create([
        'kode_brand' => $request->kode_brand,
        'brand' => $request->brand,
    ]);

    return redirect()->route('brand.index')->with('message', 'Data berhasil ditambahkan!');
}

public function edit($kode_brand)
{
    $brand = Brand::where('kode_brand', $kode_brand)->first();

    if (!$brand) {
        return response()->json(['error' => 'Data tidak ditemukan'], 404);
    }

    return response()->json($brand);
}
public function update(Request $request, $kode_brand)
{
    $request->validate([
        'brand' => 'required|string|max:255',
    ]);

    $brand = Brand::where('kode_brand', $kode_brand)->first();
    if (!$brand) {
        return response()->json(['error' => 'Data tidak ditemukan'], 404);
    }

    $brand->update([
        'brand' => $request->brand,
    ]);

    return redirect()->route('brand.index')->with('message', 'Brand berhasil diperbarui!');
}


public function destroy($kode_brand)
{
    Brand::findOrFail($kode_brand)->delete();
    return redirect()->route('brand.index')->with('message', 'Data berhasil dihapus!');
}

public function invoice(){
    {
        $brand = Brand::all();
        return view('brand.invoice', compact('brand'));
    }
    
}

public function detail($kode_brand)
{
    $brand = Brand::with('spareparts.satuan')->findOrFail($kode_brand);
    return view('brand.detail', compact('brand'));
}


}
