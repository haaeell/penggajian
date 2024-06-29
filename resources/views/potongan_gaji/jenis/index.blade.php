@extends('layouts.dashboard')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h3 class="fw-bold">Jenis Potongan Gaji</h3>
        <div class="card shadow border-0">
            <div class="col-md-3">
                <button class="btn rounded-pill mx-3 mt-3" style="background-color: #28a745; color: white; border-color: #28a745;" id="btn-add">
                    <i class="bi bi-plus"></i> Tambah Jenis Potongan Gaji
                </button>
            </div>
            
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="jenisPotonganGajiTable">
                        <thead>
                            <tr>
                                <th class="text-start">Jenis Potongan</th>
                                <th class="text-start">Jumlah</th>
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
<div class="modal fade" id="jenisPotonganGajiModal" tabindex="-1" role="dialog" aria-labelledby="jenisPotonganGajiModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="jenisPotonganGajiForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="jenisPotonganGajiModalLabel"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="jenis_potongan_gaji_id">
                    <div class="mb-3">
                        <label for="jenis_potongan" class="form-label">Jenis Potongan</label>
                        <input type="text" class="form-control" id="jenis_potongan" name="jenis_potongan" required>
                    </div>
                    <div class="mb-3">
                        <label for="jumlah" class="form-label">Jumlah</label>
                        <input type="number" class="form-control" id="jumlah" name="jumlah" required>
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
                <p>Apakah Anda yakin ingin menghapus data ini?</p>
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
    $(document).ready(function() {
        var table = $('#jenisPotonganGajiTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('getJenis') }}',
            columns: [
                { data: 'jenis_potongan', name: 'jenis_potongan' },
                { data: 'jumlah', name: 'jumlah' },
                { data: 'id', name: 'id', orderable: false, searchable: false, render: function(data, type, row) {
                    return `
                        <button class="btn rounded-pill btn-warning btn-sm btn-edit" data-id="${data}"><i class="bi bi-pencil"></i></button>
                        <button class="btn rounded-pill btn-danger btn-sm btn-delete" data-id="${data}"><i class="bi bi-trash"></i></button>
                    `;
                }},
            ]
        });

        $('#btn-add').click(function() {
            $('#jenisPotonganGajiForm').trigger("reset");
            $('#jenisPotonganGajiModalLabel').html("Tambah Jenis Potongan Gaji");
            $('#btn-save').html("Tambah");
            $('#jenisPotonganGajiModal').modal('show');
        });

        $('body').on('click', '.btn-edit', function() {
            var id = $(this).data('id');
            $.get('{{ route('jenis-potongan-gaji.index') }}/' + id + '/edit', function(data) {
                $('#jenisPotonganGajiModalLabel').html("Edit Jenis Potongan Gaji");
                $('#btn-save').html("Update");
                $('#jenis_potongan_gaji_id').val(data.id);
                $('#jenis_potongan').val(data.jenis_potongan);
                $('#jumlah').val(data.jumlah);
                $('#jenisPotonganGajiModal').modal('show');
            });
        });

        $('#jenisPotonganGajiForm').submit(function(e) {
            e.preventDefault();
            var id = $('#jenis_potongan_gaji_id').val();
            var url = id ? 'jenis-potongan-gaji/' + id : '{{ route('jenis-potongan-gaji.store') }}';
            var type = id ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                type: type,
                data: $(this).serialize(),
                success: function(response) {
                    $('#jenisPotonganGajiModal').modal('hide');
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
                url: 'jenis-potongan-gaji/' + deleteId,
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
    });
</script>
@endpush
