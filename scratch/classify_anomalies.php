<?php
// scratch/classify_anomalies.php
$data = json_decode(file_get_contents(__DIR__ . '/reconciliation_data.json'), true);

$shifted_only = [];
$zero_date_only = [];

foreach ($data['shifted_records'] as $s) {
    // Cek apakah benar-benar ada pergeseran kolom (bukan hanya '0000-00-00' vs NULL)
    $name_diff = ($s['old']['nama_barang'] !== $s['new']['nama_barang']);
    $kat_diff = ($s['old']['katalog'] !== $s['new']['katalog']);
    $merk_diff = ($s['old']['merk'] !== $s['new']['merk']);
    $stok_diff = ($s['old']['stok'] !== $s['new']['stok']);
    $jenis_diff = ($s['old']['jenis_barang'] !== $s['new']['jenis_barang']);

    if ($name_diff || $kat_diff || $merk_diff || $stok_diff || $jenis_diff) {
        $shifted_only[] = $s;
    }
}

echo "Jumlah Record Pergeseran Kolom Nyata: " . count($shifted_only) . "\n";
foreach ($shifted_only as $idx => $s) {
    echo ($idx + 1) . ". ID {$s['id']} [{$s['kode']}] File: {$s['src_file']}:{$s['src_row']}\n";
    echo "    OLD: nama='{$s['old']['nama_barang']}' | kat='{$s['old']['katalog']}' | merk='{$s['old']['merk']}' | stok='{$s['old']['stok']}' | jenis='{$s['old']['jenis_barang']}' | exp='{$s['old']['expired_date']}'\n";
    echo "    NEW: nama='{$s['new']['nama_barang']}' | kat='{$s['new']['katalog']}' | merk='{$s['new']['merk']}' | stok='{$s['new']['stok']}' | jenis='{$s['new']['jenis_barang']}' | exp='" . ($s['new']['expired_date'] ?? 'NULL') . "'\n";
}
