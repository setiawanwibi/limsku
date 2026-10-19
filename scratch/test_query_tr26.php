<?php
define('BASEPATH', 'defined');
define('ENVIRONMENT', 'development');

require_once __DIR__ . '/../application/config/database.php';
$db_config = $db['default'];

$mysqli = new mysqli($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);
if ($mysqli->connect_error) {
    die("DB Connect Error: " . $mysqli->connect_error . "\n");
}

$query = "SELECT tr.*, m.nama_metode, m.kode_metode, m.nama_zat_aktif, m.parameter_uji, m.jenis_sediaan, m.bentuk_sediaan, m.pustaka_acuan, m.metode_analisa, m.persyaratan, tc.nama_kelas_terapi, t.nama_template, t.kode_template, t.skema_form, t.kategori as kategori_template, p.nama_parameter, p.kode_parameter, u.nama_lengkap as nama_penguji, u.nip as nip_penguji, uv.nama_lengkap as nama_verifier, ua.nama_lengkap as nama_approver, s.nama_sampel, s.kode_sampel_manual, s.no as no_sampel, s.status as status_sampel, s.kategori_sampel 
    FROM test_results tr 
    INNER JOIN samples s ON s.id = tr.sample_id 
    LEFT JOIN methods m ON m.id = tr.method_id 
    LEFT JOIN therapeutic_classes tc ON tc.id = m.kelas_terapi_id 
    LEFT JOIN form_templates t ON t.id = tr.template_id 
    LEFT JOIN parameters p ON p.id = t.parameter_id 
    LEFT JOIN users u ON u.id = tr.penguji_id 
    LEFT JOIN users uv ON uv.id = tr.verifier_id 
    LEFT JOIN users ua ON ua.id = tr.approver_id 
    WHERE tr.id = 26";

$res = $mysqli->query($query);
if (!$res) {
    echo "[FAIL] Query error: " . $mysqli->error . "\n";
} else {
    $row = $res->fetch_assoc();
    echo "[PASS] Query berhasil dijalankan untuk tr.id = 26!\n";
    echo "Sample: " . $row['nama_sampel'] . " (" . $row['kode_sampel_manual'] . ")\n";
    echo "Template: " . $row['nama_template'] . " (" . $row['kode_template'] . ")\n";
    echo "Penguji: " . $row['nama_penguji'] . "\n";
    echo "Verifier: " . $row['nama_verifier'] . "\n";
}

$mysqli->close();
