<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\SettingRequest;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $setting = Setting::first(); // Mendapatkan data setting dari database

    return view('setting.index')->with('setting', $setting);
}


    /**
     * Show the form for creating a new resource.
     */
    /**
     * Store a newly created resource in storage.
     */
    /**
     * Display the specified resource.
     */
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id_setting)
    {
        {
            return view('setting.update', [
                'setting'    => Setting::find($id_setting)
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id_setting)
{
    // Temukan data yang akan diperbarui
    $setting = Setting::findOrFail($id_setting);

    // Validasi data dari request
    $validatedData = $request->validate([
        'nama_perusahaan' => 'required',
        'alamat' => 'required',
        'email' => 'required',
        'website' => 'required',
        'kodepos' => 'required',
        'telepon' => 'required',
        'path_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate image file
    ]);

    if ($request->hasFile('path_logo')) {
        // Hapus logo lama jika ada
        if ($setting->path_logo) {
            Storage::delete($setting->path_logo);
        }

        // Simpan file logo yang baru
        $validatedData['path_logo'] = $request->file('path_logo')->store('logos', 'public');
    }

    // Perbarui data dengan data yang valid
    $setting->update($validatedData);

    // Redirect kembali dengan pesan sukses
    return redirect()->route('setting.index')->with('success', 'Setting updated successfully.');
}
    /**
     * Remove the specified resource from storage.
     */
}
