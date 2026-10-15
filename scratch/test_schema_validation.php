<?php
define('BASEPATH', 'TRUE');
define('ENVIRONMENT', 'development');

// 1. Cek validitas sintaks view
$output = [];
$return_var = 0;
exec('C:\\xampp\\php\\php.exe -l application/views/pengujian/proses_sesi.php', $output, $return_var);
echo "Linting proses_sesi.php: " . implode("\n", $output) . "\n";
if ($return_var !== 0) {
    exit(1);
}

// 2. Cek validitas skema file
require_once __DIR__ . '/../application/config/skema_lcp_cemaran_mikroba_kualitatif.php';
$skema = dapatkan_skema_lcp_cemaran_mikroba_kualitatif();
$json = json_encode($skema, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo "JSON Encode Error: " . json_last_error_msg() . "\n";
    exit(1);
}

echo "Skema JSON encode: VALID (" . strlen($json) . " bytes)\n";

$decoded = json_decode($json, true);
if (!isset($decoded['sections']) || count($decoded['sections']) !== 7) {
    echo "Sections check failed: " . count($decoded['sections'] ?? []) . " sections found.\n";
    exit(1);
}

echo "Sections count: " . count($decoded['sections']) . " (PASS)\n";
foreach ($decoded['sections'] as $s) {
    echo " - Section: [{$s['id']}] {$s['title']} (" . (isset($s['fields']) ? count($s['fields']) . " fields" : (isset($s['items']) ? count($s['items']) . " ref items" : "0")) . ")\n";
}

echo "\nALL TESTS PASSED.\n";
