<?php
/**
 * LIMSKU Template Synchronizer
 * 
 * Script ini membaca semua file JSON di folder `database/templates/`
 * dan otomatis memasukkan / memperbarui (UPSERT) tabel `form_templates`.
 * 
 * Cara Menjalankan:
 * 1. Lewat Terminal CLI:
 *    php sync_templates.php
 * 2. Lewat Browser:
 *    http://localhost/LIMSKU/sync_templates.php
 */

define('BASEPATH', 'TRUE');
define('ENVIRONMENT', 'development');

require_once __DIR__ . '/application/config/database.php';
$db_cfg = $db['default'];

$is_cli = (php_sapi_name() === 'cli');

if (!$is_cli) {
    echo "<pre style='font-family: monospace; font-size: 14px; background: #1e1e1e; color: #d4d4d4; padding: 20px; border-radius: 8px;'>";
    echo "<h3>🚀 LIMSKU Template Synchronizer</h3>\n";
} else {
    echo "\n=== LIMSKU Template Synchronizer ===\n\n";
}

$mysqli = @new mysqli($db_cfg['hostname'], $db_cfg['username'], $db_cfg['password'], $db_cfg['database']);
if ($mysqli->connect_error) {
    die("❌ Gagal koneksi database: " . $mysqli->connect_error . "\n");
}
$mysqli->set_charset("utf8mb4");

$templates_dir = __DIR__ . '/database/templates';
if (!is_dir($templates_dir)) {
    die("❌ Folder templates tidak ditemukan di: $templates_dir\n");
}

$files = glob($templates_dir . '/*.json');
if (empty($files)) {
    echo "ℹ️ Tidak ada file template JSON di $templates_dir\n";
    exit;
}

$count_success = 0;
$count_error = 0;

foreach ($files as $file) {
    $filename = basename($file);
    $raw_content = file_get_contents($file);
    $data = json_decode($raw_content, true);

    if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
        echo "⚠️  [SKIP] File $filename bukan JSON yang valid (" . json_last_error_msg() . ")\n";
        $count_error++;
        continue;
    }

    $kode_template = isset($data['kode_template']) ? trim($data['kode_template']) : '';
    $nama_template = isset($data['nama_template']) ? trim($data['nama_template']) : '';
    $kategori = isset($data['kategori']) ? trim($data['kategori']) : '';
    
    // skema_form bisa berupa object/array atau langsung string JSON
    if (isset($data['skema_form'])) {
        $skema_form = is_array($data['skema_form']) ? json_encode($data['skema_form'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) : $data['skema_form'];
    } else {
        $skema_form = json_encode([], JSON_UNESCAPED_UNICODE);
    }

    if (empty($kode_template) || empty($nama_template)) {
        echo "⚠️  [SKIP] File $filename wajib memiliki 'kode_template' dan 'nama_template'\n";
        $count_error++;
        continue;
    }

    // Cek apakah template sudah ada di DB
    $check_stmt = $mysqli->prepare("SELECT id FROM form_templates WHERE kode_template = ? LIMIT 1");
    $check_stmt->bind_param("s", $kode_template);
    $check_stmt->execute();
    $res = $check_stmt->get_result();

    if ($row = $res->fetch_assoc()) {
        $tpl_id = $row['id'];
        $up = $mysqli->prepare("UPDATE form_templates SET nama_template = ?, kategori = ?, skema_form = ?, status = 'Aktif', updated_at = NOW() WHERE id = ?");
        $up->bind_param("sssi", $nama_template, $kategori, $skema_form, $tpl_id);
        if ($up->execute()) {
            echo "✅ [UPDATED] $kode_template - $nama_template (ID: $tpl_id)\n";
            $count_success++;
        } else {
            echo "❌ [ERROR] Gagal update $kode_template: " . $up->error . "\n";
            $count_error++;
        }
        $up->close();
    } else {
        $ins = $mysqli->prepare("INSERT INTO form_templates (kode_template, nama_template, kategori, skema_form, status, created_at, updated_at) VALUES (?, ?, ?, ?, 'Aktif', NOW(), NOW())");
        $ins->bind_param("ssss", $kode_template, $nama_template, $kategori, $skema_form);
        if ($ins->execute()) {
            $new_id = $mysqli->insert_id;
            echo "✨ [CREATED] $kode_template - $nama_template (ID: $new_id)\n";
            $count_success++;
        } else {
            echo "❌ [ERROR] Gagal insert $kode_template: " . $ins->error . "\n";
            $count_error++;
        }
        $ins->close();
    }
    $check_stmt->close();
}

$mysqli->close();

echo "\n---------------------------------------------\n";
echo "🎉 Selesai sinkronisasi! Sukses: $count_success | Gagal/Skip: $count_error\n";
echo "Database kamu sekarang sudah sinkron 100% dengan file template Git.\n";

if (!$is_cli) {
    echo "</pre>";
}
