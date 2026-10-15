<?php
$userprofile = getenv('USERPROFILE') ?: 'C:\Users\ASUS';

$paths = [
    $userprofile . '\Documents\Magang BPOM\Data BPOM\Format Parameter Pengujian\Mikrobiologi',
    $userprofile . '\OneDrive\Dokumen\Magang BPOM\Data BPOM\Format Parameter Pengujian\Mikrobiologi',
    $userprofile . '\OneDrive\Documents\Magang BPOM\Data BPOM\Format Parameter Pengujian\Mikrobiologi',
];

$found = null;
foreach ($paths as $p) {
    echo "Checking: $p -> " . (is_dir($p) ? "FOUND" : "NOT FOUND") . "\n";
    if (is_dir($p) && !$found) {
        $found = $p;
    }
}

if (!$found) {
    die("\n[ERROR] Direktori tidak ditemukan di lokasi standar!\n");
}

echo "\n[ACTIVE DIR] $found\n\n";

$files = scandir($found);
$excel_files = [];
foreach ($files as $f) {
    if ($f === '.' || $f === '..') continue;
    $ext = strtolower(pathinfo($f, PATHINFO_EXTENSION));
    if (in_array($ext, ['xlsx', 'xlsm', 'xls'])) {
        $full = $found . DIRECTORY_SEPARATOR . $f;
        $size = filesize($full);
        $excel_files[] = [
            'name' => $f,
            'full' => $full,
            'size' => $size,
            'ext'  => $ext
        ];
        echo sprintf("%-80s | %-6s | %10s bytes\n", $f, strtoupper($ext), number_format($size));
    }
}
