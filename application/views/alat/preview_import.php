<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
    .preview-import-page {
        width: 100%;
        padding: 4px 0 24px;
        color: #294257;
    }

    .preview-card {
        width: 100%;
        background: #ffffff;
        border: 1px solid #e0e8ed;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(41, 66, 87, 0.05);
        overflow: hidden;
    }

    .preview-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 18px 24px;
        border-bottom: 1px solid #e8eef1;
        background: #ffffff;
    }

    .preview-header-heading {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .preview-header-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        background: #ebf8ff;
        color: #2b6cb0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .preview-header-title {
        margin: 0;
        color: #294257;
        font-size: 1.1rem;
        font-weight: 700;
    }

    .preview-header-subtitle {
        margin: 4px 0 0;
        color: #7b8b98;
        font-size: 0.82rem;
    }

    .preview-body {
        padding: 22px 24px;
    }

    .summary-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 22px;
    }

    .stat-box {
        padding: 16px;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .stat-box-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }

    .stat-box.success {
        border-color: #c6f6d5;
        background: #f0fff4;
    }
    .stat-box.success .stat-box-icon {
        background: #c6f6d5;
        color: #276749;
    }

    .stat-box.danger {
        border-color: #fed7d7;
        background: #fff5f5;
    }
    .stat-box.danger .stat-box-icon {
        background: #fed7d7;
        color: #9b2c2c;
    }

    .stat-box.info {
        border-color: #bee3f8;
        background: #ebf8ff;
    }
    .stat-box.info .stat-box-icon {
        background: #bee3f8;
        color: #2c5282;
    }

    .stat-box-number {
        font-size: 1.25rem;
        font-weight: 700;
        color: #2d3748;
        line-height: 1.2;
    }

    .stat-box-label {
        font-size: 0.78rem;
        color: #718096;
    }

    .preview-table {
        margin-bottom: 0;
        font-size: 0.83rem;
    }

    .preview-table th {
        background: #f7fafc;
        color: #4a5568;
        font-size: 0.76rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        border-bottom: 1px solid #e2e8f0;
        padding: 10px 12px;
    }

    .preview-table td {
        padding: 10px 12px;
        border-bottom: 1px solid #edf2f7;
        vertical-align: middle;
    }

    .preview-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding-top: 20px;
        margin-top: 22px;
        border-top: 1px solid #edf2f7;
    }

    .btn-back-upload {
        padding: 8px 18px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #4a5568;
        background: #edf2f7;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-back-upload:hover {
        background: #e2e8f0;
        color: #2d3748;
    }

    .btn-confirm-import {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 24px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #ffffff;
        background: #159b98;
        border: 1px solid #159b98;
        border-radius: 8px;
        box-shadow: 0 2px 6px rgba(21, 155, 152, 0.25);
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-confirm-import:hover {
        background: #118380;
        border-color: #118380;
    }
</style>

<div class="preview-import-page">

    <div class="preview-card">
        <div class="preview-header">
            <div class="preview-header-heading">
                <div class="preview-header-icon">
                    <i class="bi bi-ui-checks"></i>
                </div>
                <div>
                    <h5 class="preview-header-title">Hasil Analisis &amp; Validasi File Excel</h5>
                    <p class="preview-header-subtitle">
                        Periksa rincian data sebelum menyetujui penyimpanan ke dalam database inventaris LIMSKU.
                    </p>
                </div>
            </div>
        </div>

        <div class="preview-body">

            <!-- Summary Stats -->
            <div class="summary-stats">
                <div class="stat-box info">
                    <div class="stat-box-icon">
                        <i class="bi bi-file-earmark-spreadsheet"></i>
                    </div>
                    <div>
                        <div class="stat-box-number"><?php echo number_format($total_read); ?></div>
                        <div class="stat-box-label">Total Baris Terbaca</div>
                    </div>
                </div>

                <div class="stat-box success">
                    <div class="stat-box-icon">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div>
                        <div class="stat-box-number"><?php echo number_format(count($valid_rows)); ?></div>
                        <div class="stat-box-label">Siap Di-import (Valid)</div>
                    </div>
                </div>

                <div class="stat-box <?php echo (!empty($invalid_rows)) ? 'danger' : ''; ?>">
                    <div class="stat-box-icon">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                    <div>
                        <div class="stat-box-number"><?php echo number_format(count($invalid_rows)); ?></div>
                        <div class="stat-box-label">Baris Invalid / Dilewati</div>
                    </div>
                </div>
            </div>

            <!-- Error List If Any -->
            <?php if (!empty($invalid_rows)): ?>
                <div class="alert alert-warning py-3 px-3 mb-4">
                    <h6 class="fw-bold mb-2 text-dark">
                        <i class="bi bi-exclamation-triangle-fill text-warning me-1"></i> 
                        Terdapat <?php echo count($invalid_rows); ?> baris yang dilewati karena tidak memenuhi validasi:
                    </h6>
                    <ul class="mb-0 small text-dark">
                        <?php foreach (array_slice($invalid_rows, 0, 10) as $err): ?>
                            <li><strong>Baris <?php echo $err['row']; ?>:</strong> <?php echo html_escape($err['alasan']); ?></li>
                        <?php endforeach; ?>
                        <?php if (count($invalid_rows) > 10): ?>
                            <li class="fst-italic text-muted">...dan <?php echo count($invalid_rows) - 10; ?> baris lainnya.</li>
                        <?php endif; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- Preview Data Table -->
            <div class="card border-0 bg-light p-3 mb-3">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <h6 class="fw-bold mb-0 text-dark">
                        <i class="bi bi-eye me-1 text-primary"></i> 
                        Preview Data (Menampilkan maksimal 20 baris pertama):
                    </h6>
                    <span class="badge bg-secondary"><?php echo count($valid_rows); ?> baris siap disimpan</span>
                </div>

                <div class="table-responsive bg-white rounded border">
                    <table class="table table-hover preview-table align-middle">
                        <thead>
                            <tr>
                                <th width="4%">No</th>
                                <th>Nama Barang</th>
                                <th>Merk/Tipe</th>
                                <th>Tgl Perolehan</th>
                                <th>Kondisi ADM</th>
                                <th>Kode Ruangan</th>
                                <th>Nama Ruangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($valid_rows)): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">
                                        Tidak ada data yang valid untuk disimpan.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php $no = 1; foreach (array_slice($valid_rows, 0, 20) as $row): ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td class="fw-semibold text-dark"><?php echo html_escape($row['nama_barang']); ?></td>
                                        <td><?php echo html_escape($row['merk_tipe'] ?: '-'); ?></td>
                                        <td><?php echo html_escape($row['tanggal_perolehan'] ? date('d/m/Y', strtotime($row['tanggal_perolehan'])) : '-'); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo ($row['kondisi_adm'] === 'Baik') ? 'success' : 'warning'; ?>">
                                                <?php echo html_escape($row['kondisi_adm']); ?>
                                            </span>
                                        </td>
                                        <td><code><?php echo html_escape($row['kode_ruangan'] ?: '-'); ?></code></td>
                                        <td><?php echo html_escape($row['nama_ruangan'] ?: '-'); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Action buttons -->
            <div class="preview-actions">
                <a href="<?php echo site_url('alat/import'); ?>" class="btn-back-upload">
                    <i class="bi bi-arrow-left me-1"></i> Upload Ulang File
                </a>

                <?php if (!empty($valid_rows)): ?>
                    <?php echo form_open('alat/konfirmasi_import', array('class' => 'm-0')); ?>
                        <button type="submit" class="btn-confirm-import" onclick="return confirm('Apakah Anda yakin ingin mengimpor <?php echo count($valid_rows); ?> data alat ini ke database? Kode barang ALT-xxxx akan dibuat otomatis.');">
                            <i class="bi bi-check-lg me-1"></i> Konfirmasi &amp; Simpan ke Database
                        </button>
                    <?php echo form_close(); ?>
                <?php endif; ?>
            </div>

        </div>
    </div>

</div>
