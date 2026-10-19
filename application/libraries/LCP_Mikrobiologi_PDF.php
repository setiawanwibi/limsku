<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'libraries/FPDF.php';

/**
 * Class LCP_Mikrobiologi_PDF
 * 
 * Generator PDF Lembar Catatan Pengujian (LCP) Laboratorium Mikrobiologi LIMSKU BBPOM di Bandar Lampung.
 * Menghasilkan PDF LCP resmi yang mengadaptasi desain spesifik masing-masing form LCP (seperti Cemaran Mikroba Kualitatif Sediaan Farmasi, dsb),
 * mencakup identitas sampel, instrumen alat & reagen yang digunakan, tabel matriks pengamatan, pustaka acuan, serta tanda tangan penguji & penyelia.
 */
class LCP_Mikrobiologi_PDF extends FPDF
{
    protected $instansi = 'BALAI BESAR PENGAWAS OBAT DAN MAKANAN DI BANDAR LAMPUNG';
    protected $sub_instansi = 'Laboratorium Pengujian Obat dan Makanan';
    protected $alamat = 'Jl. Way Pengubuan No. 3 Pahoman, Bandar Lampung';

    public function Header()
    {
        // Header dokumen diatur per halaman
    }

    public function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', '', 7.5);
        $this->SetTextColor(80, 80, 80);
        $this->Cell(100, 10, 'LIMSKU - Lembar Catatan Pengujian (LCP)', 0, 0, 'L');
        $this->Cell(0, 10, 'Halaman ' . $this->PageNo(), 0, 0, 'R');
        $this->SetTextColor(0, 0, 0);
    }

    /**
     * Helper validasi tanda tangan digital user dari direktori aman
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
        $rel_path = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $sig['signature_file']);
        $full_path = $base_dir . $rel_path;

        $allowed_dir = realpath($base_dir . 'uploads' . DIRECTORY_SEPARATOR . 'signatures');
        $real_path = realpath($full_path);

        if ($real_path === false || $allowed_dir === false || strpos($real_path, $allowed_dir) !== 0) {
            return [false, null, 'Path tanda tangan tidak valid atau berada di luar direktori aman.'];
        }

        if (!is_file($real_path) || !is_readable($real_path)) {
            return [false, null, 'File tanda tangan tidak dapat dibaca di disk server.'];
        }

        $img_info = @getimagesize($real_path);
        if ($img_info === false) {
            return [false, null, 'File tanda tangan rusak atau bukan file gambar yang valid.'];
        }

        if (!in_array($img_info[2], [IMAGETYPE_PNG, IMAGETYPE_JPEG], true)) {
            return [false, null, 'Format file tanda tangan tidak didukung (hanya PNG atau JPG).'];
        }

        return [true, $real_path, null];
    }

    /**
     * Buat Dokumen LCP PDF
     *
     * @param array $test_result Data baris test_results
     * @param array $sampel Data sampel
     * @param array $sesi Data testing_session
     * @param array $penguji Data user penguji
     * @param array|null $verifier Data user verifier/penyelia
     * @param array $alat_list Daftar seluruh alat (untuk lookup nama/merk)
     * @param array $reagen_list Daftar seluruh reagen (untuk lookup nama/merk)
     * @param string|null $save_path Path penyimpanan file di server
     * @return string
     */
    public function buat_lcp($test_result, $sampel, $sesi, $penguji, $verifier = null, $alat_list = array(), $reagen_list = array(), $save_path = null)
    {
        $this->AddPage('P', 'A4');
        $this->SetMargins(10, 10, 10);
        $this->SetAutoPageBreak(true, 15);

        // Dekode schema form dan data hasil
        $schema = !empty($test_result['skema_form']) ? json_decode($test_result['skema_form'], true) : array();
        $dh = !empty($test_result['data_hasil']) ? json_decode($test_result['data_hasil'], true) : array();
        $pv = isset($dh['parameter_values']) && is_array($dh['parameter_values']) ? $dh['parameter_values'] : $dh;

        $alat_ids = isset($dh['alat_ids']) && is_array($dh['alat_ids']) ? $dh['alat_ids'] : array();
        $reagen_ids = isset($dh['reagen_ids']) && is_array($dh['reagen_ids']) ? $dh['reagen_ids'] : array();

        // Siapkan lookup map alat & reagen
        $alat_map = array();
        foreach ($alat_list as $a) {
            $alat_map[$a['id']] = $a;
        }
        $reagen_map = array();
        foreach ($reagen_list as $r) {
            $reagen_map[$r['id']] = $r;
        }

        // ==========================================
        // 1. KOP RESMI LEMBAR CATATAN PENGUJIAN
        // ==========================================
        $this->SetFont('Arial', 'B', 9);
        $this->Cell(190, 5, 'LAMPIRAN CATATAN PENGUJIAN', 0, 1, 'C');

        $nama_template = !empty($test_result['nama_template']) ? strtoupper($test_result['nama_template']) : 'CATATAN PENGUJIAN MIKROBIOLOGI';
        $this->SetFont('Arial', 'B', 11);
        $this->Cell(190, 6, $nama_template, 0, 1, 'C');

        $kode_template = !empty($test_result['kode_template']) ? $test_result['kode_template'] : (!empty($test_result['kode_metode']) ? $test_result['kode_metode'] : '-');
        $this->SetFont('Arial', '', 8);
        $this->Cell(190, 4.5, 'Formulir: ' . $kode_template . ' | Laboratorium Mikrobiologi Balai Besar POM di Bandar Lampung', 0, 1, 'C');

        $this->SetLineWidth(0.3);
        $this->Line(10, $this->GetY() + 2, 200, $this->GetY() + 2);
        $this->Ln(4);

        // ==========================================
        // 2. KOTAK INFORMASI IDENTITAS SAMPEL & PENGUJIAN (GRID BOX)
        // ==========================================
        $kode_smp = !empty($sampel['kode_sampel_manual']) 
            ? $sampel['kode_sampel_manual'] 
            : (!empty($sampel['no']) ? 'NO-' . $sampel['no'] : (!empty($sampel['id']) ? 'SMP-' . $sampel['id'] : '-'));

        $nama_smp = !empty($sampel['nama_sampel']) ? $sampel['nama_sampel'] : '-';
        $tgl_terima = !empty($sampel['tanggal_sampling']) ? date('d/m/Y', strtotime($sampel['tanggal_sampling'])) : (!empty($sampel['created_at']) ? date('d/m/Y', strtotime($sampel['created_at'])) : '-');
        $tgl_mulai = !empty($sesi['waktu_mulai']) ? date('d/m/Y', strtotime($sesi['waktu_mulai'])) : '-';

        $nama_penguji = !empty($penguji['nama_lengkap']) ? $penguji['nama_lengkap'] : (!empty($sesi['nama_penguji']) ? $sesi['nama_penguji'] : '-');
        $parameter_uji = !empty($test_result['parameter_uji']) ? $test_result['parameter_uji'] : (!empty($test_result['nama_metode']) ? $test_result['nama_metode'] : '-');

        $this->SetFont('Arial', '', 8.5);
        
        // Baris 1: Nama Sampel & Kode Sampel
        $this->Cell(32, 6, ' Nama Sampel', 1, 0, 'L');
        $this->Cell(68, 6, ' ' . substr($nama_smp, 0, 40), 1, 0, 'L');
        $this->Cell(32, 6, ' Kode Sampel', 1, 0, 'L');
        $this->Cell(58, 6, ' ' . $kode_smp, 1, 1, 'L');

        // Baris 2: Parameter Uji & Tanggal Diterima
        $this->Cell(32, 6, ' Parameter Uji', 1, 0, 'L');
        $this->Cell(68, 6, ' ' . substr($parameter_uji, 0, 40), 1, 0, 'L');
        $this->Cell(32, 6, ' Tanggal Diterima', 1, 0, 'L');
        $this->Cell(58, 6, ' ' . $tgl_terima, 1, 1, 'L');

        // Baris 3: Penguji & Tanggal Mulai Uji
        $this->Cell(32, 6, ' Penguji', 1, 0, 'L');
        $this->Cell(68, 6, ' ' . substr($nama_penguji, 0, 40), 1, 0, 'L');
        $this->Cell(32, 6, ' Tanggal Mulai Uji', 1, 0, 'L');
        $this->Cell(58, 6, ' ' . $tgl_mulai, 1, 1, 'L');

        $this->Ln(3);

        // ==========================================
        // 3. DAFTAR ALAT & REAGEN YANG DIGUNAKAN
        // ==========================================
        if (!empty($alat_ids) || !empty($reagen_ids)) {
            $this->SetFont('Arial', 'B', 8.5);
            $this->Cell(190, 5.5, 'Instrumen Alat & Reagen yang Digunakan', 1, 1, 'L');

            // Baris Alat
            $alat_str = '-';
            if (!empty($alat_ids)) {
                $alat_names = array();
                foreach ($alat_ids as $aid) {
                    if (isset($alat_map[$aid])) {
                        $al = $alat_map[$aid];
                        $alat_names[] = '[' . $al['kode_barang'] . '] ' . $al['nama_barang'] . (!empty($al['merk_tipe']) ? ' (' . $al['merk_tipe'] . ')' : '');
                    }
                }
                if (!empty($alat_names)) {
                    $alat_str = implode(', ', $alat_names);
                }
            }

            // Baris Reagen
            $reagen_str = '-';
            if (!empty($reagen_ids)) {
                $reagen_names = array();
                foreach ($reagen_ids as $rid) {
                    if (isset($reagen_map[$rid])) {
                        $rg = $reagen_map[$rid];
                        $rg_nama = !empty($rg['nama_barang']) ? $rg['nama_barang'] : $rg['nama_reagen'];
                        $reagen_names[] = $rg_nama . (!empty($rg['merk']) ? ' (' . $rg['merk'] . ')' : '');
                    }
                }
                if (!empty($reagen_names)) {
                    $reagen_str = implode(', ', $reagen_names);
                }
            }

            $this->SetFont('Arial', '', 8);
            $this->Cell(32, 5.5, ' Alat Laboratorium', 1, 0, 'L');
            $this->Cell(158, 5.5, ' ' . substr($alat_str, 0, 95), 1, 1, 'L');

            $this->Cell(32, 5.5, ' Reagen / Media', 1, 0, 'L');
            $this->Cell(158, 5.5, ' ' . substr($reagen_str, 0, 95), 1, 1, 'L');

            $this->Ln(3);
        }

        // ==========================================
        // 4. KONTEN FORM SPESIFIK BERDASARKAN SKEMA FORM LCP
        // ==========================================
        if (!empty($schema['sections']) && is_array($schema['sections'])) {
            foreach ($schema['sections'] as $sec) {
                // Lewati seksi static pengesahan di sini karena akan dirender khusus di bagian footer
                if (isset($sec['title']) && stripos($sec['title'], 'Pengesahan') !== false) {
                    continue;
                }

                // Cek perpindahan halaman
                if ($this->GetY() > 245) {
                    $this->AddPage('P', 'A4');
                }

                $sec_title = isset($sec['title']) ? $sec['title'] : 'Bagian Form';
                $this->SetFont('Arial', 'B', 8.5);
                $this->Cell(190, 6, $sec_title, 1, 1, 'L');

                if (isset($sec['type']) && $sec['type'] === 'reference_guide' && !empty($sec['items'])) {
                    // Panduan acuan visual
                    $this->SetFont('Arial', 'B', 7.5);
                    $this->Cell(60, 5, 'Mikroba Target', 1, 0, 'C');
                    $this->Cell(130, 5, 'Media & Kriteria Karakteristik Koloni', 1, 1, 'C');

                    $this->SetFont('Arial', '', 7.5);
                    foreach ($sec['items'] as $ref) {
                        $m_name = isset($ref['mikroba']) ? $ref['mikroba'] : '-';
                        $krit_arr = array();
                        if (isset($ref['kriteria']) && is_array($ref['kriteria'])) {
                            foreach ($ref['kriteria'] as $med => $krit) {
                                $krit_arr[] = $med . ': ' . $krit;
                            }
                        }
                        $krit_str = implode('; ', $krit_arr);

                        $this->Cell(60, 5, ' ' . $m_name, 1, 0, 'L');
                        $this->Cell(130, 5, ' ' . substr($krit_str, 0, 78), 1, 1, 'L');
                    }
                } elseif (!empty($sec['fields']) && is_array($sec['fields'])) {
                    foreach ($sec['fields'] as $fld) {
                        $fld_id = isset($fld['id']) ? $fld['id'] : '';
                        $fld_label = isset($fld['label']) ? $fld['label'] : ucwords(str_replace('_', ' ', $fld_id));
                        $fld_type = isset($fld['type']) ? $fld['type'] : 'text';

                        $val = isset($pv[$fld_id]) ? $pv[$fld_id] : (isset($dh[$fld_id]) ? $dh[$fld_id] : '');

                        if ($fld_type === 'table') {
                            // Subtabel pengamatan (misal tabel_tahapan)
                            $cols = isset($fld['columns']) ? $fld['columns'] : array();
                            $rows = is_array($val) ? $val : (isset($fld['default_rows']) ? $fld['default_rows'] : array());

                            if (!empty($cols)) {
                                $col_width = floor(190 / count($cols));
                                $last_width = 190 - ($col_width * (count($cols) - 1));

                                $this->SetFont('Arial', 'B', 7.5);
                                foreach ($cols as $c_idx => $col) {
                                    $w = ($c_idx === count($cols) - 1) ? $last_width : $col_width;
                                    $this->Cell($w, 5.5, $col['label'], 1, 0, 'C');
                                }
                                $this->Ln(5.5);

                                $this->SetFont('Arial', '', 7.5);
                                foreach ($rows as $r) {
                                    foreach ($cols as $c_idx => $col) {
                                        $w = ($c_idx === count($cols) - 1) ? $last_width : $col_width;
                                        $col_key = isset($col['id']) ? $col['id'] : (isset($col['key']) ? $col['key'] : '');
                                        $cell_v = ($col_key !== '' && isset($r[$col_key])) ? (string)$r[$col_key] : '-';
                                        if ($cell_v === '') $cell_v = '-';
                                        $this->Cell($w, 5, ' ' . substr($cell_v, 0, 22), 1, 0, 'L');
                                    }
                                    $this->Ln(5);
                                }
                            }
                        } elseif ($fld_type === 'checkbox') {
                            $check_symbol = (!empty($val) && $val != '0') ? '[X]' : '[ ]';
                            $this->SetFont('Arial', '', 8);
                            $this->Cell(12, 5, $check_symbol, 1, 0, 'C');
                            $this->Cell(178, 5, ' ' . $fld_label, 1, 1, 'L');
                        } else {
                            // Text, select, textarea biasa
                            $val_str = is_array($val) ? json_encode($val) : (string)$val;
                            if ($val_str === '') $val_str = '-';

                            $this->SetFont('Arial', '', 8);
                            $this->Cell(65, 5.5, ' ' . $fld_label, 1, 0, 'L');
                            $this->Cell(125, 5.5, ' ' . substr($val_str, 0, 75), 1, 1, 'L');
                        }
                    }
                }
                $this->Ln(2);
            }
        } else {
            // Fallback generic parameter rendering jika belum ada sections
            $this->SetFont('Arial', 'B', 8.5);
            $this->Cell(190, 6, 'Hasil Pengamatan & Parameter Uji', 1, 1, 'L');

            $this->SetFont('Arial', '', 8);
            if (!empty($pv) && is_array($pv)) {
                foreach ($pv as $k => $v) {
                    if (is_array($v)) continue;
                    $lbl = ucwords(str_replace('_', ' ', $k));
                    $this->Cell(65, 5.5, ' ' . $lbl, 1, 0, 'L');
                    $this->Cell(125, 5.5, ' ' . substr((string)$v, 0, 75), 1, 1, 'L');
                }
            } else {
                $this->Cell(190, 6, ' Belum ada data pengamatan tercatat.', 1, 1, 'L');
            }
            $this->Ln(2);
        }

        // ==========================================
        // 5. KESIMPULAN & SPESIFIKASI MUTU
        // ==========================================
        if ($this->GetY() > 230) {
            $this->AddPage('P', 'A4');
        }

        $kesimpulan = !empty($test_result['kesimpulan']) ? $test_result['kesimpulan'] : (isset($pv['kesimpulan_lcp']) ? $pv['kesimpulan_lcp'] : 'Belum Disimpulkan');
        $syarat = !empty($test_result['persyaratan']) ? $test_result['persyaratan'] : (isset($pv['syarat']) ? $pv['syarat'] : '-');
        $pustaka = !empty($test_result['pustaka_acuan']) ? $test_result['pustaka_acuan'] : (isset($pv['pustaka_metode']) ? $pv['pustaka_metode'] : '-');

        $this->SetFont('Arial', 'B', 8.5);
        $this->Cell(190, 6, 'Kesimpulan & Evaluasi Mutu Parameter', 1, 1, 'L');

        $this->SetFont('Arial', '', 8);
        $this->Cell(35, 5.5, ' Persyaratan Mutu', 1, 0, 'L');
        $this->Cell(155, 5.5, ' ' . $syarat, 1, 1, 'L');

        $this->Cell(35, 5.5, ' Pustaka Acuan', 1, 0, 'L');
        $this->Cell(155, 5.5, ' ' . $pustaka, 1, 1, 'L');

        $this->SetFont('Arial', 'B', 8.5);
        $this->Cell(35, 6, ' Kesimpulan Akhir', 1, 0, 'L');
        $this->Cell(155, 6, ' ' . $kesimpulan, 1, 1, 'L');

        $this->Ln(4);

        // ==========================================
        // 6. BLOK PENGESAHAN & TANDA TANGAN DIGITAL
        // ==========================================
        if ($this->GetY() > 225) {
            $this->AddPage('P', 'A4');
        }

        $val_p = $this->validateUserSignature($sesi['penguji_id']);
        $val_v = !empty($sesi['verifier_id']) ? $this->validateUserSignature($sesi['verifier_id']) : [false, null, null];

        $sig_p = $val_p[0] ? $val_p[1] : null;
        $sig_v = $val_v[0] ? $val_v[1] : null;

        $this->SetFont('Arial', '', 8.5);
        $this->Cell(95, 4.5, '', 0, 0, 'C');
        $this->Cell(95, 4.5, 'BALAI BESAR POM DI BANDAR LAMPUNG', 0, 1, 'C');

        $this->Cell(95, 4.5, 'Penguji', 0, 0, 'C');
        $this->Cell(95, 4.5, 'Penyelia Mikro', 0, 1, 'C');

        $y_ttd = $this->GetY() + 1;
        $ttd_w = 30;
        $ttd_h = 14;

        // Render TTD Penguji
        if ($sig_p && file_exists($sig_p)) {
            $this->Image($sig_p, 42, $y_ttd, $ttd_w, $ttd_h);
        } else {
            $this->SetFont('Arial', '', 7);
            $this->SetTextColor(140, 140, 140);
            $this->SetXY(10, $y_ttd + 4);
            $this->Cell(95, 4, '(Tanda tangan belum diunggah)', 0, 0, 'C');
            $this->SetTextColor(0, 0, 0);
        }

        // Render TTD Penyelia
        if ($sig_v && file_exists($sig_v)) {
            $this->Image($sig_v, 137, $y_ttd, $ttd_w, $ttd_h);
        } else {
            $this->SetFont('Arial', '', 7);
            $this->SetTextColor(140, 140, 140);
            $this->SetXY(105, $y_ttd + 4);
            $this->Cell(95, 4, '(Tanda tangan belum diunggah)', 0, 0, 'C');
            $this->SetTextColor(0, 0, 0);
        }

        $this->SetY($y_ttd + $ttd_h + 1);

        $nama_p = !empty($penguji['nama_lengkap']) ? $penguji['nama_lengkap'] : (!empty($sesi['nama_penguji']) ? $sesi['nama_penguji'] : '...................................');
        $nip_p  = !empty($penguji['nip']) ? 'NIP. ' . $penguji['nip'] : 'NIP. .....................';

        $nama_v = !empty($verifier['nama_lengkap']) ? $verifier['nama_lengkap'] : (!empty($sesi['nama_verifier']) ? $sesi['nama_verifier'] : '...................................');
        $nip_v  = !empty($verifier['nip']) ? 'NIP. ' . $verifier['nip'] : 'NIP. .................................';

        $this->SetFont('Arial', '', 8.5);
        $this->Cell(95, 4.5, $nama_p, 0, 0, 'C');
        $this->Cell(95, 4.5, $nama_v, 0, 1, 'C');

        $this->Cell(95, 4, $nip_p, 0, 0, 'C');
        $this->Cell(95, 4, $nip_v, 0, 1, 'C');

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
