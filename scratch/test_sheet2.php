<?php
define('ENVIRONMENT', 'development');
define('BASEPATH', 'dummy');

require_once __DIR__ . '/../application/libraries/SimpleXLSX.php';
require_once __DIR__ . '/../application/libraries/Excel_Importer.php';

$excelPath = 'C:\\Users\\ASUS\\OneDrive\\Dokumen\\Magang BPOM\\Data BPOM\\Format Data Sampel\\Sampel Pangan\\4. Data Contoh April 2026_print.xlsx';
$xlsx = SimpleXLSX::parse($excelPath);

$rows = $xlsx->rows(2);
$header_info = Excel_Importer::detect_header_and_data_start($rows);
$mapping = Excel_Importer::map_headers($header_info['header']);
echo "Header: " . json_encode($header_info['header'], JSON_UNESCAPED_UNICODE) . "\n";
echo "Mapping: " . json_encode($mapping, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
