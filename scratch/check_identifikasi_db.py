import sqlite3, os
# Wait, this is MySQL / MariaDB via XAMPP!
# Let's write a PHP script to query MySQL.
code = '''<?php
require_once __DIR__ . '/../application/config/database.php';
$db_cfg = $db['default'];
$conn = new mysqli($db_cfg['hostname'], $db_cfg['username'], $db_cfg['password'], $db_cfg['database']);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "=== PARAMETER DENGAN KATA IDENTIFIKASI ===\\n";
$res = $conn->query("SELECT id, nama_zat_aktif, parameter, pustaka, template_id FROM methods WHERE parameter LIKE '%identifikasi%' LIMIT 30");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        echo "ID: {$row['id']} | Zat: {$row['nama_zat_aktif']} | Param: {$row['parameter']} | Template ID: {$row['template_id']} | Pustaka: {$row['pustaka']}\\n";
    }
}

echo "\\n=== TEMPLATE YANG TERDAFTAR ===\\n";
$res2 = $conn->query("SELECT id, kode_template, nama_template, kategori FROM templates");
if ($res2) {
    while ($row = $res2->fetch_assoc()) {
        echo "Tpl ID: {$row['id']} | Kode: {$row['kode_template']} | Nama: {$row['nama_template']} | Kat: {$row['kategori']}\\n";
    }
}
'''
with open('scratch/check_identifikasi_db.php', 'w') as f:
    f.write(code)

