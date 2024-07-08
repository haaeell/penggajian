<?php

namespace App\Http\Controllers;

use App\Imports\KaryawanImport;
use App\Models\Jabatan;
use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class KaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jabatans = Jabatan::all();
        return view('karyawan.index', compact('jabatans'));
    }

    public function getKaryawan()
    {
        $karyawan = Karyawan::select(['id', 'user_id', 'jabatan_id', 'nik', 'tanggal_bergabung', 'no_hp', 'no_rekening', 'alamat', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin'])
            ->with('user', 'jabatan');

        return DataTables::of($karyawan)
            ->addColumn('name', function ($karyawan) {
                return $karyawan->user->name ?? 'User not found';
            })
            ->addColumn('email', function ($karyawan) {
                return $karyawan->user->email ?? 'Email not found';
            })
            ->addColumn('jabatan', function ($karyawan) {
                return $karyawan->jabatan->jabatan ?? 'Jabatan not found';
            })
            ->addColumn('action', function ($karyawan) {
                return '
                        <button class="btn btn-warning btn-sm btn-edit" data-id="' . $karyawan->id . '"><i class="bi bi-pencil"></i></button>
                        <button class="btn btn-danger btn-sm btn-delete" data-id="' . $karyawan->id . '"><i class="bi bi-trash"></i></button>
                    ';
            })
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jabatans = Jabatan::all();
        return view('karyawan.create', compact('jabatans'));
    }

    public function store(Request $request)
    {

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'jabatan_id' => 'required',
            'tanggal_bergabung' => 'required|date',
            'no_hp' => 'required|string',
            'no_rekening' => 'required|string',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required',
            'jenis_kelamin' => 'required',
        ],[
            'required' => ':attribute wajib diisi'
        ]);

        $user = User::firstOrCreate([
            'email' => $request->email,
        ], [
            'name' => $request->name,
            'password' => bcrypt('123123123'),
        ]);

        $karyawan = Karyawan::create([
            'user_id' => $user->id,
            'jabatan_id' => $request->jabatan_id,
            'nik' => $request->nik,
            'tanggal_bergabung' => $request->tanggal_bergabung,
            'no_hp' => $request->no_hp,
            'no_rekening' => $request->no_rekening,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
            'jenis_kelamin' => $request->jenis_kelamin,
        ]);

        return response()->json(['success' => 'Karyawan added successfully.']);
    }

    public function edit($id)
    {
        $karyawan = Karyawan::with('jabatan', 'user')->findOrFail($id);

        $jabatans = Jabatan::all();

        return response()->json([
            'karyawan' => $karyawan,
            'jabatans' => $jabatans,
        ]);
    }
    public function show($id)
    {
        $karyawan = Karyawan::with('user', 'jabatan')->findOrFail($id);
        return response()->json(['karyawan' => $karyawan]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'jabatan_id' => 'required',
            'tanggal_bergabung' => 'required|date',
            'no_hp' => 'required|string',
            'no_rekening' => 'required|string',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required',
            'alamat' => 'required',
        ]);

        $karyawan = Karyawan::findOrFail($id);

        // Update User
        $user = User::findOrFail($karyawan->user_id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Update Karyawan
        $karyawan->update([
            'jabatan_id' => $request->jabatan_id,
            'tanggal_bergabung' => $request->tanggal_bergabung,
            'no_hp' => $request->no_hp,
        ]);

        return response()->json(['success' => 'Karyawan updated successfully.']);
    }

    public function destroy($id)
    {
        try {

            $karyawan = Karyawan::findOrFail($id);

            $user = User::findOrFail($karyawan->user_id);

            DB::beginTransaction();

            $user->delete();

            $karyawan->delete();
            DB::commit();

            return response()->json(['success' => 'Karyawan and associated user deleted successfully.']);
        } catch (\Exception $e) {
            // Rollback transaksi database jika terjadi kesalahan
            DB::rollback();

            return response()->json(['error' => 'Failed to delete karyawan and associated user.'], 500);
        }
    }


    public function import(Request $request)
    {
        $file = $request->file('file');
        $nama_file = $file->hashName();
        $path = $file->storeAs('public/excel/', $nama_file);

        $import = Excel::import(new KaryawanImport(), storage_path('app/public/excel/' . $nama_file));
        Storage::delete($path);

        if ($import) {
            return redirect()->route('karyawan.index')->with(['success' => 'Data Berhasil Diimport!']);
        } else {
            return redirect()->route('karyawan.index')->with(['error' => 'Data Gagal Diimport!']);
        }
        return redirect()->back()->with('success', 'Data karyawan berhasil diimpor.');
    }
}
