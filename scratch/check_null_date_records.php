<?php
// scratch/check_null_date_records.php
$mysqli = new mysqli('localhost', 'root', '', 'limsku');
$res = $mysqli->query('SELECT id, kode_barang, nama_barang, katalog, merk, jenis_barang FROM reagents WHERE expired_date IS NULL ORDER BY id ASC LIMIT 10');
while ($row = $res->fetch_assoc()) {
    echo sprintf("ID: %-3d | Kode: %-10s | Jenis: %-15s | Nama: %-30s\n", 
        $row['id'], $row['kode_barang'], $row['jenis_barang'], substr($row['nama_barang'], 0, 30)
    );
}
