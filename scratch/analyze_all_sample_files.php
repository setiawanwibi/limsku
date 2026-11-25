<?php
define('ENVIRONMENT', 'development');
define('BASEPATH', 'dummy');

require_once __DIR__ . '/../application/libraries/SimpleXLSX.php';

$files = [
    'Kosmetik' => 'C:\\Users\\ASUS\\OneDrive\\Dokumen\\Magang BPOM\\Data BPOM\\Format Data Sampel\\Sampel Kosmetik\\10. Data Contoh Kosmetik Oktober 2026 - 2.xlsx',
    'Obat'     => 'C:\\Users\\ASUS\\OneDrive\\Dokumen\\Magang BPOM\\Data BPOM\\Format Data Sampel\\Sampel Obat\\Data Contoh Jambi Juni 2026.xlsx',
    'OTSK'     => 'C:\\Users\\ASUS\\OneDrive\\Dokumen\\Magang BPOM\\Data BPOM\\Format Data Sampel\\Sampel Obat Tradisional dan Suplemen\\6. Data contoh OTSK JUNI 2026 NEW.xlsx',
    'Pangan'   => 'C:\\Users\\ASUS\\OneDrive\\Dokumen\\Magang BPOM\\Data BPOM\\Format Data Sampel\\Sampel Pangan\\4. Data Contoh April 2026_print.xlsx'
];

foreach ($files as $name => $path) {
    echo "========================================================\n";
    echo "ANALYZING: $name ($path)\n";
    echo "========================================================\n";
    $xlsx = SimpleXLSX::parse($path);
    if (!$xlsx) {
        echo "FAIL parse: " . SimpleXLSX::parseError() . "\n";
        continue;
    }
    $sheets = $xlsx->sheetNames();
    echo "Sheets: " . json_encode($sheets) . "\n";
    foreach ($sheets as $sIdx => $sName) {
        $rows = $xlsx->rows($sIdx);
        echo "  --- Sheet $sIdx ('$sName') [" . count($rows) . " rows] ---\n";
        for ($r = 0; $r < min(5, count($rows)); $r++) {
            $filtered = array_values(array_filter(array_map('trim', $rows[$r])));
            if (!empty($filtered)) {
                echo "    Row $r: " . json_encode(array_slice($filtered, 0, 7), JSON_UNESCAPED_UNICODE) . "\n";
            }
        }
    }
}
