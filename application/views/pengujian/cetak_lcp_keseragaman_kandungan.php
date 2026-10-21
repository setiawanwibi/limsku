<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * View Cetak / Simpan PDF Lembar Catatan Pengujian (LCP)
 * KESERAGAMAN KANDUNGAN (Bidang Kimia)
 * Replikasi presisi 100% dari format Excel resmi BPOM:
 * POM-06.02/CFM.01/SOP.01/F.01A.03
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

$is_tahap2 = (!empty($p['lakukan_tahap2']) && $p['lakukan_tahap2'] === 'Ya');
$satuan = $p['satuan_sediaan'] ?? 'mg/tab';
$tgl_lap = date('d F Y', strtotime($hasil['updated_at'] ?? 'now'));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak LCP - KESERAGAMAN KANDUNGAN - <?php echo html_escape($sampel['nama_sampel'] ?? 'Sampel'); ?></title>
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
            padding: 10px 0;
            background-color: #525659;
            font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
            font-size: 6.8pt;
            color: #000;
            line-height: 1.12;
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
            padding: 4mm 7mm 4mm 7mm;
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
            padding: 1px 2.5px;
            vertical-align: middle;
            font-size: 6.8pt;
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
                padding: 4mm 7mm !important;
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
            <table class="lcp-table" style="margin-bottom: 4px;">
                <colgroup>
                    <col style="width: 14%;">
                    <col style="width: 86%;">
                </colgroup>
                <tr>
                    <td class="text-center" style="vertical-align: middle;">
                        <img src="<?php echo base_url('assets/img/bpom_logo_excel.png'); ?>" style="height: 44px; width: auto; display: block; margin: 0 auto;" alt="Logo BPOM">
                    </td>
                    <td class="text-center" style="vertical-align: middle; padding-right: 25px;">
                        <div style="font-size: 10.5pt; font-weight: bold; letter-spacing: 0.5px;">LAMPIRAN CATATAN PENGUJIAN</div>
                        <div style="font-size: 10pt; font-weight: bold; margin-top: 1px;">KESERAGAMAN KANDUNGAN</div>
                        <div style="font-size: 7.5pt; font-weight: bold; margin-top: 2px;">
                            Laboratorium Kimia Balai Besar POM di Bandar Lampung
                        </div>
                    </td>
                </tr>
            </table>

            <!-- Header Info Sampel, Instrumen & Baku Pembanding Persis Excel -->
            <table class="lcp-table border-all" style="margin-bottom: 4px;">
                <colgroup>
                    <col style="width: 18%;">
                    <col style="width: 2%;">
                    <col style="width: 30%;">
                    <col style="width: 23%;">
                    <col style="width: 2%;">
                    <col style="width: 25%;">
                </colgroup>
                <tr>
                    <td class="border-t border-l" style="padding-left: 4px;">Nama Sampel</td>
                    <td class="border-t">:</td>
                    <td class="border-t border-r fw-bold"><?php echo html_escape($sampel['nama_sampel'] ?? '-'); ?></td>
                    <td class="border-t border-l" style="padding-left: 4px;">No. Kode Sampel</td>
                    <td class="border-t">:</td>
                    <td class="border-t border-r"><?php echo html_escape(!empty($sampel['kode_sampel_manual']) ? $sampel['kode_sampel_manual'] : 'SMP-' . ($sampel['id'] ?? '')); ?></td>
                </tr>
                <tr>
                    <td class="border-l" style="padding-left: 4px;">Zat yang diuji</td>
                    <td>:</td>
                    <td class="border-r fw-bold"><?php echo html_escape(!empty($p['zat_uji']) ? $p['zat_uji'] : ($sampel['komposisi'] ?? '-')); ?></td>
                    <td class="border-l" style="padding-left: 4px;">No. Bets</td>
                    <td>:</td>
                    <td class="border-r"><?php echo html_escape($sampel['no_bets'] ?? '-'); ?></td>
                </tr>
                <tr>
                    <td class="border-l" style="padding-left: 4px;">Instrumen</td>
                    <td>:</td>
                    <td class="border-r fw-bold"><?php echo html_escape($p['pilihan_instrumen'] ?? 'HPLC'); ?></td>
                    <td class="border-l" style="padding-left: 4px;">Tanggal Uji</td>
                    <td>:</td>
                    <td class="border-r"><?php echo html_escape($p['tgl_uji'] ?? '-'); ?></td>
                </tr>
                <tr>
                    <td class="border-l" style="padding-left: 4px;">Merk/Tipe</td>
                    <td>:</td>
                    <td class="border-r"><?php echo html_escape($p['merk_tipe_instrumen'] ?? '-'); ?></td>
                    <td class="border-l" style="padding-left: 4px;">Baku Pembanding</td>
                    <td>:</td>
                    <td class="border-r"><?php echo html_escape($p['baku_pembanding'] ?? '-'); ?></td>
                </tr>
                <tr>
                    <td class="border-l" style="padding-left: 4px;">Kuvet/Tebal Kuvet</td>
                    <td>:</td>
                    <td class="border-r"><?php echo html_escape($p['kuvet_tebal'] ?? '-'); ?></td>
                    <td class="border-l" style="padding-left: 4px;">Kemurnian Baku (Kb)</td>
                    <td>:</td>
                    <td class="border-r"><?php echo html_escape(!empty($p['kemurnian_baku']) ? $p['kemurnian_baku'] . ' %' : '-'); ?></td>
                </tr>
                <tr>
                    <td class="border-l" style="padding-left: 4px;">λ Uji</td>
                    <td>:</td>
                    <td class="border-r"><?php echo html_escape($p['panjang_gelombang'] ?? '-'); ?></td>
                    <td class="border-l" style="padding-left: 4px;">Susut pengeringan/Kadar air*</td>
                    <td>:</td>
                    <td class="border-r"><?php echo html_escape(!empty($p['susut_pengeringan']) ? $p['susut_pengeringan'] . ' %' : '-'); ?></td>
                </tr>
                <tr>
                    <td class="border-l" style="padding-left: 4px;">Sistem KCKT*</td>
                    <td>:</td>
                    <td class="border-r"><?php echo html_escape($p['sistem_kckt'] ?? '-'); ?></td>
                    <td class="border-l" style="padding-left: 4px;">Bobot Baku (Bb)</td>
                    <td>:</td>
                    <td class="border-r"><?php echo html_escape(!empty($p['bobot_baku']) ? $p['bobot_baku'] . ' mg' : '-'); ?></td>
                </tr>
                <tr>
                    <td class="border-l" style="padding-left: 4px;">Fase Gerak</td>
                    <td>:</td>
                    <td class="border-r"><?php echo html_escape($p['fase_gerak'] ?? '-'); ?></td>
                    <td class="border-l" style="padding-left: 4px;">Hasil Pengukuran Baku</td>
                    <td>:</td>
                    <td class="border-r"><?php echo html_escape($p['hasil_ukur_baku'] ?? '-'); ?></td>
                </tr>
                <tr>
                    <td class="border-l" style="padding-left: 4px;">Laju Alir</td>
                    <td>:</td>
                    <td class="border-r"><?php echo html_escape($p['laju_alir'] ?? '-'); ?></td>
                    <td class="border-l" style="padding-left: 4px;">Faktor Pengenceran Baku (Fb)</td>
                    <td>:</td>
                    <td class="border-r"><?php echo html_escape($p['faktor_pengenceran_baku'] ?? '-'); ?></td>
                </tr>
                <tr>
                    <td class="border-l" style="padding-left: 4px;">Kromatogram UKS</td>
                    <td>:</td>
                    <td class="border-r">Terlampir</td>
                    <td class="border-l" style="padding-left: 4px;">Faktor Pengenceran Uji (Fu)</td>
                    <td>:</td>
                    <td class="border-r"><?php echo html_escape($p['faktor_pengenceran_uji'] ?? '-'); ?></td>
                </tr>
                <tr>
                    <td class="border-l" style="padding-left: 4px;">Pelarut / Bracket</td>
                    <td>:</td>
                    <td class="border-r">Terlampir</td>
                    <td class="border-l" style="padding-left: 4px;">Kondisi Analisis*</td>
                    <td>:</td>
                    <td class="border-r"><?php echo html_escape($p['kondisi_analisis'] ?? 'Sesuai prosedur'); ?></td>
                </tr>
                <tr>
                    <td class="border-l border-b" style="padding-left: 4px;">Etiket*</td>
                    <td class="border-b">:</td>
                    <td class="border-r border-b fw-bold"><?php echo html_escape(!empty($p['etiket']) ? $p['etiket'] . ' ' . $satuan : '-'); ?></td>
                    <td class="border-l border-b" style="padding-left: 4px;">Keterangan adjustment</td>
                    <td class="border-b">:</td>
                    <td class="border-r border-b"><?php echo html_escape($p['ket_adjustment'] ?? '-'); ?></td>
                </tr>
            </table>

            <!-- Tabel Pengukuran Tahap 1 & Tahap 2 Bersebelahan Persis Excel -->
            <table class="lcp-table" style="margin-bottom: 4px;">
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
                                <col style="width: 10%;">
                                <col style="width: 36%;">
                                <col style="width: 27%;">
                                <col style="width: 27%;">
                            </colgroup>
                            <thead>
                                <tr class="bg-light-gray">
                                    <th rowspan="2" class="border-all text-center">No</th>
                                    <th rowspan="2" class="border-all text-center">Area / Serapan</th>
                                    <th colspan="2" class="border-all text-center">Kadar</th>
                                </tr>
                                <tr class="bg-light-gray">
                                    <th class="border-all text-center">mg</th>
                                    <th class="border-all text-center">%</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php for ($i = 0; $i < 10; $i++): 
                                    $row = $tabel_t1[$i] ?? array();
                                ?>
                                    <tr style="height: 14px;">
                                        <td class="border-all text-center"><?php echo ($i + 1); ?></td>
                                        <td class="border-all text-right" style="padding-right: 3px;"><?php echo html_escape($row['area_serapan'] ?? ''); ?></td>
                                        <td class="border-all text-right fw-bold" style="padding-right: 3px;"><?php echo html_escape($row['kadar_mg'] ?? ''); ?></td>
                                        <td class="border-all text-right" style="padding-right: 3px;"><?php echo html_escape($row['kadar_persen'] ?? ''); ?></td>
                                    </tr>
                                <?php endfor; ?>
                                <tr>
                                    <td colspan="2" class="border-all fw-bold text-right" style="padding-right: 4px;">Rerata (x rerata)</td>
                                    <td class="border-all text-center fw-bold"><?php echo html_escape($p['rerata_kadar_mg_t1'] ?? '-'); ?></td>
                                    <td class="border-all text-center fw-bold"><?php echo html_escape($p['x_rerata_tahap1'] ?? '-'); ?></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="border-all fw-bold text-right" style="padding-right: 4px;">SD (s)</td>
                                    <td class="border-all text-center fw-bold"><?php echo html_escape($p['sd_tahap1'] ?? '-'); ?></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="border-all fw-bold text-right" style="padding-right: 4px;">RSD</td>
                                    <td class="border-all text-center fw-bold"><?php echo html_escape(!empty($p['rsd_tahap1']) ? $p['rsd_tahap1'] . ' %' : '-'); ?></td>
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
                                <col style="width: 10%;">
                                <col style="width: 36%;">
                                <col style="width: 27%;">
                                <col style="width: 27%;">
                            </colgroup>
                            <thead>
                                <tr class="bg-light-gray">
                                    <th rowspan="2" class="border-all text-center">No</th>
                                    <th rowspan="2" class="border-all text-center">Area / Serapan</th>
                                    <th colspan="2" class="border-all text-center">Kadar</th>
                                </tr>
                                <tr class="bg-light-gray">
                                    <th class="border-all text-center">mg</th>
                                    <th class="border-all text-center">%</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php for ($j = 0; $j < 20; $j++): 
                                    $row2 = $tabel_t2[$j] ?? array();
                                ?>
                                    <tr style="height: 12px;">
                                        <td class="border-all text-center"><?php echo ($j + 11); ?></td>
                                        <td class="border-all text-right" style="padding-right: 3px;"><?php echo html_escape($row2['area_serapan'] ?? ''); ?></td>
                                        <td class="border-all text-right fw-bold" style="padding-right: 3px;"><?php echo html_escape($row2['kadar_mg'] ?? ''); ?></td>
                                        <td class="border-all text-right" style="padding-right: 3px;"><?php echo html_escape($row2['kadar_persen'] ?? ''); ?></td>
                                    </tr>
                                <?php endfor; ?>
                                <tr>
                                    <td colspan="3" class="border-all fw-bold text-right" style="padding-right: 4px;">Rerata (x rerata) 30 data</td>
                                    <td class="border-all text-center fw-bold"><?php echo html_escape($p['x_rerata_30'] ?? '-'); ?></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="border-all fw-bold text-right" style="padding-right: 4px;">SD (s) 30 data</td>
                                    <td class="border-all text-center fw-bold"><?php echo html_escape($p['sd_30'] ?? '-'); ?></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="border-all fw-bold text-right" style="padding-right: 4px;">RSD 30 data</td>
                                    <td class="border-all text-center fw-bold"><?php echo html_escape(!empty($p['rsd_30']) ? $p['rsd_30'] . ' %' : '-'); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>

            <!-- Tabel Evaluasi Matriks AV Persis Formula Excel -->
            <table class="lcp-table border-all" style="margin-bottom: 4px;">
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
                        <td class="border-all text-center">n = <?php echo $is_tahap2 ? '30' : '10'; ?></td>
                        <td class="border-all text-center">k = <?php echo html_escape($p['nilai_k'] ?? ($is_tahap2 ? '2.0' : '2.4')); ?></td>
                        <td colspan="2" class="border-all text-center"><?php echo html_escape($p['nilai_k'] ?? ($is_tahap2 ? '2.0' : '2.4')); ?>%</td>
                    </tr>
                    <tr>
                        <td class="border-all text-center">T (Target)</td>
                        <td class="border-all text-center">T (%) = 100</td>
                        <td colspan="2" class="border-all text-center">100%</td>
                    </tr>
                    <tr>
                        <td class="border-all">98.5% ≤ x rerata ≤ 101.5%</td>
                        <td class="border-all">M = x rerata <br>(AV = ks)</td>
                        <td colspan="2" class="border-all text-center fw-bold">
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
                        <td colspan="2" class="border-all fw-bold text-center">L1</td>
                        <td colspan="2" class="border-all text-center fw-bold" style="color: #0369a1;">15.0</td>
                    </tr>
                </tbody>
            </table>

            <!-- Syarat, Pustaka & Kesimpulan Persis Excel -->
            <div style="font-size: 6.8pt; margin-bottom: 3px;">
                <div><strong>Syarat:</strong> <?php echo html_escape($p['syarat'] ?? 'AV dari 10 unit ≤ 15,0 %'); ?></div>
                <div style="margin-top: 1px;"><strong>Pustaka :</strong> <?php echo html_escape(!empty($p['pustaka']) ? $p['pustaka'] : ($metode['pustaka_acuan'] ?? 'Farmakope Indonesia Edisi VI')); ?></div>
                <div style="margin-top: 1.5px;">
                    <strong>Kesimpulan :</strong> 
                    <span class="fw-bold" style="color: <?php echo (($p['kesimpulan'] ?? '') === 'Tidak Memenuhi Syarat (TMS)') ? '#dc2626' : '#15803d'; ?>;">
                        <?php echo html_escape($p['kesimpulan'] ?? 'Memenuhi Syarat (MS)'); ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Bagian Pengesahan & Dokumen Resmi Persis Excel -->
        <div>
            <table class="lcp-table border-all" style="margin-bottom: 3px;">
                <colgroup>
                    <col style="width: 50%;">
                    <col style="width: 50%;">
                </colgroup>
                <thead>
                    <tr class="bg-light-gray">
                        <th class="border-all text-center fw-bold" style="padding: 2px;">Penguji</th>
                        <th class="border-all text-center fw-bold" style="padding: 2px;">Diperiksa Penyelia / Verifikator</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="height: 44px;">
                        <td class="border-all text-center" style="vertical-align: middle;">
                            <?php if (!empty($ttd_penguji_img)): ?>
                                <img src="<?php echo $ttd_penguji_img; ?>" style="max-height: 38px; max-width: 85%;" alt="TTD Penguji">
                            <?php else: ?>
                                <span style="color: #94a3b8; font-style: italic; font-size: 6.5pt;">[Paraf Penguji]</span>
                            <?php endif; ?>
                        </td>
                        <td class="border-all text-center" style="vertical-align: middle;">
                            <?php if (!empty($ttd_penyelia_img)): ?>
                                <img src="<?php echo $ttd_penyelia_img; ?>" style="max-height: 38px; max-width: 85%;" alt="TTD Penyelia">
                            <?php else: ?>
                                <span style="color: #94a3b8; font-style: italic; font-size: 6.5pt;">
                                    <?php echo in_array($status_sesi, array('Verified', 'Approved', 'Selesai')) ? '[Telah Diperiksa]' : '[Menunggu Pemeriksaan]'; ?>
                                </span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="border-all" style="padding-left: 5px; font-size: 6.5pt;">
                            <div>Nama: <strong><?php echo html_escape($penguji['nama_lengkap'] ?? ($penguji['nama_user'] ?? '-')); ?></strong></div>
                            <div>Tanggal: <?php echo html_escape($tgl_lap); ?></div>
                        </td>
                        <td class="border-all" style="padding-left: 5px; font-size: 6.5pt;">
                            <div>Nama: <strong><?php echo html_escape($verifier['nama_lengkap'] ?? ($verifier['nama_user'] ?? '-')); ?></strong></div>
                            <div>Tanggal: <?php echo html_escape($tgl_lap); ?></div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Footer Dokumen Resmi BPOM Excel -->
            <table class="lcp-table" style="font-size: 6.2pt; color: #475569;">
                <tr>
                    <td style="width: 50%;">Ket: *Coret yang tidak perlu</td>
                    <td style="width: 50%; text-align: right;">Lembar 1 dari 1 Halaman</td>
                </tr>
                <tr>
                    <td colspan="2" style="font-weight: bold; font-family: monospace; font-size: 6.8pt; color: #000;">
                        POM-06.02/CFM.01/SOP.01/F.01A.03
                    </td>
                </tr>
            </table>
        </div>
    </div>

</body>
</html>
