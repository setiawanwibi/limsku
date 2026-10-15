<?php
define('BASEPATH', 'defined');
$zip = new ZipArchive();
$tempFile = sys_get_temp_dir() . '/test_template.xlsx';
if ($zip->open($tempFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
    $zip->addFromString('[Content_Types].xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">
  <Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>
  <Default Extension="xml" ContentType="application/xml"/>
  <Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>
  <Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>
  <Override PartName="/xl/sharedStrings.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sharedStrings+xml"/>
</Types>');

    $zip->addFromString('_rels/.rels', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>
</Relationships>');

    $zip->addFromString('xl/_rels/workbook.xml.rels', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>
  <Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/sharedStrings" Target="sharedStrings.xml"/>
</Relationships>');

    $zip->addFromString('xl/workbook.xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">
  <sheets>
    <sheet name="Sheet1" sheetId="1" r:id="rId1"/>
  </sheets>
</workbook>');

    $headers = ['No', 'Kode Barang', 'Katalog', 'Nama Barang', 'Merk', 'Stok', 'Jenis Barang', 'Expired Date'];
    $sample1 = ['1', 'BRG-001', 'KAT-1001', 'Asam Sulfat H2SO4 p.a 98%', 'Merck', '2 BOTOL', 'Reagen Cair', '2027-12-31'];
    $sample2 = ['2', 'BRG-002', 'KAT-1002', 'Nutrient Agar (NA)', 'Oxoid', '1 PAC', 'Media Mikrobiologi', ''];
    
    $all_strings = array_merge($headers, $sample1, $sample2);
    $unique_strings = array_values(array_unique($all_strings));
    $str_map = array_flip($unique_strings);

    $sstXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";
    $sstXml .= '<sst xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" count="' . count($all_strings) . '" uniqueCount="' . count($unique_strings) . '">';
    foreach ($unique_strings as $s) {
        $sstXml .= '<si><t>' . htmlspecialchars($s, ENT_XML1, 'UTF-8') . '</t></si>';
    }
    $sstXml .= '</sst>';
    $zip->addFromString('xl/sharedStrings.xml', $sstXml);

    $sheetXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";
    $sheetXml .= '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><sheetData>';
    
    // Header row
    $sheetXml .= '<row r="1">';
    $cols = ['A','B','C','D','E','F','G','H'];
    foreach ($headers as $ci => $h) {
        $ref = $cols[$ci] . '1';
        $sheetXml .= '<c r="' . $ref . '" t="s"><v>' . $str_map[$h] . '</v></c>';
    }
    $sheetXml .= '</row>';

    // Row 2
    $sheetXml .= '<row r="2">';
    foreach ($sample1 as $ci => $val) {
        $ref = $cols[$ci] . '2';
        $sheetXml .= '<c r="' . $ref . '" t="s"><v>' . $str_map[$val] . '</v></c>';
    }
    $sheetXml .= '</row>';

    // Row 3
    $sheetXml .= '<row r="3">';
    foreach ($sample2 as $ci => $val) {
        $ref = $cols[$ci] . '3';
        if ($val === '') {
            $sheetXml .= '<c r="' . $ref . '"/>';
        } else {
            $sheetXml .= '<c r="' . $ref . '" t="s"><v>' . $str_map[$val] . '</v></c>';
        }
    }
    $sheetXml .= '</row>';

    $sheetXml .= '</sheetData></worksheet>';
    $zip->addFromString('xl/worksheets/sheet1.xml', $sheetXml);

    $zip->close();
    echo "SUCCESS GENERATING XLSX: " . filesize($tempFile) . " bytes\n";

    // Test with SimpleXLSX
    require_once __DIR__ . '/../application/libraries/SimpleXLSX.php';
    $xlsx = SimpleXLSX::parse($tempFile);
    if ($xlsx) {
        echo "SimpleXLSX parsed rows:\n";
        print_r($xlsx->rows());
    } else {
        echo "Parse error: " . SimpleXLSX::parseError() . "\n";
    }
    unlink($tempFile);
}
