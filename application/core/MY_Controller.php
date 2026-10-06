<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Base Controller LIMSKU
 * 
 * Fondasi Controller dasar untuk aplikasi LIMSKU - BBPOM Bandar Lampung.
 * Mengikuti konvensi ekstensi core CodeIgniter 3.
 */
class MY_Controller extends CI_Controller
{
    /**
     * Data yang diteruskan ke view layout
     * @var array
     */
    protected $data = array();

    public function __construct()
    {
        parent::__construct();

        // Inisialisasi data dasar aplikasi
        $this->data['nama_aplikasi'] = 'LIMSKU';
        $this->data['judul_panjang'] = 'Laboratory Information Management System';
        $this->data['instansi']      = 'BBPOM di Bandar Lampung';
        $this->data['halaman_aktif'] = '';
        $this->data['judul_halaman'] = '';
        $this->data['breadcrumbs']   = array();
        
        // Load model audit
        $this->load->model('Model_Audit');
        $this->load->model('Model_Hak_Akses');
    }

    /**
     * Memeriksa apakah pengguna sudah login
     * 
     * @return void
     */
    protected function cek_login()
    {
        if (!$this->session->userdata('logged_in')) {
            $this->session->set_flashdata('pesan_gagal', 'Silakan masuk terlebih dahulu untuk mengakses sistem.');
            redirect('masuk');
        }
    }

    /**
     * Memeriksa hak akses (permission) pengguna
     * 
     * @param string $nama_permission Nama permission yang dibutuhkan
     * @return void
     */
    protected function cek_hak_akses($nama_permission)
    {
        $this->cek_login();

        $role_id = $this->session->userdata('role_id');
        if (!$role_id || !$this->Model_Hak_Akses->memiliki_akses($role_id, $nama_permission)) {
            if ($this->input->is_ajax_request()) {
                $this->output
                    ->set_status_header(403)
                    ->set_content_type('application/json')
                    ->set_output(json_encode(array(
                        'status' => 'error',
                        'message' => 'Anda tidak memiliki hak akses untuk melakukan tindakan ini.'
                    )));
                exit;
            } else {
                $this->session->set_flashdata('pesan_gagal', 'Anda tidak memiliki hak akses untuk mengakses halaman tersebut.');
                redirect('dasbor');
            }
        }
    }

    /**
     * Helper untuk mencatat log aktivitas pengguna
     * 
     * @param string $aksi Jenis aksi (misal: 'CREATE', 'UPDATE', 'DELETE')
     * @param string $modul Nama modul
     * @param string|array|null $detail Detail perubahan atau informasi aktivitas
     * @return void
     */
    protected function catat_audit($aksi, $modul, $detail = NULL)
    {
        $user_id  = $this->session->userdata('user_id');
        $username = $this->session->userdata('username');

        $this->Model_Audit->catat_log($user_id, $username, $aksi, $modul, $detail);
    }

    /**
     * Helper untuk memuat tampilan master layout
     * 
     * @param string $konten_view Nama view konten yang akan dirender
     * @param array $data_tambahan Data tambahan untuk view
     * @return void
     */
    protected function muat_tampilan($konten_view, $data_tambahan = array())
    {
        $data_gabungan = array_merge($this->data, $data_tambahan);
        $data_gabungan['konten_utama'] = $this->load->view($konten_view, $data_gabungan, TRUE);
        $this->load->view('layouts/main', $data_gabungan);
    }
}
