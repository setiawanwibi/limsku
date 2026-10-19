<?php
/**
 * Isolated Automated Test Suite for Testing Session Requirements & Completeness Evaluation
 * 
 * Tests:
 * 1. Kimia saja (jumlah_kimia > 0, jumlah_mikro kosong/0) -> siap jika Sesi Kimia 'Menunggu Approval'.
 * 2. Mikro saja (jumlah_mikro > 0, jumlah_kimia kosong/0) -> siap jika Sesi Mikro 'Menunggu Approval'.
 * 3. Keduanya lengkap (Kimia & Mikro > 0) -> siap jika KEDUA sesi 'Menunggu Approval'.
 * 4. Keduanya wajib, tetapi salah satu sesi belum dibuat -> BELUM_LENGKAP (fail-closed).
 * 5. Keduanya wajib, salah satu sesi masih 'Sedang Diuji' / 'Menunggu Verifikasi' -> BELUM_LENGKAP.
 * 6. Salah satu sesi ditolak Penyelia ('Ditolak') -> BELUM_LENGKAP.
 * 7. Kuantitas ambigu pada jumlah_kimia ('ada', '?') -> KUANTITAS_AMBIGU (fail-closed).
 * 8. Kuantitas ambigu pada jumlah_mikro ('perlu') -> KUANTITAS_AMBIGU (fail-closed).
 * 9. Kuantitas satuan valid ('250 gram', '10 tablet', '1 sampel') diparsing secara benar.
 * 10. Nilai nol dengan satuan ('0 gram', '0 botol') dianggap 0 (tidak wajib).
 * 11. Sampel tanpa kebutuhan uji sama sekali (0 dan 0) -> TIDAK_ADA_PENGUJIAN_WAJIB.
 * 12. Sampel kosong / null -> INVALID_SAMPLE.
 */

define('BASEPATH', 'true');
define('APPPATH', dirname(__DIR__) . '/application/');

// Dummy CI_Model base class jika berjalan di CLI tanpa framework bootstrap
if (!class_exists('CI_Model')) {
    class CI_Model {
        public $db;
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

echo "=== TEST SUITE: EVALUASI KELENGKAPAN SESI PENGUJIAN LHU ===\n\n";

// 1. Parsing Kuantitas Dasar & Konservatif
assert_test("Parsing kuantitas numerik bulat dan desimal", function() use ($model) {
    $r1 = $model->evaluasi_kebutuhan_pengujian("30.0");
    if (!$r1['is_wajib'] || $r1['qty'] != 30.0 || $r1['is_ambiguous']) return "30.0 harus wajib dan bernilai 30";

    $r2 = $model->evaluasi_kebutuhan_pengujian(5);
    if (!$r2['is_wajib'] || $r2['qty'] != 5.0) return "5 harus wajib";

    $r0 = $model->evaluasi_kebutuhan_pengujian("0.0");
    if ($r0['is_wajib'] || $r0['qty'] != 0.0) return "0.0 tidak boleh wajib";

    return true;
});

assert_test("Parsing kuantitas kosong, NULL, dan strip", function() use ($model) {
    foreach ([NULL, "", "   ", "-", "--"] as $val) {
        $r = $model->evaluasi_kebutuhan_pengujian($val);
        if ($r['is_wajib'] || $r['is_ambiguous'] || $r['qty'] != 0.0) {
            return "Nilai " . var_export($val, true) . " harus tidak wajib dan tidak ambigu";
        }
    }
    return true;
});

assert_test("Parsing kuantitas dengan teks satuan", function() use ($model) {
    $r1 = $model->evaluasi_kebutuhan_pengujian("1 sampel");
    if (!$r1['is_wajib'] || $r1['qty'] != 1.0) return "'1 sampel' harus wajib 1.0";

    $r2 = $model->evaluasi_kebutuhan_pengujian("250 gram");
    if (!$r2['is_wajib'] || $r2['qty'] != 250.0) return "'250 gram' harus wajib 250.0";

    $r3 = $model->evaluasi_kebutuhan_pengujian("10 tablet");
    if (!$r3['is_wajib'] || $r3['qty'] != 10.0) return "'10 tablet' harus wajib 10.0";

    $r0 = $model->evaluasi_kebutuhan_pengujian("0 botol");
    if ($r0['is_wajib'] || $r0['qty'] != 0.0) return "'0 botol' harus tidak wajib bernilai 0";

    return true;
});

assert_test("Parsing kuantitas teks tak berpola / ambigu -> fail closed", function() use ($model) {
    $ambigu_list = ["ada", "perlu", "ya", "uji kimia", "?", "sedikit"];
    foreach ($ambigu_list as $val) {
        $r = $model->evaluasi_kebutuhan_pengujian($val);
        if (!$r['is_ambiguous'] || $r['is_wajib']) {
            return "Nilai ambigu " . var_export($val, true) . " harus is_ambiguous = true dan is_wajib = false";
        }
    }
    return true;
});

// 2. Evaluasi Sesi Kelayakan Pengajuan MT
assert_test("Kasus Kimia Saja: Siap jika Sesi Kimia Menunggu Approval", function() use ($model) {
    $sampel = ['id' => 101, 'jumlah_kimia' => '100 gram', 'jumlah_mikro' => '0'];
    $sesi_list = [
        ['id' => 1, 'sample_id' => 101, 'jenis_pengujian' => 'Kimia', 'status' => 'Menunggu Approval']
    ];
    $res = $model->evaluasi_kelengkapan_sesi_sampel($sampel, $sesi_list);
    if (!$res['siap_mt'] || $res['status_agregat'] !== 'SIAP_APPROVAL_MT') {
        return "Harus siap_mt = true untuk Kimia saja yang sudah Menunggu Approval";
    }
    if (!$res['wajib_kimia'] || $res['wajib_mikro']) {
        return "Kebutuhan wajib harus hanya Kimia";
    }
    return true;
});

assert_test("Kasus Mikrobiologi Saja: Siap jika Sesi Mikro Menunggu Approval", function() use ($model) {
    $sampel = ['id' => 102, 'jumlah_kimia' => '-', 'jumlah_mikro' => '30.0'];
    $sesi_list = [
        ['id' => 2, 'sample_id' => 102, 'jenis_pengujian' => 'Mikrobiologi', 'status' => 'Menunggu Approval']
    ];
    $res = $model->evaluasi_kelengkapan_sesi_sampel($sampel, $sesi_list);
    if (!$res['siap_mt'] || $res['status_agregat'] !== 'SIAP_APPROVAL_MT') {
        return "Harus siap_mt = true untuk Mikro saja yang sudah Menunggu Approval";
    }
    if ($res['wajib_kimia'] || !$res['wajib_mikro']) {
        return "Kebutuhan wajib harus hanya Mikrobiologi";
    }
    return true;
});

assert_test("Kasus Keduanya Wajib: Siap jika KEDUA sesi berstatus Menunggu Approval", function() use ($model) {
    $sampel = ['id' => 103, 'jumlah_kimia' => '10 tablet', 'jumlah_mikro' => '20 tablet'];
    $sesi_list = [
        ['id' => 10, 'sample_id' => 103, 'jenis_pengujian' => 'Kimia', 'status' => 'Menunggu Approval'],
        ['id' => 11, 'sample_id' => 103, 'jenis_pengujian' => 'Mikrobiologi', 'status' => 'Menunggu Approval']
    ];
    $res = $model->evaluasi_kelengkapan_sesi_sampel($sampel, $sesi_list);
    if (!$res['siap_mt'] || $res['status_agregat'] !== 'SIAP_APPROVAL_MT') {
        return "Harus siap_mt = true jika kedua sesi telah Menunggu Approval";
    }
    return true;
});

assert_test("Kasus Fleksibel Pengujian: Salah satu sesi (Kimia) siap Menunggu Approval meski Mikro belum dibuat -> siap_mt = true", function() use ($model) {
    $sampel = ['id' => 104, 'jumlah_kimia' => '50 gram', 'jumlah_mikro' => '30 gram'];
    $sesi_list = [
        ['id' => 20, 'sample_id' => 104, 'jenis_pengujian' => 'Kimia', 'status' => 'Menunggu Approval']
        // Mikro belum dibuat
    ];
    $res = $model->evaluasi_kelengkapan_sesi_sampel($sampel, $sesi_list);
    if (!$res['siap_mt'] || $res['status_agregat'] !== 'SIAP_APPROVAL_MT') {
        return "Harus siap_mt = true karena sesi Kimia sudah Menunggu Approval dan tidak ada sesi yang konflik/berjalan";
    }
    if ($res['status_sesi']['Mikrobiologi']['status'] !== 'BELUM_DIBUAT') {
        return "Status sesi mikro harus tetap tercatat BELUM_DIBUAT";
    }
    return true;
});

assert_test("Kasus Keduanya Wajib: Sesi Mikro MASIH BERJALAN ('Sedang Diuji' / 'Menunggu Verifikasi') -> fail-closed", function() use ($model) {
    $sampel = ['id' => 105, 'jumlah_kimia' => '10 strip', 'jumlah_mikro' => '10 strip'];
    $sesi_list = [
        ['id' => 30, 'sample_id' => 105, 'jenis_pengujian' => 'Kimia', 'status' => 'Menunggu Approval'],
        ['id' => 31, 'sample_id' => 105, 'jenis_pengujian' => 'Mikrobiologi', 'status' => 'Sedang Diuji']
    ];
    $res = $model->evaluasi_kelengkapan_sesi_sampel($sampel, $sesi_list);
    if ($res['siap_mt'] || $res['status_agregat'] !== 'BELUM_LENGKAP') {
        return "Harus siap_mt = false jika salah satu sesi masih Sedang Diuji";
    }
    return true;
});

assert_test("Kasus Keduanya Wajib: Sesi Kimia DITOLAK Penyelia -> fail-closed", function() use ($model) {
    $sampel = ['id' => 106, 'jumlah_kimia' => '10 strip', 'jumlah_mikro' => '10 strip'];
    $sesi_list = [
        ['id' => 40, 'sample_id' => 106, 'jenis_pengujian' => 'Kimia', 'status' => 'Ditolak'],
        ['id' => 41, 'sample_id' => 106, 'jenis_pengujian' => 'Mikrobiologi', 'status' => 'Menunggu Approval']
    ];
    $res = $model->evaluasi_kelengkapan_sesi_sampel($sampel, $sesi_list);
    if ($res['siap_mt'] || $res['status_agregat'] !== 'BELUM_LENGKAP') {
        return "Harus siap_mt = false jika ada sesi Ditolak";
    }
    if ($res['status_sesi']['Kimia']['status'] !== 'Ditolak') {
        return "Status sesi kimia harus tercatat Ditolak";
    }
    return true;
});

assert_test("Kasus Kuantitas Ambigu pada Sampel -> KUANTITAS_AMBIGU fail-closed", function() use ($model) {
    $sampel = ['id' => 107, 'jumlah_kimia' => 'perlu uji', 'jumlah_mikro' => '30.0'];
    $sesi_list = [
        ['id' => 50, 'sample_id' => 107, 'jenis_pengujian' => 'Mikrobiologi', 'status' => 'Menunggu Approval']
    ];
    $res = $model->evaluasi_kelengkapan_sesi_sampel($sampel, $sesi_list);
    if ($res['siap_mt'] || $res['status_agregat'] !== 'KUANTITAS_AMBIGU' || !$res['is_ambigu']) {
        return "Harus menghasilkan status KUANTITAS_AMBIGU dan tidak siap MT";
    }
    return true;
});

assert_test("Kasus Sampel Tanpa Pengujian Wajib (0 dan 0)", function() use ($model) {
    $sampel = ['id' => 108, 'jumlah_kimia' => '0', 'jumlah_mikro' => '-'];
    $res = $model->evaluasi_kelengkapan_sesi_sampel($sampel, []);
    if ($res['siap_mt'] || $res['status_agregat'] !== 'TIDAK_ADA_PENGUJIAN_WAJIB') {
        return "Harus TIDAK_ADA_PENGUJIAN_WAJIB";
    }
    return true;
});

echo "\n=== RINGKASAN TEST SUITE ===\n";
echo "Total Tests : $total_tests\n";
echo "Passed      : $passed_tests\n";
echo "Failed      : $failed_tests\n";

if ($failed_tests > 0) {
    exit(1);
}
exit(0);
