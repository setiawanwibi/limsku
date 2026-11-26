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

echo "1. Creating table master_lod_loq_logam...\n";
$mysqli->query("DROP TABLE IF EXISTS master_lod_loq_logam");
$sql_lod = "CREATE TABLE master_lod_loq_logam (
    id INT(11) NOT NULL AUTO_INCREMENT,
    elemen VARCHAR(20) NOT NULL,
    nama_parameter VARCHAR(150) NOT NULL,
    instrumen VARCHAR(50) NOT NULL DEFAULT 'AAS',
    metode_aas VARCHAR(100) NULL,
    wavelength VARCHAR(50) NULL,
    baku_pembanding VARCHAR(150) NULL,
    pelarut VARCHAR(255) NULL,
    larutan_pereduksi VARCHAR(150) NULL,
    pustaka_metode VARCHAR(100) NULL,
    lod_nilai DECIMAL(12,6) NOT NULL,
    loq_nilai DECIMAL(12,6) NOT NULL,
    satuan VARCHAR(30) NOT NULL DEFAULT 'mg/kg',
    PRIMARY KEY (id),
    UNIQUE KEY uq_elemen_instrumen (elemen, instrumen)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
if (!$mysqli->query($sql_lod)) {
    die("Error creating master_lod_loq_logam: " . $mysqli->error);
}

$refs = [
    ['As', 'PK Cemaran As', 'AAS', 'Hydride Vapour Generator', '193 nm',   'Arsenic Standard Solution 1000 mg/L', 'HCl (3,5 mL) : Reducing Agent (KI + Ascorbic Acid) (0,5 mL) : Aquadest', 'NaBH4 0,3% dan NaOH 0,1%', '16/PA/MA-PPPOMN/19',  0.039567, 0.119900, 'mg/kg'],
    ['Cd', 'PK Cemaran Cd', 'AAS', 'Graphite Furnace Atomizer', '224,6 nm', 'Cadmium Standard Solution 1000 mg/L', 'HNO3 0,1 N dengan modifier', '-', '18/PA/MA-PPPOMN/22',  0.090896, 0.275443, 'mg/kg'],
    ['Hg', 'PK Cemaran Hg', 'AAS', 'Hydride Vapour Generator', '253 nm',   'Mercury Standard Solution 1000 mg/L', 'HNO3 / HCl : Aquadest', 'NaBH4', '121/PA/MA-PPPOMN/20', 0.039197, 0.118779, 'mg/kg'],
    ['Pb', 'PK Cemaran Pb', 'AAS', 'Graphite Furnace Atomizer', '224,6 nm', 'Lead Standard Solution 1000 mg/L',    'HNO3 0,1 N dengan modifier', '-', '72/PA/MA-PPPOMN/18',  0.080400, 0.243637, 'mg/kg'],
    ['Sn', 'PK Cemaran Sn', 'AAS', 'Graphite Furnace Atomizer', '224,6 nm', 'Tin Standard Solution 1000 mg/L',     'HNO3 0,1 N dengan modifier Pd(NO3)2 100 ppm', '-', '11/PA/MA-PPPOMN/13',  0.875038, 2.651631, 'mg/kg'],
];

$stmt_lod = $mysqli->prepare("INSERT INTO master_lod_loq_logam (elemen, nama_parameter, instrumen, metode_aas, wavelength, baku_pembanding, pelarut, larutan_pereduksi, pustaka_metode, lod_nilai, loq_nilai, satuan) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
foreach ($refs as $r) {
    $stmt_lod->bind_param("sssssssssdds", $r[0], $r[1], $r[2], $r[3], $r[4], $r[5], $r[6], $r[7], $r[8], $r[9], $r[10], $r[11]);
    $stmt_lod->execute();
}
echo "   -> Seeded " . count($refs) . " AAS elements into master_lod_loq_logam.\n";

echo "2. Creating template PANG-KIM-AAS...\n";
$schema = [
    'kode_dokumen' => 'POM-06.02/CFM.01/SOP.01/F.01A.14',
    'judul'        => 'LAMPIRAN CATATAN PENGUJIAN - SPEKTROFOTOMETRI SERAPAN ATOM (AAS)',
    'kategori'     => 'Kimia',
    'sections'     => [
        [
            'id'          => 'kondisi_alat_aas',
            'title'       => 'Kondisi Instrumen AAS & Identitas Elemen Logam',
            'description' => 'Formulir resmi POM-06.02/CFM.01/SOP.01/F.01A.14. Pilih Elemen Logam untuk mengisi otomatis Wavelength, Metode AAS, Pustaka, serta LOD/LOQ referensi.',
            'fields'      => [
                [
                    'id'      => 'elemen_logam',
                    'label'   => 'Elemen Logam Target',
                    'type'    => 'select',
                    'options' => ['As', 'Cd', 'Hg', 'Pb', 'Sn'],
                    'default' => 'Pb'
                ],
                ['id' => 'merk_alat', 'label' => 'Merk Alat AAS', 'type' => 'text', 'default' => 'Analytic Jena'],
                ['id' => 'tipe_seri', 'label' => 'Tipe / Seri', 'type' => 'text', 'default' => 'ContraAA800D'],
                ['id' => 'wavelength', 'label' => 'Wavelength', 'type' => 'text', 'default' => '224,6 nm'],
                [
                    'id'      => 'metode_aas',
                    'label'   => 'Metode AAS',
                    'type'    => 'select',
                    'options' => ['Graphite Furnace Atomizer', 'Hydride Vapour Generator', 'Flame AAS'],
                    'default' => 'Graphite Furnace Atomizer'
                ],
                ['id' => 'baku_nama', 'label' => 'Baku Pembanding', 'type' => 'text', 'default' => 'Lead Standard Solution 1000 mg/L'],
                ['id' => 'pelarut', 'label' => 'Pelarut / Modifier', 'type' => 'text', 'default' => 'HNO3 0,1 N dengan modifier'],
                ['id' => 'larutan_pereduksi', 'label' => 'Larutan Pereduksi', 'type' => 'text', 'default' => '-'],
                ['id' => 'linieritas_baku', 'label' => 'Linieritas Baku', 'type' => 'text', 'default' => 'terlampir']
            ]
        ],
        [
            'id'          => 'tabel_baku_pembanding_kurva',
            'title'       => '1. Baku Pembanding & Regresi Kurva Baku AAS',
            'description' => 'Input Konsentrasi (µg/L / ppb) dan Serapan/Absorbansi untuk Blanko Baku & 6 titik Baku Kerja. Sistem menghitung y = bx + a, r (≥ 0,99), Sy/x, Vx0 (≤ 0,05), dan Grafik Kurva Baku.',
            'fields'      => [
                ['id' => 'blanko_baku_serapan', 'label' => 'Serapan Blanko Baku', 'type' => 'number', 'step' => 'any', 'default' => '0'],
                [
                    'id'           => 'tabel_baku_kurva',
                    'label'        => 'Tabel Baku Kerja AAS',
                    'type'         => 'table',
                    'columns'      => [
                        ['id' => 'tahap', 'label' => 'Tahap', 'type' => 'text', 'readonly' => true],
                        ['id' => 'faktor_pengenceran', 'label' => 'Faktor Pengenceran', 'type' => 'text'],
                        ['id' => 'konsentrasi', 'label' => 'Konsentrasi (Xi)', 'type' => 'number', 'step' => 'any'],
                        ['id' => 'respon_puncak', 'label' => 'Serapan / Absorbansi (Yi)', 'type' => 'number', 'step' => 'any']
                    ],
                    'default_rows' => [
                        ['tahap' => 'Baku 1', 'faktor_pengenceran' => '0.05/50*0.5/50', 'konsentrasi' => '10',  'respon_puncak' => ''],
                        ['tahap' => 'Baku 2', 'faktor_pengenceran' => '0.05/50*1/50',   'konsentrasi' => '20',  'respon_puncak' => ''],
                        ['tahap' => 'Baku 3', 'faktor_pengenceran' => '0.05/50*2/50',   'konsentrasi' => '40',  'respon_puncak' => ''],
                        ['tahap' => 'Baku 4', 'faktor_pengenceran' => '0.05/50*3/50',   'konsentrasi' => '60',  'respon_puncak' => ''],
                        ['tahap' => 'Baku 5', 'faktor_pengenceran' => '0.05/50*4/50',   'konsentrasi' => '80',  'respon_puncak' => ''],
                        ['tahap' => 'Baku 6', 'faktor_pengenceran' => '0.05/50*5/50',   'konsentrasi' => '100', 'respon_puncak' => '']
                    ]
                ],
                ['id' => 'regresi_slope', 'label' => 'Slope (b)', 'type' => 'number', 'step' => 'any', 'readonly' => true],
                ['id' => 'regresi_intercept', 'label' => 'Intercept (a)', 'type' => 'number', 'step' => 'any', 'readonly' => true],
                ['id' => 'regresi_r', 'label' => 'Corr. Coeff (r)', 'type' => 'number', 'step' => 'any', 'readonly' => true],
                ['id' => 'sy_x', 'label' => 'SD Residual (Sy/x)', 'type' => 'number', 'step' => 'any', 'readonly' => true],
                ['id' => 'vx0', 'label' => 'Vx0 (Syarat ≤ 0,05)', 'type' => 'number', 'step' => 'any', 'readonly' => true],
                ['id' => 'syarat_r', 'label' => 'Syarat Keberterimaan r', 'type' => 'text', 'default' => '≥ 0,99']
            ]
        ],
        [
            'id'          => 'tabel_zat_uji_pangan',
            'title'       => '2. Zat Uji (Sampel Tanpa Spike - 2 Bagian)',
            'description' => 'Input penimbangan (g), faktor pengenceran (mis. 50 atau 50/1*50), dan Serapan. Rumus Kadar (mg/kg) = (Konsentrasi x Fp x 10^-3) / W(g).',
            'fields'      => [
                ['id' => 'blanko_sampel_serapan', 'label' => 'Serapan Blanko Sampel', 'type' => 'number', 'step' => 'any', 'default' => '0'],
                [
                    'id'           => 'tabel_zat_uji',
                    'label'        => 'Tabel Penimbangan & Pengukuran Zat Uji (Duplo)',
                    'type'         => 'table',
                    'columns'      => [
                        ['id' => 'tahap', 'label' => 'Zat Uji', 'type' => 'text', 'readonly' => true],
                        ['id' => 'wadah_zat', 'label' => 'Wadah+Zat (g)', 'type' => 'number', 'step' => 'any'],
                        ['id' => 'wadah_sisa', 'label' => 'Wadah+Sisa (g)', 'type' => 'number', 'step' => 'any'],
                        ['id' => 'bobot_bersih', 'label' => 'Bobot Zat (g)', 'type' => 'number', 'step' => 'any'],
                        ['id' => 'faktor_pengenceran', 'label' => 'Faktor Pengenceran', 'type' => 'text'],
                        ['id' => 'respon_puncak', 'label' => 'Serapan (Absorbansi)', 'type' => 'number', 'step' => 'any']
                    ],
                    'default_rows' => [
                        ['tahap' => '1. Uji-01', 'wadah_zat' => '', 'wadah_sisa' => '', 'bobot_bersih' => '', 'faktor_pengenceran' => '50', 'respon_puncak' => ''],
                        ['tahap' => '2. Uji-02', 'wadah_zat' => '', 'wadah_sisa' => '', 'bobot_bersih' => '', 'faktor_pengenceran' => '50', 'respon_puncak' => '']
                    ]
                ]
            ]
        ],
        [
            'id'          => 'tabel_spiked_sample_pangan',
            'title'       => '3. Spiked Sample (Duplo)',
            'description' => 'Opsional jika dilakukan uji spike duplo.',
            'fields'      => [
                ['id' => 'spike_vol_ml', 'label' => 'Volume Baku Ditambahkan (mL)', 'type' => 'number', 'step' => 'any'],
                ['id' => 'spike_sumber_konsentrasi', 'label' => 'Konsentrasi Baku Penambah (mg/L)', 'type' => 'number', 'step' => 'any'],
                ['id' => 'spike_berat_sampel', 'label' => 'Berat Sampel Target Spike (g)', 'type' => 'number', 'step' => 'any'],
                ['id' => 'kadar_baku_ditambahkan', 'label' => 'Kadar Baku Ditambahkan (mg/kg)', 'type' => 'number', 'step' => 'any', 'readonly' => true],
                [
                    'id'           => 'tabel_spiked_sample',
                    'label'        => 'Tabel Spiked Sample (Duplo)',
                    'type'         => 'table',
                    'columns'      => [
                        ['id' => 'tahap', 'label' => 'Spiked Sample', 'type' => 'text', 'readonly' => true],
                        ['id' => 'wadah_zat', 'label' => 'Wadah+Zat (g)', 'type' => 'number', 'step' => 'any'],
                        ['id' => 'wadah_sisa', 'label' => 'Wadah+Sisa (g)', 'type' => 'number', 'step' => 'any'],
                        ['id' => 'bobot_bersih', 'label' => 'Bobot Zat (g)', 'type' => 'number', 'step' => 'any'],
                        ['id' => 'faktor_pengenceran', 'label' => 'Faktor Pengenceran', 'type' => 'text'],
                        ['id' => 'respon_puncak', 'label' => 'Serapan (Absorbansi)', 'type' => 'number', 'step' => 'any']
                    ],
                    'default_rows' => [
                        ['tahap' => 'Spiked 1', 'wadah_zat' => '', 'wadah_sisa' => '', 'bobot_bersih' => '', 'faktor_pengenceran' => '50', 'respon_puncak' => ''],
                        ['tahap' => 'Spiked 2', 'wadah_zat' => '', 'wadah_sisa' => '', 'bobot_bersih' => '', 'faktor_pengenceran' => '50', 'respon_puncak' => '']
                    ]
                ]
            ]
        ],
        [
            'id'          => 'perhitungan_pangan_aas',
            'title'       => 'Perhitungan Kadar Cemaran Logam, RPD, & LOD/LOQ Referensi Otomatis',
            'description' => 'LOD & LOQ ditarik otomatis dari tabel referensi master_lod_loq_logam berdasarkan Elemen Logam terpilih.',
            'fields'      => [
                ['id' => 'x1_sampel', 'label' => 'Konsentrasi Uji-1 (ppb)', 'type' => 'number', 'step' => 'any', 'readonly' => true],
                ['id' => 'x2_sampel', 'label' => 'Konsentrasi Uji-2 (ppb)', 'type' => 'number', 'step' => 'any', 'readonly' => true],
                ['id' => 'kadar1_sampel', 'label' => 'Kadar Cemaran Logam 1 (mg/kg)', 'type' => 'number', 'step' => 'any', 'readonly' => true],
                ['id' => 'kadar2_sampel', 'label' => 'Kadar Cemaran Logam 2 (mg/kg)', 'type' => 'number', 'step' => 'any', 'readonly' => true],
                ['id' => 'rata_kadar_sampel', 'label' => 'Rata-rata Kadar Cemaran Logam (mg/kg)', 'type' => 'number', 'step' => 'any', 'readonly' => true],
                ['id' => 'recovery_1', 'label' => 'Recovery 1 (%)', 'type' => 'number', 'step' => 'any', 'readonly' => true],
                ['id' => 'recovery_2', 'label' => 'Recovery 2 (%)', 'type' => 'number', 'step' => 'any', 'readonly' => true],
                ['id' => 'nilai_rpd', 'label' => 'RPD / RSD (%)', 'type' => 'number', 'step' => 'any', 'readonly' => true],
                ['id' => 'syarat_horwitz', 'label' => 'Syarat RPD (1/2 CV Horwitz %)', 'type' => 'number', 'step' => 'any', 'readonly' => true],
                ['id' => 'lod_nilai', 'label' => 'LOD Referensi (Auto)', 'type' => 'number', 'step' => 'any', 'readonly' => true],
                ['id' => 'lod_satuan', 'label' => 'Satuan LOD', 'type' => 'text', 'default' => 'mg/kg', 'readonly' => true],
                ['id' => 'loq_nilai', 'label' => 'LOQ Referensi (Auto)', 'type' => 'number', 'step' => 'any', 'readonly' => true],
                ['id' => 'loq_satuan', 'label' => 'Satuan LOQ', 'type' => 'text', 'default' => 'mg/kg', 'readonly' => true],
                ['id' => 'ketidakpastian_teks', 'label' => 'Hasil Uji ± Ketidakpastian', 'type' => 'text', 'placeholder' => 'Otomatis jika < LOD'],
                ['id' => 'decision_rule', 'label' => 'Aturan Keputusan (Decision Rule)', 'type' => 'select', 'options' => ['Simple Acceptance', 'Guard Band'], 'default' => 'Simple Acceptance']
            ]
        ],
        [
            'id'          => 'kesimpulan_legalitas_aas',
            'title'       => 'Pustaka Persyaratan & Legalitas',
            'fields'      => [
                ['id' => 'pustaka_metode', 'label' => 'Pustaka Metode', 'type' => 'text', 'default' => '72/PA/MA-PPPOMN/18'],
                ['id' => 'pustaka_persyaratan', 'label' => 'Pustaka Persyaratan', 'type' => 'text', 'default' => 'PerBPOM No. 9 Tahun 2022'],
                ['id' => 'tolerance_limit', 'label' => 'Tolerance Limit (Syarat Maksimum)', 'type' => 'text', 'placeholder' => 'Contoh: Maks. 0,30 mg/kg'],
                ['id' => 'acceptance_limit', 'label' => 'Acceptance Limit', 'type' => 'text'],
                ['id' => 'tanggal_laporan', 'label' => 'Tanggal Laporan', 'type' => 'date']
            ]
        ]
    ]
];

$kode_tpl = 'PANG-KIM-AAS';
$nama_tpl = 'LCP AAS Pangan - Cemaran Logam (As, Cd, Hg, Pb, Sn)';
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

// Auto-map parameter Logam (Pb, As, Hg, Cd, Sn) di Pangan Olahan yang belum dipetakan
$mysqli->query("UPDATE master_metode_pangan_olahan SET template_id = $tpl_id WHERE bidang='Kimia' AND template_id IS NULL AND (metode_analisa LIKE '%AAS%' OR metode_analisa LIKE '%16/PA%' OR metode_analisa LIKE '%72/PA%' OR metode_analisa LIKE '%121/PA%' OR metode_analisa LIKE '%18/PA%' OR metode_analisa LIKE '%11/PA%')");
echo "Auto-mapped " . $mysqli->affected_rows . " Pangan Olahan Logam/AAS rows to template ID $tpl_id.\n";
