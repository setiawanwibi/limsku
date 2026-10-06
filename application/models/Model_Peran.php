<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Peran (Roles) LIMSKU
 * 
 * Menangani pengelolaan master peran/role pengguna.
 */
class Model_Peran extends CI_Model
{
    protected $tabel = 'roles';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Mengambil seluruh data peran
     * 
     * @return array
     */
    public function ambil_semua()
    {
        $this->db->order_by('nama_role', 'ASC');
        return $this->db->get($this->tabel)->result_array();
    }

    /**
     * Mengambil peran berdasarkan ID
     * 
     * @param int $id
     * @return array|null
     */
    public function ambil_by_id($id)
    {
        return $this->db->get_where($this->tabel, array('id' => $id))->row_array();
    }

    /**
     * Menambah peran baru
     * 
     * @param array $data
     * @return int|bool ID role baru
     */
    public function tambah($data)
    {
        if ($this->db->insert($this->tabel, $data)) {
            return $this->db->insert_id();
        }
        return FALSE;
    }

    /**
     * Mengubah data peran
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
     * Menghapus data peran
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
     * Menghitung berapa pengguna yang terasosiasi dengan peran ini
     * 
     * @param int $role_id
     * @return int
     */
    public function hitung_pengguna($role_id)
    {
        $this->db->where('role_id', $role_id);
        return $this->db->count_all_results('users');
    }
}
