<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
    .metode-form-page { width: 100%; padding: 4px 0 24px; color: #294257; }
    .metode-form-card { width: 100%; max-width: 800px; background: #ffffff; border: 1px solid #e0e8ed; border-radius: 12px; box-shadow: 0 4px 16px rgba(41, 66, 87, 0.05); overflow: hidden; }
    .metode-form-header { display: flex; align-items: center; gap: 12px; padding: 18px 24px; border-bottom: 1px solid #e8eef1; background: #ffffff; }
    .metode-form-header-icon { width: 42px; height: 42px; border-radius: 10px; background: #e6f7ff; color: #1890ff; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0; }
    .metode-form-title { margin: 0; color: #294257; font-size: 1.1rem; font-weight: 700; }
    .metode-form-body { padding: 24px 26px; }
    .form-group-custom { margin-bottom: 18px; }
    .form-label-custom { font-size: 0.84rem; font-weight: 600; color: #334e68; margin-bottom: 6px; display: block; }
    .form-control-custom, .form-select-custom { border: 1px solid #cbd5e0; border-radius: 8px; padding: 9px 12px; font-size: 0.86rem; color: #2d3748; width: 100%; }
    .form-control-custom:focus, .form-select-custom:focus { border-color: #1890ff; box-shadow: 0 0 0 3px rgba(24, 144, 255, 0.15); outline: none; }
    .form-actions { display: flex; align-items: center; justify-content: flex-end; gap: 12px; margin-top: 26px; padding-top: 18px; border-top: 1px solid #edf2f7; }
    .btn-cancel { padding: 8px 18px; font-size: 0.85rem; font-weight: 600; color: #4a5568; background: #edf2f7; border: 1px solid #e2e8f0; border-radius: 8px; text-decoration: none; }
    .btn-submit { display: inline-flex; align-items: center; gap: 8px; padding: 8px 22px; font-size: 0.85rem; font-weight: 600; color: #ffffff; background: #1890ff; border: 1px solid #1890ff; border-radius: 8px; box-shadow: 0 2px 6px rgba(24, 144, 255, 0.25); cursor: pointer; }
</style>

<div class="metode-form-page">
    <div class="metode-form-card">
        <div class="metode-form-header">
            <div class="metode-form-header-icon"><i class="bi bi-journal-plus"></i></div>
            <div>
                <h5 class="metode-form-title">Tambah Metode Monografi Baru</h5>
                <small class="text-muted">Masukkan rincian monografi zat aktif dan parameter pengujian.</small>
            </div>
        </div>

        <div class="metode-form-body">
            <?php if (validation_errors()): ?>
                <div class="alert alert-danger alert-dismissible fade show mb-4">
                    <h6 class="fw-bold mb-1">Kesalahan Input:</h6>
                    <?php echo validation_errors('<div class="small">- ', '</div>'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php echo form_open('metode/tambah'); ?>
                <div class="row">
                    <div class="col-md-6 form-group-custom">
                        <label for="nama_zat_aktif" class="form-label-custom">NAMA ZAT AKTIF <span class="text-danger">*</span></label>
                        <input type="text" name="nama_zat_aktif" id="nama_zat_aktif" class="form-control-custom" value="<?php echo set_value('nama_zat_aktif'); ?>" placeholder="Contoh: Parasetamol, Amoksisilin" required>
                    </div>
                    <div class="col-md-6 form-group-custom">
                        <label for="bidang_pengujian" class="form-label-custom">BIDANG PENGUJIAN <span class="text-danger">*</span></label>
                        <select name="bidang_pengujian" id="bidang_pengujian" class="form-select-custom" required>
                            <option value="Kimia" <?php echo set_select('bidang_pengujian', 'Kimia', TRUE); ?>>Kimia</option>
                            <option value="Mikrobiologi" <?php echo set_select('bidang_pengujian', 'Mikrobiologi'); ?>>Mikrobiologi</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group-custom">
                        <label for="jenis_sediaan" class="form-label-custom">JENIS SEDIAAN</label>
                        <input type="text" name="jenis_sediaan" id="jenis_sediaan" class="form-control-custom" value="<?php echo set_value('jenis_sediaan'); ?>" placeholder="Contoh: Obat Jadi, Kosmetik">
                    </div>
                    <div class="col-md-6 form-group-custom">
                        <label for="bentuk_sediaan" class="form-label-custom">BENTUK SEDIAAN</label>
                        <input type="text" name="bentuk_sediaan" id="bentuk_sediaan" class="form-control-custom" value="<?php echo set_value('bentuk_sediaan'); ?>" placeholder="Contoh: Tablet, Sirup, Injeksi">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group-custom">
                        <label for="parameter_uji" class="form-label-custom">PARAMETER UJI KRITIS SESUAI MONOGRAFI <span class="text-danger">*</span></label>
                        <input type="text" name="parameter_uji" id="parameter_uji" class="form-control-custom" value="<?php echo set_value('parameter_uji'); ?>" placeholder="Contoh: Penetapan Kadar, Disolusi, Identifikasi" required>
                    </div>
                    <div class="col-md-6 form-group-custom">
                        <label for="pustaka_acuan" class="form-label-custom">PUSTAKA ACUAN</label>
                        <input type="text" name="pustaka_acuan" id="pustaka_acuan" class="form-control-custom" value="<?php echo set_value('pustaka_acuan'); ?>" placeholder="Contoh: FI VI, USP 43">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group-custom">
                        <label for="metode_analisa" class="form-label-custom">METODE ANALISA</label>
                        <input type="text" name="metode_analisa" id="metode_analisa" class="form-control-custom" value="<?php echo set_value('metode_analisa'); ?>" placeholder="Contoh: KCKT, Spektrofotometri UV-Vis">
                    </div>
                    <div class="col-md-6 form-group-custom">
                        <label for="kelas_terapi_id" class="form-label-custom">KELAS TERAPI</label>
                        <select name="kelas_terapi_id" id="kelas_terapi_id" class="form-select-custom">
                            <option value="">-- Tanpa Kelas Terapi --</option>
                            <?php foreach ($daftar_kelas_terapi as $kt): ?>
                                <option value="<?php echo $kt['id']; ?>" <?php echo set_select('kelas_terapi_id', $kt['id']); ?>>
                                    <?php echo html_escape($kt['nama_kelas_terapi']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group-custom">
                    <label for="persyaratan" class="form-label-custom">PERSYARATAN</label>
                    <textarea name="persyaratan" id="persyaratan" rows="2" class="form-control-custom" placeholder="Contoh: Mengandung tidak kurang dari 90.0% dan tidak lebih dari 110.0%"><?php echo set_value('persyaratan'); ?></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6 form-group-custom">
                        <label for="template_id" class="form-label-custom">Pemetaan ke Form LCP</label>
                        <select name="template_id" id="template_id" class="form-select-custom">
                            <option value="">-- Belum Dipetakan --</option>
                            <?php foreach ($daftar_lcp as $lcp): ?>
                                <option value="<?php echo $lcp['id']; ?>" <?php echo set_select('template_id', $lcp['id']); ?>>
                                    [<?php echo html_escape($lcp['kode_template']); ?>] <?php echo html_escape($lcp['nama_template']); ?> (<?php echo html_escape($lcp['kategori']); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6 form-group-custom">
                        <label for="status" class="form-label-custom">Status</label>
                        <select name="status" id="status" class="form-select-custom">
                            <option value="Aktif" <?php echo set_select('status', 'Aktif', TRUE); ?>>Aktif</option>
                            <option value="Nonaktif" <?php echo set_select('status', 'Nonaktif'); ?>>Nonaktif</option>
                        </select>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="<?php echo site_url('metode'); ?>" class="btn-cancel">Batal</a>
                    <button type="submit" class="btn-submit"><i class="bi bi-check-lg me-1"></i> Simpan Monografi</button>
                </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>