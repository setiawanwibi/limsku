<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * View Cetak / Simpan PDF Lembar Catatan Pengujian (LCP)
 * Cemaran Mikroba Kualitatif Sediaan Farmasi
 * Replikasi presisi 100% dari file Excel Template Resmi.
 */

$status_sesi = $sesi['status'] ?? 'Draft';
$p = !empty($hasil['data_hasil']) ? json_decode($hasil['data_hasil'], true) : array();
if (isset($p['parameter_values']) && is_array($p['parameter_values'])) {
    $p = array_merge($p, $p['parameter_values']);
}
$tahapan = isset($p['tabel_tahapan']) && is_array($p['tabel_tahapan']) ? $p['tabel_tahapan'] : array();
$t0 = $tahapan[0] ?? array();
$t1 = $tahapan[1] ?? array();
$t2 = $tahapan[2] ?? array();
$t3 = $tahapan[3] ?? array();
$t4 = $tahapan[4] ?? array();

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
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak LCP - CEMARAN MIKROBA KUALITATIF SEDIAAN FARMASI - <?php echo html_escape($sampel['nama_sampel'] ?? 'Sampel'); ?></title>
    <style>
        /* Reset and Print Setup */
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
            padding: 20px 0;
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

        /* Document Paper */
        .document-wrapper {
            width: 210mm;
            max-height: 295mm;
            margin: 0 auto 30px auto;
            padding: 3mm 7mm 2mm 7mm;
            background-color: #fff;
            box-shadow: 0 0 15px rgba(0,0,0,0.3);
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        /* Excel Exact Grid Table */
        table.excel-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-family: "Times New Roman", Times, serif;
            font-size: 7pt;
            color: #000;
        }

        table.excel-table td {
            padding: 0.2px 1.5px;
            overflow: hidden;
            word-wrap: break-word;
            line-height: 1.05;
            border: none;
        }

        /* Print Media Overrides */
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
                padding: 2mm 5mm 2mm 5mm !important;
                box-shadow: none !important;
                width: 100% !important;
                height: 100% !important;
                max-height: 297mm !important;
                page-break-after: avoid !important;
                page-break-inside: avoid !important;
                page-break-before: avoid !important;
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

    <!-- HALAMAN 1 -->
    <div class="document-wrapper">
        <table class="excel-table">
        <colgroup>
            <col style="width: 2.009%;">
            <col style="width: 7.138%;">
            <col style="width: 5.077%;">
            <col style="width: 7.616%;">
            <col style="width: 2.909%;">
            <col style="width: 4.548%;">
            <col style="width: 7.827%;">
            <col style="width: 2.484%;">
            <col style="width: 5.236%;">
            <col style="width: 2.009%;">
            <col style="width: 5.236%;">
            <col style="width: 2.802%;">
            <col style="width: 9.043%;">
            <col style="width: 3.914%;">
            <col style="width: 9.043%;">
            <col style="width: 3.066%;">
            <col style="width: 7.245%;">
            <col style="width: 2.484%;">
            <col style="width: 7.563%;">
            <col style="width: 2.75%;">
        </colgroup>
            <tbody>
                <tr style="height: 3.5pt;">
                    <td colspan="20" style="border-top: 1.5pt solid #000; border-left: 1.5pt solid #000; border-right: 1.5pt solid #000;"></td>
                </tr>
                <tr style="height: 14.5pt;">
                    <td rowspan="4" colspan="3" style="border-left: 1.5pt solid #000; text-align: center; vertical-align: middle; padding: 2px 2px;">
                        <img src="<?php echo base_url('assets/img/bpom_logo_excel.png'); ?>" style="height: 52px; width: auto; max-width: 95%; display: block; margin: 0 auto;" alt="Logo BPOM">
                    </td>
                    <td colspan="16" style="font-size: 10.5pt; font-weight: bold; text-align: center; vertical-align: middle;">LAMPIRAN CATATAN PENGUJIAN</td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
                <tr style="height: 12.5pt;">
                    <td colspan="16" style="font-size: 9.0pt; font-weight: bold; text-align: center; vertical-align: middle;">CEMARAN MIKROBA KUALITATIF SEDIAAN FARMASI</td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
                <tr style="height: 9.0pt;">
                    <td colspan="3"></td>
                    <td style="font-size: 7.0pt; text-align: center; vertical-align: middle;"><?php echo (!empty($p['target_staphylococcus']) ? '&#9745;' : '&#9744;'); ?></td>
                    <td colspan="4" style="font-size: 7.0pt; font-style: italic; vertical-align: middle; white-space: nowrap;">Staphylococcus aureus</td>
                    <td style="font-size: 7.0pt; text-align: center; vertical-align: middle;"><?php echo (!empty($p['target_candida']) ? '&#9745;' : '&#9744;'); ?></td>
                    <td colspan="3" style="font-size: 7.0pt; font-style: italic; vertical-align: middle; white-space: nowrap;">Candida albicans</td>
                    <td style="font-size: 7.0pt; text-align: center; vertical-align: middle;"><?php echo (!empty($p['target_salmonella']) ? '&#9745;' : '&#9744;'); ?></td>
                    <td colspan="4" style="font-size: 7.0pt; font-style: italic; vertical-align: middle; white-space: nowrap;">Salmonella sp</td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
                <tr style="height: 9.0pt;">
                    <td colspan="3"></td>
                    <td style="font-size: 7.0pt; text-align: center; vertical-align: middle;"><?php echo (!empty($p['target_pseudomonas']) ? '&#9745;' : '&#9744;'); ?></td>
                    <td colspan="4" style="font-size: 7.0pt; font-style: italic; vertical-align: middle; white-space: nowrap;">Pseudomonas aeruginosa</td>
                    <td style="font-size: 7.0pt; text-align: center; vertical-align: middle;"><?php echo (!empty($p['target_shigella']) ? '&#9745;' : '&#9744;'); ?></td>
                    <td colspan="3" style="font-size: 7.0pt; font-style: italic; vertical-align: middle; white-space: nowrap;">Shigella sp</td>
                    <td style="font-size: 7.0pt; text-align: center; vertical-align: middle;"><?php echo (!empty($p['target_ecoli']) ? '&#9745;' : '&#9744;'); ?></td>
                    <td colspan="4" style="font-size: 7.0pt; font-style: italic; vertical-align: middle; white-space: nowrap;">E.coli</td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
                <tr style="height: 9.0pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td colspan="13"></td>
                    <td style="font-size: 7.0pt; text-align: center; vertical-align: middle;"><?php echo (!empty($p['target_lainnya_check']) ? '&#9745;' : '&#9744;'); ?></td>
                    <td colspan="4" style="font-size: 7.0pt; font-style: italic; vertical-align: middle; white-space: nowrap;">Lainnya: <?php echo !empty($p['target_lainnya_text']) ? '<u>' . html_escape($p['target_lainnya_text']) . '</u>' : '..............'; ?></td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
                <tr style="height: 10.0pt;">
                    <td style="border-left: 1.5pt solid #000; font-size: 9.5pt; vertical-align: middle"></td>
                    <td colspan="18" style="text-align: center; vertical-align: middle; font-weight: bold; font-size: 8.5pt;">Laboratorium Mikrobiologi Balai Besar/Balai/Loka*) POM di .........................</td>
                    <td style="border-right: 1.5pt solid #000; font-size: 9.5pt; vertical-align: middle"></td>
                </tr>
                <tr style="height: 3.5pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td colspan="18"></td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
                <tr style="height: 9.5pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td colspan="7" style="border-top: 1pt solid #000; border-left: 1pt solid #000; font-size: 7.0pt; font-weight: bold; vertical-align: middle; padding-left: 5px;">Nama Sampel Uji :</td>
                    <td colspan="6" style="border-top: 1pt solid #000; border-left: 1pt solid #000; font-size: 7.0pt; font-weight: bold; vertical-align: middle; padding-left: 5px;">Kode Sampel Uji :</td>
                    <td colspan="2" style="border-top: 1pt solid #000; border-left: 1pt solid #000; font-size: 6.8pt; font-weight: bold; vertical-align: middle; padding-left: 5px; white-space: nowrap;">Tanggal Diterima</td>
                    <td style="border-top: 1pt solid #000; font-size: 6.8pt; text-align: center; vertical-align: middle;">:</td>
                    <td colspan="2" style="border-top: 1pt solid #000; border-right: 1pt solid #000; font-size: 6.8pt; vertical-align: middle; padding-left: 3px; white-space: nowrap;"><?php echo !empty($sampel['tanggal_sampling']) ? date('d-m-Y', strtotime($sampel['tanggal_sampling'])) : (!empty($sampel['created_at']) ? date('d-m-Y', strtotime($sampel['created_at'])) : '-'); ?></td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
                <tr style="height: 9.5pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td rowspan="2" colspan="7" style="border-bottom: 1pt solid #000; border-left: 1pt solid #000; font-size: 7.0pt; font-weight: bold; vertical-align: middle; padding-left: 5px;"><?php echo html_escape($sampel['nama_sampel'] ?? '-'); ?></td>
                    <td rowspan="2" colspan="6" style="border-bottom: 1pt solid #000; border-left: 1pt solid #000; font-size: 7.0pt; font-weight: bold; vertical-align: middle; padding-left: 5px;"><?php echo html_escape(!empty($sampel['kode_sampel_manual']) ? $sampel['kode_sampel_manual'] : (!empty($sampel['no']) ? 'NO-' . $sampel['no'] : 'SMP-' . $sampel['id'])); ?></td>
                    <td colspan="2" style="border-left: 1pt solid #000; font-size: 6.8pt; font-weight: bold; vertical-align: middle; padding-left: 5px; white-space: nowrap;">Penguji</td>
                    <td style="font-size: 6.8pt; text-align: center; vertical-align: middle;">:</td>
                    <td colspan="2" style="border-right: 1pt solid #000; font-size: 6.8pt; font-weight: bold; vertical-align: middle; padding-left: 3px; white-space: nowrap;"><?php echo html_escape($penguji['nama_lengkap'] ?? ($sesi['nama_penguji'] ?? '-')); ?></td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
                <tr style="height: 9.5pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td colspan="2" style="border-bottom: 1pt solid #000; border-left: 1pt solid #000; font-size: 6.8pt; font-weight: bold; vertical-align: middle; padding-left: 5px; white-space: nowrap;">Tanggal Mulai Uji</td>
                    <td style="border-bottom: 1pt solid #000; font-size: 6.8pt; text-align: center; vertical-align: middle;">:</td>
                    <td colspan="2" style="border-bottom: 1pt solid #000; border-right: 1pt solid #000; font-size: 6.8pt; vertical-align: middle; padding-left: 3px; white-space: nowrap;"><?php echo !empty($sesi['waktu_mulai']) ? date('d-m-Y', strtotime($sesi['waktu_mulai'])) : date('d-m-Y'); ?></td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
                <tr style="height: 3.5pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td colspan="18"></td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
                <!-- Hasil Uji Kesesuaian Metode -->
                <tr style="height: 10.0pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td colspan="18" style="font-size: 8.0pt; font-weight: bold; vertical-align: middle;">Hasil Uji Kesesuaian Metode :</td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
                <tr style="height: 9.8pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td colspan="3" style="font-size: 7.5pt; vertical-align: middle; white-space: nowrap;">Pelarut yang sesuai :</td>
                    <td style="font-size: 8.0pt; text-align: center; vertical-align: middle;"><?php echo (isset($p['pelarut_kesesuaian']) && $p['pelarut_kesesuaian'] === 'MLB') ? '&#9745;' : '&#9744;'; ?></td>
                    <td colspan="5" style="font-size: 7.5pt; vertical-align: middle; white-space: nowrap;">MLB</td>
                    <td colspan="2"></td>
                    <td colspan="7" style="font-size: 8.0pt; font-weight: bold; vertical-align: middle; white-space: nowrap;">Mikroba Baku</td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
                <tr style="height: 9.8pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td colspan="3"></td>
                    <td style="font-size: 8.0pt; text-align: center; vertical-align: middle;"><?php echo (isset($p['pelarut_kesesuaian']) && $p['pelarut_kesesuaian'] === 'TSB') ? '&#9745;' : '&#9744;'; ?></td>
                    <td colspan="5" style="font-size: 7.5pt; vertical-align: middle; white-space: nowrap;">TSB</td>
                    <td colspan="2"></td>
                    <td style="font-size: 8.0pt; text-align: center; vertical-align: middle;"><?php echo (!empty($p['baku_staphylococcus']) ? '&#9745;' : '&#9744;'); ?></td>
                    <td colspan="6" style="font-size: 7.5pt; font-style: italic; vertical-align: middle; white-space: nowrap;">Staphylococcus aureus <span style="font-style: normal;">ATCC / WDCM <?php echo !empty($p['baku_staphylococcus_no']) ? '<u>' . html_escape($p['baku_staphylococcus_no']) . '</u>' : '…................'; ?></span></td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
                <tr style="height: 9.8pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td colspan="3"></td>
                    <td style="font-size: 8.0pt; text-align: center; vertical-align: middle;"><?php echo (isset($p['pelarut_kesesuaian']) && $p['pelarut_kesesuaian'] === 'TSB + 30 g/L polysorbate 80') ? '&#9745;' : '&#9744;'; ?></td>
                    <td colspan="5" style="font-size: 7.5pt; vertical-align: middle; white-space: nowrap;">TSB + 30 g/L polysorbate 80</td>
                    <td colspan="2"></td>
                    <td style="font-size: 8.0pt; text-align: center; vertical-align: middle;"><?php echo (!empty($p['baku_pseudomonas']) ? '&#9745;' : '&#9744;'); ?></td>
                    <td colspan="6" style="font-size: 7.5pt; font-style: italic; vertical-align: middle; white-space: nowrap;">Pseudomonas aeruginosa <span style="font-style: normal;">ATCC / WDCM <?php echo !empty($p['baku_pseudomonas_no']) ? '<u>' . html_escape($p['baku_pseudomonas_no']) . '</u>' : '…...............'; ?></span></td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
                <tr style="height: 9.8pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td colspan="3"></td>
                    <td style="font-size: 8.0pt; text-align: center; vertical-align: middle;"><?php echo (isset($p['pelarut_kesesuaian']) && $p['pelarut_kesesuaian'] === 'TSB + 30 g/L polysorbate 80 + 3 g/L Lesitin') ? '&#9745;' : '&#9744;'; ?></td>
                    <td colspan="5" style="font-size: 7.5pt; vertical-align: middle; white-space: nowrap;">TSB + 30 g/L polysorbate 80 + 3 g/L Lesitin</td>
                    <td colspan="2"></td>
                    <td style="font-size: 8.0pt; text-align: center; vertical-align: middle;"><?php echo (!empty($p['baku_candida']) ? '&#9745;' : '&#9744;'); ?></td>
                    <td colspan="6" style="font-size: 7.5pt; font-style: italic; vertical-align: middle; white-space: nowrap;">Candida albicans <span style="font-style: normal;">ATCC / WDCM <?php echo !empty($p['baku_candida_no']) ? '<u>' . html_escape($p['baku_candida_no']) . '</u>' : '…...............'; ?></span></td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
                <tr style="height: 9.8pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td colspan="3"></td>
                    <td style="font-size: 8.0pt; text-align: center; vertical-align: middle;"><?php echo (isset($p['pelarut_kesesuaian']) && $p['pelarut_kesesuaian'] === 'Lainnya') ? '&#9745;' : '&#9744;'; ?></td>
                    <td colspan="5" style="font-size: 7.5pt; vertical-align: middle; white-space: nowrap;">Lainnya: <?php echo !empty($p['pelarut_kesesuaian_lainnya']) ? '<u>' . html_escape($p['pelarut_kesesuaian_lainnya']) . '</u>' : '…..........................'; ?></td>
                    <td colspan="2"></td>
                    <td style="font-size: 8.0pt; text-align: center; vertical-align: middle;"><?php echo (!empty($p['baku_lainnya']) ? '&#9745;' : '&#9744;'); ?></td>
                    <td colspan="6" style="font-size: 7.5pt; vertical-align: middle; white-space: nowrap;">Lainnya: <?php echo !empty($p['baku_lainnya']) ? '<u>' . html_escape($p['baku_lainnya']) . '</u>' : '…..........................'; ?></td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
                <tr style="height: 4.5pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td colspan="18"></td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>

                <!-- Homogenisasi Awal -->
                <tr style="height: 10.0pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td colspan="18" style="font-size: 8.0pt; font-weight: bold; vertical-align: middle;">Homogenisasi Awal</td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
                <tr style="height: 9.8pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td colspan="3" style="font-size: 7.5pt; vertical-align: middle; white-space: nowrap;">Penimbangan/Pemipetan Sampel</td>
                    <td style="font-size: 7.5pt; text-align: center; vertical-align: middle;">:</td>
                    <td colspan="2" style="font-size: 7.5pt; text-align: center; vertical-align: middle;"><strong><?php echo html_escape($p['bobot_sampel'] ?? '10.0'); ?></strong></td>
                    <td colspan="12" style="font-size: 7.5pt; vertical-align: middle; white-space: nowrap;">g atau mL *)</td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
                <tr style="height: 9.8pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td colspan="3" style="font-size: 7.5pt; vertical-align: middle; white-space: nowrap;">Pelarut</td>
                    <td style="font-size: 7.5pt; text-align: center; vertical-align: middle;">:</td>
                    <td colspan="2" style="font-size: 7.5pt; text-align: center; vertical-align: middle;"><strong><?php echo html_escape($p['volume_pelarut'] ?? '90.0'); ?></strong></td>
                    <td style="font-size: 7.5pt; vertical-align: middle;">mL</td>
                    <td style="font-size: 8.0pt; text-align: center; vertical-align: middle;"><?php echo (isset($p['jenis_pelarut_homogenisasi']) && $p['jenis_pelarut_homogenisasi'] === 'MLB') ? '&#9745;' : '&#9744;'; ?></td>
                    <td colspan="10" style="font-size: 7.5pt; vertical-align: middle; white-space: nowrap;">MLB</td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
                <tr style="height: 9.8pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td colspan="7"></td>
                    <td style="font-size: 8.0pt; text-align: center; vertical-align: middle;"><?php echo (isset($p['jenis_pelarut_homogenisasi']) && $p['jenis_pelarut_homogenisasi'] === 'TSB') ? '&#9745;' : '&#9744;'; ?></td>
                    <td colspan="10" style="font-size: 7.5pt; vertical-align: middle; white-space: nowrap;">TSB</td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
                <tr style="height: 9.8pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td colspan="7"></td>
                    <td style="font-size: 8.0pt; text-align: center; vertical-align: middle;"><?php echo (isset($p['jenis_pelarut_homogenisasi']) && $p['jenis_pelarut_homogenisasi'] === 'TSB + 30 g/L polysorbate 80') ? '&#9745;' : '&#9744;'; ?></td>
                    <td colspan="10" style="font-size: 7.5pt; vertical-align: middle; white-space: nowrap;">TSB + 30 g/L polysorbate 80</td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
                <tr style="height: 9.8pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td colspan="7"></td>
                    <td style="font-size: 8.0pt; text-align: center; vertical-align: middle;"><?php echo (isset($p['jenis_pelarut_homogenisasi']) && $p['jenis_pelarut_homogenisasi'] === 'TSB + 30 g/L polysorbate 80 + 3 g/L Lesitin') ? '&#9745;' : '&#9744;'; ?></td>
                    <td colspan="10" style="font-size: 7.5pt; vertical-align: middle; white-space: nowrap;">TSB + 30 g/L polysorbate 80 + 3 g/L Lesitin</td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
                <tr style="height: 9.8pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td colspan="7"></td>
                    <td style="font-size: 8.0pt; text-align: center; vertical-align: middle;"><?php echo (isset($p['jenis_pelarut_homogenisasi']) && $p['jenis_pelarut_homogenisasi'] === 'Lainnya') ? '&#9745;' : '&#9744;'; ?></td>
                    <td colspan="10" style="font-size: 7.5pt; vertical-align: middle; white-space: nowrap;">Lainnya : <?php echo !empty($p['pelarut_homogenisasi_lainnya']) ? '<u>' . html_escape($p['pelarut_homogenisasi_lainnya']) . '</u>' : '….......................'; ?></td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
                <tr style="height: 4.5pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td colspan="18"></td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
                <!-- Header Tabel Tahapan Pengujian -->
                <tr style="height: 11.5pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td rowspan="5" style="border: 0.75pt solid #000; font-size: 7.5pt; text-align: center; vertical-align: middle; white-space: nowrap;">Tanggal</td>
                    <td rowspan="5" colspan="2" style="border: 0.75pt solid #000; font-size: 7.5pt; text-align: center; vertical-align: middle; white-space: nowrap;">Tahapan Uji</td>
                    <td rowspan="5" colspan="3" style="border: 0.75pt solid #000; font-size: 7.5pt; text-align: center; vertical-align: middle; white-space: nowrap;">Media</td>
                    <td colspan="4" style="border: 0.75pt solid #000; font-size: 7.5pt; text-align: center; vertical-align: middle; white-space: nowrap;">Inkubasi</td>
                    <td colspan="2" style="border: 0.75pt solid #000; font-size: 7.5pt; font-weight: bold; text-align: center; vertical-align: middle; white-space: nowrap;">Kesesuaian Metode</td>
                    <td colspan="2" style="border: 0.75pt solid #000; font-size: 7.5pt; font-weight: bold; text-align: center; vertical-align: middle; white-space: nowrap;">Kontrol Positif</td>
                    <td colspan="2" style="border: 0.75pt solid #000; font-size: 7.5pt; font-weight: bold; text-align: center; vertical-align: middle; white-space: nowrap;">Kontrol Media</td>
                    <td rowspan="2" colspan="2" style="border: 0.75pt solid #000; font-size: 7.5pt; font-weight: bold; text-align: center; vertical-align: middle; white-space: nowrap;">Sampel Uji</td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
                <tr style="height: 16.5pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td rowspan="4" colspan="2" style="border: 0.75pt solid #000; font-size: 7.2pt; text-align: center; vertical-align: middle; white-space: nowrap;">Suhu (&deg;C)</td>
                    <td rowspan="4" colspan="2" style="border: 0.75pt solid #000; font-size: 7.2pt; text-align: center; vertical-align: middle; line-height: 1.15;">Waktu<br>(Jam)</td>
                    <td colspan="2" style="border: 0.75pt solid #000; font-size: 7.0pt; text-align: center; vertical-align: middle; white-space: nowrap;">Sampel + <em>Spike</em></td>
                    <td colspan="2" style="border: 0.75pt solid #000; font-size: 7.0pt; text-align: center; vertical-align: middle; line-height: 1.15;">Media Tanpa sampel<br>+ <em>Spike</em></td>
                    <td colspan="2" style="border: 0.75pt solid #000; font-size: 7.0pt; text-align: center; vertical-align: middle; line-height: 1.15;">Tanpa Sampel<br>dan Tanpa Spike</td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
                <tr style="height: 10.0pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td colspan="2" style="border-left: 0.75pt solid #000; border-right: 0.75pt solid #000; border-top: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 4px; white-space: nowrap;">
                        <span style="font-size: 7.2pt;"><?php echo (isset($p['inokulum_tingkat']) && $p['inokulum_tingkat'] === '1 g atau mL') ? '&#9745;' : '&#9744;'; ?></span> &nbsp;1 g atau mL
                    </td>
                    <td colspan="2" style="border-left: 0.75pt solid #000; border-right: 0.75pt solid #000; border-top: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 4px; white-space: nowrap;">
                        <span style="font-size: 7.2pt;"><?php echo (isset($p['inokulum_tingkat']) && $p['inokulum_tingkat'] === '1 g atau mL') ? '&#9745;' : '&#9744;'; ?></span> &nbsp;1 g atau mL
                    </td>
                    <td colspan="2" style="border-left: 0.75pt solid #000; border-right: 0.75pt solid #000; border-top: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 4px; white-space: nowrap;">
                        <span style="font-size: 7.2pt;"><?php echo (isset($p['inokulum_tingkat']) && $p['inokulum_tingkat'] === '1 g atau mL') ? '&#9745;' : '&#9744;'; ?></span> &nbsp;1 g atau mL
                    </td>
                    <td colspan="2" style="border-left: 0.75pt solid #000; border-right: 0.75pt solid #000; border-top: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 4px; white-space: nowrap;">
                        <span style="font-size: 7.2pt;"><?php echo (isset($p['inokulum_tingkat']) && $p['inokulum_tingkat'] === '1 g atau mL') ? '&#9745;' : '&#9744;'; ?></span> &nbsp;1 g atau mL
                    </td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
                <tr style="height: 10.0pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td colspan="2" style="border-left: 0.75pt solid #000; border-right: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 4px; white-space: nowrap;">
                        <span style="font-size: 7.2pt;"><?php echo (isset($p['inokulum_tingkat']) && $p['inokulum_tingkat'] === '0,1 g atau mL') ? '&#9745;' : '&#9744;'; ?></span> &nbsp;0,1 g atau mL
                    </td>
                    <td colspan="2" style="border-left: 0.75pt solid #000; border-right: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 4px; white-space: nowrap;">
                        <span style="font-size: 7.2pt;"><?php echo (isset($p['inokulum_tingkat']) && $p['inokulum_tingkat'] === '0,1 g atau mL') ? '&#9745;' : '&#9744;'; ?></span> &nbsp;0,1 g atau mL
                    </td>
                    <td colspan="2" style="border-left: 0.75pt solid #000; border-right: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 4px; white-space: nowrap;">
                        <span style="font-size: 7.2pt;"><?php echo (isset($p['inokulum_tingkat']) && $p['inokulum_tingkat'] === '0,1 g atau mL') ? '&#9745;' : '&#9744;'; ?></span> &nbsp;0,1 g atau mL
                    </td>
                    <td colspan="2" style="border-left: 0.75pt solid #000; border-right: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 4px; white-space: nowrap;">
                        <span style="font-size: 7.2pt;"><?php echo (isset($p['inokulum_tingkat']) && $p['inokulum_tingkat'] === '0,1 g atau mL') ? '&#9745;' : '&#9744;'; ?></span> &nbsp;0,1 g atau mL
                    </td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
                <tr style="height: 10.0pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td colspan="2" style="border-left: 0.75pt solid #000; border-right: 0.75pt solid #000; border-bottom: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 4px; white-space: nowrap;">
                        <span style="font-size: 7.2pt;"><?php echo (isset($p['inokulum_tingkat']) && $p['inokulum_tingkat'] === 'Lainnya') ? '&#9745;' : '&#9744;'; ?></span> &nbsp;Lainnya: ........
                    </td>
                    <td colspan="2" style="border-left: 0.75pt solid #000; border-right: 0.75pt solid #000; border-bottom: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 4px; white-space: nowrap;">
                        <span style="font-size: 7.2pt;"><?php echo (isset($p['inokulum_tingkat']) && $p['inokulum_tingkat'] === 'Lainnya') ? '&#9745;' : '&#9744;'; ?></span> &nbsp;Lainnya: ........
                    </td>
                    <td colspan="2" style="border-left: 0.75pt solid #000; border-right: 0.75pt solid #000; border-bottom: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 4px; white-space: nowrap;">
                        <span style="font-size: 7.2pt;"><?php echo (isset($p['inokulum_tingkat']) && $p['inokulum_tingkat'] === 'Lainnya') ? '&#9745;' : '&#9744;'; ?></span> &nbsp;Lainnya: ........
                    </td>
                    <td colspan="2" style="border-left: 0.75pt solid #000; border-right: 0.75pt solid #000; border-bottom: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 4px; white-space: nowrap;">
                        <span style="font-size: 7.2pt;"><?php echo (isset($p['inokulum_tingkat']) && $p['inokulum_tingkat'] === 'Lainnya') ? '&#9745;' : '&#9744;'; ?></span> &nbsp;Lainnya: ........
                    </td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>

                <!-- 1. Pra Pengayaan -->
                <tr style="height: 10.0pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td rowspan="3" style="border: 0.75pt solid #000; font-size: 7.2pt; text-align: center; vertical-align: middle; white-space: nowrap;"><?php echo !empty($t0['tanggal']) ? date('d-m-Y', strtotime($t0['tanggal'])) : (!empty($contoh_mode) ? 'N/A' : ''); ?></td>
                    <td rowspan="3" colspan="2" style="border: 0.75pt solid #000; font-size: 7.2pt; text-align: center; vertical-align: middle; white-space: nowrap;">Pra Pengayaan</td>
                    <td rowspan="3" colspan="3" style="border: 0.75pt solid #000; font-size: 7.0pt; text-align: center; vertical-align: middle; padding: 2px 4px; line-height: 1.15;">sesuai pelarut yang digunakan saat homogenisasi awal</td>
                    <td colspan="2" style="border-left: 0.75pt solid #000; border-top: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 4px; white-space: nowrap;">
                        <span style="font-size: 7.2pt;">&#9744;</span> &nbsp;30 - 37
                    </td>
                    <td colspan="2" style="border-left: 0.75pt solid #000; border-right: 0.75pt solid #000; border-top: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 4px; white-space: nowrap;">
                        <span style="font-size: 7.2pt;">&#9744;</span> &nbsp;2 - 5
                    </td>
                    <td rowspan="3" colspan="2" style="border: 0.75pt solid #000; font-size: 7.2pt; text-align: center; vertical-align: middle; white-space: nowrap;"><?php echo !empty($t0['sampel_spike']) ? html_escape($t0['sampel_spike']) : (!empty($contoh_mode) ? 'N/A' : ''); ?></td>
                    <td rowspan="3" colspan="2" style="border: 0.75pt solid #000; font-size: 7.2pt; text-align: center; vertical-align: middle; white-space: nowrap;"><?php echo !empty($t0['media_spike']) ? html_escape($t0['media_spike']) : (!empty($contoh_mode) ? 'N/A' : ''); ?></td>
                    <td rowspan="3" colspan="2" style="border: 0.75pt solid #000; font-size: 7.2pt; text-align: center; vertical-align: middle; white-space: nowrap;"><?php echo !empty($t0['kontrol_media']) ? html_escape($t0['kontrol_media']) : (!empty($contoh_mode) ? 'N/A' : ''); ?></td>
                    <td rowspan="3" colspan="2" style="border: 0.75pt solid #000; font-size: 7.2pt; text-align: center; vertical-align: middle; white-space: nowrap;"><?php echo !empty($t0['sampel_uji']) ? html_escape($t0['sampel_uji']) : (!empty($contoh_mode) ? 'N/A' : ''); ?></td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
                <tr style="height: 10.0pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td colspan="2" style="border-left: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 4px; white-space: nowrap;">
                        <span style="font-size: 7.2pt;">&#9744;</span> &nbsp;Lainnya:
                    </td>
                    <td colspan="2" style="border-left: 0.75pt solid #000; border-right: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 4px; white-space: nowrap;">
                        <span style="font-size: 7.2pt;">&#9744;</span> &nbsp;Lainnya:
                    </td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
                <tr style="height: 10.0pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td colspan="2" style="border-left: 0.75pt solid #000; border-bottom: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 18px; white-space: nowrap;">..........</td>
                    <td colspan="2" style="border-left: 0.75pt solid #000; border-right: 0.75pt solid #000; border-bottom: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 18px; white-space: nowrap;">..........</td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>

                <!-- 2. Pengayaan / Pengayaan Selektif -->
                <tr style="height: 10.0pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td rowspan="6" style="border: 0.75pt solid #000; font-size: 7.2pt; text-align: center; vertical-align: middle; white-space: nowrap;"><?php echo !empty($t1['tanggal']) ? date('d-m-Y', strtotime($t1['tanggal'])) : ''; ?></td>
                    <td rowspan="2" colspan="2" style="border-left: 0.75pt solid #000; border-right: 0.75pt solid #000; border-top: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 4px; white-space: nowrap;">
                        <span style="font-size: 7.2pt;">&#9744;</span> &nbsp;Pengayaan
                    </td>
                    <td colspan="3" style="border-left: 0.75pt solid #000; border-right: 0.75pt solid #000; border-top: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 4px; white-space: nowrap;">
                        <span style="font-size: 7.2pt;">&#9744;</span> &nbsp;10 mL &nbsp;MLB
                    </td>
                    <td colspan="2" style="border-left: 0.75pt solid #000; border-top: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 4px; white-space: nowrap;">
                        <span style="font-size: 7.2pt;">&#9744;</span> &nbsp;30 - 35
                    </td>
                    <td colspan="2" style="border-left: 0.75pt solid #000; border-right: 0.75pt solid #000; border-top: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 4px; white-space: nowrap;">
                        <span style="font-size: 7.2pt;">&#9744;</span> &nbsp;18 - 24
                    </td>
                    <td rowspan="6" colspan="2" style="border: 0.75pt solid #000; font-size: 7.2pt; text-align: center; vertical-align: middle; white-space: nowrap;"><?php echo !empty($t1['sampel_spike']) ? html_escape($t1['sampel_spike']) : ''; ?></td>
                    <td rowspan="6" colspan="2" style="border: 0.75pt solid #000; font-size: 7.2pt; text-align: center; vertical-align: middle; white-space: nowrap;"><?php echo !empty($t1['media_spike']) ? html_escape($t1['media_spike']) : 'Keruh'; ?></td>
                    <td rowspan="6" colspan="2" style="border: 0.75pt solid #000; font-size: 7.2pt; text-align: center; vertical-align: middle; white-space: nowrap;"><?php echo !empty($t1['kontrol_media']) ? html_escape($t1['kontrol_media']) : ''; ?></td>
                    <td rowspan="6" colspan="2" style="border: 0.75pt solid #000; font-size: 7.2pt; text-align: center; vertical-align: middle; white-space: nowrap;"><?php echo !empty($t1['sampel_uji']) ? html_escape($t1['sampel_uji']) : ''; ?></td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
                <tr style="height: 10.0pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td colspan="3" style="border-left: 0.75pt solid #000; border-right: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 4px; white-space: nowrap;">
                        <span style="font-size: 7.2pt;">&#9744;</span> &nbsp;90 mL &nbsp;TSB
                    </td>
                    <td colspan="2" style="border-left: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 4px; white-space: nowrap;">
                        <span style="font-size: 7.2pt;">&#9744;</span> &nbsp;35 - 37
                    </td>
                    <td colspan="2" style="border-left: 0.75pt solid #000; border-right: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 4px; white-space: nowrap;">
                        <span style="font-size: 7.2pt;">&#9744;</span> &nbsp;24 - 48
                    </td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
                <tr style="height: 10.0pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td rowspan="2" colspan="2" style="border-left: 0.75pt solid #000; border-right: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 4px; white-space: nowrap;">
                        <span style="font-size: 7.2pt;">&#9744;</span> &nbsp;Pengayaan<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Selektif
                    </td>
                    <td colspan="3" style="border-left: 0.75pt solid #000; border-right: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 4px; white-space: nowrap;">
                        <span style="font-size: 7.2pt;">&#9744;</span> &nbsp;100 mL TSB
                    </td>
                    <td colspan="2" style="border-left: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 4px; white-space: nowrap;">
                        <span style="font-size: 7.2pt;">&#9744;</span> &nbsp;Lainnya:
                    </td>
                    <td colspan="2" style="border-left: 0.75pt solid #000; border-right: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 4px; white-space: nowrap;">
                        <span style="font-size: 7.2pt;">&#9744;</span> &nbsp;Lainnya:
                    </td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
                <tr style="height: 10.0pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td colspan="3" style="border-left: 0.75pt solid #000; border-right: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 4px; white-space: nowrap;">
                        <span style="font-size: 7.2pt;">&#9744;</span> &nbsp;90 mL &nbsp;EEB
                    </td>
                    <td colspan="2" style="border-left: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 18px; white-space: nowrap;">..........</td>
                    <td colspan="2" style="border-left: 0.75pt solid #000; border-right: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 18px; white-space: nowrap;">..........</td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
                <tr style="height: 10.0pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td rowspan="2" colspan="2" style="border-left: 0.75pt solid #000; border-right: 0.75pt solid #000; border-bottom: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 4px; white-space: nowrap;">
                        <span style="font-size: 7.2pt;">&#9744;</span> &nbsp;Lainnya:<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;..........
                    </td>
                    <td colspan="3" style="border-left: 0.75pt solid #000; border-right: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 4px; white-space: nowrap;">
                        <span style="font-size: 7.2pt;">&#9744;</span> &nbsp;100 mL Lactose Broth
                    </td>
                    <td colspan="2" style="border-left: 0.75pt solid #000;"></td>
                    <td colspan="2" style="border-left: 0.75pt solid #000; border-right: 0.75pt solid #000;"></td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
                <tr style="height: 10.0pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td colspan="3" style="border-left: 0.75pt solid #000; border-right: 0.75pt solid #000; border-bottom: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 4px; white-space: nowrap;">
                        <span style="font-size: 7.2pt;">&#9744;</span> &nbsp;Lainnya: .........
                    </td>
                    <td colspan="2" style="border-left: 0.75pt solid #000; border-bottom: 0.75pt solid #000;"></td>
                    <td colspan="2" style="border-left: 0.75pt solid #000; border-right: 0.75pt solid #000; border-bottom: 0.75pt solid #000;"></td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
                <!-- 3. Seleksi -->
                <tr style="height: 10.0pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td rowspan="3" style="border: 0.75pt solid #000; font-size: 7.2pt; text-align: center; vertical-align: middle; white-space: nowrap;"><?php echo !empty($t2['tanggal']) ? date('d-m-Y', strtotime($t2['tanggal'])) : ''; ?></td>
                    <td rowspan="2" colspan="2" style="border-left: 0.75pt solid #000; border-right: 0.75pt solid #000; border-top: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 4px; white-space: nowrap;">
                        <span style="font-size: 7.2pt;">&#9744;</span> &nbsp;Seleksi
                    </td>
                    <td colspan="3" style="border-left: 0.75pt solid #000; border-right: 0.75pt solid #000; border-top: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 4px; white-space: nowrap;">
                        <span style="font-size: 7.2pt;">&#9744;</span> &nbsp;10 mL &nbsp;RVS
                    </td>
                    <td colspan="2" style="border-left: 0.75pt solid #000; border-top: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 4px; white-space: nowrap;">
                        <span style="font-size: 7.2pt;">&#9744;</span> &nbsp;30 - 35
                    </td>
                    <td colspan="2" style="border-left: 0.75pt solid #000; border-right: 0.75pt solid #000; border-top: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 4px; white-space: nowrap;">
                        <span style="font-size: 7.2pt;">&#9744;</span> &nbsp;24 - 48
                    </td>
                    <td rowspan="3" colspan="2" style="border: 0.75pt solid #000; font-size: 7.2pt; text-align: center; vertical-align: middle; white-space: nowrap;"><?php echo !empty($t2['sampel_spike']) ? html_escape($t2['sampel_spike']) : ''; ?></td>
                    <td rowspan="3" colspan="2" style="border: 0.75pt solid #000; font-size: 7.2pt; text-align: center; vertical-align: middle; white-space: nowrap;"><?php echo !empty($t2['media_spike']) ? html_escape($t2['media_spike']) : 'Keruh'; ?></td>
                    <td rowspan="3" colspan="2" style="border: 0.75pt solid #000; font-size: 7.2pt; text-align: center; vertical-align: middle; white-space: nowrap;"><?php echo !empty($t2['kontrol_media']) ? html_escape($t2['kontrol_media']) : ''; ?></td>
                    <td rowspan="3" colspan="2" style="border: 0.75pt solid #000; font-size: 7.2pt; text-align: center; vertical-align: middle; white-space: nowrap;"><?php echo !empty($t2['sampel_uji']) ? html_escape($t2['sampel_uji']) : ''; ?></td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
                <tr style="height: 10.0pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td colspan="3" style="border-left: 0.75pt solid #000; border-right: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 4px; white-space: nowrap;">
                        <span style="font-size: 7.2pt;">&#9744;</span> &nbsp;100 mL TBGB
                    </td>
                    <td colspan="2" style="border-left: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 4px; white-space: nowrap;">
                        <span style="font-size: 7.2pt;">&#9744;</span> &nbsp;Lainnya:
                    </td>
                    <td colspan="2" style="border-left: 0.75pt solid #000; border-right: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 4px; white-space: nowrap;">
                        <span style="font-size: 7.2pt;">&#9744;</span> &nbsp;Lainnya:
                    </td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
                <tr style="height: 10.0pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td colspan="2" style="border-left: 0.75pt solid #000; border-right: 0.75pt solid #000; border-bottom: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 4px; white-space: nowrap;">
                        <span style="font-size: 7.2pt;">&#9744;</span> &nbsp;Lainnya: .....
                    </td>
                    <td colspan="3" style="border-left: 0.75pt solid #000; border-right: 0.75pt solid #000; border-bottom: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 4px; white-space: nowrap;">
                        <span style="font-size: 7.2pt;">&#9744;</span> &nbsp;Lainnya: ............
                    </td>
                    <td colspan="2" style="border-left: 0.75pt solid #000; border-bottom: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 18px; white-space: nowrap;">..........</td>
                    <td colspan="2" style="border-left: 0.75pt solid #000; border-right: 0.75pt solid #000; border-bottom: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 18px; white-space: nowrap;">..........</td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
                <!-- 4. Sub Kultur Isolasi -->
                <tr style="height: 9.8pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td rowspan="8" style="border: 0.75pt solid #000; font-size: 7.2pt; text-align: center; vertical-align: middle; white-space: nowrap;"><?php echo !empty($t3['tanggal']) ? date('d-m-Y', strtotime($t3['tanggal'])) : ''; ?></td>
                    <td rowspan="2" colspan="2" style="border-left: 0.75pt solid #000; border-right: 0.75pt solid #000; border-top: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 4px; white-space: nowrap;">
                        <span style="font-size: 7.2pt;">&#9744;</span> &nbsp;Sub Kultur
                    </td>
                    <td colspan="3" style="border-left: 0.75pt solid #000; border-right: 0.75pt solid #000; border-top: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 4px; white-space: nowrap;">
                        <span style="font-size: 7.2pt;">&#9744;</span> &nbsp;BPA
                    </td>
                    <td colspan="2" style="border-left: 0.75pt solid #000; border-top: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 4px; white-space: nowrap;">
                        <span style="font-size: 7.2pt;">&#9744;</span> &nbsp;30 - 35
                    </td>
                    <td colspan="2" style="border-left: 0.75pt solid #000; border-right: 0.75pt solid #000; border-top: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 4px; white-space: nowrap;">
                        <span style="font-size: 7.2pt;">&#9744;</span> &nbsp;18 - 72
                    </td>
                    <td rowspan="8" colspan="2" style="border: 0.75pt solid #000; font-size: 7.2pt; text-align: center; vertical-align: middle; white-space: nowrap;"><?php echo !empty($t3['sampel_spike']) ? html_escape($t3['sampel_spike']) : ''; ?></td>
                    <td rowspan="8" colspan="2" style="border: 0.75pt solid #000; font-size: 7.2pt; text-align: center; vertical-align: middle; white-space: nowrap;"><?php echo !empty($t3['media_spike']) ? html_escape($t3['media_spike']) : 'Positif'; ?></td>
                    <td rowspan="8" colspan="2" style="border: 0.75pt solid #000; font-size: 7.2pt; text-align: center; vertical-align: middle; white-space: nowrap;"><?php echo !empty($t3['kontrol_media']) ? html_escape($t3['kontrol_media']) : ''; ?></td>
                    <td rowspan="8" colspan="2" style="border: 0.75pt solid #000; font-size: 7.2pt; text-align: center; vertical-align: middle; white-space: nowrap;"><?php echo !empty($t3['sampel_uji']) ? html_escape($t3['sampel_uji']) : ''; ?></td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
                <tr style="height: 9.8pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td colspan="3" style="border-left: 0.75pt solid #000; border-right: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 4px; white-space: nowrap;">
                        <span style="font-size: 7.2pt;">&#9744;</span> &nbsp;MSA
                    </td>
                    <td colspan="2" style="border-left: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 4px; white-space: nowrap;">
                        <span style="font-size: 7.2pt;">&#9744;</span> &nbsp;Lainnya:
                    </td>
                    <td colspan="2" style="border-left: 0.75pt solid #000; border-right: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 4px; white-space: nowrap;">
                        <span style="font-size: 7.2pt;">&#9744;</span> &nbsp;18 - 24
                    </td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
                <tr style="height: 9.8pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td rowspan="2" colspan="2" style="border-left: 0.75pt solid #000; border-right: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 4px; white-space: nowrap;">
                        <span style="font-size: 7.2pt;">&#9744;</span> &nbsp;Isolasi
                    </td>
                    <td colspan="3" style="border-left: 0.75pt solid #000; border-right: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 4px; white-space: nowrap;">
                        <span style="font-size: 7.2pt;">&#9744;</span> &nbsp;CETA
                    </td>
                    <td colspan="2" style="border-left: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 18px; white-space: nowrap;">..........</td>
                    <td colspan="2" style="border-left: 0.75pt solid #000; border-right: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 4px; white-space: nowrap;">
                        <span style="font-size: 7.2pt;">&#9744;</span> &nbsp;Lainnya:
                    </td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
                <tr style="height: 9.8pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td colspan="3" style="border-left: 0.75pt solid #000; border-right: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 4px; white-space: nowrap;">
                        <span style="font-size: 7.2pt;">&#9744;</span> &nbsp;SDA
                    </td>
                    <td colspan="2" style="border-left: 0.75pt solid #000;"></td>
                    <td colspan="2" style="border-left: 0.75pt solid #000; border-right: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 18px; white-space: nowrap;">..........</td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
                <tr style="height: 9.8pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td rowspan="2" colspan="2" style="border-left: 0.75pt solid #000; border-right: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 4px; white-space: nowrap;">
                        <span style="font-size: 7.2pt;">&#9744;</span> &nbsp;Lainnya:<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;..........
                    </td>
                    <td colspan="3" style="border-left: 0.75pt solid #000; border-right: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 4px; white-space: nowrap;">
                        <span style="font-size: 7.2pt;">&#9744;</span> &nbsp;XLD
                    </td>
                    <td colspan="2" style="border-left: 0.75pt solid #000;"></td>
                    <td colspan="2" style="border-left: 0.75pt solid #000; border-right: 0.75pt solid #000;"></td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
                <tr style="height: 9.8pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td colspan="3" style="border-left: 0.75pt solid #000; border-right: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 4px; white-space: nowrap;">
                        <span style="font-size: 7.2pt;">&#9744;</span> &nbsp;BGA
                    </td>
                    <td colspan="2" style="border-left: 0.75pt solid #000;"></td>
                    <td colspan="2" style="border-left: 0.75pt solid #000; border-right: 0.75pt solid #000;"></td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
                <tr style="height: 9.8pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td rowspan="2" colspan="2" style="border-left: 0.75pt solid #000; border-right: 0.75pt solid #000; border-bottom: 0.75pt solid #000;"></td>
                    <td colspan="3" style="border-left: 0.75pt solid #000; border-right: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 4px; white-space: nowrap;">
                        <span style="font-size: 7.2pt;">&#9744;</span> &nbsp;MCA
                    </td>
                    <td colspan="2" style="border-left: 0.75pt solid #000;"></td>
                    <td colspan="2" style="border-left: 0.75pt solid #000; border-right: 0.75pt solid #000;"></td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
                <tr style="height: 9.8pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td colspan="3" style="border-left: 0.75pt solid #000; border-right: 0.75pt solid #000; border-bottom: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 4px; white-space: nowrap;">
                        <span style="font-size: 7.2pt;">&#9744;</span> &nbsp;Lainnya: ............
                    </td>
                    <td colspan="2" style="border-left: 0.75pt solid #000; border-bottom: 0.75pt solid #000;"></td>
                    <td colspan="2" style="border-left: 0.75pt solid #000; border-right: 0.75pt solid #000; border-bottom: 0.75pt solid #000;"></td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
                <!-- Row Konfirmasi 1 -->
                <tr style="height: 11.5pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td rowspan="4" style="border-top: 0.75pt solid #000; border-bottom: 0.75pt solid #000; border-left: 0.75pt solid #000; border-right: 0.75pt solid #000; font-size: 6.8pt; text-align: center; vertical-align: middle; white-space: nowrap;"><?php echo !empty($t4['tanggal']) ? date('d-m-Y', strtotime($t4['tanggal'])) : ''; ?></td>
                    <td rowspan="4" colspan="2" style="border-top: 0.75pt solid #000; border-bottom: 0.75pt solid #000; border-right: 0.75pt solid #000; font-size: 7.0pt; text-align: center; vertical-align: middle; white-space: nowrap;">Konfirmasi</td>
                    <td colspan="3" style="border-top: 0.75pt solid #000; border-left: 0.75pt solid #000; border-right: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 4px; white-space: nowrap;">
                        <span style="font-size: 7.2pt;">&#9744;</span> &nbsp;Biokimia Kit
                    </td>
                    <td colspan="2" style="border-top: 0.75pt solid #000; border-left: 0.75pt solid #000; font-size: 6.8pt; vertical-align: middle; padding-left: 4px; white-space: nowrap;">
                        <span style="font-size: 7.2pt;">&#9744;</span> 35 - 37
                    </td>
                    <td colspan="2" style="border-top: 0.75pt solid #000; border-left: 0.75pt solid #000; border-right: 0.75pt solid #000; font-size: 6.8pt; vertical-align: middle; padding-left: 4px; white-space: nowrap;">
                        <span style="font-size: 7.2pt;">&#9744;</span> 18 - 24
                    </td>
                    <td rowspan="4" colspan="2" style="border-top: 0.75pt solid #000; border-bottom: 0.75pt solid #000; border-left: 0.75pt solid #000; border-right: 0.75pt solid #000; font-size: 7.0pt; text-align: center; vertical-align: middle;"><?php echo !empty($t4['sampel_spike']) ? html_escape($t4['sampel_spike']) : ''; ?></td>
                    <td rowspan="4" colspan="2" style="border-top: 0.75pt solid #000; border-bottom: 0.75pt solid #000; border-right: 0.75pt solid #000; font-size: 6.8pt; text-align: center; vertical-align: middle; line-height: 1.25;">
                        Biokimia Kit /<br>Konvensional *)<br><br>........................
                    </td>
                    <td rowspan="4" colspan="2" style="border-top: 0.75pt solid #000; border-bottom: 0.75pt solid #000; border-right: 0.75pt solid #000; font-size: 7.0pt; text-align: center; vertical-align: middle;"><?php echo !empty($t4['kontrol_media']) ? html_escape($t4['kontrol_media']) : 'N/A'; ?></td>
                    <td rowspan="4" colspan="2" style="border-top: 0.75pt solid #000; border-bottom: 0.75pt solid #000; border-right: 0.75pt solid #000; font-size: 7.0pt; text-align: center; vertical-align: middle;"><?php echo !empty($t4['sampel_uji']) ? html_escape($t4['sampel_uji']) : ''; ?></td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
                <!-- Row Konfirmasi 2 -->
                <tr style="height: 10.0pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td colspan="3" style="border-left: 0.75pt solid #000; border-right: 0.75pt solid #000; font-size: 6.8pt; vertical-align: middle; padding-left: 18px; color: #555; white-space: nowrap;">
                        .........................
                    </td>
                    <td colspan="2" style="border-left: 0.75pt solid #000; font-size: 6.8pt; vertical-align: middle; padding-left: 4px; white-space: nowrap;">
                        <span style="font-size: 7.2pt;">&#9744;</span> Lainnya:
                    </td>
                    <td colspan="2" style="border-left: 0.75pt solid #000; border-right: 0.75pt solid #000; font-size: 6.8pt; vertical-align: middle; padding-left: 4px; white-space: nowrap;">
                        <span style="font-size: 7.2pt;">&#9744;</span> Lainnya:
                    </td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
                <!-- Row Konfirmasi 3 -->
                <tr style="height: 11.5pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td colspan="3" style="border-left: 0.75pt solid #000; border-right: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 4px; white-space: nowrap;">
                        <span style="font-size: 7.2pt;">&#9744;</span> &nbsp;Biokimia Konvensional
                    </td>
                    <td colspan="2" style="border-left: 0.75pt solid #000; font-size: 6.8pt; vertical-align: middle; padding-left: 16px; color: #555; white-space: nowrap;">
                        ............
                    </td>
                    <td colspan="2" style="border-left: 0.75pt solid #000; border-right: 0.75pt solid #000; font-size: 6.8pt; vertical-align: middle; padding-left: 16px; color: #555; white-space: nowrap;">
                        ............
                    </td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
                <!-- Row Konfirmasi 4 -->
                <tr style="height: 10.0pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td colspan="3" style="border-left: 0.75pt solid #000; border-right: 0.75pt solid #000; border-bottom: 0.75pt solid #000; font-size: 6.8pt; vertical-align: middle; padding-left: 18px; color: #555; white-space: nowrap;">
                        .........................
                    </td>
                    <td colspan="2" style="border-left: 0.75pt solid #000; border-bottom: 0.75pt solid #000;"></td>
                    <td colspan="2" style="border-left: 0.75pt solid #000; border-right: 0.75pt solid #000; border-bottom: 0.75pt solid #000;"></td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
                <!-- Baris Keterangan & HASIL -->
                <tr style="height: 13.5pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td style="font-size: 7.0pt; vertical-align: middle; padding-left: 2px; white-space: nowrap;">Keterangan</td>
                    <td style="font-size: 8.0pt; text-align: center; vertical-align: middle;">&#9744;</td>
                    <td colspan="6" style="font-size: 6.8pt; vertical-align: middle; white-space: nowrap;">: tandai salah satu sesuai pilihan, atau isi lainnya</td>
                    <td colspan="2" style="border: 0.75pt solid #000; font-size: 7.0pt; font-weight: bold; text-align: center; vertical-align: middle; background-color: #f2f2f2; letter-spacing: 1px;">HASIL</td>
                    <td colspan="2" style="border: 0.75pt solid #000; font-size: 6.8pt; text-align: center; vertical-align: middle;"></td>
                    <td colspan="2" style="border: 0.75pt solid #000; font-size: 6.8pt; text-align: center; vertical-align: middle;"></td>
                    <td colspan="2" style="border: 0.75pt solid #000; font-size: 6.8pt; text-align: center; vertical-align: middle;"></td>
                    <td colspan="2" style="border: 0.75pt solid #000; font-size: 6.8pt; text-align: center; vertical-align: middle;"></td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
                <tr style="height: 9.0pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td></td>
                    <td style="font-size: 6.8pt; text-align: center; vertical-align: middle; white-space: nowrap;">N/A</td>
                    <td colspan="6" style="font-size: 6.8pt; vertical-align: middle; white-space: nowrap;">: tidak dilakukan</td>
                    <td colspan="10"></td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
                <tr style="height: 9.0pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td></td>
                    <td style="font-size: 6.8pt; text-align: center; vertical-align: middle; white-space: nowrap;">*)</td>
                    <td colspan="6" style="font-size: 6.8pt; vertical-align: middle; white-space: nowrap;">: coret yang tidak perlu</td>
                    <td colspan="10"></td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
                <tr style="height: 3.5pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td colspan="18"></td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
                <!-- Tabel Karakteristik Koloni Mikroba Baku -->
                <!-- Kolom Kiri: Cols 2-10 (44.8%), Kolom Kanan: Cols 11-19 (50.4%) -->
                <tr style="height: 8.5pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td colspan="9" style="font-size: 5.8pt; vertical-align: top; line-height: 1.15; padding: 0.5px 2px;">
                        <span style="display: inline-block; width: 72px; font-weight: bold; white-space: nowrap;">Positif S. aureus</span>
                        <span style="display: inline-block; width: 28px; font-weight: 600; white-space: nowrap;">BPA</span>
                        <span>: Koloni cembung, warna hitam mengkilat dikelilingi zona bening</span>
                    </td>
                    <td colspan="9" style="font-size: 5.8pt; vertical-align: top; line-height: 1.15; padding: 0.5px 2px 0.5px 6px;">
                        <span style="display: inline-block; width: 72px; font-weight: bold; white-space: nowrap;">Positif Shigella spp</span>
                        <span style="display: inline-block; width: 26px; font-weight: 600; white-space: nowrap;">XLD</span>
                        <span>: Koloni bulat warna putih-merah muda, media berubah menjadi merah</span>
                    </td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
                <tr style="height: 8.5pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td colspan="9" style="font-size: 5.8pt; vertical-align: top; line-height: 1.15; padding: 0.5px 2px;">
                        <span style="display: inline-block; width: 72px;"></span>
                        <span style="display: inline-block; width: 28px; font-weight: 600; white-space: nowrap;">VJA</span>
                        <span>: Koloni cembung, warna hitam mengkilat</span>
                    </td>
                    <td colspan="9" style="font-size: 5.8pt; vertical-align: top; line-height: 1.15; padding: 0.5px 2px 0.5px 6px;">
                        <span style="display: inline-block; width: 72px;"></span>
                        <span style="display: inline-block; width: 26px; font-weight: 600; white-space: nowrap;">MCA</span>
                        <span>: Koloni bergerigi warna putih oranye, media berubah menjadi oranye</span>
                    </td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
                <tr style="height: 8.5pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td colspan="9" style="font-size: 5.8pt; vertical-align: top; line-height: 1.15; padding: 0.5px 2px;">
                        <span style="display: inline-block; width: 72px;"></span>
                        <span style="display: inline-block; width: 28px; font-weight: 600; white-space: nowrap;">MSA</span>
                        <span>: Koloni cembung, warna kuning</span>
                    </td>
                    <td colspan="9" style="font-size: 5.8pt; vertical-align: top; line-height: 1.15; padding: 0.5px 2px 0.5px 6px;">
                        <span style="display: inline-block; width: 72px; font-weight: bold; white-space: nowrap;">Positif E.coli :</span>
                        <span style="display: inline-block; width: 26px; font-weight: 600; white-space: nowrap;">MCA</span>
                        <span>: Koloni berwarna merah muda hingga merah,</span>
                    </td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
                <!-- Lanjutan keterangan MCA (Positif E.coli) persis di bawah kata Koloni -->
                <tr style="height: 8.5pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td colspan="9" style="font-size: 5.8pt; vertical-align: top; line-height: 1.15; padding: 0.5px 2px;">
                        <span style="display: inline-block; width: 72px; font-weight: bold; white-space: nowrap;">Positif P. aeruginosa</span>
                        <span style="display: inline-block; width: 28px; font-weight: 600; white-space: nowrap;">CETA</span>
                        <span>: Koloni berwarna kuning kehijauan dengan fluoresensi</span>
                    </td>
                    <td colspan="9" style="font-size: 5.8pt; vertical-align: top; line-height: 1.15; padding: 0.5px 2px 0.5px 112px;">
                        <span>dikelilingi zona presipitasi berwarna merah gelap</span>
                    </td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
                <!-- Lanjutan keterangan CETA persis di bawah kata Koloni -->
                <tr style="height: 8.5pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td colspan="9" style="font-size: 5.8pt; vertical-align: top; line-height: 1.15; padding: 0.5px 2px 0.5px 108px;">
                        <span>di bawah lampu UV</span>
                    </td>
                    <td colspan="9"></td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
                <tr style="height: 8.5pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td colspan="9" style="font-size: 5.8pt; vertical-align: top; line-height: 1.15; padding: 0.5px 2px;">
                        <span style="display: inline-block; width: 72px; font-weight: bold; white-space: nowrap;">Positif C. albicans</span>
                        <span style="display: inline-block; width: 28px; font-weight: 600; white-space: nowrap;">SDA</span>
                        <span>: Koloni berwarna putih sampai krem, permukaan cembung dan licin</span>
                    </td>
                    <td colspan="9"></td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
                <tr style="height: 8.5pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td colspan="9" style="font-size: 5.8pt; vertical-align: top; line-height: 1.15; padding: 0.5px 2px;">
                        <span style="display: inline-block; width: 72px; font-weight: bold; white-space: nowrap;">Positif Salmonella spp</span>
                        <span style="display: inline-block; width: 28px; font-weight: 600; white-space: nowrap;">BGA</span>
                        <span>: Koloni berukuran kecil, dari tidak berwarna, merah muda,</span>
                    </td>
                    <td colspan="9"></td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
                <!-- Lanjutan keterangan BGA persis di bawah kata Koloni -->
                <tr style="height: 8.5pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td colspan="9" style="font-size: 5.8pt; vertical-align: top; line-height: 1.15; padding: 0.5px 2px 0.5px 108px;">
                        <span>hingga merah dan translusen hingga keruh (opaque) dengan lingkaran merah muda hingga merah</span>
                    </td>
                    <td colspan="9"></td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
                <tr style="height: 8.5pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td colspan="9" style="font-size: 5.8pt; vertical-align: top; line-height: 1.15; padding: 0.5px 2px;">
                        <span style="display: inline-block; width: 72px;"></span>
                        <span style="display: inline-block; width: 28px; font-weight: 600; white-space: nowrap;">XLD</span>
                        <span>: Koloni berwarna merah dengan atau tanpa bintik hitam di tengah</span>
                    </td>
                    <td colspan="9"></td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
                <tr style="height: 4.5pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td colspan="18"></td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>

                <!-- Baris Hasil Uji -->
                <tr style="height: 12.5pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td colspan="2" style="font-size: 7.5pt; font-weight: bold; vertical-align: middle; white-space: nowrap;">Hasil Uji</td>
                    <td colspan="16" style="font-size: 7.2pt; vertical-align: middle; white-space: nowrap;">
                        : &nbsp;<strong style="font-size: 7.5pt; color: #000;"><?php echo html_escape($p['hasil_uji'] ?? ($hasil['hasil_uji'] ?? '-')); ?></strong>
                    </td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>

                <!-- Baris Pustaka/Metode & Syarat -->
                <tr style="height: 12.5pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td colspan="2" style="font-size: 7.0pt; vertical-align: middle; white-space: nowrap;">Pustaka/Metode</td>
                    <td colspan="9" style="font-size: 7.0pt; vertical-align: middle; white-space: nowrap;">
                        : &nbsp;<span style="font-size: 6.8pt;"><?php echo html_escape($p['pustaka_metode'] ?? ($hasil['pustaka_acuan'] ?? ($metode['pustaka_acuan'] ?? '-'))); ?></span>
                    </td>
                    <td colspan="7" style="font-size: 7.0pt; vertical-align: middle; white-space: nowrap; padding-left: 10px;">
                        Syarat : &nbsp;<strong style="font-size: 7.0pt; color: #000;"><?php echo html_escape($p['syarat'] ?? ($hasil['persyaratan'] ?? ($metode['persyaratan'] ?? '-'))); ?></strong>
                    </td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>

                <tr style="height: 3.5pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td colspan="18"></td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>

                <!-- Baris KESIMPULAN -->
                <tr style="height: 13.5pt;">
                    <td style="border-top: 0.75pt solid #000; border-bottom: 0.75pt solid #000; border-left: 1.5pt solid #000;"></td>
                    <td colspan="2" style="border-top: 0.75pt solid #000; border-bottom: 0.75pt solid #000; font-size: 7.0pt; font-weight: bold; vertical-align: middle; white-space: nowrap;">KESIMPULAN :</td>
                    <td colspan="16" style="border-top: 0.75pt solid #000; border-bottom: 0.75pt solid #000; font-size: 6.8pt; font-style: italic; vertical-align: middle; white-space: nowrap;">
                        <?php
                            $ks = $p['kesimpulan_lcp'] ?? ($hasil['kesimpulan'] ?? '');
                            $ms_style = (strpos($ks, 'MS') !== false && strpos($ks, 'TMS') === false) ? 'font-weight: bold; background: #e8f5e9; padding: 2px 6px; border: 1.5px solid #2e7d32; border-radius: 3px;' : 'color: #111;';
                            $tms_style = (strpos($ks, 'TMS') !== false) ? 'font-weight: bold; background: #ffebee; padding: 2px 6px; border: 1.5px solid #c62828; border-radius: 3px;' : 'color: #111;';
                            $hpst_style = (strpos($ks, 'HPST') !== false) ? 'font-weight: bold; background: #e3f2fd; padding: 2px 6px; border: 1.5px solid #1565c0; border-radius: 3px;' : 'color: #111;';
                        ?>
                        <span style="<?php echo $ms_style; ?>">Memenuhi Syarat (MS)</span> / 
                        <span style="<?php echo $tms_style; ?>">Tidak Memenuhi Syarat (TMS)</span> / 
                        <span style="<?php echo $hpst_style; ?>">Hasil Pengujian Seperti Tersebut (HPST)</span> *)
                    </td>
                    <td style="border-top: 0.75pt solid #000; border-bottom: 0.75pt solid #000; border-right: 1.5pt solid #000;"></td>
                </tr>

                <!-- Box Tanda Tangan Penguji & Diperiksa oleh -->
                <tr style="height: 11.5pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td colspan="11" style="font-size: 7.0pt; vertical-align: middle; padding-left: 4px; white-space: nowrap;">Tanda Tangan Penguji :</td>
                    <td colspan="7" style="border-left: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 8px; white-space: nowrap;">Diperiksa oleh :</td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
                <tr style="height: 32.0pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td colspan="11" style="vertical-align: middle; padding-left: 20px;">
                        <?php if (!empty($ttd_penguji_img)): ?>
                            <div style="height: 26px; display: flex; align-items: center; justify-content: flex-start;">
                                <img src="<?php echo $ttd_penguji_img; ?>" style="max-height: 24px; max-width: 140px; object-fit: contain;" alt="TTD Penguji">
                            </div>
                        <?php else: ?>
                            <div style="height: 22px; display: flex; align-items: center; color: #888; font-size: 6.5pt; font-style: italic;">
                                [ Tanda Tangan Digital Penguji ]
                            </div>
                        <?php endif; ?>
                        <div style="font-size: 7.0pt; font-weight: bold; margin-top: 2px; color: #000;"><?php echo html_escape($penguji['nama_lengkap'] ?? ($sesi['nama_penguji'] ?? '-')); ?></div>
                    </td>
                    <td colspan="7" style="border-left: 0.75pt solid #000; vertical-align: middle; padding: 2px 6px;">
                        <?php if ($status_sesi === 'Approved / Final'): ?>
                            <div style="display: flex; gap: 6px; justify-content: space-between; align-items: flex-end;">
                                <!-- Penyelia -->
                                <div style="flex: 1; text-align: center; border-right: 1px dashed #ccc; padding-right: 4px;">
                                    <div style="font-size: 6.2pt; color: #555; margin-bottom: 1px;">Penyelia:</div>
                                    <?php if (!empty($ttd_penyelia_img)): ?>
                                        <div style="height: 24px; display: flex; align-items: center; justify-content: center;">
                                            <img src="<?php echo $ttd_penyelia_img; ?>" style="max-height: 22px; max-width: 80px; object-fit: contain;" alt="TTD Penyelia">
                                        </div>
                                    <?php else: ?>
                                        <div style="height: 20px; display: flex; align-items: center; justify-content: center; color: #198754; font-size: 6.2pt; font-weight: bold;">[ VERIFIED ]</div>
                                    <?php endif; ?>
                                    <div style="font-size: 6.5pt; font-weight: bold; margin-top: 1px; color: #000; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?php echo html_escape($verifier['nama_lengkap'] ?? ($sesi['nama_verifier'] ?? 'Penyelia')); ?>">
                                        <?php echo html_escape($verifier['nama_lengkap'] ?? ($sesi['nama_verifier'] ?? 'Penyelia')); ?>
                                    </div>
                                </div>
                                <!-- Manajer Teknis -->
                                <div style="flex: 1; text-align: center; padding-left: 2px;">
                                    <div style="font-size: 6.2pt; color: #555; margin-bottom: 1px;">Manajer Teknis:</div>
                                    <?php if (!empty($ttd_approver_img)): ?>
                                        <div style="height: 24px; display: flex; align-items: center; justify-content: center;">
                                            <img src="<?php echo $ttd_approver_img; ?>" style="max-height: 22px; max-width: 80px; object-fit: contain;" alt="TTD MT">
                                        </div>
                                    <?php else: ?>
                                        <div style="height: 20px; display: flex; align-items: center; justify-content: center; color: #0d6efd; font-size: 6.2pt; font-weight: bold;">[ APPROVED ]</div>
                                    <?php endif; ?>
                                    <div style="font-size: 6.5pt; font-weight: bold; margin-top: 1px; color: #000; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?php echo html_escape($approver['nama_lengkap'] ?? ($sesi['nama_approver'] ?? 'Manajer Teknis')); ?>">
                                        <?php echo html_escape($approver['nama_lengkap'] ?? ($sesi['nama_approver'] ?? 'Manajer Teknis')); ?>
                                    </div>
                                </div>
                            </div>
                        <?php elseif ($status_sesi === 'Menunggu Approval'): ?>
                            <div style="text-align: center;">
                                <div style="font-size: 6.5pt; color: #555;">Penyelia:</div>
                                <?php if (!empty($ttd_penyelia_img)): ?>
                                    <div style="height: 24px; display: flex; align-items: center; justify-content: center;">
                                        <img src="<?php echo $ttd_penyelia_img; ?>" style="max-height: 22px; max-width: 120px; object-fit: contain;" alt="TTD Penyelia">
                                    </div>
                                <?php else: ?>
                                    <div style="height: 20px; display: flex; align-items: center; justify-content: center; color: #198754; font-size: 6.5pt; font-weight: bold;">
                                        [ VERIFIED DIGITAL ]
                                    </div>
                                <?php endif; ?>
                                <div style="font-size: 6.8pt; font-weight: bold; margin-top: 1px; color: #000;"><?php echo html_escape($verifier['nama_lengkap'] ?? ($sesi['nama_verifier'] ?? 'Penyelia')); ?></div>
                                <div style="font-size: 6.0pt; color: #888; font-style: italic;">(Menunggu Approval MT)</div>
                            </div>
                        <?php else: ?>
                            <div style="height: 22px; display: flex; align-items: center; justify-content: center; color: #888; font-size: 6.5pt; font-style: italic;">
                                (Menunggu Verifikasi Penyelia)
                            </div>
                            <div style="font-size: 6.8pt; color: #333; margin-top: 2px; text-align: center;">(TTD, Nama)</div>
                        <?php endif; ?>
                    </td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
                <tr style="height: 11.5pt;">
                    <td style="border-left: 1.5pt solid #000;"></td>
                    <td colspan="11" style="font-size: 7.0pt; vertical-align: middle; padding-left: 4px; white-space: nowrap;">
                        Tanggal : &nbsp;<span style="font-size: 6.8pt;"><?php echo !empty($sesi['waktu_selesai']) ? date('d-m-Y', strtotime($sesi['waktu_selesai'])) : date('d-m-Y'); ?></span>
                    </td>
                    <td colspan="7" style="border-left: 0.75pt solid #000; font-size: 7.0pt; vertical-align: middle; padding-left: 8px; white-space: nowrap;">
                        Tanggal : &nbsp;<span style="font-size: 6.8pt;"><?php echo ($status_sesi === 'Approved / Final' || $status_sesi === 'Menunggu Approval') && !empty($sesi['waktu_verifikasi']) ? date('d-m-Y', strtotime($sesi['waktu_verifikasi'])) : '-'; ?></span>
                    </td>
                    <td style="border-right: 1.5pt solid #000;"></td>
                </tr>
                <tr style="height: 2.5pt;">
                    <td style="border-left: 1.5pt solid #000; border-bottom: 1.5pt solid #000;"></td>
                    <td colspan="11" style="border-bottom: 1.5pt solid #000;"></td>
                    <td colspan="7" style="border-left: 0.75pt solid #000; border-bottom: 1.5pt solid #000;"></td>
                    <td style="border-right: 1.5pt solid #000; border-bottom: 1.5pt solid #000;"></td>
                </tr>
            </tbody>
        </table>

        <!-- Footer Halaman 2 (Row 95) -->
        <table class="excel-table" style="margin-top: 4px;">
        <colgroup>
            <col style="width: 2.009%;">
            <col style="width: 7.138%;">
            <col style="width: 5.077%;">
            <col style="width: 7.616%;">
            <col style="width: 2.909%;">
            <col style="width: 4.548%;">
            <col style="width: 7.827%;">
            <col style="width: 2.484%;">
            <col style="width: 5.236%;">
            <col style="width: 2.009%;">
            <col style="width: 5.236%;">
            <col style="width: 2.802%;">
            <col style="width: 9.043%;">
            <col style="width: 3.914%;">
            <col style="width: 9.043%;">
            <col style="width: 3.066%;">
            <col style="width: 7.245%;">
            <col style="width: 2.484%;">
            <col style="width: 7.563%;">
            <col style="width: 2.75%;">
        </colgroup>
            <tbody>
                <tr style="height: 9.8pt;">
                    <td style="border: none;"></td>
                    <td colspan="15" style="border: none; font-size: 6.5pt;"></td>
                    <td colspan="4" style="border: none; font-size: 6.5pt; text-align: right;">Lembar 1 dari 1 halaman</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>
