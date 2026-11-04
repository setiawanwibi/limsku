<?php
$conn = new mysqli('127.0.0.1', 'root', '', 'limsku');

echo "=== CHECK HOW SESSIONS APPEAR FOR PENGUJI (USER_ID 3) ===\n";
// Let's check user 3
$u = $conn->query("SELECT id, username, nama_lengkap, role_id FROM users WHERE id = 3")->fetch_assoc();
print_r($u);

echo "\n=== SESSIONS FOR USER 3 ===\n";
$res = $conn->query("SELECT ts.id, ts.sample_id, ts.jenis_pengujian, ts.status, ts.alasan_penolakan, s.kode_sampel_manual, s.nama_sampel FROM testing_sessions ts JOIN samples s ON s.id = ts.sample_id WHERE ts.penguji_id = 3 ORDER BY ts.id DESC");
while ($r = $res->fetch_assoc()) {
    print_r($r);
}
