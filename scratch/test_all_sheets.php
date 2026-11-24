<?php
define('ENVIRONMENT', 'development');
define('BASEPATH', 'dummy');

require_once __DIR__ . '/../application/libraries/SimpleXLSX.php';
require_once __DIR__ . '/../application/libraries/Excel_Importer.php';

$excelPath = 'C:\\Users\\ASUS\\OneDrive\\Dokumen\\Magang BPOM\\Data BPOM\\Format Data Sampel\\Sampel Pangan\\4. Data Contoh April 2026_print.xlsx';
$xlsx = SimpleXLSX::parse($excelPath);
$sheets = $xlsx->sheetNames();

foreach ($sheets as $sIdx => $sName) {
    $rows = $xlsx->rows($sIdx);
    $header_info = Excel_Importer::detect_header_and_data_start($rows);
    $mapping = Excel_Importer::map_headers($header_info['header']);
    echo "=== Sheet {$sIdx}: '{$sName}' (Rows: " . count($rows) . ") ===\n";
    echo "  Main header row: {$header_info['main_header_idx']}, First data row: {$header_info['first_data_idx']}\n";
    echo "  Total mapped: {$mapping['total_mapped']} columns\n";
    echo "  Mapping: " . json_encode(array_values($mapping['field_map']), JSON_UNESCAPED_UNICODE) . "\n\n";
}
