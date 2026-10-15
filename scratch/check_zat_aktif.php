<?php
define('BASEPATH', 'defined');
define('ENVIRONMENT', 'development');

require_once __DIR__ . '/../application/config/database.php';
$db_config = $db['default'];

$mysqli = new mysqli($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);
$res = $mysqli->query("SELECT DISTINCT nama_zat_aktif FROM methods WHERE bidang_pengujian='Mikrobiologi' OR kategori='Mikrobiologi' ORDER BY nama_zat_aktif ASC LIMIT 10");

echo "ZAT AKTIF MIKROBIOLOGI:\n";
while ($r = $res->fetch_assoc()) {
    echo "- " . $r['nama_zat_aktif'] . "\n";
}
$mysqli->close();
