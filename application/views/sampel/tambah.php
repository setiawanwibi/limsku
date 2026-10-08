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