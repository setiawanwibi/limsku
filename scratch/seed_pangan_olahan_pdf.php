<?php
define('BASEPATH', 1);
define('ENVIRONMENT', 'development');

require_once __DIR__ . '/../application/config/database.php';

$jsonPath = __DIR__ . '/pangan_olahan_parsed.json';
$data = json_decode(file_get_contents($jsonPath), true);
if (!$data || empty($data['kimia']) || empty($data['mikrobiologi'])) {
    die("Invalid or empty parsed JSON.\n");
}

$cfg = $db['default'];
$mysqli = new mysqli($cfg['hostname'], $cfg['username'], $cfg['password'], $cfg['database']);
if ($mysqli->connect_error) {
    die("DB connection failed: " . $mysqli->connect_error);
}
$mysqli->set_charset("utf8mb4");

echo "1. Truncating master_metode_pangan_olahan...\n";
$mysqli->query("TRUNCATE TABLE master_metode_pangan_olahan");

$stmt = $mysqli->prepare("INSERT INTO master_metode_pangan_olahan (
    kode_metode, kategori_pangan, indeks_katpang, jenis_pangan, presentase_jenis_pangan,
    bidang, jenis_pu, jenis_lab, parameter_uji, syarat_batas,
    n, c, kriteria_m, kriteria_M_besar, kriteria_3_kelas, referensi_regulasi, cara_uji, metode_analisa, halaman_sk
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$count = 0;
$kimia_count = 0;
$mikro_count = 0;

foreach (array_merge($data['kimia'], $data['mikrobiologi']) as $row) {
    $count++;
    $kode     = sprintf("MET-PO-%04d", $count);
    $kat      = $row['kategori_pangan'] ?? '';
    $idx      = $row['indeks_katpang'] ?? '';
    $jns      = $row['jenis_pangan'] ?? '';
    $pct      = $row['presentase_jenis_pangan'] ?? '';
    $bidang   = $row['bidang'] ?? 'Kimia';
    $pu       = $row['jenis_pu'] ?? '';
    $lab      = $row['jenis_lab'] ?? '';
    $param    = $row['parameter_uji'] ?? '';
    $syarat   = $row['syarat_batas'] ?? '';
    $n        = $row['n'] ?? null;
    $c        = $row['c'] ?? null;
    $m_val    = $row['kriteria_m'] ?? null;
    $M_val    = $row['kriteria_M_besar'] ?? null;
    $k3       = $row['kriteria_3_kelas'] ?? null;
    $reg      = $row['referensi_regulasi'] ?? '';
    $cara     = $row['cara_uji'] ?? null;
    $metode   = $row['metode_analisa'] ?? '';
    $hal      = isset($row['page']) ? ('Hal. ' . $row['page']) : '';

    $stmt->bind_param("sssssssssssssssssss",
        $kode, $kat, $idx, $jns, $pct,
        $bidang, $pu, $lab, $param, $syarat,
        $n, $c, $m_val, $M_val, $k3, $reg, $cara, $metode, $hal
    );
    if (!$stmt->execute()) {
        die("Insert error at row $count: " . $stmt->error . "\n");
    }

    if ($bidang === 'Kimia') $kimia_count++;
    else $mikro_count++;
}

echo "Inserted total: $count rows (Kimia: $kimia_count, Mikrobiologi: $mikro_count)\n";

$verify = $mysqli->query("SELECT COUNT(*) as total, SUM(bidang='Kimia') as k, SUM(bidang='Mikrobiologi') as m FROM master_metode_pangan_olahan")->fetch_assoc();
echo "DB Verify: Total={$verify['total']}, Kimia={$verify['k']}, Mikrobiologi={$verify['m']}\n";
