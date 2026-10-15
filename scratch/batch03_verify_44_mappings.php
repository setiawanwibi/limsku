<?php
// scratch/batch03_verify_44_mappings.php
define('BASEPATH', 'dummy');
require_once __DIR__ . '/../application/libraries/SimpleXLSX.php';

$source_dir = 'C:\\Users\\ASUS\\OneDrive\\Dokumen\\Magang BPOM\\Data BPOM\\Data Reagen';
$files = [
    'Data Baku Pembanding.xlsx'   => 'Baku Pembanding',
    'Data Media Mikrobiologi.xlsx' => 'Media Mikrobiologi',
    'Data Reagen Cair.xlsx'        => 'Reagen Cair',
    'Data Reagen Padat.xlsx'       => 'Reagen Padat',
];

$excel_by_code = [];
$code_occurrences = [];

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
    if (!$xlsx) die("Gagal parse $filename\n");
    $rows = $xlsx->rows();
    for ($i = 1; $i < count($rows); $i++) {
        $row = $rows[$i];
        $is_empty = true;
        foreach ($row as $c) {
            if (trim((string)$c) !== '') { $is_empty = false; break; }
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

        $entry = [
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

        if (!isset($code_occurrences[$kode_barang])) {
            $code_occurrences[$kode_barang] = [];
        }
        $code_occurrences[$kode_barang][] = $entry;
        $excel_by_code[$kode_barang] = $entry;
    }
}

// Cek duplikasi kode barang di sumber Excel
echo "=== DUPLIKASI KODE BARANG DI SUMBER EXCEL ===\n";
$dup_found = false;
foreach ($code_occurrences as $code => $list) {
    if (count($list) > 1) {
        $dup_found = true;
        echo "Duplikat Kode Barang: $code (" . count($list) . " kali muncul)\n";
        foreach ($list as $item) {
            echo "   -> File: {$item['file']} Row: {$item['excel_row']} Nama: {$item['nama_barang']}\n";
        }
    }
}
if (!$dup_found) echo "TIDAK ADA duplikasi kode barang di seluruh 4 file Excel sumber (Semua unik).\n";

// Muat reconciliation_data.json
$json_path = __DIR__ . '/reconciliation_data.json';
$rec_json = json_decode(file_get_contents($json_path), true);
$shifted_in_json = $rec_json['shifted_records'];

// Koneksi ke DB limsku
$mysqli = new mysqli('localhost', 'root', '', 'limsku');
if ($mysqli->connect_error) die('DB connect failed');

// Periksa setiap satu dari 44 record
echo "\n=== AUDIT RINCI 44 RECORD TERGESER ===\n";
$valid_count = 0;
$mismatch_count = 0;
$ambiguous_count = 0;
$manual_count = 0;

// Baca recovery_reagents_historical.sql
$recovery_sql = file_get_contents(__DIR__ . '/../database/recovery_reagents_historical.sql');
$rollback_sql = file_get_contents(__DIR__ . '/../database/rollback_reagents_historical.sql');

foreach ($shifted_in_json as $idx => $s) {
    $id = (int)$s['id'];
    if ($id == 323) continue; // ID 323 dummy row

    $kode = $s['kode'];
    
    // 1. Cek di DB aktif saat ini
    $res = $mysqli->query("SELECT * FROM reagents WHERE id = $id");
    $db_row = $res->fetch_assoc();
    if (!$db_row) {
        echo "FAIL: ID $id tidak ditemukan di DB!\n";
        $mismatch_count++;
        continue;
    }

    if ($db_row['kode_barang'] !== $kode) {
        echo "FAIL: ID $id kode barang DB ({$db_row['kode_barang']}) != JSON ($kode)\n";
        $mismatch_count++;
        continue;
    }

    // 2. Cek keunikan di Excel
    if (!isset($code_occurrences[$kode]) || count($code_occurrences[$kode]) !== 1) {
        echo "AMBIGUOUS: Kode $kode tidak unik di Excel!\n";
        $ambiguous_count++;
        continue;
    }

    $src = $code_occurrences[$kode][0];

    // 3. Verifikasi apakah SQL recovery memuat UPDATE yang cocok persis
    // Cari statement UPDATE untuk ID ini di recovery_reagents_historical.sql
    $pattern_rec = "/-- \[(\d+)\] ID: $id \($kode\).*?UPDATE reagents\s+SET\s+(.*?)\s+WHERE id = $id AND kode_barang = '$kode';/s";
    if (!preg_match($pattern_rec, $recovery_sql, $matches_rec)) {
        echo "FAIL SQL RECOVERY: Tidak ditemukan UPDATE untuk ID $id di recovery SQL!\n";
        $mismatch_count++;
        continue;
    }

    // Cari statement UPDATE untuk ID ini di rollback_reagents_historical.sql
    $pattern_roll = "/-- \[(\d+)\] ID: $id \($kode\).*?UPDATE reagents\s+SET\s+(.*?)\s+WHERE id = $id AND kode_barang = '$kode';/s";
    if (!preg_match($pattern_roll, $rollback_sql, $matches_roll)) {
        echo "FAIL SQL ROLLBACK: Tidak ditemukan UPDATE untuk ID $id di rollback SQL!\n";
        $mismatch_count++;
        continue;
    }

    // 4. Verifikasi nilai di recovery SQL
    $set_body = $matches_rec[2];
    $new_nama_escaped = addslashes($src['nama_barang']);
    if (strpos($set_body, "nama_barang = '$new_nama_escaped'") === false) {
        echo "FAIL VALUE: ID $id nama_barang di SQL tidak cocok dengan Excel!\n";
        echo "  SQL Set: $set_body\n";
        echo "  Expected: $new_nama_escaped\n";
        $mismatch_count++;
        continue;
    }

    // 5. Cek kolom nama_reagen di DB vs nama_barang
    // Catatan: apakah nama_reagen terisi atau perlu disinkronkan?
    // Mari kita cek
    $valid_count++;
}

echo "Total Valid & Unik: $valid_count / 44\n";
echo "Mismatch: $mismatch_count\n";
echo "Ambiguous: $ambiguous_count\n";
echo "Manual: $manual_count\n";

$mysqli->close();
