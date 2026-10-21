<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Pengujian Laboratorium
 * 
 * Menangani Antrean Pengujian (Queue), Self-Assignment, Pemilihan Metode, Dynamic Form Rendering,
 * Penyimpanan Hasil Uji, Transisi Status ("Menunggu Verifikasi"), & Pembentukan Laporan Hasil Uji PDF.
 */
class Pengujian extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_Pengujian');
        $this->load->model('Model_Sampel');
        $this->load->model('Model_Metode');
        $this->load->model('Model_Template');
        $this->load->model('Model_Pengguna');
        $this->load->model('Model_Alat');
        $this->load->model('Model_Reagen');
        $this->load->library('Laporan_PDF');
    }

    /**
     * Halaman Antrean Pengujian (Queue)
     * Status sampel: "Menunggu Pengujian"
     */
    public function index()
    {
        $this->antrean();
    }

    public function antrean()
    {
        $this->cek_hak_akses('pengujian_view');

        $this->data['halaman_aktif'] = 'pengujian_antrean';
        $this->data['judul_halaman'] = 'Antrean Pengujian Laboratorium';

        $this->data['antrean_sampel'] = $this->Model_Pengujian->ambil_antrean();
        $this->data['sedang_diuji']   = $this->Model_Pengujian->ambil_dalam_pengujian($this->session->userdata('user_id'));

        $this->muat_tampilan('pengujian/antrean');
    }

    /**
     * Klaim Sampel Mandiri (Self-Assignment)
     * Redirects to Pemilihan Jenis Pengujian (Step 4)
     * 
     * @param int $sample_id
     */
    public function klaim($sample_id = NULL)
    {
        $this->cek_hak_akses('pengujian_assign');

        $penguji_id = $this->session->userdata('user_id');

        // Jika sampel sudah memiliki sesi pengujian aktif milik penguji ini, arahkan langsung
        $sesi_aktif = $this->Model_Pengujian->ambil_sesi_by_sample($sample_id);
        if ($sesi_aktif && $sesi_aktif['penguji_id'] == $penguji_id && $sesi_aktif['status'] === 'Sedang Diuji') {
            redirect('pengujian/proses_sesi/' . $sesi_aktif['id']);
        }

        if ($this->Model_Pengujian->klaim_sampel($sample_id, $penguji_id)) {
            $this->catat_audit('ASSIGN', 'Pengujian', array(
                'sample_id'  => $sample_id,
                'penguji_id' => $penguji_id,
                'status'     => 'Sedang Diuji'
            ));

            $this->session->set_flashdata('pesan_sukses', 'Sampel berhasil diklaim. Silakan pilih jenis pengujian.');
            redirect('pengujian/pilih_jenis/' . $sample_id);
        } else {
            $this->session->set_flashdata('pesan_gagal', 'Gagal mengklaim sampel (sampel mungkin sudah diklaim atau berstatus lain).');
            redirect('pengujian/antrean');
        }
    }

    /**
     * STEP 4 — Pemilihan Jenis Pengujian (Kimia / Mikrobiologi)
     * 
     * @param int $sample_id
     */
    public function pilih_jenis($sample_id = NULL)
    {
        $this->cek_hak_akses('pengujian_input');

        $sampel = $this->Model_Sampel->ambil_by_id($sample_id);
        if (!$sampel) {
            show_404();
        }

        if ($sampel['status'] !== 'Sedang Diuji') {
            $this->session->set_flashdata('pesan_gagal', 'Sampel tidak berstatus "Sedang Diuji". Klaim sampel terlebih dahulu.');
            redirect('pengujian/antrean');
        }

        if ($this->input->method() === 'post') {
            $jenis_pengujian = $this->input->post('jenis_pengujian', TRUE);
            if (!in_array($jenis_pengujian, array('Kimia', 'Mikrobiologi'), TRUE)) {
                $this->session->set_flashdata('pesan_gagal', 'Jenis pengujian wajib dipilih (Kimia atau Mikrobiologi).');
                redirect('pengujian/pilih_jenis/' . $sample_id);
            }

            redirect('pengujian/pilih_form/' . $sample_id . '?jenis=' . urlencode($jenis_pengujian));
        }

        $this->data['halaman_aktif'] = 'pengujian_antrean';
        $this->data['judul_halaman'] = 'Pilih Jenis Pengujian';
        $this->data['breadcrumbs']   = array(
            array('label' => 'Pengujian', 'url' => site_url('pengujian')),
            array('label' => 'Pilih Jenis Pengujian', 'url' => '#')
        );
        $this->data['sampel']        = $sampel;

        $this->muat_tampilan('pengujian/pilih_jenis');
    }

    /**
     * STEP 5 — Pemilihan Multiple Form Template Sesuai Kategori
     * 
     * @param int $sample_id
     */
    /**
     * STEP 5 — Pemilihan Zat Aktif Monografi & Parameter Uji Kritis Sesuai Monografi
     * Workflow: Sampel -> Bidang (Kimia/Mikro) -> Tepat Satu Zat Aktif -> Pilih Parameter Valid Ber-LCP
     *
     * @param int $sample_id
     */
    public function pilih_form($sample_id = NULL)
    {
        $this->cek_hak_akses('pengujian_input');

        $sampel = $this->Model_Sampel->ambil_by_id($sample_id);
        if (!$sampel) {
            show_404();
        }

        $jenis_pengujian = $this->input->get('jenis', TRUE);
        if (!in_array($jenis_pengujian, array('Kimia', 'Mikrobiologi'), TRUE)) {
            $this->session->set_flashdata('pesan_gagal', 'Jenis pengujian tidak valid.');
            redirect('pengujian/pilih_jenis/' . $sample_id);
        }

        // Ambil daftar unik Zat Aktif monografi untuk bidang ini
        $daftar_zat_aktif = $this->Model_Metode->ambil_daftar_zat_aktif($jenis_pengujian);

        // Ambil zat aktif terpilih dari query string jika ada
        $zat_aktif_terpilih = $this->input->get('zat_aktif', TRUE);
        $parameter_monografi = array();

        if (!empty($zat_aktif_terpilih)) {
            $parameter_monografi = $this->Model_Metode->ambil_parameter_by_zat_aktif($zat_aktif_terpilih, $jenis_pengujian);
        }

        $this->load->model('Model_Template');
        $daftar_template_bidang = $this->Model_Template->ambil_aktif($jenis_pengujian);

        $this->data['halaman_aktif']          = 'pengujian_antrean';
        $this->data['judul_halaman']          = 'Pilih Zat Aktif & Parameter Monografi (' . $jenis_pengujian . ')';
        $this->data['breadcrumbs']            = array(
            array('label' => 'Pengujian', 'url' => site_url('pengujian')),
            array('label' => 'Pilih Parameter', 'url' => '#')
        );
        $this->data['sampel']                 = $sampel;
        $this->data['jenis_pengujian']        = $jenis_pengujian;
        $this->data['daftar_zat_aktif']       = $daftar_zat_aktif;
        $this->data['zat_aktif_terpilih']     = $zat_aktif_terpilih;
        $this->data['parameter_monografi']    = $parameter_monografi;
        $this->data['daftar_template_bidang'] = $daftar_template_bidang;

        $this->muat_tampilan('pengujian/pilih_form');
    }

    /**
     * STEP 6 & 7 — Inisialisasi Sesi Pengujian Berdasarkan Parameter Monografi Terpilih
     * Memastikan 1 sesi = 1 Zat Aktif utama, dan setiap parameter terpilih memiliki template LCP yang valid.
     * 
     * @param int $sample_id
     */
    public function mulai_sesi($sample_id = NULL)
    {
        $this->cek_hak_akses('pengujian_input');

        if ($this->input->method() !== 'post') {
            redirect('pengujian/antrean');
        }

        $sampel = $this->Model_Sampel->ambil_by_id($sample_id);
        if (!$sampel) {
            show_404();
        }

        $jenis_pengujian   = $this->input->post('jenis_pengujian', TRUE);
        $nama_zat_aktif    = $this->input->post('nama_zat_aktif', TRUE);
        $selected_methods  = $this->input->post('method_ids', TRUE);

        // 1. Validasi Bidang Pengujian
        if (empty($jenis_pengujian) || !in_array($jenis_pengujian, array('Kimia', 'Mikrobiologi'), TRUE)) {
            $this->session->set_flashdata('pesan_gagal', 'Jenis pengujian wajib dipilih secara valid.');
            redirect('pengujian/pilih_jenis/' . $sample_id);
        }

        // 2. Validasi Tepat Satu Zat Aktif Per Sesi
        if (empty($nama_zat_aktif)) {
            $this->session->set_flashdata('pesan_gagal', 'Satu sampel wajib memilih tepat satu zat aktif utama per sesi pengujian.');
            redirect('pengujian/pilih_form/' . $sample_id . '?jenis=' . urlencode($jenis_pengujian));
        }

        // 3. Validasi Minimal 1 Parameter Dipilih
        if (empty($selected_methods) || !is_array($selected_methods)) {
            $this->session->set_flashdata('pesan_gagal', 'Anda wajib memilih minimal 1 parameter uji kritis monografi.');
            redirect('pengujian/pilih_form/' . $sample_id . '?jenis=' . urlencode($jenis_pengujian) . '&zat_aktif=' . urlencode($nama_zat_aktif));
        }

        $custom_template_ids = $this->input->post('custom_template_ids', TRUE);
        if (!is_array($custom_template_ids)) {
            $custom_template_ids = array();
        }

        // 4. Validasi Eksplisit: Setiap parameter wajib cocok dengan Zat Aktif, Bidang, dan memiliki mapping LCP valid
        $valid_items_to_create = array();

        foreach ($selected_methods as $m_id) {
            $metode_item = $this->Model_Metode->ambil_by_id($m_id);

            if (!$metode_item) {
                $this->session->set_flashdata('pesan_gagal', 'Metode tidak ditemukan dalam database.');
                redirect('pengujian/pilih_form/' . $sample_id . '?jenis=' . urlencode($jenis_pengujian) . '&zat_aktif=' . urlencode($nama_zat_aktif));
            }

            // Validasi konsistensi Zat Aktif
            if ($metode_item['nama_zat_aktif'] !== $nama_zat_aktif) {
                $this->session->set_flashdata('pesan_gagal', 'Seluruh parameter dalam 1 sesi harus berasal dari zat aktif yang sama (' . html_escape($nama_zat_aktif) . ').');
                redirect('pengujian/pilih_form/' . $sample_id . '?jenis=' . urlencode($jenis_pengujian) . '&zat_aktif=' . urlencode($nama_zat_aktif));
            }

            // Validasi kecocokan bidang
            if ($metode_item['bidang_pengujian'] !== $jenis_pengujian) {
                $this->session->set_flashdata('pesan_gagal', 'Parameter bertentangan dengan bidang pengujian sesi (' . html_escape($jenis_pengujian) . ').');
                redirect('pengujian/pilih_form/' . $sample_id . '?jenis=' . urlencode($jenis_pengujian) . '&zat_aktif=' . urlencode($nama_zat_aktif));
            }

            // Dapatkan template_id: gunakan template bawaan metode atau custom yang dipilih analis
            $chosen_template_id = !empty($metode_item['template_id']) ? $metode_item['template_id'] : (isset($custom_template_ids[$m_id]) ? (int)$custom_template_ids[$m_id] : NULL);

            if (empty($chosen_template_id)) {
                $this->session->set_flashdata('pesan_gagal', 'Parameter "' . html_escape($metode_item['parameter_uji']) . '" belum memiliki form LCP. Silakan pilih form LCP yang sesuai.');
                redirect('pengujian/pilih_form/' . $sample_id . '?jenis=' . urlencode($jenis_pengujian) . '&zat_aktif=' . urlencode($nama_zat_aktif));
            }

            // Validasi keamanan: pastikan template valid, aktif, dan cocok dengan kategori bidang
            $this->load->model('Model_Template');
            $tpl_check = $this->Model_Template->ambil_by_id($chosen_template_id);
            if (!$tpl_check || $tpl_check['status'] !== 'Aktif' || $tpl_check['kategori'] !== $jenis_pengujian) {
                $this->session->set_flashdata('pesan_gagal', 'Template LCP yang dipilih untuk parameter "' . html_escape($metode_item['parameter_uji']) . '" tidak valid atau tidak cocok dengan bidang ' . html_escape($jenis_pengujian) . '.');
                redirect('pengujian/pilih_form/' . $sample_id . '?jenis=' . urlencode($jenis_pengujian) . '&zat_aktif=' . urlencode($nama_zat_aktif));
            }

            $valid_items_to_create[] = array(
                'method_id'   => $metode_item['id'],
                'template_id' => $chosen_template_id
            );
        }

        $penguji_id = $this->session->userdata('user_id');

        $session_id = $this->Model_Pengujian->buat_sesi_pengujian($sample_id, $penguji_id, $jenis_pengujian, $valid_items_to_create);

        if ($session_id) {
            $this->catat_audit('START_TESTING_SESSION', 'Pengujian', array(
                'testing_session_id' => $session_id,
                'sample_id'          => $sample_id,
                'jenis_pengujian'    => $jenis_pengujian,
                'nama_zat_aktif'     => $nama_zat_aktif,
                'parameter_count'    => count($valid_items_to_create),
                'penguji_id'         => $penguji_id
            ));

            $this->session->set_flashdata('pesan_sukses', 'Sesi pengujian (' . $jenis_pengujian . ' - ' . html_escape($nama_zat_aktif) . ') berhasil dimulai dengan ' . count($valid_items_to_create) . ' parameter monografi.');
            redirect('pengujian/proses_sesi/' . $session_id);
        } else {
            $this->session->set_flashdata('pesan_gagal', 'Gagal membuat sesi pengujian.');
            redirect('pengujian/antrean');
        }
    }

    /**
     * Halaman Pengisian Hasil Multiple Form Sesi Pengujian (LCP)
     * Terintegrasi dengan Master Alat Laboratorium & Master Reagen
     * 
     * @param int $session_id
     */
    public function proses_sesi($session_id = NULL)
    {
        $this->cek_hak_akses('pengujian_input');

        $sesi = $this->Model_Pengujian->ambil_sesi_by_id($session_id);
        if (!$sesi) {
            show_404();
        }

        // Ownership & authorization check
        if ($sesi['penguji_id'] != $this->session->userdata('user_id')) {
            $this->session->set_flashdata('pesan_gagal', 'Anda tidak berhak mengakses sesi pengujian ini (ownership mismatch).');
            redirect('pengujian/antrean');
        }

        $forms = $this->Model_Pengujian->ambil_hasil_by_session($session_id);

        if ($this->input->method() === 'post') {
            $forms_input = $this->input->post('forms', TRUE);
            if (!is_array($forms_input)) {
                $forms_input = array();
            }

            // Tangani upload file pengujian (misal: foto hasil pengamatan reaksi warna)
            if (!empty($_FILES)) {
                $upload_dir = FCPATH . 'uploads/pengujian/';
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, TRUE);
                }

                foreach ($_FILES as $file_field => $file_info) {
                    if (!empty($file_info['name']) && $file_info['error'] === UPLOAD_ERR_OK) {
                        // Pattern name: file_{form_id}_{field_id}
                        if (preg_match('/^file_(\d+)_(.+)$/', $file_field, $matches)) {
                            $f_id = (int)$matches[1];
                            $fld_id = $matches[2];

                            $ext = strtolower(pathinfo($file_info['name'], PATHINFO_EXTENSION));
                            $allowed_exts = array('jpg', 'jpeg', 'png', 'gif', 'pdf');
                            if (in_array($ext, $allowed_exts, TRUE)) {
                                $new_filename = 'PENGUJIAN_' . $session_id . '_' . $f_id . '_' . $fld_id . '_' . time() . '.' . $ext;
                                $dest = $upload_dir . $new_filename;
                                if (move_uploaded_file($file_info['tmp_name'], $dest)) {
                                    if (!isset($forms_input[$f_id])) {
                                        $forms_input[$f_id] = array();
                                    }
                                    if (!isset($forms_input[$f_id]['hasil'])) {
                                        $forms_input[$f_id]['hasil'] = array();
                                    }
                                    $forms_input[$f_id]['hasil'][$fld_id] = 'uploads/pengujian/' . $new_filename;
                                }
                            }
                        }
                    }
                }
            }

            if ($this->Model_Pengujian->simpan_hasil_sesi($session_id, $forms_input)) {
                $this->catat_audit('SAVE_SESSION_RESULTS', 'Pengujian', array(
                    'testing_session_id' => $session_id,
                    'sample_id'          => $sesi['sample_id'],
                    'status'             => 'Menunggu Verifikasi'
                ));

                $this->session->set_flashdata('pesan_sukses', 'Seluruh lembar LCP parameter monografi berhasil disimpan beserta pencatatan alat & reagen, dan diteruskan ke Penyelia (Menunggu Verifikasi).');
                redirect('pengujian/detail_sesi/' . $session_id);
            } else {
                $this->session->set_flashdata('pesan_gagal', 'Gagal menyimpan hasil pengujian sesi.');
            }
        }

        // Master Alat & Reagen aktif untuk dipilih pada LCP
        $daftar_alat   = $this->Model_Alat->ambil_semua();
        $daftar_reagen = $this->Model_Reagen->ambil_aktif();

        $this->data['halaman_aktif'] = 'pengujian_antrean';
        $this->data['judul_halaman'] = 'Pelaksanaan Sesi Pengujian (' . $sesi['jenis_pengujian'] . ')';
        $this->data['sesi']          = $sesi;
        $this->data['forms']         = $forms;
        $this->data['hasil_forms']   = $forms;
        $this->data['daftar_alat']   = $daftar_alat;
        $this->data['daftar_reagen'] = $daftar_reagen;

        $this->muat_tampilan('pengujian/proses_sesi');
    }

    /**
     * Halaman Detail Sesi Pengujian
     * 
     * @param int $session_id
     */
    public function detail_sesi($session_id = NULL)
    {
        $this->cek_hak_akses('laporan_view');

        $session_id = (int)$session_id;
        if ($session_id <= 0) {
            show_404();
        }

        $sesi = $this->Model_Pengujian->ambil_sesi_by_id($session_id);
        if (!$sesi) {
            // Cek apakah parameter yang diberikan adalah ID sampel (bukan ID sesi)
            $sampel = $this->Model_Sampel->ambil_by_id($session_id);
            if ($sampel) {
                // Sampel ditemukan, alihkan ke halaman Tinjauan LHU Sampel (yang memuat seluruh sesi dan CP)
                redirect('pengujian/tinjauan_lhu/' . $session_id);
                return;
            }
            show_404();
        }

        $forms = $this->Model_Pengujian->ambil_hasil_by_session($session_id);

        $this->data['halaman_aktif'] = 'pengujian_riwayat';
        $this->data['judul_halaman'] = 'Detail Sesi Pengujian: ' . html_escape($sesi['nama_sampel']);
        $this->data['sesi']          = $sesi;
        $this->data['forms']         = $forms;
        $this->data['daftar_alat']   = $this->Model_Alat->ambil_semua();
        $this->data['daftar_reagen'] = $this->Model_Reagen->ambil_semua();

        $this->muat_tampilan('pengujian/detail_sesi');
    }

    /**
     * Halaman Pengisian Form Pengujian Dinamis (Legacy compatibility wrapper)
     * 
     * @param int $sample_id
     */
    public function proses($sample_id = NULL)
    {
        $sesi = $this->Model_Pengujian->ambil_sesi_by_sample($sample_id);
        if ($sesi) {
            redirect('pengujian/proses_sesi/' . $sesi['id']);
        }

        $this->pilih_jenis($sample_id);
    }

    /**
     * Halaman Riwayat Hasil Pengujian / Laporan
     * Dapat diakses oleh semua role dengan permission laporan_view.
     * Penguji (pengujian_input) hanya melihat riwayat miliknya sendiri.
     * Role lain dengan laporan_view melihat seluruh data.
     */
    public function riwayat()
    {
        $this->cek_hak_akses('laporan_view');

        $this->data['halaman_aktif'] = 'pengujian_riwayat';
        $this->data['judul_halaman'] = 'Riwayat Hasil Uji';

        // Filter: Penguji (permission pengujian_input) hanya melihat riwayat miliknya.
        // Role dengan laporan_view tapi bukan penguji operasional melihat semua data.
        $penguji_id = NULL;
        $role_id    = $this->session->userdata('role_id');
        $is_penguji = $this->Model_Hak_Akses->memiliki_akses($role_id, 'pengujian_input')
                   && !$this->Model_Hak_Akses->memiliki_akses($role_id, 'pengujian_verify')
                   && !$this->Model_Hak_Akses->memiliki_akses($role_id, 'pengujian_approve');
        if ($is_penguji) {
            $penguji_id = $this->session->userdata('user_id');
        }

        $this->data['daftar_hasil'] = $this->Model_Pengujian->ambil_semua_hasil($penguji_id);

        $this->muat_tampilan('pengujian/riwayat');
    }

    /**
     * Detail Hasil Pengujian & Dokumen Laporan
     * 
     * @param int $test_id
     */
    public function detail($test_id = NULL)
    {
        $this->cek_hak_akses('laporan_view');

        $hasil = $this->Model_Pengujian->ambil_hasil_by_id($test_id);
        if (!$hasil) {
            show_404();
        }

        $this->data['halaman_aktif'] = 'pengujian_riwayat';
        $this->data['judul_halaman'] = 'Detail Hasil Pengujian: ' . html_escape($hasil['nama_sampel']);
        $this->data['breadcrumbs']   = array(
            array('label' => 'Pengujian', 'url' => site_url('pengujian/riwayat')),
            array('label' => 'Detail Hasil Uji', 'url' => '#')
        );
        $this->data['hasil']         = $hasil;

        $this->muat_tampilan('pengujian/detail');
    }

    /**
     * Unduh / Tampilkan File Laporan Hasil Uji PDF
     * 
     * @param int $test_id
     */
    public function download_pdf($test_id = NULL)
    {
        $this->cek_hak_akses('laporan_view');

        $hasil = $this->Model_Pengujian->ambil_hasil_by_id($test_id);
        if (!$hasil) {
            show_404();
        }

        // Strict Status Audit: PDF final hanya dapat di-download jika status sudah Approved / Final
        if ($hasil['status'] !== 'Approved / Final') {
            $this->session->set_flashdata('pesan_gagal', 'Laporan PDF final hanya dapat diunduh untuk pengujian yang berstatus "Approved / Final".');
            redirect('pengujian/riwayat');
        }

        if (empty($hasil['file_laporan'])) {
            $this->session->set_flashdata('pesan_gagal', 'File laporan PDF belum terbentuk atau tidak ditemukan.');
            redirect('pengujian/riwayat');
        }

        $full_path = FCPATH . $hasil['file_laporan'];
        if (!file_exists($full_path)) {
            $this->session->set_flashdata('pesan_gagal', 'File PDF tidak ditemukan di server.');
            redirect('pengujian/riwayat');
        }

        $this->catat_audit('DOWNLOAD_REPORT', 'Pengujian', array(
            'test_id'   => $test_id,
            'file_name' => basename($full_path)
        ));

        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . basename($full_path) . '"');
        header('Content-Length: ' . filesize($full_path));
        readfile($full_path);
        exit;
    }

    /**
     * Unduh / Tampilkan File Laporan Hasil Uji PDF untuk Sesi Pengujian
     * 
     * @param int $session_id
     */
    public function download_pdf_sesi($session_id = NULL)
    {
        $this->cek_hak_akses('laporan_view');

        $sesi = $this->Model_Pengujian->ambil_sesi_by_id($session_id);
        if (!$sesi) {
            show_404();
        }

        if ($sesi['status'] !== 'Approved / Final') {
            $this->session->set_flashdata('pesan_gagal', 'Laporan PDF final hanya dapat diunduh untuk sesi pengujian yang berstatus "Approved / Final".');
            redirect('pengujian/riwayat');
        }

        $forms = $this->Model_Pengujian->ambil_hasil_by_session($session_id);
        $file_laporan = NULL;
        foreach ($forms as $f) {
            if (!empty($f['file_laporan'])) {
                $file_laporan = $f['file_laporan'];
                break;
            }
        }

        if (empty($file_laporan) || !file_exists(FCPATH . $file_laporan)) {
            $sampel_info   = $this->Model_Sampel->ambil_by_id($sesi['sample_id']);
            $penguji_info  = $this->Model_Pengguna->ambil_by_id($sesi['penguji_id']);
            $verifier_info = $this->Model_Pengguna->ambil_by_id($sesi['verifier_id']);
            $approver_info = $this->Model_Pengguna->ambil_by_id($sesi['approver_id']);

            $pdf_dir  = FCPATH . 'uploads/laporan/';
            $pdf_name = 'LHU_FINAL_' . $sesi['sample_id'] . '_SESI' . $session_id . '_' . time() . '.pdf';
            $pdf_path = $pdf_dir . $pdf_name;

            try {
                $pdf_gen = new Laporan_PDF();
                $pdf_gen->buat_laporan_sesi($sampel_info, $sesi, $forms, $penguji_info, $verifier_info, $approver_info, $pdf_path);
                $file_laporan = 'uploads/laporan/' . $pdf_name;
                $this->Model_Pengujian->update_file_laporan_sesi($session_id, $file_laporan);
            } catch (Exception $e) {
                log_message('error', 'Gagal membuat PDF sesi: ' . $e->getMessage());
            }
        }

        $full_path = FCPATH . $file_laporan;
        if (!file_exists($full_path)) {
            $this->session->set_flashdata('pesan_gagal', 'File PDF tidak ditemukan di server.');
            redirect('pengujian/riwayat');
        }

        $this->catat_audit('DOWNLOAD_REPORT_SESSION', 'Pengujian', array(
            'session_id' => $session_id,
            'file_name'  => basename($full_path)
        ));

        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . basename($full_path) . '"');
        header('Content-Length: ' . filesize($full_path));
        readfile($full_path);
        exit;
    }

    /**
     * FASE 4 — Unduh / Tampilkan File Laporan Hasil Uji (LHU) PDF Final Gabungan Tingkat Sampel
     *
     * @param int $sample_id
     * @param string $mode 'inline' (preview) atau 'download' (unduh file)
     */
    public function download_pdf_sampel($sample_id = NULL, $mode = 'inline')
    {
        $this->cek_hak_akses('laporan_view');

        $sample_id = (int)$sample_id;
        if ($sample_id <= 0) {
            show_404();
        }

        $allowed_modes = array('inline', 'download');
        if (!in_array($mode, $allowed_modes, TRUE)) {
            $mode = 'inline';
        }

        $sampel = $this->Model_Sampel->ambil_by_id($sample_id);
        if (!$sampel) {
            show_404();
        }

        if ($sampel['status'] !== 'Approved / Final') {
            $this->session->set_flashdata('pesan_gagal', 'LHU PDF Final hanya dapat diakses setelah sampel disetujui Manajer Teknis (status "Approved / Final").');
            redirect('pengujian/tinjauan_lhu/' . $sample_id);
        }

        $serumpun_ids = $this->Model_Pengujian->ambil_sample_ids_serumpun($sample_id);

        // Cari file LHU dari test_results milik sampel atau sampel serumpun
        $this->db->select('file_laporan');
        $this->db->from('test_results');
        $this->db->where_in('sample_id', $serumpun_ids);
        $this->db->where('file_laporan IS NOT NULL', NULL, FALSE);
        $this->db->order_by('id', 'DESC');
        $row_laporan = $this->db->get()->row_array();

        $file_laporan = !empty($row_laporan['file_laporan']) ? $row_laporan['file_laporan'] : NULL;
        $full_path = !empty($file_laporan) ? FCPATH . $file_laporan : NULL;

        if (!$full_path || !file_exists($full_path) || filesize($full_path) === 0) {
            // Auto-generate LHU PDF final jika file belum tersedia
            require_once APPPATH . 'libraries/Laporan_PDF.php';
            $pdf_gen = new Laporan_PDF();

            $this->db->select('ts.*, u.nama_lengkap as nama_penguji, u.nip as nip_penguji, uv.nama_lengkap as nama_verifier, uv.nip as nip_verifier');
            $this->db->from('testing_sessions ts');
            $this->db->join('users u', 'u.id = ts.penguji_id', 'left');
            $this->db->join('users uv', 'uv.id = ts.verifier_id', 'left');
            $this->db->where_in('ts.sample_id', $serumpun_ids);
            $sesi_rows = $this->db->get()->result_array();

            if (!empty($sesi_rows)) {
                foreach ($sesi_rows as &$sr) {
                    $sr['forms'] = $this->Model_Pengujian->ambil_hasil_by_session($sr['id']);
                }
                unset($sr);

                $approver_id = !empty($sampel['updated_by']) ? $sampel['updated_by'] : 5;
                $approver_user = $this->db->get_where('users', ['id' => $approver_id])->row_array();

                $kd_clean = preg_replace('/[^A-Za-z0-9_\-]/', '_', (!empty($sampel['kode_sampel_manual']) ? $sampel['kode_sampel_manual'] : 'SMP_' . $sample_id));
                $pdf_name = 'LHU_FINAL_' . $kd_clean . '_SMP' . $sample_id . '_' . time() . '.pdf';
                $pdf_rel_path = 'uploads/laporan/' . $pdf_name;
                $full_path = FCPATH . $pdf_rel_path;

                try {
                    $pdf_gen->buat_laporan_sampel($sampel, $sesi_rows, $approver_user, $full_path, 'F');
                    $this->db->where_in('sample_id', $serumpun_ids)->update('test_results', ['file_laporan' => $pdf_rel_path]);
                } catch (Exception $e) {
                    $this->session->set_flashdata('pesan_gagal', 'Gagal membuat berkas PDF LHU: ' . $e->getMessage());
                    redirect('pengujian/tinjauan_lhu/' . $sample_id);
                }
            } else {
                $this->session->set_flashdata('pesan_gagal', 'Berkas LHU PDF final belum tersedia di server. Silakan hubungi Administrator.');
                redirect('pengujian/tinjauan_lhu/' . $sample_id);
            }
        }

        $this->catat_audit('DOWNLOAD_LHU_FINAL_SAMPEL', 'Pengujian', array(
            'sample_id' => $sample_id,
            'file_name' => basename($full_path),
            'mode'      => $mode
        ));

        $disposition = ($mode === 'download') ? 'attachment' : 'inline';
        header('Content-Type: application/pdf');
        header('Content-Disposition: ' . $disposition . '; filename="' . basename($full_path) . '"');
        header('Content-Length: ' . filesize($full_path));
        readfile($full_path);
        exit;
    }

    /**
     * Preview / Cetak Laporan Hasil Uji (LHU) Final Gabungan Tingkat Sampel (Kimia & Mikro)
     * Format Resmi BBPOM Bandar Lampung
     *
     * @param int $sample_id
     */
    public function lhu($sample_id = NULL)
    {
        $this->cek_hak_akses('laporan_view');

        $sample_id = (int)$sample_id;
        if ($sample_id <= 0) {
            show_404();
        }

        $sampel = $this->Model_Sampel->ambil_by_id($sample_id);
        if (!$sampel) {
            show_404();
        }

        $serumpun_ids = $this->Model_Pengujian->ambil_sample_ids_serumpun($sample_id);

        // Ambil seluruh sesi pengujian sampel (Kimia & Mikrobiologi) dari seluruh sampel serumpun
        $this->db->select('ts.*, u.nama_lengkap as nama_penguji, u.nip as nip_penguji, uv.nama_lengkap as nama_verifier, uv.nip as nip_verifier, ua.nama_lengkap as nama_approver, ua.nip as nip_approver');
        $this->db->from('testing_sessions ts');
        $this->db->join('users u', 'u.id = ts.penguji_id', 'left');
        $this->db->join('users uv', 'uv.id = ts.verifier_id', 'left');
        $this->db->join('users ua', 'ua.id = ts.approver_id', 'left');
        $this->db->where_in('ts.sample_id', $serumpun_ids);
        $this->db->order_by('ts.id', 'ASC');
        $sesi_list = $this->db->get()->result_array();

        if (empty($sesi_list)) {
            $this->session->set_flashdata('pesan_gagal', 'Belum ada sesi pengujian yang tercatat untuk sampel ini.');
            redirect('pengujian/riwayat');
        }

        // Ambil form hasil per sesi
        foreach ($sesi_list as &$s) {
            $s['forms'] = $this->Model_Pengujian->ambil_hasil_by_session($s['id']);
        }
        unset($s);

        // Resolusi Penandatangan Sesuai Role
        $this->load->model('Model_User_Signature');

        // Penguji
        $penguji_id = !empty($sesi_list[0]['penguji_id']) ? $sesi_list[0]['penguji_id'] : null;
        $penguji_info = $penguji_id ? $this->Model_Pengguna->ambil_by_id($penguji_id) : $this->db->get_where('users', ['role_id' => 3, 'status' => 'Aktif'])->row_array();
        $sig_p = !empty($penguji_info['id']) ? $this->Model_User_Signature->ambil_by_user_id($penguji_info['id']) : null;

        // Penyelia
        $verifier_id = !empty($sesi_list[0]['verifier_id']) ? $sesi_list[0]['verifier_id'] : null;
        $verifier_info = $verifier_id ? $this->Model_Pengguna->ambil_by_id($verifier_id) : $this->db->get_where('users', ['role_id' => 4, 'status' => 'Aktif'])->row_array();
        $sig_v = !empty($verifier_info['id']) ? $this->Model_User_Signature->ambil_by_user_id($verifier_info['id']) : null;

        // Manajer Teknis
        $approver_id = !empty($sesi_list[0]['approver_id']) ? $sesi_list[0]['approver_id'] : (!empty($sampel['updated_by']) ? $sampel['updated_by'] : null);
        $approver_info = $approver_id ? $this->Model_Pengguna->ambil_by_id($approver_id) : $this->db->get_where('users', ['role_id' => 5, 'status' => 'Aktif'])->row_array();
        $sig_a = !empty($approver_info['id']) ? $this->Model_User_Signature->ambil_by_user_id($approver_info['id']) : null;

        $this->catat_audit('VIEW_LHU_FINAL', 'Pengujian', array(
            'sample_id' => $sample_id
        ));

        $view_data = array(
            'sampel'             => $sampel,
            'sesi_list'          => $sesi_list,
            'penguji'            => $penguji_info,
            'verifier'           => $verifier_info,
            'approver'           => $approver_info,
            'signature_penguji'  => $sig_p,
            'signature_verifier' => $sig_v,
            'signature_approver' => $sig_a
        );

        $this->load->view('pengujian/cetak_lhu', $view_data);
    }

    /**
     * Preview / Unduh Catatan Pengujian (CP) Kimia (Form 5.10/F01)
     *
     * @param int $session_id
     */
    public function cp_kimia($session_id = NULL)
    {
        $this->cek_hak_akses('laporan_view');

        $session_id = (int)$session_id;
        if ($session_id <= 0) {
            show_404();
        }

        $sesi = $this->Model_Pengujian->ambil_sesi_by_id($session_id);
        if (!$sesi) {
            $serumpun_ids = $this->Model_Pengujian->ambil_sample_ids_serumpun($session_id);
            $sesi_kimia = $this->db->where_in('sample_id', $serumpun_ids)->where('jenis_pengujian', 'Kimia')->order_by('id', 'DESC')->get('testing_sessions')->row_array();
            if ($sesi_kimia) {
                redirect('pengujian/cp_kimia/' . $sesi_kimia['id']);
                return;
            }
            show_404();
        }

        $allowed_statuses = array('Menunggu Approval', 'Approved / Final');
        if (!in_array($sesi['status'], $allowed_statuses, TRUE)) {
            $this->session->set_flashdata('pesan_gagal', 'Catatan Pengujian (CP) hanya dapat diakses setelah hasil pengujian selesai diverifikasi oleh Penyelia.');
            redirect('pengujian/riwayat');
        }

        $sampel_info = $this->Model_Sampel->ambil_by_id($sesi['sample_id']);
        if (!$sampel_info) {
            $this->session->set_flashdata('pesan_gagal', 'Data sampel terkait tidak ditemukan.');
            redirect('pengujian/riwayat');
        }

        $forms         = $this->Model_Pengujian->ambil_hasil_by_session($session_id);

        $this->load->model('Model_User_Signature');

        // Resolusi Pengguna & Tanda Tangan Sesuai Role
        // 1. Role Penguji (role_id = 3)
        $penguji_id = !empty($sesi['penguji_id']) ? $sesi['penguji_id'] : null;
        $penguji_info = $penguji_id ? $this->Model_Pengguna->ambil_by_id($penguji_id) : $this->db->get_where('users', ['role_id' => 3, 'status' => 'Aktif'])->row_array();
        $signature_penguji = !empty($penguji_info['id']) ? $this->Model_User_Signature->ambil_by_user_id($penguji_info['id']) : null;

        // 2. Role Penyelia (role_id = 4)
        $verifier_id = !empty($sesi['verifier_id']) ? $sesi['verifier_id'] : null;
        $verifier_info = $verifier_id ? $this->Model_Pengguna->ambil_by_id($verifier_id) : $this->db->get_where('users', ['role_id' => 4, 'status' => 'Aktif'])->row_array();
        $signature_verifier = !empty($verifier_info['id']) ? $this->Model_User_Signature->ambil_by_user_id($verifier_info['id']) : null;

        // 3. Role Manajer Teknis (role_id = 5)
        $approver_id = !empty($sesi['approver_id']) ? $sesi['approver_id'] : (!empty($sampel_info['updated_by']) && $sampel_info['status'] === 'Approved / Final' ? $sampel_info['updated_by'] : null);
        $approver_info = $approver_id ? $this->Model_Pengguna->ambil_by_id($approver_id) : $this->db->get_where('users', ['role_id' => 5, 'status' => 'Aktif'])->row_array();
        $signature_approver = !empty($approver_info['id']) ? $this->Model_User_Signature->ambil_by_user_id($approver_info['id']) : null;

        $this->catat_audit('VIEW_CP_KIMIA', 'Pengujian', array(
            'session_id' => $session_id,
            'sample_id'  => $sesi['sample_id']
        ));

        $view_data = array(
            'sesi'               => $sesi,
            'sampel'             => $sampel_info,
            'forms'              => $forms,
            'penguji'            => $penguji_info,
            'verifier'           => $verifier_info,
            'approver'           => $approver_info,
            'signature_penguji'  => $signature_penguji,
            'signature_verifier' => $signature_verifier,
            'signature_approver' => $signature_approver
        );

        $this->load->view('pengujian/cetak_cp_kimia', $view_data);
    }

    /**
     * Preview / Unduh Catatan Pengujian (CP) Mikrobiologi PDF
     * Sesuai format workbook resmi "CP mikro.xlsx" BBPOM di Bandar Lampung
     *
     * @param int $session_id
     * @param string $mode 'inline' (preview) atau 'attachment' (unduh)
     */
    public function cp_mikro($session_id = NULL, $mode = 'inline')
    {
        $this->cek_hak_akses('laporan_view');

        $session_id = (int)$session_id;
        if ($session_id <= 0) {
            show_404();
        }

        // Whitelist mode parameter: hanya 'inline' atau 'download'
        $allowed_modes = array('inline', 'download');
        if (!in_array($mode, $allowed_modes, TRUE)) {
            $mode = 'inline';
        }

        $sesi = $this->Model_Pengujian->ambil_sesi_by_id($session_id);
        if (!$sesi) {
            $serumpun_ids = $this->Model_Pengujian->ambil_sample_ids_serumpun($session_id);
            $sesi_mikro = $this->db->where_in('sample_id', $serumpun_ids)->where('jenis_pengujian', 'Mikrobiologi')->order_by('id', 'DESC')->get('testing_sessions')->row_array();
            if ($sesi_mikro) {
                redirect('pengujian/cp_mikro/' . $sesi_mikro['id'] . '/' . $mode);
                return;
            }
            show_404();
        }

        // Validasi jenis pengujian harus Mikrobiologi
        if (isset($sesi['jenis_pengujian']) && $sesi['jenis_pengujian'] !== 'Mikrobiologi') {
            $this->session->set_flashdata('pesan_gagal', 'Catatan Pengujian (CP) khusus hanya tersedia untuk jenis pengujian Mikrobiologi.');
            redirect('pengujian/riwayat');
        }

        // Syarat CP Mikrobiologi: harus telah diverifikasi oleh Penyelia (status 'Menunggu Approval' atau 'Approved / Final')
        $allowed_statuses = array('Menunggu Approval', 'Approved / Final');
        if (!in_array($sesi['status'], $allowed_statuses, TRUE)) {
            $this->session->set_flashdata('pesan_gagal', 'Catatan Pengujian (CP) hanya dapat diakses setelah hasil pengujian selesai diverifikasi oleh Penyelia.');
            redirect('pengujian/riwayat');
        }

        // Ambil data terkait dari database
        $sampel_info   = $this->Model_Sampel->ambil_by_id($sesi['sample_id']);
        if (!$sampel_info) {
            $this->session->set_flashdata('pesan_gagal', 'Data sampel terkait tidak ditemukan.');
            redirect('pengujian/riwayat');
        }

        $forms         = $this->Model_Pengujian->ambil_hasil_by_session($session_id);

        // Resolusi Pengguna Sesuai Role
        $penguji_id = !empty($sesi['penguji_id']) ? $sesi['penguji_id'] : null;
        $penguji_info = $penguji_id ? $this->Model_Pengguna->ambil_by_id($penguji_id) : $this->db->get_where('users', ['role_id' => 3, 'status' => 'Aktif'])->row_array();
        if ($penguji_info && empty($penguji_id)) {
            $penguji_id = $penguji_info['id'];
        }

        $verifier_id = !empty($sesi['verifier_id']) ? $sesi['verifier_id'] : null;
        $verifier_info = $verifier_id ? $this->Model_Pengguna->ambil_by_id($verifier_id) : $this->db->get_where('users', ['role_id' => 4, 'status' => 'Aktif'])->row_array();
        if ($verifier_info && empty($verifier_id)) {
            $verifier_id = $verifier_info['id'];
        }

        // GATE KETAT TANDA TANGAN DIGITAL:
        // CP final harus otomatis memuat TTD Penguji dan Penyelia yang sah.
        // Validasi keberadaan file, keterbacaan, format gambar, dan path yang diizinkan.
        require_once APPPATH . 'libraries/CP_Mikrobiologi_PDF.php';
        $cp_gen = new CP_Mikrobiologi_PDF();

        $val_penguji  = $cp_gen->validateUserSignature($penguji_id);
        $val_penyelia = $cp_gen->validateUserSignature($verifier_id);

        $err_penguji  = !$val_penguji[0];
        $err_penyelia = !$val_penyelia[0];

        if ($err_penguji || $err_penyelia) {
            if ($err_penguji && $err_penyelia) {
                $pesan_ttd = 'CP final belum dapat diterbitkan karena Tanda Tangan Digital Penguji dan Penyelia belum tersedia atau tidak valid di profil akun masing-masing.';
            } elseif ($err_penguji) {
                $pesan_ttd = 'CP final belum dapat diterbitkan karena Tanda Tangan Digital Penguji belum tersedia atau tidak valid di profil akun.';
            } else {
                $pesan_ttd = 'CP final belum dapat diterbitkan karena Tanda Tangan Digital Penyelia belum tersedia atau tidak valid di profil akun.';
            }

            $this->catat_audit('CP_SIGNATURE_GATE_BLOCKED', 'Pengujian', array(
                'session_id'   => $session_id,
                'penguji_id'   => $sesi['penguji_id'],
                'verifier_id'  => $sesi['verifier_id'],
                'err_penguji'  => $err_penguji,
                'err_penyelia' => $err_penyelia
            ));

            $this->session->set_flashdata('pesan_gagal', $pesan_ttd);
            redirect('pengujian/riwayat');
        }

        // Sanitasi kode sampel untuk nama file
        $kd_clean = preg_replace('/[^A-Za-z0-9_\-]/', '_', (!empty($sampel_info['kode_sampel_manual']) ? $sampel_info['kode_sampel_manual'] : 'SMP_' . $sesi['sample_id']));
        $pdf_name = 'CP_Mikrobiologi_' . $kd_clean . '_SESI' . $session_id . '.pdf';
        $pdf_dir  = FCPATH . 'uploads/laporan/';
        $pdf_path = $pdf_dir . $pdf_name;

        // Generate / Update file CP PDF
        try {
            $cp_gen->buat_cp($sampel_info, $sesi, $forms, $penguji_info, $verifier_info, $pdf_path);
        } catch (Exception $e) {
            log_message('error', 'Gagal membuat CP Mikrobiologi PDF: ' . $e->getMessage());
            $this->session->set_flashdata('pesan_gagal', 'Terjadi kesalahan saat memproses Catatan Pengujian (CP).');
            redirect('pengujian/riwayat');
        }

        if (!file_exists($pdf_path)) {
            $this->session->set_flashdata('pesan_gagal', 'File CP PDF gagal dibuat di server.');
            redirect('pengujian/riwayat');
        }

        $this->catat_audit('DOWNLOAD_CP_MIKRO', 'Pengujian', array(
            'session_id' => $session_id,
            'file_name'  => $pdf_name,
            'mode'       => $mode
        ));

        $disposition = ($mode === 'download') ? 'attachment' : 'inline';
        header('Content-Type: application/pdf');
        header('Content-Disposition: ' . $disposition . '; filename="' . $pdf_name . '"');
        header('Content-Length: ' . filesize($pdf_path));
        readfile($pdf_path);
        exit;
    }

    /**
     * Preview / Unduh Lembar Catatan Pengujian (LCP) Form Spesifik PDF
     *
     * @param int $test_id
     * @param string $mode 'inline' atau 'download'
     */
    public function lcp_pdf($test_id = NULL, $mode = 'inline')
    {
        $this->cek_hak_akses('laporan_view');

        $test_id = (int)$test_id;
        if ($test_id <= 0) {
            show_404();
        }

        $allowed_modes = array('inline', 'download');
        if (!in_array($mode, $allowed_modes, TRUE)) {
            $mode = 'inline';
        }

        $hasil = $this->Model_Pengujian->ambil_hasil_by_id($test_id);
        if (!$hasil) {
            show_404();
        }

        $session_id = !empty($hasil['session_id']) ? (int)$hasil['session_id'] : 0;
        $sesi = $session_id > 0 ? $this->Model_Pengujian->ambil_sesi_by_id($session_id) : array();

        $status_uji = !empty($sesi['status']) ? $sesi['status'] : $hasil['status'];
        $allowed_statuses = array('Menunggu Approval', 'Approved / Final');
        if (!in_array($status_uji, $allowed_statuses, TRUE)) {
            $this->session->set_flashdata('pesan_gagal', 'Lembar Catatan Pengujian (LCP) hanya dapat diakses setelah hasil pengujian selesai diverifikasi oleh Penyelia.');
            redirect('pengujian/riwayat');
        }

        $sampel_info   = $this->Model_Sampel->ambil_by_id($hasil['sample_id']);
        if (!$sampel_info) {
            $this->session->set_flashdata('pesan_gagal', 'Data sampel terkait tidak ditemukan.');
            redirect('pengujian/riwayat');
        }

        // Resolusi Pengguna & Tanda Tangan Sesuai Role
        // 1. Role Penguji (role_id = 3)
        $penguji_id    = !empty($sesi['penguji_id']) ? $sesi['penguji_id'] : (!empty($hasil['penguji_id']) ? $hasil['penguji_id'] : null);
        $penguji_info  = $penguji_id ? $this->Model_Pengguna->ambil_by_id($penguji_id) : $this->db->get_where('users', ['role_id' => 3, 'status' => 'Aktif'])->row_array();
        if ($penguji_info && empty($penguji_id)) {
            $penguji_id = $penguji_info['id'];
        }

        // 2. Role Penyelia (role_id = 4)
        $verifier_id   = !empty($sesi['verifier_id']) ? $sesi['verifier_id'] : (!empty($hasil['verifier_id']) ? $hasil['verifier_id'] : null);
        $verifier_info = $verifier_id ? $this->Model_Pengguna->ambil_by_id($verifier_id) : $this->db->get_where('users', ['role_id' => 4, 'status' => 'Aktif'])->row_array();
        if ($verifier_info && empty($verifier_id)) {
            $verifier_id = $verifier_info['id'];
        }

        // 3. Role Manajer Teknis (role_id = 5)
        $approver_id   = !empty($sesi['approver_id']) ? $sesi['approver_id'] : (!empty($hasil['approver_id']) ? $hasil['approver_id'] : (!empty($sampel_info['updated_by']) && $sampel_info['status'] === 'Approved / Final' ? $sampel_info['updated_by'] : null));
        $approver_info = $approver_id ? $this->Model_Pengguna->ambil_by_id($approver_id) : $this->db->get_where('users', ['role_id' => 5, 'status' => 'Aktif'])->row_array();

        $this->load->model('Model_User_Signature');
        $signature_penguji  = !empty($penguji_info['id']) ? $this->Model_User_Signature->ambil_by_user_id($penguji_info['id']) : null;
        $signature_verifier = !empty($verifier_info['id']) ? $this->Model_User_Signature->ambil_by_user_id($verifier_info['id']) : null;
        $signature_approver = !empty($approver_info['id']) ? $this->Model_User_Signature->ambil_by_user_id($approver_info['id']) : null;

        require_once APPPATH . 'libraries/LCP_Mikrobiologi_PDF.php';
        $lcp_gen = new LCP_Mikrobiologi_PDF();

        // Validasi Tanda Tangan Digital Penguji & Penyelia
        $val_penguji  = $lcp_gen->validateUserSignature($penguji_id);
        $val_penyelia = $verifier_id ? $lcp_gen->validateUserSignature($verifier_id) : [false, null, 'Penyelia belum ditentukan'];

        $err_penguji  = !$val_penguji[0];
        $err_penyelia = !$val_penyelia[0];

        if ($err_penguji || $err_penyelia) {
            if ($err_penguji && $err_penyelia) {
                $pesan_ttd = 'LCP final belum dapat diterbitkan karena Tanda Tangan Digital Penguji dan Penyelia belum tersedia atau tidak valid di profil akun masing-masing.';
            } elseif ($err_penguji) {
                $pesan_ttd = 'LCP final belum dapat diterbitkan karena Tanda Tangan Digital Penguji belum tersedia atau tidak valid di profil akun.';
            } else {
                $pesan_ttd = 'LCP final belum dapat diterbitkan karena Tanda Tangan Digital Penyelia belum tersedia atau tidak valid di profil akun.';
            }

            $this->catat_audit('LCP_SIGNATURE_GATE_BLOCKED', 'Pengujian', array(
                'test_id'      => $test_id,
                'session_id'   => $session_id,
                'penguji_id'   => $penguji_id,
                'verifier_id'  => $verifier_id,
                'err_penguji'  => $err_penguji,
                'err_penyelia' => $err_penyelia
            ));

            $this->session->set_flashdata('pesan_gagal', $pesan_ttd);
            redirect('pengujian/riwayat');
        }

        // Jika form template adalah Cemaran Mikroba Kualitatif (MIK-04), gunakan view LCP presisi
        $is_kualitatif = (
            (!empty($hasil['kode_template']) && $hasil['kode_template'] === 'MIK-04') ||
            (!empty($hasil['template_id']) && (int)$hasil['template_id'] === 19) ||
            (!empty($hasil['nama_template']) && stripos($hasil['nama_template'], 'Kualitatif') !== false)
        );

        if ($is_kualitatif) {
            $this->catat_audit('VIEW_LCP_KUALITATIF', 'Pengujian', array(
                'test_id'    => $test_id,
                'session_id' => $session_id,
                'template'   => !empty($hasil['kode_template']) ? $hasil['kode_template'] : 'MIK-04'
            ));

            $view_data = array(
                'hasil'              => $hasil,
                'sampel'             => $sampel_info,
                'sesi'               => $sesi,
                'penguji'            => $penguji_info,
                'verifier'           => $verifier_info,
                'approver'           => $approver_info,
                'signature_penguji'  => $signature_penguji,
                'signature_verifier' => $signature_verifier,
                'signature_approver' => $signature_approver,
                'metode'             => $hasil
            );

            $this->load->view('pengujian/cetak_lcp_kualitatif', $view_data);
            return;
        }

        // Jika form template adalah Uji Kimia Reaksi Warna / Pengendapan (KIM-01)
        $is_reaksi_warna = (
            (!empty($hasil['kode_template']) && $hasil['kode_template'] === 'KIM-01') ||
            (!empty($hasil['template_id']) && (int)$hasil['template_id'] === 20) ||
            (!empty($hasil['nama_template']) && stripos($hasil['nama_template'], 'Reaksi Warna') !== false)
        );

        if ($is_reaksi_warna) {
            $this->catat_audit('VIEW_LCP_REAKSI_WARNA', 'Pengujian', array(
                'test_id'    => $test_id,
                'session_id' => $session_id,
                'template'   => !empty($hasil['kode_template']) ? $hasil['kode_template'] : 'KIM-01'
            ));

            $view_data = array(
                'hasil'              => $hasil,
                'sampel'             => $sampel_info,
                'sesi'               => $sesi,
                'penguji'            => $penguji_info,
                'verifier'           => $verifier_info,
                'approver'           => $approver_info,
                'signature_penguji'  => $signature_penguji,
                'signature_verifier' => $signature_verifier,
                'signature_approver' => $signature_approver,
                'metode'             => $hasil
            );

            $this->load->view('pengujian/cetak_lcp_reaksi_warna', $view_data);
            return;
        }

        // Jika form template adalah Keragaman Bobot (KIM-02)
        $is_keragaman_bobot = (
            (!empty($hasil['kode_template']) && $hasil['kode_template'] === 'KIM-02') ||
            (!empty($hasil['nama_template']) && stripos($hasil['nama_template'], 'Keragaman Bobot') !== false)
        );

        if ($is_keragaman_bobot) {
            $this->catat_audit('VIEW_LCP_KERAGAMAN_BOBOT', 'Pengujian', array(
                'test_id'    => $test_id,
                'session_id' => $session_id,
                'template'   => !empty($hasil['kode_template']) ? $hasil['kode_template'] : 'KIM-02'
            ));

            $view_data = array(
                'hasil'              => $hasil,
                'sampel'             => $sampel_info,
                'sesi'               => $sesi,
                'penguji'            => $penguji_info,
                'verifier'           => $verifier_info,
                'approver'           => $approver_info,
                'signature_penguji'  => $signature_penguji,
                'signature_verifier' => $signature_verifier,
                'signature_approver' => $signature_approver,
                'metode'             => $hasil
            );

            $this->load->view('pengujian/cetak_lcp_keragaman_bobot', $view_data);
            return;
        }

        // Jika form template adalah Keseragaman Kandungan (KIM-03)
        $is_keseragaman_kandungan = (
            (!empty($hasil['kode_template']) && $hasil['kode_template'] === 'KIM-03') ||
            (!empty($hasil['nama_template']) && stripos($hasil['nama_template'], 'Keseragaman Kandungan') !== false)
        );

        if ($is_keseragaman_kandungan) {
            $this->catat_audit('VIEW_LCP_KESERAGAMAN_KANDUNGAN', 'Pengujian', array(
                'test_id'    => $test_id,
                'session_id' => $session_id,
                'template'   => !empty($hasil['kode_template']) ? $hasil['kode_template'] : 'KIM-03'
            ));

            $view_data = array(
                'hasil'              => $hasil,
                'sampel'             => $sampel_info,
                'sesi'               => $sesi,
                'penguji'            => $penguji_info,
                'verifier'           => $verifier_info,
                'approver'           => $approver_info,
                'signature_penguji'  => $signature_penguji,
                'signature_verifier' => $signature_verifier,
                'signature_approver' => $signature_approver,
                'metode'             => $hasil
            );

            $this->load->view('pengujian/cetak_lcp_keseragaman_kandungan', $view_data);
            return;
        }

        // Jika form template adalah Waktu Hancur (KIM-04)
        $is_waktu_hancur = (
            (!empty($hasil['kode_template']) && $hasil['kode_template'] === 'KIM-04') ||
            (!empty($hasil['nama_template']) && stripos($hasil['nama_template'], 'Waktu Hancur') !== false)
        );

        if ($is_waktu_hancur) {
            $this->catat_audit('VIEW_LCP_WAKTU_HANCUR', 'Pengujian', array(
                'test_id'    => $test_id,
                'session_id' => $session_id,
                'template'   => !empty($hasil['kode_template']) ? $hasil['kode_template'] : 'KIM-04'
            ));

            $view_data = array(
                'hasil'              => $hasil,
                'sampel'             => $sampel_info,
                'sesi'               => $sesi,
                'penguji'            => $penguji_info,
                'verifier'           => $verifier_info,
                'approver'           => $approver_info,
                'signature_penguji'  => $signature_penguji,
                'signature_verifier' => $signature_verifier,
                'signature_approver' => $signature_approver,
                'metode'             => $hasil
            );

            $this->load->view('pengujian/cetak_lcp_waktu_hancur', $view_data);
            return;
        }

        // Jika form template adalah Spektrofotometri (KIM-05)
        $is_spektrofotometri = (
            (!empty($hasil['kode_template']) && $hasil['kode_template'] === 'KIM-05') ||
            (!empty($hasil['nama_template']) && stripos($hasil['nama_template'], 'Spektrofotometri') !== false)
        );

        if ($is_spektrofotometri) {
            $this->catat_audit('VIEW_LCP_SPEKTROFOTOMETRI', 'Pengujian', array(
                'test_id'    => $test_id,
                'session_id' => $session_id,
                'template'   => !empty($hasil['kode_template']) ? $hasil['kode_template'] : 'KIM-05'
            ));

            $view_data = array(
                'hasil'              => $hasil,
                'sampel'             => $sampel_info,
                'sesi'               => $sesi,
                'penguji'            => $penguji_info,
                'verifier'           => $verifier_info,
                'approver'           => $approver_info,
                'signature_penguji'  => $signature_penguji,
                'signature_verifier' => $signature_verifier,
                'signature_approver' => $signature_approver,
                'metode'             => $hasil
            );

            $this->load->view('pengujian/cetak_lcp_spektrofotometri', $view_data);
            return;
        }

        // Jika form template adalah Disolusi (KIM-06)
        $is_disolusi = (
            (!empty($hasil['kode_template']) && $hasil['kode_template'] === 'KIM-06') ||
            (!empty($hasil['nama_template']) && stripos($hasil['nama_template'], 'Disolusi') !== false)
        );

        if ($is_disolusi) {
            $this->catat_audit('VIEW_LCP_DISOLUSI', 'Pengujian', array(
                'test_id'    => $test_id,
                'session_id' => $session_id,
                'template'   => !empty($hasil['kode_template']) ? $hasil['kode_template'] : 'KIM-06'
            ));

            $view_data = array(
                'hasil'              => $hasil,
                'sampel'             => $sampel_info,
                'sesi'               => $sesi,
                'penguji'            => $penguji_info,
                'verifier'           => $verifier_info,
                'approver'           => $approver_info,
                'signature_penguji'  => $signature_penguji,
                'signature_verifier' => $signature_verifier,
                'signature_approver' => $signature_approver,
                'metode'             => $hasil
            );

            $this->load->view('pengujian/cetak_lcp_disolusi', $view_data);
            return;
        }

        // --- KHUSUS FORM LCP KIM-07: KROMATOGRAFI LAPIS TIPIS ---
        if (
            (!empty($hasil['kode_template']) && strtoupper($hasil['kode_template']) === 'KIM-07') ||
            (!empty($hasil['nama_template']) && stripos($hasil['nama_template'], 'kromatografi lapis tipis') !== false) ||
            (!empty($hasil['nama_pengujian']) && stripos($hasil['nama_pengujian'], 'kromatografi lapis tipis') !== false) ||
            (!empty($hasil['nama_pengujian']) && stripos($hasil['nama_pengujian'], 'klt') !== false) ||
            (!empty($hasil['sop_number']) && stripos($hasil['sop_number'], 'F.01A.10') !== false)
        ) {
            $this->catat_audit('VIEW_LCP_KLT', 'Pengujian', array(
                'test_id'    => $test_id,
                'session_id' => $session_id,
                'template'   => !empty($hasil['kode_template']) ? $hasil['kode_template'] : 'KIM-07'
            ));

            $view_data = array(
                'hasil'              => $hasil,
                'sampel'             => $sampel_info,
                'sesi'               => $sesi,
                'penguji'            => $penguji_info,
                'verifier'           => $verifier_info,
                'approver'           => $approver_info,
                'signature_penguji'  => $signature_penguji,
                'signature_verifier' => $signature_verifier,
                'signature_approver' => $signature_approver,
                'metode'             => $hasil
            );

            $this->load->view('pengujian/cetak_lcp_klt', $view_data);
            return;
        }

        // --- KHUSUS FORM LCP KIM-08: KROMATOGRAFI CAIR KINERJA TINGGI (HPLC / KCKT) ---
        if (
            (!empty($hasil['kode_template']) && strtoupper($hasil['kode_template']) === 'KIM-08') ||
            (!empty($hasil['nama_template']) && stripos($hasil['nama_template'], 'kromatografi cair kinerja tinggi') !== false) ||
            (!empty($hasil['nama_template']) && stripos($hasil['nama_template'], 'hplc') !== false) ||
            (!empty($hasil['nama_pengujian']) && stripos($hasil['nama_pengujian'], 'kckt') !== false) ||
            (!empty($hasil['nama_pengujian']) && stripos($hasil['nama_pengujian'], 'hplc') !== false) ||
            (!empty($hasil['sop_number']) && stripos($hasil['sop_number'], 'F.01A.12') !== false)
        ) {
            $this->catat_audit('VIEW_LCP_KCKT', 'Pengujian', array(
                'test_id'    => $test_id,
                'session_id' => $session_id,
                'template'   => !empty($hasil['kode_template']) ? $hasil['kode_template'] : 'KIM-08'
            ));

            $view_data = array(
                'hasil'              => $hasil,
                'sampel'             => $sampel_info,
                'sesi'               => $sesi,
                'penguji'            => $penguji_info,
                'verifier'           => $verifier_info,
                'approver'           => $approver_info,
                'signature_penguji'  => $signature_penguji,
                'signature_verifier' => $signature_verifier,
                'signature_approver' => $signature_approver,
                'metode'             => $hasil
            );

            $this->load->view('pengujian/cetak_lcp_kckt', $view_data);
            return;
        }

        // --- KHUSUS FORM LCP KIM-09: KROMATOGRAFI GAS SPEKTROMETRI MASSA (GC-MS) ---
        if (
            (!empty($hasil['kode_template']) && strtoupper($hasil['kode_template']) === 'KIM-09') ||
            (!empty($hasil['nama_template']) && stripos($hasil['nama_template'], 'kromatografi gas') !== false) ||
            (!empty($hasil['nama_template']) && stripos($hasil['nama_template'], 'gc-ms') !== false) ||
            (!empty($hasil['nama_template']) && stripos($hasil['nama_template'], 'gcms') !== false) ||
            (!empty($hasil['nama_pengujian']) && stripos($hasil['nama_pengujian'], 'gc-ms') !== false) ||
            (!empty($hasil['nama_pengujian']) && stripos($hasil['nama_pengujian'], 'gcms') !== false) ||
            (!empty($hasil['sop_number']) && stripos($hasil['sop_number'], 'F.01A.17') !== false)
        ) {
            $this->catat_audit('VIEW_LCP_GCMS', 'Pengujian', array(
                'test_id'    => $test_id,
                'session_id' => $session_id,
                'template'   => !empty($hasil['kode_template']) ? $hasil['kode_template'] : 'KIM-09'
            ));

            $view_data = array(
                'hasil'              => $hasil,
                'sampel'             => $sampel_info,
                'sesi'               => $sesi,
                'penguji'            => $penguji_info,
                'verifier'           => $verifier_info,
                'approver'           => $approver_info,
                'signature_penguji'  => $signature_penguji,
                'signature_verifier' => $signature_verifier,
                'signature_approver' => $signature_approver,
                'metode'             => $hasil
            );

            $this->load->view('pengujian/cetak_lcp_gcms', $view_data);
            return;
        }

        // --- KHUSUS FORM LCP KIM-10: PEMERIAN (ORGANOLEPTIS) ---
        if (
            (!empty($hasil['kode_template']) && strtoupper($hasil['kode_template']) === 'KIM-10') ||
            (!empty($hasil['nama_template']) && stripos($hasil['nama_template'], 'pemerian') !== false) ||
            (!empty($hasil['nama_pengujian']) && stripos($hasil['nama_pengujian'], 'pemerian') !== false) ||
            (!empty($hasil['nama_pengujian']) && stripos($hasil['nama_pengujian'], 'organoleptis') !== false) ||
            (!empty($hasil['sop_number']) && stripos($hasil['sop_number'], 'F.01A.25') !== false)
        ) {
            $this->catat_audit('VIEW_LCP_PEMERIAN', 'Pengujian', array(
                'test_id'    => $test_id,
                'session_id' => $session_id,
                'template'   => !empty($hasil['kode_template']) ? $hasil['kode_template'] : 'KIM-10'
            ));

            $view_data = array(
                'hasil'              => $hasil,
                'sampel'             => $sampel_info,
                'sesi'               => $sesi,
                'penguji'            => $penguji_info,
                'verifier'           => $verifier_info,
                'approver'           => $approver_info,
                'signature_penguji'  => $signature_penguji,
                'signature_verifier' => $signature_verifier,
                'signature_approver' => $signature_approver,
                'metode'             => $hasil
            );

            $this->load->view('pengujian/cetak_lcp_pemerian', $view_data);
            return;
        }

        $daftar_alat   = $this->Model_Alat->ambil_semua();
        $daftar_reagen = $this->Model_Reagen->ambil_semua();

        $kd_clean = preg_replace('/[^A-Za-z0-9_\-]/', '_', (!empty($sampel_info['kode_sampel_manual']) ? $sampel_info['kode_sampel_manual'] : 'SMP_' . $hasil['sample_id']));
        $tpl_clean = preg_replace('/[^A-Za-z0-9_\-]/', '_', (!empty($hasil['kode_template']) ? $hasil['kode_template'] : 'LCP'));
        $pdf_name = 'LCP_' . $tpl_clean . '_' . $kd_clean . '_T' . $test_id . '.pdf';
        $pdf_dir  = FCPATH . 'uploads/laporan/';
        $pdf_path = $pdf_dir . $pdf_name;

        try {
            $lcp_gen->buat_lcp($hasil, $sampel_info, $sesi, $penguji_info, $verifier_info, $daftar_alat, $daftar_reagen, $pdf_path);
        } catch (Exception $e) {
            log_message('error', 'Gagal membuat LCP Mikrobiologi PDF: ' . $e->getMessage());
            $this->session->set_flashdata('pesan_gagal', 'Terjadi kesalahan saat memproses Lembar Catatan Pengujian (LCP).');
            redirect('pengujian/riwayat');
        }

        if (!file_exists($pdf_path)) {
            $this->session->set_flashdata('pesan_gagal', 'File LCP PDF gagal dibuat di server.');
            redirect('pengujian/riwayat');
        }

        $this->catat_audit('DOWNLOAD_LCP_MIKRO', 'Pengujian', array(
            'test_id'    => $test_id,
            'session_id' => $session_id,
            'file_name'  => $pdf_name,
            'mode'       => $mode
        ));

        $disposition = ($mode === 'download') ? 'attachment' : 'inline';
        header('Content-Type: application/pdf');
        header('Content-Disposition: ' . $disposition . '; filename="' . $pdf_name . '"');
        header('Content-Length: ' . filesize($pdf_path));
        readfile($pdf_path);
        exit;
    }


    /**
     * FASE 4 — Halaman Workspace Penyelia (Verifikasi Laporan)
     */
    public function verifikasi()
    {
        $this->cek_hak_akses('pengujian_verify');

        $this->data['halaman_aktif'] = 'pengujian_verifikasi';
        $this->data['judul_halaman'] = 'Verifikasi Laporan';
        $antrean = $this->Model_Pengujian->ambil_antrean_verifikasi();
        
        $session_id_selected = $this->input->get('session_id', TRUE);
        $selected_item = NULL;
        $selected_forms = array();

        if (!empty($antrean)) {
            if ($session_id_selected) {
                foreach ($antrean as $item) {
                    if ($item['id'] == $session_id_selected) {
                        $selected_item = $item;
                        break;
                    }
                }
            }
            if (!$selected_item) {
                $selected_item = $antrean[0];
            }

            if ($selected_item) {
                $selected_forms = $this->Model_Pengujian->ambil_hasil_by_session($selected_item['id']);
            }
        }

        $this->data['antrean_verifikasi']  = $antrean;
        $this->data['selected_item']       = $selected_item;
        $this->data['selected_forms']      = $selected_forms;
        $this->data['daftar_alat']         = $this->Model_Alat->ambil_semua();
        $this->data['daftar_reagen']       = $this->Model_Reagen->ambil_semua();

        $this->muat_tampilan('pengujian/verifikasi');
    }

    /**
     * FASE 4 — Proses Setujui Verifikasi Laporan Uji
     * Transisi: "Menunggu Verifikasi" -> "Menunggu Approval"
     * 
     * @param int $test_id
     */
    public function proses_verifikasi($test_id = NULL)
    {
        $this->cek_hak_akses('pengujian_verify');

        if ($this->input->method() !== 'post') {
            redirect('pengujian/verifikasi');
        }

        $hasil = $this->Model_Pengujian->ambil_hasil_by_id($test_id);
        if (!$hasil) {
            show_404();
        }

        if ($hasil['status'] !== 'Menunggu Verifikasi') {
            $this->session->set_flashdata('pesan_gagal', 'Laporan ini tidak dalam status "Menunggu Verifikasi".');
            redirect('pengujian/detail/' . $test_id);
        }

        $verifier_id = $this->session->userdata('user_id');

        if ($this->Model_Pengujian->verifikasi_hasil($test_id, $verifier_id)) {
            $this->catat_audit('VERIFIKASI', 'Pengujian', array(
                'test_id'          => $test_id,
                'sample_id'        => $hasil['sample_id'],
                'verifier_id'      => $verifier_id,
                'status_sebelum'   => 'Menunggu Verifikasi',
                'status_sesudah'   => 'Menunggu Approval',
                'waktu_verifikasi' => date('Y-m-d H:i:s')
            ));

            $this->session->set_flashdata('pesan_sukses', 'Laporan hasil pengujian berhasil diverifikasi dan diteruskan ke alur Approval (Menunggu Approval).');
            redirect('pengujian/detail/' . $test_id);
        } else {
            $this->session->set_flashdata('pesan_gagal', 'Gagal memproses verifikasi laporan.');
            redirect('pengujian/detail/' . $test_id);
        }
    }

    /**
     * FASE 4 — Proses Penolakan Hasil Uji oleh Penyelia
     * Transisi: "Menunggu Verifikasi" -> "Ditolak"
     * Requirement: Alasan penolakan WAJIB diisi.
     * 
     * @param int $test_id
     */
    public function proses_penolakan($test_id = NULL)
    {
        $this->cek_hak_akses('pengujian_verify');

        if ($this->input->method() !== 'post') {
            redirect('pengujian/verifikasi');
        }

        $hasil = $this->Model_Pengujian->ambil_hasil_by_id($test_id);
        if (!$hasil) {
            show_404();
        }

        if ($hasil['status'] !== 'Menunggu Verifikasi') {
            $this->session->set_flashdata('pesan_gagal', 'Laporan ini tidak dalam status "Menunggu Verifikasi".');
            redirect('pengujian/detail/' . $test_id);
        }

        $alasan_penolakan = trim($this->input->post('alasan_penolakan', TRUE));

        // Validasi Mandatory Rejection Reason
        if (empty($alasan_penolakan)) {
            $this->session->set_flashdata('pesan_gagal', 'Alasan penolakan WAJIB diisi saat menolak hasil pengujian.');
            redirect('pengujian/detail/' . $test_id);
        }

        $verifier_id = $this->session->userdata('user_id');

        if ($this->Model_Pengujian->tolak_hasil($test_id, $verifier_id, $alasan_penolakan)) {
            $this->catat_audit('PENOLAKAN_VERIFIKASI', 'Pengujian', array(
                'test_id'          => $test_id,
                'sample_id'        => $hasil['sample_id'],
                'verifier_id'      => $verifier_id,
                'alasan_penolakan' => $alasan_penolakan,
                'status_sebelum'   => 'Menunggu Verifikasi',
                'status_sesudah'   => 'Ditolak',
                'waktu_verifikasi' => date('Y-m-d H:i:s')
            ));

            $this->session->set_flashdata('pesan_sukses', 'Hasil pengujian berhasil ditolak dan dikembalikan ke Penguji untuk revisi.');
            redirect('pengujian/detail/' . $test_id);
        } else {
            $this->session->set_flashdata('pesan_gagal', 'Gagal memproses penolakan laporan.');
            redirect('pengujian/detail/' . $test_id);
        }
    }

    /**
     * FASE 4 — Memproses Verifikasi Sesi Pengujian oleh Penyelia
     */
    public function verifikasi_sesi($session_id = NULL)
    {
        $this->cek_hak_akses('pengujian_verify');

        if ($this->input->method() !== 'post') {
            redirect('pengujian/verifikasi');
        }

        $sesi = $this->Model_Pengujian->ambil_sesi_by_id($session_id);
        if (!$sesi) {
            show_404();
        }

        if ($sesi['status'] !== 'Menunggu Verifikasi') {
            $this->session->set_flashdata('pesan_gagal', 'Sesi ini tidak dalam status "Menunggu Verifikasi".');
            redirect('pengujian/detail_sesi/' . $session_id);
        }

        $verifier_id = $this->session->userdata('user_id');

        if ($this->Model_Pengujian->verifikasi_sesi($session_id, $verifier_id)) {
            $this->catat_audit('VERIFIKASI_SESI', 'Pengujian', array(
                'testing_session_id' => $session_id,
                'sample_id'          => $sesi['sample_id'],
                'verifier_id'        => $verifier_id,
                'status_sebelum'     => 'Menunggu Verifikasi',
                'status_sesudah'     => 'Menunggu Approval'
            ));

            $this->session->set_flashdata('pesan_sukses', 'Sesi pengujian berhasil diverifikasi dan diteruskan ke Manajer Teknis (Menunggu Approval).');
            redirect('pengujian/detail_sesi/' . $session_id);
        } else {
            $this->session->set_flashdata('pesan_gagal', 'Gagal memproses verifikasi sesi.');
            redirect('pengujian/detail_sesi/' . $session_id);
        }
    }

    /**
     * FASE 4 — Memproses Penolakan Sesi Pengujian oleh Penyelia
     */
    public function tolak_sesi($session_id = NULL)
    {
        $this->cek_hak_akses('pengujian_verify');

        if ($this->input->method() !== 'post') {
            redirect('pengujian/verifikasi');
        }

        $sesi = $this->Model_Pengujian->ambil_sesi_by_id($session_id);
        if (!$sesi) {
            show_404();
        }

        if ($sesi['status'] !== 'Menunggu Verifikasi') {
            $this->session->set_flashdata('pesan_gagal', 'Sesi ini tidak dalam status "Menunggu Verifikasi".');
            redirect('pengujian/detail_sesi/' . $session_id);
        }

        $alasan_penolakan = trim($this->input->post('alasan_penolakan', TRUE));
        if (empty($alasan_penolakan)) {
            $this->session->set_flashdata('pesan_gagal', 'Alasan penolakan WAJIB diisi.');
            redirect('pengujian/detail_sesi/' . $session_id);
        }

        $verifier_id = $this->session->userdata('user_id');

        if ($this->Model_Pengujian->tolak_sesi($session_id, $verifier_id, $alasan_penolakan)) {
            $this->catat_audit('PENOLAKAN_VERIFIKASI_SESI', 'Pengujian', array(
                'testing_session_id' => $session_id,
                'sample_id'          => $sesi['sample_id'],
                'verifier_id'        => $verifier_id,
                'alasan_penolakan'   => $alasan_penolakan,
                'status_sebelum'     => 'Menunggu Verifikasi',
                'status_sesudah'     => 'Ditolak'
            ));

            $this->session->set_flashdata('pesan_sukses', 'Sesi pengujian berhasil ditolak dan dikembalikan ke Penguji untuk revisi.');
            redirect('pengujian/detail_sesi/' . $session_id);
        } else {
            $this->session->set_flashdata('pesan_gagal', 'Gagal memproses penolakan sesi.');
            redirect('pengujian/detail_sesi/' . $session_id);
        }
    }

    /**
     * FASE 5 — Memproses Approval Sesi Pengujian oleh Manajer Teknis
     */
    public function approve_sesi($session_id = NULL)
    {
        $this->cek_hak_akses('pengujian_approve');

        $sesi = $this->Model_Pengujian->ambil_sesi_by_id($session_id);
        if (!$sesi) {
            show_404();
        }

        // Pencegahan Bypass: Keputusan persetujuan akhir MT diproses di tingkat sampel
        // agar seluruh sesi wajib (Kimia & Mikrobiologi) dievaluasi kelengkapannya secara komprehensif.
        $this->session->set_flashdata('pesan_gagal', 'Persetujuan Manajer Teknis harus dilakukan di tingkat sampel untuk memastikan kelengkapan seluruh sesi pengujian wajib.');
        redirect('pengujian/tinjauan_lhu/' . $sesi['sample_id']);
    }

    /**
     * FASE 4 — Form & Proses Revisi Sesi Pengujian oleh Penguji
     * Transisi: "Ditolak" -> "Menunggu Verifikasi"
     * 
     * @param int $session_id
     */
    public function revisi_sesi($session_id = NULL)
    {
        $this->cek_hak_akses('pengujian_input');

        $sesi = $this->Model_Pengujian->ambil_sesi_by_id($session_id);
        if (!$sesi) {
            show_404();
        }

        // Ownership validation: Penguji hanya boleh merevisi session miliknya
        if ($sesi['penguji_id'] != $this->session->userdata('user_id')) {
            $this->session->set_flashdata('pesan_gagal', 'Anda tidak berhak merevisi sesi pengujian ini (ownership mismatch).');
            redirect('pengujian/riwayat');
        }

        // Status validation: Hanya session berstatus Ditolak yang dapat direvisi
        if ($sesi['status'] !== 'Ditolak') {
            $this->session->set_flashdata('pesan_gagal', 'Sesi pengujian ini tidak dalam status "Ditolak" sehingga tidak dapat direvisi.');
            redirect('pengujian/detail_sesi/' . $session_id);
        }

        $forms = $this->Model_Pengujian->ambil_hasil_by_session($session_id);

        if ($this->input->method() === 'post') {
            $forms_input = $this->input->post('forms', TRUE);
            if (!is_array($forms_input)) {
                $forms_input = array();
            }

            if ($this->Model_Pengujian->revisi_sesi($session_id, $forms_input)) {
                $this->catat_audit('REVISI_SESI', 'Pengujian', array(
                    'testing_session_id' => $session_id,
                    'sample_id'          => $sesi['sample_id'],
                    'status_sebelum'     => 'Ditolak',
                    'status_sesudah'     => 'Menunggu Verifikasi'
                ));

                $this->session->set_flashdata('pesan_sukses', 'Revisi hasil pengujian sesi berhasil disimpan dan dikirim kembali ke Penyelia (Menunggu Verifikasi).');
                redirect('pengujian/detail_sesi/' . $session_id);
            } else {
                $this->session->set_flashdata('pesan_gagal', 'Gagal menyimpan revisi sesi pengujian.');
            }
        }

        $this->data['halaman_aktif'] = 'pengujian_riwayat';
        $this->data['judul_halaman'] = 'Revisi Hasil Pengujian Sesi: ' . html_escape($sesi['nama_sampel']);
        $this->data['breadcrumbs']   = array(
            array('label' => 'Pengujian', 'url' => site_url('pengujian/riwayat')),
            array('label' => 'Detail Sesi', 'url' => site_url('pengujian/detail_sesi/' . $session_id)),
            array('label' => 'Revisi Sesi', 'url' => '#')
        );
        $this->data['sesi']          = $sesi;
        $this->data['forms']         = $forms;
        $this->data['hasil_forms']   = $forms;
        $this->data['daftar_alat']   = $this->Model_Alat->ambil_semua();
        $this->data['daftar_reagen'] = $this->Model_Reagen->ambil_semua();

        $this->muat_tampilan('pengujian/proses_sesi');
    }

    /**
     * FASE 4 — Form & Proses Revisi Hasil Pengujian oleh Penguji (Legacy Single Form)
     * Transisi: "Ditolak" -> "Menunggu Verifikasi"
     * 
     * @param int $test_id
     */
    public function revisi($test_id = NULL)
    {
        $this->cek_hak_akses('pengujian_input');

        $hasil = $this->Model_Pengujian->ambil_hasil_by_id($test_id);
        if (!$hasil) {
            show_404();
        }

        if ($hasil['status'] !== 'Ditolak') {
            $this->session->set_flashdata('pesan_gagal', 'Hasil pengujian ini tidak dalam status "Ditolak" sehingga tidak dapat direvisi.');
            redirect('pengujian/detail/' . $test_id);
        }

        if ($hasil['penguji_id'] != $this->session->userdata('user_id')) {
            $this->session->set_flashdata('pesan_gagal', 'Anda tidak berhak merevisi hasil pengujian ini karena bukan milik Anda.');
            redirect('pengujian/detail/' . $test_id);
        }

        $sampel = $this->Model_Sampel->ambil_by_id($hasil['sample_id']);

        if ($this->input->method() === 'post') {
            $template_id = $this->input->post('template_id', TRUE) ?: $hasil['template_id'];
            $method_id   = $this->input->post('method_id', TRUE) ?: $hasil['method_id'];
            $kesimpulan  = $this->input->post('kesimpulan', TRUE);
            $catatan     = $this->input->post('catatan', TRUE);
            $hasil_input = $this->input->post('hasil', TRUE);

            if (!is_array($hasil_input)) {
                $hasil_input = array();
            }

            $data_revisi = array(
                'method_id'   => $method_id ?: NULL,
                'template_id' => $template_id ?: NULL,
                'data_hasil'  => json_encode($hasil_input, JSON_UNESCAPED_UNICODE),
                'kesimpulan'  => $kesimpulan ?: 'Belum Disimpulkan',
                'catatan'     => $catatan ?: NULL
            );

            if ($this->Model_Pengujian->revisi_hasil($test_id, $data_revisi)) {
                // Regenerate LHU PDF
                $template_info = $this->Model_Template->ambil_by_id($template_id);
                $hasil_info    = $this->Model_Pengujian->ambil_hasil_by_id($test_id);
                $penguji_info  = $this->Model_Pengguna->ambil_by_id($hasil['penguji_id']);

                if ($template_info && !empty($template_info['skema_form'])) {
                    $template_info['fields_map'] = json_decode($template_info['skema_form'], true);
                }

                $pdf_dir  = FCPATH . 'uploads/laporan/';
                $pdf_name = 'LHU_REV_' . $hasil['sample_id'] . '_' . $test_id . '_' . time() . '.pdf';
                $pdf_path = $pdf_dir . $pdf_name;

                try {
                    $pdf_gen = new Laporan_PDF();
                    $pdf_gen->buat_laporan($sampel, $hasil_info, $template_info, $penguji_info, $pdf_path);
                    
                    $rel_path = 'uploads/laporan/' . $pdf_name;
                    $this->Model_Pengujian->update_file_laporan($test_id, $rel_path);
                } catch (Exception $e) {
                    // Log warning
                }

                $this->catat_audit('REVISI_HASIL', 'Pengujian', array(
                    'test_id'        => $test_id,
                    'sample_id'      => $hasil['sample_id'],
                    'status_sebelum' => 'Ditolak',
                    'status_sesudah' => 'Menunggu Verifikasi'
                ));

                $this->session->set_flashdata('pesan_sukses', 'Hasil pengujian telah berhasil direvisi dan dikirimkan kembali ke Penyelia (Menunggu Verifikasi).');
                redirect('pengujian/detail/' . $test_id);
            } else {
                $this->session->set_flashdata('pesan_gagal', 'Gagal menyimpan revisi pengujian.');
            }
        }

        $template_selected = NULL;
        if (!empty($hasil['template_id'])) {
            $template_selected = $this->Model_Template->ambil_by_id($hasil['template_id']);
        }

        $this->data['halaman_aktif']     = 'pengujian_riwayat';
        $this->data['judul_halaman']     = 'Revisi Hasil Pengujian: ' . html_escape($sampel['nama_sampel']);
        $this->data['breadcrumbs']       = array(
            array('label' => 'Pengujian', 'url' => site_url('pengujian/riwayat')),
            array('label' => 'Detail', 'url' => site_url('pengujian/detail/' . $test_id)),
            array('label' => 'Revisi', 'url' => '#')
        );
        $this->data['sampel']            = $sampel;
        $this->data['hasil']             = $hasil;
        $this->data['daftar_template']   = $this->Model_Template->ambil_aktif();
        $this->data['daftar_metode']     = $this->Model_Metode->ambil_aktif();
        $this->data['template_selected'] = $template_selected;

        $this->muat_tampilan('pengujian/revisi');
    }

    /**
     * FASE 5 — Menampilkan daftar laporan yang menunggu approval Manajer Teknis (Berbasis Sampel)
     */
    public function approval()
    {
        $this->cek_hak_akses('pengujian_approve');

        $this->data['halaman_aktif'] = 'pengujian_approval';
        $this->data['judul_halaman'] = 'Antrean Approval Laporan';

        $this->data['antrean'] = $this->Model_Pengujian->ambil_antrean_approval_sampel();

        $this->muat_tampilan('pengujian/approval');
    }

    /**
     * FASE 5 — Halaman Tinjauan LHU Komprehensif Lintas Sesi untuk Manajer Teknis (MT)
     *
     * Menampilkan seluruh hasil pengujian per sampel (Kimia, Mikrobiologi, atau keduanya)
     * secara transparan beserta evaluasi kelengkapan sesi wajib sebelum persetujuan final.
     *
     * @param int $sample_id
     */
    public function tinjauan_lhu($sample_id = NULL)
    {
        $this->cek_hak_akses('pengujian_approve');

        if (empty($sample_id)) {
            show_404();
        }

        $tinjauan = $this->Model_Pengujian->ambil_tinjauan_lhu_sampel($sample_id);
        if (!$tinjauan) {
            show_404();
        }

        $this->data['halaman_aktif'] = 'pengujian_approval';
        $this->data['judul_halaman'] = 'Tinjauan LHU Sampel: ' . html_escape($tinjauan['sampel']['nama_sampel']);
        $this->data['sampel']        = $tinjauan['sampel'];
        $this->data['kelengkapan']   = $tinjauan['kelengkapan'];
        $this->data['sesi_list']     = $tinjauan['sesi_list'];

        $this->muat_tampilan('pengujian/tinjauan_lhu');
    }

    /**
     * FASE 5 (Tahap 3) — Memproses Approval MT untuk Seluruh Sesi Wajib Sampel
     *
     * @param int $sample_id
     */
    public function proses_approval_sampel($sample_id = NULL)
    {
        $this->cek_hak_akses('pengujian_approve');

        if ($this->input->method() !== 'post') {
            redirect('pengujian/tinjauan_lhu/' . $sample_id);
        }

        $approver_id = (int)$this->session->userdata('user_id');
        $username    = (string)$this->session->userdata('username');

        $hasil = $this->Model_Pengujian->approve_sampel_mt($sample_id, $approver_id, $username);

        if ($hasil['sukses']) {
            $this->session->set_flashdata('pesan_sukses', $hasil['pesan'] . ' Laporan kini dapat dilihat di Riwayat Approval.');
            redirect('pengujian/tinjauan_lhu/' . $sample_id);
        } else {
            $this->session->set_flashdata('pesan_gagal', $hasil['pesan']);
            redirect('pengujian/tinjauan_lhu/' . $sample_id);
        }
    }

    /**
     * FASE 5 (Tahap 3) — Memproses Penolakan Pengujian Sampel oleh MT dengan Alasan Wajib
     *
     * @param int $sample_id
     */
    public function proses_penolakan_sampel($sample_id = NULL)
    {
        $this->cek_hak_akses('pengujian_approve');

        if ($this->input->method() !== 'post') {
            redirect('pengujian/tinjauan_lhu/' . $sample_id);
        }

        $alasan = $this->input->post('alasan_penolakan', TRUE);
        $approver_id = (int)$this->session->userdata('user_id');
        $username    = (string)$this->session->userdata('username');

        $hasil = $this->Model_Pengujian->tolak_sampel_mt($sample_id, $approver_id, $alasan, $username);

        if ($hasil['sukses']) {
            $this->session->set_flashdata('pesan_sukses', $hasil['pesan']);
            redirect('pengujian/tinjauan_lhu/' . $sample_id);
        } else {
            $this->session->set_flashdata('pesan_gagal', $hasil['pesan']);
            redirect('pengujian/tinjauan_lhu/' . $sample_id);
        }
    }

    /**
     * Historical Laporan yang Telah Diverifikasi oleh Penyelia
     */
    public function riwayat_verifikasi()
    {
        $this->cek_hak_akses('pengujian_verify');

        $this->data['halaman_aktif'] = 'pengujian_riwayat_verifikasi';
        $this->data['judul_halaman'] = 'Riwayat Verifikasi Laporan (Penyelia)';

        $user_id = $this->session->userdata('user_id');
        $this->data['daftar_hasil'] = $this->Model_Pengujian->ambil_riwayat_verifikasi_by_user($user_id);

        $this->muat_tampilan('pengujian/riwayat');
    }

    /**
     * Historical Laporan yang Telah Diapprove oleh Manajer Teknis
     */
    public function riwayat_approval()
    {
        $this->cek_hak_akses('pengujian_approve');

        $this->data['halaman_aktif'] = 'pengujian_riwayat_approval';
        $this->data['judul_halaman'] = 'Riwayat Approval Laporan (Manajer Teknis)';

        $user_id = $this->session->userdata('user_id');
        $this->data['daftar_hasil'] = $this->Model_Pengujian->ambil_riwayat_approval_by_user($user_id);

        $this->muat_tampilan('pengujian/riwayat');
    }

    /**
     * FASE 5 — Memproses Approval oleh Manajer Teknis
     * 
     * @param int $test_id
     */
    public function proses_approval($test_id = NULL)
    {
        $this->cek_hak_akses('pengujian_approve');

        if ($this->input->method() !== 'post') {
            show_404();
        }

        $hasil = $this->Model_Pengujian->ambil_hasil_by_id($test_id);
        if (!$hasil) {
            $this->session->set_flashdata('pesan_gagal', 'Data pengujian tidak ditemukan.');
            redirect('pengujian/approval');
        }

        if ($hasil['status'] !== 'Menunggu Approval') {
            $this->session->set_flashdata('pesan_gagal', 'Laporan ini tidak dalam status "Menunggu Approval".');
            redirect('pengujian/detail/' . $test_id);
        }

        $approver_id = $this->session->userdata('user_id');

        if ($this->Model_Pengujian->approve_hasil($test_id, $approver_id)) {
            $this->catat_audit('APPROVAL', 'Pengujian', array(
                'test_id'        => $test_id,
                'sample_id'      => $hasil['sample_id'],
                'status_sebelum' => 'Menunggu Approval',
                'status_sesudah' => 'Approved / Final'
            ));

            $this->session->set_flashdata('pesan_sukses', 'Laporan hasil pengujian berhasil di-approve (Approved / Final).');
            redirect('pengujian/detail/' . $test_id);
        } else {
            $this->session->set_flashdata('pesan_gagal', 'Terjadi kesalahan saat menyimpan approval.');
            redirect('pengujian/detail/' . $test_id);
        }
    }
}
