<?php
define('BASEPATH', 'defined');
define('ENVIRONMENT', 'development');

require_once __DIR__ . '/../application/config/database.php';
require_once __DIR__ . '/../application/libraries/SimpleXLSX.php';

$db_config = $db['default'];
$mysqli = new mysqli($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);
if ($mysqli->connect_error) {
    die("Connect Error: " . $mysqli->connect_error);
}

// 1. Baca seluruh 4 file sumber Excel
$source_dir = 'C:/Users/ASUS/OneDrive/Dokumen/Magang BPOM/Data BPOM/Data Reagen';
$source_files = [
    'Data Baku Pembanding.xlsx',
    'Data Media Mikrobiologi.xlsx',
    'Data Reagen Cair.xlsx',
    'Data Reagen Padat.xlsx'
];

$excel_items_by_kode = [];
$excel_items_by_name = [];
$all_excel_rows = [];

foreach ($source_files as $sf) {
    $path = $source_dir . '/' . $sf;
    if (!file_exists($path)) continue;
    $xlsx = SimpleXLSX::parse($path);
    if (!$xlsx) continue;
    $rows = $xlsx->rows();
    
    // Temukan header
    $header_idx = -1;
    for ($i = 0; $i < min(10, count($rows)); $i++) {
        $str = strtolower(implode(' ', array_filter($rows[$i])));
        if (strpos($str, 'nama barang') !== false || strpos($str, 'kode barang') !== false) {
            $header_idx = $i;
            break;
        }
    }
    if ($header_idx === -1) continue;
    
    $header = $rows[$header_idx];
    $col_map = [];
    foreach ($header as $ci => $cname) {
        $norm = strtolower(trim((string)$cname));
        $norm = preg_replace('/[^\w\s]/u', ' ', $norm);
        $norm = preg_replace('/\s+/', ' ', $norm);
        $norm = trim($norm);
        if (empty($norm)) continue;
        if (strpos($norm, 'nama barang') !== false) $col_map['nama_barang'] = $ci;
        elseif (strpos($norm, 'kode barang') !== false) $col_map['kode_barang'] = $ci;
        elseif (strpos($norm, 'katalog') !== false) $col_map['katalog'] = $ci;
        elseif (strpos($norm, 'merk') !== false) $col_map['merk'] = $ci;
        elseif (strpos($norm, 'stok') !== false) $col_map['stok'] = $ci;
        elseif (strpos($norm, 'jenis barang') !== false) $col_map['jenis_barang'] = $ci;
        elseif (strpos($norm, 'expired date') !== false || strpos($norm, 'expired') !== false) $col_map['expired_date'] = $ci;
    }
    
    for ($r = $header_idx + 1; $r < count($rows); $r++) {
        $row = $rows[$r];
        if (empty(array_filter($row))) continue;
        
        $kode = isset($col_map['kode_barang']) && isset($row[$col_map['kode_barang']]) ? trim((string)$row[$col_map['kode_barang']]) : '';
        $nama = isset($col_map['nama_barang']) && isset($row[$col_map['nama_barang']]) ? trim((string)$row[$col_map['nama_barang']]) : '';
        if (empty($nama) && empty($kode)) continue;
        
        $kat  = isset($col_map['katalog']) && isset($row[$col_map['katalog']]) ? trim((string)$row[$col_map['katalog']]) : '';
        $merk = isset($col_map['merk']) && isset($row[$col_map['merk']]) ? trim((string)$row[$col_map['merk']]) : '';
        $stok = isset($col_map['stok']) && isset($row[$col_map['stok']]) ? trim((string)$row[$col_map['stok']]) : '';
        $jenis= isset($col_map['jenis_barang']) && isset($row[$col_map['jenis_barang']]) ? trim((string)$row[$col_map['jenis_barang']]) : '';
        $exp  = isset($col_map['expired_date']) && isset($row[$col_map['expired_date']]) ? trim((string)$row[$col_map['expired_date']]) : '';
        
        // Parse date
        $parsed_exp = null;
        if (!empty($exp)) {
            if (is_numeric($exp) && (float)$exp > 1000 && (float)$exp < 100000) {
                $days = (int) floor((float)$exp);
                $ts = ($days - 25569) * 86400;
                if ($ts > 0) $parsed_exp = date('Y-m-d', $ts);
            } elseif (preg_match('/^\d{4}-\d{2}-\d{2}$/', $exp)) {
                $parsed_exp = $exp;
            } elseif (preg_match('/^(\d{1,2})[\/\-\.](\d{1,2})[\/\-\.](\d{4})$/', $exp, $m)) {
                $parsed_exp = sprintf('%04d-%02d-%02d', $m[3], $m[2], $m[1]);
            } elseif (strtotime($exp)) {
                $parsed_exp = date('Y-m-d', strtotime($exp));
            }
        }
        
        $item = [
            'source_file'  => $sf,
            'source_row'   => $r + 1,
            'kode_barang'  => $kode,
            'katalog'      => $kat,
            'nama_barang'  => $nama,
            'merk'         => $merk,
            'stok'         => $stok,
            'jenis_barang' => $jenis,
            'expired_date' => $parsed_exp
        ];
        
        if (!empty($kode)) {
            $excel_items_by_kode[strtoupper($kode)] = $item;
        }
        $comp = strtoupper($nama) . '|' . strtoupper($merk);
        $excel_items_by_name[$comp] = $item;
        $all_excel_rows[] = $item;
    }
}

echo "Total Data Excel Ditemukan: " . count($all_excel_rows) . "\n";
echo "Total Unique Kode Barang di Excel: " . count($excel_items_by_kode) . "\n\n";

// 2. Bandingkan dengan seluruh 389 data di tabel reagents
$res_db = $mysqli->query("SELECT * FROM reagents ORDER BY id ASC");
$discrepancies = [];
$zero_date_records = [];
$total_checked = 0;

while ($db_row = $res_db->fetch_assoc()) {
    $total_checked++;
    $id = $db_row['id'];
    $kode = strtoupper(trim((string)$db_row['kode_barang']));
    
    // Cek zero date
    if ($db_row['expired_date'] === '0000-00-00') {
        $zero_date_records[] = $db_row;
    }
    
    // Cari pasangan di Excel
    $matched = null;
    $match_type = '';
    if (!empty($kode) && isset($excel_items_by_kode[$kode])) {
        $matched = $excel_items_by_kode[$kode];
        $match_type = 'KODE_BARANG';
    }
    
    if ($matched) {
        // Cek apakah ada ketidakcocokan
        $diffs = [];
        if ($db_row['nama_barang'] !== $matched['nama_barang']) {
            $diffs['nama_barang'] = ['db' => $db_row['nama_barang'], 'source' => $matched['nama_barang']];
        }
        if ($db_row['katalog'] !== $matched['katalog'] && !(empty($db_row['katalog']) && empty($matched['katalog']))) {
            $diffs['katalog'] = ['db' => $db_row['katalog'], 'source' => $matched['katalog']];
        }
        if ($db_row['merk'] !== $matched['merk'] && !(empty($db_row['merk']) && empty($matched['merk']))) {
            $diffs['merk'] = ['db' => $db_row['merk'], 'source' => $matched['merk']];
        }
        if ($db_row['stok'] !== $matched['stok'] && !(empty($db_row['stok']) && empty($matched['stok']))) {
            $diffs['stok'] = ['db' => $db_row['stok'], 'source' => $matched['stok']];
        }
        if ($db_row['jenis_barang'] !== $matched['jenis_barang'] && !(empty($db_row['jenis_barang']) && empty($matched['jenis_barang']))) {
            $diffs['jenis_barang'] = ['db' => $db_row['jenis_barang'], 'source' => $matched['jenis_barang']];
        }
        if ($db_row['expired_date'] !== $matched['expired_date']) {
            // Catat jika db berisi '0000-00-00' atau berbeda dengan source
            $diffs['expired_date'] = ['db' => $db_row['expired_date'], 'source' => $matched['expired_date']];
        }
        
        if (!empty($diffs)) {
            $discrepancies[] = [
                'id'         => $id,
                'db_row'     => $db_row,
                'source_row' => $matched,
                'diffs'      => $diffs
            ];
        }
    } else {
        // Tidak cocok kode_barang
        // ID 3 adalah dummy lama
        if ($id == 3) continue;
        // Coba match nama
    }
}

echo "=== HASIL REKONSILIASI DATABASE VS EXCEL ASLI ===\n";
echo "Total Record DB Diperiksa : {$total_checked}\n";
echo "Total Record Berbeda/Anomali: " . count($discrepancies) . "\n";
echo "Total Expired Date '0000-00-00': " . count($zero_date_records) . "\n\n";

// Analisis detail ketidakcocokan
$shifted_columns_count = 0;
$date_only_diff_count = 0;

foreach ($discrepancies as $d) {
    $has_nama_diff = isset($d['diffs']['nama_barang']);
    $has_stok_diff = isset($d['diffs']['stok']);
    $has_jenis_diff = isset($d['diffs']['jenis_barang']);
    
    if ($has_nama_diff || $has_stok_diff || $has_jenis_diff) {
        $shifted_columns_count++;
        echo "ID {$d['id']} (Pergeseran Kolom Terbukti!):\n";
        echo "   File Sumber: {$d['source_row']['source_file']} (Baris {$d['source_row']['source_row']})\n";
        echo "   DB Saat Ini:\n";
        echo "      Nama   : '{$d['db_row']['nama_barang']}'\n";
        echo "      Katalog: '{$d['db_row']['katalog']}'\n";
        echo "      Merk   : '{$d['db_row']['merk']}'\n";
        echo "      Stok   : '{$d['db_row']['stok']}'\n";
        echo "      Jenis  : '{$d['db_row']['jenis_barang']}'\n";
        echo "      Exp    : " . var_export($d['db_row']['expired_date'], true) . "\n";
        echo "   Nilai Asli Sumber Excel:\n";
        echo "      Nama   : '{$d['source_row']['nama_barang']}'\n";
        echo "      Katalog: '{$d['source_row']['katalog']}'\n";
        echo "      Merk   : '{$d['source_row']['merk']}'\n";
        echo "      Stok   : '{$d['source_row']['stok']}'\n";
        echo "      Jenis  : '{$d['source_row']['jenis_barang']}'\n";
        echo "      Exp    : " . var_export($d['source_row']['expired_date'], true) . "\n\n";
    } else {
        $date_only_diff_count++;
    }
}

echo "Ringkasan Anomali:\n";
echo "  - Record dengan Pergeseran Kolom (Nama/Stok/Jenis terbukti salah posisi): {$shifted_columns_count}\n";
echo "  - Record yang hanya berbeda format Tanggal / '0000-00-00' vs NULL: {$date_only_diff_count}\n";

$mysqli->close();
