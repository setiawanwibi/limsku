<?php
define('BASEPATH', 'defined');
define('ENVIRONMENT', 'development');
require_once __DIR__ . '/../application/config/database.php';
$db_config = $db['default'];

$mysqli = new mysqli($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);
$res = $mysqli->query("SELECT skema_form FROM form_templates WHERE id = 19");
$row = $res->fetch_assoc();
$skema = json_decode($row['skema_form'], true);

echo "KEYS IN SKEMA:\n";
print_r(array_keys($skema));

if (isset($skema['sections'])) {
    echo "\nSECTIONS COUNT: " . count($skema['sections']) . "\n";
    foreach ($skema['sections'] as $i => $sec) {
        echo "[$i] " . (isset($sec['title']) ? $sec['title'] : 'no title') . " | Type: " . (isset($sec['type']) ? $sec['type'] : 'fields') . "\n";
        if (isset($sec['fields'])) {
            foreach ($sec['fields'] as $f) {
                echo "    - " . (isset($f['id']) ? $f['id'] : '') . " (" . (isset($f['label']) ? $f['label'] : '') . ") type=" . (isset($f['type']) ? $f['type'] : 'text') . "\n";
            }
        }
    }
}

echo "\n--- ACTUAL DATA_HASIL IN test_results id=24 ---\n";
$res2 = $mysqli->query("SELECT data_hasil FROM test_results WHERE id = 24");
$r2 = $res2->fetch_assoc();
print_r(json_decode($r2['data_hasil'], true));

$mysqli->close();
