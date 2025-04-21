<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Outlet;
use App\Models\Layanan;
use App\Models\Setting;

class OutletController extends Controller
{
    public function index()
    {
        $setting = Setting::first(); 
        $outlets = Outlet::all(); 
        $layanans = Layanan::all();
        return view('outlet.index', compact('setting', 'outlets', 'layanans'));
    }

    public function store(Request $request)
    {
        $layanan = implode(',', $request->id_layanan); 

        Outlet::create([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'no_telp' => $request->no_telp,
            'id_layanan' => $layanan,
        ]);

        return response()->json(['success' => true]);
    }
    public function edit($id)
    {
        $outlet = Outlet::findOrFail($id);
        $outlet->layanan_array = explode(',', $outlet->id_layanan); 
    
        return response()->json(['outlet' => $outlet]);
    }
    
    
    public function update(Request $request, $id)
    {
        $outlet = Outlet::findOrFail($id);

        $layanan = implode(',', $request->id_layanan); // gabungkan jadi string

        $outlet->update([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'no_telp' => $request->no_telp,
            'id_layanan' => $layanan,
        ]);

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $outlet = Outlet::findOrFail($id);
        $outlet->delete();

        return response()->json(['success' => true]);
    }

    public function show($id)
    {
        $outlet = Outlet::findOrFail($id);
        $layanans = Layanan::whereIn('id_layanan', explode(',', $outlet->id_layanan))->get();

        return view('outlet.show', compact('outlet', 'layanans'));
    }
}
