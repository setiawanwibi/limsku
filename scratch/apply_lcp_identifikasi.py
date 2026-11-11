# Python patch script for LCP Identifikasi (3 Poin Revisi)
import re

# 1. Update application/views/pengujian/proses_sesi.php
with open('application/views/pengujian/proses_sesi.php', 'r', encoding='utf-8') as f:
    ps_content = f.read()

# Let's check where $param_name or $is_identifikasi can be defined in proses_sesi.php
# Around line 731: foreach ($hasil_forms as $idx => $f):
target_tab_loop = "<?php foreach ($hasil_forms as $idx => $f): ?>"
replacement_tab_loop = """<?php foreach ($hasil_forms as $idx => $f): ?>
                        <?php
                            $f_param_name = $f['parameter_uji'] ?: ($f['nama_metode'] ?? '');
                            $f_tpl_name   = $f['nama_template'] ?: '';
                            $is_identifikasi = (stripos($f_param_name, 'identifikasi') !== false || stripos($f_tpl_name, 'identifikasi') !== false);
                        ?>"""

if target_tab_loop in ps_content and '$is_identifikasi =' not in ps_content:
    ps_content = ps_content.replace(target_tab_loop, replacement_tab_loop, 1)
    print("Added $is_identifikasi definition in proses_sesi.php")
else:
    print("Tab loop already updated or not found")

# Skip sections in proses_sesi.php:
# foreach ($schema['sections'] as $sec):
target_sec_loop = "<?php foreach ($schema['sections'] as $sec): ?>"
replacement_sec_loop = """<?php foreach ($schema['sections'] as $sec): ?>
                                                <?php
                                                    $sec_id = $sec['id'] ?? '';
                                                    // Poin 1 & 3: Skip bagian perhitungan kadar untuk parameter Identifikasi
                                                    if ($is_identifikasi && in_array($sec_id, array('perhitungan_kadar_hplc', 'perhitungan_kadar_dan_presisi', 'perhitungan_kadar'), true)) {
                                                        continue;
                                                    }
                                                ?>"""

if target_sec_loop in ps_content and 'Skip bagian perhitungan kadar untuk parameter Identifikasi' not in ps_content:
    ps_content = ps_content.replace(target_sec_loop, replacement_sec_loop, 1)
    print("Added section skip for identifikasi in proses_sesi.php")
else:
    print("Section skip already present or not found")

# Skip fields in proses_sesi.php:
# foreach ($sec['fields'] as $fld):
target_fld_loop = "<?php foreach ($sec['fields'] as $fld): ?>"
replacement_fld_loop = """<?php foreach ($sec['fields'] as $fld): ?>
                                                                <?php
                                                                    $fld_id = isset($fld['id']) ? $fld['id'] : '';
                                                                    // Poin 1 & 3: Skip field kadar sediaan, bobot rata-rata, klaim, faktor koreksi BM, kadar baku internal untuk Identifikasi
                                                                    $kadar_exclusive_flds = array(
                                                                        'metode_perhitungan_kadar', 'volume_takaran_etiket', 'bobot_rata_tablet',
                                                                        'bobot_rata_rata_tablet', 'klaim_etiket', 'faktor_konversi_bm',
                                                                        'baku_kadar', 'baku_sp', 'baku_kadar_terkoreksi',
                                                                        'internal_kadar', 'internal_sp', 'internal_kadar_terkoreksi',
                                                                        'kadar_replikasi_1', 'kadar_replikasi_2', 'kadar_rata_persen',
                                                                        'kadar_rata_mg', 'rpd', 'syarat_horwitz'
                                                                    );
                                                                    if ($is_identifikasi && in_array($fld_id, $kadar_exclusive_flds, true)) {
                                                                        continue;
                                                                    }
                                                                ?>"""

if target_fld_loop in ps_content and 'Skip field kadar sediaan, bobot rata-rata' not in ps_content:
    ps_content = ps_content.replace(target_fld_loop, replacement_fld_loop, 1)
    print("Added field skip for identifikasi in proses_sesi.php")
else:
    print("Field skip already present or not found")

# Poin 2: Override default rows to 4 rows for Identifikasi (Baku Pembanding, Pelarut, Zat Uji, Spike)
# And allow text inputs in table cells
target_rows = """                                                                                    <?php
                                                                                        $existing_rows = !empty($raw_val) && is_array($raw_val)
                                                                                            ? $raw_val
                                                                                            : (isset($fld['default_rows']) ? $fld['default_rows'] : array(array()));
                                                                                    ?>"""

replacement_rows = """                                                                                    <?php
                                                                                        // Poin 2: Baris tabel baku untuk Identifikasi (Baku Pembanding, Pelarut, Zat Uji, Spike)
                                                                                        if ($is_identifikasi && in_array($fld_id, array('tabel_hplc', 'tabel_preparasi', 'klt_items'), true)) {
                                                                                            if (empty($raw_val) || !is_array($raw_val) || count($raw_val) < 2) {
                                                                                                $existing_rows = array(
                                                                                                    array('kategori' => 'Baku Pembanding', 'nama_zat' => 'Baku Pembanding'),
                                                                                                    array('kategori' => 'Pelarut', 'nama_zat' => 'Pelarut'),
                                                                                                    array('kategori' => 'Zat Uji', 'nama_zat' => 'Zat Uji'),
                                                                                                    array('kategori' => 'Spike', 'nama_zat' => 'Spike')
                                                                                                );
                                                                                            } else {
                                                                                                $existing_rows = $raw_val;
                                                                                            }
                                                                                        } else {
                                                                                            $existing_rows = !empty($raw_val) && is_array($raw_val)
                                                                                                ? $raw_val
                                                                                                : (isset($fld['default_rows']) ? $fld['default_rows'] : array(array()));
                                                                                        }
                                                                                    ?>"""

if target_rows in ps_content:
    ps_content = ps_content.replace(target_rows, replacement_rows, 1)
    print("Updated existing_rows with 4 default rows for Identifikasi in proses_sesi.php")
else:
    print("Target existing_rows not found in proses_sesi.php")

# Table input: support text for identifikasi
target_input = """                                                                                                    <input
                                                                                                            type="<?php echo $c_type; ?>"
                                                                                                            class="form-control form-control-sm"
                                                                                                            name="forms[<?php echo $f['id']; ?>][hasil][<?php echo $fld_id; ?>][<?php echo $r_idx; ?>][<?php echo $c_id; ?>]"
                                                                                                            value="<?php echo html_escape((string)$c_val); ?>"
                                                                                                        >"""

replacement_input = """                                                                                                    <input
                                                                                                            type="<?php echo $is_identifikasi ? 'text' : $c_type; ?>"
                                                                                                            class="form-control form-control-sm"
                                                                                                            name="forms[<?php echo $f['id']; ?>][hasil][<?php echo $fld_id; ?>][<?php echo $r_idx; ?>][<?php echo $c_id; ?>]"
                                                                                                            value="<?php echo html_escape((string)$c_val); ?>"
                                                                                                            placeholder="<?php echo $is_identifikasi ? 'Input teks/angka...' : ''; ?>"
                                                                                                        >"""

if target_input in ps_content:
    ps_content = ps_content.replace(target_input, replacement_input, 1)
    print("Updated table cell input to support text in proses_sesi.php")
else:
    print("Target table input not found in proses_sesi.php")

# Update JavaScript to handle identifikasi
target_js_metode = "function updateMetodeKadarUI(pane) {"
replacement_js_metode = """function updateMetodeKadarUI(pane) {
        if (!pane) return;
        var pName = (pane.getAttribute('data-parameter') || '').toLowerCase();
        var tName = (pane.getAttribute('data-nama-template') || '').toLowerCase();
        if (pName.indexOf('identifikasi') !== -1 || tName.indexOf('identifikasi') !== -1) {
            var mw = pane.querySelector('[data-field-wrapper="metode_perhitungan_kadar"]');
            if (mw) mw.style.display = 'none';
            return;
        }"""

if target_js_metode in ps_content and 'pName.indexOf(\'identifikasi\')' not in ps_content:
    ps_content = ps_content.replace(target_js_metode, replacement_js_metode, 1)
    print("Updated updateMetodeKadarUI in JS")

target_js_hplc = "function hitungHPLCPane(pane) {"
replacement_js_hplc = """function hitungHPLCPane(pane) {
        if (!pane) return;
        var pName = (pane.getAttribute('data-parameter') || '').toLowerCase();
        var tName = (pane.getAttribute('data-nama-template') || '').toLowerCase();
        if (pName.indexOf('identifikasi') !== -1 || tName.indexOf('identifikasi') !== -1) {
            return; // Skip hitung kadar untuk LCP Identifikasi
        }"""

if target_js_hplc in ps_content and 'Skip hitung kadar untuk LCP Identifikasi' not in ps_content:
    ps_content = ps_content.replace(target_js_hplc, replacement_js_hplc, 1)
    print("Updated hitungHPLCPane in JS")

target_js_spektro = "function hitungSpektrofotometriPane(pane) {"
replacement_js_spektro = """function hitungSpektrofotometriPane(pane) {
        if (!pane) return;
        var pName = (pane.getAttribute('data-parameter') || '').toLowerCase();
        var tName = (pane.getAttribute('data-nama-template') || '').toLowerCase();
        if (pName.indexOf('identifikasi') !== -1 || tName.indexOf('identifikasi') !== -1) {
            return; // Skip hitung kadar untuk LCP Identifikasi
        }"""

if target_js_spektro in ps_content and 'Skip hitung kadar untuk LCP Identifikasi' not in ps_content:
    ps_content = ps_content.replace(target_js_spektro, replacement_js_spektro, 1)
    print("Updated hitungSpektrofotometriPane in JS")

with open('application/views/pengujian/proses_sesi.php', 'w', encoding='utf-8') as f:
    f.write(ps_content)

print("proses_sesi.php saved successfully!")

