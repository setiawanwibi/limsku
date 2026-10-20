<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * View Cetak / Simpan PDF Catatan Pengujian (CP) Kimia
 * Kode Dokumen: Form 5.10/F01 | rev (00). 01.09.14
 * BALAI BESAR PENGAWAS OBAT DAN MAKANAN DI BANDAR LAMPUNG
 * Replikasi presisi 100% dari format resmi BBPOM.
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
$ttd_approver_img = $resolve_ttd($signature_approver ?? null);

// Data Pemerian default atau dari sampel / form
$pemerian_bentuk = !empty($sampel['bentuk_sediaan']) ? $sampel['bentuk_sediaan'] : (!empty($sampel['pemerian_bentuk']) ? $sampel['pemerian_bentuk'] : 'Tablet');
$pemerian_warna  = !empty($sampel['pemerian_warna']) ? $sampel['pemerian_warna'] : 'Putih';
$pemerian_bau    = !empty($sampel['pemerian_bau']) ? $sampel['pemerian_bau'] : 'Normal';
$pemerian_rasa   = !empty($sampel['pemerian_rasa']) ? $sampel['pemerian_rasa'] : '-';

// Cek apakah ada form dengan parameter Pemerian
foreach ($forms as $f_item) {
    if (stripos($f_item['parameter_uji'], 'Pemerian') !== false || stripos($f_item['nama_template'], 'Pemerian') !== false) {
        $dt = !empty($f_item['data_hasil']) ? json_decode($f_item['data_hasil'], true) : [];
        if (isset($dt['parameter_values'])) $dt = array_merge($dt, $dt['parameter_values']);
        if (!empty($dt['bentuk'])) $pemerian_bentuk = $dt['bentuk'];
        if (!empty($dt['warna'])) $pemerian_warna = $dt['warna'];
        if (!empty($dt['bau'])) $pemerian_bau = $dt['bau'];
        if (!empty($dt['rasa'])) $pemerian_rasa = $dt['rasa'];
    }
}

// Format Tanggal Laporan Penguji
$tgl_penguji = !empty($sesi['waktu_selesai']) ? date('j F Y', strtotime($sesi['waktu_selesai'])) : date('j F Y');
// Bulan Indonesia
$bulan_indo = [
    'January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret',
    'April' => 'April', 'May' => 'Mei', 'June' => 'Juni',
    'July' => 'Juli', 'August' => 'Agustus', 'September' => 'September',
    'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'
];
$tgl_penguji = strtr($tgl_penguji, $bulan_indo);

// Kesimpulan Keseluruhan Sesi
$kesimpulan_sesi = 'MS terhadap Uji yang dilakukan';
$has_tms = false;
foreach ($forms as $f_item) {
    if (stripos($f_item['kesimpulan'], 'TMS') !== false || stripos($f_item['kesimpulan'], 'Tidak Memenuhi') !== false) {
        $has_tms = true;
        break;
    }
}
if ($has_tms) {
    $kesimpulan_sesi = 'TMS terhadap Uji yang dilakukan';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catatan Pengujian Kimia - <?php echo html_escape($sampel['nama_sampel'] ?? 'Sampel'); ?></title>
    <style>
        /* Reset and Print Setup */
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
            padding: 20px 0;
            background-color: #525659;
            font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
            font-size: 8.5pt;
            color: #000;
            line-height: 1.25;
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

        /* Document Paper */
        .document-wrapper {
            width: 210mm;
            min-height: 297mm;
            max-height: 297mm;
            margin: 0 auto 30px auto;
            padding: 10mm 12mm 8mm 12mm;
            background-color: #fff;
            box-shadow: 0 0 15px rgba(0,0,0,0.3);
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        /* Header Document Code */
        .doc-code-top {
            text-align: right;
            font-size: 8.5pt;
            line-height: 1.2;
            margin-bottom: 2px;
        }
        .doc-code-top strong {
            font-size: 9pt;
        }

        .instansi-title {
            text-align: center;
            font-size: 10.5pt;
            font-weight: bold;
            margin-top: 4px;
            margin-bottom: 5px;
            letter-spacing: 0.2px;
        }

        .double-line {
            border-top: 2.5pt solid #000;
            border-bottom: 0.75pt solid #000;
            height: 4px;
            margin-bottom: 12px;
        }

        .cp-heading {
            text-align: center;
            font-size: 12pt;
            font-weight: bold;
            letter-spacing: 0.5px;
            margin-bottom: 14px;
        }

        /* Double border outer box */
        .outer-box {
            border: 2pt solid #000;
            padding: 0;
            margin-bottom: 10px;
        }

        table.cp-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-size: 8.5pt;
        }

        table.cp-table td, table.cp-table th {
            vertical-align: top;
            padding: 4px 6px;
            word-wrap: break-word;
        }

        .border-b-single {
            border-bottom: 1pt solid #000;
        }
        .border-t-single {
            border-top: 1pt solid #000;
        }
        .border-r-single {
            border-right: 1pt solid #000;
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
                overflow: hidden !important;
                background: none !important;
            }
            .no-print {
                display: none !important;
            }
            .document-wrapper {
                margin: 0 !important;
                padding: 8mm 12mm 6mm 12mm !important;
                box-shadow: none !important;
                width: 100% !important;
                height: 100% !important;
                max-height: 297mm !important;
                page-break-after: avoid !important;
                page-break-inside: avoid !important;
                page-break-before: avoid !important;
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

    <div class="document-wrapper">
        <div>
            <!-- Header Dokumen Atas Kanan -->
            <div class="doc-code-top">
                <strong>Form 5.10/F01</strong><br>
                <span style="font-size: 7.5pt;">rev (00). 01.09.14</span>
            </div>

            <!-- Nama Balai & Garis Ganda -->
            <div class="instansi-title">
                BALAI BESAR PENGAWAS OBAT DAN MAKANAN DI BANDAR LAMPUNG
            </div>
            <div class="double-line"></div>

            <!-- Judul Catatan Pengujian -->
            <div class="cp-heading">
                CATATAN PENGUJIAN
            </div>

            <!-- Kotak Utama Bingkai Ganda -->
            <div class="outer-box">
                <!-- Baris Identitas Sampel -->
                <table class="cp-table border-b-single">
                    <colgroup>
                        <col style="width: 14%;">
                        <col style="width: 38%;">
                        <col style="width: 16%;">
                        <col style="width: 32%;">
                    </colgroup>
                    <tr>
                        <td class="fw-bold" style="padding-top: 6px;">Kode Sampel:</td>
                        <td class="border-r-single" style="padding-top: 6px;">
                            <div class="fw-bold"><?php echo html_escape(!empty($sampel['kode_sampel_manual']) ? $sampel['kode_sampel_manual'] : (!empty($sampel['no']) ? $sampel['no'] : 'SMP-' . $sampel['id'])); ?></div>
                            <div style="font-size: 8pt; color: #333; margin-top: 1px;"><?php echo html_escape($sampel['no_sipt'] ?? '26.088.101.01.0014'); ?></div>
                            <div style="font-size: 8pt; color: #444; margin-top: 1px;"><?php echo html_escape($sampel['balai_penguji'] ?? 'BBPOM di Bandar Lampung'); ?></div>
                        </td>
                        <td class="fw-bold" style="padding-top: 6px; padding-left: 12px;">Jenis Sampel:</td>
                        <td style="padding-top: 6px;">
                            <?php echo html_escape($sampel['kategori_sampel'] ?? 'Obat'); ?>
                        </td>
                    </tr>
                </table>

                <!-- Bagian Hasil Pengujian & Pemerian -->
                <div style="padding: 8px 10px 4px 10px;">
                    <div class="fw-bold" style="font-size: 9pt; margin-bottom: 6px;">Hasil Pengujian</div>
                    <div class="fw-bold" style="font-size: 8.5pt; margin-bottom: 4px;">Pemerian:</div>

                    <table class="cp-table" style="margin-bottom: 12px;">
                        <colgroup>
                            <col style="width: 25%;">
                            <col style="width: 22%;">
                            <col style="width: 25%;">
                            <col style="width: 28%;">
                        </colgroup>
                        <tr>
                            <td>
                                <div><span class="fw-bold">Bentuk:</span></div>
                                <div style="margin-top: 2px; line-height: 1.25;"><?php echo nl2br(html_escape($pemerian_bentuk)); ?></div>
                            </td>
                            <td>
                                <div><span class="fw-bold">Warna:</span></div>
                                <div style="margin-top: 2px;"><?php echo html_escape($pemerian_warna); ?></div>
                            </td>
                            <td>
                                <div><span class="fw-bold">Bau:</span></div>
                                <div style="margin-top: 2px;"><?php echo html_escape($pemerian_bau); ?></div>
                            </td>
                            <td>
                                <div><span class="fw-bold">Rasa:</span></div>
                                <div style="margin-top: 2px;"><?php echo html_escape($pemerian_rasa); ?></div>
                            </td>
                        </tr>
                    </table>

                    <!-- Header Kolom Uji yang dilakukan -->
                    <table class="cp-table" style="margin-bottom: 6px;">
                        <colgroup>
                            <col style="width: 22%;">
                            <col style="width: 16%;">
                            <col style="width: 22%;">
                            <col style="width: 20%;">
                            <col style="width: 20%;">
                        </colgroup>
                        <tr style="font-weight: bold; font-size: 8.5pt;">
                            <td>Uji yang dilakukan</td>
                            <td>Cara</td>
                            <td>Hasil</td>
                            <td>Syarat</td>
                            <td>Pustaka</td>
                        </tr>
                    </table>

                    <!-- Daftar Parameter Uji Dinamis -->
                    <?php if (!empty($forms)): ?>
                        <?php foreach ($forms as $f_idx => $form_item): ?>
                            <?php
                                // Abaikan form khusus Pemerian jika sudah dirender di atas
                                if (stripos($form_item['parameter_uji'], 'Pemerian') !== false || stripos($form_item['nama_template'], 'Pemerian') !== false) {
                                    continue;
                                }

                                $f_data = !empty($form_item['data_hasil']) ? json_decode($form_item['data_hasil'], true) : [];
                                if (isset($f_data['parameter_values'])) {
                                    $f_data = array_merge($f_data, $f_data['parameter_values']);
                                }

                                // Ekstraksi nilai
                                $cara_analisa = !empty($form_item['metode_analisa']) ? $form_item['metode_analisa'] : (!empty($f_data['metode_analisa']) ? $f_data['metode_analisa'] : '-');
                                $hasil_uji_val = !empty($form_item['hasil_uji']) ? $form_item['hasil_uji'] : (!empty($f_data['hasil_uji']) ? $f_data['hasil_uji'] : (!empty($form_item['kesimpulan']) ? $form_item['kesimpulan'] : '-'));
                                $syarat_val = !empty($form_item['persyaratan']) ? $form_item['persyaratan'] : (!empty($f_data['syarat']) ? $f_data['syarat'] : (!empty($f_data['persyaratan']) ? $f_data['persyaratan'] : '-'));
                                $pustaka_val = !empty($form_item['pustaka_acuan']) ? $form_item['pustaka_acuan'] : (!empty($f_data['pustaka_metode']) ? $f_data['pustaka_metode'] : (!empty($f_data['pustaka_acuan']) ? $f_data['pustaka_acuan'] : '-'));

                                $nama_zat = !empty($form_item['nama_zat_aktif']) ? $form_item['nama_zat_aktif'] : ($sesi['nama_zat_aktif'] ?? '');
                                $param_title = $form_item['parameter_uji'] ?: $form_item['nama_template'];
                            ?>

                            <table class="cp-table" style="margin-bottom: 14px;">
                                <colgroup>
                                    <col style="width: 22%;">
                                    <col style="width: 16%;">
                                    <col style="width: 22%;">
                                    <col style="width: 20%;">
                                    <col style="width: 20%;">
                                </colgroup>
                                <tr>
                                    <!-- Kolom 1: Uji & Zat -->
                                    <td>
                                        <div class="fw-bold"><?php echo html_escape($param_title); ?></div>
                                        <?php if (!empty($nama_zat)): ?>
                                            <div style="font-size: 8pt; color: #222; margin-top: 1px;"><?php echo html_escape($nama_zat); ?></div>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Kolom 2: Cara -->
                                    <td>
                                        <div><?php echo html_escape($cara_analisa); ?></div>
                                    </td>

                                    <!-- Kolom 3: Hasil -->
                                    <td>
                                        <div><?php echo nl2br(html_escape($hasil_uji_val)); ?></div>
                                    </td>

                                    <!-- Kolom 4: Syarat -->
                                    <td>
                                        <div><?php echo nl2br(html_escape($syarat_val)); ?></div>
                                    </td>

                                    <!-- Kolom 5: Pustaka -->
                                    <td>
                                        <div><?php echo html_escape($pustaka_val); ?></div>
                                    </td>
                                </tr>
                            </table>

                        <?php endforeach; ?>
                    <?php else: ?>
                        <div style="padding: 15px; text-align: center; color: #888; font-style: italic;">
                            Belum ada parameter uji yang terdaftar dalam sesi pengujian ini.
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Baris Kesimpulan Sesi -->
                <table class="cp-table border-t-single border-b-single">
                    <colgroup>
                        <col style="width: 14%;">
                        <col style="width: 2%;">
                        <col style="width: 84%;">
                    </colgroup>
                    <tr style="height: 22px;">
                        <td class="fw-bold" style="padding-left: 10px;">Kesimpulan</td>
                        <td>:</td>
                        <td class="fw-bold"><?php echo html_escape($kesimpulan_sesi); ?></td>
                    </tr>
                    <tr style="height: 20px;">
                        <td class="fw-bold" style="padding-left: 10px;">Catatan</td>
                        <td>:</td>
                        <td><?php echo html_escape(!empty($sesi['catatan']) ? $sesi['catatan'] : '-'); ?></td>
                    </tr>
                </table>

                <!-- Bagian Tanda Tangan Pengesahan CP Kimia -->
                <table class="cp-table">
                    <colgroup>
                        <col style="width: 32%;">
                        <col style="width: 32%;">
                        <col style="width: 36%;">
                    </colgroup>
                    <tr>
                        <!-- Kolom Tanda Tangan Penguji -->
                        <td class="border-r-single" style="padding: 6px 10px 10px 10px;">
                            <div class="fw-bold" style="margin-bottom: 6px;">Tanda Tangan Penguji</div>
                            <div style="display: flex; gap: 8px; margin-bottom: 4px;">
                                <span>a.</span>
                                <div>
                                    <?php if (!empty($ttd_penguji_img)): ?>
                                        <div style="height: 42px; display: flex; align-items: center;">
                                            <img src="<?php echo $ttd_penguji_img; ?>" style="max-height: 40px; max-width: 130px; object-fit: contain;" alt="TTD Penguji">
                                        </div>
                                    <?php else: ?>
                                        <div style="height: 38px; display: flex; align-items: center; color: #888; font-size: 7pt; font-style: italic;">
                                            [ Tanda Tangan Digital Penguji ]
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div style="display: flex; gap: 8px; color: #777; font-size: 8pt; margin-top: 2px;">
                                <span>b.</span>
                            </div>
                            <div style="display: flex; gap: 8px; color: #777; font-size: 8pt; margin-top: 2px;">
                                <span>c.</span>
                            </div>
                        </td>

                        <!-- Kolom Nama Penguji -->
                        <td class="border-r-single" style="padding: 6px 10px 10px 10px;">
                            <div class="fw-bold" style="margin-bottom: 6px;">Nama Penguji</div>
                            <div style="display: flex; gap: 8px; margin-bottom: 4px;">
                                <span class="fw-bold">a.</span>
                                <span class="fw-bold"><?php echo html_escape($penguji['nama_lengkap'] ?? ($sesi['nama_penguji'] ?? '-')); ?></span>
                            </div>
                            <div style="display: flex; gap: 8px; color: #777; font-size: 8pt; margin-top: 14px;">
                                <span>b.</span>
                            </div>
                            <div style="display: flex; gap: 8px; color: #777; font-size: 8pt; margin-top: 14px;">
                                <span>c.</span>
                            </div>
                        </td>

                        <!-- Kolom Tanggal & Penyelia / Manajer Teknis -->
                        <td style="padding: 6px 10px 10px 10px;">
                            <div class="border-b-single" style="padding-bottom: 6px; margin-bottom: 8px;">
                                <div class="fw-bold">Tanggal dilaporkan penguji</div>
                                <div style="margin-top: 2px;"><?php echo html_escape($tgl_penguji); ?></div>
                            </div>
                            <div>
                                <div class="fw-bold">Catatan pengujian</div>
                                <div class="fw-bold">Diperiksa Oleh</div>
                                <div class="fw-bold" style="margin-bottom: 4px;">Penyelia / Manajer Teknis</div>

                                <?php if ($status_sesi === 'Approved / Final'): ?>
                                    <div style="display: flex; gap: 4px; justify-content: space-between; align-items: flex-end; margin-top: 2px;">
                                        <!-- Penyelia -->
                                        <div style="flex: 1; text-align: center; border-right: 1px dashed #ccc; padding-right: 4px;">
                                            <div style="font-size: 7pt; color: #555; margin-bottom: 1px;">Penyelia</div>
                                            <?php if (!empty($ttd_penyelia_img)): ?>
                                                <div style="height: 36px; display: flex; align-items: center; justify-content: center;">
                                                    <img src="<?php echo $ttd_penyelia_img; ?>" style="max-height: 34px; max-width: 90px; object-fit: contain;" alt="TTD Penyelia">
                                                </div>
                                            <?php else: ?>
                                                <div style="height: 28px; display: flex; align-items: center; justify-content: center; color: #198754; font-size: 6.8pt; font-weight: bold;">
                                                    [ VERIFIED ]
                                                </div>
                                            <?php endif; ?>
                                            <div style="font-weight: bold; font-size: 7.5pt; margin-top: 1px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?php echo html_escape($verifier['nama_lengkap'] ?? ($sesi['nama_verifier'] ?? 'Penyelia')); ?>">
                                                <?php echo html_escape($verifier['nama_lengkap'] ?? ($sesi['nama_verifier'] ?? 'Penyelia')); ?>
                                            </div>
                                        </div>
                                        <!-- Manajer Teknis -->
                                        <div style="flex: 1; text-align: center; padding-left: 4px;">
                                            <div style="font-size: 7pt; color: #555; margin-bottom: 1px;">Manajer Teknis</div>
                                            <?php if (!empty($ttd_approver_img)): ?>
                                                <div style="height: 36px; display: flex; align-items: center; justify-content: center;">
                                                    <img src="<?php echo $ttd_approver_img; ?>" style="max-height: 34px; max-width: 90px; object-fit: contain;" alt="TTD MT">
                                                </div>
                                            <?php else: ?>
                                                <div style="height: 28px; display: flex; align-items: center; justify-content: center; color: #0d6efd; font-size: 6.8pt; font-weight: bold;">
                                                    [ APPROVED ]
                                                </div>
                                            <?php endif; ?>
                                            <div style="font-weight: bold; font-size: 7.5pt; margin-top: 1px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?php echo html_escape($approver['nama_lengkap'] ?? ($sesi['nama_approver'] ?? 'Manajer Teknis')); ?>">
                                                <?php echo html_escape($approver['nama_lengkap'] ?? ($sesi['nama_approver'] ?? 'Manajer Teknis')); ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php elseif ($status_sesi === 'Menunggu Approval'): ?>
                                    <div style="margin-top: 2px;">
                                        <div style="font-size: 7.2pt; color: #555;">Penyelia</div>
                                        <?php if (!empty($ttd_penyelia_img)): ?>
                                            <div style="height: 36px; display: flex; align-items: center;">
                                                <img src="<?php echo $ttd_penyelia_img; ?>" style="max-height: 34px; max-width: 140px; object-fit: contain;" alt="TTD Penyelia">
                                            </div>
                                        <?php else: ?>
                                            <div style="height: 28px; display: flex; align-items: center; color: #198754; font-size: 7.2pt; font-weight: bold;">
                                                [ VERIFIED ]
                                            </div>
                                        <?php endif; ?>
                                        <div style="font-weight: bold; font-size: 8pt; margin-top: 1px;">
                                            <?php echo html_escape($verifier['nama_lengkap'] ?? ($sesi['nama_verifier'] ?? 'Penyelia')); ?>
                                        </div>
                                        <div style="font-size: 7pt; color: #888; font-style: italic; margin-top: 2px;">(Menunggu Approval Manajer Teknis)</div>
                                    </div>
                                <?php else: ?>
                                    <div style="height: 28px; display: flex; align-items: center; color: #888; font-size: 7.5pt; font-style: italic;">
                                        (Menunggu Verifikasi Penyelia)
                                    </div>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Catatan Footer Lembar -->
        <div style="text-align: right; font-size: 7.5pt; margin-top: 6px;">
            Lembar 1 dari 1 halaman
        </div>
    </div>
</body>
</html>
