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
    echo "HEADERS IN: $label\n";
    echo "========================================================\n";
    $xlsx = SimpleXLSX::parse($path);
    foreach ($xlsx->sheetNames() as $sIdx => $sName) {
        $rows = $xlsx->rows($sIdx);
        echo "Sheet $sIdx: '$sName' (" . count($rows) . " rows)\n";
        echo "  Header Row 0 (" . count($rows[0]) . " cols):\n";
        foreach ($rows[0] as $c => $v) {
            echo "    [$c] => '$v'\n";
        }
        if (isset($rows[1])) {
            echo "  Sample Row 1:\n";
            foreach ($rows[1] as $c => $v) {
                if (trim($v) !== '') {
                    echo "    [$c] => '" . trim(str_replace("\n", " ", $v)) . "'\n";
                }
            }
        }
    }
}
