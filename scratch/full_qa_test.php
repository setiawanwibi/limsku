<?php
define('BASEPATH', 'TRUE');
define('ENVIRONMENT', 'development');
define('APPPATH', 'application/');
require_once 'application/config/database.php';

$db_config = $db['default'];
$mysqli = new mysqli($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

echo "=== LIMSKU COMPREHENSIVE QA & AUDIT TEST ===\n\n";

$pass_count = 0;
$fail_count = 0;

function check($test_name, $condition, $details = '') {
    global $pass_count, $fail_count;
    if ($condition) {
        echo "[PASS] $test_name\n";
        if ($details) echo "       Details: $details\n";
        $pass_count++;
    } else {
        echo "[FAIL] $test_name\n";
        if ($details) echo "       Details: $details\n";
        $fail_count++;
    }
}

// 1. Audit Tables
echo "--- AREA 1: TABLE SCHEMA AUDIT ---\n";
$tables = ['testing_sessions', 'test_results', 'samples', 'users', 'audit_logs', 'form_templates'];
foreach ($tables as $t) {
    $res = $mysqli->query("SHOW TABLES LIKE '$t'");
    check("Table '$t' existence", $res->num_rows > 0);
}
$res = $mysqli->query("SHOW COLUMNS FROM test_results LIKE 'session_id'");
check("Column 'test_results.session_id' existence", $res->num_rows > 0);

// 2. Audit Workflow & Multi-form Session Structure
echo "\n--- AREA 2: MULTI-FORM & SESSION INTEGRITY ---\n";
$sessions = $mysqli->query("SELECT ts.*, s.nama_sampel FROM testing_sessions ts JOIN samples s ON s.id = ts.sample_id");
while ($s = $sessions->fetch_assoc()) {
    $results = $mysqli->query("SELECT tr.*, t.kategori FROM test_results tr JOIN form_templates t ON t.id = tr.template_id WHERE tr.session_id = {$s['id']}");
    $form_count = $results->num_rows;
    check("Session #{$s['id']} has multi-form entries ({$form_count} forms)", $form_count > 0);

    // Prevent mixing audit
    $categories = [];
    while ($r = $results->fetch_assoc()) {
        $categories[] = $r['kategori'];
    }
    $unique_cat = array_unique($categories);
    check("Session #{$s['id']} no form category mixing (Category: " . implode(',', $unique_cat) . ")", count($unique_cat) <= 1);
}

// 3. Audit Status Transitions & Workflow Rules
echo "\n--- AREA 3: WORKFLOW STATUS ENFORCEMENT ---\n";

// Test Approval simulation on session 2
require_once 'application/libraries/FPDF.php';
require_once 'application/libraries/Laporan_PDF.php';

$session_2 = $mysqli->query("SELECT ts.*, s.nama_sampel, s.kode_sampel_manual, s.no as no_sampel, s.kategori_sampel FROM testing_sessions ts JOIN samples s ON s.id = ts.sample_id WHERE ts.id = 2")->fetch_assoc();
check("Session #2 initial status is valid", !empty($session_2['status']));

// Simulate approval: update status to Approved / Final
$time_now = date('Y-m-d H:i:s');
$mysqli->query("UPDATE testing_sessions SET status = 'Approved / Final', approver_id = 5, waktu_approval = '$time_now' WHERE id = 2");
$mysqli->query("UPDATE test_results SET status = 'Approved / Final', approver_id = 5, waktu_approval = '$time_now' WHERE session_id = 2");
$mysqli->query("UPDATE samples SET status = 'Approved / Final', updated_by = 5 WHERE id = {$session_2['sample_id']}");

// Fetch updated forms
$forms = [];
$res_f = $mysqli->query("SELECT tr.*, t.nama_template, m.nama_metode FROM test_results tr LEFT JOIN form_templates t ON t.id = tr.template_id LEFT JOIN methods m ON m.id = tr.method_id WHERE tr.session_id = 2");
while ($rf = $res_f->fetch_assoc()) {
    $forms[] = $rf;
}

$penguji  = $mysqli->query("SELECT * FROM users WHERE id = {$session_2['penguji_id']}")->fetch_assoc();
$verifier = $mysqli->query("SELECT * FROM users WHERE id = {$session_2['verifier_id']}")->fetch_assoc();
$approver = $mysqli->query("SELECT * FROM users WHERE id = 5")->fetch_assoc();

$pdf_dir  = "uploads/laporan/";
if (!is_dir($pdf_dir)) {
    mkdir($pdf_dir, 0777, true);
}
$pdf_path = $pdf_dir . "LHU_FINAL_TEST_SESSION_2.pdf";

try {
    $pdf_gen = new Laporan_PDF();
    $pdf_gen->buat_laporan_sesi($session_2, $session_2, $forms, $penguji, $verifier, $approver, $pdf_path);
    $mysqli->query("UPDATE test_results SET file_laporan = '$pdf_path' WHERE session_id = 2");
    check("Multi-form final LHU PDF generated successfully", file_exists($pdf_path), "File: $pdf_path, Size: " . filesize($pdf_path) . " bytes");
} catch (Exception $e) {
    check("Multi-form final LHU PDF generated successfully", false, $e->getMessage());
}

// 4. PDF Content Audit
echo "\n--- AREA 4: PDF & SIGNATURE AUDIT ---\n";
check("PDF File size is valid (> 1KB)", file_exists($pdf_path) && filesize($pdf_path) > 1000);

// 5. Security & Authorization Audit
echo "\n--- AREA 5: RBAC & PERMISSION AUDIT ---\n";
$perm_check = $mysqli->query("SELECT COUNT(*) as cnt FROM role_permissions rp JOIN permissions p ON p.id = rp.permission_id WHERE p.nama_permission = 'laporan_view'")->fetch_assoc();
check("Permission 'laporan_view' is assigned to roles", $perm_check['cnt'] > 0, "Total roles with laporan_view: " . $perm_check['cnt']);

echo "\n=== QA TEST SUMMARY ===\n";
echo "Total PASS: $pass_count\n";
echo "Total FAIL: $fail_count\n";

$mysqli->close();
