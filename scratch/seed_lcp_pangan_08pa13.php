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

// Ambil skema dasar dari PANG-KIM-01 lalu sesuaikan untuk 08/PA/13 (Pengawet Simultan)
$r01 = $mysqli->query("SELECT skema_form FROM form_templates WHERE kode_template = 'PANG-KIM-01'")->fetch_assoc();
if (!$r01) {
    die("PANG-KIM-01 not found.\n");
}
$schema = json_decode($r01['skema_form'], true);
$schema['judul'] = 'LAMPIRAN CATATAN PENGUJIAN - KROMATOGRAFI CAIR KINERJA TINGGI (PENGAWET SIMULTAN)';

// Update Kondisi Instrumen sesuai 08/PA/13
foreach ($schema['sections'] as &$sec) {
    if ($sec['id'] === 'kondisi_alat_kromatografi') {
        $sec['title'] = 'Kondisi Instrumen HPLC & Parameter Analisis (MA PPOMN 08/PA/13)';
        $sec['description'] = 'Formulir resmi POM-06.02/CFM.01/SOP.01/F.01A.12 untuk Pengujian Pengawet Simultan (Benzoat, Sorbat, Sakarin).';
        foreach ($sec['fields'] as &$fld) {
            if ($fld['id'] === 'metode_analisis_pilihan') $fld['default'] = 'MA PPOM 08/PA/13 (KCKT UV - Pengawet & Sakarin)';
            if ($fld['id'] === 'detektor') $fld['default'] = 'PDA';
            if ($fld['id'] === 'kolom_dimensi') $fld['default'] = 'Welch, C18, 5 µm (150 mm x 4.6 mm)';
            if ($fld['id'] === 'tipe_elusi') $fld['default'] = 'Isokratik';
            if ($fld['id'] === 'fase_gerak_komposisi') $fld['default'] = 'K2HPO4 - KH2PO4 pH 6,8 : Methanol (96:4)';
            if ($fld['id'] === 'panjang_gelombang') $fld['default'] = '225';
            if ($fld['id'] === 'tabel_gradient') {
                $fld['default_rows'] = [
                    ['waktu_menit' => '', 'fase_a' => '', 'fase_b' => '', 'fase_c' => '']
                ];
            }
        }
    } elseif ($sec['id'] === 'baku_pembanding_identitas') {
        $sec['fields'][] = [
            'id' => 'faktor_konversi_bm',
            'label' => 'Faktor Konversi BM (BM Asam / BM Garam, mis. 122.12/144.1)',
            'type' => 'text',
            'default' => '122.12/144.1',
            'placeholder' => 'Isi 1 jika tanpa konversi garam'
        ];
    } elseif ($sec['id'] === 'tabel_baku_pembanding_kurva') {
        foreach ($sec['fields'] as &$fld) {
            if ($fld['id'] === 'tabel_baku_kurva') {
                $fld['default_rows'] = [
                    ['tahap' => 'Baku Induk',  'wadah_zat' => '', 'wadah_sisa' => '', 'bobot_bersih' => '', 'faktor_pengenceran' => '25',    'konsentrasi' => '', 'vol_injeksi' => '',   'waktu_retensi' => '', 'respon_puncak' => '', 'lambda_nm' => ''],
                    ['tahap' => 'Bk Antara',   'wadah_zat' => '', 'wadah_sisa' => '', 'bobot_bersih' => '', 'faktor_pengenceran' => '1/10',  'konsentrasi' => '', 'vol_injeksi' => '',   'waktu_retensi' => '', 'respon_puncak' => '', 'lambda_nm' => ''],
                    ['tahap' => 'Bk Kerja 1',  'wadah_zat' => '', 'wadah_sisa' => '', 'bobot_bersih' => '', 'faktor_pengenceran' => '0.1/50','konsentrasi' => '', 'vol_injeksi' => '20', 'waktu_retensi' => '', 'respon_puncak' => '', 'lambda_nm' => '225'],
                    ['tahap' => 'Bk Kerja 2',  'wadah_zat' => '', 'wadah_sisa' => '', 'bobot_bersih' => '', 'faktor_pengenceran' => '0.2/50','konsentrasi' => '', 'vol_injeksi' => '20', 'waktu_retensi' => '', 'respon_puncak' => '', 'lambda_nm' => '225'],
                    ['tahap' => 'Bk Kerja 3',  'wadah_zat' => '', 'wadah_sisa' => '', 'bobot_bersih' => '', 'faktor_pengenceran' => '0.8/50','konsentrasi' => '', 'vol_injeksi' => '20', 'waktu_retensi' => '', 'respon_puncak' => '', 'lambda_nm' => '225'],
                    ['tahap' => 'Bk Kerja 4',  'wadah_zat' => '', 'wadah_sisa' => '', 'bobot_bersih' => '', 'faktor_pengenceran' => '0.3/10','konsentrasi' => '', 'vol_injeksi' => '20', 'waktu_retensi' => '', 'respon_puncak' => '', 'lambda_nm' => '225'],
                    ['tahap' => 'Bk Kerja 5',  'wadah_zat' => '', 'wadah_sisa' => '', 'bobot_bersih' => '', 'faktor_pengenceran' => '0.6/10','konsentrasi' => '', 'vol_injeksi' => '20', 'waktu_retensi' => '', 'respon_puncak' => '', 'lambda_nm' => '225'],
                    ['tahap' => 'Bk Kerja 6',  'wadah_zat' => '', 'wadah_sisa' => '', 'bobot_bersih' => '', 'faktor_pengenceran' => '0.6/5', 'konsentrasi' => '', 'vol_injeksi' => '20', 'waktu_retensi' => '', 'respon_puncak' => '', 'lambda_nm' => '225'],
                    ['tahap' => 'Bk Kerja 7',  'wadah_zat' => '', 'wadah_sisa' => '', 'bobot_bersih' => '', 'faktor_pengenceran' => '1.3/5', 'konsentrasi' => '', 'vol_injeksi' => '20', 'waktu_retensi' => '', 'respon_puncak' => '', 'lambda_nm' => '225'],
                    ['tahap' => 'Bk Kerja 8',  'wadah_zat' => '', 'wadah_sisa' => '', 'bobot_bersih' => '', 'faktor_pengenceran' => '2.6/5', 'konsentrasi' => '', 'vol_injeksi' => '20', 'waktu_retensi' => '', 'respon_puncak' => '', 'lambda_nm' => '225']
                ];
            }
        }
    } elseif ($sec['id'] === 'tabel_zat_uji_pangan') {
        foreach ($sec['fields'] as &$fld) {
            if ($fld['id'] === 'tabel_zat_uji') {
                $fld['columns'][4]['type'] = 'text';
                $fld['default_rows'] = [
                    ['tahap' => 'Zat Uji 1', 'wadah_zat' => '', 'wadah_sisa' => '', 'bobot_bersih' => '0', 'faktor_pengenceran' => '50/2*10', 'vol_injeksi' => '20', 'waktu_retensi' => '', 'respon_puncak' => '0', 'lambda_nm' => '225'],
                    ['tahap' => 'Zat Uji 2', 'wadah_zat' => '', 'wadah_sisa' => '', 'bobot_bersih' => '0', 'faktor_pengenceran' => '50/2*10', 'vol_injeksi' => '20', 'waktu_retensi' => '', 'respon_puncak' => '0', 'lambda_nm' => '225']
                ];
            }
        }
    } elseif ($sec['id'] === 'tabel_spiked_sample_pangan') {
        foreach ($sec['fields'] as &$fld) {
            if ($fld['id'] === 'spike_berat_sampel') $fld['default'] = '2';
            if ($fld['id'] === 'tabel_spiked_sample') {
                $fld['columns'][4]['type'] = 'text';
                $fld['default_rows'] = [
                    ['tahap' => 'Spiked Sample 1', 'wadah_zat' => '', 'wadah_sisa' => '', 'bobot_bersih' => '', 'faktor_pengenceran' => '50/2*10', 'vol_injeksi' => '20', 'waktu_retensi' => '', 'respon_puncak' => '', 'lambda_nm' => '225'],
                    ['tahap' => 'Spiked Sample 2', 'wadah_zat' => '', 'wadah_sisa' => '', 'bobot_bersih' => '', 'faktor_pengenceran' => '50/2*10', 'vol_injeksi' => '20', 'waktu_retensi' => '', 'respon_puncak' => '', 'lambda_nm' => '225']
                ];
            }
        }
    } elseif ($sec['id'] === 'perhitungan_pangan_hplc') {
        foreach ($sec['fields'] as &$fld) {
            if ($fld['id'] === 'syarat_recovery') $fld['default'] = '90 - 107 %';
        }
    } elseif ($sec['id'] === 'kesimpulan_legalitas_hplc') {
        foreach ($sec['fields'] as &$fld) {
            if ($fld['id'] === 'pustaka_metode') $fld['default'] = 'MA PPOM 08/PA/13';
        }
    }
}

$kode_tpl = 'PANG-KIM-02';
$nama_tpl = 'LCP KCKT / HPLC Pangan - Pengawet Simultan (MA PPOMN 08/PA/13)';
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

// Auto-map Pangan Olahan Pengawet Simultan (08/PA/13: Benzoat, Sorbat) ke PANG-KIM-02
$mysqli->query("UPDATE master_metode_pangan_olahan SET template_id = $tpl_id WHERE bidang='Kimia' AND (metode_analisa LIKE '%08/PA/13%' OR metode_analisa LIKE '%19/PA/15%' OR parameter_uji LIKE '%Benzoat%' OR parameter_uji LIKE '%Sorbat%')");
echo "Auto-mapped " . $mysqli->affected_rows . " Pangan Olahan Pengawet rows to template ID $tpl_id.\n";
