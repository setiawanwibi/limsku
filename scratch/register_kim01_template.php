<?php
define('BASEPATH', 'TRUE');
define('ENVIRONMENT', 'development');
require_once __DIR__ . '/../application/config/database.php';

$db_cfg = $db['default'];
$mysqli = @new mysqli($db_cfg['hostname'], $db_cfg['username'], $db_cfg['password'], $db_cfg['database']);
if ($mysqli->connect_error) {
    die("DB error: " . $mysqli->connect_error);
}

$kode_template = 'KIM-01';
$nama_template = 'Reaksi Warna / Pengendapan';
$kategori = 'Kimia';

$skema = [
    'kode_dokumen' => '',
    'judul' => 'REAKSI WARNA / PENGENDAPAN',
    'kategori' => 'Kimia',
    'sections' => [
        [
            'id' => 'tabel_pengujian_reaksi',
            'title' => 'Tabel Pengujian Reaksi Warna / Pengendapan',
            'description' => 'Isi data penimbangan bobot, pereaksi, hasil pengamatan reaksi warna/endapan, dan interpretasi hasil.',
            'fields' => [
                [
                    'id' => 'tabel_reaksi',
                    'label' => 'Tabel Reaksi Warna / Pengendapan',
                    'type' => 'table',
                    'columns' => [
                        ['id' => 'nama_zat', 'label' => 'Nama zat', 'readonly' => true],
                        ['id' => 'bobot', 'label' => 'Bobot (satuan)'],
                        ['id' => 'pereaksi', 'label' => 'Pereaksi'],
                        ['id' => 'pengamatan', 'label' => 'Pengamatan'],
                        ['id' => 'hasil', 'label' => 'Hasil (Positif/Negatif)']
                    ],
                    'default_rows' => [
                        [
                            'nama_zat' => 'Baku Pembanding :',
                            'bobot' => '',
                            'pereaksi' => '',
                            'pengamatan' => '',
                            'hasil' => ''
                        ],
                        [
                            'nama_zat' => "Zat Uji\n1. :",
                            'bobot' => '',
                            'pereaksi' => '',
                            'pengamatan' => '',
                            'hasil' => ''
                        ],
                        [
                            'nama_zat' => "2. :",
                            'bobot' => '',
                            'pereaksi' => '',
                            'pengamatan' => '',
                            'hasil' => ''
                        ],
                        [
                            'nama_zat' => "Spiked sample :\nZat Uji :",
                            'bobot' => '',
                            'pereaksi' => '',
                            'pengamatan' => '',
                            'hasil' => ''
                        ],
                        [
                            'nama_zat' => "Baku Pembanding :",
                            'bobot' => '',
                            'pereaksi' => '',
                            'pengamatan' => '',
                            'hasil' => ''
                        ]
                    ]
                ]
            ]
        ],
        [
            'id' => 'pengamatan_lanjutan',
            'title' => 'Hasil Pengamatan & Sensitivitas (LOD)',
            'description' => 'Dokumentasi foto dan parameter analit uji.',
            'fields' => [
                [
                    'id' => 'keterangan_foto',
                    'label' => 'Keterangan Hasil Pengamatan',
                    'type' => 'text',
                    'default' => 'foto hasil pengamatan terlampir'
                ],
                [
                    'id' => 'foto_pengamatan',
                    'label' => 'Unggah Foto Hasil Pengamatan Reaksi Warna / Pengendapan',
                    'type' => 'file',
                    'accept' => 'image/*',
                    'keterangan' => 'Format file: JPG, PNG, atau JPEG.'
                ],
                [
                    'id' => 'analit',
                    'label' => 'Analit',
                    'type' => 'text',
                    'placeholder' => 'Nama analit...'
                ],
                [
                    'id' => 'lod',
                    'label' => 'LOD (Limit of Detection)',
                    'type' => 'text',
                    'placeholder' => 'Contoh: 0.1 mg/mL'
                ]
            ]
        ],
        [
            'id' => 'acuan_pustaka',
            'title' => 'Pustaka & Persyaratan',
            'description' => 'Metode analisa dan baku persyaratan mutu.',
            'fields' => [
                [
                    'id' => 'pustaka_metode',
                    'label' => 'Pustaka Metode',
                    'type' => 'text',
                    'placeholder' => 'Contoh: Farmakope Indonesia Edisi VI'
                ],
                [
                    'id' => 'pustaka_persyaratan',
                    'label' => 'Pustaka Persyaratan',
                    'type' => 'text',
                    'placeholder' => 'Contoh: Farmakope Indonesia Edisi VI'
                ],
                [
                    'id' => 'syarat',
                    'label' => 'Syarat',
                    'type' => 'text',
                    'placeholder' => 'Kriteria penerimaan reaksi...'
                ]
            ]
        ],
        [
            'id' => 'kesimpulan_opsi',
            'title' => 'Pilihan Kesimpulan LCP',
            'description' => 'Pilih interpretasi kesimpulan pengujian sesuai blanko resmi.',
            'fields' => [
                [
                    'id' => 'pilihan_kesimpulan',
                    'label' => 'Pilihan Kesimpulan',
                    'type' => 'select',
                    'options' => [
                        'Memenuhi Syarat (MS)',
                        'Tidak Memenuhi Syarat (TMS)',
                        'Diduga mengandung'
                    ],
                    'default' => 'Memenuhi Syarat (MS)'
                ],
                [
                    'id' => 'diduga_mengandung_text',
                    'label' => 'Jika Diduga Mengandung (sebutkan zat):',
                    'type' => 'text',
                    'placeholder' => 'Nama zat yang diduga terkandung...'
                ]
            ]
        ]
    ]
];

$skema_json = json_encode($skema, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

// Check if already exists
$stmt = $mysqli->prepare("SELECT id FROM form_templates WHERE kode_template = ?");
$stmt->bind_param("s", $kode_template);
$stmt->execute();
$res = $stmt->get_result();

if ($row = $res->fetch_assoc()) {
    $tpl_id = $row['id'];
    $up = $mysqli->prepare("UPDATE form_templates SET nama_template = ?, kategori = ?, skema_form = ?, status = 'Aktif', updated_at = NOW() WHERE id = ?");
    $up->bind_param("sssi", $nama_template, $kategori, $skema_json, $tpl_id);
    $up->execute();
    echo "SUCCESS: Updated existing form_template ID $tpl_id ($kode_template)\n";
} else {
    $ins = $mysqli->prepare("INSERT INTO form_templates (kode_template, nama_template, kategori, skema_form, status, created_at, updated_at) VALUES (?, ?, ?, ?, 'Aktif', NOW(), NOW())");
    $ins->bind_param("ssss", $kode_template, $nama_template, $kategori, $skema_json);
    $ins->execute();
    $tpl_id = $mysqli->insert_id;
    echo "SUCCESS: Created new form_template ID $tpl_id ($kode_template)\n";
}

$mysqli->close();
