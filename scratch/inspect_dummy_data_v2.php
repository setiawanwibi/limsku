<?php
$conn = new mysqli('127.0.0.1', 'root', '', 'limsku');
if ($conn->connect_error) {
    die("Connect Error: " . $conn->connect_error . "\n");
}

echo "=== SAMPLES COLUMNS ===\n";
$res = $conn->query("SHOW COLUMNS FROM samples");
while ($r = $res->fetch_assoc()) {
    echo $r['Field'] . " (" . $r['Type'] . ")\n";
}

echo "\n=== SAMPLES DATA ===\n";
$res = $conn->query("SELECT * FROM samples");
while ($r = $res->fetch_assoc()) {
    echo sprintf("ID: %-3d | Kode: %-15s | Nama: %-30s | Status: %-15s | Created: %s\n", 
        $r['id'], $r['kode_sampel'] ?? $r['nomor_sampel'] ?? $r['sample_code'] ?? 'N/A', 
        $r['nama_sampel'] ?? 'N/A', $r['status'] ?? 'N/A', $r['created_at'] ?? 'N/A');
}

echo "\n=== TESTING_SESSIONS DATA ===\n";
$res = $conn->query("SELECT * FROM testing_sessions");
while ($r = $res->fetch_assoc()) {
    echo sprintf("Session ID: %-3d | Sample ID: %-3d | Penguji: %-2d | Jenis: %-12s | Status: %-20s | Created: %s\n", 
        $r['id'], $r['sample_id'], $r['penguji_id'], $r['jenis_pengujian'], $r['status'], $r['created_at']);
}

echo "\n=== TEST_RESULTS DETAIL ===\n";
$res = $conn->query("
    SELECT tr.id, tr.sample_id, tr.method_id, tr.template_id, tr.penguji_id, tr.status, tr.kesimpulan, tr.created_at,
           m.nama_zat_aktif, m.parameter_uji
    FROM test_results tr
    LEFT JOIN methods m ON tr.method_id = m.id
");
while ($r = $res->fetch_assoc()) {
    echo sprintf("Result ID: %-3d | Sample ID: %-3d | Method: %-3d (%s - %s) | Tpl: %-2d | Status: %-15s | Kesimpulan: %s\n",
        $r['id'], $r['sample_id'], $r['method_id'], $r['nama_zat_aktif'] ?? 'NULL', $r['parameter_uji'] ?? 'NULL', $r['template_id'] ?? 0, $r['status'] ?? '-', $r['kesimpulan'] ?? '-');
}

echo "\n=== AUDIT_LOGS DETAIL ===\n";
$res = $conn->query("SELECT id, user_id, action, table_name, record_id, created_at FROM audit_logs ORDER BY id DESC LIMIT 20");
if ($res) {
    while ($r = $res->fetch_assoc()) {
        echo sprintf("Log ID: %-4d | User: %-2d | Action: %-15s | Table: %-15s | Rec ID: %-4d | Time: %s\n",
            $r['id'], $r['user_id'], $r['action'], $r['table_name'], $r['record_id'], $r['created_at']);
    }
}

echo "\n=== CHECK METHODS.TEMPLATE_ID COUNT ===\n";
$res = $conn->query("SELECT COUNT(*) as total_methods, COUNT(template_id) as mapped_methods FROM methods");
$r = $res->fetch_assoc();
echo "Total Methods: " . $r['total_methods'] . " | Mapped Methods: " . $r['mapped_methods'] . "\n";

$conn->close();
