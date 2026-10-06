<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Flash Messages -->
<?php if ($this->session->flashdata('pesan_gagal')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo html_escape($this->session->flashdata('pesan_gagal')); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card border-0 shadow-sm mx-auto" style="max-width: 850px;">
    <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
        <div>
            <h5 class="fw-bold mb-0 text-dark">
                Pilih Form / Parameter Pengujian <?php echo html_escape($jenis_pengujian); ?>
            </h5>
            <small class="text-muted">Sampel: <strong><?php echo html_escape($sampel['nama_sampel']); ?></strong> (Kode: <?php echo html_escape($sampel['kode_sampel_manual'] ?: 'SMP-' . $sampel['id']); ?>)</small>
        </div>
        <span class="badge bg-<?php echo ($jenis_pengujian === 'Kimia' ? 'primary' : 'success'); ?> fs-6">
            Mode: Uji <?php echo html_escape($jenis_pengujian); ?>
        </span>
    </div>
    <div class="card-body p-4">
        <form action="<?php echo site_url('pengujian/mulai_sesi/' . $sampel['id']); ?>" method="post" id="form-pilih-template">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
            <input type="hidden" name="jenis_pengujian" value="<?php echo html_escape($jenis_pengujian); ?>">

            <div class="d-flex align-items-center justify-content-between mb-3">
                <label class="form-label fw-bold text-dark fs-6 mb-0">
                    Daftar Form Parameter Uji <?php echo html_escape($jenis_pengujian); ?> (Pilih Minimal 1) <span class="text-danger">*</span>
                </label>
                <span class="badge bg-secondary px-3 py-2 fs-6" id="badge-counter">0 form dipilih</span>
            </div>

            <?php if (!empty($daftar_template)): ?>
                <div class="list-group mb-4 shadow-sm">
                    <?php foreach ($daftar_template as $tmpl): ?>
                        <label class="list-group-item list-group-item-action d-flex align-items-center p-3 cursor-pointer">
                            <input class="form-check-input me-3 form-check-template" type="checkbox" name="template_ids[]" value="<?php echo $tmpl['id']; ?>" style="width: 1.25rem; height: 1.25rem;">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center justify-content-between">
                                    <strong class="text-dark fs-6"><?php echo html_escape($tmpl['nama_template']); ?></strong>
                                    <span class="badge bg-outline-dark font-monospace border px-2 py-1 small"><?php echo html_escape($tmpl['kode_template']); ?></span>
                                </div>
                                <?php if (!empty($tmpl['nama_metode'])): ?>
                                    <small class="text-muted d-block mt-1">
                                        Metode Ref: <?php echo html_escape($tmpl['nama_metode']); ?> (<?php echo html_escape($tmpl['kode_metode']); ?>)
                                    </small>
                                <?php endif; ?>
                            </div>
                        </label>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="alert alert-warning text-center py-4 mb-4">
                    Belum ada template form pengujian aktif untuk kategori <strong><?php echo html_escape($jenis_pengujian); ?></strong>.
                </div>
            <?php endif; ?>

            <div class="alert alert-info border-info small mb-4" role="alert">
                <strong>Catatan:</strong> Anda dapat memilih satu atau lebih form parameter di atas. Semua form yang dipilih akan digabungkan dalam satu sesi pengujian <?php echo html_escape($jenis_pengujian); ?>.
            </div>

            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                <a href="<?php echo site_url('pengujian/pilih_jenis/' . $sampel['id']); ?>" class="btn btn-secondary">&larr; Kembali</a>
                <button type="submit" class="btn btn-success fw-semibold px-4" id="btn-submit-sesi" disabled>
                    &rarr; Mulai Sesi Pengujian
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.form-check-template');
    const counterBadge = document.getElementById('badge-counter');
    const submitBtn = document.getElementById('btn-submit-sesi');

    function updateCounter() {
        let count = 0;
        checkboxes.forEach(function(cb) {
            if (cb.checked) count++;
        });

        counterBadge.textContent = count + ' form dipilih';
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
