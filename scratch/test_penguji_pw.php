<?php
define('BASEPATH', 'defined');
define('ENVIRONMENT', 'development');

require_once __DIR__ . '/../application/config/database.php';
$db_config = $db['default'];

$mysqli = new mysqli($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);
$res = $mysqli->query("SELECT id, username, password FROM users WHERE username IN ('penguji', 'penyelia', 'manajer')");

$candidates = ['penguji', 'penguji123', 'penyelia', 'penyelia123', 'manajer', 'manajer123', 'admin', 'admin123', '123456', 'limsku', 'password'];

while ($u = $res->fetch_assoc()) {
    $found = false;
    foreach ($candidates as $c) {
        if (password_verify($c, $u['password'])) {
            echo "MATCH: User '{$u['username']}' -> password: '{$c}'\n";
            $found = true;
            break;
        }
    }
    if (!$found) echo "NO MATCH for {$u['username']}\n";
}
$mysqli->close();
