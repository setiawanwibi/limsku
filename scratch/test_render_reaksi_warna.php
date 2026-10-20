<?php
define('BASEPATH', 'dummy');
define('FCPATH', dirname(__DIR__) . '/');
function html_escape($v) { return htmlspecialchars(isset($v) ? $v : '', ENT_QUOTES, 'UTF-8'); }
function base_url($v = '') { return 'http://localhost/limsku/' . $v; }

$sesi = array(
    'id' => 12,
    'status' => 'Approved / Final',
    'sample_id' => 14,
    'nama_sampel' => 'Paracetamol Tablet 500 mg',
    'nama_penguji' => 'Penguji Kimiawi',
    'nama_verifier' => 'Penyelia Kimiawi',
    'waktu_mulai' => '2026-10-15',
    'waktu_selesai' => '2026-10-16',
    'waktu_verifikasi' => '2026-10-17'
);

$sampel = array(
    'id' => 14,
    'nama_sampel' => 'Paracetamol Tablet 500 mg',
    'kode_sampel_manual' => 'SMP-KIM-2026-001',
    'no_bets' => 'B261019',
    'tanggal_sampling' => '2026-10-14'
);

$hasil = array(
    'id' => 30,
    'nama_zat_aktif' => 'Paracetamol',
    'hasil_uji' => 'Positif Paracetamol',
    'kesimpulan' => 'Memenuhi Syarat (MS)',
    'pustaka_acuan' => 'Farmakope Indonesia Edisi VI Hal. 1359',
    'persyaratan' => 'Terbentuk warna biru violet atau endapan spesifik',
    'data_hasil' => json_encode(array(
        'analit' => 'Paracetamol',
        'lod' => '0.05 mg/mL',
        'keterangan_foto' => 'foto hasil pengamatan terlampir',
        'pustaka_metode' => 'Farmakope Indonesia Edisi VI',
        'pustaka_persyaratan' => 'Farmakope Indonesia Edisi VI',
        'syarat' => 'Terbentuk warna biru violet yang stabil',
        'pilihan_kesimpulan' => 'Memenuhi Syarat (MS)',
        'tabel_reaksi' => array(
            array('bobot' => '10.2 mg', 'pereaksi' => 'Besi(III) klorida LP', 'pengamatan' => 'Terbentuk warna biru violet', 'hasil' => 'Positif'),
            array('bobot' => '10.5 mg', 'pereaksi' => 'Besi(III) klorida LP', 'pengamatan' => 'Terbentuk warna biru violet', 'hasil' => 'Positif'),
            array('bobot' => '10.4 mg', 'pereaksi' => 'Besi(III) klorida LP', 'pengamatan' => 'Terbentuk warna biru violet', 'hasil' => 'Positif'),
            array('bobot' => '10.0 mg', 'pereaksi' => 'Besi(III) klorida LP', 'pengamatan' => 'Terbentuk warna biru violet pekat', 'hasil' => 'Positif'),
            array('bobot' => '5.0 mg', 'pereaksi' => 'Besi(III) klorida LP', 'pengamatan' => 'Terbentuk warna biru violet pekat', 'hasil' => 'Positif')
        )
    ))
);

$penguji = array('nama_lengkap' => 'Penguji Kimia Laboratorium');
$verifier = array('nama_lengkap' => 'Penyelia Kimia Laboratorium');
$signature_penguji = array('signature_file' => 'test_sig.png');
$signature_verifier = array('signature_file' => 'test_sig.png');
$metode = array('nama_zat_aktif' => 'Paracetamol', 'pustaka_acuan' => 'Farmakope Indonesia Edisi VI', 'persyaratan' => 'Positif');

ob_start();
include dirname(__DIR__) . '/application/views/pengujian/cetak_lcp_reaksi_warna.php';
$html = ob_get_clean();

echo "Rendered length: " . strlen($html) . " bytes\n";
file_put_contents(dirname(__DIR__) . '/scratch/test_render_reaksi_warna.html', $html);
echo "SUCCESS: Saved to scratch/test_render_reaksi_warna.html\n";
