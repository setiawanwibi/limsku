<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * View Cetak / Simpan PDF Lembar Catatan Pengujian (LCP)
 * PEMERIAN (Organoleptis) - Bidang Kimia
 * Replikasi presisi 100% dari format Excel resmi BPOM:
 * POM-06.02/CFM.01/SOP.01/F.01A.25
 */

$status_sesi = $sesi['status'] ?? 'Draft';
$p = !empty($hasil['data_hasil']) ? json_decode($hasil['data_hasil'], true) : array();
if (isset($p['parameter_values']) && is_array($p['parameter_values'])) {
    $p = array_merge($p, $p['parameter_values']);
}

// Helper resolve TTD digital
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

$tgl_lap = !empty($p['tanggal_laporan']) ? date('d F Y', strtotime($p['tanggal_laporan'])) : date('d F Y', strtotime($hasil['updated_at'] ?? 'now'));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak LCP - PEMERIAN - <?php echo html_escape($sampel['nama_sampel'] ?? 'Sampel'); ?></title>
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
            padding: 15px 0;
            background-color: #525659;
            font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
            font-size: 8.5pt;
            color: #000;
            line-height: 1.3;
        }

        .no-print {
            position: fixed;
            top: 12px;
            right: 12px;
            z-index: 9999;
            background: #ffffff;
            padding: 10px 14px;
            border-radius: 8px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.25);
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .no-print button {
            background: #004b87;
            color: #ffffff;
            border: none;
            padding: 7px 14px;
            font-size: 11px;
            font-weight: 600;
            border-radius: 4px;
            cursor: pointer;
        }

        .page-container {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            background: #ffffff;
            padding: 12mm 15mm;
            position: relative;
            box-shadow: 0 0 10px rgba(0,0,0,0.3);
        }

        @media print {
            body {
                background: transparent;
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
            .page-container {
                box-shadow: none;
                margin: 0;
                width: 100%;
                min-height: 100%;
                padding: 10mm 12mm;
            }
        }

        .header-kop {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 6px;
            margin-bottom: 12px;
        }
        .header-kop .title-1 {
            font-size: 11pt;
            font-weight: bold;
            letter-spacing: 0.5px;
        }
        .header-kop .title-2 {
            font-size: 13pt;
            font-weight: bold;
            margin-top: 3px;
        }
        .header-kop .title-sub {
            font-size: 9pt;
            font-style: italic;
            margin-top: 3px;
        }

        .table-data {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5pt;
            margin-bottom: 8px;
        }

        .table-data td {
            padding: 4px 6px;
            vertical-align: top;
        }

        .table-pemerian {
            width: 100%;
            border-collapse: collapse;
            font-size: 9pt;
            margin: 15px 0 20px 0;
            border: 1px solid #000;
        }

        .table-pemerian th, .table-pemerian td {
            border: 1px solid #000;
            padding: 8px 12px;
            vertical-align: middle;
        }

        .table-pemerian th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
        }

        .ttd-box {
            height: 55px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .ttd-box img {
            max-height: 52px;
            max-width: 130px;
        }
    </style>
</head>
<body>

<div class="no-print">
    <button onclick="window.print()">🖨️ Cetak / Simpan PDF</button>
    <button onclick="window.close()" style="background:#6c757d;">Tutup</button>
</div>

<div class="page-container">
    <!-- KOP FORMULIR -->
    <div class="header-kop">
        <div class="title-1">LAMPIRAN CATATAN PENGUJIAN</div>
        <div class="title-2">PEMERIAN</div>
        <div class="title-sub">Laboratorium Kimia Balai Besar/Balai/Loka POM di …....................................</div>
    </div>

    <!-- IDENTITAS SAMPEL -->
    <table class="table-data" style="margin-bottom: 8px;">
        <tr>
            <td style="width: 18%;">Nama Sampel</td>
            <td style="width: 2%;">:</td>
            <td style="width: 40%; font-weight: bold;"><?php echo html_escape($sampel['nama_sampel'] ?? '-'); ?></td>
            <td style="width: 18%;">No Kode Sampel</td>
            <td style="width: 2%;">:</td>
            <td style="width: 20%; font-weight: bold;"><?php echo html_escape($sampel['kode_sampel'] ?? '-'); ?></td>
        </tr>
        <tr>
            <td>Zat yang diuji</td>
            <td>:</td>
            <td style="font-weight: bold;"><?php echo html_escape($sampel['parameter_uji'] ?? ($p['zat_yang_diuji'] ?? 'Pemerian')); ?></td>
            <td>No Bets</td>
            <td>:</td>
            <td><?php echo html_escape($sampel['nomor_bets'] ?? '-'); ?></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td></td>
            <td></td>
            <td>Tanggal Uji</td>
            <td>:</td>
            <td><?php echo !empty($sesi['tanggal_pengujian']) ? date('d-m-Y', strtotime($sesi['tanggal_pengujian'])) : '-'; ?></td>
        </tr>
    </table>

    <div style="border-bottom: 1px dashed #000; margin: 6px 0 14px 0;"></div>

    <!-- TABEL HASIL PENGAMATAN PEMERIAN -->
    <table class="table-pemerian">
        <thead>
            <tr>
                <th style="width: 8%;">No</th>
                <th style="width: 24%;">Parameter Pengamatan</th>
                <th style="width: 68%;">Hasil Pengamatan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: center; font-weight: bold;">1</td>
                <td><strong>Bentuk</strong></td>
                <td style="font-weight: bold;"><?php echo html_escape($p['bentuk'] ?? '-'); ?></td>
            </tr>
            <tr>
                <td style="text-align: center; font-weight: bold;">2</td>
                <td><strong>Warna</strong></td>
                <td style="font-weight: bold;"><?php echo html_escape($p['warna'] ?? '-'); ?></td>
            </tr>
            <tr>
                <td style="text-align: center; font-weight: bold;">3</td>
                <td><strong>Bau</strong></td>
                <td style="font-weight: bold;"><?php echo html_escape($p['bau'] ?? '-'); ?></td>
            </tr>
            <tr>
                <td style="text-align: center; font-weight: bold;">4</td>
                <td><strong>Rasa</strong></td>
                <td style="font-weight: bold;"><?php echo html_escape($p['rasa'] ?? '-'); ?></td>
            </tr>
            <tr>
                <td style="text-align: center; font-weight: bold;">5</td>
                <td><strong>Penandaan</strong></td>
                <td style="font-weight: bold;"><?php echo html_escape($p['penandaan'] ?? '-'); ?></td>
            </tr>
        </tbody>
    </table>

    <!-- PERSYARATAN & KESIMPULAN -->
    <table class="table-data" style="margin-top: 10px;">
        <tr>
            <td style="width: 22%;">Pustaka Persyaratan</td>
            <td style="width: 2%;">:</td>
            <td><?php echo nl2br(html_escape($p['pustaka_persyaratan'] ?? '-')); ?></td>
        </tr>
        <tr>
            <td>Syarat Monografi</td>
            <td>:</td>
            <td><strong><?php echo html_escape($p['syarat_pemerian'] ?? '-'); ?></strong></td>
        </tr>
        <?php if (!empty($p['keterangan_tambahan'])): ?>
        <tr>
            <td>Catatan Tambahan</td>
            <td>:</td>
            <td><?php echo nl2br(html_escape($p['keterangan_tambahan'])); ?></td>
        </tr>
        <?php endif; ?>
        <tr>
            <td style="font-size: 9.5pt; font-weight: bold; padding-top: 10px;">Kesimpulan</td>
            <td style="padding-top: 10px;">:</td>
            <td style="font-size: 10pt; font-weight: bold; color: #004b87; padding-top: 10px;">
                <?php echo html_escape($p['kesimpulan'] ?? 'Memenuhi Syarat (MS)'); ?>
            </td>
        </tr>
    </table>

    <!-- TANDA TANGAN -->
    <table style="width: 100%; border-collapse: collapse; margin-top: 40px; font-size: 8.5pt;">
        <tr>
            <td style="width: 50%; padding-left: 20px;">
                Penguji :<br>
                Nama dan Paraf :
                <div class="ttd-box">
                    <?php if (!empty($ttd_penguji_img)): ?>
                        <img src="<?php echo $ttd_penguji_img; ?>" alt="TTD Penguji">
                    <?php else: ?>
                        <span style="color:#aaa; font-style:italic;">(Tanda Tangan)</span>
                    <?php endif; ?>
                </div>
                <strong>( <?php echo html_escape($sesi['nama_penguji'] ?? 'Penguji'); ?> )</strong><br>
                Tanggal Laporan : <?php echo $tgl_lap; ?>
            </td>
            <td style="width: 50%; padding-left: 40px;">
                Diperiksa Penyelia / Verifikator :<br>
                Nama dan Paraf :
                <div class="ttd-box">
                    <?php if (!empty($ttd_penyelia_img)): ?>
                        <img src="<?php echo $ttd_penyelia_img; ?>" alt="TTD Verifikator">
                    <?php else: ?>
                        <span style="color:#aaa; font-style:italic;">(Tanda Tangan)</span>
                    <?php endif; ?>
                </div>
                <strong>( <?php echo html_escape($sesi['nama_verifikator'] ?? 'Penyelia / Verifikator'); ?> )</strong><br>
                Tanggal : <?php echo !empty($sesi['verified_at']) ? date('d F Y', strtotime($sesi['verified_at'])) : '-'; ?>
            </td>
        </tr>
    </table>

    <!-- FOOTER RESMI -->
    <div style="position: absolute; bottom: 10mm; left: 15mm; right: 15mm; font-size: 8pt;">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="font-style: italic;">Ket: *Coret yang tidak perlu</td>
                <td style="text-align: right;">Lembar 1 dari 1 Halaman</td>
            </tr>
            <tr>
                <td colspan="2" style="font-weight: bold; padding-top: 2px;">POM-06.02/CFM.01/SOP.01/F.01A.25</td>
            </tr>
        </table>
    </div>

</div>

</body>
</html>
