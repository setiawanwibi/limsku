<?php
define('BASEPATH', 1);
define('ENVIRONMENT', 'development');
require_once __DIR__ . '/../application/config/database.php';
$cfg = $db['default'];
$mysqli = new mysqli($cfg['hostname'], $cfg['username'], $cfg['password'], $cfg['database']);

$res = $mysqli->query("SELECT MIN(id) as min_id, MAX(id) as max_id, COUNT(*) as c FROM methods WHERE komoditi = 'pangan'");
$r = $res->fetch_assoc();
echo "Pangan IDs: min {$r['min_id']} to max {$r['max_id']}, total {$r['c']}\n";

$res2 = $mysqli->query("SELECT id, kode_metode, nama_metode, nama_zat_aktif, jenis_sediaan, parameter_uji, bidang_pengujian FROM methods WHERE komoditi = 'pangan' ORDER BY id ASC LIMIT 10");
echo "\nFirst 10 pangan rows:\n";
while ($row = $res2->fetch_assoc()) {
    echo "ID: {$row['id']} | Kode: {$row['kode_metode']} | Zat: {$row['nama_zat_aktif']} | Sediaan: {$row['jenis_sediaan']} | Param: {$row['parameter_uji']}\n";
}

$res3 = $mysqli->query("SELECT id, kode_metode, nama_metode, nama_zat_aktif, jenis_sediaan, parameter_uji, bidang_pengujian FROM methods WHERE komoditi = 'pangan' ORDER BY id DESC LIMIT 10");
echo "\nLast 10 pangan rows:\n";
while ($row = $res3->fetch_assoc()) {
    echo "ID: {$row['id']} | Kode: {$row['kode_metode']} | Zat: {$row['nama_zat_aktif']} | Sediaan: {$row['jenis_sediaan']} | Param: {$row['parameter_uji']}\n";
}
