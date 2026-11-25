<?php
define('ENVIRONMENT', 'development');
define('BASEPATH', 'dummy');
require_once __DIR__ . '/../application/config/database.php';
require_once __DIR__ . '/../application/libraries/SimpleXLSX.php';

$excelPath = 'C:\\Users\\ASUS\\OneDrive\\Dokumen\\Magang BPOM\\Data BPOM\\Format Data Sampel\\Sampel Kosmetik\\10. Data Contoh Kosmetik Oktober 2026 - 2.xlsx';
$xlsx = SimpleXLSX::parse($excelPath);
$rows = $xlsx->rows(0);

echo "Total rows in file: " . count($rows) . "\n";

// Test our full logic
class TestImporter {
    public static function normalize_header_key($str) {
        $str = strtolower(trim((string)$str));
        $str = str_replace('/', ' ', $str);
        $str = preg_replace('/[^\w\s]/u', ' ', $str);
        $str = preg_replace('/\s+/', ' ', $str);
        return trim($str);
    }

    public static function detect_header_and_data_start($rows) {
        $main_header_idx = -1;
        $max_scan        = min(15, count($rows));

        for ($k = 0; $k < $max_scan; $k++) {
            $row_str = implode(' ', array_filter($rows[$k]));
            $norm    = self::normalize_header_key($row_str);
            if (strpos($norm, 'nama sampel') !== false || strpos($norm, 'kategori sampel') !== false || strpos($norm, 'nama contoh') !== false) {
                $main_header_idx = $k;
                break;
            }
        }

        if ($main_header_idx === -1) {
            $main_header_idx = 0;
        }

        $last_header_idx = $main_header_idx;
        $scan_limit      = min($main_header_idx + 5, count($rows));

        for ($k = $main_header_idx + 1; $k < $scan_limit; $k++) {
            if (!isset($rows[$k])) break;

            $row     = $rows[$k];
            $row_str = implode(' ', array_filter($row));
            $norm    = self::normalize_header_key($row_str);

            $is_sub_header = false;
            $has_data_indicator = false;

            foreach ($row as $v) {
                $c_norm = self::normalize_header_key($v);
                if (in_array($c_norm, array('nama sarana', 'sarana', 'alamat', 'alamat sarana', 'manual', 'sipt', 'kategori sarana', 'kabupaten kota', 'kabupaten', 'ki', 'mi', 'arsip', 'p', 'total', 'kode', 'sub kategori', 'tanggal sampling', 'k', 'r', 'a', 't', 'kode contoh', 'tipe produk'))) {
                    $is_sub_header = true;
                }
                if (strlen(trim((string)$v)) > 45 || preg_match('/(\/p\/|\/o\-b\/|bpom|pirt|susu|desa|kec\.)/i', (string)$v)) {
                    $has_data_indicator = true;
                }
            }

            if ($has_data_indicator) {
                $is_sub_header = false;
            }

            $numeric_count = 0;
            $non_empty     = 0;
            foreach ($row as $v) {
                $v_str = trim((string)$v);
                if ($v_str !== '') {
                    $non_empty++;
                    if (is_numeric($v_str)) {
                        $numeric_count++;
                    }
                }
            }

            $is_numbering_row = ($non_empty > 0 && ($numeric_count / $non_empty) >= 0.7);

            if ($is_sub_header || $is_numbering_row) {
                $last_header_idx = $k;
            } else {
                break;
            }
        }

        $first_data_idx = $last_header_idx + 1;

        $max_cols = 0;
        for ($k = $main_header_idx; $k <= $last_header_idx; $k++) {
            if (isset($rows[$k])) {
                $max_cols = max($max_cols, count($rows[$k]));
            }
        }

        $consolidated_header = array_fill(0, $max_cols, '');
        for ($c = 0; $c < $max_cols; $c++) {
            for ($k = $last_header_idx; $k >= $main_header_idx; $k--) {
                $val = isset($rows[$k][$c]) ? trim((string)$rows[$k][$c]) : '';
                if ($val !== '' && !is_numeric($val)) {
                    $consolidated_header[$c] = $val;
                    break;
                }
            }
        }

        // Deteksi kolom No & Kode Contoh (khusus format Kosmetik BPOM jika Col B adalah "No. Kode Contoh" dan Col C kosong karena merged cell)
        for ($c = 0; $c < $max_cols - 1; $c++) {
            $c_norm = self::normalize_header_key($consolidated_header[$c]);
            if (strpos($c_norm, 'no kode contoh') !== false || ($c_norm === 'kode contoh' && $consolidated_header[$c + 1] === '')) {
                if ($consolidated_header[$c + 1] === '') {
                    $consolidated_header[$c] = 'No';
                    $consolidated_header[$c + 1] = 'No SIPT';
                }
            }
        }

        return array(
            'main_header_idx' => $main_header_idx,
            'last_header_idx' => $last_header_idx,
            'first_data_idx'  => $first_data_idx,
            'header'          => $consolidated_header
        );
    }

    public static function is_footer_or_non_data_row($raw_row, $row_data = array()) {
        $non_empty = array_filter($raw_row, function($v) {
            return trim((string)$v) !== '';
        });

        if (empty($non_empty)) {
            return true;
        }

        $row_text    = strtolower(implode(' ', $non_empty));
        $nama_sampel = isset($row_data['nama_sampel']) ? trim((string)$row_data['nama_sampel']) : '';

        $footer_keywords = array(
            'penerima sampel', 'pengirim sampel', 'diterima oleh', 'diserahkan oleh',
            'petugas sampling', 't = total', 'total =', 'novia hestiningrum', '..........'
        );

        foreach ($footer_keywords as $kw) {
            if (strpos($row_text, $kw) !== false) {
                return true;
            }
        }

        if ($nama_sampel === '') {
            if (preg_match('/[a-z\s]+,\s*\d+\s+[a-z]+\s+\d{4}/i', $row_text)) {
                return true;
            }
            $has_sample_indicator = false;
            foreach ($non_empty as $cell_val) {
                $cell_str = strtolower(trim((string)$cell_val));
                if (
                    strpos($cell_str, '/o-b/') !== false ||
                    strpos($cell_str, 'sipt') !== false ||
                    strpos($cell_str, 'targeted') !== false ||
                    strpos($cell_str, 'jkn') !== false ||
                    preg_match('/^\d{2}\.\d{3}\.\d{3}\./', $cell_str) // SIPT format
                ) {
                    $has_sample_indicator = true;
                    break;
                }
            }

            if (!$has_sample_indicator) {
                return true;
            }
        }

        return false;
    }

    public static function map_headers($header) {
        $field_map = [];
        $unrecognized = [];
        $penandaan_counter = 0;

        foreach ($header as $idx => $raw) {
            $norm = self::normalize_header_key($raw);
            $mapped = null;

            switch ($norm) {
                case 'no':
                case 'no.':
                    $mapped = !in_array('no', $field_map) ? 'no' : 'spesifik:no_agenda';
                    break;
                case 'nama sampel':
                case 'nama contoh':
                case 'contoh':
                    $mapped = 'nama_sampel';
                    break;
                case 'nie':
                case 'nomor izin edar':
                case 'nomor notifikasi':
                case 'no notifikasi':
                    $mapped = 'nomor_izin_edar';
                    break;
                case 'kategori sampel':
                    $mapped = 'kategori_sampel';
                    break;
                case 'tipe produk kategori':
                case 'tipe produk':
                case 'sub kategori':
                    $mapped = 'sub_kategori';
                    break;
                case 'metode pengambilan':
                    $mapped = 'spesifik:metode_pengambilan';
                    break;
                case 'tanggal sampling':
                case 'tgl sampling':
                    $mapped = 'tanggal_sampling';
                    break;
                case 'komposisi':
                    $mapped = 'komposisi';
                    break;
                case 'kemasan netto':
                case 'kemasan':
                    $mapped = 'kemasan';
                    break;
                case 'bentuk sediaan':
                    $mapped = 'spesifik:bentuk_sediaan';
                    break;
                case 'bets':
                case 'no bets':
                case 'no batch':
                case 'batch':
                    $mapped = 'no_bets';
                    break;
                case 'ed':
                case 'exp':
                case 'expired':
                case 'kedaluwarsa':
                    $mapped = 'kedaluwarsa';
                    break;
                case 'k':
                case 'kimia':
                case 'ki':
                case 'jumlah kimia':
                    $mapped = 'jumlah_kimia';
                    break;
                case 'mi':
                case 'mikro':
                case 'mikrobiologi':
                case 'jumlah mikro':
                    $mapped = 'jumlah_mikro';
                    break;
                case 'r':
                case 'jumlah r':
                    $mapped = 'spesifik:jumlah_retain';
                    break;
                case 'p':
                case 'penandaan':
                    $penandaan_counter++;
                    $mapped = ($penandaan_counter === 1) ? 'jumlah_penandaan' : 'penandaan';
                    break;
                case 'a':
                case 'arsip':
                case 'jumlah arsip':
                    $mapped = 'jumlah_arsip';
                    break;
                case 't':
                case 'total':
                case 'jumlah total':
                    $mapped = 'jumlah_total';
                    break;
                case 'tempat sampling':
                case 'nama sarana':
                case 'sarana':
                    $mapped = 'nama_sarana';
                    break;
                case 'kab kota':
                case 'kabupaten kota':
                case 'kabupaten':
                case 'kota':
                    $mapped = 'kabupaten_kota';
                    break;
                case 'pabrik importir':
                case 'produsen':
                case 'nama dan alamat perusahaan':
                    $mapped = 'nama_alamat_perusahaan';
                    break;
                case 'cluster jenis uji':
                case 'cluster':
                    $mapped = 'spesifik:cluster_jenis_uji';
                    break;
                case 'kondisi sampel':
                case 'kondisi produk':
                    $mapped = 'kondisi_produk';
                    break;
                case 'no sipt':
                case 'sipt':
                case 'kode contoh':
                    $mapped = 'no_sipt';
                    break;
                default:
                    if ($norm !== '') {
                        $unrecognized[$idx] = $raw;
                    }
                    break;
            }

            if ($mapped !== null) {
                $field_map[$idx] = $mapped;
            }
        }

        return [
            'status' => in_array('nama_sampel', $field_map),
            'field_map' => $field_map,
            'unrecognized' => $unrecognized,
            'total_mapped' => count($field_map)
        ];
    }
}

$detected = TestImporter::detect_header_and_data_start($rows);
$mapping = TestImporter::map_headers($detected['header']);

echo "=== MAPPING STATUS: " . ($mapping['status'] ? 'SUCCESS' : 'FAILED') . " ===\n";
echo "Total Mapped Columns: " . $mapping['total_mapped'] . "\n";
echo "Field Map:\n";
foreach ($mapping['field_map'] as $colIdx => $f) {
    echo "  Col $colIdx ('{$detected['header'][$colIdx]}') => $f\n";
}
echo "Unrecognized (" . count($mapping['unrecognized']) . "): " . json_encode($mapping['unrecognized']) . "\n\n";

// Now test parsing data rows
$dataRows = [];
$first_data = $detected['first_data_idx'];
for ($i = $first_data; $i < count($rows); $i++) {
    $row = $rows[$i];
    if (empty(array_filter($row))) continue;

    $row_data = [
        'komoditi' => 'kosmetik',
        'status' => 'Menunggu Pengujian'
    ];
    $spesifik = [];
    foreach ($mapping['field_map'] as $colIdx => $dbField) {
        $val = isset($row[$colIdx]) ? trim($row[$colIdx]) : null;
        if (strpos($dbField, 'spesifik:') === 0) {
            $key = substr($dbField, 9);
            $spesifik[$key] = $val !== '' ? $val : null;
        } else {
            $row_data[$dbField] = $val !== '' ? $val : null;
        }
    }
    if (!empty(array_filter($spesifik))) {
        $row_data['atribut_spesifik'] = json_encode($spesifik, JSON_UNESCAPED_UNICODE);
    }

    if (TestImporter::is_footer_or_non_data_row($row, $row_data)) {
        continue;
    }

    if (empty($row_data['nama_sampel'])) {
        echo "WARNING: Row " . ($i + 1) . " has empty nama_sampel! Row content: " . json_encode(array_slice($row, 0, 5)) . "\n";
        continue;
    }

    $dataRows[] = $row_data;
}

echo "=== RESULT: Successfully parsed " . count($dataRows) . " sample data rows! ===\n";
echo "Sample Row 1:\n" . json_encode($dataRows[0], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
echo "Sample Row 2:\n" . json_encode($dataRows[1], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
echo "Sample Last Row (" . count($dataRows) . "):\n" . json_encode(end($dataRows), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
