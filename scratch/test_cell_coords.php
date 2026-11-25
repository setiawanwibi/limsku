<?php
require_once __DIR__ . '/../application/libraries/spout/autoload.php'; // or check if PhpOffice/PhpSpreadsheet is installed
// Let's check what composer packages or libraries exist
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require_once __DIR__ . '/../vendor/autoload.php';
}

$excelPath = 'C:\\Users\\ASUS\\OneDrive\\Dokumen\\Magang BPOM\\Data BPOM\\Format Data Sampel\\Sampel Kosmetik\\10. Data Contoh Kosmetik Oktober 2026 - 2.xlsx';

if (class_exists('PhpOffice\PhpSpreadsheet\IOFactory')) {
    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($excelPath);
    $sheet = $spreadsheet->getActiveSheet();
    echo "Using PhpSpreadsheet!\n";
    echo "Sheet Title: " . $sheet->getTitle() . "\n";
    echo "Merge Cells: " . json_encode($sheet->getMergeCells()) . "\n\n";
    
    echo "Rows 1 to 9:\n";
    for ($row = 1; $row <= 9; $row++) {
        echo "Row $row: ";
        $cells = [];
        for ($col = 'A'; $col <= 'Z'; $col++) {
            $val = $sheet->getCell($col . $row)->getValue();
            if ($val !== null && $val !== '') {
                $cells[] = "$col$row: " . str_replace("\n", " ", $val);
            }
        }
        echo implode(' | ', $cells) . "\n";
    }
} else {
    echo "PhpOffice not found, checking with SimpleXLSX or zip...\n";
}
