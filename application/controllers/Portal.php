<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Portal Pemilih Modul Laboratorium
 * 
 * Menangani pemilihan ruang kerja (workspace) komoditi:
 * 1. Laboratorium Obat (Aktif)
 * 2. Laboratorium Pangan & Air (Segera Hadir)
 * 3. Laboratorium Obat Bahan Alam / Herbal (Segera Hadir)
 * 4. Laboratorium Kosmetik (Segera Hadir)
 * 5. Monitoring & Rekap Eksekutif (Aktif)
 * 6. Admin TI & Pengaturan Sistem (Aktif)
 */
class Portal extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->cek_login();
    }

    /**
     * Halaman Portal Utama Pemilih Modul
     */
    public function index()
    {
        $this->data['judul_halaman'] = 'Portal Modul Laboratorium';
        $this->data['halaman_aktif'] = 'portal';

        $role_id = (int)$this->session->userdata('role_id');
        $komoditi_akses = $this->session->userdata('komoditi_akses') ?: array();
        if (!is_array($komoditi_akses)) {
            $komoditi_akses = array($komoditi_akses);
        }

        $is_all = in_array('all', $komoditi_akses) || in_array('all_sampel', $komoditi_akses) || $role_id === 1 || $role_id === 2;

        // Definisi Modul-Modul LIMSKU
        $this->data['daftar_modul'] = array(
            array(
                'id'          => 'obat',
                'kode'        => 'LAB-OBAT',
                'nama'        => 'Laboratorium Obat',
                'subjudul'    => 'Produk Terapetik, Bahan Baku & Narkotika',
                'deskripsi'   => 'Alur pengujian lengkap: registrasi sampel, antrean, LCP kimia (FI VI), LCP mikrobiologi, verifikasi penyelia, dan approval LHU.',
                'icon'        => 'bi-capsule',
                'color'       => '#0d838a',
                'bg_gradient' => 'linear-gradient(135deg, #0d838a 0%, #065b60 100%)',
                'status'      => 'ready',
                'url'         => site_url('portal/pilih/obat'),
                'can_access'  => $is_all || in_array('obat', $komoditi_akses)
            ),
            array(
                'id'          => 'pangan',
                'kode'        => 'LAB-PANGAN',
                'nama'        => 'Laboratorium Pangan & Air',
                'subjudul'    => 'Pangan Olahan, Minuman, AMDK & BTP',
                'deskripsi'   => 'Pengujian mikrobiologi SNI (ALT, Coliform, Salmonella), Bahan Tambahan Pangan (BTP: Pemanis, Pengawet), dan cemaran pangan.',
                'icon'        => 'bi-cup-straw',
                'color'       => '#27ae60',
                'bg_gradient' => 'linear-gradient(135deg, #27ae60 0%, #1e824c 100%)',
                'status'      => 'coming_soon',
                'url'         => '#',
                'can_access'  => $is_all || in_array('pangan', $komoditi_akses)
            ),
            array(
                'id'          => 'herbal',
                'kode'        => 'LAB-HERBAL',
                'nama'        => 'Laboratorium Obat Bahan Alam',
                'subjudul'    => 'Jamu, OHT, Fitofarmaka & Suplemen',
                'deskripsi'   => 'Skrining Bahan Kimia Obat (BKO kualitatif KLT/LC-MS), kadar abu, susut pengeringan, aflatoksin, dan cemaran mikroba FHI.',
                'icon'        => 'bi-flower1',
                'color'       => '#e67e22',
                'bg_gradient' => 'linear-gradient(135deg, #e67e22 0%, #d35400 100%)',
                'status'      => 'coming_soon',
                'url'         => '#',
                'can_access'  => $is_all || in_array('herbal', $komoditi_akses)
            ),
            array(
                'id'          => 'kosmetik',
                'kode'        => 'LAB-KOSMETIK',
                'nama'        => 'Laboratorium Kosmetik',
                'subjudul'    => 'Produk Perawatan Kulit, Rambut & Riasan',
                'deskripsi'   => 'Identifikasi zat warna dilarang (K3, Rhodamin B), uji logam berat (Merkuri, Timbal, Arsen), hidrokuinon, dan asam retinoat.',
                'icon'        => 'bi-brush',
                'color'       => '#9b59b6',
                'bg_gradient' => 'linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%)',
                'status'      => 'coming_soon',
                'url'         => '#',
                'can_access'  => $is_all || in_array('kosmetik', $komoditi_akses)
            ),
            array(
                'id'          => 'rekap',
                'kode'        => 'MONITORING',
                'nama'        => 'Monitoring & Rekapitulasi',
                'subjudul'    => 'Penyelia, Manajer Teknis & Eksekutif',
                'deskripsi'   => 'Tinjauan matriks sampel balai real-time, statistik lead time, tracking status alur, filter komoditi, dan ekspor resmi PDF/Excel.',
                'icon'        => 'bi-speedometer2',
                'color'       => '#2980b9',
                'bg_gradient' => 'linear-gradient(135deg, #2980b9 0%, #1f618d 100%)',
                'status'      => 'ready',
                'url'         => site_url('laporan'),
                'can_access'  => $is_all || $role_id === 1 || $role_id === 2 || $role_id === 3
            ),
            array(
                'id'          => 'admin',
                'kode'        => 'ADMIN-TI',
                'nama'        => 'Admin TI & Manajemen Sistem',
                'subjudul'    => 'Pengaturan Pengguna, Hak Akses & Audit',
                'deskripsi'   => 'Manajemen personil, penugasan komoditi, konfigurasi RBAC, master reagen, instrumen laboratorium, dan audit trail aktivitas.',
                'icon'        => 'bi-shield-lock-fill',
                'color'       => '#34495e',
                'bg_gradient' => 'linear-gradient(135deg, #34495e 0%, #2c3e50 100%)',
                'status'      => 'ready',
                'url'         => site_url('pengguna'),
                'can_access'  => $role_id === 1 || in_array('admin', $komoditi_akses)
            )
        );

        $this->load->view('portal/index', $this->data);
    }

    /**
     * Set komoditi aktif ke sesi dan alihkan ke dasbor
     */
    public function pilih($komoditi = 'obat')
    {
        $role_id = (int)$this->session->userdata('role_id');
        $komoditi_akses = $this->session->userdata('komoditi_akses') ?: array();
        if (!is_array($komoditi_akses)) {
            $komoditi_akses = array($komoditi_akses);
        }

        $is_all = in_array('all', $komoditi_akses) || in_array('all_sampel', $komoditi_akses) || $role_id === 1 || $role_id === 2;

        if (!$is_all && !in_array($komoditi, $komoditi_akses)) {
            $this->session->set_flashdata('pesan_gagal', 'Anda tidak memiliki penugasan pada Laboratorium ' . ucfirst($komoditi) . '.');
            redirect('portal');
            return;
        }

        $this->session->set_userdata('komoditi_aktif', $komoditi);
        $this->session->set_flashdata('pesan_sukses', 'Workspace aktif: Laboratorium ' . ucfirst($komoditi));
        redirect('dasbor');
    }
}
