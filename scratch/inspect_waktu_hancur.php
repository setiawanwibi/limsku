<?php
$zip = new ZipArchive();
$file = 'C:/Users/ASUS/OneDrive/Dokumen/Magang BPOM/Data BPOM/Format Parameter Pengujian/Kimia/POM-06.02_CFM.01_SOP.01_F.01A.04_LCP_WAKTU HANCUR.xlsx';

if ($zip->open($file) === TRUE) {
    echo "Archive opened: $file\n";
    $sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml');
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
    
    $xml = simplexml_load_string($sheetXml);
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
                $line[] = $ref . ": [FORMULA: " . $f . "] (val: " . $val . ")";
            } elseif ($val !== '') {
                $line[] = $ref . ": " . $val;
            }
        }
        if (!empty($line)) {
            echo implode(' | ', $line) . "\n";
        }
    }
    $zip->close();
} else {
    echo "Failed to open $file\n";
}
