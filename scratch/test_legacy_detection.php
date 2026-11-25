<?php
define('ENVIRONMENT', 'development');
define('BASEPATH', 'dummy');

require_once __DIR__ . '/../application/libraries/SimpleXLSX.php';
require_once __DIR__ . '/../application/libraries/Excel_Importer.php';

function detect_commodity_format($rows, $sheet_name = '') {
    $scores = [
        'obat'     => 0,
        'pangan'   => 0,
        'kosmetik' => 0,
        'herbal'   => 0,
        'suplemen' => 0
    ];

    // 1. Periksa nama sheet jika ada
    $s_clean = strtolower(trim($sheet_name));
    if ($s_clean !== '') {
        if (strpos($s_clean, 'kosmetik') !== false) $scores['kosmetik'] += 60;
        if (strpos($s_clean, 'pangan') !== false) $scores['pangan'] += 60;
        if (strpos($s_clean, 'oba') !== false || strpos($s_clean, 'obat bahan alam') !== false || strpos($s_clean, 'herbal') !== false || strpos($s_clean, 'jamu') !== false) {
            $scores['herbal'] += 80;
        }
        if (preg_match('/\b(sk|suplemen)\b/i', $s_clean)) {
            $scores['suplemen'] += 80;
        }
        if (strpos($s_clean, 'obat') !== false && strpos($s_clean, 'obat bahan alam') === false && strpos($s_clean, 'obat tradisional') === false) {
            $scores['obat'] += 60;
        }
    }

    // 2. Periksa Banner / Baris Judul Awal (Baris 0 - 5)
    $banner_text = '';
    for ($i = 0; $i < min(6, count($rows)); $i++) {
        $banner_text .= ' ' . strtolower(implode(' ', array_filter($rows[$i])));
    }

    if (strpos($banner_text, 'kosmetik') !== false) {
        $scores['kosmetik'] += 100;
    }
    if (strpos($banner_text, 'obat bahan alam') !== false || strpos($banner_text, 'obat tradisional') !== false || strpos($banner_text, 'obaoksk') !== false) {
        $scores['herbal'] += 100;
    }
    if (strpos($banner_text, 'suplemen kesehatan') !== false || strpos($banner_text, 'suplemen') !== false) {
        $scores['suplemen'] += 100;
    }
    if (strpos($banner_text, 'sampel obat') !== false || (strpos($banner_text, 'obat') !== false && strpos($banner_text, 'bahan alam') === false && strpos($banner_text, 'tradisional') === false && strpos($banner_text, 'oba') === false)) {
        $scores['obat'] += 80;
    }
    if (strpos($banner_text, 'data contoh bulan') !== false && strpos($banner_text, 'balai besar pom bandar lampung') !== false && strpos($banner_text, 'obat') === false && strpos($banner_text, 'kosmetik') === false) {
        $scores['pangan'] += 60;
    }

    // 3. Deteksi Header & Kolom Karakteristik
    $header_info = Excel_Importer::detect_header_and_data_start($rows);
    $header_raw = $header_info['header'];
    $header_str = strtolower(implode(' ', array_filter($header_raw)));

    // Ciri khas OBAT (Eksklusif Kelas Terapi)
    if (strpos($header_str, 'kelas terapi') !== false || strpos($header_str, 'jenis terapi') !== false) {
        $scores['obat'] += 150;
    }
    if (strpos($header_str, 'balai penguji') !== false && strpos($header_str, 'sub kategori') !== false) {
        $scores['obat'] += 70;
    }

    // Ciri khas KOSMETIK
    if (strpos($header_str, 'nama contoh') !== false || strpos($header_str, 'kode contoh') !== false) {
        $scores['kosmetik'] += 120;
    }
    if (strpos($header_str, 'tipe produk') !== false || strpos($header_str, 'cluster') !== false || strpos($header_str, 'kategori kosmetik') !== false) {
        $scores['kosmetik'] += 120;
    }
    if (strpos($header_str, 'metode pengambilan') !== false || strpos($header_str, 'target uji bahan berbahaya') !== false || strpos($header_str, 'nomor notifikasi') !== false) {
        $scores['kosmetik'] += 120;
    }

    // Ciri khas PANGAN
    if (strpos($header_str, 'petugas sampling') !== false || strpos($header_str, 'tanggal produksi') !== false) {
        $scores['pangan'] += 150;
    }
    if (strpos($header_str, 'nama sarana') !== false && strpos($header_str, 'alamat') !== false && strpos($header_str, 'sipt') !== false && strpos($header_str, 'manual') !== false) {
        $scores['pangan'] += 80;
    }

    // Ciri khas OTSK & Legacy Herbal / Suplemen
    if (strpos($header_str, 'klaim khasiat') !== false || strpos($header_str, 'skrining bko') !== false || strpos($header_str, 'simplisia utama') !== false) {
        $scores['herbal'] += 150;
    }
    if (strpos($header_str, 'klaim suplemen') !== false) {
        $scores['suplemen'] += 150;
    }

    if (strpos($header_str, 'nama barang') !== false || strpos($header_str, 'komposisi zat berkhasiat') !== false || strpos($header_str, 'nama industri') !== false) {
        $scores['herbal'] += 60;
        $scores['suplemen'] += 60;
    }

    // 4. Periksa Sampel Data (NIE Prefix & Nomor Surtug / Kode)
    $first_data = $header_info['first_data_idx'];
    $data_text = '';
    for ($i = $first_data; $i < min($first_data + 10, count($rows)); $i++) {
        if (!empty(array_filter($rows[$i]))) {
            $data_text .= ' ' . implode(' ', array_filter($rows[$i]));
        }
    }

    // NIE Kosmetik: NA18..., NA11..., NA22...
    if (preg_match('/\bNA\d{11}\b/i', $data_text) || preg_match('/\bNA\d{9,12}\b/i', $data_text)) {
        $scores['kosmetik'] += 120;
    }
    // NIE Pangan: MD, ML, PIRT
    if (preg_match('/\b(MD|ML)\s*\d{10,15}\b/i', $data_text) || preg_match('/\bPIRT\b/i', $data_text)) {
        $scores['pangan'] += 120;
    }
    // NIE & Kode Herbal: POM TR, POM TI, POM QD, POM QL, /OT/
    if (preg_match('/\bPOM\s*T[RI]\b/i', $data_text) || strpos($data_text, '/OT/') !== false) {
        $scores['herbal'] += 150;
    }
    // NIE & Kode Suplemen: POM SD, POM SI, POM SL, /SK/
    if (preg_match('/\bPOM\s*S[DI]\b/i', $data_text) || strpos($data_text, '/SK/') !== false) {
        $scores['suplemen'] += 150;
    }
    // NIE Obat: DKL, DBL, GKL, etc.
    if (preg_match('/\b[DGB][KL][LIPC]\d{9,12}\b/i', $data_text)) {
        $scores['obat'] += 120;
    }

    arsort($scores);
    $top_commodity = key($scores);
    $top_score = current($scores);

    return [
        'detected' => ($top_score >= 50) ? $top_commodity : 'unknown',
        'score'    => $top_score,
        'scores'   => $scores
    ];
}

// Test legacy flat templates
$legacy_herbal = [
    ['No', 'Nama Sampel', 'Bentuk Sediaan', 'Nomor Izin Edar', 'No Bets Exp', 'Kategori Sarana', 'Nama Sarana', 'Kabupaten Kota', 'Tanggal Sampling', 'Surtug', 'Klaim Khasiat', 'Skrining BKO', 'Simplisia Utama', 'Nama Alamat Produsen'],
    ['1', 'Kapsul Ekstrak Temulawak', 'Kapsul', 'POM TR 192345671', 'BETS-01', 'Toko Obat', 'Toko Berkah', 'Lampung', '2026-06-01', 'ST/01', 'Khasiat hati', 'BKO Parasetamol', 'Curcuma', 'PT Herbal']
];
$res_lh = detect_commodity_format($legacy_herbal, 'Legacy Herbal');
echo "Legacy Herbal: DETECTED => {$res_lh['detected']} (Score: {$res_lh['score']})\n";

$legacy_suplemen = [
    ['No', 'Nama Sampel', 'Bentuk Sediaan', 'Nomor Izin Edar', 'No Bets Exp', 'Kategori Sarana', 'Nama Sarana', 'Kabupaten Kota', 'Tanggal Sampling', 'Surtug', 'Klaim Suplemen', 'Target Pengujian', 'Nama Alamat Produsen'],
    ['1', 'Vitamin C 1000mg', 'Tablet', 'POM SD 211512341', 'BETS-02', 'Apotek', 'Apotek KF', 'Bandar Lampung', '2026-06-01', 'ST/02', 'Daya tahan', 'Uji Vit C', 'PT Vita']
];
$res_ls = detect_commodity_format($legacy_suplemen, 'Legacy Suplemen');
echo "Legacy Suplemen: DETECTED => {$res_ls['detected']} (Score: {$res_ls['score']})\n";

$legacy_kosmetik = [
    ['No', 'Nama Sampel', 'Kategori Kosmetik', 'Nomor Notifikasi', 'Bentuk Sediaan', 'Asal Produk', 'Kategori Sarana', 'Nama Sarana', 'Kabupaten Kota', 'Tanggal Sampling', 'Surtug', 'Target Uji Bahan Berbahaya', 'Nama Alamat Produsen'],
    ['1', 'Day Cream SPF 30', 'Skincare', 'NA18240105678', 'Krim', 'Lokal', 'Klinik', 'Glow Center', 'Metro', '2026-06-01', 'ST/03', 'Uji Merkuri', 'PT Kosmetika']
];
$res_lk = detect_commodity_format($legacy_kosmetik, 'Legacy Kosmetik');
echo "Legacy Kosmetik: DETECTED => {$res_lk['detected']} (Score: {$res_lk['score']})\n";
