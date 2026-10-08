<?php
define('BASEPATH', 'TRUE');
define('ENVIRONMENT', 'development');
require_once 'application/config/database.php';

$db_config = $db['default'];
$mysqli = new mysqli($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

echo "=== LIMSKU AUDIT & QA ENGINE ===\n\n";

// 1. Audit Tables
echo "[1] AUDIT TABLES & SCHEMA:\n";
$tables = ['testing_sessions', 'test_results', 'samples', 'users', 'audit_logs', 'form_templates'];
foreach ($tables as $t) {
    $res = $mysqli->query("SHOW TABLES LIKE '$t'");
    echo "Tabel '$t': " . ($res->num_rows > 0 ? "EXISTS" : "MISSING") . "\n";
}

// Check test_results session_id
$res = $mysqli->query("DESCRIBE test_results session_id");
echo "test_results.session_id: " . ($res->num_rows > 0 ? "EXISTS" : "MISSING") . "\n\n";

// 2. Audit Existing Sessions & Results
echo "[2] AUDIT SESSIONS & RESULTS:\n";
$sessions = $mysqli->query("SELECT ts.*, s.kode_sampel_manual, s.nama_sampel FROM testing_sessions ts JOIN samples s ON s.id = ts.sample_id ORDER BY ts.id DESC");
echo "Jumlah testing_sessions: " . $sessions->num_rows . "\n";
while ($row = $sessions->fetch_assoc()) {
    echo "  - Session ID: {$row['id']} | Sampel: {$row['nama_sampel']} ({$row['kode_sampel_manual']}) | Jenis: {$row['jenis_pengujian']} | Status: {$row['status']} | Penguji: {$row['penguji_id']} | Verifier: " . ($row['verifier_id'] ?: 'NULL') . " | Approver: " . ($row['approver_id'] ?: 'NULL') . "\n";
    $results = $mysqli->query("SELECT tr.id, tr.template_id, tr.status, tr.file_laporan, t.nama_template FROM test_results tr LEFT JOIN form_templates t ON t.id = tr.template_id WHERE tr.session_id = {$row['id']}");
    echo "    Form count: " . $results->num_rows . "\n";
    while ($r = $results->fetch_assoc()) {
        echo "      Form ID: {$r['id']} | Template: {$r['nama_template']} | Status: {$r['status']} | PDF: " . ($r['file_laporan'] ?: 'NULL') . "\n";
    }
}

echo "\n[3] AUDIT PERMISSIONS for Laporan & Pengujian:\n";
$perms = $mysqli->query("SELECT p.nama_permission, r.nama_role FROM role_permissions rp JOIN permissions p ON p.id = rp.permission_id JOIN roles r ON r.id = rp.role_id WHERE p.nama_permission IN ('laporan_view', 'pengujian_view', 'pengujian_input', 'pengujian_verify', 'pengujian_approve') ORDER BY r.nama_role, p.nama_permission");
while ($p = $perms->fetch_assoc()) {
    echo "  - Role '{$p['nama_role']}' has permission '{$p['nama_permission']}'\n";
}

echo "\n[4] AUDIT USERS & ROLES:\n";
$users = $mysqli->query("SELECT u.id, u.username, u.nama_lengkap, r.nama_role FROM users u JOIN roles r ON r.id = u.role_id ORDER BY u.id");
while ($u = $users->fetch_assoc()) {
    echo "  - User ID: {$u['id']} | Username: {$u['username']} | Nama: {$u['nama_lengkap']} | Role: {$u['nama_role']}\n";
}

$mysqli->close();
