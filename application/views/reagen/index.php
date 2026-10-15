<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
    .reagent-list-page {
        width: 100%;
        padding: 4px 0 22px;
        color: #294257;
    }

    .reagent-list-page .reagent-card {
        width: 100%;
        background: #ffffff;
        border: 1px solid #e0e8ed;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(41, 66, 87, 0.05);
        overflow: hidden;
    }

    .reagent-list-page .reagent-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 18px 22px;
        background: #ffffff;
        border-bottom: 1px solid #e8eef1;
    }

    .reagent-list-page .reagent-heading {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }

    .reagent-list-page .reagent-heading-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        width: 42px;
        height: 42px;
        border-radius: 10px;
        background: #e6f7ff;
        color: #096dd9;
        font-size: 20px;
    }

    .reagent-list-page .reagent-title {
        margin: 0;
        color: #294257;
        font-size: 1.08rem;
        font-weight: 700;
    }

    .reagent-list-page .reagent-subtitle {
        margin: 4px 0 0;
        color: #7b8b98;
        font-size: 0.8rem;
    }

    .reagent-list-page .reagent-header-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-shrink: 0;
    }

    .reagent-list-page .btn-add-reagent {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 16px;
        background: #096dd9;
        color: #ffffff;
        border: 1px solid #096dd9;
        border-radius: 8px;
        font-size: 0.84rem;
        font-weight: 600;
        text-decoration: none;
        box-shadow: 0 2px 6px rgba(9, 109, 217, 0.2);
        transition: all 0.2s ease;
        white-space: nowrap;
    }

    .reagent-list-page .btn-add-reagent:hover {
        background: #085fc4;
        border-color: #085fc4;
        color: #ffffff;
        box-shadow: 0 4px 10px rgba(9, 109, 217, 0.3);
    }

    .reagent-list-page .btn-import-reagent {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 16px;
        background: #f0f7ff;
        color: #096dd9;
        border: 1px solid #b3d8ff;
        border-radius: 8px;
        font-size: 0.84rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
        white-space: nowrap;
    }

    .reagent-list-page .btn-import-reagent:hover {
        background: #e1f0fe;
        color: #085fc4;
        border-color: #085fc4;
    }

    .reagent-list-page .filter-bar {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 14px 22px;
        background: #fbfcfd;
        border-bottom: 1px solid #e8eef1;
    }

    .reagent-list-page .filter-form {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 10px;
        flex: 1;
    }

    .reagent-list-page .filter-select, .reagent-list-page .filter-input {
        padding: 6px 12px;
        font-size: 0.82rem;
        border: 1px solid #cbd5e0;
        border-radius: 6px;
        background: #ffffff;
        color: #2d3748;
    }

    .reagent-list-page .filter-input {
        min-width: 220px;
    }

    .reagent-list-page .reagent-body {
        padding: 20px 22px;
    }

    .reagent-list-page .reagent-table {
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
    }

    .reagent-list-page .reagent-table thead th {
        background: #f7fafc;
        color: #40596d;
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 12px 14px;
        border-top: 1px solid #e8eef1;
        border-bottom: 2px solid #dde5ea;
        white-space: nowrap;
    }

    .reagent-list-page .reagent-table tbody td {
        padding: 12px 14px;
        font-size: 0.83rem;
        color: #334e68;
        border-bottom: 1px solid #edf2f6;
        vertical-align: middle;
    }

    .reagent-list-page .reagent-table tbody tr:hover {
        background-color: #f9fbfd;
    }

    .reagent-list-page .reagent-name {
        font-weight: 600;
        color: #102a43;
    }

    .reagent-list-page .code-badge {
        font-family: monospace;
        font-size: 0.78rem;
        padding: 2px 6px;
        background: #f0f4f8;
        border: 1px solid #d9e2ec;
        border-radius: 4px;
        color: #334e68;
    }

    .reagent-list-page .stok-badge {
        display: inline-block;
        padding: 3px 8px;
        border-radius: 6px;
        font-weight: 700;
        font-size: 0.76rem;
    }

    .reagent-list-page .stok-ready {
        background: #e6ffed;
        color: #22863a;
        border: 1px solid #b7eb8f;
    }

    .reagent-list-page .stok-empty {
        background: #fff1f0;
        color: #cf1322;
        border: 1px solid #ffa39e;
    }

    .reagent-list-page .category-tag {
        display: inline-block;
        padding: 2px 8px;
        background: #f0f4f8;
        border: 1px solid #d9e2ec;
        border-radius: 12px;
        font-size: 0.74rem;
        color: #486581;
    }

    .reagent-list-page .exp-safe {
        color: #24292e;
    }

    .reagent-list-page .exp-expired {
        color: #cf1322;
        font-weight: 700;
    }

    .reagent-list-page .action-btn-group {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .reagent-list-page .action-btn {
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

    .reagent-list-page .action-detail {
        background: #f0f7ff;
        color: #0066cc;
        border-color: #d0e4ff;
    }
    .reagent-list-page .action-detail:hover {
        background: #0066cc;
        color: #ffffff;
    }

    .reagent-list-page .action-edit {
        background: #eef7f6;
        color: #127976;
        border-color: #cce9e8;
    }
    .reagent-list-page .action-edit:hover {
        background: #127976;
        color: #ffffff;
    }

    .reagent-list-page .action-status {
        background: #fff8e6;
        color: #b7791f;
        border: 1px solid #feebc8;
    }
    .reagent-list-page .action-status:hover {
        background: #feebc8;
        color: #744210;
    }

    .reagent-list-page .action-delete {
        background: #fff5f5;
        color: #e53e3e;
        border: 1px solid #fed7d7;
    }
    .reagent-list-page .action-delete:hover {
        background: #e53e3e;
        color: #ffffff;
    }
</style>

<div class="reagent-list-page">

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

    <div class="reagent-card">

        <div class="reagent-header">
            <div class="reagent-heading">
                <div class="reagent-heading-icon">
                    <i class="bi bi-eyedropper"></i>
                </div>

                <div>
                    <h5 class="reagent-title">
                        Master Data Reagen Laboratorium
                    </h5>
                    <p class="reagent-subtitle">
                        Kelola data master bahan, reagen, dan media pengujian sesuai standar inventaris laboratorium BBPOM.
                    </p>
                </div>
            </div>

            <div class="reagent-header-actions">
                <?php if ($this->Model_Hak_Akses->memiliki_akses($this->session->userdata('role_id'), 'reagen_import')): ?>
                    <a href="<?php echo site_url('reagen/import'); ?>" class="btn-import-reagent">
                        <i class="bi bi-file-earmark-arrow-up"></i>
                        Impor Excel
                    </a>
                <?php endif; ?>

                <?php if ($this->Model_Hak_Akses->memiliki_akses($this->session->userdata('role_id'), 'reagen_create')): ?>
                    <a href="<?php echo site_url('reagen/tambah'); ?>" class="btn-add-reagent">
                        <i class="bi bi-plus-lg"></i>
                        Tambah Reagen
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Filter & Search Bar -->
        <div class="filter-bar">
            <form action="<?php echo site_url('reagen'); ?>" method="get" class="filter-form">
                <input type="text" name="q" class="filter-input" placeholder="Cari nama, kode, katalog, merk..." value="<?php echo html_escape($keyword_aktif ?? ''); ?>">

                <select name="jenis" class="filter-select">
                    <option value="">Semua Jenis Barang</option>
                    <?php if (!empty($daftar_jenis)): ?>
                        <?php foreach ($daftar_jenis as $j): ?>
                            <option value="<?php echo html_escape($j); ?>" <?php echo (isset($jenis_aktif) && $jenis_aktif === $j) ? 'selected' : ''; ?>>
                                <?php echo html_escape($j); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>

                <select name="status" class="filter-select">
                    <option value="">Semua Status</option>
                    <option value="Aktif" <?php echo (isset($status_aktif) && $status_aktif === 'Aktif') ? 'selected' : ''; ?>>Aktif</option>
                    <option value="Nonaktif" <?php echo (isset($status_aktif) && $status_aktif === 'Nonaktif') ? 'selected' : ''; ?>>Nonaktif</option>
                </select>

                <button type="submit" class="btn btn-sm btn-primary">
                    <i class="bi bi-search me-1"></i> Filter
                </button>

                <?php if (!empty($keyword_aktif) || !empty($jenis_aktif) || !empty($status_aktif)): ?>
                    <a href="<?php echo site_url('reagen'); ?>" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-x-circle me-1"></i> Reset
                    </a>
                <?php endif; ?>
            </form>

            <div class="text-muted small">
                Total: <strong><?php echo count($daftar_reagen); ?></strong> data reagen
            </div>
        </div>

        <div class="reagent-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle reagent-table" id="tabel-reagen">
                    <thead>
                        <tr>
                            <th width="4%" class="text-center">No</th>
                            <th>Kode Barang</th>
                            <th>Katalog</th>
                            <th>Nama Barang</th>
                            <th>Merk</th>
                            <th>Stok</th>
                            <th>Jenis Barang</th>
                            <th>Expired Date</th>
                            <th width="12%" class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (empty($daftar_reagen)): ?>
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox fs-3 d-block mb-2 text-muted"></i>
                                    Belum ada data reagen laboratorium yang sesuai.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php $no = 1; foreach ($daftar_reagen as $r): ?>
                                <tr>
                                    <td class="text-center"><?php echo $no++; ?></td>
                                    <td>
                                        <?php if (!empty($r['kode_barang'])): ?>
                                            <span class="code-badge"><?php echo html_escape($r['kode_barang']); ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($r['katalog'])): ?>
                                            <span class="code-badge"><?php echo html_escape($r['katalog']); ?></span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="reagent-name">
                                            <?php echo html_escape(!empty($r['nama_barang']) ? $r['nama_barang'] : $r['nama_reagen']); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <?php echo !empty($r['merk']) ? html_escape($r['merk']) : '<span class="text-muted">-</span>'; ?>
                                    </td>
                                    <td>
                                        <?php 
                                        $stok_val = !empty($r['stok']) ? trim($r['stok']) : '0 BOTOL';
                                        $is_zero = preg_match('/^0\b/', $stok_val);
                                        ?>
                                        <span class="stok-badge <?php echo $is_zero ? 'stok-empty' : 'stok-ready'; ?>">
                                            <?php echo html_escape($stok_val); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="category-tag">
                                            <?php echo html_escape(!empty($r['jenis_barang']) ? $r['jenis_barang'] : 'Reagen Cair'); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if (!empty($r['expired_date']) && $r['expired_date'] !== '0000-00-00'): ?>
                                            <?php 
                                            $is_exp = strtotime($r['expired_date']) < time();
                                            ?>
                                            <span class="<?php echo $is_exp ? 'exp-expired' : 'exp-safe'; ?>">
                                                <?php echo date('d-m-Y', strtotime($r['expired_date'])); ?>
                                                <?php if ($is_exp): ?><i class="bi bi-exclamation-circle-fill text-danger ms-1" title="Kedaluwarsa"></i><?php endif; ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="action-btn-group justify-content-center">
                                            <a href="<?php echo site_url('reagen/detail/' . $r['id']); ?>" class="action-btn action-detail" title="Lihat Detail">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <?php if ($this->Model_Hak_Akses->memiliki_akses($this->session->userdata('role_id'), 'reagen_edit')): ?>
                                                <a href="<?php echo site_url('reagen/edit/' . $r['id']); ?>" class="action-btn action-edit" title="Edit Reagen">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <button type="button" class="action-btn action-status" title="<?php echo ($r['status'] === 'Aktif') ? 'Nonaktifkan' : 'Aktifkan'; ?>"
                                                        data-bs-toggle="modal" data-bs-target="#modalStatus<?php echo $r['id']; ?>">
                                                    <i class="bi bi-power"></i>
                                                </button>
                                            <?php endif; ?>
                                            <?php if ($this->Model_Hak_Akses->memiliki_akses($this->session->userdata('role_id'), 'reagen_delete')): ?>
                                                <button type="button" class="action-btn action-delete" title="Hapus Reagen"
                                                        data-bs-toggle="modal" data-bs-target="#modalHapus<?php echo $r['id']; ?>">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Modal Toggle Status -->
                                        <div class="modal fade" id="modalStatus<?php echo $r['id']; ?>" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content border-0 shadow">
                                                    <div class="modal-header border-bottom">
                                                        <h5 class="modal-title fs-6 fw-bold">Konfirmasi Ubah Status</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body text-start py-3">
                                                        Apakah Anda yakin ingin mengubah status reagen <strong>"<?php echo html_escape(!empty($r['nama_barang']) ? $r['nama_barang'] : $r['nama_reagen']); ?>"</strong> menjadi <strong><?php echo ($r['status'] === 'Aktif') ? 'Nonaktif' : 'Aktif'; ?></strong>?
                                                    </div>
                                                    <div class="modal-footer border-top py-2">
                                                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <a href="<?php echo site_url('reagen/status/' . $r['id']); ?>" class="btn btn-sm btn-warning">Ya, Ubah Status</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Modal Hapus -->
                                        <div class="modal fade" id="modalHapus<?php echo $r['id']; ?>" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content border-0 shadow">
                                                    <div class="modal-header border-bottom">
                                                        <h5 class="modal-title fs-6 fw-bold text-danger">Konfirmasi Hapus Data</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body text-start py-3">
                                                        Apakah Anda yakin ingin menghapus data reagen <strong>"<?php echo html_escape(!empty($r['nama_barang']) ? $r['nama_barang'] : $r['nama_reagen']); ?>"</strong>? Tindakan ini tidak dapat dibatalkan.
                                                    </div>
                                                    <div class="modal-footer border-top py-2">
                                                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <a href="<?php echo site_url('reagen/hapus/' . $r['id']); ?>" class="btn btn-sm btn-danger">Ya, Hapus</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
            $('#tabel-reagen').DataTable({
                language: {
                    search: "Cari Reagen:",
                    lengthMenu: "Tampilkan _MENU_ baris",
                    info: "Menampilkan _START_ s/d _END_ dari _TOTAL_ data reagen",
                    infoEmpty: "Menampilkan 0 data",
                    zeroRecords: "Tidak ada data reagen yang cocok",
                    paginate: { first: "Awal", last: "Akhir", next: "Berikutnya", previous: "Sebelumnya" }
                },
                columnDefs: [ { orderable: false, targets: [0, 8] } ],
                order: [[3, 'asc']],
                pageLength: 15,
                lengthMenu: [[15, 30, 50, 100, -1], [15, 30, 50, 100, "Semua"]],
                responsive: true
            });
        }
    });
</script>
