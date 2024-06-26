<?php

namespace App\Http\Controllers;

use App\Models\Penggajian;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Carbon::setLocale('id'); 

        $query = Penggajian::with('karyawan.user', 'karyawan.jabatan');

        if ($request->filled('bulan')) {
            $query->whereMonth('bulan', $request->bulan);
        }

        if ($request->filled('tahun')) {
            $query->whereYear('bulan', $request->tahun);
        }

        $penggajianData = $query->get();

        return view('laporan.index', compact('penggajianData'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
