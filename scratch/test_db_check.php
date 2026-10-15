<?php
$mysqli = new mysqli('127.0.0.1', 'root', '', 'limsku');
if ($mysqli->connect_error) {
    die("Connect Error: " . $mysqli->connect_error);
}

echo "=== USER LIST (Role 2 / Penguji & Role 3 / Penyelia) ===\n";
$res = $mysqli->query("SELECT id, username, nama_lengkap, role_id, status FROM users WHERE role_id IN (2, 3, 4)");
while ($row = $res->fetch_assoc()) {
    echo "ID: {$row['id']} | Username: {$row['username']} | Role: {$row['role_id']} | Nama: {$row['nama_lengkap']} | Status: {$row['status']}\n";
}

echo "\n=== RECENT TESTING SESSIONS ===\n";
$res2 = $mysqli->query("SELECT ts.id, ts.sample_id, ts.penguji_id, ts.jenis_pengujian, ts.status, s.nama_sampel 
                        FROM testing_sessions ts 
                        JOIN samples s ON s.id = ts.sample_id 
                        ORDER BY ts.id DESC LIMIT 5");
while ($row = $res2->fetch_assoc()) {
    echo "Session ID: {$row['id']} | Penguji ID: {$row['penguji_id']} | Jenis: {$row['jenis_pengujian']} | Status: {$row['status']} | Sampel: {$row['nama_sampel']}\n";
}

echo "\n=== MASTER ALAT (SAMPLE) ===\n";
$res3 = $mysqli->query("SELECT id, kode_barang, nama_barang, merk_tipe, kondisi_adm FROM instruments WHERE status = 'Aktif' LIMIT 3");
while ($row = $res3->fetch_assoc()) {
    echo "Alat ID: {$row['id']} | Kode: {$row['kode_barang']} | Nama: {$row['nama_barang']} | Merk: {$row['merk_tipe']}\n";
}

echo "\n=== MASTER REAGEN (SAMPLE) ===\n";
$res4 = $mysqli->query("SELECT id, kode_barang, katalog, COALESCE(nama_barang, nama_reagen) as nama, merk, stok, expired_date FROM reagents WHERE status = 'Aktif' LIMIT 4");
while ($row = $res4->fetch_assoc()) {
    echo "Reagen ID: {$row['id']} | Kode: {$row['kode_barang']} | Kat: {$row['katalog']} | Nama: {$row['nama']} | Stok: {$row['stok']} | Exp: {$row['expired_date']}\n";
}
