<?php
$file = 'C:/Users/ASUS/OneDrive/Dokumen/Magang BPOM/Data BPOM/Format Parameter Pengujian/Kimia/POM-06.02_CFM.01_SOP.01_F.01A.25_LCP_PEMERIAN.xlsx';
$zip = new ZipArchive();
if ($zip->open($file) === TRUE) {
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

    $xml = simplexml_load_string($zip->getFromName('xl/worksheets/sheet1.xml'));
    foreach ($xml->sheetData->row as $r) {
        $rn = (int)$r['r'];
        if ($rn >= 10 && $rn <= 24) {
            $line = "Row $rn: ";
            foreach ($r->c as $c) {
                $pos = (string)$c['r'];
                $t = (string)$c['t'];
                $val = (string)$c->v;
                if ($t === 's' && isset($strings[(int)$val])) $valStr = $strings[(int)$val];
                else $valStr = $val;
                $line .= "[$pos: $valStr] ";
            }
            echo $line . "\n";
        }
    }
}
