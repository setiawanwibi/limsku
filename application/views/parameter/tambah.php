
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
    .parameter-create-page {
        width: 100%;
        padding: 8px 0 24px;
    }

    .parameter-create-card {
        width: 100%;
        max-width: 700px;
        margin: 0 auto;
        background: #ffffff;
        border: 1px solid #e0e8ed;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(41, 66, 87, 0.06);
        overflow: hidden;
    }

    .parameter-create-header {
        display: flex;
        align-items: center;
        gap: 13px;
        padding: 18px 22px;
        background: #ffffff;
        border-bottom: 1px solid #e8eef1;
    }

    .parameter-create-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        width: 42px;
        height: 42px;
        border-radius: 10px;
        background: #e5f6f4;
        color: #159b98;
        font-size: 20px;
    }

    .parameter-create-title {
        margin: 0;
        color: #294257;
        font-size: 1.08rem;
        font-weight: 700;
    }

    .parameter-create-subtitle {
        margin: 4px 0 0;
        color: #7b8b98;
        font-size: 0.8rem;
        line-height: 1.5;
    }

    .parameter-create-body {
        padding: 23px 22px 21px;
    }

    .parameter-create-page .form-label {
        display: block;
        margin-bottom: 7px;
        color: #354b5d;
        font-size: 0.88rem;
        font-weight: 600;
    }

    .parameter-create-page .form-control,
    .parameter-create-page .form-select {
        min-height: 42px;
        border: 1px solid #d8e2e8;
        border-radius: 7px;
        color: #294257;
        font-size: 0.86rem;
        box-shadow: none;
    }

    .parameter-create-page .form-control {
        padding: 9px 12px;
    }

    .parameter-create-page .form-select {
        padding: 9px 34px 9px 12px;
    }

    .parameter-create-page .form-control::placeholder {
        color: #a0adb7;
        font-size: 0.82rem;
    }

    .parameter-create-page .form-control:focus,
    .parameter-create-page .form-select:focus {
        border-color: #159b98;
        box-shadow: 0 0 0 3px rgba(21, 155, 152, 0.11);
    }

    .parameter-create-page .field-hint {
        margin-top: 5px;
        color: #8997a2;
        font-size: 0.76rem;
    }

    .parameter-create-page .required-mark {
        color: #dc5353;
    }

    .parameter-create-page .form-section-title {
        display: flex;
        align-items: center;
        gap: 7px;
        margin: 0 0 15px;
        color: #294257;
        font-size: 0.88rem;
        font-weight: 700;
    }

    .parameter-create-page .form-section-title i {
        color: #159b98;
        font-size: 1rem;
    }

    .parameter-create-page .form-divider {
        margin: 21px 0;
        border: 0;
        border-top: 1px solid #edf1f3;
        opacity: 1;
    }

    .parameter-create-page .alert {
        margin-bottom: 20px;
        border-radius: 8px;
        font-size: 0.86rem;
    }

    .parameter-create-page .alert div {
        margin-bottom: 3px;
    }

    .parameter-create-page .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 9px;
        padding-top: 18px;
        margin-top: 22px;
        border-top: 1px solid #edf1f3;
    }

    .parameter-create-page .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 39px;
        padding: 8px 15px;
        border-radius: 7px;
        font-size: 0.83rem;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .parameter-create-page .btn-cancel {
        color: #526675;
        background: #ffffff;
        border: 1px solid #d8e2e8;
    }

    .parameter-create-page .btn-cancel:hover {
        background: #f4f7f9;
        border-color: #bdcbd4;
    }

    .parameter-create-page .btn-save {
        color: #ffffff;
        background: #159b98;
        border: 1px solid #159b98;
    }

    .parameter-create-page .btn-save:hover {
        color: #ffffff;
        background: #118582;
        border-color: #118582;
        box-shadow: 0 3px 8px rgba(21, 155, 152, 0.18);
    }

    @media (max-width: 576px) {
        .parameter-create-page {
            padding-top: 0;
        }

        .parameter-create-header {
            padding: 16px;
        }

        .parameter-create-body {
            padding: 19px 16px;
        }

        .parameter-create-title {
            font-size: 1rem;
        }

        .parameter-create-page .form-actions {
            flex-direction: column-reverse;
        }

        .parameter-create-page .form-actions .btn {
            width: 100%;
        }
    }
</style>

<div class="parameter-create-page">
    <div class="parameter-create-card">

        <div class="parameter-create-header">
            <div class="parameter-create-icon">
                <i class="bi bi-sliders"></i>
            </div>

            <div>
                <h5 class="parameter-create-title">
                    Tambah Master Parameter Pengujian
                </h5>
                <p class="parameter-create-subtitle">
                    Lengkapi informasi parameter yang akan digunakan dalam pengujian laboratorium.
                </p>
            </div>
        </div>

        <div class="parameter-create-body">

            <?php if (validation_errors()): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <div class="fw-semibold mb-1">
                        <i class="bi bi-exclamation-circle me-1"></i>
                        Terdapat kesalahan pada input:
                    </div>

                    <?php echo validation_errors('<div>', '</div>'); ?>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form action="<?php echo site_url('parameter/tambah'); ?>" method="post">

                <input
                    type="hidden"
                    name="<?php echo $this->security->get_csrf_token_name(); ?>"
                    value="<?php echo $this->security->get_csrf_hash(); ?>"
                >

                <h6 class="form-section-title">
                    <i class="bi bi-info-circle"></i>
                    Informasi Parameter
                </h6>

                <div class="mb-3">
                    <label for="kode_parameter" class="form-label">
                        Kode Parameter <span class="required-mark">*</span>
                    </label>

                    <input
                        type="text"
                        class="form-control text-uppercase"
                        id="kode_parameter"
                        name="kode_parameter"
                        value="<?php echo set_value('kode_parameter'); ?>"
                        placeholder="Contoh: PAR-KADAR, PAR-pH, PAR-ALT"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label for="nama_parameter" class="form-label">
                        Nama Parameter <span class="required-mark">*</span>
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        id="nama_parameter"
                        name="nama_parameter"
                        value="<?php echo set_value('nama_parameter'); ?>"
                        placeholder="Contoh: Penetapan Kadar Parasetamol"
                        required
                    >
                </div>

                <hr class="form-divider">

                <h6 class="form-section-title">
                    <i class="bi bi-clipboard-data"></i>
                    Detail Pengujian
                </h6>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="satuan" class="form-label">
                            Satuan Hasil
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="satuan"
                            name="satuan"
                            value="<?php echo set_value('satuan'); ?>"
                            placeholder="Contoh: %, mg/mL, CFU/g, pH"
                        >
                    </div>

                    <div class="col-md-6">
                        <label for="kategori" class="form-label">
                            Kategori Bidang
                        </label>

                        <select class="form-select" id="kategori" name="kategori">
                            <option value="Kimia" <?php echo set_select('kategori', 'Kimia', TRUE); ?>>
                                Kimia
                            </option>

                            <option value="Mikrobiologi" <?php echo set_select('kategori', 'Mikrobiologi'); ?>>
                                Mikrobiologi
                            </option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="baku_mutu" class="form-label">
                        Baku Mutu / Persyaratan Spesifikasi
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        id="baku_mutu"
                        name="baku_mutu"
                        value="<?php echo set_value('baku_mutu'); ?>"
                        placeholder="Contoh: 90.0% - 110.0%, ALT <= 10^4 CFU/g"
                    >

                    <div class="field-hint">
                        Isi persyaratan atau rentang nilai yang menjadi acuan pengujian jika tersedia.
                    </div>
                </div>

                <hr class="form-divider">

                <h6 class="form-section-title">
                    <i class="bi bi-toggle-on"></i>
                    Status Parameter
                </h6>

                <div class="mb-3">
                    <label for="status" class="form-label">
                        Status Parameter
                    </label>

                    <select class="form-select" id="status" name="status">
                        <option value="Aktif" <?php echo set_select('status', 'Aktif', TRUE); ?>>
                            Aktif
                        </option>

                        <option value="Nonaktif" <?php echo set_select('status', 'Nonaktif'); ?>>
                            Nonaktif
                        </option>
                    </select>
                </div>

                <div class="form-actions">
                    <a href="<?php echo site_url('parameter'); ?>" class="btn btn-cancel">
                        <i class="bi bi-arrow-left"></i>
                        Batal
                    </a>

                    <button type="submit" class="btn btn-save">
                        <i class="bi bi-check2-circle"></i>
                        Simpan Parameter
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>