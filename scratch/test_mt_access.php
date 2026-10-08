<?php
define('BASEPATH', 'TRUE');
define('ENVIRONMENT', 'development');
require_once 'application/config/database.php';

$db_config = $db['default'];
$mysqli = new mysqli($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

echo "=== VERIFY MANAJER TEKNIS ACCESS TO DETAIL_SESI & DETAIL ===\n\n";

// Fetch role permissions for Manajer Teknis (role_id = 5)
$res = $mysqli->query("SELECT p.nama_permission FROM role_permissions rp JOIN permissions p ON p.id = rp.permission_id WHERE rp.role_id = 5");
$mt_perms = [];
while ($row = $res->fetch_assoc()) {
    $mt_perms[] = $row['nama_permission'];
}

echo "Role 'Manajer Teknis' (role_id = 5) Permissions:\n";
foreach ($mt_perms as $p) {
    echo "  - $p\n";
}

echo "\n[CHECK 1] Access to detail_sesi (requires 'laporan_view'):\n";
if (in_array('laporan_view', $mt_perms)) {
    echo "  -> PASS: Manajer Teknis HAS 'laporan_view'. Access GRANTED. No redirect to dasbor.\n";
} else {
    echo "  -> FAIL: Manajer Teknis DOES NOT HAVE 'laporan_view'.\n";
}

echo "\n[CHECK 2] Access to approve_sesi (requires 'pengujian_approve'):\n";
if (in_array('pengujian_approve', $mt_perms)) {
    echo "  -> PASS: Manajer Teknis HAS 'pengujian_approve'. Approval GRANTED.\n";
} else {
    echo "  -> FAIL: Manajer Teknis DOES NOT HAVE 'pengujian_approve'.\n";
}

$mysqli->close();
