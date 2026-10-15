<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
    .import-instrument-page {
        width: 100%;
        padding: 4px 0 24px;
        color: #294257;
    }

    .import-instrument-card {
        width: 100%;
        max-width: 900px;
        background: #ffffff;
        border: 1px solid #e0e8ed;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(41, 66, 87, 0.05);
        overflow: hidden;
    }

    .import-header {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 18px 24px;
        border-bottom: 1px solid #e8eef1;
        background: #ffffff;
    }

    .import-header-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        background: #ebf8ff;
        color: #2b6cb0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .import-header-title {
        margin: 0;
        color: #294257;
        font-size: 1.1rem;
        font-weight: 700;
    }

    .import-header-subtitle {
        margin: 4px 0 0;
        color: #7b8b98;
        font-size: 0.82rem;
    }

    .import-body {
        padding: 24px 26px;
    }

    .import-info-box {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 16px 18px;
        background: #f7fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        margin-bottom: 24px;
    }

    .import-info-box i {
        font-size: 20px;
        color: #3182ce;
        margin-top: 2px;
    }

    .import-info-box h6 {
        margin: 0 0 6px;
        font-size: 0.9rem;
        font-weight: 700;
        color: #2d3748;
    }

    .import-info-box ul {
        margin: 0;
        padding-left: 20px;
        font-size: 0.82rem;
        color: #4a5568;
    }

    .import-info-box li {
        margin-bottom: 4px;
    }

    .dropzone-upload {
        border: 2px dashed #cbd5e0;
        border-radius: 12px;
        padding: 36px 20px;
        text-align: center;
        background: #fcfdfd;
        transition: all 0.2s ease;
        cursor: pointer;
        position: relative;
    }

    .dropzone-upload:hover {
        border-color: #3182ce;
        background: #f7fafc;
    }

    .dropzone-icon {
        font-size: 40px;
        color: #4a5568;
        margin-bottom: 12px;
    }

    .dropzone-text {
        font-size: 0.95rem;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 6px;
    }

    .dropzone-hint {
        font-size: 0.8rem;
        color: #718096;
    }

    .file-input-hidden {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }

    .selected-file-info {
        display: none;
        align-items: center;
        justify-content: space-between;
        padding: 12px 16px;
        margin-top: 14px;
        background: #edf2f7;
        border-radius: 8px;
        font-size: 0.85rem;
        color: #2d3748;
    }

    .form-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 26px;
        padding-top: 18px;
        border-top: 1px solid #edf2f7;
    }

    .btn-cancel {
        padding: 8px 18px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #4a5568;
        background: #edf2f7;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-cancel:hover {
        background: #e2e8f0;
        color: #2d3748;
    }

    .btn-submit-import {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 22px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #ffffff;
        background: #2b6cb0;
        border: 1px solid #2b6cb0;
        border-radius: 8px;
        box-shadow: 0 2px 6px rgba(43, 108, 176, 0.25);
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-submit-import:hover {
        background: #2c5282;
        border-color: #2c5282;
    }
</style>

<div class="import-instrument-page">

    <!-- Flash Messages -->
    <?php if ($this->session->flashdata('pesan_sukses')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            <?php echo html_escape($this->session->flashdata('pesan_sukses')); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('pesan_gagal')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <?php echo html_escape($this->session->flashdata('pesan_gagal')); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="import-instrument-card">
        <div class="import-header">
            <div class="import-header-icon">
                <i class="bi bi-file-earmark-excel"></i>
            </div>
            <div>
                <h5 class="import-header-title">Import Data Alat Laboratorium</h5>
                <p class="import-header-subtitle">
                    Unggah file Excel inventaris alat laboratorium (misal: <code>Daftar Alat.xlsx</code>) ke database.
                </p>
            </div>
        </div>

        <div class="import-body">
            
            <div class="import-info-box">
                <i class="bi bi-info-circle-fill"></i>
                <div>
                    <h6>Petunjuk &amp; Ketentuan Import:</h6>
                    <ul>
                        <li>File harus berformat Excel OpenXML (<strong>.xlsx</strong>).</li>
                        <li>Header kolom dibaca otomatis berdasarkan nama: <strong>Nama Barang</strong> (wajib), <strong>Merk/Tipe</strong>, <strong>Tanggal Perolehan</strong>, <strong>Kondisi ADM</strong>, <strong>Kode Ruangan</strong>, dan <strong>Nama Ruangan</strong>.</li>
                        <li>Kolom <strong>Kode Barang</strong> akan di-generate otomatis oleh sistem secara berurutan dengan format <code>ALT-0001</code>, <code>ALT-0002</code>, dst.</li>
                        <li>Field <strong>Perangkat Lunak</strong> dan <strong>Detektor</strong> akan diisi kosong (NULL) dan dapat dilengkapi sewaktu-waktu melalui menu Edit.</li>
                        <li>Format tanggal perolehan didukung dalam format Excel Serial, <code>YYYY-MM-DD</code>, maupun <code>DD/MM/YYYY</code>.</li>
                        <li>Sistem akan menampilkan <strong>halaman preview</strong> hasil validasi sebelum data benar-benar disimpan ke database.</li>
                    </ul>
                </div>
            </div>

            <?php echo form_open_multipart('alat/import', array('id' => 'form-import-alat')); ?>
                
                <div class="dropzone-upload" id="dropzone-area">
                    <input type="file" name="file_excel" id="file_excel" class="file-input-hidden" accept=".xlsx" required>
                    <div class="dropzone-icon">
                        <i class="bi bi-cloud-arrow-up"></i>
                    </div>
                    <div class="dropzone-text">Pilih atau seret file Excel ke sini</div>
                    <div class="dropzone-hint">Format file yang didukung: .xlsx (Maksimal 10 MB)</div>
                </div>

                <div class="selected-file-info" id="selected-file-info">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-file-earmark-spreadsheet text-success fs-5"></i>
                        <span id="file-name-display" class="fw-semibold"></span>
                        <span id="file-size-display" class="text-muted small"></span>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-danger py-0 px-2" id="btn-remove-file">
                        <i class="bi bi-x"></i> Hapus
                    </button>
                </div>

                <div class="form-actions">
                    <a href="<?php echo site_url('alat'); ?>" class="btn-cancel">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                    <button type="submit" class="btn-submit-import" id="btn-submit-upload">
                        <i class="bi bi-search me-1"></i> Preview &amp; Validasi Data
                    </button>
                </div>

            <?php echo form_close(); ?>

        </div>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('file_excel');
        const fileInfo = document.getElementById('selected-file-info');
        const fileNameDisplay = document.getElementById('file-name-display');
        const fileSizeDisplay = document.getElementById('file-size-display');
        const btnRemove = document.getElementById('btn-remove-file');

        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const file = this.files[0];
                fileNameDisplay.textContent = file.name;
                fileSizeDisplay.textContent = '(' + (file.size / 1024).toFixed(1) + ' KB)';
                fileInfo.style.display = 'flex';
            }
        });

        btnRemove.addEventListener('click', function(e) {
            e.preventDefault();
            fileInput.value = '';
            fileInfo.style.display = 'none';
        });
    });
</script>
