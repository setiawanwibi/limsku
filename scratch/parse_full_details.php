<?php
// parse_full_details.php
$dir = 'C:\Users\ASUS\OneDrive\Dokumen\Magang BPOM\Data BPOM\Format Parameter Pengujian\Mikrobiologi';
$filename = 'POM-06.02_CFM.01_SOP.01_F.01B.13_Cemaran Mikroba Kualitatif Sediaan Farmasi_.xlsx';
$filepath = $dir . DIRECTORY_SEPARATOR . $filename;

$zip = new ZipArchive();
$zip->open($filepath);

$shared_strings = [];
$ss_xml = $zip->getFromName('xl/sharedStrings.xml');
if ($ss_xml) {
    $xml = simplexml_load_string($ss_xml);
    foreach ($xml->si as $si) {
        if (isset($si->t)) {
            $shared_strings[] = (string)$si->t;
        } elseif (isset($si->r)) {
            $text = '';
            foreach ($si->r as $r) { $text .= (string)$r->t; }
            $shared_strings[] = $text;
        } else {
            $shared_strings[] = '';
        }
    }
}

$wb_xml = simplexml_load_string($zip->getFromName('xl/workbook.xml'));
$wb_rels = simplexml_load_string($zip->getFromName('xl/_rels/workbook.xml.rels'));
$sheet_targets = [];
foreach ($wb_rels->Relationship as $rel) {
    $sheet_targets[(string)$rel['Id']] = 'xl/' . ltrim((string)$rel['Target'], '/');
}

foreach ($wb_xml->sheets->sheet as $s) {
    $name = (string)$s['name'];
    $rId = (string)$s->attributes('http://schemas.openxmlformats.org/officeDocument/2006/relationships')['id'];
    $path = $sheet_targets[$rId];
    
    echo "======================================================================\n";
    echo "WORKSHEET: $name (Lanjutan Baris 61 s/d Akhir)\n";
    echo "======================================================================\n";
    
    $xml = simplexml_load_string($zip->getFromName($path));
    $cells = [];
    if (isset($xml->sheetData->row)) {
        foreach ($xml->sheetData->row as $row) {
            foreach ($row->c as $c) {
                $ref = (string)$c['r'];
                $type = (string)$c['t'];
                $val = '';
                $formula = isset($c->f) ? (string)$c->f : null;
                if ($type === 's') {
                    $val = $shared_strings[(int)$c->v] ?? '';
                } elseif (isset($c->v)) {
                    $val = (string)$c->v;
                }
                if ($val !== '' || $formula !== null) {
                    $cells[$ref] = ['val' => $val, 'formula' => $formula];
                }
            }
        }
    }
    
    uksort($cells, function($a, $b) {
        preg_match('/([A-Z]+)([0-9]+)/', $a, $mA);
        preg_match('/([A-Z]+)([0-9]+)/', $b, $mB);
        if ($mA[2] != $mB[2]) return (int)$mA[2] - (int)$mB[2];
        return strcmp($mA[1], $mB[1]);
    });
    
    foreach ($cells as $coord => $d) {
        preg_match('/([A-Z]+)([0-9]+)/', $coord, $m);
        $row = (int)$m[2];
        if ($row > 60) {
            $f_str = $d['formula'] ? " [FORMULA: =" . $d['formula'] . "]" : "";
            echo sprintf("%-6s = %s%s\n", $coord, $d['val'], $f_str);
        }
    }
}

$zip->close();
