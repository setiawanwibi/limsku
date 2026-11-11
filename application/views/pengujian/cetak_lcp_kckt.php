<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * View Cetak / Simpan PDF Lembar Catatan Pengujian (LCP)
 * KROMATOGRAFI CAIR KINERJA TINGGI (HPLC / KCKT) - Bidang Kimia
 * Replikasi presisi 100% dari format Excel resmi BPOM:
 * POM-06.02/CFM.01/SOP.01/F.01A.12
 */

$status_sesi = $sesi['status'] ?? 'Draft';
$p = !empty($hasil['data_hasil']) ? json_decode($hasil['data_hasil'], true) : array();
if (isset($p['parameter_values']) && is_array($p['parameter_values'])) {
    $p = array_merge($p, $p['parameter_values']);
}

// Deteksi Metode Perhitungan Sediaan (Multi-Formula Penetapan Kadar HPLC)
$metode_raw = strtolower((string)($p['metode_perhitungan_kadar'] ?? ''));
if (strpos($metode_raw, 'cemplung') !== false || strpos($metode_raw, 'effervescent') !== false) {
    $mode_kadar = 'tablet_cemplung';
} elseif (strpos($metode_raw, 'sirup') !== false || strpos($metode_raw, 'suspensi') !== false || strpos($metode_raw, 'cair') !== false || strpos($metode_raw, 'volume') !== false) {
    $mode_kadar = 'sirup';
} else {
    $mode_kadar = 'tablet_br';
}

$vol_etiket = !empty($p['volume_takaran_etiket']) && (float)$p['volume_takaran_etiket'] > 0 ? (float)$p['volume_takaran_etiket'] : 5.0;
$label_kadar_nyata = ($mode_kadar === 'sirup') ? ('Kadar per ' . $vol_etiket . ' mL') : 'Kadar per tablet';
$unit_kadar_nyata = ($mode_kadar === 'sirup') ? ('mg/' . $vol_etiket . 'mL') : 'mg/tab';

// Data tabel HPLC
$tabel_hplc = isset($p['tabel_hplc']) && is_array($p['tabel_hplc']) ? $p['tabel_hplc'] : array();
$param_uji = $metode['parameter_uji'] ?? ($hasil['nama_metode'] ?? ($p['parameter_uji'] ?? ''));
$is_identifikasi = (stripos($param_uji, 'identifikasi') !== false || stripos($hasil['nama_metode'] ?? '', 'identifikasi') !== false || stripos($p['nama_template'] ?? '', 'identifikasi') !== false);

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
    <title>Cetak LCP - KROMATOGRAFI CAIR KINERJA TINGGI - <?php echo html_escape($sampel['nama_sampel'] ?? 'Sampel'); ?></title>
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
            padding: 12px 0;
            background-color: #525659;
            font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
            font-size: 7.5pt;
            color: #000;
            line-height: 1.2;
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
            padding: 8mm 12mm 10mm 12mm;
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
                padding: 6mm 10mm 8mm 10mm;
            }
        }

        .header-kop {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 4px;
            margin-bottom: 6px;
        }
        .header-kop .title-1 {
            font-size: 10.5pt;
            font-weight: bold;
            letter-spacing: 0.5px;
        }
        .header-kop .title-2 {
            font-size: 11.5pt;
            font-weight: bold;
            margin-top: 2px;
        }
        .header-kop .title-sub {
            font-size: 8pt;
            font-style: italic;
            margin-top: 2px;
        }

        .table-data {
            width: 100%;
            border-collapse: collapse;
            font-size: 7.5pt;
            margin-bottom: 4px;
        }

        .table-data td {
            padding: 1.5px 3px;
            vertical-align: top;
        }

        .table-bordered {
            width: 100%;
            border-collapse: collapse;
            font-size: 7pt;
            margin: 4px 0;
        }

        .table-bordered th, .table-bordered td {
            border: 1px solid #000;
            padding: 2.5px 3px;
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

        .rumus-box {
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            padding: 4px 8px;
            font-size: 7.5pt;
            margin: 4px 0;
            border-radius: 3px;
        }

        .ttd-box {
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .ttd-box img {
            max-height: 42px;
            max-width: 110px;
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
        <div class="title-2">KROMATOGRAFI CAIR KINERJA TINGGI</div>
        <div class="title-sub">Laboratorium Kimia Balai Besar/Balai/Loka POM di …....................................</div>
    </div>

    <!-- IDENTITAS SAMPEL -->
    <table class="table-data">
        <tr>
            <td style="width: 16%;">Nama Sampel</td>
            <td style="width: 2%;">:</td>
            <td style="width: 44%; font-weight: bold;"><?php echo html_escape($sampel['nama_sampel'] ?? '-'); ?></td>
            <td style="width: 16%;">No Kode Sampel</td>
            <td style="width: 2%;">:</td>
            <td style="width: 20%; font-weight: bold;"><?php echo html_escape($sampel['kode_sampel'] ?? '-'); ?></td>
        </tr>
        <tr>
            <td>Zat yang diuji</td>
            <td>:</td>
            <td style="font-weight: bold;"><?php echo html_escape($sampel['parameter_uji'] ?? ($p['zat_yang_diuji'] ?? '-')); ?></td>
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

    <div style="border-bottom: 1px dashed #000; margin: 3px 0 5px 0;"></div>

    <!-- KONDISI INSTRUMEN & KROMATOGRAFI -->
    <table class="table-data">
        <tr>
            <td style="width: 16%;">Merk Alat</td>
            <td style="width: 2%;">:</td>
            <td style="width: 44%;"><?php echo html_escape($p['merk_alat'] ?? '-'); ?></td>
            <td style="width: 16%;">Detektor</td>
            <td style="width: 2%;">:</td>
            <td style="width: 20%;"><?php echo html_escape($p['detektor'] ?? '-'); ?></td>
        </tr>
        <tr>
            <td>Tipe / seri</td>
            <td>:</td>
            <td><?php echo html_escape($p['tipe_seri'] ?? '-'); ?></td>
            <td>Suhu Kolom</td>
            <td>:</td>
            <td>
                <?php 
                $suhu_opt = $p['suhu_opt'] ?? 'Suhu Kamar';
                echo html_escape($suhu_opt);
                if ($suhu_opt === 'Suhu Terkontrol (°C)' && !empty($p['suhu_custom'])) {
                    echo " (" . html_escape($p['suhu_custom']) . " °C)";
                }
                ?>
            </td>
        </tr>
        <tr>
            <td>Kondisi Analisis</td>
            <td>:</td>
            <td colspan="4">
                <strong><?php echo html_escape($p['kondisi_analisis'] ?? 'Sesuai prosedur'); ?></strong>
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
            <td>Kolom</td>
            <td>:</td>
            <td colspan="4"><?php echo html_escape($p['kolom_dimensi'] ?? '-'); ?></td>
        </tr>
        <tr>
            <td>Fase Gerak</td>
            <td>:</td>
            <td colspan="4">
                <strong><?php echo html_escape($p['tipe_elusi'] ?? 'Isokratik'); ?></strong> :
                <?php if (($p['tipe_elusi'] ?? 'Isokratik') === 'Isokratik'): ?>
                    <?php echo html_escape($p['fase_gerak_isokratik'] ?? '-'); ?>
                <?php else: ?>
                    <?php if (!empty($p['komposisi_fase_a']) || !empty($p['komposisi_fase_b']) || !empty($p['komposisi_fase_c'])): ?>
                        <div style="font-size: 7.2pt; margin-bottom: 3px; line-height: 1.35;">
                            <?php if (!empty($p['komposisi_fase_a'])): ?>
                                <div><strong>A.</strong> <?php echo html_escape($p['komposisi_fase_a']); ?></div>
                            <?php endif; ?>
                            <?php if (!empty($p['komposisi_fase_b'])): ?>
                                <div><strong>B.</strong> <?php echo html_escape($p['komposisi_fase_b']); ?></div>
                            <?php endif; ?>
                            <?php if (!empty($p['komposisi_fase_c'])): ?>
                                <div><strong>C.</strong> <?php echo html_escape($p['komposisi_fase_c']); ?></div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <?php 
                    $gradTable = !empty($tabel_gradient) ? $tabel_gradient : (!empty($p['tabel_gradient']) ? $p['tabel_gradient'] : null);
                    if (!empty($gradTable) && is_array($gradTable)): 
                    ?>
                        <div style="margin-top: 3px;">
                            <table class="table-bordered" style="width: 60%; font-size: 6.8pt; margin: 2px 0;">
                                <thead>
                                    <tr>
                                        <th>Waktu (menit)</th>
                                        <th>Fase A (%)</th>
                                        <th>Fase B (%)</th>
                                        <th>Fase C (%)</th>
                                        <th>Laju Alir (mL/menit)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($gradTable as $g): 
                                        if (empty($g['waktu_menit']) && empty($g['fase_a']) && empty($g['fase_b'])) continue;
                                    ?>
                                    <tr>
                                        <td class="text-center"><?php echo html_escape($g['waktu_menit'] ?? '-'); ?></td>
                                        <td class="text-center"><?php echo html_escape($g['fase_a'] ?? '-'); ?></td>
                                        <td class="text-center"><?php echo html_escape($g['fase_b'] ?? '-'); ?></td>
                                        <td class="text-center"><?php echo html_escape(!empty($g['fase_c']) ? $g['fase_c'] : '-'); ?></td>
                                        <td class="text-center"><?php echo html_escape(!empty($g['laju_alir']) ? $g['laju_alir'] : ($p['laju_aliran'] ?? '-')); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <?php echo nl2br(html_escape($p['gradient_komposisi'] ?? '-')); ?>
                    <?php endif; ?>
                <?php endif; ?>
            </td>
        </tr>
        <tr>
            <td>Laju Aliran</td>
            <td>:</td>
            <td><strong><?php echo html_escape($p['laju_aliran'] ?? '-'); ?></strong> mL/menit</td>
            <td>λ uji</td>
            <td>:</td>
            <td><strong><?php echo html_escape($p['panjang_gelombang'] ?? '-'); ?></strong> nm</td>
        </tr>
        <tr>
            <td>Baku pembanding</td>
            <td>:</td>
            <td colspan="4">
                <strong><?php echo html_escape($p['baku_nama'] ?? '-'); ?></strong>&nbsp;&nbsp;
                (No Kontrol: <?php echo html_escape($p['baku_no_kontrol'] ?? '-'); ?>,
                 Kadar: <?php echo html_escape($p['baku_kadar'] ?? '-'); ?>%,
                 SP/Kadar Air: <?php echo html_escape($p['baku_sp'] ?? '-'); ?>%,
                 Kadar Terkoreksi: <strong><?php echo html_escape($p['baku_kadar_terkoreksi'] ?? '-'); ?>%</strong>)
            </td>
        </tr>
        <?php if (!empty($p['internal_nama'])): ?>
        <tr>
            <td>Baku Internal</td>
            <td>:</td>
            <td colspan="4">
                <strong><?php echo html_escape($p['internal_nama']); ?></strong>&nbsp;&nbsp;
                (No Kontrol: <?php echo html_escape($p['internal_no_kontrol'] ?? '-'); ?>,
                 Kadar: <?php echo html_escape($p['internal_kadar'] ?? '-'); ?>%,
                 SP: <?php echo html_escape($p['internal_sp'] ?? '-'); ?>%,
                 Kadar Terkoreksi: <strong><?php echo html_escape($p['internal_kadar_terkoreksi'] ?? '-'); ?>%</strong>)
            </td>
        </tr>
        <?php endif; ?>
        <tr>
            <td>Status Lampiran</td>
            <td>:</td>
            <td colspan="4">
                Kromatogram UKS: <em><?php echo html_escape($p['status_kromatogram_uks'] ?? 'Terlampir'); ?></em> |
                Kromatogram Sampel/Baku: <em><?php echo html_escape($p['status_kromatogram_sampel'] ?? 'Terlampir'); ?></em> |
                Kurva Regresi: <em><?php echo html_escape($p['status_regresi'] ?? 'Terlampir'); ?></em>
            </td>
        </tr>
        <?php if (!$is_identifikasi && !empty($p['faktor_konversi_bm']) && $p['faktor_konversi_bm'] !== '1.0' && $p['faktor_konversi_bm'] !== '1'): ?>
        <tr>
            <td>Faktor Koreksi BM/BR</td>
            <td>:</td>
            <td colspan="4">
                <?php
                    $bm_raw = trim((string)$p['faktor_konversi_bm']);
                    $bm_eval = null;
                    if (strpos($bm_raw, '/') !== false || strpos($bm_raw, '*') !== false) {
                        $clean_expr = preg_replace('/[^0-9\.\+\-\*\/]/', '', str_replace(',', '.', $bm_raw));
                        try {
                            @eval("\$bm_eval = ($clean_expr);");
                        } catch (Throwable $e) {}
                    }
                ?>
                <strong><?php echo html_escape($bm_raw); ?></strong>
                <?php if ($bm_eval !== null && is_numeric($bm_eval) && $bm_eval > 0): ?>
                    <span class="text-primary fw-bold">( = <?php echo number_format($bm_eval, 4, '.', ''); ?> )</span>
                <?php endif; ?>
                (BM1 / BM2)
            </td>
        </tr>
        <?php endif; ?>
    </table>

    <!-- TABEL PENIMBANGAN & PENGUKURAN HPLC -->
    <table class="table-bordered">
        <thead>
            <tr>
                <th rowspan="2" style="width: 22%;">Nama Zat</th>
                <th colspan="3" style="width: 27%;">
                    <?php if ($mode_kadar === 'tablet_cemplung'): ?>
                        Bobot Penimbangan Baku / Sediaan Uji (1 Tablet Utuh)
                    <?php elseif ($mode_kadar === 'sirup'): ?>
                        Bobot Baku / Volume Pipet Uji (Vu)
                    <?php else: ?>
                        Bobot (g)
                    <?php endif; ?>
                </th>
                <th rowspan="2" style="width: 10%;">Faktor Pengenceran</th>
                <th rowspan="2" style="width: 10%;">Konsentrasi</th>
                <th rowspan="2" style="width: 8%;">Vol. Injeksi (µL)</th>
                <th rowspan="2" style="width: 7%;">Rt (menit)</th>
                <th rowspan="2" style="width: 10%;">Respon Puncak</th>
                <th rowspan="2" style="width: 6%;">λ (nm)</th>
            </tr>
            <tr>
                <th style="width: 9%;">Wadah + zat</th>
                <th style="width: 9%;">Wadah + sisa</th>
                <th style="width: 9%;">Bobot bersih</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($is_identifikasi) {
                $rows = !empty($tabel_hplc) ? $tabel_hplc : array(
                    array('kategori' => 'Baku Pembanding'),
                    array('kategori' => 'Pelarut'),
                    array('kategori' => 'Zat Uji'),
                    array('kategori' => 'Spike')
                );
            } else {
                $rows = !empty($tabel_hplc) ? $tabel_hplc : array(
                    array('kategori' => 'Baku Pembanding'),
                    array('kategori' => 'Baku Internal'),
                    array('kategori' => 'Zat Uji Replikasi 1'),
                    array('kategori' => 'Zat Uji Replikasi 2')
                );
            }

            foreach ($rows as $r):
                $kat = $r['kategori'] ?? '-';
                if (!$is_identifikasi && stripos($kat, 'spiked') !== false) continue;
                $is_uji = (stripos($kat, 'uji') !== false || stripos($kat, 'sampel') !== false);

                $w_zat = (isset($r['wadah_zat']) && $r['wadah_zat'] !== '') ? (string)$r['wadah_zat'] : '-';
                $w_sisa = (isset($r['wadah_sisa']) && $r['wadah_sisa'] !== '') ? (string)$r['wadah_sisa'] : '-';
                
                $b_bersih = '-';
                if (isset($r['bobot_bersih']) && $r['bobot_bersih'] !== '') {
                    $b_bersih = (string)$r['bobot_bersih'];
                } elseif (isset($r['wadah_zat']) && is_numeric($r['wadah_zat']) && isset($r['wadah_sisa']) && is_numeric($r['wadah_sisa'])) {
                    $b_bersih = (string)((float)$r['wadah_zat'] - (float)$r['wadah_sisa']);
                }

                $fp = isset($r['faktor_pengenceran']) && is_numeric($r['faktor_pengenceran']) ? (float)$r['faktor_pengenceran'] : ($r['faktor_pengenceran'] ?? '-');
                $vol = isset($r['vol_injeksi']) ? $r['vol_injeksi'] : '20';
                $rt = isset($r['waktu_retensi']) ? $r['waktu_retensi'] : '-';
                $respon = isset($r['respon_puncak']) && $r['respon_puncak'] !== '' ? (string)$r['respon_puncak'] : '-';
                $lmbd = isset($r['lambda_nm']) ? $r['lambda_nm'] : ($p['panjang_gelombang'] ?? '-');
            ?>
            <tr>
                <td><strong><?php echo html_escape($kat); ?></strong></td>
                <?php if ($is_uji && $mode_kadar === 'tablet_cemplung'): ?>
                    <td colspan="3" class="text-center" style="font-size: 7.2pt; background-color: #fbfbfb;">
                        <strong>1 Tablet Utuh</strong> (Direct Dissolution)
                    </td>
                <?php elseif ($is_uji && $mode_kadar === 'sirup'): ?>
                    <td colspan="2" class="text-center" style="font-size: 7.2pt; background-color: #fbfbfb;">
                        Volume Pipet (Vu): <strong><?php echo html_escape(!empty($r['bobot_bersih']) ? $r['bobot_bersih'] : (!empty($r['wadah_zat']) ? $r['wadah_zat'] : ($vol_etiket ?: '5.0'))); ?> mL</strong>
                    </td>
                    <td class="text-right text-bold"><?php echo html_escape(!empty($r['bobot_bersih']) ? $r['bobot_bersih'] : (!empty($r['wadah_zat']) ? $r['wadah_zat'] : ($vol_etiket ?: '5.0'))); ?></td>
                <?php else: ?>
                    <td class="text-right"><?php echo $w_zat; ?></td>
                    <td class="text-right"><?php echo $w_sisa; ?></td>
                    <td class="text-right text-bold"><?php echo $b_bersih; ?></td>
                <?php endif; ?>
                <td class="text-center"><?php echo html_escape($fp); ?></td>
                <td class="text-center"><?php echo html_escape($r['konsentrasi'] ?? '-'); ?></td>
                <td class="text-center"><?php echo html_escape($vol); ?></td>
                <td class="text-center"><?php echo html_escape($rt); ?></td>
                <td class="text-right text-bold"><?php echo html_escape($respon); ?></td>
                <td class="text-center"><?php echo html_escape($lmbd); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php if ($is_identifikasi): ?>
    <!-- HASIL PENGUJIAN IDENTIFIKASI (KUALITATIF - Poin 1 & 3: Tanpa Rumus & Kolom Kadar) -->
    <table class="table-data" style="margin-top: 6px;">
        <tr>
            <td style="width: 25%;">Hasil Pengamatan / Uji</td>
            <td style="width: 2%;">:</td>
            <td colspan="4" style="font-weight: bold; font-size: 8.5pt; color: #004b87;">
                <?php echo html_escape($p['hasil_uji'] ?? ($p['hasil_identifikasi'] ?? 'Positif / Sesuai')); ?>
            </td>
        </tr>
        <tr>
            <td>Keterangan Uji Identifikasi</td>
            <td>:</td>
            <td colspan="4">
                <em>Waktu retensi (Rt) puncak utama kromatogram larutan uji sesuai dengan larutan baku pembanding.</em>
            </td>
        </tr>
        <tr>
            <td>Pustaka Metode</td>
            <td>:</td>
            <td colspan="4"><?php echo nl2br(html_escape(!empty($p['pustaka_metode']) ? $p['pustaka_metode'] : ($metode['pustaka_acuan'] ?? 'Farmakope Indonesia Edisi VI'))); ?></td>
        </tr>
        <tr>
            <td>Pustaka Persyaratan</td>
            <td>:</td>
            <td><?php echo nl2br(html_escape($p['pustaka_persyaratan'] ?? 'Farmakope Indonesia Edisi VI')); ?></td>
            <td>Syarat :</td>
            <td>:</td>
            <td><strong><?php echo html_escape($p['syarat_mutu'] ?? ($p['syarat'] ?? ($metode['persyaratan'] ?? '-'))); ?></strong></td>
        </tr>
        <tr>
            <td style="font-size: 8.5pt; font-weight: bold;">Kesimpulan*</td>
            <td>:</td>
            <td colspan="4" style="font-size: 9pt; font-weight: bold; color: <?php echo (($p['kesimpulan'] ?? '') === 'Tidak Memenuhi Syarat (TMS)') ? '#dc2626' : '#004b87'; ?>;">
                <?php echo html_escape($p['kesimpulan'] ?? 'Memenuhi Syarat (MS)'); ?>
            </td>
        </tr>
    </table>
    <?php else: ?>
    <!-- RUMUS DAN DETAIL PERHITUNGAN -->
    <div class="rumus-box">
        <strong>Rumus Perhitungan:</strong><br>
        <?php if ($mode_kadar === 'tablet_cemplung'): ?>
            <code>Kadar Rata-rata = (Lu / Lb) &times; Bb &times; (Fu / Fb) &times; (1 / Ke) &times; Kb <?php if (!empty($p['faktor_konversi_bm']) && $p['faktor_konversi_bm'] !== '1.0' && $p['faktor_konversi_bm'] !== '1'): ?>&times; (<?php echo html_escape($p['faktor_konversi_bm']); ?>) <?php endif; ?>&times; 100%</code><br>
            <div style="font-size: 7.2pt; color: #166534; font-weight: 600; margin: 3px 0;">
                Keterangan Rumus Tablet Cemplung: Pada penetapan kadar sediaan tablet cemplung / effervescent / direct dissolution, pengujian menggunakan 1 tablet utuh yang dilarutkan langsung ke dalam labu ukur tanpa penggerusan 20 tablet dan tanpa penimbangan serbuk sisa (bagian zat uji: wadah zat, wadah sisa, dan bobot uji digabung menjadi 1 tablet utuh).
            </div>
            <span style="font-size: 6.8pt; color: #555;">
                Lu = Respon Puncak Uji | Lb = Respon Puncak Baku | Bb = Bobot Baku (mg) | Fu = FP Uji | Fb = FP Baku | Ke = Klaim Etiket (<strong><?php echo html_escape($p['klaim_etiket'] ?? '-'); ?> mg/tablet</strong>) | Kb = Kadar Baku Terkoreksi (<strong><?php echo html_escape($p['baku_kadar_terkoreksi'] ?? '-'); ?>%</strong>)<?php if (!empty($p['faktor_konversi_bm']) && $p['faktor_konversi_bm'] !== '1.0' && $p['faktor_konversi_bm'] !== '1'): ?> | Faktor Koreksi BM/BR = <strong><?php echo html_escape($p['faktor_konversi_bm']); ?></strong><?php endif; ?> [Metode: Tablet Cemplung / Direct Dissolution Utuh]
            </span>
        <?php elseif ($mode_kadar === 'sirup'): ?>
            <code>Kadar Rata-rata = (Lu / Lb) &times; (Bb / Vu) &times; (Fu / Fb) &times; (<?php echo $vol_etiket; ?> / Ke) &times; Kb <?php if (!empty($p['faktor_konversi_bm']) && $p['faktor_konversi_bm'] !== '1.0' && $p['faktor_konversi_bm'] !== '1'): ?>&times; (<?php echo html_escape($p['faktor_konversi_bm']); ?>) <?php endif; ?>&times; 100%</code><br>
            <div style="font-size: 7.2pt; color: #1e40af; font-weight: 600; margin: 3px 0;">
                Keterangan Rumus Sirup: Pada penetapan kadar sediaan sirup / suspensi / cairan oral, pengujian menggunakan pemipetan volume sampel uji (Vu dalam mL) per takaran saji <?php echo $vol_etiket; ?> mL (bagian wadah zat dan wadah sisa digabung menjadi satu bagian volume uji Vu).
            </div>
            <span style="font-size: 6.8pt; color: #555;">
                Lu = Respon Puncak Uji | Lb = Respon Puncak Baku | Bb = Bobot Baku (mg) | Vu = Volume Pipet Sampel Uji (mL) | Fu = FP Uji | Fb = FP Baku | V_etiket = Takaran Saji (<strong><?php echo $vol_etiket; ?> mL</strong>) | Ke = Klaim Etiket (<strong><?php echo html_escape($p['klaim_etiket'] ?? '-'); ?> mg per <?php echo $vol_etiket; ?> mL</strong>) | Kb = Kadar Baku Terkoreksi (<strong><?php echo html_escape($p['baku_kadar_terkoreksi'] ?? '-'); ?>%</strong>)<?php if (!empty($p['faktor_konversi_bm']) && $p['faktor_konversi_bm'] !== '1.0' && $p['faktor_konversi_bm'] !== '1'): ?> | Faktor Koreksi BM/BR = <strong><?php echo html_escape($p['faktor_konversi_bm']); ?></strong><?php endif; ?> [Metode: Sirup / Cairan Oral]
            </span>
        <?php else: ?>
            <code>Kadar Rata-rata = (Lu / Lb) &times; (Bb / Bu) &times; (Fu / Fb) &times; (Br / Ke) &times; Kb <?php if (!empty($p['faktor_konversi_bm']) && $p['faktor_konversi_bm'] !== '1.0' && $p['faktor_konversi_bm'] !== '1'): ?>&times; (<?php echo html_escape($p['faktor_konversi_bm']); ?>) <?php endif; ?>&times; 100%</code><br>
            <span style="font-size: 6.8pt; color: #555;">
                Lu = Respon Puncak Uji | Lb = Respon Puncak Baku | Bb = Bobot Baku | Bu = Bobot Uji | Fu = Faktor Pengenceran Uji | Fb = Faktor Pengenceran Baku | Br = Bobot Rata-rata Tablet (<strong><?php echo html_escape($p['bobot_rata_tablet'] ?? '-'); ?> mg</strong>) | Ke = Klaim Etiket (<strong><?php echo html_escape($p['klaim_etiket'] ?? '-'); ?> mg</strong>) | Kb = Kadar Baku Terkoreksi (<strong><?php echo html_escape($p['baku_kadar_terkoreksi'] ?? '-'); ?>%</strong>)<?php if (!empty($p['faktor_konversi_bm']) && $p['faktor_konversi_bm'] !== '1.0' && $p['faktor_konversi_bm'] !== '1'): ?> | Faktor Koreksi BM/BR = <strong><?php echo html_escape($p['faktor_konversi_bm']); ?></strong><?php endif; ?> [Metode: Tablet/Kapsul BR - 20 Tablet Digerus]
            </span>
        <?php endif; ?>
    </div>

    <!-- HASIL PERHITUNGAN -->
    <table class="table-data" style="margin-top: 3px;">
        <tr>
            <td style="width: 25%;">1) Replikasi 1</td>
            <td style="width: 2%;">:</td>
            <td style="width: 28%; font-weight: bold; font-size: 8pt;"><?php echo html_escape($p['kadar_replikasi_1'] ?? '-'); ?> %</td>
            <td style="width: 20%;">Kadar Rata-rata (%)</td>
            <td style="width: 2%;">:</td>
            <td style="width: 23%; font-weight: bold; font-size: 8.5pt; color: #004b87;"><?php echo html_escape($p['kadar_rata_persen'] ?? '-'); ?> %</td>
        </tr>
        <tr>
            <td>2) Replikasi 2</td>
            <td>:</td>
            <td style="font-weight: bold; font-size: 8pt;"><?php echo html_escape($p['kadar_replikasi_2'] ?? '-'); ?> %</td>
            <td><?php echo html_escape($label_kadar_nyata); ?></td>
            <td>:</td>
            <td style="font-weight: bold; font-size: 8.5pt; color: #004b87;"><?php echo html_escape(!empty($p['kadar_rata_mg']) ? $p['kadar_rata_mg'] . ' ' . $unit_kadar_nyata : '-'); ?></td>
        </tr>
        <tr>
            <td>Hasil Uji (Identifikasi)</td>
            <td>:</td>
            <td><strong><?php echo html_escape($p['hasil_identifikasi'] ?? 'Positif'); ?></strong></td>
            <td>RPD / RSD*</td>
            <td>:</td>
            <td><strong><?php echo html_escape($p['nilai_rpd'] ?? '-'); ?> %</strong></td>
        </tr>
        <tr>
            <td>Hasil Uji ± Ketidakpastian</td>
            <td>:</td>
            <td>
                <?php echo html_escape($p['ketidakpastian_teks'] ?? ($p['kadar_rata_persen'] ?? '-') . ' %'); ?>
                (<em><?php echo html_escape($p['decision_rule'] ?? 'Simple Acceptance'); ?></em>)
            </td>
            <td>Syarat RPD Horwitz</td>
            <td>:</td>
            <td><strong>&le; <?php echo html_escape($p['syarat_horwitz'] ?? '-'); ?> %</strong></td>
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
            <td>Syarat Kadar</td>
            <td>:</td>
            <td><strong><?php echo html_escape($p['syarat_kadar'] ?? '-'); ?></strong></td>
        </tr>
        <tr>
            <td style="font-size: 8.5pt; font-weight: bold;">Kesimpulan*</td>
            <td>:</td>
            <td colspan="4" style="font-size: 9pt; font-weight: bold; color: #004b87;">
                <?php echo html_escape($p['kesimpulan'] ?? 'Memenuhi Syarat (MS)'); ?>
            </td>
        </tr>
    </table>

    <?php endif; ?>
    <!-- TANDA TANGAN -->
    <table style="width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 7.5pt;">
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
                Diperiksa penyelia / Verifikator :<br>
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
    <div style="position: absolute; bottom: 6mm; left: 12mm; right: 12mm; font-size: 7pt;">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="font-style: italic;">Ket: *Coret yang tidak perlu</td>
                <td style="text-align: right;">Lembar 1 dari 1 halaman</td>
            </tr>
            
        </table>
    </div>

</div>

    <?php
    $lampiran_alat = $p['lampiran_alat'] ?? ($p['file_lampiran_alat'] ?? '');
    if (!empty($lampiran_alat)) {
        $this->load->view('pengujian/partials_lampiran_lcp', array(
            'lampiran_alat'     => $lampiran_alat,
            'sampel'            => $sampel,
            'hasil'             => $hasil,
            'penguji'           => $penguji,
            'verifier'          => $verifier,
            'ttd_penguji_img'   => $ttd_penguji_img ?? '',
            'ttd_penyelia_img'  => $ttd_penyelia_img ?? '',
            'status_sesi'       => $status_sesi ?? 'Draft'
        ));
    }
    ?>
</body>
</html>
