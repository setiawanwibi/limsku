<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="card border-0 shadow-sm" style="max-width: 600px;">
    <div class="card-header bg-white py-3">
        <h5 class="fw-bold mb-0 text-dark">Ubah Kata Sandi: <?php echo html_escape($user['username']); ?></h5>
    </div>
    <div class="card-body">
        <?php if (validation_errors()): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo validation_errors('<div>', '</div>'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form action="<?php echo site_url('pengguna/password/' . $user['id']); ?>" method="post">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

            <div class="mb-3">
                <label for="password_baru" class="form-label fw-semibold">Kata Sandi Baru <span class="text-danger">*</span></label>
                <input type="password" class="form-control" id="password_baru" name="password_baru" placeholder="Minimal 6 karakter" required>
            </div>

            <div class="mb-4">
                <label for="konfirmasi_password" class="form-label fw-semibold">Konfirmasi Kata Sandi Baru <span class="text-danger">*</span></label>
                <input type="password" class="form-control" id="konfirmasi_password" name="konfirmasi_password" placeholder="Ulangi kata sandi baru" required>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="<?php echo site_url('pengguna'); ?>" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-warning fw-semibold">Reset Kata Sandi</button>
            </div>
        </form>
    </div>
</div>
