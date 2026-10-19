<?php
$zip = new ZipArchive();
$filePath = 'C:/Users/ASUS/OneDrive/Dokumen/Magang BPOM/Data BPOM/Format Parameter Pengujian/Mikrobiologi/POM-06.02_CFM.01_SOP.01_F.01B.13_Cemaran Mikroba Kualitatif Sediaan Farmasi_.xlsx';
if ($zip->open($filePath) === true) {
    echo "=== WORKBOOK SHEETS ===\n";
    $wbXml = $zip->getFromName('xl/workbook.xml');
    if (preg_match_all('/<sheet [^>]*name="([^"]+)"[^>]*sheetId="([^"]+)"/i', $wbXml, $matches)) {
        foreach ($matches[1] as $idx => $name) {
            echo "Sheet: " . $name . "\n";
        }
    }
    
    // Check first sheet
    $sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml');
    echo "\n=== FIRST SHEET MERGED CELLS COUNT ===\n";
    preg_match_all('/<mergeCell ref="([^"]+)"\/>/', $sheetXml, $mCells);
    echo "Count: " . count($mCells[1]) . "\n";
    echo "First 20 merged: " . implode(', ', array_slice($mCells[1], 0, 20)) . "\n";
    
    $zip->close();
} else {
    echo "Cannot open file.\n";
}
