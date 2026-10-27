<?php
$mysqli = new mysqli("localhost", "root", "", "limsku");
if ($mysqli->connect_error) die("DB Error: " . $mysqli->connect_error);

echo "=== SHOW COLUMNS OF SAMPLES ===\n";
$res = $mysqli->query("SHOW COLUMNS FROM samples");
while ($r = $res->fetch_assoc()) {
    echo "{$r['Field']} ({$r['Type']})\n";
}

echo "\n=== DISTINCT KATEGORI_SAMPEL ===\n";
$res2 = $mysqli->query("SELECT DISTINCT kategori_sampel, COUNT(*) as cnt FROM samples GROUP BY kategori_sampel");
while ($r2 = $res2->fetch_assoc()) {
    echo "'{$r2['kategori_sampel']}': {$r2['cnt']} sampel\n";
}

$mysqli->close();
