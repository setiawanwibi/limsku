-- ========================================================================
-- MIGRASI DATABASE: MODUL MASTER REAGEN (LIMSKU)
-- Tanggal: 12 Oktober 2026
-- Tabel: reagents
-- ========================================================================

-- 1. Buat Tabel reagents jika belum ada
CREATE TABLE IF NOT EXISTS `reagents` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `nama_reagen` VARCHAR(150) NOT NULL,
    `merk` VARCHAR(150) DEFAULT NULL,
    `kemasan` VARCHAR(100) DEFAULT NULL,
    `status` ENUM('Aktif','Nonaktif') NOT NULL DEFAULT 'Aktif',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_reagents_status` (`status`),
    KEY `idx_reagents_nama` (`nama_reagen`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 2. Tambahkan permission untuk Master Reagen
INSERT IGNORE INTO `permissions` (`nama_permission`, `deskripsi`, `kategori`) VALUES 
('reagen_view', 'Melihat daftar master reagen', 'Master Reagen'),
('reagen_create', 'Menambah master reagen', 'Master Reagen'),
('reagen_edit', 'Mengubah data master reagen', 'Master Reagen'),
('reagen_delete', 'Menghapus/menonaktifkan master reagen', 'Master Reagen');

-- 3. Berikan permission ke role Admin TI (role_id = 1)
INSERT IGNORE INTO `role_permissions` (`role_id`, `permission_id`) 
SELECT 1, `id` FROM `permissions` WHERE `nama_permission` IN ('reagen_view', 'reagen_create', 'reagen_edit', 'reagen_delete');
