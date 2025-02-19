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

    $service = Service::create([
        'kode_service' => $kode_service,
        'plat_nomor' => $request->plat_nomor,
        'nama_motor' => $request->nama_motor,
        'kode_brand' => $request->kode_brand,
        'deskripsi_masalah' => $request->deskripsi_masalah,
        'kode_user' => $request->kode_user,
        'kode_petugas' => $request->kode_petugas,
    ]);

    // **Cek jika spareparts ada, baru attach**
    if (!empty($request->spareparts)) {
        $service->spareparts()->attach($request->spareparts);
    }

    // **Cek jika alat ada, baru attach**
    if (!empty($request->alat)) {
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
        'kode_user' => $request->kode_user,
        'kode_petugas' => $request->kode_petugas,
    ]);

    // **Pastikan spareparts & alat tidak null sebelum sync()**
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
