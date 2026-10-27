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

        // Filter Komoditi (Default 'obat' atau 'semua' sesuai pilihan)
        $komoditi_pilih = $this->input->get('komoditi', TRUE) ?: 'obat';
        $this->data['komoditi_aktif'] = $komoditi_pilih;

        $this->data['daftar_sampel']    = $this->Model_Sampel->ambil_semua($komoditi_pilih);
        $this->data['rekap_komoditi']   = $this->Model_Sampel->hitung_per_komoditi();

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

        $komoditi_pilihan = $this->input->get('komoditi', TRUE) ?: 'obat';
        if (!in_array($komoditi_pilihan, array('obat', 'pangan', 'herbal', 'kosmetik'))) {
            $komoditi_pilihan = 'obat';
        }

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('nama_sampel', 'Nama Sampel', 'required|trim', array(
                'required' => '%s wajib diisi.'
            ));

            if ($this->form_validation->run() === TRUE) {
                $komoditi_post = $this->input->post('komoditi', TRUE) ?: 'obat';
                if (!in_array($komoditi_post, array('obat', 'pangan', 'herbal', 'kosmetik'))) {
                    $komoditi_post = 'obat';
                }

                $data_simpan = array(
                    'komoditi'               => $komoditi_post,
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

                // Ekstraksi atribut spesifik per komoditi
                $atribut_spesifik = array();
                if ($komoditi_post === 'pangan') {
                    $atribut_spesifik = array(
                        'kategori_pangan'     => $this->input->post('pangan_kategori', TRUE) ?: NULL,
                        'nie_pangan'          => $this->input->post('pangan_nie', TRUE) ?: NULL,
                        'kode_produksi'       => $this->input->post('pangan_kode_produksi', TRUE) ?: NULL,
                        'kemasan_pangan'      => $this->input->post('pangan_kemasan', TRUE) ?: NULL,
                        'suhu_sampling'       => $this->input->post('pangan_suhu', TRUE) ?: NULL,
                        'volume_netto'        => $this->input->post('pangan_volume', TRUE) ?: NULL,
                        'target_pengujian'    => $this->input->post('pangan_parameter', TRUE) ?: NULL,
                        'produsen_pangan'     => $this->input->post('pangan_produsen', TRUE) ?: NULL,
                    );
                    if (!empty($this->input->post('pangan_nie', TRUE))) {
                        $data_simpan['nomor_izin_edar'] = $this->input->post('pangan_nie', TRUE);
                    }
                    if (!empty($this->input->post('pangan_produsen', TRUE))) {
                        $data_simpan['nama_alamat_perusahaan'] = $this->input->post('pangan_produsen', TRUE);
                    }
                } elseif ($komoditi_post === 'herbal') {
                    $atribut_spesifik = array(
                        'bentuk_sediaan'      => $this->input->post('herbal_bentuk_sediaan', TRUE) ?: NULL,
                        'nie_herbal'          => $this->input->post('herbal_nie', TRUE) ?: NULL,
                        'no_bets_exp'         => $this->input->post('herbal_bets', TRUE) ?: NULL,
                        'klaim_khasiat'       => $this->input->post('herbal_klaim', TRUE) ?: NULL,
                        'skrining_bko'        => $this->input->post('herbal_bko', TRUE) ?: NULL,
                        'simplisia_utama'     => $this->input->post('herbal_simplisia', TRUE) ?: NULL,
                    );
                    if (!empty($this->input->post('herbal_nie', TRUE))) {
                        $data_simpan['nomor_izin_edar'] = $this->input->post('herbal_nie', TRUE);
                    }
                    if (!empty($this->input->post('herbal_bets', TRUE))) {
                        $data_simpan['no_bets'] = $this->input->post('herbal_bets', TRUE);
                    }
                } elseif ($komoditi_post === 'kosmetik') {
                    $atribut_spesifik = array(
                        'notifikasi_bpom'     => $this->input->post('kosmetik_notifikasi', TRUE) ?: NULL,
                        'kategori_kosmetik'   => $this->input->post('kosmetik_kategori', TRUE) ?: NULL,
                        'asal_produk'         => $this->input->post('kosmetik_asal', TRUE) ?: NULL,
                        'target_uji_bahaya'   => $this->input->post('kosmetik_target_uji', TRUE) ?: NULL,
                        'produsen_kosmetik'   => $this->input->post('kosmetik_produsen', TRUE) ?: NULL,
                    );
                    if (!empty($this->input->post('kosmetik_notifikasi', TRUE))) {
                        $data_simpan['nomor_izin_edar'] = $this->input->post('kosmetik_notifikasi', TRUE);
                    }
                    if (!empty($this->input->post('kosmetik_produsen', TRUE))) {
                        $data_simpan['nama_alamat_perusahaan'] = $this->input->post('kosmetik_produsen', TRUE);
                    }
                }

                if (!empty(array_filter($atribut_spesifik))) {
                    $data_simpan['atribut_spesifik'] = json_encode($atribut_spesifik, JSON_UNESCAPED_UNICODE);
                }

                $sampel_id = $this->Model_Sampel->tambah($data_simpan);

                if ($sampel_id) {
                    $this->catat_audit('CREATE', 'Sampel', array(
                        'sampel_id'   => $sampel_id,
                        'komoditi'    => $data_simpan['komoditi'],
                        'nama_sampel' => $data_simpan['nama_sampel'],
                        'status'      => 'Menunggu Pengujian'
                    ));

                    $this->session->set_flashdata('pesan_sukses', 'Sampel berhasil diregistrasikan ke komoditi ' . strtoupper($data_simpan['komoditi']) . '.');
                    redirect('sampel?komoditi=' . $data_simpan['komoditi']);
                } else {
                    $this->session->set_flashdata('pesan_gagal', 'Gagal meregistrasikan sampel.');
                }
            }
        }

        $this->data['halaman_aktif']    = 'sampel';
        $this->data['judul_halaman']    = 'Registrasi Sampel Manual';
        $this->data['komoditi_pilihan'] = $komoditi_pilihan;

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
                $komoditi_edit = $this->input->post('komoditi', TRUE);
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

                if (!empty($komoditi_edit) && in_array($komoditi_edit, array('obat', 'pangan', 'herbal', 'kosmetik'))) {
                    $data_update['komoditi'] = $komoditi_edit;
                }

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

        $komoditi_pilihan = $this->input->get('komoditi', TRUE) ?: 'obat';
        if (!in_array($komoditi_pilihan, array('obat', 'pangan', 'herbal', 'kosmetik'))) {
            $komoditi_pilihan = 'obat';
        }

        if ($this->input->method() === 'post') {
            $komoditi_import = $this->input->post('komoditi', TRUE) ?: 'obat';
            if (!in_array($komoditi_import, array('obat', 'pangan', 'herbal', 'kosmetik'))) {
                $komoditi_import = 'obat';
            }

            if (empty($_FILES['file_excel']['name'])) {
                $this->session->set_flashdata('pesan_gagal', 'Silakan pilih file Excel (.xlsx / .csv) yang akan diunggah.');
                redirect('sampel/import?komoditi=' . $komoditi_import);
            }

            $ext = strtolower(pathinfo($_FILES['file_excel']['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, array('xlsx', 'csv'))) {
                $this->session->set_flashdata('pesan_gagal', 'Format file tidak valid. Hanya file ber-ekstensi .xlsx atau .csv yang diperbolehkan.');
                redirect('sampel/import?komoditi=' . $komoditi_import);
            }

            $tmpPath = $_FILES['file_excel']['tmp_name'];
            $rows    = array();

            if ($ext === 'xlsx') {
                $xlsx = SimpleXLSX::parse($tmpPath);
                if ($xlsx) {
                    $rows = $xlsx->rows();
                } else {
                    $this->session->set_flashdata('pesan_gagal', 'Error membaca file Excel: ' . $xlsx->getError());
                    redirect('sampel/import?komoditi=' . $komoditi_import);
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
                redirect('sampel/import?komoditi=' . $komoditi_import);
            }

            // Deteksi baris header (termasuk stacked header & sub-header) dan first data row secara dinamis
            $header_info = Excel_Importer::detect_header_and_data_start($rows);
            $header      = $header_info['header'];
            $first_data  = $header_info['first_data_idx'];

            // Pemetaan Kolom (31 fields) menggunakan Excel_Importer
            $mapping = Excel_Importer::map_headers($header);

            if ($mapping['status'] === FALSE) {
                $this->session->set_flashdata('pesan_gagal', 'Header Excel tidak sesuai template: ' . $mapping['pesan']);
                redirect('sampel/import?komoditi=' . $komoditi_import);
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
                    'komoditi'   => $komoditi_import,
                    'status'     => 'Menunggu Pengujian',
                    'created_by' => $user_id
                );

                $spesifik_row = array();

                foreach ($field_map as $col_index => $db_field) {
                    $val = isset($row[$col_index]) ? trim($row[$col_index]) : NULL;

                    // Konversi tanggal jika pada kolom tanggal_sampling atau kedaluwarsa
                    if (($db_field === 'tanggal_sampling' || $db_field === 'kedaluwarsa') && $val !== NULL) {
                        $val = Excel_Importer::convert_excel_date($val);
                    }

                    if (strpos($db_field, 'spesifik:') === 0) {
                        $key_spesifik = substr($db_field, 9);
                        $spesifik_row[$key_spesifik] = ($val !== '') ? $val : NULL;
                    } else {
                        $row_data[$db_field] = ($val !== '') ? $val : NULL;
                    }
                }

                if (!empty(array_filter($spesifik_row))) {
                    $row_data['atribut_spesifik'] = json_encode($spesifik_row, JSON_UNESCAPED_UNICODE);
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
                redirect('sampel/import?komoditi=' . $komoditi_import);
            }

            if (!empty($batch_data)) {
                // Eksekusi Database Transaction untuk keamanan import
                if ($this->Model_Sampel->tambah_batch($batch_data)) {
                    $total_imported = count($batch_data);

                    $this->catat_audit('IMPORT', 'Sampel', array(
                        'file_name'      => $_FILES['file_excel']['name'],
                        'komoditi'       => $komoditi_import,
                        'total_imported' => $total_imported,
                        'total_mapped'   => $mapping['total_mapped'],
                        'status'         => 'Menunggu Pengujian'
                    ));

                    $this->session->set_flashdata('pesan_sukses', "Berhasil meng-import $total_imported data sampel ke komoditi " . strtoupper($komoditi_import) . " (Status: Menunggu Pengujian). Seluruh 31 field berhasil dipetakan.");
                    redirect('sampel?komoditi=' . $komoditi_import);
                } else {
                    $this->session->set_flashdata('pesan_gagal', 'Gagal meng-import data sampel. Transaksi database di-rollback.');
                    redirect('sampel/import?komoditi=' . $komoditi_import);
                }
            }
        }

        $this->data['halaman_aktif']    = 'sampel';
        $this->data['judul_halaman']    = 'Import Sampel via Excel';
        $this->data['komoditi_pilihan'] = $komoditi_pilihan;

        $this->muat_tampilan('sampel/import');
    }

    /**
     * Memetakan header Excel (31 kolom) ke nama field database (snake_case)
     * Menggunakan Excel_Importer library
     * 
     * @param array $header
     * @return array
     */
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

    /**
     * Mengunduh file template Excel (.xlsx) baku & rapi untuk import sampel per komoditi
     * 
     * @param string $komoditi
     */
    public function download_template($komoditi = 'obat')
    {
        $this->cek_hak_akses('sampel_import');

        if (!in_array($komoditi, array('obat', 'pangan', 'herbal', 'kosmetik'))) {
            $komoditi = 'obat';
        }

        require_once APPPATH . 'libraries/SimpleXLSXGen.php';

        $filename = 'Template_Import_Sampel_' . ucfirst($komoditi) . '_' . date('Ymd') . '.xlsx';
        $rows = array();

        if ($komoditi === 'pangan') {
            $rows[] = array('No', 'Nama Sampel', 'Kategori Pangan', 'Nomor Izin Edar', 'Kode Produksi', 'Kategori Sarana', 'Nama Sarana', 'Kabupaten Kota', 'Tanggal Sampling', 'Surtug', 'Jenis Kemasan', 'Suhu Sampling', 'Volume Netto', 'Target Pengujian', 'Nama Alamat Produsen');
            $rows[] = array('1', 'Susu UHT Cokelat 250ml', '01.0 Produk Susu dan Analognya', 'BPOM RI MD 201234567890', 'EXP 2026-11-20', 'Ritel Modern', 'Supermarket Sejahtera', 'Bandar Lampung', date('Y-m-d'), 'ST/081/BBPOM/2026', 'Tetra Pak', 'Suhu Ruang (25-30 °C)', '250 ml', 'Uji ALT, Salmonella, Cemaran Logam Timbal', 'PT Industri Susu Nusantara, Tangerang');
            $rows[] = array('2', 'Air Mineral Dalam Kemasan 600ml', '14.0 Minuman Siap Konsumsi / AMDK', 'BPOM RI MD 209876543210', 'EXP 2027-01-15', 'Distributor', 'Gudang Tirta Jaya', 'Lampung Selatan', date('Y-m-d'), 'ST/081/BBPOM/2026', 'Botol PET', 'Suhu Ruang (25-30 °C)', '600 ml', 'Uji Mikroba Pseudomonas aeruginosa & Coliform', 'PT Sumber Air Sejuk, Lampung Selatan');
        } elseif ($komoditi === 'herbal') {
            $rows[] = array('No', 'Nama Sampel', 'Bentuk Sediaan', 'Nomor Izin Edar', 'No Bets Exp', 'Kategori Sarana', 'Nama Sarana', 'Kabupaten Kota', 'Tanggal Sampling', 'Surtug', 'Klaim Khasiat', 'Skrining BKO', 'Simplisia Utama', 'Nama Alamat Produsen');
            $rows[] = array('1', 'Kapsul Ekstrak Temulawak Plus', 'Kapsul Herbal', 'POM TR 192345671', 'BETS-09 EXP 2027-08', 'Toko Obat Herbal', 'Toko Berkah Sehat', 'Lampung Selatan', date('Y-m-d'), 'ST/082/BBPOM/2026', 'Membantu memelihara fungsi hati dan daya tahan tubuh', 'Skrining Parasetamol dan Deksametason', 'Curcuma xanthorrhiza rhizoma 500mg', 'CV Alami Sejahtera, Sukoharjo');
            $rows[] = array('2', 'Cairan Obat Dalam Pegal Linu', 'Cairan Obat Dalam / Jamu Cair', 'POM TR 203612891', 'BETS-02 EXP 2026-10', 'Depot Jamu Tradisional', 'Depot Jamu Mbok Sri', 'Bandar Lampung', date('Y-m-d'), 'ST/082/BBPOM/2026', 'Membantu meredakan pegal linu dan nyeri sendi', 'Skrining Fenilbutazon dan Deksametason', 'Zingiberis officinale, Piper retrofractum', 'PJ Herbal Nusantara, Solo');
        } elseif ($komoditi === 'kosmetik') {
            $rows[] = array('No', 'Nama Sampel', 'Kategori Kosmetik', 'Nomor Notifikasi', 'Bentuk Sediaan', 'Asal Produk', 'Kategori Sarana', 'Nama Sarana', 'Kabupaten Kota', 'Tanggal Sampling', 'Surtug', 'Target Uji Bahan Berbahaya', 'Nama Alamat Produsen');
            $rows[] = array('1', 'Brightening Day Cream SPF 30', 'Perawatan Kulit (Skincare: Serum, Krim, Toner)', 'NA18240105678', 'Krim Siang', 'Produksi Dalam Negeri (Lokal)', 'Klinik Kecantikan', 'Glow Estetika Center', 'Metro', date('Y-m-d'), 'ST/083/BBPOM/2026', 'Uji Merkuri (Hg) dan Hidrokuinon', 'PT Kosmetika Prima Indonesia, Bandung');
            $rows[] = array('2', 'Matte Velvet Lip Cream Shade 05', 'Riasan Wajah / Makeup (Lipstik, Bedak, Foundation)', 'NA18241300999', 'Lip Cream Cair', 'Produksi Dalam Negeri (Lokal)', 'Toko Kosmetik', 'Toko Cantik Jelita', 'Bandar Lampung', date('Y-m-d'), 'ST/083/BBPOM/2026', 'Uji Pewarna Merah K3 / K10 dan Timbal (Pb)', 'PT Beauty Pesona Indonesia, Tangerang');
        } else {
            // Header Baku Obat (31 Kolom)
            $rows[] = array(
                'No', 'Kategori Sampel', 'Sub Kategori', 'Jenis Kelas Terapi', 'Kategori Sarana', 'Nama Sarana',
                'Kabupaten Kota', 'Tanggal Sampling', 'Kode Sampel Manual', 'No SIPT', 'Nama Sampel',
                'Nomor Izin Edar', 'Kondisi Produk', 'No Bets', 'Kedaluwarsa', 'Kemasan',
                'Nama Alamat Perusahaan', 'Komposisi', 'Jumlah Kimia', 'Jumlah Mikro', 'Jumlah Arsip',
                'Jumlah Penandaan', 'Jumlah Total', 'Penyimpanan', 'Penandaan', 'Harga', 'TIE', 'MK', 'TMK',
                'Surtug', 'Balai Penguji'
            );
            $rows[] = array(
                '1', 'Targeted', 'Obat Keras', 'Analgesik-Antipiretik', 'Apotek', 'Apotek Sehat Sentosa',
                'Bandar Lampung', date('Y-m-d'), 'K-OBT-001/2026', 'SIPT-998811', 'Parasetamol Tablet 500 mg',
                'DKL9812345610A1', 'Baik', 'B9801A', date('Y-m-d', strtotime('+2 years')), 'Dus, 10 blister @ 10 tablet',
                'PT Farmasi Jaya, Jakarta', 'Paracetamol 500 mg', '20', '0', '10',
                '1', '31', 'Suhu Ruang', 'MK', '15000', 'TIE', 'MK', 'TMK',
                'ST/099/BBPOM/2026', 'Balai Besar POM di Bandar Lampung'
            );
        }

        $xlsx = SimpleXLSXGen::fromArray($rows, ucfirst($komoditi));
        $xlsx->downloadAs($filename);
        exit;
    }
}
