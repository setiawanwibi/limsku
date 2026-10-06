<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Controller Template Form Pengujian
 * 
 * Menangani manajemen Master Data Template Form Pengujian Dinamis (12 Kimia + 5 Mikrobiologi).
 */
class Template extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_Template');
        $this->load->model('Model_Metode');
        $this->load->model('Model_Parameter');
    }

    /**
     * Daftar template form pengujian
     */
    public function index()
    {
        $this->cek_hak_akses('template_view');

        $this->data['halaman_aktif'] = 'template';
        $this->data['judul_halaman'] = 'Master Template Form Pengujian (17 Form)';
        $this->data['breadcrumbs']   = array(
            array('label' => 'Master Data', 'url' => '#'),
            array('label' => 'Template Form', 'url' => site_url('template'))
        );

        $this->data['daftar_template'] = $this->Model_Template->ambil_semua();

        $this->muat_tampilan('template/index');
    }

    /**
     * Form dan proses tambah template form baru
     */
    public function tambah()
    {
        $this->cek_hak_akses('template_create');

        if ($this->input->method() === 'post') {
            $this->form_validation->set_rules('kode_template', 'Kode Template', 'required|trim|is_unique[form_templates.kode_template]', array(
                'required'  => '%s wajib diisi.',
                'is_unique' => '%s sudah ada.'
            ));
            $this->form_validation->set_rules('nama_template', 'Nama Template', 'required|trim', array(
                'required' => '%s wajib diisi.'
            ));
            $this->form_validation->set_rules('skema_form', 'Skema Form (JSON)', 'required', array(
                'required' => '%s wajib diisi.'
            ));

            if ($this->form_validation->run() === TRUE) {
                $skema_json = $this->input->post('skema_form');
                if (json_decode($skema_json) === NULL) {
                    $this->session->set_flashdata('pesan_gagal', 'Format JSON Skema Form tidak valid.');
                    redirect('template/tambah');
                }

                $data_simpan = array(
                    'kode_template' => strtoupper($this->input->post('kode_template', TRUE)),
                    'nama_template' => $this->input->post('nama_template', TRUE),
                    'kategori'      => $this->input->post('kategori', TRUE) ?: 'Kimia',
                    'method_id'     => $this->input->post('method_id', TRUE) ?: NULL,
                    'parameter_id'  => $this->input->post('parameter_id', TRUE) ?: NULL,
                    'skema_form'    => $skema_json,
                    'status'        => $this->input->post('status', TRUE) ?: 'Aktif'
                );

                $template_id = $this->Model_Template->tambah($data_simpan);

                if ($template_id) {
                    $this->catat_audit('CREATE', 'Template', array(
                        'template_id'   => $template_id,
                        'kode_template' => $data_simpan['kode_template'],
                        'nama_template' => $data_simpan['nama_template']
                    ));

                    $this->session->set_flashdata('pesan_sukses', 'Template form pengujian baru berhasil ditambahkan.');
                    redirect('template');
                } else {
                    $this->session->set_flashdata('pesan_gagal', 'Gagal menambahkan template form pengujian.');
                }
            }
        }

        $this->data['halaman_aktif']    = 'template';
        $this->data['judul_halaman']    = 'Tambah Template Form Pengujian';
        $this->data['breadcrumbs']      = array(
            array('label' => 'Template Form', 'url' => site_url('template')),
            array('label' => 'Tambah', 'url' => '#')
        );
        $this->data['daftar_metode']    = $this->Model_Metode->ambil_aktif();
        $this->data['daftar_parameter'] = $this->Model_Parameter->ambil_aktif();

        $this->muat_tampilan('template/tambah');
    }

    /**
     * Form dan proses edit template form
     * 
     * @param int $id
     */
    public function edit($id = NULL)
    {
        $this->cek_hak_akses('template_edit');

        $tpl = $this->Model_Template->ambil_by_id($id);
        if (!$tpl) {
            show_404();
        }

        if ($this->input->method() === 'post') {
            $is_unique = ($this->input->post('kode_template') !== $tpl['kode_template']) ? '|is_unique[form_templates.kode_template]' : '';

            $this->form_validation->set_rules('kode_template', 'Kode Template', 'required|trim' . $is_unique, array(
                'required'  => '%s wajib diisi.',
                'is_unique' => '%s sudah ada.'
            ));
            $this->form_validation->set_rules('nama_template', 'Nama Template', 'required|trim', array(
                'required' => '%s wajib diisi.'
            ));
            $this->form_validation->set_rules('skema_form', 'Skema Form (JSON)', 'required');

            if ($this->form_validation->run() === TRUE) {
                $skema_json = $this->input->post('skema_form');
                if (json_decode($skema_json) === NULL) {
                    $this->session->set_flashdata('pesan_gagal', 'Format JSON Skema Form tidak valid.');
                    redirect('template/edit/' . $id);
                }

                $data_update = array(
                    'kode_template' => strtoupper($this->input->post('kode_template', TRUE)),
                    'nama_template' => $this->input->post('nama_template', TRUE),
                    'kategori'      => $this->input->post('kategori', TRUE) ?: 'Kimia',
                    'method_id'     => $this->input->post('method_id', TRUE) ?: NULL,
                    'parameter_id'  => $this->input->post('parameter_id', TRUE) ?: NULL,
                    'skema_form'    => $skema_json,
                    'status'        => $this->input->post('status', TRUE) ?: 'Aktif'
                );

                if ($this->Model_Template->ubah($id, $data_update)) {
                    $this->catat_audit('UPDATE', 'Template', array(
                        'template_id'   => $id,
                        'kode_template' => $data_update['kode_template'],
                        'nama_template' => $data_update['nama_template']
                    ));

                    $this->session->set_flashdata('pesan_sukses', 'Data template form pengujian berhasil diperbarui.');
                    redirect('template');
                } else {
                    $this->session->set_flashdata('pesan_gagal', 'Gagal memperbarui data template form.');
                }
            }
        }

        $this->data['halaman_aktif']    = 'template';
        $this->data['judul_halaman']    = 'Edit Template Form Pengujian';
        $this->data['breadcrumbs']      = array(
            array('label' => 'Template Form', 'url' => site_url('template')),
            array('label' => 'Edit', 'url' => '#')
        );
        $this->data['template']         = $tpl;
        $this->data['daftar_metode']    = $this->Model_Metode->ambil_aktif();
        $this->data['daftar_parameter'] = $this->Model_Parameter->ambil_aktif();

        $this->muat_tampilan('template/edit');
    }

    /**
     * Ubah status template (Aktif/Nonaktif)
     * 
     * @param int $id
     */
    public function status($id = NULL)
    {
        $this->cek_hak_akses('template_edit');

        $tpl = $this->Model_Template->ambil_by_id($id);
        if (!$tpl) {
            show_404();
        }

        $status_baru = ($tpl['status'] === 'Aktif') ? 'Nonaktif' : 'Aktif';

        if ($this->Model_Template->ubah_status($id, $status_baru)) {
            $this->catat_audit('UPDATE_STATUS', 'Template', array(
                'template_id'   => $id,
                'kode_template' => $tpl['kode_template'],
                'status_baru'   => $status_baru
            ));

            $this->session->set_flashdata('pesan_sukses', 'Status template ' . $tpl['kode_template'] . ' diubah menjadi ' . $status_baru . '.');
        } else {
            $this->session->set_flashdata('pesan_gagal', 'Gagal mengubah status template.');
        }

        redirect('template');
    }

    /**
     * Hapus template form
     * 
     * @param int $id
     */
    public function hapus($id = NULL)
    {
        $this->cek_hak_akses('template_delete');

        $tpl = $this->Model_Template->ambil_by_id($id);
        if (!$tpl) {
            show_404();
        }

        if ($this->Model_Template->hapus($id)) {
            $this->catat_audit('DELETE', 'Template', array(
                'template_id'   => $id,
                'kode_template' => $tpl['kode_template']
            ));

            $this->session->set_flashdata('pesan_sukses', 'Template form pengujian berhasil dihapus.');
        } else {
            $this->session->set_flashdata('pesan_gagal', 'Gagal menghapus template form pengujian.');
        }

        redirect('template');
    }

    /**
     * Inisialisasi 17 Preset Template Form Pengujian (12 Kimia + 5 Mikrobiologi)
     */
    public function inisialisasi_preset()
    {
        $this->cek_hak_akses('template_create');

        $presets = array(
            // 12 Form Kimia
            array('KIM-01', 'Form 1: Penetapan Kadar Obat (Kimia)', 'Kimia', array('kadar_zat_aktif' => array('label' => 'Kadar Zat Aktif (%)', 'type' => 'number', 'unit' => '%', 'required' => true), 'pembacaan_serapan' => array('label' => 'Serapan Absorbansi', 'type' => 'number', 'unit' => 'Abs', 'required' => true))),
            array('KIM-02', 'Form 2: Uji Disolusi (Kimia)', 'Kimia', array('kecepatan_putaran' => array('label' => 'Kecepatan Putaran (rpm)', 'type' => 'number', 'unit' => 'rpm', 'required' => true), 'persen_terlarut' => array('label' => '% Terlarut (Q)', 'type' => 'number', 'unit' => '%', 'required' => true))),
            array('KIM-03', 'Form 3: Uji Keseragaman Bobot / Ukuran (Kimia)', 'Kimia', array('bobot_rata_rata' => array('label' => 'Bobot Rata-rata (mg)', 'type' => 'number', 'unit' => 'mg', 'required' => true), 'penyimpangan_bobot' => array('label' => '% Penyimpangan', 'type' => 'number', 'unit' => '%', 'required' => true))),
            array('KIM-04', 'Form 4: Uji pH / Derajat Keasaman (Kimia)', 'Kimia', array('nilai_ph' => array('label' => 'Nilai pH Pengukuran', 'type' => 'number', 'unit' => 'pH', 'required' => true), 'suhu_pengukuran' => array('label' => 'Suhu Pengukuran (C)', 'type' => 'number', 'unit' => 'C', 'required' => true))),
            array('KIM-05', 'Form 5: Uji Kejernihan & Warna Larutan (Kimia)', 'Kimia', array('kejernihan' => array('label' => 'Hasil Kejernihan', 'type' => 'text', 'required' => true), 'warna_larutan' => array('label' => 'Pemeriksaan Warna', 'type' => 'text', 'required' => true))),
            array('KIM-06', 'Form 6: Uji Kadar Air / Susut Pengeringan (Kimia)', 'Kimia', array('susut_pengeringan' => array('label' => 'Susut Pengeringan (%)', 'type' => 'number', 'unit' => '%', 'required' => true))),
            array('KIM-07', 'Form 7: Uji Identifikasi Kualitatif (Kimia)', 'Kimia', array('metode_identifikasi' => array('label' => 'Metode Identifikasi (KLT/HPLC/Reaksi Warna)', 'type' => 'text', 'required' => true), 'hasil_identifikasi' => array('label' => 'Hasil Pengamatan Warna/Rf', 'type' => 'text', 'required' => true))),
            array('KIM-08', 'Form 8: Uji Kebocoran Kemasan (Kimia)', 'Kimia', array('metode_vakum' => array('label' => 'Tekanan Vakum (mmHg)', 'type' => 'number', 'unit' => 'mmHg'), 'kebocoran' => array('label' => 'Pengamatan Kebocoran', 'type' => 'text', 'required' => true))),
            array('KIM-09', 'Form 9: Uji Waktu Hancur Tablet (Kimia)', 'Kimia', array('waktu_hancur' => array('label' => 'Waktu Hancur (Menit)', 'type' => 'number', 'unit' => 'menit', 'required' => true), 'media_hancur' => array('label' => 'Media Hancur', 'type' => 'text'))),
            array('KIM-10', 'Form 10: Uji Viskositas & Bobot Jenis (Kimia)', 'Kimia', array('viskositas' => array('label' => 'Viskositas (cPoise)', 'type' => 'number', 'unit' => 'cP'), 'bobot_jenis' => array('label' => 'Bobot Jenis (g/mL)', 'type' => 'number', 'unit' => 'g/mL', 'required' => true))),
            array('KIM-11', 'Form 11: Uji Titik Lebur / Didih (Kimia)', 'Kimia', array('titik_lebur' => array('label' => 'Rentang Titik Lebur (C)', 'type' => 'text', 'required' => true))),
            array('KIM-12', 'Form 12: Uji Senyawa Sejenis / Impurities (Kimia)', 'Kimia', array('total_impurities' => array('label' => 'Total Impurities (%)', 'type' => 'number', 'unit' => '%', 'required' => true))),

            // 5 Form Mikrobiologi
            array('MIK-01', 'Form 13: Uji Sterilitas (Mikrobiologi)', 'Mikrobiologi', array('pengamatan_hari_7' => array('label' => 'Pengamatan Hari Ke-7', 'type' => 'text', 'required' => true), 'pengamatan_hari_14' => array('label' => 'Pengamatan Hari Ke-14', 'type' => 'text', 'required' => true), 'hasil_sterilitas' => array('label' => 'Hasil Uji Sterilitas', 'type' => 'text', 'required' => true))),
            array('MIK-02', 'Form 14: Uji Batas Mikroba / ALT & AKK (Mikrobiologi)', 'Mikrobiologi', array('alt_bakteri' => array('label' => 'Angka Lempeng Total / ALT (koloni/g)', 'type' => 'number', 'unit' => 'CFU/g', 'required' => true), 'akk_kapang' => array('label' => 'Angka Kapang Kamir / AKK (koloni/g)', 'type' => 'number', 'unit' => 'CFU/g', 'required' => true))),
            array('MIK-03', 'Form 15: Uji Potensi Antibiotik (Mikrobiologi)', 'Mikrobiologi', array('diameter_hambatan' => array('label' => 'Diameter Zona Hambat (mm)', 'type' => 'number', 'unit' => 'mm', 'required' => true), 'potensi_persen' => array('label' => 'Potensi Antibiotik (%)', 'type' => 'number', 'unit' => '%', 'required' => true))),
            array('MIK-04', 'Form 16: Uji Efektivitas Pengawet Antimikroba (Mikrobiologi)', 'Mikrobiologi', array('penurunan_log' => array('label' => 'Penurunan Log Koloni Mikroba', 'type' => 'number', 'required' => true))),
            array('MIK-05', 'Form 17: Uji Endotoksin Bakteri / LAL Test (Mikrobiologi)', 'Mikrobiologi', array('sensitivitas_lal' => array('label' => 'Sensitivitas Reagen LAL (EU/mL)', 'type' => 'number', 'unit' => 'EU/mL', 'required' => true), 'hasil_gelation' => array('label' => 'Hasil Pembentukan Gel (Positif/Negatif)', 'type' => 'text', 'required' => true)))
        );

        $inserted = 0;
        foreach ($presets as $p) {
            $existing = $this->db->get_where('form_templates', array('kode_template' => $p[0]))->row_array();
            if (!$existing) {
                $this->Model_Template->tambah(array(
                    'kode_template' => $p[0],
                    'nama_template' => $p[1],
                    'kategori'      => $p[2],
                    'skema_form'    => json_encode($p[3], JSON_UNESCAPED_UNICODE),
                    'status'        => 'Aktif'
                ));
                $inserted++;
            }
        }

        $this->catat_audit('INITIALIZE_PRESETS', 'Template', array('total_inserted' => $inserted));
        $this->session->set_flashdata('pesan_sukses', "Inisialisasi 17 Preset Template Form berhasil dilakukan ($inserted template baru ditambahkan).");
        redirect('template');
    }
}
