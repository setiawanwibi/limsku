<?php
// final_verification.php
$mysqli = new mysqli('127.0.0.1', 'root', '', 'limsku');
if ($mysqli->connect_error) {
    die("[FATAL] Gagal koneksi: " . $mysqli->connect_error . "\n");
}

echo "=== VERIFIKASI AKHIR DATABASE LIMSKU ===\n";

$tables_check = [
    'form_templates'     => 0,
    'test_results'       => 0,
    'testing_sessions'   => 0,
    'samples'            => 24,
    'methods'            => 723,
    'instruments'        => 4,
    'reagents'           => 5,
    'users'              => 5,
    'roles'              => 5,
    'permissions'        => 28,
    'user_signatures'    => 5,
];

foreach ($tables_check as $tbl => $expected) {
    $cnt = $mysqli->query("SELECT COUNT(*) as c FROM `$tbl`")->fetch_assoc()['c'];
    $status = ($cnt == $expected) ? "OK" : "MISMATCH (Expected: $expected)";
    echo sprintf("%-20s : %-5d [%s]\n", $tbl, $cnt, $status);
}

// Check sample statuses
echo "\n=== SAMPLES STATUS DISTRIBUTION ===\n";
$res = $mysqli->query("SELECT status, COUNT(*) as c FROM samples GROUP BY status");
while ($r = $res->fetch_assoc()) {
    echo sprintf("Status: %-25s : %d sampel\n", $r['status'], $r['c']);
}

// Check methods mapping
$res_m = $mysqli->query("SELECT COUNT(*) as total, COUNT(template_id) as mapped FROM methods")->fetch_assoc();
echo "\n=== METHODS MAPPING STATUS ===\n";
echo "Total Methods: " . $res_m['total'] . " | Mapped: " . $res_m['mapped'] . "\n";

$mysqli->close();
