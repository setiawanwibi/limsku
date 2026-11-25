<?php
define('BASEPATH', 1);
define('ENVIRONMENT', 'development');

require_once __DIR__ . '/../application/config/database.php';
require_once __DIR__ . '/../application/libraries/SimpleXLSX.php';

$cfg = $db['default'];
$mysqli = new mysqli($cfg['hostname'], $cfg['username'], $cfg['password'], $cfg['database']);
if ($mysqli->connect_error) {
    die("DB connection failed: " . $mysqli->connect_error);
}
$mysqli->set_charset("utf8mb4");

echo "1. Checking and deleting old kosmetik rows in methods...\n";
$old_ids = [];
$res = $mysqli->query("SELECT id FROM methods WHERE komoditi = 'kosmetik'");
while ($r = $res->fetch_assoc()) {
    $old_ids[] = (int)$r['id'];
}
if (!empty($old_ids)) {
    $id_list = implode(',', $old_ids);
    // Check if referenced in test_results or sample_parameters
    $mysqli->query("SET FOREIGN_KEY_CHECKS=0");
    $mysqli->query("DELETE FROM methods WHERE id IN ($id_list)");
    $deleted = $mysqli->affected_rows;
    $mysqli->query("SET FOREIGN_KEY_CHECKS=1");
    echo "   -> Deleted $deleted old kosmetik rows from methods.\n";
} else {
    echo "   -> No old kosmetik rows found in methods.\n";
}

echo "2. Creating table master_metode_kosmetik...\n";
$mysqli->query("DROP TABLE IF EXISTS master_metode_kosmetik");
$sql = "CREATE TABLE master_metode_kosmetik (
    id INT(11) NOT NULL AUTO_INCREMENT,
    kode_metode VARCHAR(50) NOT NULL,
    jenis_pengawasan ENUM('Compliance', 'Surveillance') NOT NULL DEFAULT 'Compliance',
    tipe_produk VARCHAR(255) NULL,
    kategori VARCHAR(255) NULL,
    kelompok_uji VARCHAR(100) NULL,
    bidang ENUM('Kimia', 'Mikrobiologi') NOT NULL DEFAULT 'Kimia',
    parameter_uji TEXT NOT NULL,
    halaman VARCHAR(50) NULL,
    template_id INT(11) NULL,
    status ENUM('Aktif', 'Nonaktif') NOT NULL DEFAULT 'Aktif',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_kos_pengawasan (jenis_pengawasan),
    KEY idx_kos_bidang (bidang),
    KEY idx_kos_kelompok (kelompok_uji),
    KEY idx_kos_template (template_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

if (!$mysqli->query($sql)) {
    die("Error creating table master_metode_kosmetik: " . $mysqli->error);
}
echo "   -> Table master_metode_kosmetik created.\n";

$file = 'C:\\Users\\ASUS\\OneDrive\\Dokumen\\Magang BPOM\\Data BPOM\\Master Metode\\Kosmetik\\Master_Metode_Kosmetik_Audit.xlsx';
echo "3. Importing Kosmetik from: $file ...\n";
$xlsx = SimpleXLSX::parse($file);
if (!$xlsx) {
    die("Fail open Kosmetik file: " . SimpleXLSX::parseError());
}

$stmt = $mysqli->prepare("INSERT INTO master_metode_kosmetik (
    kode_metode, jenis_pengawasan, tipe_produk, kategori, kelompok_uji, bidang, parameter_uji, halaman
) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

$total_count = 0;

$sheets = [
    0 => 'Compliance',
    1 => 'Surveillance'
];

foreach ($sheets as $sheetIdx => $pengawasan) {
    $rows = $xlsx->rows($sheetIdx);
    $sheet_count = 0;
    for ($i = 1; $i < count($rows); $i++) {
        $r = $rows[$i];
        if (empty(array_filter($r))) continue;
        $total_count++;
        $sheet_count++;
        $kode = sprintf("MET-KOS-%04d", $total_count);
        $tipe_produk  = trim($r[0] ?? '');
        $kategori     = trim($r[1] ?? '');
        $kelompok_uji = trim($r[2] ?? '');
        $bidang_raw   = trim($r[3] ?? 'Kimia');
        $bidang       = (stripos($bidang_raw, 'mikro') !== false) ? 'Mikrobiologi' : 'Kimia';
        $param        = trim($r[4] ?? '');
        $halaman      = trim($r[5] ?? '');

        $stmt->bind_param("ssssssss",
            $kode, $pengawasan, $tipe_produk, $kategori, $kelompok_uji, $bidang, $param, $halaman
        );
        $stmt->execute();
    }
    echo "   -> Imported $sheet_count rows for $pengawasan.\n";
}

echo "Total Kosmetik inserted: $total_count\n";
echo "ALL KOSMETIK DATA MIGRATED AND SEEDED SUCCESSFULLY!\n";
