-- ==============================================================================
-- SKRIP IDEMPOTEN PENDAFTARAN & PEMBARUAN TEMPLATE RESMI LCP KIMIA
-- PARAMETER: PENETAPAN KADAR & IDENTIFIKASI (KCKT/HPLC, SPEKTROFOTOMETRI, KLT)
-- Target Database: limsku
-- ==============================================================================

USE `limsku`;

-- -----------------------------------------------------------------------------
-- Template KIM-08: Kromatografi Cair Kinerja Tinggi (HPLC/KCKT)
-- -----------------------------------------------------------------------------
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
    'KIM-08',
    'Kromatografi Cair Kinerja Tinggi (HPLC/KCKT)',
    'Kimia',
    NULL,
    NULL,
    '{
  "kode_dokumen": "POM-06.02/CFM.01/SOP.01/F.01A.12",
  "judul": "LAMPIRAN CATATAN PENGUJIAN - KROMATOGRAFI CAIR KINERJA TINGGI",
  "kategori": "Kimia",
  "sections": [
    {
      "id": "kondisi_alat_kromatografi",
      "title": "Kondisi Instrumen HPLC & Parameter Kromatografi",
      "description": "Formulir resmi POM-06.02/CFM.01/SOP.01/F.01A.12. Parameter instrumen, kolom, detektor, elusi isokratik/gradient, suhu, laju alir, panjang gelombang.",
      "fields": [
        {
          "id": "merk_alat",
          "label": "Merk Alat HPLC",
          "type": "text",
          "placeholder": "Contoh: Waters / Shimadzu / Agilent"
        },
        {
          "id": "tipe_seri",
          "label": "Tipe / Seri",
          "type": "text",
          "placeholder": "Contoh: Alliance e2695 / Prominence LC-20AT"
        },
        {
          "id": "detektor",
          "label": "Detektor",
          "type": "text",
          "placeholder": "Contoh: UV-Vis / PDA / RID / FLD"
        },
        {
          "id": "kondisi_analisis",
          "label": "Kondisi Analisis",
          "type": "select",
          "options": [
            "Sesuai prosedur",
            "Adjusment"
          ],
          "default": "Sesuai prosedur"
        },
        {
          "id": "kondisi_adjusment",
          "label": "Kondisi Adjusment (jika ada)",
          "type": "text"
        },
        {
          "id": "pelarut",
          "label": "Pelarut",
          "type": "text"
        },
        {
          "id": "suhu_opt",
          "label": "Kondisi Suhu Kolom",
          "type": "select",
          "options": [
            "Suhu Kamar",
            "Suhu Terkontrol (°C)"
          ],
          "default": "Suhu Kamar"
        },
        {
          "id": "suhu_custom",
          "label": "Suhu Kolom Terkontrol (°C)",
          "type": "number",
          "step": "any",
          "placeholder": "Contoh: 30 / 40"
        },
        {
          "id": "kolom_dimensi",
          "label": "Kolom (Ukuran partikel µm, mm x cm, material)",
          "type": "text",
          "placeholder": "Contoh: C18 5 µm (4.6 mm x 25 cm)"
        },
        {
          "id": "tipe_elusi",
          "label": "Tipe Fase Gerak",
          "type": "select",
          "options": [
            "Isokratik",
            "Gradient"
          ],
          "default": "Isokratik"
        },
        {
          "id": "fase_gerak_isokratik",
          "label": "Fase Gerak Isokratik",
          "type": "text",
          "placeholder": "Contoh: Metanol : Dapar Fosfat pH 3.0 (60 : 40)"
        },
        {
          "id": "komposisi_fase_a",
          "label": "Komposisi Fase Gerak A (opsional)",
          "type": "text",
          "placeholder": "Contoh: Asetonitril 100%"
        },
        {
          "id": "komposisi_fase_b",
          "label": "Komposisi Fase Gerak B (opsional)",
          "type": "text",
          "placeholder": "Contoh: Dapar Fosfat pH 3.0"
        },
        {
          "id": "komposisi_fase_c",
          "label": "Komposisi Fase Gerak C (opsional)",
          "type": "text",
          "placeholder": "Contoh: Metanol : Air (50:50)"
        },
        {
          "id": "tabel_gradient",
          "label": "Program Fase Gerak Gradien (jika Elusi Gradien)",
          "type": "table",
          "columns": [
            {
              "id": "waktu_menit",
              "label": "Waktu (menit)",
              "type": "number",
              "step": "any"
            },
            {
              "id": "fase_a",
              "label": "Fase Gerak A (%)",
              "type": "number",
              "step": "any"
            },
            {
              "id": "fase_b",
              "label": "Fase Gerak B (%)",
              "type": "number",
              "step": "any"
            },
            {
              "id": "fase_c",
              "label": "Fase Gerak C (%)",
              "type": "number",
              "step": "any"
            },
            {
              "id": "laju_alir",
              "label": "Laju Alir (mL/menit)",
              "type": "number",
              "step": "any"
            }
          ],
          "default_rows": [
            {
              "waktu_menit": "0",
              "fase_a": "",
              "fase_b": "",
              "fase_c": "",
              "laju_alir": "1.0"
            },
            {
              "waktu_menit": "5",
              "fase_a": "",
              "fase_b": "",
              "fase_c": "",
              "laju_alir": "1.0"
            },
            {
              "waktu_menit": "15",
              "fase_a": "",
              "fase_b": "",
              "fase_c": "",
              "laju_alir": "1.0"
            },
            {
              "waktu_menit": "20",
              "fase_a": "",
              "fase_b": "",
              "fase_c": "",
              "laju_alir": "1.0"
            }
          ]
        },
        {
          "id": "laju_aliran",
          "label": "Laju Aliran (mL/menit)",
          "type": "number",
          "step": "any",
          "placeholder": "Contoh: 1.0"
        },
        {
          "id": "panjang_gelombang",
          "label": "Panjang Gelombang λ Uji (nm)",
          "type": "number",
          "step": "any",
          "placeholder": "Contoh: 254"
        }
      ]
    },
    {
      "id": "baku_pembanding_section",
      "title": "Data Baku Pembanding & Baku Internal",
      "description": "Koreksi kadar kemurnian baku: Kb = (100 - Susut Pengeringan) * Kadar / 100",
      "fields": [
        {
          "id": "baku_nama",
          "label": "Nama Baku Pembanding",
          "type": "text"
        },
        {
          "id": "baku_no_kontrol",
          "label": "Nomor Kontrol Baku",
          "type": "text"
        },
        {
          "id": "baku_kadar",
          "label": "Kadar Baku (%) - L24",
          "type": "number",
          "step": "any",
          "placeholder": "Contoh: 99.8"
        },
        {
          "id": "baku_sp",
          "label": "Susut Pengeringan / Kadar Air (%) - O24",
          "type": "number",
          "step": "any",
          "placeholder": "Contoh: 0.2"
        },
        {
          "id": "baku_kadar_terkoreksi",
          "label": "Kadar Terkoreksi Baku (%) - T24 (Auto: (100-SP)*Kadar/100)",
          "type": "number",
          "step": "any",
          "readonly": true
        },
        {
          "id": "internal_nama",
          "label": "Nama Baku Internal (jika menggunakan)",
          "type": "text"
        },
        {
          "id": "internal_no_kontrol",
          "label": "Nomor Kontrol Baku Internal",
          "type": "text"
        },
        {
          "id": "internal_kadar",
          "label": "Kadar Baku Internal (%)",
          "type": "number",
          "step": "any"
        },
        {
          "id": "internal_sp",
          "label": "Susut Pengeringan Baku Internal (%)",
          "type": "number",
          "step": "any"
        },
        {
          "id": "internal_kadar_terkoreksi",
          "label": "Kadar Terkoreksi Baku Internal (%)",
          "type": "number",
          "step": "any",
          "readonly": true
        },
        {
          "id": "status_kromatogram_uks",
          "label": "Kromatogram Uji Kesesuaian Sistem (UKS)",
          "type": "select",
          "options": [
            "Terlampir",
            "Tidak Diperlukan"
          ],
          "default": "Terlampir"
        },
        {
          "id": "status_kromatogram_sampel",
          "label": "Kromatogram Baku dan Sampel",
          "type": "select",
          "options": [
            "Terlampir",
            "Tidak Diperlukan"
          ],
          "default": "Terlampir"
        },
        {
          "id": "status_regresi",
          "label": "Regresi Kurva Baku dan Perhitungan",
          "type": "select",
          "options": [
            "Terlampir",
            "Metode One-Point Standard"
          ],
          "default": "Terlampir"
        }
      ]
    },
    {
      "id": "tabel_penimbangan_hplc",
      "title": "Tabel Penimbangan & Respon Puncak Kromatografi",
      "description": "Perhitungan Bobot Bersih = (Wadah+Zat) - (Wadah+Sisa). Respon Puncak (Luas Area / Tinggi Puncak).",
      "fields": [
        {
          "id": "metode_perhitungan_kadar",
          "label": "Metode Perhitungan Kadar Sediaan",
          "type": "select",
          "options": [
            "Tablet / Kapsul BR (Metode 20 Tablet Digerus)",
            "Tablet Cemplung (Direct Dissolution / Effervescent)",
            "Sirup / Suspensi / Cairan Oral (Volume mL)"
          ],
          "default": "Tablet / Kapsul BR (Metode 20 Tablet Digerus)",
          "keterangan": "Pilih metode perhitungan sesuai bentuk sediaan & preparasi sampel uji."
        },
        {
          "id": "volume_takaran_etiket",
          "label": "Volume Takaran Saji Etiket (mL)",
          "type": "number",
          "step": "any",
          "default": "5.0",
          "placeholder": "Contoh: 5.0 (sendok takar 5 mL)",
          "keterangan": "Hanya untuk metode Sirup / Cairan Oral. Misal 5 mL atau 15 mL."
        },
        {
          "id": "bobot_rata_tablet",
          "label": "Bobot Rata-rata Tablet / Sediaan (Br) [mg]",
          "type": "number",
          "step": "any",
          "placeholder": "Contoh: 250.5",
          "keterangan": "Hanya untuk metode Tablet / Kapsul BR."
        },
        {
          "id": "klaim_etiket",
          "label": "Komposisi / Klaim Etiket (Ke) [mg]",
          "type": "number",
          "step": "any",
          "placeholder": "Contoh: 50.0",
          "keterangan": "Kandungan zat aktif per tablet atau per volume takaran saji etiket."
        },
        {
          "id": "faktor_konversi_bm",
          "label": "Faktor Koreksi BM/BR (BM1 / BM2)",
          "type": "text",
          "default": "1.0",
          "placeholder": "Contoh: 1.0 atau 314.4/350.87",
          "keterangan": "Dapat berupa angka desimal atau rumus pembagian langsung (misal: 314.4/350.87)"
        },
        {
          "id": "tabel_hplc",
          "type": "table",
          "label": "Data Pengukuran KCKT / HPLC",
          "columns": [
            {
              "id": "kategori",
              "label": "Kategori / Nama Zat",
              "type": "text",
              "placeholder": "Kategori / Keterangan Zat Uji (opsional)"
            },
            {
              "id": "wadah_zat",
              "label": "Wadah + Zat (g)",
              "type": "number",
              "step": "any"
            },
            {
              "id": "wadah_sisa",
              "label": "Wadah + Sisa (g)",
              "type": "number",
              "step": "any"
            },
            {
              "id": "bobot_bersih",
              "label": "Bobot Bersih (g)",
              "type": "number",
              "step": "any"
            },
            {
              "id": "faktor_pengenceran",
              "label": "Faktor Pengenceran",
              "type": "number",
              "step": "any"
            },
            {
              "id": "konsentrasi",
              "label": "Konsentrasi (Auto/Teks)",
              "type": "text",
              "placeholder": "Auto hitung dari Bobot/FP"
            },
            {
              "id": "vol_injeksi",
              "label": "Vol. Injeksi (µL)",
              "type": "number",
              "step": "any"
            },
            {
              "id": "waktu_retensi",
              "label": "Rt (menit)",
              "type": "number",
              "step": "any"
            },
            {
              "id": "respon_puncak",
              "label": "Respon Puncak (Area)",
              "type": "number",
              "step": "any"
            },
            {
              "id": "lambda_nm",
              "label": "λ (nm)",
              "type": "number",
              "step": "any"
            }
          ],
          "default_rows": [
            {
              "kategori": "Baku Pembanding",
              "wadah_zat": "",
              "wadah_sisa": "",
              "bobot_bersih": "",
              "faktor_pengenceran": "",
              "konsentrasi": "",
              "vol_injeksi": "20",
              "waktu_retensi": "",
              "respon_puncak": "",
              "lambda_nm": ""
            },
            {
              "kategori": "Baku Internal",
              "wadah_zat": "",
              "wadah_sisa": "",
              "bobot_bersih": "",
              "faktor_pengenceran": "",
              "konsentrasi": "",
              "vol_injeksi": "20",
              "waktu_retensi": "",
              "respon_puncak": "",
              "lambda_nm": ""
            },
            {
              "kategori": "Zat Uji Replikasi 1",
              "wadah_zat": "",
              "wadah_sisa": "",
              "bobot_bersih": "",
              "faktor_pengenceran": "",
              "konsentrasi": "",
              "vol_injeksi": "20",
              "waktu_retensi": "",
              "respon_puncak": "",
              "lambda_nm": ""
            },
            {
              "kategori": "Zat Uji Replikasi 2",
              "wadah_zat": "",
              "wadah_sisa": "",
              "bobot_bersih": "",
              "faktor_pengenceran": "",
              "konsentrasi": "",
              "vol_injeksi": "20",
              "waktu_retensi": "",
              "respon_puncak": "",
              "lambda_nm": ""
            }
          ]
        }
      ]
    },
    {
      "id": "perhitungan_kadar_hplc",
      "title": "Perhitungan Kadar, RPD & Horwitz",
      "description": "Rumus Resmi: Kadar = (Lu/Lb) * (Bb/Bu) * (Fu/Fb) * (Br/Ke) * Kb * 100%. Dilanjutkan RPD dan batas penerimaan Horwitz.",
      "fields": [
        {
          "id": "kadar_replikasi_1",
          "label": "Kadar Uji 1 (%) - T51",
          "type": "number",
          "step": "any",
          "readonly": true
        },
        {
          "id": "kadar_replikasi_2",
          "label": "Kadar Uji 2 (%) - T54",
          "type": "number",
          "step": "any",
          "readonly": true
        },
        {
          "id": "kadar_rata_persen",
          "label": "Kadar Rata-rata (%) - H57",
          "type": "number",
          "step": "any",
          "readonly": true
        },
        {
          "id": "kadar_rata_mg",
          "label": "Kadar Rata-rata (mg/tablet) - H58",
          "type": "number",
          "step": "any",
          "readonly": true
        },
        {
          "id": "hasil_identifikasi",
          "label": "Hasil Uji (Identifikasi)",
          "type": "select",
          "options": [
            "Positif",
            "Negatif"
          ],
          "default": "Positif"
        },
        {
          "id": "ketidakpastian_teks",
          "label": "Hasil Uji ± Ketidakpastian",
          "type": "text",
          "placeholder": "Contoh: 99.82 ± 1.15 % / 49.91 ± 0.58 mg/tablet"
        },
        {
          "id": "decision_rule",
          "label": "Aturan Keputusan (Decision Rule)",
          "type": "select",
          "options": [
            "Simple Acceptance",
            "Guard Band"
          ],
          "default": "Simple Acceptance"
        },
        {
          "id": "nilai_rpd",
          "label": "Nilai RPD / RSD (%) - E62 (Auto: |K1-K2|/Kadar*100)",
          "type": "number",
          "step": "any",
          "readonly": true
        },
        {
          "id": "syarat_horwitz",
          "label": "Syarat RPD / RSD Horwitz (%) - E63 (Auto: 0.5 * 2^(1 - 0.5*log10(C)))",
          "type": "number",
          "step": "any",
          "readonly": true
        },
        {
          "id": "lod_loq",
          "label": "LOD / LOQ (jika ada)",
          "type": "text"
        }
      ]
    },
    {
      "id": "kesimpulan_legalitas_hplc",
      "title": "Pustaka Persyaratan",
      "description": "Pustaka acuan monografi, syarat keberterimaan, dan keputusan akhir.",
      "fields": [
        {
          "id": "pustaka_metode",
          "label": "Pustaka Metode",
          "type": "textarea"
        },
        {
          "id": "pustaka_persyaratan",
          "label": "Pustaka Persyaratan",
          "type": "textarea"
        },
        {
          "id": "syarat_kadar",
          "label": "Syarat Persyaratan Kadar",
          "type": "text",
          "placeholder": "Contoh: Antara 90.0% dan 110.0% dari jumlah yang tertera pada etiket"
        },
        {
          "id": "tolerance_limit",
          "label": "Tolerance Limit",
          "type": "text"
        },
        {
          "id": "acceptance_limit",
          "label": "Acceptance Limit",
          "type": "text"
        },
        {
          "id": "tanggal_laporan",
          "label": "Tanggal Laporan",
          "type": "date"
        }
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

-- -----------------------------------------------------------------------------
-- Template KIM-05: Spektrofotometri
-- -----------------------------------------------------------------------------
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
    'KIM-05',
    'Spektrofotometri',
    'Kimia',
    NULL,
    NULL,
    '{
  "kode_dokumen": "POM-06.02/CFM.01/SOP.01/F.01A.06",
  "judul": "LAMPIRAN CATATAN PENGUJIAN - SPEKTROFOTOMETRI",
  "kategori": "Kimia",
  "sections": [
    {
      "id": "info_instrumen_spektro",
      "title": "Data Instrumen & Kondisi Analisis Spektrofotometri",
      "description": "Formulir resmi POM-06.02/CFM.01/SOP.01/F.01A.06. Data alat Spektrofotometer UV-Vis, scanning, kuvet, pelarut, dan baku pembanding.",
      "fields": [
        {
          "id": "zat_uji",
          "label": "Zat yang Diuji",
          "type": "text",
          "placeholder": "Contoh: Parasetamol"
        },
        {
          "id": "tgl_uji",
          "label": "Tanggal Uji",
          "type": "date"
        },
        {
          "id": "merk_alat",
          "label": "Merk Alat",
          "type": "text",
          "placeholder": "Contoh: Shimadzu"
        },
        {
          "id": "tipe_seri_alat",
          "label": "Tipe / Seri",
          "type": "text",
          "placeholder": "Contoh: UV-1800"
        },
        {
          "id": "kondisi_analisis",
          "label": "Kondisi Analisis*",
          "type": "select",
          "options": [
            "Sesuai prosedur",
            "Ada Adjustment"
          ],
          "default": "Sesuai prosedur"
        },
        {
          "id": "ket_adjustment",
          "label": "Kondisi Adjustment (jika ada)",
          "type": "text",
          "placeholder": "Catatan kondisi adjustment..."
        },
        {
          "id": "kecepatan_scanning",
          "label": "Kecepatan Scanning",
          "type": "select",
          "options": [
            "Lambat",
            "Sedang",
            "Cepat"
          ],
          "default": "Sedang"
        },
        {
          "id": "rentang_panjang_gelombang",
          "label": "Rentang Scanning Panjang Gelombang (nm)",
          "type": "text",
          "placeholder": "Contoh: 200 - 400 nm"
        },
        {
          "id": "jenis_spektrum",
          "label": "Jenis Spektrum",
          "type": "select",
          "options": [
            "Normal",
            "Derivatif"
          ],
          "default": "Normal"
        },
        {
          "id": "jenis_kuvet",
          "label": "Kuvet",
          "type": "select",
          "options": [
            "Kwarsa",
            "Kaca"
          ],
          "default": "Kwarsa"
        },
        {
          "id": "tebal_kuvet",
          "label": "Tebal Kuvet (cm)",
          "type": "text",
          "default": "1 cm"
        },
        {
          "id": "pelarut",
          "label": "Pelarut",
          "type": "text",
          "placeholder": "Contoh: Metanol P / NaOH 0.1 N"
        },
        {
          "id": "blanko",
          "label": "Blanko",
          "type": "text",
          "placeholder": "Contoh: Metanol P"
        },
        {
          "id": "baku_pembanding",
          "label": "Baku Pembanding (Nama & No Kontrol)",
          "type": "text",
          "placeholder": "Contoh: BPFI Parasetamol No. Kontrol: ..."
        },
        {
          "id": "susut_pengeringan",
          "label": "Susut Pengeringan / Kadar Air (%)",
          "type": "number",
          "step": "any",
          "placeholder": "Contoh: 0.2"
        },
        {
          "id": "kadar_baku_persen",
          "label": "Kadar Baku (Kb / M25) (%)",
          "type": "number",
          "step": "any",
          "placeholder": "M25: Contoh 99.8"
        },
        {
          "id": "metode_perhitungan_kadar",
          "label": "Metode Perhitungan Kadar Sediaan",
          "type": "select",
          "options": [
            "Tablet / Kapsul BR (Metode 20 Tablet Digerus)",
            "Tablet Cemplung (Direct Dissolution / Effervescent)",
            "Sirup / Suspensi / Cairan Oral (Volume mL)"
          ],
          "default": "Tablet / Kapsul BR (Metode 20 Tablet Digerus)",
          "keterangan": "Pilih metode perhitungan sesuai bentuk sediaan & preparasi sampel uji."
        },
        {
          "id": "volume_takaran_etiket",
          "label": "Volume Takaran Saji Etiket (mL)",
          "type": "number",
          "step": "any",
          "default": "5.0",
          "placeholder": "Contoh: 5.0 (sendok takar 5 mL)",
          "keterangan": "Hanya untuk metode Sirup / Cairan Oral. Misal 5 mL atau 15 mL."
        },
        {
          "id": "bobot_rata_rata_tablet",
          "label": "Bobot Rata-rata Tablet (Br / K46) (mg)",
          "type": "number",
          "step": "any",
          "placeholder": "K46 (Br): Contoh 650.0",
          "keterangan": "Hanya untuk metode Tablet / Kapsul BR."
        },
        {
          "id": "klaim_etiket",
          "label": "Klaim Etiket (Ke / K47) (mg)",
          "type": "number",
          "step": "any",
          "placeholder": "K47 (Ke): Contoh 500.0",
          "keterangan": "Kandungan zat aktif per tablet atau per volume takaran saji etiket."
        }
      ]
    },
    {
      "id": "data_penimbangan_dan_serapan",
      "title": "Data Penimbangan, Pengenceran & Serapan",
      "description": "Isi data penimbangan wadah+zat, wadah+sisa, faktor pengenceran, dan serapan (Absorbansi) pada panjang gelombang maksimum.",
      "fields": [
        {
          "id": "faktor_konversi_bm",
          "label": "Faktor Koreksi BM/BR (BM1 / BM2)",
          "type": "text",
          "default": "1.0",
          "placeholder": "Contoh: 1.0 atau 314.4/350.87",
          "keterangan": "Dapat berupa angka desimal atau rumus pembagian langsung (misal: 314.4/350.87)"
        },
        {
          "id": "tabel_preparasi",
          "label": "Tabel Penimbangan & Pengukuran Serapan",
          "type": "table",
          "columns": [
            {
              "id": "nama_zat",
              "label": "Nama Zat",
              "type": "text",
              "placeholder": "Nama Zat / Keterangan Uji (opsional)"
            },
            {
              "id": "wadah_zat",
              "label": "Wadah + Zat (mg)"
            },
            {
              "id": "wadah_sisa",
              "label": "Wadah + Sisa (mg)"
            },
            {
              "id": "bobot_zat",
              "label": "Bobot Zat (mg)"
            },
            {
              "id": "faktor_pengenceran",
              "label": "Faktor Pengenceran"
            },
            {
              "id": "konsentrasi",
              "label": "Konsentrasi (mcg/mL)"
            },
            {
              "id": "lambda_maks",
              "label": "λ Maks (nm)"
            },
            {
              "id": "serapan",
              "label": "Serapan (Absorbansi)"
            }
          ],
          "default_rows": [
            {
              "nama_zat": "Baku Pembanding",
              "wadah_zat": "",
              "wadah_sisa": "",
              "bobot_zat": "",
              "faktor_pengenceran": "1000",
              "konsentrasi": "",
              "lambda_maks": "243.0",
              "serapan": ""
            },
            {
              "nama_zat": "Zat Uji 1",
              "wadah_zat": "",
              "wadah_sisa": "",
              "bobot_zat": "",
              "faktor_pengenceran": "1000",
              "konsentrasi": "",
              "lambda_maks": "243.0",
              "serapan": ""
            },
            {
              "nama_zat": "Zat Uji 2",
              "wadah_zat": "",
              "wadah_sisa": "",
              "bobot_zat": "",
              "faktor_pengenceran": "1000",
              "konsentrasi": "",
              "lambda_maks": "243.0",
              "serapan": ""
            }
          ]
        }
      ]
    },
    {
      "id": "perhitungan_kadar_dan_presisi",
      "title": "Perhitungan Kadar & Verifikasi Presisi (RPD / Horwitz)",
      "description": "Rumus Excel resmi: Au/Ab * Bb/Bu * Fu/Fb * Br/Ke * Kb * 100%. Dilengkapi evaluasi RPD vs 1/2 CV Horwitz.",
      "fields": [
        {
          "id": "kadar_uji_1",
          "label": "Kadar Zat Uji 1 (O46) (%)",
          "type": "number",
          "step": "any",
          "placeholder": "Kadar Uji 1"
        },
        {
          "id": "kadar_uji_2",
          "label": "Kadar Zat Uji 2 (O49) (%)",
          "type": "number",
          "step": "any",
          "placeholder": "Kadar Uji 2"
        },
        {
          "id": "kadar_rata_rata_persen",
          "label": "Kadar Rata-rata (G52) (%)",
          "type": "number",
          "step": "any",
          "placeholder": "=(O46+O49)/2"
        },
        {
          "id": "kadar_rata_rata_mg",
          "label": "Kadar Rata-rata (G53) (mg/tablet)",
          "type": "number",
          "step": "any",
          "placeholder": "=(G52*Ke)/100"
        },
        {
          "id": "hasil_identifikasi",
          "label": "Hasil Uji (Identifikasi)",
          "type": "text",
          "default": "Positif"
        },
        {
          "id": "rpd_hitung",
          "label": "RPD / RSD (%) (D58)",
          "type": "number",
          "step": "any",
          "placeholder": "=|Uji1 - Uji2| / Rata2 * 100"
        },
        {
          "id": "syarat_rpd_horwitz",
          "label": "Syarat RPD (1/2 CV Horwitz <) (G59)",
          "type": "number",
          "step": "any",
          "placeholder": "=1/2 * (2^(1 - 0.5*LOG10(Kadar/100)))"
        },
        {
          "id": "lod_loq",
          "label": "LOD / LOQ",
          "type": "text",
          "placeholder": "Jika dipersyaratkan..."
        },
        {
          "id": "pustaka_metode",
          "label": "Pustaka Metode",
          "type": "text",
          "placeholder": "Contoh: Farmakope Indonesia Edisi VI"
        },
        {
          "id": "pustaka_persyaratan",
          "label": "Pustaka Persyaratan",
          "type": "text",
          "placeholder": "Contoh: Farmakope Indonesia Edisi VI"
        },
        {
          "id": "syarat_mutu",
          "label": "Syarat",
          "type": "text",
          "placeholder": "Contoh: Mengandung tidak kurang dari 90.0% dan tidak lebih dari 110.0%"
        }
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

-- -----------------------------------------------------------------------------
-- Template KIM-07: Kromatografi Lapis Tipis
-- -----------------------------------------------------------------------------
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
    'KIM-07',
    'Kromatografi Lapis Tipis',
    'Kimia',
    NULL,
    NULL,
    '{
  "kode_dokumen": "POM-06.02/CFM.01/SOP.01/F.01A.10",
  "judul": "LAMPIRAN CATATAN PENGUJIAN - KROMATOGRAFI LAPIS TIPIS",
  "kategori": "Kimia",
  "sections": [
    {
      "id": "kondisi_analisis",
      "title": "Kondisi Analisis",
      "description": "Parameter kondisi kromatografi, pelarut, fase diam, fase gerak, dan baku pembanding sesuai POM-06.02/CFM.01/SOP.01/F.01A.10",
      "fields": [
        {
          "id": "kondisi_analisis_opt",
          "label": "Kondisi Analisis",
          "type": "select",
          "options": [
            "Sesuai prosedur",
            "Adjusment"
          ],
          "required": true
        },
        {
          "id": "kondisi_adjusment",
          "label": "Kondisi Adjusment (jika ada)",
          "type": "text",
          "required": false
        },
        {
          "id": "pelarut",
          "label": "Pelarut",
          "type": "text",
          "required": false
        },
        {
          "id": "fase_diam",
          "label": "Fase Diam",
          "type": "text",
          "default": "Silika gel 60 F254",
          "required": true
        },
        {
          "id": "ukuran_lempeng",
          "label": "Ukuran Lempeng",
          "type": "text",
          "placeholder": "misal 10 x 20 cm",
          "required": false
        },
        {
          "id": "lempeng_lapis_tipis",
          "label": "Lempeng Lapis Tipis",
          "type": "select",
          "options": [
            "Kaca",
            "Alumunium",
            "Lainnya"
          ],
          "required": true
        },
        {
          "id": "fase_gerak_1",
          "label": "Fase Gerak (FG) 1",
          "type": "text",
          "required": true
        },
        {
          "id": "fase_gerak_2",
          "label": "Fase Gerak (FG) 2",
          "type": "text",
          "required": false
        },
        {
          "id": "fase_gerak_3",
          "label": "Fase Gerak (FG) 3",
          "type": "text",
          "required": false
        },
        {
          "id": "fase_gerak_dst",
          "label": "Fase Gerak dst",
          "type": "text",
          "required": false
        },
        {
          "id": "teknik_pengembangan",
          "label": "Teknik Pengembangan",
          "type": "select",
          "options": [
            "Menaik",
            "Jenuh",
            "Impregnasi"
          ],
          "required": true
        },
        {
          "id": "impregnasi_dalam",
          "label": "Impregnasi dalam (jika impregnasi)",
          "type": "text",
          "required": false
        },
        {
          "id": "jarak_rambat_cm",
          "label": "Jarak Rambat (cm)",
          "type": "number",
          "step": "any",
          "required": true
        },
        {
          "id": "pengamatan",
          "label": "Pengamatan",
          "type": "select",
          "options": [
            "Cahaya tampak",
            "UV 254 nm",
            "UV 366 nm",
            "UV 254 nm & UV 366 nm"
          ],
          "required": true
        },
        {
          "id": "penampak_bercak",
          "label": "Penampak Bercak",
          "type": "text",
          "required": false
        },
        {
          "id": "baku_nama",
          "label": "Baku Pembanding (Nama Zat)",
          "type": "text",
          "required": false
        },
        {
          "id": "baku_no_kontrol",
          "label": "No Kontrol Baku",
          "type": "text",
          "required": false
        },
        {
          "id": "baku_kadar",
          "label": "Kadar Baku (%)",
          "type": "text",
          "required": false
        },
        {
          "id": "baku_susut_pengeringan",
          "label": "Susut Pengeringan / Kadar Air (%)",
          "type": "text",
          "required": false
        }
      ]
    },
    {
      "id": "tabel_pengujian",
      "title": "Tabel Penimbangan, Preparasi & Nilai Rf",
      "description": "Perhitungan Bobot Zat = (Wadah + Zat) - (Wadah + Sisa). Dilanjutkan penotolan dan pengamatan nilai Rf.",
      "fields": [
        {
          "id": "klt_items",
          "type": "table",
          "label": "Data Penimbangan & Rf KLT",
          "columns": [
            {
              "id": "kategori",
              "label": "Kategori / Jenis",
              "type": "text",
              "readonly": true
            },
            {
              "id": "nama_zat",
              "label": "Nama Zat",
              "type": "text"
            },
            {
              "id": "wadah_zat",
              "label": "Wadah + Zat (g)",
              "type": "number",
              "step": "any"
            },
            {
              "id": "wadah_sisa",
              "label": "Wadah + Sisa (g)",
              "type": "number",
              "step": "any"
            },
            {
              "id": "bobot_zat",
              "label": "Bobot Zat (g)",
              "type": "number",
              "step": "any",
              "readonly": true
            },
            {
              "id": "pengenceran",
              "label": "Pengenceran",
              "type": "text"
            },
            {
              "id": "konsentrasi",
              "label": "Konsentrasi (satuan)",
              "type": "text"
            },
            {
              "id": "vol_penotolan",
              "label": "Vol Penotolan (µl)",
              "type": "number",
              "step": "any"
            },
            {
              "id": "rf_fg1",
              "label": "Rf FG 1",
              "type": "number",
              "step": "any"
            },
            {
              "id": "rf_fg2",
              "label": "Rf FG 2",
              "type": "number",
              "step": "any"
            },
            {
              "id": "rf_fg3",
              "label": "Rf FG 3",
              "type": "number",
              "step": "any"
            }
          ],
          "default_rows": [
            {
              "kategori": "Baku Pembanding",
              "nama_zat": "",
              "wadah_zat": "",
              "wadah_sisa": "",
              "bobot_zat": "",
              "pengenceran": "",
              "konsentrasi": "",
              "vol_penotolan": "",
              "rf_fg1": "",
              "rf_fg2": "",
              "rf_fg3": ""
            },
            {
              "kategori": "Zat Uji 1",
              "nama_zat": "1. ",
              "wadah_zat": "",
              "wadah_sisa": "",
              "bobot_zat": "",
              "pengenceran": "",
              "konsentrasi": "",
              "vol_penotolan": "",
              "rf_fg1": "",
              "rf_fg2": "",
              "rf_fg3": ""
            },
            {
              "kategori": "Zat Uji 2",
              "nama_zat": "2. ",
              "wadah_zat": "",
              "wadah_sisa": "",
              "bobot_zat": "",
              "pengenceran": "",
              "konsentrasi": "",
              "vol_penotolan": "",
              "rf_fg1": "",
              "rf_fg2": "",
              "rf_fg3": ""
            },
            {
              "kategori": "Spiked Sample: Zat Uji",
              "nama_zat": "Zat Uji",
              "wadah_zat": "",
              "wadah_sisa": "",
              "bobot_zat": "",
              "pengenceran": "",
              "konsentrasi": "",
              "vol_penotolan": "",
              "rf_fg1": "",
              "rf_fg2": "",
              "rf_fg3": ""
            },
            {
              "kategori": "Spiked Sample: Baku",
              "nama_zat": "Baku Pembanding",
              "wadah_zat": "",
              "wadah_sisa": "",
              "bobot_zat": "",
              "pengenceran": "",
              "konsentrasi": "",
              "vol_penotolan": "",
              "rf_fg1": "",
              "rf_fg2": "",
              "rf_fg3": ""
            }
          ]
        }
      ]
    },
    {
      "id": "evaluasi_kesimpulan",
      "title": "Evaluasi",
      "description": "Pengamatan bercak, kesesuaian sampel, dan kesimpulan pengujian.",
      "fields": [
        {
          "id": "hasil_pengamatan",
          "label": "Hasil Pengamatan*",
          "type": "select",
          "options": [
            "Ada bercak sampel yang sejajar dengan baku dan spiked sample",
            "Tidak ada bercak sampel yang sejajar dengan baku dan spiked sample"
          ],
          "required": true
        },
        {
          "id": "kromatogram",
          "label": "Kromatogram*",
          "type": "text",
          "default": "Terlampir",
          "required": true
        },
        {
          "id": "hasil_uji",
          "label": "Hasil Uji*",
          "type": "select",
          "options": [
            "Sampel diduga mengandung zat yang diuji",
            "Sampel tidak mengandung zat yang diuji"
          ],
          "required": true
        },
        {
          "id": "lod",
          "label": "LOD",
          "type": "text",
          "required": false
        },
        {
          "id": "pustaka_metode",
          "label": "Pustaka Metode",
          "type": "textarea",
          "required": false
        },
        {
          "id": "pustaka_persyaratan",
          "label": "Pustaka Persyaratan",
          "type": "textarea",
          "required": false
        },
        {
          "id": "syarat",
          "label": "Syarat",
          "type": "text",
          "required": false
        },
        {
          "id": "tanggal_laporan",
          "label": "Tanggal Laporan",
          "type": "date",
          "required": true
        }
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
