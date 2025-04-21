<?php

namespace App\Http\Controllers;

use App\Models\Outlet;
use App\Models\Layanan;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PosManagement;
use App\Models\Member;

class UserController extends Controller
{
    public function index()
    {
        $setting = DB::table('setting')->first();
        $outlets = Outlet::all();
        
        return view('user.dashboard', compact('setting', 'outlets'));
    }

    public function getLayananByOutlet($outlet_id)
    {
        $outlet = Outlet::findOrFail($outlet_id);

        // Ambil layanan yang terkait dengan outlet yang dipilih
        $layanans = Layanan::whereIn('id_layanan', explode(',', $outlet->id_layanan))->get();

        return response()->json(['layanans' => $layanans]);
    }
    public function showOutlet($id)
    {
    $outlet = Outlet::findOrFail($id);
    $setting = DB::table('setting')->first();

    // Ambil layanan yang terkait dengan outlet
    $layanans = Layanan::whereIn('id_layanan', explode(',', $outlet->id_layanan))->get();

    // Ambil user saat ini
    $user = Auth::user();

    // Ambil semua promo yang aktif
    $today = now()->toDateString();

    $promos = PosManagement::where('tanggal_mulai', '<=', $today)
        ->where('tanggal_akhir', '>=', $today)
        ->get();

    // Hitung total diskon berdasarkan tipe
    $totalDiskonNominal = 0;
    $totalDiskonPersen = 0;

    foreach ($promos as $promo) {
        if ($promo->tipe == 'general') {
            if ($promo->satuan_diskon == 'nominal') {
                $totalDiskonNominal += $promo->diskon;
            } else {
                $totalDiskonPersen += $promo->diskon;
            }
        }

        if ($promo->tipe == 'outlet' && $promo->id_outlet == $outlet->id_outlet) {
            if ($promo->satuan_diskon == 'nominal') {
                $totalDiskonNominal += $promo->diskon;
            } else {
                $totalDiskonPersen += $promo->diskon;
            }
        }

        if ($promo->tipe == 'member' && $user && Member::where('id_user', $user->id)->exists()) {
            if ($promo->satuan_diskon == 'nominal') {
                $totalDiskonNominal += $promo->diskon;
            } else {
                $totalDiskonPersen += $promo->diskon;
            }
        }
    }

    return view('user.outlet-detail', compact('outlet', 'layanans', 'setting', 'totalDiskonNominal', 'totalDiskonPersen'));
    }


}
