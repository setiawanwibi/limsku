<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
    .reagent-detail-page {
        width: 100%;
        padding: 4px 0 24px;
        color: #294257;
    }

    .reagent-detail-card {
        width: 100%;
        max-width: 860px;
        background: #ffffff;
        border: 1px solid #e0e8ed;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(41, 66, 87, 0.05);
        overflow: hidden;
    }

    .reagent-detail-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 18px 24px;
        border-bottom: 1px solid #e8eef1;
        background: #ffffff;
    }

    .reagent-detail-heading {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .reagent-detail-header-icon {
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

    .reagent-detail-title {
        margin: 0;
        color: #294257;
        font-size: 1.1rem;
        font-weight: 700;
    }

    .reagent-detail-subtitle {
        margin: 4px 0 0;
        color: #7b8b98;
        font-size: 0.82rem;
    }

    .reagent-detail-body {
        padding: 24px 26px;
    }

    .detail-table {
        margin-bottom: 0;
        width: 100%;
    }

    .detail-table th {
        width: 28%;
        color: #718096;
        font-size: 0.84rem;
        font-weight: 600;
        padding: 14px 16px;
        border-bottom: 1px solid #edf2f7;
        background: #fcfdfd;
    }

    .detail-table td {
        color: #2d3748;
        font-size: 0.86rem;
        padding: 14px 16px;
        border-bottom: 1px solid #edf2f7;
    }

    .reagent-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 600;
    }

    .status-active {
        background: #e6ffed;
        color: #22863a;
        border: 1px solid #b7eb8f;
    }

    .status-inactive {
        background: #fff1f0;
        color: #cf1322;
        border: 1px solid #ffa39e;
    }

    .detail-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-top: 24px;
        padding-top: 18px;
        border-top: 1px solid #edf2f7;
    }

    .btn-back {
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

    .btn-back:hover {
        background: #e2e8f0;
        color: #2d3748;
    }

    .btn-edit-action {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 20px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #ffffff;
        background: #096dd9;
        border: 1px solid #096dd9;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-edit-action:hover {
        background: #085fc4;
        color: #ffffff;
    }
</style>

<div class="reagent-detail-page">

    <div class="reagent-detail-card">
        <div class="reagent-detail-header">
            <div class="reagent-detail-heading">
                <div class="reagent-detail-header-icon">
                    <i class="bi bi-eyedropper"></i>
                </div>
                <div>
                    <h5 class="reagent-detail-title">
                        <?php echo html_escape(!empty($reagen['nama_barang']) ? $reagen['nama_barang'] : $reagen['nama_reagen']); ?>
                    </h5>
                    <p class="reagent-detail-subtitle">
                        Rincian lengkap identitas, nomor katalog, stok, dan masa berlaku barang laboratorium.
                    </p>
                </div>
            </div>

            <?php if ($reagen['status'] === 'Aktif'): ?>
                <span class="reagent-status-badge status-active">
                    <i class="bi bi-check-circle-fill"></i> Aktif
                </span>
            <?php else: ?>
                <span class="reagent-status-badge status-inactive">
                    <i class="bi bi-x-circle-fill"></i> Nonaktif
                </span>
            <?php endif; ?>
        </div>

        <div class="reagent-detail-body">
            <table class="detail-table table">
                <tbody>
                    <tr>
                        <th>Kode Barang</th>
                        <td>
                            <?php if (!empty($reagen['kode_barang'])): ?>
                                <span class="badge bg-secondary font-monospace"><?php echo html_escape($reagen['kode_barang']); ?></span>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Nomor Katalog</th>
                        <td>
                            <?php if (!empty($reagen['katalog'])): ?>
                                <span class="badge bg-light text-dark border font-monospace"><?php echo html_escape($reagen['katalog']); ?></span>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Nama Barang</th>
                        <td>
                            <strong class="text-dark">
                                <?php echo html_escape(!empty($reagen['nama_barang']) ? $reagen['nama_barang'] : $reagen['nama_reagen']); ?>
                            </strong>
                        </td>
                    </tr>
                    <tr>
                        <th>Merk / Produsen</th>
                        <td>
                            <?php echo !empty($reagen['merk']) ? html_escape($reagen['merk']) : '<span class="text-muted">-</span>'; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Stok Saat Ini</th>
                        <td>
                            <?php 
                            $stok_val = !empty($reagen['stok']) ? trim($reagen['stok']) : '0 BOTOL';
                            $is_zero = preg_match('/^0\b/', $stok_val);
                            ?>
                            <span class="badge <?php echo $is_zero ? 'bg-danger-subtle text-danger border border-danger-subtle' : 'bg-success-subtle text-success border border-success-subtle'; ?> fs-6 px-3 py-1">
                                <?php echo html_escape($stok_val); ?>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Jenis Barang</th>
                        <td>
                            <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle px-2 py-1">
                                <?php echo html_escape(!empty($reagen['jenis_barang']) ? $reagen['jenis_barang'] : 'Reagen Cair'); ?>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Expired Date</th>
                        <td>
                            <?php if (!empty($reagen['expired_date']) && $reagen['expired_date'] !== '0000-00-00'): ?>
                                <?php 
                                $is_exp = strtotime($reagen['expired_date']) < time();
                                ?>
                                <strong class="<?php echo $is_exp ? 'text-danger' : 'text-dark'; ?>">
                                    <?php echo date('d F Y', strtotime($reagen['expired_date'])); ?>
                                </strong>
                                <?php if ($is_exp): ?>
                                    <span class="badge bg-danger ms-2"><i class="bi bi-exclamation-triangle-fill me-1"></i> Kedaluwarsa</span>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="text-muted">Tidak Tercantum / Masa Simpan Terbuka</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Kemasan (Historis)</th>
                        <td>
                            <?php echo !empty($reagen['kemasan']) ? html_escape($reagen['kemasan']) : '<span class="text-muted">-</span>'; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Tanggal Terdaftar</th>
                        <td>
                            <?php echo !empty($reagen['created_at']) ? date('d-m-Y H:i', strtotime($reagen['created_at'])) : '-'; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Terakhir Diperbarui</th>
                        <td>
                            <?php echo !empty($reagen['updated_at']) ? date('d-m-Y H:i', strtotime($reagen['updated_at'])) : '-'; ?>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="detail-actions">
                <a href="<?php echo site_url('reagen'); ?>" class="btn-back">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
                </a>

                <?php if ($this->Model_Hak_Akses->memiliki_akses($this->session->userdata('role_id'), 'reagen_edit')): ?>
                    <a href="<?php echo site_url('reagen/edit/' . $reagen['id']); ?>" class="btn-edit-action">
                        <i class="bi bi-pencil-square"></i> Edit Data
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>
