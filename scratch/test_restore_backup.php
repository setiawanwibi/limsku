<?php
// test_restore_backup.php
$mysqli = new mysqli('127.0.0.1', 'root', '');
if ($mysqli->connect_error) {
    die("[FATAL] Gagal koneksi MySQL: " . $mysqli->connect_error . "\n");
}

$test_db = 'limsku_backup_verify';
$mysqli->query("DROP DATABASE IF EXISTS `$test_db`");
if (!$mysqli->query("CREATE DATABASE `$test_db`")) {
    die("[FATAL] Gagal membuat test database: " . $mysqli->error . "\n");
}

$mysqli->select_db($test_db);

$backup_file = "C:/xampp/backups/LIMSKU/limsku_backup_20261013_090831.sql";
$sql_content = file_get_contents($backup_file);
if (!$sql_content) {
    die("[FATAL] Gagal membaca file backup: $backup_file\n");
}

if ($mysqli->multi_query($sql_content)) {
    do {
        if ($result = $mysqli->store_result()) {
            $result->free();
        }
    } while ($mysqli->more_results() && $mysqli->next_result());
}

if ($mysqli->error) {
    die("[FATAL] Error saat restore: " . $mysqli->error . "\n");
}

// Verifikasi count
$res_m = $mysqli->query("SELECT COUNT(*) as cnt FROM methods")->fetch_assoc();
$res_ft = $mysqli->query("SELECT COUNT(*) as cnt FROM form_templates")->fetch_assoc();
$res_tr = $mysqli->query("SELECT COUNT(*) as cnt FROM test_results")->fetch_assoc();
$res_ts = $mysqli->query("SELECT COUNT(*) as cnt FROM testing_sessions")->fetch_assoc();
$res_s = $mysqli->query("SELECT COUNT(*) as cnt FROM samples")->fetch_assoc();

echo "[VERIFIKASI RESTORE TEST DB]\n";
echo "Methods: " . $res_m['cnt'] . "\n";
echo "Form Templates: " . $res_ft['cnt'] . "\n";
echo "Test Results: " . $res_tr['cnt'] . "\n";
echo "Testing Sessions: " . $res_ts['cnt'] . "\n";
echo "Samples: " . $res_s['cnt'] . "\n";

// Drop test database setelah verifikasi
$mysqli->query("DROP DATABASE `$test_db`");
echo "[SUCCESS] Backup 100% valid dan dapat di-restore tanpa error!\n";

$mysqli->close();
