<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Sampel LIMSKU
 * 
 * Menangani manajemen Master Data dan Transaksi Sampel Laboratorium.
 */
class Model_Sampel extends CI_Model
{
    protected $tabel = 'samples';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Mengambil daftar sampel laboratorium dengan filter komoditi opsional
     * 
     * @param string|null $komoditi
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function ambil_semua($komoditi = NULL, $limit = 2000, $offset = 0)
    {
        $this->db->select('s.*, uc.nama_lengkap as nama_pembuat, uu.nama_lengkap as nama_pengubah');
        $this->db->from($this->tabel . ' s');
        $this->db->join('users uc', 'uc.id = s.created_by', 'left');
        $this->db->join('users uu', 'uu.id = s.updated_by', 'left');

        if (!empty($komoditi) && $komoditi !== 'semua') {
            $this->db->group_start();
            $this->db->where('s.komoditi', $komoditi);
            if ($komoditi === 'obat') {
                $this->db->or_where('s.komoditi IS NULL', NULL, FALSE);
                $this->db->or_where('s.komoditi', '');
            }
            $this->db->group_end();
        }

        $this->db->order_by('s.id', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result_array();
    }

    /**
     * Menghitung jumlah sampel per komoditi untuk badge navigasi tab
     * 
     * @return array
     */
    public function hitung_per_komoditi()
    {
        $this->db->select('
            COUNT(*) as total,
            SUM(CASE WHEN komoditi = "obat" OR komoditi IS NULL OR komoditi = "" THEN 1 ELSE 0 END) as total_obat,
            SUM(CASE WHEN komoditi = "pangan" THEN 1 ELSE 0 END) as total_pangan,
            SUM(CASE WHEN komoditi = "herbal" THEN 1 ELSE 0 END) as total_herbal,
            SUM(CASE WHEN komoditi = "kosmetik" THEN 1 ELSE 0 END) as total_kosmetik
        ');
        $res = $this->db->get($this->tabel)->row_array();
        return array(
            'semua'    => (int)($res['total'] ?? 0),
            'obat'     => (int)($res['total_obat'] ?? 0),
            'pangan'   => (int)($res['total_pangan'] ?? 0),
            'herbal'   => (int)($res['total_herbal'] ?? 0),
            'kosmetik' => (int)($res['total_kosmetik'] ?? 0),
        );
    }

    /**
     * Mengambil data sampel berdasarkan ID
     * 
     * @param int $id
     * @return array|null
     */
    public function ambil_by_id($id)
    {
        $this->db->select('s.*, uc.nama_lengkap as nama_pembuat, uu.nama_lengkap as nama_pengubah');
        $this->db->from($this->tabel . ' s');
        $this->db->join('users uc', 'uc.id = s.created_by', 'left');
        $this->db->join('users uu', 'uu.id = s.updated_by', 'left');
        $this->db->where('s.id', $id);
        return $this->db->get()->row_array();
    }

    /**
     * Menambah sampel baru
     * 
     * @param array $data
     * @return int|bool ID sampel baru
     */
    public function tambah($data)
    {
        $data['status'] = isset($data['status']) ? $data['status'] : 'Menunggu Pengujian';
        
        if ($this->db->insert($this->tabel, $data)) {
            return $this->db->insert_id();
        }
        return FALSE;
    }

    /**
     * Menambah banyak sampel sekaligus menggunakan database transaction
     * 
     * @param array $batch_data Array baris sampel
     * @return bool
     */
    public function tambah_batch($batch_data)
    {
        if (empty($batch_data)) {
            return FALSE;
        }

        $this->db->trans_start();
        $this->db->insert_batch($this->tabel, $batch_data);
        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    /**
     * Mengubah data sampel
     * 
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function ubah($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->tabel, $data);
    }

    /**
     * Menghapus data sampel
     * 
     * @param int $id
     * @return bool
     */
    public function hapus($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete($this->tabel);
    }

    /**
     * Menghitung total data sampel
     * 
     * @return int
     */
    public function hitung_semua()
    {
        return $this->db->count_all($this->tabel);
    }
}
