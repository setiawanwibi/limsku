<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
    .import-metode-page { width: 100%; padding: 4px 0 24px; color: #294257; }
    .import-metode-card { width: 100%; max-width: 900px; background: #ffffff; border: 1px solid #e0e8ed; border-radius: 12px; box-shadow: 0 4px 16px rgba(41, 66, 87, 0.05); overflow: hidden; }
    .import-header { display: flex; align-items: center; gap: 12px; padding: 18px 24px; border-bottom: 1px solid #e8eef1; background: #ffffff; }
    .import-header-icon { width: 42px; height: 42px; border-radius: 10px; background: #e6f7ff; color: #1890ff; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0; }
    .import-header-title { margin: 0; color: #294257; font-size: 1.1rem; font-weight: 700; }
    .import-header-subtitle { margin: 4px 0 0; color: #7b8b98; font-size: 0.82rem; }
    .import-body { padding: 24px 26px; }
    .info-box-custom { padding: 16px 18px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; margin-bottom: 22px; font-size: 0.83rem; color: #4a5568; }
    .dropzone-upload { border: 2px dashed #cbd5e0; border-radius: 12px; padding: 36px 20px; text-align: center; background: #fcfdfd; cursor: pointer; position: relative; }
    .dropzone-upload:hover { border-color: #1890ff; background: #f0f7ff; }
    .file-input-hidden { position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; }
    .form-actions { display: flex; align-items: center; justify-content: flex-end; gap: 12px; margin-top: 26px; padding-top: 18px; border-top: 1px solid #edf2f7; }
    .btn-cancel { padding: 8px 18px; font-size: 0.85rem; font-weight: 600; color: #4a5568; background: #edf2f7; border: 1px solid #e2e8f0; border-radius: 8px; text-decoration: none; }
    .btn-submit-upload { display: inline-flex; align-items: center; gap: 8px; padding: 8px 22px; font-size: 0.85rem; font-weight: 600; color: #ffffff; background: #1890ff; border: 1px solid #1890ff; border-radius: 8px; box-shadow: 0 2px 6px rgba(24, 144, 255, 0.25); cursor: pointer; }
    .btn-submit-upload:hover { background: #096dd9; }
</style>

<div class="import-metode-page">

    <!-- Flash Messages -->
    <?php if ($this->session->flashdata('pesan_sukses')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            <?php echo html_escape($this->session->flashdata('pesan_sukses')); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('pesan_gagal')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <?php echo html_escape($this->session->flashdata('pesan_gagal')); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="import-metode-card">
        <div class="import-header">
            <div class="import-header-icon">
                <i class="bi bi-file-earmark-excel"></i>
            </div>
            <div>
                <h5 class="import-header-title">Import Data Master Metode Monografi Obat</h5>
                <p class="import-header-subtitle">
                    Unggah file Excel monografi laboratorium berisi ribuan baris zat aktif dan parameter uji.
                </p>
            </div>
        </div>

        <div class="import-body">
            <div class="info-box-custom">
                <h6 class="fw-bold mb-2 text-dark"><i class="bi bi-info-circle-fill text-primary me-1"></i> Ketentuan &amp; Format Kolom Monografi:</h6>
                <ul class="mb-0 ps-3">
                    <li>Format file harus <strong>.xlsx</strong>.</li>
                    <li><strong>Urutan &amp; Nama Kolom Header Excel:</strong>
                        <code>NO</code>,
                        <code>NAMA ZAT AKTIF</code>,
                        <code>JENIS SEDIAAN</code>,
                        <code>BENTUK SEDIAAN</code>,
                        <code>PUSTAKA ACUAN</code>,
                        <code>PARAMETER UJI KRITIS SESUAI MONOGRAFI</code>,
                        <code>METODE ANALISA</code>,
                        <code>PERSYARATAN</code>,
                        <code>BIDANG PENGUJIAN</code>,
                        <code>KELAS TERAPI</code> (jika berupa kolom, atau dideteksi dari judul baris kelompok).
                    </li>
                    <li><strong>Deteksi Header KELAS TERAPI:</strong> Jika berupa baris judul kelompok (misal: <em>1. KELAS TERAPI ANTIINFEKSI...</em>), sistem otomatis mengaitkannya ke seluruh baris monografi di bawahnya hingga judul kelompok berikutnya. Jika ada kolom <code>KELAS TERAPI</code> per baris, nilai kolom tersebut akan diprioritaskan.</li>
                    <li><strong>Kolom NO:</strong> Digunakan sebagai nomor urut sumber/tampilan dan diabaikan dari primary key database.</li>
                    <li><strong>Kebijakan Append-Only:</strong> Baris duplikat komposit otomatis dilewati tanpa menimpa data lama.</li>
                </ul>
            </div>

            <?php echo form_open_multipart('metode/import'); ?>
                <div class="dropzone-upload mb-3">
                    <input type="file" name="file_excel" id="file_excel" class="file-input-hidden" accept=".xlsx" required>
                    <i class="bi bi-cloud-arrow-up text-primary fs-1 d-block mb-2"></i>
                    <strong class="d-block text-dark">Pilih atau Seret File Excel ke Sini</strong>
                    <small class="text-muted">Hanya file berekstensi .xlsx</small>
                </div>

                <div id="file-info" class="p-2 bg-light rounded text-dark small mb-3 d-none align-items-center justify-content-between">
                    <span><i class="bi bi-file-earmark-spreadsheet text-success me-1"></i> <strong id="file-name"></strong></span>
                    <span id="file-size" class="text-muted"></span>
                </div>

                <div class="form-actions">
                    <a href="<?php echo site_url('metode'); ?>" class="btn-cancel">Batal</a>
                    <button type="submit" class="btn-submit-upload">
                        <i class="bi bi-search me-1"></i> Preview &amp; Validasi Data Monografi
                    </button>
                </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('file_excel');
    const fileInfo  = document.getElementById('file-info');
    const fileName  = document.getElementById('file-name');
    const fileSize  = document.getElementById('file-size');

    fileInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            fileName.textContent = this.files[0].name;
            fileSize.textContent = (this.files[0].size / 1024).toFixed(1) + ' KB';
            fileInfo.classList.remove('d-none');
            fileInfo.classList.add('d-flex');
        }
    });
});
</script>
