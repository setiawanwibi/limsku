<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
    /* =========================================================
       MASTER TEMPLATE FORM PENGUJIAN
       Visual redesign only
       ========================================================= */

    .template-page {
        color: #294257;
        font-size: .88rem;
    }


    /* =========================================================
       FLASH MESSAGE
       ========================================================= */

    .template-page .alert {
        border-radius: 9px;
        font-size: .80rem;
        line-height: 1.55;
        border-width: 1px;
        box-shadow: 0 3px 10px rgba(35, 58, 76, .04);
    }


    /* =========================================================
       MAIN CARD
       ========================================================= */

    .template-card {
        overflow: hidden;
        background: #fff;
        border: 1px solid #e0e8ed;
        border-radius: 11px;
        box-shadow: 0 5px 18px rgba(35, 58, 76, .06);
    }


    /* =========================================================
       HEADER
       ========================================================= */

    .template-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;

        padding: 17px 20px;

        border-bottom: 1px solid #e5ebee;
        background: #fff;
    }

    .template-header-main {
        min-width: 0;
    }

    .template-title {
        display: flex;
        align-items: center;
        gap: 8px;

        margin: 0;

        color: #294257;
        font-size: 1rem;
        font-weight: 700;
        line-height: 1.4;
    }

    .template-title i {
        color: #159b98;
        font-size: 1.05rem;
    }

    .template-subtitle {
        margin: 5px 0 0;

        color: #788b97;
        font-size: .73rem;
        line-height: 1.5;
    }


    /* =========================================================
       HEADER BUTTONS
       ========================================================= */

    .template-header-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 7px;

        flex-shrink: 0;
    }

    .template-preset-btn,
    .template-add-btn {
        min-height: 34px;

        padding: 7px 11px;

        border-radius: 7px;

        font-size: .68rem;
        font-weight: 700;

        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;

        white-space: nowrap;
        text-decoration: none;

        transition: .18s ease;
    }

    .template-preset-btn {
        border: 1px solid #cfe4e2;
        background: #f3faf9;
        color: #168f8b;
    }

    .template-preset-btn:hover {
        border-color: #b8d8d5;
        background: #eaf7f6;
        color: #127f7c;
    }

    .template-add-btn {
        border: 1px solid #159b98;
        background: #159b98;
        color: #fff;

        box-shadow: 0 3px 7px rgba(21, 155, 152, .12);
    }

    .template-add-btn:hover {
        border-color: #118b88;
        background: #118b88;
        color: #fff;
        transform: translateY(-1px);
    }


    /* =========================================================
       BODY
       ========================================================= */

    .template-body {
        padding: 18px 20px 20px;
    }


    /* =========================================================
       TABLE WRAPPER
       ========================================================= */

    .template-table-wrap {
        border: 1px solid #e3eaee;
        border-radius: 8px;
        overflow: hidden;
        background: #fff;
    }

    .template-table {
        margin: 0;
        color: #536b79;
        font-size: .74rem;
    }


    /* =========================================================
       TABLE HEADER
       ========================================================= */

    .template-table thead th {
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


    /* =========================================================
       TABLE BODY
       ========================================================= */

    .template-table tbody td {
        padding: 12px;

        border-color: #edf1f3;

        vertical-align: middle;
    }

    .template-table tbody tr {
        transition: .16s ease;
    }

    .template-table tbody tr:hover {
        background: #f8fbfb;
    }


    /* =========================================================
       NUMBER
       ========================================================= */

    .template-number {
        color: #8a9aa4;
        font-size: .68rem;
        font-weight: 600;
    }


    /* =========================================================
       TEMPLATE CODE
       ========================================================= */

    .template-code {
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
       TEMPLATE NAME
       ========================================================= */

    .template-name {
        color: #3f5868;
        font-size: .74rem;
        font-weight: 700;
        line-height: 1.4;
    }


    /* =========================================================
       METHOD
       ========================================================= */

    .template-method {
        color: #607683;
        font-size: .70rem;
        line-height: 1.4;
    }


    /* =========================================================
       CATEGORY BADGE
       ========================================================= */

    .template-category {
        display: inline-flex;
        align-items: center;
        justify-content: center;

        min-height: 24px;

        padding: 4px 8px;

        border-radius: 20px;

        font-size: .61rem;
        font-weight: 700;

        white-space: nowrap;
    }

    .template-category.kimia {
        border: 1px solid #cde8e7;
        background: #eef8f7;
        color: #168f8b;
    }

    .template-category.micro {
        border: 1px solid #f0dfb7;
        background: #fff8e9;
        color: #a97816;
    }


    /* =========================================================
       STATUS BADGE
       ========================================================= */

    .template-status {
        display: inline-flex;
        align-items: center;
        justify-content: center;

        min-height: 24px;

        padding: 4px 8px;

        border-radius: 20px;

        font-size: .61rem;
        font-weight: 700;

        white-space: nowrap;
    }

    .template-status.active {
        border: 1px solid #cde7d5;
        background: #edf8f0;
        color: #33804e;
    }

    .template-status.inactive {
        border: 1px solid #dfe5e8;
        background: #f4f6f7;
        color: #72838e;
    }


    /* =========================================================
       ACTION BUTTONS
       ========================================================= */

    .template-actions {
        display: flex;
        align-items: center;
        gap: 5px;

        white-space: nowrap;
    }

    .template-action-btn {
        min-height: 29px;

        padding: 5px 8px;

        border-radius: 6px;

        font-size: .62rem;
        font-weight: 700;

        display: inline-flex;
        align-items: center;
        justify-content: center;

        text-decoration: none;

        transition: .16s ease;
    }

    .template-action-edit {
        border: 1px solid #cfe4e2;
        background: #f3faf9;
        color: #168f8b;
    }

    .template-action-edit:hover {
        border-color: #b8d8d5;
        background: #eaf7f6;
        color: #127f7c;
    }

    .template-action-status {
        border: 1px solid #d8e1e6;
        background: #fff;
        color: #657987;
    }

    .template-action-status:hover {
        border-color: #c8d5db;
        background: #f7fafb;
        color: #526b79;
    }

    .template-action-delete {
        border: 1px solid #efcccc;
        background: #fff7f7;
        color: #b14d4d;
    }

    .template-action-delete:hover {
        border-color: #e5b8b8;
        background: #fff0f0;
        color: #9f3f3f;
    }


    /* =========================================================
       DATATABLES
       ========================================================= */

    .template-page .dataTables_wrapper {
        padding-top: 0 !important;
        padding-bottom: 8px !important;
    }


    /* =========================================================
       TOP CONTROL
       ========================================================= */

    .template-page .dataTables_wrapper .dataTables_length,
    .template-page .dataTables_wrapper .dataTables_filter {
        margin-top: 0 !important;
        margin-bottom: 10px !important;

        padding-top: 10px !important;

        color: #748793;
        font-size: .68rem;
    }

    .template-page .dataTables_wrapper .dataTables_length {
        padding-left: 10px !important;
    }

    .template-page .dataTables_wrapper .dataTables_filter {
        display: flex;
        align-items: center;
        justify-content: flex-end;

        padding-right: 10px !important;
    }


    /* =========================================================
       SHOW ENTRIES
       ========================================================= */

    .template-page .dataTables_wrapper .dataTables_length label {
        display: inline-flex;
        align-items: center;
        gap: 5px;

        margin: 0;

        color: #748793;
        font-size: .68rem;
        font-weight: 500;
        line-height: 1;
    }

    .template-page .dataTables_wrapper .dataTables_length select {
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

    .template-page .dataTables_wrapper .dataTables_length select:focus {
        border-color: #a9cfcd !important;
        outline: none !important;
        box-shadow: 0 0 0 2px rgba(21, 155, 152, .07) !important;
    }


    /* =========================================================
       SEARCH
       ========================================================= */

    .template-page .dataTables_wrapper .dataTables_filter label {
        display: inline-flex;
        align-items: center;
        gap: 5px;

        margin: 0;

        color: #748793;
        font-size: .68rem;
    }

    .template-page .dataTables_wrapper .dataTables_filter input {
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

    .template-page .dataTables_wrapper .dataTables_filter input:focus {
        border-color: #a7d3d0 !important;
        outline: none !important;
        box-shadow: 0 0 0 2px rgba(21, 155, 152, .07) !important;
    }


    /* =========================================================
       DATATABLES ROW RESET
       ========================================================= */

    .template-page .dataTables_wrapper > .row:first-child {
        margin-left: 0 !important;
        margin-right: 0 !important;
    }

    .template-page .dataTables_wrapper > .row:first-child > div {
        padding-left: 0 !important;
        padding-right: 0 !important;
    }


    /* =========================================================
       INFO
       ========================================================= */

    .template-page .dataTables_wrapper .dataTables_info {
        padding-top: 10px !important;
        padding-left: 10px !important;
        padding-bottom: 4px !important;

        color: #84949e !important;
        font-size: .63rem !important;
        line-height: 1.2 !important;
    }


    /* =========================================================
       PAGINATION
       ========================================================= */

    .template-page .dataTables_wrapper .dataTables_paginate {
        padding-top: 8px !important;
        padding-right: 10px !important;
        padding-bottom: 4px !important;

        margin: 0 !important;
    }

    .template-page .dataTables_wrapper .dataTables_paginate .pagination {
        display: flex !important;
        align-items: center !important;
        justify-content: flex-end !important;
        gap: 4px !important;

        margin: 0 !important;
        padding: 0 !important;
    }

    .template-page .dataTables_wrapper .dataTables_paginate .page-item {
        margin: 0 !important;
        padding: 0 !important;
    }

    .template-page .dataTables_wrapper .dataTables_paginate .page-link {
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

    .template-page .dataTables_wrapper .dataTables_paginate .page-item:first-child .page-link,
    .template-page .dataTables_wrapper .dataTables_paginate .page-item:last-child .page-link {
        min-width: 48px !important;
        padding-left: 7px !important;
        padding-right: 7px !important;
    }

    .template-page .dataTables_wrapper .dataTables_paginate .page-link:hover {
        border-color: #cfe4e2 !important;
        background: #eef8f7 !important;
        color: #168f8b !important;
    }

    .template-page .dataTables_wrapper .dataTables_paginate .page-item.active .page-link {
        border-color: #159b98 !important;
        background: #159b98 !important;
        color: #fff !important;
    }

    .template-page .dataTables_wrapper .dataTables_paginate .page-item.active .page-link:hover {
        border-color: #159b98 !important;
        background: #159b98 !important;
        color: #fff !important;
    }

    .template-page .dataTables_wrapper .dataTables_paginate .page-item.disabled .page-link {
        border-color: #e3e8eb !important;
        background: #f5f7f8 !important;
        color: #b1bdc4 !important;
        cursor: default !important;
    }


    /* =========================================================
       NON-BOOTSTRAP DATATABLES FALLBACK
       ========================================================= */

    .template-page .dataTables_wrapper .dataTables_paginate .paginate_button {
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

    .template-page .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        border-color: #cfe4e2 !important;
        background: #eef8f7 !important;
        color: #168f8b !important;
    }

    .template-page .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        border-color: #159b98 !important;
        background: #159b98 !important;
        color: #fff !important;
    }

    .template-page .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
        color: #b1bdc4 !important;
    }


    /* =========================================================
       EMPTY STATE
       ========================================================= */

    .template-empty {
        padding: 38px 20px !important;

        text-align: center;

        color: #8797a1;
        font-size: .72rem;
        line-height: 1.6;
    }

    .template-empty i {
        display: block;

        margin-bottom: 8px;

        color: #9aabb4;
        font-size: 1.35rem;
    }

    .template-empty strong {
        color: #667d8b;
    }


    /* =========================================================
       RESPONSIVE
       ========================================================= */

    @media (max-width: 991.98px) {

        .template-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .template-header-actions {
            width: 100%;
            justify-content: flex-start;
        }

        .template-body {
            padding: 15px 15px 18px;
        }

    }


    @media (max-width: 767.98px) {

        .template-header-actions {
            flex-wrap: wrap;
        }

        .template-page .dataTables_wrapper .dataTables_length,
        .template-page .dataTables_wrapper .dataTables_filter {
            margin-bottom: 8px !important;
        }

        .template-page .dataTables_wrapper .dataTables_length {
            padding-left: 7px !important;
        }

        .template-page .dataTables_wrapper .dataTables_filter {
            justify-content: flex-start;

            padding-right: 7px !important;

            margin-top: 5px;
        }

        .template-page .dataTables_wrapper .dataTables_filter input {
            width: 130px !important;
            min-width: 130px !important;
        }

        .template-page .dataTables_wrapper .dataTables_info {
            padding-left: 7px !important;
        }

        .template-page .dataTables_wrapper .dataTables_paginate {
            padding-right: 7px !important;
        }

        .template-page .dataTables_wrapper .dataTables_paginate .page-link {
            min-width: 27px !important;
            height: 27px !important;

            padding: 3px 7px !important;

            font-size: .62rem !important;
        }

        .template-page .dataTables_wrapper .dataTables_paginate .page-item:first-child .page-link,
        .template-page .dataTables_wrapper .dataTables_paginate .page-item:last-child .page-link {
            min-width: 43px !important;
        }

        .template-table {
            min-width: 900px;
        }

    }


    @media (max-width: 575.98px) {

        .template-title {
            font-size: .92rem;
        }

        .template-subtitle {
            font-size: .69rem;
        }

        .template-header-actions {
            flex-direction: column;
            align-items: stretch;
        }

        .template-preset-btn,
        .template-add-btn {
            width: 100%;
        }

        .template-page .dataTables_wrapper .dataTables_info {
            font-size: .60rem !important;
        }

    }
</style>


<div class="template-page">

    <!-- =====================================================
         FLASH MESSAGES
    ====================================================== -->

    <?php if ($this->session->flashdata('pesan_sukses')): ?>

        <div
            class="alert alert-success alert-dismissible fade show mb-4"
            role="alert"
        >

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

        <div
            class="alert alert-danger alert-dismissible fade show mb-4"
            role="alert"
        >

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
         MAIN TEMPLATE CARD
    ====================================================== -->

    <div class="template-card">

        <!-- Header -->
        <div class="template-header">

            <div class="template-header-main">

                <h5 class="template-title">

                    <i class="bi bi-file-earmark-text"></i>

                    Master Template Form Pengujian

                </h5>

                <p class="template-subtitle">
                    Kelola template form pengujian dinamis untuk kebutuhan
                    pengujian Kimia dan Mikrobiologi.
                </p>

            </div>


            <!-- Header Actions -->
            <div class="template-header-actions">

                <a
                    href="<?php echo site_url('template/preset'); ?>"
                    class="template-preset-btn"
                    onclick="return confirm('Inisialisasi 17 Preset Form Pengujian (12 Kimia + 5 Mikrobiologi)?');"
                >

                    <i class="bi bi-stars"></i>

                    Inisialisasi 17 Preset Form

                </a>


                <a
                    href="<?php echo site_url('template/tambah'); ?>"
                    class="template-add-btn"
                >

                    <i class="bi bi-plus-lg"></i>

                    Tambah Template

                </a>

            </div>

        </div>


        <!-- Body -->
        <div class="template-body">

            <div class="table-responsive template-table-wrap">

                <table
                    id="tabel-template"
                    class="table table-hover align-middle w-100 template-table"
                >

                    <thead>

                        <tr>

                            <th width="5%">
                                #
                            </th>

                            <th>
                                Kode Template
                            </th>

                            <th>
                                Nama Form Template
                            </th>

                            <th>
                                Kategori
                            </th>

                            <th>
                                Metode Acuan
                            </th>

                            <th>
                                Status
                            </th>

                            <th width="18%">
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        <?php if (!empty($daftar_template)): ?>

                            <?php $no = 1; foreach ($daftar_template as $t): ?>

                                <tr>

                                    <!-- Nomor -->
                                    <td>

                                        <span class="template-number">
                                            <?php echo $no++; ?>
                                        </span>

                                    </td>


                                    <!-- Kode Template -->
                                    <td>

                                        <span class="template-code">

                                            <?php echo html_escape($t['kode_template']); ?>

                                        </span>

                                    </td>


                                    <!-- Nama Template -->
                                    <td>

                                        <strong class="template-name">

                                            <?php echo html_escape($t['nama_template']); ?>

                                        </strong>

                                    </td>


                                    <!-- Kategori -->
                                    <td>

                                        <?php if ($t['kategori'] === 'Kimia'): ?>

                                            <span class="template-category kimia">

                                                <?php echo html_escape($t['kategori']); ?>

                                            </span>

                                        <?php else: ?>

                                            <span class="template-category micro">

                                                <?php echo html_escape($t['kategori']); ?>

                                            </span>

                                        <?php endif; ?>

                                    </td>


                                    <!-- Metode -->
                                    <td>

                                        <span class="template-method">

                                            <?php echo html_escape($t['nama_metode'] ?: 'Dinamis / Pilihan Analis'); ?>

                                        </span>

                                    </td>


                                    <!-- Status -->
                                    <td>

                                        <?php if ($t['status'] === 'Aktif'): ?>

                                            <span class="template-status active">

                                                <i class="bi bi-check-circle-fill me-1"></i>

                                                Aktif

                                            </span>

                                        <?php else: ?>

                                            <span class="template-status inactive">

                                                <?php echo html_escape($t['status']); ?>

                                            </span>

                                        <?php endif; ?>

                                    </td>


                                    <!-- Aksi -->
                                    <td>

                                        <div
                                            class="template-actions"
                                            role="group"
                                        >

                                            <a
                                                href="<?php echo site_url('template/edit/' . $t['id']); ?>"
                                                class="template-action-btn template-action-edit"
                                                title="Edit Skema"
                                            >

                                                <i class="bi bi-pencil-square"></i>

                                                Edit

                                            </a>


                                            <a
                                                href="<?php echo site_url('template/status/' . $t['id']); ?>"
                                                class="template-action-btn template-action-status"
                                                title="Ubah Status"
                                            >

                                                <i class="bi bi-toggle-on"></i>

                                                Status

                                            </a>


                                            <a
                                                href="<?php echo site_url('template/hapus/' . $t['id']); ?>"
                                                class="template-action-btn template-action-delete"
                                                onclick="return confirm('Yakin ingin menghapus template ini?');"
                                                title="Hapus"
                                            >

                                                <i class="bi bi-trash3"></i>

                                                Hapus

                                            </a>

                                        </div>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>

                                <td
                                    colspan="7"
                                    class="template-empty"
                                >

                                    <i class="bi bi-file-earmark-x"></i>

                                    Belum ada template form pengujian.
                                    Klik <strong>"Inisialisasi 17 Preset Form"</strong>
                                    untuk mengisikan 12 Form Kimia &amp;
                                    5 Form Mikrobiologi secara otomatis.

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

        $('#tabel-template').DataTable({

            language: {
                url: '<?php echo base_url('assets/js/dataTables.indonesian.json'); ?>'
            }

        });

    }

});
</script>