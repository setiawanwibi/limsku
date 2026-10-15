<?php
// inspect_cemaran_kualitatif.php
$dir = 'C:\Users\ASUS\OneDrive\Dokumen\Magang BPOM\Data BPOM\Format Parameter Pengujian\Mikrobiologi';
$filename = 'POM-06.02_CFM.01_SOP.01_F.01B.13_Cemaran Mikroba Kualitatif Sediaan Farmasi_.xlsx';
$filepath = $dir . DIRECTORY_SEPARATOR . $filename;

$zip = new ZipArchive();
if ($zip->open($filepath) !== TRUE) {
    die("Cannot open file: $filepath\n");
}

// 1. Shared Strings
$shared_strings = [];
$ss_xml = $zip->getFromName('xl/sharedStrings.xml');
if ($ss_xml) {
    $xml = simplexml_load_string($ss_xml);
    foreach ($xml->si as $si) {
        if (isset($si->t)) {
            $shared_strings[] = (string)$si->t;
        } elseif (isset($si->r)) {
            $text = '';
            foreach ($si->r as $r) {
                $text .= (string)$r->t;
            }
            $shared_strings[] = $text;
        } else {
            $shared_strings[] = '';
        }
    }
}

// 2. Map sheet relationships
$wb_xml = simplexml_load_string($zip->getFromName('xl/workbook.xml'));
$wb_rels = simplexml_load_string($zip->getFromName('xl/_rels/workbook.xml.rels'));
$sheet_targets = [];
foreach ($wb_rels->Relationship as $rel) {
    $sheet_targets[(string)$rel['Id']] = 'xl/' . ltrim((string)$rel['Target'], '/');
}

$sheets = [];
foreach ($wb_xml->sheets->sheet as $s) {
    $rId = (string)$s->attributes('http://schemas.openxmlformats.org/officeDocument/2006/relationships')['id'];
    $sheets[] = [
        'name' => (string)$s['name'],
        'state' => (string)$s['state'] ?: 'visible',
        'path' => $sheet_targets[$rId]
    ];
}

// Helper to extract non-empty cells
function extract_sheet_cells($zip, $path, $shared_strings) {
    $xml_str = $zip->getFromName($path);
    if (!$xml_str) return [];
    $xml = simplexml_load_string($xml_str);
    
    $cells = [];
    if (isset($xml->sheetData->row)) {
        foreach ($xml->sheetData->row as $row) {
            foreach ($row->c as $c) {
                $ref = (string)$c['r'];
                $type = (string)$c['t'];
                $val = '';
                $formula = isset($c->f) ? (string)$c->f : null;
                
                if ($type === 's') {
                    $idx = (int)$c->v;
                    $val = $shared_strings[$idx] ?? '';
                } elseif (isset($c->v)) {
                    $val = (string)$c->v;
                }
                
                // Only store if non-empty string or has formula or value
                if ($val !== '' || $formula !== null) {
                    $cells[$ref] = [
                        'val' => $val,
                        'formula' => $formula,
                        'type' => $type ?: 'number'
                    ];
                }
            }
        }
    }
    
    // Merge cells
    $merges = [];
    if (isset($xml->mergeCells->mergeCell)) {
        foreach ($xml->mergeCells->mergeCell as $mc) {
            $merges[] = (string)$mc['ref'];
        }
    }
    
    return ['cells' => $cells, 'merges' => $merges];
}

echo "=== INSPEKSI MENDALAM: CEMARAN MIKROBA KUALITATIF SEDIAAN FARMASI ===\n";
echo "File: $filename\n\n";

$sheet_data = [];
foreach ($sheets as $sh) {
    echo "======================================================================\n";
    echo "WORKSHEET: " . $sh['name'] . " (Status: " . strtoupper($sh['state']) . ")\n";
    echo "======================================================================\n";
    $extracted = extract_sheet_cells($zip, $sh['path'], $shared_strings);
    $sheet_data[$sh['name']] = $extracted;
    
    echo "Total Non-empty cells: " . count($extracted['cells']) . "\n";
    echo "Total Merged ranges: " . count($extracted['merges']) . "\n";
    echo "Daftar Merged Ranges (Contoh 15 pertama):\n";
    echo implode(', ', array_slice($extracted['merges'], 0, 15)) . "\n\n";
    
    echo "Sample Isi Sel Non-Empty (Baris 1 s/d 60):\n";
    // Sort cells by row and col
    uksort($extracted['cells'], function($a, $b) {
        preg_match('/([A-Z]+)([0-9]+)/', $a, $mA);
        preg_match('/([A-Z]+)([0-9]+)/', $b, $mB);
        if ($mA[2] != $mB[2]) return (int)$mA[2] - (int)$mB[2];
        return strcmp($mA[1], $mB[1]);
    });
    
    foreach ($extracted['cells'] as $coord => $d) {
        preg_match('/([A-Z]+)([0-9]+)/', $coord, $m);
        $row = (int)$m[2];
        if ($row <= 60) {
            $f_str = $d['formula'] ? " [FORMULA: =" . $d['formula'] . "]" : "";
            echo sprintf("%-6s = %s%s\n", $coord, $d['val'], $f_str);
        }
    }
}

$zip->close();
