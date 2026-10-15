<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
    .preview-page { width: 100%; padding: 4px 0 24px; color: #294257; }
    .preview-card { width: 100%; background: #ffffff; border: 1px solid #e0e8ed; border-radius: 12px; box-shadow: 0 4px 16px rgba(41, 66, 87, 0.05); overflow: hidden; }
    .preview-header { padding: 18px 24px; border-bottom: 1px solid #e8eef1; background: #ffffff; }
    .preview-body { padding: 22px 24px; }
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 14px; margin-bottom: 22px; }
    .stat-card { padding: 14px 16px; border-radius: 10px; border: 1px solid #e2e8f0; background: #f8fafc; }
    .stat-card.valid { border-color: #b7eb8f; background: #f6ffed; }
    .stat-card.dup { border-color: #ffe58f; background: #fffbe6; }
    .stat-card.err { border-color: #ffa39e; background: #fff1f0; }
    .stat-val { font-size: 1.3rem; font-weight: 700; color: #2d3748; line-height: 1.2; }
    .stat-lbl { font-size: 0.77rem; color: #718096; margin-top: 4px; }
    .preview-table { font-size: 0.82rem; }
    .preview-table th { background: #f7fafc; padding: 10px 12px; font-size: 0.76rem; text-transform: uppercase; border-bottom: 1px solid #e2e8f0; }
    .preview-table td { padding: 10px 12px; border-bottom: 1px solid #edf2f7; vertical-align: middle; }
    .actions-bar { display: flex; align-items: center; justify-content: space-between; gap: 16px; padding-top: 18px; margin-top: 22px; border-top: 1px solid #edf2f7; }
</style>

<div class="preview-page">
    <div class="preview-card">
        <div class="preview-header">
            <h5 class="fw-bold text-dark mb-1"><i class="bi bi-ui-checks text-primary me-2"></i> Preview &amp; Validasi Monografi Metode</h5>
            <small class="text-muted">Periksa ringkasan analisis sebelum mengonfirmasi penyimpanan append-only ke database.</small>
        </div>

        <div class="preview-body">
            <!-- Statistik Ringkasan -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-val"><?php echo number_format($total_read); ?></div>
                    <div class="stat-lbl">Total Baris Dibaca</div>
                </div>
                <div class="stat-card valid">
                    <div class="stat-val text-success"><?php echo number_format(count($valid_rows)); ?></div>
                    <div class="stat-lbl">Baris Baru Valid (Append)</div>
                </div>
                <div class="stat-card dup">
                    <div class="stat-val text-warning"><?php echo number_format(count($duplicate_rows)); ?></div>
                    <div class="stat-lbl">Baris Duplikat (Dilewati)</div>
                </div>
                <div class="stat-card err">
                    <div class="stat-val text-danger"><?php echo number_format(count($invalid_rows)); ?></div>
                    <div class="stat-lbl">Baris Gagal Validasi</div>
                </div>
            </div>

            <!-- Ringkasan Deteksi Header Kelas Terapi -->
            <div class="card border-0 bg-light p-3 mb-4">
                <h6 class="fw-bold text-dark mb-2"><i class="bi bi-diagram-3 text-primary me-1"></i> Kelas Terapi yang Terdeteksi dari Judul Header (<?php echo count($kt_summary); ?>):</h6>
                <div class="table-responsive bg-white rounded border">
                    <table class="table table-sm mb-0 small">
                        <thead>
                            <tr>
                                <th>Nama Kelas Terapi Terdeteksi</th>
                                <th width="35%">Status Resolusi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($kt_summary as $kt): ?>
                                <tr>
                                    <td><strong><?php echo html_escape($kt['nama']); ?></strong></td>
                                    <td>
                                        <span class="badge bg-<?php echo ($kt['status'] === 'Sudah Ada di Database') ? 'secondary' : 'primary'; ?>">
                                            <?php echo html_escape($kt['status']); ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Laporan Duplikat / Error (Jika Ada) -->
            <?php if (!empty($duplicate_rows) || !empty($invalid_rows)): ?>
                <div class="alert alert-warning py-3 px-3 mb-4">
                    <h6 class="fw-bold mb-2 text-dark"><i class="bi bi-exclamation-triangle-fill text-warning me-1"></i> Rincian Baris yang Dilewati / Ditolak (Append-Only):</h6>
                    <ul class="mb-0 small text-dark">
                        <?php foreach (array_slice($duplicate_rows, 0, 10) as $d): ?>
                            <li><strong>Baris Excel <?php echo $d['excel_row']; ?><?php echo !empty($d['no_sumber']) ? ' (NO: ' . html_escape($d['no_sumber']) . ')' : ''; ?>:</strong> <?php echo html_escape($d['alasan']); ?> [Zat: <?php echo html_escape($d['nama_zat_aktif']); ?>]</li>
                        <?php endforeach; ?>
                        <?php foreach (array_slice($invalid_rows, 0, 10) as $inv): ?>
                            <li><strong>Baris Excel <?php echo $inv['excel_row']; ?><?php echo !empty($inv['no_sumber']) ? ' (NO: ' . html_escape($inv['no_sumber']) . ')' : ''; ?>:</strong> <?php echo html_escape($inv['alasan']); ?></li>
                        <?php endforeach; ?>
                        <?php $sisa = (count($duplicate_rows) + count($invalid_rows)) - 20; ?>
                        <?php if ($sisa > 0): ?>
                            <li class="fst-italic text-muted">...dan <?php echo $sisa; ?> baris bermasalah lainnya.</li>
                        <?php endif; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- Preview Data 25 Baris Pertama -->
            <div class="card border-0 bg-light p-3 mb-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h6 class="fw-bold mb-0 text-dark">Preview Monografi (Maksimal 25 baris pertama):</h6>
                    <span class="badge bg-success"><?php echo count($valid_rows); ?> baris siap disimpan</span>
                </div>

                <div class="table-responsive bg-white rounded border">
                    <table class="table table-hover preview-table mb-0 align-middle">
                        <thead>
                            <tr>
                                <th>BARIS EXCEL</th>
                                <th>NO</th>
                                <th>NAMA ZAT AKTIF</th>
                                <th>JENIS SEDIAAN</th>
                                <th>BENTUK SEDIAAN</th>
                                <th>PUSTAKA ACUAN</th>
                                <th>PARAMETER UJI KRITIS SESUAI MONOGRAFI</th>
                                <th>METODE ANALISA</th>
                                <th>PERSYARATAN</th>
                                <th>BIDANG PENGUJIAN</th>
                                <th>KELAS TERAPI</th>
                                <th>STATUS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($valid_rows) && empty($duplicate_rows) && empty($invalid_rows)): ?>
                                <tr>
                                    <td colspan="12" class="text-center py-4 text-muted">Tidak ada data monografi yang terbaca dari file Excel.</td>
                                </tr>
                            <?php else: ?>
                                <?php 
                                    $sample_display = array_merge(
                                        array_slice($valid_rows, 0, 20),
                                        array_slice($duplicate_rows, 0, 5),
                                        array_slice($invalid_rows, 0, 5)
                                    );
                                    foreach ($sample_display as $row_item): 
                                ?>
                                    <tr>
                                        <td><code>Baris <?php echo $row_item['excel_row']; ?></code></td>
                                        <td class="text-center"><?php echo html_escape($row_item['no_sumber'] ?: '-'); ?></td>
                                        <td><strong><?php echo html_escape($row_item['nama_zat_aktif'] ?: '-'); ?></strong></td>
                                        <td><?php echo html_escape($row_item['jenis_sediaan'] ?: '-'); ?></td>
                                        <td><?php echo html_escape($row_item['bentuk_sediaan'] ?: '-'); ?></td>
                                        <td><?php echo html_escape($row_item['pustaka_acuan'] ?: '-'); ?></td>
                                        <td><span class="text-primary fw-semibold"><?php echo html_escape($row_item['parameter_uji'] ?: '-'); ?></span></td>
                                        <td><?php echo html_escape($row_item['metode_analisa'] ?: '-'); ?></td>
                                        <td><small class="text-muted"><?php echo html_escape($row_item['persyaratan'] ?: '-'); ?></small></td>
                                        <td>
                                            <span class="badge bg-<?php echo ($row_item['bidang_pengujian'] === 'Kimia' ? 'info text-dark' : 'success'); ?>">
                                                <?php echo html_escape($row_item['bidang_pengujian']); ?>
                                            </span>
                                        </td>
                                        <td><small class="fw-semibold text-dark"><?php echo html_escape($row_item['raw_kelas_terapi'] ?: '-'); ?></small></td>
                                        <td>
                                            <?php if ($row_item['status'] === 'Valid'): ?>
                                                <span class="badge bg-success" title="<?php echo html_escape($row_item['alasan']); ?>">Valid</span>
                                            <?php elseif (strpos($row_item['status'], 'Duplikat') !== FALSE): ?>
                                                <span class="badge bg-warning text-dark" title="<?php echo html_escape($row_item['alasan']); ?>">Duplikat (Lewati)</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger" title="<?php echo html_escape($row_item['alasan']); ?>">Invalid</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tombol Konfirmasi -->
            <div class="actions-bar">
                <a href="<?php echo site_url('metode/import'); ?>" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Batal &amp; Unggah Ulang
                </a>

                <?php if (!empty($valid_rows)): ?>
                    <?php echo form_open('metode/konfirmasi_import', array('class' => 'm-0')); ?>
                        <button type="submit" class="btn btn-sm btn-success fw-bold px-4" onclick="return confirm('Apakah Anda yakin ingin mengimpor <?php echo count($valid_rows); ?> data metode monografi baru ke database? Aturan append-only akan diterapkan.');">
                            <i class="bi bi-check-lg me-1"></i> Konfirmasi &amp; Simpan Append-Only
                        </button>
                    <?php echo form_close(); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
