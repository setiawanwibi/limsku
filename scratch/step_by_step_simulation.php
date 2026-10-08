<?php
define('BASEPATH', 'TRUE');
define('ENVIRONMENT', 'development');
require_once 'application/config/database.php';

$db_config = $db['default'];
$mysqli = new mysqli($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

echo "=== DEEP TRACE: STEP-BY-STEP WORKFLOW SIMULATION ===\n\n";

$mysqli->begin_transaction();

try {
    // Step 1: Set Session 2 to 'Menunggu Verifikasi' (Simulating state right after Penguji completes test)
    $mysqli->query("UPDATE testing_sessions SET status = 'Menunggu Verifikasi', verifier_id = NULL, waktu_verifikasi = NULL WHERE id = 2");
    $mysqli->query("UPDATE test_results SET status = 'Menunggu Verifikasi', verifier_id = NULL, waktu_verifikasi = NULL WHERE session_id = 2");
    $mysqli->query("UPDATE samples SET status = 'Menunggu Verifikasi' WHERE id = 2");

    echo "[STEP 1] Penguji completes test:\n";
    $s1 = $mysqli->query("SELECT status FROM testing_sessions WHERE id = 2")->fetch_assoc();
    echo "         testing_sessions #2 status: '{$s1['status']}'\n";

    // Step 2: Penyelia opens /pengujian/verifikasi
    echo "\n[STEP 2] Penyelia opens /pengujian/verifikasi:\n";
    $v_queue = $mysqli->query("SELECT ts.id, ts.status, s.nama_sampel FROM testing_sessions ts JOIN samples s ON s.id = ts.sample_id WHERE ts.status = 'Menunggu Verifikasi'");
    echo "         Queue size: " . $v_queue->num_rows . "\n";
    while ($item = $v_queue->fetch_assoc()) {
        echo "           Item session_id: {$item['id']} | Sampel: {$item['nama_sampel']} | Status: {$item['status']}\n";
    }

    // Step 3: Penyelia verifies session 2 (Penyelia calls verifikasi_sesi/2)
    echo "\n[STEP 3] Penyelia clicks 'Terima & Kirim ke MT' (verifikasi_sesi/2):\n";
    $time_v = date('Y-m-d H:i:s');
    $mysqli->query("UPDATE testing_sessions SET status = 'Menunggu Approval', verifier_id = 4, waktu_verifikasi = '$time_v' WHERE id = 2");
    $mysqli->query("UPDATE test_results SET status = 'Menunggu Approval', verifier_id = 4, waktu_verifikasi = '$time_v' WHERE session_id = 2");
    $mysqli->query("UPDATE samples SET status = 'Menunggu Approval', updated_by = 4 WHERE id = 2");

    $s3 = $mysqli->query("SELECT status, verifier_id, waktu_verifikasi FROM testing_sessions WHERE id = 2")->fetch_assoc();
    echo "         testing_sessions #2 AFTER verification: status='{$s3['status']}', verifier_id={$s3['verifier_id']}, waktu={$s3['waktu_verifikasi']}\n";

    // Step 4: MT opens /pengujian/approval
    echo "\n[STEP 4] MT opens /pengujian/approval:\n";
    $sql_app = "SELECT ts.*, s.nama_sampel, s.kode_sampel_manual, s.no as no_sampel, u.nama_lengkap as nama_penguji, uv.nama_lengkap as nama_verifier
    FROM testing_sessions ts
    INNER JOIN samples s ON s.id = ts.sample_id
    LEFT JOIN users u ON u.id = ts.penguji_id
    LEFT JOIN users uv ON uv.id = ts.verifier_id
    WHERE ts.status = 'Menunggu Approval'
    ORDER BY ts.id ASC";

    $app_queue = $mysqli->query($sql_app);
    echo "         MT Approval Queue count: " . $app_queue->num_rows . "\n";
    while ($app_item = $app_queue->fetch_assoc()) {
        echo "           Session ID in queue: {$app_item['id']} | Sampel: {$app_item['nama_sampel']}\n";
        echo "           approval.php generates link: site_url('pengujian/detail/' . {$app_item['id']})\n";
        
        // Step 5: Trace what happens when MT clicks site_url('pengujian/detail/' . 2)
        $clicked_id = $app_item['id']; // = 2
        echo "\n[STEP 5] MT clicks 'Periksa & Approve' -> opens /pengujian/detail/{$clicked_id}:\n";
        echo "         Controller Pengujian::detail({$clicked_id}) calls Model_Pengujian::ambil_hasil_by_id({$clicked_id})\n";

        // Query executed inside Model_Pengujian::ambil_hasil_by_id($clicked_id):
        $tr_fetch = $mysqli->query("SELECT tr.*, s.nama_sampel FROM test_results tr JOIN samples s ON s.id = tr.sample_id WHERE tr.id = {$clicked_id}")->fetch_assoc();

        if ($tr_fetch) {
            echo "         Model_Pengujian::ambil_hasil_by_id({$clicked_id}) RETRIEVED test_results WHERE id = {$clicked_id}:\n";
            echo "           Found test_result ID: {$tr_fetch['id']}\n";
            echo "           Belongs to session_id: {$tr_fetch['session_id']}\n";
            echo "           test_result STATUS: '{$tr_fetch['status']}'\n";

            if ($tr_fetch['session_id'] != $clicked_id) {
                echo "           [MISMATCH FOUND!] The clicked ID is session_id={$clicked_id}, but test_results record with id={$clicked_id} belongs to session_id={$tr_fetch['session_id']}!\n";
            }
        } else {
            echo "         Model_Pengujian::ambil_hasil_by_id({$clicked_id}) RETRIEVED NULL! (Causes 404 NOT FOUND Error!)\n";
        }
    }

} finally {
    $mysqli->rollback();
    echo "\n[TRANSACTION ROLLED BACK SAFELY - NO PRODUCTION DATA WAS ALTERED]\n";
}

$mysqli->close();
