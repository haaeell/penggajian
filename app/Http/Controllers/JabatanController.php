<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class JabatanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('jabatan.index');
    }

    public function getJabatans()
    {
        $jabatans = Jabatan::select(['id', 'jabatan', 'gaji_per_hari', 'tunjangan_transportasi', 'uang_makan','tunjangan_jabatan']);
        return DataTables::of($jabatans)
            ->addColumn('action', function ($jabatan) {
                return '
                    <button class="btn rounded-pill btn-warning btn-sm btn-edit" data-id="' . $jabatan->id . '"><i class="bi bi-pencil"></i></button>
                    <button class="btn rounded-pill btn-danger btn-sm btn-delete" data-id="' . $jabatan->id . '"><i class="bi bi-trash"></i></button>
                ';
            })
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'jabatan' => 'required|string|max:255',
            'gaji_per_hari' => 'required|numeric',
            'tunjangan_transportasi' => 'required|numeric',
            'uang_makan' => 'required|numeric',
            'tunjangan_jabatan' => 'required|numeric',
        ]);

        $jabatan = Jabatan::create($request->all());

        return response()->json(['success' => 'Jabatan saved successfully.']);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jabatan' => 'required|string|max:255',
            'gaji_per_hari' => 'required|numeric',
            'tunjangan_transportasi' => 'required|numeric',
            'uang_makan' => 'required|numeric',
            'tunjangan_jabatan' => 'required|numeric',
        ]);

        $jabatan = Jabatan::find($id);
        $jabatan->update($request->all());

        return response()->json(['success' => 'Jabatan updated successfully.']);
    }

    public function edit($id)
    {
        $jabatan = Jabatan::find($id);
        return response()->json($jabatan);
    }
    public function show($id)
    {
        $jabatan = Jabatan::find($id);
        return response()->json($jabatan);
    }
    


    public function destroy($id)
    {
        Jabatan::find($id)->delete();
        return response()->json(['success' => 'Jabatan deleted successfully.']);
    }
}
