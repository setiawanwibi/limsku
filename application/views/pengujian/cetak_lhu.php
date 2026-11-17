<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * View Cetak / Simpan PDF Laporan Hasil Uji (LHU) Resmi
 * Format & Desain 100% Presisi Sesuai Standar Resmi BBPOM di Bandar Lampung
 * Sesuai Dokumen Template LHU Official (DejaVu Serif Condensed / Times New Roman, 11pt)
 */

// Helper tanggal bahasa Indonesia
$format_tgl_indo = function($date_str) {
    if (empty($date_str) || $date_str === '0000-00-00' || $date_str === '0000-00-00 00:00:00' || $date_str === '-') {
        return '-';
    }
    $ts = strtotime($date_str);
    if (!$ts) return $date_str;
    $bulan = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni',
        7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];
    $d = date('d', $ts);
    $m = (int)date('m', $ts);
    $y = date('Y', $ts);
    return $d . ' ' . ($bulan[$m] ?? date('F', $ts)) . ' ' . $y;
};

// Helper jumlah sampel (menghilangkan trailing .00 jika bilangan bulat)
$format_jumlah = function($val) {
    if (empty($val) && $val !== 0 && $val !== '0') return '-';
    if (is_numeric($val)) {
        return ((float)$val == (int)$val) ? (int)$val : (float)$val;
    }
    return $val;
};

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

// Hitung rentang tanggal pengujian
$tgl_mulai_ts = null;
$tgl_selesai_ts = null;
$ada_tms = false;
$semua_ms = true;

if (!empty($sesi_list) && is_array($sesi_list)) {
    foreach ($sesi_list as $s) {
        if (!empty($s['created_at'])) {
            $t = strtotime($s['created_at']);
            if ($t && (!$tgl_mulai_ts || $t < $tgl_mulai_ts)) $tgl_mulai_ts = $t;
        }
        if (!empty($s['waktu_selesai'])) {
            $t = strtotime($s['waktu_selesai']);
            if ($t && (!$tgl_selesai_ts || $t > $tgl_selesai_ts)) $tgl_selesai_ts = $t;
        }
        if (!empty($s['waktu_approval'])) {
            $t = strtotime($s['waktu_approval']);
            if ($t && (!$tgl_selesai_ts || $t > $tgl_selesai_ts)) $tgl_selesai_ts = $t;
        }
    }
}

$str_tgl_mulai   = $tgl_mulai_ts ? $format_tgl_indo(date('Y-m-d', $tgl_mulai_ts)) : (!empty($sampel['created_at']) ? $format_tgl_indo($sampel['created_at']) : '-');
$str_tgl_selesai = $tgl_selesai_ts ? $format_tgl_indo(date('Y-m-d', $tgl_selesai_ts)) : $format_tgl_indo(date('Y-m-d'));

// Nomor LHU
$nomor_lhu = !empty($sampel['nomor_lhu']) ? $sampel['nomor_lhu'] : (!empty($sampel['no_sipt']) ? 'LHU.' . $sampel['no_sipt'] : 'LHU.090.K.01.01.26.' . str_pad($sampel['id'] ?? 1, 4, '0', STR_PAD_LEFT));

// Tempat sampling
$tempat_sampling = !empty($sampel['nama_sarana']) ? $sampel['nama_sarana'] : (!empty($sampel['tempat_sampling']) ? $sampel['tempat_sampling'] : (!empty($sampel['kabupaten_kota']) ? $sampel['kabupaten_kota'] : '-'));

// Surat Permintaan Uji
$no_spu = !empty($sampel['surtug']) ? $sampel['surtug'] : (!empty($sampel['nomor_spu']) ? $sampel['nomor_spu'] : 'SPU.090.01.26.' . date('m.d', strtotime($sampel['created_at'] ?? 'now')) . '.0001');
$tgl_spu = !empty($sampel['tanggal_spu']) ? $format_tgl_indo($sampel['tanggal_spu']) : (!empty($sampel['tanggal_surat']) ? $format_tgl_indo($sampel['tanggal_surat']) : (!empty($sampel['tanggal_sampling']) ? $format_tgl_indo($sampel['tanggal_sampling']) : $format_tgl_indo($sampel['created_at'] ?? 'now')));

// Pabrik / Industri
$pabrik = !empty($sampel['nama_alamat_perusahaan']) ? $sampel['nama_alamat_perusahaan'] : (!empty($sampel['nama_pabrik']) ? $sampel['nama_pabrik'] : (!empty($sampel['nama_sarana']) ? $sampel['nama_sarana'] : ''));

// No Registrasi
$no_registrasi = !empty($sampel['nomor_izin_edar']) ? $sampel['nomor_izin_edar'] : (!empty($sampel['nomor_registrasi']) ? $sampel['nomor_registrasi'] : (!empty($sampel['nie']) ? $sampel['nie'] : '-'));

// No Bets / Lot
$no_bets = !empty($sampel['no_bets']) ? $sampel['no_bets'] : (!empty($sampel['no_lot']) ? $sampel['no_lot'] : '-');

// Tanggal Kadaluarsa
$tgl_kadaluarsa = !empty($sampel['kedaluwarsa']) ? $sampel['kedaluwarsa'] : (!empty($sampel['tanggal_kadaluarsa']) ? $sampel['tanggal_kadaluarsa'] : '-');

// Kemasan
$kemasan = !empty($sampel['kemasan']) ? $sampel['kemasan'] : '-';

// Jumlah Sampel
$raw_jumlah = !empty($sampel['jumlah_total']) ? $sampel['jumlah_total'] : (!empty($sampel['jumlah_kimia']) || !empty($sampel['jumlah_mikro']) ? ((float)($sampel['jumlah_kimia'] ?? 0) + (float)($sampel['jumlah_mikro'] ?? 0)) : (!empty($sampel['jumlah_sampel']) ? $sampel['jumlah_sampel'] : '-'));
$jumlah_sampel = $format_jumlah($raw_jumlah);

// Approver / MT Pengujian
$nama_approver = !empty($approver['nama_lengkap']) ? $approver['nama_lengkap'] : 'Benny Bagaskara Bobby Putra, A.Md';
$nip_approver  = !empty($approver['nip']) ? $approver['nip'] : '199509172019031004';

// Tanggal LHU pada tanda tangan MT Pengujian
$tgl_lhu_raw = !empty($sampel['tanggal_lhu']) ? $sampel['tanggal_lhu'] : (!empty($sesi_list[0]['waktu_approval']) ? $sesi_list[0]['waktu_approval'] : (!empty($tgl_selesai_ts) ? date('Y-m-d', $tgl_selesai_ts) : date('Y-m-d')));
$str_tgl_lhu = $format_tgl_indo($tgl_lhu_raw);

// Resolusi TTD Approver
$ttd_approver_img = $resolve_ttd($signature_approver ?? null);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Hasil Uji (LHU) - <?php echo html_escape($sampel['nama_sampel'] ?? 'Sampel'); ?></title>
    <style>
        @page {
            size: A4 portrait;
            margin: 15mm 15mm 15mm 15mm;
        }

        *, *::before, *::after {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        body {
            font-family: 'DejaVu Serif Condensed', 'DejaVu Serif', 'Times New Roman', Times, serif;
            font-size: 11pt;
            color: #000;
            line-height: 1.35;
            background-color: #525659;
            margin: 0;
            padding: 20px 0;
        }

        .lhu-wrapper {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto 30px auto;
            padding: 15mm 15mm 15mm 15mm;
            background-color: #fff;
            box-shadow: 0 0 15px rgba(0,0,0,0.3);
            box-sizing: border-box;
        }

        /* Header Judul Laporan Hasil Uji */
        .lhu-header {
            text-align: center;
            margin-bottom: 6px;
        }

        .lhu-header .title-main {
            font-size: 11pt;
            font-weight: bold;
        }

        .lhu-header .title-nomor {
            font-size: 11pt;
            font-weight: bold;
            margin-top: 2px;
        }

        /* Garis Pemisah Tunggal di Bawah Nomor */
        .divider-single {
            border: none;
            border-top: 1px solid #000;
            margin: 6px 0 8px 0;
        }

        /* Tabel Metadata */
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11pt;
            line-height: 1.35;
        }

        .meta-table td {
            padding: 1.5px 0;
            vertical-align: top;
            font-weight: normal;
        }

        .meta-table .col-label {
            width: 36.8%;
        }

        .meta-table .col-colon {
            width: 2.2%;
            text-align: left;
        }

        .meta-table .col-value {
            width: 61%;
        }

        /* Dua Garis Sejajar Pemisah Antara Blok Metadata 1 & Blok Metadata 2 */
        .meta-divider-wrap {
            margin: 4px 0;
        }

        .meta-divider-line {
            border-top: 1px solid #000;
        }

        .meta-divider-space {
            height: 26px; /* Jarak presisi ~25.9 pt */
        }

        /* Judul Seksi Pengujian */
        .section-title {
            font-size: 11pt;
            font-weight: bold;
            margin-top: 18px;
            margin-bottom: 3px;
            text-transform: uppercase;
        }

        /* Tabel Hasil Uji */
        .table-lhu {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            font-size: 11pt;
            line-height: 1.35;
        }

        .table-lhu th, .table-lhu td {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: top;
        }

        .table-lhu thead th {
            font-weight: bold;
            text-align: left;
            background: #fff;
        }

        .table-lhu .sub-header-row {
            font-weight: bold;
            text-align: left;
        }

        .table-lhu tbody td {
            font-weight: normal;
        }

        /* Tabel Kesimpulan Pengujian */
        .kesimpulan-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 10px;
            font-size: 11pt;
            line-height: 1.35;
        }

        .kesimpulan-table td {
            padding: 1.5px 0;
            vertical-align: top;
        }

        .kesimpulan-table .col-label {
            width: 36.8%;
            font-weight: bold;
        }

        .kesimpulan-table .col-colon {
            width: 2.2%;
            text-align: left;
            font-weight: normal;
        }

        .kesimpulan-table .col-value {
            width: 61%;
            font-weight: normal;
        }

        /* Kotak Tanda Tangan MT Pengujian */
        .signature-box {
            width: 100%;
            border-left: 1px solid #000;
            border-right: 1px solid #000;
            border-bottom: 1px solid #000;
            border-top: none;
            padding: 8px 10px 14px 10px;
            font-size: 11pt;
            line-height: 1.35;
            box-sizing: border-box;
            page-break-inside: avoid;
        }

        .sig-top {
            margin-bottom: 4px;
        }

        .sig-space {
            min-height: 65px;
            display: flex;
            align-items: center;
            margin: 4px 0;
        }

        .sig-img {
            max-height: 60px;
            max-width: 160px;
            object-fit: contain;
        }

        .sig-bottom {
            margin-top: 4px;
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

        .btn-print {
            background-color: #198754;
            color: #fff;
        }
        .btn-print:hover {
            background-color: #146c43;
        }

        .btn-back {
            background-color: #6c757d;
            color: #fff;
        }
        .btn-back:hover {
            background-color: #565e64;
        }

        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: #fff;
                padding: 0;
            }
            .lhu-wrapper {
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
                min-height: auto !important;
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
                <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z"/>
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

    <div class="lhu-wrapper">

        <!-- Header Dokumen: Laporan Hasil Uji & Nomor (11pt Bold, Centered) -->
        <div class="lhu-header">
            <div class="title-main">Laporan Hasil Uji</div>
            <div class="title-nomor">Nomor : <?php echo html_escape($nomor_lhu); ?></div>
        </div>

        <!-- Garis Pemisah Tunggal -->
        <div class="divider-single"></div>

        <!-- Blok Metadata 1: Data Sampling & SPU (11pt Regular) -->
        <table class="meta-table">
            <tr>
                <td class="col-label">Nama Sampel</td>
                <td class="col-colon">:</td>
                <td class="col-value"><?php echo html_escape(!empty($sampel['nama_sampel']) ? $sampel['nama_sampel'] : '-'); ?></td>
            </tr>
            <tr>
                <td class="col-label">No. Kode Sampel</td>
                <td class="col-colon">:</td>
                <td class="col-value"><?php echo html_escape(!empty($sampel['no_sipt']) ? $sampel['no_sipt'] : (!empty($sampel['kode_sampel_manual']) ? $sampel['kode_sampel_manual'] : '-')); ?></td>
            </tr>
            <tr>
                <td class="col-label">Tempat Sampling</td>
                <td class="col-colon">:</td>
                <td class="col-value"><?php echo html_escape($tempat_sampling); ?></td>
            </tr>
            <tr>
                <td class="col-label">Tanggal Sampling</td>
                <td class="col-colon">:</td>
                <td class="col-value"><?php echo html_escape(!empty($sampel['tanggal_sampling']) ? $format_tgl_indo($sampel['tanggal_sampling']) : '-'); ?></td>
            </tr>
            <tr>
                <td class="col-label">Nomor Surat Permintaan Uji</td>
                <td class="col-colon">:</td>
                <td class="col-value"><?php echo html_escape($no_spu); ?></td>
            </tr>
            <tr>
                <td class="col-label">Tanggal Surat Permintaan Uji</td>
                <td class="col-colon">:</td>
                <td class="col-value"><?php echo html_escape($tgl_spu); ?></td>
            </tr>
        </table>

        <!-- Garis Ganda Pemisah Antara Blok Metadata 1 & 2 (Spasi 26pt Sesuai PDF Asli) -->
        <div class="meta-divider-wrap">
            <div class="meta-divider-line"></div>
            <div class="meta-divider-space"></div>
            <div class="meta-divider-line"></div>
        </div>

        <!-- Blok Metadata 2: Data Produk & Pengujian (11pt Regular) -->
        <table class="meta-table">
            <tr>
                <td class="col-label">Nama Pabrik / Distributor / Importir</td>
                <td class="col-colon">:</td>
                <td class="col-value"><?php echo html_escape($pabrik); ?></td>
            </tr>
            <tr>
                <td class="col-label">No. Registrasi</td>
                <td class="col-colon">:</td>
                <td class="col-value"><?php echo html_escape($no_registrasi); ?></td>
            </tr>
            <tr>
                <td class="col-label">No. Bets / Lot</td>
                <td class="col-colon">:</td>
                <td class="col-value"><?php echo html_escape($no_bets); ?></td>
            </tr>
            <tr>
                <td class="col-label">Tanggal Kadaluarsa</td>
                <td class="col-colon">:</td>
                <td class="col-value"><?php echo html_escape($tgl_kadaluarsa); ?></td>
            </tr>
            <tr>
                <td class="col-label">Kemasan</td>
                <td class="col-colon">:</td>
                <td class="col-value"><?php echo html_escape($kemasan); ?></td>
            </tr>
            <tr>
                <td class="col-label">Jumlah Sampel</td>
                <td class="col-colon">:</td>
                <td class="col-value"><?php echo html_escape($jumlah_sampel); ?></td>
            </tr>
            <tr>
                <td class="col-label">Tanggal Mulai Pengujian</td>
                <td class="col-colon">:</td>
                <td class="col-value"><?php echo html_escape($str_tgl_mulai); ?></td>
            </tr>
            <tr>
                <td class="col-label">Tanggal Selesai Pengujian</td>
                <td class="col-colon">:</td>
                <td class="col-value"><?php echo html_escape($str_tgl_selesai); ?></td>
            </tr>
        </table>

        <!-- ========================================================================= -->
        <!-- TABEL HASIL PENGUJIAN SESUAI FORMAT RESMI                                -->
        <!-- ========================================================================= -->

        <?php
        // Kelompokkan sesi berdasarkan jenis_pengujian (Kimia dan Mikrobiologi)
        $sesi_by_jenis = [];
        if (!empty($sesi_list) && is_array($sesi_list)) {
            foreach ($sesi_list as $s) {
                $j = !empty($s['jenis_pengujian']) ? trim($s['jenis_pengujian']) : 'Kimia';
                $sesi_by_jenis[$j][] = $s;
            }
        }

        // Tampilkan sesi Kimia terlebih dahulu jika ada, lalu Mikrobiologi
        $urutan_jenis = ['Kimia', 'Mikrobiologi'];
        foreach (array_keys($sesi_by_jenis) as $jk) {
            if (!in_array($jk, $urutan_jenis, true)) {
                $urutan_jenis[] = $jk;
            }
        }

        // Jika tidak ada sesi sama sekali, buat 1 seksi default
        if (empty($urutan_jenis)) {
            $urutan_jenis = ['Kimia'];
        }

        foreach ($urutan_jenis as $jenis):
            if (empty($sesi_by_jenis[$jenis])) continue;

            $judul_seksi = ($jenis === 'Mikrobiologi') ? 'PENGUJIAN MIKROBIOLOGI' : 'PENGUJIAN ' . strtoupper($jenis);
        ?>

            <div class="section-title"><?php echo html_escape($judul_seksi); ?></div>

            <table class="table-lhu">
                <thead>
                    <tr>
                        <th colspan="6" class="sub-header-row">
                            Hasil Pengujian Balai Regional :
                        </th>
                    </tr>
                    <tr>
                        <th style="width: 18%;">Uji yang<br>dilakukan</th>
                        <th style="width: 30.3%;">Hasil</th>
                        <th style="width: 11%;">Syarat</th>
                        <th style="width: 12.9%;">Pustaka</th>
                        <th style="width: 11.4%;">Metode</th>
                        <th style="width: 16.4%;">Balai Penguji</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $ada_item = false;
                    foreach ($sesi_by_jenis[$jenis] as $sesi_item):
                        $forms = !empty($sesi_item['forms']) ? $sesi_item['forms'] : [];

                        foreach ($forms as $f):
                            $ada_item = true;

                            // Parameter Uji
                            $param_nama = !empty($f['parameter_uji']) ? $f['parameter_uji'] : (!empty($f['nama_parameter']) ? $f['nama_parameter'] : (!empty($f['nama_template']) ? $f['nama_template'] : 'Parameter'));

                            // Kesimpulan item (MS / TMS)
                            $kesimpulan_item = !empty($f['kesimpulan']) ? $f['kesimpulan'] : '-';
                            if (stripos($kesimpulan_item, 'tidak memenuhi') !== false) {
                                $ada_tms = true;
                                $semua_ms = false;
                                $tag_kes = 'TMS';
                            } elseif (stripos($kesimpulan_item, 'memenuhi') !== false) {
                                $tag_kes = 'MS';
                            } else {
                                $tag_kes = $kesimpulan_item;
                            }

                            // Pustaka
                            $pustaka_item = !empty($f['pustaka_acuan']) ? $f['pustaka_acuan'] : (!empty($f['pustaka_metode']) ? $f['pustaka_metode'] : '-');

                            // Metode
                            $metode_item = !empty($f['metode_analisa']) ? $f['metode_analisa'] : (!empty($f['nama_metode']) ? $f['nama_metode'] : '-');

                            // Syarat
                            $syarat_item = !empty($f['persyaratan']) ? $f['persyaratan'] : '-';

                            // Balai Penguji
                            $nama_balai = !empty($f['balai_penguji']) ? $f['balai_penguji'] : (!empty($sesi_item['balai_penguji']) ? $sesi_item['balai_penguji'] : (!empty($sampel['balai_penguji']) ? $sampel['balai_penguji'] : 'BALAI POM DI JAMBI'));

                            // Ekstraksi Nilai Pengamatan Hasil Uji
                            $raw_hasil = !empty($f['data_hasil']) ? json_decode($f['data_hasil'], true) : [];
                            $str_detail = '';

                            if (is_array($raw_hasil)) {
                                if (isset($raw_hasil['parameter_values']) && is_array($raw_hasil['parameter_values'])) {
                                    $raw_hasil = array_merge($raw_hasil, $raw_hasil['parameter_values']);
                                }

                                if (!empty($raw_hasil['hasil_lhu'])) {
                                    $str_detail = $raw_hasil['hasil_lhu'];
                                } elseif (!empty($raw_hasil['ringkasan_hasil'])) {
                                    $str_detail = $raw_hasil['ringkasan_hasil'];
                                } elseif (!empty($raw_hasil['hasil_uji'])) {
                                    $str_detail = $raw_hasil['hasil_uji'];
                                } elseif (isset($raw_hasil['kadar_rata_rata']) && $raw_hasil['kadar_rata_rata'] !== '') {
                                    $kadar_num = number_format((float)$raw_hasil['kadar_rata_rata'], 1, ',', '');
                                    $str_detail = $kadar_num . ' %';
                                    if (!empty($raw_hasil['kadar_mg_rata_rata'])) {
                                        $mg_num = number_format((float)$raw_hasil['kadar_mg_rata_rata'], 1, ',', '');
                                        $str_detail .= ' (' . $mg_num . ' mg)';
                                    } elseif (!empty($raw_hasil['kadar_mg'])) {
                                        $mg_num = number_format((float)$raw_hasil['kadar_mg'], 1, ',', '');
                                        $str_detail .= ' (' . $mg_num . ' mg)';
                                    }
                                } elseif (!empty($raw_hasil['nilai_av']) || !empty($raw_hasil['av'])) {
                                    $av_val = !empty($raw_hasil['nilai_av']) ? $raw_hasil['nilai_av'] : $raw_hasil['av'];
                                    $str_detail = 'AV = ' . $av_val;
                                } elseif (!empty($raw_hasil['pemerian']) && is_array($raw_hasil['pemerian'])) {
                                    $p_parts = [];
                                    foreach ($raw_hasil['pemerian'] as $pk => $pv) {
                                        if (!empty($pv)) {
                                            $p_parts[] = ucfirst($pk) . ': ' . $pv;
                                        }
                                    }
                                    $str_detail = implode('; ', $p_parts);
                                } elseif (!empty($raw_hasil['bentuk']) || !empty($raw_hasil['warna']) || !empty($raw_hasil['bau'])) {
                                    $p_parts = [];
                                    if (!empty($raw_hasil['bentuk'])) $p_parts[] = 'Bentuk: ' . $raw_hasil['bentuk'];
                                    if (!empty($raw_hasil['warna'])) $p_parts[] = 'Warna: ' . $raw_hasil['warna'];
                                    if (!empty($raw_hasil['bau'])) $p_parts[] = 'Bau: ' . $raw_hasil['bau'];
                                    if (!empty($raw_hasil['rasa'])) $p_parts[] = 'Rasa: ' . $raw_hasil['rasa'];
                                    if (!empty($raw_hasil['penandaan'])) $p_parts[] = 'Penandaan: ' . $raw_hasil['penandaan'];
                                    $str_detail = implode('; ', $p_parts);
                                } elseif (!empty($raw_hasil['disolusi_ringkasan'])) {
                                    $str_detail = $raw_hasil['disolusi_ringkasan'];
                                } elseif (!empty($raw_hasil['tabel_disolusi']) && is_array($raw_hasil['tabel_disolusi'])) {
                                    $dx_parts = [];
                                    $i_dx = 1;
                                    $total_pct = 0;
                                    $count_pct = 0;
                                    foreach ($raw_hasil['tabel_disolusi'] as $td) {
                                        $pct = isset($td['persen_terlarut']) ? $td['persen_terlarut'] : (isset($td['persen']) ? $td['persen'] : null);
                                        if ($pct !== null) {
                                            $dx_parts[] = 'Dx' . $i_dx . ' = ' . round((float)$pct) . '%';
                                            $total_pct += (float)$pct;
                                            $count_pct++;
                                        }
                                        $i_dx++;
                                    }
                                    if ($count_pct > 0) {
                                        $avg_pct = round($total_pct / $count_pct);
                                        $dx_parts[] = 'Dx Rata-rata (S1) = ' . $avg_pct . '% (Syarat S1: Tiap unit sediaan > Q+5%)';
                                    }
                                    $str_detail = implode(' ', $dx_parts);
                                } elseif (!empty($raw_hasil['pengamatan'])) {
                                    $str_detail = $raw_hasil['pengamatan'];
                                } elseif (!empty($raw_hasil['tabel_reaksi']) && is_array($raw_hasil['tabel_reaksi'])) {
                                    $r_parts = [];
                                    foreach ($raw_hasil['tabel_reaksi'] as $tr_item) {
                                        if (!empty($tr_item['pengamatan'])) {
                                            $r_parts[] = $tr_item['pengamatan'] . (!empty($tr_item['hasil']) ? ' (' . $tr_item['hasil'] . ')' : '');
                                        }
                                    }
                                    $str_detail = implode('; ', $r_parts);
                                } elseif (!empty($raw_hasil['tabel_tahapan']) && is_array($raw_hasil['tabel_tahapan'])) {
                                    $str_detail = 'Negatif/g contoh';
                                } elseif (!empty($f['catatan_hasil'])) {
                                    $str_detail = $f['catatan_hasil'];
                                }
                            }
                    ?>
                        <tr>
                            <td><?php echo html_escape($param_nama); ?></td>
                            <td>
                                <div><?php echo html_escape($tag_kes); ?></div>
                                <?php if (!empty($str_detail) && $str_detail !== '-'): ?>
                                    <div><?php echo nl2br(html_escape($str_detail)); ?></div>
                                <?php endif; ?>
                            </td>
                            <td><?php echo nl2br(html_escape($syarat_item)); ?></td>
                            <td><?php echo nl2br(html_escape($pustaka_item)); ?></td>
                            <td><?php echo nl2br(html_escape($metode_item)); ?></td>
                            <td><?php echo html_escape($nama_balai); ?></td>
                        </tr>
                    <?php
                        endforeach;
                    endforeach;

                    if (!$ada_item):
                    ?>
                        <tr>
                            <td colspan="6" style="text-align: center; color: #777; font-style: italic;">
                                Tidak ada data parameter pengujian untuk seksi ini.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

        <?php endforeach; ?>

        <!-- Kesimpulan Pengujian (Label Bold, Titik Dua & Nilai Regular, Sejajar Sumbu Metadata) -->
        <?php
        if ($ada_tms) {
            $kesimpulan_teks = 'Tidak Memenuhi Syarat';
        } elseif ($semua_ms) {
            $kesimpulan_teks = 'Memenuhi Syarat';
        } else {
            $kesimpulan_teks = 'Sesuai dengan hasil uji';
        }
        ?>

        <table class="kesimpulan-table">
            <tr>
                <td class="col-label">Kesimpulan Pengujian</td>
                <td class="col-colon">:</td>
                <td class="col-value"><?php echo html_escape($kesimpulan_teks); ?></td>
            </tr>
        </table>

        <!-- ========================================================================= -->
        <!-- KOTAK TANDA TANGAN MT PENGUJIAN (BORDER KIRI, KANAN, BAWAH, ATAS TERBUKA) -->
        <!-- ========================================================================= -->

        <div class="signature-box">
            <div class="sig-top">
                <div>Bandar Lampung, <?php echo html_escape($str_tgl_lhu); ?></div>
                <div style="margin-top: 2px;">MT Pengujian</div>
            </div>
            <div class="sig-space">
                <?php if (!empty($ttd_approver_img)): ?>
                    <img src="<?php echo $ttd_approver_img; ?>" alt="TTD MT Pengujian" class="sig-img">
                <?php endif; ?>
            </div>
            <div class="sig-bottom">
                <div><?php echo html_escape($nama_approver); ?></div>
                <div style="margin-top: 2px;">NIP. <?php echo html_escape($nip_approver); ?></div>
            </div>
        </div>

    </div>

</body>
</html>
