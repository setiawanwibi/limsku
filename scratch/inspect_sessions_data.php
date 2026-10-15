<?php
$mysqli = new mysqli('localhost', 'root', '', 'limsku');
$res = $mysqli->query('SELECT COUNT(*) as c FROM testing_sessions');
echo 'Total sessions: ' . $res->fetch_assoc()['c'] . PHP_EOL;
$res = $mysqli->query('SELECT id, sample_id, penguji_id, jenis_pengujian, status FROM testing_sessions');
while ($row = $res->fetch_assoc()) {
    echo sprintf('Session ID: %-2d | Sample ID: %-2d | Penguji: %-2d | Jenis: %-12s | Status: %s' . PHP_EOL, $row['id'], $row['sample_id'], $row['penguji_id'], $row['jenis_pengujian'], $row['status']);
}
$res = $mysqli->query('SELECT session_id, COUNT(*) as form_count FROM test_results GROUP BY session_id');
while ($row = $res->fetch_assoc()) {
    echo sprintf('Session ID: %-2s | Forms in test_results: %d' . PHP_EOL, $row['session_id'], $row['form_count']);
}
