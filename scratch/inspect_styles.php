<?php
$zip = new ZipArchive();
$filePath = 'C:/Users/ASUS/OneDrive/Dokumen/Magang BPOM/Data BPOM/Format Parameter Pengujian/Mikrobiologi/CP mikro.xlsx';
if ($zip->open($filePath) === true) {
    $sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml');
    
    // Parse row heights
    preg_match_all('/<row r="(\d+)"[^>]*ht="([^"]+)"[^>]*>/', $sheetXml, $htMatches);
    echo "=== ROW HEIGHTS ===\n";
    for ($i = 0; $i < count($htMatches[1]); $i++) {
        echo "Row " . $htMatches[1][$i] . " ht=" . $htMatches[2][$i] . "\n";
    }

    $stylesXml = $zip->getFromName('xl/styles.xml');
    echo "\n=== FONTS IN STYLES.XML ===\n";
    if (preg_match_all('/<font>(.*?)<\/font>/s', $stylesXml, $fontMatches)) {
        foreach ($fontMatches[1] as $idx => $f) {
            echo "Font $idx: " . str_replace("\n", " ", $f) . "\n";
        }
    }

    $zip->close();
}
