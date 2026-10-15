<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
    .instrument-list-page {
        width: 100%;
        padding: 4px 0 22px;
        color: #294257;
    }

    .instrument-list-page .instrument-card {
        width: 100%;
        background: #ffffff;
        border: 1px solid #e0e8ed;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(41, 66, 87, 0.05);
        overflow: hidden;
    }

    .instrument-list-page .instrument-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 18px 22px;
        background: #ffffff;
        border-bottom: 1px solid #e8eef1;
    }

    .instrument-list-page .instrument-heading {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }

    .instrument-list-page .instrument-heading-icon {
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

    .instrument-list-page .instrument-title {
        margin: 0;
        color: #294257;
        font-size: 1.08rem;
        font-weight: 700;
    }

    .instrument-list-page .instrument-subtitle {
        margin: 4px 0 0;
        color: #7b8b98;
        font-size: 0.8rem;
    }

    .instrument-list-page .btn-add-instrument {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 16px;
        background: #159b98;
        color: #ffffff;
        border: 1px solid #159b98;
        border-radius: 8px;
        font-size: 0.84rem;
        font-weight: 600;
        text-decoration: none;
        box-shadow: 0 2px 6px rgba(21, 155, 152, 0.2);
        transition: all 0.2s ease;
        white-space: nowrap;
    }

    .instrument-list-page .btn-add-instrument:hover {
        background: #118380;
        border-color: #118380;
        color: #ffffff;
        box-shadow: 0 4px 10px rgba(21, 155, 152, 0.3);
    }

    .instrument-list-page .btn-import-instrument {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 16px;
        background: #ffffff;
        color: #2b6cb0;
        border: 1px solid #cbd5e0;
        border-radius: 8px;
        font-size: 0.84rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
        white-space: nowrap;
    }

    .instrument-list-page .btn-import-instrument:hover {
        background: #ebf8ff;
        border-color: #3182ce;
        color: #2b6cb0;
        box-shadow: 0 2px 6px rgba(49, 130, 206, 0.2);
    }

    .instrument-list-page .instrument-body {
        padding: 20px 22px;
    }

    .instrument-list-page .instrument-table {
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
    }

    .instrument-list-page .instrument-table thead th {
        background: #f7fafc;
        color: #40596d;
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        padding: 12px 14px;
        border-bottom: 1px solid #e0e8ed;
        white-space: nowrap;
    }

    .instrument-list-page .instrument-table tbody td {
        padding: 13px 14px;
        color: #334e68;
        font-size: 0.85rem;
        border-bottom: 1px solid #edf2f5;
        vertical-align: middle;
    }

    .instrument-list-page .instrument-code {
        display: inline-block;
        padding: 3px 8px;
        background: #eef6fc;
        color: #1e70a2;
        border: 1px solid #d4e7f5;
        border-radius: 6px;
        font-family: monospace;
        font-size: 0.8rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .instrument-list-page .instrument-name {
        font-weight: 600;
        color: #243b53;
    }

    .instrument-list-page .instrument-status {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .instrument-list-page .status-active {
        background: #eaf7ed;
        color: #218339;
        border: 1px solid #c8edd0;
    }

    .instrument-list-page .status-inactive {
        background: #fdf2f2;
        color: #c92a2a;
        border: 1px solid #f8d4d4;
    }

    .instrument-list-page .instrument-actions {
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
    }

    .instrument-list-page .instrument-action {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 0.76rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.15s ease;
    }

    .instrument-list-page .action-detail {
        background: #f0f7f7;
        color: #159b98;
        border: 1px solid #d0ebe9;
    }
    .instrument-list-page .action-detail:hover {
        background: #159b98;
        color: #ffffff;
    }

    .instrument-list-page .action-edit {
        background: #f0f4f8;
        color: #334e68;
        border: 1px solid #d9e2ec;
    }
    .instrument-list-page .action-edit:hover {
        background: #d9e2ec;
        color: #102a43;
    }

    .instrument-list-page .action-status {
        background: #fff8e6;
        color: #b7791f;
        border: 1px solid #feebc8;
    }
    .instrument-list-page .action-status:hover {
        background: #feebc8;
        color: #744210;
    }

    .instrument-list-page .action-delete {
        background: #fff5f5;
        color: #e53e3e;
        border: 1px solid #fed7d7;
    }
    .instrument-list-page .action-delete:hover {
        background: #e53e3e;
        color: #ffffff;
    }
</style>

<div class="instrument-list-page">

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
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <?php echo html_escape($this->session->flashdata('pesan_gagal')); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="instrument-card">

        <div class="instrument-header">
            <div class="instrument-heading">
                <div class="instrument-heading-icon">
                    <i class="bi bi-tools"></i>
                </div>

                <div>
                    <h5 class="instrument-title">
                        Daftar Alat Laboratorium
                    </h5>
                    <p class="instrument-subtitle">
                        Kelola data inventaris alat pengujian, spesifikasi teknis, kondisi, dan lokasi ruangan.
                    </p>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                <a href="<?php echo site_url('alat/import'); ?>" class="btn-import-instrument">
                    <i class="bi bi-file-earmark-excel"></i>
                    Import Excel
                </a>
                <a href="<?php echo site_url('alat/tambah'); ?>" class="btn-add-instrument">
                    <i class="bi bi-plus-lg"></i>
                    Tambah Alat
                </a>
            </div>
        </div>

        <div class="instrument-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle instrument-table" id="tabel-alat">
                    <thead>
                        <tr>
                            <th width="4%">No</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Merk/Tipe</th>
                            <th>Perangkat Lunak</th>
                            <th>Detektor</th>
                            <th>Tgl Perolehan</th>
                            <th>Kondisi ADM</th>
                            <th>Kode Ruangan</th>
                            <th>Nama Ruangan</th>
                            <th>Status</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (!empty($daftar_alat)): ?>
                            <?php $no = 1; foreach ($daftar_alat as $item): ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>

                                    <td>
                                        <span class="instrument-code">
                                            <?php echo html_escape($item['kode_barang']); ?>
                                        </span>
                                    </td>

                                    <td>
                                        <span class="instrument-name">
                                            <?php echo html_escape($item['nama_barang']); ?>
                                        </span>
                                    </td>

                                    <td><?php echo html_escape($item['merk_tipe'] ?: '-'); ?></td>

                                    <td><?php echo html_escape($item['perangkat_lunak'] ?: '-'); ?></td>

                                    <td><?php echo html_escape($item['detektor'] ?: '-'); ?></td>

                                    <td>
                                        <?php 
                                            if (!empty($item['tanggal_perolehan'])) {
                                                echo date('d/m/Y', strtotime($item['tanggal_perolehan']));
                                            } else {
                                                echo '-';
                                            }
                                        ?>
                                    </td>

                                    <td>
                                        <?php if (!empty($item['kondisi_adm'])): ?>
                                            <span class="badge bg-light text-dark border">
                                                <?php echo html_escape($item['kondisi_adm']); ?>
                                            </span>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <?php if (!empty($item['kode_ruangan'])): ?>
                                            <span class="badge bg-secondary-subtle text-secondary border">
                                                <?php echo html_escape($item['kode_ruangan']); ?>
                                            </span>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>

                                    <td><?php echo html_escape($item['nama_ruangan'] ?: '-'); ?></td>

                                    <td>
                                        <?php if ($item['status'] === 'Aktif'): ?>
                                            <span class="instrument-status status-active">
                                                <i class="bi bi-check-circle-fill"></i>
                                                Aktif
                                            </span>
                                        <?php else: ?>
                                            <span class="instrument-status status-inactive">
                                                <i class="bi bi-pause-circle-fill"></i>
                                                Nonaktif
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <div class="instrument-actions">
                                            <a href="<?php echo site_url('alat/detail/' . $item['id']); ?>"
                                               class="instrument-action action-detail"
                                               title="Detail">
                                                <i class="bi bi-eye"></i>
                                                Detail
                                            </a>

                                            <a href="<?php echo site_url('alat/edit/' . $item['id']); ?>"
                                               class="instrument-action action-edit"
                                               title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                                Edit
                                            </a>

                                            <a href="<?php echo site_url('alat/status/' . $item['id']); ?>"
                                               class="instrument-action action-status"
                                               title="Ubah Status">
                                                <i class="bi bi-arrow-repeat"></i>
                                                Status
                                            </a>

                                            <a href="<?php echo site_url('alat/hapus/' . $item['id']); ?>"
                                               class="instrument-action action-delete"
                                               onclick="return confirm('Apakah Anda yakin ingin menghapus data alat <?php echo html_escape($item['kode_barang']); ?>?');"
                                               title="Hapus">
                                                <i class="bi bi-trash3"></i>
                                                Hapus
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="12" class="text-center text-muted py-5">
                                    <i class="bi bi-inbox d-block mb-2" style="font-size: 2rem; color: #a0aec0;"></i>
                                    Belum ada data alat laboratorium.
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
        $('#tabel-alat').DataTable({
            language: {
                url: '<?php echo base_url('assets/js/dataTables.indonesian.json'); ?>'
            },
            pageLength: 10,
            columnDefs: [
                { orderable: false, targets: [11] }
            ]
        });
    }
});
</script>
