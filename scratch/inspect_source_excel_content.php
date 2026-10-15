<?php
define('BASEPATH', 'defined');
require_once __DIR__ . '/../application/libraries/SimpleXLSX.php';

$source_dir = 'C:/Users/ASUS/OneDrive/Dokumen/Magang BPOM/Data BPOM/Data Reagen';
$files = [
    'Data Baku Pembanding.xlsx',
    'Data Media Mikrobiologi.xlsx',
    'Data Reagen Cair.xlsx',
    'Data Reagen Padat.xlsx'
];

echo "=== MEMERIKSA FILE SUMBER RESMI REAGEN BPOM ===\n\n";

foreach ($files as $f) {
    $path = $source_dir . '/' . $f;
    if (!file_exists($path)) {
        echo "File $f tidak ada.\n";
        continue;
    }
    
    $xlsx = SimpleXLSX::parse($path);
    if (!$xlsx) {
        echo "Gagal parse $f: " . SimpleXLSX::parseError() . "\n";
        continue;
    }
    
    $rows = $xlsx->rows();
    echo "File: $f (" . count($rows) . " baris)\n";
    if (!empty($rows)) {
        // Tampilkan 3 baris pertama
        for ($i = 0; $i < min(4, count($rows)); $i++) {
            echo "  Row " . ($i+1) . ": " . json_encode($rows[$i], JSON_UNESCAPED_UNICODE) . "\n";
        }
    }
    echo "--------------------------------------------------------\n\n";
}
