<?php
define('BASEPATH', 'TRUE');
define('ENVIRONMENT', 'development');
require_once 'application/config/database.php';
$db_config = $db['default'];
$mysqli = new mysqli($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);
if ($mysqli->connect_error) {
    die("Connect error: " . $mysqli->connect_error);
}

$res = $mysqli->query("
    SELECT 
        COUNT(*) as total_methods,
        SUM(CASE WHEN template_id IS NOT NULL AND template_id > 0 THEN 1 ELSE 0 END) as mapped_count,
        SUM(CASE WHEN template_id IS NULL OR template_id = 0 THEN 1 ELSE 0 END) as unmapped_count
    FROM methods
    WHERE status = 'Aktif'
");
$row = $res->fetch_assoc();
echo "TOTAL AKTIF: {$row['total_methods']}\n";
echo "MAPPED: {$row['mapped_count']}\n";
echo "UNMAPPED: {$row['unmapped_count']}\n";

echo "\nCONTOH MAPPED:\n";
$m_res = $mysqli->query("SELECT id, kode_metode, nama_zat_aktif, parameter_uji, template_id, bidang_pengujian FROM methods WHERE template_id IS NOT NULL AND status='Aktif' LIMIT 5");
while ($r = $m_res->fetch_assoc()) {
    echo "  [{$r['kode_metode']}] {$r['nama_zat_aktif']} - {$r['parameter_uji']} -> Template ID: {$r['template_id']} ({$r['bidang_pengujian']})\n";
}

echo "\nCONTOH UNMAPPED (Mapping Belum Lengkap):\n";
$u_res = $mysqli->query("SELECT id, kode_metode, nama_zat_aktif, parameter_uji, template_id, bidang_pengujian FROM methods WHERE (template_id IS NULL OR template_id=0) AND status='Aktif' LIMIT 5");
while ($r = $u_res->fetch_assoc()) {
    echo "  [{$r['kode_metode']}] {$r['nama_zat_aktif']} - {$r['parameter_uji']} -> Template ID: NULL ({$r['bidang_pengujian']})\n";
}

$mysqli->close();
