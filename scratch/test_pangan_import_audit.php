<?php
define('ENVIRONMENT', 'development');
define('BASEPATH', 'dummy');
require_once __DIR__ . '/../application/config/database.php';

$dsn = 'mysql:host=' . $db['default']['hostname'] . ';dbname=' . $db['default']['database'] . ';charset=utf8mb4';
try {
    $pdo = new PDO($dsn, $db['default']['username'], $db['default']['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected to database.\n";
    
    $stmt = $pdo->query("DESCRIBE samples");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "=== SAMPLES TABLE COLUMNS ===\n";
    foreach ($columns as $col) {
        echo " - {$col['Field']} ({$col['Type']})\n";
    }
} catch (Exception $e) {
    echo "DB Error: " . $e->getMessage() . "\n";
}

require_once __DIR__ . '/../application/libraries/SimpleXLSX.php';
require_once __DIR__ . '/../application/libraries/Excel_Importer.php';

$excelPath = 'C:\\Users\\ASUS\\OneDrive\\Dokumen\\Magang BPOM\\Data BPOM\\Format Data Sampel\\Sampel Pangan\\4. Data Contoh April 2026_print.xlsx';

echo "\n=== PARSING EXCEL WITH SIMPLEXLSX ===\n";
$xlsx = SimpleXLSX::parse($excelPath);
if (!$xlsx) {
    echo "Error parsing: " . SimpleXLSX::parseError() . "\n";
    exit;
}

$sheets = $xlsx->sheetNames();
echo "Sheets: " . implode(', ', $sheets) . "\n";

$rows = $xlsx->rows(0); // sheet 0: April 206
echo "Total rows in Sheet 0: " . count($rows) . "\n";

echo "\n--- First 8 raw rows ---\n";
for ($i = 0; $i < min(8, count($rows)); $i++) {
    echo "Row $i: " . json_encode(array_slice($rows[$i], 0, 22), JSON_UNESCAPED_UNICODE) . "\n";
}

$header_info = Excel_Importer::detect_header_and_data_start($rows);
echo "\n--- Detected Header Info ---\n";
echo "main_header_idx: {$header_info['main_header_idx']}\n";
echo "last_header_idx: {$header_info['last_header_idx']}\n";
echo "first_data_idx: {$header_info['first_data_idx']}\n";
echo "Consolidated Header: " . json_encode($header_info['header'], JSON_UNESCAPED_UNICODE) . "\n";

$mapping = Excel_Importer::map_headers($header_info['header']);
echo "\n--- Mapping Status: " . ($mapping['status'] ? 'SUCCESS' : 'FAILED') . " ---\n";
echo "Total mapped: " . ($mapping['total_mapped'] ?? 0) . "\n";
echo "Field map: " . json_encode($mapping['field_map'] ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
echo "Unrecognized: " . json_encode($mapping['unrecognized'] ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
