<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\User;
use App\Models\Pegawai;
use App\Models\Outlet;
use Illuminate\Support\Facades\Log;

class PegawaiController extends Controller
{
    public function index()
{
    $user = auth()->user();

    if ($user->role === 'admin') {
        // Admin general
        $pegawais = Pegawai::with(['user', 'outlet'])->get();
        $users = User::where('role', 'user')->get();
    } else {
        // Ambil idout dari pegawai atau supervisor
        $idOutlet = null;

        $pegawai = Pegawai::where('id_user', $user->id)->first();
        if ($pegawai) {
            $idOutlet = $pegawai->id_outlet;
        } else {
            // Cek supervisor
            $supervisor = \App\Models\Supervisor::where('id_user', $user->id)->first();
            if ($supervisor) {
                $idOutlet = $supervisor->id_outlet;
            }
        }
        // Jika id outlet ditemukan, filter pegawai berdasarkan outlet
        if ($idOutlet) {
            $pegawais = Pegawai::with(['user', 'outlet'])
                        ->where('id_outlet', $idOutlet)
                        ->get();
        } else {
            $pegawais = collect(); // Kosongkan jika outlet tidak ketemu
        }
        // Ambil semua user dengan role 'user' YANG:
        // - Belum jadi pegawai (tidak punya relasi di tabel pegawai)
        // - Atau, sudah jadi pegawai tapi dari outlet yang sama
        $users = User::where('role', 'user')
            ->where(function ($query) use ($idOutlet) {
                $query->whereDoesntHave('pegawai')
                      ->orWhereHas('pegawai', function ($q) use ($idOutlet) {
                          $q->where('id_outlet', $idOutlet);
                      });
            })->get();
    }

    $outlets = Outlet::all();

    return view('pegawai.index', compact('pegawais', 'users', 'outlets'));
}



    public function store(Request $request)
    {
        $request->validate([
            'id_user' => 'required|unique:pegawai,id_user',
            'id_outlet' => 'required',
            'jabatan' => 'required|string|max:100',
        ]);

        $pegawai = Pegawai::create([
            'id_pegawai' => 'PGW-' . Str::random(6),
            'id_user' => $request->id_user,
            'id_outlet' => $request->id_outlet,
            'jabatan' => $request->jabatan,
        ]);

        // Ubah role user menjadi pegawai
        $user = User::find($request->id_user);
        $user->role = 'pegawai';
        $user->save();

        return response()->json(['success' => true]);
    }

    public function edit($id)
    {
    $pegawai = Pegawai::with(['user', 'outlet'])->findOrFail($id);

    // Kirim juga data user & outlet secara eksplisit agar bisa digunakan di JS
    return response()->json([
        'pegawai' => $pegawai,
        'user' => $pegawai->user,
        'outlet' => $pegawai->outlet,
    ]);
    }
    public function update(Request $request, $id)
    {
        $pegawai = Pegawai::findOrFail($id);

        // Rollback role user sebelumnya
        $oldUser = User::find($pegawai->id_user);
        $oldUser->role = 'user';
        $oldUser->save();

        $pegawai->update([
            'id_user' => $request->id_user,
            'id_outlet' => $request->id_outlet,
            'jabatan' => $request->jabatan,
        ]);

        // Ubah role user baru
        $newUser = User::find($request->id_user);
        $newUser->role = 'pegawai';
        $newUser->save();

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $pegawai = Pegawai::findOrFail($id);

        // Rollback role user
        $user = User::find($pegawai->id_user);
        $user->role = 'user';
        $user->save();

        $pegawai->delete();

        return response()->json(['success' => true]);
    }
}
