<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Metode (Master Metode Monografi)
 * 
 * Menangani manajemen Master Monografi Metode Pengujian, Import Excel ribuan baris,
 * deteksi judul kelas terapi dinamis, preview validasi, dan mapping ke LCP.
 */
class Metode extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_Metode');
        $this->load->model('Model_Kelas_Terapi');
        $this->load->model('Model_Template');
    }

    /**
     * Halaman daftar Master Metode Monografi
     */
    public function index()
    {
        $this->cek_hak_akses('metode_view');

        $bidang = $this->input->get('bidang', TRUE);
        $status = $this->input->get('status', TRUE);

        $this->data['halaman_aktif'] = 'metode';
        $this->data['judul_halaman'] = 'Master Metode Pengujian (Monografi)';
        $this->data['breadcrumbs']   = array(
            array('label' => 'Master Metode', 'url' => site_url('metode'))
        );

        $this->data['bidang_aktif']  = $bidang;
        $this->data['status_aktif']  = $status;
        $this->data['daftar_metode'] = $this->Model_Metode->ambil_semua($bidang, $status);
        $this->data['daftar_lcp']    = $this->Model_Template->ambil_semua();

        $this->muat_tampilan('metode/index');
    }

    /**
     * Tambah Metode Monografi Manual
     */
    public function tambah()
    {
        $this->cek_hak_akses('metode_create');

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('nama_zat_aktif', 'Nama Zat Aktif', 'required|trim|max_length[150]', array(
                'required' => '%s wajib diisi.'
            ));
            $this->form_validation->set_rules('parameter_uji', 'Parameter Uji Kritis', 'required|trim|max_length[150]', array(
                'required' => '%s wajib diisi.'
            ));
            $this->form_validation->set_rules('bidang_pengujian', 'Bidang Pengujian', 'required|in_list[Kimia,Mikrobiologi]');

            if ($this->form_validation->run() === TRUE) {
                $data_simpan = array(
                    'nama_zat_aktif'   => $this->input->post('nama_zat_aktif', TRUE),
                    'jenis_sediaan'    => $this->input->post('jenis_sediaan', TRUE) ?: NULL,
                    'bentuk_sediaan'   => $this->input->post('bentuk_sediaan', TRUE) ?: NULL,
                    'pustaka_acuan'    => $this->input->post('pustaka_acuan', TRUE) ?: NULL,
                    'parameter_uji'    => $this->input->post('parameter_uji', TRUE),
                    'metode_analisa'   => $this->input->post('metode_analisa', TRUE) ?: NULL,
                    'persyaratan'      => $this->input->post('persyaratan', TRUE) ?: NULL,
                    'bidang_pengujian' => $this->input->post('bidang_pengujian', TRUE),
                    'kategori'         => $this->input->post('bidang_pengujian', TRUE),
                    'kelas_terapi_id'  => $this->input->post('kelas_terapi_id', TRUE) ?: NULL,
                    'template_id'      => $this->input->post('template_id', TRUE) ?: NULL,
                    'status'           => $this->input->post('status', TRUE) ?: 'Aktif'
                );

                $id = $this->Model_Metode->tambah($data_simpan);
                if ($id) {
                    $this->catat_audit('CREATE', 'Metode', array(
                        'id'             => $id,
                        'nama_zat_aktif' => $data_simpan['nama_zat_aktif'],
                        'parameter_uji'  => $data_simpan['parameter_uji']
                    ));

                    $this->session->set_flashdata('pesan_sukses', 'Metode monografi baru berhasil ditambahkan.');
                    redirect('metode');
                } else {
                    $this->session->set_flashdata('pesan_gagal', 'Gagal menambahkan data metode monografi.');
                }
            }
        }

        $this->data['halaman_aktif']       = 'metode';
        $this->data['judul_halaman']       = 'Tambah Metode Monografi';
        $this->data['breadcrumbs']         = array(
            array('label' => 'Master Metode', 'url' => site_url('metode')),
            array('label' => 'Tambah', 'url' => '#')
        );
        $this->data['daftar_kelas_terapi'] = $this->Model_Kelas_Terapi->ambil_aktif();
        $this->data['daftar_lcp']          = $this->Model_Template->ambil_semua();

        $this->muat_tampilan('metode/tambah');
    }

    /**
     * Edit Metode Monografi
     * 
     * @param int $id
     */
    public function edit($id = NULL)
    {
        $this->cek_hak_akses('metode_edit');

        $metode = $this->Model_Metode->ambil_by_id($id);
        if (!$metode) {
            show_404();
        }

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('nama_zat_aktif', 'Nama Zat Aktif', 'required|trim|max_length[150]');
            $this->form_validation->set_rules('parameter_uji', 'Parameter Uji Kritis', 'required|trim|max_length[150]');
            $this->form_validation->set_rules('bidang_pengujian', 'Bidang Pengujian', 'required|in_list[Kimia,Mikrobiologi]');

            if ($this->form_validation->run() === TRUE) {
                $data_update = array(
                    'nama_zat_aktif'   => $this->input->post('nama_zat_aktif', TRUE),
                    'jenis_sediaan'    => $this->input->post('jenis_sediaan', TRUE) ?: NULL,
                    'bentuk_sediaan'   => $this->input->post('bentuk_sediaan', TRUE) ?: NULL,
                    'pustaka_acuan'    => $this->input->post('pustaka_acuan', TRUE) ?: NULL,
                    'parameter_uji'    => $this->input->post('parameter_uji', TRUE),
                    'metode_analisa'   => $this->input->post('metode_analisa', TRUE) ?: NULL,
                    'persyaratan'      => $this->input->post('persyaratan', TRUE) ?: NULL,
                    'bidang_pengujian' => $this->input->post('bidang_pengujian', TRUE),
                    'kategori'         => $this->input->post('bidang_pengujian', TRUE),
                    'kelas_terapi_id'  => $this->input->post('kelas_terapi_id', TRUE) ?: NULL,
                    'template_id'      => $this->input->post('template_id', TRUE) ?: NULL,
                    'status'           => $this->input->post('status', TRUE) ?: 'Aktif'
                );

                if ($this->Model_Metode->ubah($id, $data_update)) {
                    $this->catat_audit('UPDATE', 'Metode', array(
                        'id'             => $id,
                        'nama_zat_aktif' => $data_update['nama_zat_aktif'],
                        'parameter_uji'  => $data_update['parameter_uji']
                    ));

                    $this->session->set_flashdata('pesan_sukses', 'Data metode monografi berhasil diperbarui.');
                    redirect('metode');
                } else {
                    $this->session->set_flashdata('pesan_gagal', 'Gagal memperbarui data metode.');
                }
            }
        }

        $this->data['halaman_aktif']       = 'metode';
        $this->data['judul_halaman']       = 'Edit Metode Monografi';
        $this->data['breadcrumbs']         = array(
            array('label' => 'Master Metode', 'url' => site_url('metode')),
            array('label' => 'Edit', 'url' => '#')
        );
        $this->data['metode']              = $metode;
        $this->data['daftar_kelas_terapi'] = $this->Model_Kelas_Terapi->ambil_aktif();
        $this->data['daftar_lcp']          = $this->Model_Template->ambil_semua();

        $this->muat_tampilan('metode/edit');
    }

    /**
     * Ubah status metode
     * 
     * @param int $id
     */
    public function status($id = NULL)
    {
        $this->cek_hak_akses('metode_edit');

        $metode = $this->Model_Metode->ambil_by_id($id);
        if (!$metode) {
            show_404();
        }

        $status_baru = ($metode['status'] === 'Aktif') ? 'Nonaktif' : 'Aktif';
        if ($this->Model_Metode->ubah_status($id, $status_baru)) {
            $this->catat_audit('STATUS_CHANGE', 'Metode', array(
                'id'          => $id,
                'status_baru' => $status_baru
            ));
            $this->session->set_flashdata('pesan_sukses', 'Status metode berhasil diubah menjadi ' . $status_baru . '.');
        } else {
            $this->session->set_flashdata('pesan_gagal', 'Gagal mengubah status metode.');
        }

        redirect('metode');
    }

    /**
     * Map template LCP secara instan melalui modal/AJAX/POST
     * 
     * @param int $id
     */
    public function mapping($id = NULL)
    {
        $this->cek_hak_akses('metode_edit');

        if ($this->input->method() !== 'post') {
            redirect('metode');
        }

        $template_id = $this->input->post('template_id', TRUE);
        if ($this->Model_Metode->set_template_mapping($id, $template_id)) {
            $this->session->set_flashdata('pesan_sukses', 'Pemetaan LCP berhasil diperbarui.');
        } else {
            $this->session->set_flashdata('pesan_gagal', 'Gagal memperbarui pemetaan LCP.');
        }

        redirect('metode');
    }

    /**
     * Halaman Analisis & Preview Auto-Mapping LCP per Kelompok Parameter Kanonis
     */
    public function automap()
    {
        $this->cek_hak_akses('metode_edit');

        $stats = $this->Model_Metode->analisis_automap();
        $daftar_lcp = $this->Model_Template->ambil_semua();

        $this->data['halaman_aktif']  = 'metode';
        $this->data['judul_halaman']  = 'Analisis & Auto-Mapping LCP Monografi';
        $this->data['breadcrumbs']    = array(
            array('label' => 'Master Metode', 'url' => site_url('metode')),
            array('label' => 'Auto-Mapping LCP', 'url' => '#')
        );
        $this->data['stats']          = $stats;
        $this->data['daftar_lcp']      = $daftar_lcp;

        $this->muat_tampilan('metode/automap');
    }

    /**
     * Terapkan Pemetaan Massal untuk Kelompok Parameter yang Disetujui Admin TI
     */
    public function terapkan_automap()
    {
        $this->cek_hak_akses('metode_edit');

        if ($this->input->method() !== 'post') {
            redirect('metode/automap');
        }

        $group_key        = $this->input->post('group_key', TRUE);
        $template_id      = (int)$this->input->post('template_id', TRUE);
        $overwrite_manual = ($this->input->post('overwrite_manual') === '1');

        if (empty($group_key) || empty($template_id)) {
            $this->session->set_flashdata('pesan_gagal', 'Kelompok parameter dan template LCP tujuan wajib dipilih.');
            redirect('metode/automap');
        }

        $res = $this->Model_Metode->terapkan_automap_kelompok($group_key, $template_id, $overwrite_manual);

        if ($res['sukses']) {
            $this->catat_audit('MASS_AUTOMAP_LCP', 'Metode', array(
                'group_key'        => $group_key,
                'template_id'      => $template_id,
                'updated_count'    => $res['updated_count'],
                'overwrite_manual' => $overwrite_manual
            ));

            $this->session->set_flashdata('pesan_sukses', 'Berhasil menerapkan pemetaan massal untuk kelompok "' . html_escape($group_key) . '" sebanyak ' . $res['updated_count'] . ' monografi.');
        } else {
            $this->session->set_flashdata('pesan_gagal', 'Gagal menerapkan pemetaan massal: ' . $res['error']);
        }

        redirect('metode/automap');
    }

    /**
     * Terapkan Seluruh Pemetaan Rekomendasi Otomatis (1-Klik Batch Auto-Mapping)
     */
    public function terapkan_semua_automap()
    {
        $this->cek_hak_akses('metode_edit');

        if ($this->input->method() !== 'post') {
            redirect('metode/automap');
        }

        $overwrite_manual = ($this->input->post('overwrite_manual') === '1');

        $res = $this->Model_Metode->terapkan_automap_semua_otomatis($overwrite_manual);

        if ($res['sukses']) {
            $this->catat_audit('MASS_AUTOMAP_ALL_RECOMMENDED', 'Metode', array(
                'updated_count'    => $res['updated_count'],
                'group_count'      => $res['group_count'],
                'overwrite_manual' => $overwrite_manual
            ));

            if ($res['updated_count'] > 0) {
                $this->session->set_flashdata('pesan_sukses', 'Hebat! Sebanyak ' . number_format($res['updated_count']) . ' monografi dari ' . $res['group_count'] . ' kelompok metode berhasil dipetakan secara otomatis ke Form LCP resmi dengan tingkat keyakinan 100%.');
            } else {
                $this->session->set_flashdata('pesan_sukses', $res['pesan'] ?? 'Seluruh monografi yang memenuhi kriteria otomatis sudah terpetakan sebelumnya.');
            }
        } else {
            $this->session->set_flashdata('pesan_gagal', 'Gagal menjalankan pemetaan otomatis massal: ' . $res['error']);
        }

        redirect('metode/automap');
    }

    /**
     * Hapus metode
     * 
     * @param int $id
     */
    public function hapus($id = NULL)
    {
        $this->cek_hak_akses('metode_delete');

        $metode = $this->Model_Metode->ambil_by_id($id);
        if (!$metode) {
            show_404();
        }

        if ($this->Model_Metode->hapus($id)) {
            $this->catat_audit('DELETE', 'Metode', array('id' => $id, 'nama' => $metode['nama_metode']));
            $this->session->set_flashdata('pesan_sukses', 'Data metode monografi berhasil dihapus.');
        } else {
            $this->session->set_flashdata('pesan_gagal', 'Metode tidak dapat dihapus karena sudah memiliki riwayat pengujian.');
        }

        redirect('metode');
    }

    /**
     * Halaman Upload & Parser Excel Master Metode Monografi (Multi-Kelas-Terapi)
     */
    public function import()
    {
        $this->cek_hak_akses('metode_import');

        if ($this->input->method() === 'post') {
            if (empty($_FILES['file_excel']['name'])) {
                $this->session->set_flashdata('pesan_gagal', 'Silakan pilih file Excel (.xlsx) monografi yang akan diunggah.');
                redirect('metode/import');
            }

            $ext = strtolower(pathinfo($_FILES['file_excel']['name'], PATHINFO_EXTENSION));
            if ($ext !== 'xlsx') {
                $this->session->set_flashdata('pesan_gagal', 'Format file tidak didukung. Harap unggah file berekstensi .xlsx.');
                redirect('metode/import');
            }

            $tmpPath = $_FILES['file_excel']['tmp_name'];
            $this->load->library('SimpleXLSX');

            $xlsx = SimpleXLSX::parse($tmpPath);
            if (!$xlsx) {
                $this->session->set_flashdata('pesan_gagal', 'Gagal membaca file Excel: ' . SimpleXLSX::parseError());
                redirect('metode/import');
            }

            $rows = $xlsx->rows();
            if (empty($rows) || count($rows) < 2) {
                $this->session->set_flashdata('pesan_gagal', 'File Excel kosong atau tidak memiliki baris data.');
                redirect('metode/import');
            }

            // Parsing State Machine: Deteksi Kelas Terapi & Header Kolom
            $current_kelas_terapi = 'KELAS TERAPI UMUM';
            $col_map              = array();
            $valid_rows           = array();
            $duplicate_rows       = array();
            $invalid_rows         = array();
            $detected_kt_set      = array();
            $existing_hash_lookup = $this->Model_Metode->ambil_semua_hash_lookup();
            $in_file_hash_lookup  = array();
            $total_read           = 0;

            foreach ($rows as $r_idx => $row) {
                $excel_row_num = $r_idx + 1;

                // 1. Cek apakah baris kosong total
                $non_empty_cells = array();
                foreach ($row as $ci => $cell) {
                    $val = trim((string)$cell);
                    if ($val !== '') {
                        $non_empty_cells[$ci] = $val;
                    }
                }
                if (empty($non_empty_cells)) {
                    continue; // Skip baris kosong
                }

                $row_str = implode(' ', $non_empty_cells);

                // 2. Deteksi Judul Kelompok Kelas Terapi
                // Pola: Baris berisi teks "KELAS TERAPI" atau memiliki nomor urut di awal seperti "1. KELAS TERAPI..."
                if (preg_match('/(?:^|\s)(KELAS\s+TERAPI\b.*)$/iu', $row_str, $matches)) {
                    $clean_kt = Model_Kelas_Terapi::bersihkan_nama_header($matches[1]);
                    if (!empty($clean_kt)) {
                        $current_kelas_terapi = $clean_kt;
                        $detected_kt_set[$clean_kt] = true;
                        continue; // Baris judul kelompok bukan baris data
                    }
                }

                // 3. Deteksi Baris Header Kolom (Bisa berulang jika dipecah per seksi kelas terapi)
                $norm_row_str = strtolower($row_str);
                if (strpos($norm_row_str, 'zat aktif') !== FALSE && strpos($norm_row_str, 'parameter') !== FALSE) {
                    $col_map = array(); // Reset/re-map kolom
                    foreach ($row as $ci => $cell_name) {
                        $raw_cell = trim((string)$cell_name);
                        $norm = strtoupper($raw_cell);
                        $norm = preg_replace('/[^\w\s]/u', ' ', $norm);
                        $norm = preg_replace('/\s+/', ' ', $norm);
                        $norm = trim($norm);

                        if ($norm === 'NO' || $norm === 'NOMOR') {
                            $col_map['no_sumber'] = $ci;
                        } elseif (strpos($norm, 'NAMA ZAT AKTIF') !== FALSE || $norm === 'ZAT AKTIF') {
                            $col_map['nama_zat_aktif'] = $ci;
                        } elseif (strpos($norm, 'JENIS SEDIAAN') !== FALSE || $norm === 'JENIS') {
                            $col_map['jenis_sediaan'] = $ci;
                        } elseif (strpos($norm, 'BENTUK SEDIAAN') !== FALSE || $norm === 'BENTUK') {
                            $col_map['bentuk_sediaan'] = $ci;
                        } elseif (strpos($norm, 'PUSTAKA ACUAN') !== FALSE || strpos($norm, 'PUSTAKA') !== FALSE || strpos($norm, 'ACUAN') !== FALSE) {
                            $col_map['pustaka_acuan'] = $ci;
                        } elseif (strpos($norm, 'PARAMETER UJI KRITIS SESUAI MONOGRAFI') !== FALSE || strpos($norm, 'PARAMETER UJI') !== FALSE || strpos($norm, 'PARAMETER') !== FALSE) {
                            $col_map['parameter_uji'] = $ci;
                        } elseif (strpos($norm, 'METODE ANALISA') !== FALSE || strpos($norm, 'METODE ANALISIS') !== FALSE || strpos($norm, 'METODE') !== FALSE) {
                            $col_map['metode_analisa'] = $ci;
                        } elseif (strpos($norm, 'PERSYARATAN') !== FALSE || strpos($norm, 'SPESIFIKASI') !== FALSE) {
                            $col_map['persyaratan'] = $ci;
                        } elseif (strpos($norm, 'BIDANG PENGUJIAN') !== FALSE || strpos($norm, 'BIDANG') !== FALSE) {
                            $col_map['bidang_pengujian'] = $ci;
                        } elseif (strpos($norm, 'KELAS TERAPI') !== FALSE) {
                            $col_map['kelas_terapi'] = $ci;
                        }
                    }
                    continue; // Baris header bukan baris data
                }

                // 4. Deteksi Baris Data
                // Pastikan kolom penting sudah terpetakan
                if (!isset($col_map['nama_zat_aktif']) || !isset($col_map['parameter_uji'])) {
                    continue; // Belum melewati baris header
                }

                $total_read++;

                $no_sumber = isset($col_map['no_sumber']) && isset($row[$col_map['no_sumber']]) ? trim((string)$row[$col_map['no_sumber']]) : '';
                $zat_aktif = isset($col_map['nama_zat_aktif']) && isset($row[$col_map['nama_zat_aktif']]) ? trim((string)$row[$col_map['nama_zat_aktif']]) : '';
                $param_uji = isset($col_map['parameter_uji']) && isset($row[$col_map['parameter_uji']]) ? trim((string)$row[$col_map['parameter_uji']]) : '';

                $jenis_sediaan  = isset($col_map['jenis_sediaan']) && isset($row[$col_map['jenis_sediaan']]) ? trim((string)$row[$col_map['jenis_sediaan']]) : '';
                $bentuk_sediaan = isset($col_map['bentuk_sediaan']) && isset($row[$col_map['bentuk_sediaan']]) ? trim((string)$row[$col_map['bentuk_sediaan']]) : '';
                $pustaka_acuan  = isset($col_map['pustaka_acuan']) && isset($row[$col_map['pustaka_acuan']]) ? trim((string)$row[$col_map['pustaka_acuan']]) : '';
                $metode_analisa = isset($col_map['metode_analisa']) && isset($row[$col_map['metode_analisa']]) ? trim((string)$row[$col_map['metode_analisa']]) : '';
                $persyaratan    = isset($col_map['persyaratan']) && isset($row[$col_map['persyaratan']]) ? trim((string)$row[$col_map['persyaratan']]) : '';
                $raw_bidang     = isset($col_map['bidang_pengujian']) && isset($row[$col_map['bidang_pengujian']]) ? trim((string)$row[$col_map['bidang_pengujian']]) : 'Kimia';

                // Resolusi Kelas Terapi: jika terdapat di kolom per-baris, pakai itu; jika kosong, pakai current_kelas_terapi dari judul kelompok
                $row_kt = isset($col_map['kelas_terapi']) && isset($row[$col_map['kelas_terapi']]) ? trim((string)$row[$col_map['kelas_terapi']]) : '';
                if (!empty($row_kt)) {
                    $resolved_kt = Model_Kelas_Terapi::bersihkan_nama_header($row_kt);
                } else {
                    $resolved_kt = $current_kelas_terapi;
                }
                if (!empty($resolved_kt)) {
                    $detected_kt_set[$resolved_kt] = true;
                }

                // Normalisasi Bidang Pengujian
                $bidang_norm = 'Kimia';
                $bidang_lower = strtolower($raw_bidang);
                if (strpos($bidang_lower, 'mikro') !== FALSE || $bidang_lower === 'm') {
                    $bidang_norm = 'Mikrobiologi';
                }

                // Bangun data baris monografi lengkap
                $item_record = array(
                    'excel_row'        => $excel_row_num,
                    'no_sumber'        => $no_sumber,
                    'nama_zat_aktif'   => $zat_aktif,
                    'jenis_sediaan'    => $jenis_sediaan,
                    'bentuk_sediaan'   => $bentuk_sediaan,
                    'pustaka_acuan'    => $pustaka_acuan,
                    'parameter_uji'    => $param_uji,
                    'metode_analisa'   => $metode_analisa,
                    'persyaratan'      => $persyaratan,
                    'bidang_pengujian' => $bidang_norm,
                    'raw_kelas_terapi' => $resolved_kt
                );

                // Validasi field wajib
                if ($zat_aktif === '' || $param_uji === '') {
                    $item_record['status'] = 'Invalid';
                    $item_record['alasan'] = 'NAMA ZAT AKTIF atau PARAMETER UJI KRITIS SESUAI MONOGRAFI kosong.';
                    $invalid_rows[] = $item_record;
                    continue;
                }

                // Hitung Hash Duplikat Komposit
                $comp_hash = Model_Metode::buat_composite_hash($item_record);

                // Cek duplikasi di internal file
                if (isset($in_file_hash_lookup[$comp_hash])) {
                    $item_record['status'] = 'Duplikat (File)';
                    $item_record['alasan'] = 'Duplikat komposit dalam file Excel yang sama.';
                    $duplicate_rows[] = $item_record;
                    continue;
                }

                // Cek duplikasi terhadap database existing
                if (isset($existing_hash_lookup[$comp_hash])) {
                    $item_record['status'] = 'Duplikat (DB)';
                    $item_record['alasan'] = 'Sudah ada dalam database. Dilewati sesuai aturan append-only.';
                    $duplicate_rows[] = $item_record;
                    continue;
                }

                // Lolos verifikasi
                $item_record['status'] = 'Valid';
                $item_record['alasan'] = 'Siap diimpor';
                $in_file_hash_lookup[$comp_hash] = true;
                $valid_rows[] = $item_record;
            }

            // Identifikasi status kelas terapi (Baru vs Sudah Ada)
            $kt_summary = array();
            foreach (array_keys($detected_kt_set) as $kt_nama) {
                $existing_kt = $this->Model_Kelas_Terapi->ambil_by_nama($kt_nama);
                $kt_summary[] = array(
                    'nama'   => $kt_nama,
                    'status' => $existing_kt ? 'Sudah Ada di Database' : 'Baru (Akan Dibuat Otomatis)'
                );
            }

            // Simpan hasil staging di sesi untuk konfirmasi pengguna
            $this->session->set_userdata('import_metode_staging', array(
                'valid_rows' => $valid_rows,
                'kt_detected'=> array_keys($detected_kt_set)
            ));

            $this->data['halaman_aktif']  = 'metode';
            $this->data['judul_halaman']  = 'Preview & Validasi Import Metode Monografi';
            $this->data['breadcrumbs']    = array(
                array('label' => 'Master Metode', 'url' => site_url('metode')),
                array('label' => 'Import', 'url' => site_url('metode/import')),
                array('label' => 'Preview', 'url' => '#')
            );
            $this->data['total_read']     = $total_read;
            $this->data['valid_rows']     = $valid_rows;
            $this->data['duplicate_rows'] = $duplicate_rows;
            $this->data['invalid_rows']   = $invalid_rows;
            $this->data['kt_summary']     = $kt_summary;

            $this->muat_tampilan('metode/preview_import');
            return;
        }

        $this->data['halaman_aktif'] = 'metode';
        $this->data['judul_halaman'] = 'Import Metode Monografi via Excel';
        $this->data['breadcrumbs']   = array(
            array('label' => 'Master Metode', 'url' => site_url('metode')),
            array('label' => 'Import Excel', 'url' => '#')
        );

        $this->muat_tampilan('metode/import');
    }

    /**
     * Konfirmasi commit batch import metode monografi
     */
    public function konfirmasi_import()
    {
        $this->cek_hak_akses('metode_import');

        if ($this->input->method() !== 'post') {
            redirect('metode/import');
        }

        $staging = $this->session->userdata('import_metode_staging');
        if (empty($staging) || empty($staging['valid_rows'])) {
            $this->session->set_flashdata('pesan_gagal', 'Sesi import kedaluwarsa atau tidak ada data yang valid. Silakan unggah kembali file Excel.');
            redirect('metode/import');
        }

        $valid_rows  = $staging['valid_rows'];
        $kt_detected = $staging['kt_detected'];

        // 1. Resolve / Buat Kelas Terapi otomatis jika belum ada
        $kelas_terapi_map = array();
        foreach ($kt_detected as $raw_kt) {
            $clean_kt = Model_Kelas_Terapi::bersihkan_nama_header($raw_kt);
            if (!empty($clean_kt)) {
                $kt_id = $this->Model_Kelas_Terapi->dapatkan_atau_buat($clean_kt);
                if ($kt_id) {
                    $kelas_terapi_map[$clean_kt] = $kt_id;
                }
            }
        }

        // 2. Jalankan batch append-only import metode
        $import_res = $this->Model_Metode->eksekusi_import_batch($valid_rows, $kelas_terapi_map);

        // Bersihkan sesi
        $this->session->unset_userdata('import_metode_staging');

        if ($import_res['sukses']) {
            $msg = "Berhasil mengimpor " . $import_res['imported'] . " data monografi metode baru.";
            if ($import_res['skipped'] > 0) {
                $msg .= " Sebanyak " . $import_res['skipped'] . " baris duplikat dilewati.";
            }

            $this->catat_audit('IMPORT', 'Metode', array(
                'imported' => $import_res['imported'],
                'skipped'  => $import_res['skipped']
            ));

            $this->session->set_flashdata('pesan_sukses', $msg);
            redirect('metode');
        } else {
            $this->session->set_flashdata('pesan_gagal', 'Gagal memproses import data: ' . implode(', ', $import_res['errors']));
            redirect('metode/import');
        }
    }
}
