<?php
namespace App\Http\Controllers;

use App\User;
use App\Models\Outlet; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $users = User::count();
        $outlets = Outlet::count();
        $user = Auth::user();

        if ($user->role == 'user') {
            return redirect()->route('user.dashboard');
        }

        $widget = [
            'users' => $users,
            'outlet' => $outlets,
        ];

        // Redirect ke halaman home biasa (admin/supervisor/pegawai)
        return view('home', compact('widget'));
    }
}
