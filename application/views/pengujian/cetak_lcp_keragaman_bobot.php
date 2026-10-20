<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * View Cetak / Simpan PDF Lembar Catatan Pengujian (LCP)
 * KERAGAMAN BOBOT (Bidang Kimia)
 * Replikasi presisi 100% dari format Excel resmi Balai Besar POM di Bandar Lampung.
 */

$status_sesi = $sesi['status'] ?? 'Draft';
$p = !empty($hasil['data_hasil']) ? json_decode($hasil['data_hasil'], true) : array();
if (isset($p['parameter_values']) && is_array($p['parameter_values'])) {
    $p = array_merge($p, $p['parameter_values']);
}

// Data tabel tahap 1 & 2
$tabel_t1 = isset($p['tabel_tahap1']) && is_array($p['tabel_tahap1']) ? $p['tabel_tahap1'] : array();
$tabel_t2 = isset($p['tabel_tahap2']) && is_array($p['tabel_tahap2']) ? $p['tabel_tahap2'] : array();

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
$ttd_approver_img = $resolve_ttd($signature_approver ?? null);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak LCP - KERAGAMAN BOBOT - <?php echo html_escape($sampel['nama_sampel'] ?? 'Sampel'); ?></title>
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
            font-size: 7.5pt;
            color: #000;
            line-height: 1.15;
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

        .btn-print {
            background: #0284c7;
            color: #fff;
        }
        .btn-print:hover { background: #0369a1; }

        .btn-back {
            background: #e2e8f0;
            color: #334155;
        }
        .btn-back:hover { background: #cbd5e1; }

        .document-wrapper {
            background-color: #fff;
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            padding: 6mm 8mm 6mm 8mm;
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
            padding: 1.5px 3px;
            vertical-align: middle;
            font-size: 7.5pt;
            word-wrap: break-word;
        }

        .border-all {
            border: 1px solid #000;
        }

        .border-b { border-bottom: 1px solid #000; }
        .border-t { border-top: 1px solid #000; }
        .border-l { border-left: 1px solid #000; }
        .border-r { border-right: 1px solid #000; }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .fw-bold { font-weight: bold; }

        .bg-light-gray { background-color: #f3f4f6; }

        @media print {
            body {
                background-color: #fff !important;
                padding: 0 !important;
            }
            .no-print { display: none !important; }
            .document-wrapper {
                margin: 0 !important;
                padding: 5mm 8mm !important;
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
        <button onclick="window.print()" class="btn-action btn-print">
            Cetak / Simpan PDF
        </button>
        <button onclick="window.close()" class="btn-action btn-back">
            Tutup
        </button>
    </div>

    <div class="document-wrapper">
        <div>
            <!-- Header Logo & Judul -->
            <table class="lcp-table" style="margin-bottom: 8px;">
                <colgroup>
                    <col style="width: 16%;">
                    <col style="width: 84%;">
                </colgroup>
                <tr>
                    <td class="text-center" style="vertical-align: middle;">
                        <img src="<?php echo base_url('assets/img/bpom_logo_excel.png'); ?>" style="height: 48px; width: auto; display: block; margin: 0 auto;" alt="Logo BPOM">
                    </td>
                    <td class="text-center" style="vertical-align: middle; padding-right: 35px;">
                        <div style="font-size: 11pt; font-weight: bold; letter-spacing: 0.5px;">LAMPIRAN CATATAN PENGUJIAN</div>
                        <div style="font-size: 10.5pt; font-weight: bold; margin-top: 1px;">KERAGAMAN BOBOT</div>
                        <div style="font-size: 8pt; font-weight: bold; margin-top: 4px;">
                            Laboratorium Kimia Balai Besar POM di Bandar Lampung
                        </div>
                    </td>
                </tr>
            </table>

            <!-- Header Info Sampel & Instrumen -->
            <table class="lcp-table border-all" style="margin-bottom: 6px;">
                <colgroup>
                    <col style="width: 17%;">
                    <col style="width: 2%;">
                    <col style="width: 36%;">
                    <col style="width: 18%;">
                    <col style="width: 2%;">
                    <col style="width: 25%;">
                </colgroup>
                <tr>
                    <td class="border-t border-l" style="padding-left: 5px;">Nama Sampel</td>
                    <td class="border-t">:</td>
                    <td class="border-t border-r fw-bold"><?php echo html_escape($sampel['nama_sampel'] ?? '-'); ?></td>
                    <td class="border-t border-l" style="padding-left: 5px;">No. Kode Sampel</td>
                    <td class="border-t">:</td>
                    <td class="border-t border-r"><?php echo html_escape(!empty($sampel['kode_sampel_manual']) ? $sampel['kode_sampel_manual'] : 'SMP-' . ($sampel['id'] ?? '')); ?></td>
                </tr>
                <tr>
                    <td class="border-l" style="padding-left: 5px;">Zat yang diuji</td>
                    <td>:</td>
                    <td class="border-r fw-bold"><?php echo html_escape(!empty($p['zat_uji']) ? $p['zat_uji'] : ($sampel['komposisi'] ?? '-')); ?></td>
                    <td class="border-l" style="padding-left: 5px;">No. Bets</td>
                    <td>:</td>
                    <td class="border-r"><?php echo html_escape($sampel['no_bets'] ?? '-'); ?></td>
                </tr>
                <tr>
                    <td class="border-l" style="padding-left: 5px;">Merk/Tipe Timbangan</td>
                    <td>:</td>
                    <td class="border-r"><?php echo html_escape($p['merk_timbangan'] ?? '-'); ?></td>
                    <td class="border-l" style="padding-left: 5px;">Merk/Tipe Timbangan</td>
                    <td>:</td>
                    <td class="border-r"><?php echo html_escape($p['merk_timbangan_2'] ?? '-'); ?></td>
                </tr>
                <tr>
                    <td class="border-l border-b" style="padding-left: 5px;">Tanggal Kalibrasi</td>
                    <td class="border-b">:</td>
                    <td class="border-r border-b"><?php echo html_escape($p['tgl_kalibrasi'] ?? '-'); ?></td>
                    <td class="border-l border-b" style="padding-left: 5px;">Tanggal Kalibrasi</td>
                    <td class="border-b">:</td>
                    <td class="border-r border-b"><?php echo html_escape($p['tgl_kalibrasi_2'] ?? '-'); ?></td>
                </tr>
                <tr>
                    <td class="border-l" style="padding-left: 5px;">Bobot rata-rata</td>
                    <td>:</td>
                    <td class="border-r"><?php echo html_escape(!empty($p['bobot_rata_rata']) ? $p['bobot_rata_rata'] . ' mg' : '-'); ?></td>
                    <td colspan="3" class="border-r"></td>
                </tr>
                <tr>
                    <td class="border-l" style="padding-left: 5px;">Kadar dari hasil uji*</td>
                    <td>:</td>
                    <td class="border-r"><?php echo html_escape(!empty($p['kadar_hasil_uji']) ? $p['kadar_hasil_uji'] . ' % / mg' : '-'); ?></td>
                    <td colspan="3" class="border-r"></td>
                </tr>
                <tr>
                    <td class="border-l border-b" style="padding-left: 5px;">Etiket*</td>
                    <td class="border-b">:</td>
                    <td class="border-r border-b"><?php echo html_escape(!empty($p['etiket']) ? $p['etiket'] . ' mg/sediaan' : '-'); ?></td>
                    <td colspan="3" class="border-r border-b"></td>
                </tr>
            </table>

            <!-- Tabel Pengukuran Tahap 1 & Tahap 2 Bersebelahan (Presisi Excel) -->
            <table class="lcp-table" style="margin-bottom: 6px;">
                <colgroup>
                    <col style="width: 48.5%;">
                    <col style="width: 3%;">
                    <col style="width: 48.5%;">
                </colgroup>
                <tr>
                    <!-- Kolom Kiri: Tahap 1 (n=10) -->
                    <td style="vertical-align: top; padding: 0;">
                        <table class="lcp-table border-all">
                            <colgroup>
                                <col style="width: 8%;">
                                <col style="width: 22%;">
                                <col style="width: 22%;">
                                <col style="width: 22%;">
                                <col style="width: 13%;">
                                <col style="width: 13%;">
                            </colgroup>
                            <thead>
                                <tr class="bg-light-gray">
                                    <th class="border-all text-center fw-bold" colspan="4">Tahap 1</th>
                                    <th class="border-all text-center fw-bold" colspan="2">Tanggal Uji: <?php echo html_escape($p['tgl_uji_tahap1'] ?? ''); ?></th>
                                </tr>
                                <tr class="bg-light-gray">
                                    <th rowspan="2" class="border-all text-center">No.</th>
                                    <th colspan="3" class="border-all text-center">Bobot (mg)</th>
                                    <th colspan="2" class="border-all text-center">Kadar</th>
                                </tr>
                                <tr class="bg-light-gray">
                                    <th class="border-all text-center">Bruto</th>
                                    <th class="border-all text-center">Cangkang</th>
                                    <th class="border-all text-center">NETTO</th>
                                    <th class="border-all text-center">mg/sedia</th>
                                    <th class="border-all text-center">%</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php for ($i = 0; $i < 10; $i++): 
                                    $row = $tabel_t1[$i] ?? array();
                                ?>
                                    <tr style="height: 18px;">
                                        <td class="border-all text-center"><?php echo ($i + 1); ?></td>
                                        <td class="border-all text-right" style="padding-right: 4px;"><?php echo html_escape($row['bruto'] ?? ''); ?></td>
                                        <td class="border-all text-right" style="padding-right: 4px;"><?php echo html_escape($row['cangkang'] ?? ''); ?></td>
                                        <td class="border-all text-right fw-bold" style="padding-right: 4px;"><?php echo html_escape($row['netto'] ?? ''); ?></td>
                                        <td class="border-all text-right" style="padding-right: 4px;"><?php echo html_escape($row['kadar_mg'] ?? ''); ?></td>
                                        <td class="border-all text-right" style="padding-right: 4px;"><?php echo html_escape($row['kadar_persen'] ?? ''); ?></td>
                                    </tr>
                                <?php endfor; ?>
                                <tr>
                                    <td colspan="4" class="border-all fw-bold text-right" style="padding-right: 6px;">X<sub>rerata</sub></td>
                                    <td colspan="2" class="border-all text-center fw-bold"><?php echo html_escape($p['x_rerata_tahap1'] ?? '-'); ?></td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="border-all fw-bold text-right" style="padding-right: 6px;">SD (s)</td>
                                    <td colspan="2" class="border-all text-center fw-bold"><?php echo html_escape($p['sd_tahap1'] ?? '-'); ?></td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="border-all fw-bold text-right" style="padding-right: 6px;">RSD</td>
                                    <td colspan="2" class="border-all text-center fw-bold"><?php echo html_escape(!empty($p['rsd_tahap1']) ? $p['rsd_tahap1'] . ' %' : '-'); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>

                    <!-- Spacer -->
                    <td></td>

                    <!-- Kolom Kanan: Tahap 2 (No 11-30) -->
                    <td style="vertical-align: top; padding: 0;">
                        <table class="lcp-table border-all">
                            <colgroup>
                                <col style="width: 8%;">
                                <col style="width: 22%;">
                                <col style="width: 22%;">
                                <col style="width: 22%;">
                                <col style="width: 13%;">
                                <col style="width: 13%;">
                            </colgroup>
                            <thead>
                                <tr class="bg-light-gray">
                                    <th class="border-all text-center fw-bold" colspan="4">Tahap 2</th>
                                    <th class="border-all text-center fw-bold" colspan="2">Tanggal Uji: <?php echo html_escape($p['tgl_uji_tahap2'] ?? ''); ?></th>
                                </tr>
                                <tr class="bg-light-gray">
                                    <th rowspan="2" class="border-all text-center">No.</th>
                                    <th colspan="3" class="border-all text-center">Bobot (mg)</th>
                                    <th colspan="2" class="border-all text-center">Kadar</th>
                                </tr>
                                <tr class="bg-light-gray">
                                    <th class="border-all text-center">Bruto</th>
                                    <th class="border-all text-center">Cangkang</th>
                                    <th class="border-all text-center">NETTO</th>
                                    <th class="border-all text-center">mg/sedia</th>
                                    <th class="border-all text-center">%</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php for ($j = 0; $j < 20; $j++): 
                                    $row2 = $tabel_t2[$j] ?? array();
                                ?>
                                    <tr style="height: 14.5px;">
                                        <td class="border-all text-center"><?php echo ($j + 11); ?></td>
                                        <td class="border-all text-right" style="padding-right: 4px;"><?php echo html_escape($row2['bruto'] ?? ''); ?></td>
                                        <td class="border-all text-right" style="padding-right: 4px;"><?php echo html_escape($row2['cangkang'] ?? ''); ?></td>
                                        <td class="border-all text-right fw-bold" style="padding-right: 4px;"><?php echo html_escape($row2['netto'] ?? ''); ?></td>
                                        <td class="border-all text-right" style="padding-right: 4px;"><?php echo html_escape($row2['kadar_mg'] ?? ''); ?></td>
                                        <td class="border-all text-right" style="padding-right: 4px;"><?php echo html_escape($row2['kadar_persen'] ?? ''); ?></td>
                                    </tr>
                                <?php endfor; ?>
                                <tr>
                                    <td colspan="4" class="border-all fw-bold text-right" style="padding-right: 6px;">X<sub>rerata</sub> 30 data</td>
                                    <td colspan="2" class="border-all text-center fw-bold"><?php echo html_escape($p['x_rerata_30'] ?? '-'); ?></td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="border-all fw-bold text-right" style="padding-right: 6px;">SD (s) 30 data</td>
                                    <td colspan="2" class="border-all text-center fw-bold"><?php echo html_escape($p['sd_30'] ?? '-'); ?></td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="border-all fw-bold text-right" style="padding-right: 6px;">RSD 30 data</td>
                                    <td colspan="2" class="border-all text-center fw-bold"><?php echo html_escape(!empty($p['rsd_30']) ? $p['rsd_30'] . ' %' : '-'); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>

            <!-- Tabel Evaluasi Penerimaan Nilai AV (Acceptance Value) -->
            <table class="lcp-table border-all" style="margin-bottom: 6px;">
                <colgroup>
                    <col style="width: 25%;">
                    <col style="width: 25%;">
                    <col style="width: 25%;">
                    <col style="width: 25%;">
                </colgroup>
                <thead>
                    <tr class="bg-light-gray">
                        <th class="border-all text-center fw-bold">KONDISI</th>
                        <th colspan="3" class="border-all text-center fw-bold">NILAI</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border-all text-center">n = <?php echo (!empty($p['lakukan_tahap2']) && $p['lakukan_tahap2'] === 'Ya') ? '30' : '10'; ?></td>
                        <td class="border-all text-center">k = <?php echo html_escape($p['nilai_k'] ?? '2.4'); ?></td>
                        <td colspan="2" class="border-all text-center"><?php echo html_escape($p['nilai_k'] ?? '2.4'); ?></td>
                    </tr>
                    <tr>
                        <td class="border-all text-center">T (Target)</td>
                        <td class="border-all text-center">T (%) = 100</td>
                        <td colspan="2" class="border-all text-center">100%</td>
                    </tr>
                    <tr>
                        <td class="border-all">98.5% ≤ x rerata ≤ 101.5%</td>
                        <td class="border-all">M = x rerata <br>(AV = ks)</td>
                        <td colspan="2" class="border-all text-center fw-bold" style="font-size: 8.5pt;">
                            M = <?php echo html_escape($p['nilai_m'] ?? '-'); ?><br>
                            AV = <?php echo html_escape($p['nilai_av'] ?? '-'); ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="border-all">x rerata &lt; 98.5%</td>
                        <td class="border-all">M = 98.5% <br>(AV = 98.5 - x rerata + ks)</td>
                        <td colspan="2" class="border-all text-center text-muted">-</td>
                    </tr>
                    <tr>
                        <td class="border-all">x rerata &gt; 101.5%</td>
                        <td class="border-all">M = 101.5% <br>(AV = x rerata - 101.5 + ks)</td>
                        <td colspan="2" class="border-all text-center text-muted">-</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="border-all fw-bold text-center">L1 (Batas Maksimum)</td>
                        <td colspan="2" class="border-all text-center fw-bold" style="color: #0369a1;">15.0</td>
                    </tr>
                </tbody>
            </table>

            <!-- Syarat & Kesimpulan -->
            <div style="font-size: 7.5pt; margin-bottom: 6px;">
                <div><strong>Syarat:</strong> <?php echo html_escape($p['syarat_av'] ?? 'AV dari 10 unit ≤ 15,0 %'); ?></div>
                <div style="margin-top: 2px;"><strong>Pustaka:</strong> <?php echo html_escape(!empty($p['pustaka']) ? $p['pustaka'] : ($metode['pustaka_acuan'] ?? 'Farmakope Indonesia Edisi VI')); ?></div>
                <div style="margin-top: 3px; font-size: 8pt;">
                    <strong>Kesimpulan:</strong> 
                    <span class="fw-bold" style="color: <?php echo (($p['kesimpulan'] ?? '') === 'Tidak Memenuhi Syarat (TMS)') ? '#dc2626' : '#15803d'; ?>;">
                        <?php echo html_escape($p['kesimpulan'] ?? 'Memenuhi Syarat (MS)'); ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Bagian Pengesahan / Tanda Tangan Digital Resmi 3 Pihak -->
        <div style="margin-top: 10px;">
            <table class="lcp-table border-all">
                <colgroup>
                    <col style="width: 33.33%;">
                    <col style="width: 33.33%;">
                    <col style="width: 33.34%;">
                </colgroup>
                <thead>
                    <tr class="bg-light-gray">
                        <th class="border-all text-center fw-bold" style="padding: 3px;">Penguji</th>
                        <th class="border-all text-center fw-bold" style="padding: 3px;">Penyelia (Verifier)</th>
                        <th class="border-all text-center fw-bold" style="padding: 3px;">Manajer Teknis (Approver)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="height: 52px;">
                        <td class="border-all text-center" style="vertical-align: middle;">
                            <?php if (!empty($ttd_penguji_img)): ?>
                                <img src="<?php echo $ttd_penguji_img; ?>" style="max-height: 44px; max-width: 90%;" alt="TTD Penguji">
                            <?php else: ?>
                                <span style="color: #94a3b8; font-style: italic; font-size: 7pt;">[Telah Diverifikasi Sistem]</span>
                            <?php endif; ?>
                        </td>
                        <td class="border-all text-center" style="vertical-align: middle;">
                            <?php if (!empty($ttd_penyelia_img)): ?>
                                <img src="<?php echo $ttd_penyelia_img; ?>" style="max-height: 44px; max-width: 90%;" alt="TTD Penyelia">
                            <?php else: ?>
                                <span style="color: #94a3b8; font-style: italic; font-size: 7pt;">
                                    <?php echo in_array($status_sesi, array('Verified', 'Approved', 'Selesai')) ? '[Telah Diverifikasi]' : '[Menunggu Verifikasi]'; ?>
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="border-all text-center" style="vertical-align: middle;">
                            <?php if (!empty($ttd_approver_img)): ?>
                                <img src="<?php echo $ttd_approver_img; ?>" style="max-height: 44px; max-width: 90%;" alt="TTD Approver">
                            <?php else: ?>
                                <span style="color: #94a3b8; font-style: italic; font-size: 7pt;">
                                    <?php echo in_array($status_sesi, array('Approved', 'Selesai')) ? '[Telah Disetujui]' : '[Menunggu Approval]'; ?>
                                </span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="border-all text-center fw-bold" style="font-size: 7pt;">
                            <?php echo html_escape($penguji['nama_lengkap'] ?? ($penguji['nama_user'] ?? '-')); ?><br>
                            <span style="font-weight: normal; color: #475569;">NIP. <?php echo html_escape($penguji['nip'] ?? '-'); ?></span>
                        </td>
                        <td class="border-all text-center fw-bold" style="font-size: 7pt;">
                            <?php echo html_escape($verifier['nama_lengkap'] ?? ($verifier['nama_user'] ?? '-')); ?><br>
                            <span style="font-weight: normal; color: #475569;">NIP. <?php echo html_escape($verifier['nip'] ?? '-'); ?></span>
                        </td>
                        <td class="border-all text-center fw-bold" style="font-size: 7pt;">
                            <?php echo html_escape($approver['nama_lengkap'] ?? ($approver['nama_user'] ?? '-')); ?><br>
                            <span style="font-weight: normal; color: #475569;">NIP. <?php echo html_escape($approver['nip'] ?? '-'); ?></span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
