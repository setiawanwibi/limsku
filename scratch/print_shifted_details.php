<?php
$data = json_decode(file_get_contents(__DIR__ . '/reconciliation_data.json'), true);
echo "Total Shifted: " . count($data['shifted_records']) . "\n";
foreach ($data['shifted_records'] as $s) {
    echo sprintf(
        "ID %-3d [%-10s] OLD_NAMA: %-30s | NEW_NAMA: %-30s | OLD_JENIS: %-12s | NEW_JENIS: %-12s | OLD_EXP: %-10s | NEW_EXP: %-10s\n",
        $s['id'],
        $s['kode'],
        substr($s['old']['nama_barang'], 0, 30),
        substr($s['new']['nama_barang'], 0, 30),
        $s['old']['jenis_barang'],
        $s['new']['jenis_barang'],
        $s['old']['expired_date'] ?? 'NULL',
        $s['new']['expired_date'] ?? 'NULL'
    );
}
