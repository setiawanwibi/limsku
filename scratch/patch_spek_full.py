with open('application/views/pengujian/cetak_lcp_spektrofotometri.php', 'r', encoding='utf-8') as f:
    text = f.read()

# 1. Add $is_identifikasi definition at the top
target_init = "$tabel_prep = isset($p['tabel_preparasi']) && is_array($p['tabel_preparasi']) ? $p['tabel_preparasi'] : array();"
replacement_init = """$tabel_prep = isset($p['tabel_preparasi']) && is_array($p['tabel_preparasi']) ? $p['tabel_preparasi'] : array();
$param_uji = $metode['parameter_uji'] ?? ($hasil['nama_metode'] ?? ($p['parameter_uji'] ?? ''));
$is_identifikasi = (stripos($param_uji, 'identifikasi') !== false || stripos($hasil['nama_metode'] ?? '', 'identifikasi') !== false || stripos($p['nama_template'] ?? '', 'identifikasi') !== false);"""

if target_init in text and '$is_identifikasi =' not in text:
    text = text.replace(target_init, replacement_init, 1)
    print("Added $is_identifikasi in cetak_lcp_spektrofotometri.php")

# 2. Update metadata table rows (Scanning, Jenis spektrum, Pelarut) to hide Br, Ke, BM when $is_identifikasi
target_meta_rows = """                <tr>
                    <td class="border-l" style="padding-left: 4px;">Scanning</td>
                    <td>:</td>
                    <td class="border-r"><?php echo html_escape($p['kecepatan_scanning'] ?? 'Sedang'); ?> (λ: <?php echo html_escape($p['rentang_panjang_gelombang'] ?? '200 - 400 nm'); ?>)</td>
                    <td class="border-l" style="padding-left: 4px;">Bobot Rata-rata (Br)</td>
                    <td>:</td>
                    <td class="border-r"><?php echo html_escape(!empty($p['bobot_rata_rata_tablet']) ? $p['bobot_rata_rata_tablet'] . ' mg' : '-'); ?></td>
                </tr>
                <tr>
                    <td class="border-l" style="padding-left: 4px;">Jenis spektrum / Kuvet</td>
                    <td>:</td>
                    <td class="border-r"><?php echo html_escape($p['jenis_spektrum'] ?? 'Normal'); ?> / <?php echo html_escape($p['jenis_kuvet'] ?? 'Kwarsa'); ?> (Tebal: <?php echo html_escape($p['tebal_kuvet'] ?? '1 cm'); ?>)</td>
                    <td class="border-l" style="padding-left: 4px;">Klaim Etiket (Ke)</td>
                    <td>:</td>
                    <td class="border-r fw-bold"><?php echo html_escape(!empty($p['klaim_etiket']) ? $p['klaim_etiket'] . ' mg/tab' : '-'); ?></td>
                </tr>
                <tr>
                    <td class="border-l border-b" style="padding-left: 4px;">Pelarut / Blanko</td>
                    <td class="border-b">:</td>
                    <td class="border-r border-b"><?php echo html_escape($p['pelarut'] ?? '-'); ?> / <?php echo html_escape($p['blanko'] ?? '-'); ?></td>
                    <td class="border-l border-b" style="padding-left: 4px;">Faktor Koreksi BM</td>
                    <td class="border-b">:</td>
                    <td class="border-r border-b fw-bold">
                        <?php
                            $bm_raw = trim((string)(!empty($p['faktor_konversi_bm']) ? $p['faktor_konversi_bm'] : '1.0'));
                            $bm_eval = null;
                            if (strpos($bm_raw, '/') !== false || strpos($bm_raw, '*') !== false) {
                                $clean_expr = preg_replace('/[^0-9\.\+\-\*\/]/', '', str_replace(',', '.', $bm_raw));
                                try {
                                    @eval("\$bm_eval = ($clean_expr);");
                                } catch (Throwable $e) {}
                            }
                            echo html_escape($bm_raw);
                            if ($bm_eval !== null && is_numeric($bm_eval) && $bm_eval > 0) {
                                echo ' <span style="color: #0284c7;">( = ' . number_format($bm_eval, 4, '.', '') . ' )</span>';
                            }
                        ?>
                    </td>
                </tr>"""

replacement_meta_rows = """<?php if ($is_identifikasi): ?>
                <tr>
                    <td class="border-l" style="padding-left: 4px;">Scanning</td>
                    <td>:</td>
                    <td class="border-r" colspan="4"><?php echo html_escape($p['kecepatan_scanning'] ?? 'Sedang'); ?> (λ: <?php echo html_escape($p['rentang_panjang_gelombang'] ?? '200 - 400 nm'); ?>)</td>
                </tr>
                <tr>
                    <td class="border-l" style="padding-left: 4px;">Jenis spektrum / Kuvet</td>
                    <td>:</td>
                    <td class="border-r" colspan="4"><?php echo html_escape($p['jenis_spektrum'] ?? 'Normal'); ?> / <?php echo html_escape($p['jenis_kuvet'] ?? 'Kwarsa'); ?> (Tebal: <?php echo html_escape($p['tebal_kuvet'] ?? '1 cm'); ?>)</td>
                </tr>
                <tr>
                    <td class="border-l border-b" style="padding-left: 4px;">Pelarut / Blanko</td>
                    <td class="border-b">:</td>
                    <td class="border-r border-b" colspan="4"><?php echo html_escape($p['pelarut'] ?? '-'); ?> / <?php echo html_escape($p['blanko'] ?? '-'); ?></td>
                </tr>
<?php else: ?>
""" + target_meta_rows + """
<?php endif; ?>"""

if target_meta_rows in text:
    text = text.replace(target_meta_rows, replacement_meta_rows, 1)
    print("Updated metadata rows in cetak_lcp_spektrofotometri.php")
else:
    print("Target meta rows not found in cetak_lcp_spektrofotometri.php")

with open('application/views/pengujian/cetak_lcp_spektrofotometri.php', 'w', encoding='utf-8') as f:
    f.write(text)
print("Saved cetak_lcp_spektrofotometri.php successfully!")

