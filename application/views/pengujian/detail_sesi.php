<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
    .detail-sesi-page {
        color: #294257;
        font-size: .88rem;
    }

    /* =========================
       ALERT
    ========================= */
    .detail-sesi-page .alert {
        border-radius: 8px;
        border-width: 1px;
        font-size: .80rem;
        padding: 11px 14px;
        margin-bottom: 18px;
    }

    /* =========================
       MAIN CARD
    ========================= */
    .detail-sesi-card {
        background: #fff;
        border: 1px solid #e0e8ed;
        border-radius: 11px;
        box-shadow: 0 5px 18px rgba(35, 58, 76, .06);
        overflow: hidden;
        margin-bottom: 20px;
    }

    /* =========================
       PAGE HEADER
    ========================= */
    .detail-sesi-header {
        padding: 17px 20px;
        background: #fff;
        border-bottom: 1px solid #e7edf0;
    }

    .detail-sesi-header-main {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }

    .detail-sesi-header-icon {
        width: 40px;
        height: 40px;
        flex: 0 0 40px;
        border-radius: 9px;
        background: #e4f6f4;
        color: #159b98;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
    }

    .detail-sesi-title {
        margin: 0;
        color: #294257;
        font-size: 1rem;
        font-weight: 700;
        line-height: 1.35;
    }

    .detail-sesi-subtitle {
        margin: 3px 0 0;
        color: #7a8d9a;
        font-size: .72rem;
    }

    .detail-sesi-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        flex-wrap: wrap;
        gap: 7px;
    }

    .detail-sesi-actions .btn {
        min-height: 34px;
        padding: 6px 11px;
        border-radius: 7px;
        font-size: .72rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        white-space: nowrap;
    }

    .detail-sesi-actions .btn-light {
        background: #f7f9fa;
        border-color: #dfe7eb;
        color: #5d7180;
    }

    .detail-sesi-actions .btn-warning {
        background: #fff5dc;
        border-color: #f2d48a;
        color: #946b08;
    }

    .detail-sesi-actions .btn-success {
        background: #e5f6e9;
        border-color: #cdebd4;
        color: #268447;
    }

    .detail-sesi-actions .btn-danger {
        background: #fff0f0;
        border-color: #f0cccc;
        color: #bd4545;
    }

    .detail-sesi-actions .btn-danger:hover,
    .detail-sesi-actions .btn-success:hover,
    .detail-sesi-actions .btn-warning:hover,
    .detail-sesi-actions .btn-light:hover {
        filter: brightness(.97);
    }

    /* =========================
       CARD BODY
    ========================= */
    .detail-sesi-body {
        padding: 20px;
    }

    /* =========================
       REJECTION NOTICE
    ========================= */
    .detail-sesi-rejection {
        margin: 0;
        padding: 15px 20px;
        background: #fff5f5;
        border-bottom: 1px solid #f0d0d0;
    }

    .detail-sesi-rejection-inner {
        display: flex;
        align-items: flex-start;
        gap: 11px;
    }

    .detail-sesi-rejection-icon {
        width: 34px;
        height: 34px;
        flex: 0 0 34px;
        border-radius: 8px;
        background: #fde4e4;
        color: #c94b4b;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }

    .detail-sesi-rejection-title {
        margin: 0 0 5px;
        color: #a53d3d;
        font-size: .78rem;
        font-weight: 700;
    }

    .detail-sesi-rejection-text {
        margin: 0;
        padding: 9px 11px;
        background: #fff;
        border: 1px solid #efd5d5;
        border-radius: 7px;
        color: #536876;
        font-size: .77rem;
        line-height: 1.55;
    }

    /* =========================
       INFO SECTION
    ========================= */
    .detail-sesi-info-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
    }

    .detail-sesi-info-box {
        border: 1px solid #e2e9ed;
        border-radius: 9px;
        background: #fbfcfd;
        overflow: hidden;
    }

    .detail-sesi-info-title {
        display: flex;
        align-items: center;
        gap: 7px;
        padding: 11px 13px;
        border-bottom: 1px solid #e5ebee;
        background: #fff;
        color: #294257;
        font-size: .76rem;
        font-weight: 700;
    }

    .detail-sesi-info-title i {
        color: #159b98;
        font-size: .90rem;
    }

    .detail-sesi-info-list {
        margin: 0;
        padding: 3px 13px;
    }

    .detail-sesi-info-row {
        display: grid;
        grid-template-columns: 38% 62%;
        gap: 8px;
        padding: 9px 0;
        border-bottom: 1px solid #edf1f3;
        align-items: center;
    }

    .detail-sesi-info-row:last-child {
        border-bottom: 0;
    }

    .detail-sesi-label {
        color: #7b8d99;
        font-size: .72rem;
    }

    .detail-sesi-value {
        color: #3b5262;
        font-size: .76rem;
        font-weight: 500;
        word-break: break-word;
    }

    .detail-sesi-value strong {
        color: #294257;
        font-weight: 700;
    }

    .detail-sesi-code {
        display: inline-flex;
        align-items: center;
        min-height: 25px;
        padding: 3px 8px;
        border-radius: 6px;
        background: #eef8f7;
        color: #148d89;
        border: 1px solid #d6ecea;
        font-family: monospace;
        font-size: .72rem;
        font-weight: 700;
    }

    .detail-sesi-type {
        display: inline-flex;
        align-items: center;
        padding: 4px 9px;
        border-radius: 20px;
        background: #e4f6f4;
        border: 1px solid #cce9e7;
        color: #168d89;
        font-size: .68rem;
        font-weight: 700;
    }

    /* =========================
       STATUS BADGES
    ========================= */
    .detail-sesi-status {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 9px;
        border-radius: 20px;
        font-size: .67rem;
        font-weight: 700;
        line-height: 1;
        border: 1px solid transparent;
    }

    .detail-sesi-status::before {
        content: "";
        width: 5px;
        height: 5px;
        border-radius: 50%;
        background: currentColor;
    }

    .status-sedang {
        background: #fff4d8;
        border-color: #f0dca5;
        color: #94700d;
    }

    .status-verifikasi {
        background: #e5f5fa;
        border-color: #c9e7ef;
        color: #25758a;
    }

    .status-approval {
        background: #e8eefb;
        border-color: #d1dcf2;
        color: #4b6694;
    }

    .status-final {
        background: #e5f6e9;
        border-color: #cdebd4;
        color: #268447;
    }

    .status-ditolak {
        background: #fde8e8;
        border-color: #f1cccc;
        color: #b54545;
    }

    .status-default {
        background: #f0f3f5;
        border-color: #dde4e8;
        color: #687985;
    }

    /* =========================
       FORM TABLE CARD
    ========================= */
    .detail-sesi-form-card {
        background: #fff;
        border: 1px solid #e0e8ed;
        border-radius: 11px;
        box-shadow: 0 5px 18px rgba(35, 58, 76, .06);
        overflow: hidden;
    }

    .detail-sesi-form-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        min-height: 57px;
        padding: 14px 18px;
        border-bottom: 1px solid #e7edf0;
        background: #fff;
    }

    .detail-sesi-form-title {
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 0;
        color: #294257;
        font-size: .84rem;
        font-weight: 700;
    }

    .detail-sesi-form-title i {
        color: #159b98;
        font-size: 1rem;
    }

    .detail-sesi-form-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 26px;
        height: 23px;
        padding: 0 7px;
        border-radius: 12px;
        background: #e4f6f4;
        color: #168d89;
        font-size: .66rem;
        font-weight: 700;
    }

    .detail-sesi-table-wrap {
        overflow-x: auto;
    }

    .detail-sesi-table {
        width: 100%;
        min-width: 760px;
        margin: 0;
        border-collapse: separate;
        border-spacing: 0;
    }

    .detail-sesi-table thead th {
        padding: 11px 13px;
        background: #f7f9fa;
        border-bottom: 1px solid #dfe7eb;
        color: #627684;
        font-size: .67rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .02em;
        white-space: nowrap;
    }

    .detail-sesi-table tbody td {
        padding: 12px 13px;
        border-bottom: 1px solid #edf1f3;
        color: #526875;
        font-size: .75rem;
        vertical-align: middle;
    }

    .detail-sesi-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .detail-sesi-table tbody tr:hover {
        background: #fbfdfd;
    }

    .detail-sesi-number {
        color: #94a3ad;
        font-size: .70rem;
        font-weight: 600;
    }

    .detail-sesi-template-code {
        display: inline-flex;
        padding: 4px 7px;
        border-radius: 5px;
        background: #f2f7f8;
        border: 1px solid #e0e8eb;
        color: #4e6877;
        font-family: monospace;
        font-size: .68rem;
        font-weight: 700;
    }

    .detail-sesi-template-name {
        color: #294257;
        font-size: .75rem;
        font-weight: 700;
    }

    .detail-sesi-category {
        display: inline-flex;
        align-items: center;
        padding: 4px 8px;
        border-radius: 5px;
        background: #f7f9fa;
        border: 1px solid #e1e8ec;
        color: #637784;
        font-size: .66rem;
        font-weight: 600;
    }

    .detail-sesi-result {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 8px;
        border-radius: 20px;
        font-size: .65rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .detail-sesi-result-done {
        background: #e5f6e9;
        border: 1px solid #cdebd4;
        color: #268447;
    }

    .detail-sesi-result-empty {
        background: #f1f3f5;
        border: 1px solid #e0e5e8;
        color: #7a8992;
    }

    .detail-sesi-conclusion {
        color: #536a77;
        font-size: .73rem;
        line-height: 1.4;
    }

    .detail-sesi-empty {
        padding: 40px 20px !important;
        text-align: center;
        color: #8999a3 !important;
        font-size: .76rem !important;
    }

    .detail-sesi-empty-icon {
        width: 42px;
        height: 42px;
        margin: 0 auto 10px;
        border-radius: 9px;
        background: #f1f5f6;
        color: #9aa9b1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
    }

    /* =========================
       MODAL
    ========================= */
    .detail-sesi-modal .modal-content {
        border: 1px solid #e0e8ed;
        border-radius: 11px;
        overflow: hidden;
        box-shadow: 0 12px 35px rgba(35, 58, 76, .16);
    }

    .detail-sesi-modal .modal-header {
        padding: 15px 18px;
        background: #fff5f5;
        border-bottom: 1px solid #f0d4d4;
        color: #a53d3d;
    }

    .detail-sesi-modal .modal-title {
        font-size: .88rem;
        font-weight: 700;
    }

    .detail-sesi-modal .modal-body {
        padding: 19px;
    }

    .detail-sesi-modal .modal-body p {
        color: #71838e;
        font-size: .76rem;
        line-height: 1.6;
    }

    .detail-sesi-modal .form-label {
        color: #4c6270;
        font-size: .77rem;
        font-weight: 700;
    }

    .detail-sesi-modal textarea {
        min-height: 105px;
        padding: 10px 12px;
        border: 1px solid #dfe7eb;
        border-radius: 7px;
        background: #f9fbfc;
        color: #435a68;
        font-size: .76rem;
        resize: vertical;
    }

    .detail-sesi-modal textarea:focus {
        border-color: #a9cfcd;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(21, 155, 152, .08);
        outline: none;
    }

    .detail-sesi-modal .modal-footer {
        padding: 12px 18px;
        border-top: 1px solid #edf1f3;
        background: #fbfcfd;
    }

    .detail-sesi-modal .modal-footer .btn {
        min-height: 36px;
        padding: 6px 14px;
        border-radius: 7px;
        font-size: .74rem;
        font-weight: 600;
    }

    /* =========================
       RESPONSIVE
    ========================= */
    @media (max-width: 991.98px) {
        .detail-sesi-header {
            padding: 15px;
        }

        .detail-sesi-header-main {
            margin-bottom: 12px;
        }

        .detail-sesi-actions {
            justify-content: flex-start;
        }

        .detail-sesi-body {
            padding: 15px;
        }

        .detail-sesi-info-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 767.98px) {
        .detail-sesi-header {
            display: block !important;
        }

        .detail-sesi-actions {
            width: 100%;
        }

        .detail-sesi-actions .btn {
            flex: 0 0 auto;
        }

        .detail-sesi-info-row {
            grid-template-columns: 1fr;
            gap: 3px;
        }

        .detail-sesi-form-header {
            align-items: flex-start;
        }

        .detail-sesi-rejection {
            padding: 14px 15px;
        }
    }

    @media (max-width: 575.98px) {
        .detail-sesi-title {
            font-size: .91rem;
        }

        .detail-sesi-subtitle {
            font-size: .68rem;
        }

        .detail-sesi-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        .detail-sesi-actions .btn,
        .detail-sesi-actions form {
            width: 100%;
        }

        .detail-sesi-actions form .btn {
            width: 100%;
        }

        .detail-sesi-body {
            padding: 12px;
        }

        .detail-sesi-info-title {
            padding: 10px 11px;
        }

        .detail-sesi-info-list {
            padding: 3px 11px;
        }
    }
</style>

<div class="detail-sesi-page">

    <!-- Flash Messages -->
    <?php if ($this->session->flashdata('pesan_sukses')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>
            <?php echo html_escape($this->session->flashdata('pesan_sukses')); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('pesan_gagal')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i>
            <?php echo html_escape($this->session->flashdata('pesan_gagal')); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- =========================
         DETAIL SESI
    ========================== -->
    <div class="detail-sesi-card">

        <!-- Header -->
        <div class="detail-sesi-header">
            <div class="row align-items-center g-3">

                <div class="col-lg">
                    <div class="detail-sesi-header-main">
                        <div class="detail-sesi-header-icon">
                            <i class="bi bi-clipboard2-pulse"></i>
                        </div>

                        <div>
                            <h5 class="detail-sesi-title">
                                Detail Sesi Pengujian #<?php echo $sesi['id']; ?>
                            </h5>
                            <p class="detail-sesi-subtitle">
                                Informasi sesi, penguji, status, dan daftar form pengujian
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-auto">
                    <div class="detail-sesi-actions">

                        <a href="<?php echo site_url('pengujian/antrean'); ?>"
                           class="btn btn-light">
                            <i class="bi bi-arrow-left me-1"></i>
                            Kembali ke Antrean
                        </a>

                        <?php if ($sesi['penguji_id'] == $this->session->userdata('user_id') && $sesi['status'] === 'Sedang Diuji'): ?>
                            <a href="<?php echo site_url('pengujian/proses_sesi/' . $sesi['id']); ?>"
                               class="btn btn-warning">
                                <i class="bi bi-play-circle me-1"></i>
                                Lanjutkan Pengujian
                            </a>
                        <?php endif; ?>

                        <?php if ($sesi['status'] === 'Menunggu Verifikasi' && $this->Model_Hak_Akses->memiliki_akses($this->session->userdata('role_id'), 'pengujian_verify')): ?>

                            <form action="<?php echo site_url('pengujian/verifikasi_sesi/' . $sesi['id']); ?>"
                                  method="post"
                                  class="d-inline"
                                  onsubmit="return confirm('Apakah Anda yakin ingin memverifikasi dan menyetujui seluruh hasil pengujian sesi ini?');">

                                <input type="hidden"
                                       name="<?php echo $this->security->get_csrf_token_name(); ?>"
                                       value="<?php echo $this->security->get_csrf_hash(); ?>">

                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-check-circle me-1"></i>
                                    Verifikasi Sesi
                                </button>
                            </form>

                            <button type="button"
                                    class="btn btn-danger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalTolakSesi">
                                <i class="bi bi-x-circle me-1"></i>
                                Tolak Sesi
                            </button>

                        <?php endif; ?>

                        <?php if ($sesi['status'] === 'Menunggu Approval' && $this->Model_Hak_Akses->memiliki_akses($this->session->userdata('role_id'), 'pengujian_approve')): ?>

                            <form action="<?php echo site_url('pengujian/approve_sesi/' . $sesi['id']); ?>"
                                  method="post"
                                  class="d-inline"
                                  onsubmit="return confirm('Apakah Anda yakin ingin melakukan Approval final pada seluruh hasil pengujian sesi ini?');">

                                <input type="hidden"
                                       name="<?php echo $this->security->get_csrf_token_name(); ?>"
                                       value="<?php echo $this->security->get_csrf_hash(); ?>">

                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-shield-check me-1"></i>
                                    Approve Final Sesi
                                </button>
                            </form>

                        <?php endif; ?>

                        <?php if ($sesi['status'] === 'Approved / Final'): ?>

                            <a href="<?php echo site_url('pengujian/pdf_sesi/' . $sesi['id']); ?>"
                               class="btn btn-danger"
                               target="_blank">
                                <i class="bi bi-file-earmark-pdf-fill me-1"></i>
                                Unduh LHU PDF
                            </a>

                        <?php endif; ?>

                        <?php if ($sesi['penguji_id'] == $this->session->userdata('user_id') && $sesi['status'] === 'Ditolak'): ?>

                            <a href="<?php echo site_url('pengujian/revisi_sesi/' . $sesi['id']); ?>"
                               class="btn btn-warning">
                                <i class="bi bi-pencil-square me-1"></i>
                                Perbaiki &amp; Revisi Hasil Sesi
                            </a>

                        <?php endif; ?>

                    </div>
                </div>

            </div>
        </div>

        <!-- Rejection Notice -->
        <?php if ($sesi['status'] === 'Ditolak' && !empty($sesi['alasan_penolakan'])): ?>

            <div class="detail-sesi-rejection">
                <div class="detail-sesi-rejection-inner">

                    <div class="detail-sesi-rejection-icon">
                        <i class="bi bi-exclamation-octagon-fill"></i>
                    </div>

                    <div class="flex-grow-1">
                        <p class="detail-sesi-rejection-title">
                            Sesi Pengujian Ditolak oleh Penyelia
                        </p>

                        <div class="detail-sesi-rejection-text">
                            <?php echo nl2br(html_escape($sesi['alasan_penolakan'])); ?>
                        </div>
                    </div>

                </div>
            </div>

        <?php endif; ?>

        <!-- Session Information -->
        <div class="detail-sesi-body">

            <div class="detail-sesi-info-grid">

                <!-- Sampel -->
                <div class="detail-sesi-info-box">

                    <div class="detail-sesi-info-title">
                        <i class="bi bi-box-seam"></i>
                        Informasi Sampel
                    </div>

                    <div class="detail-sesi-info-list">

                        <div class="detail-sesi-info-row">
                            <div class="detail-sesi-label">
                                Nama Sampel
                            </div>
                            <div class="detail-sesi-value">
                                <strong><?php echo html_escape($sesi['nama_sampel']); ?></strong>
                            </div>
                        </div>

                        <div class="detail-sesi-info-row">
                            <div class="detail-sesi-label">
                                Kode / No Sampel
                            </div>
                            <div class="detail-sesi-value">
                                <span class="detail-sesi-code">
                                    <?php echo html_escape($sesi['kode_sampel_manual'] ?: ($sesi['no'] ? 'NO-' . $sesi['no'] : 'SMP-' . $sesi['sample_id'])); ?>
                                </span>
                            </div>
                        </div>

                        <div class="detail-sesi-info-row">
                            <div class="detail-sesi-label">
                                Jenis Pengujian
                            </div>
                            <div class="detail-sesi-value">
                                <span class="detail-sesi-type">
                                    <i class="bi bi-beaker me-1"></i>
                                    <?php echo html_escape($sesi['jenis_pengujian']); ?>
                                </span>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Sesi -->
                <div class="detail-sesi-info-box">

                    <div class="detail-sesi-info-title">
                        <i class="bi bi-clipboard-check"></i>
                        Informasi Sesi
                    </div>

                    <div class="detail-sesi-info-list">

                        <div class="detail-sesi-info-row">
                            <div class="detail-sesi-label">
                                Status Sesi
                            </div>

                            <div class="detail-sesi-value">

                                <?php
                                $badge_cls = 'status-default';

                                if ($sesi['status'] === 'Sedang Diuji') {
                                    $badge_cls = 'status-sedang';
                                } elseif ($sesi['status'] === 'Menunggu Verifikasi') {
                                    $badge_cls = 'status-verifikasi';
                                } elseif ($sesi['status'] === 'Menunggu Approval') {
                                    $badge_cls = 'status-approval';
                                } elseif ($sesi['status'] === 'Approved / Final') {
                                    $badge_cls = 'status-final';
                                } elseif ($sesi['status'] === 'Ditolak') {
                                    $badge_cls = 'status-ditolak';
                                }
                                ?>

                                <span class="detail-sesi-status <?php echo $badge_cls; ?>">
                                    <?php echo html_escape($sesi['status']); ?>
                                </span>

                            </div>
                        </div>

                        <div class="detail-sesi-info-row">
                            <div class="detail-sesi-label">
                                Penguji
                            </div>

                            <div class="detail-sesi-value">
                                <strong>
                                    <?php echo html_escape($sesi['nama_penguji'] ?: 'Penguji ID: ' . $sesi['penguji_id']); ?>
                                </strong>
                            </div>
                        </div>

                        <div class="detail-sesi-info-row">
                            <div class="detail-sesi-label">
                                Waktu Mulai
                            </div>

                            <div class="detail-sesi-value">
                                <?php echo html_escape($sesi['waktu_mulai'] ?: '-'); ?>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

        </div>

    </div>


    <!-- =========================
         FORM & PARAMETER
    ========================== -->
    <div class="detail-sesi-form-card">

        <div class="detail-sesi-form-header">

            <h6 class="detail-sesi-form-title">
                <i class="bi bi-list-check"></i>
                Form &amp; Parameter Terdaftar
                <span class="detail-sesi-form-count">
                    <?php echo count($forms); ?> Form
                </span>
            </h6>

        </div>

        <div class="detail-sesi-table-wrap">

            <table class="detail-sesi-table">

                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th>Kode Template</th>
                        <th>Nama Parameter / Template</th>
                        <th>Kategori</th>
                        <th>Status Hasil</th>
                        <th>Kesimpulan</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($forms)): ?>

                        <?php $no = 1; foreach ($forms as $f): ?>

                            <tr>

                                <td>
                                    <span class="detail-sesi-number">
                                        <?php echo $no++; ?>
                                    </span>
                                </td>

                                <td>
                                    <span class="detail-sesi-template-code">
                                        <?php echo html_escape($f['kode_template']); ?>
                                    </span>
                                </td>

                                <td>
                                    <span class="detail-sesi-template-name">
                                        <?php echo html_escape($f['nama_template']); ?>
                                    </span>
                                </td>

                                <td>

                                    <?php
                                    $kat = isset($f['kategori_template'])
                                        ? $f['kategori_template']
                                        : (
                                            isset($f['kategori'])
                                                ? $f['kategori']
                                                : (
                                                    isset($sesi['jenis_pengujian'])
                                                        ? $sesi['jenis_pengujian']
                                                        : '-'
                                                )
                                        );
                                    ?>

                                    <span class="detail-sesi-category">
                                        <?php echo html_escape($kat); ?>
                                    </span>

                                </td>

                                <td>

                                    <?php if (!empty($f['data_hasil'])): ?>

                                        <span class="detail-sesi-result detail-sesi-result-done">
                                            <i class="bi bi-check-circle-fill"></i>
                                            Sudah Diisi
                                        </span>

                                    <?php else: ?>

                                        <span class="detail-sesi-result detail-sesi-result-empty">
                                            <i class="bi bi-dash-circle"></i>
                                            Belum Diisi
                                        </span>

                                    <?php endif; ?>

                                </td>

                                <td>
                                    <span class="detail-sesi-conclusion">
                                        <?php echo html_escape($f['kesimpulan'] ?: '-'); ?>
                                    </span>
                                </td>

                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="6" class="detail-sesi-empty">

                                <div class="detail-sesi-empty-icon">
                                    <i class="bi bi-clipboard-x"></i>
                                </div>

                                Belum ada form yang didaftarkan pada sesi ini.

                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>


    <!-- =========================
         MODAL PENOLAKAN
    ========================== -->
    <?php if ($sesi['status'] === 'Menunggu Verifikasi' && $this->Model_Hak_Akses->memiliki_akses($this->session->userdata('role_id'), 'pengujian_verify')): ?>

        <div class="modal fade detail-sesi-modal"
             id="modalTolakSesi"
             tabindex="-1"
             aria-labelledby="modalTolakSesiLabel"
             aria-hidden="true">

            <div class="modal-dialog modal-dialog-centered">

                <div class="modal-content">

                    <form action="<?php echo site_url('pengujian/tolak_sesi/' . $sesi['id']); ?>"
                          method="post">

                        <input type="hidden"
                               name="<?php echo $this->security->get_csrf_token_name(); ?>"
                               value="<?php echo $this->security->get_csrf_hash(); ?>">

                        <div class="modal-header">

                            <h5 class="modal-title" id="modalTolakSesiLabel">
                                <i class="bi bi-exclamation-octagon me-2"></i>
                                Penolakan Sesi Pengujian
                            </h5>

                            <button type="button"
                                    class="btn-close"
                                    data-bs-dismiss="modal"
                                    aria-label="Close"></button>

                        </div>

                        <div class="modal-body">

                            <p class="mb-3">
                                Penolakan akan mengembalikan status sesi pengujian menjadi
                                <strong>"Ditolak"</strong> dan mengirimkannya kembali ke penguji
                                untuk diperbaiki.
                            </p>

                            <div class="mb-0">

                                <label for="alasan_penolakan"
                                       class="form-label">
                                    Alasan Penolakan
                                    <span class="text-danger">*</span>
                                </label>

                                <textarea name="alasan_penolakan"
                                          id="alasan_penolakan"
                                          class="form-control"
                                          rows="4"
                                          placeholder="Tuliskan catatan perbaikan atau alasan penolakan secara jelas..."
                                          required></textarea>

                            </div>

                        </div>

                        <div class="modal-footer">

                            <button type="button"
                                    class="btn btn-secondary"
                                    data-bs-dismiss="modal">
                                Batal
                            </button>

                            <button type="submit"
                                    class="btn btn-danger fw-bold">
                                <i class="bi bi-x-circle me-1"></i>
                                Konfirmasi Penolakan
                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    <?php endif; ?>

</div>