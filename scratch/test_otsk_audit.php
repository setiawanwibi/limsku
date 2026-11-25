<?php
define('BASEPATH', 'dummy');
require_once __DIR__ . '/../application/libraries/SimpleXLSX.php';

$excelPath = 'C:\\Users\\ASUS\\OneDrive\\Dokumen\\Magang BPOM\\Data BPOM\\Format Data Sampel\\Sampel Obat Tradisional dan Suplemen\\6. Data contoh OTSK JUNI 2026 NEW.xlsx';

echo "=== INSPECTING OTSK (OBAT TRADISIONAL & SUPLEMEN) EXCEL FILE ===\n";
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
    
    // Print first 12 rows
    for ($i = 0; $i < min(12, count($rows)); $i++) {
        $r = $rows[$i];
        while (count($r) > 0 && end($r) === '') {
            array_pop($r);
        }
        echo "Row $i (" . count($r) . " cols): " . json_encode($r, JSON_UNESCAPED_UNICODE) . "\n";
    }
    echo "\n";
}
