<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
    /* =========================================================
       AKUN PENGGUNA
       Tampilan saja - fungsi tetap sama
       ========================================================= */

    .akun-page {
        color: #294257;
        font-size: 0.88rem;
    }

    /* =========================================================
       LAYOUT
       ========================================================= */

    .akun-grid {
        display: grid;
        grid-template-columns: minmax(300px, 0.9fr) minmax(420px, 1.4fr);
        gap: 16px;
        align-items: stretch;
    }

    /* =========================================================
       CARD
       ========================================================= */

    .akun-card {
        background: #fff;
        border: 1px solid #e0e8ed;
        border-radius: 10px;
        box-shadow: 0 5px 18px rgba(35, 58, 76, 0.07);
        overflow: hidden;
        height: 100%;
    }

    .akun-card-header {
        min-height: 53px;
        padding: 13px 16px;
        border-bottom: 1px solid #e8eef2;
        display: flex;
        align-items: center;
    }

    .akun-card-title {
        margin: 0;
        color: #294257;
        font-size: 0.82rem;
        font-weight: 700;
    }

    .akun-card-title i {
        color: #159b98;
        margin-right: 8px;
        font-size: 0.95rem;
    }

    .akun-card-body {
        padding: 18px;
    }

    /* =========================================================
       PROFILE
       ========================================================= */

    .akun-profile {
        text-align: center;
        padding: 3px 0 17px;
        border-bottom: 1px solid #edf1f3;
        margin-bottom: 14px;
    }

    .akun-avatar {
        width: 64px;
        height: 64px;
        margin: 0 auto 9px;
        border-radius: 50%;
        background: #e3f6f4;
        color: #159b98;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.45rem;
        font-weight: 700;
    }

    .akun-profile-name {
        margin: 0 0 7px;
        color: #263f55;
        font-size: 0.92rem;
        font-weight: 700;
    }

    .akun-role {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 20px;
        border: 1px solid #cfe5e3;
        background: #f1faf9;
        color: #168d88;
        font-size: 0.66rem;
        font-weight: 700;
    }

    /* =========================================================
       PROFILE DATA
       ========================================================= */

    .akun-info-list {
        margin: 0;
    }

    .akun-info-row {
        display: grid;
        grid-template-columns: 105px minmax(0, 1fr);
        gap: 10px;
        padding: 9px 0;
        border-bottom: 1px solid #edf1f3;
    }

    .akun-info-row:last-child {
        border-bottom: 0;
        padding-bottom: 0;
    }

    .akun-info-label {
        color: #8495a1;
        font-size: 0.70rem;
        font-weight: 500;
    }

    .akun-info-value {
        color: #526779;
        font-size: 0.73rem;
        font-weight: 600;
        word-break: break-word;
    }

    .akun-status {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 9px;
        border-radius: 20px;
        background: #e5f6e9;
        border: 1px solid #cdebd4;
        color: #268447;
        font-size: 0.64rem;
        font-weight: 700;
    }

    .akun-status::before {
        content: "";
        width: 5px;
        height: 5px;
        border-radius: 50%;
        background: #268447;
    }

    /* =========================================================
       TTD DESCRIPTION
       ========================================================= */

    .akun-ttd-description {
        margin: 0 0 16px;
        color: #71869a;
        font-size: 0.73rem;
        line-height: 1.55;
    }

    /* =========================================================
       ALERT
       ========================================================= */

    .akun-page .alert {
        border-radius: 7px;
        font-size: 0.72rem;
        margin-bottom: 15px;
    }

    .akun-page .alert i {
        font-size: 0.95rem;
    }

    /* =========================================================
       TTD PREVIEW
       ========================================================= */

    .ttd-preview {
        padding: 18px;
        margin-bottom: 16px;
        border: 1px solid #dfe8ec;
        border-radius: 8px;
        background: #f7f9fa;
        text-align: center;
    }

    /* =========================================================
       KOTAK GAMBAR TTD
       ========================================================= */

    .ttd-image-box {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        min-height: 120px;
        padding: 12px 20px;
        margin: 0 auto 12px;
        background: #fff;
        border: 1px solid #e0e8ed;
        border-radius: 7px;
        box-sizing: border-box;
    }

    .ttd-image-box img {
        display: block;
        max-height: 100px;
        max-width: 280px;
        margin: 0 auto;
        object-fit: contain;
    }

    /* =========================================================
       STATUS TTD TERSIMPAN
       ========================================================= */

    .ttd-saved-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        padding: 4px 9px;
        margin-bottom: 7px;
        border: 1px solid #cdebd4;
        border-radius: 20px;
        background: #e5f6e9;
        color: #268447;
        font-size: 0.64rem;
        font-weight: 700;
    }

    /* =========================================================
       WAKTU UPDATE TTD
       ========================================================= */

    .ttd-updated {
        margin-top: 2px;
        color: #94a2ad;
        font-size: 0.64rem;
        text-align: center;
    }

    /* =========================================================
       UPLOAD SECTION
       ========================================================= */

    .ttd-upload-box {
        padding: 15px;
        border: 1px solid #e0e8ed;
        border-radius: 8px;
        background: #fff;
    }

    .ttd-upload-title {
        margin: 0 0 13px;
        color: #294257;
        font-size: 0.78rem;
        font-weight: 700;
    }

    .ttd-upload-box .form-label {
        margin-bottom: 6px;
        color: #71869a;
        font-size: 0.68rem;
        font-weight: 600;
    }

    .ttd-upload-box .form-control {
        height: 36px;
        padding: 6px 10px;
        border: 1px solid #dfe7eb;
        border-radius: 6px;
        background: #f8fafb;
        color: #526779;
        font-size: 0.70rem;
        box-shadow: none;
    }

    .ttd-upload-box .form-control:focus {
        border-color: #b8d4d2;
        background: #fff;
        box-shadow: none;
    }

    .ttd-upload-box .form-text {
        margin-top: 5px;
        font-size: 0.64rem;
    }

    .ttd-upload-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-top: 14px;
        padding-top: 13px;
        border-top: 1px solid #edf1f3;
    }

    .btn-ttd-save {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        height: 34px;
        padding: 0 13px;
        border: 1px solid #159b98;
        border-radius: 6px;
        background: #159b98;
        color: #fff;
        font-size: 0.70rem;
        font-weight: 700;
    }

    .btn-ttd-save:hover {
        background: #128a87;
        border-color: #128a87;
        color: #fff;
    }

    .btn-ttd-delete {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        height: 32px;
        padding: 0 11px;
        border-radius: 6px;
        font-size: 0.68rem;
        font-weight: 700;
    }

    /* =========================================================
       TTD TIDAK TERSEDIA
       ========================================================= */

    .ttd-not-available {
        padding: 15px;
        margin: 4px 0 0;
        border: 1px solid #dfe5e9;
        border-radius: 8px;
        background: #f7f9fa;
        display: flex;
        align-items: flex-start;
        gap: 11px;
    }

    .ttd-not-available i {
        margin-top: 1px;
        color: #81919d;
        font-size: 1rem;
    }

    .ttd-not-available strong {
        display: block;
        margin-bottom: 3px;
        color: #526779;
        font-size: 0.73rem;
    }

    .ttd-not-available span {
        color: #8797a4;
        font-size: 0.68rem;
        line-height: 1.45;
    }

    /* =========================================================
       RESPONSIVE
       ========================================================= */

    @media (max-width: 992px) {

        .akun-grid {
            grid-template-columns: 1fr;
        }

    }

    @media (max-width: 576px) {

        .akun-card-body {
            padding: 14px;
        }

        .akun-info-row {
            grid-template-columns: 90px minmax(0, 1fr);
        }

        .ttd-upload-actions {
            flex-direction: column;
            align-items: stretch;
        }

        .btn-ttd-save,
        .btn-ttd-delete {
            width: 100%;
        }

        .ttd-image-box {
            min-width: 0;
            width: 100%;
        }

    }
</style>


<div class="akun-page">

    <div class="akun-grid">

        <!-- =====================================================
             INFORMASI PENGGUNA
             ===================================================== -->

        <div class="akun-card">

            <div class="akun-card-header">

                <h5 class="akun-card-title">
                    <i class="bi bi-person-badge"></i>
                    Informasi Profil Saya
                </h5>

            </div>


            <div class="akun-card-body">

                <!-- PROFILE -->

                <div class="akun-profile">

                    <div class="akun-avatar">

                        <?php echo strtoupper(substr($user['nama_lengkap'] ?: 'U', 0, 1)); ?>

                    </div>

                    <h5 class="akun-profile-name">

                        <?php echo html_escape($user['nama_lengkap']); ?>

                    </h5>

                    <span class="akun-role">

                        <?php echo html_escape($user['nama_role']); ?>

                    </span>

                </div>


                <!-- INFORMASI -->

                <div class="akun-info-list">

                    <div class="akun-info-row">

                        <div class="akun-info-label">
                            Username
                        </div>

                        <div class="akun-info-value">
                            <?php echo html_escape($user['username']); ?>
                        </div>

                    </div>


                    <div class="akun-info-row">

                        <div class="akun-info-label">
                            NIP
                        </div>

                        <div class="akun-info-value">
                            <?php echo html_escape($user['nip'] ?: '-'); ?>
                        </div>

                    </div>


                    <div class="akun-info-row">

                        <div class="akun-info-label">
                            Email
                        </div>

                        <div class="akun-info-value">
                            <?php echo html_escape($user['email'] ?: '-'); ?>
                        </div>

                    </div>


                    <div class="akun-info-row">

                        <div class="akun-info-label">
                            Laboratorium
                        </div>

                        <div class="akun-info-value">
                            <?php echo html_escape($user['nama_lab'] ?: 'Seluruh Lab'); ?>
                        </div>

                    </div>


                    <div class="akun-info-row">

                        <div class="akun-info-label">
                            Status Akun
                        </div>

                        <div class="akun-info-value">

                            <span class="akun-status">

                                <?php echo html_escape($user['status']); ?>

                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        <!-- =====================================================
             TANDA TANGAN DIGITAL
             ===================================================== -->

        <div class="akun-card">

            <div class="akun-card-header">

                <h5 class="akun-card-title">
                    <i class="bi bi-pen-fill"></i>
                    Tanda Tangan Digital (TTD)
                </h5>

            </div>


            <div class="akun-card-body">

                <?php if ($boleh_ttd): ?>


                    <p class="akun-ttd-description">

                        Tanda tangan digital ini terhubung dengan akun pengguna Anda
                        dan digunakan secara otomatis dalam dokumen dan laporan
                        pengujian resmi sesuai wewenang role Anda.

                    </p>


                    <!-- =================================================
                         FLASH SUCCESS
                         ================================================= -->

                    <?php if ($this->session->flashdata('pesan_sukses')): ?>

                        <div
                            class="alert alert-success alert-dismissible fade show"
                            role="alert"
                        >

                            <i class="bi bi-check-circle-fill me-2"></i>

                            <?php echo $this->session->flashdata('pesan_sukses'); ?>

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
                            class="alert alert-danger alert-dismissible fade show"
                            role="alert"
                        >

                            <i class="bi bi-exclamation-triangle-fill me-2"></i>

                            <?php echo $this->session->flashdata('pesan_gagal'); ?>

                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="alert"
                                aria-label="Close"
                            ></button>

                        </div>

                    <?php endif; ?>


                    <!-- =================================================
                         PREVIEW TTD
                         ================================================= -->

                    <?php if (!empty($user_signature) && file_exists(FCPATH . $user_signature['signature_file'])): ?>

                        <div class="ttd-preview">

                            <!-- GAMBAR TANDA TANGAN -->

                            <div class="ttd-image-box">

                                <img
                                    src="<?php echo base_url($user_signature['signature_file']); ?>"
                                    alt="TTD Saya"
                                >

                            </div>


                            <!-- STATUS TTD -->

                            <div class="ttd-saved-badge">

                                <i class="bi bi-check-circle-fill"></i>

                                Tanda Tangan Digital Tersimpan

                            </div>


                            <!-- WAKTU UPDATE -->

                            <div class="ttd-updated">

                                Terakhir diperbarui:

                                <?php echo date('d M Y H:i', strtotime($user_signature['updated_at'])); ?>

                                WIB

                            </div>

                        </div>

                    <?php else: ?>


                        <div
                            class="alert alert-warning border-warning d-flex align-items-center"
                            role="alert"
                        >

                            <i class="bi bi-exclamation-circle-fill fs-4 me-3 text-warning"></i>

                            <div>

                                <strong>
                                    Belum ada Tanda Tangan Digital.
                                </strong>

                                <br>

                                <span class="small">

                                    Silakan unggah gambar tanda tangan Anda di bawah
                                    ini agar siap digunakan pada dokumen.

                                </span>

                            </div>

                        </div>

                    <?php endif; ?>


                    <!-- =================================================
                         FORM UPLOAD / GANTI TTD
                         ================================================= -->

                    <div class="ttd-upload-box">

                        <h6 class="ttd-upload-title">

                            <?php echo (!empty($user_signature))
                                ? 'Ganti Tanda Tangan Digital'
                                : 'Unggah Tanda Tangan Digital Baru'; ?>

                        </h6>


                        <?php echo form_open_multipart('akun/upload_ttd'); ?>


                            <div class="mb-3">

                                <label
                                    for="file_ttd"
                                    class="form-label"
                                >

                                    Pilih File Gambar TTD
                                    (PNG / JPG / JPEG, Max 2MB)

                                </label>


                                <input
                                    class="form-control"
                                    type="file"
                                    id="file_ttd"
                                    name="file_ttd"
                                    accept="image/png, image/jpeg, image/jpg"
                                    required
                                >


                                <div class="form-text text-muted">

                                    Disarankan menggunakan gambar TTD
                                    transparan berformat PNG.

                                </div>

                            </div>


                            <div class="ttd-upload-actions">

                                <!-- SIMPAN TTD -->

                                <button
                                    type="submit"
                                    class="btn-ttd-save"
                                >

                                    <i class="bi bi-upload"></i>

                                    <?php echo (!empty($user_signature))
                                        ? 'Simpan TTD Baru'
                                        : 'Unggah TTD'; ?>

                                </button>


                        <?php echo form_close(); ?>


                                <!-- HAPUS TTD -->

                                <?php if (!empty($user_signature)): ?>

                                    <?php echo form_open(
                                        'akun/hapus_ttd',
                                        array('class' => 'd-inline')
                                    ); ?>

                                        <button
                                            type="submit"
                                            class="btn btn-outline-danger btn-ttd-delete"
                                            onclick="return confirm('Apakah Anda yakin ingin nonaktifkan TTD digital ini dari akun Anda?');"
                                        >

                                            <i class="bi bi-trash"></i>

                                            Nonaktifkan TTD

                                        </button>

                                    <?php echo form_close(); ?>

                                <?php endif; ?>


                            </div>

                    </div>


                <?php else: ?>


                    <!-- =================================================
                         ROLE TIDAK MEMILIKI TTD
                         ================================================= -->

                    <div class="ttd-not-available">

                        <i class="bi bi-info-circle-fill"></i>

                        <div>

                            <strong>
                                Fitur TTD Tidak Tersedia untuk Role Anda.
                            </strong>

                            <span>
                                Fitur Tanda Tangan Digital khusus diperuntukkan
                                bagi Penguji, Penyelia, dan Manajer Teknis.
                            </span>

                        </div>

                    </div>


                <?php endif; ?>

            </div>

        </div>

    </div>

</div>