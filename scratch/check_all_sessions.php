<?php
define('BASEPATH', 'defined');
define('ENVIRONMENT', 'development');

require_once __DIR__ . '/../application/config/database.php';
$db_config = $db['default'];

$mysqli = new mysqli($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);
$res = $mysqli->query("SELECT ts.id, ts.sample_id, ts.penguji_id, ts.jenis_pengujian, ts.status, s.kode_sampel_manual FROM testing_sessions ts JOIN samples s ON s.id = ts.sample_id");

echo "ALL EXISTING SESSIONS:\n";
while ($r = $res->fetch_assoc()) {
    echo "Session ID={$r['id']}, PengujiID={$r['penguji_id']}, Status={$r['status']}, Jenis={$r['jenis_pengujian']}, Sample={$r['kode_sampel_manual']}\n";
}
$mysqli->close();
