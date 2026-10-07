<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style>
    /* =========================================================
       HALAMAN ANTREAN PENGUJIAN
       Tampilan saja - tidak mengubah fungsi
       ========================================================= */

    .antrean-page {
        color: #26384a;
    }

    /* =========================================================
       HEADER HALAMAN
       ========================================================= */

    .antrean-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 18px;
    }

    .antrean-title {
        font-size: 1.45rem;
        font-weight: 700;
        color: #24384b;
        margin-bottom: 4px;
    }

    .antrean-subtitle {
        font-size: 0.78rem;
        color: #7b8a99;
        margin-bottom: 0;
    }

    /* =========================================================
       SUMMARY BADGE
       ========================================================= */

    .antrean-summary {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .summary-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        border-radius: 20px;
        font-size: 0.68rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .summary-badge .dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        display: inline-block;
    }

    .badge-belum {
        background: #fff4da;
        color: #b97900;
    }

    .badge-belum .dot {
        background: #efa900;
    }

    .badge-sedang {
        background: #e4f6f5;
        color: #16857f;
    }

    .badge-sedang .dot {
        background: #18a39a;
    }

    /* =========================================================
       CARD
       ========================================================= */

    .antrean-card {
        background: #fff;
        border: 1px solid #e2e9ee;
        border-radius: 10px;
        box-shadow: 0 3px 12px rgba(34, 55, 73, 0.06);
        overflow: hidden;
    }

    /* =========================================================
       HEADER TABEL + SEARCH
       ========================================================= */

    .antrean-card-header {
    padding: 13px 14px;
    display: flex !important;
    flex-direction: row !important;
    align-items: center !important;
    justify-content: space-between !important;
    gap: 15px;
    background: #fff;
    border-bottom: 1px solid #edf1f3;
    width: 100%;
}

    .antrean-card-title {
        font-size: 0.82rem;
        font-weight: 700;
        color: #2b4053;
        margin: 0;
        white-space: nowrap;
    }

    /* =========================================================
       SEARCH DATATABLE
       ========================================================= */

.antrean-search {
    width: 240px;
    flex: 0 0 240px;
    margin-left: auto;
}

#search-sampel {
    width: 100%;
    height: 34px;
    margin: 0;
    padding: 7px 12px;
    border: 1px solid #e1e8ed;
    border-radius: 6px;
    background: #f7f9fa;
    color: #526576;
    font-size: 0.68rem;
    outline: none;
    box-shadow: none;
}

#search-sampel::placeholder {
    color: #9aa7b2;
}

#search-sampel:focus {
    border-color: #b9cbd7;
    background: #fff;
    box-shadow: none;
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
        background: #f7f9fa;
        border-top: 1px solid #edf1f3;
        border-bottom: 1px solid #e5ebef;
        padding: 9px 10px;
        font-size: 0.56rem;
        font-weight: 700;
        color: #83909b;
        text-transform: uppercase;
        white-space: nowrap;
    }

    #tabel-antrean tbody td {
        padding: 11px 10px;
        border-bottom: 1px solid #e8edf0;
        font-size: 0.67rem;
        color: #536474;
        vertical-align: middle;
    }

    #tabel-antrean tbody tr:last-child td {
        border-bottom: 0;
    }

    #tabel-antrean tbody tr:hover {
        background: #fbfcfd;
    }

    /* =========================================================
       DATA SAMPLE
       ========================================================= */

    .kode-sampel {
        font-size: 0.68rem;
        font-weight: 700;
        color: #28465e;
        white-space: nowrap;
    }

    .nama-sampel {
        font-size: 0.69rem;
        font-weight: 700;
        color: #263e52;
        display: block;
    }

    .kategori-sampel {
        display: block;
        font-size: 0.57rem;
        color: #8c99a3;
        margin-top: 2px;
    }

    /* =========================================================
       STATUS
       ========================================================= */

    .status-menunggu {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: #fff4da;
        color: #b67a08;
        border-radius: 20px;
        padding: 5px 9px;
        font-size: 0.58rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .status-menunggu::before {
        content: "";
        width: 6px;
        height: 6px;
        background: #eda900;
        border-radius: 50%;
    }

    /* =========================================================
       BUTTON AMBIL SAMPEL
       ========================================================= */

    .btn-ambil-sampel {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 6px 11px;
        border: 1px solid #cbd9e2;
        background: #fff;
        color: #344e63;
        border-radius: 6px;
        font-size: 0.61rem;
        font-weight: 700;
        text-decoration: none;
        white-space: nowrap;
        transition: all 0.15s ease;
    }

    .btn-ambil-sampel:hover {
        background: #f3f7f9;
        color: #243e54;
        border-color: #aebfca;
    }

    /* =========================================================
       SAMPEL SAYA
       ========================================================= */

    .pekerjaan-card {
        margin-bottom: 18px;
        border: 1px solid #e2e9ee;
        border-left: 4px solid #efa900;
        border-radius: 9px;
        overflow: hidden;
        background: #fff;
    }

    .pekerjaan-card-header {
        padding: 11px 14px;
        background: #fff;
        border-bottom: 1px solid #edf1f3;
    }

    .pekerjaan-card-title {
        margin: 0;
        font-size: 0.75rem;
        font-weight: 700;
        color: #33495b;
    }

    .pekerjaan-table {
        margin-bottom: 0;
    }

    .pekerjaan-table th {
        font-size: 0.57rem;
        color: #84919b;
        background: #f7f9fa;
        padding: 8px 10px;
    }

    .pekerjaan-table td {
        font-size: 0.65rem;
        padding: 9px 10px;
    }

    .badge-status {
        display: inline-block;
        padding: 5px 8px;
        border-radius: 20px;
        font-size: 0.57rem;
        font-weight: 700;
    }

    .badge-sedang-diuji {
        background: #e4f6f5;
        color: #16857f;
    }

    /* =========================================================
       INFORMASI
       ========================================================= */

    .antrean-info {
        margin-top: 13px;
        padding: 12px 14px;
        border: 1px solid #dce8f3;
        background: #edf5fc;
        border-radius: 9px;
        display: flex;
        align-items: flex-start;
        gap: 9px;
    }

    .antrean-info-icon {
        width: 19px;
        height: 19px;
        min-width: 19px;
        border-radius: 50%;
        border: 1px solid #bdd3e8;
        color: #5487b4;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.68rem;
        font-weight: 700;
    }

    .antrean-info-title {
        font-size: 0.62rem;
        font-weight: 700;
        color: #5281ab;
        margin-bottom: 2px;
    }

    .antrean-info-text {
        font-size: 0.57rem;
        color: #73899b;
        line-height: 1.45;
    }

    /* =========================================================
       DATATABLE FOOTER
       ========================================================= */

    .antrean-datatables-footer {
        padding: 8px 12px;
        border-top: 1px solid #edf1f3;
        font-size: 0.59rem;
    }

    .dataTables_info {
        font-size: 0.59rem !important;
        color: #84929d !important;
        padding-top: 7px !important;
    }

    .dataTables_paginate {
        font-size: 0.59rem !important;
        padding-top: 4px !important;
    }

    .dataTables_paginate .paginate_button {
        padding: 3px 7px !important;
        border-radius: 4px !important;
    }

    /* =========================================================
       FLASH MESSAGE
       ========================================================= */

    .flash-message {
        border-radius: 7px;
        font-size: 0.72rem;
    }

    /* =========================================================
       RESPONSIVE
       ========================================================= */

    @media (max-width: 700px) {

        .antrean-header {
            flex-direction: column;
        }

        .antrean-summary {
            width: 100%;
        }

        .antrean-card-header {
            flex-direction: column;
            align-items: stretch;
        }

        .antrean-search {
            width: 100%;
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