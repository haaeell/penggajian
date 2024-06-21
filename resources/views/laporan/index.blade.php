@extends('layouts.dashboard')

@section('content') 
<div class="row">
    <div class="col-md-12">
        <h3 class="fw-bold">Laporan</h3>
        <div class="card shadow border-0"> 
            <div class="card-body">
                <form action="" method="GET">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Bulan</label>
                                <select class="form-select" name="bulan">
                                    <option value="">Pilih Bulan</option>
                                    @for ($month = 1; $month <= 12; $month++)
                                        <option value="{{ $month }}">{{ DateTime::createFromFormat('!m', $month)->format('F') }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Tahun</label>
                                <select class="form-select" name="tahun">
                                    <option value="">Pilih Tahun</option>
                                    @for ($year = 2020; $year <= now()->year; $year++)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Laporan</label>
                                <select class="form-select" name="laporan">
                                    <option value="">Pilih Laporan</option>
                                    <option value="data_karyawan">Data Karyawan</option>
                                    <option value="data_jabatan">Data Jabatan</option>
                                    <option value="data_absensi">Data Absensi</option>
                                    <!-- Tambahkan opsi laporan lainnya di sini -->
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12 text-end">
                            <button type="submit" class="btn btn-primary">Generate Laporan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
