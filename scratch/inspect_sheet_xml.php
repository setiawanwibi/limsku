<?php
$zip = new ZipArchive();
$filePath = 'C:/Users/ASUS/OneDrive/Dokumen/Magang BPOM/Data BPOM/Format Parameter Pengujian/Mikrobiologi/CP mikro.xlsx';
if ($zip->open($filePath) === true) {
    for ($i = 0; $i < $zip->numFiles; $i++) {
        $entry = $zip->getNameIndex($i);
        if (strpos($entry, 'sheet') !== false || strpos($entry, 'styles') !== false) {
            echo "FILE: " . $entry . "\n";
        }
    }
    
    // Dump sheet1.xml
    $sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml');
    echo "\n=== MERGED CELLS ===\n";
    if (preg_match_all('/<mergeCell ref="([^"]+)"\/>/', $sheetXml, $matches)) {
        foreach ($matches[1] as $ref) {
            echo $ref . " ";
        }
        echo "\n";
    }

    echo "\n=== COLS DEFINITIONS ===\n";
    if (preg_match('/<cols>(.*?)<\/cols>/s', $sheetXml, $colsMatch)) {
        echo $colsMatch[1] . "\n";
    }

    // Dump first 40 rows
    echo "\n=== ROWS (FIRST 40) ===\n";
    if (preg_match_all('/<row r="(\d+)"[^>]*>(.*?)<\/row>/s', $sheetXml, $rowMatches)) {
        for ($k = 0; $k < min(40, count($rowMatches[1])); $k++) {
            $rNum = $rowMatches[1][$k];
            $rContent = $rowMatches[2][$k];
            preg_match_all('/<c r="([A-Z]+\d+)"[^>]*>(.*?)<\/c>/s', $rContent, $cMatches);
            echo "Row $rNum: " . implode(', ', $cMatches[1]) . "\n";
        }
    }

    $zip->close();
}
