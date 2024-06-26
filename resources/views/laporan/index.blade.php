@extends('layouts.dashboard')

@section('content') 
<div class="row">
    <div class="col-md-12">
        <h3 class="fw-bold">Laporan</h3>
        <div class="card shadow border-0 mb-3">
            <div class="card-body">
                <form action="{{ route('laporan.index') }}" method="GET">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Bulan</label>
                                <select class="form-select" name="bulan">
                                    <option value="">Pilih Bulan</option>
                                    @for ($month = 1; $month <= 12; $month++)
                                    <option value="{{ $month }}"
                                        {{ ($bulan ?? date('n')) == $month ? 'selected' : '' }}>
                                        {{ Carbon\Carbon::create()->month($month)->locale('id')->isoFormat('MMMM') }}
                                    </option>
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
                                        <option value="{{ $year }}" {{ request('tahun', now()->year) == $year ? 'selected' : '' }}>{{ $year }}</option>
                                    @endfor
                                </select>
                            </div>
                            
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Laporan</label>
                                <select class="form-select" name="laporan">
                                    <option value="">Pilih Laporan</option>
                                    <option value="data_karyawan" {{ request('laporan') == 'data_karyawan' ? 'selected' : '' }}>Data Karyawan</option>
                                    <option value="data_jabatan" {{ request('laporan') == 'data_jabatan' ? 'selected' : '' }}>Data Jabatan</option>
                                    <option value="data_absensi" {{ request('laporan') == 'data_absensi' ? 'selected' : '' }}>Data Absensi</option>
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
        <div class="card shadow border-0"> 
            <div class="card-body">
            
                @if(isset($penggajianData))
                    <div class="table-responsive mt-4">
                        <table class="table datatable">
                            <thead>
                                <tr>
                                    <th>NIK</th>
                                    <th>Nama Karyawan</th>
                                    <th>Jabatan</th>
                                    <th>Total Penghasilan</th>
                                    <th>Total Potongan</th>
                                    <th>Gaji Bersih</th>
                                    <th>Bulan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($penggajianData as $data)
                                    <tr>
                                        <td>{{ $data->karyawan->nik }}</td>
                                        <td>{{ $data->karyawan->user->name }}</td>
                                        <td>{{ $data->karyawan->jabatan->jabatan }}</td>
                                        <td>Rp. {{ number_format($data->total_penghasilan, 2) }}</td>
                                        <td>Rp. {{ number_format($data->total_potongan, 2) }}</td>
                                        <td>Rp. {{ number_format($data->gaji_bersih, 2) }}</td>
                                        <td>{{ $data->bulan->translatedFormat('F Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

