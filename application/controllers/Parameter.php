<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Parameter
 * 
 * Menangani manajemen Master Data Parameter Pengujian.
 */
class Parameter extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_Parameter');
    }

    /**
     * Daftar parameter pengujian
     */
    public function index()
    {
        $this->cek_hak_akses('parameter_view');

        $this->data['halaman_aktif'] = 'parameter';
        $this->data['judul_halaman'] = 'Master Parameter Pengujian';
        $this->data['breadcrumbs']   = array(
            array('label' => 'Master Data', 'url' => '#'),
            array('label' => 'Parameter', 'url' => site_url('parameter'))
        );

        $this->data['daftar_parameter'] = $this->Model_Parameter->ambil_semua();

        $this->muat_tampilan('parameter/index');
    }

    /**
     * Form dan proses tambah parameter baru
     */
    public function tambah()
    {
        $this->cek_hak_akses('parameter_create');

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('kode_parameter', 'Kode Parameter', 'required|trim|is_unique[parameters.kode_parameter]', array(
                'required'  => '%s wajib diisi.',
                'is_unique' => '%s sudah ada.'
            ));
            $this->form_validation->set_rules('nama_parameter', 'Nama Parameter', 'required|trim', array(
                'required' => '%s wajib diisi.'
            ));

            if ($this->form_validation->run() === TRUE) {
                $data_simpan = array(
                    'kode_parameter' => strtoupper($this->input->post('kode_parameter', TRUE)),
                    'nama_parameter' => $this->input->post('nama_parameter', TRUE),
                    'satuan'         => $this->input->post('satuan', TRUE) ?: NULL,
                    'baku_mutu'      => $this->input->post('baku_mutu', TRUE) ?: NULL,
                    'kategori'       => $this->input->post('kategori', TRUE) ?: 'Kimia',
                    'status'         => $this->input->post('status', TRUE) ?: 'Aktif'
                );

                $param_id = $this->Model_Parameter->tambah($data_simpan);

                if ($param_id) {
                    $this->catat_audit('CREATE', 'Parameter', array(
                        'parameter_id'   => $param_id,
                        'kode_parameter' => $data_simpan['kode_parameter'],
                        'nama_parameter' => $data_simpan['nama_parameter']
                    ));

                    $this->session->set_flashdata('pesan_sukses', 'Parameter pengujian baru berhasil ditambahkan.');
                    redirect('parameter');
                } else {
                    $this->session->set_flashdata('pesan_gagal', 'Gagal menambahkan parameter pengujian.');
                }
            }
        }

        $this->data['halaman_aktif'] = 'parameter';
        $this->data['judul_halaman'] = 'Tambah Parameter Pengujian';
        $this->data['breadcrumbs']   = array(
            array('label' => 'Parameter', 'url' => site_url('parameter')),
            array('label' => 'Tambah', 'url' => '#')
        );

        $this->muat_tampilan('parameter/tambah');
    }

    /**
     * Form dan proses edit parameter
     * 
     * @param int $id
     */
    public function edit($id = NULL)
    {
        $this->cek_hak_akses('parameter_edit');

        $param = $this->Model_Parameter->ambil_by_id($id);
        if (!$param) {
            show_404();
        }

        if ($this->input->method() === 'post') {
            $is_unique = ($this->input->post('kode_parameter') !== $param['kode_parameter']) ? '|is_unique[parameters.kode_parameter]' : '';

            $this->form_validation->set_rules('kode_parameter', 'Kode Parameter', 'required|trim' . $is_unique, array(
                'required'  => '%s wajib diisi.',
                'is_unique' => '%s sudah ada.'
            ));
            $this->form_validation->set_rules('nama_parameter', 'Nama Parameter', 'required|trim', array(
                'required' => '%s wajib diisi.'
            ));

            if ($this->form_validation->run() === TRUE) {
                $data_update = array(
                    'kode_parameter' => strtoupper($this->input->post('kode_parameter', TRUE)),
                    'nama_parameter' => $this->input->post('nama_parameter', TRUE),
                    'satuan'         => $this->input->post('satuan', TRUE) ?: NULL,
                    'baku_mutu'      => $this->input->post('baku_mutu', TRUE) ?: NULL,
                    'kategori'       => $this->input->post('kategori', TRUE) ?: 'Kimia',
                    'status'         => $this->input->post('status', TRUE) ?: 'Aktif'
                );

                if ($this->Model_Parameter->ubah($id, $data_update)) {
                    $this->catat_audit('UPDATE', 'Parameter', array(
                        'parameter_id'   => $id,
                        'kode_parameter' => $data_update['kode_parameter'],
                        'nama_parameter' => $data_update['nama_parameter']
                    ));

                    $this->session->set_flashdata('pesan_sukses', 'Data parameter pengujian berhasil diperbarui.');
                    redirect('parameter');
                } else {
                    $this->session->set_flashdata('pesan_gagal', 'Gagal memperbarui data parameter pengujian.');
                }
            }
        }

        $this->data['halaman_aktif'] = 'parameter';
        $this->data['judul_halaman'] = 'Edit Parameter Pengujian';
        $this->data['breadcrumbs']   = array(
            array('label' => 'Parameter', 'url' => site_url('parameter')),
            array('label' => 'Edit', 'url' => '#')
        );
        $this->data['parameter']     = $param;

        $this->muat_tampilan('parameter/edit');
    }

    /**
     * Ubah status parameter (Aktif/Nonaktif)
     * 
     * @param int $id
     */
    public function status($id = NULL)
    {
        $this->cek_hak_akses('parameter_edit');

        $param = $this->Model_Parameter->ambil_by_id($id);
        if (!$param) {
            show_404();
        }

        $status_baru = ($param['status'] === 'Aktif') ? 'Nonaktif' : 'Aktif';

        if ($this->Model_Parameter->ubah_status($id, $status_baru)) {
            $this->catat_audit('UPDATE_STATUS', 'Parameter', array(
                'parameter_id'   => $id,
                'kode_parameter' => $param['kode_parameter'],
                'status_baru'    => $status_baru
            ));

            $this->session->set_flashdata('pesan_sukses', 'Status parameter ' . $param['kode_parameter'] . ' diubah menjadi ' . $status_baru . '.');
        } else {
            $this->session->set_flashdata('pesan_gagal', 'Gagal mengubah status parameter.');
        }

        redirect('parameter');
    }

    /**
     * Hapus parameter
     * 
     * @param int $id
     */
    public function hapus($id = NULL)
    {
        $this->cek_hak_akses('parameter_delete');

        $param = $this->Model_Parameter->ambil_by_id($id);
        if (!$param) {
            show_404();
        }

        if ($this->Model_Parameter->hapus($id)) {
            $this->catat_audit('DELETE', 'Parameter', array(
                'parameter_id'   => $id,
                'kode_parameter' => $param['kode_parameter']
            ));

            $this->session->set_flashdata('pesan_sukses', 'Parameter pengujian berhasil dihapus.');
        } else {
            $this->session->set_flashdata('pesan_gagal', 'Gagal menghapus parameter pengujian.');
        }

        redirect('parameter');
    }
}
