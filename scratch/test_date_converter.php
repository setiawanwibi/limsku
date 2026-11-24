<?php
define('ENVIRONMENT', 'development');
define('BASEPATH', 'dummy');

function test_convert_date($val) {
    if ($val === NULL || $val === '') return NULL;
    $val = trim((string)$val);
    
    // Excel serial date (e.g. 46113 -> 2026-04-01)
    if (is_numeric($val) && (float)$val >= 30000 && (float)$val <= 65000) {
        $days = (int) floor((float)$val);
        $timestamp = ($days - 25569) * 86400;
        return date('Y-m-d', $timestamp);
    }
    
    // 8-digit DDMMYYYY (e.g. 12062026 -> 2026-06-12)
    if (preg_match('/^(\d{2})(\d{2})(\d{4})$/', $val, $m)) {
        if ((int)$m[1] <= 31 && (int)$m[2] <= 12) {
            return "{$m[3]}-{$m[2]}-{$m[1]}";
        }
    }
    
    // 5-digit DMMYY or 6-digit DDMMYY (e.g. 80126 -> 2026-01-08, 080126 -> 2026-01-08)
    if (preg_match('/^(\d{1,2})(\d{2})(\d{2})$/', $val, $m)) {
        $d = str_pad($m[1], 2, '0', STR_PAD_LEFT);
        $mth = $m[2];
        $yr = (int)$m[3] < 50 ? '20' . $m[3] : '19' . $m[3];
        if ((int)$d <= 31 && (int)$mth <= 12) {
            return "{$yr}-{$mth}-{$d}";
        }
    }
    
    return $val;
}

echo "46113 -> " . test_convert_date('46113') . "\n";
echo "46705 -> " . test_convert_date('46705') . "\n";
echo "80126 -> " . test_convert_date('80126') . "\n";
echo "80128 -> " . test_convert_date('80128') . "\n";
echo "12062026 -> " . test_convert_date('12062026') . "\n";
echo "April 2026 -> " . test_convert_date('April 2026') . "\n";
