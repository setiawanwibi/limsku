<?php
$excelPath = 'C:\\Users\\ASUS\\OneDrive\\Dokumen\\Magang BPOM\\Data BPOM\\Format Data Sampel\\Sampel Kosmetik\\10. Data Contoh Kosmetik Oktober 2026 - 2.xlsx';

$zip = new ZipArchive();
if ($zip->open($excelPath) === TRUE) {
    // Read shared strings
    $sharedStrings = [];
    $ssXml = $zip->getFromName('xl/sharedStrings.xml');
    if ($ssXml) {
        $xml = simplexml_load_string($ssXml);
        foreach ($xml->si as $si) {
            $sharedStrings[] = (string)($si->t ?? $si->r->t ?? '');
            if (!isset($si->t) && isset($si->r)) {
                $t = '';
                foreach ($si->r as $r) {
                    $t .= (string)$r->t;
                }
                $sharedStrings[count($sharedStrings)-1] = $t;
            }
        }
    }
    
    // Read sheet1.xml
    $sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml');
    if ($sheetXml) {
        $xml = simplexml_load_string($sheetXml);
        
        echo "=== MERGE CELLS ===\n";
        if (isset($xml->mergeCells)) {
            foreach ($xml->mergeCells->mergeCell as $mc) {
                echo "  " . (string)$mc['ref'] . "\n";
            }
        }
        
        echo "\n=== ROWS 1 to 9 CELLS ===\n";
        foreach ($xml->sheetData->row as $r) {
            $rNum = (int)$r['r'];
            if ($rNum <= 10) {
                echo "Row $rNum:\n";
                foreach ($r->c as $c) {
                    $ref = (string)$c['r'];
                    $t = (string)$c['t'];
                    $v = (string)$c->v;
                    $val = ($t === 's' && isset($sharedStrings[(int)$v])) ? $sharedStrings[(int)$v] : $v;
                    echo "  $ref: " . json_encode(str_replace("\n", " ", $val)) . "\n";
                }
            }
        }
    }
    $zip->close();
}
