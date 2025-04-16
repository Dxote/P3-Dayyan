<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Models\Admin;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        $admins = DB::table('admin')
            ->join('users', 'admin.id_user', '=', 'users.id')
            ->select('admin.id_admin', 'users.id as id_user', 'users.name', 'users.email')
            ->get();

        $users = User::where('role', '!=', 'admin')->get(); // hanya user non-admin

        return view('admin.index', compact('admins', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_user' => 'required|exists:users,id|unique:admin,id_user',
        ]);

        DB::table('admin')->insert([
            'id_admin' => Str::uuid(),
            'id_user' => $request->id_user,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        User::where('id', $request->id_user)->update(['role' => 'admin']);

        return response()->json(['success' => true]);
    }

    public function edit($id)
    {
        $admin = DB::table('admin')
            ->join('users', 'admin.id_user', '=', 'users.id')
            ->where('admin.id_admin', $id)
            ->select('admin.*', 'users.name as user_name', 'users.email as user_email')
            ->first();
    
        return response()->json(['admin' => $admin]);
    }
    
    public function destroy($id)
    {
        $admin = DB::table('admin')->where('id_admin', $id)->first();
        if ($admin) {
            User::where('id', $admin->id_user)->update(['role' => 'user']); // rollback ke user
            DB::table('admin')->where('id_admin', $id)->delete();
        }

        return response()->json(['success' => true]);
    }
}
