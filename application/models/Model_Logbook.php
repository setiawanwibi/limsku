<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Logbook Alat Laboratorium LIMSKU
 *
 * Menangani pencatatan otomatis penggunaan instrumen laboratorium per sesi/pengujian,
 * riwayat pemakaian alat digital, serta pelaporan logbook instrumen (GLP/ISO 17025).
 */
class Model_Logbook extends CI_Model
{
    protected $tabel = 'instrument_logbooks';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Mengambil daftar logbook penggunaan alat laboratorium dengan filter komprehensif
     *
     * @param array $filter
     * @return array
     */
    public function ambil_logbook($filter = array())
    {
        $this->db->select('il.*, 
            i.nama_barang as nama_alat, i.kode_barang, i.merk_tipe, i.nomor_seri, i.nama_ruangan as lokasi_alat, i.kondisi_adm,
            s.nama_sampel, s.kode_sampel_manual, s.no_sipt, s.no as no_sampel,
            u.nama_lengkap as nama_pengguna, u.nip as nip_pengguna, u.username,
            ts.jenis_pengujian, ts.status as status_sesi,
            COALESCE(m.parameter_uji, t.nama_template, "Pengujian Laboratorium") as parameter_uji, tr.kesimpulan');
        $this->db->from($this->tabel . ' il');
        $this->db->join('instruments i', 'i.id = il.instrument_id', 'inner');
        $this->db->join('samples s', 's.id = il.sample_id', 'inner');
        $this->db->join('users u', 'u.id = il.user_id', 'left');
        $this->db->join('testing_sessions ts', 'ts.id = il.session_id', 'left');
        $this->db->join('test_results tr', 'tr.id = il.test_result_id', 'left');
        $this->db->join('methods m', 'm.id = tr.method_id', 'left');
        $this->db->join('form_templates t', 't.id = tr.template_id', 'left');

        // Filter Spesifik Alat
        if (!empty($filter['instrument_id'])) {
            $this->db->where('il.instrument_id', (int)$filter['instrument_id']);
        }

        // Filter Rentang Tanggal Penggunaan
        if (!empty($filter['tanggal_mulai'])) {
            $this->db->where('il.tanggal_penggunaan >=', $filter['tanggal_mulai']);
        }
        if (!empty($filter['tanggal_selesai'])) {
            $this->db->where('il.tanggal_penggunaan <=', $filter['tanggal_selesai']);
        }

        // Filter Pengguna / Analis
        if (!empty($filter['user_id'])) {
            $this->db->where('il.user_id', (int)$filter['user_id']);
        }

        // Filter Bidang Pengujian (Kimia / Mikrobiologi)
        if (!empty($filter['jenis_pengujian'])) {
            $this->db->where('ts.jenis_pengujian', $filter['jenis_pengujian']);
        }

        // Filter Pencarian Teks Bebas
        if (!empty($filter['q'])) {
            $q = trim($filter['q']);
            $this->db->group_start();
            $this->db->like('i.nama_barang', $q);
            $this->db->or_like('i.kode_barang', $q);
            $this->db->or_like('i.nomor_seri', $q);
            $this->db->or_like('i.nama_ruangan', $q);
            $this->db->or_like('s.nama_sampel', $q);
            $this->db->or_like('s.kode_sampel_manual', $q);
            $this->db->or_like('u.nama_lengkap', $q);
            $this->db->or_like('u.username', $q);
            $this->db->or_like('il.keterangan', $q);
            $this->db->group_end();
        }

        $this->db->order_by('il.waktu_penggunaan', 'DESC');
        $this->db->order_by('il.id', 'DESC');

        if (!empty($filter['limit'])) {
            $offset = !empty($filter['offset']) ? (int)$filter['offset'] : 0;
            $this->db->limit((int)$filter['limit'], $offset);
        }

        return $this->db->get()->result_array();
    }

    /**
     * Mengambil riwayat logbook khusus untuk satu alat tertentu
     *
     * @param int $instrument_id
     * @param array $filter
     * @return array
     */
    public function ambil_by_instrument($instrument_id, $filter = array())
    {
        $filter['instrument_id'] = (int)$instrument_id;
        return $this->ambil_logbook($filter);
    }

    /**
     * Mencatat otomatis pemakaian alat saat penguji menyelesaikan sesi pengujian / revisi ke penyelia.
     * Ekstrak alat_ids yang dipilih pada tiap form parameter LCP.
     *
     * @param int $session_id
     * @param array|null $forms_input Data array dari input POST forms jika ada
     * @return int Jumlah entri logbook yang berhasil dicatat
     */
    public function catat_otomatis_dari_sesi($session_id, $forms_input = NULL)
    {
        $session_id = (int)$session_id;
        if ($session_id <= 0) return 0;

        $sesi = $this->db->get_where('testing_sessions', array('id' => $session_id))->row_array();
        if (!$sesi) return 0;

        $sample_id = (int)$sesi['sample_id'];
        $user_id   = (int)$sesi['penguji_id'];
        $waktu_sekarang = date('Y-m-d H:i:s');
        $tgl_sekarang   = date('Y-m-d');

        // Ambil data seluruh test_results pada sesi ini
        $this->db->select('tr.*, t.nama_template, m.parameter_uji');
        $this->db->from('test_results tr');
        $this->db->join('methods m', 'm.id = tr.method_id', 'left');
        $this->db->join('form_templates t', 't.id = tr.template_id', 'left');
        $this->db->where('tr.session_id', $session_id);
        $test_results = $this->db->get()->result_array();

        if (empty($test_results)) return 0;

        $total_tercatat = 0;

        foreach ($test_results as $tr) {
            $tr_id = (int)$tr['id'];
            $alat_ids = array();

            // 1. Periksa alat_ids dari parameter $forms_input jika diberikan
            if (isset($forms_input[$tr_id]['alat_ids']) && is_array($forms_input[$tr_id]['alat_ids'])) {
                $alat_ids = array_filter(array_map('intval', $forms_input[$tr_id]['alat_ids']));
            } else {
                // 2. Ekstrak alat_ids dari data_hasil yang tersimpan di database
                $data_hasil = !empty($tr['data_hasil']) ? json_decode($tr['data_hasil'], true) : array();
                if (isset($data_hasil['alat_ids']) && is_array($data_hasil['alat_ids'])) {
                    $alat_ids = array_filter(array_map('intval', $data_hasil['alat_ids']));
                }
            }

            if (empty($alat_ids)) continue;

            // Siapkan teks keterangan parameter
            $param_nama = !empty($tr['parameter_uji']) ? $tr['parameter_uji'] : (!empty($tr['nama_template']) ? $tr['nama_template'] : 'Pengujian Laboratorium');
            $ket_teks = '[' . $sesi['jenis_pengujian'] . '] ' . $param_nama;
            if (!empty($tr['kesimpulan']) && $tr['kesimpulan'] !== 'Belum Disimpulkan') {
                $ket_teks .= ' (' . $tr['kesimpulan'] . ')';
            }

            foreach ($alat_ids as $alat_id) {
                if ($alat_id <= 0) continue;

                // Verifikasi keberadaan alat
                $alat = $this->db->get_where('instruments', array('id' => $alat_id))->row_array();
                if (!$alat) continue;

                // Cek apakah entri logbook untuk alat, sesi, dan test_result ini sudah tercatat
                $this->db->where('instrument_id', $alat_id);
                $this->db->where('test_result_id', $tr_id);
                $cek_ada = $this->db->get($this->tabel)->row_array();

                if ($cek_ada) {
                    // Update logbook yang ada
                    $this->db->where('id', $cek_ada['id']);
                    $this->db->update($this->tabel, array(
                        'user_id'            => $user_id,
                        'tanggal_penggunaan' => $tgl_sekarang,
                        'waktu_penggunaan'   => $waktu_sekarang,
                        'keterangan'         => $ket_teks,
                        'updated_at'         => $waktu_sekarang
                    ));
                    $total_tercatat++;
                } else {
                    // Insert entri logbook baru
                    $this->db->insert($this->tabel, array(
                        'instrument_id'      => $alat_id,
                        'sample_id'          => $sample_id,
                        'session_id'         => $session_id,
                        'test_result_id'     => $tr_id,
                        'user_id'            => $user_id,
                        'tanggal_penggunaan' => $tgl_sekarang,
                        'waktu_penggunaan'   => $waktu_sekarang,
                        'keterangan'         => $ket_teks,
                        'created_at'         => $waktu_sekarang
                    ));
                    $total_tercatat++;
                }
            }
        }

        return $total_tercatat;
    }

    /**
     * Mengambil ringkasan statistik penggunaan alat untuk dashboard/kartu metrik logbook
     *
     * @return array
     */
    public function ambil_statistik()
    {
        // 1. Total pencatatan penggunaan logbook
        $total_log = $this->db->count_all($this->tabel);

        // 2. Jumlah alat unik yang pernah digunakan
        $this->db->select('COUNT(DISTINCT instrument_id) as total_alat_terpakai');
        $r_alat = $this->db->get($this->tabel)->row_array();
        $total_alat_terpakai = !empty($r_alat['total_alat_terpakai']) ? (int)$r_alat['total_alat_terpakai'] : 0;

        // 3. Jumlah sampel unik yang diuji menggunakan instrumen
        $this->db->select('COUNT(DISTINCT sample_id) as total_sampel');
        $r_smp = $this->db->get($this->tabel)->row_array();
        $total_sampel = !empty($r_smp['total_sampel']) ? (int)$r_smp['total_sampel'] : 0;

        // 4. Penggunaan bulan berjalan
        $bln_ini = date('Y-m');
        $this->db->like('tanggal_penggunaan', $bln_ini, 'after');
        $penggunaan_bulan_ini = $this->db->count_all_results($this->tabel);

        // 5. Total master alat aktif
        $this->db->where('status', 'Aktif');
        $total_master_alat = $this->db->count_all_results('instruments');

        return array(
            'total_log'            => (int)$total_log,
            'total_alat_terpakai'  => $total_alat_terpakai,
            'total_sampel'         => $total_sampel,
            'penggunaan_bulan_ini' => (int)$penggunaan_bulan_ini,
            'total_master_alat'    => (int)$total_master_alat
        );
    }

    /**
     * Sinkronisasi data historis dari sesi pengujian yang sudah pernah diinput sebelumnya ke dalam logbook.
     * Dijalankan secara otomatis / on-demand agar pengujian lama langsung memiliki riwayat logbook alat.
     *
     * @return int Jumlah entri baru yang berhasil disinkronkan
     */
    public function sinkronisasi_historis()
    {
        $this->db->select('tr.id as tr_id, tr.session_id, tr.sample_id, m.parameter_uji, tr.kesimpulan, tr.data_hasil, tr.created_at, tr.updated_at,
                           ts.penguji_id, ts.jenis_pengujian, ts.waktu_selesai, ts.waktu_mulai, t.nama_template');
        $this->db->from('test_results tr');
        $this->db->join('testing_sessions ts', 'ts.id = tr.session_id', 'inner');
        $this->db->join('methods m', 'm.id = tr.method_id', 'left');
        $this->db->join('form_templates t', 't.id = tr.template_id', 'left');
        $this->db->where('tr.data_hasil IS NOT NULL', NULL, FALSE);
        $this->db->where('tr.data_hasil LIKE', '%alat_ids%');
        $rows = $this->db->get()->result_array();

        if (empty($rows)) return 0;

        $baru_disinkron = 0;

        foreach ($rows as $row) {
            $data = json_decode($row['data_hasil'], true);
            if (!isset($data['alat_ids']) || !is_array($data['alat_ids']) || empty($data['alat_ids'])) {
                continue;
            }

            $alat_ids = array_filter(array_map('intval', $data['alat_ids']));
            if (empty($alat_ids)) continue;

            $waktu = !empty($row['waktu_selesai']) ? $row['waktu_selesai'] : (!empty($row['updated_at']) ? $row['updated_at'] : $row['created_at']);
            $tgl = date('Y-m-d', strtotime($waktu));
            $param_nama = !empty($row['parameter_uji']) ? $row['parameter_uji'] : (!empty($row['nama_template']) ? $row['nama_template'] : 'Pengujian Parameter');
            $ket = '[' . $row['jenis_pengujian'] . '] ' . $param_nama;

            foreach ($alat_ids as $aid) {
                // Cek apakah sudah ada di logbook
                $this->db->where('instrument_id', $aid);
                $this->db->where('test_result_id', $row['tr_id']);
                $ada = $this->db->get($this->tabel)->row_array();

                if (!$ada) {
                    $this->db->insert($this->tabel, array(
                        'instrument_id'      => $aid,
                        'sample_id'          => $row['sample_id'],
                        'session_id'         => $row['session_id'],
                        'test_result_id'     => $row['tr_id'],
                        'user_id'            => $row['penguji_id'] ?: 3,
                        'tanggal_penggunaan' => $tgl,
                        'waktu_penggunaan'   => $waktu,
                        'keterangan'         => $ket,
                        'created_at'         => $waktu
                    ));
                    $baru_disinkron++;
                }
            }
        }

        return $baru_disinkron;
    }
}

