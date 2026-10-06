<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="card border-0 shadow-sm" style="max-width: 800px;">
    <div class="card-header bg-white py-3">
        <h5 class="fw-bold mb-0 text-dark">Tambah Template Form Pengujian</h5>
    </div>
    <div class="card-body">
        <?php if (validation_errors()): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo validation_errors('<div>', '</div>'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form action="<?php echo site_url('template/tambah'); ?>" method="post">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <label for="kode_template" class="form-label fw-semibold">Kode Template <span class="text-danger">*</span></label>
                    <input type="text" class="form-control text-uppercase" id="kode_template" name="kode_template" value="<?php echo set_value('kode_template'); ?>" placeholder="Contoh: TPL-KIM-01" required>
                </div>
                <div class="col-md-5">
                    <label for="nama_template" class="form-label fw-semibold">Nama Form Template <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="nama_template" name="nama_template" value="<?php echo set_value('nama_template'); ?>" placeholder="Contoh: Form Penetapan Kadar Obat" required>
                </div>
                <div class="col-md-3">
                    <label for="kategori" class="form-label fw-semibold">Kategori Bidang</label>
                    <select class="form-select" id="kategori" name="kategori">
                        <option value="Kimia" <?php echo set_select('kategori', 'Kimia', TRUE); ?>>Kimia</option>
                        <option value="Mikrobiologi" <?php echo set_select('kategori', 'Mikrobiologi'); ?>>Mikrobiologi</option>
                    </select>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label for="method_id" class="form-label fw-semibold">Metode Acuan (Opsional)</label>
                    <select class="form-select" id="method_id" name="method_id">
                        <option value="">-- Bebas / Pilihan Penguji --</option>
                        <?php if (!empty($daftar_metode)): ?>
                            <?php foreach ($daftar_metode as $m): ?>
                                <option value="<?php echo $m['id']; ?>" <?php echo set_select('method_id', $m['id']); ?>>
                                    [<?php echo html_escape($m['kode_metode']); ?>] <?php echo html_escape($m['nama_metode']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="parameter_id" class="form-label fw-semibold">Parameter Acuan (Opsional)</label>
                    <select class="form-select" id="parameter_id" name="parameter_id">
                        <option value="">-- Bebas / Pilihan Penguji --</option>
                        <?php if (!empty($daftar_parameter)): ?>
                            <?php foreach ($daftar_parameter as $p): ?>
                                <option value="<?php echo $p['id']; ?>" <?php echo set_select('parameter_id', $p['id']); ?>>
                                    [<?php echo html_escape($p['kode_parameter']); ?>] <?php echo html_escape($p['nama_parameter']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label for="skema_form" class="form-label fw-semibold">Skema Form Dinamis (Format JSON) <span class="text-danger">*</span></label>
                <textarea class="form-control font-monospace small" id="skema_form" name="skema_form" rows="8" placeholder='{
  "kadar_zat": {"label": "Kadar Zat Aktif (%)", "type": "number", "unit": "%", "required": true},
  "absorbansi": {"label": "Pembacaan Absorbansi", "type": "number", "unit": "Abs", "required": true}
}' required><?php echo set_value('skema_form'); ?></textarea>
                <small class="text-muted">Masukkan struktur JSON valid yang mendefinisikan field input pengujian.</small>
            </div>

            <div class="mb-4">
                <label for="status" class="form-label fw-semibold">Status Template</label>
                <select class="form-select" id="status" name="status">
                    <option value="Aktif" <?php echo set_select('status', 'Aktif', TRUE); ?>>Aktif</option>
                    <option value="Nonaktif" <?php echo set_select('status', 'Nonaktif'); ?>>Nonaktif</option>
                </select>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="<?php echo site_url('template'); ?>" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Template</button>
            </div>
        </form>
    </div>
</div>
