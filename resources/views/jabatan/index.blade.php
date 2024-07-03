@extends('layouts.dashboard')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h3 class="fw-bold">Jabatan</h3>
        <div class="card shadow border-0">
            <div class="col-md-3">
                <button class="btn rounded-pill mx-3 mt-3" style="background-color: #28a745; color: white; border-color: #28a745;" id="btn-add">
                    <i class="bi bi-plus"></i> Tambah Jabatan
                </button>
            </div>
            
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="jabatans-table">
                        <thead>
                            <tr>
                                <th class="text-start">Jabatan</th>
                                <th class="text-start">Gaji per hari</th>
                                <th class="text-start">Tunjangan Transportasi</th>
                                <th class="text-start">Uang Makan</th>
                                <th class="text-start">Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Form -->
<div class="modal fade" id="jabatanModal" tabindex="-1" role="dialog" aria-labelledby="jabatanModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="jabatanForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="jabatanModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="jabatan_id">
                    <div class="mb-3">
                        <label for="jabatan" class="form-label">Jabatan</label>
                        <input type="text" class="form-control" id="jabatan" name="jabatan" required>
                    </div>
                    <div class="mb-3">
                        <label for="gaji_per_hari" class="form-label">Gaji per hari</label>
                        <input type="text" class="form-control rupiah-input" id="gaji_per_hari" name="gaji_per_hari" required>
                    </div>
                    <div class="mb-3">
                        <label for="tunjangan_transportasi" class="form-label">Tunjangan Transportasi</label>
                        <input type="text" class="form-control rupiah-input" id="tunjangan_transportasi" name="tunjangan_transportasi" required>
                    </div>
                    <div class="mb-3">
                        <label for="uang_makan" class="form-label">Uang Makan</label>
                        <input type="text" class="form-control rupiah-input" id="uang_makan" name="uang_makan" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="btn-save"></button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Delete Confirmation -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus data ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="btn-confirm-delete">Hapus</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function formatRupiah(angka, prefix) {
        var number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
    }

    $(document).ready(function() {
        var table = $('#jabatans-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('getJabatans') }}',
            columns: [
                { data: 'jabatan', name: 'jabatan' },
                { data: 'gaji_per_hari', name: 'gaji_per_hari', render: function(data, type, row) {
                    return formatRupiah(data.toString(), 'Rp. ');
                }},
                { data: 'tunjangan_transportasi', name: 'tunjangan_transportasi', render: function(data, type, row) {
                    return formatRupiah(data.toString(), 'Rp. ');
                }},
                { data: 'uang_makan', name: 'uang_makan', render: function(data, type, row) {
                    return formatRupiah(data.toString(), 'Rp. ');
                }},
                { data: 'id', name: 'id', orderable: false, searchable: false, render: function(data, type, row) {
                    return `
                        <button class="btn rounded-pill btn-warning btn-sm btn-edit" data-id="${data}"><i class="bi bi-pencil"></i></button>
                        <button class="btn rounded-pill btn-danger btn-sm btn-delete" data-id="${data}"><i class="bi bi-trash"></i></button>
                    `;
                }},
            ]
        });

        $('#btn-add').click(function() {
            $('#jabatanForm').trigger("reset");
            $('#jabatanModalLabel').html("Tambah Jabatan");
            $('#btn-save').html("Tambah");
            $('#jabatanModal').modal('show');
        });

        $('body').on('click', '.btn-edit', function() {
            var id = $(this).data('id');
            $.get('jabatans/' + id + '/edit', function(data) {
                $('#jabatanModalLabel').html("Edit Jabatan");
                $('#btn-save').html("Update");
                $('#jabatan_id').val(data.id);
                $('#jabatan').val(data.jabatan);
                $('#gaji_per_hari').val(formatRupiah(data.gaji_per_hari.toString(), 'Rp. '));
                $('#tunjangan_transportasi').val(formatRupiah(data.tunjangan_transportasi.toString(), 'Rp. '));
                $('#uang_makan').val(formatRupiah(data.uang_makan.toString(), 'Rp. '));
                $('#jabatanModal').modal('show');
            })
        });

        $('#jabatanForm').submit(function(e) {
            e.preventDefault();
            var id = $('#jabatan_id').val();
            var url = id ? 'jabatans/' + id : 'jabatans';
            var type = id ? 'PUT' : 'POST';

            // Convert Rupiah format back to numbers for submission
            var gajiPerHari = $('#gaji_per_hari').val().replace(/[^,\d]/g, '');
            var tunjanganTransportasi = $('#tunjangan_transportasi').val().replace(/[^,\d]/g, '');
            var uangMakan = $('#uang_makan').val().replace(/[^,\d]/g, '');
            $('#gaji_per_hari').val(gajiPerHari);
            $('#tunjangan_transportasi').val(tunjanganTransportasi);
            $('#uang_makan').val(uangMakan);

            $.ajax({
                url: url,
                type: type,
                data: $(this).serialize(),
                success: function(response) {
                    $('#jabatanModal').modal('hide');
                    table.ajax.reload();
                    toastr.success(response.success);
                },
                error: function(response) {
                    toastr.error('Something went wrong!');
                }
            });
        });

        var deleteId;
        $('body').on('click', '.btn-delete', function() {
            deleteId = $(this).data('id');
            $('#deleteModal').modal('show');
        });

        $('#btn-confirm-delete').click(function() {
            $.ajax({
                url: 'jabatans/' + deleteId,
                type: 'DELETE',
                success: function(response) {
                    $('#deleteModal').modal('hide');
                    table.ajax.reload();
                    toastr.success(response.success);
                },
                error: function(response) {
                    toastr.error('Something went wrong!');
                }
            });
        });

        // Format Rupiah in input fields
        $('body').on('input', '.rupiah-input', function() {
            var value = $(this).val();
            $(this).val(formatRupiah(value, 'Rp. '));
        });
    });
</script>
@endpush
