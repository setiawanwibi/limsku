<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'libraries/FPDF.php';

/**
 * Class CP_Mikrobiologi_PDF
 * 
 * Generator PDF Catatan Pengujian (CP) Laboratorium Mikrobiologi LIMSKU.
 * Dibuat sesuai dengan struktur resmi workbook Excel "CP mikro.xlsx" (Sheet CP-SIPT) BBPOM di Bandar Lampung.
 */
class CP_Mikrobiologi_PDF extends FPDF
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
        $this->Cell(100, 10, 'LIMSKU - Catatan Pengujian (CP) Mikrobiologi', 0, 0, 'L');
        $this->Cell(0, 10, 'Halaman ' . $this->PageNo(), 0, 0, 'R');
    }

    /**
     * Memeriksa dan memvalidasi keabsahan file signature
     * Memastikan file ada, berada di direktori aman yang diizinkan (uploads/signatures/),
     * dan merupakan gambar PNG atau JPEG yang valid dan dapat dibaca.
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
        
        // Bersihkan path untuk mencegah traversal directory
        $clean_rel = str_replace(array('../', '..\\'), '', $sig['signature_file']);
        $full_path = $base_dir . $clean_rel;

        // Validasi direktori yang diizinkan (uploads/signatures/)
        $real_path = realpath($full_path);
        $allowed_dir = realpath($base_dir . 'uploads/signatures');
        
        if (!$real_path || !$allowed_dir || strpos($real_path, $allowed_dir) !== 0) {
            return [false, null, 'Lokasi berkas tanda tangan tidak sah atau di luar direktori aman.'];
        }

        if (!is_file($real_path) || !is_readable($real_path)) {
            return [false, null, 'Berkas tanda tangan digital tidak dapat dibaca di server.'];
        }

        // Validasi format gambar nyata melalui getimagesize
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
     * Buat Dokumen CP Mikrobiologi PDF
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
        // JUDUL DOKUMEN (Row 2 Excel, B2:K2 Merged, Border Box, Bold 11pt)
        // ==========================================
        $this->SetFont('Arial', 'B', 11);
        $this->Cell(190, 7.5, 'CATATAN PENGUJIAN', 1, 1, 'C');

        // ==========================================
        // BARIS 3: 1 Nama Sampel | 2 Kode Sampel | 3 Segel (Tinggi 6.5 mm)
        // Kolom B: 7 mm, C-E: 75 mm, F: 7 mm, G-H: 56 mm, I: 7 mm, J-K: 38 mm
        // ==========================================
        $this->SetFont('Arial', '', 9);
        $this->Cell(7, 6.5, '1', 1, 0, 'C');
        $this->Cell(75, 6.5, 'Nama Sampel', 1, 0, 'L');
        $this->Cell(7, 6.5, '2', 1, 0, 'C');
        $this->Cell(56, 6.5, 'Kode Sampel', 1, 0, 'L');
        $this->Cell(7, 6.5, '3', 1, 0, 'C');
        $this->Cell(38, 6.5, 'Segel', 1, 1, 'L');

        // ==========================================
        // BARIS 4: Data Nilai Baris 1 (Tinggi 7 mm)
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
        // BARIS 6: Data Nilai Baris 4 & 5 (Mikrobiologi)
        // ==========================================
        $surtug_val = '-';
        if (!empty($sampel['surtug'])) {
            $surtug_val = $sampel['surtug'];
        } elseif (!empty($sampel['tanggal_surat_perintah'])) {
            $surtug_val = date('d F Y', strtotime($sampel['tanggal_surat_perintah']));
        }

        $this->SetFont('Arial', '', 8.5);
        $this->Cell(82, 7, ' ' . substr($surtug_val, 0, 48), 1, 0, 'L');
        $this->Cell(108, 7, ' Mikrobiologi', 1, 1, 'L');

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

        // Nilai Baris 15: Jumlah mikro & Label
        $jml_diterima = (isset($sampel['jumlah_mikro']) && $sampel['jumlah_mikro'] !== '') 
            ? $sampel['jumlah_mikro'] 
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
        if (!empty($sampel['pemerian']) && is_string($sampel['pemerian'])) {
            $pemerian['Bentuk'] = substr($sampel['pemerian'], 0, 30);
        }
        foreach ($forms as $f) {
            $dh = !empty($f['data_hasil']) ? json_decode($f['data_hasil'], true) : array();
            $pv = isset($dh['parameter_values']) && is_array($dh['parameter_values']) ? $dh['parameter_values'] : $dh;
            foreach ($pemerian as $pk => $pv_def) {
                $low = strtolower($pk);
                if (isset($pv[$low]) && $pv[$low] !== '') {
                    $pemerian[$pk] = is_array($pv[$low]) ? json_encode($pv[$low]) : (string)$pv[$low];
                }
            }
        }

        $this->SetFont('Arial', '', 8.5);
        $this->Cell(45, 6, ' Bentuk: ' . $pemerian['Bentuk'], 1, 0, 'L');
        $this->Cell(45, 6, ' Warna: ' . $pemerian['Warna'], 1, 0, 'L');
        $this->Cell(50, 6, ' Bau : ' . $pemerian['Bau'], 1, 0, 'L');
        $this->Cell(50, 6, ' Rasa: ' . $pemerian['Rasa'], 1, 1, 'L');

        // ==========================================
        // BARIS 19: Tabel Uji yang dilakukan, Hasil, Syarat, Pustaka, Metode
        // Kolom B-C: 45 mm, D-E: 38 mm, F-G: 42 mm, H-J: 45 mm, K: 20 mm
        // ==========================================
        $print_table_header = function() {
            $this->SetFont('Arial', 'B', 8.5);
            $this->Cell(45, 6, 'Uji yang dilakukan', 1, 0, 'C');
            $this->Cell(38, 6, 'Hasil', 1, 0, 'C');
            $this->Cell(42, 6, 'Syarat', 1, 0, 'C');
            $this->Cell(45, 6, 'Pustaka', 1, 0, 'C');
            $this->Cell(20, 6, 'Metode', 1, 1, 'C');
        };

        $print_table_header();

        // Isi Tabel Pengujian dari LCP
        $this->SetFont('Arial', '', 8);
        $row_count = 0;
        if (!empty($forms)) {
            foreach ($forms as $f) {
                if ($this->GetY() > 255) {
                    $this->AddPage('P', 'A4');
                    $print_table_header();
                    $this->SetFont('Arial', '', 8);
                }

                $dh = !empty($f['data_hasil']) ? json_decode($f['data_hasil'], true) : array();
                $pv = isset($dh['parameter_values']) && is_array($dh['parameter_values']) ? $dh['parameter_values'] : $dh;

                $hasil_str = '';
                if (!empty($f['kesimpulan']) && $f['kesimpulan'] !== 'Belum Disimpulkan') {
                    $hasil_str = $f['kesimpulan'];
                }
                $hasil_items = array();
                foreach ($pv as $k => $v) {
                    if (in_array($k, array('alat_ids', 'reagen_ids', 'parameter_values'))) continue;
                    $val_str = is_array($v) ? json_encode($v) : (string)$v;
                    if ($val_str !== '') {
                        $hasil_items[] = ucwords(str_replace('_', ' ', $k)) . ': ' . $val_str;
                    }
                    if (count($hasil_items) >= 2) break;
                }
                if (!empty($hasil_items)) {
                    $hasil_str = implode('; ', $hasil_items) . (!empty($hasil_str) ? ' (' . $hasil_str . ')' : '');
                }
                if (empty($hasil_str)) {
                    $hasil_str = '-';
                }

                $nama_uji = !empty($f['parameter_uji']) ? $f['parameter_uji'] : (!empty($f['nama_template']) ? $f['nama_template'] : '-');
                $syarat = !empty($f['persyaratan']) ? $f['persyaratan'] : (!empty($f['persyaratan_monografi']) ? $f['persyaratan_monografi'] : '-');
                $pustaka = !empty($f['pustaka_acuan']) ? $f['pustaka_acuan'] : '-';
                $metode_kd = !empty($f['metode_analisa']) ? $f['metode_analisa'] : (!empty($f['nama_metode']) ? $f['nama_metode'] : (!empty($f['kode_template']) ? $f['kode_template'] : '-'));

                // Baris uji
                $this->Cell(45, 6, ' ' . substr($nama_uji, 0, 26), 1, 0, 'L');
                $this->Cell(38, 6, ' ' . substr($hasil_str, 0, 24), 1, 0, 'L');
                $this->Cell(42, 6, ' ' . substr($syarat, 0, 26), 1, 0, 'L');
                $this->Cell(45, 6, ' ' . substr($pustaka, 0, 28), 1, 0, 'L');
                $this->Cell(20, 6, ' ' . substr($metode_kd, 0, 12), 1, 1, 'C');
                $row_count++;
            }
        }

        // Pertahankan minimal 5 baris grid seperti workbook template (Rows 20-26)
        while ($row_count < 5) {
            $this->Cell(45, 6, '', 1, 0, 'L');
            $this->Cell(38, 6, '', 1, 0, 'L');
            $this->Cell(42, 6, '', 1, 0, 'L');
            $this->Cell(45, 6, '', 1, 0, 'L');
            $this->Cell(20, 6, '', 1, 1, 'C');
            $row_count++;
        }

        if ($this->GetY() > 225) {
            $this->AddPage('P', 'A4');
        }

        // ==========================================
        // BARIS 27: 10 Kesimpulan (Row 27 Excel)
        // ==========================================
        $kesimpulan_sesi = !empty($sesi['kesimpulan']) ? $sesi['kesimpulan'] : '-';
        $this->SetFont('Arial', '', 9);
        $this->Cell(7, 6, '10', 1, 0, 'C');
        $this->Cell(75, 6, 'Kesimpulan', 1, 0, 'L');
        $this->SetFont('Arial', 'B', 8.5);
        $this->Cell(108, 6, ' ' . $kesimpulan_sesi, 1, 1, 'L');

        // ==========================================
        // BARIS 28: 11 Catatan (Row 28 Excel)
        // ==========================================
        $catatan_sesi = (isset($sesi['catatan']) && $sesi['catatan'] !== '') ? $sesi['catatan'] : ((isset($sesi['catatan_pengujian']) && $sesi['catatan_pengujian'] !== '') ? $sesi['catatan_pengujian'] : '-');
        $this->SetFont('Arial', '', 9);
        $this->Cell(7, 6, '11', 1, 0, 'C');
        $this->Cell(75, 6, 'Catatan', 1, 0, 'L');
        $this->SetFont('Arial', '', 8.5);
        $this->Cell(108, 6, ' ' . $catatan_sesi, 1, 1, 'L');

        // ==========================================
        // BARIS 29: 12 Sisa Sampel (Row 29 Excel)
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

        // BARIS 31: Nilai Tanggal lapor & catatan periksa (Row 31 Excel: [F] = "-")
        $tgl_lapor = !empty($sesi['waktu_selesai']) ? date('d F Y', strtotime($sesi['waktu_selesai'])) : '-';
        $tgl_periksa = !empty($sesi['waktu_verifikasi']) ? date('d F Y', strtotime($sesi['waktu_verifikasi'])) : '-';

        $this->SetFont('Arial', '', 8.5);
        $this->Cell(82, 6.5, ' ' . $tgl_lapor, 1, 0, 'L');
        $this->Cell(108, 6.5, ' ' . $tgl_periksa, 1, 1, 'C');

        $this->Ln(3);

        // ==========================================
        // AREA TANDA TANGAN (Penguji & Penyelia Mikro)
        // Persis sesuai Workbook Rows 34-37 & Foto Screenshot
        // ==========================================
        $this->SetFont('Arial', '', 9);
        $this->Cell(105, 5, '', 0, 0);
        $this->Cell(85, 5, 'BALAI BESAR POM DI BANDAR LAMPUNG', 0, 1, 'C');

        $this->Cell(10, 5, '', 0, 0);
        $this->Cell(70, 5, 'Penguji', 0, 0, 'C');
        $this->Cell(25, 5, '', 0, 0);
        $this->Cell(85, 5, 'Penyelia Mikro', 0, 1, 'C');

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

        // Kolom Penyelia Mikro
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

