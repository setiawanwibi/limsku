<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Autentikasi
 * 
 * Menangani halaman masuk (login) dan keluar (logout) sistem LIMSKU.
 * Mengikuti konvensi penamaan Bahasa Indonesia (D10).
 */
class Autentikasi extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_Pengguna');
    }

    /**
     * Halaman Masuk / Login awal
     * 
     * @return void
     */
    public function index()
    {
        $this->masuk();
    }

    /**
     * Tampilan form masuk dan proses autentikasi
     * 
     * @return void
     */
    public function masuk()
    {
        // Jika sudah login, alihkan langsung ke dasbor
        if ($this->session->userdata('logged_in')) {
            redirect('dasbor');
        }

        // Tangani submit form POST
        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('nama_pengguna', 'Nama Pengguna / NIP', 'required|trim', array(
                'required' => '%s wajib diisi.'
            ));
            $this->form_validation->set_rules('kata_sandi', 'Kata Sandi', 'required', array(
                'required' => '%s wajib diisi.'
            ));

            if ($this->form_validation->run() === TRUE) {
                $identifier = $this->input->post('nama_pengguna', TRUE);
                $password   = $this->input->post('kata_sandi');

                $hasil = $this->Model_Pengguna->verifikasi_login($identifier, $password);

                if ($hasil['status'] === TRUE) {
                    $user = $hasil['user'];

                    // Set data session login
                    $sess_data = array(
                        'user_id'       => $user['id'],
                        'username'      => $user['username'],
                        'nama_lengkap'  => $user['nama_lengkap'],
                        'role_id'       => $user['role_id'],
                        'role_name'     => $user['nama_role'],
                        'laboratory_id' => $user['laboratory_id'],
                        'logged_in'     => TRUE
                    );
                    $this->session->set_userdata($sess_data);

                    // Catat audit log login sukses
                    $this->catat_audit('LOGIN_SUKSES', 'Autentikasi', array(
                        'username' => $user['username'],
                        'role'     => $user['nama_role']
                    ));

                    $this->session->set_flashdata('pesan_sukses', 'Selamat datang kembali, ' . $user['nama_lengkap'] . '!');
                    redirect('dasbor');
                } else {
                    // Catat audit log login gagal (tanpa menyimpan password)
                    $this->catat_audit('LOGIN_GAGAL', 'Autentikasi', array(
                        'identifier' => $identifier,
                        'alasan'     => $hasil['pesan']
                    ));

                    $this->session->set_flashdata('pesan_gagal', $hasil['pesan']);
                    redirect('masuk');
                }
            }
        }

        $this->data['judul_halaman'] = 'Masuk ke Sistem';
        $this->load->view('autentikasi/masuk', $this->data);
    }

    /**
     * Keluar dari sistem (Logout)
     * 
     * @return void
     */
    public function keluar()
    {
        if ($this->session->userdata('logged_in')) {
            // Catat audit log logout
            $this->catat_audit('LOGOUT', 'Autentikasi', array(
                'username' => $this->session->userdata('username')
            ));
        }

        // Hapus seluruh session
        $this->session->sess_destroy();
        redirect('masuk');
    }
}
