<?php
define('BASEPATH', 'dummy');
require_once __DIR__ . '/../application/libraries/SimpleXLSX.php';
require_once __DIR__ . '/../application/libraries/Excel_Importer.php';

$excelPath = 'C:\\Users\\ASUS\\OneDrive\\Dokumen\\Magang BPOM\\Data BPOM\\Format Data Sampel\\Sampel Obat Tradisional dan Suplemen\\6. Data contoh OTSK JUNI 2026 NEW.xlsx';
$xlsx = SimpleXLSX::parse($excelPath);

echo "=== TESTING SHEET 0 (OBAT TRADISIONAL / OBA) ===\n";
$rows0 = $xlsx->rows(0);
echo "Total rows: " . count($rows0) . "\n";
$info0 = Excel_Importer::detect_header_and_data_start($rows0);
echo "main_header_idx: {$info0['main_header_idx']}\n";
echo "last_header_idx: {$info0['last_header_idx']}\n";
echo "first_data_idx: {$info0['first_data_idx']}\n";
echo "Consolidated Header:\n";
foreach ($info0['header'] as $i => $h) {
    if ($h !== '') echo "  [$i] => '$h'\n";
}
$map0 = Excel_Importer::map_headers($info0['header']);
echo "Mapping Status: " . ($map0['status'] ? 'SUCCESS' : 'FAILED') . "\n";
echo "Mapped Fields: " . count($map0['field_map']) . "\n";
echo "Unrecognized: " . json_encode($map0['unrecognized']) . "\n\n";

echo "=== TESTING SHEET 1 (SUPLEMEN KESEHATAN / SK) ===\n";
$rows1 = $xlsx->rows(1);
echo "Total rows: " . count($rows1) . "\n";
$info1 = Excel_Importer::detect_header_and_data_start($rows1);
echo "main_header_idx: {$info1['main_header_idx']}\n";
echo "last_header_idx: {$info1['last_header_idx']}\n";
echo "first_data_idx: {$info1['first_data_idx']}\n";
$map1 = Excel_Importer::map_headers($info1['header']);
echo "Mapping Status: " . ($map1['status'] ? 'SUCCESS' : 'FAILED') . "\n";
echo "Mapped Fields: " . count($map1['field_map']) . "\n";
echo "Unrecognized: " . json_encode($map1['unrecognized']) . "\n";
