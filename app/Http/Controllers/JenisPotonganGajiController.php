<?php

namespace App\Http\Controllers;

use App\Models\JenisPotonganGaji;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class JenisPotonganGajiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('potongan_gaji.jenis.index');
    }

    public function getJenis(Request $request)
    {
        if ($request->ajax()) {
            $data = JenisPotonganGaji::latest()->get();
            return DataTables::of($data)
                ->addColumn('action', function ($row) {
                    $btn = '<button class="btn btn-warning btn-sm btn-edit" data-id="' . $row->id . '"><i class="bi bi-pencil"></i></button>';
                    $btn .= ' <button class="btn btn-danger btn-sm btn-delete" data-id="' . $row->id . '"><i class="bi bi-trash"></i></button>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return response()->json(['error' => 'Unauthorized Access']);
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
    $request->validate([
        'jenis_potongan' => 'required|string|max:255',
        'jumlah' => 'required|numeric',
    ]);

    $jenisPotonganGaji = JenisPotonganGaji::create($request->all());

    return response()->json(['success' => 'Jenis potongan gaji ditambahkan dengan sukses.']);
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
    public function edit($id)
    {
        $jenisPotonganGaji = JenisPotonganGaji::find($id);
        return response()->json($jenisPotonganGaji);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'jenis_potongan' => 'required|string|max:255',
            'jumlah' => 'required|numeric',
        ]);

        $jenisPotonganGaji = JenisPotonganGaji::find($id);
        $jenisPotonganGaji->update($request->all());

        return response()->json(['success' => 'Jenis Potongan Gaji updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        JenisPotonganGaji::find($id)->delete();
        return response()->json(['success' => 'Jenis Potongan Gaji deleted successfully.']);
    }
}
