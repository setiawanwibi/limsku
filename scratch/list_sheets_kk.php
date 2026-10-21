<?php
$zip = new ZipArchive();
$file = 'C:/Users/ASUS/OneDrive/Dokumen/Magang BPOM/Data BPOM/Format Parameter Pengujian/Kimia/POM-06.02_CFM.01_SOP.01_F.01A.03_LCP_KESERAGAMAN KANDUNGAN.xlsx';

if ($zip->open($file) === TRUE) {
    $wbXml = $zip->getFromName('xl/workbook.xml');
    echo "=== WORKBOOK SHEETS ===\n";
    if ($wbXml) {
        $xml = simplexml_load_string($wbXml);
        foreach ($xml->sheets->sheet as $sh) {
            echo "Sheet Name: " . (string)$sh['name'] . " | sheetId: " . (string)$sh['sheetId'] . " | r:id: " . (string)$sh->attributes('http://schemas.openxmlformats.org/officeDocument/2006/relationships')['id'] . "\n";
        }
    }
    $zip->close();
}
