<?php
define('BASEPATH', 'defined');
define('ENVIRONMENT', 'development');

require_once __DIR__ . '/../application/config/database.php';
$db_config = $db['default'];

$mysqli = new mysqli($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);
if ($mysqli->connect_error) {
    die("Connect Error: " . $mysqli->connect_error . "\n");
}

echo "=== 1. CHECK SAMPLE & METHOD FOR TESTING ===\n";
// Find an existing sample or see sample list
$res = $mysqli->query("SELECT id, kode_sampel, nama_sampel, status FROM samples LIMIT 5");
while ($r = $res->fetch_assoc()) {
    echo "Sample: ID={$r['id']}, Kode={$r['kode_sampel']}, Nama={$r['nama_sampel']}, Status={$r['status']}\n";
}

$res = $mysqli->query("SELECT id, kode_metode, nama_metode, kategori, template_id FROM methods WHERE kategori='Mikrobiologi' LIMIT 5");
while ($r = $res->fetch_assoc()) {
    echo "Microbiology Method: ID={$r['id']}, Kode={$r['kode_metode']}, Nama={$r['nama_metode']}, TemplateID=" . ($r['template_id'] ?? 'NULL') . "\n";
}

// Check an existing user to use as tester
$res = $mysqli->query("SELECT id, username, nama_lengkap, role FROM users LIMIT 5");
while ($r = $res->fetch_assoc()) {
    echo "User: ID={$r['id']}, User={$r['username']}, Role={$r['role']}\n";
}

$mysqli->close();
