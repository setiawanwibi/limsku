<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Template Form Pengujian
 * 
 * Menangani manajemen Master Data Template Form Pengujian Dinamis (12 Kimia + 5 Mikrobiologi).
 */
class Template extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_Template');
        $this->load->model('Model_Metode');
        $this->load->model('Model_Parameter');
    }

    /**
     * Daftar template form pengujian
     */
    public function index()
    {
        $this->cek_hak_akses('template_view');

        $this->data['halaman_aktif'] = 'template';
        $this->data['judul_halaman'] = 'Master Template Form Pengujian (17 Form)';
        $this->data['daftar_template'] = $this->Model_Template->ambil_semua();

        $this->muat_tampilan('template/index');
    }

    /**
     * Form dan proses tambah template form baru
     */
    public function tambah()
    {
        $this->cek_hak_akses('template_create');

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('kode_template', 'Kode Template', 'required|trim|is_unique[form_templates.kode_template]', array(
                'required'  => '%s wajib diisi.',
                'is_unique' => '%s sudah ada.'
            ));
            $this->form_validation->set_rules('nama_template', 'Nama Template', 'required|trim', array(
                'required' => '%s wajib diisi.'
            ));
            $this->form_validation->set_rules('skema_form', 'Skema Form (JSON)', 'required', array(
                'required' => '%s wajib diisi.'
            ));

            if ($this->form_validation->run() === TRUE) {
                $skema_json = $this->input->post('skema_form');
                if (json_decode($skema_json) === NULL) {
                    $this->session->set_flashdata('pesan_gagal', 'Format JSON Skema Form tidak valid.');
                    redirect('template/tambah');
                }

                $data_simpan = array(
                    'kode_template' => strtoupper($this->input->post('kode_template', TRUE)),
                    'nama_template' => $this->input->post('nama_template', TRUE),
                    'kategori'      => $this->input->post('kategori', TRUE) ?: 'Kimia',
                    'method_id'     => $this->input->post('method_id', TRUE) ?: NULL,
                    'parameter_id'  => $this->input->post('parameter_id', TRUE) ?: NULL,
                    'skema_form'    => $skema_json,
                    'status'        => $this->input->post('status', TRUE) ?: 'Aktif'
                );

                $template_id = $this->Model_Template->tambah($data_simpan);

                if ($template_id) {
                    $this->catat_audit('CREATE', 'Template', array(
                        'template_id'   => $template_id,
                        'kode_template' => $data_simpan['kode_template'],
                        'nama_template' => $data_simpan['nama_template']
                    ));

                    $this->session->set_flashdata('pesan_sukses', 'Template form pengujian baru berhasil ditambahkan.');
                    redirect('template');
                } else {
                    $this->session->set_flashdata('pesan_gagal', 'Gagal menambahkan template form pengujian.');
                }
            }
        }

        $this->data['halaman_aktif']    = 'template';
        $this->data['judul_halaman']    = 'Tambah Template Form Pengujian';
        $this->data['daftar_metode']    = $this->Model_Metode->ambil_aktif();
        $this->data['daftar_parameter'] = $this->Model_Parameter->ambil_aktif();

        $this->muat_tampilan('template/tambah');
    }

    /**
     * Form dan proses edit template form
     * 
     * @param int $id
     */
    public function edit($id = NULL)
    {
        $this->cek_hak_akses('template_edit');

        $tpl = $this->Model_Template->ambil_by_id($id);
        if (!$tpl) {
            show_404();
        }

        if ($this->input->method() === 'post') {
            $is_unique = ($this->input->post('kode_template') !== $tpl['kode_template']) ? '|is_unique[form_templates.kode_template]' : '';

            $this->form_validation->set_rules('kode_template', 'Kode Template', 'required|trim' . $is_unique, array(
                'required'  => '%s wajib diisi.',
                'is_unique' => '%s sudah ada.'
            ));
            $this->form_validation->set_rules('nama_template', 'Nama Template', 'required|trim', array(
                'required' => '%s wajib diisi.'
            ));
            $this->form_validation->set_rules('skema_form', 'Skema Form (JSON)', 'required');

            if ($this->form_validation->run() === TRUE) {
                $skema_json = $this->input->post('skema_form');
                if (json_decode($skema_json) === NULL) {
                    $this->session->set_flashdata('pesan_gagal', 'Format JSON Skema Form tidak valid.');
                    redirect('template/edit/' . $id);
                }

                $data_update = array(
                    'kode_template' => strtoupper($this->input->post('kode_template', TRUE)),
                    'nama_template' => $this->input->post('nama_template', TRUE),
                    'kategori'      => $this->input->post('kategori', TRUE) ?: 'Kimia',
                    'method_id'     => $this->input->post('method_id', TRUE) ?: NULL,
                    'parameter_id'  => $this->input->post('parameter_id', TRUE) ?: NULL,
                    'skema_form'    => $skema_json,
                    'status'        => $this->input->post('status', TRUE) ?: 'Aktif'
                );

                if ($this->Model_Template->ubah($id, $data_update)) {
                    $this->catat_audit('UPDATE', 'Template', array(
                        'template_id'   => $id,
                        'kode_template' => $data_update['kode_template'],
                        'nama_template' => $data_update['nama_template']
                    ));

                    $this->session->set_flashdata('pesan_sukses', 'Data template form pengujian berhasil diperbarui.');
                    redirect('template');
                } else {
                    $this->session->set_flashdata('pesan_gagal', 'Gagal memperbarui data template form.');
                }
            }
        }

        $this->data['halaman_aktif']    = 'template';
        $this->data['judul_halaman']    = 'Edit Template Form Pengujian';
        $this->data['template']         = $tpl;
        $this->data['daftar_metode']    = $this->Model_Metode->ambil_aktif();
        $this->data['daftar_parameter'] = $this->Model_Parameter->ambil_aktif();

        $this->muat_tampilan('template/edit');
    }

    /**
     * Ubah status template (Aktif/Nonaktif)
     * 
     * @param int $id
     */
    public function status($id = NULL)
    {
        $this->cek_hak_akses('template_edit');

        $tpl = $this->Model_Template->ambil_by_id($id);
        if (!$tpl) {
            show_404();
        }

        $status_baru = ($tpl['status'] === 'Aktif') ? 'Nonaktif' : 'Aktif';

        if ($this->Model_Template->ubah_status($id, $status_baru)) {
            $this->catat_audit('UPDATE_STATUS', 'Template', array(
                'template_id'   => $id,
                'kode_template' => $tpl['kode_template'],
                'status_baru'   => $status_baru
            ));

            $this->session->set_flashdata('pesan_sukses', 'Status template ' . $tpl['kode_template'] . ' diubah menjadi ' . $status_baru . '.');
        } else {
            $this->session->set_flashdata('pesan_gagal', 'Gagal mengubah status template.');
        }

        redirect('template');
    }

    /**
     * Hapus template form
     * 
     * @param int $id
     */
    public function hapus($id = NULL)
    {
        $this->cek_hak_akses('template_delete');

        $tpl = $this->Model_Template->ambil_by_id($id);
        if (!$tpl) {
            show_404();
        }

        if ($this->Model_Template->hapus($id)) {
            $this->catat_audit('DELETE', 'Template', array(
                'template_id'   => $id,
                'kode_template' => $tpl['kode_template']
            ));

            $this->session->set_flashdata('pesan_sukses', 'Template form pengujian berhasil dihapus.');
        } else {
            $this->session->set_flashdata('pesan_gagal', 'Gagal menghapus template form pengujian.');
        }

        redirect('template');
    }

    /**
     * Inisialisasi 17 Preset Template Form Pengujian (12 Kimia + 5 Mikrobiologi)
     */
    public function inisialisasi_preset()
    {
        $this->cek_hak_akses('template_create');
        $this->session->set_flashdata('pesan_error', 'Fitur inisialisasi preset prototype lama telah dinonaktifkan.');
        redirect('template');
    }
}
