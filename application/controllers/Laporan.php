<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Laporan & Monitoring Pengujian
 * 
 * Modul J & Pelaporan Eksekutif LIMSKU:
 * - Halaman Monitoring Operasional Laboratorium Interaktif
 * - Filter multi-parameter (Tanggal, Status, Lab Kimia/Mikro, Analis)
 * - Ekspor Rekapitulasi ke Excel (.csv UTF-8 / spreadsheet kompatibel)
 * - Ekspor Dokumen Cetak Rekapitulasi Resmi ke PDF (Landscape A4)
 */
class Laporan extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->cek_login();

        // Guard Eksklusif: Hanya Admin TI (1), Penyelia (4), dan Manajer Teknis (5)
        $role_id = (int)$this->session->userdata('role_id');
        if (!in_array($role_id, array(1, 4, 5), true)) {
            $this->session->set_flashdata('pesan_gagal', 'Akses ditolak: Menu Monitoring & Laporan hanya dapat diakses oleh Penyelia, Manajer Teknis, atau Admin.');
            redirect('dasbor');
        }

        $this->load->model('Model_Laporan');
        $this->load->model('Model_Audit');
    }

    /**
     * Halaman Utama Monitoring & Laporan Pengujian
     */
    public function index()
    {
        // Hanya role yang memiliki hak akses laporan_view yang dapat membuka
        $this->cek_hak_akses('laporan_view');

        $this->data['halaman_aktif']   = 'laporan_monitoring';
        $this->data['judul_halaman']   = 'Monitoring & Laporan Pengujian';
        $this->data['hide_breadcrumb'] = true;
        $this->data['breadcrumbs']     = array();

        // Ambil parameter filter dari GET
        $filter = array(
            'tgl_mulai'       => $this->input->get('tgl_mulai', TRUE),
            'tgl_selesai'     => $this->input->get('tgl_selesai', TRUE),
            'status'          => $this->input->get('status', TRUE),
            'jenis_pengujian' => $this->input->get('jenis_pengujian', TRUE),
            'penguji_id'      => $this->input->get('penguji_id', TRUE),
            'kata_kunci'      => $this->input->get('kata_kunci', TRUE)
        );

        // Default tanggal: 30 hari terakhir jika tidak diset
        if (empty($filter['tgl_mulai'])) {
            $filter['tgl_mulai'] = date('Y-m-d', strtotime('-30 days'));
        }
        if (empty($filter['tgl_selesai'])) {
            $filter['tgl_selesai'] = date('Y-m-d');
        }

        $this->data['filter']            = $filter;
        $this->data['statistik']         = $this->Model_Laporan->ambil_statistik_monitoring($filter);
        $this->data['daftar_rekap']      = $this->Model_Laporan->ambil_daftar_monitoring($filter, 1000, 0);
        $this->data['daftar_analis']     = $this->Model_Laporan->ambil_daftar_analis();
        $this->data['distribusi_bidang'] = $this->Model_Laporan->ambil_distribusi_bidang($filter);
        $this->data['kinerja_analis']    = $this->Model_Laporan->ambil_kinerja_analis($filter);

        $this->muat_tampilan('laporan/index');
    }

    /**
     * Ekspor Rekapitulasi Data ke Excel (.CSV terstandar UTF-8 dengan BOM)
     */
    public function export_excel()
    {
        $this->cek_hak_akses('laporan_view');

        $filter = array(
            'tgl_mulai'       => $this->input->get('tgl_mulai', TRUE),
            'tgl_selesai'     => $this->input->get('tgl_selesai', TRUE),
            'status'          => $this->input->get('status', TRUE),
            'jenis_pengujian' => $this->input->get('jenis_pengujian', TRUE),
            'penguji_id'      => $this->input->get('penguji_id', TRUE),
            'kata_kunci'      => $this->input->get('kata_kunci', TRUE)
        );

        $daftar_rekap = $this->Model_Laporan->ambil_daftar_monitoring($filter);

        // Catat audit trail
        $this->catat_audit(
            'EXPORT_EXCEL',
            'Laporan',
            'Pengguna mengunduh rekapitulasi data pengujian format Excel (Filter: ' . json_encode($filter) . ')'
        );

        $filename = 'Rekap_Pengujian_LIMSKU_' . date('Ymd_His') . '.csv';

        // Set Headers untuk file download
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $output = fopen('php://output', 'w');
        // UTF-8 BOM agar rapi di Microsoft Excel versi Windows/Mac tanpa issue karakter khusus
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        // Header Dokumen
        fputcsv($output, array('REKAPITULASI MONITORING PENGUJIAN LABORATORIUM - LIMSKU BBPOM DI BANDAR LAMPUNG'));
        fputcsv($output, array('Periode Tanggal:', ($filter['tgl_mulai'] ?: 'Semua') . ' s/d ' . ($filter['tgl_selesai'] ?: 'Semua')));
        fputcsv($output, array('Waktu Ekspor:', date('d-m-Y H:i:s')));
        fputcsv($output, array('Diekspor Oleh:', $this->session->userdata('nama_lengkap')));
        fputcsv($output, array('')); // Baris kosong

        // Header Kolom Tabel
        fputcsv($output, array(
            'No',
            'No. Urut / SIPT',
            'Kode Sampel',
            'Nama Sampel / Komoditi',
            'Bentuk Sediaan',
            'No. Bets',
            'Asal / Anggaran',
            'Tanggal Terima',
            'Bidang Laboratorium',
            'Status Alur',
            'Penguji / Analis',
            'Penyelia',
            'Waktu Approval',
            'Jumlah Parameter',
            'Kesimpulan Hasil',
            'Turn Around Time (Hari)'
        ));

        $no = 1;
        foreach ($daftar_rekap as $row) {
            $kode_sampel = !empty($row['kode_sampel_manual']) ? $row['kode_sampel_manual'] : ('SMP-' . str_pad($row['sample_id'], 5, '0', STR_PAD_LEFT));
            $no_sipt = !empty($row['no_sipt']) ? $row['no_sipt'] : ('# ' . $row['no_sampel']);
            $waktu_app = !empty($row['waktu_approval']) ? date('d/m/Y H:i', strtotime($row['waktu_approval'])) : '-';

            fputcsv($output, array(
                $no++,
                $no_sipt,
                $kode_sampel,
                $row['nama_sampel'],
                $row['sub_kategori'] ?: '-',
                $row['no_bets'] ?: '-',
                $row['nama_sarana'] ?: '-',
                !empty($row['created_at']) ? date('d/m/Y', strtotime($row['created_at'])) : '-',
                $row['jenis_pengujian'] ?: 'Kimia',
                $row['status_sampel'],
                $row['nama_penguji'] ?: '-',
                $row['nama_verifier'] ?: '-',
                $waktu_app,
                $row['total_parameter'],
                $row['kesimpulan_hasil'] ?: 'Belum Ada Hasil',
                $row['lead_time_hari'] !== null ? $row['lead_time_hari'] . ' hari' : '-'
            ));
        }

        fclose($output);
        exit;
    }

    /**
     * Ekspor Rekapitulasi Data ke PDF Resmi (Landscape A4)
     */
    public function export_pdf()
    {
        $this->cek_hak_akses('laporan_view');

        $filter = array(
            'tgl_mulai'       => $this->input->get('tgl_mulai', TRUE),
            'tgl_selesai'     => $this->input->get('tgl_selesai', TRUE),
            'status'          => $this->input->get('status', TRUE),
            'jenis_pengujian' => $this->input->get('jenis_pengujian', TRUE),
            'penguji_id'      => $this->input->get('penguji_id', TRUE),
            'kata_kunci'      => $this->input->get('kata_kunci', TRUE)
        );

        $daftar_rekap = $this->Model_Laporan->ambil_daftar_monitoring($filter);
        $statistik    = $this->Model_Laporan->ambil_statistik_monitoring($filter);

        // Catat audit trail
        $this->catat_audit(
            'EXPORT_PDF',
            'Laporan',
            'Pengguna mencetak laporan rekapitulasi PDF (Filter: ' . json_encode($filter) . ')'
        );

        // Bersihkan output buffer agar PDF tidak corrupt jika ada output sebelumnya
        if (ob_get_length()) {
            ob_end_clean();
        }

        // Include engine FPDF
        require_once APPPATH . 'libraries/FPDF.php';

        $pdf = new FPDF('L', 'mm', 'A4'); // Landscape A4 (297 x 210 mm)
        $pdf->SetMargins(12, 12, 12);
        $pdf->SetAutoPageBreak(true, 15);
        $pdf->AddPage();

        // 1. KOP SURAT RESMI
        $pdf->SetFont('Helvetica', 'B', 12);
        $pdf->Cell(0, 5, 'BALAI BESAR PENGAWAS OBAT DAN MAKANAN DI BANDAR LAMPUNG', 0, 1, 'C');
        $pdf->SetFont('Helvetica', 'B', 10);
        $pdf->Cell(0, 5, 'REKAPITULASI MONITORING & KINERJA PENGUJIAN LABORATORIUM', 0, 1, 'C');
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(0, 4, 'Jl. Way Pengubuan No. 3 Pahoman, Bandar Lampung | Dokumen Sistem Informasi Manajemen Laboratorium (LIMSKU)', 0, 1, 'C');
        
        $pdf->SetLineWidth(0.5);
        $pdf->Line(12, $pdf->GetY() + 2, 285, $pdf->GetY() + 2);
        $pdf->SetLineWidth(0.2);
        $pdf->Line(12, $pdf->GetY() + 3, 285, $pdf->GetY() + 3);
        $pdf->Ln(6);

        // 2. METADATA FILTER & STATISTIK RINGKAS
        $pdf->SetFont('Helvetica', '', 8);
        $periode_str = ($filter['tgl_mulai'] ?: 'Awal') . ' s/d ' . ($filter['tgl_selesai'] ?: 'Sekarang');
        $pdf->Cell(45, 4, 'Periode Tanggal', 0, 0, 'L');
        $pdf->Cell(5, 4, ':', 0, 0, 'C');
        $pdf->Cell(90, 4, $periode_str, 0, 0, 'L');

        $pdf->Cell(45, 4, 'Total Sampel Terdata', 0, 0, 'L');
        $pdf->Cell(5, 4, ':', 0, 0, 'C');
        $pdf->Cell(0, 4, $statistik['total_sampel'] . ' sampel (Selesai Final: ' . $statistik['selesai_final'] . ')', 0, 1, 'L');

        $pdf->Cell(45, 4, 'Filter Status / Lab', 0, 0, 'L');
        $pdf->Cell(5, 4, ':', 0, 0, 'C');
        $pdf->Cell(90, 4, ($filter['status'] ?: 'Semua Status') . ' / ' . ($filter['jenis_pengujian'] ?: 'Semua Lab'), 0, 0, 'L');

        $pdf->Cell(45, 4, 'Rata-rata Waktu Proses (TAT)', 0, 0, 'L');
        $pdf->Cell(5, 4, ':', 0, 0, 'C');
        $pdf->Cell(0, 4, $statistik['rata_tat_hari'] . ' Hari (Target SLA <= 3 Hari)', 0, 1, 'L');

        $pdf->Ln(4);

        // 3. TABEL DATA
        $pdf->SetFillColor(235, 243, 247);
        $pdf->SetFont('Helvetica', 'B', 7.5);
        $pdf->SetDrawColor(180, 195, 205);

        // Lebar kolom total = 273 mm (297 - 24)
        $w = array(8, 26, 26, 44, 25, 23, 27, 27, 23, 25, 19);
        
        $pdf->Cell($w[0], 7, 'No', 1, 0, 'C', true);
        $pdf->Cell($w[1], 7, 'No. Urut / SIPT', 1, 0, 'C', true);
        $pdf->Cell($w[2], 7, 'Kode Sampel', 1, 0, 'C', true);
        $pdf->Cell($w[3], 7, 'Nama Sampel / Bets', 1, 0, 'C', true);
        $pdf->Cell($w[4], 7, 'Bidang Lab', 1, 0, 'C', true);
        $pdf->Cell($w[5], 7, 'Tgl Terima', 1, 0, 'C', true);
        $pdf->Cell($w[6], 7, 'Penguji / Analis', 1, 0, 'C', true);
        $pdf->Cell($w[7], 7, 'Penyelia', 1, 0, 'C', true);
        $pdf->Cell($w[8], 7, 'Status', 1, 0, 'C', true);
        $pdf->Cell($w[9], 7, 'Kesimpulan', 1, 0, 'C', true);
        $pdf->Cell($w[10], 7, 'Lead Time', 1, 1, 'C', true);

        $pdf->SetFont('Helvetica', '', 7);
        $no = 1;

        if (empty($daftar_rekap)) {
            $pdf->Cell(array_sum($w), 10, 'Tidak ada data pengujian yang sesuai dengan kriteria filter.', 1, 1, 'C');
        } else {
            foreach ($daftar_rekap as $row) {
                // Auto page break handle
                if ($pdf->GetY() > 180) {
                    $pdf->AddPage();
                    // Render Header ulang
                    $pdf->SetFillColor(235, 243, 247);
                    $pdf->SetFont('Helvetica', 'B', 7.5);
                    $pdf->Cell($w[0], 7, 'No', 1, 0, 'C', true);
                    $pdf->Cell($w[1], 7, 'No. Urut / SIPT', 1, 0, 'C', true);
                    $pdf->Cell($w[2], 7, 'Kode Sampel', 1, 0, 'C', true);
                    $pdf->Cell($w[3], 7, 'Nama Sampel / Bets', 1, 0, 'C', true);
                    $pdf->Cell($w[4], 7, 'Bidang Lab', 1, 0, 'C', true);
                    $pdf->Cell($w[5], 7, 'Tgl Terima', 1, 0, 'C', true);
                    $pdf->Cell($w[6], 7, 'Penguji / Analis', 1, 0, 'C', true);
                    $pdf->Cell($w[7], 7, 'Penyelia', 1, 0, 'C', true);
                    $pdf->Cell($w[8], 7, 'Status', 1, 0, 'C', true);
                    $pdf->Cell($w[9], 7, 'Kesimpulan', 1, 0, 'C', true);
                    $pdf->Cell($w[10], 7, 'Lead Time', 1, 1, 'C', true);
                    $pdf->SetFont('Helvetica', '', 7);
                }

                $kode_sampel = !empty($row['kode_sampel_manual']) ? $row['kode_sampel_manual'] : ('SMP-' . str_pad($row['sample_id'], 5, '0', STR_PAD_LEFT));
                $no_sipt = !empty($row['no_sipt']) ? $row['no_sipt'] : ('# ' . $row['no_sampel']);
                $nama_sampel = substr($row['nama_sampel'], 0, 24) . ($row['no_bets'] ? ' (B: ' . substr($row['no_bets'], 0, 8) . ')' : '');
                $tgl_terima = !empty($row['created_at']) ? date('d/m/y', strtotime($row['created_at'])) : '-';
                $penyelia = substr($row['nama_verifier'] ?: '-', 0, 16);
                $lead_time = $row['lead_time_hari'] !== null ? ($row['lead_time_hari'] . ' hr') : '-';

                $pdf->Cell($w[0], 6, $no++, 1, 0, 'C');
                $pdf->Cell($w[1], 6, substr($no_sipt, 0, 16), 1, 0, 'L');
                $pdf->Cell($w[2], 6, substr($kode_sampel, 0, 16), 1, 0, 'L');
                $pdf->Cell($w[3], 6, $nama_sampel, 1, 0, 'L');
                $pdf->Cell($w[4], 6, $row['jenis_pengujian'] ?: 'Kimia', 1, 0, 'C');
                $pdf->Cell($w[5], 6, $tgl_terima, 1, 0, 'C');
                $pdf->Cell($w[6], 6, substr($row['nama_penguji'] ?: '-', 0, 15), 1, 0, 'L');
                $pdf->Cell($w[7], 6, $penyelia, 1, 0, 'L');
                $pdf->Cell($w[8], 6, substr($row['status_sampel'], 0, 14), 1, 0, 'C');
                $pdf->Cell($w[9], 6, substr($row['kesimpulan_hasil'] ?: 'Belum Ada', 0, 15), 1, 0, 'C');
                $pdf->Cell($w[10], 6, $lead_time, 1, 1, 'C');
            }
        }

        // 4. FOOTER & TANDA TANGAN
        $pdf->Ln(6);
        if ($pdf->GetY() > 170) {
            $pdf->AddPage();
        }

        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(200, 4, '', 0, 0);
        $pdf->Cell(70, 4, 'Bandar Lampung, ' . date('d F Y'), 0, 1, 'C');
        $pdf->Cell(200, 4, '', 0, 0);
        $pdf->Cell(70, 4, 'Dicetak oleh,', 0, 1, 'C');
        $pdf->Ln(15);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(200, 4, '', 0, 0);
        $pdf->Cell(70, 4, $this->session->userdata('nama_lengkap'), 0, 1, 'C');
        $pdf->SetFont('Helvetica', '', 8);
        $pdf->Cell(200, 4, '', 0, 0);
        $pdf->Cell(70, 4, 'Petugas Laboratorium LIMSKU', 0, 1, 'C');

        $pdf->Output('I', 'Laporan_Rekap_Pengujian_' . date('Ymd_His') . '.pdf');
        exit;
    }
}
