<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'libraries/FPDF.php';

/**
 * Class CP_Kimia_PDF
 * 
 * Generator PDF Catatan Pengujian (CP) Laboratorium Kimia LIMSKU.
 * Dibuat sesuai dengan struktur resmi Catatan Pengujian BPOM (Sheet CP-SIPT) BBPOM di Bandar Lampung,
 * konsisten dan seragam dengan Catatan Pengujian (CP) Mikrobiologi.
 */
class CP_Kimia_PDF extends FPDF
{
    protected $instansi = 'BALAI BESAR PENGAWAS OBAT DAN MAKANAN DI BANDAR LAMPUNG';
    protected $sub_instansi = 'Laboratorium Pengujian Obat dan Makanan';
    protected $alamat = 'Jl. Way Pengubuan No. 3 Pahoman, Bandar Lampung';

    public function Header()
    {
        // Header resmi Catatan Pengujian
    }

    public function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', '', 7.5);
        $this->Cell(100, 10, 'LIMSKU - Catatan Pengujian (CP) Kimia', 0, 0, 'L');
        $this->Cell(0, 10, 'Halaman ' . $this->PageNo(), 0, 0, 'R');
    }

    /**
     * Memeriksa dan memvalidasi keabsahan file signature
     *
     * @param int $user_id
     * @return array [bool $valid, string|null $path, string|null $error_msg]
     */
    public function validateUserSignature($user_id)
    {
        if (empty($user_id)) {
            return [false, null, 'ID pengguna tanda tangan tidak ditentukan.'];
        }

        if (!function_exists('get_instance')) {
            return [false, null, 'Instance sistem tidak tersedia.'];
        }

        $ci =& get_instance();
        if (!$ci) {
            return [false, null, 'Instance sistem tidak tersedia.'];
        }
        if (!isset($ci->Model_User_Signature)) {
            $ci->load->model('Model_User_Signature');
        }

        $sig = $ci->Model_User_Signature->ambil_by_user_id($user_id);
        if (!$sig || empty($sig['signature_file'])) {
            return [false, null, 'Tanda tangan digital belum diunggah di profil akun.'];
        }

        $base_dir = defined('FCPATH') ? FCPATH : dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR;
        
        $clean_rel = str_replace(array('../', '..\\'), '', $sig['signature_file']);
        $full_path = $base_dir . $clean_rel;

        $real_path = realpath($full_path);
        $allowed_dir = realpath($base_dir . 'uploads/signatures');
        
        if (!$real_path || !$allowed_dir || strpos($real_path, $allowed_dir) !== 0) {
            return [false, null, 'Lokasi berkas tanda tangan tidak sah atau di luar direktori aman.'];
        }

        if (!is_file($real_path) || !is_readable($real_path)) {
            return [false, null, 'Berkas tanda tangan digital tidak dapat dibaca di server.'];
        }

        $img_info = @getimagesize($real_path);
        if ($img_info === false) {
            return [false, null, 'Berkas tanda tangan rusak atau bukan file gambar.'];
        }

        $allowed_types = [IMAGETYPE_PNG, IMAGETYPE_JPEG];
        if (!in_array($img_info[2], $allowed_types, true)) {
            return [false, null, 'Format berkas tanda tangan tidak didukung (wajib PNG atau JPG/JPEG).'];
        }

        return [true, $real_path, null];
    }

    /**
     * Helper: Ambil path file signature valid jika lolos verifikasi keamanan
     */
    public function getUserSignature($user_id)
    {
        $val = $this->validateUserSignature($user_id);
        return $val[0] ? $val[1] : NULL;
    }

    /**
     * Helper mencetak satu baris tabel dengan multi-line wrapping dinamis
     */
    public function RowMultiCell($widths, $aligns, $data, $min_height = 6)
    {
        $max_lines = 1;
        for ($i = 0; $i < count($data); $i++) {
            $w = $widths[$i];
            $txt = $data[$i];
            $lines = $this->NbLines($w, $txt);
            if ($lines > $max_lines) {
                $max_lines = $lines;
            }
        }
        $h = max($min_height, $max_lines * 4.5);

        if ($this->GetY() + $h > 265) {
            $this->AddPage('P', 'A4');
        }

        $startX = $this->GetX();
        $startY = $this->GetY();

        for ($i = 0; $i < count($data); $i++) {
            $w = $widths[$i];
            $a = $aligns[$i];
            $txt = $data[$i];

            $this->Rect($startX, $startY, $w, $h);
            $this->SetXY($startX + 0.8, $startY + 1);
            $this->MultiCell($w - 1.6, 4, $txt, 0, $a);

            $startX += $w;
            $this->SetXY($startX, $startY);
        }
        $this->SetY($startY + $h);
    }

    /**
     * Hitung jumlah baris teks pada lebar tertentu
     */
    public function NbLines($w, $txt)
    {
        $cw =& $this->CurrentFont['cw'];
        if ($w == 0) $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', (string)$txt);
        $nb = strlen($s);
        if ($nb > 0 and $s[$nb - 1] == "\n") $nb--;
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while ($i < $nb) {
            $c = $s[$i];
            if ($c == "\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if ($c == ' ') $sep = $i;
            $l += $cw[$c] ?? 500;
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j) $i++;
                } else {
                    $i = $sep + 1;
                }
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            } else {
                $i++;
            }
        }
        return $nl;
    }

    /**
     * Buat Dokumen CP Kimia PDF
     * 
     * @param array $sampel Data sampel (tabel samples)
     * @param array $sesi Data sesi pengujian (tabel testing_sessions)
     * @param array $forms Data LCP / test_results yang tergabung dalam sesi
     * @param array $penguji Data user penguji
     * @param array $verifier Data user verifier/penyelia
     * @param string|null $save_path Path penyimpanan file di server
     * @return string
     */
    public function buat_cp($sampel, $sesi, $forms, $penguji, $verifier, $save_path = null)
    {
        $this->AddPage('P', 'A4');
        $this->SetMargins(10, 10, 10);
        $this->SetAutoPageBreak(true, 15);

        // Lebar tabel total = 190 mm
        // ==========================================
        // JUDUL DOKUMEN (Border Box, Bold 11pt)
        // ==========================================
        $this->SetFont('Arial', 'B', 11);
        $this->Cell(190, 7.5, 'CATATAN PENGUJIAN', 1, 1, 'C');

        // ==========================================
        // BARIS 3: 1 Nama Sampel | 2 Kode Sampel | 3 Segel (Tinggi 6.5 mm)
        // Kolom 1 (7mm), Label (75mm) | Kolom 2 (7mm), Label (56mm) | Kolom 3 (7mm), Label (38mm)
        // ==========================================
        $this->SetFont('Arial', '', 9);
        $this->Cell(7, 6.5, '1', 1, 0, 'C');
        $this->Cell(75, 6.5, 'Nama Sampel', 1, 0, 'L');
        $this->Cell(7, 6.5, '2', 1, 0, 'C');
        $this->Cell(56, 6.5, 'Kode Sampel', 1, 0, 'L');
        $this->Cell(7, 6.5, '3', 1, 0, 'C');
        $this->Cell(38, 6.5, 'Segel', 1, 1, 'L');

        // ==========================================
        // BARIS 4: Data Nilai Baris 1-3 (Tinggi 7 mm)
        // ==========================================
        $kode_smp = !empty($sampel['kode_sampel_manual']) 
            ? $sampel['kode_sampel_manual'] 
            : (!empty($sampel['no']) ? 'NO-' . $sampel['no'] : (!empty($sampel['id']) ? 'SMP-' . $sampel['id'] : '-'));

        $nama_smp = (isset($sampel['nama_sampel']) && $sampel['nama_sampel'] !== '') ? $sampel['nama_sampel'] : '-';
        $segel_val = (isset($sampel['kondisi_produk']) && $sampel['kondisi_produk'] !== '') ? $sampel['kondisi_produk'] : '-';

        $this->SetFont('Arial', '', 8.5);
        $this->Cell(82, 7, ' ' . substr($nama_smp, 0, 48), 1, 0, 'L');
        $this->Cell(63, 7, ' ' . $kode_smp, 1, 0, 'L');
        $this->Cell(45, 7, ' ' . $segel_val, 1, 1, 'L');

        // ==========================================
        // BARIS 5: 4 Tanggal Surat Perintah Pengujian | 5 Laboratorium
        // ==========================================
        $this->SetFont('Arial', '', 9);
        $this->Cell(7, 6.5, '4', 1, 0, 'C');
        $this->Cell(75, 6.5, 'Tanggal Surat Perintah Pengujian', 1, 0, 'L');
        $this->Cell(7, 6.5, '5', 1, 0, 'C');
        $this->Cell(101, 6.5, 'Laboratorium', 1, 1, 'L');

        // ==========================================
        // BARIS 6: Data Nilai Baris 4 & 5 (Kimia)
        // ==========================================
        $surtug_val = '-';
        if (!empty($sampel['surtug'])) {
            $surtug_val = $sampel['surtug'];
        } elseif (!empty($sampel['tanggal_surat_perintah'])) {
            $surtug_val = date('d F Y', strtotime($sampel['tanggal_surat_perintah']));
        }

        $this->SetFont('Arial', '', 8.5);
        $this->Cell(82, 7, ' ' . substr($surtug_val, 0, 48), 1, 0, 'L');
        $this->Cell(108, 7, ' Kimia', 1, 1, 'L');

        // ==========================================
        // BARIS 7: 6 Informasi Sampel (Header Baris)
        // ==========================================
        $this->SetFont('Arial', '', 9);
        $this->Cell(7, 6.5, '6', 'LTR', 0, 'C');
        $this->Cell(183, 6.5, 'Informasi Sampel', 1, 1, 'L');

        // Sub Baris Informasi Sampel: Kolom B (7mm blank), Label (C-F: 75mm), Value (G-K: 108mm)
        $info_rows = array(
            'Kemasan'                                => (isset($sampel['kemasan']) && $sampel['kemasan'] !== '') ? $sampel['kemasan'] : '-',
            'Komposisi'                              => (isset($sampel['komposisi']) && $sampel['komposisi'] !== '') ? $sampel['komposisi'] : '-',
            'Nama Pabrik / Distributor / Importir'   => (isset($sampel['nama_alamat_perusahaan']) && $sampel['nama_alamat_perusahaan'] !== '') ? $sampel['nama_alamat_perusahaan'] : ((isset($sampel['nama_sarana']) && $sampel['nama_sarana'] !== '') ? $sampel['nama_sarana'] : '-'),
            'No. Bets / Lot'                         => (isset($sampel['no_bets']) && $sampel['no_bets'] !== '') ? $sampel['no_bets'] : '-',
            'No. Registrasi'                         => (isset($sampel['nomor_izin_edar']) && $sampel['nomor_izin_edar'] !== '') ? $sampel['nomor_izin_edar'] : '-',
            'Tanggal Kadaluarsa'                     => (isset($sampel['kedaluwarsa']) && $sampel['kedaluwarsa'] !== '') ? $sampel['kedaluwarsa'] : '-'
        );

        foreach ($info_rows as $lbl => $val) {
            $h_row = ($lbl === 'Komposisi') ? 14 : 6;
            $this->Cell(7, $h_row, '', 'LR', 0, 'C');
            
            // Label
            $this->SetFont('Arial', '', 8.5);
            $this->Cell(75, $h_row, ' ' . $lbl, 1, 0, 'L');
            
            // Value
            if ($lbl === 'Komposisi') {
                $currX = $this->GetX();
                $currY = $this->GetY();
                $this->Rect($currX, $currY, 108, $h_row);
                $this->SetXY($currX + 1, $currY + 1);
                $this->MultiCell(106, 4, $val, 0, 'L');
                $this->SetXY($currX + 108, $currY + $h_row);
                $this->Ln(0);
            } else {
                $this->Cell(108, $h_row, ' ' . substr($val, 0, 70), 1, 1, 'L');
            }
        }
        // Penutup border bawah nomor 6
        $this->Cell(7, 0.1, '', 'B', 0, 'C');
        $this->Cell(183, 0.1, '', 0, 1);

        // ==========================================
        // BARIS 14: 7 Jumlah sampel yang diterima | 8 Label Sampel
        // ==========================================
        $this->SetFont('Arial', '', 9);
        $this->Cell(7, 6.5, '7', 1, 0, 'C');
        $this->Cell(75, 6.5, 'Jumlah sampel yang diterima', 1, 0, 'L');
        $this->Cell(7, 6.5, '8', 1, 0, 'C');
        $this->Cell(101, 6.5, 'Label Sampel', 1, 1, 'L');

        // Nilai Baris 15: Jumlah kimia & Label
        $jml_diterima = (isset($sampel['jumlah_kimia']) && $sampel['jumlah_kimia'] !== '') 
            ? $sampel['jumlah_kimia'] 
            : ((isset($sampel['jumlah_total']) && $sampel['jumlah_total'] !== '') ? $sampel['jumlah_total'] : '-');

        $label_smp = (isset($sampel['penandaan']) && $sampel['penandaan'] !== '') ? $sampel['penandaan'] : '-';

        $this->SetFont('Arial', '', 8.5);
        $this->Cell(82, 7, ' ' . $jml_diterima, 1, 0, 'L');
        $this->Cell(108, 7, ' ' . $label_smp, 1, 1, 'L');

        // ==========================================
        // BARIS 16: 9 Hasil Pengujian (Header Baris)
        // ==========================================
        $this->SetFont('Arial', '', 9);
        $this->Cell(7, 6, '9', 1, 0, 'C');
        $this->Cell(183, 6, 'Hasil Pengujian', 1, 1, 'L');

        // BARIS 17: Pemerian
        $this->Cell(190, 5.5, 'Pemerian', 1, 1, 'L');

        // BARIS 18: Bentuk: | Warna: | Bau : | Rasa:
        $pemerian = array('Bentuk' => '-', 'Warna' => '-', 'Bau' => '-', 'Rasa' => '-');
        if (!empty($sampel['bentuk_sediaan'])) {
            $pemerian['Bentuk'] = $sampel['bentuk_sediaan'];
        } elseif (!empty($sampel['pemerian']) && is_string($sampel['pemerian'])) {
            $pemerian['Bentuk'] = substr($sampel['pemerian'], 0, 30);
        }
        if (!empty($sampel['pemerian_warna'])) $pemerian['Warna'] = $sampel['pemerian_warna'];
        if (!empty($sampel['pemerian_bau']))   $pemerian['Bau']   = $sampel['pemerian_bau'];
        if (!empty($sampel['pemerian_rasa']))  $pemerian['Rasa']  = $sampel['pemerian_rasa'];

        foreach ($forms as $f) {
            if (stripos($f['parameter_uji'], 'Pemerian') !== false || stripos($f['nama_template'], 'Pemerian') !== false) {
                $dh = !empty($f['data_hasil']) ? json_decode($f['data_hasil'], true) : array();
                $pv = isset($dh['parameter_values']) && is_array($dh['parameter_values']) ? $dh['parameter_values'] : $dh;
                if (!empty($pv['bentuk'])) $pemerian['Bentuk'] = $pv['bentuk'];
                if (!empty($pv['warna']))  $pemerian['Warna']  = $pv['warna'];
                if (!empty($pv['bau']))    $pemerian['Bau']    = $pv['bau'];
                if (!empty($pv['rasa']))   $pemerian['Rasa']   = $pv['rasa'];
            }
        }

        $this->SetFont('Arial', '', 8.5);
        $this->Cell(45, 6, ' Bentuk: ' . substr($pemerian['Bentuk'], 0, 20), 1, 0, 'L');
        $this->Cell(45, 6, ' Warna: ' . substr($pemerian['Warna'], 0, 20), 1, 0, 'L');
        $this->Cell(50, 6, ' Bau : ' . substr($pemerian['Bau'], 0, 20), 1, 0, 'L');
        $this->Cell(50, 6, ' Rasa: ' . substr($pemerian['Rasa'], 0, 20), 1, 1, 'L');

        // ==========================================
        // BARIS 19: Tabel Uji yang dilakukan, Hasil, Syarat, Pustaka, Metode
        // Proporsi lebar kolom presisi (Total = 190 mm):
        // Uji yang dilakukan: 60 mm, Hasil: 44 mm, Syarat: 32 mm, Pustaka: 25 mm, Metode: 29 mm
        // ==========================================
        $col_widths = array(60, 44, 32, 25, 29);
        $col_aligns = array('L', 'L', 'L', 'L', 'C');

        $print_table_header = function() use ($col_widths) {
            $this->SetFont('Arial', 'B', 8.5);
            $this->Cell($col_widths[0], 6, 'Uji yang dilakukan', 1, 0, 'C');
            $this->Cell($col_widths[1], 6, 'Hasil', 1, 0, 'C');
            $this->Cell($col_widths[2], 6, 'Syarat', 1, 0, 'C');
            $this->Cell($col_widths[3], 6, 'Pustaka', 1, 0, 'C');
            $this->Cell($col_widths[4], 6, 'Metode', 1, 1, 'C');
        };

        $print_table_header();

        // Isi Tabel Pengujian dari LCP Kimia
        $this->SetFont('Arial', '', 8);
        $row_count = 0;
        if (!empty($forms)) {
            foreach ($forms as $f) {
                // Lewati form khusus pemerian karena sudah masuk di box pemerian di atas
                if (stripos($f['parameter_uji'], 'Pemerian') !== false || stripos($f['nama_template'], 'Pemerian') !== false) {
                    continue;
                }

                if ($this->GetY() > 250) {
                    $this->AddPage('P', 'A4');
                    $print_table_header();
                    $this->SetFont('Arial', '', 8);
                }

                $dh = !empty($f['data_hasil']) ? json_decode($f['data_hasil'], true) : array();
                $pv = isset($dh['parameter_values']) && is_array($dh['parameter_values']) ? $dh['parameter_values'] : $dh;

                $nama_uji = !empty($f['parameter_uji']) ? $f['parameter_uji'] : (!empty($f['nama_template']) ? $f['nama_template'] : '-');
                $nama_zat = !empty($f['nama_zat_aktif']) ? $f['nama_zat_aktif'] : ($sesi['nama_zat_aktif'] ?? '');
                if (!empty($nama_zat) && stripos($nama_uji, $nama_zat) === false) {
                    $nama_uji .= "\n(" . $nama_zat . ")";
                }

                $hasil_str = !empty($f['hasil_uji']) ? $f['hasil_uji'] : (!empty($pv['hasil_uji']) ? $pv['hasil_uji'] : (!empty($f['kesimpulan']) ? $f['kesimpulan'] : '-'));
                $syarat    = !empty($f['persyaratan']) ? $f['persyaratan'] : (!empty($pv['syarat']) ? $pv['syarat'] : (!empty($pv['persyaratan']) ? $pv['persyaratan'] : '-'));
                $pustaka   = !empty($f['pustaka_acuan']) ? $f['pustaka_acuan'] : (!empty($pv['pustaka_metode']) ? $pv['pustaka_metode'] : (!empty($pv['pustaka_acuan']) ? $pv['pustaka_acuan'] : '-'));
                $metode_kd = !empty($f['metode_analisa']) ? $f['metode_analisa'] : (!empty($pv['metode_analisa']) ? $pv['metode_analisa'] : (!empty($f['nama_metode']) ? $f['nama_metode'] : '-'));

                // Cetak baris dengan pembungkusan otomatis jika panjang (MultiCell wrapping)
                $row_data = array($nama_uji, $hasil_str, $syarat, $pustaka, $metode_kd);
                $this->RowMultiCell($col_widths, $col_aligns, $row_data, 6);
                $row_count++;
            }
        }

        // Pertahankan minimal 5 baris grid seperti workbook template (Rows 20-26)
        while ($row_count < 5) {
            $this->Cell($col_widths[0], 6, '', 1, 0, 'L');
            $this->Cell($col_widths[1], 6, '', 1, 0, 'L');
            $this->Cell($col_widths[2], 6, '', 1, 0, 'L');
            $this->Cell($col_widths[3], 6, '', 1, 0, 'L');
            $this->Cell($col_widths[4], 6, '', 1, 1, 'C');
            $row_count++;
        }

        if ($this->GetY() > 225) {
            $this->AddPage('P', 'A4');
        }

        // ==========================================
        // BARIS 27: 10 Kesimpulan (Row 27)
        // ==========================================
        $kesimpulan_sesi = !empty($sesi['kesimpulan']) ? $sesi['kesimpulan'] : 'MS terhadap Uji yang dilakukan';
        $this->SetFont('Arial', '', 9);
        $this->Cell(7, 6, '10', 1, 0, 'C');
        $this->Cell(75, 6, 'Kesimpulan', 1, 0, 'L');
        $this->SetFont('Arial', 'B', 8.5);
        $this->Cell(108, 6, ' ' . $kesimpulan_sesi, 1, 1, 'L');

        // ==========================================
        // BARIS 28: 11 Catatan (Row 28)
        // ==========================================
        $catatan_sesi = (isset($sesi['catatan']) && $sesi['catatan'] !== '') ? $sesi['catatan'] : ((isset($sesi['catatan_pengujian']) && $sesi['catatan_pengujian'] !== '') ? $sesi['catatan_pengujian'] : '-');
        $this->SetFont('Arial', '', 9);
        $this->Cell(7, 6, '11', 1, 0, 'C');
        $this->Cell(75, 6, 'Catatan', 1, 0, 'L');
        $this->SetFont('Arial', '', 8.5);
        $this->Cell(108, 6, ' ' . $catatan_sesi, 1, 1, 'L');

        // ==========================================
        // BARIS 29: 12 Sisa Sampel (Row 29)
        // ==========================================
        $sisa_smp = (isset($sampel['jumlah_arsip']) && $sampel['jumlah_arsip'] !== '') ? $sampel['jumlah_arsip'] : '-';
        $this->SetFont('Arial', '', 9);
        $this->Cell(7, 6, '12', 1, 0, 'C');
        $this->Cell(75, 6, 'Sisa Sampel', 1, 0, 'L');
        $this->SetFont('Arial', '', 8.5);
        $this->Cell(108, 6, ' ' . $sisa_smp, 1, 1, 'L');

        // ==========================================
        // BARIS 30: 13 Tanggal dilaporkan penguji | 14 Catatan Pengujian diperiksa
        // ==========================================
        $this->SetFont('Arial', '', 9);
        $this->Cell(7, 6, '13', 1, 0, 'C');
        $this->Cell(75, 6, 'Tanggal dilaporkan penguji', 1, 0, 'L');
        $this->Cell(7, 6, '14', 1, 0, 'C');
        $this->Cell(101, 6, 'Catatan Pengujian diperiksa', 1, 1, 'L');

        // BARIS 31: Nilai Tanggal lapor & tanggal verifikasi
        $tgl_lapor = !empty($sesi['waktu_selesai']) ? date('d F Y', strtotime($sesi['waktu_selesai'])) : '-';
        $tgl_periksa = !empty($sesi['waktu_verifikasi']) ? date('d F Y', strtotime($sesi['waktu_verifikasi'])) : '-';

        $this->SetFont('Arial', '', 8.5);
        $this->Cell(82, 6.5, ' ' . $tgl_lapor, 1, 0, 'L');
        $this->Cell(108, 6.5, ' ' . $tgl_periksa, 1, 1, 'C');

        $this->Ln(3);

        // ==========================================
        // AREA TANDA TANGAN (Penguji & Penyelia Kimia)
        // Persis sesuai Workbook Rows 34-37 & Standar CP BBPOM
        // ==========================================
        $this->SetFont('Arial', '', 9);
        $this->Cell(105, 5, '', 0, 0);
        $this->Cell(85, 5, 'BALAI BESAR POM DI BANDAR LAMPUNG', 0, 1, 'C');

        $this->Cell(10, 5, '', 0, 0);
        $this->Cell(70, 5, 'Penguji', 0, 0, 'C');
        $this->Cell(25, 5, '', 0, 0);
        $this->Cell(85, 5, 'Penyelia Kimia', 0, 1, 'C');

        // Area Gambar TTD
        $ci =& get_instance();
        $pid = !empty($penguji['id']) ? $penguji['id'] : (!empty($sesi['penguji_id']) ? $sesi['penguji_id'] : NULL);
        if (!$pid) {
            $u3 = $ci->db->get_where('users', ['role_id' => 3, 'status' => 'Aktif'])->row_array();
            $pid = $u3['id'] ?? null;
            if ($u3 && empty($penguji)) $penguji = $u3;
        }
        $vid = !empty($verifier['id']) ? $verifier['id'] : (!empty($sesi['verifier_id']) ? $sesi['verifier_id'] : NULL);
        if (!$vid) {
            $u4 = $ci->db->get_where('users', ['role_id' => 4, 'status' => 'Aktif'])->row_array();
            $vid = $u4['id'] ?? null;
            if ($u4 && empty($verifier)) $verifier = $u4;
        }

        $sig_p = $pid ? $this->getUserSignature($pid) : NULL;
        $sig_v = $vid ? $this->getUserSignature($vid) : NULL;

        $y_ttd = $this->GetY() + 1;
        $ttd_w = 32;
        $ttd_h = 15;

        // Kolom Penguji
        if ($sig_p && file_exists($sig_p)) {
            $this->Image($sig_p, 29, $y_ttd, $ttd_w, $ttd_h);
        } else {
            $this->SetTextColor(140, 140, 140);
            $this->SetFont('Arial', '', 7);
            $this->SetXY(10, $y_ttd + 5);
            $this->Cell(70, 4, '(Tanda tangan belum diunggah)', 0, 0, 'C');
        }

        // Kolom Penyelia Kimia
        if ($sig_v && file_exists($sig_v)) {
            $this->Image($sig_v, 131, $y_ttd, $ttd_w, $ttd_h);
        } else {
            $this->SetTextColor(140, 140, 140);
            $this->SetFont('Arial', '', 7);
            $this->SetXY(105, $y_ttd + 5);
            $this->Cell(85, 4, '(Tanda tangan belum diunggah)', 0, 0, 'C');
        }

        $this->SetTextColor(0, 0, 0);
        $this->SetY($y_ttd + $ttd_h + 1);

        $nama_p = (isset($penguji['nama_lengkap']) && $penguji['nama_lengkap'] !== '') ? $penguji['nama_lengkap'] : ((isset($sesi['nama_penguji']) && $sesi['nama_penguji'] !== '') ? $sesi['nama_penguji'] : '...................................');
        $nip_p  = (isset($penguji['nip']) && $penguji['nip'] !== '') ? 'NIP. ' . $penguji['nip'] : 'NIP. .....................';

        $nama_v = (isset($verifier['nama_lengkap']) && $verifier['nama_lengkap'] !== '') ? $verifier['nama_lengkap'] : ((isset($sesi['nama_verifier']) && $sesi['nama_verifier'] !== '') ? $sesi['nama_verifier'] : '...................................');
        $nip_v  = (isset($verifier['nip']) && $verifier['nip'] !== '') ? 'NIP. ' . $verifier['nip'] : 'NIP. .................................';

        // Garis Nama
        $this->SetFont('Arial', '', 9);
        $this->Cell(10, 4.5, '', 0, 0);
        $this->Cell(70, 4.5, $nama_p, 0, 0, 'C');
        $this->Cell(25, 4.5, '', 0, 0);
        $this->Cell(85, 4.5, $nama_v, 0, 1, 'C');

        // NIP
        $this->Cell(10, 4, '', 0, 0);
        $this->Cell(70, 4, $nip_p, 0, 0, 'C');
        $this->Cell(25, 4, '', 0, 0);
        $this->Cell(85, 4, $nip_v, 0, 1, 'C');

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
