<?php
define('ENVIRONMENT', 'development');
define('BASEPATH', 'dummy');
require_once __DIR__ . '/../application/libraries/SimpleXLSX.php';

$dir = 'C:\\Users\\ASUS\\OneDrive\\Dokumen\\Magang BPOM\\Data BPOM\\Master Metode\\Pangan';
$files = [
    'PIRT' => $dir . '\\Master_Metode_Pangan_PIRT_SK_Pedoman.xlsx',
    'Pangan_Olahan' => $dir . '\\Master_Metode_Pangan_SK_Pedoman.xlsx'
];

foreach ($files as $label => $path) {
    echo "========================================================\n";
    echo "INSPECTING FILE: $label ($path)\n";
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
        for ($r = 0; $r < min(10, count($rows)); $r++) {
            $nonEmpty = [];
            foreach ($rows[$r] as $c => $v) {
                if (trim($v) !== '') {
                    $nonEmpty[] = "[$c] " . trim(str_replace("\n", " ", $v));
                }
            }
            if (!empty($nonEmpty)) {
                echo "    Row $r: " . implode(' | ', array_slice($nonEmpty, 0, 8)) . "\n";
            }
        }
    }
}
