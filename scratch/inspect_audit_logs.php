<?php
$conn = new mysqli('127.0.0.1', 'root', '', 'limsku');
if ($conn->connect_error) {
    die("Connect Error: " . $conn->connect_error . "\n");
}

echo "=== AUDIT_LOGS COLUMNS ===\n";
$res = $conn->query("SHOW COLUMNS FROM audit_logs");
while ($r = $res->fetch_assoc()) {
    echo $r['Field'] . " (" . $r['Type'] . ")\n";
}

echo "\n=== AUDIT_LOGS DATA (RECENT 15) ===\n";
$res = $conn->query("SELECT * FROM audit_logs ORDER BY id DESC LIMIT 15");
while ($r = $res->fetch_assoc()) {
    print_r($r);
}

echo "\n=== SEEDERS / PRESETS IN CONTROLLERS OR MODELS ===\n";
$conn->close();
