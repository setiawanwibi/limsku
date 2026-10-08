<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Akun (Profil & TTD Digital) LIMSKU
 * 
 * Menangani pengelolaan akun mandiri untuk pengguna yang sedang login.
 * Mengikuti prinsip pemisahan controller (Akun.php khusus user login, Pengguna.php khusus Admin TI).
 */
class Akun extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->cek_login();
        $this->load->model('Model_Pengguna');
        $this->load->model('Model_User_Signature');
    }

    /**
     * Memeriksa apakah role user yang sedang login berhak memiliki TTD Digital
     * Role yang diizinkan: Penguji, Penyelia, Manajer Teknis
     * 
     * @return bool
     */
    private function boleh_kelola_ttd()
    {
        $role_id = $this->session->userdata('role_id');
        
        // Cek via permission atau role_id
        $is_penguji = $this->Model_Hak_Akses->memiliki_akses($role_id, 'pengujian_input');
        $is_penyelia = $this->Model_Hak_Akses->memiliki_akses($role_id, 'pengujian_verify');
        $is_mt       = $this->Model_Hak_Akses->memiliki_akses($role_id, 'pengujian_approve');

        return ($is_penguji || $is_penyelia || $is_mt);
    }

    /**
     * Halaman profil dan pengelolaan TTD Digital
     */
    public function index()
    {
        $user_id = $this->session->userdata('user_id');
        
        $this->data['halaman_aktif'] = 'akun';
        $this->data['judul_halaman'] = 'Profil & Akun Pengguna';
        $this->data['breadcrumbs']   = array(
            array('label' => 'Akun Saya', 'url' => site_url('akun'))
        );

        $this->data['user']           = $this->Model_Pengguna->ambil_by_id($user_id);
        $this->data['boleh_ttd']      = $this->boleh_kelola_ttd();
        $this->data['user_signature'] = $this->Model_User_Signature->ambil_by_user_id($user_id);

        $this->muat_tampilan('akun/index');
    }

    /**
     * Proses Unggah / Ganti Tanda Tangan Digital
     */
    public function upload_ttd()
    {
        // Otorisasi Server-side: hanya role berhak yang boleh upload TTD
        if (!$this->boleh_kelola_ttd()) {
            $this->session->set_flashdata('pesan_gagal', 'Role Anda tidak memiliki akses untuk mengelola Tanda Tangan Digital.');
            redirect('akun');
        }

        // Pengaman Server-side: user_id SELALU diambil dari session logged-in
        $user_id = $this->session->userdata('user_id');

        if ($this->input->method() === 'post') {
            // Pastikan folder upload ada
            $target_dir = FCPATH . 'uploads/signatures/';
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0755, TRUE);
            }

            // Konfigurasi Upload Library CodeIgniter
            $config['upload_path']   = $target_dir;
            $config['allowed_types'] = 'png|jpg|jpeg';
            $config['max_size']      = 2048; // 2 MB
            $config['file_name']     = 'sig_' . $user_id . '_' . time() . '_' . substr(md5(uniqid(rand(), true)), 0, 8);
            $config['overwrite']     = FALSE;

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('file_ttd')) {
                $error_msg = $this->upload->display_errors('', '');
                $this->session->set_flashdata('pesan_gagal', 'Gagal mengunggah TTD: ' . $error_msg);
            } else {
                $upload_data   = $this->upload->data();
                $relative_path = 'uploads/signatures/' . $upload_data['file_name'];

                // Simpan ke database (One-to-One Upsert berbasis user_id)
                $simpan = $this->Model_User_Signature->simpan_signature($user_id, $relative_path);

                if ($simpan) {
                    $this->catat_audit('UPLOAD_TTD', 'Akun', array(
                        'user_id'   => $user_id,
                        'file_path' => $relative_path
                    ));
                    $this->session->set_flashdata('pesan_sukses', 'Tanda Tangan Digital berhasil diperbarui.');
                } else {
                    $this->session->set_flashdata('pesan_gagal', 'Gagal menyimpan data Tanda Tangan Digital.');
                }
            }
        }

        redirect('akun');
    }

    /**
     * Proses Menghapus / Melepas Tanda Tangan Digital Aktif Pengguna
     */
    public function hapus_ttd()
    {
        // Otorisasi Server-side
        if (!$this->boleh_kelola_ttd()) {
            $this->session->set_flashdata('pesan_gagal', 'Role Anda tidak memiliki akses untuk mengelola Tanda Tangan Digital.');
            redirect('akun');
        }

        // Pengaman Server-side: user_id SELALU diambil dari session logged-in
        $user_id = $this->session->userdata('user_id');

        if ($this->input->method() === 'post') {
            // Hapus record dari database (file fisik tetap dipertahankan sesuai aturan historis)
            $hapus = $this->Model_User_Signature->hapus_signature($user_id);

            if ($hapus) {
                $this->catat_audit('HAPUS_TTD', 'Akun', array(
                    'user_id' => $user_id
                ));
                $this->session->set_flashdata('pesan_sukses', 'Tanda Tangan Digital berhasil dinonaktifkan.');
            } else {
                $this->session->set_flashdata('pesan_gagal', 'Gagal menonaktifkan Tanda Tangan Digital.');
            }
        }

        redirect('akun');
    }
}
