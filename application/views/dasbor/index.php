<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
    /* Dashboard specific styling matching Figma & screenshot */
    .dashboard-container {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        color: #1e293b;
    }

    .dash-greeting-title {
        font-size: 1.65rem;
        font-weight: 700;
        color: #1e293b;
        margin-top: 0.25rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 0.75rem;
    }

    .dash-date-badge {
        font-size: 0.85rem;
        color: #64748b;
        font-weight: 500;
    }

    /* Base Card Style */
    .dash-card {
        background: #ffffff;
        border: 1px solid #eef2f6;
        border-radius: 14px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
        padding: 1.25rem 1.4rem;
        height: 100%;
        transition: all 0.2s ease;
    }

    .dash-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    /* Top KPI Cards */
    .kpi-icon-box {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        margin-bottom: 0.85rem;
    }

    .kpi-icon-teal {
        background-color: #e6f7f8;
        color: #0d838a;
    }

    .kpi-icon-blue {
        background-color: #e0f2fe;
        color: #0284c7;
    }

    .kpi-icon-yellow {
        background-color: #fef3c7;
        color: #d97706;
    }

    .kpi-icon-purple {
        background-color: #ede9fe;
        color: #7c3aed;
    }

    .kpi-header-row {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.5rem;
    }

    .kpi-title {
        font-size: 0.85rem;
        font-weight: 700;
        color: #334155;
        margin: 0;
    }

    .kpi-subtitle {
        font-size: 0.75rem;
        color: #94a3b8;
        margin: 0;
    }

    .kpi-number-main {
        font-size: 2.1rem;
        font-weight: 700;
        line-height: 1.1;
        color: #0f172a;
    }

    .kpi-sub-badge {
        background: #f1f5f9;
        border-radius: 8px;
        padding: 0.35rem 0.6rem;
        text-align: center;
        min-width: 60px;
    }

    .kpi-sub-badge .sub-label {
        font-size: 0.65rem;
        color: #64748b;
        display: block;
        line-height: 1.2;
    }

    .kpi-sub-badge .sub-val {
        font-size: 1.05rem;
        font-weight: 700;
        color: #1e293b;
    }

    .kpi-trend-text {
        font-size: 0.75rem;
        font-weight: 600;
        margin-top: 0.35rem;
    }

    .trend-up {
        color: #0d9488;
    }

    .trend-down {
        color: #e11d48;
    }

    .trend-neutral {
        color: #64748b;
    }

    /* Section Cards Title */
    .section-card-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .section-card-title i {
        font-size: 1.05rem;
        color: #475569;
    }

    /* Progress Rows for Status Pekerjaan */
    .workflow-progress-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
        font-size: 0.82rem;
    }

    .workflow-progress-row:last-child {
        margin-bottom: 0;
    }

    .workflow-label {
        width: 120px;
        font-weight: 500;
        color: #64748b;
        flex-shrink: 0;
    }

    .workflow-bar-wrap {
        flex: 1;
        margin: 0 1rem;
        background-color: #f1f5f9;
        height: 10px;
        border-radius: 20px;
        overflow: hidden;
    }

    .workflow-bar-fill {
        height: 100%;
        border-radius: 20px;
        transition: width 0.6s ease;
    }

    .workflow-count {
        width: 30px;
        text-align: right;
        font-weight: 600;
        color: #334155;
        flex-shrink: 0;
    }

    /* Bar colors matching screenshot */
    .bar-entry { background-color: #38bdf8; }
    .bar-pengujian { background-color: #0284c7; }
    .bar-verifikasi { background-color: #818cf8; }
    .bar-approval { background-color: #c084fc; }
    .bar-selesai { background-color: #10b981; }

    /* Action cards in Pekerjaan yang Perlu Tindakan */
    .action-badge-item {
        border-radius: 10px;
        padding: 0.75rem 1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 0.65rem;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .action-badge-item:last-child {
        margin-bottom: 0;
    }

    .action-yellow {
        background-color: #fef9c3;
        color: #713f12;
    }

    .action-blue {
        background-color: #e0f2fe;
        color: #075985;
    }

    .action-purple {
        background-color: #f3e8ff;
        color: #581c87;
    }

    .action-badge-val {
        font-size: 1.15rem;
        font-weight: 700;
    }

    /* Donut Legend */
    .donut-legend-item {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        font-size: 0.85rem;
        margin-bottom: 0.6rem;
    }

    .legend-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
    }

    .legend-dot-green { background-color: #10b981; }
    .legend-dot-orange { background-color: #f97316; }
    .legend-dot-gray { background-color: #cbd5e1; }

    .legend-label {
        color: #475569;
        min-width: 110px;
    }

    .legend-val {
        font-weight: 600;
        color: #1e293b;
    }

    /* Auto refresh indicator */
    .refresh-indicator {
        font-size: 0.75rem;
        color: #94a3b8;
        display: flex;
        align-items: center;
        gap: 0.35rem;
    }

    .pulse-dot {
        width: 8px;
        height: 8px;
        background-color: #10b981;
        border-radius: 50%;
        display: inline-block;
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }
</style>

<div class="dashboard-container">
    <!-- Greeting Section (Halaman Dashboard langsung dimulai dari Greeting & Tanggal) -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <h1 class="dash-greeting-title mb-0">
            <?php echo html_escape($greeting); ?>, <?php echo html_escape($nama_panggilan); ?>
        </h1>
        <div class="text-end d-flex align-items-center gap-3">
            <div class="dash-date-badge" id="dash-current-date">
                <?php echo html_escape($tanggal_hari_ini); ?>
            </div>
            <div class="refresh-indicator">
                <span class="pulse-dot"></span>
                <span>Live Data</span>
            </div>
        </div>
    </div>

    <!-- 4 KPI Cards Horizontal Grid -->
    <div class="row g-3 mb-4">
        <!-- Card 1: Sampel Diterima -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="dash-card">
                <div class="kpi-header-row">
                    <div class="kpi-icon-box kpi-icon-teal mb-0">
                        <i class="bi bi-inbox"></i>
                    </div>
                    <div>
                        <h4 class="kpi-title">Sampel Diterima</h4>
                        <p class="kpi-subtitle">Hari ini</p>
                    </div>
                </div>
                <div class="d-flex align-items-baseline justify-content-between mt-2">
                    <div>
                        <div class="kpi-number-main" id="kpi-sampel-hari-ini">
                            <?php echo (int) $ringkasan['kpi']['sampel_diterima']['hari_ini']; ?>
                        </div>
                        <div class="kpi-trend-text <?php echo ($ringkasan['kpi']['sampel_diterima']['tren_persen'] !== null && $ringkasan['kpi']['sampel_diterima']['tren_persen'] >= 0) ? 'trend-up' : 'trend-neutral'; ?>" id="kpi-sampel-tren">
                            <?php if ($ringkasan['kpi']['sampel_diterima']['tren_persen'] !== null): ?>
                                <i class="bi bi-arrow-up-right"></i> <?php echo ($ringkasan['kpi']['sampel_diterima']['tren_persen'] >= 0 ? '+' : '') . $ringkasan['kpi']['sampel_diterima']['tren_persen']; ?>% dari kemarin
                            <?php else: ?>
                                <span class="text-muted"><i class="bi bi-dash"></i> Data awal</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="kpi-sub-badge">
                        <span class="sub-label">Bulan Ini</span>
                        <span class="sub-val" id="kpi-sampel-bulan-ini"><?php echo (int) $ringkasan['kpi']['sampel_diterima']['bulan_ini']; ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Pengujian -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="dash-card">
                <div class="kpi-header-row">
                    <div class="kpi-icon-box kpi-icon-blue mb-0">
                        <i class="bi bi-flask"></i>
                    </div>
                    <div>
                        <h4 class="kpi-title">Pengujian</h4>
                        <p class="kpi-subtitle">Aktif &amp; selesai</p>
                    </div>
                </div>
                <div class="d-flex align-items-baseline justify-content-start gap-4 mt-2">
                    <div>
                        <div class="kpi-number-main text-primary" id="kpi-pengujian-aktif">
                            <?php echo (int) $ringkasan['kpi']['pengujian']['aktif']; ?>
                        </div>
                        <small class="text-muted fw-semibold">Aktif</small>
                    </div>
                    <div>
                        <div class="kpi-number-main text-dark" id="kpi-pengujian-selesai">
                            <?php echo (int) $ringkasan['kpi']['pengujian']['selesai']; ?>
                        </div>
                        <small class="text-muted fw-semibold">Selesai</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 3: Melewati SLA -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="dash-card">
                <div class="kpi-header-row">
                    <div class="kpi-icon-box kpi-icon-yellow mb-0">
                        <i class="bi bi-exclamation"></i>
                    </div>
                    <div>
                        <h4 class="kpi-title">Melewati SLA</h4>
                        <p class="kpi-subtitle">Batas waktu standard</p>
                    </div>
                </div>
                <div class="mt-2">
                    <div class="d-flex align-items-baseline gap-2">
                        <span class="kpi-number-main text-warning" id="kpi-melewati-sla">
                            <?php echo (int) $ringkasan['kpi']['melewati_sla']['jumlah']; ?>
                        </span>
                        <span class="text-muted small">pekerjaan</span>
                    </div>
                    <div class="kpi-trend-text trend-neutral" id="kpi-sla-subtext">
                        <span class="text-muted"><i class="bi bi-info-circle"></i> Target max 3 hari</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 4: Perlu Tindakan -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="dash-card">
                <div class="kpi-header-row">
                    <div class="kpi-icon-box kpi-icon-purple mb-0">
                        <i class="bi bi-check"></i>
                    </div>
                    <div>
                        <h4 class="kpi-title">Perlu Tindakan</h4>
                        <p class="kpi-subtitle">Antrean aksi aktif</p>
                    </div>
                </div>
                <div class="mt-2">
                    <div class="d-flex align-items-baseline gap-2">
                        <span class="kpi-number-main" style="color: #7c3aed;" id="kpi-perlu-tindakan">
                            <?php echo (int) $ringkasan['kpi']['perlu_tindakan']['jumlah']; ?>
                        </span>
                        <span class="text-muted small">pekerjaan</span>
                    </div>
                    <div class="kpi-trend-text" style="color: #7c3aed;" id="kpi-tindakan-subtext">
                        <i class="bi bi-list-task"></i> Menunggu proses
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 2: Status Pekerjaan, Rata-rata Waktu, & Pekerjaan yang Perlu Tindakan -->
    <div class="row g-3 mb-4">
        <!-- Status Pekerjaan (5 Progress Bars) -->
        <div class="col-12 col-lg-6">
            <div class="dash-card">
                <h3 class="section-card-title">
                    <i class="bi bi-list-nested"></i> Status Pekerjaan
                </h3>

                <!-- 1. Entry -->
                <div class="workflow-progress-row">
                    <div class="workflow-label">Entry</div>
                    <div class="workflow-bar-wrap">
                        <div class="workflow-bar-fill bar-entry" id="bar-entry" style="width: <?php echo (int) $ringkasan['status_pekerjaan']['progress']['entry']; ?>%;"></div>
                    </div>
                    <div class="workflow-count" id="count-entry"><?php echo (int) $ringkasan['status_pekerjaan']['counts']['entry']; ?></div>
                </div>

                <!-- 2. Tahap Pengujian -->
                <div class="workflow-progress-row">
                    <div class="workflow-label">Tahap Pengujian</div>
                    <div class="workflow-bar-wrap">
                        <div class="workflow-bar-fill bar-pengujian" id="bar-pengujian" style="width: <?php echo (int) $ringkasan['status_pekerjaan']['progress']['tahap_pengujian']; ?>%;"></div>
                    </div>
                    <div class="workflow-count" id="count-pengujian"><?php echo (int) $ringkasan['status_pekerjaan']['counts']['tahap_pengujian']; ?></div>
                </div>

                <!-- 3. Verifikasi -->
                <div class="workflow-progress-row">
                    <div class="workflow-label">Verifikasi</div>
                    <div class="workflow-bar-wrap">
                        <div class="workflow-bar-fill bar-verifikasi" id="bar-verifikasi" style="width: <?php echo (int) $ringkasan['status_pekerjaan']['progress']['verifikasi']; ?>%;"></div>
                    </div>
                    <div class="workflow-count" id="count-verifikasi"><?php echo (int) $ringkasan['status_pekerjaan']['counts']['verifikasi']; ?></div>
                </div>

                <!-- 4. Approval -->
                <div class="workflow-progress-row">
                    <div class="workflow-label">Approval</div>
                    <div class="workflow-bar-wrap">
                        <div class="workflow-bar-fill bar-approval" id="bar-approval" style="width: <?php echo (int) $ringkasan['status_pekerjaan']['progress']['approval']; ?>%;"></div>
                    </div>
                    <div class="workflow-count" id="count-approval"><?php echo (int) $ringkasan['status_pekerjaan']['counts']['approval']; ?></div>
                </div>

                <!-- 5. Selesai -->
                <div class="workflow-progress-row">
                    <div class="workflow-label">Selesai</div>
                    <div class="workflow-bar-wrap">
                        <div class="workflow-bar-fill bar-selesai" id="bar-selesai" style="width: <?php echo (int) $ringkasan['status_pekerjaan']['progress']['selesai']; ?>%;"></div>
                    </div>
                    <div class="workflow-count" id="count-selesai"><?php echo (int) $ringkasan['status_pekerjaan']['counts']['selesai']; ?></div>
                </div>
            </div>
        </div>

        <!-- Rata-rata Waktu Penyelesaian -->
        <div class="col-12 col-md-6 col-lg-3">
            <div class="dash-card d-flex flex-column justify-content-between">
                <div>
                    <h3 class="section-card-title">
                        <i class="bi bi-clock"></i> Rata-rata Waktu Penyelesaian
                    </h3>
                    <div class="d-flex align-items-baseline gap-2 mt-3">
                        <span class="kpi-number-main" id="val-rata-waktu">
                            <?php echo ($ringkasan['rata_waktu']['hari'] !== null) ? html_escape(str_replace('.', ',', $ringkasan['rata_waktu']['hari'])) : '0'; ?>
                        </span>
                        <span class="text-muted fw-semibold">hari</span>
                    </div>
                    <div class="kpi-trend-text trend-up mt-1">
                        <i class="bi bi-arrow-down-right"></i> <?php echo ($ringkasan['rata_waktu']['hari'] !== null) ? 'Sesuai standar SLA' : 'Belum ada data final'; ?>
                    </div>
                </div>

                <div class="pt-3 border-top mt-3">
                    <small class="text-muted d-block" style="font-size: 0.72rem;">Target SLA</small>
                    <span class="fw-bold text-dark fs-6"><?php echo (int) $ringkasan['rata_waktu']['target_sla']; ?> hari</span>
                </div>
            </div>
        </div>

        <!-- Pekerjaan yang Perlu Tindakan (3 Triage Badges) -->
        <div class="col-12 col-md-6 col-lg-3">
            <div class="dash-card">
                <h3 class="section-card-title mb-3">
                    Pekerjaan yang Perlu Tindakan
                </h3>

                <!-- Pengujian -->
                <div class="action-badge-item action-yellow">
                    <span>Pengujian</span>
                    <span class="action-badge-val" id="tindakan-pengujian"><?php echo (int) $ringkasan['pekerjaan_tindakan']['pengujian']; ?></span>
                </div>

                <!-- Verifikasi -->
                <div class="action-badge-item action-blue">
                    <span>Verifikasi</span>
                    <span class="action-badge-val" id="tindakan-verifikasi"><?php echo (int) $ringkasan['pekerjaan_tindakan']['verifikasi']; ?></span>
                </div>

                <!-- Approval -->
                <div class="action-badge-item action-purple">
                    <span>Approval</span>
                    <span class="action-badge-val" id="tindakan-approval"><?php echo (int) $ringkasan['pekerjaan_tindakan']['approval']; ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 3: Trend Line Chart -->
<div class="row g-3 mb-4">
        <!-- Line Chart: Tren Sampel & Pengujian -->
    <div class="col-12">
            <div class="dash-card">
                <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
                    <h3 class="section-card-title mb-0">
                        <i class="bi bi-graph-up-arrow"></i> Tren Sampel &amp; Pengujian
                    </h3>
                    <div class="d-flex align-items-center gap-3" style="font-size: 0.78rem;">
                        <span class="d-flex align-items-center gap-1">
                            <span class="legend-dot legend-dot-green"></span> Sampel diterima
                        </span>
                        <span class="d-flex align-items-center gap-1">
                            <span class="legend-dot" style="background-color: #0284c7;"></span> Pengujian dilakukan
                        </span>
                    </div>
                </div>

                <!-- Canvas Chart.js -->
                <div style="height: 240px; position: relative;">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Row 4: Distribusi Hasil Pengujian (Donut Chart) -->
    <div class="row g-3">
        <div class="col-12">
            <div class="dash-card">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h3 class="section-card-title mb-0">
                        <i class="bi bi-pie-chart"></i> Distribusi Hasil Pengujian
                    </h3>
                    <a href="<?php echo site_url('pengujian/riwayat'); ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1" style="font-size: 0.8rem;">
                        Lihat detail &rarr;
                    </a>
                </div>

                <div class="row align-items-center">
                    <!-- Donut Canvas -->
                    <div class="col-12 col-md-5 col-lg-4 text-center">
                        <div style="height: 190px; max-width: 190px; margin: 0 auto; position: relative;">
                            <canvas id="donutChart"></canvas>
                            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center; pointer-events: none;">
                                <div class="fw-bold fs-4 text-dark" id="donut-center-pct"><?php echo (int) $ringkasan['distribusi']['sesuai']['percent']; ?>%</div>
                                <div class="text-muted" style="font-size: 0.72rem;">Sesuai</div>
                            </div>
                        </div>
                    </div>

                    <!-- Donut Legend & Stats -->
                    <div class="col-12 col-md-7 col-lg-8 ps-md-4 mt-3 mt-md-0">
                        <!-- Sesuai -->
                        <div class="donut-legend-item">
                            <span class="legend-dot legend-dot-green"></span>
                            <span class="legend-label">Sesuai</span>
                            <span class="legend-val" id="legend-sesuai"><?php echo (int) $ringkasan['distribusi']['sesuai']['percent']; ?>% (<?php echo (int) $ringkasan['distribusi']['sesuai']['count']; ?>)</span>
                        </div>

                        <!-- Tidak Sesuai -->
                        <div class="donut-legend-item">
                            <span class="legend-dot legend-dot-orange"></span>
                            <span class="legend-label">Tidak Sesuai</span>
                            <span class="legend-val" id="legend-tidak-sesuai"><?php echo (int) $ringkasan['distribusi']['tidak_sesuai']['percent']; ?>% (<?php echo (int) $ringkasan['distribusi']['tidak_sesuai']['count']; ?>)</span>
                        </div>

                        <!-- Belum Final -->
                        <div class="donut-legend-item">
                            <span class="legend-dot legend-dot-gray"></span>
                            <span class="legend-label">Belum Final</span>
                            <span class="legend-val" id="legend-belum-final"><?php echo (int) $ringkasan['distribusi']['belum_final']['percent']; ?>% (<?php echo (int) $ringkasan['distribusi']['belum_final']['count']; ?>)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Script Inisialisasi Charts & AJAX Polling -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initial data from server backend
    const initialTrendLabels  = <?php echo json_encode($ringkasan['trend']['labels']); ?>;
    const initialTrendSamples = <?php echo json_encode($ringkasan['trend']['samples']); ?>;
    const initialTrendTests   = <?php echo json_encode($ringkasan['trend']['tests']); ?>;

    const initialDistSesuai      = <?php echo (int) $ringkasan['distribusi']['sesuai']['count']; ?>;
    const initialDistTidakSesuai = <?php echo (int) $ringkasan['distribusi']['tidak_sesuai']['count']; ?>;
    const initialDistBelumFinal  = <?php echo (int) $ringkasan['distribusi']['belum_final']['count']; ?>;

    // 1. Line Chart: Tren Sampel & Pengujian
    const ctxTrend = document.getElementById('trendChart').getContext('2d');
    const trendChart = new Chart(ctxTrend, {
        type: 'line',
        data: {
            labels: initialTrendLabels,
            datasets: [
                {
                    label: 'Sampel diterima',
                    data: initialTrendSamples,
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.05)',
                    borderWidth: 2.2,
                    tension: 0.35,
                    pointRadius: 0,
                    pointHoverRadius: 4,
                    fill: true
                },
                {
                    label: 'Pengujian dilakukan',
                    data: initialTrendTests,
                    borderColor: '#0284c7',
                    backgroundColor: 'transparent',
                    borderWidth: 2.2,
                    tension: 0.35,
                    pointRadius: 0,
                    pointHoverRadius: 4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0f172a',
                    padding: 8,
                    titleFont: { size: 12, family: 'Inter' },
                    bodyFont: { size: 11, family: 'Inter' }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: {
                        color: '#94a3b8',
                        font: { size: 10, family: 'Inter' },
                        maxTicksLimit: 7
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: '#f1f5f9' },
                    ticks: {
                        color: '#94a3b8',
                        font: { size: 10, family: 'Inter' },
                        stepSize: 5,
                        precision: 0
                    }
                }
            }
        }
    });

    // 2. Donut Chart: Distribusi Hasil Pengujian
    const ctxDonut = document.getElementById('donutChart').getContext('2d');
    const donutDataTotal = initialDistSesuai + initialDistTidakSesuai + initialDistBelumFinal;
    
    // Default zero state visualization jika total data masih 0
    const donutDataset = (donutDataTotal === 0) 
        ? [0, 0, 1] 
        : [initialDistSesuai, initialDistTidakSesuai, initialDistBelumFinal];

    const donutChart = new Chart(ctxDonut, {
        type: 'doughnut',
        data: {
            labels: ['Sesuai', 'Tidak Sesuai', 'Belum Final'],
            datasets: [{
                data: donutDataset,
                backgroundColor: ['#10b981', '#f97316', '#cbd5e1'],
                borderWidth: 0,
                cutout: '72%'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    enabled: donutDataTotal > 0,
                    backgroundColor: '#0f172a',
                    padding: 8
                }
            }
        }
    });

    // 3. AJAX Polling Auto-Refresh (setiap 30 detik secara asynchronous)
    const refreshIntervalMs = 30000;
    const apiUrl = '<?php echo site_url("dasbor/api_ringkasan"); ?>';

    function fetchDashboardSummary() {
        $.ajax({
            url: apiUrl,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response && response.status === 'success' && response.data) {
                    const d = response.data;

                    // Update KPI Cards
                    $('#kpi-sampel-hari-ini').text(d.kpi.sampel_diterima.hari_ini);
                    $('#kpi-sampel-bulan-ini').text(d.kpi.sampel_diterima.bulan_ini);
                    
                    if (d.kpi.sampel_diterima.tren_persen !== null) {
                        const sign = d.kpi.sampel_diterima.tren_persen >= 0 ? '+' : '';
                        $('#kpi-sampel-tren').html('<i class="bi bi-arrow-up-right"></i> ' + sign + d.kpi.sampel_diterima.tren_persen + '% dari kemarin');
                    }

                    $('#kpi-pengujian-aktif').text(d.kpi.pengujian.aktif);
                    $('#kpi-pengujian-selesai').text(d.kpi.pengujian.selesai);
                    $('#kpi-melewati-sla').text(d.kpi.melewati_sla.jumlah);
                    $('#kpi-perlu-tindakan').text(d.kpi.perlu_tindakan.jumlah);

                    // Update Status Pekerjaan
                    $('#count-entry').text(d.status_pekerjaan.counts.entry);
                    $('#bar-entry').css('width', d.status_pekerjaan.progress.entry + '%');

                    $('#count-pengujian').text(d.status_pekerjaan.counts.tahap_pengujian);
                    $('#bar-pengujian').css('width', d.status_pekerjaan.progress.tahap_pengujian + '%');

                    $('#count-verifikasi').text(d.status_pekerjaan.counts.verifikasi);
                    $('#bar-verifikasi').css('width', d.status_pekerjaan.progress.verifikasi + '%');

                    $('#count-approval').text(d.status_pekerjaan.counts.approval);
                    $('#bar-approval').css('width', d.status_pekerjaan.progress.approval + '%');

                    $('#count-selesai').text(d.status_pekerjaan.counts.selesai);
                    $('#bar-selesai').css('width', d.status_pekerjaan.progress.selesai + '%');

                    // Update Rata-rata Waktu
                    const avgDayStr = (d.rata_waktu.hari !== null) ? String(d.rata_waktu.hari).replace('.', ',') : '0';
                    $('#val-rata-waktu').text(avgDayStr);

                    // Update Tindakan Triage
                    $('#tindakan-pengujian').text(d.pekerjaan_tindakan.pengujian);
                    $('#tindakan-verifikasi').text(d.pekerjaan_tindakan.verifikasi);
                    $('#tindakan-approval').text(d.pekerjaan_tindakan.approval);

                    // Update Line Chart
                    trendChart.data.labels = d.trend.labels;
                    trendChart.data.datasets[0].data = d.trend.samples;
                    trendChart.data.datasets[1].data = d.trend.tests;
                    trendChart.update();

                    // Update Donut Chart
                    const totalDist = d.distribusi.total;
                    $('#donut-center-pct').text(d.distribusi.sesuai.percent + '%');
                    $('#legend-sesuai').text(d.distribusi.sesuai.percent + '% (' + d.distribusi.sesuai.count + ')');
                    $('#legend-tidak-sesuai').text(d.distribusi.tidak_sesuai.percent + '% (' + d.distribusi.tidak_sesuai.count + ')');
                    $('#legend-belum-final').text(d.distribusi.belum_final.percent + '% (' + d.distribusi.belum_final.count + ')');

                    if (totalDist === 0) {
                        donutChart.data.datasets[0].data = [0, 0, 1];
                    } else {
                        donutChart.data.datasets[0].data = [
                            d.distribusi.sesuai.count,
                            d.distribusi.tidak_sesuai.count,
                            d.distribusi.belum_final.count
                        ];
                    }
                    donutChart.update();
                }
            },
            error: function() {
                // Silently maintain last data state on connection glitch without disrupting the UI
                console.warn('LIMSKU Dashboard: Refresh glitch, retaining latest state.');
            }
        });
    }

    // Jalankan timer polling berkala
    setInterval(fetchDashboardSummary, refreshIntervalMs);
});
</script>
