<?php
// parse_xlsx_inventory.php
$dir = 'C:\Users\ASUS\OneDrive\Dokumen\Magang BPOM\Data BPOM\Format Parameter Pengujian\Mikrobiologi';

$files = scandir($dir);
$inventory = [];

foreach ($files as $f) {
    if (!in_array(strtolower(pathinfo($f, PATHINFO_EXTENSION)), ['xlsx', 'xlsm', 'xls'])) continue;
    $filepath = $dir . DIRECTORY_SEPARATOR . $f;
    
    $zip = new ZipArchive();
    if ($zip->open($filepath) !== TRUE) {
        $inventory[$f] = ['error' => 'Cannot open zip'];
        continue;
    }
    
    // Read workbook.xml to get sheets and their state
    $workbook_xml = $zip->getFromName('xl/workbook.xml');
    $sheets = [];
    if ($workbook_xml) {
        $xml = simplexml_load_string($workbook_xml);
        foreach ($xml->sheets->sheet as $s) {
            $name = (string)$s['name'];
            $sheetId = (string)$s['sheetId'];
            $state = (string)$s['state'] ?: 'visible'; // hidden or veryHidden
            $rId = (string)$s->attributes('http://schemas.openxmlformats.org/officeDocument/2006/relationships')['id'];
            $sheets[] = [
                'name' => $name,
                'sheetId' => $sheetId,
                'state' => $state,
                'rId' => $rId
            ];
        }
    }
    
    // Read sharedStrings.xml
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
    
    // Map relationships to find sheet XML path
    $sheet_paths = [];
    $wb_rels_xml = $zip->getFromName('xl/_rels/workbook.xml.rels');
    if ($wb_rels_xml) {
        $xml = simplexml_load_string($wb_rels_xml);
        foreach ($xml->Relationship as $rel) {
            $rId = (string)$rel['Id'];
            $target = (string)$rel['Target'];
            $sheet_paths[$rId] = 'xl/' . ltrim($target, '/');
        }
    }
    
    // Inspect each sheet
    $sheet_details = [];
    foreach ($sheets as &$sh) {
        $target_path = $sheet_paths[$sh['rId']] ?? null;
        if (!$target_path) continue;
        
        $sh_xml = $zip->getFromName($target_path);
        if (!$sh_xml) continue;
        
        $xml = simplexml_load_string($sh_xml);
        
        // Dimensions
        $dimension = (string)$xml->dimension['ref'] ?? 'N/A';
        
        // Merged cells
        $merged = [];
        if (isset($xml->mergeCells->mergeCell)) {
            foreach ($xml->mergeCells->mergeCell as $mc) {
                $merged[] = (string)$mc['ref'];
            }
        }
        
        // Page setup & print area
        $pageSetup = [];
        if (isset($xml->pageSetup)) {
            foreach ($xml->pageSetup->attributes() as $k => $v) {
                $pageSetup[$k] = (string)$v;
            }
        }
        
        // Count non-empty cells
        $non_empty_cells = 0;
        $formulas = [];
        if (isset($xml->sheetData->row)) {
            foreach ($xml->sheetData->row as $row) {
                foreach ($row->c as $c) {
                    $non_empty_cells++;
                    if (isset($c->f)) {
                        $formulas[(string)$c['r']] = (string)$c->f;
                    }
                }
            }
        }
        
        $sh['dimension'] = $dimension;
        $sh['non_empty_cells'] = $non_empty_cells;
        $sh['merged_count'] = count($merged);
        $sh['formula_count'] = count($formulas);
        $sh['pageSetup'] = $pageSetup;
    }
    
    $inventory[$f] = [
        'size' => filesize($filepath),
        'sheets' => $sheets,
        'shared_strings_count' => count($shared_strings)
    ];
    
    $zip->close();
}

echo "=== INVENTARISASI WORKBOOK MIKROBIOLOGI ===\n\n";
foreach ($inventory as $filename => $info) {
    echo "FILE: $filename (" . number_format($info['size']) . " bytes)\n";
    echo "Total Shared Strings: " . ($info['shared_strings_count'] ?? 0) . "\n";
    echo "Worksheets (" . count($info['sheets'] ?? []) . "):\n";
    foreach ($info['sheets'] ?? [] as $s) {
        echo sprintf("  - [%-10s] %-45s | Dim: %-12s | Cells: %-4d | Merged: %-3d | Formulas: %-2d\n",
            strtoupper($s['state']), $s['name'], $s['dimension'], $s['non_empty_cells'], $s['merged_count'], $s['formula_count']);
        if (!empty($s['pageSetup'])) {
            $orient = $s['pageSetup']['orientation'] ?? 'default';
            $scale = $s['pageSetup']['scale'] ?? '100';
            $paper = $s['pageSetup']['paperSize'] ?? 'default';
            echo "    -> PageSetup: Orientation=$orient, Scale=$scale%, PaperSize=$paper\n";
        }
    }
    echo "--------------------------------------------------------------------------------------------------------\n";
}
