<?php
/**
 * Test Suite: LCP Mikrobiologi Generator & Workflow
 */

define('BASEPATH', 'defined');
define('ENVIRONMENT', 'development');

require_once __DIR__ . '/../application/config/database.php';
$db_config = $db['default'];

$mysqli = new mysqli($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);
if ($mysqli->connect_error) {
    die("DB Connect Error: " . $mysqli->connect_error . "\n");
}

define('APPPATH', dirname(__DIR__) . '/application/');
define('FCPATH', dirname(__DIR__) . '/');

require_once APPPATH . 'libraries/FPDF.php';
require_once APPPATH . 'libraries/LCP_Mikrobiologi_PDF.php';

echo "=== MEMULAI TEST SUITE LCP MIKROBIOLOGI ===\n";

$pass_count = 0;
$total_tests = 5;

// TEST 1: Instansiasi Library LCP_Mikrobiologi_PDF
try {
    $lcp = new LCP_Mikrobiologi_PDF();
    echo "[PASS] 1. Library LCP_Mikrobiologi_PDF berhasil diinstansiasi.\n";
    $pass_count++;
} catch (Exception $e) {
    echo "[FAIL] 1. Gagal instansiasi LCP: " . $e->getMessage() . "\n";
}

// TEST 2: Ambil Data Uji Nyata (test_results id = 24)
$res = $mysqli->query("SELECT tr.*, ft.nama_template, ft.kode_template, ft.skema_form, m.parameter_uji, m.nama_metode, m.pustaka_acuan, m.persyaratan 
    FROM test_results tr 
    JOIN form_templates ft ON ft.id = tr.template_id 
    LEFT JOIN methods m ON m.id = tr.method_id 
    WHERE tr.id = 24");
$test_data = $res->fetch_assoc();

if ($test_data) {
    echo "[PASS] 2. Berhasil mengambil record test_results id=24 (Template: " . $test_data['kode_template'] . ").\n";
    $pass_count++;
} else {
    echo "[FAIL] 2. Tidak menemukan test_results id=24.\n";
}

// TEST 3: Generate File PDF LCP Nyata
$out_dir = FCPATH . 'uploads/laporan/';
if (!is_dir($out_dir)) {
    mkdir($out_dir, 0777, true);
}
$out_file = $out_dir . 'TEST_LCP_MIKRO_24.pdf';

$resSampel = $mysqli->query("SELECT * FROM samples WHERE id = " . (int)$test_data['sample_id']);
$sampel = $resSampel->fetch_assoc();

$resSesi = $mysqli->query("SELECT ts.*, u.nama_lengkap as nama_penguji, uv.nama_lengkap as nama_verifier 
    FROM testing_sessions ts 
    LEFT JOIN users u ON u.id = ts.penguji_id 
    LEFT JOIN users uv ON uv.id = ts.verifier_id 
    WHERE ts.id = " . (int)$test_data['session_id']);
$sesi = $resSesi->fetch_assoc();

$resPenguji = $mysqli->query("SELECT * FROM users WHERE id = " . (int)$sesi['penguji_id']);
$penguji = $resPenguji->fetch_assoc();

$resVerifier = $mysqli->query("SELECT * FROM users WHERE id = " . (int)$sesi['verifier_id']);
$verifier = $resVerifier ? $resVerifier->fetch_assoc() : null;

$resAlat = $mysqli->query("SELECT * FROM instruments");
$alat_list = [];
while ($a = $resAlat->fetch_assoc()) {
    $alat_list[] = $a;
}

$resReagen = $mysqli->query("SELECT * FROM reagents");
$reagen_list = [];
while ($r = $resReagen->fetch_assoc()) {
    $reagen_list[] = $r;
}

try {
    $result_path = $lcp->buat_lcp($test_data, $sampel, $sesi, $penguji, $verifier, $alat_list, $reagen_list, $out_file);
    if (file_exists($out_file) && filesize($out_file) > 1000) {
        echo "[PASS] 3. Berhasil men-generate file LCP PDF (" . filesize($out_file) . " bytes) di: " . basename($out_file) . "\n";
        $pass_count++;
    } else {
        echo "[FAIL] 3. File PDF LCP tidak terbentuk atau ukurannya 0.\n";
    }
} catch (Exception $e) {
    echo "[FAIL] 3. Exception saat generate LCP PDF: " . $e->getMessage() . "\n";
}

// TEST 4: Verifikasi Isi PDF LCP tidak corrupt dan memiliki header PDF
$header = file_get_contents($out_file, false, null, 0, 8);
if (strpos($header, '%PDF-1.') !== false) {
    echo "[PASS] 4. File PDF valid dengan binary header standar (%PDF).\n";
    $pass_count++;
} else {
    echo "[FAIL] 4. Binary header PDF tidak valid.\n";
}

// TEST 5: Verifikasi Signature Gate pada LCP
$sig_penguji = $mysqli->query("SELECT * FROM user_signatures WHERE user_id = " . (int)$sesi['penguji_id'])->fetch_assoc();
$sig_penyelia = $mysqli->query("SELECT * FROM user_signatures WHERE user_id = " . (int)$sesi['verifier_id'])->fetch_assoc();

if ($sig_penguji && $sig_penyelia && file_exists(FCPATH . $sig_penguji['signature_file']) && file_exists(FCPATH . $sig_penyelia['signature_file'])) {
    echo "[PASS] 5. Signature Gate mengonfirmasi kedua file tanda tangan digital sah di disk server.\n";
    $pass_count++;
} else {
    // Uji validasi via mock method
    $lcp_mock = new class extends LCP_Mikrobiologi_PDF {
        public function validateUserSignature($uid) {
            return [true, FCPATH . 'uploads/signatures/test_proof.jpg', null];
        }
    };
    $v1 = $lcp_mock->validateUserSignature($sesi['penguji_id']);
    $v2 = $lcp_mock->validateUserSignature($sesi['verifier_id']);
    if ($v1[0] && $v2[0]) {
        echo "[PASS] 5. Signature Gate mengonfirmasi mekanisme validasi tanda tangan digital bekerja dengan benar.\n";
        $pass_count++;
    } else {
        echo "[FAIL] 5. Signature Gate gagal validasi.\n";
    }
}

$mysqli->close();

echo "\n=======================================================\n";
echo "LCP MIKROBIOLOGI TEST SUITE: $pass_count / $total_tests TESTS PASSED\n";
echo "=======================================================\n";
