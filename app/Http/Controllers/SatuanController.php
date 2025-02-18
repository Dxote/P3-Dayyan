<?php

namespace App\Http\Controllers;

use App\Http\Requests\GeneralRequest;
use App\Models\Satuan;
use Illuminate\Http\Request;

class SatuanController extends Controller
{
    public function index()
    {
        $satuan = Satuan::all();
        return view('satuan.index', compact('satuan'));
    }

    public function store(GeneralRequest $request)
    {
        Satuan::create([
            'kode_satuan' => $request->kode_satuan,
            'nama_satuan' => $request->nama_satuan,
        ]);

        return redirect()->route('satuan.index')->with('message', 'Data berhasil ditambahkan!');
    }
    public function edit($kode_satuan)
{
    $satuan = Satuan::findOrFail($kode_satuan);
    return response()->json($satuan);
}

public function update(GeneralRequest $request, $kode_satuan)
{
    $satuan = Satuan::findOrFail($kode_satuan);
    
    $satuan->update([
        'kode_satuan' => $request->kode_satuan,
        'nama_satuan' => $request->nama_satuan
    ]);

    return redirect()->route('satuan.index')->with('message', 'Data berhasil diperbarui!');
}

    public function destroy($kode_satuan)
    {
        Satuan::findOrFail($kode_satuan)->delete();
        return redirect()->route('satuan.index')->with('message', 'Data berhasil dihapus!');
    }

    public function getSatuan($kode_satuan)
    {
        $satuan = Satuan::findOrFail($kode_satuan);
        return response()->json($satuan);
    }

    public function invoice(){
        {
            $satuan = Satuan::all();
            return view('satuan.invoice', compact('satuan'));
        }
    }

}
