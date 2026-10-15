<?php
$conn = new mysqli('127.0.0.1', 'root', '', 'limsku');
if ($conn->connect_error) {
    die("Connect Error: " . $conn->connect_error . "\n");
}

echo "=== ALL TABLES IN LIMSKU ===\n";
$tables = [];
$res = $conn->query("SHOW TABLES");
while ($r = $res->fetch_array()) {
    $tables[] = $r[0];
}
echo implode(', ', $tables) . "\n\n";

echo "=== ALL FOREIGN KEYS REFERENCING TESTING_SESSIONS, TEST_RESULTS, SAMPLES, FORM_TEMPLATES ===\n";
$res = $conn->query("
    SELECT TABLE_NAME, COLUMN_NAME, CONSTRAINT_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
    FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
    WHERE TABLE_SCHEMA = 'limsku' 
      AND (REFERENCED_TABLE_NAME IN ('testing_sessions', 'test_results', 'samples', 'form_templates')
           OR TABLE_NAME IN ('testing_sessions', 'test_results', 'samples', 'form_templates'))
");
while ($r = $res->fetch_assoc()) {
    echo sprintf("%s.%s -> %s.%s (%s)\n", $r['TABLE_NAME'], $r['COLUMN_NAME'], $r['REFERENCED_TABLE_NAME'] ?? 'NULL', $r['REFERENCED_COLUMN_NAME'] ?? 'NULL', $r['CONSTRAINT_NAME']);
}

echo "\n=== ALL COLUMNS IN ALL TABLES WITH 'session' IN NAME ===\n";
$res = $conn->query("
    SELECT TABLE_NAME, COLUMN_NAME, DATA_TYPE
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = 'limsku' AND COLUMN_NAME LIKE '%session%'
");
while ($r = $res->fetch_assoc()) {
    echo sprintf("%s.%s (%s)\n", $r['TABLE_NAME'], $r['COLUMN_NAME'], $r['DATA_TYPE']);
}

echo "\n=== SAMPLES STATUS AND PENGUJI_ID FOR SAMPLES IN TESTING_SESSIONS ===\n";
$res = $conn->query("
    SELECT s.id, s.nama_sampel, s.status as sample_status, s.penguji_id as sample_penguji,
           ts.id as session_id, ts.jenis_pengujian, ts.status as session_status, ts.penguji_id as session_penguji
    FROM samples s
    LEFT JOIN testing_sessions ts ON s.id = ts.sample_id
    WHERE s.id IN (SELECT DISTINCT sample_id FROM testing_sessions) OR s.id IN (SELECT DISTINCT sample_id FROM test_results)
");
while ($r = $res->fetch_assoc()) {
    echo sprintf("Sample %-2d [%-25s] Stat: %-15s Penguji: %-2s | Sesi: %-2s [%-12s] Stat: %-15s Penguji: %-2s\n",
        $r['id'], substr($r['nama_sampel'], 0, 25), $r['sample_status'], $r['sample_penguji'] ?? 'NULL',
        $r['session_id'] ?? 'NULL', $r['jenis_pengujian'] ?? 'NULL', $r['session_status'] ?? 'NULL', $r['session_penguji'] ?? 'NULL');
}

echo "\n=== AUDIT_LOGS RECENT ENTRIES USER INFO ===\n";
$res = $conn->query("
    SELECT al.id, al.user_id, al.username, u.nama_lengkap, al.aksi, al.modul, al.created_at
    FROM audit_logs al
    LEFT JOIN users u ON al.user_id = u.id
    ORDER BY al.id DESC LIMIT 10
");
while ($r = $res->fetch_assoc()) {
    echo sprintf("Log ID: %-4d | UserID: %-2d | Username: %-10s (%-15s) | Aksi: %-15s | Modul: %-15s | Time: %s\n",
        $r['id'], $r['user_id'], $r['username'], $r['nama_lengkap'] ?? 'N/A', $r['aksi'], $r['modul'], $r['created_at']);
}

echo "\n=== USERS TABLE ===\n";
$res = $conn->query("SELECT u.id, u.username, u.nama_lengkap, r.nama_role FROM users u LEFT JOIN roles r ON u.role_id = r.id");
while ($r = $res->fetch_assoc()) {
    echo sprintf("User ID: %-2d | Username: %-12s | Nama: %-25s | Role: %s\n",
        $r['id'], $r['username'], $r['nama_lengkap'], $r['nama_role']);
}

$conn->close();
