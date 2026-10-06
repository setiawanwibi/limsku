<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Excel_Importer Helper Class / Service
 * 
 * Menangani parsing, normalisasi header 31 field, konversi serial date Excel,
 * deteksi otomatis stacked/merged headers, serta eliminasi footer non-data.
 */
class Excel_Importer
{
    /**
     * Konversi Excel serial date number (misal 46175.0 atau 46175) ke string Y-m-d.
     * 
     * @param mixed $val
     * @return string|null
     */
    public static function convert_excel_date($val)
    {
        if ($val === NULL || $val === '') {
            return NULL;
        }

        $val = trim((string)$val);

        // Jika berupa angka serial (misal 46175.0 atau 46175)
        if (is_numeric($val) && (float)$val > 10000 && (float)$val < 100000) {
            $days = (int) floor((float)$val);
            // Excel leap year bug: 25569 = 1970-01-01 (1900 date system)
            $timestamp = ($days - 25569) * 86400;
            if ($timestamp > 0) {
                return date('Y-m-d', $timestamp);
            }
        }

        // Jika value berupa teks tanggal seperti "Okt 2028" atau "2026-10-06"
        return $val;
    }

    /**
     * Normalisasi string header untuk pencocokan toleran
     * 
     * @param string $str
     * @return string
     */
    public static function normalize_header_key($str)
    {
        $str = strtolower(trim((string)$str));
        // Ganti '/' dengan spasi
        $str = str_replace('/', ' ', $str);
        // Hapus karakter non-alphanumeric selain spasi
        $str = preg_replace('/[^\w\s]/u', ' ', $str);
        // Normalisasi multiple whitespace menjadi single space
        $str = preg_replace('/\s+/', ' ', $str);
        return trim($str);
    }

    /**
     * Mendeteksi baris header utama, konsolidasi stacked headers, dan index data pertama.
     * 
     * @param array $rows Seluruh baris yang dibaca dari Excel / CSV
     * @return array Array berisi 'main_header_idx', 'last_header_idx', 'first_data_idx', 'header'
     */
    public static function detect_header_and_data_start($rows)
    {
        $main_header_idx = -1;
        $max_scan        = min(15, count($rows));

        for ($k = 0; $k < $max_scan; $k++) {
            $row_str = implode(' ', array_filter($rows[$k]));
            $norm    = self::normalize_header_key($row_str);
            if (strpos($norm, 'nama sampel') !== false || strpos($norm, 'kategori sampel') !== false) {
                $main_header_idx = $k;
                break;
            }
        }

        if ($main_header_idx === -1) {
            $main_header_idx = 0; // Fallback
        }

        // Cek baris-baris berikutnya yang merupakan stacked sub-header atau baris penomoran
        $last_header_idx = $main_header_idx;
        $scan_limit      = min($main_header_idx + 5, count($rows));

        for ($k = $main_header_idx + 1; $k < $scan_limit; $k++) {
            if (!isset($rows[$k])) {
                break;
            }

            $row     = $rows[$k];
            $row_str = implode(' ', array_filter($row));
            $norm    = self::normalize_header_key($row_str);

            $is_sub_header = (
                strpos($norm, 'kategori sarana') !== false ||
                strpos($norm, 'nama sarana') !== false ||
                strpos($norm, 'kabupaten') !== false ||
                strpos($norm, 'tanggal sampling') !== false ||
                strpos($norm, 'kimia') !== false ||
                strpos($norm, 'mikro') !== false ||
                strpos($norm, 'arsip') !== false
            );

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

        // Konsolidasi header dari main_header_idx s/d last_header_idx
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

        return array(
            'main_header_idx' => $main_header_idx,
            'last_header_idx' => $last_header_idx,
            'first_data_idx'  => $first_data_idx,
            'header'          => $consolidated_header
        );
    }

    /**
     * Memeriksa apakah suatu baris merupakan catatan kaki / blok tanda tangan / non-data.
     * 
     * @param array $raw_row Data mentah baris Excel
     * @param array $row_data Data baris yang sudah dipetakan
     * @return bool TRUE jika baris adalah footer / non-data, FALSE jika baris data sampel
     */
    public static function is_footer_or_non_data_row($raw_row, $row_data = array())
    {
        $non_empty = array_filter($raw_row, function($v) {
            return trim((string)$v) !== '';
        });

        if (empty($non_empty)) {
            return true; // Baris kosong
        }

        $row_text    = strtolower(implode(' ', $non_empty));
        $nama_sampel = isset($row_data['nama_sampel']) ? trim((string)$row_data['nama_sampel']) : '';

        // Keyword khusus blok tanda tangan / footer administratif
        $footer_keywords = array(
            'penerima sampel', 'pengirim sampel', 'diterima oleh', 'diserahkan oleh',
            'novia hestiningrum', '..........'
        );

        foreach ($footer_keywords as $kw) {
            if (strpos($row_text, $kw) !== false) {
                return true;
            }
        }

        // Cek tanggal laporan / catatan kaki tanpa nama sampel (misal: "Bandar Lampung, 5 Juni 2026")
        if ($nama_sampel === '') {
            if (preg_match('/[a-z\s]+,\s*\d+\s+[a-z]+\s+\d{4}/i', $row_text)) {
                return true;
            }

            // Cek apakah ada indikator data sampel di kolom lain (seperti nomor sampel / SIPT / NIE / Bets / Kategori)
            $has_sample_indicator = false;
            foreach ($non_empty as $cell_val) {
                $cell_str = strtolower(trim((string)$cell_val));
                if (
                    strpos($cell_str, '/o-b/') !== false ||
                    strpos($cell_str, 'sipt') !== false ||
                    strpos($cell_str, 'targeted') !== false ||
                    strpos($cell_str, 'jkn') !== false
                ) {
                    $has_sample_indicator = true;
                    break;
                }
            }

            // Jika tidak ada nama sampel dan tidak ada indikator sampel sama sekali, ini adalah footer non-data
            if (!$has_sample_indicator) {
                return true;
            }
        }

        return false;
    }

    /**
     * Memetakan header Excel ke 31 field database
     * 
     * @param array $header
     * @return array
     */
    public static function map_headers($header)
    {
        $field_map         = array();
        $mapped_details    = array();
        $unrecognized      = array();
        $penandaan_counter = 0;

        foreach ($header as $idx => $raw_col_name) {
            $normalized = self::normalize_header_key($raw_col_name);
            $mapped_field = NULL;

            switch ($normalized) {
                case 'no':
                case 'no.':
                    $mapped_field = 'no';
                    break;
                case 'kategori sampel':
                    $mapped_field = 'kategori_sampel';
                    break;
                case 'sub kategori':
                    $mapped_field = 'sub_kategori';
                    break;
                case 'jenis kelas terapi':
                case 'jenis terapi':
                    $mapped_field = 'jenis_kelas_terapi';
                    break;
                case 'kategori sarana':
                    $mapped_field = 'kategori_sarana';
                    break;
                case 'nama sarana':
                    $mapped_field = 'nama_sarana';
                    break;
                case 'kabupaten kota':
                case 'kabupaten':
                case 'kota':
                    $mapped_field = 'kabupaten_kota';
                    break;
                case 'tanggal sampling':
                case 'tgl sampling':
                    $mapped_field = 'tanggal_sampling';
                    break;
                case 'kode sampel manual':
                case 'kode sampel':
                    $mapped_field = 'kode_sampel_manual';
                    break;
                case 'no sipt':
                    $mapped_field = 'no_sipt';
                    break;
                case 'nama sampel':
                    $mapped_field = 'nama_sampel';
                    break;
                case 'nomor izin edar':
                case 'nie':
                    $mapped_field = 'nomor_izin_edar';
                    break;
                case 'kondisi produk':
                case 'kondisi sampel':
                    $mapped_field = 'kondisi_produk';
                    break;
                case 'no bets':
                case 'no batch':
                case 'batch':
                    $mapped_field = 'no_bets';
                    break;
                case 'kedaluwarsa':
                case 'exp':
                case 'expired':
                    $mapped_field = 'kedaluwarsa';
                    break;
                case 'kemasan':
                    $mapped_field = 'kemasan';
                    break;
                case 'nama dan alamat perusahaan':
                case 'nama alamat perusahaan':
                case 'produsen':
                case 'pabrik':
                    $mapped_field = 'nama_alamat_perusahaan';
                    break;
                case 'komposisi':
                    $mapped_field = 'komposisi';
                    break;
                case 'kimia':
                    $mapped_field = 'jumlah_kimia';
                    break;
                case 'mikro':
                case 'mikrobiologi':
                    $mapped_field = 'jumlah_mikro';
                    break;
                case 'arsip di balai penguji':
                case 'arsip balai penguji':
                case 'arsip':
                    $mapped_field = 'jumlah_arsip';
                    break;
                case 'penandaan':
                    $penandaan_counter++;
                    if ($penandaan_counter === 1) {
                        $mapped_field = 'jumlah_penandaan';
                    } else {
                        $mapped_field = 'penandaan';
                    }
                    break;
                case 'jumlah penandaan':
                    $mapped_field = 'jumlah_penandaan';
                    break;
                case 'total':
                case 'jumlah total':
                    $mapped_field = 'jumlah_total';
                    break;
                case 'penyimpanan':
                    $mapped_field = 'penyimpanan';
                    break;
                case 'harga':
                    $mapped_field = 'harga';
                    break;
                case 'tie':
                    $mapped_field = 'tie';
                    break;
                case 'mk':
                    $mapped_field = 'mk';
                    break;
                case 'tmk':
                    $mapped_field = 'tmk';
                    break;
                case 'surtug':
                case 'surat tugas':
                    $mapped_field = 'surtug';
                    break;
                case 'balai penguji':
                case 'balai':
                    $mapped_field = 'balai_penguji';
                    break;
                default:
                    $unrecognized[$idx] = $raw_col_name;
                    break;
            }

            if ($mapped_field !== NULL) {
                $field_map[$idx] = $mapped_field;
                $mapped_details[$mapped_field] = array(
                    'col_index' => $idx,
                    'raw_header' => $raw_col_name
                );
            }
        }

        // Cek keberadaan nama_sampel sebagai field mandatory
        if (!in_array('nama_sampel', $field_map)) {
            return array(
                'status'         => FALSE,
                'pesan'          => 'Kolom mandatory "Nama Sampel" tidak berhasil dipetakan.',
                'mapped_details' => $mapped_details,
                'unrecognized'   => $unrecognized
            );
        }

        return array(
            'status'         => TRUE,
            'field_map'      => $field_map,
            'mapped_details' => $mapped_details,
            'unrecognized'   => $unrecognized,
            'total_mapped'   => count($field_map)
        );
    }
}
