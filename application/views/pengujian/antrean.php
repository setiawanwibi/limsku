<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style>
    /* =========================================================
       HALAMAN ANTREAN PENGUJIAN
       UI ONLY - FUNGSI TIDAK DIUBAH
    ========================================================= */

    .antrean-page {
        color: #294257;
        font-size: .88rem;
    }

    /* =========================================================
       FLASH MESSAGE
    ========================================================= */

    .flash-message {
        border-radius: 8px;
        border-width: 1px;
        padding: 11px 14px;
        margin-bottom: 18px;
        font-size: .78rem;
    }

    /* =========================================================
       HEADER HALAMAN
    ========================================================= */

    .antrean-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 20px;
    }

    .antrean-title {
        margin: 0 0 4px;
        color: #294257;
        font-size: 1.08rem;
        font-weight: 700;
        line-height: 1.35;
    }

    .antrean-subtitle {
        margin: 0;
        color: #7a8d9a;
        font-size: .76rem;
        line-height: 1.5;
    }

    /* =========================================================
       SUMMARY
    ========================================================= */

    .antrean-summary {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        flex-wrap: wrap;
        gap: 7px;
    }

    .summary-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        min-height: 28px;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: .66rem;
        font-weight: 700;
        white-space: nowrap;
        border: 1px solid transparent;
    }

    .summary-badge .dot {
        width: 6px;
        height: 6px;
        flex: 0 0 6px;
        border-radius: 50%;
    }

    .badge-belum {
        background: #fff6df;
        border-color: #f1dfac;
        color: #94700d;
    }

    .badge-belum .dot {
        background: #e9ad18;
    }

    .badge-sedang {
        background: #e5f6f4;
        border-color: #cde9e7;
        color: #168d89;
    }

    .badge-sedang .dot {
        background: #159b98;
    }

    /* =========================================================
       CARD
    ========================================================= */

    .antrean-card,
    .pekerjaan-card {
        background: #fff;
        border: 1px solid #e0e8ed;
        border-radius: 11px;
        box-shadow: 0 5px 18px rgba(35, 58, 76, .06);
        overflow: hidden;
    }

    /* =========================================================
       SAMPEL SAYA
    ========================================================= */

    .pekerjaan-card {
        margin-bottom: 20px;
        border-left: 3px solid #159b98;
    }

    .pekerjaan-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        min-height: 53px;
        padding: 13px 17px;
        background: #fff;
        border-bottom: 1px solid #e7edf0;
    }

    .pekerjaan-card-title {
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 0;
        color: #294257;
        font-size: .82rem;
        font-weight: 700;
    }

    .pekerjaan-card-title::before {
        content: "\f26a";
        font-family: "bootstrap-icons";
        color: #159b98;
        font-size: .95rem;
    }

    .pekerjaan-table {
        width: 100%;
        margin: 0 !important;
    }

    .pekerjaan-table thead th {
        padding: 10px 13px;
        background: #f7f9fa;
        border-bottom: 1px solid #e2e9ed;
        color: #738591;
        font-size: .65rem;
        font-weight: 700;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .pekerjaan-table tbody td {
        padding: 12px 13px;
        border-bottom: 1px solid #edf1f3;
        color: #536a78;
        font-size: .76rem;
        vertical-align: middle;
    }

    .pekerjaan-table tbody tr:last-child td {
        border-bottom: 0;
    }

    .pekerjaan-table tbody tr:hover {
        background: #fbfdfd;
    }

    /* =========================================================
       STATUS
    ========================================================= */

    .badge-status {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 9px;
        border-radius: 20px;
        font-size: .65rem;
        font-weight: 700;
        line-height: 1;
        white-space: nowrap;
    }

    .badge-sedang-diuji {
        background: #e5f6f4;
        border: 1px solid #cde9e7;
        color: #168d89;
    }

    .badge-sedang-diuji::before {
        content: "";
        width: 5px;
        height: 5px;
        border-radius: 50%;
        background: currentColor;
    }

    /* =========================================================
       TOMBOL LANJUTKAN
    ========================================================= */

    .pekerjaan-table .btn-warning {
        min-height: 34px;
        padding: 6px 11px;
        border: 1px solid #efd68f;
        border-radius: 7px;
        background: #fff7e2;
        color: #92700e;
        font-size: .70rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .pekerjaan-table .btn-warning:hover {
        background: #fff1c9;
        border-color: #e7ca74;
        color: #80620b;
    }

    /* =========================================================
       HEADER CARD SAMPEL TERSEDIA
    ========================================================= */

    .antrean-card-header {
        min-height: 58px;
        padding: 13px 17px;
        display: flex !important;
        flex-direction: row !important;
        align-items: center !important;
        justify-content: space-between !important;
        gap: 15px;
        background: #fff;
        border-bottom: 1px solid #e7edf0;
        width: 100%;
    }

    .antrean-card-title {
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 0;
        color: #294257;
        font-size: .84rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .antrean-card-title::before {
        content: "\f1e6";
        font-family: "bootstrap-icons";
        color: #159b98;
        font-size: .95rem;
    }

    /* =========================================================
       SEARCH
    ========================================================= */

    .antrean-search {
        width: 240px;
        flex: 0 0 240px;
        margin-left: auto;
    }

    #search-sampel {
        width: 100%;
        height: 36px;
        margin: 0;
        padding: 7px 12px;
        border: 1px solid #dfe7eb;
        border-radius: 7px;
        background: #f8fafb;
        color: #526876;
        font-size: .73rem;
        box-shadow: none;
        outline: none;
    }

    #search-sampel::placeholder {
        color: #9aa8b1;
    }

    #search-sampel:focus {
        border-color: #a9cfcd;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(21, 155, 152, .07);
    }

    /* =========================================================
       TABEL ANTREAN
    ========================================================= */

    .antrean-table-wrapper {
        overflow-x: auto;
    }

    #tabel-antrean {
        width: 100% !important;
        margin: 0 !important;
        border-collapse: separate;
        border-spacing: 0;
    }

    #tabel-antrean thead th {
        padding: 11px 13px;
        background: #f7f9fa;
        border-bottom: 1px solid #dfe7eb;
        color: #71828e;
        font-size: .65rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .02em;
        white-space: nowrap;
    }

    #tabel-antrean tbody td {
        padding: 13px;
        border-bottom: 1px solid #edf1f3;
        color: #536a78;
        font-size: .76rem;
        vertical-align: middle;
    }

    #tabel-antrean tbody tr:last-child td {
        border-bottom: 0;
    }

    #tabel-antrean tbody tr:hover {
        background: #fbfdfd;
    }

    /* =========================================================
       DATA SAMPLE
    ========================================================= */

    .kode-sampel {
        display: inline-flex;
        align-items: center;
        padding: 4px 7px;
        border-radius: 5px;
        background: #eef8f7;
        border: 1px solid #d6ecea;
        color: #168d89;
        font-family: monospace;
        font-size: .71rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .nama-sampel {
        display: block;
        margin-bottom: 3px;
        color: #294257;
        font-size: .77rem;
        font-weight: 700;
        line-height: 1.4;
    }

    .kategori-sampel {
        display: block;
        color: #8797a1;
        font-size: .68rem;
        line-height: 1.3;
    }

    /* =========================================================
       STATUS MENUNGGU
    ========================================================= */

    .status-menunggu {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 9px;
        border: 1px solid #f1dfac;
        border-radius: 20px;
        background: #fff6df;
        color: #94700d;
        font-size: .65rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .status-menunggu::before {
        content: "";
        width: 5px;
        height: 5px;
        border-radius: 50%;
        background: #e9ad18;
    }

    /* =========================================================
       TOMBOL AMBIL
    ========================================================= */

    .btn-ambil-sampel {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 34px;
        padding: 6px 12px;
        border: 1px solid #bcdedc;
        border-radius: 7px;
        background: #fff;
        color: #168d89;
        font-size: .70rem;
        font-weight: 700;
        text-decoration: none;
        white-space: nowrap;
        transition: all .15s ease;
    }

    .btn-ambil-sampel:hover {
        background: #eaf7f6;
        border-color: #9fcfcb;
        color: #127c78;
    }

    /* =========================================================
       INFORMASI
    ========================================================= */

    .antrean-info {
        display: flex;
        align-items: flex-start;
        gap: 11px;
        margin-top: 18px;
        padding: 14px 16px;
        border: 1px solid #d4e8e7;
        border-radius: 9px;
        background: #f2faf9;
    }

    .antrean-info-icon {
        width: 24px;
        height: 24px;
        min-width: 24px;
        border-radius: 7px;
        background: #dff3f1;
        color: #159b98;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .72rem;
        font-weight: 700;
    }

    .antrean-info-title {
        margin-bottom: 3px;
        color: #294257;
        font-size: .72rem;
        font-weight: 700;
    }

    .antrean-info-text {
        color: #6d828f;
        font-size: .70rem;
        line-height: 1.55;
    }

    /* =========================================================
       RIWAYAT
    ========================================================= */

    .antrean-page > .text-end {
        margin-top: 16px !important;
        margin-bottom: 4px !important;
    }

    .antrean-page > .text-end .btn {
        min-height: 35px;
        padding: 6px 12px;
        border: 1px solid #d5dfe4;
        border-radius: 7px;
        background: #fff;
        color: #627582;
        font-size: .70rem;
        font-weight: 600;
    }

    .antrean-page > .text-end .btn:hover {
        background: #f6f9fa;
        color: #4d6472;
        border-color: #c5d2d9;
    }

    /* =========================================================
       DATATABLE FOOTER
    ========================================================= */

    .antrean-datatables-footer {
        min-height: 50px;
        padding: 9px 13px;
        border-top: 1px solid #edf1f3;
        background: #fff;
    }

    .antrean-datatables-footer .dataTables_info,
    .antrean-datatables-footer .dataTables_paginate {
        font-size: .68rem !important;
    }

    .antrean-datatables-footer .dataTables_info {
        padding-top: 7px !important;
        color: #84929d !important;
    }

    .antrean-datatables-footer .dataTables_paginate {
        padding-top: 3px !important;
    }

    .antrean-datatables-footer .paginate_button {
        padding: 4px 8px !important;
        border-radius: 5px !important;
        font-size: .67rem !important;
    }

    /* =========================================================
       EMPTY STATE
    ========================================================= */

    #tabel-antrean tbody td.text-muted {
        padding: 42px 20px !important;
        color: #8999a3 !important;
        font-size: .75rem;
    }

    /* =========================================================
       RESPONSIVE
    ========================================================= */

    @media (max-width: 991.98px) {

        .antrean-header {
            align-items: flex-start;
        }

        .antrean-summary {
            justify-content: flex-start;
        }

        .antrean-card-header {
            padding: 12px 14px;
        }

        .antrean-search {
            width: 210px;
            flex-basis: 210px;
        }

    }

    @media (max-width: 767.98px) {

        .antrean-header {
            flex-direction: column;
            gap: 12px;
        }

        .antrean-summary {
            width: 100%;
            justify-content: flex-start;
        }

        .antrean-card-header {
            flex-direction: column !important;
            align-items: stretch !important;
        }

        .antrean-search {
            width: 100%;
            flex-basis: auto;
        }

        .antrean-card-title {
            font-size: .80rem;
        }

    }

    @media (max-width: 575.98px) {

        .antrean-title {
            font-size: .98rem;
        }

        .antrean-subtitle {
            font-size: .72rem;
        }

        .summary-badge {
            font-size: .62rem;
        }

        .pekerjaan-card-header {
            padding: 12px 13px;
        }

        .pekerjaan-card-title {
            font-size: .76rem;
        }

        #tabel-antrean thead th,
        #tabel-antrean tbody td,
        .pekerjaan-table thead th,
        .pekerjaan-table tbody td {
            padding-left: 10px;
            padding-right: 10px;
        }

    }
</style>

<div class="antrean-page">

    <!-- =====================================================
         FLASH MESSAGE
         ===================================================== -->

    <?php if ($this->session->flashdata('pesan_sukses')): ?>

        <div class="alert alert-success alert-dismissible fade show flash-message" role="alert">

            <?php echo html_escape($this->session->flashdata('pesan_sukses')); ?>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close">
            </button>

        </div>

    <?php endif; ?>


    <?php if ($this->session->flashdata('pesan_gagal')): ?>

        <div class="alert alert-danger alert-dismissible fade show flash-message" role="alert">

            <?php echo html_escape($this->session->flashdata('pesan_gagal')); ?>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close">
            </button>

        </div>

    <?php endif; ?>


    <!-- =====================================================
         HEADER
         ===================================================== -->

    <div class="antrean-header">

        <div>

            <h1 class="antrean-title">
                Daftar Sampel Tersedia
            </h1>

            <p class="antrean-subtitle">
                Pilih sampel yang ingin diuji, lalu ambil untuk memindahkannya ke Sampel Saya.
            </p>

        </div>


        <!-- SUMMARY -->

        <div class="antrean-summary">

            <span class="summary-badge badge-belum">

                <span class="dot"></span>

                <?php echo !empty($antrean_sampel) ? count($antrean_sampel) : 0; ?>

                BELUM DIAMBIL

            </span>


            <span class="summary-badge badge-sedang">

                <span class="dot"></span>

                <?php echo !empty($sedang_diuji) ? count($sedang_diuji) : 0; ?>

                SEDANG DIAMBIL

            </span>

        </div>

    </div>


    <!-- =====================================================
         SAMPEL SAYA
         ===================================================== -->

    <?php if (!empty($sedang_diuji)): ?>

        <div id="sedang-diuji" class="pekerjaan-card">

            <div class="pekerjaan-card-header">

                <h6 class="pekerjaan-card-title">
                    Sampel Saya — Pekerjaan Pengujian Sedang Berlangsung
                </h6>

            </div>


            <div class="table-responsive">

                <table class="table table-hover align-middle pekerjaan-table">

                    <thead>

                        <tr>

                            <th>Kode / No</th>

                            <th>Nama Sampel</th>

                            <th>Kategori</th>

                            <th>Status</th>

                            <th>Aksi</th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php foreach ($sedang_diuji as $sd): ?>

                            <tr>

                                <!-- KODE -->

                                <td>

                                    <strong class="text-primary font-monospace">

                                        <?php

                                        echo html_escape(

                                            $sd['kode_sampel_manual']

                                            ?: (

                                                $sd['no']

                                                ? 'NO-' . $sd['no']

                                                : 'SMP-' . $sd['id']

                                            )

                                        );

                                        ?>

                                    </strong>

                                </td>


                                <!-- NAMA SAMPLE -->

                                <td>

                                    <strong class="text-dark">

                                        <?php

                                        echo html_escape(
                                            $sd['nama_sampel']
                                        );

                                        ?>

                                    </strong>

                                </td>


                                <!-- KATEGORI -->

                                <td>

                                    <?php

                                    echo html_escape(
                                        $sd['kategori_sampel'] ?: '-'
                                    );

                                    ?>

                                </td>


                                <!-- STATUS -->

                                <td>

                                    <span class="badge-status badge-sedang-diuji">

                                        <?php

                                        echo html_escape(
                                            $sd['status']
                                        );

                                        ?>

                                    </span>

                                </td>


                                <!-- AKSI -->

                                <td>

                                    <a
                                        href="<?php echo site_url('pengujian/proses/' . $sd['id']); ?>"
                                        class="btn btn-warning btn-sm fw-semibold"
                                    >

                                        Lanjutkan Pengujian &rarr;

                                    </a>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        </div>

    <?php endif; ?>


    <!-- =====================================================
         DAFTAR SAMPEL TERSEDIA
         ===================================================== -->

    <div class="antrean-card">


        <!-- HEADER TABEL + SEARCH SEJAJAR -->

        <div class="antrean-card-header">

            <h2 class="antrean-card-title">
                Sampel Tersedia
            </h2>


            <!-- SEARCH DATATABLE -->

            <div class="antrean-search" id="search-antrean">
    <input
        type="text"
        id="search-sampel"
        class="form-control"
        placeholder="Cari sampel..."
        autocomplete="off"
    >
</div>
        </div>


        <!-- =================================================
             TABEL
             ================================================= -->

        <div class="antrean-table-wrapper">

            <table
                id="tabel-antrean"
                class="table align-middle"
            >

                <thead>

                    <tr>

                        <th>Kode / No</th>

                        <th>Produk / Kategori</th>

                        <th>Pengirim / Sarana</th>

                        <th>Petugas Input</th>

                        <th>Status</th>

                        <th>Aksi</th>

                    </tr>

                </thead>


                <tbody>


                    <?php if (!empty($antrean_sampel)): ?>


                        <?php foreach ($antrean_sampel as $s): ?>


                            <tr>


                                <!-- =================================
                                     KODE SAMPLE
                                     ================================= -->

                                <td>

                                    <span class="kode-sampel">

                                        <?php

                                        echo html_escape(

                                            $s['kode_sampel_manual']

                                            ?: (

                                                $s['no']

                                                ? 'NO-' . $s['no']

                                                : 'SMP-' . $s['id']

                                            )

                                        );

                                        ?>

                                    </span>

                                </td>


                                <!-- =================================
                                     PRODUK
                                     ================================= -->

                                <td>

                                    <span class="nama-sampel">

                                        <?php

                                        echo html_escape(
                                            $s['nama_sampel']
                                        );

                                        ?>

                                    </span>


                                    <span class="kategori-sampel">

                                        <?php

                                        echo html_escape(
                                            $s['kategori_sampel'] ?: '-'
                                        );

                                        ?>

                                    </span>

                                </td>


                                <!-- =================================
                                     SARANA
                                     ================================= -->

                                <td>

                                    <?php

                                    echo html_escape(
                                        $s['nama_sarana'] ?: '-'
                                    );

                                    ?>

                                </td>


                                <!-- =================================
                                     PETUGAS INPUT
                                     ================================= -->

                                <td>

                                    <small>

                                        <?php

                                        echo html_escape(
                                            $s['nama_pembuat'] ?: 'Sistem'
                                        );

                                        ?>

                                    </small>

                                </td>


                                <!-- =================================
                                     STATUS
                                     ================================= -->

                                <td>

                                    <span class="status-menunggu">

                                        BELUM DIAMBIL

                                    </span>

                                </td>


                                <!-- =================================
                                     AKSI
                                     ================================= -->

                                <td>

                                    <a
                                        href="<?php echo site_url('pengujian/klaim/' . $s['id']); ?>"
                                        class="btn-ambil-sampel"
                                        onclick="return confirm('Klaim dan ambil pekerjaan pengujian sampel ini?');"
                                    >

                                        Ambil Sampel

                                    </a>

                                </td>


                            </tr>


                        <?php endforeach; ?>


                    <?php else: ?>


                        <tr>

                            <td
                                colspan="6"
                                class="text-center text-muted py-4"
                            >

                                Belum ada sampel baru dalam antrean
                                "Menunggu Pengujian".

                            </td>

                        </tr>


                    <?php endif; ?>


                </tbody>

            </table>

        </div>

    </div>


    <!-- =====================================================
         INFORMASI
         ===================================================== -->

    <div class="antrean-info">


        <div class="antrean-info-icon">
            i
        </div>


        <div>

            <div class="antrean-info-title">
                Perhatian saat mengambil sampel
            </div>


            <div class="antrean-info-text">

                Setelah tombol Ambil Sampel digunakan, sampel akan menjadi
                tanggung jawab Anda dan hanya muncul pada halaman Sampel Saya
                milik penguji tersebut.

            </div>

        </div>

    </div>


    <!-- =====================================================
         RIWAYAT
         ===================================================== -->

    <div class="text-end mt-3 mb-2">

        <a
            href="<?php echo site_url('pengujian/riwayat'); ?>"
            class="btn btn-outline-secondary btn-sm"
        >

            Riwayat Hasil Pengujian

        </a>

    </div>


</div>


<!-- =========================================================
     DATATABLE
     ========================================================= -->

<script>

document.addEventListener('DOMContentLoaded', function() {

    if (typeof $.fn.DataTable !== 'undefined') {

        var tabelAntrean = $('#tabel-antrean').DataTable({

            language: {
                url: '<?php echo base_url('assets/js/dataTables.indonesian.json'); ?>'
            },

            pageLength: 9,

            lengthChange: false,

            ordering: false,

            /*
             * Tidak menggunakan search bawaan DataTables.
             * Search dibuat sendiri di bagian header.
             */
            dom: 'rt<"antrean-datatables-footer"ip>'

        });


        /*
         * Search custom
         * tetap menggunakan fungsi pencarian DataTables.
         */

        $('#search-sampel').on('keyup', function() {

            tabelAntrean
                .search(this.value)
                .draw();

        });

    }

});

</script>