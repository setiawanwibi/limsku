<?php
define('BASEPATH', 'dummy');
require_once __DIR__ . '/../application/libraries/SimpleXLSX.php';
require_once __DIR__ . '/../application/libraries/Excel_Importer.php';

$excelPath = 'C:\\Users\\ASUS\\OneDrive\\Dokumen\\Magang BPOM\\Data BPOM\\Format Data Sampel\\Sampel Kosmetik\\10. Data Contoh Kosmetik Oktober 2026 - 2.xlsx';
$xlsx = SimpleXLSX::parse($excelPath);
$rows = $xlsx->rows(0);

echo "Total rows: " . count($rows) . "\n";

$header_info = Excel_Importer::detect_header_and_data_start($rows);
echo "=== DETECT HEADER INFO ===\n";
echo "main_header_idx: {$header_info['main_header_idx']}\n";
echo "last_header_idx: {$header_info['last_header_idx']}\n";
echo "first_data_idx: {$header_info['first_data_idx']}\n";
echo "Consolidated Header:\n";
foreach ($header_info['header'] as $idx => $h) {
    echo "  [$idx] => '$h'\n";
}

$mapping = Excel_Importer::map_headers($header_info['header']);
echo "\n=== MAPPING RESULT ===\n";
echo "Status: " . ($mapping['status'] ? 'SUCCESS' : 'FAILED') . "\n";
if (!$mapping['status']) {
    echo "Pesan: " . ($mapping['pesan'] ?? '') . "\n";
}
echo "Total Mapped: " . ($mapping['total_mapped'] ?? 0) . "\n";
echo "Field Map:\n";
foreach ($mapping['field_map'] ?? [] as $colIdx => $field) {
    echo "  Col $colIdx ('{$header_info['header'][$colIdx]}') => $field\n";
}
echo "Unrecognized:\n";
foreach ($mapping['unrecognized'] ?? [] as $colIdx => $raw) {
    echo "  Col $colIdx => '$raw'\n";
}
