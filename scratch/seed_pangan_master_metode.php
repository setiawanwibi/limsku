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

echo "1. Creating table master_metode_pangan_olahan...\n";
$mysqli->query("DROP TABLE IF EXISTS master_metode_pangan_olahan");
$sql1 = "CREATE TABLE master_metode_pangan_olahan (
    id INT(11) NOT NULL AUTO_INCREMENT,
    kode_metode VARCHAR(50) NOT NULL,
    kategori_pangan VARCHAR(255) NULL,
    indeks_katpang VARCHAR(50) NULL,
    jenis_pangan TEXT NULL,
    presentase_jenis_pangan VARCHAR(50) NULL,
    bidang ENUM('Kimia', 'Mikrobiologi') NOT NULL DEFAULT 'Kimia',
    jenis_pu VARCHAR(50) NULL,
    jenis_lab VARCHAR(100) NULL,
    parameter_uji VARCHAR(255) NOT NULL,
    syarat_batas TEXT NULL,
    n VARCHAR(20) NULL,
    c VARCHAR(20) NULL,
    m_value VARCHAR(50) NULL,
    M_value VARCHAR(50) NULL,
    kriteria_3_kelas TEXT NULL,
    referensi_regulasi TEXT NULL,
    cara_uji VARCHAR(50) NULL,
    metode_analisa TEXT NULL,
    halaman_sk VARCHAR(50) NULL,
    template_id INT(11) NULL,
    status ENUM('Aktif', 'Nonaktif') NOT NULL DEFAULT 'Aktif',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_po_bidang (bidang),
    KEY idx_po_pu (jenis_pu),
    KEY idx_po_katpang (indeks_katpang),
    KEY idx_po_param (parameter_uji),
    KEY idx_po_template (template_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
if (!$mysqli->query($sql1)) {
    die("Error creating table master_metode_pangan_olahan: " . $mysqli->error);
}

echo "2. Creating table master_metode_pangan_pirt...\n";
$mysqli->query("DROP TABLE IF EXISTS master_metode_pangan_pirt");
$sql2 = "CREATE TABLE master_metode_pangan_pirt (
    id INT(11) NOT NULL AUTO_INCREMENT,
    kode_metode VARCHAR(50) NOT NULL,
    no_urut INT(11) NULL,
    kategori_pangan_pirt VARCHAR(255) NULL,
    kategori_pangan_nasional VARCHAR(50) NULL,
    jenis_pangan_pirt TEXT NULL,
    bidang ENUM('Kimia', 'Mikrobiologi') NOT NULL DEFAULT 'Kimia',
    pu VARCHAR(50) NULL,
    parameter_uji VARCHAR(255) NOT NULL,
    syarat_batas TEXT NULL,
    n VARCHAR(20) NULL,
    c VARCHAR(20) NULL,
    m_value VARCHAR(50) NULL,
    M_value VARCHAR(50) NULL,
    kriteria_3_kelas TEXT NULL,
    referensi_regulasi TEXT NULL,
    metode_analisa TEXT NULL,
    halaman_sk VARCHAR(50) NULL,
    template_id INT(11) NULL,
    status ENUM('Aktif', 'Nonaktif') NOT NULL DEFAULT 'Aktif',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_pirt_bidang (bidang),
    KEY idx_pirt_pu (pu),
    KEY idx_pirt_katnas (kategori_pangan_nasional),
    KEY idx_pirt_param (parameter_uji),
    KEY idx_pirt_template (template_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
if (!$mysqli->query($sql2)) {
    die("Error creating table master_metode_pangan_pirt: " . $mysqli->error);
}

echo "Tables created successfully.\n\n";

$dir = 'C:\\Users\\ASUS\\OneDrive\\Dokumen\\Magang BPOM\\Data BPOM\\Master Metode\\Pangan';
$f_olahan = $dir . '\\Master_Metode_Pangan_SK_Pedoman.xlsx';
$f_pirt = $dir . '\\Master_Metode_Pangan_PIRT_SK_Pedoman.xlsx';

// ========================================================
// 3. POPULATE master_metode_pangan_olahan
// ========================================================
echo "3. Importing Pangan Olahan from: $f_olahan ...\n";
$x_olahan = SimpleXLSX::parse($f_olahan);
if (!$x_olahan) {
    die("Fail open Pangan Olahan file: " . SimpleXLSX::parseError());
}

$stmt_olahan = $mysqli->prepare("INSERT INTO master_metode_pangan_olahan (
    kode_metode, kategori_pangan, indeks_katpang, jenis_pangan, presentase_jenis_pangan,
    bidang, jenis_pu, jenis_lab, parameter_uji, syarat_batas,
    n, c, m, M, kriteria_3_kelas, referensi_regulasi, cara_uji, metode_analisa, halaman_sk
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$po_count = 0;

// Sheet 0: Fisika-Kimia Pangan
$rows0 = $x_olahan->rows(0);
for ($i = 1; $i < count($rows0); $i++) {
    $r = $rows0[$i];
    if (empty(array_filter($r))) continue;
    $po_count++;
    $kode = sprintf("MET-PO-%04d", $po_count);
    $kat_pangan = trim($r[0] ?? '');
    $indeks = trim($r[1] ?? '');
    $jenis_p = trim($r[2] ?? '');
    $persen = trim($r[3] ?? '');
    $bidang = 'Kimia';
    $jenis_pu = trim($r[5] ?? '');
    $jenis_lab = trim($r[6] ?? '');
    $param = trim($r[7] ?? '');
    $syarat = trim($r[8] ?? '');
    $regulasi = trim($r[9] ?? '');
    $metode = trim($r[10] ?? '');
    $hal = trim($r[11] ?? '');

    $n = null; $c = null; $m = null; $M = null; $kriteria = null; $cara_uji = null;

    $stmt_olahan->bind_param("sssssssssssssssssss",
        $kode, $kat_pangan, $indeks, $jenis_p, $persen,
        $bidang, $jenis_pu, $jenis_lab, $param, $syarat,
        $n, $c, $m, $M, $kriteria, $regulasi, $cara_uji, $metode, $hal
    );
    $stmt_olahan->execute();
}
echo "  -> Imported " . ($po_count) . " Fisika-Kimia rows.\n";

// Sheet 1: Mikrobiologi Pangan
$rows1 = $x_olahan->rows(1);
$po_mikro_start = $po_count;
for ($i = 1; $i < count($rows1); $i++) {
    $r = $rows1[$i];
    if (empty(array_filter($r))) continue;
    $po_count++;
    $kode = sprintf("MET-PO-%04d", $po_count);
    $kat_pangan = trim($r[0] ?? '');
    $indeks = trim($r[1] ?? '');
    $jenis_p = trim($r[2] ?? '');
    $persen = trim($r[3] ?? '');
    $bidang = 'Mikrobiologi';
    $jenis_pu = trim($r[5] ?? '');
    $jenis_lab = trim($r[6] ?? '');
    $param = trim($r[7] ?? '');
    $n = trim($r[8] ?? '');
    $c = trim($r[9] ?? '');
    $m = trim($r[10] ?? '');
    $M = trim($r[11] ?? '');
    $kriteria = trim($r[12] ?? '');
    $regulasi = trim($r[13] ?? '');
    $cara_uji = trim($r[14] ?? '');
    $metode = trim($r[15] ?? '');
    $hal = trim($r[16] ?? '');
    $syarat = $kriteria; // Untuk mikro, kriteria 3-kelas berfungsi sebagai syarat batas

    $stmt_olahan->bind_param("sssssssssssssssssss",
        $kode, $kat_pangan, $indeks, $jenis_p, $persen,
        $bidang, $jenis_pu, $jenis_lab, $param, $syarat,
        $n, $c, $m, $M, $kriteria, $regulasi, $cara_uji, $metode, $hal
    );
    $stmt_olahan->execute();
}
echo "  -> Imported " . ($po_count - $po_mikro_start) . " Mikrobiologi rows.\n";
echo "Total Pangan Olahan inserted: $po_count\n\n";

// ========================================================
// 4. POPULATE master_metode_pangan_pirt
// ========================================================
echo "4. Importing PIRT from: $f_pirt ...\n";
$x_pirt = SimpleXLSX::parse($f_pirt);
if (!$x_pirt) {
    die("Fail open PIRT file: " . SimpleXLSX::parseError());
}

$stmt_pirt = $mysqli->prepare("INSERT INTO master_metode_pangan_pirt (
    kode_metode, no_urut, kategori_pangan_pirt, kategori_pangan_nasional, jenis_pangan_pirt,
    bidang, pu, parameter_uji, syarat_batas,
    n, c, m, M, kriteria_3_kelas, referensi_regulasi, metode_analisa, halaman_sk
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$pirt_count = 0;

// Sheet 0: PIRT Fisika-Kimia
$rows0_pirt = $x_pirt->rows(0);
for ($i = 1; $i < count($rows0_pirt); $i++) {
    $r = $rows0_pirt[$i];
    if (empty(array_filter($r))) continue;
    $pirt_count++;
    $kode = sprintf("MET-PIRT-%04d", $pirt_count);
    $no_urut = (int)($r[0] ?? 0);
    $kat_pirt = trim($r[1] ?? '');
    $kat_nas = trim($r[2] ?? '');
    $jenis_p = trim($r[3] ?? '');
    $bidang = 'Kimia';
    $pu = trim($r[5] ?? '');
    $param = trim($r[6] ?? '');
    $syarat = trim($r[7] ?? '');
    $regulasi = trim($r[8] ?? '');
    $metode = trim($r[9] ?? '');
    $hal = trim($r[10] ?? '');

    $n = null; $c = null; $m = null; $M = null; $kriteria = null;

    $stmt_pirt->bind_param("sisssssssssssssss",
        $kode, $no_urut, $kat_pirt, $kat_nas, $jenis_p,
        $bidang, $pu, $param, $syarat,
        $n, $c, $m, $M, $kriteria, $regulasi, $metode, $hal
    );
    $stmt_pirt->execute();
}
echo "  -> Imported " . ($pirt_count) . " PIRT Fisika-Kimia rows.\n";

// Sheet 1: PIRT Mikrobiologi
$rows1_pirt = $x_pirt->rows(1);
$pirt_mikro_start = $pirt_count;
for ($i = 1; $i < count($rows1_pirt); $i++) {
    $r = $rows1_pirt[$i];
    if (empty(array_filter($r))) continue;
    $pirt_count++;
    $kode = sprintf("MET-PIRT-%04d", $pirt_count);
    $no_urut = (int)($r[0] ?? 0);
    $kat_pirt = trim($r[1] ?? '');
    $kat_nas = trim($r[2] ?? '');
    $jenis_p = trim($r[3] ?? '');
    $bidang = 'Mikrobiologi';
    $pu = trim($r[5] ?? '');
    $param = trim($r[6] ?? '');
    $n = trim($r[7] ?? '');
    $c = trim($r[8] ?? '');
    $m = trim($r[9] ?? '');
    $M = trim($r[10] ?? '');
    $kriteria = trim($r[11] ?? '');
    $regulasi = trim($r[12] ?? '');
    $metode = trim($r[13] ?? '');
    $hal = trim($r[14] ?? '');
    $syarat = $kriteria;

    $stmt_pirt->bind_param("sisssssssssssssss",
        $kode, $no_urut, $kat_pirt, $kat_nas, $jenis_p,
        $bidang, $pu, $param, $syarat,
        $n, $c, $m, $M, $kriteria, $regulasi, $metode, $hal
    );
    $stmt_pirt->execute();
}
echo "  -> Imported " . ($pirt_count - $pirt_mikro_start) . " PIRT Mikrobiologi rows.\n";
echo "Total PIRT inserted: $pirt_count\n\n";

echo "ALL PANGAN TABLES CREATED AND SEEDED SUCCESSFULLY!\n";
