@extends('layouts.dashboard')

@section('content') 
<div class="row">
    <div class="col-md-12">
        <h3 class="fw-bold">Absensi Karyawan</h3>

        <div class="row">
            <div class="col-md-12">
                <div class="card shadow border-0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Bulan</label>
                                    <select class="form-select">
                                        <option value="">Pilih Bulan</option>
                                        @for ($month = 1; $month <= 12; $month++)
                                            <option value="{{ $month }}">{{ DateTime::createFromFormat('!m', $month)->format('F') }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tahun</label>
                                    <select class="form-select">
                                        <option value="">Pilih Tahun</option>
                                        @for ($year = 2020; $year <= now()->year; $year++)
                                            <option value="{{ $year }}">{{ $year }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card shadow border-0">
                    <div class="col-md-3">
                        <button class="btn rounded-pill btn-primary mx-3 mt-3"><i class="bi bi-plus"></i> Input Absensi</button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>NIK</th>
                                        <th>Nama</th>
                                        <th>Jabatan</th>
                                        <th>Hadir</th>
                                        <th>Izin</th>
                                        <th>Sakit</th>
                                        <th>Alpa</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @for ($i = 1; $i <= 5; $i++)
                                        <tr>
                                            <td>NIK{{ str_pad($i, 4, '0', STR_PAD_LEFT) }}</td>
                                            <td>Karyawan {{ $i }}</td>
                                            <td>Jabatan {{ $i }}</td>
                                            <td>{{ rand(15, 22) }}</td>
                                            <td>{{ rand(0, 3) }}</td>
                                            <td>{{ rand(0, 3) }}</td>
                                            <td>{{ rand(0, 3) }}</td>
                                            <td>
                                                <div class="d-flex gap-1">
                                                    <button class="btn rounded-pill btn-warning btn-sm"><i class="bi bi-pencil"></i> </button>
                                                    <button class="btn rounded-pill btn-danger btn-sm"><i class="bi bi-trash"></i> </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
