<?php
$excelPath = 'C:\\Users\\ASUS\\OneDrive\\Dokumen\\Magang BPOM\\Data BPOM\\Format Data Sampel\\Sampel Obat Tradisional dan Suplemen\\6. Data contoh OTSK JUNI 2026 NEW.xlsx';

$zip = new ZipArchive();
if ($zip->open($excelPath) === TRUE) {
    // Check sheet1 (OBA) and sheet2 (SK)
    for ($s = 1; $s <= 2; $s++) {
        $sheetXml = $zip->getFromName("xl/worksheets/sheet{$s}.xml");
        if ($sheetXml) {
            $xml = simplexml_load_string($sheetXml);
            echo "=== SHEET $s MERGE CELLS ===\n";
            if (isset($xml->mergeCells)) {
                $mcs = [];
                foreach ($xml->mergeCells->mergeCell as $mc) {
                    $mcs[] = (string)$mc['ref'];
                }
                echo implode(', ', $mcs) . "\n\n";
            }
        }
    }
    $zip->close();
}
