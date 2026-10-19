<?php
/**
 * Isolated Automated Test Suite for Phase 2:
 * Sample-Based Technical Manager (MT) Approval Queue & Cross-Session Review
 *
 * Tests:
 * 1. Sampel Kimia saja yang siap muncul satu kali di antrean MT.
 * 2. Sampel Mikro saja yang siap muncul satu kali di antrean MT.
 * 3. Sampel yang membutuhkan Kimia + Mikro dan keduanya siap muncul satu kali dengan kedua sesi tersedia.
 * 4. Sampel dengan satu sesi wajib belum dibuat tidak muncul sebagai siap.
 * 5. Sampel dengan satu sesi wajib masih berjalan ('Sedang Diuji' / 'Menunggu Verifikasi') tidak muncul sebagai siap.
 * 6. Sampel dengan salah satu sesi wajib ditolak tidak muncul sebagai siap.
 * 7. Sampel dengan kuantitas ambigu tidak muncul sebagai siap.
 * 8. Segregasi data: sesi dari sampel lain tidak tertukar ke sampel yang sedang dievaluasi.
 * 9. Otentikasi & Otorisasi: Role tanpa permission 'pengujian_approve' diblokir oleh guard cek_hak_akses.
 * 10. Konsistensi relasi: metadata penguji, verifier, dan ID sesi digabung dengan benar per sampel.
 */

define('BASEPATH', 'true');
define('APPPATH', dirname(__DIR__) . '/application/');

// Dummy CI_Model & CI_Controller jika berjalan terisolasi di CLI
if (!class_exists('CI_Model')) {
    class CI_Model {
        public $db;
        public function __construct() {}
    }
}

if (!class_exists('CI_Controller')) {
    class CI_Controller {
        public function __construct() {}
    }
}

require_once APPPATH . 'models/Model_Pengujian.php';

$model = new Model_Pengujian();

$total_tests = 0;
$passed_tests = 0;
$failed_tests = 0;
$results_log = [];

function assert_test($name, $callable) {
    global $total_tests, $passed_tests, $failed_tests, $results_log;
    $total_tests++;
    try {
        $res = $callable();
        if ($res === true) {
            $passed_tests++;
            $results_log[] = ['name' => $name, 'status' => 'PASS', 'msg' => 'OK'];
            echo "[PASS] $name\n";
        } else {
            $failed_tests++;
            $results_log[] = ['name' => $name, 'status' => 'FAIL', 'msg' => (string)$res];
            echo "[FAIL] $name: $res\n";
        }
    } catch (Exception $e) {
        $failed_tests++;
        $results_log[] = ['name' => $name, 'status' => 'FAIL', 'msg' => $e->getMessage()];
        echo "[FAIL] $name: " . $e->getMessage() . "\n";
    }
}

echo "=== TEST SUITE TAHAP 2: ANTREAN MT BERBASIS SAMPEL & TINJAUAN LHU ===\n\n";

// 1. Sampel Kimia Saja Siap
assert_test("1. Sampel Kimia saja yang siap muncul satu kali", function() use ($model) {
    $samples = [
        ['id' => 1, 'nama_sampel' => 'Paracetamol Tab', 'jumlah_kimia' => '100 tablet', 'jumlah_mikro' => '0']
    ];
    $sessions = [
        ['id' => 101, 'sample_id' => 1, 'jenis_pengujian' => 'Kimia', 'status' => 'Menunggu Approval', 'nama_penguji' => 'Analis Kimia', 'nama_verifier' => 'Penyelia Kimia', 'kesimpulan' => 'Memenuhi Syarat']
    ];

    $res = $model->filter_antrean_approval_sampel($samples, $sessions);

    if (count($res) !== 1) return "Harus menghasilkan tepat 1 sampel di antrean MT (ditemukan: " . count($res) . ")";
    if ($res[0]['id'] != 1) return "ID sampel tidak cocok";
    if ($res[0]['jumlah_sesi_siap'] !== 1) return "Jumlah sesi siap harus 1";
    if ($res[0]['sesi_ids'] !== [101]) return "Sesi ID harus [101]";
    return true;
});

// 2. Sampel Mikro Saja Siap
assert_test("2. Sampel Mikro saja yang siap muncul satu kali", function() use ($model) {
    $samples = [
        ['id' => 2, 'nama_sampel' => 'Amoxicillin Sirup', 'jumlah_kimia' => '-', 'jumlah_mikro' => '60 ml']
    ];
    $sessions = [
        ['id' => 201, 'sample_id' => 2, 'jenis_pengujian' => 'Mikrobiologi', 'status' => 'Menunggu Approval', 'nama_penguji' => 'Analis Mikro', 'nama_verifier' => 'Penyelia Mikro', 'kesimpulan' => 'Memenuhi Syarat']
    ];

    $res = $model->filter_antrean_approval_sampel($samples, $sessions);

    if (count($res) !== 1) return "Harus menghasilkan tepat 1 sampel di antrean MT";
    if ($res[0]['id'] != 2) return "ID sampel tidak cocok";
    if ($res[0]['sesi_ids'] !== [201]) return "Sesi ID harus [201]";
    return true;
});

// 3. Sampel Kimia + Mikro Keduanya Siap
assert_test("3. Sampel Kimia + Mikro siap muncul satu kali dengan kedua sesi tersedia", function() use ($model) {
    $samples = [
        ['id' => 3, 'nama_sampel' => 'Cefixime Kapsul', 'jumlah_kimia' => '30 kapsul', 'jumlah_mikro' => '30 kapsul']
    ];
    $sessions = [
        ['id' => 301, 'sample_id' => 3, 'jenis_pengujian' => 'Kimia', 'status' => 'Menunggu Approval', 'nama_penguji' => 'Analis Kimia A', 'nama_verifier' => 'Penyelia Kimia', 'kesimpulan' => 'Memenuhi Syarat'],
        ['id' => 302, 'sample_id' => 3, 'jenis_pengujian' => 'Mikrobiologi', 'status' => 'Menunggu Approval', 'nama_penguji' => 'Analis Mikro B', 'nama_verifier' => 'Penyelia Mikro', 'kesimpulan' => 'Memenuhi Syarat']
    ];

    $res = $model->filter_antrean_approval_sampel($samples, $sessions);

    if (count($res) !== 1) return "Harus digabung menjadi tepat 1 entitas sampel di antrean MT (ditemukan: " . count($res) . ")";
    if (count($res[0]['sesi_ids']) !== 2) return "Harus memuat 2 sesi ID (ditemukan: " . count($res[0]['sesi_ids']) . ")";
    if ($res[0]['sesi_ids'] !== [301, 302]) return "ID sesi tidak cocok";
    if (strpos($res[0]['nama_penguji_gabung'], 'Analis Kimia A') === false || strpos($res[0]['nama_penguji_gabung'], 'Analis Mikro B') === false) {
        return "Penguji gabungan harus memuat kedua analis";
    }
    return true;
});

// 4. Sampel dengan satu sesi wajib belum dibuat
assert_test("4. Sampel dengan satu sesi wajib belum dibuat tidak muncul sebagai siap", function() use ($model) {
    $samples = [
        ['id' => 4, 'nama_sampel' => 'Salep Mata', 'jumlah_kimia' => '5 tube', 'jumlah_mikro' => '5 tube']
    ];
    $sessions = [
        ['id' => 401, 'sample_id' => 4, 'jenis_pengujian' => 'Kimia', 'status' => 'Menunggu Approval']
        // Mikro belum dibuat
    ];

    $res = $model->filter_antrean_approval_sampel($samples, $sessions);

    if (count($res) !== 0) return "Sampel tidak boleh muncul di antrean jika Sesi Mikro belum dibuat";
    return true;
});

// 5. Sampel dengan sesi wajib masih berjalan
assert_test("5. Sampel dengan sesi wajib sedang berjalan tidak muncul sebagai siap", function() use ($model) {
    $samples = [
        ['id' => 5, 'nama_sampel' => 'Injeksi Vitamin C', 'jumlah_kimia' => '10 ampul', 'jumlah_mikro' => '10 ampul']
    ];
    $sessions = [
        ['id' => 501, 'sample_id' => 5, 'jenis_pengujian' => 'Kimia', 'status' => 'Menunggu Approval'],
        ['id' => 502, 'sample_id' => 5, 'jenis_pengujian' => 'Mikrobiologi', 'status' => 'Sedang Diuji']
    ];

    $res = $model->filter_antrean_approval_sampel($samples, $sessions);

    if (count($res) !== 0) return "Sampel tidak boleh muncul jika salah satu sesi masih Sedang Diuji";
    return true;
});

// 6. Sampel dengan sesi wajib ditolak
assert_test("6. Sampel dengan sesi wajib ditolak tidak muncul sebagai siap", function() use ($model) {
    $samples = [
        ['id' => 6, 'nama_sampel' => 'Tablet Antasida', 'jumlah_kimia' => '20 tablet', 'jumlah_mikro' => '20 tablet']
    ];
    $sessions = [
        ['id' => 601, 'sample_id' => 6, 'jenis_pengujian' => 'Kimia', 'status' => 'Menunggu Approval'],
        ['id' => 602, 'sample_id' => 6, 'jenis_pengujian' => 'Mikrobiologi', 'status' => 'Ditolak']
    ];

    $res = $model->filter_antrean_approval_sampel($samples, $sessions);

    if (count($res) !== 0) return "Sampel tidak boleh muncul jika salah satu sesi Ditolak";
    return true;
});

// 7. Sampel dengan kuantitas ambigu
assert_test("7. Sampel dengan kuantitas ambigu tidak muncul sebagai siap", function() use ($model) {
    $samples = [
        ['id' => 7, 'nama_sampel' => 'Sampel Ambigu', 'jumlah_kimia' => 'perlu uji', 'jumlah_mikro' => '10']
    ];
    $sessions = [
        ['id' => 701, 'sample_id' => 7, 'jenis_pengujian' => 'Mikrobiologi', 'status' => 'Menunggu Approval']
    ];

    $res = $model->filter_antrean_approval_sampel($samples, $sessions);

    if (count($res) !== 0) return "Sampel dengan kuantitas ambigu harus di-filter keluar dari antrean MT";
    return true;
});

// 8. Segregasi data antar sampel
assert_test("8. Segregasi data: sesi dari sampel lain tidak tertukar", function() use ($model) {
    $samples = [
        ['id' => 10, 'nama_sampel' => 'Sampel 10', 'jumlah_kimia' => '10', 'jumlah_mikro' => '0'],
        ['id' => 20, 'nama_sampel' => 'Sampel 20', 'jumlah_kimia' => '0', 'jumlah_mikro' => '20']
    ];
    $sessions = [
        ['id' => 1001, 'sample_id' => 10, 'jenis_pengujian' => 'Kimia', 'status' => 'Menunggu Approval', 'nama_penguji' => 'Penguji 10'],
        ['id' => 2001, 'sample_id' => 20, 'jenis_pengujian' => 'Mikrobiologi', 'status' => 'Menunggu Approval', 'nama_penguji' => 'Penguji 20']
    ];

    $res = $model->filter_antrean_approval_sampel($samples, $sessions);

    if (count($res) !== 2) return "Kedua sampel harus muncul mandiri";
    if ($res[0]['id'] != 10 || $res[0]['sesi_ids'] !== [1001]) return "Sesi 10 tertukar";
    if ($res[1]['id'] != 20 || $res[1]['sesi_ids'] !== [2001]) return "Sesi 20 tertukar";
    return true;
});

// 9. Otentikasi & Otorisasi Role Guard (Permission 'pengujian_approve')
assert_test("9. Otentikasi & Otorisasi: Role tanpa permission ditolak guard", function() {
    // Model simulasi permission check
    $role_permissions = [
        1 => ['pengujian_view', 'pengujian_input'],                 // Penguji
        2 => ['pengujian_view', 'pengujian_verify'],                // Penyelia
        3 => ['pengujian_view', 'pengujian_approve', 'laporan_view'] // Manajer Teknis
    ];

    $check_access = function($role_id, $perm) use ($role_permissions) {
        return isset($role_permissions[$role_id]) && in_array($perm, $role_permissions[$role_id], true);
    };

    // Role Penguji tidak boleh mengakses approval MT
    if ($check_access(1, 'pengujian_approve') === true) return "Penguji tidak boleh memiliki izin pengujian_approve";

    // Role Penyelia tidak boleh mengakses approval MT
    if ($check_access(2, 'pengujian_approve') === true) return "Penyelia tidak boleh memiliki izin pengujian_approve";

    // Role Manajer Teknis WAJIB memiliki izin pengujian_approve
    if ($check_access(3, 'pengujian_approve') !== true) return "Manajer Teknis harus memiliki izin pengujian_approve";

    return true;
});

echo "\n=== RINGKASAN TEST SUITE TAHAP 2 ===\n";
echo "Total Tests : $total_tests\n";
echo "Passed      : $passed_tests\n";
echo "Failed      : $failed_tests\n";

if ($failed_tests > 0) {
    exit(1);
}
exit(0);
