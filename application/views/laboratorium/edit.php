<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="card border-0 shadow-sm" style="max-width: 600px;">
    <div class="card-header bg-white py-3">
        <h5 class="fw-bold mb-0 text-dark">Edit Laboratorium</h5>
    </div>
    <div class="card-body">
        <?php if (validation_errors()): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo validation_errors('<div>', '</div>'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form action="<?php echo site_url('laboratorium/edit/' . $lab['id']); ?>" method="post">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

            <div class="mb-3">
                <label for="kode_lab" class="form-label fw-semibold">Kode Laboratorium <span class="text-danger">*</span></label>
                <input type="text" class="form-control text-uppercase" id="kode_lab" name="kode_lab" value="<?php echo set_value('kode_lab', $lab['kode_lab']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="nama_lab" class="form-label fw-semibold">Nama Laboratorium <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="nama_lab" name="nama_lab" value="<?php echo set_value('nama_lab', $lab['nama_lab']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="deskripsi" class="form-label fw-semibold">Deskripsi (Opsional)</label>
                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"><?php echo set_value('deskripsi', $lab['deskripsi']); ?></textarea>
            </div>

            <div class="mb-4">
                <label for="status" class="form-label fw-semibold">Status Laboratorium</label>
                <select class="form-select" id="status" name="status">
                    <option value="Aktif" <?php echo set_select('status', 'Aktif', ($lab['status'] === 'Aktif')); ?>>Aktif</option>
                    <option value="Nonaktif" <?php echo set_select('status', 'Nonaktif', ($lab['status'] === 'Nonaktif')); ?>>Nonaktif</option>
                </select>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="<?php echo site_url('laboratorium'); ?>" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
