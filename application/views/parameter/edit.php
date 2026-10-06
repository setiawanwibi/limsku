<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="card border-0 shadow-sm" style="max-width: 650px;">
    <div class="card-header bg-white py-3">
        <h5 class="fw-bold mb-0 text-dark">Edit Master Parameter Pengujian</h5>
    </div>
    <div class="card-body">
        <?php if (validation_errors()): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo validation_errors('<div>', '</div>'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form action="<?php echo site_url('parameter/edit/' . $parameter['id']); ?>" method="post">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

            <div class="mb-3">
                <label for="kode_parameter" class="form-label fw-semibold">Kode Parameter <span class="text-danger">*</span></label>
                <input type="text" class="form-control text-uppercase" id="kode_parameter" name="kode_parameter" value="<?php echo set_value('kode_parameter', $parameter['kode_parameter']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="nama_parameter" class="form-label fw-semibold">Nama Parameter <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="nama_parameter" name="nama_parameter" value="<?php echo set_value('nama_parameter', $parameter['nama_parameter']); ?>" required>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label for="satuan" class="form-label fw-semibold">Satuan Hasil</label>
                    <input type="text" class="form-control" id="satuan" name="satuan" value="<?php echo set_value('satuan', $parameter['satuan']); ?>">
                </div>
                <div class="col-md-6">
                    <label for="kategori" class="form-label fw-semibold">Kategori Bidang</label>
                    <select class="form-select" id="kategori" name="kategori">
                        <option value="Kimia" <?php echo set_select('kategori', 'Kimia', ($parameter['kategori'] === 'Kimia')); ?>>Kimia</option>
                        <option value="Mikrobiologi" <?php echo set_select('kategori', 'Mikrobiologi', ($parameter['kategori'] === 'Mikrobiologi')); ?>>Mikrobiologi</option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label for="baku_mutu" class="form-label fw-semibold">Baku Mutu / Persyaratan Spesifikasi</label>
                <input type="text" class="form-control" id="baku_mutu" name="baku_mutu" value="<?php echo set_value('baku_mutu', $parameter['baku_mutu']); ?>">
            </div>

            <div class="mb-4">
                <label for="status" class="form-label fw-semibold">Status Parameter</label>
                <select class="form-select" id="status" name="status">
                    <option value="Aktif" <?php echo set_select('status', 'Aktif', ($parameter['status'] === 'Aktif')); ?>>Aktif</option>
                    <option value="Nonaktif" <?php echo set_select('status', 'Nonaktif', ($parameter['status'] === 'Nonaktif')); ?>>Nonaktif</option>
                </select>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="<?php echo site_url('parameter'); ?>" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
