<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$d = array();
if (isset($hasil['data_pengujian']) && is_array($hasil['data_pengujian'])) {
    $d = $hasil['data_pengujian'];
} elseif (isset($hasil['hasil']) && is_array($hasil['hasil'])) {
    $d = $hasil['hasil'];
}

$val = function ($key, $default = '') use ($d) {
    if (!isset($d[$key]) || $d[$key] === '' || $d[$key] === null) {
        return $default;
    }
    return $d[$key];
};

$evalNum = function ($raw) {
    if ($raw === null || $raw === '' || $raw === '-') return null;
    if (is_numeric($raw)) return (float)$raw;
    $s = str_replace(',', '.', trim((string)$raw));
    if (preg_match('/^[0-9+\-*\/().\s]+$/', $s)) {
        try {
            $res = @eval('return (' . $s . ');');
            if (is_numeric($res) && is_finite((float)$res)) return (float)$res;
        } catch (Throwable $e) {
            return null;
        }
    }
    return is_numeric($s) ? (float)$s : null;
};

$fmt = function ($num, $dec = 2, $empty = '-') {
    if ($num === null || $num === '' || !is_numeric($num)) return $empty;
    return number_format((float)$num, $dec, '.', ',');
};

$nama_sampel   = $sesi['nama_sampel'] ?? ($hasil['nama_sampel'] ?? '-');
$no_kode       = $sesi['kode_sampel'] ?? ($sesi['no_kode_sampel'] ?? '-');
$no_bets       = $sesi['no_bets'] ?? ($d['no_bets'] ?? '-');
$zat_uji_nama  = $hasil['nama_pengujian'] ?? ($sesi['nama_pengujian'] ?? $val('baku_pembanding', 'Pewarna Simultan'));
$tgl_uji_raw   = $hasil['tanggal_uji'] ?? ($sesi['tanggal_uji'] ?? ($sesi['created_at'] ?? ''));
$tgl_uji       = !empty($tgl_uji_raw) ? date('d-m-Y', strtotime($tgl_uji_raw)) : '-';

$kondisi_analisis = $val('kondisi_analisis', 'Sesuai prosedur');
$suhu_mode        = $val('suhu', 'Suhu Terkontrol');
$suhu_nilai       = $val('suhu_nilai', '35');
$fase_mode        = $val('fase_gerak_mode', 'Gradient');

$rows_induk  = isset($d['tabel_baku_induk_antara']) && is_array($d['tabel_baku_induk_antara']) ? $d['tabel_baku_induk_antara'] : array();
$rows_kerja  = isset($d['tabel_baku_kerja']) && is_array($d['tabel_baku_kerja']) ? $d['tabel_baku_kerja'] : array();
$rows_uji    = isset($d['tabel_zat_uji']) && is_array($d['tabel_zat_uji']) ? $d['tabel_zat_uji'] : array();
$rows_spiked = isset($d['tabel_spiked_sample']) && is_array($d['tabel_spiked_sample']) ? $d['tabel_spiked_sample'] : array();
$rows_grad   = isset($d['tabel_gradient']) && is_array($d['tabel_gradient']) ? $d['tabel_gradient'] : array();

$pts = array();
foreach ($rows_kerja as $rk) {
    $cx = $evalNum($rk['konsentrasi'] ?? null);
    $cy = $evalNum($rk['respon_puncak'] ?? null);
    if ($cx !== null && $cy !== null) {
        $pts[] = array('x' => $cx, 'y' => $cy);
    }
}

$slope     = $evalNum($val('slope', ''));
$intercept = $evalNum($val('intercept', ''));
$r_val     = $evalNum($val('koefisien_korelasi', ''));
$r2_val    = $evalNum($val('koefisien_determinasi', ''));

if (count($pts) >= 2 && ($slope === null || $intercept === null)) {
    $n = count($pts);
    $sx = 0; $sy = 0;
    foreach ($pts as $p) { $sx += $p['x']; $sy += $p['y']; }
    $mx = $sx / $n; $my = $sy / $n;
    $sxx = 0; $syy = 0; $sxy = 0;
    foreach ($pts as $p) {
        $dx = $p['x'] - $mx; $dy = $p['y'] - $my;
        $sxx += $dx * $dx; $syy += $dy * $dy; $sxy += $dx * $dy;
    }
    if ($sxx > 0) {
        $slope = $sxy / $sxx;
        $intercept = $my - $slope * $mx;
        $r_val = $syy > 0 ? ($sxy / sqrt($sxx * $syy)) : null;
        $r2_val = $r_val !== null ? ($r_val * $r_val) : null;
    }
}

$persamaan_regresi = $val('persamaan_regresi', '');
if (empty($persamaan_regresi) && $slope !== null && $intercept !== null) {
    $sign = $intercept >= 0 ? ' + ' : ' - ';
    $persamaan_regresi = 'y = ' . number_format($slope, 4, '.', '') . 'x' . $sign . number_format(abs($intercept), 4, '.', '');
}

$k1       = $evalNum($val('kadar_replikasi_1', ''));
$k2       = $evalNum($val('kadar_replikasi_2', ''));
$k_rata   = $evalNum($val('kadar_rata_mg', ''));
$rpd_val  = $evalNum($val('rpd', ''));
$horwitz  = $evalNum($val('syarat_horwitz', ''));

$vol_spike      = $evalNum($val('spike_vol_baku_ml', ''));
$w_spike        = $evalNum($val('spike_bobot_sampel_g', ''));
$k_tambah       = $evalNum($val('spike_kadar_ditambahkan', ''));
$k_tanpa_spike  = $evalNum($val('spike_kadar_tanpa_spike', '0'));
$rec_1          = $evalNum($val('recovery_1', ''));
$rec_2          = $evalNum($val('recovery_2', ''));

$nama_analis   = $sesi['nama_analis'] ?? ($hasil['nama_analis'] ?? '-');
$nip_analis    = $sesi['nip_analis'] ?? '';
$nama_penyelia = $sesi['nama_penyelia'] ?? ($hasil['nama_penyelia'] ?? '-');
$nip_penyelia  = $sesi['nip_penyelia'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>LCP KCKT Pewarna Simultan - <?php echo html_escape($no_kode); ?></title>
    <style>
        @page { size: A4 portrait; margin: 8mm 10mm; }
        * { box-sizing: border-box; }
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 8pt;
            color: #000;
            background: #525659;
            margin: 0;
            padding: 15px 0;
        }
        .no-print {
            width: 210mm;
            margin: 0 auto 10px auto;
            display: flex;
            justify-content: flex-end;
            gap: 8px;
        }
        .btn-print {
            background: #1d4ed8;
            color: #fff;
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
            background: #fff;
            padding: 8mm 10mm;
            box-shadow: 0 0 10px rgba(0,0,0,0.3);
        }
        @media print {
            body { background: transparent; padding: 0; }
            .no-print { display: none !important; }
            .page-container { box-shadow: none; width: 100%; min-height: auto; padding: 0; margin: 0; }
        }
        .header-kop {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 4px;
            margin-bottom: 6px;
        }
        .header-kop .doc-no { font-size: 7.5pt; text-align: right; font-weight: bold; }
        .header-kop .title-1 { font-size: 10pt; font-weight: bold; }
        .header-kop .title-2 { font-size: 10.5pt; font-weight: bold; margin-top: 2px; }
        .table-info { width: 100%; border-collapse: collapse; font-size: 7.5pt; margin-bottom: 5px; }
        .table-info td { padding: 1.5px 3px; vertical-align: top; }
        .table-grid { width: 100%; border-collapse: collapse; font-size: 7pt; margin: 4px 0; }
        .table-grid th, .table-grid td { border: 1px solid #000; padding: 2px 3px; vertical-align: middle; }
        .table-grid th { background: #f2f2f2; text-align: center; font-weight: bold; }
        .tc { text-align: center; }
        .tr { text-align: right; }
        .tb { font-weight: bold; }
        .sec-title { font-weight: bold; font-size: 7.8pt; margin-top: 5px; margin-bottom: 2px; }
        .box-calc { border: 1px solid #000; padding: 4px 6px; margin-top: 4px; font-size: 7.5pt; }
        .two-col { display: flex; gap: 8px; align-items: stretch; margin-top: 4px; }
        .two-col > div { flex: 1; }
        .ttd-table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 8pt; }
        .ttd-table td { width: 50%; text-align: center; vertical-align: top; padding: 4px; }
        .ttd-img { max-height: 48px; max-width: 140px; display: block; margin: 4px auto; }
        .cb { display: inline-block; width: 10px; height: 10px; border: 1px solid #000; text-align: center; line-height: 9px; font-size: 7pt; margin-right: 3px; }
    </style>
</head>
<body>
    <div class="no-print">
        <button type="button" class="btn-print" onclick="window.print()">Cetak / Simpan PDF</button>
    </div>

    <div class="page-container">
        <div class="header-kop">
            <div class="doc-no">POM-06.02/CFM.01/SOP.01/F.01A.12</div>
            <div class="title-1">LAMPIRAN CATATAN PENGUJIAN</div>
            <div class="title-2">KROMATOGRAFI CAIR KINERJA TINGGI (PEWARNA SIMULTAN PANGAN)</div>
        </div>

        <table class="table-info">
            <tr>
                <td style="width:16%;">Nama Sampel</td><td style="width:2%;">:</td><td style="width:32%;" class="tb"><?php echo html_escape($nama_sampel); ?></td>
                <td style="width:16%;">No. Kode Sampel</td><td style="width:2%;">:</td><td style="width:32%;" class="tb"><?php echo html_escape($no_kode); ?></td>
            </tr>
            <tr>
                <td>Zat yang diuji</td><td>:</td><td class="tb"><?php echo html_escape($zat_uji_nama); ?></td>
                <td>No. Bets</td><td>:</td><td><?php echo html_escape($no_bets); ?></td>
            </tr>
            <tr>
                <td>Tanggal Uji</td><td>:</td><td><?php echo html_escape($tgl_uji); ?></td>
                <td>Pelarut</td><td>:</td><td><?php echo html_escape($val('pelarut', 'Metanol – NH4OH 2 M (80:20)')); ?></td>
            </tr>
        </table>

        <table class="table-info" style="border: 1px solid #000; padding: 3px;">
            <tr>
                <td style="width:16%;">Merk / Tipe Alat</td><td style="width:2%;">:</td>
                <td style="width:32%;"><?php echo html_escape($val('alat_merk', 'Thermo') . ' / ' . $val('alat_tipe', 'Ultimate 3000')); ?></td>
                <td style="width:16%;">Detektor</td><td style="width:2%;">:</td>
                <td style="width:32%;"><?php echo html_escape($val('detektor', 'PDA (Dionex Ultimate3000)')); ?></td>
            </tr>
            <tr>
                <td>Kondisi Analisis</td><td>:</td>
                <td>
                    <span class="cb"><?php echo ($kondisi_analisis === 'Sesuai prosedur') ? '✓' : '&nbsp;'; ?></span>Sesuai prosedur &nbsp;
                    <span class="cb"><?php echo ($kondisi_analisis !== 'Sesuai prosedur') ? '✓' : '&nbsp;'; ?></span>Adjusment
                    <?php if ($val('kondisi_adjustment') !== ''): ?> (<?php echo html_escape($val('kondisi_adjustment')); ?>)<?php endif; ?>
                </td>
                <td>Suhu Kolom</td><td>:</td>
                <td>
                    <span class="cb"><?php echo ($suhu_mode === 'Kamar') ? '✓' : '&nbsp;'; ?></span>Kamar &nbsp;
                    <span class="cb"><?php echo ($suhu_mode !== 'Kamar') ? '✓' : '&nbsp;'; ?></span>Suhu Terkontrol: <?php echo html_escape($suhu_nilai); ?> °C
                </td>
            </tr>
            <tr>
                <td>Kolom</td><td>:</td><td><?php echo html_escape($val('kolom', '-')); ?></td>
                <td>Laju Aliran / λ</td><td>:</td><td><?php echo html_escape($val('laju_aliran', '1 mL/menit') . ' / ' . $val('panjang_gelombang_uji', '426 nm')); ?></td>
            </tr>
            <tr>
                <td>Fase Gerak (<?php echo html_escape($fase_mode); ?>)</td><td>:</td>
                <td colspan="4">
                    <?php if ($fase_mode === 'Gradient'): ?>
                        A: <?php echo html_escape($val('fase_gerak_a', '-')); ?> &nbsp;|&nbsp; B: <?php echo html_escape($val('fase_gerak_b', '-')); ?>
                    <?php else: ?>
                        <?php echo html_escape($val('fase_gerak_isokratik', '-')); ?>
                    <?php endif; ?>
                </td>
            </tr>
        </table>

        <?php if ($fase_mode === 'Gradient' && !empty($rows_grad)): ?>
            <table class="table-grid">
                <thead>
                    <tr><th>Waktu (menit)</th><th>% A</th><th>% B</th><th>% C</th><th>% D</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($rows_grad as $rg): ?>
                        <?php if (implode('', $rg) === '') continue; ?>
                        <tr>
                            <td class="tc"><?php echo html_escape($rg['waktu_menit'] ?? ''); ?></td>
                            <td class="tc"><?php echo html_escape($rg['persen_a'] ?? ''); ?></td>
                            <td class="tc"><?php echo html_escape($rg['persen_b'] ?? ''); ?></td>
                            <td class="tc"><?php echo html_escape($rg['persen_c'] ?? ''); ?></td>
                            <td class="tc"><?php echo html_escape($rg['persen_d'] ?? ''); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <table class="table-info" style="border: 1px solid #000;">
            <tr>
                <td style="width:18%;">Baku Pembanding</td><td style="width:2%;">:</td><td style="width:30%;" class="tb"><?php echo html_escape($val('baku_pembanding', '-')); ?></td>
                <td style="width:18%;">Baku Internal</td><td style="width:2%;">:</td><td style="width:30%;"><?php echo html_escape($val('baku_internal', '-')); ?></td>
            </tr>
            <tr>
                <td>No. Kontrol</td><td>:</td><td><?php echo html_escape($val('baku_no_kontrol', '-')); ?></td>
                <td>No. Kontrol Internal</td><td>:</td><td><?php echo html_escape($val('baku_internal_no_kontrol', '-')); ?></td>
            </tr>
            <tr>
                <td>Kadar / SP</td><td>:</td><td><?php echo html_escape($val('baku_kadar', '-') . ' / SP: ' . $val('baku_sp', '-')); ?></td>
                <td>Status Lampiran</td><td>:</td>
                <td>
                    <span class="cb"><?php echo !empty($d['status_kromatogram_uks']) ? '✓' : '&nbsp;'; ?></span>UKS
                    <span class="cb"><?php echo !empty($d['status_kromatogram_baku_sampel']) ? '✓' : '&nbsp;'; ?></span>Baku/Sampel
                    <span class="cb"><?php echo !empty($d['status_regresi_kurva']) ? '✓' : '&nbsp;'; ?></span>Regresi
                </td>
            </tr>
        </table>

        <div class="sec-title">1. Baku Pembanding, Baku Induk, Baku Antara &amp; Deret Baku Kerja</div>
        <table class="table-grid">
            <thead>
                <tr>
                    <th>Larutan</th>
                    <th>Wadah + Zat (mg)</th>
                    <th>Wadah + Sisa (mg)</th>
                    <th>Bobot Bersih (mg)</th>
                    <th>FP</th>
                    <th>Konsentrasi (µg/mL)</th>
                    <th>Vol. Suntik (µL)</th>
                    <th>RT (menit)</th>
                    <th>Respon Puncak</th>
                    <th>λ (nm)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows_induk as $ri): ?>
                    <tr>
                        <td class="tb"><?php echo html_escape($ri['tahap'] ?? ''); ?></td>
                        <td class="tr"><?php echo html_escape($ri['wadah_zat'] ?? '-'); ?></td>
                        <td class="tr"><?php echo html_escape($ri['wadah_sisa'] ?? '-'); ?></td>
                        <td class="tr"><?php echo html_escape($ri['bobot_bersih'] ?? '-'); ?></td>
                        <td class="tc"><?php echo html_escape($ri['faktor_pengenceran'] ?? '-'); ?></td>
                        <td class="tr tb"><?php echo html_escape($ri['konsentrasi'] ?? '-'); ?></td>
                        <td class="tc"><?php echo html_escape($ri['vol_suntik'] ?? '-'); ?></td>
                        <td class="tc"><?php echo html_escape($ri['waktu_retensi'] ?? '-'); ?></td>
                        <td class="tr"><?php echo html_escape($ri['respon_puncak'] ?? '-'); ?></td>
                        <td class="tc"><?php echo html_escape($ri['lambda_nm'] ?? '-'); ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php foreach ($rows_kerja as $rk): ?>
                    <tr>
                        <td><?php echo html_escape($rk['tahap'] ?? ''); ?></td>
                        <td class="tc">-</td>
                        <td class="tc">-</td>
                        <td class="tc">-</td>
                        <td class="tc"><?php echo html_escape($rk['faktor_pengenceran'] ?? '-'); ?></td>
                        <td class="tr"><?php echo html_escape($rk['konsentrasi'] ?? '-'); ?></td>
                        <td class="tc"><?php echo html_escape($rk['vol_suntik'] ?? '-'); ?></td>
                        <td class="tc"><?php echo html_escape($rk['waktu_retensi'] ?? '-'); ?></td>
                        <td class="tr"><?php echo html_escape($rk['respon_puncak'] ?? '-'); ?></td>
                        <td class="tc"><?php echo html_escape($rk['lambda_nm'] ?? '-'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="sec-title">2. Penimbangan &amp; Pengukuran Zat Uji (Duplo)<?php echo !empty($rows_spiked) ? ' serta Spiked Sample' : ''; ?></div>
        <table class="table-grid">
            <thead>
                <tr>
                    <th>Replikasi / Sampel</th>
                    <th>Wadah + Zat (g)</th>
                    <th>Wadah + Sisa (g)</th>
                    <th>Bobot Bersih w (g)</th>
                    <th>Faktor Pengenceran F</th>
                    <th>Konsentrasi X (µg/mL)</th>
                    <th>Vol. Suntik (µL)</th>
                    <th>RT (menit)</th>
                    <th>Respon Puncak Y</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows_uji as $ru): ?>
                    <tr>
                        <td class="tb"><?php echo html_escape($ru['replikasi'] ?? ''); ?></td>
                        <td class="tr"><?php echo html_escape($ru['wadah_zat'] ?? '-'); ?></td>
                        <td class="tr"><?php echo html_escape($ru['wadah_sisa'] ?? '-'); ?></td>
                        <td class="tr"><?php echo html_escape($ru['bobot_bersih'] ?? '-'); ?></td>
                        <td class="tc"><?php echo html_escape($ru['faktor_pengenceran'] ?? '-'); ?></td>
                        <td class="tr tb"><?php echo html_escape($ru['konsentrasi_terukur'] ?? '-'); ?></td>
                        <td class="tc"><?php echo html_escape($ru['vol_suntik'] ?? '-'); ?></td>
                        <td class="tc"><?php echo html_escape($ru['waktu_retensi'] ?? '-'); ?></td>
                        <td class="tr"><?php echo html_escape($ru['respon_puncak'] ?? '-'); ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php foreach ($rows_spiked as $rs): ?>
                    <?php if (($rs['wadah_zat'] ?? '') === '' && ($rs['respon_puncak'] ?? '') === '') continue; ?>
                    <tr>
                        <td><?php echo html_escape($rs['tahap'] ?? 'Spiked Sample'); ?></td>
                        <td class="tr"><?php echo html_escape($rs['wadah_zat'] ?? '-'); ?></td>
                        <td class="tr"><?php echo html_escape($rs['wadah_sisa'] ?? '-'); ?></td>
                        <td class="tr"><?php echo html_escape($rs['bobot_bersih'] ?? '-'); ?></td>
                        <td class="tc"><?php echo html_escape($rs['faktor_pengenceran'] ?? '-'); ?></td>
                        <td class="tr"><?php echo html_escape($rs['konsentrasi'] ?? '-'); ?></td>
                        <td class="tc"><?php echo html_escape($rs['vol_suntik'] ?? '-'); ?></td>
                        <td class="tc"><?php echo html_escape($rs['waktu_retensi'] ?? '-'); ?></td>
                        <td class="tr"><?php echo html_escape($rs['respon_puncak'] ?? '-'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="two-col">
            <div class="box-calc">
                <div class="tb" style="margin-bottom:3px;">Kurva Kalibrasi Baku Pewarna</div>
                <?php
                    $W = 300; $H = 155; $padL = 42; $padR = 12; $padT = 18; $padB = 26;
                    $plotW = $W - $padL - $padR; $plotH = $H - $padT - $padB;
                ?>
                <svg viewBox="0 0 <?php echo $W; ?> <?php echo $H; ?>" style="width:100%; height:auto; border:1px solid #ccc; background:#fff;">
                    <?php if (count($pts) >= 2 && $slope !== null && $intercept !== null): ?>
                        <?php
                            $xs = array_column($pts, 'x'); $ys = array_column($pts, 'y');
                            $minX = min(0, min($xs)); $maxX = max($xs) * 1.08;
                            $minY = min(0, min($ys)); $maxY = max($ys) * 1.10;
                            if ($maxX <= $minX) $maxX = $minX + 1;
                            if ($maxY <= $minY) $maxY = $minY + 1;
                            $sx = function($x) use ($padL, $plotW, $minX, $maxX) { return $padL + (($x - $minX) / ($maxX - $minX)) * $plotW; };
                            $sy = function($y) use ($padT, $plotH, $minY, $maxY) { return $padT + $plotH - (($y - $minY) / ($maxY - $minY)) * $plotH; };
                        ?>
                        <?php for ($i = 0; $i <= 4; $i++): ?>
                            <?php $gy = $minY + ($maxY - $minY) * ($i / 4); $py = $sy($gy); ?>
                            <line x1="<?php echo $padL; ?>" y1="<?php echo number_format($py, 1); ?>" x2="<?php echo $W - $padR; ?>" y2="<?php echo number_format($py, 1); ?>" stroke="#e5e7eb" stroke-width="0.8"/>
                            <text x="<?php echo $padL - 4; ?>" y="<?php echo number_format($py + 3, 1); ?>" text-anchor="end" font-size="6.5" fill="#444"><?php echo number_format($gy, 0, '.', ','); ?></text>
                        <?php endfor; ?>
                        <line x1="<?php echo $padL; ?>" y1="<?php echo $padT; ?>" x2="<?php echo $padL; ?>" y2="<?php echo $H - $padB; ?>" stroke="#000" stroke-width="1"/>
                        <line x1="<?php echo $padL; ?>" y1="<?php echo $H - $padB; ?>" x2="<?php echo $W - $padR; ?>" y2="<?php echo $H - $padB; ?>" stroke="#000" stroke-width="1"/>
                        <line x1="<?php echo number_format($sx($minX), 1); ?>" y1="<?php echo number_format($sy($slope * $minX + $intercept), 1); ?>"
                              x2="<?php echo number_format($sx($maxX * 0.96), 1); ?>" y2="<?php echo number_format($sy($slope * ($maxX * 0.96) + $intercept), 1); ?>"
                              stroke="#dc2626" stroke-width="1.3" stroke-dasharray="3,2"/>
                        <?php foreach ($pts as $p): ?>
                            <circle cx="<?php echo number_format($sx($p['x']), 1); ?>" cy="<?php echo number_format($sy($p['y']), 1); ?>" r="2.8" fill="#1d4ed8"/>
                        <?php endforeach; ?>
                        <text x="<?php echo $padL + 6; ?>" y="11" font-size="6.8" font-weight="bold" fill="#000">
                            <?php echo html_escape($persamaan_regresi); ?> | r = <?php echo $fmt($r_val, 5); ?>
                        </text>
                        <text x="<?php echo $padL + $plotW / 2; ?>" y="<?php echo $H - 6; ?>" text-anchor="middle" font-size="7" fill="#000">Konsentrasi (µg/mL)</text>
                    <?php else: ?>
                        <text x="<?php echo $W / 2; ?>" y="<?php echo $H / 2; ?>" text-anchor="middle" font-size="8" fill="#888">Data deret baku belum tersedia</text>
                    <?php endif; ?>
                </svg>
                <div style="margin-top:3px; font-size:7pt;">
                    Slope (b): <b><?php echo $fmt($slope, 4); ?></b> &nbsp;|&nbsp;
                    Intercept (a): <b><?php echo $fmt($intercept, 4); ?></b> &nbsp;|&nbsp;
                    r: <b><?php echo $fmt($r_val, 5); ?></b> (r ≥ 0.998) &nbsp;|&nbsp;
                    R²: <b><?php echo $fmt($r2_val, 5); ?></b>
                </div>
            </div>

            <div class="box-calc">
                <div class="tb" style="margin-bottom:3px;">Perhitungan Kadar, Presisi &amp; Recovery</div>
                <div style="font-size:6.8pt; margin-bottom:4px;">
                    Rumus: X = (Y - a) / b &nbsp;;&nbsp; Kadar (mg/kg) = (X × F) / w &nbsp;;&nbsp; Recovery = ((K_spike - K_0) / C_add) × 100%
                </div>
                <table class="table-grid" style="margin:0;">
                    <tr><td>Kadar Pewarna 1</td><td class="tr tb"><?php echo $fmt($k1, 2); ?> mg/kg</td></tr>
                    <tr><td>Kadar Pewarna 2</td><td class="tr tb"><?php echo $fmt($k2, 2); ?> mg/kg</td></tr>
                    <tr><td class="tb">Rata-rata Kadar dalam Sampel</td><td class="tr tb"><?php echo $fmt($k_rata, 2); ?> mg/kg</td></tr>
                    <tr><td>Presisi Duplo (RPD / RSD*)</td><td class="tr"><?php echo $fmt($rpd_val, 2); ?> %</td></tr>
                    <tr><td>Syarat 1/2 CV Horwitz</td><td class="tr"><?php echo $fmt($horwitz, 2); ?> %</td></tr>
                    <tr><td>Kadar Baku Ditambahkan (Spike)</td><td class="tr"><?php echo $fmt($k_tambah, 2); ?> mg/kg (<?php echo $fmt($vol_spike, 2); ?> mL / <?php echo $fmt($w_spike, 2); ?> g)</td></tr>
                    <tr><td>Recovery 1 / Recovery 2 (Syarat 90–107%)</td><td class="tr tb"><?php echo $fmt($rec_1, 2); ?>% / <?php echo $fmt($rec_2, 2); ?>%</td></tr>
                </table>
            </div>
        </div>

        <table class="table-grid" style="margin-top:5px;">
            <tr>
                <td style="width:28%;" class="tb">LOD / LOQ</td>
                <td>LOD: <?php echo html_escape($val('lod_nilai', '-') . ' ' . $val('lod_satuan', 'mg/kg')); ?> &nbsp;&nbsp;|&nbsp;&nbsp; LOQ: <?php echo html_escape($val('loq_nilai', '-') . ' ' . $val('loq_satuan', 'mg/kg')); ?></td>
            </tr>
            <tr>
                <td class="tb">Hasil Uji (Identifikasi)</td>
                <td><?php echo html_escape($val('hasil_uji_identifikasi', '-')); ?></td>
            </tr>
            <tr>
                <td class="tb">Hasil Uji ± Ketidakpastian + Simple Acceptance/Guard Band*</td>
                <td class="tb"><?php echo html_escape($val('hasil_uji_kadar_akhir', '-')); ?></td>
            </tr>
            <tr>
                <td class="tb">Pustaka Metode / Persyaratan</td>
                <td>Metode: <?php echo html_escape($val('pustaka_metode', 'MA PPOM 67/PA/17')); ?> &nbsp;&nbsp;|&nbsp;&nbsp; Persyaratan: <?php echo html_escape($val('pustaka_persyaratan', 'PBPOM No. 11 Tahun 2019')); ?></td>
            </tr>
            <tr>
                <td class="tb">Syarat (Tolerance / Acceptance Limit)</td>
                <td><?php echo nl2br(html_escape($val('syarat', '-'))); ?></td>
            </tr>
            <tr>
                <td class="tb">Kesimpulan</td>
                <td class="tb" style="font-size:8.5pt;"><?php echo html_escape($val('kesimpulan', '-')); ?></td>
            </tr>
        </table>

        <table class="ttd-table">
            <tr>
                <td>
                    Mengetahui,<br>Penyelia / Pengawas Mutu
                    <?php if (!empty($ttd_penyelia)): ?>
                        <img src="<?php echo html_escape($ttd_penyelia); ?>" class="ttd-img" alt="TTD Penyelia">
                    <?php else: ?>
                        <div style="height:48px;"></div>
                    <?php endif; ?>
                    <div class="tb" style="text-decoration:underline;"><?php echo html_escape($nama_penyelia); ?></div>
                    <?php if (!empty($nip_penyelia)): ?><div>NIP. <?php echo html_escape($nip_penyelia); ?></div><?php endif; ?>
                </td>
                <td>
                    Bandar Lampung, <?php echo html_escape($tgl_uji); ?><br>Analis Penguji
                    <?php if (!empty($ttd_analis)): ?>
                        <img src="<?php echo html_escape($ttd_analis); ?>" class="ttd-img" alt="TTD Analis">
                    <?php else: ?>
                        <div style="height:48px;"></div>
                    <?php endif; ?>
                    <div class="tb" style="text-decoration:underline;"><?php echo html_escape($nama_analis); ?></div>
                    <?php if (!empty($nip_analis)): ?><div>NIP. <?php echo html_escape($nip_analis); ?></div><?php endif; ?>
                </td>
            </tr>
        </table>

        <?php
        $lampiran_path = APPPATH . 'views/pengujian/partials_lampiran_lcp.php';
        if (file_exists($lampiran_path)) {
            include $lampiran_path;
        }
        ?>
    </div>
</body>
</html>
