<?php
$mysqli = new mysqli("localhost", "root", "", "limsku");
if ($mysqli->connect_error) die("DB Error: " . $mysqli->connect_error);

echo "=== CEK DATA SAMPLES & KATEGORI ===\n";
$q = $mysqli->query("SELECT id, nama_sampel, kategori_sampel, created_at, status FROM samples ORDER BY id DESC LIMIT 10");
while ($r = $q->fetch_assoc()) {
    echo "ID: {$r['id']} | Nama: {$r['nama_sampel']} | Kategori: '{$r['kategori_sampel']}' | Status: {$r['status']} | Tgl: {$r['created_at']}\n";
}

echo "\n=== CEK ROLE PENYELIA DI DB ===\n";
$u = $mysqli->query("SELECT u.id, u.username, u.role_id, r.nama_role, u.komoditi_akses FROM users u JOIN roles r ON r.id = u.role_id WHERE r.nama_role LIKE '%Penyelia%' OR u.username = 'penyelia'");
while ($ru = $u->fetch_assoc()) {
    echo "User: {$ru['username']} | Role ID: {$ru['role_id']} | Role Name: {$ru['nama_role']} | Akses: {$ru['komoditi_akses']}\n";
}

$mysqli->close();
