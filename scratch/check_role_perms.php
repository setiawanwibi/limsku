<?php
define('BASEPATH', 'TRUE');
define('ENVIRONMENT', 'development');
require_once 'application/config/database.php';

$db_config = $db['default'];
$mysqli = new mysqli($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);

echo "=== PERMISSIONS FOR MANAJER TEKNIS & OTHER ROLES ===\n\n";

$res = $mysqli->query("SELECT r.nama_role, p.nama_permission FROM role_permissions rp JOIN roles r ON r.id = rp.role_id JOIN permissions p ON p.id = rp.permission_id ORDER BY r.nama_role, p.nama_permission");
$perms = [];
while ($row = $res->fetch_assoc()) {
    $perms[$row['nama_role']][] = $row['nama_permission'];
}

foreach ($perms as $role => $plist) {
    echo "Role: '$role'\n";
    foreach ($plist as $p) {
        echo "  - $p\n";
    }
    echo "\n";
}

$mysqli->close();
