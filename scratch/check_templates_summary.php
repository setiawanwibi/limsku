<?php
define('BASEPATH', 'dummy');
define('ENVIRONMENT', 'development');
require_once 'application/config/database.php';
$pdo = new PDO('mysql:host=' . $db['default']['hostname'] . ';dbname=' . $db['default']['database'], $db['default']['username'], $db['default']['password']);

echo "=== FORM TEMPLATES LIST ===\n";
$stmt = $pdo->query('SELECT id, kode_template, nama_template, kategori, komoditi FROM form_templates');
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "ID: {$row['id']} | Kode: {$row['kode_template']} | Nama: {$row['nama_template']} | Kat: {$row['kategori']} | Kom: {$row['komoditi']}\n";
}

echo "\n=== METHODS LIST FOR PANGAN ===\n";
$stmt = $pdo->query("SELECT id, kode_metode, nama_metode, komoditi FROM methods WHERE komoditi = 'pangan'");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "ID: {$row['id']} | Kode: {$row['kode_metode']} | Nama: {$row['nama_metode']}\n";
}
