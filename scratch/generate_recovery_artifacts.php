<?php
// scratch/generate_recovery_artifacts.php
// Menghasilkan skrip SQL koreksi dan rollback, serta JSON mapping rekonsiliasi

define('BASEPATH', 'dummy');
require_once __DIR__ . '/../application/libraries/SimpleXLSX.php';

$source_dir = 'C:\\Users\\ASUS\\OneDrive\\Dokumen\\Magang BPOM\\Data BPOM\\Data Reagen';
$files = [
    'Data Baku Pembanding.xlsx'   => 'Baku Pembanding',
    'Data Media Mikrobiologi.xlsx' => 'Media Mikrobiologi',
    'Data Reagen Cair.xlsx'        => 'Reagen Cair',
    'Data Reagen Padat.xlsx'       => 'Reagen Padat',
];

$excel_data = [];

function normalize_excel_date($val) {
    if ($val === null || $val === '') return null;
    if (is_numeric($val)) {
        $excel_base = new DateTime('1899-12-30');
        $days = (int)$val;
        $excel_base->modify("+{$days} days");
        return $excel_base->format('Y-m-d');
    }
    $trimmed = trim((string)$val);
    if ($trimmed === '' || $trimmed === '-' || $trimmed === '0000-00-00') return null;
    $ts = strtotime($trimmed);
    if ($ts !== false) return date('Y-m-d', $ts);
    return null;
}

foreach ($files as $filename => $default_jenis) {
    $full_path = $source_dir . '\\' . $filename;
    $xlsx = SimpleXLSX::parse($full_path);
    if (!$xlsx) {
        die("Gagal parse $filename\n");
    }
    $rows = $xlsx->rows();
    if (empty($rows)) continue;

    $header = $rows[0];
    for ($i = 1; $i < count($rows); $i++) {
        $row = $rows[$i];
        $is_empty = true;
        foreach ($row as $c) {
            if (trim((string)$c) !== '') {
                $is_empty = false;
                break;
            }
        }
        if ($is_empty) continue;

        $kode_barang  = trim((string)($row[1] ?? ''));
        $katalog      = trim((string)($row[2] ?? ''));
        $nama_barang  = trim((string)($row[3] ?? ''));
        $merk         = trim((string)($row[4] ?? ''));
        $stok         = trim((string)($row[5] ?? ''));
        $jenis_barang = trim((string)($row[6] ?? ''));
        $expired_raw  = $row[7] ?? null;

        if ($kode_barang === '' && $nama_barang === '') continue;

        $expired_date = normalize_excel_date($expired_raw);
        if ($jenis_barang === '') {
            $jenis_barang = $default_jenis;
        }

        $excel_data[$kode_barang] = [
            'file'         => $filename,
            'excel_row'    => $i + 1,
            'kode_barang'  => $kode_barang,
            'katalog'      => $katalog,
            'nama_barang'  => $nama_barang,
            'merk'         => $merk,
            'stok'         => $stok,
            'jenis_barang' => $jenis_barang,
            'expired_date' => $expired_date,
            'expired_raw'  => $expired_raw
        ];
    }
}

// Koneksi read-only ke database limsku
$mysqli = new mysqli('localhost', 'root', '', 'limsku');
if ($mysqli->connect_error) {
    die("Koneksi DB gagal: " . $mysqli->connect_error . "\n");
}

$res = $mysqli->query("SELECT id, kode_barang, nama_barang, katalog, merk, stok, jenis_barang, expired_date FROM reagents ORDER BY id ASC");
$db_records = [];
while ($row = $res->fetch_assoc()) {
    $db_records[] = $row;
}
$mysqli->close();

$shifted_records = [];
$zero_date_records = [];

foreach ($db_records as $rec) {
    $id = (int)$rec['id'];
    $kode = $rec['kode_barang'];
    $curr_exp = $rec['expired_date'];

    // Cek zero date
    if ($curr_exp === '0000-00-00') {
        $zero_date_records[] = $rec;
    }

    if (!isset($excel_data[$kode])) {
        continue;
    }

    $src = $excel_data[$kode];

    // Deteksi pergeseran: jika nama, merk, stok, atau jenis_barang tidak cocok secara substansial
    $is_shifted = false;
    if ($rec['nama_barang'] !== $src['nama_barang']) $is_shifted = true;
    if ($rec['katalog'] !== $src['katalog']) $is_shifted = true;
    if ($rec['merk'] !== $src['merk']) $is_shifted = true;
    if ($rec['stok'] !== $src['stok']) $is_shifted = true;
    if ($rec['jenis_barang'] !== $src['jenis_barang']) $is_shifted = true;

    if ($is_shifted) {
        $shifted_records[] = [
            'id'       => $id,
            'kode'     => $kode,
            'src_file' => $src['file'],
            'src_row'  => $src['excel_row'],
            'old'      => $rec,
            'new'      => $src
        ];
    }
}

echo "Total Record DB: " . count($db_records) . "\n";
echo "Total Shifted: " . count($shifted_records) . "\n";
echo "Total Zero-Date ('0000-00-00'): " . count($zero_date_records) . "\n";

// Simpan data rekonsiliasi ke scratch/reconciliation_data.json
file_put_contents(
    __DIR__ . '/reconciliation_data.json',
    json_encode([
        'shifted_records' => $shifted_records,
        'zero_date_records' => $zero_date_records
    ], JSON_PRETTY_PRINT)
);

echo "Data JSON berhasil disimpan ke scratch/reconciliation_data.json\n";
