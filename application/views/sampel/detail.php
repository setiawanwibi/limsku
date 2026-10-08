<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
    /* =========================================================
       DETAIL SAMPEL
       Tampilan saja - fungsi PHP tetap dipertahankan
       ========================================================= */

    .detail-sampel-page {
        width: 100%;
        color: #294257;
        font-size: 0.92rem;
    }

    .detail-sampel-card {
        width: 100%;
        background: #fff;
        border: 1px solid #e0e8ed;
        border-radius: 11px;
        box-shadow: 0 5px 18px rgba(35, 58, 76, 0.07);
        overflow: hidden;
    }

    /* =========================
       HEADER
       ========================= */

    .detail-sampel-header {
        min-height: 64px;
        padding: 15px 20px;
        border-bottom: 1px solid #e8eef2;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
    }

    .detail-sampel-title {
        margin: 0;
        color: #294257;
        font-size: 1rem;
        font-weight: 700;
        line-height: 1.4;
    }

    .detail-sampel-title-name {
        color: #159b98;
    }

    .detail-sampel-header-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-shrink: 0;
    }

    .detail-btn {
        min-height: 38px;
        padding: 0 15px;
        border-radius: 7px;
        font-size: 0.78rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
    }

    .detail-btn-back {
        border: 1px solid #d7e0e5;
        background: #fff;
        color: #657887;
    }

    .detail-btn-back:hover {
        background: #f7f9fa;
        color: #526a79;
        border-color: #cbd6dc;
    }

    .detail-btn-edit {
        border: 1px solid #159b98;
        background: #159b98;
        color: #fff;
    }

    .detail-btn-edit:hover {
        background: #128986;
        border-color: #128986;
        color: #fff;
    }

    /* =========================
       BODY
       ========================= */

    .detail-sampel-body {
        padding: 22px;
    }

    /* =========================
       STATUS / KODE
       ========================= */

    .detail-summary {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
        margin-bottom: 22px;
    }

    .detail-summary-box {
        min-height: 78px;
        padding: 15px 17px;
        border: 1px solid #e0e8ed;
        border-radius: 9px;
        background: #f8fafb;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
    }

    .detail-summary-label {
        display: block;
        margin-bottom: 6px;
        color: #718493;
        font-size: 0.72rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .detail-summary-code {
        color: #294257;
        font-family: monospace;
        font-size: 0.88rem;
        font-weight: 700;
    }

    .detail-status {
        display: inline-flex;
        align-items: center;
        min-height: 27px;
        padding: 4px 11px;
        border: 1px solid #f0d99c;
        border-radius: 20px;
        background: #fff8e6;
        color: #916d17;
        font-size: 0.72rem;
        font-weight: 700;
    }

    .detail-summary-right {
        text-align: right;
    }

    /* =========================
       SECTION
       ========================= */

    .detail-section {
        margin-bottom: 18px;
        border: 1px solid #e0e8ed;
        border-radius: 9px;
        background: #fff;
        overflow: hidden;
    }

    .detail-section:last-child {
        margin-bottom: 0;
    }

    .detail-section-header {
        min-height: 51px;
        padding: 13px 16px;
        border-bottom: 1px solid #e8eef2;
        background: #f9fbfc;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .detail-section-icon {
        width: 30px;
        height: 30px;
        flex: 0 0 30px;
        border-radius: 7px;
        background: #e4f6f4;
        color: #159b98;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
    }

    .detail-section-title {
        margin: 0;
        color: #294257;
        font-size: 0.86rem;
        font-weight: 700;
    }

    .detail-section-body {
        padding: 15px 17px;
    }

    /* =========================
       DETAIL ROW
       ========================= */

    .detail-info-table {
        width: 100%;
        margin: 0;
        border-collapse: collapse;
    }

    .detail-info-table tr {
        border-bottom: 1px solid #f0f3f5;
    }

    .detail-info-table tr:last-child {
        border-bottom: 0;
    }

    .detail-info-table td {
        padding: 9px 6px;
        vertical-align: middle;
        font-size: 0.80rem;
    }

    .detail-info-table td:first-child {
        width: 38%;
        padding-left: 0;
        color: #718493;
        font-weight: 500;
    }

    .detail-info-table td:last-child {
        padding-right: 0;
        color: #40596a;
    }

    .detail-info-table strong {
        color: #294257;
        font-weight: 700;
    }

    .detail-info-table .separator {
        color: #b2bec5;
        padding-right: 4px;
    }

    /* =========================
       TWO COLUMN GRID
       ========================= */

    .detail-columns {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 18px;
        margin-bottom: 18px;
    }

    .detail-columns .detail-section {
        margin-bottom: 0;
    }

    /* =========================
       TEXT BOX
       ========================= */

    .detail-text-item {
        margin-bottom: 15px;
    }

    .detail-text-item:last-child {
        margin-bottom: 0;
    }

    .detail-text-label {
        display: block;
        margin-bottom: 6px;
        color: #718493;
        font-size: 0.72rem;
        font-weight: 600;
    }

    .detail-text-box {
        min-height: 58px;
        padding: 10px 12px;
        border: 1px solid #e0e8ed;
        border-radius: 7px;
        background: #f8fafb;
        color: #526a79;
        font-size: 0.79rem;
        line-height: 1.6;
        word-break: break-word;
    }

    /* =========================
       EVALUASI
       ========================= */

    .detail-evaluasi-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        margin-top: 14px;
    }

    .detail-evaluasi-item {
        padding: 10px 12px;
        border: 1px solid #e6ecef;
        border-radius: 7px;
        background: #f9fbfc;
    }

    .detail-evaluasi-label {
        display: block;
        margin-bottom: 3px;
        color: #7a8c99;
        font-size: 0.70rem;
        font-weight: 600;
    }

    .detail-evaluasi-value {
        color: #294257;
        font-size: 0.80rem;
        font-weight: 700;
    }

    /* =========================
       META INFORMATION
       ========================= */

    .detail-meta {
        margin-top: 15px;
        padding-top: 13px;
        border-top: 1px solid #edf1f3;
    }

    .detail-meta-item {
        display: block;
        margin-bottom: 4px;
        color: #718493;
        font-size: 0.70rem;
        line-height: 1.5;
    }

    .detail-meta-item:last-child {
        margin-bottom: 0;
    }

    .detail-meta-item strong {
        color: #526a79;
        font-weight: 600;
    }

    /* =========================
       RESPONSIVE
       ========================= */

    @media (max-width: 992px) {
        .detail-columns {
            grid-template-columns: 1fr;
        }

        .detail-columns .detail-section {
            margin-bottom: 0;
        }
    }

    @media (max-width: 768px) {
        .detail-sampel-header {
            align-items: flex-start;
            flex-direction: column;
        }

        .detail-sampel-header-actions {
            width: 100%;
        }

        .detail-btn {
            flex: 1;
        }

        .detail-summary {
            grid-template-columns: 1fr;
        }

        .detail-summary-right {
            text-align: left;
        }

        .detail-sampel-body {
            padding: 16px;
        }
    }

    @media (max-width: 576px) {
        .detail-sampel-header {
            padding: 14px 15px;
        }

        .detail-sampel-title {
            font-size: 0.92rem;
        }

        .detail-sampel-body {
            padding: 12px;
        }

        .detail-section-body {
            padding: 12px;
        }

        .detail-info-table td {
            padding: 8px 4px;
            font-size: 0.76rem;
        }

        .detail-info-table td:first-child {
            width: 42%;
        }

        .detail-evaluasi-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="detail-sampel-page">

    <div class="detail-sampel-card">

        <!-- HEADER -->
        <div class="detail-sampel-header">
            <h5 class="detail-sampel-title">
                Detail Sampel:
                <span class="detail-sampel-title-name">
                    <?php echo html_escape($sampel['nama_sampel']); ?>
                </span>
            </h5>

            <div class="detail-sampel-header-actions">
                <a href="<?php echo site_url('sampel'); ?>" class="detail-btn detail-btn-back">
                    &larr; Kembali ke Daftar
                </a>

                <a href="<?php echo site_url('sampel/edit/' . $sampel['id']); ?>" class="detail-btn detail-btn-edit">
                    Edit Sampel
                </a>
            </div>
        </div>

        <!-- BODY -->
        <div class="detail-sampel-body">

            <!-- STATUS & KODE -->
            <div class="detail-summary">

                <div class="detail-summary-box">
                    <div>
                        <span class="detail-summary-label">
                            Status Alur Sampel
                        </span>

                        <span class="detail-status">
                            <?php echo html_escape($sampel['status']); ?>
                        </span>
                    </div>
                </div>

                <div class="detail-summary-box detail-summary-right">
                    <div style="width: 100%;">
                        <span class="detail-summary-label">
                            Kode Sampel
                        </span>

                        <strong class="detail-summary-code">
                            <?php echo html_escape($sampel['kode_sampel_manual'] ?: ($sampel['no'] ? 'NO-' . $sampel['no'] : 'SMP-' . $sampel['id'])); ?>
                        </strong>
                    </div>
                </div>

            </div>

            <!-- IDENTITAS & SAMPLING + DETAIL PRODUK -->
            <div class="detail-columns">

                <!-- IDENTITAS & SAMPLING -->
                <div class="detail-section">

                    <div class="detail-section-header">
                        <div class="detail-section-icon">
                            <i class="bi bi-box-seam"></i>
                        </div>

                        <h6 class="detail-section-title">
                            Identitas &amp; Sampling
                        </h6>
                    </div>

                    <div class="detail-section-body">

                        <table class="detail-info-table">

                            <tr>
                                <td>No. Urut</td>
                                <td>
                                    <span class="separator">:</span>
                                    <strong>
                                        <?php echo html_escape($sampel['no'] ?: '-'); ?>
                                    </strong>
                                </td>
                            </tr>

                            <tr>
                                <td>Kode Manual</td>
                                <td>
                                    <span class="separator">:</span>
                                    <?php echo html_escape($sampel['kode_sampel_manual'] ?: '-'); ?>
                                </td>
                            </tr>

                            <tr>
                                <td>Kategori Sampel</td>
                                <td>
                                    <span class="separator">:</span>
                                    <?php echo html_escape($sampel['kategori_sampel'] ?: '-'); ?>
                                </td>
                            </tr>

                            <tr>
                                <td>Sub Kategori</td>
                                <td>
                                    <span class="separator">:</span>
                                    <?php echo html_escape($sampel['sub_kategori'] ?: '-'); ?>
                                </td>
                            </tr>

                            <tr>
                                <td>Jenis/Kelas Terapi</td>
                                <td>
                                    <span class="separator">:</span>
                                    <?php echo html_escape($sampel['jenis_kelas_terapi'] ?: '-'); ?>
                                </td>
                            </tr>

                            <tr>
                                <td>Kategori Sarana</td>
                                <td>
                                    <span class="separator">:</span>
                                    <?php echo html_escape($sampel['kategori_sarana'] ?: '-'); ?>
                                </td>
                            </tr>

                            <tr>
                                <td>Nama Sarana</td>
                                <td>
                                    <span class="separator">:</span>
                                    <?php echo html_escape($sampel['nama_sarana'] ?: '-'); ?>
                                </td>
                            </tr>

                            <tr>
                                <td>Kabupaten / Kota</td>
                                <td>
                                    <span class="separator">:</span>
                                    <?php echo html_escape($sampel['kabupaten_kota'] ?: '-'); ?>
                                </td>
                            </tr>

                            <tr>
                                <td>Tanggal Sampling</td>
                                <td>
                                    <span class="separator">:</span>
                                    <?php echo html_escape($sampel['tanggal_sampling'] ?: '-'); ?>
                                </td>
                            </tr>

                            <tr>
                                <td>NO SIPT</td>
                                <td>
                                    <span class="separator">:</span>
                                    <?php echo html_escape($sampel['no_sipt'] ?: '-'); ?>
                                </td>
                            </tr>

                            <tr>
                                <td>Surat Tugas (Surtug)</td>
                                <td>
                                    <span class="separator">:</span>
                                    <?php echo html_escape($sampel['surtug'] ?: '-'); ?>
                                </td>
                            </tr>

                        </table>

                    </div>
                </div>

                <!-- DETAIL PRODUK & ALOKASI -->
                <div class="detail-section">

                    <div class="detail-section-header">
                        <div class="detail-section-icon">
                            <i class="bi bi-clipboard2-data"></i>
                        </div>

                        <h6 class="detail-section-title">
                            Detail Produk &amp; Alokasi
                        </h6>
                    </div>

                    <div class="detail-section-body">

                        <table class="detail-info-table">

                            <tr>
                                <td>NIE (Izin Edar)</td>
                                <td>
                                    <span class="separator">:</span>
                                    <?php echo html_escape($sampel['nomor_izin_edar'] ?: '-'); ?>
                                </td>
                            </tr>

                            <tr>
                                <td>No. Bets</td>
                                <td>
                                    <span class="separator">:</span>
                                    <?php echo html_escape($sampel['no_bets'] ?: '-'); ?>
                                </td>
                            </tr>

                            <tr>
                                <td>Kedaluwarsa</td>
                                <td>
                                    <span class="separator">:</span>
                                    <?php echo html_escape($sampel['kedaluwarsa'] ?: '-'); ?>
                                </td>
                            </tr>

                            <tr>
                                <td>Kondisi Produk</td>
                                <td>
                                    <span class="separator">:</span>
                                    <?php echo html_escape($sampel['kondisi_produk'] ?: '-'); ?>
                                </td>
                            </tr>

                            <tr>
                                <td>Kemasan</td>
                                <td>
                                    <span class="separator">:</span>
                                    <?php echo html_escape($sampel['kemasan'] ?: '-'); ?>
                                </td>
                            </tr>

                            <tr>
                                <td>Penyimpanan</td>
                                <td>
                                    <span class="separator">:</span>
                                    <?php echo html_escape($sampel['penyimpanan'] ?: '-'); ?>
                                </td>
                            </tr>

                            <tr>
                                <td>Jumlah Kimia</td>
                                <td>
                                    <span class="separator">:</span>
                                    <?php echo html_escape($sampel['jumlah_kimia'] ?: '-'); ?>
                                </td>
                            </tr>

                            <tr>
                                <td>Jumlah Mikro</td>
                                <td>
                                    <span class="separator">:</span>
                                    <?php echo html_escape($sampel['jumlah_mikro'] ?: '-'); ?>
                                </td>
                            </tr>

                            <tr>
                                <td>Jumlah Arsip</td>
                                <td>
                                    <span class="separator">:</span>
                                    <?php echo html_escape($sampel['jumlah_arsip'] ?: '-'); ?>
                                </td>
                            </tr>

                            <tr>
                                <td>Jumlah Penandaan</td>
                                <td>
                                    <span class="separator">:</span>
                                    <?php echo html_escape($sampel['jumlah_penandaan'] ?: '-'); ?>
                                </td>
                            </tr>

                            <tr>
                                <td>Jumlah Total</td>
                                <td>
                                    <span class="separator">:</span>
                                    <strong>
                                        <?php echo html_escape($sampel['jumlah_total'] ?: '-'); ?>
                                    </strong>
                                </td>
                            </tr>

                        </table>

                    </div>
                </div>

            </div>

            <!-- PERUSAHAAN & KOMPOSISI + EVALUASI -->
            <div class="detail-columns">

                <!-- PERUSAHAAN & KOMPOSISI -->
                <div class="detail-section">

                    <div class="detail-section-header">
                        <div class="detail-section-icon">
                            <i class="bi bi-building"></i>
                        </div>

                        <h6 class="detail-section-title">
                            Perusahaan &amp; Komposisi
                        </h6>
                    </div>

                    <div class="detail-section-body">

                        <div class="detail-text-item">
                            <span class="detail-text-label">
                                Nama &amp; Alamat Perusahaan
                            </span>

                            <div class="detail-text-box">
                                <?php echo nl2br(html_escape($sampel['nama_alamat_perusahaan'] ?: '-')); ?>
                            </div>
                        </div>

                        <div class="detail-text-item">
                            <span class="detail-text-label">
                                Komposisi
                            </span>

                            <div class="detail-text-box">
                                <?php echo nl2br(html_escape($sampel['komposisi'] ?: '-')); ?>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- EVALUASI ADMINISTRASI & STATUS -->
                <div class="detail-section">

                    <div class="detail-section-header">
                        <div class="detail-section-icon">
                            <i class="bi bi-file-earmark-check"></i>
                        </div>

                        <h6 class="detail-section-title">
                            Evaluasi Administrasi &amp; Status
                        </h6>
                    </div>

                    <div class="detail-section-body">

                        <div class="detail-text-item">
                            <span class="detail-text-label">
                                Penandaan (Field Utama)
                            </span>

                            <div class="detail-text-box">
                                <?php echo nl2br(html_escape($sampel['penandaan'] ?: '-')); ?>
                            </div>
                        </div>

                        <div class="detail-evaluasi-grid">

                            <div class="detail-evaluasi-item">
                                <span class="detail-evaluasi-label">
                                    Harga
                                </span>
                                <span class="detail-evaluasi-value">
                                    <?php echo html_escape($sampel['harga'] ?: '-'); ?>
                                </span>
                            </div>

                            <div class="detail-evaluasi-item">
                                <span class="detail-evaluasi-label">
                                    TIE
                                </span>
                                <span class="detail-evaluasi-value">
                                    <?php echo html_escape($sampel['tie'] ?: '-'); ?>
                                </span>
                            </div>

                            <div class="detail-evaluasi-item">
                                <span class="detail-evaluasi-label">
                                    MK
                                </span>
                                <span class="detail-evaluasi-value">
                                    <?php echo html_escape($sampel['mk'] ?: '-'); ?>
                                </span>
                            </div>

                            <div class="detail-evaluasi-item">
                                <span class="detail-evaluasi-label">
                                    TMK
                                </span>
                                <span class="detail-evaluasi-value">
                                    <?php echo html_escape($sampel['tmk'] ?: '-'); ?>
                                </span>
                            </div>

                        </div>

                        <div class="detail-meta">

                            <span class="detail-meta-item">
                                Balai Penguji:
                                <strong>
                                    <?php echo html_escape($sampel['balai_penguji'] ?: '-'); ?>
                                </strong>
                            </span>

                            <span class="detail-meta-item">
                                Petugas Registrasi:
                                <strong>
                                    <?php echo html_escape($sampel['nama_pembuat'] ?: 'Sistem'); ?>
                                </strong>
                                (<?php echo html_escape($sampel['created_at']); ?>)
                            </span>

                        </div>

                    </div>
                </div>

            </div>

        </div>
    </div>
</div>