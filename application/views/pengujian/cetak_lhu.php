<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * View Cetak / Simpan PDF Laporan Hasil Uji (LHU)
 * Menggabungkan Sesi Pengujian Kimia dan Mikrobiologi dalam Satu Dokumen Resmi
 * Format Presisi BBPOM Bandar Lampung
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

$str_tgl_mulai   = $tgl_mulai_ts ? $format_tgl_indo(date('Y-m-d', $tgl_mulai_ts)) : (!empty($sampel['created_at']) ? $format_tgl_indo($sampel['created_at']) : '-');
$str_tgl_selesai = $tgl_selesai_ts ? $format_tgl_indo(date('Y-m-d', $tgl_selesai_ts)) : $format_tgl_indo(date('Y-m-d'));

// Nomor LHU
$nomor_lhu = !empty($sampel['no_sipt']) ? 'LHU.' . $sampel['no_sipt'] : (!empty($sampel['nomor_lhu']) ? $sampel['nomor_lhu'] : 'LHU.090.K.01.01.26.' . str_pad($sampel['id'], 4, '0', STR_PAD_LEFT));

// Tempat sampling
$tempat_sampling = !empty($sampel['nama_sarana']) ? $sampel['nama_sarana'] : (!empty($sampel['kabupaten_kota']) ? $sampel['kabupaten_kota'] : '-');

// Surat Permintaan Uji
$no_spu = !empty($sampel['surtug']) ? $sampel['surtug'] : 'SPU.090.01.26.' . date('m.d', strtotime($sampel['created_at'] ?? 'now')) . '.0001';
$tgl_spu = !empty($sampel['tanggal_sampling']) ? $format_tgl_indo($sampel['tanggal_sampling']) : $format_tgl_indo($sampel['created_at'] ?? 'now');

// Pabrik / Industri
$pabrik = !empty($sampel['nama_alamat_perusahaan']) ? $sampel['nama_alamat_perusahaan'] : (!empty($sampel['nama_sarana']) ? $sampel['nama_sarana'] : '-');

// Resolusi TTD
$ttd_penguji_img  = $resolve_ttd($signature_penguji ?? null);
$ttd_penyelia_img = $resolve_ttd($signature_verifier ?? null);
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
            font-family: 'Times New Roman', Times, serif;
            font-size: 10pt;
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

        .lhu-header {
            text-align: center;
            margin-bottom: 8px;
        }

        .lhu-header .title-main {
            font-size: 13pt;
            font-weight: bold;
            letter-spacing: 0.3px;
        }

        .lhu-header .title-nomor {
            font-size: 11pt;
            font-weight: bold;
            margin-top: 3px;
        }

        .divider-line {
            border: none;
            border-top: 1px solid #000;
            margin: 6px 0;
        }

        .meta-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.5pt;
        }

        .meta-table td {
            padding: 2px 0;
            vertical-align: top;
        }

        .meta-table .col-label {
            width: 38%;
        }

        .meta-table .col-colon {
            width: 3%;
            text-align: center;
        }

        .meta-table .col-value {
            width: 59%;
        }

        .section-title {
            font-size: 10.5pt;
            font-weight: bold;
            margin-top: 14px;
            margin-bottom: 3px;
            text-transform: uppercase;
        }

        .table-lhu {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            font-size: 9pt;
        }

        .table-lhu th, .table-lhu td {
            border: 1px solid #000;
            padding: 5px 6px;
            vertical-align: top;
        }

        .table-lhu thead th {
            font-weight: bold;
            background: #fff;
        }

        .table-lhu .sub-header-row {
            font-weight: bold;
            text-align: left;
        }

        /* Tanda Tangan */
        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            page-break-inside: avoid;
        }

        .signature-table td {
            width: 33.33%;
            text-align: center;
            vertical-align: top;
            padding: 4px;
        }

        .sig-img-box {
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 4px 0;
        }

        .sig-img-box img {
            max-height: 46px;
            max-width: 130px;
            object-fit: contain;
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

        <!-- Header Dokumen -->
        <div class="lhu-header">
            <div class="title-main">Laporan Hasil Uji</div>
            <div class="title-nomor">Nomor : <?php echo html_escape($nomor_lhu); ?></div>
        </div>

        <hr class="divider-line">

        <!-- Blok Metadata 1 -->
        <table class="meta-table">
            <tr>
                <td class="col-label">Nama Sampel</td>
                <td class="col-colon">:</td>
                <td class="col-value fw-bold"><?php echo html_escape(!empty($sampel['nama_sampel']) ? $sampel['nama_sampel'] : '-'); ?></td>
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

        <hr class="divider-line" style="margin-top: 6px; margin-bottom: 16px;">
        <hr class="divider-line" style="margin-top: 0; margin-bottom: 8px;">

        <!-- Blok Metadata 2 -->
        <table class="meta-table">
            <tr>
                <td class="col-label">Nama Pabrik / Distributor / Importir</td>
                <td class="col-colon">:</td>
                <td class="col-value"><?php echo html_escape($pabrik); ?></td>
            </tr>
            <tr>
                <td class="col-label">No. Registrasi</td>
                <td class="col-colon">:</td>
                <td class="col-value"><?php echo html_escape(!empty($sampel['nomor_izin_edar']) ? $sampel['nomor_izin_edar'] : '-'); ?></td>
            </tr>
            <tr>
                <td class="col-label">No. Bets / Lot</td>
                <td class="col-colon">:</td>
                <td class="col-value"><?php echo html_escape(!empty($sampel['no_bets']) ? $sampel['no_bets'] : '-'); ?></td>
            </tr>
            <tr>
                <td class="col-label">Tanggal Kadaluarsa</td>
                <td class="col-colon">:</td>
                <td class="col-value"><?php echo html_escape(!empty($sampel['kedaluwarsa']) ? $sampel['kedaluwarsa'] : '-'); ?></td>
            </tr>
            <tr>
                <td class="col-label">Kemasan</td>
                <td class="col-colon">:</td>
                <td class="col-value"><?php echo html_escape(!empty($sampel['kemasan']) ? $sampel['kemasan'] : '-'); ?></td>
            </tr>
            <tr>
                <td class="col-label">Jumlah Sampel</td>
                <td class="col-colon">:</td>
                <td class="col-value"><?php echo html_escape(!empty($sampel['jumlah_total']) ? $sampel['jumlah_total'] : (!empty($sampel['jumlah_kimia']) || !empty($sampel['jumlah_mikro']) ? ((float)($sampel['jumlah_kimia'] ?? 0) + (float)($sampel['jumlah_mikro'] ?? 0)) : '-')); ?></td>
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
        <!-- TABEL PENGUJIAN GABUNGAN: PENGUJIAN KIMIA & PENGUJIAN MIKRO              -->
        <!-- ========================================================================= -->

        <?php
        // Kelompokkan sesi berdasarkan jenis_pengujian (Kimia dan Mikrobiologi)
        $sesi_by_jenis = [];
        foreach ($sesi_list as $s) {
            $j = !empty($s['jenis_pengujian']) ? trim($s['jenis_pengujian']) : 'Umum';
            $sesi_by_jenis[$j][] = $s;
        }

        // Tampilkan sesi Kimia terlebih dahulu jika ada, lalu Mikrobiologi
        $urutan_jenis = ['Kimia', 'Mikrobiologi'];
        foreach (array_keys($sesi_by_jenis) as $jk) {
            if (!in_array($jk, $urutan_jenis, true)) {
                $urutan_jenis[] = $jk;
            }
        }

        foreach ($urutan_jenis as $jenis):
            if (empty($sesi_by_jenis[$jenis])) continue;

            $judul_seksi = ($jenis === 'Mikrobiologi') ? 'PENGUJIAN MIKRO' : 'PENGUJIAN ' . strtoupper($jenis);
            $nama_balai = !empty($sampel['balai_penguji']) ? 'BALAI BESAR POM DI ' . strtoupper($sampel['balai_penguji']) : 'BALAI BESAR POM DI BANDAR LAMPUNG';
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
                        <th style="width: 22%;">Uji yang dilakukan</th>
                        <th style="width: 22%;">Hasil</th>
                        <th style="width: 20%;">Syarat</th>
                        <th style="width: 13%;">Pustaka</th>
                        <th style="width: 10%;">Metode</th>
                        <th style="width: 13%;">Balai Penguji</th>
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
                            $param_nama = !empty($f['parameter_uji']) ? $f['parameter_uji'] : (!empty($f['nama_template']) ? $f['nama_template'] : 'Parameter');

                            // Kesimpulan & Evaluasi Nilai Hasil
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

                            // Format Nilai Pengamatan Hasil Uji
                            $raw_hasil = !empty($f['data_hasil']) ? json_decode($f['data_hasil'], true) : [];
                            $detail_hasil = [];

                            if (is_array($raw_hasil)) {
                                if (isset($raw_hasil['parameter_values']) && is_array($raw_hasil['parameter_values'])) {
                                    $raw_hasil = array_merge($raw_hasil, $raw_hasil['parameter_values']);
                                }

                                // Ekstrak nilai representatif
                                if (!empty($raw_hasil['hasil_uji'])) {
                                    $detail_hasil[] = $raw_hasil['hasil_uji'];
                                } elseif (!empty($raw_hasil['kadar_rata_rata'])) {
                                    $detail_hasil[] = 'Kadar: ' . $raw_hasil['kadar_rata_rata'] . '%';
                                } elseif (!empty($raw_hasil['pengamatan'])) {
                                    $detail_hasil[] = $raw_hasil['pengamatan'];
                                } elseif (!empty($raw_hasil['tabel_reaksi']) && is_array($raw_hasil['tabel_reaksi'])) {
                                    foreach ($raw_hasil['tabel_reaksi'] as $tr_item) {
                                        if (!empty($tr_item['pengamatan'])) {
                                            $detail_hasil[] = $tr_item['pengamatan'] . (!empty($tr_item['hasil']) ? ' (' . $tr_item['hasil'] . ')' : '');
                                        }
                                    }
                                } elseif (!empty($raw_hasil['tabel_tahapan']) && is_array($raw_hasil['tabel_tahapan'])) {
                                    $detail_hasil[] = 'Negatif/g contoh';
                                }
                            }

                            $str_detail = !empty($detail_hasil) ? implode("<br>- ", $detail_hasil) : '-';
                    ?>
                        <tr>
                            <td><strong><?php echo html_escape($param_nama); ?></strong></td>
                            <td>
                                <div><strong><?php echo html_escape($tag_kes); ?></strong></div>
                                <div style="margin-top: 2px;">-</div>
                                <div style="margin-top: 2px; font-size: 8.5pt;"><?php echo $str_detail; ?></div>
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

        <!-- Kesimpulan Pengujian (Sesuai Gambar 1) -->
        <?php
        if ($ada_tms) {
            $kesimpulan_teks = 'Tidak Memenuhi Syarat';
        } elseif ($semua_ms) {
            $kesimpulan_teks = 'Memenuhi Syarat';
        } else {
            $kesimpulan_teks = 'Sesuai dengan hasil uji';
        }
        ?>

        <table style="width: 100%; border-collapse: collapse; margin-top: 14px; margin-bottom: 8px; font-size: 10pt;">
            <tr>
                <td style="width: 250px; font-weight: bold; vertical-align: top;">Kesimpulan Pengujian</td>
                <td style="width: 15px; text-align: center; vertical-align: top;">:</td>
                <td style="vertical-align: top; font-weight: normal;"><?php echo html_escape($kesimpulan_teks); ?></td>
            </tr>
        </table>

        <!-- ========================================================================= -->
        <!-- KOTAK TANDA TANGAN MT PENGUJIAN (DI SISI KIRI SESUAI GAMBAR 1)            -->
        <!-- ========================================================================= -->

        <div style="border-left: 1px solid #000; border-right: 1px solid #000; border-bottom: 1px solid #000; padding: 10px 14px 14px 14px; min-height: 180px; page-break-inside: avoid; display: flex; flex-direction: column; justify-content: space-between;">
            <div>
                <div style="font-size: 9.5pt;">Bandar Lampung, <?php echo $format_tgl_indo(date('Y-m-d')); ?></div>
                <div style="font-size: 9.5pt; margin-top: 2px;">MT Pengujian</div>
                <div class="sig-img-box" style="margin: 8px 0; justify-content: flex-start; height: 50px;">
                    <?php if (!empty($ttd_approver_img)): ?>
                        <img src="<?php echo $ttd_approver_img; ?>" alt="TTD MT Pengujian" style="max-height: 48px; max-width: 140px; object-fit: contain;">
                    <?php else: ?>
                        <div style="height: 44px; display: flex; align-items: center; font-size: 8pt; color: #777; font-style: italic;">[ TTD Digital ]</div>
                    <?php endif; ?>
                </div>
            </div>
            <div>
                <div style="font-size: 9.5pt; font-weight: normal;"><?php echo html_escape($approver['nama_lengkap'] ?? 'MT Pengujian'); ?></div>
                <div style="font-size: 9pt; color: #333; margin-top: 1px;">NIP. <?php echo html_escape(!empty($approver['nip']) ? $approver['nip'] : '-'); ?></div>
            </div>
        </div>

    </div>

</body>
</html>
