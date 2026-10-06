<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Pengujian LIMSKU
 * 
 * Menangani Antrean Pengujian, Klaim Sampel (Self Assignment), Penyimpanan Hasil Uji, dan Transisi Status Workflow.
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
        $this->db->select('s.*, u.nama_lengkap as nama_penguji');
        $this->db->from('samples s');
        $this->db->join('users u', 'u.id = s.penguji_id', 'left');
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
     * Menyimpan hasil pengujian dan melakukan transisi status sampel ke "Menunggu Verifikasi"
     * 
     * @param array $data_hasil Data hasil uji untuk tabel test_results
     * @return int|bool ID test_result baru
     */
    public function simpan_hasil_uji($data_hasil)
    {
        $this->db->trans_start();

        $data_hasil['status']       = 'Menunggu Verifikasi';
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
     * 
     * @param int $test_id
     * @param string $file_path
     * @return bool
     */
    public function update_file_laporan($test_id, $file_path)
    {
        $this->db->where('id', $test_id);
        return $this->db->update($this->tabel_hasil, array('file_laporan' => $file_path));
    }

    /**
     * Mengambil hasil pengujian berdasarkan ID test_result
     * 
     * @param int $test_id
     * @return array|null
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
     * Mengambil daftar seluruh hasil pengujian
     * 
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function ambil_semua_hasil($limit = 1000, $offset = 0)
    {
        $this->db->select('tr.*, m.nama_metode, t.nama_template, u.nama_lengkap as nama_penguji, uv.nama_lengkap as nama_verifier, ua.nama_lengkap as nama_approver, s.nama_sampel, s.kode_sampel_manual, s.no as no_sampel');
        $this->db->from($this->tabel_hasil . ' tr');
        $this->db->join('samples s', 's.id = tr.sample_id', 'inner');
        $this->db->join('methods m', 'm.id = tr.method_id', 'left');
        $this->db->join('form_templates t', 't.id = tr.template_id', 'left');
        $this->db->join('users u', 'u.id = tr.penguji_id', 'left');
        $this->db->join('users uv', 'uv.id = tr.verifier_id', 'left');
        $this->db->join('users ua', 'ua.id = tr.approver_id', 'left');
        $this->db->order_by('tr.id', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result_array();
    }

    /**
     * FASE 5 — Mengambil antrean laporan hasil uji yang "Menunggu Approval"
     * 
     * @return array
     */
    public function ambil_antrean_approval()
    {
        $this->db->select('tr.*, m.nama_metode, t.nama_template, u.nama_lengkap as nama_penguji, uv.nama_lengkap as nama_verifier, s.nama_sampel, s.kode_sampel_manual, s.no as no_sampel');
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

    /**
     * FASE 5 — Melakukan Approval oleh Manajer Teknis
     * Transisi status: "Menunggu Approval" -> "Approved / Final"
     * 
     * @param int $test_id
     * @param int $approver_id
     * @return bool
     */
    public function approve_hasil($test_id, $approver_id)
    {
        $this->db->trans_start();

        $hasil = $this->db->get_where($this->tabel_hasil, array('id' => $test_id))->row_array();
        if (!$hasil || $hasil['status'] !== 'Menunggu Approval') {
            $this->db->trans_rollback();
            return FALSE;
        }

        $waktu_sekarang = date('Y-m-d H:i:s');

        // Update test_results
        $this->db->where('id', $test_id);
        $this->db->update($this->tabel_hasil, array(
            'status'         => 'Approved / Final',
            'approver_id'    => $approver_id,
            'waktu_approval' => $waktu_sekarang
        ));

        // Update samples
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
     * 
     * @return array
     */
    public function ambil_antrean_verifikasi()
    {
        $this->db->select('tr.*, m.nama_metode, t.nama_template, u.nama_lengkap as nama_penguji, s.nama_sampel, s.kode_sampel_manual, s.no as no_sampel');
        $this->db->from($this->tabel_hasil . ' tr');
        $this->db->join('samples s', 's.id = tr.sample_id', 'inner');
        $this->db->join('methods m', 'm.id = tr.method_id', 'left');
        $this->db->join('form_templates t', 't.id = tr.template_id', 'left');
        $this->db->join('users u', 'u.id = tr.penguji_id', 'left');
        $this->db->where('tr.status', 'Menunggu Verifikasi');
        $this->db->order_by('tr.id', 'ASC');
        return $this->db->get()->result_array();
    }

    /**
     * FASE 4 — Melakukan Verifikasi oleh Penyelia
     * Transisi status: "Menunggu Verifikasi" -> "Menunggu Approval"
     * 
     * @param int $test_id
     * @param int $verifier_id
     * @return bool
     */
    public function verifikasi_hasil($test_id, $verifier_id)
    {
        $this->db->trans_start();

        $hasil = $this->db->get_where($this->tabel_hasil, array('id' => $test_id))->row_array();
        if (!$hasil || $hasil['status'] !== 'Menunggu Verifikasi') {
            $this->db->trans_rollback();
            return FALSE;
        }

        $waktu_sekarang = date('Y-m-d H:i:s');

        // Update test_results
        $this->db->where('id', $test_id);
        $this->db->update($this->tabel_hasil, array(
            'status'           => 'Menunggu Approval',
            'verifier_id'      => $verifier_id,
            'waktu_verifikasi' => $waktu_sekarang,
            'alasan_penolakan' => NULL
        ));

        // Update samples
        $this->db->where('id', $hasil['sample_id']);
        $this->db->update('samples', array(
            'status'     => 'Menunggu Approval',
            'updated_by' => $verifier_id
        ));

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /**
     * FASE 4 — Melakukan Penolakan Hasil oleh Penyelia
     * Transisi status: "Menunggu Verifikasi" -> "Ditolak"
     * 
     * @param int $test_id
     * @param int $verifier_id
     * @param string $alasan_penolakan
     * @return bool
     */
    public function tolak_hasil($test_id, $verifier_id, $alasan_penolakan)
    {
        $this->db->trans_start();

        $hasil = $this->db->get_where($this->tabel_hasil, array('id' => $test_id))->row_array();
        if (!$hasil || $hasil['status'] !== 'Menunggu Verifikasi') {
            $this->db->trans_rollback();
            return FALSE;
        }

        $waktu_sekarang = date('Y-m-d H:i:s');

        // Update test_results
        $this->db->where('id', $test_id);
        $this->db->update($this->tabel_hasil, array(
            'status'           => 'Ditolak',
            'verifier_id'      => $verifier_id,
            'waktu_verifikasi' => $waktu_sekarang,
            'alasan_penolakan' => $alasan_penolakan
        ));

        // Update samples
        $this->db->where('id', $hasil['sample_id']);
        $this->db->update('samples', array(
            'status'     => 'Ditolak',
            'updated_by' => $verifier_id
        ));

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /**
     * FASE 4 — Penguji Melakukan Revisi atas Hasil yang Ditolak
     * Transisi status: "Ditolak" -> "Menunggu Verifikasi"
     * 
     * @param int $test_id
     * @param array $data_revisi
     * @return bool
     */
    public function revisi_hasil($test_id, $data_revisi)
    {
        $this->db->trans_start();

        $hasil = $this->db->get_where($this->tabel_hasil, array('id' => $test_id))->row_array();
        if (!$hasil || $hasil['status'] !== 'Ditolak') {
            $this->db->trans_rollback();
            return FALSE;
        }

        $data_revisi['status']           = 'Menunggu Verifikasi';
        $data_revisi['verifier_id']      = NULL;
        $data_revisi['waktu_verifikasi'] = NULL;
        $data_revisi['alasan_penolakan'] = NULL;
        $data_revisi['updated_at']       = date('Y-m-d H:i:s');

        // Update test_results
        $this->db->where('id', $test_id);
        $this->db->update($this->tabel_hasil, $data_revisi);

        // Update status samples kembali ke "Menunggu Verifikasi"
        $this->db->where('id', $hasil['sample_id']);
        $this->db->update('samples', array(
            'status'     => 'Menunggu Verifikasi',
            'updated_by' => $hasil['penguji_id']
        ));

        $this->db->trans_complete();
        return $this->db->trans_status();
    }
}
