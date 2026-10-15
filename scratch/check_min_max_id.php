<?php
$mysqli = new mysqli('localhost', 'root', '', 'limsku');
$res = $mysqli->query('SELECT id FROM reagents WHERE id = 1');
echo 'ID 1 exists? ' . ($res->num_rows ? 'YES' : 'NO') . PHP_EOL;
$res = $mysqli->query('SELECT MIN(id), MAX(id) FROM reagents');
print_r($res->fetch_assoc());
