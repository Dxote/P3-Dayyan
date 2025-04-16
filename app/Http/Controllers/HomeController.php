<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\Outlet; 
use Illuminate\Http\Request;

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

        $widget = [
            'users' => $users,
            'outlet' => $outlets,
            //...
        ];

        return view('home', compact('widget'));
    }
}
