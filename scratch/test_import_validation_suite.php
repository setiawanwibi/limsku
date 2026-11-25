<?php
define('ENVIRONMENT', 'development');
define('BASEPATH', 'dummy');

require_once __DIR__ . '/../application/libraries/SimpleXLSX.php';
require_once __DIR__ . '/../application/libraries/Excel_Importer.php';

$files = [
    'Kosmetik' => 'C:\\Users\\ASUS\\OneDrive\\Dokumen\\Magang BPOM\\Data BPOM\\Format Data Sampel\\Sampel Kosmetik\\10. Data Contoh Kosmetik Oktober 2026 - 2.xlsx',
    'Obat'     => 'C:\\Users\\ASUS\\OneDrive\\Dokumen\\Magang BPOM\\Data BPOM\\Format Data Sampel\\Sampel Obat\\Data Contoh Jambi Juni 2026.xlsx',
    'OTSK'     => 'C:\\Users\\ASUS\\OneDrive\\Dokumen\\Magang BPOM\\Data BPOM\\Format Data Sampel\\Sampel Obat Tradisional dan Suplemen\\6. Data contoh OTSK JUNI 2026 NEW.xlsx',
    'Pangan'   => 'C:\\Users\\ASUS\\OneDrive\\Dokumen\\Magang BPOM\\Data BPOM\\Format Data Sampel\\Sampel Pangan\\4. Data Contoh April 2026_print.xlsx'
];

function simulate_import_validation($file_path, $komoditi_import) {
    $xlsx = SimpleXLSX::parse($file_path);
    if (!$xlsx) {
        return ['status' => false, 'pesan' => 'Parse error: ' . SimpleXLSX::parseError()];
    }

    $sheet_names = $xlsx->sheetNames();
    $matched_sheet_idx = null;
    $detected_summaries = array();

    foreach ($sheet_names as $sIdx => $sName) {
        $sRows = $xlsx->rows($sIdx);
        if (empty($sRows) || count($sRows) < 2) {
            continue;
        }
        $det = Excel_Importer::detect_commodity_format($sRows, $sName);
        $sheet_label = "Sheet " . ($sIdx + 1) . " ('" . htmlspecialchars($sName) . "'): " . Excel_Importer::get_commodity_label($det['detected']);
        $detected_summaries[] = $sheet_label;

        if ($det['detected'] === $komoditi_import) {
            $matched_sheet_idx = $sIdx;
            $rows = $sRows;
            break;
        }
    }

    if ($matched_sheet_idx === null) {
        $target_label = Excel_Importer::get_commodity_label($komoditi_import);
        $summary_str  = !empty($detected_summaries) ? implode(', ', $detected_summaries) : 'Format tidak dikenali';
        return [
            'status' => false,
            'pesan'  => "Format file Excel tidak sesuai dengan komoditi <strong>{$target_label}</strong>! Berkas yang diunggah terdeteksi berisi: {$summary_str}. Pastikan Anda mengunggah template resmi sampling <strong>{$target_label}</strong>."
        ];
    }

    return [
        'status'    => true,
        'sheet_idx' => $matched_sheet_idx,
        'sheet_name'=> $sheet_names[$matched_sheet_idx],
        'total_rows'=> count($rows)
    ];
}

$tests = [
    // [FileKey, TargetKomoditi, ExpectedStatus, Description]
    ['OTSK', 'kosmetik', false, 'Import OTSK ke Kosmetik (Kasus User - Harus Ditolak)'],
    ['OTSK', 'obat', false, 'Import OTSK ke Obat (Harus Ditolak)'],
    ['OTSK', 'pangan', false, 'Import OTSK ke Pangan (Harus Ditolak)'],
    ['OTSK', 'herbal', true, 'Import OTSK ke Herbal (Harus Diterima Sheet 0 OBA)'],
    ['OTSK', 'suplemen', true, 'Import OTSK ke Suplemen (Harus Diterima Sheet 1 SK)'],

    ['Kosmetik', 'kosmetik', true, 'Import Kosmetik ke Kosmetik (Harus Diterima)'],
    ['Kosmetik', 'obat', false, 'Import Kosmetik ke Obat (Harus Ditolak)'],
    ['Kosmetik', 'herbal', false, 'Import Kosmetik ke Herbal (Harus Ditolak)'],

    ['Pangan', 'pangan', true, 'Import Pangan ke Pangan (Harus Diterima)'],
    ['Pangan', 'kosmetik', false, 'Import Pangan ke Kosmetik (Harus Ditolak)'],

    ['Obat', 'obat', true, 'Import Obat ke Obat (Harus Diterima)'],
    ['Obat', 'kosmetik', false, 'Import Obat ke Kosmetik (Harus Ditolak)'],
];

echo "========================================================================\n";
echo "TEST SUITE: VALIDASI KOMODITI SAAT IMPORT EXCEL\n";
echo "========================================================================\n";

$pass_count = 0;
$fail_count = 0;

foreach ($tests as $idx => $t) {
    list($fKey, $targetKom, $expected, $desc) = $t;
    $filePath = $files[$fKey];
    $res = simulate_import_validation($filePath, $targetKom);

    $is_pass = ($res['status'] === $expected);
    if ($is_pass) {
        $pass_count++;
        echo "[PASS] Test #" . ($idx + 1) . ": $desc\n";
        if ($res['status']) {
            echo "       -> Matched Sheet: {$res['sheet_idx']} ('{$res['sheet_name']}'), Rows: {$res['total_rows']}\n";
        } else {
            echo "       -> Rejected with message: " . strip_tags($res['pesan']) . "\n";
        }
    } else {
        $fail_count++;
        echo "[FAIL] Test #" . ($idx + 1) . ": $desc\n";
        echo "       -> Result: " . json_encode($res) . "\n";
    }
}

echo "========================================================================\n";
echo "SUMMARY: Total Tests: " . count($tests) . " | PASSED: $pass_count | FAILED: $fail_count\n";
echo "========================================================================\n";
