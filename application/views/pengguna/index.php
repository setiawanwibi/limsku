<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
    /* =========================================================
       MANAJEMEN PENGGUNA
       Perubahan tampilan saja
       ========================================================= */

    .user-management-page {
        color: #294257;
        font-size: .9rem;
    }

    .user-management-card {
        width: 100%;
        overflow: hidden;
        background: #fff;
        border: 1px solid #e0e8ed;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(35, 58, 76, .05);
    }

    .user-management-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        padding: 18px 22px;
        border-bottom: 1px solid #e7edf0;
        background: #fff;
    }

    .user-management-heading {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }

    .user-management-header-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        flex-shrink: 0;
        border-radius: 8px;
        background: #e5f6f4;
        color: #159b98;
        font-size: 1.15rem;
    }

    .user-management-title {
        margin: 0;
        color: #294257;
        font-size: 1.08rem;
        font-weight: 700;
        line-height: 1.4;
    }

    .user-management-subtitle {
        margin: 4px 0 0;
        color: #8797a1;
        font-size: .8rem;
        line-height: 1.5;
    }

    .user-management-btn-add {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 39px;
        padding: 9px 15px;
        flex-shrink: 0;
        border: 1px solid #159b98;
        border-radius: 7px;
        background: #159b98;
        color: #fff;
        font-size: .83rem;
        font-weight: 700;
        text-decoration: none;
        transition: .18s ease;
    }

    .user-management-btn-add:hover {
        border-color: #118b88;
        background: #118b88;
        color: #fff;
        box-shadow: 0 3px 8px rgba(21, 155, 152, .15);
    }

    .user-management-body {
        padding: 22px;
    }

    .user-management-page .alert {
        margin-bottom: 18px;
        padding: 12px 15px;
        border-radius: 7px;
        font-size: .85rem;
        line-height: 1.6;
    }

    /* Tabel */
    .user-management-table-wrap {
        width: 100%;
        overflow-x: auto;
        padding-bottom: 2px;
    }

    .user-management-page .table {
        width: 100% !important;
        margin-bottom: 0;
        color: #40596a;
        font-size: .84rem;
        vertical-align: middle;
        border-color: #e7edf0;
    }

    .user-management-page .table thead th {
        padding: 13px 12px;
        border-bottom: 1px solid #dfe8ec;
        background: #f5faf9;
        color: #526b7a;
        font-size: .78rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .user-management-page .table tbody td {
        padding: 13px 12px;
        border-bottom: 1px solid #edf1f3;
        vertical-align: middle;
    }

    .user-management-page .table tbody tr:last-child td {
        border-bottom: 0;
    }

    .user-management-page .table tbody tr:hover td {
        background-color: #f8fbfb;
    }

    .user-management-page .user-name {
        color: #294257;
        font-size: .85rem;
        font-weight: 700;
    }

    /* Badge role dan status */
    .user-management-page .badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 5px 9px;
        border-radius: 5px;
        font-size: .74rem;
        font-weight: 600;
        line-height: 1.4;
        white-space: nowrap;
    }

    .user-management-page .role-badge {
        border: 1px solid #ccece9;
        background: #e5f6f4;
        color: #167f7c;
    }

    .user-management-page .status-active {
        border: 1px solid #ccebd8;
        background: #e8f7ee;
        color: #23834d;
    }

    .user-management-page .status-inactive {
        border: 1px solid #e0e5e9;
        background: #f0f2f4;
        color: #657987;
    }

    /* Tombol aksi */
    .user-management-actions {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 5px;
        min-width: 230px;
    }

    .user-management-page .user-action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        min-height: 30px;
        padding: 5px 8px;
        border: 1px solid;
        border-radius: 5px;
        background: #fff;
        font-size: .74rem;
        font-weight: 600;
        line-height: 1.3;
        text-decoration: none;
        white-space: nowrap;
        transition: .15s ease;
    }

    .user-management-page .action-edit {
        border-color: #b9d9f8;
        color: #2878bb;
    }

    .user-management-page .action-edit:hover {
        background: #edf6ff;
    }

    .user-management-page .action-password {
        border-color: #f1d7a1;
        color: #a97612;
    }

    .user-management-page .action-password:hover {
        background: #fff8e8;
    }

    .user-management-page .action-status {
        border-color: #d8e1e6;
        color: #657987;
    }

    .user-management-page .action-status:hover {
        background: #f5f7f8;
    }

    .user-management-page .action-delete {
        border-color: #f1c4c4;
        color: #c94e4e;
    }

    .user-management-page .action-delete:hover {
        background: #fff1f1;
    }

    /* DataTables */
    .user-management-page .dataTables_wrapper {
        width: 100%;
        color: #657987;
        font-size: .82rem;
    }

    .user-management-page .dataTables_wrapper .dataTables_length,
    .user-management-page .dataTables_wrapper .dataTables_filter {
        margin-bottom: 16px;
        color: #657987;
        font-size: .82rem;
    }

    .user-management-page .dataTables_wrapper .dataTables_length label,
    .user-management-page .dataTables_wrapper .dataTables_filter label {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
    }

    .user-management-page .dataTables_wrapper .dataTables_filter label {
        justify-content: flex-end;
    }

    .user-management-page .dataTables_wrapper .dataTables_length select,
    .user-management-page .dataTables_wrapper .dataTables_filter input {
        min-height: 35px;
        padding: 6px 10px;
        border: 1px solid #dce5e9;
        border-radius: 6px;
        background: #fff;
        color: #40596a;
        font-size: .82rem;
        outline: none;
    }

    .user-management-page .dataTables_wrapper .dataTables_filter input {
        width: 190px;
        margin-left: 0;
    }

    .user-management-page .dataTables_wrapper .dataTables_length select:focus,
    .user-management-page .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #8fcac6;
        box-shadow: 0 0 0 3px rgba(21, 155, 152, .08);
    }

    .user-management-page .dataTables_wrapper .dataTables_info {
        padding-top: 16px;
        color: #8797a1;
        font-size: .8rem;
    }


/* =========================
   DATATABLES PAGINATION FIX
   Mencegah tombol pagination bertumpuk
   ========================= */

.user-management-page .dataTables_wrapper .dataTables_paginate {
    display: flex !important;
    justify-content: flex-end;
    align-items: center;
    flex-wrap: wrap;
    gap: 4px;
    padding-top: 12px !important;
    margin: 0 !important;
    border: 0 !important;
    background: transparent !important;
    box-shadow: none !important;
}

/* Reset wrapper tombol bawaan DataTables */
.user-management-page .dataTables_wrapper .dataTables_paginate .paginate_button {
    display: inline-flex !important;
    align-items: center;
    justify-content: center;
    float: none !important;
    position: static !important;
    box-sizing: border-box !important;
    min-width: 0 !important;
    min-height: 0 !important;
    width: auto !important;
    height: auto !important;
    margin: 0 !important;
    padding: 0 !important;
    border: 0 !important;
    border-radius: 0 !important;
    background: transparent !important;
    color: inherit !important;
    box-shadow: none !important;
    line-height: normal !important;
    transform: none !important;
}

/* Tampilan tombol yang sebenarnya */
.user-management-page .dataTables_wrapper .dataTables_paginate .paginate_button .page-link {
    display: inline-flex !important;
    align-items: center;
    justify-content: center;
    box-sizing: border-box !important;
    min-width: 32px !important;
    height: 32px !important;
    margin: 0 !important;
    padding: 5px 10px !important;
    border: 1px solid #e0e8ed !important;
    border-radius: 6px !important;
    background: #fff !important;
    color: #526b7a !important;
    font-size: .8rem !important;
    line-height: 1.3 !important;
    text-decoration: none !important;
    box-shadow: none !important;
    outline: none !important;
}

/* Halaman aktif */
.user-management-page .dataTables_wrapper .dataTables_paginate .paginate_button.current .page-link {
    border-color: #159b98 !important;
    background: #159b98 !important;
    color: #fff !important;
}

/* Hover tombol aktif */
.user-management-page .dataTables_wrapper .dataTables_paginate .paginate_button:not(.disabled):not(.current) .page-link:hover {
    border-color: #b9dfdc !important;
    background: #f0faf9 !important;
    color: #159b98 !important;
}

/* Tombol sebelumnya/berikutnya yang nonaktif */
.user-management-page .dataTables_wrapper .dataTables_paginate .paginate_button.disabled .page-link {
    background: #f3f5f6 !important;
    border-color: #e6ebee !important;
    color: #aab4bb !important;
    opacity: 1 !important;
    cursor: default !important;
}

/* Hilangkan pseudo-element dekoratif yang mungkin menggandakan tampilan */
.user-management-page .dataTables_wrapper .dataTables_paginate .paginate_button::before,
.user-management-page .dataTables_wrapper .dataTables_paginate .paginate_button::after,
.user-management-page .dataTables_wrapper .dataTables_paginate .page-link::before,
.user-management-page .dataTables_wrapper .dataTables_paginate .page-link::after {
    display: none !important;
}

@media (max-width: 575.98px) {
    .user-management-page .dataTables_wrapper .dataTables_paginate {
        justify-content: flex-start;
    }
}

    @media (max-width: 767.98px) {
        .user-management-header {
            align-items: flex-start;
            flex-direction: column;
            padding: 16px;
        }

        .user-management-body {
            padding: 17px 16px;
        }

        .user-management-btn-add {
            align-self: flex-start;
        }

        .user-management-page .dataTables_wrapper .dataTables_filter label {
            justify-content: flex-start;
        }

        .user-management-page .dataTables_wrapper .dataTables_filter input {
            width: 170px;
        }
    }

    @media (max-width: 575.98px) {
        .user-management-heading {
            gap: 10px;
        }

        .user-management-header-icon {
            width: 36px;
            height: 36px;
            font-size: 1rem;
        }

        .user-management-title {
            font-size: 1rem;
        }

        .user-management-subtitle {
            font-size: .76rem;
        }

        .user-management-page .table thead th {
            padding: 11px 10px;
        }

        .user-management-page .table tbody td {
            padding: 11px 10px;
        }

        .user-management-page .dataTables_wrapper .dataTables_length,
        .user-management-page .dataTables_wrapper .dataTables_filter {
            float: none;
            width: 100%;
        }

        .user-management-page .dataTables_wrapper .dataTables_filter input {
            max-width: 100%;
        }

        .user-management-page .dataTables_wrapper .dataTables_info {
            float: none;
            text-align: left;
        }

        .user-management-page .dataTables_wrapper .dataTables_paginate {
            float: none;
            justify-content: flex-start;
        }
    }
</style>

<div class="user-management-page">

    <!-- Flash Messages -->
    <?php if ($this->session->flashdata('pesan_sukses')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            <?php echo html_escape($this->session->flashdata('pesan_sukses')); ?>
            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close"
            ></button>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('pesan_gagal')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle-fill me-2"></i>
            <?php echo html_escape($this->session->flashdata('pesan_gagal')); ?>
            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close"
            ></button>
        </div>
    <?php endif; ?>

    <div class="user-management-card">

        <!-- Header -->
        <div class="user-management-header">

            <div class="user-management-heading">
                <div class="user-management-header-icon">
                    <i class="bi bi-people"></i>
                </div>

                <div>
                    <h5 class="user-management-title">Daftar Pengguna</h5>
                    <p class="user-management-subtitle">
                        Kelola akun, peran, laboratorium, dan status pengguna sistem.
                    </p>
                </div>
            </div>

            <a
                href="<?php echo site_url('pengguna/tambah'); ?>"
                class="user-management-btn-add"
            >
                <i class="bi bi-person-plus"></i>
                Tambah Pengguna
            </a>

        </div>

        <!-- Table -->
        <div class="user-management-body">

            <div class="user-management-table-wrap">

                <table id="tabel-pengguna" class="table table-hover align-middle w-100">

                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th>Nama Pengguna</th>
                            <th>NIP</th>
                            <th>Nama Lengkap</th>
                            <th>Role / Peran</th>
                            <th>Laboratorium</th>
                            <th>Status</th>
                            <th width="18%">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if (!empty($daftar_pengguna)): ?>

                            <?php $no = 1; foreach ($daftar_pengguna as $u): ?>

                                <tr>
                                    <td><?php echo $no++; ?></td>

                                    <td>
                                        <strong class="user-name">
                                            <?php echo html_escape($u['username']); ?>
                                        </strong>
                                    </td>

                                    <td>
                                        <?php echo html_escape($u['nip'] ?: '-'); ?>
                                    </td>

                                    <td>
                                        <?php echo html_escape($u['nama_lengkap']); ?>
                                    </td>

                                    <td>
                                        <span class="badge role-badge">
                                            <?php echo html_escape($u['nama_role'] ?: '-'); ?>
                                        </span>
                                    </td>

                                    <td>
                                        <?php echo html_escape($u['nama_lab'] ?: 'Seluruh Lab / Non-Lab'); ?>
                                    </td>

                                    <td>
                                        <?php if ($u['status'] === 'Aktif'): ?>
                                            <span class="badge status-active">
                                                <i class="bi bi-check-circle me-1"></i>
                                                Aktif
                                            </span>
                                        <?php else: ?>
                                            <span class="badge status-inactive">
                                                <i class="bi bi-dash-circle me-1"></i>
                                                Nonaktif
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <div class="user-management-actions">

                                            <a
                                                href="<?php echo site_url('pengguna/edit/' . $u['id']); ?>"
                                                class="user-action-btn action-edit"
                                                title="Edit Data"
                                            >
                                                <i class="bi bi-pencil-square"></i>
                                                Edit
                                            </a>

                                            <a
                                                href="<?php echo site_url('pengguna/password/' . $u['id']); ?>"
                                                class="user-action-btn action-password"
                                                title="Ubah Password"
                                            >
                                                <i class="bi bi-key"></i>
                                                Password
                                            </a>

                                            <a
                                                href="<?php echo site_url('pengguna/status/' . $u['id']); ?>"
                                                class="user-action-btn action-status"
                                                title="Ubah Status"
                                            >
                                                <i class="bi bi-arrow-left-right"></i>
                                                Status
                                            </a>

                                            <?php if ($u['id'] != $this->session->userdata('user_id')): ?>

                                                <a
                                                    href="<?php echo site_url('pengguna/hapus/' . $u['id']); ?>"
                                                    class="user-action-btn action-delete"
                                                    onclick="return confirm('Yakin ingin menghapus pengguna ini?');"
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
                                <td colspan="8" class="text-center text-muted py-4">
                                    <i class="bi bi-people fs-4 d-block mb-2"></i>
                                    Belum ada data pengguna.
                                </td>
                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof $.fn.DataTable !== 'undefined') {
        $('#tabel-pengguna').DataTable({
            language: {
                url: '<?php echo base_url('assets/js/dataTables.indonesian.json'); ?>'
            }
        });
    }
});
</script>
