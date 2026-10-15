<?php
$mysqli = new mysqli('localhost', 'root', '', 'limsku');
$res = $mysqli->query('SELECT s.*, u.nama_lengkap FROM user_signatures s JOIN users u ON u.id = s.user_id');
while ($row = $res->fetch_assoc()) {
    print_r($row);
}
