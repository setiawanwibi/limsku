<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Pengujian LIMSKU
 * 
 * Menangani Antrean Pengujian, Sesi Pengujian Multi-Form (Kimia & Mikrobiologi),
 * Self Assignment, Penyimpanan Hasil Uji, dan Transisi Status Workflow.
 */
class Model_Pengujian extends CI_Model
{
    protected $tabel_hasil = 'test_results';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Mengambil daftar sampel yang memiliki slot pengujian belum diambil (Antrean Pengujian).
     * Mendukung multi-bidang (Kimia & Mikrobiologi) dan penyekatan komoditi:
     * 
     * @param array|string|null $komoditi_izin
     * @return array
     */
    public function ambil_antrean($komoditi_izin = NULL)
    {
        $this->db->select('s.*, uc.nama_lengkap as nama_pembuat');
        $this->db->from('samples s');
        $this->db->join('users uc', 'uc.id = s.created_by', 'left');
        // Jangan tampilkan jika sampel sudah final atau ditolak
        $this->db->where_not_in('s.status', array('Approved / Final', 'Ditolak'));

        // Penyekatan Komoditi untuk Penguji
        if (!empty($komoditi_izin) && $komoditi_izin !== 'all') {
            $izins = is_array($komoditi_izin) ? $komoditi_izin : array($komoditi_izin);
            if (!in_array('all', $izins) && !in_array('all_sampel', $izins)) {
                $this->db->group_start();
                $this->db->where_in('s.komoditi', $izins);
                if (in_array('obat', $izins)) {
                    $this->db->or_where('s.komoditi IS NULL', NULL, FALSE);
                    $this->db->or_where('s.komoditi', '');
                }
                $this->db->group_end();
            }
        }

        $this->db->order_by('s.id', 'DESC');
        $daftar_sampel = $this->db->get()->result_array();

        if (empty($daftar_sampel)) {
            return array();
        }

        $sample_ids = array_column($daftar_sampel, 'id');
        $this->db->select('id, sample_id, penguji_id, jenis_pengujian, status');
        $this->db->from('testing_sessions');
        $this->db->where_in('sample_id', $sample_ids);
        $daftar_sesi = $this->db->get()->result_array();

        // Kelompokkan sesi per sample_id
        $sesi_map = array();
        foreach ($daftar_sesi as $sesi) {
            $sid = (int)$sesi['sample_id'];
            if (!isset($sesi_map[$sid])) {
                $sesi_map[$sid] = array();
            }
            $sesi_map[$sid][] = $sesi;
        }

        $antrean_tersedia = array();
        foreach ($daftar_sampel as $s) {
            $sid = (int)$s['id'];
            $sesi_list = isset($sesi_map[$sid]) ? $sesi_map[$sid] : array();

            // Evaluasi kebutuhan
            $eval_k = $this->evaluasi_kebutuhan_pengujian(isset($s['jumlah_kimia']) ? $s['jumlah_kimia'] : NULL);
            $eval_m = $this->evaluasi_kebutuhan_pengujian(isset($s['jumlah_mikro']) ? $s['jumlah_mikro'] : NULL);

            $wajib_kimia = $eval_k['is_wajib'];
            $wajib_mikro = $eval_m['is_wajib'];

            // Jika keduanya tidak diwajibkan secara eksplisit, cek apakah status lama masih Menunggu Pengujian
            if (!$wajib_kimia && !$wajib_mikro) {
                if ($s['status'] === 'Menunggu Pengujian') {
                    $wajib_kimia = TRUE; // fallback default
                } else {
                    continue;
                }
            }

            // Cek ketersediaan sesi untuk masing-masing bidang
            $sesi_kimia = NULL;
            $sesi_mikro = NULL;
            foreach ($sesi_list as $ses) {
                if ($ses['jenis_pengujian'] === 'Kimia' && in_array($ses['status'], array('Sedang Diuji', 'Menunggu Verifikasi', 'Menunggu Approval', 'Approved / Final'))) {
                    $sesi_kimia = $ses;
                }
                if ($ses['jenis_pengujian'] === 'Mikrobiologi' && in_array($ses['status'], array('Sedang Diuji', 'Menunggu Verifikasi', 'Menunggu Approval', 'Approved / Final'))) {
                    $sesi_mikro = $ses;
                }
            }

            $slot_kimia_tersedia = ($wajib_kimia && empty($sesi_kimia));
            $slot_mikro_tersedia = ($wajib_mikro && empty($sesi_mikro));

            // Jika masih ada minimal 1 slot yang belum diambil oleh analis
            if ($slot_kimia_tersedia || $slot_mikro_tersedia) {
                $s['wajib_kimia']          = $wajib_kimia;
                $s['wajib_mikro']          = $wajib_mikro;
                $s['slot_kimia_tersedia']  = $slot_kimia_tersedia;
                $s['slot_mikro_tersedia']  = $slot_mikro_tersedia;
                $s['sesi_kimia_aktif']     = $sesi_kimia;
                $s['sesi_mikro_aktif']     = $sesi_mikro;
                $antrean_tersedia[] = $s;
            }
        }

        return $antrean_tersedia;
    }

    /**
     * Mengambil daftar sesi pengujian aktif milik penguji tertentu (Sampel Saya)
     * 
     * @param int|null $penguji_id
     * @return array
     */
    public function ambil_dalam_pengujian($penguji_id = NULL)
    {
        $this->db->select('ts.id as session_id, ts.jenis_pengujian, ts.status as session_status, ts.waktu_mulai, ts.updated_at as session_updated_at, s.*, u.nama_lengkap as nama_penguji,
            (SELECT COUNT(*) FROM test_results tr WHERE tr.session_id = ts.id) as total_form,
            (SELECT COUNT(*) FROM test_results tr WHERE tr.session_id = ts.id AND tr.data_hasil IS NOT NULL AND tr.data_hasil != "" AND tr.data_hasil != "[]") as form_terisi');
        $this->db->from('testing_sessions ts');
        $this->db->join('samples s', 's.id = ts.sample_id', 'inner');
        $this->db->join('users u', 'u.id = ts.penguji_id', 'left');
        $this->db->where('ts.status', 'Sedang Diuji');
        if ($penguji_id) {
            $this->db->where('ts.penguji_id', $penguji_id);
        }
        $this->db->order_by('ts.id', 'DESC');
        return $this->db->get()->result_array();
    }

    /**
     * Klaim sampel oleh penguji (Self-Assignment)
     * Mendukung klaim spesifik bidang ('Kimia' / 'Mikrobiologi') atau auto-deteksi
     * 
     * @param int $sample_id
     * @param int $penguji_id
     * @param string|null $jenis_pengujian
     * @return bool
     */
    public function klaim_sampel($sample_id, $penguji_id, $jenis_pengujian = NULL)
    {
        $this->db->trans_start();

        // Lock & Verify sampel
        $sampel = $this->db->get_where('samples', array('id' => $sample_id))->row_array();
        if (!$sampel || in_array($sampel['status'], array('Approved / Final', 'Ditolak'))) {
            $this->db->trans_rollback();
            return FALSE;
        }

        // Cek sesi yang sudah ada
        $this->db->where('sample_id', $sample_id);
        $sesi_list = $this->db->get('testing_sessions')->result_array();

        if ($jenis_pengujian) {
            foreach ($sesi_list as $ses) {
                if ($ses['jenis_pengujian'] === $jenis_pengujian && in_array($ses['status'], array('Sedang Diuji', 'Menunggu Verifikasi', 'Menunggu Approval', 'Approved / Final'))) {
                    // Bidang ini sudah ada sesinya
                    $this->db->trans_rollback();
                    return FALSE;
                }
            }
        }

        // Update status umum sampel menjadi Sedang Diuji jika masih Menunggu Pengujian
        if ($sampel['status'] === 'Menunggu Pengujian') {
            $this->db->where('id', $sample_id);
            $this->db->update('samples', array(
                'status'     => 'Sedang Diuji',
                'penguji_id' => $penguji_id
            ));
        }

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /**
     * Membuat Sesi Pengujian Baru Berdasarkan Parameter Monografi Terpilih
     * 
     * @param int $sample_id
     * @param int $penguji_id
     * @param string $jenis_pengujian 'Kimia' / 'Mikrobiologi'
     * @param array $items_metode Array associative/list dari method_id yang memiliki template_id valid
     * @return int|bool ID testing_session baru
     */
    public function buat_sesi_pengujian($sample_id, $penguji_id, $jenis_pengujian, $items_metode)
    {
        $this->db->trans_start();

        // 1. Insert ke testing_sessions
        $session_data = array(
            'sample_id'       => $sample_id,
            'penguji_id'      => $penguji_id,
            'jenis_pengujian' => $jenis_pengujian,
            'status'          => 'Sedang Diuji',
            'waktu_mulai'     => date('Y-m-d H:i:s')
        );
        $this->db->insert('testing_sessions', $session_data);
        $session_id = $this->db->insert_id();

        // 2. Insert ke test_results untuk setiap item parameter monografi terpilih
        foreach ($items_metode as $item) {
            $m_id = is_array($item) ? $item['method_id'] : $item;
            $t_id = is_array($item) ? $item['template_id'] : NULL;

            // Ambil data metode jika t_id belum diketahui
            if (!$t_id) {
                $met = $this->db->get_where('methods', array('id' => $m_id))->row_array();
                if ($met && !empty($met['template_id'])) {
                    $t_id = $met['template_id'];
                }
            }

            $test_data = array(
                'session_id'  => $session_id,
                'sample_id'   => $sample_id,
                'method_id'   => $m_id,
                'template_id' => $t_id,
                'penguji_id'  => $penguji_id,
                'data_hasil'  => '[]',
                'kesimpulan'  => 'Belum Disimpulkan',
                'status'      => 'Sedang Diuji',
                'waktu_mulai' => date('Y-m-d H:i:s')
            );
            $this->db->insert('test_results', $test_data);
        }

        // 3. Pastikan status sampel adalah 'Sedang Diuji' dan penguji_id terisi
        $this->db->where('id', $sample_id);
        $this->db->update('samples', array(
            'status'     => 'Sedang Diuji',
            'penguji_id' => $penguji_id
        ));

        $this->db->trans_complete();

        if ($this->db->trans_status() === TRUE) {
            return $session_id;
        }
        return FALSE;
    }

    /**
     * Memperbarui Sesi Pengujian yang Sedang Berjalan (Ganti Zat Aktif / Tambah / Ubah Parameter LCP)
     * Data hasil pengujian yang telah diinput sebelumnya tetap dipertahankan dan tidak hilang.
     * 
     * @param int $session_id
     * @param array $items_metode Array associative dari method_id dan template_id yang dipilih baru
     * @return bool
     */
    public function perbarui_sesi_pengujian($session_id, $items_metode)
    {
        $this->db->trans_start();

        $sesi = $this->db->get_where('testing_sessions', array('id' => $session_id))->row_array();
        if (!$sesi || $sesi['status'] !== 'Sedang Diuji') {
            $this->db->trans_rollback();
            return FALSE;
        }

        // Ambil hasil pengujian yang sudah ada saat ini di sesi ini
        $existing_results = $this->db->get_where('test_results', array('session_id' => $session_id))->result_array();
        $existing_by_method = array();
        $existing_by_template = array();
        foreach ($existing_results as $er) {
            if (!empty($er['method_id'])) {
                $existing_by_method[(int)$er['method_id']] = $er;
            }
            if (!empty($er['template_id'])) {
                $existing_by_template[(int)$er['template_id']] = $er;
            }
        }

        // Kumpulkan id method baru
        $new_method_ids = array();
        foreach ($items_metode as $item) {
            $new_method_ids[] = (int)(is_array($item) ? $item['method_id'] : $item);
        }

        // Hapus test_results lama yang tidak dipilih lagi
        if (!empty($new_method_ids)) {
            $this->db->where('session_id', $session_id);
            $this->db->where_not_in('method_id', $new_method_ids);
            $this->db->delete('test_results');
        }

        // Perbarui atau tambahkan item parameter uji baru
        foreach ($items_metode as $item) {
            $m_id = (int)(is_array($item) ? $item['method_id'] : $item);
            $t_id = !empty($item['template_id']) ? (int)$item['template_id'] : NULL;

            if (isset($existing_by_method[$m_id])) {
                // Parameter lama sudah ada: Pertahankan data_hasil, kesimpulan, catatan, dll!
                $existing = $existing_by_method[$m_id];
                $update_data = array(
                    'template_id' => $t_id ?: $existing['template_id'],
                    'updated_at'  => date('Y-m-d H:i:s')
                );
                $this->db->where('id', $existing['id']);
                $this->db->update('test_results', $update_data);
            } else {
                // Parameter baru ditambahkan ke sesi
                // Cek apakah ada data_hasil dari template yang sama untuk di-inherit jika ada
                $inherit_hasil = '[]';
                $inherit_kesimpulan = 'Belum Disimpulkan';
                $inherit_catatan = NULL;
                if ($t_id && isset($existing_by_template[$t_id])) {
                    $inherit_hasil = $existing_by_template[$t_id]['data_hasil'];
                    $inherit_kesimpulan = $existing_by_template[$t_id]['kesimpulan'];
                    $inherit_catatan = $existing_by_template[$t_id]['catatan'];
                }

                $new_test_data = array(
                    'session_id'  => $session_id,
                    'sample_id'   => $sesi['sample_id'],
                    'method_id'   => $m_id,
                    'template_id' => $t_id,
                    'penguji_id'  => $sesi['penguji_id'],
                    'data_hasil'  => $inherit_hasil,
                    'kesimpulan'  => $inherit_kesimpulan,
                    'catatan'     => $inherit_catatan,
                    'status'      => 'Sedang Diuji',
                    'waktu_mulai' => date('Y-m-d H:i:s')
                );
                $this->db->insert('test_results', $new_test_data);
            }
        }

        // Update timestamp testing_sessions
        $this->db->where('id', $session_id);
        $this->db->update('testing_sessions', array(
            'updated_at' => date('Y-m-d H:i:s')
        ));

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /**
     * Mengambil data sesi pengujian berdasarkan ID Sesi
     */
    public function ambil_sesi_by_id($session_id)
    {
        $this->db->select('ts.*, s.nama_sampel, s.kode_sampel_manual, s.no as no_sampel, s.kategori_sampel, u.nama_lengkap as nama_penguji, uv.nama_lengkap as nama_verifier, ua.nama_lengkap as nama_approver');
        $this->db->from('testing_sessions ts');
        $this->db->join('samples s', 's.id = ts.sample_id', 'inner');
        $this->db->join('users u', 'u.id = ts.penguji_id', 'left');
        $this->db->join('users uv', 'uv.id = ts.verifier_id', 'left');
        $this->db->join('users ua', 'ua.id = ts.approver_id', 'left');
        $this->db->where('ts.id', $session_id);
        return $this->db->get()->row_array();
    }

    /**
     * Mengambil daftar hasil uji yang terhubung dengan suatu Sesi Pengujian
     * Mengikutsertakan informasi monografi obat lengkap
     */
    public function ambil_hasil_by_session($session_id)
    {
        $this->db->select('tr.*, m.nama_metode, m.kode_metode, m.nama_zat_aktif, m.parameter_uji, m.jenis_sediaan, m.bentuk_sediaan, m.pustaka_acuan, m.metode_analisa, m.persyaratan, tc.nama_kelas_terapi, t.nama_template, t.kode_template, t.skema_form, t.kategori as kategori_template, p.nama_parameter, p.kode_parameter');
        $this->db->from('test_results tr');
        $this->db->join('form_templates t', 't.id = tr.template_id', 'left');
        $this->db->join('methods m', 'm.id = tr.method_id', 'left');
        $this->db->join('therapeutic_classes tc', 'tc.id = m.kelas_terapi_id', 'left');
        $this->db->join('parameters p', 'p.id = t.parameter_id', 'left');
        $this->db->where('tr.session_id', $session_id);
        $this->db->order_by('tr.id', 'ASC');
        return $this->db->get()->result_array();
    }

    /**
     * Mengambil sesi pengujian aktif untuk sampel tertentu
     */
    public function ambil_sesi_by_sample($sample_id)
    {
        $this->db->where('sample_id', $sample_id);
        $this->db->order_by('id', 'DESC');
        return $this->db->get('testing_sessions')->row_array();
    }

    /**
     * Menyimpan hasil pengujian untuk seluruh form dalam satu Sesi Pengujian
     * Menyimpan data parameter uji, instrumen alat, dan reagen terstruktur
     * Menyimpan seluruh hasil pengujian satu sesi (banyak lembar LCP parameter)
     * Mendukung simpan sementara (draft) atau simpan final kirim verifikasi
     * 
     * @param int $session_id
     * @param array $hasil_forms
     * @param bool $is_draft Jika TRUE, status tetap 'Sedang Diuji' dan data_hasil tersimpan sementara
     * @return bool
     */
    public function simpan_hasil_sesi($session_id, $hasil_forms, $is_draft = FALSE)
    {
        $this->db->trans_start();
        $waktu_sekarang = date('Y-m-d H:i:s');

        $sesi = $this->db->get_where('testing_sessions', array('id' => $session_id))->row_array();
        if (!$sesi) {
            $this->db->trans_rollback();
            return FALSE;
        }

        // Update masing-masing form di test_results
        foreach ($hasil_forms as $test_id => $f_data) {
            $raw_hasil = isset($f_data['hasil']) && is_array($f_data['hasil']) ? $f_data['hasil'] : array();

            // Simpan ID instrumen & reagen terstruktur di dalam payload hasil
            $alat_ids = isset($f_data['alat_ids']) && is_array($f_data['alat_ids']) ? array_filter(array_map('intval', $f_data['alat_ids'])) : array();
            $reagen_ids = isset($f_data['reagen_ids']) && is_array($f_data['reagen_ids']) ? array_filter(array_map('intval', $f_data['reagen_ids'])) : array();

            $payload_hasil = array(
                'parameter_values' => $raw_hasil,
                'alat_ids'         => array_values($alat_ids),
                'reagen_ids'       => array_values($reagen_ids)
            );

            // Kompatibilitas skema lama: merge parameter_values langsung ke top level agar view legacy tetap membaca key
            foreach ($raw_hasil as $k => $v) {
                $payload_hasil[$k] = $v;
            }

            $update_data = array(
                'data_hasil'    => json_encode($payload_hasil, JSON_UNESCAPED_UNICODE),
                'kesimpulan'    => isset($f_data['kesimpulan']) && !empty($f_data['kesimpulan']) ? $f_data['kesimpulan'] : 'Belum Disimpulkan',
                'catatan'       => isset($f_data['catatan']) ? $f_data['catatan'] : NULL
            );

            // Jika analis mengganti form LCP (template_id)
            if (isset($f_data['template_id']) && !empty($f_data['template_id'])) {
                $update_data['template_id'] = (int)$f_data['template_id'];
            }

            if ($is_draft) {
                $update_data['status'] = 'Sedang Diuji';
                $update_data['updated_at'] = $waktu_sekarang;
            } else {
                $update_data['status'] = 'Menunggu Verifikasi';
                $update_data['waktu_selesai'] = $waktu_sekarang;
            }

            $this->db->where('id', $test_id);
            $this->db->where('session_id', $session_id);
            $this->db->update('test_results', $update_data);
        }

        if ($is_draft) {
            // Update timestamp testing_sessions tanpa mengubah status
            $this->db->where('id', $session_id);
            $this->db->update('testing_sessions', array(
                'status'     => 'Sedang Diuji',
                'updated_at' => $waktu_sekarang
            ));

            // Pastikan status sampel tetap 'Sedang Diuji'
            $this->db->where('id', $sesi['sample_id']);
            $this->db->update('samples', array(
                'status'     => 'Sedang Diuji',
                'updated_at' => $waktu_sekarang,
                'updated_by' => $sesi['penguji_id']
            ));
        } else {
            // Update status testing_sessions -> 'Menunggu Verifikasi'
            $this->db->where('id', $session_id);
            $this->db->update('testing_sessions', array(
                'status'        => 'Menunggu Verifikasi',
                'waktu_selesai' => $waktu_sekarang
            ));

            // Update status samples -> 'Menunggu Verifikasi'
            $this->db->where('id', $sesi['sample_id']);
            $this->db->update('samples', array(
                'status'     => 'Menunggu Verifikasi',
                'updated_by' => $sesi['penguji_id']
            ));
        }

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /**
     * Menyimpan hasil pengujian dan melakukan transisi status sampel ke "Menunggu Verifikasi" (Single form / compatibility)
     */
    public function simpan_hasil_uji($data_hasil)
    {
        $this->db->trans_start();

        $data_hasil['status']        = 'Menunggu Verifikasi';
        $data_hasil['waktu_selesai'] = date('Y-m-d H:i:s');

        // Insert ke test_results
        $this->db->insert($this->tabel_hasil, $data_hasil);
        $test_id = $this->db->insert_id();

        // Update status sampel menjadi "Menunggu Verifikasi"
        $this->db->where('id', $data_hasil['sample_id']);
        $this->db->update('samples', array(
            'status'     => 'Menunggu Verifikasi',
            'updated_by' => $data_hasil['penguji_id']
        ));

        $this->db->trans_complete();

        if ($this->db->trans_status() === TRUE) {
            return $test_id;
        }
        return FALSE;
    }

    /**
     * Update path file PDF laporan hasil uji pada test_results
     */
    public function update_file_laporan($test_id, $file_path)
    {
        $this->db->where('id', $test_id);
        return $this->db->update($this->tabel_hasil, array('file_laporan' => $file_path));
    }

    /**
     * Update path file PDF laporan hasil uji pada testing_sessions
     */
    public function update_file_laporan_sesi($session_id, $file_path)
    {
        $this->db->where('session_id', $session_id);
        $this->db->update($this->tabel_hasil, array('file_laporan' => $file_path));

        $this->db->where('id', $session_id);
        return $this->db->update('testing_sessions', array('waktu_selesai' => date('Y-m-d H:i:s')));
    }

    /**
     * Mengambil hasil pengujian berdasarkan ID test_result
     */
    public function ambil_hasil_by_id($test_id)
    {
        $this->db->select('tr.*, m.nama_metode, m.kode_metode, m.nama_zat_aktif, m.parameter_uji, m.jenis_sediaan, m.bentuk_sediaan, m.pustaka_acuan, m.metode_analisa, m.persyaratan, tc.nama_kelas_terapi, t.nama_template, t.kode_template, t.skema_form, t.kategori as kategori_template, p.nama_parameter, p.kode_parameter, u.nama_lengkap as nama_penguji, u.nip as nip_penguji, uv.nama_lengkap as nama_verifier, ua.nama_lengkap as nama_approver, s.nama_sampel, s.kode_sampel_manual, s.no as no_sampel, s.status as status_sampel, s.kategori_sampel');
        $this->db->from($this->tabel_hasil . ' tr');
        $this->db->join('samples s', 's.id = tr.sample_id', 'inner');
        $this->db->join('methods m', 'm.id = tr.method_id', 'left');
        $this->db->join('therapeutic_classes tc', 'tc.id = m.kelas_terapi_id', 'left');
        $this->db->join('form_templates t', 't.id = tr.template_id', 'left');
        $this->db->join('parameters p', 'p.id = t.parameter_id', 'left');
        $this->db->join('users u', 'u.id = tr.penguji_id', 'left');
        $this->db->join('users uv', 'uv.id = tr.verifier_id', 'left');
        $this->db->join('users ua', 'ua.id = tr.approver_id', 'left');
        $this->db->where('tr.id', $test_id);
        return $this->db->get()->row_array();
    }

    /**
     * Mengambil daftar seluruh hasil pengujian / sesi pengujian
     * 
     * @param int|null $penguji_id Filter khusus penguji tertentu
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function ambil_semua_hasil($penguji_id = NULL, $limit = 1000, $offset = 0)
    {
        $this->db->select('s.id as sample_id, s.id, s.nama_sampel, s.kode_sampel_manual, s.no as no_sampel, s.no_sipt,
            COALESCE(NULLIF(s.status, ""), MAX(ts.status)) as status,
            GROUP_CONCAT(DISTINCT ts.jenis_pengujian ORDER BY ts.jenis_pengujian ASC SEPARATOR " & ") as jenis_pengujian,
            GROUP_CONCAT(DISTINCT u.nama_lengkap SEPARATOR "; ") as nama_penguji,
            GROUP_CONCAT(DISTINCT uv.nama_lengkap SEPARATOR "; ") as nama_verifier,
            GROUP_CONCAT(DISTINCT ua.nama_lengkap SEPARATOR "; ") as nama_approver,
            MAX(ts.waktu_approval) as waktu_approval,
            MAX(ts.waktu_verifikasi) as waktu_verifikasi,
            MAX(ts.waktu_selesai) as waktu_selesai,
            MAX(ts.waktu_mulai) as waktu_mulai,
            MAX(ts.created_at) as created_at,
            MAX(ts.updated_at) as updated_at,
            COUNT(DISTINCT ts.id) as jumlah_sesi,
            COUNT(DISTINCT tr.id) as jumlah_form,
            GROUP_CONCAT(DISTINCT tr.kesimpulan SEPARATOR ", ") as kesimpulan,
            MAX(CASE WHEN ts.jenis_pengujian = "Kimia" THEN ts.id ELSE NULL END) as sesi_kimia_id,
            MAX(CASE WHEN ts.jenis_pengujian = "Mikrobiologi" THEN ts.id ELSE NULL END) as sesi_mikro_id,
            MAX(CASE WHEN ts.jenis_pengujian = "Kimia" THEN ts.status ELSE NULL END) as status_sesi_kimia,
            MAX(CASE WHEN ts.jenis_pengujian = "Mikrobiologi" THEN ts.status ELSE NULL END) as status_sesi_mikro,
            MIN(ts.id) as first_session_id');
        $this->db->from('testing_sessions ts');
        $this->db->join('samples s', 's.id = ts.sample_id', 'inner');
        $this->db->join('users u', 'u.id = ts.penguji_id', 'left');
        $this->db->join('users uv', 'uv.id = ts.verifier_id', 'left');
        $this->db->join('users ua', 'ua.id = ts.approver_id', 'left');
        $this->db->join('test_results tr', 'tr.session_id = ts.id', 'left');

        if ($penguji_id) {
            $this->db->where('EXISTS (SELECT 1 FROM testing_sessions ts_p JOIN samples s_p ON s_p.id = ts_p.sample_id WHERE (s_p.kode_sampel_manual = s.kode_sampel_manual OR s_p.no_sipt = s.no_sipt) AND ts_p.penguji_id = ' . (int)$penguji_id . ')', NULL, FALSE);
        }

        $this->db->group_by('IF(s.kode_sampel_manual IS NOT NULL AND s.kode_sampel_manual != "", s.kode_sampel_manual, s.no_sipt)');
        $this->db->order_by('COALESCE(MAX(ts.updated_at), MAX(ts.created_at), MAX(ts.id)) DESC', '', FALSE);
        $this->db->limit($limit, $offset);
        $res = $this->db->get()->result_array();

        if (empty($res)) {
            // Fallback ke test_results jika belum ada testing_sessions
            $this->db->select('tr.sample_id, s.id, tr.id as test_id, m.nama_metode, t.nama_template, u.nama_lengkap as nama_penguji, uv.nama_lengkap as nama_verifier, ua.nama_lengkap as nama_approver, s.nama_sampel, s.kode_sampel_manual, s.no as no_sampel, s.no_sipt, s.status, 1 as jumlah_sesi, 1 as jumlah_form, tr.kesimpulan, "Kimia" as jenis_pengujian');
            $this->db->from($this->tabel_hasil . ' tr');
            $this->db->join('samples s', 's.id = tr.sample_id', 'inner');
            $this->db->join('methods m', 'm.id = tr.method_id', 'left');
            $this->db->join('form_templates t', 't.id = tr.template_id', 'left');
            $this->db->join('users u', 'u.id = tr.penguji_id', 'left');
            $this->db->join('users uv', 'uv.id = tr.verifier_id', 'left');
            $this->db->join('users ua', 'ua.id = tr.approver_id', 'left');

            if ($penguji_id) {
                $this->db->where('tr.penguji_id', $penguji_id);
            }

            $this->db->order_by('tr.id', 'DESC');
            $this->db->limit($limit, $offset);
            return $this->db->get()->result_array();
        }

        return $res;
    }

    /**
     * FASE 5 — Mengambil antrean laporan hasil uji / sesi yang "Menunggu Approval"
     */
    public function ambil_antrean_approval()
    {
        $this->db->select('ts.*, s.nama_sampel, s.kode_sampel_manual, s.no as no_sampel, u.nama_lengkap as nama_penguji, uv.nama_lengkap as nama_verifier,
            (SELECT COUNT(*) FROM test_results tr WHERE tr.session_id = ts.id) as jumlah_form,
            (SELECT GROUP_CONCAT(DISTINCT m.nama_metode SEPARATOR ", ") FROM test_results tr JOIN methods m ON m.id = tr.method_id WHERE tr.session_id = ts.id) as nama_metode,
            (SELECT GROUP_CONCAT(DISTINCT t.nama_template SEPARATOR ", ") FROM test_results tr JOIN form_templates t ON t.id = tr.template_id WHERE tr.session_id = ts.id) as nama_template,
            (SELECT GROUP_CONCAT(DISTINCT tr.kesimpulan SEPARATOR ", ") FROM test_results tr WHERE tr.session_id = ts.id) as kesimpulan');
        $this->db->from('testing_sessions ts');
        $this->db->join('samples s', 's.id = ts.sample_id', 'inner');
        $this->db->join('users u', 'u.id = ts.penguji_id', 'left');
        $this->db->join('users uv', 'uv.id = ts.verifier_id', 'left');
        $this->db->where('ts.status', 'Menunggu Approval');
        $this->db->order_by('ts.id', 'ASC');
        $res = $this->db->get()->result_array();

        if (empty($res)) {
            $this->db->select('tr.*, m.nama_metode, t.nama_template, u.nama_lengkap as nama_penguji, uv.nama_lengkap as nama_verifier, s.nama_sampel, s.kode_sampel_manual, s.no as no_sampel, 1 as jumlah_form');
            $this->db->from($this->tabel_hasil . ' tr');
            $this->db->join('samples s', 's.id = tr.sample_id', 'inner');
            $this->db->join('methods m', 'm.id = tr.method_id', 'left');
            $this->db->join('form_templates t', 't.id = tr.template_id', 'left');
            $this->db->join('users u', 'u.id = tr.penguji_id', 'left');
            $this->db->join('users uv', 'uv.id = tr.verifier_id', 'left');
            $this->db->where('tr.status', 'Menunggu Approval');
            $this->db->order_by('tr.id', 'ASC');
            return $this->db->get()->result_array();
        }

        return $res;
    }

    /**
     * FASE 5 — Melakukan Approval oleh Manajer Teknis untuk Sesi
     */
    public function approve_sesi($session_id, $approver_id)
    {
        $this->db->trans_start();
        $waktu_sekarang = date('Y-m-d H:i:s');

        $sesi = $this->db->get_where('testing_sessions', array('id' => $session_id))->row_array();
        if (!$sesi || $sesi['status'] !== 'Menunggu Approval') {
            $this->db->trans_rollback();
            return FALSE;
        }

        // Update testing_sessions
        $this->db->where('id', $session_id);
        $this->db->update('testing_sessions', array(
            'status'         => 'Approved / Final',
            'approver_id'    => $approver_id,
            'waktu_approval' => $waktu_sekarang
        ));

        // Update test_results
        $this->db->where('session_id', $session_id);
        $this->db->update('test_results', array(
            'status'         => 'Approved / Final',
            'approver_id'    => $approver_id,
            'waktu_approval' => $waktu_sekarang
        ));

        // Update samples
        $this->db->where('id', $sesi['sample_id']);
        $this->db->update('samples', array(
            'status'     => 'Approved / Final',
            'updated_by' => $approver_id
        ));

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function approve_hasil($test_id, $approver_id)
    {
        $hasil = $this->db->get_where($this->tabel_hasil, array('id' => $test_id))->row_array();
        if ($hasil && !empty($hasil['session_id'])) {
            return $this->approve_sesi($hasil['session_id'], $approver_id);
        }

        $this->db->trans_start();

        if (!$hasil || $hasil['status'] !== 'Menunggu Approval') {
            $this->db->trans_rollback();
            return FALSE;
        }

        $waktu_sekarang = date('Y-m-d H:i:s');

        $this->db->where('id', $test_id);
        $this->db->update($this->tabel_hasil, array(
            'status'         => 'Approved / Final',
            'approver_id'    => $approver_id,
            'waktu_approval' => $waktu_sekarang
        ));

        $this->db->where('id', $hasil['sample_id']);
        $this->db->update('samples', array(
            'status'     => 'Approved / Final',
            'updated_by' => $approver_id
        ));

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /**
     * FASE 4 — Mengambil antrean laporan hasil uji yang "Menunggu Verifikasi"
     */
    public function ambil_antrean_verifikasi()
    {
        $this->db->select('ts.*, s.nama_sampel, s.kode_sampel_manual, s.no as no_sampel, u.nama_lengkap as nama_penguji,
            (SELECT COUNT(*) FROM test_results tr WHERE tr.session_id = ts.id) as jumlah_form,
            (SELECT GROUP_CONCAT(DISTINCT m.nama_metode SEPARATOR ", ") FROM test_results tr JOIN methods m ON m.id = tr.method_id WHERE tr.session_id = ts.id) as nama_metode,
            (SELECT GROUP_CONCAT(DISTINCT t.nama_template SEPARATOR ", ") FROM test_results tr JOIN form_templates t ON t.id = tr.template_id WHERE tr.session_id = ts.id) as nama_template,
            (SELECT GROUP_CONCAT(DISTINCT tr.kesimpulan SEPARATOR ", ") FROM test_results tr WHERE tr.session_id = ts.id) as kesimpulan');
        $this->db->from('testing_sessions ts');
        $this->db->join('samples s', 's.id = ts.sample_id', 'inner');
        $this->db->join('users u', 'u.id = ts.penguji_id', 'left');
        $this->db->where_in('ts.status', array('Menunggu Verifikasi', 'Ditolak MT'));
        $this->db->order_by('COALESCE(ts.waktu_selesai, ts.updated_at, ts.id) DESC', '', FALSE);
        $res = $this->db->get()->result_array();

        if (empty($res)) {
            $this->db->select('tr.*, m.nama_metode, t.nama_template, u.nama_lengkap as nama_penguji, s.nama_sampel, s.kode_sampel_manual, s.no as no_sampel, 1 as jumlah_form');
            $this->db->from($this->tabel_hasil . ' tr');
            $this->db->join('samples s', 's.id = tr.sample_id', 'inner');
            $this->db->join('methods m', 'm.id = tr.method_id', 'left');
            $this->db->join('form_templates t', 't.id = tr.template_id', 'left');
            $this->db->join('users u', 'u.id = tr.penguji_id', 'left');
            $this->db->where_in('tr.status', array('Menunggu Verifikasi', 'Ditolak MT'));
            $this->db->order_by('tr.id', 'DESC');
            return $this->db->get()->result_array();
        }

        return $res;
    }

    /**
     * FASE 4 — Melakukan Verifikasi oleh Penyelia untuk Sesi
     */
    public function verifikasi_sesi($session_id, $verifier_id)
    {
        $this->db->trans_start();
        $waktu_sekarang = date('Y-m-d H:i:s');

        $sesi = $this->db->get_where('testing_sessions', array('id' => $session_id))->row_array();
        if (!$sesi || !in_array($sesi['status'], array('Menunggu Verifikasi', 'Ditolak MT'), TRUE)) {
            $this->db->trans_rollback();
            return FALSE;
        }

        $this->db->where('id', $session_id);
        $this->db->update('testing_sessions', array(
            'status'           => 'Menunggu Approval',
            'verifier_id'      => $verifier_id,
            'waktu_verifikasi' => $waktu_sekarang,
            'alasan_penolakan' => NULL
        ));

        $this->db->where('session_id', $session_id);
        $this->db->update('test_results', array(
            'status'           => 'Menunggu Approval',
            'verifier_id'      => $verifier_id,
            'waktu_verifikasi' => $waktu_sekarang,
            'alasan_penolakan' => NULL
        ));

        $this->db->where('id', $sesi['sample_id']);
        $this->db->update('samples', array(
            'status'     => 'Menunggu Approval',
            'updated_by' => $verifier_id
        ));

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function verifikasi_hasil($test_id, $verifier_id)
    {
        $hasil = $this->db->get_where($this->tabel_hasil, array('id' => $test_id))->row_array();
        if ($hasil && !empty($hasil['session_id'])) {
            return $this->verifikasi_sesi($hasil['session_id'], $verifier_id);
        }

        $this->db->trans_start();

        if (!$hasil || !in_array($hasil['status'], array('Menunggu Verifikasi', 'Ditolak MT'), TRUE)) {
            $this->db->trans_rollback();
            return FALSE;
        }

        $waktu_sekarang = date('Y-m-d H:i:s');

        $this->db->where('id', $test_id);
        $this->db->update($this->tabel_hasil, array(
            'status'           => 'Menunggu Approval',
            'verifier_id'      => $verifier_id,
            'waktu_verifikasi' => $waktu_sekarang,
            'alasan_penolakan' => NULL
        ));

        $this->db->where('id', $hasil['sample_id']);
        $this->db->update('samples', array(
            'status'     => 'Menunggu Approval',
            'updated_by' => $verifier_id
        ));

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /**
     * FASE 4 — Melakukan Penolakan Hasil oleh Penyelia untuk Sesi
     */
    public function tolak_sesi($session_id, $verifier_id, $alasan_penolakan)
    {
        $this->db->trans_start();
        $waktu_sekarang = date('Y-m-d H:i:s');

        $sesi = $this->db->get_where('testing_sessions', array('id' => $session_id))->row_array();
        if (!$sesi || !in_array($sesi['status'], array('Menunggu Verifikasi', 'Ditolak MT'), TRUE)) {
            $this->db->trans_rollback();
            return FALSE;
        }

        // Jika sesi sebelumnya berasal dari penolakan MT, gabungkan catatan MT dan catatan Penyelia
        $final_alasan = $alasan_penolakan;
        if ($sesi['status'] === 'Ditolak MT' && !empty($sesi['alasan_penolakan'])) {
            $catatan_mt_lama = trim($sesi['alasan_penolakan']);
            $final_alasan = "[Catatan MT]: " . $catatan_mt_lama . "\n\n[Review Penyelia]: " . $alasan_penolakan;
        }

        $this->db->where('id', $session_id);
        $this->db->update('testing_sessions', array(
            'status'           => 'Ditolak',
            'verifier_id'      => $verifier_id,
            'waktu_verifikasi' => $waktu_sekarang,
            'alasan_penolakan' => $final_alasan
        ));

        $this->db->where('session_id', $session_id);
        $this->db->update('test_results', array(
            'status'           => 'Ditolak',
            'verifier_id'      => $verifier_id,
            'waktu_verifikasi' => $waktu_sekarang,
            'alasan_penolakan' => $final_alasan
        ));

        $this->db->where('id', $sesi['sample_id']);
        $this->db->update('samples', array(
            'status'     => 'Ditolak',
            'updated_by' => $verifier_id
        ));

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function tolak_hasil($test_id, $verifier_id, $alasan_penolakan)
    {
        $hasil = $this->db->get_where($this->tabel_hasil, array('id' => $test_id))->row_array();
        if ($hasil && !empty($hasil['session_id'])) {
            return $this->tolak_sesi($hasil['session_id'], $verifier_id, $alasan_penolakan);
        }

        $this->db->trans_start();

        if (!$hasil || !in_array($hasil['status'], array('Menunggu Verifikasi', 'Ditolak MT'), TRUE)) {
            $this->db->trans_rollback();
            return FALSE;
        }

        $waktu_sekarang = date('Y-m-d H:i:s');

        $this->db->where('id', $test_id);
        $this->db->update($this->tabel_hasil, array(
            'status'           => 'Ditolak',
            'verifier_id'      => $verifier_id,
            'waktu_verifikasi' => $waktu_sekarang,
            'alasan_penolakan' => $alasan_penolakan
        ));

        $this->db->where('id', $hasil['sample_id']);
        $this->db->update('samples', array(
            'status'     => 'Ditolak',
            'updated_by' => $verifier_id
        ));

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /**
     * FASE 4 — Penguji Melakukan Revisi atas Hasil yang Ditolak untuk Sesi
     */
    public function revisi_sesi($session_id, $data_revisi_forms)
    {
        $this->db->trans_start();
        $waktu_sekarang = date('Y-m-d H:i:s');

        $sesi = $this->db->get_where('testing_sessions', array('id' => $session_id))->row_array();
        if (!$sesi || $sesi['status'] !== 'Ditolak') {
            $this->db->trans_rollback();
            return FALSE;
        }

        foreach ($data_revisi_forms as $test_id => $f_data) {
            $raw_hasil = isset($f_data['hasil']) && is_array($f_data['hasil']) ? $f_data['hasil'] : array();

            // Simpan ID instrumen & reagen terstruktur di dalam payload hasil
            $alat_ids = isset($f_data['alat_ids']) && is_array($f_data['alat_ids']) ? array_filter(array_map('intval', $f_data['alat_ids'])) : array();
            $reagen_ids = isset($f_data['reagen_ids']) && is_array($f_data['reagen_ids']) ? array_filter(array_map('intval', $f_data['reagen_ids'])) : array();

            $payload_hasil = array(
                'parameter_values' => $raw_hasil,
                'alat_ids'         => array_values($alat_ids),
                'reagen_ids'       => array_values($reagen_ids)
            );

            // Kompatibilitas skema: merge parameter_values ke top level
            foreach ($raw_hasil as $k => $v) {
                $payload_hasil[$k] = $v;
            }

            $update_data = array(
                'data_hasil'       => json_encode($payload_hasil, JSON_UNESCAPED_UNICODE),
                'kesimpulan'       => isset($f_data['kesimpulan']) && !empty($f_data['kesimpulan']) ? $f_data['kesimpulan'] : 'Belum Disimpulkan',
                'catatan'          => isset($f_data['catatan']) ? $f_data['catatan'] : NULL,
                'status'           => 'Menunggu Verifikasi',
                'verifier_id'      => NULL,
                'waktu_verifikasi' => NULL,
                'alasan_penolakan' => NULL,
                'updated_at'       => $waktu_sekarang
            );
            $this->db->where('id', $test_id);
            $this->db->where('session_id', $session_id);
            $this->db->update('test_results', $update_data);
        }

        // Update testing_sessions
        $this->db->where('id', $session_id);
        $this->db->update('testing_sessions', array(
            'status'           => 'Menunggu Verifikasi',
            'verifier_id'      => NULL,
            'waktu_verifikasi' => NULL,
            'alasan_penolakan' => NULL,
            'updated_at'       => $waktu_sekarang
        ));

        // Update samples
        $this->db->where('id', $sesi['sample_id']);
        $this->db->update('samples', array(
            'status'     => 'Menunggu Verifikasi',
            'updated_by' => $sesi['penguji_id']
        ));

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function revisi_hasil($test_id, $data_revisi)
    {
        $hasil = $this->db->get_where($this->tabel_hasil, array('id' => $test_id))->row_array();
        if ($hasil && !empty($hasil['session_id'])) {
            return $this->revisi_sesi($hasil['session_id'], array($test_id => $data_revisi));
        }

        $this->db->trans_start();

        if (!$hasil || $hasil['status'] !== 'Ditolak') {
            $this->db->trans_rollback();
            return FALSE;
        }

        $data_revisi['status']           = 'Menunggu Verifikasi';
        $data_revisi['verifier_id']      = NULL;
        $data_revisi['waktu_verifikasi'] = NULL;
        $data_revisi['alasan_penolakan'] = NULL;
        $data_revisi['updated_at']       = date('Y-m-d H:i:s');

        $this->db->where('id', $test_id);
        $this->db->update($this->tabel_hasil, $data_revisi);

        $this->db->where('id', $hasil['sample_id']);
        $this->db->update('samples', array(
            'status'     => 'Menunggu Verifikasi',
            'updated_by' => $hasil['penguji_id']
        ));

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    /**
     * Mengambil riwayat pengujian/sesi yang telah diverifikasi oleh penyelia tertentu
     */
    public function ambil_riwayat_verifikasi_by_user($verifier_id)
    {
        $this->db->select('ts.*, s.nama_sampel, s.kode_sampel_manual, s.no as no_sampel, u.nama_lengkap as nama_penguji, uv.nama_lengkap as nama_verifier, ua.nama_lengkap as nama_approver,
            (SELECT COUNT(*) FROM test_results tr WHERE tr.session_id = ts.id) as jumlah_form,
            (SELECT GROUP_CONCAT(DISTINCT t.nama_template SEPARATOR ", ") FROM test_results tr JOIN form_templates t ON t.id = tr.template_id WHERE tr.session_id = ts.id) as nama_template,
            (SELECT GROUP_CONCAT(DISTINCT tr.kesimpulan SEPARATOR ", ") FROM test_results tr WHERE tr.session_id = ts.id) as kesimpulan');
        $this->db->from('testing_sessions ts');
        $this->db->join('samples s', 's.id = ts.sample_id', 'inner');
        $this->db->join('users u', 'u.id = ts.penguji_id', 'left');
        $this->db->join('users uv', 'uv.id = ts.verifier_id', 'left');
        $this->db->join('users ua', 'ua.id = ts.approver_id', 'left');
        $this->db->where('ts.verifier_id', $verifier_id);
        $this->db->order_by('COALESCE(ts.waktu_verifikasi, ts.updated_at, ts.created_at, ts.id) DESC', '', FALSE);
        return $this->db->get()->result_array();
    }

    /**
     * Mengambil riwayat pengujian/sesi yang diuji oleh penguji tertentu
     */
    public function ambil_riwayat_penguji_by_user($penguji_id)
    {
        $this->db->select('ts.*, s.nama_sampel, s.kode_sampel_manual, s.no as no_sampel, u.nama_lengkap as nama_penguji, uv.nama_lengkap as nama_verifier, ua.nama_lengkap as nama_approver,
            (SELECT COUNT(*) FROM test_results tr WHERE tr.session_id = ts.id) as jumlah_form,
            (SELECT GROUP_CONCAT(DISTINCT t.nama_template SEPARATOR ", ") FROM test_results tr JOIN form_templates t ON t.id = tr.template_id WHERE tr.session_id = ts.id) as nama_template,
            (SELECT GROUP_CONCAT(DISTINCT tr.kesimpulan SEPARATOR ", ") FROM test_results tr WHERE tr.session_id = ts.id) as kesimpulan');
        $this->db->from('testing_sessions ts');
        $this->db->join('samples s', 's.id = ts.sample_id', 'inner');
        $this->db->join('users u', 'u.id = ts.penguji_id', 'left');
        $this->db->join('users uv', 'uv.id = ts.verifier_id', 'left');
        $this->db->join('users ua', 'ua.id = ts.approver_id', 'left');
        $this->db->where('ts.penguji_id', $penguji_id);
        $this->db->order_by('COALESCE(ts.waktu_selesai, ts.updated_at, ts.created_at, ts.id) DESC', '', FALSE);
        return $this->db->get()->result_array();
    }

    /**
     * Mengambil riwayat pengujian/sesi yang telah diapprove oleh manajer teknis tertentu
     */
    public function ambil_riwayat_approval_by_user($approver_id)
    {
        $this->db->select('s.id as sample_id, s.id, s.nama_sampel, s.kode_sampel_manual, s.no as no_sampel, s.no_sipt,
            GROUP_CONCAT(DISTINCT ts.jenis_pengujian SEPARATOR " & ") as jenis_pengujian,
            GROUP_CONCAT(DISTINCT u.nama_lengkap SEPARATOR "; ") as nama_penguji,
            GROUP_CONCAT(DISTINCT uv.nama_lengkap SEPARATOR "; ") as nama_verifier,
            ua.nama_lengkap as nama_approver,
            MAX(ts.waktu_approval) as waktu_approval,
            (CASE 
                WHEN SUM(CASE WHEN ts.status LIKE "%Ditolak%" THEN 1 ELSE 0 END) > 0 THEN "Ditolak"
                WHEN SUM(CASE WHEN ts.status = "Approved / Final" THEN 1 ELSE 0 END) > 0 THEN "Approved / Final"
                ELSE MAX(ts.status)
            END) as status,
            COUNT(DISTINCT ts.id) as jumlah_sesi,
            COUNT(DISTINCT tr.id) as jumlah_form,
            GROUP_CONCAT(DISTINCT tr.kesimpulan SEPARATOR ", ") as kesimpulan,
            MAX(CASE WHEN ts.jenis_pengujian = "Kimia" THEN ts.id ELSE NULL END) as sesi_kimia_id,
            MAX(CASE WHEN ts.jenis_pengujian = "Mikrobiologi" THEN ts.id ELSE NULL END) as sesi_mikro_id,
            MIN(ts.id) as first_session_id');
        $this->db->from('testing_sessions ts');
        $this->db->join('samples s', 's.id = ts.sample_id', 'inner');
        $this->db->join('users u', 'u.id = ts.penguji_id', 'left');
        $this->db->join('users uv', 'uv.id = ts.verifier_id', 'left');
        $this->db->join('users ua', 'ua.id = ts.approver_id', 'left');
        $this->db->join('test_results tr', 'tr.session_id = ts.id', 'left');
        $this->db->where('ts.approver_id', $approver_id);
        $this->db->group_by('IF(s.kode_sampel_manual IS NOT NULL AND s.kode_sampel_manual != "", s.kode_sampel_manual, s.no_sipt)');
        $this->db->order_by('MAX(ts.waktu_approval) DESC', '', FALSE);
        return $this->db->get()->result_array();
    }

    /**
     * Mengevaluasi kebutuhan jenis pengujian dari nilai string kuantitas (jumlah_kimia / jumlah_mikro).
     *
     * Aturan parsing konservatif:
     * - NULL, string kosong, whitespace, '-' dianggap 0 (tidak wajib).
     * - Nilai numerik atau string angka (misal '0', '0.0', '1', '30.0') diparsing secara ketat.
     * - Teks satuan (misal '1 sampel', '250 gram', '10 tablet') diparsing dengan regex numerik di awal.
     * - Jika mengandung angka nol (misal '0 sampel', '0 gram') dianggap 0 (tidak wajib).
     * - Jika ada teks tak berpola / ambigu tanpa angka valid (misal 'ada', 'perlu', 'ya', '?')
     *   maka status adalah AMBIGUOUS (fail-closed, tidak boleh diasumsikan selesai).
     *
     * @param mixed $raw_val
     * @return array ['is_wajib' => bool, 'is_ambiguous' => bool, 'qty' => float, 'raw' => mixed, 'message' => string]
     */
    public function evaluasi_kebutuhan_pengujian($raw_val)
    {
        if ($raw_val === NULL) {
            return [
                'is_wajib'     => FALSE,
                'is_ambiguous' => FALSE,
                'qty'          => 0.0,
                'raw'          => NULL,
                'message'      => 'Nilai kosong / NULL'
            ];
        }

        $str = trim((string)$raw_val);

        if ($str === '' || $str === '-' || $str === '--') {
            return [
                'is_wajib'     => FALSE,
                'is_ambiguous' => FALSE,
                'qty'          => 0.0,
                'raw'          => $raw_val,
                'message'      => 'Kuantitas kosong atau tanda strip'
            ];
        }

        // Cek jika murni angka desimal atau integer
        if (is_numeric($str)) {
            $val = (float)$str;
            return [
                'is_wajib'     => ($val > 0),
                'is_ambiguous' => FALSE,
                'qty'          => $val,
                'raw'          => $raw_val,
                'message'      => ($val > 0) ? 'Kuantitas numerik valid' : 'Kuantitas nol'
            ];
        }

        // Teks dengan satuan di belakang, misal: "1 sampel", "250 gram", "10 tablet", "0.5 liter", "0 botol"
        if (preg_match('/^\s*([0-9]+(?:[\.,][0-9]+)?)\s*([a-zA-Z\/]+.*)?$/', $str, $matches)) {
            $num_str = str_replace(',', '.', $matches[1]);
            $val = (float)$num_str;
            return [
                'is_wajib'     => ($val > 0),
                'is_ambiguous' => FALSE,
                'qty'          => $val,
                'raw'          => $raw_val,
                'message'      => ($val > 0) ? 'Kuantitas satuan valid' : 'Kuantitas satuan bernilai nol'
            ];
        }

        // Nilai tidak dapat dipastikan secara aman (ambigu) -> fail-closed
        return [
            'is_wajib'     => FALSE,
            'is_ambiguous' => TRUE,
            'qty'          => 0.0,
            'raw'          => $raw_val,
            'message'      => 'Kuantitas ambigu / tidak dapat dipastikan secara otomatis'
        ];
    }

    /**
     * Memeriksa kelengkapan sesi wajib per sampel untuk pengajuan LHU ke Manajer Teknis (MT).
     *
     * Logika murni (pure evaluator): dapat menerima data array sampel & daftar sesi secara mandiri
     * (untuk automated unit testing) atau menarik langsung dari database jika hanya ID sampel diberikan.
     *
     * @param array $sampel Data baris tabel samples (harus memuat id, jumlah_kimia, jumlah_mikro)
     * @param array $sesi_list List associative array dari testing_sessions terkait sample_id tersebut
     * @return array
     */
    public function evaluasi_kelengkapan_sesi_sampel($sampel, $sesi_list = [])
    {
        if (empty($sampel) || !is_array($sampel)) {
            return [
                'siap_mt'          => FALSE,
                'status_agregat'   => 'INVALID_SAMPLE',
                'alasan'           => 'Data sampel tidak valid atau tidak ditemukan',
                'is_ambigu'        => FALSE,
                'wajib_kimia'      => FALSE,
                'wajib_mikro'      => FALSE,
                'detail_kebutuhan' => [],
                'status_sesi'      => []
            ];
        }

        $sample_id = isset($sampel['id']) ? $sampel['id'] : NULL;
        $eval_kimia = $this->evaluasi_kebutuhan_pengujian(isset($sampel['jumlah_kimia']) ? $sampel['jumlah_kimia'] : NULL);
        $eval_mikro = $this->evaluasi_kebutuhan_pengujian(isset($sampel['jumlah_mikro']) ? $sampel['jumlah_mikro'] : NULL);

        $detail_kebutuhan = [
            'Kimia'        => $eval_kimia,
            'Mikrobiologi' => $eval_mikro
        ];

        // Fail-closed jika salah satu kuantitas berstatus ambigu
        if ($eval_kimia['is_ambiguous'] || $eval_mikro['is_ambiguous']) {
            $alasan_ambigu = [];
            if ($eval_kimia['is_ambiguous']) {
                $alasan_ambigu[] = 'jumlah_kimia ambigu (' . var_export($eval_kimia['raw'], TRUE) . ')';
            }
            if ($eval_mikro['is_ambiguous']) {
                $alasan_ambigu[] = 'jumlah_mikro ambigu (' . var_export($eval_mikro['raw'], TRUE) . ')';
            }

            return [
                'siap_mt'          => FALSE,
                'status_agregat'   => 'KUANTITAS_AMBIGU',
                'alasan'           => 'Kebutuhan pengujian tidak dapat dipastikan: ' . implode(', ', $alasan_ambigu),
                'is_ambigu'        => TRUE,
                'wajib_kimia'      => $eval_kimia['is_wajib'],
                'wajib_mikro'      => $eval_mikro['is_wajib'],
                'detail_kebutuhan' => $detail_kebutuhan,
                'status_sesi'      => []
            ];
        }

        $wajib_kimia = $eval_kimia['is_wajib'];
        $wajib_mikro = $eval_mikro['is_wajib'];

        // Jika kedua kolom 0 atau kosong, tidak ada kewajiban pengujian terdefinisi
        if (!$wajib_kimia && !$wajib_mikro) {
            return [
                'siap_mt'          => FALSE,
                'status_agregat'   => 'TIDAK_ADA_PENGUJIAN_WAJIB',
                'alasan'           => 'Tidak ada alokasi pengujian Kimia maupun Mikrobiologi yang diwajibkan untuk sampel ini',
                'is_ambigu'        => FALSE,
                'wajib_kimia'      => FALSE,
                'wajib_mikro'      => FALSE,
                'detail_kebutuhan' => $detail_kebutuhan,
                'status_sesi'      => []
            ];
        }

        // Petakan sesi per jenis pengujian
        // Format sesi: ['id' => ..., 'sample_id' => ..., 'jenis_pengujian' => 'Kimia'|'Mikrobiologi', 'status' => ...]
        $sesi_per_jenis = [
            'Kimia'        => [],
            'Mikrobiologi' => []
        ];

        foreach ($sesi_list as $sesi) {
            $jp = isset($sesi['jenis_pengujian']) ? trim($sesi['jenis_pengujian']) : '';
            if (isset($sesi_per_jenis[$jp])) {
                $sesi_per_jenis[$jp][] = $sesi;
            }
        }

        $wajib_jenis = [];
        if ($wajib_kimia) $wajib_jenis[] = 'Kimia';
        if ($wajib_mikro) $wajib_jenis[] = 'Mikrobiologi';

        // Sertakan juga jenis pengujian yang memiliki sesi aktual pada sampel ini
        foreach (['Kimia', 'Mikrobiologi'] as $jp_cek) {
            if (!empty($sesi_per_jenis[$jp_cek]) && !in_array($jp_cek, $wajib_jenis, TRUE)) {
                $wajib_jenis[] = $jp_cek;
            }
        }

        $status_per_jenis = [];
        $semua_wajib_terpenuhi = TRUE;
        $alasan_kendala = [];

        foreach ($wajib_jenis as $jenis) {
            $daftar = $sesi_per_jenis[$jenis];

            if (empty($daftar)) {
                $status_per_jenis[$jenis] = [
                    'lengkap' => FALSE,
                    'status'  => 'BELUM_DIBUAT',
                    'sesi_id' => NULL,
                    'catatan' => 'Sesi ' . $jenis . ' belum dibuat'
                ];
                $semua_wajib_terpenuhi = FALSE;
                $alasan_kendala[] = 'Sesi ' . $jenis . ' belum dibuat';
                continue;
            }

            // Pisahkan sesi historis final dengan sesi non-final (aktif)
            $sesi_aktif = [];
            $sesi_final = [];
            foreach ($daftar as $s) {
                $st = isset($s['status']) ? trim($s['status']) : '';
                if ($st === 'Approved / Final') {
                    $sesi_final[] = $s;
                } else {
                    $sesi_aktif[] = $s;
                }
            }

            // Aturan Konservatif Sesi Ganda:
            // Jika terdapat lebih dari 1 sesi non-final untuk jenis pengujian yang sama,
            // sistem tidak boleh menebak sesi mana yang otoritatif -> fail-closed (SESI_GANDA_AMBIGU).
            if (count($sesi_aktif) > 1) {
                $status_per_jenis[$jenis] = [
                    'lengkap' => FALSE,
                    'status'  => 'SESI_GANDA_AMBIGU',
                    'sesi_id' => NULL,
                    'catatan' => 'Terdapat lebih dari satu sesi aktif (' . count($sesi_aktif) . ' sesi) untuk ' . $jenis . ' yang belum final. Membutuhkan klarifikasi administratif.'
                ];
                $semua_wajib_terpenuhi = FALSE;
                $alasan_kendala[] = 'Konflik sesi ganda pada ' . $jenis;
                continue;
            }

            // Jika tidak ada sesi aktif, tapi ada sesi final historis
            if (empty($sesi_aktif) && !empty($sesi_final)) {
                $sesi_terakhir_final = end($sesi_final);
                $status_per_jenis[$jenis] = [
                    'lengkap' => TRUE,
                    'status'  => 'Approved / Final',
                    'sesi_id' => $sesi_terakhir_final['id'],
                    'catatan' => 'Sesi ' . $jenis . ' telah disetujui sebelumnya (Historis Final)'
                ];
                continue;
            }

            // Jika tepat 1 sesi aktif
            $s_tunggal = $sesi_aktif[0];
            $st_tunggal = isset($s_tunggal['status']) ? trim($s_tunggal['status']) : '';

            if ($st_tunggal === 'Menunggu Approval') {
                $status_per_jenis[$jenis] = [
                    'lengkap' => TRUE,
                    'status'  => 'Menunggu Approval',
                    'sesi_id' => $s_tunggal['id'],
                    'catatan' => 'Sesi ' . $jenis . ' telah disetujui Penyelia dan siap diajukan ke MT'
                ];
            } elseif ($st_tunggal === 'Ditolak') {
                $status_per_jenis[$jenis] = [
                    'lengkap' => FALSE,
                    'status'  => 'Ditolak',
                    'sesi_id' => $s_tunggal['id'],
                    'catatan' => 'Sesi ' . $jenis . ' berstatus Ditolak oleh Penyelia'
                ];
                $semua_wajib_terpenuhi = FALSE;
                $alasan_kendala[] = 'Sesi ' . $jenis . ' ditolak Penyelia';
            } else {
                $status_per_jenis[$jenis] = [
                    'lengkap' => FALSE,
                    'status'  => $st_tunggal,
                    'sesi_id' => $s_tunggal['id'],
                    'catatan' => 'Sesi ' . $jenis . ' masih berstatus ' . $st_tunggal
                ];
                $semua_wajib_terpenuhi = FALSE;
                $alasan_kendala[] = 'Sesi ' . $jenis . ' masih berstatus ' . $st_tunggal;
            }
        }

        // Hitung sesi yang siap diapprove MT dan sesi yang sedang bermasalah / pending
        $jumlah_sesi_siap = 0;
        $ada_sesi_berjalan_atau_konflik = FALSE;

        foreach ($status_per_jenis as $jenis => $info) {
            if ($info['status'] === 'Menunggu Approval' || $info['status'] === 'Approved / Final') {
                $jumlah_sesi_siap++;
            } elseif ($info['status'] === 'SESI_GANDA_AMBIGU' || $info['status'] === 'Ditolak' || $info['status'] === 'Sedang Diuji' || $info['status'] === 'Menunggu Verifikasi') {
                $ada_sesi_berjalan_atau_konflik = TRUE;
            }
        }

        // Syarat Sampel Siap Ditinjau / Diapprove MT:
        // 1. Terdapat minimal 1 sesi yang siap (status 'Menunggu Approval' atau 'Approved / Final').
        // 2. Tidak ada sesi yang sedang konflik (SESI_GANDA_AMBIGU), ditolak, atau masih berjalan ('Sedang Diuji' / 'Menunggu Verifikasi').
        // Ketiadaan sesi pada jenis pengujian lain ('BELUM_DIBUAT') tidak lagi memblokir approval, karena sampel bisa saja hanya diuji salah satu (Kimia saja atau Mikro saja).
        if ($jumlah_sesi_siap > 0 && !$ada_sesi_berjalan_atau_konflik) {
            $jenis_siap = [];
            foreach ($status_per_jenis as $jenis => $info) {
                if ($info['status'] === 'Menunggu Approval' || $info['status'] === 'Approved / Final') {
                    $jenis_siap[] = $jenis;
                }
            }

            return [
                'siap_mt'          => TRUE,
                'status_agregat'   => 'SIAP_APPROVAL_MT',
                'alasan'           => 'Sesi pengujian (' . implode(', ', $jenis_siap) . ') telah selesai dan disetujui Penyelia, siap ditinjau/diapprove Manajer Teknis',
                'is_ambigu'        => FALSE,
                'wajib_kimia'      => $wajib_kimia,
                'wajib_mikro'      => $wajib_mikro,
                'detail_kebutuhan' => $detail_kebutuhan,
                'status_sesi'      => $status_per_jenis
            ];
        }

        return [
            'siap_mt'          => FALSE,
            'status_agregat'   => 'BELUM_LENGKAP',
            'alasan'           => 'Belum siap diajukan ke MT: ' . (!empty($alasan_kendala) ? implode('; ', $alasan_kendala) : 'Belum ada sesi pengujian yang berstatus Menunggu Approval'),
            'is_ambigu'        => FALSE,
            'wajib_kimia'      => $wajib_kimia,
            'wajib_mikro'      => $wajib_mikro,
            'detail_kebutuhan' => $detail_kebutuhan,
            'status_sesi'      => $status_per_jenis
        ];
    }

    /**
     * Memeriksa kelengkapan sesi wajib untuk satu sample_id langsung dari database.
     *
     * @param int $sample_id
     * @return array
     */
    /**
     * Mengambil seluruh sample_id yang serumpun berdasarkan kesamaan kode_sampel_manual atau no_sipt
     *
     * @param int $sample_id
     * @return array Array of sample IDs
     */
    public function ambil_sample_ids_serumpun($sample_id)
    {
        $sample_id = (int)$sample_id;
        if ($sample_id <= 0) return [];

        $s0 = $this->db->get_where('samples', ['id' => $sample_id])->row_array();
        if (!$s0) return [$sample_id];

        $kd = !empty($s0['kode_sampel_manual']) ? trim($s0['kode_sampel_manual']) : '';
        $sipt = !empty($s0['no_sipt']) ? trim($s0['no_sipt']) : '';

        $this->db->select('id');
        $this->db->from('samples');
        
        $where_clauses = ['id = ' . $sample_id];
        if ($kd !== '' && $kd !== '-') {
            $where_clauses[] = 'kode_sampel_manual = ' . $this->db->escape($kd);
        }
        if ($sipt !== '' && $sipt !== '-') {
            $where_clauses[] = 'no_sipt = ' . $this->db->escape($sipt);
        }

        $this->db->where('(' . implode(' OR ', $where_clauses) . ')', NULL, FALSE);
        $rows = $this->db->get()->result_array();

        $ids = [];
        foreach ($rows as $r) {
            $ids[] = (int)$r['id'];
        }
        return !empty($ids) ? array_unique($ids) : [$sample_id];
    }

    /**
     * Memeriksa kelengkapan sesi wajib untuk satu sample_id langsung dari database.
     * Menggabungkan seluruh sesi dari sampel serumpun (kode sampel yang sama).
     *
     * @param int $sample_id
     * @return array
     */
    public function cek_kelengkapan_sesi_sampel($sample_id)
    {
        $sampel = $this->db->get_where('samples', ['id' => $sample_id])->row_array();
        if (!$sampel) {
            return [
                'siap_mt'          => FALSE,
                'status_agregat'   => 'SAMPEL_TIDAK_DITEMUKAN',
                'alasan'           => 'Sampel ID ' . $sample_id . ' tidak ditemukan di database',
                'is_ambigu'        => FALSE,
                'wajib_kimia'      => FALSE,
                'wajib_mikro'      => FALSE,
                'detail_kebutuhan' => [],
                'status_sesi'      => []
            ];
        }

        $serumpun_ids = $this->ambil_sample_ids_serumpun($sample_id);
        $this->db->where_in('sample_id', $serumpun_ids);
        $sesi_list = $this->db->get('testing_sessions')->result_array();
        return $this->evaluasi_kelengkapan_sesi_sampel($sampel, $sesi_list);
    }

    /**
     * Mengelompokkan dan menyaring antrean approval MT berbasis sampel (Tahap 2).
     *
     * Logika murni:
     * - Menerima daftar sampel kandidat dan seluruh testing_sessions terkait.
     * - Untuk setiap sampel, memanggil evaluasi_kelengkapan_sesi_sampel().
     * - Hanya meloloskan sampel yang siap_mt === TRUE.
     * - Menggabungkan metadata sesi (Kimia dan/atau Mikro), penguji, verifier, dan kesimpulan.
     *
     * @param array $daftar_sampel
     * @param array $daftar_sesi
     * @return array
     */
    public function filter_antrean_approval_sampel($daftar_sampel, $daftar_sesi)
    {
        $antrean_siap = [];

        foreach ($daftar_sampel as $s) {
            $sample_id = (int)$s['id'];
            $serumpun_ids = $this->ambil_sample_ids_serumpun($sample_id);

            // Kumpulkan seluruh sesi milik semua sampel serumpun
            $sesi_sampel = [];
            foreach ($daftar_sesi as $sesi) {
                if (in_array((int)$sesi['sample_id'], $serumpun_ids, true)) {
                    $sesi_sampel[] = $sesi;
                }
            }

            // Kumpulkan daftar sesi yang siap diapprove MT (status 'Menunggu Approval')
            $penguji_list = [];
            $verifier_list = [];
            $kesimpulan_list = [];
            $sesi_ids = [];
            $daftar_sesi_siap = [];

            foreach ($sesi_sampel as $sesi) {
                if (isset($sesi['status']) && $sesi['status'] === 'Menunggu Approval') {
                    $sesi_ids[] = (int)$sesi['id'];
                    $daftar_sesi_siap[] = $sesi;

                    if (!empty($sesi['nama_penguji']) && !in_array($sesi['nama_penguji'], $penguji_list, TRUE)) {
                        $penguji_list[] = $sesi['nama_penguji'];
                    }
                    if (!empty($sesi['nama_verifier']) && !in_array($sesi['nama_verifier'], $verifier_list, TRUE)) {
                        $verifier_list[] = $sesi['nama_verifier'];
                    }
                    if (!empty($sesi['kesimpulan'])) {
                        $kesimpulan_list[] = trim($sesi['kesimpulan']);
                    }
                }
            }

            // Sampel layak masuk antrean approval MT jika memiliki minimal 1 sesi yang menunggu approval
            if (!empty($daftar_sesi_siap)) {
                $eval = $this->evaluasi_kelengkapan_sesi_sampel($s, $sesi_sampel);

                $s['kelengkapan']        = $eval;
                $s['sesi_ids']            = $sesi_ids;
                $s['daftar_sesi_siap']    = $daftar_sesi_siap;
                $s['waktu_siap_mt']       = !empty($waktu_terakhir_list) ? max($waktu_terakhir_list) : (!empty($s['updated_at']) ? $s['updated_at'] : (!empty($s['created_at']) ? $s['created_at'] : null));
                $s['nama_penguji_gabung'] = !empty($penguji_list) ? implode('; ', $penguji_list) : '-';
                $s['nama_verifier_gabung']= !empty($verifier_list) ? implode('; ', $verifier_list) : '-';
                $s['kesimpulan_gabung']   = !empty($kesimpulan_list) ? implode(' | ', array_unique($kesimpulan_list)) : '-';
                $s['jumlah_sesi_siap']    = count($daftar_sesi_siap);

                $antrean_siap[] = $s;
            }
        }

        // Urutkan data antrean approval dari tanggal & waktu terbaru
        usort($antrean_siap, function ($a, $b) {
            $time_a = !empty($a['waktu_siap_mt']) ? strtotime($a['waktu_siap_mt']) : 0;
            $time_b = !empty($b['waktu_siap_mt']) ? strtotime($b['waktu_siap_mt']) : 0;
            if ($time_a === $time_b) {
                return (int)$b['id'] - (int)$a['id'];
            }
            return $time_b - $time_a;
        });

        return $antrean_siap;
    }

    /**
     * Mengambil antrean approval MT berbasis kode sampel langsung dari database.
     * Mengelompokkan seluruh pengujian dengan kode sampel yang sama menjadi 1 baris antrean LHU.
     *
     * @return array
     */
    public function ambil_antrean_approval_sampel()
    {
        // 1. Ambil kandidat sampel unik berdasarkan kode sampel yang memiliki sesi 'Menunggu Approval'
        $this->db->select('s.*');
        $this->db->from('samples s');
        $this->db->join('testing_sessions ts', 'ts.sample_id = s.id', 'inner');
        $this->db->where('ts.status', 'Menunggu Approval');
        $this->db->group_by('IF(s.kode_sampel_manual IS NOT NULL AND s.kode_sampel_manual != "", s.kode_sampel_manual, s.no_sipt)');
        $this->db->order_by('s.id', 'DESC');
        $daftar_sampel = $this->db->get()->result_array();

        if (empty($daftar_sampel)) {
            return [];
        }

        // Kumpulkan seluruh sample_ids serumpun
        $all_sample_ids = [];
        foreach ($daftar_sampel as $row) {
            $serumpun = $this->ambil_sample_ids_serumpun($row['id']);
            $all_sample_ids = array_merge($all_sample_ids, $serumpun);
        }
        $all_sample_ids = array_unique($all_sample_ids);

        // 2. Ambil seluruh sesi terkait sampel-sampel tersebut
        $this->db->select('ts.*, u.nama_lengkap as nama_penguji, uv.nama_lengkap as nama_verifier,
            (SELECT COUNT(*) FROM test_results tr WHERE tr.session_id = ts.id) as jumlah_form,
            (SELECT GROUP_CONCAT(DISTINCT t.nama_template SEPARATOR ", ") FROM test_results tr JOIN form_templates t ON t.id = tr.template_id WHERE tr.session_id = ts.id) as nama_template,
            (SELECT GROUP_CONCAT(DISTINCT tr.kesimpulan SEPARATOR ", ") FROM test_results tr WHERE tr.session_id = ts.id) as kesimpulan');
        $this->db->from('testing_sessions ts');
        $this->db->join('users u', 'u.id = ts.penguji_id', 'left');
        $this->db->join('users uv', 'uv.id = ts.verifier_id', 'left');
        $this->db->where_in('ts.sample_id', $all_sample_ids);
        $this->db->order_by('ts.id', 'ASC');
        $daftar_sesi = $this->db->get()->result_array();

        return $this->filter_antrean_approval_sampel($daftar_sampel, $daftar_sesi);
    }

    /**
     * Mengambil seluruh data tinjauan LHU komprehensif per sampel untuk Manajer Teknis (MT).
     * Otomatis menggabungkan seluruh sesi Kimia dan Mikrobiologi yang berkode sampel sama.
     *
     * @param int $sample_id
     * @return array|false
     */
    public function ambil_tinjauan_lhu_sampel($sample_id)
    {
        $sampel = $this->db->get_where('samples', ['id' => $sample_id])->row_array();
        if (!$sampel) {
            return FALSE;
        }

        $serumpun_ids = $this->ambil_sample_ids_serumpun($sample_id);
        $kelengkapan = $this->cek_kelengkapan_sesi_sampel($sample_id);

        // Ambil seluruh sesi terkait seluruh sampel serumpun
        $this->db->select('ts.*, u.nama_lengkap as nama_penguji, uv.nama_lengkap as nama_verifier, ua.nama_lengkap as nama_approver');
        $this->db->from('testing_sessions ts');
        $this->db->join('users u', 'u.id = ts.penguji_id', 'left');
        $this->db->join('users uv', 'uv.id = ts.verifier_id', 'left');
        $this->db->join('users ua', 'ua.id = ts.approver_id', 'left');
        $this->db->where_in('ts.sample_id', $serumpun_ids);
        $this->db->order_by('ts.id', 'ASC');
        $sesi_list = $this->db->get()->result_array();

        // Ambil seluruh form LCP per sesi
        $sesi_dengan_forms = [];
        foreach ($sesi_list as $sesi) {
            $forms = $this->ambil_hasil_by_session($sesi['id']);
            $sesi['forms'] = $forms;
            $sesi_dengan_forms[] = $sesi;
        }

        return [
            'sampel'      => $sampel,
            'kelengkapan' => $kelengkapan,
            'sesi_list'   => $sesi_dengan_forms
        ];
    }

    /**
     * FASE 5 (Tahap 3) — Memproses Approval Seluruh Sesi Wajib Sampel oleh Manajer Teknis (MT).
     *
     * Menjalankan validasi ulang server-side:
     * 1. Sampel harus ada.
     * 2. Evaluasi kelengkapan sesi wajib harus SIAP_APPROVAL_MT (siap_mt === TRUE).
     * 3. Sesi yang diproses harus benar-benar berstatus 'Menunggu Approval' dan milik sample_id tersebut.
     * 4. Pembaruan seluruh sesi, test_results, sampel, dan penulisan audit log berada dalam SATU transaksi database.
     * 5. Jika satu bagian gagal, rollback penuh dijalankan.
     *
     * Catatan Integritas Status LHU:
     * - Sesi-sesi wajib diubah statusnya menjadi 'Approved / Final' (approval sah oleh MT).
     * - Sampel diubah statusnya menjadi 'Approved / Final'.
     * - Nomor LHU dan file laporan gabungan TIDAK diterbitkan di sini (menunggu generator gabungan Tahap 4).
     *
     * @param int $sample_id
     * @param int $approver_id
     * @param string|null $username
     * @return array ['sukses' => bool, 'pesan' => string, 'sesi_ids' => array]
     */
    public function approve_sampel_mt($sample_id, $approver_id, $username = NULL)
    {
        $sample_id = (int)$sample_id;
        $approver_id = (int)$approver_id;

        $sampel = $this->db->get_where('samples', ['id' => $sample_id])->row_array();
        if (!$sampel) {
            return ['sukses' => FALSE, 'pesan' => 'Data sampel tidak ditemukan di database.'];
        }

        // Panggil ulang evaluator kelengkapan sesi sampel (baca ulang state terkini dari database)
        $eval = $this->cek_kelengkapan_sesi_sampel($sample_id);
        if (!$eval['siap_mt']) {
            return [
                'sukses' => FALSE,
                'pesan'  => 'Sampel belum memenuhi syarat approval MT: ' . $eval['alasan']
            ];
        }

        // Kumpulkan ID sesi wajib yang siap diapprove (status 'Menunggu Approval')
        $target_sesi_ids = [];
        foreach ($eval['status_sesi'] as $jenis => $info) {
            $st = isset($info['status']) ? trim($info['status']) : '';
            if (!empty($info['sesi_id']) && ($st === 'Menunggu Approval' || ($st === '' && !empty($info['lengkap'])))) {
                $target_sesi_ids[] = (int)$info['sesi_id'];
            }
        }

        if (empty($target_sesi_ids)) {
            return ['sukses' => FALSE, 'pesan' => 'Tidak ditemukan sesi yang siap diapprove (berstatus Menunggu Approval).'];
        }

        $waktu_sekarang = date('Y-m-d H:i:s');

        // =========================================================================
        // FASE 4 — GATE KETAT VALIDASI TANDA TANGAN & GENERATOR PDF LHU FINAL GABUNGAN
        // =========================================================================
        // Ambil data lengkap approver (MT)
        $approver_user = $this->db->get_where('users', ['id' => $approver_id])->row_array();
        if (!$approver_user) {
            return ['sukses' => FALSE, 'pesan' => 'Data pengguna Manajer Teknis tidak ditemukan di database.'];
        }

        $serumpun_ids = $this->ambil_sample_ids_serumpun($sample_id);

        // Ambil data sesi lengkap beserta user dan form test_results
        $sesi_lengkap_list = [];
        foreach ($target_sesi_ids as $sid) {
            $this->db->select('ts.*, u.nama_lengkap as nama_penguji, u.nip as nip_penguji, uv.nama_lengkap as nama_verifier, uv.nip as nip_verifier');
            $this->db->from('testing_sessions ts');
            $this->db->join('users u', 'u.id = ts.penguji_id', 'left');
            $this->db->join('users uv', 'uv.id = ts.verifier_id', 'left');
            $this->db->where('ts.id', $sid);
            $this->db->where_in('ts.sample_id', $serumpun_ids);
            $s_row = $this->db->get()->row_array();
            if (!$s_row) {
                return ['sukses' => FALSE, 'pesan' => 'Data sesi ID ' . $sid . ' tidak valid atau bukan milik sampel ini.'];
            }
            $s_row['forms'] = $this->ambil_hasil_by_session($sid);
            $sesi_lengkap_list[] = $s_row;
        }

        // Siapkan nama dan path berkas PDF
        $kd_clean = preg_replace('/[^A-Za-z0-9_\-]/', '_', (!empty($sampel['kode_sampel_manual']) ? $sampel['kode_sampel_manual'] : 'SMP_' . $sample_id));
        $pdf_name = 'LHU_FINAL_' . $kd_clean . '_SMP' . $sample_id . '_' . time() . '.pdf';
        $pdf_rel_path = 'uploads/laporan/' . $pdf_name;
        $base_fpath = defined('FCPATH') ? FCPATH : dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR;
        $pdf_abs_path = $base_fpath . $pdf_rel_path;

        // Inisialisasi dan jalankan generator PDF gabungan dengan gate tanda tangan digital ketat
        require_once APPPATH . 'libraries/Laporan_PDF.php';
        $pdf_gen = new Laporan_PDF();

        try {
            $pdf_gen->buat_laporan_sampel($sampel, $sesi_lengkap_list, $approver_user, $pdf_abs_path, 'F');
        } catch (\Throwable $e) {
            return [
                'sukses' => FALSE,
                'pesan'  => 'Finalisasi LHU dibatalkan: ' . $e->getMessage()
            ];
        }

        // Verifikasi fisik berkas PDF di server
        if (!file_exists($pdf_abs_path) || filesize($pdf_abs_path) === 0) {
            return [
                'sukses' => FALSE,
                'pesan'  => 'Pembuatan berkas LHU PDF final gagal atau berkas kosong di server. Status belum diperbarui.'
            ];
        }

        // Buka Transaksi Database
        $this->db->trans_start();

        // 1. Verifikasi kepemilikan dan status terkini seluruh sesi di dalam transaksi
        $this->db->where_in('id', $target_sesi_ids);
        $this->db->where_in('sample_id', $serumpun_ids);
        $this->db->where('status', 'Menunggu Approval');
        $sesi_rows = $this->db->get('testing_sessions')->result_array();

        if (count($sesi_rows) !== count($target_sesi_ids)) {
            $this->db->trans_rollback();
            if (file_exists($pdf_abs_path)) {
                @unlink($pdf_abs_path);
            }
            return [
                'sukses' => FALSE,
                'pesan'  => 'Status sesi telah berubah atau kepemilikan sesi tidak valid. Approval dibatalkan.'
            ];
        }

        // 2. Update status testing_sessions
        $this->db->where_in('id', $target_sesi_ids);
        $this->db->where_in('sample_id', $serumpun_ids);
        $up_sesi = $this->db->update('testing_sessions', [
            'status'         => 'Approved / Final',
            'approver_id'    => $approver_id,
            'waktu_approval' => $waktu_sekarang,
            'updated_at'     => $waktu_sekarang
        ]);

        if (!$up_sesi) {
            $this->db->trans_rollback();
            if (file_exists($pdf_abs_path)) {
                @unlink($pdf_abs_path);
            }
            return ['sukses' => FALSE, 'pesan' => 'Gagal memperbarui status sesi pengujian.'];
        }

        // 3. Update status test_results dan simpan path file_laporan gabungan
        $this->db->where_in('session_id', $target_sesi_ids);
        $this->db->where_in('sample_id', $serumpun_ids);
        $up_results = $this->db->update('test_results', [
            'status'         => 'Approved / Final',
            'file_laporan'   => $pdf_rel_path,
            'approver_id'    => $approver_id,
            'waktu_approval' => $waktu_sekarang,
            'updated_at'     => $waktu_sekarang
        ]);

        if (!$up_results) {
            $this->db->trans_rollback();
            if (file_exists($pdf_abs_path)) {
                @unlink($pdf_abs_path);
            }
            return ['sukses' => FALSE, 'pesan' => 'Gagal memperbarui status parameter hasil pengujian.'];
        }

        // 4. Update status samples (seluruh sampel serumpun)
        $this->db->where_in('id', $serumpun_ids);
        $up_sampel = $this->db->update('samples', [
            'status'     => 'Approved / Final',
            'updated_by' => $approver_id,
            'updated_at' => $waktu_sekarang
        ]);

        if (!$up_sampel) {
            $this->db->trans_rollback();
            if (file_exists($pdf_abs_path)) {
                @unlink($pdf_abs_path);
            }
            return ['sukses' => FALSE, 'pesan' => 'Gagal memperbarui status sampel.'];
        }

        // 5. Pencatatan Audit Log di dalam transaksi
        $detail_audit = [
            'sample_id'        => $sample_id,
            'kode_sampel'      => $sampel['kode_sampel_manual'],
            'sesi_ids'         => $target_sesi_ids,
            'status_sebelum'   => 'Menunggu Approval',
            'status_sesudah'   => 'Approved / Final',
            'file_pdf'         => $pdf_rel_path,
            'approver_id'      => $approver_id,
            'waktu_keputusan'  => $waktu_sekarang
        ];

        $audit_sukses = $this->db->insert('audit_logs', [
            'user_id'    => $approver_id,
            'username'   => $username ?: 'MT',
            'aksi'       => 'APPROVAL_MT_SAMPEL',
            'modul'      => 'Pengujian',
            'detail'     => json_encode($detail_audit, JSON_UNESCAPED_UNICODE),
            'ip_address' => $this->input->ip_address(),
            'user_agent' => substr((string)$this->input->user_agent(), 0, 255),
            'created_at' => $waktu_sekarang
        ]);

        if (!$audit_sukses) {
            $this->db->trans_rollback();
            if (file_exists($pdf_abs_path)) {
                @unlink($pdf_abs_path);
            }
            return ['sukses' => FALSE, 'pesan' => 'Gagal menulis jejak audit log. Transaksi approval dibatalkan.'];
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            if (file_exists($pdf_abs_path)) {
                @unlink($pdf_abs_path);
            }
            return ['sukses' => FALSE, 'pesan' => 'Terjadi kesalahan sistem saat menyimpan approval ke database.'];
        }

        return [
            'sukses'   => TRUE,
            'pesan'    => 'Seluruh sesi pengujian sampel berhasil disetujui oleh Manajer Teknis dan LHU PDF Final telah diterbitkan.',
            'sesi_ids' => $target_sesi_ids,
            'file_pdf' => $pdf_rel_path
        ];
    }

    /**
     * FASE 5 (Tahap 3) — Memproses Penolakan Pengujian Sampel oleh Manajer Teknis (MT).
     *
     * Validasi:
     * 1. Alasan penolakan wajib terisi (minimal 5 karakter, trimmed).
     * 2. Sampel harus ada.
     * 3. Sesi yang ditolak harus merupakan sesi berstatus 'Menunggu Approval' milik sample_id tersebut.
     * 4. Seluruh pembaruan sesi, form LCP, sampel, dan audit log dieksekusi dalam SATU transaksi atomik.
     *
     * @param int $sample_id
     * @param int $approver_id
     * @param string $alasan_penolakan
     * @param string|null $username
     * @return array ['sukses' => bool, 'pesan' => string, 'sesi_ids' => array]
     */
    public function tolak_sampel_mt($sample_id, $approver_id, $alasan_penolakan, $username = NULL)
    {
        $sample_id = (int)$sample_id;
        $approver_id = (int)$approver_id;
        $alasan = trim((string)$alasan_penolakan);

        if (mb_strlen($alasan) < 5) {
            return ['sukses' => FALSE, 'pesan' => 'Alasan penolakan wajib diisi dengan jelas (minimal 5 karakter).'];
        }

        $sampel = $this->db->get_where('samples', ['id' => $sample_id])->row_array();
        if (!$sampel) {
            return ['sukses' => FALSE, 'pesan' => 'Data sampel tidak ditemukan di database.'];
        }

        $serumpun_ids = $this->ambil_sample_ids_serumpun($sample_id);

        // Ambil seluruh sesi milik sampel ini yang sedang berstatus 'Menunggu Approval'
        $this->db->where_in('sample_id', $serumpun_ids);
        $this->db->where('status', 'Menunggu Approval');
        $sesi_list = $this->db->get('testing_sessions')->result_array();

        if (empty($sesi_list)) {
            return [
                'sukses' => FALSE,
                'pesan'  => 'Tidak ada sesi dengan status "Menunggu Approval" pada sampel ini untuk ditolak.'
            ];
        }

        $target_sesi_ids = array_map(function($s) {
            return (int)$s['id'];
        }, $sesi_list);

        $waktu_sekarang = date('Y-m-d H:i:s');

        // Buka Transaksi Database
        $this->db->trans_start();

        // 1. Update status dan alasan pada testing_sessions
        // Catatan: Dikembalikan ke Penyelia untuk direview ulang dengan status 'Ditolak MT'
        $this->db->where_in('id', $target_sesi_ids);
        $this->db->where_in('sample_id', $serumpun_ids);
        $up_sesi = $this->db->update('testing_sessions', [
            'status'           => 'Ditolak MT',
            'approver_id'      => $approver_id,
            'alasan_penolakan' => $alasan,
            'waktu_approval'   => $waktu_sekarang,
            'updated_at'       => $waktu_sekarang
        ]);

        if (!$up_sesi) {
            $this->db->trans_rollback();
            return ['sukses' => FALSE, 'pesan' => 'Gagal memperbarui penolakan pada sesi pengujian.'];
        }

        // 2. Update status test_results kembali ke antrean verifikasi dengan catatan penolakan MT
        $this->db->where_in('session_id', $target_sesi_ids);
        $this->db->where_in('sample_id', $serumpun_ids);
        $up_results = $this->db->update('test_results', [
            'status'           => 'Menunggu Verifikasi',
            'approver_id'      => $approver_id,
            'alasan_penolakan' => $alasan,
            'waktu_approval'   => $waktu_sekarang,
            'updated_at'       => $waktu_sekarang
        ]);

        if (!$up_results) {
            $this->db->trans_rollback();
            return ['sukses' => FALSE, 'pesan' => 'Gagal memperbarui status parameter hasil pengujian.'];
        }

        // 3. Update status samples menjadi 'Menunggu Verifikasi' (dikembalikan ke Penyelia)
        $this->db->where_in('id', $serumpun_ids);
        $up_sampel = $this->db->update('samples', [
            'status'     => 'Menunggu Verifikasi',
            'updated_by' => $approver_id,
            'updated_at' => $waktu_sekarang
        ]);

        if (!$up_sampel) {
            $this->db->trans_rollback();
            return ['sukses' => FALSE, 'pesan' => 'Gagal memperbarui status sampel.'];
        }

        // 4. Pencatatan Audit Log di dalam transaksi
        $detail_audit = [
            'sample_id'        => $sample_id,
            'kode_sampel'      => $sampel['kode_sampel_manual'],
            'sesi_ids'         => $target_sesi_ids,
            'status_sebelum'   => 'Menunggu Approval',
            'status_sesudah'   => 'Ditolak MT (Dikembalikan ke Penyelia)',
            'alasan_penolakan' => $alasan,
            'approver_id'      => $approver_id,
            'waktu_keputusan'  => $waktu_sekarang
        ];

        $audit_sukses = $this->db->insert('audit_logs', [
            'user_id'    => $approver_id,
            'username'   => $username ?: 'MT',
            'aksi'       => 'PENOLAKAN_MT_SAMPEL',
            'modul'      => 'Pengujian',
            'detail'     => json_encode($detail_audit, JSON_UNESCAPED_UNICODE),
            'ip_address' => $this->input->ip_address(),
            'user_agent' => substr((string)$this->input->user_agent(), 0, 255),
            'created_at' => $waktu_sekarang
        ]);

        if (!$audit_sukses) {
            $this->db->trans_rollback();
            return ['sukses' => FALSE, 'pesan' => 'Gagal menulis jejak audit log penolakan. Transaksi dibatalkan.'];
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return ['sukses' => FALSE, 'pesan' => 'Transaksi database penolakan sampel gagal dieksekusi.'];
        }

        return [
            'sukses'   => TRUE,
            'pesan'    => 'Hasil pengujian sampel berhasil ditolak dan dikembalikan ke Penyelia untuk ditinjau ulang sebelum diteruskan ke Penguji.',
            'sesi_ids' => $target_sesi_ids
        ];
    }
}
