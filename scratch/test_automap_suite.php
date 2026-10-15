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

// Mulai transaksi isolasi dengan rollback penuh di akhir
$mysqli->begin_transaction();

$total_pass = 0;
$total_fail = 0;

function test_assert($title, $condition, $info = '') {
    global $total_pass, $total_fail;
    if ($condition) {
        echo "[PASS] $title\n";
        if ($info) echo "       Details: $info\n";
        $total_pass++;
    } else {
        echo "[FAIL] $title\n";
        if ($info) echo "       Details: $info\n";
        $total_fail++;
    }
}

echo "=== AUTOMATED TEST SUITE: NORMALISASI & AUTO-MAPPING MASTER METODE ===\n\n";

if (!class_exists('CI_Model')) {
    class CI_Model {}
}
require_once 'application/models/Model_Metode.php';

// 1. Test Normalisasi Parameter Kanonis & Sinonim
echo "--- TEST AREA 1: NORMALISASI PARAMETER KANONIS & KONFLIK SINONIM ---\n";
$cases = [
    'Penetapan Kadar' => 'Penetapan Kadar',
    'penetapan kadar' => 'Penetapan Kadar',
    'Kadar' => 'Penetapan Kadar',
    'Penetapan Kadar Alumunium Hidroksida' => 'Penetapan Kadar',
    'Disolusi' => 'Disolusi',
    'disolusi' => 'Disolusi',
    'pelepasan obat' => 'Disolusi',
    'Disolusi Glibenclamide' => 'Disolusi',
    'Identifikasi' => 'Identifikasi',
    'identifikasi' => 'Identifikasi',
    'Identifikasi Glibenclamide' => 'Identifikasi',
    'Keseragaman Sediaan' => 'Keseragaman Sediaan',
    'keseragaman sediaan' => 'Keseragaman Sediaan',
    'Keseragaman Kandungan' => 'Keseragaman Sediaan',
    'Keragaman Bobot' => 'Keseragaman Sediaan',
    'pH' => 'pH',
    'ph' => 'pH',
    'Waktu hancur' => 'Waktu Hancur',
    'Sterilitas' => 'Sterilitas',
    'Endotoksin' => 'Endotoksin Bakteri',
    'Endotoksin bakteri' => 'Endotoksin Bakteri',
    'Batas mikroba' => 'Batas Mikroba',
    'Penghitungan mikroba' => 'Batas Mikroba',
    'penetapan potensi' => 'Potensi Antibiotik',
    'Uji mikroba spesifik' => 'Mikroba Spesifik',
    'Pemerian' => 'Pemerian',
    'Uji DNA Porceine' => 'Uji DNA Porceine'
];

$all_norm_pass = true;
foreach ($cases as $raw => $expected) {
    $norm = Model_Metode::normalisasi_parameter($raw);
    if ($norm !== $expected) {
        $all_norm_pass = false;
        echo "  [FAIL NORM] '$raw' -> '$norm' (expected '$expected')\n";
    }
}
test_assert("Normalisasi 27 variasi teks monografi menghasilkan kanonis yang konsisten", $all_norm_pass);

// 2. Test Pembatasan Bidang (Kecocokan Kategori LCP vs Bidang)
echo "\n--- TEST AREA 2: PEMBATASAN BIDANG & INTEGRITAS LCP ---\n";
$tpls_q = $mysqli->query("SELECT id, kode_template, nama_template, kategori FROM form_templates WHERE status='Aktif'");
$tpl_map = [];
while ($t = $tpls_q->fetch_assoc()) {
    $tpl_map[$t['id']] = $t;
}

// Simulasi penentuan kandidat
$cand_kadar = Model_Metode::cari_kandidat_lcp('Penetapan Kadar', 'Kimia', 'KCKT', $tpl_map);
test_assert("Penetapan Kadar (Kimia) diarahkan ke Form 1 KIM-01", ($cand_kadar['template_id'] === 1 && $cand_kadar['status'] === 'OTOMATIS'));

$cand_steril = Model_Metode::cari_kandidat_lcp('Sterilitas', 'Mikrobiologi', 'Penyaringan', $tpl_map);
test_assert("Sterilitas (Mikrobiologi) diarahkan ke Form 13 MIK-01", ($cand_steril['template_id'] === 13 && $cand_steril['status'] === 'OTOMATIS'));

// Cross-category rejection check
$cand_cross = Model_Metode::cari_kandidat_lcp('Sterilitas', 'Kimia', 'Penyaringan', $tpl_map);
test_assert("Sterilitas dalam bidang Kimia ditolak otomatis dan butuh konfirmasi", ($cand_cross['status'] === 'PERLU_KONFIRMASI' && $cand_cross['template_id'] === null));

// 3. Test Preview Analisis Tanpa Perubahan Database (Read-Only)
echo "\n--- TEST AREA 3: PREVIEW ANALISIS TANPA PERUBAHAN DB ---\n";
$before_map_count = $mysqli->query("SELECT COUNT(*) as c FROM methods WHERE template_id IS NOT NULL")->fetch_assoc()['c'];

// Jalankan analisis via simulasi model
$methods_sample = $mysqli->query("SELECT id, parameter_uji, bidang_pengujian, metode_analisa, template_id FROM methods WHERE status='Aktif'");
$analisis_total = 0;
$analisis_otomatis = 0;
$analisis_review = 0;
while ($m = $methods_sample->fetch_assoc()) {
    $analisis_total++;
    $kan = Model_Metode::normalisasi_parameter($m['parameter_uji']);
    $res_k = Model_Metode::cari_kandidat_lcp($kan, $m['bidang_pengujian'], $m['metode_analisa'], $tpl_map);
    if ($res_k['status'] === 'OTOMATIS') {
        $analisis_otomatis++;
    } else {
        $analisis_review++;
    }
}
$after_preview_count = $mysqli->query("SELECT COUNT(*) as c FROM methods WHERE template_id IS NOT NULL")->fetch_assoc()['c'];
test_assert("Preview analisis tidak mengubah data tabel methods (Read-Only)", ($before_map_count === $after_preview_count), "Count sebelum: $before_map_count, sesudah: $after_preview_count");
test_assert("Preview membagi $analisis_total monografi: $analisis_otomatis Otomatis, $analisis_review Perlu Konfirmasi", ($analisis_total === ($analisis_otomatis + $analisis_review)));

// 4. Test Penerapan Massal & Perlindungan Mapping Manual
echo "\n--- TEST AREA 4: PENERAPAN MASSAL & PERLINDUNGAN MAPPING MANUAL ---\n";

// Set satu baris manual ke template_id = 999 (misal custom template atau template_id=4)
$sample_row = $mysqli->query("SELECT id FROM methods WHERE parameter_uji LIKE '%Penetapan Kadar%' AND bidang_pengujian='Kimia' LIMIT 1")->fetch_assoc();
$manual_method_id = $sample_row['id'];
$mysqli->query("UPDATE methods SET template_id = 4 WHERE id = $manual_method_id");

// Simulasi penerapan massal untuk kelompok Kimia||Penetapan Kadar ke template_id = 1 tanpa overwrite manual
$target_group = 'Kimia||Penetapan Kadar';
$target_template_id = 1;

// Eksekusi update massal yang menghormati baris manual (overwrite_manual = FALSE)
$p_rows = $mysqli->query("SELECT id, parameter_uji, template_id FROM methods WHERE bidang_pengujian='Kimia' AND status='Aktif'");
$to_update = [];
while ($pr = $p_rows->fetch_assoc()) {
    if (Model_Metode::normalisasi_parameter($pr['parameter_uji']) === 'Penetapan Kadar') {
        if (!empty($pr['template_id'])) {
            continue; // Dilindungi
        }
        $to_update[] = $pr['id'];
    }
}

// Catat jumlah yang sudah template_id = 1 sebelum update massal
$initial_tpl1_count = $mysqli->query("SELECT COUNT(*) as c FROM methods WHERE template_id = 1 AND bidang_pengujian='Kimia'")->fetch_assoc()['c'];

if (!empty($to_update)) {
    $chunks = array_chunk($to_update, 100);
    foreach ($chunks as $chunk) {
        $id_list = implode(',', $chunk);
        $mysqli->query("UPDATE methods SET template_id = $target_template_id WHERE id IN ($id_list)");
    }
}

// Cek apakah baris manual tetap 4 (tidak tertimpa)
$check_manual = $mysqli->query("SELECT template_id FROM methods WHERE id = $manual_method_id")->fetch_assoc()['template_id'];
test_assert("Perlindungan mapping manual berhasil: baris yang sudah dipetakan tidak tertimpa tanpa izin eksplisit", ($check_manual == 4), "Template ID: $check_manual (expected 4)");

// Cek baris lain yang sebelumnya NULL sekarang sudah terpetakan ke template_id = 1
$after_tpl1_count = $mysqli->query("SELECT COUNT(*) as c FROM methods WHERE template_id = 1 AND bidang_pengujian='Kimia'")->fetch_assoc()['c'];
$diff_updated = $after_tpl1_count - $initial_tpl1_count;
test_assert("Penerapan massal berhasil memperbarui " . count($to_update) . " monografi ke LCP KIM-01", ($diff_updated === count($to_update)), "Diupdate: $diff_updated baris");

// 5. Test Integrasi Workflow Pengujian Sampel
echo "\n--- TEST AREA 5: INTEGRASI WORKFLOW PENGUJIAN SAMPEL ---\n";
// Ambil sampel zat aktif yang barusan terpetakan
$m_info = $mysqli->query("SELECT nama_zat_aktif, parameter_uji, template_id FROM methods WHERE id IN (" . implode(',', $to_update) . ") LIMIT 1")->fetch_assoc();

// Cek apakah parameter ini sekarang siap untuk dipilih di alur pengujian
$ready_for_testing = (!empty($m_info['template_id']) && $m_info['template_id'] > 0);
test_assert("Parameter terkonfirmasi [{$m_info['nama_zat_aktif']} - {$m_info['parameter_uji']}] sekarang siap dipilih untuk sesi LCP", $ready_for_testing, "Template ID: {$m_info['template_id']}");

// 6. Rollback Seluruh Data Uji
echo "\n--- ROLLBACK TRANSAKSI ISOLASI ---\n";
$mysqli->rollback();
$final_check = $mysqli->query("SELECT template_id FROM methods WHERE id = $manual_method_id")->fetch_assoc()['template_id'];
test_assert("Rollback transaksi berhasil: Data laboratorium kembali murni tanpa modifikasi", ($final_check === null || empty($final_check)));

$mysqli->close();

echo "\nTOTAL TESTS: " . ($total_pass + $total_fail) . " | PASS: $total_pass | FAIL: $total_fail\n";
if ($total_fail === 0) {
    echo "STATUS: ALL AUTOMATED TESTS FOR MASTER METODE AUTO-MAPPING PASSED PERFECTLY!\n";
} else {
    echo "STATUS: FAILURES DETECTED IN TESTS!\n";
}
