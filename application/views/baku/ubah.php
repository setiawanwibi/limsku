<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
    .baku-form-page {
        color: #1e293b;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }
    .baku-form-header-card {
        background: #ffffff;
        border: 1px solid #edf2f7;
        border-radius: 14px;
        padding: 18px 22px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }
    .baku-form-card {
        background: #ffffff;
        border: 1px solid #edf2f7;
        border-radius: 14px;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
        padding: 24px;
    }
    .form-section-title {
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: #0284c7;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }
    .baku-input {
        background-color: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        color: #334155;
        font-size: 0.84rem;
        padding: 0.5rem 0.85rem;
        transition: all 0.2s ease;
    }
    .baku-input:focus {
        background-color: #ffffff;
        border-color: #38bdf8;
        box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.15);
        color: #0f172a;
    }
    .baku-label {
        font-size: 0.76rem;
        font-weight: 600;
        color: #475569;
        margin-bottom: 0.35rem;
    }
    .btn-baku-back {
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #475569;
        font-size: 0.82rem;
        font-weight: 500;
        border-radius: 999px;
        padding: 0.45rem 1.15rem;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .btn-baku-back:hover {
        background: #f1f5f9;
        color: #0f172a;
        border-color: #cbd5e1;
    }
    .btn-baku-submit {
        background: #0284c7;
        border: 1px solid #0284c7;
        color: #ffffff;
        font-weight: 600;
        font-size: 0.82rem;
        border-radius: 8px;
        padding: 0.5rem 1.5rem;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        transition: all 0.2s ease;
    }
    .btn-baku-submit:hover {
        background: #0369a1;
        border-color: #0369a1;
        color: #ffffff;
        box-shadow: 0 3px 8px rgba(2, 132, 199, 0.25);
    }
    .btn-baku-cancel {
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #64748b;
        font-weight: 500;
        font-size: 0.82rem;
        border-radius: 8px;
        padding: 0.5rem 1.25rem;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .btn-baku-cancel:hover {
        background: #f1f5f9;
        color: #0f172a;
    }
    .baku-addon {
        background-color: #f1f5f9;
        border: 1px solid #e2e8f0;
        color: #64748b;
        font-weight: 600;
        font-size: 0.82rem;
    }
</style>

<div class="baku-form-page">

    <!-- Tombol Kembali -->
    <div class="mb-3">
        <a href="<?php echo site_url('baku'); ?>" class="btn-baku-back">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Baku
        </a>
    </div>

    <!-- Form Card -->
    <div class="baku-form-card">
        <form action="<?php echo site_url('baku/ubah/' . $baku['id']); ?>" method="post">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

            <!-- Bagian 1: Identitas Baku -->
            <div class="form-section-title">
                <i class="bi bi-info-circle"></i> 1. Identitas Baku Pembanding
            </div>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="baku-label">Nama Baku Pembanding <span class="text-danger">*</span></label>
                    <input type="text" name="nama_baku" class="form-control baku-input" required value="<?php echo set_value('nama_baku', $baku['nama_baku']); ?>">
                </div>
                <div class="col-md-3">
                    <label class="baku-label">Nomor Kontrol / Lot</label>
                    <input type="text" name="no_kontrol" class="form-control baku-input font-monospace" value="<?php echo set_value('no_kontrol', $baku['no_kontrol']); ?>">
                </div>
                <div class="col-md-3">
                    <label class="baku-label">Kode / Rak Posisi</label>
                    <input type="text" name="kode_posisi" class="form-control baku-input font-monospace" value="<?php echo set_value('kode_posisi', $baku['kode_posisi']); ?>">
                </div>
            </div>

            <!-- Bagian 2: Nilai Kadar & Penyimpanan -->
            <div class="form-section-title">
                <i class="bi bi-sliders"></i> 2. Nilai Kadar &amp; Kondisi Penyimpanan
            </div>
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <label class="baku-label text-info-emphasis">Kadar Air (%)</label>
                    <div class="input-group">
                        <input type="number" step="0.01" name="kadar_air" class="form-control baku-input font-monospace" value="<?php echo set_value('kadar_air', $baku['kadar_air']); ?>">
                        <span class="input-group-text baku-addon">%</span>
                    </div>
                    <div class="form-text" style="font-size: 0.72rem;">Kosongkan bila tidak ada data kadar air.</div>
                </div>
                <div class="col-md-3">
                    <label class="baku-label text-success">Kadar Baku / KCKT (%)</label>
                    <div class="input-group">
                        <input type="number" step="0.01" name="kadar_baku" class="form-control baku-input font-monospace" value="<?php echo set_value('kadar_baku', $baku['kadar_baku']); ?>">
                        <span class="input-group-text baku-addon">%</span>
                    </div>
                    <div class="form-text" style="font-size: 0.72rem;">Kosongkan bila tidak ada data KCKT.</div>
                </div>
                <div class="col-md-3">
                    <label class="baku-label">Lokasi Penyimpanan <span class="text-danger">*</span></label>
                    <select name="lokasi_penyimpanan" class="form-select baku-input" required>
                        <option value="Ruang Baku Pembanding" <?php echo ($baku['lokasi_penyimpanan'] === 'Ruang Baku Pembanding') ? 'selected' : ''; ?>>Ruang Baku Pembanding</option>
                        <option value="Showcase Kimia" <?php echo ($baku['lokasi_penyimpanan'] === 'Showcase Kimia') ? 'selected' : ''; ?>>Showcase Kimia</option>
                        <option value="Freezer Disolusi" <?php echo ($baku['lokasi_penyimpanan'] === 'Freezer Disolusi') ? 'selected' : ''; ?>>Freezer Disolusi</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="baku-label">Suhu Rekomendasi</label>
                    <input type="text" name="suhu_rekomendasi" class="form-control baku-input" value="<?php echo set_value('suhu_rekomendasi', $baku['suhu_rekomendasi']); ?>">
                </div>
            </div>

            <!-- Bagian 3: Dokumen CoA -->
            <div class="form-section-title">
                <i class="bi bi-file-earmark-medical"></i> 3. Persyaratan &amp; Catatan CoA
            </div>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="baku-label">Catatan Kemurnian Raw (CoA)</label>
                    <textarea name="kemurnian_raw" rows="3" class="form-control baku-input font-monospace" style="font-size: 0.78rem;"><?php echo set_value('kemurnian_raw', $baku['kemurnian_raw']); ?></textarea>
                </div>
                <div class="col-md-6">
                    <label class="baku-label">Persyaratan Penyimpanan (CoA)</label>
                    <textarea name="penyimpanan" rows="3" class="form-control baku-input" style="font-size: 0.78rem;"><?php echo set_value('penyimpanan', $baku['penyimpanan']); ?></textarea>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex justify-content-end gap-2 border-top pt-3">
                <a href="<?php echo site_url('baku'); ?>" class="btn-baku-cancel">Batal</a>
                <button type="submit" class="btn-baku-submit">
                    <i class="bi bi-check-lg"></i>
                    <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>
</div>
