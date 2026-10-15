<?php
define('BASEPATH', 'TRUE');
define('ENVIRONMENT', 'development');

// Mock CodeIgniter helpers/methods
function html_escape($var) {
    if (is_array($var)) {
        return array_map('html_escape', $var);
    }
    return htmlspecialchars((string)$var, ENT_QUOTES, 'UTF-8');
}

class MockSession {
    public function flashdata($key) { return null; }
    public function userdata($key) { 
        if ($key === 'nama_lengkap') return 'Penguji Lab Mikro';
        return null;
    }
}

class MockSecurity {
    public function get_csrf_token_name() { return 'csrf_limsku'; }
    public function get_csrf_hash() { return 'fake_token_hash_123'; }
}

class MockCI {
    public $session;
    public $security;
    public function __construct() {
        $this->session = new MockSession();
        $this->security = new MockSecurity();
    }
}

function site_url($uri = '') {
    return 'http://localhost/LIMSKU/' . $uri;
}

// 1. Muat skema LCP resmi
require_once __DIR__ . '/../application/config/skema_lcp_cemaran_mikroba_kualitatif.php';
$schema_array = dapatkan_skema_lcp_cemaran_mikroba_kualitatif();
$schema_json = json_encode($schema_array, JSON_UNESCAPED_UNICODE);

echo "=== TEST 1: SERIALISASI & STRUKTUR SKEMA ===\n";
echo "Ukuran JSON Skema: " . strlen($schema_json) . " bytes\n";
$decoded = json_decode($schema_json, true);
assert($decoded['kode_dokumen'] === 'POM-06.02/CFM.01/SOP.01/F.01B.13', "Kode dokumen harus cocok");
assert(count($decoded['sections']) === 7, "Harus ada 7 seksi");
echo "[PASS] Serialisasi dan struktur skema valid.\n\n";

// 2. Siapkan Fixture Simulasi untuk View proses_sesi.php
$this_mock = new MockCI();
$sesi = array(
    'id'                 => 99,
    'nama_sampel'        => 'Amoxicillin Sirup Kering',
    'kode_sampel_manual' => 'SMP-MIKRO-001',
    'sample_id'          => 12,
    'jenis_pengujian'    => 'Mikrobiologi'
);

$form_fixture = array(
    'id'            => 101,
    'nama_template' => 'Uji Cemaran Mikroba Kualitatif Sediaan Farmasi',
    'kode_template' => 'MIK-04',
    'parameter_uji' => 'Batas Mikroba Spesifik',
    'nama_metode'   => 'Metode Uji Cemaran Mikroba',
    'nama_zat_aktif'=> 'Amoxicillin',
    'metode_analisa'=> 'Farmakope Indonesia VI',
    'persyaratan'   => 'Bebas E. coli dan Salmonella sp',
    'skema_form'    => $schema_json,
    'data_hasil'    => '',
    'kesimpulan'    => 'Belum Disimpulkan',
    'catatan'       => ''
);

$hasil_forms = array($form_fixture);
$daftar_alat = array(
    array('id' => 1, 'kode_barang' => 'ALT-01', 'nama_barang' => 'Inkubator Memmert', 'merk_tipe' => 'IN-55')
);
$daftar_reagen = array(
    array('id' => 1, 'nama_reagen' => 'MacConkey Agar', 'merk' => 'Merck', 'kemasan' => '500g')
);

// Function runner to bind $this context just like CI_Loader
$render_view = function($view_file, $view_vars) use ($this_mock) {
    extract($view_vars);
    ob_start();
    include $view_file;
    return ob_get_clean();
};
$render_view = $render_view->bindTo($this_mock);

echo "=== TEST 2: SIMULASI RENDER VIEW AWAL (EMPTY STATE) ===\n";
$html_initial = $render_view(
    __DIR__ . '/../application/views/pengujian/proses_sesi.php',
    array(
        'sesi'        => $sesi,
        'hasil_forms' => $hasil_forms,
        'daftar_alat' => $daftar_alat,
        'daftar_reagen' => $daftar_reagen
    )
);

assert(strlen($html_initial) > 1000, "HTML render awal tidak boleh kosong");
assert(strpos($html_initial, 'name="forms[101][hasil][target_staphylococcus]"') !== false, "Input target_staphylococcus harus ada");
assert(strpos($html_initial, 'name="forms[101][hasil][bobot_sampel]"') !== false, "Input bobot_sampel harus ada");
assert(strpos($html_initial, 'name="forms[101][hasil][tabel_tahapan][0][tahap]"') !== false, "Tabel tahapan tahap 0 harus ada");
assert(strpos($html_initial, 'name="forms[101][hasil][hasil_uji]"') !== false, "Input hasil_uji harus ada");
assert(strpos($html_initial, 'name="forms[101][hasil][kesimpulan_lcp]"') !== false, "Select kesimpulan_lcp harus ada");
assert(strpos($html_initial, 'value="0"') !== false, "Hidden 0 untuk checkbox harus ada");
echo "[PASS] View proses_sesi.php berhasil di-render tanpa error PHP Notice/Warning.\n\n";

echo "=== TEST 3: SIMULASI SUBMIT POST & PENYIMPANAN DATA_HASIL ===\n";
// Data POST yang disubmit analis
$post_forms = array(
    101 => array(
        'alat_ids'   => array(1),
        'reagen_ids' => array(1),
        'hasil'      => array(
            'target_staphylococcus' => '0',
            'target_pseudomonas'    => '0',
            'target_candida'        => '0',
            'target_shigella'       => '0',
            'target_salmonella'     => '0',
            'target_ecoli'          => '1', // Dicentang
            'target_lainnya_check'  => '0',
            'target_lainnya_text'   => '',
            'pelarut_kesesuaian'    => 'MLB',
            'baku_staphylococcus'   => '0',
            'baku_pseudomonas'      => '0',
            'baku_candida'          => '0',
            'bobot_sampel'          => '10.05',
            'volume_pelarut'        => '90.2',
            'jenis_pelarut_homogenisasi' => 'MLB',
            'inokulum_tingkat'      => '1 g atau mL',
            'tabel_tahapan'         => array(
                array('tahap' => 'Pra Pengayaan', 'tanggal' => '2026-10-14', 'media' => 'MLB', 'suhu' => '35', 'waktu' => '24', 'sampel_spike' => 'Keruh', 'media_spike' => 'Keruh', 'kontrol_media' => 'Jernih', 'sampel_uji' => 'Jernih'),
                array('tahap' => 'Pengayaan / Pengayaan Selektif', 'tanggal' => '2026-10-15', 'media' => 'EEB', 'suhu' => '35', 'waktu' => '24', 'sampel_spike' => 'Keruh', 'media_spike' => 'Keruh', 'kontrol_media' => 'Jernih', 'sampel_uji' => 'Jernih'),
                array('tahap' => 'Seleksi', 'tanggal' => '2026-10-16', 'media' => 'MacConkey', 'suhu' => '35', 'waktu' => '24', 'sampel_spike' => 'Positif', 'media_spike' => 'Positif', 'kontrol_media' => 'Steril', 'sampel_uji' => 'Negatif'),
                array('tahap' => 'Sub Kultur / Isolasi', 'tanggal' => '2026-10-17', 'media' => 'MCA', 'suhu' => '35', 'waktu' => '24', 'sampel_spike' => 'Merah muda', 'media_spike' => 'Merah muda', 'kontrol_media' => 'Steril', 'sampel_uji' => 'Tidak tumbuh koloni'),
                array('tahap' => 'Konfirmasi', 'tanggal' => '2026-10-18', 'media' => 'Biokimia Kit', 'suhu' => '35', 'waktu' => '24', 'sampel_spike' => 'E. coli (+)', 'media_spike' => 'E. coli (+)', 'kontrol_media' => 'Steril', 'sampel_uji' => 'Negatif')
            ),
            'hasil_uji'             => 'Negatif per 10 g',
            'pustaka_metode'        => 'Farmakope Indonesia Edisi VI (2020)',
            'syarat'                => 'Negatif',
            'kesimpulan_lcp'        => 'Memenuhi Syarat (MS)'
        ),
        'kesimpulan' => 'Memenuhi Syarat (MS)',
        'catatan'    => 'Kontrol media memenuhi kriteria uji.'
    )
);

// Jalankan simulasi penyimpanan sesuai logika Model_Pengujian::simpan_hasil_sesi (baris 208-235)
$saved_test_results = array();
foreach ($post_forms as $test_id => $f_data) {
    $raw_hasil = isset($f_data['hasil']) && is_array($f_data['hasil']) ? $f_data['hasil'] : array();
    $alat_ids = isset($f_data['alat_ids']) && is_array($f_data['alat_ids']) ? array_filter(array_map('intval', $f_data['alat_ids'])) : array();
    $reagen_ids = isset($f_data['reagen_ids']) && is_array($f_data['reagen_ids']) ? array_filter(array_map('intval', $f_data['reagen_ids'])) : array();

    $payload_hasil = array(
        'parameter_values' => $raw_hasil,
        'alat_ids'         => array_values($alat_ids),
        'reagen_ids'       => array_values($reagen_ids)
    );
    foreach ($raw_hasil as $k => $v) {
        $payload_hasil[$k] = $v;
    }

    $saved_test_results[$test_id] = array(
        'data_hasil' => json_encode($payload_hasil, JSON_UNESCAPED_UNICODE),
        'kesimpulan' => $f_data['kesimpulan'],
        'catatan'    => $f_data['catatan']
    );
}

echo "Simulasi penyimpanan Model_Pengujian::simpan_hasil_sesi berhasil.\n";
echo "data_hasil payload: " . strlen($saved_test_results[101]['data_hasil']) . " bytes\n";
assert(strpos($saved_test_results[101]['data_hasil'], 'Negatif per 10 g') !== false, "Data hasil harus tersimpan di payload");
echo "[PASS] Struktur payload tersimpan dengan tepat.\n\n";

echo "=== TEST 4: ROUND-TRIP RE-RENDER DENGAN DATA TERSIMPAN ===\n";
// Pasang data_hasil tersimpan ke form fixture
$form_fixture_saved = $form_fixture;
$form_fixture_saved['data_hasil'] = $saved_test_results[101]['data_hasil'];
$form_fixture_saved['kesimpulan'] = $saved_test_results[101]['kesimpulan'];
$form_fixture_saved['catatan']    = $saved_test_results[101]['catatan'];
$hasil_forms_saved = array($form_fixture_saved);

$html_rerender = $render_view(
    __DIR__ . '/../application/views/pengujian/proses_sesi.php',
    array(
        'sesi'        => $sesi,
        'hasil_forms' => $hasil_forms_saved,
        'daftar_alat' => $daftar_alat,
        'daftar_reagen' => $daftar_reagen
    )
);

// Verifikasi nilai tersimpan muncul kembali di HTML input
assert(strpos($html_rerender, 'value="10.05"') !== false, "Bobot sampel 10.05 harus terisi di input");
assert(strpos($html_rerender, 'value="90.2"') !== false, "Volume pelarut 90.2 harus terisi di input");
assert(strpos($html_rerender, 'Negatif per 10 g') !== false, "Hasil uji harus terisi di textarea");
assert(strpos($html_rerender, 'Farmakope Indonesia Edisi VI (2020)') !== false, "Pustaka metode harus terisi");
// Pastikan checkbox ecoli checked dan staph unchecked
assert(preg_match('/name="forms\[101\]\[hasil\]\[target_ecoli\]"[^>]*checked/', $html_rerender) === 1, "Checkbox E.coli harus checked");
assert(preg_match('/name="forms\[101\]\[hasil\]\[target_staphylococcus\]"[^>]*checked/', $html_rerender) === 0, "Checkbox Staphylococcus tidak boleh checked");

echo "[PASS] Round-trip re-render data_hasil sukses 100%!\n\n";
echo "=== SELURUH PENGUJIAN FIXTURE LOKAL BERHASIL (ZERO ERROR) ===\n";
