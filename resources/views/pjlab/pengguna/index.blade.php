
@extends('layouts.pjlab')

@section('title', 'Manajemen Pengguna')

@push('styles')
<style>

/* ============================================
   KHUSUS ISI HALAMAN MANAJEMEN PENGGUNA
   Tidak mengubah sidebar atau topbar.
============================================ */

.pengguna-card {
    background: #fff;
    border: 1px solid #e0e8e3;
    border-radius: 14px;
    box-shadow: 0 8px 25px rgba(28,68,49,.04);
}

.pengguna-heading {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
    margin-bottom: 22px;
}

.pengguna-heading h4 {
    font-size: 23px;
    font-weight: 700;
    color: #173a2c;
    margin: 0 0 6px;
}

.pengguna-heading p {
    color: #7c8982;
    font-size: 12px;
    margin: 0;
}

.pengguna-btn-primary {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    border: 1px solid #2f855a;
    border-radius: 9px;
    padding: 11px 17px;
    background: #2f855a;
    color: white;
    font-size: 12px;
    font-weight: 600;
    transition: .2s ease;
}

.pengguna-btn-primary:hover {
    background: #246b48;
    color: white;
}

.pengguna-filter {
    padding: 18px;
    margin-bottom: 17px;
}

.pengguna-search {
    display: flex;
    align-items: center;
    gap: 10px;
    border: 1px solid #e0e8e3;
    border-radius: 9px;
    padding: 0 13px;
    min-height: 42px;
    background: #fff;
}

.pengguna-search i {
    color: #7c8982;
}

.pengguna-search input {
    width: 100%;
    border: 0;
    outline: 0;
    background: transparent;
    font-size: 12px;
}

.pengguna-filter .form-select,
.pengguna-modal .form-control,
.pengguna-modal .form-select {
    border: 1px solid #e0e8e3;
    border-radius: 9px;
    font-size: 12px;
    min-height: 42px;
}

.pengguna-filter .form-select:focus,
.pengguna-modal .form-control:focus,
.pengguna-modal .form-select:focus {
    border-color: #2f855a;
    box-shadow: 0 0 0 .15rem rgba(47,133,90,.12);
}

.pengguna-table {
    width: 100%;
    margin-bottom: 0;
}

.pengguna-table thead th {
    background: #f7faf8;
    color: #667b70;
    border-bottom: 1px solid #e0e8e3;
    padding: 16px;
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
}

.pengguna-table tbody td {
    padding: 15px 16px;
    vertical-align: middle;
    border-bottom: 1px solid #edf1ee;
    font-size: 12px;
    color: #344a3f;
}

.pengguna-table tbody tr:last-child td {
    border-bottom: 0;
}

.pengguna-table tbody tr:hover {
    background: #fbfdfb;
}

.pengguna-avatar {
    width: 35px;
    height: 35px;
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: #e5f2eb;
    color: #2f855a;
    font-size: 11px;
    font-weight: 700;
}

.pengguna-name {
    font-weight: 600;
    color: #173a2c;
}

.pengguna-badge {
    display: inline-block;
    padding: 5px 10px;
    border-radius: 30px;
    font-size: 10px;
    font-weight: 600;
    white-space: nowrap;
}

.pengguna-badge-role {
    color: #607168;
    background: #edf1ee;
}

.pengguna-badge-active {
    color: #218254;
    background: #e6f4eb;
}

.pengguna-badge-inactive {
    color: #c03939;
    background: #fce8e8;
}

.pengguna-badge-protected {
    color: #946919;
    background: #fff4dc;
}

.pengguna-actions {
    display: flex;
    align-items: center;
    gap: 6px;
}

.pengguna-action-btn {
    width: 34px;
    height: 34px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    border: 1px solid #e0e8e3;
    background: #fff;
    color: #2f855a;
    cursor: pointer;
}

.pengguna-action-btn:hover {
    background: #eaf5ee;
}

.pengguna-action-btn.danger {
    color: #d64545;
}

.pengguna-action-btn.danger:hover {
    background: #fceaea;
}

.pengguna-empty {
    padding: 45px 15px;
    text-align: center;
    color: #87948b;
}

.pengguna-empty i {
    display: block;
    font-size: 30px;
    margin-bottom: 9px;
}

.pengguna-modal .modal-content {
    border: 0;
    border-radius: 15px;
    overflow: hidden;
}

.pengguna-modal .modal-header {
    border-bottom: 1px solid #edf1ee;
}

.pengguna-modal .modal-footer {
    border-top: 1px solid #edf1ee;
}

.pengguna-modal .form-label {
    font-size: 12px;
    font-weight: 600;
    color: #344a3f;
}

.pengguna-password-note {
    font-size: 11px;
    color: #7c8982;
}

@media (max-width: 576px) {
    .pengguna-heading h4 {
        font-size: 20px;
    }

    .pengguna-btn-primary {
        width: 100%;
    }
}

</style>
@endpush


@section('content')

<!-- ==========================================
     HEADER HALAMAN
========================================== -->

<div class="pengguna-heading">

    <div>
        <h4>Manajemen Pengguna</h4>

        <p>
            Kelola akun PJ Lab, Guru, dan Kepala Madrasah.
        </p>
    </div>

    <button
        type="button"
        class="pengguna-btn-primary"
        id="btnTambahPengguna">

        <i class="bi bi-person-plus"></i>
        Tambah Pengguna

    </button>

</div>


<!-- ==========================================
     PESAN SUKSES
========================================== -->

@if(session('success'))

    <div class="alert alert-success alert-dismissible fade show"
         role="alert">

        <i class="bi bi-check-circle me-2"></i>

        {{ session('success') }}

        <button
            type="button"
            class="btn-close"
            data-bs-dismiss="alert"
            aria-label="Tutup">
        </button>

    </div>

@endif


<!-- ==========================================
     PESAN VALIDASI
========================================== -->

@if($errors->any())

    <div class="alert alert-danger" role="alert">

        <strong>
            Data belum berhasil disimpan.
        </strong>

        <ul class="mb-0 mt-2">

            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach

        </ul>

    </div>

@endif


<!-- ==========================================
     FILTER DAN PENCARIAN
========================================== -->

<div class="pengguna-card pengguna-filter">

    <div class="row g-3">

        <!-- SEARCH -->
        <div class="col-md-5">

            <div class="pengguna-search">

                <i class="bi bi-search"></i>

                <input
                    type="search"
                    id="searchUser"
                    placeholder="Cari nama / email..."
                    autocomplete="off">

            </div>

        </div>


        <!-- FILTER ROLE -->
        <div class="col-md-3">

            <select
                class="form-select"
                id="filterRole"
                aria-label="Filter role">

                <option value="">
                    Semua Role
                </option>

                <option value="pjlab">
                    PJ Lab
                </option>

                <option value="guru">
                    Guru
                </option>

                <option value="kamad">
                    Kepala Madrasah
                </option>

            </select>

        </div>


        <!-- FILTER STATUS -->
        <div class="col-md-3">

            <select
                class="form-select"
                id="filterStatus"
                aria-label="Filter status">

                <option value="">
                    Semua Status
                </option>

                <option value="1">
                    Aktif
                </option>

                <option value="0">
                    Nonaktif
                </option>

            </select>

        </div>

    </div>

</div>


<!-- ==========================================
     TABEL PENGGUNA
========================================== -->

<div class="pengguna-card">

    <div class="table-responsive">

        <table class="table pengguna-table">

            <thead>

                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>

            </thead>


            <tbody id="tbodyPengguna">

                @forelse($pengguna as $item)

                    @php
                        $isProtected = $item->is_protected
                            || $item->role === \App\Models\User::ROLE_PJLAB;

                        $roleLabel = match($item->role) {
                            'pjlab' => 'PJ Lab',
                            'guru' => 'Guru',
                            'kamad' => 'Kepala Madrasah',
                            default => $item->role,
                        };
                    @endphp

                    <tr
                        class="pengguna-row"
                        data-search="{{ mb_strtolower($item->name . ' ' . $item->email) }}"
                        data-role="{{ $item->role }}"
                        data-status="{{ $item->is_active ? '1' : '0' }}">

                        <!-- NAMA -->
                        <td>

                            <div class="d-flex align-items-center gap-2">

                                <div class="pengguna-avatar">

                                    {{ mb_strtoupper(
                                        mb_substr(
                                            trim($item->name),
                                            0,
                                            2
                                        )
                                    ) }}

                                </div>

                                <div>

                                    <div class="pengguna-name">
                                        {{ $item->name }}
                                    </div>

                                    @if($isProtected)
                                        <span class="pengguna-badge pengguna-badge-protected">
                                            Akun terlindungi
                                        </span>
                                    @endif

                                </div>

                            </div>

                        </td>


                        <!-- EMAIL -->
                        <td>
                            {{ $item->email }}
                        </td>


                        <!-- ROLE -->
                        <td>

                            <span class="pengguna-badge pengguna-badge-role">
                                {{ $roleLabel }}
                            </span>

                        </td>


                        <!-- STATUS -->
                        <td>

                            @if($item->is_active)

                                <span class="pengguna-badge pengguna-badge-active">
                                    Aktif
                                </span>

                            @else

                                <span class="pengguna-badge pengguna-badge-inactive">
                                    Nonaktif
                                </span>

                            @endif

                        </td>


                        <!-- AKSI -->
                        <td>

                            @if($isProtected)

                                <span class="text-muted small">
                                    <i class="bi bi-lock me-1"></i>
                                    Dilindungi
                                </span>

                            @else

                                <div class="pengguna-actions">

                                    <!-- EDIT -->
                                    <button
                                        type="button"
                                        class="pengguna-action-btn btnEditPengguna"
                                        title="Edit Pengguna"
                                        data-id="{{ $item->id }}"
                                        data-name="{{ $item->name }}"
                                        data-email="{{ $item->email }}"
                                        data-role="{{ $item->role }}"
                                        data-status="{{ $item->is_active ? '1' : '0' }}"
                                        data-update-url="{{ route('pjlab.pengguna.update', $item) }}">

                                        <i class="bi bi-pencil"></i>

                                    </button>


                                    <!-- HAPUS -->
                                    <form
                                        method="POST"
                                        action="{{ route('pjlab.pengguna.destroy', $item) }}"
                                        class="formHapusPengguna"
                                        data-name="{{ $item->name }}">

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="pengguna-action-btn danger"
                                            title="Hapus Pengguna">

                                            <i class="bi bi-trash"></i>

                                        </button>

                                    </form>

                                </div>

                            @endif

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="5">

                            <div class="pengguna-empty">

                                <i class="bi bi-inbox"></i>

                                Belum ada pengguna terdaftar.

                            </div>

                        </td>
                    </tr>

                @endforelse

                <tr id="rowTidakDitemukan" hidden>

                    <td colspan="5">

                        <div class="pengguna-empty">

                            <i class="bi bi-search"></i>

                            Tidak ada pengguna yang sesuai dengan filter.

                        </div>

                    </td>

                </tr>

            </tbody>

        </table>

    </div>

</div>


<!-- ==========================================
     MODAL TAMBAH / EDIT PENGGUNA
========================================== -->

<div
    class="modal fade pengguna-modal"
    id="modalPengguna"
    tabindex="-1"
    aria-labelledby="modalUserTitle"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">

        <div class="modal-content">

            <form
                id="formPengguna"
                action="{{ route('pjlab.pengguna.store') }}"
                method="POST">

                @csrf

                <!-- Dipakai ketika melakukan edit -->
                <input
                    type="hidden"
                    name="_method"
                    id="formMethod"
                    value="PATCH"
                    disabled>

                <!-- Untuk membuka kembali modal jika validasi gagal -->
                <input
                    type="hidden"
                    name="form_mode"
                    id="formMode"
                    value="add">

                <input
                    type="hidden"
                    name="editing_id"
                    id="editingId"
                    value="">


                <!-- MODAL HEADER -->
                <div class="modal-header">

                    <h5
                        class="modal-title fw-bold"
                        id="modalUserTitle">

                        Tambah Pengguna

                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Tutup">
                    </button>

                </div>


                <!-- MODAL BODY -->
                <div class="modal-body p-4">

                    <!-- NAMA -->
                    <div class="mb-3">

                        <label
                            for="userNama"
                            class="form-label">

                            Nama Lengkap

                        </label>

                        <input
                            type="text"
                            name="name"
                            id="userNama"
                            class="form-control"
                            maxlength="255"
                            autocomplete="name"
                            required>

                    </div>


                    <!-- EMAIL -->
                    <div class="mb-3">

                        <label
                            for="userEmail"
                            class="form-label">

                            Email

                        </label>

                        <input
                            type="email"
                            name="email"
                            id="userEmail"
                            class="form-control"
                            maxlength="255"
                            autocomplete="off"
                            required>

                    </div>


                    <!-- ROLE -->
                    <div class="mb-3">

                        <label
                            for="userRole"
                            class="form-label">

                            Role Pengguna

                        </label>

                        <select
                            name="role"
                            id="userRole"
                            class="form-select"
                            required>

                            <option value="guru">
                                Guru
                            </option>

                            <option value="kamad">
                                Kepala Madrasah
                            </option>

                        </select>

                    </div>


                    <!-- STATUS -->
                    <div class="mb-3">

                        <label
                            for="userStatus"
                            class="form-label">

                            Status Akun

                        </label>

                        <select
                            name="is_active"
                            id="userStatus"
                            class="form-select"
                            required>

                            <option value="1">
                                Aktif
                            </option>

                            <option value="0">
                                Nonaktif
                            </option>

                        </select>

                    </div>


                    <!-- PASSWORD -->
                    <div class="mb-3">

                        <label
                            for="userPassword"
                            class="form-label">

                            Password

                        </label>

                        <input
                            type="password"
                            name="password"
                            id="userPassword"
                            class="form-control"
                            minlength="8"
                            autocomplete="new-password">

                        <div
                            class="pengguna-password-note mt-2"
                            id="passwordHelp">

                            Password wajib diisi saat membuat akun baru.
                            Minimal 8 karakter.

                        </div>

                    </div>


                    <!-- KONFIRMASI PASSWORD -->
                    <div class="mb-2">

                        <label
                            for="userPasswordConfirmation"
                            class="form-label">

                            Konfirmasi Password

                        </label>

                        <input
                            type="password"
                            name="password_confirmation"
                            id="userPasswordConfirmation"
                            class="form-control"
                            minlength="8"
                            autocomplete="new-password">

                    </div>

                </div>


                <!-- MODAL FOOTER -->
                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-outline-secondary"
                        data-bs-dismiss="modal">

                        Batal

                    </button>

                    <button
                        type="submit"
                        class="pengguna-btn-primary">

                        <i class="bi bi-save"></i>

                        Simpan

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection


@push('scripts')

<!-- Bootstrap JS untuk modal -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | ELEMEN
    |--------------------------------------------------------------------------
    */

    const form = document.getElementById('formPengguna');

    const modalElement =
        document.getElementById('modalPengguna');

    const modal = bootstrap.Modal.getOrCreateInstance(
        modalElement
    );

    const formMethod = document.getElementById('formMethod');
    const formMode = document.getElementById('formMode');
    const editingId = document.getElementById('editingId');

    const nama = document.getElementById('userNama');
    const email = document.getElementById('userEmail');
    const role = document.getElementById('userRole');
    const status = document.getElementById('userStatus');

    const password = document.getElementById('userPassword');

    const confirmation =
        document.getElementById('userPasswordConfirmation');

    const passwordHelp =
        document.getElementById('passwordHelp');

    const modalTitle =
        document.getElementById('modalUserTitle');

    const storeUrl = @json(route('pjlab.pengguna.store'));


    /*
    |--------------------------------------------------------------------------
    | MODAL TAMBAH
    |--------------------------------------------------------------------------
    */

    function openTambah() {

        form.reset();

        form.action = storeUrl;

        formMethod.disabled = true;

        formMode.value = 'add';
        editingId.value = '';

        modalTitle.textContent = 'Tambah Pengguna';

        role.value = 'guru';
        status.value = '1';

        password.required = true;
        confirmation.required = true;

        passwordHelp.textContent =
            'Password wajib diisi. Minimal 8 karakter.';

        modal.show();
    }


    /*
    |--------------------------------------------------------------------------
    | MODAL EDIT
    |--------------------------------------------------------------------------
    */

    function openEdit(button) {

        form.reset();

        form.action = button.dataset.updateUrl;

        formMethod.disabled = false;

        formMode.value = 'edit';
        editingId.value = button.dataset.id;

        modalTitle.textContent = 'Edit Pengguna';

        nama.value = button.dataset.name;
        email.value = button.dataset.email;

        role.value = button.dataset.role;
        status.value = button.dataset.status;

        password.value = '';
        confirmation.value = '';

        password.required = false;
        confirmation.required = false;

        passwordHelp.textContent =
            'Kosongkan password jika tidak ingin menggantinya. '
            + 'Isi password baru minimal 8 karakter untuk reset password.';

        modal.show();
    }


    /*
    |--------------------------------------------------------------------------
    | EVENT TOMBOL TAMBAH
    |--------------------------------------------------------------------------
    */

    document.getElementById('btnTambahPengguna')
        .addEventListener('click', openTambah);


    /*
    |--------------------------------------------------------------------------
    | EVENT TOMBOL EDIT
    |--------------------------------------------------------------------------
    */

    document.querySelectorAll('.btnEditPengguna')
        .forEach(function (button) {

            button.addEventListener('click', function () {
                openEdit(this);
            });

        });


    /*
    |--------------------------------------------------------------------------
    | SEARCH DAN FILTER
    |--------------------------------------------------------------------------
    */

    const search = document.getElementById('searchUser');

    const filterRole = document.getElementById('filterRole');

    const filterStatus =
        document.getElementById('filterStatus');

    const rows = document.querySelectorAll('.pengguna-row');

    const emptyRow =
        document.getElementById('rowTidakDitemukan');

    function filterPengguna() {

        const keyword =
            search.value.trim().toLowerCase();

        const selectedRole = filterRole.value;

        const selectedStatus = filterStatus.value;

        let totalVisible = 0;

        rows.forEach(function (row) {

            const matchSearch =
                row.dataset.search.includes(keyword);

            const matchRole =
                !selectedRole ||
                row.dataset.role === selectedRole;

            const matchStatus =
                !selectedStatus ||
                row.dataset.status === selectedStatus;

            const visible =
                matchSearch &&
                matchRole &&
                matchStatus;

            row.hidden = !visible;

            if (visible) {
                totalVisible++;
            }

        });

        emptyRow.hidden = totalVisible > 0;

    }

    search.addEventListener('input', filterPengguna);

    filterRole.addEventListener('change', filterPengguna);

    filterStatus.addEventListener('change', filterPengguna);


    /*
    |--------------------------------------------------------------------------
    | KONFIRMASI HAPUS
    |--------------------------------------------------------------------------
    */

    document.querySelectorAll('.formHapusPengguna')
        .forEach(function (deleteForm) {

            deleteForm.addEventListener('submit', function (event) {

                event.preventDefault();

                const targetForm = this;

                const userName = this.dataset.name;

                if (typeof Swal !== 'undefined') {

                    Swal.fire({
                        title: 'Hapus pengguna ini?',
                        text: userName + ' akan dihapus dari sistem.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d64545',
                        cancelButtonColor: '#94a3b8',
                        confirmButtonText: 'Ya, Hapus',
                        cancelButtonText: 'Batal'
                    }).then(function (result) {

                        if (result.isConfirmed) {
                            targetForm.submit();
                        }

                    });

                } else {

                    if (confirm(
                        'Yakin ingin menghapus ' + userName + '?'
                    )) {
                        targetForm.submit();
                    }

                }

            });

        });


    /*
    |--------------------------------------------------------------------------
    | JIKA VALIDASI GAGAL, BUKA MODAL KEMBALI
    |--------------------------------------------------------------------------
    */

    @if($errors->any())

        const oldMode = @json(old('form_mode', 'add'));

        const oldId = @json(old('editing_id', ''));

        if (oldMode === 'edit' && oldId) {

            const editButton = Array.from(
                document.querySelectorAll('.btnEditPengguna')
            ).find(function (button) {

                return String(button.dataset.id) ===
                    String(oldId);

            });

            if (editButton) {
                openEdit(editButton);
            } else {
                openTambah();
            }

        } else {

            openTambah();

        }

        nama.value = @json(old('name', ''));
        email.value = @json(old('email', ''));
        role.value = @json(old('role', 'guru'));
        status.value = @json(old('is_active', '1'));

    @endif

});
</script>
@endpush
