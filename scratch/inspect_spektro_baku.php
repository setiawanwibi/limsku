<?php
$zip = new ZipArchive();
$file = 'C:/Users/ASUS/OneDrive/Dokumen/Magang BPOM/Data BPOM/Format Parameter Pengujian/Kimia/POM-06.02_CFM.01_SOP.01_F.01A.06_LCP_SPEKTROFOTOMETRI.xlsx';

if ($zip->open($file) === TRUE) {
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
        $rNum = (int)$row['r'];
        if ($rNum >= 24 && $rNum <= 27) {
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
                $str = $ref . ": " . $val;
                if ($f) $str .= " [F: $f]";
                $line[] = $str;
            }
            echo "Row $rNum: " . implode(' | ', $line) . "\n";
        }
    }
    $zip->close();
}
