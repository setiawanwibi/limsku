<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row align-items-center mb-4">
    <div class="col-md-7">
        <h4 class="fw-bold mb-1 text-dark">
            <i class="bi bi-plus-circle text-primary me-2"></i>Tambah Data Baku Pembanding
        </h4>
        <p class="text-muted mb-0 small">Masukkan data baku baru beserta nilai kadar air dan kadar baku yang dinormalisasi.</p>
    </div>
    <div class="col-md-5 text-md-end mt-3 mt-md-0">
        <a href="<?php echo site_url('baku'); ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Baku
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="<?php echo site_url('baku/tambah'); ?>" method="post">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nama Baku Pembanding <span class="text-danger">*</span></label>
                    <input type="text" name="nama_baku" class="form-control" required value="<?php echo set_value('nama_baku'); ?>" placeholder="Contoh: Asam Folat">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Nomor Kontrol / Lot</label>
                    <input type="text" name="no_kontrol" class="form-control font-monospace" value="<?php echo set_value('no_kontrol'); ?>" placeholder="Contoh: B 0314004">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Kode / Rak Posisi</label>
                    <input type="text" name="kode_posisi" class="form-control font-monospace" value="<?php echo set_value('kode_posisi'); ?>" placeholder="Contoh: A2">
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-3">
                    <label class="form-label fw-semibold text-info-emphasis">Kadar Air (%)</label>
                    <div class="input-group">
                        <input type="number" step="0.01" name="kadar_air" class="form-control font-monospace" value="<?php echo set_value('kadar_air'); ?>" placeholder="Contoh: 8.33">
                        <span class="input-group-text">%</span>
                    </div>
                    <div class="form-text">Biarkan kosong jika tidak ada data kadar air.</div>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold text-success">Kadar Baku / KCKT (%)</label>
                    <div class="input-group">
                        <input type="number" step="0.01" name="kadar_baku" class="form-control font-monospace" value="<?php echo set_value('kadar_baku'); ?>" placeholder="Contoh: 97.60">
                        <span class="input-group-text">%</span>
                    </div>
                    <div class="form-text">Biarkan kosong jika tidak ada data KCKT.</div>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Lokasi Penyimpanan <span class="text-danger">*</span></label>
                    <select name="lokasi_penyimpanan" class="form-select" required>
                        <option value="Ruang Baku Pembanding" selected>Ruang Baku Pembanding</option>
                        <option value="Showcase Kimia">Showcase Kimia</option>
                        <option value="Freezer Disolusi">Freezer Disolusi</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Suhu Rekomendasi</label>
                    <input type="text" name="suhu_rekomendasi" class="form-control" value="<?php echo set_value('suhu_rekomendasi', 'Suhu Ruang 15-30°C'); ?>" placeholder="Contoh: Suhu Ruang 15-30°C">
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Catatan Kemurnian Raw (CoA)</label>
                    <textarea name="kemurnian_raw" rows="3" class="form-control font-monospace small" placeholder="Teks lengkap catatan kemurnian resmi dari CoA..."><?php echo set_value('kemurnian_raw'); ?></textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Persyaratan Penyimpanan (CoA)</label>
                    <textarea name="penyimpanan" rows="3" class="form-control small" placeholder="Contoh: Dalam wadah tertutup rapat, terlindung dari cahaya pada suhu 2-8°C..."><?php echo set_value('penyimpanan'); ?></textarea>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 border-top pt-3">
                <a href="<?php echo site_url('baku'); ?>" class="btn btn-light border">Batal</a>
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-save me-1"></i> Simpan Data Baku
                </button>
            </div>
        </form>
    </div>
</div>
