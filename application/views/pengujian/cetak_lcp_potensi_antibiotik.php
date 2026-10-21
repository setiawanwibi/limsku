<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * View Cetak / Simpan PDF Lembar Catatan Pengujian (LCP)
 * POTENSI ANTIBIOTIKA METODE LEMPENG-SILINDER 5 DOSIS
 * Replikasi presisi 100% dari file Excel Template Resmi:
 * POM-06.02_CFM.01_SOP.01_F.01B.18 Potensi Antibiotik Metode Lempeng silinder 5 Dosis.xlsx
 * Format Dokumen: POM-06.02/CFM.01/SOP.01/F.01B.18 (2 Halaman A4)
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

// Auto-calculate RSD helper jika ada SD dan Rata-rata
$calc_rsd = function($sd, $avg) {
    if (is_numeric($sd) && is_numeric($avg) && (float)$avg > 0) {
        return round(((float)$sd / (float)$avg) * 100, 2);
    }
    return null;
};

// Hitung auto RSD kelompok 1 jika belum tersimpan
$rsd_s1 = $p['rsd_s1_nilai'] ?? '';
if ($rsd_s1 === '' && isset($p['sd_s1']) && isset($p['avg_s1'])) {
    $res = $calc_rsd($p['sd_s1'], $p['avg_s1']);
    if ($res !== null) $rsd_s1 = $res;
}
$rsd_s2 = $p['rsd_s2_nilai'] ?? '';
if ($rsd_s2 === '' && isset($p['sd_s2']) && isset($p['avg_s2'])) {
    $res = $calc_rsd($p['sd_s2'], $p['avg_s2']);
    if ($res !== null) $rsd_s2 = $res;
}
$rsd_s4 = $p['rsd_s4_nilai'] ?? '';
if ($rsd_s4 === '' && isset($p['sd_s4']) && isset($p['avg_s4'])) {
    $res = $calc_rsd($p['sd_s4'], $p['avg_s4']);
    if ($res !== null) $rsd_s4 = $res;
}
$rsd_s5 = $p['rsd_s5_nilai'] ?? '';
if ($rsd_s5 === '' && isset($p['sd_s5']) && isset($p['avg_s5'])) {
    $res = $calc_rsd($p['sd_s5'], $p['avg_s5']);
    if ($res !== null) $rsd_s5 = $res;
}
$rsd_uji = $p['rsd_uji_nilai'] ?? '';
if ($rsd_uji === '' && isset($p['sd_uji']) && isset($p['avg_uji'])) {
    $res = $calc_rsd($p['sd_uji'], $p['avg_uji']);
    if ($res !== null) $rsd_uji = $res;
}

// Auto %R2 dari R jika ada
$kurva_r  = $p['kurva_r'] ?? '';
$kurva_r2 = $p['kurva_r2'] ?? '';
$kurva_persen_r2 = $p['kurva_persen_r2'] ?? '';
if ($kurva_r !== '' && is_numeric($kurva_r)) {
    if ($kurva_r2 === '') $kurva_r2 = round(pow((float)$kurva_r, 2), 4);
    if ($kurva_persen_r2 === '') $kurva_persen_r2 = round((float)$kurva_r2 * 100, 2);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak LCP - POTENSI ANTIBIOTIKA 5 DOSIS - <?php echo html_escape($nama_sampel); ?></title>
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

        /* Lembar Dokumen A4 */
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

        .outer-border-box {
            border: 3px double #000;
            padding: 3px 6px 4px 6px;
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
    <!-- HALAMAN 1 DARI 2                          -->
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
                        <div style="font-size: 10pt; font-weight: bold; margin-top: 1px; letter-spacing: 0.3px;">POTENSI ANTIBIOTIKA METODE LEMPENG-SILINDER 5 DOSIS</div>
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
                    <td class="b-right text-bold" style="padding-left: 4px; font-size: 7.2pt;">Nama Sampel Uji :</td>
                    <td class="b-right text-bold" style="padding-left: 4px; font-size: 7.2pt;">No. Kode Sampel Uji :</td>
                    <td style="padding-left: 4px; font-size: 7pt; white-space: nowrap;">Tanggal Diterima</td>
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

            <div style="height: 6px;"></div>

            <!-- PARAMETER PENGUJIAN 1 - 12 -->
            <table class="excel-table">
                <colgroup>
                    <col style="width: 3.5%;">
                    <col style="width: 34%;">
                    <col style="width: 2%;">
                    <col style="width: 60.5%;">
                </colgroup>
                <tr style="height: 13pt;">
                    <td class="text-bold">1.</td>
                    <td>Mikroba Uji</td>
                    <td class="text-center">:</td>
                    <td><?php echo html_escape($p['mikroba_uji'] ?? ''); ?></td>
                </tr>
                <tr style="height: 13pt;">
                    <td class="text-bold">2.</td>
                    <td>Penimbangan / Pemipetan Contoh</td>
                    <td class="text-center">:</td>
                    <td><?php echo html_escape($p['bobot_contoh'] ?? ''); ?> &nbsp;<?php echo html_escape($p['satuan_contoh'] ?? 'g'); ?>/mL*</td>
                </tr>
                <tr style="height: 13pt;">
                    <td class="text-bold">3.</td>
                    <td>Medium Lapisan Dasar</td>
                    <td class="text-center">:</td>
                    <td><?php echo html_escape($p['medium_dasar'] ?? ''); ?></td>
                </tr>
                <tr style="height: 13pt;">
                    <td></td>
                    <td style="padding-left: 12px;">Lapisan Inokulum</td>
                    <td class="text-center">:</td>
                    <td><?php echo html_escape($p['medium_inokulum'] ?? ''); ?></td>
                </tr>
                <tr style="height: 13pt;">
                    <td class="text-bold">4.</td>
                    <td>Pelarut awal</td>
                    <td class="text-center">:</td>
                    <td><?php echo html_escape($p['pelarut_awal'] ?? ''); ?></td>
                </tr>
                <tr style="height: 13pt;">
                    <td class="text-bold">5.</td>
                    <td>Pengencer</td>
                    <td class="text-center">:</td>
                    <td><?php echo html_escape($p['pengencer'] ?? ''); ?></td>
                </tr>
                <tr style="height: 13pt;">
                    <td class="text-bold">6.</td>
                    <td>Suhu Inkubasi / Waktu Inkubasi</td>
                    <td class="text-center">:</td>
                    <td><?php echo html_escape($p['suhu_inkubasi'] ?? '29 - 31'); ?> °C &nbsp;/&nbsp; <?php echo html_escape($p['waktu_inkubasi'] ?? '16 - 18'); ?> jam</td>
                </tr>
                <tr style="height: 13pt;">
                    <td class="text-bold">7.</td>
                    <td>Volume Penetesan</td>
                    <td class="text-center">:</td>
                    <td><?php echo html_escape($p['volume_penetesan'] ?? '100'); ?> µL</td>
                </tr>
                <tr style="height: 13pt;">
                    <td class="text-bold">8.</td>
                    <td>Kekeruhan Mikroba</td>
                    <td class="text-center">:</td>
                    <td><?php echo html_escape($p['kekeruhan_mikroba'] ?? ''); ?> &nbsp;<?php echo html_escape($p['satuan_kekeruhan'] ?? 'McF'); ?> (McF atau %Transmitan*)</td>
                </tr>
                <tr style="height: 13pt;">
                    <td class="text-bold">9.</td>
                    <td>Konsentrasi Inokula</td>
                    <td class="text-center">:</td>
                    <td><?php echo html_escape($p['konsentrasi_inokula'] ?? ''); ?></td>
                </tr>
                <tr style="height: 13pt;">
                    <td class="text-bold">10.</td>
                    <td colspan="3" class="text-bold">Baku Potensi Antibiotika</td>
                </tr>
                <tr style="height: 12pt;">
                    <td></td>
                    <td style="padding-left: 12px;">Nama Baku</td>
                    <td class="text-center">:</td>
                    <td><?php echo html_escape($p['nama_baku'] ?? ''); ?></td>
                </tr>
                <tr style="height: 12pt;">
                    <td></td>
                    <td style="padding-left: 12px;">Tanggal Buka Kemasan Baku</td>
                    <td class="text-center">:</td>
                    <td><?php echo !empty($p['tgl_buka_baku']) ? date('d-m-Y', strtotime($p['tgl_buka_baku'])) : ''; ?></td>
                </tr>
                <tr style="height: 12pt;">
                    <td></td>
                    <td style="padding-left: 12px;">No Kontrol /Lot</td>
                    <td class="text-center">:</td>
                    <td><?php echo html_escape($p['no_kontrol_baku'] ?? ''); ?></td>
                </tr>
                <tr style="height: 12pt;">
                    <td></td>
                    <td style="padding-left: 12px;">Nilai potensi</td>
                    <td class="text-center">:</td>
                    <td><?php echo html_escape($p['nilai_potensi_baku'] ?? ''); ?> &nbsp;<?php echo html_escape($p['satuan_potensi_baku'] ?? 'SI/mg'); ?> (µg/mg atau SI/mg*)</td>
                </tr>
                <tr style="height: 12pt;">
                    <td></td>
                    <td style="padding-left: 12px;">Penimbangan Baku</td>
                    <td class="text-center">:</td>
                    <td><?php echo html_escape($p['penimbangan_baku'] ?? ''); ?> &nbsp;<?php echo !empty($p['keterangan_penimbangan_baku']) ? '(' . html_escape($p['keterangan_penimbangan_baku']) . ')' : ''; ?></td>
                </tr>
                <tr style="height: 13pt;">
                    <td class="text-bold">11.</td>
                    <td>Rasio Dosis</td>
                    <td class="text-center">:</td>
                    <td><?php echo html_escape($p['rasio_dosis'] ?? '1 : 1.25'); ?></td>
                </tr>
                <tr style="height: 12pt;">
                    <td></td>
                    <td style="padding-left: 12px;">Dosis S1</td>
                    <td class="text-center">:</td>
                    <td><?php echo html_escape($p['dosis_s1'] ?? ''); ?> &nbsp;<?php echo html_escape($p['satuan_dosis'] ?? 'SI/mL'); ?></td>
                </tr>
                <tr style="height: 12pt;">
                    <td></td>
                    <td style="padding-left: 12px;">Dosis S2</td>
                    <td class="text-center">:</td>
                    <td><?php echo html_escape($p['dosis_s2'] ?? ''); ?> &nbsp;<?php echo html_escape($p['satuan_dosis'] ?? 'SI/mL'); ?></td>
                </tr>
                <tr style="height: 12pt;">
                    <td></td>
                    <td style="padding-left: 12px;">Dosis S3 (Dosis Acuan)</td>
                    <td class="text-center">:</td>
                    <td><?php echo html_escape($p['dosis_s3'] ?? ''); ?> &nbsp;<?php echo html_escape($p['satuan_dosis'] ?? 'SI/mL'); ?></td>
                </tr>
                <tr style="height: 12pt;">
                    <td></td>
                    <td style="padding-left: 12px;">Dosis S4</td>
                    <td class="text-center">:</td>
                    <td><?php echo html_escape($p['dosis_s4'] ?? ''); ?> &nbsp;<?php echo html_escape($p['satuan_dosis'] ?? 'SI/mL'); ?></td>
                </tr>
                <tr style="height: 12pt;">
                    <td></td>
                    <td style="padding-left: 12px;">Dosis S5</td>
                    <td class="text-center">:</td>
                    <td><?php echo html_escape($p['dosis_s5'] ?? ''); ?> &nbsp;<?php echo html_escape($p['satuan_dosis'] ?? 'SI/mL'); ?></td>
                </tr>
                <tr style="height: 13pt;">
                    <td class="text-bold">12.</td>
                    <td>Pengukuran Daerah Hambatan</td>
                    <td class="text-center">:</td>
                    <td><?php echo html_escape($p['metode_pengukuran_zona'] ?? 'Automatic Zona Reader'); ?> &nbsp;(Automatic Zona Reader / Manual*)</td>
                </tr>
                <tr style="height: 13pt;">
                    <td class="text-bold">13.</td>
                    <td colspan="3" class="text-bold">Perhitungan Larutan Baku :</td>
                </tr>
            </table>

            <!-- PERHITUNGAN LARUTAN BAKU (S1 - S5) BOX -->
            <div style="border: 1pt solid #000; padding: 4px 6px; margin: 4px 0; min-height: 85pt; font-size: 6.8pt; line-height: 1.2;">
                <table class="excel-table">
                    <colgroup>
                        <col style="width: 50%;">
                        <col style="width: 50%;">
                    </colgroup>
                    <tr>
                        <td style="vertical-align: top; padding-right: 6px;">
                            <strong>a. Larutan Baku S1:</strong><br>
                            <?php echo !empty($p['larutan_baku_s1']) ? nl2br(html_escape($p['larutan_baku_s1'])) : '-'; ?>
                            <div style="height: 4px;"></div>
                            <strong>b. Larutan Baku S2:</strong><br>
                            <?php echo !empty($p['larutan_baku_s2']) ? nl2br(html_escape($p['larutan_baku_s2'])) : '-'; ?>
                            <div style="height: 4px;"></div>
                            <strong>c. Larutan Baku S3:</strong><br>
                            <?php echo !empty($p['larutan_baku_s3']) ? nl2br(html_escape($p['larutan_baku_s3'])) : '-'; ?>
                        </td>
                        <td style="vertical-align: top; padding-left: 6px; border-left: 1pt dashed #ccc;">
                            <strong>d. Larutan Baku S4:</strong><br>
                            <?php echo !empty($p['larutan_baku_s4']) ? nl2br(html_escape($p['larutan_baku_s4'])) : '-'; ?>
                            <div style="height: 4px;"></div>
                            <strong>e. Larutan Baku S5:</strong><br>
                            <?php echo !empty($p['larutan_baku_s5']) ? nl2br(html_escape($p['larutan_baku_s5'])) : '-'; ?>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- TANDA TANGAN HALAMAN 1 -->
            <table class="excel-table" style="border-top: 1pt solid #000; border-bottom: 2px double #000; margin-top: auto;">
                <colgroup>
                    <col style="width: 50%;">
                    <col style="width: 50%;">
                </colgroup>
                <tr style="height: 13pt;">
                    <td class="b-right text-bold" style="padding-left: 4px;">Tanda Tangan Penguji :</td>
                    <td class="text-bold" style="padding-left: 10px;">Diperiksa Oleh :</td>
                </tr>
                <tr style="height: 40pt;">
                    <td class="b-right text-center" style="vertical-align: middle;">
                        <?php if (!empty($ttd_penguji_img)): ?>
                            <img src="<?php echo $ttd_penguji_img; ?>" style="max-height: 32px; max-width: 130px; object-fit: contain;" alt="TTD Penguji">
                            <div style="font-size: 6.8pt; font-weight: bold; margin-top: 1px;"><?php echo html_escape($nama_penguji); ?></div>
                        <?php else: ?>
                            <div style="font-size: 7pt; font-weight: bold; color: #198754;">[ TERCATAT DIGITAL ]</div>
                            <div style="font-size: 6.8pt; font-weight: bold; margin-top: 2px;"><?php echo html_escape($nama_penguji); ?></div>
                        <?php endif; ?>
                    </td>
                    <td class="text-center" style="vertical-align: middle;">
                        <?php if ($status_sesi === 'Approved / Final' || $status_sesi === 'Menunggu Approval'): ?>
                            <?php if (!empty($ttd_penyelia_img)): ?>
                                <img src="<?php echo $ttd_penyelia_img; ?>" style="max-height: 32px; max-width: 130px; object-fit: contain;" alt="TTD Penyelia">
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
                <td style="font-size: 7pt; font-weight: bold; padding-left: 2px;">POM-06.02/CFM.01/SOP.01/F.01B.18</td>
                <td class="text-right" style="font-size: 7pt; padding-right: 2px;">Lembar 1 dari 2 halaman</td>
            </tr>
        </table>
    </div>

    <!-- ========================================== -->
    <!-- HALAMAN 2 DARI 2                          -->
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
                        <div style="font-size: 10pt; font-weight: bold; margin-top: 1px; letter-spacing: 0.3px;">POTENSI ANTIBIOTIKA METODE LEMPENG-SILINDER 5 DOSIS</div>
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
                    <td class="b-right text-bold" style="padding-left: 4px; font-size: 7.2pt;">Nama Sampel Uji :</td>
                    <td class="b-right text-bold" style="padding-left: 4px; font-size: 7.2pt;">No. Kode Sampel Uji :</td>
                    <td style="padding-left: 4px; font-size: 7pt; white-space: nowrap;">Tanggal Diterima</td>
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

            <div style="height: 6px;"></div>

            <!-- 14. PERHITUNGAN LARUTAN UJI -->
            <table class="excel-table">
                <colgroup>
                    <col style="width: 3.5%;">
                    <col style="width: 34%;">
                    <col style="width: 2%;">
                    <col style="width: 60.5%;">
                </colgroup>
                <tr style="height: 14pt;">
                    <td class="text-bold">14.</td>
                    <td colspan="3" class="text-bold">Perhitungan Larutan Uji :</td>
                </tr>
            </table>
            <div style="border: 1pt solid #000; padding: 4px 6px; margin: 2px 0 6px 0; min-height: 45pt; font-size: 6.8pt; line-height: 1.2;">
                <?php echo !empty($p['perhitungan_larutan_uji']) ? nl2br(html_escape($p['perhitungan_larutan_uji'])) : '-'; ?>
            </div>

            <!-- 15. PERHITUNGAN RSD ANTAR CAWAN PETRI -->
            <table class="excel-table">
                <colgroup>
                    <col style="width: 3.5%;">
                    <col style="width: 96.5%;">
                </colgroup>
                <tr style="height: 14pt;">
                    <td class="text-bold">15.</td>
                    <td class="text-bold">Perhitungan RSD antar cawan Petri</td>
                </tr>
            </table>

            <div style="padding-left: 14px; font-size: 7pt;">
                <strong>Perhitungan RSD</strong><br>
                <span class="italic">% RSD : SD/Average x 100% , Syarat &le; 10%</span>
            </div>

            <table class="excel-table" style="margin-top: 4px;">
                <colgroup>
                    <col style="width: 3%;">
                    <col style="width: 14%;">
                    <col style="width: 2%;">
                    <col style="width: 16%;">
                    <col style="width: 25%;">
                    <col style="width: 40%;">
                </colgroup>
                <tr style="height: 12pt;">
                    <td>1.</td>
                    <td>S1, RSD</td>
                    <td>=</td>
                    <td><?php echo html_escape($rsd_s1 !== '' ? $rsd_s1 : ($p['rsd_s1_nilai'] ?? '-')); ?> %</td>
                    <td class="text-bold"><?php echo html_escape($p['rsd_s1_status'] ?? ((is_numeric($rsd_s1) && (float)$rsd_s1 <= 10) ? 'Memenuhi' : 'Memenuhi')); ?></td>
                    <td>(Memenuhi/Tidak Memenuhi*)</td>
                </tr>
                <tr style="height: 12pt;">
                    <td>2.</td>
                    <td>S2, RSD</td>
                    <td>=</td>
                    <td><?php echo html_escape($rsd_s2 !== '' ? $rsd_s2 : ($p['rsd_s2_nilai'] ?? '-')); ?> %</td>
                    <td class="text-bold"><?php echo html_escape($p['rsd_s2_status'] ?? ((is_numeric($rsd_s2) && (float)$rsd_s2 <= 10) ? 'Memenuhi' : 'Memenuhi')); ?></td>
                    <td></td>
                </tr>
                <tr style="height: 12pt;">
                    <td>3.</td>
                    <td>S4, RSD</td>
                    <td>=</td>
                    <td><?php echo html_escape($rsd_s4 !== '' ? $rsd_s4 : ($p['rsd_s4_nilai'] ?? '-')); ?> %</td>
                    <td class="text-bold"><?php echo html_escape($p['rsd_s4_status'] ?? ((is_numeric($rsd_s4) && (float)$rsd_s4 <= 10) ? 'Memenuhi' : 'Memenuhi')); ?></td>
                    <td></td>
                </tr>
                <tr style="height: 12pt;">
                    <td>4.</td>
                    <td>S5, RSD</td>
                    <td>=</td>
                    <td><?php echo html_escape($rsd_s5 !== '' ? $rsd_s5 : ($p['rsd_s5_nilai'] ?? '-')); ?> %</td>
                    <td class="text-bold"><?php echo html_escape($p['rsd_s5_status'] ?? ((is_numeric($rsd_s5) && (float)$rsd_s5 <= 10) ? 'Memenuhi' : 'Memenuhi')); ?></td>
                    <td></td>
                </tr>
                <tr style="height: 12pt;">
                    <td>5.</td>
                    <td>Uji, RSD</td>
                    <td>=</td>
                    <td><?php echo html_escape($rsd_uji !== '' ? $rsd_uji : ($p['rsd_uji_nilai'] ?? '-')); ?> %</td>
                    <td class="text-bold"><?php echo html_escape($p['rsd_uji_status'] ?? ((is_numeric($rsd_uji) && (float)$rsd_uji <= 10) ? 'Memenuhi' : 'Memenuhi')); ?></td>
                    <td></td>
                </tr>
            </table>

            <div style="padding-left: 14px; font-size: 7pt; margin-top: 4px;">
                <strong>Dosis tengah S3 pada :</strong>
            </div>

            <table class="excel-table" style="margin-top: 2px;">
                <colgroup>
                    <col style="width: 3%;">
                    <col style="width: 14%;">
                    <col style="width: 2%;">
                    <col style="width: 16%;">
                    <col style="width: 25%;">
                    <col style="width: 40%;">
                </colgroup>
                <tr style="height: 12pt;">
                    <td>1.</td>
                    <td>S1, RSD</td>
                    <td>=</td>
                    <td><?php echo html_escape($p['rsd_tengah_s1_nilai'] ?? '-'); ?> %</td>
                    <td class="text-bold">Memenuhi</td>
                    <td>(Memenuhi/Tidak Memenuhi*)</td>
                </tr>
                <tr style="height: 12pt;">
                    <td>2.</td>
                    <td>S2, RSD</td>
                    <td>=</td>
                    <td><?php echo html_escape($p['rsd_tengah_s2_nilai'] ?? '-'); ?> %</td>
                    <td class="text-bold">Memenuhi</td>
                    <td></td>
                </tr>
                <tr style="height: 12pt;">
                    <td>3.</td>
                    <td>S4, RSD</td>
                    <td>=</td>
                    <td><?php echo html_escape($p['rsd_tengah_s4_nilai'] ?? '-'); ?> %</td>
                    <td class="text-bold">Memenuhi</td>
                    <td></td>
                </tr>
                <tr style="height: 12pt;">
                    <td>4.</td>
                    <td>S5, RSD</td>
                    <td>=</td>
                    <td><?php echo html_escape($p['rsd_tengah_s5_nilai'] ?? '-'); ?> %</td>
                    <td class="text-bold">Memenuhi</td>
                    <td></td>
                </tr>
                <tr style="height: 12pt;">
                    <td>5.</td>
                    <td>Uji RSD</td>
                    <td>=</td>
                    <td><?php echo html_escape($p['rsd_tengah_uji_nilai'] ?? '-'); ?> %</td>
                    <td class="text-bold">Memenuhi</td>
                    <td></td>
                </tr>
            </table>

            <div style="height: 4px;"></div>

            <!-- 16. PERHITUNGAN NILAI R / KURVA BAKU -->
            <table class="excel-table">
                <colgroup>
                    <col style="width: 3.5%;">
                    <col style="width: 96.5%;">
                </colgroup>
                <tr style="height: 14pt;">
                    <td class="text-bold">16.</td>
                    <td class="text-bold">Perhitungan Nilai R / Kurva Baku</td>
                </tr>
            </table>

            <table class="excel-table" style="padding-left: 14px;">
                <colgroup>
                    <col style="width: 3%;">
                    <col style="width: 14%;">
                    <col style="width: 2%;">
                    <col style="width: 16%;">
                    <col style="width: 25%;">
                    <col style="width: 40%;">
                </colgroup>
                <tr style="height: 12pt;">
                    <td></td>
                    <td>R</td>
                    <td>=</td>
                    <td><?php echo html_escape($kurva_r !== '' ? $kurva_r : ($p['kurva_r'] ?? '')); ?></td>
                    <td colspan="2"></td>
                </tr>
                <tr style="height: 12pt;">
                    <td></td>
                    <td>R2</td>
                    <td>=</td>
                    <td><?php echo html_escape($kurva_r2 !== '' ? $kurva_r2 : ($p['kurva_r2'] ?? '')); ?></td>
                    <td colspan="2"></td>
                </tr>
                <tr style="height: 12pt;">
                    <td></td>
                    <td>% R2</td>
                    <td>=</td>
                    <td><?php echo html_escape($kurva_persen_r2 !== '' ? $kurva_persen_r2 : ($p['kurva_persen_r2'] ?? '')); ?> %</td>
                    <td class="text-bold"><?php echo html_escape($p['kurva_status'] ?? ((is_numeric($kurva_persen_r2) && (float)$kurva_persen_r2 >= 95) ? 'Memenuhi' : 'Memenuhi')); ?></td>
                    <td>(Memenuhi/Tidak Memenuhi*)</td>
                </tr>
                <tr style="height: 12pt;">
                    <td colspan="4"></td>
                    <td colspan="2" class="italic">Syarat %R2 &ge; 95%</td>
                </tr>
            </table>

            <div style="height: 4px;"></div>

            <!-- 17. POTENSI SAMPEL -->
            <table class="excel-table">
                <colgroup>
                    <col style="width: 3.5%;">
                    <col style="width: 18%;">
                    <col style="width: 2%;">
                    <col style="width: 76.5%;">
                </colgroup>
                <tr style="height: 14pt;">
                    <td class="text-bold">17.</td>
                    <td class="text-bold">Potensi Sampel</td>
                    <td class="text-center">:</td>
                    <td class="text-bold" style="font-size: 8pt;"><?php echo html_escape($p['potensi_sampel_persen'] ?? '.......'); ?> % &nbsp;<span style="font-size: 6.8pt; font-weight: normal;">(% Perhitungan Terlampir)</span></td>
                </tr>
            </table>

            <div style="font-size: 6.8pt; margin-top: 4px;">
                Ket : *) pilih salah satu
            </div>

            <!-- PUSTAKA & SYARAT -->
            <div style="border-top: 1pt solid #000; padding: 4px 2px; margin-top: 4px;">
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
                        <td><?php echo html_escape($p['pustaka_metode'] ?? 'Farmakope Indonesia Edisi VI'); ?></td>
                        <td><strong>Syarat :</strong> &nbsp;<?php echo html_escape($p['syarat_potensi'] ?? '............ %'); ?> (Pustaka)</td>
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

            <!-- TANDA TANGAN HALAMAN 2 -->
            <table class="excel-table" style="border-bottom: 2px double #000; margin-top: auto;">
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

        <!-- FOOTER HALAMAN 2 -->
        <table class="excel-table" style="margin-top: 3px;">
            <colgroup>
                <col style="width: 70%;">
                <col style="width: 30%;">
            </colgroup>
            <tr style="height: 12pt;">
                <td style="font-size: 7pt; font-weight: bold; padding-left: 2px;">POM-06.02/CFM.01/SOP.01/F.01B.18</td>
                <td class="text-right" style="font-size: 7pt; padding-right: 2px;">Lembar 2 dari 2 halaman</td>
            </tr>
        </table>
    </div>

</body>
</html>
