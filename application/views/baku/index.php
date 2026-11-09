<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
    .baku-page {
        color: #1e293b;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }
    
    /* Header Card & Actions */
    .baku-header-card {
        background: #ffffff;
        border: 1px solid #edf2f7;
        border-radius: 14px;
        padding: 18px 22px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }

    .baku-header-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.25rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .baku-header-title i {
        color: #0284c7;
        font-size: 1.25rem;
    }

    /* Action Buttons */
    .btn-baku-add {
        background: #0284c7;
        border: 1px solid #0284c7;
        color: #ffffff !important;
        font-weight: 600;
        font-size: 0.82rem;
        border-radius: 999px;
        padding: 0.45rem 1.15rem;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        text-decoration: none !important;
        transition: all 0.2s ease;
    }

    .btn-baku-add:hover {
        background: #0369a1;
        border-color: #0369a1;
        color: #ffffff !important;
        box-shadow: 0 3px 8px rgba(2, 132, 199, 0.25);
        text-decoration: none !important;
    }

    .btn-baku-excel {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #166534 !important;
        font-weight: 600;
        font-size: 0.82rem;
        border-radius: 999px;
        padding: 0.45rem 1.15rem;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        text-decoration: none !important;
        transition: all 0.2s ease;
    }

    .btn-baku-excel:hover {
        background: #dcfce7;
        border-color: #86efac;
        color: #14532d !important;
        text-decoration: none !important;
    }

    /* Filter Card */
    .baku-filter-card {
        background: #ffffff;
        border: 1px solid #edf2f7;
        border-radius: 14px;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.03);
        margin-bottom: 20px;
        padding: 16px 20px;
    }

    .baku-filter-label {
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        margin-bottom: 0.35rem;
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }

    .baku-filter-input {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        color: #334155;
        font-size: 0.82rem;
        padding: 0.45rem 0.75rem;
        transition: all 0.2s ease;
    }

    .baku-filter-input:focus {
        background-color: #ffffff;
        border-color: #38bdf8;
        box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.15);
        color: #0f172a;
    }

    .baku-filter-btn {
        background: #0284c7;
        border: 1px solid #0284c7;
        border-radius: 8px;
        color: #ffffff;
        font-weight: 600;
        font-size: 0.82rem;
        padding: 0.45rem 1rem;
        transition: all 0.2s ease;
    }

    .baku-filter-btn:hover {
        background: #0369a1;
        border-color: #0369a1;
        color: #ffffff;
        box-shadow: 0 2px 6px rgba(2, 132, 199, 0.3);
    }

    .baku-filter-reset {
        border: 1px solid #e2e8f0;
        background: #ffffff;
        border-radius: 8px;
        color: #64748b;
        padding: 0.45rem 0.75rem;
        transition: all 0.2s ease;
    }

    .baku-filter-reset:hover {
        background: #f1f5f9;
        color: #0f172a;
        border-color: #cbd5e1;
    }

    /* Main Table Card */
    .baku-table-card {
        background: #ffffff;
        border: 1px solid #edf2f7;
        border-radius: 14px;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
        overflow: hidden;
    }

    .baku-table-header {
        background: #ffffff;
        padding: 16px 22px;
        border-bottom: 1px solid #edf2f7;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .baku-table {
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
    }

    .baku-table thead th {
        background-color: #f8fafc;
        color: #475569;
        font-weight: 700;
        font-size: 0.74rem;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        border-bottom: 1px solid #e2e8f0;
        border-top: none;
        padding: 12px 16px;
        white-space: nowrap;
    }

    .baku-table tbody td {
        font-size: 0.84rem;
        vertical-align: middle;
        padding: 12px 16px;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
    }

    .baku-table tbody tr {
        transition: background-color 0.15s ease-in-out;
    }

    .baku-table tbody tr:hover {
        background-color: #f8fafc;
    }

    /* Badges & Chips */
    .badge-kontrol {
        background: #f8fafc;
        color: #334155;
        border: 1px solid #e2e8f0;
        font-family: 'JetBrains Mono', Consolas, monospace;
        font-size: 0.74rem;
        padding: 3px 7px;
        border-radius: 6px;
    }

    .badge-posisi {
        background: #f0f9ff;
        color: #0369a1;
        border: 1px solid #bae6fd;
        font-family: 'JetBrains Mono', Consolas, monospace;
        font-size: 0.74rem;
        font-weight: 700;
        padding: 3px 7px;
        border-radius: 6px;
    }

    .badge-persen-air {
        background: #f0fdfa;
        color: #0f766e;
        border: 1px solid #ccfbf1;
        font-family: 'JetBrains Mono', Consolas, monospace;
        font-size: 0.76rem;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 6px;
    }

    .badge-persen-baku {
        background: #f0fdf4;
        color: #15803d;
        border: 1px solid #bbf7d0;
        font-family: 'JetBrains Mono', Consolas, monospace;
        font-size: 0.76rem;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 6px;
    }

    .action-icon-btn {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #475569;
        transition: all 0.15s ease;
        text-decoration: none;
    }

    .action-icon-btn:hover {
        background: #f1f5f9;
        color: #0284c7;
        border-color: #cbd5e1;
    }

    .action-icon-btn.btn-delete:hover {
        background: #fef2f2;
        color: #dc2626;
        border-color: #fecaca;
    }
</style>

<div class="baku-page">

    <!-- Header Section -->
    <div class="baku-header-card d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div>
            <h4 class="baku-header-title">
                <i class="bi bi-eyedropper"></i> Master Data Baku Pembanding
            </h4>
            <p class="text-muted mb-0 small">
                Kelola data baku laboratorium kimia, nomor kontrol, lokasi penyimpanan, suhu, serta nilai normalisasi kadar air dan kadar baku (KCKT).
            </p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <?php if ($this->Model_Hak_Akses->memiliki_akses($this->session->userdata('role_id'), 'baku_import')): ?>
                <a href="<?php echo site_url('baku/import'); ?>" class="btn-baku-excel" title="Impor data dari spreadsheet Excel">
                    <i class="bi bi-file-earmark-excel"></i>
                    <span>Impor Excel</span>
                </a>
            <?php endif; ?>
            <?php if ($this->Model_Hak_Akses->memiliki_akses($this->session->userdata('role_id'), 'baku_create')): ?>
                <a href="<?php echo site_url('baku/tambah'); ?>" class="btn-baku-add" title="Tambah data baku baru">
                    <i class="bi bi-plus-lg"></i>
                    <span>Tambah Baku</span>
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Alert Notifikasi -->
    <?php if ($this->session->flashdata('pesan_sukses')): ?>
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 10px; background: #f0fdf4; color: #166534;">
            <i class="bi bi-check-circle-fill me-2 fs-5 align-middle text-success"></i>
            <span><?php echo $this->session->flashdata('pesan_sukses'); ?></span>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('pesan_gagal')): ?>
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 10px; background: #fef2f2; color: #991b1b;">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-5 align-middle text-danger"></i>
            <span><?php echo $this->session->flashdata('pesan_gagal'); ?></span>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Filter & Pencarian Bersih (Clean) -->
    <div class="baku-filter-card">
        <form method="get" action="<?php echo site_url('baku'); ?>" class="row g-3 align-items-end">
            <div class="col-md-5">
                <label class="baku-filter-label">
                    <i class="bi bi-search text-primary"></i> Kata Kunci
                </label>
                <input type="text" name="q" class="form-control baku-filter-input" placeholder="Cari nama baku, no kontrol, kode posisi..." value="<?php echo html_escape($keyword_aktif); ?>">
            </div>
            <div class="col-md-4">
                <label class="baku-filter-label">
                    <i class="bi bi-geo-alt text-primary"></i> Lokasi Penyimpanan
                </label>
                <select name="lokasi" class="form-select baku-filter-input">
                    <option value="">Semua Lokasi Penyimpanan</option>
                    <?php if (!empty($daftar_lokasi)): ?>
                        <?php foreach ($daftar_lokasi as $lok): ?>
                            <option value="<?php echo html_escape($lok); ?>" <?php echo ($lokasi_aktif === $lok) ? 'selected' : ''; ?>>
                                <?php echo html_escape($lok); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn baku-filter-btn flex-grow-1 d-inline-flex align-items-center justify-content-center gap-1">
                    <i class="bi bi-funnel"></i>
                    <span>Filter</span>
                </button>
                <?php if (!empty($keyword_aktif) || !empty($lokasi_aktif)): ?>
                    <a href="<?php echo site_url('baku'); ?>" class="btn baku-filter-reset d-inline-flex align-items-center justify-content-center" title="Reset filter">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Tabel Data Master Baku -->
    <div class="baku-table-card">
        <div class="baku-table-header">
            <div class="d-flex align-items-center gap-2">
                <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.92rem;">
                    Daftar Baku Pembanding
                </h6>
                <span class="badge rounded-pill" style="font-size: 0.72rem; background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; font-weight: 600;">
                    <?php echo number_format($total_baku); ?> Data
                </span>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table align-middle baku-table">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 45px;">No</th>
                        <th>Nama Baku</th>
                        <th style="width: 140px;">No. Kontrol</th>
                        <th class="text-center" style="width: 80px;">Kode</th>
                        <th class="text-center" style="width: 120px;">Kadar Air</th>
                        <th class="text-center" style="width: 130px;">Kadar Baku</th>
                        <th>Lokasi &amp; Suhu</th>
                        <th style="width: 250px;">Persyaratan CoA</th>
                        <th class="text-center" style="width: 90px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($daftar_baku)): ?>
                        <?php $no = 1; foreach ($daftar_baku as $b): ?>
                            <tr>
                                <td class="text-center text-muted fw-semibold" style="font-size: 0.8rem;"><?php echo $no++; ?></td>
                                <td>
                                    <div class="fw-bold text-dark"><?php echo html_escape($b['nama_baku']); ?></div>
                                    <?php if (!empty($b['kemurnian_raw'])): ?>
                                        <div class="text-muted font-monospace" style="font-size: 0.74rem; max-width: 320px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?php echo html_escape($b['kemurnian_raw']); ?>">
                                            <?php echo html_escape($b['kemurnian_raw']); ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge-kontrol">
                                        <?php echo html_escape($b['no_kontrol'] ?: '-'); ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge-posisi">
                                        <?php echo html_escape($b['kode_posisi'] ?: '-'); ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <?php if ($b['kadar_air'] !== null): ?>
                                        <span class="badge-persen-air">
                                            <?php echo number_format($b['kadar_air'], 2); ?>%
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($b['kadar_baku'] !== null): ?>
                                        <span class="badge-persen-baku">
                                            <?php echo number_format($b['kadar_baku'], 2); ?>%
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="fw-semibold text-secondary small"><?php echo html_escape($b['lokasi_penyimpanan']); ?></div>
                                    <div class="text-muted" style="font-size: 0.74rem;">
                                        <i class="bi bi-thermometer-half text-danger me-1"></i><?php echo html_escape($b['suhu_rekomendasi'] ?: '-'); ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-muted" style="max-height: 48px; overflow-y: auto; font-size: 0.76rem; line-height: 1.35;">
                                        <?php echo html_escape($b['penyimpanan'] ?: '-'); ?>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex align-items-center justify-content-center gap-1">
                                        <?php if ($this->Model_Hak_Akses->memiliki_akses($this->session->userdata('role_id'), 'baku_edit')): ?>
                                            <a href="<?php echo site_url('baku/ubah/' . $b['id']); ?>" class="action-icon-btn" title="Ubah Data Baku">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if ($this->Model_Hak_Akses->memiliki_akses($this->session->userdata('role_id'), 'baku_delete')): ?>
                                            <a href="<?php echo site_url('baku/hapus/' . $b['id']); ?>" class="action-icon-btn btn-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus baku \'<?php echo html_escape($b['nama_baku']); ?>\'?');" title="Hapus Data Baku">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                                <span>Belum ada data baku pembanding yang ditemukan.</span>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
