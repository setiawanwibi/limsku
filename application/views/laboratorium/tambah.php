
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
    .laboratory-create-page {
        width: 100%;
        padding: 8px 0 24px;
    }

    .laboratory-create-card {
        width: 100%;
        max-width: 700px;
        margin: 0 auto;
        background: #ffffff;
        border: 1px solid #e0e8ed;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(41, 66, 87, 0.06);
        overflow: hidden;
    }

    .laboratory-create-header {
        display: flex;
        align-items: center;
        gap: 13px;
        padding: 18px 22px;
        background: #ffffff;
        border-bottom: 1px solid #e8eef1;
    }

    .laboratory-create-icon {
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

    .laboratory-create-title {
        margin: 0;
        color: #294257;
        font-size: 1.08rem;
        font-weight: 700;
    }

    .laboratory-create-subtitle {
        margin: 4px 0 0;
        color: #7b8b98;
        font-size: 0.8rem;
        line-height: 1.5;
    }

    .laboratory-create-body {
        padding: 23px 22px 21px;
    }

    .laboratory-create-page .form-section-title {
        display: flex;
        align-items: center;
        gap: 7px;
        margin: 0 0 15px;
        color: #294257;
        font-size: 0.88rem;
        font-weight: 700;
    }

    .laboratory-create-page .form-section-title i {
        color: #159b98;
        font-size: 1rem;
    }

    .laboratory-create-page .form-label {
        display: block;
        margin-bottom: 7px;
        color: #354b5d;
        font-size: 0.88rem;
        font-weight: 600;
    }

    .laboratory-create-page .form-control,
    .laboratory-create-page .form-select {
        min-height: 42px;
        padding: 9px 12px;
        border: 1px solid #d8e2e8;
        border-radius: 7px;
        color: #294257;
        font-size: 0.86rem;
        box-shadow: none;
    }

    .laboratory-create-page .form-select {
        padding-right: 34px;
    }

    .laboratory-create-page textarea.form-control {
        min-height: 95px;
        resize: vertical;
    }

    .laboratory-create-page .form-control::placeholder {
        color: #a0adb7;
        font-size: 0.82rem;
    }

    .laboratory-create-page .form-control:focus,
    .laboratory-create-page .form-select:focus {
        border-color: #159b98;
        box-shadow: 0 0 0 3px rgba(21, 155, 152, 0.11);
    }

    .laboratory-create-page .required-mark {
        color: #dc5353;
    }

    .laboratory-create-page .form-divider {
        margin: 21px 0;
        border: 0;
        border-top: 1px solid #edf1f3;
        opacity: 1;
    }

    .laboratory-create-page .alert {
        margin-bottom: 20px;
        border-radius: 8px;
        font-size: 0.86rem;
    }

    .laboratory-create-page .alert div {
        margin-bottom: 3px;
    }

    .laboratory-create-page .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 9px;
        padding-top: 18px;
        margin-top: 22px;
        border-top: 1px solid #edf1f3;
    }

    .laboratory-create-page .btn {
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

    .laboratory-create-page .btn-cancel {
        color: #526675;
        background: #ffffff;
        border: 1px solid #d8e2e8;
    }

    .laboratory-create-page .btn-cancel:hover {
        background: #f4f7f9;
        border-color: #bdcbd4;
    }

    .laboratory-create-page .btn-save {
        color: #ffffff;
        background: #159b98;
        border: 1px solid #159b98;
    }

    .laboratory-create-page .btn-save:hover {
        color: #ffffff;
        background: #118582;
        border-color: #118582;
        box-shadow: 0 3px 8px rgba(21, 155, 152, 0.18);
    }

    @media (max-width: 576px) {
        .laboratory-create-page {
            padding-top: 0;
        }

        .laboratory-create-header {
            padding: 16px;
        }

        .laboratory-create-body {
            padding: 19px 16px;
        }

        .laboratory-create-title {
            font-size: 1rem;
        }

        .laboratory-create-page .form-actions {
            flex-direction: column-reverse;
        }

        .laboratory-create-page .form-actions .btn {
            width: 100%;
        }
    }
</style>

<div class="laboratory-create-page">
    <div class="laboratory-create-card">

        <div class="laboratory-create-header">
            <div class="laboratory-create-icon">
                <i class="bi bi-building"></i>
            </div>

            <div>
                <h5 class="laboratory-create-title">
                    Tambah Laboratorium Baru
                </h5>
                <p class="laboratory-create-subtitle">
                    Lengkapi informasi laboratorium atau unit pelaksana pengujian.
                </p>
            </div>
        </div>

        <div class="laboratory-create-body">

            <?php if (validation_errors()): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <div class="fw-semibold mb-1">
                        <i class="bi bi-exclamation-circle me-1"></i>
                        Terdapat kesalahan pada input:
                    </div>

                    <?php echo validation_errors('<div>', '</div>'); ?>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Close">
                    </button>
                </div>
            <?php endif; ?>

            <form action="<?php echo site_url('laboratorium/tambah'); ?>" method="post">

                <input
                    type="hidden"
                    name="<?php echo $this->security->get_csrf_token_name(); ?>"
                    value="<?php echo $this->security->get_csrf_hash(); ?>"
                >

                <h6 class="form-section-title">
                    <i class="bi bi-info-circle"></i>
                    Informasi Laboratorium
                </h6>

                <div class="mb-3">
                    <label for="kode_lab" class="form-label">
                        Kode Laboratorium <span class="required-mark">*</span>
                    </label>

                    <input
                        type="text"
                        class="form-control text-uppercase"
                        id="kode_lab"
                        name="kode_lab"
                        value="<?php echo set_value('kode_lab'); ?>"
                        placeholder="Contoh: LAB-KIM, LAB-MIK"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label for="nama_lab" class="form-label">
                        Nama Laboratorium <span class="required-mark">*</span>
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        id="nama_lab"
                        name="nama_lab"
                        value="<?php echo set_value('nama_lab'); ?>"
                        placeholder="Contoh: Laboratorium Kimia"
                        required
                    >
                </div>

                <hr class="form-divider">

                <h6 class="form-section-title">
                    <i class="bi bi-card-text"></i>
                    Deskripsi dan Status
                </h6>

                <div class="mb-3">
                    <label for="deskripsi" class="form-label">
                        Deskripsi (Opsional)
                    </label>

                    <textarea
                        class="form-control"
                        id="deskripsi"
                        name="deskripsi"
                        rows="3"
                        placeholder="Keterangan bidang pengujian laboratorium"
                    ><?php echo set_value('deskripsi'); ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">
                        Status Laboratorium
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
                    <a href="<?php echo site_url('laboratorium'); ?>" class="btn btn-cancel">
                        <i class="bi bi-arrow-left"></i>
                        Batal
                    </a>

                    <button type="submit" class="btn btn-save">
                        <i class="bi bi-check2-circle"></i>
                        Simpan Laboratorium
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>