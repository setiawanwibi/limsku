<?php
define('BASEPATH', 'defined');
define('ENVIRONMENT', 'development');

require_once __DIR__ . '/../application/config/database.php';
$db_config = $db['default'];

$mysqli = new mysqli($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);
$res = $mysqli->query("SELECT p.nama_permission FROM permissions p JOIN role_permissions rp ON p.id = rp.permission_id JOIN users u ON u.role_id = rp.role_id WHERE u.username = 'admin'");
echo "ADMIN PERMISSIONS:\n";
while ($r = $res->fetch_assoc()) {
    echo "- " . $r['nama_permission'] . "\n";
}
$mysqli->close();
