<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * View Cetak / Simpan PDF Lembar Catatan Pengujian (LCP) Pangan
 * KROMATOGRAFI CAIR KINERJA TINGGI (HPLC / KCKT) - Pemanis Simultan (MA PPOMN 16/PA/13)
 * Replikasi presisi format resmi BPOM: POM-06.02/CFM.01/SOP.01/F.01A.12
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

$tabel_gradient = isset($p['tabel_gradient']) && is_array($p['tabel_gradient']) ? $p['tabel_gradient'] : array();
$tabel_baku     = isset($p['tabel_baku_kurva']) && is_array($p['tabel_baku_kurva']) ? $p['tabel_baku_kurva'] : array();
$tabel_zat_uji  = isset($p['tabel_zat_uji']) && is_array($p['tabel_zat_uji']) ? $p['tabel_zat_uji'] : array();
$tabel_spike    = isset($p['tabel_spiked_sample']) && is_array($p['tabel_spiked_sample']) ? $p['tabel_spiked_sample'] : array();

// Kumpulkan titik kurva baku untuk SVG grafik
$pts = array();
foreach ($tabel_baku as $rIdx => $r) {
    if ($rIdx < 2) continue; // skip Baku Induk & Bk Antara
    $cx = (float)str_replace(',', '.', (string)($r['konsentrasi'] ?? 0));
    $cy = (float)str_replace(',', '.', (string)($r['respon_puncak'] ?? 0));
    if ($cx > 0 && $cy > 0) {
        $pts[] = array('x' => $cx, 'y' => $cy);
    }
}
$slope     = (float)str_replace(',', '.', (string)($p['regresi_slope'] ?? 0));
$intercept = (float)str_replace(',', '.', (string)($p['regresi_intercept'] ?? 0));
$r_val     = (string)($p['regresi_r'] ?? '-');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak LCP Pangan HPLC - <?php echo html_escape($sampel['nama_sampel'] ?? 'Sampel'); ?></title>
    <style>
        @page { size: A4 portrait; margin: 0; }
        *, *::before, *::after { box-sizing: border-box; -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
        body { margin: 0; padding: 12px 0; background: #525659; font-family: Arial, Helvetica, sans-serif; font-size: 7.5pt; color: #000; line-height: 1.25; }
        .no-print { position: fixed; top: 12px; right: 12px; z-index: 9999; background: #fff; padding: 8px 12px; border-radius: 6px; box-shadow: 0 4px 12px rgba(0,0,0,0.25); display: flex; gap: 8px; }
        .no-print button { background: #004b87; color: #fff; border: none; padding: 6px 12px; font-size: 11px; font-weight: 600; border-radius: 4px; cursor: pointer; }
        .page-container { width: 210mm; min-height: 297mm; margin: 0 auto; background: #fff; padding: 8mm 11mm; box-shadow: 0 0 10px rgba(0,0,0,0.3); }
        @media print { body { background: none; padding: 0; } .no-print { display: none !important; } .page-container { box-shadow: none; margin: 0; } }
        .header-title { text-align: center; font-weight: bold; margin-bottom: 6px; border-bottom: 1.5px solid #000; padding-bottom: 4px; }
        .header-title .l1 { font-size: 10pt; letter-spacing: 0.3px; }
        .header-title .l2 { font-size: 9pt; }
        .header-title .l3 { font-size: 8pt; font-weight: normal; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 5px; }
        .meta-tbl td { padding: 1.5px 3px; vertical-align: top; }
        .grid-tbl th, .grid-tbl td { border: 1px solid #000; padding: 2px 3px; font-size: 7pt; }
        .grid-tbl th { background: #f2f4f7; text-align: center; font-weight: bold; }
        .sec-hdr { font-weight: bold; font-size: 7.5pt; margin: 5px 0 2px; background: #e9ecef; padding: 2px 5px; border-left: 3px solid #004b87; }
        .num { text-align: right; }
        .ctr { text-align: center; }
        .ttd-img { max-height: 34px; max-width: 90px; display: block; margin: 2px auto; }
        .footer-code { font-size: 6.8pt; color: #444; margin-top: 6px; display: flex; justify-content: space-between; }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()">Cetak / Simpan PDF</button>
    </div>

    <div class="page-container">
        <div class="header-title">
            <div class="l1">LAMPIRAN CATATAN PENGUJIAN</div>
            <div class="l2">KROMATOGRAFI CAIR KINERJA TINGGI (PANGAN)</div>
            <div class="l3">Laboratorium Kimia Balai Besar POM di Bandar Lampung</div>
        </div>

        <!-- Identitas Sampel -->
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
                <td>Zat yang diuji</td><td>:</td><td><strong><?php echo html_escape($hasil['parameter_uji'] ?? ($metode['nama_metode'] ?? '-')); ?></strong></td>
                <td>Metode Analisis</td><td>:</td><td><?php echo html_escape($p['metode_analisis_pilihan'] ?? ($p['pustaka_metode'] ?? 'MA PPOM 16/PA/13')); ?></td>
            </tr>
        </table>

        <!-- Kondisi Instrumen & Analisis -->
        <table class="meta-tbl" style="border-top: 1px solid #999; padding-top: 3px;">
            <tr>
                <td width="18%">Merk Alat / Seri</td><td width="2%">:</td><td width="34%"><?php echo html_escape(($p['merk_alat'] ?? '-') . ' / ' . ($p['tipe_seri'] ?? '-')); ?></td>
                <td width="16%">Detektor</td><td width="2%">:</td><td width="28%"><?php echo html_escape($p['detektor'] ?? '-'); ?></td>
            </tr>
            <tr>
                <td>Kondisi Analisis</td><td>:</td><td><?php echo html_escape($p['kondisi_analisis'] ?? 'Sesuai prosedur'); ?> <?php echo !empty($p['kondisi_adjusment']) ? '(' . html_escape($p['kondisi_adjusment']) . ')' : ''; ?></td>
                <td>Pelarut / Suhu</td><td>:</td><td><?php echo html_escape(($p['pelarut'] ?? '-') . ' / ' . (($p['suhu_opt'] ?? '') === 'Suhu Kamar' ? 'Kamar' : (($p['suhu_custom'] ?? '35') . ' °C'))); ?></td>
            </tr>
            <tr>
                <td>Kolom</td><td>:</td><td><?php echo html_escape($p['kolom_dimensi'] ?? '-'); ?></td>
                <td>Laju Aliran / λ Uji</td><td>:</td><td><?php echo html_escape(($p['laju_alir_umum'] ?? '1') . ' mL/menit | ' . ($p['panjang_gelombang'] ?? '205') . ' nm'); ?></td>
            </tr>
            <tr>
                <td>Fase Gerak</td><td>:</td><td colspan="4"><?php echo html_escape(($p['tipe_elusi'] ?? 'Gradient') . ' — ' . ($p['fase_gerak_komposisi'] ?? '-')); ?></td>
            </tr>
            <tr>
                <td>Baku Pembanding</td><td>:</td><td colspan="4">
                    <strong><?php echo html_escape($p['baku_nama'] ?? '-'); ?></strong> (No. Kontrol: <?php echo html_escape($p['baku_no_kontrol'] ?? '-'); ?>, Kadar: <?php echo html_escape($p['baku_kadar'] ?? '-'); ?>, SP/KA: <?php echo html_escape(($p['baku_sp'] ?? '0') . '%'); ?>)
                    <?php if (!empty($p['baku_tambahan_nama'])): ?>
                        <br><em>Baku Tambahan:</em> <?php echo html_escape($p['baku_tambahan_nama']); ?> (No. Kontrol: <?php echo html_escape($p['baku_tambahan_no_kontrol'] ?? '-'); ?>, Kadar: <?php echo html_escape($p['baku_tambahan_kadar'] ?? '-'); ?>)
                    <?php endif; ?>
                </td>
            </tr>
        </table>

        <?php if (!empty($tabel_gradient)): ?>
        <table class="grid-tbl" style="width: 55%;">
            <thead>
                <tr><th>Waktu (menit)</th><th>% A</th><th>% B</th><th>% C</th></tr>
            </thead>
            <tbody>
                <?php foreach ($tabel_gradient as $g): ?>
                <tr>
                    <td class="ctr"><?php echo html_escape($g['waktu_menit'] ?? ''); ?></td>
                    <td class="ctr"><?php echo html_escape($g['fase_a'] ?? ''); ?></td>
                    <td class="ctr"><?php echo html_escape($g['fase_b'] ?? ''); ?></td>
                    <td class="ctr"><?php echo html_escape($g['fase_c'] ?? ''); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>

        <!-- 1. BAKU PEMBANDING & REGRESI -->
        <div class="sec-hdr">1. Baku Pembanding &amp; Regresi Kurva Baku</div>
        <table class="grid-tbl">
            <thead>
                <tr>
                    <th>Tahap Baku</th>
                    <th>Wadah+Zat (mg)</th>
                    <th>Wadah+Sisa (mg)</th>
                    <th>Bobot Bersih (mg)</th>
                    <th>Pengenceran</th>
                    <th>Konsentrasi (µg/mL)</th>
                    <th>Vol. Inj (µL)</th>
                    <th>Rt (menit)</th>
                    <th>Respon Puncak</th>
                    <th>λ (nm)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tabel_baku as $b): ?>
                <tr>
                    <td><?php echo html_escape($b['tahap'] ?? ''); ?></td>
                    <td class="num"><?php echo html_escape($b['wadah_zat'] ?? ''); ?></td>
                    <td class="num"><?php echo html_escape($b['wadah_sisa'] ?? ''); ?></td>
                    <td class="num"><?php echo html_escape($b['bobot_bersih'] ?? ''); ?></td>
                    <td class="ctr"><?php echo html_escape($b['faktor_pengenceran'] ?? ''); ?></td>
                    <td class="num"><?php echo html_escape($b['konsentrasi'] ?? ''); ?></td>
                    <td class="ctr"><?php echo html_escape($b['vol_injeksi'] ?? ''); ?></td>
                    <td class="ctr"><?php echo html_escape($b['waktu_retensi'] ?? ''); ?></td>
                    <td class="num"><?php echo html_escape($b['respon_puncak'] ?? ''); ?></td>
                    <td class="ctr"><?php echo html_escape($b['lambda_nm'] ?? ''); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Regresi + Grafik Kurva Baku SVG -->
        <table class="grid-tbl">
            <tr>
                <td width="48%" style="vertical-align: middle; padding: 4px 6px;">
                    <strong>Persamaan Regresi Linier:</strong><br>
                    <code>y = <?php echo $slope ? number_format($slope, 4, '.', '') : '-'; ?>x <?php echo ($intercept >= 0 ? '+ ' : '- ') . number_format(abs($intercept), 4, '.', ''); ?></code><br>
                    <strong>Koefisien Korelasi (r):</strong> <code><?php echo html_escape($r_val); ?></code> (Syarat: <?php echo html_escape($p['syarat_r'] ?? '≥ 0,998'); ?>)
                </td>
                <td width="52%" class="ctr" style="padding: 3px;">
                    <?php if (count($pts) >= 2):
                        $w = 260; $h = 78; $pad = 14;
                        $xs = array_column($pts, 'x'); $ys = array_column($pts, 'y');
                        $minX = min($xs); $maxX = max($xs); $minY = min($ys); $maxY = max($ys);
                        $dx = ($maxX - $minX) ?: 1; $dy = ($maxY - $minY) ?: 1;
                        $mapX = function($x) use ($minX, $dx, $w, $pad) { return $pad + (($x - $minX) / $dx) * ($w - 2 * $pad); };
                        $mapY = function($y) use ($minY, $dy, $h, $pad) { return ($h - $pad) - (($y - $minY) / $dy) * ($h - 2 * $pad); };
                        $x1 = $mapX($minX); $y1 = $mapY($slope * $minX + $intercept);
                        $x2 = $mapX($maxX); $y2 = $mapY($slope * $maxX + $intercept);
                    ?>
                    <svg width="<?php echo $w; ?>" height="<?php echo $h; ?>" viewBox="0 0 <?php echo $w; ?> <?php echo $h; ?>" style="background:#fafbfc; border:1px solid #ddd;">
                        <line x1="<?php echo $pad; ?>" y1="<?php echo $h-$pad; ?>" x2="<?php echo $w-$pad; ?>" y2="<?php echo $h-$pad; ?>" stroke="#999" stroke-width="0.8"/>
                        <line x1="<?php echo $pad; ?>" y1="<?php echo $pad; ?>" x2="<?php echo $pad; ?>" y2="<?php echo $h-$pad; ?>" stroke="#999" stroke-width="0.8"/>
                        <line x1="<?php echo round($x1,1); ?>" y1="<?php echo round($y1,1); ?>" x2="<?php echo round($x2,1); ?>" y2="<?php echo round($y2,1); ?>" stroke="#198754" stroke-width="1.4"/>
                        <?php foreach ($pts as $pt): ?>
                            <circle cx="<?php echo round($mapX($pt['x']),1); ?>" cy="<?php echo round($mapY($pt['y']),1); ?>" r="2.5" fill="#0d6efd"/>
                        <?php endforeach; ?>
                    </svg>
                    <?php else: ?>
                        <span style="color:#777;">Grafik Kurva Baku (minimal 2 titik baku kerja)</span>
                    <?php endif; ?>
                </td>
            </tr>
        </table>

        <!-- 2. ZAT UJI -->
        <div class="sec-hdr">2. Zat Uji (Sampel Tanpa Spike)</div>
        <table class="grid-tbl">
            <thead>
                <tr>
                    <th>Zat Uji</th><th>Wadah+Zat (g)</th><th>Wadah+Sisa (g)</th><th>Bobot Bersih (g)</th>
                    <th>Faktor Pengenceran</th><th>Vol. Inj (µL)</th><th>Rt (menit)</th><th>Respon Puncak</th><th>λ (nm)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tabel_zat_uji as $z): ?>
                <tr>
                    <td><?php echo html_escape($z['tahap'] ?? ''); ?></td>
                    <td class="num"><?php echo html_escape($z['wadah_zat'] ?? ''); ?></td>
                    <td class="num"><?php echo html_escape($z['wadah_sisa'] ?? ''); ?></td>
                    <td class="num"><?php echo html_escape($z['bobot_bersih'] ?? ''); ?></td>
                    <td class="ctr"><?php echo html_escape($z['faktor_pengenceran'] ?? ''); ?></td>
                    <td class="ctr"><?php echo html_escape($z['vol_injeksi'] ?? ''); ?></td>
                    <td class="ctr"><?php echo html_escape($z['waktu_retensi'] ?? ''); ?></td>
                    <td class="num"><?php echo html_escape($z['respon_puncak'] ?? ''); ?></td>
                    <td class="ctr"><?php echo html_escape($z['lambda_nm'] ?? ''); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- 3. SPIKED SAMPLE DUPLO -->
        <div class="sec-hdr">3. Spiked Sample (Duplo)</div>
        <table class="grid-tbl">
            <thead>
                <tr>
                    <th>Spiked Sample</th><th>Wadah+Zat (g)</th><th>Wadah+Sisa (g)</th><th>Bobot Bersih (g)</th>
                    <th>Faktor Pengenceran</th><th>Vol. Inj (µL)</th><th>Rt (menit)</th><th>Respon Puncak</th><th>λ (nm)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tabel_spike as $sp): ?>
                <tr>
                    <td><?php echo html_escape($sp['tahap'] ?? ''); ?></td>
                    <td class="num"><?php echo html_escape($sp['wadah_zat'] ?? ''); ?></td>
                    <td class="num"><?php echo html_escape($sp['wadah_sisa'] ?? ''); ?></td>
                    <td class="num"><?php echo html_escape($sp['bobot_bersih'] ?? ''); ?></td>
                    <td class="ctr"><?php echo html_escape($sp['faktor_pengenceran'] ?? ''); ?></td>
                    <td class="ctr"><?php echo html_escape($sp['vol_injeksi'] ?? ''); ?></td>
                    <td class="ctr"><?php echo html_escape($sp['waktu_retensi'] ?? ''); ?></td>
                    <td class="num"><?php echo html_escape($sp['respon_puncak'] ?? ''); ?></td>
                    <td class="ctr"><?php echo html_escape($sp['lambda_nm'] ?? ''); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- PERHITUNGAN -->
        <div class="sec-hdr">Perhitungan Kadar, Recovery Duplo, RPD &amp; LOD/LOQ</div>
        <table class="grid-tbl">
            <tr>
                <td width="50%">
                    <strong>Sampel Tanpa Spike:</strong><br>
                    x1 = <?php echo html_escape($p['x1_sampel'] ?? '0'); ?> µg/mL | x2 = <?php echo html_escape($p['x2_sampel'] ?? '0'); ?> µg/mL<br>
                    Kadar 1 = <?php echo html_escape($p['kadar1_sampel'] ?? '0'); ?> mg/kg | Kadar 2 = <?php echo html_escape($p['kadar2_sampel'] ?? '0'); ?> mg/kg<br>
                    <strong>Rata-rata Kadar = <?php echo html_escape($p['rata_kadar_sampel'] ?? '0'); ?> mg/kg</strong>
                </td>
                <td width="50%">
                    <strong>Spiked Sample &amp; Recovery:</strong><br>
                    Baku ditambahkan: <?php echo html_escape($p['spike_vol_ml'] ?? '-'); ?> mL (<?php echo html_escape($p['spike_sumber_konsentrasi'] ?? '-'); ?> µg/mL) ke <?php echo html_escape($p['spike_berat_sampel'] ?? '-'); ?> g = <strong><?php echo html_escape($p['kadar_baku_ditambahkan'] ?? '-'); ?> mg/kg</strong><br>
                    Kadar+Spike 1 = <?php echo html_escape($p['kadar1_spike'] ?? '-'); ?> mg/kg | Kadar+Spike 2 = <?php echo html_escape($p['kadar2_spike'] ?? '-'); ?> mg/kg<br>
                    <strong>Recovery 1 = <?php echo html_escape($p['recovery_1'] ?? '-'); ?> %</strong> | <strong>Recovery 2 = <?php echo html_escape($p['recovery_2'] ?? '-'); ?> %</strong> (Syarat: <?php echo html_escape($p['syarat_recovery'] ?? '80 - 110 %'); ?>)
                </td>
            </tr>
            <tr>
                <td>
                    <strong>RPD / RSD:</strong> <?php echo html_escape($p['nilai_rpd'] ?? '-'); ?> % (Syarat 1/2 CV Horwitz &lt; <?php echo html_escape($p['syarat_horwitz'] ?? '-'); ?> %)<br>
                    <strong>LOD / LOQ:</strong> <?php echo html_escape(($p['lod_nilai'] ?? '-') . ' ' . ($p['lod_satuan'] ?? 'mg/kg')); ?> / <?php echo html_escape(($p['loq_nilai'] ?? '-') . ' ' . ($p['loq_satuan'] ?? 'mg/kg')); ?>
                </td>
                <td>
                    <strong>Hasil Uji (Identifikasi):</strong> <?php echo html_escape($p['hasil_identifikasi'] ?? '-'); ?><br>
                    <strong>Hasil Uji ± Ketidakpastian:</strong> <?php echo html_escape($p['ketidakpastian_teks'] ?? '-'); ?> (<?php echo html_escape($p['decision_rule'] ?? 'Simple Acceptance'); ?>)
                </td>
            </tr>
            <tr>
                <td>
                    <strong>Pustaka Metode:</strong> <?php echo html_escape($p['pustaka_metode'] ?? 'MA PPOM 16/PA/13'); ?><br>
                    <strong>Pustaka Persyaratan:</strong> <?php echo html_escape($p['pustaka_persyaratan'] ?? 'PerBPOM No. 11 Tahun 2019'); ?>
                </td>
                <td>
                    <strong>Tolerance Limit:</strong> <?php echo html_escape($p['tolerance_limit'] ?? '-'); ?><br>
                    <strong>Kesimpulan:</strong> <strong><?php echo html_escape($hasil['kesimpulan'] ?? ($sesi['kesimpulan'] ?? '-')); ?></strong>
                </td>
            </tr>
        </table>

        <!-- TANDA TANGAN -->
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
            <span>POM-06.02/CFM.01/SOP.01/F.01A.12</span>
        </div>
    </div>
</body>
</html>
