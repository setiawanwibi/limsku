<?php
$file = 'C:/Users/ASUS/OneDrive/Dokumen/Magang BPOM/Data BPOM/Format Parameter Pengujian/Kimia/POM-06.02_CFM.01_SOP.01_F.01A.12_LCP_KROMATOGRAFI CAIR KINERJA TINGGI.xlsx';
$zip = new ZipArchive();
if ($zip->open($file) === TRUE) {
    echo "=== AUDIT FILE: " . basename($file) . " ===\n";
    $workbookXml = simplexml_load_string($zip->getFromName('xl/workbook.xml'));
    echo "Daftar Sheets:\n";
    $sheets = [];
    foreach ($workbookXml->sheets->sheet as $sh) {
        $sheets[] = [
            'name' => (string)$sh['name'],
            'sheetId' => (string)$sh['sheetId'],
            'rId' => (string)$sh->attributes('http://schemas.openxmlformats.org/officeDocument/2006/relationships')['id']
        ];
        echo " - Sheet: " . $sh['name'] . " | ID: " . $sh['sheetId'] . "\n";
    }

    // Periksa setiap sheet yang ada
    for ($i = 1; $i <= count($sheets); $i++) {
        $sheetFile = "xl/worksheets/sheet{$i}.xml";
        if ($zip->locateName($sheetFile) !== false) {
            $xml = simplexml_load_string($zip->getFromName($sheetFile));
            $maxRow = 0;
            $minRow = 999999;
            $rowCount = 0;
            foreach ($xml->sheetData->row as $r) {
                $rn = (int)$r['r'];
                if ($rn > $maxRow) $maxRow = $rn;
                if ($rn < $minRow) $minRow = $rn;
                $rowCount++;
            }
            echo "Sheet {$i} ({$sheets[$i-1]['name']}): Total rows = $rowCount, Range = Row $minRow to Row $maxRow\n";
        }
    }
}
