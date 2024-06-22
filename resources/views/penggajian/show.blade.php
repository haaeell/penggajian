@extends('layouts.dashboard')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h3 class="fw-bold">Detail Penggajian</h3>
        <div class="card shadow border-0">
            <div class="card-body">
                <h4>Nama Karyawan: {{ $penggajian->karyawan->user->name }}</h4>
                <p>Jabatan: {{ $penggajian->karyawan->jabatan->nama }}</p>
                <p>Bulan: {{ \Carbon\Carbon::parse($penggajian->bulan_gaji)->format('F Y') }}</p>

                <h5>Rincian Gaji</h5>
                <p>Gaji Pokok: Rp. {{ number_format($penggajian->karyawan->jabatan->gaji_perhari * $penggajian->absensi->hadir, 2) }}</p>
                <p>Tunjangan Transportasi: Rp. {{ number_format($penggajian->karyawan->jabatan->tunjangan_transportasi, 2) }}</p>
                <p>Uang Makan: Rp. {{ number_format($penggajian->karyawan->jabatan->uang_makan, 2) }}</p>

                <h5>Potongan Gaji</h5>
                <p>Total Potongan: Rp. {{ number_format($penggajian->potonganGaji->total_potongan_gaji, 2) }}</p>

                <h5>Gaji Bersih</h5>
                <p>Rp. {{ number_format($penggajian->gaji_bersih, 2) }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
