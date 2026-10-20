<?php
$f = file_get_contents('d:/xampp/htdocs/limsku/scratch/test_reaksi_warna_print.pdf');
preg_match_all('/\/Type\s*\/Page\b/', $f, $m);
echo 'Count of /Page: ' . count($m[0]) . PHP_EOL;

preg_match_all('/\/Count\s+(\d+)/', $f, $m2);
print_r($m2[0]);
