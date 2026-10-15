<?php
define('BASEPATH', 'defined');
define('ENVIRONMENT', 'development');

require_once __DIR__ . '/../application/config/database.php';
$db_config = $db['default'];

$mysqli = new mysqli($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);
if ($mysqli->connect_error) {
    die("Connect Error: " . $mysqli->connect_error . "\n");
}

echo "=== REAGENTS TABLE STRUCTURE ===\n";
$res = $mysqli->query("SHOW COLUMNS FROM reagents");
while ($r = $res->fetch_assoc()) {
    echo "{$r['Field']} | {$r['Type']} | Null: {$r['Null']} | Default: {$r['Default']} | Extra: {$r['Extra']}\n";
}

echo "\n=== REAGENTS DATA COUNT & SAMPLE ===\n";
$cnt = $mysqli->query("SELECT COUNT(*) as total FROM reagents")->fetch_assoc();
echo "Total rows in reagents: {$cnt['total']}\n";

$res = $mysqli->query("SELECT * FROM reagents LIMIT 5");
while ($r = $res->fetch_assoc()) {
    print_r($r);
}

$mysqli->close();
