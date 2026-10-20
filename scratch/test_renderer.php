<?php
define('BASEPATH', 'dummy');
define('FCPATH', dirname(__DIR__) . '/');
function html_escape($v) { return htmlspecialchars(isset($v) ? $v : '', ENT_QUOTES, 'UTF-8'); }
function base_url($v = '') { return 'http://localhost/limsku/' . $v; }

$sesi = array(
    'id' => 11,
    'status' => 'Approved / Final',
    'sample_id' => 14,
    'nama_sampel' => 'Paracetamol Tablet 500mg',
    'nama_penguji' => 'Penguji Mikrobiologi',
    'nama_verifier' => 'Penyelia Mikrobiologi',
    'waktu_mulai' => '2025-05-10',
    'waktu_selesai' => '2025-05-15',
    'waktu_verifikasi' => '2025-05-16'
);

$sampel = array(
    'id' => 14,
    'nama_sampel' => 'Paracetamol Tablet 500mg',
    'kode_sampel_manual' => 'SMP-2025-001',
    'tanggal_sampling' => '2025-05-08'
);

$hasil = array(
    'id' => 20,
    'hasil_uji' => 'Negatif / Tidak Ditemukan Cemaran',
    'kesimpulan' => 'Memenuhi Syarat (MS)',
    'data_hasil' => json_encode(array(
        'target_staphylococcus' => '1',
        'target_ecoli' => '1',
        'pelarut_kesesuaian' => 'TSB',
        'bobot_sampel' => '10.0',
        'volume_pelarut' => '90.0',
        'jenis_pelarut_homogenisasi' => 'TSB',
        'inokulum_tingkat' => '1 g atau mL',
        'hasil_uji' => 'Negatif / 10g',
        'syarat' => 'Negatif / 10g',
        'kesimpulan_lcp' => 'Memenuhi Syarat (MS)',
        'tabel_tahapan' => array(
            array('tanggal' => '2025-05-10', 'sampel_spike' => 'N/A', 'media_spike' => 'N/A', 'kontrol_media' => 'N/A', 'sampel_uji' => 'N/A'),
            array('tanggal' => '2025-05-11', 'sampel_spike' => 'Keruh', 'media_spike' => 'Keruh', 'kontrol_media' => 'Jernih', 'sampel_uji' => 'Jernih'),
            array('tanggal' => '2025-05-12', 'sampel_spike' => 'Keruh', 'media_spike' => 'Keruh', 'kontrol_media' => 'Jernih', 'sampel_uji' => 'Jernih'),
            array('tanggal' => '2025-05-13', 'sampel_spike' => 'Positif', 'media_spike' => 'Positif', 'kontrol_media' => 'Negatif', 'sampel_uji' => 'Negatif'),
            array('tanggal' => '2025-05-14', 'sampel_spike' => 'Positif', 'media_spike' => 'Kit Negatif', 'kontrol_media' => 'N/A', 'sampel_uji' => 'Negatif')
        )
    ))
);

$penguji = array('nama_lengkap' => 'Penguji Mikrobiologi');
$verifier = array('nama_lengkap' => 'Penyelia Mikrobiologi');
$signature_penguji = array('signature_file' => 'test_sig.png');
$signature_verifier = array('signature_file' => 'test_sig.png');
$metode = array('pustaka_acuan' => 'FI VI', 'persyaratan' => 'Negatif / 10g');

ob_start();
include dirname(__DIR__) . '/application/views/pengujian/cetak_lcp_kualitatif.php';
$html = ob_get_clean();
echo 'Length of rendered HTML: ' . strlen($html) . PHP_EOL;
file_put_contents(dirname(__DIR__) . '/scratch/test_render_lcp.html', $html);
echo 'SUCCESS: Saved rendered HTML to scratch/test_render_lcp.html' . PHP_EOL;
