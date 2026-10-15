<?php
// scratch/batch03_verify_backup.php
$backup_file = 'C:\\xampp\\backups\\LIMSKU\\limsku_backup_pre_batch03_recovery_20261015_092500.sql';

if (!file_exists($backup_file)) {
    die("File backup tidak ditemukan: $backup_file\n");
}

$size = filesize($backup_file);
$sha256 = hash_file('sha256', $backup_file);
$mtime = date('Y-m-d H:i:s', filemtime($backup_file));

echo "File       : $backup_file\n";
echo "Ukuran     : " . number_format($size, 0, ',', '.') . " bytes\n";
echo "Timestamp  : $mtime\n";
echo "SHA-256    : $sha256\n";

// Analisis isi dump
$content = file_get_contents($backup_file);

// Hitung tabel
preg_match_all('/CREATE TABLE `?([^`\s]+)`?/i', $content, $table_matches);
$tables = array_unique($table_matches[1]);
echo "Jumlah Tabel Ditemukan: " . count($tables) . "\n";
echo "Daftar Tabel: " . implode(', ', $tables) . "\n";

// Hitung record pada tabel reagents
// Format INSERT INTO `reagents` VALUES (...), (...);
// Mari hitung baris atau lakukan restore ke temporary database terisolasi
preg_match('/INSERT INTO `reagents` VALUES (.*?);/s', $content, $insert_reagents);
if (!empty($insert_reagents[1])) {
    // Parser sederhana untuk menghitung record dalam bulk insert
    // Setiap record dipisahkan oleh ),(
    // Hitung kemunculan substring "),(", plus 1
    $records_count = preg_match_all('/\),\(/', $insert_reagents[1]) + 1;
    echo "Jumlah Record `reagents` dalam Dump: $records_count\n";
} else {
    echo "Peringatan: INSERT INTO reagents tidak ditemukan secara langsung!\n";
}

// RESTORE TEST KE DATABASE TERPISAH
echo "\n=== RESTORE-TEST KE DATABASE ISOLASI ===\n";
$test_db_name = 'limsku_restore_test_gate';

$mysqli = new mysqli('localhost', 'root', '', '');
if ($mysqli->connect_error) {
    die("Koneksi MySQL gagal: " . $mysqli->connect_error . "\n");
}

// 1. Pastikan database isolasi bersih dan terpisah
$mysqli->query("DROP DATABASE IF EXISTS `$test_db_name`");
if (!$mysqli->query("CREATE DATABASE `$test_db_name` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci")) {
    die("Gagal membuat database test: " . $mysqli->error . "\n");
}
$mysqli->close();

// 2. Eksekusi restore ke database test menggunakan CLI mysql
$cmd = "C:\\xampp\\mysql\\bin\\mysql.exe -u root $test_db_name < \"$backup_file\"";
exec($cmd, $out, $ret);

if ($ret !== 0) {
    echo "RESTORE FAILED dengan exit code: $ret\n";
} else {
    echo "Restore ke database test `$test_db_name` BERHASIL!\n";

    // 3. Verifikasi isi di database test
    $test_mysqli = new mysqli('localhost', 'root', '', $test_db_name);
    if ($test_mysqli->connect_error) {
        die("Koneksi ke test DB gagal: " . $test_mysqli->connect_error . "\n");
    }

    $res = $test_mysqli->query("SELECT COUNT(*) AS total FROM reagents");
    $total_reagents = $res->fetch_assoc()['total'];

    $res = $test_mysqli->query("SELECT COUNT(*) AS zero_dates FROM reagents WHERE expired_date = '0000-00-00'");
    $zero_dates = $res->fetch_assoc()['zero_dates'];

    $res = $test_mysqli->query("SHOW TABLES");
    $test_tables_count = $res->num_rows;

    echo "Hasil Verifikasi Database Uji:\n";
    echo " - Total Tabel: $test_tables_count\n";
    echo " - Total Record `reagents`: $total_reagents (Harus 389)\n";
    echo " - Total Zero-Date `reagents`: $zero_dates (Harus 68)\n";

    $test_mysqli->close();

    // 4. Bersihkan database uji setelah verifikasi sukses
    $cleanup_mysqli = new mysqli('localhost', 'root', '', '');
    $cleanup_mysqli->query("DROP DATABASE IF EXISTS `$test_db_name`");
    $cleanup_mysqli->close();
    echo "Database isolasi pengujian telah dibersihkan (DROP) kembali.\n";
}
