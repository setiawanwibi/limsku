<?php
define('ENVIRONMENT', 'development');
define('BASEPATH', 'dummy');
require_once __DIR__ . '/../application/libraries/SimpleXLSX.php';

$excelPath = 'C:\\Users\\ASUS\\OneDrive\\Dokumen\\Magang BPOM\\Data BPOM\\Format Data Sampel\\Sampel Obat Tradisional dan Suplemen\\6. Data contoh OTSK JUNI 2026 NEW.xlsx';
$xlsx = SimpleXLSX::parse($excelPath);

function test_otsk_pipeline($rows, $komoditi_label) {
    echo "========================================================\n";
    echo "TESTING: $komoditi_label\n";
    echo "========================================================\n";

    // 1. Detect header
    $main_header_idx = -1;
    for ($k = 0; $k < min(15, count($rows)); $k++) {
        $row_str = strtolower(implode(' ', array_filter($rows[$k])));
        if (
            strpos($row_str, 'nama sampel') !== false || 
            strpos($row_str, 'kategori sampel') !== false || 
            strpos($row_str, 'nama contoh') !== false ||
            strpos($row_str, 'nama barang') !== false
        ) {
            $main_header_idx = $k;
            break;
        }
    }
    if ($main_header_idx === -1) $main_header_idx = 0;

    $last_header_idx = $main_header_idx;
    for ($k = $main_header_idx + 1; $k < min($main_header_idx + 5, count($rows)); $k++) {
        $row = $rows[$k];
        $is_sub = false;
        foreach ($row as $v) {
            $v_clean = strtolower(trim($v));
            if (in_array($v_clean, ['k', 'm', 'mi', 'r', 'p', 'a', 'j', 't', 'ki', 'manual', 'sipt', 'sarana', 'alamat', 'tempat', 'tanggal', 'total'])) {
                $is_sub = true;
                break;
            }
        }

        // numbering row check
        $non_empty = 0; $num_cnt = 0;
        foreach ($row as $v) {
            $vt = trim($v);
            if ($vt !== '') {
                $non_empty++;
                if (is_numeric($vt)) $num_cnt++;
            }
        }
        $is_numbering = ($non_empty > 0 && ($num_cnt / $non_empty) >= 0.7);

        if ($is_sub || $is_numbering) {
            $last_header_idx = $k;
        } else {
            break;
        }
    }

    $first_data_idx = $last_header_idx + 1;
    $max_cols = 0;
    for ($k = $main_header_idx; $k <= $last_header_idx; $k++) {
        $max_cols = max($max_cols, count($rows[$k]));
    }

    $consolidated_header = array_fill(0, $max_cols, '');
    for ($c = 0; $c < $max_cols; $c++) {
        for ($k = $last_header_idx; $k >= $main_header_idx; $k--) {
            $val = trim($rows[$k][$c] ?? '');
            if ($val !== '' && !is_numeric($val)) {
                $consolidated_header[$c] = $val;
                break;
            }
        }
    }

    // Auto-fix OTSK merged headers:
    // If Col 1 is 'NO.' and Col 2 is 'NO' and Col 3 is '':
    for ($c = 0; $c < $max_cols - 1; $c++) {
        $c_clean = strtolower(trim(preg_replace('/[^\w\s]/', ' ', $consolidated_header[$c])));
        $c_next = trim($consolidated_header[$c + 1] ?? '');
        
        // Merge No. Kode Contoh (Kosmetik)
        if (strpos($c_clean, 'no kode contoh') !== false && $c_next === '') {
            $consolidated_header[$c] = 'No';
            $consolidated_header[$c + 1] = 'No SIPT';
        }
        // Merge NO & Kode Sampel Manual (OTSK)
        if (($c_clean === 'no' || $c_clean === 'nomor') && $c_next === '') {
            $consolidated_header[$c] = 'No SIPT';
            $consolidated_header[$c + 1] = 'Kode Sampel Manual';
        }
        // Merge NIE prefix & number
        if ($c_clean === 'nie' && $c_next === '') {
            $consolidated_header[$c] = 'NIE Prefix';
            $consolidated_header[$c + 1] = 'Nomor Izin Edar';
        }
    }

    echo "main_header_idx: $main_header_idx | last_header_idx: $last_header_idx | first_data_idx: $first_data_idx\n";
    echo "Consolidated Header:\n";
    foreach ($consolidated_header as $idx => $h) {
        if ($h !== '') echo "  [$idx] => '$h'\n";
    }

    // Map headers
    $field_map = [];
    $unrecognized = [];
    foreach ($consolidated_header as $idx => $raw) {
        $norm = strtolower(trim(str_replace('/', ' ', preg_replace('/[^\w\s]/', ' ', $raw))));
        $norm = trim(preg_replace('/\s+/', ' ', $norm));
        $mapped = null;

        switch ($norm) {
            case 'no':
            case 'no.':
                $mapped = !in_array('no', $field_map) ? 'no' : 'spesifik:no_agenda';
                break;
            case 'no sipt':
            case 'sipt':
            case 'nomor sipt':
                $mapped = 'no_sipt';
                break;
            case 'kode sampel manual':
            case 'kode sampel':
            case 'manual':
                $mapped = 'kode_sampel_manual';
                break;
            case 'nama sampel':
            case 'nama barang':
            case 'nama contoh':
            case 'contoh':
                $mapped = 'nama_sampel';
                break;
            case 'bentuk sediaan':
                $mapped = 'spesifik:bentuk_sediaan';
                break;
            case 'jenis sampel':
            case 'kategori sampel':
                $mapped = 'kategori_sampel';
                break;
            case 'komposisi zat berkhasiat':
            case 'komposisi':
                $mapped = 'komposisi';
                break;
            case 'nama industri':
            case 'produsen':
            case 'nama dan alamat perusahaan':
                $mapped = 'nama_alamat_perusahaan';
                break;
            case 'nie prefix':
                $mapped = 'spesifik:nie_prefix';
                break;
            case 'nie':
            case 'nomor izin edar':
            case 'nomor notifikasi':
                $mapped = 'nomor_izin_edar';
                break;
            case 'no batch':
            case 'no bets':
            case 'batch':
            case 'bets':
                $mapped = 'no_bets';
                break;
            case 'ed':
            case 'exp':
            case 'expired':
            case 'kedaluwarsa':
                $mapped = 'kedaluwarsa';
                break;
            case 'kemasan':
                $mapped = 'kemasan';
                break;
            case 'tempat':
            case 'nama sarana':
            case 'sarana':
                $mapped = 'nama_sarana';
                break;
            case 'tanggal':
            case 'tanggal sampling':
            case 'tgl sampling':
                $mapped = 'tanggal_sampling';
                break;
            case 'k':
            case 'ki':
            case 'kimia':
            case 'jumlah kimia':
                $mapped = 'jumlah_kimia';
                break;
            case 'm':
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
                $mapped = 'jumlah_penandaan';
                break;
            case 'a':
            case 'arsip':
            case 'jumlah arsip':
                $mapped = 'jumlah_arsip';
                break;
            case 'j':
            case 't':
            case 'total':
            case 'jumlah total':
                $mapped = 'jumlah_total';
                break;
            case 'ket':
            case 'keterangan':
                $mapped = 'penandaan';
                break;
            default:
                if ($norm !== '') $unrecognized[$idx] = $raw;
                break;
        }
        if ($mapped !== null) $field_map[$idx] = $mapped;
    }

    echo "Field Map (" . count($field_map) . " columns):\n";
    foreach ($field_map as $c => $f) {
        echo "  Col $c ('{$consolidated_header[$c]}') => $f\n";
    }
    echo "Unrecognized: " . json_encode($unrecognized) . "\n";

    // Parse sample data rows
    $parsed_samples = [];
    for ($i = $first_data_idx; $i < count($rows); $i++) {
        $row = $rows[$i];
        if (empty(array_filter($row))) continue;

        $row_data = [];
        $spesifik = [];
        foreach ($field_map as $c => $f) {
            $val = isset($row[$c]) ? trim($row[$c]) : null;
            if (strpos($f, 'spesifik:') === 0) {
                $k_spec = substr($f, 9);
                $spesifik[$k_spec] = ($val !== '') ? $val : null;
            } else {
                $row_data[$f] = ($val !== '') ? $val : null;
            }
        }

        // Combine NIE Prefix and Nomor Izin Edar if needed
        if (!empty($spesifik['nie_prefix']) && !empty($row_data['nomor_izin_edar'])) {
            $row_data['nomor_izin_edar'] = trim($spesifik['nie_prefix'] . ' ' . $row_data['nomor_izin_edar']);
        } elseif (!empty($spesifik['nie_prefix']) && empty($row_data['nomor_izin_edar'])) {
            $row_data['nomor_izin_edar'] = $spesifik['nie_prefix'];
        }

        // Footer check
        $row_text = strtolower(implode(' ', array_filter($row)));
        if (
            strpos($row_text, 'diterima oleh') !== false || 
            strpos($row_text, 'disetujui oleh') !== false ||
            strpos($row_text, 'koordinator') !== false ||
            strpos($row_text, 'bandar lampung,') !== false
        ) {
            continue;
        }

        if (empty($row_data['nama_sampel'])) {
            continue;
        }

        $parsed_samples[] = $row_data;
    }

    echo "Total Valid Sample Rows Parsed: " . count($parsed_samples) . "\n";
    echo "First Sample:\n" . json_encode($parsed_samples[0] ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
    echo "Last Sample:\n" . json_encode(end($parsed_samples) ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";
}

test_otsk_pipeline($xlsx->rows(0), "OBAT TRADISIONAL (Sheet 0)");
test_otsk_pipeline($xlsx->rows(1), "SUPLEMEN KESEHATAN (Sheet 1)");
