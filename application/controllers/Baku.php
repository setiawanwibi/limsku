<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Baku (Master Data Baku Pembanding Laboratorium)
 * 
 * Menangani manajemen Master Data Baku Pembanding Laboratorium BBPOM.
 * Mendukung normalisasi otomatis kolom Kadar Air (%) dan Kadar Baku / KCKT (%),
 * informasi nomor kontrol, lokasi penyimpanan, suhu penyimpanan, persyaratan CoA,
 * serta fitur Import File Excel (.xlsx) dinamis.
 */
class Baku extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_Baku');
    }

    /**
     * Halaman daftar Master Data Baku
     */
    public function index()
    {
        $this->cek_hak_akses('baku_view');

        $lokasi  = $this->input->get('lokasi', TRUE);
        $keyword = $this->input->get('q', TRUE);

        $this->data['halaman_aktif'] = 'baku';
        $this->data['judul_halaman'] = 'Master Data Baku Pembanding';

        $this->data['lokasi_aktif']  = $lokasi;
        $this->data['keyword_aktif'] = $keyword;
        $this->data['daftar_lokasi'] = $this->Model_Baku->ambil_daftar_lokasi();
        $this->data['daftar_baku']   = $this->Model_Baku->ambil_semua($lokasi, $keyword);
        $this->data['total_baku']    = count($this->data['daftar_baku']);

        $this->muat_tampilan('baku/index');
    }

    /**
     * Form dan proses tambah data baku baru
     */
    public function tambah()
    {
        $this->cek_hak_akses('baku_create');

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('nama_baku', 'Nama Baku', 'required|trim|max_length[255]', array(
                'required'   => '%s wajib diisi.',
                'max_length' => '%s maksimal 255 karakter.'
            ));
            $this->form_validation->set_rules('no_kontrol', 'Nomor Kontrol', 'trim|max_length[100]');
            $this->form_validation->set_rules('kode_posisi', 'Kode / Posisi Rak', 'trim|max_length[50]');
            $this->form_validation->set_rules('lokasi_penyimpanan', 'Lokasi Penyimpanan', 'required|trim');

            if ($this->form_validation->run() === TRUE) {
                $raw_kemurnian = $this->input->post('kemurnian_raw', TRUE);
                $kadar_air_input = $this->input->post('kadar_air', TRUE);
                $kadar_baku_input = $this->input->post('kadar_baku', TRUE);

                // Jika kolom normalisasi tidak diisi manual tetapi ada kemurnian_raw, coba normalisasi
                if ($kadar_air_input === '' || $kadar_baku_input === '') {
                    $norm = Model_Baku::normalisasi_kemurnian($raw_kemurnian);
                    if ($kadar_air_input === '' && $norm['kadar_air'] !== NULL) {
                        $kadar_air_input = $norm['kadar_air'];
                    }
                    if ($kadar_baku_input === '' && $norm['kadar_baku'] !== NULL) {
                        $kadar_baku_input = $norm['kadar_baku'];
                    }
                }

                $data = array(
                    'nama_baku'          => $this->input->post('nama_baku', TRUE),
                    'no_kontrol'         => $this->input->post('no_kontrol', TRUE) ?: NULL,
                    'kode_posisi'        => $this->input->post('kode_posisi', TRUE) ?: NULL,
                    'kadar_air'          => is_numeric($kadar_air_input) ? floatval($kadar_air_input) : NULL,
                    'kadar_baku'         => is_numeric($kadar_baku_input) ? floatval($kadar_baku_input) : NULL,
                    'kemurnian_raw'      => $raw_kemurnian ?: NULL,
                    'penyimpanan'        => $this->input->post('penyimpanan', TRUE) ?: NULL,
                    'lokasi_penyimpanan' => $this->input->post('lokasi_penyimpanan', TRUE),
                    'suhu_rekomendasi'   => $this->input->post('suhu_rekomendasi', TRUE) ?: NULL,
                    'keterangan'         => $this->input->post('keterangan', TRUE) ?: NULL,
                );

                $baku_id = $this->Model_Baku->tambah($data);
                if ($baku_id) {
                    $this->catat_audit('CREATE_BAKU', "Menambah baku pembanding baru ID #$baku_id: " . $data['nama_baku']);
                    $this->session->set_flashdata('pesan_sukses', 'Data Baku Pembanding berhasil ditambahkan.');
                    redirect('baku');
                } else {
                    $this->session->set_flashdata('pesan_gagal', 'Gagal menambahkan data baku pembanding.');
                }
            }
        }

        $this->data['halaman_aktif'] = 'baku';
        $this->data['judul_halaman'] = 'Tambah Data Baku Pembanding';
        $this->data['daftar_lokasi'] = $this->Model_Baku->ambil_daftar_lokasi();
        $this->muat_tampilan('baku/tambah');
    }

    /**
     * Form dan proses ubah data baku
     */
    public function ubah($id)
    {
        $this->cek_hak_akses('baku_edit');

        $baku = $this->Model_Baku->ambil_by_id($id);
        if (!$baku) {
            $this->session->set_flashdata('pesan_gagal', 'Data baku pembanding tidak ditemukan.');
            redirect('baku');
        }

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('nama_baku', 'Nama Baku', 'required|trim|max_length[255]');
            $this->form_validation->set_rules('lokasi_penyimpanan', 'Lokasi Penyimpanan', 'required|trim');

            if ($this->form_validation->run() === TRUE) {
                $raw_kemurnian = $this->input->post('kemurnian_raw', TRUE);
                $kadar_air_input = $this->input->post('kadar_air', TRUE);
                $kadar_baku_input = $this->input->post('kadar_baku', TRUE);

                $data = array(
                    'nama_baku'          => $this->input->post('nama_baku', TRUE),
                    'no_kontrol'         => $this->input->post('no_kontrol', TRUE) ?: NULL,
                    'kode_posisi'        => $this->input->post('kode_posisi', TRUE) ?: NULL,
                    'kadar_air'          => is_numeric($kadar_air_input) ? floatval($kadar_air_input) : NULL,
                    'kadar_baku'         => is_numeric($kadar_baku_input) ? floatval($kadar_baku_input) : NULL,
                    'kemurnian_raw'      => $raw_kemurnian ?: NULL,
                    'penyimpanan'        => $this->input->post('penyimpanan', TRUE) ?: NULL,
                    'lokasi_penyimpanan' => $this->input->post('lokasi_penyimpanan', TRUE),
                    'suhu_rekomendasi'   => $this->input->post('suhu_rekomendasi', TRUE) ?: NULL,
                    'keterangan'         => $this->input->post('keterangan', TRUE) ?: NULL,
                );

                if ($this->Model_Baku->ubah($id, $data)) {
                    $this->catat_audit('UPDATE_BAKU', "Mengubah data baku ID #$id: " . $data['nama_baku']);
                    $this->session->set_flashdata('pesan_sukses', 'Data baku pembanding berhasil diperbarui.');
                    redirect('baku');
                } else {
                    $this->session->set_flashdata('pesan_gagal', 'Gagal memperbarui data baku pembanding.');
                }
            }
        }

        $this->data['halaman_aktif'] = 'baku';
        $this->data['judul_halaman'] = 'Ubah Data Baku Pembanding';
        $this->data['baku']          = $baku;
        $this->data['daftar_lokasi'] = $this->Model_Baku->ambil_daftar_lokasi();
        $this->muat_tampilan('baku/ubah');
    }

    /**
     * Hapus data baku
     */
    public function hapus($id)
    {
        $this->cek_hak_akses('baku_delete');

        $baku = $this->Model_Baku->ambil_by_id($id);
        if (!$baku) {
            $this->session->set_flashdata('pesan_gagal', 'Data baku tidak ditemukan.');
            redirect('baku');
        }

        if ($this->Model_Baku->hapus($id)) {
            $this->catat_audit('DELETE_BAKU', "Menghapus baku pembanding ID #$id: " . $baku['nama_baku']);
            $this->session->set_flashdata('pesan_sukses', 'Data baku pembanding berhasil dihapus.');
        } else {
            $this->session->set_flashdata('pesan_gagal', 'Gagal menghapus data baku pembanding.');
        }

        redirect('baku');
    }

    /**
     * Halaman Impor Excel Master Baku
     */
    public function import()
    {
        $this->cek_hak_akses('baku_import');

        if ($this->input->method() === 'post') {
            if (empty($_FILES['file_excel']['name'])) {
                $this->session->set_flashdata('pesan_gagal', 'Silakan pilih file spreadsheet Excel (.xlsx) yang akan diunggah.');
                redirect('baku/import');
            }

            if (isset($_FILES['file_excel']['error']) && $_FILES['file_excel']['error'] !== UPLOAD_ERR_OK) {
                $this->session->set_flashdata('pesan_gagal', 'Terjadi kesalahan saat mengunggah file.');
                redirect('baku/import');
            }

            $ext = strtolower(pathinfo($_FILES['file_excel']['name'], PATHINFO_EXTENSION));
            if ($ext !== 'xlsx') {
                $this->session->set_flashdata('pesan_gagal', 'Format file tidak didukung. Harap unggah file spreadsheet Excel dengan ekstensi .xlsx.');
                redirect('baku/import');
            }

            $tmpPath = $_FILES['file_excel']['tmp_name'];
            $this->load->library('SimpleXLSX');

            $xlsx = SimpleXLSX::parse($tmpPath);
            if (!$xlsx) {
                $this->session->set_flashdata('pesan_gagal', 'Gagal membaca file Excel: ' . SimpleXLSX::parseError());
                redirect('baku/import');
            }

            $sheetNames = $xlsx->sheetNames();
            $mode_impor = $this->input->post('mode_impor', TRUE); // 'replace' atau 'append'

            // Baca CoA jika sheet COA KEMURNIAN tersedia
            $coaData = array();
            $coaSheetIndex = false;
            foreach ($sheetNames as $idx => $sName) {
                if (stripos($sName, 'COA') !== false || stripos($sName, 'KEMURNIAN') !== false) {
                    $coaSheetIndex = $idx;
                    break;
                }
            }

            if ($coaSheetIndex !== false) {
                $coaRows = $xlsx->rows($coaSheetIndex);
                for ($r = 4; $r < count($coaRows); $r++) {
                    $cRow = $coaRows[$r];
                    $cNama = isset($cRow[1]) ? trim($cRow[1]) : '';
                    if (empty($cNama)) continue;
                    $cKontrol = isset($cRow[2]) ? trim($cRow[2]) : '';
                    $cKemurnian = isset($cRow[4]) ? trim($cRow[4]) : '';
                    $cPenyimpanan = isset($cRow[5]) ? trim($cRow[5]) : '';
                    $coaKey = strtolower($cNama) . '||' . strtolower($cKontrol);
                    $coaData[$coaKey] = array(
                        'kemurnian' => $cKemurnian,
                        'penyimpanan' => $cPenyimpanan
                    );
                }
            }

            $sheetsToProcess = array();
            foreach ($sheetNames as $idx => $sName) {
                if ($idx === $coaSheetIndex) continue;
                $suhu = 'Suhu Ruang 15-30°C';
                if (stripos($sName, 'KULKAS') !== false || stripos($sName, 'SHOWCASE') !== false) {
                    $suhu = 'Suhu Kulkas 2-15°C';
                } elseif (stripos($sName, 'FREEZER') !== false) {
                    $suhu = 'Suhu Freezer -20-0°C';
                }
                $sheetsToProcess[$idx] = array('nama' => $sName, 'suhu' => $suhu);
            }

            // Jika hanya 1 sheet (misal format export sederhana)
            if (empty($sheetsToProcess)) {
                $sheetsToProcess[0] = array('nama' => 'Ruang Baku Pembanding', 'suhu' => 'Suhu Ruang 15-30°C');
            }

            $batchInsert = array();
            $totalAir = 0;
            $totalBaku = 0;

            foreach ($sheetsToProcess as $sIdx => $sMeta) {
                $rows = $xlsx->rows($sIdx);
                // Deteksi baris header
                $startRow = 4;
                if (!empty($rows)) {
                    foreach ($rows as $rIdx => $rData) {
                        $col1 = isset($rData[1]) ? strtolower(trim($rData[1])) : '';
                        if ($col1 === 'nama baku' || $col1 === 'nama_baku') {
                            $startRow = $rIdx + 1;
                            break;
                        }
                    }
                }

                for ($r = $startRow; $r < count($rows); $r++) {
                    $row = $rows[$r];
                    $no = isset($row[0]) ? trim($row[0]) : null;
                    $nama = isset($row[1]) ? trim($row[1]) : '';
                    if (empty($nama)) continue;

                    $no_kontrol = isset($row[2]) ? trim($row[2]) : '';
                    if (preg_match('/^(\d+)\.0$/', $no_kontrol, $m)) {
                        $no_kontrol = $m[1];
                    }
                    $kode_posisi = isset($row[3]) ? trim($row[3]) : '';

                    // Cek jika file export sudah punya kolom kadar air & kadar baku
                    $kadar_air = NULL;
                    $kadar_baku = NULL;
                    $kemurnian_raw = NULL;
                    $penyimpanan = NULL;

                    if (isset($row[4]) && is_numeric(str_replace(',', '.', trim($row[4])))) {
                        $kadar_air = floatval(str_replace(',', '.', trim($row[4])));
                    }
                    if (isset($row[5]) && is_numeric(str_replace(',', '.', trim($row[5])))) {
                        $kadar_baku = floatval(str_replace(',', '.', trim($row[5])));
                    }

                    // Ambil dari CoA jika ada
                    $coaKey = strtolower($nama) . '||' . strtolower($no_kontrol);
                    if (isset($coaData[$coaKey])) {
                        $kemurnian_raw = $coaData[$coaKey]['kemurnian'];
                        $penyimpanan = $coaData[$coaKey]['penyimpanan'];
                    } else {
                        foreach ($coaData as $k => $c) {
                            if (strpos($k, strtolower($nama) . '||') === 0) {
                                $kemurnian_raw = $c['kemurnian'];
                                $penyimpanan = $c['penyimpanan'];
                                break;
                            }
                        }
                    }

                    // Normalisasi otomatis jika kadar_air atau kadar_baku belum terisi
                    if ($kemurnian_raw) {
                        $norm = Model_Baku::normalisasi_kemurnian($kemurnian_raw);
                        if ($kadar_air === NULL) $kadar_air = $norm['kadar_air'];
                        if ($kadar_baku === NULL) $kadar_baku = $norm['kadar_baku'];
                    }

                    if ($kadar_air !== NULL) $totalAir++;
                    if ($kadar_baku !== NULL) $totalBaku++;

                    $no_int = is_numeric($no) ? intval($no) : null;
                    $batchInsert[] = array(
                        'no_urut'            => $no_int,
                        'nama_baku'          => $nama,
                        'no_kontrol'         => $no_kontrol ?: NULL,
                        'kode_posisi'        => $kode_posisi ?: NULL,
                        'kadar_air'          => $kadar_air,
                        'kadar_baku'         => $kadar_baku,
                        'kemurnian_raw'      => $kemurnian_raw,
                        'penyimpanan'        => $penyimpanan,
                        'lokasi_penyimpanan' => $sMeta['nama'],
                        'suhu_rekomendasi'   => $sMeta['suhu'],
                        'created_at'         => date('Y-m-d H:i:s'),
                    );
                }
            }

            if (empty($batchInsert)) {
                $this->session->set_flashdata('pesan_gagal', 'Tidak ada baris data baku yang valid untuk diimpor.');
                redirect('baku/import');
            }

            if ($mode_impor === 'replace') {
                $this->db->truncate('master_baku');
            }

            // Chunk insert batch 100 rows per query
            $chunks = array_chunk($batchInsert, 100);
            $totalInserted = 0;
            foreach ($chunks as $chunk) {
                $this->db->insert_batch('master_baku', $chunk);
                $totalInserted += count($chunk);
            }

            $this->catat_audit('IMPORT_BAKU', "Mengimpor $totalInserted data baku via Excel ($mode_impor). Ternormalisasi: Air=$totalAir, Baku=$totalBaku.");
            $this->session->set_flashdata('pesan_sukses', "Berhasil mengimpor <strong>$totalInserted</strong> data baku pembanding! (Ternormalisasi otomatis: <strong>$totalAir</strong> Kadar Air, <strong>$totalBaku</strong> Kadar Baku KCKT).");
            redirect('baku');
        }

        $this->data['halaman_aktif'] = 'baku';
        $this->data['judul_halaman'] = 'Impor Master Data Baku dari Excel';
        $this->muat_tampilan('baku/import');
    }

    /**
     * Endpoint JSON Autocomplete Baku Pembanding untuk Pengujian
     */
    public function cari_ajax()
    {
        $term = $this->input->get('term', TRUE);
        $data = $this->Model_Baku->cari_baku($term, 20);
        $this->output
             ->set_content_type('application/json')
             ->set_output(json_encode($data));
    }
}
