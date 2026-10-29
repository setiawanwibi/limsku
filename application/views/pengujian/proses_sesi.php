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

                </div>
            </div>
        </div>
    </div>

    <!-- Alert Catatan Penolakan Revisi -->
    <?php if ($sesi['status'] === 'Ditolak' && !empty($sesi['alasan_penolakan'])): ?>
        <div class="alert alert-danger border-2 border-danger shadow-sm mb-4">
            <div class="d-flex align-items-center gap-2 mb-2">
                <i class="bi bi-exclamation-octagon-fill text-danger fs-5"></i>
                <h6 class="fw-bold mb-0 text-danger">Catatan Penolakan / Poin Perbaikan:</h6>
            </div>
            <div class="bg-white p-3 rounded border border-danger-subtle font-monospace text-dark" style="font-size: 0.82rem; line-height: 1.55;">
                <?php echo nl2br(html_escape($sesi['alasan_penolakan'])); ?>
            </div>
            <small class="text-muted d-block mt-2" style="font-size: 0.72rem;">
                <i class="bi bi-info-circle me-1"></i> Harap perbaiki nilai perhitungan, lampiran, atau data uji sesuai dengan catatan di atas sebelum mengirim ulang.
            </small>
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
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="badge bg-primary px-2 py-1"><i class="bi bi-link-45deg me-1"></i> Data Master Metode Monografi Terhubung</span>
                                                <strong class="text-dark fs-6"><?php echo html_escape($f['parameter_uji'] ?: $f['nama_metode']); ?></strong>
                                            </div>
                                            <small class="text-muted"><i class="bi bi-qr-code me-1"></i>Kode: <code><?php echo html_escape($f['kode_metode'] ?: '-'); ?></code></small>
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
                                            <small class="text-muted d-block mt-1">Tahan tombol <em>Ctrl</em> (Windows) untuk memilih lebih dari 1 alat.</small>

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

                                                            // Tentukan apakah reagen tidak dapat dipilih
                                                            $is_disabled = ($is_stok_kosong || $is_expired);
                                                            $status_keterangan = '';
                                                            if ($is_expired && $is_stok_kosong) {
                                                                $status_keterangan = ' [KEDALUWARSA & STOK KOSONG]';
                                                            } elseif ($is_expired) {
                                                                $status_keterangan = ' [KEDALUWARSA - ' . date('d/m/Y', strtotime($rg['expired_date'])) . ']';
                                                            } elseif ($is_stok_kosong) {
                                                                $status_keterangan = ' [STOK HABIS]';
                                                            }
                                                            $nama_tampil = !empty($rg['nama_barang']) ? $rg['nama_barang'] : $rg['nama_reagen'];
                                                            ?>
                                                            <option value="<?php echo $rg['id']; ?>" 
                                                                <?php echo in_array($rg['id'], $saved_reagen_ids) ? 'selected' : ''; ?>
                                                                <?php echo $is_disabled ? 'disabled' : ''; ?>
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
                                                Tahan tombol <em>Ctrl</em> (Windows) untuk memilih lebih dari 1 reagen.
                                                <span class="text-danger fw-semibold d-inline-block ms-1">
                                                    <i class="bi bi-slash-circle me-1"></i>Reagen kedaluwarsa atau stok habis dinonaktifkan otomatis.
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
                                                <div class="parameter-section mb-4">
                                                    <div class="parameter-section-title">
                                                        <i class="bi bi-folder2-open me-1"></i>
                                                        <?php echo html_escape(isset($sec['title']) ? $sec['title'] : 'Bagian Form'); ?>
                                                    </div>

                                                    <?php if (!empty($sec['description'])): ?>
                                                        <p class="text-muted small mb-3"><?php echo html_escape($sec['description']); ?></p>
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

                                                                    $val_text = is_array($raw_val) ? json_encode($raw_val, JSON_UNESCAPED_UNICODE) : (string)$raw_val;
                                                                ?>

                                                                <?php if ($fld_type === 'table'): ?>
                                                                    <!-- Subtabel Pengamatan (Matriks Bertahap) -->
                                                                    <div class="col-12">
                                                                        <label class="form-label fw-semibold"><?php echo html_escape($fld_label); ?></label>
                                                                        <div class="table-responsive bg-white rounded border">
                                                                            <table class="table table-sm table-bordered align-middle mb-0">
                                                                                <thead class="table-light">
                                                                                    <tr>
                                                                                        <?php foreach ($fld['columns'] as $col): ?>
                                                                                            <th class="small text-nowrap"><?php echo html_escape($col['label']); ?></th>
                                                                                        <?php endforeach; ?>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <?php
                                                                                        $existing_rows = !empty($raw_val) && is_array($raw_val)
                                                                                            ? $raw_val
                                                                                            : (isset($fld['default_rows']) ? $fld['default_rows'] : array(array()));
                                                                                    ?>
                                                                                    <?php foreach ($existing_rows as $r_idx => $row_data): ?>
                                                                                        <tr>
                                                                                            <?php foreach ($fld['columns'] as $col): ?>
                                                                                                <?php
                                                                                                    $c_id = $col['id'];
                                                                                                    $c_val = isset($row_data[$c_id]) ? $row_data[$c_id] : '';
                                                                                                    $c_readonly = !empty($col['readonly']) ? 'readonly' : '';
                                                                                                    $c_type = isset($col['type']) && $col['type'] === 'date' ? 'date' : 'text';
                                                                                                ?>
                                                                                                <td>
                                                                                                    <input
                                                                                                        type="<?php echo $c_type; ?>"
                                                                                                        class="form-control form-control-sm"
                                                                                                        name="forms[<?php echo $f['id']; ?>][hasil][<?php echo $fld_id; ?>][<?php echo $r_idx; ?>][<?php echo $c_id; ?>]"
                                                                                                        value="<?php echo html_escape((string)$c_val); ?>"
                                                                                                        <?php echo $c_readonly; ?>
                                                                                                    >
                                                                                                </td>
                                                                                            <?php endforeach; ?>
                                                                                        </tr>
                                                                                    <?php endforeach; ?>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>

                                                                <?php elseif ($fld_type === 'checkbox'): ?>
                                                                    <div class="col-md-6 d-flex align-items-center">
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
                                                                    <div class="col-12">
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
                                                                    <div class="col-md-6">
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

                                                                <?php elseif ($fld_type === 'textarea'): ?>
                                                                    <div class="col-12">
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
                                                                    <div class="col-md-6">
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
                                                                    <div class="col-md-6">
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
                                                                    <div class="col-md-6">
                                                                        <label for="f_<?php echo $f['id']; ?>_<?php echo $fld_id; ?>" class="form-label">
                                                                            <?php echo html_escape($fld_label . $fld_unit); ?>
                                                                            <?php if ($fld_req): ?><span class="text-danger">*</span><?php endif; ?>
                                                                        </label>
                                                                        <input
                                                                            type="<?php echo ($fld_type === 'number' ? 'number' : ($fld_type === 'date' ? 'date' : 'text')); ?>"
                                                                            step="<?php echo ($fld_type === 'number' ? 'any' : ''); ?>"
                                                                            class="form-control"
                                                                            id="f_<?php echo $f['id']; ?>_<?php echo $fld_id; ?>"
                                                                            name="forms[<?php echo $f['id']; ?>][hasil][<?php echo $fld_id; ?>]"
                                                                            value="<?php echo html_escape($val_text); ?>"
                                                                            placeholder="<?php echo html_escape($fld_ph); ?>"
                                                                            <?php echo $fld_req; ?>
                                                                        >
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
    </div>

</div>

<style>
    .info-detail-container {
        font-size: 0.78rem;
    }
    .info-item-card {
        background: #ffffff;
        border: 1px solid #d9e2ec;
        border-radius: 6px;
        padding: 8px 10px;
        margin-bottom: 6px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    }
    .info-item-card:last-child {
        margin-bottom: 0;
    }
    .info-item-title {
        font-weight: 700;
        color: #102a43;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 6px;
        margin-bottom: 4px;
    }
    .info-item-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
        gap: 4px 10px;
        color: #486581;
    }
    .info-item-grid span {
        display: block;
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

                html += '<div class="info-item-card border-start border-primary border-3">' +
                        '  <div class="info-item-title">' +
                        '    <span><i class="bi bi-cpu text-primary me-1"></i><strong>' + escapeHtml(nama) + '</strong></span>' +
                        '    <span class="badge bg-light text-dark border font-monospace">' + escapeHtml(kode) + '</span>' +
                        '  </div>' +
                        '  <div class="info-item-grid small">' +
                        '    <div>Merk/Tipe: <strong class="text-dark">' + escapeHtml(merk) + '</strong></div>' +
                        '    <div>Software: <strong class="text-dark">' + escapeHtml(sw) + '</strong></div>' +
                        '    <div>Detektor: <strong class="text-dark">' + escapeHtml(det) + '</strong></div>' +
                        '    <div>Ruangan: <strong class="text-dark">' + escapeHtml(rgn) + '</strong></div>' +
                        '    <div>Kondisi ADM: <span class="badge bg-success-subtle text-success border border-success-subtle">' + escapeHtml(adm) + '</span></div>' +
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

                html += '<div class="info-item-card border-start border-info border-3">' +
                        '  <div class="info-item-title">' +
                        '    <span><i class="bi bi-droplet-half text-info me-1"></i><strong>' + escapeHtml(nama) + '</strong></span>' +
                        '    <span class="badge bg-info-subtle text-info border border-info-subtle font-monospace">Kat: ' + escapeHtml(katalog) + '</span>' +
                        '  </div>' +
                        '  <div class="info-item-grid small">' +
                        '    <div>Kode Barang: <strong class="text-dark font-monospace">' + escapeHtml(kode) + '</strong></div>' +
                        '    <div>Merk/Produsen: <strong class="text-dark">' + escapeHtml(merk) + '</strong></div>' +
                        '    <div>Jenis Barang: <span class="badge bg-light text-secondary border">' + escapeHtml(jenis) + '</span></div>' +
                        '    <div>Sisa Stok: <strong class="text-success">' + escapeHtml(stok) + '</strong></div>' +
                        '    <div>Expired Date: <strong class="' + (exp !== '-' && new Date(exp) < new Date() ? 'text-danger' : 'text-dark') + '">' + escapeHtml(exp) + '</strong></div>' +
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
        // Auto-Calculator Engine untuk Formulir Keragaman Bobot (KIM-02)
        // Sesuai Formula Resmi Excel: POM-06.02/CFM.01/SOP.01/F.01A.02
        // =========================================================================
        function hitungKeragamanBobot() {
            var bobotRataInput = document.querySelector('input[name*="[bobot_rata_rata]"]');
            var kadarHasilInput = document.querySelector('input[name*="[kadar_hasil_uji]"]');
            var etiketInput = document.querySelector('input[name*="[etiket]"]');
            var tahap2Select = document.querySelector('select[name*="[lakukan_tahap2]"]');

            if (!bobotRataInput && !kadarHasilInput && !etiketInput) {
                return; // Bukan form Keragaman Bobot
            }

            var G14 = bobotRataInput ? parseNum(bobotRataInput.value) : 0; // Bobot rata-rata (mg)
            var G15 = kadarHasilInput ? parseNum(kadarHasilInput.value) : 0; // Kadar dari hasil uji (mg/tab)
            var G16 = etiketInput ? parseNum(etiketInput.value) : 0; // Etiket (mg/tab)
            var isTahap2 = tahap2Select && tahap2Select.value === 'Ya';

            // 1. Hitung Baris Tahap 1 (n = 10)
            // Rumus Excel: H20 = G20 / $G$14 * $G$15 | I20 = H20 / $G$16 * 100
            var kadarPersenT1 = [];
            for (var i = 0; i < 10; i++) {
                var brutoEl = document.querySelector('input[name*="[tabel_tahap1][' + i + '][bruto]"]');
                var cangkangEl = document.querySelector('input[name*="[tabel_tahap1][' + i + '][cangkang]"]');
                var nettoEl = document.querySelector('input[name*="[tabel_tahap1][' + i + '][netto]"]');
                var kadarMgEl = document.querySelector('input[name*="[tabel_tahap1][' + i + '][kadar_mg]"]');
                var kadarPersenEl = document.querySelector('input[name*="[tabel_tahap1][' + i + '][kadar_persen]"]');

                if (nettoEl) {
                    var bruto = brutoEl ? parseNum(brutoEl.value) : 0;
                    var cangkang = cangkangEl ? parseNum(cangkangEl.value) : 0;
                    var netto = 0;

                    if (bruto > 0) {
                        netto = bruto - cangkang;
                        nettoEl.value = netto.toFixed(2);
                    } else if (nettoEl.value) {
                        netto = parseNum(nettoEl.value);
                    }

                    if (netto > 0) {
                        if (G14 > 0 && G15 > 0) {
                            var kadarMg = (netto / G14) * G15; // H20 = G20 / $G$14 * $G$15
                            if (kadarMgEl) kadarMgEl.value = kadarMg.toFixed(2);

                            var kadarPersen = G16 > 0 ? (kadarMg / G16) * 100 : (netto / G14) * 100; // I20 = H20 / $G$16 * 100
                            if (kadarPersenEl) kadarPersenEl.value = kadarPersen.toFixed(2);
                            kadarPersenT1.push(kadarPersen);
                        } else if (kadarPersenEl && parseNum(kadarPersenEl.value) > 0) {
                            kadarPersenT1.push(parseNum(kadarPersenEl.value));
                        }
                    }
                }
            }

            // Statistik Tahap 1
            var meanT1 = 0, sdT1 = 0, rsdT1 = 0;
            if (kadarPersenT1.length >= 2) {
                var n1 = kadarPersenT1.length;
                var sum1 = kadarPersenT1.reduce(function(a, b) { return a + b; }, 0);
                meanT1 = sum1 / n1; // I30: =AVERAGE(I20:I29)
                
                var var1 = kadarPersenT1.reduce(function(a, b) { return a + Math.pow(b - meanT1, 2); }, 0) / (n1 - 1);
                sdT1 = Math.sqrt(var1); // I31: =STDEV(I20:I29)
                rsdT1 = meanT1 > 0 ? (sdT1 / meanT1) * 100 : 0; // I32: =I31/I30*100

                var xRerataT1Input = document.querySelector('input[name*="[x_rerata_tahap1]"]');
                var sdT1Input = document.querySelector('input[name*="[sd_tahap1]"]');
                var rsdT1Input = document.querySelector('input[name*="[rsd_tahap1]"]');

                if (xRerataT1Input) xRerataT1Input.value = meanT1.toFixed(2);
                if (sdT1Input) sdT1Input.value = sdT1.toFixed(2);
                if (rsdT1Input) rsdT1Input.value = rsdT1.toFixed(2);
            }

            // 2. Hitung Baris Tahap 2 (No. 11 - 30) jika diaktifkan
            var kadarPersenT2 = [];
            if (isTahap2) {
                for (var j = 0; j < 20; j++) {
                    var bruto2El = document.querySelector('input[name*="[tabel_tahap2][' + j + '][bruto]"]');
                    var cangkang2El = document.querySelector('input[name*="[tabel_tahap2][' + j + '][cangkang]"]');
                    var netto2El = document.querySelector('input[name*="[tabel_tahap2][' + j + '][netto]"]');
                    var kadarMg2El = document.querySelector('input[name*="[tabel_tahap2][' + j + '][kadar_mg]"]');
                    var kadarPersen2El = document.querySelector('input[name*="[tabel_tahap2][' + j + '][kadar_persen]"]');

                    if (netto2El) {
                        var bruto2 = bruto2El ? parseNum(bruto2El.value) : 0;
                        var cangkang2 = cangkang2El ? parseNum(cangkang2El.value) : 0;
                        var netto2 = 0;

                        if (bruto2 > 0) {
                            netto2 = bruto2 - cangkang2;
                            netto2El.value = netto2.toFixed(2);
                        } else if (netto2El.value) {
                            netto2 = parseNum(netto2El.value);
                        }

                        if (netto2 > 0) {
                            if (G14 > 0 && G15 > 0) {
                                var kadarMg2 = (netto2 / G14) * G15;
                                if (kadarMg2El) kadarMg2El.value = kadarMg2.toFixed(2);

                                var kadarPersen2 = G16 > 0 ? (kadarMg2 / G16) * 100 : (netto2 / G14) * 100;
                                if (kadarPersen2El) kadarPersen2El.value = kadarPersen2.toFixed(2);
                                kadarPersenT2.push(kadarPersen2);
                            } else if (kadarPersen2El && parseNum(kadarPersen2El.value) > 0) {
                                kadarPersenT2.push(parseNum(kadarPersen2El.value));
                            }
                        }
                    }
                }
            }

            // Statistik Gabungan 30 Data
            var all30 = kadarPersenT1.concat(kadarPersenT2);
            var mean30 = 0, sd30 = 0, rsd30 = 0;
            if (isTahap2 && all30.length >= 15) {
                var n30 = all30.length;
                var sum30 = all30.reduce(function(a, b) { return a + b; }, 0);
                mean30 = sum30 / n30;
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

            // 3. Evaluasi Acceptance Value (AV)
            // Rumus Farmakope Indonesia & Form BPOM POM-06.02/CFM.01/SOP.01/F.01A.02:
            // Jika n=10 => k = 2.4 | Jika n=30 => k = 2.0
            var finalMean = (isTahap2 && mean30 > 0) ? mean30 : meanT1;
            var finalSD = (isTahap2 && sd30 > 0) ? sd30 : sdT1;
            var k = (isTahap2 && all30.length >= 25) ? 2.0 : 2.4;

            if (finalMean > 0) {
                var M = finalMean;
                var AV = 0;

                // Logika Matriks Kondisi Excel:
                if (finalMean >= 98.5 && finalMean <= 101.5) {
                    M = finalMean;
                    AV = k * finalSD; // AV = ks
                } else if (finalMean < 98.5) {
                    M = 98.5;
                    AV = (98.5 - finalMean) + (k * finalSD); // AV = 98.5 - xrerata + ks
                } else {
                    M = 101.5;
                    AV = (finalMean - 101.5) + (k * finalSD); // AV = xrerata - 101.5 + ks
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
            }
        }

        // Pasang listener pada perubahan input bobot dan parameter terkait
        document.addEventListener('input', function(e) {
            if (e.target && e.target.matches('input[name*="[tabel_tahap1]"], input[name*="[tabel_tahap2]"], input[name*="[bobot_rata_rata]"], input[name*="[kadar_hasil_uji]"], input[name*="[etiket]"]')) {
                hitungKeragamanBobot();
            }
        });

        document.addEventListener('change', function(e) {
            if (e.target && e.target.matches('select[name*="[lakukan_tahap2]"]')) {
                hitungKeragamanBobot();
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
            }
        }

        // Listener untuk KIM-03
        document.addEventListener('input', function(e) {
            if (e.target && e.target.matches('input[name*="[area_serapan]"], input[name*="[kemurnian_baku]"], input[name*="[bobot_baku]"], input[name*="[hasil_ukur_baku]"], input[name*="[faktor_pengenceran_baku]"], input[name*="[faktor_pengenceran_uji]"], input[name*="[etiket]"]')) {
                hitungKeseragamanKandungan();
            }
        });

        // =========================================================================
        // Auto-Calculator Engine untuk Formulir Spektrofotometri (KIM-05)
        // Sesuai Formula Resmi Excel: POM-06.02/CFM.01/SOP.01/F.01A.06
        // Kadar = (Au / Ab) * (Bb / Bu) * (Fu / Fb) * (Br / Ke) * Kb * 100%
        // RPD = |Uji1 - Uji2| / Rata2 * 100%
        // Syarat Horwitz = 0.5 * (2 ^ (1 - 0.5 * LOG10(Kadar/100)))
        // =========================================================================
        function hitungSpektrofotometri() {
            var kbInput = document.querySelector('input[name*="[kadar_baku_persen]"]'); // M25
            var brInput = document.querySelector('input[name*="[bobot_rata_rata_tablet]"]'); // K46
            var keInput = document.querySelector('input[name*="[klaim_etiket]"]'); // K47

            var Kb = kbInput ? parseFloat(kbInput.value) || 0 : 0;
            var Br = brInput ? parseFloat(brInput.value) || 0 : 0;
            var Ke = keInput ? parseFloat(keInput.value) || 0 : 0;

            // Baris 0: Baku | Baris 1: Uji 1 | Baris 2: Uji 2
            var wadahZatBaku = document.querySelector('input[name*="[tabel_preparasi][0][wadah_zat]"]');
            var wadahSisaBaku = document.querySelector('input[name*="[tabel_preparasi][0][wadah_sisa]"]');
            var bobotZatBakuEl = document.querySelector('input[name*="[tabel_preparasi][0][bobot_zat]"]');
            var fbEl = document.querySelector('input[name*="[tabel_preparasi][0][faktor_pengenceran]"]');
            var abEl = document.querySelector('input[name*="[tabel_preparasi][0][serapan]"]');

            var wadahZatU1 = document.querySelector('input[name*="[tabel_preparasi][1][wadah_zat]"]');
            var wadahSisaU1 = document.querySelector('input[name*="[tabel_preparasi][1][wadah_sisa]"]');
            var bobotZatU1El = document.querySelector('input[name*="[tabel_preparasi][1][bobot_zat]"]');
            var fu1El = document.querySelector('input[name*="[tabel_preparasi][1][faktor_pengenceran]"]');
            var au1El = document.querySelector('input[name*="[tabel_preparasi][1][serapan]"]');

            var wadahZatU2 = document.querySelector('input[name*="[tabel_preparasi][2][wadah_zat]"]');
            var wadahSisaU2 = document.querySelector('input[name*="[tabel_preparasi][2][wadah_sisa]"]');
            var bobotZatU2El = document.querySelector('input[name*="[tabel_preparasi][2][bobot_zat]"]');
            var fu2El = document.querySelector('input[name*="[tabel_preparasi][2][faktor_pengenceran]"]');
            var au2El = document.querySelector('input[name*="[tabel_preparasi][2][serapan]"]');

            if (!kbInput && !abEl) return;

            // 1. Hitung bobot zat = wadah+zat - wadah+sisa
            var Bb = 0, Bu1 = 0, Bu2 = 0;
            if (wadahZatBaku && wadahSisaBaku) {
                var wzb = parseFloat(wadahZatBaku.value) || 0;
                var wsb = parseFloat(wadahSisaBaku.value) || 0;
                Bb = wzb > 0 ? (wzb - wsb) : (parseFloat(bobotZatBakuEl.value) || 0);
                if (bobotZatBakuEl && Bb > 0) bobotZatBakuEl.value = Bb.toFixed(2);
            }
            if (wadahZatU1 && wadahSisaU1) {
                var wzu1 = parseFloat(wadahZatU1.value) || 0;
                var wsu1 = parseFloat(wadahSisaU1.value) || 0;
                Bu1 = wzu1 > 0 ? (wzu1 - wsu1) : (parseFloat(bobotZatU1El.value) || 0);
                if (bobotZatU1El && Bu1 > 0) bobotZatU1El.value = Bu1.toFixed(2);
            }
            if (wadahZatU2 && wadahSisaU2) {
                var wzu2 = parseFloat(wadahZatU2.value) || 0;
                var wsu2 = parseFloat(wadahSisaU2.value) || 0;
                Bu2 = wzu2 > 0 ? (wzu2 - wsu2) : (parseFloat(bobotZatU2El.value) || 0);
                if (bobotZatU2El && Bu2 > 0) bobotZatU2El.value = Bu2.toFixed(2);
            }

            var Ab = abEl ? parseFloat(abEl.value) || 0 : 0;
            var Fb = fbEl ? parseFloat(fbEl.value) || 1 : 1;
            var Au1 = au1El ? parseFloat(au1El.value) || 0 : 0;
            var Fu1 = fu1El ? parseFloat(fu1El.value) || 1 : 1;
            var Au2 = au2El ? parseFloat(au2El.value) || 0 : 0;
            var Fu2 = fu2El ? parseFloat(fu2El.value) || 1 : 1;

            // 2. Hitung Kadar Uji 1 & 2 (%)
            var kadarU1 = 0, kadarU2 = 0;
            var faktorKb = (Kb > 1) ? (Kb / 100) : Kb;

            if (Ab > 0 && Bu1 > 0 && Ke > 0 && Bb > 0) {
                kadarU1 = (Au1 / Ab) * (Bb / Bu1) * (Fu1 / Fb) * (Br / Ke) * faktorKb * 100;
            }
            if (Ab > 0 && Bu2 > 0 && Ke > 0 && Bb > 0) {
                kadarU2 = (Au2 / Ab) * (Bb / Bu2) * (Fu2 / Fb) * (Br / Ke) * faktorKb * 100;
            }

            var kadarU1Input = document.querySelector('input[name*="[kadar_uji_1]"]');
            var kadarU2Input = document.querySelector('input[name*="[kadar_uji_2]"]');
            var kadarRataPersenInput = document.querySelector('input[name*="[kadar_rata_rata_persen]"]');
            var kadarRataMgInput = document.querySelector('input[name*="[kadar_rata_rata_mg]"]');
            var rpdInput = document.querySelector('input[name*="[rpd_hitung]"]');
            var horwitzInput = document.querySelector('input[name*="[syarat_rpd_horwitz]"]');

            if (kadarU1Input && kadarU1 > 0) kadarU1Input.value = kadarU1.toFixed(2);
            if (kadarU2Input && kadarU2 > 0) kadarU2Input.value = kadarU2.toFixed(2);

            if (kadarU1 > 0 || kadarU2 > 0) {
                var rataPersen = (kadarU1 > 0 && kadarU2 > 0) ? (kadarU1 + kadarU2) / 2 : (kadarU1 || kadarU2);
                var rataMg = Ke > 0 ? (rataPersen * Ke) / 100 : 0;

                if (kadarRataPersenInput) kadarRataPersenInput.value = rataPersen.toFixed(2);
                if (kadarRataMgInput) kadarRataMgInput.value = rataMg.toFixed(2);

                // RPD = |Uji1 - Uji2| / Rata2 * 100
                if (kadarU1 > 0 && kadarU2 > 0 && rataPersen > 0) {
                    var rpd = (Math.abs(kadarU1 - kadarU2) / rataPersen) * 100;
                    if (rpdInput) rpdInput.value = rpd.toFixed(2);

                    // 1/2 CV Horwitz: 0.5 * 2 ^ (1 - 0.5 * LOG10(kadar_fraksi))
                    var C_fraksi = rataPersen / 100;
                    if (C_fraksi > 0) {
                        var horw = 0.5 * Math.pow(2, (1 - 0.5 * (Math.log10(C_fraksi))));
                        if (horwitzInput) horwitzInput.value = horw.toFixed(2);
                    }
                }
            }
        }

        // Listener untuk KIM-05 Spektrofotometri
        document.addEventListener('input', function(e) {
            if (e.target && e.target.matches('input[name*="[tabel_preparasi]"], input[name*="[kadar_baku_persen]"], input[name*="[bobot_rata_rata_tablet]"], input[name*="[klaim_etiket]"]')) {
                hitungSpektrofotometri();
            }
        });

        // =========================================================================
        // Auto-Calculator Engine untuk Formulir Disolusi (KIM-06)
        // Sesuai Formula Resmi Excel: POM-06.02/CFM.01/SOP.01/F.01A.08
        // Fk = (V * Fu * Bb * Kb * [BM1/BM2] * 100%) / (Fb * Ab * Ke)
        // % Terlarut (Dx) = Fk * Au
        // =========================================================================
        function hitungDisolusi() {
            var vInput = document.querySelector('input[name*="[volume_media]"]');               // P12 (V)
            var keInput = document.querySelector('input[name*="[komposisi_etiket]"]');          // D10 (Ke)
            var kbInput = document.querySelector('input[name*="[kadar_baku]"]');                // P18 (Kb)
            var spInput = document.querySelector('input[name*="[susut_pengeringan]"]');         // P19
            var bbInput = document.querySelector('input[name*="[bobot_baku_mg]"]');             // P21 (Bb)
            var bmInput = document.querySelector('input[name*="[faktor_konversi_bm]"]');        // BM1/BM2
            var abInput = document.querySelector('input[name*="[hasil_ukur_baku_ab]"]');        // P24 (Ab)
            var fbInput = document.querySelector('input[name*="[faktor_pengenceran_baku]"]');   // P25 (Fb)
            var fuInput = document.querySelector('input[name*="[faktor_pengenceran_uji]"]');    // P26 (Fu)

            if (!vInput || !abInput) return;

            var V = parseFloat(vInput.value) || 0;
            var Ke = parseFloat(keInput ? keInput.value : 0) || 0;
            var rawKb = parseFloat(kbInput ? kbInput.value : 100) || 100;
            var SP = parseFloat(spInput ? spInput.value : 0) || 0;
            var Kb = (100 - SP) * (rawKb / 100); // Koreksi kemurnian baku
            var Bb = parseFloat(bbInput ? bbInput.value : 0) || 0;
            var bmFactor = parseFloat(bmInput ? bmInput.value : 1.0) || 1.0;
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

            var fkInput = document.querySelector('input[name*="[faktor_perkalian_fk]"]');
            if (fkInput && Fk > 0) fkInput.value = Fk.toFixed(4);

            // Hitung % Terlarut tiap bejana 1 - 6
            var terlarutVals = [];
            for (var b = 0; b < 6; b++) {
                var auEl = document.querySelector('input[name*="[tabel_s1][' + b + '][pengukuran_au]"]');
                var dxEl = document.querySelector('input[name*="[tabel_s1][' + b + '][persen_terlarut]"]');

                if (auEl) {
                    var Au = parseFloat(auEl.value) || 0;
                    if (Au > 0 && Fk > 0) {
                        var Dx = Fk * Au;
                        if (dxEl) dxEl.value = Dx.toFixed(2);
                        terlarutVals.push(Dx);
                    } else if (dxEl && parseFloat(dxEl.value)) {
                        terlarutVals.push(parseFloat(dxEl.value));
                    }
                }
            }

            // Statistik Rata-rata & Nilai Minimum
            if (terlarutVals.length >= 1) {
                var sum = terlarutVals.reduce(function(a, c) { return a + c; }, 0);
                var mean = sum / terlarutVals.length;
                var minVal = Math.min.apply(null, terlarutVals);

                var rataInput = document.querySelector('input[name*="[rata_rata_s1]"]');
                var minInput = document.querySelector('input[name*="[nilai_minimum_s1]"]');

                if (rataInput) rataInput.value = mean.toFixed(2);
                if (minInput) minInput.value = minVal.toFixed(2);
            }
        }

        // Listener untuk KIM-06 Disolusi
        document.addEventListener('input', function(e) {
            if (e.target && e.target.matches('input[name*="[tabel_s1]"], input[name*="[volume_media]"], input[name*="[komposisi_etiket]"], input[name*="[bobot_baku_mg]"], input[name*="[hasil_ukur_baku_ab]"], input[name*="[faktor_pengenceran_baku]"], input[name*="[faktor_pengenceran_uji]"]')) {
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
        // Rumus Resmi: Kadar = (Lu/Lb) * (Bb/Bu) * (Fu/Fb) * (Br/Ke) * Kb * 100%
        // RPD = |K1 - K2| / Kadar * 100%, Horwitz = 0.5 * 2^(1 - 0.5*log10(C))
        // =========================================================================
        function hitungHPLC() {
            // 1. Koreksi Kadar Kemurnian Baku
            var bakuKadarInput = document.querySelector('input[name*="[baku_kadar]"]');
            var bakuSpInput = document.querySelector('input[name*="[baku_sp]"]');
            var bakuTerkoreksiInput = document.querySelector('input[name*="[baku_kadar_terkoreksi]"]');

            var Kb = 0;
            if (bakuKadarInput) {
                var rawKadar = parseFloat(bakuKadarInput.value) || 0;
                var sp = parseFloat(bakuSpInput ? bakuSpInput.value : 0) || 0;
                Kb = (100 - sp) * (rawKadar / 100);
                if (bakuTerkoreksiInput && rawKadar > 0) {
                    bakuTerkoreksiInput.value = Kb.toFixed(2);
                }
            }

            // 2. Bobot Bersih Tiap Baris Tabel HPLC
            var hplcRows = document.querySelectorAll('tr:has(input[name*="[bobot_bersih]"])');
            if (!hplcRows || hplcRows.length === 0) {
                var allBb = document.querySelectorAll('input[name*="[bobot_bersih]"]');
                allBb.forEach(function(bEl) {
                    var tr = bEl.closest('tr');
                    if (tr) hitungBarisHPLC(tr);
                });
            } else {
                hplcRows.forEach(function(row) {
                    hitungBarisHPLC(row);
                });
            }

            // 3. Parameter Tambahan: Br (Bobot rata tablet) & Ke (Klaim etiket)
            var brInput = document.querySelector('input[name*="[bobot_rata_tablet]"]');
            var keInput = document.querySelector('input[name*="[klaim_etiket]"]');
            var Br = parseFloat(brInput ? brInput.value : 0) || 1;
            var Ke = parseFloat(keInput ? keInput.value : 0) || 1;

            // 4. Ambil Nilai Baku Pembanding (Row 0)
            var bbBakuEl = document.querySelector('input[name*="[tabel_hplc][0][bobot_bersih]"]');
            var fbBakuEl = document.querySelector('input[name*="[tabel_hplc][0][faktor_pengenceran]"]');
            var lbBakuEl = document.querySelector('input[name*="[tabel_hplc][0][respon_puncak]"]');

            var Bb = parseFloat(bbBakuEl ? bbBakuEl.value : 0) || 0;
            var Fb = parseFloat(fbBakuEl ? fbBakuEl.value : 1) || 1;
            var Lb = parseFloat(lbBakuEl ? lbBakuEl.value : 0) || 0;

            // 5. Hitung Replikasi 1 (Row 2) & Replikasi 2 (Row 3)
            var k1 = hitungKadarSatuSampel(2, Lb, Bb, Fb, Br, Ke, Kb);
            var k2 = hitungKadarSatuSampel(3, Lb, Bb, Fb, Br, Ke, Kb);

            var k1Input = document.querySelector('input[name*="[kadar_replikasi_1]"]');
            var k2Input = document.querySelector('input[name*="[kadar_replikasi_2]"]');

            if (k1Input && k1 > 0) k1Input.value = k1.toFixed(2);
            if (k2Input && k2 > 0) k2Input.value = k2.toFixed(2);

            // 6. Rata-rata Kadar, RPD & Horwitz
            if (k1 > 0 && k2 > 0) {
                var rataPersen = (k1 + k2) / 2;
                var rataMg = (rataPersen / 100) * Ke;
                var rpd = (Math.abs(k1 - k2) / rataPersen) * 100;

                // Syarat Horwitz: 0.5 * (2 ^ (1 - 0.5 * log10(C)))
                var C = rataPersen / 100;
                var horwitz = 0;
                if (C > 0) {
                    horwitz = 0.5 * Math.pow(2, (1 - (0.5 * (Math.log(C) / Math.LN10))));
                }

                var rataPersenInput = document.querySelector('input[name*="[kadar_rata_persen]"]');
                var rataMgInput = document.querySelector('input[name*="[kadar_rata_mg]"]');
                var rpdInput = document.querySelector('input[name*="[nilai_rpd]"]');
                var horwitzInput = document.querySelector('input[name*="[syarat_horwitz]"]');

                if (rataPersenInput) rataPersenInput.value = rataPersen.toFixed(2);
                if (rataMgInput) rataMgInput.value = rataMg.toFixed(2);
                if (rpdInput) rpdInput.value = rpd.toFixed(2);
                if (horwitzInput && horwitz > 0) horwitzInput.value = horwitz.toFixed(2);
            }
        }

        function hitungBarisHPLC(tr) {
            var wZatInput = tr.querySelector('input[name*="[wadah_zat]"]');
            var wSisaInput = tr.querySelector('input[name*="[wadah_sisa]"]');
            var bBersihInput = tr.querySelector('input[name*="[bobot_bersih]"]');

            if (wZatInput && wSisaInput && bBersihInput) {
                var wZat = parseFloat(wZatInput.value) || 0;
                var wSisa = parseFloat(wSisaInput.value) || 0;
                if (wZat > 0 && wSisa > 0 && wZat >= wSisa) {
                    var bBersih = wZat - wSisa;
                    bBersihInput.value = bBersih.toFixed(4);
                }
            }
        }

        function hitungKadarSatuSampel(rowIdx, Lb, Bb, Fb, Br, Ke, Kb) {
            if (Lb <= 0 || Bb <= 0 || Fb <= 0 || Ke <= 0 || Kb <= 0) return 0;

            var buEl = document.querySelector('input[name*="[tabel_hplc][' + rowIdx + '][bobot_bersih]"]');
            var fuEl = document.querySelector('input[name*="[tabel_hplc][' + rowIdx + '][faktor_pengenceran]"]');
            var luEl = document.querySelector('input[name*="[tabel_hplc][' + rowIdx + '][respon_puncak]"]');

            var Bu = parseFloat(buEl ? buEl.value : 0) || 0;
            var Fu = parseFloat(fuEl ? fuEl.value : 1) || 1;
            var Lu = parseFloat(luEl ? luEl.value : 0) || 0;

            if (Bu <= 0 || Lu <= 0) return 0;

            // Kadar = (Lu / Lb) * (Bb / Bu) * (Fu / Fb) * (Br / Ke) * Kb
            var kadar = (Lu / Lb) * (Bb / Bu) * (Fu / Fb) * (Br / Ke) * Kb;
            return kadar;
        }

        // Listener HPLC input
        document.addEventListener('input', function(e) {
            if (e.target && e.target.matches('input[name*="[baku_kadar]"], input[name*="[baku_sp]"], input[name*="[tabel_hplc]"], input[name*="[bobot_rata_tablet]"], input[name*="[klaim_etiket]"]')) {
                hitungHPLC();
            }
        });

        // =========================================================================
        // Auto-Calculator Engine untuk Formulir GC-MS (KIM-09)
        // Menghitung: Bobot Bersih = Wadah+Zat - Wadah+Sisa
        // Rasio Ion: R1 = B/A, R2 = C/A, R3 = D/A
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
                var wZat = parseFloat(wZatInput.value) || 0;
                var wSisa = parseFloat(wSisaInput.value) || 0;
                if (wZat > 0 && wSisa > 0 && wZat >= wSisa) {
                    bBersihInput.value = (wZat - wSisa).toFixed(4);
                }
            }

            // Rasio Ion
            var aInput = tr.querySelector('input[name*="[area_quantifier]"]');
            var bInput = tr.querySelector('input[name*="[area_qualifier_1]"]');
            var cInput = tr.querySelector('input[name*="[area_qualifier_2]"]');
            var dInput = tr.querySelector('input[name*="[area_qualifier_3]"]');

            var r1Input = tr.querySelector('input[name*="[rasio_ion_1]"]');
            var r2Input = tr.querySelector('input[name*="[rasio_ion_2]"]');
            var r3Input = tr.querySelector('input[name*="[rasio_ion_3]"]');

            var A = parseFloat(aInput ? aInput.value : 0) || 0;
            var B = parseFloat(bInput ? bInput.value : 0) || 0;
            var C = parseFloat(cInput ? cInput.value : 0) || 0;
            var D = parseFloat(dInput ? dInput.value : 0) || 0;

            if (A > 0) {
                if (r1Input && B > 0) r1Input.value = (B / A).toFixed(3);
                if (r2Input && C > 0) r2Input.value = (C / A).toFixed(3);
                if (r3Input && D > 0) r3Input.value = (D / A).toFixed(3);
            }
        }

        // Listener GC-MS input
        document.addEventListener('input', function(e) {
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
            var rows = document.querySelectorAll('tr:has(input[name*="[bobot_1]"])');
            var w = [null, null, null]; // W0, W1, W2

            for (var i = 0; i < 3; i++) {
                var b1Input = document.querySelector('input[name*="[tabel_penimbangan][' + i + '][bobot_1]"]');
                var b2Input = document.querySelector('input[name*="[tabel_penimbangan][' + i + '][bobot_2]"]');
                var rataInput = document.querySelector('input[name*="[tabel_penimbangan][' + i + '][rata_rata]"]');

                if (b1Input) {
                    var v1 = parseFloat(b1Input.value);
                    var v2 = parseFloat(b2Input ? b2Input.value : NaN);

                    if (!isNaN(v1) && !isNaN(v2)) {
                        var mean = (v1 + v2) / 2;
                        if (rataInput) rataInput.value = mean.toFixed(4);
                        w[i] = mean;
                    } else if (!isNaN(v1)) {
                        if (rataInput) rataInput.value = v1.toFixed(4);
                        w[i] = v1;
                    }
                }
            }

            var w0 = w[0];
            var w1 = w[1];
            var w2 = w[2];

            var zatEl = document.querySelector('input[name*="[bobot_zat_bersih]"]');
            var airEl = document.querySelector('input[name*="[bobot_air_bersih]"]');
            var bjEl = document.querySelector('input[name*="[nilai_bobot_jenis]"]');

            if (w0 !== null && w1 !== null) {
                var bZat = w1 - w0;
                if (zatEl) zatEl.value = bZat.toFixed(4);
            }

            if (w0 !== null && w2 !== null) {
                var bAir = w2 - w0;
                if (airEl) airEl.value = bAir.toFixed(4);
            }

            if (w0 !== null && w1 !== null && w2 !== null) {
                var bZat = w1 - w0;
                var bAir = w2 - w0;
                if (bAir > 0 && bjEl) {
                    bjEl.value = (bZat / bAir).toFixed(4);
                }
            }
        }

        // Listener Bobot Jenis input
        document.addEventListener('input', function(e) {
            if (e.target && e.target.matches('input[name*="[tabel_penimbangan]"]')) {
                hitungBobotJenis();
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
        return confirm('Apakah Anda yakin ingin menyimpan seluruh hasil pengujian sesi ini dan meneruskannya ke Verifikasi Penyelia?');
    }

    function konfirmasiGantiZatAktif(targetUrl) {
        return confirm('Apakah Anda ingin mengganti pilihan Zat Aktif atau parameter pengujian untuk sesi ini?\n\nSemua data yang telah Anda isi pada parameter yang tetap dipilih akan tersimpan aman dan tidak akan hilang.');
    }
</script>