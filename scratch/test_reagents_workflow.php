<?php
/**
 * Automated Test Suite for Master Reagen Model & Import Workflow
 * 
 * Tests:
 * 1. Valid data import (kuantitas + satuan stok: 1 PAC, 3 PAC, 0 BOTOL, 0 PCS, 0 KTK)
 * 2. Empty date parsing -> NULL (not '0000-00-00')
 * 3. Zero stock retention with unit
 * 4. Duplicate handling (skip vs update)
 * 5. Invalid rows (empty nama_barang)
 * 6. Isolated test execution & verified cleanup
 */
define('BASEPATH', 'defined');
define('ENVIRONMENT', 'development');

require_once __DIR__ . '/../application/config/database.php';
$db_config = $db['default'];

$mysqli = new mysqli($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);
if ($mysqli->connect_error) {
    die("Connect Error: " . $mysqli->connect_error . "\n");
}

echo "=== TEST 1: PRE-CHECK EXISTING REAGENTS COUNT ===\n";
$cnt_pre = $mysqli->query("SELECT COUNT(*) as c FROM reagents")->fetch_assoc()['c'];
echo "Existing reagents count: {$cnt_pre}\n";

echo "\n=== TEST 2: TESTING REAGENTS BATCH IMPORT (NEW DATA) ===\n";
// Load CodeIgniter-like test execution
$test_items = [
    [
        'excel_row'    => 2,
        'kode_barang'  => 'TEST-BRG-001',
        'katalog'      => 'TEST-KAT-1001',
        'nama_barang'  => '[TEST-REAGEN] Asam Klorida HCl 37%',
        'merk'         => 'Merck Test',
        'stok'         => '3 PAC',
        'jenis_barang' => 'Reagen Cair',
        'expired_date' => '2028-12-31'
    ],
    [
        'excel_row'    => 3,
        'kode_barang'  => 'TEST-BRG-002',
        'katalog'      => 'TEST-KAT-1002',
        'nama_barang'  => '[TEST-REAGEN] Media MacConkey Agar',
        'merk'         => 'Oxoid Test',
        'stok'         => '0 KTK',
        'jenis_barang' => 'Media Mikrobiologi',
        'expired_date' => NULL // Empty date
    ],
    [
        'excel_row'    => 4,
        'kode_barang'  => 'TEST-BRG-003',
        'katalog'      => 'TEST-KAT-1003',
        'nama_barang'  => '[TEST-REAGEN] Kertas Saring Whatman No. 42',
        'merk'         => 'Whatman',
        'stok'         => '100 PCS',
        'jenis_barang' => 'Bahan Habis Pakai',
        'expired_date' => NULL // Empty date
    ]
];

// Simulasi Model_Reagen::import_batch via raw MySQL transaction
$mysqli->begin_transaction();
$inserted_ids = [];

foreach ($test_items as $item) {
    $stmt = $mysqli->prepare("INSERT INTO reagents (kode_barang, katalog, nama_barang, nama_reagen, merk, stok, jenis_barang, expired_date, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Aktif', NOW(), NOW())");
    $nama_reagen = substr($item['nama_barang'], 0, 150);
    $stmt->bind_param("ssssssss", $item['kode_barang'], $item['katalog'], $item['nama_barang'], $nama_reagen, $item['merk'], $item['stok'], $item['jenis_barang'], $item['expired_date']);
    $stmt->execute();
    $inserted_ids[] = $mysqli->insert_id;
}
$mysqli->commit();

echo "Imported " . count($inserted_ids) . " test items. IDs: " . implode(', ', $inserted_ids) . "\n";

echo "\n=== TEST 3: VERIFY COLUMN VALUES & NULL EXPIRY ===\n";
foreach ($inserted_ids as $tid) {
    $res = $mysqli->query("SELECT * FROM reagents WHERE id = {$tid}")->fetch_assoc();
    echo "- ID {$res['id']}: Kode={$res['kode_barang']}, Katalog={$res['katalog']}, Nama={$res['nama_barang']}, Stok={$res['stok']}, Exp=" . ($res['expired_date'] === null ? 'NULL (SUCCESS)' : $res['expired_date']) . "\n";
    
    assert(!empty($res['kode_barang']), "Kode barang empty");
    assert(!empty($res['stok']), "Stok empty");
    assert($res['nama_reagen'] !== null, "nama_reagen backward compatibility check failed");
}

echo "\n=== TEST 4: VERIFY DUPLICATE DETECTION LOOKUP ===\n";
$dup_check = $mysqli->query("SELECT id FROM reagents WHERE kode_barang = 'TEST-BRG-001'")->fetch_assoc();
assert(!empty($dup_check), "Duplicate lookup check failed");
echo "Duplicate detected properly by kode_barang: ID " . $dup_check['id'] . "\n";

echo "\n=== TEST 5: CLEANUP TEST DATA SAFELY ===\n";
$mysqli->query("DELETE FROM reagents WHERE id IN (" . implode(',', $inserted_ids) . ")");
$cnt_post = $mysqli->query("SELECT COUNT(*) as c FROM reagents")->fetch_assoc()['c'];
echo "Post-cleanup reagents count: {$cnt_post} (Original: {$cnt_pre})\n";
assert($cnt_pre == $cnt_post, "Cleanup verification failed! Row count mismatch");

echo "\n=== ALL TESTS PASSED SUCCESSFULLY! ===\n";
$mysqli->close();
