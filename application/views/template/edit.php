<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
    /* =========================================================
       EDIT TEMPLATE FORM PENGUJIAN
       Visual redesign only
       ========================================================= */

    .template-edit-page {
        color: #294257;
        font-size: .86rem;
    }

    /* Main Card */
    .template-edit-card {
        width: 100%;
        max-width: 900px;
        overflow: hidden;
        background: #fff;
        border: 1px solid #e0e8ed;
        border-radius: 11px;
        box-shadow: 0 5px 18px rgba(35, 58, 76, .06);
    }

    /* Header */
    .template-edit-header {
        display: flex;
        align-items: center;
        gap: 10px;
        min-height: 60px;
        padding: 15px 19px;
        border-bottom: 1px solid #e5ebee;
        background: #fff;
    }

    .template-edit-header-icon {
        width: 34px;
        height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 34px;
        border: 1px solid #cfe8e7;
        border-radius: 7px;
        background: #eef8f7;
        color: #159b98;
        font-size: 1rem;
    }

    .template-edit-title {
        margin: 0;
        color: #294257;
        font-size: .98rem;
        font-weight: 700;
        line-height: 1.35;
    }

    .template-edit-subtitle {
        margin: 2px 0 0;
        color: #84949e;
        font-size: .68rem;
        line-height: 1.4;
    }

    /* Body */
    .template-edit-body {
        padding: 21px 20px;
    }

    /* Alert */
    .template-edit-page .alert {
        margin-bottom: 18px;
        padding: 10px 12px;
        border-radius: 7px;
        font-size: .73rem;
        line-height: 1.5;
        box-shadow: none;
    }

    .template-edit-page .alert-danger {
        border-color: #efcccc;
    }

    /* Form Group */
    .template-edit-page .form-label {
        display: block;
        margin-bottom: 6px;
        color: #536b79;
        font-size: .72rem;
        font-weight: 700 !important;
    }

    .template-edit-page .form-control,
    .template-edit-page .form-select {
        min-height: 37px;
        padding: 7px 10px;
        border: 1px solid #dce5e9;
        border-radius: 7px;
        background-color: #fff;
        color: #526779;
        font-size: .75rem;
        box-shadow: none;
        transition: .16s ease;
    }

    .template-edit-page .form-control::placeholder {
        color: #a1adb5;
    }

    .template-edit-page .form-control:focus,
    .template-edit-page .form-select:focus {
        border-color: #a7d3d0;
        outline: none;
        box-shadow: 0 0 0 3px rgba(21, 155, 152, .07);
    }

    /* Select Arrow / Text */
    .template-edit-page .form-select {
        cursor: pointer;
    }

    /* Uppercase Code */
    .template-edit-page #kode_template {
        font-weight: 600;
        color: #168f8b;
    }

    /* JSON Section */
    .template-schema-section {
        margin-top: 3px;
        padding: 15px;
        border: 1px solid #e1e9ed;
        border-radius: 9px;
        background: #f8fafb;
    }

    .template-schema-header {
        display: flex;
        align-items: flex-start;
        gap: 9px;
        margin-bottom: 10px;
    }

    .template-schema-icon {
        width: 29px;
        height: 29px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 29px;
        border-radius: 6px;
        background: #e8f5f4;
        color: #159b98;
        font-size: .85rem;
    }

    .template-schema-title {
        margin: 0;
        color: #3e5969;
        font-size: .75rem;
        font-weight: 700;
        line-height: 1.4;
    }

    .template-schema-description {
        margin: 2px 0 0;
        color: #8998a2;
        font-size: .64rem;
        line-height: 1.45;
    }

    .template-edit-page #skema_form {
        width: 100%;
        min-height: 175px;
        padding: 11px 12px;
        border: 1px solid #d7e2e7;
        border-radius: 7px;
        background: #fff;
        color: #405866;
        font-family: Consolas, "Courier New", monospace;
        font-size: .70rem;
        line-height: 1.6;
        resize: vertical;
        tab-size: 2;
    }

    .template-edit-page #skema_form:focus {
        border-color: #a7d3d0;
        box-shadow: 0 0 0 3px rgba(21, 155, 152, .07);
        outline: none;
    }

    /* Status */
    .template-status-section {
        margin-top: 17px;
        padding-top: 17px;
        border-top: 1px solid #edf1f3;
    }

    /* Required */
    .template-edit-page .text-danger {
        color: #c95a5a !important;
    }

    /* Footer Actions */
    .template-edit-footer {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 8px;
        margin-top: 21px;
        padding-top: 16px;
        border-top: 1px solid #edf1f3;
    }

    .template-btn {
        min-height: 35px;
        padding: 7px 14px;
        border-radius: 7px;
        font-size: .70rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        text-decoration: none;
        transition: .16s ease;
    }

    .template-btn-cancel {
        border: 1px solid #d7e0e5;
        background: #fff;
        color: #687b87;
    }

    .template-btn-cancel:hover {
        border-color: #c9d5da;
        background: #f7f9fa;
        color: #526b79;
    }

    .template-btn-save {
        border: 1px solid #159b98;
        background: #159b98;
        color: #fff;
        box-shadow: 0 3px 7px rgba(21, 155, 152, .12);
    }

    .template-btn-save:hover {
        border-color: #118b88;
        background: #118b88;
        color: #fff;
        transform: translateY(-1px);
    }

    /* Grid Spacing */
    .template-edit-page .row.g-3 {
        --bs-gutter-x: 14px;
        --bs-gutter-y: 15px;
    }

    /* Responsive */
    @media (max-width: 767.98px) {

        .template-edit-card {
            max-width: 100%;
        }

        .template-edit-body {
            padding: 17px 15px;
        }

        .template-edit-header {
            padding: 13px 15px;
        }

        .template-edit-footer {
            flex-direction: column-reverse;
            align-items: stretch;
        }

        .template-btn {
            width: 100%;
        }

    }

    @media (max-width: 575.98px) {

        .template-edit-title {
            font-size: .91rem;
        }

        .template-edit-subtitle {
            font-size: .64rem;
        }

        .template-schema-section {
            padding: 12px;
        }

        .template-edit-page #skema_form {
            min-height: 150px;
            font-size: .66rem;
        }

    }
</style>


<div class="template-edit-page">

    <div class="template-edit-card">

        <!-- Header -->
        <div class="template-edit-header">

            <div class="template-edit-header-icon">
                <i class="bi bi-pencil-square"></i>
            </div>

            <div>
                <h5 class="template-edit-title">
                    Edit Template Form Pengujian
                </h5>

                <p class="template-edit-subtitle">
                    Perbarui informasi dan skema form pengujian yang digunakan dalam proses pengujian.
                </p>
            </div>

        </div>


        <!-- Body -->
        <div class="template-edit-body">

            <!-- Validation Errors -->
            <?php if (validation_errors()): ?>

                <div class="alert alert-danger alert-dismissible fade show" role="alert">

                    <div class="d-flex align-items-start gap-2">

                        <i class="bi bi-exclamation-circle-fill mt-1"></i>

                        <div>
                            <?php echo validation_errors('<div>', '</div>'); ?>
                        </div>

                    </div>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Close"
                    ></button>

                </div>

            <?php endif; ?>


            <form
                action="<?php echo site_url('template/edit/' . $template['id']); ?>"
                method="post"
            >

                <input
                    type="hidden"
                    name="<?php echo $this->security->get_csrf_token_name(); ?>"
                    value="<?php echo $this->security->get_csrf_hash(); ?>"
                >


                <!-- Informasi Utama -->
                <div class="row g-3 mb-3">

                    <div class="col-md-4">

                        <label
                            for="kode_template"
                            class="form-label"
                        >
                            Kode Template
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            class="form-control text-uppercase"
                            id="kode_template"
                            name="kode_template"
                            value="<?php echo set_value('kode_template', $template['kode_template']); ?>"
                            required
                        >

                    </div>


                    <div class="col-md-5">

                        <label
                            for="nama_template"
                            class="form-label"
                        >
                            Nama Form Template
                            <span class="text-danger">*</span>
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="nama_template"
                            name="nama_template"
                            value="<?php echo set_value('nama_template', $template['nama_template']); ?>"
                            required
                        >

                    </div>


                    <div class="col-md-3">

                        <label
                            for="kategori"
                            class="form-label"
                        >
                            Kategori Bidang
                        </label>

                        <select
                            class="form-select"
                            id="kategori"
                            name="kategori"
                        >

                            <option
                                value="Kimia"
                                <?php echo set_select('kategori', 'Kimia', ($template['kategori'] === 'Kimia')); ?>
                            >
                                Kimia
                            </option>

                            <option
                                value="Mikrobiologi"
                                <?php echo set_select('kategori', 'Mikrobiologi', ($template['kategori'] === 'Mikrobiologi')); ?>
                            >
                                Mikrobiologi
                            </option>

                        </select>

                    </div>

                </div>


                <!-- Metode & Parameter -->
                <div class="row g-3 mb-3">

                    <div class="col-md-6">

                        <label
                            for="method_id"
                            class="form-label"
                        >
                            Metode Acuan (Opsional)
                        </label>

                        <select
                            class="form-select"
                            id="method_id"
                            name="method_id"
                        >

                            <option value="">
                                -- Bebas / Pilihan Penguji --
                            </option>

                            <?php if (!empty($daftar_metode)): ?>

                                <?php foreach ($daftar_metode as $m): ?>

                                    <option
                                        value="<?php echo $m['id']; ?>"
                                        <?php echo set_select(
                                            'method_id',
                                            $m['id'],
                                            ($template['method_id'] == $m['id'])
                                        ); ?>
                                    >
                                        [<?php echo html_escape($m['kode_metode']); ?>]
                                        <?php echo html_escape($m['nama_metode']); ?>
                                    </option>

                                <?php endforeach; ?>

                            <?php endif; ?>

                        </select>

                    </div>


                    <div class="col-md-6">

                        <label
                            for="parameter_id"
                            class="form-label"
                        >
                            Parameter Acuan (Opsional)
                        </label>

                        <select
                            class="form-select"
                            id="parameter_id"
                            name="parameter_id"
                        >

                            <option value="">
                                -- Bebas / Pilihan Penguji --
                            </option>

                            <?php if (!empty($daftar_parameter)): ?>

                                <?php foreach ($daftar_parameter as $p): ?>

                                    <option
                                        value="<?php echo $p['id']; ?>"
                                        <?php echo set_select(
                                            'parameter_id',
                                            $p['id'],
                                            ($template['parameter_id'] == $p['id'])
                                        ); ?>
                                    >
                                        [<?php echo html_escape($p['kode_parameter']); ?>]
                                        <?php echo html_escape($p['nama_parameter']); ?>
                                    </option>

                                <?php endforeach; ?>

                            <?php endif; ?>

                        </select>

                    </div>

                </div>


                <!-- Skema Form -->
                <div class="template-schema-section">

                    <div class="template-schema-header">

                        <div class="template-schema-icon">
                            <i class="bi bi-braces"></i>
                        </div>

                        <div>

                            <p class="template-schema-title">
                                Skema Form Dinamis (Format JSON)
                                <span class="text-danger">*</span>
                            </p>

                            <p class="template-schema-description">
                                Masukkan struktur parameter form dalam format JSON sesuai kebutuhan template pengujian.
                            </p>

                        </div>

                    </div>


                    <textarea
                        class="form-control font-monospace"
                        id="skema_form"
                        name="skema_form"
                        rows="8"
                        required
                    ><?php echo set_value('skema_form', $template['skema_form']); ?></textarea>

                </div>


                <!-- Status -->
                <div class="template-status-section">

                    <label
                        for="status"
                        class="form-label"
                    >
                        Status Template
                    </label>

                    <select
                        class="form-select"
                        id="status"
                        name="status"
                    >

                        <option
                            value="Aktif"
                            <?php echo set_select(
                                'status',
                                'Aktif',
                                ($template['status'] === 'Aktif')
                            ); ?>
                        >
                            Aktif
                        </option>

                        <option
                            value="Nonaktif"
                            <?php echo set_select(
                                'status',
                                'Nonaktif',
                                ($template['status'] === 'Nonaktif')
                            ); ?>
                        >
                            Nonaktif
                        </option>

                    </select>

                </div>


                <!-- Footer -->
                <div class="template-edit-footer">

                    <a
                        href="<?php echo site_url('template'); ?>"
                        class="template-btn template-btn-cancel"
                    >
                        <i class="bi bi-arrow-left"></i>
                        Batal
                    </a>

                    <button
                        type="submit"
                        class="template-btn template-btn-save"
                    >
                        <i class="bi bi-check2"></i>
                        Simpan Perubahan
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>