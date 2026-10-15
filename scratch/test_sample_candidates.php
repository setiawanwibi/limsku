<?php
$mysqli = new mysqli('127.0.0.1', 'root', '', 'limsku');
if ($mysqli->connect_error) {
    die("Connect Error: " . $mysqli->connect_error);
}

// 1. Cek sample yang tersedia untuk pengujian
$res = $mysqli->query("SELECT id, kode_sampel_manual, nama_sampel, status FROM samples WHERE status = 'Menunggu Pengujian' LIMIT 5");
echo "=== SAMPLES WITH 'Menunggu Pengujian' ===\n";
while ($row = $res->fetch_assoc()) {
    echo "Sample ID: {$row['id']} | Kode: {$row['kode_sampel_manual']} | Nama: {$row['nama_sampel']} | Status: {$row['status']}\n";
}

// 2. Cek sample yang sedang dalam pengujian penguji ID 3
$res2 = $mysqli->query("SELECT ts.id as session_id, ts.sample_id, ts.status as session_status, s.nama_sampel, s.status as sample_status 
                        FROM testing_sessions ts 
                        JOIN samples s ON s.id = ts.sample_id 
                        WHERE ts.penguji_id = 3 ORDER BY ts.id DESC LIMIT 5");
echo "\n=== SESSIONS FOR PENGUJI (ID 3) ===\n";
while ($row = $res2->fetch_assoc()) {
    echo "Session ID: {$row['session_id']} | Sample ID: {$row['sample_id']} | Status Sesi: {$row['session_status']} | Sampel: {$row['nama_sampel']} ({$row['sample_status']})\n";
}

// 3. Cek detail test_results untuk session ID 11
$res3 = $mysqli->query("SELECT tr.id, tr.session_id, tr.template_id, tr.method_id, tr.status, tr.kesimpulan, tr.data_hasil,
                               m.nama_zat_aktif, m.parameter_uji, m.pustaka_acuan, m.persyaratan
                        FROM test_results tr
                        LEFT JOIN methods m ON m.id = tr.method_id
                        WHERE tr.session_id = 11");
echo "\n=== TEST RESULTS FOR SESSION 11 ===\n";
while ($row = $res3->fetch_assoc()) {
    echo "Result ID: {$row['id']} | Session: {$row['session_id']} | Param: {$row['parameter_uji']} | Pustaka: {$row['pustaka_acuan']} | Syarat: {$row['persyaratan']}\n";
    echo "Data Hasil: " . $row['data_hasil'] . "\n";
}
