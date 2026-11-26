<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * View Cetak / Simpan PDF Lembar Catatan Pengujian (LCP) Pangan
 * SPEKTROFOTOMETRI SERAPAN ATOM (AAS) - Cemaran Logam (As, Cd, Hg, Pb, Sn)
 * Replikasi presisi format resmi BPOM: POM-06.02/CFM.01/SOP.01/F.01A.14
 */

$p = !empty($hasil['data_hasil']) ? json_decode($hasil['data_hasil'], true) : array();
if (isset($p['parameter_values']) && is_array($p['parameter_values'])) {
    $p = array_merge($p, $p['parameter_values']);
}

$resolve_ttd = function($sig) {
    if (empty($sig) || empty($sig['signature_file'])) return '';
    $clean = str_replace(array('../', '..\\'), '', $sig['signature_file']);
    $rel   = (strpos($clean, 'uploads/signatures/') === 0) ? $clean : ('uploads/signatures/' . ltrim($clean, '/\\'));
    $full  = FCPATH . str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $rel);
    return (file_exists($full) && is_file($full)) ? base_url($rel) : '';
};

$ttd_penguji_img  = $resolve_ttd($signature_penguji ?? null);
$ttd_penyelia_img = $resolve_ttd($signature_verifier ?? null);
$tgl_lap = !empty($p['tanggal_laporan']) ? date('d F Y', strtotime($p['tanggal_laporan'])) : date('d F Y', strtotime($hasil['updated_at'] ?? 'now'));
$tgl_uji = !empty($sesi['waktu_mulai']) ? date('d F Y', strtotime($sesi['waktu_mulai'])) : date('d F Y');

$tabel_baku    = isset($p['tabel_baku_kurva']) && is_array($p['tabel_baku_kurva']) ? $p['tabel_baku_kurva'] : array();
$tabel_zat_uji = isset($p['tabel_zat_uji']) && is_array($p['tabel_zat_uji']) ? $p['tabel_zat_uji'] : array();
$tabel_spike   = isset($p['tabel_spiked_sample']) && is_array($p['tabel_spiked_sample']) ? $p['tabel_spiked_sample'] : array();

$pts = array();
foreach ($tabel_baku as $r) {
    $cx = (float)str_replace(',', '.', (string)($r['konsentrasi'] ?? 0));
    $cy = (float)str_replace(',', '.', (string)($r['respon_puncak'] ?? 0));
    if ($cx > 0 && $cy != 0) $pts[] = array('x' => $cx, 'y' => $cy);
}
$slope     = (float)str_replace(',', '.', (string)($p['regresi_slope'] ?? 0));
$intercept = (float)str_replace(',', '.', (string)($p['regresi_intercept'] ?? 0));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak LCP AAS - <?php echo html_escape($sampel['nama_sampel'] ?? 'Sampel'); ?></title>
    <style>
        @page { size: A4 portrait; margin: 0; }
        *, *::before, *::after { box-sizing: border-box; -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
        body { margin: 0; padding: 12px 0; background: #525659; font-family: Arial, Helvetica, sans-serif; font-size: 7.5pt; color: #000; line-height: 1.25; }
        .no-print { position: fixed; top: 12px; right: 12px; z-index: 9999; background: #fff; padding: 8px 12px; border-radius: 6px; box-shadow: 0 4px 12px rgba(0,0,0,0.25); }
        .no-print button { background: #004b87; color: #fff; border: none; padding: 6px 12px; font-size: 11px; font-weight: 600; border-radius: 4px; cursor: pointer; }
        .page-container { width: 210mm; min-height: 297mm; margin: 0 auto; background: #fff; padding: 8mm 11mm; box-shadow: 0 0 10px rgba(0,0,0,0.3); }
        @media print { body { background: none; padding: 0; } .no-print { display: none !important; } .page-container { box-shadow: none; margin: 0; } }
        .header-title { text-align: center; font-weight: bold; margin-bottom: 6px; border-bottom: 1.5px solid #000; padding-bottom: 4px; }
        .header-title .l1 { font-size: 10pt; }
        .header-title .l2 { font-size: 9pt; }
        .header-title .l3 { font-size: 8pt; font-weight: normal; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 5px; }
        .meta-tbl td { padding: 1.5px 3px; vertical-align: top; }
        .grid-tbl th, .grid-tbl td { border: 1px solid #000; padding: 2px 4px; font-size: 7pt; }
        .grid-tbl th { background: #f2f4f7; text-align: center; font-weight: bold; }
        .sec-hdr { font-weight: bold; font-size: 7.5pt; margin: 5px 0 2px; background: #e9ecef; padding: 2px 5px; border-left: 3px solid #004b87; }
        .num { text-align: right; } .ctr { text-align: center; }
        .ttd-img { max-height: 34px; max-width: 90px; display: block; margin: 2px auto; }
        .footer-code { font-size: 6.8pt; color: #444; margin-top: 6px; display: flex; justify-content: space-between; }
    </style>
</head>
<body>
    <div class="no-print"><button onclick="window.print()">Cetak / Simpan PDF</button></div>
    <div class="page-container">
        <div class="header-title">
            <div class="l1">LAMPIRAN CATATAN PENGUJIAN</div>
            <div class="l2">SPEKTROFOTOMETRI SERAPAN ATOM (AAS)</div>
            <div class="l3">Laboratorium Kimia Balai Besar/Balai/Loka POM di Bandar Lampung</div>
        </div>

        <table class="meta-tbl">
            <tr>
                <td width="18%">Nama Sampel</td><td width="2%">:</td><td width="34%"><strong><?php echo html_escape($sampel['nama_sampel'] ?? '-'); ?></strong></td>
                <td width="16%">No Kode Sampel</td><td width="2%">:</td><td width="28%"><?php echo html_escape($sampel['kode_sampel_manual'] ?: ($sampel['no_sampel'] ?? '-')); ?></td>
            </tr>
            <tr>
                <td>No Bets</td><td>:</td><td><?php echo html_escape($sampel['no_bets'] ?? '-'); ?></td>
                <td>Tanggal Uji</td><td>:</td><td><?php echo html_escape($tgl_uji); ?></td>
            </tr>
            <tr>
                <td>Zat yang diuji</td><td>:</td><td><strong>PK Cemaran Logam <?php echo html_escape($p['elemen_logam'] ?? ''); ?></strong></td>
                <td>Merk / Tipe Alat</td><td>:</td><td><?php echo html_escape(($p['merk_alat'] ?? '-') . ' / ' . ($p['tipe_seri'] ?? '-')); ?></td>
            </tr>
            <tr>
                <td>Elemen / Wavelength</td><td>:</td><td><?php echo html_escape(($p['elemen_logam'] ?? '-') . ' / ' . ($p['wavelength'] ?? '-')); ?></td>
                <td>Metode AAS</td><td>:</td><td><?php echo html_escape($p['metode_aas'] ?? '-'); ?></td>
            </tr>
            <tr>
                <td>Baku Pembanding</td><td>:</td><td colspan="4"><?php echo html_escape($p['baku_nama'] ?? '-'); ?></td>
            </tr>
            <tr>
                <td>Pelarut</td><td>:</td><td><?php echo html_escape($p['pelarut'] ?? '-'); ?></td>
                <td>Larutan Pereduksi</td><td>:</td><td><?php echo html_escape($p['larutan_pereduksi'] ?? '-'); ?></td>
            </tr>
        </table>

        <div class="sec-hdr">1. Baku Pembanding &amp; Regresi Kurva Baku AAS (Blanko Baku: <?php echo html_escape($p['blanko_baku_serapan'] ?? '0'); ?>)</div>
        <table class="grid-tbl">
            <thead>
                <tr><th>Tahap</th><th>Faktor Pengenceran</th><th>Konsentrasi (Xi)</th><th>Serapan / Absorbansi (Yi)</th></tr>
            </thead>
            <tbody>
                <?php foreach ($tabel_baku as $b): ?>
                <tr>
                    <td><?php echo html_escape($b['tahap'] ?? ''); ?></td>
                    <td class="ctr"><?php echo html_escape($b['faktor_pengenceran'] ?? ''); ?></td>
                    <td class="num"><?php echo html_escape($b['konsentrasi'] ?? ''); ?></td>
                    <td class="num"><?php echo html_escape($b['respon_puncak'] ?? ''); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <table class="grid-tbl">
            <tr>
                <td width="48%">
                    <strong>Regresi Linier:</strong> <code>y = <?php echo $slope ? number_format($slope, 5, '.', '') : '-'; ?>x <?php echo ($intercept >= 0 ? '+ ' : '- ') . number_format(abs($intercept), 5, '.', ''); ?></code><br>
                    <strong>Corr. Coeff (r):</strong> <?php echo html_escape($p['regresi_r'] ?? '-'); ?> (Syarat <?php echo html_escape($p['syarat_r'] ?? '≥ 0,99'); ?>)<br>
                    <strong>SD Residual (Sy/x):</strong> <?php echo html_escape($p['sy_x'] ?? '-'); ?> | <strong>Vx0:</strong> <?php echo html_escape($p['vx0'] ?? '-'); ?> (≤ 0,05)
                </td>
                <td width="52%" class="ctr" style="padding: 3px;">
                    <?php if (count($pts) >= 2):
                        $w = 250; $h = 72; $pad = 12;
                        $xs = array_column($pts, 'x'); $ys = array_column($pts, 'y');
                        $minX = min($xs); $maxX = max($xs); $minY = min($ys); $maxY = max($ys);
                        $dx = ($maxX - $minX) ?: 1; $dy = ($maxY - $minY) ?: 1;
                        $mapX = function($x) use ($minX, $dx, $w, $pad) { return $pad + (($x - $minX) / $dx) * ($w - 2 * $pad); };
                        $mapY = function($y) use ($minY, $dy, $h, $pad) { return ($h - $pad) - (($y - $minY) / $dy) * ($h - 2 * $pad); };
                    ?>
                    <svg width="<?php echo $w; ?>" height="<?php echo $h; ?>" style="background:#fafbfc; border:1px solid #ddd;">
                        <line x1="<?php echo $pad; ?>" y1="<?php echo $h-$pad; ?>" x2="<?php echo $w-$pad; ?>" y2="<?php echo $h-$pad; ?>" stroke="#999" stroke-width="0.8"/>
                        <line x1="<?php echo $pad; ?>" y1="<?php echo $pad; ?>" x2="<?php echo $pad; ?>" y2="<?php echo $h-$pad; ?>" stroke="#999" stroke-width="0.8"/>
                        <line x1="<?php echo round($mapX($minX),1); ?>" y1="<?php echo round($mapY($slope*$minX+$intercept),1); ?>" x2="<?php echo round($mapX($maxX),1); ?>" y2="<?php echo round($mapY($slope*$maxX+$intercept),1); ?>" stroke="#198754" stroke-width="1.4"/>
                        <?php foreach ($pts as $pt): ?>
                            <circle cx="<?php echo round($mapX($pt['x']),1); ?>" cy="<?php echo round($mapY($pt['y']),1); ?>" r="2.5" fill="#0d6efd"/>
                        <?php endforeach; ?>
                    </svg>
                    <?php else: ?>
                        <span style="color:#777;">Grafik Kurva Baku AAS</span>
                    <?php endif; ?>
                </td>
            </tr>
        </table>

        <div class="sec-hdr">2. Zat Uji (Blanko Sampel: <?php echo html_escape($p['blanko_sampel_serapan'] ?? '0'); ?>)</div>
        <table class="grid-tbl">
            <thead>
                <tr><th>Zat Uji</th><th>Wadah+Zat (g)</th><th>Wadah+Sisa (g)</th><th>Bobot Zat (g)</th><th>Faktor Pengenceran</th><th>Serapan</th></tr>
            </thead>
            <tbody>
                <?php foreach ($tabel_zat_uji as $z): ?>
                <tr>
                    <td><?php echo html_escape($z['tahap'] ?? ''); ?></td>
                    <td class="num"><?php echo html_escape($z['wadah_zat'] ?? ''); ?></td>
                    <td class="num"><?php echo html_escape($z['wadah_sisa'] ?? ''); ?></td>
                    <td class="num"><?php echo html_escape($z['bobot_bersih'] ?? ''); ?></td>
                    <td class="ctr"><?php echo html_escape($z['faktor_pengenceran'] ?? ''); ?></td>
                    <td class="num"><?php echo html_escape($z['respon_puncak'] ?? ''); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="sec-hdr">3. Perhitungan Kadar Cemaran Logam &amp; LOD/LOQ Referensi</div>
        <table class="grid-tbl">
            <tr>
                <td width="50%">
                    <strong>Konsentrasi Uji:</strong> Uji-1 = <?php echo html_escape($p['x1_sampel'] ?? '-'); ?> ppb | Uji-2 = <?php echo html_escape($p['x2_sampel'] ?? '-'); ?> ppb<br>
                    <strong>Kadar Cemaran:</strong> Uji-1 = <?php echo html_escape($p['kadar1_sampel'] ?? '-'); ?> mg/kg | Uji-2 = <?php echo html_escape($p['kadar2_sampel'] ?? '-'); ?> mg/kg<br>
                    <strong>Rata-rata Kadar Cemaran Logam <?php echo html_escape($p['elemen_logam'] ?? ''); ?> = <?php echo html_escape($p['rata_kadar_sampel'] ?? '-'); ?> mg/kg</strong>
                </td>
                <td width="50%">
                    <strong>LOD / LOQ (Referensi):</strong> <?php echo html_escape(($p['lod_nilai'] ?? '-') . ' ' . ($p['lod_satuan'] ?? 'mg/kg')); ?> / <?php echo html_escape(($p['loq_nilai'] ?? '-') . ' ' . ($p['loq_satuan'] ?? 'mg/kg')); ?><br>
                    <strong>RPD / RSD:</strong> <?php echo html_escape($p['nilai_rpd'] ?? '-'); ?> % (Syarat 1/2 CV Horwitz &lt; <?php echo html_escape($p['syarat_horwitz'] ?? '-'); ?> %)<br>
                    <strong>Hasil Uji ± Ketidakpastian:</strong> <?php echo html_escape($p['ketidakpastian_teks'] ?? '-'); ?> (<?php echo html_escape($p['decision_rule'] ?? 'Simple Acceptance'); ?>)
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Pustaka Metode:</strong> <?php echo html_escape($p['pustaka_metode'] ?? '-'); ?><br>
                    <strong>Pustaka Persyaratan:</strong> <?php echo html_escape($p['pustaka_persyaratan'] ?? 'PerBPOM No. 9 Tahun 2022'); ?>
                </td>
                <td>
                    <strong>Tolerance Limit:</strong> <?php echo html_escape($p['tolerance_limit'] ?? '-'); ?><br>
                    <strong>Kesimpulan:</strong> <strong><?php echo html_escape($hasil['kesimpulan'] ?? ($sesi['kesimpulan'] ?? '-')); ?></strong>
                </td>
            </tr>
        </table>

        <table class="grid-tbl" style="margin-top: 6px;">
            <tr>
                <td width="50%" class="ctr" style="padding: 5px;">
                    Penguji,<br>
                    <?php if ($ttd_penguji_img): ?><img src="<?php echo $ttd_penguji_img; ?>" class="ttd-img"><?php else: ?><div style="height:34px;"></div><?php endif; ?>
                    <strong><?php echo html_escape($penguji['nama_lengkap'] ?? '-'); ?></strong><br>
                    Tanggal Laporan: <?php echo html_escape($tgl_lap); ?>
                </td>
                <td width="50%" class="ctr" style="padding: 5px;">
                    Diperiksa Penyelia / Verifikator,<br>
                    <?php if ($ttd_penyelia_img): ?><img src="<?php echo $ttd_penyelia_img; ?>" class="ttd-img"><?php else: ?><div style="height:34px;"></div><?php endif; ?>
                    <strong><?php echo html_escape($verifier['nama_lengkap'] ?? '-'); ?></strong><br>
                    Tanggal: <?php echo html_escape(!empty($sesi['waktu_verifikasi']) ? date('d F Y', strtotime($sesi['waktu_verifikasi'])) : '-'); ?>
                </td>
            </tr>
        </table>

        <div class="footer-code">
            <span>Ket: *Coret yang tidak perlu</span>
            <span>POM-06.02/CFM.01/SOP.01/F.01A.14</span>
        </div>
    </div>
</body>
</html>
