<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * View Cetak / Simpan PDF Lembar Catatan Pengujian (LCP)
 * REAKSI WARNA / PENGENDAPAN (Bidang Kimia)
 * Replikasi presisi 100% dari format resmi BPOM.
 */

$status_sesi = $sesi['status'] ?? 'Draft';
$p = !empty($hasil['data_hasil']) ? json_decode($hasil['data_hasil'], true) : array();
if (isset($p['parameter_values']) && is_array($p['parameter_values'])) {
    $p = array_merge($p, $p['parameter_values']);
}

$tabel_reaksi = isset($p['tabel_reaksi']) && is_array($p['tabel_reaksi']) ? $p['tabel_reaksi'] : array();

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

// Cek foto hasil pengamatan jika ada
$foto_pengamatan_url = '';
if (!empty($p['foto_pengamatan'])) {
    $foto_path = FCPATH . $p['foto_pengamatan'];
    if (file_exists($foto_path)) {
        $foto_pengamatan_url = base_url($p['foto_pengamatan']);
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak LCP - REAKSI WARNA / PENGENDAPAN - <?php echo html_escape($sampel['nama_sampel'] ?? 'Sampel'); ?></title>
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
            padding: 6mm 10mm 6mm 10mm;
            background-color: #fff;
            box-shadow: 0 0 15px rgba(0,0,0,0.3);
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        /* Table Grid */
        table.lcp-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
            font-size: 8.5pt;
            color: #000;
        }

        table.lcp-table td, table.lcp-table th {
            padding: 3px 5px;
            vertical-align: middle;
            word-wrap: break-word;
        }

        .border-all {
            border: 1pt solid #000;
        }

        .border-t { border-top: 1pt solid #000; }
        .border-b { border-bottom: 1pt solid #000; }
        .border-l { border-left: 1pt solid #000; }
        .border-r { border-right: 1pt solid #000; }

        .border-thick-t { border-top: 1.5pt solid #000; }
        .border-thick-b { border-bottom: 1.5pt solid #000; }
        .border-thick-l { border-left: 1.5pt solid #000; }
        .border-thick-r { border-right: 1.5pt solid #000; }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .fw-bold { font-weight: bold; }

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
                padding: 5mm 8mm 5mm 8mm !important;
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
            <!-- Header Logo & Judul -->
            <table class="lcp-table" style="margin-bottom: 12px;">
                <colgroup>
                    <col style="width: 18%;">
                    <col style="width: 82%;">
                </colgroup>
                <tr>
                    <td class="text-center" style="vertical-align: middle;">
                        <img src="<?php echo base_url('assets/img/bpom_logo_excel.png'); ?>" style="height: 52px; width: auto; max-width: 95%; display: block; margin: 0 auto;" alt="Logo BPOM">
                    </td>
                    <td class="text-center" style="vertical-align: middle; padding-right: 40px;">
                        <div style="font-size: 11pt; font-weight: bold; letter-spacing: 0.5px;">LAMPIRAN CATATAN PENGUJIAN</div>
                        <div style="font-size: 10.5pt; font-weight: bold; margin-top: 1px;">REAKSI WARNA / PENGENDAPAN</div>
                        <div style="font-size: 8.5pt; font-weight: bold; margin-top: 6px;">
                            Laboratorium Kimia Balai Besar/Balai/Loka POM di .........................
                        </div>
                    </td>
                </tr>
            </table>

            <!-- Tabel Informasi Sampel -->
            <table class="lcp-table border-all" style="margin-bottom: 0;">
                <colgroup>
                    <col style="width: 14%;">
                    <col style="width: 2%;">
                    <col style="width: 37%;">
                    <col style="width: 18%;">
                    <col style="width: 2%;">
                    <col style="width: 27%;">
                </colgroup>
                <tr style="height: 20px;">
                    <td class="border-t border-l" style="padding-left: 6px;">Nama sampel</td>
                    <td class="border-t">:</td>
                    <td class="border-t border-r fw-bold"><?php echo html_escape($sampel['nama_sampel'] ?? '-'); ?></td>
                    <td class="border-t border-l" style="padding-left: 6px;">No. Kode sampel</td>
                    <td class="border-t">:</td>
                    <td class="border-t border-r"><?php echo html_escape(!empty($sampel['kode_sampel_manual']) ? $sampel['kode_sampel_manual'] : (!empty($sampel['no']) ? 'NO-' . $sampel['no'] : 'SMP-' . $sampel['id'])); ?></td>
                </tr>
                <tr style="height: 20px;">
                    <td class="border-l" style="padding-left: 6px;"></td>
                    <td></td>
                    <td class="border-r"></td>
                    <td class="border-l" style="padding-left: 6px;">No. Bets</td>
                    <td>:</td>
                    <td class="border-r"><?php echo html_escape($sampel['no_bets'] ?? '-'); ?></td>
                </tr>
                <tr style="height: 20px;">
                    <td class="border-b border-l" style="padding-left: 6px;">Zat yang diuji</td>
                    <td class="border-b">:</td>
                    <td class="border-b border-r fw-bold"><?php echo html_escape($p['analit'] ?? ($hasil['nama_zat_aktif'] ?? ($metode['nama_zat_aktif'] ?? '-'))); ?></td>
                    <td class="border-b border-l" style="padding-left: 6px;">Tanggal Uji</td>
                    <td class="border-b">:</td>
                    <td class="border-b border-r"><?php echo !empty($sesi['waktu_mulai']) ? date('d-m-Y', strtotime($sesi['waktu_mulai'])) : date('d-m-Y'); ?></td>
                </tr>
                <!-- 1 Baris Kosong Pemisah Sebelum Header Tabel Nama Zat -->
                <tr style="height: 20px;">
                    <td colspan="6" class="border-b border-l border-r"></td>
                </tr>
            </table>

            <!-- Tabel Utama Pengamatan Reaksi Warna / Pengendapan -->
            <table class="lcp-table border-all" style="border-top: none;">
                <colgroup>
                    <col style="width: 20%;">
                    <col style="width: 15%;">
                    <col style="width: 20%;">
                    <col style="width: 30%;">
                    <col style="width: 15%;">
                </colgroup>
                <thead>
                    <tr style="height: 28px; background-color: #fff;">
                        <th class="border-all text-center fw-bold">Nama zat</th>
                        <th class="border-all text-center fw-bold">Bobot (satuan)</th>
                        <th class="border-all text-center fw-bold">Pereaksi</th>
                        <th class="border-all text-center fw-bold">Pengamatan</th>
                        <th class="border-all text-center fw-bold" style="line-height: 1.15;">Hasil<br>(Positif/Negatif)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $r0 = $tabel_reaksi[0] ?? array();
                        $r1 = $tabel_reaksi[1] ?? array();
                        $r2 = $tabel_reaksi[2] ?? array();
                        $r3 = $tabel_reaksi[3] ?? array();
                        $r4 = $tabel_reaksi[4] ?? array();
                    ?>
                    <!-- Baris 1: Baku Pembanding -->
                    <tr style="height: 28px;">
                        <td class="border-all" style="padding-left: 6px;">Baku Pembanding &nbsp;:</td>
                        <td class="border-all text-center"><?php echo html_escape($r0['bobot'] ?? ''); ?></td>
                        <td class="border-all"><?php echo html_escape($r0['pereaksi'] ?? ''); ?></td>
                        <td class="border-all"><?php echo html_escape($r0['pengamatan'] ?? ''); ?></td>
                        <td class="border-all text-center fw-bold"><?php echo html_escape($r0['hasil'] ?? ''); ?></td>
                    </tr>
                    <!-- Baris 2: Zat Uji 1 -->
                    <tr style="height: 28px;">
                        <td class="border-all" style="padding-left: 6px;">
                            <div>Zat Uji</div>
                            <div>1. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</div>
                        </td>
                        <td class="border-all text-center"><?php echo html_escape($r1['bobot'] ?? ''); ?></td>
                        <td class="border-all"><?php echo html_escape($r1['pereaksi'] ?? ''); ?></td>
                        <td class="border-all"><?php echo html_escape($r1['pengamatan'] ?? ''); ?></td>
                        <td class="border-all text-center fw-bold"><?php echo html_escape($r1['hasil'] ?? ''); ?></td>
                    </tr>
                    <!-- Baris 3: Zat Uji 2 -->
                    <tr style="height: 28px;">
                        <td class="border-all" style="padding-left: 6px;">2. &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</td>
                        <td class="border-all text-center"><?php echo html_escape($r2['bobot'] ?? ''); ?></td>
                        <td class="border-all"><?php echo html_escape($r2['pereaksi'] ?? ''); ?></td>
                        <td class="border-all"><?php echo html_escape($r2['pengamatan'] ?? ''); ?></td>
                        <td class="border-all text-center fw-bold"><?php echo html_escape($r2['hasil'] ?? ''); ?></td>
                    </tr>
                    <!-- Baris 4: Spiked sample - Zat Uji -->
                    <tr style="height: 28px;">
                        <td class="border-all" style="padding-left: 6px;">
                            <div style="font-style: italic;">Spiked sample :</div>
                            <div>Zat Uji &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:</div>
                        </td>
                        <td class="border-all text-center"><?php echo html_escape($r3['bobot'] ?? ''); ?></td>
                        <td class="border-all"><?php echo html_escape($r3['pereaksi'] ?? ''); ?></td>
                        <td class="border-all"><?php echo html_escape($r3['pengamatan'] ?? ''); ?></td>
                        <td class="border-all text-center fw-bold"><?php echo html_escape($r3['hasil'] ?? ''); ?></td>
                    </tr>
                    <!-- Baris 5: Spiked sample - Baku Pembanding -->
                    <tr style="height: 28px;">
                        <td class="border-all" style="padding-left: 6px;">Baku Pembanding &nbsp;:</td>
                        <td class="border-all text-center"><?php echo html_escape($r4['bobot'] ?? ''); ?></td>
                        <td class="border-all"><?php echo html_escape($r4['pereaksi'] ?? ''); ?></td>
                        <td class="border-all"><?php echo html_escape($r4['pengamatan'] ?? ''); ?></td>
                        <td class="border-all text-center fw-bold"><?php echo html_escape($r4['hasil'] ?? ''); ?></td>
                    </tr>
                </tbody>
            </table>

            <!-- Area Hasil Pengamatan : Foto & LOD -->
            <table class="lcp-table border-all" style="border-top: none;">
                <colgroup>
                    <col style="width: 17%;">
                    <col style="width: 2%;">
                    <col style="width: 35%;">
                    <col style="width: 10%;">
                    <col style="width: 2%;">
                    <col style="width: 34%;">
                </colgroup>
                <tr>
                    <td class="border-l" style="padding-left: 6px; vertical-align: top;">Hasil Pengamatan</td>
                    <td style="vertical-align: top;">:</td>
                    <td colspan="4" class="border-r" style="vertical-align: top;">
                        <span><?php echo html_escape($p['keterangan_foto'] ?? 'foto hasil pengamatan terlampir'); ?></span>
                    </td>
                </tr>
                <tr>
                    <td class="border-l" style="padding-left: 6px;"></td>
                    <td></td>
                    <td>
                        Analit : &nbsp;<span class="fw-bold"><?php echo html_escape($p['analit'] ?? ($hasil['nama_zat_aktif'] ?? ($metode['nama_zat_aktif'] ?? '-'))); ?></span>
                    </td>
                    <td>LOD</td>
                    <td>:</td>
                    <td class="border-r"><?php echo html_escape($p['lod'] ?? '-'); ?></td>
                </tr>
                <!-- Kotak Foto Lampiran Pengamatan -->
                <tr>
                    <td class="border-l border-r" colspan="6" style="padding: 6px 12px; height: 160px; vertical-align: middle; text-align: center; border-bottom: none;">
                        <?php if (!empty($foto_pengamatan_url)): ?>
                            <div style="height: 150px; display: flex; align-items: center; justify-content: center;">
                                <img src="<?php echo $foto_pengamatan_url; ?>" style="max-height: 145px; max-width: 90%; object-fit: contain; border: 1px solid #ccc; padding: 2px;" alt="Foto Hasil Pengamatan">
                            </div>
                        <?php else: ?>
                            <div style="height: 140px; border: 1px dashed #aaa; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #777; background: #fafafa; border-radius: 4px;">
                                <svg width="28" height="28" fill="#999" viewBox="0 0 16 16" style="margin-bottom: 6px;">
                                    <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                                    <path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-12zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1h12z"/>
                                </svg>
                                <span style="font-size: 8pt; font-style: italic;">[ Lampiran Foto Hasil Pengamatan Reaksi Warna / Pengendapan ]</span>
                            </div>
                        <?php endif; ?>
                    </td>
                </tr>
            </table>

            <!-- Pustaka Metode, Pustaka Persyaratan & Syarat -->
            <table class="lcp-table border-all" style="border-top: none;">
                <colgroup>
                    <col style="width: 17%;">
                    <col style="width: 2%;">
                    <col style="width: 41%;">
                    <col style="width: 10%;">
                    <col style="width: 2%;">
                    <col style="width: 28%;">
                </colgroup>
                <tr style="height: 22px;">
                    <td class="border-l" style="padding-left: 6px;">Pustaka Metode</td>
                    <td>:</td>
                    <td colspan="4" class="border-r"><?php echo html_escape($p['pustaka_metode'] ?? ($hasil['pustaka_acuan'] ?? ($metode['pustaka_acuan'] ?? '-'))); ?></td>
                </tr>
                <tr style="height: 22px;">
                    <td class="border-l border-b" style="padding-left: 6px;">Pustaka Persyaratan</td>
                    <td class="border-b">:</td>
                    <td class="border-b"><?php echo html_escape($p['pustaka_persyaratan'] ?? ($hasil['pustaka_acuan'] ?? ($metode['pustaka_acuan'] ?? '-'))); ?></td>
                    <td class="border-b">Syarat</td>
                    <td class="border-b">:</td>
                    <td class="border-b border-r fw-bold"><?php echo html_escape($p['syarat'] ?? ($hasil['persyaratan'] ?? ($metode['persyaratan'] ?? '-'))); ?></td>
                </tr>
            </table>

            <!-- Kesimpulan -->
            <table class="lcp-table border-all" style="border-top: none;">
                <colgroup>
                    <col style="width: 17%;">
                    <col style="width: 2%;">
                    <col style="width: 81%;">
                </colgroup>
                <tr style="height: 24px;">
                    <td class="border-l border-b" style="padding-left: 6px;">Kesimpulan*</td>
                    <td class="border-b">:</td>
                    <td class="border-b border-r">
                        <?php
                            $pil_kes = $p['pilihan_kesimpulan'] ?? ($hasil['kesimpulan'] ?? '');
                            $diduga_text = !empty($p['diduga_mengandung_text']) ? $p['diduga_mengandung_text'] : '......';

                            $is_ms = (strpos($pil_kes, 'MS') !== false && strpos($pil_kes, 'TMS') === false);
                            $is_tms = (strpos($pil_kes, 'TMS') !== false);
                            $is_diduga = (stripos($pil_kes, 'Diduga') !== false);

                            $ms_render = $is_ms ? '<strong style="background:#e8f5e9; padding:2px 6px; border:1px solid #2e7d32; border-radius:3px;">MS</strong>' : 'MS';
                            $tms_render = $is_tms ? '<strong style="background:#ffebee; padding:2px 6px; border:1px solid #c62828; border-radius:3px;">TMS</strong>' : 'TMS';
                            $diduga_render = $is_diduga ? '<strong style="background:#fff3e0; padding:2px 6px; border:1px solid #ef6c00; border-radius:3px;">Diduga mengandung <u>' . html_escape($diduga_text) . '</u></strong>' : 'Diduga mengandung ......';
                        ?>
                        <span><?php echo $ms_render; ?></span> &nbsp;/&nbsp; 
                        <span><?php echo $tms_render; ?></span> &nbsp;/&nbsp; 
                        <span><?php echo $diduga_render; ?></span>
                    </td>
                </tr>
            </table>

            <!-- Tanda Tangan & Pengesahan -->
            <table class="lcp-table border-all" style="border-top: none;">
                <colgroup>
                    <col style="width: 50%;">
                    <col style="width: 50%;">
                </colgroup>
                <tr style="height: 22px;">
                    <td class="border-l border-r" style="padding-left: 6px;">
                        Penguji : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Nama dan paraf
                    </td>
                    <td class="border-r" style="padding-left: 6px;">
                        Diperiksa Penyelia/ verifikator : &nbsp;&nbsp;&nbsp;&nbsp; Nama dan paraf
                    </td>
                </tr>
                <!-- Area TTD Gambar -->
                <tr style="height: 48px;">
                    <td class="border-l border-r" style="vertical-align: middle; padding-left: 20px;">
                        <?php if (!empty($ttd_penguji_img)): ?>
                            <div style="height: 38px; display: flex; align-items: center;">
                                <img src="<?php echo $ttd_penguji_img; ?>" style="max-height: 36px; max-width: 150px; object-fit: contain;" alt="TTD Penguji">
                            </div>
                        <?php else: ?>
                            <div style="height: 30px; display: flex; align-items: center; color: #888; font-size: 7.5pt; font-style: italic;">
                                [ Tanda Tangan Digital Penguji ]
                            </div>
                        <?php endif; ?>
                        <div style="font-weight: bold; margin-top: 2px; font-size: 8.5pt;">
                            <?php echo html_escape($penguji['nama_lengkap'] ?? ($sesi['nama_penguji'] ?? '-')); ?>
                        </div>
                    </td>
                    <td class="border-r" style="vertical-align: middle; padding: 4px 10px;">
                        <?php if ($status_sesi === 'Approved / Final'): ?>
                            <div style="display: flex; gap: 8px; align-items: flex-end;">
                                <!-- Penyelia -->
                                <div style="flex: 1; border-right: 1px dashed #ccc; padding-right: 6px;">
                                    <div style="font-size: 7pt; color: #555; margin-bottom: 1px;">Penyelia:</div>
                                    <?php if (!empty($ttd_penyelia_img)): ?>
                                        <div style="height: 36px; display: flex; align-items: center;">
                                            <img src="<?php echo $ttd_penyelia_img; ?>" style="max-height: 34px; max-width: 100px; object-fit: contain;" alt="TTD Penyelia">
                                        </div>
                                    <?php else: ?>
                                        <div style="height: 28px; display: flex; align-items: center; color: #198754; font-size: 7pt; font-weight: bold;">[ VERIFIED ]</div>
                                    <?php endif; ?>
                                    <div style="font-weight: bold; font-size: 7.8pt; margin-top: 1px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?php echo html_escape($verifier['nama_lengkap'] ?? ($sesi['nama_verifier'] ?? 'Penyelia')); ?>">
                                        <?php echo html_escape($verifier['nama_lengkap'] ?? ($sesi['nama_verifier'] ?? 'Penyelia')); ?>
                                    </div>
                                </div>
                                <!-- Manajer Teknis -->
                                <div style="flex: 1; padding-left: 2px;">
                                    <div style="font-size: 7pt; color: #555; margin-bottom: 1px;">Manajer Teknis:</div>
                                    <?php if (!empty($ttd_approver_img)): ?>
                                        <div style="height: 36px; display: flex; align-items: center;">
                                            <img src="<?php echo $ttd_approver_img; ?>" style="max-height: 34px; max-width: 100px; object-fit: contain;" alt="TTD MT">
                                        </div>
                                    <?php else: ?>
                                        <div style="height: 28px; display: flex; align-items: center; color: #0d6efd; font-size: 7pt; font-weight: bold;">[ APPROVED ]</div>
                                    <?php endif; ?>
                                    <div style="font-weight: bold; font-size: 7.8pt; margin-top: 1px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?php echo html_escape($approver['nama_lengkap'] ?? ($sesi['nama_approver'] ?? 'Manajer Teknis')); ?>">
                                        <?php echo html_escape($approver['nama_lengkap'] ?? ($sesi['nama_approver'] ?? 'Manajer Teknis')); ?>
                                    </div>
                                </div>
                            </div>
                        <?php elseif ($status_sesi === 'Menunggu Approval'): ?>
                            <div>
                                <div style="font-size: 7pt; color: #555;">Penyelia:</div>
                                <?php if (!empty($ttd_penyelia_img)): ?>
                                    <div style="height: 36px; display: flex; align-items: center;">
                                        <img src="<?php echo $ttd_penyelia_img; ?>" style="max-height: 34px; max-width: 140px; object-fit: contain;" alt="TTD Penyelia">
                                    </div>
                                <?php else: ?>
                                    <div style="height: 28px; display: flex; align-items: center; color: #198754; font-size: 7pt; font-weight: bold;">
                                        [ VERIFIED DIGITAL ]
                                    </div>
                                <?php endif; ?>
                                <div style="font-weight: bold; font-size: 8pt; margin-top: 1px;">
                                    <?php echo html_escape($verifier['nama_lengkap'] ?? ($sesi['nama_verifier'] ?? 'Penyelia')); ?>
                                </div>
                                <div style="font-size: 6.8pt; color: #888; font-style: italic;">(Menunggu Approval Manajer Teknis)</div>
                            </div>
                        <?php else: ?>
                            <div style="height: 30px; display: flex; align-items: center; color: #888; font-size: 7.5pt; font-style: italic;">
                                (Menunggu Verifikasi Penyelia)
                            </div>
                            <div style="font-size: 7.5pt; color: #555; margin-top: 2px;">(TTD, Nama)</div>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr style="height: 22px;">
                    <td class="border-l border-b border-r" style="padding-left: 6px;">
                        Tanggal Laporan : &nbsp;<span style="font-weight: normal;"><?php echo !empty($sesi['waktu_selesai']) ? date('d-m-Y', strtotime($sesi['waktu_selesai'])) : date('d-m-Y'); ?></span>
                    </td>
                    <td class="border-b border-r" style="padding-left: 6px;">
                        Tanggal : &nbsp;<span style="font-weight: normal;"><?php echo ($status_sesi === 'Approved / Final' || $status_sesi === 'Menunggu Approval') && !empty($sesi['waktu_verifikasi']) ? date('d-m-Y', strtotime($sesi['waktu_verifikasi'])) : '-'; ?></span>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Catatan Footer -->
        <table class="lcp-table" style="margin-top: 6px;">
            <colgroup>
                <col style="width: 50%;">
                <col style="width: 50%;">
            </colgroup>
            <tr>
                <td style="font-size: 7.5pt; font-style: italic;">Ket: *Coret yang tidak perlu</td>
                <td class="text-right" style="font-size: 7.5pt;">Lembar 1 dari 1 Halaman</td>
            </tr>
        </table>
    </div>
</body>
</html>
