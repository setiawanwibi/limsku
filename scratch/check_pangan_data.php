<?php
define('BASEPATH', 1);
define('ENVIRONMENT', 'development');
require_once __DIR__ . '/../application/config/database.php';
$cfg = $db['default'];
$mysqli = new mysqli($cfg['hostname'], $cfg['username'], $cfg['password'], $cfg['database']);

echo "Total methods pangan: ";
$r = $mysqli->query("SELECT COUNT(*) as c FROM methods WHERE komoditi = 'pangan'")->fetch_assoc();
echo $r['c'] . "\n";

echo "Distinct versi/keterangan/jenis_sediaan:\n";
$res = $mysqli->query("SELECT versi, keterangan, COUNT(*) as c FROM methods WHERE komoditi = 'pangan' GROUP BY versi, keterangan");
while ($r = $res->fetch_assoc()) {
    echo "versi: " . json_encode($r['versi']) . " | ket: " . json_encode($r['keterangan']) . " => " . $r['c'] . "\n";
}

echo "\nCheck if any PIRT or Pangan Olahan tags exist:\n";
$res2 = $mysqli->query("SELECT id, kode_metode, nama_metode, nama_zat_aktif, jenis_sediaan, bentuk_sediaan, pustaka_acuan, parameter_uji, metode_analisa, persyaratan, bidang_pengujian, versi, keterangan FROM methods WHERE komoditi = 'pangan' LIMIT 10");
while ($r = $res2->fetch_assoc()) {
    echo json_encode($r, JSON_UNESCAPED_UNICODE) . "\n";
}
