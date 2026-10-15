<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Alat (Alat Laboratorium)
 * 
 * Menangani manajemen Master Data Inventaris Alat Laboratorium BBPOM.
 */
class Alat extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_Alat');
    }

    /**
     * Daftar alat laboratorium
     */
    public function index()
    {
        $this->cek_hak_akses('alat_view');

        $this->data['halaman_aktif'] = 'alat';
        $this->data['judul_halaman'] = 'Master Alat Laboratorium';
        $this->data['breadcrumbs']   = array(
            array('label' => 'Alat Laboratorium', 'url' => site_url('alat'))
        );

        $this->data['daftar_alat'] = $this->Model_Alat->ambil_semua();

        $this->muat_tampilan('alat/index');
    }

    /**
     * Form dan proses tambah alat laboratorium baru
     */
    public function tambah()
    {
        $this->cek_hak_akses('alat_create');

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('nama_barang', 'Nama Barang', 'required|trim|max_length[150]', array(
                'required'   => '%s wajib diisi.',
                'max_length' => '%s maksimal 150 karakter.'
            ));
            $this->form_validation->set_rules('merk_tipe', 'Merk/Tipe', 'trim|max_length[150]', array(
                'max_length' => '%s maksimal 150 karakter.'
            ));
            $this->form_validation->set_rules('perangkat_lunak', 'Perangkat Lunak', 'trim|max_length[150]', array(
                'max_length' => '%s maksimal 150 karakter.'
            ));
            $this->form_validation->set_rules('detektor', 'Detektor', 'trim|max_length[150]', array(
                'max_length' => '%s maksimal 150 karakter.'
            ));
            $this->form_validation->set_rules('tanggal_perolehan', 'Tanggal Perolehan', 'trim|regex_match[/^\d{4}-\d{2}-\d{2}$/]', array(
                'regex_match' => '%s harus berformat YYYY-MM-DD.'
            ));
            $this->form_validation->set_rules('kondisi_adm', 'Kondisi ADM', 'trim|max_length[100]', array(
                'max_length' => '%s maksimal 100 karakter.'
            ));
            $this->form_validation->set_rules('kode_ruangan', 'Kode Ruangan', 'trim|max_length[50]', array(
                'max_length' => '%s maksimal 50 karakter.'
            ));
            $this->form_validation->set_rules('nama_ruangan', 'Nama Ruangan', 'trim|max_length[150]', array(
                'max_length' => '%s maksimal 150 karakter.'
            ));

            if ($this->form_validation->run() === TRUE) {
                $tanggal_perolehan = $this->input->post('tanggal_perolehan', TRUE);
                if (empty($tanggal_perolehan)) {
                    $tanggal_perolehan = NULL;
                }

                $data_simpan = array(
                    'nama_barang'       => $this->input->post('nama_barang', TRUE),
                    'merk_tipe'         => $this->input->post('merk_tipe', TRUE) ?: NULL,
                    'perangkat_lunak'   => $this->input->post('perangkat_lunak', TRUE) ?: NULL,
                    'detektor'          => $this->input->post('detektor', TRUE) ?: NULL,
                    'tanggal_perolehan' => $tanggal_perolehan,
                    'kondisi_adm'       => $this->input->post('kondisi_adm', TRUE) ?: NULL,
                    'kode_ruangan'      => $this->input->post('kode_ruangan', TRUE) ?: NULL,
                    'nama_ruangan'      => $this->input->post('nama_ruangan', TRUE) ?: NULL,
                    'status'            => $this->input->post('status', TRUE) ?: 'Aktif'
                );

                $alat_id = $this->Model_Alat->tambah($data_simpan);

                if ($alat_id) {
                    $alat_baru = $this->Model_Alat->ambil_by_id($alat_id);
                    $this->catat_audit('CREATE', 'Alat', array(
                        'alat_id'     => $alat_id,
                        'kode_barang' => $alat_baru['kode_barang'],
                        'nama_barang' => $data_simpan['nama_barang']
                    ));

                    $this->session->set_flashdata('pesan_sukses', 'Alat laboratorium baru berhasil ditambahkan dengan Kode Barang: ' . $alat_baru['kode_barang'] . '.');
                    redirect('alat');
                } else {
                    $this->session->set_flashdata('pesan_gagal', 'Gagal menambahkan alat laboratorium.');
                }
            }
        }

        $this->data['halaman_aktif']      = 'alat';
        $this->data['judul_halaman']      = 'Tambah Alat Laboratorium';
        $this->data['breadcrumbs']        = array(
            array('label' => 'Alat Laboratorium', 'url' => site_url('alat')),
            array('label' => 'Tambah', 'url' => '#')
        );
        $this->data['kode_barang_preview'] = $this->Model_Alat->generate_kode_barang();
        $this->data['daftar_ruangan']      = $this->Model_Alat->ambil_daftar_ruangan();

        $this->muat_tampilan('alat/tambah');
    }

    /**
     * Detail informasi alat laboratorium
     * 
     * @param int $id
     */
    public function detail($id = NULL)
    {
        $this->cek_hak_akses('alat_view');

        $alat = $this->Model_Alat->ambil_by_id($id);
        if (!$alat) {
            show_404();
        }

        $this->data['halaman_aktif'] = 'alat';
        $this->data['judul_halaman'] = 'Detail Alat Laboratorium: ' . html_escape($alat['kode_barang']);
        $this->data['breadcrumbs']   = array(
            array('label' => 'Alat Laboratorium', 'url' => site_url('alat')),
            array('label' => 'Detail', 'url' => '#')
        );
        $this->data['alat']          = $alat;

        $this->muat_tampilan('alat/detail');
    }

    /**
     * Form dan proses ubah data alat laboratorium
     * 
     * @param int $id
     */
    public function edit($id = NULL)
    {
        $this->cek_hak_akses('alat_edit');

        $alat = $this->Model_Alat->ambil_by_id($id);
        if (!$alat) {
            show_404();
        }

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('nama_barang', 'Nama Barang', 'required|trim|max_length[150]', array(
                'required'   => '%s wajib diisi.',
                'max_length' => '%s maksimal 150 karakter.'
            ));
            $this->form_validation->set_rules('merk_tipe', 'Merk/Tipe', 'trim|max_length[150]', array(
                'max_length' => '%s maksimal 150 karakter.'
            ));
            $this->form_validation->set_rules('perangkat_lunak', 'Perangkat Lunak', 'trim|max_length[150]', array(
                'max_length' => '%s maksimal 150 karakter.'
            ));
            $this->form_validation->set_rules('detektor', 'Detektor', 'trim|max_length[150]', array(
                'max_length' => '%s maksimal 150 karakter.'
            ));
            $this->form_validation->set_rules('tanggal_perolehan', 'Tanggal Perolehan', 'trim|regex_match[/^\d{4}-\d{2}-\d{2}$/]', array(
                'regex_match' => '%s harus berformat YYYY-MM-DD.'
            ));
            $this->form_validation->set_rules('kondisi_adm', 'Kondisi ADM', 'trim|max_length[100]', array(
                'max_length' => '%s maksimal 100 karakter.'
            ));
            $this->form_validation->set_rules('kode_ruangan', 'Kode Ruangan', 'trim|max_length[50]', array(
                'max_length' => '%s maksimal 50 karakter.'
            ));
            $this->form_validation->set_rules('nama_ruangan', 'Nama Ruangan', 'trim|max_length[150]', array(
                'max_length' => '%s maksimal 150 karakter.'
            ));

            if ($this->form_validation->run() === TRUE) {
                $tanggal_perolehan = $this->input->post('tanggal_perolehan', TRUE);
                if (empty($tanggal_perolehan)) {
                    $tanggal_perolehan = NULL;
                }

                $data_update = array(
                    'nama_barang'       => $this->input->post('nama_barang', TRUE),
                    'merk_tipe'         => $this->input->post('merk_tipe', TRUE) ?: NULL,
                    'perangkat_lunak'   => $this->input->post('perangkat_lunak', TRUE) ?: NULL,
                    'detektor'          => $this->input->post('detektor', TRUE) ?: NULL,
                    'tanggal_perolehan' => $tanggal_perolehan,
                    'kondisi_adm'       => $this->input->post('kondisi_adm', TRUE) ?: NULL,
                    'kode_ruangan'      => $this->input->post('kode_ruangan', TRUE) ?: NULL,
                    'nama_ruangan'      => $this->input->post('nama_ruangan', TRUE) ?: NULL,
                    'status'            => $this->input->post('status', TRUE) ?: 'Aktif'
                );

                if ($this->Model_Alat->ubah($id, $data_update)) {
                    $this->catat_audit('UPDATE', 'Alat', array(
                        'alat_id'     => $id,
                        'kode_barang' => $alat['kode_barang'],
                        'nama_barang' => $data_update['nama_barang']
                    ));

                    $this->session->set_flashdata('pesan_sukses', 'Data alat laboratorium ' . $alat['kode_barang'] . ' berhasil diperbarui.');
                    redirect('alat');
                } else {
                    $this->session->set_flashdata('pesan_gagal', 'Gagal memperbarui data alat laboratorium.');
                }
            }
        }

        $this->data['halaman_aktif'] = 'alat';
        $this->data['judul_halaman'] = 'Edit Alat Laboratorium';
        $this->data['breadcrumbs']   = array(
            array('label' => 'Alat Laboratorium', 'url' => site_url('alat')),
            array('label' => 'Edit', 'url' => '#')
        );
        $this->data['alat']           = $alat;
        $this->data['daftar_ruangan'] = $this->Model_Alat->ambil_daftar_ruangan();

        $this->muat_tampilan('alat/edit');
    }

    /**
     * Ubah status alat laboratorium (Aktif/Nonaktif)
     * 
     * @param int $id
     */
    public function status($id = NULL)
    {
        $this->cek_hak_akses('alat_edit');

        $alat = $this->Model_Alat->ambil_by_id($id);
        if (!$alat) {
            show_404();
        }

        $status_baru = ($alat['status'] === 'Aktif') ? 'Nonaktif' : 'Aktif';

        if ($this->Model_Alat->ubah_status($id, $status_baru)) {
            $this->catat_audit('UPDATE_STATUS', 'Alat', array(
                'alat_id'     => $id,
                'kode_barang' => $alat['kode_barang'],
                'status_baru' => $status_baru
            ));

            $this->session->set_flashdata('pesan_sukses', 'Status alat ' . $alat['kode_barang'] . ' diubah menjadi ' . $status_baru . '.');
        } else {
            $this->session->set_flashdata('pesan_gagal', 'Gagal mengubah status alat laboratorium.');
        }

        redirect('alat');
    }

    /**
     * Hapus alat laboratorium secara aman
     * 
     * @param int $id
     */
    public function hapus($id = NULL)
    {
        $this->cek_hak_akses('alat_delete');

        $alat = $this->Model_Alat->ambil_by_id($id);
        if (!$alat) {
            show_404();
        }

        if ($this->Model_Alat->hapus($id)) {
            $this->catat_audit('DELETE', 'Alat', array(
                'alat_id'     => $id,
                'kode_barang' => $alat['kode_barang'],
                'nama_barang' => $alat['nama_barang']
            ));

            $this->session->set_flashdata('pesan_sukses', 'Alat laboratorium ' . $alat['kode_barang'] . ' berhasil dihapus.');
        } else {
            $this->session->set_flashdata('pesan_gagal', 'Gagal menghapus data alat laboratorium.');
        }

        redirect('alat');
    }

    /**
     * Halaman upload file Excel dan preview validasi import alat laboratorium
     */
    public function import()
    {
        $this->cek_hak_akses('alat_import');

        if ($this->input->method() === 'post') {
            // Validasi keberadaan file
            if (empty($_FILES['file_excel']['name'])) {
                $this->session->set_flashdata('pesan_gagal', 'Silakan pilih file Excel (.xlsx) yang akan diunggah.');
                redirect('alat/import');
            }

            $ext = strtolower(pathinfo($_FILES['file_excel']['name'], PATHINFO_EXTENSION));
            if ($ext !== 'xlsx') {
                $this->session->set_flashdata('pesan_gagal', 'Format file tidak valid. Hanya file ber-ekstensi .xlsx yang diperbolehkan.');
                redirect('alat/import');
            }

            $tmpPath = $_FILES['file_excel']['tmp_name'];
            $this->load->library('SimpleXLSX');

            $xlsx = SimpleXLSX::parse($tmpPath);
            if (!$xlsx) {
                $this->session->set_flashdata('pesan_gagal', 'Error membaca file Excel: ' . SimpleXLSX::parseError());
                redirect('alat/import');
            }

            $rows = $xlsx->rows();
            if (empty($rows) || count($rows) < 2) {
                $this->session->set_flashdata('pesan_gagal', 'File Excel kosong atau tidak memiliki baris data.');
                redirect('alat/import');
            }

            // 1. Temukan baris header secara fleksibel
            $header_idx = -1;
            $max_header_scan = min(15, count($rows));

            for ($i = 0; $i < $max_header_scan; $i++) {
                $row_str = strtolower(implode(' ', array_filter($rows[$i])));
                if (strpos($row_str, 'nama barang') !== FALSE || strpos($row_str, 'nama alat') !== FALSE) {
                    $header_idx = $i;
                    break;
                }
            }

            if ($header_idx === -1) {
                // Fallback cek jika kolom mengandung kata barang atau merk
                for ($i = 0; $i < $max_header_scan; $i++) {
                    $row_str = strtolower(implode(' ', array_filter($rows[$i])));
                    if (strpos($row_str, 'merk') !== FALSE || strpos($row_str, 'ruangan') !== FALSE) {
                        $header_idx = $i;
                        break;
                    }
                }
            }

            if ($header_idx === -1) {
                $header_idx = 0; // Default baris 0 jika tidak ada penanda khusus
            }

            $header_row = $rows[$header_idx];

            // 2. Petakan kolom berdasarkan nama header (bukan asumsi posisi)
            $col_map = array();
            foreach ($header_row as $c_idx => $c_name) {
                $norm = strtolower(trim((string)$c_name));
                $norm = preg_replace('/[^\w\s]/u', ' ', $norm);
                $norm = preg_replace('/\s+/', ' ', $norm);
                $norm = trim($norm);

                if (empty($norm)) continue;

                if (strpos($norm, 'nama barang') !== FALSE || strpos($norm, 'nama alat') !== FALSE || $norm === 'barang') {
                    $col_map['nama_barang'] = $c_idx;
                } elseif (strpos($norm, 'merk') !== FALSE || strpos($norm, 'tipe') !== FALSE || strpos($norm, 'type') !== FALSE) {
                    $col_map['merk_tipe'] = $c_idx;
                } elseif (strpos($norm, 'tanggal perolehan') !== FALSE || strpos($norm, 'tgl perolehan') !== FALSE || strpos($norm, 'perolehan') !== FALSE) {
                    $col_map['tanggal_perolehan'] = $c_idx;
                } elseif (strpos($norm, 'kondisi adm') !== FALSE || strpos($norm, 'kondisi') !== FALSE) {
                    $col_map['kondisi_adm'] = $c_idx;
                } elseif (strpos($norm, 'kode ruangan') !== FALSE || strpos($norm, 'kd ruang') !== FALSE) {
                    $col_map['kode_ruangan'] = $c_idx;
                } elseif (strpos($norm, 'nama ruangan') !== FALSE || strpos($norm, 'nm ruang') !== FALSE || strpos($norm, 'ruangan') !== FALSE || strpos($norm, 'lokasi') !== FALSE) {
                    if (!isset($col_map['nama_ruangan'])) {
                        $col_map['nama_ruangan'] = $c_idx;
                    }
                }
            }

            if (!isset($col_map['nama_barang'])) {
                $this->session->set_flashdata('pesan_gagal', 'Header kolom "Nama Barang" tidak ditemukan pada file Excel. Mohon pastikan file memiliki kolom Nama Barang.');
                redirect('alat/import');
            }

            // 3. Proses dan validasi baris data
            $valid_rows   = array();
            $invalid_rows = array();
            $total_read   = 0;

            for ($r = $header_idx + 1; $r < count($rows); $r++) {
                $row = $rows[$r];
                $excel_row_num = $r + 1; // 1-indexed Excel

                // Cek baris kosong total
                $has_val = FALSE;
                foreach ($row as $cell) {
                    if (trim((string)$cell) !== '') {
                        $has_val = TRUE;
                        break;
                    }
                }
                if (!$has_val) {
                    continue; // Skip baris kosong
                }

                $nama_barang = isset($col_map['nama_barang']) && isset($row[$col_map['nama_barang']]) ? trim((string)$row[$col_map['nama_barang']]) : '';

                // Jika nama barang kosong, abaikan baris atau anggap invalid
                if ($nama_barang === '') {
                    $invalid_rows[] = array(
                        'row'    => $excel_row_num,
                        'alasan' => 'Nama Barang kosong.'
                    );
                    continue;
                }

                $total_read++;

                $merk_tipe = isset($col_map['merk_tipe']) && isset($row[$col_map['merk_tipe']]) ? trim((string)$row[$col_map['merk_tipe']]) : '';
                $raw_tgl   = isset($col_map['tanggal_perolehan']) && isset($row[$col_map['tanggal_perolehan']]) ? trim((string)$row[$col_map['tanggal_perolehan']]) : '';
                $kondisi   = isset($col_map['kondisi_adm']) && isset($row[$col_map['kondisi_adm']]) ? trim((string)$row[$col_map['kondisi_adm']]) : 'Baik';
                $k_ruangan = isset($col_map['kode_ruangan']) && isset($row[$col_map['kode_ruangan']]) ? trim((string)$row[$col_map['kode_ruangan']]) : '';
                $n_ruangan = isset($col_map['nama_ruangan']) && isset($row[$col_map['nama_ruangan']]) ? trim((string)$row[$col_map['nama_ruangan']]) : '';

                // Konversi Tanggal
                $parsed_tgl = NULL;
                if ($raw_tgl !== '') {
                    // Jika format serial excel (misal 44197)
                    if (is_numeric($raw_tgl) && (float)$raw_tgl > 1000 && (float)$raw_tgl < 100000) {
                        $days = (int) floor((float)$raw_tgl);
                        $timestamp = ($days - 25569) * 86400;
                        if ($timestamp > 0) {
                            $parsed_tgl = date('Y-m-d', $timestamp);
                        }
                    } elseif (preg_match('/^\d{4}-\d{2}-\d{2}$/', $raw_tgl)) {
                        $parsed_tgl = $raw_tgl;
                    } elseif (preg_match('/^(\d{1,2})[\/\-\.](\d{1,2})[\/\-\.](\d{4})$/', $raw_tgl, $m)) {
                        // dd/mm/yyyy
                        $parsed_tgl = sprintf('%04d-%02d-%02d', $m[3], $m[2], $m[1]);
                    } elseif (strtotime($raw_tgl)) {
                        $parsed_tgl = date('Y-m-d', strtotime($raw_tgl));
                    } else {
                        $invalid_rows[] = array(
                            'row'    => $excel_row_num,
                            'alasan' => 'Format Tanggal Perolehan tidak valid: "' . html_escape($raw_tgl) . '". Gunakan format YYYY-MM-DD atau DD/MM/YYYY.'
                        );
                        continue;
                    }
                }

                // Normalisasi Kondisi ADM
                $kondisi_adm_clean = 'Baik';
                $kondisi_lower = strtolower($kondisi);
                if (strpos($kondisi_lower, 'rusak berat') !== FALSE || strpos($kondisi_lower, 'berat') !== FALSE) {
                    $kondisi_adm_clean = 'Rusak Berat';
                } elseif (strpos($kondisi_lower, 'rusak ringan') !== FALSE || strpos($kondisi_lower, 'ringan') !== FALSE || strpos($kondisi_lower, 'rusak') !== FALSE) {
                    $kondisi_adm_clean = 'Rusak Ringan';
                }

                $valid_rows[] = array(
                    'excel_row'         => $excel_row_num,
                    'nama_barang'       => $nama_barang,
                    'merk_tipe'         => $merk_tipe,
                    'perangkat_lunak'   => NULL,
                    'detektor'          => NULL,
                    'tanggal_perolehan' => $parsed_tgl,
                    'kondisi_adm'       => $kondisi_adm_clean,
                    'kode_ruangan'      => $k_ruangan,
                    'nama_ruangan'      => $n_ruangan,
                    'status'            => 'Aktif'
                );
            }

            // Simpan data tervalidasi sementara di session untuk konfirmasi import
            $this->session->set_userdata('import_alat_temp', $valid_rows);

            $this->data['halaman_aktif'] = 'alat';
            $this->data['judul_halaman'] = 'Preview Validasi Import Alat Laboratorium';
            $this->data['breadcrumbs']   = array(
                array('label' => 'Alat Laboratorium', 'url' => site_url('alat')),
                array('label' => 'Import', 'url' => site_url('alat/import')),
                array('label' => 'Preview', 'url' => '#')
            );

            $this->data['total_read']   = $total_read;
            $this->data['valid_rows']   = $valid_rows;
            $this->data['invalid_rows'] = $invalid_rows;

            $this->muat_tampilan('alat/preview_import');
            return;
        }

        $this->data['halaman_aktif'] = 'alat';
        $this->data['judul_halaman'] = 'Import Alat Laboratorium via Excel';
        $this->data['breadcrumbs']   = array(
            array('label' => 'Alat Laboratorium', 'url' => site_url('alat')),
            array('label' => 'Import Excel', 'url' => '#')
        );

        $this->muat_tampilan('alat/import');
    }

    /**
     * Konfirmasi eksekusi batch import ke database
     */
    public function konfirmasi_import()
    {
        $this->cek_hak_akses('alat_import');

        if ($this->input->method() !== 'post') {
            redirect('alat/import');
        }

        $items = $this->session->userdata('import_alat_temp');
        if (empty($items)) {
            $this->session->set_flashdata('pesan_gagal', 'Sesi import telah kedaluwarsa atau tidak ada data yang siap di-import. Silakan upload ulang file Excel.');
            redirect('alat/import');
        }

        // Jalankan import batch dengan transaksi dan auto-generate kode berurutan
        $import_result = $this->Model_Alat->import_batch($items);

        // Bersihkan sesi
        $this->session->unset_userdata('import_alat_temp');

        if ($import_result['sukses']) {
            $total_sukses  = $import_result['imported'];
            $total_dilewati = $import_result['skipped'];

            $this->catat_audit('IMPORT', 'Alat', array(
                'total_diimpor'  => $total_sukses,
                'total_dilewati' => $total_dilewati
            ));

            $pesan = "Berhasil mengimpor $total_sukses data inventaris alat laboratorium.";
            if ($total_dilewati > 0) {
                $pesan .= " Sebanyak $total_dilewati baris dilewati.";
            }

            $this->session->set_flashdata('pesan_sukses', $pesan);
            if (!empty($import_result['errors'])) {
                $this->session->set_flashdata('import_warnings', array_slice($import_result['errors'], 0, 10));
            }
            redirect('alat');
        } else {
            $this->session->set_flashdata('pesan_gagal', 'Gagal memproses import data: ' . implode(', ', $import_result['errors']));
            redirect('alat/import');
        }
    }
}
