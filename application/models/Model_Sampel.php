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
     * Mengambil daftar sampel laboratorium
     * 
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function ambil_semua($limit = 2000, $offset = 0)
    {
        $this->db->select('s.*, uc.nama_lengkap as nama_pembuat, uu.nama_lengkap as nama_pengubah');
        $this->db->from($this->tabel . ' s');
        $this->db->join('users uc', 'uc.id = s.created_by', 'left');
        $this->db->join('users uu', 'uu.id = s.updated_by', 'left');
        $this->db->order_by('s.id', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result_array();
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
