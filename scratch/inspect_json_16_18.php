<?php
$json = json_decode(file_get_contents(__DIR__ . '/reconciliation_data.json'), true);
foreach ($json['shifted_records'] as $s) {
    if ($s['id'] == 16 || $s['id'] == 18) {
        echo "=== ID {$s['id']} ===\n";
        print_r($s);
    }
}
