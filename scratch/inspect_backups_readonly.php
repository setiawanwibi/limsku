<?php
// Script read-only untuk memeriksa backup files
$backup_dir = 'C:/xampp/backups/LIMSKU';
$files = [
    'limsku_backup_20261013_090831.sql',
    'limsku_backup_20261014_045207.sql',
    'limsku_backup_pre_reagents_20261014_095411.sql'
];

echo "=== INSPEKSI FILE BACKUP SECARA READ-ONLY ===\n\n";

foreach ($files as $f) {
    $path = $backup_dir . '/' . $f;
    if (!file_exists($path)) {
        echo "File: $f TIDAK DITEMUKAN.\n";
        continue;
    }
    
    $size = filesize($path);
    $sha256 = hash_file('sha256', $path);
    echo "File: {$f}\n";
    echo "  - Size   : {$size} bytes\n";
    echo "  - SHA256 : {$sha256}\n";
    
    // Check if table `reagents` is in the dump
    $fp = fopen($path, 'r');
    $has_reagents_table = false;
    $has_reagents_data = false;
    $reagents_create_def = '';
    $insert_rows_count = 0;
    $sample_insert_lines = [];
    
    $in_reagents_create = false;
    while (($line = fgets($fp)) !== false) {
        if (strpos($line, 'CREATE TABLE `reagents`') !== false || strpos($line, 'CREATE TABLE IF NOT EXISTS `reagents`') !== false) {
            $has_reagents_table = true;
            $in_reagents_create = true;
            $reagents_create_def .= $line;
            continue;
        }
        if ($in_reagents_create) {
            $reagents_create_def .= $line;
            if (strpos($line, ';') !== false) {
                $in_reagents_create = false;
            }
        }
        if (strpos($line, 'INSERT INTO `reagents`') !== false) {
            $has_reagents_data = true;
            $sample_insert_lines[] = substr($line, 0, 200) . '...';
            // Count rows in insert
            $insert_rows_count += substr_count($line, '),(') + 1;
        }
    }
    fclose($fp);
    
    echo "  - Tabel `reagents` Ditemukan: " . ($has_reagents_table ? "YA" : "TIDAK") . "\n";
    if ($has_reagents_table) {
        echo "  - Definisi Tabel:\n" . trim($reagents_create_def) . "\n";
    }
    echo "  - Data `reagents` Ditemukan: " . ($has_reagents_data ? "YA (Estimasi {$insert_rows_count} baris)" : "TIDAK") . "\n";
    if (!empty($sample_insert_lines)) {
        echo "  - Sample Insert:\n    " . implode("\n    ", array_slice($sample_insert_lines, 0, 3)) . "\n";
    }
    echo "\n------------------------------------------------------------\n\n";
}
