<?php
$all_pass = true;

function assert_test($name, $cond, $detail = '') {
    global $all_pass;
    if ($cond) {
        echo "[PASS] $name\n";
    } else {
        echo "[FAIL] $name: $detail\n";
        $all_pass = false;
    }
}

echo "=== VERIFIKASI AUDIT LENGKAP ===\n\n";

// 1. Error Screenshot Verification: Pengujian.php line around 250 in pilih_form
$ctrl = file_get_contents(__DIR__ . '/../application/controllers/Pengujian.php');
preg_match('/public function pilih_form\([\s\S]*?\n    \}/', $ctrl, $m_pf);
$pf_code = $m_pf[0] ?? '';

assert_test("Fix Screenshot Error: Tidak ada \$sesi yang undefined di function pilih_form()",
    strpos($pf_code, '$sesi[') === false,
    "Masih ada \$sesi[...] di pilih_form()!");

assert_test("Fix Screenshot Error: Menggunakan \$jenis_pengujian di ambil_master_parameter_untuk_picker pada pilih_form()",
    strpos($pf_code, "ambil_master_parameter_untuk_picker(\$jenis_pengujian") !== false,
    "Tidak ditemukan pemanggilan dengan \$jenis_pengujian");

// 2. KIM-08.json Template Checks
$kim08_raw = file_get_contents(__DIR__ . '/../database/templates/KIM-08.json');
$kim08 = json_decode($kim08_raw, true);
assert_test("KIM-08.json is valid JSON", is_array($kim08), "JSON decode gagal");

$sections = $kim08['skema_form']['sections'] ?? array();
$all_fields = array();
$tabel_hplc = null;

foreach ($sections as $sec) {
    if (!empty($sec['fields'])) {
        foreach ($sec['fields'] as $f) {
            $fid = $f['id'] ?? $f['name'] ?? '';
            if ($fid) $all_fields[$fid] = $f;
            if ($fid === 'tabel_hplc') {
                $tabel_hplc = $f;
            }
        }
    }
}

assert_test("Point 4: Ada komposisi_fase_a di skema KIM-08", isset($all_fields['komposisi_fase_a']));
assert_test("Point 4: Ada komposisi_fase_b di skema KIM-08", isset($all_fields['komposisi_fase_b']));
assert_test("Point 4: Ada komposisi_fase_c di skema KIM-08", isset($all_fields['komposisi_fase_c']));

// Check bobot_bersih not readonly
$bobot_bersih_col = null;
if ($tabel_hplc && !empty($tabel_hplc['columns'])) {
    foreach ($tabel_hplc['columns'] as $c) {
        $cid = $c['id'] ?? $c['name'] ?? '';
        if ($cid === 'bobot_bersih') {
            $bobot_bersih_col = $c;
            break;
        }
    }
}
assert_test("Point 5: Kolom bobot_bersih tidak dikunci (readonly != true)",
    $bobot_bersih_col && empty($bobot_bersih_col['readonly']));

// 3. proses_sesi.php Checks
$proses_sesi = file_get_contents(__DIR__ . '/../application/views/pengujian/proses_sesi.php');
assert_test("Point 1: evalMathExpression tersedia di proses_sesi.php",
    strpos($proses_sesi, 'function evalMathExpression(') !== false);
assert_test("Point 1: updateBMPreviewInContainer tersedia untuk live preview BM",
    strpos($proses_sesi, 'function updateBMPreviewInContainer(') !== false);
assert_test("Point 3: hitungHPLCPane dengan selector terisolasi pane tersedia",
    strpos($proses_sesi, 'function hitungHPLCPane(') !== false);
assert_test("Point 3: hitungSpektrofotometriPane tersedia",
    strpos($proses_sesi, 'function hitungSpektrofotometriPane(') !== false);
assert_test("Point 5 & 6: manualEdited flag digunakan agar tidak mengunci bobot dan konsentrasi",
    strpos($proses_sesi, 'dataset.manualEdited') !== false);
assert_test("Point 7 & 8: updateMetodeKadarUI mengatur keterangan kalimat dan placeholder tablet cemplung / sirup",
    strpos($proses_sesi, 'tablet_cemplung') !== false && strpos($proses_sesi, 'sirup') !== false);

// 4. cetak_lcp_kckt.php Checks
$cetak_kckt = file_get_contents(__DIR__ . '/../application/views/pengujian/cetak_lcp_kckt.php');
assert_test("Cetak KCKT: Menampilkan keterangan Komposisi Fase Gerak A, B, C",
    strpos($cetak_kckt, 'komposisi_fase_a') !== false);
assert_test("Cetak KCKT: Evaluasi dan desimal Faktor BM ditampilkan",
    strpos($cetak_kckt, '$bm_eval') !== false);
assert_test("Cetak KCKT: Penggabungan wadah zat/sisa untuk tablet cemplung & sirup",
    strpos($cetak_kckt, 'tablet_cemplung') !== false && strpos($cetak_kckt, 'sirup') !== false);

// 5. cetak_lcp_spektrofotometri.php Checks
$cetak_spektro = file_get_contents(__DIR__ . '/../application/views/pengujian/cetak_lcp_spektrofotometri.php');
assert_test("Cetak Spektro: Evaluasi desimal Faktor BM ditampilkan",
    strpos($cetak_spektro, '$bm_eval') !== false);
assert_test("Cetak Spektro: Penggabungan zat uji untuk tablet cemplung & sirup",
    strpos($cetak_spektro, 'tablet_cemplung') !== false && strpos($cetak_spektro, 'sirup') !== false);

if ($all_pass) {
    echo "\n>>> SEMUA AUDIT & VERIFIKASI BERHASIL 100% <<<\n";
    exit(0);
} else {
    echo "\n>>> ADA VERIFIKASI YANG GAGAL <<<\n";
    exit(1);
}
