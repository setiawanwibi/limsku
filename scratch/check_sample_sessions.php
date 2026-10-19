<?php
require_once 'index.php';
$CI =& get_instance();

echo "=== SESSIONS FOR SAMPLES 14, 15, 16, 17 ===" . PHP_EOL;
$sessions = $CI->db->select('ts.*, s.nama_sampel, s.kode_sampel_manual, s.jumlah_kimia, s.jumlah_mikro')
    ->from('testing_sessions ts')
    ->join('samples s', 's.id = ts.sample_id')
    ->where_in('ts.sample_id', [14, 15, 16, 17])
    ->get()->result_array();

foreach ($sessions as $s) {
    echo "Sample {$s['sample_id']} ({$s['nama_sampel']}) | Sesi {$s['id']} | Jenis: {$s['jenis_pengujian']} | Status: {$s['status']} | Penguji: {$s['penguji_id']} | Verifier: {$s['verifier_id']} | Approver: {$s['approver_id']}" . PHP_EOL;
}
