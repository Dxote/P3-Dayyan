<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shift;
use App\User;

class ShiftController extends Controller
{
    public function index()
    {
        $shifts = Shift::with('user')->get();
        $users = User::all();

        return view('shift.index', compact('shifts', 'users'));
    }

    public function create()
{
    $users = User::whereHas('role', function ($query) {
        $query->where('name', 'petugas');
    })->get();

    return view('shift.index', compact('users'));
}

    public function store(Request $request)
    {
        $request->validate([
            'kode_shift' => 'required|unique:shift,kode_shift|max:10',
            'user_id' => 'required|exists:users,id',
            'tanggal_shift' => 'required|date',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        Shift::create([
            'kode_shift' => $request->kode_shift,
            'user_id' => $request->user_id,
            'tanggal_shift' => $request->tanggal_shift,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
        ]);

        return redirect()->route('shift.index')->with('message', 'Shift berhasil ditambahkan!');
    }

    public function edit($kode_shift)
    {
        $shift = Shift::findOrFail($kode_shift);
        $users = User::all();

        return response()->json([
            'shift' => $shift,
            'users' => $users,
        ]);
    }

    public function update(Request $request, $kode_shift)
    {
        $shift = Shift::where('kode_shift', $kode_shift)->firstOrFail();
    
        $shift->update([
            'user_id' => $request->user_id,
            'tanggal_shift' => $request->tanggal_shift,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
        ]);
    
        return redirect()->route('shift.index')->with('message', 'Shift berhasil diperbarui!');
    }
    

    public function destroy($kode_shift)
    {
        Shift::findOrFail($kode_shift)->delete();
        return redirect()->route('shift.index')->with('message', 'Shift berhasil dihapus!');
    }

    public function invoice()
    {
        $shifts = Shift::with('user')->get();
        $users = User::all();

        return view('shift.invoice', compact('shifts', 'users'));
    }
}
