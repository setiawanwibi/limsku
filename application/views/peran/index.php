<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
    /* =========================================================
       DAFTAR PERAN
       Perubahan tampilan saja, fungsi tetap dipertahankan
       ========================================================= */

    .role-page {
        color: #294257;
        font-size: .84rem;
    }

    /* Notifikasi */
    .role-page .alert {
        border-radius: 8px;
        font-size: .78rem;
        line-height: 1.5;
        box-shadow: 0 3px 10px rgba(35, 58, 76, .04);
    }

    /* Kartu utama */
    .role-card {
        overflow: hidden;
        background: #fff;
        border: 1px solid #e0e8ed;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(35, 58, 76, .05);
    }

    /* Header */
    .role-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        padding: 17px 20px;
        background: #fff;
        border-bottom: 1px solid #e5ebee;
    }

    .role-heading {
        display: flex;
        align-items: center;
        gap: 9px;
        margin: 0;
        color: #294257;
        font-size: .98rem;
        font-weight: 700;
    }

    .role-heading i {
        color: #159b98;
        font-size: 1.1rem;
    }

    .role-header-actions {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
    }

    .role-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        min-height: 34px;
        padding: 7px 11px;
        border: 1px solid transparent;
        border-radius: 6px;
        font-size: .70rem;
        font-weight: 700;
        text-decoration: none;
        white-space: nowrap;
        transition: .18s ease;
        cursor: pointer;
    }

    .role-btn-permission {
        color: #5e7280;
        background: #fff;
        border-color: #d9e3e7;
    }

    .role-btn-permission:hover {
        color: #168f8b;
        background: #f2faf9;
        border-color: #c6dfdd;
    }

    .role-btn-add {
        color: #fff;
        background: #159b98;
        border-color: #159b98;
        box-shadow: 0 2px 6px rgba(21, 155, 152, .12);
    }

    .role-btn-add:hover {
        color: #fff;
        background: #118b88;
        border-color: #118b88;
        transform: translateY(-1px);
    }

    /* Bagian tabel */
    .role-body {
        padding: 18px 20px;
    }

    .role-table-wrap {
        width: 100%;
        overflow-x: auto;
        border: 1px solid #e3eaee;
        border-radius: 7px;
    }

    .role-table {
        width: 100%;
        min-width: 680px;
        margin: 0;
        color: #536b79;
        font-size: .75rem;
    }

    .role-table thead th {
        padding: 11px 12px;
        background: #f7fafb;
        color: #708490;
        border-bottom: 1px solid #dfe7eb;
        font-size: .64rem;
        font-weight: 700;
        letter-spacing: .025em;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .role-table tbody td {
        padding: 12px;
        border-color: #edf1f3;
        vertical-align: middle;
        line-height: 1.5;
    }

    .role-table tbody tr {
        transition: background .15s ease;
    }

    .role-table tbody tr:hover {
        background: #f8fbfb;
    }

    .role-number {
        color: #8a9aa4;
        font-size: .70rem;
        font-weight: 600;
    }

    .role-name {
        color: #354f60;
        font-size: .76rem;
        font-weight: 700;
    }

    .role-description {
        color: #70838f;
        font-size: .72rem;
        line-height: 1.5;
    }

    /* Jumlah pengguna */
    .role-user-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        min-height: 25px;
        padding: 4px 8px;
        border: 1px solid #dce5e9;
        border-radius: 5px;
        background: #f4f7f8;
        color: #617786;
        font-size: .66rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .role-user-count i {
        font-size: .72rem;
    }

    /* Tombol aksi */
    .role-actions {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 5px;
    }

    .role-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        min-height: 28px;
        padding: 5px 8px;
        border: 1px solid;
        border-radius: 5px;
        background: #fff;
        font-size: .65rem;
        font-weight: 700;
        line-height: 1.2;
        text-decoration: none;
        white-space: nowrap;
        transition: .15s ease;
    }

    .role-action-access {
        color: #25835a;
        border-color: #cce7d7;
    }

    .role-action-access:hover {
        color: #fff;
        border-color: #25835a;
        background: #25835a;
    }

    .role-action-edit {
        color: #347eb5;
        border-color: #d1e3f3;
    }

    .role-action-edit:hover {
        color: #fff;
        border-color: #347eb5;
        background: #347eb5;
    }

    .role-action-delete {
        color: #c05252;
        border-color: #efcece;
    }

    .role-action-delete:hover {
        color: #fff;
        border-color: #c05252;
        background: #c05252;
    }

    /* Kondisi kosong */
    .role-empty {
        padding: 35px 15px !important;
        color: #8797a1;
        text-align: center;
        font-size: .74rem;
    }

    .role-empty i {
        display: block;
        margin-bottom: 8px;
        color: #9aabb4;
        font-size: 1.5rem;
    }

    /* Modal Master Permission */
    .role-modal .modal-content {
        overflow: hidden;
        border: 1px solid #e0e8ed;
        border-radius: 10px;
        box-shadow: 0 12px 35px rgba(35, 58, 76, .13);
    }

    .role-modal .modal-header {
        padding: 16px 19px;
        border-bottom: 1px solid #e5ebee;
        background: #fff;
    }

    .role-modal .modal-title {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #294257;
        font-size: .95rem;
        font-weight: 700;
    }

    .role-modal .modal-title i {
        color: #159b98;
    }

    .role-modal .modal-body {
        padding: 19px;
    }

    .role-modal .form-label {
        margin-bottom: 6px;
        color: #526779;
        font-size: .76rem;
        font-weight: 700;
    }

    .role-modal .form-control {
        min-height: 37px;
        padding: 8px 10px;
        border: 1px solid #dce5e9;
        border-radius: 6px;
        color: #405968;
        font-size: .76rem;
        box-shadow: none;
    }

    .role-modal .form-control:focus {
        border-color: #a7d3d0;
        box-shadow: 0 0 0 3px rgba(21, 155, 152, .08);
    }

    .role-modal .form-text,
    .role-modal .text-muted {
        color: #82929c !important;
        font-size: .68rem;
    }

    .role-modal .modal-footer {
        gap: 7px;
        padding: 13px 19px;
        border-top: 1px solid #e8eef1;
        background: #fbfcfd;
    }

    .role-modal .modal-footer .btn {
        min-height: 33px;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: .72rem;
        font-weight: 700;
    }

    .role-modal .btn-primary {
        border-color: #159b98;
        background: #159b98;
    }

    .role-modal .btn-primary:hover {
        border-color: #118b88;
        background: #118b88;
    }

    .role-modal .btn-secondary {
        border-color: #d7e0e5;
        background: #fff;
        color: #657887;
    }

    /* Responsif */
    @media (max-width: 767.98px) {
        .role-header {
            align-items: flex-start;
            flex-direction: column;
            padding: 15px;
        }

        .role-header-actions {
            width: 100%;
        }

        .role-header-actions .role-btn {
            flex: 1;
        }

        .role-body {
            padding: 12px;
        }
    }

    @media (max-width: 480px) {
        .role-heading {
            font-size: .91rem;
        }

        .role-header-actions {
            align-items: stretch;
            flex-direction: column;
        }

        .role-header-actions .role-btn {
            width: 100%;
        }
    }
</style>

<div class="role-page">

    <!-- Flash Messages -->
    <?php if ($this->session->flashdata('pesan_sukses')): ?>
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            <?php echo html_escape($this->session->flashdata('pesan_sukses')); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('pesan_gagal')): ?>
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <?php echo html_escape($this->session->flashdata('pesan_gagal')); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Kartu Daftar Peran -->
    <div class="role-card mb-4">

        <div class="role-header">
            <h5 class="role-heading">
                <i class="bi bi-people"></i>
                Daftar Peran (Roles)
            </h5>

            <div class="role-header-actions">
                <button
                    type="button"
                    class="role-btn role-btn-permission"
                    data-bs-toggle="modal"
                    data-bs-target="#modalTambahPermission"
                >
                    <i class="bi bi-shield-lock"></i>
                    Master Permission
                </button>

                <a
                    href="<?php echo site_url('peran/tambah'); ?>"
                    class="role-btn role-btn-add"
                >
                    <i class="bi bi-plus-lg"></i>
                    Tambah Peran
                </a>
            </div>
        </div>

        <div class="role-body">
            <div class="role-table-wrap">
                <table class="table table-hover align-middle role-table">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th>Nama Peran</th>
                            <th>Keterangan</th>
                            <th>Pengguna Terkait</th>
                            <th width="25%">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (!empty($daftar_peran)): ?>
                            <?php $no = 1; foreach ($daftar_peran as $r): ?>
                                <tr>
                                    <td>
                                        <span class="role-number">
                                            <?php echo $no++; ?>
                                        </span>
                                    </td>

                                    <td>
                                        <strong class="role-name">
                                            <?php echo html_escape($r['nama_role']); ?>
                                        </strong>
                                    </td>

                                    <td>
                                        <span class="role-description">
                                            <?php echo html_escape($r['keterangan'] ?: '-'); ?>
                                        </span>
                                    </td>

                                    <td>
                                        <span class="role-user-count">
                                            <i class="bi bi-person"></i>
                                            <?php echo $r['total_pengguna']; ?> Pengguna
                                        </span>
                                    </td>

                                    <td>
                                        <div class="role-actions">
                                            <a
                                                href="<?php echo site_url('peran/hak-akses/' . $r['id']); ?>"
                                                class="role-action role-action-access"
                                                title="Pengaturan Hak Akses"
                                            >
                                                <i class="bi bi-shield-check"></i>
                                                Hak Akses
                                            </a>

                                            <a
                                                href="<?php echo site_url('peran/edit/' . $r['id']); ?>"
                                                class="role-action role-action-edit"
                                                title="Edit Peran"
                                            >
                                                <i class="bi bi-pencil-square"></i>
                                                Edit
                                            </a>

                                            <?php if ($r['total_pengguna'] == 0): ?>
                                                <a
                                                    href="<?php echo site_url('peran/hapus/' . $r['id']); ?>"
                                                    class="role-action role-action-delete"
                                                    onclick="return confirm('Yakin ingin menghapus peran ini?');"
                                                    title="Hapus"
                                                >
                                                    <i class="bi bi-trash"></i>
                                                    Hapus
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="role-empty">
                                    <i class="bi bi-inbox"></i>
                                    Belum ada data peran.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<!-- Modal Tambah Master Permission -->
<div
    class="modal fade role-modal"
    id="modalTambahPermission"
    tabindex="-1"
    aria-labelledby="labelModalPermission"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <form action="<?php echo site_url('peran/permission/tambah'); ?>" method="post">
                <input
                    type="hidden"
                    name="<?php echo $this->security->get_csrf_token_name(); ?>"
                    value="<?php echo $this->security->get_csrf_hash(); ?>"
                >

                <div class="modal-header">
                    <h5 class="modal-title" id="labelModalPermission">
                        <i class="bi bi-shield-lock"></i>
                        Tambah Master Permission
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_permission" class="form-label">
                            Kode / Nama Permission <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="nama_permission"
                            name="nama_permission"
                            placeholder="contoh: pengguna_view, sampel_import"
                            required
                        >

                        <small class="form-text">
                            Gunakan huruf kecil dan garis bawah (snake_case).
                        </small>
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">
                            Deskripsi <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="deskripsi"
                            name="deskripsi"
                            placeholder="Melihat daftar pengguna"
                            required
                        >
                    </div>

                    <div class="mb-0">
                        <label for="kategori" class="form-label">
                            Kategori Modul
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="kategori"
                            name="kategori"
                            placeholder="contoh: Pengguna, Sampel, Pengujian"
                        >
                    </div>
                </div>

                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >
                        Batal
                    </button>

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check2-circle me-1"></i>
                        Simpan Permission
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
