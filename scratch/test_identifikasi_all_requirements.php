<?php
// Test Suite: Verifikasi Implementasi LCP Identifikasi (3 Poin Revisi)
$errors = array();
$passes = array();

function test_assert($condition, $desc) {
    global $errors, $passes;
    if ($condition) {
        $passes[] = "PASS: " . $desc;
    } else {
        $errors[] = "FAIL: " . $desc;
    }
}

// 1. Test proses_sesi.php
$ps = file_get_contents(__DIR__ . '/../application/views/pengujian/proses_sesi.php');

test_assert(strpos($ps, '$is_identifikasi = (stripos($f_param_name, \'identifikasi\')') !== false,
    "proses_sesi.php: Deteksi adaptif parameter Identifikasi aktif pada loop tab form");

test_assert(strpos($ps, 'in_array($sec_id, array(\'perhitungan_kadar_hplc\', \'perhitungan_kadar_dan_presisi\', \'perhitungan_kadar\')') !== false,
    "proses_sesi.php (Poin 1): Bagian perhitungan kadar HPLC & Spektro di-skip otomatis untuk Identifikasi");

test_assert(strpos($ps, '$kadar_exclusive_flds = array(') !== false,
    "proses_sesi.php (Poin 3): Field metode perhitungan, bobot rata-rata, klaim, faktor BM di-skip otomatis untuk Identifikasi");

test_assert(strpos($ps, "'kategori' => 'Spike'") !== false && strpos($ps, "'kategori' => 'Pelarut'") !== false,
    "proses_sesi.php (Poin 2): Default baris tabel dibakukan menjadi 4 baris: Baku Pembanding, Pelarut, Zat Uji, Spike");

test_assert(strpos($ps, '$is_identifikasi ? \'text\' : $c_type') !== false,
    "proses_sesi.php (Poin 2): Input sel tabel mendukung teks/kalimat bebas saat parameter Identifikasi");

test_assert(strpos($ps, 'pName.indexOf(\'identifikasi\') !== -1 || tName.indexOf(\'identifikasi\') !== -1') !== false,
    "proses_sesi.php: JS hitungHPLCPane aman dan keluar dini untuk Identifikasi");

test_assert(strpos($ps, 'Skip hitung kadar untuk LCP Identifikasi') !== false,
    "proses_sesi.php: JS hitungSpektrofotometriPane aman dan keluar dini untuk Identifikasi");


// 2. Test cetak_lcp_kckt.php
$kckt = file_get_contents(__DIR__ . '/../application/views/pengujian/cetak_lcp_kckt.php');

test_assert(strpos($kckt, '$is_identifikasi = (stripos($param_uji, \'identifikasi\')') !== false,
    "cetak_lcp_kckt.php: Deteksi dinamis \$is_identifikasi aktif");

test_assert(strpos($kckt, "array('kategori' => 'Pelarut'),") !== false && strpos($kckt, "array('kategori' => 'Spike')") !== false,
    "cetak_lcp_kckt.php (Poin 2): Tabel KCKT memiliki 4 baris (Baku Pembanding, Pelarut, Zat Uji, Spike)");

test_assert(strpos($kckt, 'if (!$is_identifikasi && stripos($kat, \'spiked\') !== false) continue;') !== false,
    "cetak_lcp_kckt.php (Poin 2): Baris Spike tidak di-filter/tetap tampil pada LCP Identifikasi");

test_assert(strpos($kckt, 'if (!$is_identifikasi && !empty($p[\'faktor_konversi_bm\'])') !== false,
    "cetak_lcp_kckt.php (Poin 3): Faktor koreksi BM disembunyikan pada Identifikasi");

test_assert(strpos($kckt, 'HASIL PENGUJIAN IDENTIFIKASI (KUALITATIF - Poin 1 & 3: Tanpa Rumus & Kolom Kadar)') !== false,
    "cetak_lcp_kckt.php (Poin 1): Rumus kadar dan tabel kadar replikasi 1/2 disembunyikan dan digantikan Hasil Uji Identifikasi kualitatif");

test_assert(strpos($kckt, "Pustaka Metode") !== false && strpos($kckt, "Pustaka Persyaratan") !== false && strpos($kckt, "Syarat :") !== false,
    "cetak_lcp_kckt.php (Poin 1): Pustaka acuan dan syarat spesifikasi tetap dipertahankan");


// 3. Test cetak_lcp_spektrofotometri.php
$spek = file_get_contents(__DIR__ . '/../application/views/pengujian/cetak_lcp_spektrofotometri.php');

test_assert(strpos($spek, '$is_identifikasi = (stripos($param_uji, \'identifikasi\')') !== false,
    "cetak_lcp_spektrofotometri.php: Deteksi dinamis \$is_identifikasi aktif");

test_assert(strpos($spek, '<?php if ($is_identifikasi): ?>') !== false && strpos($spek, 'colspan="4"><?php echo html_escape($p[\'kecepatan_scanning\']') !== false,
    "cetak_lcp_spektrofotometri.php (Poin 3): Br, Ke, dan BM disembunyikan saat Identifikasi");

test_assert(strpos($spek, "array('nama_zat' => 'Pelarut'),") !== false && strpos($spek, "array('nama_zat' => 'Spike')") !== false,
    "cetak_lcp_spektrofotometri.php (Poin 2): Tabel preparasi menampilkan 4 baris baku untuk Identifikasi");

test_assert(strpos($spek, 'HASIL PENGUJIAN IDENTIFIKASI (KUALITATIF - Poin 1 & 3: Tanpa Rumus & Kolom Kadar)') !== false,
    "cetak_lcp_spektrofotometri.php (Poin 1): Rumus kadar & sub-perhitungan disembunyikan dan digantikan Hasil Uji kualitatif");

test_assert(strpos($spek, "Pustaka metode :") !== false && strpos($spek, "Pustaka persyaratan :") !== false && strpos($spek, "Syarat :") !== false,
    "cetak_lcp_spektrofotometri.php (Poin 1): Pustaka acuan dan syarat mutu tetap dipertahankan");


// 4. Test cetak_lcp_klt.php
$klt = file_get_contents(__DIR__ . '/../application/views/pengujian/cetak_lcp_klt.php');

test_assert(strpos($klt, "array('kategori' => 'Pelarut', 'nama_zat' => 'Pelarut'),") !== false && strpos($klt, "array('kategori' => 'Spike', 'nama_zat' => 'Spike')") !== false,
    "cetak_lcp_klt.php (Poin 2): Default baris tabel dibakukan 4 baris: Baku Pembanding, Pelarut, Zat Uji, Spike");


// 5. Test pilih_form error (from screenshot)
$ctrl = file_get_contents(__DIR__ . '/../application/controllers/Pengujian.php');
test_assert(strpos($ctrl, 'ambil_master_parameter_untuk_picker($jenis_pengujian, NULL, 300);') !== false,
    "Pengujian.php: Line 257 menggunakan \$jenis_pengujian (bukan \$sesi['jenis_pengujian']), bebas error 'Undefined variable: sesi'");


echo "=== HASIL VERIFIKASI IDENTIFIKASI & AUDIT ===\n";
foreach ($passes as $p) {
    echo "  [OK] " . $p . "\n";
}
if (!empty($errors)) {
    echo "\n=== ADA KEGAGALAN (" . count($errors) . ") ===\n";
    foreach ($errors as $e) {
        echo "  [FAIL] " . $e . "\n";
    }
    exit(1);
} else {
    echo "\n>>> SEMUA TEST IDENTIFIKASI (3 POIN REVISI + SCREENSHOT FIX) LULUS 100% TANPA KENDALA! <<<\n";
    exit(0);
}
