<?php
define('BASEPATH', true);
define('ENVIRONMENT', 'development');
require_once __DIR__ . '/../application/config/database.php';
$db_cfg = $db['default'];
$conn = new mysqli($db_cfg['hostname'], $db_cfg['username'], $db_cfg['password'], $db_cfg['database']);

echo "=== TEST RESULTS WITH DATA_HASIL FOR IDENTIFIKASI ===\n";
$res = $conn->query("SELECT tr.id, tr.session_id, m.nama_zat_aktif, m.parameter_uji, tr.template_id, ft.kode_template, ft.nama_template, tr.data_hasil 
                      FROM test_results tr 
                      LEFT JOIN methods m ON tr.method_id = m.id
                      LEFT JOIN form_templates ft ON tr.template_id = ft.id 
                      WHERE m.parameter_uji LIKE '%identifikasi%' AND tr.data_hasil IS NOT NULL AND tr.data_hasil != '' AND tr.data_hasil != '[]' AND tr.data_hasil != '{}'
                      LIMIT 10");
if ($res) {
    while ($r = $res->fetch_assoc()) {
        echo "TR ID: {$r['id']} | Sesi: {$r['session_id']} | Zat: {$r['nama_zat_aktif']} | Param: {$r['parameter_uji']} | Tpl: [{$r['template_id']}] {$r['kode_template']}\n";
        echo "data_hasil: " . substr($r['data_hasil'], 0, 300) . "\n\n";
    }
}
