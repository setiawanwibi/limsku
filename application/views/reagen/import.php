<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
    .import-reagent-page {
        width: 100%;
        padding: 4px 0 24px;
        color: #294257;
    }

    .import-reagent-card {
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
        justify-content: space-between;
        gap: 16px;
        padding: 18px 24px;
        border-bottom: 1px solid #e8eef1;
        background: #ffffff;
    }

    .import-header-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .import-header-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        background: #e6f7ff;
        color: #096dd9;
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

    .btn-download-tpl {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        background: #f0fdf4;
        color: #15803d;
        border: 1px solid #bbf7d0;
        border-radius: 8px;
        font-size: 0.82rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
        white-space: nowrap;
    }

    .btn-download-tpl:hover {
        background: #dcfce7;
        color: #166534;
        border-color: #86efac;
    }

    .import-body {
        padding: 24px 26px;
    }

    .import-info-box {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 16px 18px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        margin-bottom: 24px;
    }

    .import-info-box i {
        font-size: 20px;
        color: #096dd9;
        margin-top: 2px;
    }

    .import-info-box h6 {
        margin: 0 0 6px;
        font-size: 0.88rem;
        font-weight: 700;
        color: #1e293b;
    }

    .import-info-box ul {
        margin: 0;
        padding-left: 18px;
        font-size: 0.81rem;
        color: #475569;
        line-height: 1.6;
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
        position: relative;
        cursor: pointer;
    }

    .dropzone-upload:hover, .dropzone-upload.dragover {
        border-color: #096dd9;
        background: #f0f7ff;
    }

    .dropzone-icon {
        font-size: 40px;
        color: #096dd9;
        margin-bottom: 12px;
    }

    .dropzone-title {
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
        background: #096dd9;
        border: 1px solid #096dd9;
        border-radius: 8px;
        box-shadow: 0 2px 6px rgba(9, 109, 217, 0.25);
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-submit-import:hover {
        background: #085fc4;
        border-color: #085fc4;
    }
</style>

<div class="import-reagent-page">

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

    <div class="import-reagent-card">
        <div class="import-header">
            <div class="import-header-left">
                <div class="import-header-icon">
                    <i class="bi bi-file-earmark-excel"></i>
                </div>
                <div>
                    <h5 class="import-header-title">Impor Data Master Reagen</h5>
                    <p class="import-header-subtitle">
                        Unggah file spreadsheet inventaris reagen (.xlsx) ke dalam database LIMSKU.
                    </p>
                </div>
            </div>

            <a href="<?php echo site_url('reagen/download_template'); ?>" class="btn-download-tpl">
                <i class="bi bi-download"></i> Unduh Template Excel
            </a>
        </div>

        <div class="import-body">
            
            <div class="import-info-box">
                <i class="bi bi-info-circle-fill"></i>
                <div>
                    <h6>Petunjuk &amp; Struktur Kolom Impor:</h6>
                    <ul>
                        <li>Urutan 8 kolom tabel resmi: <strong>No</strong>, <strong>Kode Barang</strong>, <strong>Katalog</strong>, <strong>Nama Barang</strong> (wajib), <strong>Merk</strong>, <strong>Stok</strong>, <strong>Jenis Barang</strong>, <strong>Expired Date</strong>.</li>
                        <li><strong>Format File:</strong> Hanya mendukung file spreadsheet Excel modern berformat <strong>.xlsx</strong> (arsip OpenXML) dengan ukuran maksimal <strong>5 MB</strong>. Format biner <code>.xls</code> lama tidak didukung.</li>
                        <li><strong>No:</strong> Hanya nomor urut tampilan di spreadsheet, tidak digunakan sebagai primary key/ID database.</li>
                        <li><strong>Stok:</strong> Mempertahankan kuantitas dan satuan (contoh: <code>1 PAC</code>, <code>3 PAC</code>, <code>0 BOTOL</code>, <code>0 PCS</code>, <code>0 KTK</code>).</li>
                        <li><strong>Expired Date:</strong> Format <code>YYYY-MM-DD</code> atau <code>DD/MM/YYYY</code>. Boleh kosong jika tidak tersedia (akan disimpan NULL, bukan <code>0000-00-00</code>).</li>
                        <li><strong>Validasi &amp; Duplikasi:</strong> Sistem akan menampilkan halaman <em>Pratinjau</em> validasi sebelum data disimpan, dan memberikan opsi untuk melewati atau memperbarui data duplikat secara aman.</li>
                    </ul>
                </div>
            </div>

            <?php echo form_open_multipart('reagen/import', array('id' => 'form-import-reagen')); ?>
                
                <div class="dropzone-upload" id="dropzone-area">
                    <input type="file" name="file_excel" id="file_excel" class="file-input-hidden" accept=".xlsx" required>
                    <div class="dropzone-icon">
                        <i class="bi bi-cloud-arrow-up"></i>
                    </div>
                    <div class="dropzone-title">Pilih atau Seret File Excel (.xlsx) ke Sini</div>
                    <div class="dropzone-hint">Format .xlsx (maksimal ukuran file 5 MB)</div>
                </div>

                <div class="selected-file-info" id="file-info-box">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-file-earmark-spreadsheet text-success fs-5"></i>
                        <span id="file-name-text">Nama File</span>
                        <span class="badge bg-secondary" id="file-size-text">0 KB</span>
                    </div>
                    <button type="button" class="btn btn-sm btn-link text-danger p-0 text-decoration-none" id="btn-remove-file">
                        <i class="bi bi-x-circle"></i> Batal
                    </button>
                </div>

                <div class="form-actions">
                    <a href="<?php echo site_url('reagen'); ?>" class="btn-cancel">
                        Kembali
                    </a>
                    <button type="submit" class="btn-submit-import">
                        <i class="bi bi-eye"></i>
                        Unggah &amp; Periksa Pratinjau
                    </button>
                </div>

            <?php echo form_close(); ?>

        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var fileInput = document.getElementById('file_excel');
    var dropzone = document.getElementById('dropzone-area');
    var fileInfo = document.getElementById('file-info-box');
    var fileName = document.getElementById('file-name-text');
    var fileSize = document.getElementById('file-size-text');
    var btnRemove = document.getElementById('btn-remove-file');

    fileInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            var f = this.files[0];
            fileName.textContent = f.name;
            fileSize.textContent = (f.size / 1024).toFixed(1) + ' KB';
            fileInfo.style.display = 'flex';
        }
    });

    btnRemove.addEventListener('click', function() {
        fileInput.value = '';
        fileInfo.style.display = 'none';
    });

    ['dragenter', 'dragover'].forEach(function(eventName) {
        dropzone.addEventListener(eventName, function(e) {
            e.preventDefault();
            dropzone.classList.add('dragover');
        });
    });

    ['dragleave', 'drop'].forEach(function(eventName) {
        dropzone.addEventListener(eventName, function(e) {
            e.preventDefault();
            dropzone.classList.remove('dragover');
        });
    });
});
</script>
