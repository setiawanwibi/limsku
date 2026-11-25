<?php
define('BASEPATH', 'dummy');
require_once __DIR__ . '/../application/libraries/SimpleXLSX.php';

$excelPath = 'C:\\Users\\ASUS\\OneDrive\\Dokumen\\Magang BPOM\\Data BPOM\\Format Data Sampel\\Sampel Obat Tradisional dan Suplemen\\6. Data contoh OTSK JUNI 2026 NEW.xlsx';
$xlsx = SimpleXLSX::parse($excelPath);
$rows = $xlsx->rows(0);

echo "=== ROWS 0 TO 5 ===\n";
for ($r = 0; $r <= 5; $r++) {
    echo "Row $r:\n";
    foreach ($rows[$r] as $c => $val) {
        if ($val !== '') {
            echo "  [$c] => " . json_encode(str_replace("\n", " ", $val)) . "\n";
        }
    }
}
