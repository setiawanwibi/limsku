<?php
/**
 * Setup isolated test session for Penyelia verification test
 */
define('BASEPATH', 'defined');
define('ENVIRONMENT', 'development');

require_once __DIR__ . '/../application/config/database.php';
$db_config = $db['default'];

$mysqli = new mysqli($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);
if ($mysqli->connect_error) {
    die("Connect Error: " . $mysqli->connect_error . "\n");
}

echo "=== 1. PREPARING TEST DATA FOR PENYELIA ===\n";

// Get template MIK-04
$tpl_res = $mysqli->query("SELECT id, kode_template, nama_template FROM form_templates WHERE kode_template='MIK-04'");
$tpl = $tpl_res->fetch_assoc();
$template_id = (int)$tpl['id'];

// Get penguji
$penguji_res = $mysqli->query("SELECT id, nama_lengkap FROM users WHERE username='penguji'");
$penguji = $penguji_res->fetch_assoc();
$penguji_id = (int)$penguji['id'];

// Use sample 14 (Cetirizine HCl Tablet 10 mg, Menunggu Pengujian)
$sample_id = 14;
$s_res = $mysqli->query("SELECT id, kode_sampel_manual, nama_sampel, status FROM samples WHERE id = {$sample_id}");
$sample = $s_res->fetch_assoc();

// Method for testing
$m_res = $mysqli->query("SELECT id, kode_metode, nama_metode, parameter_uji FROM methods WHERE kategori='Mikrobiologi' LIMIT 1");
$method = $m_res->fetch_assoc();
$method_id = (int)$method['id'];

echo "Sample: ID={$sample['id']}, Kode={$sample['kode_sampel_manual']}, Nama={$sample['nama_sampel']}, Status={$sample['status']}\n";
echo "Template: ID={$template_id}, Kode={$tpl['kode_template']}\n";
echo "Penguji: ID={$penguji_id}, Nama={$penguji['nama_lengkap']}\n";
echo "Method: ID={$method_id}, Parameter={$method['parameter_uji']}\n";

// 2. Create isolated testing_session in 'Menunggu Verifikasi' status
$now = date('Y-m-d H:i:s');
$stmt = $mysqli->prepare("INSERT INTO testing_sessions (sample_id, penguji_id, jenis_pengujian, status, waktu_mulai, waktu_selesai, created_at) VALUES (?, ?, 'Mikrobiologi', 'Menunggu Verifikasi', ?, ?, ?)");
$stmt->bind_param("iisss", $sample_id, $penguji_id, $now, $now, $now);
$stmt->execute();
$session_id = $mysqli->insert_id;
echo "Created Test Session ID: {$session_id} (Status: Menunggu Verifikasi)\n";

// 3. Prepare full realistic LCP MIK-04 payload
$test_payload = [
    'target_ecoli' => '1',
    'target_salmonella' => '1',
    'target_pseudomonas' => '0',
    'target_staphylococcus' => '0',
    'target_candida' => '0',
    'target_lainnya_check' => '0',
    'target_lainnya_text' => '',
    'kesesuaian_metode' => 'Tervalidasi / Terverifikasi',
    'homogenisasi_preparasi' => '[DATA PENGUJIAN PENYELIA] 25 g sampel dilarutkan dalam 225 mL TSB homogen.',
    'bobot_sampel' => '25',
    'volume_pelarut' => '225',
    'jenis_pelarut_homogenisasi' => 'TSB',
    'tabel_tahapan' => [
        [
            'tahap' => 'Pra Pengayaan',
            'media' => 'TSB',
            'suhu' => '30-35 °C',
            'waktu' => '18-24 jam',
            'sampel_uji' => 'Keruh, ada pertumbuhan'
        ],
        [
            'tahap' => 'Pengayaan Selektif',
            'media' => 'MacConkey Broth / RV',
            'suhu' => '42-44 °C',
            'waktu' => '24-48 jam',
            'sampel_uji' => 'Tidak ada perubahan warna spesifik'
        ],
        [
            'tahap' => 'Seleksi & Isolasi',
            'media' => 'MacConkey Agar & XLD',
            'suhu' => '30-35 °C',
            'waktu' => '18-24 jam',
            'sampel_uji' => 'Tidak ditemukan koloni khas E. coli maupun Salmonella'
        ]
    ],
    'hasil_ringkasan' => '[DATA UJI PENYELIA] Negatif cemaran E. coli dan Salmonella spp. per 10 g.',
    'hasil_uji' => 'Negatif per 10 g',
    'pustaka_metode' => 'Farmakope Indonesia Edisi VI <51>',
    'pustaka_acuan' => 'Farmakope Indonesia Edisi VI <51>',
    'syarat_keberterimaan' => 'Negatif per 10 g',
    'syarat' => 'Negatif per 10 g',
    'kesimpulan_lcp' => 'Memenuhi Syarat (MS)',
    'kesimpulan_akhir' => 'Memenuhi Syarat (MS)'
];

$payload_data = [
    'parameter_values' => $test_payload,
    'alat_ids' => [],
    'reagen_ids' => []
];
foreach ($test_payload as $k => $v) {
    $payload_data[$k] = $v;
}
$json_hasil = json_encode($payload_data, JSON_UNESCAPED_UNICODE);

$catatan = '[DATA UJI PENYELIA MIK-04 - AMAN DIHAPUS SETELAH TES]';
$kesimpulan = 'Memenuhi Syarat (MS)';
$stmt = $mysqli->prepare("INSERT INTO test_results (session_id, sample_id, method_id, template_id, penguji_id, data_hasil, kesimpulan, catatan, status, waktu_mulai, waktu_selesai) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Menunggu Verifikasi', ?, ?)");
$stmt->bind_param("iiiiisssss", $session_id, $sample_id, $method_id, $template_id, $penguji_id, $json_hasil, $kesimpulan, $catatan, $now, $now);
$stmt->execute();
$test_result_id = $mysqli->insert_id;
echo "Created Test Result ID: {$test_result_id} (Status: Menunggu Verifikasi)\n";

// Update sample status temporarily to 'Menunggu Verifikasi'
$mysqli->query("UPDATE samples SET status = 'Menunggu Verifikasi' WHERE id = {$sample_id}");
echo "Sample ID {$sample_id} set to 'Menunggu Verifikasi'.\n";

$mysqli->close();
echo "\nSETUP DONE! Session ID = {$session_id}, Test Result ID = {$test_result_id}\n";
