<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Pengujian LIMSKU
 * 
 * Menangani Antrean Pengujian, Sesi Pengujian Multi-Form (Kimia & Mikrobiologi),
 * Self Assignment, Penyimpanan Hasil Uji, dan Transisi Status Workflow.
 */
class Model_Pengujian extends CI_Model
{
    protected $tabel_hasil = 'test_results';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Mengambil daftar sampel yang berstatus "Menunggu Pengujian" (Antrean Pengujian)
     * 
     * @return array
     */
    public function ambil_antrean()
    {
        $this->db->select('s.*, uc.nama_lengkap as nama_pembuat');
        $this->db->from('samples s');
        $this->db->join('users uc', 'uc.id = s.created_by', 'left');
        $this->db->where('s.status', 'Menunggu Pengujian');
        $this->db->order_by('s.id', 'ASC');
        return $this->db->get()->result_array();
    }

    /**
     * Mengambil daftar sampel yang sedang diuji
     * 
     * @param int|null $penguji_id
     * @return array
     */
    public function ambil_dalam_pengujian($penguji_id = NULL)
    {
        $this->db->select('s.*, u.nama_lengkap as nama_penguji, ts.id as session_id, ts.jenis_pengujian');
        $this->db->from('samples s');
        $this->db->join('users u', 'u.id = s.penguji_id', 'left');
        $this->db->join('testing_sessions ts', "ts.sample_id = s.id AND ts.status = 'Sedang Diuji'", 'left');
        $this->db->where('s.status', 'Sedang Diuji');
        if ($penguji_id) {
            $this->db->where('s.penguji_id', $penguji_id);
        }
        $this->db->order_by('s.id', 'DESC');
        return $this->db->get()->result_array();
    }

    /**
     * Klaim sampel oleh penguji (Self-Assignment)
     * Mengubah status sampel dari "Menunggu Pengujian" -> "Sedang Diuji"
     * 
     * @param int $sample_id
     * @param int $penguji_id
     * @return bool
     */
    public function klaim_sampel($sample_id, $penguji_id)
    {
        $this->db->trans_start();

        // Lock & Verify status sampel
        $sampel = $this->db->get_where('samples', array('id' => $sample_id))->row_array();
        if (!$sampel || $sampel['status'] !== 'Menunggu Pengujian') {
            $this->db->trans_rollback();
            return FALSE;
        }

        // Update status & penguji_id pada tabel samples
        $this->db->where('id', $sample_id);
        $this->db->update('samples', array(
            'status'     => 'Sedang Diuji',
            'penguji_id' => $penguji_id
        ));

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /**
     * Membuat Sesi Pengujian Baru dengan Multiple Form
     * 
     * @param int $sample_id
     * @param int $penguji_id
     * @param string $jenis_pengujian 'Kimia' / 'Mikrobiologi'
     * @param array $template_ids Array ID form_template
     * @return int|bool ID testing_session baru
     */
    public function buat_sesi_pengujian($sample_id, $penguji_id, $jenis_pengujian, $template_ids)
    {
        $this->db->trans_start();

        // 1. Insert ke testing_sessions
        $session_data = array(
            'sample_id'       => $sample_id,
            'penguji_id'      => $penguji_id,
            'jenis_pengujian' => $jenis_pengujian,
            'status'          => 'Sedang Diuji',
            'waktu_mulai'     => date('Y-m-d H:i:s')
        );
        $this->db->insert('testing_sessions', $session_data);
        $session_id = $this->db->insert_id();

        // 2. Insert ke test_results untuk setiap form_template yang dipilih
        foreach ($template_ids as $tid) {
            $tmpl = $this->db->get_where('form_templates', array('id' => $tid))->row_array();
            $test_data = array(
                'session_id'  => $session_id,
                'sample_id'   => $sample_id,
                'method_id'   => isset($tmpl['method_id']) ? $tmpl['method_id'] : NULL,
                'template_id' => $tid,
                'penguji_id'  => $penguji_id,
                'data_hasil'  => '[]',
                'kesimpulan'  => 'Belum Disimpulkan',
                'status'      => 'Sedang Diuji',
                'waktu_mulai' => date('Y-m-d H:i:s')
            );
            $this->db->insert('test_results', $test_data);
        }

        // 3. Pastikan status sampel adalah 'Sedang Diuji' dan penguji_id terisi
        $this->db->where('id', $sample_id);
        $this->db->update('samples', array(
            'status'     => 'Sedang Diuji',
            'penguji_id' => $penguji_id
        ));

        $this->db->trans_complete();

        if ($this->db->trans_status() === TRUE) {
            return $session_id;
        }
        return FALSE;
    }

    /**
     * Mengambil data sesi pengujian berdasarkan ID Sesi
     */
    public function ambil_sesi_by_id($session_id)
    {
        $this->db->select('ts.*, s.nama_sampel, s.kode_sampel_manual, s.no as no_sampel, s.kategori_sampel, u.nama_lengkap as nama_penguji, uv.nama_lengkap as nama_verifier, ua.nama_lengkap as nama_approver');
        $this->db->from('testing_sessions ts');
        $this->db->join('samples s', 's.id = ts.sample_id', 'inner');
        $this->db->join('users u', 'u.id = ts.penguji_id', 'left');
        $this->db->join('users uv', 'uv.id = ts.verifier_id', 'left');
        $this->db->join('users ua', 'ua.id = ts.approver_id', 'left');
        $this->db->where('ts.id', $session_id);
        return $this->db->get()->row_array();
    }

    /**
     * Mengambil daftar hasil uji yang terhubung dengan suatu Sesi Pengujian
     */
    public function ambil_hasil_by_session($session_id)
    {
        $this->db->select('tr.*, m.nama_metode, m.kode_metode, t.nama_template, t.kode_template, t.skema_form, t.kategori as kategori_template, p.nama_parameter, p.kode_parameter');
        $this->db->from('test_results tr');
        $this->db->join('form_templates t', 't.id = tr.template_id', 'left');
        $this->db->join('methods m', 'm.id = tr.method_id', 'left');
        $this->db->join('parameters p', 'p.id = t.parameter_id', 'left');
        $this->db->where('tr.session_id', $session_id);
        $this->db->order_by('tr.id', 'ASC');
        return $this->db->get()->result_array();
    }

    /**
     * Mengambil sesi pengujian aktif untuk sampel tertentu
     */
    public function ambil_sesi_by_sample($sample_id)
    {
        $this->db->where('sample_id', $sample_id);
        $this->db->order_by('id', 'DESC');
        return $this->db->get('testing_sessions')->row_array();
    }

    /**
     * Menyimpan hasil pengujian untuk seluruh form dalam satu Sesi Pengujian
     */
    public function simpan_hasil_sesi($session_id, $hasil_forms)
    {
        $this->db->trans_start();
        $waktu_sekarang = date('Y-m-d H:i:s');

        $sesi = $this->db->get_where('testing_sessions', array('id' => $session_id))->row_array();
        if (!$sesi) {
            $this->db->trans_rollback();
            return FALSE;
        }

        // Update masing-masing form di test_results
        foreach ($hasil_forms as $test_id => $f_data) {
            $update_data = array(
                'data_hasil'    => json_encode(isset($f_data['hasil']) ? $f_data['hasil'] : array(), JSON_UNESCAPED_UNICODE),
                'kesimpulan'    => isset($f_data['kesimpulan']) && !empty($f_data['kesimpulan']) ? $f_data['kesimpulan'] : 'Belum Disimpulkan',
                'catatan'       => isset($f_data['catatan']) ? $f_data['catatan'] : NULL,
                'status'        => 'Menunggu Verifikasi',
                'waktu_selesai' => $waktu_sekarang
            );
            $this->db->where('id', $test_id);
            $this->db->where('session_id', $session_id);
            $this->db->update('test_results', $update_data);
        }

        // Update status testing_sessions -> 'Menunggu Verifikasi'
        $this->db->where('id', $session_id);
        $this->db->update('testing_sessions', array(
            'status'        => 'Menunggu Verifikasi',
            'waktu_selesai' => $waktu_sekarang
        ));

        // Update status samples -> 'Menunggu Verifikasi'
        $this->db->where('id', $sesi['sample_id']);
        $this->db->update('samples', array(
            'status'     => 'Menunggu Verifikasi',
            'updated_by' => $sesi['penguji_id']
        ));

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /**
     * Menyimpan hasil pengujian dan melakukan transisi status sampel ke "Menunggu Verifikasi" (Single form / compatibility)
     */
    public function simpan_hasil_uji($data_hasil)
    {
        $this->db->trans_start();

        $data_hasil['status']        = 'Menunggu Verifikasi';
        $data_hasil['waktu_selesai'] = date('Y-m-d H:i:s');

        // Insert ke test_results
        $this->db->insert($this->tabel_hasil, $data_hasil);
        $test_id = $this->db->insert_id();

        // Update status sampel menjadi "Menunggu Verifikasi"
        $this->db->where('id', $data_hasil['sample_id']);
        $this->db->update('samples', array(
            'status'     => 'Menunggu Verifikasi',
            'updated_by' => $data_hasil['penguji_id']
        ));

        $this->db->trans_complete();

        if ($this->db->trans_status() === TRUE) {
            return $test_id;
        }
        return FALSE;
    }

    /**
     * Update path file PDF laporan hasil uji pada test_results
     */
    public function update_file_laporan($test_id, $file_path)
    {
        $this->db->where('id', $test_id);
        return $this->db->update($this->tabel_hasil, array('file_laporan' => $file_path));
    }

    /**
     * Update path file PDF laporan hasil uji pada testing_sessions
     */
    public function update_file_laporan_sesi($session_id, $file_path)
    {
        $this->db->where('session_id', $session_id);
        $this->db->update($this->tabel_hasil, array('file_laporan' => $file_path));

        $this->db->where('id', $session_id);
        return $this->db->update('testing_sessions', array('waktu_selesai' => date('Y-m-d H:i:s')));
    }

    /**
     * Mengambil hasil pengujian berdasarkan ID test_result
     */
    public function ambil_hasil_by_id($test_id)
    {
        $this->db->select('tr.*, m.nama_metode, m.kode_metode, p.nama_parameter, p.kode_parameter, t.nama_template, t.skema_form, u.nama_lengkap as nama_penguji, u.nip as nip_penguji, uv.nama_lengkap as nama_verifier, ua.nama_lengkap as nama_approver, s.nama_sampel, s.kode_sampel_manual, s.no as no_sampel, s.status as status_sampel, s.kategori_sampel, l.nama_lab');
        $this->db->from($this->tabel_hasil . ' tr');
        $this->db->join('samples s', 's.id = tr.sample_id', 'inner');
        $this->db->join('laboratories l', 'l.id = s.lab_id', 'left');
        $this->db->join('methods m', 'm.id = tr.method_id', 'left');
        $this->db->join('form_templates t', 't.id = tr.template_id', 'left');
        $this->db->join('parameters p', 'p.id = t.parameter_id', 'left');
        $this->db->join('users u', 'u.id = tr.penguji_id', 'left');
        $this->db->join('users uv', 'uv.id = tr.verifier_id', 'left');
        $this->db->join('users ua', 'ua.id = tr.approver_id', 'left');
        $this->db->where('tr.id', $test_id);
        return $this->db->get()->row_array();
    }

    /**
     * Mengambil daftar seluruh hasil pengujian / sesi pengujian
     * 
     * @param int|null $penguji_id Filter khusus penguji tertentu
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function ambil_semua_hasil($penguji_id = NULL, $limit = 1000, $offset = 0)
    {
        $this->db->select('ts.*, s.nama_sampel, s.kode_sampel_manual, s.no as no_sampel, u.nama_lengkap as nama_penguji, uv.nama_lengkap as nama_verifier, ua.nama_lengkap as nama_approver,
            (SELECT COUNT(*) FROM test_results tr WHERE tr.session_id = ts.id) as jumlah_form,
            (SELECT GROUP_CONCAT(DISTINCT t.nama_template SEPARATOR ", ") FROM test_results tr JOIN form_templates t ON t.id = tr.template_id WHERE tr.session_id = ts.id) as nama_template,
            (SELECT GROUP_CONCAT(DISTINCT tr.kesimpulan SEPARATOR ", ") FROM test_results tr WHERE tr.session_id = ts.id) as kesimpulan');
        $this->db->from('testing_sessions ts');
        $this->db->join('samples s', 's.id = ts.sample_id', 'inner');
        $this->db->join('users u', 'u.id = ts.penguji_id', 'left');
        $this->db->join('users uv', 'uv.id = ts.verifier_id', 'left');
        $this->db->join('users ua', 'ua.id = ts.approver_id', 'left');

        if ($penguji_id) {
            $this->db->where('ts.penguji_id', $penguji_id);
        }

        $this->db->order_by('ts.id', 'DESC');
        $this->db->limit($limit, $offset);
        $res = $this->db->get()->result_array();

        if (empty($res)) {
            // Fallback ke test_results jika belum ada testing_sessions
            $this->db->select('tr.*, m.nama_metode, t.nama_template, u.nama_lengkap as nama_penguji, uv.nama_lengkap as nama_verifier, ua.nama_lengkap as nama_approver, s.nama_sampel, s.kode_sampel_manual, s.no as no_sampel, 1 as jumlah_form');
            $this->db->from($this->tabel_hasil . ' tr');
            $this->db->join('samples s', 's.id = tr.sample_id', 'inner');
            $this->db->join('methods m', 'm.id = tr.method_id', 'left');
            $this->db->join('form_templates t', 't.id = tr.template_id', 'left');
            $this->db->join('users u', 'u.id = tr.penguji_id', 'left');
            $this->db->join('users uv', 'uv.id = tr.verifier_id', 'left');
            $this->db->join('users ua', 'ua.id = tr.approver_id', 'left');

            if ($penguji_id) {
                $this->db->where('tr.penguji_id', $penguji_id);
            }

            $this->db->order_by('tr.id', 'DESC');
            $this->db->limit($limit, $offset);
            return $this->db->get()->result_array();
        }

        return $res;
    }

    /**
     * FASE 5 — Mengambil antrean laporan hasil uji / sesi yang "Menunggu Approval"
     */
    public function ambil_antrean_approval()
    {
        $this->db->select('ts.*, s.nama_sampel, s.kode_sampel_manual, s.no as no_sampel, u.nama_lengkap as nama_penguji, uv.nama_lengkap as nama_verifier,
            (SELECT COUNT(*) FROM test_results tr WHERE tr.session_id = ts.id) as jumlah_form,
            (SELECT GROUP_CONCAT(DISTINCT m.nama_metode SEPARATOR ", ") FROM test_results tr JOIN methods m ON m.id = tr.method_id WHERE tr.session_id = ts.id) as nama_metode,
            (SELECT GROUP_CONCAT(DISTINCT t.nama_template SEPARATOR ", ") FROM test_results tr JOIN form_templates t ON t.id = tr.template_id WHERE tr.session_id = ts.id) as nama_template,
            (SELECT GROUP_CONCAT(DISTINCT tr.kesimpulan SEPARATOR ", ") FROM test_results tr WHERE tr.session_id = ts.id) as kesimpulan');
        $this->db->from('testing_sessions ts');
        $this->db->join('samples s', 's.id = ts.sample_id', 'inner');
        $this->db->join('users u', 'u.id = ts.penguji_id', 'left');
        $this->db->join('users uv', 'uv.id = ts.verifier_id', 'left');
        $this->db->where('ts.status', 'Menunggu Approval');
        $this->db->order_by('ts.id', 'ASC');
        $res = $this->db->get()->result_array();

        if (empty($res)) {
            $this->db->select('tr.*, m.nama_metode, t.nama_template, u.nama_lengkap as nama_penguji, uv.nama_lengkap as nama_verifier, s.nama_sampel, s.kode_sampel_manual, s.no as no_sampel, 1 as jumlah_form');
            $this->db->from($this->tabel_hasil . ' tr');
            $this->db->join('samples s', 's.id = tr.sample_id', 'inner');
            $this->db->join('methods m', 'm.id = tr.method_id', 'left');
            $this->db->join('form_templates t', 't.id = tr.template_id', 'left');
            $this->db->join('users u', 'u.id = tr.penguji_id', 'left');
            $this->db->join('users uv', 'uv.id = tr.verifier_id', 'left');
            $this->db->where('tr.status', 'Menunggu Approval');
            $this->db->order_by('tr.id', 'ASC');
            return $this->db->get()->result_array();
        }

        return $res;
    }

    /**
     * FASE 5 — Melakukan Approval oleh Manajer Teknis untuk Sesi
     */
    public function approve_sesi($session_id, $approver_id)
    {
        $this->db->trans_start();
        $waktu_sekarang = date('Y-m-d H:i:s');

        $sesi = $this->db->get_where('testing_sessions', array('id' => $session_id))->row_array();
        if (!$sesi || $sesi['status'] !== 'Menunggu Approval') {
            $this->db->trans_rollback();
            return FALSE;
        }

        // Update testing_sessions
        $this->db->where('id', $session_id);
        $this->db->update('testing_sessions', array(
            'status'         => 'Approved / Final',
            'approver_id'    => $approver_id,
            'waktu_approval' => $waktu_sekarang
        ));

        // Update test_results
        $this->db->where('session_id', $session_id);
        $this->db->update('test_results', array(
            'status'         => 'Approved / Final',
            'approver_id'    => $approver_id,
            'waktu_approval' => $waktu_sekarang
        ));

        // Update samples
        $this->db->where('id', $sesi['sample_id']);
        $this->db->update('samples', array(
            'status'     => 'Approved / Final',
            'updated_by' => $approver_id
        ));

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function approve_hasil($test_id, $approver_id)
    {
        $hasil = $this->db->get_where($this->tabel_hasil, array('id' => $test_id))->row_array();
        if ($hasil && !empty($hasil['session_id'])) {
            return $this->approve_sesi($hasil['session_id'], $approver_id);
        }

        $this->db->trans_start();

        if (!$hasil || $hasil['status'] !== 'Menunggu Approval') {
            $this->db->trans_rollback();
            return FALSE;
        }

        $waktu_sekarang = date('Y-m-d H:i:s');

        $this->db->where('id', $test_id);
        $this->db->update($this->tabel_hasil, array(
            'status'         => 'Approved / Final',
            'approver_id'    => $approver_id,
            'waktu_approval' => $waktu_sekarang
        ));

        $this->db->where('id', $hasil['sample_id']);
        $this->db->update('samples', array(
            'status'     => 'Approved / Final',
            'updated_by' => $approver_id
        ));

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /**
     * FASE 4 — Mengambil antrean laporan hasil uji yang "Menunggu Verifikasi"
     */
    public function ambil_antrean_verifikasi()
    {
        $this->db->select('ts.*, s.nama_sampel, s.kode_sampel_manual, s.no as no_sampel, u.nama_lengkap as nama_penguji,
            (SELECT COUNT(*) FROM test_results tr WHERE tr.session_id = ts.id) as jumlah_form,
            (SELECT GROUP_CONCAT(DISTINCT m.nama_metode SEPARATOR ", ") FROM test_results tr JOIN methods m ON m.id = tr.method_id WHERE tr.session_id = ts.id) as nama_metode,
            (SELECT GROUP_CONCAT(DISTINCT t.nama_template SEPARATOR ", ") FROM test_results tr JOIN form_templates t ON t.id = tr.template_id WHERE tr.session_id = ts.id) as nama_template,
            (SELECT GROUP_CONCAT(DISTINCT tr.kesimpulan SEPARATOR ", ") FROM test_results tr WHERE tr.session_id = ts.id) as kesimpulan');
        $this->db->from('testing_sessions ts');
        $this->db->join('samples s', 's.id = ts.sample_id', 'inner');
        $this->db->join('users u', 'u.id = ts.penguji_id', 'left');
        $this->db->where('ts.status', 'Menunggu Verifikasi');
        $this->db->order_by('ts.id', 'ASC');
        $res = $this->db->get()->result_array();

        if (empty($res)) {
            $this->db->select('tr.*, m.nama_metode, t.nama_template, u.nama_lengkap as nama_penguji, s.nama_sampel, s.kode_sampel_manual, s.no as no_sampel, 1 as jumlah_form');
            $this->db->from($this->tabel_hasil . ' tr');
            $this->db->join('samples s', 's.id = tr.sample_id', 'inner');
            $this->db->join('methods m', 'm.id = tr.method_id', 'left');
            $this->db->join('form_templates t', 't.id = tr.template_id', 'left');
            $this->db->join('users u', 'u.id = tr.penguji_id', 'left');
            $this->db->where('tr.status', 'Menunggu Verifikasi');
            $this->db->order_by('tr.id', 'ASC');
            return $this->db->get()->result_array();
        }

        return $res;
    }

    /**
     * FASE 4 — Melakukan Verifikasi oleh Penyelia untuk Sesi
     */
    public function verifikasi_sesi($session_id, $verifier_id)
    {
        $this->db->trans_start();
        $waktu_sekarang = date('Y-m-d H:i:s');

        $sesi = $this->db->get_where('testing_sessions', array('id' => $session_id))->row_array();
        if (!$sesi || $sesi['status'] !== 'Menunggu Verifikasi') {
            $this->db->trans_rollback();
            return FALSE;
        }

        $this->db->where('id', $session_id);
        $this->db->update('testing_sessions', array(
            'status'           => 'Menunggu Approval',
            'verifier_id'      => $verifier_id,
            'waktu_verifikasi' => $waktu_sekarang,
            'alasan_penolakan' => NULL
        ));

        $this->db->where('session_id', $session_id);
        $this->db->update('test_results', array(
            'status'           => 'Menunggu Approval',
            'verifier_id'      => $verifier_id,
            'waktu_verifikasi' => $waktu_sekarang,
            'alasan_penolakan' => NULL
        ));

        $this->db->where('id', $sesi['sample_id']);
        $this->db->update('samples', array(
            'status'     => 'Menunggu Approval',
            'updated_by' => $verifier_id
        ));

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function verifikasi_hasil($test_id, $verifier_id)
    {
        $hasil = $this->db->get_where($this->tabel_hasil, array('id' => $test_id))->row_array();
        if ($hasil && !empty($hasil['session_id'])) {
            return $this->verifikasi_sesi($hasil['session_id'], $verifier_id);
        }

        $this->db->trans_start();

        if (!$hasil || $hasil['status'] !== 'Menunggu Verifikasi') {
            $this->db->trans_rollback();
            return FALSE;
        }

        $waktu_sekarang = date('Y-m-d H:i:s');

        $this->db->where('id', $test_id);
        $this->db->update($this->tabel_hasil, array(
            'status'           => 'Menunggu Approval',
            'verifier_id'      => $verifier_id,
            'waktu_verifikasi' => $waktu_sekarang,
            'alasan_penolakan' => NULL
        ));

        $this->db->where('id', $hasil['sample_id']);
        $this->db->update('samples', array(
            'status'     => 'Menunggu Approval',
            'updated_by' => $verifier_id
        ));

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /**
     * FASE 4 — Melakukan Penolakan Hasil oleh Penyelia untuk Sesi
     */
    public function tolak_sesi($session_id, $verifier_id, $alasan_penolakan)
    {
        $this->db->trans_start();
        $waktu_sekarang = date('Y-m-d H:i:s');

        $sesi = $this->db->get_where('testing_sessions', array('id' => $session_id))->row_array();
        if (!$sesi || $sesi['status'] !== 'Menunggu Verifikasi') {
            $this->db->trans_rollback();
            return FALSE;
        }

        $this->db->where('id', $session_id);
        $this->db->update('testing_sessions', array(
            'status'           => 'Ditolak',
            'verifier_id'      => $verifier_id,
            'waktu_verifikasi' => $waktu_sekarang,
            'alasan_penolakan' => $alasan_penolakan
        ));

        $this->db->where('session_id', $session_id);
        $this->db->update('test_results', array(
            'status'           => 'Ditolak',
            'verifier_id'      => $verifier_id,
            'waktu_verifikasi' => $waktu_sekarang,
            'alasan_penolakan' => $alasan_penolakan
        ));

        $this->db->where('id', $sesi['sample_id']);
        $this->db->update('samples', array(
            'status'     => 'Ditolak',
            'updated_by' => $verifier_id
        ));

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function tolak_hasil($test_id, $verifier_id, $alasan_penolakan)
    {
        $hasil = $this->db->get_where($this->tabel_hasil, array('id' => $test_id))->row_array();
        if ($hasil && !empty($hasil['session_id'])) {
            return $this->tolak_sesi($hasil['session_id'], $verifier_id, $alasan_penolakan);
        }

        $this->db->trans_start();

        if (!$hasil || $hasil['status'] !== 'Menunggu Verifikasi') {
            $this->db->trans_rollback();
            return FALSE;
        }

        $waktu_sekarang = date('Y-m-d H:i:s');

        $this->db->where('id', $test_id);
        $this->db->update($this->tabel_hasil, array(
            'status'           => 'Ditolak',
            'verifier_id'      => $verifier_id,
            'waktu_verifikasi' => $waktu_sekarang,
            'alasan_penolakan' => $alasan_penolakan
        ));

        $this->db->where('id', $hasil['sample_id']);
        $this->db->update('samples', array(
            'status'     => 'Ditolak',
            'updated_by' => $verifier_id
        ));

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /**
     * FASE 4 — Penguji Melakukan Revisi atas Hasil yang Ditolak untuk Sesi
     */
    public function revisi_sesi($session_id, $data_revisi_forms)
    {
        $this->db->trans_start();
        $waktu_sekarang = date('Y-m-d H:i:s');

        $sesi = $this->db->get_where('testing_sessions', array('id' => $session_id))->row_array();
        if (!$sesi || $sesi['status'] !== 'Ditolak') {
            $this->db->trans_rollback();
            return FALSE;
        }

        foreach ($data_revisi_forms as $test_id => $f_data) {
            $update_data = array(
                'data_hasil'       => json_encode(isset($f_data['hasil']) ? $f_data['hasil'] : array(), JSON_UNESCAPED_UNICODE),
                'kesimpulan'       => isset($f_data['kesimpulan']) && !empty($f_data['kesimpulan']) ? $f_data['kesimpulan'] : 'Belum Disimpulkan',
                'catatan'          => isset($f_data['catatan']) ? $f_data['catatan'] : NULL,
                'status'           => 'Menunggu Verifikasi',
                'verifier_id'      => NULL,
                'waktu_verifikasi' => NULL,
                'alasan_penolakan' => NULL,
                'updated_at'       => $waktu_sekarang
            );
            $this->db->where('id', $test_id);
            $this->db->where('session_id', $session_id);
            $this->db->update('test_results', $update_data);
        }

        // Update testing_sessions
        $this->db->where('id', $session_id);
        $this->db->update('testing_sessions', array(
            'status'           => 'Menunggu Verifikasi',
            'verifier_id'      => NULL,
            'waktu_verifikasi' => NULL,
            'alasan_penolakan' => NULL,
            'updated_at'       => $waktu_sekarang
        ));

        // Update samples
        $this->db->where('id', $sesi['sample_id']);
        $this->db->update('samples', array(
            'status'     => 'Menunggu Verifikasi',
            'updated_by' => $sesi['penguji_id']
        ));

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function revisi_hasil($test_id, $data_revisi)
    {
        $hasil = $this->db->get_where($this->tabel_hasil, array('id' => $test_id))->row_array();
        if ($hasil && !empty($hasil['session_id'])) {
            return $this->revisi_sesi($hasil['session_id'], array($test_id => $data_revisi));
        }

        $this->db->trans_start();

        if (!$hasil || $hasil['status'] !== 'Ditolak') {
            $this->db->trans_rollback();
            return FALSE;
        }

        $data_revisi['status']           = 'Menunggu Verifikasi';
        $data_revisi['verifier_id']      = NULL;
        $data_revisi['waktu_verifikasi'] = NULL;
        $data_revisi['alasan_penolakan'] = NULL;
        $data_revisi['updated_at']       = date('Y-m-d H:i:s');

        $this->db->where('id', $test_id);
        $this->db->update($this->tabel_hasil, $data_revisi);

        $this->db->where('id', $hasil['sample_id']);
        $this->db->update('samples', array(
            'status'     => 'Menunggu Verifikasi',
            'updated_by' => $hasil['penguji_id']
        ));

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /**
     * Mengambil riwayat pengujian/sesi yang telah diverifikasi oleh penyelia tertentu
     */
    public function ambil_riwayat_verifikasi_by_user($verifier_id)
    {
        $this->db->select('ts.*, s.nama_sampel, s.kode_sampel_manual, s.no as no_sampel, u.nama_lengkap as nama_penguji, uv.nama_lengkap as nama_verifier, ua.nama_lengkap as nama_approver,
            (SELECT COUNT(*) FROM test_results tr WHERE tr.session_id = ts.id) as jumlah_form,
            (SELECT GROUP_CONCAT(DISTINCT t.nama_template SEPARATOR ", ") FROM test_results tr JOIN form_templates t ON t.id = tr.template_id WHERE tr.session_id = ts.id) as nama_template,
            (SELECT GROUP_CONCAT(DISTINCT tr.kesimpulan SEPARATOR ", ") FROM test_results tr WHERE tr.session_id = ts.id) as kesimpulan');
        $this->db->from('testing_sessions ts');
        $this->db->join('samples s', 's.id = ts.sample_id', 'inner');
        $this->db->join('users u', 'u.id = ts.penguji_id', 'left');
        $this->db->join('users uv', 'uv.id = ts.verifier_id', 'left');
        $this->db->join('users ua', 'ua.id = ts.approver_id', 'left');
        $this->db->where('ts.verifier_id', $verifier_id);
        $this->db->order_by('ts.waktu_verifikasi', 'DESC');
        return $this->db->get()->result_array();
    }

    /**
     * Mengambil riwayat pengujian/sesi yang telah diapprove oleh manajer teknis tertentu
     */
    public function ambil_riwayat_approval_by_user($approver_id)
    {
        $this->db->select('ts.*, s.nama_sampel, s.kode_sampel_manual, s.no as no_sampel, u.nama_lengkap as nama_penguji, uv.nama_lengkap as nama_verifier, ua.nama_lengkap as nama_approver,
            (SELECT COUNT(*) FROM test_results tr WHERE tr.session_id = ts.id) as jumlah_form,
            (SELECT GROUP_CONCAT(DISTINCT t.nama_template SEPARATOR ", ") FROM test_results tr JOIN form_templates t ON t.id = tr.template_id WHERE tr.session_id = ts.id) as nama_template,
            (SELECT GROUP_CONCAT(DISTINCT tr.kesimpulan SEPARATOR ", ") FROM test_results tr WHERE tr.session_id = ts.id) as kesimpulan');
        $this->db->from('testing_sessions ts');
        $this->db->join('samples s', 's.id = ts.sample_id', 'inner');
        $this->db->join('users u', 'u.id = ts.penguji_id', 'left');
        $this->db->join('users uv', 'uv.id = ts.verifier_id', 'left');
        $this->db->join('users ua', 'ua.id = ts.approver_id', 'left');
        $this->db->where('ts.approver_id', $approver_id);
        $this->db->order_by('ts.waktu_approval', 'DESC');
        return $this->db->get()->result_array();
    }
}
