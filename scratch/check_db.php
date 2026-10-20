<?php
$m = new mysqli('localhost', 'root', '', 'limsku');
if ($m->connect_error) {
    die("Connect failed: " . $m->connect_error . "\n");
}
$r = $m->query('SELECT id, kode_template, nama_template, kategori FROM form_templates');
while($row = $r->fetch_assoc()) {
    echo json_encode($row) . PHP_EOL;
}
