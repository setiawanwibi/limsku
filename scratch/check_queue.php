<?php
define('BASEPATH', 'defined');
define('ENVIRONMENT', 'development');

require_once __DIR__ . '/../application/config/database.php';
$db_config = $db['default'];

$mysqli = new mysqli($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);
$res = $mysqli->query("SELECT id, kode_sampel_manual, nama_sampel, status, jumlah_mikro FROM samples WHERE status = 'Menunggu Pengujian'");

echo "SAMPLES WITH 'Menunggu Pengujian':\n";
while ($r = $res->fetch_assoc()) {
    echo "ID: {$r['id']}, Kode: {$r['kode_sampel_manual']}, Nama: {$r['nama_sampel']}, JmlMikro: {$r['jumlah_mikro']}\n";
}
$mysqli->close();
