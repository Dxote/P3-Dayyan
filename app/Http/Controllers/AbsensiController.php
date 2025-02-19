<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\Shift;
use App\User;

class AbsensiController extends Controller
{
    public function index()
    {
        $absensi = Absensi::with('user', 'shift')->get();
        $users = User::all();
        $shifts = Shift::all();

        return view('absensi.index', compact('absensi', 'users', 'shifts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_absen' => 'required|unique:absensi,kode_absen|max:6',
            'user_id' => 'required|exists:users,id',
            'kode_shift' => 'required|exists:shift,kode_shift',
            'tanggal_absen' => 'required|date',
            'jam_absen' => 'nullable|date_format:H:i',
            'status' => 'required|in:hadir,izin,sakit,tanpa keterangan',
        ]);

        Absensi::create([
            'kode_absen' => $request->kode_absen,
            'user_id' => $request->user_id,
            'kode_shift' => $request->kode_shift,
            'tanggal_absen' => $request->tanggal_absen,
            'jam_absen' => $request->jam_absen,
            'status' => $request->status,
        ]);

        return redirect()->route('absensi.index')->with('message', 'Absensi berhasil ditambahkan!');
    }

    public function edit($kode_absen)
    {
        $absensi = Absensi::findOrFail($kode_absen);
        $users = User::all();
        $shifts = Shift::all();

        return response()->json([
            'absensi' => $absensi,
            'users' => $users,
            'shifts' => $shifts,
        ]);
    }

    public function update(Request $request, $kode_absen)
    {
        $absensi = Absensi::where('kode_absen', $kode_absen)->firstOrFail();

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'kode_shift' => 'required|exists:shift,kode_shift',
            'tanggal_absen' => 'required|date',
            'jam_absen' => 'nullable|date_format:H:i',
            'status' => 'required|in:hadir,izin,sakit,tanpa keterangan',
        ]);

        $absensi->update([
            'user_id' => $request->user_id,
            'kode_shift' => $request->kode_shift,
            'tanggal_absen' => $request->tanggal_absen,
            'jam_absen' => $request->jam_absen,
            'status' => $request->status,
        ]);

        return redirect()->route('absensi.index')->with('message', 'Absensi berhasil diperbarui!');
    }

    public function destroy($kode_absen)
    {
        Absensi::findOrFail($kode_absen)->delete();
        return redirect()->route('absensi.index')->with('message', 'Absensi berhasil dihapus!');
    }
}
