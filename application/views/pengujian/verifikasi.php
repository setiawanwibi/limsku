<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
    /* =========================================================
       VERIFIKASI LAPORAN
       Visual redesign only
       Fungsi PHP / URL / Form / JS tetap dipertahankan
    ========================================================= */

    .verifikasi-page {
        color: #294257;
        font-size: .88rem;
    }

    /* =========================================================
       FLASH MESSAGE
    ========================================================= */

    .verifikasi-page .alert {
        border-radius: 9px;
        font-size: .80rem;
        line-height: 1.55;
        border-width: 1px;
        box-shadow: 0 3px 10px rgba(35, 58, 76, .04);
    }

    /* =========================================================
       HEADER
    ========================================================= */

    .verifikasi-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        margin-bottom: 20px;
    }

    .verifikasi-title {
        margin: 0 0 5px;
        color: #294257;
        font-size: 1.08rem;
        font-weight: 700;
        line-height: 1.35;
    }

    .verifikasi-subtitle {
        margin: 0;
        color: #718491;
        font-size: .76rem;
        line-height: 1.55;
    }

    .verifikasi-header-tools {
        display: flex;
        align-items: center;
        gap: 9px;
        flex-shrink: 0;
    }

    .verifikasi-search {
        position: relative;
        width: 245px;
    }

    .verifikasi-search i {
        position: absolute;
        top: 50%;
        left: 12px;
        transform: translateY(-50%);
        color: #91a1ab;
        font-size: .82rem;
        pointer-events: none;
    }

    .verifikasi-search input {
        width: 100%;
        height: 36px;
        padding: 7px 12px 7px 34px;
        border: 1px solid #dce5e9;
        border-radius: 7px;
        background: #fff;
        color: #526779;
        font-size: .74rem;
        box-shadow: none;
    }

    .verifikasi-search input::placeholder {
        color: #9aa9b2;
    }

    .verifikasi-search input:focus {
        border-color: #a7d3d0;
        box-shadow: 0 0 0 3px rgba(21, 155, 152, .08);
        outline: none;
    }

    .verifikasi-date {
        display: inline-flex;
        align-items: center;
        min-height: 36px;
        padding: 7px 12px;
        border: 1px solid #dfe7eb;
        border-radius: 7px;
        background: #fff;
        color: #6d808d;
        font-size: .72rem;
        font-weight: 600;
        white-space: nowrap;
    }

    /* =========================================================
       MAIN LAYOUT
    ========================================================= */

    .verifikasi-layout {
        align-items: flex-start;
    }

    /* =========================================================
       SIDEBAR ANTREAN
    ========================================================= */

    .verifikasi-sidebar-card {
        overflow: hidden;
        background: #fff;
        border: 1px solid #e0e8ed;
        border-radius: 10px;
        box-shadow: 0 5px 18px rgba(35, 58, 76, .06);
    }

    .verifikasi-sidebar-header {
        padding: 15px 15px 13px;
        background: #fff;
        border-bottom: 1px solid #e9eef1;
    }

    .verifikasi-sidebar-title {
        margin: 0 0 10px;
        color: #294257;
        font-size: .82rem;
        font-weight: 700;
    }

    .verifikasi-filter {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .verifikasi-filter-badge {
        display: inline-flex;
        align-items: center;
        min-height: 25px;
        padding: 4px 9px;
        border: 1px solid #dce5e9;
        border-radius: 6px;
        background: #fff;
        color: #667b89;
        font-size: .66rem;
        font-weight: 600;
    }

    .verifikasi-filter-badge.active {
        background: #eaf7f6;
        border-color: #c9e6e4;
        color: #168f8b;
    }

    .verifikasi-filter-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 17px;
        height: 17px;
        margin-left: 4px;
        padding: 0 4px;
        border-radius: 10px;
        background: #eef2f4;
        color: #748793;
        font-size: .59rem;
        font-weight: 700;
    }

    .verifikasi-queue {
        max-height: 560px;
        overflow-y: auto;
    }

    .verifikasi-queue::-webkit-scrollbar {
        width: 5px;
    }

    .verifikasi-queue::-webkit-scrollbar-thumb {
        background: #d5e0e5;
        border-radius: 10px;
    }

    .verifikasi-queue-item {
        position: relative;
        display: block;
        padding: 13px 14px;
        border: 0;
        border-bottom: 1px solid #edf1f3;
        background: #fff;
        color: inherit;
        text-decoration: none;
        transition: .18s ease;
    }

    .verifikasi-queue-item:hover {
        background: #f8fbfb;
        color: inherit;
    }

    .verifikasi-queue-item.active {
        background: #f2faf9;
        box-shadow: inset 3px 0 0 #159b98;
    }

    .queue-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        margin-bottom: 5px;
    }

    .queue-code {
        color: #687d8b;
        font-family: monospace;
        font-size: .68rem;
        font-weight: 700;
    }

    .queue-time {
        color: #91a0a9;
        font-size: .63rem;
        white-space: nowrap;
    }

    .queue-name {
        margin: 0 0 6px;
        color: #334f61;
        font-size: .76rem;
        font-weight: 700;
        line-height: 1.4;
    }

    .queue-bottom {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
    }

    .queue-type {
        color: #82929d;
        font-size: .65rem;
    }

    .queue-status {
        display: inline-flex;
        align-items: center;
        min-height: 21px;
        padding: 3px 7px;
        border: 1px solid #cfe8e7;
        border-radius: 20px;
        background: #eaf7f6;
        color: #168f8b;
        font-size: .60rem;
        font-weight: 700;
    }

    .queue-empty {
        padding: 30px 15px;
        color: #8999a3;
        text-align: center;
        font-size: .72rem;
        line-height: 1.55;
    }

    .verifikasi-sidebar-footer {
        padding: 10px 13px;
        border-top: 1px solid #e9eef1;
        background: #fff;
        color: #788b97;
        text-align: center;
        font-size: .68rem;
    }

    /* =========================================================
       SAMPLE HEADER
    ========================================================= */

    .verifikasi-sample-card {
        padding: 16px 17px;
        margin-bottom: 15px;
        background: #fff;
        border: 1px solid #e0e8ed;
        border-radius: 10px;
        box-shadow: 0 5px 18px rgba(35, 58, 76, .06);
    }

    .verifikasi-sample-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 15px;
    }

    .sample-code-row {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 7px;
        margin-bottom: 5px;
    }

    .sample-code {
        color: #657b89;
        font-family: monospace;
        font-size: .70rem;
        font-weight: 700;
    }

    .status-menunggu {
        display: inline-flex;
        align-items: center;
        min-height: 22px;
        padding: 3px 8px;
        border: 1px solid #cfe8e7;
        border-radius: 20px;
        background: #eaf7f6;
        color: #168f8b;
        font-size: .60rem;
        font-weight: 700;
    }

    .sample-name {
        margin: 0 0 6px;
        color: #294257;
        font-size: 1.02rem;
        font-weight: 700;
        line-height: 1.4;
    }

    .sample-meta {
        margin: 0;
        color: #7b8c97;
        font-size: .68rem;
        line-height: 1.55;
    }

    .sample-meta strong {
        color: #566e7d;
    }

    .btn-lampiran {
        min-height: 34px;
        padding: 7px 12px;
        border: 1px solid #d9e3e7;
        border-radius: 7px;
        background: #fff;
        color: #617684;
        font-size: .68rem;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        white-space: nowrap;
        transition: .18s ease;
    }

    .btn-lampiran:hover {
        border-color: #bddbd9;
        background: #f4faf9;
        color: #168f8b;
    }

    /* =========================================================
       METRIC CARDS
    ========================================================= */

    .verifikasi-metrics {
        margin-bottom: 15px;
    }

    .verifikasi-metric {
        min-height: 89px;
        padding: 13px;
        background: #fff;
        border: 1px solid #e0e8ed;
        border-radius: 9px;
        box-shadow: 0 3px 12px rgba(35, 58, 76, .04);
    }

    .verifikasi-metric.warning {
        background: #fffdf5;
        border-color: #f0e2b7;
    }

    .metric-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 27px;
        height: 27px;
        margin-bottom: 7px;
        border-radius: 6px;
        background: #e6f5f3;
        color: #159b98;
        font-size: .85rem;
    }

    .metric-icon.warning {
        background: #fff3cd;
        color: #a67c00;
    }

    .metric-label {
        display: block;
        margin-bottom: 2px;
        color: #7a8d99;
        font-size: .61rem;
        line-height: 1.3;
    }

    .metric-value {
        color: #40596a;
        font-size: .75rem;
        font-weight: 700;
    }

    /* =========================================================
       REPORT CARD
    ========================================================= */

    .verifikasi-report-card {
        overflow: hidden;
        background: #fff;
        border: 1px solid #e0e8ed;
        border-radius: 10px;
        box-shadow: 0 5px 18px rgba(35, 58, 76, .06);
    }

    .report-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 15px 16px;
        border-bottom: 1px solid #e5ebee;
        background: #fff;
    }

    .report-title {
        margin: 0 0 3px;
        color: #294257;
        font-size: .84rem;
        font-weight: 700;
    }

    .report-session {
        color: #7d8e99;
        font-family: monospace;
        font-size: .64rem;
    }

    .btn-preview {
        min-height: 32px;
        padding: 6px 11px;
        border: 1px solid #dbe4e8;
        border-radius: 7px;
        background: #f8fafb;
        color: #617684;
        font-size: .66rem;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        white-space: nowrap;
    }

    .btn-preview:hover {
        background: #f1f7f7;
        color: #168f8b;
        border-color: #c7e0df;
    }

    .report-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 10px 15px;
        background: #f8fafb;
        border-bottom: 1px solid #e6ecef;
        color: #738591;
        font-size: .63rem;
    }

    .report-meta strong {
        color: #526b7a;
        font-weight: 700;
    }

    .report-table {
        margin: 0;
        color: #526a79;
        font-size: .72rem;
    }

    .report-table thead th {
        padding: 10px 12px;
        border-bottom: 1px solid #dfe7eb;
        background: #f8fafb;
        color: #738591;
        font-size: .61rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .02em;
        white-space: nowrap;
    }

    .report-table tbody td {
        padding: 11px 12px;
        border-color: #edf1f3;
        vertical-align: middle;
    }

    .report-form-name {
        display: block;
        margin-bottom: 2px;
        color: #3c5768;
        font-size: .73rem;
        font-weight: 700;
        line-height: 1.4;
    }

    .report-form-code {
        color: #8a9aa4;
        font-family: monospace;
        font-size: .61rem;
    }

    .report-result {
        color: #536a78;
        font-size: .68rem;
        font-weight: 600;
        line-height: 1.4;
    }

    .report-requirement {
        color: #8797a0;
        font-size: .66rem;
    }

    .report-conclusion {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 8px;
        border-radius: 20px;
        font-size: .61rem;
        font-weight: 700;
        white-space: nowrap;
    }

    /* =========================================================
       PENGUJI CONCLUSION
    ========================================================= */

    .penguji-conclusion {
        padding: 13px 15px;
        border-top: 1px solid #f0d7d7;
        background: #fff7f7;
        color: #a34c4c;
        font-size: .70rem;
        line-height: 1.55;
    }

    .penguji-conclusion-title {
        display: block;
        margin-bottom: 4px;
        color: #8f4a4a;
        font-family: monospace;
        font-size: .61rem;
        font-weight: 700;
        letter-spacing: .02em;
    }

    /* =========================================================
       RIGHT DECISION PANEL
    ========================================================= */

    .keputusan-card {
        position: sticky;
        top: 18px;
        padding: 16px;
        background: #fff;
        border: 1px solid #e0e8ed;
        border-radius: 10px;
        box-shadow: 0 5px 18px rgba(35, 58, 76, .06);
    }

    .keputusan-title {
        margin: 0 0 4px;
        color: #294257;
        font-size: .84rem;
        font-weight: 700;
    }

    .keputusan-subtitle {
        display: block;
        margin-bottom: 15px;
        color: #82929d;
        font-size: .68rem;
        line-height: 1.5;
    }

    .keputusan-option {
        position: relative;
        margin-bottom: 9px;
    }

    .keputusan-option input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .keputusan-option label {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        min-height: 64px;
        padding: 11px 12px;
        border: 1px solid #dfe7eb;
        border-radius: 8px;
        background: #fff;
        cursor: pointer;
        transition: .18s ease;
    }

    .keputusan-option label:hover {
        background: #fafcfc;
        border-color: #cfdcdf;
    }

    .keputusan-option input:checked + label.terima {
        border-color: #a8d8c0;
        background: #f1faf5;
        box-shadow: 0 0 0 2px rgba(40, 132, 71, .06);
    }

    .keputusan-option input:checked + label.tolak {
        border-color: #edb8b8;
        background: #fff6f6;
        box-shadow: 0 0 0 2px rgba(217, 83, 79, .05);
    }

    .keputusan-option-title {
        display: block;
        margin-bottom: 3px;
        color: #40596a;
        font-size: .72rem;
        font-weight: 700;
    }

    .keputusan-option-title.danger {
        color: #a64e4e;
    }

    .keputusan-option-desc {
        color: #84949e;
        font-size: .62rem;
        line-height: 1.4;
    }

    .keputusan-option-icon {
        flex-shrink: 0;
        font-size: 1rem;
    }

    .keputusan-label {
        display: block;
        margin-bottom: 7px;
        color: #526979;
        font-size: .70rem;
        font-weight: 700;
    }

    .keputusan-textarea {
        min-height: 92px;
        padding: 9px 10px;
        border: 1px solid #dce5e9;
        border-radius: 7px;
        resize: vertical;
        color: #526a79;
        font-size: .70rem;
        box-shadow: none;
    }

    .keputusan-textarea::placeholder {
        color: #a1afb8;
    }

    .keputusan-textarea:focus {
        border-color: #a7d3d0;
        box-shadow: 0 0 0 3px rgba(21, 155, 152, .08);
    }

    .keputusan-help {
        display: block;
        margin-top: 6px;
        color: #8a9aa4;
        font-size: .61rem;
        line-height: 1.4;
    }

    .keputusan-submit {
        width: 100%;
        min-height: 39px;
        margin-top: 4px;
        border: 1px solid #159b98;
        border-radius: 7px;
        background: #159b98;
        color: #fff;
        font-size: .73rem;
        font-weight: 700;
        box-shadow: 0 3px 8px rgba(21, 155, 152, .13);
        transition: .18s ease;
    }

    .keputusan-submit:hover {
        border-color: #118b88;
        background: #118b88;
        color: #fff;
        transform: translateY(-1px);
    }

    /* =========================================================
       EMPTY STATE
    ========================================================= */

    .verifikasi-empty {
        min-height: 260px;
        padding: 45px 25px;
        background: #fff;
        border: 1px solid #e0e8ed;
        border-radius: 10px;
        box-shadow: 0 5px 18px rgba(35, 58, 76, .06);
        text-align: center;
        color: #82929d;
    }

    .verifikasi-empty-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 54px;
        height: 54px;
        margin: 0 auto 13px;
        border-radius: 12px;
        background: #f1f7f7;
        color: #159b98;
        font-size: 1.5rem;
    }

    .verifikasi-empty h5 {
        margin-bottom: 5px;
        color: #526a79;
        font-size: .86rem;
        font-weight: 700;
    }

    .verifikasi-empty p {
        margin: 0;
        color: #8a9aa4;
        font-size: .70rem;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 1199.98px) {
        .verifikasi-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .verifikasi-header-tools {
            width: 100%;
        }

        .verifikasi-search {
            flex: 1;
            width: auto;
        }

        .keputusan-card {
            position: static;
        }
    }

    @media (max-width: 991.98px) {
        .verifikasi-layout {
            gap: 15px;
        }

        .verifikasi-queue {
            max-height: 350px;
        }

        .report-meta {
            align-items: flex-start;
            flex-direction: column;
            gap: 4px;
        }
    }

    @media (max-width: 575.98px) {
        .verifikasi-header-tools {
            align-items: stretch;
            flex-direction: column;
        }

        .verifikasi-search {
            width: 100%;
        }

        .verifikasi-date {
            justify-content: center;
        }

        .verifikasi-sample-top {
            flex-direction: column;
        }

        .btn-lampiran {
            width: 100%;
        }

        .report-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .btn-preview {
            width: 100%;
        }

        .report-table {
            min-width: 650px;
        }
    }
</style>


<div class="verifikasi-page">

    <!-- Flash Messages -->
    <?php if ($this->session->flashdata('pesan_sukses')): ?>
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
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


    <?php if ($this->session->flashdata('pesan_gagal')): ?>
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <?php echo html_escape($this->session->flashdata('pesan_gagal')); ?>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close"
            ></button>
        </div>
    <?php endif; ?>


    <!-- =====================================================
         HEADER WORKSPACE PENYELIA
    ====================================================== -->

    <div class="verifikasi-header">

        <div>
            <h5 class="verifikasi-title">
                Verifikasi Laporan Hasil Pengujian
            </h5>

            <p class="verifikasi-subtitle">
                Periksa kelengkapan metode, hasil uji, dan kesimpulan sebelum diteruskan ke MT.
            </p>
        </div>


        <div class="verifikasi-header-tools">

            <div class="verifikasi-search">
                <i class="bi bi-search"></i>

                <input
                    type="text"
                    class="form-control"
                    placeholder="Cari nomor sampel..."
                >
            </div>

            <span class="verifikasi-date">
                <i class="bi bi-calendar3 me-2"></i>
                <?php echo date('d M Y'); ?>
            </span>

        </div>

    </div>


    <!-- =====================================================
         MAIN CONTENT
    ====================================================== -->

    <div class="row g-3 verifikasi-layout">

        <!-- =================================================
             LEFT SIDEBAR
        ================================================== -->

        <div class="col-lg-3">

            <div class="verifikasi-sidebar-card">

                <div class="verifikasi-sidebar-header">

                    <h6 class="verifikasi-sidebar-title">
                        Menunggu Verifikasi
                    </h6>

                    <div class="verifikasi-filter">

                        <span class="verifikasi-filter-badge active">
                            Semua
                        </span>

                        <span class="verifikasi-filter-badge">
                            Revisi
                            <span class="verifikasi-filter-count">0</span>
                        </span>

                    </div>

                </div>


                <div class="verifikasi-queue">

                    <?php if (!empty($antrean_verifikasi)): ?>

                        <?php foreach ($antrean_verifikasi as $item): ?>

                            <?php
                                $is_active = ($selected_item && $selected_item['id'] == $item['id']);

                                $kode = $item['kode_sampel_manual']
                                    ?: ($item['no_sampel']
                                        ? 'NO-' . $item['no_sampel']
                                        : 'SMP-' . $item['sample_id']);
                            ?>


                            <a
                                href="<?php echo site_url('pengujian/verifikasi?session_id=' . $item['id']); ?>"
                                class="verifikasi-queue-item <?php echo ($is_active ? 'active' : ''); ?>"
                            >

                                <div class="queue-top">

                                    <span class="queue-code">
                                        <?php echo html_escape($kode); ?>
                                    </span>

                                    <small class="queue-time">
                                        <?php
                                            echo html_escape(
                                                $item['waktu_selesai']
                                                    ? date('H.i', strtotime($item['waktu_selesai']))
                                                    : '-'
                                            );
                                        ?>
                                    </small>

                                </div>


                                <h6 class="queue-name">
                                    <?php echo html_escape($item['nama_sampel']); ?>
                                </h6>


                                <div class="queue-bottom">

                                    <small class="queue-type">
                                        <?php echo html_escape($item['jenis_pengujian'] ?: 'Pengujian'); ?>
                                    </small>

                                    <span class="queue-status">
                                        Baru
                                    </span>

                                </div>

                            </a>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <div class="queue-empty">
                            <i class="bi bi-inbox d-block mb-2 fs-5"></i>
                            Tidak ada antrean laporan yang menunggu verifikasi.
                        </div>

                    <?php endif; ?>

                </div>


                <div class="verifikasi-sidebar-footer">
                    <strong>Total:</strong>
                    <?php echo count($antrean_verifikasi); ?>
                    Laporan
                </div>

            </div>

        </div>


        <!-- =================================================
             MAIN & RIGHT PANEL
        ================================================== -->

        <?php if ($selected_item): ?>

            <!-- =============================================
                 MAIN REPORT
            ============================================== -->

            <div class="col-lg-6">

                <!-- Sample Header -->
                <div class="verifikasi-sample-card">

                    <div class="verifikasi-sample-top">

                        <div>

                            <div class="sample-code-row">

                                <span class="sample-code">
                                    <?php
                                        echo html_escape(
                                            $selected_item['kode_sampel_manual']
                                            ?: 'SMP-' . $selected_item['sample_id']
                                        );
                                    ?>
                                </span>

                                <span class="status-menunggu">
                                    <i class="bi bi-clock-history me-1"></i>
                                    MENUNGGU VERIFIKASI
                                </span>

                            </div>


                            <h4 class="sample-name">
                                <?php echo html_escape($selected_item['nama_sampel']); ?>
                            </h4>


                            <p class="sample-meta">

                                Penguji:
                                <strong>
                                    <?php echo html_escape($selected_item['nama_penguji'] ?: 'Analis'); ?>
                                </strong>

                                <span class="mx-1">·</span>

                                Selesai:
                                <?php
                                    echo html_escape(
                                        $selected_item['waktu_selesai']
                                            ? date('d M Y, H.i', strtotime($selected_item['waktu_selesai']))
                                            : '-'
                                    );
                                ?>

                            </p>

                        </div>


                        <div>

                            <a
                                href="<?php echo site_url('pengujian/detail_sesi/' . $selected_item['id']); ?>"
                                class="btn-lampiran"
                            >
                                <i class="bi bi-folder2-open"></i>
                                Buka Lampiran
                            </a>

                        </div>

                    </div>

                </div>


                <!-- =========================================
                     STATUS CHECK CARDS
                ========================================== -->

                <div class="row g-2 verifikasi-metrics">

                    <div class="col-6 col-md-3">

                        <div class="verifikasi-metric">

                            <div class="metric-icon">
                                <i class="bi bi-box-seam"></i>
                            </div>

                            <small class="metric-label">
                                Kelengkapan Sampel
                            </small>

                            <strong class="metric-value">
                                Lengkap
                            </strong>

                        </div>

                    </div>


                    <div class="col-6 col-md-3">

                        <div class="verifikasi-metric">

                            <div class="metric-icon">
                                <i class="bi bi-check-square"></i>
                            </div>

                            <small class="metric-label">
                                Metode &amp; IK
                            </small>

                            <strong class="metric-value">
                                Sesuai
                            </strong>

                        </div>

                    </div>


                    <div class="col-6 col-md-3">

                        <div class="verifikasi-metric">

                            <div class="metric-icon">
                                <i class="bi bi-paperclip"></i>
                            </div>

                            <small class="metric-label">
                                Worksheet
                            </small>

                            <strong class="metric-value">
                                <?php echo count($selected_forms); ?> Form
                            </strong>

                        </div>

                    </div>


                    <div class="col-6 col-md-3">

                        <div class="verifikasi-metric warning">

                            <div class="metric-icon warning">
                                <i class="bi bi-exclamation-circle"></i>
                            </div>

                            <small class="metric-label">
                                Kesimpulan
                            </small>

                            <strong class="metric-value">
                                Perlu ditinjau
                            </strong>

                        </div>

                    </div>

                </div>


                <!-- =========================================
                     RINGKASAN LAPORAN HASIL UJI
                ========================================== -->

                <div class="verifikasi-report-card">

                    <div class="report-header">

                        <div>

                            <h6 class="report-title">
                                Ringkasan Laporan Hasil Uji
                            </h6>

                            <small class="report-session">
                                Sesi ID: #<?php echo $selected_item['id']; ?>
                                (<?php echo html_escape($selected_item['jenis_pengujian']); ?>)
                            </small>

                        </div>


                        <div>

                            <a
                                href="<?php echo site_url('pengujian/detail_sesi/' . $selected_item['id']); ?>"
                                class="btn-preview"
                            >
                                <i class="bi bi-file-earmark-pdf"></i>
                                Preview PDF
                            </a>

                        </div>

                    </div>


                    <!-- Report Metadata -->
                    <div class="report-meta">

                        <span>
                            <strong>METODE:</strong>
                            <?php
                                echo html_escape(
                                    $selected_item['nama_metode']
                                    ?: 'Standar Pengujian BBPOM'
                                );
                            ?>
                        </span>

                        <span>
                            <strong>JENIS:</strong>
                            <?php echo html_escape($selected_item['jenis_pengujian']); ?>
                        </span>

                        <span>
                            <strong>TANGGAL UJI:</strong>
                            <?php
                                echo html_escape(
                                    $selected_item['waktu_selesai']
                                        ? date('d M Y', strtotime($selected_item['waktu_selesai']))
                                        : date('d M Y')
                                );
                            ?>
                        </span>

                    </div>


                    <!-- Report Table -->
                    <div class="table-responsive">

                        <table class="table table-hover align-middle report-table">

                            <thead>

                                <tr>

                                    <th class="ps-3">
                                        Parameter / Form
                                    </th>

                                    <th>
                                        Hasil
                                    </th>

                                    <th>
                                        Persyaratan
                                    </th>

                                    <th class="pe-3">
                                        Kesimpulan
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                                <?php if (!empty($selected_forms)): ?>

                                    <?php foreach ($selected_forms as $form): ?>

                                        <tr>

                                            <td class="ps-3">

                                                <strong class="report-form-name">
                                                    <?php echo html_escape($form['nama_template']); ?>
                                                </strong>

                                                <small class="report-form-code">
                                                    <?php echo html_escape($form['kode_template']); ?>
                                                </small>

                                            </td>


                                            <td>

                                                <small class="report-result">

                                                    <?php

                                                        $dh = !empty($form['data_hasil'])
                                                            ? json_decode($form['data_hasil'], true)
                                                            : array();

                                                        if (!empty($dh) && is_array($dh)) {

                                                            echo html_escape(
                                                                implode(', ', array_slice($dh, 0, 2))
                                                            );

                                                        } else {

                                                            echo 'Tercatat';

                                                        }

                                                    ?>

                                                </small>

                                            </td>


                                            <td>

                                                <small class="report-requirement">
                                                    Sesuai Syarat
                                                </small>

                                            </td>


                                            <td class="pe-3">

                                                <?php

                                                    $kes = $form['kesimpulan'];

                                                    $b_cls = 'bg-secondary';

                                                    if (
                                                        stripos($kes, 'memenuhi') !== false
                                                        &&
                                                        stripos($kes, 'tidak') === false
                                                    ) {

                                                        $b_cls =
                                                            'bg-success-subtle text-success border border-success-subtle';

                                                    } elseif (
                                                        stripos($kes, 'tidak memenuhi') !== false
                                                    ) {

                                                        $b_cls =
                                                            'bg-danger-subtle text-danger border border-danger-subtle';

                                                    }

                                                ?>


                                                <span
                                                    class="report-conclusion <?php echo $b_cls; ?>"
                                                >
                                                    <span>●</span>
                                                    <?php echo html_escape($kes); ?>
                                                </span>

                                            </td>

                                        </tr>

                                    <?php endforeach; ?>

                                <?php else: ?>

                                    <tr>

                                        <td
                                            colspan="4"
                                            class="text-center py-4"
                                        >

                                            <span class="text-muted" style="font-size:.70rem;">
                                                <i class="bi bi-inbox me-1"></i>
                                                Belum ada form pengujian terdaftar.
                                            </span>

                                        </td>

                                    </tr>

                                <?php endif; ?>

                            </tbody>

                        </table>

                    </div>


                    <!-- Kesimpulan Penguji -->
                    <div class="penguji-conclusion">

                        <strong class="penguji-conclusion-title">
                            KESIMPULAN PENGUJI
                        </strong>

                        <span>
                            <?php
                                echo html_escape(
                                    $selected_item['kesimpulan']
                                    ?: 'Pengujian telah diselesaikan oleh penguji dan siap diverifikasi.'
                                );
                            ?>
                        </span>

                    </div>

                </div>

            </div>


            <!-- =============================================
                 RIGHT SIDEBAR
            ============================================== -->

            <div class="col-lg-3">

                <div class="keputusan-card">

                    <h6 class="keputusan-title">
                        Keputusan Penyelia
                    </h6>

                    <small class="keputusan-subtitle">
                        Pilih keputusan setelah seluruh data diperiksa.
                    </small>


                    <!-- Form Decision Submission -->
                    <form
                        id="formKeputusanPenyelia"
                        action="<?php echo site_url('pengujian/verifikasi_sesi/' . $selected_item['id']); ?>"
                        method="post"
                    >

                        <input
                            type="hidden"
                            name="<?php echo $this->security->get_csrf_token_name(); ?>"
                            value="<?php echo $this->security->get_csrf_hash(); ?>"
                        >


                        <!-- Terima -->
                        <div class="keputusan-option">

                            <input
                                class="btn-check"
                                type="radio"
                                name="keputusan"
                                id="keputusanTerima"
                                value="terima"
                                checked
                                onchange="toggleKeputusan('terima')"
                            >

                            <label
                                class="terima"
                                for="keputusanTerima"
                            >

                                <div>

                                    <strong class="keputusan-option-title">
                                        Terima laporan
                                    </strong>

                                    <small class="keputusan-option-desc">
                                        Kirim ke MT untuk approval
                                    </small>

                                </div>

                                <i class="bi bi-check-circle-fill text-success keputusan-option-icon"></i>

                            </label>

                        </div>


                        <!-- Tolak -->
                        <div class="keputusan-option">

                            <input
                                class="btn-check"
                                type="radio"
                                name="keputusan"
                                id="keputusanTolak"
                                value="tolak"
                                onchange="toggleKeputusan('tolak')"
                            >

                            <label
                                class="tolak"
                                for="keputusanTolak"
                            >

                                <div>

                                    <strong class="keputusan-option-title danger">
                                        Tolak &amp; kembalikan
                                    </strong>

                                    <small class="keputusan-option-desc">
                                        Kembali ke Penguji untuk revisi
                                    </small>

                                </div>

                                <i class="bi bi-x-circle-fill text-danger keputusan-option-icon"></i>

                            </label>

                        </div>


                        <!-- Comment -->
                        <div class="mb-3">

                            <label
                                for="alasan_penolakan"
                                class="keputusan-label"
                            >
                                Komentar
                                <span
                                    id="reqKomentar"
                                    class="text-danger d-none"
                                >*</span>
                            </label>


                            <textarea
                                name="alasan_penolakan"
                                id="alasan_penolakan"
                                class="form-control keputusan-textarea"
                                rows="4"
                                placeholder="Tuliskan catatan atau masukan untuk penguji..."
                            ></textarea>


                            <small
                                id="textHelpKomentar"
                                class="keputusan-help"
                            >
                                Komentar wajib jika memilih keputusan tolak.
                            </small>

                        </div>


                        <!-- Submit -->
                        <button
                            type="submit"
                            id="btnSubmitDecision"
                            class="keputusan-submit"
                        >
                            <i class="bi bi-check2-circle me-1"></i>
                            Terima &amp; Kirim ke MT
                        </button>

                    </form>

                </div>

            </div>


        <?php else: ?>

            <!-- =============================================
                 EMPTY STATE
            ============================================== -->

            <div class="col-lg-9">

                <div class="verifikasi-empty">

                    <div class="verifikasi-empty-icon">
                        <i class="bi bi-inbox"></i>
                    </div>

                    <h5>
                        Tidak ada laporan pengujian yang dipilih.
                    </h5>

                    <p>
                        Pilih salah satu laporan pada daftar antrean untuk melakukan verifikasi.
                    </p>

                </div>

            </div>

        <?php endif; ?>

    </div>

</div>


<script>
function toggleKeputusan(type) {

    const form = document.getElementById('formKeputusanPenyelia');
    const reqKomentar = document.getElementById('reqKomentar');
    const textHelpKomentar = document.getElementById('textHelpKomentar');
    const btnSubmit = document.getElementById('btnSubmitDecision');
    const txtArea = document.getElementById('alasan_penolakan');

    if (type === 'tolak') {

        form.action = '<?php echo site_url('pengujian/tolak_sesi/' . ($selected_item ? $selected_item['id'] : 0)); ?>';

        reqKomentar.classList.remove('d-none');

        textHelpKomentar.classList.add('text-danger');
        textHelpKomentar.classList.remove('text-muted');

        btnSubmit.className = 'keputusan-submit btn-danger';

        btnSubmit.innerHTML =
            '<i class="bi bi-arrow-return-left me-1"></i> Tolak &amp; Kirim Revisi';

        txtArea.required = true;

    } else {

        form.action = '<?php echo site_url('pengujian/verifikasi_sesi/' . ($selected_item ? $selected_item['id'] : 0)); ?>';

        reqKomentar.classList.add('d-none');

        textHelpKomentar.classList.remove('text-danger');
        textHelpKomentar.classList.add('text-muted');

        btnSubmit.className = 'keputusan-submit';

        btnSubmit.innerHTML =
            '<i class="bi bi-check2-circle me-1"></i> Terima &amp; Kirim ke MT';

        txtArea.required = false;

    }
}
</script>

<style>
    /* Warna tombol submit saat keputusan = tolak */
    .keputusan-submit.btn-danger {
        border-color: #d9534f;
        background: #d9534f;
        color: #fff;
        box-shadow: 0 3px 8px rgba(217, 83, 79, .13);
    }

    .keputusan-submit.btn-danger:hover {
        border-color: #c74743;
        background: #c74743;
        color: #fff;
    }
</style>