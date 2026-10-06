<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="card border-0 shadow-sm" style="max-width: 650px;">
    <div class="card-header bg-white py-3">
        <h5 class="fw-bold mb-0 text-dark">Tambah Master Metode Pengujian</h5>
    </div>
    <div class="card-body">
        <?php if (validation_errors()): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo validation_errors('<div>', '</div>'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form action="<?php echo site_url('metode/tambah'); ?>" method="post">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

            <div class="mb-3">
                <label for="kode_metode" class="form-label fw-semibold">Kode Metode <span class="text-danger">*</span></label>
                <input type="text" class="form-control text-uppercase" id="kode_metode" name="kode_metode" value="<?php echo set_value('kode_metode'); ?>" placeholder="Contoh: MET-KIM-01, MET-MIK-02" required>
            </div>

            <div class="mb-3">
                <label for="nama_metode" class="form-label fw-semibold">Nama Metode Pengujian <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="nama_metode" name="nama_metode" value="<?php echo set_value('nama_metode'); ?>" placeholder="Contoh: Penetapan Kadar Secara Spektrofotometri UV-Vis" required>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label for="kategori" class="form-label fw-semibold">Kategori Bidang</label>
                    <select class="form-select" id="kategori" name="kategori">
                        <option value="Kimia" <?php echo set_select('kategori', 'Kimia', TRUE); ?>>Kimia</option>
                        <option value="Mikrobiologi" <?php echo set_select('kategori', 'Mikrobiologi'); ?>>Mikrobiologi</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="versi" class="form-label fw-semibold">Versi / Farmakope Acuan</label>
                    <input type="text" class="form-control" id="versi" name="versi" value="<?php echo set_value('versi'); ?>" placeholder="Contoh: FI Edisi VI / MA 2024">
                </div>
            </div>

            <div class="mb-3">
                <label for="keterangan" class="form-label fw-semibold">Keterangan (Opsional)</label>
                <textarea class="form-control" id="keterangan" name="keterangan" rows="3"><?php echo set_value('keterangan'); ?></textarea>
            </div>

            <div class="mb-4">
                <label for="status" class="form-label fw-semibold">Status Metode</label>
                <select class="form-select" id="status" name="status">
                    <option value="Aktif" <?php echo set_select('status', 'Aktif', TRUE); ?>>Aktif</option>
                    <option value="Nonaktif" <?php echo set_select('status', 'Nonaktif'); ?>>Nonaktif</option>
                </select>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="<?php echo site_url('metode'); ?>" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Metode</button>
            </div>
        </form>
    </div>
</div>
