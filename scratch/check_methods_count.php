<?php
$conn = new mysqli('127.0.0.1', 'root', '', 'limsku');
if ($conn->connect_error) {
    die("Connect Error: " . $conn->connect_error . "\n");
}

$res = $conn->query("SELECT COUNT(*) as cnt FROM methods");
$row = $res->fetch_assoc();
echo "Actual methods count: " . $row['cnt'] . "\n";

$res = $conn->query("SELECT id, nama_zat_aktif, parameter_uji, metode_analisa, template_id FROM methods ORDER BY id DESC LIMIT 5");
echo "Recent methods:\n";
while ($r = $res->fetch_assoc()) {
    echo sprintf("ID: %-4d | %-25s | %-25s | Tpl: %s\n", $r['id'], $r['nama_zat_aktif'], $r['parameter_uji'], $r['template_id'] ?? 'NULL');
}

$conn->close();
