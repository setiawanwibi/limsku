<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Laboratorium
 * 
 * Menangani manajemen Master Data Laboratorium / Unit Pelaksana.
 */
class Laboratorium extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_Laboratorium');
    }

    /**
     * Daftar laboratorium
     */
    public function index()
    {
        $this->cek_hak_akses('laboratorium_view');

        $this->data['halaman_aktif'] = 'laboratorium';
        $this->data['judul_halaman'] = 'Manajemen Laboratorium';
        $daftar_lab = $this->Model_Laboratorium->ambil_semua();
        foreach ($daftar_lab as &$lab) {
            $lab['total_pengguna'] = $this->Model_Laboratorium->hitung_pengguna($lab['id']);
        }

        $this->data['daftar_laboratorium'] = $daftar_lab;

        $this->muat_tampilan('laboratorium/index');
    }

    /**
     * Form dan proses tambah laboratorium baru
     */
    public function tambah()
    {
        $this->cek_hak_akses('laboratorium_create');

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('kode_lab', 'Kode Laboratorium', 'required|trim|is_unique[laboratories.kode_lab]', array(
                'required'  => '%s wajib diisi.',
                'is_unique' => '%s sudah digunakan.'
            ));
            $this->form_validation->set_rules('nama_lab', 'Nama Laboratorium', 'required|trim', array(
                'required' => '%s wajib diisi.'
            ));

            if ($this->form_validation->run() === TRUE) {
                $data_simpan = array(
                    'kode_lab'  => strtoupper($this->input->post('kode_lab', TRUE)),
                    'nama_lab'  => $this->input->post('nama_lab', TRUE),
                    'deskripsi' => $this->input->post('deskripsi', TRUE) ?: NULL,
                    'status'    => $this->input->post('status', TRUE) ?: 'Aktif'
                );

                $lab_id = $this->Model_Laboratorium->tambah($data_simpan);

                if ($lab_id) {
                    $this->catat_audit('CREATE', 'Laboratorium', array(
                        'lab_id'   => $lab_id,
                        'kode_lab' => $data_simpan['kode_lab'],
                        'nama_lab' => $data_simpan['nama_lab']
                    ));

                    $this->session->set_flashdata('pesan_sukses', 'Laboratorium baru berhasil ditambahkan.');
                    redirect('laboratorium');
                } else {
                    $this->session->set_flashdata('pesan_gagal', 'Gagal menambahkan laboratorium baru.');
                }
            }
        }

        $this->data['halaman_aktif'] = 'laboratorium';
        $this->data['judul_halaman'] = 'Tambah Laboratorium';

        $this->muat_tampilan('laboratorium/tambah');
    }

    /**
     * Form dan proses ubah data laboratorium
     * 
     * @param int $id
     */
    public function edit($id = NULL)
    {
        $this->cek_hak_akses('laboratorium_edit');

        $lab = $this->Model_Laboratorium->ambil_by_id($id);
        if (!$lab) {
            show_404();
        }

        if ($this->input->method() === 'post') {
            $is_unique_kode = ($this->input->post('kode_lab') !== $lab['kode_lab']) ? '|is_unique[laboratories.kode_lab]' : '';

            $this->form_validation->set_rules('kode_lab', 'Kode Laboratorium', 'required|trim' . $is_unique_kode, array(
                'required'  => '%s wajib diisi.',
                'is_unique' => '%s sudah digunakan.'
            ));
            $this->form_validation->set_rules('nama_lab', 'Nama Laboratorium', 'required|trim', array(
                'required' => '%s wajib diisi.'
            ));

            if ($this->form_validation->run() === TRUE) {
                $data_update = array(
                    'kode_lab'  => strtoupper($this->input->post('kode_lab', TRUE)),
                    'nama_lab'  => $this->input->post('nama_lab', TRUE),
                    'deskripsi' => $this->input->post('deskripsi', TRUE) ?: NULL,
                    'status'    => $this->input->post('status', TRUE) ?: 'Aktif'
                );

                if ($this->Model_Laboratorium->ubah($id, $data_update)) {
                    $this->catat_audit('UPDATE', 'Laboratorium', array(
                        'lab_id'   => $id,
                        'kode_lab' => $data_update['kode_lab'],
                        'nama_lab' => $data_update['nama_lab']
                    ));

                    $this->session->set_flashdata('pesan_sukses', 'Data laboratorium berhasil diperbarui.');
                    redirect('laboratorium');
                } else {
                    $this->session->set_flashdata('pesan_gagal', 'Gagal memperbarui data laboratorium.');
                }
            }
        }

        $this->data['halaman_aktif'] = 'laboratorium';
        $this->data['judul_halaman'] = 'Edit Laboratorium';
        $this->data['breadcrumbs']   = array(
            array('label' => 'Laboratorium', 'url' => site_url('laboratorium')),
            array('label' => 'Edit', 'url' => '#')
        );
        $this->data['lab']           = $lab;

        $this->muat_tampilan('laboratorium/edit');
    }

    /**
     * Ubah status laboratorium (Aktif/Nonaktif)
     * 
     * @param int $id
     */
    public function ubah_status($id = NULL)
    {
        $this->cek_hak_akses('laboratorium_edit');

        $lab = $this->Model_Laboratorium->ambil_by_id($id);
        if (!$lab) {
            show_404();
        }

        $status_baru = ($lab['status'] === 'Aktif') ? 'Nonaktif' : 'Aktif';

        if ($this->Model_Laboratorium->ubah($id, array('status' => $status_baru))) {
            $this->catat_audit('UPDATE_STATUS', 'Laboratorium', array(
                'lab_id'      => $id,
                'nama_lab'    => $lab['nama_lab'],
                'status_lama' => $lab['status'],
                'status_baru' => $status_baru
            ));

            $this->session->set_flashdata('pesan_sukses', 'Status laboratorium "' . $lab['nama_lab'] . '" diubah menjadi ' . $status_baru . '.');
        } else {
            $this->session->set_flashdata('pesan_gagal', 'Gagal mengubah status laboratorium.');
        }

        redirect('laboratorium');
    }

    /**
     * Hapus laboratorium
     * 
     * @param int $id
     */
    public function hapus($id = NULL)
    {
        $this->cek_hak_akses('laboratorium_delete');

        $lab = $this->Model_Laboratorium->ambil_by_id($id);
        if (!$lab) {
            show_404();
        }

        $total_pengguna = $this->Model_Laboratorium->hitung_pengguna($id);
        if ($total_pengguna > 0) {
            $this->session->set_flashdata('pesan_gagal', 'Laboratorium "' . $lab['nama_lab'] . '" sedang digunakan oleh ' . $total_pengguna . ' pengguna dan tidak dapat dihapus.');
            redirect('laboratorium');
        }

        if ($this->Model_Laboratorium->hapus($id)) {
            $this->catat_audit('DELETE', 'Laboratorium', array(
                'lab_id'   => $id,
                'nama_lab' => $lab['nama_lab']
            ));

            $this->session->set_flashdata('pesan_sukses', 'Laboratorium "' . $lab['nama_lab'] . '" berhasil dihapus.');
        } else {
            $this->session->set_flashdata('pesan_gagal', 'Gagal menghapus laboratorium.');
        }

        redirect('laboratorium');
    }
}
