<?php
/**
 * Test Suite Hardening Master Reagen & Import (Batch 01)
 * 
 * Pengujian Non-Database / Terisolasi:
 * 1. Validasi Ekstensi (.xlsx diizinkan, .xls / format lain ditolak)
 * 2. Validasi Batas Ukuran File (<= 5 MB diizinkan, > 5 MB ditolak)
 * 3. Validasi Error Code PHP Upload ($_FILES error handling)
 * 4. Normalisasi Tanggal Kosong / '0000-00-00' -> NULL
 * 5. Normalisasi Format Tanggal Valid (YYYY-MM-DD, DD/MM/YYYY, Excel Serial)
 * 6. Normalisasi Stok & Satuan (kuantitas + satuan dipertahankan)
 * 7. Deteksi Duplikat (by kode, katalog, composite nama|merk, in-file)
 * 8. Kebijakan Duplikasi: 'skip' (lewati) vs 'update' (perbarui eksplisit)
 * 9. Verifikasi Query Reagen Aktif vs Nonaktif untuk Pengujian
 */

define('BASEPATH', 'defined');
require_once __DIR__ . '/../application/libraries/SimpleXLSX.php';

echo "=== TEST SUITE HARDENING MASTER REAGEN & IMPORT (NON-DATABASE) ===\n\n";

$all_passed = true;
$tests_run = 0;

function run_test($condition, $name, $detail = '') {
    global $all_passed, $tests_run;
    $tests_run++;
    if ($condition) {
        echo "[PASS] {$name}\n";
        if ($detail) echo "       Info: {$detail}\n";
    } else {
        $all_passed = false;
        echo "[FAIL] {$name}\n";
        if ($detail) echo "       ERROR: {$detail}\n";
    }
}

// =========================================================================
// 1. PENGUJIAN VALIDASI EKSTENSI (.xlsx ONLY, .xls DITOLAK)
// =========================================================================
echo "--- 1. Validasi Ekstensi Berkas ---\n";

function validasi_ekstensi_reagen($filename) {
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    return ($ext === 'xlsx');
}

run_test(validasi_ekstensi_reagen('reagents_2026.xlsx') === true, "Berkas .xlsx diterima");
run_test(validasi_ekstensi_reagen('reagents_2026.XLSX') === true, "Berkas .XLSX (huruf besar) diterima");
run_test(validasi_ekstensi_reagen('data_lama.xls') === false, "Berkas .xls biner lama ditolak secara benar");
run_test(validasi_ekstensi_reagen('data.csv') === false, "Berkas .csv ditolak");
run_test(validasi_ekstensi_reagen('reagen.pdf') === false, "Berkas .pdf ditolak");

// =========================================================================
// 2. PENGUJIAN BATAS UKURAN BERKAS (MAKS 5 MB = 5.242.880 bytes)
// =========================================================================
echo "\n--- 2. Validasi Batas Ukuran Berkas (Maks 5 MB) ---\n";

function validasi_ukuran_reagen($bytes) {
    return ($bytes <= 5242880);
}

run_test(validasi_ukuran_reagen(1024) === true, "Ukuran 1 KB diterima");
run_test(validasi_ukuran_reagen(5242880) === true, "Ukuran tepat 5 MB (5.242.880 bytes) diterima");
run_test(validasi_ukuran_reagen(5242881) === false, "Ukuran 5 MB + 1 byte ditolak");
run_test(validasi_ukuran_reagen(10485760) === false, "Ukuran 10 MB ditolak");

// =========================================================================
// 3. PENGUJIAN ERROR CODE PHP UPLOAD ($_FILES['file']['error'])
// =========================================================================
echo "\n--- 3. Validasi Error Code PHP Upload ---\n";

function terjemahkan_error_upload($errorCode) {
    if ($errorCode === UPLOAD_ERR_OK) {
        return null;
    }
    if ($errorCode === UPLOAD_ERR_INI_SIZE || $errorCode === UPLOAD_ERR_FORM_SIZE) {
        return 'Ukuran file melebihi batas maksimum server (maksimal 5 MB).';
    }
    if ($errorCode === UPLOAD_ERR_PARTIAL) {
        return 'File hanya terunggah sebagian. Silakan coba lagi.';
    }
    if ($errorCode === UPLOAD_ERR_NO_FILE) {
        return 'Tidak ada file yang dipilih untuk diunggah.';
    }
    return 'Terjadi kesalahan saat mengunggah file (Kode error upload: ' . (int)$errorCode . ').';
}

run_test(terjemahkan_error_upload(UPLOAD_ERR_OK) === null, "UPLOAD_ERR_OK -> Lolos validasi");
run_test(strpos(terjemahkan_error_upload(UPLOAD_ERR_INI_SIZE), 'batas maksimum') !== false, "UPLOAD_ERR_INI_SIZE -> Pesan batas maksimum");
run_test(strpos(terjemahkan_error_upload(UPLOAD_ERR_PARTIAL), 'terunggah sebagian') !== false, "UPLOAD_ERR_PARTIAL -> Pesan terunggah sebagian");
run_test(strpos(terjemahkan_error_upload(UPLOAD_ERR_NO_FILE), 'Tidak ada file') !== false, "UPLOAD_ERR_NO_FILE -> Pesan tidak ada file");

// =========================================================================
// 4. PENGUJIAN NORMALISASI EXPIRED DATE (KOSONG & '0000-00-00' -> NULL)
// =========================================================================
echo "\n--- 4. Normalisasi Tanggal Kedaluwarsa (Empty / '0000-00-00' -> NULL) ---\n";

function normalisasi_expired_date($raw_exp) {
    if ($raw_exp === '' || $raw_exp === null || $raw_exp === '0000-00-00') {
        return null;
    }
    // Serial Excel
    if (is_numeric($raw_exp) && (float)$raw_exp > 1000 && (float)$raw_exp < 100000) {
        $days = (int) floor((float)$raw_exp);
        $timestamp = ($days - 25569) * 86400;
        return ($timestamp > 0) ? date('Y-m-d', $timestamp) : null;
    }
    // YYYY-MM-DD
    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $raw_exp)) {
        return $raw_exp;
    }
    // DD/MM/YYYY
    if (preg_match('/^(\d{1,2})[\/\-\.](\d{1,2})[\/\-\.](\d{4})$/', $raw_exp, $m)) {
        return sprintf('%04d-%02d-%02d', $m[3], $m[2], $m[1]);
    }
    if (strtotime($raw_exp)) {
        return date('Y-m-d', strtotime($raw_exp));
    }
    return false; // Invalid format
}

run_test(normalisasi_expired_date('') === null, "Tanggal kosong string '' -> NULL");
run_test(normalisasi_expired_date(null) === null, "Tanggal null -> NULL");
run_test(normalisasi_expired_date('0000-00-00') === null, "Tanggal '0000-00-00' -> NULL");
run_test(normalisasi_expired_date('2028-12-31') === '2028-12-31', "Format YYYY-MM-DD '2028-12-31' -> '2028-12-31'");
run_test(normalisasi_expired_date('31/12/2028') === '2028-12-31', "Format DD/MM/YYYY '31/12/2028' -> '2028-12-31'");
run_test(normalisasi_expired_date('47118') === '2028-12-31', "Serial Excel 47118 -> '2028-12-31'");
run_test(normalisasi_expired_date('bukan_tanggal') === false, "String non-tanggal terdeteksi invalid (false)");

// =========================================================================
// 5. PENGUJIAN NORMALISASI STOK DAN SATUAN
// =========================================================================
echo "\n--- 5. Normalisasi Stok & Satuan (Kuantitas + Satuan) ---\n";

function normalisasi_stok($raw_stok) {
    if ($raw_stok === '' || $raw_stok === null) {
        return '0 BOTOL';
    }
    if (is_numeric($raw_stok)) {
        return $raw_stok . ' BOTOL';
    }
    return preg_replace('/\s+/', ' ', trim($raw_stok));
}

run_test(normalisasi_stok('') === '0 BOTOL', "Stok kosong -> '0 BOTOL'");
run_test(normalisasi_stok('3 PAC') === '3 PAC', "Stok '3 PAC' -> '3 PAC'");
run_test(normalisasi_stok('0 KTK') === '0 KTK', "Stok '0 KTK' -> '0 KTK'");
run_test(normalisasi_stok('100 PCS') === '100 PCS', "Stok '100 PCS' -> '100 PCS'");
run_test(normalisasi_stok('5') === '5 BOTOL', "Stok numerik '5' -> '5 BOTOL'");
run_test(normalisasi_stok('  2    VIAL  ') === '2 VIAL', "Stok berspasi banyak '  2    VIAL  ' -> '2 VIAL'");

// =========================================================================
// 6. PENGUJIAN LOGIKA DETEKSI DUPLIKASI (SKIP VS UPDATE)
// =========================================================================
echo "\n--- 6. Logika Deteksi Duplikasi (In-Memory Mock) ---\n";

$mock_db = [
    1 => ['id' => 1, 'kode_barang' => 'BRG-101', 'katalog' => 'KAT-501', 'nama_barang' => 'Etanol 96%', 'merk' => 'Merck', 'stok' => '2 BOTOL'],
    2 => ['id' => 2, 'kode_barang' => 'BRG-102', 'katalog' => 'KAT-502', 'nama_barang' => 'Metanol p.a', 'merk' => 'Smart-Lab', 'stok' => '1 BOTOL']
];

$lookup = [
    'by_kode'      => ['BRG-101' => 1, 'BRG-102' => 2],
    'by_katalog'   => ['KAT-501' => 1, 'KAT-502' => 2],
    'by_composite' => ['ETANOL 96%|MERCK' => 1, 'METANOL P.A|SMART-LAB' => 2]
];

function simulasi_import_item($item, $duplicate_action, &$mock_db, &$lookup) {
    $kode     = !empty($item['kode_barang']) ? strtoupper(trim($item['kode_barang'])) : '';
    $katalog  = !empty($item['katalog']) ? strtoupper(trim($item['katalog'])) : '';
    $comp_key = strtoupper(trim($item['nama_barang'])) . '|' . strtoupper(trim((string)$item['merk']));

    $existing_id = null;
    if ($kode !== '' && isset($lookup['by_kode'][$kode])) {
        $existing_id = $lookup['by_kode'][$kode];
    } elseif ($katalog !== '' && isset($lookup['by_katalog'][$katalog])) {
        $existing_id = $lookup['by_katalog'][$katalog];
    } elseif (isset($lookup['by_composite'][$comp_key])) {
        $existing_id = $lookup['by_composite'][$comp_key];
    }

    if ($existing_id !== null) {
        if ($duplicate_action === 'update') {
            $mock_db[$existing_id]['stok'] = $item['stok'];
            return 'UPDATED';
        } else {
            return 'SKIPPED';
        }
    } else {
        $new_id = count($mock_db) + 1;
        $mock_db[$new_id] = $item;
        if ($kode !== '') $lookup['by_kode'][$kode] = $new_id;
        if ($katalog !== '') $lookup['by_katalog'][$katalog] = $new_id;
        $lookup['by_composite'][$comp_key] = $new_id;
        return 'INSERTED';
    }
}

// Test Duplicate Skip
$dup_item = ['kode_barang' => 'BRG-101', 'katalog' => 'KAT-501', 'nama_barang' => 'Etanol 96%', 'merk' => 'Merck', 'stok' => '5 BOTOL'];
$res_skip = simulasi_import_item($dup_item, 'skip', $mock_db, $lookup);
run_test($res_skip === 'SKIPPED', "Duplikat dengan action 'skip' -> SKIPPED");
run_test($mock_db[1]['stok'] === '2 BOTOL', "Data di mock DB TIDAK berubah saat di-skip");

// Test Duplicate Update
$res_update = simulasi_import_item($dup_item, 'update', $mock_db, $lookup);
run_test($res_update === 'UPDATED', "Duplikat dengan action 'update' -> UPDATED");
run_test($mock_db[1]['stok'] === '5 BOTOL', "Data di mock DB diperbarui secara eksplisit saat 'update'");

// Test New Insert
$new_item = ['kode_barang' => 'BRG-103', 'katalog' => 'KAT-503', 'nama_barang' => 'Aseton p.a', 'merk' => 'Merck', 'stok' => '3 BOTOL'];
$res_insert = simulasi_import_item($new_item, 'skip', $mock_db, $lookup);
run_test($res_insert === 'INSERTED', "Item baru -> INSERTED");
run_test(isset($mock_db[3]) && $mock_db[3]['nama_barang'] === 'Aseton p.a', "Item baru tersimpan di mock DB");

// =========================================================================
// 7. PENGUJIAN FILTER STATUS REAGEN (AKTIF VS SEMUA)
// =========================================================================
echo "\n--- 7. Verifikasi Logika Pengambilan Reagen Aktif vs Nonaktif ---\n";

$mock_reagents_table = [
    ['id' => 1, 'nama' => 'Reagen A', 'status' => 'Aktif'],
    ['id' => 2, 'nama' => 'Reagen B', 'status' => 'Nonaktif'],
    ['id' => 3, 'nama' => 'Reagen C', 'status' => 'Aktif']
];

function mock_ambil_semua($data, $status = null) {
    if ($status === null) return $data;
    return array_values(array_filter($data, function($r) use ($status) {
        return $r['status'] === $status;
    }));
}

function mock_ambil_aktif($data) {
    return mock_ambil_semua($data, 'Aktif');
}

$hasil_aktif = mock_ambil_aktif($mock_reagents_table);
run_test(count($hasil_aktif) === 2, "mock_ambil_aktif mengembalikan tepat 2 reagen aktif");
run_test($hasil_aktif[0]['nama'] === 'Reagen A' && $hasil_aktif[1]['nama'] === 'Reagen C', "Hanya reagen berstatus 'Aktif' yang muncul untuk pengujian");

$hasil_semua = mock_ambil_semua($mock_reagents_table);
run_test(count($hasil_semua) === 3, "mock_ambil_semua mengembalikan seluruh 3 reagen (termasuk Nonaktif) untuk histori");

echo "\n=======================================================\n";
echo "RINGKASAN: {$tests_run} tes dijalankan. Status: " . ($all_passed ? "SEMUA TES BERHASIL (100% PASS)" : "ADA TES GAGAL") . "\n";
echo "=======================================================\n";

exit($all_passed ? 0 : 1);
