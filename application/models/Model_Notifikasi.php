<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Notifikasi LIMSKU
 * 
 * Menangani agregasi notifikasi, antrean tugas tertunda (pending actions),
 * dan badge indikator real-time pada setiap menu navigasi sidebar
 * sesuai dengan alur profesional laboratorium BBPOM dan hak akses (role).
 */
class Model_Notifikasi extends CI_Model
{
    /**
     * Cache hasil perhitungan badge dalam satu lifecycle request
     * @var array|null
     */
    protected static $cache_badges = NULL;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_Hak_Akses');
    }

    /**
     * Mengambil seluruh jumlah badge notifikasi per menu untuk pengguna yang sedang login
     * 
     * @param int|null $user_id
     * @param int|null $role_id
     * @return array
     */
    public function ambil_semua_badge($user_id = NULL, $role_id = NULL)
    {
        if (self::$cache_badges !== NULL) {
            return self::$cache_badges;
        }

        if (empty($user_id)) {
            $user_id = (int) $this->session->userdata('user_id');
        }
        if (empty($role_id)) {
            $role_id = (int) $this->session->userdata('role_id');
        }

        if (empty($user_id)) {
            return array();
        }

        // Cek izin peran
        $bisa_sampel     = $this->Model_Hak_Akses->memiliki_akses($role_id, 'sampel_view');
        $bisa_antrean    = $this->Model_Hak_Akses->memiliki_akses($role_id, 'pengujian_view');
        $bisa_verifikasi = $this->Model_Hak_Akses->memiliki_akses($role_id, 'pengujian_verify');
        $bisa_approval   = $this->Model_Hak_Akses->memiliki_akses($role_id, 'pengujian_approve');
        $bisa_laporan    = $this->Model_Hak_Akses->memiliki_akses($role_id, 'laporan_view');

        $badges = array(
            'akun'                       => 0,
            'akun_label'                 => '',
            'akun_type'                  => 'warning',
            'sampel'                     => 0,
            'sampel_label'               => '',
            'sampel_type'                => 'danger',
            'pengujian_antrean'          => 0,
            'pengujian_antrean_label'    => '',
            'pengujian_antrean_type'     => 'danger',
            'pengujian_verifikasi'       => 0,
            'pengujian_verifikasi_label' => '',
            'pengujian_verifikasi_type'  => 'danger',
            'pengujian_approval'         => 0,
            'pengujian_approval_label'   => '',
            'pengujian_approval_type'    => 'danger',
            'pengujian_riwayat'          => 0,
            'pengujian_riwayat_label'    => '',
            'pengujian_riwayat_type'     => 'danger',
            'total_tindakan'             => 0,
        );

        // =========================================================================
        // 1. MENU: PROFIL & TTD DIGITAL (akun)
        // Jika user belum memiliki spesimen TTD digital tersimpan
        // =========================================================================
        $this->db->where('user_id', $user_id);
        $sig = $this->db->get('user_signatures')->row_array();
        if (empty($sig) || empty($sig['signature_file'])) {
            $badges['akun'] = 1;
            $badges['akun_label'] = 'Spesimen TTD Digital belum diunggah';
            $badges['akun_type']  = 'danger';
        }

        // =========================================================================
        // 2. MENU: DATA SAMPEL (sampel)
        // Sampel yang masih "Menunggu Pengujian" (antrean penerimaan/alokasi)
        // =========================================================================
        if ($bisa_sampel) {
            $this->db->where('status', 'Menunggu Pengujian');
            $jml_sampel_menunggu = $this->db->count_all_results('samples');
            $badges['sampel'] = (int) $jml_sampel_menunggu;
            $badges['sampel_label'] = $jml_sampel_menunggu . ' sampel menunggu pengujian';
            $badges['sampel_type']  = 'danger';
        }

        // =========================================================================
        // 3. MENU: ANTREAN PENGUJIAN (pengujian_antrean)
        // Sampel yang siap untuk diklaim / diuji analis di laboratorium
        // =========================================================================
        if ($bisa_antrean) {
            $this->db->where('status', 'Menunggu Pengujian');
            $jml_antrean_uji = $this->db->count_all_results('samples');
            $badges['pengujian_antrean'] = (int) $jml_antrean_uji;
            $badges['pengujian_antrean_label'] = $jml_antrean_uji . ' sampel dalam antrean pengujian';
            $badges['pengujian_antrean_type']  = 'danger';
        }

        // =========================================================================
        // 4. MENU: VERIFIKASI LAPORAN (pengujian_verifikasi)
        // Sesi pengujian yang menunggu review & persetujuan Penyelia
        // =========================================================================
        if ($bisa_verifikasi) {
            $this->db->where('status', 'Menunggu Verifikasi');
            $jml_verifikasi = $this->db->count_all_results('testing_sessions');
            $badges['pengujian_verifikasi'] = (int) $jml_verifikasi;
            $badges['pengujian_verifikasi_label'] = $jml_verifikasi . ' sesi menunggu verifikasi penyelia';
            $badges['pengujian_verifikasi_type']  = 'danger';
        }

        // =========================================================================
        // 5. MENU: APPROVAL LAPORAN (pengujian_approval)
        // Sampel yang menunggu approval final / pengesahan Manajer Teknis
        // =========================================================================
        if ($bisa_approval) {
            $this->db->where('status', 'Menunggu Approval');
            $jml_approval = $this->db->count_all_results('samples');
            if ($jml_approval === 0) {
                // Fallback cek sesi jika sampel belum diagregat
                $this->db->where('status', 'Menunggu Approval');
                $jml_approval = $this->db->count_all_results('testing_sessions');
            }
            $badges['pengujian_approval'] = (int) $jml_approval;
            $badges['pengujian_approval_label'] = $jml_approval . ' laporan menunggu persetujuan Manajer Teknis';
            $badges['pengujian_approval_type']  = 'danger';
        }

        // =========================================================================
        // 6. MENU: RIWAYAT HASIL UJI (pengujian_riwayat)
        // Prioritas 1: Sesi yang DITOLAK (membutuhkan perbaikan/revisi segera oleh analis)
        // Prioritas 2: Sesi yang SEDANG DIUJI milik analis (pekerjaan aktif belum selesai)
        // =========================================================================
        if ($bisa_laporan) {
            // Cek apakah ada sesi pengujian yang ditolak
            $this->db->where('status', 'Ditolak');
            if (!$bisa_verifikasi && !$bisa_approval) {
                // Penguji hanya melihat sesi miliknya yang ditolak
                $this->db->where('penguji_id', $user_id);
            }
            $jml_ditolak = $this->db->count_all_results('testing_sessions');

            if ($jml_ditolak > 0) {
                $badges['pengujian_riwayat'] = (int) $jml_ditolak;
                $badges['pengujian_riwayat_label'] = $jml_ditolak . ' laporan pengujian ditolak (perlu revisi)';
                $badges['pengujian_riwayat_type']  = 'danger';
            } else {
                // Untuk analis/penguji operasional: tampilkan sampel aktif yang sedang dikerjakan
                $is_penguji = (!$bisa_verifikasi && !$bisa_approval && $bisa_antrean);
                if ($is_penguji) {
                    $this->db->where('status', 'Sedang Diuji');
                    $this->db->where('penguji_id', $user_id);
                    $jml_sedang_diuji = $this->db->count_all_results('samples');
                    if ($jml_sedang_diuji === 0) {
                        $this->db->where('status', 'Sedang Diuji');
                        $this->db->where('penguji_id', $user_id);
                        $jml_sedang_diuji = $this->db->count_all_results('testing_sessions');
                    }

                    if ($jml_sedang_diuji > 0) {
                        $badges['pengujian_riwayat'] = (int) $jml_sedang_diuji;
                        $badges['pengujian_riwayat_label'] = $jml_sedang_diuji . ' sampel sedang aktif Anda uji';
                        $badges['pengujian_riwayat_type']  = 'danger';
                    }
                }
            }
        }

        // Hitung total pending action untuk topbar
        $badges['total_tindakan'] = 
            ($bisa_antrean ? $badges['pengujian_antrean'] : 0) +
            ($bisa_verifikasi ? $badges['pengujian_verifikasi'] : 0) +
            ($bisa_approval ? $badges['pengujian_approval'] : 0) +
            ($badges['pengujian_riwayat'] > 0 ? $badges['pengujian_riwayat'] : 0) +
            ($badges['akun'] > 0 ? 1 : 0);

        self::$cache_badges = $badges;
        return $badges;
    }
}
