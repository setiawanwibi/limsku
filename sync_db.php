<?php
/**
 * Script sinkronisasi database otomatis via browser.
 * Cukup buka http://localhost/LIMSKU/sync_db.php setelah git pull.
 */

define('BASEPATH', __DIR__ . '/system/');
$active_group = 'default';
$query_builder = TRUE;
$db = array();

$dbConfigPath = __DIR__ . '/application/config/database.php';
if (!file_exists($dbConfigPath)) {
    http_response_code(500);
    die('File konfigurasi database tidak ditemukan.');
}
require $dbConfigPath;

$cfg = $db['default'] ?? null;
if (!$cfg) {
    http_response_code(500);
    die('Konfigurasi database default tidak ditemukan.');
}

$sqlFiles = glob(__DIR__ . '/database/sync_*.sql');
sort($sqlFiles);
if (empty($sqlFiles)) {
    http_response_code(404);
    die('Tidak ada file database/sync_*.sql ditemukan.');
}

$mysqli = @new mysqli(
    $cfg['hostname'] ?: 'localhost',
    $cfg['username'] ?: 'root',
    $cfg['password'] ?? '',
    $cfg['database'] ?: 'limsku'
);

if ($mysqli->connect_errno) {
    http_response_code(500);
    die('Gagal konek ke database: ' . htmlspecialchars($mysqli->connect_error));
}

$mysqli->set_charset('utf8mb4');

$errors = array();
$executed = 0;
$fileNames = array();

foreach ($sqlFiles as $sqlFile) {
    $fileNames[] = 'database/' . basename($sqlFile);
    $sql = file_get_contents($sqlFile);

    if ($mysqli->multi_query($sql)) {
        do {
            $executed++;
            if ($result = $mysqli->store_result()) {
                $result->free();
            }
            if ($mysqli->errno) {
                $errors[] = basename($sqlFile) . " (Query #{$executed}): " . $mysqli->error;
            }
        } while ($mysqli->more_results() && $mysqli->next_result());
    } else {
        $errors[] = basename($sqlFile) . ': ' . $mysqli->error;
    }

    if ($mysqli->errno) {
        $errors[] = basename($sqlFile) . ': ' . $mysqli->error;
    }
}

$mysqli->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Sync Database LIMSKU</title>
    <style>
        body { font-family: system-ui, sans-serif; max-width: 680px; margin: 48px auto; padding: 0 16px; color: #1f2937; }
        .card { border: 1px solid #e5e7eb; border-radius: 10px; padding: 24px; background: #fff; box-shadow: 0 2px 8px rgba(0,0,0,.05); }
        .ok { color: #065f46; background: #ecfdf5; border: 1px solid #a7f3d0; padding: 12px 16px; border-radius: 8px; }
        .err { color: #991b1b; background: #fef2f2; border: 1px solid #fecaca; padding: 12px 16px; border-radius: 8px; }
        code { background: #f3f4f6; padding: 2px 6px; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Sinkronisasi Database LIMSKU</h2>
        <p>File: <code><?= htmlspecialchars(implode(', ', $fileNames)) ?></code> | Database: <code><?= htmlspecialchars($cfg['database']) ?></code></p>
        <?php if (empty($errors)): ?>
            <div class="ok">
                <strong>Berhasil!</strong> <?= $executed ?> statement SQL telah dijalankan. Database lokal kamu sudah sinkron.
            </div>
        <?php else: ?>
            <div class="err">
                <strong>Terjadi error:</strong>
                <ul>
                    <?php foreach ($errors as $e): ?>
                        <li><?= htmlspecialchars($e) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
