<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * View Cetak / Simpan PDF Catatan Pengujian (CP) Kimia
 * Dibuat sama persis dengan format resmi Catatan Pengujian (CP) Mikrobiologi (Sheet CP-SIPT).
 * BALAI BESAR PENGAWAS OBAT DAN MAKANAN DI BANDAR LAMPUNG
 */

$status_sesi = $sesi['status'] ?? 'Draft';

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

$ttd_penguji_img  = $resolve_ttd($signature_penguji ?? null);
$ttd_penyelia_img = $resolve_ttd($signature_verifier ?? null);

// Data Identitas & Nilai Sampel
$kode_smp = !empty($sampel['kode_sampel_manual']) 
    ? $sampel['kode_sampel_manual'] 
    : (!empty($sampel['no']) ? 'NO-' . $sampel['no'] : (!empty($sampel['id']) ? 'SMP-' . $sampel['id'] : '-'));

$nama_smp  = (isset($sampel['nama_sampel']) && $sampel['nama_sampel'] !== '') ? $sampel['nama_sampel'] : '-';
$segel_val = (isset($sampel['kondisi_produk']) && $sampel['kondisi_produk'] !== '') ? $sampel['kondisi_produk'] : '-';

$surtug_val = '-';
if (!empty($sampel['surtug'])) {
    $surtug_val = $sampel['surtug'];
} elseif (!empty($sampel['tanggal_surat_perintah'])) {
    $surtug_val = date('d F Y', strtotime($sampel['tanggal_surat_perintah']));
}

$info_rows = array(
    'Kemasan'                                => (isset($sampel['kemasan']) && $sampel['kemasan'] !== '') ? $sampel['kemasan'] : '-',
    'Komposisi'                              => (isset($sampel['komposisi']) && $sampel['komposisi'] !== '') ? $sampel['komposisi'] : '-',
    'Nama Pabrik / Distributor / Importir'   => (isset($sampel['nama_alamat_perusahaan']) && $sampel['nama_alamat_perusahaan'] !== '') ? $sampel['nama_alamat_perusahaan'] : ((isset($sampel['nama_sarana']) && $sampel['nama_sarana'] !== '') ? $sampel['nama_sarana'] : '-'),
    'No. Bets / Lot'                         => (isset($sampel['no_bets']) && $sampel['no_bets'] !== '') ? $sampel['no_bets'] : '-',
    'No. Registrasi'                         => (isset($sampel['nomor_izin_edar']) && $sampel['nomor_izin_edar'] !== '') ? $sampel['nomor_izin_edar'] : '-',
    'Tanggal Kadaluarsa'                     => (isset($sampel['kedaluwarsa']) && $sampel['kedaluwarsa'] !== '') ? $sampel['kedaluwarsa'] : '-'
);

$jml_diterima = (isset($sampel['jumlah_kimia']) && $sampel['jumlah_kimia'] !== '') 
    ? $sampel['jumlah_kimia'] 
    : ((isset($sampel['jumlah_total']) && $sampel['jumlah_total'] !== '') ? $sampel['jumlah_total'] : '-');

$label_smp = (isset($sampel['penandaan']) && $sampel['penandaan'] !== '') ? $sampel['penandaan'] : '-';

// Data Pemerian default atau dari sampel / form
$pemerian = array('Bentuk' => '-', 'Warna' => '-', 'Bau' => '-', 'Rasa' => '-');
if (!empty($sampel['bentuk_sediaan'])) {
    $pemerian['Bentuk'] = $sampel['bentuk_sediaan'];
} elseif (!empty($sampel['pemerian']) && is_string($sampel['pemerian'])) {
    $pemerian['Bentuk'] = $sampel['pemerian'];
}
if (!empty($sampel['pemerian_warna'])) $pemerian['Warna'] = $sampel['pemerian_warna'];
if (!empty($sampel['pemerian_bau']))   $pemerian['Bau']   = $sampel['pemerian_bau'];
if (!empty($sampel['pemerian_rasa']))  $pemerian['Rasa']  = $sampel['pemerian_rasa'];

foreach ($forms as $f_item) {
    if (stripos($f_item['parameter_uji'], 'Pemerian') !== false || stripos($f_item['nama_template'], 'Pemerian') !== false) {
        $dt = !empty($f_item['data_hasil']) ? json_decode($f_item['data_hasil'], true) : [];
        if (isset($dt['parameter_values'])) $dt = array_merge($dt, $dt['parameter_values']);
        if (!empty($dt['bentuk'])) $pemerian['Bentuk'] = $dt['bentuk'];
        if (!empty($dt['warna']))  $pemerian['Warna']  = $dt['warna'];
        if (!empty($dt['bau']))    $pemerian['Bau']    = $dt['bau'];
        if (!empty($dt['rasa']))   $pemerian['Rasa']   = $dt['rasa'];
    }
}

// Format Tanggal
$bulan_indo = [
    'January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret',
    'April' => 'April', 'May' => 'Mei', 'June' => 'Juni',
    'July' => 'Juli', 'August' => 'Agustus', 'September' => 'September',
    'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'
];

$tgl_lapor = !empty($sesi['waktu_selesai']) ? strtr(date('d F Y', strtotime($sesi['waktu_selesai'])), $bulan_indo) : '-';
$tgl_periksa = !empty($sesi['waktu_verifikasi']) ? strtr(date('d F Y', strtotime($sesi['waktu_verifikasi'])), $bulan_indo) : '-';

// Kesimpulan Sesi
$kesimpulan_sesi = !empty($sesi['kesimpulan']) ? $sesi['kesimpulan'] : 'MS terhadap Uji yang dilakukan';
$catatan_sesi    = (isset($sesi['catatan']) && $sesi['catatan'] !== '') ? $sesi['catatan'] : ((isset($sesi['catatan_pengujian']) && $sesi['catatan_pengujian'] !== '') ? $sesi['catatan_pengujian'] : '-');
$sisa_smp        = (isset($sampel['jumlah_arsip']) && $sampel['jumlah_arsip'] !== '') ? $sampel['jumlah_arsip'] : '-';

$nama_p = (isset($penguji['nama_lengkap']) && $penguji['nama_lengkap'] !== '') ? $penguji['nama_lengkap'] : ((isset($sesi['nama_penguji']) && $sesi['nama_penguji'] !== '') ? $sesi['nama_penguji'] : '...................................');
$nip_p  = (isset($penguji['nip']) && $penguji['nip'] !== '') ? 'NIP. ' . $penguji['nip'] : 'NIP. .....................';

$nama_v = (isset($verifier['nama_lengkap']) && $verifier['nama_lengkap'] !== '') ? $verifier['nama_lengkap'] : ((isset($sesi['nama_verifier']) && $sesi['nama_verifier'] !== '') ? $sesi['nama_verifier'] : '...................................');
$nip_v  = (isset($verifier['nip']) && $verifier['nip'] !== '') ? 'NIP. ' . $verifier['nip'] : 'NIP. .................................';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catatan Pengujian Kimia - <?php echo html_escape($nama_smp); ?></title>
    <style>
        @page {
            size: A4 portrait;
            margin: 10mm;
        }

        *, *::before, *::after {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            color-adjust: exact !important;
        }

        body {
            margin: 0;
            padding: 20px 0;
            background-color: #525659;
            font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
            font-size: 8.5pt;
            color: #000;
            line-height: 1.2;
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

        .btn-pdf {
            background-color: #0d6efd;
            color: #fff;
        }
        .btn-pdf:hover {
            background-color: #0b5ed7;
        }

        .btn-back {
            background-color: #6c757d;
            color: #fff;
        }
        .btn-back:hover {
            background-color: #565e64;
        }

        /* Document Paper */
        .document-wrapper {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto 30px auto;
            padding: 12mm 10mm 10mm 10mm;
            background-color: #fff;
            box-shadow: 0 0 15px rgba(0,0,0,0.3);
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        /* Grid Table Catatan Pengujian (Exact match to CP Mikro standard) */
        table.cp-grid {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 8.5pt;
            border: 1pt solid #000;
        }

        table.cp-grid td, table.cp-grid th {
            border: 1pt solid #000;
            padding: 3.5px 5px;
            vertical-align: middle;
            word-wrap: break-word;
        }

        .title-box {
            font-weight: bold;
            font-size: 11pt;
            text-align: center;
            padding: 6px 4px;
            background-color: #fff;
        }

        .num-col {
            width: 7mm;
            text-align: center;
            font-weight: normal;
            font-size: 9pt;
        }

        .lbl-col {
            font-size: 9pt;
        }

        .val-text {
            font-size: 8.5pt;
        }

        .fw-bold { font-weight: bold; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }

        /* Print Media Overrides */
        @media print {
            html, body {
                height: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                background: none !important;
            }
            .no-print {
                display: none !important;
            }
            .document-wrapper {
                margin: 0 !important;
                padding: 0 !important;
                box-shadow: none !important;
                width: 100% !important;
                min-height: 100% !important;
            }
        }
    </style>
</head>
<body>

    <!-- Floating Print / Save Controls -->
    <div class="no-print">
        <a href="<?php echo site_url('pengujian/cp_kimia_pdf/' . $sesi['id']); ?>" class="btn-action btn-pdf" target="_blank">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/>
                <path d="M4.603 14.087a.81.81 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.185.2-.391.564-.78 1.07-1.132.33-.23.704-.436 1.109-.607.21-.089.43-.173.655-.251.272-.095.553-.18.835-.252.12-.03.243-.058.366-.084.288-.06.577-.107.863-.14.28-.032.553-.05.814-.055.23-.004.444.004.64.022.188.017.355.047.498.09.138.041.246.096.321.162.072.063.111.137.115.22.004.09-.033.18-.11.266a.82.82 0 0 1-.318.204c-.146.052-.33.086-.547.102a4.4 4.4 0 0 1-.723-.005 8.3 8.3 0 0 1-.84-.09 13.6 13.6 0 0 1-.892-.185 24 24 0 0 1-.876-.285c-.279-.104-.543-.22-.787-.344a6.5 6.5 0 0 1-.689-.413 3.5 3.5 0 0 1-.54-.471z"/>
            </svg>
            Unduh CP PDF
        </a>
        <button onclick="window.print()" class="btn-action btn-print">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/>
                <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z"/>
            </svg>
            Cetak Dokumen
        </button>
        <button onclick="window.close()" class="btn-action btn-back">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
            </svg>
            Tutup
        </button>
    </div>

    <div class="document-wrapper">
        <div>
            <!-- TABEL UTAMA GRID CATATAN PENGUJIAN -->
            <table class="cp-grid">
                <!-- ROW 1: Judul CATATAN PENGUJIAN -->
                <tr>
                    <td colspan="6" class="title-box">
                        CATATAN PENGUJIAN
                    </td>
                </tr>

                <!-- ROW 2: Header 1 Nama Sampel | 2 Kode Sampel | 3 Segel -->
                <tr>
                    <td class="num-col">1</td>
                    <td class="lbl-col" style="width: 38%;">Nama Sampel</td>
                    <td class="num-col">2</td>
                    <td class="lbl-col" style="width: 28%;">Kode Sampel</td>
                    <td class="num-col">3</td>
                    <td class="lbl-col" style="width: 22%;">Segel</td>
                </tr>

                <!-- ROW 3: Nilai Baris 1-3 -->
                <tr>
                    <td colspan="2" class="val-text"><?php echo html_escape($nama_smp); ?></td>
                    <td colspan="2" class="val-text"><?php echo html_escape($kode_smp); ?></td>
                    <td colspan="2" class="val-text"><?php echo html_escape($segel_val); ?></td>
                </tr>

                <!-- ROW 4: Header 4 Tanggal Surat Perintah Pengujian | 5 Laboratorium -->
                <tr>
                    <td class="num-col">4</td>
                    <td class="lbl-col">Tanggal Surat Perintah Pengujian</td>
                    <td class="num-col">5</td>
                    <td colspan="3" class="lbl-col">Laboratorium</td>
                </tr>

                <!-- ROW 5: Nilai Baris 4-5 -->
                <tr>
                    <td colspan="2" class="val-text"><?php echo html_escape($surtug_val); ?></td>
                    <td colspan="4" class="val-text">Kimia</td>
                </tr>

                <!-- ROW 6: Header 6 Informasi Sampel -->
                <tr>
                    <td class="num-col" rowspan="7" style="vertical-align: top; padding-top: 6px;">6</td>
                    <td colspan="5" class="lbl-col">Informasi Sampel</td>
                </tr>

                <!-- ROW 7-12: Sub-baris Informasi Sampel -->
                <?php foreach ($info_rows as $lbl => $val): ?>
                <tr>
                    <td class="lbl-col" style="padding-left: 8px;"><?php echo html_escape($lbl); ?></td>
                    <td colspan="4" class="val-text"><?php echo nl2br(html_escape($val)); ?></td>
                </tr>
                <?php endforeach; ?>

                <!-- ROW 13: Header 7 Jumlah sampel yang diterima | 8 Label Sampel -->
                <tr>
                    <td class="num-col">7</td>
                    <td class="lbl-col">Jumlah sampel yang diterima</td>
                    <td class="num-col">8</td>
                    <td colspan="3" class="lbl-col">Label Sampel</td>
                </tr>

                <!-- ROW 14: Nilai Baris 7-8 -->
                <tr>
                    <td colspan="2" class="val-text"><?php echo html_escape($jml_diterima); ?></td>
                    <td colspan="4" class="val-text"><?php echo html_escape($label_smp); ?></td>
                </tr>

                <!-- ROW 15: Header 9 Hasil Pengujian -->
                <tr>
                    <td class="num-col">9</td>
                    <td colspan="5" class="lbl-col">Hasil Pengujian</td>
                </tr>

                <!-- ROW 16: Pemerian Heading -->
                <tr>
                    <td colspan="6" class="val-text fw-bold" style="background-color: #fafafa;">Pemerian</td>
                </tr>

                <!-- ROW 17: Bentuk, Warna, Bau, Rasa -->
                <tr>
                    <td colspan="6" style="padding: 0;">
                        <table style="width: 100%; border-collapse: collapse; table-layout: fixed;">
                            <tr>
                                <td style="border: none; border-right: 1pt solid #000; width: 25%; padding: 4px 6px;">
                                    Bentuk: <?php echo html_escape($pemerian['Bentuk']); ?>
                                </td>
                                <td style="border: none; border-right: 1pt solid #000; width: 25%; padding: 4px 6px;">
                                    Warna: <?php echo html_escape($pemerian['Warna']); ?>
                                </td>
                                <td style="border: none; border-right: 1pt solid #000; width: 25%; padding: 4px 6px;">
                                    Bau : <?php echo html_escape($pemerian['Bau']); ?>
                                </td>
                                <td style="border: none; width: 25%; padding: 4px 6px;">
                                    Rasa: <?php echo html_escape($pemerian['Rasa']); ?>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <!-- ROW 18: Header Kolom Parameter Pengujian -->
                <tr style="font-weight: bold; text-align: center; background-color: #f5f5f5;">
                    <td colspan="2" style="width: 32%; text-align: center;">Uji yang dilakukan</td>
                    <td style="width: 23%; text-align: center;">Hasil</td>
                    <td style="width: 17%; text-align: center;">Syarat</td>
                    <td style="width: 13%; text-align: center;">Pustaka</td>
                    <td style="width: 15%; text-align: center;">Metode</td>
                </tr>

                <!-- ROWS 19+: Daftar Parameter Uji Dinamis -->
                <?php 
                $rendered_rows = 0;
                if (!empty($forms)): 
                    foreach ($forms as $f): 
                        if (stripos($f['parameter_uji'], 'Pemerian') !== false || stripos($f['nama_template'], 'Pemerian') !== false) {
                            continue;
                        }

                        $dh = !empty($f['data_hasil']) ? json_decode($f['data_hasil'], true) : [];
                        $pv = isset($dh['parameter_values']) && is_array($dh['parameter_values']) ? $dh['parameter_values'] : $dh;

                        $nama_uji = !empty($f['parameter_uji']) ? $f['parameter_uji'] : (!empty($f['nama_template']) ? $f['nama_template'] : '-');
                        $nama_zat = !empty($f['nama_zat_aktif']) ? $f['nama_zat_aktif'] : ($sesi['nama_zat_aktif'] ?? '');
                        
                        $hasil_str = !empty($f['hasil_uji']) ? $f['hasil_uji'] : (!empty($pv['hasil_uji']) ? $pv['hasil_uji'] : (!empty($f['kesimpulan']) ? $f['kesimpulan'] : '-'));
                        $syarat    = !empty($f['persyaratan']) ? $f['persyaratan'] : (!empty($pv['syarat']) ? $pv['syarat'] : (!empty($pv['persyaratan']) ? $pv['persyaratan'] : '-'));
                        $pustaka   = !empty($f['pustaka_acuan']) ? $f['pustaka_acuan'] : (!empty($pv['pustaka_metode']) ? $pv['pustaka_metode'] : (!empty($pv['pustaka_acuan']) ? $pv['pustaka_acuan'] : '-'));
                        $metode_kd = !empty($f['metode_analisa']) ? $f['metode_analisa'] : (!empty($pv['metode_analisa']) ? $pv['metode_analisa'] : (!empty($f['nama_metode']) ? $f['nama_metode'] : '-'));
                        $rendered_rows++;
                ?>
                <tr>
                    <td colspan="2">
                        <div class="fw-bold"><?php echo html_escape($nama_uji); ?></div>
                        <?php if (!empty($nama_zat) && stripos($nama_uji, $nama_zat) === false): ?>
                            <div style="font-size: 7.8pt; color: #333; margin-top: 1px;"><?php echo html_escape($nama_zat); ?></div>
                        <?php endif; ?>
                    </td>
                    <td><?php echo nl2br(html_escape($hasil_str)); ?></td>
                    <td><?php echo nl2br(html_escape($syarat)); ?></td>
                    <td><?php echo html_escape($pustaka); ?></td>
                    <td class="text-center"><?php echo html_escape($metode_kd); ?></td>
                </tr>
                <?php 
                    endforeach; 
                endif; 
                ?>

                <!-- Pad minimal 5 baris kosong jika parameter sedikit (seperti di excel CP Mikro) -->
                <?php while ($rendered_rows < 5): $rendered_rows++; ?>
                <tr style="height: 22px;">
                    <td colspan="2">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <?php endwhile; ?>

                <!-- ROW: 10 Kesimpulan -->
                <tr>
                    <td class="num-col">10</td>
                    <td class="lbl-col">Kesimpulan</td>
                    <td colspan="4" class="val-text fw-bold"><?php echo html_escape($kesimpulan_sesi); ?></td>
                </tr>

                <!-- ROW: 11 Catatan -->
                <tr>
                    <td class="num-col">11</td>
                    <td class="lbl-col">Catatan</td>
                    <td colspan="4" class="val-text"><?php echo html_escape($catatan_sesi); ?></td>
                </tr>

                <!-- ROW: 12 Sisa Sampel -->
                <tr>
                    <td class="num-col">12</td>
                    <td class="lbl-col">Sisa Sampel</td>
                    <td colspan="4" class="val-text"><?php echo html_escape($sisa_smp); ?></td>
                </tr>

                <!-- ROW: 13 Tanggal dilaporkan penguji | 14 Catatan Pengujian diperiksa -->
                <tr>
                    <td class="num-col">13</td>
                    <td class="lbl-col">Tanggal dilaporkan penguji</td>
                    <td class="num-col">14</td>
                    <td colspan="3" class="lbl-col">Catatan Pengujian diperiksa</td>
                </tr>

                <!-- ROW: Nilai Baris 13-14 -->
                <tr>
                    <td colspan="2" class="val-text"><?php echo html_escape($tgl_lapor); ?></td>
                    <td colspan="4" class="val-text text-center"><?php echo html_escape($tgl_periksa); ?></td>
                </tr>
            </table>

            <!-- AREA TANDA TANGAN (Format resmi CP BBPOM: Penguji & Penyelia Kimia) -->
            <div style="margin-top: 15px;">
                <table style="width: 100%; border-collapse: collapse; table-layout: fixed; font-size: 9pt;">
                    <tr>
                        <td style="width: 48%;"></td>
                        <td style="width: 4%;"></td>
                        <td style="width: 48%; text-align: center; font-weight: bold;">
                            BALAI BESAR POM DI BANDAR LAMPUNG
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center; padding-top: 4px;">Penguji</td>
                        <td></td>
                        <td style="text-align: center; padding-top: 4px;">Penyelia Kimia</td>
                    </tr>
                    <tr>
                        <!-- Kolom TTD Penguji -->
                        <td style="text-align: center; height: 60px; vertical-align: middle;">
                            <?php if (!empty($ttd_penguji_img)): ?>
                                <img src="<?php echo $ttd_penguji_img; ?>" style="max-height: 52px; max-width: 160px; object-fit: contain;" alt="TTD Penguji">
                            <?php else: ?>
                                <div style="color: #888; font-size: 7.5pt; font-style: italic;">
                                    (Tanda tangan belum diunggah)
                                </div>
                            <?php endif; ?>
                        </td>
                        <td></td>
                        <!-- Kolom TTD Penyelia -->
                        <td style="text-align: center; height: 60px; vertical-align: middle;">
                            <?php if (!empty($ttd_penyelia_img)): ?>
                                <img src="<?php echo $ttd_penyelia_img; ?>" style="max-height: 52px; max-width: 160px; object-fit: contain;" alt="TTD Penyelia">
                            <?php else: ?>
                                <div style="color: #888; font-size: 7.5pt; font-style: italic;">
                                    (Tanda tangan belum diunggah)
                                </div>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <!-- Nama & NIP Penguji -->
                        <td style="text-align: center;">
                            <div class="fw-bold" style="text-decoration: underline;"><?php echo html_escape($nama_p); ?></div>
                            <div style="font-size: 8pt; margin-top: 2px;"><?php echo html_escape($nip_p); ?></div>
                        </td>
                        <td></td>
                        <!-- Nama & NIP Penyelia -->
                        <td style="text-align: center;">
                            <div class="fw-bold" style="text-decoration: underline;"><?php echo html_escape($nama_v); ?></div>
                            <div style="font-size: 8pt; margin-top: 2px;"><?php echo html_escape($nip_v); ?></div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Catatan Footer Lembar -->
        <div style="display: flex; justify-content: space-between; font-size: 7.5pt; color: #555; margin-top: 15px; border-top: 0.5pt solid #ddd; padding-top: 4px;">
            <div>LIMSKU - Catatan Pengujian (CP) Kimia</div>
            <div>Halaman 1</div>
        </div>
    </div>
</body>
</html>
