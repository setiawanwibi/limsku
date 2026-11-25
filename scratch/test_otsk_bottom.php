<?php
define('BASEPATH', 'dummy');
require_once __DIR__ . '/../application/libraries/SimpleXLSX.php';

$excelPath = 'C:\\Users\\ASUS\\OneDrive\\Dokumen\\Magang BPOM\\Data BPOM\\Format Data Sampel\\Sampel Obat Tradisional dan Suplemen\\6. Data contoh OTSK JUNI 2026 NEW.xlsx';
$xlsx = SimpleXLSX::parse($excelPath);

for ($s = 0; $s <= 1; $s++) {
    $rows = $xlsx->rows($s);
    echo "=== SHEET $s LAST ROWS ===\n";
    $count = count($rows);
    for ($r = 35; $r < min(55, $count); $r++) {
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
}
