<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
    .kt-form-page {
        width: 100%;
        padding: 4px 0 24px;
        color: #294257;
    }
    .kt-form-card {
        width: 100%;
        max-width: 700px;
        background: #ffffff;
        border: 1px solid #e0e8ed;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(41, 66, 87, 0.05);
        overflow: hidden;
    }
    .kt-form-header {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 18px 24px;
        border-bottom: 1px solid #e8eef1;
        background: #ffffff;
    }
    .kt-form-header-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        background: #f0f5ff;
        color: #2f54eb;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }
    .kt-form-title {
        margin: 0;
        color: #294257;
        font-size: 1.1rem;
        font-weight: 700;
    }
    .kt-form-subtitle {
        margin: 4px 0 0;
        color: #7b8b98;
        font-size: 0.82rem;
    }
    .kt-form-body {
        padding: 24px 26px;
    }
    .form-group-custom {
        margin-bottom: 20px;
    }
    .form-label-custom {
        font-size: 0.84rem;
        font-weight: 600;
        color: #334e68;
        margin-bottom: 6px;
        display: block;
    }
    .form-control-custom, .form-select-custom {
        border: 1px solid #cbd5e0;
        border-radius: 8px;
        padding: 9px 12px;
        font-size: 0.86rem;
        color: #2d3748;
        width: 100%;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .form-control-custom:focus, .form-select-custom:focus {
        border-color: #2f54eb;
        box-shadow: 0 0 0 3px rgba(47, 84, 235, 0.15);
        outline: none;
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
    .btn-submit {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 22px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #ffffff;
        background: #2f54eb;
        border: 1px solid #2f54eb;
        border-radius: 8px;
        box-shadow: 0 2px 6px rgba(47, 84, 235, 0.25);
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .btn-submit:hover {
        background: #1d39c4;
        border-color: #1d39c4;
    }
</style>

<div class="kt-form-page">
    <div class="kt-form-card">
        <div class="kt-form-header">
            <div class="kt-form-header-icon">
                <i class="bi bi-diagram-3"></i>
            </div>
            <div>
                <h5 class="kt-form-title">Tambah Master Kelas Terapi</h5>
                <p class="kt-form-subtitle">Tambahkan klasifikasi farmakologi kelas terapi baru ke database.</p>
            </div>
        </div>

        <div class="kt-form-body">
            <?php if (validation_errors()): ?>
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <h6 class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i> Kesalahan Input:</h6>
                    <?php echo validation_errors('<div class="small">- ', '</div>'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php echo form_open('kelas-terapi/tambah'); ?>
                <div class="form-group-custom">
                    <label for="nama_kelas_terapi" class="form-label-custom">
                        Nama Kelas Terapi <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="nama_kelas_terapi" id="nama_kelas_terapi" class="form-control-custom"
                           value="<?php echo set_value('nama_kelas_terapi'); ?>"
                           placeholder="Contoh: KELAS TERAPI ANTIINFEKSI UMUM UNTUK PENGGUNAAN SISTEMIK" maxlength="150" required>
                    <small class="text-muted d-block mt-1">Nama kelas terapi farmakologi.</small>
                </div>

                <div class="form-group-custom">
                    <label for="status" class="form-label-custom">Status</label>
                    <select name="status" id="status" class="form-select-custom">
                        <option value="Aktif" <?php echo set_select('status', 'Aktif', TRUE); ?>>Aktif</option>
                        <option value="Nonaktif" <?php echo set_select('status', 'Nonaktif'); ?>>Nonaktif</option>
                    </select>
                </div>

                <div class="form-actions">
                    <a href="<?php echo site_url('kelas-terapi'); ?>" class="btn-cancel">Batal</a>
                    <button type="submit" class="btn-submit">
                        <i class="bi bi-check-lg me-1"></i> Simpan
                    </button>
                </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
