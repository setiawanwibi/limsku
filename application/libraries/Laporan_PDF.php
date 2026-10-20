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
     * Memeriksa dan memvalidasi keabsahan file digital signature.
     * Memastikan file ada, berada di direktori aman yang diizinkan (uploads/signatures/),
     * dan merupakan gambar PNG atau JPEG yang valid serta dapat dibaca.
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

        $ci = get_instance();
        if (!$ci) {
            return [false, null, 'Instance sistem tidak tersedia.'];
        }
        if (!isset($ci->Model_User_Signature)) {
            $ci->load->model('Model_User_Signature');
        }

        $sig = $ci->Model_User_Signature->ambil_by_user_id($user_id);
        if (!$sig || empty($sig['signature_file'])) {
            return [false, null, 'Tanda tangan digital belum diunggah di profil akun (User ID: ' . $user_id . ').'];
        }

        $base_dir = defined('FCPATH') ? FCPATH : dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR;

        // Bersihkan path untuk mencegah traversal directory
        $clean_rel = str_replace(array('../', '..\\'), '', $sig['signature_file']);
        $full_path = $base_dir . $clean_rel;

        // Validasi direktori yang diizinkan (uploads/signatures/)
        $real_path = realpath($full_path);
        $allowed_dir = realpath($base_dir . 'uploads/signatures');

        if (!$real_path || !$allowed_dir || strpos($real_path, $allowed_dir) !== 0) {
            return [false, null, 'Lokasi berkas tanda tangan tidak sah atau di luar direktori aman (User ID: ' . $user_id . ').'];
        }

        if (!is_file($real_path) || !is_readable($real_path)) {
            return [false, null, 'Berkas tanda tangan digital tidak dapat dibaca di server (User ID: ' . $user_id . ').'];
        }

        // Validasi format gambar nyata melalui getimagesize
        $img_info = @getimagesize($real_path);
        if ($img_info === false) {
            return [false, null, 'Berkas tanda tangan rusak atau bukan file gambar valid (User ID: ' . $user_id . ').'];
        }

        $allowed_types = [IMAGETYPE_PNG, IMAGETYPE_JPEG];
        if (!in_array($img_info[2], $allowed_types, true)) {
            return [false, null, 'Format berkas tanda tangan tidak didukung (wajib PNG atau JPG/JPEG).'];
        }

        return [true, $real_path, null];
    }

    /**
     * Helper Preparation: Mengambil path file TTD Digital aktif berbasis user_id.
     * Mengembalikan absolute path file TTD jika lolos validasi keamanan ketat, atau NULL.
     *
     * @param int $user_id
     * @return string|null
     */
    public function getUserSignature($user_id)
    {
        $val = $this->validateUserSignature($user_id);
        return $val[0] ? $val[1] : NULL;
    }

    /**
     * Helper Cell dengan Auto-Wrapping (MultiCell) untuk baris tabel yang dapat berpindah halaman rapi.
     */
    public function RowTabelLHU($data_cells, $widths, $aligns, $h_line = 5, $fill = false)
    {
        // Hitung tinggi maksimum baris
        $max_lines = 1;
        $total_cols = count($widths);
        for ($i = 0; $i < $total_cols; $i++) {
            $txt = isset($data_cells[$i]) ? (string)$data_cells[$i] : '';
            $w = $widths[$i];
            $lines = $this->NbLines($w, $txt);
            if ($lines > $max_lines) {
                $max_lines = $lines;
            }
        }

        $row_height = $h_line * $max_lines;

        // Cek page break
        if ($this->GetY() + $row_height > $this->PageBreakTrigger) {
            $this->AddPage($this->CurOrientation);
            // Gambar ulang header tabel di halaman baru
            return true; // Menandakan page break terjadi
        }

        $x = $this->GetX();
        $y = $this->GetY();

        for ($i = 0; $i < $total_cols; $i++) {
            $w = $widths[$i];
            $a = isset($aligns[$i]) ? $aligns[$i] : 'L';
            $txt = isset($data_cells[$i]) ? (string)$data_cells[$i] : '';

            // Draw border box
            $this->Rect($x, $y, $w, $row_height, $fill ? 'DF' : 'D');

            // Draw text
            $this->SetXY($x, $y + 0.5);
            $this->MultiCell($w, $h_line, $txt, 0, $a);

            $x += $w;
        }

        $this->SetXY(10, $y + $row_height);
        return false;
    }

    /**
     * Menghitung jumlah baris text untuk lebar kolom tertentu
     */
    public function NbLines($w, $txt)
    {
        $cw =& $this->CurrentFont['cw'];
        if ($w == 0) {
            $w = $this->w - $this->rMargin - $this->x;
        }
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', (string)$txt);
        $nb = strlen($s);
        if ($nb > 0 && $s[$nb - 1] == "\n") {
            $nb--;
        }
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
            if ($c == ' ') {
                $sep = $i;
            }
            $l += isset($cw[$c]) ? $cw[$c] : 500;
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j) {
                        $i++;
                    }
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
     * FASE 4 — Generator Laporan Hasil Uji (LHU) Final Gabungan Tingkat Sampel.
     * Menggabungkan seluruh hasil pengujian dari sesi-sesi yang diwajibkan (Kimia & Mikrobiologi).
     *
     * GATE VALIDASI TANDA TANGAN DIGITAL:
     * - Memeriksa tanda tangan Penguji dan Penyelia dari setiap sesi wajib.
     * - Memeriksa tanda tangan Manajer Teknis (MT).
     * - Jika ada tanda tangan yang hilang/rusak/tidak valid, melempar Exception dan menghentikan proses.
     *
     * @param array $sampel Data sampel dari tabel samples
     * @param array $sesi_list_dengan_forms Daftar sesi wajib beserta 'forms' (test_results)
     * @param array $approver_info Data user MT (id, nama_lengkap, nip)
     * @param string|null $save_path Path penyimpanan file PDF jika disimpan ke disk
     * @param string $mode 'F' untuk simpan file, 'I' untuk inline browser, 'D' untuk download
     * @return string Path file yang berhasil dibuat
     * @throws Exception Jika validasi data atau signature gate gagal
     */
    public function buat_laporan_sampel($sampel, $sesi_list_dengan_forms, $approver_info, $save_path = null, $mode = 'F')
    {
        if (empty($sampel) || empty($sampel['id'])) {
            throw new Exception('Data sampel tidak valid untuk pembuatan LHU gabungan.');
        }

        if (empty($sesi_list_dengan_forms)) {
            throw new Exception('Daftar sesi pengujian kosong. Tidak dapat membuat LHU gabungan.');
        }

        if (empty($approver_info) || empty($approver_info['id'])) {
            throw new Exception('Data Manajer Teknis (Approver) wajib terisi.');
        }

        // =========================================================================
        // 1. GATE KETAT VALIDASI TANDA TANGAN DIGITAL SELURUH PIHAK TERKAIT
        // =========================================================================
        // Validasi MT
        $val_mt = $this->validateUserSignature($approver_info['id']);
        if (!$val_mt[0]) {
            throw new Exception('Tanda Tangan Digital Manajer Teknis (' . ($approver_info['nama_lengkap'] ?: 'MT') . ') tidak valid: ' . $val_mt[2]);
        }
        $sig_mt_path = $val_mt[1];

        // Validasi Penguji dan Penyelia untuk setiap sesi wajib
        $signers_per_sesi = [];
        foreach ($sesi_list_dengan_forms as $sesi) {
            $s_id = $sesi['id'];
            $j_uji = !empty($sesi['jenis_pengujian']) ? $sesi['jenis_pengujian'] : 'Pengujian';

            // Validasi Penguji
            if (empty($sesi['penguji_id'])) {
                throw new Exception("Sesi $j_uji (ID $s_id) tidak memiliki record Penguji yang sah.");
            }
            $val_p = $this->validateUserSignature($sesi['penguji_id']);
            if (!$val_p[0]) {
                $nm_p = !empty($sesi['nama_penguji']) ? $sesi['nama_penguji'] : 'User #' . $sesi['penguji_id'];
                throw new Exception("Tanda Tangan Digital Penguji $nm_p pada Sesi $j_uji tidak valid: " . $val_p[2]);
            }

            // Validasi Penyelia
            if (empty($sesi['verifier_id'])) {
                throw new Exception("Sesi $j_uji (ID $s_id) belum diverifikasi atau tidak memiliki Penyelia yang sah.");
            }
            $val_v = $this->validateUserSignature($sesi['verifier_id']);
            if (!$val_v[0]) {
                $nm_v = !empty($sesi['nama_verifier']) ? $sesi['nama_verifier'] : 'User #' . $sesi['verifier_id'];
                throw new Exception("Tanda Tangan Digital Penyelia $nm_v pada Sesi $j_uji tidak valid: " . $val_v[2]);
            }

            $signers_per_sesi[$s_id] = [
                'sesi'      => $sesi,
                'penguji'   => [
                    'id'   => $sesi['penguji_id'],
                    'nama' => !empty($sesi['nama_penguji']) ? $sesi['nama_penguji'] : '-',
                    'nip'  => !empty($sesi['nip_penguji']) ? $sesi['nip_penguji'] : '-',
                    'path' => $val_p[1],
                    'tgl'  => !empty($sesi['waktu_selesai']) ? date('d/m/Y', strtotime($sesi['waktu_selesai'])) : date('d/m/Y')
                ],
                'penyelia'  => [
                    'id'   => $sesi['verifier_id'],
                    'nama' => !empty($sesi['nama_verifier']) ? $sesi['nama_verifier'] : '-',
                    'nip'  => !empty($sesi['nip_verifier']) ? $sesi['nip_verifier'] : '-',
                    'path' => $val_v[1],
                    'tgl'  => !empty($sesi['waktu_verifikasi']) ? date('d/m/Y', strtotime($sesi['waktu_verifikasi'])) : '-'
                ]
            ];
        }

        // =========================================================================
        // 2. PEMBUATAN STRUKTUR DOKUMEN PDF
        // =========================================================================
        $this->AddPage('P', 'A4');

        // Judul Dokumen
        $this->SetFont('times', 'B', 12);
        $this->Cell(0, 5, 'Laporan Hasil Uji', 0, 1, 'C');
        $nomor_lhu = !empty($sampel['no_sipt']) ? 'LHU.' . $sampel['no_sipt'] : (!empty($sampel['nomor_lhu']) ? $sampel['nomor_lhu'] : 'LHU.090.K.01.01.26.' . str_pad($sampel['id'], 4, '0', STR_PAD_LEFT));
        $this->SetFont('times', 'B', 10);
        $this->Cell(0, 5, 'Nomor : ' . $nomor_lhu, 0, 1, 'C');
        $this->Ln(2);

        // Garis Pembatas 1
        $this->SetLineWidth(0.3);
        $this->Line(10, $this->GetY(), 200, $this->GetY());
        $this->Ln(2);

        // =========================================================================
        // BLOK METADATA 1
        // =========================================================================
        $this->SetFont('times', '', 9);
        $tgl_spu = !empty($sampel['tanggal_sampling']) ? $sampel['tanggal_sampling'] : (!empty($sampel['created_at']) ? date('Y-m-d', strtotime($sampel['created_at'])) : date('Y-m-d'));
        $meta1 = array(
            'Nama Sampel'                  => !empty($sampel['nama_sampel']) ? $sampel['nama_sampel'] : '-',
            'No. Kode Sampel'              => !empty($sampel['no_sipt']) ? $sampel['no_sipt'] : (!empty($sampel['kode_sampel_manual']) ? $sampel['kode_sampel_manual'] : '-'),
            'Tempat Sampling'              => !empty($sampel['nama_sarana']) ? $sampel['nama_sarana'] : (!empty($sampel['kabupaten_kota']) ? $sampel['kabupaten_kota'] : '-'),
            'Tanggal Sampling'             => !empty($sampel['tanggal_sampling']) ? date('d F Y', strtotime($sampel['tanggal_sampling'])) : '-',
            'Nomor Surat Permintaan Uji'   => !empty($sampel['surtug']) ? $sampel['surtug'] : 'SPU.090.01.26.' . date('m.d', strtotime($tgl_spu)) . '.0001',
            'Tanggal Surat Permintaan Uji' => date('d F Y', strtotime($tgl_spu))
        );

        foreach ($meta1 as $k => $v) {
            $this->Cell(65, 4.5, $k, 0, 0, 'L');
            $this->Cell(5, 4.5, ':', 0, 0, 'C');
            $this->Cell(0, 4.5, $v, 0, 1, 'L');
        }

        $this->Ln(1);
        $this->Line(10, $this->GetY(), 200, $this->GetY());
        $this->Ln(2);

        // =========================================================================
        // BLOK METADATA 2
        // =========================================================================
        $pabrik = !empty($sampel['nama_alamat_perusahaan']) ? $sampel['nama_alamat_perusahaan'] : (!empty($sampel['nama_sarana']) ? $sampel['nama_sarana'] : '-');
        $meta2 = array(
            'Nama Pabrik / Distributor / Importir' => $pabrik,
            'No. Registrasi'                      => !empty($sampel['nomor_izin_edar']) ? $sampel['nomor_izin_edar'] : '-',
            'No. Bets / Lot'                      => !empty($sampel['no_bets']) ? $sampel['no_bets'] : '-',
            'Tanggal Kadaluarsa'                  => !empty($sampel['kedaluwarsa']) ? $sampel['kedaluwarsa'] : '-',
            'Kemasan'                             => !empty($sampel['kemasan']) ? $sampel['kemasan'] : '-',
            'Jumlah Sampel'                       => !empty($sampel['jumlah_total']) ? $sampel['jumlah_total'] : '-',
            'Tanggal Mulai Pengujian'             => !empty($sampel['created_at']) ? date('d F Y', strtotime($sampel['created_at'])) : date('d F Y'),
            'Tanggal Selesai Pengujian'           => date('d F Y')
        );

        foreach ($meta2 as $k => $v) {
            $this->Cell(65, 4.5, $k, 0, 0, 'L');
            $this->Cell(5, 4.5, ':', 0, 0, 'C');
            $this->Cell(0, 4.5, $v, 0, 1, 'L');
        }

        $this->Ln(3);

        // =========================================================================
        // BAGIAN II: TABEL HASIL PENGUJIAN GABUNGAN (KIMIA & MIKRO)
        // =========================================================================
        // Kelompokkan sesi berdasarkan jenis_pengujian
        $sesi_by_jenis = [];
        foreach ($sesi_list_dengan_forms as $s) {
            $j = !empty($s['jenis_pengujian']) ? trim($s['jenis_pengujian']) : 'Umum';
            $sesi_by_jenis[$j][] = $s;
        }

        $urutan_jenis = ['Kimia', 'Mikrobiologi'];
        foreach (array_keys($sesi_by_jenis) as $jk) {
            if (!in_array($jk, $urutan_jenis, true)) {
                $urutan_jenis[] = $jk;
            }
        }

        // Lebar kolom tabel (Total 190mm pada A4 dengan margin 10mm)
        $widths = [42, 38, 36, 26, 20, 28];
        $aligns = ['L', 'L', 'L', 'L', 'L', 'L'];

        $cetak_header_tabel = function() use ($widths) {
            $this->SetFont('times', 'B', 8.5);
            $this->SetFillColor(255, 255, 255);
            $this->Cell(190, 5.5, ' Hasil Pengujian Balai Regional :', 1, 1, 'L', true);
            $this->Cell($widths[0], 6, 'Uji yang dilakukan', 1, 0, 'L', true);
            $this->Cell($widths[1], 6, 'Hasil', 1, 0, 'L', true);
            $this->Cell($widths[2], 6, 'Syarat', 1, 0, 'L', true);
            $this->Cell($widths[3], 6, 'Pustaka', 1, 0, 'L', true);
            $this->Cell($widths[4], 6, 'Metode', 1, 0, 'L', true);
            $this->Cell($widths[5], 6, 'Balai Penguji', 1, 1, 'L', true);
            $this->SetFont('times', '', 8);
        };

        $semua_ms = true;
        $ada_tms = false;
        $nama_balai = !empty($sampel['balai_penguji']) ? 'BALAI BESAR POM DI ' . strtoupper($sampel['balai_penguji']) : 'BALAI BESAR POM DI PEKANBARU';

        foreach ($urutan_jenis as $jenis) {
            if (empty($sesi_by_jenis[$jenis])) continue;

            $judul_seksi = ($jenis === 'Mikrobiologi') ? 'PENGUJIAN MIKRO' : 'PENGUJIAN ' . strtoupper($jenis);

            // Periksa sisa halaman sebelum mencetak section title & header
            if ($this->GetY() > 230) {
                $this->AddPage('P', 'A4');
            }

            $this->SetFont('times', 'B', 9);
            $this->Cell(0, 6, $judul_seksi, 0, 1, 'L');

            $cetak_header_tabel();

            foreach ($sesi_by_jenis[$jenis] as $sesi) {
                $forms = !empty($sesi['forms']) ? $sesi['forms'] : [];

                foreach ($forms as $f) {
                    $param_nama = !empty($f['parameter_uji']) ? $f['parameter_uji'] : (!empty($f['nama_parameter']) ? $f['nama_parameter'] : (!empty($f['nama_template']) ? $f['nama_template'] : '-'));
                    $metode_txt = !empty($f['metode_analisa']) ? $f['metode_analisa'] : (!empty($f['nama_metode']) ? $f['nama_metode'] : '-');
                    $pustaka_txt = !empty($f['pustaka_acuan']) ? $f['pustaka_acuan'] : (!empty($f['pustaka_metode']) ? $f['pustaka_metode'] : '-');
                    $syarat_txt = !empty($f['persyaratan']) ? $f['persyaratan'] : '-';

                    // Kesimpulan
                    $kesimpulan_item = !empty($f['kesimpulan']) ? $f['kesimpulan'] : '-';
                    if (stripos($kesimpulan_item, 'tidak memenuhi') !== false) {
                        $ada_tms = true;
                        $semua_ms = false;
                        $tag_kes = 'TMS';
                    } elseif (stripos($kesimpulan_item, 'memenuhi') !== false) {
                        $tag_kes = 'MS';
                    } else {
                        $tag_kes = $kesimpulan_item;
                    }

                    // Parsing Data Hasil
                    $raw_hasil = !empty($f['data_hasil']) ? json_decode($f['data_hasil'], true) : [];
                    $hasil_lines = [];
                    if (is_array($raw_hasil)) {
                        if (isset($raw_hasil['parameter_values']) && is_array($raw_hasil['parameter_values'])) {
                            $raw_hasil = array_merge($raw_hasil, $raw_hasil['parameter_values']);
                        }
                        if (!empty($raw_hasil['hasil_uji'])) {
                            $hasil_lines[] = $raw_hasil['hasil_uji'];
                        } elseif (!empty($raw_hasil['kadar_rata_rata'])) {
                            $hasil_lines[] = 'Kadar: ' . $raw_hasil['kadar_rata_rata'] . '%';
                        } elseif (!empty($raw_hasil['pengamatan'])) {
                            $hasil_lines[] = $raw_hasil['pengamatan'];
                        } elseif (!empty($raw_hasil['tabel_reaksi']) && is_array($raw_hasil['tabel_reaksi'])) {
                            foreach ($raw_hasil['tabel_reaksi'] as $tr_item) {
                                if (!empty($tr_item['pengamatan'])) {
                                    $hasil_lines[] = $tr_item['pengamatan'] . (!empty($tr_item['hasil']) ? ' (' . $tr_item['hasil'] . ')' : '');
                                }
                            }
                        }
                    }

                    $hasil_detail = !empty($hasil_lines) ? implode("\n", array_slice($hasil_lines, 0, 3)) : '-';
                    $hasil_cell = $tag_kes . "\n-\n" . $hasil_detail;

                    $cells = [
                        $param_nama,
                        $hasil_cell,
                        $syarat_txt,
                        $pustaka_txt,
                        $metode_txt,
                        $nama_balai
                    ];

                    $did_break = $this->RowTabelLHU($cells, $widths, $aligns, 4.5, false);
                    if ($did_break) {
                        $cetak_header_tabel();
                        $this->RowTabelLHU($cells, $widths, $aligns, 4.5, false);
                    }
                }
            }

            $this->Ln(3);
        }

        $this->Ln(3);

        // =========================================================================
        // BAGIAN III: KESIMPULAN UMUM SAMPEL
        // =========================================================================
        // Cek sisa tinggi halaman, jika kurang dari 55mm, buka halaman baru untuk kesimpulan & ttd
        if ($this->GetY() > 220) {
            $this->AddPage('P', 'A4');
        }

        $this->SetFillColor(240, 240, 240);
        $this->SetFont('helvetica', 'B', 8.5);
        $this->Cell(0, 5.5, ' III. KESIMPULAN AKHIR LHU', 1, 1, 'L', true);
        $this->SetFont('helvetica', '', 8);

        if ($ada_tms) {
            $kesimpulan_final = 'TIDAK MEMENUHI SYARAT (TMS)';
            $penjelasan_kes = 'Berdasarkan hasil pengujian laboratorium pada parameter-parameter di atas, sampel dinyatakan TIDAK MEMENUHI SYARAT.';
        } elseif ($semua_ms) {
            $kesimpulan_final = 'MEMENUHI SYARAT (MS)';
            $penjelasan_kes = 'Berdasarkan hasil pengujian laboratorium pada seluruh parameter yang diuji, sampel dinyatakan MEMENUHI SYARAT.';
        } else {
            $kesimpulan_final = 'SELESAI DIUJI';
            $penjelasan_kes = 'Pengujian laboratorium telah selesai dilaksanakan sesuai metode dan parameter yang ditentukan.';
        }

        $this->Cell(45, 5, '  Status Akhir Pengujian', 'L', 0, 'L');
        $this->Cell(4, 5, ':', 0, 0, 'C');
        $this->SetFont('helvetica', 'B', 8);
        $this->Cell(0, 5, $kesimpulan_final, 'R', 1, 'L');

        $this->SetFont('helvetica', '', 8);
        $this->Cell(45, 5, '  Keterangan Kesimpulan', 'L', 0, 'L');
        $this->Cell(4, 5, ':', 0, 0, 'C');
        $this->Cell(0, 5, $penjelasan_kes, 'R', 1, 'L');
        $this->Cell(0, 0, '', 'T', 1);
        $this->Ln(4);

        // =========================================================================
        // BAGIAN IV: LEMBAR PENGESAHAN & TANDA TANGAN DIGITAL
        // =========================================================================
        if ($this->GetY() > 230) {
            $this->AddPage('P', 'A4');
        }

        $this->SetFont('helvetica', 'B', 8.5);
        $this->Cell(0, 5.5, ' IV. LEMBAR PENGESAHAN & TANDA TANGAN DIGITAL', 1, 1, 'L', true);
        $this->Ln(2);

        $tgl_lhu = date('d F Y');
        $this->SetFont('helvetica', '', 7.5);
        $this->Cell(0, 4, 'Bandar Lampung, ' . $tgl_lhu, 0, 1, 'R');
        $this->Ln(1);

        // Cetak blok tanda tangan per sesi (Penguji & Penyelia)
        // =========================================================================
        // LEMBAR PENGESAHAN: HANYA MANAJER TEKNIS DI SISI KIRI
        // =========================================================================
        if ($this->GetY() > 240) {
            $this->AddPage('P', 'A4');
        }

        $this->Ln(3);
        $this->SetFont('helvetica', '', 8.5);
        $this->Cell(80, 4, 'Bandar Lampung, ' . $this->formatTanggalIndo(date('Y-m-d')), 0, 1, 'L');
        $this->SetFont('helvetica', 'B', 8.5);
        $this->Cell(80, 4, 'MT Pengujian,', 0, 1, 'L');

        $y_mt = $this->GetY() + 0.5;
        if (!empty($sig_mt_path) && file_exists($sig_mt_path)) {
            $this->Image($sig_mt_path, 15, $y_mt, 26, 12);
        }

        $this->SetY($y_mt + 13);
        $this->SetFont('helvetica', 'BU', 8.5);
        $this->Cell(80, 4, $approver_info['nama_lengkap'], 0, 1, 'L');
        $this->SetFont('helvetica', '', 8);
        $this->Cell(80, 3.5, 'NIP. ' . (!empty($approver_info['nip']) ? $approver_info['nip'] : '-'), 0, 1, 'L');

        // =========================================================================
        // OUTPUT FILE PDF
        // =========================================================================
        if ($save_path) {
            $dir = dirname($save_path);
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
            $this->Output('F', $save_path);
        } elseif ($mode !== 'F') {
            $this->Output($mode, 'LHU_Final_' . $kd_smp . '.pdf');
        }

        return $save_path;
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
        $jenis_uji = !empty($sesi['jenis_pengujian']) ? $sesi['jenis_pengujian'] : '-';
        $status_uji = !empty($sesi['status']) ? $sesi['status'] : '-';
        $this->Cell(0, 4, 'Jenis Pengujian: ' . $jenis_uji . ' | Status: ' . $status_uji, 0, 1, 'C');
        $this->Ln(4);

        // Bagian I: Identitas Sampel
        $this->SetFillColor(240, 240, 240);
        $this->SetFont('helvetica', 'B', 9);
        $this->Cell(0, 6, ' I. IDENTITAS SAMPEL', 1, 1, 'L', true);
        $this->SetFont('helvetica', '', 8.5);

        $kd_smp = !empty($sampel['kode_sampel_manual']) ? $sampel['kode_sampel_manual'] : (!empty($sampel['no']) ? 'NO-' . $sampel['no'] : (!empty($sampel['id']) ? 'SMP-' . $sampel['id'] : '-'));
        $identitas = array(
            'Kode / No. Sampel'     => $kd_smp,
            'Nama Sampel'           => isset($sampel['nama_sampel']) ? $sampel['nama_sampel'] : '-',
            'Kategori / Sub'        => (!empty($sampel['kategori_sampel']) ? $sampel['kategori_sampel'] : '-') . ' / ' . (isset($sampel['sub_kategori']) ? $sampel['sub_kategori'] : '-'),
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
            $nm_tpl = !empty($f['nama_template']) ? $f['nama_template'] : 'Form Pengujian';
            $nm_mtd = !empty($f['nama_metode']) ? $f['nama_metode'] : (!empty($f['nama_parameter']) ? $f['nama_parameter'] : '-');
            $form_title = $form_num++ . '. ' . $nm_tpl . ' (' . $nm_mtd . ')';
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

        // Tanda Tangan Digital Workflow (Embed Gambar Tanda Tangan Nyata Jika Ada)
        $sig_p = !empty($penguji['id']) ? $this->getUserSignature($penguji['id']) : (!empty($sesi['penguji_id']) ? $this->getUserSignature($sesi['penguji_id']) : NULL);
        $sig_v = !empty($verifier['id']) ? $this->getUserSignature($verifier['id']) : (!empty($sesi['verifier_id']) ? $this->getUserSignature($sesi['verifier_id']) : NULL);
        $sig_a = !empty($approver['id']) ? $this->getUserSignature($approver['id']) : (!empty($sesi['approver_id']) ? $this->getUserSignature($sesi['approver_id']) : NULL);

        $y_sig = $this->GetY() + 1;
        $sig_h = 12;
        $sig_w = 26;

        if ($sig_p && file_exists($sig_p)) {
            $this->Image($sig_p, 28.5, $y_sig, $sig_w, $sig_h);
        } else {
            $this->SetTextColor(40, 80, 160);
            $this->SetFont('helvetica', 'B', 7);
            $this->SetXY(10, $y_sig + 4);
            $this->Cell(63, 4, '[SIGNED DIGITAL]', 0, 0, 'C');
        }

        if ($sig_v && file_exists($sig_v)) {
            $this->Image($sig_v, 91.5, $y_sig, $sig_w, $sig_h);
        } else {
            $this->SetTextColor(40, 80, 160);
            $this->SetFont('helvetica', 'B', 7);
            $this->SetXY(73, $y_sig + 4);
            $this->Cell(63, 4, '[VERIFIED DIGITAL]', 0, 0, 'C');
        }

        if ($sig_a && file_exists($sig_a)) {
            $this->Image($sig_a, 155, $y_sig, $sig_w, $sig_h);
        } else {
            $this->SetTextColor(40, 80, 160);
            $this->SetFont('helvetica', 'B', 7);
            $this->SetXY(136, $y_sig + 4);
            $this->Cell(64, 4, '[APPROVED DIGITAL]', 0, 0, 'C');
        }

        $this->SetTextColor(0, 0, 0);
        $this->SetY($y_sig + $sig_h + 2);

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

    /**
     * Helper konversi tanggal ke format teks Indonesia (contoh: 20 Oktober 2026)
     */
    public function formatTanggalIndo($tanggal = null)
    {
        if (empty($tanggal)) {
            $tanggal = date('Y-m-d');
        }

        $bulan_indo = array(
            1  => 'Januari',
            2  => 'Februari',
            3  => 'Maret',
            4  => 'April',
            5  => 'Mei',
            6  => 'Juni',
            7  => 'Juli',
            8  => 'Agustus',
            9  => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        );

        $timestamp = strtotime($tanggal);
        if (!$timestamp) {
            $timestamp = time();
        }

        $tgl   = date('j', $timestamp);
        $bln   = (int)date('n', $timestamp);
        $thn   = date('Y', $timestamp);
        $nama_bulan = isset($bulan_indo[$bln]) ? $bulan_indo[$bln] : date('F', $timestamp);

        return $tgl . ' ' . $nama_bulan . ' ' . $thn;
    }
}

