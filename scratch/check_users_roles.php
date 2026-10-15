<?php
define('BASEPATH', 'defined');
define('ENVIRONMENT', 'development');

require_once __DIR__ . '/../application/config/database.php';
$db_config = $db['default'];

$mysqli = new mysqli($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);
if ($mysqli->connect_error) {
    die("Connect Error: " . $mysqli->connect_error . "\n");
}

echo "=== CHECK TESTING ROLES & USERS ===\n";
$res = $mysqli->query("SELECT u.id, u.username, u.nama_lengkap, r.nama_role FROM users u JOIN roles r ON u.role_id = r.id LIMIT 10");
while ($r = $res->fetch_assoc()) {
    echo "User {$r['username']} (ID: {$r['id']}, Nama: {$r['nama_lengkap']}, Role: {$r['nama_role']})\n";
}

$mysqli->close();
