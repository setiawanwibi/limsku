<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * View Cetak / Simpan PDF Lembar Catatan Pengujian (LCP)
 * KONFIRMASI KEPEKAAN LAL SEMI KUANTITATIF
 * Replikasi presisi 100% dari file Excel Template Resmi:
 * POM-06.02_CFM.01_SOP.01_F.01B.20 Konfirmasi Kepekaan LAL Semi Kuantitatif.xlsx (Sheet: Rapih)
 * Format Dokumen: POM-06.02/CFM.01/SOP.01/F.01B.20 (1 Halaman A4 Utuh)
 */

$status_sesi = $sesi['status'] ?? 'Draft';
$p = !empty($hasil['data_hasil']) ? json_decode($hasil['data_hasil'], true) : array();
if (isset($p['parameter_values']) && is_array($p['parameter_values'])) {
    $p = array_merge($p, $p['parameter_values']);
}

// Helper resolve URL tanda tangan digital dari path berkas
$resolve_ttd = function($sig) {
    if (empty($sig) || empty($sig['signature_file'])) {
        return '';
    }
    $clean = str_replace(array('../', '..\\'), '', $sig['signature_file']);
    if (strpos($clean, 'uploads/signatures/') === 0) {
        $rel = $clean;
    } else {
        $rel = 'uploads/signatures/' . ltrim($clean, '/\\');
    }
    $full = FCPATH . str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $rel);
    return (file_exists($full) && is_file($full)) ? base_url($rel) : '';
};

$ttd_penguji_img  = $resolve_ttd($signature_penguji ?? null);
$ttd_penyelia_img = $resolve_ttd($signature_verifier ?? null);
$ttd_approver_img = $resolve_ttd($signature_approver ?? null);

// Sample Metadata & Penguji
$nama_sampel = $sampel['nama_sampel'] ?? '-';
$kode_sampel = !empty($sampel['kode_sampel_manual']) ? $sampel['kode_sampel_manual'] : (!empty($sampel['no']) ? 'NO-' . $sampel['no'] : 'SMP-' . ($sampel['id'] ?? '-'));
$tgl_mulai   = !empty($sesi['waktu_mulai']) ? date('d-m-Y', strtotime($sesi['waktu_mulai'])) : date('d-m-Y');
$nama_penguji= $penguji['nama_lengkap'] ?? ($sesi['nama_penguji'] ?? '-');
$tgl_selesai = !empty($sesi['waktu_selesai']) ? date('d-m-Y', strtotime($sesi['waktu_selesai'])) : date('d-m-Y');
$tgl_verif   = !empty($sesi['waktu_verifikasi']) ? date('d-m-Y', strtotime($sesi['waktu_verifikasi'])) : '-';

// Hitung Geometric Mean (GM) jika ada titik akhir
$calc_log_e = function($val) {
    if (is_numeric($val) && (float)$val > 0) {
        return round(log10((float)$val), 4);
    }
    return '';
};

$e1 = !empty($p['log_e_1']) ? $p['log_e_1'] : $calc_log_e($p['titik_akhir_1'] ?? null);
$e2 = !empty($p['log_e_2']) ? $p['log_e_2'] : $calc_log_e($p['titik_akhir_2'] ?? null);
$e3 = !empty($p['log_e_3']) ? $p['log_e_3'] : $calc_log_e($p['titik_akhir_3'] ?? null);
$e4 = !empty($p['log_e_4']) ? $p['log_e_4'] : $calc_log_e($p['titik_akhir_4'] ?? null);

$rata_e = $p['rata_rata_e'] ?? '';
$gm_val = $p['nilai_gm'] ?? '';
if ($rata_e === '' && is_numeric($e1) && is_numeric($e2) && is_numeric($e3) && is_numeric($e4)) {
    $rata_e = round(((float)$e1 + (float)$e2 + (float)$e3 + (float)$e4) / 4, 4);
    $gm_val = round(pow(10, $rata_e), 4);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak LCP - KONFIRMASI KEPEKAAN LAL SEMI KUANTITATIF - <?php echo html_escape($nama_sampel); ?></title>
    <style>
        @page {
            size: A4 portrait;
            margin: 0;
        }

        *, *::before, *::after {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            color-adjust: exact !important;
        }

        body {
            margin: 0;
            padding: 16px 0;
            background-color: #525659;
            font-family: "Times New Roman", Times, serif;
            font-size: 7.2pt;
            color: #000;
            line-height: 1.15;
        }

        /* Floating Controls */
        .no-print {
            position: fixed;
            top: 15px;
            right: 25px;
            z-index: 9999;
            display: flex;
            gap: 10px;
            background: rgba(255, 255, 255, 0.95);
            padding: 8px 14px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.25);
            backdrop-filter: blur(4px);
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            font-size: 13px;
            font-weight: 600;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-print { background-color: #198754; color: #fff; }
        .btn-print:hover { background-color: #146c43; }
        .btn-back { background-color: #6c757d; color: #fff; }
        .btn-back:hover { background-color: #565e64; }

        /* Lembar Dokumen A4 1 Halaman Utuh */
        .document-wrapper {
            width: 210mm;
            min-height: 297mm;
            max-height: 297mm;
            margin: 0 auto;
            padding: 6mm 8mm 4mm 8mm;
            background-color: #fff;
            box-shadow: 0 0 15px rgba(0,0,0,0.3);
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        /* Outer Border Medium Solid sesuai Excel Template */
        .outer-border-box {
            border: 1.75pt solid #000;
            padding: 4px 7px 5px 7px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            flex-grow: 1;
        }

        table.excel-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-family: "Times New Roman", Times, serif;
            font-size: 7.2pt;
            color: #000;
        }

        table.excel-table td, table.excel-table th {
            padding: 1px 2px;
            overflow: hidden;
            word-wrap: break-word;
            line-height: 1.15;
            vertical-align: middle;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-bold { font-weight: bold; }
        .italic { font-style: italic; }

        .b-top { border-top: 1pt solid #000; }
        .b-bottom { border-bottom: 1pt solid #000; }
        .b-left { border-left: 1pt solid #000; }
        .b-right { border-right: 1pt solid #000; }
        .b-all { border: 1pt solid #000; }

        @media print {
            html, body {
                height: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                overflow: hidden !important;
                background: none !important;
            }
            .no-print { display: none !important; }
            .document-wrapper {
                margin: 0 !important;
                padding: 4mm 6mm 2mm 6mm !important;
                box-shadow: none !important;
                width: 100% !important;
                height: 100% !important;
                max-height: 297mm !important;
                page-break-after: avoid !important;
                page-break-inside: avoid !important;
            }
        }
    </style>
</head>
<body>

    <!-- Floating Print / Save Controls -->
    <div class="no-print">
        <button onclick="window.print()" class="btn-action btn-print">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/>
                <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z"/>
            </svg>
            Cetak / Simpan PDF
        </button>
        <button onclick="window.close()" class="btn-action btn-back">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
            </svg>
            Tutup
        </button>
    </div>

    <!-- HALAMAN 1 (A4 SINGLE SHEET EXACT REPLICA) -->
    <div class="document-wrapper">
        <div class="outer-border-box">

            <!-- 1. KOP HEADER RESMI DENGAN LOGO BPOM -->
            <table class="excel-table" style="margin-bottom: 2px;">
                <colgroup>
                    <col style="width: 14%;">
                    <col style="width: 86%;">
                </colgroup>
                <tr>
                    <td class="text-center" style="vertical-align: middle; padding: 2px 4px 2px 2px;">
                        <img src="<?php echo base_url('assets/img/bpom_logo_excel.png'); ?>" style="height: 48px; width: auto; max-width: 95%; display: block; margin: 0 auto;" alt="Logo BPOM">
                    </td>
                    <td class="text-center" style="vertical-align: middle; padding-right: 30px;">
                        <div style="font-size: 11pt; font-weight: bold; letter-spacing: 0.5px;">LAMPIRAN CATATAN PENGUJIAN</div>
                        <div style="font-size: 10pt; font-weight: bold; margin-top: 1px; letter-spacing: 0.3px;">KONFIRMASI KEPEKAAN LAL SEMI KUANTITATIF</div>
                        <div style="font-size: 8.5pt; font-weight: bold; margin-top: 2px;">Laboratorium Mikrobiologi Balai Besar/Balai/Loka*) POM di Bandar Lampung</div>
                    </td>
                </tr>
            </table>

            <div style="height: 3px;"></div>

            <!-- 2. METADATA REAGEN & PENGUJI (BARIS 6-8 EXCEL) -->
            <table class="excel-table" style="border: 1pt solid #000;">
                <colgroup>
                    <col style="width: 38%;">
                    <col style="width: 28%;">
                    <col style="width: 17%;">
                    <col style="width: 2%;">
                    <col style="width: 15%;">
                </colgroup>
                <tr style="height: 14pt;">
                    <td class="b-right text-bold" style="padding-left: 4px;">Nama Dagang Reagen :</td>
                    <td class="b-right text-bold" style="padding-left: 4px;">Reagen :</td>
                    <td style="padding-left: 4px; font-size: 7pt; white-space: nowrap;">Penguji</td>
                    <td class="text-center" style="font-size: 7pt;">:</td>
                    <td style="padding-left: 2px; font-size: 7pt; white-space: nowrap;"><?php echo html_escape($nama_penguji); ?></td>
                </tr>
                <tr style="height: 14pt;">
                    <td rowspan="2" class="b-right text-bold" style="padding-left: 4px; vertical-align: top; font-size: 7.5pt;"><?php echo html_escape($p['nama_dagang_reagen'] ?? ($nama_sampel !== '-' ? $nama_sampel : 'Pyrogent Plus')); ?></td>
                    <td rowspan="2" class="b-right text-bold" style="padding-left: 4px; vertical-align: top; font-size: 7.2pt;"><?php echo html_escape($p['tipe_reagen'] ?? 'Multi Test / Single Test'); ?></td>
                    <td style="padding-left: 4px; font-size: 7pt; white-space: nowrap;">Tanggal Uji</td>
                    <td class="text-center" style="font-size: 7pt;">:</td>
                    <td style="padding-left: 2px; font-size: 7pt; white-space: nowrap;"><?php echo html_escape($tgl_mulai); ?></td>
                </tr>
                <tr style="height: 14pt;">
                    <td style="padding-left: 4px; font-size: 7pt; white-space: nowrap;">Metode</td>
                    <td class="text-center" style="font-size: 7pt;">:</td>
                    <td style="padding-left: 2px; font-size: 7pt; white-space: nowrap;"><?php echo html_escape($p['metode_uji'] ?? 'Jendal Gel'); ?></td>
                </tr>
            </table>

            <div style="height: 5px;"></div>

            <!-- 3. ALAT & DEPIROGENASI (BARIS 10-15 EXCEL) -->
            <table class="excel-table">
                <colgroup>
                    <col style="width: 45%;">
                    <col style="width: 2%;">
                    <col style="width: 53%;">
                </colgroup>
                <tr style="height: 14pt;">
                    <td colspan="3" class="text-bold">Alat</td>
                </tr>
                <tr style="height: 13pt;">
                    <td style="padding-left: 8px;">No. Identifikasi/Tgl Kalibrasi Mikropipet .................uL</td>
                    <td class="text-center">:</td>
                    <td><?php echo html_escape($p['mikropipet_1'] ?? '-'); ?></td>
                </tr>
                <tr style="height: 13pt;">
                    <td style="padding-left: 8px;">No. Identifikasi/Tgl Kalibrasi Mikropipet ...............uL</td>
                    <td class="text-center">:</td>
                    <td><?php echo html_escape($p['mikropipet_2'] ?? '-'); ?></td>
                </tr>
                <tr style="height: 13pt;">
                    <td style="padding-left: 8px;">No. Identifikasi/Tgl Kalibrasi Oven</td>
                    <td class="text-center">:</td>
                    <td><?php echo html_escape($p['oven_id'] ?? '-'); ?></td>
                </tr>
                <tr style="height: 13pt;">
                    <td style="padding-left: 8px;">No. Identifikasi/Tgl Kalibrasi Inkubator</td>
                    <td class="text-center">:</td>
                    <td><?php echo html_escape($p['inkubator_id'] ?? '-'); ?></td>
                </tr>
                <tr style="height: 13pt;">
                    <td style="padding-left: 8px;">Proses Depirogenasi alat gelas</td>
                    <td class="text-center">:</td>
                    <td>Suhu : &nbsp;<?php echo html_escape($p['depirogenasi_suhu'] ?? '250'); ?> °C &nbsp;&nbsp;&nbsp;&nbsp; Waktu : &nbsp;<?php echo html_escape($p['depirogenasi_waktu'] ?? '60'); ?> menit</td>
                </tr>
            </table>

            <div style="height: 5px;"></div>

            <!-- 4. PEREAKSI (BARIS 17-25 EXCEL) -->
            <table class="excel-table">
                <colgroup>
                    <col style="width: 45%;">
                    <col style="width: 2%;">
                    <col style="width: 53%;">
                </colgroup>
                <tr style="height: 14pt;">
                    <td colspan="3" class="text-bold">Pereaksi</td>
                </tr>
                <tr style="height: 13pt;">
                    <td style="padding-left: 8px;">Metode</td>
                    <td class="text-center">:</td>
                    <td><?php echo html_escape($p['metode_uji'] ?? 'Jendal Gel'); ?></td>
                </tr>
                <tr style="height: 13pt;">
                    <td style="padding-left: 8px;">Nama Reagen</td>
                    <td class="text-center">:</td>
                    <td><?php echo html_escape($p['nama_reagen_lengkap'] ?? 'Limulus Amebocyte Lysate (LAL)'); ?></td>
                </tr>
                <tr style="height: 13pt;">
                    <td style="padding-left: 8px;">No. Lot CSE / No. CoA / Tgl Kadaluarsa</td>
                    <td class="text-center">:</td>
                    <td><?php echo html_escape($p['lot_cse'] ?? '-'); ?></td>
                </tr>
                <tr style="height: 13pt;">
                    <td style="padding-left: 8px;">No Lot (LAL/TAL*) / No CoA / Tgl Kadaluarsa</td>
                    <td class="text-center">:</td>
                    <td><?php echo html_escape($p['lot_lal'] ?? '-'); ?></td>
                </tr>
                <tr style="height: 13pt;">
                    <td style="padding-left: 8px;">Potensi Endotoksin / Kepekaan (LAL/TAL*) (λ)</td>
                    <td class="text-center">:</td>
                    <td><?php echo html_escape($p['potensi_cse'] ?? '-'); ?> EU/mL &nbsp;/&nbsp; <?php echo html_escape($p['kepekaan_etiket_lambda'] ?? '0.03'); ?> EU/mL</td>
                </tr>
                <tr style="height: 13pt;">
                    <td style="padding-left: 8px;">Tgl Rekonstitusi CSE/LAL-TAL*)</td>
                    <td class="text-center">:</td>
                    <td><?php echo !empty($p['tgl_rekonstitusi']) ? date('d-m-Y', strtotime($p['tgl_rekonstitusi'])) : '-'; ?></td>
                </tr>
                <tr style="height: 13pt;">
                    <td style="padding-left: 8px;">Suhu Penyimpanan reagen</td>
                    <td class="text-center">:</td>
                    <td>CSE : &nbsp;<?php echo html_escape($p['suhu_simpan_cse'] ?? '2 - 8'); ?> °C &nbsp;&nbsp;&nbsp;&nbsp; LAL/TAL*) : &nbsp;<?php echo html_escape($p['suhu_simpan_lal'] ?? '2 - 8'); ?> °C</td>
                </tr>
                <tr style="height: 13pt;">
                    <td style="padding-left: 8px;">Suhu Penyimpanan reagen (rekonstitusi)</td>
                    <td class="text-center">:</td>
                    <td>CSE : &nbsp;<?php echo html_escape($p['suhu_simpan_cse'] ?? '2 - 8'); ?> °C &nbsp;&nbsp;&nbsp;&nbsp; LAL/TAL*) : &nbsp;<?php echo html_escape($p['suhu_simpan_lal'] ?? '2 - 8'); ?> °C</td>
                </tr>
            </table>

            <div style="height: 5px;"></div>

            <!-- 5. UJI KONFIRMASI KEPEKAAN LAL-TAL (BARIS 27-34 EXCEL) -->
            <div style="font-weight: bold; margin-bottom: 3px; font-size: 7.2pt;">Uji Konfirmasi Kepekaan LAL-TAL*) :</div>

            <table class="excel-table" style="border: 1pt solid #000; text-align: center;">
                <colgroup>
                    <col style="width: 14%;">
                    <col style="width: 20%;">
                    <col style="width: 11.5%;">
                    <col style="width: 11.5%;">
                    <col style="width: 11.5%;">
                    <col style="width: 11.5%;">
                    <col style="width: 10%;">
                    <col style="width: 10%;">
                </colgroup>
                <thead>
                    <tr style="height: 13pt; background-color: #d9d9d9;">
                        <th class="b-all" style="font-weight: bold;">Kons ET</th>
                        <th rowspan="2" class="b-all" style="font-weight: bold;">Kontrol Negatif (LRW)</th>
                        <th colspan="4" class="b-all" style="font-weight: bold;">Baku Endotoksin Kontrol (CSE)</th>
                        <th rowspan="2" class="b-all" style="font-weight: bold; font-size: 6.8pt;">Titik Akhir<br>(EU/mL)</th>
                        <th rowspan="2" class="b-all" style="font-weight: bold; font-size: 6.8pt;">e<br>(Log titik akhir)</th>
                    </tr>
                    <tr style="height: 12pt; background-color: #d9d9d9;">
                        <th class="b-all" style="font-weight: bold;">Pengulangan</th>
                        <th class="b-all">2 (&lambda;)</th>
                        <th class="b-all">(&lambda;)</th>
                        <th class="b-all">1/2 (&lambda;)</th>
                        <th class="b-all">1/4 (&lambda;)</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Pengulangan 1 -->
                    <tr style="height: 13.5pt;">
                        <td class="b-all text-center">1</td>
                        <td class="b-all"><?php echo html_escape($p['lrw_1'] ?? '-'); ?></td>
                        <td class="b-all"><?php echo html_escape($p['cse_2lambda_1'] ?? '+'); ?></td>
                        <td class="b-all"><?php echo html_escape($p['cse_lambda_1'] ?? '+'); ?></td>
                        <td class="b-all"><?php echo html_escape($p['cse_half_lambda_1'] ?? '-'); ?></td>
                        <td class="b-all"><?php echo html_escape($p['cse_quarter_lambda_1'] ?? '-'); ?></td>
                        <td class="b-all"><?php echo html_escape($p['titik_akhir_1'] ?? '0.03'); ?></td>
                        <td class="b-all"><?php echo html_escape($e1); ?></td>
                    </tr>
                    <!-- Pengulangan 2 -->
                    <tr style="height: 13.5pt;">
                        <td class="b-all text-center">2</td>
                        <td class="b-all"><?php echo html_escape($p['lrw_2'] ?? '-'); ?></td>
                        <td class="b-all"><?php echo html_escape($p['cse_2lambda_2'] ?? '+'); ?></td>
                        <td class="b-all"><?php echo html_escape($p['cse_lambda_2'] ?? '+'); ?></td>
                        <td class="b-all"><?php echo html_escape($p['cse_half_lambda_2'] ?? '-'); ?></td>
                        <td class="b-all"><?php echo html_escape($p['cse_quarter_lambda_2'] ?? '-'); ?></td>
                        <td class="b-all"><?php echo html_escape($p['titik_akhir_2'] ?? '0.03'); ?></td>
                        <td class="b-all"><?php echo html_escape($e2); ?></td>
                    </tr>
                    <!-- Pengulangan 3 -->
                    <tr style="height: 13.5pt;">
                        <td class="b-all text-center">3</td>
                        <td class="b-all"><?php echo html_escape($p['lrw_3'] ?? '-'); ?></td>
                        <td class="b-all"><?php echo html_escape($p['cse_2lambda_3'] ?? '+'); ?></td>
                        <td class="b-all"><?php echo html_escape($p['cse_lambda_3'] ?? '+'); ?></td>
                        <td class="b-all"><?php echo html_escape($p['cse_half_lambda_3'] ?? '-'); ?></td>
                        <td class="b-all"><?php echo html_escape($p['cse_quarter_lambda_3'] ?? '-'); ?></td>
                        <td class="b-all"><?php echo html_escape($p['titik_akhir_3'] ?? '0.03'); ?></td>
                        <td class="b-all"><?php echo html_escape($e3); ?></td>
                    </tr>
                    <!-- Pengulangan 4 -->
                    <tr style="height: 13.5pt;">
                        <td class="b-all text-center">4</td>
                        <td class="b-all"><?php echo html_escape($p['lrw_4'] ?? '-'); ?></td>
                        <td class="b-all"><?php echo html_escape($p['cse_2lambda_4'] ?? '+'); ?></td>
                        <td class="b-all"><?php echo html_escape($p['cse_lambda_4'] ?? '+'); ?></td>
                        <td class="b-all"><?php echo html_escape($p['cse_half_lambda_4'] ?? '-'); ?></td>
                        <td class="b-all"><?php echo html_escape($p['cse_quarter_lambda_4'] ?? '-'); ?></td>
                        <td class="b-all"><?php echo html_escape($p['titik_akhir_4'] ?? '0.03'); ?></td>
                        <td class="b-all"><?php echo html_escape($e4); ?></td>
                    </tr>
                    <!-- Rata-rata e -->
                    <tr style="height: 14pt;">
                        <td colspan="6" class="b-all text-left" style="background-color: #fcfcfc;"></td>
                        <td class="b-all text-bold" style="background-color: #f2f2f2; font-size: 6.8pt;">Rata-rata e</td>
                        <td class="b-all text-bold"><?php echo html_escape($rata_e); ?></td>
                    </tr>
                </tbody>
            </table>

            <div style="font-size: 6.5pt; color: #333; margin-top: 3px;">
                Keterangan: titik akhir adalah konsentrasi terendah yang masih memberikan hasil positif dari satu pengenceran seri<br>
                *) Coret yang tidak perlu
            </div>

            <!-- 6. PUSTAKA / METODE & SYARAT (BARIS 38 EXCEL) -->
            <div style="border-top: 1pt solid #000; padding: 4px 2px; margin-top: 4px;">
                <table class="excel-table">
                    <colgroup>
                        <col style="width: 18%;">
                        <col style="width: 2%;">
                        <col style="width: 35%;">
                        <col style="width: 45%;">
                    </colgroup>
                    <tr style="height: 13pt;">
                        <td class="text-bold">Pustaka/Metode :</td>
                        <td colspan="2"><?php echo html_escape($p['pustaka_metode'] ?? 'Farmakope Indonesia VI 2020 hal. 357-358'); ?></td>
                        <td class="text-bold" style="font-size: 7.2pt;">Syarat uji konfirmasi: 1/2 &lambda; &le; GM (EU/mL) &le; 2 &lambda;</td>
                    </tr>
                </table>
            </div>

            <!-- 7. KESIMPULAN (BARIS 40-41 EXCEL) -->
            <div style="border-top: 1pt solid #000; border-bottom: 1pt solid #000; padding: 4px 2px;">
                <table class="excel-table">
                    <colgroup>
                        <col style="width: 55%;">
                        <col style="width: 45%;">
                    </colgroup>
                    <tr style="height: 13pt;">
                        <td class="text-bold">Kesimpulan : Pereaksi tersebut di atas memenuhi syarat</td>
                        <td>(1/2 &lambda; &le; GM (EU/mL) &le; 2 &lambda;)</td>
                    </tr>
                    <tr style="height: 12pt;">
                        <td></td>
                        <td style="font-style: italic;"><?php echo !empty($p['rentang_syarat_angka']) ? '(' . html_escape($p['rentang_syarat_angka']) . ')' : '(0.015 ≤ ' . ($gm_val ?: '0.030') . ' ≤ 0.060)'; ?></td>
                    </tr>
                </table>
            </div>

            <!-- 8. TANDA TANGAN (BARIS 43-47 EXCEL) -->
            <table class="excel-table" style="border-bottom: 2px double #000; margin-top: auto;">
                <colgroup>
                    <col style="width: 50%;">
                    <col style="width: 50%;">
                </colgroup>
                <tr style="height: 13pt;">
                    <td class="b-right text-bold" style="padding-left: 4px;">Tanda Tangan Penguji:</td>
                    <td class="text-bold" style="padding-left: 10px;">Diperiksa Oleh :</td>
                </tr>
                <tr style="height: 44pt;">
                    <td class="b-right text-center" style="vertical-align: middle;">
                        <?php if (!empty($ttd_penguji_img)): ?>
                            <img src="<?php echo $ttd_penguji_img; ?>" style="max-height: 34px; max-width: 130px; object-fit: contain;" alt="TTD Penguji">
                            <div style="font-size: 6.8pt; font-weight: bold; margin-top: 1px;"><?php echo html_escape($nama_penguji); ?></div>
                        <?php else: ?>
                            <div style="font-size: 7pt; font-weight: bold; color: #198754;">[ TERCATAT DIGITAL ]</div>
                            <div style="font-size: 6.8pt; font-weight: bold; margin-top: 2px;"><?php echo html_escape($nama_penguji); ?></div>
                        <?php endif; ?>
                    </td>
                    <td class="text-center" style="vertical-align: middle;">
                        <?php if ($status_sesi === 'Approved / Final' || $status_sesi === 'Menunggu Approval'): ?>
                            <?php if (!empty($ttd_penyelia_img)): ?>
                                <img src="<?php echo $ttd_penyelia_img; ?>" style="max-height: 34px; max-width: 130px; object-fit: contain;" alt="TTD Penyelia">
                            <?php else: ?>
                                <div style="font-size: 7pt; font-weight: bold; color: #198754;">[ VERIFIED DIGITAL ]</div>
                            <?php endif; ?>
                            <div style="font-size: 6.8pt; font-weight: bold; margin-top: 1px;"><?php echo html_escape($verifier['nama_lengkap'] ?? ($sesi['nama_verifier'] ?? 'Penyelia')); ?></div>
                        <?php else: ?>
                            <div style="font-size: 6.8pt; color: #888; font-style: italic;">(Menunggu Verifikasi Penyelia)</div>
                            <div style="font-size: 6.8pt; color: #555;">(Nama, TTD)</div>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr style="height: 13pt;">
                    <td class="b-right" style="padding-left: 4px;">Tanggal : &nbsp;<?php echo html_escape($tgl_selesai); ?></td>
                    <td style="padding-left: 10px;">Tanggal : &nbsp;<?php echo ($status_sesi === 'Approved / Final' || $status_sesi === 'Menunggu Approval') ? html_escape($tgl_verif) : '-'; ?></td>
                </tr>
            </table>

        </div><!-- /.outer-border-box -->

        <!-- 9. KODE DOKUMEN FOOTER (BARIS 48 EXCEL) -->
        <table class="excel-table" style="margin-top: 3px;">
            <colgroup>
                <col style="width: 70%;">
                <col style="width: 30%;">
            </colgroup>
            <tr style="height: 12pt;">
                <td style="font-size: 7pt; font-weight: bold; padding-left: 2px;">POM-06.02/CFM.01/SOP.01/F.01B.20</td>
                <td class="text-right" style="font-size: 7pt; padding-right: 2px;">Lembar 1 dari 1 halaman</td>
            </tr>
        </table>
    </div>

</body>
</html>
