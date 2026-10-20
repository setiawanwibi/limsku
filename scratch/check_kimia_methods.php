<?php
define('BASEPATH', 'TRUE');
define('ENVIRONMENT', 'development');
require_once 'application/config/database.php';
$db_config = $db['default'];
$mysqli = new mysqli($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);

echo "=== CHECK KIMIA METHODS & PARAMETERS ===\n";
$q = $mysqli->query("SELECT id, kode_metode, nama_zat_aktif, parameter_uji, metode_analisa, persyaratan, pustaka_acuan FROM methods WHERE parameter_uji LIKE '%Identifikasi%' OR metode_analisa LIKE '%Reaksi%' OR parameter_uji LIKE '%Reaksi%' OR parameter_uji LIKE '%Warna%' OR parameter_uji LIKE '%Pengendapan%' LIMIT 30");
while ($r = $q->fetch_assoc()) {
    echo "ID: {$r['id']} | Zat: {$r['nama_zat_aktif']} | Param: {$r['parameter_uji']} | Analisa: {$r['metode_analisa']}\n";
}

echo "\n=== ALL KIMIA PARAMETERS (DISTINCT) ===\n";
$q2 = $mysqli->query("SELECT DISTINCT parameter_uji, metode_analisa FROM methods ORDER BY parameter_uji");
while ($r2 = $q2->fetch_assoc()) {
    echo "Param: {$r2['parameter_uji']} | Analisa: {$r2['metode_analisa']}\n";
}
