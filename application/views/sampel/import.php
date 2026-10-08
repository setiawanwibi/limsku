<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
    /* =========================================================
       IMPORT SAMPEL
       Tampilan lebih besar
       Fungsi tetap sama
       ========================================================= */

    .import-page {
        color: #294257;
        font-size: 0.95rem;
    }

    /* =========================================================
       CARD UTAMA
       ========================================================= */

    .import-card {
        width: 100%;
        max-width: 900px;
        background: #fff;
        border: 1px solid #e0e8ed;
        border-radius: 11px;
        box-shadow: 0 5px 18px rgba(35, 58, 76, 0.07);
        overflow: hidden;
    }

    .import-card-header {
        min-height: 62px;
        padding: 16px 20px;
        border-bottom: 1px solid #e8eef2;
        background: #fff;
        display: flex;
        align-items: center;
    }

    .import-card-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0;
        color: #294257;
        font-size: 1rem;
        font-weight: 700;
    }

    .import-card-title i {
        color: #159b98;
        font-size: 1.15rem;
    }

    .import-card-body {
        padding: 24px 22px;
    }

    /* =========================================================
       ALERT
       ========================================================= */

    .import-page .alert {
        border-radius: 8px;
        font-size: 0.84rem;
        line-height: 1.6;
    }

    .import-page .alert h6 {
        font-size: 0.88rem;
    }

    .import-page .alert li {
        margin-bottom: 4px;
    }

    .import-page .alert .btn-close {
        font-size: 0.7rem;
    }

    /* =========================================================
       INFORMASI TEMPLATE
       ========================================================= */

    .import-info {
        display: flex;
        align-items: flex-start;
        gap: 13px;
        padding: 17px 18px;
        margin-bottom: 23px;
        border: 1px solid #cfe8e7 !important;
        border-radius: 9px;
        background: #f2faf9 !important;
        color: #526f7e;
    }

    .import-info-icon {
        flex: 0 0 auto;
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: #dff3f1;
        color: #159b98;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.05rem;
    }

    .import-info-content {
        min-width: 0;
    }

    .import-info-title {
        margin: 0 0 8px;
        color: #294257;
        font-size: 0.88rem;
        font-weight: 700;
    }

    .import-info-list {
        padding-left: 21px;
        margin: 0;
        color: #657b8b;
        font-size: 0.80rem;
        line-height: 1.7;
    }

    .import-info-list li {
        margin-bottom: 3px;
    }

    .import-info-list strong {
        color: #526b7b;
    }

    /* =========================================================
       ERROR DETAIL
       ========================================================= */

    .import-error {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 16px 17px;
        margin-bottom: 19px;
        border: 1px solid #ead9a8 !important;
        border-radius: 9px;
        background: #fffaf0 !important;
    }

    .import-error-icon {
        flex: 0 0 auto;
        color: #c99720;
        font-size: 1.05rem;
        margin-top: 2px;
    }

    .import-error-title {
        margin: 0 0 6px;
        color: #5e5131;
        font-size: 0.84rem;
        font-weight: 700;
    }

    .import-error-list {
        padding-left: 20px;
        margin: 0;
        color: #766b4c;
        font-size: 0.78rem;
        line-height: 1.6;
    }

    .import-error-list li {
        margin-bottom: 3px;
    }

    /* =========================================================
       FILE UPLOAD
       ========================================================= */

    .import-upload-section {
        margin-top: 4px;
    }

    .import-file-label {
        display: block;
        margin-bottom: 9px;
        color: #526779;
        font-size: 0.82rem;
        font-weight: 700;
    }

    .import-file-label .required {
        color: #dc3545;
        font-size: 0.9rem;
    }

    .import-file-input {
        width: 100%;
        height: 48px;
        padding: 8px 13px;
        border: 1px solid #dfe7eb;
        border-radius: 8px;
        background: #f8fafb;
        color: #526779;
        font-size: 0.80rem;
        box-shadow: none;
        cursor: pointer;
    }

    .import-file-input:hover {
        border-color: #c8d8dc;
        background: #fff;
    }

    .import-file-input:focus {
        border-color: #a9cfcd;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(21, 155, 152, 0.08);
        outline: none;
    }

    .import-file-input::file-selector-button {
        height: 32px;
        margin-right: 10px;
        padding: 0 12px;
        border: 1px solid #cfe2e1;
        border-radius: 6px;
        background: #eaf7f6;
        color: #168d88;
        font-size: 0.76rem;
        font-weight: 700;
        cursor: pointer;
    }

    .import-file-input::file-selector-button:hover {
        background: #dff2f0;
    }

    /* =========================================================
       FOOTER / BUTTON
       ========================================================= */

    .import-form-footer {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 23px;
        padding-top: 17px;
        border-top: 1px solid #edf1f3;
    }

    .import-btn-cancel {
        height: 39px;
        padding: 0 17px;
        border: 1px solid #d7e0e5;
        border-radius: 7px;
        background: #fff;
        color: #657887;
        font-size: 0.78rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
    }

    .import-btn-cancel:hover {
        background: #f7f9fa;
        border-color: #cbd6dc;
        color: #526a79;
    }

    .import-btn-submit {
        height: 39px;
        padding: 0 18px;
        border: 1px solid #159b98;
        border-radius: 7px;
        background: #159b98;
        color: #fff;
        font-size: 0.78rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        box-shadow: none;
    }

    .import-btn-submit:hover {
        background: #128a87;
        border-color: #128a87;
        color: #fff;
    }

    /* =========================================================
       FLASH SUCCESS
       ========================================================= */

    .import-success {
        border: 1px solid #cdebd4 !important;
        background: #f3fbf5 !important;
        color: #39704a;
        padding: 13px 16px;
    }

    /* =========================================================
       FLASH ERROR
       ========================================================= */

    .import-danger {
        border: 1px solid #f0cccc !important;
        background: #fff6f6 !important;
        color: #8a4848;
        padding: 13px 16px;
    }

    /* =========================================================
       RESPONSIVE
       ========================================================= */

    @media (max-width: 992px) {

        .import-card {
            max-width: 100%;
        }

    }

    @media (max-width: 768px) {

        .import-card-body {
            padding: 20px 18px;
        }

        .import-form-footer {
            justify-content: stretch;
        }

        .import-btn-cancel,
        .import-btn-submit {
            flex: 1;
        }

    }

    @media (max-width: 576px) {

        .import-card-header {
            padding: 14px 16px;
        }

        .import-card-title {
            font-size: 0.9rem;
        }

        .import-card-body {
            padding: 17px 15px;
        }

        .import-info {
            gap: 10px;
            padding: 14px;
        }

        .import-info-icon {
            width: 32px;
            height: 32px;
        }

        .import-info-list {
            font-size: 0.76rem;
        }

        .import-form-footer {
            flex-direction: column-reverse;
            align-items: stretch;
        }

        .import-btn-cancel,
        .import-btn-submit {
            width: 100%;
        }

    }
</style>


<div class="import-page">

    <div class="import-card">

        <!-- =====================================================
             HEADER
             ===================================================== -->

        <div class="import-card-header">

            <h5 class="import-card-title">

                <i class="bi bi-file-earmark-spreadsheet"></i>

                Import Sampel via File Excel

            </h5>

        </div>


        <div class="import-card-body">

            <!-- =================================================
                 FLASH SUCCESS
                 ================================================= -->

            <?php if ($this->session->flashdata('pesan_sukses')): ?>

                <div
                    class="alert import-success alert-dismissible fade show"
                    role="alert"
                >

                    <i class="bi bi-check-circle-fill me-2"></i>

                    <?php echo html_escape($this->session->flashdata('pesan_sukses')); ?>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Close"
                    ></button>

                </div>

            <?php endif; ?>


            <!-- =================================================
                 FLASH ERROR
                 ================================================= -->

            <?php if ($this->session->flashdata('pesan_gagal')): ?>

                <div
                    class="alert import-danger alert-dismissible fade show"
                    role="alert"
                >

                    <strong>Gagal Import:</strong>

                    <?php echo html_escape($this->session->flashdata('pesan_gagal')); ?>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Close"
                    ></button>

                </div>

            <?php endif; ?>


            <!-- =================================================
                 DETAIL ERROR IMPORT
                 ================================================= -->

            <?php if ($this->session->flashdata('import_errors')): ?>

                <div class="import-error" role="alert">

                    <div class="import-error-icon">

                        <i class="bi bi-exclamation-triangle-fill"></i>

                    </div>


                    <div class="import-error-content">

                        <h6 class="import-error-title">

                            Detail Error Validasi Baris Data:

                        </h6>


                        <ul class="import-error-list">

                            <?php foreach ($this->session->flashdata('import_errors') as $err): ?>

                                <li>

                                    <?php echo html_escape($err); ?>

                                </li>

                            <?php endforeach; ?>

                        </ul>

                    </div>

                </div>

            <?php endif; ?>


            <!-- =================================================
                 KETENTUAN TEMPLATE EXCEL
                 ================================================= -->

            <div class="import-info" role="alert">

                <div class="import-info-icon">

                    <i class="bi bi-info-lg"></i>

                </div>


                <div class="import-info-content">

                    <h6 class="import-info-title">

                        Ketentuan Upload Template Excel:

                    </h6>


                    <ol class="import-info-list">

                        <li>
                            File harus berformat
                            <strong>.xlsx</strong>
                            atau
                            <strong>.csv</strong>.
                        </li>

                        <li>
                            Baris pertama (Row 1) wajib berisi
                            Header Kolom (31 kolom baku).
                        </li>

                        <li>
                            Status sampel otomatis di-set menjadi
                            <strong>Menunggu Pengujian</strong>.
                        </li>

                        <li>
                            Dua konteks <em>Penandaan</em> pada Excel
                            dipisahkan secara otomatis
                            (Penandaan jumlah vs Penandaan evaluasi).
                        </li>

                    </ol>

                </div>

            </div>


            <!-- =================================================
                 FORM IMPORT
                 ================================================= -->

            <form
                action="<?php echo site_url('sampel/import'); ?>"
                method="post"
                enctype="multipart/form-data"
            >

                <input
                    type="hidden"
                    name="<?php echo $this->security->get_csrf_token_name(); ?>"
                    value="<?php echo $this->security->get_csrf_hash(); ?>"
                >


                <!-- =================================================
                     FILE
                     ================================================= -->

                <div class="import-upload-section">

                    <label
                        for="file_excel"
                        class="import-file-label"
                    >

                        Pilih File Excel (.xlsx / .csv)

                        <span class="required">*</span>

                    </label>


                    <input
                        type="file"
                        class="import-file-input"
                        id="file_excel"
                        name="file_excel"
                        accept=".xlsx,.csv"
                        required
                    >

                </div>


                <!-- =================================================
                     BUTTON
                     ================================================= -->

                <div class="import-form-footer">

                    <a
                        href="<?php echo site_url('sampel'); ?>"
                        class="import-btn-cancel"
                    >

                        Batal

                    </a>


                    <button
                        type="submit"
                        class="import-btn-submit"
                    >

                        <i class="bi bi-upload"></i>

                        Unggah &amp; Proses Import

                    </button>

                </div>


            </form>

        </div>

    </div>

</div>