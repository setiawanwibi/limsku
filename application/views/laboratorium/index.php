
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
    .laboratory-list-page {
        width: 100%;
        padding: 4px 0 22px;
        color: #294257;
    }

    .laboratory-list-page .laboratory-card {
        width: 100%;
        background: #ffffff;
        border: 1px solid #e0e8ed;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(41, 66, 87, 0.05);
        overflow: hidden;
    }

    .laboratory-list-page .laboratory-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 18px 22px;
        background: #ffffff;
        border-bottom: 1px solid #e8eef1;
    }

    .laboratory-list-page .laboratory-heading {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }

    .laboratory-list-page .laboratory-heading-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        width: 42px;
        height: 42px;
        border-radius: 10px;
        background: #e5f6f4;
        color: #159b98;
        font-size: 20px;
    }

    .laboratory-list-page .laboratory-title {
        margin: 0;
        color: #294257;
        font-size: 1.08rem;
        font-weight: 700;
    }

    .laboratory-list-page .laboratory-subtitle {
        margin: 4px 0 0;
        color: #7b8b98;
        font-size: 0.8rem;
        line-height: 1.5;
    }

    .laboratory-list-page .btn-add-laboratory {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 38px;
        padding: 8px 13px;
        color: #ffffff;
        background: #159b98;
        border: 1px solid #159b98;
        border-radius: 7px;
        font-size: 0.83rem;
        font-weight: 600;
        text-decoration: none;
        white-space: nowrap;
        transition: all 0.2s ease;
    }

    .laboratory-list-page .btn-add-laboratory:hover {
        color: #ffffff;
        background: #118582;
        border-color: #118582;
        box-shadow: 0 3px 8px rgba(21, 155, 152, 0.16);
    }

    .laboratory-list-page .laboratory-body {
        padding: 20px 22px 22px;
    }

    .laboratory-list-page .alert {
        border-radius: 8px;
        font-size: 0.86rem;
    }

    .laboratory-list-page .table-responsive {
        width: 100%;
    }

    .laboratory-list-page .laboratory-table {
        width: 100%;
        margin-bottom: 0;
        color: #405566;
        font-size: 0.84rem;
        vertical-align: middle;
        border-collapse: separate;
        border-spacing: 0;
    }

    .laboratory-list-page .laboratory-table thead th {
        padding: 13px 12px;
        color: #536a7b;
        background: #f5faf9;
        border-top: 1px solid #e5edef;
        border-bottom: 1px solid #e0e8ed;
        font-size: 0.78rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .laboratory-list-page .laboratory-table thead th:first-child {
        border-left: 1px solid #e5edef;
        border-top-left-radius: 7px;
    }

    .laboratory-list-page .laboratory-table thead th:last-child {
        border-right: 1px solid #e5edef;
        border-top-right-radius: 7px;
    }

    .laboratory-list-page .laboratory-table tbody td {
        padding: 12px;
        border-bottom: 1px solid #edf1f3;
        background: #ffffff;
    }

    .laboratory-list-page .laboratory-table tbody tr:hover td {
        background: #f8fbfb;
    }

    .laboratory-list-page .laboratory-code {
        display: inline-block;
        padding: 5px 8px;
        color: #117f7d;
        background: #e5f6f4;
        border: 1px solid #d1efec;
        border-radius: 5px;
        font-family: monospace;
        font-size: 0.8rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .laboratory-list-page .laboratory-name {
        color: #294257;
        font-weight: 600;
        line-height: 1.5;
    }

    .laboratory-list-page .laboratory-description {
        min-width: 140px;
        max-width: 320px;
        color: #718391;
        line-height: 1.6;
        overflow-wrap: anywhere;
    }

    .laboratory-list-page .laboratory-status,
    .laboratory-list-page .laboratory-users {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        padding: 5px 9px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .laboratory-list-page .status-active {
        color: #23804d;
        background: #e5f6eb;
    }

    .laboratory-list-page .status-inactive {
        color: #657482;
        background: #edf0f2;
    }

    .laboratory-list-page .laboratory-users {
        color: #526b7e;
        background: #edf4f8;
    }

    .laboratory-list-page .laboratory-actions {
        display: flex;
        align-items: center;
        gap: 5px;
        white-space: nowrap;
    }

    .laboratory-list-page .laboratory-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        min-height: 30px;
        padding: 5px 8px;
        border: 1px solid;
        border-radius: 6px;
        background: #ffffff;
        font-size: 0.76rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.15s ease;
    }

    .laboratory-list-page .action-edit {
        color: #2877b7;
        border-color: #cfe3f5;
    }

    .laboratory-list-page .action-edit:hover {
        color: #ffffff;
        background: #2877b7;
        border-color: #2877b7;
    }

    .laboratory-list-page .action-status {
        color: #657482;
        border-color: #d9e1e6;
    }

    .laboratory-list-page .action-status:hover {
        color: #ffffff;
        background: #657482;
        border-color: #657482;
    }

    .laboratory-list-page .action-delete {
        color: #d14d4d;
        border-color: #f0d3d3;
    }

    .laboratory-list-page .action-delete:hover {
        color: #ffffff;
        background: #d14d4d;
        border-color: #d14d4d;
    }

    .laboratory-list-page .empty-state {
        padding: 32px 16px !important;
        color: #7b8b98;
        text-align: center;
    }

    .laboratory-list-page .empty-state i {
        display: block;
        margin-bottom: 9px;
        color: #a5b5bf;
        font-size: 1.8rem;
    }

    @media (max-width: 768px) {
        .laboratory-list-page .laboratory-header {
            align-items: flex-start;
            flex-direction: column;
            padding: 16px;
        }

        .laboratory-list-page .btn-add-laboratory {
            width: 100%;
        }

        .laboratory-list-page .laboratory-body {
            padding: 16px;
        }

        .laboratory-list-page .laboratory-table {
            min-width: 950px;
        }
    }
</style>

<div class="laboratory-list-page">

    <!-- Flash Messages -->
    <?php if ($this->session->flashdata('pesan_sukses')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-1"></i>
            <?php echo html_escape($this->session->flashdata('pesan_sukses')); ?>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close">
            </button>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('pesan_gagal')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle me-1"></i>
            <?php echo html_escape($this->session->flashdata('pesan_gagal')); ?>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close">
            </button>
        </div>
    <?php endif; ?>

    <div class="laboratory-card">

        <div class="laboratory-header">
            <div class="laboratory-heading">
                <div class="laboratory-heading-icon">
                    <i class="bi bi-building"></i>
                </div>

                <div>
                    <h5 class="laboratory-title">
                        Daftar Laboratorium / Unit Pelaksana
                    </h5>
                    <p class="laboratory-subtitle">
                        Kelola data laboratorium, status, dan pengguna yang terkait.
                    </p>
                </div>
            </div>

            <a
                href="<?php echo site_url('laboratorium/tambah'); ?>"
                class="btn-add-laboratory">
                <i class="bi bi-plus-lg"></i>
                Tambah Laboratorium
            </a>
        </div>

        <div class="laboratory-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle laboratory-table">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th>Kode Lab</th>
                            <th>Nama Laboratorium</th>
                            <th>Deskripsi</th>
                            <th>Status</th>
                            <th>Pengguna Terkait</th>
                            <th width="20%">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (!empty($daftar_laboratorium)): ?>
                            <?php $no = 1; foreach ($daftar_laboratorium as $lab): ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>

                                    <td>
                                        <span class="laboratory-code">
                                            <?php echo html_escape($lab['kode_lab']); ?>
                                        </span>
                                    </td>

                                    <td>
                                        <span class="laboratory-name">
                                            <?php echo html_escape($lab['nama_lab']); ?>
                                        </span>
                                    </td>

                                    <td>
                                        <div class="laboratory-description">
                                            <?php echo html_escape($lab['deskripsi'] ?: '-'); ?>
                                        </div>
                                    </td>

                                    <td>
                                        <?php if ($lab['status'] === 'Aktif'): ?>
                                            <span class="laboratory-status status-active">
                                                <i class="bi bi-check-circle-fill"></i>
                                                Aktif
                                            </span>
                                        <?php else: ?>
                                            <span class="laboratory-status status-inactive">
                                                <i class="bi bi-pause-circle-fill"></i>
                                                Nonaktif
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <span class="laboratory-users">
                                            <i class="bi bi-people"></i>
                                            <?php echo $lab['total_pengguna']; ?> Pengguna
                                        </span>
                                    </td>

                                    <td>
                                        <div class="laboratory-actions">
                                            <a
                                                href="<?php echo site_url('laboratorium/edit/' . $lab['id']); ?>"
                                                class="laboratory-action action-edit"
                                                title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                                Edit
                                            </a>

                                            <a
                                                href="<?php echo site_url('laboratorium/status/' . $lab['id']); ?>"
                                                class="laboratory-action action-status"
                                                title="Ubah Status">
                                                <i class="bi bi-arrow-repeat"></i>
                                                Status
                                            </a>

                                            <?php if ($lab['total_pengguna'] == 0): ?>
                                                <a
                                                    href="<?php echo site_url('laboratorium/hapus/' . $lab['id']); ?>"
                                                    class="laboratory-action action-delete"
                                                    onclick="return confirm('Yakin ingin menghapus laboratorium ini?');"
                                                    title="Hapus">
                                                    <i class="bi bi-trash3"></i>
                                                    Hapus
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="empty-state">
                                    <i class="bi bi-inbox"></i>
                                    Belum ada data laboratorium.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>