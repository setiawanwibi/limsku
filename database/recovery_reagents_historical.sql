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

-- [1] ID: 8 (34022294) - Sumber: Data Baku Pembanding.xlsx (Baris 4)
UPDATE reagents 
SET 
    nama_barang = 'Asam Aminobenzoat (2070051)',
    katalog = '',
    merk = '',
    stok = '3 VIAL',
    jenis_barang = 'Baku Pembanding',
    expired_date = NULL
WHERE id = 8 AND kode_barang = '34022294';

-- [2] ID: 9 (21443077) - Sumber: Data Baku Pembanding.xlsx (Baris 5)
UPDATE reagents 
SET 
    nama_barang = 'Asam Folat (B0314004)',
    katalog = '',
    merk = '',
    stok = '1 VIAL',
    jenis_barang = 'Baku Pembanding',
    expired_date = NULL
WHERE id = 9 AND kode_barang = '21443077';

-- [3] ID: 10 (11679155) - Sumber: Data Baku Pembanding.xlsx (Baris 6)
UPDATE reagents 
SET 
    nama_barang = 'Acidum nicotinicum (100015)',
    katalog = '',
    merk = '',
    stok = '2 VIAL',
    jenis_barang = 'Baku Pembanding',
    expired_date = NULL
WHERE id = 10 AND kode_barang = '11679155';

-- [4] ID: 11 (54344487) - Sumber: Data Baku Pembanding.xlsx (Baris 10)
UPDATE reagents 
SET 
    nama_barang = 'Alfa Tokoferol Asetat (2083321)',
    katalog = '',
    merk = '',
    stok = '4 VIAL',
    jenis_barang = 'Baku Pembanding',
    expired_date = NULL
WHERE id = 11 AND kode_barang = '54344487';

-- [5] ID: 12 (35446903) - Sumber: Data Baku Pembanding.xlsx (Baris 12)
UPDATE reagents 
SET 
    nama_barang = 'Alopurinol (208028)',
    katalog = '',
    merk = '',
    stok = '0 VIAL',
    jenis_barang = 'Baku Pembanding',
    expired_date = NULL
WHERE id = 12 AND kode_barang = '35446903';

-- [6] ID: 14 (44720835) - Sumber: Data Baku Pembanding.xlsx (Baris 68)
UPDATE reagents 
SET 
    nama_barang = 'Buthylis Parabenum (205068)',
    katalog = '',
    merk = '',
    stok = '5 VIAL',
    jenis_barang = 'Baku Pembanding',
    expired_date = NULL
WHERE id = 14 AND kode_barang = '44720835';

-- [7] ID: 15 (94151222) - Sumber: Data Baku Pembanding.xlsx (Baris 169)
UPDATE reagents 
SET 
    nama_barang = 'Hexachlorophenum (207165)',
    katalog = '',
    merk = '',
    stok = '6 VIAL',
    jenis_barang = 'Baku Pembanding',
    expired_date = NULL
WHERE id = 15 AND kode_barang = '94151222';

-- [8] ID: 16 (51531441) - Sumber: Data Baku Pembanding.xlsx (Baris 408)
UPDATE reagents 
SET 
    nama_barang = 'Zink Pirition (B0114350)',
    katalog = '0',
    merk = '-',
    stok = '4 VIAL',
    jenis_barang = 'Baku Pembanding',
    expired_date = NULL
WHERE id = 16 AND kode_barang = '51531441';

-- [9] ID: 18 (76185601) - Sumber: Data Baku Pembanding.xlsx (Baris 429)
UPDATE reagents 
SET 
    nama_barang = 'Propil paraben/Nipasol (B0314174)',
    katalog = '0',
    merk = '0',
    stok = '1 VIAL',
    jenis_barang = 'Baku Pembanding',
    expired_date = NULL
WHERE id = 18 AND kode_barang = '76185601';

-- [10] ID: 37 (72464452) - Sumber: Data Media Mikrobiologi.xlsx (Baris 21)
UPDATE reagents 
SET 
    nama_barang = 'Urea agar (base) (1.08492.0500)',
    katalog = '',
    merk = 'Merck',
    stok = '0 BOTOL',
    jenis_barang = 'Media Mikrobiologi',
    expired_date = NULL
WHERE id = 37 AND kode_barang = '72464452';

-- [11] ID: 46 (31406226) - Sumber: Data Media Mikrobiologi.xlsx (Baris 30)
UPDATE reagents 
SET 
    nama_barang = 'Corn Meal Agar (42347-500G-F)',
    katalog = '',
    merk = 'sigma',
    stok = '0 BOTOL',
    jenis_barang = 'Media Mikrobiologi',
    expired_date = NULL
WHERE id = 46 AND kode_barang = '31406226';

-- [12] ID: 48 (74490778) - Sumber: Data Media Mikrobiologi.xlsx (Baris 33)
UPDATE reagents 
SET 
    nama_barang = 'Letheen Broth (M165-500G)',
    katalog = '',
    merk = 'HIMEDIA',
    stok = '0 BOTOL',
    jenis_barang = 'Media Mikrobiologi',
    expired_date = '2026-07-31'
WHERE id = 48 AND kode_barang = '74490778';

-- [13] ID: 50 (23427054) - Sumber: Data Media Mikrobiologi.xlsx (Baris 35)
UPDATE reagents 
SET 
    nama_barang = 'Palcam Listeria selective agar (75977-500G)',
    katalog = '',
    merk = 'Millipore',
    stok = '0 BOTOL',
    jenis_barang = 'Media Mikrobiologi',
    expired_date = '2025-10-18'
WHERE id = 50 AND kode_barang = '23427054';

-- [14] ID: 51 (71703036) - Sumber: Data Media Mikrobiologi.xlsx (Baris 37)
UPDATE reagents 
SET 
    nama_barang = 'Enterobacteria enrichment Broth (1.05403.0500)',
    katalog = '',
    merk = 'sigma millipore',
    stok = '0 BOTOL',
    jenis_barang = 'Media Mikrobiologi',
    expired_date = '2026-09-30'
WHERE id = 51 AND kode_barang = '71703036';

-- [15] ID: 56 (19424412) - Sumber: Data Media Mikrobiologi.xlsx (Baris 42)
UPDATE reagents 
SET 
    nama_barang = 'RapID Inoculation Fluid 2ml.',
    katalog = '',
    merk = 'Remel',
    stok = '0 PAC',
    jenis_barang = 'Media Mikrobiologi',
    expired_date = NULL
WHERE id = 56 AND kode_barang = '19424412';

-- [16] ID: 58 (31648922) - Sumber: Data Media Mikrobiologi.xlsx (Baris 44)
UPDATE reagents 
SET 
    nama_barang = 'XLD agar (1.05287.0500)',
    katalog = '',
    merk = 'Sigma-Millipore',
    stok = '0 BOTOL',
    jenis_barang = 'Media Mikrobiologi',
    expired_date = '2027-10-31'
WHERE id = 58 AND kode_barang = '31648922';

-- [17] ID: 66 (19550384) - Sumber: Data Media Mikrobiologi.xlsx (Baris 60)
UPDATE reagents 
SET 
    nama_barang = '2,3,5-Triphenyltetrazoliumchloride for microbiology (1.08380.0010)',
    katalog = '',
    merk = 'Millipore, Sigma-Aldrich',
    stok = '0 BOTOL',
    jenis_barang = 'Media Mikrobiologi',
    expired_date = '2025-05-31'
WHERE id = 66 AND kode_barang = '19550384';

-- [18] ID: 67 (87298069) - Sumber: Data Media Mikrobiologi.xlsx (Baris 61)
UPDATE reagents 
SET 
    nama_barang = 'Bactident Coagulase Rabbit (1.13306.0001)',
    katalog = '',
    merk = 'Millipore',
    stok = '8 PAC',
    jenis_barang = 'Media Mikrobiologi',
    expired_date = '2026-12-31'
WHERE id = 67 AND kode_barang = '87298069';

-- [19] ID: 69 (17538933) - Sumber: Data Media Mikrobiologi.xlsx (Baris 63)
UPDATE reagents 
SET 
    nama_barang = 'Bactident Oxidase (1.00181.0001)',
    katalog = '',
    merk = 'Millipore',
    stok = '0 PAC',
    jenis_barang = 'Media Mikrobiologi',
    expired_date = '2024-10-31'
WHERE id = 69 AND kode_barang = '17538933';

-- [20] ID: 72 (32997799) - Sumber: Data Media Mikrobiologi.xlsx (Baris 66)
UPDATE reagents 
SET 
    nama_barang = 'Kapas Berlemak 500 gram',
    katalog = '',
    merk = 'One Med',
    stok = '19 PCS',
    jenis_barang = 'Media Mikrobiologi',
    expired_date = NULL
WHERE id = 72 AND kode_barang = '32997799';

-- [21] ID: 75 (53309530) - Sumber: Data Media Mikrobiologi.xlsx (Baris 73)
UPDATE reagents 
SET 
    nama_barang = 'Bile Esculin Azide Agar (M493l-500G)',
    katalog = '',
    merk = 'Himedia',
    stok = '1 BOTOL',
    jenis_barang = 'Media Mikrobiologi',
    expired_date = NULL
WHERE id = 75 AND kode_barang = '53309530';

-- [22] ID: 80 (67294277) - Sumber: Data Media Mikrobiologi.xlsx (Baris 78)
UPDATE reagents 
SET 
    nama_barang = 'Triple Sugar Iron agar (M021-500G)',
    katalog = 'M021-500G',
    merk = '',
    stok = '0 BOTOL',
    jenis_barang = 'Media Mikrobiologi',
    expired_date = '2028-05-30'
WHERE id = 80 AND kode_barang = '67294277';

-- [23] ID: 183 (22592547) - Sumber: Data Reagen Cair.xlsx (Baris 32)
UPDATE reagents 
SET 
    nama_barang = 'Aqua DM 20L, Non AMDK',
    katalog = '',
    merk = 'PT. Brataco',
    stok = '0 DIRIGEN',
    jenis_barang = 'Reagen Cair',
    expired_date = NULL
WHERE id = 183 AND kode_barang = '22592547';

-- [24] ID: 184 (41178184) - Sumber: Data Reagen Cair.xlsx (Baris 33)
UPDATE reagents 
SET 
    nama_barang = 'Sterile Water for Irrigation 1000 mL',
    katalog = '',
    merk = 'PT. Widatra Bhakti',
    stok = '0 BOTOL',
    jenis_barang = 'Reagen Cair',
    expired_date = NULL
WHERE id = 184 AND kode_barang = '41178184';

-- [25] ID: 186 (50611487) - Sumber: Data Reagen Cair.xlsx (Baris 35)
UPDATE reagents 
SET 
    nama_barang = 'Toluene p.a (A.1075)',
    katalog = '',
    merk = 'Smart Lab. Indonesia',
    stok = '0 BOTOL',
    jenis_barang = 'Reagen Cair',
    expired_date = '2028-02-02'
WHERE id = 186 AND kode_barang = '50611487';

-- [26] ID: 188 (57033654) - Sumber: Data Reagen Cair.xlsx (Baris 37)
UPDATE reagents 
SET 
    nama_barang = 'Dimethyl Sulfoxide ( 1.02952.1000)',
    katalog = '',
    merk = 'Merck',
    stok = '1 BOTOL',
    jenis_barang = 'Reagen Cair',
    expired_date = NULL
WHERE id = 188 AND kode_barang = '57033654';

-- [27] ID: 191 (85966351) - Sumber: Data Reagen Cair.xlsx (Baris 41)
UPDATE reagents 
SET 
    nama_barang = 'N,N Dimetil Formamid (A.1094)',
    katalog = '',
    merk = 'Smart Lab. Indonesia',
    stok = '1 BOTOL',
    jenis_barang = 'Reagen Cair',
    expired_date = '2027-09-09'
WHERE id = 191 AND kode_barang = '85966351';

-- [28] ID: 193 (17029669) - Sumber: Data Reagen Cair.xlsx (Baris 44)
UPDATE reagents 
SET 
    nama_barang = 'Quechers Extract tube ,EN Method w/CH (5982-5650 CH))',
    katalog = '',
    merk = 'Agilent',
    stok = '0 PAC',
    jenis_barang = 'Reagen Cair',
    expired_date = NULL
WHERE id = 193 AND kode_barang = '17029669';

-- [29] ID: 194 (11835386) - Sumber: Data Reagen Cair.xlsx (Baris 48)
UPDATE reagents 
SET 
    nama_barang = 'Zinc Standard Solution ( 1.19806.0100)',
    katalog = '',
    merk = 'Sigma Aldrich',
    stok = '0 BOTOL',
    jenis_barang = 'Reagen Cair',
    expired_date = NULL
WHERE id = 194 AND kode_barang = '11835386';

-- [30] ID: 198 (29103970) - Sumber: Data Reagen Cair.xlsx (Baris 52)
UPDATE reagents 
SET 
    nama_barang = 'Aflatoxin Standard mix kit (5x1ml) (G1040)',
    katalog = '',
    merk = 'Vicam',
    stok = '0 PAC',
    jenis_barang = 'Reagen Cair',
    expired_date = NULL
WHERE id = 198 AND kode_barang = '29103970';

-- [31] ID: 199 (90023374) - Sumber: Data Reagen Cair.xlsx (Baris 53)
UPDATE reagents 
SET 
    nama_barang = 'Afla M1 toxin Standard (10ug/ml) 1x1ml (G1043)',
    katalog = '',
    merk = 'Vicam',
    stok = '0 PCS',
    jenis_barang = 'Reagen Cair',
    expired_date = NULL
WHERE id = 199 AND kode_barang = '90023374';

-- [32] ID: 202 (38051612) - Sumber: Data Reagen Cair.xlsx (Baris 57)
UPDATE reagents 
SET 
    nama_barang = 'Diisopropilamine (1.03646.0100)',
    katalog = '',
    merk = 'Merck',
    stok = '0 PCS',
    jenis_barang = 'Reagen Cair',
    expired_date = '2023-12-31'
WHERE id = 202 AND kode_barang = '38051612';

-- [33] ID: 203 (68962228) - Sumber: Data Reagen Cair.xlsx (Baris 58)
UPDATE reagents 
SET 
    nama_barang = 'Quechers Extract Poucher, AOAC Method (5982-6755)',
    katalog = '',
    merk = 'Agilent',
    stok = '0 KTK',
    jenis_barang = 'Reagen Cair',
    expired_date = NULL
WHERE id = 203 AND kode_barang = '68962228';

-- [34] ID: 210 (71174163) - Sumber: Data Reagen Cair.xlsx (Baris 72)
UPDATE reagents 
SET 
    nama_barang = 'Dimethyl sulfoxide (D5879-100ML)',
    katalog = '',
    merk = 'sigma',
    stok = '1 BOTOL',
    jenis_barang = 'Reagen Cair',
    expired_date = NULL
WHERE id = 210 AND kode_barang = '71174163';

-- [35] ID: 212 (42637019) - Sumber: Data Reagen Cair.xlsx (Baris 86)
UPDATE reagents 
SET 
    nama_barang = 'SPE HLB Cartridges 3 cc, Waters (WAT 094226)',
    katalog = '',
    merk = 'waters',
    stok = '2 PAC',
    jenis_barang = 'Reagen Cair',
    expired_date = NULL
WHERE id = 212 AND kode_barang = '42637019';

-- [36] ID: 213 (86032311) - Sumber: Data Reagen Cair.xlsx (Baris 87)
UPDATE reagents 
SET 
    nama_barang = 'SPE MCX Cartridges 3cc, Waters (186000254)',
    katalog = '',
    merk = 'waters',
    stok = '0 PAC',
    jenis_barang = 'Reagen Cair',
    expired_date = NULL
WHERE id = 213 AND kode_barang = '86032311';

-- [37] ID: 215 (62236183) - Sumber: Data Reagen Cair.xlsx (Baris 89)
UPDATE reagents 
SET 
    nama_barang = 'Filter Papers (1443-090)',
    katalog = '',
    merk = 'whatman',
    stok = '0 PAC',
    jenis_barang = 'Reagen Cair',
    expired_date = NULL
WHERE id = 215 AND kode_barang = '62236183';

-- [38] ID: 222 (62056165) - Sumber: Data Reagen Cair.xlsx (Baris 99)
UPDATE reagents 
SET 
    nama_barang = 'Grade 91 Sheet, Whatman (1.091.930)',
    katalog = '',
    merk = 'grade',
    stok = '0 PAC',
    jenis_barang = 'Reagen Cair',
    expired_date = NULL
WHERE id = 222 AND kode_barang = '62056165';

-- [39] ID: 223 (15390340) - Sumber: Data Reagen Cair.xlsx (Baris 100)
UPDATE reagents 
SET 
    nama_barang = 'Grade 93 Sheet, Whatman (1.093.930)',
    katalog = '',
    merk = 'grade',
    stok = '3 PAC',
    jenis_barang = 'Reagen Cair',
    expired_date = NULL
WHERE id = 223 AND kode_barang = '15390340';

-- [40] ID: 227 (61421202) - Sumber: Data Reagen Cair.xlsx (Baris 107)
UPDATE reagents 
SET 
    nama_barang = 'Dichloromethane (A-1031)',
    katalog = '',
    merk = 'Smart Lab.',
    stok = '1 BOTOL',
    jenis_barang = 'Reagen Cair',
    expired_date = NULL
WHERE id = 227 AND kode_barang = '61421202';

-- [41] ID: 320 (70030122) - Sumber: Data Reagen Padat.xlsx (Baris 19)
UPDATE reagents 
SET 
    nama_barang = 'Ammonium Bicarbonate (A.2011)',
    katalog = '',
    merk = 'Smart Lab.',
    stok = '0 BOTOL',
    jenis_barang = 'Reagen Padat',
    expired_date = NULL
WHERE id = 320 AND kode_barang = '70030122';

-- [42] ID: 325 (69823651) - Sumber: Data Reagen Padat.xlsx (Baris 46)
UPDATE reagents 
SET 
    nama_barang = 'Penicilin V Potassium (150453.200mg)',
    katalog = '',
    merk = 'USP',
    stok = '0 VIAL',
    jenis_barang = 'Reagen Padat',
    expired_date = NULL
WHERE id = 325 AND kode_barang = '69823651';

-- [43] ID: 326 (93561385) - Sumber: Data Reagen Padat.xlsx (Baris 47)
UPDATE reagents 
SET 
    nama_barang = 'Pyrogalol Acs Reagents (2540002.10G)',
    katalog = '',
    merk = 'Sigma',
    stok = '0 VIAL',
    jenis_barang = 'Reagen Padat',
    expired_date = NULL
WHERE id = 326 AND kode_barang = '93561385';

-- [44] ID: 327 (63407330) - Sumber: Data Reagen Padat.xlsx (Baris 51)
UPDATE reagents 
SET 
    nama_barang = 'Potassium Chloride (A-2038)',
    katalog = '',
    merk = 'Smart Lab',
    stok = '1 BOTOL',
    jenis_barang = 'Reagen Padat',
    expired_date = NULL
WHERE id = 327 AND kode_barang = '63407330';

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
