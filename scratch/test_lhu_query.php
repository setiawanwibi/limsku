<?php
require_once 'index.php';
$ci =& get_instance();
$ci->load->model('Model_Pengujian');
$ci->load->model('Model_Sampel');

echo "=== TEST SAMPLE SESSIONS QUERY ===" . PHP_EOL;

foreach ([4, 14, 15] as $sample_id) {
    $sampel = $ci->Model_Sampel->ambil_by_id($sample_id);
    if (!$sampel) continue;

    echo "SAMPLE $sample_id: {$sampel['nama_sampel']}" . PHP_EOL;
    $ci->db->select('ts.*, u.nama_lengkap as nama_penguji, uv.nama_lengkap as nama_verifier, ua.nama_lengkap as nama_approver');
    $ci->db->from('testing_sessions ts');
    $ci->db->join('users u', 'u.id = ts.penguji_id', 'left');
    $ci->db->join('users uv', 'uv.id = ts.verifier_id', 'left');
    $ci->db->join('users ua', 'ua.id = ts.approver_id', 'left');
    $ci->db->where('ts.sample_id', $sample_id);
    $ci->db->order_by('ts.id', 'ASC');
    $sesi_rows = $ci->db->get()->result_array();

    foreach ($sesi_rows as $s) {
        echo "  Sesi {$s['id']} [{$s['jenis_pengujian']}] Status: {$s['status']}" . PHP_EOL;
        $forms = $ci->Model_Pengujian->ambil_hasil_by_session($s['id']);
        foreach ($forms as $f) {
            echo "    -> Form {$f['id']}: {$f['parameter_uji']} | Pustaka: {$f['pustaka_acuan']} | Metode: {$f['nama_metode']} | Kesimpulan: {$f['kesimpulan']}" . PHP_EOL;
        }
    }
}
