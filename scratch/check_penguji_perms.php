<?php
define('BASEPATH', 'defined');
define('ENVIRONMENT', 'development');

require_once __DIR__ . '/../application/config/database.php';
$db_config = $db['default'];

$mysqli = new mysqli($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);
$res = $mysqli->query("SELECT u.username, p.nama_permission FROM users u JOIN role_permissions rp ON u.role_id = rp.role_id JOIN permissions p ON p.id = rp.permission_id WHERE p.nama_permission LIKE 'pengujian%'");
echo "USERS WITH PENGUJIAN PERMISSIONS:\n";
while ($r = $res->fetch_assoc()) {
    echo "{$r['username']} -> {$r['nama_permission']}\n";
}
$mysqli->close();
