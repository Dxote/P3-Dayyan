<?php
namespace App\Http\Controllers;

use App\Models\Layanan;
use App\Http\Requests\GeneralRequest;

class LayananController extends Controller
{
    public function index()
    {
        $layanan = Layanan::all();
        return view('layanan.index', compact('layanan'));
    }

    public function store(GeneralRequest $request)
    {
        $request->validate([
            'nama_layanan' => 'required',
            'jenis' => 'required',
            'harga' => 'required|numeric',
        ]);

        Layanan::create($request->all());
        return response()->json(['success' => true]);
    }

    public function edit($id)
    {
        $layanan = Layanan::findOrFail($id);
        return response()->json(['layanan' => $layanan]);
    }

    public function update(GeneralRequest $request, $id)
    {
        $layanan = Layanan::findOrFail($id);
        $layanan->update($request->all());
        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        Layanan::destroy($id);
        return response()->json(['success' => true]);
    }
}
