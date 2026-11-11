with open('application/views/pengujian/cetak_lcp_spektrofotometri.php', 'r', encoding='utf-8') as f:
    text = f.read()

target_start = "            <!-- Rumus Perhitungan & Sub-Perhitungan Uji 1 & Uji 2 Persis Excel -->"
target_end = "            <!-- Pustaka, Syarat Mutu & Kesimpulan -->"

idx_s = text.find(target_start)
idx_e = text.find(target_end)

if idx_s != -1 and idx_e != -1:
    old_calc = text[idx_s:idx_e]
    identifikasi_calc_block = """            <?php if ($is_identifikasi): ?>
            <!-- HASIL PENGUJIAN IDENTIFIKASI (KUALITATIF - Poin 1 & 3: Tanpa Rumus & Kolom Kadar) -->
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
""" + old_calc + """            <?php endif; ?>
"""
    text = text[:idx_s] + identifikasi_calc_block + text[idx_e:]
    with open('application/views/pengujian/cetak_lcp_spektrofotometri.php', 'w', encoding='utf-8') as f:
        f.write(text)
    print("Successfully patched cetak_lcp_spektrofotometri.php calc block!")
else:
    print(f"Could not find indices: idx_s={idx_s}, idx_e={idx_e}")

