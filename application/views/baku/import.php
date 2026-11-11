<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
    .baku-import-page {
        color: #1e293b;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }
    
    .import-header-card {
        background: #ffffff;
        border: 1px solid #edf2f7;
        border-radius: 14px;
        padding: 18px 22px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
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

    /* Info Guidance Card */
    .import-guidance-card {
        background: #f0fdf4;
        border: 1px solid #dcfce7;
        border-radius: 14px;
        padding: 20px 24px;
        margin-bottom: 22px;
    }

    .import-guidance-title {
        color: #166534;
        font-size: 0.88rem;
        font-weight: 700;
        margin-bottom: 0.65rem;
        display: flex;
        align-items: center;
        gap: 0.45rem;
    }

    .guidance-list {
        list-style: none;
        padding-left: 0;
        margin-bottom: 0;
    }

    .guidance-list li {
        font-size: 0.82rem;
        color: #15803d;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: flex-start;
        gap: 0.5rem;
        line-height: 1.45;
    }

    .guidance-list li:last-child {
        margin-bottom: 0;
    }

    .guidance-list li i {
        color: #16a34a;
        margin-top: 2px;
        flex-shrink: 0;
    }

    .guidance-list code {
        background: #dcfce7;
        color: #14532d;
        padding: 1px 5px;
        border-radius: 4px;
        font-size: 0.78rem;
    }

    /* Upload Form Card */
    .import-form-card {
        background: #ffffff;
        border: 1px solid #edf2f7;
        border-radius: 14px;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
        padding: 24px;
    }

    .import-card-title {
        font-size: 0.92rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.45rem;
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

    .btn-baku-submit-green {
        background: #16a34a;
        border: 1px solid #16a34a;
        color: #ffffff;
        font-weight: 600;
        font-size: 0.82rem;
        border-radius: 8px;
        padding: 0.5rem 1.4rem;
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        transition: all 0.2s ease;
    }

    .btn-baku-submit-green:hover {
        background: #15803d;
        border-color: #15803d;
        color: #ffffff;
        box-shadow: 0 3px 8px rgba(22, 163, 74, 0.25);
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
</style>

<div class="baku-import-page">

    <!-- Tombol Kembali -->
    <div class="mb-3">
        <a href="<?php echo site_url('baku'); ?>" class="btn-baku-back">
            <i class="bi bi-arrow-left"></i> Kembali ke Master Baku
        </a>
    </div>

    <!-- Petunjuk Format File (Clean Card) -->
    <div class="import-guidance-card">
        <h6 class="import-guidance-title">
            <i class="bi bi-info-circle-fill"></i> Format &amp; Ketentuan File Excel
        </h6>
        <ul class="guidance-list">
            <li>
                <i class="bi bi-check2-circle"></i>
                <span>Mendukung format file multi-sheet standar BPOM (Sheet: <em>RUANG BAKU PEMBANDING</em>, <em>SHOWCASE KIMIA</em>, <em>FREZEER DISOLUSI</em>, dan <em>COA KEMURNIAN</em>) atau file format ringkas 1 sheet.</span>
            </li>
            <li>
                <i class="bi bi-check2-circle"></i>
                <span>Kolom baku standar: <strong>No</strong>, <strong>Nama Baku</strong>, <strong>No. Kontrol</strong>, <strong>Kode Posisi</strong>.</span>
            </li>
            <li>
                <i class="bi bi-check2-circle"></i>
                <span>Sistem akan <strong>otomatis mengekstrak nilai Kadar Air (%) dan Kadar Baku KCKT (%)</strong> dari teks pada sheet COA / kolom kemurnian (contoh: <code>Kadar air = 8,33%</code> menjadi <code>8.33</code>, dan <code>KCKT = 97,60%</code> menjadi <code>97.60</code>).</span>
            </li>
            <li>
                <i class="bi bi-check2-circle"></i>
                <span>Jika baris data tidak memuat keterangan kadar air maupun KCKT, kolom tersebut akan dibiarkan <strong>kosong (NULL)</strong> secara otomatis.</span>
            </li>
        </ul>
    </div>

    <!-- Form Upload (Clean Card) -->
    <div class="import-form-card">
        <h6 class="import-card-title">
            <i class="bi bi-cloud-arrow-up text-primary"></i> Unggah Berkas Spreadsheet (.xlsx)
        </h6>
        <form action="<?php echo site_url('baku/import'); ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

            <div class="row g-3 mb-4">
                <div class="col-md-8">
                    <label class="baku-label">Pilih File Spreadsheet Excel <span class="text-danger">*</span></label>
                    <input type="file" name="file_excel" class="form-control baku-input" accept=".xlsx" required>
                    <div class="form-text" style="font-size: 0.72rem;">Maksimal ukuran file 5 MB. Format file harus berekstensi <code>.xlsx</code>.</div>
                </div>

                <div class="col-md-4">
                    <label class="baku-label">Mode Impor Data <span class="text-danger">*</span></label>
                    <select name="mode_impor" class="form-select baku-input" required>
                        <option value="replace" selected>Timpa / Ganti Seluruh Data (Replace)</option>
                        <option value="append">Tambahkan ke Data yang Sudah Ada (Append)</option>
                    </select>
                    <div class="form-text" style="font-size: 0.72rem;">Pilih 'Replace' untuk menyelaraskan data dengan versi file Excel terbaru.</div>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 border-top pt-3">
                <a href="<?php echo site_url('baku'); ?>" class="btn-baku-cancel">Batal</a>
                <button type="submit" class="btn-baku-submit-green" onclick="return confirm('Mulai proses impor data baku dari file Excel?');">
                    <i class="bi bi-upload"></i>
                    <span>Mulai Impor &amp; Normalisasi Data</span>
                </button>
            </div>
        </form>
    </div>

</div>
