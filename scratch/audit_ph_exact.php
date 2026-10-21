<?php
$file = 'C:/Users/ASUS/OneDrive/Dokumen/Magang BPOM/Data BPOM/Format Parameter Pengujian/Kimia/POM-06.02_CFM.01_SOP.01_F.01A.27_LCP_pH.xlsx';
$zip = new ZipArchive();
if ($zip->open($file) === TRUE) {
    echo "=== AUDIT FILE: " . basename($file) . " ===\n";
    $workbookXml = simplexml_load_string($zip->getFromName('xl/workbook.xml'));
    echo "Daftar Sheets:\n";
    $sheets = [];
    foreach ($workbookXml->sheets->sheet as $sh) {
        $name = (string)$sh['name'];
        $sheetId = (string)$sh['sheetId'];
        $sheets[] = ['name' => $name, 'id' => $sheetId];
        echo " - Sheet: $name (sheetId: $sheetId)\n";
    }

    $stringsXml = simplexml_load_string($zip->getFromName('xl/sharedStrings.xml'));
    $strings = [];
    if ($stringsXml) {
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
    }

    for ($i = 1; $i <= count($sheets); $i++) {
        $sheetFile = "xl/worksheets/sheet{$i}.xml";
        if ($zip->locateName($sheetFile) !== false) {
            $xml = simplexml_load_string($zip->getFromName($sheetFile));
            $rowCount = count($xml->sheetData->row);
            echo "\n--- Sheet {$i}: {$sheets[$i-1]['name']} (Rows: $rowCount) ---\n";
            foreach ($xml->sheetData->row as $r) {
                $rn = (int)$r['r'];
                $hasData = false;
                $line = "Row $rn: ";
                foreach ($r->c as $c) {
                    $pos = (string)$c['r'];
                    $t = (string)$c['t'];
                    $val = (string)$c->v;
                    $formula = isset($c->f) ? (string)$c->f : '';
                    if ($t === 's' && isset($strings[(int)$val])) $valStr = $strings[(int)$val];
                    else $valStr = $val;
                    if (trim($valStr) !== '' || $formula !== '') {
                        $hasData = true;
                        $line .= "[$pos: $valStr" . ($formula ? " (F: $formula)" : "") . "] ";
                    }
                }
                if ($hasData) {
                    echo $line . "\n";
                }
            }
        }
    }
} else {
    echo "Failed to open $file\n";
}
