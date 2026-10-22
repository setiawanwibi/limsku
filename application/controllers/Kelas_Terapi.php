<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Kelas_Terapi (Master Kelas Terapi)
 * 
 * Modul Admin TI untuk pengelolaan klasifikasi kelas terapi obat.
 */
class Kelas_Terapi extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_Kelas_Terapi');
    }

    /**
     * Halaman daftar Master Kelas Terapi
     */
    public function index()
    {
        $this->cek_hak_akses('kelas_terapi_view');

        $this->data['halaman_aktif']      = 'kelas_terapi';
        $this->data['judul_halaman']      = 'Master Kelas Terapi';
        $this->data['daftar_kelas_terapi'] = $this->Model_Kelas_Terapi->ambil_semua();

        // Hitung penggunaan metode untuk setiap kelas terapi
        foreach ($this->data['daftar_kelas_terapi'] as &$kt) {
            $kt['total_metode'] = $this->Model_Kelas_Terapi->hitung_penggunaan($kt['id']);
        }

        $this->muat_tampilan('kelas_terapi/index');
    }

    /**
     * Tambah Master Kelas Terapi
     */
    public function tambah()
    {
        $this->cek_hak_akses('kelas_terapi_create');

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('nama_kelas_terapi', 'Nama Kelas Terapi', 'required|trim|max_length[150]|is_unique[therapeutic_classes.nama_kelas_terapi]', array(
                'required'   => '%s wajib diisi.',
                'max_length' => '%s maksimal 150 karakter.',
                'is_unique'  => '%s sudah ada dalam database.'
            ));
            $this->form_validation->set_rules('status', 'Status', 'trim|in_list[Aktif,Nonaktif]');

            if ($this->form_validation->run() === TRUE) {
                $data_simpan = array(
                    'nama_kelas_terapi' => $this->input->post('nama_kelas_terapi', TRUE),
                    'status'            => $this->input->post('status', TRUE) ?: 'Aktif'
                );

                $id = $this->Model_Kelas_Terapi->tambah($data_simpan);
                if ($id) {
                    $this->catat_audit('CREATE', 'Kelas_Terapi', array(
                        'id'                => $id,
                        'nama_kelas_terapi' => $data_simpan['nama_kelas_terapi']
                    ));

                    $this->session->set_flashdata('pesan_sukses', 'Kelas Terapi "' . html_escape($data_simpan['nama_kelas_terapi']) . '" berhasil ditambahkan.');
                    redirect('kelas-terapi');
                } else {
                    $this->session->set_flashdata('pesan_gagal', 'Gagal menambahkan Kelas Terapi.');
                }
            }
        }

        $this->data['halaman_aktif'] = 'kelas_terapi';
        $this->data['judul_halaman'] = 'Tambah Kelas Terapi';
        $this->data['breadcrumbs']   = array(
            array('label' => 'Kelas Terapi', 'url' => site_url('kelas-terapi')),
            array('label' => 'Tambah', 'url' => '#')
        );

        $this->muat_tampilan('kelas_terapi/tambah');
    }

    /**
     * Edit Master Kelas Terapi
     * 
     * @param int $id
     */
    public function edit($id = NULL)
    {
        $this->cek_hak_akses('kelas_terapi_edit');

        $kt = $this->Model_Kelas_Terapi->ambil_by_id($id);
        if (!$kt) {
            show_404();
        }

        if ($this->input->method() === 'post') {
            $is_unique = (strtolower(trim($this->input->post('nama_kelas_terapi'))) !== strtolower(trim($kt['nama_kelas_terapi']))) ? '|is_unique[therapeutic_classes.nama_kelas_terapi]' : '';

            $this->form_validation->set_rules('nama_kelas_terapi', 'Nama Kelas Terapi', 'required|trim|max_length[150]' . $is_unique, array(
                'required'   => '%s wajib diisi.',
                'max_length' => '%s maksimal 150 karakter.',
                'is_unique'  => '%s sudah ada dalam database.'
            ));
            $this->form_validation->set_rules('status', 'Status', 'trim|in_list[Aktif,Nonaktif]');

            if ($this->form_validation->run() === TRUE) {
                $data_update = array(
                    'nama_kelas_terapi' => $this->input->post('nama_kelas_terapi', TRUE),
                    'status'            => $this->input->post('status', TRUE) ?: 'Aktif'
                );

                if ($this->Model_Kelas_Terapi->ubah($id, $data_update)) {
                    $this->catat_audit('UPDATE', 'Kelas_Terapi', array(
                        'id'                => $id,
                        'nama_lama'         => $kt['nama_kelas_terapi'],
                        'nama_baru'         => $data_update['nama_kelas_terapi']
                    ));

                    $this->session->set_flashdata('pesan_sukses', 'Kelas Terapi "' . html_escape($data_update['nama_kelas_terapi']) . '" berhasil diperbarui.');
                    redirect('kelas-terapi');
                } else {
                    $this->session->set_flashdata('pesan_gagal', 'Gagal memperbarui Kelas Terapi.');
                }
            }
        }

        $this->data['halaman_aktif'] = 'kelas_terapi';
        $this->data['judul_halaman'] = 'Edit Kelas Terapi';
        $this->data['breadcrumbs']   = array(
            array('label' => 'Kelas Terapi', 'url' => site_url('kelas-terapi')),
            array('label' => 'Edit', 'url' => '#')
        );
        $this->data['kelas_terapi']  = $kt;
        $this->data['total_metode']  = $this->Model_Kelas_Terapi->hitung_penggunaan($id);

        $this->muat_tampilan('kelas_terapi/edit');
    }

    /**
     * Ubah status aktif/nonaktif
     * 
     * @param int $id
     */
    public function status($id = NULL)
    {
        $this->cek_hak_akses('kelas_terapi_edit');

        $kt = $this->Model_Kelas_Terapi->ambil_by_id($id);
        if (!$kt) {
            show_404();
        }

        $status_baru = ($kt['status'] === 'Aktif') ? 'Nonaktif' : 'Aktif';
        if ($this->Model_Kelas_Terapi->ubah_status($id, $status_baru)) {
            $this->catat_audit('STATUS_CHANGE', 'Kelas_Terapi', array(
                'id'          => $id,
                'status_baru' => $status_baru
            ));

            $this->session->set_flashdata('pesan_sukses', 'Status Kelas Terapi "' . html_escape($kt['nama_kelas_terapi']) . '" diubah menjadi ' . $status_baru . '.');
        } else {
            $this->session->set_flashdata('pesan_gagal', 'Gagal mengubah status Kelas Terapi.');
        }

        redirect('kelas-terapi');
    }

    /**
     * Hapus kelas terapi (Hanya diizinkan jika belum digunakan oleh Master Metode)
     * 
     * @param int $id
     */
    public function hapus($id = NULL)
    {
        $this->cek_hak_akses('kelas_terapi_delete');

        $kt = $this->Model_Kelas_Terapi->ambil_by_id($id);
        if (!$kt) {
            show_404();
        }

        $terpakai = $this->Model_Kelas_Terapi->hitung_penggunaan($id);
        if ($terpakai > 0) {
            $this->session->set_flashdata('pesan_gagal', 'Kelas Terapi "' . html_escape($kt['nama_kelas_terapi']) . '" tidak dapat dihapus permanen karena masih direferensikan oleh ' . $terpakai . ' data Master Metode. Silakan gunakan opsi Nonaktifkan.');
            redirect('kelas-terapi');
        }

        if ($this->Model_Kelas_Terapi->hapus($id)) {
            $this->catat_audit('DELETE', 'Kelas_Terapi', array(
                'id'                => $id,
                'nama_kelas_terapi' => $kt['nama_kelas_terapi']
            ));

            $this->session->set_flashdata('pesan_sukses', 'Kelas Terapi "' . html_escape($kt['nama_kelas_terapi']) . '" berhasil dihapus.');
        } else {
            $this->session->set_flashdata('pesan_gagal', 'Gagal menghapus data Kelas Terapi.');
        }

        redirect('kelas-terapi');
    }
}
