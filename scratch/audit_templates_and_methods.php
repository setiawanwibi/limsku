<?php
define('BASEPATH', 'TRUE');
define('ENVIRONMENT', 'development');
require_once 'application/config/database.php';

$db_config = $db['default'];
$mysqli = new mysqli($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);
if ($mysqli->connect_error) {
    die("Connect error: " . $mysqli->connect_error);
}

echo "=== FORM TEMPLATES (LCP) ===\n";
$tpls = $mysqli->query("SELECT id, kode_template, nama_template, kategori, status, skema_form FROM form_templates ORDER BY kategori, id");
while ($t = $tpls->fetch_assoc()) {
    echo "ID: {$t['id']} | [{$t['kode_template']}] {$t['nama_template']} | Kategori: {$t['kategori']} | Status: {$t['status']}\n";
    $skema = json_decode($t['skema_form'], true);
    if ($skema) {
        $keys = array_keys($skema);
        echo "   Fields: " . implode(', ', array_slice($keys, 0, 5)) . (count($keys) > 5 ? " (+" . (count($keys)-5) . " more)" : "") . "\n";
    }
}

echo "\n=== TOP 40 PARAMETER UJI IN METHODS ===\n";
$p_res = $mysqli->query("
    SELECT 
        bidang_pengujian, 
        parameter_uji, 
        metode_analisa, 
        COUNT(*) as total_records
    FROM methods
    WHERE status = 'Aktif'
    GROUP BY bidang_pengujian, parameter_uji, metode_analisa
    ORDER BY total_records DESC
    LIMIT 40
");
while ($p = $p_res->fetch_assoc()) {
    echo sprintf("[%s] %-30s | Analisa: %-25s | Total: %d\n", $p['bidang_pengujian'], substr($p['parameter_uji'], 0, 30), substr($p['metode_analisa'] ?: '-', 0, 25), $p['total_records']);
}

echo "\n=== DISTINCT PARAMETER_UJI TOTAL COUNT ===\n";
$cnt = $mysqli->query("SELECT COUNT(DISTINCT parameter_uji) as dist_p, COUNT(*) as total_m FROM methods WHERE status='Aktif'")->fetch_assoc();
echo "Total Records: {$cnt['total_m']} | Distinct Parameter Raw: {$cnt['dist_p']}\n";

$mysqli->close();
