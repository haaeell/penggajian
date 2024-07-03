<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Jabatan;
use App\Models\Karyawan;
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
        $karyawan = Auth::user()->karyawan;
        $jabatanCount = Jabatan::count();
        $karyawanCount = Karyawan::count();
        return view('home', compact('jabatanCount', 'karyawanCount','karyawan'));
    }
}
