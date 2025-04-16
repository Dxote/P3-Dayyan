<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\User;
use App\Models\Supervisor;

class SupervisorController extends Controller
{
    public function index()
    {
        $supervisors = Supervisor::with('user')->get();
        $users = User::where('role', 'user')->get();
        return view('supervisor.index', compact('supervisors', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_user' => 'required|unique:supervisor,id_user',
        ]);

        $supervisor = Supervisor::create([
            'id_supervisor' => 'SPV-' . Str::random(6),
            'id_user' => $request->id_user,
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
            'user' => $supervisor->user // ini penting untuk akses nama user di JS
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
