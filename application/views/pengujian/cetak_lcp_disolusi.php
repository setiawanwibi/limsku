<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * View Cetak / Simpan PDF Lembar Catatan Pengujian (LCP)
 * DISOLUSI (Bidang Kimia)
 * Replikasi presisi 100% dari format Excel resmi BPOM:
 * POM-06.02/CFM.01/SOP.01/F.01A.08
 */

$status_sesi = $sesi['status'] ?? 'Draft';
$p = !empty($hasil['data_hasil']) ? json_decode($hasil['data_hasil'], true) : array();
if (isset($p['parameter_values']) && is_array($p['parameter_values'])) {
    $p = array_merge($p, $p['parameter_values']);
}

// Data tabel 6 bejana disolusi Tahap S1
$tabel_s1 = isset($p['tabel_s1']) && is_array($p['tabel_s1']) ? $p['tabel_s1'] : array();

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
    <title>Cetak LCP - DISOLUSI - <?php echo html_escape($sampel['nama_sampel'] ?? 'Sampel'); ?></title>
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
            padding: 8px 0;
            background-color: #525659;
            font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
            font-size: 6.8pt;
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
            padding: 1.5px 3px;
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

        .fraction {
            display: inline-block;
            vertical-align: middle;
            text-align: center;
            padding: 0 2px;
        }
        .fraction > span { display: block; padding-top: 1px; }
        .fraction span.bottom { border-top: 1px solid #000; }

        @media print {
            body { background-color: #fff !important; padding: 0 !important; }
            .no-print { display: none !important; }
            .document-wrapper {
                margin: 0 !important;
                padding: 4mm 6mm !important;
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
                        <div style="font-size: 10pt; font-weight: bold; margin-top: 1px;">DISOLUSI</div>
                        <div style="font-size: 7.5pt; font-weight: bold; margin-top: 2px;">
                            Laboratorium Kimia Balai Besar POM di Bandar Lampung
                        </div>
                    </td>
                </tr>
            </table>

            <!-- Header Info Sampel, Dissolution Tester & Media Persis Excel -->
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
                    <td class="border-l" style="padding-left: 4px;">Komposisi / Etiket (Ke)</td>
                    <td>:</td>
                    <td class="border-r fw-bold"><?php echo html_escape(!empty($p['komposisi_etiket']) ? $p['komposisi_etiket'] . ' mg' : '-'); ?></td>
                    <td class="border-l" style="padding-left: 4px;">Tanggal Uji</td>
                    <td>:</td>
                    <td class="border-r"><?php echo html_escape($p['tgl_uji'] ?? '-'); ?></td>
                </tr>
                <tr>
                    <td class="border-l" style="padding-left: 4px;">Merk Alat / Tgl PVT</td>
                    <td>:</td>
                    <td class="border-r"><?php echo html_escape($p['merk_alat_pvt'] ?? '-'); ?></td>
                    <td class="border-l" style="padding-left: 4px;">Tipe / Seri</td>
                    <td>:</td>
                    <td class="border-r"><?php echo html_escape($p['tipe_seri_alat'] ?? '-'); ?></td>
                </tr>
                <tr>
                    <td class="border-l" style="padding-left: 4px;">Media Disolusi / pH</td>
                    <td>:</td>
                    <td class="border-r"><?php echo html_escape($p['media_disolusi'] ?? '-'); ?> (pH: <?php echo html_escape($p['ph_terbaca'] ?? '-'); ?>)</td>
                    <td class="border-l" style="padding-left: 4px;">Volume Media (V)</td>
                    <td>:</td>
                    <td class="border-r"><?php echo html_escape(!empty($p['volume_media']) ? $p['volume_media'] . ' mL' : '-'); ?></td>
                </tr>
                <tr>
                    <td class="border-l" style="padding-left: 4px;">Tipe Alat* / Kecepatan</td>
                    <td>:</td>
                    <td class="border-r"><?php echo html_escape($p['tipe_aparatus'] ?? 'Dayung'); ?> / <?php echo html_escape($p['kecepatan_putar'] ?? '50'); ?> rpm</td>
                    <td class="border-l" style="padding-left: 4px;">Waktu Pengujian</td>
                    <td>:</td>
                    <td class="border-r"><?php echo html_escape($p['waktu_menit'] ?? '45'); ?> menit</td>
                </tr>
                <tr>
                    <td class="border-l" style="padding-left: 4px;">Toleransi (Q)</td>
                    <td>:</td>
                    <td class="border-r fw-bold" style="color: #0284c7;"><?php echo html_escape($p['toleransi_q'] ?? 'Q = 80 %'); ?></td>
                    <td class="border-l" style="padding-left: 4px;">Sampling Pooled*</td>
                    <td>:</td>
                    <td class="border-r"><?php echo html_escape($p['teknik_sampling_pooled'] ?? 'Tidak'); ?></td>
                </tr>
                <tr>
                    <td class="border-l" style="padding-left: 4px;">Instrumen Deteksi</td>
                    <td>:</td>
                    <td class="border-r"><?php echo html_escape($p['instrumen_deteksi'] ?? 'Spektrofotometri UV'); ?> (λ: <?php echo html_escape($p['lambda_uji'] ?? '-'); ?>)</td>
                    <td class="border-l" style="padding-left: 4px;">Baku Pembanding</td>
                    <td>:</td>
                    <td class="border-r"><?php echo html_escape($p['baku_pembanding'] ?? '-'); ?></td>
                </tr>
                <tr>
                    <td class="border-l border-b" style="padding-left: 4px;">Parameter Baku (Bb/Ab)</td>
                    <td class="border-b">:</td>
                    <td class="border-r border-b">Bb: <?php echo html_escape($p['bobot_baku_mg'] ?? '-'); ?> mg | Ab: <?php echo html_escape($p['hasil_ukur_baku_ab'] ?? '-'); ?></td>
                    <td class="border-l border-b" style="padding-left: 4px;">Pengenceran (Fb/Fu)</td>
                    <td class="border-b">:</td>
                    <td class="border-r border-b">Fb: <?php echo html_escape($p['faktor_pengenceran_baku'] ?? '-'); ?> | Fu: <?php echo html_escape($p['faktor_pengenceran_uji'] ?? '-'); ?></td>
                </tr>
            </table>

            <!-- Rumus Faktor Perkalian (Fk) Persis Excel -->
            <div style="font-weight: bold; margin-bottom: 2px;">
                Faktor Perkalian: <span style="font-family: monospace;">Fk = (V &times; Fu &times; Bb &times; Kb &times; [BM1/BM2] &times; 100%) / (Fb &times; Ab &times; Ke)</span>
                &nbsp;&nbsp;=&nbsp;&nbsp;<span style="color: #0284c7; font-weight: bold;"><?php echo html_escape($p['faktor_perkalian_fk'] ?? '-'); ?></span>
            </div>

            <!-- Tabel Pengamatan Tahap S1 (6 Bejana) Persis Excel -->
            <table class="lcp-table border-all" style="margin-bottom: 4px;">
                <colgroup>
                    <col style="width: 20%;">
                    <col style="width: 40%;">
                    <col style="width: 40%;">
                </colgroup>
                <thead>
                    <tr class="bg-light-gray" style="height: 20px;">
                        <th class="border-all text-center fw-bold">Tahap S1 (Bejana)</th>
                        <th class="border-all text-center fw-bold">Pengukuran (Au / Area)</th>
                        <th class="border-all text-center fw-bold">% Zat Aktif Terlarut {Dx = Fk &times; Au}</th>
                    </tr>
                </thead>
                <tbody>
                    <?php for ($v = 1; $v <= 6; $v++): 
                        $r_v = $tabel_s1[$v - 1] ?? array();
                    ?>
                        <tr style="height: 16px;">
                            <td class="border-all text-center fw-bold"><?php echo $v; ?></td>
                            <td class="border-all text-right" style="padding-right: 15px;"><?php echo html_escape($r_v['pengukuran_au'] ?? ''); ?></td>
                            <td class="border-all text-right fw-bold" style="padding-right: 15px;"><?php echo html_escape($r_v['persen_terlarut'] ?? ''); ?> %</td>
                        </tr>
                    <?php endfor; ?>
                    <tr class="bg-light-gray" style="height: 18px;">
                        <td class="border-all fw-bold text-center">Rata-rata Tahap S1</td>
                        <td class="border-all text-center text-muted">-</td>
                        <td class="border-all text-right fw-bold" style="padding-right: 15px; color: #0284c7;"><?php echo html_escape($p['rata_rata_s1'] ?? '-'); ?> %</td>
                    </tr>
                    <tr class="bg-light-gray" style="height: 18px;">
                        <td class="border-all fw-bold text-center">Nilai Terendah (Minimum)</td>
                        <td class="border-all text-center text-muted">-</td>
                        <td class="border-all text-right fw-bold" style="padding-right: 15px; color: #e11d48;"><?php echo html_escape($p['nilai_minimum_s1'] ?? '-'); ?> %</td>
                    </tr>
                </tbody>
            </table>

            <!-- Syarat, Pustaka & Kesimpulan Persis Excel -->
            <div style="font-size: 6.8pt; margin-bottom: 3px;">
                <div><strong>Syarat :</strong> <?php echo html_escape($p['syarat_disolusi'] ?? 'Tiap unit sediaan tidak kurang dari Q + 5% (85%) dalam 45 menit'); ?></div>
                <div style="margin-top: 1px;"><strong>Pustaka :</strong> <?php echo html_escape($p['pustaka_disolusi'] ?? ($metode['pustaka_acuan'] ?? 'Farmakope Indonesia Edisi VI <1231>')); ?></div>
                <div style="margin-top: 2px;">
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
                        <th class="border-all text-center fw-bold" style="padding: 2.5px;">Penguji</th>
                        <th class="border-all text-center fw-bold" style="padding: 2.5px;">Diperiksa Penyelia / Verifikator :</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="height: 48px;">
                        <td class="border-all text-center" style="vertical-align: middle;">
                            <?php if (!empty($ttd_penguji_img)): ?>
                                <img src="<?php echo $ttd_penguji_img; ?>" style="max-height: 40px; max-width: 85%;" alt="TTD Penguji">
                            <?php else: ?>
                                <span style="color: #94a3b8; font-style: italic; font-size: 6.5pt;">[Nama dan Paraf]</span>
                            <?php endif; ?>
                        </td>
                        <td class="border-all text-center" style="vertical-align: middle;">
                            <?php if (!empty($ttd_penyelia_img)): ?>
                                <img src="<?php echo $ttd_penyelia_img; ?>" style="max-height: 40px; max-width: 85%;" alt="TTD Penyelia">
                            <?php else: ?>
                                <span style="color: #94a3b8; font-style: italic; font-size: 6.5pt;">
                                    <?php echo in_array($status_sesi, array('Verified', 'Approved', 'Selesai')) ? '[Telah Diperiksa]' : '[Nama dan Paraf]'; ?>
                                </span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="border-all" style="padding-left: 6px; font-size: 6.5pt;">
                            <div>Nama: <strong><?php echo html_escape($penguji['nama_lengkap'] ?? ($penguji['nama_user'] ?? '-')); ?></strong></div>
                            <div>Tanggal Laporan: <?php echo html_escape($tgl_lap); ?></div>
                        </td>
                        <td class="border-all" style="padding-left: 6px; font-size: 6.5pt;">
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
                    <td colspan="2" style="font-weight: bold; font-family: monospace; font-size: 6.8pt; color: #000; padding-top: 1px;">
                        POM-06.02/CFM.01/SOP.01/F.01A.08
                    </td>
                </tr>
            </table>
        </div>
    </div>

</body>
</html>
