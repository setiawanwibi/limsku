<?php
require_once __DIR__ . '/../index.php';
$CI =& get_instance();

echo "Instruments komoditi distinct values:\n";
$res = $CI->db->query("SELECT komoditi, COUNT(*) as cnt FROM instruments GROUP BY komoditi")->result_array();
print_r($res);

echo "\nSamples komoditi distinct values:\n";
$res2 = $CI->db->query("SELECT komoditi, COUNT(*) as cnt FROM samples GROUP BY komoditi")->result_array();
print_r($res2);

echo "\nLogbooks sample komoditi:\n";
$res3 = $CI->db->query("SELECT s.komoditi, COUNT(*) as cnt FROM instrument_logbooks il JOIN samples s ON s.id = il.sample_id GROUP BY s.komoditi")->result_array();
print_r($res3);
