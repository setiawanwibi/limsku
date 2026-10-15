<?php
// scratch/execute_active_recovery.php
//
// SKRIP EKSEKUSI PEMULIHAN RESMI (FINAL APPLY) DATABASE AKTIF limsku
// Menggunakan runner aman scratch/execute_reagents_recovery_safe.php
// dengan izin eksplisit dari pengguna.

$backup_file = 'C:\\xampp\\backups\\LIMSKU\\limsku_backup_final_batch04_20261015_094500.sql';
$expected_sha256 = '9391f36059c3c88c31b2ff4e63f1bdc2f94d19aff55edbeeb36b1fb181b88545';

echo "=================================================================\n";
echo "PROSEDUR 1: VERIFIKASI BACKUP TERAKHIR SEBELUM PERUBAHAN\n";
echo "=================================================================\n";

if (!file_exists($backup_file)) {
    die("FATAL ERROR: File backup tidak ditemukan: $backup_file\n");
}

$sha256 = hash_file('sha256', $backup_file);
echo "Path       : $backup_file\n";
echo "Ukuran     : " . number_format(filesize($backup_file), 0, ',', '.') . " bytes\n";
echo "SHA-256    : $sha256\n";

if ($sha256 !== $expected_sha256) {
    die("FATAL ERROR: SHA-256 backup TIDAK COCOK dengan snapshot keselamatan!\n");
}
echo "[VERIFIKASI BACKUP LOLOS 100%]\n\n";

echo "=================================================================\n";
echo "PROSEDUR 2: VERIFIKASI SAFETY RUNNER & IZIN EKSPLISIT\n";
echo "=================================================================\n";

require_once __DIR__ . '/execute_reagents_recovery_safe.php';

$mysqli = new mysqli('localhost', 'root', '', 'limsku');
if ($mysqli->connect_error) {
    die("Koneksi ke database limsku gagal: " . $mysqli->connect_error . "\n");
}

// Inisialisasi runner dengan is_apply = true dan allow_active_db = true (karena sudah disetujui user)
$runner = new ReagentsRecoverySafeRunner($mysqli, 'limsku', true, true);

echo "Menjalankan pemulihan pada database aktif 'limsku'...\n";
try {
    $res = $runner->runRecovery();
    echo "\n>>> HASIL RUNNER: " . ($res ? "SUKSES" : "GAGAL") . " <<<\n";
} catch (Exception $e) {
    echo "\n>>> RUNNER GAGAL: " . $e->getMessage() . " <<<\n";
    $mysqli->close();
    exit(1);
}

echo "\n=================================================================\n";
echo "PROSEDUR 3: VERIFIKASI PASCA-EKSEKUSI PADA DATABASE AKTIF\n";
echo "=================================================================\n";

// 1. Total records
$res = $mysqli->query("SELECT COUNT(*) as c FROM reagents");
$final_total = (int)$res->fetch_assoc()['c'];
echo "1. Total Reagents: $final_total (Harus tetap 389) -> " . ($final_total === 389 ? "[PASS]" : "[FAIL]") . "\n";

// 2. Zero-date count
$res = $mysqli->query("SELECT COUNT(*) as c FROM reagents WHERE expired_date = '0000-00-00'");
$final_zero = (int)$res->fetch_assoc()['c'];
echo "2. Total Zero-Date ('0000-00-00'): $final_zero (Harus 0) -> " . ($final_zero === 0 ? "[PASS]" : "[FAIL]") . "\n";

// 3. Null-date count
$res = $mysqli->query("SELECT COUNT(*) as c FROM reagents WHERE expired_date IS NULL");
$final_null = (int)$res->fetch_assoc()['c'];
echo "3. Total NULL-Date: $final_null (Sebelumnya 66, bertambah 68 zero-date yang dinormalisasi = 134) -> " . ($final_null === 134 ? "[PASS]" : "[INFO: $final_null]") . "\n";

// 4. Valid-date count
$res = $mysqli->query("SELECT COUNT(*) as c FROM reagents WHERE expired_date IS NOT NULL AND expired_date != '0000-00-00'");
$final_valid = (int)$res->fetch_assoc()['c'];
echo "4. Total Valid-Date: $final_valid (Harus 255) -> " . ($final_valid === 255 ? "[PASS]" : "[FAIL]") . "\n";

// 5. Cek sampel record tergeser yang pulih (ID 8, 9, 10, 11)
$res = $mysqli->query("SELECT id, nama_barang, nama_reagen, stok, jenis_barang FROM reagents WHERE id IN (8, 9, 10, 11) ORDER BY id ASC");
echo "5. Verifikasi Sampel ID 8-11:\n";
while ($row = $res->fetch_assoc()) {
    echo "   -> ID {$row['id']}: nama_barang='{$row['nama_barang']}', nama_reagen='{$row['nama_reagen']}', stok='{$row['stok']}', jenis='{$row['jenis_barang']}'\n";
}

// 6. Cek ID 16 dan 18
$res = $mysqli->query("SELECT id, nama_barang, expired_date FROM reagents WHERE id IN (16, 18)");
echo "6. Verifikasi ID 16 dan 18:\n";
while ($row = $res->fetch_assoc()) {
    echo "   -> ID {$row['id']}: nama='{$row['nama_barang']}', expired_date=" . var_export($row['expired_date'], true) . "\n";
}

// 7. Cek tidak ada serial angka di jenis_barang
$res = $mysqli->query("SELECT COUNT(*) as c FROM reagents WHERE jenis_barang REGEXP '^[0-9]{5}$'");
$final_serial = (int)$res->fetch_assoc()['c'];
echo "7. Kolom jenis_barang bebas serial angka: " . ($final_serial === 0 ? "[PASS: 0 serial]" : "[FAIL: $final_serial serial]") . "\n";

// 8. Cek kolom stok bersih dari nama jenis
$res = $mysqli->query("SELECT COUNT(*) as c FROM reagents WHERE stok IN ('Reagen Cair', 'Reagen Padat', 'Media Mikrobiologi', 'Baku Pembanding')");
$final_stok = (int)$res->fetch_assoc()['c'];
echo "8. Kolom stok bebas nama jenis reagen: " . ($final_stok === 0 ? "[PASS: 0]" : "[FAIL: $final_stok]") . "\n";

$mysqli->close();
echo "\n[PROSEDUR SELESAI]\n";
