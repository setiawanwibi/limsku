<?php
$mysqli = new mysqli('localhost', 'root', '', 'limsku');
if ($mysqli->connect_error) {
    die("Connect Error: " . $mysqli->connect_error);
}

echo "=== FORM TEMPLATES ===\n";
$res = $mysqli->query("SELECT id, kode_template, nama_template, kategori FROM form_templates");
while ($row = $res->fetch_assoc()) {
    echo "ID: {$row['id']} | Kode: {$row['kode_template']} | Nama: {$row['nama_template']} | Kategori: {$row['kategori']}\n";
}

echo "\n=== TESTING SESSIONS & TEST RESULTS (sample) ===\n";
$res2 = $mysqli->query("SELECT ts.id as session_id, ts.sample_id, ts.jenis_pengujian, ts.status, tr.id as test_result_id, tr.template_id, ft.kode_template, ft.nama_template 
    FROM testing_sessions ts 
    LEFT JOIN test_results tr ON ts.id = tr.session_id 
    LEFT JOIN form_templates ft ON tr.template_id = ft.id 
    ORDER BY ts.id DESC LIMIT 10");
while ($row = $res2->fetch_assoc()) {
    echo "Sesi ID: {$row['session_id']} | Sampel: {$row['sample_id']} | Status: {$row['status']} | TestID: {$row['test_result_id']} | Template: {$row['kode_template']} - {$row['nama_template']}\n";
}
