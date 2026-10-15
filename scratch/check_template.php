<?php
define('BASEPATH', 'defined');
require_once __DIR__ . '/../application/config/database.php';
$db_config = $db['default'];

$mysqli = new mysqli($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);
if ($mysqli->connect_error) {
    die("Connect Error: " . $mysqli->connect_error . "\n");
}

$res = $mysqli->query("SELECT id, kode_template, nama_template, kategori, status, LENGTH(skema_form) as skema_len FROM form_templates WHERE kode_template='MIK-04'");
$row = $res->fetch_assoc();
echo "TEMPLATE_CHECK: " . json_encode($row) . "\n";
$mysqli->close();
