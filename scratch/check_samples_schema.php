<?php
define('ENVIRONMENT', 'development');
define('BASEPATH', 'dummy');
require_once __DIR__ . '/../application/config/database.php';

$pdo = new PDO('mysql:host=' . $db['default']['hostname'] . ';dbname=' . $db['default']['database'] . ';charset=utf8mb4', $db['default']['username'], $db['default']['password']);
$stmt = $pdo->query("DESCRIBE samples");
$cols = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "=== SAMPLES TABLE COLUMNS ===\n";
foreach ($cols as $c) {
    echo "  {$c['Field']} ({$c['Type']})\n";
}
