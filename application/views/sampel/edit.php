<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
    /* =========================================================
       EDIT DATA SAMPEL
       Tampilan saja - fungsi PHP tetap dipertahankan
       ========================================================= */

    .edit-sampel-page {
        width: 100%;
        color: #294257;
        font-size: 0.95rem;
    }

    .edit-sampel-card {
        width: 100%;
        background: #fff;
        border: 1px solid #e0e8ed;
        border-radius: 11px;
        box-shadow: 0 5px 18px rgba(35, 58, 76, 0.07);
        overflow: hidden;
    }

    /* =========================
       HEADER
       ========================= */

    .edit-sampel-card-header {
        min-height: 62px;
        padding: 16px 20px;
        border-bottom: 1px solid #e8eef2;
        background: #fff;
        display: flex;
        align-items: center;
    }

    .edit-sampel-card-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0;
        color: #294257;
        font-size: 1rem;
        font-weight: 700;
    }

    .edit-sampel-card-title i {
        color: #159b98;
        font-size: 1.15rem;
    }

    /* =========================
       BODY
       ========================= */

    .edit-sampel-card-body {
        padding: 24px 22px;
    }

    /* =========================
       VALIDATION ERROR
       ========================= */

    .edit-sampel-error {
        margin-bottom: 20px;
        padding: 13px 15px;
        border: 1px solid #f0c8c8;
        border-radius: 8px;
        background: #fff7f7;
        color: #9b4141;
        font-size: 0.82rem;
        line-height: 1.6;
    }

    .edit-sampel-error .btn-close {
        font-size: 0.65rem;
    }

    /* =========================
       SECTION
       ========================= */

    .edit-sampel-section {
        margin-bottom: 18px;
        border: 1px solid #e0e8ed;
        border-radius: 9px;
        background: #fff;
        overflow: hidden;
    }

    .edit-sampel-section:last-of-type {
        margin-bottom: 0;
    }

    .edit-sampel-section-header {
        min-height: 52px;
        padding: 11px 16px;
        border-bottom: 1px solid #e8eef2;
        background: #f9fbfc;
        display: flex;
        align-items: center;
        gap: 11px;
    }

    .edit-sampel-section-number {
        width: 30px;
        height: 30px;
        flex: 0 0 30px;
        border-radius: 7px;
        background: #e4f6f4;
        color: #159b98;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.70rem;
        font-weight: 800;
    }

    .edit-sampel-section-heading {
        margin: 0;
        color: #294257;
        font-size: 0.86rem;
        font-weight: 700;
    }

    .edit-sampel-section-body {
        padding: 18px 17px;
    }

    /* =========================
       FORM
       ========================= */

    .edit-sampel-page .form-label {
        display: block;
        margin-bottom: 7px;
        color: #526779;
        font-size: 0.79rem;
        font-weight: 700;
    }

    .edit-sampel-page .form-label .text-danger {
        color: #d9534f !important;
    }

    .edit-sampel-page .form-control {
        width: 100%;
        min-height: 43px;
        padding: 9px 12px;
        border: 1px solid #dfe7eb;
        border-radius: 7px;
        background: #f9fbfc;
        color: #40596a;
        font-size: 0.80rem;
        box-shadow: none;
        transition: all 0.15s ease;
    }

    .edit-sampel-page .form-control:focus {
        border-color: #9fcfcd;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(21, 155, 152, 0.08);
        color: #294257;
    }

    .edit-sampel-page .form-control::placeholder {
        color: #a2afb7;
        font-size: 0.77rem;
    }

    .edit-sampel-page textarea.form-control {
        min-height: 84px;
        resize: vertical;
        line-height: 1.55;
    }

    /* =========================
       FOOTER ACTION
       ========================= */

    .edit-sampel-footer {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 22px;
        padding-top: 17px;
        border-top: 1px solid #edf1f3;
    }

    .edit-sampel-btn-cancel,
    .edit-sampel-btn-save {
        min-height: 39px;
        padding: 0 18px;
        border-radius: 7px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        font-size: 0.78rem;
        font-weight: 600;
    }

    .edit-sampel-btn-cancel {
        border: 1px solid #d7e0e5;
        background: #fff;
        color: #657887;
    }

    .edit-sampel-btn-cancel:hover {
        background: #f7f9fa;
        border-color: #cbd6dc;
        color: #526a79;
    }

    .edit-sampel-btn-save {
        border: 1px solid #159b98;
        background: #159b98;
        color: #fff;
        font-weight: 700;
    }

    .edit-sampel-btn-save:hover {
        border-color: #128986;
        background: #128986;
        color: #fff;
    }

    .edit-sampel-btn-save i,
    .edit-sampel-btn-cancel i {
        margin-right: 6px;
    }

    /* =========================
       RESPONSIVE
       ========================= */

    @media (max-width: 768px) {
        .edit-sampel-card-body {
            padding: 18px 16px;
        }

        .edit-sampel-section-body {
            padding: 16px 14px;
        }

        .edit-sampel-footer {
            flex-direction: column-reverse;
            align-items: stretch;
        }

        .edit-sampel-btn-cancel,
        .edit-sampel-btn-save {
            width: 100%;
        }
    }

    @media (max-width: 576px) {
        .edit-sampel-card-header {
            padding: 14px 15px;
        }

        .edit-sampel-card-title {
            font-size: 0.92rem;
        }

        .edit-sampel-card-body {
            padding: 13px;
        }

        .edit-sampel-section-header {
            padding: 10px 12px;
        }

        .edit-sampel-section-body {
            padding: 14px 12px;
        }

        .edit-sampel-section-heading {
            font-size: 0.82rem;
        }
    }
</style>


<div class="edit-sampel-page">

    <div class="edit-sampel-card">

        <!-- HEADER -->
        <div class="edit-sampel-card-header">
            <h5 class="edit-sampel-card-title">
                <i class="bi bi-pencil-square"></i>
                Edit Data Sampel
            </h5>
        </div>

        <div class="edit-sampel-card-body">

            <!-- VALIDATION ERROR -->
            <?php if (validation_errors()): ?>
                <div class="alert alert-danger alert-dismissible fade show edit-sampel-error" role="alert">
                    <?php echo validation_errors('<div>', '</div>'); ?>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Close">
                    </button>
                </div>
            <?php endif; ?>


            <form
                action="<?php echo site_url('sampel/edit/' . $sampel['id']); ?>"
                method="post">

                <input
                    type="hidden"
                    name="<?php echo $this->security->get_csrf_token_name(); ?>"
                    value="<?php echo $this->security->get_csrf_hash(); ?>">


                <!-- =====================================================
                     SECTION 1
                     ===================================================== -->

                <div class="edit-sampel-section">

                    <div class="edit-sampel-section-header">
                        <div class="edit-sampel-section-number">01</div>

                        <h6 class="edit-sampel-section-heading">
                            Identitas Utama Sampel
                        </h6>
                    </div>

                    <div class="edit-sampel-section-body">

                        <div class="row g-3">

                            <div class="col-md-3">
                                <label for="no" class="form-label">
                                    No.
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="no"
                                    name="no"
                                    value="<?php echo set_value('no', $sampel['no']); ?>">
                            </div>


                            <div class="col-md-4">
                                <label for="kode_sampel_manual" class="form-label">
                                    Kode Sampel Manual
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="kode_sampel_manual"
                                    name="kode_sampel_manual"
                                    value="<?php echo set_value('kode_sampel_manual', $sampel['kode_sampel_manual']); ?>">
                            </div>


                            <div class="col-md-5">
                                <label for="nama_sampel" class="form-label">
                                    Nama Sampel
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="nama_sampel"
                                    name="nama_sampel"
                                    value="<?php echo set_value('nama_sampel', $sampel['nama_sampel']); ?>"
                                    required>
                            </div>


                            <div class="col-md-4">
                                <label for="kategori_sampel" class="form-label">
                                    Kategori Sampel
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="kategori_sampel"
                                    name="kategori_sampel"
                                    value="<?php echo set_value('kategori_sampel', $sampel['kategori_sampel']); ?>">
                            </div>


                            <div class="col-md-4">
                                <label for="sub_kategori" class="form-label">
                                    Sub Kategori
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="sub_kategori"
                                    name="sub_kategori"
                                    value="<?php echo set_value('sub_kategori', $sampel['sub_kategori']); ?>">
                            </div>


                            <div class="col-md-4">
                                <label for="jenis_kelas_terapi" class="form-label">
                                    Jenis / Kelas Terapi
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="jenis_kelas_terapi"
                                    name="jenis_kelas_terapi"
                                    value="<?php echo set_value('jenis_kelas_terapi', $sampel['jenis_kelas_terapi']); ?>">
                            </div>

                        </div>

                    </div>
                </div>


                <!-- =====================================================
                     SECTION 2
                     ===================================================== -->

                <div class="edit-sampel-section">

                    <div class="edit-sampel-section-header">
                        <div class="edit-sampel-section-number">02</div>

                        <h6 class="edit-sampel-section-heading">
                            Asal &amp; Sarana Sampling
                        </h6>
                    </div>

                    <div class="edit-sampel-section-body">

                        <div class="row g-3">

                            <div class="col-md-4">
                                <label for="kategori_sarana" class="form-label">
                                    Kategori Sarana
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="kategori_sarana"
                                    name="kategori_sarana"
                                    value="<?php echo set_value('kategori_sarana', $sampel['kategori_sarana']); ?>">
                            </div>


                            <div class="col-md-4">
                                <label for="nama_sarana" class="form-label">
                                    Nama Sarana
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="nama_sarana"
                                    name="nama_sarana"
                                    value="<?php echo set_value('nama_sarana', $sampel['nama_sarana']); ?>">
                            </div>


                            <div class="col-md-4">
                                <label for="kabupaten_kota" class="form-label">
                                    Kabupaten / Kota
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="kabupaten_kota"
                                    name="kabupaten_kota"
                                    value="<?php echo set_value('kabupaten_kota', $sampel['kabupaten_kota']); ?>">
                            </div>


                            <div class="col-md-4">
                                <label for="tanggal_sampling" class="form-label">
                                    Tanggal Sampling
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="tanggal_sampling"
                                    name="tanggal_sampling"
                                    value="<?php echo set_value('tanggal_sampling', $sampel['tanggal_sampling']); ?>">
                            </div>


                            <div class="col-md-4">
                                <label for="no_sipt" class="form-label">
                                    NO SIPT
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="no_sipt"
                                    name="no_sipt"
                                    value="<?php echo set_value('no_sipt', $sampel['no_sipt']); ?>">
                            </div>


                            <div class="col-md-4">
                                <label for="surtug" class="form-label">
                                    Surat Tugas (Surtug)
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="surtug"
                                    name="surtug"
                                    value="<?php echo set_value('surtug', $sampel['surtug']); ?>">
                            </div>

                        </div>

                    </div>
                </div>


                <!-- =====================================================
                     SECTION 3
                     ===================================================== -->

                <div class="edit-sampel-section">

                    <div class="edit-sampel-section-header">
                        <div class="edit-sampel-section-number">03</div>

                        <h6 class="edit-sampel-section-heading">
                            Detail Produk &amp; Kemasan
                        </h6>
                    </div>

                    <div class="edit-sampel-section-body">

                        <div class="row g-3">

                            <div class="col-md-4">
                                <label for="nomor_izin_edar" class="form-label">
                                    Nomor Izin Edar (NIE)
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="nomor_izin_edar"
                                    name="nomor_izin_edar"
                                    value="<?php echo set_value('nomor_izin_edar', $sampel['nomor_izin_edar']); ?>">
                            </div>


                            <div class="col-md-4">
                                <label for="no_bets" class="form-label">
                                    No. Bets
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="no_bets"
                                    name="no_bets"
                                    value="<?php echo set_value('no_bets', $sampel['no_bets']); ?>">
                            </div>


                            <div class="col-md-4">
                                <label for="kedaluwarsa" class="form-label">
                                    Kedaluwarsa (Exp Date)
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="kedaluwarsa"
                                    name="kedaluwarsa"
                                    value="<?php echo set_value('kedaluwarsa', $sampel['kedaluwarsa']); ?>">
                            </div>


                            <div class="col-md-4">
                                <label for="kondisi_produk" class="form-label">
                                    Kondisi Produk
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="kondisi_produk"
                                    name="kondisi_produk"
                                    value="<?php echo set_value('kondisi_produk', $sampel['kondisi_produk']); ?>">
                            </div>


                            <div class="col-md-4">
                                <label for="kemasan" class="form-label">
                                    Kemasan
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="kemasan"
                                    name="kemasan"
                                    value="<?php echo set_value('kemasan', $sampel['kemasan']); ?>">
                            </div>


                            <div class="col-md-4">
                                <label for="penyimpanan" class="form-label">
                                    Penyimpanan
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="penyimpanan"
                                    name="penyimpanan"
                                    value="<?php echo set_value('penyimpanan', $sampel['penyimpanan']); ?>">
                            </div>


                            <div class="col-md-6">
                                <label for="nama_alamat_perusahaan" class="form-label">
                                    Nama dan Alamat Perusahaan
                                </label>

                                <textarea
                                    class="form-control"
                                    id="nama_alamat_perusahaan"
                                    name="nama_alamat_perusahaan"
                                    rows="2"><?php echo set_value('nama_alamat_perusahaan', $sampel['nama_alamat_perusahaan']); ?></textarea>
                            </div>


                            <div class="col-md-6">
                                <label for="komposisi" class="form-label">
                                    Komposisi
                                </label>

                                <textarea
                                    class="form-control"
                                    id="komposisi"
                                    name="komposisi"
                                    rows="2"><?php echo set_value('komposisi', $sampel['komposisi']); ?></textarea>
                            </div>

                        </div>

                    </div>
                </div>


                <!-- =====================================================
                     SECTION 4
                     ===================================================== -->

                <div class="edit-sampel-section">

                    <div class="edit-sampel-section-header">
                        <div class="edit-sampel-section-number">04</div>

                        <h6 class="edit-sampel-section-heading">
                            Jumlah Sampel &amp; Alokasi
                        </h6>
                    </div>

                    <div class="edit-sampel-section-body">

                        <div class="row g-3">

                            <div class="col-md-2">
                                <label for="jumlah_kimia" class="form-label">
                                    Jumlah Kimia
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="jumlah_kimia"
                                    name="jumlah_kimia"
                                    value="<?php echo set_value('jumlah_kimia', $sampel['jumlah_kimia']); ?>">
                            </div>


                            <div class="col-md-2">
                                <label for="jumlah_mikro" class="form-label">
                                    Jumlah Mikro
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="jumlah_mikro"
                                    name="jumlah_mikro"
                                    value="<?php echo set_value('jumlah_mikro', $sampel['jumlah_mikro']); ?>">
                            </div>


                            <div class="col-md-2">
                                <label for="jumlah_arsip" class="form-label">
                                    Jumlah Arsip
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="jumlah_arsip"
                                    name="jumlah_arsip"
                                    value="<?php echo set_value('jumlah_arsip', $sampel['jumlah_arsip']); ?>">
                            </div>


                            <div class="col-md-3">
                                <label for="jumlah_penandaan" class="form-label">
                                    Jumlah Penandaan
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="jumlah_penandaan"
                                    name="jumlah_penandaan"
                                    value="<?php echo set_value('jumlah_penandaan', $sampel['jumlah_penandaan']); ?>">
                            </div>


                            <div class="col-md-3">
                                <label for="jumlah_total" class="form-label">
                                    Jumlah Total
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="jumlah_total"
                                    name="jumlah_total"
                                    value="<?php echo set_value('jumlah_total', $sampel['jumlah_total']); ?>">
                            </div>

                        </div>

                    </div>
                </div>


                <!-- =====================================================
                     SECTION 5
                     ===================================================== -->

                <div class="edit-sampel-section">

                    <div class="edit-sampel-section-header">
                        <div class="edit-sampel-section-number">05</div>

                        <h6 class="edit-sampel-section-heading">
                            Evaluasi &amp; Administrasi
                        </h6>
                    </div>

                    <div class="edit-sampel-section-body">

                        <div class="row g-3">

                            <div class="col-md-6">
                                <label for="penandaan" class="form-label">
                                    Penandaan (Field Utama)
                                </label>

                                <textarea
                                    class="form-control"
                                    id="penandaan"
                                    name="penandaan"
                                    rows="2"><?php echo set_value('penandaan', $sampel['penandaan']); ?></textarea>
                            </div>


                            <div class="col-md-3">
                                <label for="harga" class="form-label">
                                    Harga
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="harga"
                                    name="harga"
                                    value="<?php echo set_value('harga', $sampel['harga']); ?>">
                            </div>


                            <div class="col-md-3">
                                <label for="tie" class="form-label">
                                    TIE
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="tie"
                                    name="tie"
                                    value="<?php echo set_value('tie', $sampel['tie']); ?>">
                            </div>


                            <div class="col-md-3">
                                <label for="mk" class="form-label">
                                    MK
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="mk"
                                    name="mk"
                                    value="<?php echo set_value('mk', $sampel['mk']); ?>">
                            </div>


                            <div class="col-md-3">
                                <label for="tmk" class="form-label">
                                    TMK
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="tmk"
                                    name="tmk"
                                    value="<?php echo set_value('tmk', $sampel['tmk']); ?>">
                            </div>


                            <div class="col-md-6">
                                <label for="balai_penguji" class="form-label">
                                    Balai Penguji
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="balai_penguji"
                                    name="balai_penguji"
                                    value="<?php echo set_value('balai_penguji', $sampel['balai_penguji']); ?>">
                            </div>

                        </div>

                    </div>
                </div>


                <!-- FOOTER ACTION -->
                <div class="edit-sampel-footer">

                    <a
                        href="<?php echo site_url('sampel/detail/' . $sampel['id']); ?>"
                        class="edit-sampel-btn-cancel">
                        <i class="bi bi-x-lg"></i>
                        Batal
                    </a>

                    <button
                        type="submit"
                        class="edit-sampel-btn-save">
                        <i class="bi bi-check-lg"></i>
                        Simpan Perubahan
                    </button>

                </div>

            </form>

        </div>
    </div>

</div>