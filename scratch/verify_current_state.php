<?php
$conn = new mysqli('127.0.0.1', 'root', '', 'limsku');
if ($conn->connect_error) {
    die("Connect Error: " . $conn->connect_error . "\n");
}

echo "=== VERIFIKASI ULANG JUMLAH & ID RECORD AKTUAL ===\n";

// 1. form_templates
$res = $conn->query("SELECT id, kode_template, nama_template, status FROM form_templates ORDER BY id ASC");
$ft_ids = [];
while ($r = $res->fetch_assoc()) {
    $ft_ids[] = $r['id'];
}
echo "form_templates: Total = " . count($ft_ids) . " | IDs = [" . implode(', ', $ft_ids) . "]\n";

// 2. test_results
$res = $conn->query("SELECT id, session_id, sample_id, template_id, status FROM test_results ORDER BY id ASC");
$tr_ids = [];
while ($r = $res->fetch_assoc()) {
    $tr_ids[] = $r['id'];
}
echo "test_results: Total = " . count($tr_ids) . " | IDs = [" . implode(', ', $tr_ids) . "]\n";

// 3. testing_sessions
$res = $conn->query("SELECT id, sample_id, jenis_pengujian, status FROM testing_sessions ORDER BY id ASC");
$ts_ids = [];
while ($r = $res->fetch_assoc()) {
    $ts_ids[] = $r['id'];
}
echo "testing_sessions: Total = " . count($ts_ids) . " | IDs = [" . implode(', ', $ts_ids) . "]\n";

// 4. methods
$res = $conn->query("SELECT COUNT(*) as cnt, COUNT(template_id) as mapped_cnt FROM methods");
$m = $res->fetch_assoc();
echo "methods: Total = " . $m['cnt'] . " | Mapped = " . $m['mapped_cnt'] . "\n\n";

echo "=== DETAIL DATA SAMPLES ID IN (2, 3, 4, 6, 7, 8) ===\n";
$res = $conn->query("
    SELECT id, no, kode_sampel_manual, nama_sampel, status, penguji_id, created_at, updated_at
    FROM samples
    WHERE id IN (2, 3, 4, 6, 7, 8)
    ORDER BY id ASC
");
while ($r = $res->fetch_assoc()) {
    echo sprintf("Sample ID: %d | No: %-10s | Nama: %-30s | Status Saat Ini: %-20s | Penguji ID: %s\n",
        $r['id'], $r['no'] ?? '-', $r['nama_sampel'], $r['status'], $r['penguji_id'] ?? 'NULL');
}

echo "\n=== AUDIT_LOGS SKEMA DETAILED ===\n";
$res = $conn->query("SHOW FULL COLUMNS FROM audit_logs");
while ($r = $res->fetch_assoc()) {
    echo sprintf("%-15s | %-15s | Null: %-3s | Default: %s\n", $r['Field'], $r['Type'], $r['Null'], $r['Default']);
}

$conn->close();
