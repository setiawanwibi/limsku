<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
    /* =========================================================
       HALAMAN PILIH ZAT AKTIF & PARAMETER MONOGRAFI (LIMSKU)
       Desain Modern & Clean Selaras dengan LIMSKU Design System
    ========================================================= */
    .pilih-form-page {
        width: 100%;
        max-width: 1180px;
        margin: 0 auto;
        padding: 6px 0 40px;
        color: #1e293b;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }

    /* Hero / Sample Context Header */
    .hero-sample-card {
        background: #ffffff;
        border: 1px solid #edf2f7;
        border-radius: 14px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
        padding: 22px 26px;
        margin-bottom: 22px;
        position: relative;
        overflow: hidden;
    }
    .hero-sample-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: <?php echo ($jenis_pengujian === 'Kimia' ? '#0284c7' : '#10b981'); ?>;
    }
    .hero-badge-bidang {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        border-radius: 999px;
        font-size: 0.76rem;
        font-weight: 700;
        letter-spacing: 0.02em;
        background: <?php echo ($jenis_pengujian === 'Kimia' ? '#e0f2fe' : '#dcfce7'); ?>;
        color: <?php echo ($jenis_pengujian === 'Kimia' ? '#0369a1' : '#15803d'); ?>;
        border: 1px solid <?php echo ($jenis_pengujian === 'Kimia' ? '#bae6fd' : '#86efac'); ?>;
    }
    .sample-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        padding: 4px 12px;
        border-radius: 8px;
        font-size: 0.80rem;
        color: #64748b;
    }
    .sample-chip strong {
        color: #1e293b;
    }

    .btn-baku-back {
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #475569 !important;
        font-size: 0.80rem;
        font-weight: 600;
        border-radius: 999px;
        padding: 0.45rem 1.15rem;
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        text-decoration: none !important;
        transition: all 0.2s ease;
    }
    .btn-baku-back:hover {
        background: #f1f5f9;
        color: #0f172a !important;
        border-color: #cbd5e1;
    }

    /* Section Cards */
    .step-card {
        background: #ffffff;
        border: 1px solid #edf2f7;
        border-radius: 14px;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
        margin-bottom: 22px;
        position: relative;
        z-index: 1;
    }
    .step-card-za {
        overflow: visible !important;
        position: relative;
        z-index: 10 !important;
    }
    .step-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 18px 24px;
        border-bottom: 1px solid #edf2f7;
        background: #ffffff;
        border-top-left-radius: 14px;
        border-top-right-radius: 14px;
    }
    .step-title-wrap {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .step-number {
        width: 32px;
        height: 32px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 0.90rem;
        background: <?php echo ($jenis_pengujian === 'Kimia' ? 'linear-gradient(135deg, #0284c7, #0369a1)' : 'linear-gradient(135deg, #10b981, #047857)'); ?>;
        color: #ffffff;
        flex-shrink: 0;
        box-shadow: 0 2px 6px <?php echo ($jenis_pengujian === 'Kimia' ? 'rgba(2, 132, 199, 0.25)' : 'rgba(16, 185, 129, 0.25)'); ?>;
    }
    .step-title {
        margin: 0;
        font-size: 0.98rem;
        font-weight: 700;
        color: #0f172a;
    }
    .step-subtitle {
        margin: 2px 0 0;
        font-size: 0.78rem;
        color: #64748b;
    }

    /* Step 1: Zat Aktif Box & Searchable Combobox */
    .zat-aktif-select-wrap {
        padding: 22px 24px;
        overflow: visible !important;
    }
    .searchable-za-container {
        position: relative;
        width: 100%;
        z-index: 105;
    }
    .select-za-control {
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        padding: 10px 16px;
        font-size: 0.88rem;
        font-weight: 600;
        color: #1e293b;
        transition: all 0.2s ease;
        background-color: #f8fafc;
    }
    .select-za-control:focus {
        background-color: #ffffff;
        border-color: <?php echo ($jenis_pengujian === 'Kimia' ? '#38bdf8' : '#34d399'); ?>;
        box-shadow: 0 0 0 3px <?php echo ($jenis_pengujian === 'Kimia' ? 'rgba(56, 189, 248, 0.15)' : 'rgba(52, 211, 153, 0.15)'); ?>;
        color: #0f172a;
    }
    .search-icon-left {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 1.05rem;
        pointer-events: none;
        z-index: 5;
    }
    .btn-clear-search {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        background: transparent;
        border: 0;
        color: #94a3b8;
        font-size: 1.15rem;
        padding: 4px;
        cursor: pointer;
        z-index: 5;
        display: none;
        border-radius: 50%;
        line-height: 1;
        transition: color 0.15s ease;
    }
    .btn-clear-search:hover {
        color: #ef4444;
    }
    .za-dropdown-menu {
        position: absolute;
        top: calc(100% + 6px);
        left: 0;
        right: 0;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.12);
        z-index: 99999 !important;
        display: none;
        overflow: hidden;
    }
    .za-dropdown-list {
        max-height: 280px;
        overflow-y: auto;
        overscroll-behavior: contain;
    }
    .za-dropdown-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 9px 16px;
        background: #f8fafc;
        border-bottom: 1px solid #edf2f7;
        font-size: 0.72rem;
        color: #64748b;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .za-dropdown-item {
        padding: 10px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        font-size: 0.86rem;
        font-weight: 600;
        color: #334155;
        cursor: pointer;
        border-bottom: 1px solid #f8fafc;
        transition: all 0.15s ease;
    }
    .za-dropdown-item:last-child {
        border-bottom: 0;
    }
    .za-dropdown-item:hover,
    .za-dropdown-item.is-highlighted {
        background: <?php echo ($jenis_pengujian === 'Kimia' ? '#f0f9ff' : '#ecfdf5'); ?>;
        color: <?php echo ($jenis_pengujian === 'Kimia' ? '#0369a1' : '#047857'); ?>;
    }
    .za-dropdown-item.is-selected {
        background: <?php echo ($jenis_pengujian === 'Kimia' ? '#e0f2fe' : '#d1fae5'); ?>;
        color: <?php echo ($jenis_pengujian === 'Kimia' ? '#0284c7' : '#059669'); ?>;
        font-weight: 700;
    }
    .za-dropdown-item mark {
        background: #fef08a;
        color: #854d0e;
        padding: 1px 3px;
        border-radius: 3px;
    }
    .za-empty-state {
        padding: 24px 16px;
        text-align: center;
        color: #94a3b8;
        font-size: 0.82rem;
    }
    .za-empty-state i {
        font-size: 1.75rem;
        display: block;
        margin-bottom: 6px;
        color: #cbd5e1;
    }
    .btn-muat-parameter {
        border-radius: 10px;
        padding: 10px 20px;
        font-size: 0.84rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s ease;
        background: #0284c7;
        border: 1px solid #0284c7;
        color: #ffffff;
    }
    .btn-muat-parameter:hover {
        background: #0369a1;
        border-color: #0369a1;
        color: #ffffff;
        box-shadow: 0 3px 8px rgba(2, 132, 199, 0.25);
    }

    /* Table Styling */
    .parameter-table thead th {
        background: #f8fafc;
        color: #475569;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 12px 14px;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
        vertical-align: middle;
    }
    .parameter-table tbody td {
        padding: 12px 14px;
        font-size: 0.82rem;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
        transition: background 0.15s ease;
    }
    .parameter-table tbody tr:hover td {
        background-color: #f8fafc;
    }
    .parameter-table tbody tr.row-checked td {
        background-color: #f0f9ff;
    }

    /* Modern Custom Checkbox */
    .custom-param-checkbox {
        width: 1.2rem;
        height: 1.2rem;
        cursor: pointer;
        border: 1.5px solid #cbd5e1;
        border-radius: 6px;
        transition: all 0.15s ease;
    }
    .custom-param-checkbox:checked {
        background-color: <?php echo ($jenis_pengujian === 'Kimia' ? '#0284c7' : '#10b981'); ?>;
        border-color: <?php echo ($jenis_pengujian === 'Kimia' ? '#0284c7' : '#10b981'); ?>;
    }

    /* Badges */
    .badge-kode-metode {
        display: inline-block;
        background: #f1f5f9;
        color: #64748b;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 2px 7px;
        font-size: 0.72rem;
        font-family: 'JetBrains Mono', Consolas, monospace;
        font-weight: 600;
    }
    .badge-metode-analisa {
        display: inline-block;
        background: #f8fafc;
        color: #334155;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 3px 8px;
        font-size: 0.74rem;
        font-weight: 600;
    }
    .badge-pustaka {
        display: inline-block;
        background: #f0f9ff;
        color: #0369a1;
        border: 1px solid #bae6fd;
        border-radius: 6px;
        padding: 2px 7px;
        font-size: 0.72rem;
        font-weight: 600;
        white-space: nowrap;
    }
    .badge-lcp-default {
        background: #f0fdf4;
        color: #15803d;
        border: 1px solid #bbf7d0;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 0.70rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        white-space: nowrap;
    }
    .badge-lcp-custom {
        background: #fefce8;
        color: #a16207;
        border: 1px solid #fef08a;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 0.70rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        white-space: nowrap;
    }

    /* Template Select */
    .select-template-lcp {
        font-size: 0.80rem;
        font-weight: 600;
        color: #334155;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        padding: 6px 10px;
        transition: all 0.2s ease;
        background-color: #f8fafc;
    }
    .select-template-lcp:focus {
        background-color: #ffffff;
        border-color: #38bdf8;
        box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.15);
        color: #0f172a;
    }

    /* Callout Card */
    .callout-info-card {
        background: #f0f9ff;
        border: 1px solid #e0f2fe;
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 22px;
        display: flex;
        align-items: flex-start;
        gap: 14px;
    }

    /* Sticky Bottom Action Dock */
    .action-dock-card {
        background: #ffffff;
        border: 1px solid #edf2f7;
        border-radius: 14px;
        box-shadow: 0 2px 10px rgba(15, 23, 42, 0.05);
        padding: 16px 22px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 14px;
    }
    .btn-submit-action {
        border-radius: 999px;
        font-weight: 600;
        font-size: 0.84rem;
        padding: 0.55rem 1.6rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #16a34a;
        border: 1px solid #16a34a;
        color: #ffffff;
        transition: all 0.2s ease;
    }
    .btn-submit-action:hover:not(:disabled) {
        background: #15803d;
        border-color: #15803d;
        color: #ffffff;
        box-shadow: 0 3px 8px rgba(22, 163, 74, 0.25);
    }
    .btn-submit-action:disabled {
        background: #cbd5e1;
        border-color: #cbd5e1;
        color: #ffffff;
        cursor: not-allowed;
    }

    /* Modal Tambah Master Styling */
    #modalTambahParameterMaster .modal-content {
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 20px 40px -15px rgba(15, 23, 42, 0.15);
        overflow: hidden;
    }
    #modalTambahParameterMaster .modal-header {
        background: #ffffff;
        border-bottom: 1px solid #f1f5f9;
        padding: 1.1rem 1.4rem;
    }
    #modalTambahParameterMaster .modal-body {
        background: #ffffff;
        padding: 1.25rem 1.4rem;
    }
    #modalTambahParameterMaster .input-group-search {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        overflow: hidden;
        transition: all 0.2s ease;
    }
    #modalTambahParameterMaster .input-group-search:focus-within {
        background: #ffffff;
        border-color: #38bdf8;
        box-shadow: 0 0 0 3px rgba(56, 189, 248, 0.15);
    }
    #modalTambahParameterMaster .input-group-search .input-group-text {
        background: transparent;
        border: none;
        color: #94a3b8;
        padding-left: 14px;
    }
    #modalTambahParameterMaster .input-group-search .form-control {
        background: transparent;
        border: none;
        box-shadow: none;
        font-size: 0.84rem;
        color: #1e293b;
        padding: 10px 14px 10px 4px;
    }
    #modalTambahParameterMaster .input-group-search .form-control::placeholder {
        color: #94a3b8;
    }
    #tabelMasterModal thead th {
        background: #f8fafc;
        color: #475569;
        font-size: 0.73rem;
        font-weight: 700;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        border-bottom: 1px solid #e2e8f0;
        padding: 10px 14px;
        white-space: nowrap;
    }
    #tabelMasterModal tbody td {
        padding: 12px 14px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }
    #tabelMasterModal tbody tr:hover {
        background-color: #f8fafc;
    }
    .btn-tambah-master {
        background: #0284c7;
        border: 1px solid #0284c7;
        color: #ffffff;
        font-size: 0.78rem;
        font-weight: 600;
        border-radius: 999px;
        padding: 5px 14px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        transition: all 0.2s ease;
    }
    .btn-tambah-master:hover {
        background: #0369a1;
        border-color: #0369a1;
        color: #ffffff;
        box-shadow: 0 2px 6px rgba(2, 132, 199, 0.25);
    }
    #modalTambahParameterMaster .modal-footer {
        background: #f8fafc;
        border-top: 1px solid #f1f5f9;
        padding: 0.85rem 1.4rem;
    }
    #modalTambahParameterMaster .btn-modal-close {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        color: #475569;
        font-weight: 600;
        font-size: 0.80rem;
        border-radius: 999px;
        padding: 6px 18px;
        transition: all 0.2s ease;
    }
    #modalTambahParameterMaster .btn-modal-close:hover {
        background: #f1f5f9;
        color: #1e293b;
        border-color: #cbd5e1;
    }

    @media (max-width: 767.98px) {
        .hero-sample-card {
            padding: 16px;
        }
        .hero-sample-card .d-flex.justify-content-between {
            flex-direction: column !important;
            gap: 12px;
        }
        .step-header {
            padding: 12px 14px;
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 8px;
        }
        .action-dock-card {
            flex-direction: column !important;
            align-items: stretch !important;
            gap: 10px;
            padding: 12px 14px;
        }
        .btn-submit-action {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="pilih-form-page">

    <!-- Flash Messages -->
    <?php if ($this->session->flashdata('pesan_gagal')): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                <div><?php echo html_escape($this->session->flashdata('pesan_gagal')); ?></div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- 1. Hero Sample Header -->
    <div class="hero-sample-card">
        <div class="d-flex align-items-start justify-content-between flex-wrap gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="hero-badge-bidang">
                        <?php if ($jenis_pengujian === 'Kimia'): ?>
                            <i class="bi bi-droplet-half"></i> Pengujian Kimia Laboratorium
                        <?php else: ?>
                            <i class="bi bi-virus"></i> Pengujian Mikrobiologi Laboratorium
                        <?php endif; ?>
                    </span>
                    <span class="text-muted small">• Terdaftar di Master Metode Monografi</span>
                </div>
                <h4 class="fw-bold mb-2 text-dark">
                    Pilih Zat Aktif &amp; Parameter Monografi Pengujian <?php echo html_escape($jenis_pengujian); ?>
                </h4>
                <div class="d-flex align-items-center flex-wrap gap-2 pt-1">
                    <span class="sample-chip">
                        <i class="bi bi-capsule text-primary"></i>
                        Sampel: <strong><?php echo html_escape($sampel['nama_sampel']); ?></strong>
                    </span>
                    <span class="sample-chip">
                        <i class="bi bi-upc-scan text-secondary"></i>
                        Kode: <strong><?php echo html_escape($sampel['kode_sampel_manual'] ?: 'SMP-' . $sampel['id']); ?></strong>
                    </span>
                    <?php if (!empty($sampel['nomor_bets'])): ?>
                        <span class="sample-chip">
                            <i class="bi bi-tag text-muted"></i>
                            Bets: <strong><?php echo html_escape($sampel['nomor_bets']); ?></strong>
                        </span>
                    <?php endif; ?>
                    <?php if (!empty($sampel['bentuk_sediaan'])): ?>
                        <span class="sample-chip">
                            <i class="bi bi-box-seam text-muted"></i>
                            Sediaan: <strong><?php echo html_escape($sampel['bentuk_sediaan']); ?></strong>
                        </span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="text-md-end">
                <a href="<?php echo site_url('pengujian/pilih_jenis/' . $sampel['id']); ?>" class="btn-baku-back">
                    <i class="bi bi-arrow-left me-1"></i> Ganti Bidang
                </a>
            </div>
        </div>
    </div>

    <!-- 2. Step 1: Pemilihan Zat Aktif Monografi -->
    <div class="step-card step-card-za">
        <div class="step-header">
            <div class="step-title-wrap">
                <div class="step-number">1</div>
                <div>
                    <h5 class="step-title">Tentukan Zat Aktif Monografi Obat</h5>
                    <p class="step-subtitle">Setiap sesi pengujian difokuskan pada tepat 1 zat aktif utama sesuai monografi standar Farmakope.</p>
                </div>
            </div>
            <span class="badge rounded-pill" style="font-size: 0.72rem; background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; font-weight: 600;">
                <i class="bi bi-database me-1"></i> <?php echo count($daftar_zat_aktif); ?> Zat Aktif Tersedia
            </span>
        </div>

        <div class="zat-aktif-select-wrap">
            <form method="get" action="<?php echo site_url('pengujian/pilih_form/' . $sampel['id']); ?>" id="form-pilih-zat-aktif">
                <input type="hidden" name="jenis" value="<?php echo html_escape($jenis_pengujian); ?>">
                <?php if (!empty($session_id_edit)): ?>
                    <input type="hidden" name="session_id" value="<?php echo (int)$session_id_edit; ?>">
                <?php endif; ?>
                <!-- Value aktual yang dikirimkan -->
                <input type="hidden" name="zat_aktif" id="hidden_zat_aktif" value="<?php echo html_escape($zat_aktif_terpilih); ?>">

                <div class="row align-items-center g-3">
                    <div class="col-lg-9 col-md-8">
                        <div class="searchable-za-container" id="za_search_container">
                            <i class="bi bi-search search-icon-left"></i>
                            <input 
                                type="text" 
                                id="input_search_za" 
                                class="form-control select-za-control ps-5 pe-5 shadow-sm" 
                                placeholder="Ketik nama zat aktif untuk mencari (contoh: Salbutamol, Paracetamol, Aluminium)..." 
                                value="<?php echo html_escape($zat_aktif_terpilih); ?>"
                                autocomplete="off"
                                spellcheck="false"
                            >
                            <button type="button" class="btn-clear-search" id="btn_clear_za" title="Bersihkan pencarian">
                                <i class="bi bi-x-circle-fill"></i>
                            </button>

                            <!-- Dropdown Live Search List -->
                            <div class="za-dropdown-menu shadow" id="za_dropdown_menu">
                                <div class="za-dropdown-header">
                                    <span><i class="bi bi-funnel me-1"></i> Hasil Pencarian Zat Aktif</span>
                                    <span class="badge bg-secondary-subtle text-secondary" id="za_counter_badge"><?php echo count($daftar_zat_aktif); ?> zat aktif</span>
                                </div>
                                <div class="za-dropdown-list" id="za_dropdown_list">
                                    <?php foreach ($daftar_zat_aktif as $za): ?>
                                        <?php $isSelected = ($zat_aktif_terpilih === $za['nama_zat_aktif']); ?>
                                        <div class="za-dropdown-item <?php echo $isSelected ? 'is-selected' : ''; ?>" 
                                             data-value="<?php echo html_escape($za['nama_zat_aktif']); ?>"
                                             data-sediaan="<?php echo html_escape($za['daftar_sediaan'] ?? ''); ?>">
                                            <div class="d-flex align-items-center justify-content-between w-100 gap-2">
                                                <div>
                                                    <span class="za-name fw-semibold"><?php echo html_escape($za['nama_zat_aktif']); ?></span>
                                                    <?php if (!empty($za['daftar_sediaan'])): ?>
                                                        <small class="text-muted d-block" style="font-size: 0.76rem; font-weight: normal;">
                                                            <i class="bi bi-capsule me-1 text-primary"></i><?php echo html_escape($za['daftar_sediaan']); ?>
                                                        </small>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="d-flex align-items-center gap-1 flex-shrink-0">
                                                    <?php if (isset($za['total_parameter'])): ?>
                                                        <span class="badge bg-light text-secondary border rounded-pill px-2 py-1 small" style="font-size: 0.72rem;">
                                                            <?php echo (int)$za['total_parameter']; ?> parameter
                                                        </span>
                                                    <?php endif; ?>
                                                    <?php if ($isSelected): ?>
                                                        <span class="badge bg-primary text-white rounded-pill px-2 py-1 small">
                                                            <i class="bi bi-check-circle-fill me-1"></i> Terpilih
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="za-empty-state d-none" id="za_empty_state">
                                    <i class="bi bi-search"></i>
                                    <span>Zat aktif tidak ditemukan</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4">
                        <button type="submit" class="btn btn-primary btn-muat-parameter w-100 shadow-sm" id="btn_submit_za">
                            <i class="bi bi-arrow-clockwise"></i> Muat Parameter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php if (!empty($zat_aktif_terpilih)): ?>
        <!-- 3. Step 2: Pemilihan Parameter Uji Kritis Sesuai Monografi -->
        <form action="<?php echo site_url('pengujian/mulai_sesi/' . $sampel['id']); ?>" method="post" id="form-pilih-parameter">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
            <input type="hidden" name="jenis_pengujian" value="<?php echo html_escape($jenis_pengujian); ?>">
            <input type="hidden" name="nama_zat_aktif" value="<?php echo html_escape($zat_aktif_terpilih); ?>">
            <?php if (!empty($session_id_edit)): ?>
                <input type="hidden" name="session_id" value="<?php echo (int)$session_id_edit; ?>">
            <?php endif; ?>

            <?php if (!empty($session_id_edit)): ?>
                <div class="alert alert-info border-info-subtle d-flex align-items-center gap-2 mb-3 py-2 px-3">
                    <i class="bi bi-info-circle-fill text-info fs-5"></i>
                    <div>
                        <strong>Mode Penggantian Pilihan Sesi:</strong> Anda sedang mengubah zat aktif / form parameter untuk sesi pengujian sampel ini. 
                        Data input pada parameter yang tetap Anda pilih akan <strong>dipertahankan secara utuh</strong> dan tidak akan hilang.
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($jenis_pengujian === 'Mikrobiologi' && !empty($rekomendasi_mikro)): ?>
                <div class="alert <?php echo $rekomendasi_mikro['alert_class']; ?> d-flex align-items-start gap-3 p-3 mb-3 rounded-3 shadow-sm border">
                    <div class="fs-3 flex-shrink-0">
                        <?php if ($rekomendasi_mikro['kategori'] === 'oral_non_antibiotik'): ?>
                            <i class="bi bi-shield-check text-success"></i>
                        <?php elseif ($rekomendasi_mikro['kategori'] === 'antibiotik_nystatin'): ?>
                            <i class="bi bi-capsule text-primary"></i>
                        <?php elseif ($rekomendasi_mikro['kategori'] === 'antibiotik_lainnya'): ?>
                            <i class="bi bi-exclamation-triangle-fill text-warning"></i>
                        <?php else: ?>
                            <i class="bi bi-info-circle-fill text-secondary"></i>
                        <?php endif; ?>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-1">
                            <h6 class="fw-bold mb-0 text-dark"><?php echo html_escape($rekomendasi_mikro['judul']); ?></h6>
                            <span class="badge bg-white text-dark border px-2 py-1 rounded-pill small">
                                <i class="bi bi-sliders me-1 text-primary"></i> Mode Fleksibel
                            </span>
                        </div>
                        <p class="mb-2 text-muted small" style="line-height: 1.5;">
                            <?php echo html_escape($rekomendasi_mikro['pesan']); ?>
                        </p>
                        <?php if (!empty($rekomendasi_mikro['btn_label'])): ?>
                            <button type="button" class="btn btn-sm btn-<?php echo $rekomendasi_mikro['badge_tipe']; ?> fw-semibold shadow-sm" id="btn-apply-rekomendasi">
                                <i class="bi bi-magic me-1"></i> <?php echo html_escape($rekomendasi_mikro['btn_label']); ?>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="step-card">
                <div class="step-header">
                    <div class="step-title-wrap">
                        <div class="step-number">2</div>
                        <div>
                            <h5 class="step-title">
                                Pilih Parameter Uji Kritis Sesuai Monografi: 
                                <span class="text-primary"><?php echo html_escape($zat_aktif_terpilih); ?></span>
                            </h5>
                            <p class="step-subtitle">Centang parameter yang akan diuji. Analis dapat menyesuaikan Form LCP pada kolom instrumen sebelum pengujian dimulai.</p>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-sm rounded-pill px-3 fw-semibold" style="font-size: 0.78rem; background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd;" data-bs-toggle="modal" data-bs-target="#modalTambahParameterMaster" title="Pilih dan tambahkan parameter lain dari master data laboratorium">
                            <i class="bi bi-plus-circle me-1"></i> + Tambah Parameter dari Master
                        </button>
                        <button type="button" class="btn btn-sm rounded-pill px-3 fw-semibold" style="font-size: 0.78rem; background: #f8fafc; color: #475569; border: 1px solid #e2e8f0;" id="btn-select-all">
                            <i class="bi bi-check-all me-1"></i> Pilih Semua
                        </button>
                        <button type="button" class="btn btn-sm rounded-pill px-3 fw-semibold d-none" style="font-size: 0.78rem; background: #f8fafc; color: #475569; border: 1px solid #e2e8f0;" id="btn-deselect-all">
                            <i class="bi bi-x-lg me-1"></i> Batal Semua
                        </button>
                        <span class="badge rounded-pill px-3 py-2 text-white" style="font-size: 0.76rem; background: #64748b; font-weight: 700;" id="badge-counter">0 parameter dipilih</span>
                    </div>
                </div>

                <?php if (!empty($parameter_monografi)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 parameter-table">
                            <thead>
                                <tr>
                                    <th width="4%" class="text-center">PILIH</th>
                                    <th width="24%">PARAMETER UJI KRITIS</th>
                                    <th width="15%">METODE ANALISA</th>
                                    <th width="16%">PERSYARATAN / BAKU MUTU</th>
                                    <th width="12%">PUSTAKA ACUAN</th>
                                    <th width="29%">FORM LCP PENGUJIAN (FLEKSIBEL)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($parameter_monografi as $p): ?>
                                    <?php 
                                        $is_mapped = !empty($p['template_id']);
                                        $is_checked = !empty($sesi_method_ids) && in_array((int)$p['id'], $sesi_method_ids);

                                        // Evaluasi apakah baris ini adalah parameter rekomendasi Farmakope
                                        $is_rekomendasi = false;
                                        $rekomendasi_label = '';
                                        if ($jenis_pengujian === 'Mikrobiologi' && !empty($rekomendasi_mikro)) {
                                            $pu_lower = strtolower($p['parameter_uji']);
                                            if ($rekomendasi_mikro['kategori'] === 'oral_non_antibiotik') {
                                                if (strpos($pu_lower, 'lempeng') !== false || strpos($pu_lower, 'alt') !== false) {
                                                    $is_rekomendasi = true;
                                                    $rekomendasi_label = 'Standar Oral (ALT)';
                                                } elseif (strpos($pu_lower, 'kapang') !== false || strpos($pu_lower, 'khamir') !== false || strpos($pu_lower, 'akk') !== false || (strpos($pu_lower, 'hitung') !== false && !empty($p['template_id']) && $p['template_id'] == 37)) {
                                                    $is_rekomendasi = true;
                                                    $rekomendasi_label = 'Standar Oral (AKK)';
                                                } elseif (strpos($pu_lower, 'spesifik') !== false || strpos($pu_lower, 'coli') !== false || strpos($pu_lower, 'ecoli') !== false || strpos($pu_lower, 'escherichia') !== false) {
                                                    $is_rekomendasi = true;
                                                    $rekomendasi_label = 'Standar Oral (E. coli)';
                                                }
                                            } elseif ($rekomendasi_mikro['kategori'] === 'topikal') {
                                                if (strpos($pu_lower, 'lempeng') !== false || strpos($pu_lower, 'alt') !== false) {
                                                    $is_rekomendasi = true;
                                                    $rekomendasi_label = 'Standar Topikal (ALT)';
                                                } elseif (strpos($pu_lower, 'kapang') !== false || strpos($pu_lower, 'khamir') !== false || strpos($pu_lower, 'akk') !== false) {
                                                    $is_rekomendasi = true;
                                                    $rekomendasi_label = 'Standar Topikal (AKK)';
                                                } elseif (strpos($pu_lower, 'pseudo') !== false || strpos($pu_lower, 'aeruginosa') !== false) {
                                                    $is_rekomendasi = true;
                                                    $rekomendasi_label = 'Standar Topikal (Pseudomonas)';
                                                } elseif (strpos($pu_lower, 'staph') !== false || strpos($pu_lower, 'aures') !== false) {
                                                    $is_rekomendasi = true;
                                                    $rekomendasi_label = 'Standar Topikal (Staphylococcus)';
                                                }
                                            } elseif ($rekomendasi_mikro['kategori'] === 'rektal') {
                                                if (strpos($pu_lower, 'lempeng') !== false || strpos($pu_lower, 'alt') !== false) {
                                                    $is_rekomendasi = true;
                                                    $rekomendasi_label = 'Standar Rektal (ALT)';
                                                } elseif (strpos($pu_lower, 'kapang') !== false || strpos($pu_lower, 'khamir') !== false || strpos($pu_lower, 'akk') !== false) {
                                                    $is_rekomendasi = true;
                                                    $rekomendasi_label = 'Standar Rektal (AKK)';
                                                }
                                            } elseif ($rekomendasi_mikro['kategori'] === 'antibiotik_nystatin') {
                                                if (strpos($pu_lower, 'potensi') !== false) {
                                                    $is_rekomendasi = true;
                                                    $rekomendasi_label = 'Standar Nistatin (Potensi)';
                                                }
                                            }
                                        }
                                    ?>
                                    <tr id="row_param_<?php echo $p['id']; ?>" class="<?php echo $is_checked ? 'table-active' : ''; ?>">
                                        <td class="text-center">
                                             <input class="form-check-input custom-param-checkbox form-check-parameter" 
                                                    type="checkbox" 
                                                    name="method_ids[]" 
                                                    value="<?php echo $p['id']; ?>" 
                                                    id="chk_metode_<?php echo $p['id']; ?>"
                                                    data-is-rekomendasi="<?php echo $is_rekomendasi ? '1' : '0'; ?>"
                                                    <?php echo $is_checked ? 'checked' : ''; ?>>
                                        </td>
                                        <td>
                                            <label for="chk_metode_<?php echo $p['id']; ?>" class="d-block cursor-pointer mb-0">
                                                <div class="d-flex align-items-center flex-wrap gap-2 mb-1">
                                                    <strong class="text-dark" style="font-size: 0.88rem;">
                                                        <?php echo html_escape($p['parameter_uji']); ?>
                                                    </strong>
                                                    <?php if ($is_rekomendasi): ?>
                                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill" style="font-size: 0.72rem;">
                                                            <i class="bi bi-star-fill text-warning me-1"></i><?php echo html_escape($rekomendasi_label); ?>
                                                        </span>
                                                    <?php endif; ?>
                                                    <?php if (!empty($p['bentuk_sediaan'])): ?>
                                                        <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill" style="font-size: 0.72rem;">
                                                            <i class="bi bi-capsule me-1"></i><?php echo html_escape($p['bentuk_sediaan']); ?><?php echo !empty($p['jenis_sediaan']) ? ' (' . html_escape($p['jenis_sediaan']) . ')' : ''; ?>
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                                <span class="badge-kode-metode">
                                                    <i class="bi bi-bookmark me-1"></i><?php echo html_escape($p['kode_metode']); ?>
                                                </span>
                                            </label>
                                        </td>
                                        <td>
                                            <span class="badge-metode-analisa">
                                                <?php echo html_escape($p['metode_analisa'] ?: '-'); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-muted fw-medium" style="font-size: 0.82rem;">
                                                <?php echo html_escape($p['persyaratan'] ?: '-'); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if (!empty($p['pustaka_acuan'])): ?>
                                                <span class="badge-pustaka">
                                                    <i class="bi bi-book me-1"></i><?php echo html_escape($p['pustaka_acuan']); ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <select class="form-select select-template-lcp select-custom-template" 
                                                        name="custom_template_ids[<?php echo $p['id']; ?>]" 
                                                        id="sel_lcp_<?php echo $p['id']; ?>"
                                                        data-default-id="<?php echo !empty($p['template_id']) ? $p['template_id'] : ''; ?>">
                                                    <option value="">-- Pilih Form LCP <?php echo html_escape($jenis_pengujian); ?> --</option>
                                                    <?php if (!empty($daftar_template_bidang)): ?>
                                                        <?php foreach ($daftar_template_bidang as $tpl_b): ?>
                                                            <?php 
                                                                $is_session_custom = !empty($sesi_custom_templates[(int)$p['id']]) && ($sesi_custom_templates[(int)$p['id']] == $tpl_b['id']);
                                                                $is_default = empty($sesi_custom_templates[(int)$p['id']]) && ($p['template_id'] == $tpl_b['id']);
                                                                $is_fallback_select = false;
                                                                if (!$is_mapped && empty($sesi_custom_templates[(int)$p['id']])) {
                                                                    if ($jenis_pengujian === 'Mikrobiologi') {
                                                                        if ((stripos($p['parameter_uji'], 'lempeng') !== false || stripos($p['parameter_uji'], 'alt') !== false) && $tpl_b['kode_template'] === 'MIK-01') {
                                                                            $is_fallback_select = true;
                                                                        } elseif ((stripos($p['parameter_uji'], 'hitung') !== false || stripos($p['parameter_uji'], 'kapang') !== false || stripos($p['parameter_uji'], 'khamir') !== false) && $tpl_b['kode_template'] === 'MIK-02') {
                                                                            $is_fallback_select = true;
                                                                        } elseif ($tpl_b['kode_template'] === 'MIK-04' && stripos($p['parameter_uji'], 'spesifik') !== false) {
                                                                            $is_fallback_select = true;
                                                                        } elseif ($tpl_b['kode_template'] === 'MIK-03' && stripos($p['parameter_uji'], 'potensi') !== false) {
                                                                            $is_fallback_select = true;
                                                                        } elseif ($tpl_b['kode_template'] === 'MIK-05' && stripos($p['parameter_uji'], 'endotoksin') !== false) {
                                                                            $is_fallback_select = true;
                                                                        } elseif ($tpl_b['kode_template'] === 'MIK-06' && (stripos($p['parameter_uji'], 'kepekaan') !== false || stripos($p['parameter_uji'], 'lal') !== false)) {
                                                                            $is_fallback_select = true;
                                                                        }
                                                                    } elseif ($jenis_pengujian === 'Kimia' && $tpl_b['kode_template'] === 'KIM-01') {
                                                                        $is_fallback_select = true;
                                                                    }
                                                                }
                                                                $selected = ($is_session_custom || $is_default || $is_fallback_select) ? 'selected' : '';
                                                            ?>
                                                            <option value="<?php echo $tpl_b['id']; ?>" <?php echo $selected; ?>>
                                                                [<?php echo html_escape($tpl_b['kode_template']); ?>] <?php echo html_escape($tpl_b['nama_template']); ?><?php echo ($is_default || $p['template_id'] == $tpl_b['id']) ? ' ★ (Default)' : ''; ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </select>

                                                <span class="badge-status-lcp" id="status_badge_<?php echo $p['id']; ?>">
                                                    <?php if ($is_mapped): ?>
                                                        <span class="badge-lcp-default" title="Sesuai pemetaan acuan monografi">
                                                            <i class="bi bi-shield-check"></i> Default
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="badge-lcp-custom" title="Belum ada pemetaan monografi">
                                                            <i class="bi bi-pencil-fill"></i> Custom
                                                        </span>
                                                    <?php endif; ?>
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5 px-4">
                        <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                        <h6 class="fw-bold text-dark">Tidak Ditemukan Parameter Monografi</h6>
                        <p class="text-muted small mb-0">
                            Tidak ditemukan parameter pengujian monografi untuk zat aktif 
                            <strong><?php echo html_escape($zat_aktif_terpilih); ?></strong> 
                            pada bidang <strong><?php echo html_escape($jenis_pengujian); ?></strong>.
                        </p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Callout Kebijakan Fleksibilitas Analis -->
            <div class="callout-info-card">
                <i class="bi bi-shield-check text-primary fs-4 flex-shrink-0"></i>
                <div style="font-size: 0.83rem; line-height: 1.55;">
                    <strong class="text-dark d-block mb-1">
                        Fleksibilitas Pengujian Laboratorium Terstandar (Smart Default + Analis Override):
                    </strong>
                    <span class="text-secondary">
                        Form LCP secara otomatis dipilih berdasarkan panduan Monografi Farmakope Indonesia (★ Default). Jika preparasi atau instrumen laboratorium yang Anda gunakan berbeda (misalnya KCKT dialihkan ke Spektrofotometri UV atau Reaksi Kimia), analis dapat dengan leluasa mengganti pilihan Form LCP pada menu dropdown sebelum memulai sesi.
                    </span>
                </div>
            </div>

            <!-- Action Dock Sticky / Bottom Navigation -->
            <div class="action-dock-card">
                <div class="d-flex align-items-center gap-2">
                    <?php if (!empty($session_id_edit)): ?>
                        <a href="<?php echo site_url('pengujian/proses_sesi/' . $session_id_edit); ?>" class="btn-baku-back">
                            <i class="bi bi-arrow-left me-1"></i> Batal &amp; Kembali ke Sesi
                        </a>
                    <?php else: ?>
                        <a href="<?php echo site_url('pengujian/pilih_jenis/' . $sampel['id']); ?>" class="btn-baku-back">
                            <i class="bi bi-arrow-left me-1"></i> Kembali ke Bidang
                        </a>
                    <?php endif; ?>
                    <span class="text-muted small d-none d-md-inline">| Pastikan parameter kritis yang dicentang sesuai dengan pengujian Anda.</span>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <span class="text-muted small d-none d-sm-inline" id="text-dock-summary">
                        0 parameter terpilih
                    </span>
                    <button type="submit" class="btn btn-success btn-submit-action" id="btn-submit-sesi" disabled>
                        <span><?php echo !empty($session_id_edit) ? 'Simpan Perubahan Sesi &amp; Lanjutkan' : 'Mulai Sesi Pengujian Parameter'; ?></span>
                        <i class="bi bi-arrow-right fs-6"></i>
                    </button>
                </div>
            </div>

<!-- Modal Tambah Parameter dari Master Data (Poin 11) -->
<div class="modal fade" id="modalTambahParameterMaster" tabindex="-1" aria-labelledby="modalTambahParameterMasterLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="d-flex align-items-center justify-content-center rounded-3" style="width: 38px; height: 38px; background: #e0f2fe; color: #0284c7;">
                        <i class="bi bi-collection-fill fs-5"></i>
                    </div>
                    <div>
                        <h6 class="modal-title fw-bold text-dark mb-0" id="modalTambahParameterMasterLabel">Master Data Parameter Pengujian (<?php echo html_escape($jenis_pengujian); ?>)</h6>
                        <small class="text-muted" style="font-size: 0.78rem;">Pilih parameter pengujian untuk ditambahkan ke daftar pengerjaan sesi ini</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <div class="input-group input-group-search">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" id="inputCariMasterModal" placeholder="Ketik nama parameter, metode analisa, atau zat aktif..." autocomplete="off">
                    </div>
                </div>

                <div class="table-responsive rounded-3 border" style="max-height: 420px; border-color: #f1f5f9 !important;">
                    <table class="table align-middle mb-0" id="tabelMasterModal">
                        <thead class="sticky-top">
                            <tr>
                                <th width="35%">PARAMETER &amp; ZAT AKTIF</th>
                                <th width="25%">METODE &amp; PERSYARATAN</th>
                                <th width="25%">FORM LCP DEFAULT</th>
                                <th width="15%" class="text-center">AKSI</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyMasterModal" style="font-size: 0.82rem;">
                            <?php if (!empty($daftar_master_bidang)): ?>
                                <?php foreach ($daftar_master_bidang as $mb): ?>
                                    <tr class="master-modal-item" 
                                        data-search="<?php echo html_escape(strtolower($mb['parameter_uji'] . ' ' . $mb['nama_zat_aktif'] . ' ' . $mb['metode_analisa'] . ' ' . $mb['persyaratan'])); ?>">
                                        <td>
                                            <div class="fw-bold text-dark mb-1"><?php echo html_escape($mb['parameter_uji']); ?></div>
                                            <div class="text-muted small d-flex align-items-center flex-wrap gap-1">
                                                <span><i class="bi bi-capsule me-1 text-secondary"></i><?php echo html_escape($mb['nama_zat_aktif']); ?></span>
                                                <?php if (!empty($mb['bentuk_sediaan'])): ?>
                                                    <span class="badge bg-light text-secondary border" style="font-size: 0.70rem;"><?php echo html_escape($mb['bentuk_sediaan']); ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-secondary small fw-semibold"><?php echo html_escape($mb['metode_analisa'] ?: '-'); ?></div>
                                            <div class="text-muted small font-monospace mt-1" style="font-size: 0.76rem;"><?php echo html_escape($mb['persyaratan'] ?: '-'); ?></div>
                                        </td>
                                        <td>
                                            <?php if (!empty($mb['kode_template'])): ?>
                                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill fw-semibold" style="font-size: 0.72rem; padding: 4px 10px;">
                                                    [<?php echo html_escape($mb['kode_template']); ?>] <?php echo html_escape($mb['nama_template']); ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="text-muted small fst-italic">Pilih di tabel</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <button type="button" 
                                                    class="btn btn-tambah-master btn-pilih-dari-modal"
                                                    data-id="<?php echo $mb['id']; ?>"
                                                    data-param="<?php echo html_escape($mb['parameter_uji']); ?>"
                                                    data-za="<?php echo html_escape($mb['nama_zat_aktif']); ?>"
                                                    data-sediaan="<?php echo html_escape($mb['bentuk_sediaan'] ?: ''); ?>"
                                                    data-metode="<?php echo html_escape($mb['metode_analisa'] ?: '-'); ?>"
                                                    data-syarat="<?php echo html_escape($mb['persyaratan'] ?: '-'); ?>"
                                                    data-pustaka="<?php echo html_escape(!empty($mb['pustaka_acuan']) ? $mb['pustaka_acuan'] : 'Suplemen 1 FI VI'); ?>"
                                                    data-tpl-id="<?php echo !empty($mb['template_id']) ? $mb['template_id'] : ''; ?>">
                                                <i class="bi bi-plus-lg"></i> Tambah
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">Belum ada master data untuk bidang ini.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-modal-close" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
        </form>

    <?php else: ?>
        <!-- Empty State Belum Memilih Zat Aktif -->
        <div class="step-card">
            <div class="text-center py-5 px-4">
                <div class="d-inline-flex align-items-center justify-content-center bg-light text-primary rounded-circle mb-3" style="width: 72px; height: 72px;">
                    <i class="bi bi-capsule fs-1"></i>
                </div>
                <h5 class="fw-bold text-dark mb-2">Pilih Satu Zat Aktif Monografi Obat Terlebih Dahulu</h5>
                <p class="text-muted small mx-auto mb-4" style="max-width: 520px; line-height: 1.6;">
                    Gunakan dropdown pilihan pada <strong>Langkah 1</strong> di atas untuk memuat daftar seluruh parameter uji kritis, spesifikasi baku mutu, dan pemetaan formulir LCP yang relevan untuk sampel ini.
                </p>
                <div class="d-inline-flex gap-2">
                    <label for="select_zat_aktif" class="btn btn-outline-primary px-4 py-2 fw-semibold rounded-pill cursor-pointer">
                        <i class="bi bi-search me-1"></i> Klik untuk Memilih Zat Aktif
                    </label>
                </div>
            </div>

            <div class="p-3 bg-light border-top d-flex justify-content-between align-items-center">
                <a href="<?php echo site_url('pengujian/pilih_jenis/' . $sampel['id']); ?>" class="btn btn-sm rounded-pill px-3 fw-semibold" style="font-size: 0.78rem; background: #f8fafc; color: #475569; border: 1px solid #e2e8f0;">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Bidang
                </a>
            </div>
        </div>
    <?php endif; ?>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.form-check-parameter');
    const counterBadge = document.getElementById('badge-counter');
    const dockSummary = document.getElementById('text-dock-summary');
    const submitBtn = document.getElementById('btn-submit-sesi');
    const templateSelects = document.querySelectorAll('.select-custom-template');
    const btnSelectAll = document.getElementById('btn-select-all');
    const btnDeselectAll = document.getElementById('btn-deselect-all');

    // =========================================================
    // SEARCHABLE ZAT AKTIF COMBOBOX
    // =========================================================
    const searchContainer = document.getElementById('za_search_container');
    const inputSearchZa = document.getElementById('input_search_za');
    const hiddenZa = document.getElementById('hidden_zat_aktif');
    const btnClearZa = document.getElementById('btn_clear_za');
    const dropdownMenu = document.getElementById('za_dropdown_menu');
    const dropdownList = document.getElementById('za_dropdown_list');
    const counterBadgeZa = document.getElementById('za_counter_badge');
    const emptyStateZa = document.getElementById('za_empty_state');
    const formPilihZa = document.getElementById('form-pilih-zat-aktif');
    const zaItems = dropdownList ? dropdownList.querySelectorAll('.za-dropdown-item') : [];

    let currentHighlightIdx = -1;

    function openDropdown() {
        if (!dropdownMenu) return;
        dropdownMenu.style.display = 'block';
        updateClearButton();
    }

    function closeDropdown() {
        if (!dropdownMenu) return;
        dropdownMenu.style.display = 'none';
        currentHighlightIdx = -1;
        removeHighlight();
    }

    function updateClearButton() {
        if (!btnClearZa || !inputSearchZa) return;
        btnClearZa.style.display = inputSearchZa.value.trim() !== '' ? 'block' : 'none';
    }

    function removeHighlight() {
        zaItems.forEach(item => item.classList.remove('is-highlighted'));
    }

    function highlightText(text, query) {
        if (!query) return text;
        const regex = new RegExp('(' + query.replace(/[-[\]{}()*+?.,\\^$|#\s]/g, '\\$&') + ')', 'gi');
        return text.replace(regex, '<mark>$1</mark>');
    }

    function filterZatAktif() {
        if (!inputSearchZa) return;
        const query = inputSearchZa.value.trim().toLowerCase();
        let matchCount = 0;
        const visibleItems = [];

        zaItems.forEach(function(item) {
            const rawText = item.getAttribute('data-value') || '';
            const sediaanText = item.getAttribute('data-sediaan') || '';
            const nameSpan = item.querySelector('.za-name');

            if (query === '' || rawText.toLowerCase().includes(query) || sediaanText.toLowerCase().includes(query)) {
                item.style.display = 'flex';
                if (nameSpan) {
                    nameSpan.innerHTML = highlightText(rawText, query);
                }
                matchCount++;
                visibleItems.push(item);
            } else {
                item.style.display = 'none';
            }
        });

        if (counterBadgeZa) {
            counterBadgeZa.textContent = matchCount + ' ditemukan';
        }

        if (emptyStateZa) {
            if (matchCount === 0) {
                emptyStateZa.classList.remove('d-none');
            } else {
                emptyStateZa.classList.add('d-none');
            }
        }

        updateClearButton();
        return visibleItems;
    }

    function selectZatAktif(val) {
        if (!val) return;
        if (inputSearchZa) inputSearchZa.value = val;
        if (hiddenZa) hiddenZa.value = val;
        closeDropdown();

        // Otomatis submit form untuk memuat parameter monografi
        if (formPilihZa) {
            formPilihZa.submit();
        }
    }

    if (inputSearchZa) {
        inputSearchZa.addEventListener('focus', function() {
            openDropdown();
            filterZatAktif();
        });

        inputSearchZa.addEventListener('input', function() {
            openDropdown();
            filterZatAktif();
        });

        // Keyboard Navigation (Arrow Up, Down, Enter, Escape)
        inputSearchZa.addEventListener('keydown', function(e) {
            const visibleItems = Array.from(zaItems).filter(item => item.style.display !== 'none');
            if (visibleItems.length === 0) return;

            if (e.key === 'ArrowDown') {
                e.preventDefault();
                openDropdown();
                currentHighlightIdx = (currentHighlightIdx + 1) % visibleItems.length;
                removeHighlight();
                visibleItems[currentHighlightIdx].classList.add('is-highlighted');
                visibleItems[currentHighlightIdx].scrollIntoView({ block: 'nearest' });
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                openDropdown();
                currentHighlightIdx = (currentHighlightIdx - 1 + visibleItems.length) % visibleItems.length;
                removeHighlight();
                visibleItems[currentHighlightIdx].classList.add('is-highlighted');
                visibleItems[currentHighlightIdx].scrollIntoView({ block: 'nearest' });
            } else if (e.key === 'Enter') {
                e.preventDefault();
                if (currentHighlightIdx >= 0 && visibleItems[currentHighlightIdx]) {
                    const chosen = visibleItems[currentHighlightIdx].getAttribute('data-value');
                    selectZatAktif(chosen);
                } else if (visibleItems.length === 1) {
                    selectZatAktif(visibleItems[0].getAttribute('data-value'));
                } else {
                    if (formPilihZa) formPilihZa.submit();
                }
            } else if (e.key === 'Escape') {
                closeDropdown();
            }
        });
    }

    // Klik Item Zat Aktif
    zaItems.forEach(function(item) {
        item.addEventListener('click', function() {
            const chosen = this.getAttribute('data-value');
            selectZatAktif(chosen);
        });
    });

    // Tombol Clear
    if (btnClearZa) {
        btnClearZa.addEventListener('click', function(e) {
            e.stopPropagation();
            if (inputSearchZa) {
                inputSearchZa.value = '';
                inputSearchZa.focus();
            }
            if (hiddenZa) {
                hiddenZa.value = '';
            }
            openDropdown();
            filterZatAktif();
        });
    }

    // Tutup dropdown saat klik di luar area
    document.addEventListener('click', function(e) {
        if (searchContainer && !searchContainer.contains(e.target)) {
            closeDropdown();
        }
    });

    // Cek awal tombol clear
    updateClearButton();

    // =========================================================
    // PARAMETER SELECTION & VALIDATION
    // =========================================================

    function updateRowHighlight(cb) {
        const row = cb.closest('tr');
        if (!row) return;
        if (cb.checked) {
            row.classList.add('row-checked');
        } else {
            row.classList.remove('row-checked');
        }
    }

    function updateCounter() {
        if (!counterBadge || !submitBtn) return;
        let count = 0;
        let hasInvalidSelection = false;

        checkboxes.forEach(function(cb) {
            if (cb.checked) {
                count++;
                const row = cb.closest('tr');
                const sel = row ? row.querySelector('.select-custom-template') : null;
                if (sel && (!sel.value || sel.value === '')) {
                    hasInvalidSelection = true;
                }
            }
            updateRowHighlight(cb);
        });

        const labelText = count + ' parameter dipilih';
        counterBadge.textContent = labelText;
        if (dockSummary) {
            dockSummary.textContent = count > 0 ? (count + ' parameter terpilih untuk dibuatkan LCP') : '0 parameter terpilih';
        }

        if (count > 0 && !hasInvalidSelection) {
            counterBadge.className = 'badge bg-success px-3 py-2 rounded-pill text-white';
            counterBadge.style.color = '#ffffff';
            submitBtn.disabled = false;
        } else {
            counterBadge.className = 'badge bg-secondary px-3 py-2 rounded-pill text-white';
            counterBadge.style.color = '#ffffff';
            submitBtn.disabled = true;
        }

        // Toggle tombol select all / deselect all
        if (btnSelectAll && btnDeselectAll) {
            if (count === checkboxes.length && checkboxes.length > 0) {
                btnSelectAll.classList.add('d-none');
                btnDeselectAll.classList.remove('d-none');
            } else {
                btnSelectAll.classList.remove('d-none');
                btnDeselectAll.classList.add('d-none');
            }
        }
    }

    // Event listener checkbox
    checkboxes.forEach(function(cb) {
        cb.addEventListener('change', updateCounter);
    });

    // Event listener select template LCP (interaktif)
    templateSelects.forEach(function(sel) {
        sel.addEventListener('change', function() {
            const row = sel.closest('tr');
            const cb = row ? row.querySelector('.form-check-parameter') : null;
            const badgeContainer = row ? row.querySelector('.badge-status-lcp') : null;
            const defaultId = sel.getAttribute('data-default-id');

            // Otomatis centang baris jika analis memilih template
            if (cb && sel.value !== '' && !cb.checked) {
                cb.checked = true;
            }

            // Perbarui visual badge default vs custom
            if (badgeContainer) {
                if (sel.value && defaultId && sel.value === defaultId) {
                    badgeContainer.innerHTML = '<span class="badge-lcp-default" title="Sesuai pemetaan acuan monografi"><i class="bi bi-shield-check"></i> Default</span>';
                } else if (sel.value) {
                    badgeContainer.innerHTML = '<span class="badge-lcp-custom" title="Pilihan khusus analis"><i class="bi bi-pencil-fill"></i> Custom Analis</span>';
                } else {
                    badgeContainer.innerHTML = '<span class="badge-lcp-custom" title="Belum ada template"><i class="bi bi-exclamation-circle"></i> Wajib Pilih</span>';
                }
            }

            updateCounter();
        });
    });

    // Select All & Deselect All
    if (btnSelectAll) {
        btnSelectAll.addEventListener('click', function() {
            checkboxes.forEach(function(cb) {
                cb.checked = true;
            });
            updateCounter();
        });
    }

    if (btnDeselectAll) {
        btnDeselectAll.addEventListener('click', function() {
            checkboxes.forEach(function(cb) {
                cb.checked = false;
            });
            updateCounter();
        });
    }

    // Tombol Terapkan Rekomendasi Pintar (ALT + AKK + E. coli atau Potensi Nistatin)
    const btnApplyRekomendasi = document.getElementById('btn-apply-rekomendasi');
    if (btnApplyRekomendasi) {
        btnApplyRekomendasi.addEventListener('click', function() {
            checkboxes.forEach(function(cb) {
                const isRekomendasi = (cb.getAttribute('data-is-rekomendasi') === '1');
                cb.checked = isRekomendasi;
                updateRowHighlight(cb);
            });
            updateCounter();
        });
    }

    // Smart Initial Auto-Check (Khusus saat awal buka sesi baru, belum ada yang tercentang sebelumnya)
    <?php if (empty($sesi_method_ids) && !empty($rekomendasi_mikro) && in_array($rekomendasi_mikro['kategori'], array('oral_non_antibiotik', 'antibiotik_nystatin', 'topikal', 'rektal'))): ?>
    let hasExplicitChecked = false;
    checkboxes.forEach(function(cb) {
        if (cb.checked) hasExplicitChecked = true;
    });
    if (!hasExplicitChecked) {
        checkboxes.forEach(function(cb) {
            if (cb.getAttribute('data-is-rekomendasi') === '1') {
                cb.checked = true;
                updateRowHighlight(cb);
            }
        });
    }
    <?php endif; ?>

    // Live Search di Modal Master
    const inputCariModal = document.getElementById("inputCariMasterModal");
    const modalItems = document.querySelectorAll(".master-modal-item");
    if (inputCariModal) {
        inputCariModal.addEventListener("input", function() {
            const q = this.value.toLowerCase().trim();
            modalItems.forEach(function(item) {
                const searchStr = item.getAttribute("data-search") || "";
                if (!q || searchStr.indexOf(q) !== -1) {
                    item.style.display = "";
                } else {
                    item.style.display = "none";
                }
            });
        });
    }

    // Handler Klik Tambah dari Modal
    document.querySelectorAll(".btn-pilih-dari-modal").forEach(function(btn) {
        btn.addEventListener("click", function() {
            const mId = this.getAttribute("data-id");
            const mParam = this.getAttribute("data-param");
            const mZa = this.getAttribute("data-za");
            const mSediaan = this.getAttribute("data-sediaan");
            const mMetode = this.getAttribute("data-metode");
            const mSyarat = this.getAttribute("data-syarat");
            const mPustaka = this.getAttribute("data-pustaka");
            const mTplId = this.getAttribute("data-tpl-id");

            // Cek apakah sudah ada di tabel utama
            const existingRow = document.getElementById("row_param_" + mId);
            if (existingRow) {
                const cb = existingRow.querySelector(".form-check-parameter");
                if (cb) {
                    cb.checked = true;
                    updateRowHighlight(cb);
                }
                existingRow.scrollIntoView({ behavior: "smooth", block: "center" });
                existingRow.classList.add("table-warning");
                setTimeout(function() { existingRow.classList.remove("table-warning"); }, 2000);
            } else {
                // Buat baris baru di tabel parameter
                const tbody = document.querySelector(".parameter-table tbody");
                if (tbody) {
                    const tr = document.createElement("tr");
                    tr.id = "row_param_" + mId;
                    tr.className = "row-checked table-active";

                    let tplOptions = "<option value=\"\">-- Pilih Form LCP --</option>";
                    <?php if (!empty($daftar_template_bidang)): ?>
                        <?php foreach ($daftar_template_bidang as $tpl_b): ?>
                            tplOptions += `<option value="<?php echo $tpl_b['id']; ?>" ${mTplId == "<?php echo $tpl_b['id']; ?>" ? "selected" : ""}>[<?php echo html_escape($tpl_b['kode_template']); ?>] <?php echo html_escape($tpl_b['nama_template']); ?></option>`;
                        <?php endforeach; ?>
                    <?php endif; ?>

                    tr.innerHTML = `
                        <td class="text-center">
                            <input class="form-check-input custom-param-checkbox form-check-parameter" 
                                   type="checkbox" 
                                   name="method_ids[]" 
                                   value="${mId}" 
                                   id="chk_metode_${mId}" 
                                   data-is-rekomendasi="0" 
                                   checked>
                        </td>
                        <td>
                            <label for="chk_metode_${mId}" class="d-block cursor-pointer mb-0">
                                <div class="d-flex align-items-center flex-wrap gap-2 mb-1">
                                    <strong class="text-dark" style="font-size: 0.88rem;">${mParam}</strong>
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill" style="font-size: 0.72rem;">
                                        <i class="bi bi-plus-circle me-1"></i>Tambahan Master
                                    </span>
                                    ${mSediaan ? `<span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill" style="font-size: 0.72rem;">${mSediaan}</span>` : ""}
                                </div>
                                <div class="text-muted small font-monospace">${mZa}</div>
                            </label>
                        </td>
                        <td><span class="text-secondary small fw-semibold">${mMetode}</span></td>
                        <td><span class="persyaratan-badge">${mSyarat}</span></td>
                        <td><span class="text-muted small font-monospace">${mPustaka}</span></td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <select class="form-select select-template-lcp select-custom-template" 
                                        name="custom_template_ids[${mId}]" 
                                        id="sel_lcp_${mId}">
                                    ${tplOptions}
                                </select>
                                <span class="badge-status-lcp">
                                    <span class="badge-lcp-custom"><i class="bi bi-pencil-fill"></i> Tambahan</span>
                                </span>
                            </div>
                        </td>
                    `;
                    tbody.appendChild(tr);

                    // Re-bind listeners for new checkbox & select
                    const newCb = tr.querySelector(".form-check-parameter");
                    if (newCb) {
                        newCb.addEventListener("change", updateCounter);
                        checkboxes.push(newCb);
                    }
                    const newSel = tr.querySelector(".select-custom-template");
                    if (newSel) {
                        newSel.addEventListener("change", updateCounter);
                    }
                }
            }

            updateCounter();

            // Tutup modal
            const modalEl = document.getElementById("modalTambahParameterMaster");
            const modalInstance = bootstrap.Modal.getInstance(modalEl);
            if (modalInstance) {
                modalInstance.hide();
            }
        });
    });
    // Inisialisasi awal
    updateCounter();
});
</script>
