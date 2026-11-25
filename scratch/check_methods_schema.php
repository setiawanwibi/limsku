<?php
define('BASEPATH', 1);
define('ENVIRONMENT', 'development');
require_once __DIR__ . '/../application/config/database.php';
$cfg = $db['default'];
$mysqli = new mysqli($cfg['hostname'], $cfg['username'], $cfg['password'], $cfg['database']);

echo "=== COLUMNS IN methods ===\n";
$res = $mysqli->query("DESCRIBE methods");
while ($r = $res->fetch_assoc()) {
    echo "{$r['Field']} | {$r['Type']} | Null: {$r['Null']} | Default: {$r['Default']}\n";
}

echo "\n=== ALL TABLES IN DATABASE ===\n";
$res2 = $mysqli->query("SHOW TABLES");
while ($r = $res2->fetch_array()) {
    echo $r[0] . "\n";
}
