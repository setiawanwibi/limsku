<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * View Cetak / Simpan PDF Lembar Catatan Pengujian (LCP)
 * KROMATOGRAFI LAPIS TIPIS (KLT) - Bidang Kimia
 * Replikasi presisi 100% dari format Excel resmi BPOM:
 * POM-06.02/CFM.01/SOP.01/F.01A.10
 */

$status_sesi = $sesi['status'] ?? 'Draft';
$p = !empty($hasil['data_hasil']) ? json_decode($hasil['data_hasil'], true) : array();
if (isset($p['parameter_values']) && is_array($p['parameter_values'])) {
    $p = array_merge($p, $p['parameter_values']);
}

// Data tabel klt
$klt_items = isset($p['klt_items']) && is_array($p['klt_items']) ? $p['klt_items'] : array();

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
    <title>Cetak LCP - KROMATOGRAFI LAPIS TIPIS - <?php echo html_escape($sampel['nama_sampel'] ?? 'Sampel'); ?></title>
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
            font-size: 8pt;
            color: #000;
            line-height: 1.25;
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
            padding: 10mm 12mm;
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
                padding: 8mm 10mm;
            }
        }

        .header-kop {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 5px;
            margin-bottom: 8px;
        }
        .header-kop .title-1 {
            font-size: 11pt;
            font-weight: bold;
            letter-spacing: 0.5px;
        }
        .header-kop .title-2 {
            font-size: 12pt;
            font-weight: bold;
            margin-top: 2px;
        }
        .header-kop .title-sub {
            font-size: 8.5pt;
            font-style: italic;
            margin-top: 3px;
        }

        .table-data {
            width: 100%;
            border-collapse: collapse;
            font-size: 8pt;
            margin-bottom: 6px;
        }

        .table-data td {
            padding: 2px 4px;
            vertical-align: top;
        }

        .table-bordered {
            width: 100%;
            border-collapse: collapse;
            font-size: 7.5pt;
            margin: 6px 0;
        }

        .table-bordered th, .table-bordered td {
            border: 1px solid #000;
            padding: 3px 4px;
            vertical-align: middle;
        }

        .table-bordered th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-bold { font-weight: bold; }

        .form-sop {
            font-size: 7.5pt;
            font-style: italic;
            margin-top: 4px;
        }

        .ttd-box {
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .ttd-box img {
            max-height: 48px;
            max-width: 120px;
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
        <div class="title-2">KROMATOGRAFI LAPIS TIPIS</div>
        <div class="title-sub">Laboratorium Kimia Balai Besar/Balai/Loka POM di …....................................</div>
    </div>

    <!-- IDENTITAS SAMPEL -->
    <table class="table-data" style="margin-bottom: 4px;">
        <tr>
            <td style="width: 16%;">Nama Sampel</td>
            <td style="width: 2%;">:</td>
            <td style="width: 42%; font-weight: bold;"><?php echo html_escape($sampel['nama_sampel'] ?? '-'); ?></td>
            <td style="width: 18%;">No Kode Sampel</td>
            <td style="width: 2%;">:</td>
            <td style="width: 20%; font-weight: bold;"><?php echo html_escape($sampel['kode_sampel'] ?? '-'); ?></td>
        </tr>
        <tr>
            <td>Zat yang diuji</td>
            <td>:</td>
            <td style="font-weight: bold;"><?php echo html_escape($sampel['parameter_uji'] ?? ($p['zat_yang_diuji'] ?? '-')); ?></td>
            <td>No. Bets</td>
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

    <div style="border-bottom: 1px dashed #000; margin-bottom: 6px;"></div>

    <!-- KONDISI ANALISIS -->
    <table class="table-data" style="margin-bottom: 4px;">
        <tr>
            <td style="width: 22%;">Kondisi analisis</td>
            <td style="width: 2%;">:</td>
            <td colspan="4">
                <strong><?php echo html_escape($p['kondisi_analisis_opt'] ?? 'Sesuai prosedur'); ?></strong>
                <?php if (!empty($p['kondisi_adjusment'])): ?>
                    &nbsp;&nbsp;(<em>Adjusment: <?php echo html_escape($p['kondisi_adjusment']); ?></em>)
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td>Pelarut</td>
            <td>:</td>
            <td colspan="4"><?php echo html_escape($p['pelarut'] ?? '-'); ?></td>
        </tr>
        <tr>
            <td>Fase Diam</td>
            <td>:</td>
            <td style="width: 38%;"><?php echo html_escape($p['fase_diam'] ?? 'Silika gel 60 F254'); ?></td>
            <td style="width: 18%;">Ukuran lempeng</td>
            <td style="width: 2%;">:</td>
            <td style="width: 18%;"><?php echo html_escape($p['ukuran_lempeng'] ?? '-'); ?></td>
        </tr>
        <tr>
            <td>Lempeng Lapis Tipis</td>
            <td>:</td>
            <td colspan="4"><?php echo html_escape($p['lempeng_lapis_tipis'] ?? 'Kaca'); ?></td>
        </tr>
        <tr>
            <td>Fase Gerak (FG)</td>
            <td>:</td>
            <td colspan="4">
                1. <?php echo html_escape($p['fase_gerak_1'] ?? '-'); ?>
                <?php if (!empty($p['fase_gerak_2'])): ?>&nbsp;&nbsp;|&nbsp;&nbsp;2. <?php echo html_escape($p['fase_gerak_2']); ?><?php endif; ?>
                <?php if (!empty($p['fase_gerak_3'])): ?>&nbsp;&nbsp;|&nbsp;&nbsp;3. <?php echo html_escape($p['fase_gerak_3']); ?><?php endif; ?>
                <?php if (!empty($p['fase_gerak_dst'])): ?>&nbsp;&nbsp;|&nbsp;&nbsp;dst. <?php echo html_escape($p['fase_gerak_dst']); ?><?php endif; ?>
            </td>
        </tr>
        <tr>
            <td>Teknik Pengembangan</td>
            <td>:</td>
            <td colspan="4">
                <?php echo html_escape($p['teknik_pengembangan'] ?? 'Menaik'); ?>
                <?php if (!empty($p['impregnasi_dalam'])): ?> (dalam: <?php echo html_escape($p['impregnasi_dalam']); ?>)<?php endif; ?>
            </td>
        </tr>
        <tr>
            <td>Jarak Rambat*</td>
            <td>:</td>
            <td colspan="4"><strong><?php echo html_escape($p['jarak_rambat_cm'] ?? '-'); ?></strong> cm</td>
        </tr>
        <tr>
            <td>Pengamatan</td>
            <td>:</td>
            <td colspan="4"><?php echo html_escape($p['pengamatan'] ?? 'UV 254 nm'); ?></td>
        </tr>
        <tr>
            <td>Penampak Bercak</td>
            <td>:</td>
            <td colspan="4"><?php echo html_escape($p['penampak_bercak'] ?? '-'); ?></td>
        </tr>
        <tr>
            <td>Baku Pembanding</td>
            <td>:</td>
            <td colspan="4">
                <strong><?php echo html_escape($p['baku_nama'] ?? '-'); ?></strong>
                (No Kontrol : <?php echo html_escape($p['baku_no_kontrol'] ?? '-'); ?>,
                 Kadar : <?php echo html_escape($p['baku_kadar'] ?? '-'); ?>%,
                 Susut Pengeringan/kadar air * : <?php echo html_escape($p['baku_susut_pengeringan'] ?? '-'); ?>%)
            </td>
        </tr>
    </table>

    <!-- TABEL PENIMBANGAN & RF -->
    <table class="table-bordered">
        <thead>
            <tr>
                <th rowspan="3" style="width: 20%;">Nama Zat</th>
                <th colspan="3" style="width: 27%;">Bobot (g)</th>
                <th rowspan="3" style="width: 12%;">Pengenceran</th>
                <th rowspan="3" style="width: 12%;">Konsentrasi</th>
                <th rowspan="3" style="width: 9%;">Vol Penotolan (µl)</th>
                <th colspan="3" style="width: 20%;">Rf</th>
            </tr>
            <tr>
                <th style="width: 9%;">Wadah + zat</th>
                <th style="width: 9%;">Wadah + sisa</th>
                <th style="width: 9%;">Zat</th>
                <th style="width: 6.6%;">FG 1</th>
                <th style="width: 6.6%;">FG 2</th>
                <th style="width: 6.6%;">FG 3</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $rows = !empty($klt_items) ? $klt_items : array(
                array('kategori' => 'Baku Pembanding', 'nama_zat' => ''),
                array('kategori' => 'Zat Uji 1', 'nama_zat' => '1. '),
                array('kategori' => 'Zat Uji 2', 'nama_zat' => '2. '),
                array('kategori' => 'Spiked Sample: Zat Uji', 'nama_zat' => 'Zat Uji'),
                array('kategori' => 'Spiked Sample: Baku', 'nama_zat' => 'Baku Pembanding')
            );

            foreach ($rows as $idx => $r):
                $kat = $r['kategori'] ?? '';
                $nama = $r['nama_zat'] ?? '';
                $label = !empty($nama) ? $nama : $kat;
                $w_zat = isset($r['wadah_zat']) && is_numeric($r['wadah_zat']) ? number_format((float)$r['wadah_zat'], 4, '.', '') : '-';
                $w_sisa = isset($r['wadah_sisa']) && is_numeric($r['wadah_sisa']) ? number_format((float)$r['wadah_sisa'], 4, '.', '') : '-';
                
                $b_zat = '-';
                if (isset($r['wadah_zat']) && is_numeric($r['wadah_zat']) && isset($r['wadah_sisa']) && is_numeric($r['wadah_sisa'])) {
                    $b_zat = number_format((float)$r['wadah_zat'] - (float)$r['wadah_sisa'], 4, '.', '');
                } elseif (isset($r['bobot_zat']) && is_numeric($r['bobot_zat'])) {
                    $b_zat = number_format((float)$r['bobot_zat'], 4, '.', '');
                }
            ?>
            <tr>
                <td><strong><?php echo html_escape($label); ?></strong></td>
                <td class="text-right"><?php echo $w_zat; ?></td>
                <td class="text-right"><?php echo $w_sisa; ?></td>
                <td class="text-right text-bold"><?php echo $b_zat; ?></td>
                <td class="text-center"><?php echo html_escape($r['pengenceran'] ?? '-'); ?></td>
                <td class="text-center"><?php echo html_escape($r['konsentrasi'] ?? '-'); ?></td>
                <td class="text-center"><?php echo html_escape($r['vol_penotolan'] ?? '-'); ?></td>
                <td class="text-center"><?php echo html_escape($r['rf_fg1'] ?? '-'); ?></td>
                <td class="text-center"><?php echo html_escape($r['rf_fg2'] ?? '-'); ?></td>
                <td class="text-center"><?php echo html_escape($r['rf_fg3'] ?? '-'); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- EVALUASI & HASIL -->
    <table class="table-data" style="margin-top: 6px;">
        <tr>
            <td style="width: 22%;">Hasil Pengamatan*</td>
            <td style="width: 2%;">:</td>
            <td colspan="4"><strong><?php echo html_escape($p['hasil_pengamatan'] ?? 'Ada/ Tidak ada bercak sampel yang sejajar dengan baku dan spiked sample'); ?></strong></td>
        </tr>
        <tr>
            <td>Kromatogram</td>
            <td>:</td>
            <td colspan="4"><?php echo html_escape($p['kromatogram'] ?? 'Terlampir'); ?></td>
        </tr>
        <tr>
            <td>Hasil Uji*</td>
            <td>:</td>
            <td colspan="4"><strong><?php echo html_escape($p['hasil_uji'] ?? 'Sampel diduga mengandung/ tidak mengandung zat yang diuji'); ?></strong></td>
        </tr>
        <tr>
            <td>LOD</td>
            <td>:</td>
            <td colspan="4"><?php echo html_escape($p['lod'] ?? '-'); ?></td>
        </tr>
        <tr>
            <td>Pustaka Metode</td>
            <td>:</td>
            <td colspan="4"><?php echo nl2br(html_escape($p['pustaka_metode'] ?? '-')); ?></td>
        </tr>
        <tr>
            <td>Pustaka Persyaratan</td>
            <td>:</td>
            <td style="width: 42%;"><?php echo nl2br(html_escape($p['pustaka_persyaratan'] ?? '-')); ?></td>
            <td style="width: 14%;">Syarat :</td>
            <td colspan="2"><strong><?php echo html_escape($p['syarat'] ?? '-'); ?></strong></td>
        </tr>
        <tr>
            <td style="font-size: 9pt; font-weight: bold;">Kesimpulan*</td>
            <td>:</td>
            <td colspan="4" style="font-size: 9pt; font-weight: bold; color: #004b87;">
                <?php echo html_escape($p['kesimpulan'] ?? 'MS'); ?>
                <?php if (!empty($p['kesimpulan_detail'])): ?> (<?php echo html_escape($p['kesimpulan_detail']); ?>)<?php endif; ?>
            </td>
        </tr>
    </table>

    <!-- TANDA TANGAN -->
    <table style="width: 100%; border-collapse: collapse; margin-top: 15px; font-size: 8pt;">
        <tr>
            <td style="width: 50%; padding-left: 20px;">
                Penguji :<br>
                Nama dan Paraf
                <div class="ttd-box">
                    <?php if (!empty($ttd_penguji_img)): ?>
                        <img src="<?php echo $ttd_penguji_img; ?>" alt="TTD Penguji">
                    <?php else: ?>
                        <span style="color:#aaa; font-style:italic;">(Tanda Tangan)</span>
                    <?php endif; ?>
                </div>
                <strong>( <?php echo html_escape($sesi['nama_penguji'] ?? 'Penguji'); ?> )</strong><br>
                Tanggal laporan : <?php echo $tgl_lap; ?>
            </td>
            <td style="width: 50%; padding-left: 30px;">
                Diperiksa Penyelia / Verifikator :<br>
                Nama dan Paraf
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
    <div style="position: absolute; bottom: 8mm; left: 12mm; right: 12mm; font-size: 7.5pt;">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="font-style: italic;">Ket: *Coret yang tidak perlu</td>
                <td style="text-align: right;">Lembar 1 dari 1 halaman</td>
            </tr>
            <tr>
                <td colspan="2" style="font-weight: bold; padding-top: 2px;">POM-06.02/CFM.01/SOP.01/F.01A.10</td>
            </tr>
        </table>
    </div>

</div>

</body>
</html>
