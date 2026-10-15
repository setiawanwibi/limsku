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

-- [1] ID: 8 (34022294)
UPDATE reagents 
SET 
    nama_barang = '3 VIAL',
    katalog = 'Asam Aminobenzoat (2070051)',
    merk = 'Baku Pembanding',
    stok = '0 BOTOL',
    jenis_barang = 'Reagen Cair',
    expired_date = NULL
WHERE id = 8 AND kode_barang = '34022294';

-- [2] ID: 9 (21443077)
UPDATE reagents 
SET 
    nama_barang = '1 VIAL',
    katalog = 'Asam Folat (B0314004)',
    merk = 'Baku Pembanding',
    stok = '0 BOTOL',
    jenis_barang = 'Reagen Cair',
    expired_date = NULL
WHERE id = 9 AND kode_barang = '21443077';

-- [3] ID: 10 (11679155)
UPDATE reagents 
SET 
    nama_barang = '2 VIAL',
    katalog = 'Acidum nicotinicum (100015)',
    merk = 'Baku Pembanding',
    stok = '0 BOTOL',
    jenis_barang = 'Reagen Cair',
    expired_date = NULL
WHERE id = 10 AND kode_barang = '11679155';

-- [4] ID: 11 (54344487)
UPDATE reagents 
SET 
    nama_barang = '4 VIAL',
    katalog = 'Alfa Tokoferol Asetat (2083321)',
    merk = 'Baku Pembanding',
    stok = '0 BOTOL',
    jenis_barang = 'Reagen Cair',
    expired_date = NULL
WHERE id = 11 AND kode_barang = '54344487';

-- [5] ID: 12 (35446903)
UPDATE reagents 
SET 
    nama_barang = '0 VIAL',
    katalog = 'Alopurinol (208028)',
    merk = 'Baku Pembanding',
    stok = '0 BOTOL',
    jenis_barang = 'Reagen Cair',
    expired_date = NULL
WHERE id = 12 AND kode_barang = '35446903';

-- [6] ID: 14 (44720835)
UPDATE reagents 
SET 
    nama_barang = '5 VIAL',
    katalog = 'Buthylis Parabenum (205068)',
    merk = 'Baku Pembanding',
    stok = '0 BOTOL',
    jenis_barang = 'Reagen Cair',
    expired_date = NULL
WHERE id = 14 AND kode_barang = '44720835';

-- [7] ID: 15 (94151222)
UPDATE reagents 
SET 
    nama_barang = '6 VIAL',
    katalog = 'Hexachlorophenum (207165)',
    merk = 'Baku Pembanding',
    stok = '0 BOTOL',
    jenis_barang = 'Reagen Cair',
    expired_date = NULL
WHERE id = 15 AND kode_barang = '94151222';

-- [8] ID: 16 (51531441)
UPDATE reagents 
SET 
    nama_barang = 'Zink Pirition (B0114350)',
    katalog = NULL,
    merk = '-',
    stok = '4 VIAL',
    jenis_barang = 'Baku Pembanding',
    expired_date = '0000-00-00'
WHERE id = 16 AND kode_barang = '51531441';

-- [9] ID: 18 (76185601)
UPDATE reagents 
SET 
    nama_barang = 'Propil paraben/Nipasol (B0314174)',
    katalog = NULL,
    merk = NULL,
    stok = '1 VIAL',
    jenis_barang = 'Baku Pembanding',
    expired_date = '0000-00-00'
WHERE id = 18 AND kode_barang = '76185601';

-- [10] ID: 37 (72464452)
UPDATE reagents 
SET 
    nama_barang = 'Merck',
    katalog = 'Urea agar (base) (1.08492.0500)',
    merk = '0 BOTOL',
    stok = 'Media Mikrobiologi',
    jenis_barang = 'Reagen Cair',
    expired_date = NULL
WHERE id = 37 AND kode_barang = '72464452';

-- [11] ID: 46 (31406226)
UPDATE reagents 
SET 
    nama_barang = 'sigma',
    katalog = 'Corn Meal Agar (42347-500G-F)',
    merk = '0 BOTOL',
    stok = 'Media Mikrobiologi',
    jenis_barang = 'Reagen Cair',
    expired_date = NULL
WHERE id = 46 AND kode_barang = '31406226';

-- [12] ID: 48 (74490778)
UPDATE reagents 
SET 
    nama_barang = 'HIMEDIA',
    katalog = 'Letheen Broth (M165-500G)',
    merk = '0 BOTOL',
    stok = 'Media Mikrobiologi',
    jenis_barang = '46234',
    expired_date = NULL
WHERE id = 48 AND kode_barang = '74490778';

-- [13] ID: 50 (23427054)
UPDATE reagents 
SET 
    nama_barang = 'Millipore',
    katalog = 'Palcam Listeria selective agar (75977-500G)',
    merk = '0 BOTOL',
    stok = 'Media Mikrobiologi',
    jenis_barang = '45948',
    expired_date = NULL
WHERE id = 50 AND kode_barang = '23427054';

-- [14] ID: 51 (71703036)
UPDATE reagents 
SET 
    nama_barang = 'sigma millipore',
    katalog = 'Enterobacteria enrichment Broth (1.05403.0500)',
    merk = '0 BOTOL',
    stok = 'Media Mikrobiologi',
    jenis_barang = '46295',
    expired_date = NULL
WHERE id = 51 AND kode_barang = '71703036';

-- [15] ID: 56 (19424412)
UPDATE reagents 
SET 
    nama_barang = 'Remel',
    katalog = 'RapID Inoculation Fluid 2ml.',
    merk = '0 PAC',
    stok = 'Media Mikrobiologi',
    jenis_barang = 'Reagen Cair',
    expired_date = NULL
WHERE id = 56 AND kode_barang = '19424412';

-- [16] ID: 58 (31648922)
UPDATE reagents 
SET 
    nama_barang = 'Sigma-Millipore',
    katalog = 'XLD agar (1.05287.0500)',
    merk = '0 BOTOL',
    stok = 'Media Mikrobiologi',
    jenis_barang = '46691',
    expired_date = NULL
WHERE id = 58 AND kode_barang = '31648922';

-- [17] ID: 66 (19550384)
UPDATE reagents 
SET 
    nama_barang = 'Millipore, Sigma-Aldrich',
    katalog = '2,3,5-Triphenyltetrazoliumchloride for microbiology (1.08380.0010)',
    merk = '0 BOTOL',
    stok = 'Media Mikrobiologi',
    jenis_barang = '45808',
    expired_date = NULL
WHERE id = 66 AND kode_barang = '19550384';

-- [18] ID: 67 (87298069)
UPDATE reagents 
SET 
    nama_barang = 'Millipore',
    katalog = 'Bactident Coagulase Rabbit (1.13306.0001)',
    merk = '8 PAC',
    stok = 'Media Mikrobiologi',
    jenis_barang = '46387',
    expired_date = NULL
WHERE id = 67 AND kode_barang = '87298069';

-- [19] ID: 69 (17538933)
UPDATE reagents 
SET 
    nama_barang = 'Millipore',
    katalog = 'Bactident Oxidase (1.00181.0001)',
    merk = '0 PAC',
    stok = 'Media Mikrobiologi',
    jenis_barang = '45596',
    expired_date = NULL
WHERE id = 69 AND kode_barang = '17538933';

-- [20] ID: 72 (32997799)
UPDATE reagents 
SET 
    nama_barang = 'One Med',
    katalog = 'Kapas Berlemak 500 gram',
    merk = '19 PCS',
    stok = 'Media Mikrobiologi',
    jenis_barang = 'Reagen Cair',
    expired_date = NULL
WHERE id = 72 AND kode_barang = '32997799';

-- [21] ID: 75 (53309530)
UPDATE reagents 
SET 
    nama_barang = 'Himedia',
    katalog = 'Bile Esculin Azide Agar (M493l-500G)',
    merk = '1 BOTOL',
    stok = 'Media Mikrobiologi',
    jenis_barang = 'Reagen Cair',
    expired_date = NULL
WHERE id = 75 AND kode_barang = '53309530';

-- [22] ID: 80 (67294277)
UPDATE reagents 
SET 
    nama_barang = 'Triple Sugar Iron agar (M021-500G)',
    katalog = 'M021-500G',
    merk = '0 BOTOL',
    stok = 'Media Mikrobiologi',
    jenis_barang = '46903',
    expired_date = NULL
WHERE id = 80 AND kode_barang = '67294277';

-- [23] ID: 183 (22592547)
UPDATE reagents 
SET 
    nama_barang = 'PT. Brataco',
    katalog = 'Aqua DM 20L, Non AMDK',
    merk = '0 DIRIGEN',
    stok = 'Reagen Cair',
    jenis_barang = 'Reagen Cair',
    expired_date = NULL
WHERE id = 183 AND kode_barang = '22592547';

-- [24] ID: 184 (41178184)
UPDATE reagents 
SET 
    nama_barang = 'PT. Widatra Bhakti',
    katalog = 'Sterile Water for Irrigation 1000 mL',
    merk = '0 BOTOL',
    stok = 'Reagen Cair',
    jenis_barang = 'Reagen Cair',
    expired_date = NULL
WHERE id = 184 AND kode_barang = '41178184';

-- [25] ID: 186 (50611487)
UPDATE reagents 
SET 
    nama_barang = 'Smart Lab. Indonesia',
    katalog = 'Toluene p.a (A.1075)',
    merk = '0 BOTOL',
    stok = 'Reagen Cair',
    jenis_barang = '46785',
    expired_date = NULL
WHERE id = 186 AND kode_barang = '50611487';

-- [26] ID: 188 (57033654)
UPDATE reagents 
SET 
    nama_barang = 'Merck',
    katalog = 'Dimethyl Sulfoxide ( 1.02952.1000)',
    merk = '1 BOTOL',
    stok = 'Reagen Cair',
    jenis_barang = 'Reagen Cair',
    expired_date = NULL
WHERE id = 188 AND kode_barang = '57033654';

-- [27] ID: 191 (85966351)
UPDATE reagents 
SET 
    nama_barang = 'Smart Lab. Indonesia',
    katalog = 'N,N Dimetil Formamid (A.1094)',
    merk = '1 BOTOL',
    stok = 'Reagen Cair',
    jenis_barang = '46639',
    expired_date = NULL
WHERE id = 191 AND kode_barang = '85966351';

-- [28] ID: 193 (17029669)
UPDATE reagents 
SET 
    nama_barang = 'Agilent',
    katalog = 'Quechers Extract tube ,EN Method w/CH (5982-5650 CH))',
    merk = '0 PAC',
    stok = 'Reagen Cair',
    jenis_barang = 'Reagen Cair',
    expired_date = NULL
WHERE id = 193 AND kode_barang = '17029669';

-- [29] ID: 194 (11835386)
UPDATE reagents 
SET 
    nama_barang = 'Sigma Aldrich',
    katalog = 'Zinc Standard Solution ( 1.19806.0100)',
    merk = '0 BOTOL',
    stok = 'Reagen Cair',
    jenis_barang = 'Reagen Cair',
    expired_date = NULL
WHERE id = 194 AND kode_barang = '11835386';

-- [30] ID: 198 (29103970)
UPDATE reagents 
SET 
    nama_barang = 'Vicam',
    katalog = 'Aflatoxin Standard mix kit (5x1ml) (G1040)',
    merk = '0 PAC',
    stok = 'Reagen Cair',
    jenis_barang = 'Reagen Cair',
    expired_date = NULL
WHERE id = 198 AND kode_barang = '29103970';

-- [31] ID: 199 (90023374)
UPDATE reagents 
SET 
    nama_barang = 'Vicam',
    katalog = 'Afla M1 toxin Standard (10ug/ml) 1x1ml (G1043)',
    merk = '0 PCS',
    stok = 'Reagen Cair',
    jenis_barang = 'Reagen Cair',
    expired_date = NULL
WHERE id = 199 AND kode_barang = '90023374';

-- [32] ID: 202 (38051612)
UPDATE reagents 
SET 
    nama_barang = 'Merck',
    katalog = 'Diisopropilamine (1.03646.0100)',
    merk = '0 PCS',
    stok = 'Reagen Cair',
    jenis_barang = '45291',
    expired_date = NULL
WHERE id = 202 AND kode_barang = '38051612';

-- [33] ID: 203 (68962228)
UPDATE reagents 
SET 
    nama_barang = 'Agilent',
    katalog = 'Quechers Extract Poucher, AOAC Method (5982-6755)',
    merk = '0 KTK',
    stok = 'Reagen Cair',
    jenis_barang = 'Reagen Cair',
    expired_date = NULL
WHERE id = 203 AND kode_barang = '68962228';

-- [34] ID: 210 (71174163)
UPDATE reagents 
SET 
    nama_barang = 'sigma',
    katalog = 'Dimethyl sulfoxide (D5879-100ML)',
    merk = '1 BOTOL',
    stok = 'Reagen Cair',
    jenis_barang = 'Reagen Cair',
    expired_date = NULL
WHERE id = 210 AND kode_barang = '71174163';

-- [35] ID: 212 (42637019)
UPDATE reagents 
SET 
    nama_barang = 'waters',
    katalog = 'SPE HLB Cartridges 3 cc, Waters (WAT 094226)',
    merk = '2 PAC',
    stok = 'Reagen Cair',
    jenis_barang = 'Reagen Cair',
    expired_date = NULL
WHERE id = 212 AND kode_barang = '42637019';

-- [36] ID: 213 (86032311)
UPDATE reagents 
SET 
    nama_barang = 'waters',
    katalog = 'SPE MCX Cartridges 3cc, Waters (186000254)',
    merk = '0 PAC',
    stok = 'Reagen Cair',
    jenis_barang = 'Reagen Cair',
    expired_date = NULL
WHERE id = 213 AND kode_barang = '86032311';

-- [37] ID: 215 (62236183)
UPDATE reagents 
SET 
    nama_barang = 'whatman',
    katalog = 'Filter Papers (1443-090)',
    merk = '0 PAC',
    stok = 'Reagen Cair',
    jenis_barang = 'Reagen Cair',
    expired_date = NULL
WHERE id = 215 AND kode_barang = '62236183';

-- [38] ID: 222 (62056165)
UPDATE reagents 
SET 
    nama_barang = 'grade',
    katalog = 'Grade 91 Sheet, Whatman (1.091.930)',
    merk = '0 PAC',
    stok = 'Reagen Cair',
    jenis_barang = 'Reagen Cair',
    expired_date = NULL
WHERE id = 222 AND kode_barang = '62056165';

-- [39] ID: 223 (15390340)
UPDATE reagents 
SET 
    nama_barang = 'grade',
    katalog = 'Grade 93 Sheet, Whatman (1.093.930)',
    merk = '3 PAC',
    stok = 'Reagen Cair',
    jenis_barang = 'Reagen Cair',
    expired_date = NULL
WHERE id = 223 AND kode_barang = '15390340';

-- [40] ID: 227 (61421202)
UPDATE reagents 
SET 
    nama_barang = 'Smart Lab.',
    katalog = 'Dichloromethane (A-1031)',
    merk = '1 BOTOL',
    stok = 'Reagen Cair',
    jenis_barang = 'Reagen Cair',
    expired_date = NULL
WHERE id = 227 AND kode_barang = '61421202';

-- [41] ID: 320 (70030122)
UPDATE reagents 
SET 
    nama_barang = 'Smart Lab.',
    katalog = 'Ammonium Bicarbonate (A.2011)',
    merk = '0 BOTOL',
    stok = 'Reagen Padat',
    jenis_barang = 'Reagen Cair',
    expired_date = NULL
WHERE id = 320 AND kode_barang = '70030122';

-- [42] ID: 325 (69823651)
UPDATE reagents 
SET 
    nama_barang = 'USP',
    katalog = 'Penicilin V Potassium (150453.200mg)',
    merk = '0 VIAL',
    stok = 'Reagen Padat',
    jenis_barang = 'Reagen Cair',
    expired_date = NULL
WHERE id = 325 AND kode_barang = '69823651';

-- [43] ID: 326 (93561385)
UPDATE reagents 
SET 
    nama_barang = 'Sigma',
    katalog = 'Pyrogalol Acs Reagents (2540002.10G)',
    merk = '0 VIAL',
    stok = 'Reagen Padat',
    jenis_barang = 'Reagen Cair',
    expired_date = NULL
WHERE id = 326 AND kode_barang = '93561385';

-- [44] ID: 327 (63407330)
UPDATE reagents 
SET 
    nama_barang = 'Smart Lab',
    katalog = 'Potassium Chloride (A-2038)',
    merk = '1 BOTOL',
    stok = 'Reagen Padat',
    jenis_barang = 'Reagen Cair',
    expired_date = NULL
WHERE id = 327 AND kode_barang = '63407330';

-- -----------------------------------------------------------------------------
-- 2. ROLLBACK EXPIRED_DATE NULL KE '0000-00-00' (KHUSUS RECORD AWAL YANG 0000-00-00)
-- -----------------------------------------------------------------------------

UPDATE reagents
SET expired_date = '0000-00-00'
WHERE id IN (13, 16, 17, 18, 40, 98, 103, 118, 119, 152, 153, 156, 160, 170, 187, 214, 216, 217, 218, 219, 226, 243, 244, 245, 253, 255, 257, 260, 271, 272, 276, 283, 287, 288, 289, 290, 291, 292, 297, 298, 299, 302, 313, 316, 318, 323, 329, 338, 340, 341, 346, 348, 349, 350, 352, 356, 357, 358, 359, 362, 370, 385, 387, 388, 391, 392, 393, 394);

COMMIT;
