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

        // Filter Komoditi (Default 'semua' untuk Admin TI & Petugas Sampel, atau komoditi aktif lab terkait)
        $role_id_aktif      = (int)$this->session->userdata('role_id');
        $komoditi_session   = $this->session->userdata('komoditi_aktif') ?: 'obat';
        $is_admin_workspace = ($komoditi_session === 'admin');

        $default_komoditi   = ($role_id_aktif === 2 || $is_admin_workspace) ? 'semua' : $komoditi_session;
        $komoditi_pilih     = $this->input->get('komoditi', TRUE) ?: $default_komoditi;
        $this->data['komoditi_aktif']     = $komoditi_pilih;
        $this->data['is_admin_workspace'] = $is_admin_workspace;

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
        if (!in_array($komoditi_pilihan, array('obat', 'pangan', 'herbal', 'kosmetik', 'suplemen'))) {
            $komoditi_pilihan = 'obat';
        }

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('nama_sampel', 'Nama Sampel', 'required|trim', array(
                'required' => '%s wajib diisi.'
            ));

            if ($this->form_validation->run() === TRUE) {
                $komoditi_post = $this->input->post('komoditi', TRUE) ?: 'obat';
                if (!in_array($komoditi_post, array('obat', 'pangan', 'herbal', 'kosmetik', 'suplemen'))) {
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
                        'petugas_sampling'    => $this->input->post('pangan_petugas_sampling', TRUE) ?: NULL,
                        'no_agenda'           => $this->input->post('pangan_no_agenda', TRUE) ?: NULL,
                        'alamat_sarana'       => $this->input->post('pangan_alamat_sarana', TRUE) ?: NULL,
                        'tanggal_produksi'    => $this->input->post('pangan_tanggal_produksi', TRUE) ?: NULL,
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
                    if (!empty($this->input->post('pangan_kategori', TRUE))) {
                        $data_simpan['kategori_sampel'] = $this->input->post('pangan_kategori', TRUE);
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
                } elseif ($komoditi_post === 'suplemen') {
                    $atribut_spesifik = array(
                        'nie_suplemen'       => $this->input->post('suplemen_nie', TRUE) ?: NULL,
                        'bentuk_sediaan'     => $this->input->post('suplemen_bentuk_sediaan', TRUE) ?: NULL,
                        'klaim_suplemen'     => $this->input->post('suplemen_klaim', TRUE) ?: NULL,
                        'target_pengujian'   => $this->input->post('suplemen_target_uji', TRUE) ?: NULL,
                        'produsen_suplemen'  => $this->input->post('suplemen_produsen', TRUE) ?: NULL,
                    );
                    if (!empty($this->input->post('suplemen_nie', TRUE))) {
                        $data_simpan['nomor_izin_edar'] = $this->input->post('suplemen_nie', TRUE);
                    }
                    if (!empty($this->input->post('suplemen_produsen', TRUE))) {
                        $data_simpan['nama_alamat_perusahaan'] = $this->input->post('suplemen_produsen', TRUE);
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

                if (!empty($komoditi_edit) && in_array($komoditi_edit, array('obat', 'pangan', 'herbal', 'kosmetik', 'suplemen'))) {
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
        if (!in_array($komoditi_pilihan, array('obat', 'pangan', 'herbal', 'kosmetik', 'suplemen'))) {
            $komoditi_pilihan = 'obat';
        }

        if ($this->input->method() === 'post') {
            $komoditi_import = $this->input->post('komoditi', TRUE) ?: 'obat';
            if (!in_array($komoditi_import, array('obat', 'pangan', 'herbal', 'kosmetik', 'suplemen'))) {
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
                    $sheet_names = $xlsx->sheetNames();
                    $matched_sheet_idx = null;
                    $detected_summaries = array();

                    // Cek seluruh sheet untuk menemukan sheet yang sesuai dengan target komoditi
                    foreach ($sheet_names as $sIdx => $sName) {
                        $sRows = $xlsx->rows($sIdx);
                        if (empty($sRows) || count($sRows) < 2) {
                            continue;
                        }
                        $det = Excel_Importer::detect_commodity_format($sRows, $sName);
                        $sheet_label = "Sheet " . ($sIdx + 1) . " ('" . htmlspecialchars($sName) . "'): " . Excel_Importer::get_commodity_label($det['detected']);
                        $detected_summaries[] = $sheet_label;

                        if ($det['detected'] === $komoditi_import) {
                            $matched_sheet_idx = $sIdx;
                            $rows = $sRows;
                            break;
                        }
                    }

                    if ($matched_sheet_idx === null) {
                        $target_label = Excel_Importer::get_commodity_label($komoditi_import);
                        $summary_str  = !empty($detected_summaries) ? implode(', ', $detected_summaries) : 'Format tidak dikenali';
                        $this->session->set_flashdata('pesan_gagal', "Format file Excel tidak sesuai dengan komoditi [{$target_label}]! Berkas yang diunggah terdeteksi berisi format: {$summary_str}. Pastikan Anda mengunggah template resmi sampling {$target_label}.");
                        redirect('sampel/import?komoditi=' . $komoditi_import);
                    }
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

                if (!empty($rows)) {
                    $val_check = Excel_Importer::validate_commodity_format($rows, $komoditi_import, 'CSV');
                    if (!$val_check['valid']) {
                        $this->session->set_flashdata('pesan_gagal', $val_check['pesan']);
                        redirect('sampel/import?komoditi=' . $komoditi_import);
                    }
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

                    // Konversi tanggal jika pada kolom tanggal_sampling, kedaluwarsa, atau tanggal_produksi
                    if (($db_field === 'tanggal_sampling' || $db_field === 'kedaluwarsa' || $db_field === 'spesifik:tanggal_produksi') && $val !== NULL) {
                        $val = Excel_Importer::convert_excel_date($val);
                    }

                    if (strpos($db_field, 'spesifik:') === 0) {
                        $key_spesifik = substr($db_field, 9);
                        $spesifik_row[$key_spesifik] = ($val !== '') ? $val : NULL;
                    } else {
                        $row_data[$db_field] = ($val !== '') ? $val : NULL;
                    }
                }

                // Gabungkan NIE Prefix (misal: POM TR, POM TI, POM SD, POM SI) dengan Nomor Izin Edar jika ada
                if (!empty($spesifik_row['nie_prefix']) && !empty($row_data['nomor_izin_edar'])) {
                    $row_data['nomor_izin_edar'] = trim($spesifik_row['nie_prefix'] . ' ' . $row_data['nomor_izin_edar']);
                } elseif (!empty($spesifik_row['nie_prefix']) && empty($row_data['nomor_izin_edar'])) {
                    $row_data['nomor_izin_edar'] = $spesifik_row['nie_prefix'];
                }

                // Fallback Surat Tugas dari metadata header file jika tidak ada di kolom tabel
                if (empty($row_data['surtug']) && !empty($header_info['surtug'])) {
                    $row_data['surtug'] = $header_info['surtug'];
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

        if (!in_array($komoditi, array('obat', 'pangan', 'herbal', 'kosmetik', 'suplemen'))) {
            $komoditi = 'obat';
        }

        require_once APPPATH . 'libraries/SimpleXLSXGen.php';

        $filename = 'Template_Import_Sampel_' . ucfirst($komoditi) . '_' . date('Ymd') . '.xlsx';
        $rows = array();

        if ($komoditi === 'pangan') {
            // Header Resmi BBPOM Bandar Lampung untuk Komoditi Pangan (Sesuai Berkas Asli)
            $rows[] = array('', 'Data Contoh Bulan ' . date('F Y'), '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');
            $rows[] = array('', '', '', '', '', '', '', '', '', '', 'BALAI BESAR POM BANDAR LAMPUNG', '', '', '', '', '', '', '', '', '', '', '');
            $rows[] = array('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');
            $rows[] = array('Petugas Sampling', 'No.', 'No', 'No Sampel', '', 'Tempat dan Tanggal Sampling', '', 'Tanggal Sampling', 'Kategori Sampel', 'Nama Sampel', 'NIE', 'Pabrik/ Importir', 'No Bets', 'Tanggal produksi', 'Komposisi', 'Tgl Kadaluarsa', 'Kemasan', 'Jumlah', '', '', '', '');
            $rows[] = array('', '', '', 'Manual', 'SIPT', 'Nama Sarana', 'alamat', '', '', '', '', '', '', '', '', '', '', 'Ki', 'Mi', 'Arsip', 'P', 'Total');
            $rows[] = array('ELFINA', '1', '78', 'P/BL/IV/26', '26.090.301.13.01.0078', 'Pasar Tugu', 'JL.Hayam Wuruk Bandar Lampung', date('Y-m-d'), '11.1.5 Gula kristal putih atau gula pasir, gula batu', 'Gulaku', 'MD 120867000100167', 'PT.Gula Putih Mataram Lampung Indonesia', '2A 23;07', date('Y-m-d', strtotime('-3 months')), 'gula tebu', date('Y-m-d', strtotime('+1 year')), 'Plastik 1 kg', '3', '0', '2', '1', '6');
            $rows[] = array('Asih', '2', '79', 'P/BL/IV/26', '26.090.301.13.01.0079', 'Aneka Sari Rasa', 'Jl. Ikan Kakap No.26 - 28, Pesawahan, Bandar Lampung', date('Y-m-d'), '15.3 HASIL OLAHAN PERIKANAN (makanan ringan berbasis ikan)', 'Kemplang Panggang Asli', 'PIRT 2021871040881 30', 'Ango Bandar Lampung', 'KP-0921', date('Y-m-d', strtotime('-1 month')), 'sagu, ikan, telur, garam, penyedap rasa', date('Y-m-d', strtotime('+6 months')), 'Plastik isi 10 pc', '3', '5', '2', '1', '11');
            $rows[] = array('ELFINA', '3', '80', 'P/BL/IV/26', '26.090.301.13.01.0080', 'Chandra Superstore', 'Jalan Hayam Wuruk Kebon Jeruk No.1, Bandar Lampung', date('Y-m-d'), '06.7 TEPUNG DAN HASIL OLAHANNYA (produk olahan beras)', 'Dodol Pandan Ny. Lie-Lie', 'PIRT 2051871031009-29', 'Ny Lie-Lie Bandar Lampung', 'DP-1002', date('Y-m-d', strtotime('-2 weeks')), 'Ketan, Gula, Santan, Pandan', date('Y-m-d', strtotime('+3 months')), 'Mika Plastik 120 g', '3', '5', '2', '1', '11');
        } elseif ($komoditi === 'herbal') {
            // Format Resmi Sampling OTSK (Obat Bahan Alam / Obat Tradisional) BBPOM Bandar Lampung
            $rows[] = array('', 'DATA CONTOH OBAT BAHAN ALAM BULAN ' . strtoupper(date('F Y')), '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');
            $rows[] = array('', '', 'NO', '', 'NAMA BARANG', 'BENTUK SEDIAAN', 'Jenis Sampel', 'KOMPOSISI ZAT BERKHASIAT', 'NAMA INDUSTRI', 'NIE', '', 'NO. BATCH', 'ED', 'KEMASAN', 'PENGAMBILAN', '', 'Jlh', '', '', '', '', '', 'KET');
            $rows[] = array('', '', '', '', '', '', '', '', '', '', '', '', '', '', 'TEMPAT', 'TANGGAL', 'K', 'M', 'R', 'P', 'A', 'J', '');
            $rows[] = array('', 'NO.', '1.0', '', '2.0', '3.0', '4.0', '5.0', '6.0', '7.0', '', '8.0', '9.0', '10.0', '11.0', '12.0', '13.0', '', '', '', '', '', '14.0');
            
            // Baris Data Riil Obat Bahan Alam BBPOM
            $rows[] = array('', '1', '106', '/OT/T-Ki-Mi/POM/LT/VI/26', 'RemAct', 'Kapsul', 'Import-Online', "Apium Graveolens Semen\nBoswellia Serrata Resin\nCurcuma Longa Rhizoma\nHarpagophytum Procumbens Radix\nSalix Alba Cortex\nUncaria Tomentosa Rhizoma\nZingiber Officinale Rhizoma", "Di Produksi Oleh : DOT GREEN LIFESCIENCES SDN BHD,KL\nDi Import oleh : Marion Sam, Jakarta-Indonesia", 'POM TI', '094340391', 'M015/2025', '06 / 2028', 'Dus, Botol@ 30 Kapsul', 'Apotek Rafael, Jl. Jend. Sudirman, Bandar Jaya, Lampung Tengah', date('d F Y'), '1', '1', '0', '0', '1', '3', '');
            $rows[] = array('', '2', '107', '/OT/T-Ki-Mi/POM/LT/VI/26', 'GOLDEN THROAT LOZENGES', 'Tablet', 'Import', "Canarium Album Fructus\nCane Sugar\nCitri Rubrum Exocarpium\nLonicera Japonica Flos\nMentha Haplocalycis Herba\nMomordica Grosvenori Fructus\nOleum Anisi Stelati\noleum eucalypti", "Diproduksi Oleh : Guangxi Jinsangzi Co.Ltd, Guangxi-China, China\nDiimport Oleh : Saras Subur Abadi, Jakarta-Indonesia", 'POM TI', '164550761', '250606', '05.06.2028', 'Dus, 1 Sachet @ 2 Blister @ 6 Tablet Hisap', 'Apotek Fajaria, Jl. Jend. Sudirman, Bandar Jaya, Lampung Tengah', date('d F Y'), '1', '2', '0', '0', '1', '4', '');
            $rows[] = array('', '3', '108', '/OT/T-Ki-Mi/POM/LT/VI/26', 'SANJIN WATERMELON FROST LOZENGES', 'Tablet', 'Import', "Citrulus Vulgaris\nMirabilite\nMirabilitum Citruli Preparatum\nOleum Menthae Dementholathum", "Diproduksi oleh : GUILIN SANJIN PHARMACEUTICAL CO LTD, CHINA.\nDi Import Oleh PT. Saras Subur Abadi, Jakarta -Indonesia", 'POM TI', '164459791', 'YN23031', '12-03-2027', 'Dus, Strip @ 12 Tablet', 'Apotek Fajaria, Jl. Jend. Sudirman, Bandar Jaya, Lampung Tengah', date('d F Y'), '1', '2', '0', '0', '1', '4', '');
            
            // Catatan Kaki & Tanda Tangan
            $rows[] = array('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'Bandar Lampung, ' . date('d F Y'), '', '', '', '', '', '', '');
            $rows[] = array('', '', 'Diterima oleh :', '', '', '', '', '', '', '', '', '', '', '', '', 'Koordinator OBAOKSK', '', '', '', '', '', '', '');
            $rows[] = array('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');
            $rows[] = array('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');
            $rows[] = array('', '', 'Surya Gatot Asmara', '', '', '', '', '', 'Disetujui oleh :', '', '', '', '', '', '', 'Yulia Rahmawati, S.Si.,Apt.,M.H.', '', '', '', '', '', '', '');
        } elseif ($komoditi === 'kosmetik') {
            // Format Resmi Sampling Kosmetik BBPOM Bandar Lampung (Sesuai Berkas Asli)
            $rows[] = array('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');
            $rows[] = array('', 'Lampiran Sampling Produk Kosmetik', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');
            $rows[] = array('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');
            $rows[] = array('', 'Bulan', ': ' . date('F'), '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');
            $rows[] = array('', 'No. Surat Tugas', ': PW.03.12.18B.10.26.1018, PW.03.12.18B.10.26.1020', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');
            $rows[] = array('', 'Tanggal Surat', ': ' . date('d F Y'), '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');
            $rows[] = array('', 'No. Kode Contoh', '', 'Nama Contoh', 'NIE', 'Kategori Sampel', 'Tipe Produk-Kategori', 'Metode Pengambilan', 'Tanggal Sampling', 'Komposisi', "Kemasan/\n  Netto", 'Bentuk Sediaan', 'Bets', 'ED', 'Jumlah', '', '', '', '', '', 'Tempat Sampling', 'Kab. / Kota', 'Pabrik/Importir', "Cluster/\n  Jenis Uji", 'Kondisi sampel');
            $rows[] = array('', '', '', '', '', '', '', '', '', '', '', '', '', '', 'K', 'Mi', 'R', 'P', 'A', 'T', '', '', '', '', '');
            
            // Baris Data Riil Kosmetik BBPOM
            $rows[] = array('', '1', '26.090.201.12.01.0371', 'KREEN Galactomyces 70 Day Cream', 'NA18190105263', 'Kosmetik beresiko tinggi - c. Sediaan perawatan wajah (krim siang, krim malam, moisturizer)', "01. Krim, emulsi, cair, cairan kental, gel, minyak untuk kulit (wajah, tangan, kaki, dan lain-lain)\n01.09 Krim siang (day cream)", 'Offline', date('d F Y'), 'Galactomyces Ferment Filtrate, Aqua Demineralisata, Ethylhexyl Methoxycinnamate, Titanium Dioxide, Cetyl Alcohol, Glyceryl Monostearate, Isopropyl Myristate, Stearic Acid, Phenoxyethanol, Glycerin, Triethanolamine, Xanthan Gum, Tocopheryl Acetate, BHT, Zinc Oxide', 'Pot, Dus / 20 g', 'Krim', '1125008 A', date('m Y', strtotime('+2 years')), '1.0', '2.0', '0.0', '1.0', '1.0', '5', "Ririnis Shop\nJl. Pulau Damar No. 203, Kel. Perum Way Kandis, Kec. Tanjung Senang, Kota Bandar Lampung\n0822-6928-1918", 'Kota Bandar Lampung', "Produced by\nPT. MULIA KAIROS INDUSTRI\nJl. Otonom Gang Masjid 1 No. 7 Cikupa, Kab. Tangerang, Indonesia 15710", 'C/Compliance', 'Baik');
            $rows[] = array('', '2', '26.090.201.12.01.0372', 'THE FACE Advanced Night Cream', 'NA18250112228', 'Kosmetik beresiko tinggi - c. Sediaan perawatan wajah (krim siang, krim malam, moisturizer)', "01. Krim, emulsi, cair, cairan kental, gel, minyak untuk kulit (wajah, tangan, kaki, dan lain-lain)/\n01.07 Krim malam (night cream)", 'Offline', date('d F Y'), 'Aqua, Niacinamide, Caprylic/Capric Triglyceride, Butylene Glycol, Glycerin, Cetyl Alcohol, Dimethicone, Allantoin, Dipropylene Glycol, Propylene Glycol, Phenoxyethanol, Curcuma Xanthorrhiza Root Extract, Tocopheryl Acetate, Sodium Hyaluronate', 'Pot, Dus / 30 g', 'Krim', '31461A', date('m Y', strtotime('+3 years')), '1.0', '2.0', '0.0', '1.0', '1.0', '5', "Ririnis Shop\nJl. Pulau Damar No. 203, Kel. Perum Way Kandis, Kec. Tanjung Senang, Kota Bandar Lampung\n0822-6928-1918", 'Kota Bandar Lampung', "Produced by :\nPT. Cressindo Kusuma, Jl. Kamal Muara III No. 2A, Penjaringan, Jakarta-Indonesia", 'C/Compliance', 'Baik');
            $rows[] = array('', '3', '26.090.201.12.01.0373', 'SANIYE EYESHADOW E0925 01#', 'NA11251200260', 'Kosmetik beresiko tinggi - b. Sediaan rias mata dan perawatan sekitar mata (eye shadow, pensil alis dan maskara)', "12. Sediaan rias mata, rias wajah, sediaan pembersih rias wajah dan mata\n12.02 Bayangan mata (eye shadow)", 'Offline', date('d F Y'), 'Talc, Mica, Magnesium Myristate, Kaolin, Mineral Oil, Cocos Nucifera (Coconut) Oil, Ethylhexyl Palmitate, Polyisobutene, Phenoxyethanol, Red Iron Oxide (CI 77491), Titanium Dioxide (CI 77891)', 'Case, Dus / 5 g', 'Serbuk kompak', 'SN60109', date('M.Y', strtotime('+4 years')), '2.0', '1.0', '0.0', '0.0', '1.0', '4', "Miss Glam\nJl. ZA. Pagar Alam No. 3A, Gedong Meneng, Kec. Rajabasa, Kota Bandar Lampung", 'Kota Bandar Lampung', "Imported: PT.Beauty Source Indonesia, Jakarta.\nManufacturer: Zhejiang Shinujo Cosmetics CO, LTD Made in P.R.C", 'C/Compliance', 'Baik');
            
            // Catatan Kaki & Tanda Tangan
            $rows[] = array('', '', 'T = Total', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');
            $rows[] = array('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');
            $rows[] = array('', '', 'Penerima Sampel', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'Bandar Lampung, ' . date('d F Y'), '', '', '', '', '');
            $rows[] = array('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'Petugas sampling', '', '', '', '', '');
            $rows[] = array('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '1. Sofia Masroh', '', '', '', '', '');
            $rows[] = array('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '2. Rico Erwinda Budi Raharja, S. Farm., Apt.', '', '', '', '', '');
        } elseif ($komoditi === 'suplemen') {
            // Format Resmi Sampling OTSK (Suplemen Kesehatan) BBPOM Bandar Lampung
            $rows[] = array('', 'DATA CONTOH SUPLEMEN KESEHATAN BULAN ' . strtoupper(date('F Y')), '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');
            $rows[] = array('', '', 'NO', '', 'NAMA BARANG', 'BENTUK SEDIAAN', 'Jenis Sampel', 'KOMPOSISI ZAT BERKHASIAT', 'NAMA INDUSTRI', 'NIE', '', 'NO. BATCH', 'ED', 'KEMASAN', 'PENGAMBILAN', '', 'Jlh', '', '', '', '', '', 'KET');
            $rows[] = array('', '', '', '', '', '', '', '', '', '', '', '', '', '', 'TEMPAT', 'TANGGAL', 'K', 'M', 'R', 'P', 'A', 'J', '');
            $rows[] = array('', 'NO.', '1.0', '', '2.0', '3.0', '4.0', '5.0', '6.0', '7.0', '', '8.0', '9.0', '10.0', '11.0', '12.0', '13.0', '', '', '', '', '', '14.0');
            
            // Baris Data Riil Suplemen Kesehatan BBPOM
            $rows[] = array('', '1', '27', '/SK/T-Ki/POM/LT/VI/26', 'NOURISH SKIN', 'Tablet Salut  selaput', 'Import Online', "Calcium, Calcium Carbonate Granular, Citrus Bioflavonoid, Collagen, Grape Seed Extract, Green Tea Leaf Extract, Spirulina, Standardized Marine Protein Extract, Vitamin A, B2, B6, C, E, K, Zinc", "Manufactured By :\nPERFORMANCE RESEARCH LABORATORIES Inc. USA.\nDiimport Oleh : PT. Pharos Indonesia, Jakarta-Indonesia", 'POM SI', '034508261', '7520870', 'ED.02 2027', 'Dus, alumunium foil @ 1, 2 & 4 blister @ 30 tablet salut selaput', 'Apotek Rafael, Jl. Jend.Sudirman, bandar Jaya-Lampung Tengah', date('d F Y'), '1', '0', '0', '0', '1', '2', '');
            $rows[] = array('', '2', '28', '/SK/T-Ki/POM/LT/VI/26', 'EVER E 250', 'Kapsul Lunak', 'Import Online', "Vitamin E, Vitamin E (d-alpha Tocopherol)", "Diproduksi Oleh : Catalent Japan KK, Shizuoka, Japan.\nDiimport Oleh : PT. KONIMEX, Sukoharjo-Jawa Tengah-Indonesia", 'POM SI', '204368101', 'JAN26A03', 'JUN 26', 'Botol plastik @ 30 kapsul lunak', 'Apotek Rafael, Jl. Jend.Sudirman, bandar Jaya-Lampung Tengah', date('d F Y'), '2', '2', '0', '0', '1', '5', '');
            $rows[] = array('', '3', '29', '/SK/T-Ki/POM/LT/VI/26', 'VITALONG C', 'Kapsul Lepas Berkala', 'Riwayat TMS- Online', "Vitamin C 500 mg", "Diproduksi Oleh : PT BERNOFARM, Sidoarjo-Indonesia", 'POM SD', '091338861', 'KPF25854', 'MAY 29', 'Dus, botol plastik @ 30 kapsul', 'Apotek Rafael, Jl. Jend.Sudirman, bandar Jaya-Lampung Tengah', date('d F Y'), '1', '0', '0', '0', '1', '2', '');
            
            // Catatan Kaki & Tanda Tangan
            $rows[] = array('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'Bandar Lampung, ' . date('d F Y'), '', '', '', '', '', '', '');
            $rows[] = array('', '', 'Diterima oleh :', '', '', '', '', '', '', '', '', '', '', '', '', 'Koordinator OBAOKSK', '', '', '', '', '', '', '');
            $rows[] = array('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');
            $rows[] = array('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');
            $rows[] = array('', '', 'Surya Gatot Asmara', '', '', '', '', '', 'Disetujui oleh :', '', '', '', '', '', '', 'Yulia Rahmawati, S.Si.,Apt.,M.H.', '', '', '', '', '', '', '');
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
