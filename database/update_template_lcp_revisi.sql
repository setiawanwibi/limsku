-- =============================================================
-- UPDATE TEMPLATE LCP: KIM-03, KIM-08, KIM-12
-- Jalankan skrip ini untuk memperbarui skema template di database limsku
-- =============================================================

USE `limsku`;

UPDATE `form_templates` SET `skema_form` = '{"kode_dokumen":"POM-06.02\\/CFM.01\\/SOP.01\\/F.01A.03","judul":"LAMPIRAN CATATAN PENGUJIAN - KESERAGAMAN KANDUNGAN","kategori":"Kimia","sections":[{"id":"info_instrumen_dan_baku","title":"Data Instrumen & Baku Pembanding","description":"Formulir resmi POM-06.02\\/CFM.01\\/SOP.01\\/F.01A.03. Isi informasi instrumen Spektro UV\\/HPLC, parameter preparasi baku pembanding, dan etiket.","fields":[{"id":"zat_uji","label":"Zat yang diuji","type":"text","placeholder":"Contoh: Parasetamol"},{"id":"tgl_uji","label":"Tanggal Uji","type":"date"},{"id":"pilihan_instrumen","label":"Instrumen yang Digunakan","type":"select","options":["Spektrofotometri UV","HPLC"],"default":"HPLC"},{"id":"merk_tipe_instrumen","label":"Merk\\/Tipe Instrumen","type":"text","placeholder":"Contoh: Shimadzu Prominence LC-20AD"},{"id":"kuvet_tebal","label":"Kuvet \\/ Tebal Kuvet (jika UV)","type":"text","placeholder":"Contoh: 1 cm (Silika)"},{"id":"panjang_gelombang","label":"\\u03bb Uji (nm)","type":"text","placeholder":"Contoh: 243 nm"},{"id":"sistem_kckt","label":"Sistem KCKT*","type":"select","options":["Isokratik","Gradien","Bukan KCKT"],"default":"Gradien"},{"id":"fase_gerak_a","label":"Fase Gerak A (FG A)","type":"text","placeholder":"Contoh: 0.4 g Ammonium bikarbonat dalam 75 mL air + 425 mL metanol"},{"id":"fase_gerak_b","label":"Fase Gerak B (FG B)","type":"text","placeholder":"Contoh: 0.4 g Ammonium bikarbonat dalam 400 mL air + 100 mL metanol"},{"id":"tabel_fase_gerak_gradient","label":"Program Fase Gerak Gradien","type":"table","columns":[{"id":"waktu_menit","label":"Waktu (menit)","type":"number","step":"any"},{"id":"persen_a","label":"% A","type":"number","step":"any"},{"id":"persen_b","label":"% B","type":"number","step":"any"}],"default_rows":[{"waktu_menit":"","persen_a":"","persen_b":""},{"waktu_menit":"","persen_a":"","persen_b":""},{"waktu_menit":"","persen_a":"","persen_b":""},{"waktu_menit":"","persen_a":"","persen_b":""},{"waktu_menit":"","persen_a":"","persen_b":""},{"waktu_menit":"","persen_a":"","persen_b":""}]},{"id":"laju_alir","label":"Laju Alir (mL\\/menit)","type":"text","placeholder":"Contoh: 1.0 mL\\/menit"},{"id":"baku_pembanding","label":"Baku Pembanding (Nama & No Kontrol)","type":"text","placeholder":"Contoh: BPFI Parasetamol No: 12345"},{"id":"kemurnian_baku","label":"Kemurnian Baku (Kb) - L11 (%)","type":"number","step":"any","placeholder":"Contoh: 99.8"},{"id":"susut_pengeringan","label":"Susut Pengeringan \\/ Kadar Air (%)","type":"number","step":"any","placeholder":"Contoh: 0.2"},{"id":"bobot_baku","label":"Bobot Baku (Bb) - L13 (mg)","type":"number","step":"any","placeholder":"Contoh: 10.5"},{"id":"hasil_ukur_baku","label":"Hasil Pengukuran Baku (Area\\/Serapan Baku) - L14","type":"number","step":"any","placeholder":"Nilai rata-rata Area atau Absorbansi Baku"},{"id":"faktor_pengenceran_baku","label":"Faktor Pengenceran Baku (Fb) - L15","type":"number","step":"any","placeholder":"Contoh: 100"},{"id":"faktor_pengenceran_uji","label":"Faktor Pengenceran Uji (Fu) - L16","type":"number","step":"any","placeholder":"Contoh: 100"},{"id":"etiket","label":"Etiket* (mg\\/sediaan) - E21","type":"number","step":"any","placeholder":"Contoh: 500"},{"id":"satuan_sediaan","label":"Satuan Sediaan*","type":"select","options":["mg\\/tab","mg\\/kaps"],"default":"mg\\/tab"},{"id":"kondisi_analisis","label":"Kondisi Analisis*","type":"select","options":["Sesuai prosedur","Ada Adjustment"],"default":"Sesuai prosedur"},{"id":"ket_adjustment","label":"Keterangan Adjustment (jika ada)","type":"text","placeholder":"Catatan perubahan kondisi..."}]},{"id":"tahap_1","title":"Tahap 1 Pengujian (10 Unit)","description":"Pengukuran Area\\/Serapan sediaan No. 1 s\\/d 10. Nilai Kadar (mg) dan Kadar (%) terkalkulasi otomatis persis rumus Excel.","fields":[{"id":"tabel_tahap1","label":"Tabel Serapan & Kadar Tahap 1","type":"table","columns":[{"id":"no","label":"No","readonly":true},{"id":"area_serapan","label":"Area \\/ Serapan"},{"id":"kadar_mg","label":"Kadar (mg)"},{"id":"kadar_persen","label":"Kadar (%)"}],"default_rows":[{"no":"1","area_serapan":"","kadar_mg":"","kadar_persen":""},{"no":"2","area_serapan":"","kadar_mg":"","kadar_persen":""},{"no":"3","area_serapan":"","kadar_mg":"","kadar_persen":""},{"no":"4","area_serapan":"","kadar_mg":"","kadar_persen":""},{"no":"5","area_serapan":"","kadar_mg":"","kadar_persen":""},{"no":"6","area_serapan":"","kadar_mg":"","kadar_persen":""},{"no":"7","area_serapan":"","kadar_mg":"","kadar_persen":""},{"no":"8","area_serapan":"","kadar_mg":"","kadar_persen":""},{"no":"9","area_serapan":"","kadar_mg":"","kadar_persen":""},{"no":"10","area_serapan":"","kadar_mg":"","kadar_persen":""}]},{"id":"rerata_kadar_mg_t1","label":"Rerata Kadar (mg) Tahap 1 (E35)","type":"number","step":"any","placeholder":"=AVERAGE(E25:E34)"},{"id":"x_rerata_tahap1","label":"Xrerata Kadar (%) Tahap 1 (G35)","type":"number","step":"any","placeholder":"=AVERAGE(G25:G34)"},{"id":"sd_tahap1","label":"SD (s) Tahap 1 (G36)","type":"number","step":"any","placeholder":"=STDEV(G25:G34)"},{"id":"rsd_tahap1","label":"RSD (%) Tahap 1 (G37)","type":"number","step":"any","placeholder":"=G36\\/G35*100"}]},{"id":"tahap_2","title":"Tahap 2 Pengujian (Tambahan No 11 - 30 jika Diperlukan)","description":"Pengujian 20 unit lanjutan jika kriteria Tahap 1 belum terpenuhi.","fields":[{"id":"lakukan_tahap2","label":"Aktifkan Tahap 2?","type":"select","options":["Tidak","Ya"],"default":"Tidak"},{"id":"tabel_tahap2","label":"Tabel Serapan & Kadar Tahap 2 (No 11 - 30)","type":"table","columns":[{"id":"no","label":"No","readonly":true},{"id":"area_serapan","label":"Area \\/ Serapan"},{"id":"kadar_mg","label":"Kadar (mg)"},{"id":"kadar_persen","label":"Kadar (%)"}],"default_rows":[{"no":"11","area_serapan":"","kadar_mg":"","kadar_persen":""},{"no":"12","area_serapan":"","kadar_mg":"","kadar_persen":""},{"no":"13","area_serapan":"","kadar_mg":"","kadar_persen":""},{"no":"14","area_serapan":"","kadar_mg":"","kadar_persen":""},{"no":"15","area_serapan":"","kadar_mg":"","kadar_persen":""},{"no":"16","area_serapan":"","kadar_mg":"","kadar_persen":""},{"no":"17","area_serapan":"","kadar_mg":"","kadar_persen":""},{"no":"18","area_serapan":"","kadar_mg":"","kadar_persen":""},{"no":"19","area_serapan":"","kadar_mg":"","kadar_persen":""},{"no":"20","area_serapan":"","kadar_mg":"","kadar_persen":""},{"no":"21","area_serapan":"","kadar_mg":"","kadar_persen":""},{"no":"22","area_serapan":"","kadar_mg":"","kadar_persen":""},{"no":"23","area_serapan":"","kadar_mg":"","kadar_persen":""},{"no":"24","area_serapan":"","kadar_mg":"","kadar_persen":""},{"no":"25","area_serapan":"","kadar_mg":"","kadar_persen":""},{"no":"26","area_serapan":"","kadar_mg":"","kadar_persen":""},{"no":"27","area_serapan":"","kadar_mg":"","kadar_persen":""},{"no":"28","area_serapan":"","kadar_mg":"","kadar_persen":""},{"no":"29","area_serapan":"","kadar_mg":"","kadar_persen":""},{"no":"30","area_serapan":"","kadar_mg":"","kadar_persen":""}]},{"id":"x_rerata_30","label":"Rerata (x rerata) 30 data (%)","type":"number","step":"any","placeholder":"Rata-rata kadar gabungan 30 unit"},{"id":"sd_30","label":"SD (s) 30 data","type":"number","step":"any","placeholder":"Standar Deviasi gabungan 30 unit"},{"id":"rsd_30","label":"RSD 30 data (%)","type":"number","step":"any","placeholder":"RSD gabungan 30 unit"}]},{"id":"evaluasi_av","title":"Evaluasi Nilai Keberterimaan (Acceptance Value - AV)","description":"Perhitungan matriks kondisi nilai M dan AV sesuai ketentuan Farmakope Indonesia (L1 <= 15.0).","fields":[{"id":"nilai_k","label":"k (Faktor Penerimaan)","type":"text","default":"2.4","placeholder":"2.4 untuk n=10, 2.0 untuk n=30"},{"id":"target_t","label":"T (%) = Target","type":"text","default":"100","readonly":true},{"id":"nilai_m","label":"M (Nilai Rujukan %)","type":"number","step":"any","placeholder":"Nilai M terhitung"},{"id":"nilai_av","label":"AV (Nilai Keberterimaan)","type":"number","step":"any","placeholder":"Nilai AV terhitung"},{"id":"batas_l1","label":"L1 (Batas Maksimum AV)","type":"text","default":"15.0","readonly":true},{"id":"syarat","label":"Syarat","type":"text","default":"AV dari 10 unit \\u2264 15,0 %"},{"id":"pustaka","label":"Pustaka Acuan","type":"text","placeholder":"Contoh: Farmakope Indonesia Edisi VI <971> Keseragaman Sediaan"}]}]}', `updated_at` = NOW() WHERE `kode_template` = 'KIM-03';

UPDATE `form_templates` SET `skema_form` = '{"kode_dokumen":"POM-06.02/CFM.01/SOP.01/F.01A.12","judul":"LAMPIRAN CATATAN PENGUJIAN - KROMATOGRAFI CAIR KINERJA TINGGI","kategori":"Kimia","sections":[{"id":"kondisi_alat_kromatografi","title":"Kondisi Instrumen HPLC & Parameter Kromatografi","description":"Formulir resmi POM-06.02/CFM.01/SOP.01/F.01A.12. Parameter instrumen, kolom, detektor, elusi isokratik/gradient, suhu, laju alir, panjang gelombang.","fields":[{"id":"merk_alat","label":"Merk Alat HPLC","type":"text","placeholder":"Contoh: Waters / Shimadzu / Agilent"},{"id":"tipe_seri","label":"Tipe / Seri","type":"text","placeholder":"Contoh: Alliance e2695 / Prominence LC-20AT"},{"id":"detektor","label":"Detektor","type":"text","placeholder":"Contoh: UV-Vis / PDA / RID / FLD"},{"id":"kondisi_analisis","label":"Kondisi Analisis","type":"select","options":["Sesuai prosedur","Adjusment"],"default":"Sesuai prosedur"},{"id":"kondisi_adjusment","label":"Kondisi Adjusment (jika ada)","type":"text"},{"id":"pelarut","label":"Pelarut","type":"text"},{"id":"suhu_opt","label":"Kondisi Suhu Kolom","type":"select","options":["Suhu Kamar","Suhu Terkontrol (°C)"],"default":"Suhu Kamar"},{"id":"suhu_custom","label":"Suhu Kolom Terkontrol (°C)","type":"number","step":"any","placeholder":"Contoh: 30 / 40"},{"id":"kolom_dimensi","label":"Kolom (Ukuran partikel µm, mm x cm, material)","type":"text","placeholder":"Contoh: C18 5 µm (4.6 mm x 25 cm)"},{"id":"tipe_elusi","label":"Tipe Fase Gerak","type":"select","options":["Isokratik","Gradient"],"default":"Isokratik"},{"id":"fase_gerak_isokratik","label":"Fase Gerak Isokratik","type":"text","placeholder":"Contoh: Metanol : Dapar Fosfat pH 3.0 (60 : 40)"},{"id":"tabel_gradient","label":"Program Fase Gerak Gradien (jika Elusi Gradien)","type":"table","columns":[{"id":"waktu_menit","label":"Waktu (menit)","type":"number","step":"any"},{"id":"fase_a","label":"Fase Gerak A (%)","type":"number","step":"any"},{"id":"fase_b","label":"Fase Gerak B (%)","type":"number","step":"any"},{"id":"fase_c","label":"Fase Gerak C (%)","type":"number","step":"any"},{"id":"laju_alir","label":"Laju Alir (mL/menit)","type":"number","step":"any"}],"default_rows":[{"waktu_menit":"0","fase_a":"","fase_b":"","fase_c":"","laju_alir":"1.0"},{"waktu_menit":"5","fase_a":"","fase_b":"","fase_c":"","laju_alir":"1.0"},{"waktu_menit":"15","fase_a":"","fase_b":"","fase_c":"","laju_alir":"1.0"},{"waktu_menit":"20","fase_a":"","fase_b":"","fase_c":"","laju_alir":"1.0"}]},{"id":"laju_aliran","label":"Laju Aliran (mL/menit)","type":"number","step":"any","placeholder":"Contoh: 1.0"},{"id":"panjang_gelombang","label":"Panjang Gelombang λ Uji (nm)","type":"number","step":"any","placeholder":"Contoh: 254"}]},{"id":"baku_pembanding_section","title":"Data Baku Pembanding & Baku Internal","description":"Koreksi kadar kemurnian baku: Kb = (100 - Susut Pengeringan) * Kadar / 100","fields":[{"id":"baku_nama","label":"Nama Baku Pembanding","type":"text"},{"id":"baku_no_kontrol","label":"Nomor Kontrol Baku","type":"text"},{"id":"baku_kadar","label":"Kadar Baku (%) - L24","type":"number","step":"any","placeholder":"Contoh: 99.8"},{"id":"baku_sp","label":"Susut Pengeringan / Kadar Air (%) - O24","type":"number","step":"any","placeholder":"Contoh: 0.2"},{"id":"baku_kadar_terkoreksi","label":"Kadar Terkoreksi Baku (%) - T24 (Auto: (100-SP)*Kadar/100)","type":"number","step":"any","readonly":true},{"id":"internal_nama","label":"Nama Baku Internal (jika menggunakan)","type":"text"},{"id":"internal_no_kontrol","label":"Nomor Kontrol Baku Internal","type":"text"},{"id":"internal_kadar","label":"Kadar Baku Internal (%)","type":"number","step":"any"},{"id":"internal_sp","label":"Susut Pengeringan Baku Internal (%)","type":"number","step":"any"},{"id":"internal_kadar_terkoreksi","label":"Kadar Terkoreksi Baku Internal (%)","type":"number","step":"any","readonly":true},{"id":"status_kromatogram_uks","label":"Kromatogram Uji Kesesuaian Sistem (UKS)","type":"select","options":["Terlampir","Tidak Diperlukan"],"default":"Terlampir"},{"id":"status_kromatogram_sampel","label":"Kromatogram Baku dan Sampel","type":"select","options":["Terlampir","Tidak Diperlukan"],"default":"Terlampir"},{"id":"status_regresi","label":"Regresi Kurva Baku dan Perhitungan","type":"select","options":["Terlampir","Metode One-Point Standard"],"default":"Terlampir"}]},{"id":"tabel_penimbangan_hplc","title":"Tabel Penimbangan & Respon Puncak Kromatografi","description":"Perhitungan Bobot Bersih = (Wadah+Zat) - (Wadah+Sisa). Respon Puncak (Luas Area / Tinggi Puncak).","fields":[{"id":"metode_perhitungan_kadar","label":"Metode Perhitungan Kadar Sediaan","type":"select","options":["Tablet / Kapsul BR (Metode 20 Tablet Digerus)","Tablet Cemplung (Direct Dissolution / Effervescent)","Sirup / Suspensi / Cairan Oral (Volume mL)"],"default":"Tablet / Kapsul BR (Metode 20 Tablet Digerus)","keterangan":"Pilih metode perhitungan sesuai bentuk sediaan & preparasi sampel uji."},{"id":"volume_takaran_etiket","label":"Volume Takaran Saji Etiket (mL)","type":"number","step":"any","default":"5.0","placeholder":"Contoh: 5.0 (sendok takar 5 mL)","keterangan":"Hanya untuk metode Sirup / Cairan Oral. Misal 5 mL atau 15 mL."},{"id":"bobot_rata_tablet","label":"Bobot Rata-rata Tablet / Sediaan (Br) [mg]","type":"number","step":"any","placeholder":"Contoh: 250.5","keterangan":"Hanya untuk metode Tablet / Kapsul BR."},{"id":"klaim_etiket","label":"Komposisi / Klaim Etiket (Ke) [mg]","type":"number","step":"any","placeholder":"Contoh: 50.0","keterangan":"Kandungan zat aktif per tablet atau per volume takaran saji etiket."},{"id":"faktor_konversi_bm","label":"Faktor Koreksi BM/BR (BM1 / BM2)","type":"text","default":"1.0","placeholder":"Contoh: 1.0 atau 314.4/350.87","keterangan":"Dapat berupa angka desimal atau rumus pembagian langsung (misal: 314.4/350.87)"},{"id":"tabel_hplc","type":"table","label":"Data Pengukuran KCKT / HPLC","columns":[{"id":"kategori","label":"Kategori / Nama Zat","type":"text","placeholder":"Kategori / Keterangan Zat Uji (opsional)"},{"id":"wadah_zat","label":"Wadah + Zat (g)","type":"number","step":"any"},{"id":"wadah_sisa","label":"Wadah + Sisa (g)","type":"number","step":"any"},{"id":"bobot_bersih","label":"Bobot Bersih (g)","type":"number","step":"any"},{"id":"faktor_pengenceran","label":"Faktor Pengenceran","type":"number","step":"any"},{"id":"konsentrasi","label":"Konsentrasi (Auto/Teks)","type":"text","placeholder":"Auto hitung dari Bobot/FP"},{"id":"vol_injeksi","label":"Vol. Injeksi (µL)","type":"number","step":"any"},{"id":"waktu_retensi","label":"Rt (menit)","type":"number","step":"any"},{"id":"respon_puncak","label":"Respon Puncak (Area)","type":"number","step":"any"},{"id":"lambda_nm","label":"λ (nm)","type":"number","step":"any"}],"default_rows":[{"kategori":"Baku Pembanding","wadah_zat":"","wadah_sisa":"","bobot_bersih":"","faktor_pengenceran":"","konsentrasi":"","vol_injeksi":"20","waktu_retensi":"","respon_puncak":"","lambda_nm":""},{"kategori":"Baku Internal","wadah_zat":"","wadah_sisa":"","bobot_bersih":"","faktor_pengenceran":"","konsentrasi":"","vol_injeksi":"20","waktu_retensi":"","respon_puncak":"","lambda_nm":""},{"kategori":"Zat Uji Replikasi 1","wadah_zat":"","wadah_sisa":"","bobot_bersih":"","faktor_pengenceran":"","konsentrasi":"","vol_injeksi":"20","waktu_retensi":"","respon_puncak":"","lambda_nm":""},{"kategori":"Zat Uji Replikasi 2","wadah_zat":"","wadah_sisa":"","bobot_bersih":"","faktor_pengenceran":"","konsentrasi":"","vol_injeksi":"20","waktu_retensi":"","respon_puncak":"","lambda_nm":""}]}]},{"id":"perhitungan_kadar_hplc","title":"Perhitungan Kadar, RPD & Horwitz","description":"Rumus Resmi: Kadar = (Lu/Lb) * (Bb/Bu) * (Fu/Fb) * (Br/Ke) * Kb * 100%. Dilanjutkan RPD dan batas penerimaan Horwitz.","fields":[{"id":"kadar_replikasi_1","label":"Kadar Uji 1 (%) - T51","type":"number","step":"any","readonly":true},{"id":"kadar_replikasi_2","label":"Kadar Uji 2 (%) - T54","type":"number","step":"any","readonly":true},{"id":"kadar_rata_persen","label":"Kadar Rata-rata (%) - H57","type":"number","step":"any","readonly":true},{"id":"kadar_rata_mg","label":"Kadar Rata-rata (mg/tablet) - H58","type":"number","step":"any","readonly":true},{"id":"hasil_identifikasi","label":"Hasil Uji (Identifikasi)","type":"select","options":["Positif","Negatif"],"default":"Positif"},{"id":"ketidakpastian_teks","label":"Hasil Uji ± Ketidakpastian","type":"text","placeholder":"Contoh: 99.82 ± 1.15 % / 49.91 ± 0.58 mg/tablet"},{"id":"decision_rule","label":"Aturan Keputusan (Decision Rule)","type":"select","options":["Simple Acceptance","Guard Band"],"default":"Simple Acceptance"},{"id":"nilai_rpd","label":"Nilai RPD / RSD (%) - E62 (Auto: |K1-K2|/Kadar*100)","type":"number","step":"any","readonly":true},{"id":"syarat_horwitz","label":"Syarat RPD / RSD Horwitz (%) - E63 (Auto: 0.5 * 2^(1 - 0.5*log10(C)))","type":"number","step":"any","readonly":true},{"id":"lod_loq","label":"LOD / LOQ (jika ada)","type":"text"}]},{"id":"kesimpulan_legalitas_hplc","title":"Pustaka Persyaratan","description":"Pustaka acuan monografi, syarat keberterimaan, dan keputusan akhir.","fields":[{"id":"pustaka_metode","label":"Pustaka Metode","type":"textarea"},{"id":"pustaka_persyaratan","label":"Pustaka Persyaratan","type":"textarea"},{"id":"syarat_kadar","label":"Syarat Persyaratan Kadar","type":"text","placeholder":"Contoh: Antara 90.0% dan 110.0% dari jumlah yang tertera pada etiket"},{"id":"tolerance_limit","label":"Tolerance Limit","type":"text"},{"id":"acceptance_limit","label":"Acceptance Limit","type":"text"},{"id":"tanggal_laporan","label":"Tanggal Laporan","type":"date"}]}]}', `updated_at` = NOW() WHERE `kode_template` = 'KIM-08';

UPDATE `form_templates` SET `skema_form` = '{
    "kode_dokumen": "POM-06.02\\/CFM.01\\/SOP.01\\/F.01A.28",
    "judul": "LAMPIRAN CATATAN PENGUJIAN - BOBOT JENIS",
    "kategori": "Kimia",
    "sections": [
        {
            "id": "info_alat_bj",
            "title": "Instrumen Neraca & Piknometer",
            "description": "Formulir resmi POM-06.02\\/CFM.01\\/SOP.01\\/F.01A.28. Instrumen timbangan analitik dan piknometer kalibrasi.",
            "fields": [
                {
                    "id": "merk_alat",
                    "label": "Merk Alat Timbangan \\/ Piknometer",
                    "type": "text",
                    "placeholder": "Contoh: Mettler Toledo \\/ Sartorius \\/ Iwaki"
                },
                {
                    "id": "tipe_seri",
                    "label": "Tipe \\/ Seri \\/ Volume Piknometer",
                    "type": "text",
                    "placeholder": "Contoh: MS204TS \\/ Piknometer 25 mL"
                }
            ]
        },
        {
            "id": "data_baku_pembanding",
            "title": "Data Baku Pembanding & Kadar",
            "description": "Informasi baku pembanding laboratorium, nomor kontrol, kadar kemurnian baku, dan kadar air \\/ susut pengeringan.",
            "fields": [
                {
                    "id": "baku_pembanding",
                    "label": "Baku Pembanding (Nama & No Kontrol)",
                    "type": "text",
                    "placeholder": "Contoh: BPFI Parasetamol No. Kontrol: 12345"
                },
                {
                    "id": "kadar_baku",
                    "label": "Kadar Baku (%)",
                    "type": "number",
                    "step": "any",
                    "placeholder": "Contoh: 99.8"
                },
                {
                    "id": "susut_pengeringan",
                    "label": "Susut Pengeringan \\/ Kadar Air (%)",
                    "type": "number",
                    "step": "any",
                    "placeholder": "Contoh: 0.1"
                }
            ]
        },
        {
            "id": "penimbangan_bobot_jenis",
            "title": "Tabel Penimbangan & Perhitungan Bobot Jenis (BJ)",
            "description": "Formula: Bobot Zat = W1 - W0, Bobot Air = W2 - W0. Bobot Jenis = (W1 - W0) \\/ (W2 - W0).",
            "fields": [
                {
                    "id": "tabel_penimbangan",
                    "type": "table",
                    "label": "Data Penimbangan Piknometer",
                    "columns": [
                        {
                            "id": "komponen",
                            "label": "Yang Ditimbang",
                            "type": "text",
                            "readonly": true
                        },
                        {
                            "id": "bobot_1",
                            "label": "Bobot 1 (g)",
                            "type": "number",
                            "step": "any"
                        },
                        {
                            "id": "bobot_2",
                            "label": "Bobot 2 (g)",
                            "type": "number",
                            "step": "any"
                        },
                        {
                            "id": "rata_rata",
                            "label": "Rata-rata (g)",
                            "type": "number",
                            "step": "any",
                            "readonly": true
                        }
                    ],
                    "default_rows": [
                        {
                            "komponen": "Piknometer Kosong (W0)",
                            "bobot_1": "",
                            "bobot_2": "",
                            "rata_rata": ""
                        },
                        {
                            "komponen": "Piknometer + Zat (W1)",
                            "bobot_1": "",
                            "bobot_2": "",
                            "rata_rata": ""
                        },
                        {
                            "komponen": "Piknometer + Air (W2)",
                            "bobot_1": "",
                            "bobot_2": "",
                            "rata_rata": ""
                        }
                    ]
                },
                {
                    "id": "bobot_zat_bersih",
                    "label": "Bobot Zat Uji (W1 - W0) [g]",
                    "type": "number",
                    "step": "any",
                    "readonly": true
                },
                {
                    "id": "bobot_air_bersih",
                    "label": "Bobot Air (W2 - W0) [g]",
                    "type": "number",
                    "step": "any",
                    "readonly": true
                },
                {
                    "id": "nilai_bobot_jenis",
                    "label": "Bobot Jenis (BJ) = (W1 - W0) \\/ (W2 - W0)",
                    "type": "number",
                    "step": "any",
                    "readonly": true
                }
            ]
        },
        {
            "id": "evaluasi_bj",
            "title": "Pustaka Persyaratan",
            "description": "Pustaka acuan monografi dan syarat rentang Bobot Jenis.",
            "fields": [
                {
                    "id": "pustaka",
                    "label": "Pustaka Acuan",
                    "type": "text",
                    "default": "FI VI"
                },
                {
                    "id": "syarat_bj",
                    "label": "Syarat Bobot Jenis Monografi",
                    "type": "text",
                    "placeholder": "Contoh: Antara 1.010 dan 1.030",
                    "required": true
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
}', `updated_at` = NOW() WHERE `kode_template` = 'KIM-12';

