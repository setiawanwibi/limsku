with open('application/views/pengujian/cetak_lcp_spektrofotometri.php', 'r', encoding='utf-8') as f:
    text = f.read()

target_tbody_start = "                <tbody>\n                    <tr>\n                        <td class=\"border-all fw-bold\"><?php echo html_escape(!empty($baku_row['nama_zat']) ? $baku_row['nama_zat'] : 'Baku Pembanding'); ?></td>"

replacement_tbody = """                <tbody>
<?php if ($is_identifikasi): ?>
                    <?php
                    $spek_rows = !empty($tabel_preparasi) ? $tabel_preparasi : array(
                        array('nama_zat' => 'Baku Pembanding'),
                        array('nama_zat' => 'Pelarut'),
                        array('nama_zat' => 'Zat Uji'),
                        array('nama_zat' => 'Spike')
                    );
                    $default_labels = array('Baku Pembanding', 'Pelarut', 'Zat Uji', 'Spike');
                    foreach ($default_labels as $d_idx => $d_lbl) {
                        if (!isset($spek_rows[$d_idx])) {
                            $spek_rows[$d_idx] = array('nama_zat' => $d_lbl);
                        } elseif (empty($spek_rows[$d_idx]['nama_zat'])) {
                            $spek_rows[$d_idx]['nama_zat'] = $d_lbl;
                        }
                    }
                    ?>
                    <?php foreach ($spek_rows as $sr): ?>
                    <tr>
                        <td class="border-all fw-bold"><?php echo html_escape($sr['nama_zat'] ?? ($sr['kategori'] ?? '-')); ?></td>
                        <td class="border-all text-right"><?php echo html_escape($sr['wadah_zat'] ?? '-'); ?></td>
                        <td class="border-all text-right"><?php echo html_escape($sr['wadah_sisa'] ?? '-'); ?></td>
                        <td class="border-all text-right fw-bold"><?php echo html_escape($sr['bobot_zat'] ?? '-'); ?></td>
                        <td class="border-all text-center"><?php echo html_escape($sr['faktor_pengenceran'] ?? '-'); ?></td>
                        <td class="border-all text-right"><?php echo html_escape($sr['konsentrasi'] ?? '-'); ?></td>
                        <td class="border-all text-center"><?php echo html_escape($sr['lambda_maks'] ?? '-'); ?></td>
                        <td class="border-all text-right fw-bold"><?php echo html_escape($sr['serapan'] ?? '-'); ?></td>
                    </tr>
                    <?php endforeach; ?>
<?php else: ?>
                    <tr>
                        <td class="border-all fw-bold"><?php echo html_escape(!empty($baku_row['nama_zat']) ? $baku_row['nama_zat'] : 'Baku Pembanding'); ?></td>"""

if target_tbody_start in text:
    text = text.replace(target_tbody_start, replacement_tbody, 1)
    # Now close the <?php endif; ?> before </tbody>
    target_tbody_end = "                    </tr>\n                </tbody>"
    idx_end = text.find(target_tbody_end)
    if idx_end != -1:
        text = text[:idx_end] + "                    </tr>\n<?php endif; ?>\n                </tbody>" + text[idx_end + len(target_tbody_end):]
        with open('application/views/pengujian/cetak_lcp_spektrofotometri.php', 'w', encoding='utf-8') as f:
            f.write(text)
        print("Updated cetak_lcp_spektrofotometri.php 4 rows successfully!")
    else:
        print("Target tbody end not found")
else:
    print("Target tbody start not found")

