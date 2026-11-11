<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style>
    .logbook-page {
        color: #1e293b;
        font-size: 0.86rem;
    }
    .stat-card-custom {
        background: #ffffff;
        border: 1px solid #edf2f7;
        border-radius: 12px;
        padding: 16px 20px;
        transition: all 0.2s ease;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }
    .stat-card-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 14px -3px rgba(0,0,0,0.06);
        border-color: #cbd5e1;
    }
    .stat-icon-wrap {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        flex-shrink: 0;
    }
    .filter-card {
        background: #ffffff;
        border: 1px solid #edf2f7;
        border-radius: 14px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.03);
    }
    .filter-input-clean {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        color: #334155;
        font-size: 0.82rem;
        padding: 0.45rem 0.75rem;
        transition: all 0.2s ease;
    }
    .filter-input-clean:focus {
        background-color: #ffffff;
        border-color: #38bdf8;
        box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.15);
        color: #0f172a;
    }
    .filter-label-clean {
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
    .filter-btn-search {
        background: #0284c7;
        border: 1px solid #0284c7;
        border-radius: 8px;
        color: #ffffff;
        font-weight: 600;
        font-size: 0.82rem;
        padding: 0.45rem 1rem;
        transition: all 0.2s ease;
    }
    .filter-btn-search:hover {
        background: #0369a1;
        border-color: #0369a1;
        color: #ffffff;
        box-shadow: 0 2px 6px rgba(2, 132, 199, 0.3);
    }
    .filter-btn-reset {
        border: 1px solid #e2e8f0;
        background: #ffffff;
        border-radius: 8px;
        color: #64748b;
        padding: 0.45rem 0.75rem;
        transition: all 0.2s ease;
    }
    .filter-btn-reset:hover {
        background: #f1f5f9;
        color: #0f172a;
        border-color: #cbd5e1;
    }
    .main-table-card {
        background: #ffffff;
        border: 1px solid #edf2f7;
        border-radius: 14px;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
        overflow: hidden;
    }
    .logbook-table th {
        background-color: #f8fafc;
        color: #475569;
        font-weight: 700;
        font-size: 0.76rem;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        border-bottom: 2px solid #e2e8f0;
        padding: 14px 16px;
        white-space: nowrap;
    }
    .logbook-table td {
        font-size: 0.85rem;
        vertical-align: middle;
        padding: 14px 16px;
        border-bottom: 1px solid #f1f5f9;
    }
    .logbook-table tbody tr {
        transition: background-color 0.15s ease-in-out;
    }
    .logbook-table tbody tr:hover {
        background-color: #f8fafc;
    }
    .index-pill {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: #f1f5f9;
        color: #475569;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.76rem;
        font-weight: 700;
    }
    .badge-ruangan-pill {
        background: #eff6ff;
        color: #1e40af;
        border: 1px solid #bfdbfe;
        font-size: 0.74rem;
        padding: 4px 10px;
        border-radius: 50rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
    }
    .badge-serial {
        background: #f8fafc;
        color: #0f172a;
        border: 1px solid #cbd5e1;
        font-family: 'JetBrains Mono', Consolas, SFMono-Regular, monospace;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 3px 8px;
        border-radius: 6px;
    }
    .nav-tabs-custom {
        border-bottom: 2px solid #e2e8f0;
        gap: 6px;
    }
    .nav-tabs-custom .nav-link {
        border: 1px solid transparent;
        border-bottom: 2px solid transparent;
        color: #64748b;
        font-weight: 600;
        font-size: 0.86rem;
        padding: 10px 18px;
        border-radius: 8px 8px 0 0;
        transition: all 0.2s ease;
        background: transparent;
    }
    .nav-tabs-custom .nav-link:hover {
        color: #0284c7;
        background: #f8fafc;
        border-color: #e2e8f0 #e2e8f0 transparent;
    }
    .nav-tabs-custom .nav-link.active {
        color: #0284c7;
        background: #ffffff;
        border-color: #e2e8f0 #e2e8f0 transparent;
        border-bottom: 2px solid #0284c7;
        margin-bottom: -2px;
        font-weight: 700;
    }
    .badge-tab-count {
        font-size: 0.72rem;
        padding: 3px 8px;
        border-radius: 50rem;
    }
</style>

<div class="logbook-page">

    <!-- Ringkasan Statistik Kartu (Paling Atas) -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-lg-3">
            <div class="stat-card-custom d-flex align-items-center gap-3">
                <div class="stat-icon-wrap bg-success-subtle text-success">
                    <i class="bi bi-tools"></i>
                </div>
                <div>
                    <div class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.5px; font-size: 0.72rem;">Alat Pernah Digunakan</div>
                    <h4 class="fw-bold mb-0 text-dark"><?php echo number_format($statistik['total_alat_terpakai']); ?> <small class="text-muted fs-6 fw-normal">Alat Aktif</small></h4>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="stat-card-custom d-flex align-items-center gap-3">
                <div class="stat-icon-wrap bg-primary-subtle text-primary">
                    <i class="bi bi-cpu-fill"></i>
                </div>
                <div>
                    <div class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.5px; font-size: 0.72rem;">Total Master Alat</div>
                    <h4 class="fw-bold mb-0 text-dark"><?php echo number_format($statistik['total_master_alat']); ?> <small class="text-muted fs-6 fw-normal">Instrumen</small></h4>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="stat-card-custom d-flex align-items-center gap-3">
                <div class="stat-icon-wrap bg-info-subtle text-info-emphasis">
                    <i class="bi bi-journal-check"></i>
                </div>
                <div>
                    <div class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.5px; font-size: 0.72rem;">Total Riwayat Log</div>
                    <h4 class="fw-bold mb-0 text-dark"><?php echo number_format($statistik['total_log']); ?> <small class="text-muted fs-6 fw-normal">Catatan</small></h4>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="stat-card-custom d-flex align-items-center gap-3">
                <div class="stat-icon-wrap bg-warning-subtle text-warning-emphasis">
                    <i class="bi bi-box-seam-fill"></i>
                </div>
                <div>
                    <div class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.5px; font-size: 0.72rem;">Sampel Diuji</div>
                    <h4 class="fw-bold mb-0 text-dark"><?php echo number_format($statistik['total_sampel']); ?> <small class="text-muted fs-6 fw-normal">Sampel</small></h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Bar Navigasi Tab & Tombol Cetak Rekap Logbook -->
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 mb-3">
        <!-- Tab Navigasi Tampilan Logbook Berbasis Alat -->
        <ul class="nav nav-tabs nav-tabs-custom border-bottom-0 mb-0">
            <li class="nav-item">
                <a class="nav-link <?php echo ($tab_aktif === 'terpakai') ? 'active' : ''; ?>" 
                   href="<?php echo site_url('logbook?tab=terpakai' . (!empty($filter['ruangan']) ? '&ruangan=' . urlencode($filter['ruangan']) : '') . (!empty($filter['q']) ? '&q=' . urlencode($filter['q']) : '')); ?>">
                    Alat Pernah Digunakan
                    <span class="badge bg-success-subtle text-success border border-success-subtle badge-tab-count ms-1">
                        <?php echo $statistik['total_alat_terpakai']; ?>
                    </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($tab_aktif === 'semua') ? 'active' : ''; ?>" 
                   href="<?php echo site_url('logbook?tab=semua' . (!empty($filter['ruangan']) ? '&ruangan=' . urlencode($filter['ruangan']) : '') . (!empty($filter['q']) ? '&q=' . urlencode($filter['q']) : '')); ?>">
                    Semua Alat Laboratorium
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle badge-tab-count ms-1">
                        <?php echo $statistik['total_master_alat']; ?>
                    </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo ($tab_aktif === 'belum_terpakai') ? 'active' : ''; ?>" 
                   href="<?php echo site_url('logbook?tab=belum_terpakai' . (!empty($filter['ruangan']) ? '&ruangan=' . urlencode($filter['ruangan']) : '') . (!empty($filter['q']) ? '&q=' . urlencode($filter['q']) : '')); ?>">
                    Belum Pernah Digunakan
                    <span class="badge bg-secondary-subtle text-secondary border badge-tab-count ms-1">
                        <?php echo $statistik['total_alat_belum_terpakai']; ?>
                    </span>
                </a>
            </li>
        </ul>

        <!-- Tombol Cetak Rekap Logbook (Di Bawah Card Statistik) -->
        <div>
            <a href="<?php echo site_url('logbook/cetak'); ?>" target="_blank" class="btn btn-outline-primary btn-sm px-3 shadow-sm rounded-pill d-inline-flex align-items-center gap-2 fw-semibold">
                <i class="bi bi-printer-fill"></i>
                <span>Cetak Rekap Logbook</span>
            </a>
        </div>
    </div>

    <!-- Filter & Pencarian Cepat -->
    <div class="card filter-card mb-4">
        <div class="card-body p-3">
            <form method="get" action="<?php echo site_url('logbook'); ?>" class="row g-2 align-items-end">
                <input type="hidden" name="tab" value="<?php echo html_escape($tab_aktif); ?>">

                <div class="col-md-5">
                    <label class="form-label small fw-bold text-secondary mb-1">
                        <i class="bi bi-search me-1 text-primary"></i> Cari Alat / Instrumen
                    </label>
                    <input type="text" class="form-control form-control-sm" name="q" placeholder="Cari nama alat, kode BMN (ALT-xxx), nomor seri (SN), merk..." value="<?php echo html_escape(!empty($filter['q']) ? $filter['q'] : ''); ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label small fw-bold text-secondary mb-1">
                        <i class="bi bi-geo-alt me-1 text-primary"></i> Lokasi Ruangan Laboratorium
                    </label>
                    <select class="form-select form-select-sm" name="ruangan">
                        <option value="">-- Semua Ruangan Laboratorium --</option>
                        <?php if (!empty($daftar_ruangan)): ?>
                            <?php foreach ($daftar_ruangan as $rng): ?>
                                <option value="<?php echo html_escape($rng); ?>" <?php echo (!empty($filter['ruangan']) && $filter['ruangan'] === $rng) ? 'selected' : ''; ?>>
                                    <?php echo html_escape($rng); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="col-md-3 d-flex gap-1">
                    <button type="submit" class="btn btn-primary btn-sm w-100 fw-semibold">
                        <i class="bi bi-filter me-1"></i> Terapkan Filter
                    </button>
                    <a href="<?php echo site_url('logbook?tab=' . $tab_aktif); ?>" class="btn btn-outline-secondary btn-sm" title="Reset Pencarian">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Master Alat & Status Buku Log -->
    <div class="main-table-card">
        <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <div class="bg-primary-subtle text-primary p-2 rounded-2 d-inline-flex">
                    <i class="bi bi-journal-bookmark-fill"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0 text-dark">
                        <?php if ($tab_aktif === 'terpakai'): ?>
                            Daftar Alat yang Pernah Digunakan
                        <?php elseif ($tab_aktif === 'belum_terpakai'): ?>
                            Daftar Alat yang Belum Pernah Digunakan
                        <?php else: ?>
                            Daftar Seluruh Alat Laboratorium
                        <?php endif; ?>
                    </h6>
                    <small class="text-muted">Klik tombol "Buka Buku Log" pada alat yang diinginkan untuk melihat rincian sampel, tanggal, dan penguji</small>
                </div>
            </div>
            <div>
                <span class="badge bg-secondary-subtle text-secondary border px-3 py-1 rounded-pill fw-semibold">
                    <i class="bi bi-tools me-1"></i> Total Ditampilkan: <?php echo count($daftar_alat_stat); ?> Alat
                </span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table align-middle mb-0 logbook-table">
                <thead>
                    <tr>
                        <th class="ps-4" style="width: 5%;">No</th>
                        <th style="width: 25%;">Nama Alat &amp; Model</th>
                        <th style="width: 15%;">Kode Barang &amp; No. Seri</th>
                        <th style="width: 14%;">Lokasi Ruangan</th>
                        <th style="width: 13%;">Status Penggunaan</th>
                        <th style="width: 16%;">Penggunaan Terakhir</th>
                        <th class="pe-4 text-center" style="width: 12%;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($daftar_alat_stat)): ?>
                        <?php $no = 1; foreach ($daftar_alat_stat as $row): ?>
                            <tr>
                                <td class="ps-4">
                                    <span class="index-pill"><?php echo $no++; ?></span>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <strong class="text-dark" style="font-size: 0.88rem; line-height: 1.3;">
                                            <?php echo html_escape($row['nama_barang']); ?>
                                        </strong>
                                        <?php if (!empty($row['merk_tipe'])): ?>
                                            <span class="text-muted small mt-1" style="font-size: 0.76rem;">
                                                <i class="bi bi-tag me-1 text-secondary"></i><?php echo html_escape($row['merk_tipe']); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        <span class="badge bg-light text-dark border d-inline-block text-start font-monospace" style="font-size: 0.76rem; width: fit-content;">
                                            <i class="bi bi-hash me-1 text-primary"></i><?php echo html_escape($row['kode_barang']); ?>
                                        </span>
                                        <?php if (!empty($row['nomor_seri'])): ?>
                                            <span class="badge-serial d-inline-block">
                                                <i class="bi bi-upc-scan me-1 text-muted"></i>SN: <?php echo html_escape($row['nomor_seri']); ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="badge-serial d-inline-block text-muted" style="font-size: 0.72rem;">
                                                SN: -
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge-ruangan-pill">
                                        <i class="bi bi-geo-alt-fill me-1 text-primary"></i><?php echo html_escape(!empty($row['nama_ruangan']) ? $row['nama_ruangan'] : 'Laboratorium'); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($row['total_pemakaian'] > 0): ?>
                                        <div class="d-flex flex-column gap-1">
                                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1 fw-bold" style="font-size: 0.74rem; width: fit-content;">
                                                <i class="bi bi-check-circle-fill me-1"></i><?php echo number_format($row['total_pemakaian']); ?>x Digunakan
                                            </span>
                                            <small class="text-muted" style="font-size: 0.72rem;">
                                                <i class="bi bi-box-seam me-1"></i><?php echo number_format($row['total_sampel']); ?> Sampel Diuji
                                            </small>
                                        </div>
                                    <?php else: ?>
                                        <span class="badge bg-secondary-subtle text-secondary border rounded-pill px-2 py-1" style="font-size: 0.74rem;">
                                            <i class="bi bi-dash-circle me-1"></i>Belum Pernah Dipakai
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($row['total_pemakaian'] > 0 && !empty($row['terakhir_digunakan'])): ?>
                                        <div class="d-flex flex-column">
                                            <div class="fw-bold text-dark" style="font-size: 0.80rem;">
                                                <i class="bi bi-calendar-event me-1 text-primary"></i><?php echo date('d M Y', strtotime($row['terakhir_digunakan'])); ?>
                                            </div>
                                            <small class="text-muted text-truncate" style="font-size: 0.72rem; max-width: 170px;" title="<?php echo html_escape($row['penguji_terakhir'] ?: 'Penguji'); ?>">
                                                <i class="bi bi-person me-1"></i>Oleh: <?php echo html_escape($row['penguji_terakhir'] ?: 'Penguji'); ?>
                                            </small>
                                            <?php if (!empty($row['sampel_terakhir'])): ?>
                                                <small class="text-secondary text-truncate" style="font-size: 0.70rem; max-width: 170px;" title="<?php echo html_escape($row['sampel_terakhir']); ?>">
                                                    <i class="bi bi-capsule me-1 text-info"></i><?php echo html_escape($row['sampel_terakhir']); ?>
                                                </small>
                                            <?php endif; ?>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted small">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="pe-4 text-center">
                                    <a href="<?php echo site_url('logbook/alat/' . $row['id']); ?>" 
                                       class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm d-inline-flex align-items-center gap-1 fw-semibold"
                                       style="font-size: 0.78rem;"
                                       title="Buka Lembar Buku Log Alat Ini">
                                        <i class="bi bi-journal-text"></i>
                                        <span>Buka Logbook</span>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="py-4">
                                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px;">
                                        <i class="bi bi-tools fs-1 text-muted"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1">Tidak Ada Alat yang Sesuai</h6>
                                    <p class="text-muted small mb-0" style="max-width: 420px; margin: 0 auto;">
                                        <?php if ($tab_aktif === 'terpakai'): ?>
                                            Belum ada alat laboratorium yang tercatat memiliki riwayat pengujian. Anda dapat membuka tab "Semua Alat Laboratorium" untuk melihat seluruh inventaris.
                                        <?php else: ?>
                                            Tidak ditemukan data alat dengan kata kunci atau filter ruangan yang Anda masukkan.
                                        <?php endif; ?>
                                    </p>
                                    <div class="mt-3">
                                        <a href="<?php echo site_url('logbook?tab=semua'); ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                            <i class="bi bi-collection me-1"></i> Tampilkan Semua Alat
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
