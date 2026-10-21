<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * View Cetak / Simpan PDF Lembar Catatan Pengujian (LCP)
 * WAKTU HANCUR (Bidang Kimia)
 * Replikasi presisi 100% dari format Excel resmi BPOM:
 * POM-06.02/CFM.01/SOP.01/F.01A.04
 */

$status_sesi = $sesi['status'] ?? 'Draft';
$p = !empty($hasil['data_hasil']) ? json_decode($hasil['data_hasil'], true) : array();
if (isset($p['parameter_values']) && is_array($p['parameter_values'])) {
    $p = array_merge($p, $p['parameter_values']);
}

// Data tabel 6 sampel
$tabel_hancur = isset($p['tabel_hasil_hancur']) && is_array($p['tabel_hasil_hancur']) ? $p['tabel_hasil_hancur'] : array();

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

$tgl_lap = date('d F Y', strtotime($hasil['updated_at'] ?? 'now'));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak LCP - WAKTU HANCUR - <?php echo html_escape($sampel['nama_sampel'] ?? 'Sampel'); ?></title>
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
            padding: 15px 0;
            background-color: #525659;
            font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
            font-size: 8pt;
            color: #000;
            line-height: 1.2;
        }

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
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            font-family: inherit;
            font-size: 12px;
            font-weight: 600;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-print { background: #0284c7; color: #fff; }
        .btn-print:hover { background: #0369a1; }

        .btn-back { background: #e2e8f0; color: #334155; }
        .btn-back:hover { background: #cbd5e1; }

        .document-wrapper {
            background-color: #fff;
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            padding: 7mm 10mm 7mm 10mm;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        table.lcp-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        table.lcp-table td, table.lcp-table th {
            padding: 3px 5px;
            vertical-align: middle;
            font-size: 8pt;
            word-wrap: break-word;
        }

        .border-all { border: 1px solid #000; }
        .border-b { border-bottom: 1px solid #000; }
        .border-t { border-top: 1px solid #000; }
        .border-l { border-left: 1px solid #000; }
        .border-r { border-right: 1px solid #000; }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .fw-bold { font-weight: bold; }

        .bg-light-gray { background-color: #f8fafc; }

        @media print {
            body { background-color: #fff !important; padding: 0 !important; }
            .no-print { display: none !important; }
            .document-wrapper {
                margin: 0 !important;
                padding: 6mm 10mm !important;
                box-shadow: none !important;
                width: 100% !important;
                height: 100% !important;
                max-height: 297mm !important;
                page-break-after: avoid !important;
            }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button onclick="window.print()" class="btn-action btn-print">Cetak / Simpan PDF</button>
        <button onclick="window.close()" class="btn-action btn-back">Tutup</button>
    </div>

    <div class="document-wrapper">
        <div>
            <!-- Header Logo & Judul Persis Excel -->
            <table class="lcp-table" style="margin-bottom: 8px;">
                <colgroup>
                    <col style="width: 16%;">
                    <col style="width: 84%;">
                </colgroup>
                <tr>
                    <td class="text-center" style="vertical-align: middle;">
                        <img src="<?php echo base_url('assets/img/bpom_logo_excel.png'); ?>" style="height: 52px; width: auto; display: block; margin: 0 auto;" alt="Logo BPOM">
                    </td>
                    <td class="text-center" style="vertical-align: middle; padding-right: 35px;">
                        <div style="font-size: 11pt; font-weight: bold; letter-spacing: 0.5px;">LAMPIRAN CATATAN PENGUJIAN</div>
                        <div style="font-size: 10.5pt; font-weight: bold; margin-top: 2px;">WAKTU HANCUR</div>
                        <div style="font-size: 8.5pt; font-weight: bold; margin-top: 4px;">
                            Laboratorium Kimia Balai Besar POM di Bandar Lampung
                        </div>
                    </td>
                </tr>
            </table>

            <!-- Header Info Sampel, Alat & Media Uji Persis Excel -->
            <table class="lcp-table border-all" style="margin-bottom: 10px;">
                <colgroup>
                    <col style="width: 18%;">
                    <col style="width: 2%;">
                    <col style="width: 33%;">
                    <col style="width: 18%;">
                    <col style="width: 2%;">
                    <col style="width: 27%;">
                </colgroup>
                <tr>
                    <td class="border-t border-l" style="padding-left: 6px;">Nama Sampel</td>
                    <td class="border-t">:</td>
                    <td class="border-t border-r fw-bold"><?php echo html_escape($sampel['nama_sampel'] ?? '-'); ?></td>
                    <td class="border-t border-l" style="padding-left: 6px;">No Kode Sampel</td>
                    <td class="border-t">:</td>
                    <td class="border-t border-r"><?php echo html_escape(!empty($sampel['kode_sampel_manual']) ? $sampel['kode_sampel_manual'] : 'SMP-' . ($sampel['id'] ?? '')); ?></td>
                </tr>
                <tr>
                    <td class="border-l" style="padding-left: 6px;"></td>
                    <td></td>
                    <td class="border-r"></td>
                    <td class="border-l" style="padding-left: 6px;">No. Bets</td>
                    <td>:</td>
                    <td class="border-r"><?php echo html_escape($sampel['no_bets'] ?? '-'); ?></td>
                </tr>
                <tr>
                    <td class="border-l" style="padding-left: 6px;">Bentuk Sediaan</td>
                    <td>:</td>
                    <td class="border-r fw-bold"><?php echo html_escape($p['bentuk_sediaan'] ?? ($sampel['kemasan'] ?? 'Tablet')); ?></td>
                    <td class="border-l" style="padding-left: 6px;">Tanggal Uji</td>
                    <td>:</td>
                    <td class="border-r"><?php echo html_escape($p['tgl_uji'] ?? '-'); ?></td>
                </tr>
                <tr>
                    <td class="border-l" style="padding-left: 6px;">Merk Alat</td>
                    <td>:</td>
                    <td class="border-r"><?php echo html_escape($p['merk_alat'] ?? '-'); ?></td>
                    <td class="border-l" style="padding-left: 6px;">Tipe / Seri</td>
                    <td>:</td>
                    <td class="border-r"><?php echo html_escape($p['tipe_seri_alat'] ?? '-'); ?></td>
                </tr>
                <tr>
                    <td class="border-l" style="padding-left: 6px;">Tanggal Kalibrasi</td>
                    <td>:</td>
                    <td class="border-r" colspan="4">
                        <?php echo html_escape($p['tgl_kalibrasi_mulai'] ?? '-'); ?> 
                        &nbsp;&nbsp;s.d&nbsp;&nbsp; 
                        <?php echo html_escape($p['tgl_kalibrasi_selesai'] ?? '-'); ?>
                    </td>
                </tr>
                <tr>
                    <td class="border-l border-b" style="padding-left: 6px;">Media Uji</td>
                    <td class="border-b">:</td>
                    <td class="border-r border-b"><?php echo html_escape($p['media_uji'] ?? 'Air suling'); ?></td>
                    <td class="border-l border-b" style="padding-left: 6px;">Suhu</td>
                    <td class="border-b">:</td>
                    <td class="border-r border-b">
                        <?php echo html_escape($p['suhu_pengujian'] ?? '37'); ?> °C 
                        <span style="font-size: 7pt; color: #555;">(Syarat: 37 ± 2 °C)</span>
                    </td>
                </tr>
            </table>

            <!-- Bagian Hasil Uji Persis Excel -->
            <div style="font-weight: bold; font-size: 9pt; margin-bottom: 5px;">Hasil Uji :</div>

            <table class="lcp-table border-all" style="margin-bottom: 12px;">
                <colgroup>
                    <col style="width: 15%;">
                    <col style="width: 25%;">
                    <col style="width: 60%;">
                </colgroup>
                <thead>
                    <tr class="bg-light-gray" style="height: 24px;">
                        <th class="border-all text-center fw-bold">Sampel</th>
                        <th class="border-all text-center fw-bold">Waktu Hancur</th>
                        <th class="border-all text-center fw-bold">Pengamatan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php for ($s = 1; $s <= 6; $s++): 
                        $row_s = $tabel_hancur[$s - 1] ?? array();
                    ?>
                        <tr style="height: 22px;">
                            <td class="border-all text-center fw-bold"><?php echo $s; ?></td>
                            <td class="border-all text-center"><?php echo html_escape($row_s['waktu_hancur'] ?? ''); ?></td>
                            <td class="border-all" style="padding-left: 8px;"><?php echo html_escape($row_s['pengamatan'] ?? 'Hancur sempurna, tidak ada residu pada kasa'); ?></td>
                        </tr>
                    <?php endfor; ?>
                    <?php if (!empty($p['waktu_maksimum'])): ?>
                        <tr class="bg-light-gray" style="height: 22px;">
                            <td colspan="2" class="border-all fw-bold text-right" style="padding-right: 8px;">Waktu Terlama / Rata-rata :</td>
                            <td class="border-all fw-bold" style="padding-left: 8px; color: #0284c7;"><?php echo html_escape($p['waktu_maksimum']); ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- Pustaka & Syarat Persis Excel -->
            <table class="lcp-table" style="margin-bottom: 8px;">
                <colgroup>
                    <col style="width: 20%;">
                    <col style="width: 2%;">
                    <col style="width: 78%;">
                </colgroup>
                <tr>
                    <td class="fw-bold">Pustaka Metode</td>
                    <td>:</td>
                    <td><?php echo html_escape(!empty($p['pustaka_metode']) ? $p['pustaka_metode'] : ($metode['pustaka_acuan'] ?? 'Farmakope Indonesia Edisi VI <1251>')); ?></td>
                </tr>
                <tr>
                    <td class="fw-bold">Pustaka Persyaratan</td>
                    <td>:</td>
                    <td><?php echo html_escape(!empty($p['pustaka_persyaratan']) ? $p['pustaka_persyaratan'] : 'Farmakope Indonesia Edisi VI'); ?></td>
                </tr>
                <tr>
                    <td class="fw-bold" style="vertical-align: top;">Syarat</td>
                    <td style="vertical-align: top;">:</td>
                    <td><?php echo html_escape($p['syarat'] ?? 'Semua sediaan hancur sempurna dalam batas waktu yang ditentukan'); ?></td>
                </tr>
                <tr>
                    <td class="fw-bold">Kesimpulan</td>
                    <td>:</td>
                    <td>
                        <span class="fw-bold" style="font-size: 9pt; color: <?php echo (($p['kesimpulan'] ?? '') === 'Tidak Memenuhi Syarat (TMS)') ? '#dc2626' : '#15803d'; ?>;">
                            <?php echo html_escape($p['kesimpulan'] ?? 'Memenuhi Syarat (MS)'); ?>
                        </span>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Bagian Pengesahan & Dokumen Resmi Persis Excel -->
        <div>
            <table class="lcp-table border-all" style="margin-bottom: 6px;">
                <colgroup>
                    <col style="width: 50%;">
                    <col style="width: 50%;">
                </colgroup>
                <thead>
                    <tr class="bg-light-gray">
                        <th class="border-all text-center fw-bold" style="padding: 4px;">Penguji</th>
                        <th class="border-all text-center fw-bold" style="padding: 4px;">Diperiksa Penyelia / Verifikator:</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="height: 58px;">
                        <td class="border-all text-center" style="vertical-align: middle;">
                            <?php if (!empty($ttd_penguji_img)): ?>
                                <img src="<?php echo $ttd_penguji_img; ?>" style="max-height: 50px; max-width: 85%;" alt="TTD Penguji">
                            <?php else: ?>
                                <span style="color: #94a3b8; font-style: italic; font-size: 7.5pt;">[Nama dan Paraf]</span>
                            <?php endif; ?>
                        </td>
                        <td class="border-all text-center" style="vertical-align: middle;">
                            <?php if (!empty($ttd_penyelia_img)): ?>
                                <img src="<?php echo $ttd_penyelia_img; ?>" style="max-height: 50px; max-width: 85%;" alt="TTD Penyelia">
                            <?php else: ?>
                                <span style="color: #94a3b8; font-style: italic; font-size: 7.5pt;">
                                    <?php echo in_array($status_sesi, array('Verified', 'Approved', 'Selesai')) ? '[Telah Diperiksa]' : '[Nama dan Paraf]'; ?>
                                </span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="border-all" style="padding-left: 8px; font-size: 7.5pt;">
                            <div>Nama: <strong><?php echo html_escape($penguji['nama_lengkap'] ?? ($penguji['nama_user'] ?? '-')); ?></strong></div>
                            <div>Tanggal Laporan: <?php echo html_escape($tgl_lap); ?></div>
                        </td>
                        <td class="border-all" style="padding-left: 8px; font-size: 7.5pt;">
                            <div>Nama: <strong><?php echo html_escape($verifier['nama_lengkap'] ?? ($verifier['nama_user'] ?? '-')); ?></strong></div>
                            <div>Tanggal: <?php echo html_escape($tgl_lap); ?></div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Footer Dokumen Resmi BPOM Excel -->
            <table class="lcp-table" style="font-size: 7pt; color: #475569;">
                <tr>
                    <td style="width: 50%;"></td>
                    <td style="width: 50%; text-align: right;">Lembar 1 dari 1 Halaman</td>
                </tr>
                <tr>
                    <td colspan="2" style="font-weight: bold; font-family: monospace; font-size: 7.5pt; color: #000; padding-top: 2px;">
                        POM-06.02/CFM.01/SOP.01/F.01A.04
                    </td>
                </tr>
            </table>
        </div>
    </div>

</body>
</html>
