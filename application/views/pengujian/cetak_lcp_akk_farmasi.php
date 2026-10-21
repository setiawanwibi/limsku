<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * View Cetak / Simpan PDF Lembar Catatan Pengujian (LCP)
 * UJI ANGKA KAPANG KHAMIR SEDIAAN FARMASI
 * Replikasi presisi 100% dari file Excel Template Resmi: LCP AKK Obat.xlsx (Sheet: Rapih)
 * Dokumen Nomor: POM-06.02/CFM.01/SOP.01/F.01B.04
 */

$status_sesi = $sesi['status'] ?? 'Draft';
$p = !empty($hasil['data_hasil']) ? json_decode($hasil['data_hasil'], true) : array();
if (isset($p['parameter_values']) && is_array($p['parameter_values'])) {
    $p = array_merge($p, $p['parameter_values']);
}

// Format Helper Checkbox Checked / Unchecked
$is_checked = function($val) {
    return ($val === true || $val === 'true' || $val === '1' || $val === 1 || $val === 'on' || $val === '☑');
};

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

// Data Pengamatan Koloni Sampel Uji
$tabel_koloni = isset($p['tabel_koloni']) && is_array($p['tabel_koloni']) ? $p['tabel_koloni'] : array();

// Standarisasi baris pengenceran bila kosong/parsial
$default_dilutions = ['10^-1', '10^-2', '10^-3', '10^-4', '10^-5', '10^-6', '....'];
$rows_koloni = array();
for ($i = 0; $i < 7; $i++) {
    $def_label = $default_dilutions[$i];
    $row_data  = $tabel_koloni[$i] ?? array();
    $rows_koloni[] = array(
        'pengenceran' => $row_data['pengenceran'] ?? $def_label,
        'cawan_a'     => $row_data['cawan_a'] ?? '',
        'cawan_b'     => $row_data['cawan_b'] ?? '',
        'total'       => $row_data['total_koloni'] ?? (
            (is_numeric($row_data['cawan_a'] ?? null) && is_numeric($row_data['cawan_b'] ?? null))
                ? ((float)$row_data['cawan_a'] + (float)$row_data['cawan_b'])
                : ''
        )
    );
}

// Sample info
$nama_sampel = $sampel['nama_sampel'] ?? '-';
$kode_sampel = !empty($sampel['kode_sampel_manual']) ? $sampel['kode_sampel_manual'] : (!empty($sampel['no']) ? 'NO-' . $sampel['no'] : 'SMP-' . ($sampel['id'] ?? '-'));
$tgl_terima  = !empty($sampel['tanggal_sampling']) ? date('d-m-Y', strtotime($sampel['tanggal_sampling'])) : (!empty($sampel['created_at']) ? date('d-m-Y', strtotime($sampel['created_at'])) : '-');
$tgl_mulai   = !empty($sesi['waktu_mulai']) ? date('d-m-Y', strtotime($sesi['waktu_mulai'])) : date('d-m-Y');
$nama_penguji= $penguji['nama_lengkap'] ?? ($sesi['nama_penguji'] ?? '-');
$tgl_selesai = !empty($sesi['waktu_selesai']) ? date('d-m-Y', strtotime($sesi['waktu_selesai'])) : date('d-m-Y');
$tgl_verif   = !empty($sesi['waktu_verifikasi']) ? date('d-m-Y', strtotime($sesi['waktu_verifikasi'])) : '-';

// Hitung tanggal pengamatan koloni default (+7 hari dari mulai uji) jika belum diisi
$tgl_pengamatan_koloni = !empty($p['tanggal_pengamatan']) 
    ? date('d-m-Y', strtotime($p['tanggal_pengamatan'])) 
    : (!empty($sesi['waktu_mulai']) ? date('d-m-Y', strtotime($sesi['waktu_mulai'] . ' +7 days')) : date('d-m-Y', strtotime('+7 days')));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak LCP - UJI ANGKA KAPANG KHAMIR SEDIAAN FARMASI - <?php echo html_escape($nama_sampel); ?></title>
    <style>
        /* Setup Print Ukuran A4 */
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
            padding: 18px 0;
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

        .btn-print {
            background-color: #198754;
            color: #fff;
        }
        .btn-print:hover {
            background-color: #146c43;
        }

        .btn-back {
            background-color: #6c757d;
            color: #fff;
        }
        .btn-back:hover {
            background-color: #565e64;
        }

        /* Lembar Dokumen A4 */
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

        /* Box Utama Border Ganda (Outer Double Border) */
        .outer-border-box {
            border: 3px double #000;
            padding: 3px 5px 4px 5px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            flex-grow: 1;
        }

        /* Tabel Format Excel */
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
            line-height: 1.1;
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

        .checkbox-symbol {
            font-family: "Segoe UI Symbol", "DejaVu Sans", Arial, sans-serif;
            font-size: 8.5pt;
            display: inline-block;
            line-height: 1;
        }

        @media print {
            html, body {
                height: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                overflow: hidden !important;
                background: none !important;
            }
            .no-print {
                display: none !important;
            }
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
                <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z"/>
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

    <!-- HALAMAN 1 (A4 SINGLE SHEET) -->
    <div class="document-wrapper">
        <div class="outer-border-box">

            <!-- 1. KOP HEADER -->
            <table class="excel-table">
                <tr style="height: 18pt;">
                    <td class="text-center text-bold" style="font-size: 11pt; letter-spacing: 0.5px;">LAMPIRAN CATATAN PENGUJIAN</td>
                </tr>
                <tr style="height: 17pt;">
                    <td class="text-center text-bold" style="font-size: 10pt; letter-spacing: 0.3px;">UJI ANGKA KAPANG KHAMIR SEDIAAN FARMASI</td>
                </tr>
                <tr style="height: 18pt;">
                    <td class="text-center text-bold" style="font-size: 9pt;">Laboratorium Mikrobiologi Balai Besar POM di Bandar Lampung</td>
                </tr>
            </table>

            <div style="height: 4px;"></div>

            <!-- 2. METADATA SAMPEL -->
            <table class="excel-table" style="border: 1pt solid #000;">
                <colgroup>
                    <col style="width: 38%;">
                    <col style="width: 28%;">
                    <col style="width: 17%;">
                    <col style="width: 2%;">
                    <col style="width: 15%;">
                </colgroup>
                <tr style="height: 13.5pt;">
                    <td class="b-right text-bold" style="padding-left: 4px; font-size: 7.2pt;">Nama Sampel Uji :</td>
                    <td class="b-right text-bold" style="padding-left: 4px; font-size: 7.2pt;">No. Kode Sampel Uji :</td>
                    <td style="padding-left: 4px; font-size: 7pt; white-space: nowrap;">Tanggal Terima</td>
                    <td class="text-center" style="font-size: 7pt;">:</td>
                    <td style="padding-left: 2px; font-size: 7pt; white-space: nowrap;"><?php echo html_escape($tgl_terima); ?></td>
                </tr>
                <tr style="height: 13.5pt;">
                    <td rowspan="2" class="b-right text-bold" style="padding-left: 4px; vertical-align: top; font-size: 7.2pt;"><?php echo html_escape($nama_sampel); ?></td>
                    <td rowspan="2" class="b-right text-bold" style="padding-left: 4px; vertical-align: top; font-size: 7.2pt;"><?php echo html_escape($kode_sampel); ?></td>
                    <td style="padding-left: 4px; font-size: 7pt; white-space: nowrap;">Tanggal Mulai Uji</td>
                    <td class="text-center" style="font-size: 7pt;">:</td>
                    <td style="padding-left: 2px; font-size: 7pt; white-space: nowrap;"><?php echo html_escape($tgl_mulai); ?></td>
                </tr>
                <tr style="height: 13.5pt;">
                    <td style="padding-left: 4px; font-size: 7pt; white-space: nowrap;">Penguji</td>
                    <td class="text-center" style="font-size: 7pt;">:</td>
                    <td style="padding-left: 2px; font-size: 7pt; white-space: nowrap;"><?php echo html_escape($nama_penguji); ?></td>
                </tr>
            </table>

            <div style="height: 5px;"></div>

            <!-- 3. HASIL UJI KESESUAIAN METODE -->
            <table class="excel-table">
                <colgroup>
                    <col style="width: 17%;">
                    <col style="width: 4%;">
                    <col style="width: 39%;">
                    <col style="width: 4%;">
                    <col style="width: 36%;">
                </colgroup>
                <tr>
                    <td colspan="5" class="text-bold" style="font-size: 7.3pt; padding-bottom: 2px;">Hasil Uji Kesesuaian Metode :</td>
                </tr>
                <tr style="height: 12pt;">
                    <td rowspan="5" style="vertical-align: top; font-size: 7.2pt;">Pelarut yang sesuai :</td>
                    <td class="text-center"><span class="checkbox-symbol"><?php echo (($p['pelarut_sesuai'] ?? '') === 'TSB') ? '&#9745;' : '&#9744;'; ?></span></td>
                    <td style="font-size: 7.2pt;">TSB</td>
                    <td colspan="2" class="text-bold" style="font-size: 7.2pt; padding-left: 4px;">Mikroba Baku</td>
                </tr>
                <tr style="height: 12pt;">
                    <td class="text-center"><span class="checkbox-symbol"><?php echo (($p['pelarut_sesuai'] ?? '') === 'TSB + 30 g/L polysorbate 80') ? '&#9745;' : '&#9744;'; ?></span></td>
                    <td style="font-size: 7.2pt;">TSB + 30 g/L polysorbate 80</td>
                    <td class="text-center"><span class="checkbox-symbol"><?php echo !empty($p['mikroba_candida']) ? '&#9745;' : '&#9744;'; ?></span></td>
                    <td class="italic" style="font-size: 7.2pt;">Candida albicans ATCC 10231</td>
                </tr>
                <tr style="height: 12pt;">
                    <td class="text-center"><span class="checkbox-symbol"><?php echo (($p['pelarut_sesuai'] ?? '') === 'TSB + 30 g/L polysorbate 80 + 3 g/L Lesitin') ? '&#9745;' : '&#9744;'; ?></span></td>
                    <td style="font-size: 7.2pt;">TSB + 30 g/L polysorbate 80 + 3 g/L Lesitin</td>
                    <td class="text-center"><span class="checkbox-symbol"><?php echo !empty($p['mikroba_aspergillus']) ? '&#9745;' : '&#9744;'; ?></span></td>
                    <td class="italic" style="font-size: 7.2pt;">Aspergillus brasiliensis ATCC 16404</td>
                </tr>
                <tr style="height: 12pt;">
                    <td class="text-center"><span class="checkbox-symbol"><?php echo (($p['pelarut_sesuai'] ?? '') === 'SCDLP 20 Broth') ? '&#9745;' : '&#9744;'; ?></span></td>
                    <td style="font-size: 7.2pt;">SCDLP 20 Broth</td>
                    <td class="text-center"><span class="checkbox-symbol"><?php echo !empty($p['mikroba_lainnya_check']) ? '&#9745;' : '&#9744;'; ?></span></td>
                    <td style="font-size: 7.2pt;">Lainnya : <?php echo !empty($p['mikroba_lainnya_text']) ? html_escape($p['mikroba_lainnya_text']) : '.................'; ?></td>
                </tr>
                <tr style="height: 12pt;">
                    <td class="text-center"><span class="checkbox-symbol"><?php echo (($p['pelarut_sesuai'] ?? 'Lainnya') === 'Lainnya') ? '&#9745;' : '&#9744;'; ?></span></td>
                    <td style="font-size: 7.2pt;">Lainnya : <?php echo html_escape($p['pelarut_sesuai_lainnya'] ?? 'LDF pH 7,2'); ?></td>
                    <td colspan="2"></td>
                </tr>
            </table>

            <div style="height: 4px;"></div>

            <!-- 4. HOMOGENISASI AWAL -->
            <table class="excel-table">
                <colgroup>
                    <col style="width: 25%;">
                    <col style="width: 14%;">
                    <col style="width: 10%;">
                    <col style="width: 4%;">
                    <col style="width: 47%;">
                </colgroup>
                <tr>
                    <td colspan="5" class="text-bold" style="font-size: 7.3pt; padding-bottom: 2px;">Homogenisasi Awal</td>
                </tr>
                <tr style="height: 12pt;">
                    <td style="font-size: 7.2pt;">Penimbangan/Pemipetan Contoh</td>
                    <td style="font-size: 7.2pt;">: &nbsp;<?php echo html_escape($p['bobot_contoh'] ?? '10'); ?></td>
                    <td style="font-size: 7.2pt;"><?php echo html_escape($p['satuan_contoh'] ?? 'g'); ?> / mL *)</td>
                    <td colspan="2"></td>
                </tr>
                <tr style="height: 12pt;">
                    <td style="font-size: 7.2pt;">Pelarut</td>
                    <td style="font-size: 7.2pt;">: &nbsp;<?php echo html_escape($p['volume_pelarut'] ?? '90'); ?></td>
                    <td style="font-size: 7.2pt;">mL</td>
                    <td class="text-center"><span class="checkbox-symbol"><?php echo (($p['pelarut_pengencer'] ?? '') === 'TSB') ? '&#9745;' : '&#9744;'; ?></span></td>
                    <td style="font-size: 7.2pt;">TSB</td>
                </tr>
                <tr style="height: 12pt;">
                    <td colspan="3" style="font-size: 7.2pt;">Pengenceran seri &nbsp;: 1 dalam 9 mL</td>
                    <td class="text-center"><span class="checkbox-symbol"><?php echo (($p['pelarut_pengencer'] ?? '') === 'TSB + 30 g/L polysorbate 80') ? '&#9745;' : '&#9744;'; ?></span></td>
                    <td style="font-size: 7.2pt;">TSB + 30 g/L polysorbate 80</td>
                </tr>
                <tr style="height: 12pt;">
                    <td colspan="3"></td>
                    <td class="text-center"><span class="checkbox-symbol"><?php echo (($p['pelarut_pengencer'] ?? '') === 'TSB + 30 g/L polysorbate 80 + 3 g/L Lesitin') ? '&#9745;' : '&#9744;'; ?></span></td>
                    <td style="font-size: 7.2pt;">TSB + 30 g/L polysorbate 80 + 3 g/L Lesitin</td>
                </tr>
                <tr style="height: 12pt;">
                    <td colspan="3"></td>
                    <td class="text-center"><span class="checkbox-symbol"><?php echo (($p['pelarut_pengencer'] ?? '') === 'SCDLP 20 Broth') ? '&#9745;' : '&#9744;'; ?></span></td>
                    <td style="font-size: 7.2pt;">SCDLP 20 Broth</td>
                </tr>
                <tr style="height: 12pt;">
                    <td colspan="3"></td>
                    <td class="text-center"><span class="checkbox-symbol"><?php echo (($p['pelarut_pengencer'] ?? 'Lainnya') === 'Lainnya') ? '&#9745;' : '&#9744;'; ?></span></td>
                    <td style="font-size: 7.2pt;">Lainnya : <?php echo html_escape($p['pelarut_pengencer_lainnya'] ?? 'LDF pH 7,2'); ?></td>
                </tr>
            </table>

            <div style="height: 5px;"></div>

            <!-- 5. TABEL PENGAMATAN & KESESUAIAN METODE (GRID UTAMA) -->
            <table class="excel-table" style="border: 1pt solid #000; text-align: center;">
                <colgroup>
                    <!-- Col 1: Tanggal (B) -->
                    <col style="width: 10%;">
                    <!-- Col 2: Pengenceran (C) -->
                    <col style="width: 9%;">
                    <!-- Col 3: Media Check (D) -->
                    <col style="width: 3.5%;">
                    <!-- Col 4: Media Nama (E) -->
                    <col style="width: 8%;">
                    <!-- Col 5: Suhu Check (F) -->
                    <col style="width: 3.5%;">
                    <!-- Col 6: Suhu Nilai (G) -->
                    <col style="width: 8.5%;">
                    <!-- Col 7: Waktu Check (H) -->
                    <col style="width: 3.5%;">
                    <!-- Col 8: Waktu Nilai (I) -->
                    <col style="width: 7.5%;">
                    <!-- Col 9: Sampel A (J) -->
                    <col style="width: 6.5%;">
                    <!-- Col 10: Sampel B (K) -->
                    <col style="width: 6.5%;">
                    <!-- Col 11: Total Koloni (L) -->
                    <col style="width: 7.5%;">
                    <!-- Col 12: Spike C. albicans A (M) -->
                    <col style="width: 4.5%;">
                    <!-- Col 13: Spike C. albicans B (N) -->
                    <col style="width: 4.5%;">
                    <!-- Col 14: Spike C. albicans Rata (O) -->
                    <col style="width: 4.5%;">
                    <!-- Col 15: Spike A. niger A (P) -->
                    <col style="width: 4.5%;">
                    <!-- Col 16: Spike A. niger B (Q) -->
                    <col style="width: 4.5%;">
                    <!-- Col 17: Spike A. niger Rata (R) -->
                    <col style="width: 4.5%;">
                </colgroup>
                <thead>
                    <tr style="height: 12pt;">
                        <th rowspan="4" class="b-all text-bold">Tanggal</th>
                        <th rowspan="4" class="b-all text-bold">Pengenceran</th>
                        <th rowspan="4" colspan="2" class="b-all text-bold">Media</th>
                        <th colspan="4" class="b-all text-bold">Inkubasi</th>
                        <th colspan="3" class="b-all text-bold">Pengamatan</th>
                        <th colspan="6" class="b-all text-bold">Pengamatan KESESUAIAN METODE</th>
                    </tr>
                    <tr style="height: 11pt;">
                        <th rowspan="3" colspan="2" class="b-all text-bold">Suhu<br>(°C)</th>
                        <th rowspan="3" colspan="2" class="b-all text-bold">Waktu<br>(Hari)</th>
                        <th colspan="2" class="b-all text-bold">SAMPEL UJI</th>
                        <th rowspan="3" class="b-all text-bold">Total Koloni</th>
                        <th colspan="3" class="b-all" style="font-size: 6.5pt;">Jumlah Spike : <?php echo html_escape($p['spike_candida_jumlah'] ?? '............'); ?> koloni</th>
                        <th colspan="3" class="b-all" style="font-size: 6.5pt;">Jumlah Spike : <?php echo html_escape($p['spike_aspergillus_jumlah'] ?? '............'); ?> koloni</th>
                    </tr>
                    <tr style="height: 11pt;">
                        <th colspan="2" rowspan="2" class="b-all" style="font-size: 6.8pt;">1 ml (A) &nbsp;|&nbsp; 1 ml (B)</th>
                        <th colspan="2" class="b-all italic text-bold" style="font-size: 6.5pt;">C. albicans</th>
                        <th rowspan="2" class="b-all" style="font-size: 6pt; line-height: 1;">Rata-rata<br>jumlah koloni</th>
                        <th colspan="2" class="b-all italic text-bold" style="font-size: 6.5pt;">A. niger</th>
                        <th rowspan="2" class="b-all" style="font-size: 6pt; line-height: 1;">Rata-rata<br>jumlah koloni</th>
                    </tr>
                    <tr style="height: 11pt;">
                        <th class="b-all" style="font-size: 6pt;">1 ml (A)</th>
                        <th class="b-all" style="font-size: 6pt;">1 ml (B)</th>
                        <th class="b-all" style="font-size: 6pt;">1 ml (A)</th>
                        <th class="b-all" style="font-size: 6pt;">1 ml (B)</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Baris 1: Pengenceran 10^-1 -->
                    <tr style="height: 14pt;">
                        <td rowspan="7" class="b-all text-center text-bold" style="font-size: 7.2pt; vertical-align: middle;">
                            <?php echo html_escape($tgl_pengamatan_koloni); ?>
                        </td>
                        <td class="b-all text-bold"><?php echo html_escape($rows_koloni[0]['pengenceran']); ?></td>
                        <td colspan="2" class="b-all"></td>
                        <td colspan="2" class="b-all"></td>
                        <td colspan="2" class="b-all"></td>
                        <td class="b-all"><?php echo html_escape($rows_koloni[0]['cawan_a']); ?></td>
                        <td class="b-all"><?php echo html_escape($rows_koloni[0]['cawan_b']); ?></td>
                        <td class="b-all text-bold"><?php echo html_escape($rows_koloni[0]['total']); ?></td>
                        <td class="b-all" style="font-size: 6.5pt;"><?php echo html_escape($p['spike_candida_a'] ?? ''); ?></td>
                        <td class="b-all" style="font-size: 6.5pt;"><?php echo html_escape($p['spike_candida_b'] ?? ''); ?></td>
                        <td class="b-all" style="font-size: 6.5pt;"><?php echo html_escape($p['spike_candida_rata'] ?? ''); ?></td>
                        <td class="b-all" style="font-size: 6.5pt;"><?php echo html_escape($p['spike_aspergillus_a'] ?? ''); ?></td>
                        <td class="b-all" style="font-size: 6.5pt;"><?php echo html_escape($p['spike_aspergillus_b'] ?? ''); ?></td>
                        <td class="b-all" style="font-size: 6.5pt;"><?php echo html_escape($p['spike_aspergillus_rata'] ?? ''); ?></td>
                    </tr>

                    <!-- Baris 2: Pengenceran 10^-2 -->
                    <tr style="height: 14pt;">
                        <td class="b-all text-bold"><?php echo html_escape($rows_koloni[1]['pengenceran']); ?></td>
                        <td class="b-all text-center"><span class="checkbox-symbol"><?php echo (($p['media_agar'] ?? 'SDA') === 'SDA') ? '&#9745;' : '&#9744;'; ?></span></td>
                        <td class="b-all text-left" style="padding-left: 2px;">SDA</td>
                        <td class="b-all text-center"><span class="checkbox-symbol"><?php echo (($p['suhu_inkubasi'] ?? '22,5±2,5') === '22,5±2,5') ? '&#9745;' : '&#9744;'; ?></span></td>
                        <td class="b-all text-left" style="padding-left: 2px;">22,5±2,5</td>
                        <td class="b-all text-center"><span class="checkbox-symbol"><?php echo (($p['waktu_inkubasi'] ?? '') === '3 - 5') ? '&#9745;' : '&#9744;'; ?></span></td>
                        <td class="b-all text-left" style="padding-left: 2px;">3 - 5</td>
                        <td class="b-all"><?php echo html_escape($rows_koloni[1]['cawan_a']); ?></td>
                        <td class="b-all"><?php echo html_escape($rows_koloni[1]['cawan_b']); ?></td>
                        <td class="b-all text-bold"><?php echo html_escape($rows_koloni[1]['total']); ?></td>
                        <td colspan="3" rowspan="2" class="b-all text-left" style="vertical-align: top; padding: 2px 3px; font-size: 6.5pt;">
                            <strong>Perhitungan :</strong><br>
                            <?php echo html_escape($p['perhitungan_suitabilitas_candida'] ?? ''); ?>
                        </td>
                        <td colspan="3" rowspan="2" class="b-all text-left" style="vertical-align: top; padding: 2px 3px; font-size: 6.5pt;">
                            <strong>Perhitungan :</strong><br>
                            <?php echo html_escape($p['perhitungan_suitabilitas_aspergillus'] ?? ''); ?>
                        </td>
                    </tr>

                    <!-- Baris 3: Pengenceran 10^-3 -->
                    <tr style="height: 14pt;">
                        <td class="b-all text-bold"><?php echo html_escape($rows_koloni[2]['pengenceran']); ?></td>
                        <td class="b-all text-center"><span class="checkbox-symbol"><?php echo (($p['media_agar'] ?? '') === 'PDA') ? '&#9745;' : '&#9744;'; ?></span></td>
                        <td class="b-all text-left" style="padding-left: 2px;">PDA</td>
                        <td class="b-all text-center"><span class="checkbox-symbol"><?php echo (($p['suhu_inkubasi'] ?? '') === '25±2,5') ? '&#9745;' : '&#9744;'; ?></span></td>
                        <td class="b-all text-left" style="padding-left: 2px;">25±2,5</td>
                        <td class="b-all text-center"><span class="checkbox-symbol"><?php echo (($p['waktu_inkubasi'] ?? '5 - 7') === '5 - 7') ? '&#9745;' : '&#9744;'; ?></span></td>
                        <td class="b-all text-left" style="padding-left: 2px;">5 - 7</td>
                        <td class="b-all"><?php echo html_escape($rows_koloni[2]['cawan_a']); ?></td>
                        <td class="b-all"><?php echo html_escape($rows_koloni[2]['cawan_b']); ?></td>
                        <td class="b-all text-bold"><?php echo html_escape($rows_koloni[2]['total']); ?></td>
                    </tr>

                    <!-- Baris 4: Pengenceran 10^-4 -->
                    <tr style="height: 14pt;">
                        <td class="b-all text-bold"><?php echo html_escape($rows_koloni[3]['pengenceran']); ?></td>
                        <td class="b-all text-center"><span class="checkbox-symbol"><?php echo (($p['media_agar'] ?? '') === 'Lainnya') ? '&#9745;' : '&#9744;'; ?></span></td>
                        <td class="b-all text-left" style="padding-left: 2px; font-size: 6.5pt;">Lainnya:</td>
                        <td class="b-all text-center"><span class="checkbox-symbol"><?php echo (($p['suhu_inkubasi'] ?? '') === 'Lainnya') ? '&#9745;' : '&#9744;'; ?></span></td>
                        <td class="b-all text-left" style="padding-left: 2px; font-size: 6.5pt;">Lainnya:</td>
                        <td class="b-all text-center"><span class="checkbox-symbol"><?php echo (($p['waktu_inkubasi'] ?? '') === 'Lainnya') ? '&#9745;' : '&#9744;'; ?></span></td>
                        <td class="b-all text-left" style="padding-left: 2px; font-size: 6.5pt;">Lainnya:</td>
                        <td class="b-all"><?php echo html_escape($rows_koloni[3]['cawan_a']); ?></td>
                        <td class="b-all"><?php echo html_escape($rows_koloni[3]['cawan_b']); ?></td>
                        <td class="b-all text-bold"><?php echo html_escape($rows_koloni[3]['total']); ?></td>
                        <td colspan="6" rowspan="4" class="b-all text-left" style="vertical-align: top; padding: 2px 4px; font-size: 6.5pt; line-height: 1.15;">
                            <strong>Syarat Uji Kesesuaian Metode</strong><br>
                            Komoditi Obat dan Obat Kuasi : 50 - 200 %<br>
                            Komoditi Suplemen Kesehatan : &ge; 70%<br>
                            Komoditi Obat Bahan Alam : &gt; 20%<br>
                            Komoditi Kosmetik : &gt; 50%
                        </td>
                    </tr>

                    <!-- Baris 5: Pengenceran 10^-5 -->
                    <tr style="height: 13pt;">
                        <td class="b-all text-bold"><?php echo html_escape($rows_koloni[4]['pengenceran']); ?></td>
                        <td class="b-all"></td>
                        <td class="b-all text-left" style="padding-left: 2px; font-size: 6.5pt;"><?php echo html_escape($p['media_agar_lainnya'] ?? '.............'); ?></td>
                        <td class="b-all"></td>
                        <td class="b-all text-left" style="padding-left: 2px; font-size: 6.5pt;"><?php echo html_escape($p['suhu_inkubasi_lainnya'] ?? '...........'); ?></td>
                        <td class="b-all"></td>
                        <td class="b-all text-left" style="padding-left: 2px; font-size: 6.5pt;"><?php echo html_escape($p['waktu_inkubasi_lainnya'] ?? '.........'); ?></td>
                        <td class="b-all"><?php echo html_escape($rows_koloni[4]['cawan_a']); ?></td>
                        <td class="b-all"><?php echo html_escape($rows_koloni[4]['cawan_b']); ?></td>
                        <td class="b-all text-bold"><?php echo html_escape($rows_koloni[4]['total']); ?></td>
                    </tr>

                    <!-- Baris 6: Pengenceran 10^-6 -->
                    <tr style="height: 13pt;">
                        <td class="b-all text-bold"><?php echo html_escape($rows_koloni[5]['pengenceran']); ?></td>
                        <td colspan="2" class="b-all"></td>
                        <td colspan="2" class="b-all"></td>
                        <td colspan="2" class="b-all"></td>
                        <td class="b-all"><?php echo html_escape($rows_koloni[5]['cawan_a']); ?></td>
                        <td class="b-all"><?php echo html_escape($rows_koloni[5]['cawan_b']); ?></td>
                        <td class="b-all text-bold"><?php echo html_escape($rows_koloni[5]['total']); ?></td>
                    </tr>

                    <!-- Baris 7: Pengenceran .... -->
                    <tr style="height: 13pt;">
                        <td class="b-all text-bold"><?php echo html_escape($rows_koloni[6]['pengenceran']); ?></td>
                        <td colspan="2" class="b-all"></td>
                        <td colspan="2" class="b-all"></td>
                        <td colspan="2" class="b-all"></td>
                        <td class="b-all"><?php echo html_escape($rows_koloni[6]['cawan_a']); ?></td>
                        <td class="b-all"><?php echo html_escape($rows_koloni[6]['cawan_b']); ?></td>
                        <td class="b-all text-bold"><?php echo html_escape($rows_koloni[6]['total']); ?></td>
                    </tr>

                    <!-- Bagian Blangko & Perhitungan AKK -->
                    <tr style="height: 13pt;">
                        <td colspan="8" class="b-left b-right text-left text-bold" style="padding-left: 4px; font-size: 7.2pt;">
                            Jumlah koloni pada :
                        </td>
                        <td colspan="3" class="b-left b-right text-bold" style="font-size: 7.5pt; text-align: center;">
                            Perhitungan AKK
                        </td>
                        <td colspan="6" rowspan="4" class="b-all text-left" style="background-color: #fbfbfb;"></td>
                    </tr>
                    <tr style="height: 13pt;">
                        <td colspan="3" class="b-left text-left" style="padding-left: 6px; font-size: 7.2pt;">- Blangko Agar =</td>
                        <td colspan="5" class="b-right text-left" style="padding-left: 4px; font-size: 7.2pt;"><?php echo html_escape($p['blangko_agar'] ?? '0'); ?></td>
                        <td class="b-left text-center" style="font-size: 7.5pt;">=</td>
                        <td colspan="2" class="b-right text-center" style="border-bottom: 1pt solid #000; font-size: 7.5pt; padding: 1px 2px;">
                            <?php echo html_escape($p['perhitungan_akk_rumus_atas'] ?? '(2+3) x 10^1'); ?>
                        </td>
                    </tr>
                    <tr style="height: 13pt;">
                        <td colspan="3" class="b-left text-left" style="padding-left: 6px; font-size: 7.2pt;">- Blangko Agar + Pengencer =</td>
                        <td colspan="5" class="b-right text-left" style="padding-left: 4px; font-size: 7.2pt;"><?php echo html_escape($p['blangko_pengencer'] ?? '0'); ?></td>
                        <td class="b-left text-center" style="font-size: 7.5pt;"></td>
                        <td colspan="2" class="b-right text-center" style="font-size: 7.5pt;">
                            <?php echo html_escape($p['perhitungan_akk_rumus_bawah'] ?? '2'); ?>
                        </td>
                    </tr>
                    <tr style="height: 14pt;">
                        <td colspan="8" class="b-left b-right b-bottom"></td>
                        <td class="b-left b-bottom text-center" style="font-size: 7.5pt;">=</td>
                        <td colspan="2" class="b-right b-bottom text-center text-bold" style="font-size: 8pt;">
                            <?php echo html_escape($p['hasil_akk_angka'] ?? '2,5 x 10^1'); ?> &nbsp;<span style="font-size: 7.2pt; font-weight: normal;"><?php echo html_escape($p['hasil_akk_satuan'] ?? 'koloni/g'); ?></span>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div style="height: 4px;"></div>

            <!-- 6. KETERANGAN -->
            <table class="excel-table">
                <colgroup>
                    <col style="width: 12%;">
                    <col style="width: 4%;">
                    <col style="width: 84%;">
                </colgroup>
                <tr style="height: 10.5pt;">
                    <td class="text-bold" style="font-size: 6.8pt;">Keterangan:</td>
                    <td class="text-center"><span class="checkbox-symbol">&#9744;</span></td>
                    <td style="font-size: 6.8pt;">: tandai salah satu pilihan atau isi kolom &ldquo;lainnya&rdquo; jika diperlukan</td>
                </tr>
                <tr style="height: 10.5pt;">
                    <td></td>
                    <td class="text-center" style="font-size: 7pt; font-weight: bold;">*)</td>
                    <td style="font-size: 6.8pt;">: coret yang tidak perlu</td>
                </tr>
                <tr style="height: 10.5pt;">
                    <td></td>
                    <td class="text-center" style="font-size: 7pt; font-weight: bold;">N/A</td>
                    <td style="font-size: 6.8pt;">: tidak dilakukan</td>
                </tr>
            </table>

            <div style="height: 4px;"></div>

            <!-- 7. PUSTAKA / METODE ACUAN & SYARAT -->
            <div style="border-top: 1pt solid #000; padding-top: 4px; padding-bottom: 3px;">
                <table class="excel-table">
                    <colgroup>
                        <col style="width: 18%;">
                        <col style="width: 2%;">
                        <col style="width: 32%;">
                        <col style="width: 48%;">
                    </colgroup>
                    <tr style="height: 14pt;">
                        <td class="text-bold" style="font-size: 7.5pt;">Pustaka / Metode Acuan</td>
                        <td class="text-center" style="font-size: 7.5pt;">:</td>
                        <td style="font-size: 7.5pt;"><?php echo html_escape($p['pustaka_acuan'] ?? '68/MI/16'); ?></td>
                        <td style="font-size: 7.5pt;">
                            <strong>Syarat :</strong> &nbsp;<?php echo html_escape($p['syarat_keberterimaan'] ?? ''); ?>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- 8. KESIMPULAN -->
            <div style="border-top: 1pt solid #000; border-bottom: 1pt solid #000; padding: 5px 2px;">
                <table class="excel-table">
                    <colgroup>
                        <col style="width: 12%;">
                        <col style="width: 88%;">
                    </colgroup>
                    <tr>
                        <td class="text-bold" style="font-size: 7.5pt;">Kesimpulan &nbsp;:</td>
                        <td style="font-size: 7.5pt;">
                            <?php 
                            $kesimpulan_val = $p['kesimpulan_status'] ?? ($hasil['kesimpulan'] ?? 'Memenuhi Syarat (MS)');
                            $is_ms   = (stripos($kesimpulan_val, 'Memenuhi Syarat (MS)') !== false || $kesimpulan_val === 'MS');
                            $is_tms  = (stripos($kesimpulan_val, 'Tidak Memenuhi Syarat (TMS)') !== false || $kesimpulan_val === 'TMS');
                            $is_hpst = (stripos($kesimpulan_val, 'Hasil Pengujian Seperti Tersebut') !== false || $kesimpulan_val === 'HPST');
                            ?>
                            <span style="<?php echo $is_ms ? 'font-weight: bold; text-decoration: underline;' : 'color: #777;'; ?>">Memenuhi Syarat (MS)</span> / 
                            <span style="<?php echo $is_tms ? 'font-weight: bold; text-decoration: underline; color: #dc3545;' : 'color: #777;'; ?>">Tidak Memenuhi Syarat (TMS)</span> / 
                            <span style="<?php echo $is_hpst ? 'font-weight: bold; text-decoration: underline;' : 'color: #777;'; ?>">Hasil Pengujian Seperti Tersebut (HPST)</span> *)
                        </td>
                    </tr>
                </table>
            </div>

            <!-- 9. TANDA TANGAN (PENGUJI & PEMERIKSA / PENYELIA) -->
            <table class="excel-table" style="border-bottom: 2px double #000;">
                <colgroup>
                    <col style="width: 50%;">
                    <col style="width: 50%;">
                </colgroup>
                <tr style="height: 14pt;">
                    <td class="b-right text-bold" style="font-size: 7.5pt; padding-left: 4px;">Tanda Tangan Penguji :</td>
                    <td class="text-bold" style="font-size: 7.5pt; padding-left: 10px;">Diperiksa Oleh :</td>
                </tr>
                <tr style="height: 52pt;">
                    <!-- Area TTD Penguji -->
                    <td class="b-right text-center" style="vertical-align: middle; padding: 2px;">
                        <?php if (!empty($ttd_penguji_img)): ?>
                            <div style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
                                <img src="<?php echo $ttd_penguji_img; ?>" style="max-height: 42px; max-width: 140px; object-fit: contain;" alt="TTD Penguji">
                                <div style="font-size: 7pt; font-weight: bold; margin-top: 1px; color: #000;"><?php echo html_escape($nama_penguji); ?></div>
                            </div>
                        <?php else: ?>
                            <div style="font-size: 7.2pt; font-weight: bold; color: #198754;">[ TERCATAT DIGITAL ]</div>
                            <div style="font-size: 7pt; font-weight: bold; margin-top: 2px; color: #000;"><?php echo html_escape($nama_penguji); ?></div>
                        <?php endif; ?>
                    </td>

                    <!-- Area TTD Pemeriksa / Penyelia -->
                    <td class="text-center" style="vertical-align: middle; padding: 2px;">
                        <?php if ($status_sesi === 'Approved / Final'): ?>
                            <div style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
                                <?php if (!empty($ttd_penyelia_img)): ?>
                                    <img src="<?php echo $ttd_penyelia_img; ?>" style="max-height: 42px; max-width: 140px; object-fit: contain;" alt="TTD Penyelia">
                                <?php else: ?>
                                    <div style="font-size: 7.2pt; font-weight: bold; color: #198754;">[ VERIFIED DIGITAL ]</div>
                                <?php endif; ?>
                                <div style="font-size: 7pt; font-weight: bold; margin-top: 1px; color: #000;"><?php echo html_escape($verifier['nama_lengkap'] ?? ($sesi['nama_verifier'] ?? 'Penyelia')); ?></div>
                            </div>
                        <?php elseif ($status_sesi === 'Menunggu Approval'): ?>
                            <div style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
                                <?php if (!empty($ttd_penyelia_img)): ?>
                                    <img src="<?php echo $ttd_penyelia_img; ?>" style="max-height: 42px; max-width: 140px; object-fit: contain;" alt="TTD Penyelia">
                                <?php else: ?>
                                    <div style="font-size: 7.2pt; font-weight: bold; color: #198754;">[ VERIFIED DIGITAL ]</div>
                                <?php endif; ?>
                                <div style="font-size: 7pt; font-weight: bold; margin-top: 1px; color: #000;"><?php echo html_escape($verifier['nama_lengkap'] ?? ($sesi['nama_verifier'] ?? 'Penyelia')); ?></div>
                            </div>
                        <?php else: ?>
                            <div style="font-size: 7pt; color: #888; font-style: italic;">
                                (Menunggu Verifikasi Penyelia)
                            </div>
                            <div style="font-size: 7pt; color: #444; margin-top: 2px;">(TTD, Nama)</div>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr style="height: 14pt;">
                    <td class="b-right" style="font-size: 7.2pt; padding-left: 4px;">
                        Tanggal : &nbsp;<span style="font-size: 7.2pt;"><?php echo html_escape($tgl_selesai); ?></span>
                    </td>
                    <td style="font-size: 7.2pt; padding-left: 10px;">
                        Tanggal : &nbsp;<span style="font-size: 7.2pt;"><?php echo ($status_sesi === 'Approved / Final' || $status_sesi === 'Menunggu Approval') ? html_escape($tgl_verif) : '-'; ?></span>
                    </td>
                </tr>
            </table>

        </div><!-- /.outer-border-box -->

        <!-- 10. KODE DOKUMEN & FOOTER HALAMAN (DI LUAR BORDER GANDA) -->
        <table class="excel-table" style="margin-top: 3px;">
            <colgroup>
                <col style="width: 70%;">
                <col style="width: 30%;">
            </colgroup>
            <tr style="height: 12pt;">
                <td style="font-size: 7pt; font-weight: bold; padding-left: 2px;">POM-06.02/CFM.01/SOP.01/F.01B.04</td>
                <td class="text-right" style="font-size: 7pt; padding-right: 2px;">Lembar 1 dari 1 halaman</td>
            </tr>
        </table>

    </div><!-- /.document-wrapper -->

</body>
</html>
