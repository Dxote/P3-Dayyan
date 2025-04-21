<?php

namespace App\Http\Controllers;

use App\Models\PosManagement;
use App\Models\Outlet;
use App\Models\Pegawai;
use App\Models\Supervisor;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PosManagementController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $idOutlet = null;

        // Menentukan id_outlet berdasarkan peran pengguna
        if ($user->role === 'pegawai') {
            $pegawai = Pegawai::where('id_user', $user->id)->first();
            $idOutlet = $pegawai ? $pegawai->id_outlet : null;
        } elseif ($user->role === 'supervisor') {
            $supervisor = Supervisor::where('id_user', $user->id)->first();
            $idOutlet = $supervisor ? $supervisor->id_outlet : null;
        }

        // Mengambil data promo berdasarkan peran pengguna
        if (in_array($user->role, ['pegawai', 'supervisor']) && $idOutlet) {
            $promos = PosManagement::where(function($query) use ($idOutlet) {
                $query->where('id_outlet', $idOutlet)
                      ->orWhereIn('tipe', ['member', 'general']);
            })->get();
        } else {
            // Admin dapat melihat semua data promo
            $promos = PosManagement::all();
        }

        $outlets = Outlet::all();
        return view('pos.index', compact('promos', 'outlets'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipe' => 'required|string',
            'id_outlet' => 'nullable|string',
            'diskon' => 'required|numeric|min:0',
            'satuan_diskon' => 'required|string|in:persen,nominal',
            'tanggal_mulai' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        $validated['id_pos'] = (string) Str::uuid();

        PosManagement::create($validated);

        return response()->json(['success' => true]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'tipe' => 'required|string',
            'id_outlet' => 'nullable|string',
            'diskon' => 'required|numeric|min:0',
            'satuan_diskon' => 'required|string|in:persen,nominal',
            'tanggal_mulai' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        $promo = PosManagement::findOrFail($id);
        $promo->update($validated);

        return response()->json(['success' => true]);
    }

    public function edit($id)
    {
        $promo = PosManagement::findOrFail($id);
        return response()->json(['promo' => $promo]);
    }
    public function destroy($id)
    {
    $user = auth()->user();
    $idOutlet = null;

    // Ambil id_outlet berdasarkan role
    if ($user->role === 'pegawai') {
        $pegawai = \App\Models\Pegawai::where('id_user', $user->id)->first();
        $idOutlet = $pegawai ? $pegawai->id_outlet : null;
    } elseif ($user->role === 'supervisor') {
        $supervisor = \App\Models\Supervisor::where('id_user', $user->id)->first();
        $idOutlet = $supervisor ? $supervisor->id_outlet : null;
    }

    // Ambil promo yang akan dihapus
    $promo = PosManagement::findOrFail($id);

    // Batasi akses: hanya admin atau outlet yang sama yang bisa hapus
    if ($user->role !== 'admin' && $promo->id_outlet !== $idOutlet) {
        return response()->json(['error' => 'Tidak diizinkan menghapus promo ini.'], 403);
    }

    // Hapus promo
    $promo->delete();

    return response()->json(['success' => true]);
    }
 
}
