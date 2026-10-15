<?php
$mysqli = new mysqli('localhost', 'root', '', 'limsku');
$res = $mysqli->query('SELECT id, kode_barang, nama_barang, katalog, merk, stok, jenis_barang, expired_date FROM reagents WHERE id IN (16, 18)');
while ($row = $res->fetch_assoc()) {
    print_r($row);
}
