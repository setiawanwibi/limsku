<?php
define('BASEPATH', 'TRUE');
define('ENVIRONMENT', 'development');
require_once 'application/config/database.php';

$db_config = $db['default'];
$mysqli = new mysqli($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);
if ($mysqli->connect_error) {
    die("Connect error: " . $mysqli->connect_error);
}

echo "=== TABEL form_templates AKTUAL ===\n";
$res = $mysqli->query("SELECT id, kode_template, nama_template, kategori, status FROM form_templates ORDER BY id");
while ($r = $res->fetch_assoc()) {
    echo sprintf("ID: %2d | Kode: %-10s | Kategori: %-12s | Status: %-8s | Nama: %s\n", 
        $r['id'], $r['kode_template'], $r['kategori'], $r['status'], $r['nama_template']);
}

// Cek apakah ada tabel template lain atau migration
echo "\n=== MIGRATION FILES / SQL TERKAIT TEMPLATE ===\n";
$tables = $mysqli->query("SHOW TABLES LIKE '%template%'");
while ($t = $tables->fetch_array()) {
    echo "Table: " . $t[0] . "\n";
}

// Cek apakah ada test_results yang menggunakan template_id tertentu
echo "\n=== PENGGUNAAN template_id PADA test_results ===\n";
$tr_use = $mysqli->query("SELECT template_id, COUNT(*) as cnt FROM test_results GROUP BY template_id");
while ($tu = $tr_use->fetch_assoc()) {
    echo "Template ID: " . ($tu['template_id'] ?: 'NULL') . " -> Used: " . $tu['cnt'] . " times\n";
}

$mysqli->close();
