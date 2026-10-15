<?php
define('BASEPATH', 'defined');
define('ENVIRONMENT', 'development');

require_once __DIR__ . '/../application/config/database.php';
$db_config = $db['default'];

$mysqli = new mysqli($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);
// Clean session 13
$mysqli->query("DELETE FROM test_results WHERE session_id = 13");
$mysqli->query("DELETE FROM testing_sessions WHERE id = 13");
$mysqli->query("UPDATE samples SET status = 'Menunggu Pengujian', penguji_id = NULL WHERE id = 14");
echo "Session 13 cleaned up, Sample 14 restored.\n";

$mysqli->close();
