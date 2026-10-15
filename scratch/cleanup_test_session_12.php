<?php
define('BASEPATH', 'defined');
define('ENVIRONMENT', 'development');

require_once __DIR__ . '/../application/config/database.php';
$db_config = $db['default'];

$mysqli = new mysqli($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);

echo "=== CLEANING UP BROWSER TEST SESSION 12 ===\n";
// Ambil sample_id dari session 12
$s_res = $mysqli->query("SELECT sample_id FROM testing_sessions WHERE id = 12");
$row = $s_res->fetch_assoc();

if ($row) {
    $sample_id = (int)$row['sample_id'];
    
    // Hapus test_results
    $mysqli->query("DELETE FROM test_results WHERE session_id = 12");
    echo "Deleted test_results for session 12.\n";
    
    // Hapus testing_sessions
    $mysqli->query("DELETE FROM testing_sessions WHERE id = 12");
    echo "Deleted testing_sessions for session 12.\n";
    
    // Kembalikan sampel ke status 'Menunggu Pengujian' dan set penguji_id ke NULL
    $stmt = $mysqli->prepare("UPDATE samples SET status = 'Menunggu Pengujian', penguji_id = NULL WHERE id = ?");
    $stmt->bind_param("i", $sample_id);
    $stmt->execute();
    echo "Restored sample ID {$sample_id} to status 'Menunggu Pengujian'.\n";
} else {
    echo "Session 12 not found or already deleted.\n";
}

$mysqli->close();
