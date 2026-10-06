<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Header Informasi Sampel -->
<div class="card border-0 shadow-sm mb-4 border-start border-primary border-4">
    <div class="card-body p-4">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <h5 class="fw-bold mb-0 text-dark">
                Pelaksanaan Pengujian Sampel: <span class="text-primary"><?php echo html_escape($sampel['nama_sampel']); ?></span>
            </h5>
            <span class="badge bg-warning text-dark fs-6"><?php echo html_escape($sampel['status']); ?></span>
        </div>
        <div class="row g-2 text-muted small mt-2">
            <div class="col-md-3"><strong>Kode Sampel:</strong> <?php echo html_escape($sampel['kode_sampel_manual'] ?: ($sampel['no'] ? 'NO-' . $sampel['no'] : 'SMP-' . $sampel['id'])); ?></div>
            <div class="col-md-3"><strong>Kategori:</strong> <?php echo html_escape($sampel['kategori_sampel'] ?: '-'); ?></div>
            <div class="col-md-3"><strong>No. Bets:</strong> <?php echo html_escape($sampel['no_bets'] ?: '-'); ?></div>
            <div class="col-md-3"><strong>Penguji Logged-In:</strong> <?php echo html_escape($this->session->userdata('nama_lengkap')); ?></div>
        </div>
    </div>
</div>

<!-- Step 1: Pemilihan Form Template & Metode -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3">
        <h6 class="fw-bold mb-0 text-dark">Langkah 1: Pilih Template Form &amp; Metode Pengujian</h6>
    </div>
    <div class="card-body">
        <form method="get" action="<?php echo site_url('pengujian/proses/' . $sampel['id']); ?>" class="row g-3 align-items-end">
            <div class="col-md-8">
                <label for="template_id_select" class="form-label fw-semibold">Pilih Template Form Pengujian <span class="text-danger">*</span></label>
                <select class="form-select form-select-lg" id="template_id_select" name="template_id" onchange="this.form.submit()" required>
                    <option value="">-- Pilih Form Template (17 Form Dinamis) --</option>
                    <?php if (!empty($daftar_template)): ?>
                        <?php foreach ($daftar_template as $t): ?>
                            <option value="<?php echo $t['id']; ?>" <?php echo ($template_selected && $template_selected['id'] == $t['id']) ? 'selected' : ''; ?>>
                                [<?php echo html_escape($t['kategori']); ?>] <?php echo html_escape($t['nama_template']); ?> (<?php echo html_escape($t['kode_template']); ?>)
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary btn-lg w-100 fw-semibold">Muat Form Template</button>
            </div>
        </form>
    </div>
</div>

<!-- Step 2: Form Pengisian Hasil Uji Dinamis -->
<?php if ($template_selected): ?>
    <?php $skema_fields = json_decode($template_selected['skema_form'], true); ?>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
            <h6 class="fw-bold mb-0 text-dark">
                Langkah 2: Isian Form Hasil Uji — <span class="text-primary"><?php echo html_escape($template_selected['nama_template']); ?></span>
            </h6>
            <span class="badge bg-info text-dark"><?php echo html_escape($template_selected['kategori']); ?></span>
        </div>
        <div class="card-body p-4">

            <form action="<?php echo site_url('pengujian/proses/' . $sampel['id']); ?>" method="post">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <input type="hidden" name="template_id" value="<?php echo $template_selected['id']; ?>">

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="method_id" class="form-label fw-semibold">Metode Pengujian <span class="text-danger">*</span></label>
                        <select class="form-select" id="method_id" name="method_id" required>
                            <option value="">-- Pilih Metode Acuan --</option>
                            <?php if (!empty($daftar_metode)): ?>
                                <?php foreach ($daftar_metode as $m): ?>
                                    <option value="<?php echo $m['id']; ?>" <?php echo ($template_selected['method_id'] == $m['id']) ? 'selected' : ''; ?>>
                                        [<?php echo html_escape($m['kode_metode']); ?>] <?php echo html_escape($m['nama_metode']); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

                <!-- Rendering Dynamic Fields -->
                <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">Field Parametrik &amp; Pengamatan Uji:</h6>

                <?php if (is_array($skema_fields) && !empty($skema_fields)): ?>
                    <div class="row g-3 mb-4">
                        <?php foreach ($skema_fields as $field_key => $config): ?>
                            <?php
                                $label    = isset($config['label']) ? $config['label'] : ucwords(str_replace('_', ' ', $field_key));
                                $type     = isset($config['type']) ? $config['type'] : 'text';
                                $unit     = isset($config['unit']) ? ' (' . $config['unit'] . ')' : '';
                                $required = !empty($config['required']) ? 'required' : '';
                            ?>
                            <div class="col-md-6">
                                <label for="field_<?php echo $field_key; ?>" class="form-label fw-semibold">
                                    <?php echo html_escape($label . $unit); ?>
                                    <?php if ($required): ?><span class="text-danger">*</span><?php endif; ?>
                                </label>

                                <?php if ($type === 'textarea'): ?>
                                    <textarea class="form-control" id="field_<?php echo $field_key; ?>" name="hasil[<?php echo $field_key; ?>]" rows="3" <?php echo $required; ?>></textarea>
                                <?php elseif ($type === 'number'): ?>
                                    <input type="number" step="any" class="form-control" id="field_<?php echo $field_key; ?>" name="hasil[<?php echo $field_key; ?>]" <?php echo $required; ?>>
                                <?php else: ?>
                                    <input type="text" class="form-control" id="field_<?php echo $field_key; ?>" name="hasil[<?php echo $field_key; ?>]" <?php echo $required; ?>>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning mb-4">Skema form template ini belum memiliki field pengamatan.</div>
                <?php endif; ?>

                <!-- Kesimpulan & Catatan Penguji -->
                <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">Kesimpulan &amp; Catatan Penguji:</h6>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="kesimpulan" class="form-label fw-semibold">Kesimpulan Hasil Uji <span class="text-danger">*</span></label>
                        <select class="form-select form-select-lg fw-bold" id="kesimpulan" name="kesimpulan" required>
                            <option value="Memenuhi Syarat (MS)">Memenuhi Syarat (MS)</option>
                            <option value="Tidak Memenuhi Syarat (TMS)">Tidak Memenuhi Syarat (TMS)</option>
                            <option value="Belum Disimpulkan">Belum Disimpulkan</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="catatan" class="form-label fw-semibold">Catatan / Evaluasi Penguji</label>
                        <textarea class="form-control" id="catatan" name="catatan" rows="2" placeholder="Catatan khusus analis mengenai pengujian..."></textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                    <a href="<?php echo site_url('pengujian/antrean'); ?>" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-success btn-lg fw-semibold" onclick="return confirm('Kirim hasil uji dan terbitkan Laporan Hasil Uji PDF? Status sampel akan berubah menjadi Menunggu Verifikasi.');">
                        Simpan &amp; Kirim ke Penyelia (Menunggu Verifikasi)
                    </button>
                </div>
            </form>

        </div>
    </div>
<?php endif; ?>
