# Python patch script for cetak_lcp_kckt.php, cetak_lcp_spektrofotometri.php, and cetak_lcp_klt.php
import re

# 1. cetak_lcp_kckt.php
with open('application/views/pengujian/cetak_lcp_kckt.php', 'r', encoding='utf-8') as f:
    kckt = f.read()

# Add $is_identifikasi after $tabel_hplc definition
target_kckt_init = "$tabel_hplc = isset($p['tabel_hplc']) && is_array($p['tabel_hplc']) ? $p['tabel_hplc'] : array();"
replacement_kckt_init = """$tabel_hplc = isset($p['tabel_hplc']) && is_array($p['tabel_hplc']) ? $p['tabel_hplc'] : array();
$param_uji = $metode['parameter_uji'] ?? ($hasil['nama_metode'] ?? ($p['parameter_uji'] ?? ''));
$is_identifikasi = (stripos($param_uji, 'identifikasi') !== false || stripos($hasil['nama_metode'] ?? '', 'identifikasi') !== false || stripos($p['nama_template'] ?? '', 'identifikasi') !== false);"""

if target_kckt_init in kckt and '$is_identifikasi =' not in kckt:
    kckt = kckt.replace(target_kckt_init, replacement_kckt_init, 1)
    print("Added $is_identifikasi in cetak_lcp_kckt.php")

# Hide Faktor BM in cetak_lcp_kckt.php if identifikasi
target_bm_row = "<?php if (!empty($p['faktor_konversi_bm']) && $p['faktor_konversi_bm'] !== '1.0' && $p['faktor_konversi_bm'] !== '1'): ?>"
replacement_bm_row = "<?php if (!$is_identifikasi && !empty($p['faktor_konversi_bm']) && $p['faktor_konversi_bm'] !== '1.0' && $p['faktor_konversi_bm'] !== '1'): ?>"
if target_bm_row in kckt:
    kckt = kckt.replace(target_bm_row, replacement_bm_row, 1)
    print("Updated BM row condition in cetak_lcp_kckt.php")

# Default rows in cetak_lcp_kckt.php
target_kckt_rows = """            $rows = !empty($tabel_hplc) ? $tabel_hplc : array(
                array('kategori' => 'Baku Pembanding'),
                array('kategori' => 'Baku Internal'),
                array('kategori' => 'Zat Uji Replikasi 1'),
                array('kategori' => 'Zat Uji Replikasi 2')
            );

            foreach ($rows as $r):
                $kat = $r['kategori'] ?? '-';
                if (stripos($kat, 'spiked') !== false) continue;"""

replacement_kckt_rows = """            if ($is_identifikasi) {
                $rows = !empty($tabel_hplc) ? $tabel_hplc : array(
                    array('kategori' => 'Baku Pembanding'),
                    array('kategori' => 'Pelarut'),
                    array('kategori' => 'Zat Uji'),
                    array('kategori' => 'Spike')
                );
            } else {
                $rows = !empty($tabel_hplc) ? $tabel_hplc : array(
                    array('kategori' => 'Baku Pembanding'),
                    array('kategori' => 'Baku Internal'),
                    array('kategori' => 'Zat Uji Replikasi 1'),
                    array('kategori' => 'Zat Uji Replikasi 2')
                );
            }

            foreach ($rows as $r):
                $kat = $r['kategori'] ?? '-';
                if (!$is_identifikasi && stripos($kat, 'spiked') !== false) continue;"""

if target_kckt_rows in kckt:
    kckt = kckt.replace(target_kckt_rows, replacement_kckt_rows, 1)
    print("Updated table rows in cetak_lcp_kckt.php")
else:
    print("Target table rows not found in cetak_lcp_kckt.php")

# Rumus & Perhitungan Kadar section in cetak_lcp_kckt.php
target_kckt_rumus = '    <!-- RUMUS DAN DETAIL PERHITUNGAN -->'
idx_rumus = kckt.find(target_kckt_rumus)
target_kckt_pustaka = '    <!-- PUSTAKA, SYARAT & KESIMPULAN -->'
idx_pustaka = kckt.find(target_kckt_pustaka)

if idx_rumus != -1 and idx_pustaka != -1:
    rumus_and_calc_block = kckt[idx_rumus:idx_pustaka]
    new_block = """    <?php if ($is_identifikasi): ?>
    <!-- HASIL PENGUJIAN IDENTIFIKASI (KUALITATIF - Poin 1 & 3: Tanpa Rumus Kadar) -->
    <table class="table-data" style="margin-top: 6px;">
        <tr>
            <td style="width: 25%;">Hasil Pengamatan / Uji</td>
            <td style="width: 2%;">:</td>
            <td colspan="4" style="font-weight: bold; font-size: 8.5pt; color: #004b87;">
                <?php echo html_escape($p['hasil_uji'] ?? ($p['hasil_identifikasi'] ?? 'Sesuai / Positif')); ?>
            </td>
        </tr>
        <tr>
            <td>Keterangan Uji Identifikasi</td>
            <td>:</td>
            <td colspan="4">
                <em>Waktu retensi (Rt) puncak utama kromatogram larutan uji sesuai dengan larutan baku pembanding.</em>
            </td>
        </tr>
    </table>
    <?php else: ?>
""" + rumus_and_calc_block + """    <?php endif; ?>
"""
    kckt = kckt[:idx_rumus] + new_block + kckt[idx_pustaka:]
    print("Wrapped rumus and kadar calculation in condition for cetak_lcp_kckt.php")
else:
    print("Could not find rumus / pustaka indices in cetak_lcp_kckt.php")

with open('application/views/pengujian/cetak_lcp_kckt.php', 'w', encoding='utf-8') as f:
    f.write(kckt)
print("cetak_lcp_kckt.php saved successfully!")


# 2. cetak_lcp_spektrofotometri.php
with open('application/views/pengujian/cetak_lcp_spektrofotometri.php', 'r', encoding='utf-8') as f:
    spek = f.read()

target_spek_init = "$tabel_preparasi = isset($p['tabel_preparasi']) && is_array($p['tabel_preparasi']) ? $p['tabel_preparasi'] : array();"
replacement_spek_init = """$tabel_preparasi = isset($p['tabel_preparasi']) && is_array($p['tabel_preparasi']) ? $p['tabel_preparasi'] : array();
$param_uji = $metode['parameter_uji'] ?? ($hasil['nama_metode'] ?? ($p['parameter_uji'] ?? ''));
$is_identifikasi = (stripos($param_uji, 'identifikasi') !== false || stripos($hasil['nama_metode'] ?? '', 'identifikasi') !== false || stripos($p['nama_template'] ?? '', 'identifikasi') !== false);"""

if target_spek_init in spek and '$is_identifikasi =' not in spek:
    spek = spek.replace(target_spek_init, replacement_spek_init, 1)
    print("Added $is_identifikasi in cetak_lcp_spektrofotometri.php")

# Hide Br, Ke, Faktor BM header in spek if identifikasi
target_spek_meta = '<!-- Baris 1: Kadar Baku & Bobot Rata-rata -->'
idx_meta_start = spek.find(target_spek_meta)
target_spek_table = '<!-- Tabel Penimbangan, Preparasi & Pengukuran Serapan Persis Excel -->'
idx_table_start = spek.find(target_spek_table)

if idx_meta_start != -1 and idx_table_start != -1:
    meta_block = spek[idx_meta_start:idx_table_start]
    new_meta_block = """<?php if (!$is_identifikasi): ?>
""" + meta_block + """<?php endif; ?>
"""
    spek = spek[:idx_meta_start] + new_meta_block + spek[idx_table_start:]
    print("Wrapped metadata header in !$is_identifikasi condition in cetak_lcp_spektrofotometri.php")

# Handle table in cetak_lcp_spektrofotometri.php
target_spek_tbody = """                <tbody>
                    <!-- Baris 0: Baku Pembanding -->"""
idx_tbody = spek.find(target_spek_tbody)
target_spek_calc = '<!-- Rumus Perhitungan & Sub-Perhitungan Uji 1 & Uji 2 Persis Excel -->'
idx_calc = spek.find(target_spek_calc)
target_spek_pustaka = '<!-- Pustaka, Syarat Mutu & Kesimpulan -->'
idx_spek_pustaka = spek.find(target_spek_pustaka)

if idx_tbody != -1 and idx_calc != -1 and idx_spek_pustaka != -1:
    calc_block = spek[idx_calc:idx_spek_pustaka]
    new_calc_block = """<?php if ($is_identifikasi): ?>
            <!-- HASIL PENGUJIAN IDENTIFIKASI (KUALITATIF - Poin 1 & 3: Tanpa Rumus Kadar) -->
            <table class="lcp-table border-all" style="margin-bottom: 4px;">
                <colgroup>
                    <col style="width: 30%;">
                    <col style="width: 2%;">
                    <col style="width: 68%;">
                </colgroup>
                <tr>
                    <td class="border-all" style="padding-left: 4px;">Hasil Pengamatan / Uji</td>
                    <td class="border-all text-center">:</td>
                    <td class="border-all fw-bold" style="color: #0284c7; padding-left: 4px;">
                        <?php echo html_escape($p['hasil_uji'] ?? ($p['hasil_identifikasi'] ?? 'Sesuai / Positif')); ?>
                    </td>
                </tr>
                <tr>
                    <td class="border-all" style="padding-left: 4px;">Keterangan Spektrum</td>
                    <td class="border-all text-center">:</td>
                    <td class="border-all" style="padding-left: 4px;">
                        <em>Spektrum serapan UV-Vis larutan uji menunjukkan serapan maksimum pada panjang gelombang yang sama seperti baku pembanding.</em>
                    </td>
                </tr>
            </table>
<?php else: ?>
""" + calc_block + """<?php endif; ?>
"""
    spek = spek[:idx_calc] + new_calc_block + spek[idx_spek_pustaka:]
    print("Wrapped calc block in cetak_lcp_spektrofotometri.php")

with open('application/views/pengujian/cetak_lcp_spektrofotometri.php', 'w', encoding='utf-8') as f:
    f.write(spek)
print("cetak_lcp_spektrofotometri.php saved successfully!")


# 3. cetak_lcp_klt.php
with open('application/views/pengujian/cetak_lcp_klt.php', 'r', encoding='utf-8') as f:
    klt = f.read()

target_klt_rows = """            $rows = !empty($klt_items) ? $klt_items : array(
                array('kategori' => 'Baku Pembanding', 'nama_zat' => ''),
                array('kategori' => 'Zat Uji 1', 'nama_zat' => '1. '),
                array('kategori' => 'Zat Uji 2', 'nama_zat' => '2. '),
                array('kategori' => 'Spiked Sample: Zat Uji', 'nama_zat' => 'Zat Uji'),
                array('kategori' => 'Spiked Sample: Baku', 'nama_zat' => 'Baku Pembanding')
            );"""

replacement_klt_rows = """            $rows = !empty($klt_items) ? $klt_items : array(
                array('kategori' => 'Baku Pembanding', 'nama_zat' => 'Baku Pembanding'),
                array('kategori' => 'Pelarut', 'nama_zat' => 'Pelarut'),
                array('kategori' => 'Zat Uji', 'nama_zat' => 'Zat Uji'),
                array('kategori' => 'Spike', 'nama_zat' => 'Spike')
            );"""

if target_klt_rows in klt:
    klt = klt.replace(target_klt_rows, replacement_klt_rows, 1)
    print("Updated default rows to 4 rows (Baku Pembanding, Pelarut, Zat Uji, Spike) in cetak_lcp_klt.php")
else:
    print("Target rows not found in cetak_lcp_klt.php")

with open('application/views/pengujian/cetak_lcp_klt.php', 'w', encoding='utf-8') as f:
    f.write(klt)
print("cetak_lcp_klt.php saved successfully!")

