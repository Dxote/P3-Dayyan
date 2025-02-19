<?php

namespace App\Http\Controllers;

use App\Http\Requests\GeneralRequest;
use App\Models\Service;
use App\Models\Sparepart;
use App\Models\Alat;
use App\Models\Brand; 
use App\User;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
{
    $service = Service::with(['spareparts', 'alat', 'user', 'petugas', 'brand'])->get();
    $spareparts = Sparepart::all();
    $alat = Alat::all();
    $users = User::where('level', 'pengguna')->get();
    $petugas = User::where('level', 'petugas')->get();
    $brand = Brand::all(); // Tambahkan ini

    return view('service.index', compact('service', 'spareparts', 'alat', 'users', 'petugas', 'brand'));

}

public function store(GeneralRequest $request)
{
    $kode_service = autonumber('service', 'kode_service', 3, 'SVC'); // Generate kode unik

    // Simpan data utama
    $service = Service::create([
        'kode_service' => $kode_service,
        'plat_nomor' => $request->plat_nomor,
        'nama_motor' => $request->nama_motor,
        'kode_brand' => $request->kode_brand,
        'deskripsi_masalah' => $request->deskripsi_masalah,
        'user_id' => $request->kode_user, // Sesuaikan dengan nama field di database
        'petugas_id' => $request->kode_petugas,
        'sparepart' => $request->spareparts ?? [], // Set default array kosong jika tidak ada
        'alat' => $request->alat ?? [], // Set default array kosong jika tidak ada
        
    ]);

    // Attach spareparts jika ada
    if ($request->has('spareparts') && is_array($request->spareparts)) {
        $service->spareparts()->attach($request->spareparts);
    }

    // Attach alat jika ada
    if ($request->has('alat') && is_array($request->alat)) {
        $service->alat()->attach($request->alat);
    }

    return response()->json(['message' => 'Data berhasil ditambahkan!', 'data' => $service]);
}

public function edit($id)
{
    $service = Service::with(['spareparts', 'alat', 'user', 'petugas', 'brand'])->findOrFail($id);
    return response()->json($service);
}


public function update(GeneralRequest $request, $id)
{
    $service = Service::findOrFail($id);
    $service->update([
        'plat_nomor' => $request->plat_nomor,
        'nama_motor' => $request->nama_motor,
        'kode_brand' => $request->kode_brand,
        'deskripsi_masalah' => $request->deskripsi_masalah,
        'user_id' => $request->kode_user,
        'petugas_id' => $request->kode_petugas,
        'sparepart' => $request->spareparts ?? [],
    ]);

    $service->spareparts()->sync($request->spareparts ?? []);
    $service->alat()->sync($request->alat ?? []);

    return response()->json(['message' => 'Data berhasil diperbarui!', 'data' => $service]);
}



public function destroy($id)
{
    $service = Service::findOrFail($id);

    if ($service) {
        $service->spareparts()->detach();
        $service->alat()->detach();
        $service->delete();
    }

    return response()->json(['message' => 'Data berhasil dihapus!']);
}

}
