<?php
define('BASEPATH', 'TRUE');
define('ENVIRONMENT', 'development');
require_once 'application/config/database.php';

$db_config = $db['default'];
$mysqli = new mysqli($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);
if ($mysqli->connect_error) {
    die("Connect error: " . $mysqli->connect_error);
}

// 1. Ambil semua form_templates (LCP)
$tpl_res = $mysqli->query("SELECT id, kode_template, nama_template, kategori FROM form_templates WHERE status='Aktif'");
$templates = [];
while ($t = $tpl_res->fetch_assoc()) {
    $templates[$t['id']] = $t;
}

// Helper Normalizer
function normalisasi_parameter($param_raw) {
    $clean = trim(preg_replace('/\s+/', ' ', (string)$param_raw));
    $lower = strtolower($clean);

    // Aturan Kanonisasi Ilmiah & Farmakope
    if (preg_match('/^pemerian/i', $lower)) {
        return 'Pemerian';
    }
    if (preg_match('/^identifikasi/i', $lower)) {
        return 'Identifikasi';
    }
    if (preg_match('/^(penetapan kadar|kadar)\b/i', $lower)) {
        return 'Penetapan Kadar';
    }
    if (preg_match('/^(disolusi|pelepasan obat)\b/i', $lower)) {
        return 'Disolusi';
    }
    if (preg_match('/^(keseragaman sediaan|keseragaman kandungan|keragaman bobot)\b/i', $lower)) {
        return 'Keseragaman Sediaan';
    }
    if (preg_match('/^ph\b/i', $lower)) {
        return 'pH';
    }
    if (preg_match('/^waktu hancur/i', $lower)) {
        return 'Waktu Hancur';
    }
    if (preg_match('/^(batas mikroba|penghitungan mikroba)/i', $lower)) {
        return 'Batas Mikroba';
    }
    if (preg_match('/^sterilitas/i', $lower)) {
        return 'Sterilitas';
    }
    if (preg_match('/^endotoksin/i', $lower)) {
        return 'Endotoksin Bakteri';
    }
    if (preg_match('/^(penetapan potensi|potensi)/i', $lower)) {
        return 'Potensi Antibiotik';
    }
    if (preg_match('/^uji mikroba spesifik/i', $lower)) {
        return 'Mikroba Spesifik';
    }

    // Default jika tidak ada variasi sinonim: gunakan teks asli yang di-clean whitespace
    return ucwords($clean);
}

// Helper Auto-Mapping ke LCP
function cari_kandidat_lcp($kanonis, $bidang, $metode_analisa, $templates) {
    // Kandidat LCP: template_id, status: 'OTOMATIS' | 'PERLU_KONFIRMASI', alasan
    if ($bidang === 'Kimia') {
        if ($kanonis === 'Penetapan Kadar') {
            return ['template_id' => 1, 'status' => 'OTOMATIS', 'alasan' => 'Sesuai Form 1: Penetapan Kadar Obat (Kimia)'];
        }
        if ($kanonis === 'Disolusi') {
            return ['template_id' => 2, 'status' => 'OTOMATIS', 'alasan' => 'Sesuai Form 2: Uji Disolusi (Kimia)'];
        }
        if ($kanonis === 'Keseragaman Sediaan') {
            return ['template_id' => 3, 'status' => 'OTOMATIS', 'alasan' => 'Sesuai Form 3: Uji Keseragaman Bobot / Ukuran (Kimia)'];
        }
        if ($kanonis === 'pH') {
            return ['template_id' => 4, 'status' => 'OTOMATIS', 'alasan' => 'Sesuai Form 4: Uji pH / Derajat Keasaman (Kimia)'];
        }
        if ($kanonis === 'Identifikasi') {
            return ['template_id' => 7, 'status' => 'OTOMATIS', 'alasan' => 'Sesuai Form 7: Uji Identifikasi Kualitatif (Kimia)'];
        }
        if ($kanonis === 'Waktu Hancur') {
            return ['template_id' => 9, 'status' => 'OTOMATIS', 'alasan' => 'Sesuai Form 9: Uji Waktu Hancur Tablet (Kimia)'];
        }
        if ($kanonis === 'Pemerian') {
            // Form 5 adalah Kejernihan & Warna Larutan; Pemerian organoleptis sering dicatat umum atau form 5 jika larutan
            return ['template_id' => 5, 'status' => 'PERLU_KONFIRMASI', 'alasan' => 'Pemerian fisik/organoleptis dapat menggunakan Form 5 atau Form Khusus Fisik'];
        }
    } elseif ($bidang === 'Mikrobiologi') {
        if ($kanonis === 'Sterilitas') {
            return ['template_id' => 13, 'status' => 'OTOMATIS', 'alasan' => 'Sesuai Form 13: Uji Sterilitas (Mikrobiologi)'];
        }
        if ($kanonis === 'Batas Mikroba') {
            return ['template_id' => 14, 'status' => 'OTOMATIS', 'alasan' => 'Sesuai Form 14: Uji Batas Mikroba / ALT & AKK (Mikrobiologi)'];
        }
        if ($kanonis === 'Potensi Antibiotik') {
            return ['template_id' => 15, 'status' => 'OTOMATIS', 'alasan' => 'Sesuai Form 15: Uji Potensi Antibiotik (Mikrobiologi)'];
        }
        if ($kanonis === 'Endotoksin Bakteri') {
            return ['template_id' => 17, 'status' => 'OTOMATIS', 'alasan' => 'Sesuai Form 17: Uji Endotoksin Bakteri / LAL Test (Mikrobiologi)'];
        }
        if ($kanonis === 'Mikroba Spesifik') {
            return ['template_id' => 14, 'status' => 'PERLU_KONFIRMASI', 'alasan' => 'Uji mikroba spesifik dapat dirujuk ke Form 14 Batas Mikroba atau Form Khusus'];
        }
    }

    return ['template_id' => null, 'status' => 'PERLU_KONFIRMASI', 'alasan' => 'Belum ada katalog LCP spesifik yang cocok dengan parameter ini'];
}

// 2. Evaluasi seluruh data methods
$methods_res = $mysqli->query("SELECT id, kode_metode, parameter_uji, bidang_pengujian, metode_analisa, template_id FROM methods WHERE status='Aktif'");

$stats = [
    'total_monografi' => 0,
    'terpetakan_saat_ini' => 0,
    'kandidat_otomatis_valid' => 0,
    'perlu_konfirmasi' => 0,
    'kelompok_kanonis' => []
];

while ($m = $methods_res->fetch_assoc()) {
    $stats['total_monografi']++;
    if (!empty($m['template_id'])) {
        $stats['terpetakan_saat_ini']++;
    }

    $kanonis = normalisasi_parameter($m['parameter_uji']);
    $mapping = cari_kandidat_lcp($kanonis, $m['bidang_pengujian'], $m['metode_analisa'], $templates);

    $group_key = $m['bidang_pengujian'] . ' | ' . $kanonis;
    if (!isset($stats['kelompok_kanonis'][$group_key])) {
        $stats['kelompok_kanonis'][$group_key] = [
            'bidang' => $m['bidang_pengujian'],
            'kanonis' => $kanonis,
            'variasi_asli' => [],
            'metode_analisa' => [],
            'total_monografi' => 0,
            'kandidat_template_id' => $mapping['template_id'],
            'kandidat_kode' => $mapping['template_id'] ? $templates[$mapping['template_id']]['kode_template'] : '-',
            'kandidat_nama' => $mapping['template_id'] ? $templates[$mapping['template_id']]['nama_template'] : '-',
            'status' => $mapping['status'],
            'alasan' => $mapping['alasan']
        ];
    }

    $stats['kelompok_kanonis'][$group_key]['total_monografi']++;
    if (!in_array($m['parameter_uji'], $stats['kelompok_kanonis'][$group_key]['variasi_asli'])) {
        $stats['kelompok_kanonis'][$group_key]['variasi_asli'][] = $m['parameter_uji'];
    }
    if (!empty($m['metode_analisa']) && !in_array($m['metode_analisa'], $stats['kelompok_kanonis'][$group_key]['metode_analisa'])) {
        $stats['kelompok_kanonis'][$group_key]['metode_analisa'][] = $m['metode_analisa'];
    }

    if ($mapping['status'] === 'OTOMATIS') {
        $stats['kandidat_otomatis_valid']++;
    } else {
        $stats['perlu_konfirmasi']++;
    }
}

echo "=== STATISTIK HASIL ANALISIS AUTO-MAPPING ===\n";
echo "Total Monografi Aktif    : {$stats['total_monografi']}\n";
echo "Sudah Terpetakan Saat Ini: {$stats['terpetakan_saat_ini']}\n";
echo "Kandidat Otomatis Valid  : {$stats['kandidat_otomatis_valid']} monografi\n";
echo "Perlu Konfirmasi         : {$stats['perlu_konfirmasi']} monografi\n";
echo "Jumlah Kelompok Kanonis  : " . count($stats['kelompok_kanonis']) . "\n\n";

echo "=== RINCIAN PER KELOMPOK PARAMETER KANONIS ===\n";
foreach ($stats['kelompok_kanonis'] as $gk => $g) {
    echo sprintf("[%-12s] Kanonis: %-22s | Target: %-8s | %-16s | Monografi: %3d\n", 
        $g['bidang'], 
        $g['kanonis'], 
        $g['kandidat_kode'], 
        $g['status'], 
        $g['total_monografi']
    );
    echo "   Variasi Teks Asli (" . count($g['variasi_asli']) . "): " . implode('; ', array_slice($g['variasi_asli'], 0, 4)) . "\n";
    echo "   Alasan: {$g['alasan']}\n\n";
}

$mysqli->close();
