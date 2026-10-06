<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Metode
 * 
 * Menangani manajemen Master Data Metode Pengujian.
 */
class Metode extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_Metode');
    }

    /**
     * Daftar metode pengujian
     */
    public function index()
    {
        $this->cek_hak_akses('metode_view');

        $this->data['halaman_aktif'] = 'metode';
        $this->data['judul_halaman'] = 'Master Metode Pengujian';
        $this->data['breadcrumbs']   = array(
            array('label' => 'Master Data', 'url' => '#'),
            array('label' => 'Metode', 'url' => site_url('metode'))
        );

        $this->data['daftar_metode'] = $this->Model_Metode->ambil_semua();

        $this->muat_tampilan('metode/index');
    }

    /**
     * Form dan proses tambah metode baru
     */
    public function tambah()
    {
        $this->cek_hak_akses('metode_create');

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('kode_metode', 'Kode Metode', 'required|trim|is_unique[methods.kode_metode]', array(
                'required'  => '%s wajib diisi.',
                'is_unique' => '%s sudah ada.'
            ));
            $this->form_validation->set_rules('nama_metode', 'Nama Metode', 'required|trim', array(
                'required' => '%s wajib diisi.'
            ));

            if ($this->form_validation->run() === TRUE) {
                $data_simpan = array(
                    'kode_metode' => strtoupper($this->input->post('kode_metode', TRUE)),
                    'nama_metode' => $this->input->post('nama_metode', TRUE),
                    'kategori'    => $this->input->post('kategori', TRUE) ?: 'Kimia',
                    'versi'       => $this->input->post('versi', TRUE) ?: NULL,
                    'keterangan'  => $this->input->post('keterangan', TRUE) ?: NULL,
                    'status'      => $this->input->post('status', TRUE) ?: 'Aktif'
                );

                $metode_id = $this->Model_Metode->tambah($data_simpan);

                if ($metode_id) {
                    $this->catat_audit('CREATE', 'Metode', array(
                        'metode_id'   => $metode_id,
                        'kode_metode' => $data_simpan['kode_metode'],
                        'nama_metode' => $data_simpan['nama_metode']
                    ));

                    $this->session->set_flashdata('pesan_sukses', 'Metode pengujian baru berhasil ditambahkan.');
                    redirect('metode');
                } else {
                    $this->session->set_flashdata('pesan_gagal', 'Gagal menambahkan metode pengujian.');
                }
            }
        }

        $this->data['halaman_aktif'] = 'metode';
        $this->data['judul_halaman'] = 'Tambah Metode Pengujian';
        $this->data['breadcrumbs']   = array(
            array('label' => 'Metode', 'url' => site_url('metode')),
            array('label' => 'Tambah', 'url' => '#')
        );

        $this->muat_tampilan('metode/tambah');
    }

    /**
     * Form dan proses edit metode
     * 
     * @param int $id
     */
    public function edit($id = NULL)
    {
        $this->cek_hak_akses('metode_edit');

        $metode = $this->Model_Metode->ambil_by_id($id);
        if (!$metode) {
            show_404();
        }

        if ($this->input->method() === 'post') {
            $is_unique = ($this->input->post('kode_metode') !== $metode['kode_metode']) ? '|is_unique[methods.kode_metode]' : '';

            $this->form_validation->set_rules('kode_metode', 'Kode Metode', 'required|trim' . $is_unique, array(
                'required'  => '%s wajib diisi.',
                'is_unique' => '%s sudah ada.'
            ));
            $this->form_validation->set_rules('nama_metode', 'Nama Metode', 'required|trim', array(
                'required' => '%s wajib diisi.'
            ));

            if ($this->form_validation->run() === TRUE) {
                $data_update = array(
                    'kode_metode' => strtoupper($this->input->post('kode_metode', TRUE)),
                    'nama_metode' => $this->input->post('nama_metode', TRUE),
                    'kategori'    => $this->input->post('kategori', TRUE) ?: 'Kimia',
                    'versi'       => $this->input->post('versi', TRUE) ?: NULL,
                    'keterangan'  => $this->input->post('keterangan', TRUE) ?: NULL,
                    'status'      => $this->input->post('status', TRUE) ?: 'Aktif'
                );

                if ($this->Model_Metode->ubah($id, $data_update)) {
                    $this->catat_audit('UPDATE', 'Metode', array(
                        'metode_id'   => $id,
                        'kode_metode' => $data_update['kode_metode'],
                        'nama_metode' => $data_update['nama_metode']
                    ));

                    $this->session->set_flashdata('pesan_sukses', 'Data metode pengujian berhasil diperbarui.');
                    redirect('metode');
                } else {
                    $this->session->set_flashdata('pesan_gagal', 'Gagal memperbarui data metode pengujian.');
                }
            }
        }

        $this->data['halaman_aktif'] = 'metode';
        $this->data['judul_halaman'] = 'Edit Metode Pengujian';
        $this->data['breadcrumbs']   = array(
            array('label' => 'Metode', 'url' => site_url('metode')),
            array('label' => 'Edit', 'url' => '#')
        );
        $this->data['metode']        = $metode;

        $this->muat_tampilan('metode/edit');
    }

    /**
     * Ubah status metode (Aktif/Nonaktif)
     * 
     * @param int $id
     */
    public function status($id = NULL)
    {
        $this->cek_hak_akses('metode_edit');

        $metode = $this->Model_Metode->ambil_by_id($id);
        if (!$metode) {
            show_404();
        }

        $status_baru = ($metode['status'] === 'Aktif') ? 'Nonaktif' : 'Aktif';

        if ($this->Model_Metode->ubah_status($id, $status_baru)) {
            $this->catat_audit('UPDATE_STATUS', 'Metode', array(
                'metode_id'   => $id,
                'kode_metode' => $metode['kode_metode'],
                'status_baru' => $status_baru
            ));

            $this->session->set_flashdata('pesan_sukses', 'Status metode ' . $metode['kode_metode'] . ' diubah menjadi ' . $status_baru . '.');
        } else {
            $this->session->set_flashdata('pesan_gagal', 'Gagal mengubah status metode.');
        }

        redirect('metode');
    }

    /**
     * Hapus metode
     * 
     * @param int $id
     */
    public function hapus($id = NULL)
    {
        $this->cek_hak_akses('metode_delete');

        $metode = $this->Model_Metode->ambil_by_id($id);
        if (!$metode) {
            show_404();
        }

        if ($this->Model_Metode->hapus($id)) {
            $this->catat_audit('DELETE', 'Metode', array(
                'metode_id'   => $id,
                'kode_metode' => $metode['kode_metode']
            ));

            $this->session->set_flashdata('pesan_sukses', 'Metode pengujian berhasil dihapus.');
        } else {
            $this->session->set_flashdata('pesan_gagal', 'Gagal menghapus metode pengujian.');
        }

        redirect('metode');
    }
}
