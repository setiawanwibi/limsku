<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Dasbor LIMSKU
 * 
 * Menangani query aggregate statistik dan performa operasional laboratorium
 * untuk Dashboard Utama (Semua Role).
 */
class Model_Dasbor extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Mengambil ringkasan statistik lengkap untuk Dashboard
     * 
     * @return array
     */
    public function ambil_ringkasan()
    {
        $hari_ini_tgl = date('Y-m-d');
        $bulan_ini_str = date('Y-m');

        // ----------------------------------------------------
        // 1. KPI CARD 1 — SAMPEL DITERIMA (Hari Ini & Bulan Ini)
        // ----------------------------------------------------
        // Sampel diterima hari ini & bulan ini
        $q_sample = $this->db->query("
            SELECT 
                COUNT(*) as total_semua,
                SUM(CASE WHEN DATE(created_at) = CURDATE() THEN 1 ELSE 0 END) as hari_ini,
                SUM(CASE WHEN DATE(created_at) = DATE_SUB(CURDATE(), INTERVAL 1 DAY) THEN 1 ELSE 0 END) as kemarin,
                SUM(CASE WHEN DATE_FORMAT(created_at, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m') THEN 1 ELSE 0 END) as bulan_ini
            FROM samples
        ")->row_array();

        $sampel_hari_ini = (int) ($q_sample['hari_ini'] ?? 0);
        $sampel_kemarin  = (int) ($q_sample['kemarin'] ?? 0);
        $sampel_bulan_ini = (int) ($q_sample['bulan_ini'] ?? 0);

        // Perhitungan trend dibanding kemarin jika valid
        $sampel_tren_persen = null;
        if ($sampel_kemarin > 0) {
            $sampel_tren_persen = round((($sampel_hari_ini - $sampel_kemarin) / $sampel_kemarin) * 100);
        }

        // ----------------------------------------------------
        // 2. KPI CARD 2 — PENGUJIAN (Aktif & Selesai)
        // ----------------------------------------------------
        // Mapping WORKFLOW.md:
        // Aktif   = Sedang Diuji (atau test_results yang sedang berlangsung)
        // Selesai = Approved / Final
        $q_uji = $this->db->query("
            SELECT 
                SUM(CASE WHEN status = 'Sedang Diuji' THEN 1 ELSE 0 END) as aktif,
                SUM(CASE WHEN status = 'Approved / Final' THEN 1 ELSE 0 END) as selesai
            FROM samples
        ")->row_array();

        $pengujian_aktif   = (int) ($q_uji['aktif'] ?? 0);
        $pengujian_selesai = (int) ($q_uji['selesai'] ?? 0);

        // ----------------------------------------------------
        // 3. KPI CARD 3 — MELEWATI SLA
        // ----------------------------------------------------
        // Menurut PRD/WORKFLOW, target waktu proses standard = 3 hari kerja sejak sampel diterima / masuk antrean.
        // Pekerjaan yang belum selesai (status != 'Approved / Final') dan DATEDIFF(CURDATE(), DATE(created_at)) > 3 dianggap melewati batas waktu.
        $q_sla = $this->db->query("
            SELECT 
                COUNT(*) as total_lewat_sla,
                SUM(CASE WHEN DATE(created_at) = DATE_SUB(CURDATE(), INTERVAL 1 DAY) THEN 1 ELSE 0 END) as lewat_sla_kemarin
            FROM samples
            WHERE status != 'Approved / Final'
              AND DATEDIFF(CURDATE(), DATE(created_at)) > 3
        ")->row_array();

        $melewati_sla = (int) ($q_sla['total_lewat_sla'] ?? 0);

        // ----------------------------------------------------
        // 4. KPI CARD 4 & SECTION J — PERLU TINDAKAN (Triage Action Queue)
        // ----------------------------------------------------
        // Pengujian = Menunggu Pengujian (dari tabel samples)
        // Verifikasi = Menunggu Verifikasi (dari tabel test_results atau samples)
        // Approval = Menunggu Approval (dari tabel test_results atau samples)
        $q_status_samples = $this->db->query("
            SELECT 
                SUM(CASE WHEN status = 'Menunggu Pengujian' THEN 1 ELSE 0 END) as entry_queue,
                SUM(CASE WHEN status = 'Sedang Diuji' THEN 1 ELSE 0 END) as proses_uji,
                SUM(CASE WHEN status = 'Menunggu Verifikasi' THEN 1 ELSE 0 END) as perlu_verifikasi,
                SUM(CASE WHEN status = 'Menunggu Approval' THEN 1 ELSE 0 END) as perlu_approval,
                SUM(CASE WHEN status = 'Approved / Final' THEN 1 ELSE 0 END) as tahap_selesai,
                SUM(CASE WHEN status = 'Ditolak' THEN 1 ELSE 0 END) as tahap_ditolak,
                COUNT(*) as total_pekerjaan
            FROM samples
        ")->row_array();

        $tindakan_pengujian  = (int) ($q_status_samples['entry_queue'] ?? 0);
        $tindakan_verifikasi = (int) ($q_status_samples['perlu_verifikasi'] ?? 0);
        $tindakan_approval   = (int) ($q_status_samples['perlu_approval'] ?? 0);
        $total_perlu_tindakan = $tindakan_pengujian + $tindakan_verifikasi + $tindakan_approval;

        // ----------------------------------------------------
        // 5. SECTION H — STATUS PEKERJAAN (5 Tahap Workflow)
        // ----------------------------------------------------
        // 1. Entry           -> Menunggu Pengujian
        // 2. Tahap Pengujian -> Sedang Diuji
        // 3. Verifikasi      -> Menunggu Verifikasi
        // 4. Approval        -> Menunggu Approval
        // 5. Selesai         -> Approved / Final
        $status_pekerjaan = array(
            'entry'           => $tindakan_pengujian,
            'tahap_pengujian' => (int) ($q_status_samples['proses_uji'] ?? 0),
            'verifikasi'      => $tindakan_verifikasi,
            'approval'        => $tindakan_approval,
            'selesai'         => (int) ($q_status_samples['tahap_selesai'] ?? 0)
        );

        $total_workflow = array_sum($status_pekerjaan);
        // Hitung persentase progress per status relatif thd total workflow (atau max jika nol)
        $status_progress = array();
        $max_count = max(array_merge(array_values($status_pekerjaan), array(1)));
        foreach ($status_pekerjaan as $key => $val) {
            $status_progress[$key] = ($total_workflow > 0) ? round(($val / $max_count) * 100) : 0;
        }

        // ----------------------------------------------------
        // 6. SECTION I — RATA-RATA WAKTU PENYELESAIAN
        // ----------------------------------------------------
        // Dihitung dari waktu mulai uji ke waktu approval / waktu selesai pada test_results yang sudah Approved / Final
        $q_avg = $this->db->query("
            SELECT 
                AVG(TIMESTAMPDIFF(HOUR, s.created_at, tr.waktu_approval)) as avg_hours,
                COUNT(tr.id) as total_approved
            FROM test_results tr
            JOIN samples s ON s.id = tr.sample_id
            WHERE tr.status = 'Approved / Final'
              AND tr.waktu_approval IS NOT NULL
        ")->row_array();

        $avg_waktu_hari = null;
        if (!empty($q_avg['total_approved']) && $q_avg['total_approved'] > 0 && !is_null($q_avg['avg_hours'])) {
            $avg_waktu_hari = round($q_avg['avg_hours'] / 24, 1);
        }

        // ----------------------------------------------------
        // 7. SECTION K — TREND SAMPEL & PENGUJIAN (Bulan Berjalan)
        // ----------------------------------------------------
        // Mengambil agregasi harian dalam 30 hari terakhir / bulan berjalan
        $q_trend_samples = $this->db->query("
            SELECT DATE(created_at) as tgl, COUNT(*) as jml
            FROM samples
            WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 29 DAY)
            GROUP BY DATE(created_at)
        ")->result_array();

        $q_trend_tests = $this->db->query("
            SELECT DATE(waktu_selesai) as tgl, COUNT(*) as jml
            FROM test_results
            WHERE waktu_selesai >= DATE_SUB(CURDATE(), INTERVAL 29 DAY)
            GROUP BY DATE(waktu_selesai)
        ")->result_array();

        $map_samples = array();
        foreach ($q_trend_samples as $ts) {
            $map_samples[$ts['tgl']] = (int) $ts['jml'];
        }

        $map_tests = array();
        foreach ($q_trend_tests as $tt) {
            $map_tests[$tt['tgl']] = (int) $tt['jml'];
        }

        // Generate 30 hari terakhir
        $trend_labels  = array();
        $trend_samples = array();
        $trend_tests   = array();

        for ($i = 29; $i >= 0; $i--) {
            $d = date('Y-m-d', strtotime("-{$i} days"));
            $label_fmt = date('j M', strtotime($d));
            $trend_labels[]  = $label_fmt;
            $trend_samples[] = isset($map_samples[$d]) ? $map_samples[$d] : 0;
            $trend_tests[]   = isset($map_tests[$d]) ? $map_tests[$d] : 0;
        }

        // ----------------------------------------------------
        // 8. SECTION L — DISTRIBUSI HASIL PENGUJIAN (Donut)
        // ----------------------------------------------------
        // Kategori: Sesuai (MS), Tidak Sesuai (TMS), Belum Final (Sedang Diuji, Menunggu Verifikasi, Menunggu Approval)
        $q_dist = $this->db->query("
            SELECT 
                SUM(CASE WHEN kesimpulan = 'Memenuhi Syarat (MS)' THEN 1 ELSE 0 END) as sesuai,
                SUM(CASE WHEN kesimpulan = 'Tidak Memenuhi Syarat (TMS)' THEN 1 ELSE 0 END) as tidak_sesuai,
                SUM(CASE WHEN kesimpulan = 'Belum Disimpulkan' OR status != 'Approved / Final' THEN 1 ELSE 0 END) as belum_final,
                COUNT(*) as total_dist
            FROM test_results
        ")->row_array();

        $dist_sesuai       = (int) ($q_dist['sesuai'] ?? 0);
        $dist_tidak_sesuai = (int) ($q_dist['tidak_sesuai'] ?? 0);
        $dist_belum_final  = (int) ($q_dist['belum_final'] ?? 0);
        $total_dist        = $dist_sesuai + $dist_tidak_sesuai + $dist_belum_final;

        $pct_sesuai       = ($total_dist > 0) ? round(($dist_sesuai / $total_dist) * 100) : 0;
        $pct_tidak_sesuai = ($total_dist > 0) ? round(($dist_tidak_sesuai / $total_dist) * 100) : 0;
        $pct_belum_final  = ($total_dist > 0) ? round(($dist_belum_final / $total_dist) * 100) : 0;

        return array(
            'tanggal_server' => date('d M Y'),
            'kpi' => array(
                'sampel_diterima' => array(
                    'hari_ini'    => $sampel_hari_ini,
                    'bulan_ini'   => $sampel_bulan_ini,
                    'tren_persen' => $sampel_tren_persen
                ),
                'pengujian' => array(
                    'aktif'   => $pengujian_aktif,
                    'selesai' => $pengujian_selesai
                ),
                'melewati_sla' => array(
                    'jumlah'      => $melewati_sla,
                    'keterangan'  => 'pekerjaan'
                ),
                'perlu_tindakan' => array(
                    'jumlah'      => $total_perlu_tindakan,
                    'keterangan'  => 'pekerjaan'
                )
            ),
            'status_pekerjaan' => array(
                'counts'   => $status_pekerjaan,
                'progress' => $status_progress,
                'total'    => $total_workflow
            ),
            'rata_waktu' => array(
                'hari'       => $avg_waktu_hari,
                'target_sla' => 3
            ),
            'pekerjaan_tindakan' => array(
                'pengujian'  => $tindakan_pengujian,
                'verifikasi' => $tindakan_verifikasi,
                'approval'   => $tindakan_approval
            ),
            'trend' => array(
                'labels'  => $trend_labels,
                'samples' => $trend_samples,
                'tests'   => $trend_tests
            ),
            'distribusi' => array(
                'sesuai' => array(
                    'count'   => $dist_sesuai,
                    'percent' => $pct_sesuai
                ),
                'tidak_sesuai' => array(
                    'count'   => $dist_tidak_sesuai,
                    'percent' => $pct_tidak_sesuai
                ),
                'belum_final' => array(
                    'count'   => $dist_belum_final,
                    'percent' => $pct_belum_final
                ),
                'total' => $total_dist
            ),
            // Deferred modules (Alat & Reagen)
            'alat_kalibrasi' => array(
                'status'  => 'deferred',
                'pesan'   => 'Modul master alat & kalibrasi belum masuk scope database saat ini.'
            ),
            'reagen_kedaluwarsa' => array(
                'status'  => 'deferred',
                'pesan'   => 'Modul master reagen & material belum masuk scope database saat ini.'
            )
        );
    }
}
