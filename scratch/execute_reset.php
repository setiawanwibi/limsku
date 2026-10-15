<?php
// execute_reset.php
$mysqli = new mysqli('127.0.0.1', 'root', '', 'limsku');
if ($mysqli->connect_error) {
    die("[FATAL] Gagal koneksi database: " . $mysqli->connect_error . "\n");
}

echo "=== TAHAP 1: PREFLIGHT CHECK KETAT ===\n";

// 1. Check form_templates
$res = $mysqli->query("SELECT id FROM form_templates ORDER BY id ASC");
$ft_ids = [];
while ($r = $res->fetch_assoc()) { $ft_ids[] = (int)$r['id']; }
if (count($ft_ids) !== 17 || $ft_ids !== range(1, 17)) {
    die("[ABORT] form_templates tidak sesuai target! Total: " . count($ft_ids) . "\n");
}

// 2. Check test_results
$res = $mysqli->query("SELECT id, session_id, method_id FROM test_results ORDER BY id ASC");
$tr_ids = [];
$invalid_session = 0;
while ($r = $res->fetch_assoc()) {
    $tr_ids[] = (int)$r['id'];
    if (!in_array((int)$r['session_id'], range(1, 7))) {
        $invalid_session++;
    }
}
if (count($tr_ids) !== 15 || $tr_ids !== range(1, 15)) {
    die("[ABORT] test_results tidak sesuai target! Total: " . count($tr_ids) . "\n");
}
if ($invalid_session > 0) {
    die("[ABORT] test_results memiliki session di luar 1-7!\n");
}

// 3. Check testing_sessions
$res = $mysqli->query("SELECT id FROM testing_sessions ORDER BY id ASC");
$ts_ids = [];
while ($r = $res->fetch_assoc()) { $ts_ids[] = (int)$r['id']; }
if (count($ts_ids) !== 7 || $ts_ids !== range(1, 7)) {
    die("[ABORT] testing_sessions tidak sesuai target! Total: " . count($ts_ids) . "\n");
}

// 4. Check samples (Wajib 24)
$res = $mysqli->query("SELECT COUNT(*) as cnt FROM samples")->fetch_assoc();
if ((int)$res['cnt'] !== 24) {
    die("[ABORT] samples tidak berjumlah 24! Total: " . $res['cnt'] . "\n");
}

// 5. Check methods (Wajib tidak ada template_id yang terisi)
$res = $mysqli->query("SELECT COUNT(*) as total, COUNT(template_id) as mapped FROM methods")->fetch_assoc();
if ((int)$res['mapped'] !== 0) {
    die("[ABORT] methods memiliki relasi template terisi!\n");
}

echo "Preflight check 100% LOLOS!\n\n";

echo "=== TAHAP 2: EKSEKUSI DML TRANSAKSI ===\n";

$mysqli->begin_transaction();

try {
    // 1. Hapus test_results target
    $res1 = $mysqli->query("DELETE FROM test_results WHERE id IN (1,2,3,4,5,6,7,8,9,10,11,12,13,14,15)");
    $affected_tr = $mysqli->affected_rows;
    if ($affected_tr !== 15) {
        throw new Exception("Jumlah test_results terhapus ($affected_tr) tidak sesuai target 15!");
    }
    echo "1. test_results terhapus: $affected_tr baris.\n";

    // 2. Hapus testing_sessions target
    $res2 = $mysqli->query("DELETE FROM testing_sessions WHERE id IN (1,2,3,4,5,6,7)");
    $affected_ts = $mysqli->affected_rows;
    if ($affected_ts !== 7) {
        throw new Exception("Jumlah testing_sessions terhapus ($affected_ts) tidak sesuai target 7!");
    }
    echo "2. testing_sessions terhapus: $affected_ts baris.\n";

    // 3. Hapus form_templates target
    $res3 = $mysqli->query("DELETE FROM form_templates WHERE id IN (1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17)");
    $affected_ft = $mysqli->affected_rows;
    if ($affected_ft !== 17) {
        throw new Exception("Jumlah form_templates terhapus ($affected_ft) tidak sesuai target 17!");
    }
    echo "3. form_templates terhapus: $affected_ft baris.\n";

    // Commit transaksi
    $mysqli->commit();
    echo "\n[TRANSAKSI BERHASIL DI-COMMIT]\n";

} catch (Exception $e) {
    $mysqli->rollback();
    die("[ROLLBACK DILAKUKAN] Error: " . $e->getMessage() . "\n");
}

echo "\n=== TAHAP 3: VERIFIKASI PASCA-RESET ===\n";
$c_ft = $mysqli->query("SELECT COUNT(*) as cnt FROM form_templates")->fetch_assoc()['cnt'];
$c_tr = $mysqli->query("SELECT COUNT(*) as cnt FROM test_results")->fetch_assoc()['cnt'];
$c_ts = $mysqli->query("SELECT COUNT(*) as cnt FROM testing_sessions")->fetch_assoc()['cnt'];
$c_m  = $mysqli->query("SELECT COUNT(*) as cnt FROM methods")->fetch_assoc()['cnt'];
$c_s  = $mysqli->query("SELECT COUNT(*) as cnt FROM samples")->fetch_assoc()['cnt'];

echo "form_templates: $c_ft (Target: 0)\n";
echo "test_results: $c_tr (Target: 0)\n";
echo "testing_sessions: $c_ts (Target: 0)\n";
echo "methods: $c_m (Dipertahankan utuh)\n";
echo "samples: $c_s (Dipertahankan utuh: 24)\n";

$mysqli->close();
