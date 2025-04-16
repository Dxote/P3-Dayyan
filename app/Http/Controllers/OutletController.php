<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Outlet;
use App\Models\Layanan;

class OutletController extends Controller
{
    public function index()
    {
        $widget = [
            'users' => \App\Models\User::count(),
            'outlet' => Outlet::count(),
        ];

        $outlets = Outlet::all(); 
        $layanans = Layanan::all();
        return view('outlet.index', compact('outlets', 'layanans'));
    }

    public function store(Request $request)
    {
        $layanan = implode(',', $request->id_layanan); // gabungkan jadi string

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
        $outlet->layanan_array = explode(',', $outlet->id_layanan); // ubah jadi array
    
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
}
