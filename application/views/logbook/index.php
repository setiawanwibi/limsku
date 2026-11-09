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
    .avatar-user-badge {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.82rem;
        flex-shrink: 0;
        background: #e0e7ff;
        color: #4338ca;
        border: 1px solid #c7d2fe;
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
    .index-pill {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background: #f1f5f9;
        color: #64748b;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.76rem;
        font-weight: 700;
    }
</style>

<div class="logbook-page">

    <!-- Flash Messages -->
    <?php if ($this->session->flashdata('pesan_sukses')): ?>
        <div class="alert alert-success alert-dismissible fade show mb-3 shadow-sm border-0" role="alert">
            <i class="bi bi-check-circle-fill me-2 fs-5 align-middle"></i>
            <span><?php echo html_escape($this->session->flashdata('pesan_sukses')); ?></span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('pesan_gagal')): ?>
        <div class="alert alert-danger alert-dismissible fade show mb-3 shadow-sm border-0" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2 fs-5 align-middle"></i>
            <span><?php echo html_escape($this->session->flashdata('pesan_gagal')); ?></span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Tombol Aksi Cepat Header Bersih -->
    <div class="d-flex justify-content-end mb-3">
        <a href="<?php echo site_url('logbook/cetak' . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '')); ?>" 
           target="_blank" 
           class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 shadow-2xs d-inline-flex align-items-center gap-2 fw-semibold" style="font-size: 0.8rem;">
            <i class="bi bi-printer"></i>
            <span>Cetak / Ekspor Logbook</span>
        </a>
    </div>

    <!-- Kartu Statistik Penggunaan -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-lg-3">
            <div class="stat-card-custom d-flex align-items-center gap-3">
                <div class="stat-icon-wrap bg-primary-subtle text-primary">
                    <i class="bi bi-journal-check"></i>
                </div>
                <div>
                    <div class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.5px; font-size: 0.72rem;">Total Riwayat Log</div>
                    <h4 class="fw-bold mb-0 text-dark"><?php echo number_format($statistik['total_log']); ?></h4>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="stat-card-custom d-flex align-items-center gap-3">
                <div class="stat-icon-wrap bg-success-subtle text-success">
                    <i class="bi bi-tools"></i>
                </div>
                <div>
                    <div class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.5px; font-size: 0.72rem;">Alat Aktif Terpakai</div>
                    <h4 class="fw-bold mb-0 text-dark"><?php echo number_format($statistik['total_alat_terpakai']); ?> <small class="text-muted fs-6 fw-normal">/ <?php echo $statistik['total_master_alat']; ?></small></h4>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="stat-card-custom d-flex align-items-center gap-3">
                <div class="stat-icon-wrap bg-info-subtle text-info-emphasis">
                    <i class="bi bi-box-seam-fill"></i>
                </div>
                <div>
                    <div class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.5px; font-size: 0.72rem;">Sampel Diuji</div>
                    <h4 class="fw-bold mb-0 text-dark"><?php echo number_format($statistik['total_sampel']); ?></h4>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="stat-card-custom d-flex align-items-center gap-3">
                <div class="stat-icon-wrap bg-warning-subtle text-warning-emphasis">
                    <i class="bi bi-calendar2-week-fill"></i>
                </div>
                <div>
                    <div class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.5px; font-size: 0.72rem;">Bulan Ini (<?php echo date('M Y'); ?>)</div>
                    <h4 class="fw-bold mb-0 text-dark"><?php echo number_format($statistik['penggunaan_bulan_ini']); ?></h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Card Filter & Pencarian -->
    <div class="card filter-card mb-4">
        <div class="card-body p-3">
            <form method="get" action="<?php echo site_url('logbook'); ?>" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="filter-label-clean">
                        <i class="bi bi-cpu text-primary"></i> Filter Alat
                    </label>
                    <select class="form-select filter-input-clean" name="instrument_id" id="instrument_id">
                        <option value="">Semua Instrumen</option>
                        <?php foreach ($daftar_alat as $alt): ?>
                            <option value="<?php echo $alt['id']; ?>" <?php echo (!empty($filter['instrument_id']) && $filter['instrument_id'] == $alt['id']) ? 'selected' : ''; ?>>
                                [<?php echo html_escape($alt['kode_barang']); ?>] <?php echo html_escape($alt['nama_barang']); ?> <?php echo !empty($alt['nomor_seri']) ? '(SN: ' . html_escape($alt['nomor_seri']) . ')' : ''; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="filter-label-clean">
                        <i class="bi bi-calendar3 text-primary"></i> Dari Tanggal
                    </label>
                    <input type="date" class="form-control filter-input-clean" name="tanggal_mulai" value="<?php echo html_escape(!empty($filter['tanggal_mulai']) ? $filter['tanggal_mulai'] : ''); ?>">
                </div>

                <div class="col-md-2">
                    <label class="filter-label-clean">
                        <i class="bi bi-calendar3 text-primary"></i> Sampai Tanggal
                    </label>
                    <input type="date" class="form-control filter-input-clean" name="tanggal_selesai" value="<?php echo html_escape(!empty($filter['tanggal_selesai']) ? $filter['tanggal_selesai'] : ''); ?>">
                </div>

                <div class="col-md-3">
                    <label class="filter-label-clean">
                        <i class="bi bi-search text-primary"></i> Kata Kunci
                    </label>
                    <input type="text" class="form-control filter-input-clean" name="q" placeholder="Cari nama alat, sampel, analis..." value="<?php echo html_escape(!empty($filter['q']) ? $filter['q'] : ''); ?>">
                </div>

                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn filter-btn-search w-100 d-inline-flex align-items-center justify-content-center gap-1">
                        <i class="bi bi-funnel"></i>
                        <span>Filter</span>
                    </button>
                    <a href="<?php echo site_url('logbook'); ?>" class="btn filter-btn-reset d-inline-flex align-items-center justify-content-center" title="Reset Filter">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <?php if (!empty($alat_terpilih)): ?>
        <!-- Banner Khusus Alat Terpilih -->
        <div class="alert alert-primary border-primary-subtle d-flex align-items-center justify-content-between p-3 rounded-3 mb-4 shadow-sm" style="background: #f0f7ff;">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon-wrap bg-primary text-white shadow-sm" style="width: 44px; height: 44px; border-radius: 10px;">
                    <i class="bi bi-tools fs-5"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-1 text-dark">
                        Buku Log Khusus: <?php echo html_escape($alat_terpilih['nama_barang']); ?>
                    </h6>
                    <div class="small text-muted d-flex flex-wrap gap-2 align-items-center">
                        <span>Kode: <strong class="text-dark"><?php echo html_escape($alat_terpilih['kode_barang']); ?></strong></span>
                        <span>•</span>
                        <span>No. Seri: <strong class="text-dark"><?php echo html_escape(!empty($alat_terpilih['nomor_seri']) ? $alat_terpilih['nomor_seri'] : '-'); ?></strong></span>
                        <span>•</span>
                        <span>Merk/Tipe: <strong class="text-dark"><?php echo html_escape($alat_terpilih['merk_tipe'] ?: '-'); ?></strong></span>
                        <span>•</span>
                        <span>Ruangan: <span class="badge-ruangan-pill"><?php echo html_escape($alat_terpilih['nama_ruangan'] ?: '-'); ?></span></span>
                    </div>
                </div>
            </div>
            <a href="<?php echo site_url('logbook/alat/' . $alat_terpilih['id']); ?>" class="btn btn-sm btn-primary px-3 shadow-sm rounded-pill fw-semibold">
                <i class="bi bi-book me-1"></i> Buka Buku Log Resmi
            </a>
        </div>
    <?php endif; ?>

    <!-- Tabel Utama Logbook Penggunaan Alat -->
    <div class="main-table-card">
        <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <div class="bg-primary-subtle text-primary p-2 rounded-2 d-inline-flex">
                    <i class="bi bi-table"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0 text-dark">Daftar Rekam Penggunaan Instrumen</h6>
                    <small class="text-muted">Data otomatis tersinkron dari lembar kerja pengujian laboratorium</small>
                </div>
            </div>
            <div>
                <span class="badge bg-secondary-subtle text-secondary border px-3 py-1 rounded-pill fw-semibold">
                    <i class="bi bi-list-check me-1"></i> Total: <?php echo count($daftar_logbook); ?> Entri Pemakaian
                </span>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table align-middle mb-0 logbook-table">
                <thead>
                    <tr>
                        <th class="ps-4" style="width: 5%;">No</th>
                        <th style="width: 20%;">Nama Alat &amp; Model</th>
                        <th style="width: 13%;">Nomor Seri / Kode</th>
                        <th style="width: 11%;">Lokasi Alat</th>
                        <th style="width: 14%;">Tanggal &amp; Waktu</th>
                        <th style="width: 15%;">Keterangan Pengujian</th>
                        <th style="width: 11%;">Pengguna / Analis</th>
                        <th class="pe-4" style="width: 11%;">Sampel yang Diuji</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($daftar_logbook)): ?>
                        <?php $no = 1; foreach ($daftar_logbook as $row): ?>
                            <tr>
                                <td class="ps-4">
                                    <span class="index-pill"><?php echo $no++; ?></span>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <strong class="text-dark" style="font-size: 0.88rem; line-height: 1.3;">
                                            <?php echo html_escape($row['nama_alat']); ?>
                                        </strong>
                                        <div class="d-flex align-items-center gap-2 mt-1">
                                            <?php if (!empty($row['merk_tipe'])): ?>
                                                <span class="text-muted small" style="font-size: 0.76rem;">
                                                    <i class="bi bi-tag me-1 text-secondary"></i><?php echo html_escape($row['merk_tipe']); ?>
                                                </span>
                                            <?php endif; ?>
                                            <a href="<?php echo site_url('logbook/alat/' . $row['instrument_id']); ?>" 
                                               class="badge bg-primary-subtle text-primary border border-primary-subtle text-decoration-none px-2 py-1 rounded-pill" 
                                               style="font-size: 0.68rem;"
                                               title="Buka buku log khusus alat ini">
                                                <i class="bi bi-journal-text me-1"></i>Buku Log
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        <?php if (!empty($row['nomor_seri'])): ?>
                                            <span class="badge-serial d-inline-block">
                                                <i class="bi bi-upc-scan me-1 text-muted"></i>SN: <?php echo html_escape($row['nomor_seri']); ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="badge-serial d-inline-block text-muted">
                                                SN: -
                                            </span>
                                        <?php endif; ?>
                                        <span class="badge bg-light text-secondary border d-inline-block text-start" style="font-size: 0.72rem; width: fit-content;">
                                            <i class="bi bi-hash me-1"></i><?php echo html_escape($row['kode_barang']); ?>
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge-ruangan-pill">
                                        <i class="bi bi-geo-alt-fill me-1 text-primary"></i><?php echo html_escape(!empty($row['lokasi_alat']) ? $row['lokasi_alat'] : 'Laboratorium'); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="text-primary fs-5">
                                            <i class="bi bi-calendar-event"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark" style="font-size: 0.82rem;">
                                                <?php echo date('d M Y', strtotime($row['tanggal_penggunaan'])); ?>
                                            </div>
                                            <div class="text-muted small" style="font-size: 0.72rem;">
                                                <i class="bi bi-clock me-1"></i><?php echo date('H:i', strtotime($row['waktu_penggunaan'])); ?> WIB
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1" style="max-width: 220px;">
                                        <?php 
                                            $is_mikro = (!empty($row['jenis_pengujian']) && stripos($row['jenis_pengujian'], 'mikro') !== false);
                                            $badge_class = $is_mikro ? 'bg-purple-subtle text-purple border-purple-subtle' : 'bg-primary-subtle text-primary border-primary-subtle';
                                        ?>
                                        <div class="text-dark small fw-semibold" style="line-height: 1.35;">
                                            <?php echo html_escape($row['parameter_uji'] ?: ($row['keterangan'] ?: '-')); ?>
                                        </div>
                                        <div class="d-flex align-items-center gap-1 flex-wrap">
                                            <?php if (!empty($row['jenis_pengujian'])): ?>
                                                <span class="badge bg-light text-secondary border px-2 py-0" style="font-size: 0.68rem;">
                                                    <?php echo html_escape($row['jenis_pengujian']); ?>
                                                </span>
                                            <?php endif; ?>

                                            <?php if (!empty($row['kesimpulan']) && $row['kesimpulan'] !== 'Belum Disimpulkan'): ?>
                                                <?php $is_ms = in_array(strtoupper(trim($row['kesimpulan'])), array('MS', 'MEMENUHI SYARAT')); ?>
                                                <span class="badge <?php echo $is_ms ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-danger-subtle text-danger border border-danger-subtle'; ?> px-2 py-0" style="font-size: 0.68rem;">
                                                    <?php echo html_escape($row['kesimpulan']); ?>
                                                </span>
                                            <?php endif; ?>

                                            <?php if (!empty($row['session_id'])): ?>
                                                <a href="<?php echo site_url('pengujian/detail_sesi/' . $row['session_id']); ?>" 
                                                   class="badge bg-light text-primary border text-decoration-none px-2 py-0" 
                                                   style="font-size: 0.68rem;" 
                                                   target="_blank"
                                                   title="Buka Detail Sesi Pengujian">
                                                    <i class="bi bi-box-arrow-up-right me-1"></i>Sesi #<?php echo $row['session_id']; ?>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-user-badge">
                                            <?php 
                                                $nama_user = !empty($row['nama_pengguna']) ? $row['nama_pengguna'] : ($row['username'] ?: 'P');
                                                echo strtoupper(substr(trim($nama_user), 0, 1));
                                            ?>
                                        </div>
                                        <div class="d-flex flex-column" style="min-width: 0;">
                                            <span class="fw-bold text-dark text-truncate" style="font-size: 0.82rem; max-width: 130px;" title="<?php echo html_escape($nama_user); ?>">
                                                <?php echo html_escape($nama_user); ?>
                                            </span>
                                            <span class="text-muted small text-truncate" style="font-size: 0.70rem; max-width: 130px;">
                                                <?php echo !empty($row['nip_pengguna']) ? 'NIP: ' . html_escape($row['nip_pengguna']) : '@' . html_escape($row['username']); ?>
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="pe-4">
                                    <div class="d-flex flex-column" style="max-width: 160px;">
                                        <a href="<?php echo site_url('sampel/detail/' . $row['sample_id']); ?>" 
                                           class="fw-bold text-primary text-decoration-none font-monospace d-inline-flex align-items-center gap-1" 
                                           target="_blank" 
                                           style="font-size: 0.82rem;"
                                           title="Buka Detail Sampel">
                                            <i class="bi bi-box-seam"></i>
                                            <span><?php echo html_escape($row['kode_sampel_manual'] ?: ($row['no_sipt'] ?: 'SMP-' . $row['sample_id'])); ?></span>
                                        </a>
                                        <small class="text-muted text-truncate mt-1" style="font-size: 0.72rem;" title="<?php echo html_escape($row['nama_sampel']); ?>">
                                            <?php echo html_escape($row['nama_sampel']); ?>
                                        </small>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="py-4">
                                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px;">
                                        <i class="bi bi-journal-x fs-1 text-muted"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1">Belum Ada Catatan Logbook Alat</h6>
                                    <p class="text-muted small mb-0" style="max-width: 450px; margin: 0 auto;">
                                        Penggunaan instrumen laboratorium akan otomatis tercatat di sini setiap kali penguji menyelesaikan dan mengirimkan hasil pengujian ke penyelia.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
