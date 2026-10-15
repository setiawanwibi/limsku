-- ========================================================================
-- MIGRASI DATABASE: MODUL MASTER ALAT LABORATORIUM (LIMSKU)
-- Tanggal: 12 Oktober 2026
-- Tabel: instruments
-- ========================================================================

-- 1. Buat Tabel instruments jika belum ada
CREATE TABLE IF NOT EXISTS `instruments` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `kode_barang` VARCHAR(50) NOT NULL,
    `nama_barang` VARCHAR(150) NOT NULL,
    `merk_tipe` VARCHAR(150) DEFAULT NULL,
    `perangkat_lunak` VARCHAR(150) DEFAULT NULL,
    `detektor` VARCHAR(150) DEFAULT NULL,
    `tanggal_perolehan` DATE DEFAULT NULL,
    `kondisi_adm` VARCHAR(100) DEFAULT NULL,
    `kode_ruangan` VARCHAR(50) DEFAULT NULL,
    `nama_ruangan` VARCHAR(150) DEFAULT NULL,
    `status` ENUM('Aktif','Nonaktif') NOT NULL DEFAULT 'Aktif',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_instruments_kode_barang` (`kode_barang`),
    KEY `idx_instruments_status` (`status`),
    KEY `idx_instruments_kode_ruangan` (`kode_ruangan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 2. Tambahkan permission untuk Alat Laboratorium
INSERT IGNORE INTO `permissions` (`nama_permission`, `deskripsi`, `kategori`) VALUES 
('alat_view', 'Melihat daftar alat laboratorium', 'Alat Laboratorium'),
('alat_create', 'Menambah alat laboratorium', 'Alat Laboratorium'),
('alat_edit', 'Mengubah data alat laboratorium', 'Alat Laboratorium'),
('alat_delete', 'Menghapus/menonaktifkan alat laboratorium', 'Alat Laboratorium'),
('alat_import', 'Import data alat laboratorium via Excel', 'Alat Laboratorium');

-- 3. Berikan permission ke role Admin TI (role_id = 1)
INSERT IGNORE INTO `role_permissions` (`role_id`, `permission_id`) 
SELECT 1, `id` FROM `permissions` WHERE `nama_permission` IN ('alat_view', 'alat_create', 'alat_edit', 'alat_delete', 'alat_import');

