<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Pengguna
 * 
 * Menangani manajemen Master Data Pengguna (Users).
 */
class Pengguna extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_Pengguna');
        $this->load->model('Model_Peran');
        $this->load->model('Model_Laboratorium');
    }

    /**
     * Daftar pengguna
     */
    public function index()
    {
        $this->cek_hak_akses('pengguna_view');

        $this->data['halaman_aktif'] = 'pengguna';
        $this->data['judul_halaman'] = 'Manajemen Pengguna';
        $this->data['breadcrumbs']   = array(
            array('label' => 'Master Data', 'url' => '#'),
            array('label' => 'Pengguna', 'url' => site_url('pengguna'))
        );

        $this->data['daftar_pengguna'] = $this->Model_Pengguna->ambil_semua();

        $this->muat_tampilan('pengguna/index');
    }

    /**
     * Form dan proses tambah pengguna baru
     */
    public function tambah()
    {
        $this->cek_hak_akses('pengguna_create');

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('username', 'Nama Pengguna / Username', 'required|trim|is_unique[users.username]', array(
                'required'  => '%s wajib diisi.',
                'is_unique' => '%s sudah digunakan.'
            ));
            $this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'required|trim', array(
                'required' => '%s wajib diisi.'
            ));
            $this->form_validation->set_rules('password', 'Kata Sandi', 'required|min_length[6]', array(
                'required'   => '%s wajib diisi.',
                'min_length' => '%s minimal 6 karakter.'
            ));
            $this->form_validation->set_rules('role_id', 'Role / Peran', 'required|numeric', array(
                'required' => '%s wajib dipilih.'
            ));

            if ($this->form_validation->run() === TRUE) {
                $data_simpan = array(
                    'username'      => $this->input->post('username', TRUE),
                    'nip'           => $this->input->post('nip', TRUE) ?: NULL,
                    'password'      => $this->input->post('password'),
                    'nama_lengkap'  => $this->input->post('nama_lengkap', TRUE),
                    'email'         => $this->input->post('email', TRUE) ?: NULL,
                    'role_id'       => $this->input->post('role_id', TRUE),
                    'laboratory_id' => $this->input->post('laboratory_id', TRUE) ?: NULL,
                    'status'        => $this->input->post('status', TRUE) ?: 'Aktif'
                );

                $user_id = $this->Model_Pengguna->tambah($data_simpan);

                if ($user_id) {
                    $this->catat_audit('CREATE', 'Pengguna', array(
                        'user_id'  => $user_id,
                        'username' => $data_simpan['username'],
                        'nama'     => $data_simpan['nama_lengkap']
                    ));

                    $this->session->set_flashdata('pesan_sukses', 'Pengguna baru berhasil ditambahkan.');
                    redirect('pengguna');
                } else {
                    $this->session->set_flashdata('pesan_gagal', 'Gagal menambahkan pengguna baru.');
                }
            }
        }

        $this->data['halaman_aktif']      = 'pengguna';
        $this->data['judul_halaman']      = 'Tambah Pengguna';
        $this->data['breadcrumbs']        = array(
            array('label' => 'Pengguna', 'url' => site_url('pengguna')),
            array('label' => 'Tambah', 'url' => '#')
        );
        $this->data['daftar_role']        = $this->Model_Peran->ambil_semua();
        $this->data['daftar_laboratorium'] = $this->Model_Laboratorium->ambil_aktif();

        $this->muat_tampilan('pengguna/tambah');
    }

    /**
     * Form dan proses ubah data pengguna
     * 
     * @param int $id ID pengguna
     */
    public function edit($id = NULL)
    {
        $this->cek_hak_akses('pengguna_edit');

        $user = $this->Model_Pengguna->ambil_by_id($id);
        if (!$user) {
            show_404();
        }

        if ($this->input->method() === 'post') {
            $is_unique_username = ($this->input->post('username') !== $user['username']) ? '|is_unique[users.username]' : '';
            
            $this->form_validation->set_rules('username', 'Nama Pengguna / Username', 'required|trim' . $is_unique_username, array(
                'required'  => '%s wajib diisi.',
                'is_unique' => '%s sudah digunakan.'
            ));
            $this->form_validation->set_rules('nama_lengkap', 'Nama Lengkap', 'required|trim', array(
                'required' => '%s wajib diisi.'
            ));
            $this->form_validation->set_rules('role_id', 'Role / Peran', 'required|numeric', array(
                'required' => '%s wajib dipilih.'
            ));

            if ($this->form_validation->run() === TRUE) {
                $data_update = array(
                    'username'      => $this->input->post('username', TRUE),
                    'nip'           => $this->input->post('nip', TRUE) ?: NULL,
                    'nama_lengkap'  => $this->input->post('nama_lengkap', TRUE),
                    'email'         => $this->input->post('email', TRUE) ?: NULL,
                    'role_id'       => $this->input->post('role_id', TRUE),
                    'laboratory_id' => $this->input->post('laboratory_id', TRUE) ?: NULL,
                    'status'        => $this->input->post('status', TRUE) ?: 'Aktif'
                );

                // Jika kata sandi diisi
                if ($this->input->post('password')) {
                    $data_update['password'] = $this->input->post('password');
                }

                if ($this->Model_Pengguna->ubah($id, $data_update)) {
                    $this->catat_audit('UPDATE', 'Pengguna', array(
                        'user_id'  => $id,
                        'username' => $data_update['username'],
                        'nama'     => $data_update['nama_lengkap']
                    ));

                    $this->session->set_flashdata('pesan_sukses', 'Data pengguna berhasil diperbarui.');
                    redirect('pengguna');
                } else {
                    $this->session->set_flashdata('pesan_gagal', 'Gagal memperbarui data pengguna.');
                }
            }
        }

        $this->data['halaman_aktif']       = 'pengguna';
        $this->data['judul_halaman']       = 'Edit Pengguna';
        $this->data['breadcrumbs']         = array(
            array('label' => 'Pengguna', 'url' => site_url('pengguna')),
            array('label' => 'Edit', 'url' => '#')
        );
        $this->data['user']                = $user;
        $this->data['daftar_role']         = $this->Model_Peran->ambil_semua();
        $this->data['daftar_laboratorium'] = $this->Model_Laboratorium->ambil_aktif();

        $this->muat_tampilan('pengguna/edit');
    }

    /**
     * Ubah status pengguna (Aktif/Nonaktif)
     * 
     * @param int $id
     */
    public function ubah_status($id = NULL)
    {
        $this->cek_hak_akses('pengguna_edit');

        $user = $this->Model_Pengguna->ambil_by_id($id);
        if (!$user) {
            show_404();
        }

        $status_baru = ($user['status'] === 'Aktif') ? 'Nonaktif' : 'Aktif';

        if ($this->Model_Pengguna->ubah_status($id, $status_baru)) {
            $this->catat_audit('UPDATE_STATUS', 'Pengguna', array(
                'user_id'     => $id,
                'username'    => $user['username'],
                'status_lama' => $user['status'],
                'status_baru' => $status_baru
            ));

            $this->session->set_flashdata('pesan_sukses', 'Status pengguna ' . $user['username'] . ' diubah menjadi ' . $status_baru . '.');
        } else {
            $this->session->set_flashdata('pesan_gagal', 'Gagal mengubah status pengguna.');
        }

        redirect('pengguna');
    }

    /**
     * Form dan proses reset password pengguna
     * 
     * @param int $id
     */
    public function ubah_password($id = NULL)
    {
        $this->cek_hak_akses('pengguna_edit');

        $user = $this->Model_Pengguna->ambil_by_id($id);
        if (!$user) {
            show_404();
        }

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('password_baru', 'Kata Sandi Baru', 'required|min_length[6]', array(
                'required'   => '%s wajib diisi.',
                'min_length' => '%s minimal 6 karakter.'
            ));
            $this->form_validation->set_rules('konfirmasi_password', 'Konfirmasi Kata Sandi Baru', 'required|matches[password_baru]', array(
                'required' => '%s wajib diisi.',
                'matches'  => '%s tidak sesuai dengan Kata Sandi Baru.'
            ));

            if ($this->form_validation->run() === TRUE) {
                $password_baru = $this->input->post('password_baru');

                if ($this->Model_Pengguna->ubah_password($id, $password_baru)) {
                    $this->catat_audit('RESET_PASSWORD', 'Pengguna', array(
                        'user_id'  => $id,
                        'username' => $user['username']
                    ));

                    $this->session->set_flashdata('pesan_sukses', 'Kata sandi pengguna ' . $user['username'] . ' berhasil diubah.');
                    redirect('pengguna');
                } else {
                    $this->session->set_flashdata('pesan_gagal', 'Gagal mengubah kata sandi pengguna.');
                }
            }
        }

        $this->data['halaman_aktif'] = 'pengguna';
        $this->data['judul_halaman'] = 'Ubah Kata Sandi Pengguna';
        $this->data['breadcrumbs']   = array(
            array('label' => 'Pengguna', 'url' => site_url('pengguna')),
            array('label' => 'Ubah Kata Sandi', 'url' => '#')
        );
        $this->data['user']          = $user;

        $this->muat_tampilan('pengguna/ubah_password');
    }

    /**
     * Hapus pengguna
     * 
     * @param int $id
     */
    public function hapus($id = NULL)
    {
        $this->cek_hak_akses('pengguna_delete');

        $user = $this->Model_Pengguna->ambil_by_id($id);
        if (!$user) {
            show_404();
        }

        // Mencegah pengguna menghapus dirinya sendiri
        if ($user['id'] == $this->session->userdata('user_id')) {
            $this->session->set_flashdata('pesan_gagal', 'Anda tidak dapat menghapus akun Anda sendiri.');
            redirect('pengguna');
        }

        if ($this->Model_Pengguna->hapus($id)) {
            $this->catat_audit('DELETE', 'Pengguna', array(
                'user_id'  => $id,
                'username' => $user['username']
            ));

            $this->session->set_flashdata('pesan_sukses', 'Pengguna ' . $user['username'] . ' berhasil dihapus.');
        } else {
            $this->session->set_flashdata('pesan_gagal', 'Gagal menghapus pengguna.');
        }

        redirect('pengguna');
    }
}
