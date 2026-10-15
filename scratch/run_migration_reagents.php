<?php
define('BASEPATH', 'defined');
define('ENVIRONMENT', 'development');

require_once __DIR__ . '/../application/config/database.php';
$db_config = $db['default'];

$mysqli = new mysqli($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);
if ($mysqli->connect_error) {
    die("Connect Error: " . $mysqli->connect_error . "\n");
}

$sql = file_get_contents(__DIR__ . '/../database/migration_reagents_v2.sql');
$mysqli->multi_query($sql);

do {
    if ($result = $mysqli->store_result()) {
        $result->free();
    }
} while ($mysqli->next_result());

if ($mysqli->errno) {
    echo "SQL Migration Error: " . $mysqli->error . "\n";
} else {
    echo "SQL MIGRATION SUCCESSFUL!\n";
}

echo "\n=== REAGENTS COLUMNS AFTER MIGRATION ===\n";
$res = $mysqli->query("SHOW COLUMNS FROM reagents");
while ($r = $res->fetch_assoc()) {
    echo "{$r['Field']} | {$r['Type']} | Null: {$r['Null']} | Default: {$r['Default']}\n";
}

echo "\n=== EXISTING REAGENTS DATA ===\n";
$res = $mysqli->query("SELECT * FROM reagents");
while ($r = $res->fetch_assoc()) {
    print_r($r);
}

$mysqli->close();
