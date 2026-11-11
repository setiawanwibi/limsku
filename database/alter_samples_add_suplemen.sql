-- ==============================================================================
-- MIGRASI ENUM KOMODITI TABEL SAMPLES (MENAMBAHKAN SUPLEMEN)
-- Target Database: limsku
-- ==============================================================================

USE `limsku`;

ALTER TABLE `samples` 
MODIFY COLUMN `komoditi` ENUM('obat','pangan','herbal','kosmetik','suplemen') NOT NULL DEFAULT 'obat';

