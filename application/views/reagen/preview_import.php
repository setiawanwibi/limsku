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
        background: #e6f7ff;
        color: #096dd9;
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
        border-color: #bbf7d0;
        background: #f0fdf4;
    }
    .stat-box.success .stat-box-icon {
        background: #dcfce7;
        color: #166534;
    }

    .stat-box.warning {
        border-color: #fde68a;
        background: #fffbeb;
    }
    .stat-box.warning .stat-box-icon {
        background: #fef3c7;
        color: #b45309;
    }

    .stat-box.danger {
        border-color: #fecaca;
        background: #fef2f2;
    }
    .stat-box.danger .stat-box-icon {
        background: #fee2e2;
        color: #b91c1c;
    }

    .stat-val {
        font-size: 1.35rem;
        font-weight: 700;
        line-height: 1.1;
        color: #1e293b;
    }

    .stat-lbl {
        font-size: 0.78rem;
        color: #64748b;
        margin-top: 2px;
    }

    .duplicate-decision-card {
        padding: 16px 18px;
        background: #fffdf5;
        border: 1px solid #fed7aa;
        border-radius: 10px;
        margin-bottom: 22px;
    }

    .preview-table {
        margin-bottom: 0;
        font-size: 0.82rem;
    }

    .preview-table thead th {
        background: #f8fafc;
        color: #475569;
        font-weight: 700;
        font-size: 0.76rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 10px 12px;
        white-space: nowrap;
    }

    .preview-table tbody td {
        padding: 10px 12px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }

    .dup-row {
        background-color: #fffbeb !important;
    }

    .form-actions-sticky {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-top: 24px;
        padding-top: 18px;
        border-top: 1px solid #e2e8f0;
    }

    .btn-confirm-import {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 24px;
        font-size: 0.88rem;
        font-weight: 600;
        color: #ffffff;
        background: #096dd9;
        border: 1px solid #096dd9;
        border-radius: 8px;
        box-shadow: 0 2px 6px rgba(9, 109, 217, 0.25);
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-confirm-import:hover {
        background: #085fc4;
        border-color: #085fc4;
    }

    .btn-cancel-preview {
        padding: 9px 18px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #475569;
        background: #f1f5f9;
        border: 1px solid #cbd5e0;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-cancel-preview:hover {
        background: #e2e8f0;
        color: #1e293b;
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
                    <h5 class="preview-header-title">Pratinjau &amp; Validasi Impor Reagen</h5>
                    <p class="preview-header-subtitle">
                        Periksa hasil pembacaan baris spreadsheet sebelum mengonfirmasi penyimpanan ke database.
                    </p>
                </div>
            </div>
        </div>

        <div class="preview-body">

            <!-- Summary Stats -->
            <div class="summary-stats">
                <div class="stat-box">
                    <div class="stat-box-icon bg-light text-primary">
                        <i class="bi bi-file-text"></i>
                    </div>
                    <div>
                        <div class="stat-val"><?php echo $total_read; ?></div>
                        <div class="stat-lbl">Total Baris Dibaca</div>
                    </div>
                </div>

                <div class="stat-box success">
                    <div class="stat-box-icon">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div>
                        <div class="stat-val text-success"><?php echo count($valid_rows); ?></div>
                        <div class="stat-lbl">Baris Valid (Siap Impor)</div>
                    </div>
                </div>

                <div class="stat-box warning">
                    <div class="stat-box-icon">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                    <div>
                        <div class="stat-val text-warning"><?php echo count($duplicate_rows); ?></div>
                        <div class="stat-lbl">Terdeteksi Duplikat</div>
                    </div>
                </div>

                <div class="stat-box danger">
                    <div class="stat-box-icon">
                        <i class="bi bi-x-circle"></i>
                    </div>
                    <div>
                        <div class="stat-val text-danger"><?php echo count($invalid_rows); ?></div>
                        <div class="stat-lbl">Baris Gagal / Tidak Valid</div>
                    </div>
                </div>
            </div>

            <!-- Bagian Baris Tidak Valid (Jika Ada) -->
            <?php if (!empty($invalid_rows)): ?>
                <div class="alert alert-danger border-0 shadow-sm mb-4">
                    <h6 class="fw-bold mb-2">
                        <i class="bi bi-exclamation-octagon-fill me-1"></i>
                        Ditemukan <?php echo count($invalid_rows); ?> Baris Tidak Valid (Akan Dilewati Otomatis):
                    </h6>
                    <div class="table-responsive" style="max-height: 200px; overflow-y: auto;">
                        <table class="table table-sm table-bordered bg-white mb-0">
                            <thead>
                                <tr class="table-light">
                                    <th width="12%">Baris Excel</th>
                                    <th>Alasan Kegagalan Validasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($invalid_rows as $inv): ?>
                                    <tr>
                                        <td class="font-monospace fw-bold text-center">Baris <?php echo $inv['row']; ?></td>
                                        <td class="text-danger"><?php echo html_escape($inv['alasan']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (!empty($valid_rows)): ?>

                <?php echo form_open('reagen/konfirmasi_import', array('id' => 'form-konfirmasi-import')); ?>

                    <!-- Opsi Penanganan Duplikasi Jika Ada Data Duplikat -->
                    <?php if (!empty($duplicate_rows)): ?>
                        <div class="duplicate-decision-card">
                            <h6 class="fw-bold text-dark mb-1">
                                <i class="bi bi-shield-exclamation text-warning me-1"></i>
                                Perlakuan Terhadap <?php echo count($duplicate_rows); ?> Data yang Sudah Ada di Sistem:
                            </h6>
                            <p class="text-muted small mb-2">
                                Identifikasi duplikasi didasarkan pada kecocokan <strong>Kode Barang</strong>, <strong>Nomor Katalog</strong>, atau kombinasi <strong>Nama Barang &amp; Merk</strong>.
                            </p>
                            <div class="d-flex flex-wrap gap-4 mt-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="duplicate_action" id="dup_skip" value="skip" checked>
                                    <label class="form-check-label fw-semibold" for="dup_skip">
                                        Lewati (Skip) data duplikat — pertahankan data lama di database
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="duplicate_action" id="dup_update" value="update">
                                    <label class="form-check-label fw-semibold" for="dup_update">
                                        Perbarui (Update) data lama dengan nilai terbaru dari file Excel
                                    </label>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <input type="hidden" name="duplicate_action" value="skip">
                    <?php endif; ?>

                    <!-- Tabel Pratinjau 8 Kolom Standar -->
                    <div class="table-responsive border rounded" style="max-height: 480px; overflow-y: auto;">
                        <table class="table table-hover preview-table align-middle">
                            <thead class="sticky-top">
                                <tr>
                                    <th width="4%" class="text-center">No</th>
                                    <th>Kode Barang</th>
                                    <th>Katalog</th>
                                    <th>Nama Barang</th>
                                    <th>Merk</th>
                                    <th>Stok</th>
                                    <th>Jenis Barang</th>
                                    <th>Expired Date</th>
                                    <th>Status Data</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; foreach ($valid_rows as $vr): ?>
                                    <tr class="<?php echo $vr['is_duplicate'] ? 'dup-row' : ''; ?>">
                                        <td class="text-center text-muted"><?php echo $no++; ?></td>
                                        <td>
                                            <?php if (!empty($vr['kode_barang'])): ?>
                                                <span class="badge bg-secondary font-monospace"><?php echo html_escape($vr['kode_barang']); ?></span>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (!empty($vr['katalog'])): ?>
                                                <span class="badge bg-light text-dark border font-monospace"><?php echo html_escape($vr['katalog']); ?></span>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <strong><?php echo html_escape($vr['nama_barang']); ?></strong>
                                        </td>
                                        <td>
                                            <?php echo !empty($vr['merk']) ? html_escape($vr['merk']) : '<span class="text-muted">-</span>'; ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border fw-bold">
                                                <?php echo html_escape($vr['stok']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle">
                                                <?php echo html_escape($vr['jenis_barang']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if (!empty($vr['expired_date'])): ?>
                                                <?php echo date('d-m-Y', strtotime($vr['expired_date'])); ?>
                                            <?php else: ?>
                                                <span class="text-muted">NULL (Kosong)</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($vr['is_duplicate']): ?>
                                                <span class="badge bg-warning text-dark" title="<?php echo html_escape($vr['dup_reason']); ?>">
                                                    <i class="bi bi-exclamation-triangle me-1"></i> Duplikat
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-success">
                                                    <i class="bi bi-plus-circle me-1"></i> Baru
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="form-actions-sticky">
                        <a href="<?php echo site_url('reagen/import'); ?>" class="btn-cancel-preview">
                            <i class="bi bi-arrow-left me-1"></i> Unggah File Lain
                        </a>

                        <button type="submit" class="btn-confirm-import" onclick="return confirm('Apakah Anda yakin ingin memproses dan menyimpan data yang tervalidasi ke Master Reagen?');">
                            <i class="bi bi-check2-circle"></i>
                            Konfirmasi &amp; Simpan ke Database (<?php echo count($valid_rows); ?> Data)
                        </button>
                    </div>

                <?php echo form_close(); ?>

            <?php else: ?>

                <div class="alert alert-warning border-0">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    Tidak ada baris data yang memenuhi syarat validasi untuk diimpor. Silakan periksa kembali format kolom file Anda.
                </div>

                <div class="text-start mt-3">
                    <a href="<?php echo site_url('reagen/import'); ?>" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Form Unggah
                    </a>
                </div>

            <?php endif; ?>

        </div>
    </div>

</div>
