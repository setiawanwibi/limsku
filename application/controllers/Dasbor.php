<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Dasbor
 * 
 * Halaman utama (Dashboard) pengguna setelah masuk ke sistem.
 */
class Dasbor extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->cek_login();
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
        $this->data['breadcrumbs']   = array(
            array('label' => 'Dasbor', 'url' => site_url('dasbor'))
        );

        $this->muat_tampilan('dasbor/index');
    }
}
