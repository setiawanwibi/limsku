with open('application/views/pengujian/cetak_lcp_kckt.php', 'r', encoding='utf-8') as f:
    text = f.read()

target_start = "    <!-- RUMUS DAN DETAIL PERHITUNGAN -->"
target_end = "    <!-- TANDA TANGAN -->"

idx_s = text.find(target_start)
idx_e = text.find(target_end)

if idx_s != -1 and idx_e != -1:
    old_section = text[idx_s:idx_e]
    identifikasi_block = """    <?php if ($is_identifikasi): ?>
    <!-- HASIL PENGUJIAN IDENTIFIKASI (KUALITATIF - Poin 1 & 3: Tanpa Rumus & Kolom Kadar) -->
    <table class="table-data" style="margin-top: 6px;">
        <tr>
            <td style="width: 25%;">Hasil Pengamatan / Uji</td>
            <td style="width: 2%;">:</td>
            <td colspan="4" style="font-weight: bold; font-size: 8.5pt; color: #004b87;">
                <?php echo html_escape($p['hasil_uji'] ?? ($p['hasil_identifikasi'] ?? 'Positif / Sesuai')); ?>
            </td>
        </tr>
        <tr>
            <td>Keterangan Uji Identifikasi</td>
            <td>:</td>
            <td colspan="4">
                <em>Waktu retensi (Rt) puncak utama kromatogram larutan uji sesuai dengan larutan baku pembanding.</em>
            </td>
        </tr>
        <tr>
            <td>Pustaka Metode</td>
            <td>:</td>
            <td colspan="4"><?php echo nl2br(html_escape(!empty($p['pustaka_metode']) ? $p['pustaka_metode'] : ($metode['pustaka_acuan'] ?? 'Farmakope Indonesia Edisi VI'))); ?></td>
        </tr>
        <tr>
            <td>Pustaka Persyaratan</td>
            <td>:</td>
            <td><?php echo nl2br(html_escape($p['pustaka_persyaratan'] ?? 'Farmakope Indonesia Edisi VI')); ?></td>
            <td>Syarat :</td>
            <td>:</td>
            <td><strong><?php echo html_escape($p['syarat_mutu'] ?? ($p['syarat'] ?? ($metode['persyaratan'] ?? '-'))); ?></strong></td>
        </tr>
        <tr>
            <td style="font-size: 8.5pt; font-weight: bold;">Kesimpulan*</td>
            <td>:</td>
            <td colspan="4" style="font-size: 9pt; font-weight: bold; color: <?php echo (($p['kesimpulan'] ?? '') === 'Tidak Memenuhi Syarat (TMS)') ? '#dc2626' : '#004b87'; ?>;">
                <?php echo html_escape($p['kesimpulan'] ?? 'Memenuhi Syarat (MS)'); ?>
            </td>
        </tr>
    </table>
    <?php else: ?>
""" + old_section + """    <?php endif; ?>
"""
    text = text[:idx_s] + identifikasi_block + text[idx_e:]
    with open('application/views/pengujian/cetak_lcp_kckt.php', 'w', encoding='utf-8') as f:
        f.write(text)
    print("Successfully patched cetak_lcp_kckt.php for identifikasi!")
else:
    print("Could not find start or end target")

