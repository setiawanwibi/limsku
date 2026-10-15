<?php
define('BASEPATH', 'dummy');
require_once __DIR__ . '/../application/libraries/SimpleXLSX.php';

$path = 'C:/Users/ASUS/OneDrive/Dokumen/Magang BPOM/Data BPOM/Data Reagen/Data Reagen Padat.xlsx';
$xlsx = SimpleXLSX::parse($path);
$rows = $xlsx->rows();
echo "Header: \n";
print_r($rows[0]);
echo "Baris 22 (Index 21/22): \n";
for ($i = 20; $i <= 24; $i++) {
    echo "Row $i:\n";
    print_r($rows[$i] ?? []);
}
