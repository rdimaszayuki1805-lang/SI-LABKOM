
@extends('layouts.pjlab')

@section('title', 'Manajemen Pengguna Logbook (Siswa)')

@section('content')

<div class="pjlab-siswa-page">

    <!-- ==========================================
         JUDUL HALAMAN
    ========================================== -->

    <div class="siswa-heading">

        <div>
            <h4>
                Manajemen Pengguna Logbook (Siswa)
            </h4>

            <p>
                Kelola akun siswa untuk mengakses
                dan mengisi Logbook Laboratorium.
            </p>
        </div>

        <div class="siswa-actions">

            <a
                href="{{ route('pjlab.siswa.template') }}"
                class="siswa-btn siswa-btn-outline">

                <i class="bi bi-download"></i>
                Template Excel

            </a>

        </div>

    </div>


    <!-- ==========================================
         PESAN BERHASIL
    ========================================== -->

    @if(session('success'))

        <div class="alert alert-success" role="alert">

            <i class="bi bi-check-circle me-2"></i>

            {{ session('success') }}

        </div>

    @endif


    <!-- ==========================================
         PESAN VALIDASI
    ========================================== -->

    @if($errors->any())

        <div class="alert alert-danger" role="alert">

            <strong>
                Data belum dapat disimpan:
            </strong>

            <ul class="mb-0 mt-2">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif


    <!-- ==========================================
         PILIH KELAS
    ========================================== -->

    <div class="siswa-card">

        <div class="row g-3 align-items-end">

            <div class="col-md-5">

                <label
                    for="pilihKelas"
                    class="siswa-field-label">

                    Pilih Kelas

                </label>

                <select
                    id="pilihKelas"
                    class="siswa-input"
                    onchange="window.location.href = this.value">

                    @foreach($daftarKelas as $item)

                        <option
                            value="{{ route('pjlab.siswa.index', ['kelas' => $item]) }}"
                            @selected($kelas === $item)>

                            Kelas {{ $item }}

                        </option>

                    @endforeach

                </select>

            </div>

            <div class="col-md-3">

                <div class="siswa-field-label">
                    Kelas Aktif
                </div>

                <strong>
                    Kelas {{ $kelas }}
                </strong>

            </div>

            <div class="col-md-4">

                <div class="siswa-field-label">
                    Jumlah Siswa
                </div>

                <strong>
                    {{ $siswa->count() }} Siswa
                </strong>

            </div>

        </div>

    </div>


    <!-- ==========================================
         FORM TAMBAH SISWA
    ========================================== -->

    <div class="siswa-card">

        <details
            class="siswa-panel"
            @if($errors->has('nama') ||
                $errors->has('nisn') ||
                $errors->has('password'))
                open
            @endif>

            <summary>

                <i class="bi bi-person-plus me-2"></i>

                Tambah Siswa Manual

            </summary>

            <div class="siswa-form-content">

                <form
                    action="{{ route('pjlab.siswa.store') }}"
                    method="POST">

                    @csrf

                    <input
                        type="hidden"
                        name="kelas"
                        value="{{ $kelas }}">

                    <div class="row g-3">

                        <div class="col-md-6">

                            <label
                                class="siswa-field-label"
                                for="nama">

                                Nama Siswa

                            </label>

                            <input
                                type="text"
                                id="nama"
                                name="nama"
                                class="siswa-input"
                                maxlength="150"
                                value="{{ old('nama') }}"
                                placeholder="Nama lengkap siswa"
                                required>

                        </div>


                        <div class="col-md-6">

                            <label
                                class="siswa-field-label"
                                for="nisn">

                                NISN

                            </label>

                            <input
                                type="text"
                                id="nisn"
                                name="nisn"
                                class="siswa-input"
                                inputmode="numeric"
                                pattern="[0-9]{10}"
                                maxlength="10"
                                value="{{ old('nisn') }}"
                                placeholder="10 digit NISN"
                                required>

                        </div>


                        <div class="col-md-6">

                            <label
                                class="siswa-field-label"
                                for="password">

                                Password

                            </label>

                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="siswa-input"
                                minlength="8"
                                autocomplete="new-password"
                                placeholder="Minimal 8 karakter"
                                required>

                        </div>


                        <div class="col-md-6">

                            <label
                                class="siswa-field-label"
                                for="password_confirmation">

                                Konfirmasi Password

                            </label>

                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                class="siswa-input"
                                minlength="8"
                                autocomplete="new-password"
                                placeholder="Ulangi password"
                                required>

                        </div>

                        <div class="col-12">

                            <button
                                type="submit"
                                class="siswa-btn">

                                <i class="bi bi-save"></i>

                                Simpan Siswa ke Kelas {{ $kelas }}

                            </button>

                        </div>

                    </div>

                </form>

            </div>

        </details>

    </div>


    <!-- ==========================================
         IMPORT EXCEL
    ========================================== -->

    <div class="siswa-card">

        <details class="siswa-panel">

            <summary>

                <i class="bi bi-file-earmark-excel me-2"></i>

                Import Siswa dari Excel

            </summary>

            <div class="siswa-form-content">

                <p class="text-muted small">

                    Unduh template Excel, isi kolom
                    nama, nisn, dan password.
                    Seluruh siswa akan dimasukkan ke
                    <strong>Kelas {{ $kelas }}</strong>.

                </p>

                <form
                    action="{{ route('pjlab.siswa.import') }}"
                    method="POST"
                    enctype="multipart/form-data">

                    @csrf

                    <input
                        type="hidden"
                        name="kelas"
                        value="{{ $kelas }}">

                    <div class="row g-3">

                        <div class="col-md-8">

                            <label
                                for="fileExcel"
                                class="siswa-field-label">

                                Pilih File Excel (.xlsx)

                            </label>

                            <input
                                type="file"
                                id="fileExcel"
                                name="file_excel"
                                class="siswa-input"
                                accept=".xlsx"
                                required>

                        </div>

                        <div class="col-md-4 d-flex align-items-end">

                            <button
                                type="submit"
                                class="siswa-btn w-100">

                                <i class="bi bi-upload"></i>

                                Import Excel

                            </button>

                        </div>

                    </div>

                </form>

                <p class="small text-muted mt-3 mb-0">

                    Maksimal 500 siswa dalam satu file,
                    ukuran maksimal 2 MB.
                    Jika terdapat NISN duplikat atau
                    baris tidak valid, proses import
                    tidak akan menyimpan sebagian data.

                </p>

            </div>

        </details>

    </div>


    <!-- ==========================================
         TABEL DATA SISWA
    ========================================== -->

    <div class="siswa-card">

        <div class="row g-3 align-items-center mb-3">

            <div class="col-md-6">

                <h5 class="mb-1 fw-bold">

                    <i class="bi bi-people me-2 text-success"></i>

                    Daftar Siswa Kelas {{ $kelas }}

                </h5>

                <p class="text-muted small mb-0">

                    Kelola identitas dan password akun siswa.

                </p>

            </div>

            <div class="col-md-6">

                <input
                    type="search"
                    id="cariSiswa"
                    class="siswa-input"
                    placeholder="Cari nama atau NISN..."
                    aria-label="Cari siswa">

            </div>

        </div>


        <div class="siswa-table-wrap">

            <table class="siswa-table">

                <thead>

                    <tr>

                        <th width="55">No.</th>

                        <th>Nama Siswa</th>

                        <th>NISN</th>

                        <th>Password</th>

                        <th width="180">Aksi</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($siswa as $item)

                        <tr class="baris-siswa">

                            <td>
                                {{ $loop->iteration }}
                            </td>

                            <td>
                                <strong>
                                    {{ $item->nama }}
                                </strong>
                            </td>

                            <td>
                                {{ $item->nisn }}
                            </td>

                            <td>

                                <span class="siswa-password"
                                      title="Password disimpan dengan aman">

                                    ••••••••

                                </span>

                            </td>

                            <td>

                                <div class="siswa-row-actions">

                                    <!-- EDIT -->
                                    <button
                                        type="button"
                                        class="siswa-action tombol-edit-siswa"
                                        data-nama="{{ $item->nama }}"
                                        data-nisn="{{ $item->nisn }}"
                                        data-url="{{ route('pjlab.siswa.update', ['siswa' => $item->id]) }}">

                                        <i class="bi bi-pencil-square"></i>
                                        Edit

                                    </button>


                                    <!-- HAPUS -->
                                    <form
                                        method="POST"
                                        action="{{ route('pjlab.siswa.destroy', ['siswa' => $item->id]) }}"
                                        onsubmit="return confirm('Yakin ingin menghapus akun siswa ini?')">

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="siswa-action siswa-action-danger">

                                            <i class="bi bi-trash"></i>
                                            Hapus

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr id="barisKosong">

                            <td colspan="5">

                                <div class="siswa-empty">

                                    <i class="bi bi-person-x"></i>

                                    <strong>
                                        Belum ada siswa di Kelas {{ $kelas }}
                                    </strong>

                                    <p class="mb-0 mt-2">

                                        Silakan tambahkan siswa secara
                                        manual atau import melalui Excel.

                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                    <tr id="barisHasilKosong" hidden>

                        <td colspan="5" class="text-center text-muted py-4">
                            Nama atau NISN tidak ditemukan.
                        </td>

                    </tr>

                </tbody>

            </table>

        </div>

    </div>

</div>


<!-- ==========================================
     MODAL EDIT SISWA
========================================== -->

<dialog
    id="modalEditSiswa"
    class="pjlab-siswa-dialog">

    <h5>

        <i class="bi bi-pencil-square me-2 text-success"></i>

        Edit Data Siswa

    </h5>

    <form
        id="formEditSiswa"
        method="POST">

        @csrf

        @method('PATCH')

        <div class="mb-3">

            <label
                for="editNama"
                class="siswa-field-label">

                Nama Siswa

            </label>

            <input
                type="text"
                id="editNama"
                name="nama"
                class="siswa-input"
                maxlength="150"
                required>

        </div>


        <div class="mb-3">

            <label
                for="editNisn"
                class="siswa-field-label">

                NISN

            </label>

            <input
                type="text"
                id="editNisn"
                name="nisn"
                class="siswa-input"
                inputmode="numeric"
                pattern="[0-9]{10}"
                maxlength="10"
                required>

        </div>


        <div class="mb-3">

            <label
                for="editPassword"
                class="siswa-field-label">

                Password Baru

            </label>

            <input
                type="password"
                id="editPassword"
                name="password"
                class="siswa-input"
                minlength="8"
                autocomplete="new-password"
                placeholder="Kosongkan jika tidak diganti">

        </div>


        <div class="mb-3">

            <label
                for="editPasswordConfirmation"
                class="siswa-field-label">

                Konfirmasi Password Baru

            </label>

            <input
                type="password"
                id="editPasswordConfirmation"
                name="password_confirmation"
                class="siswa-input"
                minlength="8"
                autocomplete="new-password"
                placeholder="Ulangi password baru">

        </div>


        <div class="d-flex flex-wrap gap-2 justify-content-end">

            <button
                type="button"
                id="tutupEditSiswa"
                class="siswa-btn siswa-btn-outline">

                Batal

            </button>

            <button
                type="submit"
                class="siswa-btn">

                <i class="bi bi-save"></i>

                Simpan Perubahan

            </button>

        </div>

    </form>

</dialog>


@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {

    /*
    |--------------------------------------------------------------------------
    | EDIT DATA SISWA
    |--------------------------------------------------------------------------
    */

    const modal = document.getElementById('modalEditSiswa');

    const form = document.getElementById('formEditSiswa');

    const inputNama = document.getElementById('editNama');

    const inputNisn = document.getElementById('editNisn');

    const inputPassword = document.getElementById('editPassword');

    const inputKonfirmasi =
        document.getElementById('editPasswordConfirmation');

    document.querySelectorAll('.tombol-edit-siswa')
        .forEach(function (button) {

            button.addEventListener('click', function () {

                form.action = button.dataset.url;

                inputNama.value = button.dataset.nama;

                inputNisn.value = button.dataset.nisn;

                inputPassword.value = '';

                inputKonfirmasi.value = '';

                modal.showModal();

            });

        });


    document.getElementById('tutupEditSiswa')
        .addEventListener('click', function () {

            modal.close();

        });


    /*
    |--------------------------------------------------------------------------
    | PENCARIAN SISWA
    |--------------------------------------------------------------------------
    */

    const inputCari = document.getElementById('cariSiswa');

    const baris = Array.from(
        document.querySelectorAll('.baris-siswa')
    );

    const hasilKosong =
        document.getElementById('barisHasilKosong');

    inputCari.addEventListener('input', function () {

        const kataKunci = inputCari.value
            .trim()
            .toLowerCase();

        let ditemukan = 0;

        baris.forEach(function (row) {

            const sesuai = row.textContent
                .toLowerCase()
                .includes(kataKunci);

            row.hidden = !sesuai;

            if (sesuai) {
                ditemukan++;
            }

        });

        hasilKosong.hidden =
            ditemukan > 0 || baris.length === 0;

    });

});
</script>

@endpush

@endsection
