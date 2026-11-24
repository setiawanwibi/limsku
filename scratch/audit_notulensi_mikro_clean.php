<?php
$pdo = new PDO('mysql:host=localhost;dbname=limsku;charset=utf8mb4', 'root', '', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
]);

echo "========================================================\n";
echo "AUDIT NOTULENSI MIKROBIOLOGI 09 NOVEMBER 2026\n";
echo "========================================================\n\n";

// POIN 1 & 2: LOGBOOK ALAT
echo "--- POIN 1 & 2: LOGBOOK ALAT ---\n";
$stmt = $pdo->query("
    SELECT il.id, i.nama_barang as nama_alat, i.kode_barang, s.nama_sampel, s.kode_sampel_manual, il.tanggal_penggunaan, u.nama_lengkap as penguji
    FROM instrument_logbooks il
    LEFT JOIN instruments i ON i.id = il.instrument_id
    LEFT JOIN samples s ON s.id = il.sample_id
    LEFT JOIN users u ON u.id = il.user_id
    ORDER BY il.id DESC
    LIMIT 3
");
$logs = $stmt->fetchAll();
foreach ($logs as $l) {
    echo "  - Alat: {$l['nama_alat']} ({$l['kode_barang']}) | Sampel: {$l['nama_sampel']} | Tgl: {$l['tanggal_penggunaan']} | Pengguna: {$l['penguji']}\n";
}

// POIN 3: PERSYARATAN E. COLI, STAPHYLOCOCCUS, PSEUDOMONAS
echo "\n--- POIN 3: PERSYARATAN E. COLI, STAPHYLOCOCCUS, PSEUDOMONAS ---\n";
$stmt = $pdo->query("
    SELECT id, parameter_uji, bentuk_sediaan, persyaratan, pustaka_acuan, bidang_pengujian 
    FROM methods 
    WHERE (parameter_uji LIKE '%coli%' OR parameter_uji LIKE '%staph%' OR parameter_uji LIKE '%pseudo%')
");
$p3 = $stmt->fetchAll();
echo "Found " . count($p3) . " methods for E. coli / Staph / Pseudo:\n";
foreach ($p3 as $r) {
    echo "  [ID {$r['id']}] Param: '{$r['parameter_uji']}' | Sediaan: '{$r['bentuk_sediaan']}' | Syarat: '{$r['persyaratan']}' | Pustaka: '{$r['pustaka_acuan']}' | Bidang: '{$r['bidang_pengujian']}'\n";
}

// POIN 4: DATABASE ZAT AKTIF & KELAS TERAPI MIKROBIOLOGI VS KIMIA
echo "\n--- POIN 4: DATABASE ZAT AKTIF & KELAS TERAPI ---\n";
$stmt = $pdo->query("SELECT bidang_pengujian, COUNT(*) as cnt FROM methods GROUP BY bidang_pengujian");
echo "Methods count by bidang_pengujian:\n";
foreach ($stmt->fetchAll() as $r) {
    echo "  - {$r['bidang_pengujian']}: {$r['cnt']}\n";
}

$stmt = $pdo->query("SELECT DISTINCT TRIM(nama_zat_aktif) as za FROM methods WHERE bidang_pengujian = 'Kimia' AND nama_zat_aktif IS NOT NULL AND nama_zat_aktif != ''");
$kimia_za = $stmt->fetchAll(PDO::FETCH_COLUMN);
$stmt = $pdo->query("SELECT DISTINCT TRIM(nama_zat_aktif) as za FROM methods WHERE bidang_pengujian = 'Mikrobiologi' AND nama_zat_aktif IS NOT NULL AND nama_zat_aktif != ''");
$mikro_za = $stmt->fetchAll(PDO::FETCH_COLUMN);
echo "Unique zat aktif in Kimia: " . count($kimia_za) . "\n";
echo "Unique zat aktif in Mikrobiologi: " . count($mikro_za) . "\n";

$missing_in_mikro = array_diff($kimia_za, $mikro_za);
echo "Zat aktif in Kimia but missing in Mikrobiologi: " . count($missing_in_mikro) . "\n";
if (count($missing_in_mikro) > 0) {
    echo "  Sample missing (up to 10): " . implode(', ', array_slice($missing_in_mikro, 0, 10)) . "\n";
}

// Also check therapy classes
$stmt = $pdo->query("SELECT COUNT(DISTINCT kelas_terapi_id) FROM methods WHERE bidang_pengujian = 'Kimia'");
echo "Unique kelas terapi in Kimia: " . $stmt->fetchColumn() . "\n";
$stmt = $pdo->query("SELECT COUNT(DISTINCT kelas_terapi_id) FROM methods WHERE bidang_pengujian = 'Mikrobiologi'");
echo "Unique kelas terapi in Mikrobiologi: " . $stmt->fetchColumn() . "\n";

// POIN 5: PUSTAKA ACUAN MIKROBIOLOGI
echo "\n--- POIN 5: PUSTAKA ACUAN MIKROBIOLOGI ---\n";
$stmt = $pdo->query("
    SELECT pustaka_acuan, COUNT(*) as cnt 
    FROM methods 
    WHERE bidang_pengujian = 'Mikrobiologi' 
    GROUP BY pustaka_acuan
");
$pustakas = $stmt->fetchAll();
echo "Distinct pustaka_acuan in Mikrobiologi:\n";
foreach ($pustakas as $p) {
    echo "  - '{$p['pustaka_acuan']}': {$p['cnt']} methods\n";
}

// POIN 6, 7, 8, 9, 10: PARAMETER SEDIAAN ORAL, TOPIKAL, REKTAL & SYARAT ALT, AKK
echo "\n--- POIN 6, 7, 8, 9, 10: PARAMETER SEDIAAN & PERSYARATAN ALT, AKK ---\n";
$stmt = $pdo->query("
    SELECT bentuk_sediaan, jenis_sediaan, parameter_uji, persyaratan, pustaka_acuan, COUNT(*) as cnt 
    FROM methods 
    WHERE bidang_pengujian = 'Mikrobiologi'
    GROUP BY bentuk_sediaan, jenis_sediaan, parameter_uji, persyaratan, pustaka_acuan
    ORDER BY jenis_sediaan ASC, bentuk_sediaan ASC, parameter_uji ASC
");
$sediaans = $stmt->fetchAll();
foreach ($sediaans as $s) {
    echo "  - [{$s['jenis_sediaan']}] Sediaan: '{$s['bentuk_sediaan']}' | Param: '{$s['parameter_uji']}' | Syarat: '{$s['persyaratan']}' (Cnt: {$s['cnt']})\n";
}

// Check distinct jenis_sediaan and bentuk_sediaan
$stmt = $pdo->query("SELECT DISTINCT jenis_sediaan FROM methods WHERE bidang_pengujian = 'Mikrobiologi'");
echo "\nDistinct jenis_sediaan in Mikrobiologi: " . implode(', ', $stmt->fetchAll(PDO::FETCH_COLUMN)) . "\n";

$stmt = $pdo->query("SELECT DISTINCT bentuk_sediaan FROM methods WHERE bidang_pengujian = 'Mikrobiologi'");
echo "Distinct bentuk_sediaan in Mikrobiologi: " . implode(', ', $stmt->fetchAll(PDO::FETCH_COLUMN)) . "\n";

