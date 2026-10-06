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
        $this->Cell(100, 10, 'LIMSKU - Laporan Hasil Pengujian (Pra-Verifikasi)', 0, 0, 'L');
        $this->Cell(0, 10, 'Halaman ' . $this->PageNo(), 0, 0, 'R');
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
            'Kategori / Sub'       => ($sampel['kategori_sampel'] ?: '-') . ' / ' . ($sampel['sub_kategori'] ?: '-'),
            'Nomor Izin Edar'      => $sampel['nomor_izin_edar'] ?: '-',
            'No. Bets / Kedaluwarsa'=> ($sampel['no_bets'] ?: '-') . ' / ' . ($sampel['kedaluwarsa'] ?: '-'),
            'Sarana / Asal'        => ($sampel['nama_sarana'] ?: '-') . ' (' . ($sampel['kabupaten_kota'] ?: '-') . ')',
            'Kemasan & Jumlah'     => ($sampel['kemasan'] ?: '-') . ' (Total: ' . ($sampel['jumlah_total'] ?: '-') . ')'
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
            'Metode Uji'           => isset($hasil_uji['nama_metode']) ? $hasil_uji['nama_metode'] . ' (' . ($hasil_uji['kode_metode'] ?: '') . ')' : '-',
            'Parameter'            => isset($hasil_uji['nama_parameter']) ? $hasil_uji['nama_parameter'] : '-',
            'Analis / Penguji'     => isset($penguji['nama_lengkap']) ? $penguji['nama_lengkap'] : '-',
            'Waktu Pelaksanaan'    => ($hasil_uji['waktu_mulai'] ?: '-') . ' s/d ' . ($hasil_uji['waktu_selesai'] ?: '-')
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
        $this->Cell(0, 5.5, ($hasil_uji['catatan'] ?: '-'), 'R', 1, 'L');
        $this->Cell(0, 0, '', 'T', 1);
        $this->Ln(6);

        // Tanda Tangan Penguji (Tahap Pra-Verifikasi)
        $this->SetFont('helvetica', '', 8.5);
        $this->Cell(120, 4, '', 0, 0);
        $this->Cell(70, 4, 'Bandar Lampung, ' . date('d F Y', strtotime($hasil_uji['created_at'])), 0, 1, 'C');
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
