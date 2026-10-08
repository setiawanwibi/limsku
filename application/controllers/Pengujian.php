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

        $daftar_template = $this->Model_Template->ambil_by_kategori($jenis_pengujian);

        $this->data['halaman_aktif']    = 'pengujian_antrean';
        $this->data['judul_halaman']    = 'Pilih Form / Parameter Pengujian (' . $jenis_pengujian . ')';
        $this->data['breadcrumbs']      = array(
            array('label' => 'Pengujian', 'url' => site_url('pengujian')),
            array('label' => 'Pilih Form', 'url' => '#')
        );
        $this->data['sampel']           = $sampel;
        $this->data['jenis_pengujian']  = $jenis_pengujian;
        $this->data['daftar_template']  = $daftar_template;

        $this->muat_tampilan('pengujian/pilih_form');
    }

    /**
     * STEP 6 & 7 — Inisialisasi Sesi Pengujian & Prevent Mixing Validation
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

        $jenis_pengujian = $this->input->post('jenis_pengujian', TRUE);
        $template_ids    = $this->input->post('template_ids', TRUE);

        // Validation 1: Mandatory Selection
        if (empty($jenis_pengujian) || !in_array($jenis_pengujian, array('Kimia', 'Mikrobiologi'), TRUE)) {
            $this->session->set_flashdata('pesan_gagal', 'Jenis pengujian wajib dipilih secara valid.');
            redirect('pengujian/pilih_jenis/' . $sample_id);
        }

        if (empty($template_ids) || !is_array($template_ids)) {
            $this->session->set_flashdata('pesan_gagal', 'Anda wajib memilih minimal 1 form/parameter pengujian.');
            redirect('pengujian/pilih_form/' . $sample_id . '?jenis=' . urlencode($jenis_pengujian));
        }

        // Validation 2: STEP 6 BACKEND PREVENT MIXING VALIDATION
        foreach ($template_ids as $tid) {
            $tpl = $this->Model_Template->ambil_by_id($tid);
            if (!$tpl || $tpl['kategori'] !== $jenis_pengujian) {
                // Reject request & log audit violation
                $this->catat_audit('INVALID_TESTING_SESSION', 'Pengujian', array(
                    'sample_id'       => $sample_id,
                    'jenis_pengujian' => $jenis_pengujian,
                    'invalid_form_id' => $tid,
                    'invalid_kategori'=> $tpl ? $tpl['kategori'] : 'UNKNOWN',
                    'alasan'          => 'Terdeteksi pencampuran form Kimia & Mikrobiologi dalam 1 sesi.'
                ));

                $this->session->set_flashdata('pesan_gagal', 'Permintaan ditolak: Form/parameter yang dipilih bertentangan dengan jenis pengujian yang dipilih. (Kimia & Mikrobiologi tidak boleh dicampur).');
                redirect('pengujian/pilih_form/' . $sample_id . '?jenis=' . urlencode($jenis_pengujian));
            }
        }

        $penguji_id = $this->session->userdata('user_id');

        $session_id = $this->Model_Pengujian->buat_sesi_pengujian($sample_id, $penguji_id, $jenis_pengujian, $template_ids);

        if ($session_id) {
            $this->catat_audit('START_TESTING_SESSION', 'Pengujian', array(
                'testing_session_id' => $session_id,
                'sample_id'          => $sample_id,
                'jenis_pengujian'    => $jenis_pengujian,
                'form_count'         => count($template_ids),
                'template_ids'       => implode(',', $template_ids),
                'penguji_id'         => $penguji_id
            ));

            $this->session->set_flashdata('pesan_sukses', 'Sesi pengujian (' . $jenis_pengujian . ') berhasil dimulai dengan ' . count($template_ids) . ' form.');
            redirect('pengujian/proses_sesi/' . $session_id);
        } else {
            $this->session->set_flashdata('pesan_gagal', 'Gagal membuat sesi pengujian.');
            redirect('pengujian/antrean');
        }
    }

    /**
     * Halaman Pengisian Hasil Multiple Form Sesi Pengujian
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

            if ($this->Model_Pengujian->simpan_hasil_sesi($session_id, $forms_input)) {
                $this->catat_audit('SAVE_SESSION_RESULTS', 'Pengujian', array(
                    'testing_session_id' => $session_id,
                    'sample_id'          => $sesi['sample_id'],
                    'status'             => 'Menunggu Verifikasi'
                ));

                $this->session->set_flashdata('pesan_sukses', 'Seluruh hasil pengujian pada sesi ini berhasil disimpan dan diteruskan ke Penyelia (Menunggu Verifikasi).');
                redirect('pengujian/detail_sesi/' . $session_id);
            } else {
                $this->session->set_flashdata('pesan_gagal', 'Gagal menyimpan hasil pengujian sesi.');
            }
        }

        $this->data['halaman_aktif'] = 'pengujian_antrean';
        $this->data['judul_halaman'] = 'Pelaksanaan Sesi Pengujian (' . $sesi['jenis_pengujian'] . ')';
        $this->data['breadcrumbs']   = array(
            array('label' => 'Pengujian', 'url' => site_url('pengujian')),
            array('label' => 'Proses Sesi Pengujian', 'url' => '#')
        );
        $this->data['sesi']          = $sesi;
        $this->data['forms']         = $forms;
        $this->data['hasil_forms']   = $forms;

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

        $sesi = $this->Model_Pengujian->ambil_sesi_by_id($session_id);
        if (!$sesi) {
            show_404();
        }

        $forms = $this->Model_Pengujian->ambil_hasil_by_session($session_id);

        $this->data['halaman_aktif'] = 'pengujian_riwayat';
        $this->data['judul_halaman'] = 'Detail Sesi Pengujian: ' . html_escape($sesi['nama_sampel']);
        $this->data['breadcrumbs']   = array(
            array('label' => 'Pengujian', 'url' => site_url('pengujian/riwayat')),
            array('label' => 'Detail Sesi', 'url' => '#')
        );
        $this->data['sesi']          = $sesi;
        $this->data['forms']         = $forms;

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
        $this->data['breadcrumbs']   = array(
            array('label' => 'Laporan', 'url' => site_url('pengujian/riwayat')),
            array('label' => 'Riwayat Hasil Uji', 'url' => '#')
        );

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
     * FASE 4 — Halaman Workspace Penyelia (Verifikasi Laporan)
     */
    public function verifikasi()
    {
        $this->cek_hak_akses('pengujian_verify');

        $this->data['halaman_aktif'] = 'pengujian_verifikasi';
        $this->data['judul_halaman'] = 'Verifikasi Laporan';
        $this->data['breadcrumbs']   = array(
            array('label' => 'Pengujian', 'url' => site_url('pengujian')),
            array('label' => 'Verifikasi Laporan', 'url' => '#')
        );

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

        if ($this->input->method() !== 'post') {
            redirect('pengujian/approval');
        }

        $sesi = $this->Model_Pengujian->ambil_sesi_by_id($session_id);
        if (!$sesi) {
            show_404();
        }

        if ($sesi['status'] !== 'Menunggu Approval') {
            $this->session->set_flashdata('pesan_gagal', 'Sesi ini tidak dalam status "Menunggu Approval".');
            redirect('pengujian/detail_sesi/' . $session_id);
        }

        $approver_id = $this->session->userdata('user_id');

        if ($this->Model_Pengujian->approve_sesi($session_id, $approver_id)) {
            // Generate official multi-form LHU PDF
            $sampel_info   = $this->Model_Sampel->ambil_by_id($sesi['sample_id']);
            $sesi_info     = $this->Model_Pengujian->ambil_sesi_by_id($session_id);
            $forms_info    = $this->Model_Pengujian->ambil_hasil_by_session($session_id);
            $penguji_info  = $this->Model_Pengguna->ambil_by_id($sesi['penguji_id']);
            $verifier_info = $this->Model_Pengguna->ambil_by_id($sesi['verifier_id']);
            $approver_info = $this->Model_Pengguna->ambil_by_id($approver_id);

            $pdf_dir  = FCPATH . 'uploads/laporan/';
            $pdf_name = 'LHU_FINAL_' . $sesi['sample_id'] . '_SESI' . $session_id . '_' . time() . '.pdf';
            $pdf_path = $pdf_dir . $pdf_name;

            try {
                $pdf_gen = new Laporan_PDF();
                $pdf_gen->buat_laporan_sesi($sampel_info, $sesi_info, $forms_info, $penguji_info, $verifier_info, $approver_info, $pdf_path);
                
                $rel_path = 'uploads/laporan/' . $pdf_name;
                $this->Model_Pengujian->update_file_laporan_sesi($session_id, $rel_path);
            } catch (Exception $e) {
                log_message('error', 'Gagal membuat PDF LHU Sesi: ' . $e->getMessage());
            }

            $this->catat_audit('APPROVAL_SESI', 'Pengujian', array(
                'testing_session_id' => $session_id,
                'sample_id'          => $sesi['sample_id'],
                'approver_id'        => $approver_id,
                'status_sebelum'     => 'Menunggu Approval',
                'status_sesudah'     => 'Approved / Final'
            ));

            $this->session->set_flashdata('pesan_sukses', 'Sesi pengujian berhasil di-approve (Approved / Final) dan Laporan PDF Final (LHU) telah diterbitkan.');
            redirect('pengujian/detail_sesi/' . $session_id);
        } else {
            $this->session->set_flashdata('pesan_gagal', 'Gagal memproses approval sesi.');
            redirect('pengujian/detail_sesi/' . $session_id);
        }
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
     * FASE 5 — Menampilkan daftar laporan yang menunggu approval Manajer Teknis
     */
    public function approval()
    {
        $this->cek_hak_akses('pengujian_approve');

        $this->data['halaman_aktif'] = 'pengujian_approval';
        $this->data['judul_halaman'] = 'Antrean Approval Laporan';
        $this->data['breadcrumbs']   = array(
            array('label' => 'Pengujian', 'url' => site_url('pengujian')),
            array('label' => 'Approval', 'url' => '#')
        );

        $this->data['antrean'] = $this->Model_Pengujian->ambil_antrean_approval();

        $this->muat_tampilan('pengujian/approval');
    }

    /**
     * Historical Laporan yang Telah Diverifikasi oleh Penyelia
     */
    public function riwayat_verifikasi()
    {
        $this->cek_hak_akses('pengujian_verify');

        $this->data['halaman_aktif'] = 'pengujian_riwayat_verifikasi';
        $this->data['judul_halaman'] = 'Riwayat Verifikasi Laporan (Penyelia)';
        $this->data['breadcrumbs']   = array(
            array('label' => 'Verifikasi Laporan', 'url' => site_url('pengujian/verifikasi')),
            array('label' => 'Riwayat Verifikasi', 'url' => '#')
        );

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
        $this->data['breadcrumbs']   = array(
            array('label' => 'Approval Laporan', 'url' => site_url('pengujian/approval')),
            array('label' => 'Riwayat Approval', 'url' => '#')
        );

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
