<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
    /* =========================================================
       PROSES SESI PENGUJIAN
       Visual only - tidak mengubah PHP / form logic
    ========================================================= */

    .proses-sesi-page {
        color: #294257;
        font-size: .88rem;
    }

    /* Flash Message */
    .proses-sesi-page .alert {
        border-radius: 9px;
        font-size: .80rem;
        line-height: 1.55;
        border-width: 1px;
    }

    /* Main Header */
    .proses-sesi-header {
        background: #fff;
        border: 1px solid #e0e8ed;
        border-radius: 11px;
        box-shadow: 0 5px 18px rgba(35, 58, 76, .06);
        padding: 18px 20px;
        margin-bottom: 18px;
    }

    .proses-sesi-header-main {
        min-width: 0;
    }

    .proses-sesi-title {
        margin: 0 0 5px;
        color: #294257;
        font-size: 1.05rem;
        font-weight: 700;
        line-height: 1.35;
    }

    .proses-sesi-subtitle {
        margin: 0;
        color: #728594;
        font-size: .76rem;
        line-height: 1.5;
    }

    .proses-sesi-subtitle strong {
        color: #4b6474;
        font-weight: 700;
    }

    .proses-sesi-badges {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 7px;
        flex-wrap: wrap;
    }

    .proses-sesi-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        min-height: 29px;
        padding: 5px 10px;
        border-radius: 6px;
        font-size: .68rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .proses-sesi-badge-kimia {
        background: #e8f1fb;
        border: 1px solid #cfe0f3;
        color: #3974a8;
    }

    .proses-sesi-badge-mikro {
        background: #e8f6ed;
        border: 1px solid #cde8d7;
        color: #3d8158;
    }

    .proses-sesi-badge-form {
        background: #fff6df;
        border: 1px solid #f1dfac;
        color: #936f1c;
    }

    /* Main Form Card */
    .proses-sesi-card {
        background: #fff;
        border: 1px solid #e0e8ed;
        border-radius: 11px;
        box-shadow: 0 5px 18px rgba(35, 58, 76, .06);
        overflow: hidden;
    }

    .proses-sesi-body {
        padding: 20px;
    }

    /* Tabs */
    .proses-sesi-tabs-wrap {
        margin-bottom: 20px;
        overflow-x: auto;
        overflow-y: hidden;
        padding-bottom: 1px;
    }

    .proses-sesi-tabs {
        display: flex;
        flex-wrap: nowrap;
        gap: 4px;
        margin: 0;
        padding: 0;
        border-bottom: 1px solid #e1e8ec;
        min-width: max-content;
    }

    .proses-sesi-tabs .nav-item {
        margin-bottom: -1px;
    }

    .proses-sesi-tabs .nav-link {
        position: relative;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        min-height: 40px;
        padding: 9px 13px;
        border: 0;
        border-bottom: 2px solid transparent;
        border-radius: 7px 7px 0 0;
        background: transparent;
        color: #728594;
        font-size: .76rem;
        font-weight: 600;
        white-space: nowrap;
        transition: .18s ease;
    }

    .proses-sesi-tabs .nav-link:hover {
        color: #159b98;
        background: #f5fbfa;
    }

    .proses-sesi-tabs .nav-link.active {
        color: #159b98;
        background: #f2faf9;
        border-bottom-color: #159b98;
        font-weight: 700;
    }

    /* Form Test Card */
    .uji-form-card {
        margin-bottom: 18px;
        background: #fff;
        border: 1px solid #e0e8ed;
        border-radius: 9px;
        overflow: hidden;
    }

    .uji-form-header {
        min-height: 54px;
        padding: 12px 16px;
        background: #f8fafb;
        border-bottom: 1px solid #e3eaee;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
    }

    .uji-form-title {
        margin: 0;
        color: #294257;
        font-size: .82rem;
        font-weight: 700;
        line-height: 1.45;
    }

    .uji-form-code {
        color: #159b98;
        font-weight: 700;
    }

    .uji-form-method {
        flex-shrink: 0;
        color: #758895;
        font-size: .70rem;
        font-weight: 500;
        text-align: right;
    }

    .uji-form-method strong {
        color: #607786;
        font-weight: 700;
    }

    .uji-form-body {
        padding: 19px 18px;
    }

    /* Labels & Inputs */
    .proses-sesi-page .form-label {
        margin-bottom: 7px;
        color: #526779;
        font-size: .76rem;
        font-weight: 700;
    }

    .proses-sesi-page .form-control,
    .proses-sesi-page .form-select {
        min-height: 40px;
        padding: 8px 11px;
        border: 1px solid #dce5e9;
        border-radius: 7px;
        background: #fff;
        color: #425b6b;
        font-size: .78rem;
        box-shadow: none;
        transition: .18s ease;
    }

    .proses-sesi-page .form-control::placeholder {
        color: #a1afb8;
    }

    .proses-sesi-page .form-control:focus,
    .proses-sesi-page .form-select:focus {
        border-color: #a7d3d0;
        box-shadow: 0 0 0 3px rgba(21, 155, 152, .08);
        background: #fff;
    }

    .parameter-section {
        margin-bottom: 20px;
    }

    .parameter-section-title {
        display: flex;
        align-items: center;
        gap: 7px;
        margin-bottom: 13px;
        color: #526b7a;
        font-size: .74rem;
        font-weight: 700;
    }

    .parameter-section-title i {
        color: #159b98;
        font-size: .88rem;
    }

    /* Empty Schema Alert */
    .template-info-alert {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 13px 15px;
        margin-bottom: 18px;
        border: 1px solid #dfe8ec;
        border-radius: 8px;
        background: #f8fafb;
        color: #718491;
        font-size: .75rem;
        line-height: 1.6;
    }

    .template-info-alert i {
        flex-shrink: 0;
        margin-top: 1px;
        color: #159b98;
        font-size: .92rem;
    }

    .template-info-alert em {
        font-style: normal;
    }

    /* Conclusion Area */
    .kesimpulan-section {
        margin-top: 5px;
        padding-top: 18px;
        border-top: 1px solid #edf1f3;
    }

    .kesimpulan-title {
        margin: 0 0 14px;
        color: #526b7a;
        font-size: .75rem;
        font-weight: 700;
    }

    .required-mark {
        color: #d9534f;
        font-weight: 700;
    }

    /* Bottom Actions */
    .proses-sesi-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-top: 22px;
        padding-top: 17px;
        border-top: 1px solid #e8eef1;
    }

    .proses-btn {
        min-height: 39px;
        padding: 8px 16px;
        border-radius: 7px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        font-size: .76rem;
        font-weight: 700;
        text-decoration: none;
        transition: .18s ease;
    }

    .proses-btn-cancel {
        border: 1px solid #d7e0e5;
        background: #fff;
        color: #657887;
    }

    .proses-btn-cancel:hover {
        background: #f7f9fa;
        color: #526b7a;
        border-color: #c9d5db;
    }

    .proses-btn-submit {
        border: 1px solid #159b98;
        background: #159b98;
        color: #fff;
        box-shadow: 0 3px 8px rgba(21, 155, 152, .15);
    }

    .proses-btn-submit:hover {
        border-color: #118b88;
        background: #118b88;
        color: #fff;
        transform: translateY(-1px);
    }

    /* Small visual indicator */
    .input-group-label {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        margin-bottom: 7px;
    }

    /* Responsive */
    @media (max-width: 767.98px) {
        .proses-sesi-header {
            padding: 15px;
        }

        .proses-sesi-header .row {
            gap: 13px;
        }

        .proses-sesi-badges {
            justify-content: flex-start;
        }

        .proses-sesi-body {
            padding: 14px;
        }

        .uji-form-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .uji-form-method {
            text-align: left;
        }

        .uji-form-body {
            padding: 15px;
        }

        .proses-sesi-actions {
            align-items: stretch;
            flex-direction: column-reverse;
        }

        .proses-btn {
            width: 100%;
        }
    }
</style>

<div class="proses-sesi-page">

    <!-- Flash Messages -->
    <?php if ($this->session->flashdata('pesan_gagal')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i>
            <?php echo html_escape($this->session->flashdata('pesan_gagal')); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Session Header -->
    <div class="proses-sesi-header">
        <div class="row align-items-center g-3">
            <div class="col-lg">
                <div class="proses-sesi-header-main">
                    <h5 class="proses-sesi-title">
                        Form Input Hasil Pengujian Sesi
                    </h5>

                    <p class="proses-sesi-subtitle">
                        Sampel:
                        <strong><?php echo html_escape($sesi['nama_sampel']); ?></strong>
                        <span class="mx-1">•</span>
                        Kode:
                        <strong>
                            <?php echo html_escape($sesi['kode_sampel_manual'] ?: 'SMP-' . $sesi['sample_id']); ?>
                        </strong>
                    </p>
                </div>
            </div>

            <div class="col-lg-auto">
                <div class="proses-sesi-badges">

                    <span class="proses-sesi-badge <?php echo ($sesi['jenis_pengujian'] === 'Kimia' ? 'proses-sesi-badge-kimia' : 'proses-sesi-badge-mikro'); ?>">
                        <i class="bi <?php echo ($sesi['jenis_pengujian'] === 'Kimia' ? 'bi-flask' : 'bi-virus'); ?>"></i>
                        Sesi: <?php echo html_escape($sesi['jenis_pengujian']); ?>
                    </span>

                    <span class="proses-sesi-badge proses-sesi-badge-form">
                        <i class="bi bi-ui-checks-grid"></i>
                        <?php echo count($hasil_forms); ?> Form Uji
                    </span>

                </div>
            </div>
        </div>
    </div>

    <!-- Main Form -->
    <div class="proses-sesi-card">
        <div class="proses-sesi-body">

            <form action="<?php echo site_url('pengujian/proses_sesi/' . $sesi['id']); ?>" method="post">

                <input
                    type="hidden"
                    name="<?php echo $this->security->get_csrf_token_name(); ?>"
                    value="<?php echo $this->security->get_csrf_hash(); ?>"
                >

                <!-- Form Tabs Navigation -->
                <div class="proses-sesi-tabs-wrap">
                    <ul class="nav nav-tabs proses-sesi-tabs" id="formTabs" role="tablist">

                        <?php foreach ($hasil_forms as $idx => $f): ?>

                            <li class="nav-item" role="presentation">

                                <button
                                    class="nav-link <?php echo ($idx === 0 ? 'active fw-bold' : ''); ?>"
                                    id="tab-btn-<?php echo $f['id']; ?>"
                                    data-bs-toggle="tab"
                                    data-bs-target="#tab-pane-<?php echo $f['id']; ?>"
                                    type="button"
                                    role="tab"
                                >
                                    <i class="bi bi-file-earmark-text"></i>

                                    Form #<?php echo ($idx + 1); ?>:
                                    <?php echo html_escape($f['nama_template'] ?: 'Pengujian ' . $sesi['jenis_pengujian']); ?>

                                </button>

                            </li>

                        <?php endforeach; ?>

                    </ul>
                </div>

                <!-- Form Tab Panes -->
                <div class="tab-content" id="formTabsContent">

                    <?php foreach ($hasil_forms as $idx => $f): ?>

                        <div
                            class="tab-pane fade <?php echo ($idx === 0 ? 'show active' : ''); ?>"
                            id="tab-pane-<?php echo $f['id']; ?>"
                            role="tabpanel"
                        >

                            <div class="uji-form-card">

                                <!-- Form Header -->
                                <div class="uji-form-header">

                                    <div>
                                        <h6 class="uji-form-title">
                                            <?php echo html_escape($f['nama_template']); ?>

                                            <span class="uji-form-code">
                                                (<?php echo html_escape($f['kode_template']); ?>)
                                            </span>
                                        </h6>
                                    </div>

                                    <div class="uji-form-method">
                                        Metode:
                                        <strong>
                                            <?php echo html_escape($f['nama_metode'] ?: '-'); ?>
                                        </strong>
                                    </div>

                                </div>

                                <!-- Form Body -->
                                <div class="uji-form-body">

                                    <!-- Dynamic Form Parameter Fields -->
                                    <?php
                                        $schema = !empty($f['skema_form'])
                                            ? json_decode($f['skema_form'], true)
                                            : array();

                                        $data_hasil = !empty($f['data_hasil'])
                                            ? json_decode($f['data_hasil'], true)
                                            : array();
                                    ?>

                                    <?php if (!empty($schema) && is_array($schema)): ?>

                                        <div class="parameter-section">

                                            <div class="parameter-section-title">
                                                <i class="bi bi-sliders"></i>
                                                Parameter Hasil Pengujian
                                            </div>

                                            <div class="row g-3">

                                                <?php foreach ($schema as $key => $label): ?>

                                                    <?php
                                                        // Handle label as array/object or string safely
                                                        if (is_array($label)) {
                                                            $lbl_text = isset($label['label'])
                                                                ? $label['label']
                                                                : (
                                                                    isset($label['title'])
                                                                    ? $label['title']
                                                                    : ucwords(str_replace('_', ' ', $key))
                                                                );
                                                        } else {
                                                            $lbl_text = (string)$label;
                                                        }

                                                        // Handle data_hasil value safely
                                                        $raw_val = isset($data_hasil[$key])
                                                            ? $data_hasil[$key]
                                                            : '';

                                                        if (is_array($raw_val)) {
                                                            $val_text = json_encode(
                                                                $raw_val,
                                                                JSON_UNESCAPED_UNICODE
                                                            );
                                                        } else {
                                                            $val_text = (string)$raw_val;
                                                        }
                                                    ?>

                                                    <div class="col-md-6">

                                                        <label
                                                            for="f_<?php echo $f['id']; ?>_<?php echo $key; ?>"
                                                            class="form-label"
                                                        >
                                                            <?php echo html_escape($lbl_text); ?>
                                                        </label>

                                                        <input
                                                            type="text"
                                                            class="form-control"
                                                            id="f_<?php echo $f['id']; ?>_<?php echo $key; ?>"
                                                            name="forms[<?php echo $f['id']; ?>][hasil][<?php echo $key; ?>]"
                                                            value="<?php echo html_escape($val_text); ?>"
                                                            placeholder="Input hasil <?php echo html_escape($lbl_text); ?>"
                                                        >

                                                    </div>

                                                <?php endforeach; ?>

                                            </div>

                                        </div>

                                    <?php else: ?>

                                        <div class="template-info-alert">
                                            <i class="bi bi-info-circle"></i>

                                            <em>
                                                Template form ini merupakan acuan pengujian
                                                <?php echo html_escape($sesi['jenis_pengujian']); ?>.
                                                Detail parameter spesifik akan diisi sesuai template resmi masing-masing.
                                            </em>
                                        </div>

                                    <?php endif; ?>

                                    <!-- Kesimpulan Form -->
                                    <div class="kesimpulan-section">

                                        <div class="kesimpulan-title">
                                            <i class="bi bi-clipboard-check me-1"></i>
                                            Kesimpulan &amp; Catatan Pengujian
                                        </div>

                                        <div class="row g-3">

                                            <div class="col-md-6">

                                                <label
                                                    for="kesimpulan_<?php echo $f['id']; ?>"
                                                    class="form-label"
                                                >
                                                    Kesimpulan Form #<?php echo ($idx + 1); ?>

                                                    <span class="required-mark">*</span>
                                                </label>

                                                <select
                                                    class="form-select"
                                                    id="kesimpulan_<?php echo $f['id']; ?>"
                                                    name="forms[<?php echo $f['id']; ?>][kesimpulan]"
                                                    required
                                                >

                                                    <option
                                                        value="Belum Disimpulkan"
                                                        <?php echo ($f['kesimpulan'] === 'Belum Disimpulkan' ? 'selected' : ''); ?>
                                                    >
                                                        -- Belum Disimpulkan --
                                                    </option>

                                                    <option
                                                        value="Memenuhi Syarat (MS)"
                                                        <?php echo ($f['kesimpulan'] === 'Memenuhi Syarat (MS)' ? 'selected' : ''); ?>
                                                    >
                                                        Memenuhi Syarat (MS)
                                                    </option>

                                                    <option
                                                        value="Tidak Memenuhi Syarat (TMS)"
                                                        <?php echo ($f['kesimpulan'] === 'Tidak Memenuhi Syarat (TMS)' ? 'selected' : ''); ?>
                                                    >
                                                        Tidak Memenuhi Syarat (TMS)
                                                    </option>

                                                </select>

                                            </div>

                                            <div class="col-md-6">

                                                <label
                                                    for="catatan_<?php echo $f['id']; ?>"
                                                    class="form-label"
                                                >
                                                    Catatan Pengujian
                                                </label>

                                                <input
                                                    type="text"
                                                    class="form-control"
                                                    id="catatan_<?php echo $f['id']; ?>"
                                                    name="forms[<?php echo $f['id']; ?>][catatan]"
                                                    value="<?php echo html_escape($f['catatan'] ?: ''); ?>"
                                                    placeholder="Catatan pengujian jika ada"
                                                >

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    <?php endforeach; ?>

                </div>

                <!-- Global Action Buttons -->
                <div class="proses-sesi-actions">

                    <a
                        href="<?php echo site_url('pengujian/antrean'); ?>"
                        class="proses-btn proses-btn-cancel"
                    >
                        <i class="bi bi-arrow-left"></i>
                        Batal
                    </a>

                    <button
                        type="submit"
                        class="proses-btn proses-btn-submit"
                        onclick="return confirm('Apakah Anda yakin ingin menyimpan seluruh hasil pengujian sesi ini dan meneruskannya ke Verifikasi Penyelia?');"
                    >
                        <i class="bi bi-check2-circle"></i>
                        Simpan Seluruh Hasil Sesi &amp; Kirim Verifikasi
                    </button>

                </div>

            </form>

        </div>
    </div>

</div>