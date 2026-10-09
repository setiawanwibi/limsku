<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
    .parameter-list-page {
        width: 100%;
        padding: 4px 0 20px;
        color: #294257;
        font-size: .9rem;
    }

    .parameter-list-card {
        width: 100%;
        overflow: hidden;
        background: #fff;
        border: 1px solid #e0e8ed;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(41, 66, 87, .05);
    }

    .parameter-list-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 14px;
        padding: 18px 22px;
        background: #fff;
        border-bottom: 1px solid #e8eff2;
    }

    .parameter-list-heading {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }

    .parameter-list-header-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 42px;
        width: 42px;
        height: 42px;
        color: #159b98;
        background: #e5f6f4;
        border-radius: 10px;
        font-size: 1.15rem;
    }

    .parameter-list-title {
        margin: 0;
        color: #294257;
        font-size: 1.08rem;
        font-weight: 700;
        line-height: 1.4;
    }

    .parameter-list-subtitle {
        margin: 3px 0 0;
        color: #8495a3;
        font-size: .8rem;
        line-height: 1.5;
    }

    .parameter-list-page .btn-add-parameter {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 39px;
        padding: 9px 14px;
        color: #fff;
        background: #159b98;
        border: 1px solid #159b98;
        border-radius: 7px;
        font-size: .83rem;
        font-weight: 600;
        text-decoration: none;
        white-space: nowrap;
        transition: all .2s ease;
    }

    .parameter-list-page .btn-add-parameter:hover {
        color: #fff;
        background: #118582;
        border-color: #118582;
        box-shadow: 0 3px 8px rgba(21, 155, 152, .18);
    }

    .parameter-list-body {
        padding: 22px;
    }

    .parameter-list-page .alert {
        margin-bottom: 18px;
        padding: 12px 15px;
        border-radius: 8px;
        font-size: .84rem;
    }

    .parameter-list-page .alert-success {
        color: #176b55;
        background: #effaf5;
        border: 1px solid #d4eee2;
    }

    .parameter-list-page .alert-danger {
        color: #9c3434;
        background: #fff5f5;
        border: 1px solid #f4d6d6;
    }

    .parameter-table-wrap {
        width: 100%;
        overflow-x: auto;
    }

    .parameter-list-page #tabel-parameter {
        width: 100% !important;
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
        color: #40596d;
        font-size: .84rem;
        vertical-align: middle;
    }

    .parameter-list-page #tabel-parameter thead th {
        padding: 12px 13px;
        color: #526a7b;
        background: #f5f9fa;
        border-top: 1px solid #e5edf0;
        border-bottom: 1px solid #e0e8ed;
        font-size: .78rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .parameter-list-page #tabel-parameter thead th:first-child {
        border-left: 1px solid #e5edf0;
        border-top-left-radius: 7px;
    }

    .parameter-list-page #tabel-parameter thead th:last-child {
        border-right: 1px solid #e5edf0;
        border-top-right-radius: 7px;
    }

    .parameter-list-page #tabel-parameter tbody td {
        padding: 13px;
        border-bottom: 1px solid #edf1f3;
        background: #fff;
        line-height: 1.55;
    }

    .parameter-list-page #tabel-parameter tbody tr:hover td {
        background: #f8fbfb;
    }

    .parameter-list-page #tabel-parameter tbody tr:last-child td {
        border-bottom-color: #e5edf0;
    }

    .parameter-list-page .parameter-code {
        display: inline-block;
        padding: 5px 8px;
        color: #168b88;
        background: #eaf7f6;
        border: 1px solid #d8efed;
        border-radius: 5px;
        font-family: Consolas, Monaco, monospace;
        font-size: .78rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .parameter-list-page .parameter-name {
        color: #294257;
        font-weight: 600;
    }

    .parameter-list-page .parameter-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 5px 9px;
        border-radius: 5px;
        font-size: .75rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .parameter-list-page .parameter-category-kimia {
        color: #176e83;
        background: #e5f5f9;
        border: 1px solid #d0edf3;
    }

    .parameter-list-page .parameter-category-mikro {
        color: #8a6417;
        background: #fff5dc;
        border: 1px solid #f4e6b9;
    }

    .parameter-list-page .parameter-status-active {
        color: #19734f;
        background: #e8f7ef;
        border: 1px solid #d2eddf;
    }

    .parameter-list-page .parameter-status-inactive {
        color: #637381;
        background: #eef1f4;
        border: 1px solid #e0e5e9;
    }

    .parameter-list-page .parameter-actions {
        display: flex;
        align-items: center;
        flex-wrap: nowrap;
        gap: 5px;
        white-space: nowrap;
    }

    .parameter-list-page .parameter-actions .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        min-height: 30px;
        padding: 5px 8px;
        border-radius: 5px;
        font-size: .75rem;
        font-weight: 600;
        line-height: 1.3;
        transition: all .18s ease;
    }

    .parameter-list-page .parameter-actions .btn-outline-primary {
        color: #168b88;
        border-color: #a8d8d5;
    }

    .parameter-list-page .parameter-actions .btn-outline-primary:hover {
        color: #fff;
        background: #159b98;
        border-color: #159b98;
    }

    .parameter-list-page .parameter-actions .btn-outline-secondary {
        color: #5c7080;
        border-color: #d5dfe5;
    }

    .parameter-list-page .parameter-actions .btn-outline-secondary:hover {
        color: #fff;
        background: #637887;
        border-color: #637887;
    }

    .parameter-list-page .parameter-actions .btn-outline-danger {
        color: #c34e56;
        border-color: #efc5c8;
    }

    .parameter-list-page .parameter-actions .btn-outline-danger:hover {
        color: #fff;
        background: #c34e56;
        border-color: #c34e56;
    }

    .parameter-list-page .parameter-empty {
        padding: 30px 15px !important;
        color: #8797a1;
        text-align: center;
    }

    /* DataTables */
    .parameter-list-page .dataTables_wrapper {
        color: #657987;
        font-size: .82rem;
    }

    .parameter-list-page .dataTables_wrapper .dataTables_length,
    .parameter-list-page .dataTables_wrapper .dataTables_filter {
        margin-bottom: 16px;
        color: #657987;
        font-size: .82rem;
    }

    .parameter-list-page .dataTables_wrapper .dataTables_filter {
        text-align: right;
    }

    .parameter-list-page .dataTables_wrapper .dataTables_length select,
    .parameter-list-page .dataTables_wrapper .dataTables_filter input {
        min-height: 35px;
        padding: 6px 10px;
        color: #40596d;
        background: #fff;
        border: 1px solid #dce5ea;
        border-radius: 6px;
        font-size: .82rem;
        box-shadow: none;
    }

    .parameter-list-page .dataTables_wrapper .dataTables_filter input {
        margin-left: 7px;
    }

    .parameter-list-page .dataTables_wrapper .dataTables_length select:focus,
    .parameter-list-page .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #159b98;
        outline: none;
        box-shadow: 0 0 0 3px rgba(21, 155, 152, .1);
    }

    .parameter-list-page .dataTables_wrapper .dataTables_info {
        padding-top: 17px;
        color: #8495a3;
        font-size: .8rem;
    }

    .parameter-list-page .dataTables_wrapper .dataTables_paginate {
        padding-top: 12px;
        font-size: .8rem;
    }

    .parameter-list-page .dataTables_wrapper .dataTables_paginate .paginate_button {
        margin-left: 3px;
        padding: 6px 10px;
        color: #526a7b !important;
        background: #fff !important;
        border: 1px solid #dce5ea !important;
        border-radius: 6px;
        font-size: .8rem;
    }

    .parameter-list-page .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        color: #168b88 !important;
        background: #eaf7f6 !important;
        border-color: #b8e0dd !important;
    }

    .parameter-list-page .dataTables_wrapper .dataTables_paginate .paginate_button.current,
    .parameter-list-page .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        color: #fff !important;
        background: #159b98 !important;
        border-color: #159b98 !important;
    }

    .parameter-list-page .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
        color: #b2bdc5 !important;
        background: #f7f9fa !important;
        cursor: default;
    }

    @media (max-width: 767.98px) {
        .parameter-list-page {
            padding-top: 0;
        }

        .parameter-list-header {
            padding: 16px;
        }

        .parameter-list-body {
            padding: 16px;
        }

        .parameter-list-header-icon {
            flex-basis: 38px;
            width: 38px;
            height: 38px;
            font-size: 1.05rem;
        }

        .parameter-list-title {
            font-size: 1rem;
        }

        .parameter-list-subtitle {
            font-size: .76rem;
        }

        .parameter-list-page .btn-add-parameter {
            min-height: 36px;
            padding: 8px 11px;
            font-size: .8rem;
        }

        .parameter-list-page .dataTables_wrapper .dataTables_length,
        .parameter-list-page .dataTables_wrapper .dataTables_filter {
            float: none;
            width: 100%;
            margin-bottom: 12px;
            text-align: left;
        }

        .parameter-list-page .dataTables_wrapper .dataTables_filter input {
            max-width: 65%;
        }

        .parameter-list-page .dataTables_wrapper .dataTables_info,
        .parameter-list-page .dataTables_wrapper .dataTables_paginate {
            float: none;
            width: 100%;
            text-align: left;
        }

        .parameter-list-page .dataTables_wrapper .dataTables_paginate {
            margin-top: 8px;
        }
    }

    @media (max-width: 480px) {
        .parameter-list-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .parameter-list-page .btn-add-parameter {
            align-self: flex-start;
        }
    }
</style>

<div class="parameter-list-page">

    <!-- Flash Messages -->
    <?php if ($this->session->flashdata('pesan_sukses')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            <?php echo html_escape($this->session->flashdata('pesan_sukses')); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('pesan_gagal')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle-fill me-2"></i>
            <?php echo html_escape($this->session->flashdata('pesan_gagal')); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="parameter-list-card">

        <!-- Header -->
        <div class="parameter-list-header">
            <div class="parameter-list-heading">
                <div class="parameter-list-header-icon">
                    <i class="bi bi-clipboard2-pulse"></i>
                </div>

                <div>
                    <h5 class="parameter-list-title">
                        Daftar Master Parameter Pengujian
                    </h5>
                    <p class="parameter-list-subtitle">
                        Kelola parameter, satuan, spesifikasi, dan status pengujian.
                    </p>
                </div>
            </div>

            <a href="<?php echo site_url('parameter/tambah'); ?>"
               class="btn-add-parameter">
                <i class="bi bi-plus-lg"></i>
                Tambah Parameter
            </a>
        </div>

        <!-- Table -->
        <div class="parameter-list-body">
            <div class="parameter-table-wrap">
                <table id="tabel-parameter" class="table table-hover align-middle w-100">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th>Kode Parameter</th>
                            <th>Nama Parameter</th>
                            <th>Satuan</th>
                            <th>Baku Mutu / Spesifikasi</th>
                            <th>Kategori</th>
                            <th>Status</th>
                            <th width="18%">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (!empty($daftar_parameter)): ?>
                            <?php $no = 1; foreach ($daftar_parameter as $p): ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>

                                    <td>
                                        <span class="parameter-code">
                                            <?php echo html_escape($p['kode_parameter']); ?>
                                        </span>
                                    </td>

                                    <td>
                                        <strong class="parameter-name">
                                            <?php echo html_escape($p['nama_parameter']); ?>
                                        </strong>
                                    </td>

                                    <td>
                                        <?php echo html_escape($p['satuan'] ?: '-'); ?>
                                    </td>

                                    <td>
                                        <?php echo html_escape($p['baku_mutu'] ?: '-'); ?>
                                    </td>

                                    <td>
                                        <span class="parameter-badge <?php echo $p['kategori'] === 'Kimia' ? 'parameter-category-kimia' : 'parameter-category-mikro'; ?>">
                                            <?php echo html_escape($p['kategori']); ?>
                                        </span>
                                    </td>

                                    <td>
                                        <?php if ($p['status'] === 'Aktif'): ?>
                                            <span class="parameter-badge parameter-status-active">
                                                <i class="bi bi-check-circle me-1"></i>
                                                Aktif
                                            </span>
                                        <?php else: ?>
                                            <span class="parameter-badge parameter-status-inactive">
                                                <i class="bi bi-dash-circle me-1"></i>
                                                Nonaktif
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <div class="parameter-actions" role="group">
                                            <a
                                                href="<?php echo site_url('parameter/edit/' . $p['id']); ?>"
                                                class="btn btn-outline-primary"
                                                title="Edit"
                                            >
                                                <i class="bi bi-pencil-square"></i>
                                                Edit
                                            </a>

                                            <a
                                                href="<?php echo site_url('parameter/status/' . $p['id']); ?>"
                                                class="btn btn-outline-secondary"
                                                title="Ubah Status"
                                            >
                                                <i class="bi bi-arrow-left-right"></i>
                                                Status
                                            </a>

                                            <a
                                                href="<?php echo site_url('parameter/hapus/' . $p['id']); ?>"
                                                class="btn btn-outline-danger"
                                                onclick="return confirm('Yakin ingin menghapus parameter ini?');"
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
                                <td colspan="8" class="parameter-empty">
                                    <i class="bi bi-inbox fs-4 d-block mb-2"></i>
                                    Belum ada data parameter pengujian.
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
        $('#tabel-parameter').DataTable({
            language: {
                url: '<?php echo base_url('assets/js/dataTables.indonesian.json'); ?>'
            }
        });
    }
});
</script>