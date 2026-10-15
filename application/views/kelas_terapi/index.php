<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
    .kt-list-page {
        width: 100%;
        padding: 4px 0 22px;
        color: #294257;
    }

    .kt-card {
        width: 100%;
        background: #ffffff;
        border: 1px solid #e0e8ed;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(41, 66, 87, 0.05);
        overflow: hidden;
    }

    .kt-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 18px 22px;
        background: #ffffff;
        border-bottom: 1px solid #e8eef1;
    }

    .kt-heading {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }

    .kt-heading-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        width: 42px;
        height: 42px;
        border-radius: 10px;
        background: #f0f5ff;
        color: #2f54eb;
        font-size: 20px;
    }

    .kt-title {
        margin: 0;
        color: #294257;
        font-size: 1.08rem;
        font-weight: 700;
    }

    .kt-subtitle {
        margin: 4px 0 0;
        color: #7b8b98;
        font-size: 0.8rem;
    }

    .btn-add-kt {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 16px;
        background: #2f54eb;
        color: #ffffff;
        border: 1px solid #2f54eb;
        border-radius: 8px;
        font-size: 0.84rem;
        font-weight: 600;
        text-decoration: none;
        box-shadow: 0 2px 6px rgba(47, 84, 235, 0.2);
        transition: all 0.2s ease;
        white-space: nowrap;
    }

    .btn-add-kt:hover {
        background: #1d39c4;
        border-color: #1d39c4;
        color: #ffffff;
        box-shadow: 0 4px 10px rgba(47, 84, 235, 0.3);
    }

    .kt-body {
        padding: 20px 22px;
    }

    .kt-table {
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
    }

    .kt-table thead th {
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

    .kt-table tbody td {
        padding: 12px 14px;
        color: #334e68;
        font-size: 0.85rem;
        border-bottom: 1px solid #edf2f5;
        vertical-align: middle;
    }

    .kt-status {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .status-active {
        background: #e6ffed;
        color: #22863a;
        border: 1px solid #b7eb8f;
    }

    .status-inactive {
        background: #fff1f0;
        color: #cf1322;
        border: 1px solid #ffa39e;
    }

    .action-btn-group {
        display: inline-flex;
        gap: 6px;
    }

    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 6px;
        font-size: 0.85rem;
        text-decoration: none;
        border: 1px solid transparent;
        transition: all 0.15s ease;
    }

    .action-edit {
        background: #eef7f6;
        color: #127976;
        border-color: #cce9e8;
    }
    .action-edit:hover {
        background: #127976;
        color: #ffffff;
    }

    .action-status {
        background: #fff8e6;
        color: #b7791f;
        border: 1px solid #feebc8;
    }
    .action-status:hover {
        background: #feebc8;
        color: #744210;
    }

    .action-delete {
        background: #fff5f5;
        color: #e53e3e;
        border: 1px solid #fed7d7;
    }
    .action-delete:hover {
        background: #e53e3e;
        color: #ffffff;
    }
</style>

<div class="kt-list-page">

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

    <div class="kt-card">

        <div class="kt-header">
            <div class="kt-heading">
                <div class="kt-heading-icon">
                    <i class="bi bi-diagram-3"></i>
                </div>

                <div>
                    <h5 class="kt-title">
                        Master Kelas Terapi
                    </h5>
                    <p class="kt-subtitle">
                        Klasifikasi farmakologi obat untuk pengelompokan monografi metode dan registrasi sampel BBPOM.
                    </p>
                </div>
            </div>

            <a href="<?php echo site_url('kelas-terapi/tambah'); ?>" class="btn-add-kt">
                <i class="bi bi-plus-lg"></i>
                Tambah Kelas Terapi
            </a>
        </div>

        <div class="kt-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle kt-table" id="tabel-kelas-terapi">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th>Nama Kelas Terapi</th>
                            <th>Total Metode Terkait</th>
                            <th>Status</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (empty($daftar_kelas_terapi)): ?>
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox fs-3 d-block mb-2 text-muted"></i>
                                    Belum ada data kelas terapi. Anda dapat menambahkannya manual atau terbuat otomatis saat import Excel metode.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php $no = 1; foreach ($daftar_kelas_terapi as $kt): ?>
                                <tr>
                                    <td class="text-center"><?php echo $no++; ?></td>
                                    <td>
                                        <strong class="text-dark">
                                            <?php echo html_escape($kt['nama_kelas_terapi']); ?>
                                        </strong>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border">
                                            <?php echo (int)$kt['total_metode']; ?> metode
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($kt['status'] === 'Aktif'): ?>
                                            <span class="kt-status status-active">
                                                <i class="bi bi-dot"></i> Aktif
                                            </span>
                                        <?php else: ?>
                                            <span class="kt-status status-inactive">
                                                <i class="bi bi-dot"></i> Nonaktif
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="action-btn-group">
                                            <a href="<?php echo site_url('kelas-terapi/edit/' . $kt['id']); ?>" class="action-btn action-edit" title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <button type="button" class="action-btn action-status" title="<?php echo ($kt['status'] === 'Aktif') ? 'Nonaktifkan' : 'Aktifkan'; ?>"
                                                    data-bs-toggle="modal" data-bs-target="#modalStatus<?php echo $kt['id']; ?>">
                                                <i class="bi bi-power"></i>
                                            </button>
                                            <?php if ($kt['total_metode'] == 0): ?>
                                                <button type="button" class="action-btn action-delete" title="Hapus Permanen"
                                                        data-bs-toggle="modal" data-bs-target="#modalHapus<?php echo $kt['id']; ?>">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            <?php else: ?>
                                                <button type="button" class="action-btn action-delete disabled text-muted opacity-50" title="Tidak dapat dihapus karena masih dipakai metode" disabled>
                                                    <i class="bi bi-lock"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Modal Status -->
                                        <div class="modal fade" id="modalStatus<?php echo $kt['id']; ?>" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content border-0 shadow">
                                                    <div class="modal-header border-bottom">
                                                        <h5 class="modal-title fs-6 fw-bold">Konfirmasi Status</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body py-3">
                                                        Ubah status Kelas Terapi <strong><?php echo html_escape($kt['nama_kelas_terapi']); ?></strong> menjadi <strong><?php echo ($kt['status'] === 'Aktif') ? 'Nonaktif' : 'Aktif'; ?></strong>?
                                                    </div>
                                                    <div class="modal-footer border-top">
                                                        <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Batal</button>
                                                        <a href="<?php echo site_url('kelas-terapi/status/' . $kt['id']); ?>" class="btn btn-sm btn-warning">
                                                            Ya, Ubah
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal Hapus -->
                                        <?php if ($kt['total_metode'] == 0): ?>
                                            <div class="modal fade" id="modalHapus<?php echo $kt['id']; ?>" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content border-0 shadow">
                                                        <div class="modal-header border-bottom">
                                                            <h5 class="modal-title fs-6 fw-bold text-danger">Hapus Kelas Terapi</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body py-3">
                                                            Apakah Anda yakin ingin menghapus kelas terapi <strong><?php echo html_escape($kt['nama_kelas_terapi']); ?></strong>? Tindakan ini tidak dapat dibatalkan.
                                                        </div>
                                                        <div class="modal-footer border-top">
                                                            <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Batal</button>
                                                            <a href="<?php echo site_url('kelas-terapi/hapus/' . $kt['id']); ?>" class="btn btn-sm btn-danger">
                                                                Ya, Hapus
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof jQuery !== 'undefined' && $.fn.DataTable) {
            $('#tabel-kelas-terapi').DataTable({
                language: {
                    search: "Cari Kelas Terapi:",
                    lengthMenu: "Tampilkan _MENU_ baris",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 data",
                    zeroRecords: "Tidak ada kelas terapi yang cocok",
                    paginate: { first: "Awal", last: "Akhir", next: "Berikutnya", previous: "Sebelumnya" }
                },
                columnDefs: [
                    { orderable: false, targets: [0, 4] }
                ],
                order: [[1, 'asc']],
                pageLength: 25,
                responsive: true
            });
        }
    });
</script>
