<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php
// Deteksi rekomendasi pintar dari nama sampel
$smart_suggestions = array();
if (!empty($sampel['nama_sampel']) && !empty($daftar_zat_aktif)) {
    $nama_sampel_upper = strtoupper($sampel['nama_sampel']);
    // Pisahkan nama sampel menjadi token kata (minimal 3 huruf)
    $sampel_tokens = preg_split('/[^A-Za-z0-9]+/', $nama_sampel_upper);
    $sampel_tokens = array_filter($sampel_tokens, function($t) { return strlen($t) >= 3; });

    foreach ($daftar_zat_aktif as $za) {
        $za_name = strtoupper(trim($za['nama_zat_aktif']));
        if (empty($za_name)) continue;

        // Cocok persis substring
        if (stripos($nama_sampel_upper, $za_name) !== false) {
            $smart_suggestions[] = $za['nama_zat_aktif'];
        } else {
            // Cocok per token kata
            foreach ($sampel_tokens as $token) {
                if (stripos($za_name, $token) !== false) {
                    $smart_suggestions[] = $za['nama_zat_aktif'];
                    break;
                }
            }
        }
        if (count($smart_suggestions) >= 4) break;
    }
    $smart_suggestions = array_unique($smart_suggestions);
}
?>

<style>
    /* =========================================================
       HALAMAN PILIH ZAT AKTIF & PARAMETER MONOGRAFI (LIMSKU)
       Desain Selaras dengan Master Metode & Antrean Pengujian
    ========================================================= */
    .pilih-form-page {
        width: 100%;
        max-width: 1180px;
        margin: 0 auto;
        padding: 6px 0 40px;
        color: #294257;
    }

    /* Hero / Sample Context Header */
    .hero-sample-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        box-shadow: 0 4px 18px rgba(41, 66, 87, 0.05);
        padding: 22px 26px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
    }
    .hero-sample-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: <?php echo ($jenis_pengujian === 'Kimia' ? 'linear-gradient(90deg, #0284c7 0%, #0369a1 100%)' : 'linear-gradient(90deg, #10b981 0%, #047857 100%)'); ?>;
    }
    .hero-badge-bidang {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.82rem;
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
        font-size: 0.82rem;
        color: #475569;
    }
    .sample-chip strong {
        color: #1e293b;
    }

    /* Section Cards */
    .step-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        box-shadow: 0 4px 18px rgba(41, 66, 87, 0.05);
        margin-bottom: 24px;
        overflow: hidden;
    }
    .step-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 18px 24px;
        border-bottom: 1px solid #edf2f5;
        background: #fafcff;
    }
    .step-title-wrap {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .step-number {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 0.95rem;
        background: <?php echo ($jenis_pengujian === 'Kimia' ? '#0284c7' : '#10b981'); ?>;
        color: #ffffff;
        flex-shrink: 0;
        box-shadow: 0 2px 6px rgba(0,0,0,0.12);
    }
    .step-title {
        margin: 0;
        font-size: 1.02rem;
        font-weight: 700;
        color: #1e293b;
    }
    .step-subtitle {
        margin: 2px 0 0;
        font-size: 0.8rem;
        color: #64748b;
    }

    /* Step 1: Search & Zat Aktif Controls */
    .zat-aktif-select-wrap {
        padding: 22px 24px;
    }

    /* Smart Recommendation Banner */
    .smart-recommend-bar {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 8px;
        padding: 10px 16px;
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        border-radius: 10px;
        margin-bottom: 18px;
    }
    .btn-recommend-chip {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 12px;
        border-radius: 20px;
        background: #ffffff;
        border: 1px solid #86efac;
        color: #15803d;
        font-size: 0.78rem;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.18s ease;
    }
    .btn-recommend-chip:hover {
        background: #16a34a;
        color: #ffffff;
        border-color: #16a34a;
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(22, 163, 74, 0.2);
    }

    /* Search Toolbar */
    .search-filter-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 14px 16px;
        margin-bottom: 18px;
    }
    .search-input-group {
        position: relative;
    }
    .search-input-group .form-control {
        border-radius: 8px;
        border: 1.5px solid #cbd5e1;
        padding-left: 38px;
        font-size: 0.88rem;
        font-weight: 500;
        height: 42px;
    }
    .search-input-group .form-control:focus {
        border-color: #0284c7;
        box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15);
    }
    .search-input-group .search-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #64748b;
        font-size: 0.95rem;
        pointer-events: none;
        z-index: 5;
    }
    .btn-clear-search {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #94a3b8;
        cursor: pointer;
        font-size: 0.85rem;
        padding: 4px;
        display: none;
        z-index: 5;
    }
    .btn-clear-search:hover {
        color: #ef4444;
    }

    /* Alphabet Filter Buttons */
    .alphabet-filter-container {
        display: flex;
        align-items: center;
        gap: 3px;
        overflow-x: auto;
        padding: 2px 0;
        scrollbar-width: thin;
    }
    .alpha-btn {
        width: 27px;
        height: 27px;
        min-width: 27px;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #475569;
        font-size: 0.72rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        padding: 0;
        transition: all 0.15s ease;
    }
    .alpha-btn:hover {
        background: #e2e8f0;
        color: #0f172a;
    }
    .alpha-btn.active {
        background: <?php echo ($jenis_pengujian === 'Kimia' ? '#0284c7' : '#10b981'); ?>;
        border-color: <?php echo ($jenis_pengujian === 'Kimia' ? '#0284c7' : '#10b981'); ?>;
        color: #ffffff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .alpha-btn.btn-all {
        width: auto;
        padding: 0 10px;
    }

    /* Autocomplete Suggestions Dropdown */
    .suggestions-list-box {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.12);
        max-height: 250px;
        overflow-y: auto;
        z-index: 1050;
        display: none;
        margin-top: 4px;
    }
    .suggestion-item {
        padding: 9px 14px;
        font-size: 0.85rem;
        color: #1e293b;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid #f1f5f9;
        transition: background 0.12s ease;
    }
    .suggestion-item:last-child {
        border-bottom: none;
    }
    .suggestion-item:hover, .suggestion-item.active {
        background: #eff6ff;
        color: #0284c7;
        font-weight: 600;
    }

    /* Main Select & Submit */
    .select-za-control {
        border-radius: 10px;
        border: 1.5px solid #cbd5e1;
        padding: 12px 16px;
        font-size: 0.95rem;
        font-weight: 600;
        color: #1e293b;
        transition: all 0.2s ease;
        background-color: #ffffff;
    }
    .select-za-control:focus {
        border-color: <?php echo ($jenis_pengujian === 'Kimia' ? '#0284c7' : '#10b981'); ?>;
        box-shadow: 0 0 0 3px <?php echo ($jenis_pengujian === 'Kimia' ? 'rgba(2, 132, 199, 0.15)' : 'rgba(16, 185, 129, 0.15)'); ?>;
    }
    .btn-muat-parameter {
        border-radius: 10px;
        padding: 12px 22px;
        font-size: 0.92rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s ease;
    }

    /* Table Styling */
    .parameter-table thead th {
        background: #f8fafc;
        color: #475569;
        font-size: 0.74rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 13px 14px;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
        vertical-align: middle;
    }
    .parameter-table tbody td {
        padding: 13px 14px;
        font-size: 0.83rem;
        color: #334e68;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
        transition: background 0.15s ease;
    }
    .parameter-table tbody tr:hover td {
        background-color: #f8fafc;
    }
    .parameter-table tbody tr.row-checked td {
        background-color: #f0f7ff;
    }

    /* Modern Custom Checkbox */
    .custom-param-checkbox {
        width: 1.25rem;
        height: 1.25rem;
        cursor: pointer;
        border: 2px solid #94a3b8;
        border-radius: 5px;
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
        border-radius: 4px;
        padding: 1px 6px;
        font-size: 0.72rem;
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-weight: 600;
    }
    .badge-metode-analisa {
        display: inline-block;
        background: #f8fafc;
        color: #334155;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        padding: 3px 8px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .badge-pustaka {
        display: inline-block;
        background: #eff6ff;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
        border-radius: 4px;
        padding: 2px 7px;
        font-size: 0.73rem;
        font-weight: 600;
        white-space: nowrap;
    }
    .badge-lcp-default {
        background: #e6ffed;
        color: #15803d;
        border: 1px solid #b7eb8f;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 0.72rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        white-space: nowrap;
    }
    .badge-lcp-custom {
        background: #fffbe6;
        color: #b45309;
        border: 1px solid #fde047;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 0.72rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        white-space: nowrap;
    }

    /* Template Select */
    .select-template-lcp {
        font-size: 0.81rem;
        font-weight: 600;
        color: #1e293b;
        border-radius: 7px;
        border: 1px solid #cbd5e1;
        padding: 6px 10px;
        transition: all 0.2s ease;
        background-color: #ffffff;
    }
    .select-template-lcp:focus {
        border-color: #0284c7;
        box-shadow: 0 0 0 2px rgba(2, 132, 199, 0.15);
    }

    /* Callout Card */
    .callout-info-card {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-left: 4px solid #0284c7;
        border-radius: 10px;
        padding: 16px 20px;
        margin-bottom: 24px;
        display: flex;
        align-items: flex-start;
        gap: 14px;
    }

    /* Sticky Bottom Action Dock */
    .action-dock-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        box-shadow: 0 4px 18px rgba(41, 66, 87, 0.08);
        padding: 16px 22px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 14px;
    }
    .btn-submit-action {
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.92rem;
        padding: 11px 24px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.08);
        transition: all 0.2s ease;
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
                <a href="<?php echo site_url('pengujian/pilih_jenis/' . $sampel['id']); ?>" class="btn btn-outline-secondary btn-sm px-3 py-2 fw-semibold">
                    <i class="bi bi-arrow-left me-1"></i> Ganti Bidang
                </a>
            </div>
        </div>
    </div>

    <!-- 2. Step 1: Pemilihan Zat Aktif Monografi dengan Fitur Search Cerdas -->
    <div class="step-card">
        <div class="step-header">
            <div class="step-title-wrap">
                <div class="step-number">1</div>
                <div>
                    <h5 class="step-title">Tentukan Zat Aktif Monografi Obat</h5>
                    <p class="step-subtitle">Ketik cepat atau saring abjad untuk menemukan zat aktif Farmakope yang sesuai.</p>
                </div>
            </div>
            <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill small" id="badge_total_za">
                <i class="bi bi-database me-1"></i> <?php echo count($daftar_zat_aktif); ?> Zat Aktif Tersedia
            </span>
        </div>

        <div class="zat-aktif-select-wrap">

            <!-- Smart Suggestion dari nama sampel (1-Klik Langsung Pilih) -->
            <?php if (!empty($smart_suggestions)): ?>
                <div class="smart-recommend-bar">
                    <span class="text-success fw-bold small d-inline-flex align-items-center gap-1">
                        <i class="bi bi-stars text-warning fs-6"></i> Saran Cerdas (Nama Sampel):
                    </span>
                    <?php foreach ($smart_suggestions as $sug): ?>
                        <button type="button" 
                                class="btn-recommend-chip" 
                                onclick="quickSelectZatAktif('<?php echo html_escape(addslashes($sug)); ?>')" 
                                title="Klik untuk langsung memuat monografi <?php echo html_escape($sug); ?>">
                            <i class="bi bi-lightning-charge-fill text-warning"></i>
                            <span><?php echo html_escape($sug); ?></span>
                        </button>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Interactive Search Toolbar -->
            <div class="search-filter-box">
                <div class="row align-items-center g-3">
                    <div class="col-lg-6 col-md-12">
                        <div class="search-input-group">
                            <i class="bi bi-search search-icon"></i>
                            <input type="text" 
                                   id="input_search_zat_aktif" 
                                   class="form-control" 
                                   placeholder="🔍 Ketik cepat nama zat aktif (misal: Ketoconazole, Parasetamol, Calcium...)" 
                                   autocomplete="off">
                            <button type="button" class="btn-clear-search" id="btn_clear_search_za" title="Hapus pencarian">
                                <i class="bi bi-x-circle-fill"></i>
                            </button>
                            <!-- Autocomplete Suggestions List Floating Box -->
                            <div class="suggestions-list-box" id="suggestions_box_za"></div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <!-- Quick Alphabet Filter A-Z -->
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-muted small fw-semibold text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.05em; white-space: nowrap;">
                                Filter Huruf:
                            </span>
                            <div class="alphabet-filter-container" id="alpha_container">
                                <button type="button" class="alpha-btn btn-all active" data-char="ALL" title="Tampilkan Semua">Semua</button>
                                <?php foreach (range('A', 'Z') as $char): ?>
                                    <button type="button" class="alpha-btn" data-char="<?php echo $char; ?>"><?php echo $char; ?></button>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Dropdown Form -->
            <form method="get" action="<?php echo site_url('pengujian/pilih_form/' . $sampel['id']); ?>" id="form-pilih-zat-aktif">
                <input type="hidden" name="jenis" value="<?php echo html_escape($jenis_pengujian); ?>">
                <div class="row align-items-center g-3">
                    <div class="col-lg-9 col-md-8">
                        <div class="position-relative">
                            <select name="zat_aktif" id="select_zat_aktif" class="form-select select-za-control" onchange="this.form.submit()" required>
                                <option value="">-- Pilih Satu Zat Aktif Utama Monografi Obat --</option>
                                <?php foreach ($daftar_zat_aktif as $za): ?>
                                    <option value="<?php echo html_escape($za['nama_zat_aktif']); ?>" <?php echo ($zat_aktif_terpilih === $za['nama_zat_aktif']) ? 'selected' : ''; ?>>
                                        <?php echo html_escape($za['nama_zat_aktif']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-2 px-1">
                            <small class="text-muted" id="search_feedback_msg">
                                Menampilkan <?php echo count($daftar_zat_aktif); ?> zat aktif monografi <?php echo html_escape($jenis_pengujian); ?>.
                            </small>
                            <?php if (!empty($zat_aktif_terpilih)): ?>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1" style="font-size: 0.76rem;">
                                    <i class="bi bi-check2-circle me-1"></i> Aktif: <strong><?php echo html_escape($zat_aktif_terpilih); ?></strong>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4">
                        <button type="submit" class="btn btn-primary btn-muat-parameter w-100 shadow-sm" id="btn-submit-load-param">
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

            <div class="step-card">
                <div class="step-header flex-wrap">
                    <div class="step-title-wrap">
                        <div class="step-number">2</div>
                        <div>
                            <h5 class="step-title">
                                Parameter Uji Kritis Monografi: 
                                <span class="text-primary"><?php echo html_escape($zat_aktif_terpilih); ?></span>
                            </h5>
                            <p class="step-subtitle">Centang parameter yang akan diuji. Analis dapat menyesuaikan Form LCP pada kolom instrumen sebelum pengujian dimulai.</p>
                        </div>
                    </div>

                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <!-- Mini search di tabel parameter -->
                        <div class="input-group input-group-sm" style="max-width: 220px;">
                            <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" id="input_filter_tabel_param" class="form-control" placeholder="Cari di parameter...">
                        </div>
                        <button type="button" class="btn btn-outline-secondary btn-sm px-3 fw-semibold" id="btn-select-all">
                            <i class="bi bi-check-all me-1"></i> Pilih Semua
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm px-3 fw-semibold d-none" id="btn-deselect-all">
                            <i class="bi bi-x-lg me-1"></i> Batal Semua
                        </button>
                        <span class="badge bg-secondary px-3 py-2 fs-6 rounded-pill" id="badge-counter">0 parameter dipilih</span>
                    </div>
                </div>

                <?php if (!empty($parameter_monografi)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 parameter-table" id="tabel_parameter_monografi">
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
                                    <?php $is_mapped = !empty($p['template_id']); ?>
                                    <tr id="row_param_<?php echo $p['id']; ?>" class="parameter-row" data-param-text="<?php echo strtolower(html_escape($p['parameter_uji'] . ' ' . $p['kode_metode'] . ' ' . $p['metode_analisa'])); ?>">
                                        <td class="text-center">
                                            <input class="form-check-input custom-param-checkbox form-check-parameter" 
                                                   type="checkbox" 
                                                   name="method_ids[]" 
                                                   value="<?php echo $p['id']; ?>" 
                                                   id="chk_metode_<?php echo $p['id']; ?>">
                                        </td>
                                        <td>
                                            <label for="chk_metode_<?php echo $p['id']; ?>" class="d-block cursor-pointer mb-0">
                                                <strong class="text-dark d-block mb-1" style="font-size: 0.88rem;">
                                                    <?php echo html_escape($p['parameter_uji']); ?>
                                                </strong>
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
                                                                $is_default = ($p['template_id'] == $tpl_b['id']);
                                                                $is_fallback_select = false;
                                                                if (!$is_mapped) {
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
                                                                $selected = ($is_default || $is_fallback_select) ? 'selected' : '';
                                                            ?>
                                                            <option value="<?php echo $tpl_b['id']; ?>" <?php echo $selected; ?>>
                                                                [<?php echo html_escape($tpl_b['kode_template']); ?>] <?php echo html_escape($tpl_b['nama_template']); ?><?php echo $is_default ? ' ★ (Default)' : ''; ?>
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
                    <a href="<?php echo site_url('pengujian/pilih_jenis/' . $sampel['id']); ?>" class="btn btn-outline-secondary px-3 py-2 fw-semibold" style="border-radius: 8px;">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Bidang
                    </a>
                    <span class="text-muted small d-none d-md-inline">| Pastikan parameter kritis yang dicentang sesuai dengan pengujian Anda.</span>
                </div>

                <div class="d-flex align-items-center gap-3">
                    <span class="text-muted small d-none d-sm-inline" id="text-dock-summary">
                        0 parameter terpilih
                    </span>
                    <button type="submit" class="btn btn-success btn-submit-action" id="btn-submit-sesi" disabled>
                        <span>Mulai Sesi Pengujian Parameter</span>
                        <i class="bi bi-arrow-right fs-6"></i>
                    </button>
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
                    Gunakan kotak pencarian atau dropdown pada <strong>Langkah 1</strong> di atas untuk memuat daftar seluruh parameter uji kritis, spesifikasi baku mutu, dan pemetaan formulir LCP yang relevan untuk sampel ini.
                </p>
                <div class="d-inline-flex gap-2">
                    <button type="button" class="btn btn-outline-primary px-4 py-2 fw-semibold rounded-pill" onclick="document.getElementById('input_search_zat_aktif').focus()">
                        <i class="bi bi-search me-1"></i> Ketik Nama Zat Aktif Sekarang
                    </button>
                </div>
            </div>

            <div class="p-3 bg-light border-top d-flex justify-content-between align-items-center">
                <a href="<?php echo site_url('pengujian/pilih_jenis/' . $sampel['id']); ?>" class="btn btn-outline-secondary btn-sm px-3 fw-semibold">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Bidang
                </a>
            </div>
        </div>
    <?php endif; ?>

</div>

<script>
// Fungsi global cepat untuk memilih zat aktif dan langsung submit
function quickSelectZatAktif(namaZat) {
    const selectZa = document.getElementById('select_zat_aktif');
    const form = document.getElementById('form-pilih-zat-aktif');
    if (!selectZa || !form) return;

    // Pastikan option ada di select
    let found = false;
    for (let i = 0; i < selectZa.options.length; i++) {
        if (selectZa.options[i].value.toUpperCase() === namaZat.toUpperCase()) {
            selectZa.selectedIndex = i;
            found = true;
            break;
        }
    }
    if (!found) {
        // Buat option baru jika belum ada
        const opt = new Option(namaZat, namaZat, true, true);
        selectZa.add(opt);
    }
    form.submit();
}

document.addEventListener('DOMContentLoaded', function() {
    // =========================================================
    // 1. FITUR SEARCH ZAT AKTIF & ALPHABET FILTER
    // =========================================================
    const searchInput = document.getElementById('input_search_zat_aktif');
    const btnClearSearch = document.getElementById('btn_clear_search_za');
    const selectZa = document.getElementById('select_zat_aktif');
    const suggestionsBox = document.getElementById('suggestions_box_za');
    const feedbackMsg = document.getElementById('search_feedback_msg');
    const alphaButtons = document.querySelectorAll('.alpha-btn');
    const formZa = document.getElementById('form-pilih-zat-aktif');

    // Simpan semua opsi asli dari select saat load
    const originalOptions = [];
    if (selectZa) {
        for (let i = 0; i < selectZa.options.length; i++) {
            const opt = selectZa.options[i];
            if (opt.value !== '') {
                originalOptions.push({
                    value: opt.value,
                    text: opt.text,
                    selected: opt.selected
                });
            }
        }
    }

    let activeLetter = 'ALL';

    function renderFilteredSelect(query = '', letter = 'ALL') {
        if (!selectZa) return;
        const q = query.trim().toUpperCase();
        const letFilter = letter.toUpperCase();

        const filtered = originalOptions.filter(function(item) {
            const valUpper = item.value.toUpperCase();
            const matchQuery = (q === '' || valUpper.includes(q));
            const matchLetter = (letFilter === 'ALL' || valUpper.startsWith(letFilter));
            return matchQuery && matchLetter;
        });

        // Re-populate select element
        selectZa.innerHTML = '';
        const defaultOpt = new Option('-- Pilih Satu Zat Aktif Utama Monografi Obat --', '');
        selectZa.add(defaultOpt);

        filtered.forEach(function(item) {
            const opt = new Option(item.text, item.value, false, item.selected);
            selectZa.add(opt);
        });

        // Update info message
        if (feedbackMsg) {
            if (q !== '' || letFilter !== 'ALL') {
                feedbackMsg.innerHTML = 'Ditemukan <strong>' + filtered.length + '</strong> zat aktif yang cocok.';
            } else {
                feedbackMsg.textContent = 'Menampilkan ' + originalOptions.length + ' zat aktif monografi <?php echo html_escape($jenis_pengujian); ?>.';
            }
        }

        // Tampilkan box suggestions jika user mengetik
        if (suggestionsBox) {
            if (q.length >= 1) {
                suggestionsBox.innerHTML = '';
                if (filtered.length === 0) {
                    suggestionsBox.innerHTML = '<div class="p-3 text-muted text-center small"><i class="bi bi-info-circle me-1"></i> Tidak ditemukan zat aktif "' + html_escape_js(q) + '"</div>';
                } else {
                    const maxDisplay = Math.min(filtered.length, 12);
                    for (let i = 0; i < maxDisplay; i++) {
                        const item = filtered[i];
                        const div = document.createElement('div');
                        div.className = 'suggestion-item';
                        // Highlight kecocokan
                        const regex = new RegExp('(' + escapeRegExp(q) + ')', 'gi');
                        const highlightedText = item.text.replace(regex, '<span class="text-primary fw-bold">$1</span>');
                        div.innerHTML = '<span><i class="bi bi-capsule me-2 text-muted"></i>' + highlightedText + '</span><span class="badge bg-light text-secondary border small">Pilih &rarr;</span>';
                        div.addEventListener('click', function() {
                            quickSelectZatAktif(item.value);
                        });
                        suggestionsBox.appendChild(div);
                    }
                    if (filtered.length > 12) {
                        const moreDiv = document.createElement('div');
                        moreDiv.className = 'p-2 text-center text-muted small bg-light';
                        moreDiv.textContent = '...dan ' + (filtered.length - 12) + ' zat aktif lainnya (persempit pencarian)';
                        suggestionsBox.appendChild(moreDiv);
                    }
                }
                suggestionsBox.style.display = 'block';
            } else {
                suggestionsBox.style.display = 'none';
            }
        }
    }

    function escapeRegExp(string) {
        return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    }

    function html_escape_js(str) {
        return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const val = this.value;
            if (btnClearSearch) {
                btnClearSearch.style.display = val.length > 0 ? 'block' : 'none';
            }
            renderFilteredSelect(val, activeLetter);
        });

        // Enter key di input search
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const q = this.value.trim().toUpperCase();
                // Jika ada tepat 1 kecocokan di suggestions atau dropdown, langsung pilih
                if (selectZa && selectZa.options.length === 2 && selectZa.options[1].value !== '') {
                    selectZa.selectedIndex = 1;
                    if (formZa) formZa.submit();
                } else if (suggestionsBox && suggestionsBox.querySelector('.suggestion-item')) {
                    suggestionsBox.querySelector('.suggestion-item').click();
                }
            }
        });
    }

    if (btnClearSearch) {
        btnClearSearch.addEventListener('click', function() {
            if (searchInput) {
                searchInput.value = '';
                searchInput.focus();
            }
            btnClearSearch.style.display = 'none';
            renderFilteredSelect('', activeLetter);
        });
    }

    // Klik di luar suggestion box untuk menutup
    document.addEventListener('click', function(e) {
        if (suggestionsBox && !suggestionsBox.contains(e.target) && e.target !== searchInput) {
            suggestionsBox.style.display = 'none';
        }
    });

    // Alphabet filter
    alphaButtons.forEach(function(btn) {
        btn.addEventListener('click', function() {
            alphaButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            activeLetter = this.getAttribute('data-char');
            renderFilteredSelect(searchInput ? searchInput.value : '', activeLetter);
        });
    });

    // =========================================================
    // 2. SEARCH FILTER DI TABEL PARAMETER (LANGKAH 2)
    // =========================================================
    const inputFilterTable = document.getElementById('input_filter_tabel_param');
    if (inputFilterTable) {
        inputFilterTable.addEventListener('input', function() {
            const query = this.value.trim().toLowerCase();
            const paramRows = document.querySelectorAll('.parameter-row');
            paramRows.forEach(function(row) {
                const text = row.getAttribute('data-param-text') || '';
                if (query === '' || text.includes(query)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }

    // =========================================================
    // 3. PARAMETER SELECTION & DYNAMIC COUNTER LOGIC
    // =========================================================
    const checkboxes = document.querySelectorAll('.form-check-parameter');
    const counterBadge = document.getElementById('badge-counter');
    const dockSummary = document.getElementById('text-dock-summary');
    const submitBtn = document.getElementById('btn-submit-sesi');
    const templateSelects = document.querySelectorAll('.select-custom-template');
    const btnSelectAll = document.getElementById('btn-select-all');
    const btnDeselectAll = document.getElementById('btn-deselect-all');

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
            counterBadge.className = 'badge bg-success px-3 py-2 fs-6 rounded-pill';
            submitBtn.disabled = false;
        } else {
            counterBadge.className = 'badge bg-secondary px-3 py-2 fs-6 rounded-pill';
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

    checkboxes.forEach(function(cb) {
        cb.addEventListener('change', updateCounter);
    });

    templateSelects.forEach(function(sel) {
        sel.addEventListener('change', function() {
            const row = sel.closest('tr');
            const cb = row ? row.querySelector('.form-check-parameter') : null;
            const badgeContainer = row ? row.querySelector('.badge-status-lcp') : null;
            const defaultId = sel.getAttribute('data-default-id');

            if (cb && sel.value !== '' && !cb.checked) {
                cb.checked = true;
            }

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

    updateCounter();
});
</script>
