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

        $this->data['user']           = $this->Model_Pengguna->ambil_by_id($user_id);
        $this->data['boleh_ttd']      = $this->boleh_kelola_ttd();
        $this->data['user_signature'] = $this->Model_User_Signature->ambil_by_user_id($user_id);

        $this->muat_tampilan('akun/index');
    }

    /**
     * Proses Perubahan Data Diri Mandiri (Nama Lengkap & Email)
     */
    public function ubah_profil()
    {
        $user_id = (int)$this->session->userdata('user_id');

        if ($this->input->method() === 'post') {
            $this->load->library('form_validation');

            $user_sekarang = $this->Model_Pengguna->ambil_by_id($user_id);
            $username_input = trim($this->input->post('username', TRUE));

            $this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'required|trim', array(
                'required' => '%s wajib diisi.'
            ));
            $this->form_validation->set_rules('username', 'Username', 'required|alpha_dash|trim|min_length[3]', array(
                'required'   => '%s wajib diisi.',
                'alpha_dash' => '%s hanya boleh berisi huruf, angka, underscore, atau dash.',
                'min_length' => '%s minimal 3 karakter.'
            ));
            $this->form_validation->set_rules('email', 'Email', 'valid_email|trim', array(
                'valid_email' => 'Format %s tidak valid.'
            ));

            // Cek keunikan username jika diubah
            if ($username_input !== $user_sekarang['username']) {
                $cek_duplikat = $this->db->where('username', $username_input)
                                         ->where('id !=', $user_id)
                                         ->get('users')
                                         ->row_array();
                if ($cek_duplikat) {
                    $this->session->set_flashdata('pesan_gagal', 'Username "' . html_escape($username_input) . '" sudah digunakan pengguna lain.');
                    redirect('akun');
                }
            }

            if ($this->form_validation->run() === TRUE) {
                $data_update = array(
                    'nama_lengkap' => $this->input->post('nama_lengkap', TRUE),
                    'username'     => $username_input,
                    'email'        => $this->input->post('email', TRUE) ?: NULL
                );

                $update = $this->Model_Pengguna->ubah($user_id, $data_update);
                if ($update) {
                    // Update juga nama dan username di session login aktif
                    $this->session->set_userdata('nama_lengkap', $data_update['nama_lengkap']);
                    $this->session->set_userdata('username', $data_update['username']);

                    $this->catat_audit('UPDATE_PROFIL_MANDIRI', 'Akun', $data_update);
                    $this->session->set_flashdata('pesan_sukses', 'Data diri dan username berhasil diperbarui.');
                } else {
                    $this->session->set_flashdata('pesan_gagal', 'Gagal memperbarui data profil.');
                }
            } else {
                $this->session->set_flashdata('pesan_gagal', validation_errors('', ' '));
            }
        }

        redirect('akun');
    }

    /**
     * Proses Perubahan Kata Sandi Mandiri
     */
    public function ubah_password()
    {
        $user_id = (int)$this->session->userdata('user_id');

        if ($this->input->method() === 'post') {
            $this->load->library('form_validation');

            $this->form_validation->set_rules('sandi_lama', 'Kata Sandi Saat Ini', 'required', array(
                'required' => '%s wajib diisi.'
            ));
            $this->form_validation->set_rules('sandi_baru', 'Kata Sandi Baru', 'required|min_length[6]', array(
                'required'   => '%s wajib diisi.',
                'min_length' => '%s minimal 6 karakter.'
            ));
            $this->form_validation->set_rules('konfirmasi_sandi', 'Konfirmasi Kata Sandi Baru', 'required|matches[sandi_baru]', array(
                'required' => '%s wajib diisi.',
                'matches'  => '%s tidak cocok dengan Kata Sandi Baru.'
            ));

            if ($this->form_validation->run() === TRUE) {
                $sandi_lama = $this->input->post('sandi_lama');
                $sandi_baru = $this->input->post('sandi_baru');

                // Ambil data user terkini untuk verifikasi sandi lama
                $user = $this->Model_Pengguna->ambil_by_id($user_id);
                if (!$user || !password_verify($sandi_lama, $user['password'])) {
                    $this->session->set_flashdata('pesan_gagal', 'Kata sandi saat ini yang Anda masukkan salah.');
                } else {
                    $ubah = $this->Model_Pengguna->ubah_password($user_id, $sandi_baru);
                    if ($ubah) {
                        $this->catat_audit('UPDATE_PASSWORD_MANDIRI', 'Akun', array('user_id' => $user_id));
                        $this->session->set_flashdata('pesan_sukses', 'Kata sandi akun Anda berhasil diperbarui.');
                    } else {
                        $this->session->set_flashdata('pesan_gagal', 'Gagal memperbarui kata sandi.');
                    }
                }
            } else {
                $this->session->set_flashdata('pesan_gagal', validation_errors('', ' '));
            }
        }

        redirect('akun');
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