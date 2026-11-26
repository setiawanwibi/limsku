<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$d = array();
if (isset($hasil['data_pengujian']) && is_array($hasil['data_pengujian'])) {
    $d = $hasil['data_pengujian'];
} elseif (isset($hasil['hasil']) && is_array($hasil['hasil'])) {
    $d = $hasil['hasil'];
}

$val = function ($key, $default = '') use ($d) {
    if (!isset($d[$key]) || $d[$key] === '' || $d[$key] === null) return $default;
    return $d[$key];
};

$evalNum = function ($raw) {
    if ($raw === null || $raw === '' || $raw === '-') return null;
    if (is_numeric($raw)) return (float)$raw;
    $s = str_replace(',', '.', trim((string)$raw));
    return is_numeric($s) ? (float)$s : null;
};

$fmt = function ($num, $dec = 4, $empty = '-') {
    if ($num === null || $num === '' || !is_numeric($num)) return $empty;
    return number_format((float)$num, $dec, '.', ',');
};

$nama_sampel  = $sesi['nama_sampel'] ?? ($hasil['nama_sampel'] ?? '-');
$no_kode      = $sesi['kode_sampel'] ?? ($sesi['no_kode_sampel'] ?? '-');
$no_bets      = $sesi['no_bets'] ?? ($d['no_bets'] ?? '-');
$zat_uji_nama = $hasil['nama_pengujian'] ?? ($sesi['nama_pengujian'] ?? 'PK Cemaran Logam');
$tgl_uji_raw  = $hasil['tanggal_uji'] ?? ($sesi['tanggal_uji'] ?? ($sesi['created_at'] ?? ''));
$tgl_uji      = !empty($tgl_uji_raw) ? date('d-m-Y', strtotime($tgl_uji_raw)) : '-';

$rows_induk  = isset($d['tabel_baku_induk_antara']) && is_array($d['tabel_baku_induk_antara']) ? $d['tabel_baku_induk_antara'] : array();
$rows_kerja  = isset($d['tabel_baku_kerja']) && is_array($d['tabel_baku_kerja']) ? $d['tabel_baku_kerja'] : array();
$rows_uji    = isset($d['tabel_zat_uji']) && is_array($d['tabel_zat_uji']) ? $d['tabel_zat_uji'] : array();
$rows_spiked = isset($d['tabel_spiked_sample']) && is_array($d['tabel_spiked_sample']) ? $d['tabel_spiked_sample'] : array();

$pts = array();
foreach ($rows_kerja as $rk) {
    $cx = $evalNum($rk['konsentrasi'] ?? null);
    $cy = $evalNum($rk['intensitas_analit'] ?? null);
    if ($cx !== null && $cy !== null) $pts[] = array('x' => $cx, 'y' => $cy);
}

$slope     = $evalNum($val('slope', ''));
$intercept = $evalNum($val('intercept', ''));
$r_val     = $evalNum($val('koefisien_korelasi', ''));
$syx       = $evalNum($val('sy_x', ''));
$vx0       = $evalNum($val('vx0', ''));
$lod_val   = $evalNum($val('lod_nilai', ''));
$loq_val   = $evalNum($val('loq_nilai', ''));
$persamaan = $val('persamaan_regresi', '');

$nama_analis   = $sesi['nama_analis'] ?? ($hasil['nama_analis'] ?? '-');
$nip_analis    = $sesi['nip_analis'] ?? '';
$nama_penyelia = $sesi['nama_penyelia'] ?? ($hasil['nama_penyelia'] ?? '-');
$nip_penyelia  = $sesi['nip_penyelia'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>LCP ICP-MS Pangan - <?php echo html_escape($no_kode); ?></title>
    <style>
        @page { size: A4 portrait; margin: 8mm 10mm; }
        * { box-sizing: border-box; }
        body { font-family: "Times New Roman", Times, serif; font-size: 8pt; color: #000; background: #525659; margin: 0; padding: 15px 0; }
        .no-print { width: 210mm; margin: 0 auto 10px auto; display: flex; justify-content: flex-end; }
        .btn-print { background: #1d4ed8; color: #fff; border: none; padding: 7px 14px; font-size: 11px; font-weight: 600; border-radius: 4px; cursor: pointer; }
        .page-container { width: 210mm; min-height: 297mm; margin: 0 auto; background: #fff; padding: 8mm 10mm; box-shadow: 0 0 10px rgba(0,0,0,0.3); }
        @media print { body { background: transparent; padding: 0; } .no-print { display: none !important; } .page-container { box-shadow: none; width: 100%; min-height: auto; padding: 0; margin: 0; } }
        .header-kop { text-align: center; border-bottom: 2px solid #000; padding-bottom: 4px; margin-bottom: 6px; }
        .header-kop .doc-no { font-size: 7.5pt; text-align: right; font-weight: bold; }
        .header-kop .title-1 { font-size: 10pt; font-weight: bold; }
        .header-kop .title-2 { font-size: 10.5pt; font-weight: bold; margin-top: 2px; }
        .table-info { width: 100%; border-collapse: collapse; font-size: 7.5pt; margin-bottom: 5px; }
        .table-info td { padding: 1.5px 3px; vertical-align: top; }
        .table-grid { width: 100%; border-collapse: collapse; font-size: 7pt; margin: 4px 0; }
        .table-grid th, .table-grid td { border: 1px solid #000; padding: 2px 3px; vertical-align: middle; }
        .table-grid th { background: #f2f2f2; text-align: center; font-weight: bold; }
        .tc { text-align: center; } .tr { text-align: right; } .tb { font-weight: bold; }
        .sec-title { font-weight: bold; font-size: 7.8pt; margin-top: 5px; margin-bottom: 2px; }
        .box-calc { border: 1px solid #000; padding: 4px 6px; margin-top: 4px; font-size: 7.5pt; }
        .two-col { display: flex; gap: 8px; align-items: stretch; margin-top: 4px; }
        .two-col > div { flex: 1; }
        .ttd-table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 8pt; }
        .ttd-table td { width: 50%; text-align: center; vertical-align: top; padding: 4px; }
        .ttd-img { max-height: 48px; max-width: 140px; display: block; margin: 4px auto; }
    </style>
</head>
<body>
    <div class="no-print"><button type="button" class="btn-print" onclick="window.print()">Cetak / Simpan PDF</button></div>
    <div class="page-container">
        <div class="header-kop">
            <div class="doc-no">POM-06.02/CFM.01/SOP.01/F.01A.18</div>
            <div class="title-1">LAMPIRAN CATATAN PENGUJIAN</div>
            <div class="title-2">ICP MS — <?php echo html_escape($hasil['nama_template'] ?? 'CEMARAN LOGAM PANGAN'); ?></div>
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
                <td>Pelarut</td><td>:</td><td><?php echo html_escape($val('pelarut', '-')); ?></td>
            </tr>
        </table>

        <table class="table-info" style="border: 1px solid #000; padding: 3px;">
            <tr>
                <td style="width:16%;">Merk / Tipe Alat</td><td style="width:2%;">:</td>
                <td style="width:32%;"><?php echo html_escape($val('alat_merk', 'Thermo-Scientific') . ' / ' . $val('alat_tipe', 'ICAP RQ')); ?></td>
                <td style="width:16%;">Detektor / Mass</td><td style="width:2%;">:</td>
                <td style="width:32%;"><?php echo html_escape($val('detektor', 'Quadrapole') . ' / m/z: ' . $val('mass_analit', '-')); ?></td>
            </tr>
            <tr>
                <td>Mode / Gas Mode</td><td>:</td><td><?php echo html_escape($val('mode_icp', 'KED') . ' / ' . $val('gas_mode', '-')); ?></td>
                <td>Kondisi Tuning</td><td>:</td><td class="tb"><?php echo html_escape($val('kondisi_tuning', 'Memenuhi syarat')); ?></td>
            </tr>
            <tr>
                <td>Baku Pembanding</td><td>:</td><td><?php echo html_escape($val('baku_pembanding', '-') . ' (' . $val('baku_kadar_mgl', '1000') . ' mg/L)'); ?></td>
                <td>Blanko Sampel</td><td>:</td><td><?php echo html_escape($val('blanko_sampel_cps', '0')); ?> CPS</td>
            </tr>
        </table>

        <div class="sec-title">1. Penyiapan Baku Antara &amp; Deret Baku Kerja (ng/mL)</div>
        <table class="table-grid">
            <thead>
                <tr>
                    <th>Larutan</th><th>Vol. Pipet (mL)</th><th>Vol. Labu (mL)</th><th>Konsentrasi (ng/mL)</th>
                    <th>Intensitas Analit (CPS)</th><th>Intensitas SI (CPS)</th><th>Rasio</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows_induk as $ri): ?>
                    <tr>
                        <td class="tb"><?php echo html_escape($ri['tahap'] ?? ''); ?></td>
                        <td class="tc"><?php echo html_escape($ri['vol_pipet'] ?? '-'); ?></td>
                        <td class="tc"><?php echo html_escape($ri['vol_labu'] ?? '-'); ?></td>
                        <td class="tr tb"><?php echo html_escape($ri['konsentrasi'] ?? '-'); ?></td>
                        <td class="tc">-</td><td class="tc">-</td><td class="tc">-</td>
                    </tr>
                <?php endforeach; ?>
                <?php foreach ($rows_kerja as $rk): ?>
                    <tr>
                        <td><?php echo html_escape($rk['tahap'] ?? ''); ?></td>
                        <td class="tc"><?php echo html_escape($rk['vol_pipet'] ?? '-'); ?></td>
                        <td class="tc"><?php echo html_escape($rk['vol_labu'] ?? '-'); ?></td>
                        <td class="tr"><?php echo html_escape($rk['konsentrasi'] ?? '-'); ?></td>
                        <td class="tr"><?php echo html_escape($rk['intensitas_analit'] ?? '-'); ?></td>
                        <td class="tr"><?php echo html_escape($rk['intensitas_internal'] ?? '-'); ?></td>
                        <td class="tr"><?php echo html_escape($rk['rasio_intensitas'] ?? '-'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="sec-title">2. Penimbangan &amp; Pengukuran Zat Uji serta Spiked Sample</div>
        <table class="table-grid">
            <thead>
                <tr>
                    <th>Sampel / Replikasi</th><th>Wadah + Zat (g)</th><th>Wadah + Sisa (g)</th><th>Bobot Bersih w (g)</th>
                    <th>Vol. Labu (mL)</th><th>Konsentrasi X (µg/mL)</th><th>Intensitas Analit (CPS)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows_uji as $ru): ?>
                    <tr>
                        <td class="tb"><?php echo html_escape($ru['replikasi'] ?? ''); ?></td>
                        <td class="tr"><?php echo html_escape($ru['wadah_zat'] ?? '-'); ?></td>
                        <td class="tr"><?php echo html_escape($ru['wadah_sisa'] ?? '-'); ?></td>
                        <td class="tr"><?php echo html_escape($ru['bobot_bersih'] ?? '-'); ?></td>
                        <td class="tc"><?php echo html_escape($ru['vol_labu'] ?? '-'); ?></td>
                        <td class="tr tb"><?php echo html_escape($ru['konsentrasi_terukur'] ?? '-'); ?></td>
                        <td class="tr"><?php echo html_escape($ru['intensitas_analit'] ?? '-'); ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php foreach ($rows_spiked as $rs): ?>
                    <tr>
                        <td><?php echo html_escape($rs['replikasi'] ?? ''); ?></td>
                        <td class="tr"><?php echo html_escape($rs['wadah_zat'] ?? '-'); ?></td>
                        <td class="tr"><?php echo html_escape($rs['wadah_sisa'] ?? '-'); ?></td>
                        <td class="tr"><?php echo html_escape($rs['bobot_bersih'] ?? '-'); ?></td>
                        <td class="tc"><?php echo html_escape($rs['vol_labu'] ?? '-'); ?></td>
                        <td class="tr"><?php echo html_escape($rs['konsentrasi_terukur'] ?? '-'); ?></td>
                        <td class="tr"><?php echo html_escape($rs['intensitas_analit'] ?? '-'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="two-col">
            <div class="box-calc">
                <div class="tb" style="margin-bottom:3px;">Kurva Linieritas Baku (Syarat r ≥ 0.99000)</div>
                <div style="font-size:7pt;">
                    Persamaan: <b><?php echo html_escape($persamaan ?: '-'); ?></b><br>
                    Slope (b): <b><?php echo $fmt($slope, 4); ?></b> | Intercept (a): <b><?php echo $fmt($intercept, 4); ?></b><br>
                    Korelasi (r): <b><?php echo $fmt($r_val, 5); ?></b> | Sy/x: <b><?php echo $fmt($syx, 4); ?></b> | Vx0: <b><?php echo $fmt($vx0, 5); ?></b><br>
                    LOD: <b><?php echo $fmt($lod_val, 4); ?> mg/kg</b> | LOQ: <b><?php echo $fmt($loq_val, 4); ?> mg/kg</b>
                </div>
            </div>
            <div class="box-calc">
                <div class="tb" style="margin-bottom:3px;">Perhitungan Kadar, Presisi &amp; Recovery</div>
                <table class="table-grid" style="margin:0;">
                    <tr><td>Kadar Uji-01 / Uji-02</td><td class="tr tb"><?php echo $fmt($val('kadar_replikasi_1'), 4); ?> / <?php echo $fmt($val('kadar_replikasi_2'), 4); ?> mg/kg</td></tr>
                    <tr><td class="tb">Rata-rata Kadar Cemaran Logam</td><td class="tr tb"><?php echo $fmt($val('kadar_rata_mg'), 4); ?> mg/kg</td></tr>
                    <tr><td>Recovery-01 / Recovery-02 (60–115%)</td><td class="tr"><?php echo $fmt($val('recovery_1'), 2); ?>% / <?php echo $fmt($val('recovery_2'), 2); ?>% (Rata: <b><?php echo $fmt($val('recovery_rata'), 2); ?>%</b>)</td></tr>
                    <tr><td>RPD / Syarat 1/2 CV Horwitz</td><td class="tr"><?php echo $fmt($val('rpd'), 2); ?>% / <?php echo $fmt($val('syarat_horwitz'), 2); ?>%</td></tr>
                </table>
            </div>
        </div>

        <table class="table-grid" style="margin-top:5px;">
            <tr><td style="width:28%;" class="tb">Hasil Uji ± Ketidakpastian</td><td class="tb"><?php echo html_escape($val('hasil_uji_kadar_akhir', '-')); ?></td></tr>
            <tr><td class="tb">Pustaka Metode / Persyaratan</td><td>Metode: <?php echo html_escape($val('pustaka_metode', '-')); ?> | Persyaratan: <?php echo html_escape($val('pustaka_persyaratan', '-')); ?></td></tr>
            <tr><td class="tb">Syarat</td><td><?php echo nl2br(html_escape($val('syarat', '-'))); ?></td></tr>
            <tr><td class="tb">Kesimpulan</td><td class="tb" style="font-size:8.5pt;"><?php echo html_escape($val('kesimpulan', '-')); ?></td></tr>
        </table>

        <table class="ttd-table">
            <tr>
                <td>
                    Mengetahui,<br>Penyelia / Pengawas Mutu
                    <?php if (!empty($ttd_penyelia)): ?><img src="<?php echo html_escape($ttd_penyelia); ?>" class="ttd-img" alt="TTD Penyelia"><?php else: ?><div style="height:48px;"></div><?php endif; ?>
                    <div class="tb" style="text-decoration:underline;"><?php echo html_escape($nama_penyelia); ?></div>
                    <?php if (!empty($nip_penyelia)): ?><div>NIP. <?php echo html_escape($nip_penyelia); ?></div><?php endif; ?>
                </td>
                <td>
                    Bandar Lampung, <?php echo html_escape($tgl_uji); ?><br>Analis Penguji
                    <?php if (!empty($ttd_analis)): ?><img src="<?php echo html_escape($ttd_analis); ?>" class="ttd-img" alt="TTD Analis"><?php else: ?><div style="height:48px;"></div><?php endif; ?>
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
