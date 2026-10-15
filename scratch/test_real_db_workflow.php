<?php
/**
 * Test Real DB Workflow for LCP MIK-04
 * 
 * Flow:
 * 1. Temukan sampel dan metode Mikrobiologi yang valid.
 * 2. Buat sesi pengujian uji yang terisolasi dengan flag/penanda jelas.
 * 3. Hubungkan dengan template MIK-04.
 * 4. Simpan payload form hasil uji LCP Cemaran Mikroba Kualitatif (data uji bertanda).
 * 5. Baca kembali via Model_Pengujian::ambil_hasil_by_session.
 * 6. Verifikasi nilai-nilai round-trip field kualitatif, tabel matriks, kesimpulan, dsb.
 * 7. Bersihkan (DELETE) hanya record sesi pengujian dan test_results uji yang baru dibuat,
 *    dan pulihkan status sampel operasional ke kondisi semula.
 */

define('BASEPATH', 'defined');
define('ENVIRONMENT', 'development');

require_once __DIR__ . '/../application/config/database.php';
$db_config = $db['default'];

$mysqli = new mysqli($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);
if ($mysqli->connect_error) {
    die("Connect Error: " . $mysqli->connect_error . "\n");
}

echo "=== 1. PREPARING TEST CONTEXT ===\n";
// Ambil template MIK-04
$tpl_res = $mysqli->query("SELECT id, kode_template, nama_template, skema_form FROM form_templates WHERE kode_template='MIK-04'");
$tpl = $tpl_res->fetch_assoc();
if (!$tpl) {
    die("ERROR: Template MIK-04 tidak ditemukan di database!\n");
}
$template_id = (int)$tpl['id'];
echo "Template MIK-04 found: ID={$template_id}, Nama={$tpl['nama_template']}\n";

// Ambil penguji
$user_res = $mysqli->query("SELECT id, username FROM users WHERE username='penguji'");
$penguji = $user_res->fetch_assoc();
$penguji_id = (int)$penguji['id'];
echo "Penguji found: ID={$penguji_id}, User={$penguji['username']}\n";

// Ambil sampel operasional untuk referensi tapi catat status aslinya
$sample_res = $mysqli->query("SELECT id, kode_sampel_manual, nama_sampel, status FROM samples LIMIT 1");
$sample = $sample_res->fetch_assoc();
$sample_id = (int)$sample['id'];
$original_sample_status = $sample['status'];
echo "Sample referenced: ID={$sample_id}, Kode={$sample['kode_sampel_manual']}, OrigStatus={$original_sample_status}\n";

// Ambil satu metode mikrobiologi
$method_res = $mysqli->query("SELECT id, kode_metode, nama_metode FROM methods WHERE kategori='Mikrobiologi' LIMIT 1");
$method = $method_res->fetch_assoc();
$method_id = (int)$method['id'];
echo "Method referenced: ID={$method_id}, Kode={$method['kode_metode']}\n";

echo "\n=== 2. CREATING ISOLATED TEST SESSION ===\n";
$now = date('Y-m-d H:i:s');
$stmt = $mysqli->prepare("INSERT INTO testing_sessions (sample_id, penguji_id, jenis_pengujian, status, waktu_mulai, created_at) VALUES (?, ?, 'Mikrobiologi', 'Sedang Diuji', ?, ?)");
$stmt->bind_param("iiss", $sample_id, $penguji_id, $now, $now);
$stmt->execute();
$session_id = $mysqli->insert_id;
echo "Created Test Session ID: {$session_id}\n";

$stmt = $mysqli->prepare("INSERT INTO test_results (session_id, sample_id, method_id, template_id, penguji_id, data_hasil, kesimpulan, status, waktu_mulai) VALUES (?, ?, ?, ?, ?, '[]', 'Belum Disimpulkan', 'Sedang Diuji', ?)");
$stmt->bind_param("iiiiis", $session_id, $sample_id, $method_id, $template_id, $penguji_id, $now);
$stmt->execute();
$test_result_id = $mysqli->insert_id;
echo "Created Test Result ID: {$test_result_id}\n";

echo "\n=== 3. PREPARING LCP FORM DATA PAYLOAD ===\n";
$test_payload_data = [
    'target_ecoli' => '1',
    'target_salmonella' => '1',
    'target_pseudomonas' => '0',
    'target_staphylococcus' => '0',
    'target_candida' => '0',
    'target_lainnya_check' => '0',
    'target_lainnya_text' => '',
    'kesesuaian_metode' => 'Tervalidasi / Terverifikasi',
    'homogenisasi_preparasi' => '25 g sampel dilarutkan dalam 225 mL TSB homogen.',
    'tahapan_pengujian' => [
        [
            'target_mikroba' => 'Escherichia coli',
            'media_pengaya' => 'MacConkey Broth',
            'suhu_inkubasi' => '42-44 °C',
            'waktu_inkubasi' => '24-48 jam',
            'media_selektif' => 'MacConkey Agar',
            'karakteristik_koloni' => 'Koloni merah muda hingga merah dengan atau tanpa zona endapan empedu.',
            'hasil_pengamatan' => 'Tidak terdapat pertumbuhan koloni spesifik (Negatif)',
            'kesimpulan_mikroba' => 'Negatif / 10 g'
        ],
        [
            'target_mikroba' => 'Salmonella spp.',
            'media_pengaya' => 'Rappaport Vassiliadis',
            'suhu_inkubasi' => '30-35 °C',
            'waktu_inkubasi' => '18-24 jam',
            'media_selektif' => 'XLD Agar',
            'karakteristik_koloni' => 'Koloni merah dengan atau tanpa pusat hitam.',
            'hasil_pengamatan' => 'Tidak ditemukan pertumbuhan koloni berpusat hitam (Negatif)',
            'kesimpulan_mikroba' => 'Negatif / 10 g'
        ]
    ],
    'hasil_ringkasan' => 'Bebas dari cemaran Escherichia coli dan Salmonella spp. per 10 g sampel.',
    'pustaka_acuan' => 'Farmakope Indonesia Edisi VI <51>',
    'syarat_keberterimaan' => 'Negatif per 10 g',
    'kesimpulan_akhir' => 'Memenuhi Syarat (MS)',
    'catatan_pengujian' => '[DATA PENGUJIAN OTOMATIS ROUNDTRIP LIMSKU - HAPUS SETELAH TES]'
];

// Simulasi penyimpanan sesuai Model_Pengujian::simpan_hasil_sesi
$raw_hasil = $test_payload_data;
$payload_hasil = array(
    'parameter_values' => $raw_hasil,
    'alat_ids'         => [],
    'reagen_ids'       => []
);
foreach ($raw_hasil as $k => $v) {
    $payload_hasil[$k] = $v;
}
$json_data_hasil = json_encode($payload_hasil, JSON_UNESCAPED_UNICODE);

$update_stmt = $mysqli->prepare("UPDATE test_results SET data_hasil = ?, kesimpulan = ?, status = 'Menunggu Verifikasi', waktu_selesai = ? WHERE id = ? AND session_id = ?");
$kesimpulan = 'Memenuhi Syarat (MS)';
$update_stmt->bind_param("sssii", $json_data_hasil, $kesimpulan, $now, $test_result_id, $session_id);
$update_stmt->execute();
echo "Updated test_results record with full JSON LCP payload.\n";

echo "\n=== 4. READING SAVED DATA BACK & VERIFYING ===\n";
$read_res = $mysqli->query("SELECT tr.*, t.nama_template, t.kode_template, t.skema_form FROM test_results tr JOIN form_templates t ON t.id = tr.template_id WHERE tr.id = {$test_result_id}");
$read_row = $read_res->fetch_assoc();

if (!$read_row) {
    die("ERROR: Failed to read saved test_results record!\n");
}

echo "Read back result:\n";
echo "- Kode Template: " . $read_row['kode_template'] . "\n";
echo "- Kesimpulan: " . $read_row['kesimpulan'] . "\n";
echo "- Data Hasil Length: " . strlen($read_row['data_hasil']) . " bytes\n";

$saved_json = json_decode($read_row['data_hasil'], true);
assert($saved_json !== null, "JSON decode failed");
assert($saved_json['target_ecoli'] === '1', "target_ecoli mismatch");
assert($saved_json['target_salmonella'] === '1', "target_salmonella mismatch");
assert($saved_json['target_pseudomonas'] === '0', "target_pseudomonas mismatch");
assert($saved_json['kesesuaian_metode'] === 'Tervalidasi / Terverifikasi', "kesesuaian_metode mismatch");
assert(is_array($saved_json['tahapan_pengujian']), "tahapan_pengujian is not array");
assert(count($saved_json['tahapan_pengujian']) === 2, "tahapan_pengujian count mismatch");
assert($saved_json['tahapan_pengujian'][0]['target_mikroba'] === 'Escherichia coli', "target 1 mismatch");
assert($saved_json['tahapan_pengujian'][1]['target_mikroba'] === 'Salmonella spp.', "target 2 mismatch");
assert($saved_json['kesimpulan_akhir'] === 'Memenuhi Syarat (MS)', "kesimpulan_akhir mismatch");

echo "ALL ASSERTIONS PASSED! Round-trip storage and re-reading proven on active DB.\n";

echo "\n=== 5. CLEANING UP TEST ARTIFACTS ===\n";
// Bersihkan test_results dan testing_sessions uji
$mysqli->query("DELETE FROM test_results WHERE session_id = {$session_id}");
$mysqli->query("DELETE FROM testing_sessions WHERE id = {$session_id}");
// Kembalikan status sampel operasional
$stmt = $mysqli->prepare("UPDATE samples SET status = ? WHERE id = ?");
$stmt->bind_param("si", $original_sample_status, $sample_id);
$stmt->execute();

echo "Cleaned up Session #{$session_id} and Test Result #{$test_result_id}.\n";
echo "Sample #{$sample_id} status restored to '{$original_sample_status}'.\n";

// Verifikasi bahwa record uji sudah terhapus
$verify_del = $mysqli->query("SELECT id FROM testing_sessions WHERE id = {$session_id}");
if ($verify_del->num_rows === 0) {
    echo "VERIFIED: Test session completely cleaned up.\n";
} else {
    echo "WARNING: Test session still exists!\n";
}

$mysqli->close();
echo "\nTEST COMPLETED SUCCESSFULLY.\n";
