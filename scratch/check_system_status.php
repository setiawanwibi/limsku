<?php
define('BASEPATH', 'dummy');
define('ENVIRONMENT', 'development');
require_once 'application/config/database.php';
$pdo = new PDO('mysql:host=' . $db['default']['hostname'] . ';dbname=' . $db['default']['database'], $db['default']['username'], $db['default']['password']);

echo "=== USERS & KOMODITI AKSES ===\n";
$stmt = $pdo->query('SELECT id, username, nama_lengkap, role_id, komoditi_akses FROM users ORDER BY id');
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "ID: {$row['id']} | User: {$row['username']} | Role: {$row['role_id']} | Komoditi: {$row['komoditi_akses']}\n";
}

echo "\n=== SAMPLES COUNT BY KOMODITI ===\n";
$stmt = $pdo->query('SELECT komoditi, COUNT(*) as jml FROM samples GROUP BY komoditi');
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "Komoditi: {$row['komoditi']} -> {$row['jml']} sampel\n";
}

echo "\n=== TESTING SESSIONS BY KOMODITI ===\n";
$stmt = $pdo->query('SELECT s.komoditi, ts.status, COUNT(*) as jml 
                     FROM testing_sessions ts 
                     JOIN samples s ON ts.sample_id = s.id 
                     GROUP BY s.komoditi, ts.status');
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "Komoditi: {$row['komoditi']} | Status: {$row['status']} -> {$row['jml']} sesi\n";
}

echo "\n=== SAMPLES STATUS IN PANGAN ===\n";
$stmt = $pdo->query("SELECT status, COUNT(*) as jml FROM samples WHERE komoditi = 'pangan' GROUP BY status");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "Status: {$row['status']} -> {$row['jml']}\n";
}
