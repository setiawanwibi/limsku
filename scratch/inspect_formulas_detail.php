<?php
$zip = new ZipArchive();
$file = 'C:/Users/ASUS/OneDrive/Dokumen/Magang BPOM/Data BPOM/Format Parameter Pengujian/Kimia/POM-06.02_CFM.01_SOP.01_F.01A.02_LCP_KERAGAMAN BOBOT.xlsx';

if ($zip->open($file) === TRUE) {
    $sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml');
    $xml = simplexml_load_string($sheetXml);
    foreach ($xml->sheetData->row as $row) {
        $rNum = (int)$row['r'];
        if ($rNum >= 38 && $rNum <= 56) {
            foreach ($row->c as $c) {
                if (isset($c->f)) {
                    echo (string)$c['r'] . ' => ' . (string)$c->f . "\n";
                }
            }
        }
    }
}
