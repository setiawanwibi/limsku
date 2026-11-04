<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Logbook Alat Laboratorium LIMSKU
 *
 * Menampilkan pencatatan otomatis penggunaan instrumen laboratorium,
 * pemantauan utilisasi alat per pengujian/sampel, serta pelaporan digital
 * buku log instrumen untuk seluruh role pengguna laboratorium.
 */
class Logbook extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        // Akses terbuka untuk SELURUH role yang telah terotentikasi di LIMSKU
        $this->cek_login();

        $this->load->model('Model_Logbook');
        $this->load->model('Model_Alat');
        $this->load->model('Model_Sampel');
        $this->load->model('Model_Pengguna');
    }

    /**
     * Halaman Utama Logbook Penggunaan Alat Laboratorium (Tabel Komprehensif)
     */
    public function index()
    {
        // Jalankan auto-sync dari pengujian lama jika diperlukan agar logbook terisi
        $this->Model_Logbook->sinkronisasi_historis();

        $instrument_id   = $this->input->get('instrument_id', TRUE);
        $tanggal_mulai   = $this->input->get('tanggal_mulai', TRUE);
        $tanggal_selesai = $this->input->get('tanggal_selesai', TRUE);
        $q               = $this->input->get('q', TRUE);
        $jenis_pengujian = $this->input->get('jenis_pengujian', TRUE);

        $filter = array(
            'instrument_id'   => !empty($instrument_id) ? (int)$instrument_id : NULL,
            'tanggal_mulai'   => !empty($tanggal_mulai) ? $tanggal_mulai : NULL,
            'tanggal_selesai' => !empty($tanggal_selesai) ? $tanggal_selesai : NULL,
            'q'               => !empty($q) ? trim($q) : NULL,
            'jenis_pengujian' => !empty($jenis_pengujian) ? trim($jenis_pengujian) : NULL
        );

        $daftar_logbook = $this->Model_Logbook->ambil_logbook($filter);
        $statistik      = $this->Model_Logbook->ambil_statistik();
        $daftar_alat    = $this->Model_Alat->ambil_semua();

        // Data instrumen terpilih jika filter spesifik
        $alat_terpilih = NULL;
        if (!empty($filter['instrument_id'])) {
            $alat_terpilih = $this->Model_Alat->ambil_by_id($filter['instrument_id']);
        }

        $this->data['halaman_aktif']  = 'logbook_alat';
        $this->data['judul_halaman']  = 'Logbook Alat Laboratorium';
        $this->data['daftar_logbook'] = $daftar_logbook;
        $this->data['statistik']      = $statistik;
        $this->data['daftar_alat']    = $daftar_alat;
        $this->data['filter']         = $filter;
        $this->data['alat_terpilih']  = $alat_terpilih;

        $this->catat_audit('VIEW_INSTRUMENT_LOGBOOK', 'Logbook', array(
            'filter' => $filter,
            'total'  => count($daftar_logbook)
        ));

        $this->muat_tampilan('logbook/index');
    }

    /**
     * Halaman Buku Log Khusus Satu Alat Tertentu
     *
     * @param int $instrument_id
     */
    public function alat($instrument_id = NULL)
    {
        $instrument_id = (int)$instrument_id;
        if ($instrument_id <= 0) {
            redirect('logbook');
        }

        $alat = $this->Model_Alat->ambil_by_id($instrument_id);
        if (!$alat) {
            $this->session->set_flashdata('pesan_gagal', 'Data alat laboratorium tidak ditemukan.');
            redirect('logbook');
        }

        $tanggal_mulai   = $this->input->get('tanggal_mulai', TRUE);
        $tanggal_selesai = $this->input->get('tanggal_selesai', TRUE);
        $q               = $this->input->get('q', TRUE);

        $filter = array(
            'instrument_id'   => $instrument_id,
            'tanggal_mulai'   => $tanggal_mulai,
            'tanggal_selesai' => $tanggal_selesai,
            'q'               => $q
        );

        $daftar_logbook = $this->Model_Logbook->ambil_by_instrument($instrument_id, $filter);

        $this->data['halaman_aktif']  = 'logbook_alat';
        $this->data['judul_halaman']  = 'Buku Log: ' . html_escape($alat['nama_barang']) . ' (' . html_escape($alat['kode_barang']) . ')';
        $this->data['alat']           = $alat;
        $this->data['daftar_logbook'] = $daftar_logbook;
        $this->data['filter']         = $filter;

        $this->muat_tampilan('logbook/detail_alat');
    }

    /**
     * Cetak Tampilan Resmi Buku Log Alat (Format Cetak GLP/ISO 17025)
     */
    public function cetak()
    {
        $instrument_id   = $this->input->get('instrument_id', TRUE);
        $tanggal_mulai   = $this->input->get('tanggal_mulai', TRUE);
        $tanggal_selesai = $this->input->get('tanggal_selesai', TRUE);
        $q               = $this->input->get('q', TRUE);

        $filter = array(
            'instrument_id'   => !empty($instrument_id) ? (int)$instrument_id : NULL,
            'tanggal_mulai'   => !empty($tanggal_mulai) ? $tanggal_mulai : NULL,
            'tanggal_selesai' => !empty($tanggal_selesai) ? $tanggal_selesai : NULL,
            'q'               => !empty($q) ? trim($q) : NULL
        );

        $daftar_logbook = $this->Model_Logbook->ambil_logbook($filter);
        $alat_terpilih  = !empty($filter['instrument_id']) ? $this->Model_Alat->ambil_by_id($filter['instrument_id']) : NULL;

        $this->data['daftar_logbook'] = $daftar_logbook;
        $this->data['alat_terpilih']  = $alat_terpilih;
        $this->data['filter']         = $filter;
        $this->data['waktu_cetak']    = date('d F Y H:i:s');
        $this->data['pencetak']       = $this->session->userdata('nama_lengkap') ?: 'Petugas Laboratorium';

        $this->load->view('logbook/cetak', $this->data);
    }
}

