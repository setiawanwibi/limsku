<?php
define('ENVIRONMENT', 'development');
define('BASEPATH', 'dummy');

require_once __DIR__ . '/../application/libraries/SimpleXLSX.php';
require_once __DIR__ . '/../application/libraries/SimpleXLSXGen.php';
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

    // Ciri khas KOSMETIK (Eksklusif Tipe Produk, Metode Pengambilan, Cluster, Nama Contoh)
    if (strpos($header_str, 'nama contoh') !== false || strpos($header_str, 'kode contoh') !== false) {
        $scores['kosmetik'] += 120;
    }
    if (strpos($header_str, 'tipe produk') !== false || strpos($header_str, 'cluster') !== false || strpos($header_str, 'kategori kosmetik') !== false) {
        $scores['kosmetik'] += 120;
    }
    if (strpos($header_str, 'metode pengambilan') !== false || strpos($header_str, 'target uji bahan berbahaya') !== false) {
        $scores['kosmetik'] += 100;
    }

    // Ciri khas PANGAN (Petugas sampling, Tanggal produksi, Manual SIPT)
    if (strpos($header_str, 'petugas sampling') !== false || strpos($header_str, 'tanggal produksi') !== false) {
        $scores['pangan'] += 150;
    }
    if (strpos($header_str, 'nama sarana') !== false && strpos($header_str, 'alamat') !== false && strpos($header_str, 'sipt') !== false && strpos($header_str, 'manual') !== false) {
        $scores['pangan'] += 80;
    }

    // Ciri khas OTSK (NAMA BARANG + KOMPOSISI ZAT BERKHASIAT + NAMA INDUSTRI)
    $is_otsk = false;
    if (strpos($header_str, 'nama barang') !== false || strpos($header_str, 'komposisi zat berkhasiat') !== false || strpos($header_str, 'nama industri') !== false) {
        $is_otsk = true;
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

$files = [
    'Kosmetik Asli' => 'C:\\Users\\ASUS\\OneDrive\\Dokumen\\Magang BPOM\\Data BPOM\\Format Data Sampel\\Sampel Kosmetik\\10. Data Contoh Kosmetik Oktober 2026 - 2.xlsx',
    'Obat Asli'     => 'C:\\Users\\ASUS\\OneDrive\\Dokumen\\Magang BPOM\\Data BPOM\\Format Data Sampel\\Sampel Obat\\Data Contoh Jambi Juni 2026.xlsx',
    'OTSK Asli'     => 'C:\\Users\\ASUS\\OneDrive\\Dokumen\\Magang BPOM\\Data BPOM\\Format Data Sampel\\Sampel Obat Tradisional dan Suplemen\\6. Data contoh OTSK JUNI 2026 NEW.xlsx',
    'Pangan Asli'   => 'C:\\Users\\ASUS\\OneDrive\\Dokumen\\Magang BPOM\\Data BPOM\\Format Data Sampel\\Sampel Pangan\\4. Data Contoh April 2026_print.xlsx'
];

echo "\n========================================================\n";
echo "TESTING DETECTION FOR GENERATED TEMPLATES\n";
echo "========================================================\n";

$komoditis = ['obat', 'pangan', 'kosmetik', 'herbal', 'suplemen'];
foreach ($komoditis as $kom) {
    // Simulate template rows as in download_template
    $rows = [];
    if ($kom === 'pangan') {
        $rows[] = array('', 'Data Contoh Bulan ' . date('F Y'), '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');
        $rows[] = array('', '', '', '', '', '', '', '', '', '', 'BALAI BESAR POM BANDAR LAMPUNG', '', '', '', '', '', '', '', '', '', '', '');
        $rows[] = array('Petugas Sampling', 'No.', 'No', 'No Sampel', '', 'Tempat dan Tanggal Sampling', '', 'Tanggal Sampling', 'Kategori Sampel', 'Nama Sampel', 'NIE', 'Pabrik/ Importir', 'No Bets', 'Tanggal produksi', 'Komposisi', 'Tgl Kadaluarsa', 'Kemasan', 'Jumlah', '', '', '', '');
        $rows[] = array('', '', '', 'Manual', 'SIPT', 'Nama Sarana', 'alamat', '', '', '', '', '', '', '', '', '', '', 'Ki', 'Mi', 'Arsip', 'P', 'Total');
        $rows[] = array('ELFINA', '1', '78', 'P/BL/IV/26', '26.090.301.13.01.0078', 'Pasar Tugu', 'JL.Hayam Wuruk Bandar Lampung', date('Y-m-d'), '11.1.5 Gula kristal putih', 'Gulaku', 'MD 120867000100167', 'PT.Gula Putih Mataram', '2A 23;07', date('Y-m-d'), 'gula tebu', date('Y-m-d'), 'Plastik 1 kg', '3', '0', '2', '1', '6');
    } elseif ($kom === 'herbal') {
        $rows[] = array('', 'DATA CONTOH OBAT BAHAN ALAM BULAN ' . strtoupper(date('F Y')), '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');
        $rows[] = array('', '', 'NO', '', 'NAMA BARANG', 'BENTUK SEDIAAN', 'Jenis Sampel', 'KOMPOSISI ZAT BERKHASIAT', 'NAMA INDUSTRI', 'NIE', '', 'NO. BATCH', 'ED', 'KEMASAN', 'PENGAMBILAN', '', 'Jlh', '', '', '', '', '', 'KET');
        $rows[] = array('', '', '', '', '', '', '', '', '', '', '', '', '', '', 'TEMPAT', 'TANGGAL', 'K', 'M', 'R', 'P', 'A', 'J', '');
        $rows[] = array('', 'NO.', '1.0', '', '2.0', '3.0', '4.0', '5.0', '6.0', '7.0', '', '8.0', '9.0', '10.0', '11.0', '12.0', '13.0', '', '', '', '', '', '14.0');
        $rows[] = array('', '1', '106', '/OT/T-Ki-Mi/POM/LT/VI/26', 'RemAct', 'Kapsul', 'Import-Online', "Apium Graveolens Semen", "Di Produksi Oleh : DOT GREEN", 'POM TI', '094340391', 'M015/2025', '06 / 2028', 'Dus, Botol@ 30 Kapsul', 'Apotek Rafael', date('d F Y'), '1', '1', '0', '0', '1', '3', '');
    } elseif ($kom === 'kosmetik') {
        $rows[] = array('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');
        $rows[] = array('', 'Lampiran Sampling Produk Kosmetik', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');
        $rows[] = array('', 'No. Kode Contoh', '', 'Nama Contoh', 'NIE', 'Kategori Sampel', 'Tipe Produk-Kategori', 'Metode Pengambilan', 'Tanggal Sampling', 'Komposisi', "Kemasan/\n  Netto", 'Bentuk Sediaan', 'Bets', 'ED', 'Jumlah', '', '', '', '', '', 'Tempat Sampling', 'Kab. / Kota', 'Pabrik/Importir', "Cluster/\n  Jenis Uji", 'Kondisi sampel');
        $rows[] = array('', '', '', '', '', '', '', '', '', '', '', '', '', '', 'K', 'Mi', 'R', 'P', 'A', 'T', '', '', '', '', '');
        $rows[] = array('', '1', '26.090.201.12.01.0371', 'KREEN Galactomyces 70 Day Cream', 'NA18190105263', 'Kosmetik beresiko tinggi', "01.09 Krim siang", 'Offline', date('d F Y'), 'Galactomyces', 'Pot 20 g', 'Krim', '1125008 A', date('m Y'), '1.0', '2.0', '0.0', '1.0', '1.0', '5', "Ririnis Shop", 'Kota Bandar Lampung', "PT. MULIA KAIROS", 'C/Compliance', 'Baik');
    } elseif ($kom === 'suplemen') {
        $rows[] = array('', 'DATA CONTOH SUPLEMEN KESEHATAN BULAN ' . strtoupper(date('F Y')), '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');
        $rows[] = array('', '', 'NO', '', 'NAMA BARANG', 'BENTUK SEDIAAN', 'Jenis Sampel', 'KOMPOSISI ZAT BERKHASIAT', 'NAMA INDUSTRI', 'NIE', '', 'NO. BATCH', 'ED', 'KEMASAN', 'PENGAMBILAN', '', 'Jlh', '', '', '', '', '', 'KET');
        $rows[] = array('', '', '', '', '', '', '', '', '', '', '', '', '', '', 'TEMPAT', 'TANGGAL', 'K', 'M', 'R', 'P', 'A', 'J', '');
        $rows[] = array('', 'NO.', '1.0', '', '2.0', '3.0', '4.0', '5.0', '6.0', '7.0', '', '8.0', '9.0', '10.0', '11.0', '12.0', '13.0', '', '', '', '', '', '14.0');
        $rows[] = array('', '1', '27', '/SK/T-Ki/POM/LT/VI/26', 'NOURISH SKIN', 'Tablet Salut  selaput', 'Import Online', "Calcium", "PERFORMANCE RESEARCH", 'POM SI', '034508261', '7520870', 'ED.02 2027', 'Dus 30 tablet', 'Apotek Rafael', date('d F Y'), '1', '0', '0', '0', '1', '2', '');
    } else {
        // Obat
        $rows[] = array('No', 'Kategori Sampel', 'Sub Kategori', 'Jenis Kelas Terapi', 'Kategori Sarana', 'Nama Sarana', 'Kabupaten Kota', 'Tanggal Sampling', 'Kode Sampel Manual', 'No SIPT', 'Nama Sampel', 'Nomor Izin Edar', 'Kondisi Produk', 'No Bets', 'Kedaluwarsa', 'Kemasan', 'Nama Alamat Perusahaan', 'Komposisi', 'Jumlah Kimia', 'Jumlah Mikro', 'Jumlah Arsip', 'Jumlah Penandaan', 'Jumlah Total', 'Penyimpanan', 'Penandaan', 'Harga', 'TIE', 'MK', 'TMK', 'Surtug', 'Balai Penguji');
        $rows[] = array('1', 'Targeted', 'Obat Keras', 'Analgesik-Antipiretik', 'Apotek', 'Apotek Sehat', 'Bandar Lampung', date('Y-m-d'), 'K-OBT-001', 'SIPT-01', 'Parasetamol 500 mg', 'DKL9812345610A1', 'Baik', 'B9801A', date('Y-m-d'), 'Dus', 'PT Farmasi', 'Paracetamol', '20', '0', '10', '1', '31', 'Suhu Ruang', 'MK', '15000', 'TIE', 'MK', 'TMK', 'ST/01', 'BBPOM');
    }

    $res = detect_commodity_format($rows, 'Template ' . ucfirst($kom));
    echo "Template $kom: DETECTED => {$res['detected']} (Score: {$res['score']})\n";
}

