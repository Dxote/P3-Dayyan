<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;


class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('profile');
    }

    public function update(Request $request)
    {
        $user = User::findOrFail(Auth::user()->id); // Menggunakan User::findOrFail

        $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'telepon' => 'nullable|min:8',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|max:12|required_with:current_password',
            'password_confirmation' => 'nullable|min:8|max:12|required_with:new_password|same:new_password',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:8192'
        ]);

        if ($request->filled('current_password')) {
            if (Hash::check($request->current_password, $user->password)) {
                $user->password = Hash::make($request->new_password);
            } else {
                return back()->withErrors(['current_password' => 'Current password is incorrect']);
            }
        }

        if ($request->hasFile('foto')) {
            if ($user->foto) {
                Storage::delete('public/' . $user->foto);
            }
            $path = $request->file('foto')->store('profile_pictures', 'public');
            $user->foto = $path;
        }

        $user->name = $request->input('name');
        $user->last_name = $request->input('last_name');
        $user->email = $request->input('email');
        $user->telepon = $request->input('telepon');

        try {
            $user->save();
        } catch (\Exception $e) {
            Log::error('Failed to update user profile: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to update profile. Please try again later.']);
        }

        return redirect()->route('profile')->with('status', 'Profile updated successfully.');
    }
}

