<?php
define('BASEPATH', 'defined');
define('ENVIRONMENT', 'development');

require_once __DIR__ . '/../application/config/database.php';
$db_config = $db['default'];

$mysqli = new mysqli($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);
$res = $mysqli->query("SELECT ts.id, ts.sample_id, ts.penguji_id, ts.jenis_pengujian, ts.status, s.kode_sampel_manual, s.nama_sampel, tr.id as test_id, tr.template_id, t.kode_template FROM testing_sessions ts JOIN samples s ON s.id = ts.sample_id LEFT JOIN test_results tr ON tr.session_id = ts.id LEFT JOIN form_templates t ON t.id = tr.template_id WHERE ts.penguji_id = 3");

echo "EXISTING SESSIONS FOR PENGUJI (ID: 3):\n";
while ($r = $res->fetch_assoc()) {
    echo "Session ID={$r['id']}, Status={$r['status']}, Jenis={$r['jenis_pengujian']}, Sample={$r['kode_sampel_manual']}, TestID={$r['test_id']}, Template={$r['kode_template']}\n";
}
$mysqli->close();
