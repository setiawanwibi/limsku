<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Hak Akses (Permissions) LIMSKU
 * 
 * Menangani master permissions dan relasi role_permissions.
 */
class Model_Hak_Akses extends CI_Model
{
    protected $tabel_permissions = 'permissions';
    protected $tabel_role_permissions = 'role_permissions';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Mengambil seluruh data permission
     * 
     * @return array
     */
    public function ambil_semua()
    {
        $this->db->order_by('kategori', 'ASC');
        $this->db->order_by('nama_permission', 'ASC');
        return $this->db->get($this->tabel_permissions)->result_array();
    }

    /**
     * Mengambil permission berdasarkan ID
     * 
     * @param int $id
     * @return array|null
     */
    public function ambil_by_id($id)
    {
        return $this->db->get_where($this->tabel_permissions, array('id' => $id))->row_array();
    }

    /**
     * Menambah data permission
     * 
     * @param array $data
     * @return int|bool ID permission yang baru dibuat
     */
    public function tambah($data)
    {
        if ($this->db->insert($this->tabel_permissions, $data)) {
            return $this->db->insert_id();
        }
        return FALSE;
    }

    /**
     * Mengubah data permission
     * 
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function ubah($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->tabel_permissions, $data);
    }

    /**
     * Menghapus data permission
     * 
     * @param int $id
     * @return bool
     */
    public function hapus($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete($this->tabel_permissions);
    }

    /**
     * Memeriksa apakah role_id memiliki permission tertentu
     * 
     * @param int $role_id
     * @param string $nama_permission
     * @return bool
     */
    public function memiliki_akses($role_id, $nama_permission)
    {
        $this->db->select('rp.id');
        $this->db->from($this->tabel_role_permissions . ' rp');
        $this->db->join($this->tabel_permissions . ' p', 'p.id = rp.permission_id');
        $this->db->where('rp.role_id', $role_id);
        $this->db->where('p.nama_permission', $nama_permission);
        
        $query = $this->db->get();
        return ($query->num_rows() > 0);
    }

    /**
     * Mengambil array ID permission yang dimiliki oleh suatu role
     * 
     * @param int $role_id
     * @return array
     */
    public function ambil_permission_by_role($role_id)
    {
        $this->db->select('permission_id');
        $this->db->where('role_id', $role_id);
        $result = $this->db->get($this->tabel_role_permissions)->result_array();
        
        return array_column($result, 'permission_id');
    }

    /**
     * Menyimpan pemetaan permission untuk suatu role
     * 
     * @param int $role_id
     * @param array $permission_ids Array ID permission
     * @return bool
     */
    public function simpan_role_permissions($role_id, $permission_ids = array())
    {
        $this->db->trans_start();

        // Hapus pemetaan lama
        $this->db->where('role_id', $role_id);
        $this->db->delete($this->tabel_role_permissions);

        // Insert pemetaan baru
        if (!empty($permission_ids)) {
            $batch = array();
            foreach ($permission_ids as $pid) {
                $batch[] = array(
                    'role_id'       => $role_id,
                    'permission_id' => $pid
                );
            }
            $this->db->insert_batch($this->tabel_role_permissions, $batch);
        }

        $this->db->trans_complete();
        return $this->db->trans_status();
    }
}
