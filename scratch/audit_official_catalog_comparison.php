<?php
define('BASEPATH', 'TRUE');
define('ENVIRONMENT', 'development');
require_once 'application/config/database.php';

$db_config = $db['default'];
$mysqli = new mysqli($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);
if ($mysqli->connect_error) {
    die("Connect error: " . $mysqli->connect_error);
}

// 1. Audit daftar resmi sesuai instruksi User
$daftar_resmi_lcp_kimia = [
    'REAKSI WARNA / PENGENDAPAN',
    'KERAGAMAN BOBOT',
    'KESERAGAMAN KANDUNGAN',
    'WAKTU HANCUR',
    'SPEKTROFOTOMETRI',
    'DISOLUSI',
    'KROMATOGRAFI LAPIS TIPIS',
    'KROMATOGRAFI CAIR KINERJA TINGGI',
    'KROMATOGRAFI GAS SPEKTROMETRI MASSA',
    'PEMERIAN',
    'pH',
    'BOBOT JENIS'
];

$daftar_resmi_lcp_mikro = [
    'UJI ANGKA KAPANG KHAMIR SEDIAAN FARMASI (AKK)',
    'UJI ANGKA KAPANG KHAMIR SEDIAAN FARMASI 2 (AKK2)',
    'UJI ANGKA LEMPENG TOTAL SEDIAAN FARMASI',
    'CEMARAN MIKROBA KUALITATIF SEDIAAN FARMASI',
    'POTENSI ANTIBIOTIKA METODE LEMPENG-SILINDER 5 DOSIS',
    'ENDOTOKSIN BAKTERI SEMI KUANTITATIF',
    'KONFIRMASI KEPEKAAN LAL SEMI KUANTITATIF'
];

$daftar_resmi_cp = [
    'CP KIMIA' => 'Kimia',
    'CP MIKROBIOLOGI' => 'Mikrobiologi'
];

echo "=== PERIKSA KECOCOKAN form_templates AKTUAL DENGAN DAFTAR RESMI ===\n";
$tpls = $mysqli->query("SELECT id, kode_template, nama_template, kategori, status FROM form_templates");
$existing_templates = [];
while ($t = $tpls->fetch_assoc()) {
    $existing_templates[$t['id']] = $t;
    echo "ID: {$t['id']} | [{$t['kode_template']}] {$t['nama_template']} | Kat: {$t['kategori']}\n";
}

echo "\n--- APAKAH ADA RECORD DENGAN NAMA DAFTAR RESMI? ---\n";
foreach ($daftar_resmi_lcp_kimia as $nr) {
    $found = false;
    foreach ($existing_templates as $t) {
        if (stripos($t['nama_template'], $nr) !== false) {
            echo "  [FOUND KIMIA] '$nr' matches ID {$t['id']}: {$t['nama_template']}\n";
            $found = true;
            break;
        }
    }
    if (!$found) echo "  [NOT FOUND KIMIA] '$nr'\n";
}

foreach ($daftar_resmi_lcp_mikro as $nr) {
    $found = false;
    foreach ($existing_templates as $t) {
        if (stripos($t['nama_template'], $nr) !== false) {
            echo "  [FOUND MIKRO] '$nr' matches ID {$t['id']}: {$t['nama_template']}\n";
            $found = true;
            break;
        }
    }
    if (!$found) echo "  [NOT FOUND MIKRO] '$nr'\n";
}

$mysqli->close();
