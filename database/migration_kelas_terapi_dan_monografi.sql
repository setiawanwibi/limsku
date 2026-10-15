-- ========================================================================
-- MIGRASI DATABASE: MASTER KELAS TERAPI & MASTER METODE MONOGRAFI (LIMSKU)
-- Tanggal: 12 Oktober 2026
-- ========================================================================

-- 1. Buat Tabel therapeutic_classes
CREATE TABLE IF NOT EXISTS `therapeutic_classes` (
    `id` INT(11) NOT NULL AUTO_INCREMENT,
    `nama_kelas_terapi` VARCHAR(150) NOT NULL,
    `status` ENUM('Aktif','Nonaktif') NOT NULL DEFAULT 'Aktif',
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_therapeutic_classes_nama` (`nama_kelas_terapi`),
    KEY `idx_therapeutic_classes_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- 2. Tambahkan kolom monografi ke tabel methods (non-destruktif)
-- Cek keberadaan kolom sebelum menambahkan
ALTER TABLE `methods`
    ADD COLUMN IF NOT EXISTS `nama_zat_aktif` VARCHAR(150) NULL AFTER `nama_metode`,
    ADD COLUMN IF NOT EXISTS `jenis_sediaan` VARCHAR(100) NULL AFTER `nama_zat_aktif`,
    ADD COLUMN IF NOT EXISTS `bentuk_sediaan` VARCHAR(100) NULL AFTER `jenis_sediaan`,
    ADD COLUMN IF NOT EXISTS `pustaka_acuan` VARCHAR(100) NULL AFTER `bentuk_sediaan`,
    ADD COLUMN IF NOT EXISTS `parameter_uji` VARCHAR(150) NULL AFTER `pustaka_acuan`,
    ADD COLUMN IF NOT EXISTS `metode_analisa` VARCHAR(150) NULL AFTER `parameter_uji`,
    ADD COLUMN IF NOT EXISTS `persyaratan` TEXT NULL AFTER `metode_analisa`,
    ADD COLUMN IF NOT EXISTS `bidang_pengujian` ENUM('Kimia','Mikrobiologi') NOT NULL DEFAULT 'Kimia' AFTER `persyaratan`,
    ADD COLUMN IF NOT EXISTS `kelas_terapi_id` INT(11) NULL AFTER `bidang_pengujian`,
    ADD COLUMN IF NOT EXISTS `template_id` INT(11) NULL AFTER `kelas_terapi_id`,
    ADD COLUMN IF NOT EXISTS `composite_hash` VARCHAR(32) NULL AFTER `template_id`;

-- 3. Tambahkan Indeks dan Foreign Key pada tabel methods
ALTER TABLE `methods`
    ADD INDEX IF NOT EXISTS `idx_methods_zat_aktif` (`nama_zat_aktif`),
    ADD INDEX IF NOT EXISTS `idx_methods_bidang` (`bidang_pengujian`),
    ADD INDEX IF NOT EXISTS `idx_methods_kelas_terapi` (`kelas_terapi_id`),
    ADD INDEX IF NOT EXISTS `idx_methods_template` (`template_id`),
    ADD UNIQUE INDEX IF NOT EXISTS `uq_methods_composite_hash` (`composite_hash`);

-- Tambahkan foreign key jika belum ada
SET @fk_exists = (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS WHERE CONSTRAINT_NAME = 'fk_methods_kelas_terapi' AND TABLE_SCHEMA = DATABASE());
SET @sql = IF(@fk_exists = 0, 'ALTER TABLE `methods` ADD CONSTRAINT `fk_methods_kelas_terapi` FOREIGN KEY (`kelas_terapi_id`) REFERENCES `therapeutic_classes` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;', 'SELECT "FK already exists";');
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- 4. Registrasi Permission Baru untuk Master Kelas Terapi & Import Metode
INSERT IGNORE INTO `permissions` (`nama_permission`, `deskripsi`, `kategori`) VALUES 
('kelas_terapi_view', 'Melihat daftar master kelas terapi', 'Master Kelas Terapi'),
('kelas_terapi_create', 'Menambah master kelas terapi', 'Master Kelas Terapi'),
('kelas_terapi_edit', 'Mengubah master kelas terapi', 'Master Kelas Terapi'),
('kelas_terapi_delete', 'Menghapus/menonaktifkan master kelas terapi', 'Master Kelas Terapi'),
('metode_import', 'Import data master metode monografi via Excel', 'Metode');

-- 5. Berikan Hak Akses ke Admin TI (role_id = 1)
INSERT IGNORE INTO `role_permissions` (`role_id`, `permission_id`)
SELECT 1, `id` FROM `permissions` WHERE `nama_permission` IN (
    'kelas_terapi_view', 'kelas_terapi_create', 'kelas_terapi_edit', 'kelas_terapi_delete', 'metode_import'
);
