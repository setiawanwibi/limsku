<?php
define('BASEPATH', 1);
define('ENVIRONMENT', 'development');
require_once __DIR__ . '/../application/libraries/SimpleXLSX.php';

$dir = 'C:\\Users\\ASUS\\OneDrive\\Dokumen\\Magang BPOM\\Data BPOM\\Master Metode\\Pangan';
$f_pirt = $dir . '\\Master_Metode_Pangan_PIRT_SK_Pedoman.xlsx';
$f_olahan = $dir . '\\Master_Metode_Pangan_SK_Pedoman.xlsx';

$x_pirt = SimpleXLSX::parse($f_pirt);
$x_olahan = SimpleXLSX::parse($f_olahan);

echo "=== PIRT FILE ===\n";
foreach ($x_pirt->sheetNames() as $idx => $sName) {
    $rows = $x_pirt->rows($idx);
    $data_rows = count($rows) - 1; // minus header
    echo "Sheet $idx: '$sName' => $data_rows data rows\n";
}

echo "\n=== PANGAN OLAHAN FILE ===\n";
foreach ($x_olahan->sheetNames() as $idx => $sName) {
    $rows = $x_olahan->rows($idx);
    $data_rows = count($rows) - 1; // minus header
    echo "Sheet $idx: '$sName' => $data_rows data rows\n";
}
