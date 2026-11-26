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

$r02 = $mysqli->query("SELECT skema_form FROM form_templates WHERE kode_template = 'PANG-KIM-02'")->fetch_assoc();
if (!$r02) {
    die("PANG-KIM-02 not found.\n");
}
$schema = json_decode($r02['skema_form'], true);
$schema['judul'] = 'LAMPIRAN CATATAN PENGUJIAN - KROMATOGRAFI CAIR KINERJA TINGGI (SIKLAMAT)';

foreach ($schema['sections'] as &$sec) {
    if ($sec['id'] === 'kondisi_alat_kromatografi') {
        $sec['title'] = 'Kondisi Instrumen HPLC & Parameter Analisis (MA PPOMN 53/PA/16)';
        $sec['description'] = 'Formulir resmi POM-06.02/CFM.01/SOP.01/F.01A.12 untuk Pengujian Siklamat (Derivatisasi n-heptana).';
        foreach ($sec['fields'] as &$fld) {
            if ($fld['id'] === 'metode_analisis_pilihan') $fld['default'] = 'MA PPOM 53/PA/16 (KCKT - Siklamat)';
            if ($fld['id'] === 'merk_alat') $fld['default'] = 'Themoscientific';
            if ($fld['id'] === 'tipe_seri') $fld['default'] = 'Ultimate 3000';
            if ($fld['id'] === 'detektor') $fld['default'] = 'PDA';
            if ($fld['id'] === 'pelarut') $fld['default'] = 'n-heptane';
            if ($fld['id'] === 'kolom_dimensi') $fld['default'] = 'Welch, C18, 5 µm (250 mm x 4.6 mm)';
            if ($fld['id'] === 'tipe_elusi') $fld['default'] = 'Isokratik';
            if ($fld['id'] === 'fase_gerak_komposisi') $fld['default'] = 'Methanol - Aquadest (90:10)';
            if ($fld['id'] === 'panjang_gelombang') $fld['default'] = '314';
        }
    } elseif ($sec['id'] === 'baku_pembanding_identitas') {
        foreach ($sec['fields'] as &$fld) {
            if ($fld['id'] === 'baku_nama') $fld['placeholder'] = 'Na. Siklamat';
            if ($fld['id'] === 'faktor_konversi_bm') {
                $fld['label'] = 'Faktor Konversi BM (BM Asam Siklamat / BM Na.Siklamat)';
                $fld['default'] = '179.24/201.22';
            }
        }
    } elseif ($sec['id'] === 'tabel_baku_pembanding_kurva') {
        foreach ($sec['fields'] as &$fld) {
            if ($fld['id'] === 'tabel_baku_kurva') {
                $fld['default_rows'] = [
                    ['tahap' => 'Baku Induk', 'wadah_zat' => '', 'wadah_sisa' => '', 'bobot_bersih' => '', 'faktor_pengenceran' => '25',      'konsentrasi' => '', 'vol_injeksi' => '',   'waktu_retensi' => '', 'respon_puncak' => '', 'lambda_nm' => ''],
                    ['tahap' => 'Bk Kerja 1', 'wadah_zat' => '', 'wadah_sisa' => '', 'bobot_bersih' => '', 'faktor_pengenceran' => '0.0125/10','konsentrasi' => '', 'vol_injeksi' => '20', 'waktu_retensi' => '', 'respon_puncak' => '', 'lambda_nm' => '312'],
                    ['tahap' => 'Bk Kerja 2', 'wadah_zat' => '', 'wadah_sisa' => '', 'bobot_bersih' => '', 'faktor_pengenceran' => '0.25/10', 'konsentrasi' => '', 'vol_injeksi' => '20', 'waktu_retensi' => '', 'respon_puncak' => '', 'lambda_nm' => '312'],
                    ['tahap' => 'Bk Kerja 3', 'wadah_zat' => '', 'wadah_sisa' => '', 'bobot_bersih' => '', 'faktor_pengenceran' => '0.75/10', 'konsentrasi' => '', 'vol_injeksi' => '20', 'waktu_retensi' => '', 'respon_puncak' => '', 'lambda_nm' => '312'],
                    ['tahap' => 'Bk Kerja 4', 'wadah_zat' => '', 'wadah_sisa' => '', 'bobot_bersih' => '', 'faktor_pengenceran' => '1.25/10', 'konsentrasi' => '', 'vol_injeksi' => '20', 'waktu_retensi' => '', 'respon_puncak' => '', 'lambda_nm' => '312'],
                    ['tahap' => 'Bk Kerja 5', 'wadah_zat' => '', 'wadah_sisa' => '', 'bobot_bersih' => '', 'faktor_pengenceran' => '1.75/10', 'konsentrasi' => '', 'vol_injeksi' => '20', 'waktu_retensi' => '', 'respon_puncak' => '', 'lambda_nm' => '312'],
                    ['tahap' => 'Bk Kerja 6', 'wadah_zat' => '', 'wadah_sisa' => '', 'bobot_bersih' => '', 'faktor_pengenceran' => '2.25/10', 'konsentrasi' => '', 'vol_injeksi' => '20', 'waktu_retensi' => '', 'respon_puncak' => '', 'lambda_nm' => '312'],
                    ['tahap' => 'Bk Kerja 7', 'wadah_zat' => '', 'wadah_sisa' => '', 'bobot_bersih' => '', 'faktor_pengenceran' => '2.75/10', 'konsentrasi' => '', 'vol_injeksi' => '20', 'waktu_retensi' => '', 'respon_puncak' => '', 'lambda_nm' => '312'],
                    ['tahap' => 'Bk Kerja 8', 'wadah_zat' => '', 'wadah_sisa' => '', 'bobot_bersih' => '', 'faktor_pengenceran' => '3.25/10', 'konsentrasi' => '', 'vol_injeksi' => '20', 'waktu_retensi' => '', 'respon_puncak' => '', 'lambda_nm' => '312'],
                    ['tahap' => 'Bk Kerja 9', 'wadah_zat' => '', 'wadah_sisa' => '', 'bobot_bersih' => '', 'faktor_pengenceran' => '3.75/10', 'konsentrasi' => '', 'vol_injeksi' => '20', 'waktu_retensi' => '', 'respon_puncak' => '', 'lambda_nm' => '312']
                ];
            }
        }
    } elseif ($sec['id'] === 'tabel_zat_uji_pangan') {
        foreach ($sec['fields'] as &$fld) {
            if ($fld['id'] === 'tabel_zat_uji') {
                $fld['default_rows'] = [
                    ['tahap' => 'Zat Uji 1', 'wadah_zat' => '', 'wadah_sisa' => '', 'bobot_bersih' => '0', 'faktor_pengenceran' => '10', 'vol_injeksi' => '20', 'waktu_retensi' => '', 'respon_puncak' => '0', 'lambda_nm' => '312'],
                    ['tahap' => 'Zat Uji 2', 'wadah_zat' => '', 'wadah_sisa' => '', 'bobot_bersih' => '0', 'faktor_pengenceran' => '10', 'vol_injeksi' => '20', 'waktu_retensi' => '', 'respon_puncak' => '0', 'lambda_nm' => '312']
                ];
            }
        }
    } elseif ($sec['id'] === 'tabel_spiked_sample_pangan') {
        foreach ($sec['fields'] as &$fld) {
            if ($fld['id'] === 'spike_vol_ml') $fld['default'] = '1.25';
            if ($fld['id'] === 'spike_berat_sampel') $fld['default'] = '10';
            if ($fld['id'] === 'tabel_spiked_sample') {
                $fld['default_rows'] = [
                    ['tahap' => 'Spiked Sample 1', 'wadah_zat' => '', 'wadah_sisa' => '', 'bobot_bersih' => '', 'faktor_pengenceran' => '10', 'vol_injeksi' => '20', 'waktu_retensi' => '', 'respon_puncak' => '', 'lambda_nm' => '312'],
                    ['tahap' => 'Spiked Sample 2', 'wadah_zat' => '', 'wadah_sisa' => '', 'bobot_bersih' => '', 'faktor_pengenceran' => '10', 'vol_injeksi' => '20', 'waktu_retensi' => '', 'respon_puncak' => '', 'lambda_nm' => '312']
                ];
            }
        }
    } elseif ($sec['id'] === 'kesimpulan_legalitas_hplc') {
        foreach ($sec['fields'] as &$fld) {
            if ($fld['id'] === 'pustaka_metode') $fld['default'] = 'MA PPOM 53/PA/16';
        }
    }
}

$kode_tpl = 'PANG-KIM-03';
$nama_tpl = 'LCP KCKT / HPLC Pangan - Siklamat (MA PPOMN 53/PA/16)';
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

$mysqli->query("UPDATE master_metode_pangan_olahan SET template_id = $tpl_id WHERE bidang='Kimia' AND (metode_analisa LIKE '%53/PA/16%' OR parameter_uji LIKE '%Siklamat%')");
echo "Auto-mapped " . $mysqli->affected_rows . " Pangan Olahan Siklamat rows to template ID $tpl_id.\n";
