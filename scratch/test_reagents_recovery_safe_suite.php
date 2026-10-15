<?php
// scratch/test_reagents_recovery_safe_suite.php
//
// SUITE PENGUJIAN OTOMATIS: REAGENTS RECOVERY & ROLLBACK SAFE RUNNER
// Menjalankan seluruh skenario pengujian pada database terisolasi (limsku_test_recovery_suite).
// Menjamin database limsku aktif TIDAK TERKENA MUTASI.

require_once __DIR__ . '/execute_reagents_recovery_safe.php';

$test_db = 'limsku_test_recovery_suite';
$backup_file = 'C:\\xampp\\backups\\LIMSKU\\limsku_backup_final_batch04_20261015_094500.sql';

if (!file_exists($backup_file)) {
    die("Backup file tidak ditemukan!\n");
}

function reset_test_db($db_name, $backup_file) {
    $mysqli = new mysqli('localhost', 'root', '', '');
    $mysqli->query("DROP DATABASE IF EXISTS `$db_name`");
    $mysqli->query("CREATE DATABASE `$db_name` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
    $mysqli->close();

    $cmd = "C:\\xampp\\mysql\\bin\\mysql.exe -u root $db_name < \"$backup_file\"";
    exec($cmd, $out, $ret);
    if ($ret !== 0) {
        throw new Exception("Gagal restore fixture ke $db_name!");
    }
}

echo "=== MEMULAI TEST SUITE REAGENTS RECOVERY SAFE RUNNER ===\n\n";
$tests_passed = 0;
$tests_total = 0;

function assert_test($name, $condition) {
    global $tests_passed, $tests_total;
    $tests_total++;
    if ($condition) {
        $tests_passed++;
        echo "[PASS] Test $tests_total: $name\n";
    } else {
        echo "[FAIL] Test $tests_total: $name\n";
    }
}

// -------------------------------------------------------------
// TEST 1: Database aktif 'limsku' DITOLAK saat APPLY tanpa izin
// -------------------------------------------------------------
try {
    $mysqli = new mysqli('localhost', 'root', '', 'limsku');
    $runner = new ReagentsRecoverySafeRunner($mysqli, 'limsku', true, false);
    $runner->runRecovery();
    assert_test("Database aktif ditolak saat mode APPLY", false);
} catch (Exception $e) {
    assert_test("Database aktif ditolak saat mode APPLY", strpos($e->getMessage(), 'BLOCKED') !== false);
}

// -------------------------------------------------------------
// TEST 2: DRY-RUN pada database uji terisolasi (Lolos tanpa mutasi)
// -------------------------------------------------------------
reset_test_db($test_db, $backup_file);
$test_mysqli = new mysqli('localhost', 'root', '', $test_db);
$runner = new ReagentsRecoverySafeRunner($test_mysqli, $test_db, false, false);
$res = $runner->runRecovery();
assert_test("DRY-RUN sukses pada database terisolasi", $res === true);

// Pastikan DB uji belum termutasi saat DRY-RUN
$r = $test_mysqli->query("SELECT nama_barang FROM reagents WHERE id = 8")->fetch_assoc();
assert_test("DRY-RUN tidak mengubah data di database", $r['nama_barang'] === '3 VIAL');

// -------------------------------------------------------------
// TEST 3: Precondition GAGAL jika Row Count berbeda
// -------------------------------------------------------------
$test_mysqli->query("DELETE FROM reagents WHERE id = 7"); // Buat jadi 388 rows
try {
    $runner = new ReagentsRecoverySafeRunner($test_mysqli, $test_db, true, false);
    $runner->runRecovery();
    assert_test("Precondition gagal jika row count != 389", false);
} catch (Exception $e) {
    assert_test("Precondition gagal jika row count != 389", strpos($e->getMessage(), 'PRECONDITION FAIL: Total record') !== false);
}

// -------------------------------------------------------------
// TEST 4: Precondition GAGAL jika nilai lama salah satu record berubah
// -------------------------------------------------------------
reset_test_db($test_db, $backup_file);
$test_mysqli = new mysqli('localhost', 'root', '', $test_db);
$test_mysqli->query("UPDATE reagents SET nama_barang = 'MODIFIED_NAME' WHERE id = 8");
try {
    $runner = new ReagentsRecoverySafeRunner($test_mysqli, $test_db, true, false);
    $runner->runRecovery();
    assert_test("Precondition gagal jika nilai lama telah dimodifikasi", false);
} catch (Exception $e) {
    assert_test("Precondition gagal jika nilai lama telah dimodifikasi", strpos($e->getMessage(), 'PRECONDITION FAIL') !== false);
}

// -------------------------------------------------------------
// TEST 5: APPLY Sukses pada database uji terisolasi
// -------------------------------------------------------------
reset_test_db($test_db, $backup_file);
$test_mysqli = new mysqli('localhost', 'root', '', $test_db);
$runner = new ReagentsRecoverySafeRunner($test_mysqli, $test_db, true, false);
$res = $runner->runRecovery();
assert_test("APPLY recovery sukses pada database terisolasi", $res === true);

// Verifikasi postcondition di DB uji
$r8 = $test_mysqli->query("SELECT * FROM reagents WHERE id = 8")->fetch_assoc();
assert_test("ID 8 nama_barang pulih jadi 'Asam Aminobenzoat (2070051)'", $r8['nama_barang'] === 'Asam Aminobenzoat (2070051)');
assert_test("ID 8 nama_reagen tersinkronisasi", $r8['nama_reagen'] === 'Asam Aminobenzoat (2070051)');
assert_test("ID 8 stok pulih jadi '3 VIAL'", $r8['stok'] === '3 VIAL');
assert_test("ID 8 jenis_barang pulih jadi 'Baku Pembanding'", $r8['jenis_barang'] === 'Baku Pembanding');

$r48 = $test_mysqli->query("SELECT * FROM reagents WHERE id = 48")->fetch_assoc();
assert_test("ID 48 serial number pulih jadi expired_date '2026-07-31'", $r48['expired_date'] === '2026-07-31');
assert_test("ID 48 jenis_barang pulih jadi 'Media Mikrobiologi'", $r48['jenis_barang'] === 'Media Mikrobiologi');

$r_zero = $test_mysqli->query("SELECT COUNT(*) as c FROM reagents WHERE expired_date = '0000-00-00'")->fetch_assoc();
assert_test("Tidak ada lagi expired_date '0000-00-00'", (int)$r_zero['c'] === 0);

// -------------------------------------------------------------
// TEST 6: Eksekusi Ulang Recovery (Idempotency Protection)
// -------------------------------------------------------------
try {
    $runner_again = new ReagentsRecoverySafeRunner($test_mysqli, $test_db, true, false);
    $runner_again->runRecovery();
    assert_test("Eksekusi ulang recovery ditolak secara aman oleh precondition", false);
} catch (Exception $e) {
    // Karena sudah pulih, precondition akan menolak (zero date sudah 0, bukan 68)
    assert_test("Eksekusi ulang recovery ditolak secara aman oleh precondition", strpos($e->getMessage(), 'PRECONDITION FAIL') !== false);
}

// -------------------------------------------------------------
// TEST 7: ROLLBACK Sukses pada data normal
// -------------------------------------------------------------
$runner_roll = new ReagentsRecoverySafeRunner($test_mysqli, $test_db, true, false);
$res_roll = $runner_roll->runRollback();
assert_test("APPLY rollback sukses", $res_roll === true);

$r8_roll = $test_mysqli->query("SELECT * FROM reagents WHERE id = 8")->fetch_assoc();
assert_test("Rollback ID 8 nama_barang kembali jadi '3 VIAL'", $r8_roll['nama_barang'] === '3 VIAL');
assert_test("Rollback ID 8 nama_reagen kembali jadi '3 VIAL'", $r8_roll['nama_reagen'] === '3 VIAL');

// -------------------------------------------------------------
// TEST 8: ROLLBACK dengan Optimistic Concurrency (Melewati record yang diedit pasca-recovery)
// -------------------------------------------------------------
// Lakukan recovery lagi di clean DB
reset_test_db($test_db, $backup_file);
$test_mysqli = new mysqli('localhost', 'root', '', $test_db);
$runner = new ReagentsRecoverySafeRunner($test_mysqli, $test_db, true, false);
$runner->runRecovery();

// Simulasikan user mengedit ID 8 pasca recovery
$test_mysqli->query("UPDATE reagents SET nama_barang = 'NAMA DIUBAH PENGGUNA' WHERE id = 8");
// Dan user mengedit expired_date salah satu zero-date record (misal ID 13)
$test_mysqli->query("UPDATE reagents SET expired_date = '2030-01-01' WHERE id = 13");

// Jalankan rollback
$runner_roll = new ReagentsRecoverySafeRunner($test_mysqli, $test_db, true, false);
$runner_roll->runRollback();

$r8_after = $test_mysqli->query("SELECT nama_barang FROM reagents WHERE id = 8")->fetch_assoc();
assert_test("Rollback melewati ID 8 karena ada perubahan user (optimistic lock)", $r8_after['nama_barang'] === 'NAMA DIUBAH PENGGUNA');

$r13_after = $test_mysqli->query("SELECT expired_date FROM reagents WHERE id = 13")->fetch_assoc();
assert_test("Rollback tidak menimpa expired_date ID 13 yang sudah diisi user", $r13_after['expired_date'] === '2030-01-01');

// -------------------------------------------------------------
// TEST 9: Verifikasi Database Aktif 'limsku' TIDAK TERSENTUH
// -------------------------------------------------------------
$active_mysqli = new mysqli('localhost', 'root', '', 'limsku');
$c_total = $active_mysqli->query("SELECT COUNT(*) as c FROM reagents")->fetch_assoc()['c'];
$c_zero = $active_mysqli->query("SELECT COUNT(*) as c FROM reagents WHERE expired_date = '0000-00-00'")->fetch_assoc()['c'];
$c_null = $active_mysqli->query("SELECT COUNT(*) as c FROM reagents WHERE expired_date IS NULL")->fetch_assoc()['c'];
$c_vial = $active_mysqli->query("SELECT COUNT(*) as c FROM reagents WHERE id = 8 AND nama_barang = '3 VIAL'")->fetch_assoc()['c'];

assert_test("Database aktif limsku total record tetap 389", (int)$c_total === 389);
assert_test("Database aktif limsku zero-date tetap 68", (int)$c_zero === 68);
assert_test("Database aktif limsku null-date tetap 66", (int)$c_null === 66);
assert_test("Database aktif limsku ID 8 tetap '3 VIAL'", (int)$c_vial === 1);

// Cleanup isolated database
$cleanup = new mysqli('localhost', 'root', '', '');
$cleanup->query("DROP DATABASE IF EXISTS `$test_db`");
$cleanup->close();

echo "\n=======================================================\n";
echo "HASIL AKHIR TEST SUITE: $tests_passed / $tests_total TESTS PASSED\n";
echo "=======================================================\n";
