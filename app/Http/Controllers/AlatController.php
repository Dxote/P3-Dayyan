<?php

namespace App\Http\Controllers;

use App\Http\Requests\GeneralRequest;
use App\Models\Alat;
use Illuminate\Http\Request;

class AlatController extends Controller
{
    public function index()
    {
        $alat = Alat::all();
        return view('alat.index', compact('alat'));
    }

    public function store(GeneralRequest $request)
    {
        Alat::create([
            'kode_alat' => $request->kode_alat,
            'nama_alat' => $request->nama_alat,
        ]);

        return redirect()->route('alat.index')->with('message', 'Data berhasil ditambahkan!');
    }

    public function edit($kode_alat)
{
    $alat = Alat::findOrFail($kode_alat);
    return response()->json($alat);
}

public function update(GeneralRequest $request, $kode_alat)
{
    $alat = Alat::findOrFail($kode_alat);
    
    $alat->update([
        'kode_alat' => $request->kode_alat,
        'nama_alat' => $request->nama_alat
    ]);

    return redirect()->route('alat.index')->with('message', 'Data berhasil diperbarui!');
}


    public function destroy($kode_alat)
    {
        Alat::findOrFail($kode_alat)->delete();
        return redirect()->route('alat.index')->with('message', 'Data berhasil dihapus!');
    }

    public function getAlat($kode_alat)
    {
    $alat = Alat::where('kode_alat', $kode_alat)->firstOrFail();
    return response()->json($alat);
    }

    public function invoice()
    {
        $alat = Alat::all();
        return view('alat.invoice', compact('alat'));
    }
}
