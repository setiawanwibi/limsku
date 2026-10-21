<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * View Cetak / Simpan PDF Lembar Catatan Pengujian (LCP)
 * ENDOTOKSIN BAKTERI SEMI KUANTITATIF
 * Replikasi presisi 100% dari file Excel Template Resmi:
 * POM-06.02_CFM.01_SOP.01_F.01B.19 Endotoksin Bakteri Semi Kuantitatif .xlsx
 * Format Dokumen: POM-06.02/CFM.01/SOP.01/F.01B.19 (3 Halaman A4)
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

// Sample Metadata
$nama_sampel = $sampel['nama_sampel'] ?? '-';
$kode_sampel = !empty($sampel['kode_sampel_manual']) ? $sampel['kode_sampel_manual'] : (!empty($sampel['no']) ? 'NO-' . $sampel['no'] : 'SMP-' . ($sampel['id'] ?? '-'));
$tgl_terima  = !empty($sampel['tanggal_sampling']) ? date('d-m-Y', strtotime($sampel['tanggal_sampling'])) : (!empty($sampel['created_at']) ? date('d-m-Y', strtotime($sampel['created_at'])) : '-');
$tgl_mulai   = !empty($sesi['waktu_mulai']) ? date('d-m-Y', strtotime($sesi['waktu_mulai'])) : date('d-m-Y');
$nama_penguji= $penguji['nama_lengkap'] ?? ($sesi['nama_penguji'] ?? '-');
$tgl_selesai = !empty($sesi['waktu_selesai']) ? date('d-m-Y', strtotime($sesi['waktu_selesai'])) : date('d-m-Y');
$tgl_verif   = !empty($sesi['waktu_verifikasi']) ? date('d-m-Y', strtotime($sesi['waktu_verifikasi'])) : '-';

// Auto-calculate PMA: (Batas Endotoksin * Konsentrasi) / Kepekaan Lambda
$batas_et = $p['batas_endotoksin'] ?? null;
$kons_smp = $p['konsentrasi_larutan_sampel'] ?? null;
$lambda   = $p['kepekaan_lambda'] ?? null;
$pma_calc = $p['nilai_pma'] ?? '';

if (($pma_calc === '' || $pma_calc === '-') && is_numeric($batas_et) && is_numeric($lambda) && (float)$lambda > 0) {
    $kons_val = is_numeric($kons_smp) ? (float)$kons_smp : 1.0;
    $pma_calc = round(((float)$batas_et * $kons_val) / (float)$lambda, 2);
}

// Auto-calculate Konversi Potensi Sampel (Hal 3) jika ada rata-rata kadar endotoksin (E) & potensi sampel
$kadar_e_val = $p['konversi_e_val'] ?? '';
$potensi_smp_val = $p['konversi_potensi_val'] ?? '';
$hasil_angka = $p['konversi_hasil_angka'] ?? '';
$hasil_kurang = $p['konversi_hasil_kurang'] ?? '';

if (($hasil_angka === '' || $hasil_angka === '###') && is_numeric($kadar_e_val) && is_numeric($potensi_smp_val) && (float)$potensi_smp_val > 0) {
    $hasil_angka = round((float)$kadar_e_val / (float)$potensi_smp_val, 4);
    if ($hasil_kurang === '' || $hasil_kurang === '###') {
        $hasil_kurang = $hasil_angka;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak LCP - ENDOTOKSIN BAKTERI SEMI KUANTITATIF - <?php echo html_escape($nama_sampel); ?></title>
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

        .document-wrapper {
            width: 210mm;
            min-height: 297mm;
            max-height: 297mm;
            margin: 0 auto 20px auto;
            padding: 6mm 8mm 4mm 8mm;
            background-color: #fff;
            box-shadow: 0 0 15px rgba(0,0,0,0.3);
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            page-break-after: always;
        }

        .document-wrapper:last-child {
            margin-bottom: 0;
            page-break-after: auto;
        }

        /* Outer Border Tunggal (Solid Border sesuai Kualitatif) */
        .outer-border-box {
            border: 1.5pt solid #000;
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
                page-break-after: always !important;
                page-break-inside: avoid !important;
            }
            .document-wrapper:last-child {
                page-break-after: auto !important;
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

    <!-- ========================================== -->
    <!-- HALAMAN 1 DARI 3: ALAT & REAGEN            -->
    <!-- ========================================== -->
    <div class="document-wrapper">
        <div class="outer-border-box">

            <!-- KOP HEADER HALAMAN 1 DENGAN LOGO BPOM -->
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
                        <div style="font-size: 10pt; font-weight: bold; margin-top: 1px; letter-spacing: 0.3px;">ENDOTOKSIN BAKTERI SEMI KUANTITATIF</div>
                        <div style="font-size: 8.5pt; font-weight: bold; margin-top: 2px;">Laboratorium Mikrobiologi Balai Besar/Balai/Loka*) POM di Bandar Lampung</div>
                    </td>
                </tr>
            </table>

            <div style="height: 3px;"></div>

            <!-- METADATA SAMPEL -->
            <table class="excel-table" style="border: 1pt solid #000;">
                <colgroup>
                    <col style="width: 38%;">
                    <col style="width: 28%;">
                    <col style="width: 17%;">
                    <col style="width: 2%;">
                    <col style="width: 15%;">
                </colgroup>
                <tr style="height: 13.5pt;">
                    <td class="b-right text-bold" style="padding-left: 4px;">Nama Sampel Uji :</td>
                    <td class="b-right text-bold" style="padding-left: 4px;">No. Kode Sampel Uji:</td>
                    <td style="padding-left: 4px; font-size: 7pt; white-space: nowrap;">Tanggal Diterima</td>
                    <td class="text-center" style="font-size: 7pt;">:</td>
                    <td style="padding-left: 2px; font-size: 7pt; white-space: nowrap;"><?php echo html_escape($tgl_terima); ?></td>
                </tr>
                <tr style="height: 13.5pt;">
                    <td rowspan="2" class="b-right text-bold" style="padding-left: 4px; vertical-align: top;"><?php echo html_escape($nama_sampel); ?></td>
                    <td rowspan="2" class="b-right text-bold" style="padding-left: 4px; vertical-align: top;"><?php echo html_escape($kode_sampel); ?></td>
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

            <div style="height: 8px;"></div>

            <!-- METODE & ALAT -->
            <table class="excel-table">
                <colgroup>
                    <col style="width: 45%;">
                    <col style="width: 2%;">
                    <col style="width: 53%;">
                </colgroup>
                <tr style="height: 14pt;">
                    <td class="text-bold">Metode</td>
                    <td class="text-center">:</td>
                    <td class="text-bold"><?php echo html_escape($p['metode_uji'] ?? 'Jendal Gel'); ?></td>
                </tr>
                <tr style="height: 14pt;">
                    <td colspan="3" class="text-bold" style="padding-top: 4px;">Alat</td>
                </tr>
                <tr style="height: 13pt;">
                    <td style="padding-left: 8px;">No. Identifikasi/Tgl Kalibrasi Mikropipet 1</td>
                    <td class="text-center">:</td>
                    <td><?php echo html_escape($p['mikropipet_1'] ?? '-'); ?></td>
                </tr>
                <tr style="height: 13pt;">
                    <td style="padding-left: 8px;">No. Identifikasi/Tgl Kalibrasi Mikropipet 2</td>
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

            <div style="height: 8px;"></div>

            <!-- PEREAKSI -->
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
                    <td><?php echo html_escape($p['nama_reagen'] ?? 'Limulus Amebocyte Lysate (LAL)'); ?></td>
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
                    <td><?php echo html_escape($p['potensi_cse'] ?? '-'); ?> EU/mL &nbsp;/&nbsp; <?php echo html_escape($p['kepekaan_lambda'] ?? '-'); ?> EU/mL</td>
                </tr>
                <tr style="height: 13pt;">
                    <td style="padding-left: 8px;">Tgl Uji Konfirmasi CSE/LAL-TAL*) (λ)</td>
                    <td class="text-center">:</td>
                    <td><?php echo !empty($p['tgl_uji_konfirmasi']) ? date('d-m-Y', strtotime($p['tgl_uji_konfirmasi'])) : '-'; ?></td>
                </tr>
                <tr style="height: 13pt;">
                    <td style="padding-left: 8px;">Tgl Rekonstitusi CSE/LAL-TAL*) (λ)</td>
                    <td class="text-center">:</td>
                    <td><?php echo !empty($p['tgl_rekonstitusi']) ? date('d-m-Y', strtotime($p['tgl_rekonstitusi'])) : '-'; ?></td>
                </tr>
                <tr style="height: 13pt;">
                    <td style="padding-left: 8px;">Suhu Penyimpanan reagen</td>
                    <td class="text-center">:</td>
                    <td>CSE : &nbsp;<?php echo html_escape($p['suhu_simpan_cse'] ?? '2 - 8'); ?> °C &nbsp;&nbsp;&nbsp;&nbsp; LAL/TAL *) : &nbsp;<?php echo html_escape($p['suhu_simpan_lal'] ?? '2 - 8'); ?> °C</td>
                </tr>
                <tr style="height: 13pt;">
                    <td style="padding-left: 8px;">Suhu Penyimpanan reagen (rekonstitusi)</td>
                    <td class="text-center">:</td>
                    <td>CSE : &nbsp;<?php echo html_escape($p['suhu_simpan_cse'] ?? '2 - 8'); ?> °C &nbsp;&nbsp;&nbsp;&nbsp; LAL/TAL *) : &nbsp;<?php echo html_escape($p['suhu_simpan_lal'] ?? '2 - 8'); ?> °C</td>
                </tr>
            </table>

            <div style="font-size: 6.8pt; margin-top: 10px;">
                Keterangan : *) Coret yang tidak perlu
            </div>

            <!-- TTD HALAMAN 1 -->
            <table class="excel-table" style="border-top: 1pt solid #000; border-bottom: 1pt solid #000; margin-top: auto;">
                <colgroup>
                    <col style="width: 50%;">
                    <col style="width: 50%;">
                </colgroup>
                <tr style="height: 13pt;">
                    <td class="b-right text-bold" style="padding-left: 4px;">Tanda Tangan Penguji :</td>
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
                        <?php endif; ?>
                    </td>
                </tr>
                <tr style="height: 13pt;">
                    <td class="b-right" style="padding-left: 4px;">Tanggal : &nbsp;<?php echo html_escape($tgl_selesai); ?></td>
                    <td style="padding-left: 10px;">Tanggal : &nbsp;<?php echo ($status_sesi === 'Approved / Final' || $status_sesi === 'Menunggu Approval') ? html_escape($tgl_verif) : '-'; ?></td>
                </tr>
            </table>

        </div><!-- /.outer-border-box -->

        <!-- FOOTER HALAMAN 1 -->
        <table class="excel-table" style="margin-top: 3px;">
            <colgroup>
                <col style="width: 70%;">
                <col style="width: 30%;">
            </colgroup>
            <tr style="height: 12pt;">
                <td style="font-size: 7pt; font-weight: bold; padding-left: 2px;">POM-06.02/CFM.01/SOP.01/F.01B.19</td>
                <td class="text-right" style="font-size: 7pt; padding-right: 2px;">Lembar 1 dari 3 halaman</td>
            </tr>
        </table>
    </div>

    <!-- ========================================== -->
    <!-- HALAMAN 2 DARI 3: PREPARASI & PENGENCERAN  -->
    <!-- ========================================== -->
    <div class="document-wrapper">
        <div class="outer-border-box">

            <!-- KOP HEADER HALAMAN 2 DENGAN LOGO BPOM -->
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
                        <div style="font-size: 10pt; font-weight: bold; margin-top: 1px; letter-spacing: 0.3px;">ENDOTOKSIN BAKTERI SEMI KUANTITATIF</div>
                        <div style="font-size: 8.5pt; font-weight: bold; margin-top: 2px;">Laboratorium Mikrobiologi Balai Besar/Balai/Loka*) POM di Bandar Lampung</div>
                    </td>
                </tr>
            </table>

            <div style="height: 3px;"></div>

            <!-- METADATA SAMPEL HALAMAN 2 -->
            <table class="excel-table" style="border: 1pt solid #000;">
                <colgroup>
                    <col style="width: 38%;">
                    <col style="width: 28%;">
                    <col style="width: 17%;">
                    <col style="width: 2%;">
                    <col style="width: 15%;">
                </colgroup>
                <tr style="height: 13.5pt;">
                    <td class="b-right text-bold" style="padding-left: 4px;">Nama Sampel Uji :</td>
                    <td class="b-right text-bold" style="padding-left: 4px;">No. Kode Sampel Uji:</td>
                    <td style="padding-left: 4px; font-size: 7pt; white-space: nowrap;">Tanggal Diterima</td>
                    <td class="text-center" style="font-size: 7pt;">:</td>
                    <td style="padding-left: 2px; font-size: 7pt; white-space: nowrap;"><?php echo html_escape($tgl_terima); ?></td>
                </tr>
                <tr style="height: 13.5pt;">
                    <td rowspan="2" class="b-right text-bold" style="padding-left: 4px; vertical-align: top;"><?php echo html_escape($nama_sampel); ?></td>
                    <td rowspan="2" class="b-right text-bold" style="padding-left: 4px; vertical-align: top;"><?php echo html_escape($kode_sampel); ?></td>
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

            <div style="height: 6px;"></div>

            <!-- PROSEDUR METODE JENDAL -->
            <table class="excel-table">
                <colgroup>
                    <col style="width: 30%;">
                    <col style="width: 2%;">
                    <col style="width: 68%;">
                </colgroup>
                <tr style="height: 14pt;">
                    <td colspan="3" class="text-bold">Prosedur Metode Jendal</td>
                </tr>
                <tr style="height: 13pt;">
                    <td>Pemerian Contoh</td>
                    <td class="text-center">:</td>
                    <td><?php echo html_escape($p['pemerian_contoh'] ?? '-'); ?></td>
                </tr>
                <tr style="height: 13pt;">
                    <td>pH Contoh</td>
                    <td class="text-center">:</td>
                    <td><?php echo html_escape($p['ph_contoh'] ?? '-'); ?></td>
                </tr>
                <tr style="height: 13pt;">
                    <td>Potensi CSE</td>
                    <td class="text-center">:</td>
                    <td><?php echo html_escape($p['potensi_cse'] ?? '-'); ?> EU/mL dalam LRW</td>
                </tr>
                <tr style="height: 13pt;">
                    <td>Kepekaan LAL/TAL*) (λ)</td>
                    <td class="text-center">:</td>
                    <td><?php echo html_escape($p['kepekaan_lambda'] ?? '-'); ?> EU/mL</td>
                </tr>
            </table>

            <div style="height: 4px;"></div>

            <!-- PERHITUNGAN PMA -->
            <div style="border: 1pt solid #000; padding: 4px 6px; margin: 4px 0;">
                <table class="excel-table">
                    <colgroup>
                        <col style="width: 15%;">
                        <col style="width: 85%;">
                    </colgroup>
                    <tr>
                        <td class="text-bold" style="vertical-align: top;">Perhitungan PMA :</td>
                        <td>
                            <div style="font-size: 7pt;">
                                PMA = &nbsp;<u>Batas Endotoksin &times; Konsentrasi Larutan Sampel</u><br>
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Kepekaan pereaksi (&lambda;)
                            </div>
                            <div style="margin-top: 3px; font-size: 7.2pt;">
                                = &nbsp;<u><?php echo html_escape($p['batas_endotoksin'] ?? '0.2'); ?> <?php echo html_escape($p['satuan_batas_endotoksin'] ?? 'EU/mL'); ?> &times; <?php echo html_escape($p['konsentrasi_larutan_sampel'] ?? '1'); ?></u> &nbsp;=&nbsp; <strong><?php echo html_escape($pma_calc !== '' ? $pma_calc : '-'); ?></strong>
                            </div>
                            <small class="text-muted" style="font-size: 6.5pt;">*(jika satuan batas endotoksin adalah EU/mL maka konsentrasi ditulis 1)</small>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- SKEMA PENGENCERAN SAMPEL & KSP -->
            <div style="border: 1pt solid #000; padding: 4px 6px; margin: 4px 0; font-size: 6.8pt;">
                <strong>Pengenceran Sampel :</strong><br>
                <?php echo !empty($p['skema_pengenceran_sampel']) ? nl2br(html_escape($p['skema_pengenceran_sampel'])) : 'Neat &nbsp;&rarr;&nbsp; 1:2 &nbsp;&rarr;&nbsp; 1:4 &nbsp;&rarr;&nbsp; 1:8 &nbsp;&rarr;&nbsp; 1:16 (sesuai nilai PMA)'; ?>
                <div style="height: 4px;"></div>
                <strong>Pengenceran KSP (Kontrol Sampel Positif) :</strong><br>
                <?php echo !empty($p['skema_pengenceran_ksp']) ? nl2br(html_escape($p['skema_pengenceran_ksp'])) : '@ 0,9 mL Sampel (sesuai tingkat pengenceran) + @ 0,1 mL 20&lambda;'; ?>
            </div>

            <div style="margin-top: auto;"></div>

        </div><!-- /.outer-border-box -->

        <!-- FOOTER HALAMAN 2 -->
        <table class="excel-table" style="margin-top: 3px;">
            <colgroup>
                <col style="width: 70%;">
                <col style="width: 30%;">
            </colgroup>
            <tr style="height: 12pt;">
                <td style="font-size: 7pt; font-weight: bold; padding-left: 2px;">POM-06.02/CFM.01/SOP.01/F.01B.19</td>
                <td class="text-right" style="font-size: 7pt; padding-right: 2px;">Lembar 2 dari 3 halaman</td>
            </tr>
        </table>
    </div>

    <!-- ========================================== -->
    <!-- HALAMAN 3 DARI 3: PENGAMATAN & KESIMPULAN  -->
    <!-- ========================================== -->
    <div class="document-wrapper">
        <div class="outer-border-box">

            <!-- KOP HEADER HALAMAN 3 DENGAN LOGO BPOM -->
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
                        <div style="font-size: 10pt; font-weight: bold; margin-top: 1px; letter-spacing: 0.3px;">ENDOTOKSIN BAKTERI SEMI KUANTITATIF</div>
                        <div style="font-size: 8.5pt; font-weight: bold; margin-top: 2px;">Laboratorium Mikrobiologi Balai Besar/Balai/Loka*) POM di Bandar Lampung</div>
                    </td>
                </tr>
            </table>

            <div style="height: 3px;"></div>

            <!-- METADATA SAMPEL HALAMAN 3 -->
            <table class="excel-table" style="border: 1pt solid #000; margin-bottom: 4px;">
                <colgroup>
                    <col style="width: 38%;">
                    <col style="width: 28%;">
                    <col style="width: 17%;">
                    <col style="width: 2%;">
                    <col style="width: 15%;">
                </colgroup>
                <tr style="height: 13.5pt;">
                    <td class="b-right text-bold" style="padding-left: 4px;">Nama Sampel Uji :</td>
                    <td class="b-right text-bold" style="padding-left: 4px;">No. Kode Sampel Uji:</td>
                    <td style="padding-left: 4px; font-size: 7pt; white-space: nowrap;">Tanggal Diterima</td>
                    <td class="text-center" style="font-size: 7pt;">:</td>
                    <td style="padding-left: 2px; font-size: 7pt; white-space: nowrap;"><?php echo html_escape($tgl_terima); ?></td>
                </tr>
                <tr style="height: 13.5pt;">
                    <td rowspan="2" class="b-right text-bold" style="padding-left: 4px; vertical-align: top;"><?php echo html_escape($nama_sampel); ?></td>
                    <td rowspan="2" class="b-right text-bold" style="padding-left: 4px; vertical-align: top;"><?php echo html_escape($kode_sampel); ?></td>
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

            <!-- KONDISI INKUBASI HALAMAN 3 -->
            <table class="excel-table" style="border: 1pt solid #000; margin-bottom: 4px;">
                <colgroup>
                    <col style="width: 25%;">
                    <col style="width: 2%;">
                    <col style="width: 23%;">
                    <col style="width: 25%;">
                    <col style="width: 2%;">
                    <col style="width: 23%;">
                </colgroup>
                <tr style="height: 13.5pt;">
                    <td style="padding-left: 4px; font-weight: bold;">Waktu Inkubasi</td>
                    <td class="text-center">:</td>
                    <td><?php echo html_escape($p['waktu_inkubasi'] ?? '60 ± 2'); ?> Menit</td>
                    <td style="padding-left: 4px; font-weight: bold;">Suhu Inkubator</td>
                    <td class="text-center">:</td>
                    <td><?php echo html_escape($p['suhu_inkubator'] ?? '37 ± 1'); ?> °C</td>
                </tr>
            </table>

            <!-- 4 TABEL PENGAMATAN SESUAI FORMAT RESMI EXCEL -->
            <div style="font-weight: bold; margin-bottom: 3px; font-size: 7.2pt;">Pengamatan Contoh :</div>

            <!-- TABEL 1: LRW/WFI & KONTROL POSITIF -->
            <table class="excel-table" style="border: 1pt solid #000; text-align: center; margin-bottom: 6px;">
                <colgroup>
                    <col style="width: 5%;">
                    <col style="width: 15%;">
                    <col style="width: 20%;">
                    <col style="width: 20%;">
                    <col style="width: 20%;">
                    <col style="width: 20%;">
                </colgroup>
                <thead>
                    <tr style="height: 12.5pt; background-color: #d9d9d9;">
                        <th rowspan="2" class="b-all">f</th>
                        <th rowspan="2" class="b-all">LRW/WFI</th>
                        <th colspan="4" class="b-all">Kontrol Positif</th>
                    </tr>
                    <tr style="height: 12pt; background-color: #d9d9d9;">
                        <th class="b-all">2&lambda;</th>
                        <th class="b-all">&lambda;</th>
                        <th class="b-all">1/2&lambda;</th>
                        <th class="b-all">1/4&lambda;</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="height: 13pt;">
                        <td class="b-all">1</td>
                        <td class="b-all"><?php echo html_escape($p['hasil_lrw_1'] ?? ''); ?></td>
                        <td class="b-all"><?php echo html_escape($p['hasil_kp_2lambda_1'] ?? ''); ?></td>
                        <td class="b-all"><?php echo html_escape($p['hasil_kp_lambda_1'] ?? ''); ?></td>
                        <td class="b-all"><?php echo html_escape($p['hasil_kp_setengah_1'] ?? ''); ?></td>
                        <td class="b-all"><?php echo html_escape($p['hasil_kp_seperempat_1'] ?? ''); ?></td>
                    </tr>
                    <tr style="height: 13pt;">
                        <td class="b-all">2</td>
                        <td class="b-all"><?php echo html_escape($p['hasil_lrw_2'] ?? ''); ?></td>
                        <td class="b-all"><?php echo html_escape($p['hasil_kp_2lambda_2'] ?? ''); ?></td>
                        <td class="b-all"><?php echo html_escape($p['hasil_kp_lambda_2'] ?? ''); ?></td>
                        <td class="b-all"><?php echo html_escape($p['hasil_kp_setengah_2'] ?? ''); ?></td>
                        <td class="b-all"><?php echo html_escape($p['hasil_kp_seperempat_2'] ?? ''); ?></td>
                    </tr>
                </tbody>
            </table>

            <!-- TABEL 2: KONTROL SAMPEL POSITIF (KSP) -->
            <table class="excel-table" style="border: 1pt solid #000; text-align: center; margin-bottom: 6px;">
                <colgroup>
                    <col style="width: 5%;">
                    <col style="width: 9.5%;">
                    <col style="width: 8.55%;">
                    <col style="width: 8.55%;">
                    <col style="width: 8.55%;">
                    <col style="width: 8.55%;">
                    <col style="width: 8.55%;">
                    <col style="width: 8.55%;">
                    <col style="width: 8.55%;">
                    <col style="width: 8.55%;">
                    <col style="width: 8.55%;">
                    <col style="width: 8.55%;">
                </colgroup>
                <thead>
                    <tr style="height: 12.5pt; background-color: #d9d9d9;">
                        <th rowspan="2" class="b-all">f</th>
                        <th colspan="11" class="b-all">Kontrol Sampel Positif</th>
                    </tr>
                    <tr style="height: 12pt; background-color: #f2f2f2; font-size: 6.8pt;">
                        <th class="b-all">Neat</th>
                        <th class="b-all">x:x</th>
                        <th class="b-all">x:x</th>
                        <th class="b-all">x:x</th>
                        <th class="b-all">x:x</th>
                        <th class="b-all">x:x</th>
                        <th class="b-all">x:x</th>
                        <th class="b-all">x:x</th>
                        <th class="b-all">x:x</th>
                        <th class="b-all">x:x</th>
                        <th class="b-all">x:x</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="height: 13pt;">
                        <td class="b-all">1</td>
                        <td class="b-all"><?php echo html_escape($p['ksp_f1_neat'] ?? ''); ?></td>
                        <td class="b-all"><?php echo html_escape($p['ksp_f1_c1'] ?? ''); ?></td>
                        <td class="b-all"><?php echo html_escape($p['ksp_f1_c2'] ?? ''); ?></td>
                        <td class="b-all"><?php echo html_escape($p['ksp_f1_c3'] ?? ''); ?></td>
                        <td class="b-all"><?php echo html_escape($p['ksp_f1_c4'] ?? ''); ?></td>
                        <td class="b-all"><?php echo html_escape($p['ksp_f1_c5'] ?? ''); ?></td>
                        <td class="b-all"><?php echo html_escape($p['ksp_f1_c6'] ?? ''); ?></td>
                        <td class="b-all"><?php echo html_escape($p['ksp_f1_c7'] ?? ''); ?></td>
                        <td class="b-all"><?php echo html_escape($p['ksp_f1_c8'] ?? ''); ?></td>
                        <td class="b-all"><?php echo html_escape($p['ksp_f1_c9'] ?? ''); ?></td>
                        <td class="b-all"><?php echo html_escape($p['ksp_f1_c10'] ?? ''); ?></td>
                    </tr>
                    <tr style="height: 13pt;">
                        <td class="b-all">2</td>
                        <td class="b-all"><?php echo html_escape($p['ksp_f2_neat'] ?? ''); ?></td>
                        <td class="b-all"><?php echo html_escape($p['ksp_f2_c1'] ?? ''); ?></td>
                        <td class="b-all"><?php echo html_escape($p['ksp_f2_c2'] ?? ''); ?></td>
                        <td class="b-all"><?php echo html_escape($p['ksp_f2_c3'] ?? ''); ?></td>
                        <td class="b-all"><?php echo html_escape($p['ksp_f2_c4'] ?? ''); ?></td>
                        <td class="b-all"><?php echo html_escape($p['ksp_f2_c5'] ?? ''); ?></td>
                        <td class="b-all"><?php echo html_escape($p['ksp_f2_c6'] ?? ''); ?></td>
                        <td class="b-all"><?php echo html_escape($p['ksp_f2_c7'] ?? ''); ?></td>
                        <td class="b-all"><?php echo html_escape($p['ksp_f2_c8'] ?? ''); ?></td>
                        <td class="b-all"><?php echo html_escape($p['ksp_f2_c9'] ?? ''); ?></td>
                        <td class="b-all"><?php echo html_escape($p['ksp_f2_c10'] ?? ''); ?></td>
                    </tr>
                </tbody>
            </table>

            <!-- TABEL 3: SAMPEL -->
            <table class="excel-table" style="border: 1pt solid #000; text-align: center; margin-bottom: 6px;">
                <colgroup>
                    <col style="width: 5%;">
                    <col style="width: 9.5%;">
                    <col style="width: 8.55%;">
                    <col style="width: 8.55%;">
                    <col style="width: 8.55%;">
                    <col style="width: 8.55%;">
                    <col style="width: 8.55%;">
                    <col style="width: 8.55%;">
                    <col style="width: 8.55%;">
                    <col style="width: 8.55%;">
                    <col style="width: 8.55%;">
                    <col style="width: 8.55%;">
                </colgroup>
                <thead>
                    <tr style="height: 12.5pt; background-color: #d9d9d9;">
                        <th rowspan="2" class="b-all">f</th>
                        <th colspan="11" class="b-all">Sampel</th>
                    </tr>
                    <tr style="height: 12pt; background-color: #f2f2f2; font-size: 6.8pt;">
                        <th class="b-all">Neat</th>
                        <th class="b-all">x:x</th>
                        <th class="b-all">x:x</th>
                        <th class="b-all">x:x</th>
                        <th class="b-all">x:x</th>
                        <th class="b-all">x:x</th>
                        <th class="b-all">x:x</th>
                        <th class="b-all">x:x</th>
                        <th class="b-all">x:x</th>
                        <th class="b-all">x:x</th>
                        <th class="b-all">x:x</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="height: 13pt;">
                        <td class="b-all">1</td>
                        <td class="b-all"><?php echo html_escape($p['smp_f1_neat'] ?? ''); ?></td>
                        <td class="b-all"><?php echo html_escape($p['smp_f1_c1'] ?? ''); ?></td>
                        <td class="b-all"><?php echo html_escape($p['smp_f1_c2'] ?? ''); ?></td>
                        <td class="b-all"><?php echo html_escape($p['smp_f1_c3'] ?? ''); ?></td>
                        <td class="b-all"><?php echo html_escape($p['smp_f1_c4'] ?? ''); ?></td>
                        <td class="b-all"><?php echo html_escape($p['smp_f1_c5'] ?? ''); ?></td>
                        <td class="b-all"><?php echo html_escape($p['smp_f1_c6'] ?? ''); ?></td>
                        <td class="b-all"><?php echo html_escape($p['smp_f1_c7'] ?? ''); ?></td>
                        <td class="b-all"><?php echo html_escape($p['smp_f1_c8'] ?? ''); ?></td>
                        <td class="b-all"><?php echo html_escape($p['smp_f1_c9'] ?? ''); ?></td>
                        <td class="b-all"><?php echo html_escape($p['smp_f1_c10'] ?? ''); ?></td>
                    </tr>
                    <tr style="height: 13pt;">
                        <td class="b-all">2</td>
                        <td class="b-all"><?php echo html_escape($p['smp_f2_neat'] ?? ''); ?></td>
                        <td class="b-all"><?php echo html_escape($p['smp_f2_c1'] ?? ''); ?></td>
                        <td class="b-all"><?php echo html_escape($p['smp_f2_c2'] ?? ''); ?></td>
                        <td class="b-all"><?php echo html_escape($p['smp_f2_c3'] ?? ''); ?></td>
                        <td class="b-all"><?php echo html_escape($p['smp_f2_c4'] ?? ''); ?></td>
                        <td class="b-all"><?php echo html_escape($p['smp_f2_c5'] ?? ''); ?></td>
                        <td class="b-all"><?php echo html_escape($p['smp_f2_c6'] ?? ''); ?></td>
                        <td class="b-all"><?php echo html_escape($p['smp_f2_c7'] ?? ''); ?></td>
                        <td class="b-all"><?php echo html_escape($p['smp_f2_c8'] ?? ''); ?></td>
                        <td class="b-all"><?php echo html_escape($p['smp_f2_c9'] ?? ''); ?></td>
                        <td class="b-all"><?php echo html_escape($p['smp_f2_c10'] ?? ''); ?></td>
                    </tr>
                </tbody>
            </table>

            <!-- TABEL 4: KADAR ENDOTOKSIN (E) & RATA-RATA -->
            <div style="width: 32%; margin-bottom: 6px;">
                <table class="excel-table" style="border: 1pt solid #000; text-align: center;">
                    <colgroup>
                        <col style="width: 35%;">
                        <col style="width: 65%;">
                    </colgroup>
                    <thead>
                        <tr style="height: 12.5pt; background-color: #d9d9d9;">
                            <th colspan="2" class="b-all">Kadar Endotoksin (E)<br>(EU/ml)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr style="height: 13pt;">
                            <td colspan="2" class="b-all"><?php echo html_escape($p['kadar_endotoksin_f1'] ?? ''); ?></td>
                        </tr>
                        <tr style="height: 13pt;">
                            <td colspan="2" class="b-all"><?php echo html_escape($p['kadar_endotoksin_f2'] ?? ''); ?></td>
                        </tr>
                        <tr style="height: 14pt;">
                            <td class="b-all text-bold" style="background-color: #f9f9f9; font-size: 6.8pt;">Rata-<br>Rata</td>
                            <td class="b-all text-bold"><?php echo html_escape($p['kadar_endotoksin_rata'] ?? ''); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- KETERANGAN RUMUS DI BAWAH TABEL 4 -->
            <table class="excel-table" style="margin-bottom: 4px;">
                <colgroup>
                    <col style="width: 12%;">
                    <col style="width: 2%;">
                    <col style="width: 25%;">
                    <col style="width: 2%;">
                    <col style="width: 59%;">
                </colgroup>
                <tr style="height: 10.5pt;">
                    <td class="text-bold" style="font-size: 6.8pt;">Keterangan</td>
                    <td class="text-center">:</td>
                    <td>f</td>
                    <td class="text-center">=</td>
                    <td>jumlah pengulangan</td>
                </tr>
                <tr style="height: 10.5pt;">
                    <td></td>
                    <td></td>
                    <td>Konsentrasi Endotoksin</td>
                    <td class="text-center">=</td>
                    <td>faktor pengenceran titik akhir dikali dengan &lambda;</td>
                </tr>
            </table>

            <!-- KADAR ENDOTOKSIN SAMPEL UJI & KONVERSI -->
            <div style="border-top: 1pt solid #000; padding: 4px 2px 2px 2px;">
                <table class="excel-table">
                    <colgroup>
                        <col style="width: 28%;">
                        <col style="width: 2%;">
                        <col style="width: 15%;">
                        <col style="width: 55%;">
                    </colgroup>
                    <tr style="height: 13pt;">
                        <td class="text-bold">Kadar endotoksin sampel uji :</td>
                        <td class="text-center">&lt;</td>
                        <td class="text-bold"><?php echo html_escape($p['kadar_endotoksin_eu_ml'] ?? '..........'); ?></td>
                        <td>EU/mL</td>
                    </tr>
                </table>
            </div>

            <!-- KONVERSI POTENSI SAMPEL JIKA BUKAN EU/ML -->
            <div style="font-size: 6.8pt; padding: 2px 2px;">
                Jika batas endotoksin memiliki satuan selain EU/mL maka konsentrasi Endotoksin sampel uji dikonversi sebagai berikut:
            </div>

            <table class="excel-table" style="margin: 2px 0 4px 0;">
                <colgroup>
                    <col style="width: 12%;">
                    <col style="width: 2%;">
                    <col style="width: 16%;">
                    <col style="width: 2%;">
                    <col style="width: 20%;">
                    <col style="width: 48%;">
                </colgroup>
                <tr style="height: 12pt;">
                    <td>Perhitungan</td>
                    <td class="text-center">:</td>
                    <td>Kadar Endotoksin</td>
                    <td class="text-center">=</td>
                    <td style="border-bottom: 1pt solid #000; text-align: center;">E</td>
                    <td></td>
                </tr>
                <tr style="height: 12pt;">
                    <td colspan="4"></td>
                    <td style="text-align: center;">Potensi Sampel</td>
                    <td></td>
                </tr>
                <tr style="height: 12pt;">
                    <td colspan="3"></td>
                    <td class="text-center">=</td>
                    <td style="text-align: center; border-bottom: 1pt solid #000;"><?php echo html_escape($p['konversi_e_val'] ?? '###'); ?> EU/mL</td>
                    <td></td>
                </tr>
                <tr style="height: 12pt;">
                    <td colspan="4"></td>
                    <td style="text-align: center;"><?php echo html_escape($p['konversi_potensi_val'] ?? '0'); ?> &nbsp;<?php echo html_escape($p['satuan_potensi_sampel'] ?? '.......'); ?></td>
                    <td></td>
                </tr>
                <tr style="height: 12pt;">
                    <td colspan="3"></td>
                    <td class="text-center">=</td>
                    <td class="text-bold" style="text-align: center;"><?php echo html_escape($hasil_angka !== '' ? $hasil_angka : ($p['konversi_hasil_angka'] ?? '###')); ?></td>
                    <td><?php echo html_escape($p['konversi_hasil_satuan'] ?? '........'); ?></td>
                </tr>
                <tr style="height: 12pt;">
                    <td colspan="3"></td>
                    <td class="text-center">&lt;</td>
                    <td class="text-bold" style="text-align: center;"><?php echo html_escape($hasil_kurang !== '' ? $hasil_kurang : ($p['konversi_hasil_kurang'] ?? '###')); ?></td>
                    <td><?php echo html_escape($p['konversi_hasil_satuan'] ?? '........'); ?></td>
                </tr>
            </table>

            <!-- PUSTAKA & SYARAT -->
            <div style="border-top: 1pt solid #000; padding: 4px 2px; margin-top: 3px;">
                <table class="excel-table">
                    <colgroup>
                        <col style="width: 18%;">
                        <col style="width: 2%;">
                        <col style="width: 35%;">
                        <col style="width: 45%;">
                    </colgroup>
                    <tr style="height: 13pt;">
                        <td class="text-bold">Pustaka / Metode</td>
                        <td class="text-center">:</td>
                        <td><?php echo html_escape($p['pustaka_metode'] ?? 'Farmakope Indonesia VI Tahun 2020'); ?></td>
                        <td><strong>Syarat :</strong> &nbsp;<?php echo html_escape($p['syarat_endotoksin'] ?? '< 0.2 EU/mL'); ?></td>
                    </tr>
                </table>
            </div>

            <!-- KESIMPULAN -->
            <div style="border-top: 1pt solid #000; border-bottom: 1pt solid #000; padding: 4px 2px;">
                <table class="excel-table">
                    <colgroup>
                        <col style="width: 14%;">
                        <col style="width: 86%;">
                    </colgroup>
                    <tr style="height: 13pt;">
                        <td class="text-bold">Kesimpulan &nbsp;:</td>
                        <td>
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

            <!-- TANDA TANGAN HALAMAN 3 -->
            <table class="excel-table" style="border-bottom: 1pt solid #000; margin-top: auto;">
                <colgroup>
                    <col style="width: 50%;">
                    <col style="width: 50%;">
                </colgroup>
                <tr style="height: 13pt;">
                    <td class="b-right text-bold" style="padding-left: 4px;">Tanda Tangan Penguji :</td>
                    <td class="text-bold" style="padding-left: 10px;">Diperiksa Oleh :</td>
                </tr>
                <tr style="height: 42pt;">
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
                        <?php endif; ?>
                    </td>
                </tr>
                <tr style="height: 13pt;">
                    <td class="b-right" style="padding-left: 4px;">Tanggal : &nbsp;<?php echo html_escape($tgl_selesai); ?></td>
                    <td style="padding-left: 10px;">Tanggal : &nbsp;<?php echo ($status_sesi === 'Approved / Final' || $status_sesi === 'Menunggu Approval') ? html_escape($tgl_verif) : '-'; ?></td>
                </tr>
            </table>

        </div><!-- /.outer-border-box -->

        <!-- FOOTER HALAMAN 3 -->
        <table class="excel-table" style="margin-top: 3px;">
            <colgroup>
                <col style="width: 70%;">
                <col style="width: 30%;">
            </colgroup>
            <tr style="height: 12pt;">
                <td style="font-size: 7pt; font-weight: bold; padding-left: 2px;">POM-06.02/CFM.01/SOP.01/F.01B.19</td>
                <td class="text-right" style="font-size: 7pt; padding-right: 2px;">Lembar 3 dari 3 halaman</td>
            </tr>
        </table>
    </div>

</body>
</html>
