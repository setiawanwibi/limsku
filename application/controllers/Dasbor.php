<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Dasbor
 * 
 * Halaman utama (Dashboard) operasional LIMSKU untuk SEMUA role pengguna.
 * Menyediakan overview operasional laboratorium secara real-time dan endpoint AJAX polling.
 */
class Dasbor extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->cek_login();
        $this->load->model('Model_Dasbor');
    }

    /**
     * Tampilan utama dasbor
     * 
     * @return void
     */
    public function index()
    {
        $this->data['halaman_aktif'] = 'dasbor';
        $this->data['judul_halaman'] = 'Dasbor Utama';
        $this->data['breadcrumbs']   = array();

        // Menentukan greeting dinamis berdasarkan waktu server
        $jam = (int) date('H');
        if ($jam >= 5 && $jam < 11) {
            $greeting = 'Selamat pagi';
        } elseif ($jam >= 11 && $jam < 15) {
            $greeting = 'Selamat siang';
        } elseif ($jam >= 15 && $jam < 18) {
            $greeting = 'Selamat sore';
        } else {
            $greeting = 'Selamat malam';
        }

        // Ambil nama user dari session
        $nama_lengkap = $this->session->userdata('nama_lengkap');
        // Ambil nama depan untuk sapaan
        $parts = explode(' ', trim($nama_lengkap ?: 'User'));
        $nama_panggilan = $parts[0];

        $this->data['greeting']       = $greeting;
        $this->data['nama_panggilan'] = $nama_panggilan;
        $this->data['nama_lengkap']   = $nama_lengkap;
        $this->data['tanggal_hari_ini'] = date('d M Y');

        // Mengambil seluruh ringkasan statistik
        $this->data['ringkasan'] = $this->Model_Dasbor->ambil_ringkasan();

        $this->muat_tampilan('dasbor/index');
    }

    /**
     * Endpoint AJAX untuk Polling & Auto-Refresh Real-Time
     * 
     * @return void JSON
     */
    public function api_ringkasan()
    {
        // Pastikan hanya authenticated user yang bisa akses
        $this->cek_login();

        $ringkasan = $this->Model_Dasbor->ambil_ringkasan();

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array(
                'status'    => 'success',
                'timestamp' => time(),
                'data'      => $ringkasan
            )));
    }
}
