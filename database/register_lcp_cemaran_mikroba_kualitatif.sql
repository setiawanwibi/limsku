-- ==============================================================================
-- SKRIP IDEMPOTEN PENDAFTARAN TEMPLATE RESMI LCP CEMARAN MIKROBA KUALITATIF
-- Kode Dokumen: POM-06.02/CFM.01/SOP.01/F.01B.13
-- Target Database: limsku
-- ==============================================================================
-- PENTING:
-- 1. Jangan jalankan skrip ini sebelum melakukan backup database limsku:
--    mysqldump -u root -p limsku > backup_limsku_sebelum_lcp_mikroba.sql
-- 2. Skrip ini bersifat IDEMPOTEN (menggunakan INSERT ... ON DUPLICATE KEY UPDATE)
--    berbasis kode_template 'MIK-04' yang unik.
-- 3. method_id dan parameter_id dibiarkan NULL karena belum ada pemetaan
--    terverifikasi dengan monografi spesifik (menghindari asumsi relasi).
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
    'MIK-04',
    'Uji Cemaran Mikroba Kualitatif Sediaan Farmasi',
    'Mikrobiologi',
    NULL,
    NULL,
    '{
  "kode_dokumen": "POM-06.02/CFM.01/SOP.01/F.01B.13",
  "judul": "Cemaran Mikroba Kualitatif Sediaan Farmasi",
  "kategori": "Mikrobiologi",
  "sections": [
    {
      "id": "target_mikroba",
      "title": "Target Cemaran Mikroba yang Diuji",
      "description": "Pilih satu atau lebih target cemaran mikroba sesuai acuan pengujian sampel.",
      "fields": [
        {"id": "target_staphylococcus", "label": "Staphylococcus aures", "type": "checkbox", "default": false, "keterangan": "Ejaan dokumen resmi: Staphylococcus aures"},
        {"id": "target_pseudomonas", "label": "Pseudomonas aeruginosa", "type": "checkbox", "default": false},
        {"id": "target_candida", "label": "Candida albicans", "type": "checkbox", "default": false},
        {"id": "target_shigella", "label": "Shigella sp", "type": "checkbox", "default": false},
        {"id": "target_salmonella", "label": "Salmonella sp", "type": "checkbox", "default": false},
        {"id": "target_ecoli", "label": "E.coli", "type": "checkbox", "default": false},
        {"id": "target_lainnya_check", "label": "Lainnya", "type": "checkbox", "default": false},
        {"id": "target_lainnya_text", "label": "Mikroba Lainnya", "type": "text", "placeholder": "Sebutkan mikroba target lainnya...", "depends_on": "target_lainnya_check"},
        {"id": "lab_lokasi", "label": "Laboratorium Penguji", "type": "static_text", "value": "Laboratorium Mikrobiologi Balai Besar POM di Bandar Lampung"}
      ]
    },
    {
      "id": "kesesuaian_metode",
      "title": "Hasil Uji Kesesuaian Metode",
      "description": "Kontrol kesesuaian metode pelarut dan galur baku pembanding.",
      "fields": [
        {
          "id": "pelarut_kesesuaian",
          "label": "Pelarut yang Sesuai",
          "type": "radio",
          "options": [
            "MLB",
            "TSB",
            "TSB + 30 g/L polysorbate 80",
            "TSB + 30 g/L polysorbate 80 + 3 g/L Lesitin",
            "Lainnya"
          ]
        },
        {"id": "pelarut_kesesuaian_lainnya", "label": "Pelarut Lainnya", "type": "text", "placeholder": "Sebutkan pelarut lainnya...", "depends_on": "pelarut_kesesuaian=Lainnya"},
        {"id": "baku_staphylococcus", "label": "Staphylococcus aures ATCC / WDCM", "type": "checkbox", "default": false},
        {"id": "baku_staphylococcus_no", "label": "No. ATCC/WDCM S. aureus", "type": "text", "placeholder": "Misal: ATCC 6538"},
        {"id": "baku_pseudomonas", "label": "Pseudomonas aeruginosa ATCC / WDCM", "type": "checkbox", "default": false},
        {"id": "baku_pseudomonas_no", "label": "No. ATCC/WDCM P. aeruginosa", "type": "text", "placeholder": "Misal: ATCC 9027"},
        {"id": "baku_candida", "label": "Candida albicans ATCC / WDCM", "type": "checkbox", "default": false},
        {"id": "baku_candida_no", "label": "No. ATCC/WDCM C. albicans", "type": "text", "placeholder": "Misal: ATCC 10231"},
        {"id": "baku_lainnya", "label": "Mikroba Baku Lainnya", "type": "text", "placeholder": "Galur baku ATCC/WDCM lainnya..."}
      ]
    },
    {
      "id": "homogenisasi_awal",
      "title": "Homogenisasi Awal",
      "description": "Pencatatan penimbangan sampel dan volume pelarut suspensi.",
      "fields": [
        {"id": "bobot_sampel", "label": "Penimbangan/Pemipetan Sampel", "type": "number", "placeholder": "10.0 (acuan SOP)", "unit": "g atau mL", "required": true},
        {"id": "volume_pelarut", "label": "Pelarut", "type": "number", "placeholder": "90.0 (acuan SOP)", "unit": "mL", "required": true},
        {
          "id": "jenis_pelarut_homogenisasi",
          "label": "Pilihan Pelarut Homogenisasi",
          "type": "radio",
          "options": [
            "MLB",
            "TSB",
            "TSB + 30 g/L polysorbate 80",
            "TSB + 30 g/L polysorbate 80 + 3 g/L Lesitin",
            "Lainnya"
          ]
        },
        {"id": "pelarut_homogenisasi_lainnya", "label": "Pelarut Homogenisasi Lainnya", "type": "text", "placeholder": "Sebutkan jenis pelarut lainnya...", "depends_on": "jenis_pelarut_homogenisasi=Lainnya"}
      ]
    },
    {
      "id": "tahapan_pengujian",
      "title": "Matriks Tahapan Pengujian",
      "description": "Alur bertahap dari pra-pengayaan, pengayaan, seleksi, isolasi, hingga konfirmasi.",
      "fields": [
        {
          "id": "inokulum_tingkat",
          "label": "Tingkat Inokulum",
          "type": "radio",
          "options": ["1 g atau mL", "0,1 g atau mL", "Lainnya"]
        },
        {
          "id": "tabel_tahapan",
          "label": "Tabel Pengamatan Tahap Uji",
          "type": "table",
          "columns": [
            {"id": "tahap", "label": "Tahapan Uji", "type": "text", "readonly": true},
            {"id": "tanggal", "label": "Tanggal Uji", "type": "date"},
            {"id": "media", "label": "Media", "type": "text"},
            {"id": "suhu", "label": "Suhu (°C)", "type": "text"},
            {"id": "waktu", "label": "Waktu (Jam)", "type": "text"},
            {"id": "sampel_spike", "label": "Sampel + Spike", "type": "text"},
            {"id": "media_spike", "label": "Media + Spike (Kontrol Positif)", "type": "text"},
            {"id": "kontrol_media", "label": "Kontrol Media (Sterilitas)", "type": "text"},
            {"id": "sampel_uji", "label": "Sampel Uji", "type": "text"}
          ],
          "default_rows": [
            {"tahap": "Pra Pengayaan"},
            {"tahap": "Pengayaan / Pengayaan Selektif"},
            {"tahap": "Seleksi"},
            {"tahap": "Sub Kultur / Isolasi"},
            {"tahap": "Konfirmasi"}
          ]
        }
      ]
    },
    {
      "id": "pedoman_koloni",
      "title": "Pedoman Kriteria Koloni Spesifik (Referensi Visual)",
      "description": "Acuan visual morfologi koloni pada media isolasi agar.",
      "type": "reference_guide",
      "items": [
        {
          "mikroba": "Staphylococcus aureus",
          "kriteria": {
            "BPA": "Koloni cembung, warna hitam mengkilat dikelilingi zona bening.",
            "VJA": "Koloni cembung, warna hitam mengkilat.",
            "MSA": "Koloni cembung, warna kuning."
          }
        },
        {
          "mikroba": "Pseudomonas aeruginosa",
          "kriteria": {
            "CETA": "Koloni berwarna kuning kehijauan dengan fluoresensi di bawah lampu UV."
          }
        },
        {
          "mikroba": "Candida albicans",
          "kriteria": {
            "SDA": "Koloni berwarna putih sampai krem, permukaan cembung dan licin."
          }
        },
        {
          "mikroba": "Salmonella spp",
          "kriteria": {
            "BGA": "Koloni kecil, tidak berwarna / merah muda translusen dgn lingkaran merah.",
            "XLD": "Koloni berwarna merah dengan atau tanpa bintik hitam di tengah."
          }
        },
        {
          "mikroba": "Shigella spp",
          "kriteria": {
            "XLD": "Koloni bulat putih-merah muda, media berubah menjadi merah.",
            "MCA": "Koloni bergerigi warna putih oranye, media berubah menjadi oranye."
          }
        },
        {
          "mikroba": "Escherichia coli",
          "kriteria": {
            "MCA": "Koloni merah muda hingga merah dikelilingi zona presipitasi merah gelap."
          }
        }
      ]
    },
    {
      "id": "hasil_dan_kesimpulan",
      "title": "Hasil Uji, Spesifikasi, & Kesimpulan",
      "description": "Ringkasan eksekutif pengujian untuk integrasi CP-SIPT dan LHU.",
      "fields": [
        {"id": "hasil_uji", "label": "Hasil Uji", "type": "textarea", "placeholder": "Misal: Negatif per 10 g / Tidak ditemukan Salmonella sp", "required": true},
        {"id": "pustaka_metode", "label": "Pustaka/Metode", "type": "text", "placeholder": "Misal: Farmakope Indonesia Edisi VI (2020)", "required": true},
        {"id": "syarat", "label": "Syarat", "type": "text", "placeholder": "Misal: Negatif / Harus bebas dari E. coli", "required": true},
        {
          "id": "kesimpulan_lcp",
          "label": "Kesimpulan LCP",
          "type": "select",
          "options": [
            "Memenuhi Syarat (MS)",
            "Tidak Memenuhi Syarat (TMS)",
            "Hasil Pengujian Seperti Tersebut (HPST)"
          ],
          "required": true
        }
      ]
    },
    {
      "id": "metadata_pengesahan",
      "title": "Pengesahan & Identitas Dokumen",
      "description": "Metadata tanda tangan berjenjang sesuai alur mutu laboratorium.",
      "fields": [
        {"id": "nomor_formulir", "label": "Kode Dokumen Formulir", "type": "static_text", "value": "POM-06.02/CFM.01/SOP.01/F.01B.13"},
        {"id": "penguji_nama", "label": "Tanda Tangan Penguji", "type": "display_bound", "source": "users.nama_lengkap"},
        {"id": "penguji_tanggal", "label": "Tanggal Selesai Pengujian", "type": "display_bound", "source": "testing_sessions.waktu_selesai"},
        {"id": "penyelia_nama", "label": "Diperiksa oleh (Penyelia)", "type": "display_bound", "source": "users.nama_lengkap"},
        {"id": "penyelia_tanggal", "label": "Tanggal Verifikasi Penyelia", "type": "display_bound", "source": "testing_sessions.waktu_verifikasi"}
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
    `status`        = VALUES(`status`),
    `updated_at`    = NOW();
