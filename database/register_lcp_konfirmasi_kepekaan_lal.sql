-- ==============================================================================
-- SKRIP IDEMPOTEN PENDAFTARAN TEMPLATE RESMI LCP KONFIRMASI KEPEKAAN LAL
-- Kode Dokumen: POM-06.02/CFM.01/SOP.01/F.01B.20
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
    'MIK-06',
    'Konfirmasi Kepekaan LAL Semi Kuantitatif',
    'Mikrobiologi',
    NULL,
    NULL,
    '{
  "kode_dokumen": "POM-06.02/CFM.01/SOP.01/F.01B.20",
  "judul": "KONFIRMASI KEPEKAAN LAL SEMI KUANTITATIF",
  "kategori": "Mikrobiologi",
  "sections": [
    {
      "id": "identitas_reagen_lal",
      "title": "Identitas Reagen LAL & Metode",
      "description": "Informasi reagen LAL, tipe test (Multi/Single), metode pengujian, dan identitas penguji.",
      "fields": [
        {"id": "nama_dagang_reagen", "label": "Nama Dagang Reagen", "type": "text", "placeholder": "Contoh: Pyrogent Plus"},
        {"id": "tipe_reagen", "label": "Tipe Reagen", "type": "select", "options": ["Multi Test", "Single Test"], "default": "Multi Test"},
        {"id": "metode_uji", "label": "Metode", "type": "text", "default": "Jendal Gel", "readonly": true}
      ]
    },
    {
      "id": "alat_dan_depirogenasi",
      "title": "Peralatan & Depirogenasi",
      "description": "Identifikasi alat ukur dan kondisi depirogenasi alat gelas.",
      "fields": [
        {"id": "mikropipet_1", "label": "No. ID / Tgl Kalibrasi Mikropipet 1", "type": "text", "placeholder": "No. ID / Tgl Kalibrasi (uL)"},
        {"id": "mikropipet_2", "label": "No. ID / Tgl Kalibrasi Mikropipet 2", "type": "text", "placeholder": "No. ID / Tgl Kalibrasi (uL)"},
        {"id": "oven_id", "label": "No. ID / Tgl Kalibrasi Oven", "type": "text", "placeholder": "No. ID / Tgl Kalibrasi"},
        {"id": "inkubator_id", "label": "No. ID / Tgl Kalibrasi Inkubator", "type": "text", "placeholder": "No. ID / Tgl Kalibrasi"},
        {"id": "depirogenasi_suhu", "label": "Suhu Depirogenasi Alat Gelas (°C)", "type": "text", "default": "250"},
        {"id": "depirogenasi_waktu", "label": "Waktu Depirogenasi (Menit)", "type": "text", "default": "60"}
      ]
    },
    {
      "id": "pereaksi_dan_kontrol",
      "title": "Pereaksi & Kontrol Lot",
      "description": "Data lot CSE, LAL/TAL, potensi, kepekaan etiket, dan suhu penyimpanan.",
      "fields": [
        {"id": "nama_reagen_lengkap", "label": "Nama Reagen Lengkap", "type": "text", "placeholder": "Contoh: Limulus Amebocyte Lysate (LAL)"},
        {"id": "lot_cse", "label": "No. Lot CSE / CoA / Kadaluarsa", "type": "text", "placeholder": "No. Lot / No. CoA / Tgl Exp"},
        {"id": "lot_lal", "label": "No. Lot (LAL/TAL*) / CoA / Kadaluarsa", "type": "text", "placeholder": "No. Lot / No. CoA / Tgl Exp"},
        {"id": "jenis_lisin", "label": "Jenis Lisin", "type": "select", "options": ["LAL", "TAL"], "default": "LAL"},
        {"id": "potensi_cse", "label": "Potensi Endotoksin CSE (EU/mL)", "type": "text", "placeholder": "Contoh: 100"},
        {"id": "kepekaan_etiket_lambda", "label": "Kepekaan Etiket LAL (λ) (EU/mL)", "type": "text", "placeholder": "Contoh: 0.03"},
        {"id": "tgl_rekonstitusi", "label": "Tgl Rekonstitusi CSE / LAL-TAL", "type": "date"},
        {"id": "suhu_simpan_cse", "label": "Suhu Penyimpanan CSE (°C)", "type": "text", "default": "2 - 8"},
        {"id": "suhu_simpan_lal", "label": "Suhu Penyimpanan LAL/TAL (°C)", "type": "text", "default": "2 - 8"}
      ]
    },
    {
      "id": "tabel_konfirmasi_kepekaan",
      "title": "Tabel Hasil Uji Konfirmasi Kepekaan LAL (4 Pengulangan)",
      "description": "Hasil reaksi jendal gel (+/-) pada LRW, 2λ, λ, 1/2λ, 1/4λ, titik akhir, dan nilai e (log titik akhir).",
      "fields": [
        {"id": "lrw_1", "label": "LRW Rep 1", "type": "select", "options": ["-", "+"], "default": "-"},
        {"id": "lrw_2", "label": "LRW Rep 2", "type": "select", "options": ["-", "+"], "default": "-"},
        {"id": "lrw_3", "label": "LRW Rep 3", "type": "select", "options": ["-", "+"], "default": "-"},
        {"id": "lrw_4", "label": "LRW Rep 4", "type": "select", "options": ["-", "+"], "default": "-"},
        {"id": "cse_2lambda_1", "label": "2λ Rep 1", "type": "select", "options": ["+", "-"], "default": "+"},
        {"id": "cse_2lambda_2", "label": "2λ Rep 2", "type": "select", "options": ["+", "-"], "default": "+"},
        {"id": "cse_2lambda_3", "label": "2λ Rep 3", "type": "select", "options": ["+", "-"], "default": "+"},
        {"id": "cse_2lambda_4", "label": "2λ Rep 4", "type": "select", "options": ["+", "-"], "default": "+"},
        {"id": "cse_lambda_1", "label": "λ Rep 1", "type": "select", "options": ["+", "-"], "default": "+"},
        {"id": "cse_lambda_2", "label": "λ Rep 2", "type": "select", "options": ["+", "-"], "default": "+"},
        {"id": "cse_lambda_3", "label": "λ Rep 3", "type": "select", "options": ["+", "-"], "default": "+"},
        {"id": "cse_lambda_4", "label": "λ Rep 4", "type": "select", "options": ["+", "-"], "default": "+"},
        {"id": "cse_half_lambda_1", "label": "1/2λ Rep 1", "type": "select", "options": ["+", "-"], "default": "-"},
        {"id": "cse_half_lambda_2", "label": "1/2λ Rep 2", "type": "select", "options": ["+", "-"], "default": "-"},
        {"id": "cse_half_lambda_3", "label": "1/2λ Rep 3", "type": "select", "options": ["+", "-"], "default": "-"},
        {"id": "cse_half_lambda_4", "label": "1/2λ Rep 4", "type": "select", "options": ["+", "-"], "default": "-"},
        {"id": "cse_quarter_lambda_1", "label": "1/4λ Rep 1", "type": "select", "options": ["+", "-"], "default": "-"},
        {"id": "cse_quarter_lambda_2", "label": "1/4λ Rep 2", "type": "select", "options": ["+", "-"], "default": "-"},
        {"id": "cse_quarter_lambda_3", "label": "1/4λ Rep 3", "type": "select", "options": ["+", "-"], "default": "-"},
        {"id": "cse_quarter_lambda_4", "label": "1/4λ Rep 4", "type": "select", "options": ["+", "-"], "default": "-"},
        {"id": "titik_akhir_1", "label": "Titik Akhir Rep 1 (EU/mL)", "type": "text"},
        {"id": "titik_akhir_2", "label": "Titik Akhir Rep 2 (EU/mL)", "type": "text"},
        {"id": "titik_akhir_3", "label": "Titik Akhir Rep 3 (EU/mL)", "type": "text"},
        {"id": "titik_akhir_4", "label": "Titik Akhir Rep 4 (EU/mL)", "type": "text"},
        {"id": "log_e_1", "label": "e (Log Titik Akhir) Rep 1", "type": "text"},
        {"id": "log_e_2", "label": "e (Log Titik Akhir) Rep 2", "type": "text"},
        {"id": "log_e_3", "label": "e (Log Titik Akhir) Rep 3", "type": "text"},
        {"id": "log_e_4", "label": "e (Log Titik Akhir) Rep 4", "type": "text"},
        {"id": "rata_rata_e", "label": "Rata-rata e", "type": "text"},
        {"id": "nilai_gm", "label": "Geometric Mean (GM) Terhitung (EU/mL)", "type": "text", "placeholder": "Antilog(Rata-rata e)"}
      ]
    },
    {
      "id": "pustaka_dan_kesimpulan",
      "title": "Pustaka, Kriteria Keberterimaan & Kesimpulan",
      "description": "Kriteria syarat 1/2 λ ≤ GM ≤ 2 λ dan status keberterimaan pereaksi.",
      "fields": [
        {"id": "pustaka_metode", "label": "Pustaka / Metode", "type": "text", "default": "Farmakope Indonesia VI 2020 hal. 357-358"},
        {"id": "syarat_teks", "label": "Kriteria Syarat", "type": "text", "default": "1/2 λ ≤ GM (EU/mL) ≤ 2 λ", "readonly": true},
        {"id": "rentang_syarat_angka", "label": "Rentang Angka Syarat (1/2λ ≤ GM ≤ 2λ)", "type": "text", "placeholder": "Contoh: 0.015 ≤ 0.030 ≤ 0.060"},
        {"id": "kesimpulan_status", "label": "Kesimpulan", "type": "select", "options": [
          "Memenuhi Syarat (MS)",
          "Tidak Memenuhi Syarat (TMS)"
        ], "default": "Memenuhi Syarat (MS)"}
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
