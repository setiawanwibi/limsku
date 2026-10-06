<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Sampel
 * 
 * Menangani Registrasi Sampel (Input Manual & Import Excel), Management Sampel, Detail, dan List.
 */
class Sampel extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_Sampel');
        $this->load->library('SimpleXLSX');
    }

    /**
     * Daftar sampel
     */
    public function index()
    {
        $this->cek_hak_akses('sampel_view');

        $this->data['halaman_aktif'] = 'sampel';
        $this->data['judul_halaman'] = 'Daftar Sampel Laboratorium';
        $this->data['breadcrumbs']   = array(
            array('label' => 'Sampel', 'url' => site_url('sampel'))
        );

        $this->data['daftar_sampel'] = $this->Model_Sampel->ambil_semua();

        $this->muat_tampilan('sampel/index');
    }

    /**
     * Detail sampel
     * 
     * @param int $id
     */
    public function detail($id = NULL)
    {
        $this->cek_hak_akses('sampel_view');

        $sampel = $this->Model_Sampel->ambil_by_id($id);
        if (!$sampel) {
            show_404();
        }

        $this->data['halaman_aktif'] = 'sampel';
        $this->data['judul_halaman'] = 'Detail Sampel: ' . html_escape($sampel['nama_sampel']);
        $this->data['breadcrumbs']   = array(
            array('label' => 'Sampel', 'url' => site_url('sampel')),
            array('label' => 'Detail', 'url' => '#')
        );
        $this->data['sampel']        = $sampel;

        $this->muat_tampilan('sampel/detail');
    }

    /**
     * Form dan proses registrasi sampel manual
     */
    public function tambah()
    {
        $this->cek_hak_akses('sampel_create');

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('nama_sampel', 'Nama Sampel', 'required|trim', array(
                'required' => '%s wajib diisi.'
            ));

            if ($this->form_validation->run() === TRUE) {
                $data_simpan = array(
                    'no'                     => $this->input->post('no', TRUE) ?: NULL,
                    'kategori_sampel'        => $this->input->post('kategori_sampel', TRUE) ?: NULL,
                    'sub_kategori'           => $this->input->post('sub_kategori', TRUE) ?: NULL,
                    'jenis_kelas_terapi'     => $this->input->post('jenis_kelas_terapi', TRUE) ?: NULL,
                    'kategori_sarana'        => $this->input->post('kategori_sarana', TRUE) ?: NULL,
                    'nama_sarana'            => $this->input->post('nama_sarana', TRUE) ?: NULL,
                    'kabupaten_kota'         => $this->input->post('kabupaten_kota', TRUE) ?: NULL,
                    'tanggal_sampling'       => $this->input->post('tanggal_sampling', TRUE) ?: NULL,
                    'kode_sampel_manual'     => $this->input->post('kode_sampel_manual', TRUE) ?: NULL,
                    'no_sipt'                => $this->input->post('no_sipt', TRUE) ?: NULL,
                    'nama_sampel'            => $this->input->post('nama_sampel', TRUE),
                    'nomor_izin_edar'        => $this->input->post('nomor_izin_edar', TRUE) ?: NULL,
                    'kondisi_produk'         => $this->input->post('kondisi_produk', TRUE) ?: NULL,
                    'no_bets'                => $this->input->post('no_bets', TRUE) ?: NULL,
                    'kedaluwarsa'            => $this->input->post('kedaluwarsa', TRUE) ?: NULL,
                    'kemasan'                => $this->input->post('kemasan', TRUE) ?: NULL,
                    'nama_alamat_perusahaan' => $this->input->post('nama_alamat_perusahaan', TRUE) ?: NULL,
                    'komposisi'              => $this->input->post('komposisi', TRUE) ?: NULL,
                    'jumlah_kimia'           => $this->input->post('jumlah_kimia', TRUE) ?: NULL,
                    'jumlah_mikro'           => $this->input->post('jumlah_mikro', TRUE) ?: NULL,
                    'jumlah_arsip'           => $this->input->post('jumlah_arsip', TRUE) ?: NULL,
                    'jumlah_penandaan'       => $this->input->post('jumlah_penandaan', TRUE) ?: NULL,
                    'jumlah_total'           => $this->input->post('jumlah_total', TRUE) ?: NULL,
                    'penyimpanan'            => $this->input->post('penyimpanan', TRUE) ?: NULL,
                    'penandaan'              => $this->input->post('penandaan', TRUE) ?: NULL,
                    'harga'                  => $this->input->post('harga', TRUE) ?: NULL,
                    'tie'                    => $this->input->post('tie', TRUE) ?: NULL,
                    'mk'                     => $this->input->post('mk', TRUE) ?: NULL,
                    'tmk'                    => $this->input->post('tmk', TRUE) ?: NULL,
                    'surtug'                 => $this->input->post('surtug', TRUE) ?: NULL,
                    'balai_penguji'          => $this->input->post('balai_penguji', TRUE) ?: NULL,
                    'status'                 => 'Menunggu Pengujian',
                    'created_by'             => $this->session->userdata('user_id')
                );

                $sampel_id = $this->Model_Sampel->tambah($data_simpan);

                if ($sampel_id) {
                    $this->catat_audit('CREATE', 'Sampel', array(
                        'sampel_id'   => $sampel_id,
                        'nama_sampel' => $data_simpan['nama_sampel'],
                        'status'      => 'Menunggu Pengujian'
                    ));

                    $this->session->set_flashdata('pesan_sukses', 'Sampel berhasil diregistrasikan.');
                    redirect('sampel');
                } else {
                    $this->session->set_flashdata('pesan_gagal', 'Gagal meregistrasikan sampel.');
                }
            }
        }

        $this->data['halaman_aktif'] = 'sampel';
        $this->data['judul_halaman'] = 'Registrasi Sampel Manual';
        $this->data['breadcrumbs']   = array(
            array('label' => 'Sampel', 'url' => site_url('sampel')),
            array('label' => 'Registrasi Manual', 'url' => '#')
        );

        $this->muat_tampilan('sampel/tambah');
    }

    /**
     * Form dan proses edit sampel
     * 
     * @param int $id
     */
    public function edit($id = NULL)
    {
        $this->cek_hak_akses('sampel_edit');

        $sampel = $this->Model_Sampel->ambil_by_id($id);
        if (!$sampel) {
            show_404();
        }

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('nama_sampel', 'Nama Sampel', 'required|trim', array(
                'required' => '%s wajib diisi.'
            ));

            if ($this->form_validation->run() === TRUE) {
                $data_update = array(
                    'no'                     => $this->input->post('no', TRUE) ?: NULL,
                    'kategori_sampel'        => $this->input->post('kategori_sampel', TRUE) ?: NULL,
                    'sub_kategori'           => $this->input->post('sub_kategori', TRUE) ?: NULL,
                    'jenis_kelas_terapi'     => $this->input->post('jenis_kelas_terapi', TRUE) ?: NULL,
                    'kategori_sarana'        => $this->input->post('kategori_sarana', TRUE) ?: NULL,
                    'nama_sarana'            => $this->input->post('nama_sarana', TRUE) ?: NULL,
                    'kabupaten_kota'         => $this->input->post('kabupaten_kota', TRUE) ?: NULL,
                    'tanggal_sampling'       => $this->input->post('tanggal_sampling', TRUE) ?: NULL,
                    'kode_sampel_manual'     => $this->input->post('kode_sampel_manual', TRUE) ?: NULL,
                    'no_sipt'                => $this->input->post('no_sipt', TRUE) ?: NULL,
                    'nama_sampel'            => $this->input->post('nama_sampel', TRUE),
                    'nomor_izin_edar'        => $this->input->post('nomor_izin_edar', TRUE) ?: NULL,
                    'kondisi_produk'         => $this->input->post('kondisi_produk', TRUE) ?: NULL,
                    'no_bets'                => $this->input->post('no_bets', TRUE) ?: NULL,
                    'kedaluwarsa'            => $this->input->post('kedaluwarsa', TRUE) ?: NULL,
                    'kemasan'                => $this->input->post('kemasan', TRUE) ?: NULL,
                    'nama_alamat_perusahaan' => $this->input->post('nama_alamat_perusahaan', TRUE) ?: NULL,
                    'komposisi'              => $this->input->post('komposisi', TRUE) ?: NULL,
                    'jumlah_kimia'           => $this->input->post('jumlah_kimia', TRUE) ?: NULL,
                    'jumlah_mikro'           => $this->input->post('jumlah_mikro', TRUE) ?: NULL,
                    'jumlah_arsip'           => $this->input->post('jumlah_arsip', TRUE) ?: NULL,
                    'jumlah_penandaan'       => $this->input->post('jumlah_penandaan', TRUE) ?: NULL,
                    'jumlah_total'           => $this->input->post('jumlah_total', TRUE) ?: NULL,
                    'penyimpanan'            => $this->input->post('penyimpanan', TRUE) ?: NULL,
                    'penandaan'              => $this->input->post('penandaan', TRUE) ?: NULL,
                    'harga'                  => $this->input->post('harga', TRUE) ?: NULL,
                    'tie'                    => $this->input->post('tie', TRUE) ?: NULL,
                    'mk'                     => $this->input->post('mk', TRUE) ?: NULL,
                    'tmk'                    => $this->input->post('tmk', TRUE) ?: NULL,
                    'surtug'                 => $this->input->post('surtug', TRUE) ?: NULL,
                    'balai_penguji'          => $this->input->post('balai_penguji', TRUE) ?: NULL,
                    'updated_by'             => $this->session->userdata('user_id')
                );

                if ($this->Model_Sampel->ubah($id, $data_update)) {
                    $this->catat_audit('UPDATE', 'Sampel', array(
                        'sampel_id'   => $id,
                        'nama_sampel' => $data_update['nama_sampel']
                    ));

                    $this->session->set_flashdata('pesan_sukses', 'Data sampel berhasil diperbarui.');
                    redirect('sampel/detail/' . $id);
                } else {
                    $this->session->set_flashdata('pesan_gagal', 'Gagal memperbarui data sampel.');
                }
            }
        }

        $this->data['halaman_aktif'] = 'sampel';
        $this->data['judul_halaman'] = 'Edit Sampel: ' . html_escape($sampel['nama_sampel']);
        $this->data['breadcrumbs']   = array(
            array('label' => 'Sampel', 'url' => site_url('sampel')),
            array('label' => 'Edit', 'url' => '#')
        );
        $this->data['sampel']        = $sampel;

        $this->muat_tampilan('sampel/edit');
    }

    /**
     * Hapus sampel
     * 
     * @param int $id
     */
    public function hapus($id = NULL)
    {
        $this->cek_hak_akses('sampel_delete');

        $sampel = $this->Model_Sampel->ambil_by_id($id);
        if (!$sampel) {
            show_404();
        }

        if ($this->Model_Sampel->hapus($id)) {
            $this->catat_audit('DELETE', 'Sampel', array(
                'sampel_id'   => $id,
                'nama_sampel' => $sampel['nama_sampel']
            ));

            $this->session->set_flashdata('pesan_sukses', 'Sampel berhasil dihapus.');
        } else {
            $this->session->set_flashdata('pesan_gagal', 'Gagal menghapus sampel.');
        }

        redirect('sampel');
    }

    /**
     * Halaman dan proses Import Sampel via Excel (.xlsx / .csv)
     */
    public function import()
    {
        $this->cek_hak_akses('sampel_import');

        if ($this->input->method() === 'post') {
            if (empty($_FILES['file_excel']['name'])) {
                $this->session->set_flashdata('pesan_gagal', 'Silakan pilih file Excel (.xlsx / .csv) yang akan diunggah.');
                redirect('sampel/import');
            }

            $ext = strtolower(pathinfo($_FILES['file_excel']['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, array('xlsx', 'csv'))) {
                $this->session->set_flashdata('pesan_gagal', 'Format file tidak valid. Hanya file ber-ekstensi .xlsx atau .csv yang diperbolehkan.');
                redirect('sampel/import');
            }

            $tmpPath = $_FILES['file_excel']['tmp_name'];
            $rows    = array();

            if ($ext === 'xlsx') {
                $xlsx = SimpleXLSX::parse($tmpPath);
                if ($xlsx) {
                    $rows = $xlsx->rows();
                } else {
                    $this->session->set_flashdata('pesan_gagal', 'Error membaca file Excel: ' . $xlsx->getError());
                    redirect('sampel/import');
                }
            } elseif ($ext === 'csv') {
                if (($handle = fopen($tmpPath, 'r')) !== FALSE) {
                    while (($data = fgetcsv($handle, 2000, ',')) !== FALSE) {
                        $rows[] = array_map('trim', $data);
                    }
                    fclose($handle);
                }
            }

            if (empty($rows) || count($rows) < 2) {
                $this->session->set_flashdata('pesan_gagal', 'File Excel kosong atau tidak memiliki baris data setelah header.');
                redirect('sampel/import');
            }

            // Membaca Header (Baris 0)
            $header = array_map('trim', $rows[0]);

            // Pemetaan Kolom (31 fields)
            $mapping = $this->_map_excel_header($header);

            if ($mapping['status'] === FALSE) {
                $this->session->set_flashdata('pesan_gagal', 'Header Excel tidak sesuai template: ' . $mapping['pesan']);
                redirect('sampel/import');
            }

            $field_map  = $mapping['field_map'];
            $batch_data = array();
            $errors     = array();
            $user_id    = $this->session->userdata('user_id');

            // Validasi baris data (mulai baris index 1)
            for ($i = 1; $i < count($rows); $i++) {
                $row = $rows[$i];
                if (empty(array_filter($row))) {
                    continue; // Skip baris kosong
                }

                $row_num = $i + 1;
                $row_data = array(
                    'status'     => 'Menunggu Pengujian',
                    'created_by' => $user_id
                );

                foreach ($field_map as $col_index => $db_field) {
                    $val = isset($row[$col_index]) ? trim($row[$col_index]) : NULL;
                    $row_data[$db_field] = ($val !== '') ? $val : NULL;
                }

                // Validasi data wajib (misal: nama_sampel)
                if (empty($row_data['nama_sampel'])) {
                    $errors[] = "Baris $row_num: Kolom 'Nama Sampel' wajib diisi.";
                }

                $batch_data[] = $row_data;
            }

            if (!empty($errors)) {
                $this->session->set_flashdata('import_errors', $errors);
                $this->session->set_flashdata('pesan_gagal', 'Import dibatalkan karena terdapat error validasi data.');
                redirect('sampel/import');
            }

            if (!empty($batch_data)) {
                // Eksekusi Database Transaction untuk keamanan import
                if ($this->Model_Sampel->tambah_batch($batch_data)) {
                    $total_imported = count($batch_data);

                    $this->catat_audit('IMPORT', 'Sampel', array(
                        'file_name'      => $_FILES['file_excel']['name'],
                        'total_imported' => $total_imported,
                        'status'         => 'Menunggu Pengujian'
                    ));

                    $this->session->set_flashdata('pesan_sukses', "Berhasil meng-import $total_imported data sampel ke dalam sistem (Status: Menunggu Pengujian).");
                    redirect('sampel');
                } else {
                    $this->session->set_flashdata('pesan_gagal', 'Gagal meng-import data sampel. Transaksi database di-rollback.');
                    redirect('sampel/import');
                }
            }
        }

        $this->data['halaman_aktif'] = 'sampel';
        $this->data['judul_halaman'] = 'Import Sampel via Excel';
        $this->data['breadcrumbs']   = array(
            array('label' => 'Sampel', 'url' => site_url('sampel')),
            array('label' => 'Import Excel', 'url' => '#')
        );

        $this->muat_tampilan('sampel/import');
    }

    /**
     * Memetakan header Excel (31 kolom) ke nama field database (snake_case)
     * Menangani konteks 2 "Penandaan":
     * - Penandaan (bagian jumlah) => jumlah_penandaan
     * - Penandaan (field utama)  => penandaan
     * 
     * @param array $header
     * @return array
     */
    private function _map_excel_header($header)
    {
        $field_map = array();
        $penandaan_count = 0;

        foreach ($header as $idx => $col_name) {
            $normalized = strtolower(trim($col_name));

            switch ($normalized) {
                case 'no.':
                case 'no':
                    $field_map[$idx] = 'no';
                    break;
                case 'kategori sampel':
                    $field_map[$idx] = 'kategori_sampel';
                    break;
                case 'sub kategori':
                    $field_map[$idx] = 'sub_kategori';
                    break;
                case 'jenis/kelas terapi':
                case 'jenis kelas terapi':
                    $field_map[$idx] = 'jenis_kelas_terapi';
                    break;
                case 'kategori sarana':
                    $field_map[$idx] = 'kategori_sarana';
                    break;
                case 'nama sarana':
                    $field_map[$idx] = 'nama_sarana';
                    break;
                case 'kabupaten/kota':
                case 'kabupaten kota':
                    $field_map[$idx] = 'kabupaten_kota';
                    break;
                case 'tanggal sampling':
                    $field_map[$idx] = 'tanggal_sampling';
                    break;
                case 'kode sampel manual':
                    $field_map[$idx] = 'kode_sampel_manual';
                    break;
                case 'no sipt':
                case 'no. sipt':
                    $field_map[$idx] = 'no_sipt';
                    break;
                case 'nama sampel':
                    $field_map[$idx] = 'nama_sampel';
                    break;
                case 'nomor izin edar':
                    $field_map[$idx] = 'nomor_izin_edar';
                    break;
                case 'kondisi produk':
                    $field_map[$idx] = 'kondisi_produk';
                    break;
                case 'no bets':
                case 'no. bets':
                    $field_map[$idx] = 'no_bets';
                    break;
                case 'kedaluwarsa':
                    $field_map[$idx] = 'kedaluwarsa';
                    break;
                case 'kemasan':
                    $field_map[$idx] = 'kemasan';
                    break;
                case 'nama dan alamat perusahaan':
                    $field_map[$idx] = 'nama_alamat_perusahaan';
                    break;
                case 'komposisi':
                    $field_map[$idx] = 'komposisi';
                    break;
                case 'kimia':
                    $field_map[$idx] = 'jumlah_kimia';
                    break;
                case 'mikro':
                    $field_map[$idx] = 'jumlah_mikro';
                    break;
                case 'arsip (di balai penguji)':
                case 'arsip':
                    $field_map[$idx] = 'jumlah_arsip';
                    break;
                case 'penandaan':
                    $penandaan_count++;
                    if ($penandaan_count === 1) {
                        $field_map[$idx] = 'jumlah_penandaan';
                    } else {
                        $field_map[$idx] = 'penandaan';
                    }
                    break;
                case 'jumlah penandaan':
                    $field_map[$idx] = 'jumlah_penandaan';
                    break;
                case 'total':
                case 'jumlah total':
                    $field_map[$idx] = 'jumlah_total';
                    break;
                case 'penyimpanan':
                    $field_map[$idx] = 'penyimpanan';
                    break;
                case 'harga':
                    $field_map[$idx] = 'harga';
                    break;
                case 'tie':
                    $field_map[$idx] = 'tie';
                    break;
                case 'mk':
                    $field_map[$idx] = 'mk';
                    break;
                case 'tmk':
                    $field_map[$idx] = 'tmk';
                    break;
                case 'surtug':
                    $field_map[$idx] = 'surtug';
                    break;
                case 'balai penguji':
                    $field_map[$idx] = 'balai_penguji';
                    break;
                default:
                    // Jika kolom opsional/tidak dikenal, diabaikan secara aman
                    break;
            }
        }

        // Verifikasi bahwa kolom minimal (nama_sampel) terpetakan
        if (!in_array('nama_sampel', $field_map)) {
            return array(
                'status' => FALSE,
                'pesan'  => 'Kolom "Nama Sampel" tidak ditemukan pada header Excel.'
            );
        }

        return array(
            'status'    => TRUE,
            'field_map' => $field_map
        );
    }
}
