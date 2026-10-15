<?php
define('BASEPATH', 'defined');
define('ENVIRONMENT', 'development');

require_once __DIR__ . '/../application/config/database.php';
$db_config = $db['default'];

$mysqli = new mysqli($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);

echo "=== SAMPLES COLUMNS ===\n";
$res = $mysqli->query("SHOW COLUMNS FROM samples");
while ($r = $res->fetch_assoc()) echo $r['Field'] . " (" . $r['Type'] . ")\n";

echo "=== METHODS COLUMNS ===\n";
$res = $mysqli->query("SHOW COLUMNS FROM methods");
while ($r = $res->fetch_assoc()) echo $r['Field'] . " (" . $r['Type'] . ")\n";

echo "=== USERS COLUMNS ===\n";
$res = $mysqli->query("SHOW COLUMNS FROM users");
while ($r = $res->fetch_assoc()) echo $r['Field'] . " (" . $r['Type'] . ")\n";

$mysqli->close();
