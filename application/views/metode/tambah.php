
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
    .method-create-page {
        width: 100%;
        padding: 8px 0 24px;
    }

    .method-create-card {
        width: 100%;
        max-width: 700px;
        margin: 0 auto;
        background: #ffffff;
        border: 1px solid #e0e8ed;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(41, 66, 87, 0.06);
        overflow: hidden;
    }

    .method-create-header {
        display: flex;
        align-items: center;
        gap: 13px;
        padding: 18px 22px;
        background: #ffffff;
        border-bottom: 1px solid #e8eef1;
    }

    .method-create-icon {
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

    .method-create-title {
        margin: 0;
        color: #294257;
        font-size: 1.08rem;
        font-weight: 700;
    }

    .method-create-subtitle {
        margin: 4px 0 0;
        color: #7b8b98;
        font-size: 0.8rem;
        line-height: 1.5;
    }

    .method-create-body {
        padding: 23px 22px 21px;
    }

    .method-create-page .form-section-title {
        display: flex;
        align-items: center;
        gap: 7px;
        margin: 0 0 15px;
        color: #294257;
        font-size: 0.88rem;
        font-weight: 700;
    }

    .method-create-page .form-section-title i {
        color: #159b98;
        font-size: 1rem;
    }

    .method-create-page .form-label {
        display: block;
        margin-bottom: 7px;
        color: #354b5d;
        font-size: 0.88rem;
        font-weight: 600;
    }

    .method-create-page .form-control,
    .method-create-page .form-select {
        min-height: 42px;
        padding: 9px 12px;
        border: 1px solid #d8e2e8;
        border-radius: 7px;
        color: #294257;
        font-size: 0.86rem;
        box-shadow: none;
    }

    .method-create-page .form-select {
        padding-right: 34px;
    }

    .method-create-page textarea.form-control {
        min-height: 90px;
        resize: vertical;
    }

    .method-create-page .form-control::placeholder {
        color: #a0adb7;
        font-size: 0.82rem;
    }

    .method-create-page .form-control:focus,
    .method-create-page .form-select:focus {
        border-color: #159b98;
        box-shadow: 0 0 0 3px rgba(21, 155, 152, 0.11);
    }

    .method-create-page .required-mark {
        color: #dc5353;
    }

    .method-create-page .form-divider {
        margin: 21px 0;
        border: 0;
        border-top: 1px solid #edf1f3;
        opacity: 1;
    }

    .method-create-page .alert {
        margin-bottom: 20px;
        border-radius: 8px;
        font-size: 0.86rem;
    }

    .method-create-page .alert div {
        margin-bottom: 3px;
    }

    .method-create-page .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 9px;
        padding-top: 18px;
        margin-top: 22px;
        border-top: 1px solid #edf1f3;
    }

    .method-create-page .btn {
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

    .method-create-page .btn-cancel {
        color: #526675;
        background: #ffffff;
        border: 1px solid #d8e2e8;
    }

    .method-create-page .btn-cancel:hover {
        background: #f4f7f9;
        border-color: #bdcbd4;
    }

    .method-create-page .btn-save {
        color: #ffffff;
        background: #159b98;
        border: 1px solid #159b98;
    }

    .method-create-page .btn-save:hover {
        color: #ffffff;
        background: #118582;
        border-color: #118582;
        box-shadow: 0 3px 8px rgba(21, 155, 152, 0.18);
    }

    @media (max-width: 576px) {
        .method-create-page {
            padding-top: 0;
        }

        .method-create-header {
            padding: 16px;
        }

        .method-create-body {
            padding: 19px 16px;
        }

        .method-create-title {
            font-size: 1rem;
        }

        .method-create-page .form-actions {
            flex-direction: column-reverse;
        }

        .method-create-page .form-actions .btn {
            width: 100%;
        }
    }
</style>

<div class="method-create-page">
    <div class="method-create-card">

        <div class="method-create-header">
            <div class="method-create-icon">
                <i class="bi bi-journal-check"></i>
            </div>

            <div>
                <h5 class="method-create-title">
                    Tambah Master Metode Pengujian
                </h5>
                <p class="method-create-subtitle">
                    Lengkapi informasi metode dan acuan pengujian laboratorium.
                </p>
            </div>
        </div>

        <div class="method-create-body">

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

            <form action="<?php echo site_url('metode/tambah'); ?>" method="post">

                <input
                    type="hidden"
                    name="<?php echo $this->security->get_csrf_token_name(); ?>"
                    value="<?php echo $this->security->get_csrf_hash(); ?>"
                >

                <h6 class="form-section-title">
                    <i class="bi bi-info-circle"></i>
                    Informasi Metode
                </h6>

                <div class="mb-3">
                    <label for="kode_metode" class="form-label">
                        Kode Metode <span class="required-mark">*</span>
                    </label>

                    <input
                        type="text"
                        class="form-control text-uppercase"
                        id="kode_metode"
                        name="kode_metode"
                        value="<?php echo set_value('kode_metode'); ?>"
                        placeholder="Contoh: MET-KIM-01, MET-MIK-02"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label for="nama_metode" class="form-label">
                        Nama Metode Pengujian <span class="required-mark">*</span>
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        id="nama_metode"
                        name="nama_metode"
                        value="<?php echo set_value('nama_metode'); ?>"
                        placeholder="Contoh: Penetapan Kadar Secara Spektrofotometri UV-Vis"
                        required
                    >
                </div>

                <hr class="form-divider">

                <h6 class="form-section-title">
                    <i class="bi bi-clipboard-data"></i>
                    Kategori dan Acuan
                </h6>

                <div class="row g-3 mb-3">
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

                    <div class="col-md-6">
                        <label for="versi" class="form-label">
                            Versi / Farmakope Acuan
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="versi"
                            name="versi"
                            value="<?php echo set_value('versi'); ?>"
                            placeholder="Contoh: FI Edisi VI / MA 2024"
                        >
                    </div>
                </div>

                <div class="mb-3">
                    <label for="keterangan" class="form-label">
                        Keterangan (Opsional)
                    </label>

                    <textarea
                        class="form-control"
                        id="keterangan"
                        name="keterangan"
                        rows="3"
                        placeholder="Tambahkan keterangan jika diperlukan..."
                    ><?php echo set_value('keterangan'); ?></textarea>
                </div>

                <hr class="form-divider">

                <h6 class="form-section-title">
                    <i class="bi bi-toggle-on"></i>
                    Status Metode
                </h6>

                <div class="mb-3">
                    <label for="status" class="form-label">
                        Status Metode
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
                    <a href="<?php echo site_url('metode'); ?>" class="btn btn-cancel">
                        <i class="bi bi-arrow-left"></i>
                        Batal
                    </a>

                    <button type="submit" class="btn btn-save">
                        <i class="bi bi-check2-circle"></i>
                        Simpan Metode
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>