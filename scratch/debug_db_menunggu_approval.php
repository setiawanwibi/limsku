<?php
define('BASEPATH', 'TRUE');
define('ENVIRONMENT', 'development');
require_once __DIR__ . '/../application/config/database.php';

$db_cfg = $db['default'];
$mysqli = @new mysqli($db_cfg['hostname'], $db_cfg['username'], $db_cfg['password'], $db_cfg['database']);
if ($mysqli->connect_error) {
    echo "DB error: " . $mysqli->connect_error . PHP_EOL;
    exit(1);
}

if (!class_exists('CI_Model')) {
    class CI_Model {
        public $db;
        public function __construct() {}
    }
}

require_once __DIR__ . '/../application/models/Model_Pengujian.php';

// Mock DB wrapper for Model_Pengujian
class SimpleMockDB {
    private $m;
    public function __construct($mysqli) { $this->m = $mysqli; }
    public function get_where($table, $where) {
        $conds = [];
        foreach ($where as $k => $v) {
            $conds[] = "$k = '" . $this->m->real_escape_string($v) . "'";
        }
        $sql = "SELECT * FROM $table WHERE " . implode(' AND ', $conds);
        $res = $this->m->query($sql);
        $rows = [];
        while ($r = $res->fetch_assoc()) $rows[] = $r;
        return new class($rows) {
            private $data;
            public function __construct($d) { $this->data = $d; }
            public function row_array() { return !empty($this->data) ? $this->data[0] : null; }
            public function result_array() { return $this->data; }
        };
    }
}

$model = new Model_Pengujian();
$model->db = new SimpleMockDB($mysqli);

$res = $mysqli->query("SELECT s.id, s.kode_sampel_manual, s.nama_sampel, s.jumlah_kimia, s.jumlah_mikro, ts.id as sesi_id, ts.jenis_pengujian, ts.status as status_sesi FROM samples s JOIN testing_sessions ts ON ts.sample_id = s.id WHERE ts.status = 'Menunggu Approval'");

echo "=== Sesi Menunggu Approval di Database ===\n";
echo "=== Detail Seluruh Sesi untuk Sampel 16 ===\n";
$s16 = $mysqli->query("SELECT * FROM samples WHERE id = 16")->fetch_assoc();
print_r($s16);

$res16 = $mysqli->query("SELECT * FROM testing_sessions WHERE sample_id = 16");
while ($r = $res16->fetch_assoc()) {
    echo "Sesi ID: " . $r['id'] . " | jenis: " . $r['jenis_pengujian'] . " | status: " . $r['status'] . "\n";
}

$eval16 = $model->cek_kelengkapan_sesi_sampel(16);
print_r($eval16);
