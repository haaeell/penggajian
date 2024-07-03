@extends('layouts.dashboard')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Edit Profil Karyawan</div>

                <div class="card-body">
                    <form action="{{ route('profile.update', $karyawan->id) }}" method="POST"  enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-2">
                                    <label for="foto">Foto Profil</label>
                                    <input type="file" id="foto" name="foto" class="form-control">
                                    @if($karyawan->foto)
                                        <img src="{{ asset('uploads/foto/' . $karyawan->foto) }}" alt="Foto Profil" class="img-thumbnail mt-2" width="150">
                                    @endif
                                </div>
                                <div class="form-group mb-2">
                                    <label for="nik">NIK</label>
                                    <input type="text" id="nik" name="nik" class="form-control" value="{{ old('nik', $karyawan->nik) }}">
                                </div>
                                <div class="form-group mb-2">
                                    <label for="tanggal_bergabung">Tanggal Bergabung</label>
                                    <input type="date" id="tanggal_bergabung" name="tanggal_bergabung" class="form-control" value="{{ old('tanggal_bergabung', $karyawan->tanggal_bergabung) }}">
                                </div>
                                <div class="form-group mb-2">
                                    <label for="no_hp">Nomor HP</label>
                                    <input type="text" id="no_hp" name="no_hp" class="form-control" value="{{ old('no_hp', $karyawan->no_hp) }}">
                                </div>
                                <div class="form-group mb-2">
                                    <label for="alamat">Alamat</label>
                                    <textarea id="alamat" name="alamat" class="form-control">{{ old('alamat', $karyawan->alamat) }}</textarea>
                                </div>
                            </div>

                            <div class="col-md-6">
   
                                <div class="form-group mb-2">
                                    <label for="no_rekening">Nomor Rekening</label>
                                    <input type="text" id="no_rekening" name="no_rekening" class="form-control" value="{{ old('no_rekening', $karyawan->no_rekening) }}">
                                </div>
                                <div class="form-group mb-2">
                                    <label for="tempat_lahir">Tempat Lahir</label>
                                    <input type="text" id="tempat_lahir" name="tempat_lahir" class="form-control" value="{{ old('tempat_lahir', $karyawan->tempat_lahir) }}">
                                </div>
                                <div class="form-group mb-2">
                                    <label for="tanggal_lahir">Tanggal Lahir</label>
                                    <input type="date" id="tanggal_lahir" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir', $karyawan->tanggal_lahir) }}">
                                </div>
                                <div class="form-group mb-2">
                                    <label for="jenis_kelamin">Jenis Kelamin</label>
                                    <select id="jenis_kelamin" name="jenis_kelamin" class="form-control">
                                        <option value="L" {{ old('jenis_kelamin', $karyawan->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="P" {{ old('jenis_kelamin', $karyawan->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                     

                        <!-- Submit button -->
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
