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
        $this->data['breadcrumbs']   = array(
            array('label' => 'Pengujian', 'url' => site_url('pengujian')),
            array('label' => 'Antrean', 'url' => '#')
        );

        $this->data['antrean_sampel'] = $this->Model_Pengujian->ambil_antrean();
        $this->data['sedang_diuji']   = $this->Model_Pengujian->ambil_dalam_pengujian($this->session->userdata('user_id'));

        $this->muat_tampilan('pengujian/antrean');
    }

    /**
     * Klaim Sampel Mandiri (Self-Assignment)
     * Transisi status: "Menunggu Pengujian" -> "Sedang Diuji"
     * 
     * @param int $sample_id
     */
    public function klaim($sample_id = NULL)
    {
        $this->cek_hak_akses('pengujian_assign');

        $penguji_id = $this->session->userdata('user_id');

        if ($this->Model_Pengujian->klaim_sampel($sample_id, $penguji_id)) {
            $this->catat_audit('ASSIGN', 'Pengujian', array(
                'sample_id'  => $sample_id,
                'penguji_id' => $penguji_id,
                'status'     => 'Sedang Diuji'
            ));

            $this->session->set_flashdata('pesan_sukses', 'Sampel berhasil diklaim. Silakan pilih metode dan isi form pengujian.');
            redirect('pengujian/proses/' . $sample_id);
        } else {
            $this->session->set_flashdata('pesan_gagal', 'Gagal mengklaim sampel (sampel mungkin sudah diklaim atau berstatus lain).');
            redirect('pengujian/antrean');
        }
    }

    /**
     * Halaman Pengisian Form Pengujian Dinamis
     * 
     * @param int $sample_id
     */
    public function proses($sample_id = NULL)
    {
        $this->cek_hak_akses('pengujian_input');

        $sampel = $this->Model_Sampel->ambil_by_id($sample_id);
        if (!$sampel) {
            show_404();
        }

        // Pastikan sampel berstatus "Sedang Diuji"
        if ($sampel['status'] !== 'Sedang Diuji') {
            $this->session->set_flashdata('pesan_gagal', 'Sampel tidak berstatus "Sedang Diuji". Klaim sampel terlebih dahulu.');
            redirect('pengujian/antrean');
        }

        // Tangani submit hasil pengujian
        if ($this->input->method() === 'post') {
            $template_id = $this->input->post('template_id', TRUE);
            $method_id   = $this->input->post('method_id', TRUE);
            $kesimpulan  = $this->input->post('kesimpulan', TRUE);
            $catatan     = $this->input->post('catatan', TRUE);
            $hasil_input = $this->input->post('hasil', TRUE);

            if (!is_array($hasil_input)) {
                $hasil_input = array();
            }

            $penguji_id = $this->session->userdata('user_id');

            $data_hasil_uji = array(
                'sample_id'    => $sample_id,
                'method_id'    => $method_id ?: NULL,
                'template_id'  => $template_id ?: NULL,
                'penguji_id'   => $penguji_id,
                'data_hasil'   => json_encode($hasil_input, JSON_UNESCAPED_UNICODE),
                'kesimpulan'   => $kesimpulan ?: 'Belum Disimpulkan',
                'catatan'      => $catatan ?: NULL,
                'waktu_mulai'  => date('Y-m-d H:i:s')
            );

            // Simpan Hasil Uji & Transisi status ke "Menunggu Verifikasi"
            $test_id = $this->Model_Pengujian->simpan_hasil_uji($data_hasil_uji);

            if ($test_id) {
                // Generate Laporan Hasil Uji PDF (Pra-Verifikasi)
                $template_info = $this->Model_Template->ambil_by_id($template_id);
                $hasil_info    = $this->Model_Pengujian->ambil_hasil_by_id($test_id);
                $penguji_info  = $this->Model_Pengguna->ambil_by_id($penguji_id);

                // Buat mapping fields untuk label di PDF
                if ($template_info && !empty($template_info['skema_form'])) {
                    $template_info['fields_map'] = json_decode($template_info['skema_form'], true);
                }

                $pdf_dir  = FCPATH . 'uploads/laporan/';
                $pdf_name = 'LHU_' . $sample_id . '_' . $test_id . '_' . time() . '.pdf';
                $pdf_path = $pdf_dir . $pdf_name;

                try {
                    $pdf_gen = new Laporan_PDF();
                    $pdf_gen->buat_laporan($sampel, $hasil_info, $template_info, $penguji_info, $pdf_path);
                    
                    // Simpan path PDF di database
                    $rel_path = 'uploads/laporan/' . $pdf_name;
                    $this->Model_Pengujian->update_file_laporan($test_id, $rel_path);

                    $this->catat_audit('GENERATE_REPORT', 'Pengujian', array(
                        'test_id'   => $test_id,
                        'sample_id' => $sample_id,
                        'file_pdf'  => $rel_path
                    ));
                } catch (Exception $e) {
                    // Log warning jika PDF generation ada hambatan kecil
                }

                $this->catat_audit('SAVE_TEST_RESULT', 'Pengujian', array(
                    'test_id'    => $test_id,
                    'sample_id'  => $sample_id,
                    'kesimpulan' => $kesimpulan,
                    'status'     => 'Menunggu Verifikasi'
                ));

                $this->session->set_flashdata('pesan_sukses', 'Hasil pengujian berhasil disimpan. Status sampel diperbarui menjadi "Menunggu Verifikasi" dan Laporan PDF telah terbentuk.');
                redirect('pengujian/detail/' . $test_id);
            } else {
                $this->session->set_flashdata('pesan_gagal', 'Gagal menyimpan hasil pengujian.');
            }
        }

        $template_selected_id = $this->input->get('template_id', TRUE);
        $template_selected    = NULL;
        if ($template_selected_id) {
            $template_selected = $this->Model_Template->ambil_by_id($template_selected_id);
        }

        $this->data['halaman_aktif']      = 'pengujian_antrean';
        $this->data['judul_halaman']      = 'Pelaksanaan Pengujian Sampel';
        $this->data['breadcrumbs']        = array(
            array('label' => 'Pengujian', 'url' => site_url('pengujian')),
            array('label' => 'Proses Pengujian', 'url' => '#')
        );
        $this->data['sampel']             = $sampel;
        $this->data['daftar_template']    = $this->Model_Template->ambil_aktif();
        $this->data['daftar_metode']      = $this->Model_Metode->ambil_aktif();
        $this->data['template_selected']  = $template_selected;

        $this->muat_tampilan('pengujian/proses');
    }

    /**
     * Halaman Riwayat Hasil Pengujian
     */
    public function riwayat()
    {
        $this->cek_hak_akses('pengujian_view');

        $this->data['halaman_aktif'] = 'pengujian_riwayat';
        $this->data['judul_halaman'] = 'Riwayat Hasil Pengujian';
        $this->data['breadcrumbs']   = array(
            array('label' => 'Pengujian', 'url' => site_url('pengujian')),
            array('label' => 'Riwayat Hasil Uji', 'url' => '#')
        );

        $this->data['daftar_hasil'] = $this->Model_Pengujian->ambil_semua_hasil();

        $this->muat_tampilan('pengujian/riwayat');
    }

    /**
     * Detail Hasil Pengujian & Dokumen Laporan
     * 
     * @param int $test_id
     */
    public function detail($test_id = NULL)
    {
        $this->cek_hak_akses('pengujian_view');

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
        if (!$hasil || empty($hasil['file_laporan'])) {
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
     * FASE 4 — Halaman Queue Laporan "Menunggu Verifikasi" untuk Penyelia
     */
    public function verifikasi()
    {
        $this->cek_hak_akses('pengujian_verify');

        $this->data['halaman_aktif'] = 'pengujian_verifikasi';
        $this->data['judul_halaman'] = 'Queue Verifikasi Laporan Pengujian';
        $this->data['breadcrumbs']   = array(
            array('label' => 'Pengujian', 'url' => site_url('pengujian')),
            array('label' => 'Verifikasi', 'url' => '#')
        );

        $this->data['antrean_verifikasi'] = $this->Model_Pengujian->ambil_antrean_verifikasi();

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
     * FASE 4 — Form & Proses Revisi Hasil Pengujian oleh Penguji
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
