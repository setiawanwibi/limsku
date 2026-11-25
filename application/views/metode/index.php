<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
    .metode-page { width: 100%; padding: 4px 0 24px; color: #294257; }
    .metode-card { width: 100%; background: #ffffff; border: 1px solid #e0e8ed; border-radius: 12px; box-shadow: 0 4px 16px rgba(41, 66, 87, 0.05); overflow: hidden; }
    .metode-header { display: flex; align-items: center; justify-content: space-between; gap: 16px; padding: 18px 24px; background: #ffffff; border-bottom: 1px solid #e8eef1; }
    .metode-heading { display: flex; align-items: center; gap: 12px; }
    .metode-heading-icon { width: 42px; height: 42px; border-radius: 10px; background: #e6f7ff; color: #1890ff; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0; }
    .metode-title { margin: 0; color: #294257; font-size: 1.1rem; font-weight: 700; }
    .metode-subtitle { margin: 4px 0 0; color: #7b8b98; font-size: 0.82rem; }
    .btn-import-excel { display: inline-flex; align-items: center; gap: 8px; padding: 9px 16px; background: #ffffff; color: #2b6cb0; border: 1px solid #cbd5e0; border-radius: 8px; font-size: 0.84rem; font-weight: 600; text-decoration: none; transition: all 0.2s; }
    .btn-import-excel:hover { background: #ebf8ff; border-color: #3182ce; color: #2b6cb0; }
    .btn-add-metode { display: inline-flex; align-items: center; gap: 8px; padding: 9px 16px; background: #1890ff; color: #ffffff; border: 1px solid #1890ff; border-radius: 8px; font-size: 0.84rem; font-weight: 600; text-decoration: none; box-shadow: 0 2px 6px rgba(24, 144, 255, 0.2); }
    .btn-add-metode:hover { background: #096dd9; color: #ffffff; }
    .metode-body { padding: 20px 24px; }
    .metode-table thead th { background: #f7fafc; color: #40596d; font-size: 0.76rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; padding: 12px 14px; border-bottom: 1px solid #e0e8ed; white-space: nowrap; }
    .metode-table tbody td { padding: 12px 14px; font-size: 0.83rem; color: #334e68; border-bottom: 1px solid #edf2f5; vertical-align: middle; }
    .badge-lcp-mapped { background: #e6ffed; color: #22863a; border: 1px solid #b7eb8f; padding: 3px 8px; border-radius: 6px; font-size: 0.74rem; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; }
    .badge-lcp-unmapped { background: #fffbe6; color: #d48806; border: 1px solid #ffe58f; padding: 3px 8px; border-radius: 6px; font-size: 0.74rem; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; cursor: pointer; }
    .badge-lcp-unmapped:hover { background: #fff1b8; }
    .action-btn-group { display: inline-flex; gap: 5px; }
    .action-btn { display: inline-flex; align-items: center; justify-content: center; width: 30px; height: 30px; border-radius: 6px; font-size: 0.82rem; border: 1px solid transparent; }

    /* Pangan Sub-Navigation Styling */
    .pangan-filter-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px 18px; margin-bottom: 20px; }
    .pangan-nav-pill { display: inline-flex; align-items: center; gap: 8px; padding: 8px 16px; border-radius: 8px; font-size: 0.86rem; font-weight: 600; text-decoration: none; transition: all 0.2s; border: 1px solid #cbd5e1; background: #ffffff; color: #475569; }
    .pangan-nav-pill:hover { background: #f1f5f9; color: #1e293b; border-color: #94a3b8; }
    .pangan-nav-pill.active { background: #1890ff; color: #ffffff; border-color: #1890ff; box-shadow: 0 2px 6px rgba(24, 144, 255, 0.25); }
    .pangan-nav-pill .badge { font-size: 0.74rem; padding: 3px 7px; border-radius: 12px; }
    .pangan-nav-pill.active .badge { background: #ffffff !important; color: #1890ff !important; }
    .pangan-nav-pill:not(.active) .badge { background: #e2e8f0; color: #475569; }

    @media (max-width: 991.98px) {
        .metode-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 14px;
            padding: 16px;
        }
        .metode-header .d-flex.align-items-center {
            width: 100%;
            flex-wrap: wrap;
            gap: 8px !important;
        }
        .btn-import-excel, .btn-add-metode, .metode-header .btn {
            flex: 1 1 auto;
            justify-content: center;
        }
        .metode-body {
            padding: 16px;
        }
    }
</style>

<div class="metode-page">

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

    <div class="metode-card">
        <?php
        $komoditi_curr = isset($komoditi_aktif) ? $komoditi_aktif : ($this->session->userdata('komoditi_aktif') ?: 'obat');
        $tab_items = array(
            'obat'     => array('label' => 'Obat', 'full' => 'Laboratorium Obat', 'icon' => 'bi-capsule'),
            'pangan'   => array('label' => 'Pangan', 'full' => 'Laboratorium Pangan', 'icon' => 'bi-cup-straw'),
            'herbal'   => array('label' => 'Obat Tradisional', 'full' => 'Laboratorium Obat Tradisional', 'icon' => 'bi-flower1'),
            'kosmetik' => array('label' => 'Kosmetik', 'full' => 'Laboratorium Kosmetik', 'icon' => 'bi-brush'),
            'suplemen' => array('label' => 'Suplemen', 'full' => 'Laboratorium Suplemen', 'icon' => 'bi-heart-pulse'),
            'semua'    => array('label' => 'Semua Komoditi', 'full' => 'Semua Komoditi', 'icon' => 'bi-collection')
        );
        $is_pangan = ($komoditi_curr === 'pangan');
        $is_kosmetik = ($komoditi_curr === 'kosmetik');
        $sub_pangan = isset($sub_pangan_aktif) ? $sub_pangan_aktif : 'olahan';
        $sub_kosmetik = isset($sub_kosmetik_aktif) ? $sub_kosmetik_aktif : '';
        $bidang_cur = isset($bidang_aktif) ? $bidang_aktif : '';
        ?>
        <div class="metode-header">
            <div class="metode-heading">
                <div class="metode-heading-icon">
                    <i class="bi <?php echo isset($tab_items[$komoditi_curr]['icon']) ? $tab_items[$komoditi_curr]['icon'] : 'bi-journal-bookmark'; ?>"></i>
                </div>
                <div>
                    <h5 class="metode-title">
                        Master Metode Pengujian &bull; <?php echo isset($tab_items[$komoditi_curr]['full']) ? $tab_items[$komoditi_curr]['full'] : ucfirst($komoditi_curr); ?>
                        <?php if ($is_pangan): ?>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle ms-2 fw-semibold" style="font-size: 0.78rem;">
                                <?php echo ($sub_pangan === 'pirt') ? 'Pedoman PIRT' : 'Pedoman Pangan Olahan (MD/ML)'; ?>
                            </span>
                        <?php elseif ($is_kosmetik): ?>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle ms-2 fw-semibold" style="font-size: 0.78rem;">
                                <?php echo !empty($sub_kosmetik) ? 'Pedoman ' . html_escape($sub_kosmetik) : 'Pedoman Compliance & Surveillance'; ?>
                            </span>
                        <?php endif; ?>
                    </h5>
                    <p class="metode-subtitle">
                        <?php if ($is_pangan): ?>
                            Pedoman Resmi Metode Pengujian BBPOM: Kategori Pangan (Katpang), Pengujian Prioritas/Kajian, Syarat Mutu 3-Kelas, Acuan Regulasi & LCP.
                        <?php elseif ($is_kosmetik): ?>
                            Pedoman Resmi Metode Pengujian Kosmetik BBPOM: Tipe Produk, Kategori Sediaan, Kelompok Uji (Compliance & Surveillance), Bidang & Parameter Uji Kritis.
                        <?php else: ?>
                            Kelola data monografi zat aktif, parameter uji kritis, spesifikasi, dan pemetaan ke Lembar Catatan Pengujian (LCP).
                        <?php endif; ?>
                    </p>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                <?php if (!$is_pangan && !$is_kosmetik): ?>
                    <a href="<?php echo site_url('metode/automap'); ?>" class="btn btn-outline-primary d-inline-flex align-items-center gap-2 fw-semibold px-3 py-2" style="border-radius: 8px; font-size: 0.84rem;">
                        <i class="bi bi-diagram-3-fill text-primary"></i>
                        Auto-Mapping LCP
                    </a>
                    <a href="<?php echo site_url('metode/import'); ?>" class="btn-import-excel">
                        <i class="bi bi-file-earmark-excel"></i>
                        Import Excel
                    </a>
                    <a href="<?php echo site_url('metode/tambah'); ?>" class="btn-add-metode">
                        <i class="bi bi-plus-lg"></i>
                        Tambah Metode
                    </a>
                <?php else: ?>
                    <span class="text-muted small d-inline-flex align-items-center gap-1">
                        <i class="bi bi-shield-check text-success fs-5"></i> 
                        <span>Data SK Pedoman Terverifikasi</span>
                    </span>
                <?php endif; ?>
            </div>
        </div>

        <div class="metode-body">
            <?php if ($is_pangan): ?>
                <!-- Pangan Sub-Navigation Filter Bar -->
                <div class="pangan-filter-box d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <!-- Segmented Control Pilihan Pedoman -->
                    <div class="d-flex align-items-center gap-2">
                        <span class="small fw-bold text-secondary text-uppercase me-1"><i class="bi bi-funnel-fill text-primary"></i> Pedoman Data:</span>
                        <div class="d-inline-flex gap-2">
                            <a href="<?php echo site_url('metode?komoditi=pangan&sub=olahan' . ($bidang_cur ? '&bidang=' . urlencode($bidang_cur) : '')); ?>" 
                               class="pangan-nav-pill <?php echo ($sub_pangan === 'olahan') ? 'active' : ''; ?>">
                                <i class="bi bi-box-seam"></i> Pangan Olahan (MD/ML)
                                <span class="badge"><?php echo isset($counts_pangan['olahan_total']) ? number_format($counts_pangan['olahan_total']) : 0; ?></span>
                            </a>
                            <a href="<?php echo site_url('metode?komoditi=pangan&sub=pirt' . ($bidang_cur ? '&bidang=' . urlencode($bidang_cur) : '')); ?>" 
                               class="pangan-nav-pill <?php echo ($sub_pangan === 'pirt') ? 'active' : ''; ?>">
                                <i class="bi bi-house-door"></i> Pangan Industri Rumah Tangga (PIRT)
                                <span class="badge"><?php echo isset($counts_pangan['pirt_total']) ? number_format($counts_pangan['pirt_total']) : 0; ?></span>
                            </a>
                        </div>
                    </div>

                    <!-- Filter Bidang Pengujian -->
                    <div class="d-flex align-items-center gap-2">
                        <span class="small fw-bold text-secondary text-uppercase"><i class="bi bi-filter"></i> Bidang Lab:</span>
                        <div class="btn-group" role="group">
                            <a href="<?php echo site_url('metode?komoditi=pangan&sub=' . $sub_pangan); ?>" 
                               class="btn btn-sm <?php echo empty($bidang_cur) ? 'btn-secondary text-white fw-bold' : 'btn-outline-secondary'; ?>">
                                Semua
                            </a>
                            <a href="<?php echo site_url('metode?komoditi=pangan&sub=' . $sub_pangan . '&bidang=Kimia'); ?>" 
                               class="btn btn-sm <?php echo ($bidang_cur === 'Kimia') ? 'btn-info text-dark fw-bold' : 'btn-outline-info text-dark'; ?>">
                                <i class="bi bi-droplet-half me-1"></i> Kimia
                                <span class="badge bg-light text-dark ms-1">
                                    <?php echo ($sub_pangan === 'olahan') ? ($counts_pangan['olahan_kimia'] ?? 0) : ($counts_pangan['pirt_kimia'] ?? 0); ?>
                                </span>
                            </a>
                            <a href="<?php echo site_url('metode?komoditi=pangan&sub=' . $sub_pangan . '&bidang=Mikrobiologi'); ?>" 
                               class="btn btn-sm <?php echo ($bidang_cur === 'Mikrobiologi') ? 'btn-success text-white fw-bold' : 'btn-outline-success'; ?>">
                                <i class="bi bi-virus me-1"></i> Mikrobiologi
                                <span class="badge bg-light text-success ms-1">
                                    <?php echo ($sub_pangan === 'olahan') ? ($counts_pangan['olahan_mikro'] ?? 0) : ($counts_pangan['pirt_mikro'] ?? 0); ?>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Info Header Banner -->
                <div class="alert alert-info py-2 px-3 small d-flex align-items-center gap-2 mb-3 border-0 bg-info-subtle text-info-emphasis rounded-3">
                    <i class="bi bi-info-circle-fill fs-5"></i>
                    <div>
                        <?php if ($sub_pangan === 'olahan'): ?>
                            <strong>Tabel Pedoman Metode Pangan Olahan (MD/ML):</strong> Meliputi 16 Kategori Pangan (Katpang 01 s/d 16), pengujian Wajib/Kajian, batas cemaran kimia & kriteria 3-kelas mikrobiologi (n, c, m, M).
                        <?php else: ?>
                            <strong>Tabel Pedoman Metode Pangan Industri Rumah Tangga (PIRT):</strong> Meliputi Kategori Pangan PIRT, Kode Katpang Nasional, pengujian PRIORITAS & PILIHAN, syarat mutu, dan metode analisa acuan BBPOM.
                        <?php endif; ?>
                    </div>
                </div>
            <?php elseif ($is_kosmetik): ?>
                <!-- Kosmetik Sub-Navigation Filter Bar -->
                <div class="pangan-filter-box d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <!-- Segmented Control Jenis Pengawasan -->
                    <div class="d-flex align-items-center gap-2">
                        <span class="small fw-bold text-secondary text-uppercase me-1"><i class="bi bi-funnel-fill text-primary"></i> Pengawasan:</span>
                        <div class="d-inline-flex flex-wrap gap-2">
                            <a href="<?php echo site_url('metode?komoditi=kosmetik' . ($bidang_cur ? '&bidang=' . urlencode($bidang_cur) : '')); ?>" 
                               class="pangan-nav-pill <?php echo empty($sub_kosmetik) ? 'active' : ''; ?>">
                                <i class="bi bi-collection"></i> Semua
                                <span class="badge"><?php echo isset($counts_kosmetik['total_semua']) ? number_format($counts_kosmetik['total_semua']) : 0; ?></span>
                            </a>
                            <a href="<?php echo site_url('metode?komoditi=kosmetik&sub=Compliance' . ($bidang_cur ? '&bidang=' . urlencode($bidang_cur) : '')); ?>" 
                               class="pangan-nav-pill <?php echo ($sub_kosmetik === 'Compliance') ? 'active' : ''; ?>">
                                <i class="bi bi-shield-check"></i> Compliance
                                <span class="badge"><?php echo isset($counts_kosmetik['compliance_total']) ? number_format($counts_kosmetik['compliance_total']) : 0; ?></span>
                            </a>
                            <a href="<?php echo site_url('metode?komoditi=kosmetik&sub=Surveillance' . ($bidang_cur ? '&bidang=' . urlencode($bidang_cur) : '')); ?>" 
                               class="pangan-nav-pill <?php echo ($sub_kosmetik === 'Surveillance') ? 'active' : ''; ?>">
                                <i class="bi bi-search"></i> Surveillance
                                <span class="badge"><?php echo isset($counts_kosmetik['surveillance_total']) ? number_format($counts_kosmetik['surveillance_total']) : 0; ?></span>
                            </a>
                        </div>
                    </div>

                    <!-- Filter Bidang Pengujian -->
                    <div class="d-flex align-items-center gap-2">
                        <span class="small fw-bold text-secondary text-uppercase"><i class="bi bi-filter"></i> Bidang Lab:</span>
                        <div class="btn-group" role="group">
                            <a href="<?php echo site_url('metode?komoditi=kosmetik' . ($sub_kosmetik ? '&sub=' . urlencode($sub_kosmetik) : '')); ?>" 
                               class="btn btn-sm <?php echo empty($bidang_cur) ? 'btn-secondary text-white fw-bold' : 'btn-outline-secondary'; ?>">
                                Semua
                            </a>
                            <a href="<?php echo site_url('metode?komoditi=kosmetik' . ($sub_kosmetik ? '&sub=' . urlencode($sub_kosmetik) : '') . '&bidang=Kimia'); ?>" 
                               class="btn btn-sm <?php echo ($bidang_cur === 'Kimia') ? 'btn-info text-dark fw-bold' : 'btn-outline-info text-dark'; ?>">
                                <i class="bi bi-droplet-half me-1"></i> Kimia
                                <span class="badge bg-light text-dark ms-1">
                                    <?php 
                                    if ($sub_kosmetik === 'Compliance') echo $counts_kosmetik['compliance_kimia'] ?? 0;
                                    elseif ($sub_kosmetik === 'Surveillance') echo $counts_kosmetik['surveillance_kimia'] ?? 0;
                                    else echo $counts_kosmetik['kimia_total'] ?? 0;
                                    ?>
                                </span>
                            </a>
                            <a href="<?php echo site_url('metode?komoditi=kosmetik' . ($sub_kosmetik ? '&sub=' . urlencode($sub_kosmetik) : '') . '&bidang=Mikrobiologi'); ?>" 
                               class="btn btn-sm <?php echo ($bidang_cur === 'Mikrobiologi') ? 'btn-success text-white fw-bold' : 'btn-outline-success'; ?>">
                                <i class="bi bi-virus me-1"></i> Mikrobiologi
                                <span class="badge bg-light text-success ms-1">
                                    <?php 
                                    if ($sub_kosmetik === 'Compliance') echo $counts_kosmetik['compliance_mikro'] ?? 0;
                                    elseif ($sub_kosmetik === 'Surveillance') echo $counts_kosmetik['surveillance_mikro'] ?? 0;
                                    else echo $counts_kosmetik['mikro_total'] ?? 0;
                                    ?>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Info Header Banner Kosmetik -->
                <div class="alert alert-info py-2 px-3 small d-flex align-items-center gap-2 mb-3 border-0 bg-info-subtle text-info-emphasis rounded-3">
                    <i class="bi bi-info-circle-fill fs-5"></i>
                    <div>
                        <strong>Tabel Pedoman Master Metode Kosmetik:</strong> Data resmi audit BBPOM meliputi Tipe Produk (01 s/d 13), Kategori Sediaan Kosmetik, Kelompok Uji (Keamanan & Mutu Compliance/Surveillance), Bidang Pengujian (Kimia & Mikrobiologi), serta Parameter Uji Kritis.
                    </div>
                </div>
            <?php elseif ($komoditi_curr === 'herbal' || $komoditi_curr === 'suplemen'): ?>
                <!-- Filter Bidang Lab untuk Obat Tradisional (OBASK) & Suplemen -->
                <div class="pangan-filter-box d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div class="d-flex align-items-center gap-2">
                        <span class="small fw-bold text-secondary text-uppercase me-1"><i class="bi bi-journal-medical text-primary"></i> Pedoman:</span>
                        <span class="badge bg-primary px-3 py-2" style="font-size: 0.82rem;">
                            <?php echo ($komoditi_curr === 'herbal') ? 'Master Metode Herbal (OBASK) &bull; ' . (isset($counts_komoditi['herbal']) ? $counts_komoditi['herbal'] : count($daftar_metode)) . ' Data' : 'Master Metode Suplemen &bull; ' . (isset($counts_komoditi['suplemen']) ? $counts_komoditi['suplemen'] : count($daftar_metode)) . ' Data'; ?>
                        </span>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <span class="small fw-bold text-secondary text-uppercase"><i class="bi bi-filter"></i> Bidang Lab:</span>
                        <div class="btn-group" role="group">
                            <a href="<?php echo site_url('metode?komoditi=' . $komoditi_curr); ?>" 
                               class="btn btn-sm <?php echo empty($bidang_cur) ? 'btn-secondary text-white fw-bold' : 'btn-outline-secondary'; ?>">
                                Semua
                            </a>
                            <a href="<?php echo site_url('metode?komoditi=' . $komoditi_curr . '&bidang=Kimia'); ?>" 
                               class="btn btn-sm <?php echo ($bidang_cur === 'Kimia') ? 'btn-info text-dark fw-bold' : 'btn-outline-info text-dark'; ?>">
                                <i class="bi bi-droplet-half me-1"></i> Kimia
                            </a>
                            <a href="<?php echo site_url('metode?komoditi=' . $komoditi_curr . '&bidang=Mikrobiologi'); ?>" 
                               class="btn btn-sm <?php echo ($bidang_cur === 'Mikrobiologi') ? 'btn-success text-white fw-bold' : 'btn-outline-success'; ?>">
                                <i class="bi bi-virus me-1"></i> Mikrobiologi
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-hover align-middle metode-table" id="tabel-metode">
                    <?php if ($is_pangan && $sub_pangan === 'olahan'): ?>
                        <!-- TABEL 1: PANGAN OLAHAN (MD/ML) -->
                        <thead>
                            <tr>
                                <th width="3%">NO</th>
                                <th>KODE</th>
                                <th>KATEGORI PANGAN (KATPANG)</th>
                                <th>JENIS PANGAN / MATRIKS</th>
                                <th>BIDANG & LAB</th>
                                <th>JENIS PU</th>
                                <th>PARAMETER UJI KRITIS & LCP</th>
                                <th>SYARAT / BATAS MUTU</th>
                                <th>METODE ANALISA / CARA UJI</th>
                                <th>REGULASI</th>
                                <th width="6%">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($daftar_pangan_olahan)): ?>
                                <tr>
                                    <td colspan="11" class="text-center py-4 text-muted">
                                        <i class="bi bi-inbox fs-3 d-block mb-2 text-muted"></i>
                                        Tidak ada data metode pangan olahan yang sesuai dengan filter.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php $no = 1; foreach ($daftar_pangan_olahan as $m): ?>
                                    <tr>
                                        <td class="text-center text-muted"><?php echo $no++; ?></td>
                                        <td><code><?php echo html_escape($m['kode_metode']); ?></code></td>
                                        <td>
                                            <?php if (!empty($m['indeks_katpang'])): ?>
                                                <span class="badge bg-secondary-subtle text-secondary border me-1"><?php echo html_escape($m['indeks_katpang']); ?></span>
                                            <?php endif; ?>
                                            <strong class="text-dark"><?php echo html_escape($m['kategori_pangan'] ?: '-'); ?></strong>
                                        </td>
                                        <td>
                                            <div class="fw-semibold text-dark"><?php echo html_escape($m['jenis_pangan'] ?: '-'); ?></div>
                                            <?php if (!empty($m['presentase_jenis_pangan'])): ?>
                                                <small class="text-muted d-block"><?php echo html_escape($m['presentase_jenis_pangan']); ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?php echo ($m['bidang'] === 'Kimia' ? 'info text-dark' : 'success'); ?>">
                                                <?php echo html_escape($m['bidang']); ?>
                                            </span>
                                            <?php if (!empty($m['jenis_lab'])): ?>
                                                <small class="d-block text-secondary mt-1"><?php echo html_escape($m['jenis_lab']); ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($m['jenis_pu'] === 'Wajib'): ?>
                                                <span class="badge bg-danger">Wajib</span>
                                            <?php elseif ($m['jenis_pu'] === 'Kajian'): ?>
                                                <span class="badge bg-warning text-dark">Kajian</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary"><?php echo html_escape($m['jenis_pu'] ?: '-'); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <strong class="text-primary d-block"><?php echo html_escape($m['parameter_uji']); ?></strong>
                                            <?php if (!empty($m['template_id'])): ?>
                                                <span class="badge-lcp-mapped mt-1" title="<?php echo html_escape($m['nama_template']); ?>">
                                                    <i class="bi bi-check-circle-fill"></i> LCP: <?php echo html_escape($m['kode_template']); ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="badge-lcp-unmapped mt-1" role="button" data-bs-toggle="modal" data-bs-target="#modalMapLcpPo<?php echo $m['id']; ?>">
                                                    <i class="bi bi-exclamation-triangle-fill"></i> Belum Dipetakan LCP
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($m['bidang'] === 'Mikrobiologi'): ?>
                                                <div class="small">
                                                    <?php if (!empty($m['n']) || !empty($m['c'])): ?>
                                                        <div><span class="fw-bold">n:</span> <?php echo html_escape($m['n'] ?: '-'); ?> | <span class="fw-bold">c:</span> <?php echo html_escape($m['c'] ?: '-'); ?></div>
                                                    <?php endif; ?>
                                                    <?php if (!empty($m['kriteria_m']) || !empty($m['kriteria_M_besar'])): ?>
                                                        <div><span class="fw-bold">m:</span> <?php echo html_escape($m['kriteria_m'] ?: '-'); ?> | <span class="fw-bold">M:</span> <?php echo html_escape($m['kriteria_M_besar'] ?: '-'); ?></div>
                                                    <?php endif; ?>
                                                    <?php if (!empty($m['kriteria_3_kelas'])): ?>
                                                        <span class="badge bg-light text-dark border mt-1"><?php echo html_escape($m['kriteria_3_kelas']); ?></span>
                                                    <?php endif; ?>
                                                </div>
                                            <?php else: ?>
                                                <span class="small text-dark"><?php echo html_escape($m['syarat_batas'] ?: '-'); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="small text-dark"><?php echo html_escape($m['metode_analisa'] ?: '-'); ?></div>
                                            <?php if (!empty($m['cara_uji'])): ?>
                                                <span class="badge bg-light text-primary border mt-1"><?php echo html_escape($m['cara_uji']); ?></span>
                                            <?php endif; ?>
                                            <?php if (!empty($m['halaman_sk'])): ?>
                                                <small class="text-muted d-block mt-1">Hal. SK: <?php echo html_escape($m['halaman_sk']); ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td><small class="text-secondary"><?php echo html_escape($m['referensi_regulasi'] ?: '-'); ?></small></td>
                                        <td>
                                            <div class="action-btn-group">
                                                <button type="button" class="action-btn btn-sm btn-outline-primary" title="Petakan LCP" data-bs-toggle="modal" data-bs-target="#modalMapLcpPo<?php echo $m['id']; ?>">
                                                    <i class="bi bi-link-45deg"></i>
                                                </button>
                                            </div>

                                            <!-- Modal Pemetaan LCP Pangan Olahan -->
                                            <div class="modal fade" id="modalMapLcpPo<?php echo $m['id']; ?>" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content border-0 shadow">
                                                        <?php echo form_open('metode/mapping/' . $m['id']); ?>
                                                            <input type="hidden" name="target_table" value="pangan_olahan">
                                                            <input type="hidden" name="redirect_url" value="<?php echo current_url() . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : ''); ?>">
                                                            <div class="modal-header border-bottom">
                                                                <h6 class="modal-title fw-bold">Petakan LCP &bull; Pangan Olahan</h6>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body py-3">
                                                                <div class="mb-3">
                                                                    <small class="text-muted d-block">Parameter Uji:</small>
                                                                    <strong class="text-primary"><?php echo html_escape($m['parameter_uji']); ?></strong>
                                                                    <small class="text-secondary d-block mt-1">Kategori: <?php echo html_escape($m['kategori_pangan']); ?> (<?php echo html_escape($m['jenis_pangan']); ?>)</small>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="tpl_po_<?php echo $m['id']; ?>" class="form-label small fw-bold">Pilih Form LCP Resmi:</label>
                                                                    <select name="template_id" id="tpl_po_<?php echo $m['id']; ?>" class="form-select form-select-sm">
                                                                        <option value="">-- Tandai Belum Dipetakan --</option>
                                                                        <?php foreach ($daftar_lcp as $lcp): ?>
                                                                            <?php if (empty($lcp['kategori']) || $lcp['kategori'] === $m['bidang']): ?>
                                                                                <option value="<?php echo $lcp['id']; ?>" <?php echo ($m['template_id'] == $lcp['id']) ? 'selected' : ''; ?>>
                                                                                    [<?php echo html_escape($lcp['kode_template']); ?>] <?php echo html_escape($lcp['nama_template']); ?>
                                                                                </option>
                                                                            <?php endif; ?>
                                                                        <?php endforeach; ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer border-top">
                                                                <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Tutup</button>
                                                                <button type="submit" class="btn btn-sm btn-primary">Simpan Pemetaan</button>
                                                            </div>
                                                        <?php echo form_close(); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>

                    <?php elseif ($is_pangan && $sub_pangan === 'pirt'): ?>
                        <!-- TABEL 2: PANGAN INDUSTRI RUMAH TANGGA (PIRT) -->
                        <thead>
                            <tr>
                                <th width="3%">NO</th>
                                <th>KODE</th>
                                <th>KATEGORI PANGAN PIRT</th>
                                <th>JENIS PANGAN PIRT</th>
                                <th>BIDANG</th>
                                <th>PRIORITAS UJI</th>
                                <th>PARAMETER UJI KRITIS & LCP</th>
                                <th>SYARAT / BATAS MUTU</th>
                                <th>METODE ANALISA</th>
                                <th>REGULASI</th>
                                <th width="6%">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($daftar_pangan_pirt)): ?>
                                <tr>
                                    <td colspan="11" class="text-center py-4 text-muted">
                                        <i class="bi bi-inbox fs-3 d-block mb-2 text-muted"></i>
                                        Tidak ada data metode PIRT yang sesuai dengan filter.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php $no = 1; foreach ($daftar_pangan_pirt as $m): ?>
                                    <tr>
                                        <td class="text-center text-muted"><?php echo $no++; ?></td>
                                        <td><code><?php echo html_escape($m['kode_metode']); ?></code></td>
                                        <td>
                                            <strong class="d-block text-dark"><?php echo html_escape($m['kategori_pangan_pirt']); ?></strong>
                                            <?php if (!empty($m['kategori_pangan_nasional'])): ?>
                                                <small class="text-muted">Kode Nas: <strong><?php echo html_escape($m['kategori_pangan_nasional']); ?></strong></small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="fw-semibold text-dark"><?php echo html_escape($m['jenis_pangan_pirt'] ?: '-'); ?></div>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?php echo ($m['bidang'] === 'Kimia' ? 'info text-dark' : 'success'); ?>">
                                                <?php echo html_escape($m['bidang']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($m['pu'] === 'PRIORITAS'): ?>
                                                <span class="badge bg-danger">PRIORITAS</span>
                                            <?php elseif ($m['pu'] === 'PILIHAN'): ?>
                                                <span class="badge bg-warning text-dark">PILIHAN</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary"><?php echo html_escape($m['pu'] ?: '-'); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <strong class="text-primary d-block"><?php echo html_escape($m['parameter_uji']); ?></strong>
                                            <?php if (!empty($m['template_id'])): ?>
                                                <span class="badge-lcp-mapped mt-1" title="<?php echo html_escape($m['nama_template']); ?>">
                                                    <i class="bi bi-check-circle-fill"></i> LCP: <?php echo html_escape($m['kode_template']); ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="badge-lcp-unmapped mt-1" role="button" data-bs-toggle="modal" data-bs-target="#modalMapLcpPirt<?php echo $m['id']; ?>">
                                                    <i class="bi bi-exclamation-triangle-fill"></i> Belum Dipetakan LCP
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($m['bidang'] === 'Mikrobiologi'): ?>
                                                <div class="small">
                                                    <?php if (!empty($m['n']) || !empty($m['c'])): ?>
                                                        <div><span class="fw-bold">n:</span> <?php echo html_escape($m['n'] ?: '-'); ?> | <span class="fw-bold">c:</span> <?php echo html_escape($m['c'] ?: '-'); ?></div>
                                                    <?php endif; ?>
                                                    <?php if (!empty($m['kriteria_m']) || !empty($m['kriteria_M_besar'])): ?>
                                                        <div><span class="fw-bold">m:</span> <?php echo html_escape($m['kriteria_m'] ?: '-'); ?> | <span class="fw-bold">M:</span> <?php echo html_escape($m['kriteria_M_besar'] ?: '-'); ?></div>
                                                    <?php endif; ?>
                                                    <?php if (!empty($m['kriteria_3_kelas'])): ?>
                                                        <span class="badge bg-light text-dark border mt-1"><?php echo html_escape($m['kriteria_3_kelas']); ?></span>
                                                    <?php endif; ?>
                                                </div>
                                            <?php else: ?>
                                                <span class="small text-dark"><?php echo html_escape($m['syarat_batas'] ?: '-'); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="small text-dark"><?php echo html_escape($m['metode_analisa'] ?: '-'); ?></div>
                                            <?php if (!empty($m['halaman_sk'])): ?>
                                                <small class="text-muted d-block mt-1">Hal. SK: <?php echo html_escape($m['halaman_sk']); ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td><small class="text-secondary"><?php echo html_escape($m['referensi_regulasi'] ?: '-'); ?></small></td>
                                        <td>
                                            <div class="action-btn-group">
                                                <button type="button" class="action-btn btn-sm btn-outline-primary" title="Petakan LCP" data-bs-toggle="modal" data-bs-target="#modalMapLcpPirt<?php echo $m['id']; ?>">
                                                    <i class="bi bi-link-45deg"></i>
                                                </button>
                                            </div>

                                            <!-- Modal Pemetaan LCP PIRT -->
                                            <div class="modal fade" id="modalMapLcpPirt<?php echo $m['id']; ?>" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content border-0 shadow">
                                                        <?php echo form_open('metode/mapping/' . $m['id']); ?>
                                                            <input type="hidden" name="target_table" value="pangan_pirt">
                                                            <input type="hidden" name="redirect_url" value="<?php echo current_url() . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : ''); ?>">
                                                            <div class="modal-header border-bottom">
                                                                <h6 class="modal-title fw-bold">Petakan LCP &bull; Pangan PIRT</h6>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body py-3">
                                                                <div class="mb-3">
                                                                    <small class="text-muted d-block">Parameter Uji:</small>
                                                                    <strong class="text-primary"><?php echo html_escape($m['parameter_uji']); ?></strong>
                                                                    <small class="text-secondary d-block mt-1">PIRT: <?php echo html_escape($m['kategori_pangan_pirt']); ?> (<?php echo html_escape($m['jenis_pangan_pirt']); ?>)</small>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="tpl_pirt_<?php echo $m['id']; ?>" class="form-label small fw-bold">Pilih Form LCP Resmi:</label>
                                                                    <select name="template_id" id="tpl_pirt_<?php echo $m['id']; ?>" class="form-select form-select-sm">
                                                                        <option value="">-- Tandai Belum Dipetakan --</option>
                                                                        <?php foreach ($daftar_lcp as $lcp): ?>
                                                                            <?php if (empty($lcp['kategori']) || $lcp['kategori'] === $m['bidang']): ?>
                                                                                <option value="<?php echo $lcp['id']; ?>" <?php echo ($m['template_id'] == $lcp['id']) ? 'selected' : ''; ?>>
                                                                                    [<?php echo html_escape($lcp['kode_template']); ?>] <?php echo html_escape($lcp['nama_template']); ?>
                                                                                </option>
                                                                            <?php endif; ?>
                                                                        <?php endforeach; ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer border-top">
                                                                <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Tutup</button>
                                                                <button type="submit" class="btn btn-sm btn-primary">Simpan Pemetaan</button>
                                                            </div>
                                                        <?php echo form_close(); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>

                    <?php elseif ($is_kosmetik): ?>
                        <!-- TABEL KOSMETIK (COMPLIANCE & SURVEILLANCE) -->
                        <thead>
                            <tr>
                                <th width="3%">NO</th>
                                <th>KODE</th>
                                <th>PENGAWASAN</th>
                                <th>TIPE PRODUK</th>
                                <th>KATEGORI KOSMETIK</th>
                                <th>KELOMPOK UJI</th>
                                <th>BIDANG</th>
                                <th>PARAMETER UJI KRITIS & LCP</th>
                                <th>HAL</th>
                                <th width="6%">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($daftar_kosmetik)): ?>
                                <tr>
                                    <td colspan="10" class="text-center py-4 text-muted">
                                        <i class="bi bi-inbox fs-3 d-block mb-2 text-muted"></i>
                                        Tidak ada data metode kosmetik yang sesuai dengan filter.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php $no = 1; foreach ($daftar_kosmetik as $m): ?>
                                    <tr>
                                        <td class="text-center text-muted"><?php echo $no++; ?></td>
                                        <td><code><?php echo html_escape($m['kode_metode']); ?></code></td>
                                        <td>
                                            <?php if ($m['jenis_pengawasan'] === 'Compliance'): ?>
                                                <span class="badge bg-primary">Compliance</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark">Surveillance</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><small class="text-dark fw-semibold"><?php echo html_escape($m['tipe_produk'] ?: '-'); ?></small></td>
                                        <td><strong class="text-dark"><?php echo html_escape($m['kategori'] ?: '-'); ?></strong></td>
                                        <td>
                                            <?php
                                            $is_keamanan = (stripos($m['kelompok_uji'], 'Keamanan') !== false);
                                            ?>
                                            <span class="badge <?php echo $is_keamanan ? 'bg-danger-subtle text-danger border border-danger-subtle' : 'bg-info-subtle text-info-emphasis border border-info-subtle'; ?>">
                                                <?php echo html_escape($m['kelompok_uji'] ?: '-'); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?php echo ($m['bidang'] === 'Kimia' ? 'info text-dark' : 'success'); ?>">
                                                <?php echo html_escape($m['bidang']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="fw-semibold text-primary d-block"><?php echo nl2br(html_escape($m['parameter_uji'])); ?></span>
                                            <?php if (!empty($m['template_id'])): ?>
                                                <span class="badge-lcp-mapped mt-1" title="<?php echo html_escape($m['nama_template']); ?>">
                                                    <i class="bi bi-check-circle-fill"></i> LCP: <?php echo html_escape($m['kode_template']); ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="badge-lcp-unmapped mt-1" role="button" data-bs-toggle="modal" data-bs-target="#modalMapLcpKos<?php echo $m['id']; ?>">
                                                    <i class="bi bi-exclamation-triangle-fill"></i> Belum Dipetakan LCP
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center"><small class="text-muted"><?php echo html_escape($m['halaman'] ?: '-'); ?></small></td>
                                        <td>
                                            <div class="action-btn-group">
                                                <button type="button" class="action-btn btn-sm btn-outline-primary" title="Petakan LCP" data-bs-toggle="modal" data-bs-target="#modalMapLcpKos<?php echo $m['id']; ?>">
                                                    <i class="bi bi-link-45deg"></i>
                                                </button>
                                            </div>

                                            <!-- Modal Pemetaan LCP Kosmetik -->
                                            <div class="modal fade" id="modalMapLcpKos<?php echo $m['id']; ?>" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content border-0 shadow">
                                                        <?php echo form_open('metode/mapping/' . $m['id']); ?>
                                                            <input type="hidden" name="target_table" value="kosmetik">
                                                            <input type="hidden" name="redirect_url" value="<?php echo current_url() . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : ''); ?>">
                                                            <div class="modal-header border-bottom">
                                                                <h6 class="modal-title fw-bold">Petakan LCP &bull; Kosmetik (<?php echo html_escape($m['jenis_pengawasan']); ?>)</h6>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body py-3">
                                                                <div class="mb-3">
                                                                    <small class="text-muted d-block">Parameter Uji:</small>
                                                                    <strong class="text-primary"><?php echo html_escape($m['parameter_uji']); ?></strong>
                                                                    <small class="text-secondary d-block mt-1"><?php echo html_escape($m['kategori']); ?> &bull; <?php echo html_escape($m['kelompok_uji']); ?></small>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="tpl_kos_<?php echo $m['id']; ?>" class="form-label small fw-bold">Pilih Form LCP Resmi:</label>
                                                                    <select name="template_id" id="tpl_kos_<?php echo $m['id']; ?>" class="form-select form-select-sm">
                                                                        <option value="">-- Tandai Belum Dipetakan --</option>
                                                                        <?php foreach ($daftar_lcp as $lcp): ?>
                                                                            <?php if (empty($lcp['kategori']) || $lcp['kategori'] === $m['bidang']): ?>
                                                                                <option value="<?php echo $lcp['id']; ?>" <?php echo ($m['template_id'] == $lcp['id']) ? 'selected' : ''; ?>>
                                                                                    [<?php echo html_escape($lcp['kode_template']); ?>] <?php echo html_escape($lcp['nama_template']); ?>
                                                                                </option>
                                                                            <?php endif; ?>
                                                                        <?php endforeach; ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer border-top">
                                                                <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Tutup</button>
                                                                <button type="submit" class="btn btn-sm btn-primary">Simpan Pemetaan</button>
                                                            </div>
                                                        <?php echo form_close(); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>

                    <?php else: ?>
                        <!-- TABEL DEFAULT: OBAT / HERBAL / SUPLEMEN / SEMUA -->
                        <thead>
                            <tr>
                                <th width="3%">NO</th>
                                <th><?php echo ($komoditi_curr === 'herbal' || $komoditi_curr === 'suplemen') ? 'NAMA SENYAWA TARGET' : 'NAMA ZAT AKTIF'; ?></th>
                                <th><?php echo ($komoditi_curr === 'herbal' || $komoditi_curr === 'suplemen') ? 'KATEGORI / SEDIAAN' : 'JENIS SEDIAAN'; ?></th>
                                <th>BENTUK SEDIAAN</th>
                                <th>PUSTAKA ACUAN</th>
                                <th>PARAMETER UJI KRITIS</th>
                                <th>METODE ANALISA</th>
                                <th>PERSYARATAN</th>
                                <th>BIDANG PENGUJIAN</th>
                                <?php if ($komoditi_curr === 'obat'): ?>
                                    <th>KELAS TERAPI</th>
                                <?php endif; ?>
                                <th width="10%">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($daftar_metode)): ?>
                                <tr>
                                    <td colspan="11" class="text-center py-4 text-muted">
                                        <i class="bi bi-inbox fs-3 d-block mb-2 text-muted"></i>
                                        Belum ada data monografi metode. Gunakan tombol <strong>Import Excel</strong> untuk mengunggah ribuan baris data monografi secara instan.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php $no = 1; foreach ($daftar_metode as $m): ?>
                                    <tr>
                                        <td class="text-center"><?php echo $no++; ?></td>
                                        <td>
                                            <strong class="text-dark d-block"><?php echo html_escape($m['nama_zat_aktif'] ?: $m['nama_metode']); ?></strong>
                                            <small class="text-muted"><code><?php echo html_escape($m['kode_metode']); ?></code></small>
                                        </td>
                                        <td><?php echo html_escape($m['jenis_sediaan'] ?: '-'); ?></td>
                                        <td><?php echo html_escape($m['bentuk_sediaan'] ?: '-'); ?></td>
                                        <td><?php echo html_escape($m['pustaka_acuan'] ?: '-'); ?></td>
                                        <td>
                                            <span class="fw-semibold text-primary d-block"><?php echo html_escape($m['parameter_uji'] ?: '-'); ?></span>
                                            <?php if (!empty($m['template_id'])): ?>
                                                <span class="badge-lcp-mapped mt-1" title="<?php echo html_escape($m['nama_template']); ?>">
                                                    <i class="bi bi-check-circle-fill"></i> LCP: <?php echo html_escape($m['kode_template']); ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="badge-lcp-unmapped mt-1" role="button" data-bs-toggle="modal" data-bs-target="#modalMapLcp<?php echo $m['id']; ?>">
                                                    <i class="bi bi-exclamation-triangle-fill"></i> Belum Dipetakan LCP
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo html_escape($m['metode_analisa'] ?: '-'); ?></td>
                                        <td><small class="text-secondary"><?php echo html_escape($m['persyaratan'] ?: '-'); ?></small></td>
                                        <td>
                                            <span class="badge bg-<?php echo ($m['bidang_pengujian'] === 'Kimia' ? 'info text-dark' : 'success'); ?>">
                                                <?php echo html_escape($m['bidang_pengujian']); ?>
                                            </span>
                                        </td>
                                        <?php if ($komoditi_curr === 'obat'): ?>
                                        <td>
                                            <small class="fw-semibold text-dark"><?php echo html_escape($m['nama_kelas_terapi'] ?: '-'); ?></small>
                                        </td>
                                        <?php endif; ?>
                                        <td>
                                            <div class="action-btn-group">
                                                <button type="button" class="action-btn btn-sm btn-outline-primary" title="Petakan LCP" data-bs-toggle="modal" data-bs-target="#modalMapLcp<?php echo $m['id']; ?>">
                                                    <i class="bi bi-link-45deg"></i>
                                                </button>
                                                <a href="<?php echo site_url('metode/edit/' . $m['id']); ?>" class="action-btn btn-sm btn-outline-secondary" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a href="<?php echo site_url('metode/status/' . $m['id']); ?>" class="action-btn btn-sm btn-outline-warning" title="Ubah Status">
                                                    <i class="bi bi-power"></i>
                                                </a>
                                                <a href="<?php echo site_url('metode/hapus/' . $m['id']); ?>" class="action-btn btn-sm btn-outline-danger" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus metode ini?');">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </div>

                                            <!-- Modal Pemetaan LCP Monografi -->
                                            <div class="modal fade" id="modalMapLcp<?php echo $m['id']; ?>" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content border-0 shadow">
                                                        <?php echo form_open('metode/mapping/' . $m['id']); ?>
                                                            <div class="modal-header border-bottom">
                                                                <h6 class="modal-title fw-bold">Petakan Parameter ke Lembar Catatan Pengujian (LCP)</h6>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body py-3">
                                                                <div class="mb-3">
                                                                    <small class="text-muted d-block">Nama Parameter Uji:</small>
                                                                    <strong><?php echo html_escape($m['parameter_uji']); ?></strong> (Zat: <?php echo html_escape($m['nama_zat_aktif']); ?>)
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="template_id_<?php echo $m['id']; ?>" class="form-label small fw-bold">Pilih Form LCP Resmi:</label>
                                                                    <select name="template_id" id="template_id_<?php echo $m['id']; ?>" class="form-select form-select-sm">
                                                                        <option value="">-- Tandai Belum Dipetakan --</option>
                                                                        <?php foreach ($daftar_lcp as $lcp): ?>
                                                                            <?php if (empty($lcp['kategori']) || $lcp['kategori'] === $m['bidang_pengujian']): ?>
                                                                                <option value="<?php echo $lcp['id']; ?>" <?php echo ($m['template_id'] == $lcp['id']) ? 'selected' : ''; ?>>
                                                                                    [<?php echo html_escape($lcp['kode_template']); ?>] <?php echo html_escape($lcp['nama_template']); ?>
                                                                                </option>
                                                                            <?php endif; ?>
                                                                        <?php endforeach; ?>
                                                                    </select>
                                                                    <small class="text-muted d-block mt-1">Pemetaan ini harus diverifikasi oleh analis laboratorium.</small>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer border-top">
                                                                <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Tutup</button>
                                                                <button type="submit" class="btn btn-sm btn-primary">Simpan Pemetaan LCP</button>
                                                            </div>
                                                        <?php echo form_close(); ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof jQuery !== 'undefined' && $.fn.DataTable) {
            var table = $('#tabel-metode').DataTable({
                language: {
                    search: "Cari Data:",
                    lengthMenu: "Tampilkan _MENU_ baris",
                    info: "Menampilkan _START_ s/d _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 data",
                    zeroRecords: "Tidak ada data yang cocok dengan pencarian",
                    paginate: { first: "Awal", last: "Akhir", next: "Berikutnya", previous: "Sebelumnya" }
                },
                columnDefs: [ { orderable: false, targets: [0, -1] } ],
                order: [],
                pageLength: 10,
                lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                responsive: true
            });

            // Auto-reindex nomor urut kolom 0 secara dinamis saat di-sort atau di-filter
            table.on('order.dt search.dt page.dt', function () {
                let info = table.page.info();
                table.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
                    cell.innerHTML = info.start + i + 1;
                });
            }).draw();
        }
    });
</script>
