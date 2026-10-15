<?php
define('BASEPATH', 'defined');
define('ENVIRONMENT', 'development');

require_once __DIR__ . '/../application/config/database.php';
$db_config = $db['default'];

$mysqli = new mysqli($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);
if ($mysqli->connect_error) {
    die("Connect Error: " . $mysqli->connect_error . "\n");
}

$tables = $mysqli->query("SHOW TABLES");
echo "TABLES IN " . $db_config['database'] . ":\n";
while ($t = $tables->fetch_row()) {
    echo "- " . $t[0] . "\n";
}

$mysqli->close();
