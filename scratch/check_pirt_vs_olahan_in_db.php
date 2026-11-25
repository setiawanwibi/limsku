<?php
define('BASEPATH', 1);
define('ENVIRONMENT', 'development');
require_once __DIR__ . '/../application/config/database.php';
$cfg = $db['default'];
$mysqli = new mysqli($cfg['hostname'], $cfg['username'], $cfg['password'], $cfg['database']);

$res = $mysqli->query("SELECT * FROM methods WHERE komoditi = 'pangan' AND (jenis_sediaan LIKE '%Hasil Olahan Daging%' OR bentuk_sediaan LIKE '%Abon Daging%') LIMIT 5");
echo "PIRT rows in methods:\n";
while ($r = $res->fetch_assoc()) {
    echo "ID: {$r['id']} | Zat: {$r['nama_zat_aktif']} | Sediaan: {$r['jenis_sediaan']} | Bentuk: " . substr($r['bentuk_sediaan'], 0, 40) . " | Pustaka: {$r['pustaka_acuan']}\n";
}

$res2 = $mysqli->query("SELECT * FROM methods WHERE komoditi = 'pangan' AND jenis_sediaan LIKE '%0.1 Produk-produk susu%' LIMIT 5");
echo "\nPangan Olahan rows in methods:\n";
while ($r = $res2->fetch_assoc()) {
    echo "ID: {$r['id']} | Zat: {$r['nama_zat_aktif']} | Sediaan: " . substr($r['jenis_sediaan'], 0, 40) . " | Bentuk: " . substr($r['bentuk_sediaan'], 0, 40) . "\n";
}
