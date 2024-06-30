<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;

class ProfilController extends Controller
{
    public function edit()
    {
        // Ambil data karyawan yang terkait dengan user yang sedang login
        $user = auth()->user();
        $karyawan = $user->karyawan;

        return view('profile.edit', compact('karyawan'));
    }

    /**
     * Update profil karyawan.
     */
    public function update(Request $request, $id)
    {
        // Validasi data input
        $validatedData = $request->validate([
            'nik' => 'required|string',
            'tanggal_bergabung' => 'required|date',
            'no_hp' => 'required|string',
            'alamat' => 'required|string',
            'no_rekening' => 'required|string',
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
        ]);

        try {
            // Cari data karyawan berdasarkan ID
            $karyawan = Karyawan::findOrFail($id);

            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/foto'), $filename);
                $validatedData['foto'] = $filename;
            }
            // Update data karyawan
            $karyawan->update($validatedData);

            // Response jika berhasil
            return redirect()->route('profile.edit')->with('success', 'Profil berhasil diperbarui.');

        } catch (\Exception $e) {
            // Response jika terjadi error
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui profil: ' . $e->getMessage());
        }
    }
}
