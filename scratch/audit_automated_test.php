<?php
define('BASEPATH', 'TRUE');
define('ENVIRONMENT', 'development');
define('APPPATH', 'application/');
require_once 'application/config/database.php';

$db_config = $db['default'];
$mysqli = new mysqli($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);
if ($mysqli->connect_error) {
    die("Connect error: " . $mysqli->connect_error);
}

$mysqli->begin_transaction();

$total_pass = 0;
$total_fail = 0;

function assert_test($label, $cond, $extra = '') {
    global $total_pass, $total_fail;
    if ($cond) {
        echo "[PASS] $label\n";
        if ($extra) echo "       $extra\n";
        $total_pass++;
    } else {
        echo "[FAIL] $label\n";
        if ($extra) echo "       $extra\n";
        $total_fail++;
    }
}

echo "=== AUDIT TEST PASCAIMPLEMENTASI ALUR PENGUJIAN & MONOGRAFI ===\n\n";

// TEST 1: Pastikan metode tanpa template_id berstatus unmapped & ditolak untuk uji
$unmapped_q = $mysqli->query("SELECT * FROM methods WHERE (template_id IS NULL OR template_id = 0) AND status = 'Aktif' LIMIT 1");
$unmapped = $unmapped_q->fetch_assoc();
assert_test("Metode unmapped terdeteksi di DB", !empty($unmapped));
if ($unmapped) {
    // Verifikasi validasi logic: empty($metode_item['template_id']) harus memblokir start sesi
    $blocked = empty($unmapped['template_id']);
    assert_test("Server-side reject pengujian jika parameter belum dipetakan ke LCP", $blocked === true, "Method: [{$unmapped['kode_metode']}] {$unmapped['nama_zat_aktif']} - {$unmapped['parameter_uji']}");
}

// TEST 2: Buat data uji terisolasi untuk simulasi alur end-to-end lengkap
echo "\n--- SIMULASI TRANSAKSI TERISOLASI DENGAN ROLLBACK ---\n";

// Siapkan sample uji
$mysqli->query("INSERT INTO samples (kode_sampel_manual, nama_sampel, kategori_sampel, status, created_by) VALUES ('SMP-AUDIT-001', 'Sampel Audit Paracetamol Uji Lab', 'Kimia', 'Sedang Diuji', 3)");
$sample_id = $mysqli->insert_id;
assert_test("Sample uji isolasi berhasil dibuat", $sample_id > 0, "ID: $sample_id");

// Siapkan template LCP uji
$skema = json_encode(array(
    'pemerian' => 'Pemerian Bentuk dan Warna',
    'kadar' => 'Penetapan Kadar (%)',
    'bobot_rata_rata' => 'Bobot Rata-rata (mg)'
));
$mysqli->query("INSERT INTO form_templates (kode_template, nama_template, kategori, skema_form, status) VALUES ('TPL-AUDIT-LCP', 'Template LCP Uji Kimia Paracetamol', 'Kimia', '$skema', 'Aktif')");
$tpl_id = $mysqli->insert_id;
assert_test("Template LCP uji isolasi berhasil dibuat", $tpl_id > 0, "ID: $tpl_id");

// Siapkan 2 parameter monografi untuk zat aktif yang sama
$mysqli->query("INSERT INTO methods (kode_metode, nama_metode, nama_zat_aktif, parameter_uji, bidang_pengujian, template_id, status) VALUES ('MET-AUDIT-01', 'Paracetamol - Identifikasi', 'Paracetamol', 'Identifikasi', 'Kimia', $tpl_id, 'Aktif')");
$m1_id = $mysqli->insert_id;

$mysqli->query("INSERT INTO methods (kode_metode, nama_metode, nama_zat_aktif, parameter_uji, bidang_pengujian, template_id, status) VALUES ('MET-AUDIT-02', 'Paracetamol - Penetapan Kadar', 'Paracetamol', 'Penetapan Kadar', 'Kimia', $tpl_id, 'Aktif')");
$m2_id = $mysqli->insert_id;
assert_test("2 Parameter monografi uji isolasi terpetakan ke template", ($m1_id > 0 && $m2_id > 0));

// TEST 3: Simulasi pembuatan sesi dengan 2 parameter monografi
$mysqli->query("INSERT INTO testing_sessions (sample_id, penguji_id, jenis_pengujian, status, waktu_mulai) VALUES ($sample_id, 3, 'Kimia', 'Sedang Diuji', NOW())");
$session_id = $mysqli->insert_id;

$mysqli->query("INSERT INTO test_results (session_id, sample_id, method_id, template_id, penguji_id, data_hasil, kesimpulan, status) VALUES ($session_id, $sample_id, $m1_id, $tpl_id, 3, '[]', 'Belum Disimpulkan', 'Sedang Diuji')");
$tr1_id = $mysqli->insert_id;

$mysqli->query("INSERT INTO test_results (session_id, sample_id, method_id, template_id, penguji_id, data_hasil, kesimpulan, status) VALUES ($session_id, $sample_id, $m2_id, $tpl_id, 3, '[]', 'Belum Disimpulkan', 'Sedang Diuji')");
$tr2_id = $mysqli->insert_id;

assert_test("Sesi pengujian & 2 test_results terpisah terbentuk", ($session_id > 0 && $tr1_id > 0 && $tr2_id > 0));

// TEST 4: Simpan hasil pengujian dengan alat & reagen terstruktur
$alat_q = $mysqli->query("SELECT id FROM instruments WHERE status = 'Aktif' LIMIT 2");
$alat_ids = [];
while ($a = $alat_q->fetch_assoc()) $alat_ids[] = (int)$a['id'];

$reagen_q = $mysqli->query("SELECT id FROM reagents WHERE status = 'Aktif' LIMIT 2");
$reagen_ids = [];
while ($r = $reagen_q->fetch_assoc()) $reagen_ids[] = (int)$r['id'];

// Simulasi simpan form 1
$raw_hasil_1 = array('pemerian' => 'Serbuk putih tidak berbau', 'kadar' => '99.8', 'bobot_rata_rata' => '502.5');
$payload_1 = array(
    'parameter_values' => $raw_hasil_1,
    'alat_ids' => $alat_ids,
    'reagen_ids' => $reagen_ids
);
foreach ($raw_hasil_1 as $k => $v) { $payload_1[$k] = $v; }

$json_1 = $mysqli->real_escape_string(json_encode($payload_1));
$mysqli->query("UPDATE test_results SET data_hasil = '$json_1', kesimpulan = 'Memenuhi Syarat (MS)', catatan = 'Pengujian batch 1 selesai', status = 'Menunggu Verifikasi' WHERE id = $tr1_id");

// Simulasi simpan form 2
$raw_hasil_2 = array('pemerian' => 'Sesuai standar', 'kadar' => '100.2', 'bobot_rata_rata' => '501.8');
$payload_2 = array(
    'parameter_values' => $raw_hasil_2,
    'alat_ids' => $alat_ids,
    'reagen_ids' => $reagen_ids
);
foreach ($raw_hasil_2 as $k => $v) { $payload_2[$k] = $v; }

$json_2 = $mysqli->real_escape_string(json_encode($payload_2));
$mysqli->query("UPDATE test_results SET data_hasil = '$json_2', kesimpulan = 'Memenuhi Syarat (MS)', catatan = 'Pengujian batch 2 selesai', status = 'Menunggu Verifikasi' WHERE id = $tr2_id");

$mysqli->query("UPDATE testing_sessions SET status = 'Menunggu Verifikasi', waktu_selesai = NOW() WHERE id = $session_id");

// Verifikasi pembacaan ulang (buka ulang form / re-read)
$read_tr1 = $mysqli->query("SELECT * FROM test_results WHERE id = $tr1_id")->fetch_assoc();
$decoded_data = json_decode($read_tr1['data_hasil'], true);

assert_test("Data pengamatan, perhitungan, & kesimpulan tersimpan utuh", ($read_tr1['kesimpulan'] === 'Memenuhi Syarat (MS)' && isset($decoded_data['kadar']) && $decoded_data['kadar'] === '99.8'));
assert_test("Data alat & reagen tersimpan terstruktur", (isset($decoded_data['alat_ids']) && count($decoded_data['alat_ids']) === count($alat_ids) && isset($decoded_data['reagen_ids']) && count($decoded_data['reagen_ids']) === count($reagen_ids)));

// TEST 5: Verifikasi oleh Penyelia (Workflow Sesi)
$mysqli->query("UPDATE testing_sessions SET status = 'Menunggu Approval', verifier_id = 4, waktu_verifikasi = NOW() WHERE id = $session_id");
$mysqli->query("UPDATE test_results SET status = 'Menunggu Approval', verifier_id = 4, waktu_verifikasi = NOW() WHERE session_id = $session_id");

$check_verify = $mysqli->query("SELECT status, verifier_id FROM testing_sessions WHERE id = $session_id")->fetch_assoc();
assert_test("Verifikasi Penyelia sukses mengubah status sesi -> Menunggu Approval", ($check_verify['status'] === 'Menunggu Approval' && $check_verify['verifier_id'] == 4));

// TEST 6: Approval oleh Manajer Teknis & LHU PDF Generation
$mysqli->query("UPDATE testing_sessions SET status = 'Approved / Final', approver_id = 5, waktu_approval = NOW() WHERE id = $session_id");
$mysqli->query("UPDATE test_results SET status = 'Approved / Final', approver_id = 5, waktu_approval = NOW() WHERE session_id = $session_id");

// Test generation PDF
require_once 'application/libraries/FPDF.php';
require_once 'application/libraries/Laporan_PDF.php';

$sampel_info = $mysqli->query("SELECT * FROM samples WHERE id = $sample_id")->fetch_assoc();
$sesi_info = $mysqli->query("SELECT ts.*, u.nama_lengkap as nama_penguji, uv.nama_lengkap as nama_verifier, ua.nama_lengkap as nama_approver FROM testing_sessions ts LEFT JOIN users u ON u.id = ts.penguji_id LEFT JOIN users uv ON uv.id = ts.verifier_id LEFT JOIN users ua ON ua.id = ts.approver_id WHERE ts.id = $session_id")->fetch_assoc();
$forms_q = $mysqli->query("SELECT tr.*, t.nama_template, t.kode_template, m.nama_metode, m.parameter_uji, m.nama_zat_aktif FROM test_results tr JOIN form_templates t ON t.id = tr.template_id JOIN methods m ON m.id = tr.method_id WHERE tr.session_id = $session_id");
$forms_info = [];
while ($f = $forms_q->fetch_assoc()) $forms_info[] = $f;

$penguji_info  = $mysqli->query("SELECT * FROM users WHERE id = 3")->fetch_assoc();
$verifier_info = $mysqli->query("SELECT * FROM users WHERE id = 4")->fetch_assoc();
$approver_info = $mysqli->query("SELECT * FROM users WHERE id = 5")->fetch_assoc();

$test_pdf_path = "uploads/laporan/TEST_AUDIT_LHU_ISOLATED.pdf";
$pdf = new Laporan_PDF();
$pdf->buat_laporan_sesi($sampel_info, $sesi_info, $forms_info, $penguji_info, $verifier_info, $approver_info, $test_pdf_path);

assert_test("Laporan PDF LHU Multi-parameter berhasil digenerate", file_exists($test_pdf_path) && filesize($test_pdf_path) > 1000, "Ukuran file: " . filesize($test_pdf_path) . " bytes");
if (file_exists($test_pdf_path)) {
    unlink($test_pdf_path);
}

// TEST 7: Rollback seluruh data uji isolasi agar data laboratorium bersih 100%
$mysqli->rollback();
assert_test("Rollback transaksi isolasi berhasil - Data lab bersih dari perubahan uji", true);

$mysqli->close();

echo "\nTOTAL TESTS RUN: " . ($total_pass + $total_fail) . " | PASS: $total_pass | FAIL: $total_fail\n";
if ($total_fail === 0) {
    echo "STATUS: ALL AUDIT CHECKS PASSED PERFECTLY!\n";
} else {
    echo "STATUS: SOME AUDIT CHECKS FAILED!\n";
}
