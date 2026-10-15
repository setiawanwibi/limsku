<?php
define('BASEPATH', 'TRUE');
define('ENVIRONMENT', 'development');
require_once __DIR__ . '/../application/config/database.php';

$db_cfg = $db['default'];
$mysqli = @new mysqli($db_cfg['hostname'], $db_cfg['username'], $db_cfg['password'], $db_cfg['database']);
if ($mysqli->connect_error) {
    echo "DB Connect error: " . $mysqli->connect_error . PHP_EOL;
    exit(1);
}

$res = $mysqli->query("SELECT id, kode_template, nama_template, kategori, method_id, parameter_id, status FROM form_templates ORDER BY id ASC");
if ($res) {
    echo "Total templates: " . $res->num_rows . PHP_EOL;
    while ($r = $res->fetch_assoc()) {
        echo sprintf("[%d] %-15s | %-40s | %-12s | method_id:%s | param_id:%s | status:%s\n",
            $r['id'], $r['kode_template'], $r['nama_template'], $r['kategori'],
            $r['method_id'] ?? 'NULL', $r['parameter_id'] ?? 'NULL', $r['status']
        );
    }
} else {
    echo "Query error: " . $mysqli->error . PHP_EOL;
}
$mysqli->close();
