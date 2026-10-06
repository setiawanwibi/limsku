<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Peran
 * 
 * Menangani manajemen Master Data Peran/Role dan Pengaturan Hak Akses.
 */
class Peran extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_Peran');
        $this->load->model('Model_Hak_Akses');
    }

    /**
     * Daftar peran
     */
    public function index()
    {
        $this->cek_hak_akses('peran_view');

        $this->data['halaman_aktif'] = 'peran';
        $this->data['judul_halaman'] = 'Manajemen Peran & Hak Akses';
        $this->data['breadcrumbs']   = array(
            array('label' => 'Master Data', 'url' => '#'),
            array('label' => 'Peran', 'url' => site_url('peran'))
        );

        $daftar_peran = $this->Model_Peran->ambil_semua();
        foreach ($daftar_peran as &$role) {
            $role['total_pengguna'] = $this->Model_Peran->hitung_pengguna($role['id']);
        }

        $this->data['daftar_peran'] = $daftar_peran;

        $this->muat_tampilan('peran/index');
    }

    /**
     * Form dan proses tambah peran baru
     */
    public function tambah()
    {
        $this->cek_hak_akses('peran_create');

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('nama_role', 'Nama Peran', 'required|trim|is_unique[roles.nama_role]', array(
                'required'  => '%s wajib diisi.',
                'is_unique' => '%s sudah ada.'
            ));

            if ($this->form_validation->run() === TRUE) {
                $data_simpan = array(
                    'nama_role'  => $this->input->post('nama_role', TRUE),
                    'keterangan' => $this->input->post('keterangan', TRUE) ?: NULL
                );

                $role_id = $this->Model_Peran->tambah($data_simpan);

                if ($role_id) {
                    $this->catat_audit('CREATE', 'Peran', array(
                        'role_id'   => $role_id,
                        'nama_role' => $data_simpan['nama_role']
                    ));

                    $this->session->set_flashdata('pesan_sukses', 'Peran baru berhasil ditambahkan.');
                    redirect('peran');
                } else {
                    $this->session->set_flashdata('pesan_gagal', 'Gagal menambahkan peran baru.');
                }
            }
        }

        $this->data['halaman_aktif'] = 'peran';
        $this->data['judul_halaman'] = 'Tambah Peran';
        $this->data['breadcrumbs']   = array(
            array('label' => 'Peran', 'url' => site_url('peran')),
            array('label' => 'Tambah', 'url' => '#')
        );

        $this->muat_tampilan('peran/tambah');
    }

    /**
     * Form dan proses ubah peran
     * 
     * @param int $id
     */
    public function edit($id = NULL)
    {
        $this->cek_hak_akses('peran_edit');

        $role = $this->Model_Peran->ambil_by_id($id);
        if (!$role) {
            show_404();
        }

        if ($this->input->method() === 'post') {
            $is_unique = ($this->input->post('nama_role') !== $role['nama_role']) ? '|is_unique[roles.nama_role]' : '';

            $this->form_validation->set_rules('nama_role', 'Nama Peran', 'required|trim' . $is_unique, array(
                'required'  => '%s wajib diisi.',
                'is_unique' => '%s sudah ada.'
            ));

            if ($this->form_validation->run() === TRUE) {
                $data_update = array(
                    'nama_role'  => $this->input->post('nama_role', TRUE),
                    'keterangan' => $this->input->post('keterangan', TRUE) ?: NULL
                );

                if ($this->Model_Peran->ubah($id, $data_update)) {
                    $this->catat_audit('UPDATE', 'Peran', array(
                        'role_id'   => $id,
                        'nama_role' => $data_update['nama_role']
                    ));

                    $this->session->set_flashdata('pesan_sukses', 'Data peran berhasil diperbarui.');
                    redirect('peran');
                } else {
                    $this->session->set_flashdata('pesan_gagal', 'Gagal memperbarui data peran.');
                }
            }
        }

        $this->data['halaman_aktif'] = 'peran';
        $this->data['judul_halaman'] = 'Edit Peran';
        $this->data['breadcrumbs']   = array(
            array('label' => 'Peran', 'url' => site_url('peran')),
            array('label' => 'Edit', 'url' => '#')
        );
        $this->data['role']          = $role;

        $this->muat_tampilan('peran/edit');
    }

    /**
     * Pengaturan Hak Akses (Permissions) untuk Peran tertentu
     * 
     * @param int $id ID Role
     */
    public function hak_akses($id = NULL)
    {
        $this->cek_hak_akses('peran_edit');

        $role = $this->Model_Peran->ambil_by_id($id);
        if (!$role) {
            show_404();
        }

        if ($this->input->method() === 'post') {
            $permission_ids = $this->input->post('permissions');
            if (!is_array($permission_ids)) {
                $permission_ids = array();
            }

            if ($this->Model_Hak_Akses->simpan_role_permissions($id, $permission_ids)) {
                $this->catat_audit('ASSIGN_PERMISSIONS', 'Peran', array(
                    'role_id'          => $id,
                    'nama_role'        => $role['nama_role'],
                    'total_permissions' => count($permission_ids)
                ));

                $this->session->set_flashdata('pesan_sukses', 'Hak akses untuk peran ' . $role['nama_role'] . ' berhasil diperbarui.');
                redirect('peran');
            } else {
                $this->session->set_flashdata('pesan_gagal', 'Gagal memperbarui hak akses peran.');
            }
        }

        $semua_permissions = $this->Model_Hak_Akses->ambil_semua();
        $assigned_ids      = $this->Model_Hak_Akses->ambil_permission_by_role($id);

        // Kelompokkan permission berdasarkan kategori
        $permissions_by_kategori = array();
        foreach ($semua_permissions as $p) {
            $kategori = $p['kategori'] ?: 'Umum';
            $permissions_by_kategori[$kategori][] = $p;
        }

        $this->data['halaman_aktif']            = 'peran';
        $this->data['judul_halaman']            = 'Hak Akses Peran: ' . $role['nama_role'];
        $this->data['breadcrumbs']              = array(
            array('label' => 'Peran', 'url' => site_url('peran')),
            array('label' => 'Hak Akses', 'url' => '#')
        );
        $this->data['role']                     = $role;
        $this->data['permissions_by_kategori'] = $permissions_by_kategori;
        $this->data['assigned_ids']             = $assigned_ids;

        $this->muat_tampilan('peran/hak_akses');
    }

    /**
     * Tambah Master Permission Baru
     */
    public function tambah_permission()
    {
        $this->cek_hak_akses('peran_create');

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('nama_permission', 'Nama Permission / Kode', 'required|trim|is_unique[permissions.nama_permission]', array(
                'required'  => '%s wajib diisi.',
                'is_unique' => '%s sudah ada.'
            ));
            $this->form_validation->set_rules('deskripsi', 'Deskripsi', 'required|trim');

            if ($this->form_validation->run() === TRUE) {
                $data_simpan = array(
                    'nama_permission' => strtolower($this->input->post('nama_permission', TRUE)),
                    'deskripsi'       => $this->input->post('deskripsi', TRUE),
                    'kategori'        => $this->input->post('kategori', TRUE) ?: 'Lainnya'
                );

                $perm_id = $this->Model_Hak_Akses->tambah($data_simpan);

                if ($perm_id) {
                    $this->catat_audit('CREATE', 'Permission', array(
                        'permission_id'   => $perm_id,
                        'nama_permission' => $data_simpan['nama_permission']
                    ));

                    $this->session->set_flashdata('pesan_sukses', 'Permission baru berhasil ditambahkan.');
                } else {
                    $this->session->set_flashdata('pesan_gagal', 'Gagal menambahkan permission.');
                }
            } else {
                $this->session->set_flashdata('pesan_gagal', validation_errors());
            }
        }

        redirect('peran');
    }

    /**
     * Hapus peran
     * 
     * @param int $id
     */
    public function hapus($id = NULL)
    {
        $this->cek_hak_akses('peran_delete');

        $role = $this->Model_Peran->ambil_by_id($id);
        if (!$role) {
            show_404();
        }

        // Cek apakah ada pengguna yang menggunakan peran ini
        $total_pengguna = $this->Model_Peran->hitung_pengguna($id);
        if ($total_pengguna > 0) {
            $this->session->set_flashdata('pesan_gagal', 'Peran "' . $role['nama_role'] . '" sedang digunakan oleh ' . $total_pengguna . ' pengguna dan tidak dapat dihapus.');
            redirect('peran');
        }

        if ($this->Model_Peran->hapus($id)) {
            $this->catat_audit('DELETE', 'Peran', array(
                'role_id'   => $id,
                'nama_role' => $role['nama_role']
            ));

            $this->session->set_flashdata('pesan_sukses', 'Peran "' . $role['nama_role'] . '" berhasil dihapus.');
        } else {
            $this->session->set_flashdata('pesan_gagal', 'Gagal menghapus peran.');
        }

        redirect('peran');
    }
}
