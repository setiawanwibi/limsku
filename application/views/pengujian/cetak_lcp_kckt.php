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

// Data tabel HPLC
$tabel_hplc = isset($p['tabel_hplc']) && is_array($p['tabel_hplc']) ? $p['tabel_hplc'] : array();

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
                    <?php echo nl2br(html_escape($p['gradient_komposisi'] ?? '-')); ?>
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
    </table>

    <!-- TABEL PENIMBANGAN & PENGUKURAN HPLC -->
    <table class="table-bordered">
        <thead>
            <tr>
                <th rowspan="2" style="width: 22%;">Nama Zat</th>
                <th colspan="3" style="width: 27%;">Bobot (g)</th>
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
            $rows = !empty($tabel_hplc) ? $tabel_hplc : array(
                array('kategori' => 'Baku Pembanding'),
                array('kategori' => 'Baku Internal'),
                array('kategori' => 'Zat Uji Replikasi 1'),
                array('kategori' => 'Zat Uji Replikasi 2'),
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

                $fp = isset($r['faktor_pengenceran']) && is_numeric($r['faktor_pengenceran']) ? (float)$r['faktor_pengenceran'] : ($r['faktor_pengenceran'] ?? '-');
                $vol = isset($r['vol_injeksi']) ? $r['vol_injeksi'] : '20';
                $rt = isset($r['waktu_retensi']) ? $r['waktu_retensi'] : '-';
                $respon = isset($r['respon_puncak']) && is_numeric($r['respon_puncak']) ? number_format((float)$r['respon_puncak'], 2, '.', ',') : ($r['respon_puncak'] ?? '-');
                $lmbd = isset($r['lambda_nm']) ? $r['lambda_nm'] : ($p['panjang_gelombang'] ?? '-');
            ?>
            <tr>
                <td><strong><?php echo html_escape($kat); ?></strong></td>
                <td class="text-right"><?php echo $w_zat; ?></td>
                <td class="text-right"><?php echo $w_sisa; ?></td>
                <td class="text-right text-bold"><?php echo $b_bersih; ?></td>
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

    <!-- RUMUS DAN DETAIL PERHITUNGAN -->
    <div class="rumus-box">
        <strong>Rumus Perhitungan:</strong><br>
        <code>Kadar Rata-rata = (Lu / Lb) &times; (Bb / Bu) &times; (Fu / Fb) &times; (Br / Ke) &times; Kb &times; 100%</code><br>
        <span style="font-size: 6.8pt; color: #555;">
            Lu = Respon Puncak Uji | Lb = Respon Puncak Baku | Bb = Bobot Baku | Bu = Bobot Uji | Fu = Faktor Pengenceran Uji | Fb = Faktor Pengenceran Baku | Br = Bobot Rata-rata Tablet (<strong><?php echo html_escape($p['bobot_rata_tablet'] ?? '-'); ?> mg</strong>) | Ke = Klaim Etiket (<strong><?php echo html_escape($p['klaim_etiket'] ?? '-'); ?> mg</strong>) | Kb = Kadar Baku Terkoreksi (<strong><?php echo html_escape($p['baku_kadar_terkoreksi'] ?? '-'); ?>%</strong>)
        </span>
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
            <td>Kadar Rata-rata (mg/tab)</td>
            <td>:</td>
            <td style="font-weight: bold; font-size: 8.5pt; color: #004b87;"><?php echo html_escape($p['kadar_rata_mg'] ?? '-'); ?> mg/tablet</td>
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
            <tr>
                <td colspan="2" style="font-weight: bold; padding-top: 2px;">POM-06.02/CFM.01/SOP.01/F.01A.12</td>
            </tr>
        </table>
    </div>

</div>

</body>
</html>
