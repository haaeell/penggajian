<div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="userForm">
                @csrf
                <input type="hidden" id="user_id" name="user_id">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalLabel">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name"  required>
                        <div class="invalid-feedback" id="name-error"></div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                        <div class="invalid-feedback" id="email-error"></div>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="admin">Admin</option>
                            <option value="pimpinan">Pimpinan</option>
                            <option value="karyawan">Karyawan</option>
                        </select>
                        <div class="invalid-feedback" id="role-error"></div>
                    </div>
                    <div id="karyawan-fields" style="display: none;">
                        <div class="mb-3">
                            <label for="jabatan_id" class="form-label">Jabatan</label>
                            <select class="form-select" id="jabatan_id" name="jabatan_id">
                                <option value="">Select Jabatan</option>
                                @foreach($jabatans as $jabatan)
                                    <option value="{{ $jabatan->id }}">{{ $jabatan->jabatan }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="jabatan_id-error"></div>
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_bergabung" class="form-label">Tanggal Bergabung</label>
                            <input type="date" class="form-control" id="tanggal_bergabung" name="tanggal_bergabung">
                            <div class="invalid-feedback" id="tanggal_bergabung-error"></div>
                        </div>
                        <div class="mb-3">
                            <label for="no_hp" class="form-label">No. HP</label>
                            <input type="text" class="form-control" id="no_hp" name="no_hp">
                            <div class="invalid-feedback" id="no_hp-error"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="btn-save">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- users.modal.blade.php -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="deleteUserForm">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Delete User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this user?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger" id="btn-confirm-delete">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>
