<?php
// diff_rapih_vs_contoh.php
$dir = 'C:\Users\ASUS\OneDrive\Dokumen\Magang BPOM\Data BPOM\Format Parameter Pengujian\Mikrobiologi';
$filename = 'POM-06.02_CFM.01_SOP.01_F.01B.13_Cemaran Mikroba Kualitatif Sediaan Farmasi_.xlsx';
$filepath = $dir . DIRECTORY_SEPARATOR . $filename;

$zip = new ZipArchive();
$zip->open($filepath);

$shared_strings = [];
$ss_xml = simplexml_load_string($zip->getFromName('xl/sharedStrings.xml'));
foreach ($ss_xml->si as $si) {
    if (isset($si->t)) $shared_strings[] = (string)$si->t;
    elseif (isset($si->r)) {
        $t = '';
        foreach ($si->r as $r) $t .= (string)$r->t;
        $shared_strings[] = $t;
    } else $shared_strings[] = '';
}

function get_sheet_cells($zip, $target, $shared_strings) {
    $xml = simplexml_load_string($zip->getFromName($target));
    $cells = [];
    if (isset($xml->sheetData->row)) {
        foreach ($xml->sheetData->row as $row) {
            foreach ($row->c as $c) {
                $ref = (string)$c['r'];
                $type = (string)$c['t'];
                $val = '';
                if ($type === 's') $val = $shared_strings[(int)$c->v] ?? '';
                elseif (isset($c->v)) $val = (string)$c->v;
                if ($val !== '' || isset($c->f)) {
                    $cells[$ref] = $val;
                }
            }
        }
    }
    return $cells;
}

$wb_rels = simplexml_load_string($zip->getFromName('xl/_rels/workbook.xml.rels'));
$wb_xml = simplexml_load_string($zip->getFromName('xl/workbook.xml'));
$sheet_targets = [];
foreach ($wb_rels->Relationship as $rel) {
    $sheet_targets[(string)$rel['Id']] = 'xl/' . ltrim((string)$rel['Target'], '/');
}

$sheets = [];
foreach ($wb_xml->sheets->sheet as $s) {
    $sheets[(string)$s['name']] = $sheet_targets[(string)$s->attributes('http://schemas.openxmlformats.org/officeDocument/2006/relationships')['id']];
}

$rapih = get_sheet_cells($zip, $sheets['Rapih'], $shared_strings);
$contoh = get_sheet_cells($zip, $sheets['contoh isi'], $shared_strings);
$kuali = get_sheet_cells($zip, $sheets['Kuali Sediaan Farmasi doc'], $shared_strings);

echo "=== PERBANDINGAN SHEET 'Rapih' VS 'contoh isi' ===\n";
$diff_rapih_contoh = [];
$all_keys = array_unique(array_merge(array_keys($rapih), array_keys($contoh)));
sort($all_keys);

foreach ($all_keys as $k) {
    $v1 = $rapih[$k] ?? '[KOSONG]';
    $v2 = $contoh[$k] ?? '[KOSONG]';
    if ($v1 !== $v2) {
        $diff_rapih_contoh[$k] = ['Rapih' => $v1, 'contoh_isi' => $v2];
    }
}

echo "Jumlah perbedaan antara Rapih dan contoh isi: " . count($diff_rapih_contoh) . "\n";
foreach ($diff_rapih_contoh as $coord => $vals) {
    echo sprintf("%-6s | Rapih: %-30s | contoh isi: %-30s\n", $coord, substr($vals['Rapih'], 0, 30), substr($vals['contoh_isi'], 0, 30));
}

echo "\n=== PERBANDINGAN TATA LETAK SHEET 'Rapih' VS 'Kuali Sediaan Farmasi doc' (HIDDEN) ===\n";
echo "Total cells di Rapih: " . count($rapih) . " | Total cells di Kuali doc: " . count($kuali) . "\n";

$zip->close();
