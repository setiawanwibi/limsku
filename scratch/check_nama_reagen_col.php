<?php
// scratch/check_nama_reagen_col.php
$mysqli = new mysqli('localhost', 'root', '', 'limsku');
if ($mysqli->connect_error) die('DB connect failed');

$res = $mysqli->query("SELECT id, kode_barang, nama_barang, nama_reagen, katalog, merk FROM reagents WHERE id IN (8, 9, 10, 11, 48, 50, 186, 202) ORDER BY id ASC");
while ($row = $res->fetch_assoc()) {
    echo sprintf("ID: %-3d | Kode: %-10s | nama_barang: %-30s | nama_reagen: %-30s\n", 
        $row['id'], $row['kode_barang'], substr($row['nama_barang'], 0, 30), substr($row['nama_reagen'], 0, 30)
    );
}
$mysqli->close();
