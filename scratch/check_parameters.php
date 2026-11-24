<?php
define('BASEPATH', 'dummy');
define('ENVIRONMENT', 'development');
require_once 'application/config/database.php';
$pdo = new PDO('mysql:host=' . $db['default']['hostname'] . ';dbname=' . $db['default']['database'], $db['default']['username'], $db['default']['password']);

echo "=== PARAMETERS COLUMNS ===\n";
$stmt = $pdo->query('DESCRIBE parameters');
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo " - {$row['Field']} ({$row['Type']})\n";
}

echo "\n=== PARAMETERS COUNT BY KATEGORI ===\n";
$stmt = $pdo->query('SELECT kategori, COUNT(*) as jml FROM parameters GROUP BY kategori');
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "Kategori: {$row['kategori']} -> {$row['jml']} parameter\n";
}

echo "\n=== METHODS COLUMNS ===\n";
$stmt = $pdo->query('DESCRIBE methods');
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo " - {$row['Field']} ({$row['Type']})\n";
}

echo "\n=== FORM TEMPLATES COLUMNS ===\n";
$stmt = $pdo->query('DESCRIBE form_templates');
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo " - {$row['Field']} ({$row['Type']})\n";
}

echo "\n=== FORM TEMPLATES LIST ===\n";
$stmt = $pdo->query('SELECT * FROM form_templates');
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo " - ID: {$row['id']} | " . json_encode($row, JSON_UNESCAPED_UNICODE) . "\n";
}
