<?php
define('ENVIRONMENT', 'development');
define('BASEPATH', 'dummy');
require_once __DIR__ . '/../application/config/database.php';
require_once __DIR__ . '/../application/libraries/SimpleXLSX.php';
require_once __DIR__ . '/../application/libraries/SimpleXLSXGen.php';
require_once __DIR__ . '/../application/libraries/Excel_Importer.php';

echo "============================================================\n";
echo "VERIFIKASI SISTEM 1: IMPORT DARI FILE ASLI BBPOM KOSMETIK\n";
echo "============================================================\n";

$excelPath = 'C:\\Users\\ASUS\\OneDrive\\Dokumen\\Magang BPOM\\Data BPOM\\Format Data Sampel\\Sampel Kosmetik\\10. Data Contoh Kosmetik Oktober 2026 - 2.xlsx';
$xlsx = SimpleXLSX::parse($excelPath);
if (!$xlsx) {
    echo "Gagal membuka file Excel asli: " . SimpleXLSX::parseError() . "\n";
    exit(1);
}

$rows = $xlsx->rows(0);
echo "Total baris di file asli: " . count($rows) . "\n";

$header_info = Excel_Importer::detect_header_and_data_start($rows);
echo "Detected main_header_idx : " . $header_info['main_header_idx'] . "\n";
echo "Detected last_header_idx : " . $header_info['last_header_idx'] . "\n";
echo "Detected first_data_idx  : " . $header_info['first_data_idx'] . "\n";
echo "Detected Surtug          : " . ($header_info['surtug'] ?? 'none') . "\n";

$mapping = Excel_Importer::map_headers($header_info['header']);
echo "Status Mapping           : " . ($mapping['status'] ? 'SUCCESS' : 'FAILED') . "\n";
echo "Total Kolom Terpetakan   : " . $mapping['total_mapped'] . "\n";
echo "Kolom Tak Dikenali       : " . count($mapping['unrecognized']) . "\n";

if (!$mapping['status']) {
    echo "ERROR: " . ($mapping['pesan'] ?? '') . "\n";
    exit(1);
}

$valid_rows = 0;
$field_map = $mapping['field_map'];
for ($i = $header_info['first_data_idx']; $i < count($rows); $i++) {
    $row = $rows[$i];
    if (empty(array_filter($row))) continue;

    $row_data = array(
        'komoditi' => 'kosmetik',
        'status'   => 'Menunggu Pengujian'
    );
    $spesifik_row = array();

    foreach ($field_map as $cIdx => $dbField) {
        $val = isset($row[$cIdx]) ? trim($row[$cIdx]) : NULL;
        if (strpos($dbField, 'spesifik:') === 0) {
            $key = substr($dbField, 9);
            $spesifik_row[$key] = ($val !== '') ? $val : NULL;
        } else {
            $row_data[$dbField] = ($val !== '') ? $val : NULL;
        }
    }

    if (empty($row_data['surtug']) && !empty($header_info['surtug'])) {
        $row_data['surtug'] = $header_info['surtug'];
    }

    if (!empty(array_filter($spesifik_row))) {
        $row_data['atribut_spesifik'] = json_encode($spesifik_row, JSON_UNESCAPED_UNICODE);
    }

    if (Excel_Importer::is_footer_or_non_data_row($row, $row_data)) {
        continue;
    }

    if (empty($row_data['nama_sampel'])) {
        echo "Error: baris $i nama_sampel kosong!\n";
        continue;
    }

    $valid_rows++;
}

echo "Total Baris Data Sampel Riil Tervalidasi: $valid_rows data sampel!\n\n";

echo "============================================================\n";
echo "VERIFIKASI SISTEM 2: GENERATE TEMPLATE KOSMETIK & PARSE BACK\n";
echo "============================================================\n";

// Generate template data matching Sampel::download_template('kosmetik')
$t_rows = array();
$t_rows[] = array('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');
$t_rows[] = array('', 'Lampiran Sampling Produk Kosmetik', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');
$t_rows[] = array('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');
$t_rows[] = array('', 'Bulan', ': ' . date('F'), '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');
$t_rows[] = array('', 'No. Surat Tugas', ': PW.03.12.18B.10.26.1018, PW.03.12.18B.10.26.1020', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');
$t_rows[] = array('', 'Tanggal Surat', ': ' . date('d F Y'), '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');
$t_rows[] = array('', 'No. Kode Contoh', '', 'Nama Contoh', 'NIE', 'Kategori Sampel', 'Tipe Produk-Kategori', 'Metode Pengambilan', 'Tanggal Sampling', 'Komposisi', "Kemasan/\n  Netto", 'Bentuk Sediaan', 'Bets', 'ED', 'Jumlah', '', '', '', '', '', 'Tempat Sampling', 'Kab. / Kota', 'Pabrik/Importir', "Cluster/\n  Jenis Uji", 'Kondisi sampel');
$t_rows[] = array('', '', '', '', '', '', '', '', '', '', '', '', '', '', 'K', 'Mi', 'R', 'P', 'A', 'T', '', '', '', '', '');
$t_rows[] = array('', '1', '26.090.201.12.01.0371', 'KREEN Galactomyces 70 Day Cream', 'NA18190105263', 'Kosmetik beresiko tinggi - c. Sediaan perawatan wajah (krim siang, krim malam, moisturizer)', "01. Krim, emulsi, cair, cairan kental, gel, minyak untuk kulit (wajah, tangan, kaki, dan lain-lain)\n01.09 Krim siang (day cream)", 'Offline', date('d F Y'), 'Galactomyces Ferment Filtrate, Aqua Demineralisata', 'Pot, Dus / 20 g', 'Krim', '1125008 A', date('m Y', strtotime('+2 years')), '1.0', '2.0', '0.0', '1.0', '1.0', '5', "Ririnis Shop\nJl. Pulau Damar No. 203, Bandar Lampung", 'Kota Bandar Lampung', "PT. MULIA KAIROS INDUSTRI", 'C/Compliance', 'Baik');
$t_rows[] = array('', '2', '26.090.201.12.01.0372', 'THE FACE Advanced Night Cream', 'NA18250112228', 'Kosmetik beresiko tinggi - c. Sediaan perawatan wajah (krim siang, krim malam, moisturizer)', "01. Krim, emulsi, cair, cairan kental, gel, minyak untuk kulit (wajah, tangan, kaki, dan lain-lain)/\n01.07 Krim malam (night cream)", 'Offline', date('d F Y'), 'Aqua, Niacinamide, Glycerin', 'Pot, Dus / 30 g', 'Krim', '31461A', date('m Y', strtotime('+3 years')), '1.0', '2.0', '0.0', '1.0', '1.0', '5', "Ririnis Shop\nJl. Pulau Damar No. 203, Bandar Lampung", 'Kota Bandar Lampung', "PT. Cressindo Kusuma", 'C/Compliance', 'Baik');
$t_rows[] = array('', '', 'T = Total', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');
$t_rows[] = array('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');
$t_rows[] = array('', '', 'Penerima Sampel', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'Bandar Lampung, ' . date('d F Y'), '', '', '', '', '');
$t_rows[] = array('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'Petugas sampling', '', '', '', '', '');

$out_file = __DIR__ . '/test_kosmetik_template_out.xlsx';
$gen = SimpleXLSXGen::fromArray($t_rows, 'Sampel Kosmetik');
file_put_contents($out_file, (string)$gen);
echo "File template berhasil digenerate: $out_file\n";

// Parse back
$xlsx_out = SimpleXLSX::parse($out_file);
$p_rows = $xlsx_out->rows(0);
$p_header_info = Excel_Importer::detect_header_and_data_start($p_rows);
$p_mapping = Excel_Importer::map_headers($p_header_info['header']);

echo "Parse Back Status : " . ($p_mapping['status'] ? 'SUCCESS' : 'FAILED') . "\n";
echo "Total Mapped      : " . $p_mapping['total_mapped'] . "\n";

$p_count = 0;
for ($j = $p_header_info['first_data_idx']; $j < count($p_rows); $j++) {
    $r = $p_rows[$j];
    $r_data = ['nama_sampel' => $r[3] ?? ''];
    if (!Excel_Importer::is_footer_or_non_data_row($r, $r_data) && !empty($r_data['nama_sampel'])) {
        $p_count++;
    }
}
echo "Sample rows in generated template: $p_count (Expected: 2)\n";

if ($valid_rows === 43 && $p_mapping['status'] && $p_count === 2) {
    echo "\n>>> SELURUH UJI VALIDASI 100% SUKSES DAN IDENTIK! <<<\n";
} else {
    echo "\n>>> TERDAPAT KENDALA PADA UJI VALIDASI! <<<\n";
}
