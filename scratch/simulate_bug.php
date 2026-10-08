<?php
define('BASEPATH', 'TRUE');
define('ENVIRONMENT', 'development');
require_once 'application/config/database.php';

$db_config = $db['default'];
$mysqli = new mysqli($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

echo "=== DEEP TRACE: SIMULATING THE EXACT BUG ===\n\n";

// 1. Fetch testing_session ID 2 (which is a multi-form session)
$session_id = 2;
$sesi = $mysqli->query("SELECT * FROM testing_sessions WHERE id = $session_id")->fetch_assoc();
echo "[1] Testing Session #{$session_id}:\n";
echo "    Sample ID: {$sesi['sample_id']}\n";
echo "    Status: {$sesi['status']}\n";

// 2. Fetch test_results for Session 2
$tr_res = $mysqli->query("SELECT * FROM test_results WHERE session_id = $session_id");
echo "\n[2] test_results for Session #{$session_id}:\n";
while ($tr = $tr_res->fetch_assoc()) {
    echo "    TR ID: {$tr['id']} | Session ID: {$tr['session_id']} | Status: {$tr['status']}\n";
}

// 3. Inspect what happens when MT opens /pengujian/approval
echo "\n[3] MT opens /pengujian/approval:\n";
$app_res = $mysqli->query("SELECT ts.id as session_id, ts.sample_id, ts.status FROM testing_sessions ts WHERE ts.status = 'Menunggu Approval'");
echo "    antrean_approval count: " . $app_res->num_rows . "\n";
while ($row = $app_res->fetch_assoc()) {
    echo "    Queue item: session_id = {$row['session_id']}\n";
    echo "    approval.php renders link: site_url('pengujian/detail/' . {$row['session_id']})\n";
    
    // 4. Trace what happens when MT clicks site_url('pengujian/detail/' . session_id)
    $target_id = $row['session_id']; // = 2
    echo "\n[4] MT clicks link -> opens /pengujian/detail/{$target_id}:\n";
    echo "    Controller Pengujian::detail({$target_id}) calls Model_Pengujian::ambil_hasil_by_id({$target_id})\n";
    
    $tr_target = $mysqli->query("SELECT tr.id as test_id, tr.session_id, tr.status, s.nama_sampel FROM test_results tr JOIN samples s ON s.id = tr.sample_id WHERE tr.id = {$target_id}")->fetch_assoc();
    if ($tr_target) {
        echo "    Model_Pengujian::ambil_hasil_by_id({$target_id}) FOUND test_results record:\n";
        echo "      TR ID: {$tr_target['test_id']}\n";
        echo "      Belongs to Session ID: " . ($tr_target['session_id'] ?: 'NULL') . "\n";
        echo "      TR Status: '{$tr_target['status']}'\n";
        echo "      Sample: '{$tr_target['nama_sampel']}'\n";
        echo "    \n    RESULT IN VIEW detail.php:\n";
        if ($tr_target['status'] !== 'Menunggu Approval') {
            echo "      [BUG REPRODUCED!] Status of TR #{$target_id} is '{$tr_target['status']}', NOT 'Menunggu Approval'!\n";
            echo "      Therefore, detail.php DOES NOT RENDER the 'Approve & Finalize' button!\n";
            echo "      MT sees the wrong status or cannot approve the session!\n";
        } else {
            echo "      TR #{$target_id} status is 'Menunggu Approval'.\n";
        }
    } else {
        echo "    Model_Pengujian::ambil_hasil_by_id({$target_id}) returned NULL! (Causes 404 Error!)\n";
    }
}

$mysqli->close();
