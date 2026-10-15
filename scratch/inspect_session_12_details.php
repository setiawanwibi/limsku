<?php
define('BASEPATH', 'defined');
define('ENVIRONMENT', 'development');

require_once __DIR__ . '/../application/config/database.php';
$db_config = $db['default'];

$mysqli = new mysqli($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);
$res = $mysqli->query("SELECT id, data_hasil FROM test_results WHERE session_id = 12");
$row = $res->fetch_assoc();
echo "FULL DATA_HASIL FOR SESSION 12:\n";
$decoded = json_decode($row['data_hasil'], true);
print_r($decoded['parameter_values'] ?? $decoded);
$mysqli->close();
