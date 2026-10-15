<?php
$conn = new mysqli('127.0.0.1', 'root', '', 'limsku');
if ($conn->connect_error) {
    die("Connect Error: " . $conn->connect_error . "\n");
}

echo "=== DATABASE NAME & VERSION ===\n";
$res = $conn->query("SELECT DATABASE() as db, VERSION() as ver");
$row = $res->fetch_assoc();
echo "Active DB: " . $row['db'] . ", MySQL Version: " . $row['ver'] . "\n\n";

echo "=== TABLES AND ENGINES ===\n";
$res = $conn->query("SHOW TABLE STATUS");
while ($r = $res->fetch_assoc()) {
    echo sprintf("%-25s | Engine: %-6s | Rows: %-5d | Collation: %s\n", $r['Name'], $r['Engine'], $r['Rows'], $r['Collation']);
}

echo "\n=== FORM_TEMPLATES SCHEMA ===\n";
$res = $conn->query("SHOW FULL COLUMNS FROM form_templates");
while ($r = $res->fetch_assoc()) {
    echo sprintf("%-15s | %-20s | Null: %-3s | Key: %-3s | Default: %s\n", $r['Field'], $r['Type'], $r['Null'], $r['Key'], $r['Default']);
}

echo "\n=== FOREIGN KEYS IN DATABASE ===\n";
$res = $conn->query("
    SELECT TABLE_NAME, COLUMN_NAME, CONSTRAINT_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
    FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
    WHERE TABLE_SCHEMA = 'limsku' AND REFERENCED_TABLE_NAME IS NOT NULL
");
while ($r = $res->fetch_assoc()) {
    echo sprintf("%s.%s -> %s.%s (%s)\n", $r['TABLE_NAME'], $r['COLUMN_NAME'], $r['REFERENCED_TABLE_NAME'], $r['REFERENCED_COLUMN_NAME'], $r['CONSTRAINT_NAME']);
}

echo "\n=== TEST_RESULTS TEMPLATE_ID USAGE & DATA_HASIL KEYS ===\n";
$res = $conn->query("
    SELECT tr.id, tr.sample_id, tr.method_id, tr.template_id, ft.kode_template, ft.nama_template, tr.data_hasil
    FROM test_results tr
    LEFT JOIN form_templates ft ON tr.template_id = ft.id
");
while ($r = $res->fetch_assoc()) {
    $data = json_decode($r['data_hasil'], true);
    $keys = is_array($data) ? implode(', ', array_keys($data)) : 'INVALID_JSON';
    echo sprintf("Result ID: %-3d | Template ID: %-2s (%s - %s) | Keys: [%s]\n", 
        $r['id'], $r['template_id'], $r['kode_template'] ?? 'NULL', $r['nama_template'] ?? 'NULL', $keys);
}

echo "\n=== FORM_TEMPLATES SKEMA_FORM KEYS & TYPES ===\n";
$res = $conn->query("SELECT id, kode_template, nama_template, status, skema_form FROM form_templates ORDER BY id ASC");
while ($r = $res->fetch_assoc()) {
    $skema = json_decode($r['skema_form'], true);
    $valid = json_last_error() === JSON_ERROR_NONE ? "VALID JSON" : "INVALID JSON: " . json_last_error_msg();
    echo "--------------------------------------------------\n";
    echo sprintf("Template %d [%s] %s (Status: %s) -> %s\n", $r['id'], $r['kode_template'], $r['nama_template'], $r['status'], $valid);
    if (is_array($skema)) {
        if (isset($skema['fields']) && is_array($skema['fields'])) {
            foreach ($skema['fields'] as $f) {
                $name = $f['name'] ?? $f['id'] ?? 'unknown';
                $type = $f['type'] ?? 'text';
                $label = $f['label'] ?? '';
                $req = !empty($f['required']) ? 'REQ' : 'OPT';
                echo sprintf("   - Field: %-20s | Type: %-10s | %-3s | Label: %s\n", $name, $type, $req, $label);
            }
        } else {
            // Check flat format
            foreach ($skema as $k => $v) {
                if (is_array($v)) {
                    $type = $v['type'] ?? 'array';
                    $label = $v['label'] ?? '';
                    echo sprintf("   - Key: %-20s | Type: %-10s | Label: %s\n", $k, $type, $label);
                } else {
                    echo sprintf("   - Key: %-20s = %s\n", $k, substr((string)$v, 0, 30));
                }
            }
        }
    }
}

echo "\n=== TESTING_SESSIONS COLUMNS ===\n";
$res = $conn->query("SHOW COLUMNS FROM testing_sessions");
while ($r = $res->fetch_assoc()) {
    echo $r['Field'] . " (" . $r['Type'] . ")\n";
}

$conn->close();
