<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
    .automap-page { width: 100%; padding: 4px 0 24px; color: #294257; }
    .stat-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 18px 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.03); }
    .stat-val { font-size: 1.8rem; font-weight: 700; line-height: 1.2; margin-top: 4px; }
    .stat-label { font-size: 0.8rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #64748b; }
    .automap-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; box-shadow: 0 4px 16px rgba(41, 66, 87, 0.05); overflow: hidden; }
    .automap-header { display: flex; align-items: center; justify-content: space-between; gap: 16px; padding: 18px 24px; border-bottom: 1px solid #e8eef1; }
    .automap-table thead th { background: #f8fafc; color: #475569; font-size: 0.76rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; padding: 12px 14px; border-bottom: 1px solid #e2e8f0; white-space: nowrap; }
    .automap-table tbody td { padding: 14px 14px; font-size: 0.83rem; color: #334e68; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    .badge-status-auto { background: #dcfce7; color: #15803d; border: 1px solid #86efac; padding: 4px 10px; border-radius: 6px; font-size: 0.74rem; font-weight: 700; display: inline-flex; align-items: center; gap: 4px; }
    .badge-status-review { background: #fef9c3; color: #a16207; border: 1px solid #fde047; padding: 4px 10px; border-radius: 6px; font-size: 0.74rem; font-weight: 700; display: inline-flex; align-items: center; gap: 4px; }
    .variasi-tag { display: inline-block; background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; border-radius: 4px; padding: 2px 6px; font-size: 0.72rem; margin: 2px 2px; }
</style>

<div class="automap-page">

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

    <!-- Summary KPI Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="stat-card border-start border-primary border-4">
                <div class="stat-label">Total Monografi Aktif</div>
                <div class="stat-val text-primary"><?php echo number_format($stats['total_monografi']); ?></div>
                <small class="text-muted"><?php echo count($stats['kelompok_kanonis']); ?> kelompok parameter kanonis</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card border-start border-success border-4">
                <div class="stat-label">Kandidat Otomatis Valid</div>
                <div class="stat-val text-success"><?php echo number_format($stats['kandidat_otomatis_valid']); ?></div>
                <small class="text-muted">Siap disetujui &amp; dipetakan massal</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card border-start border-warning border-4">
                <div class="stat-label">Perlu Konfirmasi Admin</div>
                <div class="stat-val text-warning"><?php echo number_format($stats['perlu_konfirmasi']); ?></div>
                <small class="text-muted">Parameter ambigu / butuh review LCP</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card border-start border-info border-4">
                <div class="stat-label">Status Terpetakan Saat Ini</div>
                <div class="stat-val text-info"><?php echo number_format($stats['sudah_terpetakan']); ?> / <?php echo number_format($stats['total_monografi']); ?></div>
                <small class="text-muted"><?php echo number_format($stats['belum_terpetakan']); ?> baris belum dipetakan</small>
            </div>
        </div>
    </div>

    <!-- Main Card -->
    <div class="automap-card">
        <div class="automap-header">
            <div>
                <h5 class="fw-bold mb-1 text-dark">
                    <i class="bi bi-diagram-3 me-2 text-primary"></i>
                    Analisis &amp; Persetujuan Auto-Mapping LCP (Massal)
                </h5>
                <p class="text-muted mb-0 small">
                    Parameter monografi telah dinormalisasi menjadi kelompok kanonis. Admin TI dapat meninjau variasi teks asli, metode analisis, dan kandidat LCP aktif sebelum menyetujui pemetaan massal.
                </p>
            </div>
            <div>
                <a href="<?php echo site_url('metode'); ?>" class="btn btn-outline-secondary btn-sm px-3 py-2 fw-semibold">
                    &larr; Kembali ke Master Metode
                </a>
            </div>
        </div>

        <div class="p-3 bg-light border-bottom">
            <div class="d-flex align-items-center gap-2 small text-muted">
                <i class="bi bi-shield-check text-success fs-5"></i>
                <span><strong>Prinsip Integritas Laboratorium:</strong> Pemetaan LCP hanya diterapkan ke LCP aktif dari bidang yang cocok (Kimia/Mikro). Histori teks asli monografi dan pemetaan manual sebelumnya tetap terlindungi.</span>
            </div>
        </div>

        <div class="p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle automap-table mb-0">
                    <thead>
                        <tr>
                            <th width="3%">NO</th>
                            <th width="10%">BIDANG</th>
                            <th width="18%">PARAMETER KANONIS</th>
                            <th width="22%">VARIASI TEKS ASLI &amp; METODE ANALISA</th>
                            <th width="8%" class="text-center">MONOGRAFI TERDAMPAK</th>
                            <th width="24%">KANDIDAT LCP TUJUAN (AKTIF)</th>
                            <th width="15%" class="text-end">PERSETUJUAN ADMIN TI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($stats['kelompok_kanonis'])): ?>
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">Tidak ada data monografi aktif untuk dianalisis.</td>
                            </tr>
                        <?php else: ?>
                            <?php $no = 1; foreach ($stats['kelompok_kanonis'] as $grp): ?>
                                <tr>
                                    <td class="text-center"><?php echo $no++; ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo ($grp['bidang'] === 'Kimia' ? 'info text-dark' : 'success'); ?> px-2 py-1">
                                            <?php echo html_escape($grp['bidang']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <strong class="text-dark d-block fs-6"><?php echo html_escape($grp['kanonis']); ?></strong>
                                        <small class="text-muted d-block mt-1">
                                            Sudah map: <span class="badge bg-success-subtle text-success"><?php echo $grp['terpetakan_count']; ?></span> | 
                                            Belum: <span class="badge bg-warning-subtle text-warning-emphasis"><?php echo $grp['belum_map_count']; ?></span>
                                        </small>
                                    </td>
                                    <td>
                                        <div class="mb-1">
                                            <small class="text-muted fw-bold d-block">Variasi Penulisan Asli (<?php echo count($grp['variasi_asli']); ?>):</small>
                                            <?php foreach (array_slice($grp['variasi_asli'], 0, 5) as $v): ?>
                                                <span class="variasi-tag"><?php echo html_escape($v); ?></span>
                                            <?php endforeach; ?>
                                            <?php if (count($grp['variasi_asli']) > 5): ?>
                                                <span class="text-muted small">+<?php echo (count($grp['variasi_asli']) - 5); ?> variasi</span>
                                            <?php endif; ?>
                                        </div>
                                        <?php if (!empty($grp['metode_analisa'])): ?>
                                            <div>
                                                <small class="text-muted fw-bold d-block">Metode Analisa Terdeteksi:</small>
                                                <small class="text-secondary"><?php echo html_escape(implode(', ', array_slice($grp['metode_analisa'], 0, 3))); ?><?php echo count($grp['metode_analisa']) > 3 ? '...' : ''; ?></small>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary fs-6 px-3 py-2">
                                            <?php echo $grp['total_monografi']; ?>
                                        </span>
                                        <small class="text-muted d-block mt-1">Baris</small>
                                    </td>
                                    <td>
                                        <?php if ($grp['status_kandidat'] === 'OTOMATIS'): ?>
                                            <div class="badge-status-auto mb-1">
                                                <i class="bi bi-check-circle-fill"></i> Sesuai Standar LCP
                                            </div>
                                            <strong class="d-block text-dark">[<?php echo html_escape($grp['target_kode']); ?>] <?php echo html_escape($grp['target_nama']); ?></strong>
                                            <small class="text-muted"><?php echo html_escape($grp['alasan']); ?></small>
                                        <?php else: ?>
                                            <div class="badge-status-review mb-1">
                                                <i class="bi bi-exclamation-triangle-fill"></i> Perlu Konfirmasi
                                            </div>
                                            <?php if (!empty($grp['target_kode']) && $grp['target_kode'] !== '-'): ?>
                                                <strong class="d-block text-secondary">[<?php echo html_escape($grp['target_kode']); ?>] <?php echo html_escape($grp['target_nama']); ?> (Rujukan)</strong>
                                            <?php else: ?>
                                                <strong class="d-block text-danger">Belum Ada Template LCP Khusus</strong>
                                            <?php endif; ?>
                                            <small class="text-muted"><?php echo html_escape($grp['alasan']); ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end">
                                        <!-- Form Persetujuan Admin TI Massal -->
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

                                            <button type="submit" class="btn btn-sm <?php echo ($grp['status_kandidat'] === 'OTOMATIS' ? 'btn-success' : 'btn-outline-primary'); ?> w-100 fw-semibold" onclick="return confirm('Apakah Anda menyetujui pemetaan massal kelompok \'<?php echo addslashes($grp['kanonis']); ?>\' ke template LCP terpilih untuk seluruh <?php echo $grp['total_monografi']; ?> monografi?');">
                                                <i class="bi bi-check-lg me-1"></i> Setujui &amp; Petakan
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
