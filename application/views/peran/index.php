<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
    /* =========================================================
       DAFTAR PERAN
       Perubahan tampilan saja, fungsi tetap dipertahankan
       ========================================================= */

    .role-page {
        color: #1e293b;
        font-size: .85rem;
    }

    /* Notifikasi */
    .role-page .alert {
        border-radius: 8px;
        font-size: .82rem;
        line-height: 1.5;
    }

    /* Kartu utama */
    .role-card {
        overflow: hidden;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
    }

    /* Header */
    .role-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        padding: 16px 22px;
        background: #fff;
        border-bottom: 1px solid #e2e8f0;
    }

    .role-heading-wrap {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }

    .role-header-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        flex-shrink: 0;
        border-radius: 8px;
        background: #f0f9ff;
        border: 1px solid #bae6fd;
        color: #0284c7;
        font-size: 1.05rem;
    }

    .role-heading {
        margin: 0;
        color: #0f172a;
        font-size: 1.05rem;
        font-weight: 700;
        line-height: 1.4;
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
        padding: 0.48rem 1rem;
        border: 1px solid transparent;
        border-radius: 8px;
        font-size: .82rem;
        font-weight: 600;
        text-decoration: none;
        white-space: nowrap;
        transition: .15s ease;
        cursor: pointer;
    }

    .role-btn-permission {
        color: #334155;
        background: #fff;
        border-color: #cbd5e1;
    }

    .role-btn-permission:hover {
        color: #0f172a;
        background: #f8fafc;
        border-color: #94a3b8;
    }

    .role-btn-add {
        color: #fff;
        background: #0284c7;
        border-color: #0284c7;
    }

    .role-btn-add:hover {
        color: #fff;
        background: #0369a1;
        border-color: #0369a1;
    }

    /* Bagian tabel */
    .role-body {
        padding: 20px 22px;
    }

    .role-table-wrap {
        width: 100%;
        overflow-x: auto;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
    }

    .role-table {
        width: 100%;
        min-width: 680px;
        margin: 0;
        color: #334155;
        font-size: 0.83rem;
    }

    .role-table thead th {
        padding: 11px 14px;
        background: #f8fafc;
        color: #475569;
        border-bottom: 1px solid #e2e8f0;
        font-size: 0.74rem;
        font-weight: 700;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .role-table tbody td {
        padding: 12px 14px;
        border-color: #f1f5f9;
        vertical-align: middle;
        line-height: 1.5;
        font-size: 0.83rem;
    }

    .role-table tbody tr {
        transition: background .15s ease;
    }

    .role-table tbody tr:hover {
        background: #f8fafc;
    }

    .role-number {
        color: #64748b;
        font-size: 0.82rem;
        font-weight: 600;
    }

    .role-name {
        color: #0f172a;
        font-size: 0.84rem;
        font-weight: 700;
    }

    .role-description {
        color: #64748b;
        font-size: 0.80rem;
        line-height: 1.5;
    }

    /* Jumlah pengguna */
    .role-user-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        padding: 4px 9px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        background: #f8fafc;
        color: #334155;
        font-size: 0.74rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .role-user-count i {
        font-size: 0.78rem;
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
        <?php
        $komoditi_curr = isset($komoditi_aktif) ? $komoditi_aktif : 'obat';
        $tab_items = array(
            'obat'     => array('label' => 'Obat', 'full' => 'Laboratorium Obat', 'icon' => 'bi-capsule'),
            'pangan'   => array('label' => 'Pangan', 'full' => 'Laboratorium Pangan', 'icon' => 'bi-cup-straw'),
            'herbal'   => array('label' => 'Obat Tradisional', 'full' => 'Laboratorium Obat Tradisional', 'icon' => 'bi-flower1'),
            'kosmetik' => array('label' => 'Kosmetik', 'full' => 'Laboratorium Kosmetik', 'icon' => 'bi-brush'),
            'suplemen' => array('label' => 'Suplemen', 'full' => 'Laboratorium Suplemen', 'icon' => 'bi-heart-pulse'),
            'semua'    => array('label' => 'Semua Komoditi', 'full' => 'Semua Komoditi', 'icon' => 'bi-collection')
        );
        ?>

        <div class="role-header">
            <div class="role-heading-wrap">
                <div class="role-header-icon">
                    <i class="bi bi-shield-check"></i>
                </div>
                <div>
                    <h5 class="role-heading">
                        Daftar Peran (Roles)
                    </h5>
                    <p class="text-muted small mb-0 mt-1" style="font-size: 0.8rem;">
                        Pengaturan peran RBAC dan jumlah personil pada komoditi terkait.
                    </p>
                </div>
            </div>

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

        <!-- Filter Tab Komoditi -->
        <div class="px-4 py-3 border-bottom" style="background: #f8fafc;">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                <div class="nav nav-pills gap-2">
                    <?php foreach ($tab_items as $k_key => $t_info): ?>
                        <?php $is_active = ($komoditi_curr === $k_key); ?>
                        <a href="<?php echo site_url('peran?komoditi=' . $k_key); ?>" 
                           class="nav-link btn btn-sm py-1 px-3 d-flex align-items-center gap-2 <?php echo $is_active ? 'active fw-semibold' : 'text-secondary bg-white border'; ?>" 
                           style="border-radius: 8px; <?php echo $is_active ? 'background-color: #0284c7; border-color: #0284c7; color: #fff; font-size: 0.82rem;' : 'font-size: 0.82rem; border-color: #cbd5e1;'; ?>">
                            <span><?php echo $t_info['label']; ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>
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
                                            <?php echo $r['total_pengguna']; ?> Personil
                                        </span>
                                        <?php if ($komoditi_curr !== 'semua'): ?>
                                            <div class="text-muted" style="font-size: 0.68rem;">(Total Sistem: <?php echo $r['total_pengguna_global']; ?>)</div>
                                        <?php endif; ?>
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
