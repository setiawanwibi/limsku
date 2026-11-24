<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
    .logbook-page {
        color: #1e293b;
        font-size: 0.85rem;
    }

    /* Stat Cards Minimalis */
    .stat-card-clean {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 14px 18px;
        transition: border-color 0.15s ease;
    }
    .stat-card-clean:hover {
        border-color: #cbd5e1;
    }
    .stat-card-clean .stat-label {
        font-size: 0.72rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        color: #64748b;
        margin-bottom: 4px;
    }
    .stat-card-clean .stat-value {
        font-size: 1.35rem;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.2;
    }
    .stat-card-clean .stat-sub {
        font-size: 0.75rem;
        color: #94a3b8;
        font-weight: 400;
    }

    /* Unified Container */
    .logbook-container {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
        overflow: hidden;
    }

    /* Tab Header */
    .logbook-nav-tabs {
        border-bottom: 1px solid #e2e8f0;
        gap: 4px;
    }
    .logbook-nav-tabs .nav-link {
        border: none;
        border-bottom: 2px solid transparent;
        color: #64748b;
        font-weight: 500;
        font-size: 0.82rem;
        padding: 12px 16px;
        background: transparent;
        transition: color 0.15s ease, border-color 0.15s ease;
    }
    .logbook-nav-tabs .nav-link:hover {
        color: #0284c7;
        border-color: transparent;
    }
    .logbook-nav-tabs .nav-link.active {
        color: #0284c7;
        border-bottom-color: #0284c7;
        font-weight: 600;
        background: transparent;
    }
    .logbook-nav-tabs .badge-count {
        font-size: 0.70rem;
        padding: 2px 7px;
        border-radius: 50rem;
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
        font-weight: 500;
    }
    .logbook-nav-tabs .nav-link.active .badge-count {
        background: #e0f2fe;
        color: #0369a1;
        border-color: #bae6fd;
    }

    /* Toolbar Filter */
    .logbook-toolbar {
        background-color: #f8fafc;
        border-bottom: 1px solid #edf2f7;
        padding: 12px 16px;
    }

    /* Tabel Bersih & Elegan */
    .table-logbook {
        margin-bottom: 0;
        font-size: 0.82rem;
    }
    .table-logbook thead th {
        background-color: #f8fafc;
        color: #475569;
        font-weight: 600;
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid #e2e8f0;
        padding: 11px 16px;
        white-space: nowrap;
    }
    .table-logbook tbody td {
        padding: 12px 16px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
    }
    .table-logbook tbody tr:last-child td {
        border-bottom: none;
    }
    .table-logbook tbody tr:hover td {
        background-color: #f8fafc;
    }
    .table-logbook a.hover-primary:hover {
        color: #0284c7 !important;
        text-decoration: underline !important;
    }

    /* Action Button */
    .btn-logbook-action {
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #0284c7;
        font-weight: 600;
        font-size: 0.76rem;
        padding: 5px 12px;
        border-radius: 6px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: all 0.15s ease;
    }
    .btn-logbook-action:hover {
        background: #f0f9ff;
        border-color: #bae6fd;
        color: #0369a1;
    }
</style>

<div class="logbook-page">

    <!-- Stat Bar Ringkas & Rapi -->
    <div class="row g-2 mb-3">
        <div class="col-6 col-lg-3">
            <div class="stat-card-clean">
                <div class="stat-label">Alat Aktif</div>
                <div class="d-flex align-items-baseline gap-1">
                    <span class="stat-value"><?php echo number_format($statistik['total_alat_terpakai']); ?></span>
                    <span class="stat-sub">/ <?php echo number_format($statistik['total_master_alat']); ?> unit</span>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card-clean">
                <div class="stat-label">Total Master Alat</div>
                <div class="d-flex align-items-baseline gap-1">
                    <span class="stat-value"><?php echo number_format($statistik['total_master_alat']); ?></span>
                    <span class="stat-sub">instrumen</span>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card-clean">
                <div class="stat-label">Riwayat Logbook</div>
                <div class="d-flex align-items-baseline gap-1">
                    <span class="stat-value"><?php echo number_format($statistik['total_log']); ?></span>
                    <span class="stat-sub">catatan</span>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card-clean">
                <div class="stat-label">Sampel Diuji</div>
                <div class="d-flex align-items-baseline gap-1">
                    <span class="stat-value"><?php echo number_format($statistik['total_sampel']); ?></span>
                    <span class="stat-sub">sampel</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Container Utama Terpadu (Satu Card Bersih: Tab + Filter + Tabel) -->
    <div class="logbook-container mb-4">

        <!-- Bar Tab & Tombol Cetak -->
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 px-3 pt-1 border-bottom bg-white">
            <ul class="nav logbook-nav-tabs">
                <li class="nav-item">
                    <a class="nav-link <?php echo ($tab_aktif === 'terpakai') ? 'active' : ''; ?>" 
                       href="<?php echo site_url('logbook?tab=terpakai' . (!empty($filter['ruangan']) ? '&ruangan=' . urlencode($filter['ruangan']) : '') . (!empty($filter['q']) ? '&q=' . urlencode($filter['q']) : '')); ?>">
                        Alat Pernah Digunakan
                        <span class="badge-count ms-1"><?php echo $statistik['total_alat_terpakai']; ?></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($tab_aktif === 'semua') ? 'active' : ''; ?>" 
                       href="<?php echo site_url('logbook?tab=semua' . (!empty($filter['ruangan']) ? '&ruangan=' . urlencode($filter['ruangan']) : '') . (!empty($filter['q']) ? '&q=' . urlencode($filter['q']) : '')); ?>">
                        Semua Alat
                        <span class="badge-count ms-1"><?php echo $statistik['total_master_alat']; ?></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($tab_aktif === 'belum_terpakai') ? 'active' : ''; ?>" 
                       href="<?php echo site_url('logbook?tab=belum_terpakai' . (!empty($filter['ruangan']) ? '&ruangan=' . urlencode($filter['ruangan']) : '') . (!empty($filter['q']) ? '&q=' . urlencode($filter['q']) : '')); ?>">
                        Belum Pernah Digunakan
                        <span class="badge-count ms-1"><?php echo $statistik['total_alat_belum_terpakai']; ?></span>
                    </a>
                </li>
            </ul>

            <div class="py-1">
                <a href="<?php echo site_url('logbook/cetak'); ?>" target="_blank" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1.5" style="font-size: 0.77rem; border-radius: 6px;">
                    <i class="bi bi-printer"></i>
                    <span>Cetak Rekap</span>
                </a>
            </div>
        </div>

        <!-- Toolbar Pencarian & Filter Terintegrasi (Tanpa Card Terpisah) -->
        <div class="logbook-toolbar">
            <form method="get" action="<?php echo site_url('logbook'); ?>" class="row g-2 align-items-center">
                <input type="hidden" name="tab" value="<?php echo html_escape($tab_aktif); ?>">

                <div class="col-md-6 col-lg-7">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white text-muted border-end-0">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" class="form-control border-start-0" name="q" placeholder="Cari nama alat, kode BMN (ALT-xxx), nomor seri, merk..." value="<?php echo html_escape(!empty($filter['q']) ? $filter['q'] : ''); ?>">
                    </div>
                </div>

                <div class="col-md-4 col-lg-3">
                    <select class="form-select form-select-sm" name="ruangan">
                        <option value="">Semua Ruangan</option>
                        <?php if (!empty($daftar_ruangan)): ?>
                            <?php foreach ($daftar_ruangan as $rng): ?>
                                <option value="<?php echo html_escape($rng); ?>" <?php echo (!empty($filter['ruangan']) && $filter['ruangan'] === $rng) ? 'selected' : ''; ?>>
                                    <?php echo html_escape($rng); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="col-md-2 col-lg-2 d-flex gap-1">
                    <button type="submit" class="btn btn-sm btn-dark w-100 fw-medium" style="font-size: 0.8rem; border-radius: 6px;">
                        Terapkan
                    </button>
                    <?php if (!empty($filter['q']) || !empty($filter['ruangan'])): ?>
                        <a href="<?php echo site_url('logbook?tab=' . $tab_aktif); ?>" class="btn btn-sm btn-outline-secondary" title="Reset filter" style="border-radius: 6px;">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- Tabel Logbook Bersih -->
        <div class="table-responsive">
            <table class="table table-logbook align-middle">
                <thead>
                    <tr>
                        <th class="ps-3" style="width: 4%;">#</th>
                        <th style="width: 27%;">Nama Alat &amp; Model</th>
                        <th style="width: 17%;">Kode &amp; No. Seri</th>
                        <th style="width: 15%;">Ruangan</th>
                        <th style="width: 14%;">Penggunaan</th>
                        <th style="width: 15%;">Terakhir Dipakai</th>
                        <th class="pe-3 text-end" style="width: 8%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($daftar_alat_stat)): ?>
                        <?php $no = 1; foreach ($daftar_alat_stat as $row): ?>
                            <tr>
                                <td class="ps-3 text-muted" style="font-size: 0.78rem;">
                                    <?php echo $no++; ?>
                                </td>
                                <td>
                                    <a href="<?php echo site_url('logbook/alat/' . $row['id']); ?>" class="fw-semibold text-dark text-decoration-none hover-primary d-inline-block" style="font-size: 0.85rem; line-height: 1.35;" title="Klik untuk melihat riwayat penggunaan alat ini">
                                        <?php echo html_escape($row['nama_barang']); ?>
                                    </a>
                                    <?php if (!empty($row['merk_tipe'])): ?>
                                        <div class="text-muted" style="font-size: 0.74rem; margin-top: 2px;">
                                            <?php echo html_escape($row['merk_tipe']); ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="font-monospace fw-semibold text-dark" style="font-size: 0.78rem;">
                                        <?php echo html_escape($row['kode_barang']); ?>
                                    </div>
                                    <div class="text-muted" style="font-size: 0.72rem;">
                                        SN: <?php echo html_escape(!empty($row['nomor_seri']) ? $row['nomor_seri'] : '-'); ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-secondary" style="font-size: 0.78rem;">
                                        <i class="bi bi-geo-alt text-muted me-1"></i><?php echo html_escape(!empty($row['nama_ruangan']) ? $row['nama_ruangan'] : '-'); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($row['total_pemakaian'] > 0): ?>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle fw-semibold px-2 py-0.5" style="font-size: 0.72rem; border-radius: 4px;">
                                            <?php echo number_format($row['total_pemakaian']); ?>x digunakan
                                        </span>
                                        <div class="text-muted mt-0.5" style="font-size: 0.71rem;">
                                            <?php echo number_format($row['total_sampel']); ?> sampel
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted small" style="font-size: 0.73rem;">Belum pernah</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($row['total_pemakaian'] > 0 && !empty($row['terakhir_digunakan'])): ?>
                                        <div class="fw-medium text-dark" style="font-size: 0.78rem;">
                                            <?php echo date('d M Y', strtotime($row['terakhir_digunakan'])); ?>
                                        </div>
                                        <div class="text-muted text-truncate" style="font-size: 0.71rem; max-width: 170px;" title="<?php echo html_escape(($row['sampel_terakhir'] ? $row['sampel_terakhir'] . ' • ' : '') . ($row['penguji_terakhir'] ?: 'Penguji')); ?>">
                                            <?php echo html_escape(!empty($row['sampel_terakhir']) ? $row['sampel_terakhir'] : ($row['penguji_terakhir'] ?: 'Penguji')); ?>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted small">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="pe-3 text-end">
                                    <a href="<?php echo site_url('logbook/alat/' . $row['id']); ?>" 
                                       class="btn-logbook-action"
                                       title="Buka Buku Log Alat">
                                        <span>Buka Log</span>
                                        <i class="bi bi-chevron-right" style="font-size: 0.65rem;"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <div class="py-3">
                                    <i class="bi bi-inbox fs-2 d-block mb-2 text-secondary"></i>
                                    <div class="fw-medium text-dark">Tidak ada data alat laboratorium</div>
                                    <div class="small">Silakan sesuaikan filter pencarian atau pilih tab lainnya.</div>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>

</div>
