<?php
define('ENVIRONMENT', 'development');
define('BASEPATH', 'dummy');

require_once __DIR__ . '/../application/libraries/Excel_Importer.php';

$obat_header = array(
    'No', 'Kategori Sampel', 'Sub Kategori', 'Jenis Kelas Terapi', 'Kategori Sarana', 'Nama Sarana',
    'Kabupaten Kota', 'Tanggal Sampling', 'Kode Sampel Manual', 'No SIPT', 'Nama Sampel',
    'Nomor Izin Edar', 'Kondisi Produk', 'No Bets', 'Kedaluwarsa', 'Kemasan',
    'Nama Alamat Perusahaan', 'Komposisi', 'Jumlah Kimia', 'Jumlah Mikro', 'Jumlah Arsip',
    'Jumlah Penandaan', 'Jumlah Total', 'Penyimpanan', 'Penandaan', 'Harga', 'TIE', 'MK', 'TMK',
    'Surtug', 'Balai Penguji'
);

$mapping = Excel_Importer::map_headers($obat_header);
echo "=== OBAT HEADER TEST ===\n";
echo "Total columns in template: " . count($obat_header) . "\n";
echo "Total mapped: " . $mapping['total_mapped'] . "\n";
echo "Unrecognized: " . json_encode($mapping['unrecognized']) . "\n";
