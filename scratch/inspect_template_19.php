<?php
$mysqli = new mysqli('localhost', 'root', '', 'limsku');
$res = $mysqli->query('SELECT id, nama_template, kode_template, kategori FROM form_templates WHERE id = 19 OR kode_template LIKE "%MIK%"');
while ($row = $res->fetch_assoc()) {
    print_r($row);
}
