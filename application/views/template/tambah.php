<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
    .template-create-page {
        width: 100%;
        padding: 4px 0 20px;
        color: #294257;
        font-size: .9rem;
    }

    .template-create-card {
        width: 100%;
        max-width: 700px;
        margin: 0 auto;
        overflow: hidden;
        background: #fff;
        border: 1px solid #e0e8ed;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(41, 66, 87, .05);
    }

    .template-create-header {
        display: flex;
        align-items: center;
        gap: 13px;
        padding: 18px 22px;
        background: #fff;
        border-bottom: 1px solid #e8eff2;
    }

    .template-create-header-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 42px;
        width: 42px;
        height: 42px;
        color: #159b98;
        background: #e5f6f4;
        border-radius: 10px;
        font-size: 1.2rem;
    }

    .template-create-title {
        margin: 0;
        color: #294257;
        font-size: 1.08rem;
        font-weight: 700;
        line-height: 1.4;
    }

    .template-create-subtitle {
        margin: 3px 0 0;
        color: #8495a3;
        font-size: .8rem;
        line-height: 1.5;
    }

    .template-create-body {
        padding: 23px 22px 21px;
    }

    .template-create-section-title {
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 0 0 16px;
        color: #294257;
        font-size: .91rem;
        font-weight: 700;
    }

    .template-create-section-title i {
        color: #159b98;
        font-size: 1rem;
    }

    .template-create-divider {
        height: 1px;
        margin: 21px 0;
        background: #e8eff2;
        border: 0;
    }

    .template-create-page .form-label {
        display: block;
        margin-bottom: 7px;
        color: #40596d;
        font-size: .85rem;
        font-weight: 600;
    }

    .template-create-page .required-mark {
        color: #dc5454;
    }

    .template-create-page .optional-mark {
        color: #8999a5;
        font-weight: 400;
    }

    .template-create-page .form-control,
    .template-create-page .form-select {
        width: 100%;
        min-height: 42px;
        padding: 9px 12px;
        color: #294257;
        background-color: #fff;
        border: 1px solid #dce5ea;
        border-radius: 7px;
        font-size: .86rem;
        box-shadow: none;
        transition: border-color .2s ease, box-shadow .2s ease;
    }

    .template-create-page .form-control::placeholder {
        color: #a3b0ba;
    }

    .template-create-page .form-control:focus,
    .template-create-page .form-select:focus {
        border-color: #159b98;
        box-shadow: 0 0 0 3px rgba(21, 155, 152, .11);
    }

    .template-create-page .form-control.text-uppercase {
        text-transform: uppercase;
    }

    .template-create-page .schema-textarea {
        min-height: 220px;
        padding: 13px 14px;
        background: #f8fafb;
        border-color: #dce5ea;
        font-family: Consolas, Monaco, 'Courier New', monospace;
        font-size: .82rem;
        line-height: 1.65;
        white-space: pre;
        overflow-wrap: normal;
        overflow-x: auto;
        tab-size: 2;
    }

    .template-create-page .schema-textarea:focus {
        background: #fff;
    }

    .template-create-page .field-hint {
        display: flex;
        align-items: flex-start;
        gap: 6px;
        margin-top: 7px;
        color: #8999a5;
        font-size: .76rem;
        line-height: 1.6;
    }

    .template-create-page .field-hint i {
        margin-top: 2px;
        color: #159b98;
    }

    .template-create-page .schema-label-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 6px;
        margin-bottom: 7px;
    }

    .template-create-page .schema-label-row .form-label {
        margin-bottom: 0;
    }

    .template-create-page .schema-format {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 8px;
        color: #168b88;
        background: #eaf7f6;
        border-radius: 5px;
        font-size: .72rem;
        font-weight: 700;
    }

    .template-create-page .alert {
        margin-bottom: 20px;
        padding: 12px 15px;
        border-radius: 8px;
        font-size: .84rem;
        line-height: 1.6;
    }

    .template-create-page .alert-danger {
        color: #9c3434;
        background: #fff5f5;
        border: 1px solid #f4d6d6;
    }

    .template-create-page .form-footer {
        display: flex;
        justify-content: flex-end;
        flex-wrap: wrap;
        gap: 9px;
        padding-top: 21px;
        margin-top: 24px;
        border-top: 1px solid #e8eff2;
    }

    .template-create-page .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 39px;
        padding: 9px 15px;
        border-radius: 7px;
        font-size: .83rem;
        font-weight: 600;
        text-decoration: none;
        transition: all .2s ease;
    }

    .template-create-page .btn-cancel {
        color: #536777;
        background: #fff;
        border: 1px solid #dce5ea;
    }

    .template-create-page .btn-cancel:hover {
        color: #294257;
        background: #f5f8fa;
        border-color: #cbd8df;
    }

    .template-create-page .btn-save {
        color: #fff;
        background: #159b98;
        border: 1px solid #159b98;
    }

    .template-create-page .btn-save:hover {
        color: #fff;
        background: #118582;
        border-color: #118582;
        box-shadow: 0 3px 8px rgba(21, 155, 152, .18);
    }

    @media (max-width: 575.98px) {
        .template-create-page {
            padding-top: 0;
        }

        .template-create-header {
            padding: 16px;
            gap: 10px;
        }

        .template-create-header-icon {
            flex-basis: 38px;
            width: 38px;
            height: 38px;
            font-size: 1.05rem;
        }

        .template-create-title {
            font-size: 1rem;
        }

        .template-create-subtitle {
            font-size: .76rem;
        }

        .template-create-body {
            padding: 19px 16px;
        }

        .template-create-page .schema-textarea {
            min-height: 190px;
            padding: 11px;
            font-size: .78rem;
        }

        .template-create-page .form-footer {
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        .template-create-page .form-footer .btn {
            width: 100%;
            padding-right: 8px;
            padding-left: 8px;
            font-size: .8rem;
        }
    }
</style>

<div class="template-create-page">
    <div class="template-create-card">

        <!-- Header -->
        <div class="template-create-header">
            <div class="template-create-header-icon">
                <i class="bi bi-file-earmark-code"></i>
            </div>

            <div>
                <h5 class="template-create-title">
                    Tambah Template Form Pengujian
                </h5>
                <p class="template-create-subtitle">
                    Atur identitas template, metode acuan, dan struktur form pengujian.
                </p>
            </div>
        </div>

        <!-- Form -->
        <div class="template-create-body">

            <?php if (validation_errors()): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-start gap-2">
                        <i class="bi bi-exclamation-circle-fill mt-1"></i>
                        <div>
                            <strong>Periksa kembali data yang dimasukkan.</strong>
                            <div class="mt-1">
                                <?php echo validation_errors('<div>', '</div>'); ?>
                            </div>
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

            <form action="<?php echo site_url('template/tambah'); ?>" method="post">

                <input
                    type="hidden"
                    name="<?php echo $this->security->get_csrf_token_name(); ?>"
                    value="<?php echo $this->security->get_csrf_hash(); ?>"
                >

                <!-- Identitas Template -->
                <div class="template-create-section-title">
                    <i class="bi bi-card-heading"></i>
                    <span>Identitas Template</span>
                </div>

                <div class="row g-3">

                    <div class="col-md-4">
                        <label for="kode_template" class="form-label">
                            Kode Template
                            <span class="required-mark">*</span>
                        </label>

                        <input
                            type="text"
                            class="form-control text-uppercase"
                            id="kode_template"
                            name="kode_template"
                            value="<?php echo set_value('kode_template'); ?>"
                            placeholder="TPL-KIM-01"
                            required
                        >
                    </div>

                    <div class="col-md-5">
                        <label for="nama_template" class="form-label">
                            Nama Form Template
                            <span class="required-mark">*</span>
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="nama_template"
                            name="nama_template"
                            value="<?php echo set_value('nama_template'); ?>"
                            placeholder="Nama form pengujian"
                            required
                        >
                    </div>

                    <div class="col-md-3">
                        <label for="kategori" class="form-label">
                            Kategori Bidang
                        </label>

                        <select class="form-select" id="kategori" name="kategori">
                            <option value="Kimia"
                                <?php echo set_select('kategori', 'Kimia', TRUE); ?>>
                                Kimia
                            </option>

                            <option value="Mikrobiologi"
                                <?php echo set_select('kategori', 'Mikrobiologi'); ?>>
                                Mikrobiologi
                            </option>
                        </select>
                    </div>

                </div>

                <hr class="template-create-divider">

                <!-- Referensi Pengujian -->
                <div class="template-create-section-title">
                    <i class="bi bi-journal-check"></i>
                    <span>Referensi Pengujian</span>
                </div>

                <div class="row g-3">

                    <div class="col-md-6">
                        <label for="method_id" class="form-label">
                            Metode Acuan
                            <span class="optional-mark">(Opsional)</span>
                        </label>

                        <select class="form-select" id="method_id" name="method_id">
                            <option value="">-- Bebas / Pilihan Penguji --</option>

                            <?php if (!empty($daftar_metode)): ?>
                                <?php foreach ($daftar_metode as $m): ?>
                                    <option
                                        value="<?php echo $m['id']; ?>"
                                        <?php echo set_select('method_id', $m['id']); ?>
                                    >
                                        [<?php echo html_escape($m['kode_metode']); ?>]
                                        <?php echo html_escape($m['nama_metode']); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="parameter_id" class="form-label">
                            Parameter Acuan
                            <span class="optional-mark">(Opsional)</span>
                        </label>

                        <select class="form-select" id="parameter_id" name="parameter_id">
                            <option value="">-- Bebas / Pilihan Penguji --</option>

                            <?php if (!empty($daftar_parameter)): ?>
                                <?php foreach ($daftar_parameter as $p): ?>
                                    <option
                                        value="<?php echo $p['id']; ?>"
                                        <?php echo set_select('parameter_id', $p['id']); ?>
                                    >
                                        [<?php echo html_escape($p['kode_parameter']); ?>]
                                        <?php echo html_escape($p['nama_parameter']); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                </div>

                <hr class="template-create-divider">

                <!-- Skema Form Dinamis -->
                <div class="mb-4">
                    <div class="schema-label-row">
                        <label for="skema_form" class="form-label">
                            Skema Form Dinamis (Format JSON)
                            <span class="required-mark">*</span>
                        </label>

                        <span class="schema-format">
                            <i class="bi bi-braces"></i>
                            JSON
                        </span>
                    </div>

                    <textarea
                        class="form-control schema-textarea"
                        id="skema_form"
                        name="skema_form"
                        rows="8"
                        placeholder='{
  "kadar_zat": {
    "label": "Kadar Zat Aktif (%)",
    "type": "number",
    "unit": "%",
    "required": true
  },
  "absorbansi": {
    "label": "Pembacaan Absorbansi",
    "type": "number",
    "unit": "Abs",
    "required": true
  }
}'
                        required><?php echo set_value('skema_form'); ?></textarea>

                    <small class="field-hint">
                        <i class="bi bi-info-circle"></i>
                        <span>
                            Masukkan struktur JSON yang valid untuk mendefinisikan
                            field input pengujian pada template ini.
                        </span>
                    </small>
                </div>

                <!-- Status Template -->
                <div class="mb-3">
                    <label for="status" class="form-label">
                        Status Template
                    </label>

                    <select class="form-select" id="status" name="status">
                        <option value="Aktif"
                            <?php echo set_select('status', 'Aktif', TRUE); ?>>
                            Aktif
                        </option>

                        <option value="Nonaktif"
                            <?php echo set_select('status', 'Nonaktif'); ?>>
                            Nonaktif
                        </option>
                    </select>
                </div>

                <!-- Tombol Aksi -->
                <div class="form-footer">
                    <a
                        href="<?php echo site_url('template'); ?>"
                        class="btn btn-cancel"
                    >
                        <i class="bi bi-arrow-left"></i>
                        Batal
                    </a>

                    <button type="submit" class="btn btn-save">
                        <i class="bi bi-check2-circle"></i>
                        Simpan Template
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>