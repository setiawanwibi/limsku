-- ==============================================================================
-- SKRIP IDEMPOTEN PENDAFTARAN TEMPLATE RESMI LCP ENDOTOKSIN BAKTERI SEMI KUANTITATIF
-- Kode Dokumen: POM-06.02/CFM.01/SOP.01/F.01B.19
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
    'MIK-05',
    'Uji Endotoksin Bakteri Semi Kuantitatif',
    'Mikrobiologi',
    NULL,
    NULL,
    '{
  "kode_dokumen": "POM-06.02/CFM.01/SOP.01/F.01B.19",
  "judul": "ENDOTOKSIN BAKTERI SEMI KUANTITATIF",
  "kategori": "Mikrobiologi",
  "sections": [
    {
      "id": "alat_dan_depirogenasi",
      "title": "Metode, Peralatan & Depirogenasi",
      "description": "Informasi metode pengujian, identifikasi alat, dan parameter proses depirogenasi alat gelas.",
      "fields": [
        {"id": "metode_uji", "label": "Metode Uji", "type": "text", "default": "Jendal Gel", "readonly": true},
        {"id": "mikropipet_1", "label": "No. ID / Tgl Kalibrasi Mikropipet 1", "type": "text", "placeholder": "Contoh: MP-01 / 15-01-2026 (100 uL)"},
        {"id": "mikropipet_2", "label": "No. ID / Tgl Kalibrasi Mikropipet 2", "type": "text", "placeholder": "Contoh: MP-02 / 15-01-2026 (1000 uL)"},
        {"id": "oven_id", "label": "No. ID / Tgl Kalibrasi Oven", "type": "text", "placeholder": "Contoh: OVN-01 / 10-02-2026"},
        {"id": "inkubator_id", "label": "No. ID / Tgl Kalibrasi Inkubator", "type": "text", "placeholder": "Contoh: INK-01 / 12-02-2026"},
        {"id": "depirogenasi_suhu", "label": "Suhu Depirogenasi Alat Gelas (°C)", "type": "text", "default": "250"},
        {"id": "depirogenasi_waktu", "label": "Waktu Depirogenasi (Menit)", "type": "text", "default": "60"}
      ]
    },
    {
      "id": "pereaksi_dan_reagen",
      "title": "Pereaksi & Kontrol Lot",
      "description": "Informasi lot, CoA, kepekaan lisin (λ), potensi CSE, dan kondisi penyimpanan.",
      "fields": [
        {"id": "nama_reagen", "label": "Nama Reagen LAL / TAL", "type": "text", "placeholder": "Contoh: Limulus Amebocyte Lysate (LAL)"},
        {"id": "lot_cse", "label": "No. Lot CSE / CoA / Kadaluarsa", "type": "text", "placeholder": "No. Lot / No. CoA / Tgl Exp"},
        {"id": "lot_lal", "label": "No. Lot LAL/TAL / CoA / Kadaluarsa", "type": "text", "placeholder": "No. Lot / No. CoA / Tgl Exp"},
        {"id": "jenis_lisin", "label": "Jenis Lisin", "type": "select", "options": ["LAL", "TAL"], "default": "LAL"},
        {"id": "potensi_cse", "label": "Potensi Endotoksin CSE (EU/mL)", "type": "text", "placeholder": "Contoh: 100"},
        {"id": "kepekaan_lambda", "label": "Kepekaan Lisin (λ) (EU/mL)", "type": "text", "placeholder": "Contoh: 0.03"},
        {"id": "tgl_uji_konfirmasi", "label": "Tgl Uji Konfirmasi CSE / LAL-TAL", "type": "date"},
        {"id": "tgl_rekonstitusi", "label": "Tgl Rekonstitusi CSE / LAL-TAL", "type": "date"},
        {"id": "suhu_simpan_cse", "label": "Suhu Penyimpanan CSE (°C)", "type": "text", "default": "2 - 8"},
        {"id": "suhu_simpan_lal", "label": "Suhu Penyimpanan LAL/TAL (°C)", "type": "text", "default": "2 - 8"}
      ]
    },
    {
      "id": "preparasi_dan_pma",
      "title": "Preparasi Sampel & Perhitungan PMA",
      "description": "Karakteristik contoh, pengenceran CSE, dan perhitungan Pengenceran Maksimum yang Sah (PMA).",
      "fields": [
        {"id": "pemerian_contoh", "label": "Pemerian Contoh", "type": "text", "placeholder": "Cairan jernih, tidak berwarna, tidak berbau"},
        {"id": "ph_contoh", "label": "pH Contoh", "type": "text", "placeholder": "Contoh: 6.8 - 7.2"},
        {"id": "batas_endotoksin", "label": "Batas Endotoksin (L)", "type": "text", "placeholder": "Contoh: 0.2"},
        {"id": "satuan_batas_endotoksin", "label": "Satuan Batas Endotoksin", "type": "select", "options": ["EU/mL", "EU/mg", "EU/Dosis"], "default": "EU/mL"},
        {"id": "konsentrasi_larutan_sampel", "label": "Konsentrasi Larutan Sampel (M)", "type": "text", "default": "1"},
        {"id": "nilai_pma", "label": "Nilai PMA Terhitung", "type": "text", "placeholder": "Contoh: 6.67 atau 1 : 6.67"},
        {"id": "skema_pengenceran_sampel", "label": "Skema Pengenceran Sampel", "type": "textarea", "placeholder": "Rincian seri pengenceran sampel (Neat, 1:2, 1:4, dst)..."},
        {"id": "skema_pengenceran_ksp", "label": "Skema Pengenceran KSP (Kontrol Sampel Positif)", "type": "textarea", "placeholder": "Rincian preparasi KSP (@ 0.9 mL sampel + 0.1 mL 20λ)..."}
      ]
    },
    {
      "id": "inkubasi_dan_pengamatan",
      "title": "Kondisi Inkubasi & Tabel Hasil Pengamatan",
      "description": "Hasil pembentukan gel (+/-) pada LRW, Kontrol Positif (2λ, λ, 1/2λ, 1/4λ), KSP, dan Sampel.",
      "fields": [
        {"id": "waktu_inkubasi", "label": "Waktu Inkubasi (Menit)", "type": "text", "default": "60 ± 2"},
        {"id": "suhu_inkubator", "label": "Suhu Inkubator (°C)", "type": "text", "default": "37 ± 1"},
        {"id": "hasil_lrw_1", "label": "Hasil LRW / WFI Duplo 1", "type": "select", "options": ["-", "+"], "default": "-"},
        {"id": "hasil_lrw_2", "label": "Hasil LRW / WFI Duplo 2", "type": "select", "options": ["-", "+"], "default": "-"},
        {"id": "hasil_kp_2lambda_1", "label": "Kontrol Positif 2λ Duplo 1", "type": "select", "options": ["+", "-"], "default": "+"},
        {"id": "hasil_kp_2lambda_2", "label": "Kontrol Positif 2λ Duplo 2", "type": "select", "options": ["+", "-"], "default": "+"},
        {"id": "hasil_kp_lambda_1", "label": "Kontrol Positif λ Duplo 1", "type": "select", "options": ["+", "-"], "default": "+"},
        {"id": "hasil_kp_lambda_2", "label": "Kontrol Positif λ Duplo 2", "type": "select", "options": ["+", "-"], "default": "+"},
        {"id": "hasil_kp_setengah_1", "label": "Kontrol Positif 1/2λ Duplo 1", "type": "select", "options": ["+", "-"], "default": "-"},
        {"id": "hasil_kp_setengah_2", "label": "Kontrol Positif 1/2λ Duplo 2", "type": "select", "options": ["+", "-"], "default": "-"},
        {"id": "hasil_kp_seperempat_1", "label": "Kontrol Positif 1/4λ Duplo 1", "type": "select", "options": ["+", "-"], "default": "-"},
        {"id": "hasil_kp_seperempat_2", "label": "Kontrol Positif 1/4λ Duplo 2", "type": "select", "options": ["+", "-"], "default": "-"},
        {"id": "tabel_pengamatan_ksp", "label": "Pengamatan KSP (Duplo)", "type": "textarea", "placeholder": "Hasil tiap tingkat pengenceran KSP (Neat, 1:2, dll)..."},
        {"id": "tabel_pengamatan_sampel", "label": "Pengamatan Sampel (Duplo)", "type": "textarea", "placeholder": "Hasil tiap tingkat pengenceran Sampel (Neat, 1:2, dll)..."}
      ]
    },
    {
      "id": "kadar_endotoksin_dan_kesimpulan",
      "title": "Kadar Endotoksin & Kesimpulan",
      "description": "Perhitungan kadar endotoksin sampel uji, acuan pustaka, dan keputusan akhir.",
      "fields": [
        {"id": "kadar_endotoksin_eu_ml", "label": "Kadar Endotoksin Sampel Uji (< EU/mL)", "type": "text", "placeholder": "Contoh: < 0.03 atau < 0.2"},
        {"id": "kadar_endotoksin_konversi", "label": "Kadar Endotoksin Terkonversi (jika bukan EU/mL)", "type": "text", "placeholder": "Hasil konversi sesuai dosis atau berat"},
        {"id": "pustaka_metode", "label": "Pustaka / Metode Acuan", "type": "text", "default": "Farmakope Indonesia VI Tahun 2020"},
        {"id": "syarat_endotoksin", "label": "Syarat Batas Endotoksin", "type": "text", "placeholder": "Contoh: < 0.2 EU/mL"},
        {"id": "kesimpulan_status", "label": "Kesimpulan", "type": "select", "options": ["Memenuhi Syarat (MS)", "Tidak Memenuhi Syarat (TMS)", "Hasil Pengujian Seperti Tersebut (HPST)"], "default": "Memenuhi Syarat (MS)"}
      ]
    }
  ]
}',
    'Aktif',
    NOW(),
    NOW()
)
ON DUPLICATE KEY UPDATE
    `nama_template` = VALUES(`nama_template`),
    `kategori`      = VALUES(`kategori`),
    `skema_form`    = VALUES(`skema_form`),
    `status`        = 'Aktif',
    `updated_at`    = NOW();
