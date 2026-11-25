<?php
define('BASEPATH', 'dummy');
require_once __DIR__ . '/../application/libraries/SimpleXLSX.php';

$excelPath = 'C:\\Users\\ASUS\\OneDrive\\Dokumen\\Magang BPOM\\Data BPOM\\Format Data Sampel\\Sampel Kosmetik\\10. Data Contoh Kosmetik Oktober 2026 - 2.xlsx';

echo "=== INSPECTING KOSMETIK EXCEL FILE ===\n";
echo "Path: $excelPath\n";

if (!file_exists($excelPath)) {
    echo "ERROR: File not found!\n";
    exit(1);
}

$xlsx = SimpleXLSX::parse($excelPath);
if (!$xlsx) {
    echo "Error parsing: " . SimpleXLSX::parseError() . "\n";
    exit(1);
}

$sheets = $xlsx->sheetNames();
echo "Sheets found (" . count($sheets) . "): " . implode(', ', $sheets) . "\n\n";

foreach ($sheets as $idx => $sName) {
    $rows = $xlsx->rows($idx);
    echo "--- SHEET $idx: '$sName' (Total Rows: " . count($rows) . ") ---\n";
    
    // Print first 10 rows
    for ($i = 0; $i < min(10, count($rows)); $i++) {
        // filter out nulls at the end
        $r = $rows[$i];
        while (count($r) > 0 && end($r) === '') {
            array_pop($r);
        }
        echo "Row $i (" . count($r) . " cols): " . json_encode($r, JSON_UNESCAPED_UNICODE) . "\n";
    }
    echo "\n";
}
