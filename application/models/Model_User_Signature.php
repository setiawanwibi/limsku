<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model User Signature (TTD Digital) LIMSKU
 * 
 * Menangani penyimpanan dan pengambilan TTD Digital berbasis user_id.
 * Hubungan One-to-One: 1 user hanya memiliki 1 signature di database.
 */
class Model_User_Signature extends CI_Model
{
    protected $tabel = 'user_signatures';

    public function __construct()
    {
        parent::__construct();
        $this->pastikan_tabel_ada();
    }

    /**
     * Memastikan tabel user_signatures sudah dibuat di database
     */
    private function pastikan_tabel_ada()
    {
        if (!$this->db->table_exists($this->tabel)) {
            $sql = "CREATE TABLE IF NOT EXISTS `user_signatures` (
              `id` INT AUTO_INCREMENT PRIMARY KEY,
              `user_id` INT NOT NULL UNIQUE,
              `signature_file` VARCHAR(255) NOT NULL,
              `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
              `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
              CONSTRAINT `fk_user_signatures_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
              INDEX `idx_user_signatures_user_id` (`user_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
            $this->db->query($sql);
        }
    }

    /**
     * Mengambil data TTD berdasarkan user_id
     * 
     * @param int $user_id
     * @return array|null
     */
    public function ambil_by_user_id($user_id)
    {
        if (empty($user_id)) {
            return NULL;
        }

        $this->db->where('user_id', $user_id);
        return $this->db->get($this->tabel)->row_array();
    }

    /**
     * Menyimpan / Memperbarui TTD Digital untuk user_id (Upsert 1-to-1)
     * 
     * @param int $user_id
     * @param string $signature_file Relative path file TTD
     * @return bool
     */
    public function simpan_signature($user_id, $signature_file)
    {
        if (empty($user_id) || empty($signature_file)) {
            return FALSE;
        }

        $existing = $this->ambil_by_user_id($user_id);

        if ($existing) {
            // Update record existing (One-to-One) tanpa menghapus file lama secara permanen
            $data_update = array(
                'signature_file' => $signature_file,
                'updated_at'     => date('Y-m-d H:i:s')
            );
            $this->db->where('user_id', $user_id);
            return $this->db->update($this->tabel, $data_update);
        } else {
            // Insert record baru
            $data_insert = array(
                'user_id'        => $user_id,
                'signature_file' => $signature_file,
                'created_at'     => date('Y-m-d H:i:s'),
                'updated_at'     => date('Y-m-d H:i:s')
            );
            return $this->db->insert($this->tabel, $data_insert);
        }
    }

    /**
     * Menghapus record TTD pengguna dari database
     * 
     * @param int $user_id
     * @return bool
     */
    public function hapus_signature($user_id)
    {
        if (empty($user_id)) {
            return FALSE;
        }

        $this->db->where('user_id', $user_id);
        return $this->db->delete($this->tabel);
    }
}
