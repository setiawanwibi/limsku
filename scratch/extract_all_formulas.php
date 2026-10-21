<?php
/**
 * Script untuk mengekstrak SEMUA formula sel (<f>) dari ke-12 file Excel LCP Kimia.
 */

$excelDir = 'C:/Users/ASUS/OneDrive/Dokumen/Magang BPOM/Data BPOM/Format Parameter Pengujian/Kimia';

$files = [
    'KIM-01' => 'POM-06.02_CFM.01_SOP.01_F.01A.01_LCP_REAKSI WARNA _ PENGENDAPAN.xlsx',
    'KIM-02' => 'POM-06.02_CFM.01_SOP.01_F.01A.02_LCP_KERAGAMAN BOBOT.xlsx',
    'KIM-03' => 'POM-06.02_CFM.01_SOP.01_F.01A.03_LCP_KESERAGAMAN KANDUNGAN.xlsx',
    'KIM-04' => 'POM-06.02_CFM.01_SOP.01_F.01A.04_LCP_WAKTU HANCUR.xlsx',
    'KIM-05' => 'POM-06.02_CFM.01_SOP.01_F.01A.06_LCP_SPEKTROFOTOMETRI.xlsx',
    'KIM-06' => 'POM-06.02_CFM.01_SOP.01_F.01A.08_LCP_DISOLUSI.xlsx',
    'KIM-07' => 'POM-06.02_CFM.01_SOP.01_F.01A.10_LCP_KROMATOGRAFI LAPIS TIPIS.xlsx',
    'KIM-08' => 'POM-06.02_CFM.01_SOP.01_F.01A.12_LCP_KROMATOGRAFI CAIR KINERJA TINGGI.xlsx',
    'KIM-09' => 'POM-06.02_CFM.01_SOP.01_F.01A.17_LCP_KROMATOGRAFI GAS SPEKTROMETRI MASSA.xlsx',
    'KIM-10' => 'POM-06.02_CFM.01_SOP.01_F.01A.25_LCP_PEMERIAN.xlsx',
    'KIM-11' => 'POM-06.02_CFM.01_SOP.01_F.01A.27_LCP_pH.xlsx',
    'KIM-12' => 'POM-06.02_CFM.01_SOP.01_F.01A.28_LCP_BOBOT JENIS.xlsx',
];

echo "=== EKSTRAKSI LENGKAP SEMUA FORMULA SEL EXCEL KIMIA ===\n\n";

foreach ($files as $kode => $fn) {
    $fullPath = $excelDir . '/' . $fn;
    if (!file_exists($fullPath)) {
        echo "[$kode] File tidak ditemukan: $fn\n";
        continue;
    }

    $zip = new ZipArchive();
    if ($zip->open($fullPath) === TRUE) {
        $workbookXml = simplexml_load_string($zip->getFromName('xl/workbook.xml'));
        $sheetMaps = [];
        $idx = 1;
        foreach ($workbookXml->sheets->sheet as $sh) {
            $sheetMaps[$idx] = (string)$sh['name'];
            $idx++;
        }

        // Tentukan sheet target
        $targetSheetIdx = 1;
        if ($kode === 'KIM-03') {
            // Untuk KIM-03, cari sheet Revisi_KK
            foreach ($sheetMaps as $sIdx => $sName) {
                if (stripos($sName, 'Revisi_KK') !== false) {
                    $targetSheetIdx = $sIdx;
                    break;
                }
            }
        }

        $sheetFile = "xl/worksheets/sheet{$targetSheetIdx}.xml";
        echo "========================================================================================\n";
        echo "[$kode] File: $fn\n";
        echo "Sheet Target: {$sheetMaps[$targetSheetIdx]} ($sheetFile)\n";
        echo "----------------------------------------------------------------------------------------\n";

        if ($zip->locateName($sheetFile) !== false) {
            $xml = simplexml_load_string($zip->getFromName($sheetFile));
            $formulaList = [];

            foreach ($xml->sheetData->row as $r) {
                $rn = (int)$r['r'];
                // Jika KIM-06, kita batasi sampai baris 80 (format master F.01A.08)
                if ($kode === 'KIM-06' && $rn > 80) continue;

                foreach ($r->c as $c) {
                    if (isset($c->f)) {
                        $cellRef = (string)$c['r'];
                        $fText = (string)$c->f;
                        $formulaList[$cellRef] = $fText;
                    }
                }
            }

            if (empty($formulaList)) {
                echo "  (Tidak ada formula sel matematis - formulir kualitatif/observasi murni)\n";
            } else {
                echo "  Total formula ditemukan: " . count($formulaList) . "\n";
                foreach ($formulaList as $cell => $formula) {
                    echo "  - Sel {$cell} : ={$formula}\n";
                }
            }
        }
        $zip->close();
    }
    echo "\n";
}
