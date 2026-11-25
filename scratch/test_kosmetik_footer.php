<?php
define('BASEPATH', 'dummy');
require_once __DIR__ . '/../application/libraries/SimpleXLSX.php';

$excelPath = 'C:\\Users\\ASUS\\OneDrive\\Dokumen\\Magang BPOM\\Data BPOM\\Format Data Sampel\\Sampel Kosmetik\\10. Data Contoh Kosmetik Oktober 2026 - 2.xlsx';
$xlsx = SimpleXLSX::parse($excelPath);
$rows = $xlsx->rows(0);

echo "Total rows: " . count($rows) . "\n\n";

for ($r = 58; $r < min(72, count($rows)); $r++) {
    $nonEmpty = [];
    foreach ($rows[$r] as $c => $v) {
        if ($v !== '') {
            $nonEmpty[] = "Col $c: " . str_replace("\n", " ", $v);
        }
    }
    if (!empty($nonEmpty)) {
        echo "Row $r: " . implode(' | ', $nonEmpty) . "\n";
    }
}
