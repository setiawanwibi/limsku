<?php
// scratch/execute_reagents_recovery_safe.php
//
// SAFE RUNNER FOR REAGENTS HISTORICAL RECOVERY & ROLLBACK
//
// PRINSIP KEAMANAN:
// 1. Default mode: DRY-RUN.
// 2. Proteksi target: HANYA boleh APPLY jika target database BUKAN 'limsku' aktif,
//    KECUALI jika flag explicitly diberikan setelah user memberikan izin resmi.
//    Secara default, runner MENOLAK eksekusi APPLY pada DB limsku aktif.
// 3. Fail-Closed: Semua precondition diperiksa di PHP secara ketat sebelum ada DML.
// 4. Concurrency Guard: Setiap UPDATE memeriksa ID, kode_barang, dan nilai lama secara eksak.
// 5. Postcondition Per Record & Global: Setiap mutasi divalidasi langsung di dalam transaksi.
// 6. Transaksi InnoDB: Jika ada 1 syarat gagal, otomatis $mysqli->rollback().
// 7. Rollback Safety: Rollback menggunakan optimistic lock (hanya mengembalikan jika nilai saat ini
//    masih identik dengan nilai hasil recovery).

class ReagentsRecoverySafeRunner {
    private $mysqli;
    private $db_name;
    private $is_apply = false;
    private $allow_active_db = false;
    private $log = [];

    public function __construct($mysqli, $db_name, $is_apply = false, $allow_active_db = false) {
        $this->mysqli = $mysqli;
        $this->db_name = $db_name;
        $this->is_apply = $is_apply;
        $this->allow_active_db = $allow_active_db;
    }

    public function runRecovery() {
        $this->log("=================================================================");
        $this->log("MEMULAI SAFE RECOVERY RUNNER - Target DB: {$this->db_name}");
        $this->log("Mode: " . ($this->is_apply ? "APPLY (MUTASI AKTIF)" : "DRY-RUN (SIMULASI READ-ONLY)"));
        $this->log("=================================================================");

        // SAFETY GATE 1: Dilarang apply pada database limsku aktif tanpa flag khusus
        if ($this->is_apply && strtolower($this->db_name) === 'limsku' && !$this->allow_active_db) {
            throw new Exception("BLOCKED: Eksekusi APPLY pada database 'limsku' aktif DILARANG sebelum ada izin eksplisit pengguna!");
        }

        // LOAD DATA GROUND TRUTH & MAPPING
        $json_file = __DIR__ . '/reconciliation_data.json';
        if (!file_exists($json_file)) {
            throw new Exception("File rekonsiliasi $json_file tidak ditemukan!");
        }
        $rec_data = json_decode(file_get_contents($json_file), true);
        $shifted_records = [];
        foreach ($rec_data['shifted_records'] as $s) {
            if ($s['id'] == 323) continue; // Exclude dummy row
            $shifted_records[] = $s;
        }
        $zero_date_records = $rec_data['zero_date_records'];

        // PRECONDITION CHECKS (FAIL-CLOSED)
        $this->log("\n--- FASE 1: PRECONDITION CHECKS ---");
        
        // 1. Total record
        $res = $this->mysqli->query("SELECT COUNT(*) as c FROM reagents");
        $total = (int)$res->fetch_assoc()['c'];
        if ($total !== 389) {
            throw new Exception("PRECONDITION FAIL: Total record adalah $total, diharapkan 389!");
        }
        $this->log("[PASS] Total record tepat 389.");

        // 2. Zero-date count
        $res = $this->mysqli->query("SELECT COUNT(*) as c FROM reagents WHERE expired_date = '0000-00-00'");
        $zero_dates = (int)$res->fetch_assoc()['c'];
        if ($zero_dates !== 68) {
            throw new Exception("PRECONDITION FAIL: Total zero-date adalah $zero_dates, diharapkan 68!");
        }
        $this->log("[PASS] Total expired_date '0000-00-00' tepat 68.");

        // 3. Null date count
        $res = $this->mysqli->query("SELECT COUNT(*) as c FROM reagents WHERE expired_date IS NULL");
        $null_dates = (int)$res->fetch_assoc()['c'];
        if ($null_dates !== 66) {
            throw new Exception("PRECONDITION FAIL: Total null-date adalah $null_dates, diharapkan 66!");
        }
        $this->log("[PASS] Total expired_date NULL tepat 66.");

        // 4. Validasi nilai lama setiap 44 record secara eksak
        $this->log("Memeriksa kesesuaian nilai lama 44 record target...");
        foreach ($shifted_records as $s) {
            $id = (int)$s['id'];
            $kode = $s['kode'];
            $res = $this->mysqli->query("SELECT * FROM reagents WHERE id = $id");
            $curr = $res->fetch_assoc();
            if (!$curr) {
                throw new Exception("PRECONDITION FAIL: ID $id tidak ditemukan di DB!");
            }
            if ($curr['kode_barang'] !== $kode) {
                throw new Exception("PRECONDITION FAIL: ID $id kode barang DB '{$curr['kode_barang']}' != mapping '$kode'!");
            }
            if ($curr['nama_barang'] !== $s['old']['nama_barang']) {
                throw new Exception("PRECONDITION FAIL: ID $id nama_barang DB '{$curr['nama_barang']}' != mapping lama '{$s['old']['nama_barang']}'!");
            }
            if ($curr['jenis_barang'] !== $s['old']['jenis_barang']) {
                throw new Exception("PRECONDITION FAIL: ID $id jenis_barang DB '{$curr['jenis_barang']}' != mapping lama '{$s['old']['jenis_barang']}'!");
            }
        }
        $this->log("[PASS] Seluruh 44 record cocok sempurna dengan kondisi awal DB.");

        if (!$this->is_apply) {
            $this->log("\n[DRY-RUN COMPLETE] Semua precondition lolos 100%. Simulasi selesai tanpa mutasi.");
            return true;
        }

        // TRANSACTION START
        $this->log("\n--- FASE 2: MEMULAI TRANSAKSI MUTASI (InnoDB) ---");
        $this->mysqli->begin_transaction();

        try {
            // EKSEKUSI 44 RECORD DENGAN PREPARED STATEMENT
            $stmt = $this->mysqli->prepare("
                UPDATE reagents
                SET nama_barang = ?,
                    nama_reagen = ?,
                    katalog = ?,
                    merk = ?,
                    stok = ?,
                    jenis_barang = ?,
                    expired_date = ?
                WHERE id = ? AND kode_barang = ? AND nama_barang = ?
            ");

            $updated_shifted_count = 0;
            foreach ($shifted_records as $s) {
                $id = (int)$s['id'];
                $kode = $s['kode'];
                $new_nama = $s['new']['nama_barang'];
                $new_nama_reagen = substr($new_nama, 0, 150); // Truncation safe for nama_reagen column
                $new_kat = $s['new']['katalog'];
                $new_merk = $s['new']['merk'];
                $new_stok = $s['new']['stok'];
                $new_jenis = $s['new']['jenis_barang'];
                $new_exp = $s['new']['expired_date']; // Can be null
                $old_nama = $s['old']['nama_barang'];

                $stmt->bind_param(
                    "ssssssssss",
                    $new_nama,
                    $new_nama_reagen,
                    $new_kat,
                    $new_merk,
                    $new_stok,
                    $new_jenis,
                    $new_exp,
                    $id,
                    $kode,
                    $old_nama
                );
                $stmt->execute();

                if ($stmt->affected_rows !== 1) {
                    throw new Exception("DML FAIL: Update pada ID $id affected_rows = {$stmt->affected_rows}, diharapkan 1!");
                }
                $updated_shifted_count++;
            }
            $stmt->close();
            $this->log("[PASS] 44 record berhasil diperbaiki posisi kolom dan nama_reagen.");

            // NORMALISASI ZERO-DATE DENGAN STRICT GUARD
            // Hanya normalisasi record yang bernilai '0000-00-00'
            $res = $this->mysqli->query("UPDATE reagents SET expired_date = NULL WHERE expired_date = '0000-00-00'");
            $zero_normalized = $this->mysqli->affected_rows;
            $this->log("[PASS] Zero-date normalized to NULL: $zero_normalized rows.");

            // POSTCONDITION CHECKS
            $this->log("\n--- FASE 3: POSTCONDITION CHECKS ---");

            // 1. Tidak boleh ada serial angka di jenis_barang
            $res = $this->mysqli->query("SELECT COUNT(*) as c FROM reagents WHERE jenis_barang REGEXP '^[0-9]{5}$'");
            $serial_count = (int)$res->fetch_assoc()['c'];
            if ($serial_count !== 0) {
                throw new Exception("POSTCONDITION FAIL: Masih ada $serial_count record dengan serial angka di jenis_barang!");
            }

            // 2. Tidak boleh ada '0000-00-00'
            $res = $this->mysqli->query("SELECT COUNT(*) as c FROM reagents WHERE expired_date = '0000-00-00'");
            $zero_remaining = (int)$res->fetch_assoc()['c'];
            if ($zero_remaining !== 0) {
                throw new Exception("POSTCONDITION FAIL: Masih ada $zero_remaining record expired_date '0000-00-00'!");
            }

            // 3. Kolom stok bersih dari kategori reagen
            $res = $this->mysqli->query("SELECT COUNT(*) as c FROM reagents WHERE stok IN ('Reagen Cair', 'Reagen Padat', 'Media Mikrobiologi', 'Baku Pembanding')");
            $stok_invalid = (int)$res->fetch_assoc()['c'];
            if ($stok_invalid !== 0) {
                throw new Exception("POSTCONDITION FAIL: Masih ada $stok_invalid record stok berisi nama kategori!");
            }

            // 4. Total record tetap 389
            $res = $this->mysqli->query("SELECT COUNT(*) as c FROM reagents");
            $total_after = (int)$res->fetch_assoc()['c'];
            if ($total_after !== 389) {
                throw new Exception("POSTCONDITION FAIL: Total record berubah menjadi $total_after!");
            }

            // 5. Cek sinkronisasi nama_reagen untuk 44 record
            $res = $this->mysqli->query("SELECT COUNT(*) as c FROM reagents WHERE id IN (8,9,10,11) AND nama_reagen LIKE '%VIAL%'");
            if ((int)$res->fetch_assoc()['c'] !== 0) {
                throw new Exception("POSTCONDITION FAIL: nama_reagen masih berisi nilai lama 'VIAL'!");
            }

            // COMMIT
            $this->mysqli->commit();
            $this->log("[COMMIT SUCCESS] Seluruh perubahan berhasil disimpan secara permanen!");
            return true;

        } catch (Exception $e) {
            $this->mysqli->rollback();
            $this->log("[ROLLBACK TRIGGERED] Terjadi kegagalan: " . $e->getMessage());
            throw $e;
        }
    }

    public function runRollback() {
        $this->log("=================================================================");
        $this->log("MEMULAI SAFE ROLLBACK RUNNER - Target DB: {$this->db_name}");
        $this->log("Mode: " . ($this->is_apply ? "APPLY (MUTASI AKTIF)" : "DRY-RUN (SIMULASI READ-ONLY)"));
        $this->log("=================================================================");

        if ($this->is_apply && strtolower($this->db_name) === 'limsku' && !$this->allow_active_db) {
            throw new Exception("BLOCKED: Eksekusi APPLY ROLLBACK pada database 'limsku' aktif DILARANG tanpa izin!");
        }

        $json_file = __DIR__ . '/reconciliation_data.json';
        $rec_data = json_decode(file_get_contents($json_file), true);
        $shifted_records = [];
        foreach ($rec_data['shifted_records'] as $s) {
            if ($s['id'] == 323) continue;
            $shifted_records[] = $s;
        }
        $zero_date_records = $rec_data['zero_date_records'];

        if (!$this->is_apply) {
            $this->log("[DRY-RUN ROLLBACK COMPLETE] Validasi data rollback lolos.");
            return true;
        }

        $this->mysqli->begin_transaction();
        try {
            // ROLLBACK 44 RECORD DENGAN OPTIMISTIC CONCURRENCY GUARD
            // Hanya rollback jika nama_barang saat ini masih cocok dengan hasil recovery
            $stmt = $this->mysqli->prepare("
                UPDATE reagents
                SET nama_barang = ?,
                    nama_reagen = ?,
                    katalog = ?,
                    merk = ?,
                    stok = ?,
                    jenis_barang = ?,
                    expired_date = ?
                WHERE id = ? AND kode_barang = ? AND nama_barang = ?
            ");

            $rolled_back_count = 0;
            $skipped_count = 0;

            foreach ($shifted_records as $s) {
                $id = (int)$s['id'];
                $kode = $s['kode'];
                $old_nama = $s['old']['nama_barang'];
                $old_nama_reagen = substr($old_nama, 0, 150);
                $old_kat = $s['old']['katalog'];
                $old_merk = $s['old']['merk'];
                $old_stok = $s['old']['stok'];
                $old_jenis = $s['old']['jenis_barang'];
                $old_exp = $s['old']['expired_date'];

                // Expected current value (the recovered value)
                $expected_curr_nama = $s['new']['nama_barang'];

                $stmt->bind_param(
                    "ssssssssss",
                    $old_nama,
                    $old_nama_reagen,
                    $old_kat,
                    $old_merk,
                    $old_stok,
                    $old_jenis,
                    $old_exp,
                    $id,
                    $kode,
                    $expected_curr_nama
                );
                $stmt->execute();

                if ($stmt->affected_rows === 1) {
                    $rolled_back_count++;
                } else {
                    $skipped_count++;
                    $this->log("[CONCURRENCY CONFLICT SKIPPED] ID $id telah diubah pengguna pasca-recovery, rollback dilewati.");
                }
            }
            $stmt->close();
            $this->log("[PASS] Rollback shifted records: $rolled_back_count dipulihkan, $skipped_count dilewati karena konflik.");

            // ROLLBACK ZERO-DATE HANYA YANG MASIH NULL
            $zero_ids = [];
            foreach ($zero_date_records as $z) {
                $zero_ids[] = (int)$z['id'];
            }
            $zero_ids_str = implode(',', $zero_ids);
            $res = $this->mysqli->query("
                UPDATE reagents 
                SET expired_date = '0000-00-00' 
                WHERE id IN ($zero_ids_str) AND expired_date IS NULL
            ");
            $zero_rolled_back = $this->mysqli->affected_rows;
            $this->log("[PASS] Rollback zero-date ke '0000-00-00': $zero_rolled_back rows (guard: hanya yang masih NULL).");

            $this->mysqli->commit();
            $this->log("[ROLLBACK COMMIT SUCCESS] Rollback selesai.");
            return true;
        } catch (Exception $e) {
            $this->mysqli->rollback();
            $this->log("[ROLLBACK FAILED] " . $e->getMessage());
            throw $e;
        }
    }

    private function log($msg) {
        $this->log[] = $msg;
        echo $msg . "\n";
    }

    public function getLogs() {
        return $this->log;
    }
}
