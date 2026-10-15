<?php
define('BASEPATH', 'defined');
define('ENVIRONMENT', 'development');

require_once __DIR__ . '/../application/config/database.php';
$db_config = $db['default'];

$mysqli = new mysqli($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);
if ($mysqli->connect_error) {
    die("Connect Error: " . $mysqli->connect_error . "\n");
}

echo "=== 1. AUDIT DATABASE AKTIF: STRUKTUR & METRIK REAGENTS ===\n";
$cnt_total = $mysqli->query("SELECT COUNT(*) as c FROM reagents")->fetch_assoc()['c'];
$cnt_null = $mysqli->query("SELECT COUNT(*) as c FROM reagents WHERE expired_date IS NULL")->fetch_assoc()['c'];
$cnt_zero = $mysqli->query("SELECT COUNT(*) as c FROM reagents WHERE expired_date = '0000-00-00'")->fetch_assoc()['c'];
$cnt_valid = $mysqli->query("SELECT COUNT(*) as c FROM reagents WHERE expired_date IS NOT NULL AND expired_date != '0000-00-00'")->fetch_assoc()['c'];

echo "Total Record Reagents: {$cnt_total}\n";
echo "  - expired_date IS NULL: {$cnt_null}\n";
echo "  - expired_date = '0000-00-00': {$cnt_zero}\n";
echo "  - expired_date VALID DATE: {$cnt_valid}\n";

$status_res = $mysqli->query("SELECT status, COUNT(*) as c FROM reagents GROUP BY status");
echo "Status Breakdown:\n";
while ($sr = $status_res->fetch_assoc()) {
    echo "  - {$sr['status']}: {$sr['c']}\n";
}

$jenis_res = $mysqli->query("SELECT jenis_barang, COUNT(*) as c FROM reagents GROUP BY jenis_barang ORDER BY c DESC");
echo "Jenis Barang Breakdown:\n";
while ($jr = $jenis_res->fetch_assoc()) {
    echo "  - '" . ($jr['jenis_barang'] ?: 'EMPTY') . "': {$jr['c']}\n";
}

echo "\n=== 2. AUDIT LOGS TERKAIT REAGEN ===\n";
$col_check = $mysqli->query("SHOW COLUMNS FROM audit_logs");
$log_cols = [];
while ($c = $col_check->fetch_assoc()) $log_cols[] = $c['Field'];
echo "Kolom audit_logs: " . implode(', ', $log_cols) . "\n";

$action_col = in_array('aksi', $log_cols) ? 'aksi' : (in_array('action', $log_cols) ? 'action' : 'id');
$desc_col = in_array('deskripsi', $log_cols) ? 'deskripsi' : (in_array('details', $log_cols) ? 'details' : 'id');
$mod_col = in_array('modul', $log_cols) ? 'modul' : (in_array('module', $log_cols) ? 'module' : 'id');

$log_res = $mysqli->query("SELECT * FROM audit_logs WHERE {$mod_col} LIKE '%Reagen%' OR {$desc_col} LIKE '%Reagen%' OR {$desc_col} LIKE '%reagents%' ORDER BY id DESC LIMIT 10");
if ($log_res && $log_res->num_rows > 0) {
    while ($lr = $log_res->fetch_assoc()) {
        echo "Log #{$lr['id']} | Action: " . ($lr[$action_col] ?? '-') . " | Modul: " . ($lr[$mod_col] ?? '-') . " | Desc: " . substr($lr[$desc_col] ?? '', 0, 150) . "\n";
    }
} else {
    echo "Tidak ada log audit terkait Reagen.\n";
}

echo "\n=== 3. DETEKSI RECORD DENGAN INDIKASI PERGESERAN KOLOM ===\n";
// Kriteria anomali 1: nama_barang berformat stok (misal: "X VIAL", "X BOTOL", "X PAC", "X PCS", "X KTK")
// Kriteria anomali 2: nama_barang sangat pendek / hanya angka numerik / format katalog
$res_anomali = $mysqli->query("
    SELECT id, kode_barang, katalog, nama_barang, nama_reagen, merk, stok, jenis_barang, expired_date
    FROM reagents
    WHERE nama_barang REGEXP '^[0-9]+[ ]+(VIAL|BOTOL|PAC|PCS|KTK|AMPUL|TABLET|KAPSUL|TUBE|BOX|PACK|SACHET|CAN|STRIP)'
       OR nama_barang REGEXP '^[0-9]+$'
       OR katalog REGEXP '(Asam|Nutrient|Dissolution|Standar|Baku|Media|Reagen|Buffer|Larutan|Indikator|Natrium|Kalium)'
    ORDER BY id ASC
");

$anomali_list = [];
echo "Ditemukan {$res_anomali->num_rows} record yang memenuhi kriteria pola tergeser:\n";
while ($r = $res_anomali->fetch_assoc()) {
    $anomali_list[] = $r;
    echo "  ID {$r['id']}: [Kode: {$r['kode_barang']}] | Katalog: '{$r['katalog']}' | Nama: '{$r['nama_barang']}' | Merk: '{$r['merk']}' | Stok: '{$r['stok']}' | Exp: " . var_export($r['expired_date'], true) . "\n";
}

echo "\n=== 4. AUDIT SEMUA RECORD EXPIRED DATE '0000-00-00' ===\n";
$res_zero = $mysqli->query("SELECT id, kode_barang, katalog, nama_barang, merk, stok, jenis_barang, expired_date FROM reagents WHERE expired_date = '0000-00-00' ORDER BY id ASC");
echo "Total record dengan '0000-00-00': {$res_zero->num_rows}\n";
$sample_zero = [];
$i = 0;
while ($r = $res_zero->fetch_assoc()) {
    if ($i < 10) {
        $sample_zero[] = $r;
    }
    $i++;
}
echo "Sample 10 record expired_date '0000-00-00':\n";
foreach ($sample_zero as $sz) {
    echo "  ID {$sz['id']}: Nama: '{$sz['nama_barang']}' | Merk: '{$sz['merk']}' | Jenis: '{$sz['jenis_barang']}' | Stok: '{$sz['stok']}'\n";
}

$mysqli->close();
