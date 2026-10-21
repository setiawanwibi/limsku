<?php
$file = 'C:/Users/ASUS/OneDrive/Dokumen/Magang BPOM/Data BPOM/Format Parameter Pengujian/Kimia/POM-06.02_CFM.01_SOP.01_F.01A.12_LCP_KROMATOGRAFI CAIR KINERJA TINGGI.xlsx';
$zip = new ZipArchive();
if ($zip->open($file) === TRUE) {
    echo "=== ARCHIVE OPENED: $file ===\n";
    $workbookXml = simplexml_load_string($zip->getFromName('xl/workbook.xml'));
    echo "Sheets:\n";
    foreach ($workbookXml->sheets->sheet as $sh) {
        echo " - " . $sh['name'] . " (sheetId: " . $sh['sheetId'] . ", id: " . $sh['id'] . ")\n";
    }

    $stringsXml = simplexml_load_string($zip->getFromName('xl/sharedStrings.xml'));
    $strings = [];
    foreach ($stringsXml->si as $si) {
        $text = '';
        if (isset($si->t)) $text = (string)$si->t;
        elseif (isset($si->r)) {
            foreach ($si->r as $r) {
                $text .= (string)$r->t;
            }
        }
        $strings[] = $text;
    }

    $xml = simplexml_load_string($zip->getFromName('xl/worksheets/sheet1.xml'));
    echo "\n=== SHEET 1 CONTENT (first 70 rows) ===\n";
    foreach ($xml->sheetData->row as $r) {
        $rn = (int)$r['r'];
        if ($rn <= 70) {
            $line = "Row $rn: ";
            $hasData = false;
            foreach ($r->c as $c) {
                $pos = (string)$c['r'];
                $t = (string)$c['t'];
                $val = (string)$c->v;
                $formula = isset($c->f) ? (string)$c->f : '';
                if ($t === 's' && isset($strings[(int)$val])) {
                    $valStr = $strings[(int)$val];
                } else {
                    $valStr = $val;
                }
                if ($valStr !== '' || $formula !== '') {
                    $hasData = true;
                    $line .= "[$pos: $valStr" . ($formula ? " (F: $formula)" : "") . "] ";
                }
            }
            if ($hasData) {
                echo $line . "\n";
            }
        }
    }
} else {
    echo "Failed to open $file\n";
}
