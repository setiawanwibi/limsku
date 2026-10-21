<?php
/**
 * Comprehensive Audit Script for 12 LCP Kimia
 */

$excelDir = 'C:/Users/ASUS/OneDrive/Dokumen/Magang BPOM/Data BPOM/Format Parameter Pengujian/Kimia';
$baseDir = dirname(__DIR__);

$masterList = [
    'KIM-01' => [
        'file' => 'POM-06.02_CFM.01_SOP.01_F.01A.01_LCP_REAKSI WARNA _ PENGENDAPAN.xlsx',
        'sop' => 'POM-06.02/CFM.01/SOP.01/F.01A.01',
        'view' => 'application/views/pengujian/cetak_lcp_reaksi_warna.php',
        'json' => 'database/templates/KIM-01.json',
        'name' => 'Reaksi Warna / Pengendapan'
    ],
    'KIM-02' => [
        'file' => 'POM-06.02_CFM.01_SOP.01_F.01A.02_LCP_KERAGAMAN BOBOT.xlsx',
        'sop' => 'POM-06.02/CFM.01/SOP.01/F.01A.02',
        'view' => 'application/views/pengujian/cetak_lcp_keragaman_bobot.php',
        'json' => 'database/templates/KIM-02.json',
        'name' => 'Keragaman Bobot'
    ],
    'KIM-03' => [
        'file' => 'POM-06.02_CFM.01_SOP.01_F.01A.03_LCP_KESERAGAMAN KANDUNGAN.xlsx',
        'sop' => 'POM-06.02/CFM.01/SOP.01/F.01A.03',
        'view' => 'application/views/pengujian/cetak_lcp_keseragaman_kandungan.php',
        'json' => 'database/templates/KIM-03.json',
        'name' => 'Keseragaman Kandungan'
    ],
    'KIM-04' => [
        'file' => 'POM-06.02_CFM.01_SOP.01_F.01A.04_LCP_WAKTU HANCUR.xlsx',
        'sop' => 'POM-06.02/CFM.01/SOP.01/F.01A.04',
        'view' => 'application/views/pengujian/cetak_lcp_waktu_hancur.php',
        'json' => 'database/templates/KIM-04.json',
        'name' => 'Waktu Hancur'
    ],
    'KIM-05' => [
        'file' => 'POM-06.02_CFM.01_SOP.01_F.01A.06_LCP_SPEKTROFOTOMETRI.xlsx',
        'sop' => 'POM-06.02/CFM.01/SOP.01/F.01A.06',
        'view' => 'application/views/pengujian/cetak_lcp_spektrofotometri.php',
        'json' => 'database/templates/KIM-05.json',
        'name' => 'Spektrofotometri'
    ],
    'KIM-06' => [
        'file' => 'POM-06.02_CFM.01_SOP.01_F.01A.08_LCP_DISOLUSI.xlsx',
        'sop' => 'POM-06.02/CFM.01/SOP.01/F.01A.08',
        'view' => 'application/views/pengujian/cetak_lcp_disolusi.php',
        'json' => 'database/templates/KIM-06.json',
        'name' => 'Disolusi'
    ],
    'KIM-07' => [
        'file' => 'POM-06.02_CFM.01_SOP.01_F.01A.10_LCP_KROMATOGRAFI LAPIS TIPIS.xlsx',
        'sop' => 'POM-06.02/CFM.01/SOP.01/F.01A.10',
        'view' => 'application/views/pengujian/cetak_lcp_klt.php',
        'json' => 'database/templates/KIM-07.json',
        'name' => 'Kromatografi Lapis Tipis'
    ],
    'KIM-08' => [
        'file' => 'POM-06.02_CFM.01_SOP.01_F.01A.12_LCP_KROMATOGRAFI CAIR KINERJA TINGGI.xlsx',
        'sop' => 'POM-06.02/CFM.01/SOP.01/F.01A.12',
        'view' => 'application/views/pengujian/cetak_lcp_kckt.php',
        'json' => 'database/templates/KIM-08.json',
        'name' => 'Kromatografi Cair Kinerja Tinggi (HPLC/KCKT)'
    ],
    'KIM-09' => [
        'file' => 'POM-06.02_CFM.01_SOP.01_F.01A.17_LCP_KROMATOGRAFI GAS SPEKTROMETRI MASSA.xlsx',
        'sop' => 'POM-06.02/CFM.01/SOP.01/F.01A.17',
        'view' => 'application/views/pengujian/cetak_lcp_gcms.php',
        'json' => 'database/templates/KIM-09.json',
        'name' => 'Kromatografi Gas Spektrometri Massa (GC-MS)'
    ],
    'KIM-10' => [
        'file' => 'POM-06.02_CFM.01_SOP.01_F.01A.25_LCP_PEMERIAN.xlsx',
        'sop' => 'POM-06.02/CFM.01/SOP.01/F.01A.25',
        'view' => 'application/views/pengujian/cetak_lcp_pemerian.php',
        'json' => 'database/templates/KIM-10.json',
        'name' => 'Pemerian (Organoleptis)'
    ],
    'KIM-11' => [
        'file' => 'POM-06.02_CFM.01_SOP.01_F.01A.27_LCP_pH.xlsx',
        'sop' => 'POM-06.02/CFM.01/SOP.01/F.01A.27',
        'view' => 'application/views/pengujian/cetak_lcp_ph.php',
        'json' => 'database/templates/KIM-11.json',
        'name' => 'Penetapan pH'
    ],
    'KIM-12' => [
        'file' => 'POM-06.02_CFM.01_SOP.01_F.01A.28_LCP_BOBOT JENIS.xlsx',
        'sop' => 'POM-06.02/CFM.01/SOP.01/F.01A.28',
        'view' => 'application/views/pengujian/cetak_lcp_bobot_jenis.php',
        'json' => 'database/templates/KIM-12.json',
        'name' => 'Penetapan Bobot Jenis'
    ],
];

echo "========================================================================================\n";
echo "                      AUDIT 100% KELENGKAPAN 12 LCP KIMIA BPOM\n";
echo "========================================================================================\n\n";

$pengujianController = file_get_contents($baseDir . '/application/controllers/Pengujian.php');
$prosesSesiView = file_get_contents($baseDir . '/application/views/pengujian/proses_sesi.php');

$totalOk = 0;

foreach ($masterList as $code => $info) {
    echo "[$code] {$info['name']}\n";
    $excelPath = $excelDir . '/' . $info['file'];
    
    // 1. Audit Excel
    $excelOk = file_exists($excelPath);
    echo "  1. Berkas Excel: " . ($excelOk ? "✅ Ditemukan" : "❌ Tidak Ditemukan") . " ({$info['file']})\n";
    if ($excelOk) {
        $zip = new ZipArchive();
        if ($zip->open($excelPath) === TRUE) {
            $workbookXml = simplexml_load_string($zip->getFromName('xl/workbook.xml'));
            $sheets = [];
            foreach ($workbookXml->sheets->sheet as $sh) {
                $sheets[] = (string)$sh['name'];
            }
            echo "     - Total Sheets: " . count($sheets) . " [" . implode(', ', $sheets) . "]\n";
            $zip->close();
        }
    }

    // 2. Audit JSON Template
    $jsonPath = $baseDir . '/' . $info['json'];
    $jsonExists = file_exists($jsonPath);
    $jsonValid = false;
    $sopMatch = false;
    if ($jsonExists) {
        $jsonContent = json_decode(file_get_contents($jsonPath), true);
        if ($jsonContent && isset($jsonContent['kode_template'])) {
            $jsonValid = true;
            $sopInJson = $jsonContent['skema_form']['kode_dokumen'] ?? '';
            $sopMatch = (strpos($sopInJson, basename($info['sop'])) !== false || $sopInJson === $info['sop']);
        }
    }
    echo "  2. JSON Template: " . ($jsonExists && $jsonValid ? "✅ Valid" : "❌ Tidak Valid") . " | SOP: " . ($sopMatch ? "✅ {$info['sop']}" : "⚠️ Beda ($sopInJson vs {$info['sop']})") . "\n";

    // 3. Audit View Cetak
    $viewPath = $baseDir . '/' . $info['view'];
    $viewExists = file_exists($viewPath);
    $viewHasSop = false;
    if ($viewExists) {
        $viewContent = file_get_contents($viewPath);
        $viewHasSop = (strpos($viewContent, $info['sop']) !== false);
    }
    echo "  3. View Cetak PDF: " . ($viewExists ? "✅ Ditemukan" : "❌ Tidak Ditemukan") . " | SOP Footer: " . ($viewHasSop ? "✅ Sesuai" : "⚠️ Cek SOP") . "\n";

    // 4. Audit Controller Routing
    $controllerRouted = (strpos($pengujianController, $code) !== false || strpos($pengujianController, basename($info['view'], '.php')) !== false);
    echo "  4. Routing Controller: " . ($controllerRouted ? "✅ Terdaftar di Pengujian.php" : "❌ Belum Terdaftar") . "\n";

    echo "  ------------------------------------------------------------------------------------\n";
}
