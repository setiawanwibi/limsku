<?php
// backup_limsku.php
$host = '127.0.0.1';
$user = 'root';
$pass = '';
$db   = 'limsku';

$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_error) {
    die("[FATAL] Gagal koneksi database: " . $mysqli->connect_error . "\n");
}

$date = date('Ymd_His');
$backup_file = "C:/xampp/backups/LIMSKU/limsku_backup_{$date}.sql";

$handle = fopen($backup_file, 'w');
if (!$handle) {
    die("[FATAL] Gagal membuka file backup untuk penulisan: $backup_file\n");
}

fwrite($handle, "-- LIMSKU FULL DATABASE BACKUP\n-- Generated at: " . date('Y-m-d H:i:s') . "\n-- Host: $host | Database: $db\n\n");
fwrite($handle, "SET FOREIGN_KEY_CHECKS=0;\nSET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';\n\n");

$tables = [];
$res = $mysqli->query("SHOW TABLES");
while ($row = $res->fetch_row()) {
    $tables[] = $row[0];
}

foreach ($tables as $table) {
    // Drop table
    fwrite($handle, "-- Table structure for `$table`\n");
    fwrite($handle, "DROP TABLE IF EXISTS `$table`;\n");
    
    // Create table
    $res_create = $mysqli->query("SHOW CREATE TABLE `$table`");
    $row_create = $res_create->fetch_row();
    fwrite($handle, $row_create[1] . ";\n\n");
    
    // Table data
    $res_data = $mysqli->query("SELECT * FROM `$table`");
    $num_rows = $res_data->num_rows;
    if ($num_rows > 0) {
        fwrite($handle, "-- Dumping data for `$table` ($num_rows rows)\n");
        fwrite($handle, "INSERT INTO `$table` VALUES \n");
        $first = true;
        while ($row = $res_data->fetch_row()) {
            if (!$first) {
                fwrite($handle, ",\n");
            }
            $escaped = array_map(function($val) use ($mysqli) {
                if ($val === null) return 'NULL';
                return "'" . $mysqli->real_escape_string($val) . "'";
            }, $row);
            fwrite($handle, "(" . implode(", ", $escaped) . ")");
            $first = false;
        }
        fwrite($handle, ";\n\n");
    }
}

fwrite($handle, "SET FOREIGN_KEY_CHECKS=1;\n");
fclose($handle);

$filesize = filesize($backup_file);
echo "[SUCCESS] Backup berhasil dibuat!\n";
echo "File: $backup_file\n";
echo "Size: " . number_format($filesize) . " bytes\n";

$mysqli->close();
