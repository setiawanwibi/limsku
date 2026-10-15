<?php
$backup_file = 'C:\\xampp\\backups\\LIMSKU\\limsku_backup_final_batch04_20261015_094500.sql';
if (!file_exists($backup_file)) die("File not found");

$size = filesize($backup_file);
$sha256 = hash_file('sha256', $backup_file);
$mtime = date('Y-m-d H:i:s', filemtime($backup_file));

echo "Backup File : $backup_file\n";
echo "Ukuran      : " . number_format($size, 0, ',', '.') . " bytes\n";
echo "Timestamp   : $mtime\n";
echo "SHA-256     : $sha256\n";

// Restore test to isolated database
$test_db = 'limsku_restore_test_final_batch';
$mysqli = new mysqli('localhost', 'root', '', '');
$mysqli->query("DROP DATABASE IF EXISTS `$test_db`");
$mysqli->query("CREATE DATABASE `$test_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
$mysqli->close();

$cmd = "C:\\xampp\\mysql\\bin\\mysql.exe -u root $test_db < \"$backup_file\"";
exec($cmd, $out, $ret);

if ($ret !== 0) {
    echo "RESTORE TEST FAILED (exit code $ret)\n";
} else {
    echo "RESTORE TEST SUCCESSFUL!\n";
    $test_mysqli = new mysqli('localhost', 'root', '', $test_db);
    $res = $test_mysqli->query("SHOW TABLES");
    echo "Jumlah Tabel: " . $res->num_rows . "\n";
    $res = $test_mysqli->query("SELECT COUNT(*) as c FROM reagents");
    echo "Jumlah Record reagents: " . $res->fetch_assoc()['c'] . " (Expected 389)\n";
    $res = $test_mysqli->query("SELECT COUNT(*) as c FROM reagents WHERE expired_date = '0000-00-00'");
    echo "Jumlah Zero-Date reagents: " . $res->fetch_assoc()['c'] . " (Expected 68)\n";
    $res = $test_mysqli->query("SELECT COUNT(*) as c FROM reagents WHERE expired_date IS NULL");
    echo "Jumlah NULL date reagents: " . $res->fetch_assoc()['c'] . " (Expected 66)\n";
    $test_mysqli->close();

    $cleanup = new mysqli('localhost', 'root', '', '');
    $cleanup->query("DROP DATABASE IF EXISTS `$test_db`");
    $cleanup->close();
    echo "Isolated test DB dropped successfully.\n";
}
