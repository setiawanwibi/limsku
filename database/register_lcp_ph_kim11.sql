-- ==============================================================================
-- SKRIP IDEMPOTEN PENDAFTARAN / UPDATE TEMPLATE LCP PENETAPAN pH (KIM-11)
-- Kode Dokumen: POM-06.02/CFM.01/SOP.01/F.01A.27
-- Target Database: limsku
-- ==============================================================================

USE `limsku`;

INSERT INTO `form_templates` (
    `kode_template`,
    `nama_template`,
    `kategori`,
    `method_id`,
    `parameter_id`,
    `skema_form`,
    `status`,
    `created_at`,
    `updated_at`
) VALUES (
    'KIM-11',
    'Penetapan pH',
    'Kimia',
    NULL,
    NULL,
    '{"kode_dokumen":"POM-06.02/CFM.01/SOP.01/F.01A.27","judul":"LAMPIRAN CATATAN PENGUJIAN - pH","kategori":"Kimia","sections":[{"id":"info_alat_ph","title":"Instrumen pH Meter & Larutan Dapar Baku","description":"Formulir resmi POM-06.02/CFM.01/SOP.01/F.01A.27. Instrumen pH meter dan kalibrasi dapar baku.","fields":[{"id":"merk_alat","label":"Merk Alat pH Meter","type":"text","placeholder":"Contoh: Mettler Toledo / Hanna Instruments / Horiba"},{"id":"tipe_seri","label":"Tipe / Seri","type":"text","placeholder":"Contoh: SevenCompact S210"},{"id":"larutan_dapar_baku","label":"Larutan Dapar Baku Kalibrasi","type":"text","placeholder":"Contoh: pH 4.01, pH 7.00, pH 10.01"}]},{"id":"pengukuran_ph","title":"Pengukuran pH Larutan Uji","description":"Pengukuran pH replikasi 1 dan 2. Rata-rata dihitung otomatis: (pH 1 + pH 2) / 2.","fields":[{"id":"ph_uji_1","label":"Larutan Uji 1 (pH)","type":"number","step":"any","placeholder":"Contoh: 6.85","required":true},{"id":"ph_uji_2","label":"Larutan Uji 2 (pH)","type":"number","step":"any","placeholder":"Contoh: 6.87","required":true},{"id":"ph_rata_rata","label":"Rata-rata pH (Auto)","type":"number","step":"any","readonly":true}]},{"id":"evaluasi_ph","title":"Pustaka Persyaratan","description":"Pustaka acuan monografi dan syarat rentang pH yang dipersyaratkan.","fields":[{"id":"pustaka","label":"Pustaka Acuan","type":"text","placeholder":"Contoh: Farmakope Indonesia Edisi VI Halaman ...","default":"FI VI"},{"id":"syarat_ph","label":"Syarat pH Monografi","type":"text","placeholder":"Contoh: Antara 6.5 dan 7.5","required":true},{"id":"tanggal_laporan","label":"Tanggal Laporan","type":"date","required":true}]}]}',
    'Aktif',
    NOW(),
    NOW()
)
ON DUPLICATE KEY UPDATE
    `nama_template` = VALUES(`nama_template`),
    `kategori` = VALUES(`kategori`),
    `skema_form` = VALUES(`skema_form`),
    `status` = 'Aktif',
    `updated_at` = NOW();
