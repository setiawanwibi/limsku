<?php
define('BASEPATH', 'defined');
define('ENVIRONMENT', 'development');

require_once __DIR__ . '/../application/config/database.php';
$db_config = $db['default'];

$mysqli = new mysqli($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);
$res = $mysqli->query("SELECT id, username, password FROM users");
$candidates = ['password', 'admin', 'admin123', 'penguji', 'penguji123', '123456', 'limsku', 'limsku123', 'petugas', 'petugas123'];

while ($u = $res->fetch_assoc()) {
    foreach ($candidates as $cand) {
        if (password_verify($cand, $u['password'])) {
            echo "MATCH: User '{$u['username']}' uses password '{$cand}'\n";
            break;
        }
    }
}
$mysqli->close();
