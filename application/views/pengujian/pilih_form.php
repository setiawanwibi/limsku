<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Flash Messages -->
<?php if ($this->session->flashdata('pesan_gagal')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        <?php echo html_escape($this->session->flashdata('pesan_gagal')); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card border-0 shadow-sm mx-auto" style="max-width: 980px;">
    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
        <div>
            <h5 class="fw-bold mb-0 text-dark">
                Pilih Zat Aktif &amp; Parameter Monografi Pengujian <?php echo html_escape($jenis_pengujian); ?>
            </h5>
            <small class="text-muted">Sampel: <strong><?php echo html_escape($sampel['nama_sampel']); ?></strong> (Kode: <?php echo html_escape($sampel['kode_sampel_manual'] ?: 'SMP-' . $sampel['id']); ?>)</small>
        </div>
        <span class="badge bg-<?php echo ($jenis_pengujian === 'Kimia' ? 'primary' : 'success'); ?> fs-6">
            Bidang: <?php echo html_escape($jenis_pengujian); ?>
        </span>
    </div>

    <div class="card-body p-4">
        <!-- LANGKAH 1: Pilih Tepat Satu Zat Aktif Monografi -->
        <div class="p-3 bg-light rounded border mb-4">
            <form method="get" action="<?php echo site_url('pengujian/pilih_form/' . $sampel['id']); ?>" id="form-pilih-zat-aktif">
                <input type="hidden" name="jenis" value="<?php echo html_escape($jenis_pengujian); ?>">
                <div class="row align-items-end g-3">
                    <div class="col-md-9">
                        <label for="select_zat_aktif" class="form-label fw-bold text-dark mb-1">
                            1. Pilih Zat Aktif Monografi Obat <span class="text-danger">*</span>
                        </label>
                        <select name="zat_aktif" id="select_zat_aktif" class="form-select form-select-lg" onchange="this.form.submit()" required>
                            <option value="">-- Pilih Satu Zat Aktif Utama (Master Metode) --</option>
                            <?php foreach ($daftar_zat_aktif as $za): ?>
                                <option value="<?php echo html_escape($za['nama_zat_aktif']); ?>" <?php echo ($zat_aktif_terpilih === $za['nama_zat_aktif']) ? 'selected' : ''; ?>>
                                    <?php echo html_escape($za['nama_zat_aktif']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <small class="text-muted d-block mt-1">Satu sampel menggunakan tepat satu zat aktif utama per sesi pengujian.</small>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-outline-primary btn-lg w-100 fw-semibold">
                            <i class="bi bi-funnel me-1"></i> Muat Parameter
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <?php if (!empty($zat_aktif_terpilih)): ?>
            <!-- LANGKAH 2: Daftar Parameter Monografi untuk Zat Aktif Terpilih -->
            <form action="<?php echo site_url('pengujian/mulai_sesi/' . $sampel['id']); ?>" method="post" id="form-pilih-parameter">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <input type="hidden" name="jenis_pengujian" value="<?php echo html_escape($jenis_pengujian); ?>">
                <input type="hidden" name="nama_zat_aktif" value="<?php echo html_escape($zat_aktif_terpilih); ?>">

                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <h6 class="fw-bold mb-0 text-dark">
                            2. Pilih Parameter Uji Kritis Sesuai Monografi (Zat: <span class="text-primary"><?php echo html_escape($zat_aktif_terpilih); ?></span>)
                        </h6>
                        <small class="text-muted">Centang satu atau beberapa parameter yang akan diuji. Setiap parameter menghasilkan satu lembar LCP.</small>
                    </div>
                    <span class="badge bg-secondary px-3 py-2 fs-6" id="badge-counter">0 parameter dipilih</span>
                </div>

                <?php if (!empty($parameter_monografi)): ?>
                    <div class="table-responsive border rounded mb-4">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="4%" class="text-center">PILIH</th>
                                    <th>PARAMETER UJI KRITIS</th>
                                    <th>METODE ANALISA</th>
                                    <th>PERSYARATAN / BAKU MUTU</th>
                                    <th>PUSTAKA ACUAN</th>
                                    <th>STATUS PEMETAAN LCP</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($parameter_monografi as $p): ?>
                                    <?php $is_mapped = !empty($p['template_id']); ?>
                                    <tr class="<?php echo $is_mapped ? '' : 'table-light text-muted opacity-75'; ?>">
                                        <td class="text-center">
                                            <?php if ($is_mapped): ?>
                                                <input class="form-check-input form-check-parameter" type="checkbox" name="method_ids[]" value="<?php echo $p['id']; ?>" style="width: 1.25rem; height: 1.25rem; cursor: pointer;">
                                            <?php else: ?>
                                                <input class="form-check-input form-check-parameter" type="checkbox" name="method_ids[]" value="<?php echo $p['id']; ?>" id="chk_metode_<?php echo $p['id']; ?>" style="width: 1.25rem; height: 1.25rem; cursor: pointer;">
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <strong class="text-dark">
                                                <?php echo html_escape($p['parameter_uji']); ?>
                                            </strong>
                                            <small class="d-block text-muted"><code><?php echo html_escape($p['kode_metode']); ?></code></small>
                                        </td>
                                        <td><?php echo html_escape($p['metode_analisa'] ?: '-'); ?></td>
                                        <td><small class="text-muted"><?php echo html_escape($p['persyaratan'] ?: '-'); ?></small></td>
                                        <td><?php echo html_escape($p['pustaka_acuan'] ?: '-'); ?></td>
                                        <td>
                                            <?php if ($is_mapped): ?>
                                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                                    <i class="bi bi-check-circle-fill me-1"></i> [<?php echo html_escape($p['kode_template']); ?>] <?php echo html_escape($p['nama_template']); ?>
                                                </span>
                                            <?php else: ?>
                                                <div class="d-flex align-items-center gap-1">
                                                    <select class="form-select form-select-sm border-warning select-custom-template" name="custom_template_ids[<?php echo $p['id']; ?>]">
                                                        <option value="">-- Pilih Form LCP <?php echo html_escape($jenis_pengujian); ?> --</option>
                                                        <?php if (!empty($daftar_template_bidang)): ?>
                                                            <?php foreach ($daftar_template_bidang as $tpl_b): ?>
                                                                <?php
                                                                $is_selected = false;
                                                                if ($jenis_pengujian === 'Mikrobiologi') {
                                                                    // Jika parameter adalah lempeng total / ALT -> sarankan MIK-01 (ALT)
                                                                    if ((stripos($p['parameter_uji'], 'lempeng') !== false || stripos($p['parameter_uji'], 'alt') !== false) && $tpl_b['kode_template'] === 'MIK-01') {
                                                                        $is_selected = true;
                                                                    } elseif ((stripos($p['parameter_uji'], 'hitung') !== false || stripos($p['parameter_uji'], 'kapang') !== false || stripos($p['parameter_uji'], 'khamir') !== false) && $tpl_b['kode_template'] === 'MIK-02') {
                                                                        $is_selected = true;
                                                                    } elseif ($tpl_b['kode_template'] === 'MIK-04' && stripos($p['parameter_uji'], 'spesifik') !== false) {
                                                                        $is_selected = true;
                                                                    } elseif ($tpl_b['kode_template'] === 'MIK-03' && stripos($p['parameter_uji'], 'potensi') !== false) {
                                                                        $is_selected = true;
                                                                    } elseif ($tpl_b['kode_template'] === 'MIK-05' && stripos($p['parameter_uji'], 'endotoksin') !== false) {
                                                                        $is_selected = true;
                                                                    } elseif ($tpl_b['kode_template'] === 'MIK-06' && (stripos($p['parameter_uji'], 'kepekaan') !== false || stripos($p['parameter_uji'], 'lal') !== false)) {
                                                                        $is_selected = true;
                                                                    }
                                                                } elseif ($jenis_pengujian === 'Kimia' && $tpl_b['kode_template'] === 'KIM-01') {
                                                                    $is_selected = true;
                                                                }
                                                                ?>
                                                                <option value="<?php echo $tpl_b['id']; ?>" <?php echo $is_selected ? 'selected' : ''; ?>>
                                                                    [<?php echo html_escape($tpl_b['kode_template']); ?>] <?php echo html_escape($tpl_b['nama_template']); ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    </select>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning text-center py-4 mb-4">
                        <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                        Tidak ditemukan parameter monografi untuk zat aktif <strong><?php echo html_escape($zat_aktif_terpilih); ?></strong> pada bidang <strong><?php echo html_escape($jenis_pengujian); ?></strong>.
                    </div>
                <?php endif; ?>

                <div class="alert alert-info border-info small mb-4" role="alert">
                    <i class="bi bi-info-circle-fill me-1"></i>
                    <strong>Aturan SOP Pengujian:</strong> Hanya parameter yang memiliki pemetaan resmi ke template LCP yang dapat dipilih. Parameter tanpa mapping ditolak oleh sistem untuk mencegah pengujian tanpa form standar.
                </div>

                <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                    <a href="<?php echo site_url('pengujian/pilih_jenis/' . $sampel['id']); ?>" class="btn btn-secondary">&larr; Kembali ke Bidang</a>
                    <button type="submit" class="btn btn-success fw-semibold px-4" id="btn-submit-sesi" disabled>
                        &rarr; Mulai Sesi Pengujian Parameter Terpilih
                    </button>
                </div>
            </form>
        <?php else: ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-capsule fs-1 d-block mb-3 text-secondary"></i>
                <h6 class="fw-bold text-dark">Pilih Zat Aktif Monografi Terlebih Dahulu</h6>
                <p class="small text-muted mb-0">Pilih zat aktif obat pada dropdown di atas untuk menampilkan seluruh parameter uji kritis monografinya.</p>
            </div>
            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                <a href="<?php echo site_url('pengujian/pilih_jenis/' . $sampel['id']); ?>" class="btn btn-secondary">&larr; Kembali ke Bidang</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.form-check-parameter');
    const counterBadge = document.getElementById('badge-counter');
    const submitBtn = document.getElementById('btn-submit-sesi');

    function updateCounter() {
        if (!counterBadge || !submitBtn) return;
        let count = 0;
        checkboxes.forEach(function(cb) {
            if (cb.checked) count++;
        });

        counterBadge.textContent = count + ' parameter dipilih';
        if (count > 0) {
            counterBadge.className = 'badge bg-success px-3 py-2 fs-6';
            submitBtn.disabled = false;
        } else {
            counterBadge.className = 'badge bg-secondary px-3 py-2 fs-6';
            submitBtn.disabled = true;
        }
    }

    checkboxes.forEach(function(cb) {
        cb.addEventListener('change', updateCounter);
    });

    updateCounter();
});
</script>
