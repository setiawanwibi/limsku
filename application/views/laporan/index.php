<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
    /* =========================================================
        EKSEKUTIF LABORATORIUM (MODERN & CLEAN DESIGN)
        Standar Visual LIMSKU BBPOM Bandar Lampung
       ========================================================= */

    .laporan-page {
        width: 100%;
        color: #1e293b;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        font-size: 0.88rem;
    }

    /* HEADER UTAMA */
    .laporan-header {
        background: #ffffff;
        border: 1px solid #edf2f7;
        border-radius: 14px;
        padding: 18px 22px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
    }

    .laporan-title {
        margin: 0 0 4px;
        color: #0f172a;
        font-size: 1.18rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .laporan-title i {
        color: #0284c7;
        font-size: 1.25rem;
    }

    .laporan-desc {
        margin: 0;
        color: #64748b;
        font-size: 0.80rem;
    }

    /* ACTION BUTTONS */
    .btn-export-excel {
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        color: #166534 !important;
        font-weight: 600;
        font-size: 0.80rem;
        border-radius: 999px;
        padding: 0.45rem 1.1rem;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        text-decoration: none !important;
        transition: all 0.2s ease;
    }

    .btn-export-excel:hover {
        background: #dcfce7;
        border-color: #86efac;
        color: #14532d !important;
    }

    .btn-export-pdf {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #991b1b !important;
        font-weight: 600;
        font-size: 0.80rem;
        border-radius: 999px;
        padding: 0.45rem 1.1rem;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        text-decoration: none !important;
        transition: all 0.2s ease;
    }

    .btn-export-pdf:hover {
        background: #fee2e2;
        border-color: #fca5a5;
        color: #7f1d1d !important;
    }

    .btn-berkas-lhu {
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #475569 !important;
        font-weight: 600;
        font-size: 0.80rem;
        border-radius: 999px;
        padding: 0.45rem 1.1rem;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        text-decoration: none !important;
        transition: all 0.2s ease;
    }

    .btn-berkas-lhu:hover {
        background: #f1f5f9;
        color: #0f172a !important;
        border-color: #cbd5e1;
    }

    /* KPI METRIC CARDS */
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 22px;
    }

    .kpi-card {
        background: #ffffff;
        border: 1px solid #edf2f7;
        border-radius: 14px;
        padding: 18px 20px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        position: relative;
        overflow: hidden;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .kpi-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 14px -3px rgba(0, 0, 0, 0.06);
    }

    .kpi-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: #0284c7;
    }

    .kpi-card.kpi-warning::before { background: #f59e0b; }
    .kpi-card.kpi-success::before { background: #10b981; }
    .kpi-card.kpi-danger::before { background: #ef4444; }
    .kpi-card.kpi-info::before { background: #3b82f6; }

    .kpi-label {
        font-size: 0.70rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #64748b;
        letter-spacing: 0.6px;
        margin-bottom: 6px;
        display: block;
    }

    .kpi-value {
        font-size: 1.85rem;
        font-weight: 700;
        color: #0f172a;
        line-height: 1.1;
        margin-bottom: 6px;
    }

    .kpi-sub {
        font-size: 0.72rem;
        color: #94a3b8;
    }

    /* CHARTS & ANALYTICS ROW */
    .analytics-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 18px;
        margin-bottom: 22px;
    }

    @media (max-width: 992px) {
        .analytics-row {
            grid-template-columns: 1fr;
        }
    }

    .chart-card {
        background: #ffffff;
        border: 1px solid #edf2f7;
        border-radius: 14px;
        padding: 20px;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.03);
    }

    .chart-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 1px solid #edf2f7;
    }

    .chart-title {
        font-size: 0.88rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* FILTER PANEL */
    .filter-panel {
        background: #ffffff;
        border: 1px solid #edf2f7;
        border-radius: 14px;
        padding: 18px 20px;
        margin-bottom: 22px;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.03);
    }

    .filter-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
        gap: 12px;
        align-items: flex-end;
    }

    .filter-label {
        font-size: 0.70rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        margin-bottom: 4px;
        display: block;
    }

    .filter-input {
        width: 100%;
        height: 38px;
        padding: 6px 12px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.80rem;
        color: #334155;
        background-color: #f8fafc;
        transition: all 0.2s ease;
    }

    .filter-input:focus {
        border-color: #38bdf8;
        outline: none;
        box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.15);
        background-color: #ffffff;
        color: #0f172a;
    }

    .btn-filter-apply {
        background: #0284c7;
        border: 1px solid #0284c7;
        color: #ffffff;
        font-weight: 600;
        font-size: 0.82rem;
        border-radius: 8px;
        height: 38px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.35rem;
        transition: all 0.2s ease;
    }

    .btn-filter-apply:hover {
        background: #0369a1;
        border-color: #0369a1;
        color: #ffffff;
        box-shadow: 0 2px 6px rgba(2, 132, 199, 0.25);
    }

    .btn-filter-reset {
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #64748b;
        border-radius: 8px;
        height: 38px;
        width: 38px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .btn-filter-reset:hover {
        background: #f1f5f9;
        color: #0f172a;
        border-color: #cbd5e1;
    }

    /* DATA TABLES & CARDS */
    .table-card {
        background: #ffffff;
        border: 1px solid #edf2f7;
        border-radius: 14px;
        overflow: hidden;
        margin-bottom: 22px;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
    }

    .table-card-header {
        padding: 16px 22px;
        background: #ffffff;
        border-bottom: 1px solid #edf2f7;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }

    .table-card-title {
        font-size: 0.90rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .table-responsive {
        width: 100%;
        overflow-x: auto;
    }

    /* STYLING TABEL MATRIKS CLEAN */
    .table-limsku-matrix {
        width: 100% !important;
        margin: 0 !important;
        border-collapse: separate;
        border-spacing: 0;
    }

    .table-limsku-matrix thead th {
        background: #f8fafc;
        padding: 12px 14px;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #475569;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
        vertical-align: middle;
    }

    .table-limsku-matrix tbody td {
        padding: 12px 14px;
        font-size: 0.82rem;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .table-limsku-matrix tbody tr:hover td {
        background: #f8fafc;
    }

    .table-limsku-matrix tbody tr:last-child td {
        border-bottom: 0;
    }

    /* BADGES STATUS ALUR (CLEAN & SUBTLE) */
    .badge-status {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 0.70rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .badge-menunggu-pengujian { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }
    .badge-sedang-diuji { background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; }
    .badge-menunggu-verifikasi { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
    .badge-menunggu-approval { background: #ede9fe; color: #6d28d9; border: 1px solid #ddd6fe; }
    .badge-approved-final { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
    .badge-ditolak { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }

    /* LEAD TIME PILL */
    .lead-time-pill {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 2px 7px;
        border-radius: 6px;
        font-size: 0.72rem;
        font-weight: 600;
        font-family: 'JetBrains Mono', Consolas, monospace;
        white-space: nowrap;
    }
    .lead-time-ontime { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
    .lead-time-late { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }

    /* BADGE KESIMPULAN MUTU */
    .badge-ms {
        background: #f0fdf4;
        color: #15803d;
        border: 1px solid #bbf7d0;
        font-weight: 700;
        font-size: 0.70rem;
        padding: 2px 6px;
        border-radius: 5px;
    }
    .badge-tms {
        background: #fef2f2;
        color: #b91c1c;
        border: 1px solid #fecaca;
        font-weight: 700;
        font-size: 0.70rem;
        padding: 2px 6px;
        border-radius: 5px;
    }

    /* DATATABLES CUSTOM CONTROLS OVERRIDE */
    #tabel-monitoring_wrapper {
        padding: 14px 18px;
    }

    #tabel-monitoring_wrapper .dataTables_filter input {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background-color: #f8fafc;
        padding: 5px 10px;
        font-size: 0.80rem;
        margin-left: 8px;
    }
    #tabel-monitoring_wrapper .dataTables_filter input:focus {
        border-color: #38bdf8;
        outline: none;
        background-color: #ffffff;
    }

    #tabel-monitoring_wrapper .dataTables_length select {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background-color: #f8fafc;
        padding: 4px 8px;
        font-size: 0.80rem;
    }

    #tabel-monitoring_wrapper .dataTables_info {
        color: #64748b;
        font-size: 0.76rem;
        padding-top: 12px;
    }

    #tabel-monitoring_wrapper .dataTables_paginate {
        padding-top: 10px;
    }

    @media (max-width: 767.98px) {
        .laporan-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 14px;
        }
        .laporan-header .d-flex.align-items-center {
            width: 100%;
            flex-wrap: wrap;
            gap: 8px !important;
        }
        .btn-export-excel, .btn-export-pdf, .btn-berkas-lhu {
            flex: 1 1 auto;
            justify-content: center;
        }
        .kpi-grid {
            grid-template-columns: 1fr;
            gap: 12px;
        }
        .analytics-row {
            grid-template-columns: 1fr;
        }
        .filter-panel {
            padding: 14px;
        }
    }
</style>

<div class="laporan-page">
    
    <!-- PAGE HEADER -->
    <div class="laporan-header">
        <div>
            <h4 class="laporan-title">
                <i class="bi bi-bar-chart-line-fill"></i> Monitoring &amp; Laporan Eksekutif
            </h4>
            <p class="laporan-desc">Evaluasi kinerja pengujian, kepatuhan Turn Around Time (TAT/SLA), dan rekapitulasi pelaporan manajerial.</p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <?php $query_string = http_build_query($filter); ?>
            <a href="<?php echo site_url('laporan/export_excel?' . $query_string); ?>" class="btn-export-excel" title="Unduh rekapitulasi lengkap spreadsheet CSV / Excel">
                <i class="bi bi-file-earmark-excel"></i> Ekspor Excel
            </a>
            <a href="<?php echo site_url('laporan/export_pdf?' . $query_string); ?>" target="_blank" class="btn-export-pdf" title="Cetak laporan rekapitulasi resmi A4">
                <i class="bi bi-file-earmark-pdf"></i> Cetak Rekap PDF
            </a>
            <a href="<?php echo site_url('pengujian/riwayat'); ?>" class="btn-berkas-lhu" title="Buka Workspace Berkas &amp; Cetak LHU Per Sampel">
                <i class="bi bi-folder2-open"></i> Berkas LHU
            </a>
        </div>
    </div>

    <!-- KPI STATISTIC CARDS -->
    <div class="kpi-grid">
        <div class="kpi-card">
            <span class="kpi-label">Volume Sampel Terdata</span>
            <div class="kpi-value"><?php echo number_format($statistik['total_sampel']); ?></div>
            <div class="kpi-sub">Rentang filter terpilih</div>
        </div>
        <div class="kpi-card kpi-info">
            <span class="kpi-label">Beban Kerja Berjalan</span>
            <div class="kpi-value text-primary"><?php echo number_format($statistik['antrean_pengujian'] + $statistik['sedang_diuji']); ?></div>
            <div class="kpi-sub"><?php echo $statistik['antrean_pengujian']; ?> Antrean &bull; <?php echo $statistik['sedang_diuji']; ?> Sedang Diuji</div>
        </div>
        <div class="kpi-card kpi-warning">
            <span class="kpi-label">Menunggu Review</span>
            <div class="kpi-value text-warning"><?php echo number_format($statistik['menunggu_verifikasi'] + $statistik['menunggu_approval']); ?></div>
            <div class="kpi-sub"><?php echo $statistik['menunggu_verifikasi']; ?> Verifikasi &bull; <?php echo $statistik['menunggu_approval']; ?> Approval MT</div>
        </div>
        <div class="kpi-card kpi-success">
            <span class="kpi-label">Pengujian Selesai (Final)</span>
            <div class="kpi-value text-success"><?php echo number_format($statistik['selesai_final']); ?></div>
            <div class="kpi-sub">Dokumen LHU Terbit Resmi</div>
        </div>
        <div class="kpi-card <?php echo ($statistik['melewati_sla'] > 0) ? 'kpi-danger' : ''; ?>">
            <span class="kpi-label">Rata-rata Lead Time (TAT)</span>
            <div class="kpi-value <?php echo ($statistik['melewati_sla'] > 0) ? 'text-danger' : 'text-dark'; ?>">
                <?php echo $statistik['rata_tat_hari']; ?> <span style="font-size: 0.85rem; font-weight: 600; color: #7f92a0;">Hari</span>
            </div>
            <div class="kpi-sub">
                <?php if ($statistik['melewati_sla'] > 0): ?>
                    <span class="text-danger fw-bold"><i class="bi bi-exclamation-triangle-fill"></i> <?php echo $statistik['melewati_sla']; ?> Melewati Batas SLA</span>
                <?php else: ?>
                    <span class="text-success fw-semibold"><i class="bi bi-check-circle-fill"></i> Efektif (&le; 3 hari kerja)</span>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- FILTER PANEL INTERAKTIF -->
    <div class="filter-panel">
        <?php if (!empty($is_penyelia_locked)): ?>
            <div class="alert alert-info py-2 px-3 mb-3 d-flex align-items-center justify-content-between rounded-3 border-0 bg-opacity-10 bg-primary text-primary" style="font-size: 0.8rem;">
                <div>
                    <i class="bi bi-shield-check me-2 fs-6"></i>
                    <strong>Mode Penyelia Terpadu:</strong> Data monitoring dibatasi khusus pada komoditi wewenang Anda: 
                    <?php 
                        $kom_text = array_map(function($k) {
                            if ($k === 'obat') return '💊 Laboratorium Obat';
                            if ($k === 'pangan') return '🍲 Laboratorium Pangan';
                            if ($k === 'herbal') return '🌿 Laboratorium Herbal';
                            if ($k === 'kosmetik') return '💄 Laboratorium Kosmetik';
                            return ucfirst($k);
                        }, $komoditi_penyelia ?? array('obat'));
                        echo implode(', ', $kom_text);
                    ?>.
                </div>
                <span class="badge bg-primary text-white" style="font-size: 0.68rem;">Akses Wewenang Penyelia</span>
            </div>
        <?php endif; ?>

        <form method="GET" action="<?php echo site_url('laporan'); ?>">
            <div class="filter-grid">
                <?php if (empty($is_penyelia_locked)): ?>
                    <div>
                        <label class="filter-label"><i class="bi bi-grid-fill me-1"></i> Komoditi</label>
                        <select name="komoditi" class="filter-input">
                            <option value="">-- Semua Komoditi --</option>
                            <option value="obat" <?php echo ($filter['komoditi'] === 'obat') ? 'selected' : ''; ?>>💊 Obat</option>
                            <option value="pangan" <?php echo ($filter['komoditi'] === 'pangan') ? 'selected' : ''; ?>>🍲 Pangan</option>
                            <option value="herbal" <?php echo ($filter['komoditi'] === 'herbal') ? 'selected' : ''; ?>>🌿 Herbal</option>
                            <option value="kosmetik" <?php echo ($filter['komoditi'] === 'kosmetik') ? 'selected' : ''; ?>>💄 Kosmetik</option>
                        </select>
                    </div>
                <?php endif; ?>
                <div>
                    <label class="filter-label"><i class="bi bi-calendar-event me-1"></i> Tanggal Mulai</label>
                    <input type="date" name="tgl_mulai" class="filter-input" value="<?php echo html_escape($filter['tgl_mulai']); ?>">
                </div>
                <div>
                    <label class="filter-label"><i class="bi bi-calendar-check me-1"></i> Tanggal Selesai</label>
                    <input type="date" name="tgl_selesai" class="filter-input" value="<?php echo html_escape($filter['tgl_selesai']); ?>">
                </div>
                <div>
                    <label class="filter-label"><i class="bi bi-flag me-1"></i> Status Alur</label>
                    <select name="status" class="filter-input">
                        <option value="">-- Semua Status --</option>
                        <option value="Menunggu Pengujian" <?php echo ($filter['status'] === 'Menunggu Pengujian') ? 'selected' : ''; ?>>Menunggu Pengujian</option>
                        <option value="Sedang Diuji" <?php echo ($filter['status'] === 'Sedang Diuji') ? 'selected' : ''; ?>>Sedang Diuji</option>
                        <option value="Menunggu Verifikasi" <?php echo ($filter['status'] === 'Menunggu Verifikasi') ? 'selected' : ''; ?>>Menunggu Verifikasi</option>
                        <option value="Menunggu Approval" <?php echo ($filter['status'] === 'Menunggu Approval') ? 'selected' : ''; ?>>Menunggu Approval</option>
                        <option value="Approved / Final" <?php echo ($filter['status'] === 'Approved / Final') ? 'selected' : ''; ?>>Approved / Final</option>
                        <option value="Ditolak" <?php echo ($filter['status'] === 'Ditolak') ? 'selected' : ''; ?>>Ditolak</option>
                    </select>
                </div>
                <div>
                    <label class="filter-label"><i class="bi bi-diagram-2 me-1"></i> Bidang Lab</label>
                    <select name="jenis_pengujian" class="filter-input">
                        <option value="">-- Semua Lab --</option>
                        <option value="Kimia" <?php echo ($filter['jenis_pengujian'] === 'Kimia') ? 'selected' : ''; ?>>Kimia</option>
                        <option value="Mikrobiologi" <?php echo ($filter['jenis_pengujian'] === 'Mikrobiologi') ? 'selected' : ''; ?>>Mikrobiologi</option>
                    </select>
                </div>
                <div>
                    <label class="filter-label"><i class="bi bi-person-gear me-1"></i> Analis / Penguji</label>
                    <select name="penguji_id" class="filter-input">
                        <option value="">-- Semua Analis --</option>
                        <?php foreach ($daftar_analis as $analis): ?>
                            <option value="<?php echo $analis['id']; ?>" <?php echo ((string)$filter['penguji_id'] === (string)$analis['id']) ? 'selected' : ''; ?>>
                                <?php echo html_escape($analis['nama_lengkap']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="filter-label"><i class="bi bi-search me-1"></i> Kata Kunci</label>
                    <input type="text" name="kata_kunci" class="filter-input" placeholder="No SIPT / Bets / Nama..." value="<?php echo html_escape($filter['kata_kunci']); ?>">
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-filter-apply flex-grow-1">
                        <i class="bi bi-funnel-fill me-1"></i> Terapkan
                    </button>
                    <a href="<?php echo site_url('laporan'); ?>" class="btn-filter-reset" title="Reset Filter">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- CHARTS ROW (DISTRIBUSI STATUS & BEBAN LAB) -->
    <div class="analytics-row">
        <!-- Chart 1: Donut Status Pengujian -->
        <div class="chart-card">
            <div class="chart-header">
                <h5 class="chart-title"><i class="bi bi-pie-chart-fill text-primary"></i> Proporsi Status Pengujian</h5>
                <span class="badge bg-light text-muted border">Distribusi Siklus</span>
            </div>
            <div style="height: 230px; position: relative;">
                <canvas id="chartStatusPengujian"></canvas>
            </div>
        </div>

        <!-- Chart 2: Stacked Bar Bidang Lab -->
        <div class="chart-card">
            <div class="chart-header">
                <h5 class="chart-title"><i class="bi bi-bar-chart-fill text-success"></i> Beban Kerja Antar Bidang Lab</h5>
                <span class="badge bg-light text-muted border">Kimia vs Mikro</span>
            </div>
            <div style="height: 230px; position: relative;">
                <canvas id="chartBidangLab"></canvas>
            </div>
        </div>
    </div>

    <!-- REKAPITULASI KINERJA ANALIS -->
    <?php if (!empty($kinerja_analis)): ?>
    <div class="table-card">
        <div class="table-card-header">
            <h5 class="table-card-title">
                <i class="bi bi-people-fill text-info"></i> Rekapitulasi Produktivitas &amp; Beban Kerja Analis
            </h5>
            <span class="small text-muted">Evaluasi Beban &amp; Kecepatan Pengujian Personel</span>
        </div>
        <div class="table-responsive">
            <table class="table mb-0 table-limsku-matrix">
                <thead>
                    <tr>
                        <th>Nama Analis / Penguji</th>
                        <th class="text-center">Total Penugasan</th>
                        <th class="text-center">Tugas Berjalan</th>
                        <th class="text-center">Tugas Selesai (Final)</th>
                        <th class="text-center">Rata-rata Lead Time</th>
                        <th class="text-center">Penyelesaian (Completion)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($kinerja_analis as $ka): 
                        $rate = ($ka['total_tugas'] > 0) ? round(($ka['tugas_selesai'] / $ka['total_tugas']) * 100) : 0;
                    ?>
                    <tr>
                        <td class="fw-bold text-dark">
                            <i class="bi bi-person-circle me-2 text-secondary"></i> <?php echo html_escape($ka['nama_lengkap']); ?>
                        </td>
                        <td class="text-center fw-bold text-dark"><?php echo $ka['total_tugas']; ?> sesi</td>
                        <td class="text-center text-warning fw-bold"><?php echo $ka['tugas_aktif']; ?> sesi</td>
                        <td class="text-center text-success fw-bold"><?php echo $ka['tugas_selesai']; ?> sesi</td>
                        <td class="text-center">
                            <?php if ($ka['rata_tat_analis'] !== null): ?>
                                <span class="lead-time-pill <?php echo ($ka['rata_tat_analis'] > 3) ? 'lead-time-late' : 'lead-time-ontime'; ?>">
                                    <i class="bi bi-clock"></i> <?php echo $ka['rata_tat_analis']; ?> hari
                                </span>
                            <?php else: ?>
                                <span class="text-muted small">-</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center" style="width: 190px;">
                            <div class="d-flex align-items-center gap-2">
                                <div class="progress flex-grow-1" style="height: 7px; border-radius: 4px; background-color: #e9ecef;">
                                    <div class="progress-bar bg-success" style="width: <?php echo $rate; ?>%;"></div>
                                </div>
                                <span class="small fw-bold text-dark"><?php echo $rate; ?>%</span>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

    <!-- DATA TABLE MATRIKS MONITORING SAMPEL -->
    <div class="table-card">
        <div class="table-card-header">
            <div>
                <h5 class="table-card-title">
                    <i class="bi bi-table text-primary"></i> Matriks Rekapitulasi Sampel Laboratorium
                </h5>
                <small class="text-muted">Data agregasi operasional sampel masuk hingga penetapan hasil LHU</small>
            </div>
            <div class="small text-muted">
                <i class="bi bi-info-circle me-1"></i> Data diperbarui secara real-time
            </div>
        </div>
        <div class="table-responsive">
            <table class="table mb-0 table-limsku-matrix" id="tabel-monitoring">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 45px;">No</th>
                        <th>No. SIPT / Identitas</th>
                        <th>Kode Sampel</th>
                        <th>Nama Produk &amp; Bets</th>
                        <th class="text-center">Bidang</th>
                        <th>Tgl Registrasi</th>
                        <th>Penguji</th>
                        <th>Penyelia</th>
                        <th class="text-center">Status Alur</th>
                        <th class="text-center">Mutu</th>
                        <th class="text-center">Lead Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($daftar_rekap)): ?>
                        <tr>
                            <td colspan="11" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary opacity-50"></i>
                                Tidak ada data pengujian yang sesuai dengan kriteria filter.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($daftar_rekap as $row): 
                            $badge_class = 'badge-menunggu-pengujian';
                            if ($row['status_sampel'] === 'Sedang Diuji') $badge_class = 'badge-sedang-diuji';
                            elseif ($row['status_sampel'] === 'Menunggu Verifikasi') $badge_class = 'badge-menunggu-verifikasi';
                            elseif ($row['status_sampel'] === 'Menunggu Approval') $badge_class = 'badge-menunggu-approval';
                            elseif ($row['status_sampel'] === 'Approved / Final') $badge_class = 'badge-approved-final';
                            elseif ($row['status_sampel'] === 'Ditolak') $badge_class = 'badge-ditolak';

                            $kode_sampel = !empty($row['kode_sampel_manual']) ? $row['kode_sampel_manual'] : ('SMP-' . str_pad($row['sample_id'], 5, '0', STR_PAD_LEFT));
                            $no_sipt = !empty($row['no_sipt']) ? $row['no_sipt'] : ('# ' . $row['no_sampel']);
                        ?>
                            <tr>
                                <td class="text-center text-muted fw-bold"><?php echo $no++; ?></td>
                                <td>
                                    <span class="fw-bold text-dark font-monospace"><?php echo html_escape($no_sipt); ?></span>
                                    <?php if (!empty($row['nama_sarana'])): ?>
                                        <small class="d-block text-muted text-truncate" style="max-width: 180px; font-size: 0.68rem;">
                                            <i class="bi bi-building me-1"></i><?php echo html_escape($row['nama_sarana']); ?>
                                        </small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="font-monospace text-primary fw-bold"><?php echo html_escape($kode_sampel); ?></span>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark" style="max-width: 230px; line-height: 1.35;"><?php echo html_escape($row['nama_sampel']); ?></div>
                                    <small class="text-muted" style="font-size: 0.69rem;">
                                        Bets: <span class="fw-semibold text-dark"><?php echo html_escape($row['no_bets'] ?: '-'); ?></span> &bull; <?php echo html_escape($row['sub_kategori'] ?: ($row['kemasan'] ?: '-')); ?>
                                    </small>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark border px-2 py-1">
                                        <?php echo html_escape($row['jenis_pengujian'] ?: 'Kimia'); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="text-nowrap"><?php echo !empty($row['created_at']) ? date('d/m/Y', strtotime($row['created_at'])) : '-'; ?></span>
                                </td>
                                <td>
                                    <span class="text-dark fw-semibold"><?php echo html_escape($row['nama_penguji'] ?: '-'); ?></span>
                                </td>
                                <td>
                                    <span class="text-dark fw-semibold"><?php echo html_escape($row['nama_verifier'] ?: '-'); ?></span>
                                </td>
                                <td class="text-center">
                                    <span class="badge-status <?php echo $badge_class; ?>">
                                        <?php echo html_escape($row['status_sampel']); ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <?php if (!empty($row['kesimpulan_hasil'])): ?>
                                        <?php if (stripos($row['kesimpulan_hasil'], 'MS') !== false || stripos($row['kesimpulan_hasil'], 'Memenuhi') !== false): ?>
                                            <span class="badge-ms">MS</span>
                                        <?php elseif (stripos($row['kesimpulan_hasil'], 'TMS') !== false): ?>
                                            <span class="badge-tms">TMS</span>
                                        <?php else: ?>
                                            <span class="badge bg-light text-muted border small"><?php echo html_escape(substr($row['kesimpulan_hasil'], 0, 15)); ?></span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-muted small">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($row['lead_time_hari'] !== null): ?>
                                        <span class="lead-time-pill <?php echo ($row['lead_time_hari'] > 3 && $row['status_sampel'] !== 'Approved / Final') ? 'lead-time-late' : 'lead-time-ontime'; ?>" title="<?php echo ($row['lead_time_hari'] > 3 && $row['status_sampel'] !== 'Approved / Final') ? 'Melewati batas SLA 3 hari' : 'Sesuai SLA'; ?>">
                                            <i class="bi bi-clock"></i> <?php echo $row['lead_time_hari']; ?> hr
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Inisialisasi DataTable dengan tampilan bersih jika ada baris data
        if (typeof jQuery !== 'undefined' && typeof jQuery.fn.DataTable !== 'undefined') {
            var $tabel = $('#tabel-monitoring');
            // Hanya jalankan DataTable jika ada baris data nyata (bukan baris kosong colspan)
            if ($tabel.find('tbody tr td[colspan]').length === 0) {
                $tabel.DataTable({
                    pageLength: 20,
                    ordering: true,
                    language: {
                        search: "Cari di tabel:",
                        searchPlaceholder: "Ketik kata kunci...",
                        lengthMenu: "Tampilkan _MENU_ baris",
                        info: "Menampilkan _START_ - _END_ dari _TOTAL_ sampel",
                        infoEmpty: "Tidak ada data sampel",
                        zeroRecords: "Data tidak ditemukan",
                        paginate: {
                            next: '<i class="bi bi-chevron-right"></i>',
                            previous: '<i class="bi bi-chevron-left"></i>'
                        }
                    }
                });
            }
        }

        // 2. Inisialisasi Chart 1: Donut Status Pengujian
        const ctxStatus = document.getElementById('chartStatusPengujian');
        if (ctxStatus && typeof Chart !== 'undefined') {
            new Chart(ctxStatus, {
                type: 'doughnut',
                data: {
                    labels: ['Antrean', 'Sedang Diuji', 'Verifikasi', 'Approval MT', 'Selesai (Final)', 'Ditolak'],
                    datasets: [{
                        data: [
                            <?php echo $statistik['antrean_pengujian']; ?>,
                            <?php echo $statistik['sedang_diuji']; ?>,
                            <?php echo $statistik['menunggu_verifikasi']; ?>,
                            <?php echo $statistik['menunggu_approval']; ?>,
                            <?php echo $statistik['selesai_final']; ?>,
                            <?php echo $statistik['ditolak']; ?>
                        ],
                        backgroundColor: ['#95a5a6', '#3498db', '#f39c12', '#9b59b6', '#2ecc71', '#e74c3c'],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: { boxWidth: 12, font: { size: 11 } }
                        }
                    }
                }
            });
        }

        // 3. Inisialisasi Chart 2: Stacked Bar Bidang Lab
        const ctxBidang = document.getElementById('chartBidangLab');
        if (ctxBidang && typeof Chart !== 'undefined') {
            <?php
            $labels_lab = array();
            $data_selesai = array();
            $data_proses = array();
            foreach ($distribusi_bidang as $db) {
                $labels_lab[] = $db['jenis_pengujian'];
                $data_selesai[] = (int)$db['selesai'];
                $data_proses[] = (int)$db['proses'];
            }
            if (empty($labels_lab)) {
                $labels_lab = array('Kimia', 'Mikrobiologi');
                $data_selesai = array(0, 0);
                $data_proses = array(0, 0);
            }
            ?>
            new Chart(ctxBidang, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($labels_lab); ?>,
                    datasets: [
                        {
                            label: 'Selesai (Final)',
                            data: <?php echo json_encode($data_selesai); ?>,
                            backgroundColor: '#27ae60',
                            borderRadius: 4
                        },
                        {
                            label: 'Dalam Proses / Antrean',
                            data: <?php echo json_encode($data_proses); ?>,
                            backgroundColor: '#3498db',
                            borderRadius: 4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: { stacked: true, grid: { display: false } },
                        y: { stacked: true, beginAtZero: true, ticks: { precision: 0 } }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: { boxWidth: 12, font: { size: 11 } }
                        }
                    }
                }
            });
        }
    });
</script>
