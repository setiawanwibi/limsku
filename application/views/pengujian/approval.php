<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
    /* =========================================================
       ANTREAN APPROVAL LAPORAN
       Visual redesign only
       ========================================================= */

    .approval-page {
        color: #294257;
        font-size: .88rem;
    }

    /* Flash Messages */
    .approval-page .alert {
        border-radius: 9px;
        font-size: .80rem;
        line-height: 1.55;
        border-width: 1px;
        box-shadow: 0 3px 10px rgba(35, 58, 76, .04);
    }

    /* Main Card */
    .approval-card {
        overflow: hidden;
        background: #fff;
        border: 1px solid #e0e8ed;
        border-radius: 11px;
        box-shadow: 0 5px 18px rgba(35, 58, 76, .06);
    }

    /* Header */
    .approval-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        padding: 17px 20px;
        border-bottom: 1px solid #e5ebee;
        background: #fff;
    }

    .approval-header-main {
        min-width: 0;
    }

    .approval-title {
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 0 0 5px;
        color: #294257;
        font-size: 1rem;
        font-weight: 700;
        line-height: 1.4;
    }

    .approval-title i {
        color: #159b98;
        font-size: 1.05rem;
    }

    .approval-subtitle {
        margin: 0;
        color: #788b97;
        font-size: .73rem;
        line-height: 1.55;
    }

    .approval-history-btn {
        min-height: 35px;
        padding: 7px 12px;
        border: 1px solid #d9e3e7;
        border-radius: 7px;
        background: #fff;
        color: #657987;
        font-size: .69rem;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        white-space: nowrap;
        transition: .18s ease;
    }

    .approval-history-btn:hover {
        background: #f3f9f8;
        border-color: #c6dfdd;
        color: #168f8b;
    }


    /* =========================================================
       TABLE AREA
       ========================================================= */

    .approval-body {
        padding: 18px 20px 20px;
    }

    /*
     * overflow tetap hidden untuk border radius,
     * tetapi kontrol DataTables diberi ruang sendiri.
     */
    .approval-table-wrap {
        border: 1px solid #e3eaee;
        border-radius: 8px;
        overflow: hidden;
        background: #fff;
    }

    .approval-table {
        margin: 0;
        color: #536b79;
        font-size: .74rem;
    }

    .approval-table thead th {
        padding: 11px 12px;
        border-bottom: 1px solid #dfe7eb;
        background: #f7fafb;
        color: #708490;
        font-size: .63rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .025em;
        white-space: nowrap;
    }

    .approval-table tbody td {
        padding: 12px;
        border-color: #edf1f3;
        vertical-align: middle;
    }

    .approval-table tbody tr {
        transition: .16s ease;
    }

    .approval-table tbody tr:hover {
        background: #f8fbfb;
    }


    /* =========================================================
       NUMBER
       ========================================================= */

    .approval-number {
        color: #8a9aa4;
        font-size: .68rem;
        font-weight: 600;
    }


    /* =========================================================
       SAMPLE CODE
       ========================================================= */

    .approval-code {
        display: inline-flex;
        align-items: center;
        min-height: 25px;
        padding: 4px 7px;
        border: 1px solid #cfe8e7;
        border-radius: 5px;
        background: #eef8f7;
        color: #168f8b;
        font-family: monospace;
        font-size: .66rem;
        font-weight: 700;
        white-space: nowrap;
    }


    /* =========================================================
       SAMPLE NAME
       ========================================================= */

    .approval-sample-name {
        color: #3f5868;
        font-size: .74rem;
        font-weight: 700;
        line-height: 1.4;
    }


    /* =========================================================
       USER NAMES
       ========================================================= */

    .approval-user {
        color: #536b79;
        font-size: .70rem;
        font-weight: 600;
    }

    .approval-user.verifier {
        color: #3f8584;
    }


    /* =========================================================
       CONCLUSION BADGE
       ========================================================= */

    .approval-conclusion {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 25px;
        padding: 4px 8px;
        border-radius: 20px;
        font-size: .61rem;
        font-weight: 700;
        line-height: 1.2;
        white-space: nowrap;
    }

    .approval-conclusion.success {
        border: 1px solid #cde7d5;
        background: #edf8f0;
        color: #33804e;
    }

    .approval-conclusion.danger {
        border: 1px solid #efcccc;
        background: #fff2f2;
        color: #b14d4d;
    }

    .approval-conclusion.secondary {
        border: 1px solid #dfe5e8;
        background: #f4f6f7;
        color: #72838e;
    }


    /* =========================================================
       APPROVAL BUTTON
       ========================================================= */

    .approval-action-btn {
        min-height: 34px;
        padding: 7px 11px;
        border: 1px solid #159b98;
        border-radius: 7px;
        background: #159b98;
        color: #fff;
        font-size: .67rem;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        white-space: nowrap;
        box-shadow: 0 3px 7px rgba(21, 155, 152, .12);
        transition: .18s ease;
    }

    .approval-action-btn:hover {
        border-color: #118b88;
        background: #118b88;
        color: #fff;
        transform: translateY(-1px);
    }


    /* =========================================================
       DATATABLES WRAPPER
       ========================================================= */

    .approval-page .dataTables_wrapper {
        padding-top: 0 !important;
        padding-bottom: 8px !important;
    }


    /* =========================================================
       TOP CONTROL AREA
       ========================================================= */

    .approval-page .dataTables_wrapper .dataTables_length,
    .approval-page .dataTables_wrapper .dataTables_filter {
        margin-top: 0 !important;
        margin-bottom: 10px !important;
        padding-top: 10px !important;
        color: #748793;
        font-size: .68rem;
    }

    /* Show Entries diberi jarak dari kiri */
    .approval-page .dataTables_wrapper .dataTables_length {
        padding-left: 10px !important;
    }

    /* Search diberi jarak dari kanan */
    .approval-page .dataTables_wrapper .dataTables_filter {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        padding-right: 10px !important;
    }


    /* =========================================================
       SHOW ENTRIES
       ========================================================= */

    .approval-page .dataTables_wrapper .dataTables_length label {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        margin: 0;
        color: #748793;
        font-size: .68rem;
        font-weight: 500;
        line-height: 1;
    }

    .approval-page .dataTables_wrapper .dataTables_length select {
        width: 54px !important;
        min-width: 54px !important;
        height: 29px !important;

        margin: 0 !important;
        padding: 3px 20px 3px 7px !important;

        border: 1px solid #d8e1e6 !important;
        border-radius: 6px !important;

        background-color: #fff !important;
        color: #526779 !important;

        font-size: .70rem !important;
        font-weight: 500 !important;
        line-height: 1 !important;

        box-shadow: none !important;
    }

    .approval-page .dataTables_wrapper .dataTables_length select:focus {
        border-color: #a9cfcd !important;
        outline: none !important;
        box-shadow: 0 0 0 2px rgba(21, 155, 152, .07) !important;
    }


    /* =========================================================
       SEARCH
       ========================================================= */

    .approval-page .dataTables_wrapper .dataTables_filter label {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        margin: 0;
        color: #748793;
        font-size: .68rem;
    }

    .approval-page .dataTables_wrapper .dataTables_filter input {
        width: 150px !important;
        min-width: 150px !important;
        min-height: 29px !important;
        height: 29px !important;

        margin-left: 0 !important;
        padding: 4px 8px !important;

        border: 1px solid #dce5e9 !important;
        border-radius: 6px !important;

        color: #526779 !important;
        font-size: .70rem !important;

        box-shadow: none !important;
    }

    .approval-page .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #a7d3d0 !important;
        outline: none !important;
        box-shadow: 0 0 0 2px rgba(21, 155, 152, .07) !important;
    }


    /* =========================================================
       TABLE
       ========================================================= */

    .approval-page .dataTables_wrapper > .row:first-child {
        margin-left: 0 !important;
        margin-right: 0 !important;
    }

    .approval-page .dataTables_wrapper > .row:first-child > div {
        padding-left: 0 !important;
        padding-right: 0 !important;
    }


    /* =========================================================
       INFO + PAGINATION AREA
       ========================================================= */

    /*
     * Memberikan ruang bawah agar tombol pagination
     * tidak terpotong oleh overflow:hidden.
     */
    .approval-page .dataTables_wrapper .dataTables_info {
        padding-top: 10px !important;
        padding-left: 10px !important;
        padding-bottom: 4px !important;

        color: #84949e !important;
        font-size: .63rem !important;
        line-height: 1.2 !important;
    }

    .approval-page .dataTables_wrapper .dataTables_paginate {
        padding-top: 8px !important;
        padding-right: 10px !important;
        padding-bottom: 4px !important;
        margin: 0 !important;
    }


    /* =========================================================
       BOOTSTRAP 5 PAGINATION
       ========================================================= */

    .approval-page .dataTables_wrapper .dataTables_paginate .pagination {
        display: flex !important;
        align-items: center !important;
        justify-content: flex-end !important;
        gap: 4px !important;

        margin: 0 !important;
        padding: 0 !important;
    }

    .approval-page .dataTables_wrapper .dataTables_paginate .page-item {
        margin: 0 !important;
        padding: 0 !important;
    }

    .approval-page .dataTables_wrapper .dataTables_paginate .page-link {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;

        min-width: 29px !important;
        width: auto !important;
        height: 29px !important;

        margin: 0 !important;
        padding: 4px 8px !important;

        border: 1px solid #dce4e8 !important;
        border-radius: 5px !important;

        background: #fff !important;
        color: #667b89 !important;

        font-size: .66rem !important;
        font-weight: 600 !important;
        line-height: 1 !important;

        box-shadow: none !important;
    }

    /* Previous */
    .approval-page .dataTables_wrapper .dataTables_paginate .page-item:first-child .page-link {
        min-width: 48px !important;
        padding-left: 7px !important;
        padding-right: 7px !important;
    }

    /* Next */
    .approval-page .dataTables_wrapper .dataTables_paginate .page-item:last-child .page-link {
        min-width: 48px !important;
        padding-left: 7px !important;
        padding-right: 7px !important;
    }

    .approval-page .dataTables_wrapper .dataTables_paginate .page-link:hover {
        border-color: #cfe4e2 !important;
        background: #eef8f7 !important;
        color: #168f8b !important;
    }

    .approval-page .dataTables_wrapper .dataTables_paginate .page-item.active .page-link {
        border-color: #159b98 !important;
        background: #159b98 !important;
        color: #fff !important;
    }

    .approval-page .dataTables_wrapper .dataTables_paginate .page-item.active .page-link:hover {
        border-color: #159b98 !important;
        background: #159b98 !important;
        color: #fff !important;
    }

    .approval-page .dataTables_wrapper .dataTables_paginate .page-item.disabled .page-link {
        border-color: #e3e8eb !important;
        background: #f5f7f8 !important;
        color: #b1bdc4 !important;
        cursor: default !important;
    }


    /* =========================================================
       DATATABLES NON-BOOTSTRAP FALLBACK
       ========================================================= */

    .approval-page .dataTables_wrapper .dataTables_paginate .paginate_button {
        margin: 0 1px !important;
        padding: 4px 7px !important;

        min-width: 29px !important;
        height: 29px !important;

        border: 1px solid transparent !important;
        border-radius: 5px !important;

        color: #667b89 !important;
        font-size: .65rem !important;
        line-height: 18px !important;
    }

    .approval-page .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        border-color: #cfe4e2 !important;
        background: #eef8f7 !important;
        color: #168f8b !important;
    }

    .approval-page .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        border-color: #159b98 !important;
        background: #159b98 !important;
        color: #fff !important;
    }

    .approval-page .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
        color: #b1bdc4 !important;
    }


    /* =========================================================
       EMPTY STATE
       ========================================================= */

    .approval-empty {
        padding: 38px 20px !important;
        text-align: center;
        color: #8797a1;
        font-size: .72rem;
    }

    .approval-empty i {
        display: block;
        margin-bottom: 8px;
        color: #9aabb4;
        font-size: 1.35rem;
    }


    /* =========================================================
       RESPONSIVE
       ========================================================= */

    @media (max-width: 991.98px) {

        .approval-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .approval-history-btn {
            width: 100%;
        }

        .approval-body {
            padding: 15px 15px 18px;
        }

    }


    @media (max-width: 767.98px) {

        .approval-page .dataTables_wrapper .dataTables_length,
        .approval-page .dataTables_wrapper .dataTables_filter {
            margin-bottom: 8px !important;
        }

        .approval-page .dataTables_wrapper .dataTables_length {
            padding-left: 7px !important;
        }

        .approval-page .dataTables_wrapper .dataTables_filter {
            justify-content: flex-start;
            padding-right: 7px !important;
            margin-top: 5px;
        }

        .approval-page .dataTables_wrapper .dataTables_filter input {
            width: 130px !important;
            min-width: 130px !important;
        }

        .approval-page .dataTables_wrapper .dataTables_info {
            padding-left: 7px !important;
        }

        .approval-page .dataTables_wrapper .dataTables_paginate {
            padding-right: 7px !important;
        }

        .approval-page .dataTables_wrapper .dataTables_paginate .page-link {
            min-width: 27px !important;
            height: 27px !important;
            padding: 3px 7px !important;
            font-size: .62rem !important;
        }

        .approval-page .dataTables_wrapper .dataTables_paginate .page-item:first-child .page-link,
        .approval-page .dataTables_wrapper .dataTables_paginate .page-item:last-child .page-link {
            min-width: 43px !important;
        }

    }


    @media (max-width: 575.98px) {

        .approval-title {
            font-size: .92rem;
        }

        .approval-subtitle {
            font-size: .69rem;
        }

        .approval-table {
            min-width: 900px;
        }

        .approval-page .dataTables_wrapper .dataTables_info {
            font-size: .60rem !important;
        }

    }
</style>


<div class="approval-page">

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
         MAIN APPROVAL CARD
    ====================================================== -->

    <div class="approval-card">

        <!-- Header -->
        <div class="approval-header">

            <div class="approval-header-main">

                <h5 class="approval-title">

                    <i class="bi bi-shield-check"></i>

                    Queue Approval Laporan Pengujian

                </h5>

                <p class="approval-subtitle">
                    Daftar laporan pengujian sampel yang menantikan approval oleh
                    Manajer Teknis (Status: Menunggu Approval)
                </p>

            </div>


            <a
                href="<?php echo site_url('pengujian/riwayat'); ?>"
                class="approval-history-btn"
            >

                <i class="bi bi-clock-history"></i>

                Riwayat Semua Pengujian

            </a>

        </div>


        <!-- Body -->
        <div class="approval-body">

            <div class="table-responsive approval-table-wrap">

                <table
                    id="tabel-approval"
                    class="table table-hover align-middle w-100 approval-table"
                >

                    <thead>

                        <tr>

                            <th width="5%">
                                #
                            </th>

                            <th>
                                Kode Sampel
                            </th>

                            <th>
                                Nama Sampel
                            </th>

                            <th>
                                Penguji / Analis
                            </th>

                            <th>
                                Penyelia (Verifikator)
                            </th>

                            <th>
                                Kesimpulan
                            </th>

                            <th width="12%">
                                Aksi Approval
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php if (!empty($antrean)): ?>

                            <?php $no = 1; foreach ($antrean as $v): ?>

                                <tr>

                                    <!-- Nomor -->
                                    <td>

                                        <span class="approval-number">
                                            <?php echo $no++; ?>
                                        </span>

                                    </td>


                                    <!-- Kode Sampel -->
                                    <td>

                                        <span class="approval-code">

                                            <?php
                                                echo html_escape(
                                                    $v['kode_sampel_manual']
                                                    ?: (
                                                        $v['no_sampel']
                                                        ? 'NO-' . $v['no_sampel']
                                                        : 'SMP-' . $v['sample_id']
                                                    )
                                                );
                                            ?>

                                        </span>

                                    </td>


                                    <!-- Nama Sampel -->
                                    <td>

                                        <strong class="approval-sample-name">
                                            <?php echo html_escape($v['nama_sampel']); ?>
                                        </strong>

                                    </td>


                                    <!-- Penguji -->
                                    <td>

                                        <small class="approval-user">
                                            <?php echo html_escape($v['nama_penguji'] ?: '-'); ?>
                                        </small>

                                    </td>


                                    <!-- Penyelia -->
                                    <td>

                                        <small class="approval-user verifier">
                                            <?php echo html_escape($v['nama_verifier'] ?: '-'); ?>
                                        </small>

                                    </td>


                                    <!-- Kesimpulan -->
                                    <td>

                                        <?php

                                            $kes = $v['kesimpulan'];

                                            $b_cls = 'secondary';

                                            if (
                                                stripos($kes, 'memenuhi') !== false
                                                &&
                                                stripos($kes, 'tidak') === false
                                            ) {

                                                $b_cls = 'success';

                                            } elseif (
                                                stripos($kes, 'tidak memenuhi') !== false
                                            ) {

                                                $b_cls = 'danger';

                                            }

                                        ?>


                                        <span
                                            class="approval-conclusion <?php echo $b_cls === 'success' ? 'success' : ($b_cls === 'danger' ? 'danger' : 'secondary'); ?>"
                                        >

                                            <?php echo html_escape($kes); ?>

                                        </span>

                                    </td>


                                    <!-- Aksi Approval -->
                                    <td>

                                        <a
                                            href="<?php echo site_url('pengujian/detail_sesi/' . $v['id']); ?>"
                                            class="approval-action-btn"
                                        >

                                            <i class="bi bi-search"></i>

                                            Periksa &amp; Approve

                                        </a>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>

                                <td
                                    colspan="7"
                                    class="approval-empty"
                                >

                                    <i class="bi bi-inbox"></i>

                                    Belum ada laporan pengujian dalam antrean
                                    "Menunggu Approval".

                                </td>

                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>


<script>
document.addEventListener('DOMContentLoaded', function() {

    if (typeof $.fn.DataTable !== 'undefined') {

        $('#tabel-approval').DataTable({

            language: {
                url: '<?php echo base_url('assets/js/dataTables.indonesian.json'); ?>'
            }

        });

    }

});
</script>