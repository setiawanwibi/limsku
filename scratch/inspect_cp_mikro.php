<?php
// inspect_cp_mikro.php
$dir = 'C:\Users\ASUS\OneDrive\Dokumen\Magang BPOM\Data BPOM\Format Parameter Pengujian\Mikrobiologi';
$filename = 'CP mikro.xlsx';
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

$xml = simplexml_load_string($zip->getFromName('xl/worksheets/sheet1.xml'));
$cells = [];
if (isset($xml->sheetData->row)) {
    foreach ($xml->sheetData->row as $row) {
        foreach ($row->c as $c) {
            $ref = (string)$c['r'];
            $type = (string)$c['t'];
            $val = '';
            $formula = isset($c->f) ? (string)$c->f : null;
            if ($type === 's') $val = $shared_strings[(int)$c->v] ?? '';
            elseif (isset($c->v)) $val = (string)$c->v;
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

echo "=== ISI SHEET CP-SIPT PADA CP mikro.xlsx ===\n";
foreach ($cells as $coord => $d) {
    $f_str = $d['formula'] ? " [FORMULA: =" . $d['formula'] . "]" : "";
    echo sprintf("%-6s = %s%s\n", $coord, $d['val'], $f_str);
}

$zip->close();
