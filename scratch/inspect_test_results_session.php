<?php
$conn = new mysqli('127.0.0.1', 'root', '', 'limsku');
if ($conn->connect_error) {
    die("Connect Error: " . $conn->connect_error . "\n");
}

echo "=== TEST_RESULTS COLUMNS ===\n";
$res = $conn->query("SHOW COLUMNS FROM test_results");
while ($r = $res->fetch_assoc()) {
    echo sprintf("%-20s | %-15s | Null: %-3s | Default: %s\n", $r['Field'], $r['Type'], $r['Null'], $r['Default']);
}

echo "\n=== TEST_RESULTS SESSION_ID VALUES ===\n";
$res = $conn->query("SELECT id, session_id, sample_id, template_id, status FROM test_results");
while ($r = $res->fetch_assoc()) {
    echo sprintf("Result ID: %-3d | Session ID: %-4s | Sample ID: %-3d | Template ID: %-2d | Status: %s\n",
        $r['id'], $r['session_id'] ?? 'NULL', $r['sample_id'], $r['template_id'], $r['status']);
}

$conn->close();
