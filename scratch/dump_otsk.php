<?php
define('BASEPATH', 1);
require_once __DIR__ . '/../application/libraries/SimpleXLSX.php';
$path = 'C:/Users/ASUS/OneDrive/Dokumen/Magang BPOM/Data BPOM/Format Data Sampel/Sampel Obat Tradisional dan Suplemen/6. Data contoh OTSK JUNI 2026 NEW.xlsx';
$xlsx = SimpleXLSX::parse($path);

echo "=== SHEET 0 (OBA / HERBAL) ===\n";
$rows0 = $xlsx->rows(0);
for ($i = 0; $i < min(8, count($rows0)); $i++) {
    echo "Row $i: " . json_encode($rows0[$i], JSON_UNESCAPED_UNICODE) . "\n";
}

echo "\n=== FOOTER SHEET 0 ===\n";
for ($i = count($rows0) - 7; $i < count($rows0); $i++) {
    echo "Row $i: " . json_encode(array_slice($rows0[$i], 0, 23), JSON_UNESCAPED_UNICODE) . "\n";
}

echo "\n=== FOOTER SHEET 1 ===\n";
for ($i = count($rows1) - 7; $i < count($rows1); $i++) {
    echo "Row $i: " . json_encode(array_slice($rows1[$i], 0, 23), JSON_UNESCAPED_UNICODE) . "\n";
}

