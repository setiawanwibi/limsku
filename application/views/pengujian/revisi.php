<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Alert Penolakan -->
<div class="alert alert-danger border-2 border-danger shadow-sm mb-4">
    <div class="d-flex align-items-center mb-2">
        <i class="bi bi-exclamation-octagon-fill text-danger fs-3 me-2"></i>
        <h5 class="fw-bold mb-0 text-danger">Form Revisi Hasil Pengujian (Status Sebelumnya: Ditolak)</h5>
    </div>
    <hr class="my-2">
    <p class="mb-1"><strong>Ditolak Oleh Penyelia:</strong> <?php echo html_escape($hasil['nama_verifier'] ?: 'Penyelia'); ?></p>
    <p class="mb-1"><strong>Waktu Penolakan:</strong> <?php echo html_escape($hasil['waktu_verifikasi'] ? date('d-m-Y H:i:s', strtotime($hasil['waktu_verifikasi'])) : '-'); ?></p>
    <p class="mb-0 text-danger fw-semibold"><strong>Alasan Penolakan / Catatan Perbaikan:</strong></p>
    <div class="bg-white p-3 rounded border text-dark font-monospace mt-1">
        <?php echo nl2br(html_escape($hasil['alasan_penolakan'])); ?>
    </div>
</div>

<!-- Header Informasi Sampel -->
<div class="card border-0 shadow-sm mb-4 border-start border-warning border-4">
    <div class="card-body p-4">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <h5 class="fw-bold mb-0 text-dark">
                Revisi Sampel: <span class="text-primary"><?php echo html_escape($sampel['nama_sampel']); ?></span>
            </h5>
            <span class="badge bg-danger fs-6"><?php echo html_escape($hasil['status']); ?></span>
        </div>
        <div class="row g-2 text-muted small mt-2">
            <div class="col-md-3"><strong>Kode Sampel:</strong> <?php echo html_escape($sampel['kode_sampel_manual'] ?: ($sampel['no'] ? 'NO-' . $sampel['no'] : 'SMP-' . $sampel['id'])); ?></div>
            <div class="col-md-3"><strong>Kategori:</strong> <?php echo html_escape($sampel['kategori_sampel'] ?: '-'); ?></div>
            <div class="col-md-3"><strong>Form Template:</strong> <?php echo html_escape($hasil['nama_template'] ?: '-'); ?></div>
            <div class="col-md-3"><strong>Penguji Logged-In:</strong> <?php echo html_escape($this->session->userdata('nama_lengkap')); ?></div>
        </div>
    </div>
</div>

<?php 
$existing_values = json_decode($hasil['data_hasil'], true);
if (!is_array($existing_values)) {
    $existing_values = array();
}
?>

<?php if ($template_selected): ?>
    <?php $skema_fields = json_decode($template_selected['skema_form'], true); ?>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
            <h6 class="fw-bold mb-0 text-dark">
                Perbaiki Data Hasil Uji — <span class="text-primary"><?php echo html_escape($template_selected['nama_template']); ?></span>
            </h6>
            <span class="badge bg-info text-dark"><?php echo html_escape($template_selected['kategori']); ?></span>
        </div>
        <div class="card-body p-4">

            <form action="<?php echo site_url('pengujian/revisi/' . $hasil['id']); ?>" method="post">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <input type="hidden" name="template_id" value="<?php echo $template_selected['id']; ?>">

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="method_id" class="form-label fw-semibold">Metode Pengujian <span class="text-danger">*</span></label>
                        <select class="form-select" id="method_id" name="method_id" required>
                            <option value="">-- Pilih Metode Acuan --</option>
                            <?php if (!empty($daftar_metode)): ?>
                                <?php foreach ($daftar_metode as $m): ?>
                                    <option value="<?php echo $m['id']; ?>" <?php echo ($hasil['method_id'] == $m['id']) ? 'selected' : ''; ?>>
                                        [<?php echo html_escape($m['kode_metode']); ?>] <?php echo html_escape($m['nama_metode']); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

                <!-- Rendering Dynamic Fields -->
                <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">Field Parametrik &amp; Pengamatan Uji (Revisi):</h6>

                <?php if (is_array($skema_fields) && !empty($skema_fields)): ?>
                    <div class="row g-3 mb-4">
                        <?php foreach ($skema_fields as $field_key => $config): ?>
                            <?php
                                $label    = isset($config['label']) ? $config['label'] : ucwords(str_replace('_', ' ', $field_key));
                                $type     = isset($config['type']) ? $config['type'] : 'text';
                                $unit     = isset($config['unit']) ? ' (' . $config['unit'] . ')' : '';
                                $required = !empty($config['required']) ? 'required' : '';
                                $val      = isset($existing_values[$field_key]) ? $existing_values[$field_key] : '';
                                if (is_array($val)) {
                                    $val = json_encode($val);
                                }
                            ?>
                            <div class="col-md-6">
                                <label for="field_<?php echo $field_key; ?>" class="form-label fw-semibold">
                                    <?php echo html_escape($label . $unit); ?>
                                    <?php if ($required): ?><span class="text-danger">*</span><?php endif; ?>
                                </label>

                                <?php if ($type === 'textarea'): ?>
                                    <textarea class="form-control" id="field_<?php echo $field_key; ?>" name="hasil[<?php echo $field_key; ?>]" rows="3" <?php echo $required; ?>><?php echo html_escape($val); ?></textarea>
                                <?php elseif ($type === 'number'): ?>
                                    <input type="number" step="any" class="form-control" id="field_<?php echo $field_key; ?>" name="hasil[<?php echo $field_key; ?>]" value="<?php echo html_escape($val); ?>" <?php echo $required; ?>>
                                <?php else: ?>
                                    <input type="text" class="form-control" id="field_<?php echo $field_key; ?>" name="hasil[<?php echo $field_key; ?>]" value="<?php echo html_escape($val); ?>" <?php echo $required; ?>>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning mb-4">Skema form template ini belum memiliki field pengamatan.</div>
                <?php endif; ?>

                <!-- Kesimpulan & Catatan Penguji -->
                <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">Kesimpulan &amp; Catatan Penguji (Revisi):</h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="kesimpulan" class="form-label fw-semibold">Kesimpulan Hasil Uji <span class="text-danger">*</span></label>
                        <select class="form-select form-select-lg fw-bold" id="kesimpulan" name="kesimpulan" required>
                            <option value="Memenuhi Syarat (MS)" <?php echo ($hasil['kesimpulan'] === 'Memenuhi Syarat (MS)') ? 'selected' : ''; ?>>Memenuhi Syarat (MS)</option>
                            <option value="Tidak Memenuhi Syarat (TMS)" <?php echo ($hasil['kesimpulan'] === 'Tidak Memenuhi Syarat (TMS)') ? 'selected' : ''; ?>>Tidak Memenuhi Syarat (TMS)</option>
                            <option value="Belum Disimpulkan" <?php echo ($hasil['kesimpulan'] === 'Belum Disimpulkan') ? 'selected' : ''; ?>>Belum Disimpulkan</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="catatan" class="form-label fw-semibold">Catatan / Evaluasi Penguji</label>
                        <textarea class="form-control" id="catatan" name="catatan" rows="2" placeholder="Catatan khusus analis mengenai pengujian..."><?php echo html_escape($hasil['catatan']); ?></textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                    <a href="<?php echo site_url('pengujian/detail/' . $hasil['id']); ?>" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-warning btn-lg fw-bold" onclick="return confirm('Kirimkan revisi pengujian ini kembali ke Penyelia? Status laporan akan diperbarui menjadi Menunggu Verifikasi.');">
                        <i class="bi bi-send-check me-1"></i> Simpan &amp; Kirim Ulang ke Penyelia (Menunggu Verifikasi)
                    </button>
                </div>
            </form>

        </div>
    </div>
<?php endif; ?>
