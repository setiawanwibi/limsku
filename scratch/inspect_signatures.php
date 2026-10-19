<?php
define('BASEPATH', 'true');
define('ENVIRONMENT', 'development');
require 'application/config/database.php';
$cfg = $db['default'];
$m = new mysqli($cfg['hostname'], $cfg['username'], $cfg['password'], $cfg['database']);
if ($m->connect_error) { echo "ERR: " . $m->connect_error; exit(1); }

echo "=== USER SIGNATURES STATS ===" . PHP_EOL;
$res = $m->query("SELECT COUNT(*) as total FROM user_signatures");
$row = $res->fetch_assoc();
echo "Total signatures in DB: " . $row['total'] . PHP_EOL;

$base_dir = dirname(__DIR__) . DIRECTORY_SEPARATOR;
$res2 = $m->query("SELECT us.user_id, u.username, r.nama_role, us.signature_file FROM user_signatures us JOIN users u ON u.id = us.user_id LEFT JOIN roles r ON r.id = u.role_id");
while ($r = $res2->fetch_assoc()) {
    $full = $base_dir . $r['signature_file'];
    $exists = file_exists($full) ? 'FILE_OK' : 'FILE_MISSING';
    $is_img = 'NOT_IMG';
    if ($exists === 'FILE_OK') {
        $info = @getimagesize($full);
        $is_img = ($info !== false && in_array($info[2], [IMAGETYPE_PNG, IMAGETYPE_JPEG])) ? 'VALID_IMAGE (' . $info['mime'] . ')' : 'INVALID_IMAGE';
    }
    echo "User ID " . $r['user_id'] . " (" . $r['nama_role'] . "): " . $exists . " | " . $is_img . PHP_EOL;
}

echo PHP_EOL . "=== USERS RELEVANT TO TESTING (ROLES) ===" . PHP_EOL;
$res3 = $m->query("SELECT r.nama_role, COUNT(u.id) as user_count FROM users u JOIN roles r ON r.id = u.role_id GROUP BY r.nama_role");
while ($r = $res3->fetch_assoc()) {
    echo $r['nama_role'] . ": " . $r['user_count'] . " users" . PHP_EOL;
}
