<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\User;
use App\Models\Supervisor;
use App\Models\Outlet;


class SupervisorController extends Controller
{
    public function index()
    {
        $user = auth()->user();
    
        if ($user->role === 'admin') {
            // Admin bisa lihat semua supervisor
            $supervisors = Supervisor::with('user', 'outlet')->get();
            $users = User::where('role', 'user')->get();
        } else {
            // Ambil data supervisor yang sedang login
            $currentSupervisor = Supervisor::where('id_user', $user->id)->first();
    
            // Supervisor hanya bisa lihat sesama supervisor di outlet yang sama
            $supervisors = Supervisor::with('user', 'outlet')
                            ->where('id_outlet', $currentSupervisor->id_outlet)
                            ->get();
    
            // User biasa dari outlet yang sama (untuk ditambahkan jadi supervisor)
            $users = User::where('role', 'user')->whereHas('pegawai', function ($query) use ($currentSupervisor) {
                $query->where('id_outlet', $currentSupervisor->id_outlet);
            })->get();
        }
    
        $outlets = Outlet::all();
    
        return view('supervisor.index', compact('supervisors', 'users', 'outlets'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'id_user' => 'required|unique:supervisor,id_user',
            'id_outlet' => 'required|exists:outlet,id_outlet',
        ]);
        
    
        $supervisor = Supervisor::create([
            'id_supervisor' => 'SPV-' . Str::random(6),
            'id_user' => $request->id_user,
            'id_outlet' => $request->id_outlet,
        ]);
    
        // Ubah role user menjadi supervisor
        $user = User::find($request->id_user);
        $user->role = 'supervisor';
        $user->save();
    
        return response()->json(['success' => true]);
    }

    public function edit($id)
    {
        $supervisor = Supervisor::with('user')->findOrFail($id);
        return response()->json([
            'supervisor' => $supervisor,
            'user' => $supervisor->user ,
            'outlet' => $supervisor->outlet 
        ]);
    }

    public function update(Request $request, $id)
    {
        $supervisor = Supervisor::findOrFail($id);

        // Rollback role user sebelumnya
        $oldUser = User::find($supervisor->id_user);
        $oldUser->role = 'user';
        $oldUser->save();

        $supervisor->update([
            'id_user' => $request->id_user,
            'id_outlet' => $request->id_outlet, 
        ]);

        // Ubah role user baru
        $newUser = User::find($request->id_user);
        $newUser->role = 'supervisor';
        $newUser->save();

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $supervisor = Supervisor::findOrFail($id);

        // Rollback role user
        $user = User::find($supervisor->id_user);
        $user->role = 'user';
        $user->save();

        $supervisor->delete();

        return response()->json(['success' => true]);
    }
}
