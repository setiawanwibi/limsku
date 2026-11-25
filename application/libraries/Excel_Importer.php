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

        // Jika berupa angka serial Excel (misal 46113 -> 2026-04-01, 46705 -> 2027-11-14)
        if (is_numeric($val) && (float)$val >= 30000 && (float)$val <= 65000) {
            $days = (int) floor((float)$val);
            // Excel leap year bug: 25569 = 1970-01-01 (1900 date system)
            $timestamp = ($days - 25569) * 86400;
            if ($timestamp > 0) {
                return date('Y-m-d', $timestamp);
            }
        }

        // Jika berupa 8 digit numerik DDMMYYYY (misal 12062026 -> 2026-06-12)
        if (preg_match('/^(\d{2})(\d{2})(\d{4})$/', $val, $m)) {
            if ((int)$m[1] <= 31 && (int)$m[2] <= 12) {
                return "{$m[3]}-{$m[2]}-{$m[1]}";
            }
        }

        // Jika berupa 5 atau 6 digit DMMYY / DDMMYY (misal 80126 -> 2026-01-08, 080128 -> 2028-01-08)
        if (preg_match('/^(\d{1,2})(\d{2})(\d{2})$/', $val, $m)) {
            $d = str_pad($m[1], 2, '0', STR_PAD_LEFT);
            $mth = $m[2];
            $yr = (int)$m[3] < 50 ? '20' . $m[3] : '19' . $m[3];
            if ((int)$d <= 31 && (int)$mth <= 12) {
                return "{$yr}-{$mth}-{$d}";
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
            if (
                strpos($norm, 'nama sampel') !== false ||
                strpos($norm, 'kategori sampel') !== false ||
                strpos($norm, 'nama contoh') !== false ||
                strpos($norm, 'nama barang') !== false
            ) {
                $main_header_idx = $k;
                break;
            }
        }

        if ($main_header_idx === -1) {
            $main_header_idx = 0; // Fallback
        }

        // Ekstraksi metadata Surat Tugas / Dokumen di baris atas jika ada
        $detected_surtug = NULL;
        for ($k = 0; $k < $main_header_idx; $k++) {
            if (!isset($rows[$k])) continue;
            $row_str = implode(' ', array_filter($rows[$k]));
            if (preg_match('/surat\s*tugas\s*[:\s]+([^\n\r]+)/i', $row_str, $sm)) {
                $detected_surtug = trim(ltrim($sm[1], ': '));
            }
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

            $is_sub_header = false;
            $has_data_indicator = false;

            foreach ($row as $v) {
                $c_norm = self::normalize_header_key($v);
                if (in_array($c_norm, array('nama sarana', 'sarana', 'alamat', 'alamat sarana', 'manual', 'sipt', 'kategori sarana', 'kabupaten kota', 'kabupaten', 'ki', 'mi', 'arsip', 'p', 'total', 'kode', 'sub kategori', 'tanggal sampling', 'k', 'r', 'a', 't', 'm', 'j', 'tempat', 'tanggal', 'kode contoh', 'tipe produk'))) {
                    $is_sub_header = true;
                }
                // Jika sel mengandung indikator baris data (panjang > 45 karakter atau format kode sampel / izin edar BPOM)
                if (strlen(trim((string)$v)) > 45 || preg_match('/(\/p\/|\/o\-b\/|\/ot\/|\/sk\/|bpom|pirt|susu|desa|kec\.)/i', (string)$v)) {
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

        // Deteksi kolom Petugas Sampling (khusus format Pangan BPOM jika Kolom A tidak berheader namun baris data berisi nama)
        if ($consolidated_header[0] === '' && isset($rows[$first_data_idx][0])) {
            $first_val = trim((string)$rows[$first_data_idx][0]);
            if ($first_val !== '' && !is_numeric($first_val) && strlen($first_val) > 2) {
                $consolidated_header[0] = 'Petugas Sampling';
            }
        }

        // Deteksi & normalisasi kolom merged cells khusus (Kosmetik & OTSK: Obat Tradisional / Suplemen)
        for ($c = 0; $c < $max_cols - 1; $c++) {
            $c_norm = self::normalize_header_key($consolidated_header[$c]);
            $c_next = trim($consolidated_header[$c + 1] ?? '');

            // Format Kosmetik: No. Kode Contoh (merged B7:C8)
            if (strpos($c_norm, 'no kode contoh') !== false && $c_next === '') {
                $consolidated_header[$c] = 'No';
                $consolidated_header[$c + 1] = 'No SIPT';
            }
            // Format OTSK: NO (merged C2:D3) -> No SIPT & Kode Sampel Manual
            if (($c_norm === 'no' || $c_norm === 'nomor') && $c_next === '') {
                $consolidated_header[$c] = 'No SIPT';
                $consolidated_header[$c + 1] = 'Kode Sampel Manual';
            }
            // Format OTSK: NIE (merged J2:K3) -> NIE Prefix & Nomor Izin Edar
            if ($c_norm === 'nie' && $c_next === '') {
                $consolidated_header[$c] = 'NIE Prefix';
                $consolidated_header[$c + 1] = 'Nomor Izin Edar';
            }
        }

        return array(
            'main_header_idx' => $main_header_idx,
            'last_header_idx' => $last_header_idx,
            'first_data_idx'  => $first_data_idx,
            'header'          => $consolidated_header,
            'surtug'          => $detected_surtug
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
            'disetujui oleh', 'disetuji oleh', 'koordinator obaoksk', 'koordinator',
            'petugas sampling', 't = total', 'total =', 'novia hestiningrum', '..........'
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
                    strpos($cell_str, 'jkn') !== false ||
                    preg_match('/^\d{2}\.\d{3}\.\d{3}\./', $cell_str)
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
                    if (!in_array('no', $field_map)) {
                        $mapped_field = 'no';
                    } else {
                        $mapped_field = 'spesifik:no_agenda';
                    }
                    break;
                case 'petugas sampling':
                case 'petugas':
                case 'pengambil sampel':
                case 'nama petugas':
                    $mapped_field = 'spesifik:petugas_sampling';
                    break;
                case 'kode':
                    $mapped_field = 'spesifik:kode_pangan';
                    break;
                case 'kategori sampel':
                case 'jenis sampel':
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
                case 'sarana':
                case 'sarana sampling':
                case 'tempat sampling':
                case 'tempat dan tanggal sampling':
                case 'tempat':
                    $mapped_field = 'nama_sarana';
                    break;
                case 'alamat':
                case 'alamat sarana':
                case 'lokasi sarana':
                    $mapped_field = 'spesifik:alamat_sarana';
                    break;
                case 'kabupaten kota':
                case 'kab kota':
                case 'kabupaten':
                case 'kota':
                    $mapped_field = 'kabupaten_kota';
                    break;
                case 'tanggal sampling':
                case 'tgl sampling':
                case 'tanggal':
                case 'tgl':
                    $mapped_field = 'tanggal_sampling';
                    break;
                case 'kode sampel manual':
                case 'kode sampel':
                case 'manual':
                case 'no sampel manual':
                case 'no sampel':
                    $mapped_field = 'kode_sampel_manual';
                    break;
                case 'no sipt':
                case 'sipt':
                case 'nomor sipt':
                case 'kode contoh':
                    $mapped_field = 'no_sipt';
                    break;
                case 'nama sampel':
                case 'nama contoh':
                case 'nama barang':
                case 'contoh':
                    $mapped_field = 'nama_sampel';
                    break;
                case 'nomor izin edar':
                case 'nie':
                    $mapped_field = 'nomor_izin_edar';
                    break;
                case 'nie prefix':
                    $mapped_field = 'spesifik:nie_prefix';
                    break;
                case 'kondisi produk':
                case 'kondisi sampel':
                    $mapped_field = 'kondisi_produk';
                    break;
                case 'no bets':
                case 'no batch':
                case 'batch':
                case 'bets':
                    $mapped_field = 'no_bets';
                    break;
                case 'tanggal produksi':
                case 'tgl produksi':
                case 'tgl pembuatan':
                case 'tgl prod':
                    $mapped_field = 'spesifik:tanggal_produksi';
                    break;
                case 'kedaluwarsa':
                case 'ed':
                case 'exp':
                case 'expired':
                case 'tgl kadaluarsa':
                case 'tgl kedaluwarsa':
                case 'tanggal kadaluarsa':
                case 'tanggal kedaluwarsa':
                    $mapped_field = 'kedaluwarsa';
                    break;
                case 'kemasan':
                case 'kemasan netto':
                    $mapped_field = 'kemasan';
                    break;
                case 'nama dan alamat perusahaan':
                case 'nama alamat perusahaan':
                case 'produsen':
                case 'pabrik':
                case 'importir':
                case 'pabrik importir':
                case 'pabrik importir produsen':
                case 'nama industri':
                    $mapped_field = 'nama_alamat_perusahaan';
                    break;
                case 'komposisi':
                case 'komposisi zat berkhasiat':
                    $mapped_field = 'komposisi';
                    break;
                case 'kimia':
                case 'ki':
                case 'k':
                case 'jumlah kimia':
                case 'jumlah k':
                    $mapped_field = 'jumlah_kimia';
                    break;
                case 'mikro':
                case 'mikrobiologi':
                case 'mi':
                case 'm':
                case 'jumlah mikro':
                case 'jumlah mi':
                    $mapped_field = 'jumlah_mikro';
                    break;
                case 'arsip di balai penguji':
                case 'arsip balai penguji':
                case 'arsip':
                case 'a':
                case 'jumlah arsip':
                case 'jumlah a':
                    $mapped_field = 'jumlah_arsip';
                    break;
                case 'penandaan':
                case 'p':
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
                case 't':
                case 'j':
                case 'jlh':
                case 'jumlah total':
                case 'jumlah t':
                    $mapped_field = 'jumlah_total';
                    break;
                case 'ket':
                case 'keterangan':
                    $mapped_field = 'penandaan';
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

                // =====================================================
                // KOLOM KHUSUS KOMODITI: PANGAN & AIR
                // =====================================================
                case 'kategori pangan':
                case 'kategori pangan bpom':
                    $mapped_field = 'spesifik:kategori_pangan';
                    break;
                case 'kode produksi':
                case 'kode produksi exp date':
                    $mapped_field = 'spesifik:kode_produksi';
                    break;
                case 'jenis kemasan':
                case 'kemasan pangan':
                    $mapped_field = 'kemasan';
                    break;
                case 'suhu sampling':
                case 'suhu pengambilan sampel':
                    $mapped_field = 'penyimpanan';
                    break;
                case 'volume netto':
                case 'volume berat bersih':
                case 'netto':
                    $mapped_field = 'spesifik:volume_netto';
                    break;
                case 'target pengujian':
                case 'target pengujian utama':
                case 'parameter uji':
                    $mapped_field = 'spesifik:target_pengujian';
                    break;
                case 'nama alamat produsen':
                case 'produsen pangan':
                case 'pabrik pangan':
                    $mapped_field = 'nama_alamat_perusahaan';
                    break;

                // =====================================================
                // KOLOM KHUSUS KOMODITI: HERBAL / JAMU
                // =====================================================
                case 'bentuk sediaan':
                case 'bentuk sediaan herbal':
                    $mapped_field = 'spesifik:bentuk_sediaan';
                    break;
                case 'no bets exp':
                case 'no bets dan exp':
                    $mapped_field = 'no_bets';
                    break;
                case 'klaim khasiat':
                case 'khasiat':
                case 'kegunaan tradisional':
                    $mapped_field = 'spesifik:klaim_khasiat';
                    break;
                case 'skrining bko':
                case 'bko':
                case 'skrining bahan kimia obat':
                    $mapped_field = 'spesifik:skrining_bko';
                    break;
                case 'simplisia utama':
                case 'kandungan herbal':
                case 'bahan herbal':
                    $mapped_field = 'komposisi';
                    break;

                // =====================================================
                // KOLOM KHUSUS KOMODITI: KOSMETIK
                // =====================================================
                case 'kategori kosmetik':
                    $mapped_field = 'spesifik:kategori_kosmetik';
                    break;
                case 'tipe produk kategori':
                case 'tipe produk':
                    $mapped_field = 'sub_kategori';
                    break;
                case 'metode pengambilan':
                    $mapped_field = 'spesifik:metode_pengambilan';
                    break;
                case 'cluster jenis uji':
                case 'cluster':
                    $mapped_field = 'spesifik:cluster_jenis_uji';
                    break;
                case 'r':
                case 'jumlah r':
                    $mapped_field = 'spesifik:jumlah_retain';
                    break;
                case 'nomor notifikasi':
                case 'notifikasi bpom':
                case 'no notifikasi':
                    $mapped_field = 'nomor_izin_edar';
                    break;
                case 'asal produk':
                    $mapped_field = 'spesifik:asal_produk';
                    break;
                case 'target uji bahan berbahaya':
                case 'target uji bahaya':
                case 'uji bahan berbahaya':
                    $mapped_field = 'spesifik:target_uji_bahaya';
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

    /**
     * Mendapatkan label ramah pengguna untuk setiap komoditi
     * 
     * @param string $komoditi
     * @return string
     */
    public static function get_commodity_label($komoditi)
    {
        $map = array(
            'obat'     => 'Obat',
            'pangan'   => 'Pangan',
            'kosmetik' => 'Kosmetik',
            'herbal'   => 'Obat Tradisional (Obat Bahan Alam)',
            'suplemen' => 'Suplemen Kesehatan'
        );

        return isset($map[$komoditi]) ? $map[$komoditi] : ucfirst($komoditi);
    }

    /**
     * Mendeteksi format komoditi dari kumpulan baris Excel dan nama sheet
     * 
     * @param array $rows
     * @param string $sheet_name
     * @return array
     */
    public static function detect_commodity_format($rows, $sheet_name = '')
    {
        $scores = array(
            'obat'     => 0,
            'pangan'   => 0,
            'kosmetik' => 0,
            'herbal'   => 0,
            'suplemen' => 0
        );

        if (empty($rows)) {
            return array('detected' => 'unknown', 'score' => 0, 'scores' => $scores);
        }

        // 1. Periksa nama sheet jika ada
        $s_clean = strtolower(trim($sheet_name));
        if ($s_clean !== '') {
            if (strpos($s_clean, 'kosmetik') !== false) {
                $scores['kosmetik'] += 60;
            }
            if (strpos($s_clean, 'pangan') !== false) {
                $scores['pangan'] += 60;
            }
            if (strpos($s_clean, 'oba') !== false || strpos($s_clean, 'obat bahan alam') !== false || strpos($s_clean, 'herbal') !== false || strpos($s_clean, 'jamu') !== false) {
                $scores['herbal'] += 80;
            }
            if (preg_match('/\b(sk|suplemen)\b/i', $s_clean)) {
                $scores['suplemen'] += 80;
            }
            if (strpos($s_clean, 'obat') !== false && strpos($s_clean, 'obat bahan alam') === false && strpos($s_clean, 'obat tradisional') === false && strpos($s_clean, 'oba') === false) {
                $scores['obat'] += 60;
            }
        }

        // 2. Periksa Banner / Baris Judul Awal (Baris 0 - 5)
        $banner_text = '';
        for ($i = 0; $i < min(6, count($rows)); $i++) {
            if (!empty($rows[$i]) && is_array($rows[$i])) {
                $banner_text .= ' ' . strtolower(implode(' ', array_filter($rows[$i])));
            }
        }

        if (strpos($banner_text, 'kosmetik') !== false) {
            $scores['kosmetik'] += 100;
        }
        if (strpos($banner_text, 'obat bahan alam') !== false || strpos($banner_text, 'obat tradisional') !== false || strpos($banner_text, 'obaoksk') !== false) {
            $scores['herbal'] += 100;
        }
        if (strpos($banner_text, 'suplemen kesehatan') !== false || strpos($banner_text, 'suplemen') !== false) {
            $scores['suplemen'] += 100;
        }
        if (strpos($banner_text, 'sampel obat') !== false || (strpos($banner_text, 'obat') !== false && strpos($banner_text, 'bahan alam') === false && strpos($banner_text, 'tradisional') === false && strpos($banner_text, 'oba') === false)) {
            $scores['obat'] += 80;
        }
        if (strpos($banner_text, 'data contoh bulan') !== false && strpos($banner_text, 'balai besar pom bandar lampung') !== false && strpos($banner_text, 'obat') === false && strpos($banner_text, 'kosmetik') === false) {
            $scores['pangan'] += 60;
        }

        // 3. Deteksi Header & Kolom Karakteristik
        $header_info = self::detect_header_and_data_start($rows);
        $header_raw  = $header_info['header'];
        $header_str  = strtolower(implode(' ', array_filter($header_raw)));

        // Ciri khas OBAT (Eksklusif Kelas Terapi & 31 kolom standard)
        if (strpos($header_str, 'kelas terapi') !== false || strpos($header_str, 'jenis terapi') !== false) {
            $scores['obat'] += 150;
        }
        if (strpos($header_str, 'balai penguji') !== false && strpos($header_str, 'sub kategori') !== false) {
            $scores['obat'] += 70;
        }

        // Ciri khas KOSMETIK (Eksklusif Tipe Produk, Metode Pengambilan, Cluster, Nama Contoh, dsb)
        if (strpos($header_str, 'nama contoh') !== false || strpos($header_str, 'kode contoh') !== false) {
            $scores['kosmetik'] += 120;
        }
        if (strpos($header_str, 'tipe produk') !== false || strpos($header_str, 'cluster') !== false || strpos($header_str, 'kategori kosmetik') !== false) {
            $scores['kosmetik'] += 120;
        }
        if (strpos($header_str, 'metode pengambilan') !== false || strpos($header_str, 'target uji bahan berbahaya') !== false || strpos($header_str, 'nomor notifikasi') !== false) {
            $scores['kosmetik'] += 120;
        }

        // Ciri khas PANGAN (Petugas sampling, Tanggal produksi, Manual SIPT)
        if (strpos($header_str, 'petugas sampling') !== false || strpos($header_str, 'tanggal produksi') !== false) {
            $scores['pangan'] += 150;
        }
        if (strpos($header_str, 'nama sarana') !== false && strpos($header_str, 'alamat') !== false && strpos($header_str, 'sipt') !== false && strpos($header_str, 'manual') !== false) {
            $scores['pangan'] += 80;
        }

        // Ciri khas OTSK & Legacy Herbal / Suplemen
        if (strpos($header_str, 'klaim khasiat') !== false || strpos($header_str, 'skrining bko') !== false || strpos($header_str, 'simplisia utama') !== false) {
            $scores['herbal'] += 150;
        }
        if (strpos($header_str, 'klaim suplemen') !== false) {
            $scores['suplemen'] += 150;
        }
        if (strpos($header_str, 'nama barang') !== false || strpos($header_str, 'komposisi zat berkhasiat') !== false || strpos($header_str, 'nama industri') !== false) {
            $scores['herbal'] += 60;
            $scores['suplemen'] += 60;
        }

        // 4. Periksa Sampel Data (NIE Prefix & Nomor Surtug / Kode)
        $first_data = $header_info['first_data_idx'];
        $data_text = '';
        for ($i = $first_data; $i < min($first_data + 10, count($rows)); $i++) {
            if (!empty($rows[$i]) && is_array($rows[$i]) && !empty(array_filter($rows[$i]))) {
                $data_text .= ' ' . implode(' ', array_filter($rows[$i]));
            }
        }

        // NIE Kosmetik: NA18..., NA11..., NA22...
        if (preg_match('/\bNA\d{11}\b/i', $data_text) || preg_match('/\bNA\d{9,12}\b/i', $data_text)) {
            $scores['kosmetik'] += 120;
        }
        // NIE Pangan: MD, ML, PIRT
        if (preg_match('/\b(MD|ML)\s*\d{10,15}\b/i', $data_text) || preg_match('/\bPIRT\b/i', $data_text)) {
            $scores['pangan'] += 120;
        }
        // NIE & Kode Herbal: POM TR, POM TI, POM QD, POM QL, /OT/
        if (preg_match('/\bPOM\s*T[RI]\b/i', $data_text) || strpos($data_text, '/OT/') !== false) {
            $scores['herbal'] += 150;
        }
        // NIE & Kode Suplemen: POM SD, POM SI, POM SL, /SK/
        if (preg_match('/\bPOM\s*S[DI]\b/i', $data_text) || strpos($data_text, '/SK/') !== false) {
            $scores['suplemen'] += 150;
        }
        // NIE Obat: DKL, DBL, GKL, etc.
        if (preg_match('/\b[DGB][KL][LIPC]\d{9,12}\b/i', $data_text)) {
            $scores['obat'] += 120;
        }

        arsort($scores);
        $top_commodity = key($scores);
        $top_score     = current($scores);

        return array(
            'detected' => ($top_score >= 50) ? $top_commodity : 'unknown',
            'score'    => $top_score,
            'scores'   => $scores
        );
    }

    /**
     * Memvalidasi apakah baris Excel sesuai dengan target komoditi yang dipilih
     * 
     * @param array $rows
     * @param string $target_komoditi
     * @param string $sheet_name
     * @return array
     */
    public static function validate_commodity_format($rows, $target_komoditi, $sheet_name = '')
    {
        $detection = self::detect_commodity_format($rows, $sheet_name);
        $detected  = $detection['detected'];

        $target_label   = self::get_commodity_label($target_komoditi);
        $detected_label = self::get_commodity_label($detected);

        if ($detected !== $target_komoditi) {
            if ($detected === 'unknown') {
                $pesan = "Format file Excel tidak dikenali untuk komoditi [{$target_label}]. Pastikan kolom dan struktur file sesuai dengan template resmi BBPOM.";
            } else {
                $pesan = "Format file Excel tidak sesuai dengan komoditi [{$target_label}]! File yang diunggah terdeteksi sebagai format [{$detected_label}]. Silakan gunakan template resmi untuk komoditi {$target_label}.";
            }

            return array(
                'valid'             => FALSE,
                'target_komoditi'   => $target_komoditi,
                'detected_komoditi' => $detected,
                'target_label'      => $target_label,
                'detected_label'    => $detected_label,
                'pesan'             => $pesan
            );
        }

        return array(
            'valid'             => TRUE,
            'target_komoditi'   => $target_komoditi,
            'detected_komoditi' => $detected,
            'target_label'      => $target_label,
            'detected_label'    => $detected_label,
            'pesan'             => 'Format file sesuai dengan komoditi ' . $target_label . '.'
        );
    }
}
