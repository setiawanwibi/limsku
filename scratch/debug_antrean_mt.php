<?php
require_once 'index.php';
$CI =& get_instance();
$CI->load->model('Model_Pengujian');

echo "=== SAMPLES WITH SESSIONS IN 'Menunggu Approval' ===" . PHP_EOL;
$sessions = $CI->db->select('ts.*, s.nama_sampel, s.kode_sampel_manual, s.jumlah_kimia, s.jumlah_mikro, s.status as status_sampel')
    ->from('testing_sessions ts')
    ->join('samples s', 's.id = ts.sample_id')
    ->where('ts.status', 'Menunggu Approval')
    ->get()->result_array();

echo "Found " . count($sessions) . " sessions with status Menunggu Approval" . PHP_EOL;
$sample_checked = [];
foreach ($sessions as $s) {
    echo "Session ID: {$s['id']} | Sample ID: {$s['sample_id']} | Jenis: {$s['jenis_pengujian']} | Status Sesi: {$s['status']}" . PHP_EOL;
    echo "  Sampel: {$s['nama_sampel']} ({$s['kode_sampel_manual']}) | Status: {$s['status_sampel']} | Jml Kimia: '{$s['jumlah_kimia']}' | Jml Mikro: '{$s['jumlah_mikro']}'" . PHP_EOL;

    if (!isset($sample_checked[$s['sample_id']])) {
        $sample_checked[$s['sample_id']] = true;
        $eval = $CI->Model_Pengujian->cek_kelengkapan_sesi_sampel($s['sample_id']);
        echo "  -> EVALUASI KELENGKAPAN: siap_mt=" . ($eval['siap_mt'] ? 'TRUE' : 'FALSE') . " | Alasan: {$eval['alasan']}" . PHP_EOL;
        echo "     Detail kebutuhan: " . json_encode($eval['detail_kebutuhan']) . PHP_EOL;
        echo "     Status sesi: " . json_encode($eval['status_sesi']) . PHP_EOL;
    }
}

echo PHP_EOL . "=== SAMPLES WITH STATUS 'Menunggu Approval' IN samples TABLE ===" . PHP_EOL;
$samples = $CI->db->get_where('samples', ['status' => 'Menunggu Approval'])->result_array();
foreach ($samples as $sm) {
    echo "Sample ID: {$sm['id']} | Nama: {$sm['nama_sampel']} | Jml Kimia: '{$sm['jumlah_kimia']}' | Jml Mikro: '{$sm['jumlah_mikro']}'" . PHP_EOL;
    $eval = $CI->Model_Pengujian->cek_kelengkapan_sesi_sampel($sm['id']);
    echo "  -> EVALUASI: siap_mt=" . ($eval['siap_mt'] ? 'TRUE' : 'FALSE') . " | Alasan: {$eval['alasan']}" . PHP_EOL;
}
