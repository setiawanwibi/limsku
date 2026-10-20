<?php
$conn = new mysqli('localhost', 'root', '', 'limsku');
echo "=== SESSIONS GROUPED BY SAMPLE ===" . PHP_EOL;
$res = $conn->query("
    SELECT ts.sample_id, s.nama_sampel, s.status as status_sampel, 
           GROUP_CONCAT(CONCAT(ts.id, ':', ts.jenis_pengujian, '(', ts.status, ')') SEPARATOR ', ') as sesi_list
    FROM testing_sessions ts
    JOIN samples s ON s.id = ts.sample_id
    GROUP BY ts.sample_id
    ORDER BY ts.sample_id DESC
");
while ($r = $res->fetch_assoc()) {
    echo "Sample {$r['sample_id']} [{$r['nama_sampel']}] (Status Sampel: {$r['status_sampel']}):" . PHP_EOL;
    echo "  Sesi: {$r['sesi_list']}" . PHP_EOL;
}
