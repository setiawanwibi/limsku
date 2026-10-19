<?php
define('BASEPATH', 'true');
define('ENVIRONMENT', 'development');
require 'application/config/database.php';
$cfg = $db['default'];
$m = new mysqli($cfg['hostname'], $cfg['username'], $cfg['password'], $cfg['database']);
if ($m->connect_error) { echo "DB ERROR: " . $m->connect_error; exit(1); }

echo "=== SAMPLES SURTUG & FIELD SAMPLE DATA ===" . PHP_EOL;
$res = $m->query("SELECT id, no, kode_sampel_manual, surtug, tanggal_sampling, created_at, kemasan, kondisi_produk, jumlah_mikro, jumlah_arsip, penandaan FROM samples WHERE surtug IS NOT NULL AND surtug != '' LIMIT 5");
if ($res && $res->num_rows > 0) {
    while ($r = $res->fetch_assoc()) {
        echo json_encode($r, JSON_PRETTY_PRINT) . PHP_EOL;
    }
} else {
    echo "No non-empty surtug samples found. Checking general samples:" . PHP_EOL;
    $res2 = $m->query("SELECT id, no, kode_sampel_manual, surtug, tanggal_sampling, created_at, kemasan, kondisi_produk, jumlah_mikro, jumlah_arsip, penandaan FROM samples LIMIT 3");
    while ($r = $res2->fetch_assoc()) {
        echo json_encode($r, JSON_PRETTY_PRINT) . PHP_EOL;
    }
}

echo PHP_EOL . "=== TESTING SESSIONS SAMPLE DATA ===" . PHP_EOL;
$resSesi = $m->query("SELECT id, sample_id, penguji_id, jenis_pengujian, status, verifier_id, approver_id, waktu_selesai, waktu_verifikasi, waktu_approval FROM testing_sessions LIMIT 5");
while ($r = $resSesi->fetch_assoc()) {
    echo json_encode($r, JSON_PRETTY_PRINT) . PHP_EOL;
}
