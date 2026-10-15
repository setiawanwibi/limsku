<?php
define('BASEPATH', 'defined');
define('ENVIRONMENT', 'development');

require_once __DIR__ . '/../application/config/database.php';
$db_config = $db['default'];

$mysqli = new mysqli($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);

echo "=== RECORD DENGAN JENIS_BARANG BERUPA ANGKA (SERIAL TANGGAL EXCEL) ===\n";
$res = $mysqli->query("SELECT id, kode_barang, katalog, nama_barang, merk, stok, jenis_barang, expired_date FROM reagents WHERE jenis_barang REGEXP '^[0-9]+$'");
while ($r = $res->fetch_assoc()) {
    $serial = (int)$r['jenis_barang'];
    $timestamp = ($serial - 25569) * 86400;
    $date_converted = date('Y-m-d', $timestamp);
    echo "ID {$r['id']}:\n";
    echo "   Kode Barang : '{$r['kode_barang']}'\n";
    echo "   Katalog     : '{$r['katalog']}'\n";
    echo "   Nama Barang : '{$r['nama_barang']}'\n";
    echo "   Merk        : '{$r['merk']}'\n";
    echo "   Stok        : '{$r['stok']}'\n";
    echo "   Jenis Barang: '{$r['jenis_barang']}' (Konversi Date: {$date_converted})\n";
    echo "   Expired Date: " . var_export($r['expired_date'], true) . "\n\n";
}

$mysqli->close();
