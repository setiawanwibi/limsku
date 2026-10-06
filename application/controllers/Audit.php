<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Audit (Audit Trail)
 * 
 * Menampilkan riwayat log aktivitas penting pengguna dan perubahan data.
 */
class Audit extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Tampilan daftar audit trail
     */
    public function index()
    {
        $this->cek_hak_akses('audit_view');

        $this->data['halaman_aktif'] = 'audit';
        $this->data['judul_halaman'] = 'Audit Trail Sistem';
        $this->data['breadcrumbs']   = array(
            array('label' => 'Audit Trail', 'url' => site_url('audit'))
        );

        $this->data['daftar_log'] = $this->Model_Audit->ambil_semua(500, 0);

        $this->muat_tampilan('audit/index');
    }
}
