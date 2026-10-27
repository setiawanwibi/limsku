<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Laporan & Monitoring LIMSKU
 * 
 * Menangani query agregat, filter multi-dimensi (tanggal, laboratorium, status, analis, komoditi),
 * penghitungan SLA/TAT (Turn Around Time), dan persiapan data rekap untuk ekspor.
 */
class Model_Laporan extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Mengambil ringkasan metrik statistik berdasarkan parameter filter
     * 
     * @param array $filter
     * @return array
     */
    public function ambil_statistik_monitoring($filter = array())
    {
        $this->db->select('
            COUNT(DISTINCT s.id) as total_sampel,
            SUM(CASE WHEN s.status = "Menunggu Pengujian" THEN 1 ELSE 0 END) as antrean_pengujian,
            SUM(CASE WHEN s.status = "Sedang Diuji" THEN 1 ELSE 0 END) as sedang_diuji,
            SUM(CASE WHEN s.status = "Menunggu Verifikasi" THEN 1 ELSE 0 END) as menunggu_verifikasi,
            SUM(CASE WHEN s.status = "Menunggu Approval" THEN 1 ELSE 0 END) as menunggu_approval,
            SUM(CASE WHEN s.status = "Approved / Final" THEN 1 ELSE 0 END) as selesai_final,
            SUM(CASE WHEN s.status = "Ditolak" THEN 1 ELSE 0 END) as ditolak,
            SUM(CASE WHEN s.status != "Approved / Final" AND s.status != "Ditolak" AND DATEDIFF(CURDATE(), DATE(s.created_at)) > 3 THEN 1 ELSE 0 END) as melewati_sla
        ', FALSE);
        $this->db->from('samples s');
        $this->terapkan_filter($this->db, $filter);
        $res = $this->db->get()->row_array();

        // Hitung rata-rata Lead Time (TAT) sampel yang sudah Approved / Final dalam hari
        $this->db->select('AVG(DATEDIFF(ts.waktu_approval, s.created_at)) as rata_tat_hari', FALSE);
        $this->db->from('samples s');
        $this->db->join('testing_sessions ts', 'ts.sample_id = s.id', 'inner');
        $this->db->where('s.status', 'Approved / Final');
        $this->db->where('ts.waktu_approval IS NOT NULL', NULL, FALSE);
        $this->terapkan_filter($this->db, $filter);
        $tat_res = $this->db->get()->row_array();

        $rata_tat = !empty($tat_res['rata_tat_hari']) ? round((float)$tat_res['rata_tat_hari'], 1) : 0;

        return array(
            'total_sampel'         => (int)($res['total_sampel'] ?? 0),
            'antrean_pengujian'    => (int)($res['antrean_pengujian'] ?? 0),
            'sedang_diuji'         => (int)($res['sedang_diuji'] ?? 0),
            'menunggu_verifikasi'  => (int)($res['menunggu_verifikasi'] ?? 0),
            'menunggu_approval'    => (int)($res['menunggu_approval'] ?? 0),
            'selesai_final'        => (int)($res['selesai_final'] ?? 0),
            'ditolak'              => (int)($res['ditolak'] ?? 0),
            'melewati_sla'         => (int)($res['melewati_sla'] ?? 0),
            'rata_tat_hari'        => $rata_tat
        );
    }

    /**
     * Mengambil daftar data rekapitulasi monitoring pengujian sampel
     * 
     * @param array $filter
     * @param int|null $limit
     * @param int|null $offset
     * @return array
     */
    public function ambil_daftar_monitoring($filter = array(), $limit = NULL, $offset = NULL)
    {
        $this->db->select('
            s.id as sample_id,
            s.no as no_sampel,
            s.no_sipt,
            s.kode_sampel_manual,
            s.nama_sampel,
            s.sub_kategori,
            s.jenis_kelas_terapi,
            s.nama_sarana,
            s.kemasan,
            s.no_bets,
            s.nomor_izin_edar,
            s.kondisi_produk,
            s.created_at,
            s.status as status_sampel,
            GROUP_CONCAT(DISTINCT ts.jenis_pengujian ORDER BY ts.jenis_pengujian ASC SEPARATOR " & ") as jenis_pengujian,
            GROUP_CONCAT(DISTINCT u.nama_lengkap SEPARATOR "; ") as nama_penguji,
            GROUP_CONCAT(DISTINCT uv.nama_lengkap SEPARATOR "; ") as nama_verifier,
            GROUP_CONCAT(DISTINCT ua.nama_lengkap SEPARATOR "; ") as nama_approver,
            COUNT(DISTINCT ts.id) as total_sesi,
            COUNT(DISTINCT tr.id) as total_parameter,
            GROUP_CONCAT(DISTINCT tr.kesimpulan SEPARATOR ", ") as kesimpulan_hasil,
            MAX(ts.waktu_approval) as waktu_approval,
            DATEDIFF(COALESCE(MAX(ts.waktu_approval), NOW()), s.created_at) as lead_time_hari
        ', FALSE);
        $this->db->from('samples s');
        $this->db->join('testing_sessions ts', 'ts.sample_id = s.id', 'left');
        $this->db->join('users u', 'u.id = ts.penguji_id', 'left');
        $this->db->join('users uv', 'uv.id = ts.verifier_id', 'left');
        $this->db->join('users ua', 'ua.id = ts.approver_id', 'left');
        $this->db->join('test_results tr', 'tr.session_id = ts.id', 'left');

        $this->terapkan_filter($this->db, $filter);

        $this->db->group_by('s.id');
        $this->db->order_by('s.id', 'DESC');

        if ($limit !== NULL) {
            $this->db->limit($limit, $offset);
        }

        return $this->db->get()->result_array();
    }

    /**
     * Helper privat untuk menerapkan kriteria filter SQL
     * 
     * @param CI_DB_query_builder $query
     * @param array $filter
     */
    protected function terapkan_filter(&$query, $filter)
    {
        // 1. Rentang Tanggal Terima / Input
        if (!empty($filter['tgl_mulai'])) {
            $query->where('DATE(s.created_at) >=', $filter['tgl_mulai']);
        }
        if (!empty($filter['tgl_selesai'])) {
            $query->where('DATE(s.created_at) <=', $filter['tgl_selesai']);
        }

        // 2. Filter Status
        if (!empty($filter['status'])) {
            $query->where('s.status', $filter['status']);
        }

        // 3. Filter Laboratorium / Jenis Pengujian
        if (!empty($filter['jenis_pengujian'])) {
            $query->where('EXISTS (SELECT 1 FROM testing_sessions ts_j WHERE ts_j.sample_id = s.id AND ts_j.jenis_pengujian = ' . $this->db->escape($filter['jenis_pengujian']) . ')', NULL, FALSE);
        }

        // 4. Filter Analis / Penguji
        if (!empty($filter['penguji_id'])) {
            $query->where('EXISTS (SELECT 1 FROM testing_sessions ts_u WHERE ts_u.sample_id = s.id AND ts_u.penguji_id = ' . (int)$filter['penguji_id'] . ')', NULL, FALSE);
        }

        // 5. Filter Kata Kunci (Nama Sampel / No SIPT / Kode Manual)
        if (!empty($filter['kata_kunci'])) {
            $keyword = $filter['kata_kunci'];
            $query->group_start();
            $query->like('s.nama_sampel', $keyword);
            $query->or_like('s.no_sipt', $keyword);
            $query->or_like('s.kode_sampel_manual', $keyword);
            $query->or_like('s.no_bets', $keyword);
            $query->group_end();
        }

        // 6. Penyekatan Hak Akses Komoditi (Khusus Role Penyelia & Pengguna Tertentu)
        if (!empty($filter['komoditi_izin']) && $filter['komoditi_izin'] !== 'all') {
            $izins = is_array($filter['komoditi_izin']) ? $filter['komoditi_izin'] : array($filter['komoditi_izin']);
            if (!in_array('all', $izins) && !in_array('all_sampel', $izins)) {
                $query->group_start();
                $query->where_in('s.kategori_sampel', $izins);
                // Jika izin mencakup 'obat', seluruh data obat historis (kategori_sampel = Targeted, Rutin, NULL, '') disertakan
                if (in_array('obat', $izins)) {
                    $query->or_where_in('s.kategori_sampel', array('Targeted', 'Rutin', 'Kasus'));
                    $query->or_where('s.kategori_sampel IS NULL', NULL, FALSE);
                    $query->or_where('s.kategori_sampel', '');
                }
                $query->group_end();
            }
        }

        // 7. Filter Manual Komoditi (Untuk Admin / MT yang memiliki akses 'all')
        if (!empty($filter['komoditi']) && $filter['komoditi'] !== 'all') {
            $query->group_start();
            $query->where('s.kategori_sampel', $filter['komoditi']);
            if ($filter['komoditi'] === 'obat') {
                $query->or_where_in('s.kategori_sampel', array('Targeted', 'Rutin', 'Kasus'));
                $query->or_where('s.kategori_sampel IS NULL', NULL, FALSE);
                $query->or_where('s.kategori_sampel', '');
            }
            $query->group_end();
        }
    }

    /**
     * Mengambil daftar analis untuk dropdown filter
     * 
     * @return array
     */
    public function ambil_daftar_analis()
    {
        $this->db->select('u.id, u.nama_lengkap, r.nama_role');
        $this->db->from('users u');
        $this->db->join('roles r', 'r.id = u.role_id', 'left');
        $this->db->where('r.nama_role', 'Penguji');
        $this->db->where('u.status', 'Aktif');
        $this->db->order_by('u.nama_lengkap', 'ASC');
        return $this->db->get()->result_array();
    }

    /**
     * Mengambil distribusi pengujian berdasarkan Bidang Laboratorium (Kimia vs Mikro)
     * 
     * @param array $filter
     * @return array
     */
    public function ambil_distribusi_bidang($filter = array())
    {
        $this->db->select('
            COALESCE(ts.jenis_pengujian, "Kimia") as jenis_pengujian,
            COUNT(DISTINCT s.id) as total_sampel,
            SUM(CASE WHEN s.status = "Approved / Final" THEN 1 ELSE 0 END) as selesai,
            SUM(CASE WHEN s.status != "Approved / Final" AND s.status != "Ditolak" THEN 1 ELSE 0 END) as proses
        ', FALSE);
        $this->db->from('samples s');
        $this->db->join('testing_sessions ts', 'ts.sample_id = s.id', 'left');
        $this->terapkan_filter($this->db, $filter);
        $this->db->group_by('COALESCE(ts.jenis_pengujian, "Kimia")');
        return $this->db->get()->result_array();
    }

    /**
     * Mengambil rekap performa / beban kerja analis penguji
     * 
     * @param array $filter
     * @return array
     */
    public function ambil_kinerja_analis($filter = array())
    {
        $this->db->select('
            u.id as user_id,
            u.nama_lengkap,
            COUNT(DISTINCT ts.id) as total_tugas,
            SUM(CASE WHEN ts.status = "Approved / Final" THEN 1 ELSE 0 END) as tugas_selesai,
            SUM(CASE WHEN ts.status IN ("Sedang Diuji", "Menunggu Verifikasi", "Menunggu Approval") THEN 1 ELSE 0 END) as tugas_aktif,
            ROUND(AVG(CASE WHEN ts.waktu_approval IS NOT NULL THEN DATEDIFF(ts.waktu_approval, ts.created_at) ELSE NULL END), 1) as rata_tat_analis
        ', FALSE);
        $this->db->from('testing_sessions ts');
        $this->db->join('users u', 'u.id = ts.penguji_id', 'inner');
        $this->db->join('samples s', 's.id = ts.sample_id', 'inner');
        $this->terapkan_filter($this->db, $filter);
        $this->db->group_by('u.id, u.nama_lengkap');
        $this->db->order_by('total_tugas', 'DESC');
        return $this->db->get()->result_array();
    }
}
