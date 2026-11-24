<?php
define('ENVIRONMENT', 'development');
require_once __DIR__ . '/../index.php';
$CI =& get_instance();

echo "========================================================\n";
echo "AUDIT NOTULENSI MIKROBIOLOGI 09 NOVEMBER 2026\n";
echo "========================================================\n\n";

// POIN 1 & 2: LOGBOOK ALAT
echo "--- POIN 1 & 2: LOGBOOK ALAT ---\n";
$tables = $CI->db->list_tables();
echo "Table instrument_logbooks exists: " . (in_array('instrument_logbooks', $tables) ? 'YES' : 'NO') . "\n";
$fields = $CI->db->field_data('instrument_logbooks');
$fnames = array_map(function($f) { return $f->name; }, $fields);
echo "Columns in instrument_logbooks: " . implode(', ', $fnames) . "\n";
$sample_log = $CI->db->select('il.*, i.nama_barang, s.nama_sampel, u.nama_lengkap')
    ->from('instrument_logbooks il')
    ->join('instruments i', 'i.id = il.instrument_id', 'left')
    ->join('samples s', 's.id = il.sample_id', 'left')
    ->join('users u', 'u.id = il.user_id', 'left')
    ->limit(3)->get()->result_array();
echo "Sample log count: " . count($sample_log) . "\n";
foreach ($sample_log as $sl) {
    echo "  - Alat: {$sl['nama_barang']} | Sampel: {$sl['nama_sampel']} | Tgl: {$sl['tanggal_penggunaan']} | Pengguna: {$sl['nama_lengkap']}\n";
}

// POIN 3: PERSYARATAN E. COLI, STAPHYLOCOCCUS, PSEUDOMONAS
echo "\n--- POIN 3: PERSYARATAN E. COLI, STAPHYLOCOCCUS, PSEUDOMONAS ---\n";
$p3_res = $CI->db->query("
    SELECT id, parameter_uji, bentuk_sediaan, spesifikasi, pustaka, jenis_pengujian 
    FROM methods 
    WHERE (parameter_uji LIKE '%coli%' OR parameter_uji LIKE '%staph%' OR parameter_uji LIKE '%pseudo%')
")->result_array();
echo "Found " . count($p3_res) . " matching methods:\n";
foreach ($p3_res as $r) {
    echo "  [ID {$r['id']}] Param: {$r['parameter_uji']} | Sediaan: {$r['bentuk_sediaan']} | Syarat: {$r['spesifikasi']} | Pustaka: {$r['pustaka']}\n";
}

// POIN 4: DATABASE ZAT AKTIF & KELAS TERAPI MIKROBIOLOGI VS KIMIA
echo "\n--- POIN 4: DATABASE ZAT AKTIF & KELAS TERAPI MIKROBIOLOGI VS KIMIA ---\n";
$methods_counts = $CI->db->query("SELECT jenis_pengujian, COUNT(*) as cnt FROM methods GROUP BY jenis_pengujian")->result_array();
print_r($methods_counts);

$substances_counts = $CI->db->query("SELECT COUNT(*) as total_substances FROM active_substances")->row_array();
echo "Total active substances: " . $substances_counts['total_substances'] . "\n";

// Check distinct active substances or therapy classes in methods
$kimia_subs = $CI->db->query("SELECT DISTINCT zat_aktif FROM methods WHERE jenis_pengujian = 'Kimia' AND zat_aktif IS NOT NULL AND zat_aktif != ''")->result_array();
$mikro_subs = $CI->db->query("SELECT DISTINCT zat_aktif FROM methods WHERE jenis_pengujian = 'Mikrobiologi' AND zat_aktif IS NOT NULL AND zat_aktif != ''")->result_array();
echo "Distinct zat aktif in Kimia methods: " . count($kimia_subs) . "\n";
echo "Distinct zat aktif in Mikrobiologi methods: " . count($mikro_subs) . "\n";

// POIN 5: PUSTAKA ACUAN SUPLMEN 1 FI VI
echo "\n--- POIN 5: PUSTAKA ACUAN MIKROBIOLOGI ---\n";
$p5_res = $CI->db->query("
    SELECT DISTINCT pustaka, COUNT(*) as cnt 
    FROM methods 
    WHERE jenis_pengujian = 'Mikrobiologi' 
    GROUP BY pustaka
")->result_array();
echo "Distinct pustaka in Mikrobiologi:\n";
foreach ($p5_res as $p) {
    echo "  - '{$p['pustaka']}': {$p['cnt']} methods\n";
}

// POIN 6, 7, 8, 9, 10: SEDIAAN ORAL, TOPIKAL, REKTAL & SYARAT ALT, AKK
echo "\n--- POIN 6, 7, 8, 9, 10: PARAMETER SEDIAAN ORAL, TOPIKAL, REKTAL & PERSYARATAN ALT, AKK ---\n";
$sediaan_res = $CI->db->query("
    SELECT bentuk_sediaan, parameter_uji, spesifikasi, pustaka, COUNT(*) as cnt 
    FROM methods 
    WHERE jenis_pengujian = 'Mikrobiologi'
    GROUP BY bentuk_sediaan, parameter_uji, spesifikasi, pustaka
    ORDER BY bentuk_sediaan ASC, parameter_uji ASC
")->result_array();
echo "Methods Mikrobiologi by sediaan & parameter (" . count($sediaan_res) . " variations):\n";
foreach ($sediaan_res as $s) {
    echo "  - Sediaan: '{$s['bentuk_sediaan']}' | Param: '{$s['parameter_uji']}' | Syarat: '{$s['spesifikasi']}' | Pustaka: '{$s['pustaka']}' (Cnt: {$s['cnt']})\n";
}

// Check forms / categories
$sediaan_distinct = $CI->db->query("SELECT DISTINCT bentuk_sediaan FROM methods WHERE jenis_pengujian = 'Mikrobiologi'")->result_array();
echo "Distinct sediaan in Mikrobiologi methods: " . implode(', ', array_column($sediaan_distinct, 'bentuk_sediaan')) . "\n";

