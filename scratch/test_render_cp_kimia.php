<?php
define('BASEPATH', 'dummy');
define('FCPATH', dirname(__DIR__) . '/');
function html_escape($v) { return htmlspecialchars(isset($v) ? $v : '', ENT_QUOTES, 'UTF-8'); }
function base_url($v = '') { return 'http://localhost/limsku/' . $v; }

$sesi = array(
    'id' => 10,
    'status' => 'Approved / Final',
    'sample_id' => 15,
    'nama_sampel' => 'Albendazol Tablet 400 mg',
    'nama_penguji' => 'M Daffa Hadistya',
    'nama_verifier' => 'Penyelia Kimia',
    'waktu_mulai' => '2026-03-01',
    'waktu_selesai' => '2026-03-03',
    'waktu_verifikasi' => '2026-03-04',
    'catatan' => ''
);

$sampel = array(
    'id' => 15,
    'nama_sampel' => 'Albendazol Tablet 400 mg',
    'kode_sampel_manual' => '015/O-T/JMB/2026',
    'no_sipt' => '26.088.101.01.0014',
    'balai_penguji' => 'BPOM Jambi',
    'kategori_sampel' => 'Obat',
    'bentuk_sediaan' => "Tablet; Breakline di satu sisi\ndan emboss \"H\" di sisi lainnya",
    'pemerian_warna' => 'Putih',
    'pemerian_bau' => 'Normal',
    'pemerian_rasa' => '-'
);

$forms = array(
    array(
        'id' => 101,
        'parameter_uji' => 'Identifikasi',
        'nama_template' => 'Identifikasi Kimia',
        'nama_zat_aktif' => 'Albendazol',
        'metode_analisa' => 'HPLC',
        'hasil_uji' => 'Positif',
        'persyaratan' => 'Positif',
        'pustaka_acuan' => 'Suplemen I FI 6 hal. 34',
        'kesimpulan' => 'Memenuhi Syarat (MS)',
        'data_hasil' => ''
    ),
    array(
        'id' => 102,
        'parameter_uji' => 'Penetapan Kadar',
        'nama_template' => 'Penetapan Kadar Kimia',
        'nama_zat_aktif' => 'Albendazol',
        'metode_analisa' => 'HPLC',
        'hasil_uji' => "Kadar rata-rata :\n103,50 %\n413,99 mg/tablet",
        'persyaratan' => '93,0 % - 107,0 %',
        'pustaka_acuan' => 'Suplemen I FI 6 hal. 34',
        'kesimpulan' => 'Memenuhi Syarat (MS)',
        'data_hasil' => ''
    ),
    array(
        'id' => 103,
        'parameter_uji' => 'KB',
        'nama_template' => 'Keragaman Bobot',
        'nama_zat_aktif' => 'Albendazol',
        'metode_analisa' => 'HPLC',
        'hasil_uji' => "L1 = 5,4\nL2 = 98,50 - 105,49 %",
        'persyaratan' => "L1 = AV <= 15\nL2 = 76,13 - 126,88 %",
        'pustaka_acuan' => 'Suplemen FI VI',
        'kesimpulan' => 'Memenuhi Syarat (MS)',
        'data_hasil' => ''
    ),
    array(
        'id' => 104,
        'parameter_uji' => 'Disolusi',
        'nama_template' => 'Disolusi',
        'nama_zat_aktif' => '',
        'metode_analisa' => 'Spektrofotometri',
        'hasil_uji' => '92,8 %',
        'persyaratan' => 'Q+5 (Q = 80%)',
        'pustaka_acuan' => 'Suplemen I FI 6 hal. 34',
        'kesimpulan' => 'Memenuhi Syarat (MS)',
        'data_hasil' => ''
    )
);

$penguji = array('nama_lengkap' => 'M Daffa Hadistya');
$verifier = array('nama_lengkap' => 'Penyelia / Manajer Teknis');
$signature_penguji = array('signature_file' => 'test_sig.png');
$signature_verifier = array('signature_file' => 'test_sig.png');

ob_start();
include dirname(__DIR__) . '/application/views/pengujian/cetak_cp_kimia.php';
$html = ob_get_clean();

echo "Rendered length: " . strlen($html) . " bytes\n";
file_put_contents(dirname(__DIR__) . '/scratch/test_render_cp_kimia.html', $html);
echo "SUCCESS: Saved to scratch/test_render_cp_kimia.html\n";
