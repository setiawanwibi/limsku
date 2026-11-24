<?php
define('ENVIRONMENT', 'development');
define('BASEPATH', 'dummy');

require_once __DIR__ . '/../application/libraries/SimpleXLSX.php';
require_once __DIR__ . '/../application/libraries/SimpleXLSXGen.php';
require_once __DIR__ . '/../application/libraries/Excel_Importer.php';

echo "=== TEST 1: GENERATE & VERIFY PANGAN TEMPLATE ===\n";
$pangan_template_rows = array();
$pangan_template_rows[] = array('', 'Data Contoh Bulan ' . date('F Y'), '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');
$pangan_template_rows[] = array('', '', '', '', '', '', '', '', '', '', 'BALAI BESAR POM BANDAR LAMPUNG', '', '', '', '', '', '', '', '', '', '', '');
$pangan_template_rows[] = array('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');
$pangan_template_rows[] = array('Petugas Sampling', 'No.', 'No', 'No Sampel', '', 'Tempat dan Tanggal Sampling', '', 'Tanggal Sampling', 'Kategori Sampel', 'Nama Sampel', 'NIE', 'Pabrik/ Importir', 'No Bets', 'Tanggal produksi', 'Komposisi', 'Tgl Kadaluarsa', 'Kemasan', 'Jumlah', '', '', '', '');
$pangan_template_rows[] = array('', '', '', 'Manual', 'SIPT', 'Nama Sarana', 'alamat', '', '', '', '', '', '', '', '', '', '', 'Ki', 'Mi', 'Arsip', 'P', 'Total');
$pangan_template_rows[] = array('ELFINA', '1', '78', 'P/BL/IV/26', '26.090.301.13.01.0078', 'Pasar Tugu', 'JL.Hayam Wuruk Bandar Lampung', date('Y-m-d'), '11.1.5 Gula kristal putih atau gula pasir, gula batu', 'Gulaku', 'MD 120867000100167', 'PT.Gula Putih Mataram Lampung Indonesia', '2A 23;07', date('Y-m-d', strtotime('-3 months')), 'gula tebu', date('Y-m-d', strtotime('+1 year')), 'Plastik 1 kg', '3', '0', '2', '1', '6');
$pangan_template_rows[] = array('Asih', '2', '79', 'P/BL/IV/26', '26.090.301.13.01.0079', 'Aneka Sari Rasa', 'Jl. Ikan Kakap No.26 - 28, Pesawahan, Bandar Lampung', date('Y-m-d'), '15.3 HASIL OLAHAN PERIKANAN (makanan ringan berbasis ikan)', 'Kemplang Panggang Asli', 'PIRT 2021871040881 30', 'Ango Bandar Lampung', 'KP-0921', date('Y-m-d', strtotime('-1 month')), 'sagu, ikan, telur, garam, penyedap rasa', date('Y-m-d', strtotime('+6 months')), 'Plastik isi 10 pc', '3', '5', '2', '1', '11');

$tempFile = __DIR__ . '/test_pangan_template_out.xlsx';
$gen = SimpleXLSXGen::fromArray($pangan_template_rows, 'Pangan');
file_put_contents($tempFile, (string)$gen);
echo "Template saved to: $tempFile\n";

$read_xlsx = SimpleXLSX::parse($tempFile);
$parsed_rows = $read_xlsx->rows();
$header_info = Excel_Importer::detect_header_and_data_start($parsed_rows);
$mapping = Excel_Importer::map_headers($header_info['header']);
echo "Template detected header row: {$header_info['main_header_idx']}, data start: {$header_info['first_data_idx']}\n";
echo "Total mapped: {$mapping['total_mapped']} columns\n";
echo "Mapping status: " . ($mapping['status'] ? 'SUCCESS' : 'FAILED') . "\n";
echo "Sample row 1 parsed:\n";
$first_data = $header_info['first_data_idx'];
$sample_data = array();
foreach ($mapping['field_map'] as $col_idx => $db_field) {
    $val = $parsed_rows[$first_data][$col_idx] ?? '';
    if (($db_field === 'tanggal_sampling' || $db_field === 'kedaluwarsa' || $db_field === 'spesifik:tanggal_produksi') && $val !== '') {
        $val = Excel_Importer::convert_excel_date($val);
    }
    $sample_data[$db_field] = $val;
}
echo json_encode($sample_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";

echo "=== TEST 2: SIMULATE IMPORT OF REAL BBPOM PANGAN FILE ===\n";
$real_file = 'C:\\Users\\ASUS\\OneDrive\\Dokumen\\Magang BPOM\\Data BPOM\\Format Data Sampel\\Sampel Pangan\\4. Data Contoh April 2026_print.xlsx';
$real_xlsx = SimpleXLSX::parse($real_file);
$real_rows = $real_xlsx->rows(0); // Sheet 0: April 206
$real_header_info = Excel_Importer::detect_header_and_data_start($real_rows);
$real_mapping = Excel_Importer::map_headers($real_header_info['header']);

$imported_count = 0;
$skipped_footers = 0;
$field_map = $real_mapping['field_map'];
$sample_preview = array();

for ($i = $real_header_info['first_data_idx']; $i < count($real_rows); $i++) {
    $row = $real_rows[$i];
    if (empty(array_filter($row))) continue;
    
    $row_data = array('komoditi' => 'pangan', 'status' => 'Menunggu Pengujian');
    $spesifik_row = array();
    
    foreach ($field_map as $col_index => $db_field) {
        $val = isset($row[$col_index]) ? trim($row[$col_index]) : NULL;
        if (($db_field === 'tanggal_sampling' || $db_field === 'kedaluwarsa' || $db_field === 'spesifik:tanggal_produksi') && $val !== NULL) {
            $val = Excel_Importer::convert_excel_date($val);
        }
        if (strpos($db_field, 'spesifik:') === 0) {
            $key = substr($db_field, 9);
            $spesifik_row[$key] = ($val !== '') ? $val : NULL;
        } else {
            $row_data[$db_field] = ($val !== '') ? $val : NULL;
        }
    }
    
    if (Excel_Importer::is_footer_or_non_data_row($row, $row_data)) {
        $skipped_footers++;
        continue;
    }
    
    if (!empty($row_data['nama_sampel'])) {
        $imported_count++;
        if (count($sample_preview) < 3) {
            $row_data['atribut_spesifik'] = $spesifik_row;
            $sample_preview[] = $row_data;
        }
    }
}

echo "Total real sample rows parsed & valid: $imported_count\n";
echo "Total footers skipped safely: $skipped_footers\n";
echo "Preview first 2 imported samples from real file:\n";
echo json_encode(array_slice($sample_preview, 0, 2), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n\n";

echo "=== TEST 3: OBAT REGRESSION CHECK ===\n";
$obat_header = array(
    'No', 'Kategori Sampel', 'Sub Kategori', 'Jenis Kelas Terapi', 'Kategori Sarana', 'Nama Sarana',
    'Kabupaten Kota', 'Tanggal Sampling', 'Kode Sampel Manual', 'No SIPT', 'Nama Sampel',
    'Nomor Izin Edar', 'Kondisi Produk', 'No Bets', 'Kedaluwarsa', 'Kemasan',
    'Nama Alamat Perusahaan', 'Komposisi', 'Jumlah Kimia', 'Jumlah Mikro', 'Jumlah Arsip',
    'Jumlah Penandaan', 'Jumlah Total', 'Penyimpanan', 'Penandaan', 'Harga', 'TIE', 'MK', 'TMK',
    'Surtug', 'Balai Penguji'
);
$obat_mapping = Excel_Importer::map_headers($obat_header);
echo "Obat template mapping: " . ($obat_mapping['status'] && $obat_mapping['total_mapped'] === 31 ? '100% PERFECT (31/31)' : 'ERROR') . "\n";
