<?php
define('BASEPATH', 'defined');
define('ENVIRONMENT', 'development');

require_once __DIR__ . '/../application/config/database.php';
$db_config = $db['default'];

$mysqli = new mysqli($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);

echo "=== TESTING SESSIONS COLUMNS ===\n";
$res = $mysqli->query("SHOW COLUMNS FROM testing_sessions");
while ($r = $res->fetch_assoc()) echo $r['Field'] . " (" . $r['Type'] . ")\n";

echo "=== TEST RESULTS COLUMNS ===\n";
$res = $mysqli->query("SHOW COLUMNS FROM test_results");
while ($r = $res->fetch_assoc()) echo $r['Field'] . " (" . $r['Type'] . ")\n";

echo "=== SAMPLES WITH MIKRO ===\n";
$res = $mysqli->query("SELECT id, kode_sampel_manual, nama_sampel, status, jumlah_mikro FROM samples WHERE jumlah_mikro IS NOT NULL AND jumlah_mikro != '' AND jumlah_mikro != '0' LIMIT 5");
while ($r = $res->fetch_assoc()) {
    echo "ID: {$r['id']}, Kode: {$r['kode_sampel_manual']}, Nama: {$r['nama_sampel']}, Status: {$r['status']}, JmlMikro: {$r['jumlah_mikro']}\n";
}

$mysqli->close();
