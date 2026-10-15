<?php
// scratch/check_active_db_untouched.php
$mysqli = new mysqli('localhost', 'root', '', 'limsku');
if ($mysqli->connect_error) die('DB connect failed');

$res = $mysqli->query("SELECT COUNT(*) AS total FROM reagents");
$total = $res->fetch_assoc()['total'];

$res = $mysqli->query("SELECT COUNT(*) AS zero_dates FROM reagents WHERE expired_date = '0000-00-00'");
$zero_dates = $res->fetch_assoc()['zero_dates'];

$res = $mysqli->query("SELECT COUNT(*) AS null_dates FROM reagents WHERE expired_date IS NULL");
$null_dates = $res->fetch_assoc()['null_dates'];

$res = $mysqli->query("SELECT COUNT(*) AS shifted_sample FROM reagents WHERE id IN (8,9,10,11) AND nama_barang LIKE '%VIAL'");
$shifted_sample = $res->fetch_assoc()['shifted_sample'];

echo "=== STATUS DATABASE AKTIF (limsku) ===\n";
echo "Total Records: $total (Harus 389)\n";
echo "Zero Dates   : $zero_dates (Harus 68)\n";
echo "Null Dates   : $null_dates (Harus 1)\n";
echo "Shifted Sample (ID 8-11 'VIAL'): $shifted_sample (Harus 4)\n";

$mysqli->close();
