-- ========================================================================
-- MIGRASI DATABASE: PENYESUAIAN STRUKTUR TABEL REAGENTS (LIMSKU)
-- Tanggal: 14 Oktober 2026
-- Tabel: reagents
-- Kolom Standar: No (tampilan), Kode Barang, Katalog, Nama Barang, Merk,
--                Stok (kuantitas + satuan), Jenis Barang, Expired Date
-- ========================================================================

-- 1. Tambahkan kolom baru yang dibutuhkan
ALTER TABLE `reagents`
    ADD COLUMN IF NOT EXISTS `kode_barang` VARCHAR(50) NULL AFTER `id`,
    ADD COLUMN IF NOT EXISTS `katalog` VARCHAR(100) NULL AFTER `kode_barang`,
    ADD COLUMN IF NOT EXISTS `nama_barang` VARCHAR(255) NULL AFTER `katalog`,
    ADD COLUMN IF NOT EXISTS `stok` VARCHAR(50) NULL AFTER `merk`,
    ADD COLUMN IF NOT EXISTS `jenis_barang` VARCHAR(100) NULL AFTER `stok`,
    ADD COLUMN IF NOT EXISTS `expired_date` DATE NULL AFTER `jenis_barang`;

-- 2. Migrasi data lama (jika ada): sinkronkan nama_reagen ke nama_barang
UPDATE `reagents` 
SET `nama_barang` = `nama_reagen` 
WHERE (`nama_barang` IS NULL OR `nama_barang` = '') AND `nama_reagen` IS NOT NULL;

-- 3. Beri nilai default jenis_barang & stok untuk data lama yang belum lengkap
UPDATE `reagents`
SET `jenis_barang` = 'Reagen Cair'
WHERE `jenis_barang` IS NULL OR `jenis_barang` = '';

UPDATE `reagents`
SET `stok` = '0 BOTOL'
WHERE `stok` IS NULL OR `stok` = '';

-- 4. Tambahkan indeks untuk performa pencarian dan filter
ALTER TABLE `reagents`
    ADD INDEX IF NOT EXISTS `idx_reagents_kode_barang` (`kode_barang`),
    ADD INDEX IF NOT EXISTS `idx_reagents_katalog` (`katalog`),
    ADD INDEX IF NOT EXISTS `idx_reagents_nama_barang` (`nama_barang`),
    ADD INDEX IF NOT EXISTS `idx_reagents_jenis_barang` (`jenis_barang`),
    ADD INDEX IF NOT EXISTS `idx_reagents_expired_date` (`expired_date`);

-- 5. Tambahkan permission reagen_import
INSERT IGNORE INTO `permissions` (`nama_permission`, `deskripsi`, `kategori`) VALUES 
('reagen_import', 'Import data master reagen via Excel', 'Master Reagen');

-- 6. Berikan permission ke role Admin TI (role_id = 1)
INSERT IGNORE INTO `role_permissions` (`role_id`, `permission_id`) 
SELECT 1, `id` FROM `permissions` WHERE `nama_permission` = 'reagen_import';
