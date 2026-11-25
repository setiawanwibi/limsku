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

echo "1. Ensuring methods columns accommodate full OBASK/Suplemen text...\n";
$alters = [
    "ALTER TABLE methods MODIFY COLUMN nama_metode VARCHAR(255) NOT NULL",
    "ALTER TABLE methods MODIFY COLUMN nama_zat_aktif TEXT NULL",
    "ALTER TABLE methods MODIFY COLUMN jenis_sediaan VARCHAR(255) NULL",
    "ALTER TABLE methods MODIFY COLUMN bentuk_sediaan VARCHAR(255) NULL",
    "ALTER TABLE methods MODIFY COLUMN pustaka_acuan VARCHAR(255) NULL",
    "ALTER TABLE methods MODIFY COLUMN parameter_uji TEXT NULL",
    "ALTER TABLE methods MODIFY COLUMN metode_analisa VARCHAR(255) NULL"
];
foreach ($alters as $sql) {
    if (!$mysqli->query($sql)) {
        die("Alter failed: " . $mysqli->error . "\n");
    }
}
echo "   -> Columns updated successfully.\n";

echo "2. Deleting old herbal and suplemen rows from methods...\n";
$mysqli->query("SET FOREIGN_KEY_CHECKS=0");
$mysqli->query("DELETE FROM methods WHERE komoditi IN ('herbal', 'suplemen')");
$deleted = $mysqli->affected_rows;
$mysqli->query("SET FOREIGN_KEY_CHECKS=1");
echo "   -> Deleted $deleted old rows.\n";

$file = 'C:\\Users\\ASUS\\OneDrive\\Dokumen\\Magang BPOM\\Data BPOM\\Master Metode\\Obat Tradisional\\Master_Metode_OBASK_Siap_Import.xlsx';
echo "3. Parsing Excel: $file ...\n";
$xlsx = SimpleXLSX::parse($file);
if (!$xlsx) {
    die("Fail open Excel: " . SimpleXLSX::parseError() . "\n");
}

function make_hash($zat, $jenis, $bentuk, $acuan, $param, $metode, $syarat, $bidang) {
    $parts = [
        strtolower(trim(preg_replace('/\s+/', ' ', (string)$zat))),
        strtolower(trim(preg_replace('/\s+/', ' ', (string)$jenis))),
        strtolower(trim(preg_replace('/\s+/', ' ', (string)$bentuk))),
        strtolower(trim(preg_replace('/\s+/', ' ', (string)$acuan))),
        strtolower(trim(preg_replace('/\s+/', ' ', (string)$param))),
        strtolower(trim(preg_replace('/\s+/', ' ', (string)$metode))),
        strtolower(trim(preg_replace('/\s+/', ' ', (string)$syarat))),
        strtolower(trim(preg_replace('/\s+/', ' ', (string)$bidang))),
        0
    ];
    return md5(implode('|', $parts));
}

$stmt = $mysqli->prepare("INSERT INTO methods (
    kode_metode, nama_metode, nama_zat_aktif, jenis_sediaan, bentuk_sediaan,
    pustaka_acuan, parameter_uji, metode_analisa, persyaratan,
    bidang_pengujian, kategori, komoditi, composite_hash, status
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Aktif')");

$sheets = [
    0 => ['komoditi' => 'herbal',   'prefix' => 'MET-OT'],
    1 => ['komoditi' => 'suplemen', 'prefix' => 'MET-SUP']
];

foreach ($sheets as $sheetIdx => $info) {
    $rows = $xlsx->rows($sheetIdx);
    $cnt = 0;
    for ($i = 1; $i < count($rows); $i++) {
        $r = $rows[$i];
        if (empty(array_filter($r))) continue;
        $cnt++;
        $kode         = sprintf("%s-%04d", $info['prefix'], $cnt);
        $kom_raw      = strtolower(trim($r[0] ?? ''));
        $komoditi     = ($kom_raw === 'suplemen' || $kom_raw === 'herbal') ? $kom_raw : $info['komoditi'];
        $jenis        = trim($r[1] ?? '');
        $bentuk       = trim($r[2] ?? '');
        $param        = trim($r[3] ?? '');
        $zat          = trim($r[4] ?? '');
        $bidang_raw   = trim($r[5] ?? 'Kimia');
        $bidang       = (stripos($bidang_raw, 'mikro') !== false) ? 'Mikrobiologi' : 'Kimia';
        $metode       = trim($r[6] ?? '');
        $syarat       = trim($r[7] ?? '');
        $acuan        = trim($r[8] ?? '');
        $nama_metode  = mb_substr($param ?: $zat, 0, 250);
        $hash         = make_hash($zat, $jenis, $bentuk, $acuan, $param, $metode, $syarat, $bidang);

        $stmt->bind_param("sssssssssssss",
            $kode, $nama_metode, $zat, $jenis, $bentuk,
            $acuan, $param, $metode, $syarat,
            $bidang, $bidang, $komoditi, $hash
        );
        if (!$stmt->execute()) {
            die("Insert error at sheet $sheetIdx row $i: " . $stmt->error . "\n");
        }
    }
    echo "   -> Imported $cnt rows for komoditi '{$info['komoditi']}'.\n";
}

echo "ALL OBAT TRADISIONAL & SUPLEMEN DATA SEEDED SUCCESSFULLY!\n";
