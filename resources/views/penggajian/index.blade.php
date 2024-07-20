@extends('layouts.dashboard')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h3 class="fw-bold">Penggajian</h3>
        <div class="card shadow border-0 mb-3">
            <div class="card-body">
                <form action="{{ route('penggajian.index') }}" method="GET">
                    <div class="row d-flex align-items-center">
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
                                        <option value="{{ $year }}"
                                            {{ ($tahun ?? date('Y')) == $year ? 'selected' : '' }}>{{ $year }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                            
                        <div class="col-md-4 text-end">
                            <button type="submit" class="btn btn-success mt-4" style="background-color: #28a745; border-color: #28a745;">
                                Generate Data Gaji
                            </button>
                        </div>
                        
                    </div>
                </form>

            </div>
        </div>
        <div class="card shadow border-0">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table datatable">
                        <thead>
                            <tr>
                                <th>NIK</th>
                                <th>Nama Karyawan</th>
                                <th>Jabatan</th>
                                <th>Gaji Bersih</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($penggajianData as $data)
                                <tr>
                                    <td>{{ $data['karyawan']->nik }}</td>
                                    <td>{{ $data['karyawan']->user->name }}</td>
                                    <td>{{ $data['karyawan']->jabatan->jabatan }}</td>
                                    <td>{{ ' Rp. ' . number_format($data['gaji_bersih'], 0, ',', '.') }}</td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <button class="btn rounded-pill btn-primary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#detailModal" data-id="{{ $data['karyawan']->id }}"
                                                data-bulan="{{ request()->query('bulan') }}"
                                                data-tahun="{{ request()->query('tahun') }}">
                                                <i class="bi bi-eye"></i>
                                            </button>

                                            <a href="{{ route('penggajian.pdf', $data['karyawan']->id) }}"
                                                class="btn rounded-pill btn-secondary btn-sm" target="_blank">
                                                <i class="bi bi-printer"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if (Auth::user()->role == 'admin')
                    <button type="button" class="btn btn-success mx-3" id="simpanDataGaji" style="background-color: #28a745; border-color: #28a745;">
                        Simpan Data Gaji
                    </button>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel">Slip Gaji Karyawan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Isi detail gaji akan di-load via AJAX -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <a href="{{ route('penggajian.pdf', $data['karyawan']->id) }}" class="btn btn-primary">Cetak</a>
            </div>
        </div>
    </div>
</div>

<style>
    .struk-gaji {
        font-family: Arial, sans-serif;
        line-height: 1.6;
        padding: 20px;
    }

    .struk-gaji h5 {
        margin-top: 0;
    }

    .struk-gaji hr {
        border-top: 1px solid #000;
    }

    .struk-gaji .text-center {
        text-align: center;
    }
</style>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#detailModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var karyawanId = button.data('id');
            var bulan = button.data('bulan');
            var tahun = button.data('tahun');

            var modal = $(this);
            modal.find('.modal-body').html('Loading...');

            $.ajax({
                url: '/penggajian/' + karyawanId,
                method: 'GET',
                data: {
                    bulan: bulan,
                    tahun: tahun
                },
                success: function(data) {
                    console.log(data);
                    var jenisPotonganHtml = '';
                    var pinjamanHtml = '';
                    var namaBulan = [
                        'Januari', 'Februari', 'Maret', 'April',
                        'Mei', 'Juni', 'Juli', 'Agustus',
                        'September', 'Oktober', 'November', 'Desember'
                    ];

                    // Mendapatkan nama bulan berdasarkan angka bulan
                    var namaBulanString = namaBulan[data.bulan - 1];
                    data.detail_potongan_wajib.forEach(function(potongan) {
                        jenisPotonganHtml += `
                            <tr>
                                <td style="padding: 8px; border: 1px solid #dddddd;"><strong>${potongan.jenis_potongan}:</strong></td>
                                <td colspan="2" style="padding: 8px; border: 1px solid #dddddd;"><strong>Rp.${number_format(potongan.potongan)}</strong></td>
                            </tr>
                        `;
                    });

                    data.jenis_potongan.forEach(function(jenis) {
                        jenisPotonganHtml += `
                            <tr>
                                <td style="padding: 8px; border: 1px solid #dddddd;"><strong>${jenis.jenis_potongan}:</strong></td>
                                <td colspan="2" style="padding: 8px; border: 1px solid #dddddd;"><strong>Rp.${number_format(jenis.jumlah)}</strong></td>
                            </tr>
                        `;
                    });

                    // Menambahkan informasi pinjaman yang disetujui
                    data.pinjamanPotongan.forEach(function(pinjaman) {
                        pinjamanHtml += `
                            <tr>
                                <td style="padding: 8px; border: 1px solid #dddddd;"><strong>Pinjaman: ${pinjaman.tujuan}</strong></td>
                                <td colspan="2" style="padding: 8px; border: 1px solid #dddddd;"><strong>Rp.${number_format(pinjaman.jumlah)}</strong></td>
                            </tr>
                        `;
                    });

                    var detailHtml = `
                        <div class="struk-gaji">
                            <h5 class="text-center">Klinik NU Muntilan</h5>
                            <p class="text-center">Jl. Watucongol, Kec. Santren Gunungpring, Magelang</p>
                            <hr>
                            <h5 class="text-center">Slip Gaji Karyawan</h5>
                            <p class="m-0"><strong>Bulan:</strong> ${namaBulanString} ${data.tahun}</p>
                            <p class="m-0"><strong>Nama:</strong> ${data.karyawan.user.name}</p>
                            <p class="m-0"><strong>Jabatan:</strong> ${data.karyawan.jabatan.jabatan}</p>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Penghasilan:</strong></p>
                                    <table style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; font-size: 14px;">
                                        <tr>
                                            <td style="padding: 8px; border: 1px solid #dddddd;">Kehadiran :</td>
                                            <td colspan="2" style="padding: 8px; border: 1px solid #dddddd;">${data.hadir} hari x ${number_format(data.gaji_per_hari)}</td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 8px; border: 1px solid #dddddd;">Gaji Pokok:</td>
                                            <td colspan="2" style="padding: 8px; border: 1px solid #dddddd;">= ${number_format(data.gaji_per_hari * data.hadir)}</td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 8px; border: 1px solid #dddddd;">Tunjangan Transportasi:</td>
                                            <td colspan="2" style="padding: 8px; border: 1px solid #dddddd;">${number_format(data.tunjangan_transportasi)}</td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 8px; border: 1px solid #dddddd;">Tunjangan Makan:</td>
                                            <td colspan="2" style="padding: 8px; border: 1px solid #dddddd;">${number_format(data.uang_makan)}</td>
                                        </tr>
                                        <tr>
                                            <td style="padding: 8px; border: 1px solid #dddddd;"><strong>Gaji Kotor:</strong></td>
                                            <td colspan="2" style="padding: 8px; border: 1px solid #dddddd;"><strong>${number_format(data.gaji_kotor)}</strong></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Potongan:</strong></p>
                                    <table style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; font-size: 14px;">
                                        ${jenisPotonganHtml}
                                        ${pinjamanHtml}
                                        <tr>
                                            <td style="padding: 8px; border: 1px solid #dddddd;"><strong>Total Potongan:</strong></td>
                                            <td colspan="2" style="padding: 8px; border: 1px solid #dddddd;"><strong>Rp.${number_format(data.total_potongan_gaji)}</strong></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                          <div class="mt-4">
                             <p class="m-0 text-end"><strong>Gaji Kotor: Rp.${number_format(data.gaji_kotor)}</strong></p>
                             <p class="m-0 text-end text-danger"><strong>Potongan: Rp.${number_format(data.total_potongan_gaji)}</strong></p>
                             <hr style="width: 50%; margin: 10px 0 10px auto;">
                             <p class="m-0 text-end"><strong>Gaji Bersih yang Diterima: Rp.${number_format(data.gaji_bersih)}</strong></p>
                          </div>
                        </div>
                    `;

                    modal.find('.modal-body').html(detailHtml);
                },
                error: function() {
                    modal.find('.modal-body').html('Gagal mengambil data. Silakan coba lagi.');
                }
            });
        });
    });

    // Helper function for number formatting
    function number_format(number, decimals, dec_point, thousands_sep) {
        number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
        var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            s = '',
            toFixedFix = function(n, prec) {
                var k = Math.pow(10, prec);
                return '' + (Math.round(n * k) / k).toFixed(prec);
            };
        // Fix for IE parseFloat(0.55).toFixed(0) = 0;
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    }
</script>
@endpush

