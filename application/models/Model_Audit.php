<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Audit Log LIMSKU
 * 
 * Menangani pencatatan dan pencarian riwayat aktivitas pengguna.
 */
class Model_Audit extends CI_Model
{
    protected $tabel = 'audit_logs';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Mencatat log aktivitas ke tabel audit_logs
     * 
     * @param int|null $user_id ID pengguna
     * @param string|null $username Username pengguna
     * @param string $aksi Jenis aksi (LOGIN, LOGOUT, CREATE, UPDATE, DELETE, HAK_AKSES)
     * @param string $modul Nama modul
     * @param string|array|null $detail Detail perubahan/informasi
     * @return bool
     */
    public function catat_log($user_id, $username, $aksi, $modul, $detail = NULL)
    {
        if (is_array($detail)) {
            $detail = json_encode($detail, JSON_UNESCAPED_UNICODE);
        }

        $data = array(
            'user_id'    => $user_id,
            'username'   => $username,
            'aksi'       => $aksi,
            'modul'      => $modul,
            'detail'     => $detail,
            'ip_address' => $this->input->ip_address(),
            'user_agent' => substr($this->input->user_agent(), 0, 255),
            'created_at' => date('Y-m-d H:i:s')
        );

        return $this->db->insert($this->tabel, $data);
    }

    /**
     * Mengambil daftar audit log
     * 
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function ambil_semua($limit = 500, $offset = 0)
    {
        $this->db->order_by('id', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get($this->tabel)->result_array();
    }

    /**
     * Menghitung total data log audit
     * 
     * @return int
     */
    public function hitung_semua()
    {
        return $this->db->count_all($this->tabel);
    }
}
