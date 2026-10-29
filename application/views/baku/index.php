<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row align-items-center mb-4">
    <div class="col-md-7">
        <h4 class="fw-bold mb-1 text-dark">
            <i class="bi bi-eyedropper text-primary me-2"></i>Master Data Baku Pembanding
        </h4>
        <p class="text-muted mb-0 small">
            Kelola data baku laboratorium kimia, nomor kontrol, lokasi penyimpanan, suhu, serta nilai normalisasi kadar air dan kadar baku (KCKT).
        </p>
    </div>
    <div class="col-md-5 text-md-end mt-3 mt-md-0 d-flex gap-2 justify-content-md-end">
        <?php if ($this->Model_Hak_Akses->memiliki_akses($this->session->userdata('role_id'), 'baku_import')): ?>
            <a href="<?php echo site_url('baku/import'); ?>" class="btn btn-outline-success shadow-sm">
                <i class="bi bi-file-earmark-excel me-1"></i> Impor Excel
            </a>
        <?php endif; ?>
        <?php if ($this->Model_Hak_Akses->memiliki_akses($this->session->userdata('role_id'), 'baku_create')): ?>
            <a href="<?php echo site_url('baku/tambah'); ?>" class="btn btn-primary shadow-sm">
                <i class="bi bi-plus-circle me-1"></i> Tambah Baku
            </a>
        <?php endif; ?>
    </div>
</div>

<!-- Alert Notifikasi -->
<?php if ($this->session->flashdata('pesan_sukses')): ?>
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i><?php echo $this->session->flashdata('pesan_sukses'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>
<?php if ($this->session->flashdata('pesan_gagal')): ?>
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i><?php echo $this->session->flashdata('pesan_gagal'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Filter & Pencarian -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <form method="get" action="<?php echo site_url('baku'); ?>" class="row g-2 align-items-center">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" name="q" class="form-control border-start-0" placeholder="Cari nama baku, no kontrol, kode posisi..." value="<?php echo html_escape($keyword_aktif); ?>">
                </div>
            </div>
            <div class="col-md-4">
                <select name="lokasi" class="form-select">
                    <option value="">-- Semua Lokasi Penyimpanan --</option>
                    <?php if (!empty($daftar_lokasi)): ?>
                        <?php foreach ($daftar_lokasi as $lok): ?>
                            <option value="<?php echo html_escape($lok); ?>" <?php echo ($lokasi_aktif === $lok) ? 'selected' : ''; ?>>
                                <?php echo html_escape($lok); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-secondary flex-grow-1">
                    <i class="bi bi-filter me-1"></i> Filter
                </button>
                <?php if (!empty($keyword_aktif) || !empty($lokasi_aktif)): ?>
                    <a href="<?php echo site_url('baku'); ?>" class="btn btn-light border text-danger" title="Reset filter">
                        <i class="bi bi-x-circle"></i>
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<!-- Tabel Data Master Baku -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
        <h6 class="fw-bold mb-0 text-dark">
            <i class="bi bi-table me-2 text-primary"></i>Daftar Baku Pembanding
            <span class="badge bg-primary ms-2"><?php echo number_format($total_baku); ?> Data</span>
        </h6>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle mb-0" style="font-size: 0.9rem;">
                <thead class="table-light text-muted text-uppercase" style="font-size: 0.78rem;">
                    <tr>
                        <th class="text-center" style="width: 50px;">No</th>
                        <th>Nama Baku</th>
                        <th style="width: 140px;">No. Kontrol</th>
                        <th class="text-center" style="width: 80px;">Kode</th>
                        <th class="text-center" style="width: 120px;">Kadar Air (%)</th>
                        <th class="text-center" style="width: 130px;">Kadar Baku (%)</th>
                        <th>Lokasi &amp; Suhu</th>
                        <th style="width: 250px;">Persyaratan CoA</th>
                        <th class="text-center" style="width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($daftar_baku)): ?>
                        <?php $no = 1; foreach ($daftar_baku as $b): ?>
                            <tr>
                                <td class="text-center text-muted fw-bold"><?php echo $no++; ?></td>
                                <td>
                                    <div class="fw-bold text-dark"><?php echo html_escape($b['nama_baku']); ?></div>
                                    <?php if (!empty($b['kemurnian_raw'])): ?>
                                        <div class="text-muted small font-monospace" style="font-size: 0.75rem; max-width: 320px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?php echo html_escape($b['kemurnian_raw']); ?>">
                                            <?php echo html_escape($b['kemurnian_raw']); ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border font-monospace">
                                        <?php echo html_escape($b['no_kontrol'] ?: '-'); ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle font-monospace fw-bold">
                                        <?php echo html_escape($b['kode_posisi'] ?: '-'); ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <?php if ($b['kadar_air'] !== null): ?>
                                        <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle font-monospace fs-6 px-2 py-1">
                                            <?php echo number_format($b['kadar_air'], 2); ?>%
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($b['kadar_baku'] !== null): ?>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle font-monospace fs-6 px-2 py-1">
                                            <?php echo number_format($b['kadar_baku'], 2); ?>%
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="fw-semibold text-secondary small"><?php echo html_escape($b['lokasi_penyimpanan']); ?></div>
                                    <div class="text-muted" style="font-size: 0.75rem;"><i class="bi bi-thermometer-half text-danger me-1"></i><?php echo html_escape($b['suhu_rekomendasi'] ?: '-'); ?></div>
                                </td>
                                <td>
                                    <div class="small text-muted" style="max-height: 48px; overflow-y: auto; font-size: 0.78rem;">
                                        <?php echo html_escape($b['penyimpanan'] ?: '-'); ?>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        <?php if ($this->Model_Hak_Akses->memiliki_akses($this->session->userdata('role_id'), 'baku_edit')): ?>
                                            <a href="<?php echo site_url('baku/ubah/' . $b['id']); ?>" class="btn btn-outline-primary" title="Ubah Data">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                        <?php endif; ?>
                                        <?php if ($this->Model_Hak_Akses->memiliki_akses($this->session->userdata('role_id'), 'baku_delete')): ?>
                                            <a href="<?php echo site_url('baku/hapus/' . $b['id']); ?>" class="btn btn-outline-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus baku \'<?php echo html_escape($b['nama_baku']); ?>\'?');" title="Hapus Data">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Belum ada data baku pembanding yang ditemukan.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
