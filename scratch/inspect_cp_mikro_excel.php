<?php
define('BASEPATH', 'defined');
require_once 'C:/xampp/htdocs/LIMSKU/application/libraries/SimpleXLSX.php';

$filePath = 'C:/Users/ASUS/OneDrive/Dokumen/Magang BPOM/Data BPOM/Format Parameter Pengujian/Mikrobiologi/CP mikro.xlsx';
if (!file_exists($filePath)) {
    die("File not found: " . $filePath . "\n");
}

$xlsx = SimpleXLSX::parse($filePath);
if (!$xlsx) {
    die("Error parsing: " . SimpleXLSX::parseError() . "\n");
}

echo "=== CP MIKRO.XLSX CONTENTS ===\n\n";

foreach ($xlsx->rows() as $rIdx => $row) {
    $hasContent = false;
    foreach ($row as $val) {
        if (trim($val) !== '') {
            $hasContent = true;
            break;
        }
    }
    if ($hasContent) {
        echo "Row " . ($rIdx + 1) . ":\n";
        foreach ($row as $cIdx => $val) {
            if (trim($val) !== '') {
                $colLetter = chr(65 + $cIdx);
                echo "  [{$colLetter}] " . trim($val) . "\n";
            }
        }
    }
}
