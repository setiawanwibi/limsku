<?php
/**
 * Test render check for all 12 LCP Kimia view files.
 */

define('BASEPATH', 'TRUE');
function html_escape($var) {
    if (is_array($var)) return array_map('html_escape', $var);
    return htmlspecialchars((string)$var, ENT_QUOTES, 'UTF-8');
}
function base_url($uri = '') { return 'http://localhost/LIMSKU/' . ltrim($uri, '/'); }
define('FCPATH', dirname(__DIR__) . '/');

$views = [
    'KIM-01' => 'application/views/pengujian/cetak_lcp_reaksi_warna.php',
    'KIM-02' => 'application/views/pengujian/cetak_lcp_keragaman_bobot.php',
    'KIM-03' => 'application/views/pengujian/cetak_lcp_keseragaman_kandungan.php',
    'KIM-04' => 'application/views/pengujian/cetak_lcp_waktu_hancur.php',
    'KIM-05' => 'application/views/pengujian/cetak_lcp_spektrofotometri.php',
    'KIM-06' => 'application/views/pengujian/cetak_lcp_disolusi.php',
    'KIM-07' => 'application/views/pengujian/cetak_lcp_klt.php',
    'KIM-08' => 'application/views/pengujian/cetak_lcp_kckt.php',
    'KIM-09' => 'application/views/pengujian/cetak_lcp_gcms.php',
    'KIM-10' => 'application/views/pengujian/cetak_lcp_pemerian.php',
    'KIM-11' => 'application/views/pengujian/cetak_lcp_ph.php',
    'KIM-12' => 'application/views/pengujian/cetak_lcp_bobot_jenis.php',
];

$dummyData = [
    'sampel' => [
        'id' => 101,
        'nama_sampel' => 'Paracetamol Tablet 500 mg',
        'kode_sampel' => 'SMP/KIM/2026/001',
        'kode_sampel_manual' => 'SMP/KIM/2026/001',
        'nomor_bets' => 'BET-2026-X1',
        'parameter_uji' => 'Pengujian Kimia Standar BPOM',
        'komposisi' => 'Paracetamol 500 mg'
    ],
    'sesi' => [
        'status' => 'Selesai Pengujian',
        'tanggal_pengujian' => date('Y-m-d'),
        'nama_penguji' => 'Analis Kimia BPOM',
        'nama_verifikator' => 'Penyelia Kimia BPOM',
        'verified_at' => date('Y-m-d H:i:s'),
        'waktu_selesai' => date('Y-m-d H:i:s'),
    ],
    'hasil' => [
        'data_hasil' => json_encode(['dummy' => 'val']),
        'updated_at' => date('Y-m-d H:i:s'),
    ],
    'penguji' => ['nama_lengkap' => 'Analis Kimia BPOM'],
    'verifier' => ['nama_lengkap' => 'Penyelia Kimia BPOM'],
    'approver' => ['nama_lengkap' => 'Manajer Teknis BPOM'],
    'metode' => ['pustaka_acuan' => 'FI VI', 'persyaratan' => '90.0% - 110.0%']
];

echo "=== VERIFIKASI UJI RENDER 12 VIEW CETAK LCP KIMIA ===\n\n";

foreach ($views as $code => $relPath) {
    $fullPath = FCPATH . $relPath;
    if (!file_exists($fullPath)) {
        echo "[$code] ❌ File view tidak ada: $relPath\n";
        continue;
    }

    extract($dummyData);
    ob_start();
    try {
        include $fullPath;
        $output = ob_get_clean();
        $len = strlen($output);
        $hasA4 = (strpos($output, 'size: A4 portrait') !== false || strpos($output, 'size: a4 portrait') !== false || strpos($output, '210mm') !== false);
        $hasPrint = (strpos($output, 'window.print()') !== false);
        $hasSop = (strpos($output, 'POM-06.02/CFM.01/SOP.01/F.01A.') !== false);

        echo "[$code] ✅ Render Berhasil! ($len bytes)\n";
        echo "        - Standar A4: " . ($hasA4 ? "✅ Ya" : "⚠️ Cek") . "\n";
        echo "        - Tombol Print/PDF: " . ($hasPrint ? "✅ Ya" : "⚠️ Cek") . "\n";
        echo "        - SOP Resmi BPOM: " . ($hasSop ? "✅ Ada di footer" : "⚠️ Cek") . "\n";
    } catch (Throwable $e) {
        ob_end_clean();
        echo "[$code] ❌ Error render: " . $e->getMessage() . "\n";
    }
}
