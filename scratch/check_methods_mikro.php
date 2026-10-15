<?php
define('BASEPATH', 'TRUE');
define('ENVIRONMENT', 'development');
require_once __DIR__ . '/../application/config/database.php';

$db_cfg = $db['default'];
$mysqli = @new mysqli($db_cfg['hostname'], $db_cfg['username'], $db_cfg['password'], $db_cfg['database']);
if ($mysqli->connect_error) {
    die("DB error: " . $mysqli->connect_error);
}

echo "=== CHECK METHODS MIKROBIOLOGI ===\n";
$res = $mysqli->query("
    SELECT id, kode_metode, nama_metode, bidang_pengujian, parameter_uji, template_id, status
    FROM methods
    WHERE bidang_pengujian = 'Mikrobiologi' OR kategori = 'Mikrobiologi'
    LIMIT 30
");
if ($res) {
    echo "Found " . $res->num_rows . " rows:\n";
    while ($r = $res->fetch_assoc()) {
        echo sprintf("[%d] %-15s | %-30s | %-30s | tpl_id:%s\n",
            $r['id'], $r['kode_metode'], $r['nama_metode'], $r['parameter_uji'], $r['template_id'] ?? 'NULL'
        );
    }
}

echo "\n=== CHECK METHODS WITH 'Cemaran' OR 'Kualitatif' OR 'Mikroba' ===\n";
$res2 = $mysqli->query("
    SELECT id, kode_metode, nama_metode, bidang_pengujian, parameter_uji, template_id, status
    FROM methods
    WHERE parameter_uji LIKE '%Cemaran%' OR parameter_uji LIKE '%Mikroba%' OR parameter_uji LIKE '%Kualitatif%'
    LIMIT 30
");
if ($res2) {
    echo "Found " . $res2->num_rows . " rows:\n";
    while ($r = $res2->fetch_assoc()) {
        echo sprintf("[%d] %-15s | %-30s | %-30s | tpl_id:%s\n",
            $r['id'], $r['kode_metode'], $r['nama_metode'], $r['parameter_uji'], $r['template_id'] ?? 'NULL'
        );
    }
}

$mysqli->close();
