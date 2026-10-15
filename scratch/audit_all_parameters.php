<?php
define('BASEPATH', 'TRUE');
define('ENVIRONMENT', 'development');
require_once 'application/config/database.php';

$db_config = $db['default'];
$mysqli = new mysqli($db_config['hostname'], $db_config['username'], $db_config['password'], $db_config['database']);
if ($mysqli->connect_error) {
    die("Connect error: " . $mysqli->connect_error);
}

$cols = $mysqli->query("SHOW COLUMNS FROM methods");
echo "=== COLUMNS IN 'methods' ===\n";
while ($c = $cols->fetch_assoc()) {
    echo "  {$c['Field']} ({$c['Type']})\n";
}

$p_all = $mysqli->query("
    SELECT 
        bidang_pengujian, 
        parameter_uji, 
        COUNT(*) as cnt,
        GROUP_CONCAT(DISTINCT metode_analisa SEPARATOR ' | ') as analisa_samples
    FROM methods
    WHERE status = 'Aktif'
    GROUP BY bidang_pengujian, parameter_uji
    ORDER BY bidang_pengujian, cnt DESC
");

echo "\n=== ALL 53 DISTINCT PARAMETER UJI IN METHODS ===\n";
$no = 1;
while ($row = $p_all->fetch_assoc()) {
    echo sprintf("%2d. [%-12s] (Count: %3d) '%s'\n    Metode Analisa: %s\n", 
        $no++, 
        $row['bidang_pengujian'], 
        $row['cnt'], 
        $row['parameter_uji'], 
        substr($row['analisa_samples'] ?: '-', 0, 80)
    );
}

$mysqli->close();
