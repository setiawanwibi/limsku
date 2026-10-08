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
        $this->load->library('Excel_Importer');
    }

    /**
     * Daftar sampel
     */
    public function index()
    {
        $this->cek_hak_akses('sampel_view');

        $this->data['halaman_aktif'] = 'sampel';
        $this->data['judul_halaman'] = 'Daftar Sampel Laboratorium';

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

            // Deteksi baris header (termasuk stacked header & sub-header) dan first data row secara dinamis
            $header_info = Excel_Importer::detect_header_and_data_start($rows);
            $header      = $header_info['header'];
            $first_data  = $header_info['first_data_idx'];

            // Pemetaan Kolom (31 fields) menggunakan Excel_Importer
            $mapping = Excel_Importer::map_headers($header);

            if ($mapping['status'] === FALSE) {
                $this->session->set_flashdata('pesan_gagal', 'Header Excel tidak sesuai template: ' . $mapping['pesan']);
                redirect('sampel/import');
            }

            $field_map  = $mapping['field_map'];
            $batch_data = array();
            $errors     = array();
            $user_id    = $this->session->userdata('user_id');

            // Validasi baris data (mulai baris data sampel pertama)
            for ($i = $first_data; $i < count($rows); $i++) {
                $row = $rows[$i];
                if (empty(array_filter($row))) {
                    continue; // Skip baris kosong
                }

                $row_num  = $i + 1;
                $row_data = array(
                    'status'     => 'Menunggu Pengujian',
                    'created_by' => $user_id
                );

                foreach ($field_map as $col_index => $db_field) {
                    $val = isset($row[$col_index]) ? trim($row[$col_index]) : NULL;

                    // Konversi tanggal jika pada kolom tanggal_sampling atau kedaluwarsa
                    if (($db_field === 'tanggal_sampling' || $db_field === 'kedaluwarsa') && $val !== NULL) {
                        $val = Excel_Importer::convert_excel_date($val);
                    }

                    $row_data[$db_field] = ($val !== '') ? $val : NULL;
                }

                // Cek apakah baris ini adalah footer / tanda tangan / non-data
                if (Excel_Importer::is_footer_or_non_data_row($row, $row_data)) {
                    continue; // Skip footer / signature block tanpa memicu validation error
                }

                // Validasi data wajib (nama_sampel) untuk baris data sampel aktual
                if (empty($row_data['nama_sampel'])) {
                    $errors[] = "Baris $row_num: Kolom 'Nama Sampel' wajib diisi.";
                }

                $batch_data[] = $row_data;
            }

            if (!empty($errors)) {
                $this->session->set_flashdata('import_errors', array_slice($errors, 0, 20));
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
                        'total_mapped'   => $mapping['total_mapped'],
                        'status'         => 'Menunggu Pengujian'
                    ));

                    $this->session->set_flashdata('pesan_sukses', "Berhasil meng-import $total_imported data sampel ke dalam sistem (Status: Menunggu Pengujian). Seluruh 31 field berhasil dipetakan.");
                    redirect('sampel');
                } else {
                    $this->session->set_flashdata('pesan_gagal', 'Gagal meng-import data sampel. Transaksi database di-rollback.');
                    redirect('sampel/import');
                }
            }
        }

        $this->data['halaman_aktif'] = 'sampel';
        $this->data['judul_halaman'] = 'Import Sampel via Excel';

        $this->muat_tampilan('sampel/import');
    }

    /**
     * Memetakan header Excel (31 kolom) ke nama field database (snake_case)
     * Menggunakan Excel_Importer library
     * 
     * @param array $header
     * @return array
     */
    private function _map_excel_header($header)
    {
        return Excel_Importer::map_headers($header);
    }
}
