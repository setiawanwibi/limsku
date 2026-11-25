<?php
define('BASEPATH', 'dummy');
require_once __DIR__ . '/../application/libraries/SimpleXLSX.php';

$excelPath = 'C:\\Users\\ASUS\\OneDrive\\Dokumen\\Magang BPOM\\Data BPOM\\Format Data Sampel\\Sampel Kosmetik\\10. Data Contoh Kosmetik Oktober 2026 - 2.xlsx';
$xlsx = SimpleXLSX::parse($excelPath);
$rows = $xlsx->rows(0);

echo "=== RAW ROWS 0 TO 8 ===\n";
for ($r = 0; $r <= 8; $r++) {
    echo "Row $r: " . json_encode($rows[$r], JSON_UNESCAPED_UNICODE) . "\n";
}

echo "\n=== COLUMN BREAKDOWN (Row 6 & 7) ===\n";
$maxCols = max(count($rows[6]), count($rows[7]));
for ($c = 0; $c < $maxCols; $c++) {
    $h6 = $rows[6][$c] ?? '';
    $h7 = $rows[7][$c] ?? '';
    $d8 = $rows[8][$c] ?? '';
    $d9 = $rows[9][$c] ?? '';
    echo sprintf("Col %2d | R6: %-25s | R7: %-6s | Sample: %s\n", 
        $c, 
        str_replace(["\r", "\n"], ' ', substr($h6, 0, 25)), 
        str_replace(["\r", "\n"], ' ', substr($h7, 0, 6)), 
        str_replace(["\r", "\n"], ' ', substr($d8, 0, 30))
    );
}
