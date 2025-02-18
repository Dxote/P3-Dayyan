<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shift;
use App\User;
use Carbon\Carbon;

class ShiftController extends Controller
{
    public function index()
    {
        $shifts = Shift::with('user')->get();
        return view('shift.index', compact('shifts'));
    }

    public function create()
    {
        $users = User::where('level', 'petugas')->get();
        return view('shift.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_shift' => 'required|string|max:10|unique:shift,kode_shift',
            'user_id' => 'required|exists:users,id',
            'tanggal_shift' => 'required|date',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        Shift::create($request->all());

        return redirect()->route('shift.index')->with('success', 'Shift berhasil ditambahkan.');
    }

    public function edit($kode_shift)
    {
        $shift = Shift::findOrFail($kode_shift);
        $users = User::where('role', 'pegawai')->get();
        return view('shift.edit', compact('shift', 'users'));
    }

    public function update(Request $request, $kode_shift)
    {
        $shift = Shift::findOrFail($kode_shift);

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tanggal_shift' => 'required|date',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        $shift->update($request->all());

        return redirect()->route('shift.index')->with('success', 'Shift berhasil diperbarui.');
    }

    public function destroy($kode_shift)
    {
        $shift = Shift::findOrFail($kode_shift);
        $shift->delete();

        return redirect()->route('shift.index')->with('success', 'Shift berhasil dihapus.');
    }

    public function invoice()
    {
        $shifts = Shift::with('user')->get();
        return view('shift.invoice', compact('shifts'));
    }
}
