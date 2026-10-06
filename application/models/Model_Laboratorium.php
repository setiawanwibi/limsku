<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Laboratorium LIMSKU
 * 
 * Menangani pengelolaan master laboratorium/unit pelaksana.
 */
class Model_Laboratorium extends CI_Model
{
    protected $tabel = 'laboratories';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Mengambil seluruh data laboratorium
     * 
     * @return array
     */
    public function ambil_semua()
    {
        $this->db->order_by('nama_lab', 'ASC');
        return $this->db->get($this->tabel)->result_array();
    }

    /**
     * Mengambil laboratorium yang berstatus Aktif saja
     * 
     * @return array
     */
    public function ambil_aktif()
    {
        $this->db->where('status', 'Aktif');
        $this->db->order_by('nama_lab', 'ASC');
        return $this->db->get($this->tabel)->result_array();
    }

    /**
     * Mengambil laboratorium berdasarkan ID
     * 
     * @param int $id
     * @return array|null
     */
    public function ambil_by_id($id)
    {
        return $this->db->get_where($this->tabel, array('id' => $id))->row_array();
    }

    /**
     * Menambah laboratorium baru
     * 
     * @param array $data
     * @return int|bool ID lab baru
     */
    public function tambah($data)
    {
        if ($this->db->insert($this->tabel, $data)) {
            return $this->db->insert_id();
        }
        return FALSE;
    }

    /**
     * Mengubah data laboratorium
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
     * Menghapus data laboratorium
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
     * Menghitung berapa pengguna yang terhubung ke laboratorium ini
     * 
     * @param int $lab_id
     * @return int
     */
    public function hitung_pengguna($lab_id)
    {
        $this->db->where('laboratory_id', $lab_id);
        return $this->db->count_all_results('users');
    }
}
