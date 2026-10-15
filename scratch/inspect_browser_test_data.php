<?php
define('BASEPATH', 'defined');
define('ENVIRONMENT', 'development');

require_once __DIR__ . '/../application/config/database.php';
$db_config = $db['default'];

$mysqli = new mysqli($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);

echo "=== INSPECTING SAVED BROWSER TEST DATA ===\n";
$res = $mysqli->query("
    SELECT ts.id as session_id, ts.sample_id, ts.status as session_status, ts.jenis_pengujian,
           s.kode_sampel_manual, s.nama_sampel, s.status as sample_status,
           tr.id as test_result_id, tr.template_id, tr.kesimpulan, tr.catatan, tr.status as result_status,
           LENGTH(tr.data_hasil) as json_len, tr.data_hasil,
           t.kode_template, t.nama_template
    FROM testing_sessions ts
    JOIN samples s ON s.id = ts.sample_id
    JOIN test_results tr ON tr.session_id = ts.id
    LEFT JOIN form_templates t ON t.id = tr.template_id
    WHERE tr.catatan LIKE '%DATA UJI BROWSER%' OR tr.data_hasil LIKE '%DATA UJI%'
    ORDER BY ts.id DESC LIMIT 5
");

$test_sessions_to_clean = [];
while ($r = $res->fetch_assoc()) {
    $test_sessions_to_clean[] = $r['session_id'];
    echo "SESSION ID: {$r['session_id']}\n";
    echo "- Sample: {$r['kode_sampel_manual']} ({$r['nama_sampel']})\n";
    echo "- Session Status: {$r['session_status']}\n";
    echo "- Template: {$r['kode_template']} - {$r['nama_template']}\n";
    echo "- Kesimpulan: {$r['kesimpulan']}\n";
    echo "- Catatan: {$r['catatan']}\n";
    echo "- Result JSON Bytes: {$r['json_len']}\n";
    echo "- Stored JSON Content Preview: " . substr($r['data_hasil'], 0, 300) . "...\n\n";
}

echo "=== VERIFYING RE-READ VIA MODEL_PENGUJIAN QUERY ===\n";
foreach ($test_sessions_to_clean as $sid) {
    $q = $mysqli->query("
        SELECT tr.*, m.nama_metode, m.parameter_uji, t.nama_template, t.kode_template, t.skema_form
        FROM test_results tr
        LEFT JOIN form_templates t ON t.id = tr.template_id
        LEFT JOIN methods m ON m.id = tr.method_id
        WHERE tr.session_id = {$sid}
    ");
    while ($row = $q->fetch_assoc()) {
        $parsed = json_decode($row['data_hasil'], true);
        echo "Session #{$sid} Form ID: {$row['id']}\n";
        echo "- Template code: {$row['kode_template']}\n";
        echo "- Parameter: {$row['parameter_uji']}\n";
        echo "- Parsed parameter_values keys: " . implode(', ', array_keys($parsed['parameter_values'] ?? [])) . "\n";
        if (isset($parsed['hasil_ringkasan'])) echo "- Hasil ringkasan: {$parsed['hasil_ringkasan']}\n";
        if (isset($parsed['pustaka_acuan'])) echo "- Pustaka acuan: {$parsed['pustaka_acuan']}\n";
        if (isset($parsed['syarat_keberterimaan'])) echo "- Syarat: {$parsed['syarat_keberterimaan']}\n";
        if (isset($parsed['kesimpulan_akhir'])) echo "- Kesimpulan akhir: {$parsed['kesimpulan_akhir']}\n";
    }
}

$mysqli->close();
