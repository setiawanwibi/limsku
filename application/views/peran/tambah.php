<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="card border-0 shadow-sm" style="max-width: 600px;">
    <div class="card-header bg-white py-3">
        <h5 class="fw-bold mb-0 text-dark">Tambah Peran Baru</h5>
    </div>
    <div class="card-body">
        <?php if (validation_errors()): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo validation_errors('<div>', '</div>'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form action="<?php echo site_url('peran/tambah'); ?>" method="post">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

            <div class="mb-3">
                <label for="nama_role" class="form-label fw-semibold">Nama Peran / Role <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="nama_role" name="nama_role" value="<?php echo set_value('nama_role'); ?>" placeholder="Contoh: Admin TI, Penguji Kimia, Penyelia" required>
            </div>

            <div class="mb-4">
                <label for="keterangan" class="form-label fw-semibold">Keterangan / Deskripsi (Opsional)</label>
                <textarea class="form-control" id="keterangan" name="keterangan" rows="3" placeholder="Penjelasan tugas dan tanggung jawab peran"><?php echo set_value('keterangan'); ?></textarea>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="<?php echo site_url('peran'); ?>" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Peran</button>
            </div>
        </form>
    </div>
</div>
