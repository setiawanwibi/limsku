<?php
define('BASEPATH', 1);
define('ENVIRONMENT', 'development');
require_once __DIR__ . '/../application/config/database.php';

$cfg = $db['default'];
$mysqli = new mysqli($cfg['hostname'], $cfg['username'], $cfg['password'], $cfg['database']);
if ($mysqli->connect_error) {
    die("DB connection failed: " . $mysqli->connect_error);
}
$mysqli->set_charset("utf8mb4");

$schema = [
    'kode_dokumen' => 'POM-06.02/CFM.01/SOP.01/F.01A.12',
    'judul'        => 'LAMPIRAN CATATAN PENGUJIAN - KROMATOGRAFI CAIR KINERJA TINGGI (PANGAN)',
    'kategori'     => 'Kimia',
    'sections'     => [
        [
            'id'          => 'kondisi_alat_kromatografi',
            'title'       => 'Kondisi Instrumen HPLC & Parameter Analisis (MA PPOMN 16/PA/13)',
            'description' => 'Formulir resmi POM-06.02/CFM.01/SOP.01/F.01A.12 untuk Pengujian Pemanis Simultan Pangan (Asesulfam K, Aspartam, Sakarin).',
            'fields'      => [
                [
                    'id'      => 'metode_analisis_pilihan',
                    'label'   => 'Metode Analisis Acuan',
                    'type'    => 'select',
                    'options' => [
                        'MA PPOM 16/PA/13 (KCKT UV/ELSD - Pemanis Simultan)',
                        'MA PPOM 08/PA/13 (KCKT UV - Pengawet & Sakarin)',
                        'MA PPOM 53/PA/16 (KCKT - Siklamat)',
                        'LC-MS/MS (03/PA/MA-PPPOMN/25)',
                        'Metode Lainnya'
                    ],
                    'default' => 'MA PPOM 16/PA/13 (KCKT UV/ELSD - Pemanis Simultan)'
                ],
                [
                    'id'          => 'merk_alat',
                    'label'       => 'Merk Alat HPLC',
                    'type'        => 'text',
                    'default'     => 'Shimadzu',
                    'placeholder' => 'Contoh: Shimadzu / Thermo / Waters'
                ],
                [
                    'id'          => 'tipe_seri',
                    'label'       => 'Tipe / Seri',
                    'type'        => 'text',
                    'default'     => 'LC 20 AD',
                    'placeholder' => 'Contoh: LC 20 AD / Nexera'
                ],
                [
                    'id'          => 'detektor',
                    'label'       => 'Detektor',
                    'type'        => 'text',
                    'default'     => 'UV (SPD 10A)',
                    'placeholder' => 'Contoh: UV / PDA / ELSD'
                ],
                [
                    'id'      => 'kondisi_analisis',
                    'label'   => 'Kondisi Analisis',
                    'type'    => 'select',
                    'options' => ['Sesuai prosedur', 'Adjusment'],
                    'default' => 'Sesuai prosedur'
                ],
                [
                    'id'    => 'kondisi_adjusment',
                    'label' => 'Kondisi Adjusment (jika ada)',
                    'type'  => 'text'
                ],
                [
                    'id'      => 'pelarut',
                    'label'   => 'Pelarut',
                    'type'    => 'text',
                    'default' => 'Aquabidest'
                ],
                [
                    'id'      => 'suhu_opt',
                    'label'   => 'Kondisi Suhu Kolom',
                    'type'    => 'select',
                    'options' => ['Suhu Kamar', 'Suhu Terkontrol (°C)'],
                    'default' => 'Suhu Terkontrol (°C)'
                ],
                [
                    'id'      => 'suhu_custom',
                    'label'   => 'Suhu Kolom (°C)',
                    'type'    => 'number',
                    'step'    => 'any',
                    'default' => '35'
                ],
                [
                    'id'      => 'kolom_dimensi',
                    'label'   => 'Kolom (Ukuran partikel, panjang x diameter)',
                    'type'    => 'text',
                    'default' => 'Welch, C18, 5 µm (250 mm x 4.6 mm)'
                ],
                [
                    'id'      => 'tipe_elusi',
                    'label'   => 'Tipe Fase Gerak',
                    'type'    => 'select',
                    'options' => ['Gradient', 'Isokratik'],
                    'default' => 'Gradient'
                ],
                [
                    'id'      => 'fase_gerak_komposisi',
                    'label'   => 'Komposisi Fase Gerak (A / B / C)',
                    'type'    => 'text',
                    'default' => 'A: K2HPO4 - KH2PO4 pH 5 | B: Asetonitril'
                ],
                [
                    'id'           => 'tabel_gradient',
                    'label'        => 'Program Fase Gerak Gradien (16/PA/13)',
                    'type'         => 'table',
                    'columns'      => [
                        ['id' => 'waktu_menit', 'label' => 'Waktu (menit)', 'type' => 'number', 'step' => 'any'],
                        ['id' => 'fase_a', 'label' => '% A', 'type' => 'number', 'step' => 'any'],
                        ['id' => 'fase_b', 'label' => '% B', 'type' => 'number', 'step' => 'any'],
                        ['id' => 'fase_c', 'label' => '% C', 'type' => 'number', 'step' => 'any']
                    ],
                    'default_rows' => [
                        ['waktu_menit' => '0',  'fase_a' => '95', 'fase_b' => '5',  'fase_c' => ''],
                        ['waktu_menit' => '2',  'fase_a' => '95', 'fase_b' => '5',  'fase_c' => ''],
                        ['waktu_menit' => '8',  'fase_a' => '95', 'fase_b' => '5',  'fase_c' => ''],
                        ['waktu_menit' => '11', 'fase_a' => '80', 'fase_b' => '20', 'fase_c' => ''],
                        ['waktu_menit' => '24', 'fase_a' => '80', 'fase_b' => '20', 'fase_c' => ''],
                        ['waktu_menit' => '26', 'fase_a' => '95', 'fase_b' => '5',  'fase_c' => ''],
                        ['waktu_menit' => '32', 'fase_a' => '95', 'fase_b' => '5',  'fase_c' => '']
                    ]
                ],
                [
                    'id'      => 'laju_alir_umum',
                    'label'   => 'Laju Aliran (mL/menit)',
                    'type'    => 'number',
                    'step'    => 'any',
                    'default' => '1'
                ],
                [
                    'id'      => 'panjang_gelombang',
                    'label'   => 'λ Uji (nm)',
                    'type'    => 'number',
                    'step'    => 'any',
                    'default' => '205'
                ],
                [
                    'id'      => 'kromatogram_uks',
                    'label'   => 'Kromatogram UKS',
                    'type'    => 'text',
                    'default' => 'terlampir'
                ],
                [
                    'id'      => 'kromatogram_baku_sampel',
                    'label'   => 'Kromatogram Baku, Sampel & Spiked Sample',
                    'type'    => 'text',
                    'default' => 'terlampir'
                ]
            ]
        ],
        [
            'id'          => 'baku_pembanding_identitas',
            'title'       => 'Identitas Baku Pembanding & Baku Tambahan (Opsional)',
            'description' => 'Diisi berdasarkan sertifikat baku pembanding (COA). Penambahan baku pembanding kedua/internal bersifat opsional.',
            'fields'      => [
                ['id' => 'baku_nama', 'label' => 'Baku Pembanding Utama', 'type' => 'text', 'placeholder' => 'Contoh: Asesulfam K / Aspartam / Na. Sakarin'],
                ['id' => 'baku_no_kontrol', 'label' => 'Nomor Kontrol', 'type' => 'text'],
                ['id' => 'baku_kadar', 'label' => 'Kadar / Potensi (desimal, mis. 0.9856 atau 100.41%)', 'type' => 'number', 'step' => 'any', 'default' => '1.0'],
                ['id' => 'baku_sp', 'label' => 'Susut Pengeringan / Kadar Air (%)', 'type' => 'number', 'step' => 'any', 'default' => '0'],
                ['id' => 'baku_tambahan_nama', 'label' => 'Baku Pembanding Tambahan (Opsional)', 'type' => 'text', 'placeholder' => 'Kosongkan jika hanya 1 baku'],
                ['id' => 'baku_tambahan_no_kontrol', 'label' => 'No. Kontrol Baku Tambahan (Opsional)', 'type' => 'text'],
                ['id' => 'baku_tambahan_kadar', 'label' => 'Kadar Baku Tambahan (Opsional)', 'type' => 'number', 'step' => 'any'],
                ['id' => 'baku_internal_nama', 'label' => 'Baku Internal (Opsional)', 'type' => 'text', 'default' => '-']
            ]
        ],
        [
            'id'          => 'tabel_baku_pembanding_kurva',
            'title'       => '1. Baku Pembanding & Kurva Regresi Linier Otomatis',
            'description' => 'Input bobot, faktor pengenceran (mis. 25 atau 5/25), serta Rt & Respon Puncak titik Baku Kerja. Sistem otomatis menghitung Konsentrasi, Persamaan Regresi (y = bx + a), nilai r, dan menggambar Grafik Kurva Baku.',
            'fields'      => [
                [
                    'id'           => 'tabel_baku_kurva',
                    'label'        => 'Tabel Penimbangan & Seri Konsentrasi Baku Pembanding',
                    'type'         => 'table',
                    'columns'      => [
                        ['id' => 'tahap', 'label' => 'Tahap Baku', 'type' => 'text', 'readonly' => true],
                        ['id' => 'wadah_zat', 'label' => 'Wadah+Zat (mg)', 'type' => 'number', 'step' => 'any'],
                        ['id' => 'wadah_sisa', 'label' => 'Wadah+Sisa (mg)', 'type' => 'number', 'step' => 'any'],
                        ['id' => 'bobot_bersih', 'label' => 'Bobot Bersih (mg)', 'type' => 'number', 'step' => 'any'],
                        ['id' => 'faktor_pengenceran', 'label' => 'Pengenceran (mL / V1/V2)', 'type' => 'text'],
                        ['id' => 'konsentrasi', 'label' => 'Konsentrasi (µg/mL)', 'type' => 'number', 'step' => 'any'],
                        ['id' => 'vol_injeksi', 'label' => 'Vol. Inj (µL)', 'type' => 'number', 'step' => 'any'],
                        ['id' => 'waktu_retensi', 'label' => 'Rt (menit)', 'type' => 'number', 'step' => 'any'],
                        ['id' => 'respon_puncak', 'label' => 'Respon Puncak', 'type' => 'number', 'step' => 'any'],
                        ['id' => 'lambda_nm', 'label' => 'λ (nm)', 'type' => 'number', 'step' => 'any']
                    ],
                    'default_rows' => [
                        ['tahap' => 'Baku Induk',  'wadah_zat' => '', 'wadah_sisa' => '', 'bobot_bersih' => '', 'faktor_pengenceran' => '25', 'konsentrasi' => '', 'vol_injeksi' => '', 'waktu_retensi' => '', 'respon_puncak' => '', 'lambda_nm' => ''],
                        ['tahap' => 'Bk Antara',   'wadah_zat' => '', 'wadah_sisa' => '', 'bobot_bersih' => '', 'faktor_pengenceran' => '5',  'konsentrasi' => '', 'vol_injeksi' => '', 'waktu_retensi' => '', 'respon_puncak' => '', 'lambda_nm' => ''],
                        ['tahap' => 'Bk Kerja 1',  'wadah_zat' => '', 'wadah_sisa' => '', 'bobot_bersih' => '', 'faktor_pengenceran' => '80', 'konsentrasi' => '', 'vol_injeksi' => '20', 'waktu_retensi' => '', 'respon_puncak' => '', 'lambda_nm' => '205'],
                        ['tahap' => 'Bk Kerja 2',  'wadah_zat' => '', 'wadah_sisa' => '', 'bobot_bersih' => '', 'faktor_pengenceran' => '40', 'konsentrasi' => '', 'vol_injeksi' => '20', 'waktu_retensi' => '', 'respon_puncak' => '', 'lambda_nm' => '205'],
                        ['tahap' => 'Bk Kerja 3',  'wadah_zat' => '', 'wadah_sisa' => '', 'bobot_bersih' => '', 'faktor_pengenceran' => '20', 'konsentrasi' => '', 'vol_injeksi' => '20', 'waktu_retensi' => '', 'respon_puncak' => '', 'lambda_nm' => '205'],
                        ['tahap' => 'Bk Kerja 4',  'wadah_zat' => '', 'wadah_sisa' => '', 'bobot_bersih' => '', 'faktor_pengenceran' => '10', 'konsentrasi' => '', 'vol_injeksi' => '20', 'waktu_retensi' => '', 'respon_puncak' => '', 'lambda_nm' => '205'],
                        ['tahap' => 'Bk Kerja 5',  'wadah_zat' => '', 'wadah_sisa' => '', 'bobot_bersih' => '', 'faktor_pengenceran' => '5',  'konsentrasi' => '', 'vol_injeksi' => '20', 'waktu_retensi' => '', 'respon_puncak' => '', 'lambda_nm' => '205'],
                        ['tahap' => 'Bk Kerja 6',  'wadah_zat' => '', 'wadah_sisa' => '', 'bobot_bersih' => '', 'faktor_pengenceran' => '2.5','konsentrasi' => '', 'vol_injeksi' => '20', 'waktu_retensi' => '', 'respon_puncak' => '', 'lambda_nm' => '205'],
                        ['tahap' => 'Bk Kerja 7',  'wadah_zat' => '', 'wadah_sisa' => '', 'bobot_bersih' => '', 'faktor_pengenceran' => '2',  'konsentrasi' => '', 'vol_injeksi' => '20', 'waktu_retensi' => '', 'respon_puncak' => '', 'lambda_nm' => '205']
                    ]
                ],
                ['id' => 'regresi_slope', 'label' => 'Slope (b) pada y = bx + a', 'type' => 'number', 'step' => 'any', 'readonly' => true],
                ['id' => 'regresi_intercept', 'label' => 'Intercept (a) pada y = bx + a', 'type' => 'number', 'step' => 'any', 'readonly' => true],
                ['id' => 'regresi_r', 'label' => 'Koefisien Korelasi (r)', 'type' => 'number', 'step' => 'any', 'readonly' => true],
                ['id' => 'syarat_r', 'label' => 'Syarat Linearitas r', 'type' => 'text', 'default' => '≥ 0,998']
            ]
        ],
        [
            'id'          => 'tabel_zat_uji_pangan',
            'title'       => '2. Zat Uji (Sampel Tanpa Spike - 2 Bagian)',
            'description' => 'Input penimbangan dan respon puncak untuk Zat Uji 1 dan Zat Uji 2. Jika sampel tidak mengandung analit / Respon Puncak = 0, isi Bobot Bersih = 0 atau biarkan Respon = 0.',
            'fields'      => [
                [
                    'id'           => 'tabel_zat_uji',
                    'label'        => 'Tabel Penimbangan & Pengukuran Zat Uji (Duplo)',
                    'type'         => 'table',
                    'columns'      => [
                        ['id' => 'tahap', 'label' => 'Zat Uji', 'type' => 'text', 'readonly' => true],
                        ['id' => 'wadah_zat', 'label' => 'Wadah+Zat (g)', 'type' => 'number', 'step' => 'any'],
                        ['id' => 'wadah_sisa', 'label' => 'Wadah+Sisa (g)', 'type' => 'number', 'step' => 'any'],
                        ['id' => 'bobot_bersih', 'label' => 'Bobot Bersih (g)', 'type' => 'number', 'step' => 'any'],
                        ['id' => 'faktor_pengenceran', 'label' => 'Faktor Pengenceran (mL)', 'type' => 'number', 'step' => 'any'],
                        ['id' => 'vol_injeksi', 'label' => 'Vol. Inj (µL)', 'type' => 'number', 'step' => 'any'],
                        ['id' => 'waktu_retensi', 'label' => 'Rt (menit)', 'type' => 'number', 'step' => 'any'],
                        ['id' => 'respon_puncak', 'label' => 'Respon Puncak', 'type' => 'number', 'step' => 'any'],
                        ['id' => 'lambda_nm', 'label' => 'λ (nm)', 'type' => 'number', 'step' => 'any']
                    ],
                    'default_rows' => [
                        ['tahap' => 'Zat Uji 1', 'wadah_zat' => '', 'wadah_sisa' => '', 'bobot_bersih' => '0', 'faktor_pengenceran' => '10', 'vol_injeksi' => '20', 'waktu_retensi' => '', 'respon_puncak' => '0', 'lambda_nm' => '205'],
                        ['tahap' => 'Zat Uji 2', 'wadah_zat' => '', 'wadah_sisa' => '', 'bobot_bersih' => '0', 'faktor_pengenceran' => '10', 'vol_injeksi' => '20', 'waktu_retensi' => '', 'respon_puncak' => '0', 'lambda_nm' => '205']
                    ]
                ]
            ]
        ],
        [
            'id'          => 'tabel_spiked_sample_pangan',
            'title'       => '3. Spiked Sample (Duplo)',
            'description' => 'Pengujian Spike dilakukan duplo (Spike 1 & Spike 2) untuk perhitungan % Recovery.',
            'fields'      => [
                ['id' => 'spike_vol_ml', 'label' => 'Volume Baku Ditambahkan (mL)', 'type' => 'number', 'step' => 'any', 'default' => '1'],
                ['id' => 'spike_sumber_konsentrasi', 'label' => 'Konsentrasi Baku Penambah (µg/mL, dari Bk Antara/Induk)', 'type' => 'number', 'step' => 'any'],
                ['id' => 'spike_berat_sampel', 'label' => 'Berat Sampel Target Spike (g)', 'type' => 'number', 'step' => 'any', 'default' => '1'],
                ['id' => 'kadar_baku_ditambahkan', 'label' => 'Kadar Baku Ditambahkan (mg/kg)', 'type' => 'number', 'step' => 'any', 'readonly' => true],
                [
                    'id'           => 'tabel_spiked_sample',
                    'label'        => 'Tabel Spiked Sample (Spike 1, Spike 2, & Respon)',
                    'type'         => 'table',
                    'columns'      => [
                        ['id' => 'tahap', 'label' => 'Spiked Sample', 'type' => 'text', 'readonly' => true],
                        ['id' => 'wadah_zat', 'label' => 'Wadah+Zat (g)', 'type' => 'number', 'step' => 'any'],
                        ['id' => 'wadah_sisa', 'label' => 'Wadah+Sisa (g)', 'type' => 'number', 'step' => 'any'],
                        ['id' => 'bobot_bersih', 'label' => 'Bobot Bersih (g)', 'type' => 'number', 'step' => 'any'],
                        ['id' => 'faktor_pengenceran', 'label' => 'Faktor Pengenceran (mL)', 'type' => 'number', 'step' => 'any'],
                        ['id' => 'vol_injeksi', 'label' => 'Vol. Inj (µL)', 'type' => 'number', 'step' => 'any'],
                        ['id' => 'waktu_retensi', 'label' => 'Rt (menit)', 'type' => 'number', 'step' => 'any'],
                        ['id' => 'respon_puncak', 'label' => 'Respon Puncak', 'type' => 'number', 'step' => 'any'],
                        ['id' => 'lambda_nm', 'label' => 'λ (nm)', 'type' => 'number', 'step' => 'any']
                    ],
                    'default_rows' => [
                        ['tahap' => 'Spiked Sample 1', 'wadah_zat' => '', 'wadah_sisa' => '', 'bobot_bersih' => '', 'faktor_pengenceran' => '10', 'vol_injeksi' => '20', 'waktu_retensi' => '', 'respon_puncak' => '', 'lambda_nm' => '205'],
                        ['tahap' => 'Spiked Sample 2', 'wadah_zat' => '', 'wadah_sisa' => '', 'bobot_bersih' => '', 'faktor_pengenceran' => '10', 'vol_injeksi' => '20', 'waktu_retensi' => '', 'respon_puncak' => '', 'lambda_nm' => '205']
                    ]
                ]
            ]
        ],
        [
            'id'          => 'perhitungan_pangan_hplc',
            'title'       => 'Perhitungan Kadar, Recovery Spike Duplo, RPD/Horwitz, & LOD/LOQ',
            'description' => 'Otomatis menghitung x1, x2, Kadar (mg/kg), % Recovery 1 & 2, RPD/RSD, dan 1/2 CV Horwitz. Input LOD & LOQ diisi manual sesuai instrumen.',
            'fields'      => [
                ['id' => 'x1_sampel', 'label' => 'x1 Sampel (µg/mL)', 'type' => 'number', 'step' => 'any', 'readonly' => true],
                ['id' => 'x2_sampel', 'label' => 'x2 Sampel (µg/mL)', 'type' => 'number', 'step' => 'any', 'readonly' => true],
                ['id' => 'kadar1_sampel', 'label' => 'Kadar Sampel 1 (mg/kg)', 'type' => 'number', 'step' => 'any', 'readonly' => true],
                ['id' => 'kadar2_sampel', 'label' => 'Kadar Sampel 2 (mg/kg)', 'type' => 'number', 'step' => 'any', 'readonly' => true],
                ['id' => 'rata_kadar_sampel', 'label' => 'Rata-rata Kadar Tanpa Spike (mg/kg)', 'type' => 'number', 'step' => 'any', 'readonly' => true],
                ['id' => 'x1_spike', 'label' => 'x1 Spike (µg/mL)', 'type' => 'number', 'step' => 'any', 'readonly' => true],
                ['id' => 'x2_spike', 'label' => 'x2 Spike (µg/mL)', 'type' => 'number', 'step' => 'any', 'readonly' => true],
                ['id' => 'kadar1_spike', 'label' => 'Kadar + Spike 1 (mg/kg)', 'type' => 'number', 'step' => 'any', 'readonly' => true],
                ['id' => 'kadar2_spike', 'label' => 'Kadar + Spike 2 (mg/kg)', 'type' => 'number', 'step' => 'any', 'readonly' => true],
                ['id' => 'rata_kadar_spike', 'label' => 'Rata-rata Kadar + Spike (mg/kg)', 'type' => 'number', 'step' => 'any', 'readonly' => true],
                ['id' => 'recovery_1', 'label' => 'Recovery 1 (%)', 'type' => 'number', 'step' => 'any', 'readonly' => true],
                ['id' => 'recovery_2', 'label' => 'Recovery 2 (%)', 'type' => 'number', 'step' => 'any', 'readonly' => true],
                ['id' => 'syarat_recovery', 'label' => 'Syarat Recovery (%)', 'type' => 'text', 'default' => '80 - 110 %'],
                ['id' => 'nilai_rpd', 'label' => 'RPD / RSD (%)', 'type' => 'number', 'step' => 'any', 'readonly' => true],
                ['id' => 'syarat_horwitz', 'label' => 'Syarat RPD (1/2 CV Horwitz %)', 'type' => 'number', 'step' => 'any', 'readonly' => true],
                ['id' => 'lod_nilai', 'label' => 'Nilai LOD (Angka)', 'type' => 'number', 'step' => 'any', 'placeholder' => 'Contoh: 1.0364'],
                ['id' => 'lod_satuan', 'label' => 'Satuan LOD', 'type' => 'text', 'default' => 'mg/kg'],
                ['id' => 'loq_nilai', 'label' => 'Nilai LOQ (Angka)', 'type' => 'number', 'step' => 'any', 'placeholder' => 'Contoh: 3.4547'],
                ['id' => 'loq_satuan', 'label' => 'Satuan LOQ', 'type' => 'text', 'default' => 'mg/kg'],
                ['id' => 'hasil_identifikasi', 'label' => 'Hasil Uji (Identifikasi)', 'type' => 'select', 'options' => ['-', 'Positif', 'Negatif'], 'default' => '-'],
                ['id' => 'ketidakpastian_teks', 'label' => 'Hasil Uji ± Ketidakpastian', 'type' => 'text', 'placeholder' => 'Contoh: < LOD atau 97.62 ± 2.10 mg/kg'],
                ['id' => 'decision_rule', 'label' => 'Aturan Keputusan (Decision Rule)', 'type' => 'select', 'options' => ['Simple Acceptance', 'Guard Band'], 'default' => 'Simple Acceptance']
            ]
        ],
        [
            'id'          => 'kesimpulan_legalitas_hplc',
            'title'       => 'Pustaka Persyaratan & Legalitas',
            'description' => 'Acuan metode, peraturan batas maksimum BTP, dan tanggal laporan.',
            'fields'      => [
                ['id' => 'pustaka_metode', 'label' => 'Pustaka Metode', 'type' => 'text', 'default' => 'MA PPOM 16/PA/13'],
                ['id' => 'pustaka_persyaratan', 'label' => 'Pustaka Persyaratan', 'type' => 'text', 'default' => 'PerBPOM No. 11 Tahun 2019'],
                ['id' => 'tolerance_limit', 'label' => 'Tolerance Limit (Syarat Maksimum)', 'type' => 'text', 'placeholder' => 'Contoh: Maks. 250 mg/kg'],
                ['id' => 'acceptance_limit', 'label' => 'Acceptance Limit', 'type' => 'text'],
                ['id' => 'tanggal_laporan', 'label' => 'Tanggal Laporan', 'type' => 'date']
            ]
        ]
    ]
];

$kode_tpl = 'PANG-KIM-01';
$nama_tpl = 'LCP KCKT / HPLC Pangan - Pemanis Simultan (MA PPOMN 16/PA/13)';
$json_str = json_encode($schema, JSON_UNESCAPED_UNICODE);

$chk = $mysqli->query("SELECT id FROM form_templates WHERE kode_template = '$kode_tpl'")->fetch_assoc();
if ($chk) {
    $tpl_id = (int)$chk['id'];
    $stmt = $mysqli->prepare("UPDATE form_templates SET nama_template=?, kategori='Kimia', komoditi='pangan', skema_form=?, status='Aktif' WHERE id=?");
    $stmt->bind_param("ssi", $nama_tpl, $json_str, $tpl_id);
    $stmt->execute();
    echo "Updated template $kode_tpl (ID: $tpl_id)\n";
} else {
    $stmt = $mysqli->prepare("INSERT INTO form_templates (kode_template, nama_template, kategori, komoditi, skema_form, status) VALUES (?, ?, 'Kimia', 'pangan', ?, 'Aktif')");
    $stmt->bind_param("sss", $kode_tpl, $nama_tpl, $json_str);
    $stmt->execute();
    $tpl_id = $mysqli->insert_id;
    echo "Inserted template $kode_tpl (ID: $tpl_id)\n";
}

// Auto-map Pangan Olahan Pemanis Simultan (16/PA/13: Sakarin, Asesulfam, Aspartam) ke PANG-KIM-01
$mysqli->query("UPDATE master_metode_pangan_olahan SET template_id = $tpl_id WHERE bidang='Kimia' AND (metode_analisa LIKE '%16/PA/13%' OR metode_analisa LIKE '%17/PA/13%' OR parameter_uji LIKE '%Sakarin%' OR parameter_uji LIKE '%Asesulfam%' OR parameter_uji LIKE '%Aspartam%')");
$mapped_po = $mysqli->affected_rows;
echo "Auto-mapped $mapped_po Pangan Olahan Pemanis rows to template ID $tpl_id.\n";
