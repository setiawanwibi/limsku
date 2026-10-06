<?php
$excelPath = 'C:\\Users\\heris\\OneDrive\\Documents\\MagangHub BBPOM\\LIMSKU\\Data Contoh Jambi Juni 2026.xlsx';

if (!file_exists($excelPath)) {
    die("File not found: " . $excelPath . "\n");
}

echo "File exists, size: " . filesize($excelPath) . " bytes\n";

// Buka dengan ZipArchive untuk memeriksa XML sheet dan sharedStrings
$zip = new ZipArchive();
if ($zip->open($excelPath) === true) {
    echo "Zip opened successfully.\n";
    for ($i = 0; $i < $zip->numFiles; $i++) {
        $stat = $zip->statIndex($i);
        if (strpos($stat['name'], 'sheet') !== false || strpos($stat['name'], 'sharedStrings') !== false) {
            echo " - " . $stat['name'] . " (" . $stat['size'] . " bytes)\n";
        }
    }
    $zip->close();
} else {
    echo "Failed to open zip.\n";
}
