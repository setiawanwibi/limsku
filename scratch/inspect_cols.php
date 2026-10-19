<?php
define('BASEPATH', 'true');
define('ENVIRONMENT', 'development');
require 'application/config/database.php';
$cfg = $db['default'];
$m = new mysqli($cfg['hostname'], $cfg['username'], $cfg['password'], $cfg['database']);
if ($m->connect_error) { echo 'ERR: ' . $m->connect_error; exit(1); }

$tables = ['samples', 'testing_sessions', 'test_results', 'form_templates', 'user_signatures'];
foreach ($tables as $t) {
    echo "=== $t COLS ===" . PHP_EOL;
    $res = $m->query("SHOW COLUMNS FROM $t");
    while ($row = $res->fetch_assoc()) {
        echo $row['Field'] . ' (' . $row['Type'] . ')' . PHP_EOL;
    }
    echo PHP_EOL;
}
