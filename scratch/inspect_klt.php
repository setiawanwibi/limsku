<?php
$zip = new ZipArchive();
$file = 'C:/Users/ASUS/OneDrive/Dokumen/Magang BPOM/Data BPOM/Format Parameter Pengujian/Kimia/POM-06.02_CFM.01_SOP.01_F.01A.10_LCP_KROMATOGRAFI LAPIS TIPIS.xlsx';

if ($zip->open($file) === TRUE) {
    echo "=== ARCHIVE OPENED: $file ===\n";
    
    // 1. Cek daftar sheet
    $wbXml = $zip->getFromName('xl/workbook.xml');
    if ($wbXml) {
        $xml = simplexml_load_string($wbXml);
        echo "Sheets:\n";
        foreach ($xml->sheets->sheet as $sh) {
            echo " - " . (string)$sh['name'] . " (sheetId: " . (string)$sh['sheetId'] . ", id: " . (string)$sh->attributes('http://schemas.openxmlformats.org/officeDocument/2006/relationships')['id'] . ")\n";
        }
    }

    // 2. Baca shared strings
    $stringsXml = $zip->getFromName('xl/sharedStrings.xml');
    $strings = [];
    if ($stringsXml) {
        $sXml = simplexml_load_string($stringsXml);
        foreach ($sXml->si as $si) {
            $t = '';
            if (isset($si->t)) $t = (string)$si->t;
            elseif (isset($si->r)) {
                foreach ($si->r as $r) $t .= (string)$r->t;
            }
            $strings[] = $t;
        }
    }

    // 3. Baca sheet1.xml
    $sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml');
    if ($sheetXml) {
        $xml = simplexml_load_string($sheetXml);
        echo "\n=== SHEET 1 CONTENT ===\n";
        foreach ($xml->sheetData->row as $row) {
            $line = [];
            foreach ($row->c as $c) {
                $ref = (string)$c['r'];
                $type = (string)$c['t'];
                $f = isset($c->f) ? '=' . (string)$c->f : '';
                $v = isset($c->v) ? (string)$c->v : '';
                if ($type === 's' && isset($strings[(int)$v])) {
                    $val = $strings[(int)$v];
                } else {
                    $val = $v;
                }
                if ($f) {
                    $line[] = "$ref: [FORMULA: $f] (val: $val)";
                } elseif ($val !== '') {
                    $line[] = "$ref: $val";
                }
            }
            if (!empty($line)) {
                echo implode(' | ', $line) . "\n";
            }
        }
    }

    $zip->close();
} else {
    echo "Gagal membuka file: $file\n";
}
