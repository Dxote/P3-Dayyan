<?php

namespace App\Http\Controllers;

use App\Http\Requests\GeneralRequest;
use App\Models\Service;
use App\Models\ServiceSparepart;
use App\Models\ServiceAlat;
use App\Models\Alat;
use App\Models\Brand;
use App\Models\SparePart;
use App\User;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $service = Service::all();
        $alat = Alat::all();
        $brand = Brand::all();
        $sparepart = SparePart::all();
        $pengguna = User::where('level', 'pengguna')->get();
        $petugas = User::where('level', 'petugas')->get();

        return view('service.index', compact('service', 'alat', 'brand', 'sparepart', 'pengguna', 'petugas'));
    }

    
    public function store(GeneralRequest $request)
    {
        $requestData = request()->all();
        $service = Service::create([
            'kode_service' => $request->kode_service,
            'plat_nomor' => $request->plat_nomor,
            'nama_motor' => $request->nama_motor,
            'kode_brand' => $request->kode_brand,
            'deskripsi_masalah' => $request->deskripsi_masalah,
            'user_id' => $request->user_id,
            'petugas_id' => $request->petugas_id,
        ]);

        // Simpan data sparepart ke pivot
        if ($request->sparepart) {
            foreach ($request->sparepart as $index => $kode_sparepart) {
                ServiceSparepart::create([
                    'kode_service_sparepart' => $service->kode_service . '-SP' . ($index + 1),
                    'kode_service' => $service->kode_service,
                    'kode_sparepart' => $kode_sparepart,
                    'jumlah' => $request->jumlah_sparepart[$index] ?? 1, // Default 1 jika tidak diisi
                ]);
            }
        }

        // Simpan data alat ke pivot
        if ($request->alat) {
            foreach ($request->alat as $index => $kode_alat) {
                ServiceAlat::create([
                    'kode_service_alat' => $service->kode_service . '-AL' . ($index + 1),
                    'kode_service' => $service->kode_service,
                    'kode_alat' => $kode_alat,
                ]);
            }
        }

        return redirect()->route('service.index')->with('message', 'Data berhasil ditambahkan!');
    }

            public function edit($kode_service)
        {
            $service = Service::with(['serviceSpareparts.sparepart', 'serviceAlat.alat'])->findOrFail($kode_service);

            return response()->json([
                'service' => $service,
                'selected_sparepart' => $service->serviceSpareparts->pluck('kode_sparepart')->toArray(),
                'selected_alat' => $service->serviceAlat->pluck('kode_alat')->toArray(),
            ]);
        }
        
    public function update(GeneralRequest $request, $kode_service)
    {
        $service = Service::findOrFail($kode_service);
        $service->update([
            'plat_nomor' => $request->plat_nomor,
            'nama_motor' => $request->nama_motor,
            'kode_brand' => $request->kode_brand,
            'deskripsi_masalah' => $request->deskripsi_masalah,
            'user_id' => $request->user_id,
            'petugas_id' => $request->petugas_id,
        ]);

        // Hapus data lama
        ServiceSparepart::where('kode_service', $kode_service)->delete();
        ServiceAlat::where('kode_service', $kode_service)->delete();

        // Simpan data baru
        if ($request->sparepart) {
            foreach ($request->sparepart as $index => $kode_sparepart) {
                ServiceSparepart::create([
                    'kode_service_sparepart' => $kode_service . '-SP' . ($index + 1),
                    'kode_service' => $kode_service,
                    'kode_sparepart' => $kode_sparepart,
                    'jumlah' => $request->jumlah_sparepart[$index] ?? 1,
                ]);
            }
        }

        if ($request->alat) {
            foreach ($request->alat as $index => $kode_alat) {
                ServiceAlat::create([
                    'kode_service_alat' => $kode_service . '-AL' . ($index + 1),
                    'kode_service' => $kode_service,
                    'kode_alat' => $kode_alat,
                ]);
            }
        }

        return redirect()->route('service.index')->with('message', 'Data berhasil diperbarui!');
    }

    public function destroy($kode_service)
    {
        $service = Service::findOrFail($kode_service);
    
        // Hapus data terkait di tabel pivot
        ServiceSparepart::where('kode_service', $kode_service)->delete();
        ServiceAlat::where('kode_service', $kode_service)->delete();
    
        // Hapus data service utama
        $service->delete();
    
        return response()->json(['message' => 'Data berhasil dihapus!']);
    }
            public function riwayat()
        {
            $userId = auth()->id(); // Ambil ID user yang sedang login
            $services = Service::where('user_id', $userId)->with(['petugas', 'serviceSpareparts.sparepart', 'serviceAlat.alat'])->get();

            return view('service.riwayat', compact('services'));
        }

}
