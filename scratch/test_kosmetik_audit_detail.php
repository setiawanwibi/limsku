<?php
define('BASEPATH', 'dummy');
require_once __DIR__ . '/../application/libraries/SimpleXLSX.php';

$excelPath = 'C:\\Users\\ASUS\\OneDrive\\Dokumen\\Magang BPOM\\Data BPOM\\Format Data Sampel\\Sampel Kosmetik\\10. Data Contoh Kosmetik Oktober 2026 - 2.xlsx';
$xlsx = SimpleXLSX::parse($excelPath);
$rows = $xlsx->rows(0);

echo "Total rows: " . count($rows) . "\n\n";

echo "=== ROW 6 (Header Top) ===\n";
foreach ($rows[6] as $colIdx => $val) {
    if ($val !== '' || isset($rows[7][$colIdx]) && $rows[7][$colIdx] !== '') {
        $sub = $rows[7][$colIdx] ?? '';
        echo "Col $colIdx: [Header: '$val'] [Sub: '$sub']\n";
    }
}

echo "\n=== SAMPLE DATA (Row 8 & 9) ===\n";
for ($r = 8; $r <= 10; $r++) {
    if (!isset($rows[$r])) break;
    echo "Row $r:\n";
    foreach ($rows[$r] as $colIdx => $val) {
        $h1 = $rows[6][$colIdx] ?? '';
        $h2 = $rows[7][$colIdx] ?? '';
        $h = trim($h1 . ' ' . $h2);
        if ($val !== '' || $h !== '') {
            echo "  [$colIdx] ($h) => " . str_replace("\n", " ", $val) . "\n";
        }
    }
    echo "\n";
}

// Find non-empty data rows
$dataRowCount = 0;
for ($r = 8; $r < count($rows); $r++) {
    $hasData = false;
    foreach ($rows[$r] as $val) {
        if (trim($val) !== '') {
            $hasData = true;
            break;
        }
    }
    if ($hasData) $dataRowCount++;
}
echo "Total valid data rows: $dataRowCount\n";
