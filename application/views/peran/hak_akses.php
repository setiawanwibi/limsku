<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
        <h5 class="fw-bold mb-0 text-dark">
            Pengaturan Hak Akses: <span class="text-primary"><?php echo html_escape($role['nama_role']); ?></span>
        </h5>
        <a href="<?php echo site_url('peran'); ?>" class="btn btn-sm btn-outline-secondary">
            &larr; Kembali ke Daftar Peran
        </a>
    </div>
    <div class="card-body">
        <form action="<?php echo site_url('peran/hak-akses/' . $role['id']); ?>" method="post">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

            <?php if (!empty($permissions_by_kategori)): ?>
                <div class="row g-4">
                    <?php foreach ($permissions_by_kategori as $kategori => $items): ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 border">
                                <div class="card-header bg-light fw-bold py-2 d-flex justify-content-between align-items-center">
                                    <span><?php echo html_escape($kategori); ?></span>
                                    <button type="button" class="btn btn-link btn-sm p-0 text-decoration-none select-all-btn" data-target="cat-<?php echo url_title($kategori, '-', TRUE); ?>">
                                        Pilih Semua
                                    </button>
                                </div>
                                <div class="card-body p-3">
                                    <?php foreach ($items as $p): ?>
                                        <?php $checked = in_array($p['id'], $assigned_ids) ? 'checked' : ''; ?>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input cat-<?php echo url_title($kategori, '-', TRUE); ?>" type="checkbox" name="permissions[]" value="<?php echo $p['id']; ?>" id="perm_<?php echo $p['id']; ?>" <?php echo $checked; ?>>
                                            <label class="form-check-label" for="perm_<?php echo $p['id']; ?>">
                                                <strong class="d-block text-dark"><?php echo html_escape($p['deskripsi']); ?></strong>
                                                <small class="text-muted font-monospace"><?php echo html_escape($p['nama_permission']); ?></small>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="alert alert-warning">
                    Belum ada master permission di sistem. Silakan tambahkan Master Permission terlebih dahulu melalui menu Peran.
                </div>
            <?php endif; ?>

            <div class="mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                <a href="<?php echo site_url('peran'); ?>" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-success fw-semibold">Simpan Hak Akses</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.select-all-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var targetClass = this.getAttribute('data-target');
            var checkboxes = document.querySelectorAll('.' + targetClass);
            var allChecked = Array.from(checkboxes).every(cb => cb.checked);
            
            checkboxes.forEach(function(cb) {
                cb.checked = !allChecked;
            });

            this.textContent = allChecked ? 'Pilih Semua' : 'Batal Semua';
        });
    });
});
</script>
