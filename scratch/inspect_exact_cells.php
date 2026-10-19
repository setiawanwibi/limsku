<?php
define('BASEPATH', 'true');
require_once 'C:/xampp/htdocs/LIMSKU/application/libraries/SimpleXLSX.php';

$filePath = 'C:/Users/ASUS/OneDrive/Dokumen/Magang BPOM/Data BPOM/Format Parameter Pengujian/Mikrobiologi/CP mikro.xlsx';
$xlsx = SimpleXLSX::parse($filePath);

for ($r = 0; $r <= 37; $r++) {
    $row = $xlsx->rows()[$r] ?? [];
    $cells = [];
    foreach ($row as $c => $val) {
        $colLetter = chr(65 + $c);
        $v = trim((string)$val);
        $cells[$colLetter] = $v;
    }
    // Filter non-empty cells
    $nonEmpty = array_filter($cells, function($x) { return $x !== ''; });
    $rNum = $r + 1;
    if (!empty($nonEmpty)) {
        echo "Row $rNum: ";
        foreach ($nonEmpty as $col => $v) {
            echo "[$col] = \"$v\" | ";
        }
        echo "\n";
    }
}
