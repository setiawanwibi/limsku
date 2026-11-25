<?php
define('BASEPATH', 1);
define('ENVIRONMENT', 'development');
require_once __DIR__ . '/../application/config/database.php';
$cfg = $db['default'];
$mysqli = new mysqli($cfg['hostname'], $cfg['username'], $cfg['password'], $cfg['database']);

$res = $mysqli->query("SELECT komoditi, COUNT(*) as cnt FROM methods GROUP BY komoditi");
while ($r = $res->fetch_assoc()) {
    echo "Komoditi: {$r['komoditi']} => {$r['cnt']} rows\n";
}

$res2 = $mysqli->query("SELECT * FROM methods WHERE komoditi = 'pangan' LIMIT 5");
echo "\nSample pangan rows:\n";
while ($r = $res2->fetch_assoc()) {
    echo json_encode($r, JSON_UNESCAPED_UNICODE) . "\n";
}
