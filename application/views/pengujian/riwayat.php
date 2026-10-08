<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
    /* =========================================================
       RIWAYAT HASIL UJI
       Tampilan saja - fungsi PHP tetap dipertahankan
       ========================================================= */

    .riwayat-page {
        width: 100%;
        color: #294257;
        font-size: 0.92rem;
    }

    /* =========================
       ALERT
       ========================= */

    .riwayat-page .alert {
        border-radius: 8px;
        font-size: 0.82rem;
        line-height: 1.5;
    }

    /* =========================
       PAGE HEADER
       ========================= */

    .riwayat-header {
        margin-bottom: 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
    }

    .riwayat-title {
        margin: 0 0 4px;
        color: #294257;
        font-size: 1.05rem;
        font-weight: 700;
    }

    .riwayat-description {
        margin: 0;
        color: #7a8c99;
        font-size: 0.78rem;
        line-height: 1.5;
    }

    .riwayat-btn-antrean {
        min-height: 39px;
        padding: 0 15px;
        border: 1px solid #159b98;
        border-radius: 7px;
        background: #159b98;
        color: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        font-size: 0.77rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .riwayat-btn-antrean:hover {
        background: #128986;
        border-color: #128986;
        color: #fff;
    }

    /* =========================
       FILTER CARD
       ========================= */

    .riwayat-filter-card {
        margin-bottom: 14px;
        border: 1px solid #e0e8ed;
        border-radius: 9px;
        background: #fff;
        box-shadow: 0 3px 12px rgba(35, 58, 76, 0.05);
    }

    .riwayat-filter-body {
        padding: 13px 16px;
    }

    .riwayat-filter {
        display: flex;
        align-items: center;
        gap: 9px;
        flex-wrap: wrap;
    }

    .riwayat-filter-label {
        margin-right: 2px;
        color: #718493;
        font-size: 0.74rem;
        font-weight: 700;
    }

    .riwayat-filter-select {
        min-width: 145px;
        height: 35px;
        padding: 5px 30px 5px 10px;
        border: 1px solid #dfe7eb;
        border-radius: 7px;
        background-color: #f9fbfc;
        color: #526a79;
        font-size: 0.75rem;
        box-shadow: none;
    }

    .riwayat-filter-select:focus {
        border-color: #9fcfcd;
        background-color: #fff;
        box-shadow: 0 0 0 3px rgba(21, 155, 152, 0.08);
    }

    .riwayat-total {
        margin-left: auto;
        color: #7a8c99;
        font-size: 0.74rem;
    }

    .riwayat-total strong {
        color: #294257;
    }

    /* =========================
       MAIN TABLE CARD
       ========================= */

    .riwayat-table-card {
        width: 100%;
        border: 1px solid #e0e8ed;
        border-radius: 10px;
        background: #fff;
        box-shadow: 0 5px 18px rgba(35, 58, 76, 0.06);
        overflow: hidden;
    }

    .riwayat-table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    #tabel-riwayat {
        width: 100% !important;
        margin: 0 !important;
        border-collapse: separate;
        border-spacing: 0;
    }

    #tabel-riwayat thead th {
        padding: 13px 10px;
        border-bottom: 1px solid #dfe7eb;
        background: #f7fafb;
        color: #627787;
        font-size: 0.70rem;
        font-weight: 700;
        white-space: nowrap;
        vertical-align: middle;
    }

    #tabel-riwayat thead th:first-child {
        padding-left: 15px;
    }

    #tabel-riwayat tbody td {
        padding: 12px 10px;
        border-bottom: 1px solid #edf1f3;
        color: #526a79;
        font-size: 0.75rem;
        vertical-align: middle;
    }

    #tabel-riwayat tbody tr:last-child td {
        border-bottom: 0;
    }

    #tabel-riwayat tbody tr:hover td {
        background: #fbfdfd;
    }

    #tabel-riwayat tbody td:first-child {
        padding-left: 15px;
    }

    /* =========================
       SAMPLE CODE
       ========================= */

    .riwayat-kode {
        color: #168d8a;
        font-family: monospace;
        font-size: 0.74rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .riwayat-nama {
        display: block;
        max-width: 190px;
        margin-bottom: 3px;
        color: #294257;
        font-size: 0.76rem;
        font-weight: 700;
        line-height: 1.4;
    }

    .riwayat-subtext {
        display: block;
        color: #8a9aa5;
        font-size: 0.67rem;
    }

    .riwayat-person {
        color: #526a79;
        font-size: 0.73rem;
        white-space: nowrap;
    }

    /* =========================
       JENIS PENGUJIAN
       ========================= */

    .riwayat-jenis {
        display: inline-flex;
        align-items: center;
        min-height: 25px;
        padding: 4px 9px;
        border: 1px solid #dfe7eb;
        border-radius: 20px;
        background: #f7fafb;
        color: #526a79;
        font-size: 0.66rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .riwayat-jenis-legacy {
        color: #8796a0;
        font-weight: 600;
    }

    /* =========================
       KESIMPULAN
       ========================= */

    .riwayat-kesimpulan {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        max-width: 145px;
        min-height: 25px;
        padding: 4px 9px;
        border-radius: 20px;
        font-size: 0.65rem !important;
        font-weight: 700;
        line-height: 1.25;
        text-align: center;
    }

    .riwayat-kesimpulan.success {
        border: 1px solid #cdebd4;
        background: #e8f7eb;
        color: #268447;
    }

    .riwayat-kesimpulan.danger {
        border: 1px solid #f0cece;
        background: #fff0f0;
        color: #b34b4b;
    }

    .riwayat-kesimpulan.light {
        border: 1px solid #e0e7eb;
        background: #f7f9fa;
        color: #7a8c99;
    }

    .riwayat-kesimpulan.secondary {
        border: 1px solid #dfe5e8;
        background: #eef2f4;
        color: #637783;
    }

    /* =========================
       STATUS
       ========================= */

    .riwayat-page .badge-status {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 25px;
        padding: 4px 9px;
        border-radius: 20px;
        font-size: 0.64rem;
        font-weight: 700;
        line-height: 1.2;
        white-space: nowrap;
    }

    .riwayat-page .badge-menunggu-pengujian {
        border: 1px solid #d9e5eb;
        background: #f1f6f8;
        color: #657d8b;
    }

    .riwayat-page .badge-sedang-diuji {
        border: 1px solid #cde5f4;
        background: #edf8fd;
        color: #31789c;
    }

    .riwayat-page .badge-menunggu-verifikasi {
        border: 1px solid #eadbb6;
        background: #fff8e7;
        color: #96701e;
    }

    .riwayat-page .badge-menunggu-approval {
        border: 1px solid #ddd5ed;
        background: #f7f2ff;
        color: #72589a;
    }

    .riwayat-page .badge-approved-final {
        border: 1px solid #cdebd4;
        background: #e8f7eb;
        color: #268447;
    }

    .riwayat-page .badge-ditolak {
        border: 1px solid #f0cece;
        background: #fff0f0;
        color: #b34b4b;
    }

    /* =========================
       ACTION BUTTON
       ========================= */

    .riwayat-actions {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        flex-wrap: wrap;
    }

    .riwayat-action-btn {
        min-height: 30px;
        padding: 4px 9px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.66rem;
        font-weight: 700;
        text-decoration: none;
        white-space: nowrap;
    }

    .riwayat-action-detail {
        border: 1px solid #b9dedd;
        background: #fff;
        color: #168d8a;
    }

    .riwayat-action-detail:hover {
        background: #edf9f8;
        border-color: #159b98;
        color: #128986;
    }

    .riwayat-action-pdf {
        border: 1px solid #efc5c5;
        background: #fff;
        color: #c45555;
    }

    .riwayat-action-pdf:hover {
        background: #fff4f4;
        border-color: #dc7777;
        color: #ad4141;
    }

    .riwayat-action-revisi {
        border: 1px solid #eed39c;
        background: #fff8e8;
        color: #99701c;
    }

    .riwayat-action-revisi:hover {
        background: #fff2d2;
        border-color: #e3bd67;
        color: #805c14;
    }

    /* =========================
       DATATABLES
       ========================= */

    #tabel-riwayat_wrapper {
        padding: 16px;
    }

    #tabel-riwayat_wrapper > .d-flex {
        min-height: 35px;
    }

    #tabel-riwayat_wrapper .dataTables_length,
    #tabel-riwayat_wrapper .dataTables_filter {
        color: #718493;
        font-size: 0.72rem;
    }

    #tabel-riwayat_wrapper .dataTables_length label,
    #tabel-riwayat_wrapper .dataTables_filter label {
        display: flex;
        align-items: center;
        gap: 7px;
        margin: 0;
        color: #718493;
        font-size: 0.72rem;
        font-weight: 600;
    }

    #tabel-riwayat_wrapper .dataTables_length select {
        height: 33px;
        min-width: 65px;
        padding: 4px 25px 4px 8px;
        border: 1px solid #dfe7eb;
        border-radius: 6px;
        background-color: #f9fbfc;
        color: #526a79;
        font-size: 0.72rem;
        box-shadow: none;
    }

    #tabel-riwayat_wrapper .dataTables_filter input {
        width: 190px;
        height: 34px;
        margin-left: 0;
        padding: 5px 10px;
        border: 1px solid #dfe7eb;
        border-radius: 7px;
        background: #f9fbfc;
        color: #526a79;
        font-size: 0.73rem;
        box-shadow: none;
    }

    #tabel-riwayat_wrapper .dataTables_filter input:focus {
        border-color: #9fcfcd;
        background: #fff;
        outline: none;
        box-shadow: 0 0 0 3px rgba(21, 155, 152, 0.08);
    }

    #tabel-riwayat_wrapper .dataTables_info {
        padding-top: 0;
        color: #7a8c99;
        font-size: 0.70rem;
    }

    /* =========================
       PAGINATION
       ========================= */

    #tabel-riwayat_wrapper .dataTables_paginate {
        padding-top: 0 !important;
        margin: 0 !important;
        border: 0 !important;
        background: transparent !important;
        box-shadow: none !important;
    }

    /* Container pagination */
    #tabel-riwayat_wrapper .dataTables_paginate .pagination {
        display: flex !important;
        align-items: center !important;
        gap: 4px !important;
        margin: 0 !important;
        padding: 0 !important;
        border: 0 !important;
        background: transparent !important;
        box-shadow: none !important;
        list-style: none !important;
    }

    /* Wrapper tombol: transparan, BUKAN tombol */
    #tabel-riwayat_wrapper .dataTables_paginate .page-item,
    #tabel-riwayat_wrapper .dataTables_paginate .paginate_button {
        display: block !important;
        width: auto !important;
        min-width: 0 !important;
        height: auto !important;

        margin: 0 !important;
        padding: 0 !important;

        border: 0 !important;
        border-radius: 0 !important;

        background: transparent !important;
        background-image: none !important;

        box-shadow: none !important;
        outline: none !important;

        color: inherit !important;
    }

    /* HANYA page-link yang menjadi tombol */
    #tabel-riwayat_wrapper .dataTables_paginate
    .paginate_button .page-link {

        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;

        min-width: 31px !important;
        width: 31px !important;
        height: 31px !important;

        margin: 0 !important;
        padding: 5px 8px !important;

        border: 1px solid #dfe7eb !important;
        border-radius: 6px !important;

        background: #fff !important;
        background-image: none !important;

        color: #657887 !important;

        font-size: 0.70rem !important;
        font-weight: 500 !important;
        line-height: 1 !important;

        box-shadow: none !important;
        outline: none !important;
        text-shadow: none !important;
    }

    /* Halaman aktif */
    #tabel-riwayat_wrapper .dataTables_paginate
    .paginate_button.current .page-link,

    #tabel-riwayat_wrapper .dataTables_paginate
    .page-item.active .page-link {

        border-color: #159b98 !important;
        background: #159b98 !important;
        color: #fff !important;
    }

    /* Hover */
    #tabel-riwayat_wrapper .dataTables_paginate
    .paginate_button:not(.disabled):not(.current) .page-link:hover,

    #tabel-riwayat_wrapper .dataTables_paginate
    .page-item:not(.disabled):not(.active) .page-link:hover {

        border-color: #b9dedd !important;
        background: #edf9f8 !important;
        color: #168d8a !important;
    }

    /* Previous / Next disabled */
    #tabel-riwayat_wrapper .dataTables_paginate
    .paginate_button.disabled .page-link,

    #tabel-riwayat_wrapper .dataTables_paginate
    .page-item.disabled .page-link {

        border-color: #e4e9ec !important;
        background: #f5f7f8 !important;
        color: #b2bbc1 !important;
        opacity: 1 !important;
        cursor: default !important;
    }

    /* Matikan pseudo-element bawaan */
    #tabel-riwayat_wrapper .dataTables_paginate
    .paginate_button::before,

    #tabel-riwayat_wrapper .dataTables_paginate
    .paginate_button::after,

    #tabel-riwayat_wrapper .dataTables_paginate
    .page-link::before,

    #tabel-riwayat_wrapper .dataTables_paginate
    .page-link::after {

        display: none !important;
        content: none !important;
    }

    /* Hilangkan efek focus bawaan */
    #tabel-riwayat_wrapper .dataTables_paginate
    .paginate_button:focus,

    #tabel-riwayat_wrapper .dataTables_paginate
    .page-link:focus {

        outline: none !important;
        box-shadow: none !important;
    }

    /* =========================
       EMPTY STATE
       ========================= */

    .riwayat-empty {
        padding: 55px 20px;
        text-align: center;
    }

    .riwayat-empty-icon {
        width: 62px;
        height: 62px;
        margin: 0 auto 14px;
        border-radius: 12px;
        background: #f1f6f7;
        color: #9aabb5;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.9rem;
    }

    .riwayat-empty-title {
        margin-bottom: 5px;
        color: #294257;
        font-size: 0.88rem;
        font-weight: 700;
    }

    .riwayat-empty-description {
        margin: 0;
        color: #82919b;
        font-size: 0.75rem;
    }

    .riwayat-empty-btn {
        min-height: 36px;
        margin-top: 14px;
        padding: 0 13px;
        border: 1px solid #b9dedd;
        border-radius: 7px;
        background: #fff;
        color: #168d8a;
        display: inline-flex;
        align-items: center;
        text-decoration: none;
        font-size: 0.73rem;
        font-weight: 700;
    }

    .riwayat-empty-btn:hover {
        background: #edf9f8;
        color: #128986;
    }

    /* =========================
       RESPONSIVE
       ========================= */

    @media (max-width: 992px) {

        #tabel-riwayat {
            min-width: 1150px;
        }

        .riwayat-table-wrapper {
            overflow-x: auto;
        }

    }

    @media (max-width: 768px) {

        .riwayat-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .riwayat-btn-antrean {
            width: 100%;
        }

        .riwayat-filter {
            align-items: stretch;
        }

        .riwayat-filter-label {
            width: 100%;
        }

        .riwayat-filter-select {
            flex: 1;
        }

        .riwayat-total {
            width: 100%;
            margin-left: 0;
            margin-top: 3px;
        }

        #tabel-riwayat_wrapper {
            padding: 12px;
        }

        #tabel-riwayat_wrapper > .d-flex {
            align-items: flex-start !important;
            flex-direction: column;
            gap: 9px;
        }

        #tabel-riwayat_wrapper .dataTables_filter {
            width: 100%;
        }

        #tabel-riwayat_wrapper .dataTables_filter label {
            width: 100%;
        }

        #tabel-riwayat_wrapper .dataTables_filter input {
            width: 100%;
            flex: 1;
        }

    }

    @media (max-width: 576px) {

        .riwayat-title {
            font-size: 0.95rem;
        }

        .riwayat-description {
            font-size: 0.74rem;
        }

        .riwayat-filter-body {
            padding: 12px;
        }

        .riwayat-filter-select {
            width: 100%;
        }

        .riwayat-filter {
            display: grid;
            grid-template-columns: 1fr;
        }

        .riwayat-total {
            margin-top: 2px;
        }

    }
</style>


<div class="riwayat-page">

    <!-- =========================================================
         PERMISSION FLAGS
         Fungsi tetap sama
         ========================================================= -->

    <?php

    // Permission flags — digunakan untuk kontrol tombol aksi

    $bisa_pengujian_operasional =
        $this->Model_Hak_Akses->memiliki_akses(
            $this->session->userdata('role_id'),
            'pengujian_view'
        );

    $bisa_pengujian_input =
        $this->Model_Hak_Akses->memiliki_akses(
            $this->session->userdata('role_id'),
            'pengujian_input'
        );

    $user_id_login =
        $this->session->userdata('user_id');

    ?>


    <!-- =========================================================
         FLASH MESSAGES
         ========================================================= -->

    <?php if ($this->session->flashdata('pesan_sukses')): ?>

        <div
            class="alert alert-success alert-dismissible fade show"
            role="alert"
        >

            <i class="bi bi-check-circle me-2"></i>

            <?php echo html_escape(
                $this->session->flashdata('pesan_sukses')
            ); ?>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close"
            >
            </button>

        </div>

    <?php endif; ?>


    <?php if ($this->session->flashdata('pesan_gagal')): ?>

        <div
            class="alert alert-danger alert-dismissible fade show"
            role="alert"
        >

            <i class="bi bi-exclamation-circle me-2"></i>

            <?php echo html_escape(
                $this->session->flashdata('pesan_gagal')
            ); ?>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close"
            >
            </button>

        </div>

    <?php endif; ?>


    <!-- =========================================================
         PAGE HEADER
         ========================================================= -->

    <div class="riwayat-header">

        <div>

            <p class="riwayat-description">
                Melihat riwayat dan hasil pengujian sampel yang telah diproses.
            </p>

        </div>


        <?php if ($bisa_pengujian_operasional): ?>

            <a
                href="<?php echo site_url('pengujian/antrean'); ?>"
                class="riwayat-btn-antrean"
            >

                <i class="bi bi-list-task me-1"></i>

                Antrean Pengujian

            </a>

        <?php endif; ?>

    </div>


    <!-- =========================================================
         FILTER BAR
         ========================================================= -->

    <?php if (!empty($daftar_hasil)): ?>

        <div class="riwayat-filter-card">

            <div class="riwayat-filter-body">

                <div class="riwayat-filter">

                    <span class="riwayat-filter-label">

                        <i class="bi bi-funnel me-1"></i>

                        Filter:

                    </span>


                    <select
                        id="filter-jenis"
                        class="riwayat-filter-select"
                    >

                        <option value="">
                            Semua Jenis
                        </option>

                        <option value="Kimia">
                            Kimia
                        </option>

                        <option value="Mikrobiologi">
                            Mikrobiologi
                        </option>

                    </select>


                    <select
                        id="filter-status"
                        class="riwayat-filter-select"
                    >

                        <option value="">
                            Semua Status
                        </option>

                        <option value="Sedang Diuji">
                            Sedang Diuji
                        </option>

                        <option value="Menunggu Verifikasi">
                            Menunggu Verifikasi
                        </option>

                        <option value="Menunggu Approval">
                            Menunggu Approval
                        </option>

                        <option value="Approved / Final">
                            Approved / Final
                        </option>

                        <option value="Ditolak">
                            Ditolak
                        </option>

                    </select>


                    <span class="riwayat-total">

                        Total:

                        <strong id="total-rows">
                            <?php echo count($daftar_hasil); ?>
                        </strong>

                        sesi

                    </span>

                </div>

            </div>

        </div>

    <?php endif; ?>


    <!-- =========================================================
         MAIN TABLE
         ========================================================= -->

    <div class="riwayat-table-card">

        <div class="riwayat-table-wrapper">

            <?php if (!empty($daftar_hasil)): ?>

                <table
                    id="tabel-riwayat"
                    class="table table-hover align-middle mb-0"
                    style="width:100%"
                >

                    <thead>

                        <tr>

                            <th width="4%">
                                #
                            </th>

                            <th>
                                No. / Kode Sampel
                            </th>

                            <th>
                                Nama Sampel
                            </th>

                            <th>
                                Jenis Pengujian
                            </th>

                            <th>
                                Penguji
                            </th>

                            <th>
                                Penyelia
                            </th>

                            <th>
                                Kesimpulan
                            </th>

                            <th>
                                Status
                            </th>

                            <th
                                class="text-center"
                                width="18%"
                            >
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php $no = 1; foreach ($daftar_hasil as $h): ?>

                            <?php

                            // Status badge class

                            $st = isset($h['status'])
                                ? $h['status']
                                : '-';

                            $st_cls =
                                'badge-menunggu-pengujian';


                            if ($st === 'Sedang Diuji') {

                                $st_cls =
                                    'badge-sedang-diuji';

                            } elseif (
                                $st === 'Menunggu Verifikasi'
                            ) {

                                $st_cls =
                                    'badge-menunggu-verifikasi';

                            } elseif (
                                $st === 'Menunggu Approval'
                            ) {

                                $st_cls =
                                    'badge-menunggu-approval';

                            } elseif (
                                $st === 'Approved / Final'
                            ) {

                                $st_cls =
                                    'badge-approved-final';

                            } elseif ($st === 'Ditolak') {

                                $st_cls =
                                    'badge-ditolak';

                            }


                            // Kesimpulan badge

                            $kes =
                                isset($h['kesimpulan'])
                                    ? $h['kesimpulan']
                                    : '';

                            $k_cls = 'secondary';


                            if (
                                stripos(
                                    $kes,
                                    'memenuhi'
                                ) !== false
                                &&
                                stripos(
                                    $kes,
                                    'tidak'
                                ) === false
                            ) {

                                $k_cls = 'success';

                            } elseif (
                                stripos(
                                    $kes,
                                    'tidak memenuhi'
                                ) !== false
                            ) {

                                $k_cls = 'danger';

                            } elseif (
                                stripos(
                                    $kes,
                                    'belum'
                                ) !== false
                            ) {

                                $k_cls = 'light';

                            }


                            $kode_sampel =
                                $h['kode_sampel_manual']
                                ?:
                                (
                                    $h['no_sampel']
                                    ? 'NO-' . $h['no_sampel']
                                    : 'SMP-' . $h['sample_id']
                                );


                            // Apakah baris ini sesi atau single form legacy

                            $is_sesi =
                                !empty(
                                    $h['jenis_pengujian']
                                );


                            // Jenis untuk filter

                            $jenis_val =
                                $is_sesi
                                    ? html_escape(
                                        $h['jenis_pengujian']
                                    )
                                    : 'Legacy';

                            ?>


                            <tr
                                data-jenis="<?php echo $jenis_val; ?>"
                                data-status="<?php echo html_escape($st); ?>"
                            >


                                <!-- NOMOR -->

                                <td class="text-muted">

                                    <?php echo $no++; ?>

                                </td>


                                <!-- KODE SAMPEL -->

                                <td>

                                    <span class="riwayat-kode">

                                        <?php echo html_escape(
                                            $kode_sampel
                                        ); ?>

                                    </span>

                                </td>


                                <!-- NAMA SAMPEL -->

                                <td>

                                    <strong class="riwayat-nama">

                                        <?php echo html_escape(
                                            $h['nama_sampel']
                                        ); ?>

                                    </strong>


                                    <?php if (
                                        !empty($h['nama_template'])
                                        &&
                                        !$is_sesi
                                    ): ?>

                                        <small class="riwayat-subtext">

                                            <?php echo html_escape(
                                                $h['nama_template']
                                            ); ?>

                                        </small>


                                    <?php elseif (
                                        $is_sesi
                                        &&
                                        !empty($h['jumlah_form'])
                                    ): ?>

                                        <small class="riwayat-subtext">

                                            <?php echo (int)
                                                $h['jumlah_form'];
                                            ?>

                                            form

                                        </small>

                                    <?php endif; ?>

                                </td>


                                <!-- JENIS PENGUJIAN -->

                                <td>

                                    <?php if ($is_sesi): ?>

                                        <span class="riwayat-jenis">

                                            <?php echo html_escape(
                                                $h['jenis_pengujian']
                                            ); ?>

                                        </span>

                                    <?php else: ?>

                                        <span
                                            class="
                                                riwayat-jenis
                                                riwayat-jenis-legacy
                                            "
                                        >

                                            Single Form

                                        </span>

                                    <?php endif; ?>

                                </td>


                                <!-- PENGUJI -->

                                <td>

                                    <span class="riwayat-person">

                                        <?php echo html_escape(
                                            $h['nama_penguji'] ?: '-'
                                        ); ?>

                                    </span>

                                </td>


                                <!-- PENYELIA -->

                                <td>

                                    <span class="riwayat-person">

                                        <?php echo html_escape(

                                            isset(
                                                $h['nama_verifier']
                                            )
                                            &&
                                            $h['nama_verifier']

                                                ? $h['nama_verifier']
                                                : '-'

                                        ); ?>

                                    </span>

                                </td>


                                <!-- KESIMPULAN -->

                                <td>

                                    <span
                                        class="
                                            riwayat-kesimpulan
                                            <?php echo $k_cls; ?>
                                        "
                                    >

                                        <?php echo html_escape(
                                            $kes ?: 'Belum Disimpulkan'
                                        ); ?>

                                    </span>

                                </td>


                                <!-- STATUS -->

                                <td>

                                    <span
                                        class="
                                            badge-status
                                            <?php echo $st_cls; ?>
                                        "
                                    >

                                        <?php echo html_escape($st); ?>

                                    </span>

                                </td>


                                <!-- AKSI -->

                                <td>

                                    <div class="riwayat-actions">


                                        <?php if ($is_sesi): ?>

                                            <a
                                                href="<?php echo site_url(
                                                    'pengujian/detail_sesi/'
                                                    . $h['id']
                                                ); ?>"
                                                class="
                                                    riwayat-action-btn
                                                    riwayat-action-detail
                                                "
                                                title="Lihat Detail Sesi"
                                            >

                                                <i class="bi bi-eye me-1"></i>

                                                Detail

                                            </a>

                                        <?php else: ?>

                                            <a
                                                href="<?php echo site_url(
                                                    'pengujian/detail/'
                                                    . $h['id']
                                                ); ?>"
                                                class="
                                                    riwayat-action-btn
                                                    riwayat-action-detail
                                                "
                                                title="Lihat Detail"
                                            >

                                                <i class="bi bi-eye me-1"></i>

                                                Detail

                                            </a>

                                        <?php endif; ?>


                                        <?php

                                        // Download PDF
                                        // jika status Approved / Final
                                        // atau file_laporan tersedia

                                        $has_pdf = (
                                            $st === 'Approved / Final'
                                            ||
                                            !empty(
                                                $h['file_laporan']
                                            )
                                        );


                                        if ($has_pdf):

                                            $pdf_url =
                                                $is_sesi

                                                ? site_url(
                                                    'pengujian/pdf_sesi/'
                                                    . $h['id']
                                                )

                                                : site_url(
                                                    'pengujian/download_pdf/'
                                                    . $h['id']
                                                );

                                        ?>

                                            <a
                                                href="<?php echo $pdf_url; ?>"
                                                class="
                                                    riwayat-action-btn
                                                    riwayat-action-pdf
                                                "
                                                title="Unduh PDF Laporan"
                                                target="_blank"
                                            >

                                                <i
                                                    class="
                                                        bi
                                                        bi-file-earmark-pdf
                                                        me-1
                                                    "
                                                ></i>

                                                PDF

                                            </a>

                                        <?php endif; ?>


                                        <?php

                                        // Tombol Revisi
                                        // hanya untuk penguji pemilik,
                                        // status Ditolak

                                        if (
                                            $bisa_pengujian_input
                                            &&
                                            $st === 'Ditolak'
                                            &&
                                            isset(
                                                $h['penguji_id']
                                            )
                                            &&
                                            $h['penguji_id']
                                                == $user_id_login
                                        ):

                                        ?>

                                            <?php if ($is_sesi): ?>

                                                <a
                                                    href="<?php echo site_url(
                                                        'pengujian/revisi_sesi/'
                                                        . $h['id']
                                                    ); ?>"
                                                    class="
                                                        riwayat-action-btn
                                                        riwayat-action-revisi
                                                    "
                                                    title="Revisi Hasil Sesi"
                                                >

                                                    <i
                                                        class="
                                                            bi
                                                            bi-pencil-square
                                                            me-1
                                                        "
                                                    ></i>

                                                    Revisi

                                                </a>

                                            <?php else: ?>

                                                <a
                                                    href="<?php echo site_url(
                                                        'pengujian/revisi/'
                                                        . $h['id']
                                                    ); ?>"
                                                    class="
                                                        riwayat-action-btn
                                                        riwayat-action-revisi
                                                    "
                                                    title="Revisi Hasil"
                                                >

                                                    <i
                                                        class="
                                                            bi
                                                            bi-pencil-square
                                                            me-1
                                                        "
                                                    ></i>

                                                    Revisi

                                                </a>

                                            <?php endif; ?>

                                        <?php endif; ?>


                                    </div>

                                </td>

                            </tr>


                        <?php endforeach; ?>

                    </tbody>

                </table>


            <?php else: ?>


                <!-- =====================================================
                     EMPTY STATE
                     ===================================================== -->

                <div class="riwayat-empty">

                    <div class="riwayat-empty-icon">

                        <i class="bi bi-file-earmark-text"></i>

                    </div>


                    <h6 class="riwayat-empty-title">

                        Belum Ada Hasil Pengujian

                    </h6>


                    <p class="riwayat-empty-description">

                        Belum terdapat hasil pengujian yang dapat ditampilkan.

                    </p>


                    <?php if ($bisa_pengujian_operasional): ?>

                        <a
                            href="<?php echo site_url(
                                'pengujian/antrean'
                            ); ?>"
                            class="riwayat-empty-btn"
                        >

                            <i class="bi bi-list-task me-1"></i>

                            Lihat Antrean Pengujian

                        </a>

                    <?php endif; ?>

                </div>

            <?php endif; ?>

        </div>

    </div>

</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    var table = null;


    if (
        typeof $.fn.DataTable !== 'undefined' &&
        $('#tabel-riwayat').length
    ) {

        table = $('#tabel-riwayat').DataTable({

            order: [[0, 'asc']],

            pageLength: 25,

            dom:
                '<"d-flex align-items-center justify-content-between mb-3"lf>' +
                'rt' +
                '<"d-flex align-items-center justify-content-between mt-3"ip>',

            language: {

                url:
                    '<?php echo base_url(
                        'assets/js/dataTables.indonesian.json'
                    ); ?>',

                search: 'Cari:',

                lengthMenu:
                    'Tampilkan _MENU_ data',

                info:
                    'Menampilkan _START_–_END_ dari _TOTAL_ data',

                infoEmpty:
                    'Tidak ada data',

                paginate: {
                    previous: '&laquo;',
                    next: '&raquo;'
                }

            },

            columnDefs: [

                {
                    orderable: false,
                    targets: [-1]
                }

            ]

        });

    }


    // Filter Jenis Pengujian

    var filterJenis =
        document.getElementById('filter-jenis');

    var filterStatus =
        document.getElementById('filter-status');


    function applyFilter() {

        var jenis =
            filterJenis
                ? filterJenis.value
                : '';

        var status =
            filterStatus
                ? filterStatus.value
                : '';


        if (table) {

            // Custom filter via DataTables

            $.fn.dataTable.ext.search.length = 0;


            if (jenis || status) {

                $.fn.dataTable.ext.search.push(

                    function (
                        settings,
                        data,
                        dataIndex
                    ) {

                        var rowNode =
                            table
                                .row(dataIndex)
                                .node();


                        var rowJenis =
                            rowNode
                                ? rowNode.getAttribute(
                                    'data-jenis'
                                )
                                : '';


                        var rowSt =
                            rowNode
                                ? rowNode.getAttribute(
                                    'data-status'
                                )
                                : '';


                        var okJenis =
                            !jenis ||
                            rowJenis === jenis;


                        var okStatus =
                            !status ||
                            rowSt === status;


                        return (
                            okJenis &&
                            okStatus
                        );

                    }

                );

            }


            table.draw();


            document.getElementById(
                'total-rows'
            ).textContent =

                table.rows({
                    filter: 'applied'
                }).count();


        } else {

            // Fallback:
            // no DataTable — filter via DOM

            var rows =
                document.querySelectorAll(
                    '#tabel-riwayat tbody tr'
                );

            var visible = 0;


            rows.forEach(function (row) {

                var rowJenis =
                    row.getAttribute(
                        'data-jenis'
                    ) || '';


                var rowStatus =
                    row.getAttribute(
                        'data-status'
                    ) || '';


                var show =

                    (!jenis ||
                        rowJenis === jenis)

                    &&

                    (!status ||
                        rowStatus === status);


                row.style.display =
                    show ? '' : 'none';


                if (show) {
                    visible++;
                }

            });


            var totalEl =
                document.getElementById(
                    'total-rows'
                );


            if (totalEl) {
                totalEl.textContent =
                    visible;
            }

        }

    }


    if (filterJenis) {

        filterJenis.addEventListener(
            'change',
            applyFilter
        );

    }


    if (filterStatus) {

        filterStatus.addEventListener(
            'change',
            applyFilter
        );

    }

});
</script>