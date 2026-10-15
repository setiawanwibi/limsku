<?php
define('BASEPATH', 'TRUE');
define('ENVIRONMENT', 'development');
require_once __DIR__ . '/../application/config/database.php';

$db_cfg = $db['default'];
$mysqli = @new mysqli($db_cfg['hostname'], $db_cfg['username'], $db_cfg['password'], $db_cfg['database']);
if ($mysqli->connect_error) {
    die("DB error: " . $mysqli->connect_error);
}

// 1. Pastikan nama database
$curr_db = $mysqli->query("SELECT DATABASE()")->fetch_row()[0];
echo "Target DB: $curr_db\n";
if ($curr_db !== 'limsku') {
    die("[ABORT] Target DB bukan limsku!\n");
}

// 2. Baca file SQL
$sql_file = __DIR__ . '/../database/register_lcp_cemaran_mikroba_kualitatif.sql';
$sql = file_get_contents($sql_file);

// 3. Jalankan query
if ($mysqli->multi_query($sql)) {
    do {
        if ($res = $mysqli->store_result()) {
            $res->free();
        }
    } while ($mysqli->more_results() && $mysqli->next_result());
    echo "[SUCCESS] Script SQL berhasil dijalankan!\n";
} else {
    echo "[ERROR] Eksekusi SQL gagal: " . $mysqli->error . "\n";
}

// 4. Verifikasi record
$chk = $mysqli->query("SELECT id, kode_template, nama_template, kategori, status, LENGTH(skema_form) as skema_len FROM form_templates WHERE kode_template = 'MIK-04'");
if ($chk && $chk->num_rows === 1) {
    $row = $chk->fetch_assoc();
    echo "[VERIFIED] Tepat 1 record MIK-04 terdaftar:\n";
    print_r($row);
} else {
    echo "[FAILED] Verifikasi record MIK-04 gagal! Total ditemukan: " . ($chk ? $chk->num_rows : 0) . "\n";
}

$mysqli->close();
