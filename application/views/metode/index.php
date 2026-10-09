
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
    .method-list-page {
        width: 100%;
        padding: 4px 0 22px;
        color: #294257;
    }

    .method-list-page .method-list-card {
        width: 100%;
        background: #ffffff;
        border: 1px solid #e0e8ed;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(41, 66, 87, 0.05);
        overflow: hidden;
    }

    .method-list-page .method-list-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 18px 22px;
        background: #ffffff;
        border-bottom: 1px solid #e8eef1;
    }

    .method-list-page .method-heading {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }

    .method-list-page .method-heading-icon {
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

    .method-list-page .method-title {
        margin: 0;
        color: #294257;
        font-size: 1.08rem;
        font-weight: 700;
    }

    .method-list-page .method-subtitle {
        margin: 4px 0 0;
        color: #7b8b98;
        font-size: 0.8rem;
    }

    .method-list-page .btn-add-method {
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

    .method-list-page .btn-add-method:hover {
        color: #ffffff;
        background: #118582;
        border-color: #118582;
        box-shadow: 0 3px 8px rgba(21, 155, 152, 0.16);
    }

    .method-list-page .method-list-body {
        padding: 20px 22px 22px;
    }

    .method-list-page .alert {
        border-radius: 8px;
        font-size: 0.86rem;
    }

    .method-list-page .table-responsive {
        width: 100%;
    }

    .method-list-page #tabel-metode {
        width: 100% !important;
        margin-bottom: 0;
        color: #405566;
        font-size: 0.84rem;
        vertical-align: middle;
        border-collapse: separate;
        border-spacing: 0;
    }

    .method-list-page #tabel-metode thead th {
        padding: 13px 12px;
        color: #536a7b;
        background: #f5faf9;
        border-top: 1px solid #e5edef;
        border-bottom: 1px solid #e0e8ed;
        font-size: 0.78rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .method-list-page #tabel-metode thead th:first-child {
        border-left: 1px solid #e5edef;
        border-top-left-radius: 7px;
    }

    .method-list-page #tabel-metode thead th:last-child {
        border-right: 1px solid #e5edef;
        border-top-right-radius: 7px;
    }

    .method-list-page #tabel-metode tbody td {
        padding: 12px;
        border-bottom: 1px solid #edf1f3;
        background: #ffffff;
    }

    .method-list-page #tabel-metode tbody tr:hover td {
        background: #f8fbfb;
    }

    .method-list-page .method-code {
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

    .method-list-page .method-name {
        color: #294257;
        font-weight: 600;
        line-height: 1.5;
    }

    .method-list-page .method-category,
    .method-list-page .method-status {
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

    .method-list-page .category-chemistry {
        color: #137d9b;
        background: #e4f5fa;
    }

    .method-list-page .category-microbiology {
        color: #946516;
        background: #fff3d9;
    }

    .method-list-page .status-active {
        color: #23804d;
        background: #e5f6eb;
    }

    .method-list-page .status-inactive {
        color: #657482;
        background: #edf0f2;
    }

    .method-list-page .method-actions {
        display: flex;
        align-items: center;
        gap: 5px;
        white-space: nowrap;
    }

    .method-list-page .method-action {
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

    .method-list-page .action-edit {
        color: #2877b7;
        border-color: #cfe3f5;
    }

    .method-list-page .action-edit:hover {
        color: #ffffff;
        background: #2877b7;
        border-color: #2877b7;
    }

    .method-list-page .action-status {
        color: #657482;
        border-color: #d9e1e6;
    }

    .method-list-page .action-status:hover {
        color: #ffffff;
        background: #657482;
        border-color: #657482;
    }

    .method-list-page .action-delete {
        color: #d14d4d;
        border-color: #f0d3d3;
    }

    .method-list-page .action-delete:hover {
        color: #ffffff;
        background: #d14d4d;
        border-color: #d14d4d;
    }

    .method-list-page .dataTables_wrapper {
        font-size: 0.83rem;
    }

    .method-list-page .dataTables_wrapper .dataTables_length,
    .method-list-page .dataTables_wrapper .dataTables_filter {
        margin-bottom: 16px;
        color: #667b89;
        font-size: 0.82rem;
    }

    .method-list-page .dataTables_wrapper .dataTables_filter input,
    .method-list-page .dataTables_wrapper .dataTables_length select {
        min-height: 35px;
        padding: 6px 9px;
        border: 1px solid #d8e2e8;
        border-radius: 6px;
        color: #294257;
        background-color: #ffffff;
        font-size: 0.82rem;
        outline: none;
    }

    .method-list-page .dataTables_wrapper .dataTables_filter input:focus,
    .method-list-page .dataTables_wrapper .dataTables_length select:focus {
        border-color: #159b98;
        box-shadow: 0 0 0 3px rgba(21, 155, 152, 0.09);
    }

    .method-list-page .dataTables_wrapper .dataTables_info {
        padding-top: 16px;
        color: #7b8b98;
        font-size: 0.8rem;
    }

    .method-list-page .dataTables_wrapper .dataTables_paginate {
        padding-top: 12px;
    }

    .method-list-page .dataTables_wrapper .dataTables_paginate .paginate_button {
        margin-left: 3px;
        padding: 5px 10px;
        color: #526675 !important;
        border: 1px solid transparent;
        border-radius: 6px;
        font-size: 0.8rem;
    }

    .method-list-page .dataTables_wrapper .dataTables_paginate .paginate_button.current,
    .method-list-page .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        color: #ffffff !important;
        background: #159b98 !important;
        border: 1px solid #159b98 !important;
    }

    .method-list-page .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        color: #117f7d !important;
        background: #e5f6f4 !important;
        border: 1px solid #d1efec !important;
    }

    @media (max-width: 768px) {
        .method-list-page .method-list-header {
            align-items: flex-start;
            flex-direction: column;
            padding: 16px;
        }

        .method-list-page .btn-add-method {
            width: 100%;
        }

        .method-list-page .method-list-body {
            padding: 16px;
        }

        .method-list-page .dataTables_wrapper .dataTables_length,
        .method-list-page .dataTables_wrapper .dataTables_filter {
            float: none;
            width: 100%;
            text-align: left;
        }

        .method-list-page .dataTables_wrapper .dataTables_filter input {
            width: min(100%, 220px);
            margin-left: 5px;
        }

        .method-list-page #tabel-metode {
            min-width: 850px;
        }
    }
</style>

<div class="method-list-page">

    <!-- Flash Messages -->
    <?php if ($this->session->flashdata('pesan_sukses')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-1"></i>
            <?php echo html_escape($this->session->flashdata('pesan_sukses')); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('pesan_gagal')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle me-1"></i>
            <?php echo html_escape($this->session->flashdata('pesan_gagal')); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="method-list-card">

        <div class="method-list-header">
            <div class="method-heading">
                <div class="method-heading-icon">
                    <i class="bi bi-journal-check"></i>
                </div>

                <div>
                    <h5 class="method-title">Daftar Master Metode Pengujian</h5>
                    <p class="method-subtitle">
                        Kelola metode, kategori, versi, dan status pengujian laboratorium.
                    </p>
                </div>
            </div>

            <a href="<?php echo site_url('metode/tambah'); ?>" class="btn-add-method">
                <i class="bi bi-plus-lg"></i>
                Tambah Metode
            </a>
        </div>

        <div class="method-list-body">
            <div class="table-responsive">
                <table id="tabel-metode" class="table table-hover align-middle w-100">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th>Kode Metode</th>
                            <th>Nama Metode</th>
                            <th>Kategori</th>
                            <th>Versi</th>
                            <th>Status</th>
                            <th width="18%">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (!empty($daftar_metode)): ?>
                            <?php $no = 1; foreach ($daftar_metode as $m): ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>

                                    <td>
                                        <span class="method-code">
                                            <?php echo html_escape($m['kode_metode']); ?>
                                        </span>
                                    </td>

                                    <td>
                                        <span class="method-name">
                                            <?php echo html_escape($m['nama_metode']); ?>
                                        </span>
                                    </td>

                                    <td>
                                        <span class="method-category <?php echo $m['kategori'] === 'Kimia' ? 'category-chemistry' : 'category-microbiology'; ?>">
                                            <i class="bi <?php echo $m['kategori'] === 'Kimia' ? 'bi-droplet' : 'bi-activity'; ?>"></i>
                                            <?php echo html_escape($m['kategori']); ?>
                                        </span>
                                    </td>

                                    <td>
                                        <?php echo html_escape($m['versi'] ?: '-'); ?>
                                    </td>

                                    <td>
                                        <?php if ($m['status'] === 'Aktif'): ?>
                                            <span class="method-status status-active">
                                                <i class="bi bi-check-circle-fill"></i>
                                                Aktif
                                            </span>
                                        <?php else: ?>
                                            <span class="method-status status-inactive">
                                                <i class="bi bi-pause-circle-fill"></i>
                                                Nonaktif
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <div class="method-actions">
                                            <a
                                                href="<?php echo site_url('metode/edit/' . $m['id']); ?>"
                                                class="method-action action-edit"
                                                title="Edit"
                                            >
                                                <i class="bi bi-pencil-square"></i>
                                                Edit
                                            </a>

                                            <a
                                                href="<?php echo site_url('metode/status/' . $m['id']); ?>"
                                                class="method-action action-status"
                                                title="Ubah Status"
                                            >
                                                <i class="bi bi-arrow-repeat"></i>
                                                Status
                                            </a>

                                            <a
                                                href="<?php echo site_url('metode/hapus/' . $m['id']); ?>"
                                                class="method-action action-delete"
                                                onclick="return confirm('Yakin ingin menghapus metode ini?');"
                                                title="Hapus"
                                            >
                                                <i class="bi bi-trash3"></i>
                                                Hapus
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox d-block mb-2" style="font-size: 1.6rem;"></i>
                                    Belum ada data metode pengujian.
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
        $('#tabel-metode').DataTable({
            language: {
                url: '<?php echo base_url('assets/js/dataTables.indonesian.json'); ?>'
            }
        });
    }
});
</script>