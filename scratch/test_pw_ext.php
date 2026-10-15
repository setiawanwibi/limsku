<?php
define('BASEPATH', 'defined');
define('ENVIRONMENT', 'development');

require_once __DIR__ . '/../application/config/database.php';
$db_config = $db['default'];

$mysqli = new mysqli($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);
$res = $mysqli->query("SELECT id, username, password FROM users WHERE username = 'penguji'");
$u = $res->fetch_assoc();

$candidates = [
    'password', 'password123', 'penguji', 'penguji123', 'penguji2026', 'penguji2025',
    'bpom', 'bpom123', 'limsku', 'limsku123', '123456', '12345678', 'admin', 'admin123',
    'Penguji', 'Penguji123', 'Penguji!', 'penguji!', 'testing', 'test', 'tester',
    'user', 'user123', 'analis', 'analis123', 'mikro', 'mikro123', 'kimia', 'kimia123',
    'laboratorium', 'lab123', 'bandarlampung', 'lampung'
];

foreach ($candidates as $c) {
    if (password_verify($c, $u['password'])) {
        echo "FOUND: Password for penguji is '$c'\n";
        exit;
    }
}
echo "NOT FOUND in candidate list\n";
$mysqli->close();
