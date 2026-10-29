<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row align-items-center mb-4">
    <div class="col-md-7">
        <h4 class="fw-bold mb-1 text-dark">
            <i class="bi bi-file-earmark-arrow-up text-success me-2"></i>Impor Master Data Baku dari Excel
        </h4>
        <p class="text-muted mb-0 small">
            Unggah file spreadsheet <code>.xlsx</code> Master Baku Pembanding untuk impor massal dengan fitur normalisasi otomatis kolom <strong>Kadar Air (%)</strong> dan <strong>Kadar Baku / KCKT (%)</strong>.
        </p>
    </div>
    <div class="col-md-5 text-md-end mt-3 mt-md-0">
        <a href="<?php echo site_url('baku'); ?>" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Master Baku
        </a>
    </div>
</div>

<!-- Petunjuk Format File -->
<div class="card border-0 shadow-sm mb-4 border-start border-success border-4">
    <div class="card-body p-4">
        <h6 class="fw-bold text-dark mb-2">
            <i class="bi bi-info-circle-fill text-success me-2"></i>Format &amp; Ketentuan File Excel
        </h6>
        <ul class="text-muted small mb-0 ps-3">
            <li class="mb-1">Mendukung format file multi-sheet standar BPOM (Sheet: <em>RUANG BAKU PEMBANDING</em>, <em>SHOWCASE KIMIA</em>, <em>FREZEER DISOLUSI</em>, dan <em>COA KEMURNIAN</em>) atau file format ringkas 1 sheet.</li>
            <li class="mb-1">Kolom baku standar: <strong>No</strong>, <strong>Nama Baku</strong>, <strong>No. Kontrol</strong>, <strong>Kode Posisi</strong>.</li>
            <li class="mb-1">Sistem akan <strong>otomatis mengekstrak nilai Kadar Air (%) dan Kadar Baku KCKT (%)</strong> dari teks pada sheet COA / kolom kemurnian (contoh: <code>Kadar air = 8,33%</code> menjadi <code>8.33</code>, dan <code>KCKT = 97,60%</code> menjadi <code>97.60</code>).</li>
            <li>Jika baris data tidak memuat keterangan kadar air maupun KCKT, kolom tersebut akan dibiarkan <strong>kosong (NULL)</strong> sesuai ketentuan.</li>
        </ul>
    </div>
</div>

<!-- Form Upload -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h6 class="fw-bold mb-0 text-dark">
            <i class="bi bi-cloud-arrow-up text-primary me-2"></i>Unggah Berkas Spreadsheet (.xlsx)
        </h6>
    </div>
    <div class="card-body p-4">
        <form action="<?php echo site_url('baku/import'); ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

            <div class="row g-3 mb-4">
                <div class="col-md-8">
                    <label class="form-label fw-semibold">Pilih File Spreadsheet Excel <span class="text-danger">*</span></label>
                    <input type="file" name="file_excel" class="form-control" accept=".xlsx" required>
                    <div class="form-text">Maksimal ukuran file 5 MB. Format file harus berekstensi <code>.xlsx</code>.</div>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Mode Impor Data <span class="text-danger">*</span></label>
                    <select name="mode_impor" class="form-select" required>
                        <option value="replace" selected>Timpa / Ganti Seluruh Data (Replace)</option>
                        <option value="append">Tambahkan ke Data yang Sudah Ada (Append)</option>
                    </select>
                    <div class="form-text">Pilih 'Replace' untuk menyelaraskan data dengan versi file Excel terbaru.</div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 border-top pt-3">
                <a href="<?php echo site_url('baku'); ?>" class="btn btn-light border">Batal</a>
                <button type="submit" class="btn btn-success px-4" onclick="return confirm('Mulai proses impor data baku dari file Excel?');">
                    <i class="bi bi-upload me-1"></i> Mulai Impor &amp; Normalisasi Data
                </button>
            </div>
        </form>
    </div>
</div>
