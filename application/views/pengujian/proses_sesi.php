<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Flash Messages -->
<?php if ($this->session->flashdata('pesan_gagal')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo html_escape($this->session->flashdata('pesan_gagal')); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
        <div>
            <h5 class="fw-bold mb-0 text-dark">Form Input Hasil Pengujian Sesi</h5>
            <small class="text-muted">
                Sampel: <strong><?php echo html_escape($sesi['nama_sampel']); ?></strong> 
                (Kode: <?php echo html_escape($sesi['kode_sampel_manual'] ?: 'SMP-' . $sesi['sample_id']); ?>)
            </small>
        </div>
        <div class="text-end">
            <span class="badge bg-<?php echo ($sesi['jenis_pengujian'] === 'Kimia' ? 'primary' : 'success'); ?> fs-6 me-2">
                Sesi: <?php echo html_escape($sesi['jenis_pengujian']); ?>
            </span>
            <span class="badge bg-warning text-dark fs-6">
                <?php echo count($hasil_forms); ?> Form Uji
            </span>
        </div>
    </div>
    <div class="card-body p-4">

        <form action="<?php echo site_url('pengujian/proses_sesi/' . $sesi['id']); ?>" method="post">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

            <!-- Form Tabs Navigation -->
            <ul class="nav nav-tabs mb-4" id="formTabs" role="tablist">
                <?php foreach ($hasil_forms as $idx => $f): ?>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link <?php echo ($idx === 0 ? 'active fw-bold' : ''); ?>" id="tab-btn-<?php echo $f['id']; ?>" data-bs-toggle="tab" data-bs-target="#tab-pane-<?php echo $f['id']; ?>" type="button" role="tab">
                            Form #<?php echo ($idx + 1); ?>: <?php echo html_escape($f['nama_template'] ?: 'Pengujian ' . $sesi['jenis_pengujian']); ?>
                        </button>
                    </li>
                <?php endforeach; ?>
            </ul>

            <!-- Form Tab Panes -->
            <div class="tab-content" id="formTabsContent">
                <?php foreach ($hasil_forms as $idx => $f): ?>
                    <div class="tab-pane fade <?php echo ($idx === 0 ? 'show active' : ''); ?>" id="tab-pane-<?php echo $f['id']; ?>" role="tabpanel">
                        
                        <div class="card border mb-4">
                            <div class="card-header bg-light py-2 px-3 fw-bold text-dark d-flex justify-content-between align-items-center">
                                <span><?php echo html_escape($f['nama_template']); ?> (<?php echo html_escape($f['kode_template']); ?>)</span>
                                <small class="text-muted">Metode: <?php echo html_escape($f['nama_metode'] ?: '-'); ?></small>
                            </div>
                            <div class="card-body p-3">
                                
                                <!-- Dynamic Form Parameter Fields (If Schema Exists) -->
                                <?php 
                                    $schema = !empty($f['skema_form']) ? json_decode($f['skema_form'], true) : array();
                                    $data_hasil = !empty($f['data_hasil']) ? json_decode($f['data_hasil'], true) : array();
                                ?>

                                <?php if (!empty($schema)): ?>
                                    <div class="row g-3 mb-4">
                                        <?php foreach ($schema as $key => $label): ?>
                                            <div class="col-md-6">
                                                <label for="f_<?php echo $f['id']; ?>_<?php echo $key; ?>" class="form-label fw-semibold small"><?php echo html_escape($label); ?></label>
                                                <input type="text" class="form-control form-control-sm" id="f_<?php echo $f['id']; ?>_<?php echo $key; ?>" name="forms[<?php echo $f['id']; ?>][hasil][<?php echo $key; ?>]" value="<?php echo html_escape(isset($data_hasil[$key]) ? $data_hasil[$key] : ''); ?>" placeholder="Input hasil <?php echo html_escape($label); ?>">
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="alert alert-light border small mb-3">
                                        <em>Template form ini merupakan acuan pengujian <?php echo html_escape($sesi['jenis_pengujian']); ?>. Detail parameter spesifik akan diisi sesuai template resmi masing-masing.</em>
                                    </div>
                                <?php endif; ?>

                                <!-- Kesimpulan Form -->
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="kesimpulan_<?php echo $f['id']; ?>" class="form-label fw-semibold small">Kesimpulan Form #<?php echo ($idx + 1); ?> <span class="text-danger">*</span></label>
                                        <select class="form-select form-select-sm" id="kesimpulan_<?php echo $f['id']; ?>" name="forms[<?php echo $f['id']; ?>][kesimpulan]" required>
                                            <option value="Belum Disimpulkan" <?php echo ($f['kesimpulan'] === 'Belum Disimpulkan' ? 'selected' : ''); ?>>-- Belum Disimpulkan --</option>
                                            <option value="Memenuhi Syarat (MS)" <?php echo ($f['kesimpulan'] === 'Memenuhi Syarat (MS)' ? 'selected' : ''); ?>>Memenuhi Syarat (MS)</option>
                                            <option value="Tidak Memenuhi Syarat (TMS)" <?php echo ($f['kesimpulan'] === 'Tidak Memenuhi Syarat (TMS)' ? 'selected' : ''); ?>>Tidak Memenuhi Syarat (TMS)</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="catatan_<?php echo $f['id']; ?>" class="form-label fw-semibold small">Catatan Pengujian</label>
                                        <input type="text" class="form-control form-control-sm" id="catatan_<?php echo $f['id']; ?>" name="forms[<?php echo $f['id']; ?>][catatan]" value="<?php echo html_escape($f['catatan'] ?: ''); ?>" placeholder="Catatan pengujian jika ada">
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Global Action Buttons -->
            <div class="d-flex justify-content-between align-items-center pt-3 border-top mt-4">
                <a href="<?php echo site_url('pengujian/antrean'); ?>" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-success fw-semibold px-4" onclick="return confirm('Apakah Anda yakin ingin menyimpan seluruh hasil pengujian sesi ini dan meneruskannya ke Verifikasi Penyelia?');">
                    &check; Simpan Seluruh Hasil Sesi &amp; Kirim Verifikasi
                </button>
            </div>

        </form>

    </div>
</div>
