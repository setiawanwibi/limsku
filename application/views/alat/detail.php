<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
    .instrument-detail-page {
        width: 100%;
        padding: 4px 0 22px;
        color: #294257;
    }

    .instrument-detail-card {
        width: 100%;
        max-width: 900px;
        margin: 0 auto;
        background: #ffffff;
        border: 1px solid #e0e8ed;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(41, 66, 87, 0.05);
        overflow: hidden;
    }

    .instrument-detail-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 18px 22px;
        background: #ffffff;
        border-bottom: 1px solid #e8eef1;
    }

    .instrument-detail-heading {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .instrument-detail-header-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        width: 42px;
        height: 42px;
        border-radius: 10px;
        background: #e5f6f4;
        color: #159b98;
        font-size: 20px;
    }

    .instrument-detail-title {
        margin: 0;
        color: #294257;
        font-size: 1.08rem;
        font-weight: 700;
    }

    .instrument-detail-subtitle {
        margin: 4px 0 0;
        color: #7b8b98;
        font-size: 0.8rem;
    }

    .instrument-detail-body {
        padding: 22px 24px;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px 24px;
    }

    @media (max-width: 767.98px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }
    }

    .detail-item {
        padding: 12px 14px;
        background: #f8fafc;
        border: 1px solid #e8eef2;
        border-radius: 8px;
    }

    .detail-label {
        font-size: 0.76rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #718096;
        margin-bottom: 4px;
    }

    .detail-value {
        font-size: 0.92rem;
        font-weight: 600;
        color: #2d3748;
    }

    .instrument-code-badge {
        font-family: monospace;
        padding: 3px 8px;
        background: #eef6fc;
        color: #1e70a2;
        border: 1px solid #d4e7f5;
        border-radius: 6px;
        font-weight: 700;
        display: inline-block;
    }

    .instrument-detail-footer {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
        padding-top: 18px;
        margin-top: 24px;
        border-top: 1px solid #edf2f5;
    }
</style>

<div class="instrument-detail-page">
    <div class="instrument-detail-card">

        <div class="instrument-detail-header">
            <div class="instrument-detail-heading">
                <div class="instrument-detail-header-icon">
                    <i class="bi bi-tools"></i>
                </div>
                <div>
                    <h5 class="instrument-detail-title"><?php echo html_escape($alat['nama_barang']); ?></h5>
                    <p class="instrument-detail-subtitle">Kode Inventaris: <span class="instrument-code-badge"><?php echo html_escape($alat['kode_barang']); ?></span></p>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                <a href="<?php echo site_url('alat/edit/' . $alat['id']); ?>" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-pencil-square me-1"></i> Edit Alat
                </a>
            </div>
        </div>

        <div class="instrument-detail-body">
            <div class="detail-grid">
                <!-- 1. Kode Barang -->
                <div class="detail-item">
                    <div class="detail-label">1. Kode Barang</div>
                    <div class="detail-value">
                        <span class="instrument-code-badge"><?php echo html_escape($alat['kode_barang']); ?></span>
                    </div>
                </div>

                <!-- 2. Nama Barang -->
                <div class="detail-item">
                    <div class="detail-label">2. Nama Barang</div>
                    <div class="detail-value"><?php echo html_escape($alat['nama_barang']); ?></div>
                </div>

                <!-- 3. Merk/Tipe -->
                <div class="detail-item">
                    <div class="detail-label">3. Merk/Tipe</div>
                    <div class="detail-value"><?php echo html_escape($alat['merk_tipe'] ?: '-'); ?></div>
                </div>

                <!-- 4. Perangkat Lunak -->
                <div class="detail-item">
                    <div class="detail-label">4. Perangkat Lunak</div>
                    <div class="detail-value"><?php echo html_escape($alat['perangkat_lunak'] ?: '-'); ?></div>
                </div>

                <!-- 5. Detektor -->
                <div class="detail-item">
                    <div class="detail-label">5. Detektor</div>
                    <div class="detail-value"><?php echo html_escape($alat['detektor'] ?: '-'); ?></div>
                </div>

                <!-- 6. Tanggal Perolehan -->
                <div class="detail-item">
                    <div class="detail-label">6. Tanggal Perolehan</div>
                    <div class="detail-value">
                        <?php 
                            if (!empty($alat['tanggal_perolehan'])) {
                                echo date('d F Y', strtotime($alat['tanggal_perolehan']));
                            } else {
                                echo '-';
                            }
                        ?>
                    </div>
                </div>

                <!-- 7. Kondisi ADM -->
                <div class="detail-item">
                    <div class="detail-label">7. Kondisi ADM</div>
                    <div class="detail-value">
                        <?php if (!empty($alat['kondisi_adm'])): ?>
                            <span class="badge bg-light text-dark border"><?php echo html_escape($alat['kondisi_adm']); ?></span>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </div>
                </div>

                <!-- 8. Kode Ruangan -->
                <div class="detail-item">
                    <div class="detail-label">8. Kode Ruangan</div>
                    <div class="detail-value"><?php echo html_escape($alat['kode_ruangan'] ?: '-'); ?></div>
                </div>

                <!-- 9. Nama Ruangan -->
                <div class="detail-item">
                    <div class="detail-label">9. Nama Ruangan</div>
                    <div class="detail-value"><?php echo html_escape($alat['nama_ruangan'] ?: '-'); ?></div>
                </div>

                <!-- 10. Status -->
                <div class="detail-item">
                    <div class="detail-label">10. Status Alat</div>
                    <div class="detail-value">
                        <?php if ($alat['status'] === 'Aktif'): ?>
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                <i class="bi bi-check-circle-fill me-1"></i> Aktif
                            </span>
                        <?php else: ?>
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">
                                <i class="bi bi-pause-circle-fill me-1"></i> Nonaktif
                            </span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Metadata Sistem -->
                <div class="detail-item">
                    <div class="detail-label">Waktu Dibuat</div>
                    <div class="detail-value small text-muted"><?php echo html_escape($alat['created_at'] ?: '-'); ?></div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Terakhir Diperbarui</div>
                    <div class="detail-value small text-muted"><?php echo html_escape($alat['updated_at'] ?: '-'); ?></div>
                </div>
            </div>

            <div class="instrument-detail-footer">
                <a href="<?php echo site_url('alat'); ?>" class="btn btn-outline-secondary px-3">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Alat
                </a>
            </div>
        </div>

    </div>
</div>
