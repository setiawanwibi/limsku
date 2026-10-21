<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * View Cetak / Simpan PDF Lembar Catatan Pengujian (LCP)
 * KROMATOGRAFI GAS SPEKTROMETRI MASSA (GC-MS) - Bidang Kimia
 * Replikasi presisi 100% dari format Excel resmi BPOM:
 * POM-06.02/CFM.01/SOP.01/F.01A.17
 */

$status_sesi = $sesi['status'] ?? 'Draft';
$p = !empty($hasil['data_hasil']) ? json_decode($hasil['data_hasil'], true) : array();
if (isset($p['parameter_values']) && is_array($p['parameter_values'])) {
    $p = array_merge($p, $p['parameter_values']);
}

// Data tabel GC-MS
$tabel_gcms = isset($p['tabel_gcms']) && is_array($p['tabel_gcms']) ? $p['tabel_gcms'] : array();

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
    <title>Cetak LCP - KROMATOGRAFI GAS SPEKTROMETRI MASSA - <?php echo html_escape($sampel['nama_sampel'] ?? 'Sampel'); ?></title>
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
            padding: 10px 0;
            background-color: #525659;
            font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
            font-size: 7pt;
            color: #000;
            line-height: 1.15;
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
            padding: 7mm 10mm 8mm 10mm;
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
                padding: 6mm 8mm 6mm 8mm;
            }
        }

        .header-kop {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 3px;
            margin-bottom: 5px;
        }
        .header-kop .title-1 {
            font-size: 10pt;
            font-weight: bold;
            letter-spacing: 0.5px;
        }
        .header-kop .title-2 {
            font-size: 11pt;
            font-weight: bold;
            margin-top: 1px;
        }
        .header-kop .title-sub {
            font-size: 7.5pt;
            font-style: italic;
            margin-top: 2px;
        }

        .table-data {
            width: 100%;
            border-collapse: collapse;
            font-size: 7pt;
            margin-bottom: 3px;
        }

        .table-data td {
            padding: 1px 2px;
            vertical-align: top;
        }

        .table-bordered {
            width: 100%;
            border-collapse: collapse;
            font-size: 6.5pt;
            margin: 3px 0;
        }

        .table-bordered th, .table-bordered td {
            border: 1px solid #000;
            padding: 2px 2px;
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

        .ttd-box {
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .ttd-box img {
            max-height: 36px;
            max-width: 100px;
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
        <div class="title-2">KROMATOGRAFI GAS SPEKTROMETRI MASSA</div>
        <div class="title-sub">Laboratorium Kimia Balai Besar/Balai/Loka POM di …....................................</div>
    </div>

    <!-- IDENTITAS SAMPEL -->
    <table class="table-data">
        <tr>
            <td style="width: 15%;">Nama Sampel</td>
            <td style="width: 2%;">:</td>
            <td style="width: 43%; font-weight: bold;"><?php echo html_escape($sampel['nama_sampel'] ?? '-'); ?></td>
            <td style="width: 17%;">No Kode Sampel</td>
            <td style="width: 2%;">:</td>
            <td style="width: 21%; font-weight: bold;"><?php echo html_escape($sampel['kode_sampel'] ?? '-'); ?></td>
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
            <td>Merk Alat / Tipe</td>
            <td>:</td>
            <td><?php echo html_escape(($p['merk_alat'] ?? '-') . ' / ' . ($p['tipe_seri'] ?? '-')); ?></td>
            <td>Tanggal Uji</td>
            <td>:</td>
            <td><?php echo !empty($sesi['tanggal_pengujian']) ? date('d-m-Y', strtotime($sesi['tanggal_pengujian'])) : '-'; ?></td>
        </tr>
        <tr>
            <td>Detektor</td>
            <td>:</td>
            <td><?php echo html_escape($p['detektor'] ?? 'MSD'); ?></td>
            <td>Kondisi Analisis</td>
            <td>:</td>
            <td>
                <?php echo html_escape($p['kondisi_analisis'] ?? 'Sesuai prosedur'); ?>
                <?php if (!empty($p['kondisi_adjusment'])): ?> (<em><?php echo html_escape($p['kondisi_adjusment']); ?></em>)<?php endif; ?>
            </td>
        </tr>
    </table>

    <div style="border-bottom: 1px dashed #000; margin: 2px 0 3px 0;"></div>

    <!-- KONDISI OPERASI GC-MS -->
    <table class="table-data">
        <tr>
            <td style="width: 17%;">Gas Pembawa</td>
            <td style="width: 2%;">:</td>
            <td style="width: 38%;"><?php echo html_escape($p['gas_pembawa'] ?? 'Helium'); ?></td>
            <td style="width: 18%;">Suhu Injektor</td>
            <td style="width: 2%;">:</td>
            <td style="width: 23%;"><?php echo html_escape($p['suhu_injektor'] ?? '-'); ?> °C</td>
        </tr>
        <tr>
            <td>Pelarut</td>
            <td>:</td>
            <td><?php echo html_escape($p['pelarut'] ?? '-'); ?></td>
            <td>Suhu Kolom / Oven</td>
            <td>:</td>
            <td><?php echo html_escape($p['suhu_kolom_program'] ?? '-'); ?></td>
        </tr>
        <tr>
            <td>Kolom GC</td>
            <td>:</td>
            <td><?php echo html_escape($p['kolom_dimensi'] ?? '-'); ?></td>
            <td>Suhu Detektor</td>
            <td>:</td>
            <td><?php echo html_escape($p['suhu_detektor'] ?? '-'); ?> °C</td>
        </tr>
        <tr>
            <td>Mode / Split Ratio</td>
            <td>:</td>
            <td>
                <?php echo html_escape($p['mode_penyuntikan'] ?? 'Split'); ?>
                <?php if (!empty($p['split_ratio'])): ?> (Ratio: <?php echo html_escape($p['split_ratio']); ?>)<?php endif; ?>
            </td>
            <td>Suhu Ion Source</td>
            <td>:</td>
            <td><?php echo html_escape($p['suhu_ion_source'] ?? '-'); ?> °C</td>
        </tr>
        <tr>
            <td>Vol. Penyuntikan</td>
            <td>:</td>
            <td><?php echo html_escape($p['volume_penyuntikan'] ?? '-'); ?> µL</td>
            <td>Suhu Interface</td>
            <td>:</td>
            <td><?php echo html_escape($p['suhu_interface'] ?? '-'); ?> °C</td>
        </tr>
        <tr>
            <td>Laju Alir / Pressure</td>
            <td>:</td>
            <td><?php echo html_escape($p['laju_alir_pressure'] ?? '-'); ?></td>
            <td>Solvent Cut Time</td>
            <td>:</td>
            <td><?php echo html_escape($p['solvent_cut_time'] ?? '-'); ?> menit</td>
        </tr>
        <?php if (!empty($p['headspace_info'])): ?>
        <tr>
            <td>Autosampler Headspace</td>
            <td>:</td>
            <td colspan="4"><?php echo html_escape($p['headspace_info']); ?></td>
        </tr>
        <?php endif; ?>
        <tr>
            <td>Akuisisi MS</td>
            <td>:</td>
            <td colspan="4">
                Target: <strong><?php echo html_escape($p['ms_nama_senyawa'] ?? '-'); ?></strong> |
                Quantifier: <strong><?php echo html_escape($p['quantifier_ion'] ?? '-'); ?></strong> m/z |
                Qualifier 1: <strong><?php echo html_escape($p['qualifier_ion_1'] ?? '-'); ?></strong> |
                Qualifier 2: <strong><?php echo html_escape($p['qualifier_ion_2'] ?? '-'); ?></strong> |
                Qualifier 3: <strong><?php echo html_escape($p['qualifier_ion_3'] ?? '-'); ?></strong>
            </td>
        </tr>
        <tr>
            <td>Baku Pembanding</td>
            <td>:</td>
            <td colspan="4">
                <strong><?php echo html_escape($p['baku_nama'] ?? '-'); ?></strong>
                (No Kontrol: <?php echo html_escape($p['baku_no_kontrol'] ?? '-'); ?>,
                 Kadar: <?php echo html_escape($p['baku_kadar'] ?? '-'); ?>%,
                 SP/Air: <?php echo html_escape($p['baku_sp'] ?? '-'); ?>%)
                <?php if (!empty($p['internal_nama'])): ?>
                    &nbsp;|&nbsp; Baku Internal: <strong><?php echo html_escape($p['internal_nama']); ?></strong> (No Kontrol: <?php echo html_escape($p['internal_no_kontrol'] ?? '-'); ?>)
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td>Lampiran Kromatogram</td>
            <td>:</td>
            <td colspan="4">
                Kromatogram UKS: <em><?php echo html_escape($p['status_kromatogram_uks'] ?? 'Terlampir'); ?></em> |
                Kromatogram Sampel/Baku: <em><?php echo html_escape($p['status_kromatogram_sampel'] ?? 'Terlampir'); ?></em>
            </td>
        </tr>
    </table>

    <!-- TABEL PENIMBANGAN & PENGUKURAN GC-MS -->
    <table class="table-bordered">
        <thead>
            <tr>
                <th rowspan="2" style="width: 17%;">Nama Zat</th>
                <th colspan="3" style="width: 20%;">Bobot (g)</th>
                <th rowspan="2" style="width: 6%;">FP</th>
                <th rowspan="2" style="width: 7%;">Konsentrasi</th>
                <th rowspan="2" style="width: 6%;">Rt (menit)</th>
                <th rowspan="2" style="width: 8%;">Quantifier<br>(A)</th>
                <th colspan="3" style="width: 18%;">Qualifier Area / Intensitas</th>
                <th colspan="3" style="width: 14%;">Rasio Ion</th>
                <th rowspan="2" style="width: 8%;">Rasio Area (Uji/Int)</th>
            </tr>
            <tr>
                <th style="width: 7%;">Wadah+zat</th>
                <th style="width: 7%;">Wadah+sisa</th>
                <th style="width: 6%;">Zat</th>
                <th style="width: 6%;">1 (B)</th>
                <th style="width: 6%;">2 (C)</th>
                <th style="width: 6%;">3 (D)</th>
                <th style="width: 4.6%;">B/A</th>
                <th style="width: 4.6%;">C/A</th>
                <th style="width: 4.6%;">D/A</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $rows = !empty($tabel_gcms) ? $tabel_gcms : array(
                array('kategori' => 'Baku Pembanding'),
                array('kategori' => 'Baku Internal'),
                array('kategori' => '1. Zat Uji'),
                array('kategori' => '   Baku Internal Zat Uji 1'),
                array('kategori' => '2. Zat Uji'),
                array('kategori' => '   Baku Internal Zat Uji 2'),
                array('kategori' => 'Spiked Sample: Zat Uji'),
                array('kategori' => 'Spiked Sample: Baku')
            );

            foreach ($rows as $r):
                $kat = $r['kategori'] ?? '-';
                $w_zat = isset($r['wadah_zat']) && is_numeric($r['wadah_zat']) ? number_format((float)$r['wadah_zat'], 4, '.', '') : '-';
                $w_sisa = isset($r['wadah_sisa']) && is_numeric($r['wadah_sisa']) ? number_format((float)$r['wadah_sisa'], 4, '.', '') : '-';
                
                $b_bersih = '-';
                if (isset($r['wadah_zat']) && is_numeric($r['wadah_zat']) && isset($r['wadah_sisa']) && is_numeric($r['wadah_sisa'])) {
                    $b_bersih = number_format((float)$r['wadah_zat'] - (float)$r['wadah_sisa'], 4, '.', '');
                } elseif (isset($r['bobot_bersih']) && is_numeric($r['bobot_bersih'])) {
                    $b_bersih = number_format((float)$r['bobot_bersih'], 4, '.', '');
                }

                $a = isset($r['area_quantifier']) && is_numeric($r['area_quantifier']) ? (float)$r['area_quantifier'] : 0;
                $b = isset($r['area_qualifier_1']) && is_numeric($r['area_qualifier_1']) ? (float)$r['area_qualifier_1'] : 0;
                $c = isset($r['area_qualifier_2']) && is_numeric($r['area_qualifier_2']) ? (float)$r['area_qualifier_2'] : 0;
                $d = isset($r['area_qualifier_3']) && is_numeric($r['area_qualifier_3']) ? (float)$r['area_qualifier_3'] : 0;

                $r1 = ($a > 0 && $b > 0) ? number_format($b / $a, 3, '.', '') : (isset($r['rasio_ion_1']) && is_numeric($r['rasio_ion_1']) ? number_format((float)$r['rasio_ion_1'], 3, '.', '') : '-');
                $r2 = ($a > 0 && $c > 0) ? number_format($c / $a, 3, '.', '') : (isset($r['rasio_ion_2']) && is_numeric($r['rasio_ion_2']) ? number_format((float)$r['rasio_ion_2'], 3, '.', '') : '-');
                $r3 = ($a > 0 && $d > 0) ? number_format($d / $a, 3, '.', '') : (isset($r['rasio_ion_3']) && is_numeric($r['rasio_ion_3']) ? number_format((float)$r['rasio_ion_3'], 3, '.', '') : '-');
            ?>
            <tr>
                <td><strong><?php echo html_escape($kat); ?></strong></td>
                <td class="text-right"><?php echo $w_zat; ?></td>
                <td class="text-right"><?php echo $w_sisa; ?></td>
                <td class="text-right text-bold"><?php echo $b_bersih; ?></td>
                <td class="text-center"><?php echo html_escape($r['faktor_pengenceran'] ?? '-'); ?></td>
                <td class="text-center"><?php echo html_escape($r['konsentrasi'] ?? '-'); ?></td>
                <td class="text-center"><?php echo html_escape($r['waktu_retensi'] ?? '-'); ?></td>
                <td class="text-right text-bold"><?php echo $a > 0 ? number_format($a, 0, '.', ',') : '-'; ?></td>
                <td class="text-right"><?php echo $b > 0 ? number_format($b, 0, '.', ',') : '-'; ?></td>
                <td class="text-right"><?php echo $c > 0 ? number_format($c, 0, '.', ',') : '-'; ?></td>
                <td class="text-right"><?php echo $d > 0 ? number_format($d, 0, '.', ',') : '-'; ?></td>
                <td class="text-center text-bold"><?php echo $r1; ?></td>
                <td class="text-center text-bold"><?php echo $r2; ?></td>
                <td class="text-center text-bold"><?php echo $r3; ?></td>
                <td class="text-center"><?php echo html_escape($r['rasio_area_internal'] ?? '-'); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- EVALUASI KEBERTERIMAAN RASIO ION -->
    <div style="background: #fdfdfd; border: 1px solid #ccc; padding: 3px 5px; margin: 3px 0;">
        <strong>Evaluasi Rasio Ion:</strong> (Syarat Keberterimaan: &plusmn; 40% atau sesuai tabel acuan)
        <table class="table-data" style="margin-top: 2px;">
            <tr>
                <td style="width: 33%;">
                    <strong>Rasio Ion 1:</strong><br>
                    Rentang Baku: <?php echo html_escape($p['rasio_ion_1_rentang_baku'] ?? '-'); ?><br>
                    Kesimpulan: <strong><?php echo html_escape($p['rasio_ion_1_kesimpulan'] ?? 'MS'); ?></strong>
                </td>
                <td style="width: 33%;">
                    <strong>Rasio Ion 2:</strong><br>
                    Rentang Baku: <?php echo html_escape($p['rasio_ion_2_rentang_baku'] ?? '-'); ?><br>
                    Kesimpulan: <strong><?php echo html_escape($p['rasio_ion_2_kesimpulan'] ?? 'MS'); ?></strong>
                </td>
                <td style="width: 34%;">
                    <strong>Rasio Ion 3:</strong><br>
                    Rentang Baku: <?php echo html_escape($p['rasio_ion_3_rentang_baku'] ?? '-'); ?><br>
                    Kesimpulan: <strong><?php echo html_escape($p['rasio_ion_3_kesimpulan'] ?? 'MS'); ?></strong>
                </td>
            </tr>
        </table>
    </div>

    <!-- HASIL & PERSYARATAN -->
    <table class="table-data" style="margin-top: 2px;">
        <tr>
            <td style="width: 22%;">Hasil Uji (Identifikasi)</td>
            <td style="width: 2%;">:</td>
            <td style="width: 38%; font-weight: bold; font-size: 8pt; color: #004b87;"><?php echo html_escape($p['hasil_identifikasi'] ?? 'Positif'); ?></td>
            <td style="width: 18%;">RPD / RSD</td>
            <td style="width: 2%;">:</td>
            <td style="width: 18%; font-weight: bold;"><?php echo html_escape($p['nilai_rpd'] ?? '-'); ?> %</td>
        </tr>
        <tr>
            <td>Hasil &plusmn; Ketidakpastian</td>
            <td>:</td>
            <td>
                <?php echo html_escape($p['hasil_ketidakpastian'] ?? '-'); ?>
                (<em><?php echo html_escape($p['decision_rule'] ?? 'Simple Acceptance'); ?></em>)
            </td>
            <td>Syarat RPD / RSD</td>
            <td>:</td>
            <td><?php echo html_escape($p['syarat_rpd'] ?? '-'); ?></td>
        </tr>
        <tr>
            <td>LOD / LOQ</td>
            <td>:</td>
            <td colspan="4"><?php echo html_escape($p['lod_loq'] ?? '-'); ?></td>
        </tr>
        <tr>
            <td>Pustaka Metode</td>
            <td>:</td>
            <td colspan="4"><?php echo nl2br(html_escape($p['pustaka_metode'] ?? '-')); ?></td>
        </tr>
        <tr>
            <td>Pustaka Persyaratan</td>
            <td>:</td>
            <td><?php echo nl2br(html_escape($p['pustaka_persyaratan'] ?? '-')); ?></td>
            <td>Syarat Persyaratan</td>
            <td>:</td>
            <td><strong><?php echo html_escape($p['syarat'] ?? '-'); ?></strong></td>
        </tr>
        <tr>
            <td style="font-size: 8pt; font-weight: bold;">Kesimpulan</td>
            <td>:</td>
            <td colspan="4" style="font-size: 8.5pt; font-weight: bold; color: #004b87;">
                <?php echo html_escape($p['kesimpulan'] ?? 'Memenuhi Syarat (MS)'); ?>
            </td>
        </tr>
    </table>

    <!-- TANDA TANGAN -->
    <table style="width: 100%; border-collapse: collapse; margin-top: 8px; font-size: 7pt;">
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
                Tanggal Laporan : <?php echo $tgl_lap; ?>
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
    <div style="position: absolute; bottom: 5mm; left: 10mm; right: 10mm; font-size: 6.5pt;">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="font-style: italic;">Ket: *Coret yang tidak perlu</td>
                <td style="text-align: right;">Lembar 1 dari 1 halaman</td>
            </tr>
            <tr>
                <td colspan="2" style="font-weight: bold; padding-top: 1px;">POM-06.02/CFM.01/SOP.01/F.01A.17</td>
            </tr>
        </table>
    </div>

</div>

</body>
</html>
