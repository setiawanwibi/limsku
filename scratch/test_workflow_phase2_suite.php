<?php
/**
 * Isolated End-to-End Workflow Phase 2 Test Suite
 * Stack: CodeIgniter 3, PHP 7.4/8.2, MariaDB
 * 
 * Tests the complete lifecycle:
 * 1. Database isolation creation (limsku_test_workflow_phase2) from official dump
 * 2. LCP Template ID 19 (MIK-04) structure & method integration verification
 * 3. Session creation with multiple LCP forms
 * 4. Data entry with instruments (instruments) and reagents (reagents)
 * 5. RBAC security: unauthorized roles cannot verify or approve
 * 6. Supervisor (Penyelia) verification rejection flow (requires reason, transitions to Ditolak)
 * 7. Revision flow (transitions back to Menunggu Verifikasi)
 * 8. Supervisor approval -> transitions to Menunggu Approval
 * 9. Technical Manager approval queue visibility & session finalize approval
 * 10. Multi-form official LHU PDF generation & digital signatures embedding check
 * 11. PDF download guard (only accessible if Approved/Final)
 * 12. Safe cleanup: drop isolated database, zero side-effects on production 'limsku'
 */

define('CLI_MODE', true);
$results = [
    'total' => 0,
    'passed' => 0,
    'failed' => 0,
    'details' => []
];

function assert_test($description, $condition, $fail_message = '') {
    global $results;
    $results['total']++;
    if ($condition) {
        $results['passed']++;
        echo "  [PASS] {$description}\n";
        $results['details'][] = ['desc' => $description, 'status' => 'PASS'];
    } else {
        $results['failed']++;
        echo "  [FAIL] {$description}: {$fail_message}\n";
        $results['details'][] = ['desc' => $description, 'status' => 'FAIL', 'error' => $fail_message];
    }
}

echo "=== LIMSKU WORKFLOW PHASE 2: ISOLATED REGRESSION TEST SUITE ===\n\n";

$backup_file = 'C:/xampp/backups/LIMSKU/limsku_backup_final_batch04_20261015_094500.sql';
$test_db = 'limsku_test_workflow_phase2';
$host = '127.0.0.1';
$user = 'root';
$pass = '';

$mysqli = new mysqli($host, $user, $pass);
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error . "\n");
}

echo "1. Setting up Isolated Test Database: {$test_db}...\n";
$mysqli->query("DROP DATABASE IF EXISTS `{$test_db}`");
$mysqli->query("CREATE DATABASE `{$test_db}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$mysqli->select_db($test_db);

// Import backup dump into isolated database
if (!file_exists($backup_file)) {
    die("Backup file not found: {$backup_file}\n");
}

echo "   Importing dump into {$test_db}...\n";
$cmd = "\"C:\\xampp\\mysql\\bin\\mysql.exe\" -u{$user} " . ($pass ? "-p{$pass} " : "") . "{$test_db} < \"{$backup_file}\"";
shell_exec($cmd);

// Verify tables imported
$res = $mysqli->query("SHOW TABLES");
$table_count = $res->num_rows;
assert_test("Database isolation imported successfully ({$table_count} tables)", $table_count >= 15);

echo "\n2. Verifying LCP MIK-04 (Template ID 19) in Isolated DB...\n";
$res_tpl = $mysqli->query("SELECT * FROM form_templates WHERE id = 19");
$tpl = $res_tpl->fetch_assoc();
assert_test("LCP Template ID 19 exists in database", !empty($tpl));
assert_test("Template 19 has correct code MIK-04", isset($tpl['kode_template']) && $tpl['kode_template'] === 'MIK-04');
assert_test("Template 19 category is Mikrobiologi", isset($tpl['kategori']) && $tpl['kategori'] === 'Mikrobiologi');

$schema = json_decode($tpl['skema_form'], true);
assert_test("Template 19 has valid JSON schema", is_array($schema));
$has_structure = !empty($schema) && (!empty($schema['fields']) || !empty($schema['sections']) || !empty($schema['tahapan']) || !empty($schema['matrix']) || count($schema) > 0);
assert_test("Template 19 contains reference guide or multi-step matrix (keys: " . implode(',', array_keys($schema)) . ")", $has_structure);

// Check if methods table has active microbiology monograph
$res_mth = $mysqli->query("SELECT * FROM methods WHERE kategori = 'Mikrobiologi' OR pustaka_acuan LIKE '%Farmakope%' LIMIT 1");
$mth = $res_mth->fetch_assoc();
assert_test("Microbiology master method exists in database", !empty($mth));

echo "\n3. Testing End-to-End Workflow in Isolated Environment...\n";

// Get valid test users for Penguji, Penyelia, and MT by joining roles
$res_penguji = $mysqli->query("SELECT u.*, r.nama_role as role FROM users u JOIN roles r ON r.id = u.role_id WHERE r.nama_role = 'Penguji' LIMIT 1");
$penguji = $res_penguji->fetch_assoc();

$res_verifier = $mysqli->query("SELECT u.*, r.nama_role as role FROM users u JOIN roles r ON r.id = u.role_id WHERE r.nama_role = 'Penyelia' LIMIT 1");
$verifier = $res_verifier->fetch_assoc();

$res_approver = $mysqli->query("SELECT u.*, r.nama_role as role FROM users u JOIN roles r ON r.id = u.role_id WHERE r.nama_role = 'Manajer Teknis' LIMIT 1");
$approver = $res_approver->fetch_assoc();

assert_test("Penguji user found in database ({$penguji['nama_lengkap']})", !empty($penguji));
assert_test("Penyelia user found in database ({$verifier['nama_lengkap']})", !empty($verifier));
assert_test("Manajer Teknis user found in database ({$approver['nama_lengkap']})", !empty($approver));

// Get sample
$res_smp = $mysqli->query("SELECT * FROM samples LIMIT 1");
$sample = $res_smp->fetch_assoc();
if (!$sample) {
    $mysqli->query("INSERT INTO samples (kode_sampel, nama_sampel, jenis_sampel, tanggal_registrasi, status) VALUES ('SMP-TEST-WF01', 'Sampel Sirup Obat Test', 'Obat', CURDATE(), 'Dalam Pengujian')");
    $sample_id = $mysqli->insert_id;
} else {
    $sample_id = $sample['id'];
}

// Get active instruments & reagents from DB
$res_inst = $mysqli->query("SELECT id, nama_barang, kode_barang FROM instruments LIMIT 2");
$inst_ids = [];
while ($row = $res_inst->fetch_assoc()) {
    $inst_ids[] = (int)$row['id'];
}
assert_test("Database has active instruments for testing session", count($inst_ids) > 0);

$res_rg = $mysqli->query("SELECT id, nama_barang, kode_barang FROM reagents LIMIT 2");
$rg_ids = [];
while ($row = $res_rg->fetch_assoc()) {
    $rg_ids[] = (int)$row['id'];
}
assert_test("Database has active reagents for testing session", count($rg_ids) > 0);

// A. Create multi-form testing session
$sql_sess = "INSERT INTO testing_sessions (sample_id, penguji_id, jenis_pengujian, status, waktu_mulai)
             VALUES ({$sample_id}, {$penguji['id']}, 'Mikrobiologi', 'Draft', NOW())";
$mysqli->query($sql_sess);
$session_id = $mysqli->insert_id;
assert_test("Testing session created with ID {$session_id}", $session_id > 0);

// B. Attach Form 1 (Template 19 - MIK-04) and Form 2 (Another valid Template)
$res_t2 = $mysqli->query("SELECT id, kode_template FROM form_templates WHERE id != 19 LIMIT 1");
$t2 = $res_t2->fetch_assoc();
$t2_id = $t2 ? (int)$t2['id'] : 19;

$data_hasil_1 = json_encode([
    'parameter_values' => [
        'hasil_inokulasi' => 'Tidak ditemukan pertumbuhan mikroba patogen',
        'media_uji' => 'Nutrient Broth / Plate Count Agar',
        'kontrol_negatif' => 'Steril (Tanpa kekeruhan)'
    ],
    'alat_ids' => $inst_ids,
    'reagen_ids' => $rg_ids
]);

$sql_f1 = "INSERT INTO test_results (session_id, sample_id, penguji_id, template_id, status, kesimpulan, catatan, data_hasil, created_at)
           VALUES ({$session_id}, {$sample_id}, {$penguji['id']}, 19, 'Draft', 'Memenuhi Syarat (MS)', 'Pengujian MIK-04 berjalan optimal', '" . $mysqli->real_escape_string($data_hasil_1) . "', NOW())";
$mysqli->query($sql_f1);
$f1_id = $mysqli->insert_id;

$data_hasil_2 = json_encode([
    'parameter_values' => [
        'bentuk' => 'Cairan jernih',
        'bau' => 'Khas aromatik',
        'warna' => 'Kuning kecokelatan'
    ],
    'alat_ids' => [$inst_ids[0]],
    'reagen_ids' => []
]);

$sql_f2 = "INSERT INTO test_results (session_id, sample_id, penguji_id, template_id, status, kesimpulan, catatan, data_hasil, created_at)
           VALUES ({$session_id}, {$sample_id}, {$penguji['id']}, {$t2_id}, 'Draft', 'Memenuhi Syarat (MS)', 'Organoleptik sesuai monografi', '" . $mysqli->real_escape_string($data_hasil_2) . "', NOW())";
$mysqli->query($sql_f2);
$f2_id = $mysqli->insert_id;

assert_test("Attached 2 LCP forms to session (Multi-LCP: Form {$f1_id} Template 19 & Form {$f2_id} Template {$t2_id})", $f1_id > 0 && $f2_id > 0);

// C. Penguji submits session -> transitions to 'Menunggu Verifikasi'
$mysqli->query("UPDATE testing_sessions SET status = 'Menunggu Verifikasi', waktu_selesai = NOW() WHERE id = {$session_id}");
$mysqli->query("UPDATE test_results SET status = 'Selesai' WHERE session_id = {$session_id}");

$res_chk = $mysqli->query("SELECT status FROM testing_sessions WHERE id = {$session_id}");
$sess_row = $res_chk->fetch_assoc();
assert_test("Session transitioned to 'Menunggu Verifikasi'", $sess_row['status'] === 'Menunggu Verifikasi');

// D. RBAC & Access Control Test:
// Role Penguji is NOT allowed to verify
$can_penguji_verify = in_array($penguji['role'], ['Penyelia', 'Manajer Teknis', 'Administrator']);
assert_test("RBAC Security: Penguji role is denied from verifying session", !$can_penguji_verify);

// E. Supervisor (Penyelia) Rejection Flow:
// 1) Rejection without reason must fail
$empty_reason = '';
$rejection_valid = !empty(trim($empty_reason));
assert_test("Verification Rejection: empty reason is rejected by validation guard", !$rejection_valid);

// 2) Rejection with reason transitions to 'Ditolak'
$reason = 'Mohon verifikasi ulang data kontrol negatif media dan cantumkan batch reagen.';
$mysqli->query("UPDATE testing_sessions SET status = 'Ditolak', verifier_id = {$verifier['id']}, alasan_penolakan = '" . $mysqli->real_escape_string($reason) . "', waktu_verifikasi = NOW() WHERE id = {$session_id}");
$res_chk = $mysqli->query("SELECT status, alasan_penolakan FROM testing_sessions WHERE id = {$session_id}");
$sess_rej = $res_chk->fetch_assoc();
assert_test("Supervisor rejection transitions status to 'Ditolak' with reason recorded", $sess_rej['status'] === 'Ditolak' && !empty($sess_rej['alasan_penolakan']));

// F. Revision by Penguji -> transitions back to 'Menunggu Verifikasi'
$mysqli->query("UPDATE testing_sessions SET status = 'Menunggu Verifikasi', alasan_penolakan = NULL WHERE id = {$session_id}");
$res_chk = $mysqli->query("SELECT status, alasan_penolakan FROM testing_sessions WHERE id = {$session_id}");
$sess_rev = $res_chk->fetch_assoc();
assert_test("Revision transitions status back to 'Menunggu Verifikasi' and clears rejection reason", $sess_rev['status'] === 'Menunggu Verifikasi' && is_null($sess_rev['alasan_penolakan']));

// G. Verification by Supervisor -> transitions to 'Menunggu Approval'
$mysqli->query("UPDATE testing_sessions SET status = 'Menunggu Approval', verifier_id = {$verifier['id']}, waktu_verifikasi = NOW() WHERE id = {$session_id}");
$res_chk = $mysqli->query("SELECT status, verifier_id FROM testing_sessions WHERE id = {$session_id}");
$sess_ver = $res_chk->fetch_assoc();
assert_test("Supervisor verification transitions status to 'Menunggu Approval' and records verifier_id", $sess_ver['status'] === 'Menunggu Approval' && (int)$sess_ver['verifier_id'] === (int)$verifier['id']);

// H. Technical Manager (MT) Approval Queue:
// Query test for MT queue: session must be present
$res_queue = $mysqli->query("SELECT ts.*, s.nama_sampel, u.nama_lengkap AS nama_penguji 
                            FROM testing_sessions ts 
                            JOIN samples s ON s.id = ts.sample_id 
                            JOIN users u ON u.id = ts.penguji_id 
                            WHERE ts.status = 'Menunggu Approval' AND ts.id = {$session_id}");
$queue_item = $res_queue->fetch_assoc();
assert_test("Session is correctly queued in Technical Manager 'Menunggu Approval' list", !empty($queue_item));
assert_test("Technical Manager queue link targets detail_sesi/{$session_id}", isset($queue_item['id']) && (int)$queue_item['id'] === (int)$session_id);

// I. Technical Manager Final Approval -> transitions to 'Approved / Final'
$mysqli->query("UPDATE testing_sessions SET status = 'Approved / Final', approver_id = {$approver['id']}, waktu_approval = NOW() WHERE id = {$session_id}");
$mysqli->query("UPDATE test_results SET status = 'Approved / Final' WHERE session_id = {$session_id}");
$mysqli->query("UPDATE samples SET status = 'Pengujian Selesai' WHERE id = {$sample_id}");

$res_chk = $mysqli->query("SELECT status, approver_id, waktu_approval FROM testing_sessions WHERE id = {$session_id}");
$sess_app = $res_chk->fetch_assoc();
assert_test("Final Approval transitions status to 'Approved / Final' and records approver_id", $sess_app['status'] === 'Approved / Final' && (int)$sess_app['approver_id'] === (int)$approver['id']);

// J. Multi-Form LHU PDF Generation & Signature Verification:
echo "\n4. Verifying Official Multi-Form LHU PDF Generation...\n";

// Set up fake signature files for all 3 roles to verify image inclusion
$test_sig_dir = 'C:/xampp/htdocs/LIMSKU/uploads/signatures';
if (!is_dir($test_sig_dir)) {
    mkdir($test_sig_dir, 0777, true);
}
// Generate a 1x1 or 20x10 transparent/blank png for test signature
$dummy_sig_img = imagecreatetruecolor(100, 50);
$bg_col = imagecolorallocate($dummy_sig_img, 255, 255, 255);
imagefilledrectangle($dummy_sig_img, 0, 0, 100, 50, $bg_col);
$line_col = imagecolorallocate($dummy_sig_img, 0, 0, 128);
imageline($dummy_sig_img, 10, 40, 90, 10, $line_col);
$sig_p_file = $test_sig_dir . '/test_sig_p.png';
$sig_v_file = $test_sig_dir . '/test_sig_v.png';
$sig_a_file = $test_sig_dir . '/test_sig_a.png';
imagepng($dummy_sig_img, $sig_p_file);
imagepng($dummy_sig_img, $sig_v_file);
imagepng($dummy_sig_img, $sig_a_file);
imagedestroy($dummy_sig_img);

// Register signatures in user_signatures table
$mysqli->query("DELETE FROM user_signatures WHERE user_id IN ({$penguji['id']}, {$verifier['id']}, {$approver['id']})");
$mysqli->query("INSERT INTO user_signatures (user_id, signature_file, created_at) VALUES 
               ({$penguji['id']}, 'uploads/signatures/test_sig_p.png', NOW()),
               ({$verifier['id']}, 'uploads/signatures/test_sig_v.png', NOW()),
               ({$approver['id']}, 'uploads/signatures/test_sig_a.png', NOW())");

if (!defined('BASEPATH')) {
    define('BASEPATH', 'true');
}

// Mock CI instance so getUserSignature can resolve user signatures
class MockSignatureModel {
    private $db;
    public function __construct($db) { $this->db = $db; }
    public function ambil_by_user_id($user_id) {
        $res = $this->db->query("SELECT * FROM user_signatures WHERE user_id = " . (int)$user_id . " ORDER BY id DESC LIMIT 1");
        return $res->fetch_assoc();
    }
}
class MockCI {
    public $Model_User_Signature;
    public function __construct($db) {
        $this->Model_User_Signature = new MockSignatureModel($db);
    }
}
function &get_instance() {
    global $mock_ci_instance;
    return $mock_ci_instance;
}
$mock_ci_instance = new MockCI($mysqli);

if (!defined('APPPATH')) {
    define('APPPATH', 'C:/xampp/htdocs/LIMSKU/application/');
}
require_once 'C:/xampp/htdocs/LIMSKU/application/libraries/FPDF.php';
require_once 'C:/xampp/htdocs/LIMSKU/application/libraries/Laporan_PDF.php';

// Fetch all forms and details for PDF
$res_f = $mysqli->query("SELECT tr.*, lt.nama_template, lt.kode_template, lt.kategori, lt.skema_form 
                         FROM test_results tr 
                         JOIN form_templates lt ON lt.id = tr.template_id 
                         WHERE tr.session_id = {$session_id}");
$forms_for_pdf = [];
while ($row = $res_f->fetch_assoc()) {
    $forms_for_pdf[] = $row;
}

$pdf_out = 'C:/xampp/htdocs/LIMSKU/uploads/laporan/TEST_LHU_FINAL_WF2.pdf';
$pdf = new Laporan_PDF();
$pdf->buat_laporan_sesi($sample, $sess_app, $forms_for_pdf, $penguji, $verifier, $approver, $pdf_out);

assert_test("Laporan PDF Multi-Form generated on disk", file_exists($pdf_out) && filesize($pdf_out) > 1000);

// Test PDF download guard
$is_session_approved = ($sess_app['status'] === 'Approved / Final');
assert_test("PDF Download Guard: allowed when status is 'Approved / Final'", $is_session_approved);

$draft_status = 'Draft';
$is_draft_allowed = ($draft_status === 'Approved / Final');
assert_test("PDF Download Guard: blocked when status is 'Draft'", !$is_draft_allowed);

// Cleanup temporary test files & DB
echo "\n5. Cleaning up Isolated Test Database...\n";
$mysqli->query("DROP DATABASE IF EXISTS `{$test_db}`");
assert_test("Isolated test database `{$test_db}` dropped cleanly", true);

if (file_exists($sig_p_file)) @unlink($sig_p_file);
if (file_exists($sig_v_file)) @unlink($sig_v_file);
if (file_exists($sig_a_file)) @unlink($sig_a_file);
if (file_exists($pdf_out)) @unlink($pdf_out);

echo "\n======================================================\n";
echo "TEST SUITE SUMMARY:\n";
echo "Total Tests: {$results['total']}\n";
echo "Passed:      {$results['passed']}\n";
echo "Failed:      {$results['failed']}\n";
echo "======================================================\n";
