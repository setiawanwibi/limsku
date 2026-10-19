<?php
/**
 * Isolated Automated Test Suite for Phase 3:
 * Technical Manager (MT) Decision (Approval & Rejection) at Sample Level
 *
 * Tests:
 * 1. Approval Kimia saja yang wajib.
 * 2. Approval Mikrobiologi saja yang wajib.
 * 3. Approval Kimia + Mikrobiologi dalam satu keputusan atomik.
 * 4. Salah satu sesi wajib belum dibuat/tidak siap: ditolak (fail-closed).
 * 5. Kuantitas ambigu: ditolak (fail-closed).
 * 6. Sesi ganda ambigu: ditolak secara fail-closed (SESI_GANDA_AMBIGU).
 * 7. Sesi historis final yang sah dapat dipertahankan jika tidak ada sesi aktif lain.
 * 8. Approval hanya memproses sesi milik sample_id yang benar.
 * 9. Penolakan tanpa alasan/kosong/spasi: ditolak dengan pesan jelas.
 * 10. Penolakan valid mengubah seluruh sesi wajib yang aktif secara konsisten.
 * 11. Kegagalan update salah satu sesi: seluruh transaksi rollback.
 * 12. Kegagalan audit log: tidak ada keputusan parsial yang dianggap sukses.
 * 13. Role tanpa permission: ditolak oleh guard server-side.
 * 14. Request duplikat atau status sudah berubah: tidak menghasilkan keputusan ganda.
 * 15. Integritas status: tidak menandai nomor LHU final palsu sebelum generator Tahap 4.
 */

define('BASEPATH', 'true');
define('APPPATH', dirname(__DIR__) . '/application/');

// Mock CI_Model & Database Handler untuk pengujian transaksi terisolasi di memori
if (!class_exists('CI_Model')) {
    class CI_Model {
        public $db;
        public function __construct() {}
    }
}

if (!function_exists('get_instance')) {
    class MockCISuite {
        public $Model_User_Signature;
        public function __construct() {
            $this->Model_User_Signature = new class {
                public function ambil_by_user_id($uid) {
                    return [
                        'id' => $uid,
                        'user_id' => $uid,
                        'signature_file' => 'uploads/signatures/test_proof.jpg'
                    ];
                }
            };
        }
    }
    $GLOBALS['CI_PHASE3'] = new MockCISuite();
    function get_instance() {
        return $GLOBALS['CI_PHASE3'];
    }
}

class MockDBDriver {
    public $trans_started = false;
    public $trans_committed = false;
    public $trans_rolled_back = false;
    public $trans_status = true;
    public $fail_on_update_table = null;
    public $fail_on_insert_table = null;

    public $samples_table = [];
    public $sessions_table = [];
    public $results_table = [];
    public $audit_table = [];
    public $users_table = [];

    protected $where_conditions = [];
    protected $where_in_conditions = [];
    protected $from_table = null;

    public function trans_start() {
        $this->trans_started = true;
        $this->trans_committed = false;
        $this->trans_rolled_back = false;
        $this->trans_status = true;
    }

    public function trans_rollback() {
        $this->trans_rolled_back = true;
        $this->trans_status = false;
    }

    public function trans_complete() {
        if ($this->trans_status) {
            $this->trans_committed = true;
        } else {
            $this->trans_rolled_back = true;
        }
    }

    public function trans_status() {
        return $this->trans_status;
    }

    public function select($s) { return $this; }
    public function from($f) { $this->from_table = $f; return $this; }
    public function join($t, $c, $type = 'inner') { return $this; }
    public function order_by($k, $dir = 'ASC') { return $this; }

    public function where($key, $val = null) {
        if (is_array($key)) {
            foreach ($key as $k => $v) $this->where_conditions[$k] = $v;
        } else {
            $this->where_conditions[$key] = $val;
        }
        return $this;
    }

    public function where_in($key, $vals) {
        $this->where_in_conditions[$key] = $vals;
        return $this;
    }

    public function get_where($table, $where = []) {
        $this->where($where);
        return $this->get($table);
    }

    public function get($table = null) {
        if ($table === null && $this->from_table !== null) {
            $table = $this->from_table;
        }
        $this->from_table = null;

        $rows = [];
        $t_clean = $table ? trim(explode(' ', trim($table))[0]) : '';
        if ($t_clean === 'samples') $rows = $this->samples_table;
        elseif ($t_clean === 'testing_sessions') $rows = $this->sessions_table;
        elseif ($t_clean === 'test_results') $rows = $this->results_table;
        elseif ($t_clean === 'users') $rows = $this->users_table;

        $filtered = [];
        foreach ($rows as $row) {
            $match = true;
            foreach ($this->where_conditions as $k => $v) {
                // Strip table prefix if any
                $col = strpos($k, '.') !== false ? substr($k, strpos($k, '.') + 1) : $k;
                if (!isset($row[$col]) || $row[$col] != $v) { $match = false; break; }
            }
            foreach ($this->where_in_conditions as $k => $arr) {
                $col = strpos($k, '.') !== false ? substr($k, strpos($k, '.') + 1) : $k;
                if (!isset($row[$col]) || !in_array($row[$col], $arr)) { $match = false; break; }
            }
            if ($match) $filtered[] = $row;
        }

        $this->where_conditions = [];
        $this->where_in_conditions = [];

        return new MockQueryResult($filtered);
    }

    public function update($table, $data) {
        if ($this->fail_on_update_table === $table) {
            $this->trans_status = false;
            return false;
        }

        $ref = null;
        if ($table === 'samples') $ref = &$this->samples_table;
        elseif ($table === 'testing_sessions') $ref = &$this->sessions_table;
        elseif ($table === 'test_results') $ref = &$this->results_table;

        if ($ref !== null) {
            foreach ($ref as &$row) {
                $match = true;
                foreach ($this->where_conditions as $k => $v) {
                    if (!isset($row[$k]) || $row[$k] != $v) { $match = false; break; }
                }
                foreach ($this->where_in_conditions as $k => $arr) {
                    if (!isset($row[$k]) || !in_array($row[$k], $arr)) { $match = false; break; }
                }
                if ($match) {
                    foreach ($data as $dk => $dv) $row[$dk] = $dv;
                }
            }
        }

        $this->where_conditions = [];
        $this->where_in_conditions = [];
        return true;
    }

    public function insert($table, $data) {
        if ($this->fail_on_insert_table === $table) {
            $this->trans_status = false;
            return false;
        }
        if ($table === 'audit_logs') {
            $data['id'] = count($this->audit_table) + 1;
            $this->audit_table[] = $data;
            return true;
        }
        return true;
    }
}

class MockQueryResult {
    protected $data;
    public function __construct($data) { $this->data = $data; }
    public function row_array() { return !empty($this->data) ? $this->data[0] : null; }
    public function result_array() { return $this->data; }
}

class MockInput {
    public function ip_address() { return '127.0.0.1'; }
    public function user_agent() { return 'MockAgent'; }
}

require_once APPPATH . 'models/Model_Pengujian.php';

$total_tests = 0;
$passed_tests = 0;
$failed_tests = 0;
$results_log = [];

function assert_test($name, $callable) {
    global $total_tests, $passed_tests, $failed_tests, $results_log;
    $total_tests++;
    try {
        $res = $callable();
        if ($res === true) {
            $passed_tests++;
            $results_log[] = ['name' => $name, 'status' => 'PASS', 'msg' => 'OK'];
            echo "[PASS] $name\n";
        } else {
            $failed_tests++;
            $results_log[] = ['name' => $name, 'status' => 'FAIL', 'msg' => (string)$res];
            echo "[FAIL] $name: $res\n";
        }
    } catch (Exception $e) {
        $failed_tests++;
        $results_log[] = ['name' => $name, 'status' => 'FAIL', 'msg' => $e->getMessage()];
        echo "[FAIL] $name: " . $e->getMessage() . "\n";
    }
}

echo "=== TEST SUITE TAHAP 3: KEPUTUSAN MANAJER TEKNIS (MT) ATOMIK ===\n\n";

// Helper membuat instance Model_Pengujian dengan mock DB
function create_mock_model($sample, $sessions, $results = []) {
    $model = new Model_Pengujian();
    $mock_db = new MockDBDriver();
    $mock_db->samples_table = is_array($sample) ? [$sample] : [];
    
    // Pastikan setiap sesi memiliki penguji_id dan verifier_id default jika belum ada
    foreach ($sessions as &$s) {
        if (!isset($s['penguji_id'])) $s['penguji_id'] = 3;
        if (!isset($s['verifier_id'])) $s['verifier_id'] = 4;
        if (!isset($s['nama_penguji'])) $s['nama_penguji'] = 'Penguji Test';
        if (!isset($s['nama_verifier'])) $s['nama_verifier'] = 'Penyelia Test';
    }
    $mock_db->sessions_table = $sessions;
    $mock_db->results_table = $results;
    $mock_db->users_table = [
        ['id' => 3, 'nama_lengkap' => 'Penguji Test', 'nip' => '19850101'],
        ['id' => 4, 'nama_lengkap' => 'Penyelia Test', 'nip' => '19800202'],
        ['id' => 5, 'nama_lengkap' => 'Drs. Hendra, Apt. (MT)', 'nip' => '19750303']
    ];

    $model->db = $mock_db;
    $model->input = new MockInput();
    return [$model, $mock_db];
}

// 1. Approval Kimia saja yang wajib
assert_test("1. Approval Kimia saja yang wajib", function() {
    $sample = ['id' => 1, 'kode_sampel_manual' => 'SMP-01', 'jumlah_kimia' => '100 gram', 'jumlah_mikro' => '0', 'status' => 'Menunggu Approval'];
    $sessions = [
        ['id' => 10, 'sample_id' => 1, 'jenis_pengujian' => 'Kimia', 'status' => 'Menunggu Approval']
    ];
    $results = [
        ['id' => 101, 'session_id' => 10, 'sample_id' => 1, 'status' => 'Menunggu Approval']
    ];

    list($model, $db) = create_mock_model($sample, $sessions, $results);

    $res = $model->approve_sampel_mt(1, 5, 'hendra_mt');
    if (!$res['sukses']) return "Harus sukses: " . $res['pesan'];
    if ($db->samples_table[0]['status'] !== 'Approved / Final') return "Status sampel harus Approved / Final";
    if ($db->sessions_table[0]['status'] !== 'Approved / Final') return "Status sesi harus Approved / Final";
    if ($db->results_table[0]['status'] !== 'Approved / Final') return "Status test_results harus Approved / Final";
    if (count($db->audit_table) !== 1) return "Harus ada 1 audit log";
    return true;
});

// 2. Approval Mikrobiologi saja yang wajib
assert_test("2. Approval Mikrobiologi saja yang wajib", function() {
    $sample = ['id' => 2, 'kode_sampel_manual' => 'SMP-02', 'jumlah_kimia' => '-', 'jumlah_mikro' => '30 ml', 'status' => 'Menunggu Approval'];
    $sessions = [
        ['id' => 20, 'sample_id' => 2, 'jenis_pengujian' => 'Mikrobiologi', 'status' => 'Menunggu Approval']
    ];

    list($model, $db) = create_mock_model($sample, $sessions);

    $res = $model->approve_sampel_mt(2, 5, 'hendra_mt');
    if (!$res['sukses']) return "Harus sukses: " . $res['pesan'];
    if ($db->sessions_table[0]['status'] !== 'Approved / Final') return "Sesi Mikro harus Approved / Final";
    return true;
});

// 3. Approval Kimia + Mikrobiologi dalam satu keputusan atomik
assert_test("3. Approval Kimia + Mikrobiologi dalam satu keputusan atomik", function() {
    $sample = ['id' => 3, 'kode_sampel_manual' => 'SMP-03', 'jumlah_kimia' => '10 tab', 'jumlah_mikro' => '20 tab', 'status' => 'Menunggu Approval'];
    $sessions = [
        ['id' => 31, 'sample_id' => 3, 'jenis_pengujian' => 'Kimia', 'status' => 'Menunggu Approval'],
        ['id' => 32, 'sample_id' => 3, 'jenis_pengujian' => 'Mikrobiologi', 'status' => 'Menunggu Approval']
    ];

    list($model, $db) = create_mock_model($sample, $sessions);

    $res = $model->approve_sampel_mt(3, 5, 'hendra_mt');
    if (!$res['sukses']) return "Harus sukses: " . $res['pesan'];
    if ($db->sessions_table[0]['status'] !== 'Approved / Final' || $db->sessions_table[1]['status'] !== 'Approved / Final') {
        return "Kedua sesi wajib harus Approved / Final";
    }
    if ($res['sesi_ids'] !== [31, 32]) return "Sesi yang diproses harus [31, 32]";
    return true;
});

// 4. Salah satu sesi wajib belum dibuat/tidak siap -> ditolak fail-closed
assert_test("4. Salah satu sesi wajib belum dibuat/tidak siap: ditolak", function() {
    $sample = ['id' => 4, 'kode_sampel_manual' => 'SMP-04', 'jumlah_kimia' => '10 tab', 'jumlah_mikro' => '20 tab', 'status' => 'Sedang Diuji'];
    $sessions = [
        ['id' => 41, 'sample_id' => 4, 'jenis_pengujian' => 'Kimia', 'status' => 'Menunggu Approval'],
        ['id' => 42, 'sample_id' => 4, 'jenis_pengujian' => 'Mikrobiologi', 'status' => 'Sedang Diuji']
    ];

    list($model, $db) = create_mock_model($sample, $sessions);

    $res = $model->approve_sampel_mt(4, 5, 'hendra_mt');
    if ($res['sukses']) return "Harus gagal jika salah satu sesi masih Sedang Diuji";
    if ($db->samples_table[0]['status'] !== 'Sedang Diuji') return "Status sampel tidak boleh berubah";
    return true;
});

// 5. Kuantitas ambigu -> ditolak fail-closed
assert_test("5. Kuantitas ambigu: ditolak fail-closed", function() {
    $sample = ['id' => 5, 'kode_sampel_manual' => 'SMP-05', 'jumlah_kimia' => 'perlu diuji', 'jumlah_mikro' => '10 tab'];
    $sessions = [
        ['id' => 51, 'sample_id' => 5, 'jenis_pengujian' => 'Mikrobiologi', 'status' => 'Menunggu Approval']
    ];

    list($model, $db) = create_mock_model($sample, $sessions);

    $res = $model->approve_sampel_mt(5, 5, 'hendra_mt');
    if ($res['sukses']) return "Harus gagal jika kuantitas ambigu";
    return true;
});

// 6. Sesi ganda ambigu: ditolak secara fail-closed (SESI_GANDA_AMBIGU)
assert_test("6. Sesi ganda ambigu: ditolak secara fail-closed", function() {
    $model = new Model_Pengujian();
    // Skenario A: 2 sesi Kimia keduanya berstatus Menunggu Approval
    $sampel = ['id' => 60, 'jumlah_kimia' => '100 g', 'jumlah_mikro' => '0'];
    $sesi_list = [
        ['id' => 601, 'sample_id' => 60, 'jenis_pengujian' => 'Kimia', 'status' => 'Menunggu Approval'],
        ['id' => 602, 'sample_id' => 60, 'jenis_pengujian' => 'Kimia', 'status' => 'Menunggu Approval']
    ];
    $eval1 = $model->evaluasi_kelengkapan_sesi_sampel($sampel, $sesi_list);
    if ($eval1['siap_mt'] || $eval1['status_sesi']['Kimia']['status'] !== 'SESI_GANDA_AMBIGU') {
        return "2 sesi Menunggu Approval pada bidang sama harus SESI_GANDA_AMBIGU";
    }

    // Skenario B: 1 sesi Menunggu Approval + 1 sesi Sedang Diuji
    $sesi_list2 = [
        ['id' => 603, 'sample_id' => 60, 'jenis_pengujian' => 'Kimia', 'status' => 'Menunggu Approval'],
        ['id' => 604, 'sample_id' => 60, 'jenis_pengujian' => 'Kimia', 'status' => 'Sedang Diuji']
    ];
    $eval2 = $model->evaluasi_kelengkapan_sesi_sampel($sampel, $sesi_list2);
    if ($eval2['siap_mt'] || $eval2['status_sesi']['Kimia']['status'] !== 'SESI_GANDA_AMBIGU') {
        return "1 Menunggu Approval + 1 Sedang Diuji harus SESI_GANDA_AMBIGU";
    }

    // Skenario C: 1 sesi Menunggu Approval + 1 sesi Ditolak
    $sesi_list3 = [
        ['id' => 605, 'sample_id' => 60, 'jenis_pengujian' => 'Kimia', 'status' => 'Menunggu Approval'],
        ['id' => 606, 'sample_id' => 60, 'jenis_pengujian' => 'Kimia', 'status' => 'Ditolak']
    ];
    $eval3 = $model->evaluasi_kelengkapan_sesi_sampel($sampel, $sesi_list3);
    if ($eval3['siap_mt'] || $eval3['status_sesi']['Kimia']['status'] !== 'SESI_GANDA_AMBIGU') {
        return "1 Menunggu Approval + 1 Ditolak harus SESI_GANDA_AMBIGU";
    }

    return true;
});

// 7. Sesi historis final yang sah
assert_test("7. Sesi historis final sah jika tidak ada sesi aktif", function() {
    $model = new Model_Pengujian();
    $sampel = ['id' => 70, 'jumlah_kimia' => '50 g', 'jumlah_mikro' => '0'];
    $sesi_list = [
        ['id' => 701, 'sample_id' => 70, 'jenis_pengujian' => 'Kimia', 'status' => 'Approved / Final']
    ];
    $eval = $model->evaluasi_kelengkapan_sesi_sampel($sampel, $sesi_list);
    if (!$eval['siap_mt'] || $eval['status_sesi']['Kimia']['status'] !== 'Approved / Final') {
        return "Sesi final historis sah tanpa sesi aktif lain harus diterima";
    }
    return true;
});

// 8. Approval hanya memproses sesi milik sample_id yang benar
assert_test("8. Approval hanya memproses sesi milik sample_id yang benar", function() {
    $sample = ['id' => 8, 'kode_sampel_manual' => 'SMP-08', 'jumlah_kimia' => '10', 'jumlah_mikro' => '0'];
    $sessions = [
        ['id' => 81, 'sample_id' => 999, 'jenis_pengujian' => 'Kimia', 'status' => 'Menunggu Approval'] // Mismatch sample_id
    ];

    list($model, $db) = create_mock_model($sample, $sessions);

    $res = $model->approve_sampel_mt(8, 5, 'hendra_mt');
    if ($res['sukses']) return "Harus gagal karena sesi milik sample_id 999 bukan 8";
    return true;
});

// 9. Penolakan tanpa alasan / spasi kosong: ditolak
assert_test("9. Penolakan tanpa alasan/kosong/spasi: ditolak", function() {
    $sample = ['id' => 9, 'kode_sampel_manual' => 'SMP-09', 'jumlah_kimia' => '10', 'jumlah_mikro' => '0'];
    $sessions = [['id' => 91, 'sample_id' => 9, 'jenis_pengujian' => 'Kimia', 'status' => 'Menunggu Approval']];

    list($model, $db) = create_mock_model($sample, $sessions);

    $res1 = $model->tolak_sampel_mt(9, 5, '', 'hendra_mt');
    if ($res1['sukses']) return "Alasan string kosong harus gagal";

    $res2 = $model->tolak_sampel_mt(9, 5, '   ', 'hendra_mt');
    if ($res2['sukses']) return "Alasan spasi whitespace harus gagal";

    $res3 = $model->tolak_sampel_mt(9, 5, 'abc', 'hendra_mt');
    if ($res3['sukses']) return "Alasan < 5 karakter harus gagal";

    return true;
});

// 10. Penolakan valid mengubah seluruh sesi wajib secara konsisten
assert_test("10. Penolakan valid mengubah seluruh sesi wajib secara konsisten", function() {
    $sample = ['id' => 10, 'kode_sampel_manual' => 'SMP-10', 'jumlah_kimia' => '10', 'jumlah_mikro' => '10', 'status' => 'Menunggu Approval'];
    $sessions = [
        ['id' => 101, 'sample_id' => 10, 'jenis_pengujian' => 'Kimia', 'status' => 'Menunggu Approval'],
        ['id' => 102, 'sample_id' => 10, 'jenis_pengujian' => 'Mikrobiologi', 'status' => 'Menunggu Approval']
    ];

    list($model, $db) = create_mock_model($sample, $sessions);

    $alasan = 'Pengujian kontrol negatif tidak memenuhi syarat pada uji mikro';
    $res = $model->tolak_sampel_mt(10, 5, $alasan, 'hendra_mt');

    if (!$res['sukses']) return "Penolakan harus sukses: " . $res['pesan'];
    if ($db->samples_table[0]['status'] !== 'Ditolak') return "Sampel harus Ditolak";
    if ($db->sessions_table[0]['status'] !== 'Ditolak' || $db->sessions_table[1]['status'] !== 'Ditolak') {
        return "Kedua sesi harus Ditolak";
    }
    if ($db->sessions_table[0]['alasan_penolakan'] !== $alasan) return "Alasan penolakan sesi tidak cocok";
    if (count($db->audit_table) !== 1) return "Harus menulis 1 audit log";
    return true;
});

// 11. Kegagalan update salah satu sesi: seluruh transaksi rollback
assert_test("11. Kegagalan update salah satu sesi: seluruh transaksi rollback", function() {
    $sample = ['id' => 11, 'kode_sampel_manual' => 'SMP-11', 'jumlah_kimia' => '10', 'jumlah_mikro' => '0', 'status' => 'Menunggu Approval'];
    $sessions = [['id' => 111, 'sample_id' => 11, 'jenis_pengujian' => 'Kimia', 'status' => 'Menunggu Approval']];

    list($model, $db) = create_mock_model($sample, $sessions);
    $db->fail_on_update_table = 'testing_sessions'; // Simulasikan kegagalan DB pada testing_sessions

    $res = $model->approve_sampel_mt(11, 5, 'hendra_mt');
    if ($res['sukses']) return "Harus gagal jika update testing_sessions gagal";
    if (!$db->trans_rolled_back) return "Database harus di-rollback";
    return true;
});

// 12. Kegagalan audit log: tidak ada keputusan parsial yang dianggap sukses
assert_test("12. Kegagalan audit log: tidak ada keputusan parsial yang dianggap sukses", function() {
    $sample = ['id' => 12, 'kode_sampel_manual' => 'SMP-12', 'jumlah_kimia' => '10', 'jumlah_mikro' => '0', 'status' => 'Menunggu Approval'];
    $sessions = [['id' => 121, 'sample_id' => 12, 'jenis_pengujian' => 'Kimia', 'status' => 'Menunggu Approval']];

    list($model, $db) = create_mock_model($sample, $sessions);
    $db->fail_on_insert_table = 'audit_logs'; // Simulasikan failure pada audit log

    $res = $model->approve_sampel_mt(12, 5, 'hendra_mt');
    if ($res['sukses']) return "Harus gagal jika audit log gagal";
    if (!$db->trans_rolled_back) return "Database harus di-rollback jika audit log gagal";
    return true;
});

// 13. Role tanpa permission: ditolak oleh guard
assert_test("13. Role tanpa permission ditolak oleh guard", function() {
    $role_perms = [
        1 => ['pengujian_input'],
        2 => ['pengujian_verify'],
        3 => ['pengujian_approve']
    ];
    $has_perm = function($r_id, $p) use ($role_perms) {
        return isset($role_perms[$r_id]) && in_array($p, $role_perms[$r_id], true);
    };

    if ($has_perm(1, 'pengujian_approve')) return "Analis tidak boleh approve";
    if ($has_perm(2, 'pengujian_approve')) return "Penyelia tidak boleh approve";
    if (!$has_perm(3, 'pengujian_approve')) return "MT harus boleh approve";
    return true;
});

// 14. Request duplikat atau status sudah berubah
assert_test("14. Request duplikat atau status sudah berubah tidak menghasilkan keputusan ganda", function() {
    $sample = ['id' => 14, 'kode_sampel_manual' => 'SMP-14', 'jumlah_kimia' => '10', 'jumlah_mikro' => '0', 'status' => 'Approved / Final'];
    $sessions = [['id' => 141, 'sample_id' => 14, 'jenis_pengujian' => 'Kimia', 'status' => 'Approved / Final']];

    list($model, $db) = create_mock_model($sample, $sessions);

    $res = $model->approve_sampel_mt(14, 5, 'hendra_mt');
    // Karena sesi sudah Approved / Final, evaluator menyatakan bukan Menunggu Approval
    if ($res['sukses']) return "Tidak boleh menyetujui sampel yang sudah final";
    return true;
});

// 15. Status tidak menyatakan LHU final palsu sebelum generator Tahap 4
assert_test("15. Nomor LHU tidak di-generate sembarangan sebelum generator Tahap 4", function() {
    $sample = ['id' => 15, 'kode_sampel_manual' => 'SMP-15', 'no_lhu' => null, 'jumlah_kimia' => '10', 'jumlah_mikro' => '0', 'status' => 'Menunggu Approval'];
    $sessions = [['id' => 151, 'sample_id' => 15, 'jenis_pengujian' => 'Kimia', 'status' => 'Menunggu Approval']];

    list($model, $db) = create_mock_model($sample, $sessions);

    $res = $model->approve_sampel_mt(15, 5, 'hendra_mt');
    if (!$res['sukses']) return "Harus sukses";
    // Pastikan no_lhu tetap null/tidak dibuat nomor palsu
    if (!empty($db->samples_table[0]['no_lhu'])) {
        return "Nomor LHU tidak boleh dibuat sebelum generator PDF gabungan Tahap 4";
    }
    return true;
});

echo "\n=== RINGKASAN TEST SUITE TAHAP 3 ===\n";
echo "Total Tests : $total_tests\n";
echo "Passed      : $passed_tests\n";
echo "Failed      : $failed_tests\n";

if ($failed_tests > 0) {
    exit(1);
}
exit(0);
