<?php
define('BASEPATH', true);
require_once __DIR__ . '/../application/libraries/SimpleXLSX.php';

$file = 'C:/Users/heris/OneDrive/Documents/MagangHub BBPOM/LIMSKU/Data Contoh/Data Contoh Jambi Juni 2026.xlsx';

if (!file_exists($file)) {
    echo "FILE_NOT_FOUND: $file\n";
    exit;
}

$xlsx = SimpleXLSX::parse($file);
if (!$xlsx) {
    echo "PARSE_ERROR: " . SimpleXLSX::parseError() . "\n";
    exit;
}

$rows = $xlsx->rows();
echo "TOTAL_ROWS: " . count($rows) . "\n";
echo "=== HEADER BARIS 1 ===\n";
foreach ($rows[0] as $colIdx => $colName) {
    echo "Col $colIdx: $colName\n";
}

echo "\n=== CONTOH 5 DATA PERTAMA (KOLOM NAMA, KIMIA, MIKRO) ===\n";
for ($i = 1; $i <= min(5, count($rows) - 1); $i++) {
    $row = $rows[$i];
    echo "Row $i:\n";
    echo "  Nama Sampel : " . ($row[3] ?? '-') . "\n";
    echo "  Kode Sampel : " . ($row[2] ?? '-') . "\n";
    // Cari index kolom kimia dan mikro
    foreach ($rows[0] as $cIdx => $cName) {
        $clean = strtolower(trim($cName));
        if (in_array($clean, ['kimia', 'mikro', 'mikrobiologi', 'jumlah kimia', 'jumlah mikro'])) {
            echo "  $cName (Col $cIdx): " . ($row[$cIdx] ?? '') . "\n";
        }
    }
}
