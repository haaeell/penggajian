<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Slip Gaji Karyawan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.4;
            padding: 20px;
        }
        .text-center {
            text-align: center;
        }
        .struk-gaji {
            display: flex;
            justify-content: space-between;
            padding: 20px;
        }
        .struk-gaji h5 {
            margin-top: 0;
        }
        .struk-gaji hr {
            border-top: 1px solid #000;
        }
        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            border-collapse: collapse;
        }
        .table th,
        .table td {
            padding: 0.5rem;
            vertical-align: top;
            border: 1px solid #000;
        }
        .table th {
            font-weight: bold;
            background-color: #f2f2f2;
        }
        .row {
            display: flex;
        }
        .col {
            flex: 1;
            padding: 10px;
        }
        .gaji-bersih {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="struk-gaji">
        <div>
            <h5 class="text-center">Klinik NU Muntilan</h5>
            <p class="text-center">Jl. Watucongol, Kec. Santren Gunungpring, Magelang</p>
            <hr>
            <h5 class="text-center">Slip Gaji Karyawan</h5>
            <table style="border:none">
                <tr>
                    <td colspan="3">Bulan</td>
                    <td>: {{ $bulan }}</td>
                </tr>
                <tr>
                    <td colspan="3">Nama</td>
                    <td>: {{ $karyawan->user->name }}</td>
                </tr>
                <tr>
                    <td colspan="3">Jabatan</td>
                    <td>: {{ $karyawan->jabatan->jabatan }}</td>
                </tr>
            </table>
            <hr>
            <div class="row">
                <div class="col">
                    <table class="table table-borderless">
                        <tr>
                            <th colspan="2">Penghasilan</th>
                        </tr>
                        <tr>
                            <td>Gaji Pokok:</td>
                            <td>{{ number_format($gaji_per_hari * $hadir, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Tunjangan Transportasi:</td>
                            <td>{{ number_format($tunjangan_transportasi, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Uang Makan:</td>
                            <td>{{ number_format($uang_makan, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Total Penghasilan:</td>
                            <td>{{ number_format($gaji_kotor, 2) }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col">
                    <table class="table table-borderless">
                        <tr>
                            <th colspan="2">Potongan</th>
                        </tr>
                        @foreach ($karyawan->potonganGaji as $potongan)
                            @foreach ($potongan->jenisPotonganGaji as $jenis)
                                <tr>
                                    <td>{{ $jenis->jenis_potongan }}:</td>
                                    <td>{{ $jenis->jumlah}}</td>
                                    
                                </tr>
                            @endforeach
                        @endforeach
                        <tr>
                            <td >BPJS:</td>
                            <td >{{number_format($jumlah_bpjs, 2)}}</td>
                        </tr>
                        <tr>
                            <td>Total Potongan:</td>
                            <td>{{ number_format($total_potongan_gaji, 2) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <hr>
        </div>
        <div class="gaji-bersih">
            <h3><strong>Gaji Bersih: {{ number_format($gaji_bersih, 2) }}</strong></h3>
            <hr>
            <p>Magelang, {{ $tanggal }}</p>
            <p>Penerima: {{ $karyawan->user->name }}</p>
            <p>Manajer Keuangan</p>
        </div>
    </div>
</body>
</html>
