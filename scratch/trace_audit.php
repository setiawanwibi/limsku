<?php
define('BASEPATH', 'TRUE');
define('ENVIRONMENT', 'development');
require_once 'application/config/database.php';

$db_config = $db['default'];
$mysqli = new mysqli($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

echo "=== LIMSKU DEEP WORKFLOW TRACE & QUERY AUDIT ===\n\n";

// 1. Inspect testing_sessions
echo "[1] ALL TESTING SESSIONS IN DB:\n";
$res = $mysqli->query("SELECT ts.*, s.nama_sampel, s.status as sample_status FROM testing_sessions ts JOIN samples s ON s.id = ts.sample_id");
while ($r = $res->fetch_assoc()) {
    echo "  Session ID: {$r['id']} | Sample ID: {$r['sample_id']} ({$r['nama_sampel']})\n";
    echo "    Session status: '{$r['status']}' | Sample status: '{$r['sample_status']}'\n";
    echo "    Penguji: {$r['penguji_id']} | Verifier: " . ($r['verifier_id'] ?: 'NULL') . " | Approver: " . ($r['approver_id'] ?: 'NULL') . "\n";
    echo "    Waktu Merekam: Mulai={$r['waktu_mulai']}, Selesai={$r['waktu_selesai']}, Verify={$r['waktu_verifikasi']}, Approve={$r['waktu_approval']}\n";
    
    // Test Results
    $tr_res = $mysqli->query("SELECT id, template_id, status, verifier_id, approver_id, file_laporan FROM test_results WHERE session_id = {$r['id']}");
    echo "    test_results for Session {$r['id']} (count: {$tr_res->num_rows}):\n";
    while ($tr = $tr_res->fetch_assoc()) {
        echo "      TR ID: {$tr['id']} | Status: '{$tr['status']}' | Verifier: " . ($tr['verifier_id'] ?: 'NULL') . " | Approver: " . ($tr['approver_id'] ?: 'NULL') . "\n";
    }
    echo "\n";
}

// 2. Run the exact query from Model_Pengujian::ambil_antrean_approval()
echo "[2] EXECUTING Model_Pengujian::ambil_antrean_approval() QUERY:\n";
$sql_primary = "SELECT ts.*, s.nama_sampel, s.kode_sampel_manual, s.no as no_sampel, u.nama_lengkap as nama_penguji, uv.nama_lengkap as nama_verifier,
    (SELECT COUNT(*) FROM test_results tr WHERE tr.session_id = ts.id) as jumlah_form,
    (SELECT GROUP_CONCAT(DISTINCT m.nama_metode SEPARATOR ', ') FROM test_results tr JOIN methods m ON m.id = tr.method_id WHERE tr.session_id = ts.id) as nama_metode,
    (SELECT GROUP_CONCAT(DISTINCT t.nama_template SEPARATOR ', ') FROM test_results tr JOIN form_templates t ON t.id = tr.template_id WHERE tr.session_id = ts.id) as nama_template,
    (SELECT GROUP_CONCAT(DISTINCT tr.kesimpulan SEPARATOR ', ') FROM test_results tr WHERE tr.session_id = ts.id) as kesimpulan
FROM testing_sessions ts
INNER JOIN samples s ON s.id = ts.sample_id
LEFT JOIN users u ON u.id = ts.penguji_id
LEFT JOIN users uv ON uv.id = ts.verifier_id
WHERE ts.status = 'Menunggu Approval'
ORDER BY ts.id ASC";

$res_app = $mysqli->query($sql_primary);
echo "Primary Query Results Count: " . $res_app->num_rows . "\n";
while ($row = $res_app->fetch_assoc()) {
    echo "  -> Found Session ID: {$row['id']} | Sampel: {$row['nama_sampel']} | Status: {$row['status']} | Verifier: {$row['nama_verifier']}\n";
}

// Check fallback query in ambil_antrean_approval()
echo "\nChecking Fallback Query in ambil_antrean_approval():\n";
$sql_fallback = "SELECT tr.*, m.nama_metode, t.nama_template, u.nama_lengkap as nama_penguji, uv.nama_lengkap as nama_verifier, s.nama_sampel, s.kode_sampel_manual, s.no as no_sampel, 1 as jumlah_form
FROM test_results tr
INNER JOIN samples s ON s.id = tr.sample_id
LEFT JOIN methods m ON m.id = tr.method_id
LEFT JOIN form_templates t ON t.id = tr.template_id
LEFT JOIN users u ON u.id = tr.penguji_id
LEFT JOIN users uv ON uv.id = tr.verifier_id
WHERE tr.status = 'Menunggu Approval'
ORDER BY tr.id ASC";

$res_fallback = $mysqli->query($sql_fallback);
echo "Fallback Query Results Count: " . $res_fallback->num_rows . "\n";
while ($row = $res_fallback->fetch_assoc()) {
    echo "  -> Found Test Result ID: {$row['id']} | Sampel: {$row['nama_sampel']} | Status: {$row['status']}\n";
}

$mysqli->close();
