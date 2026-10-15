<?php
/**
 * Database Backup Script before Reagents Schema Migration
 */
define('BASEPATH', 'defined');
define('ENVIRONMENT', 'development');

require_once __DIR__ . '/../application/config/database.php';
$db_config = $db['default'];

$backup_dir = 'C:/xampp/backups/LIMSKU';
if (!is_dir($backup_dir)) {
    mkdir($backup_dir, 0777, true);
}

$date = date('Ymd_His');
$backup_file = $backup_dir . "/limsku_backup_pre_reagents_{$date}.sql";

$cmd = "C:\\xampp\\mysql\\bin\\mysqldump.exe --host={$db_config['hostname']} --user={$db_config['username']} --password=\"{$db_config['password']}\" {$db_config['database']} > \"{$backup_file}\"";
exec($cmd, $output, $ret);

if ($ret === 0 && file_exists($backup_file) && filesize($backup_file) > 1000) {
    echo "BACKUP SUCCESS: {$backup_file} (" . filesize($backup_file) . " bytes)\n";
} else {
    echo "BACKUP FAILED: Return code {$ret}\n";
}
