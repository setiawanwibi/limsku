<?php
define('BASEPATH', 'defined');
define('ENVIRONMENT', 'development');

require_once __DIR__ . '/../application/config/database.php';
$db_config = $db['default'];

$mysqli = new mysqli($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);
$res = $mysqli->query("SELECT id, username, password FROM users");
while ($r = $res->fetch_assoc()) {
    echo "ID: {$r['id']}, User: {$r['username']}, Hash: {$r['password']}\n";
}
$mysqli->close();
