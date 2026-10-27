<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
    /* =========================================================
       REGISTRASI SAMPEL MANUAL
       Tampilan saja - fungsi tetap sama
       ========================================================= */

    .registrasi-page {
        width: 100%;
        color: #294257;
        font-size: 0.95rem;
    }

    /* =========================================================
       CARD UTAMA
       ========================================================= */

    .registrasi-card {
        width: 100%;
        background: #fff;
        border: 1px solid #e0e8ed;
        border-radius: 11px;
        box-shadow: 0 5px 18px rgba(35, 58, 76, 0.07);
        overflow: hidden;
    }

    .registrasi-card-header {
        min-height: 62px;
        padding: 16px 20px;
        border-bottom: 1px solid #e8eef2;
        background: #fff;
        display: flex;
        align-items: center;
    }

    .registrasi-card-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0;
        color: #294257;
        font-size: 1rem;
        font-weight: 700;
    }

    .registrasi-card-title i {
        color: #159b98;
        font-size: 1.15rem;
    }

    .registrasi-card-body {
        padding: 24px 22px;
    }

    /* =========================================================
       VALIDATION ERROR
       ========================================================= */

    .registrasi-error {
        display: flex;
        align-items: flex-start;
        gap: 11px;
        padding: 14px 16px;
        margin-bottom: 22px;
        border: 1px solid #f0cccc !important;
        border-radius: 8px;
        background: #fff6f6 !important;
        color: #8a4848;
        font-size: 0.82rem;
        line-height: 1.55;
    }

    .registrasi-error-icon {
        flex: 0 0 auto;
        color: #dc5a5a;
        font-size: 1rem;
        margin-top: 2px;
    }

    .registrasi-error-content {
        min-width: 0;
    }

    .registrasi-error-content div {
        margin-bottom: 2px;
    }

    .registrasi-error-content div:last-child {
        margin-bottom: 0;
    }

    .registrasi-error .btn-close {
        margin-left: auto;
        font-size: 0.7rem;
    }

    /* =========================================================
       FORM SECTION
       ========================================================= */

    .registrasi-section {
        margin-bottom: 26px;
        padding: 19px 19px 20px;
        border: 1px solid #e2eaee;
        border-radius: 9px;
        background: #fff;
    }

    .registrasi-section:last-of-type {
        margin-bottom: 24px;
    }

    .registrasi-section-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 19px;
        padding-bottom: 12px;
        border-bottom: 1px solid #e5ecef;
    }

    .registrasi-section-number {
        flex: 0 0 auto;
        width: 31px;
        height: 31px;
        border-radius: 8px;
        background: #e4f6f4;
        color: #159b98;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.76rem;
        font-weight: 800;
    }

    .registrasi-section-title {
        margin: 0;
        color: #294257;
        font-size: 0.91rem;
        font-weight: 700;
    }

    .registrasi-section-subtitle {
        margin: 2px 0 0;
        color: #8a9aa5;
        font-size: 0.68rem;
    }

    /* =========================================================
       FORM GRID
       ========================================================= */

    .registrasi-section .row {
        --bs-gutter-x: 18px;
        --bs-gutter-y: 17px;
    }

    /* =========================================================
       LABEL
       ========================================================= */

    .registrasi-section .form-label {
        display: block;
        margin-bottom: 7px;
        color: #526779;
        font-size: 0.78rem;
        font-weight: 700;
    }

    .registrasi-section .form-label .text-danger {
        font-size: 0.82rem;
    }

    /* =========================================================
       INPUT
       ========================================================= */

    .registrasi-section .form-control {
        min-height: 43px;
        padding: 9px 12px;
        border: 1px solid #dfe7eb;
        border-radius: 7px;
        background: #f9fbfc;
        color: #526779;
        font-size: 0.79rem;
        box-shadow: none;
        transition: all 0.15s ease;
    }

    .registrasi-section .form-control:hover {
        border-color: #cbd9de;
        background: #fff;
    }

    .registrasi-section .form-control:focus {
        border-color: #a9cfcd;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(21, 155, 152, 0.08);
        outline: none;
    }

    .registrasi-section .form-control::placeholder {
        color: #a4b0b8;
        opacity: 1;
    }

    /* =========================================================
       TEXTAREA
       ========================================================= */

    .registrasi-section textarea.form-control {
        min-height: 82px;
        resize: vertical;
        line-height: 1.55;
    }

    /* =========================================================
       FOOTER BUTTON
       ========================================================= */

    .registrasi-form-footer {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 3px;
        padding-top: 18px;
        border-top: 1px solid #e7edef;
    }

    .registrasi-btn-cancel {
        min-height: 40px;
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
        transition: all 0.15s ease;
    }

    .registrasi-btn-cancel:hover {
        background: #f7f9fa;
        border-color: #cbd6dc;
        color: #526a79;
    }

    .registrasi-btn-save {
        min-height: 40px;
        padding: 0 19px;
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
        transition: all 0.15s ease;
    }

    .registrasi-btn-save:hover {
        background: #128a87;
        border-color: #128a87;
        color: #fff;
    }

    /* =========================================================
       RESPONSIVE
       ========================================================= */

    @media (max-width: 768px) {

        .registrasi-card-body {
            padding: 20px 17px;
        }

        .registrasi-section {
            padding: 16px;
        }

        .registrasi-section .row {
            --bs-gutter-x: 14px;
            --bs-gutter-y: 15px;
        }

        .registrasi-form-footer {
            justify-content: stretch;
        }

        .registrasi-btn-cancel,
        .registrasi-btn-save {
            flex: 1;
        }

    }

    @media (max-width: 576px) {

        .registrasi-card-header {
            padding: 14px 16px;
        }

        .registrasi-card-title {
            font-size: 0.91rem;
        }

        .registrasi-card-body {
            padding: 16px 13px;
        }

        .registrasi-section {
            padding: 14px;
            margin-bottom: 18px;
        }

        .registrasi-section-header {
            margin-bottom: 16px;
        }

        .registrasi-section-title {
            font-size: 0.84rem;
        }

        .registrasi-section .form-label {
            font-size: 0.75rem;
        }

        .registrasi-section .form-control {
            font-size: 0.76rem;
        }

        .registrasi-form-footer {
            flex-direction: column-reverse;
            align-items: stretch;
        }

        .registrasi-btn-cancel,
        .registrasi-btn-save {
            width: 100%;
        }

    }
</style>


<div class="registrasi-page">

    <div class="registrasi-card">

        <!-- =====================================================
             HEADER
             ===================================================== -->

        <div class="registrasi-card-header">

            <h5 class="registrasi-card-title">

                <i class="bi bi-clipboard-plus"></i>

                Registrasi Sampel Manual

            </h5>

        </div>


        <div class="registrasi-card-body">

            <!-- =================================================
                 VALIDATION ERROR
                 ================================================= -->

            <?php if (validation_errors()): ?>

                <div
                    class="registrasi-error"
                    role="alert"
                >

                    <div class="registrasi-error-icon">

                        <i class="bi bi-exclamation-triangle-fill"></i>

                    </div>


                    <div class="registrasi-error-content">

                        <?php echo validation_errors('<div>', '</div>'); ?>

                    </div>


                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Close"
                    ></button>

                </div>

            <?php endif; ?>


            <!-- =================================================
                 FORM
                 ================================================= -->

            <form
                action="<?php echo site_url('sampel/tambah'); ?>"
                method="post"
            >

                <input
                    type="hidden"
                    name="<?php echo $this->security->get_csrf_token_name(); ?>"
                    value="<?php echo $this->security->get_csrf_hash(); ?>"
                >


                <!-- =================================================
                     SECTION 1
                     IDENTITAS UTAMA SAMPEL
                     ================================================= -->

                <div class="registrasi-section">

                    <div class="registrasi-section-header">

                        <div class="registrasi-section-number">
                            01
                        </div>

                        <div>

                            <h6 class="registrasi-section-title">
                                Identitas Utama Sampel
                            </h6>

                            <p class="registrasi-section-subtitle">
                                Informasi dasar dan identitas sampel
                            </p>

                        </div>

                    </div>


                    <div class="row g-3">

                        <!-- PILIHAN KOMODITI -->
                        <div class="col-12">
                            <label class="form-label d-flex align-items-center gap-2 fw-bold text-dark">
                                <i class="bi bi-tag-fill text-primary"></i>
                                Komoditi Sampel
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle" style="font-size: 0.72rem;">Wajib</span>
                            </label>
                            <?php $val_komoditi = set_value('komoditi', isset($komoditi_pilihan) ? $komoditi_pilihan : 'obat'); ?>
                            <div class="row g-2">
                                <div class="col-md-3 col-6">
                                    <label class="d-flex align-items-center p-2 rounded-3 border <?php echo ($val_komoditi === 'obat') ? 'border-primary bg-primary-subtle bg-opacity-25' : 'border-light-subtle bg-light'; ?> cursor-pointer w-100" style="cursor: pointer;">
                                        <input type="radio" name="komoditi" value="obat" class="form-check-input me-2 mt-0" <?php echo ($val_komoditi === 'obat') ? 'checked' : ''; ?>>
                                        <div>
                                            <div class="fw-bold text-dark small"><i class="bi bi-capsule me-1 text-primary"></i> Obat</div>
                                            <div class="text-muted" style="font-size: 0.7rem;">Lab Kimia / Mikro Obat</div>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-md-3 col-6">
                                    <label class="d-flex align-items-center p-2 rounded-3 border <?php echo ($val_komoditi === 'pangan') ? 'border-success bg-success-subtle bg-opacity-25' : 'border-light-subtle bg-light'; ?> cursor-pointer w-100" style="cursor: pointer;">
                                        <input type="radio" name="komoditi" value="pangan" class="form-check-input me-2 mt-0" <?php echo ($val_komoditi === 'pangan') ? 'checked' : ''; ?>>
                                        <div>
                                            <div class="fw-bold text-dark small"><i class="bi bi-cup-hot me-1 text-success"></i> Pangan &amp; Air</div>
                                            <div class="text-muted" style="font-size: 0.7rem;">Lab Pangan &amp; Minuman</div>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-md-3 col-6">
                                    <label class="d-flex align-items-center p-2 rounded-3 border <?php echo ($val_komoditi === 'herbal') ? 'border-warning bg-warning-subtle bg-opacity-25' : 'border-light-subtle bg-light'; ?> cursor-pointer w-100" style="cursor: pointer;">
                                        <input type="radio" name="komoditi" value="herbal" class="form-check-input me-2 mt-0" <?php echo ($val_komoditi === 'herbal') ? 'checked' : ''; ?>>
                                        <div>
                                            <div class="fw-bold text-dark small"><i class="bi bi-flower1 me-1 text-warning"></i> Herbal / Jamu</div>
                                            <div class="text-muted" style="font-size: 0.7rem;">Obat Tradisional</div>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-md-3 col-6">
                                    <label class="d-flex align-items-center p-2 rounded-3 border <?php echo ($val_komoditi === 'kosmetik') ? 'border-danger bg-danger-subtle bg-opacity-25' : 'border-light-subtle bg-light'; ?> cursor-pointer w-100" style="cursor: pointer;">
                                        <input type="radio" name="komoditi" value="kosmetik" class="form-check-input me-2 mt-0" <?php echo ($val_komoditi === 'kosmetik') ? 'checked' : ''; ?>>
                                        <div>
                                            <div class="fw-bold text-dark small"><i class="bi bi-brush me-1 text-danger"></i> Kosmetik</div>
                                            <div class="text-muted" style="font-size: 0.7rem;">Lab Kosmetika</div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            <div class="form-text text-muted small mt-1">
                                <i class="bi bi-info-circle me-1"></i> Data sampel akan langsung diteruskan ke penguji &amp; penyelia pada komoditi yang dipilih.
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label
                                for="no"
                                class="form-label"
                            >
                                No.
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="no"
                                name="no"
                                value="<?php echo set_value('no'); ?>"
                                placeholder="Nomor urut/registrasi"
                            >

                        </div>


                        <div class="col-md-4">

                            <label
                                for="kode_sampel_manual"
                                class="form-label"
                            >
                                Kode Sampel Manual
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="kode_sampel_manual"
                                name="kode_sampel_manual"
                                value="<?php echo set_value('kode_sampel_manual'); ?>"
                                placeholder="Contoh: K-001/BPOM/2026"
                            >

                        </div>


                        <div class="col-md-5">

                            <label
                                for="nama_sampel"
                                class="form-label"
                            >
                                Nama Sampel
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="nama_sampel"
                                name="nama_sampel"
                                value="<?php echo set_value('nama_sampel'); ?>"
                                placeholder="Masukkan nama obat/produk sampel"
                                required
                            >

                        </div>


                        <div class="col-md-4">

                            <label
                                for="kategori_sampel"
                                class="form-label"
                            >
                                Kategori Sampel
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="kategori_sampel"
                                name="kategori_sampel"
                                value="<?php echo set_value('kategori_sampel'); ?>"
                                placeholder="Obat / Makanan / Kosmetik"
                            >

                        </div>


                        <div class="col-md-4">

                            <label
                                for="sub_kategori"
                                class="form-label"
                            >
                                Sub Kategori
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="sub_kategori"
                                name="sub_kategori"
                                value="<?php echo set_value('sub_kategori'); ?>"
                            >

                        </div>


                        <div class="col-md-4">

                            <label
                                for="jenis_kelas_terapi"
                                class="form-label"
                            >
                                Jenis / Kelas Terapi
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="jenis_kelas_terapi"
                                name="jenis_kelas_terapi"
                                value="<?php echo set_value('jenis_kelas_terapi'); ?>"
                            >

                        </div>

                    </div>

                </div>


                <!-- =================================================
                     SECTION 2
                     ASAL & SARANA SAMPLING
                     ================================================= -->

                <div class="registrasi-section">

                    <div class="registrasi-section-header">

                        <div class="registrasi-section-number">
                            02
                        </div>

                        <div>

                            <h6 class="registrasi-section-title">
                                Asal &amp; Sarana Sampling
                            </h6>

                            <p class="registrasi-section-subtitle">
                                Informasi lokasi, sarana, dan kegiatan sampling
                            </p>

                        </div>

                    </div>


                    <div class="row g-3">

                        <div class="col-md-4">

                            <label
                                for="kategori_sarana"
                                class="form-label"
                            >
                                Kategori Sarana
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="kategori_sarana"
                                name="kategori_sarana"
                                value="<?php echo set_value('kategori_sarana'); ?>"
                            >

                        </div>


                        <div class="col-md-4">

                            <label
                                for="nama_sarana"
                                class="form-label"
                            >
                                Nama Sarana
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="nama_sarana"
                                name="nama_sarana"
                                value="<?php echo set_value('nama_sarana'); ?>"
                            >

                        </div>


                        <div class="col-md-4">

                            <label
                                for="kabupaten_kota"
                                class="form-label"
                            >
                                Kabupaten / Kota
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="kabupaten_kota"
                                name="kabupaten_kota"
                                value="<?php echo set_value('kabupaten_kota'); ?>"
                            >

                        </div>


                        <div class="col-md-4">

                            <label
                                for="tanggal_sampling"
                                class="form-label"
                            >
                                Tanggal Sampling
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="tanggal_sampling"
                                name="tanggal_sampling"
                                value="<?php echo set_value('tanggal_sampling'); ?>"
                                placeholder="YYYY-MM-DD"
                            >

                        </div>


                        <div class="col-md-4">

                            <label
                                for="no_sipt"
                                class="form-label"
                            >
                                NO SIPT
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="no_sipt"
                                name="no_sipt"
                                value="<?php echo set_value('no_sipt'); ?>"
                            >

                        </div>


                        <div class="col-md-4">

                            <label
                                for="surtug"
                                class="form-label"
                            >
                                Surat Tugas (Surtug)
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="surtug"
                                name="surtug"
                                value="<?php echo set_value('surtug'); ?>"
                            >

                        </div>

                    </div>

                </div>


                <!-- =================================================
                     BAGIAN KHUSUS KOMODITI: OBAT
                     ================================================= -->
                <div id="section-wrapper-obat" class="komoditi-section-wrapper" <?php echo ($val_komoditi !== 'obat') ? 'style="display:none;"' : ''; ?>>

                    <!-- =================================================
                         SECTION 3
                         DETAIL PRODUK & KEMASAN
                         ================================================= -->

                    <div class="registrasi-section">

                        <div class="registrasi-section-header">

                            <div class="registrasi-section-number">
                                03
                            </div>

                        <div>

                            <h6 class="registrasi-section-title">
                                Detail Produk &amp; Kemasan
                            </h6>

                            <p class="registrasi-section-subtitle">
                                Informasi produk, kemasan, penyimpanan, dan komposisi
                            </p>

                        </div>

                    </div>


                    <div class="row g-3">

                        <div class="col-md-4">

                            <label
                                for="nomor_izin_edar"
                                class="form-label"
                            >
                                Nomor Izin Edar (NIE)
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="nomor_izin_edar"
                                name="nomor_izin_edar"
                                value="<?php echo set_value('nomor_izin_edar'); ?>"
                            >

                        </div>


                        <div class="col-md-4">

                            <label
                                for="no_bets"
                                class="form-label"
                            >
                                No. Bets
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="no_bets"
                                name="no_bets"
                                value="<?php echo set_value('no_bets'); ?>"
                            >

                        </div>


                        <div class="col-md-4">

                            <label
                                for="kedaluwarsa"
                                class="form-label"
                            >
                                Kedaluwarsa (Exp Date)
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="kedaluwarsa"
                                name="kedaluwarsa"
                                value="<?php echo set_value('kedaluwarsa'); ?>"
                            >

                        </div>


                        <div class="col-md-4">

                            <label
                                for="kondisi_produk"
                                class="form-label"
                            >
                                Kondisi Produk
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="kondisi_produk"
                                name="kondisi_produk"
                                value="<?php echo set_value('kondisi_produk'); ?>"
                                placeholder="Baik / Rusak / Segel Utuh"
                            >

                        </div>


                        <div class="col-md-4">

                            <label
                                for="kemasan"
                                class="form-label"
                            >
                                Kemasan
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="kemasan"
                                name="kemasan"
                                value="<?php echo set_value('kemasan'); ?>"
                            >

                        </div>


                        <div class="col-md-4">

                            <label
                                for="penyimpanan"
                                class="form-label"
                            >
                                Penyimpanan
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="penyimpanan"
                                name="penyimpanan"
                                value="<?php echo set_value('penyimpanan'); ?>"
                                placeholder="Suhu Kamar / Dingin (2-8 C)"
                            >

                        </div>


                        <div class="col-md-6">

                            <label
                                for="nama_alamat_perusahaan"
                                class="form-label"
                            >
                                Nama dan Alamat Perusahaan
                            </label>

                            <textarea
                                class="form-control"
                                id="nama_alamat_perusahaan"
                                name="nama_alamat_perusahaan"
                                rows="2"
                            ><?php echo set_value('nama_alamat_perusahaan'); ?></textarea>

                        </div>


                        <div class="col-md-6">

                            <label
                                for="komposisi"
                                class="form-label"
                            >
                                Komposisi
                            </label>

                            <textarea
                                class="form-control"
                                id="komposisi"
                                name="komposisi"
                                rows="2"
                            ><?php echo set_value('komposisi'); ?></textarea>

                        </div>

                    </div>

                </div>


                <!-- =================================================
                     SECTION 4
                     JUMLAH SAMPEL & ALOKASI
                     ================================================= -->

                <div class="registrasi-section">

                    <div class="registrasi-section-header">

                        <div class="registrasi-section-number">
                            04
                        </div>

                        <div>

                            <h6 class="registrasi-section-title">
                                Jumlah Sampel &amp; Alokasi
                            </h6>

                            <p class="registrasi-section-subtitle">
                                Pembagian jumlah sampel berdasarkan kebutuhan pengujian
                            </p>

                        </div>

                    </div>


                    <div class="row g-3">

                        <div class="col-md-2">

                            <label
                                for="jumlah_kimia"
                                class="form-label"
                            >
                                Jumlah Kimia
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="jumlah_kimia"
                                name="jumlah_kimia"
                                value="<?php echo set_value('jumlah_kimia'); ?>"
                            >

                        </div>


                        <div class="col-md-2">

                            <label
                                for="jumlah_mikro"
                                class="form-label"
                            >
                                Jumlah Mikro
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="jumlah_mikro"
                                name="jumlah_mikro"
                                value="<?php echo set_value('jumlah_mikro'); ?>"
                            >

                        </div>


                        <div class="col-md-2">

                            <label
                                for="jumlah_arsip"
                                class="form-label"
                            >
                                Jumlah Arsip
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="jumlah_arsip"
                                name="jumlah_arsip"
                                value="<?php echo set_value('jumlah_arsip'); ?>"
                            >

                        </div>


                        <div class="col-md-3">

                            <label
                                for="jumlah_penandaan"
                                class="form-label"
                            >
                                Jumlah Penandaan
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="jumlah_penandaan"
                                name="jumlah_penandaan"
                                value="<?php echo set_value('jumlah_penandaan'); ?>"
                            >

                        </div>


                        <div class="col-md-3">

                            <label
                                for="jumlah_total"
                                class="form-label"
                            >
                                Jumlah Total
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="jumlah_total"
                                name="jumlah_total"
                                value="<?php echo set_value('jumlah_total'); ?>"
                            >

                        </div>

                    </div>

                </div>


                <!-- =================================================
                     SECTION 5
                     EVALUASI & ADMINISTRASI
                     ================================================= -->

                <div class="registrasi-section">

                    <div class="registrasi-section-header">

                        <div class="registrasi-section-number">
                            05
                        </div>

                        <div>

                            <h6 class="registrasi-section-title">
                                Evaluasi &amp; Administrasi
                            </h6>

                            <p class="registrasi-section-subtitle">
                                Informasi evaluasi dan administrasi pengujian
                            </p>

                        </div>

                    </div>


                    <div class="row g-3">

                        <div class="col-md-6">

                            <label
                                for="penandaan"
                                class="form-label"
                            >
                                Penandaan (Field Utama)
                            </label>

                            <textarea
                                class="form-control"
                                id="penandaan"
                                name="penandaan"
                                rows="2"
                                placeholder="Catatan evaluasi penandaan produk"
                            ><?php echo set_value('penandaan'); ?></textarea>

                        </div>


                        <div class="col-md-3">

                            <label
                                for="harga"
                                class="form-label"
                            >
                                Harga
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="harga"
                                name="harga"
                                value="<?php echo set_value('harga'); ?>"
                            >

                        </div>


                        <div class="col-md-3">

                            <label
                                for="tie"
                                class="form-label"
                            >
                                TIE
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="tie"
                                name="tie"
                                value="<?php echo set_value('tie'); ?>"
                            >

                        </div>


                        <div class="col-md-3">

                            <label
                                for="mk"
                                class="form-label"
                            >
                                MK
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="mk"
                                name="mk"
                                value="<?php echo set_value('mk'); ?>"
                            >

                        </div>


                        <div class="col-md-3">

                            <label
                                for="tmk"
                                class="form-label"
                            >
                                TMK
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="tmk"
                                name="tmk"
                                value="<?php echo set_value('tmk'); ?>"
                            >

                        </div>


                        <div class="col-md-6">

                            <label
                                for="balai_penguji"
                                class="form-label"
                            >
                                Balai Penguji
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="balai_penguji"
                                name="balai_penguji"
                                value="<?php echo set_value('balai_penguji', 'Balai Besar POM di Bandar Lampung'); ?>"
                            >

                        </div>

                    </div>

                </div>
                <!-- AKHIR SECTION WRAPPER OBAT -->

                <!-- =================================================
                     BAGIAN KHUSUS KOMODITI: PANGAN & AIR (DUMMY FORM)
                     ================================================= -->
                <div id="section-wrapper-pangan" class="komoditi-section-wrapper" <?php echo ($val_komoditi !== 'pangan') ? 'style="display:none;"' : ''; ?>>
                    <div class="registrasi-section" style="border-left: 4px solid #198754;">
                        <div class="registrasi-section-header">
                            <div class="registrasi-section-number bg-success text-white">03</div>
                            <div>
                                <h6 class="registrasi-section-title text-success">
                                    <i class="bi bi-cup-hot text-success me-1"></i> Parameter Produk &amp; Sampling (Pangan &amp; Air)
                                </h6>
                                <p class="registrasi-section-subtitle">
                                    Karakteristik produk pangan olahan, air minum kemasan, dan target pengujian
                                </p>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="pangan_kategori" class="form-label">Kategori Pangan BPOM</label>
                                <select class="form-select" id="pangan_kategori" name="pangan_kategori">
                                    <option value="">-- Pilih Kategori Pangan --</option>
                                    <option value="01.0 Produk Susu dan Analognya">01.0 Produk Susu dan Analognya</option>
                                    <option value="02.0 Lemak, Minyak, dan Emulsi Minyak">02.0 Lemak, Minyak, dan Emulsi Minyak</option>
                                    <option value="04.0 Buah dan Sayur Olahan">04.0 Buah dan Sayur Olahan</option>
                                    <option value="05.0 Kembang Gula / Permen dan Cokelat">05.0 Kembang Gula / Permen dan Cokelat</option>
                                    <option value="06.0 Serealia dan Produk Biji-bijian">06.0 Serealia dan Produk Biji-bijian</option>
                                    <option value="08.0 Daging dan Produk Daging Olahan">08.0 Daging dan Produk Daging Olahan</option>
                                    <option value="14.0 Minuman Siap Konsumsi / AMDK">14.0 Minuman Siap Konsumsi / AMDK</option>
                                    <option value="15.0 Makanan Ringan Siap Santap">15.0 Makanan Ringan Siap Santap</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label for="pangan_nie" class="form-label">Izin Edar (BPOM MD/ML/P-IRT)</label>
                                <input type="text" class="form-control" id="pangan_nie" name="pangan_nie" placeholder="BPOM RI MD 123456789">
                            </div>

                            <div class="col-md-3">
                                <label for="pangan_kode_produksi" class="form-label">Kode Produksi / Exp Date</label>
                                <input type="text" class="form-control" id="pangan_kode_produksi" name="pangan_kode_produksi" placeholder="EXP: 2026-12-31">
                            </div>

                            <div class="col-md-4">
                                <label for="pangan_kemasan" class="form-label">Jenis Kemasan Pangan</label>
                                <input type="text" class="form-control" id="pangan_kemasan" name="pangan_kemasan" placeholder="Botol PET / Kaleng / Plastik Vakum / Tetrapak">
                            </div>

                            <div class="col-md-4">
                                <label for="pangan_suhu" class="form-label">Suhu Pengambilan Sampel</label>
                                <select class="form-select" id="pangan_suhu" name="pangan_suhu">
                                    <option value="Suhu Ruang (25-30 °C)">Suhu Ruang (25-30 °C)</option>
                                    <option value="Chilled (0-4 °C)">Chilled / Dingin (0-4 °C)</option>
                                    <option value="Frozen (Minus 18 °C)">Frozen / Beku (-18 °C)</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="pangan_volume" class="form-label">Volume / Berat Bersih (Netto)</label>
                                <input type="text" class="form-control" id="pangan_volume" name="pangan_volume" placeholder="Contoh: 600 ml / 250 gram">
                            </div>

                            <div class="col-md-6">
                                <label for="pangan_parameter" class="form-label">Target Pengujian Utama</label>
                                <textarea class="form-control" id="pangan_parameter" name="pangan_parameter" rows="2" placeholder="Uji Mikroba (ALT, E. Coli, Salmonella) / Uji Kimia (Bahan Pengawet, Pemanis Buatan, Logam Berat)"></textarea>
                            </div>

                            <div class="col-md-6">
                                <label for="pangan_produsen" class="form-label">Nama &amp; Alamat Pabrik / Produsen Pangan</label>
                                <textarea class="form-control" id="pangan_produsen" name="pangan_produsen" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- =================================================
                     BAGIAN KHUSUS KOMODITI: HERBAL / JAMU (DUMMY FORM)
                     ================================================= -->
                <div id="section-wrapper-herbal" class="komoditi-section-wrapper" <?php echo ($val_komoditi !== 'herbal') ? 'style="display:none;"' : ''; ?>>
                    <div class="registrasi-section" style="border-left: 4px solid #ffc107;">
                        <div class="registrasi-section-header">
                            <div class="registrasi-section-number bg-warning text-dark">03</div>
                            <div>
                                <h6 class="registrasi-section-title text-warning-emphasis">
                                    <i class="bi bi-flower1 text-warning me-1"></i> Parameter Produk &amp; Sampling (Herbal / Jamu / Obat Tradisional)
                                </h6>
                                <p class="registrasi-section-subtitle">
                                    Identitas bentuk sediaan, izin edar (TR/TI/SD), klaim khasiat, dan skrining BKO
                                </p>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="herbal_bentuk_sediaan" class="form-label">Bentuk Sediaan Herbal</label>
                                <select class="form-select" id="herbal_bentuk_sediaan" name="herbal_bentuk_sediaan">
                                    <option value="">-- Pilih Bentuk Sediaan --</option>
                                    <option value="Cairan Obat Dalam / Jamu Cair">Cairan Obat Dalam / Jamu Cair</option>
                                    <option value="Kapsul Herbal">Kapsul Herbal</option>
                                    <option value="Serbuk Seduh / Rajangan">Serbuk Seduh / Rajangan</option>
                                    <option value="Pil / Tablet Herbal">Pil / Tablet Herbal</option>
                                    <option value="Minyak Gosok / Balsem / Cairan Obat Luar">Minyak Gosok / Cairan Obat Luar</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="herbal_nie" class="form-label">Nomor Izin Edar (TR / TI / QD / SD)</label>
                                <input type="text" class="form-control" id="herbal_nie" name="herbal_nie" placeholder="POM TR 123456789">
                            </div>

                            <div class="col-md-4">
                                <label for="herbal_bets" class="form-label">No. Bets &amp; Exp Date</label>
                                <input type="text" class="form-control" id="herbal_bets" name="herbal_bets" placeholder="BETS-01 / EXP: 2027-05">
                            </div>

                            <div class="col-md-6">
                                <label for="herbal_klaim" class="form-label">Klaim Khasiat / Kegunaan Tradisional</label>
                                <textarea class="form-control" id="herbal_klaim" name="herbal_klaim" rows="2" placeholder="Membantu meredakan pegal linu, memelihara daya tahan tubuh, dll."></textarea>
                            </div>

                            <div class="col-md-6">
                                <label for="herbal_bko" class="form-label">Skrining BKO (Bahan Kimia Obat) yang Diwaspadai</label>
                                <textarea class="form-control" id="herbal_bko" name="herbal_bko" rows="2" placeholder="Skrining Parasetamol, Deksametason, Sildenafil Sitrat, Fenilbutazon, dll."></textarea>
                            </div>

                            <div class="col-md-12">
                                <label for="herbal_simplisia" class="form-label">Kandungan Simplisia / Bahan Herbal Utama</label>
                                <input type="text" class="form-control" id="herbal_simplisia" name="herbal_simplisia" placeholder="Curcuma xanthorrhiza rhizoma, Zingiberis officinale, Phyllanthus niruri">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- =================================================
                     BAGIAN KHUSUS KOMODITI: KOSMETIK (DUMMY FORM)
                     ================================================= -->
                <div id="section-wrapper-kosmetik" class="komoditi-section-wrapper" <?php echo ($val_komoditi !== 'kosmetik') ? 'style="display:none;"' : ''; ?>>
                    <div class="registrasi-section" style="border-left: 4px solid #dc3545;">
                        <div class="registrasi-section-header">
                            <div class="registrasi-section-number bg-danger text-white">03</div>
                            <div>
                                <h6 class="registrasi-section-title text-danger">
                                    <i class="bi bi-brush text-danger me-1"></i> Parameter Produk &amp; Sampling (Kosmetik)
                                </h6>
                                <p class="registrasi-section-subtitle">
                                    Nomor notifikasi, kategori kosmetik, asal sediaan, dan target uji bahan berbahaya
                                </p>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="kosmetik_notifikasi" class="form-label">Nomor Notifikasi BPOM (NA / NK)</label>
                                <input type="text" class="form-control" id="kosmetik_notifikasi" name="kosmetik_notifikasi" placeholder="NA18240101234">
                            </div>

                            <div class="col-md-4">
                                <label for="kosmetik_kategori" class="form-label">Kategori Kosmetik</label>
                                <select class="form-select" id="kosmetik_kategori" name="kosmetik_kategori">
                                    <option value="">-- Pilih Kategori --</option>
                                    <option value="Perawatan Kulit (Skincare: Serum, Krim, Toner)">Perawatan Kulit (Skincare: Serum, Krim, Toner)</option>
                                    <option value="Riasan Wajah / Makeup (Lipstik, Bedak, Foundation)">Riasan Wajah / Makeup (Lipstik, Bedak, Foundation)</option>
                                    <option value="Perawatan Rambut (Sampo, Minyak Rambut)">Perawatan Rambut (Sampo, Minyak Rambut)</option>
                                    <option value="Wewangian / Parfum / Cologne">Wewangian / Parfum / Cologne</option>
                                    <option value="Pembersih Badan (Sabun Mandi)">Pembersih Badan (Sabun Mandi)</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label for="kosmetik_asal" class="form-label">Asal Produk</label>
                                <select class="form-select" id="kosmetik_asal" name="kosmetik_asal">
                                    <option value="Produksi Dalam Negeri (Lokal)">Produksi Dalam Negeri (Lokal)</option>
                                    <option value="Impor">Impor</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="kosmetik_target_uji" class="form-label">Target Uji Bahan Dilarang / Berbahaya</label>
                                <textarea class="form-control" id="kosmetik_target_uji" name="kosmetik_target_uji" rows="2" placeholder="Uji Merkuri (Hg), Hidrokuinon, Asam Retinoat, Timbal (Pb), Pewarna Merah K3 / K10"></textarea>
                            </div>

                            <div class="col-md-6">
                                <label for="kosmetik_produsen" class="form-label">Nama Pemilik Izin Notifikasi / Pabrik</label>
                                <textarea class="form-control" id="kosmetik_produsen" name="kosmetik_produsen" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- =================================================
                     FORM FOOTER
                     ================================================= -->

                <div class="registrasi-form-footer">

                    <a
                        href="<?php echo site_url('sampel'); ?>"
                        class="registrasi-btn-cancel"
                    >
                        Batal
                    </a>


                    <button
                        type="submit"
                        class="registrasi-btn-save"
                    >

                        <i class="bi bi-check-lg"></i>

                        Simpan Registrasi Sampel

                    </button>

                </div>


            </form>

        </div>

    </div>

</div>

<!-- SCRIPT SWITCHER DINAMIS KOMODITI -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const radios = document.querySelectorAll('input[name="komoditi"]');
    const wrappers = {
        'obat': document.getElementById('section-wrapper-obat'),
        'pangan': document.getElementById('section-wrapper-pangan'),
        'herbal': document.getElementById('section-wrapper-herbal'),
        'kosmetik': document.getElementById('section-wrapper-kosmetik')
    };

    function updateKomoditiForm(selectedVal) {
        // Update styling radio cards
        radios.forEach(function(radio) {
            const card = radio.closest('label');
            if (card) {
                if (radio.value === selectedVal) {
                    card.classList.add('border-primary', 'bg-primary-subtle', 'bg-opacity-25');
                    card.classList.remove('border-light-subtle', 'bg-light');
                } else {
                    card.classList.remove('border-primary', 'bg-primary-subtle', 'bg-opacity-25');
                    card.classList.add('border-light-subtle', 'bg-light');
                }
            }
        });

        // Switch container section
        Object.keys(wrappers).forEach(function(k) {
            if (wrappers[k]) {
                if (k === selectedVal) {
                    wrappers[k].style.display = 'block';
                } else {
                    wrappers[k].style.display = 'none';
                }
            }
        });
    }

    radios.forEach(function(radio) {
        radio.addEventListener('change', function() {
            updateKomoditiForm(this.value);
        });
    });
});
</script>