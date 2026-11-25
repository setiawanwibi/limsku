<?php
define('ENVIRONMENT', 'development');
define('BASEPATH', 'dummy');

require_once __DIR__ . '/../application/libraries/SimpleXLSX.php';

$files = [
    'Kosmetik Asli' => 'C:\\Users\\ASUS\\OneDrive\\Dokumen\\Magang BPOM\\Data BPOM\\Format Data Sampel\\Sampel Kosmetik\\10. Data Contoh Kosmetik Oktober 2026 - 2.xlsx',
    'OTSK Asli' => 'C:\\Users\\ASUS\\OneDrive\\Dokumen\\Magang BPOM\\Data BPOM\\Format Data Sampel\\Sampel Obat Tradisional dan Suplemen\\6. Data contoh OTSK JUNI 2026 NEW.xlsx'
];

foreach ($files as $label => $path) {
    echo "=== $label ===\n";
    $xlsx = SimpleXLSX::parse($path);
    if (!$xlsx) {
        echo "Error opening $path: " . SimpleXLSX::parseError() . "\n";
        continue;
    }
    $sheetNames = $xlsx->sheetNames();
    echo "Sheet Names: " . json_encode($sheetNames) . "\n";
    foreach ($sheetNames as $idx => $sName) {
        $rows = $xlsx->rows($idx);
        echo "  Sheet $idx ('$sName'): " . count($rows) . " rows\n";
        // Show first 3 rows
        for ($r = 0; $r < min(3, count($rows)); $r++) {
            $nonEmpty = array_values(array_filter(array_map('trim', $rows[$r])));
            echo "    Row $r: " . json_encode(array_slice($nonEmpty, 0, 5)) . "\n";
        }
    }
}
