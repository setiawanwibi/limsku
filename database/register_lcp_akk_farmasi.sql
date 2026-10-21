-- ==============================================================================
-- SKRIP IDEMPOTEN PENDAFTARAN TEMPLATE RESMI LCP ANGKA KAPANG KHAMIR SEDIAAN FARMASI
-- Kode Dokumen: POM-06.02/CFM.01/SOP.01/F.01B.04
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
    'MIK-02',
    'Uji Angka Kapang Khamir Sediaan Farmasi',
    'Mikrobiologi',
    NULL,
    NULL,
    '{
  "kode_dokumen": "POM-06.02/CFM.01/SOP.01/F.01B.04",
  "judul": "UJI ANGKA KAPANG KHAMIR SEDIAAN FARMASI",
  "kategori": "Mikrobiologi",
  "sections": [
    {
      "id": "kesesuaian_metode",
      "title": "Hasil Uji Kesesuaian Metode",
      "description": "Pelarut dan galur mikroba baku yang sesuai untuk pengujian.",
      "fields": [
        {
          "id": "pelarut_sesuai",
          "label": "Pelarut yang Sesuai",
          "type": "radio",
          "options": [
            "TSB",
            "TSB + 30 g/L polysorbate 80",
            "TSB + 30 g/L polysorbate 80 + 3 g/L Lesitin",
            "SCDLP 20 Broth",
            "Lainnya"
          ],
          "default": "Lainnya"
        },
        {
          "id": "pelarut_sesuai_lainnya",
          "label": "Keterangan Pelarut Lainnya",
          "type": "text",
          "placeholder": "Contoh: LDF pH 7,2",
          "default": "LDF pH 7,2"
        },
        {
          "id": "mikroba_candida",
          "label": "Candida albicans ATCC 10231",
          "type": "checkbox",
          "default": true
        },
        {
          "id": "mikroba_aspergillus",
          "label": "Aspergillus brasiliensis ATCC 16404",
          "type": "checkbox",
          "default": true
        },
        {
          "id": "mikroba_lainnya_check",
          "label": "Mikroba Baku Lainnya",
          "type": "checkbox",
          "default": false
        },
        {
          "id": "mikroba_lainnya_text",
          "label": "Nama Mikroba Baku Lainnya",
          "type": "text",
          "placeholder": "Sebutkan galur mikroba lainnya..."
        }
      ]
    },
    {
      "id": "homogenisasi_awal",
      "title": "Homogenisasi Awal",
      "description": "Data penimbangan sampel dan pelarut homogenisasi serta pengenceran seri.",
      "fields": [
        {
          "id": "bobot_contoh",
          "label": "Penimbangan / Pemipetan Contoh",
          "type": "text",
          "default": "10"
        },
        {
          "id": "satuan_contoh",
          "label": "Satuan Bobot/Volume",
          "type": "select",
          "options": ["g", "mL"],
          "default": "g"
        },
        {
          "id": "volume_pelarut",
          "label": "Volume Pelarut Homogenisasi (mL)",
          "type": "text",
          "default": "90"
        },
        {
          "id": "pengenceran_seri_desc",
          "label": "Pengenceran Seri",
          "type": "text",
          "default": "1 dalam 9 mL",
          "readonly": true
        },
        {
          "id": "pelarut_pengencer",
          "label": "Pelarut Pengencer",
          "type": "radio",
          "options": [
            "TSB",
            "TSB + 30 g/L polysorbate 80",
            "TSB + 30 g/L polysorbate 80 + 3 g/L Lesitin",
            "SCDLP 20 Broth",
            "Lainnya"
          ],
          "default": "Lainnya"
        },
        {
          "id": "pelarut_pengencer_lainnya",
          "label": "Keterangan Pelarut Pengencer Lainnya",
          "type": "text",
          "placeholder": "Contoh: LDF pH 7,2",
          "default": "LDF pH 7,2"
        }
      ]
    },
    {
      "id": "kondisi_inkubasi",
      "title": "Media & Kondisi Inkubasi",
      "description": "Pengaturan media agar, suhu, dan waktu inkubasi.",
      "fields": [
        {
          "id": "media_agar",
          "label": "Media Agar",
          "type": "radio",
          "options": ["SDA", "PDA", "Lainnya"],
          "default": "SDA"
        },
        {
          "id": "media_agar_lainnya",
          "label": "Media Agar Lainnya",
          "type": "text"
        },
        {
          "id": "suhu_inkubasi",
          "label": "Suhu Inkubasi (°C)",
          "type": "radio",
          "options": ["22,5±2,5", "25±2,5", "Lainnya"],
          "default": "22,5±2,5"
        },
        {
          "id": "suhu_inkubasi_lainnya",
          "label": "Suhu Lainnya (°C)",
          "type": "text"
        },
        {
          "id": "waktu_inkubasi",
          "label": "Waktu Inkubasi (Hari)",
          "type": "radio",
          "options": ["3 - 5", "5 - 7", "Lainnya"],
          "default": "5 - 7"
        },
        {
          "id": "waktu_inkubasi_lainnya",
          "label": "Waktu Lainnya",
          "type": "text"
        }
      ]
    },
    {
      "id": "pengamatan_kesesuaian",
      "title": "Pengamatan Kesesuaian Metode (Spike)",
      "description": "Jumlah inokulum spike dan hasil recovery mikroba baku.",
      "fields": [
        {
          "id": "spike_candida_jumlah",
          "label": "Jumlah Spike C. albicans (koloni)",
          "type": "text",
          "placeholder": "Contoh: 50"
        },
        {
          "id": "spike_candida_a",
          "label": "C. albicans 1 ml (A)",
          "type": "text"
        },
        {
          "id": "spike_candida_b",
          "label": "C. albicans 1 ml (B)",
          "type": "text"
        },
        {
          "id": "spike_candida_rata",
          "label": "C. albicans Rata-rata",
          "type": "text"
        },
        {
          "id": "spike_aspergillus_jumlah",
          "label": "Jumlah Spike A. niger / brasiliensis (koloni)",
          "type": "text",
          "placeholder": "Contoh: 45"
        },
        {
          "id": "spike_aspergillus_a",
          "label": "A. niger 1 ml (A)",
          "type": "text"
        },
        {
          "id": "spike_aspergillus_b",
          "label": "A. niger 1 ml (B)",
          "type": "text"
        },
        {
          "id": "spike_aspergillus_rata",
          "label": "A. niger Rata-rata",
          "type": "text"
        },
        {
          "id": "perhitungan_suitabilitas_candida",
          "label": "Perhitungan Recovery C. albicans (%)",
          "type": "text",
          "placeholder": "Contoh: 95 %"
        },
        {
          "id": "perhitungan_suitabilitas_aspergillus",
          "label": "Perhitungan Recovery A. niger (%)",
          "type": "text",
          "placeholder": "Contoh: 90 %"
        }
      ]
    },
    {
      "id": "tabel_pengamatan_akk",
      "title": "Tabel Pengamatan Sampel Uji & Perhitungan AKK",
      "description": "Pengamatan jumlah koloni pada tiap tingkat pengenceran duplo serta kontrol blangko.",
      "fields": [
        {
          "id": "tanggal_pengamatan",
          "label": "Tanggal Pengamatan Koloni",
          "type": "date"
        },
        {
          "id": "tabel_koloni",
          "label": "Tabel Pengenceran dan Koloni",
          "type": "table",
          "columns": [
            {"id": "pengenceran", "label": "Tingkat Pengenceran", "readonly": true},
            {"id": "cawan_a", "label": "1 ml (A)"},
            {"id": "cawan_b", "label": "1 ml (B)"},
            {"id": "total_koloni", "label": "Total Koloni"}
          ],
          "default_rows": [
            {"pengenceran": "10^-1", "cawan_a": "", "cawan_b": "", "total_koloni": ""},
            {"pengenceran": "10^-2", "cawan_a": "", "cawan_b": "", "total_koloni": ""},
            {"pengenceran": "10^-3", "cawan_a": "", "cawan_b": "", "total_koloni": ""},
            {"pengenceran": "10^-4", "cawan_a": "", "cawan_b": "", "total_koloni": ""},
            {"pengenceran": "10^-5", "cawan_a": "", "cawan_b": "", "total_koloni": ""},
            {"pengenceran": "10^-6", "cawan_a": "", "cawan_b": "", "total_koloni": ""},
            {"pengenceran": "....", "cawan_a": "", "cawan_b": "", "total_koloni": ""}
          ]
        },
        {
          "id": "blangko_agar",
          "label": "Jumlah Koloni Blangko Agar",
          "type": "text",
          "default": "0"
        },
        {
          "id": "blangko_pengencer",
          "label": "Jumlah Koloni Blangko Agar + Pengencer",
          "type": "text",
          "default": "0"
        },
        {
          "id": "perhitungan_akk_rumus_atas",
          "label": "Rumus Pembilang (Contoh: (2+3) x 10^1)",
          "type": "text",
          "placeholder": "(A + B) x faktor pengenceran"
        },
        {
          "id": "perhitungan_akk_rumus_bawah",
          "label": "Rumus Penyebut (Jumlah Cawan)",
          "type": "text",
          "default": "2"
        },
        {
          "id": "hasil_akk_angka",
          "label": "Hasil AKK",
          "type": "text",
          "placeholder": "Contoh: 2,5 x 10^1 atau < 10"
        },
        {
          "id": "hasil_akk_satuan",
          "label": "Satuan Hasil",
          "type": "select",
          "options": ["koloni/g", "koloni/mL", "CFU/g", "CFU/mL"],
          "default": "koloni/g"
        }
      ]
    },
    {
      "id": "pustaka_dan_kesimpulan",
      "title": "Pustaka, Syarat & Kesimpulan",
      "description": "Acuan standar, kriteria syarat penerimaan, dan keputusan akhir pengujian.",
      "fields": [
        {
          "id": "pustaka_acuan",
          "label": "Pustaka / Metode Acuan",
          "type": "text",
          "default": "68/MI/16"
        },
        {
          "id": "syarat_keberterimaan",
          "label": "Syarat Keberterimaan",
          "type": "text",
          "placeholder": "Contoh: ≤ 10^2 koloni/g"
        },
        {
          "id": "kesimpulan_status",
          "label": "Kesimpulan",
          "type": "select",
          "options": [
            "Memenuhi Syarat (MS)",
            "Tidak Memenuhi Syarat (TMS)",
            "Hasil Pengujian Seperti Tersebut (HPST)"
          ],
          "default": "Memenuhi Syarat (MS)"
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
