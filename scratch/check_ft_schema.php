<?php
define('BASEPATH', 'TRUE');
define('ENVIRONMENT', 'development');
require_once __DIR__ . '/../application/config/database.php';

$db_cfg = $db['default'];
$mysqli = @new mysqli($db_cfg['hostname'], $db_cfg['username'], $db_cfg['password'], $db_cfg['database']);
if ($mysqli->connect_error) {
    die("DB error: " . $mysqli->connect_error);
}

echo "=== SHOW COLUMNS FROM form_templates ===\n";
$res = $mysqli->query("SHOW COLUMNS FROM form_templates");
while ($r = $res->fetch_assoc()) {
    echo $r['Field'] . " | " . $r['Type'] . " | Null:" . $r['Null'] . " | Key:" . $r['Key'] . " | Default:" . ($r['Default'] ?? 'NULL') . "\n";
}

echo "\n=== SHOW CREATE TABLE form_templates ===\n";
$res = $mysqli->query("SHOW CREATE TABLE form_templates");
$row = $res->fetch_assoc();
echo $row['Create Table'] . "\n";

$mysqli->close();
