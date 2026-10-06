<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Pengguna (Users) LIMSKU
 * 
 * Menangani autentikasi, verifikasi password, dan CRUD pengguna.
 */
class Model_Pengguna extends CI_Model
{
    protected $tabel = 'users';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Mengambil seluruh data pengguna beserta nama role & laboratorium
     * 
     * @return array
     */
    public function ambil_semua()
    {
        $this->db->select('u.*, r.nama_role, l.nama_lab, l.kode_lab');
        $this->db->from($this->tabel . ' u');
        $this->db->join('roles r', 'r.id = u.role_id', 'left');
        $this->db->join('laboratories l', 'l.id = u.laboratory_id', 'left');
        $this->db->order_by('u.nama_lengkap', 'ASC');
        return $this->db->get()->result_array();
    }

    /**
     * Mengambil data pengguna berdasarkan ID
     * 
     * @param int $id
     * @return array|null
     */
    public function ambil_by_id($id)
    {
        $this->db->select('u.*, r.nama_role, l.nama_lab, l.kode_lab');
        $this->db->from($this->tabel . ' u');
        $this->db->join('roles r', 'r.id = u.role_id', 'left');
        $this->db->join('laboratories l', 'l.id = u.laboratory_id', 'left');
        $this->db->where('u.id', $id);
        return $this->db->get()->row_array();
    }

    /**
     * Mengambil data pengguna berdasarkan username atau NIP
     * 
     * @param string $identifier Username atau NIP
     * @return array|null
     */
    public function ambil_by_identifier($identifier)
    {
        $this->db->select('u.*, r.nama_role, l.nama_lab');
        $this->db->from($this->tabel . ' u');
        $this->db->join('roles r', 'r.id = u.role_id', 'left');
        $this->db->join('laboratories l', 'l.id = u.laboratory_id', 'left');
        $this->db->group_start();
        $this->db->where('u.username', $identifier);
        $this->db->or_where('u.nip', $identifier);
        $this->db->group_end();
        return $this->db->get()->row_array();
    }

    /**
     * Verifikasi kredensial login pengguna
     * 
     * @param string $identifier Username atau NIP
     * @param string $password Password plaintext
     * @return array Array berisi 'status' (bool), 'pesan' (string), dan 'user' (array|null)
     */
    public function verifikasi_login($identifier, $password)
    {
        $user = $this->ambil_by_identifier($identifier);

        if (!$user) {
            return array(
                'status' => FALSE,
                'pesan'  => 'Nama pengguna / NIP atau kata sandi tidak valid.',
                'user'   => NULL
            );
        }

        if ($user['status'] !== 'Aktif') {
            return array(
                'status' => FALSE,
                'pesan'  => 'Akun Anda sedang nonaktif. Silakan hubungi Administrator.',
                'user'   => NULL
            );
        }

        if (!password_verify($password, $user['password'])) {
            return array(
                'status' => FALSE,
                'pesan'  => 'Nama pengguna / NIP atau kata sandi tidak valid.',
                'user'   => NULL
            );
        }

        return array(
            'status' => TRUE,
            'pesan'  => 'Login berhasil.',
            'user'   => $user
        );
    }

    /**
     * Menambah pengguna baru
     * 
     * @param array $data Data pengguna
     * @return int|bool ID pengguna baru
     */
    public function tambah($data)
    {
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }

        if ($this->db->insert($this->tabel, $data)) {
            return $this->db->insert_id();
        }
        return FALSE;
    }

    /**
     * Mengubah data pengguna
     * 
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function ubah($id, $data)
    {
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        } else {
            unset($data['password']);
        }

        $this->db->where('id', $id);
        return $this->db->update($this->tabel, $data);
    }

    /**
     * Mengubah/reset password pengguna
     * 
     * @param int $id
     * @param string $password_baru
     * @return bool
     */
    public function ubah_password($id, $password_baru)
    {
        $hash = password_hash($password_baru, PASSWORD_BCRYPT);
        $this->db->where('id', $id);
        return $this->db->update($this->tabel, array('password' => $hash));
    }

    /**
     * Mengubah status pengguna (Aktif/Nonaktif)
     * 
     * @param int $id
     * @param string $status
     * @return bool
     */
    public function ubah_status($id, $status)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->tabel, array('status' => $status));
    }

    /**
     * Menghapus pengguna
     * 
     * @param int $id
     * @return bool
     */
    public function hapus($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete($this->tabel);
    }
}
