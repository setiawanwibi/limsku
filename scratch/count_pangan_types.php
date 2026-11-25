<?php
define('BASEPATH', 1);
define('ENVIRONMENT', 'development');
require_once __DIR__ . '/../application/config/database.php';
$cfg = $db['default'];
$mysqli = new mysqli($cfg['hostname'], $cfg['username'], $cfg['password'], $cfg['database']);

$q1 = $mysqli->query("SELECT COUNT(*) as c FROM methods WHERE komoditi = 'pangan' AND jenis_sediaan LIKE '[PIRT%'")->fetch_assoc()['c'];
$q2 = $mysqli->query("SELECT COUNT(*) as c FROM methods WHERE komoditi = 'pangan' AND (jenis_sediaan LIKE '[0%' OR jenis_sediaan LIKE '[1%') AND jenis_sediaan NOT LIKE '[PIRT%'")->fetch_assoc()['c'];
$q3 = $mysqli->query("SELECT COUNT(*) as c FROM methods WHERE komoditi = 'pangan' AND jenis_sediaan NOT LIKE '[%'")->fetch_assoc()['c'];

echo "PIRT rows in methods: $q1\n";
echo "Pangan Olahan rows in methods: $q2\n";
echo "Lainnya/Manual rows in methods: $q3\n";
