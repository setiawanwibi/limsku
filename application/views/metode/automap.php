<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
    .automap-page { width: 100%; padding: 4px 0 32px; color: #1e293b; font-size: 0.85rem; }
    .stat-card { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px 18px; height: 100%; }
    .stat-label { font-size: 0.72rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.04em; color: #64748b; }
    .stat-val { font-size: 1.55rem; font-weight: 700; color: #0f172a; line-height: 1.25; margin-top: 4px; }
    .stat-sub { font-size: 0.75rem; color: #94a3b8; }

    .batch-panel { background: #ffffff; border: 1px solid #e2e8f0; border-left: 3px solid #0284c7; border-radius: 10px; padding: 18px 22px; margin-bottom: 18px; }
    .automap-card { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; overflow: hidden; }
    .automap-header { display: flex; align-items: center; justify-content: space-between; gap: 16px; padding: 16px 20px; border-bottom: 1px solid #e2e8f0; flex-wrap: wrap; }
    .automap-toolbar { padding: 12px 20px; background: #f8fafc; border-bottom: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; }

    .automap-table thead th { background: #f8fafc; color: #475569; font-size: 0.74rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; padding: 10px 14px; border-bottom: 1px solid #e2e8f0; white-space: nowrap; }
    .automap-table tbody td { padding: 12px 14px; font-size: 0.83rem; color: #334155; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }

    .badge-status-auto { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; padding: 2px 8px; border-radius: 6px; font-size: 0.72rem; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; }
    .badge-status-review { background: #fffbeb; color: #b45309; border: 1px solid #fde68a; padding: 2px 8px; border-radius: 6px; font-size: 0.72rem; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; }
    .variasi-tag { display: inline-block; background: #f8fafc; color: #475569; border: 1px solid #e2e8f0; border-radius: 4px; padding: 2px 6px; font-size: 0.72rem; margin: 2px 2px 0 0; }
    .count-chip { display: inline-block; font-size: 0.95rem; font-weight: 700; color: #0f172a; background: #f1f5f9; border: 1px solid #e2e8f0; border-radius: 6px; padding: 4px 10px; min-width: 44px; }

    .filter-tab-btn { font-size: 0.78rem; font-weight: 600; padding: 6px 12px; border-radius: 8px; transition: background-color 0.15s ease, border-color 0.15s ease; border: 1px solid #cbd5e1; background: #ffffff; color: #475569; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; }
    .filter-tab-btn:hover { background: #f1f5f9; color: #0f172a; }
    .filter-tab-btn.active { background: #0284c7; color: #ffffff; border-color: #0284c7; }
    .filter-tab-btn.active .badge { background: rgba(255, 255, 255, 0.2) !important; color: #ffffff !important; }
</style>

<div class="automap-page">

    <!-- Flash Messages -->
    <?php if ($this->session->flashdata('pesan_sukses')): ?>
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            <?php echo $this->session->flashdata('pesan_sukses'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('pesan_gagal')): ?>
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <?php echo $this->session->flashdata('pesan_gagal'); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Summary KPI Cards -->
    <div class="row g-3 mb-3">
        <div class="col-md-3 col-6">
            <div class="stat-card">
                <div class="stat-label">Total Monografi</div>
                <div class="stat-val"><?php echo number_format($stats['total_monografi']); ?></div>
                <div class="stat-sub"><?php echo count($stats['kelompok_kanonis']); ?> kelompok metode</div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-card">
                <div class="stat-label">Cocok Otomatis</div>
                <div class="stat-val text-success"><?php echo number_format($stats['kandidat_otomatis_valid']); ?></div>
                <div class="stat-sub">Siap dipetakan massal</div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-card">
                <div class="stat-label">Perlu Konfirmasi</div>
                <div class="stat-val"><?php echo number_format($stats['perlu_konfirmasi']); ?></div>
                <div class="stat-sub">Titrimetri, Mikrobiologi, AAS, dll</div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="stat-card">
                <div class="stat-label">Sudah Terpetakan</div>
                <div class="stat-val"><?php echo number_format($stats['sudah_terpetakan']); ?> <span class="fs-6 fw-normal text-muted">/ <?php echo number_format($stats['total_monografi']); ?></span></div>
                <div class="stat-sub"><?php echo number_format($stats['belum_terpetakan']); ?> belum memiliki LCP</div>
            </div>
        </div>
    </div>

    <!-- Batch Execution Panel -->
    <?php if ($stats['kandidat_otomatis_valid'] > 0): ?>
        <div class="batch-panel">
            <div class="row align-items-center g-3">
                <div class="col-lg-8">
                    <div class="fw-bold text-dark mb-1" style="font-size: 0.95rem;">
                        Pemetaan Massal ke Form LCP
                    </div>
                    <p class="text-secondary mb-0 small" style="line-height: 1.5;">
                        Sebanyak <strong><?php echo number_format($stats['kandidat_otomatis_valid']); ?> baris data</strong> cocok dengan pemetaan standar Form LCP (seperti KCKT &rarr; KIM-08, Spektro UV &rarr; KIM-05, Disolusi &rarr; KIM-06, Pemerian &rarr; KIM-10) dan dapat diterapkan sekaligus.
                    </p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <?php echo form_open('metode/terapkan_semua_automap', array('class' => 'd-inline-block w-100')); ?>
                        <div class="form-check text-start mb-2" style="font-size: 0.78rem;">
                            <input class="form-check-input" type="checkbox" name="overwrite_manual" value="1" id="ow_all">
                            <label class="form-check-label text-secondary" for="ow_all">
                                Timpa baris yang sudah dipetakan manual
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm w-100 fw-semibold d-flex align-items-center justify-content-center gap-2 py-2" style="border-radius: 8px; background-color: #0284c7; border-color: #0284c7;" onclick="return confirm('Apakah Anda yakin ingin memetakan seluruh <?php echo $stats['kandidat_otomatis_valid']; ?> data monografi yang sudah 100% cocok secara otomatis?');">
                            <i class="bi bi-check2-all"></i>
                            <span>Terapkan <?php echo number_format($stats['kandidat_otomatis_valid']); ?> Pemetaan</span>
                        </button>
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Main Table Card -->
    <div class="automap-card">
        <div class="automap-header">
            <div>
                <h6 class="fw-bold mb-1 text-dark" style="font-size: 1rem;">
                    Daftar Kelompok Parameter &amp; Metode Analisis
                </h6>
                <p class="text-muted mb-0" style="font-size: 0.8rem;">
                    Tinjau kelompok parameter, variasi teks dari Excel, dan form LCP tujuan sebelum diterapkan.
                </p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <a href="<?php echo site_url('metode'); ?>" class="btn btn-outline-secondary btn-sm px-3 fw-semibold" style="border-radius: 8px; font-size: 0.8rem;">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Master Metode
                </a>
            </div>
        </div>

        <!-- Filter Tab Buttons -->
        <div class="automap-toolbar">
            <div class="d-flex align-items-center flex-wrap gap-2">
                <span class="small fw-semibold text-muted me-1" style="font-size: 0.76rem;">Filter:</span>
                <button type="button" class="filter-tab-btn active" onclick="filterKelompok('semua', this)">
                    Semua <span class="badge bg-secondary"><?php echo count($stats['kelompok_kanonis']); ?></span>
                </button>
                <button type="button" class="filter-tab-btn" onclick="filterKelompok('otomatis', this)">
                    Cocok Otomatis
                </button>
                <button type="button" class="filter-tab-btn" onclick="filterKelompok('review', this)">
                    Perlu Review
                </button>
            </div>
            <div class="small text-muted" style="font-size: 0.76rem;">
                Teks asli monografi tidak diubah saat pemetaan diterapkan.
            </div>
        </div>

        <div class="p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle automap-table mb-0" id="tabelKelompok">
                    <thead>
                        <tr>
                            <th width="3%" class="text-center">NO</th>
                            <th width="8%">BIDANG</th>
                            <th width="20%">PARAMETER &amp; METODE</th>
                            <th width="22%">VARIASI TEKS ASLI</th>
                            <th width="8%" class="text-center">JUMLAH</th>
                            <th width="24%">REKOMENDASI FORM LCP</th>
                            <th width="15%" class="text-end">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($stats['kelompok_kanonis'])): ?>
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">Tidak ada data monografi aktif untuk dianalisis.</td>
                            </tr>
                        <?php else: ?>
                            <?php $no = 1; foreach ($stats['kelompok_kanonis'] as $grp): ?>
                                <tr class="row-kelompok" data-status="<?php echo strtolower($grp['status_kandidat']); ?>">
                                    <td class="text-center text-muted"><?php echo $no++; ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo ($grp['bidang'] === 'Kimia' ? 'info text-dark' : 'success'); ?> px-2 py-1">
                                            <?php echo html_escape($grp['bidang']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-dark mb-1" style="font-size: 0.88rem;"><?php echo html_escape($grp['kanonis_param']); ?></div>
                                        <span class="variasi-tag bg-white text-dark">
                                            <?php echo html_escape($grp['kanonis_metode']); ?>
                                        </span>
                                        <div class="mt-1 text-muted" style="font-size: 0.74rem;">
                                            Terpetakan: <strong><?php echo $grp['terpetakan_count']; ?></strong> &middot;
                                            Belum: <strong><?php echo $grp['belum_map_count']; ?></strong>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="mb-2">
                                            <small class="text-muted fw-semibold d-block mb-1" style="font-size: 0.74rem;">Parameter (<?php echo count($grp['variasi_asli']); ?>):</small>
                                            <?php foreach (array_slice($grp['variasi_asli'], 0, 4) as $v): ?>
                                                <span class="variasi-tag"><?php echo html_escape($v); ?></span>
                                            <?php endforeach; ?>
                                            <?php if (count($grp['variasi_asli']) > 4): ?>
                                                <span class="text-muted small">+<?php echo (count($grp['variasi_asli']) - 4); ?> lainnya</span>
                                            <?php endif; ?>
                                        </div>
                                        <?php if (!empty($grp['metode_analisa'])): ?>
                                            <div>
                                                <small class="text-muted fw-semibold d-block mb-1" style="font-size: 0.74rem;">Metode:</small>
                                                <?php foreach (array_slice($grp['metode_analisa'], 0, 3) as $m): ?>
                                                    <span class="variasi-tag bg-white text-secondary"><?php echo html_escape($m ?: '(Kosong)'); ?></span>
                                                <?php endforeach; ?>
                                                <?php if (count($grp['metode_analisa']) > 3): ?>
                                                    <span class="text-muted small">+<?php echo (count($grp['metode_analisa']) - 3); ?> lainnya</span>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <span class="count-chip"><?php echo $grp['total_monografi']; ?></span>
                                        <small class="text-muted d-block mt-1" style="font-size: 0.72rem;">baris</small>
                                    </td>
                                    <td>
                                        <?php if ($grp['status_kandidat'] === 'OTOMATIS'): ?>
                                            <div class="badge-status-auto mb-1">
                                                <i class="bi bi-check-circle-fill"></i> Cocok Standar LCP
                                            </div>
                                            <div class="fw-semibold text-dark">[<?php echo html_escape($grp['target_kode']); ?>] <?php echo html_escape($grp['target_nama']); ?></div>
                                            <small class="text-muted d-block mt-1"><?php echo html_escape($grp['alasan']); ?></small>
                                        <?php else: ?>
                                            <div class="badge-status-review mb-1">
                                                <i class="bi bi-info-circle-fill"></i> Perlu Review
                                            </div>
                                            <?php if (!empty($grp['target_kode']) && $grp['target_kode'] !== '-'): ?>
                                                <div class="fw-semibold text-secondary">[<?php echo html_escape($grp['target_kode']); ?>] <?php echo html_escape($grp['target_nama']); ?> (Rujukan)</div>
                                            <?php else: ?>
                                                <div class="fw-semibold text-danger">Belum Ada Form LCP Khusus</div>
                                            <?php endif; ?>
                                            <small class="text-muted d-block mt-1"><?php echo html_escape($grp['alasan']); ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end">
                                        <!-- Form Persetujuan / Opsi Pemilihan Form LCP Lain -->
                                        <?php echo form_open('metode/terapkan_automap', array('class' => 'd-inline')); ?>
                                            <input type="hidden" name="group_key" value="<?php echo html_escape($grp['key']); ?>">
                                            
                                            <div class="mb-2">
                                                <select name="template_id" class="form-select form-select-sm" required style="font-size: 0.78rem;">
                                                    <option value="">-- Pilih Form LCP --</option>
                                                    <?php foreach ($daftar_lcp as $lcp): ?>
                                                        <?php if ($lcp['kategori'] === $grp['bidang'] && $lcp['status'] === 'Aktif'): ?>
                                                            <option value="<?php echo $lcp['id']; ?>" <?php echo ($grp['kandidat_template_id'] == $lcp['id']) ? 'selected' : ''; ?>>
                                                                [<?php echo html_escape($lcp['kode_template']); ?>] <?php echo html_escape($lcp['nama_template']); ?>
                                                            </option>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <div class="form-check text-start mb-2" style="font-size: 0.72rem;">
                                                <input class="form-check-input" type="checkbox" name="overwrite_manual" value="1" id="ow_<?php echo md5($grp['key']); ?>">
                                                <label class="form-check-label text-muted" for="ow_<?php echo md5($grp['key']); ?>">
                                                    Timpa baris manual
                                                </label>
                                            </div>

                                            <button type="submit" class="btn btn-sm <?php echo ($grp['status_kandidat'] === 'OTOMATIS' ? 'btn-primary' : 'btn-outline-secondary'); ?> w-100 fw-semibold" style="border-radius: 6px;" onclick="return confirm('Apakah Anda ingin memetakan kelompok \'<?php echo addslashes($grp['kanonis_param'] . ' (' . $grp['kanonis_metode'] . ')'); ?>\' ke template LCP terpilih untuk <?php echo $grp['total_monografi']; ?> monografi?');">
                                                <i class="bi bi-check-lg me-1"></i> <?php echo ($grp['status_kandidat'] === 'OTOMATIS' ? 'Setujui Kelompok' : 'Petakan Kelompok'); ?>
                                            </button>
                                        <?php echo form_close(); ?>
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
function filterKelompok(tipe, btnEl) {
    document.querySelectorAll('.filter-tab-btn').forEach(b => b.classList.remove('active'));
    btnEl.classList.add('active');

    const rows = document.querySelectorAll('.row-kelompok');
    rows.forEach(r => {
        const st = r.getAttribute('data-status');
        if (tipe === 'semua') {
            r.style.display = '';
        } else if (tipe === 'otomatis') {
            r.style.display = (st === 'otomatis') ? '' : 'none';
        } else if (tipe === 'review') {
            r.style.display = (st !== 'otomatis') ? '' : 'none';
        }
    });
}
</script>
