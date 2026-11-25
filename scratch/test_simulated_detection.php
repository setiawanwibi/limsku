<?php
define('BASEPATH', 'dummy');
require_once __DIR__ . '/../application/libraries/SimpleXLSX.php';

$excelPath = 'C:\\Users\\ASUS\\OneDrive\\Dokumen\\Magang BPOM\\Data BPOM\\Format Data Sampel\\Sampel Kosmetik\\10. Data Contoh Kosmetik Oktober 2026 - 2.xlsx';
$xlsx = SimpleXLSX::parse($excelPath);
$rows = $xlsx->rows(0);

// Simulate the updated header detection
function test_detect($rows) {
    $main_header_idx = -1;
    for ($k = 0; $k < min(15, count($rows)); $k++) {
        $row_str = strtolower(implode(' ', array_filter($rows[$k])));
        if (strpos($row_str, 'nama sampel') !== false || strpos($row_str, 'kategori sampel') !== false || strpos($row_str, 'nama contoh') !== false) {
            $main_header_idx = $k;
            break;
        }
    }
    
    $last_header_idx = $main_header_idx;
    for ($k = $main_header_idx + 1; $k < min($main_header_idx + 5, count($rows)); $k++) {
        $row = $rows[$k];
        $is_sub = false;
        foreach ($row as $v) {
            $v_clean = strtolower(trim($v));
            if (in_array($v_clean, ['k', 'mi', 'r', 'p', 'a', 't', 'ki', 'manual', 'sipt', 'sarana', 'alamat', 'total'])) {
                $is_sub = true;
                break;
            }
        }
        if ($is_sub) {
            $last_header_idx = $k;
        } else {
            break;
        }
    }
    
    $first_data_idx = $last_header_idx + 1;
    $max_cols = 0;
    for ($k = $main_header_idx; $k <= $last_header_idx; $k++) {
        $max_cols = max($max_cols, count($rows[$k]));
    }
    
    $header = array_fill(0, $max_cols, '');
    for ($c = 0; $c < $max_cols; $c++) {
        for ($k = $last_header_idx; $k >= $main_header_idx; $k--) {
            $v = trim($rows[$k][$c] ?? '');
            if ($v !== '' && !is_numeric($v)) {
                $header[$c] = $v;
                break;
            }
        }
    }
    
    // Custom kosmetik B7:C8 merge fix
    for ($c = 0; $c < $max_cols - 1; $c++) {
        $c_norm = strtolower(preg_replace('/[^\w\s]/', ' ', $header[$c]));
        $c_norm = trim(preg_replace('/\s+/', ' ', $c_norm));
        if (strpos($c_norm, 'no kode contoh') !== false) {
            if (($header[$c+1] ?? '') === '') {
                $header[$c] = 'No';
                $header[$c+1] = 'No SIPT';
            }
        }
    }
    
    return [
        'main_header_idx' => $main_header_idx,
        'last_header_idx' => $last_header_idx,
        'first_data_idx'  => $first_data_idx,
        'header'          => $header
    ];
}

$detected = test_detect($rows);
echo "main_header_idx: {$detected['main_header_idx']}\n";
echo "last_header_idx: {$detected['last_header_idx']}\n";
echo "first_data_idx: {$detected['first_data_idx']}\n";

echo "Consolidated Header:\n";
foreach ($detected['header'] as $i => $h) {
    if ($h !== '') echo "  Col $i => '$h'\n";
}
