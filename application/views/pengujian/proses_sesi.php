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

    .proses-btn-draft {
        border: 1px solid #0284c7;
        background: #f0f9ff;
        color: #0369a1;
        box-shadow: 0 2px 6px rgba(2, 132, 199, .08);
    }

    .proses-btn-draft:hover {
        border-color: #0369a1;
        background: #e0f2fe;
        color: #0c4a6e;
        transform: translateY(-1px);
    }

    .draft-save-indicator {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.72rem;
        color: #64748b;
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
        padding: 5px 10px;
        border-radius: 6px;
    }

    .draft-save-indicator.saving {
        color: #0284c7;
        border-color: #7dd3fc;
        background: #f0f9ff;
    }

    .draft-save-indicator.saved {
        color: #15803d;
        border-color: #86efac;
        background: #f0fdf4;
    }

    /* Searchable Pickers (Alat & Reagen) */
    .searchable-picker-box {
        background: #ffffff;
        border: 1px solid #dce5e9;
        border-radius: 8px;
        overflow: hidden;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .searchable-picker-box:focus-within {
        border-color: #0284c7;
        box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.1);
    }
    .searchable-picker-search {
        position: relative;
        background: #f8fafc;
        border-bottom: 1px solid #edf2f7;
        padding: 6px 10px;
    }
    .searchable-picker-search i.search-icon {
        position: absolute;
        left: 20px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 0.85rem;
        pointer-events: none;
    }
    .searchable-picker-search input.picker-search-input {
        width: 100%;
        padding: 6px 30px 6px 30px;
        font-size: 0.80rem;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        background: #ffffff;
        color: #334155;
        outline: none;
        transition: border-color 0.15s ease;
    }
    .searchable-picker-search input.picker-search-input:focus {
        border-color: #0284c7;
    }
    .searchable-picker-search .btn-clear-picker-search {
        position: absolute;
        right: 18px;
        top: 50%;
        transform: translateY(-50%);
        background: transparent;
        border: 0;
        color: #94a3b8;
        font-size: 0.95rem;
        padding: 0;
        cursor: pointer;
        display: none;
        line-height: 1;
    }
    .searchable-picker-search .btn-clear-picker-search:hover {
        color: #e11d48;
    }
    .searchable-picker-select {
        border: 0 !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        font-size: 0.82rem;
        padding: 6px 8px;
        width: 100%;
        outline: none;
    }
    .searchable-picker-select option {
        padding: 6px 10px;
        border-radius: 4px;
        margin-bottom: 2px;
        line-height: 1.4;
    }
    .searchable-picker-select option:disabled {
        background-color: #f1f5f9 !important;
        color: #94a3b8 !important;
        cursor: not-allowed;
    }
    .searchable-picker-empty {
        padding: 12px;
        text-align: center;
        color: #94a3b8;
        font-size: 0.78rem;
        background: #ffffff;
        display: none;
    }

    /* Info cards for selected Alat & Reagen */
    .info-item-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 10px 14px;
        margin-top: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.03);
    }
    .info-item-title {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        margin-bottom: 6px;
        font-size: 0.82rem;
    }
    .info-item-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 6px;
        color: #64748b;
        font-size: 0.75rem;
    }
    @media (max-width: 767.98px) {
        .info-item-grid {
            grid-template-columns: 1fr;
        }
        .proses-sesi-header {
            padding: 12px 14px;
        }
        .proses-sesi-header .d-flex.justify-content-between {
            flex-direction: column !important;
            gap: 10px;
        }
        .proses-sesi-badges {
            justify-content: flex-start;
        }
        .proses-sesi-body {
            padding: 14px;
        }
        .uji-form-head {
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 6px;
        }
        .uji-form-method {
            text-align: left;
        }
        .proses-sesi-actions {
            flex-direction: column-reverse !important;
            align-items: stretch !important;
            gap: 8px;
        }
        .proses-sesi-actions .btn {
            width: 100%;
        }
    }

    /* Modal & Search Picker Baku Pembanding */
    .baku-picker-group {
        position: relative;
    }
    .baku-picker-btn {
        background: #f0fdf4;
        color: #15803d;
        border-color: #bbf7d0;
        font-weight: 600;
        transition: all 0.2s ease;
    }
    .baku-picker-btn:hover {
        background: #dcfce7;
        color: #166534;
        border-color: #86efac;
    }
    .baku-info-badge {
        font-size: 0.72rem;
        padding: 3px 7px;
        border-radius: 4px;
        display: inline-flex;
        align-items: center;
        gap: 3px;
        font-weight: 600;
    }
    .baku-info-badge-air {
        background: #e0f2fe;
        color: #0369a1;
        border: 1px solid #bae6fd;
    }
    .baku-info-badge-kadar {
        background: #dcfce7;
        color: #15803d;
        border: 1px solid #bbf7d0;
    }
    .baku-info-badge-loc {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
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
                        <?php echo ($sesi['status'] === 'Ditolak') ? 'Perbaikan &amp; Revisi Hasil Pengujian Sesi' : 'Form Input Hasil Pengujian Sesi'; ?>
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

                    <span class="draft-save-indicator" id="headerDraftIndicator" title="Data pengujian otomatis dicadangkan">
                        <i class="bi bi-cloud-check text-primary"></i>
                        <span id="headerDraftText">Tersedia Simpan Sementara</span>
                    </span>

                    <!-- Opsi Ganti Zat Aktif / Form Parameter Uji -->
                    <?php 
                        $first_za = !empty($hasil_forms[0]['nama_zat_aktif']) ? $hasil_forms[0]['nama_zat_aktif'] : '';
                        $url_ganti_pilihan = site_url('pengujian/pilih_form/' . $sesi['sample_id'] . '?jenis=' . urlencode($sesi['jenis_pengujian']) . '&session_id=' . $sesi['id'] . '&zat_aktif=' . urlencode($first_za));
                    ?>
                    <a href="<?php echo $url_ganti_pilihan; ?>" 
                       class="btn btn-sm btn-outline-primary py-1 px-2 d-inline-flex align-items-center gap-1 shadow-sm"
                       style="font-size: 0.72rem; font-weight: 600;"
                       title="Ganti zat aktif atau ubah formulir parameter pengujian untuk sesi ini tanpa kehilangan data yang sudah diisi"
                       onclick="return konfirmasiGantiZatAktif(this.href);">
                        <i class="bi bi-arrow-left-right"></i>
                        Ganti Zat Aktif / Parameter
                    </a>

                    <!-- Opsi Tambah Parameter Uji dari Master (Poin 11) -->
                    <button type="button" 
                            class="btn btn-sm btn-success py-1 px-3 d-inline-flex align-items-center gap-1 shadow-sm rounded-pill fw-semibold"
                            style="font-size: 0.72rem;"
                            data-bs-toggle="modal" 
                            data-bs-target="#modalTambahParameterSesi"
                            title="Tambahkan parameter uji dari master data ke sesi pengerjaan ini">
                        <i class="bi bi-plus-circle-fill"></i>
                        <span>+ Tambah Parameter Uji</span>
                    </button>

                </div>
            </div>
        </div>
    </div>

    <!-- Alert Catatan Penolakan Revisi (Clean & Modern Design) -->
    <?php if ($sesi['status'] === 'Ditolak' && !empty($sesi['alasan_penolakan'])): ?>
        <div class="card border-0 shadow-sm rounded-3 mb-4 overflow-hidden" style="background: #fff8f8; border: 1px solid #fecaca !important;">
            <div class="p-3">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <div class="rounded-circle bg-danger-subtle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 28px; height: 28px;">
                        <i class="bi bi-exclamation-circle text-danger" style="font-size: 0.88rem;"></i>
                    </div>
                    <div>
                        <strong class="text-danger" style="font-size: 0.88rem;">Catatan Penolakan / Poin Perbaikan:</strong>
                    </div>
                </div>
                <div class="bg-white p-3 rounded-2 border border-danger-subtle text-dark" style="font-size: 0.82rem; line-height: 1.6;">
                    <?php echo nl2br(html_escape($sesi['alasan_penolakan'])); ?>
                </div>
                <div class="text-muted d-flex align-items-center gap-1 mt-2 ps-1" style="font-size: 0.74rem;">
                    <i class="bi bi-info-circle me-1"></i> Harap perbaiki nilai perhitungan, lampiran, atau data uji sesuai dengan catatan di atas sebelum mengirim ulang.
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Main Form -->
    <div class="proses-sesi-card">
        <div class="proses-sesi-body">

            <form id="formProsesSesi" action="<?php echo site_url('pengujian/proses_sesi/' . $sesi['id']); ?>" method="post" enctype="multipart/form-data">

                <input
                    type="hidden"
                    id="limskuCsrfToken"
                    name="<?php echo $this->security->get_csrf_token_name(); ?>"
                    value="<?php echo $this->security->get_csrf_hash(); ?>"
                >
                <input type="hidden" name="aksi_simpan" id="inputAksiSimpan" value="submit">

                <!-- Form Tabs Navigation -->
                <div class="proses-sesi-tabs-wrap">
                    <ul class="nav nav-tabs proses-sesi-tabs" id="formTabs" role="tablist">

                        <?php foreach ($hasil_forms as $idx => $f): ?>
                        <?php
                            $f_param_name = (!empty($f['parameter_uji']) ? $f['parameter_uji'] : (!empty($f['nama_parameter']) ? $f['nama_parameter'] : ($f['nama_metode'] ?? '')));
                            $f_tpl_name   = (!empty($f['nama_template']) ? $f['nama_template'] : ($f['kode_template'] ?? ''));
                            $f_check_text = strtolower($f_param_name . ' ' . $f_tpl_name . ' ' . ($f['nama_metode'] ?? '') . ' ' . ($f['nama_parameter'] ?? '') . ' ' . ($f['metode_analisa'] ?? '') . ' ' . ($f['persyaratan'] ?? '') . ' ' . ($f['kode_template'] ?? '') . ' ' . ($f['kode_metode'] ?? ''));
                            $is_identifikasi = (strpos($f_check_text, 'identifikasi') !== false);
                        ?>

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
                        <?php
                            $f_param_name = (!empty($f['parameter_uji']) ? $f['parameter_uji'] : (!empty($f['nama_parameter']) ? $f['nama_parameter'] : ($f['nama_metode'] ?? '')));
                            $f_tpl_name   = (!empty($f['nama_template']) ? $f['nama_template'] : ($f['kode_template'] ?? ''));
                            $f_check_text = strtolower($f_param_name . ' ' . $f_tpl_name . ' ' . ($f['nama_metode'] ?? '') . ' ' . ($f['nama_parameter'] ?? '') . ' ' . ($f['metode_analisa'] ?? '') . ' ' . ($f['persyaratan'] ?? '') . ' ' . ($f['kode_template'] ?? '') . ' ' . ($f['kode_metode'] ?? ''));
                            $is_identifikasi = (strpos($f_check_text, 'identifikasi') !== false);
                        ?>

                        <div
                            class="tab-pane fade <?php echo ($idx === 0 ? 'show active' : ''); ?> form-tab-pane-uji"
                            id="tab-pane-<?php echo $f['id']; ?>"
                            role="tabpanel"
                            data-form-id="<?php echo $f['id']; ?>"
                            data-kode-template="<?php echo html_escape($f['kode_template'] ?: ''); ?>"
                            data-nama-template="<?php echo html_escape($f['nama_template'] ?: ''); ?>"
                            data-syarat="<?php echo html_escape($f['persyaratan'] ?: ''); ?>"
                            data-parameter="<?php echo html_escape($f['parameter_uji'] ?: (!empty($f['nama_parameter']) ? $f['nama_parameter'] : $f['nama_metode'])); ?>"
                            data-is-identifikasi="<?php echo $is_identifikasi ? '1' : '0'; ?>"
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
                                        <div class="d-flex align-items-center justify-content-end gap-2 flex-wrap mb-1">
                                            <span>Parameter: <strong class="text-primary"><?php echo html_escape($f['parameter_uji'] ?: $f['nama_metode']); ?></strong></span>
                                            
                                            <!-- Opsi Ganti Form LCP untuk Parameter Ini -->
                                            <?php if (!empty($daftar_template_bidang)): ?>
                                                <div class="d-inline-flex align-items-center gap-1 ms-2 p-1 bg-white rounded border" style="font-size: 0.72rem;">
                                                    <label for="select_lcp_<?php echo $f['id']; ?>" class="text-secondary fw-semibold mb-0 text-nowrap">
                                                        <i class="bi bi-file-earmark-ruled text-primary me-1"></i>Form LCP:
                                                    </label>
                                                    <select class="form-select form-select-sm py-0 ps-2 pe-4 border-0 bg-transparent fw-bold text-dark select-ganti-lcp-direct" 
                                                            name="forms[<?php echo $f['id']; ?>][template_id]" 
                                                            id="select_lcp_<?php echo $f['id']; ?>" 
                                                            style="font-size: 0.74rem; width: auto; max-width: 250px; cursor: pointer;"
                                                            title="Ganti Formulir LCP untuk parameter ini">
                                                        <?php foreach ($daftar_template_bidang as $tpl_b): ?>
                                                            <option value="<?php echo $tpl_b['id']; ?>" <?php echo ($f['template_id'] == $tpl_b['id']) ? 'selected' : ''; ?>>
                                                                [<?php echo html_escape($tpl_b['kode_template']); ?>] <?php echo html_escape($tpl_b['nama_template']); ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <?php if (!empty($f['nama_zat_aktif'])): ?>
                                            <span class="d-block small text-muted">
                                                Zat: <?php echo html_escape($f['nama_zat_aktif']); ?>
                                                <?php echo !empty($f['metode_analisa']) ? ' | ' . html_escape($f['metode_analisa']) : ''; ?>
                                                <?php echo !empty($f['persyaratan']) ? ' | Spek: ' . html_escape($f['persyaratan']) : ''; ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>

                                </div>

                                <!-- Form Body -->
                                <div class="uji-form-body">

                                    <!-- Dynamic Form Data & Skema LCP -->
                                    <?php
                                        $schema = !empty($f['skema_form'])
                                            ? json_decode($f['skema_form'], true)
                                            : array();

                                        $data_hasil = !empty($f['data_hasil'])
                                            ? json_decode($f['data_hasil'], true)
                                            : array();

                                        $saved_alat_ids = isset($data_hasil['alat_ids']) && is_array($data_hasil['alat_ids'])
                                            ? $data_hasil['alat_ids']
                                            : array();

                                        $saved_reagen_ids = isset($data_hasil['reagen_ids']) && is_array($data_hasil['reagen_ids'])
                                            ? $data_hasil['reagen_ids']
                                            : array();

                                        $saved_param_values = isset($data_hasil['parameter_values']) && is_array($data_hasil['parameter_values'])
                                            ? $data_hasil['parameter_values']
                                            : $data_hasil;
                                    ?>                                    <!-- Metadata Master Metode Monografi (Terhubung Otomatis) -->
                                    <div class="p-3 mb-4 rounded border" style="background: #f8fafc; border-left: 4px solid #0284c7 !important;">
                                        <div class="d-flex align-items-center justify-content-between mb-2 flex-wrap gap-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="badge bg-primary px-2 py-1"><i class="bi bi-link-45deg me-1"></i> Data Master Metode Monografi Terhubung</span>
                                                <strong class="text-dark fs-6"><?php echo html_escape($f['parameter_uji'] ?: $f['nama_metode']); ?></strong>
                                            </div>
                                            <div class="d-flex align-items-center gap-2">
                                                <button type="button" class="btn btn-xs btn-outline-primary rounded-pill px-2 py-1 btn-open-copy-preparasi d-inline-flex align-items-center gap-1" style="font-size: 0.72rem; font-weight: 500;" data-form-id="<?php echo $f['id']; ?>" data-template-id="<?php echo !empty($f['template_id']) ? $f['template_id'] : 0; ?>" data-template-nama="<?php echo html_escape($f['nama_template'] ?: ($f['parameter_uji'] ?: $f['nama_metode'])); ?>" title="Salin alat, reagen, baku, dan kondisi preparasi dari sampel lain"><i class="bi bi-copy"></i><span>Salin Preparasi</span></button>
                                                <small class="text-muted"><i class="bi bi-qr-code me-1"></i>Kode: <code><?php echo html_escape($f['kode_metode'] ?: '-'); ?></code></small>
                                            </div>
                                        </div>
                                        <div class="row g-2 small text-secondary">
                                            <div class="col-md-3">
                                                <span class="text-muted d-block">Zat Aktif Monografi:</span>
                                                <strong class="text-dark"><?php echo html_escape($f['nama_zat_aktif'] ?: '-'); ?></strong>
                                            </div>
                                            <div class="col-md-3">
                                                <span class="text-muted d-block">Metode Analisa Acuan:</span>
                                                <strong class="text-dark"><?php echo html_escape($f['metode_analisa'] ?: '-'); ?></strong>
                                            </div>
                                            <div class="col-md-3">
                                                <span class="text-muted d-block">Pustaka Acuan Resmi:</span>
                                                <strong class="text-dark"><?php echo html_escape($f['pustaka_acuan'] ?: '-'); ?></strong>
                                            </div>
                                            <div class="col-md-3">
                                                <span class="text-muted d-block">Persyaratan Mutu:</span>
                                                <strong class="text-danger"><?php echo html_escape($f['persyaratan'] ?: '-'); ?></strong>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Pilihan Alat & Reagen dengan Live Info Panel Data Terkait -->
                                    <div class="row g-3 mb-4 p-3 bg-light rounded border">
                                        <div class="col-md-6">
                                            <label class="form-label d-flex align-items-center justify-content-between mb-1">
                                                <span><i class="bi bi-tools text-primary me-1"></i> Alat Laboratorium yang Digunakan</span>
                                                <small class="text-muted fw-normal">Pilih satu atau lebih</small>
                                            </label>

                                            <!-- Searchable Container Alat -->
                                            <div class="searchable-picker-box mb-1" id="picker-box-alat-<?php echo $f['id']; ?>">
                                                <div class="searchable-picker-search">
                                                    <i class="bi bi-search search-icon"></i>
                                                    <input type="text" class="picker-search-input input-search-alat" placeholder="Cari nama, kode, atau merk alat..." autocomplete="off">
                                                    <button type="button" class="btn-clear-picker-search" title="Bersihkan pencarian">
                                                        <i class="bi bi-x-circle-fill"></i>
                                                    </button>
                                                </div>

                                                <select class="form-select searchable-picker-select select-alat-instrument" name="forms[<?php echo $f['id']; ?>][alat_ids][]" multiple size="4" data-target-info="#info-alat-<?php echo $f['id']; ?>">
                                                    <?php if (!empty($daftar_alat)): ?>
                                                        <?php foreach ($daftar_alat as $alat): ?>
                                                            <option value="<?php echo $alat['id']; ?>" <?php echo in_array($alat['id'], $saved_alat_ids) ? 'selected' : ''; ?>
                                                                data-kode="<?php echo html_escape($alat['kode_barang']); ?>"
                                                                data-nama="<?php echo html_escape($alat['nama_barang']); ?>"
                                                                data-merk="<?php echo html_escape($alat['merk_tipe'] ?: '-'); ?>"
                                                                data-software="<?php echo html_escape($alat['perangkat_lunak'] ?: '-'); ?>"
                                                                data-detektor="<?php echo html_escape($alat['detektor'] ?: '-'); ?>"
                                                                data-ruangan="<?php echo html_escape($alat['nama_ruangan'] ?: '-'); ?>"
                                                                data-kondisi="<?php echo html_escape($alat['kondisi_adm'] ?: '-'); ?>">
                                                                [<?php echo html_escape($alat['kode_barang']); ?>] <?php echo html_escape($alat['nama_barang']); ?> <?php echo !empty($alat['merk_tipe']) ? '(' . html_escape($alat['merk_tipe']) . ')' : ''; ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <option disabled>Belum ada data alat aktif</option>
                                                    <?php endif; ?>
                                                </select>
                                                <div class="searchable-picker-empty picker-empty-alat">
                                                    <i class="bi bi-search me-1"></i> Tidak ada alat yang cocok dengan pencarian
                                                </div>
                                            </div>
                                            <small class="text-muted d-block mt-1"><i class="bi bi-cursor me-1"></i>Klik pada item untuk memilih atau membatalkan pilihan.</small>

                                            <!-- Container Detail Alat Terpilih -->
                                            <div id="info-alat-<?php echo $f['id']; ?>" class="mt-2 info-detail-container"></div>
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label d-flex align-items-center justify-content-between mb-1">
                                                <span><i class="bi bi-droplet-half text-info me-1"></i> Reagen yang Digunakan</span>
                                                <small class="text-muted fw-normal">Pilih satu atau lebih</small>
                                            </label>

                                            <!-- Searchable Container Reagen -->
                                            <div class="searchable-picker-box mb-1" id="picker-box-reagen-<?php echo $f['id']; ?>">
                                                <div class="searchable-picker-search">
                                                    <i class="bi bi-search search-icon"></i>
                                                    <input type="text" class="picker-search-input input-search-reagen" placeholder="Cari nama reagen, kode, katalog, merk..." autocomplete="off">
                                                    <button type="button" class="btn-clear-picker-search" title="Bersihkan pencarian">
                                                        <i class="bi bi-x-circle-fill"></i>
                                                    </button>
                                                </div>

                                                <select class="form-select searchable-picker-select select-reagen-material" name="forms[<?php echo $f['id']; ?>][reagen_ids][]" multiple size="4" data-target-info="#info-reagen-<?php echo $f['id']; ?>">
                                                    <?php if (!empty($daftar_reagen)): ?>
                                                        <?php foreach ($daftar_reagen as $rg): ?>
                                                            <?php
                                                            // Cek apakah stok kosong (0, null, atau berawalan 0)
                                                            $stok_raw = !empty($rg['stok']) ? trim($rg['stok']) : '0';
                                                            $is_stok_kosong = ($stok_raw === '' || $stok_raw === '0' || preg_match('/^0(\.0+)?(\s+.*)?$/i', $stok_raw));

                                                            // Cek apakah expired date sudah lewat
                                                            $is_expired = false;
                                                            if (!empty($rg['expired_date']) && $rg['expired_date'] !== '0000-00-00') {
                                                                $is_expired = (strtotime($rg['expired_date']) < strtotime('today'));
                                                            }

                                                            // Tetap dapat dipilih oleh penguji (fleksibel ketersediaan di lab)
                                                            $status_keterangan = '';
                                                            if ($is_expired && $is_stok_kosong) {
                                                                $status_keterangan = ' [Kedaluwarsa & Stok Gudang Kosong]';
                                                            } elseif ($is_expired) {
                                                                $status_keterangan = ' [Kedaluwarsa - ' . date('d/m/Y', strtotime($rg['expired_date'])) . ']';
                                                            } elseif ($is_stok_kosong) {
                                                                $status_keterangan = ' [Stok Gudang Kosong]';
                                                            }
                                                            $nama_tampil = !empty($rg['nama_barang']) ? $rg['nama_barang'] : $rg['nama_reagen'];
                                                            ?>
                                                            <option value="<?php echo $rg['id']; ?>" 
                                                                <?php echo in_array($rg['id'], $saved_reagen_ids) ? 'selected' : ''; ?>
                                                                data-kode="<?php echo html_escape($rg['kode_barang'] ?: '-'); ?>"
                                                                data-katalog="<?php echo html_escape($rg['katalog'] ?: '-'); ?>"
                                                                data-nama="<?php echo html_escape($nama_tampil); ?>"
                                                                data-merk="<?php echo html_escape($rg['merk'] ?: '-'); ?>"
                                                                data-stok="<?php echo html_escape($rg['stok'] ?: '-'); ?>"
                                                                data-jenis="<?php echo html_escape($rg['jenis_barang'] ?: '-'); ?>"
                                                                data-exp="<?php echo html_escape($rg['expired_date'] ?: '-'); ?>"
                                                                data-is-expired="<?php echo $is_expired ? '1' : '0'; ?>"
                                                                data-is-empty-stock="<?php echo $is_stok_kosong ? '1' : '0'; ?>">
                                                                <?php echo html_escape($nama_tampil); ?> <?php echo !empty($rg['merk']) ? '(' . html_escape($rg['merk']) . ')' : ''; ?> <?php echo !empty($rg['kemasan']) ? '[' . html_escape($rg['kemasan']) . ']' : ''; ?><?php echo $status_keterangan; ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <option disabled>Belum ada data reagen aktif</option>
                                                    <?php endif; ?>
                                                </select>
                                                <div class="searchable-picker-empty picker-empty-reagen">
                                                    <i class="bi bi-search me-1"></i> Tidak ada reagen yang cocok dengan pencarian
                                                </div>
                                            </div>
                                            <small class="text-muted d-block mt-1">
                                                <i class="bi bi-cursor me-1"></i>Klik pada item untuk memilih atau membatalkan pilihan.
                                                <span class="text-secondary d-inline-block ms-1">
                                                    <i class="bi bi-info-circle me-1"></i>Reagen tetap dapat dipilih jika persediaan fisik di lab masih tersedia.
                                                </span>
                                            </small>

                                            <!-- Container Detail Reagen Terpilih -->
                                            <div id="info-reagen-<?php echo $f['id']; ?>" class="mt-2 info-detail-container"></div>
                                        </div>
                                    </div>

                                    <!-- Dynamic Form Parameter Fields -->
                                    <?php if (!empty($schema) && is_array($schema)): ?>

                                        <?php if (isset($schema['sections']) && is_array($schema['sections'])): ?>

                                            <!-- Render Skema Terstruktur Berseksi (LCP Resmi Spesifikasi) -->
                                            <?php foreach ($schema['sections'] as $sec): ?>
                                                <?php
                                                    $sec_id = $sec['id'] ?? '';
                                                    $is_kadar_sec = in_array($sec_id, array('perhitungan_kadar_hplc', 'perhitungan_kadar_dan_presisi', 'perhitungan_kadar', 'perhitungan_kadar_spektro'), true);

                                                    // Field kadar yang wajib disembunyikan pada LCP Identifikasi (Poin 1 & 3)
                                                    $kadar_exclusive_flds = array(
                                                        'metode_perhitungan_kadar', 'volume_takaran_etiket', 'bobot_rata_tablet',
                                                        'bobot_rata_rata_tablet', 'klaim_etiket', 'faktor_konversi_bm',
                                                        'baku_kadar', 'baku_sp', 'baku_kadar_terkoreksi', 'kadar_baku_persen',
                                                        'internal_kadar', 'internal_sp', 'internal_kadar_terkoreksi',
                                                        'kadar_uji_1', 'kadar_uji_2', 'kadar_rata_rata_persen', 'kadar_rata_rata_mg',
                                                        'kadar_replikasi_1', 'kadar_replikasi_2', 'kadar_rata_persen',
                                                        'kadar_rata_mg', 'rpd_hitung', 'nilai_rpd', 'rpd',
                                                        'syarat_rpd_horwitz', 'syarat_horwitz', 'ketidakpastian_teks', 'decision_rule',
                                                        'kadar_hasil_persen', 'kadar_hasil_mg'
                                                    );

                                                    // Jika Identifikasi dan seluruh field dalam seksi ini adalah field kadar, skip seksi seutuhnya
                                                    if ($is_identifikasi && !empty($sec['fields']) && is_array($sec['fields'])) {
                                                        $rem_flds = 0;
                                                        foreach ($sec['fields'] as $cf) {
                                                            if (!in_array($cf['id'] ?? '', $kadar_exclusive_flds, true)) {
                                                                $rem_flds++;
                                                            }
                                                        }
                                                        if ($rem_flds === 0) {
                                                            continue;
                                                        }
                                                    }

                                                    $is_stage_sec = in_array($sec_id, array('tahap_2', 'data_tahap_s2', 'data_tahap_s3'));
                                                    $stage_trigger_key = ($sec_id === 'tahap_2') ? 'lakukan_tahap2' : (($sec_id === 'data_tahap_s2') ? 'lakukan_tahap_s2' : 'lakukan_tahap_s3');
                                                    $stage_active = isset($saved_param_values[$stage_trigger_key]) ? ($saved_param_values[$stage_trigger_key] === 'Ya') : (isset($data_hasil[$stage_trigger_key]) ? ($data_hasil[$stage_trigger_key] === 'Ya') : false);

                                                    $sec_title = isset($sec['title']) ? $sec['title'] : 'Bagian Form';
                                                    $sec_desc  = isset($sec['description']) ? $sec['description'] : '';

                                                    // Poin 1 & 3: Sesuaikan judul & keterangan seksi hasil pengujian Identifikasi
                                                    if ($is_identifikasi && $is_kadar_sec) {
                                                        $sec_title = 'Hasil Pengujian & Pustaka Acuan (Identifikasi)';
                                                        $sec_desc  = 'Catatan hasil pengamatan uji identifikasi kualitatif dan verifikasi kesesuaian terhadap pustaka acuan resmi.';
                                                    }
                                                ?>
                                                <div class="parameter-section mb-4" id="sec_<?php echo $f['id']; ?>_<?php echo html_escape($sec_id); ?>" data-section-id="<?php echo html_escape($sec_id); ?>">
                                                    <div class="parameter-section-title">
                                                        <i class="bi bi-folder2-open me-1"></i>
                                                        <?php echo html_escape($sec_title); ?>
                                                    </div>

                                                    <?php if (!empty($sec_desc)): ?>
                                                        <p class="text-muted small mb-3"><?php echo html_escape($sec_desc); ?></p>
                                                    <?php endif; ?>

                                                    <?php if (isset($sec['type']) && $sec['type'] === 'reference_guide' && !empty($sec['items'])): ?>
                                                        <!-- Tampilan Pedoman Acuan Visual (Read-Only Guide) -->
                                                        <div class="table-responsive bg-white rounded border p-2 mb-2">
                                                            <table class="table table-sm table-bordered align-middle mb-0">
                                                                <thead class="table-light">
                                                                    <tr>
                                                                        <th style="width: 28%;">Mikroba Target</th>
                                                                        <th>Media &amp; Kriteria Pengamatan Karakteristik Koloni</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php foreach ($sec['items'] as $ref): ?>
                                                                        <tr>
                                                                            <td class="fw-semibold text-primary">
                                                                                <em><?php echo html_escape($ref['mikroba']); ?></em>
                                                                            </td>
                                                                            <td>
                                                                                <ul class="list-unstyled mb-0 small">
                                                                                    <?php foreach ($ref['kriteria'] as $med => $krit): ?>
                                                                                        <li><span class="badge bg-secondary me-1"><?php echo html_escape($med); ?></span> <?php echo html_escape($krit); ?></li>
                                                                                    <?php endforeach; ?>
                                                                                </ul>
                                                                            </td>
                                                                        </tr>
                                                                    <?php endforeach; ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    <?php elseif (!empty($sec['fields']) && is_array($sec['fields'])): ?>
                                                        <div class="row g-3">
                                                            <?php foreach ($sec['fields'] as $fld): ?>
                                                                <?php
                                                                    $fld_id = isset($fld['id']) ? $fld['id'] : '';
                                                                    // Poin 1 & 3: Skip field kadar sediaan, bobot rata-rata, klaim, faktor koreksi BM, kadar baku internal untuk Identifikasi
                                                                    $kadar_exclusive_flds = array(
                                                                        'metode_perhitungan_kadar', 'volume_takaran_etiket', 'bobot_rata_tablet',
                                                                        'bobot_rata_rata_tablet', 'klaim_etiket', 'faktor_konversi_bm',
                                                                        'baku_kadar', 'baku_sp', 'baku_kadar_terkoreksi',
                                                                        'internal_kadar', 'internal_sp', 'internal_kadar_terkoreksi',
                                                                        'kadar_replikasi_1', 'kadar_replikasi_2', 'kadar_rata_persen',
                                                                        'kadar_rata_mg', 'rpd', 'syarat_horwitz'
                                                                    );
                                                                    if ($is_identifikasi && in_array($fld_id, $kadar_exclusive_flds, true)) {
                                                                        continue;
                                                                    }
                                                                ?>
                                                                <?php
                                                                    $fld_id = isset($fld['id']) ? $fld['id'] : '';
                                                                    if (empty($fld_id)) continue;

                                                                    $fld_label = isset($fld['label']) ? $fld['label'] : ucwords(str_replace('_', ' ', $fld_id));
                                                                    $fld_type = isset($fld['type']) ? $fld['type'] : 'text';
                                                                    $fld_req = !empty($fld['required']) ? 'required' : '';
                                                                    $fld_ph = isset($fld['placeholder']) ? $fld['placeholder'] : 'Input ' . $fld_label;
                                                                    $fld_unit = isset($fld['unit']) ? ' (' . $fld['unit'] . ')' : '';

                                                                    $raw_val = isset($saved_param_values[$fld_id])
                                                                        ? $saved_param_values[$fld_id]
                                                                        : (isset($data_hasil[$fld_id]) ? $data_hasil[$fld_id] : (isset($fld['default']) ? $fld['default'] : ''));

                                                                    // Integrasi Otomatis Master Metode: Pustaka Acuan & Syarat Spesifikasi
                                                                    if (($raw_val === '' || $raw_val === null)) {
                                                                        if (($fld_id === 'pustaka_metode' || $fld_id === 'pustaka_acuan') && !empty($f['pustaka_acuan'])) {
                                                                            $raw_val = $f['pustaka_acuan'];
                                                                        } elseif (($fld_id === 'syarat' || $fld_id === 'persyaratan') && !empty($f['persyaratan'])) {
                                                                            $raw_val = $f['persyaratan'];
                                                                        }
                                                                    }

                                                                    // Auto-detection metode perhitungan kadar berdasarkan bentuk sediaan jika belum tersimpan
                                                                    if ($fld_id === 'metode_perhitungan_kadar' && empty($raw_val)) {
                                                                        $str_sediaan = strtolower(($f['bentuk_sediaan'] ?? '') . ' ' . ($f['jenis_sediaan'] ?? '') . ' ' . ($f['nama_metode'] ?? '') . ' ' . ($sesi['nama_sampel'] ?? ''));
                                                                        if (strpos($str_sediaan, 'sirup') !== false || strpos($str_sediaan, 'suspensi') !== false || strpos($str_sediaan, 'drops') !== false || strpos($str_sediaan, 'cair') !== false || strpos($str_sediaan, 'larutan') !== false || strpos($str_sediaan, 'eliksir') !== false) {
                                                                            $raw_val = 'Sirup / Suspensi / Cairan Oral (Volume mL)';
                                                                        } elseif (strpos($str_sediaan, 'effervescent') !== false || strpos($str_sediaan, 'cemplung') !== false) {
                                                                            $raw_val = 'Tablet Cemplung (Direct Dissolution / Effervescent)';
                                                                        } else {
                                                                            $raw_val = 'Tablet / Kapsul BR (Metode 20 Tablet Digerus)';
                                                                        }
                                                                    }

                                                                    $val_text = is_array($raw_val) ? json_encode($raw_val, JSON_UNESCAPED_UNICODE) : (string)$raw_val;

                                                                    $is_stage_control = in_array($fld_id, array('lakukan_tahap2', 'lakukan_tahap_s2', 'lakukan_tahap_s3'));
                                                                    $is_cond_item = ($is_stage_sec && !$is_stage_control);
                                                                    $cond_class = $is_cond_item ? 'tahap-conditional-item' : '';
                                                                    $cond_style = ($is_cond_item && !$stage_active) ? 'display: none;' : '';
                                                                ?>

                                                                <?php if ($fld_type === 'table'): ?>
                                                                    <!-- Subtabel Pengamatan (Matriks Bertahap) -->
                                                                    <div class="col-12 <?php echo $cond_class; ?>" <?php echo !empty($cond_style) ? 'style="' . $cond_style . '"' : ''; ?>>
                                                                        <label class="form-label fw-semibold"><?php echo html_escape($fld_label); ?></label>
                                                                        <div class="table-responsive bg-white rounded border">
                                                                            <table class="table table-sm table-bordered align-middle mb-0">
                                                                                <thead class="table-light">
                                                                                    <tr>
                                                                                        <?php foreach ($fld['columns'] as $col): ?>
                                                                                            <?php $is_col_tahap = (!empty($col['readonly']) || $col['id'] === 'tahap'); ?>
                                                                                            <th class="small <?php echo $is_col_tahap ? '' : 'text-nowrap'; ?>" style="<?php echo $is_col_tahap ? 'min-width: 140px;' : 'min-width: 110px;'; ?>">
                                                                                                <?php echo html_escape($col['label']); ?>
                                                                                            </th>
                                                                                        <?php endforeach; ?>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <?php
                                                                                        // Poin 2: Baris tabel baku untuk Identifikasi (Baku Pembanding, Pelarut, Zat Uji, Spike)
                                                                                        if ($is_identifikasi && in_array($fld_id, array('tabel_hplc', 'tabel_preparasi', 'klt_items'), true)) {
                                                                                            $ident_categories = array('Baku Pembanding', 'Pelarut', 'Zat Uji', 'Spike');
                                                                                            
                                                                                            // Validasi apakah raw_val sudah memuat 4 kategori Identifikasi lengkap
                                                                                            $has_ident_format = false;
                                                                                            if (!empty($raw_val) && is_array($raw_val) && count($raw_val) === 4) {
                                                                                                $r1_name = strtolower($raw_val[1]['nama_zat'] ?? ($raw_val[1]['kategori'] ?? ''));
                                                                                                $r3_name = strtolower($raw_val[3]['nama_zat'] ?? ($raw_val[3]['kategori'] ?? ''));
                                                                                                if (strpos($r1_name, 'pelarut') !== false && strpos($r3_name, 'spike') !== false) {
                                                                                                    $has_ident_format = true;
                                                                                                }
                                                                                            }

                                                                                            if ($has_ident_format) {
                                                                                                $existing_rows = $raw_val;
                                                                                            } else {
                                                                                                $existing_rows = array();
                                                                                                foreach ($ident_categories as $c_idx => $c_cat) {
                                                                                                    $row_data = array('kategori' => $c_cat, 'nama_zat' => $c_cat);
                                                                                                    if (!empty($raw_val) && is_array($raw_val)) {
                                                                                                        if ($c_cat === 'Baku Pembanding' && isset($raw_val[0])) {
                                                                                                            $row_data = array_merge($raw_val[0], $row_data);
                                                                                                        } elseif ($c_cat === 'Zat Uji') {
                                                                                                            foreach ($raw_val as $old_r) {
                                                                                                                $old_name = strtolower($old_r['nama_zat'] ?? ($old_r['kategori'] ?? ''));
                                                                                                                if (strpos($old_name, 'uji') !== false) {
                                                                                                                    $row_data = array_merge($old_r, $row_data);
                                                                                                                    $row_data['nama_zat'] = 'Zat Uji';
                                                                                                                    $row_data['kategori'] = 'Zat Uji';
                                                                                                                    break;
                                                                                                                }
                                                                                                            }
                                                                                                        }
                                                                                                    }
                                                                                                    $existing_rows[] = $row_data;
                                                                                                }
                                                                                            }
                                                                                        } else {
                                                                                            $existing_rows = !empty($raw_val) && is_array($raw_val)
                                                                                                ? $raw_val
                                                                                                : (isset($fld['default_rows']) ? $fld['default_rows'] : array(array()));
                                                                                        }
                                                                                    ?>
                                                                                    <?php foreach ($existing_rows as $r_idx => $row_data): ?>
                                                                                        <tr>
                                                                                            <?php foreach ($fld['columns'] as $col): ?>
                                                                                                <?php
                                                                                                    $c_id = $col['id'];
                                                                                                    $c_val = isset($row_data[$c_id]) ? $row_data[$c_id] : '';
                                                                                                    $c_readonly = !empty($col['readonly']);
                                                                                                    $c_type = isset($col['type']) && $col['type'] === 'date' ? 'date' : 'text';
                                                                                                ?>
                                                                                                <td>
                                                                                                    <?php if ($c_readonly && !$is_identifikasi): ?>
                                                                                                        <div class="px-2 py-1 bg-light border rounded text-dark fw-semibold small" style="white-space: normal; word-break: break-word; line-height: 1.35; min-width: 130px;">
                                                                                                            <?php echo html_escape((string)$c_val); ?>
                                                                                                        </div>
                                                                                                        <input
                                                                                                            type="hidden"
                                                                                                            name="forms[<?php echo $f['id']; ?>][hasil][<?php echo $fld_id; ?>][<?php echo $r_idx; ?>][<?php echo $c_id; ?>]"
                                                                                                            value="<?php echo html_escape((string)$c_val); ?>"
                                                                                                        >
                                                                                                    <?php else: ?>
                                                                                                        <input
                                                                                                            type="<?php echo $is_identifikasi ? 'text' : $c_type; ?>"
                                                                                                            class="form-control form-control-sm"
                                                                                                            name="forms[<?php echo $f['id']; ?>][hasil][<?php echo $fld_id; ?>][<?php echo $r_idx; ?>][<?php echo $c_id; ?>]"
                                                                                                            value="<?php echo html_escape((string)$c_val); ?>"
                                                                                                            placeholder="<?php echo $is_identifikasi ? 'Input teks/angka...' : ''; ?>"
                                                                                                        >
                                                                                                    <?php endif; ?>
                                                                                                </td>
                                                                                            <?php endforeach; ?>
                                                                                        </tr>
                                                                                    <?php endforeach; ?>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>

                                                                <?php elseif ($fld_type === 'checkbox'): ?>
                                                                    <div class="col-md-6 d-flex align-items-center <?php echo $cond_class; ?>" <?php echo !empty($cond_style) ? 'style="' . $cond_style . '"' : ''; ?>>
                                                                        <div class="form-check mt-3">
                                                                            <input
                                                                                type="hidden"
                                                                                name="forms[<?php echo $f['id']; ?>][hasil][<?php echo $fld_id; ?>]"
                                                                                value="0"
                                                                            >
                                                                            <input
                                                                                type="checkbox"
                                                                                class="form-check-input"
                                                                                id="f_<?php echo $f['id']; ?>_<?php echo $fld_id; ?>"
                                                                                name="forms[<?php echo $f['id']; ?>][hasil][<?php echo $fld_id; ?>]"
                                                                                value="1"
                                                                                <?php echo (!empty($raw_val) && $raw_val !== '0' && $raw_val !== false) ? 'checked' : ''; ?>
                                                                            >
                                                                            <label class="form-check-label" for="f_<?php echo $f['id']; ?>_<?php echo $fld_id; ?>">
                                                                                <?php echo html_escape($fld_label); ?>
                                                                                <?php if (!empty($fld['keterangan'])): ?>
                                                                                    <small class="text-muted d-block"><?php echo html_escape($fld['keterangan']); ?></small>
                                                                                <?php endif; ?>
                                                                            </label>
                                                                        </div>
                                                                    </div>

                                                                <?php elseif ($fld_type === 'radio' && !empty($fld['options'])): ?>
                                                                    <div class="col-12 <?php echo $cond_class; ?>" <?php echo !empty($cond_style) ? 'style="' . $cond_style . '"' : ''; ?>>
                                                                        <label class="form-label fw-semibold d-block"><?php echo html_escape($fld_label); ?></label>
                                                                        <div class="d-flex flex-wrap gap-3">
                                                                            <?php foreach ($fld['options'] as $opt_idx => $opt): ?>
                                                                                <div class="form-check">
                                                                                    <input
                                                                                        class="form-check-input"
                                                                                        type="radio"
                                                                                        name="forms[<?php echo $f['id']; ?>][hasil][<?php echo $fld_id; ?>]"
                                                                                        id="f_<?php echo $f['id']; ?>_<?php echo $fld_id; ?>_<?php echo $opt_idx; ?>"
                                                                                        value="<?php echo html_escape($opt); ?>"
                                                                                        <?php echo ($raw_val === $opt) ? 'checked' : ''; ?>
                                                                                    >
                                                                                    <label class="form-check-label" for="f_<?php echo $f['id']; ?>_<?php echo $fld_id; ?>_<?php echo $opt_idx; ?>">
                                                                                        <?php echo html_escape($opt); ?>
                                                                                    </label>
                                                                                </div>
                                                                            <?php endforeach; ?>
                                                                        </div>
                                                                    </div>

                                                                <?php elseif ($fld_type === 'select' && !empty($fld['options'])): ?>
                                                                    <?php if ($is_stage_control): ?>
                                                                        <div class="col-12">
                                                                            <div class="p-3 rounded border control-tahap-wrapper <?php echo ($raw_val === 'Ya') ? 'bg-light-primary border-primary' : 'bg-light border-secondary'; ?>">
                                                                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                                                                    <div>
                                                                                        <label for="f_<?php echo $f['id']; ?>_<?php echo $fld_id; ?>" class="form-label fw-bold mb-0">
                                                                                            <i class="bi bi-diagram-3-fill text-primary me-1"></i> <?php echo html_escape($fld_label); ?>
                                                                                        </label>
                                                                                        <?php if (!empty($fld['keterangan'])): ?>
                                                                                            <div class="small text-muted mt-1"><?php echo html_escape($fld['keterangan']); ?></div>
                                                                                        <?php endif; ?>
                                                                                    </div>
                                                                                    <div style="min-width: 140px;">
                                                                                        <select
                                                                                            class="form-select fw-bold select-tahap-trigger"
                                                                                            id="f_<?php echo $f['id']; ?>_<?php echo $fld_id; ?>"
                                                                                            name="forms[<?php echo $f['id']; ?>][hasil][<?php echo $fld_id; ?>]"
                                                                                            data-form-id="<?php echo $f['id']; ?>"
                                                                                            data-tahap-id="<?php echo $fld_id; ?>"
                                                                                            <?php echo $fld_req; ?>
                                                                                        >
                                                                                            <?php foreach ($fld['options'] as $opt): ?>
                                                                                                <option value="<?php echo html_escape($opt); ?>" <?php echo ($raw_val === $opt) ? 'selected' : ''; ?>>
                                                                                                    <?php echo html_escape($opt); ?>
                                                                                                </option>
                                                                                            <?php endforeach; ?>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    <?php elseif ($fld_id === 'metode_perhitungan_kadar'): ?>
                                                                        <div class="col-12" data-field-wrapper="metode_perhitungan_kadar">
                                                                            <div class="p-3 rounded border bg-light-primary border-primary">
                                                                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                                                                                    <div>
                                                                                        <label for="f_<?php echo $f['id']; ?>_<?php echo $fld_id; ?>" class="form-label fw-bold mb-0 text-primary">
                                                                                            <i class="bi bi-calculator-fill me-1"></i> <?php echo html_escape($fld_label); ?>
                                                                                        </label>
                                                                                        <div class="small text-muted" style="font-size: 0.78rem;">Pilih rumus perhitungan penetapan kadar sesuai sediaan &amp; preparasi (FI VI &amp; BBPOM SOP).</div>
                                                                                    </div>
                                                                                    <div style="min-width: 330px;">
                                                                                        <select
                                                                                            class="form-select fw-bold border-primary select-metode-perhitungan-kadar"
                                                                                            id="f_<?php echo $f['id']; ?>_<?php echo $fld_id; ?>"
                                                                                            name="forms[<?php echo $f['id']; ?>][hasil][<?php echo $fld_id; ?>]"
                                                                                            data-form-id="<?php echo $f['id']; ?>"
                                                                                            <?php echo $fld_req; ?>
                                                                                        >
                                                                                            <?php foreach ($fld['options'] as $opt): ?>
                                                                                                <option value="<?php echo html_escape($opt); ?>" <?php echo ($raw_val === $opt) ? 'selected' : ''; ?>>
                                                                                                    <?php echo html_escape($opt); ?>
                                                                                                </option>
                                                                                            <?php endforeach; ?>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="badge-rumus-kadar-container p-2 rounded bg-white border small font-monospace text-dark">
                                                                                    <i class="bi bi-info-circle-fill text-info me-1"></i>
                                                                                    <span class="text-rumus-kadar">Memuat rumus perhitungan aktif...</span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    <?php else: ?>
                                                                        <div class="col-md-6 <?php echo $cond_class; ?>" data-field-wrapper="<?php echo html_escape($fld_id); ?>" <?php echo !empty($cond_style) ? 'style="' . $cond_style . '"' : ''; ?>>
                                                                            <label for="f_<?php echo $f['id']; ?>_<?php echo $fld_id; ?>" class="form-label">
                                                                                <?php echo html_escape($fld_label); ?>
                                                                                <?php if ($fld_req): ?><span class="text-danger">*</span><?php endif; ?>
                                                                            </label>
                                                                            <select
                                                                                class="form-select"
                                                                                id="f_<?php echo $f['id']; ?>_<?php echo $fld_id; ?>"
                                                                                name="forms[<?php echo $f['id']; ?>][hasil][<?php echo $fld_id; ?>]"
                                                                                <?php echo $fld_req; ?>
                                                                            >
                                                                                <option value="">-- Pilih <?php echo html_escape($fld_label); ?> --</option>
                                                                                <?php foreach ($fld['options'] as $opt): ?>
                                                                                    <option value="<?php echo html_escape($opt); ?>" <?php echo ($raw_val === $opt) ? 'selected' : ''; ?>>
                                                                                        <?php echo html_escape($opt); ?>
                                                                                    </option>
                                                                                <?php endforeach; ?>
                                                                            </select>
                                                                        </div>
                                                                    <?php endif; ?>

                                                                <?php elseif ($fld_type === 'textarea'): ?>
                                                                    <div class="col-12 <?php echo $cond_class; ?>" <?php echo !empty($cond_style) ? 'style="' . $cond_style . '"' : ''; ?>>
                                                                        <label for="f_<?php echo $f['id']; ?>_<?php echo $fld_id; ?>" class="form-label">
                                                                            <?php echo html_escape($fld_label); ?>
                                                                            <?php if ($fld_req): ?><span class="text-danger">*</span><?php endif; ?>
                                                                        </label>
                                                                        <textarea
                                                                            class="form-control"
                                                                            id="f_<?php echo $f['id']; ?>_<?php echo $fld_id; ?>"
                                                                            name="forms[<?php echo $f['id']; ?>][hasil][<?php echo $fld_id; ?>]"
                                                                            rows="2"
                                                                            placeholder="<?php echo html_escape($fld_ph); ?>"
                                                                            <?php echo $fld_req; ?>
                                                                        ><?php echo html_escape($val_text); ?></textarea>
                                                                    </div>

                                                                <?php elseif ($fld_type === 'static_text' || $fld_type === 'display_bound'): ?>
                                                                    <div class="col-md-6 <?php echo $cond_class; ?>" data-field-wrapper="<?php echo html_escape($fld_id); ?>" <?php echo !empty($cond_style) ? 'style="' . $cond_style . '"' : ''; ?>>
                                                                        <label class="form-label text-muted small"><?php echo html_escape($fld_label); ?></label>
                                                                        <div class="p-2 bg-light border rounded small text-dark fw-semibold">
                                                                            <?php
                                                                                if ($fld_type === 'static_text') {
                                                                                    echo html_escape(isset($fld['value']) ? $fld['value'] : '-');
                                                                                } else {
                                                                                    $src = isset($fld['source']) ? $fld['source'] : '';
                                                                                    if ($src === 'testing_sessions.waktu_selesai') {
                                                                                        echo html_escape(!empty($sesi['waktu_selesai']) ? $sesi['waktu_selesai'] : 'Otomatis saat submit');
                                                                                    } elseif ($src === 'testing_sessions.waktu_verifikasi') {
                                                                                        echo html_escape(!empty($sesi['waktu_verifikasi']) ? $sesi['waktu_verifikasi'] : 'Saat verifikasi Penyelia');
                                                                                    } else {
                                                                                        echo html_escape($this->session->userdata('nama_lengkap') ?: '-');
                                                                                    }
                                                                                }
                                                                            ?>
                                                                        </div>
                                                                    </div>

                                                                <?php elseif ($fld_type === 'file'): ?>
                                                                    <div class="col-md-6 <?php echo $cond_class; ?>" data-field-wrapper="<?php echo html_escape($fld_id); ?>" <?php echo !empty($cond_style) ? 'style="' . $cond_style . '"' : ''; ?>>
                                                                        <label for="f_<?php echo $f['id']; ?>_<?php echo $fld_id; ?>" class="form-label">
                                                                            <?php echo html_escape($fld_label); ?>
                                                                            <?php if ($fld_req): ?><span class="text-danger">*</span><?php endif; ?>
                                                                        </label>
                                                                        <input
                                                                            type="file"
                                                                            class="form-control"
                                                                            id="f_<?php echo $f['id']; ?>_<?php echo $fld_id; ?>"
                                                                            name="file_<?php echo $f['id']; ?>_<?php echo $fld_id; ?>"
                                                                            accept="<?php echo html_escape(isset($fld['accept']) ? $fld['accept'] : 'image/*'); ?>"
                                                                        >
                                                                        <input
                                                                            type="hidden"
                                                                            name="forms[<?php echo $f['id']; ?>][hasil][<?php echo $fld_id; ?>]"
                                                                            value="<?php echo html_escape($val_text); ?>"
                                                                        >
                                                                        <?php if (!empty($val_text)): ?>
                                                                            <div class="mt-2 small d-flex align-items-center gap-2">
                                                                                <span class="text-success"><i class="bi bi-check-circle-fill me-1"></i> File:</span>
                                                                                <a href="<?php echo base_url($val_text); ?>" target="_blank" class="text-primary text-decoration-underline"><?php echo html_escape(basename($val_text)); ?></a>
                                                                            </div>
                                                                        <?php endif; ?>
                                                                        <?php if (!empty($fld['keterangan'])): ?>
                                                                            <small class="text-muted d-block mt-1"><?php echo html_escape($fld['keterangan']); ?></small>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                 <?php else: ?>
                                                                    <?php
                                                                        $is_baku_field = in_array($fld_id, array('baku_pembanding', 'baku_nama', 'nama_baku', 'baku_no_kontrol', 'no_kontrol_baku'));
                                                                    ?>
                                                                    <div class="col-md-6 <?php echo $cond_class; ?>" data-field-wrapper="<?php echo html_escape($fld_id); ?>" <?php echo !empty($cond_style) ? 'style="' . $cond_style . '"' : ''; ?>>
                                                                        <div class="d-flex align-items-center justify-content-between mb-1">
                                                                            <label for="f_<?php echo $f['id']; ?>_<?php echo $fld_id; ?>" class="form-label mb-0">
                                                                                <?php echo html_escape($fld_label . $fld_unit); ?>
                                                                                <?php if ($fld_req): ?><span class="text-danger">*</span><?php endif; ?>
                                                                            </label>
                                                                            <?php if ($is_baku_field): ?>
                                                                                <button type="button" 
                                                                                        class="btn btn-xs btn-outline-success py-0 px-2 fw-semibold btn-buka-picker-baku" 
                                                                                        data-form-id="<?php echo $f['id']; ?>"
                                                                                        data-field-id="<?php echo $fld_id; ?>"
                                                                                        style="font-size: 0.73rem; height: 22px;"
                                                                                        title="Pilih dari Data Master Baku (Otomatis isi Nama, No Kontrol, Kadar Air, & Kadar Baku)">
                                                                                    <i class="bi bi-search me-1"></i> Cari Baku
                                                                                </button>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                        
                                                                        <?php if ($is_baku_field): ?>
                                                                            <div class="input-group">
                                                                                <input
                                                                                    type="text"
                                                                                    class="form-control input-baku-field"
                                                                                    id="f_<?php echo $f['id']; ?>_<?php echo $fld_id; ?>"
                                                                                    name="forms[<?php echo $f['id']; ?>][hasil][<?php echo $fld_id; ?>]"
                                                                                    value="<?php echo html_escape($val_text); ?>"
                                                                                    placeholder="<?php echo html_escape($fld_ph); ?>"
                                                                                    data-form-id="<?php echo $f['id']; ?>"
                                                                                    data-field-id="<?php echo $fld_id; ?>"
                                                                                    list="datalist-baku-master"
                                                                                    <?php echo $fld_req; ?>
                                                                                >
                                                                                <button type="button" 
                                                                                        class="btn btn-outline-secondary btn-buka-picker-baku"
                                                                                        data-form-id="<?php echo $f['id']; ?>"
                                                                                        data-field-id="<?php echo $fld_id; ?>"
                                                                                        title="Pilih dari Master Baku Pembanding">
                                                                                    <i class="bi bi-eyedropper text-success"></i>
                                                                                </button>
                                                                            </div>
                                                                        <?php else: ?>
                                                                            <?php
                                                                                $is_fk_final = ($fld_id === 'faktor_perkalian_final');
                                                                                $is_readonly = !empty($fld['readonly']) || $is_fk_final;
                                                                                $is_bm_field = ($fld_id === 'faktor_konversi_bm');
                                                                                $is_fk_tambahan = ($fld_id === 'faktor_pengali_tambahan');
                                                                            ?>
                                                                            <input
                                                                                type="<?php echo ($fld_type === 'number' ? 'number' : ($fld_type === 'date' ? 'date' : 'text')); ?>"
                                                                                step="<?php echo ($fld_type === 'number' ? 'any' : ''); ?>"
                                                                                class="form-control <?php echo $is_bm_field ? 'input-faktor-bm' : ($is_fk_tambahan ? 'input-fk-tambahan' : ''); ?>"
                                                                                id="f_<?php echo $f['id']; ?>_<?php echo $fld_id; ?>"
                                                                                name="forms[<?php echo $f['id']; ?>][hasil][<?php echo $fld_id; ?>]"
                                                                                value="<?php echo html_escape($val_text); ?>"
                                                                                placeholder="<?php echo html_escape($fld_ph); ?>"
                                                                                data-form-id="<?php echo $f['id']; ?>"
                                                                                data-field-id="<?php echo $fld_id; ?>"
                                                                                <?php echo $is_fk_final ? 'readonly style="background-color: #f0fdf4; cursor: not-allowed; font-weight: 700; color: #166534;"' : ($is_readonly ? 'readonly style="background-color: #f8fafc; cursor: not-allowed; font-weight: 600; color: #0284c7;"' : ''); ?>
                                                                                <?php echo $fld_req; ?>
                                                                            >
                                                                            <?php if (!empty($fld['keterangan'])): ?>
                                                                                <small class="text-muted d-block mt-1" style="font-size: 0.76rem;"><?php echo html_escape($fld['keterangan']); ?></small>
                                                                            <?php endif; ?>
                                                                            <?php if ($is_bm_field): ?>
                                                                                <div class="bm-calc-preview mt-1 small" style="display: none;">
                                                                                    <span class="badge bg-light text-dark border">
                                                                                        <i class="bi bi-calculator me-1 text-primary"></i>Hasil Hitung BM: <strong class="bm-eval-val text-primary">1.0000</strong>
                                                                                    </span>
                                                                                </div>
                                                                            <?php endif; ?>
                                                                            <?php if ($is_fk_tambahan): ?>
                                                                                <div class="fk-tambahan-preview mt-1 small" style="display: none;">
                                                                                    <span class="badge bg-light-primary text-primary border border-primary">
                                                                                        <i class="bi bi-x-circle-fill me-1"></i>Pengali Diterapkan: <strong class="fk-tambahan-eval-val">1.0000</strong>
                                                                                    </span>
                                                                                </div>
                                                                            <?php endif; ?>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endforeach; ?>

                                        <?php else: ?>

                                            <!-- Fallback: Render Skema Flat Standar (Format Template Lama) -->
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

                                                            // Handle data_hasil value safely (prioritizing saved_param_values)
                                                            $raw_val = isset($saved_param_values[$key])
                                                                ? $saved_param_values[$key]
                                                                : (isset($data_hasil[$key]) ? $data_hasil[$key] : '');

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

                                        <?php endif; ?>

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
                                                    class="form-label d-flex align-items-center justify-content-between"
                                                >
                                                    <span>Kesimpulan Form #<?php echo ($idx + 1); ?> <span class="required-mark">*</span></span>
                                                    <span id="badge_kesimpulan_<?php echo $f['id']; ?>" class="badge <?php echo ($f['kesimpulan'] === 'Memenuhi Syarat (MS)') ? 'bg-success' : (($f['kesimpulan'] === 'Tidak Memenuhi Syarat (TMS)') ? 'bg-danger' : 'bg-secondary'); ?>" style="font-size: 0.72rem;">
                                                        <?php echo html_escape($f['kesimpulan'] ?: 'Belum Disimpulkan'); ?>
                                                    </span>
                                                </label>

                                                <select
                                                    class="form-select select-kesimpulan-auto fw-bold"
                                                    id="kesimpulan_<?php echo $f['id']; ?>"
                                                    name="forms[<?php echo $f['id']; ?>][kesimpulan]"
                                                    data-form-id="<?php echo $f['id']; ?>"
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

                                                <!-- Indikator Auto Kesimpulan Kriteria -->
                                                <div id="alert_kriteria_<?php echo $f['id']; ?>" class="auto-kesimpulan-alert-box mt-2 p-2 rounded shadow-sm" style="font-size: 0.76rem; display: none;"></div>

                                                <?php if (!empty($f['persyaratan'])): ?>
                                                    <div class="form-text text-muted mt-1 d-flex align-items-center gap-1" style="font-size: 0.72rem;">
                                                        <i class="bi bi-shield-check text-primary"></i>
                                                        <span>Syarat Spesifikasi: <strong class="text-dark"><?php echo html_escape($f['persyaratan']); ?></strong></span>
                                                    </div>
                                                <?php endif; ?>

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

                                            <!-- Kotak Upload Lampiran Hasil Alat Laboratorium -->
                                            <?php
                                                $cur_lampiran = !empty($data_hasil['lampiran_alat']) 
                                                    ? $data_hasil['lampiran_alat'] 
                                                    : (!empty($saved_param_values['lampiran_alat']) ? $saved_param_values['lampiran_alat'] : '');
                                                $has_lampiran = !empty($cur_lampiran) && file_exists(FCPATH . $cur_lampiran);
                                                $lampiran_ext = $has_lampiran ? strtolower(pathinfo($cur_lampiran, PATHINFO_EXTENSION)) : '';
                                                $is_lampiran_pdf = ($lampiran_ext === 'pdf');
                                            ?>
                                            <div class="col-12 mt-3 pt-3 border-top">
                                                <div class="p-3 bg-light rounded border">
                                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                                        <label for="lampiran_alat_<?php echo $f['id']; ?>" class="form-label fw-bold mb-0 text-dark" style="font-size: 0.82rem;">
                                                            <i class="bi bi-paperclip text-primary me-1"></i>Lampiran Hasil Alat Laboratorium (PDF, JPG, PNG)
                                                        </label>
                                                        <span class="badge bg-secondary-subtle text-secondary border" style="font-size: 0.65rem;">
                                                            Otomatis Digabung ke Halaman Akhir LCP
                                                        </span>
                                                    </div>
                                                    <p class="text-muted small mb-2" style="font-size: 0.72rem; line-height: 1.4;">
                                                        Unggah printout / scan hasil instrumen pengujian alat (contoh: kromatogram HPLC, spektrum Spektrofotometri, hasil dissolution tester, foto cawan petri, dsb). Berkas akan otomatis digabungkan pada halaman kosong terakhir cetak LCP.
                                                    </p>
                                                    <div class="row align-items-center g-2">
                                                        <div class="col-md-7">
                                                            <input
                                                                type="file"
                                                                class="form-control form-control-sm"
                                                                id="lampiran_alat_<?php echo $f['id']; ?>"
                                                                name="file_<?php echo $f['id']; ?>_lampiran_alat"
                                                                accept=".pdf,.jpg,.jpeg,.png"
                                                            >
                                                            <input
                                                                type="hidden"
                                                                name="forms[<?php echo $f['id']; ?>][hasil][lampiran_alat]"
                                                                value="<?php echo html_escape($cur_lampiran); ?>"
                                                            >
                                                        </div>
                                                        <div class="col-md-5">
                                                            <?php if ($has_lampiran): ?>
                                                                <div class="d-flex align-items-center gap-2 p-1 px-2 bg-white rounded border">
                                                                    <i class="bi <?php echo $is_lampiran_pdf ? 'bi-file-earmark-pdf text-danger' : 'bi-file-earmark-image text-success'; ?> fs-5"></i>
                                                                    <div class="flex-grow-1 overflow-hidden" style="line-height: 1.2;">
                                                                        <span class="d-block text-truncate fw-semibold text-dark" style="font-size: 0.72rem;" title="<?php echo html_escape(basename($cur_lampiran)); ?>">
                                                                            <?php echo html_escape(basename($cur_lampiran)); ?>
                                                                        </span>
                                                                        <small class="text-success" style="font-size: 0.65rem;"><i class="bi bi-check-circle-fill me-1"></i>Tersimpan</small>
                                                                    </div>
                                                                    <a href="<?php echo base_url($cur_lampiran); ?>" target="_blank" class="btn btn-outline-primary btn-sm py-0 px-2" style="font-size: 0.7rem;" title="Lihat Berkas Lampiran">
                                                                        <i class="bi bi-eye"></i>
                                                                    </a>
                                                                </div>
                                                            <?php else: ?>
                                                                <small class="text-muted fst-italic" style="font-size: 0.72rem;">
                                                                    <i class="bi bi-info-circle me-1"></i>Belum ada lampiran diunggah.
                                                                </small>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
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
                        Kembali ke Antrean
                    </a>

                    <div class="d-flex align-items-center gap-2 flex-wrap">

                        <button
                            type="button"
                            id="btnSimpanSementara"
                            class="proses-btn proses-btn-draft"
                            title="Simpan progres pengisian saat ini ke database tanpa mengirim ke penyelia. Anda bisa melanjutkannya kapan saja."
                        >
                            <i class="bi bi-save2"></i>
                            <span id="btnDraftText">Simpan Sementara (Draft)</span>
                        </button>

                        <button
                            type="submit"
                            id="btnKirimVerifikasi"
                            class="proses-btn proses-btn-submit"
                            onclick="return konfirmasiKirimVerifikasi();"
                        >
                            <i class="bi bi-check2-circle"></i>
                            Simpan Seluruh Hasil Sesi &amp; Kirim Verifikasi
                        </button>

                    </div>

                </div>

            </form>

        </div>
<!-- Datalist Master Baku Pembanding untuk Quick Autocomplete -->
<datalist id="datalist-baku-master">
    <?php if (!empty($daftar_baku)): ?>
        <?php foreach ($daftar_baku as $b): ?>
            <?php 
                $label_opt = $b['nama_baku'] . (!empty($b['no_kontrol']) ? ' [No: ' . $b['no_kontrol'] . ']' : '');
                if ($b['kadar_baku'] !== null) $label_opt .= ' (KCKT: ' . number_format($b['kadar_baku'], 2) . '%)';
                if ($b['kadar_air'] !== null) $label_opt .= ' (Air: ' . number_format($b['kadar_air'], 2) . '%)';
            ?>
            <option value="<?php echo html_escape($b['nama_baku'] . (!empty($b['no_kontrol']) ? ' No. Kontrol: ' . $b['no_kontrol'] : '')); ?>" 
                    label="<?php echo html_escape($label_opt); ?>">
            </option>
        <?php endforeach; ?>
    <?php endif; ?>
</datalist>

<!-- Modal Salin Preparasi dari Riwayat Pengujian Lain (Clean & Modern Design) -->
<div class="modal fade" id="modalCopyPreparasi" tabindex="-1" aria-labelledby="modalCopyPreparasiLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-3 overflow-hidden">
            <!-- Header Bersih -->
            <div class="modal-header bg-white border-bottom px-4 py-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-primary-subtle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                        <i class="bi bi-copy text-primary fs-5"></i>
                    </div>
                    <div>
                        <h6 class="modal-title fw-bold text-dark mb-0" id="modalCopyPreparasiLabel">Salin Data Preparasi Sampel</h6>
                        <small class="text-muted" style="font-size: 0.76rem;" id="modalCopySubtitle">Pilih sampel sebelumnya untuk menyalin alat, reagen, dan baku</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4 bg-light-subtle">
                <!-- Search Box Bersih -->
                <div class="input-group mb-3 bg-white rounded-3 border shadow-sm">
                    <span class="input-group-text bg-transparent border-0 text-muted ps-3"><i class="bi bi-search"></i></span>
                    <input type="text" id="inputCariRiwayatPreparasi" class="form-control border-0 py-2" placeholder="Cari nama sampel, kode sampel, nomor kontrol, atau tanggal..." autocomplete="off" style="font-size: 0.85rem;">
                    <button class="btn btn-link text-muted pe-3 text-decoration-none" type="button" id="btnResetSearchPreparasi" title="Reset pencarian"><i class="bi bi-x-circle"></i></button>
                </div>

                <!-- Info Banner Minimalis -->
                <div class="d-flex align-items-center gap-2 px-3 py-2 rounded-2 mb-3 bg-white border text-secondary" style="font-size: 0.76rem;">
                    <i class="bi bi-shield-check text-success fs-6"></i>
                    <span>Menyalin alat, reagen, dan kondisi baku. <strong class="text-dark">Data hasil uji sampel baru tetap dibiarkan kosong.</strong></span>
                </div>

                <!-- Loading State -->
                <div id="loadingRiwayatPreparasi" class="text-center py-5 d-none">
                    <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                    <div class="small text-muted mt-2">Memuat riwayat pengujian...</div>
                </div>

                <!-- Empty State -->
                <div id="emptyRiwayatPreparasi" class="text-center py-5 text-muted bg-white rounded-3 border d-none">
                    <i class="bi bi-folder2-open fs-1 d-block mb-2 text-secondary opacity-50"></i>
                    <div class="fw-semibold text-dark small">Belum Ada Riwayat Tersedia</div>
                    <div class="small text-muted mt-1">Belum ada pengujian sebelumnya yang menggunakan parameter metode ini.</div>
                </div>

                <!-- Daftar Riwayat Sampel (Card List Clean) -->
                <div id="containerTabelPreparasi" class="overflow-auto pe-1" style="max-height: 380px;">
                    <div class="d-flex flex-column gap-2" id="tbodyModalPreparasi">
                        <!-- Diisi via JavaScript -->
                    </div>
                </div>
            </div>

            <div class="modal-footer py-2 px-4 bg-white border-top d-flex justify-content-between align-items-center">
                <span class="small text-muted" style="font-size: 0.75rem;" id="modalPreparasiCounter">Menampilkan riwayat</span>
                <button type="button" class="btn btn-light btn-sm px-3 rounded-pill" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Search & Pilih Master Baku Pembanding (Clean & Modern Design) -->
<div class="modal fade" id="modalPickerBaku" tabindex="-1" aria-labelledby="modalPickerBakuLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-3 overflow-hidden">
            <!-- Header Bersih & Modern -->
            <div class="modal-header bg-white border-bottom px-4 py-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-success-subtle d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                        <i class="bi bi-eyedropper text-success fs-5"></i>
                    </div>
                    <div>
                        <h6 class="modal-title fw-bold text-dark mb-0" id="modalPickerBakuLabel">Pilih Baku Pembanding Laboratorium</h6>
                        <small class="text-muted" style="font-size: 0.76rem;">Pilih master baku pembanding untuk mengisi otomatis nama, nomor kontrol, kadar baku, dan kadar air</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-4 bg-light">
                <!-- Search Box Clean -->
                <div class="card border-0 shadow-sm rounded-3 p-2 mb-3 bg-white">
                    <div class="input-group border-0">
                        <span class="input-group-text bg-transparent border-0 text-muted ps-2"><i class="bi bi-search"></i></span>
                        <input type="text" id="inputCariModalBaku" class="form-control border-0 shadow-none ps-1" placeholder="Ketik nama baku, nomor kontrol, atau lokasi rak..." autocomplete="off" style="font-size: 0.88rem;">
                        <button class="btn btn-link text-muted pe-2 text-decoration-none" type="button" id="btnResetSearchBaku" title="Reset pencarian"><i class="bi bi-x-circle-fill"></i></button>
                    </div>
                </div>

                <!-- Card List Baku Pembanding (Clean Card List) -->
                <div id="containerListBaku" class="overflow-auto pe-1" style="max-height: 400px;">
                    <div class="d-flex flex-column gap-2" id="tbodyModalBaku">
                        <?php if (!empty($daftar_baku)): ?>
                            <?php foreach ($daftar_baku as $b): ?>
                                <div class="card border rounded-3 p-3 bg-white shadow-sm item-baris-baku"
                                    style="transition: all 0.2s ease;"
                                    data-id="<?php echo $b['id']; ?>"
                                    data-nama="<?php echo html_escape($b['nama_baku']); ?>"
                                    data-kontrol="<?php echo html_escape($b['no_kontrol'] ?: ''); ?>"
                                    data-kode="<?php echo html_escape($b['kode_posisi'] ?: ''); ?>"
                                    data-kadar="<?php echo ($b['kadar_baku'] !== null) ? number_format($b['kadar_baku'], 2, '.', '') : ''; ?>"
                                    data-air="<?php echo ($b['kadar_air'] !== null) ? number_format($b['kadar_air'], 2, '.', '') : ''; ?>"
                                    data-lokasi="<?php echo html_escape($b['lokasi_penyimpanan']); ?>"
                                    data-kemurnian="<?php echo html_escape($b['kemurnian_raw'] ?: ''); ?>"
                                    data-search="<?php echo strtolower(html_escape($b['nama_baku'] . ' ' . $b['no_kontrol'] . ' ' . $b['kode_posisi'] . ' ' . $b['lokasi_penyimpanan'])); ?>">
                                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                        <div style="flex: 1 1 260px;">
                                            <div class="d-flex align-items-center flex-wrap gap-2 mb-1">
                                                <strong class="text-dark" style="font-size: 0.90rem;"><?php echo html_escape($b['nama_baku']); ?></strong>
                                                <?php if (!empty($b['no_kontrol'])): ?>
                                                    <span class="badge bg-secondary-subtle text-secondary font-monospace" style="font-size: 0.68rem;">
                                                        <?php echo html_escape($b['no_kontrol']); ?>
                                                    </span>
                                                <?php endif; ?>
                                                <?php if (!empty($b['kode_posisi'])): ?>
                                                    <span class="badge bg-primary-subtle text-primary font-monospace" style="font-size: 0.68rem;">
                                                        <?php echo html_escape($b['kode_posisi']); ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="d-flex align-items-center flex-wrap gap-2 text-muted" style="font-size: 0.74rem;">
                                                <span><i class="bi bi-geo-alt me-1 text-secondary"></i><?php echo html_escape($b['lokasi_penyimpanan'] ?: 'Ruang Baku'); ?></span>
                                                <?php if (!empty($b['kemurnian_raw'])): ?>
                                                    <span>&bull;</span>
                                                    <span>Kemurnian: <strong class="text-dark"><?php echo html_escape($b['kemurnian_raw']); ?></strong></span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center flex-wrap gap-2">
                                            <div class="text-end d-none d-sm-block me-2" style="font-size: 0.74rem;">
                                                <div class="mb-1">
                                                    Kadar: <?php if ($b['kadar_baku'] !== null): ?><strong class="text-success"><?php echo number_format($b['kadar_baku'], 2); ?>%</strong><?php else: ?><span class="text-muted">-</span><?php endif; ?>
                                                    &bull; Air: <?php if ($b['kadar_air'] !== null): ?><strong class="text-info"><?php echo number_format($b['kadar_air'], 2); ?>%</strong><?php else: ?><span class="text-muted">-</span><?php endif; ?>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-success rounded-pill px-3 py-1 btn-pilih-baku-item d-flex align-items-center gap-1 shadow-sm" title="Pilih baku ini">
                                                <i class="bi bi-check2"></i>
                                                <span>Pilih</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center py-4 text-muted bg-white rounded-3 border">Belum ada data master baku pembanding yang tersedia.</div>
                        <?php endif; ?>
                    </div>
                    <div id="emptyNoticeBaku" class="text-center py-4 text-muted bg-white rounded-3 border d-none">
                        <i class="bi bi-search me-1"></i> Tidak ditemukan baku pembanding yang sesuai.
                    </div>
                </div>
            </div>

            <div class="modal-footer py-2 px-4 bg-white border-top d-flex justify-content-between align-items-center">
                <span class="small text-muted" style="font-size: 0.75rem;" id="modalBakuCounter">Menampilkan semua data baku pembanding</span>
                <button type="button" class="btn btn-light btn-sm px-3 rounded-pill" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
    </div>
</div>
</div>

<style>
    .info-detail-container {
        font-size: 0.78rem;
    }
    .info-item-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 8px 12px;
        margin-bottom: 6px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
        transition: all 0.15s ease;
    }
    .info-item-card:hover {
        border-color: #cbd5e1;
        background: #f8fafc;
    }
    .info-item-card:last-child {
        margin-bottom: 0;
    }
    .info-item-card.card-alat {
        border-left: 3px solid #38bdf8;
    }
    .info-item-card.card-reagen {
        border-left: 3px solid #2dd4bf;
    }
    .info-item-title {
        color: #1e293b;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        margin-bottom: 4px;
        font-size: 0.82rem;
    }
    .info-meta-chips {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 6px 12px;
        font-size: 0.74rem;
        color: #64748b;
    }
    .info-meta-chips .chip-item {
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Fungsi untuk memperbarui panel info Alat Laboratorium
        function updateAlatInfo(selectElem) {
            var targetSelector = selectElem.getAttribute('data-target-info');
            if (!targetSelector) return;
            var container = document.querySelector(targetSelector);
            if (!container) return;

            var selectedOptions = Array.from(selectElem.selectedOptions);
            if (selectedOptions.length === 0) {
                container.innerHTML = '<div class="text-muted small fst-italic p-2 bg-white rounded border border-dashed"><i class="bi bi-info-circle me-1"></i>Belum ada alat laboratorium yang dipilih.</div>';
                return;
            }

            var html = '';
            selectedOptions.forEach(function(opt) {
                if (opt.disabled) return;
                var kode = opt.getAttribute('data-kode') || '-';
                var nama = opt.getAttribute('data-nama') || '-';
                var merk = opt.getAttribute('data-merk') || '-';
                var sw   = opt.getAttribute('data-software') || '-';
                var det  = opt.getAttribute('data-detektor') || '-';
                var rgn  = opt.getAttribute('data-ruangan') || '-';
                var adm  = opt.getAttribute('data-kondisi') || '-';

                html += '<div class="info-item-card card-alat">' +
                        '  <div class="info-item-title">' +
                        '    <span class="text-truncate fw-semibold"><i class="bi bi-cpu text-primary me-1"></i>' + escapeHtml(nama) + '</span>' +
                        '    <span class="badge bg-light text-muted border font-monospace flex-shrink-0" style="font-size: 0.70rem;">' + escapeHtml(kode) + '</span>' +
                        '  </div>' +
                        '  <div class="info-meta-chips">' +
                        (merk !== '-' ? '<span class="chip-item">Merk: <strong class="text-dark">' + escapeHtml(merk) + '</strong></span>' : '') +
                        (rgn !== '-' ? '<span class="chip-item">Ruang: <span class="text-dark">' + escapeHtml(rgn) + '</span></span>' : '') +
                        (sw !== '-' ? '<span class="chip-item">Software: <span class="text-dark">' + escapeHtml(sw) + '</span></span>' : '') +
                        (det !== '-' ? '<span class="chip-item">Detektor: <span class="text-dark">' + escapeHtml(det) + '</span></span>' : '') +
                        (adm !== '-' ? '<span class="chip-item">Kondisi: <span class="badge bg-success-subtle text-success py-0 px-1 border border-success-subtle">' + escapeHtml(adm) + '</span></span>' : '') +
                        '  </div>' +
                        '</div>';
            });
            container.innerHTML = html;
        }

        // Fungsi untuk memperbarui panel info Reagen Laboratorium
        function updateReagenInfo(selectElem) {
            var targetSelector = selectElem.getAttribute('data-target-info');
            if (!targetSelector) return;
            var container = document.querySelector(targetSelector);
            if (!container) return;

            var selectedOptions = Array.from(selectElem.selectedOptions);
            if (selectedOptions.length === 0) {
                container.innerHTML = '<div class="text-muted small fst-italic p-2 bg-white rounded border border-dashed"><i class="bi bi-info-circle me-1"></i>Belum ada reagen yang dipilih.</div>';
                return;
            }

            var html = '';
            selectedOptions.forEach(function(opt) {
                if (opt.disabled) return;
                var kode    = opt.getAttribute('data-kode') || '-';
                var katalog = opt.getAttribute('data-katalog') || '-';
                var nama    = opt.getAttribute('data-nama') || '-';
                var merk    = opt.getAttribute('data-merk') || '-';
                var stok    = opt.getAttribute('data-stok') || '-';
                var jenis   = opt.getAttribute('data-jenis') || '-';
                var exp     = opt.getAttribute('data-exp') || '-';
                var isExp   = (exp !== '-' && new Date(exp) < new Date());

                html += '<div class="info-item-card card-reagen">' +
                        '  <div class="info-item-title">' +
                        '    <span class="text-truncate fw-semibold"><i class="bi bi-droplet-half text-info me-1"></i>' + escapeHtml(nama) + '</span>' +
                        (katalog !== '-' ? '<span class="badge bg-info-subtle text-info border border-info-subtle font-monospace flex-shrink-0" style="font-size: 0.70rem;">Kat: ' + escapeHtml(katalog) + '</span>' : '') +
                        '  </div>' +
                        '  <div class="info-meta-chips">' +
                        (kode !== '-' ? '<span class="chip-item">Kode: <span class="text-dark font-monospace">' + escapeHtml(kode) + '</span></span>' : '') +
                        (merk !== '-' ? '<span class="chip-item">Merk: <strong class="text-dark">' + escapeHtml(merk) + '</strong></span>' : '') +
                        (stok !== '-' ? '<span class="chip-item">Stok: <strong class="' + (stok.indexOf('0') === 0 ? 'text-secondary' : 'text-success') + '">' + escapeHtml(stok) + '</strong></span>' : '') +
                        (exp !== '-' ? '<span class="chip-item">Exp: <strong class="' + (isExp ? 'text-danger' : 'text-dark') + '">' + escapeHtml(exp) + '</strong></span>' : '') +
                        (jenis !== '-' ? '<span class="chip-item"><span class="badge bg-light text-secondary border py-0 px-1">' + escapeHtml(jenis) + '</span></span>' : '') +
                        '  </div>' +
                        '</div>';
            });
            container.innerHTML = html;
        }

        function escapeHtml(text) {
            if (!text) return '';
            return String(text)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        // =========================================================================
        // Live Search Filter untuk Picker Alat & Reagen
        // =========================================================================
        function initSearchablePicker(box) {
            var searchInput = box.querySelector('.picker-search-input');
            var clearBtn    = box.querySelector('.btn-clear-picker-search');
            var selectElem  = box.querySelector('.searchable-picker-select');
            var emptyNotice = box.querySelector('.searchable-picker-empty');

            if (!searchInput || !selectElem) return;

            var allOptions = Array.from(selectElem.options);

            function filterOptions() {
                var query = searchInput.value.trim().toLowerCase();
                var visibleCount = 0;

                allOptions.forEach(function(opt) {
                    // Option status bawaan atau placeholder tidak difilter
                    if (opt.value === '' && opt.disabled) return;

                    var text = (opt.textContent || '').toLowerCase();
                    var match = (query === '' || text.indexOf(query) !== -1);

                    // Di multi-select standard browser, hidden / style.display = 'none' menyembunyikan option
                    if (match) {
                        opt.hidden = false;
                        opt.style.display = '';
                        visibleCount++;
                    } else {
                        opt.hidden = true;
                        opt.style.display = 'none';
                    }
                });

                if (clearBtn) {
                    clearBtn.style.display = query !== '' ? 'block' : 'none';
                }

                if (emptyNotice) {
                    emptyNotice.style.display = (visibleCount === 0 && allOptions.length > 0) ? 'block' : 'none';
                }
            }

            searchInput.addEventListener('input', filterOptions);

            if (clearBtn) {
                clearBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    searchInput.value = '';
                    searchInput.focus();
                    filterOptions();
                });
            }
        }

        // Fitur klik langsung toggle pilihan (tanpa perlu tekan Ctrl)
        function enableEasyMultiSelect(selectElem) {
            selectElem.addEventListener('mousedown', function(e) {
                var opt = e.target;
                if (!opt || opt.tagName !== 'OPTION' || opt.disabled) return;
                
                e.preventDefault(); // Mencegah perilaku default browser yang mereset pilihan lain jika tanpa Ctrl
                
                // Toggle status selected
                opt.selected = !opt.selected;
                
                // Fokuskan ke select agar keyboard navigation tetap hidup jika dibutuhkan
                selectElem.focus();
                
                // Trigger event change secara manual agar listener pembaruan panel info berjalan
                var evt = new Event('change', { bubbles: true });
                selectElem.dispatchEvent(evt);
            });
        }

        document.querySelectorAll('.select-alat-instrument, .select-reagen-material').forEach(function(el) {
            enableEasyMultiSelect(el);
        });

        // Inisialisasi semua picker alat & reagen di semua formulir
        document.querySelectorAll('.searchable-picker-box').forEach(function(box) {
            initSearchablePicker(box);
        });

        // Pasang event listener change pada semua select alat & reagen
        document.querySelectorAll('.select-alat-instrument').forEach(function(el) {
            el.addEventListener('change', function() { updateAlatInfo(this); });
            updateAlatInfo(el); // trigger awal saat halaman dimuat
        });

        document.querySelectorAll('.select-reagen-material').forEach(function(el) {
            el.addEventListener('change', function() { updateReagenInfo(this); });
            updateReagenInfo(el); // trigger awal saat halaman dimuat
        });

        // Helper untuk parse float yang aman dari koma (format Indonesia seperti 432,49) maupun titik
        function parseNum(val) {
            if (val === undefined || val === null) return 0;
            if (typeof val === 'number') return isNaN(val) ? 0 : val;
            var str = val.toString().trim();
            if (!str) return 0;
            // Ganti koma dengan titik jika koma dipakai sebagai pemisah desimal
            str = str.replace(',', '.');
            var num = parseFloat(str);
            return isNaN(num) ? 0 : num;
        }

        // =========================================================================
        // MESIN EVALUASI KESIMPULAN OTOMATIS BERDASARKAN SPESIFIKASI & METODE
        // Otomatis menetapkan 'Memenuhi Syarat (MS)' atau 'Tidak Memenuhi Syarat (TMS)'
        // =========================================================================

        /**
         * Escape HTML string
         */
        function safeHtml(str) {
            if (!str) return '';
            // Unescape entitas lama jika sebelumnya tersimpan sebagai entity
            var unescaped = String(str)
                .replace(/&lt;/gi, '<')
                .replace(/&gt;/gi, '>')
                .replace(/&amp;/gi, '&')
                .replace(/&quot;/gi, '"')
                .replace(/&#039;/gi, "'");
            return unescaped
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;');
        }

        /**
         * Mengupdate nilai dropdown kesimpulan, badge status tampilan, dan box keterangan kriteria
         */
        function setKesimpulanOtomatis(formId, kesimpulan, alasan) {
            var pane = document.getElementById('tab-pane-' + formId);
            if (!pane) return;

            var selectKes = document.getElementById('kesimpulan_' + formId);
            var badgeKes = document.getElementById('badge_kesimpulan_' + formId);
            var alertBox = document.getElementById('alert_kriteria_' + formId);

            if (selectKes) {
                var prevVal = selectKes.value;
                selectKes.value = kesimpulan;

                // Visual styling pada dropdown kesimpulan
                selectKes.classList.remove('is-valid', 'is-invalid');
                if (kesimpulan === 'Memenuhi Syarat (MS)') {
                    selectKes.classList.add('is-valid');
                    selectKes.style.borderColor = '#198754';
                    selectKes.style.backgroundColor = '#f0fdf4';
                    selectKes.style.color = '#15803d';
                } else if (kesimpulan === 'Tidak Memenuhi Syarat (TMS)') {
                    selectKes.classList.add('is-invalid');
                    selectKes.style.borderColor = '#dc3545';
                    selectKes.style.backgroundColor = '#fef2f2';
                    selectKes.style.color = '#b91c1c';
                } else {
                    selectKes.style.borderColor = '';
                    selectKes.style.backgroundColor = '';
                    selectKes.style.color = '';
                }

                if (prevVal !== kesimpulan) {
                    selectKes.dispatchEvent(new Event('change', { bubbles: true }));
                }
            }

            if (badgeKes) {
                badgeKes.textContent = kesimpulan;
                badgeKes.classList.remove('bg-success', 'bg-danger', 'bg-secondary', 'bg-warning');
                if (kesimpulan === 'Memenuhi Syarat (MS)') {
                    badgeKes.classList.add('bg-success');
                } else if (kesimpulan === 'Tidak Memenuhi Syarat (TMS)') {
                    badgeKes.classList.add('bg-danger');
                } else {
                    badgeKes.classList.add('bg-secondary');
                }
                if (alasan) {
                    badgeKes.setAttribute('title', alasan);
                }
            }

            // Sinkronkan juga ke select kesimpulan internal template jika ada
            var innerSelects = pane.querySelectorAll('select[name*="[kesimpulan]"]');
            innerSelects.forEach(function(sel) {
                if (sel.id !== 'kesimpulan_' + formId) {
                    sel.value = kesimpulan;
                }
            });

            // Tampilkan alert box keterangan kriteria otomatis
            if (alertBox && alasan) {
                var isMS = (kesimpulan === 'Memenuhi Syarat (MS)');
                alertBox.style.display = 'block';
                alertBox.style.backgroundColor = isMS ? '#f0fdf4' : '#fef2f2';
                alertBox.style.border = isMS ? '1px solid #bbf7d0' : '1px solid #fecaca';
                alertBox.style.color = isMS ? '#166534' : '#991b1b';
                alertBox.innerHTML = '<i class="bi ' + (isMS ? 'bi-check-circle-fill text-success' : 'bi-exclamation-triangle-fill text-danger') + ' me-1"></i> ' +
                    '<strong>Evaluasi Kriteria:</strong> ' + safeHtml(alasan);
            }
        }

        /**
         * Evaluasi kesimpulan untuk satu form tab pane berdasarkan template & persyaratan monografi
         */
        function evaluasiKesimpulanPane(pane) {
            if (!pane) return;
            var formId = pane.getAttribute('data-form-id');
            var kodeTpl = (pane.getAttribute('data-kode-template') || '').toUpperCase();
            var namaTpl = (pane.getAttribute('data-nama-template') || '').toLowerCase();
            var syaratStr = (pane.getAttribute('data-syarat') || '').replace(/,/g, '.');
            var paramStr = (pane.getAttribute('data-parameter') || '').toLowerCase();

            // 0. FORM IDENTIFIKASI (Kualitatif: Positif/Negatif)
            var isIdent = (pane.getAttribute('data-is-identifikasi') === '1') || paramStr.indexOf('identifikasi') !== -1 || namaTpl.indexOf('identifikasi') !== -1;
            if (isIdent) {
                evaluasiIdentifikasi(pane, formId);
                return;
            }

            // 1. FORM DISOLUSI (KIM-06 atau nama / parameter mengandung disolusi)
            if (kodeTpl === 'KIM-06' || namaTpl.indexOf('disolusi') !== -1 || paramStr.indexOf('disolusi') !== -1) {
                evaluasiDisolusi(pane, formId, syaratStr);
                return;
            }

            // 2. KERAGAMAN BOBOT (KIM-02) & KESERAGAMAN KANDUNGAN (KIM-03)
            if (kodeTpl === 'KIM-02' || kodeTpl === 'KIM-03' || namaTpl.indexOf('keragaman bobot') !== -1 || namaTpl.indexOf('keseragaman kandungan') !== -1) {
                evaluasiKeseragamanSediaan(pane, formId);
                return;
            }

            // 3. PENETAPAN KADAR (KIM-05 Spektro, KIM-08 HPLC, KIM-01 Titrimetri, atau parameter kadar)
            if (kodeTpl === 'KIM-05' || kodeTpl === 'KIM-08' || kodeTpl === 'KIM-01' || namaTpl.indexOf('kadar') !== -1 || paramStr.indexOf('kadar') !== -1) {
                evaluasiPenetapanKadar(pane, formId, syaratStr, kodeTpl);
                return;
            }

            // 4. PENETAPAN pH (KIM-11 atau parameter pH)
            if (kodeTpl === 'KIM-11' || namaTpl.indexOf('ph') !== -1 || paramStr.indexOf('ph') !== -1) {
                evaluasiPH(pane, formId, syaratStr);
                return;
            }

            // 5. WAKTU HANCUR (KIM-04 atau parameter waktu hancur)
            if (kodeTpl === 'KIM-04' || namaTpl.indexOf('waktu hancur') !== -1 || paramStr.indexOf('waktu hancur') !== -1) {
                evaluasiWaktuHancur(pane, formId, syaratStr);
                return;
            }

            // 6. BOBOT JENIS (KIM-12 atau parameter bobot jenis)
            if (kodeTpl === 'KIM-12' || namaTpl.indexOf('bobot jenis') !== -1 || paramStr.indexOf('bobot jenis') !== -1) {
                evaluasiBobotJenis(pane, formId, syaratStr);
                return;
            }

            // 7. Generic Fallback Evaluasi untuk form lainnya
            evaluasiGeneric(pane, formId, syaratStr);
        }

        /**
         * Mengatur visibilitas seksi tahap pengujian opsional (Tahap 2 Keragaman Bobot, Tahap S2 Disolusi, Tahap S3 Disolusi)
         * Jika trigger dipilih 'Ya', tampilkan item kondisional; jika 'Tidak', sembunyikan.
         */
        function updateTahapVisibility(pane) {
            if (!pane) return;

            // 1. Seksi Tahap 2 (KIM-02 Keragaman Bobot & KIM-03 Keseragaman Kandungan)
            var secTahap2 = pane.querySelector('[data-section-id="tahap_2"]');
            if (secTahap2) {
                var trigT2 = secTahap2.querySelector('.select-tahap-trigger') || pane.querySelector('select[name*="[lakukan_tahap2]"]');
                var isAktifT2 = trigT2 && trigT2.value === 'Ya';
                var condItemsT2 = secTahap2.querySelectorAll('.tahap-conditional-item');
                condItemsT2.forEach(function(el) {
                    el.style.display = isAktifT2 ? '' : 'none';
                });
                var wrapT2 = secTahap2.querySelector('.control-tahap-wrapper');
                if (wrapT2) {
                    if (isAktifT2) {
                        wrapT2.classList.remove('bg-light', 'border-secondary');
                        wrapT2.classList.add('bg-light-primary', 'border-primary');
                    } else {
                        wrapT2.classList.remove('bg-light-primary', 'border-primary');
                        wrapT2.classList.add('bg-light', 'border-secondary');
                    }
                }
            }

            // 2. Seksi Data Tahap S2 (KIM-06 Disolusi)
            var secS2 = pane.querySelector('[data-section-id="data_tahap_s2"]');
            if (secS2) {
                var trigS2 = secS2.querySelector('.select-tahap-trigger') || pane.querySelector('select[name*="[lakukan_tahap_s2]"]');
                var isAktifS2 = trigS2 && trigS2.value === 'Ya';
                var condItemsS2 = secS2.querySelectorAll('.tahap-conditional-item');
                condItemsS2.forEach(function(el) {
                    el.style.display = isAktifS2 ? '' : 'none';
                });
                var wrapS2 = secS2.querySelector('.control-tahap-wrapper');
                if (wrapS2) {
                    if (isAktifS2) {
                        wrapS2.classList.remove('bg-light', 'border-secondary');
                        wrapS2.classList.add('bg-light-primary', 'border-primary');
                    } else {
                        wrapS2.classList.remove('bg-light-primary', 'border-primary');
                        wrapS2.classList.add('bg-light', 'border-secondary');
                    }
                }
            }

            // 3. Seksi Data Tahap S3 (KIM-06 Disolusi)
            var secS3 = pane.querySelector('[data-section-id="data_tahap_s3"]');
            if (secS3) {
                var trigS3 = secS3.querySelector('.select-tahap-trigger') || pane.querySelector('select[name*="[lakukan_tahap_s3]"]');
                var isAktifS3 = trigS3 && trigS3.value === 'Ya';
                var condItemsS3 = secS3.querySelectorAll('.tahap-conditional-item');
                condItemsS3.forEach(function(el) {
                    el.style.display = isAktifS3 ? '' : 'none';
                });
                var wrapS3 = secS3.querySelector('.control-tahap-wrapper');
                if (wrapS3) {
                    if (isAktifS3) {
                        wrapS3.classList.remove('bg-light', 'border-secondary');
                        wrapS3.classList.add('bg-light-primary', 'border-primary');
                    } else {
                        wrapS3.classList.remove('bg-light-primary', 'border-primary');
                        wrapS3.classList.add('bg-light', 'border-secondary');
                    }
                }
            }
        }

        /**
         * Evaluasi Disolusi Bertahap (Farmakope Indonesia Edisi VI / BPOM)
         * Tahap S1 (6 bejana): Tiap unit >= Q + 5%
         * Jika S1 TMS: Otomatis tampilkan tabel Tahap S2 (Poin B.2 Notulensi 28 Okt 2026)
         * Tahap S2 (12 unit total): Rata-rata 12 unit >= Q dan tidak ada unit < Q - 15%
         * Jika S2 TMS: Otomatis tampilkan tabel Tahap S3 (Bejana 13-24)
         * Tahap S3 (24 unit total): Rata-rata 24 unit >= Q, tidak lebih dari 2 unit < Q - 15%, dan tidak ada unit < Q - 25%
         */
        /**
         * Evaluasi kesimpulan untuk Formulir Identifikasi (Kualitatif: Positif/Negatif)
         */
        function evaluasiIdentifikasi(pane, formId) {
            var valUji = pane.querySelector('select[name*="[hasil_identifikasi]"], input[name*="[hasil_identifikasi]"], select[name*="[hasil_uji]"], input[name*="[hasil_uji]"]');
            if (!valUji) return;
            var val = (valUji.value || '').trim().toLowerCase();
            if (!val) return;
            if (val === 'positif' || val === 'sesuai' || val === 'memenuhi syarat' || val === 'ms' || val.indexOf('positif') !== -1 || val.indexOf('sesuai') !== -1) {
                setKesimpulanOtomatis(formId, 'Memenuhi Syarat (MS)', 'Hasil pengujian identifikasi kualitatif: ' + (valUji.value || 'Positif') + ' (Memenuhi Syarat).');
            } else if (val === 'negatif' || val === 'tidak sesuai' || val === 'tidak memenuhi syarat' || val === 'tms' || val.indexOf('negatif') !== -1 || val.indexOf('tidak') !== -1) {
                setKesimpulanOtomatis(formId, 'Tidak Memenuhi Syarat (TMS)', 'Hasil pengujian identifikasi kualitatif: ' + (valUji.value || 'Negatif') + ' (Tidak Memenuhi Syarat).');
            }
        }

        function evaluasiDisolusi(pane, formId, syaratStr) {
            // Ambil toleransi Q dari input atau teks syarat monografi
            var qInput = pane.querySelector('input[name*="[toleransi_q]"]');
            var Q = 80; // default toleransi Q = 80%
            if (qInput && qInput.value.trim() !== '') {
                var mQ = qInput.value.match(/(\d+(?:\.\d+)?)/);
                if (mQ) Q = parseFloat(mQ[1]);
            } else if (syaratStr) {
                var mQ2 = syaratStr.match(/Q\s*[:=]\s*(\d+(?:\.\d+)?)/i) || syaratStr.match(/(\d+(?:\.\d+)?)\s*%\s*\(?Q\)?/i);
                if (mQ2) Q = parseFloat(mQ2[1]);
            }

            var batasS1 = Q + 5; // Batas kriteria penerimaan S1 (tiap unit >= Q + 5%)

            // 1. Ambil data persen terlarut Tahap S1 (Bejana 1 - 6)
            var valsS1 = [];
            for (var b = 0; b < 6; b++) {
                var dxEl = pane.querySelector('input[name*="[tabel_s1][' + b + '][persen_terlarut]"]');
                if (dxEl && dxEl.value.trim() !== '') {
                    var v = parseFloat(dxEl.value.replace(',', '.'));
                    if (!isNaN(v) && v > 0) valsS1.push(v);
                }
            }

            if (valsS1.length === 0) {
                return; // Belum ada data perhitungan disolusi S1
            }

            var minValS1 = Math.min.apply(null, valsS1);
            var sumS1 = valsS1.reduce(function(a, c) { return a + c; }, 0);
            var meanValS1 = sumS1 / valsS1.length;

            // Trigger element untuk S2 & S3
            var trigS2 = pane.querySelector('select[name*="[lakukan_tahap_s2]"]');
            var trigS3 = pane.querySelector('select[name*="[lakukan_tahap_s3]"]');

            // KASUS 1: Tahap S1 Memenuhi Syarat (Seluruh 6 unit >= Q + 5%)
            if (valsS1.length >= 6 && minValS1 >= batasS1) {
                var alasanMS_S1 = 'Kriteria Tahap S1: Seluruh 6 unit terlarut memenuhi batas penerimaan ≥ Q + 5% (' + batasS1.toFixed(1) + '%, toleransi Q = ' + Q + '%). Nilai terendah: ' + minValS1.toFixed(2) + '%, Rata-rata: ' + meanValS1.toFixed(2) + '%. Status otomatis: Memenuhi Syarat (MS).';
                setKesimpulanOtomatis(formId, 'Memenuhi Syarat (MS)', alasanMS_S1);

                // Sesuai Poin B.3: Jika tahap 1 telah memenuhi persyaratan, tahap 2 opsional dan tidak ditampilkan
                if (trigS2 && trigS2.value !== 'Tidak') {
                    // Hanya reset jika belum ada pengisian manual S2
                    var valsS2_cek = [];
                    for (var s2 = 0; s2 < 6; s2++) {
                        var el2 = pane.querySelector('input[name*="[tabel_s2][' + s2 + '][persen_terlarut]"]');
                        if (el2 && el2.value.trim() !== '') valsS2_cek.push(parseFloat(el2.value));
                    }
                    if (valsS2_cek.length === 0) {
                        trigS2.value = 'Tidak';
                        if (trigS3) trigS3.value = 'Tidak';
                        updateTahapVisibility(pane);
                    }
                }
                return;
            }

            // KASUS 2: Tahap S1 Tidak Memenuhi Syarat (Ada unit < Q + 5%)
            if (minValS1 < batasS1) {
                // Sesuai Poin B.2: Sistem otomatis menampilkan tabel pengujian tahap S2
                if (trigS2 && trigS2.value !== 'Ya') {
                    trigS2.value = 'Ya';
                    updateTahapVisibility(pane);
                }

                // Ambil data persen terlarut Tahap S2 (Bejana 7 - 12)
                var valsS2 = [];
                for (var b2 = 0; b2 < 6; b2++) {
                    var dxEl2 = pane.querySelector('input[name*="[tabel_s2][' + b2 + '][persen_terlarut]"]');
                    if (dxEl2 && dxEl2.value.trim() !== '') {
                        var v2 = parseFloat(dxEl2.value.replace(',', '.'));
                        if (!isNaN(v2) && v2 > 0) valsS2.push(v2);
                    }
                }

                // Jika data Tahap S2 belum diisi lengkap (total 12 unit belum ada)
                if (valsS2.length < 6) {
                    var alasanTMS_S1 = 'Kriteria Tahap S1: Nilai terendah (' + minValS1.toFixed(2) + '%) tidak memenuhi batas penerimaan Q + 5% (' + batasS1.toFixed(1) + '%, toleransi Q = ' + Q + '%). Status: Tidak Memenuhi Syarat (TMS). Sistem secara otomatis mengaktifkan tabel pengujian Tahap S2 (Bejana 7 - 12). Harap lengkapi pengujian Tahap S2.';
                    setKesimpulanOtomatis(formId, 'Tidak Memenuhi Syarat (TMS)', alasanTMS_S1);
                    return;
                }

                // Evaluasi Tahap S2 (Total 12 Unit: Bejana 1 - 12)
                var all12 = valsS1.concat(valsS2);
                var sum12 = all12.reduce(function(a, c) { return a + c; }, 0);
                var mean12 = sum12 / all12.length;
                var minVal12 = Math.min.apply(null, all12);
                var batasBawahS2 = Q - 15; // Tidak ada unit < Q - 15%

                // S2 Memenuhi Syarat: Rata-rata 12 unit >= Q DAN tidak ada unit < Q - 15%
                if (mean12 >= Q && minVal12 >= batasBawahS2) {
                    var alasanMS_S2 = 'Kriteria Tahap S2 (12 Unit): Rata-rata 12 unit (' + mean12.toFixed(2) + '%) ≥ Q (' + Q + '%) dan tidak ada unit < Q - 15% (' + batasBawahS2.toFixed(1) + '%). Nilai terendah 12 unit: ' + minVal12.toFixed(2) + '%. Status otomatis: Memenuhi Syarat (MS).';
                    setKesimpulanOtomatis(formId, 'Memenuhi Syarat (MS)', alasanMS_S2);

                    if (trigS3 && trigS3.value !== 'Tidak') {
                        trigS3.value = 'Tidak';
                        updateTahapVisibility(pane);
                    }
                    return;
                }

                // S2 Tidak Memenuhi Syarat: Rata-rata 12 unit < Q ATAU ada unit < Q - 15%
                // Sesuai Poin B.2: Otomatis tampilkan tabel pengujian tahap S3 (Bejana 13 - 24)
                if (trigS3 && trigS3.value !== 'Ya') {
                    trigS3.value = 'Ya';
                    updateTahapVisibility(pane);
                }

                // Ambil data persen terlarut Tahap S3 (Bejana 13 - 24)
                var valsS3 = [];
                for (var b3 = 0; b3 < 12; b3++) {
                    var dxEl3 = pane.querySelector('input[name*="[tabel_s3][' + b3 + '][persen_terlarut]"]');
                    if (dxEl3 && dxEl3.value.trim() !== '') {
                        var v3 = parseFloat(dxEl3.value.replace(',', '.'));
                        if (!isNaN(v3) && v3 > 0) valsS3.push(v3);
                    }
                }

                // Jika data Tahap S3 belum diisi lengkap (total 24 unit belum ada)
                if (valsS3.length < 12) {
                    var alasanTMS_S2 = 'Kriteria Tahap S2: Rata-rata 12 unit (' + mean12.toFixed(2) + '%) < Q (' + Q + '%) atau ada unit < Q - 15% (' + batasBawahS2.toFixed(1) + '%, nilai terendah: ' + minVal12.toFixed(2) + '%). Status: Tidak Memenuhi Syarat (TMS). Sistem secara otomatis mengaktifkan tabel pengujian Tahap S3 (Bejana 13 - 24). Harap lengkapi pengujian Tahap S3.';
                    setKesimpulanOtomatis(formId, 'Tidak Memenuhi Syarat (TMS)', alasanTMS_S2);
                    return;
                }

                // Evaluasi Tahap S3 (Total 24 Unit: Bejana 1 - 24)
                var all24 = all12.concat(valsS3);
                var sum24 = all24.reduce(function(a, c) { return a + c; }, 0);
                var mean24 = sum24 / all24.length;
                var minVal24 = Math.min.apply(null, all24);
                var batasBawahS3_15 = Q - 15;
                var batasBawahS3_25 = Q - 25;

                var unitBawah15 = 0;
                var unitBawah25 = 0;
                for (var u = 0; u < all24.length; u++) {
                    if (all24[u] < batasBawahS3_15) unitBawah15++;
                    if (all24[u] < batasBawahS3_25) unitBawah25++;
                }

                // Kriteria S3 (FI VI):
                // 1. Rata-rata 24 unit >= Q
                // 2. Tidak lebih dari 2 unit < Q - 15%
                // 3. Tidak ada satupun unit < Q - 25%
                if (mean24 >= Q && unitBawah15 <= 2 && unitBawah25 === 0) {
                    var alasanMS_S3 = 'Kriteria Tahap S3 (24 Unit): Rata-rata 24 unit (' + mean24.toFixed(2) + '%) ≥ Q (' + Q + '%), unit < Q - 15% berjumlah ' + unitBawah15 + ' (syarat ≤ 2), dan tidak ada unit < Q - 25%. Nilai terendah: ' + minVal24.toFixed(2) + '%. Status otomatis: Memenuhi Syarat (MS).';
                    setKesimpulanOtomatis(formId, 'Memenuhi Syarat (MS)', alasanMS_S3);
                } else {
                    var alasanTMS_S3 = 'Kriteria Tahap S3 (24 Unit): Rata-rata 24 unit (' + mean24.toFixed(2) + '%) atau distribusi toleransi tidak memenuhi syarat (unit < Q-15%: ' + unitBawah15 + ', unit < Q-25%: ' + unitBawah25 + '). Status otomatis: Tidak Memenuhi Syarat (TMS).';
                    setKesimpulanOtomatis(formId, 'Tidak Memenuhi Syarat (TMS)', alasanTMS_S3);
                }
            }
        }

        /**
         * Evaluasi Keragaman Bobot (KIM-02) & Keseragaman Kandungan (KIM-03)
         * Kriteria Penerimaan Farmakope Indonesia (Acceptance Value / AV):
         * Tahap 1 (n=10): L1 = AV <= 15.0% -> Memenuhi Syarat (MS)
         * Jika Tahap 1 AV > 15.0%: Otomatis tampilkan tabel pengujian Tahap 2 (No. 11 - 30) (Poin B.2)
         * Tahap 2 (n=30): L2 = AV <= 25.0% -> Memenuhi Syarat (MS)
         */
        function evaluasiKeseragamanSediaan(pane, formId) {
            var avInput = pane.querySelector('input[name*="[nilai_av]"]');
            if (!avInput || avInput.value.trim() === '') return;
            var AV = parseFloat(avInput.value.replace(',', '.'));
            if (isNaN(AV)) return;

            var trigT2 = pane.querySelector('select[name*="[lakukan_tahap2]"]');
            var isAktifT2 = trigT2 && trigT2.value === 'Ya';

            // Cek apakah data Tahap 2 sudah diisi (ada nilai di input x_rerata_30 atau sd_30)
            var x30Input = pane.querySelector('input[name*="[x_rerata_30]"]');
            var hasDataTahap2 = isAktifT2 && x30Input && x30Input.value.trim() !== '' && parseFloat(x30Input.value) > 0;

            if (hasDataTahap2) {
                // Evaluasi Tahap 2 (30 Satuan Sediaan)
                // Kriteria L2 Farmakope Indonesia VI: AV <= 25.0%
                if (AV <= 25.0) {
                    var alasanMS_T2 = 'Kriteria Tahap 2 (30 Satuan Sediaan): Nilai Penerimaan (AV = ' + AV.toFixed(2) + '%) ≤ 25.0% (Kriteria L2 Farmakope Indonesia Memenuhi Syarat).';
                    setKesimpulanOtomatis(formId, 'Memenuhi Syarat (MS)', alasanMS_T2);
                } else {
                    var alasanTMS_T2 = 'Kriteria Tahap 2 (30 Satuan Sediaan): Nilai Penerimaan (AV = ' + AV.toFixed(2) + '%) > 25.0% (Melebihi batas L2 25.0%). Status otomatis: Tidak Memenuhi Syarat (TMS).';
                    setKesimpulanOtomatis(formId, 'Tidak Memenuhi Syarat (TMS)', alasanTMS_T2);
                }
            } else {
                // Evaluasi Tahap 1 (10 Satuan Sediaan)
                // Kriteria L1 Farmakope Indonesia VI: AV <= 15.0%
                if (AV <= 15.0) {
                    var alasanMS_T1 = 'Kriteria Tahap 1 (10 Satuan Sediaan): Nilai Penerimaan (AV = ' + AV.toFixed(2) + '%) ≤ 15.0% (Kriteria L1 Farmakope Indonesia Memenuhi Syarat).';
                    setKesimpulanOtomatis(formId, 'Memenuhi Syarat (MS)', alasanMS_T1);

                    // Sesuai Poin B.3: Jika tahap 1 telah memenuhi persyaratan, tahap 2 opsional dan disembunyikan
                    if (trigT2 && trigT2.value !== 'Tidak') {
                        trigT2.value = 'Tidak';
                        updateTahapVisibility(pane);
                    }
                } else {
                    // Tahap 1 TMS (AV > 15.0%)
                    // Sesuai Poin B.2: Otomatis tampilkan tabel pengujian Tahap 2 (No. 11 - 30)
                    if (trigT2 && trigT2.value !== 'Ya') {
                        trigT2.value = 'Ya';
                        updateTahapVisibility(pane);
                    }

                    var alasanTMS_T1 = 'Kriteria Tahap 1 (10 Satuan Sediaan): Nilai Penerimaan (AV = ' + AV.toFixed(2) + '%) > 15.0% (Melebihi batas toleransi L1 15.0%). Status: Tidak Memenuhi Syarat (TMS). Sistem secara otomatis mengaktifkan tabel pengujian Tahap 2 (20 satuan sediaan tambahan No. 11 - 30, total 30). Harap lengkapi pengujian Tahap 2.';
                    setKesimpulanOtomatis(formId, 'Tidak Memenuhi Syarat (TMS)', alasanTMS_T1);
                }
            }
        }

        /**
         * Evaluasi Penetapan Kadar (Spektrofotometri, HPLC, Titrimetri)
         * Membandingkan kadar rata-rata (%) terhadap rentang spesifikasi monografi
         * dan presisi RPD terhadap Horwitz jika tersedia
         */
        function evaluasiPenetapanKadar(pane, formId, syaratStr, kodeTpl) {
            var kadarInp = pane.querySelector('input[name*="[kadar_rata_rata_persen]"], input[name*="[kadar_rata_persen]"], input[name*="[kadar_rata_rata]"], input[name*="[kadar_hasil_uji]"]');
            if (!kadarInp || kadarInp.value.trim() === '') return;
            var kadar = parseFloat(kadarInp.value.replace(',', '.'));
            if (isNaN(kadar) || kadar <= 0) return;

            // Batas default jika tidak tercantum di database
            var minKadar = 90.0;
            var maxKadar = 110.0;

            // Parsing rentang dari teks syarat monografi
            var rentangMatch = syaratStr.match(/(\d+(?:\.\d+)?)\s*(?:%|\s)*(?:-|sampai|dan|–)\s*(\d+(?:\.\d+)?)\s*%/i) ||
                               syaratStr.match(/(\d+(?:\.\d+)?)\s*(?:-|–)\s*(\d+(?:\.\d+)?)/i);
            if (rentangMatch) {
                minKadar = parseFloat(rentangMatch[1]);
                maxKadar = parseFloat(rentangMatch[2]);
            } else {
                var minMatch = syaratStr.match(/(?:tidak kurang dari|minimal|min\.?|>=|≥)\s*(\d+(?:\.\d+)?)/i);
                var maxMatch = syaratStr.match(/(?:tidak lebih dari|maksimal|maks\.?|<=|≤)\s*(\d+(?:\.\d+)?)/i);
                if (minMatch) minKadar = parseFloat(minMatch[1]);
                if (maxMatch) maxKadar = parseFloat(maxMatch[1]);
            }

            // Periksa RPD dan Horwitz jika ada
            var rpdInp = pane.querySelector('input[name*="[rpd_hitung]"], input[name*="[nilai_rpd]"]');
            var horwInp = pane.querySelector('input[name*="[syarat_rpd_horwitz]"], input[name*="[syarat_horwitz]"]');
            var rpd = (rpdInp && rpdInp.value) ? parseFloat(rpdInp.value.replace(',', '.')) : null;
            var horw = (horwInp && horwInp.value) ? parseFloat(horwInp.value.replace(',', '.')) : null;

            if (kadar < minKadar || kadar > maxKadar) {
                var alasanTMS = 'Kadar rata-rata (' + kadar.toFixed(2) + '%) berada di luar rentang spesifikasi (' + minKadar + '% - ' + maxKadar + '%). Status otomatis: Tidak Memenuhi Syarat (TMS).';
                setKesimpulanOtomatis(formId, 'Tidak Memenuhi Syarat (TMS)', alasanTMS);
            } else {
                if (rpd !== null && horw !== null && !isNaN(rpd) && !isNaN(horw) && rpd > horw) {
                    var alasanRPD = 'Kadar rata-rata (' + kadar.toFixed(2) + '%) masuk rentang spesifikasi (' + minKadar + '% - ' + maxKadar + '%), namun presisi RPD (' + rpd.toFixed(2) + '%) melebihi batas Horwitz (' + horw.toFixed(2) + '%). Status otomatis: TMS.';
                    setKesimpulanOtomatis(formId, 'Tidak Memenuhi Syarat (TMS)', alasanRPD);
                } else {
                    var rpdKet = (rpd !== null && !isNaN(rpd)) ? ' RPD (' + rpd.toFixed(2) + '%) memenuhi syarat.' : '';
                    var alasanMS = 'Kadar rata-rata (' + kadar.toFixed(2) + '%) memenuhi rentang spesifikasi (' + minKadar + '% - ' + maxKadar + '%).' + rpdKet + ' Status otomatis: Memenuhi Syarat (MS).';
                    setKesimpulanOtomatis(formId, 'Memenuhi Syarat (MS)', alasanMS);
                }
            }
        }

        /**
         * Evaluasi Penetapan pH (KIM-11)
         */
        function evaluasiPH(pane, formId, syaratStr) {
            var phInp = pane.querySelector('input[name*="[ph_rata_rata]"]');
            if (!phInp || phInp.value.trim() === '') return;
            var phVal = parseFloat(phInp.value.replace(',', '.'));
            if (isNaN(phVal)) return;

            var minPH = 4.0;
            var maxPH = 7.0;
            var rentangPH = syaratStr.match(/(\d+(?:\.\d+)?)\s*(?:-|sampai|dan|–)\s*(\d+(?:\.\d+)?)/i);
            if (rentangPH) {
                minPH = parseFloat(rentangPH[1]);
                maxPH = parseFloat(rentangPH[2]);
            }

            if (phVal >= minPH && phVal <= maxPH) {
                setKesimpulanOtomatis(formId, 'Memenuhi Syarat (MS)', 'pH rata-rata (' + phVal.toFixed(2) + ') memenuhi rentang spesifikasi (' + minPH + ' - ' + maxPH + ').');
            } else {
                setKesimpulanOtomatis(formId, 'Tidak Memenuhi Syarat (TMS)', 'pH rata-rata (' + phVal.toFixed(2) + ') di luar rentang spesifikasi (' + minPH + ' - ' + maxPH + ').');
            }
        }

        /**
         * Evaluasi Waktu Hancur (KIM-04)
         */
        function evaluasiWaktuHancur(pane, formId, syaratStr) {
            var wInp = pane.querySelector('input[name*="[waktu_hancur]"], input[name*="[waktu_menit]"]');
            if (!wInp || wInp.value.trim() === '') return;
            var wVal = parseFloat(wInp.value.replace(',', '.'));
            if (isNaN(wVal)) return;

            var maxWaktu = 15; // default 15 menit
            var matchMax = syaratStr.match(/(?:tidak lebih dari|maksimal|maks\.?|<=|≤)\s*(\d+(?:\.\d+)?)\s*(?:menit)?/i) ||
                           syaratStr.match(/(\d+(?:\.\d+)?)\s*menit/i);
            if (matchMax) maxWaktu = parseFloat(matchMax[1]);

            if (wVal <= maxWaktu) {
                setKesimpulanOtomatis(formId, 'Memenuhi Syarat (MS)', 'Waktu hancur (' + wVal + ' menit) ≤ batas spesifikasi (' + maxWaktu + ' menit).');
            } else {
                setKesimpulanOtomatis(formId, 'Tidak Memenuhi Syarat (TMS)', 'Waktu hancur (' + wVal + ' menit) melebihi batas spesifikasi (' + maxWaktu + ' menit).');
            }
        }

        /**
         * Evaluasi Penetapan Bobot Jenis (KIM-12)
         */
        function evaluasiBobotJenis(pane, formId, syaratStr) {
            var bjInp = pane.querySelector('input[name*="[nilai_bobot_jenis]"]');
            if (!bjInp || bjInp.value.trim() === '') return;
            var bjVal = parseFloat(bjInp.value.replace(',', '.'));
            if (isNaN(bjVal)) return;

            var minBJ = 1.0;
            var maxBJ = 1.2;
            var matchBJ = syaratStr.match(/(\d+(?:\.\d+)?)\s*(?:-|sampai|dan|–)\s*(\d+(?:\.\d+)?)/i);
            if (matchBJ) {
                minBJ = parseFloat(matchBJ[1]);
                maxBJ = parseFloat(matchBJ[2]);
            }

            if (bjVal >= minBJ && bjVal <= maxBJ) {
                setKesimpulanOtomatis(formId, 'Memenuhi Syarat (MS)', 'Bobot jenis (' + bjVal.toFixed(4) + ') memenuhi rentang spesifikasi (' + minBJ + ' - ' + maxBJ + ').');
            } else {
                setKesimpulanOtomatis(formId, 'Tidak Memenuhi Syarat (TMS)', 'Bobot jenis (' + bjVal.toFixed(4) + ') di luar rentang spesifikasi (' + minBJ + ' - ' + maxBJ + ').');
            }
        }

        /**
         * Evaluasi Generic Fallback untuk form pengujian umum berdasarkan data-syarat
         */
        function evaluasiGeneric(pane, formId, syaratStr) {
            if (!syaratStr) return;
            var numInp = pane.querySelector('input[name*="[hasil]"]:not([name*="[baku]"]):not([name*="[alat]"]), input[type="number"]:not([name*="[baku]"]):not([name*="[alat]"])');
            if (!numInp || numInp.value.trim() === '') return;
            var val = parseFloat(numInp.value.replace(',', '.'));
            if (isNaN(val)) return;

            var rentang = syaratStr.match(/(\d+(?:\.\d+)?)\s*(?:%|\s)*(?:-|sampai|dan|–)\s*(\d+(?:\.\d+)?)/i);
            if (rentang) {
                var minV = parseFloat(rentang[1]);
                var maxV = parseFloat(rentang[2]);
                if (val >= minV && val <= maxV) {
                    setKesimpulanOtomatis(formId, 'Memenuhi Syarat (MS)', 'Nilai hasil (' + val + ') memenuhi rentang spesifikasi (' + minV + ' - ' + maxV + ').');
                } else {
                    setKesimpulanOtomatis(formId, 'Tidak Memenuhi Syarat (TMS)', 'Nilai hasil (' + val + ') di luar rentang spesifikasi (' + minV + ' - ' + maxV + ').');
                }
            }
        }

        // =========================================================================
        // Auto-Calculator Engine untuk Formulir Keragaman Bobot (KIM-02)
        // Sesuai Formula Resmi Farmakope Indonesia Edisi VI <911> & SOP BPOM
        // Mendukung:
        // 1. Pengisian Netto langsung atau Bruto - Cangkang
        // 2. Bobot rata-rata otomatis dihitung dari rata-rata netto jika belum diisi manual
        // 3. Deteksi cerdas Kadar Hasil Uji (% etiket atau mg/tab)
        // 4. Perhitungan otomatis % Kadar jika mg/tab dimasukkan manual, dan sebaliknya
        // 5. Rumus statistik sebenarnya untuk Rata-rata, SD (n-1), RSD, dan AV (Tahap 1 & 2)
        // =========================================================================
        function hitungKeragamanBobot() {
            var panes = document.querySelectorAll('.form-tab-pane-uji');
            panes.forEach(function(pane) {
                var kodeTpl = (pane.getAttribute('data-kode-template') || '').toUpperCase();
                var namaTpl = (pane.getAttribute('data-nama-template') || '').toLowerCase();
                var paramTpl = (pane.getAttribute('data-parameter') || '').toLowerCase();
                if (kodeTpl === 'KIM-02' || namaTpl.indexOf('keragaman bobot') !== -1 || paramTpl.indexOf('keragaman bobot') !== -1 || pane.querySelector('input[name*="[tabel_tahap1]"]')) {
                    hitungKeragamanBobotPane(pane);
                }
            });
        }

        function hitungKeragamanBobotPane(pane) {
            var bobotRataInput = pane.querySelector('input[name*="[bobot_rata_rata]"]');
            var kadarHasilInput = pane.querySelector('input[name*="[kadar_hasil_uji]"]');
            var etiketInput = pane.querySelector('input[name*="[etiket]"]');
            var tahap2Select = pane.querySelector('select[name*="[lakukan_tahap2]"]');

            if (!bobotRataInput && !kadarHasilInput && !etiketInput) return;

            var etiketVal = etiketInput ? parseNum(etiketInput.value) : 0; // Klaim Etiket (mg)
            var kadarHasilVal = kadarHasilInput ? parseNum(kadarHasilInput.value) : 0; // Kadar Hasil Uji (% atau mg)
            var isTahap2 = tahap2Select && tahap2Select.value === 'Ya';

            // Langkah 1: Kumpulkan seluruh nilai Netto Tahap 1 (n = 10)
            var nettosT1 = [];
            for (var i = 0; i < 10; i++) {
                var brutoEl = pane.querySelector('input[name*="[tabel_tahap1][' + i + '][bruto]"]');
                var cangkangEl = pane.querySelector('input[name*="[tabel_tahap1][' + i + '][cangkang]"]');
                var nettoEl = pane.querySelector('input[name*="[tabel_tahap1][' + i + '][netto]"]');

                if (nettoEl) {
                    var bruto = brutoEl ? parseNum(brutoEl.value) : 0;
                    var cangkang = cangkangEl ? parseNum(cangkangEl.value) : 0;
                    var netto = 0;

                    if (bruto > 0) {
                        netto = bruto - cangkang;
                        nettoEl.value = netto.toFixed(2);
                    } else if (nettoEl.value.trim() !== '') {
                        netto = parseNum(nettoEl.value);
                    }
                    if (netto > 0) {
                        nettosT1.push(netto);
                    }
                }
            }

            // Langkah 2: Tentukan Bobot Rata-rata Tablet (mg)
            // Jika analis menginput manual (misal penimbangan 20 tablet awal), gunakan nilai tersebut.
            // Jika kosong/nol, otomatis hitung dari rata-rata netto tablet yang ada.
            var W_bar = bobotRataInput ? parseNum(bobotRataInput.value) : 0;
            if (W_bar <= 0 && nettosT1.length > 0) {
                var sumNetto = nettosT1.reduce(function(a, b) { return a + b; }, 0);
                W_bar = sumNetto / nettosT1.length;
                if (bobotRataInput && nettosT1.length >= 10) {
                    bobotRataInput.value = W_bar.toFixed(2);
                }
            }

            // Langkah 3: Perhitungan Baris Tahap 1 (mg/tab dan Kadar %)
            // Netto (mg) dan mg/tab (kaps) diinput secara manual.
            // Kadar (%) dihitung otomatis: mg/tab * Kadar dari hasil uji
            var kadarPersenT1 = [];
            for (var i = 0; i < 10; i++) {
                var kadarMgEl = pane.querySelector('input[name*="[tabel_tahap1][' + i + '][kadar_mg]"]');
                var kadarPersenEl = pane.querySelector('input[name*="[tabel_tahap1][' + i + '][kadar_persen]"]');

                if (kadarMgEl) {
                    var finalMg = kadarMgEl.value.trim() !== '' ? parseNum(kadarMgEl.value) : 0;
                    var kPctManual = kadarPersenEl && kadarPersenEl.value.trim() !== '' ? parseNum(kadarPersenEl.value) : 0;
                    var finalPct = 0;

                    if (finalMg > 0 && kadarHasilVal > 0) {
                        finalPct = finalMg * kadarHasilVal;
                        if (kadarPersenEl) kadarPersenEl.value = finalPct.toFixed(2);
                    } else if (kPctManual > 0) {
                        finalPct = kPctManual;
                    }

                    if (finalPct > 0) {
                        kadarPersenT1.push(finalPct);
                    }
                }
            }

            // Langkah 5: Statistik Tahap 1 (Rumus Sebenarnya: Mean, SD sampel n-1, RSD)
            var meanT1 = 0, sdT1 = 0, rsdT1 = 0;
            if (kadarPersenT1.length >= 2) {
                var n1 = kadarPersenT1.length;
                var sum1 = kadarPersenT1.reduce(function(a, b) { return a + b; }, 0);
                meanT1 = sum1 / n1; // X_rerata sebenarnya

                var var1 = kadarPersenT1.reduce(function(a, b) { return a + Math.pow(b - meanT1, 2); }, 0) / (n1 - 1);
                sdT1 = Math.sqrt(var1); // Sample Standard Deviation (s)
                rsdT1 = meanT1 > 0 ? (sdT1 / meanT1) * 100 : 0; // RSD (%)

                var xRerataT1Input = pane.querySelector('input[name*="[x_rerata_tahap1]"]');
                var sdT1Input = pane.querySelector('input[name*="[sd_tahap1]"]');
                var rsdT1Input = pane.querySelector('input[name*="[rsd_tahap1]"]');

                if (xRerataT1Input) xRerataT1Input.value = meanT1.toFixed(2);
                if (sdT1Input) sdT1Input.value = sdT1.toFixed(2);
                if (rsdT1Input) rsdT1Input.value = rsdT1.toFixed(2);
            }

            // Langkah 6: Baris Tahap 2 (No. 11 - 30) jika diaktifkan
            // Netto (mg) dan mg/tab (kaps) diinput secara manual.
            // Kadar (%) dihitung otomatis: mg/tab * Kadar dari hasil uji
            var kadarPersenT2 = [];
            if (isTahap2) {
                for (var j = 0; j < 20; j++) {
                    var kadarMg2El = pane.querySelector('input[name*="[tabel_tahap2][' + j + '][kadar_mg]"]');
                    var kadarPersen2El = pane.querySelector('input[name*="[tabel_tahap2][' + j + '][kadar_persen]"]');

                    if (kadarMg2El) {
                        var fMg2 = kadarMg2El.value.trim() !== '' ? parseNum(kadarMg2El.value) : 0;
                        var kPct2Manual = kadarPersen2El && kadarPersen2El.value.trim() !== '' ? parseNum(kadarPersen2El.value) : 0;
                        var fPct2 = 0;

                        if (fMg2 > 0 && kadarHasilVal > 0) {
                            fPct2 = fMg2 * kadarHasilVal;
                            if (kadarPersen2El) kadarPersen2El.value = fPct2.toFixed(2);
                        } else if (kPct2Manual > 0) {
                            fPct2 = kPct2Manual;
                        }

                        if (fPct2 > 0) {
                            kadarPersenT2.push(fPct2);
                        }
                    }
                }
            }

            // Langkah 7: Statistik Gabungan 30 Data (Tahap 2)
            var all30 = kadarPersenT1.concat(kadarPersenT2);
            var mean30 = 0, sd30 = 0, rsd30 = 0;
            if (isTahap2 && all30.length >= 15) {
                var n30 = all30.length;
                var sum30 = all30.reduce(function(a, b) { return a + b; }, 0);
                mean30 = sum30 / n30; // X_rerata 30 data sebenarnya
                var var30 = all30.reduce(function(a, b) { return a + Math.pow(b - mean30, 2); }, 0) / (n30 - 1);
                sd30 = Math.sqrt(var30); // SD 30 data sebenarnya (n-1)
                rsd30 = mean30 > 0 ? (sd30 / mean30) * 100 : 0;

                var xRerata30Input = pane.querySelector('input[name*="[x_rerata_30]"]');
                var sd30Input = pane.querySelector('input[name*="[sd_30]"]');
                var rsd30Input = pane.querySelector('input[name*="[rsd_30]"]');

                if (xRerata30Input) xRerata30Input.value = mean30.toFixed(2);
                if (sd30Input) sd30Input.value = sd30.toFixed(2);
                if (rsd30Input) rsd30Input.value = rsd30.toFixed(2);
            }

            // Langkah 8: Evaluasi Acceptance Value (AV) Farmakope Indonesia VI
            var finalMean = (isTahap2 && mean30 > 0) ? mean30 : meanT1;
            var finalSD = (isTahap2 && sd30 > 0) ? sd30 : sdT1;
            var k = (isTahap2 && all30.length >= 25) ? 2.0 : 2.4;

            if (finalMean > 0) {
                var M = finalMean;
                var AV = 0;

                // Logika Matriks FI VI / Excel:
                if (finalMean >= 98.5 && finalMean <= 101.5) {
                    M = finalMean;
                    AV = k * finalSD;
                } else if (finalMean < 98.5) {
                    M = 98.5;
                    AV = (98.5 - finalMean) + (k * finalSD);
                } else {
                    M = 101.5;
                    AV = (finalMean - 101.5) + (k * finalSD);
                }

                var mInput = pane.querySelector('input[name*="[nilai_m]"]');
                var avInput = pane.querySelector('input[name*="[nilai_av]"]');
                var kInput = pane.querySelector('input[name*="[nilai_k]"]');

                if (mInput) mInput.value = M.toFixed(2);
                if (avInput) avInput.value = AV.toFixed(2);
                if (kInput) kInput.value = k.toString();

                evaluasiKesimpulanPane(pane);
            }
        }

        // Pasang listener pada perubahan input bobot dan parameter terkait
        document.addEventListener('input', function(e) {
            if (e.target && e.target.matches('input[name*="[tabel_tahap1]"], input[name*="[tabel_tahap2]"], input[name*="[bobot_rata_rata]"], input[name*="[kadar_hasil_uji]"], input[name*="[etiket]"]')) {
                var pane = e.target.closest('.form-tab-pane-uji');
                if (pane) {
                    hitungKeragamanBobotPane(pane);
                } else {
                    hitungKeragamanBobot();
                }
            }
        });

        document.addEventListener('change', function(e) {
            if (e.target && e.target.matches('select[name*="[lakukan_tahap2]"], input[name*="[bobot_rata_rata]"], input[name*="[kadar_hasil_uji]"], input[name*="[etiket]"]')) {
                var pane = e.target.closest('.form-tab-pane-uji');
                if (pane) {
                    updateTahapVisibility(pane);
                    hitungKeragamanBobotPane(pane);
                } else {
                    hitungKeragamanBobot();
                }
            }
        });

        // Jalankan kalkulasi awal saat dokumen selesai dimuat
        hitungKeragamanBobot();

        // =========================================================================
        // Auto-Calculator Engine untuk Formulir Keseragaman Kandungan (KIM-03)
        // Sesuai Formula Resmi Excel: POM-06.02/CFM.01/SOP.01/F.01A.03
        // Rumus Kadar (mg): E25 = C25 / $L$14 * $L$13 * $L$16 / $L$15 * $L$11
        // Rumus Kadar (%):  G25 = E25 / $E$21 * 100
        // =========================================================================
        function hitungKeseragamanKandungan() {
            var kbInput = document.querySelector('input[name*="[kemurnian_baku]"]');       // L11: Kemurnian Baku (%)
            var bbInput = document.querySelector('input[name*="[bobot_baku]"]');           // L13: Bobot Baku (mg)
            var areaBakuInput = document.querySelector('input[name*="[hasil_ukur_baku]"]'); // L14: Hasil Ukur Baku
            var fbInput = document.querySelector('input[name*="[faktor_pengenceran_baku]"]'); // L15: Faktor Pengenceran Baku
            var fuInput = document.querySelector('input[name*="[faktor_pengenceran_uji]"]');  // L16: Faktor Pengenceran Uji
            var etiketInput = document.querySelector('input[name*="[etiket]"]');           // E21: Etiket (mg/tab)
            var tahap2Select = document.querySelector('select[name*="[lakukan_tahap2]"]');

            var L11 = kbInput ? parseFloat(kbInput.value) || 0 : 0;
            var L13 = bbInput ? parseFloat(bbInput.value) || 0 : 0;
            var L14 = areaBakuInput ? parseFloat(areaBakuInput.value) || 0 : 0;
            var L15 = fbInput ? parseFloat(fbInput.value) || 0 : 0;
            var L16 = fuInput ? parseFloat(fuInput.value) || 0 : 0;
            var E21 = etiketInput ? parseFloat(etiketInput.value) || 0 : 0;
            var isTahap2 = tahap2Select && tahap2Select.value === 'Ya';

            // Jika bukan form KIM-03 (misal field L14 tidak ada), lewati
            if (!areaBakuInput && !kbInput) return;

            // 1. Hitung Tahap 1 (n = 10)
            var kadarMgT1 = [];
            var kadarPersenT1 = [];
            for (var i = 0; i < 10; i++) {
                var areaEl = document.querySelector('input[name*="[tabel_tahap1][' + i + '][area_serapan]"]');
                var kadarMgEl = document.querySelector('input[name*="[tabel_tahap1][' + i + '][kadar_mg]"]');
                var kadarPersenEl = document.querySelector('input[name*="[tabel_tahap1][' + i + '][kadar_persen]"]');

                if (areaEl) {
                    var C25 = parseFloat(areaEl.value) || 0;
                    if (C25 > 0 && L14 > 0 && L15 > 0) {
                        // Formula Excel persis: C25 / $L$14 * $L$13 * $L$16 / $L$15 * ($L$11/100 jika persen, atau langsung sesuai template)
                        var faktorKb = (L11 > 1) ? (L11 / 100) : L11;
                        var kadarMg = (C25 / L14) * L13 * (L16 / L15) * faktorKb;
                        if (kadarMgEl) kadarMgEl.value = kadarMg.toFixed(2);
                        kadarMgT1.push(kadarMg);

                        var kadarPersen = E21 > 0 ? (kadarMg / E21) * 100 : 0;
                        if (kadarPersenEl) kadarPersenEl.value = kadarPersen.toFixed(2);
                        kadarPersenT1.push(kadarPersen);
                    } else if (kadarPersenEl && parseFloat(kadarPersenEl.value)) {
                        kadarPersenT1.push(parseFloat(kadarPersenEl.value));
                    }
                }
            }

            // Statistik Tahap 1
            var meanMgT1 = 0, meanPersenT1 = 0, sdT1 = 0, rsdT1 = 0;
            if (kadarPersenT1.length >= 2) {
                var n1 = kadarPersenT1.length;
                meanMgT1 = kadarMgT1.reduce(function(a, b) { return a + b; }, 0) / (kadarMgT1.length || 1);
                meanPersenT1 = kadarPersenT1.reduce(function(a, b) { return a + b; }, 0) / n1; // G35

                var var1 = kadarPersenT1.reduce(function(a, b) { return a + Math.pow(b - meanPersenT1, 2); }, 0) / (n1 - 1);
                sdT1 = Math.sqrt(var1); // G36: STDEV
                rsdT1 = meanPersenT1 > 0 ? (sdT1 / meanPersenT1) * 100 : 0; // G37: RSD

                var rerataMgInput = document.querySelector('input[name*="[rerata_kadar_mg_t1]"]');
                var xRerataInput = document.querySelector('input[name*="[x_rerata_tahap1]"]');
                var sdInput = document.querySelector('input[name*="[sd_tahap1]"]');
                var rsdInput = document.querySelector('input[name*="[rsd_tahap1]"]');

                if (rerataMgInput) rerataMgInput.value = meanMgT1.toFixed(2);
                if (xRerataInput) xRerataInput.value = meanPersenT1.toFixed(2);
                if (sdInput) sdInput.value = sdT1.toFixed(2);
                if (rsdInput) rsdInput.value = rsdT1.toFixed(2);
            }

            // 2. Hitung Tahap 2 (No. 11 - 30) jika ada
            var kadarPersenT2 = [];
            for (var j = 0; j < 20; j++) {
                var area2El = document.querySelector('input[name*="[tabel_tahap2][' + j + '][area_serapan]"]');
                var kadarMg2El = document.querySelector('input[name*="[tabel_tahap2][' + j + '][kadar_mg]"]');
                var kadarPersen2El = document.querySelector('input[name*="[tabel_tahap2][' + j + '][kadar_persen]"]');

                if (area2El) {
                    var areaVal = parseFloat(area2El.value) || 0;
                    if (areaVal > 0 && L14 > 0 && L15 > 0) {
                        var faktorKb2 = (L11 > 1) ? (L11 / 100) : L11;
                        var km2 = (areaVal / L14) * L13 * (L16 / L15) * faktorKb2;
                        if (kadarMg2El) kadarMg2El.value = km2.toFixed(2);

                        var kp2 = E21 > 0 ? (km2 / E21) * 100 : 0;
                        if (kadarPersen2El) kadarPersen2El.value = kp2.toFixed(2);
                        kadarPersenT2.push(kp2);
                    } else if (kadarPersen2El && parseFloat(kadarPersen2El.value)) {
                        kadarPersenT2.push(parseFloat(kadarPersen2El.value));
                    }
                }
            }

            // Statistik Gabungan 30 Data
            var all30 = kadarPersenT1.concat(kadarPersenT2);
            var mean30 = 0, sd30 = 0, rsd30 = 0;
            if (isTahap2 && all30.length >= 15) {
                var n30 = all30.length;
                mean30 = all30.reduce(function(a, b) { return a + b; }, 0) / n30;
                var var30 = all30.reduce(function(a, b) { return a + Math.pow(b - mean30, 2); }, 0) / (n30 - 1);
                sd30 = Math.sqrt(var30);
                rsd30 = mean30 > 0 ? (sd30 / mean30) * 100 : 0;

                var xRerata30Input = document.querySelector('input[name*="[x_rerata_30]"]');
                var sd30Input = document.querySelector('input[name*="[sd_30]"]');
                var rsd30Input = document.querySelector('input[name*="[rsd_30]"]');

                if (xRerata30Input) xRerata30Input.value = mean30.toFixed(2);
                if (sd30Input) sd30Input.value = sd30.toFixed(2);
                if (rsd30Input) rsd30Input.value = rsd30.toFixed(2);
            }

            // 3. Evaluasi Acceptance Value (AV) Matriks Excel
            var finalMean = (isTahap2 && mean30 > 0) ? mean30 : meanPersenT1;
            var finalSD = (isTahap2 && sd30 > 0) ? sd30 : sdT1;
            var k = (isTahap2 && all30.length >= 25) ? 2.0 : 2.4;

            if (finalMean > 0) {
                var M = finalMean;
                var AV = 0;

                if (finalMean >= 98.5 && finalMean <= 101.5) {
                    M = finalMean;
                    AV = k * finalSD;
                } else if (finalMean < 98.5) {
                    M = 98.5;
                    AV = (98.5 - finalMean) + (k * finalSD);
                } else {
                    M = 101.5;
                    AV = (finalMean - 101.5) + (k * finalSD);
                }

                var mInput = document.querySelector('input[name*="[nilai_m]"]');
                var avInput = document.querySelector('input[name*="[nilai_av]"]');
                var kInput = document.querySelector('input[name*="[nilai_k]"]');
                var kesimpulanSelect = document.querySelector('select[name*="[kesimpulan]"]');

                if (mInput) mInput.value = M.toFixed(2);
                if (avInput) avInput.value = AV.toFixed(2);
                if (kInput) kInput.value = k.toString();

                if (kesimpulanSelect) {
                    kesimpulanSelect.value = (AV <= 15.0) ? 'Memenuhi Syarat (MS)' : 'Tidak Memenuhi Syarat (TMS)';
                }
                var pKandungan = kbInput ? kbInput.closest('.form-tab-pane-uji') : null;
                if (pKandungan) evaluasiKesimpulanPane(pKandungan);
            }
        }

        // Listener untuk KIM-03
        document.addEventListener('input', function(e) {
            if (e.target && e.target.matches('input[name*="[area_serapan]"], input[name*="[kemurnian_baku]"], input[name*="[bobot_baku]"], input[name*="[hasil_ukur_baku]"], input[name*="[faktor_pengenceran_baku]"], input[name*="[faktor_pengenceran_uji]"], input[name*="[etiket]"]')) {
                hitungKeseragamanKandungan();
            }
        });

        // =========================================================================
        // Helper Multi-Metode Penetapan Kadar (FI VI & SOP BBPOM)
        // 1. Tablet / Kapsul BR: (Au/Ab) * (Bb/Bu) * (Fu/Fb) * (Br/Ke) * Kb * 100%
        // 2. Tablet Cemplung: (Au/Ab) * Bb * (Fu/Fb) * (1/Ke) * Kb * 100%
        // 3. Sirup / Cairan: (Au/Ab) * (Bb/Vu) * (Fu/Fb) * (Vetiket/Ke) * Kb * 100%
        // =========================================================================
        function getMetodeKadar(pane) {
            var sel = pane ? pane.querySelector('select[name*="[metode_perhitungan_kadar]"]') : document.querySelector('select[name*="[metode_perhitungan_kadar]"]');
            var val = sel ? sel.value.toLowerCase() : '';
            if (val.indexOf('cemplung') !== -1 || val.indexOf('effervescent') !== -1) {
                return 'tablet_cemplung';
            } else if (val.indexOf('sirup') !== -1 || val.indexOf('suspensi') !== -1 || val.indexOf('cairan') !== -1 || val.indexOf('volume') !== -1) {
                return 'sirup';
            }
            return 'tablet_br';
        }

        function updateMetodeKadarUI(pane) {
        if (!pane) return;
        var pName = (pane.getAttribute('data-parameter') || '').toLowerCase();
        var tName = (pane.getAttribute('data-nama-template') || '').toLowerCase();
        var isIdent = (pane.getAttribute('data-is-identifikasi') === '1') || pName.indexOf('identifikasi') !== -1 || tName.indexOf('identifikasi') !== -1;
        if (isIdent) {
            var mw = pane.querySelector('[data-field-wrapper="metode_perhitungan_kadar"]');
            if (mw) mw.style.display = 'none';
            return;
        }
            if (!pane) return;
            var mode = getMetodeKadar(pane);

            var wrapVol = pane.querySelector('[data-field-wrapper="volume_takaran_etiket"]');
            var wrapBr = pane.querySelector('[data-field-wrapper="bobot_rata_rata_tablet"], [data-field-wrapper="bobot_rata_tablet"]');
            var txtRumus = pane.querySelector('.text-rumus-kadar');
            var labelKe = pane.querySelector('[data-field-wrapper="klaim_etiket"] label');
            var labelKadarMg = pane.querySelector('[data-field-wrapper="kadar_rata_rata_mg"] label, [data-field-wrapper="kadar_rata_mg"] label');

            var volInp = pane.querySelector('input[name*="[volume_takaran_etiket]"]');
            var vEt = (volInp && parseFloat(volInp.value) > 0) ? volInp.value : '5.0';

            var tableRows = pane.querySelectorAll('table tbody tr');

            if (mode === 'tablet_cemplung') {
                if (wrapVol) wrapVol.style.display = 'none';
                if (wrapBr) wrapBr.style.display = 'none';
                if (txtRumus) {
                    txtRumus.innerHTML = '<strong>Rumus Aktif (Tablet Cemplung):</strong> Kadar (%) = (A<sub>u</sub> / A<sub>b</sub>) &times; B<sub>b</sub> &times; (F<sub>u</sub> / F<sub>b</sub>) &times; (1 / Ke) &times; K<sub>b</sub> &times; [f<sub>BM</sub>] &times; 100%<div class="mt-1 text-success fw-bold" style="font-size: 0.78rem;"><i class="bi bi-info-circle-fill me-1"></i>Keterangan Rumus Tablet Cemplung: Pada penetapan kadar tablet cemplung / effervescent / direct dissolution, pengujian menggunakan 1 tablet utuh yang dilarutkan langsung secara utuh tanpa penggerusan dan penimbangan serbuk sisa (bagian zat uji: wadah zat, wadah sisa, dan bobot uji digabung menjadi 1 tablet utuh).</div>';
                }
                if (labelKe) labelKe.innerHTML = 'Klaim Etiket per Tablet (Ke) [mg]';
                if (labelKadarMg) labelKadarMg.innerHTML = 'Kadar Rata-rata per Tablet (mg/tab)';

                tableRows.forEach(function(tr) {
                    var kat = (tr.querySelector('input[name*="[kategori]"], td:first-child input, td:first-child')?.value || tr.querySelector('td:first-child')?.textContent || '').toLowerCase();
                    if (kat.indexOf('uji') !== -1 || kat.indexOf('sampel') !== -1) {
                        var wZat = tr.querySelector('input[name*="[wadah_zat]"]');
                        var wSisa = tr.querySelector('input[name*="[wadah_sisa]"]');
                        var bBersih = tr.querySelector('input[name*="[bobot_bersih]"], input[name*="[bobot_zat]"]');
                        if (wZat) wZat.placeholder = "1 Tab Utuh";
                        if (wSisa) wSisa.placeholder = "(Digabung)";
                        if (bBersih) {
                            bBersih.placeholder = "1 Tab";
                            if (!bBersih.value || bBersih.value === '0') bBersih.value = "1";
                        }
                    }
                });
            } else if (mode === 'sirup') {
                if (wrapVol) wrapVol.style.display = '';
                if (wrapBr) wrapBr.style.display = 'none';
                if (txtRumus) {
                    txtRumus.innerHTML = '<strong>Rumus Aktif (Sirup/Cairan):</strong> Kadar (%) = (A<sub>u</sub> / A<sub>b</sub>) &times; (B<sub>b</sub> / V<sub>u</sub>) &times; (F<sub>u</sub> / F<sub>b</sub>) &times; (' + vEt + ' / Ke) &times; K<sub>b</sub> &times; [f<sub>BM</sub>] &times; 100%<div class="mt-1 text-primary fw-bold" style="font-size: 0.78rem;"><i class="bi bi-info-circle-fill me-1"></i>Keterangan Rumus Sirup: Pada penetapan kadar sirup / suspensi / cairan oral, pengujian menggunakan pemipetan volume sampel uji (Vu dalam mL) per takaran saji ' + vEt + ' mL (bagian wadah zat dan wadah sisa digabung menjadi satu bagian volume uji Vu).</div>';
                }
                if (labelKe) labelKe.innerHTML = 'Klaim Etiket per ' + vEt + ' mL (Ke) [mg]';
                if (labelKadarMg) labelKadarMg.innerHTML = 'Kadar Rata-rata (mg / ' + vEt + ' mL)';

                tableRows.forEach(function(tr) {
                    var kat = (tr.querySelector('input[name*="[kategori]"], td:first-child input, td:first-child')?.value || tr.querySelector('td:first-child')?.textContent || '').toLowerCase();
                    if (kat.indexOf('uji') !== -1 || kat.indexOf('sampel') !== -1) {
                        var wZat = tr.querySelector('input[name*="[wadah_zat]"]');
                        var wSisa = tr.querySelector('input[name*="[wadah_sisa]"]');
                        var bBersih = tr.querySelector('input[name*="[bobot_bersih]"], input[name*="[bobot_zat]"]');
                        if (wZat) wZat.placeholder = "Vol. Pipet Vu (mL)";
                        if (wSisa) wSisa.placeholder = "(Digabung)";
                        if (bBersih) bBersih.placeholder = "Vu (mL)";
                    }
                });
            } else { // tablet_br
                if (wrapVol) wrapVol.style.display = 'none';
                if (wrapBr) wrapBr.style.display = '';
                if (txtRumus) {
                    txtRumus.innerHTML = '<strong>Rumus Aktif (Tablet BR):</strong> Kadar (%) = (A<sub>u</sub> / A<sub>b</sub>) &times; (B<sub>b</sub> / B<sub>u</sub>) &times; (F<sub>u</sub> / F<sub>b</sub>) &times; (Br / Ke) &times; K<sub>b</sub> &times; [f<sub>BM</sub>] &times; 100%<div class="mt-1 text-secondary fw-bold" style="font-size: 0.78rem;"><i class="bi bi-info-circle-fill me-1"></i>Metode 20 Tablet Digerus: Penimbangan serbuk uji (Wadah+Zat - Wadah+Sisa) dikalikan Bobot Rata-rata 20 Tablet (Br).</div>';
                }
                if (labelKe) labelKe.innerHTML = 'Klaim Etiket per Tablet (Ke) [mg]';
                if (labelKadarMg) labelKadarMg.innerHTML = 'Kadar Rata-rata per Tablet (mg/tab)';

                tableRows.forEach(function(tr) {
                    var wZat = tr.querySelector('input[name*="[wadah_zat]"]');
                    var wSisa = tr.querySelector('input[name*="[wadah_sisa]"]');
                    var bBersih = tr.querySelector('input[name*="[bobot_bersih]"], input[name*="[bobot_zat]"]');
                    if (wZat) wZat.placeholder = "";
                    if (wSisa) wSisa.placeholder = "";
                    if (bBersih) bBersih.placeholder = "";
                });
            }
        }

        // =========================================================================
        // Auto-Calculator Engine untuk Formulir Spektrofotometri (KIM-05)
        // Mendukung 3 Metode: Tablet BR, Tablet Cemplung, dan Sirup / Cairan
        // Sesuai Formula Resmi Excel: POM-06.02/CFM.01/SOP.01/F.01A.06
        // RPD = |Uji1 - Uji2| / Rata2 * 100%
        // Syarat Horwitz = 0.5 * (2 ^ (1 - 0.5 * LOG10(Kadar/100)))
        // =========================================================================
        function hitungSpektrofotometri() {
            var panes = document.querySelectorAll('.form-tab-pane-uji');
            panes.forEach(function(pane) {
                var kodeTpl = (pane.getAttribute('data-kode-template') || '').toUpperCase();
                var namaTpl = (pane.getAttribute('data-nama-template') || '').toLowerCase();
                var paramTpl = (pane.getAttribute('data-parameter') || '').toLowerCase();
                if (kodeTpl === 'KIM-05' || namaTpl.indexOf('spektro') !== -1 || paramTpl.indexOf('spektro') !== -1 || pane.querySelector('input[name*="[tabel_preparasi]"]')) {
                    hitungSpektrofotometriPane(pane);
                }
            });
        }

        function hitungSpektrofotometriPane(pane) {
            if (!pane) return;
            var pName = (pane.getAttribute('data-parameter') || '').toLowerCase();
            var tName = (pane.getAttribute('data-nama-template') || '').toLowerCase();
            var isIdent = (pane.getAttribute('data-is-identifikasi') === '1') || pName.indexOf('identifikasi') !== -1 || tName.indexOf('identifikasi') !== -1;
            if (isIdent) {
                return; // Skip hitung kadar untuk LCP Identifikasi
            }
            var kbInput = pane.querySelector('input[name*="[kadar_baku_persen]"]'); // M25
            var brInput = pane.querySelector('input[name*="[bobot_rata_rata_tablet]"], input[name*="[bobot_rata_tablet]"]'); // K46
            var keInput = pane.querySelector('input[name*="[klaim_etiket]"]'); // K47
            var bmInput = pane.querySelector('input[name*="[faktor_konversi_bm]"]');
            var metodeKadar = getMetodeKadar(pane);
            var volEtiketInput = pane.querySelector('input[name*="[volume_takaran_etiket]"]');
            var Vetiket = (volEtiketInput && parseFloat(volEtiketInput.value) > 0) ? parseFloat(volEtiketInput.value) : 5.0;

            var Kb = kbInput ? parseFloat(kbInput.value) || 0 : 0;
            var Br = brInput ? parseFloat(brInput.value) || 0 : 0;
            var Ke = keInput ? parseFloat(keInput.value) || 0 : 0;
            var bmFactor = evalMathExpression(bmInput ? bmInput.value : 1.0);
            updateBMPreviewInContainer(pane);

            // Baris 0: Baku | Baris 1: Uji 1 | Baris 2: Uji 2
            var wadahZatBaku = pane.querySelector('input[name*="[tabel_preparasi][0][wadah_zat]"]');
            var wadahSisaBaku = pane.querySelector('input[name*="[tabel_preparasi][0][wadah_sisa]"]');
            var bobotZatBakuEl = pane.querySelector('input[name*="[tabel_preparasi][0][bobot_zat]"]');
            var fbEl = pane.querySelector('input[name*="[tabel_preparasi][0][faktor_pengenceran]"]');
            var cBakuEl = pane.querySelector('input[name*="[tabel_preparasi][0][konsentrasi]"]');
            var abEl = pane.querySelector('input[name*="[tabel_preparasi][0][serapan]"]');

            var wadahZatU1 = pane.querySelector('input[name*="[tabel_preparasi][1][wadah_zat]"]');
            var wadahSisaU1 = pane.querySelector('input[name*="[tabel_preparasi][1][wadah_sisa]"]');
            var bobotZatU1El = pane.querySelector('input[name*="[tabel_preparasi][1][bobot_zat]"]');
            var fu1El = pane.querySelector('input[name*="[tabel_preparasi][1][faktor_pengenceran]"]');
            var cU1El = pane.querySelector('input[name*="[tabel_preparasi][1][konsentrasi]"]');
            var au1El = pane.querySelector('input[name*="[tabel_preparasi][1][serapan]"]');

            var wadahZatU2 = pane.querySelector('input[name*="[tabel_preparasi][2][wadah_zat]"]');
            var wadahSisaU2 = pane.querySelector('input[name*="[tabel_preparasi][2][wadah_sisa]"]');
            var bobotZatU2El = pane.querySelector('input[name*="[tabel_preparasi][2][bobot_zat]"]');
            var fu2El = pane.querySelector('input[name*="[tabel_preparasi][2][faktor_pengenceran]"]');
            var cU2El = pane.querySelector('input[name*="[tabel_preparasi][2][konsentrasi]"]');
            var au2El = pane.querySelector('input[name*="[tabel_preparasi][2][serapan]"]');

            if (!kbInput && !abEl) return;

            // 1. Hitung bobot zat = wadah+zat - wadah+sisa (presisi penuh tanpa pembulatan paksa)
            var Bb = 0, Bu1 = 0, Bu2 = 0;
            if (wadahZatBaku && wadahSisaBaku) {
                var wzb = parseFloat(wadahZatBaku.value) || 0;
                var wsb = parseFloat(wadahSisaBaku.value) || 0;
                if (wzb > 0 && wsb > 0 && wzb >= wsb) {
                    Bb = wzb - wsb;
                    if (bobotZatBakuEl && !bobotZatBakuEl.dataset.manualEdited) {
                        bobotZatBakuEl.value = parseFloat(Bb.toPrecision(12)).toString();
                    }
                } else if (bobotZatBakuEl) {
                    Bb = parseFloat(bobotZatBakuEl.value) || 0;
                }
            } else if (bobotZatBakuEl) {
                Bb = parseFloat(bobotZatBakuEl.value) || 0;
            }

            if (metodeKadar === 'tablet_cemplung') {
                Bu1 = 1.0;
                Bu2 = 1.0;
                if (bobotZatU1El && (!bobotZatU1El.value || bobotZatU1El.value === '0')) bobotZatU1El.value = "1";
                if (bobotZatU2El && (!bobotZatU2El.value || bobotZatU2El.value === '0')) bobotZatU2El.value = "1";
            } else if (metodeKadar === 'sirup') {
                if (wadahZatU1 && parseFloat(wadahZatU1.value) > 0) {
                    Bu1 = parseFloat(wadahZatU1.value);
                    if (bobotZatU1El && !bobotZatU1El.dataset.manualEdited) bobotZatU1El.value = wadahZatU1.value;
                } else if (bobotZatU1El) {
                    Bu1 = parseFloat(bobotZatU1El.value) || 0;
                }
                if (wadahZatU2 && parseFloat(wadahZatU2.value) > 0) {
                    Bu2 = parseFloat(wadahZatU2.value);
                    if (bobotZatU2El && !bobotZatU2El.dataset.manualEdited) bobotZatU2El.value = wadahZatU2.value;
                } else if (bobotZatU2El) {
                    Bu2 = parseFloat(bobotZatU2El.value) || 0;
                }
            } else {
                if (wadahZatU1 && wadahSisaU1) {
                    var wzu1 = parseFloat(wadahZatU1.value) || 0;
                    var wsu1 = parseFloat(wadahSisaU1.value) || 0;
                    if (wzu1 > 0 && wsu1 > 0 && wzu1 >= wsu1) {
                        Bu1 = wzu1 - wsu1;
                        if (bobotZatU1El && !bobotZatU1El.dataset.manualEdited) {
                            bobotZatU1El.value = parseFloat(Bu1.toPrecision(12)).toString();
                        }
                    } else if (bobotZatU1El) {
                        Bu1 = parseFloat(bobotZatU1El.value) || 0;
                    }
                } else if (bobotZatU1El) {
                    Bu1 = parseFloat(bobotZatU1El.value) || 0;
                }

                if (wadahZatU2 && wadahSisaU2) {
                    var wzu2 = parseFloat(wadahZatU2.value) || 0;
                    var wsu2 = parseFloat(wadahSisaU2.value) || 0;
                    if (wzu2 > 0 && wsu2 > 0 && wzu2 >= wsu2) {
                        Bu2 = wzu2 - wsu2;
                        if (bobotZatU2El && !bobotZatU2El.dataset.manualEdited) {
                            bobotZatU2El.value = parseFloat(Bu2.toPrecision(12)).toString();
                        }
                    } else if (bobotZatU2El) {
                        Bu2 = parseFloat(bobotZatU2El.value) || 0;
                    }
                } else if (bobotZatU2El) {
                    Bu2 = parseFloat(bobotZatU2El.value) || 0;
                }
            }

            var Ab = abEl ? parseFloat(abEl.value) || 0 : 0;
            var Fb = fbEl ? parseFloat(fbEl.value) || 1 : 1;
            var Au1 = au1El ? parseFloat(au1El.value) || 0 : 0;
            var Fu1 = fu1El ? parseFloat(fu1El.value) || 1 : 1;
            var Au2 = au2El ? parseFloat(au2El.value) || 0 : 0;
            var Fu2 = fu2El ? parseFloat(fu2El.value) || 1 : 1;

            // 1b. Hitung otomatis Konsentrasi (mcg/mL = mg * 1000 / FP) HANYA jika belum dimodifikasi manual
            if (cBakuEl && Bb > 0 && Fb > 0 && !cBakuEl.dataset.manualEdited && cBakuEl.value === '') {
                cBakuEl.value = parseFloat(((Bb * 1000) / Fb).toPrecision(8)).toString();
            }
            if (cU1El && Bu1 > 0 && Fu1 > 0 && !cU1El.dataset.manualEdited && cU1El.value === '') {
                cU1El.value = parseFloat(((Bu1 * 1000) / Fu1).toPrecision(8)).toString();
            }
            if (cU2El && Bu2 > 0 && Fu2 > 0 && !cU2El.dataset.manualEdited && cU2El.value === '') {
                cU2El.value = parseFloat(((Bu2 * 1000) / Fu2).toPrecision(8)).toString();
            }

            // 2. Hitung Kadar Uji 1 & 2 (%) berdasarkan METODE PERHITUNGAN
            var kadarU1 = 0, kadarU2 = 0;
            var faktorKb = (Kb > 1) ? (Kb / 100) : (Kb > 0 ? Kb : 1.0);

            if (Ab > 0 && Ke > 0 && Bb > 0) {
                if (metodeKadar === 'tablet_cemplung') {
                    if (Au1 > 0) kadarU1 = (Au1 / Ab) * Bb * (Fu1 / Fb) * (1.0 / Ke) * faktorKb * bmFactor * 100;
                    if (Au2 > 0) kadarU2 = (Au2 / Ab) * Bb * (Fu2 / Fb) * (1.0 / Ke) * faktorKb * bmFactor * 100;
                } else if (metodeKadar === 'sirup') {
                    if (Bu1 > 0 && Au1 > 0) kadarU1 = (Au1 / Ab) * (Bb / Bu1) * (Fu1 / Fb) * (Vetiket / Ke) * faktorKb * bmFactor * 100;
                    if (Bu2 > 0 && Au2 > 0) kadarU2 = (Au2 / Ab) * (Bb / Bu2) * (Fu2 / Fb) * (Vetiket / Ke) * faktorKb * bmFactor * 100;
                } else {
                    if (Bu1 > 0 && Au1 > 0 && Br > 0) kadarU1 = (Au1 / Ab) * (Bb / Bu1) * (Fu1 / Fb) * (Br / Ke) * faktorKb * bmFactor * 100;
                    if (Bu2 > 0 && Au2 > 0 && Br > 0) kadarU2 = (Au2 / Ab) * (Bb / Bu2) * (Fu2 / Fb) * (Br / Ke) * faktorKb * bmFactor * 100;
                }
            }

            var kadarU1Input = pane.querySelector('input[name*="[kadar_uji_1]"]');
            var kadarU2Input = pane.querySelector('input[name*="[kadar_uji_2]"]');
            var kadarRataPersenInput = pane.querySelector('input[name*="[kadar_rata_rata_persen]"]');
            var kadarRataMgInput = pane.querySelector('input[name*="[kadar_rata_rata_mg]"]');
            var rpdInput = pane.querySelector('input[name*="[rpd_hitung]"], input[name*="[nilai_rpd]"]');
            var horwitzInput = pane.querySelector('input[name*="[syarat_rpd_horwitz]"], input[name*="[syarat_horwitz]"]');

            if (kadarU1Input) kadarU1Input.value = (kadarU1 > 0) ? parseFloat(kadarU1.toPrecision(12)).toString() : '';
            if (kadarU2Input) kadarU2Input.value = (kadarU2 > 0) ? parseFloat(kadarU2.toPrecision(12)).toString() : '';

            if (kadarU1 > 0 || kadarU2 > 0) {
                var rataPersen = (kadarU1 > 0 && kadarU2 > 0) ? (kadarU1 + kadarU2) / 2 : (kadarU1 || kadarU2);
                var rataMg = Ke > 0 ? (rataPersen * Ke) / 100 : 0;

                if (kadarRataPersenInput) kadarRataPersenInput.value = parseFloat(rataPersen.toPrecision(12)).toString();
                if (kadarRataMgInput) kadarRataMgInput.value = (rataMg > 0) ? parseFloat(rataMg.toPrecision(12)).toString() : '';

                if (kadarU1 > 0 && kadarU2 > 0 && rataPersen > 0) {
                    var rpd = (Math.abs(kadarU1 - kadarU2) / rataPersen) * 100;
                    if (rpdInput) rpdInput.value = parseFloat(rpd.toPrecision(12)).toString();

                    var C_fraksi = rataPersen / 100;
                    if (C_fraksi > 0) {
                        var horw = 0.5 * Math.pow(2, (1 - 0.5 * (Math.log10(C_fraksi))));
                        if (horwitzInput) horwitzInput.value = parseFloat(horw.toPrecision(12)).toString();
                    }
                }

                evaluasiKesimpulanPane(pane);
            } else {
                if (kadarRataPersenInput && !kadarRataPersenInput.dataset.manualEdited) kadarRataPersenInput.value = '';
                if (kadarRataMgInput && !kadarRataMgInput.dataset.manualEdited) kadarRataMgInput.value = '';
                if (rpdInput && !rpdInput.dataset.manualEdited) rpdInput.value = '';
                if (horwitzInput && !horwitzInput.dataset.manualEdited) horwitzInput.value = '';
            }
        }

        // Listener untuk KIM-05 Spektrofotometri & Multi-Metode Kadar
        document.addEventListener('input', function(e) {
            if (e.target && e.target.matches('input[name*="[tabel_preparasi]"], input[name*="[kadar_baku_persen]"], input[name*="[bobot_rata_rata_tablet]"], input[name*="[klaim_etiket]"], input[name*="[faktor_konversi_bm]"], input[name*="[volume_takaran_etiket]"]')) {
                hitungSpektrofotometri();
            }
        });

        document.addEventListener('change', function(e) {
            if (e.target && e.target.matches('select[name*="[metode_perhitungan_kadar]"]')) {
                var pane = e.target.closest('.form-tab-pane-uji');
                if (pane) {
                    updateMetodeKadarUI(pane);
                    hitungSpektrofotometri();
                    hitungHPLC();
                    evaluasiKesimpulanPane(pane);
                }
            }
        });

        // =========================================================================
        // Evaluator Ekspresi Matematika Aman untuk Faktor Koreksi BM (BM1 / BM2)
        // Menerima angka biasa (1.0, 0.896, 0,896) atau rumus pembagian (425.9/41, (314.4/350.87))
        // =========================================================================
        function evalMathExpression(expr) {
            if (expr === null || expr === undefined) return 1.0;
            var str = String(expr).trim();
            if (str === '') return 1.0;

            str = str.replace(/,/g, '.');

            if (!isNaN(str) && !isNaN(parseFloat(str))) {
                var num = parseFloat(str);
                return num > 0 ? num : 1.0;
            }

            if (!/^[0-9\.\s\(\)\+\-\*\/]+$/.test(str)) {
                return 1.0;
            }

            try {
                var res = Function('"use strict"; return (' + str + ');')();
                if (typeof res === 'number' && !isNaN(res) && isFinite(res) && res > 0) {
                    return res;
                }
            } catch (e) {}
            return 1.0;
        }

        // Helper update badge live preview hasil hitung BM di formulir
        function updateBMPreviewInContainer(container) {
            if (!container) container = document;
            var bmInputs = container.querySelectorAll('.input-faktor-bm, input[name*="[faktor_konversi_bm]"]');
            bmInputs.forEach(function(bmInput) {
                var bmCol = bmInput.closest('.col-md-6') || bmInput.closest('.col-12') || bmInput.parentElement;
                if (!bmCol) return;
                var previewEl = bmCol.querySelector('.bm-calc-preview');
                if (!previewEl) return;
                var evalValEl = previewEl.querySelector('.bm-eval-val');
                var bmRaw = String(bmInput.value || '').trim();
                var bmFactor = evalMathExpression(bmRaw);
                var hasFormula = bmRaw !== '' && (bmRaw.indexOf('/') !== -1 || bmRaw.indexOf('*') !== -1 || bmRaw.indexOf('(') !== -1 || isNaN(bmRaw.replace(/,/g, '.')));
                if (hasFormula || (bmRaw !== '' && parseFloat(bmRaw) !== 1.0)) {
                    if (evalValEl) evalValEl.textContent = parseFloat(bmFactor.toPrecision(8)).toString();
                    previewEl.style.display = 'block';
                } else {
                    previewEl.style.display = 'none';
                }
            });
        }

        // Listener universal input faktor koreksi BM
        document.addEventListener('input', function(e) {
            if (e.target && e.target.matches('.input-faktor-bm, input[name*="[faktor_konversi_bm]"]')) {
                var pane = e.target.closest('.form-tab-pane-uji') || document;
                updateBMPreviewInContainer(pane);
                hitungHPLC();
                hitungSpektrofotometri();
                hitungDisolusi();
            }
        });

        // Auto-Calculator Engine untuk Formulir Disolusi (KIM-06)
        // Sesuai Formula Resmi Excel: POM-06.02/CFM.01/SOP.01/F.01A.08
        // Fk = (V * Fu * Bb * Kb * [BM1/BM2] * 100%) / (Fb * Ab * Ke)
        // % Terlarut (Dx) = Fk * Au
        // =========================================================================
        function hitungDisolusi() {
            var panes = document.querySelectorAll('.form-tab-pane-uji');
            panes.forEach(function(pane) {
                var kodeTpl = (pane.getAttribute('data-kode-template') || '').toUpperCase();
                var namaTpl = (pane.getAttribute('data-nama-template') || '').toLowerCase();
                var paramTpl = (pane.getAttribute('data-parameter') || '').toLowerCase();
                if (kodeTpl === 'KIM-06' || namaTpl.indexOf('disolusi') !== -1 || paramTpl.indexOf('disolusi') !== -1 || pane.querySelector('input[name*="[volume_media]"]')) {
                    hitungDisolusiPane(pane);
                }
            });
        }

        function hitungDisolusiPane(pane) {
            var vInput = pane.querySelector('input[name*="[volume_media]"]');               // P12 (V)
            var keInput = pane.querySelector('input[name*="[komposisi_etiket]"]');          // D10 (Ke)
            var kbInput = pane.querySelector('input[name*="[kadar_baku]"]');                // P18 (Kb)
            var spInput = pane.querySelector('input[name*="[susut_pengeringan]"]');         // P19
            var kbKoreksiInput = pane.querySelector('input[name*="[kadar_baku_terkoreksi]"]'); // Kb Terkoreksi (Poin 5)
            var bbInput = pane.querySelector('input[name*="[bobot_baku_mg]"]');             // P21 (Bb)
            var bmInput = pane.querySelector('input[name*="[faktor_konversi_bm]"]');        // BM1/BM2 (Poin 6)
            var abInput = pane.querySelector('input[name*="[hasil_ukur_baku_ab]"]');        // P24 (Ab)
            var fbInput = pane.querySelector('input[name*="[faktor_pengenceran_baku]"]');   // P25 (Fb)
            var fuInput = pane.querySelector('input[name*="[faktor_pengenceran_uji]"]');    // P26 (Fu)

            if (!vInput || !abInput) return;

            var V = parseFloat(vInput.value) || 0;
            var Ke = parseFloat(keInput ? keInput.value : 0) || 0;
            var rawKb = parseFloat(kbInput ? kbInput.value : 100) || 100;
            var SP = parseFloat(spInput ? spInput.value : 0) || 0;
            var Kb = (100 - SP) * (rawKb / 100); // Koreksi kemurnian baku: (100 - SP) * (rawKb / 100)

            // Poin 5: Tampilkan Kadar Terkoreksi Baku (Kb) secara visual ke input field
            if (kbKoreksiInput) {
                kbKoreksiInput.value = Kb.toFixed(2);
            }

            var Bb = parseFloat(bbInput ? bbInput.value : 0) || 0;

            // Poin 6: Evaluasi ekspresi rumus BM1 / BM2
            var bmRaw = bmInput ? bmInput.value : '1.0';
            var bmFactor = evalMathExpression(bmRaw);

            // Update badge live preview hasil hitung BM
            var bmCol = bmInput ? bmInput.closest('.col-md-6') : null;
            if (bmCol) {
                var previewEl = bmCol.querySelector('.bm-calc-preview');
                if (previewEl) {
                    var evalValEl = previewEl.querySelector('.bm-eval-val');
                    var strClean = String(bmRaw).trim();
                    var hasFormula = strClean !== '' && (strClean.indexOf('/') !== -1 || strClean.indexOf('*') !== -1 || strClean.indexOf('(') !== -1 || isNaN(strClean));
                    if (hasFormula) {
                        if (evalValEl) evalValEl.textContent = bmFactor.toFixed(4);
                        previewEl.style.display = 'block';
                    } else {
                        previewEl.style.display = 'none';
                    }
                }
            }

            var Ab = parseFloat(abInput.value) || 0;
            var Fb = parseFloat(fbInput ? fbInput.value : 1) || 1;
            var Fu = parseFloat(fuInput ? fuInput.value : 1) || 1;

            // Hitung Faktor Perkalian Fk
            var Fk = 0;
            var penyebut = Fb * Ab * Ke;
            if (penyebut > 0) {
                // (V * Fu * Bb * (Kb/100 jika persen) * bmFactor * 100%) / (Fb * Ab * Ke)
                Fk = (V * Fu * Bb * (Kb / 100) * bmFactor * 100) / penyebut;
            }

            var fkInput = pane.querySelector('input[name*="[faktor_perkalian_fk]"]');
            if (fkInput && Fk > 0) fkInput.value = Fk.toFixed(4);

            // Faktor Pengali Tambahan Opsional (Manual / Pengenceran Bertingkat / Koreksi Khusus)
            var fTambahanInput = pane.querySelector('input[name*="[faktor_pengali_tambahan]"]');
            var fTambahanRaw = fTambahanInput ? fTambahanInput.value : '1.0';
            var fTambahan = evalMathExpression(fTambahanRaw);
            if (isNaN(fTambahan) || fTambahan <= 0) fTambahan = 1.0;

            // Update badge live preview hasil evaluasi Faktor Pengali Tambahan jika berupa pecahan (misal: 50/10)
            var fTambahanCol = fTambahanInput ? fTambahanInput.closest('.col-md-6, .col-12') : null;
            if (fTambahanCol) {
                var previewEl2 = fTambahanCol.querySelector('.fk-tambahan-preview');
                if (previewEl2) {
                    var evalValEl2 = previewEl2.querySelector('.fk-tambahan-eval-val');
                    var strClean2 = String(fTambahanRaw).trim();
                    var isCustom = strClean2 !== '' && strClean2 !== '1' && strClean2 !== '1.0' && strClean2 !== '1.00';
                    if (isCustom) {
                        if (evalValEl2) evalValEl2.textContent = fTambahan.toFixed(4);
                        previewEl2.style.display = 'block';
                    } else {
                        previewEl2.style.display = 'none';
                    }
                }
            }

            // Hitung Fk Final = Fk Otomatis * Faktor Pengali Tambahan
            var FkFinal = Fk * fTambahan;
            var fkFinalInput = pane.querySelector('input[name*="[faktor_perkalian_final]"]');
            if (fkFinalInput && FkFinal > 0) {
                fkFinalInput.value = FkFinal.toFixed(4);
            }

            // Gunakan FkEfektif (FkFinal jika aktif, fallback Fk) untuk seluruh % zat aktif terlarut
            var FkEfektif = (FkFinal > 0) ? FkFinal : Fk;

            // 1. Hitung % Terlarut tiap bejana 1 - 6 (Tahap S1)
            var terlarutValsS1 = [];
            for (var b = 0; b < 6; b++) {
                var auEl = pane.querySelector('input[name*="[tabel_s1][' + b + '][pengukuran_au]"]');
                var dxEl = pane.querySelector('input[name*="[tabel_s1][' + b + '][persen_terlarut]"]');

                if (auEl) {
                    var Au = parseFloat(auEl.value) || 0;
                    if (Au > 0 && FkEfektif > 0) {
                        var Dx = FkEfektif * Au;
                        if (dxEl) dxEl.value = Dx.toFixed(2);
                        terlarutValsS1.push(Dx);
                    } else if (dxEl && parseFloat(dxEl.value)) {
                        terlarutValsS1.push(parseFloat(dxEl.value));
                    }
                }
            }

            // Statistik Tahap S1 (Rata-rata & Nilai Minimum 6 Bejana)
            if (terlarutValsS1.length >= 1) {
                var sumS1 = terlarutValsS1.reduce(function(a, c) { return a + c; }, 0);
                var meanS1 = sumS1 / terlarutValsS1.length;
                var minValS1 = Math.min.apply(null, terlarutValsS1);

                var rataInputS1 = pane.querySelector('input[name*="[rata_rata_s1]"]');
                var minInputS1 = pane.querySelector('input[name*="[nilai_minimum_s1]"]');

                if (rataInputS1) rataInputS1.value = meanS1.toFixed(2);
                if (minInputS1) minInputS1.value = minValS1.toFixed(2);
            }

            // 2. Hitung % Terlarut tiap bejana 7 - 12 (Tahap S2 jika aktif)
            var trigS2 = pane.querySelector('select[name*="[lakukan_tahap_s2]"]');
            var isAktifS2 = trigS2 && trigS2.value === 'Ya';
            var terlarutValsS2 = [];

            if (isAktifS2) {
                for (var b2 = 0; b2 < 6; b2++) {
                    var auElS2 = pane.querySelector('input[name*="[tabel_s2][' + b2 + '][pengukuran_au]"]');
                    var dxElS2 = pane.querySelector('input[name*="[tabel_s2][' + b2 + '][persen_terlarut]"]');

                    if (auElS2) {
                        var AuS2 = parseFloat(auElS2.value) || 0;
                        if (AuS2 > 0 && FkEfektif > 0) {
                            var DxS2 = FkEfektif * AuS2;
                            if (dxElS2) dxElS2.value = DxS2.toFixed(2);
                            terlarutValsS2.push(DxS2);
                        } else if (dxElS2 && parseFloat(dxElS2.value)) {
                            terlarutValsS2.push(parseFloat(dxElS2.value));
                        }
                    }
                }

                // Statistik Gabungan 12 Unit (S1 + S2)
                var all12 = terlarutValsS1.concat(terlarutValsS2);
                if (all12.length >= 1) {
                    var sum12 = all12.reduce(function(a, c) { return a + c; }, 0);
                    var mean12 = sum12 / all12.length;
                    var minVal12 = Math.min.apply(null, all12);

                    var rataInputS2 = pane.querySelector('input[name*="[rata_rata_12_unit]"]');
                    var minInputS2 = pane.querySelector('input[name*="[nilai_minimum_12_unit]"]');

                    if (rataInputS2) rataInputS2.value = mean12.toFixed(2);
                    if (minInputS2) minInputS2.value = minVal12.toFixed(2);
                }
            }

            // 3. Hitung % Terlarut tiap bejana 13 - 24 (Tahap S3 jika aktif)
            var trigS3 = pane.querySelector('select[name*="[lakukan_tahap_s3]"]');
            var isAktifS3 = trigS3 && trigS3.value === 'Ya';
            var terlarutValsS3 = [];

            if (isAktifS3) {
                for (var b3 = 0; b3 < 12; b3++) {
                    var auElS3 = pane.querySelector('input[name*="[tabel_s3][' + b3 + '][pengukuran_au]"]');
                    var dxElS3 = pane.querySelector('input[name*="[tabel_s3][' + b3 + '][persen_terlarut]"]');

                    if (auElS3) {
                        var AuS3 = parseFloat(auElS3.value) || 0;
                        if (AuS3 > 0 && FkEfektif > 0) {
                            var DxS3 = FkEfektif * AuS3;
                            if (dxElS3) dxElS3.value = DxS3.toFixed(2);
                            terlarutValsS3.push(DxS3);
                        } else if (dxElS3 && parseFloat(dxElS3.value)) {
                            terlarutValsS3.push(parseFloat(dxElS3.value));
                        }
                    }
                }

                // Statistik Gabungan 24 Unit (S1 + S2 + S3)
                var all24 = terlarutValsS1.concat(terlarutValsS2).concat(terlarutValsS3);
                if (all24.length >= 1) {
                    var sum24 = all24.reduce(function(a, c) { return a + c; }, 0);
                    var mean24 = sum24 / all24.length;
                    var minVal24 = Math.min.apply(null, all24);

                    var rataInputS3 = pane.querySelector('input[name*="[rata_rata_24_unit]"]');
                    var minInputS3 = pane.querySelector('input[name*="[nilai_minimum_24_unit]"]');

                    if (rataInputS3) rataInputS3.value = mean24.toFixed(2);
                    if (minInputS3) minInputS3.value = minVal24.toFixed(2);
                }
            }

            // Evaluasi kriteria kelulusan disolusi S1/S2/S3 otomatis
            evaluasiKesimpulanPane(pane);
        }

        // Listener untuk KIM-06 Disolusi (termasuk tabel S1, S2, S3, kadar baku, susut pengeringan, rumus BM, & faktor pengali tambahan)
        document.addEventListener('input', function(e) {
            if (e.target && e.target.matches('input[name*="[tabel_s1]"], input[name*="[tabel_s2]"], input[name*="[tabel_s3]"], input[name*="[volume_media]"], input[name*="[komposisi_etiket]"], input[name*="[kadar_baku]"], input[name*="[susut_pengeringan]"], input[name*="[bobot_baku_mg]"], input[name*="[faktor_konversi_bm]"], input[name*="[faktor_pengali_tambahan]"], input[name*="[hasil_ukur_baku_ab]"], input[name*="[faktor_pengenceran_baku]"], input[name*="[faktor_pengenceran_uji]"]')) {
                hitungDisolusi();
            }
        });
        document.addEventListener('change', function(e) {
            if (e.target && e.target.matches('input[name*="[kadar_baku]"], input[name*="[susut_pengeringan]"], input[name*="[faktor_konversi_bm]"], input[name*="[faktor_pengali_tambahan]"], select[name*="[lakukan_tahap_s2]"], select[name*="[lakukan_tahap_s3]"]')) {
                var pane = e.target.closest('.form-tab-pane-uji');
                if (pane) updateTahapVisibility(pane);
                hitungDisolusi();
            }
        });

        // =========================================================================
        // Auto-Calculator Engine untuk Formulir KLT (KIM-07)
        // Menghitung otomatis: Bobot Zat = Wadah + Zat - Wadah + Sisa
        // =========================================================================
        function hitungKLT() {
            var kltRows = document.querySelectorAll('tr:has(input[name*="[wadah_zat]"])');
            if (!kltRows || kltRows.length === 0) {
                // Alternatif fallback jika browser belum support selector :has
                var allWadahZat = document.querySelectorAll('input[name*="[wadah_zat]"]');
                allWadahZat.forEach(function(wZatInput) {
                    var tr = wZatInput.closest('tr');
                    if (tr) {
                        hitungBarisKLT(tr);
                    }
                });
                return;
            }

            kltRows.forEach(function(row) {
                hitungBarisKLT(row);
            });
        }

        function hitungBarisKLT(tr) {
            var wZatInput = tr.querySelector('input[name*="[wadah_zat]"]');
            var wSisaInput = tr.querySelector('input[name*="[wadah_sisa]"]');
            var bZatInput = tr.querySelector('input[name*="[bobot_zat]"]');

            if (wZatInput && wSisaInput && bZatInput) {
                var wZat = parseFloat(wZatInput.value) || 0;
                var wSisa = parseFloat(wSisaInput.value) || 0;
                if (wZat > 0 && wSisa > 0 && wZat >= wSisa) {
                    var bZat = wZat - wSisa;
                    bZatInput.value = bZat.toFixed(4);
                }
            }
        }

        document.addEventListener('input', function(e) {
            if (e.target && (e.target.name && (e.target.name.indexOf('[wadah_zat]') !== -1 || e.target.name.indexOf('[wadah_sisa]') !== -1))) {
                var tr = e.target.closest('tr');
                if (tr) {
                    hitungBarisKLT(tr);
                }
            }
        });

        // =========================================================================
        // Auto-Calculator Engine untuk Formulir HPLC / KCKT (KIM-08)
        // Mendukung 3 Metode: Tablet BR, Tablet Cemplung, dan Sirup / Cairan
        // Rumus Resmi: Kadar = (Lu/Lb) * [Faktor Perkalian Sediaan] * Kb * [Faktor BM] * 100%
        // RPD = |K1 - K2| / Kadar * 100%, Horwitz = 0.5 * 2^(1 - 0.5*log10(C))
        // =========================================================================
        function hitungHPLC() {
            var panes = document.querySelectorAll('.form-tab-pane-uji');
            panes.forEach(function(pane) {
                var kodeTpl = (pane.getAttribute('data-kode-template') || '').toUpperCase();
                var namaTpl = (pane.getAttribute('data-nama-template') || '').toLowerCase();
                var paramTpl = (pane.getAttribute('data-parameter') || '').toLowerCase();
                if (kodeTpl === 'KIM-08' || namaTpl.indexOf('kckt') !== -1 || namaTpl.indexOf('hplc') !== -1 || paramTpl.indexOf('kadar') !== -1 || pane.querySelector('table input[name*="[respon_puncak]"]')) {
                    hitungHPLCPane(pane);
                }
            });
        }

        function hitungHPLCPane(pane) {
        if (!pane) return;
        var pName = (pane.getAttribute('data-parameter') || '').toLowerCase();
        var tName = (pane.getAttribute('data-nama-template') || '').toLowerCase();
        if (pName.indexOf('identifikasi') !== -1 || tName.indexOf('identifikasi') !== -1) {
            return; // Skip hitung kadar untuk LCP Identifikasi
        }
            if (!pane) return;

            // 1. Tipe Metode Kadar (Tablet BR / Tablet Cemplung / Sirup)
            var metodeKadar = getMetodeKadar(pane);
            var volEtiketInput = pane.querySelector('input[name*="[volume_takaran_etiket]"]');
            var Vetiket = (volEtiketInput && parseFloat(volEtiketInput.value) > 0) ? parseFloat(volEtiketInput.value) : 5.0;

            // 2. Koreksi Kadar Kemurnian Baku
            var bakuKadarInput = pane.querySelector('input[name*="[baku_kadar]"]');
            var bakuSpInput = pane.querySelector('input[name*="[baku_sp]"]');
            var bakuTerkoreksiInput = pane.querySelector('input[name*="[baku_kadar_terkoreksi]"]');

            var Kb = 100.0;
            if (bakuKadarInput && parseFloat(bakuKadarInput.value) > 0) {
                var rawKadar = parseFloat(bakuKadarInput.value) || 0;
                var sp = parseFloat(bakuSpInput ? bakuSpInput.value : 0) || 0;
                Kb = (100 - sp) * (rawKadar / 100);
                if (bakuTerkoreksiInput) {
                    bakuTerkoreksiInput.value = parseFloat(Kb.toPrecision(8)).toString();
                }
            } else if (bakuTerkoreksiInput && parseFloat(bakuTerkoreksiInput.value) > 0) {
                Kb = parseFloat(bakuTerkoreksiInput.value);
            }

            // 3. Faktor Koreksi BM / BR (BM1 / BM2)
            var bmInput = pane.querySelector('input[name*="[faktor_konversi_bm]"]');
            var bmFactor = evalMathExpression(bmInput ? bmInput.value : 1.0);
            updateBMPreviewInContainer(pane);

            // 4. Hitung Bobot Bersih Tiap Baris Tabel HPLC (Scoped khusus ke tabel HPLC, bukan tabel gradient)
            var hplcInput = pane.querySelector('input[name*="[tabel_hplc]"], input[name*="[respon_puncak]"]');
            var hplcTable = hplcInput ? hplcInput.closest('table') : null;
            var rows = hplcTable ? Array.from(hplcTable.querySelectorAll('tbody tr')) : [];
            if (rows.length === 0) {
                rows = Array.from(pane.querySelectorAll('tr:has(input[name*="[respon_puncak]"]), tr:has(input[name*="[bobot_bersih]"])'));
            }

            rows.forEach(function(tr) {
                hitungBarisHPLC(tr, metodeKadar);
            });

            // 5. Parameter Tambahan: Br (Bobot rata tablet) & Ke (Klaim etiket)
            var brInput = pane.querySelector('input[name*="[bobot_rata_tablet]"], input[name*="[bobot_rata_rata_tablet]"]');
            var keInput = pane.querySelector('input[name*="[klaim_etiket]"]');
            var Br = parseFloat(brInput ? brInput.value : 0) || 0;
            var Ke = parseFloat(keInput ? keInput.value : 0) || 0;

            // 6. Ambil Data Baku Pembanding & Zat Uji Replikasi 1 & 2 secara dinamis dari tabel HPLC
            var bakuRow = null;
            var uji1Row = null;
            var uji2Row = null;

            rows.forEach(function(tr) {
                var katInput = tr.querySelector('input[name*="[kategori]"], td:first-child input, td:first-child');
                var katText = (katInput ? (katInput.value || katInput.textContent || '') : '').toLowerCase();
                if (katText.indexOf('baku pembanding') !== -1 || (katText.indexOf('baku') !== -1 && katText.indexOf('internal') === -1)) {
                    if (!bakuRow) bakuRow = tr;
                } else if (katText.indexOf('uji 1') !== -1 || katText.indexOf('replikasi 1') !== -1) {
                    uji1Row = tr;
                } else if (katText.indexOf('uji 2') !== -1 || katText.indexOf('replikasi 2') !== -1) {
                    uji2Row = tr;
                }
            });

            // Fallback berdasarkan index tabel HPLC jika teks kategori belum/tidak cocok
            if (!bakuRow && rows.length > 0) bakuRow = rows[0];
            if (!uji1Row) {
                if (rows.length >= 4) uji1Row = rows[2];
                else if (rows.length >= 2) uji1Row = rows[1];
            }
            if (!uji2Row) {
                if (rows.length >= 4) uji2Row = rows[3];
                else if (rows.length >= 3) uji2Row = rows[2];
            }

            var Bb = 0, Fb = 1, Lb = 0;
            if (bakuRow) {
                var bbEl = bakuRow.querySelector('input[name*="[bobot_bersih]"]');
                var fbEl = bakuRow.querySelector('input[name*="[faktor_pengenceran]"]');
                var lbEl = bakuRow.querySelector('input[name*="[respon_puncak]"]');
                Bb = parseFloat(bbEl ? bbEl.value : 0) || 0;
                Fb = parseFloat(fbEl ? fbEl.value : 1) || 1;
                Lb = parseFloat(lbEl ? lbEl.value : 0) || 0;
            }

            // 7. Hitung Kadar Uji 1 dan Uji 2 (Presisi unrounded sebenar-benarnya)
            var k1 = hitungKadarSatuBaris(uji1Row, Lb, Bb, Fb, Br, Ke, Kb, bmFactor, metodeKadar, Vetiket);
            var k2 = hitungKadarSatuBaris(uji2Row, Lb, Bb, Fb, Br, Ke, Kb, bmFactor, metodeKadar, Vetiket);

            var k1Input = pane.querySelector('input[name*="[kadar_replikasi_1]"], input[name*="[kadar_uji_1]"]');
            var k2Input = pane.querySelector('input[name*="[kadar_replikasi_2]"], input[name*="[kadar_uji_2]"]');

            if (k1Input) k1Input.value = (k1 > 0) ? parseFloat(k1.toPrecision(12)).toString() : '';
            if (k2Input) k2Input.value = (k2 > 0) ? parseFloat(k2.toPrecision(12)).toString() : '';

            // 8. Rata-rata Kadar, RPD & Horwitz (Presisi unrounded sebenar-benarnya)
            var rataPersenInput = pane.querySelector('input[name*="[kadar_rata_persen]"], input[name*="[kadar_rata_rata_persen]"]');
            var rataMgInput = pane.querySelector('input[name*="[kadar_rata_mg]"], input[name*="[kadar_rata_rata_mg]"]');
            var rpdInput = pane.querySelector('input[name*="[nilai_rpd]"], input[name*="[rpd_hitung]"]');
            var horwitzInput = pane.querySelector('input[name*="[syarat_horwitz]"], input[name*="[syarat_rpd_horwitz]"]');

            if (k1 > 0 || k2 > 0) {
                var rataPersen = (k1 > 0 && k2 > 0) ? (k1 + k2) / 2 : (k1 || k2);
                var rataMg = Ke > 0 ? (rataPersen / 100) * Ke : 0;
                var rpd = (k1 > 0 && k2 > 0 && rataPersen > 0) ? (Math.abs(k1 - k2) / rataPersen) * 100 : 0;

                var C = rataPersen / 100;
                var horwitz = 0;
                if (C > 0) {
                    horwitz = 0.5 * Math.pow(2, (1 - (0.5 * (Math.log(C) / Math.LN10))));
                }

                if (rataPersenInput) rataPersenInput.value = parseFloat(rataPersen.toPrecision(12)).toString();
                if (rataMgInput) rataMgInput.value = (rataMg > 0) ? parseFloat(rataMg.toPrecision(12)).toString() : '';
                if (rpdInput) rpdInput.value = (rpd > 0) ? parseFloat(rpd.toPrecision(12)).toString() : '0';
                if (horwitzInput) horwitzInput.value = (horwitz > 0) ? parseFloat(horwitz.toPrecision(12)).toString() : '0';

                evaluasiKesimpulanPane(pane);
            } else {
                if (rataPersenInput && !rataPersenInput.dataset.manualEdited) rataPersenInput.value = '';
                if (rataMgInput && !rataMgInput.dataset.manualEdited) rataMgInput.value = '';
                if (rpdInput && !rpdInput.dataset.manualEdited) rpdInput.value = '';
                if (horwitzInput && !horwitzInput.dataset.manualEdited) horwitzInput.value = '';
            }
        }

        function hitungBarisHPLC(tr, metodeKadar) {
            if (!tr) return;
            var wZatInput = tr.querySelector('input[name*="[wadah_zat]"]');
            var wSisaInput = tr.querySelector('input[name*="[wadah_sisa]"]');
            var bBersihInput = tr.querySelector('input[name*="[bobot_bersih]"]');
            var fpInput = tr.querySelector('input[name*="[faktor_pengenceran]"]');
            var cInput = tr.querySelector('input[name*="[konsentrasi]"]');

            var katInput = tr.querySelector('input[name*="[kategori]"], td:first-child input, td:first-child');
            var katText = (katInput ? (katInput.value || katInput.textContent || '') : '').toLowerCase();
            var isUjiRow = (katText.indexOf('uji') !== -1 || katText.indexOf('sampel') !== -1);

            var mode = metodeKadar || 'tablet_br';

            // Penanganan khusus untuk Tablet Cemplung dan Sirup pada baris Zat Uji:
            if (isUjiRow && mode === 'tablet_cemplung') {
                if (wZatInput) wZatInput.placeholder = "1 Tab Utuh";
                if (wSisaInput) wSisaInput.placeholder = "(Digabung)";
                if (bBersihInput && (!bBersihInput.value || bBersihInput.value === '0')) {
                    bBersihInput.value = "1";
                }
            } else if (isUjiRow && mode === 'sirup') {
                if (wSisaInput) wSisaInput.placeholder = "(Digabung)";
                if (wZatInput && parseFloat(wZatInput.value) > 0) {
                    if (bBersihInput && !bBersihInput.dataset.manualEdited) {
                        bBersihInput.value = wZatInput.value;
                    }
                }
            } else {
                // Mode standar / Baku Pembanding: Wadah + Zat - Wadah + Sisa (presisi sebenarnya tanpa pembulatan paksa)
                if (wZatInput && bBersihInput) {
                    var wZatStr = (wZatInput.value || '').trim();
                    var wSisaStr = wSisaInput ? (wSisaInput.value || '').trim() : '';
                    if (wZatStr !== '') {
                        var wZat = parseFloat(wZatStr) || 0;
                        var wSisa = (wSisaStr !== '') ? (parseFloat(wSisaStr) || 0) : 0;
                        if (wZat >= wSisa) {
                            var diff = wZat - wSisa;
                            var cleanVal = parseFloat(diff.toPrecision(12)).toString();
                            if (!bBersihInput.dataset.manualEdited || bBersihInput.value === '') {
                                bBersihInput.value = cleanVal;
                            }
                        }
                    }
                }
            }

            var bBersih = parseFloat(bBersihInput ? bBersihInput.value : 0) || 0;

            // Hitung dan tampilkan nilai konsentrasi secara otomatis HANYA jika belum diubah manual oleh pengguna
            if (cInput && fpInput && bBersih > 0) {
                var fpVal = parseFloat(fpInput.value) || 0;
                if (fpVal > 0 && !cInput.dataset.manualEdited) {
                    var bMg = (bBersih < 2.0) ? (bBersih * 1000) : bBersih;
                    var cVal = bMg / fpVal; // mg/mL
                    cInput.value = parseFloat(cVal.toPrecision(12)).toString();
                }
            }
        }

        function hitungKadarSatuBaris(tr, Lb, Bb, Fb, Br, Ke, Kb, bmFactor, metodeKadar, Vetiket) {
            if (!tr || Lb <= 0 || Bb <= 0 || Fb <= 0 || Ke <= 0 || Kb <= 0) return 0;
            var fBM = (typeof bmFactor === 'number' && bmFactor > 0) ? bmFactor : 1.0;
            var mode = metodeKadar || 'tablet_br';
            var V_et = (typeof Vetiket === 'number' && Vetiket > 0) ? Vetiket : 5.0;

            var buEl = tr.querySelector('input[name*="[bobot_bersih]"]');
            var fuEl = tr.querySelector('input[name*="[faktor_pengenceran]"]');
            var luEl = tr.querySelector('input[name*="[respon_puncak]"], input[name*="[serapan]"]');

            var Bu = parseFloat(buEl ? buEl.value : 0) || 0;
            var Fu = parseFloat(fuEl ? fuEl.value : 1) || 1;
            var Lu = parseFloat(luEl ? luEl.value : 0) || 0;

            if (Lu <= 0) return 0;

            // Pastikan satuan bobot baku dalam mg jika penimbangan dalam gram (< 2.0 g)
            var Bb_mg = (Bb < 2.0) ? (Bb * 1000) : Bb;

            var kadar = 0;
            if (mode === 'tablet_cemplung') {
                // Rumus 2: Tablet Cemplung (1 tablet utuh direct dissolution)
                // Kadar = (Lu / Lb) * Bb_mg * (Fu / Fb) * (1 / Ke) * (Kb / 100) * FactorBM * 100%
                kadar = (Lu / Lb) * Bb_mg * (Fu / Fb) * (1.0 / Ke) * (Kb / 100) * fBM * 100;
            } else if (mode === 'sirup') {
                // Rumus 3: Sirup / Suspensi / Cairan Oral (Volume Vu dalam mL)
                if (Bu <= 0) return 0;
                kadar = (Lu / Lb) * (Bb_mg / Bu) * (Fu / Fb) * (V_et / Ke) * (Kb / 100) * fBM * 100;
            } else {
                // Rumus 1: Tablet / Kapsul BR (Metode 20 Tablet Digerus / Default)
                if (Bu <= 0 || Br <= 0) return 0;
                var Bu_mg = (Bu < 2.0) ? (Bu * 1000) : Bu;
                kadar = (Lu / Lb) * (Bb_mg / Bu_mg) * (Fu / Fb) * (Br / Ke) * (Kb / 100) * fBM * 100;
            }

            return isNaN(kadar) ? 0 : kadar;
        }

        // Listener reaktif HPLC & Penetapan Kadar: Real-time Live calculation tanpa perlu refresh!
        document.addEventListener('input', function(e) {
            if (!e.target || !e.target.name) return;
            var targetName = e.target.name;

            // Jika pengguna mengetik wadah_zat atau wadah_sisa, reset manualEdited pada bobot_bersih dan konsentrasi baris tsb
            if (targetName.indexOf('[wadah_zat]') !== -1 || targetName.indexOf('[wadah_sisa]') !== -1) {
                var tr = e.target.closest('tr');
                if (tr) {
                    var bbEl = tr.querySelector('input[name*="[bobot_bersih]"], input[name*="[bobot_zat]"]');
                    var cEl = tr.querySelector('input[name*="[konsentrasi]"]');
                    if (bbEl) delete bbEl.dataset.manualEdited;
                    if (cEl) delete cEl.dataset.manualEdited;
                }
                hitungHPLC();
                hitungSpektrofotometri();
                return;
            }

            // Jika pengguna mengedit bobot_bersih secara manual
            if (targetName.indexOf('[bobot_bersih]') !== -1 || targetName.indexOf('[bobot_zat]') !== -1) {
                e.target.dataset.manualEdited = "true";
                var tr = e.target.closest('tr');
                if (tr) {
                    var cEl = tr.querySelector('input[name*="[konsentrasi]"]');
                    if (cEl) delete cEl.dataset.manualEdited;
                }
                hitungHPLC();
                hitungSpektrofotometri();
                return;
            }

            // Jika pengguna mengedit konsentrasi secara manual
            if (targetName.indexOf('[konsentrasi]') !== -1) {
                e.target.dataset.manualEdited = "true";
                return;
            }

            // Jika pengguna mengedit faktor pengenceran
            if (targetName.indexOf('[faktor_pengenceran]') !== -1) {
                var tr = e.target.closest('tr');
                if (tr) {
                    var cEl = tr.querySelector('input[name*="[konsentrasi]"]');
                    if (cEl) delete cEl.dataset.manualEdited;
                }
                hitungHPLC();
                hitungSpektrofotometri();
                return;
            }

            // Semua field terkait penetapan kadar (baku, respon puncak, serapan, tablet BR, klaim etiket, BM, volume)
            if (targetName.indexOf('[respon_puncak]') !== -1 ||
                targetName.indexOf('[serapan]') !== -1 ||
                targetName.indexOf('[baku_kadar]') !== -1 ||
                targetName.indexOf('[baku_sp]') !== -1 ||
                targetName.indexOf('[kadar_baku_persen]') !== -1 ||
                targetName.indexOf('[bobot_rata_tablet]') !== -1 ||
                targetName.indexOf('[bobot_rata_rata_tablet]') !== -1 ||
                targetName.indexOf('[klaim_etiket]') !== -1 ||
                targetName.indexOf('[faktor_konversi_bm]') !== -1 ||
                targetName.indexOf('[volume_takaran_etiket]') !== -1 ||
                targetName.indexOf('[tabel_hplc]') !== -1 ||
                targetName.indexOf('[tabel_preparasi]') !== -1) {
                hitungHPLC();
                hitungSpektrofotometri();
            }
        });

        document.addEventListener('change', function(e) {
            if (!e.target) return;
            var targetName = e.target.name || '';
            if (targetName.indexOf('[tabel_hplc]') !== -1 ||
                targetName.indexOf('[tabel_preparasi]') !== -1 ||
                targetName.indexOf('[metode_perhitungan_kadar]') !== -1 ||
                e.target.matches('select[name*="[metode_perhitungan_kadar]"]')) {
                var pane = e.target.closest('.form-tab-pane-uji');
                if (pane) {
                    updateMetodeKadarUI(pane);
                }
                hitungHPLC();
                hitungSpektrofotometri();
            }
        });

        // Auto-Calculator Engine untuk Formulir GC-MS (KIM-09)
        // Menghitung: Bobot Bersih = Wadah+Zat - Wadah+Sisa
        // Rasio Ion: R1 = B/A, R2 = C/A, R3 = D/A
        // Input tetap dapat diedit manual dan tidak dikunci
        // =========================================================================
        function hitungGCMS() {
            var gcmsRows = document.querySelectorAll('tr:has(input[name*="[area_quantifier]"])');
            if (!gcmsRows || gcmsRows.length === 0) {
                var allA = document.querySelectorAll('input[name*="[area_quantifier]"]');
                allA.forEach(function(aEl) {
                    var tr = aEl.closest('tr');
                    if (tr) hitungBarisGCMS(tr);
                });
            } else {
                gcmsRows.forEach(function(row) {
                    hitungBarisGCMS(row);
                });
            }
        }

        function hitungBarisGCMS(tr) {
            // Bobot Bersih
            var wZatInput = tr.querySelector('input[name*="[wadah_zat]"]');
            var wSisaInput = tr.querySelector('input[name*="[wadah_sisa]"]');
            var bBersihInput = tr.querySelector('input[name*="[bobot_bersih]"]');

            if (wZatInput && wSisaInput && bBersihInput) {
                var wZat = parseFloat(wZatInput.value.replace(',', '.')) || 0;
                var wSisa = parseFloat(wSisaInput.value.replace(',', '.')) || 0;
                if (wZat > 0 && wSisa > 0 && wZat >= wSisa) {
                    bBersihInput.value = (wZat - wSisa).toFixed(4);
                }
            }

            // Rasio Ion Sesuai Rumus Penguji:
            // Intensitas (A) = area_qualifier_1
            // Qualifier (B)  = area_qualifier_2
            // Qualifier (C)  = area_qualifier_3
            // Rasio 1 (B/A) = Qualifier (B) / Intensitas (A) * 100
            // Rasio 2 (C/A) = Qualifier (C) / Intensitas (A) * 100
            var intAInput = tr.querySelector('input[name*="[area_qualifier_1]"]'); // Intensitas (A)
            var qualBInput = tr.querySelector('input[name*="[area_qualifier_2]"]'); // Qualifier (B)
            var qualCInput = tr.querySelector('input[name*="[area_qualifier_3]"]'); // Qualifier (C)

            var r1Input = tr.querySelector('input[name*="[rasio_ion_1]"]');
            var r2Input = tr.querySelector('input[name*="[rasio_ion_2]"]');
            var r3Input = tr.querySelector('input[name*="[rasio_ion_3]"]');

            var intA = parseFloat(intAInput ? intAInput.value.replace(',', '.') : 0) || 0;
            var qualB = parseFloat(qualBInput ? qualBInput.value.replace(',', '.') : 0) || 0;
            var qualC = parseFloat(qualCInput ? qualCInput.value.replace(',', '.') : 0) || 0;

            if (intA > 0) {
                if (r1Input && qualB > 0) r1Input.value = ((qualB / intA) * 100).toFixed(3);
                if (r2Input && qualC > 0) r2Input.value = ((qualC / intA) * 100).toFixed(3);
            }
        }

        // Listener GC-MS input & change secara real-time live
        document.addEventListener('input', function(e) {
            if (e.target && e.target.matches('input[name*="[tabel_gcms]"]')) {
                var tr = e.target.closest('tr');
                if (tr) hitungBarisGCMS(tr);
            }
        });
        document.addEventListener('change', function(e) {
            if (e.target && e.target.matches('input[name*="[tabel_gcms]"]')) {
                var tr = e.target.closest('tr');
                if (tr) hitungBarisGCMS(tr);
            }
        });

        // =========================================================================
        // Auto-Calculator Engine untuk Formulir Penetapan pH (KIM-11)
        // Rata-rata = (pH 1 + pH 2) / 2
        // =========================================================================
        function hitungPH() {
            var ph1Input = document.querySelector('input[name*="[ph_uji_1]"]');
            var ph2Input = document.querySelector('input[name*="[ph_uji_2]"]');
            var phRataInput = document.querySelector('input[name*="[ph_rata_rata]"]');

            if (ph1Input && ph2Input && phRataInput) {
                var p1 = parseFloat(ph1Input.value);
                var p2 = parseFloat(ph2Input.value);
                if (!isNaN(p1) && !isNaN(p2)) {
                    phRataInput.value = ((p1 + p2) / 2).toFixed(2);
                    var pPh = ph1Input ? ph1Input.closest('.form-tab-pane-uji') : null;
                    if (pPh) evaluasiKesimpulanPane(pPh);
                }
            }
        }

        // Listener pH input
        document.addEventListener('input', function(e) {
            if (e.target && (e.target.name && (e.target.name.indexOf('[ph_uji_1]') !== -1 || e.target.name.indexOf('[ph_uji_2]') !== -1))) {
                hitungPH();
            }
        });

        // =========================================================================
        // Auto-Calculator Engine untuk Formulir Penetapan Bobot Jenis (KIM-12)
        // Bobot Zat = W1 - W0, Bobot Air = W2 - W0, BJ = (W1 - W0) / (W2 - W0)
        // =========================================================================
        function hitungBobotJenis() {
            var panes = document.querySelectorAll('.form-tab-pane-uji');
            panes.forEach(function(pane) {
                var kodeTpl = (pane.getAttribute('data-kode-template') || '').toUpperCase();
                var namaTpl = (pane.getAttribute('data-nama-template') || '').toLowerCase();
                var paramTpl = (pane.getAttribute('data-parameter') || '').toLowerCase();
                if (kodeTpl === 'KIM-12' || namaTpl.indexOf('bobot jenis') !== -1 || paramTpl.indexOf('bobot jenis') !== -1 || pane.querySelector('input[name*="[tabel_penimbangan]"]')) {
                    hitungBobotJenisPane(pane);
                }
            });
        }

        function hitungBobotJenisPane(pane) {
            var w = [null, null, null]; // W0, W1, W2

            for (var i = 0; i < 3; i++) {
                var b1Input = pane.querySelector('input[name*="[tabel_penimbangan][' + i + '][bobot_1]"]');
                var b2Input = pane.querySelector('input[name*="[tabel_penimbangan][' + i + '][bobot_2]"]');
                var rataInput = pane.querySelector('input[name*="[tabel_penimbangan][' + i + '][rata_rata]"]');

                if (b1Input) {
                    var v1 = parseFloat(b1Input.value.replace(',', '.'));
                    var v2 = b2Input && b2Input.value.trim() !== '' ? parseFloat(b2Input.value.replace(',', '.')) : NaN;

                    // Hitung rata-rata otomatis jika kedua bobot atau salah satunya diisi
                    if (!isNaN(v1) && !isNaN(v2)) {
                        var mean = (v1 + v2) / 2;
                        // Tanpa pembulatan fixed, tampilkan presisi floating number murni
                        if (rataInput) rataInput.value = mean.toString();
                        w[i] = mean;
                    } else if (!isNaN(v1)) {
                        if (rataInput) rataInput.value = v1.toString();
                        w[i] = v1;
                    } else if (rataInput && rataInput.value.trim() !== '') {
                        // Jika analis mengedit nilai rata-rata manual secara langsung
                        var vRataManual = parseFloat(rataInput.value.replace(',', '.'));
                        if (!isNaN(vRataManual)) {
                            w[i] = vRataManual;
                        }
                    }
                } else if (rataInput && rataInput.value.trim() !== '') {
                    var vRataManual2 = parseFloat(rataInput.value.replace(',', '.'));
                    if (!isNaN(vRataManual2)) {
                        w[i] = vRataManual2;
                    }
                }
            }

            var w0 = w[0];
            var w1 = w[1];
            var w2 = w[2];

            var zatEl = pane.querySelector('input[name*="[bobot_zat_bersih]"]');
            var airEl = pane.querySelector('input[name*="[bobot_air_bersih]"]');
            var bjEl = pane.querySelector('input[name*="[nilai_bobot_jenis]"]');

            if (w0 !== null && w1 !== null) {
                var bZat = w1 - w0;
                if (zatEl) zatEl.value = bZat.toString();
            }

            if (w0 !== null && w2 !== null) {
                var bAir = w2 - w0;
                if (airEl) airEl.value = bAir.toString();
            }

            if (w0 !== null && w1 !== null && w2 !== null) {
                var bZatFinal = w1 - w0;
                var bAirFinal = w2 - w0;
                // Rumus Bobot Jenis (BJ) = (W2 - W0) / (W1 - W0)
                if (bZatFinal > 0 && bjEl) {
                    bjEl.value = (bAirFinal / bZatFinal).toString();
                    evaluasiKesimpulanPane(pane);
                }
            }
        }

        // Listener Bobot Jenis input & change secara live real-time
        document.addEventListener('input', function(e) {
            if (e.target && e.target.matches('input[name*="[tabel_penimbangan]"], input[name*="[bobot_zat_bersih]"], input[name*="[bobot_air_bersih]"], input[name*="[nilai_bobot_jenis]"]')) {
                var pane = e.target.closest('.form-tab-pane-uji');
                if (pane) {
                    hitungBobotJenisPane(pane);
                } else {
                    hitungBobotJenis();
                }
            }
        });
        document.addEventListener('change', function(e) {
            if (e.target && e.target.matches('input[name*="[tabel_penimbangan]"]')) {
                var pane = e.target.closest('.form-tab-pane-uji');
                if (pane) {
                    hitungBobotJenisPane(pane);
                } else {
                    hitungBobotJenis();
                }
            }
        });

                // =========================================================================
        // Auto-Calculator Engine untuk Formulir Mikrobiologi (MIK-01 s/d MIK-06)
        // Replikasi presisi 100% SOP & Template Resmi BPOM
        // =========================================================================

        // --- 1. MIK-01: Uji Angka Lempeng Total (ALT) Sediaan Farmasi ---
        function hitungMikroALT(pane) {
            if (!pane) return;
            var rows = pane.querySelectorAll('tr:has(input[name*="[cawan_a]"])');
            var validRow = null;

            rows.forEach(function(tr) {
                var cA = tr.querySelector('input[name*="[cawan_a]"]');
                var cB = tr.querySelector('input[name*="[cawan_b]"]');
                var cTot = tr.querySelector('input[name*="[total_koloni]"]');
                var cPeng = tr.querySelector('input[name*="[pengenceran]"]');

                if (cA && cB) {
                    var vA = parseFloat(cA.value);
                    var vB = parseFloat(cB.value);
                    if (!isNaN(vA) && !isNaN(vB)) {
                        var tot = vA + vB;
                        if (cTot) cTot.value = tot;
                        if (tot > 0 && !validRow) {
                            validRow = { vA: vA, vB: vB, tot: tot, peng: cPeng ? cPeng.value : '' };
                        }
                    } else if (!isNaN(vA)) {
                        if (cTot) cTot.value = vA;
                        if (!validRow) validRow = { vA: vA, vB: 0, tot: vA, peng: cPeng ? cPeng.value : '' };
                    }
                }
            });

            var rAtas = pane.querySelector('input[name*="[perhitungan_alt_rumus_atas]"]');
            var rBawah = pane.querySelector('input[name*="[perhitungan_alt_rumus_bawah]"]');
            var hAngka = pane.querySelector('input[name*="[hasil_alt_angka]"]');

            if (validRow) {
                var pengStr = validRow.peng || '10^-2';
                var match = pengStr.match(/10\^?-?(\d+)/);
                var exp = match ? parseInt(match[1]) : 2;
                var factor = Math.pow(10, exp);
                var avg = validRow.tot / 2;
                var cfu = avg * factor;

                if (rAtas) rAtas.value = '(' + validRow.vA + ' + ' + validRow.vB + ') x 10^' + exp;
                if (rBawah) rBawah.value = '2';
                if (hAngka) {
                    var sc = cfu.toExponential(1);
                    var parts = sc.split('e+');
                    var formatted = parts.length === 2 ? parts[0].replace('.', ',') + ' x 10^' + parts[1] : cfu;
                    hAngka.value = formatted;
                }
            }
        }

        // --- 2. MIK-02: Uji Angka Kapang Khamir (AKK) Sediaan Farmasi ---
        function hitungMikroAKK(pane) {
            if (!pane) return;
            var rows = pane.querySelectorAll('tr:has(input[name*="[cawan_a]"])');
            var validRow = null;

            rows.forEach(function(tr) {
                var cA = tr.querySelector('input[name*="[cawan_a]"]');
                var cB = tr.querySelector('input[name*="[cawan_b]"]');
                var cTot = tr.querySelector('input[name*="[total_koloni]"]');
                var cPeng = tr.querySelector('input[name*="[pengenceran]"]');

                if (cA && cB) {
                    var vA = parseFloat(cA.value);
                    var vB = parseFloat(cB.value);
                    if (!isNaN(vA) && !isNaN(vB)) {
                        var tot = vA + vB;
                        if (cTot) cTot.value = tot;
                        if (tot > 0 && !validRow) {
                            validRow = { vA: vA, vB: vB, tot: tot, peng: cPeng ? cPeng.value : '' };
                        }
                    } else if (!isNaN(vA)) {
                        if (cTot) cTot.value = vA;
                        if (!validRow) validRow = { vA: vA, vB: 0, tot: vA, peng: cPeng ? cPeng.value : '' };
                    }
                }
            });

            var rAtas = pane.querySelector('input[name*="[perhitungan_akk_rumus_atas]"]');
            var rBawah = pane.querySelector('input[name*="[perhitungan_akk_rumus_bawah]"]');
            var hAngka = pane.querySelector('input[name*="[hasil_akk_angka]"]');

            if (validRow) {
                var pengStr = validRow.peng || '10^-1';
                var match = pengStr.match(/10\^?-?(\d+)/);
                var exp = match ? parseInt(match[1]) : 1;
                var factor = Math.pow(10, exp);
                var avg = validRow.tot / 2;
                var cfu = avg * factor;

                if (rAtas) rAtas.value = '(' + validRow.vA + ' + ' + validRow.vB + ') x 10^' + exp;
                if (rBawah) rBawah.value = '2';
                if (hAngka) {
                    var sc = cfu.toExponential(1);
                    var parts = sc.split('e+');
                    var formatted = parts.length === 2 ? parts[0].replace('.', ',') + ' x 10^' + parts[1] : cfu;
                    hAngka.value = formatted;
                }
            }
        }

        // --- 3. MIK-03: Potensi Antibiotika 5 Dosis ---
        function hitungMikroPotensi(pane) {
            if (!pane) return;
            var calcRsdField = function(sdId, avgId, rsdId, statusId) {
                var sdInp = pane.querySelector('input[name*="[' + sdId + ']"]');
                var avgInp = pane.querySelector('input[name*="[' + avgId + ']"]');
                var rsdInp = pane.querySelector('input[name*="[' + rsdId + ']"]');
                var statSel = pane.querySelector('select[name*="[' + statusId + ']"]');

                if (sdInp && avgInp && rsdInp) {
                    var sd = parseFloat(sdInp.value);
                    var avg = parseFloat(avgInp.value);
                    if (!isNaN(sd) && !isNaN(avg) && avg > 0) {
                        var rsd = (sd / avg) * 100;
                        rsdInp.value = rsd.toFixed(2);
                        if (statSel) {
                            statSel.value = rsd <= 10.0 ? 'Memenuhi' : 'Tidak Memenuhi';
                        }
                    }
                }
            };

            calcRsdField('sd_s1', 'avg_s1', 'rsd_s1_nilai', 'rsd_s1_status');
            calcRsdField('sd_s2', 'avg_s2', 'rsd_s2_nilai', 'rsd_s2_status');
            calcRsdField('sd_s4', 'avg_s4', 'rsd_s4_nilai', 'rsd_s4_status');
            calcRsdField('sd_s5', 'avg_s5', 'rsd_s5_nilai', 'rsd_s5_status');
            calcRsdField('sd_uji', 'avg_uji', 'rsd_uji_nilai', 'rsd_uji_status');

            calcRsdField('sd_tengah_s1', 'avg_tengah_s1', 'rsd_tengah_s1_nilai');
            calcRsdField('sd_tengah_s2', 'avg_tengah_s2', 'rsd_tengah_s2_nilai');
            calcRsdField('sd_tengah_s4', 'avg_tengah_s4', 'rsd_tengah_s4_nilai');
            calcRsdField('sd_tengah_s5', 'avg_tengah_s5', 'rsd_tengah_s5_nilai');
            calcRsdField('sd_tengah_uji', 'avg_tengah_uji', 'rsd_tengah_uji_nilai');

            var rInp = pane.querySelector('input[name*="[kurva_r]"]');
            var r2Inp = pane.querySelector('input[name*="[kurva_r2]"]');
            var pr2Inp = pane.querySelector('input[name*="[kurva_persen_r2]"]');
            var kurvaStat = pane.querySelector('select[name*="[kurva_status]"]');

            var r = parseFloat(rInp ? rInp.value : NaN);
            var r2 = parseFloat(r2Inp ? r2Inp.value : NaN);

            if (!isNaN(r) && isNaN(r2)) {
                r2 = Math.pow(r, 2);
                if (r2Inp) r2Inp.value = r2.toFixed(4);
            }
            if (!isNaN(r2)) {
                var pr2 = r2 * 100;
                if (pr2Inp) pr2Inp.value = pr2.toFixed(2);
                if (kurvaStat) {
                    kurvaStat.value = pr2 >= 95.0 ? 'Memenuhi' : 'Tidak Memenuhi';
                }
            }
        }

        // --- 4. MIK-05: Uji Endotoksin Bakteri Semi Kuantitatif ---
        function hitungMikroEndotoksin(pane) {
            if (!pane) return;
            var potCse = parseFloat((pane.querySelector('input[name*="[potensi_cse]"]') || {}).value);
            var lam = parseFloat((pane.querySelector('input[name*="[kepekaan_lambda]"]') || {}).value);
            var konvCse = pane.querySelector('input[name*="[konversi_potensi_cse]"]');

            if (!isNaN(potCse) && !isNaN(lam) && lam > 0 && konvCse) {
                konvCse.value = (potCse / lam).toFixed(2) + ' λ';
            }

            var batas = parseFloat((pane.querySelector('input[name*="[batas_endotoksin]"]') || {}).value);
            var kons = parseFloat((pane.querySelector('input[name*="[konsentrasi_larutan_sampel]"]') || {}).value);
            if (isNaN(kons) || kons <= 0) kons = 1;
            var pmaInp = pane.querySelector('input[name*="[nilai_pma]"]');

            if (!isNaN(batas) && !isNaN(lam) && lam > 0 && pmaInp) {
                var pma = (batas * kons) / lam;
                pmaInp.value = pma.toFixed(2);
            }

            var f1 = parseFloat((pane.querySelector('input[name*="[kadar_endotoksin_f1]"]') || {}).value);
            var f2 = parseFloat((pane.querySelector('input[name*="[kadar_endotoksin_f2]"]') || {}).value);
            var rataInp = pane.querySelector('input[name*="[kadar_endotoksin_rata]"]');
            var euMlInp = pane.querySelector('input[name*="[kadar_endotoksin_eu_ml]"]');
            var konvEInp = pane.querySelector('input[name*="[konversi_e_val]"]');

            var rata = null;
            if (!isNaN(f1) && !isNaN(f2)) {
                rata = (f1 + f2) / 2;
            } else if (!isNaN(f1)) {
                rata = f1;
            }

            if (rata !== null) {
                if (rataInp) rataInp.value = rata.toFixed(4);
                if (euMlInp && !euMlInp.value) euMlInp.value = '< ' + rata.toFixed(4);
                if (konvEInp && !konvEInp.value) konvEInp.value = rata.toFixed(4);
            }

            var eVal = parseFloat((konvEInp ? konvEInp.value : '') || (rataInp ? rataInp.value : ''));
            var potVal = parseFloat((pane.querySelector('input[name*="[konversi_potensi_val]"]') || {}).value);
            var konvHasilInp = pane.querySelector('input[name*="[konversi_hasil_angka]"]');
            var konvKadarInp = pane.querySelector('input[name*="[kadar_endotoksin_konversi]"]');

            if (!isNaN(eVal) && !isNaN(potVal) && potVal > 0) {
                var hasilKonv = (eVal / potVal).toFixed(4);
                if (konvHasilInp) konvHasilInp.value = hasilKonv;
                if (konvKadarInp) konvKadarInp.value = '< ' + hasilKonv;
            }
        }

        // --- 5. MIK-06: Konfirmasi Kepekaan LAL Semi Kuantitatif ---
        function hitungMikroKepekaanLAL(pane) {
            if (!pane) return;
            var lam = parseFloat((pane.querySelector('input[name*="[kepekaan_etiket_lambda]"]') || {}).value);
            var logE = [];

            for (var i = 1; i <= 4; i++) {
                var taInp = pane.querySelector('input[name*="[titik_akhir_' + i + ']"]');
                var leInp = pane.querySelector('input[name*="[log_e_' + i + ']"]');

                if (taInp && (!taInp.value || isNaN(parseFloat(taInp.value))) && !isNaN(lam) && lam > 0) {
                    var r2 = (pane.querySelector('select[name*="[cse_2lambda_' + i + ']"]') || {}).value;
                    var r1 = (pane.querySelector('select[name*="[cse_lambda_' + i + ']"]') || {}).value;
                    var rh = (pane.querySelector('select[name*="[cse_half_lambda_' + i + ']"]') || {}).value;
                    var rq = (pane.querySelector('select[name*="[cse_quarter_lambda_' + i + ']"]') || {}).value;

                    if (rq === '+') taInp.value = (0.25 * lam).toFixed(4);
                    else if (rh === '+') taInp.value = (0.5 * lam).toFixed(4);
                    else if (r1 === '+') taInp.value = (1.0 * lam).toFixed(4);
                    else if (r2 === '+') taInp.value = (2.0 * lam).toFixed(4);
                }

                if (taInp) {
                    var ta = parseFloat(taInp.value);
                    if (!isNaN(ta) && ta > 0) {
                        var eVal = Math.log10(ta);
                        if (leInp) leInp.value = eVal.toFixed(4);
                        logE.push(eVal);
                    }
                }
            }

            var rataEInp = pane.querySelector('input[name*="[rata_rata_e]"]');
            var gmInp = pane.querySelector('input[name*="[nilai_gm]"]');
            var rentangInp = pane.querySelector('input[name*="[rentang_syarat_angka]"]');

            if (logE.length === 4) {
                var sum = logE.reduce(function(a, b) { return a + b; }, 0);
                var avgE = sum / 4;
                var gm = Math.pow(10, avgE);

                if (rataEInp) rataEInp.value = avgE.toFixed(4);
                if (gmInp) gmInp.value = gm.toFixed(4);

                if (!isNaN(lam) && lam > 0 && rentangInp) {
                    var minL = (0.5 * lam).toFixed(4);
                    var maxL = (2.0 * lam).toFixed(4);
                    rentangInp.value = minL + ' ≤ ' + gm.toFixed(4) + ' ≤ ' + maxL;
                }
            }
        }

        // Global Event Listeners untuk Formulir Mikrobiologi
        document.addEventListener('input', function(e) {
            var pane = e.target.closest('.form-tab-pane-uji');
            if (!pane) return;
            var kodeTpl = (pane.getAttribute('data-kode-template') || '').toUpperCase();
            var targetName = e.target.name || '';

            if (kodeTpl === 'MIK-01' || targetName.indexOf('[perhitungan_alt') !== -1 || targetName.indexOf('[cawan_') !== -1) {
                hitungMikroALT(pane);
            }
            if (kodeTpl === 'MIK-02' || targetName.indexOf('[perhitungan_akk') !== -1 || targetName.indexOf('[cawan_') !== -1) {
                hitungMikroAKK(pane);
            }
            if (kodeTpl === 'MIK-03' || targetName.indexOf('[rsd_') !== -1 || targetName.indexOf('[kurva_') !== -1 || targetName.indexOf('[sd_') !== -1 || targetName.indexOf('[avg_') !== -1) {
                hitungMikroPotensi(pane);
            }
            if (kodeTpl === 'MIK-05' || targetName.indexOf('[kadar_endotoksin') !== -1 || targetName.indexOf('[konversi_') !== -1 || targetName.indexOf('[batas_endotoksin]') !== -1 || targetName.indexOf('[potensi_cse]') !== -1) {
                hitungMikroEndotoksin(pane);
            }
            if (kodeTpl === 'MIK-06' || targetName.indexOf('[titik_akhir') !== -1 || targetName.indexOf('[kepekaan_etiket') !== -1) {
                hitungMikroKepekaanLAL(pane);
            }
        });

        document.addEventListener('change', function(e) {
            var pane = e.target.closest('.form-tab-pane-uji');
            if (!pane) return;
            var kodeTpl = (pane.getAttribute('data-kode-template') || '').toUpperCase();
            var targetName = e.target.name || '';

            if (kodeTpl === 'MIK-06' || targetName.indexOf('[cse_') !== -1) {
                hitungMikroKepekaanLAL(pane);
            }
            if (kodeTpl === 'MIK-05') {
                hitungMikroEndotoksin(pane);
            }
            if (kodeTpl === 'MIK-03') {
                hitungMikroPotensi(pane);
            }
        });

        // Inisialisasi awal saat halaman dimuat
        document.querySelectorAll('.form-tab-pane-uji').forEach(function(pane) {
            var kodeTpl = (pane.getAttribute('data-kode-template') || '').toUpperCase();
            if (kodeTpl === 'MIK-01') hitungMikroALT(pane);
            if (kodeTpl === 'MIK-02') hitungMikroAKK(pane);
            if (kodeTpl === 'MIK-03') hitungMikroPotensi(pane);
            if (kodeTpl === 'MIK-05') hitungMikroEndotoksin(pane);
            if (kodeTpl === 'MIK-06') hitungMikroKepekaanLAL(pane);
        });

        // =========================================================================
        // Auto-Calculator Engine untuk Tabel Pengamatan Koloni (ALT & AKK)
        // Menghitung Total Koloni = 1 ml (A) + 1 ml (B)
        // Dan Rata-rata Spike Mikroba (A + B) / 2
        // =========================================================================
        function hitungRumusAKK(pane) {
            if (!pane) return;
            // Pastikan ini formulir AKK (MIK-02)
            var pembilangInput = pane.querySelector('input[name*="[perhitungan_akk_rumus_atas]"]');
            var penyebutInput  = pane.querySelector('input[name*="[perhitungan_akk_rumus_bawah]"]');
            var hasilInput     = pane.querySelector('input[name*="[hasil_akk_angka]"]');

            if (!pembilangInput) return; // Bukan form AKK

            // Cari semua baris tabel koloni pada pane ini
            var rows = pane.querySelectorAll('table tbody tr');
            var selectedDilution = null;
            var selectedA = null;
            var selectedB = null;
            var selectedFactor = null;

            // Aturan AKK: cari baris pengenceran di mana nilai per cawannya (A dan B) masing-masing < 50
            // Urut dari 10^-1, 10^-2, 10^-3, dst.
            rows.forEach(function(row) {
                if (selectedDilution !== null) return; // sudah ketemu baris yang memenuhi syarat

                var pengenceranInput = row.querySelector('input[name*="[pengenceran]"]');
                var inputA = row.querySelector('input[name*="[cawan_a]"]');
                var inputB = row.querySelector('input[name*="[cawan_b]"]');

                if (pengenceranInput && inputA && inputB) {
                    var pengenceranStr = pengenceranInput.value.trim();
                    var valAStr = inputA.value.trim().replace(',', '.');
                    var valBStr = inputB.value.trim().replace(',', '.');

                    if (valAStr !== '' && valBStr !== '') {
                        var numA = parseFloat(valAStr);
                        var numB = parseFloat(valBStr);

                        if (!isNaN(numA) && !isNaN(numB)) {
                            // Cek syarat AKK: masing-masing nilai A < 50 dan B < 50
                            if (numA < 50 && numB < 50) {
                                selectedA = numA;
                                selectedB = numB;
                                selectedDilution = pengenceranStr;

                                // Cari faktor eksponen pengenceran, misal 10^-1 -> 10^1, 10^-2 -> 10^2
                                var matchExp = pengenceranStr.match(/10\^[-–]?(\d+)/);
                                if (matchExp) {
                                    selectedFactor = matchExp[1]; // misal '1', '2', '3'
                                } else {
                                    selectedFactor = '1';
                                }
                            }
                        }
                    }
                }
            });

            if (selectedDilution !== null && selectedA !== null && selectedB !== null) {
                // Susun Rumus Pembilang: (A+B) x 10^n
                var strPembilang = '(' + selectedA + '+' + selectedB + ') x 10^' + selectedFactor;
                pembilangInput.value = strPembilang;

                if (penyebutInput && (!penyebutInput.value || penyebutInput.value === '')) {
                    penyebutInput.value = '2';
                }

                // Hitung Hasil AKK: nilai total koloni sebenarnya = rata * 10^factor
                var rata = (selectedA + selectedB) / 2;
                var totalAktual = rata * Math.pow(10, parseInt(selectedFactor, 10));
                
                // Format selalu dinormalisasi ke pangkat 10^2: nilai / 100
                var nilaiBasis102 = totalAktual / 100;
                
                // Rapikan tampilan desimal (maksimal 2 angka di belakang koma tanpa trailing zero berlebih)
                var rounded102 = Math.round(nilaiBasis102 * 100) / 100;
                var strHasil102 = String(rounded102).replace('.', ',');
                
                var strHasil = strHasil102 + ' x 10^2';
                if (hasilInput) {
                    hasilInput.value = strHasil;
                    hasilInput.dispatchEvent(new Event('change', { bubbles: true }));
                }

                pembilangInput.dispatchEvent(new Event('change', { bubbles: true }));
            }
        }

        function hitungBarisKoloni(tr) {
            if (!tr) return;
            var inputA = tr.querySelector('input[name*="[cawan_a]"]');
            var inputB = tr.querySelector('input[name*="[cawan_b]"]');
            var inputTotal = tr.querySelector('input[name*="[total_koloni]"]');

            if (inputA && inputB && inputTotal) {
                var valA = inputA.value.trim();
                var valB = inputB.value.trim();

                if (valA !== '' && valB !== '') {
                    var numA = parseFloat(valA.replace(',', '.'));
                    var numB = parseFloat(valB.replace(',', '.'));

                    if (!isNaN(numA) && !isNaN(numB)) {
                        inputTotal.value = (numA + numB);
                        // Trigger input event pada inputTotal agar terdeteksi autosave
                        inputTotal.dispatchEvent(new Event('change', { bubbles: true }));
                    }
                } else if (valA !== '' && valB === '') {
                    var numA = parseFloat(valA.replace(',', '.'));
                    if (!isNaN(numA)) {
                        inputTotal.value = numA;
                    }
                } else if (valA === '' && valB !== '') {
                    var numB = parseFloat(valB.replace(',', '.'));
                    if (!isNaN(numB)) {
                        inputTotal.value = numB;
                    }
                }
            }
        }

        // Auto hitung rata-rata spike mikroba kesesuaian metode (Candida, Aspergillus, dll)
        function hitungSpikeRata(inputElem) {
            var name = inputElem.name || '';
            var pane = inputElem.closest('.form-tab-pane-uji') || document;
            
            // Cek apakah Candida
            if (name.indexOf('[spike_candida_a]') !== -1 || name.indexOf('[spike_candida_b]') !== -1) {
                var a = pane.querySelector('input[name*="[spike_candida_a]"]');
                var b = pane.querySelector('input[name*="[spike_candida_b]"]');
                var r = pane.querySelector('input[name*="[spike_candida_rata]"]');
                if (a && b && r) {
                    var na = parseFloat(a.value.replace(',', '.'));
                    var nb = parseFloat(b.value.replace(',', '.'));
                    if (!isNaN(na) && !isNaN(nb)) r.value = ((na + nb) / 2);
                }
            }
            // Cek apakah Aspergillus
            if (name.indexOf('[spike_aspergillus_a]') !== -1 || name.indexOf('[spike_aspergillus_b]') !== -1) {
                var a = pane.querySelector('input[name*="[spike_aspergillus_a]"]');
                var b = pane.querySelector('input[name*="[spike_aspergillus_b]"]');
                var r = pane.querySelector('input[name*="[spike_aspergillus_rata]"]');
                if (a && b && r) {
                    var na = parseFloat(a.value.replace(',', '.'));
                    var nb = parseFloat(b.value.replace(',', '.'));
                    if (!isNaN(na) && !isNaN(nb)) r.value = ((na + nb) / 2);
                }
            }
            // Cek ALT: S. aureus
            if (name.indexOf('[spike_saureus_a]') !== -1 || name.indexOf('[spike_saureus_b]') !== -1) {
                var a = pane.querySelector('input[name*="[spike_saureus_a]"]');
                var b = pane.querySelector('input[name*="[spike_saureus_b]"]');
                var r = pane.querySelector('input[name*="[spike_saureus_rata]"]');
                if (a && b && r) {
                    var na = parseFloat(a.value.replace(',', '.'));
                    var nb = parseFloat(b.value.replace(',', '.'));
                    if (!isNaN(na) && !isNaN(nb)) r.value = ((na + nb) / 2);
                }
            }
            // Cek ALT: P. aeruginosa
            if (name.indexOf('[spike_paeruginosa_a]') !== -1 || name.indexOf('[spike_paeruginosa_b]') !== -1) {
                var a = pane.querySelector('input[name*="[spike_paeruginosa_a]"]');
                var b = pane.querySelector('input[name*="[spike_paeruginosa_b]"]');
                var r = pane.querySelector('input[name*="[spike_paeruginosa_rata]"]');
                if (a && b && r) {
                    var na = parseFloat(a.value.replace(',', '.'));
                    var nb = parseFloat(b.value.replace(',', '.'));
                    if (!isNaN(na) && !isNaN(nb)) r.value = ((na + nb) / 2);
                }
            }
            // Cek ALT: E. coli
            if (name.indexOf('[spike_ecoli_a]') !== -1 || name.indexOf('[spike_ecoli_b]') !== -1) {
                var a = pane.querySelector('input[name*="[spike_ecoli_a]"]');
                var b = pane.querySelector('input[name*="[spike_ecoli_b]"]');
                var r = pane.querySelector('input[name*="[spike_ecoli_rata]"]');
                if (a && b && r) {
                    var na = parseFloat(a.value.replace(',', '.'));
                    var nb = parseFloat(b.value.replace(',', '.'));
                    if (!isNaN(na) && !isNaN(nb)) r.value = ((na + nb) / 2);
                }
            }
        }

        // Listener otomatis untuk input tabel koloni dan spike
        document.addEventListener('input', function(e) {
            if (!e.target || !e.target.name) return;
            var name = e.target.name;
            if (name.indexOf('[cawan_a]') !== -1 || name.indexOf('[cawan_b]') !== -1) {
                var tr = e.target.closest('tr');
                if (tr) {
                    hitungBarisKoloni(tr);
                    var pane = tr.closest('.form-tab-pane-uji') || document;
                    hitungRumusAKK(pane);
                }
            }
            if (name.indexOf('[spike_') !== -1) {
                hitungSpikeRata(e.target);
            }
        });

        // =========================================================================
        // FITUR SIMPAN SEMENTARA (DRAFT) & AUTO-SAVE PROGRES PENGUJIAN
        // Menjaga data tidak hilang saat gangguan sinyal, timeout, atau jeda kerja
        // =========================================================================
        var formProses = document.getElementById('formProsesSesi');
        var btnSimpanSementara = document.getElementById('btnSimpanSementara');
        var btnDraftText = document.getElementById('btnDraftText');
        var inputAksiSimpan = document.getElementById('inputAksiSimpan');
        var headerDraftIndicator = document.getElementById('headerDraftIndicator');
        var headerDraftText = document.getElementById('headerDraftText');
        var autoSaveTimer = null;
        var isSavingDraft = false;

        function updateDraftIndicator(status, message) {
            if (!headerDraftIndicator) return;
            headerDraftIndicator.classList.remove('saving', 'saved');
            if (status === 'saving') {
                headerDraftIndicator.classList.add('saving');
                headerDraftIndicator.innerHTML = '<span class="spinner-border spinner-border-sm text-primary" style="width:0.75rem;height:0.75rem;"></span> Menyimpan draft...';
            } else if (status === 'saved') {
                headerDraftIndicator.classList.add('saved');
                headerDraftIndicator.innerHTML = '<i class="bi bi-check-circle-fill text-success"></i> ' + (message || 'Tersimpan sementara');
            } else if (status === 'error') {
                headerDraftIndicator.innerHTML = '<i class="bi bi-exclamation-triangle text-danger"></i> Gagal simpan draft';
            }
        }

        function lakukanSimpanSementara(isAutoSave) {
            if (isSavingDraft || !formProses) return;
            isSavingDraft = true;

            if (btnSimpanSementara && !isAutoSave) {
                btnSimpanSementara.disabled = true;
                btnDraftText.innerHTML = '<span class="spinner-border spinner-border-sm me-1" style="width:0.8rem;height:0.8rem;"></span> Menyimpan...';
            }
            updateDraftIndicator('saving');

            function getCookie(name) {
                var value = "; " + document.cookie;
                var parts = value.split("; " + name + "=");
                if (parts.length === 2) return parts.pop().split(";").shift();
                return null;
            }

            var csrfCookieName = '<?php echo $this->config->item("csrf_cookie_name"); ?>';
            var currentCsrf = getCookie(csrfCookieName);
            var csrfInput = document.getElementById('limskuCsrfToken');
            if (currentCsrf && csrfInput) {
                csrfInput.value = currentCsrf;
            }

            var formData = new FormData(formProses);
            formData.set('aksi_simpan', 'draft');
            if (currentCsrf) {
                formData.set('<?php echo $this->security->get_csrf_token_name(); ?>', currentCsrf);
            }

            fetch(formProses.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(function(res) {
                if (!res.ok && res.status === 403) {
                    throw new Error('CSRF_ERROR');
                }
                return res.json().catch(function() {
                    return { status: res.ok ? 'success' : 'error' };
                });
            })
            .then(function(data) {
                isSavingDraft = false;
                if (btnSimpanSementara && !isAutoSave) {
                    btnSimpanSementara.disabled = false;
                    btnDraftText.innerHTML = 'Simpan Sementara (Draft)';
                }

                if (data.csrf_hash && csrfInput) {
                    csrfInput.value = data.csrf_hash;
                }

                if (data.status === 'success') {
                    var waktuStr = data.waktu ? ('Tersimpan (' + data.waktu.split(' ')[1] + ' WIB)') : 'Tersimpan sementara';
                    updateDraftIndicator('saved', waktuStr);
                    if (!isAutoSave) {
                        alert('Progres seluruh form pengujian berhasil disimpan sementara.\nData Anda aman dan dapat dilanjutkan kembali kapan saja di menu Antrean Pengujian.');
                    }
                } else {
                    updateDraftIndicator('error');
                    if (!isAutoSave) {
                        alert('Gagal menyimpan sementara: ' + (data.message || 'Terjadi kesalahan sistem.'));
                    }
                }
            })
            .catch(function(err) {
                isSavingDraft = false;
                if (btnSimpanSementara && !isAutoSave) {
                    btnSimpanSementara.disabled = false;
                    btnDraftText.innerHTML = 'Simpan Sementara (Draft)';
                }
                updateDraftIndicator('error');
                if (!isAutoSave) {
                    if (err.message === 'CSRF_ERROR') {
                        alert('Sesi keamanan CSRF kedaluwarsa. Halaman akan dimuat ulang untuk memperbarui token keamanan.');
                        location.reload();
                    } else {
                        alert('Gagal menyimpan sementara. Periksa koneksi internet Anda atau coba beberapa saat lagi.');
                    }
                }
            });
        }

        if (btnSimpanSementara) {
            btnSimpanSementara.addEventListener('click', function(e) {
                e.preventDefault();
                lakukanSimpanSementara(false);
            });
        }

        // Auto-save debounced saat penguji mengetik input form (setelah 2.5 detik idle)
        formProses.addEventListener('input', function(e) {
            if (e.target && (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA' || e.target.tagName === 'SELECT')) {
                if (headerDraftText) {
                    headerDraftText.textContent = 'Ada perubahan belum disimpan...';
                    if (headerDraftIndicator) headerDraftIndicator.classList.remove('saved');
                }
                clearTimeout(autoSaveTimer);
                autoSaveTimer = setTimeout(function() {
                    lakukanSimpanSementara(true);
                }, 2500);
            }
        });

        // Trigger change pada instrumen alat / reagen juga memicu auto-save
        formProses.addEventListener('change', function(e) {
            if (e.target && (e.target.tagName === 'SELECT')) {
                clearTimeout(autoSaveTimer);
                autoSaveTimer = setTimeout(function() {
                    lakukanSimpanSementara(true);
                }, 1500);
            }
        });

        // Listener ketika analis langsung mengganti Form LCP pada kartu parameter
        document.querySelectorAll('.select-ganti-lcp-direct').forEach(function(selectEl) {
            selectEl.addEventListener('change', function() {
                var newTplText = selectEl.options[selectEl.selectedIndex].text;
                if (confirm('Anda memilih untuk mengganti Form LCP parameter ini ke "' + newTplText + '".\nSistem akan menyimpan sementara progres inputan Anda saat ini dan memuat formulir LCP yang baru. Lanjutkan?')) {
                    // Simpan draft terlebih dahulu, lalu reload form untuk memuat skema template yang baru
                    updateDraftIndicator('saving');
                    var formData = new FormData(formProses);
                    formData.set('aksi_simpan', 'draft');

                    fetch(formProses.action, {
                        method: 'POST',
                        headers: { 'X-Requested-With': 'XMLHttpRequest' },
                        body: formData
                    })
                    .then(function() {
                        location.reload();
                    })
                    .catch(function() {
                        // Fallback submit form draft biasa
                        inputAksiSimpan.value = 'draft';
                        formProses.submit();
                    });
                } else {
                    location.reload();
                }
            });
        });

        // =========================================================================
        // EXCEL-LIKE KEYBOARD NAVIGATION & VALUE STEPPING PADA TABEL PENGUJIAN
        // Memungkinkan analis navigasi Panah Atas/Bawah/Kiri/Kanan & Enter
        // serta Shift+Panah Atas/Bawah untuk menaikkan/menurunkan angka secara cepat
        // =========================================================================
        function initExcelTableNavigation() {
            document.addEventListener('keydown', function(e) {
                var input = e.target;
                if (!input || input.tagName !== 'INPUT') return;

                // Hanya berlaku untuk input yang berada di dalam tabel (subtabel LCP)
                var cell = input.closest('td, th');
                var row = input.closest('tr');
                var table = input.closest('table');
                if (!cell || !row || !table) return;

                var key = e.key;
                var isArrowUp = (key === 'ArrowUp');
                var isArrowDown = (key === 'ArrowDown');
                var isArrowLeft = (key === 'ArrowLeft');
                var isArrowRight = (key === 'ArrowRight');
                var isEnter = (key === 'Enter');

                if (!isArrowUp && !isArrowDown && !isArrowLeft && !isArrowRight && !isEnter) {
                    return;
                }

                // 1. FITUR MENAIKKAN / MENURUNKAN NILAI ANGKA (Jika menekan Shift + Panah Atas/Bawah, ATAU input bertipe number)
                var isNumericInput = input.type === 'number' || (!isNaN(parseFloat(input.value)) && isFinite(input.value)) || input.name.indexOf('[bobot') !== -1 || input.name.indexOf('[kadar') !== -1 || input.name.indexOf('[hasil') !== -1 || input.name.indexOf('[area') !== -1 || input.name.indexOf('[netto') !== -1 || input.name.indexOf('[bruto') !== -1;

                if (e.shiftKey && (isArrowUp || isArrowDown) && isNumericInput) {
                    e.preventDefault();
                    var curVal = parseFloat(input.value.replace(',', '.')) || 0;
                    var step = input.step ? parseFloat(input.step) : 1;
                    if (input.value.indexOf('.') !== -1) {
                        var decimals = input.value.split('.')[1].length;
                        step = Math.pow(10, -decimals);
                    }
                    var newVal = isArrowUp ? (curVal + step) : (curVal - step);
                    if (input.min && newVal < parseFloat(input.min)) newVal = parseFloat(input.min);
                    if (input.max && newVal > parseFloat(input.max)) newVal = parseFloat(input.max);
                    
                    var decCount = (step.toString().split('.')[1] || '').length;
                    input.value = newVal.toFixed(decCount > 0 ? decCount : 0);
                    input.dispatchEvent(new Event('input', { bubbles: true }));
                    input.dispatchEvent(new Event('change', { bubbles: true }));
                    return;
                }

                // 2. NAVIGASI EXCEL DENGAN PANAH KEYBOARD (Atas, Bawah, Kiri, Kanan, Enter)
                // Cek posisi kursor untuk Kiri / Kanan agar tidak mengganggu pengeditan teks di tengah string
                var atStart = (input.selectionStart === 0 && input.selectionEnd === 0);
                var atEnd = (input.selectionStart === input.value.length && input.selectionEnd === input.value.length);

                if (isArrowLeft && !atStart && input.type !== 'number') return;
                if (isArrowRight && !atEnd && input.type !== 'number') return;

                var allInputsInTable = Array.from(table.querySelectorAll('input:not([type="hidden"]):not([disabled]):not([readonly])'));
                var curIndex = allInputsInTable.indexOf(input);
                if (curIndex === -1) return;

                // Cari baris dan kolom sel saat ini
                var allRows = Array.from(table.querySelectorAll('tbody tr'));
                var rowIndex = allRows.indexOf(row);
                var cellsInRow = Array.from(row.querySelectorAll('td'));
                var colIndex = cellsInRow.indexOf(cell);

                var targetInput = null;

                if (isArrowDown || isEnter) {
                    e.preventDefault();
                    // Pindah ke baris persis di bawahnya pada kolom yang sama (Excel style)
                    if (rowIndex !== -1 && rowIndex + 1 < allRows.length) {
                        var nextRow = allRows[rowIndex + 1];
                        var nextCells = Array.from(nextRow.querySelectorAll('td'));
                        if (nextCells[colIndex]) {
                            targetInput = nextCells[colIndex].querySelector('input:not([type="hidden"]):not([disabled]):not([readonly])');
                        }
                    }
                    // Jika tidak ada di kolom sama persis, gunakan input berikutnya secara sekuensial
                    if (!targetInput && curIndex + 1 < allInputsInTable.length) {
                        targetInput = allInputsInTable[curIndex + 1];
                    }
                } else if (isArrowUp) {
                    e.preventDefault();
                    // Pindah ke baris persis di atasnya pada kolom yang sama (Excel style)
                    if (rowIndex > 0) {
                        var prevRow = allRows[rowIndex - 1];
                        var prevCells = Array.from(prevRow.querySelectorAll('td'));
                        if (prevCells[colIndex]) {
                            targetInput = prevCells[colIndex].querySelector('input:not([type="hidden"]):not([disabled]):not([readonly])');
                        }
                    }
                    // Jika tidak ada di kolom sama persis, gunakan input sebelumnya secara sekuensial
                    if (!targetInput && curIndex > 0) {
                        targetInput = allInputsInTable[curIndex - 1];
                    }
                } else if (isArrowRight) {
                    if (curIndex + 1 < allInputsInTable.length) {
                        e.preventDefault();
                        targetInput = allInputsInTable[curIndex + 1];
                    }
                } else if (isArrowLeft) {
                    if (curIndex > 0) {
                        e.preventDefault();
                        targetInput = allInputsInTable[curIndex - 1];
                    }
                }

                if (targetInput) {
                    targetInput.focus();
                    if (targetInput.select && typeof targetInput.select === 'function') {
                        targetInput.select(); // Auto-select text saat mendarat di cell seperti Excel
                    }
                }
            });
        }

        initExcelTableNavigation();

        // =========================================================================
        // FITUR SALIN PREPARASI DARI SAMPEL LAIN (COPY / CLONE PREPARATION)
        // Membantu penguji menguji multiple sample dengan preparasi yang sama
        // =========================================================================
        var currentCopyTargetFormId = null;
        var currentCopyTargetTemplateId = null;
        var cacheRiwayatPreparasi = [];

        var modalCopyPreparasiEl = document.getElementById('modalCopyPreparasi');
        var modalCopyPreparasi = modalCopyPreparasiEl ? new bootstrap.Modal(modalCopyPreparasiEl) : null;
        var tbodyModalPreparasi = document.getElementById('tbodyModalPreparasi');
        var inputCariPreparasi = document.getElementById('inputCariRiwayatPreparasi');
        var btnResetSearchPreparasi = document.getElementById('btnResetSearchPreparasi');
        var loadingRiwayatPreparasi = document.getElementById('loadingRiwayatPreparasi');
        var emptyRiwayatPreparasi = document.getElementById('emptyRiwayatPreparasi');
        var containerTabelPreparasi = document.getElementById('containerTabelPreparasi');
        var modalCopySubtitle = document.getElementById('modalCopySubtitle');
        var modalPreparasiCounter = document.getElementById('modalPreparasiCounter');

        // Buka modal salin preparasi saat tombol diklik
        document.addEventListener('click', function(e) {
            var btn = e.target.closest('.btn-open-copy-preparasi');
            if (!btn) return;

            currentCopyTargetFormId = btn.getAttribute('data-form-id');
            currentCopyTargetTemplateId = btn.getAttribute('data-template-id');
            var tplNama = btn.getAttribute('data-template-nama') || 'Parameter';

            if (!currentCopyTargetTemplateId || currentCopyTargetTemplateId === '0') {
                alert('Template form ini belum memiliki ID terdaftar.');
                return;
            }

            if (modalCopySubtitle) {
                modalCopySubtitle.textContent = 'Parameter: ' + tplNama;
            }

            if (modalCopyPreparasi) {
                modalCopyPreparasi.show();
                muatRiwayatPreparasi(currentCopyTargetTemplateId);
            }
        });

        // Ambil data riwayat via AJAX
        function muatRiwayatPreparasi(templateId) {
            if (!tbodyModalPreparasi) return;

            tbodyModalPreparasi.innerHTML = '';
            if (loadingRiwayatPreparasi) loadingRiwayatPreparasi.classList.remove('d-none');
            if (emptyRiwayatPreparasi) emptyRiwayatPreparasi.classList.add('d-none');
            if (containerTabelPreparasi) containerTabelPreparasi.classList.add('d-none');
            if (inputCariPreparasi) inputCariPreparasi.value = '';

            var currentSessionId = '<?php echo $sesi['id']; ?>';
            var url = '<?php echo site_url("pengujian/ajax_riwayat_preparasi"); ?>?template_id=' + templateId + '&session_id=' + currentSessionId;

            fetch(url)
                .then(function(res) { return res.json(); })
                .then(function(data) {
                    if (loadingRiwayatPreparasi) loadingRiwayatPreparasi.classList.add('d-none');
                    if (data.status === 'success' && data.data && data.data.length > 0) {
                        cacheRiwayatPreparasi = data.data;
                        renderTabelRiwayatPreparasi(cacheRiwayatPreparasi);
                        if (containerTabelPreparasi) containerTabelPreparasi.classList.remove('d-none');
                    } else {
                        cacheRiwayatPreparasi = [];
                        if (emptyRiwayatPreparasi) emptyRiwayatPreparasi.classList.remove('d-none');
                    }
                })
                .catch(function(err) {
                    if (loadingRiwayatPreparasi) loadingRiwayatPreparasi.classList.add('d-none');
                    if (emptyRiwayatPreparasi) {
                        emptyRiwayatPreparasi.innerHTML = '<div class="text-danger"><i class="bi bi-exclamation-triangle fs-3 d-block mb-1"></i>Gagal memuat riwayat: ' + err.message + '</div>';
                        emptyRiwayatPreparasi.classList.remove('d-none');
                    }
                });
        }

        // Render data baris riwayat ke card list modal yang clean & modern
        function renderTabelRiwayatPreparasi(items) {
            if (!tbodyModalPreparasi) return;
            var html = '';

            items.forEach(function(item, idx) {
                var alatCount = item.alat_ids ? item.alat_ids.length : 0;
                var reagenCount = item.reagen_ids ? item.reagen_ids.length : 0;

                html += '<div class="card border rounded-3 p-3 bg-white shadow-sm mb-2" style="transition: all 0.2s ease;">' +
                        '  <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">' +
                        '    <div style="flex: 1 1 260px;">' +
                        '      <div class="d-flex align-items-center gap-2 mb-1">' +
                        '        <strong class="text-dark" style="font-size: 0.92rem;">' + escapeHtml(item.nama_sampel) + '</strong>' +
                        '        <span class="badge bg-secondary-subtle text-secondary font-monospace" style="font-size: 0.68rem;">' + escapeHtml(item.kode_sampel) + '</span>' +
                        '      </div>' +
                        '      <div class="d-flex align-items-center flex-wrap gap-2 text-muted" style="font-size: 0.74rem;">' +
                        '        <span><i class="bi bi-calendar3 me-1"></i>' + escapeHtml(item.tanggal_display) + '</span>' +
                        '        <span>&bull;</span>' +
                        '        <span><i class="bi bi-person me-1"></i>' + escapeHtml(item.nama_penguji) + '</span>' +
                        '      </div>' +
                        '    </div>' +
                        '    <div class="d-flex align-items-center flex-wrap gap-2">' +
                        '      <div class="text-end d-none d-sm-block me-2">' +
                        '        <div class="badge bg-light text-dark border px-2 py-1 mb-1 d-block text-truncate" style="max-width: 200px;">' + escapeHtml(item.info_baku) + '</div>' +
                        '        <div class="text-muted" style="font-size: 0.70rem;">' +
                        '          <span class="text-primary fw-semibold">' + alatCount + ' alat</span> &bull; <span class="text-info fw-semibold">' + reagenCount + ' reagen</span>' +
                        '        </div>' +
                        '      </div>' +
                        '      <button type="button" class="btn btn-sm btn-primary rounded-pill px-3 py-1 btn-terapkan-preparasi d-flex align-items-center gap-1 shadow-sm" data-idx="' + idx + '" title="Terapkan preparasi ini">' +
                        '        <i class="bi bi-box-arrow-in-down-right"></i>' +
                        '        <span>Terapkan</span>' +
                        '      </button>' +
                        '    </div>' +
                        '  </div>' +
                        '</div>';
            });

            tbodyModalPreparasi.innerHTML = html;
            if (modalPreparasiCounter) {
                modalPreparasiCounter.textContent = 'Menampilkan ' + items.length + ' riwayat sampel sebelumnya';
            }
        }

        // Live Filter Pencarian Riwayat di Modal
        if (inputCariPreparasi) {
            inputCariPreparasi.addEventListener('input', function() {
                var query = this.value.trim().toLowerCase();
                var filtered = cacheRiwayatPreparasi.filter(function(item) {
                    var str = (item.nama_sampel + ' ' + item.kode_sampel + ' ' + item.info_baku + ' ' + item.tanggal_display).toLowerCase();
                    return str.indexOf(query) !== -1;
                });
                renderTabelRiwayatPreparasi(filtered);
            });
        }

        if (btnResetSearchPreparasi) {
            btnResetSearchPreparasi.addEventListener('click', function() {
                if (inputCariPreparasi) {
                    inputCariPreparasi.value = '';
                    renderTabelRiwayatPreparasi(cacheRiwayatPreparasi);
                    inputCariPreparasi.focus();
                }
            });
        }

        // Eksekusi Terapkan Preparasi ke Form Aktif
        document.addEventListener('click', function(e) {
            var btn = e.target.closest('.btn-terapkan-preparasi');
            if (!btn) return;

            var idx = parseInt(btn.getAttribute('data-idx'), 10);
            var selectedItem = cacheRiwayatPreparasi[idx];
            if (!selectedItem || !currentCopyTargetFormId) return;

            var pane = document.getElementById('tab-pane-' + currentCopyTargetFormId);
            if (!pane) return;

            // 1. Terapkan Alat yang Digunakan
            var selectAlat = pane.querySelector('.select-alat-instrument');
            if (selectAlat && selectedItem.alat_ids && Array.isArray(selectedItem.alat_ids)) {
                Array.from(selectAlat.options).forEach(function(opt) {
                    opt.selected = selectedItem.alat_ids.indexOf(parseInt(opt.value, 10)) !== -1;
                });
                updateAlatInfo(selectAlat);
            }

            // 2. Terapkan Reagen yang Digunakan
            var selectReagen = pane.querySelector('.select-reagen-material');
            if (selectReagen && selectedItem.reagen_ids && Array.isArray(selectedItem.reagen_ids)) {
                Array.from(selectReagen.options).forEach(function(opt) {
                    opt.selected = selectedItem.reagen_ids.indexOf(parseInt(opt.value, 10)) !== -1;
                });
                updateReagenInfo(selectReagen);
            }

            // 3. Terapkan Nilai Field Preparasi & Baku ke Elemen Form
            var prepData = selectedItem.preparasi_data || {};
            var fieldCountApplied = 0;

            for (var key in prepData) {
                if (!prepData.hasOwnProperty(key)) continue;
                var val = prepData[key];

                // Cari input form berdasarkan pattern name
                var inputSelector = '[name="forms[' + currentCopyTargetFormId + '][hasil][' + key + ']"]';
                var targetInput = pane.querySelector(inputSelector);

                if (targetInput) {
                    if (targetInput.type === 'checkbox') {
                        targetInput.checked = (val == 1 || val === true || val === '1');
                    } else if (targetInput.type === 'radio') {
                        var radio = pane.querySelector(inputSelector + '[value="' + CSS.escape(val) + '"]');
                        if (radio) radio.checked = true;
                    } else {
                        targetInput.value = val;
                    }
                    targetInput.dispatchEvent(new Event('change', { bubbles: true }));
                    fieldCountApplied++;
                }
            }

            // Tutup Modal
            if (modalCopyPreparasi) {
                modalCopyPreparasi.hide();
            }

            // Tampilkan Notifikasi Sukses
            var notif = document.createElement('div');
            notif.className = 'alert alert-success alert-dismissible fade show shadow-sm mt-3 py-2 px-3';
            notif.innerHTML = '<i class="bi bi-check-circle-fill me-2"></i>' +
                              'Berhasil menyalin data preparasi dari <strong>' + escapeHtml(selectedItem.nama_sampel) + '</strong> (' + escapeHtml(selectedItem.kode_sampel) + '). ' +
                              'Alat, reagen, dan kondisi baku telah diterapkan ke formulir ini.' +
                              '<button type="button" class="btn-close py-2" data-bs-dismiss="alert" aria-label="Close"></button>';
            pane.insertBefore(notif, pane.firstChild);

            setTimeout(function() {
                if (notif && notif.parentNode) {
                    notif.remove();
                }
            }, 6000);
        });

        // =========================================================================
        // Fitur Pemilihan Master Baku Pembanding & Auto-Fill ke Seluruh Form Terkait
        // =========================================================================
        var currentTargetFormId = null;
        var currentTargetFieldId = null;
        var modalPickerBakuEl = document.getElementById('modalPickerBaku');
        var bsModalBaku = modalPickerBakuEl ? new bootstrap.Modal(modalPickerBakuEl) : null;
        var inputCariModalBaku = document.getElementById('inputCariModalBaku');
        var btnResetSearchBaku = document.getElementById('btnResetSearchBaku');
        var tbodyModalBaku = document.getElementById('tbodyModalBaku');
        var modalBakuCounter = document.getElementById('modalBakuCounter');

        // Buka modal saat tombol "Cari Baku" diklik
        document.addEventListener('click', function(e) {
            var btn = e.target.closest('.btn-buka-picker-baku');
            if (btn && bsModalBaku) {
                currentTargetFormId = btn.getAttribute('data-form-id');
                currentTargetFieldId = btn.getAttribute('data-field-id');

                // Pre-fill search box jika input saat ini sudah ada isian teksnya
                var curInput = document.getElementById('f_' + currentTargetFormId + '_' + currentTargetFieldId);
                if (curInput && curInput.value.trim() !== '') {
                    // Ambil kata pertama untuk search
                    var firstWord = curInput.value.trim().split(' ')[0];
                    if (inputCariModalBaku) inputCariModalBaku.value = firstWord;
                } else {
                    if (inputCariModalBaku) inputCariModalBaku.value = '';
                }
                filterBakuModal();
                bsModalBaku.show();
                setTimeout(function() {
                    if (inputCariModalBaku) inputCariModalBaku.focus();
                }, 350);
            }
        });

        // Live filter pencarian di dalam modal baku
        function filterBakuModal() {
            if (!inputCariModalBaku || !tbodyModalBaku) return;
            var q = inputCariModalBaku.value.toLowerCase().trim();
            var rows = tbodyModalBaku.querySelectorAll('.item-baris-baku');
            var emptyNotice = document.getElementById('emptyNoticeBaku');
            var visibleCount = 0;
            rows.forEach(function(row) {
                var searchData = row.getAttribute('data-search') || '';
                if (q === '' || searchData.indexOf(q) !== -1) {
                    row.classList.remove('d-none');
                    visibleCount++;
                } else {
                    row.classList.add('d-none');
                }
            });
            if (emptyNotice) {
                if (visibleCount === 0 && rows.length > 0) {
                    emptyNotice.classList.remove('d-none');
                } else {
                    emptyNotice.classList.add('d-none');
                }
            }
            if (modalBakuCounter) {
                modalBakuCounter.textContent = 'Menampilkan ' + visibleCount + ' dari ' + rows.length + ' data baku pembanding';
            }
        }

        if (inputCariModalBaku) {
            inputCariModalBaku.addEventListener('input', filterBakuModal);
        }
        if (btnResetSearchBaku) {
            btnResetSearchBaku.addEventListener('click', function() {
                if (inputCariModalBaku) {
                    inputCariModalBaku.value = '';
                    filterBakuModal();
                    inputCariModalBaku.focus();
                }
            });
        }

        // Terapkan pilihan baku ke formulir pengujian aktif
        document.addEventListener('click', function(e) {
            var btnPilih = e.target.closest('.btn-pilih-baku-item');
            if (!btnPilih) return;

            var card = btnPilih.closest('.item-baris-baku');
            if (!card || !currentTargetFormId) return;

            var bNama = card.getAttribute('data-nama') || '';
            var bKontrol = card.getAttribute('data-kontrol') || '';
            var bKadar = card.getAttribute('data-kadar') || '';
            var bAir = card.getAttribute('data-air') || '';

            terapkanDataBakuKeForm(currentTargetFormId, bNama, bKontrol, bKadar, bAir);

            if (bsModalBaku) {
                bsModalBaku.hide();
            }
        });

        // Listener jika analis mengetik langsung atau memilih via native datalist
        document.addEventListener('change', function(e) {
            var input = e.target;
            if (input && input.classList.contains('input-baku-field')) {
                var fId = input.getAttribute('data-form-id');
                var val = input.value.trim();
                if (!fId || val === '') return;

                // Cari kecocokan di datalist atau row modal
                var matchedRow = null;
                var rows = tbodyModalBaku ? tbodyModalBaku.querySelectorAll('.item-baris-baku') : [];
                for (var i = 0; i < rows.length; i++) {
                    var r = rows[i];
                    var rNama = r.getAttribute('data-nama') || '';
                    var rKontrol = r.getAttribute('data-kontrol') || '';
                    var combo1 = (rNama + ' No. Kontrol: ' + rKontrol).trim().toLowerCase();
                    var combo2 = (rNama + ' [No: ' + rKontrol + ']').trim().toLowerCase();
                    var vLow = val.toLowerCase();
                    if (vLow === combo1 || vLow === combo2 || (rKontrol && vLow.indexOf(rKontrol.toLowerCase()) !== -1)) {
                        matchedRow = r;
                        break;
                    }
                }

                if (matchedRow) {
                    var bNama = matchedRow.getAttribute('data-nama') || '';
                    var bKontrol = matchedRow.getAttribute('data-kontrol') || '';
                    var bKadar = matchedRow.getAttribute('data-kadar') || '';
                    var bAir = matchedRow.getAttribute('data-air') || '';
                    terapkanDataBakuKeForm(fId, bNama, bKontrol, bKadar, bAir);
                }
            }
        });

        // Fungsi pusat mengisi otomatis data baku ke form terkait
        function terapkanDataBakuKeForm(formId, nama, noKontrol, kadarBaku, kadarAir) {
            var pane = document.getElementById('tab-pane-' + formId);
            if (!pane) return;

            // 1. Set field Nama & No Kontrol Baku
            var formatLengkap = nama + (noKontrol ? ' No. Kontrol: ' + noKontrol : '');

            // Target input utama yang memanggil
            var inputUtama = pane.querySelector('#f_' + formId + '_' + currentTargetFieldId);
            if (inputUtama) {
                if (currentTargetFieldId === 'baku_no_kontrol' || currentTargetFieldId === 'no_kontrol_baku') {
                    inputUtama.value = noKontrol;
                } else if (currentTargetFieldId === 'baku_nama' || currentTargetFieldId === 'nama_baku') {
                    inputUtama.value = nama;
                } else {
                    inputUtama.value = formatLengkap;
                }
                inputUtama.dispatchEvent(new Event('input', { bubbles: true }));
                inputUtama.dispatchEvent(new Event('change', { bubbles: true }));
            }

            // Field pendukung lainnya jika ada di form yang sama:
            // a. Field Nama Baku terpisah
            var inputNama = pane.querySelector('input[name*="[baku_nama]"], input[name*="[nama_baku]"]');
            if (inputNama && inputNama !== inputUtama) {
                inputNama.value = nama;
                inputNama.dispatchEvent(new Event('input', { bubbles: true }));
            }

            // b. Field No Kontrol terpisah
            var inputKontrol = pane.querySelector('input[name*="[baku_no_kontrol]"], input[name*="[no_kontrol_baku]"], input[name*="[no_kontrol]"]');
            if (inputKontrol && inputKontrol !== inputUtama) {
                inputKontrol.value = noKontrol;
                inputKontrol.dispatchEvent(new Event('input', { bubbles: true }));
            }

            // c. Field Gabungan (baku_pembanding)
            var inputGabung = pane.querySelector('input[name*="[baku_pembanding]"]');
            if (inputGabung && inputGabung !== inputUtama) {
                inputGabung.value = formatLengkap;
                inputGabung.dispatchEvent(new Event('input', { bubbles: true }));
            }

            // 2. Set Kadar Baku (%)
            if (kadarBaku !== '' && kadarBaku !== null && !isNaN(parseFloat(kadarBaku))) {
                var inputKb = pane.querySelector('input[name*="[kadar_baku]"], input[name*="[kadar_baku_persen]"], input[name*="[kemurnian_baku]"], input[name*="[baku_kadar]"]');
                if (inputKb) {
                    inputKb.value = parseFloat(kadarBaku);
                    inputKb.dispatchEvent(new Event('input', { bubbles: true }));
                    inputKb.dispatchEvent(new Event('change', { bubbles: true }));
                }
            }

            // 3. Set Kadar Air / Susut Pengeringan (%)
            if (kadarAir !== '' && kadarAir !== null && !isNaN(parseFloat(kadarAir))) {
                var inputAir = pane.querySelector('input[name*="[susut_pengeringan]"], input[name*="[kadar_air]"], input[name*="[baku_sp]"], input[name*="[baku_susut_pengeringan]"]');
                if (inputAir) {
                    inputAir.value = parseFloat(kadarAir);
                    inputAir.dispatchEvent(new Event('input', { bubbles: true }));
                    inputAir.dispatchEvent(new Event('change', { bubbles: true }));
                }
            }

            // 4. Trigger mesin kalkulator otomatis jika ada di form ini (Disolusi KIM-06, Spektro KIM-05, dll)
            if (typeof hitungDisolusi === 'function') hitungDisolusi();
            if (typeof hitungSpektrofotometri === 'function') hitungSpektrofotometri();
        }

        // Inisialisasi awal visibilitas tahap opsional & UI multi-metode kadar
        document.querySelectorAll('.form-tab-pane-uji').forEach(function(pane) {
            updateTahapVisibility(pane);
            if (typeof updateMetodeKadarUI === 'function') updateMetodeKadarUI(pane);
        });

        // Inisialisasi awal kalkulator otomatis & evaluasi kesimpulan
        if (typeof hitungDisolusi === 'function') hitungDisolusi();
        if (typeof hitungSpektrofotometri === 'function') hitungSpektrofotometri();
        if (typeof hitungHPLC === 'function') hitungHPLC();
        if (typeof hitungBobotJenis === 'function') hitungBobotJenis();
        if (typeof hitungGCMS === 'function') hitungGCMS();

        document.querySelectorAll('.form-tab-pane-uji').forEach(function(pane) {
            evaluasiKesimpulanPane(pane);
        });

        // Event listener delegasi input & change untuk memicu evaluasi kesimpulan otomatis secara real-time
        if (formProses) {
            formProses.addEventListener('input', function(e) {
                var pane = e.target.closest('.form-tab-pane-uji');
                if (pane) {
                    clearTimeout(pane._evalTimer);
                    pane._evalTimer = setTimeout(function() {
                        evaluasiKesimpulanPane(pane);
                    }, 300);
                }
            });

            formProses.addEventListener('change', function(e) {
                var pane = e.target.closest('.form-tab-pane-uji');
                if (pane) {
                    if (e.target && e.target.classList.contains('select-tahap-trigger')) {
                        updateTahapVisibility(pane);
                        if (typeof hitungDisolusiPane === 'function') hitungDisolusiPane(pane);
                        if (typeof hitungKeragamanBobot === 'function') hitungKeragamanBobot();
                    }
                    evaluasiKesimpulanPane(pane);
                }
            });
        }

        // Peringatkan penguji sebelum menutup tab jika masih ada perubahan belum tersimpan
        window.addEventListener('beforeunload', function(e) {
            if (isSavingDraft) {
                e.preventDefault();
                e.returnValue = 'Data pengujian sedang disimpan sementara, mohon tunggu beberapa detik.';
            }
        });
    });

    function konfirmasiKirimVerifikasi() {
        var inputAksiSimpan = document.getElementById('inputAksiSimpan');
        if (inputAksiSimpan) inputAksiSimpan.value = 'submit';

        // Deteksi apakah ada parameter uji yang Tidak Memenuhi Syarat (TMS)
        var tmsList = [];
        var selects = document.querySelectorAll('.select-kesimpulan-auto');
        selects.forEach(function(sel) {
            var val = (sel.value || '').trim();
            if (val === 'Tidak Memenuhi Syarat (TMS)' || val === 'TMS' || val.toLowerCase().indexOf('tidak memenuhi') !== -1) {
                var formId = sel.getAttribute('data-form-id');
                var tabBtn = document.getElementById('tab-btn-' + formId);
                var paramTitle = 'Form #' + formId;
                if (tabBtn) {
                    var rawTxt = tabBtn.textContent.replace(/\s+/g, ' ').trim();
                    paramTitle = rawTxt.replace(/^Form #\d+:\s*/i, '');
                }
                tmsList.push(paramTitle);
            }
        });

        if (tmsList.length > 0) {
            var msg = 'PERHATIAN - VALIDASI KESIMPULAN SAMPEL:\n\n' +
                      'Terdapat parameter uji yang dinyatakan TIDAK MEMENUHI SYARAT (TMS):\n' +
                      '• ' + tmsList.join('\n• ') + '\n\n' +
                      'Sesuai ketentuan, status sampel pada Catatan Pengujian (CP) akan dinyatakan:\n' +
                      '"Tidak Memenuhi Syarat (TMS) pada hasil pengujian ' + tmsList.join(', ') + '"\n\n' +
                      'Apakah Anda yakin ingin menyimpan dan meneruskannya ke Verifikasi Penyelia?';
            return confirm(msg);
        }

        return confirm('Apakah Anda yakin ingin menyimpan seluruh hasil pengujian sesi ini dan meneruskannya ke Verifikasi Penyelia?');
    }

    function konfirmasiGantiZatAktif(targetUrl) {
        return confirm('Apakah Anda ingin mengganti pilihan Zat Aktif atau parameter pengujian untuk sesi ini?\n\nSemua data yang telah Anda isi pada parameter yang tetap dipilih akan tersimpan aman dan tidak akan hilang.');
    }
</script>
<!-- Modal Tambah Parameter ke Sesi Aktif (Poin 11) -->
<div class="modal fade" id="modalTambahParameterSesi" tabindex="-1" aria-labelledby="modalTambahParameterSesiLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <form action="<?php echo site_url('pengujian/tambah_parameter_sesi/' . $sesi['id']); ?>" method="post" id="formTambahParamSesi">
                <input type="hidden" name="method_id" id="tambah_param_method_id" value="">
                <div class="modal-header bg-light py-3 border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        <div class="bg-success-subtle text-success p-2 rounded-2 d-inline-flex">
                            <i class="bi bi-plus-circle-fill fs-5"></i>
                        </div>
                        <div>
                            <h6 class="modal-title fw-bold text-dark mb-0" id="modalTambahParameterSesiLabel">Tambah Parameter Pengujian ke Sesi Ini</h6>
                            <small class="text-muted">Pilih parameter dari master data laboratorium (<?php echo html_escape($sesi['jenis_pengujian']); ?>) untuk ditambahkan</small>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3">
                    <!-- Search box -->
                    <div class="mb-3">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control border-start-0" id="searchMasterSesiModal" placeholder="Cari nama parameter uji, zat aktif, atau metode analisa..." autocomplete="off">
                        </div>
                    </div>

                    <!-- Selected Parameter Preview Card -->
                    <div class="card border-primary-subtle bg-primary-subtle mb-3 d-none" id="cardSelectedParamPreview">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                <div>
                                    <div class="text-muted small fw-bold text-uppercase" style="font-size: 0.70rem;">Parameter Terpilih:</div>
                                    <h6 class="fw-bold text-primary mb-1" id="previewParamNama">-</h6>
                                    <div class="small text-secondary" id="previewParamMeta">-</div>
                                </div>
                                <div style="min-width: 220px;">
                                    <label class="form-label small fw-bold text-dark mb-1">Pilihan Form LCP:</label>
                                    <select class="form-select form-select-sm" name="template_id" id="selTemplateIdModal">
                                        <?php if (!empty($daftar_template_bidang)): ?>
                                            <?php foreach ($daftar_template_bidang as $tpl_b): ?>
                                                <option value="<?php echo $tpl_b['id']; ?>">[<?php echo html_escape($tpl_b['kode_template']); ?>] <?php echo html_escape($tpl_b['nama_template']); ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Master Table -->
                    <div class="table-responsive" style="max-height: 380px;">
                        <table class="table table-hover align-middle mb-0" id="tabelMasterSesiModal">
                            <thead class="table-light sticky-top">
                                <tr style="font-size: 0.78rem;">
                                    <th width="35%">PARAMETER &amp; ZAT AKTIF</th>
                                    <th width="30%">METODE &amp; PERSYARATAN</th>
                                    <th width="20%">FORM LCP DEFAULT</th>
                                    <th width="15%" class="text-center">PILIH</th>
                                </tr>
                            </thead>
                            <tbody id="tbodyMasterSesiModal" style="font-size: 0.82rem;">
                                <?php if (!empty($daftar_master_bidang)): ?>
                                    <?php 
                                        $existing_mids = array();
                                        if (!empty($forms)) {
                                            foreach ($forms as $f_item) {
                                                if (!empty($f_item['method_id'])) $existing_mids[] = (int)$f_item['method_id'];
                                            }
                                        }
                                    ?>
                                    <?php foreach ($daftar_master_bidang as $mb): ?>
                                        <?php $is_already_in_session = in_array((int)$mb['id'], $existing_mids); ?>
                                        <tr class="master-sesi-item <?php echo $is_already_in_session ? 'table-light opacity-75' : ''; ?>" 
                                            id="row_master_sesi_<?php echo $mb['id']; ?>"
                                            data-search="<?php echo html_escape(strtolower($mb['parameter_uji'] . ' ' . $mb['nama_zat_aktif'] . ' ' . $mb['metode_analisa'] . ' ' . $mb['persyaratan'])); ?>">
                                            <td>
                                                <div class="fw-bold text-dark mb-1"><?php echo html_escape($mb['parameter_uji']); ?></div>
                                                <div class="text-muted small">
                                                    <i class="bi bi-capsule me-1"></i><?php echo html_escape($mb['nama_zat_aktif']); ?>
                                                    <?php if (!empty($mb['bentuk_sediaan'])): ?>
                                                        <span class="badge bg-light text-secondary border ms-1"><?php echo html_escape($mb['bentuk_sediaan']); ?></span>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="text-secondary small fw-semibold"><?php echo html_escape($mb['metode_analisa'] ?: '-'); ?></div>
                                                <div class="text-muted small font-monospace mt-1"><?php echo html_escape($mb['persyaratan'] ?: '-'); ?></div>
                                            </td>
                                            <td>
                                                <?php if (!empty($mb['kode_template'])): ?>
                                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill">
                                                        [<?php echo html_escape($mb['kode_template']); ?>] <?php echo html_escape($mb['nama_template']); ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-muted small">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <?php if ($is_already_in_session): ?>
                                                    <span class="badge bg-secondary-subtle text-secondary border px-2 py-1 rounded-pill small">
                                                        <i class="bi bi-check2"></i> Ada di Sesi
                                                    </span>
                                                <?php else: ?>
                                                    <button type="button" 
                                                            class="btn btn-sm btn-outline-primary py-1 px-3 rounded-pill fw-semibold btn-select-param-sesi"
                                                            data-id="<?php echo $mb['id']; ?>"
                                                            data-param="<?php echo html_escape($mb['parameter_uji']); ?>"
                                                            data-za="<?php echo html_escape($mb['nama_zat_aktif']); ?>"
                                                            data-sediaan="<?php echo html_escape($mb['bentuk_sediaan'] ?: ''); ?>"
                                                            data-metode="<?php echo html_escape($mb['metode_analisa'] ?: '-'); ?>"
                                                            data-syarat="<?php echo html_escape($mb['persyaratan'] ?: '-'); ?>"
                                                            data-tpl-id="<?php echo !empty($mb['template_id']) ? $mb['template_id'] : ''; ?>">
                                                        Pilih
                                                    </button>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer bg-light py-2 d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success btn-sm px-4 fw-semibold shadow-sm" id="btnSubmitTambahParamSesi" disabled>
                        <i class="bi bi-plus-lg me-1"></i> Tambahkan ke Sesi Pengujian
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Live Search & Handler Modal Tambah Parameter Sesi (Poin 11)
document.addEventListener('DOMContentLoaded', function() {
    var searchInput = document.getElementById('searchMasterSesiModal');
    var items = document.querySelectorAll('.master-sesi-item');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            var q = this.value.toLowerCase().trim();
            items.forEach(function(item) {
                var s = item.getAttribute('data-search') || '';
                item.style.display = (!q || s.indexOf(q) !== -1) ? '' : 'none';
            });
        });
    }

    var hiddenInput = document.getElementById('tambah_param_method_id');
    var cardPreview = document.getElementById('cardSelectedParamPreview');
    var previewNama = document.getElementById('previewParamNama');
    var previewMeta = document.getElementById('previewParamMeta');
    var selTemplate = document.getElementById('selTemplateIdModal');
    var btnSubmit = document.getElementById('btnSubmitTambahParamSesi');

    document.querySelectorAll('.btn-select-param-sesi').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var mId = this.getAttribute('data-id');
            var mParam = this.getAttribute('data-param');
            var mZa = this.getAttribute('data-za');
            var mSediaan = this.getAttribute('data-sediaan');
            var mMetode = this.getAttribute('data-metode');
            var mSyarat = this.getAttribute('data-syarat');
            var mTplId = this.getAttribute('data-tpl-id');

            if (hiddenInput) hiddenInput.value = mId;
            if (previewNama) previewNama.textContent = mParam;
            if (previewMeta) previewMeta.textContent = mZa + (mSediaan ? ' (' + mSediaan + ')' : '') + ' • ' + mMetode + ' • Syarat: ' + mSyarat;
            if (selTemplate && mTplId) selTemplate.value = mTplId;

            if (cardPreview) cardPreview.classList.remove('d-none');
            if (btnSubmit) btnSubmit.disabled = false;

            // Highlight baris terpilih
            document.querySelectorAll('.master-sesi-item').forEach(function(row) {
                row.classList.remove('table-primary');
            });
            var selRow = document.getElementById('row_master_sesi_' + mId);
            if (selRow) selRow.classList.add('table-primary');
        });
    });
});
</script>
