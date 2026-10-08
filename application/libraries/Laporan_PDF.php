<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'libraries/FPDF.php';

/**
 * Class Laporan_PDF
 * 
 * Generator PDF Laporan Hasil Pengujian LIMSKU BBPOM Bandar Lampung.
 */
class Laporan_PDF extends FPDF
{
    protected $instansi = 'BALAI BESAR PENGAWAS OBAT DAN MAKANAN DI BANDAR LAMPUNG';
    protected $sub_instansi = 'Laboratorium Pengujian Obat dan Makanan';
    protected $alamat = 'Jl. Way Pengubuan No. 3 Pahoman, Bandar Lampung';

    public function Header()
    {
        // Kop Surat
        $this->SetFont('helvetica', 'B', 12);
        $this->Cell(0, 5, $this->instansi, 0, 1, 'C');
        $this->SetFont('helvetica', 'B', 10);
        $this->Cell(0, 5, $this->sub_instansi, 0, 1, 'C');
        $this->SetFont('helvetica', '', 8);
        $this->Cell(0, 4, $this->alamat, 0, 1, 'C');
        
        $this->SetLineWidth(0.6);
        $this->Line(10, $this->GetY() + 2, 200, $this->GetY() + 2);
        $this->SetLineWidth(0.2);
        $this->Line(10, $this->GetY() + 3, 200, $this->GetY() + 3);
        $this->Ln(6);
    }

    public function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(100, 10, 'LIMSKU - Laporan Hasil Pengujian (LHU)', 0, 0, 'L');
        $this->Cell(0, 10, 'Halaman ' . $this->PageNo(), 0, 0, 'R');
    }

    /**
     * Helper Preparation: Mengambil path file TTD Digital aktif berbasis user_id.
     * Mengembalikan absolute path file TTD jika valid dan ada di disk, atau NULL.
     * 
     * @param int $user_id
     * @return string|null
     */
    public function getUserSignature($user_id)
    {
        if (empty($user_id)) {
            return NULL;
        }

        $ci =& get_instance();
        if (!isset($ci->Model_User_Signature)) {
            $ci->load->model('Model_User_Signature');
        }

        $sig = $ci->Model_User_Signature->ambil_by_user_id($user_id);

        if ($sig && !empty($sig['signature_file'])) {
            $full_path = FCPATH . $sig['signature_file'];
            if (file_exists($full_path)) {
                return $full_path;
            }
        }

        return NULL;
    }

    /**
     * Generate Laporan Hasil Uji PDF untuk Sesi Pengujian Multi-Form
     * 
     * @param array $sampel Data sampel
     * @param array $sesi Data sesi pengujian
     * @param array $forms Array data form hasil pengujian (test_results)
     * @param array $penguji Data user penguji
     * @param array|null $verifier Data user verifier / penyelia
     * @param array|null $approver Data user approver / manajer teknis
     * @param string|null $save_path Path penyimpanan file PDF
     * @return string
     */
    public function buat_laporan_sesi($sampel, $sesi, $forms, $penguji, $verifier = null, $approver = null, $save_path = null)
    {
        $this->AddPage('P', 'A4');

        $is_final = ($sesi['status'] === 'Approved / Final');
        $judul_doc = $is_final ? 'LAPORAN HASIL PENGUJIAN (LHU)' : 'LAPORAN HASIL PENGUJIAN SEMENTARA';

        // Judul Dokumen
        $this->SetFont('helvetica', 'B', 12);
        $this->Cell(0, 6, $judul_doc, 0, 1, 'C');
        $this->SetFont('helvetica', '', 9);
        $this->Cell(0, 4, 'Jenis Pengujian: ' . ($sesi['jenis_pengujian'] ?: '-') . ' | Status: ' . $sesi['status'], 0, 1, 'C');
        $this->Ln(4);

        // Bagian I: Identitas Sampel
        $this->SetFillColor(240, 240, 240);
        $this->SetFont('helvetica', 'B', 9);
        $this->Cell(0, 6, ' I. IDENTITAS SAMPEL', 1, 1, 'L', true);
        $this->SetFont('helvetica', '', 8.5);

        $identitas = array(
            'Kode / No. Sampel'     => ($sampel['kode_sampel_manual'] ?: ($sampel['no'] ? 'NO-' . $sampel['no'] : 'SMP-' . $sampel['id'])),
            'Nama Sampel'           => $sampel['nama_sampel'],
            'Kategori / Sub'        => ($sampel['kategori_sampel'] ?: '-') . ' / ' . (isset($sampel['sub_kategori']) ? $sampel['sub_kategori'] : '-'),
            'Nomor Izin Edar'       => isset($sampel['nomor_izin_edar']) ? $sampel['nomor_izin_edar'] : '-',
            'No. Bets / Kedaluwarsa' => (isset($sampel['no_bets']) ? $sampel['no_bets'] : '-') . ' / ' . (isset($sampel['kedaluwarsa']) ? $sampel['kedaluwarsa'] : '-'),
            'Sarana / Asal'         => (isset($sampel['nama_sarana']) ? $sampel['nama_sarana'] : '-') . ' (' . (isset($sampel['kabupaten_kota']) ? $sampel['kabupaten_kota'] : '-') . ')',
            'Kemasan & Jumlah'      => (isset($sampel['kemasan']) ? $sampel['kemasan'] : '-') . ' (Total: ' . (isset($sampel['jumlah_total']) ? $sampel['jumlah_total'] : '-') . ')'
        );

        foreach ($identitas as $k => $v) {
            $this->Cell(45, 5, '  ' . $k, 'L', 0, 'L');
            $this->Cell(5, 5, ':', 0, 0, 'C');
            $this->Cell(0, 5, $v, 'R', 1, 'L');
        }
        $this->Cell(0, 0, '', 'T', 1);
        $this->Ln(3);

        // Bagian II: Hasil Pengujian Multi-Form
        $this->SetFont('helvetica', 'B', 9);
        $this->Cell(0, 6, ' II. RINCIAN HASIL PENGUJIAN (' . count($forms) . ' PARAMETER / FORM)', 1, 1, 'L', true);
        $this->Ln(2);

        $form_num = 1;
        foreach ($forms as $f) {
            $this->SetFont('helvetica', 'B', 8.5);
            $this->SetFillColor(250, 250, 250);
            $form_title = $form_num++ . '. ' . ($f['nama_template'] ?: 'Form Pengujian') . ' (' . ($f['nama_metode'] ?: (isset($f['nama_parameter']) ? $f['nama_parameter'] : '-')) . ')';
            $this->Cell(0, 5.5, '  ' . $form_title, 1, 1, 'L', true);

            // Dynamic fields
            $data_hasil = json_decode($f['data_hasil'], true);
            if (!is_array($data_hasil)) {
                $data_hasil = array();
            }

            // Sub table header
            $this->SetFont('helvetica', 'B', 8);
            $this->Cell(15, 5, 'No', 1, 0, 'C');
            $this->Cell(85, 5, 'Parameter / Komponen Uji', 1, 0, 'L');
            $this->Cell(90, 5, 'Hasil Pengamatan / Nilai Uji', 1, 1, 'L');

            $this->SetFont('helvetica', '', 8);
            $f_idx = 1;
            if (!empty($data_hasil)) {
                foreach ($data_hasil as $field_key => $val) {
                    $label = ucwords(str_replace('_', ' ', $field_key));
                    $nilai_teks = is_array($val) ? json_encode($val) : (string)$val;
                    if ($nilai_teks === '') {
                        $nilai_teks = '-';
                    }
                    $this->Cell(15, 5, $f_idx++ . '.', 1, 0, 'C');
                    $this->Cell(85, 5, ' ' . $label, 1, 0, 'L');
                    $this->Cell(90, 5, ' ' . $nilai_teks, 1, 1, 'L');
                }
            } else {
                $this->Cell(0, 5, 'Tidak ada data pengamatan.', 1, 1, 'C');
            }

            // Kesimpulan & Catatan Form
            $this->SetFont('helvetica', 'B', 8);
            $this->Cell(45, 5, '  Kesimpulan Form', 'L', 0, 'L');
            $this->Cell(5, 5, ':', 0, 0, 'C');
            $this->SetFont('helvetica', 'B', 8);
            $this->Cell(0, 5, ($f['kesimpulan'] ?: '-'), 'R', 1, 'L');

            if (!empty($f['catatan'])) {
                $this->SetFont('helvetica', '', 8);
                $this->Cell(45, 5, '  Catatan Penguji', 'L', 0, 'L');
                $this->Cell(5, 5, ':', 0, 0, 'C');
                $this->Cell(0, 5, $f['catatan'], 'R', 1, 'L');
            }
            $this->Cell(0, 0, '', 'T', 1);
            $this->Ln(2);
        }

        $this->Ln(4);

        // Bagian III: Lembar Pengesahan & Tanda Tangan Workflow (3-Column)
        $this->SetFont('helvetica', 'B', 9);
        $this->Cell(0, 6, ' III. LEMBAR PENGESAHAN HASIL PENGUJIAN', 1, 1, 'L', true);
        $this->Ln(3);

        $tgl_selesai = !empty($sesi['waktu_selesai']) ? date('d F Y', strtotime($sesi['waktu_selesai'])) : date('d F Y');
        $tgl_verify  = !empty($sesi['waktu_verifikasi']) ? date('d F Y', strtotime($sesi['waktu_verifikasi'])) : '-';
        $tgl_approve = !empty($sesi['waktu_approval']) ? date('d F Y', strtotime($sesi['waktu_approval'])) : '-';

        $this->SetFont('helvetica', '', 8);
        $this->Cell(63, 4, 'Penguji / Analis,', 0, 0, 'C');
        $this->Cell(63, 4, 'Penyelia / Verifier,', 0, 0, 'C');
        $this->Cell(64, 4, 'Manajer Teknis / Approver,', 0, 1, 'C');

        $this->SetFont('helvetica', 'I', 7.5);
        $this->Cell(63, 4, 'Selesai: ' . $tgl_selesai, 0, 0, 'C');
        $this->Cell(63, 4, 'Diverifikasi: ' . $tgl_verify, 0, 0, 'C');
        $this->Cell(64, 4, 'Disetujui: ' . $tgl_approve, 0, 1, 'C');

        $this->Ln(12);

        $this->SetFont('helvetica', 'B', 8.5);
        $nama_p = isset($penguji['nama_lengkap']) ? $penguji['nama_lengkap'] : (isset($sesi['nama_penguji']) ? $sesi['nama_penguji'] : '-');
        $nip_p  = isset($penguji['nip']) && $penguji['nip'] ? $penguji['nip'] : '-';

        $nama_v = isset($verifier['nama_lengkap']) ? $verifier['nama_lengkap'] : (isset($sesi['nama_verifier']) ? $sesi['nama_verifier'] : '-');
        $nip_v  = isset($verifier['nip']) && $verifier['nip'] ? $verifier['nip'] : '-';

        $nama_a = isset($approver['nama_lengkap']) ? $approver['nama_lengkap'] : (isset($sesi['nama_approver']) ? $sesi['nama_approver'] : '-');
        $nip_a  = isset($approver['nip']) && $approver['nip'] ? $approver['nip'] : '-';

        $this->Cell(63, 4, $nama_p, 0, 0, 'C');
        $this->Cell(63, 4, $nama_v, 0, 0, 'C');
        $this->Cell(64, 4, $nama_a, 0, 1, 'C');

        $this->SetFont('helvetica', '', 7.5);
        $this->Cell(63, 4, 'NIP. ' . $nip_p, 0, 0, 'C');
        $this->Cell(63, 4, 'NIP. ' . $nip_v, 0, 0, 'C');
        $this->Cell(64, 4, 'NIP. ' . $nip_a, 0, 1, 'C');

        if ($save_path) {
            $dir = dirname($save_path);
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
            $this->Output('F', $save_path);
        }

        return $save_path;
    }

    /**
     * Generate Laporan Hasil Uji PDF
     * 
     * @param array $sampel Data sampel
     * @param array $hasil_uji Data hasil pengujian
     * @param array $template Data template & skema
     * @param array $penguji Data user penguji
     * @param string $save_path Path penyimpanan file di server (opsional)
     * @return string
     */
    public function buat_laporan($sampel, $hasil_uji, $template, $penguji, $save_path = null)
    {
        $this->AddPage('P', 'A4');

        // Judul Dokumen
        $this->SetFont('helvetica', 'B', 11);
        $this->Cell(0, 6, 'LAPORAN HASIL PENGUJIAN SEMENTARA', 0, 1, 'C');
        $this->SetFont('helvetica', '', 9);
        $this->Cell(0, 4, 'Status Dokumen: ' . $hasil_uji['status'], 0, 1, 'C');
        $this->Ln(4);

        // Bagian I: Identitas Sampel
        $this->SetFillColor(240, 240, 240);
        $this->SetFont('helvetica', 'B', 9);
        $this->Cell(0, 6, ' I. IDENTITAS SAMPEL', 1, 1, 'L', true);
        $this->SetFont('helvetica', '', 8.5);

        $identitas = array(
            'Kode / No. Sampel'    => ($sampel['kode_sampel_manual'] ?: ($sampel['no'] ? 'NO-' . $sampel['no'] : 'SMP-' . $sampel['id'])),
            'Nama Sampel'          => $sampel['nama_sampel'],
            'Kategori / Sub'       => ($sampel['kategori_sampel'] ?: '-') . ' / ' . (isset($sampel['sub_kategori']) ? $sampel['sub_kategori'] : '-'),
            'Nomor Izin Edar'      => isset($sampel['nomor_izin_edar']) ? $sampel['nomor_izin_edar'] : '-',
            'No. Bets / Kedaluwarsa'=> (isset($sampel['no_bets']) ? $sampel['no_bets'] : '-') . ' / ' . (isset($sampel['kedaluwarsa']) ? $sampel['kedaluwarsa'] : '-'),
            'Sarana / Asal'        => (isset($sampel['nama_sarana']) ? $sampel['nama_sarana'] : '-') . ' (' . (isset($sampel['kabupaten_kota']) ? $sampel['kabupaten_kota'] : '-') . ')',
            'Kemasan & Jumlah'     => (isset($sampel['kemasan']) ? $sampel['kemasan'] : '-') . ' (Total: ' . (isset($sampel['jumlah_total']) ? $sampel['jumlah_total'] : '-') . ')'
        );

        foreach ($identitas as $k => $v) {
            $this->Cell(45, 5, '  ' . $k, 'L', 0, 'L');
            $this->Cell(5, 5, ':', 0, 0, 'C');
            $this->Cell(0, 5, $v, 'R', 1, 'L');
        }
        $this->Cell(0, 0, '', 'T', 1);
        $this->Ln(3);

        // Bagian II: Metode & Parameter Pengujian
        $this->SetFont('helvetica', 'B', 9);
        $this->Cell(0, 6, ' II. PARAMETER & METODE PENGUJIAN', 1, 1, 'L', true);
        $this->SetFont('helvetica', '', 8.5);

        $metode_param = array(
            'Template Pengujian'   => isset($template['nama_template']) ? $template['nama_template'] : '-',
            'Metode Uji'           => isset($hasil_uji['nama_metode']) ? $hasil_uji['nama_metode'] . ' (' . (isset($hasil_uji['kode_metode']) ? $hasil_uji['kode_metode'] : '') . ')' : '-',
            'Parameter'            => isset($hasil_uji['nama_parameter']) ? $hasil_uji['nama_parameter'] : '-',
            'Analis / Penguji'     => isset($penguji['nama_lengkap']) ? $penguji['nama_lengkap'] : '-',
            'Waktu Pelaksanaan'    => (isset($hasil_uji['waktu_mulai']) ? $hasil_uji['waktu_mulai'] : '-') . ' s/d ' . (isset($hasil_uji['waktu_selesai']) ? $hasil_uji['waktu_selesai'] : '-')
        );

        foreach ($metode_param as $k => $v) {
            $this->Cell(45, 5, '  ' . $k, 'L', 0, 'L');
            $this->Cell(5, 5, ':', 0, 0, 'C');
            $this->Cell(0, 5, $v, 'R', 1, 'L');
        }
        $this->Cell(0, 0, '', 'T', 1);
        $this->Ln(3);

        // Bagian III: Rincian Hasil Pengujian
        $this->SetFont('helvetica', 'B', 9);
        $this->Cell(0, 6, ' III. HASIL PENGUJIAN DINAMIS', 1, 1, 'L', true);
        
        // Header Tabel
        $this->SetFont('helvetica', 'B', 8.5);
        $this->Cell(15, 6, 'No.', 1, 0, 'C');
        $this->Cell(85, 6, 'Parameter / Komponen Uji', 1, 0, 'L');
        $this->Cell(90, 6, 'Hasil Pengamatan / Nilai Uji', 1, 1, 'L');

        // Isi Hasil Pengujian
        $this->SetFont('helvetica', '', 8.5);
        $data_hasil = json_decode($hasil_uji['data_hasil'], true);
        if (!is_array($data_hasil)) {
            $data_hasil = array();
        }

        $no = 1;
        if (!empty($data_hasil)) {
            foreach ($data_hasil as $field_key => $val) {
                // Cari label yang rapi dari skema form
                $label = ucwords(str_replace('_', ' ', $field_key));
                if (isset($template['fields_map'][$field_key]['label'])) {
                    $label = $template['fields_map'][$field_key]['label'];
                }
                
                $nilai_teks = is_array($val) ? json_encode($val) : (string)$val;
                if ($nilai_teks === '') {
                    $nilai_teks = '-';
                }

                $this->Cell(15, 5.5, $no++ . '.', 1, 0, 'C');
                $this->Cell(85, 5.5, ' ' . $label, 1, 0, 'L');
                $this->Cell(90, 5.5, ' ' . $nilai_teks, 1, 1, 'L');
            }
        } else {
            $this->Cell(0, 6, 'Tidak ada data pengamatan.', 1, 1, 'C');
        }
        $this->Ln(3);

        // Bagian IV: Kesimpulan & Catatan
        $this->SetFont('helvetica', 'B', 9);
        $this->Cell(0, 6, ' IV. KESIMPULAN & CATATAN PENGUJIAN', 1, 1, 'L', true);
        $this->SetFont('helvetica', '', 8.5);

        $this->Cell(45, 5.5, '  Kesimpulan Akhir', 'L', 0, 'L');
        $this->Cell(5, 5.5, ':', 0, 0, 'C');
        $this->SetFont('helvetica', 'B', 8.5);
        $this->Cell(0, 5.5, $hasil_uji['kesimpulan'], 'R', 1, 'L');
        
        $this->SetFont('helvetica', '', 8.5);
        $this->Cell(45, 5.5, '  Catatan Penguji', 'L', 0, 'L');
        $this->Cell(5, 5.5, ':', 0, 0, 'C');
        $this->Cell(0, 5.5, (isset($hasil_uji['catatan']) ? $hasil_uji['catatan'] : '-'), 'R', 1, 'L');
        $this->Cell(0, 0, '', 'T', 1);
        $this->Ln(6);

        // Tanda Tangan Penguji (Tahap Pra-Verifikasi)
        $this->SetFont('helvetica', '', 8.5);
        $this->Cell(120, 4, '', 0, 0);
        $this->Cell(70, 4, 'Bandar Lampung, ' . date('d F Y', strtotime(isset($hasil_uji['created_at']) ? $hasil_uji['created_at'] : 'now')), 0, 1, 'C');
        $this->Cell(120, 4, '', 0, 0);
        $this->Cell(70, 4, 'Penguji / Analis Laboratorium,', 0, 1, 'C');
        $this->Ln(15);
        $this->SetFont('helvetica', 'B', 8.5);
        $this->Cell(120, 4, '', 0, 0);
        $this->Cell(70, 4, (isset($penguji['nama_lengkap']) ? $penguji['nama_lengkap'] : '-'), 0, 1, 'C');
        $this->SetFont('helvetica', '', 8);
        $this->Cell(120, 4, '', 0, 0);
        $this->Cell(70, 4, 'NIP. ' . (isset($penguji['nip']) && $penguji['nip'] ? $penguji['nip'] : '-'), 0, 1, 'C');

        if ($save_path) {
            $dir = dirname($save_path);
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
            $this->Output('F', $save_path);
        }

        return $save_path;
    }
}
