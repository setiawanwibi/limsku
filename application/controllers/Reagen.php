<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Reagen (Master Reagen Laboratorium)
 * 
 * Menangani manajemen Master Data Reagen Laboratorium BBPOM.
 * Mendukung 8 kolom standar: No, Kode Barang, Katalog, Nama Barang, Merk,
 * Stok (kuantitas + satuan), Jenis Barang, Expired Date.
 * Terintegrasi dengan fitur Impor Excel (.xlsx / .xls), template unduhan,
 * preview validasi data, dan penanganan duplikasi aman.
 */
class Reagen extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_Reagen');
    }

    /**
     * Halaman daftar Master Reagen
     */
    public function index()
    {
        $this->cek_hak_akses('reagen_view');

        $status  = $this->input->get('status', TRUE);
        $jenis   = $this->input->get('jenis', TRUE);
        $keyword = $this->input->get('q', TRUE);

        $this->data['halaman_aktif'] = 'reagen';
        $this->data['judul_halaman'] = 'Master Reagen Laboratorium';

        $this->data['status_aktif']  = $status;
        $this->data['jenis_aktif']   = $jenis;
        $this->data['keyword_aktif'] = $keyword;
        $this->data['daftar_jenis']  = $this->Model_Reagen->ambil_daftar_jenis();
        $this->data['daftar_reagen'] = $this->Model_Reagen->ambil_semua($status, $jenis, $keyword);

        $this->muat_tampilan('reagen/index');
    }

    /**
     * Form dan proses tambah reagen baru
     */
    public function tambah()
    {
        $this->cek_hak_akses('reagen_create');

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('nama_barang', 'Nama Barang', 'required|trim|max_length[255]', array(
                'required'   => '%s wajib diisi.',
                'max_length' => '%s maksimal 255 karakter.'
            ));
            $this->form_validation->set_rules('kode_barang', 'Kode Barang', 'trim|max_length[50]', array(
                'max_length' => '%s maksimal 50 karakter.'
            ));
            $this->form_validation->set_rules('katalog', 'Katalog', 'trim|max_length[100]', array(
                'max_length' => '%s maksimal 100 karakter.'
            ));
            $this->form_validation->set_rules('merk', 'Merk', 'trim|max_length[150]', array(
                'max_length' => '%s maksimal 150 karakter.'
            ));
            $this->form_validation->set_rules('stok_jumlah', 'Jumlah Stok', 'trim|numeric', array(
                'numeric' => '%s harus berupa angka.'
            ));
            $this->form_validation->set_rules('stok_satuan', 'Satuan Stok', 'trim|max_length[20]', array(
                'max_length' => '%s maksimal 20 karakter.'
            ));
            $this->form_validation->set_rules('jenis_barang', 'Jenis Barang', 'trim|max_length[100]', array(
                'max_length' => '%s maksimal 100 karakter.'
            ));
            $this->form_validation->set_rules('expired_date', 'Expired Date', 'trim|regex_match[/^\d{4}-\d{2}-\d{2}$/]', array(
                'regex_match' => '%s harus berformat YYYY-MM-DD.'
            ));
            $this->form_validation->set_rules('status', 'Status', 'trim|in_list[Aktif,Nonaktif]', array(
                'in_list' => '%s harus bernilai Aktif atau Nonaktif.'
            ));

            if ($this->form_validation->run() === TRUE) {
                $jml_stok    = $this->input->post('stok_jumlah', TRUE);
                $satuan_stok = trim((string)$this->input->post('stok_satuan', TRUE));
                if ($jml_stok !== '' && $jml_stok !== NULL) {
                    $stok_gabung = $jml_stok . ($satuan_stok !== '' ? ' ' . strtoupper($satuan_stok) : '');
                } else {
                    $stok_gabung = '0 BOTOL';
                }

                $exp_date = $this->input->post('expired_date', TRUE);
                if (empty($exp_date) || $exp_date === '0000-00-00') {
                    $exp_date = NULL;
                }

                $data_simpan = array(
                    'kode_barang'  => $this->input->post('kode_barang', TRUE) ?: NULL,
                    'katalog'      => $this->input->post('katalog', TRUE) ?: NULL,
                    'nama_barang'  => $this->input->post('nama_barang', TRUE),
                    'nama_reagen'  => substr($this->input->post('nama_barang', TRUE), 0, 150),
                    'merk'         => $this->input->post('merk', TRUE) ?: NULL,
                    'stok'         => $stok_gabung,
                    'jenis_barang' => $this->input->post('jenis_barang', TRUE) ?: 'Reagen Cair',
                    'expired_date' => $exp_date,
                    'status'       => $this->input->post('status', TRUE) ?: 'Aktif'
                );

                $reagen_id = $this->Model_Reagen->tambah($data_simpan);

                if ($reagen_id) {
                    $this->catat_audit('CREATE', 'Reagen', array(
                        'reagen_id'   => $reagen_id,
                        'nama_barang' => $data_simpan['nama_barang'],
                        'kode_barang' => $data_simpan['kode_barang'],
                        'stok'        => $data_simpan['stok']
                    ));

                    $this->session->set_flashdata('pesan_sukses', 'Reagen baru "' . html_escape($data_simpan['nama_barang']) . '" berhasil ditambahkan.');
                    redirect('reagen');
                } else {
                    $this->session->set_flashdata('pesan_gagal', 'Gagal menambahkan data reagen.');
                }
            }
        }

        $this->data['halaman_aktif'] = 'reagen';
        $this->data['judul_halaman'] = 'Tambah Master Reagen';
        $this->data['breadcrumbs']   = array(
            array('label' => 'Master Reagen', 'url' => site_url('reagen')),
            array('label' => 'Tambah', 'url' => '#')
        );

        $this->muat_tampilan('reagen/tambah');
    }

    /**
     * Halaman detail data reagen laboratorium
     * 
     * @param int $id
     */
    public function detail($id = NULL)
    {
        $this->cek_hak_akses('reagen_view');

        $reagen = $this->Model_Reagen->ambil_by_id($id);
        if (!$reagen) {
            show_404();
        }

        $this->data['halaman_aktif'] = 'reagen';
        $this->data['judul_halaman'] = 'Detail Reagen: ' . html_escape(!empty($reagen['nama_barang']) ? $reagen['nama_barang'] : $reagen['nama_reagen']);
        $this->data['breadcrumbs']   = array(
            array('label' => 'Master Reagen', 'url' => site_url('reagen')),
            array('label' => 'Detail', 'url' => '#')
        );
        $this->data['reagen']        = $reagen;

        $this->muat_tampilan('reagen/detail');
    }

    /**
     * Form dan proses ubah data reagen laboratorium
     * 
     * @param int $id
     */
    public function edit($id = NULL)
    {
        $this->cek_hak_akses('reagen_edit');

        $reagen = $this->Model_Reagen->ambil_by_id($id);
        if (!$reagen) {
            show_404();
        }

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('nama_barang', 'Nama Barang', 'required|trim|max_length[255]', array(
                'required'   => '%s wajib diisi.',
                'max_length' => '%s maksimal 255 karakter.'
            ));
            $this->form_validation->set_rules('kode_barang', 'Kode Barang', 'trim|max_length[50]', array(
                'max_length' => '%s maksimal 50 karakter.'
            ));
            $this->form_validation->set_rules('katalog', 'Katalog', 'trim|max_length[100]', array(
                'max_length' => '%s maksimal 100 karakter.'
            ));
            $this->form_validation->set_rules('merk', 'Merk', 'trim|max_length[150]', array(
                'max_length' => '%s maksimal 150 karakter.'
            ));
            $this->form_validation->set_rules('stok_jumlah', 'Jumlah Stok', 'trim|numeric', array(
                'numeric' => '%s harus berupa angka.'
            ));
            $this->form_validation->set_rules('stok_satuan', 'Satuan Stok', 'trim|max_length[20]', array(
                'max_length' => '%s maksimal 20 karakter.'
            ));
            $this->form_validation->set_rules('jenis_barang', 'Jenis Barang', 'trim|max_length[100]', array(
                'max_length' => '%s maksimal 100 karakter.'
            ));
            $this->form_validation->set_rules('expired_date', 'Expired Date', 'trim|regex_match[/^\d{4}-\d{2}-\d{2}$/]', array(
                'regex_match' => '%s harus berformat YYYY-MM-DD.'
            ));
            $this->form_validation->set_rules('status', 'Status', 'trim|in_list[Aktif,Nonaktif]', array(
                'in_list' => '%s harus bernilai Aktif atau Nonaktif.'
            ));

            if ($this->form_validation->run() === TRUE) {
                $jml_stok    = $this->input->post('stok_jumlah', TRUE);
                $satuan_stok = trim((string)$this->input->post('stok_satuan', TRUE));
                if ($jml_stok !== '' && $jml_stok !== NULL) {
                    $stok_gabung = $jml_stok . ($satuan_stok !== '' ? ' ' . strtoupper($satuan_stok) : '');
                } else {
                    $stok_gabung = '0 BOTOL';
                }

                $exp_date = $this->input->post('expired_date', TRUE);
                if (empty($exp_date) || $exp_date === '0000-00-00') {
                    $exp_date = NULL;
                }

                $data_update = array(
                    'kode_barang'  => $this->input->post('kode_barang', TRUE) ?: NULL,
                    'katalog'      => $this->input->post('katalog', TRUE) ?: NULL,
                    'nama_barang'  => $this->input->post('nama_barang', TRUE),
                    'nama_reagen'  => substr($this->input->post('nama_barang', TRUE), 0, 150),
                    'merk'         => $this->input->post('merk', TRUE) ?: NULL,
                    'stok'         => $stok_gabung,
                    'jenis_barang' => $this->input->post('jenis_barang', TRUE) ?: 'Reagen Cair',
                    'expired_date' => $exp_date,
                    'status'       => $this->input->post('status', TRUE) ?: 'Aktif'
                );

                if ($this->Model_Reagen->ubah($id, $data_update)) {
                    $this->catat_audit('UPDATE', 'Reagen', array(
                        'reagen_id'   => $id,
                        'nama_barang' => $data_update['nama_barang'],
                        'kode_barang' => $data_update['kode_barang'],
                        'stok'        => $data_update['stok']
                    ));

                    $this->session->set_flashdata('pesan_sukses', 'Data reagen "' . html_escape($data_update['nama_barang']) . '" berhasil diperbarui.');
                    redirect('reagen');
                } else {
                    $this->session->set_flashdata('pesan_gagal', 'Gagal memperbarui data reagen.');
                }
            }
        }

        $this->data['halaman_aktif'] = 'reagen';
        $this->data['judul_halaman'] = 'Edit Master Reagen';
        $this->data['breadcrumbs']   = array(
            array('label' => 'Master Reagen', 'url' => site_url('reagen')),
            array('label' => 'Edit', 'url' => '#')
        );
        $this->data['reagen']        = $reagen;

        $this->muat_tampilan('reagen/edit');
    }

    /**
     * Ubah status reagen (Aktif / Nonaktif)
     * 
     * @param int $id
     */
    public function status($id = NULL)
    {
        $this->cek_hak_akses('reagen_edit');

        $reagen = $this->Model_Reagen->ambil_by_id($id);
        if (!$reagen) {
            show_404();
        }

        $status_baru = ($reagen['status'] === 'Aktif') ? 'Nonaktif' : 'Aktif';

        if ($this->Model_Reagen->ubah_status($id, $status_baru)) {
            $nama_label = !empty($reagen['nama_barang']) ? $reagen['nama_barang'] : $reagen['nama_reagen'];
            $this->catat_audit('STATUS_CHANGE', 'Reagen', array(
                'reagen_id'   => $id,
                'nama_barang' => $nama_label,
                'status_lama' => $reagen['status'],
                'status_baru' => $status_baru
            ));

            $this->session->set_flashdata('pesan_sukses', 'Status reagen "' . html_escape($nama_label) . '" diubah menjadi ' . $status_baru . '.');
        } else {
            $this->session->set_flashdata('pesan_gagal', 'Gagal mengubah status reagen.');
        }

        redirect('reagen');
    }

    /**
     * Hapus data reagen secara aman
     * 
     * @param int $id
     */
    public function hapus($id = NULL)
    {
        $this->cek_hak_akses('reagen_delete');

        $reagen = $this->Model_Reagen->ambil_by_id($id);
        if (!$reagen) {
            show_404();
        }

        $nama_label = !empty($reagen['nama_barang']) ? $reagen['nama_barang'] : $reagen['nama_reagen'];

        if ($this->Model_Reagen->hapus($id)) {
            $this->catat_audit('DELETE', 'Reagen', array(
                'reagen_id'   => $id,
                'nama_barang' => $nama_label
            ));

            $this->session->set_flashdata('pesan_sukses', 'Data reagen "' . html_escape($nama_label) . '" berhasil dihapus.');
        } else {
            $this->session->set_flashdata('pesan_gagal', 'Gagal menghapus data reagen.');
        }

        redirect('reagen');
    }

    /**
     * Download Template File Excel (.xlsx) untuk Master Reagen
     * Memiliki 8 kolom resmi: No, Kode Barang, Katalog, Nama Barang, Merk, Stok, Jenis Barang, Expired Date
     */
    public function download_template()
    {
        $this->cek_hak_akses('reagen_import');

        $headers = array('No', 'Kode Barang', 'Katalog', 'Nama Barang', 'Merk', 'Stok', 'Jenis Barang', 'Expired Date');
        $samples = array(
            array('1', 'BRG-001', 'KAT-1001', 'Asam Sulfat H2SO4 p.a 98%', 'Merck', '2 BOTOL', 'Reagen Cair', '2027-12-31'),
            array('2', 'BRG-002', 'KAT-1002', 'Nutrient Agar (NA) Media Bubuk', 'Oxoid', '1 PAC', 'Media Mikrobiologi', '2028-06-30'),
            array('3', 'BRG-003', 'KAT-1003', 'Indikator Fenolftalein (PP) 1%', 'Smart-Lab', '0 BOTOL', 'Indikator Kimia', '')
        );

        $tempFile = sys_get_temp_dir() . '/template_reagen_' . time() . '.xlsx';
        $zip = new ZipArchive();
        if ($zip->open($tempFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            $zip->addFromString('[Content_Types].xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">
  <Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>
  <Default Extension="xml" ContentType="application/xml"/>
  <Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>
  <Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>
  <Override PartName="/xl/sharedStrings.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sharedStrings+xml"/>
</Types>');

            $zip->addFromString('_rels/.rels', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>
</Relationships>');

            $zip->addFromString('xl/_rels/workbook.xml.rels', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>
  <Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/sharedStrings" Target="sharedStrings.xml"/>
</Relationships>');

            $zip->addFromString('xl/workbook.xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">
  <sheets>
    <sheet name="Master Reagen" sheetId="1" r:id="rId1"/>
  </sheets>
</workbook>');

            $all_strings = $headers;
            foreach ($samples as $s_row) {
                foreach ($s_row as $val) {
                    if ($val !== '') {
                        $all_strings[] = $val;
                    }
                }
            }
            $unique_strings = array_values(array_unique($all_strings));
            $str_map = array_flip($unique_strings);

            $sstXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";
            $sstXml .= '<sst xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" count="' . count($all_strings) . '" uniqueCount="' . count($unique_strings) . '">';
            foreach ($unique_strings as $s) {
                $sstXml .= '<si><t>' . htmlspecialchars($s, ENT_XML1, 'UTF-8') . '</t></si>';
            }
            $sstXml .= '</sst>';
            $zip->addFromString('xl/sharedStrings.xml', $sstXml);

            $cols = array('A','B','C','D','E','F','G','H');
            $sheetXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";
            $sheetXml .= '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><sheetData>';

            // Header row (Row 1)
            $sheetXml .= '<row r="1">';
            foreach ($headers as $ci => $h) {
                $ref = $cols[$ci] . '1';
                $sheetXml .= '<c r="' . $ref . '" t="s"><v>' . $str_map[$h] . '</v></c>';
            }
            $sheetXml .= '</row>';

            // Data rows (Row 2, 3, 4)
            foreach ($samples as $ri => $sample_row) {
                $row_idx = $ri + 2;
                $sheetXml .= '<row r="' . $row_idx . '">';
                foreach ($sample_row as $ci => $val) {
                    $ref = $cols[$ci] . $row_idx;
                    if ($val === '') {
                        $sheetXml .= '<c r="' . $ref . '"/>';
                    } else {
                        $sheetXml .= '<c r="' . $ref . '" t="s"><v>' . $str_map[$val] . '</v></c>';
                    }
                }
                $sheetXml .= '</row>';
            }

            $sheetXml .= '</sheetData></worksheet>';
            $zip->addFromString('xl/worksheets/sheet1.xml', $sheetXml);
            $zip->close();

            $filename = 'Template_Import_Master_Reagen_LIMSKU.xlsx';
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Content-Length: ' . filesize($tempFile));
            header('Cache-Control: max-age=0');
            readfile($tempFile);
            unlink($tempFile);
            exit;
        } else {
            $this->session->set_flashdata('pesan_gagal', 'Gagal membuat file template Excel.');
            redirect('reagen/import');
        }
    }

    /**
     * Halaman Upload & Pratinjau File Excel Master Reagen
     */
    public function import()
    {
        $this->cek_hak_akses('reagen_import');

        if ($this->input->method() === 'post') {
            if (empty($_FILES['file_excel']['name'])) {
                $this->session->set_flashdata('pesan_gagal', 'Silakan pilih file spreadsheet Excel (.xlsx) yang akan diunggah.');
                redirect('reagen/import');
            }

            // Validasi Error Code PHP Upload
            if (isset($_FILES['file_excel']['error']) && $_FILES['file_excel']['error'] !== UPLOAD_ERR_OK) {
                $err_code = $_FILES['file_excel']['error'];
                if ($err_code === UPLOAD_ERR_INI_SIZE || $err_code === UPLOAD_ERR_FORM_SIZE) {
                    $msg = 'Ukuran file melebihi batas maksimum server (maksimal 5 MB).';
                } elseif ($err_code === UPLOAD_ERR_PARTIAL) {
                    $msg = 'File hanya terunggah sebagian. Silakan coba lagi.';
                } elseif ($err_code === UPLOAD_ERR_NO_FILE) {
                    $msg = 'Tidak ada file yang dipilih untuk diunggah.';
                } else {
                    $msg = 'Terjadi kesalahan saat mengunggah file (Kode error upload: ' . (int)$err_code . ').';
                }
                $this->session->set_flashdata('pesan_gagal', $msg);
                redirect('reagen/import');
            }

            // Validasi Ukuran File (Maksimal 5 MB = 5.242.880 bytes)
            if (isset($_FILES['file_excel']['size']) && (int)$_FILES['file_excel']['size'] > 5242880) {
                $this->session->set_flashdata('pesan_gagal', 'Ukuran file melebihi batas maksimum 5 MB. Harap unggah file yang lebih ringkas.');
                redirect('reagen/import');
            }

            // Validasi Ekstensi File: Hanya .xlsx (OpenXML). Format .xls biner lama tidak didukung.
            $ext = strtolower(pathinfo($_FILES['file_excel']['name'], PATHINFO_EXTENSION));
            if ($ext !== 'xlsx') {
                $this->session->set_flashdata('pesan_gagal', 'Format file tidak didukung. Harap unggah file spreadsheet Excel dengan format .xlsx (format .xls biner lama tidak didukung).');
                redirect('reagen/import');
            }

            $tmpPath = $_FILES['file_excel']['tmp_name'];
            $this->load->library('SimpleXLSX');

            $xlsx = SimpleXLSX::parse($tmpPath);
            if (!$xlsx) {
                $this->session->set_flashdata('pesan_gagal', 'Gagal membaca file Excel: ' . SimpleXLSX::parseError());
                redirect('reagen/import');
            }

            $rows = $xlsx->rows();
            if (empty($rows) || count($rows) < 2) {
                $this->session->set_flashdata('pesan_gagal', 'File Excel kosong atau tidak memiliki baris data.');
                redirect('reagen/import');
            }

            // 1. Temukan baris header secara fleksibel
            $header_idx = -1;
            $max_header_scan = min(15, count($rows));

            for ($i = 0; $i < $max_header_scan; $i++) {
                $row_str = strtolower(implode(' ', array_filter($rows[$i])));
                if (strpos($row_str, 'nama barang') !== FALSE || strpos($row_str, 'kode barang') !== FALSE || strpos($row_str, 'nama reagen') !== FALSE) {
                    $header_idx = $i;
                    break;
                }
            }

            if ($header_idx === -1) {
                $header_idx = 0;
            }

            $header_row = $rows[$header_idx];

            // 2. Pemetaan kolom toleran
            $col_map = array();
            foreach ($header_row as $c_idx => $c_name) {
                $norm = strtolower(trim((string)$c_name));
                $norm = preg_replace('/[^\w\s]/u', ' ', $norm);
                $norm = preg_replace('/\s+/', ' ', $norm);
                $norm = trim($norm);

                if (empty($norm)) continue;

                if (strpos($norm, 'nama barang') !== FALSE || strpos($norm, 'nama reagen') !== FALSE || $norm === 'barang') {
                    $col_map['nama_barang'] = $c_idx;
                } elseif (strpos($norm, 'kode barang') !== FALSE || strpos($norm, 'kode') !== FALSE) {
                    $col_map['kode_barang'] = $c_idx;
                } elseif (strpos($norm, 'katalog') !== FALSE || strpos($norm, 'catalog') !== FALSE || strpos($norm, 'cat no') !== FALSE) {
                    $col_map['katalog'] = $c_idx;
                } elseif (strpos($norm, 'merk') !== FALSE || strpos($norm, 'merek') !== FALSE || strpos($norm, 'brand') !== FALSE) {
                    $col_map['merk'] = $c_idx;
                } elseif (strpos($norm, 'stok') !== FALSE || strpos($norm, 'stock') !== FALSE || strpos($norm, 'jumlah') !== FALSE) {
                    $col_map['stok'] = $c_idx;
                } elseif (strpos($norm, 'jenis barang') !== FALSE || strpos($norm, 'jenis') !== FALSE || strpos($norm, 'kategori') !== FALSE) {
                    $col_map['jenis_barang'] = $c_idx;
                } elseif (strpos($norm, 'expired date') !== FALSE || strpos($norm, 'expired') !== FALSE || strpos($norm, 'kadaluarsa') !== FALSE || strpos($norm, 'kedaluwarsa') !== FALSE || strpos($norm, 'exp date') !== FALSE) {
                    $col_map['expired_date'] = $c_idx;
                }
            }

            if (!isset($col_map['nama_barang'])) {
                $this->session->set_flashdata('pesan_gagal', 'Header kolom "Nama Barang" tidak ditemukan pada file Excel. Pastikan file memiliki kolom Nama Barang.');
                redirect('reagen/import');
            }

            // 3. Baca lookup duplikasi database untuk deteksi pratinjau
            $lookup = $this->Model_Reagen->ambil_lookup_duplikasi();

            $valid_rows     = array();
            $invalid_rows   = array();
            $duplicate_rows = array();
            $in_file_lookup = array();
            $total_read     = 0;

            for ($r = $header_idx + 1; $r < count($rows); $r++) {
                $row = $rows[$r];
                $excel_row_num = $r + 1;

                // Cek baris kosong total
                $has_val = FALSE;
                foreach ($row as $cell) {
                    if (trim((string)$cell) !== '') {
                        $has_val = TRUE;
                        break;
                    }
                }
                if (!$has_val) {
                    continue;
                }

                $total_read++;

                $nama_barang = isset($col_map['nama_barang']) && isset($row[$col_map['nama_barang']]) ? trim((string)$row[$col_map['nama_barang']]) : '';

                // Validasi kolom wajib: Nama Barang
                if ($nama_barang === '') {
                    $invalid_rows[] = array(
                        'row'    => $excel_row_num,
                        'alasan' => 'Kolom wajib "Nama Barang" kosong.'
                    );
                    continue;
                }

                $kode_barang  = isset($col_map['kode_barang']) && isset($row[$col_map['kode_barang']]) ? trim((string)$row[$col_map['kode_barang']]) : '';
                $katalog      = isset($col_map['katalog']) && isset($row[$col_map['katalog']]) ? trim((string)$row[$col_map['katalog']]) : '';
                $merk         = isset($col_map['merk']) && isset($row[$col_map['merk']]) ? trim((string)$row[$col_map['merk']]) : '';
                $raw_stok     = isset($col_map['stok']) && isset($row[$col_map['stok']]) ? trim((string)$row[$col_map['stok']]) : '';
                $jenis_barang = isset($col_map['jenis_barang']) && isset($row[$col_map['jenis_barang']]) ? trim((string)$row[$col_map['jenis_barang']]) : 'Reagen Cair';
                $raw_exp      = isset($col_map['expired_date']) && isset($row[$col_map['expired_date']]) ? trim((string)$row[$col_map['expired_date']]) : '';

                // Normalisasi Stok (mempertahankan kuantitas dan satuan, misal "1 PAC", "3 PAC", "0 BOTOL")
                $clean_stok = '0 BOTOL';
                if ($raw_stok !== '') {
                    // Jika hanya angka numerik tanpa satuan, defaultkan satuan sesuai jenis
                    if (is_numeric($raw_stok)) {
                        $clean_stok = $raw_stok . ' BOTOL';
                    } else {
                        // Pertahankan teks stok asli dan rapikan spasi berlebih
                        $clean_stok = preg_replace('/\s+/', ' ', trim($raw_stok));
                    }
                }

                // Normalisasi Expired Date
                $parsed_exp = NULL;
                if ($raw_exp !== '') {
                    // Jika serial Excel
                    if (is_numeric($raw_exp) && (float)$raw_exp > 1000 && (float)$raw_exp < 100000) {
                        $days = (int) floor((float)$raw_exp);
                        $timestamp = ($days - 25569) * 86400;
                        if ($timestamp > 0) {
                            $parsed_exp = date('Y-m-d', $timestamp);
                        }
                    } elseif (preg_match('/^\d{4}-\d{2}-\d{2}$/', $raw_exp)) {
                        $parsed_exp = $raw_exp;
                    } elseif (preg_match('/^(\d{1,2})[\/\-\.](\d{1,2})[\/\-\.](\d{4})$/', $raw_exp, $m)) {
                        $parsed_exp = sprintf('%04d-%02d-%02d', $m[3], $m[2], $m[1]);
                    } elseif (strtotime($raw_exp)) {
                        $parsed_exp = date('Y-m-d', strtotime($raw_exp));
                    } else {
                        $invalid_rows[] = array(
                            'row'    => $excel_row_num,
                            'alasan' => 'Format Expired Date tidak valid: "' . html_escape($raw_exp) . '". Gunakan format YYYY-MM-DD atau DD/MM/YYYY.'
                        );
                        continue;
                    }
                }

                // Deteksi Duplikasi
                $is_duplicate = FALSE;
                $dup_reason   = '';

                $k_upper   = strtoupper($kode_barang);
                $kat_upper = strtoupper($katalog);
                $comp_key  = strtoupper($nama_barang) . '|' . strtoupper($merk);

                if ($k_upper !== '' && isset($lookup['by_kode'][$k_upper])) {
                    $is_duplicate = TRUE;
                    $dup_reason   = 'Kode Barang "' . html_escape($kode_barang) . '" sudah ada di database.';
                } elseif ($kat_upper !== '' && isset($lookup['by_katalog'][$kat_upper])) {
                    $is_duplicate = TRUE;
                    $dup_reason   = 'Nomor Katalog "' . html_escape($katalog) . '" sudah ada di database.';
                } elseif (isset($lookup['by_composite'][$comp_key])) {
                    $is_duplicate = TRUE;
                    $dup_reason   = 'Nama Barang & Merk sudah ada di database.';
                } elseif (isset($in_file_lookup[$comp_key])) {
                    $is_duplicate = TRUE;
                    $dup_reason   = 'Duplikat dengan baris ' . $in_file_lookup[$comp_key] . ' pada file yang sama.';
                }

                $in_file_lookup[$comp_key] = $excel_row_num;

                $row_data = array(
                    'excel_row'    => $excel_row_num,
                    'kode_barang'  => $kode_barang,
                    'katalog'      => $katalog,
                    'nama_barang'  => $nama_barang,
                    'merk'         => $merk,
                    'stok'         => $clean_stok,
                    'jenis_barang' => $jenis_barang,
                    'expired_date' => $parsed_exp,
                    'is_duplicate' => $is_duplicate,
                    'dup_reason'   => $dup_reason
                );

                if ($is_duplicate) {
                    $duplicate_rows[] = $row_data;
                }
                $valid_rows[] = $row_data;
            }

            // Simpan data tervalidasi sementara di session untuk konfirmasi import
            $this->session->set_userdata('import_reagen_temp', $valid_rows);

            $this->data['halaman_aktif']   = 'reagen';
            $this->data['judul_halaman']   = 'Pratinjau Validasi Import Master Reagen';
            $this->data['breadcrumbs']     = array(
                array('label' => 'Master Reagen', 'url' => site_url('reagen')),
                array('label' => 'Import', 'url' => site_url('reagen/import')),
                array('label' => 'Pratinjau', 'url' => '#')
            );

            $this->data['total_read']     = $total_read;
            $this->data['valid_rows']     = $valid_rows;
            $this->data['invalid_rows']   = $invalid_rows;
            $this->data['duplicate_rows'] = $duplicate_rows;

            $this->muat_tampilan('reagen/preview_import');
            return;
        }

        $this->data['halaman_aktif'] = 'reagen';
        $this->data['judul_halaman'] = 'Import Master Reagen via Excel';
        $this->data['breadcrumbs']   = array(
            array('label' => 'Master Reagen', 'url' => site_url('reagen')),
            array('label' => 'Import Excel', 'url' => '#')
        );

        $this->muat_tampilan('reagen/import');
    }

    /**
     * Konfirmasi eksekusi batch import ke database
     */
    public function konfirmasi_import()
    {
        $this->cek_hak_akses('reagen_import');

        if ($this->input->method() !== 'post') {
            redirect('reagen/import');
        }

        $items = $this->session->userdata('import_reagen_temp');
        if (empty($items)) {
            $this->session->set_flashdata('pesan_gagal', 'Sesi import telah kedaluwarsa atau tidak ada data yang siap diimpor. Silakan upload ulang file Excel.');
            redirect('reagen/import');
        }

        $duplicate_action = $this->input->post('duplicate_action', TRUE);
        if (!in_array($duplicate_action, array('skip', 'update'), TRUE)) {
            $duplicate_action = 'skip';
        }

        // Jalankan import batch dengan transaksi database
        $import_result = $this->Model_Reagen->import_batch($items, $duplicate_action);

        // Bersihkan sesi
        $this->session->unset_userdata('import_reagen_temp');

        if ($import_result['sukses']) {
            $total_sukses   = $import_result['imported'];
            $total_diupdate = $import_result['updated'];
            $total_dilewati = $import_result['skipped'];

            $this->catat_audit('IMPORT', 'Reagen', array(
                'total_diimpor'    => $total_sukses,
                'total_diupdate'   => $total_diupdate,
                'total_dilewati'   => $total_dilewati,
                'duplicate_action' => $duplicate_action
            ));

            $pesan = "Berhasil memproses data master reagen: $total_sukses data baru diimpor";
            if ($total_diupdate > 0) {
                $pesan .= ", $total_diupdate data diperbarui";
            }
            if ($total_dilewati > 0) {
                $pesan .= ", $total_dilewati data duplikat dilewati";
            }
            $pesan .= '.';

            $this->session->set_flashdata('pesan_sukses', $pesan);
            redirect('reagen');
        } else {
            $this->session->set_flashdata('pesan_gagal', 'Gagal memproses import data: ' . implode(', ', $import_result['errors']));
            redirect('reagen/import');
        }
    }
}
