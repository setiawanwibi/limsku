<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
    /* =========================================================
       DAFTAR SAMPEL
       Tampilan saja - fungsi tetap sama
       ========================================================= */

    .sampel-page {
        color: #294257;
        font-size: 0.88rem;
    }

    /* =========================================================
       FLASH MESSAGE
       ========================================================= */

    .sampel-page .alert {
        border-radius: 8px;
        font-size: 0.78rem;
        margin-bottom: 14px;
    }

    /* =========================================================
       BAGIAN ATAS
       ========================================================= */

    .sampel-topbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 16px;
    }

    .sampel-top-info {
        font-size: 0.76rem;
        color: #71869a;
        margin: 0;
        line-height: 1.5;
    }

    .sampel-top-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-shrink: 0;
    }

    /* =========================================================
       BUTTON IMPORT
       ========================================================= */

    .btn-import-excel {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        height: 36px;
        padding: 0 13px;
        border: 1px solid #d5e0e7;
        border-radius: 7px;
        background: #fff;
        color: #29465d;
        font-size: 0.75rem;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.15s ease;
    }

    .btn-import-excel:hover {
        background: #f7f9fa;
        color: #203d54;
        border-color: #bdccd6;
    }

    /* Logo Excel */
    .btn-import-excel .excel-icon {
        width: 18px;
        height: 18px;
        flex: 0 0 18px;
        display: block;
    }

    /* =========================================================
       BUTTON TERIMA SAMPEL
       ========================================================= */

    .btn-terima-sampel {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        height: 36px;
        padding: 0 15px;
        border: 1px solid #159b98;
        border-radius: 7px;
        background: #159b98;
        color: #fff;
        font-size: 0.75rem;
        font-weight: 700;
        text-decoration: none;
        transition: all 0.15s ease;
    }

    .btn-terima-sampel:hover {
        background: #128a87;
        border-color: #128a87;
        color: #fff;
    }

    .btn-plus {
        font-size: 1rem;
        line-height: 1;
        font-weight: 400;
    }

    /* =========================================================
       CARD TABEL
       ========================================================= */

    .sampel-table-card {
        background: #fff;
        border: 1px solid #e0e8ed;
        border-radius: 10px;
        box-shadow: 0 5px 18px rgba(35, 58, 76, 0.07);
        overflow: hidden;
    }

    /* =========================================================
       HEADER SEARCH
       ========================================================= */

    .sampel-table-header {
        padding: 11px 14px;
        border-bottom: 1px solid #e8eef2;
        display: flex !important;
        align-items: center !important;
        width: 100%;
    }

    /* =========================================================
       SEARCH CUSTOM
       ========================================================= */

    .sampel-search {
        width: 100%;
        flex: 1 1 100%;
    }

    #search-sampel {
        width: 100%;
        height: 36px;
        margin: 0;
        padding: 7px 13px;
        border: 1px solid #e1e8ed;
        border-radius: 6px;
        background: #f7f9fa;
        color: #526576;
        font-size: 0.78rem;
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
       TABLE WRAPPER
       ========================================================= */

    .sampel-table-wrapper {
        overflow-x: auto;
    }

    /* =========================================================
       TABLE
       ========================================================= */

    #tabel-sampel {
        width: 100% !important;
        margin: 0 !important;
        border-collapse: separate;
        border-spacing: 0;
    }

    #tabel-sampel thead th {
        background: #f7f9fa;
        border-top: 1px solid #edf1f3;
        border-bottom: 1px solid #e1e8ed;
        padding: 10px 11px;
        font-size: 0.70rem;
        font-weight: 700;
        color: #7b8e9d;
        text-transform: uppercase;
        white-space: nowrap;
    }

    #tabel-sampel tbody td {
        padding: 11px;
        border-bottom: 1px solid #e7edf0;
        font-size: 0.76rem;
        color: #526779;
        vertical-align: middle;
    }

    #tabel-sampel tbody tr:last-child td {
        border-bottom: 0;
    }

    #tabel-sampel tbody tr {
        transition: background 0.12s ease;
    }

    #tabel-sampel tbody tr:hover {
        background: #fbfcfd;
    }

    /* =========================================================
       KODE SAMPEL
       ========================================================= */

    .sampel-kode {
        display: block;
        color: #24455f;
        font-size: 0.78rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .sampel-tanggal {
        display: block;
        margin-top: 3px;
        color: #94a2ad;
        font-size: 0.65rem;
        white-space: nowrap;
    }

    /* =========================================================
       NAMA SAMPEL
       ========================================================= */

    .sampel-nama {
        display: block;
        color: #263f55;
        font-size: 0.80rem;
        font-weight: 700;
        text-decoration: none;
        line-height: 1.4;
    }

    .sampel-nama:hover {
        color: #159b98;
    }

    .sampel-subkategori {
        display: block;
        margin-top: 3px;
        color: #8797a4;
        font-size: 0.68rem;
    }

    /* =========================================================
       KATEGORI
       ========================================================= */

    .sampel-kategori {
        color: #617486;
        font-size: 0.72rem;
        line-height: 1.4;
    }

    .sampel-subkategori-kategori {
        display: block;
        color: #9aa7b1;
        font-size: 0.65rem;
        margin-top: 2px;
    }

    /* =========================================================
       SARANA / PENGIRIM
       ========================================================= */

    .sampel-sarana {
        color: #566c7e;
        font-size: 0.74rem;
        line-height: 1.4;
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
        font-size: 0.66rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .badge-status::before {
        content: "";
        width: 6px;
        height: 6px;
        border-radius: 50%;
        display: inline-block;
    }

    .badge-menunggu-pengujian {
        background: #e9f2ff;
        color: #3977ba;
    }

    .badge-menunggu-pengujian::before {
        background: #3977ba;
    }

    .badge-sedang-diuji {
        background: #e3f6f4;
        color: #168d88;
    }

    .badge-sedang-diuji::before {
        background: #168d88;
    }

    .badge-menunggu-verifikasi {
        background: #fff3d9;
        color: #ba7c09;
    }

    .badge-menunggu-verifikasi::before {
        background: #eba900;
    }

    .badge-menunggu-approval {
        background: #f0e8fb;
        color: #7850ad;
    }

    .badge-menunggu-approval::before {
        background: #8154b8;
    }

    .badge-approved-final {
        background: #e5f6e9;
        color: #268447;
    }

    .badge-approved-final::before {
        background: #268447;
    }

    .badge-ditolak {
        background: #fde8e8;
        color: #bd4d4d;
    }

    .badge-ditolak::before {
        background: #bd4d4d;
    }

    /* =========================================================
       PETUGAS INPUT
       ========================================================= */

    .sampel-petugas {
        color: #6c7f8e;
        font-size: 0.70rem;
    }

    /* =========================================================
       AKSI
       ========================================================= */

    .sampel-action {
        text-align: right;
        white-space: nowrap;
    }

    .btn-action-menu {
        width: 30px;
        height: 30px;
        padding: 0;
        border: 0;
        border-radius: 7px;
        background: #f7f9fa;
        color: #738795;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
        line-height: 1;
    }

    .btn-action-menu:hover,
    .btn-action-menu:focus {
        background: #edf2f4;
        color: #3e5669;
        box-shadow: none;
    }

    .sampel-action .dropdown-menu {
        min-width: 140px;
        padding: 5px;
        border: 1px solid #e0e7eb;
        border-radius: 7px;
        box-shadow: 0 7px 18px rgba(35, 55, 70, 0.12);
    }

    .sampel-action .dropdown-item {
        padding: 7px 9px;
        border-radius: 5px;
        font-size: 0.72rem;
        color: #52687a;
    }

    .sampel-action .dropdown-item:hover {
        background: #f4f7f8;
    }

    .sampel-action .dropdown-item.text-danger:hover {
        background: #fff0f0;
    }

    /* =========================================================
       DATATABLE FOOTER
       ========================================================= */

    .sampel-datatables-footer {
        min-height: 42px;
        padding: 8px 14px;
        border-top: 1px solid #e8eef2;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .sampel-datatables-footer .dataTables_info {
        padding-top: 0 !important;
        color: #81919d !important;
        font-size: 0.68rem !important;
    }

    .sampel-datatables-footer .dataTables_paginate {
        padding-top: 0 !important;
        margin: 0 !important;
        border: 0 !important;
        background: transparent !important;
        box-shadow: none !important;
    }

    /* Wrapper pagination: BUKAN tombol */
    .sampel-datatables-footer .dataTables_paginate .paginate_button {
        min-width: 0 !important;
        width: auto !important;
        height: auto !important;
        padding: 0 !important;
        margin: 0 0 0 3px !important;
        border: 0 !important;
        border-radius: 0 !important;
        background: transparent !important;
        color: inherit !important;
        box-shadow: none !important;
        outline: none !important;
    }

    /* Hanya page-link yang menjadi tombol */
    .sampel-datatables-footer .dataTables_paginate .paginate_button .page-link {
        min-width: 27px !important;
        height: 27px !important;
        padding: 4px 8px !important;
        margin: 0 !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        border: 1px solid #dce5ea !important;
        border-radius: 5px !important;
        background: #fff !important;
        color: #557083 !important;
        box-shadow: none !important;
        outline: none !important;
        background-image: none !important;
        text-shadow: none !important;
    }

    /* Halaman aktif */
    .sampel-datatables-footer .dataTables_paginate .paginate_button.current .page-link {
        background: #159b98 !important;
        border-color: #159b98 !important;
        color: #fff !important;
    }

    /* Hover wrapper tetap transparan */
    .sampel-datatables-footer .dataTables_paginate .paginate_button:not(.disabled):hover,
    .sampel-datatables-footer .dataTables_paginate .paginate_button:not(.disabled):focus {
        border: 0 !important;
        background: transparent !important;
        box-shadow: none !important;
    }

    /* Hover tombol */
    .sampel-datatables-footer .dataTables_paginate .paginate_button:not(.disabled) .page-link:hover {
        background: #f7f9fa !important;
        border-color: #cbd8df !important;
        color: #3e5669 !important;
    }

    /* Previous / Next disabled */
    .sampel-datatables-footer .dataTables_paginate .paginate_button.disabled .page-link {
        background: #f7f9fa !important;
        border-color: #dce5ea !important;
        color: #9aa7b2 !important;
        opacity: 1 !important;
    }

    /* Matikan pseudo-element bawaan */
    .sampel-datatables-footer .paginate_button::before,
    .sampel-datatables-footer .paginate_button::after,
    .sampel-datatables-footer .page-link::before,
    .sampel-datatables-footer .page-link::after {
        display: none !important;
        content: none !important;
    }

    /* =========================================================
       EMPTY DATA
       ========================================================= */

    .sampel-empty {
        padding: 35px 15px !important;
        color: #8a99a5 !important;
        font-size: 0.74rem !important;
    }

    /* =========================================================
       RESPONSIVE
       ========================================================= */

    @media (max-width: 768px) {

        .sampel-topbar {
            flex-direction: column;
            align-items: stretch;
        }

        .sampel-top-actions {
            width: 100%;
        }

        .btn-import-excel,
        .btn-terima-sampel {
            flex: 1;
        }

        .sampel-table-header {
            justify-content: stretch !important;
        }

        .sampel-search {
            width: 100%;
            flex: 1;
        }

        .sampel-datatables-footer {
            flex-direction: column;
            align-items: flex-start;
            gap: 6px;
        }

    }
</style>


<div class="sampel-page">

    <!-- =====================================================
         FLASH MESSAGE
         ===================================================== -->

    <?php if ($this->session->flashdata('pesan_sukses')): ?>

        <div class="alert alert-success alert-dismissible fade show" role="alert">

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

        <div class="alert alert-danger alert-dismissible fade show" role="alert">

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
         BAGIAN ATAS
         ===================================================== -->

    <div class="sampel-topbar">

        <p class="sampel-top-info">

            Ada
            <?php echo !empty($daftar_sampel) ? count($daftar_sampel) : 0; ?>
            sampel baru yang perlu divalidasi sebelum dikirim ke Penguji.

        </p>


        <?php 
        $role_id_user = $this->session->userdata('role_id');
        $bisa_tambah = $this->Model_Hak_Akses->memiliki_akses($role_id_user, 'sampel_input');
        $bisa_import = $this->Model_Hak_Akses->memiliki_akses($role_id_user, 'sampel_import');
        ?>

        <?php if ($bisa_tambah || $bisa_import): ?>
        <div class="sampel-top-actions">

            <!-- IMPORT EXCEL -->
            <?php if ($bisa_import): ?>
            <a
                href="<?php echo site_url('sampel/import'); ?>"
                class="btn-import-excel"
            >

                <!-- Logo Microsoft Excel -->
                <svg
                    class="excel-icon"
                    viewBox="0 0 24 24"
                    aria-hidden="true"
                >
                    <!-- Lembar kanan -->
                    <path
                        fill="#217346"
                        d="M14 2h6a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2h-6V2z"
                    />

                    <!-- Bagian kiri -->
                    <path
                        fill="#185C37"
                        d="M4 4h10v16H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2z"
                    />

                    <!-- X putih -->
                    <path
                        fill="#FFFFFF"
                        d="M6.1 7.2h2l1.3 2.3 1.3-2.3h2l-2.3 3.5 2.4 3.7h-2l-1.4-2.5-1.4 2.5h-2l2.4-3.7-2.3-3.5z"
                    />

                    <!-- Garis worksheet -->
                    <path
                        fill="#33A852"
                        d="M14 7h5v2h-5V7zm0 4h5v2h-5v-2zm0 4h5v2h-5v-2z"
                    />
                </svg>

                Import Excel

            </a>
            <?php endif; ?>


            <!-- TERIMA SAMPEL BARU -->
            <?php if ($bisa_tambah): ?>
            <a
                href="<?php echo site_url('sampel/tambah'); ?>"
                class="btn-terima-sampel"
            >

                <span class="btn-plus">+</span>

                Terima Sampel Baru

            </a>
            <?php endif; ?>

        </div>
        <?php endif; ?>

    </div>


    <!-- =====================================================
         CARD TABEL
         ===================================================== -->

    <div class="sampel-table-card">


        <!-- =================================================
             SEARCH
             ================================================= -->

        <div class="sampel-table-header">

            <div class="sampel-search">

                <input
                    type="text"
                    id="search-sampel"
                    placeholder="Cari sampel..."
                    autocomplete="off"
                >

            </div>

        </div>


        <!-- =================================================
             TABLE
             ================================================= -->

        <div class="sampel-table-wrapper">

            <table
                id="tabel-sampel"
                class="table align-middle"
            >

                <thead>

                    <tr>

                        <th>
                            Kode / No
                        </th>

                        <th>
                            Produk / Kategori
                        </th>

                        <th>
                            Pengirim
                        </th>

                        <th>
                            Status
                        </th>

                        <th>
                            Petugas Input
                        </th>

                        <th class="text-end">
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody>

                    <?php if (!empty($daftar_sampel)): ?>


                        <?php foreach ($daftar_sampel as $s): ?>


                            <tr>


                                <!-- =================================
                                     KODE / NO
                                     ================================= -->

                                <td>

                                    <span class="sampel-kode">

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


                                    <?php if (!empty($s['created_at'])): ?>

                                        <span class="sampel-tanggal">

                                            <?php echo html_escape($s['created_at']); ?>

                                        </span>

                                    <?php else: ?>

                                        <span class="sampel-tanggal">
                                            Sampel
                                        </span>

                                    <?php endif; ?>

                                </td>


                                <!-- =================================
                                     PRODUK / KATEGORI
                                     ================================= -->

                                <td>

                                    <a
                                        href="<?php echo site_url('sampel/detail/' . $s['id']); ?>"
                                        class="sampel-nama"
                                    >

                                        <?php echo html_escape($s['nama_sampel']); ?>

                                    </a>


                                    <span class="sampel-subkategori">

                                        <?php

                                        echo html_escape(
                                            $s['sub_kategori'] ?: ''
                                        );

                                        ?>

                                    </span>

                                </td>


                                <!-- =================================
                                     PENGIRIM
                                     ================================= -->

                                <td>

                                    <span class="sampel-sarana">

                                        <?php

                                        echo html_escape(
                                            $s['nama_sarana'] ?: '-'
                                        );

                                        ?>

                                    </span>

                                </td>


                                <!-- =================================
                                     STATUS
                                     ================================= -->

                                <td>

                                    <?php

                                    $st = $s['status'];

                                    $b_class = 'badge-menunggu-pengujian';

                                    if ($st === 'Sedang Diuji') {

                                        $b_class = 'badge-sedang-diuji';

                                    } elseif ($st === 'Menunggu Verifikasi') {

                                        $b_class = 'badge-menunggu-verifikasi';

                                    } elseif ($st === 'Menunggu Approval') {

                                        $b_class = 'badge-menunggu-approval';

                                    } elseif ($st === 'Approved / Final') {

                                        $b_class = 'badge-approved-final';

                                    } elseif ($st === 'Ditolak') {

                                        $b_class = 'badge-ditolak';

                                    }

                                    ?>


                                    <span
                                        class="badge-status <?php echo $b_class; ?>"
                                    >

                                        <?php echo html_escape($st); ?>

                                    </span>

                                </td>


                                <!-- =================================
                                     PETUGAS INPUT
                                     ================================= -->

                                <td>

                                    <span class="sampel-petugas">

                                        <?php

                                        echo html_escape(
                                            $s['nama_pembuat'] ?: 'Sistem'
                                        );

                                        ?>

                                    </span>

                                </td>


                                <!-- =================================
                                     AKSI
                                     ================================= -->

                                <td class="sampel-action">

                                    <div class="dropdown">

                                        <button
                                            type="button"
                                            class="btn-action-menu dropdown-toggle"
                                            data-bs-toggle="dropdown"
                                            aria-expanded="false"
                                            title="Aksi"
                                        >
                                            ⋮
                                        </button>


                                        <ul class="dropdown-menu dropdown-menu-end">


                                            <!-- DETAIL -->

                                            <li>

                                                <a
                                                    class="dropdown-item"
                                                    href="<?php echo site_url('sampel/detail/' . $s['id']); ?>"
                                                >

                                                    Detail Sampel

                                                </a>

                                            </li>


                                            <!-- EDIT -->
                                            <?php if ($this->Model_Hak_Akses->memiliki_akses($role_id_user, 'sampel_edit')): ?>
                                             <li>

                                                 <a
                                                     class="dropdown-item"
                                                     href="<?php echo site_url('sampel/edit/' . $s['id']); ?>"
                                                 >

                                                     Edit Sampel

                                                 </a>

                                             </li>
                                            <?php endif; ?>


                                             <!-- HAPUS -->
                                            <?php if ($this->Model_Hak_Akses->memiliki_akses($role_id_user, 'sampel_delete')): ?>
                                             <li>

                                                 <a
                                                     class="dropdown-item text-danger"
                                                     href="<?php echo site_url('sampel/hapus/' . $s['id']); ?>"
                                                     onclick="return confirm('Apakah Anda yakin ingin menghapus data sampel ini?');"
                                                 >

                                                     Hapus Sampel

                                                 </a>

                                             </li>
                                            <?php endif; ?>


                                        </ul>

                                    </div>

                                </td>


                            </tr>


                        <?php endforeach; ?>


                    <?php else: ?>


                        <tr>

                            <td
                                colspan="6"
                                class="text-center sampel-empty"
                            >

                                Belum ada data sampel yang terdaftar.

                            </td>

                        </tr>


                    <?php endif; ?>


                </tbody>

            </table>

        </div>


    </div>

</div>


<!-- =========================================================
     DATATABLE
     ========================================================= -->

<script>
document.addEventListener('DOMContentLoaded', function() {

    if (typeof $.fn.DataTable === 'undefined') {
        return;
    }

    /* Hapus instance lama jika sudah pernah dibuat */
    if ($.fn.DataTable.isDataTable('#tabel-sampel')) {
        $('#tabel-sampel').DataTable().destroy();
    }

    /* Buat satu DataTable */
    var tabelSampel = $('#tabel-sampel').DataTable({

        order: [[0, 'asc']],

        /* Maksimal 15 data per halaman */
        pageLength: 15,

        language: {
            url: '<?php echo base_url('assets/js/dataTables.indonesian.json'); ?>'
        },

        /* Search bawaan dihilangkan */
        dom: 'rt<"sampel-datatables-footer"ip>'

    });

    /* Search custom */
    $('#search-sampel')
        .off('keyup.sampel')
        .on('keyup.sampel', function() {
            tabelSampel.search(this.value).draw();
        });

});
</script>