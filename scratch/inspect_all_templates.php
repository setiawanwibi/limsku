<?php
define('BASEPATH', 'defined');
define('ENVIRONMENT', 'development');
require_once __DIR__ . '/../application/config/database.php';
$db_config = $db['default'];

$mysqli = new mysqli($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);
if ($mysqli->connect_error) {
    die("Connect Error: " . $mysqli->connect_error . "\n");
}

$res = $mysqli->query("SELECT id, kode_template, nama_template, kategori FROM form_templates");
while ($r = $res->fetch_assoc()) {
    echo "ID: " . $r['id'] . " | " . $r['kode_template'] . " | " . $r['kategori'] . " | " . $r['nama_template'] . "\n";
}

echo "\n--- TESTING SESSIONS WITH FORMS ---\n";
$resS = $mysqli->query("SELECT ts.id as session_id, ts.sample_id, ts.status, ts.jenis_pengujian, COUNT(tr.id) as form_count 
    FROM testing_sessions ts 
    LEFT JOIN test_results tr ON tr.session_id = ts.id 
    GROUP BY ts.id ORDER BY ts.id DESC LIMIT 10");
while ($r = $resS->fetch_assoc()) {
    echo json_encode($r) . "\n";
}

echo "\n--- SAMPLE TEST RESULTS DATA ---\n";
$resT = $mysqli->query("SELECT tr.id, tr.session_id, tr.template_id, tr.kesimpulan, ft.kode_template, ft.nama_template, LENGTH(tr.data_hasil) as data_hasil_len
    FROM test_results tr 
    JOIN form_templates ft ON ft.id = tr.template_id 
    LIMIT 5");
while ($r = $resT->fetch_assoc()) {
    echo json_encode($r) . "\n";
}

$mysqli->close();
