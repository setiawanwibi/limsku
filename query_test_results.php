<?php
$mysqli = new mysqli('localhost', 'root', '', 'limsku');
$res = $mysqli->query("SELECT * FROM test_results WHERE id IN (20, 23)");
while ($row = $res->fetch_assoc()) {
    echo "=== TEST RESULT ID: {$row['id']} ===\n";
    echo "Session ID: {$row['session_id']}\n";
    echo "Status: {$row['status']}\n";
    echo "Kesimpulan: {$row['kesimpulan']}\n";
    echo "Catatan: {$row['catatan']}\n";
    echo "File Laporan: {$row['file_laporan']}\n";
    echo "Data Hasil JSON:\n";
    echo $row['data_hasil'] . "\n\n";
}
