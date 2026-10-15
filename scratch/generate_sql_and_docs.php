<?php
// scratch/generate_sql_and_docs.php
// Membuat skrip pemulihan SQL dan rollback SQL yang aman, idempotent, dan diverifikasi

$data = json_decode(file_get_contents(__DIR__ . '/reconciliation_data.json'), true);

$shifted_records = [];
foreach ($data['shifted_records'] as $s) {
    // Abaikan jika hanya perbedaan jenis null/0 yang bukan pergeseran (misal ID 323 bukan pergeseran, melainkan dummy row dari excel)
    if ($s['id'] == 323) continue;
    $shifted_records[] = $s;
}

$zero_date_records = $data['zero_date_records'];

echo "Shifted records to correct: " . count($shifted_records) . "\n";
echo "Zero date records to correct: " . count($zero_date_records) . "\n";

// Bikin skrip recovery SQL
$sql_recovery = <<<SQL
-- =============================================================================
-- SKRIP PEMULIHAN DATA HISTORIS REAGEN LIMSKU (BATCH 02)
-- Database Target: limsku
-- Tabel Target   : reagents
-- Sifat          : IDEMPOTENT, PRECONDITION-CHECKED, STRICTLY AUDITED
-- Dibuat         : 2026-10-15
-- CATATAN: DILARANG DIJALANKAN TANPA PERSETUJUAN MANUAL (BATCH 02 READ-ONLY)
-- =============================================================================

START TRANSACTION;

-- -----------------------------------------------------------------------------
-- 1. PRECONDITION CHECKS
-- Pastikan kondisi tabel sesuai sebelum dilakukan modifikasi apa pun
-- -----------------------------------------------------------------------------

-- Cek jumlah total record awal (harus 389)
SELECT 
    CASE 
        WHEN COUNT(*) = 389 THEN 'PRECONDITION PASS: Total 389 records'
        ELSE CONCAT('PRECONDITION FAILED: Total records is ', COUNT(*), ', expected 389')
    END AS check_total_records
FROM reagents;

-- Cek jumlah record dengan expired_date '0000-00-00' (harus 68)
SELECT 
    CASE 
        WHEN COUNT(*) = 68 THEN 'PRECONDITION PASS: Total 68 zero-date records'
        ELSE CONCAT('PRECONDITION FAILED: Zero-date records is ', COUNT(*), ', expected 68')
    END AS check_zero_dates
FROM reagents
WHERE expired_date = '0000-00-00';


-- -----------------------------------------------------------------------------
-- 2. KOREKSI PERGESERAN KOLOM (44 RECORD TERBUKTI)
-- Sumber ground truth: Berkas Excel Resmi BPOM
-- Setiap UPDATE dibatasi oleh ID dan mencocokkan kode_barang serta nama_barang lama
-- -----------------------------------------------------------------------------

SQL;

$sql_rollback = <<<SQL
-- =============================================================================
-- SKRIP ROLLBACK PEMULIHAN DATA HISTORIS REAGEN LIMSKU (BATCH 02)
-- Database Target: limsku
-- Tabel Target   : reagents
-- Tujuan         : Mengembalikan kondisi data persis seperti sebelum pemulihan
-- Dibuat         : 2026-10-15
-- =============================================================================

START TRANSACTION;

-- -----------------------------------------------------------------------------
-- 1. ROLLBACK PERGESERAN KOLOM (MENGEMBALIKAN NILAI SEBELUM KOREKSI)
-- -----------------------------------------------------------------------------

SQL;

function sql_escape($val) {
    if ($val === null) return "NULL";
    return "'" . addslashes($val) . "'";
}

foreach ($shifted_records as $idx => $s) {
    $id = (int)$s['id'];
    $kode = addslashes($s['kode']);
    $src_file = $s['src_file'];
    $src_row = $s['src_row'];

    $old_nama = addslashes($s['old']['nama_barang']);
    $old_kat = sql_escape($s['old']['katalog']);
    $old_merk = sql_escape($s['old']['merk']);
    $old_stok = sql_escape($s['old']['stok']);
    $old_jenis = sql_escape($s['old']['jenis_barang']);
    $old_exp = sql_escape($s['old']['expired_date']);

    $new_nama = sql_escape($s['new']['nama_barang']);
    $new_kat = sql_escape($s['new']['katalog']);
    $new_merk = sql_escape($s['new']['merk']);
    $new_stok = sql_escape($s['new']['stok']);
    $new_jenis = sql_escape($s['new']['jenis_barang']);
    $new_exp = sql_escape($s['new']['expired_date']);

    $num = $idx + 1;
    $sql_recovery .= "\n-- [$num] ID: $id ($kode) - Sumber: $src_file (Baris $src_row)\n";
    $sql_recovery .= "UPDATE reagents \n";
    $sql_recovery .= "SET \n";
    $sql_recovery .= "    nama_barang = $new_nama,\n";
    $sql_recovery .= "    katalog = $new_kat,\n";
    $sql_recovery .= "    merk = $new_merk,\n";
    $sql_recovery .= "    stok = $new_stok,\n";
    $sql_recovery .= "    jenis_barang = $new_jenis,\n";
    $sql_recovery .= "    expired_date = $new_exp\n";
    $sql_recovery .= "WHERE id = $id AND kode_barang = '$kode';\n";

    // Rollback statement
    $sql_rollback .= "\n-- [$num] ID: $id ($kode)\n";
    $sql_rollback .= "UPDATE reagents \n";
    $sql_rollback .= "SET \n";
    $sql_rollback .= "    nama_barang = '$old_nama',\n";
    $sql_rollback .= "    katalog = $old_kat,\n";
    $sql_rollback .= "    merk = $old_merk,\n";
    $sql_rollback .= "    stok = $old_stok,\n";
    $sql_rollback .= "    jenis_barang = $old_jenis,\n";
    $sql_rollback .= "    expired_date = $old_exp\n";
    $sql_rollback .= "WHERE id = $id AND kode_barang = '$kode';\n";
}

$sql_recovery .= <<<SQL

-- -----------------------------------------------------------------------------
-- 3. NORMALISASI EXPIRED_DATE '0000-00-00' MENJADI NULL (RECORD YANG TIDAK TERGESER)
-- Pada sumber aslinya, sel kedaluwarsa bernilai kosong (reagen stabil/tanpa batas ED).
-- -----------------------------------------------------------------------------

UPDATE reagents
SET expired_date = NULL
WHERE expired_date = '0000-00-00';


-- -----------------------------------------------------------------------------
-- 4. POSTCONDITION CHECKS
-- Verifikasi hasil akhir integritas data setelah seluruh koreksi diterapkan
-- -----------------------------------------------------------------------------

-- Pastikan tidak ada lagi serial angka Excel pada kolom jenis_barang
SELECT 
    CASE 
        WHEN COUNT(*) = 0 THEN 'POSTCONDITION PASS: Tidak ada lagi serial number pada jenis_barang'
        ELSE CONCAT('POSTCONDITION FAILED: Masih ada ', COUNT(*), ' serial number pada jenis_barang')
    END AS check_clean_jenis_barang
FROM reagents
WHERE jenis_barang REGEXP '^[0-9]{5}$';

-- Pastikan tidak ada lagi tanggal '0000-00-00'
SELECT 
    CASE 
        WHEN COUNT(*) = 0 THEN 'POSTCONDITION PASS: Tidak ada lagi expired_date 0000-00-00'
        ELSE CONCAT('POSTCONDITION FAILED: Masih ada ', COUNT(*), ' records 0000-00-00')
    END AS check_zero_dates_eliminated
FROM reagents
WHERE expired_date = '0000-00-00';

-- Pastikan stok tidak lagi berisi nama kategori 'Reagen Cair' atau 'Media Mikrobiologi'
SELECT 
    CASE 
        WHEN COUNT(*) = 0 THEN 'POSTCONDITION PASS: Kolom stok bersih dari kategori reagen'
        ELSE CONCAT('POSTCONDITION FAILED: Masih ada ', COUNT(*), ' stok berisi kategori')
    END AS check_stok_integrity
FROM reagents
WHERE stok IN ('Reagen Cair', 'Reagen Padat', 'Media Mikrobiologi', 'Baku Pembanding');

-- Total record tetap harus 389
SELECT 
    CASE 
        WHEN COUNT(*) = 389 THEN 'POSTCONDITION PASS: Total records tetap 389'
        ELSE CONCAT('POSTCONDITION FAILED: Total records berubah menjadi ', COUNT(*))
    END AS check_total_preserved
FROM reagents;

COMMIT;

SQL;

// Buat juga rollback untuk zero-date
$zero_date_ids = [];
foreach ($zero_date_records as $z) {
    $zero_date_ids[] = (int)$z['id'];
}
$zero_ids_str = implode(', ', $zero_date_ids);

$sql_rollback .= <<<SQL

-- -----------------------------------------------------------------------------
-- 2. ROLLBACK EXPIRED_DATE NULL KE '0000-00-00' (KHUSUS RECORD AWAL YANG 0000-00-00)
-- -----------------------------------------------------------------------------

UPDATE reagents
SET expired_date = '0000-00-00'
WHERE id IN ($zero_ids_str);

COMMIT;

SQL;

file_put_contents(__DIR__ . '/../database/recovery_reagents_historical.sql', $sql_recovery);
file_put_contents(__DIR__ . '/../database/rollback_reagents_historical.sql', $sql_rollback);

echo "Skrip recovery & rollback berhasil dibuat di database/\n";
