<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Metode (Monografi Obat) LIMSKU
 * 
 * Menangani Master Data Monografi Metode Pengujian Laboratorium BBPOM (Kimia & Mikrobiologi),
 * append-only bulk import dari Excel, composite hashing, dan pemetaan ke LCP.
 */
class Model_Metode extends CI_Model
{
    protected $tabel = 'methods';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Model_Kelas_Terapi');
    }

    /**
     * Mengambil daftar metode lengkap dengan relasi kelas terapi dan template LCP
     * 
     * @param string|null $bidang Filter bidang ('Kimia' / 'Mikrobiologi')
     * @param string|null $status Filter status ('Aktif' / 'Nonaktif')
     * @return array
     */
    public function ambil_semua($bidang = NULL, $status = NULL)
    {
        $this->db->select('m.*, tc.nama_kelas_terapi, ft.kode_template, ft.nama_template');
        $this->db->from($this->tabel . ' m');
        $this->db->join('therapeutic_classes tc', 'tc.id = m.kelas_terapi_id', 'left');
        $this->db->join('form_templates ft', 'ft.id = m.template_id', 'left');

        if ($bidang) {
            $this->db->group_start();
            $this->db->where('m.bidang_pengujian', $bidang);
            $this->db->or_where('m.kategori', $bidang);
            $this->db->group_end();
        }
        if ($status) {
            $this->db->where('m.status', $status);
        }

        $this->db->order_by('m.nama_zat_aktif', 'ASC');
        $this->db->order_by('m.parameter_uji', 'ASC');
        return $this->db->get()->result_array();
    }

    /**
     * Mengambil metode aktif
     * 
     * @param string|null $bidang
     * @return array
     */
    public function ambil_aktif($bidang = NULL)
    {
        return $this->ambil_semua($bidang, 'Aktif');
    }

    /**
     * Mengambil detail metode berdasarkan ID
     * 
     * @param int $id
     * @return array|null
     */
    public function ambil_by_id($id)
    {
        $this->db->select('m.*, tc.nama_kelas_terapi, ft.kode_template, ft.nama_template');
        $this->db->from($this->tabel . ' m');
        $this->db->join('therapeutic_classes tc', 'tc.id = m.kelas_terapi_id', 'left');
        $this->db->join('form_templates ft', 'ft.id = m.template_id', 'left');
        $this->db->where('m.id', $id);
        return $this->db->get()->row_array();
    }

    /**
     * Generate kode metode unik otomatis (MET-00001, MET-00002, dst.)
     * 
     * @param int $offset_add
     * @return string
     */
    public function generate_kode_metode($offset_add = 1)
    {
        $this->db->select("MAX(CAST(SUBSTRING(kode_metode, 5) AS UNSIGNED)) as max_num");
        $this->db->like('kode_metode', 'MET-', 'after');
        $row = $this->db->get($this->tabel)->row_array();

        $base = (!empty($row['max_num'])) ? (int)$row['max_num'] : 0;
        return sprintf('MET-%05d', $base + $offset_add);
    }

    /**
     * Hitung composite hash untuk mendeteksi baris monografi duplikat
     * Menggunakan kombinasi: zat aktif, sediaan, acuan, parameter uji, metode analisa, persyaratan, bidang, dan kelas terapi
     * 
     * @param array $item
     * @return string MD5 hash 32 karakter
     */
    public static function buat_composite_hash($item)
    {
        $parts = array(
            strtolower(trim(preg_replace('/\s+/', ' ', (string)($item['nama_zat_aktif'] ?? '')))),
            strtolower(trim(preg_replace('/\s+/', ' ', (string)($item['jenis_sediaan'] ?? '')))),
            strtolower(trim(preg_replace('/\s+/', ' ', (string)($item['bentuk_sediaan'] ?? '')))),
            strtolower(trim(preg_replace('/\s+/', ' ', (string)($item['pustaka_acuan'] ?? '')))),
            strtolower(trim(preg_replace('/\s+/', ' ', (string)($item['parameter_uji'] ?? '')))),
            strtolower(trim(preg_replace('/\s+/', ' ', (string)($item['metode_analisa'] ?? '')))),
            strtolower(trim(preg_replace('/\s+/', ' ', (string)($item['persyaratan'] ?? '')))),
            strtolower(trim(preg_replace('/\s+/', ' ', (string)($item['bidang_pengujian'] ?? 'Kimia')))),
            (int)($item['kelas_terapi_id'] ?? 0)
        );

        return md5(implode('|', $parts));
    }

    /**
     * Tambah metode secara manual
     * 
     * @param array $data
     * @return int|bool
     */
    public function tambah($data)
    {
        if (empty($data['kode_metode'])) {
            $data['kode_metode'] = $this->generate_kode_metode();
        }
        if (empty($data['nama_metode'])) {
            $data['nama_metode'] = (!empty($data['nama_zat_aktif']) && !empty($data['parameter_uji']))
                ? $data['nama_zat_aktif'] . ' - ' . $data['parameter_uji']
                : ($data['nama_zat_aktif'] ?? 'Metode Baru');
        }
        if (empty($data['composite_hash'])) {
            $data['composite_hash'] = self::buat_composite_hash($data);
        }

        if ($this->db->insert($this->tabel, $data)) {
            return $this->db->insert_id();
        }
        return FALSE;
    }

    /**
     * Ubah metode
     * 
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function ubah($id, $data)
    {
        if (isset($data['kode_metode'])) {
            unset($data['kode_metode']); // Kode metode bersifat immutable
        }
        if (!empty($data['nama_zat_aktif']) && !empty($data['parameter_uji'])) {
            $data['nama_metode'] = $data['nama_zat_aktif'] . ' - ' . $data['parameter_uji'];
        }
        $data['composite_hash'] = self::buat_composite_hash($data);

        $this->db->where('id', $id);
        return $this->db->update($this->tabel, $data);
    }

    /**
     * Ubah status metode
     * 
     * @param int $id
     * @param string $status
     * @return bool
     */
    public function ubah_status($id, $status)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->tabel, array('status' => $status));
    }

    /**
     * Map template LCP ke metode
     * 
     * @param int $id
     * @param int|null $template_id
     * @return bool
     */
    public function set_template_mapping($id, $template_id)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->tabel, array(
            'template_id' => $template_id ?: NULL
        ));
    }

    /**
     * Hapus metode (Proteksi jika sudah pernah dipakai di test_results)
     * 
     * @param int $id
     * @return bool
     */
    public function hapus($id)
    {
        // Cek pemakaian transaksi
        $this->db->where('method_id', $id);
        if ($this->db->count_all_results('test_results') > 0) {
            return FALSE;
        }

        $this->db->where('id', $id);
        return $this->db->delete($this->tabel);
    }

    /**
     * Ambil daftar zat aktif unik untuk pemilihan saat pengujian
     * 
     * @param string $bidang 'Kimia' / 'Mikrobiologi'
     * @return array
     */
    public function ambil_daftar_zat_aktif($bidang = 'Kimia')
    {
        if ($bidang === 'Mikrobiologi') {
            // Untuk mikrobiologi, tampilkan semua zat aktif aktif (karena sediaan oral non-antibiotik berlaku umum untuk seluruh zat aktif)
            // Hitung juga jumlah parameter mikro yang sudah terdaftar
            $this->db->select('TRIM(nama_zat_aktif) as nama_zat_aktif, 
                               COUNT(*) as total_parameter, 
                               COUNT(CASE WHEN bidang_pengujian = "Mikrobiologi" OR kategori = "Mikrobiologi" THEN 1 END) as total_param_mikro,
                               GROUP_CONCAT(DISTINCT NULLIF(TRIM(bentuk_sediaan), "") ORDER BY bentuk_sediaan ASC SEPARATOR ", ") as daftar_sediaan');
        } else {
            $this->db->select('TRIM(nama_zat_aktif) as nama_zat_aktif, COUNT(*) as total_parameter, GROUP_CONCAT(DISTINCT NULLIF(TRIM(bentuk_sediaan), "") ORDER BY bentuk_sediaan ASC SEPARATOR ", ") as daftar_sediaan');
            $this->db->group_start();
            $this->db->where('bidang_pengujian', $bidang);
            $this->db->or_where('kategori', $bidang);
            $this->db->group_end();
        }
        $this->db->where('status', 'Aktif');
        $this->db->where("nama_zat_aktif IS NOT NULL AND TRIM(nama_zat_aktif) != ''", NULL, FALSE);
        $this->db->group_by('TRIM(nama_zat_aktif)');
        $this->db->order_by('TRIM(nama_zat_aktif)', 'ASC');
        return $this->db->get($this->tabel)->result_array();
    }

    /**
     * Ambil parameter monografi berdasarkan zat aktif terpilih beserta relasi LCP dan kelas terapi
     * 
     * @param string $zat_aktif
     * @param string $bidang
     * @param string $bentuk_sediaan_hint
     * @return array
     */
    public function ambil_parameter_by_zat_aktif($zat_aktif, $bidang = 'Kimia', $bentuk_sediaan_hint = '')
    {
        $clean_za = trim(preg_replace('/\s+/', ' ', (string)$zat_aktif));
        if (empty($clean_za)) {
            return array();
        }

        // Jika Mikrobiologi, periksa apakah perlu menyematkan 3 parameter standar untuk oral non-antibiotik
        if ($bidang === 'Mikrobiologi') {
            $eval = self::evaluasi_rekomendasi_mikro($clean_za, $bentuk_sediaan_hint);
            if ($eval['kategori'] === 'oral_non_antibiotik') {
                $this->pastikan_parameter_mikro_standar_oral($clean_za, $bentuk_sediaan_hint);
            }
        }

        $this->db->select('m.*, tc.nama_kelas_terapi, ft.kode_template, ft.nama_template, ft.kategori as kategori_template, ft.skema_form');
        $this->db->from($this->tabel . ' m');
        $this->db->join('therapeutic_classes tc', 'tc.id = m.kelas_terapi_id', 'left');
        $this->db->join('form_templates ft', 'ft.id = m.template_id', 'left');
        $this->db->where('TRIM(m.nama_zat_aktif)', $clean_za);
        $this->db->group_start();
        $this->db->where('m.bidang_pengujian', $bidang);
        $this->db->or_where('m.kategori', $bidang);
        $this->db->group_end();
        $this->db->where('m.status', 'Aktif');
        $this->db->order_by('m.bentuk_sediaan', 'ASC');
        $this->db->order_by('m.parameter_uji', 'ASC');
        return $this->db->get()->result_array();
    }

    /**
     * Evaluasi Cerdas Rekomendasi Pengujian Mikrobiologi:
     * - Sediaan oral nonsteril non-antibiotik: Wajib/Rekomendasi Paket 3 Parameter (ALT, AKK, E. coli)
     * - Antibiotik Nistatin: Wajib/Rekomendasi Penetapan Potensi (MIK-03)
     * - Antibiotik Oral Lainnya: Pemberitahuan informatif (umumnya tidak diuji mikro rutin, kadar di Kimia)
     * Tetap 100% fleksibel bagi analis.
     * 
     * @param string $zat_aktif
     * @param string $bentuk_sediaan
     * @param string $nama_sampel
     * @return array
     */
    public static function evaluasi_rekomendasi_mikro($zat_aktif, $bentuk_sediaan = '', $nama_sampel = '')
    {
        $za_lower = strtolower(trim((string)$zat_aktif));
        $bs_lower = strtolower(trim((string)$bentuk_sediaan . ' ' . (string)$nama_sampel));

        // 1. Deteksi apakah Nistatin / Nystatin
        $is_nystatin = (strpos($za_lower, 'nystatin') !== false || strpos($za_lower, 'nistatin') !== false || strpos($bs_lower, 'nystatin') !== false || strpos($bs_lower, 'nistatin') !== false);

        // 2. Deteksi Golongan Antibiotik
        $daftar_antibiotik = array(
            'nystatin', 'nistatin',
            'amoxicillin', 'amoksisilin', 'ampicillin', 'ampisilin', 'co-amoxiclav', 'clavulanic',
            'cefadroxil', 'sefadroksil', 'cefixime', 'sefiksim', 'cefotaxime', 'ceftriaxone', 'cefepime', 'cefazolin', 'cephalexin', 'cefaclor', 'cefuroxime', 'cefprozil',
            'ciprofloxacin', 'siprofloksasin', 'levofloxacin', 'levofloksasin', 'ofloxacin', 'moxifloxacin',
            'erythromycin', 'eritromisin', 'azithromycin', 'azitromisin', 'clarithromycin', 'klaritromisin', 'spiramycin',
            'tetracycline', 'tetrasiklin', 'doxycycline', 'doksisiklin', 'oxytetracycline',
            'chloramphenicol', 'kloramfenikol', 'thiamphenicol', 'tiamfenikol',
            'clindamycin', 'klindamisin', 'lincomycin', 'linkomisin',
            'cotrimoxazole', 'kotrimoksazol', 'sulfamethoxazole', 'trimethoprim',
            'metronidazole', 'metronidazol',
            'gentamicin', 'gentamisin', 'kanamycin', 'amikacin', 'neomycin', 'tobramycin',
            'rifampicin', 'rifampisin', 'isoniazid', 'pyrazinamide', 'ethambutol',
            'vancomycin', 'vankomisin'
        );

        $is_antibiotik = false;
        foreach ($daftar_antibiotik as $ab) {
            if (strpos($za_lower, $ab) !== false || strpos($bs_lower, $ab) !== false) {
                $is_antibiotik = true;
                break;
            }
        }

        // 3. Deteksi Bentuk Sediaan (Topikal, Rektal, Oral Nonsteril)
        $is_topikal = (strpos($bs_lower, 'krim') !== false || strpos($bs_lower, 'salep') !== false || strpos($bs_lower, 'gel') !== false || strpos($bs_lower, 'pasta') !== false || strpos($bs_lower, 'topikal') !== false || strpos($bs_lower, 'topical') !== false || strpos($bs_lower, 'ointment') !== false || strpos($bs_lower, 'cream') !== false || strpos($bs_lower, 'losio') !== false || strpos($bs_lower, 'lotion') !== false);
        $is_rektal = (strpos($bs_lower, 'supositor') !== false || strpos($bs_lower, 'suppos') !== false || strpos($bs_lower, 'ovula') !== false || strpos($bs_lower, 'rektal') !== false || strpos($bs_lower, 'rectal') !== false || strpos($bs_lower, 'enema') !== false);

        $kata_kunci_oral = array(
            'tablet', 'kaplet', 'kapsul', 'capsule', 'sirup', 'syrup', 'suspensi', 'suspension',
            'larutan oral', 'oral solution', 'eliksir', 'elixir', 'drop', 'tetes oral', 'oral drop',
            'emulsi', 'emulsion', 'serbuk oral', 'puyer', 'granul', 'effervescent', 'chewable', 'oral'
        );
        $kata_kunci_non_oral_steril = array(
            'injeksi', 'injection', 'infus', 'infusion', 'tetes mata', 'salep mata', 'eye drop',
            'eye ointment', 'tetes telinga', 'ear drop', 'topikal', 'topical', 'krim', 'cream',
            'salep', 'ointment', 'gel', 'suppositoria', 'ovula', 'inhalasi', 'inhalation'
        );

        $ada_kata_oral = false;
        foreach ($kata_kunci_oral as $ko) {
            if (strpos($bs_lower, $ko) !== false) {
                $ada_kata_oral = true;
                break;
            }
        }

        $ada_kata_steril_atau_topikal = false;
        foreach ($kata_kunci_non_oral_steril as $ks) {
            if (strpos($bs_lower, $ks) !== false) {
                $ada_kata_steril_atau_topikal = true;
                break;
            }
        }

        // Oral nonsteril jika ada kata oral dan bukan topikal/rektal/steril
        $is_oral_nonsteril = ($ada_kata_oral && !$ada_kata_steril_atau_topikal && !$is_topikal && !$is_rektal);

        // Jika tidak ada indikasi eksplisit sediaan steril/topikal/rektal, asumsikan sediaan umum oral
        if (!$is_oral_nonsteril && !$ada_kata_steril_atau_topikal && !$is_topikal && !$is_rektal && (empty($bentuk_sediaan) || strpos($bs_lower, 'oral') !== false)) {
            $is_oral_nonsteril = true;
        }

        // 4. Klasifikasi dan Susun Respons Rekomendasi
        if ($is_nystatin) {
            return array(
                'kategori'               => 'antibiotik_nystatin',
                'is_oral_nonsteril'      => $is_oral_nonsteril,
                'is_antibiotik'          => true,
                'is_nystatin'            => true,
                'judul'                  => 'Rekomendasi Farmakope: Penetapan Potensi Nistatin (MIK-03)',
                'pesan'                  => 'Khusus antibiotik Nistatin, pengujian mikrobiologi yang dipersyaratkan adalah Penetapan Potensi Antibiotika (Metode Lempeng-Silinder 5 Dosis / MIK-03). Uji cemaran mikroba ALT, AKK, dan E. coli tidak dipersyaratkan sesuai standar Farmakope Indonesia.',
                'parameter_rekomendasi'  => array('potensi', 'penetapan potensi'),
                'template_rekomendasi'   => array('MIK-03'),
                'badge_tipe'             => 'primary',
                'alert_class'            => 'alert-primary',
                'auto_check_target'      => 'potensi',
                'btn_label'              => 'Pilih Rekomendasi (Potensi Antibiotika MIK-03)'
            );
        } elseif ($is_topikal) {
            return array(
                'kategori'               => 'topikal',
                'is_oral_nonsteril'      => false,
                'is_antibiotik'          => $is_antibiotik,
                'is_nystatin'            => false,
                'judul'                  => 'Rekomendasi Suplemen 1 FI VI (Sediaan Topikal)',
                'pesan'                  => 'Berdasarkan Suplemen 1 FI VI, seluruh sediaan topikal dipersyaratkan memenuhi uji Angka Lempeng Total (ALT ≤ 10^2 koloni/g), Angka Kapang Khamir (AKK ≤ 10^1 koloni/g), serta bebas dari Pseudomonas aeruginosa dan Staphylococcus aureus (Negatif per gram atau per mL).',
                'parameter_rekomendasi'  => array('alt', 'lempeng', 'akk', 'kapang', 'khamir', 'pseudomonas', 'pseudo', 'staphylococcus', 'staph', 'aures', 'aeruginosa'),
                'template_rekomendasi'   => array('MIK-01', 'MIK-02', 'MIK-04'),
                'badge_tipe'             => 'success',
                'alert_class'            => 'alert-success border-success-subtle',
                'auto_check_target'      => 'paket_topikal',
                'btn_label'              => 'Pilih Paket Rekomendasi Topikal (ALT + AKK + Pseudomonas + Staphylococcus)'
            );
        } elseif ($is_rektal) {
            return array(
                'kategori'               => 'rektal',
                'is_oral_nonsteril'      => false,
                'is_antibiotik'          => $is_antibiotik,
                'is_nystatin'            => false,
                'judul'                  => 'Rekomendasi Suplemen 1 FI VI (Sediaan Rektal)',
                'pesan'                  => 'Berdasarkan Suplemen 1 FI VI, seluruh sediaan rektal dipersyaratkan memenuhi uji Angka Lempeng Total (ALT ≤ 10^3 koloni/g) dan Angka Kapang Khamir (AKK ≤ 10^2 koloni/g).',
                'parameter_rekomendasi'  => array('alt', 'lempeng', 'akk', 'kapang', 'khamir'),
                'template_rekomendasi'   => array('MIK-01', 'MIK-02'),
                'badge_tipe'             => 'info',
                'alert_class'            => 'alert-info border-info-subtle',
                'auto_check_target'      => 'paket_rektal',
                'btn_label'              => 'Pilih Paket Rekomendasi Rektal (ALT + AKK)'
            );
        } elseif ($is_antibiotik) {
            return array(
                'kategori'               => 'antibiotik_lainnya',
                'is_oral_nonsteril'      => $is_oral_nonsteril,
                'is_antibiotik'          => true,
                'is_nystatin'            => false,
                'judul'                  => 'Pemberitahuan SOP Mikrobiologi (Sediaan Antibiotik Oral)',
                'pesan'                  => 'Sediaan antibiotik oral umumnya tidak memerlukan pengujian cemaran mikroba (ALT, AKK, dan E. coli) karena zat aktif memiliki daya hambat bakteri secara alami. Uji penetapan kadar dilakukan di Laboratorium Kimia. Pilihan form di bawah tetap fleksibel jika terdapat instruksi pengujian khusus dari Manajer Teknis.',
                'parameter_rekomendasi'  => array(),
                'template_rekomendasi'   => array(),
                'badge_tipe'             => 'warning',
                'alert_class'            => 'alert-warning border-warning-subtle',
                'auto_check_target'      => 'none',
                'btn_label'              => null
            );
        } elseif ($is_oral_nonsteril) {
            return array(
                'kategori'               => 'oral_non_antibiotik',
                'is_oral_nonsteril'      => true,
                'is_antibiotik'          => false,
                'is_nystatin'            => false,
                'judul'                  => 'Rekomendasi Suplemen 1 FI VI (Sediaan Oral Nonsteril)',
                'pesan'                  => 'Berdasarkan Suplemen 1 FI VI & SOP BBPOM, sediaan oral nonsteril wajib memenuhi batas cemaran mikroba Paket 3 Parameter: Angka Lempeng Total (ALT), Angka Kapang Khamir (AKK), dan Escherichia coli (E. coli).',
                'parameter_rekomendasi'  => array('alt', 'lempeng', 'akk', 'kapang', 'khamir', 'hitung', 'spesifik', 'ecoli', 'e.coli', 'escherichia'),
                'template_rekomendasi'   => array('MIK-01', 'MIK-02', 'MIK-04'),
                'badge_tipe'             => 'success',
                'alert_class'            => 'alert-success border-success-subtle',
                'auto_check_target'      => 'paket_3_oral',
                'btn_label'              => 'Pilih Paket Rekomendasi (ALT + AKK + E. coli)'
            );
        } else {
            return array(
                'kategori'               => 'lainnya',
                'is_oral_nonsteril'      => false,
                'is_antibiotik'          => false,
                'is_nystatin'            => false,
                'judul'                  => 'Panduan Pengujian Mikrobiologi',
                'pesan'                  => 'Pilih parameter pengujian mikrobiologi yang sesuai dengan monografi dan spesifikasi sampel laboratorium.',
                'parameter_rekomendasi'  => array(),
                'template_rekomendasi'   => array(),
                'badge_tipe'             => 'secondary',
                'alert_class'            => 'alert-light border',
                'auto_check_target'      => 'none',
                'btn_label'              => null
            );
        }
    }

    /**
     * Memastikan 3 parameter mikrobiologi standar untuk sediaan oral nonsteril tersedia di database methods:
     * 1. Uji Angka Lempeng Total (ALT) -> Template MIK-01
     * 2. Uji Angka Kapang Khamir (AKK) -> Template MIK-02
     * 3. Uji Cemaran Mikroba Kualitatif (Escherichia coli) -> Template MIK-04
     * 
     * @param string $zat_aktif
     * @param string $bentuk_sediaan
     * @return bool
     */
    public function pastikan_parameter_mikro_standar_oral($zat_aktif, $bentuk_sediaan = 'Tablet / Sediaan Oral')
    {
        $clean_za = trim(preg_replace('/\s+/', ' ', (string)$zat_aktif));
        if (empty($clean_za)) {
            return false;
        }

        // Cek apakah sudah ada parameter mikro untuk zat aktif ini
        $this->db->where('TRIM(nama_zat_aktif)', $clean_za);
        $this->db->group_start();
        $this->db->where('bidang_pengujian', 'Mikrobiologi');
        $this->db->or_where('kategori', 'Mikrobiologi');
        $this->db->group_end();
        $count = $this->db->count_all_results($this->tabel);

        if ($count > 0) {
            return true; // Sudah ada entri mikro di database
        }

        // Cari bentuk sediaan dari metode kimia yang sudah ada jika $bentuk_sediaan default
        if ($bentuk_sediaan === 'Tablet / Sediaan Oral' || empty($bentuk_sediaan)) {
            $this->db->select('bentuk_sediaan');
            $this->db->where('TRIM(nama_zat_aktif)', $clean_za);
            $this->db->where("bentuk_sediaan IS NOT NULL AND TRIM(bentuk_sediaan) != ''", NULL, FALSE);
            $this->db->limit(1);
            $row_bs = $this->db->get($this->tabel)->row_array();
            if (!empty($row_bs['bentuk_sediaan'])) {
                $bentuk_sediaan = trim($row_bs['bentuk_sediaan']);
            } else {
                $bentuk_sediaan = 'Tablet / Sediaan Oral';
            }
        }

        $bs_lower = strtolower($bentuk_sediaan);
        $is_cair = (strpos($bs_lower, 'sirup') !== false || strpos($bs_lower, 'suspensi') !== false || strpos($bs_lower, 'larutan') !== false || strpos($bs_lower, 'drop') !== false || strpos($bs_lower, 'eliksir') !== false || strpos($bs_lower, 'emulsi') !== false);

        $syarat_alt = $is_cair ? '≤ 10^2 koloni/mL' : '≤ 10^3 koloni/g';
        $syarat_akk = $is_cair ? '≤ 10^1 koloni/mL' : '≤ 10^2 koloni/g';

        $standard_params = array(
            array(
                'parameter_uji'  => 'Uji Angka Lempeng Total (ALT)',
                'metode_analisa' => 'Metode Tuang / Cawan Sebar',
                'persyaratan'    => $syarat_alt,
                'pustaka_acuan'  => 'Suplemen 1 FI VI',
                'template_id'    => 32 // MIK-01
            ),
            array(
                'parameter_uji'  => 'Uji Angka Kapang Khamir (AKK)',
                'metode_analisa' => 'Metode Tuang / Cawan Sebar',
                'persyaratan'    => $syarat_akk,
                'pustaka_acuan'  => 'Suplemen 1 FI VI',
                'template_id'    => 37 // MIK-02
            ),
            array(
                'parameter_uji'  => 'Uji Cemaran Mikroba Kualitatif (Escherichia coli)',
                'metode_analisa' => 'Pengayaan & Media Selektif MacConkey',
                'persyaratan'    => 'Negatif per gram atau per mL',
                'pustaka_acuan'  => 'Suplemen 1 FI VI',
                'template_id'    => 19 // MIK-04
            )
        );

        $offset = 1;
        foreach ($standard_params as $sp) {
            $item = array(
                'kode_metode'      => $this->generate_kode_metode($offset++),
                'nama_metode'      => $sp['parameter_uji'] . ' ' . $clean_za,
                'nama_zat_aktif'   => $clean_za,
                'jenis_sediaan'    => 'Sediaan Farmasi',
                'bentuk_sediaan'   => $bentuk_sediaan,
                'parameter_uji'    => $sp['parameter_uji'],
                'metode_analisa'   => $sp['metode_analisa'],
                'persyaratan'      => $sp['persyaratan'],
                'pustaka_acuan'    => $sp['pustaka_acuan'],
                'bidang_pengujian' => 'Mikrobiologi',
                'kategori'         => 'Mikrobiologi',
                'template_id'      => $sp['template_id'],
                'status'           => 'Aktif'
            );
            $item['composite_hash'] = self::buat_composite_hash($item);
            $this->db->insert($this->tabel, $item);
        }

        return true;
    }

    /**
     * Mengambil daftar master parameter pengujian untuk modal picker (Poin 11)
     *
     * @param string $bidang 'Kimia' / 'Mikrobiologi'
     * @param string|null $q Kata kunci pencarian
     * @param int $limit
     * @return array
     */
    public function ambil_master_parameter_untuk_picker($bidang = 'Mikrobiologi', $q = NULL, $limit = 200)
    {
        $this->db->select('m.id, m.kode_metode, m.nama_metode, m.nama_zat_aktif, m.bentuk_sediaan, 
                           m.parameter_uji, m.metode_analisa, m.persyaratan, m.pustaka_acuan, 
                           m.template_id, ft.kode_template, ft.nama_template, tc.nama_kelas_terapi');
        $this->db->from($this->tabel . ' m');
        $this->db->join('form_templates ft', 'ft.id = m.template_id', 'left');
        $this->db->join('therapeutic_classes tc', 'tc.id = m.kelas_terapi_id', 'left');
        $this->db->group_start();
        $this->db->where('m.bidang_pengujian', $bidang);
        $this->db->or_where('m.kategori', $bidang);
        $this->db->group_end();
        $this->db->where('m.status', 'Aktif');

        if (!empty($q)) {
            $q = trim($q);
            $this->db->group_start();
            $this->db->like('m.nama_zat_aktif', $q);
            $this->db->or_like('m.parameter_uji', $q);
            $this->db->or_like('m.metode_analisa', $q);
            $this->db->or_like('m.bentuk_sediaan', $q);
            $this->db->or_like('ft.nama_template', $q);
            $this->db->or_like('ft.kode_template', $q);
            $this->db->group_end();
        }

        $this->db->order_by('m.parameter_uji', 'ASC');
        $this->db->order_by('m.nama_zat_aktif', 'ASC');
        if ($limit > 0) {
            $this->db->limit($limit);
        }
        return $this->db->get()->result_array();
    }

    /**
     * Ambil set seluruh composite_hash yang sudah ada di database untuk lookup efisien
     * 
     * @return array Associative array [hash => 1]
     */
    public function ambil_semua_hash_lookup()
    {
        $this->db->select('composite_hash');
        $this->db->where("composite_hash IS NOT NULL AND composite_hash != ''", NULL, FALSE);
        $rows = $this->db->get($this->tabel)->result_array();

        $lookup = array();
        foreach ($rows as $r) {
            $lookup[$r['composite_hash']] = 1;
        }
        return $lookup;
    }

    /**
     * Eksekusi Batch Append-Only Import
     * 
     * @param array $valid_items
     * @param array $kelas_terapi_map [raw_nama => id]
     * @return array
     */
    public function eksekusi_import_batch($valid_items, $kelas_terapi_map)
    {
        $result = array(
            'sukses'   => FALSE,
            'imported' => 0,
            'skipped'  => 0,
            'errors'   => array()
        );

        if (empty($valid_items)) {
            $result['errors'][] = 'Tidak ada baris data valid untuk diimpor.';
            return $result;
        }

        // Ambil counter awal
        $this->db->select("MAX(CAST(SUBSTRING(kode_metode, 5) AS UNSIGNED)) as max_num");
        $this->db->like('kode_metode', 'MET-', 'after');
        $row_cnt = $this->db->get($this->tabel)->row_array();
        $current_num = (!empty($row_cnt['max_num'])) ? (int)$row_cnt['max_num'] : 0;

        // Ambil lookup hash terkini untuk mencegah balapan duplicate
        $existing_hash = $this->ambil_semua_hash_lookup();

        $this->db->trans_start();

        $chunk_size = 250;
        $chunks = array_chunk($valid_items, $chunk_size);
        $total_imported = 0;
        $total_skipped = 0;

        foreach ($chunks as $chunk) {
            $batch_insert = array();

            foreach ($chunk as $item) {
                // Resolusi ID Kelas Terapi
                $kt_id = NULL;
                if (!empty($item['raw_kelas_terapi'])) {
                    $clean_kt = Model_Kelas_Terapi::bersihkan_nama_header($item['raw_kelas_terapi']);
                    if (isset($kelas_terapi_map[$clean_kt])) {
                        $kt_id = $kelas_terapi_map[$clean_kt];
                    }
                }

                $item_data = array(
                    'nama_zat_aktif'   => $item['nama_zat_aktif'],
                    'jenis_sediaan'    => !empty($item['jenis_sediaan']) ? $item['jenis_sediaan'] : NULL,
                    'bentuk_sediaan'   => !empty($item['bentuk_sediaan']) ? $item['bentuk_sediaan'] : NULL,
                    'pustaka_acuan'    => !empty($item['pustaka_acuan']) ? $item['pustaka_acuan'] : NULL,
                    'parameter_uji'    => $item['parameter_uji'],
                    'metode_analisa'   => !empty($item['metode_analisa']) ? $item['metode_analisa'] : NULL,
                    'persyaratan'      => !empty($item['persyaratan']) ? $item['persyaratan'] : NULL,
                    'bidang_pengujian' => $item['bidang_pengujian'],
                    'kategori'         => $item['bidang_pengujian'], // Kompatibilitas kolom legacy
                    'kelas_terapi_id'  => $kt_id,
                    'template_id'      => NULL, // Ditandai 'Belum Dipetakan' secara eksplisit
                    'status'           => 'Aktif'
                );

                $hash = self::buat_composite_hash($item_data);

                // Jika sudah ada di database, lewati
                if (isset($existing_hash[$hash])) {
                    $total_skipped++;
                    continue;
                }

                $current_num++;
                $item_data['kode_metode']    = sprintf('MET-%05d', $current_num);
                $item_data['nama_metode']    = $item_data['nama_zat_aktif'] . ' - ' . $item_data['parameter_uji'];
                $item_data['composite_hash'] = $hash;
                $item_data['created_at']     = date('Y-m-d H:i:s');
                $item_data['updated_at']     = date('Y-m-d H:i:s');

                $existing_hash[$hash] = 1; // Cegah duplikat dalam batch lanjutan
                $batch_insert[] = $item_data;
            }

            if (!empty($batch_insert)) {
                $this->db->insert_batch($this->tabel, $batch_insert);
                $total_imported += count($batch_insert);
            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $result['sukses'] = FALSE;
            $result['errors'][] = 'Transaksi database gagal disimpan.';
            return $result;
        }

        $result['sukses']   = TRUE;
        $result['imported'] = $total_imported;
        $result['skipped']  = $total_skipped;
        return $result;
    }

    /**
     * Normalisasi teks parameter monografi menjadi kelompok parameter kanonis.
     * Mengelompokkan variasi penulisan atau sinonim hanya jika kesetaraannya dapat dipertanggungjawabkan farmakope.
     * Teks asli monografi tetap dipertahankan utuh pada kolom parameter_uji untuk audit dan histori.
     * 
     * @param string $param_raw
     * @return string
     */
    /**
     * Normalisasi teks parameter monografi menjadi kelompok parameter kanonis.
     * Mengelompokkan variasi penulisan atau sinonim hanya jika kesetaraannya dapat dipertanggungjawabkan farmakope.
     * Teks asli monografi tetap dipertahankan utuh pada kolom parameter_uji untuk audit dan histori.
     * 
     * @param string $param_raw
     * @return string
     */
    public static function normalisasi_parameter($param_raw)
    {
        $clean = trim(preg_replace('/\s+/', ' ', (string)$param_raw));
        $lower = strtolower($clean);

        // Aturan Kanonisasi Ilmiah & Farmakope
        if (preg_match('/^pemerian/i', $lower)) {
            return 'Pemerian';
        }
        if (preg_match('/^identifikasi/i', $lower)) {
            return 'Identifikasi';
        }
        if (preg_match('/^(penetapan kadar|kadar)\b/i', $lower)) {
            return 'Penetapan Kadar';
        }
        if (preg_match('/^(disolusi|pelepasan obat)\b/i', $lower)) {
            return 'Disolusi';
        }
        if (preg_match('/^keragaman bobot\b/i', $lower)) {
            return 'Keragaman Bobot';
        }
        if (preg_match('/^keseragaman kandungan\b/i', $lower)) {
            return 'Keseragaman Kandungan';
        }
        if (preg_match('/^keseragaman sediaan\b/i', $lower)) {
            return 'Keseragaman Sediaan';
        }
        if (preg_match('/^ph\b/i', $lower)) {
            return 'pH';
        }
        if (preg_match('/^bobot jenis\b/i', $lower)) {
            return 'Bobot Jenis';
        }
        if (preg_match('/^waktu hancur/i', $lower)) {
            return 'Waktu Hancur';
        }
        if (preg_match('/^(batas mikroba|penghitungan mikroba)/i', $lower)) {
            return 'Batas Mikroba';
        }
        if (preg_match('/^sterilitas/i', $lower)) {
            return 'Sterilitas';
        }
        if (preg_match('/^endotoksin/i', $lower)) {
            return 'Endotoksin Bakteri';
        }
        if (preg_match('/^(penetapan potensi|potensi)/i', $lower)) {
            return 'Potensi Antibiotik';
        }
        if (preg_match('/^uji mikroba spesifik/i', $lower)) {
            return 'Mikroba Spesifik';
        }

        return ucwords($clean);
    }

    /**
     * Normalisasi metode analisis instrumen/pengujian menjadi kategori kanonis.
     * Menangani variasi penulisan teks dan singkatan alat laboratorium.
     * 
     * @param string|null $metode_raw
     * @return string
     */
    public static function normalisasi_metode($metode_raw)
    {
        $clean = trim(preg_replace('/\s+/', ' ', (string)$metode_raw));
        $lower = strtolower($clean);

        if (empty($lower) || $lower === '-') {
            return 'Metode Standar / Monografi';
        }
        if (preg_match('/(kckt|hplc)/i', $lower)) {
            return 'KCKT / HPLC';
        }
        if (preg_match('/(serapan atom|aas|sepktrofotom\s*etri\s*serapan\s*atom)/i', $lower)) {
            return 'Spektrofotometri Serapan Atom (AAS)';
        }
        if (preg_match('/ir\b/i', $lower)) {
            return 'Spektrofotometri IR';
        }
        if (preg_match('/(spektrofotom|spektro|uv[\-\/\s]?vis|serapan uv)/i', $lower)) {
            return 'Spektrofotometri UV-Vis';
        }
        if (preg_match('/(klt|lapis tipis)/i', $lower)) {
            return 'Kromatografi Lapis Tipis (KLT)';
        }
        if (preg_match('/(gc[\-\s]?ms|spektrometri massa)/i', $lower)) {
            return 'Kromatografi Gas (GC-MS)';
        }
        if (preg_match('/(kromatografi gas|gc\b)/i', $lower)) {
            return 'Kromatografi Gas (GC)';
        }
        if (preg_match('/(reaksi kimia|reaksi warna|pengendapan)/i', $lower)) {
            return 'Reaksi Kimia / Pengendapan';
        }
        if (preg_match('/(organolep|organopel|pemerian fisik)/i', $lower)) {
            return 'Organoleptis';
        }
        if (preg_match('/(titrimetri|titrasi|volumetri)/i', $lower)) {
            return 'Titrimetri';
        }
        if (preg_match('/(disintegrat|desintegrat|waktu hancur)/i', $lower)) {
            return 'Disintegration Tester';
        }
        if (preg_match('/ph/i', $lower)) {
            return 'pH Meter';
        }
        if (preg_match('/piknometer/i', $lower)) {
            return 'Piknometer';
        }
        if (preg_match('/fotometer nyala/i', $lower)) {
            return 'Fotometer Nyala';
        }
        if (preg_match('/(penyaringan|inokulasi)/i', $lower)) {
            return 'Inokulasi / Filtrasi Membran';
        }
        if (preg_match('/(gel clot|gendal gel)/i', $lower)) {
            return 'Gel Clot (LAL)';
        }
        if (preg_match('/(alt|akk)/i', $lower)) {
            return 'Hitungan Cawan (ALT / AKK)';
        }

        return ucwords($clean);
    }

    /**
     * Menentukan kandidat LCP resmi aktif berdasarkan pasangan Parameter Kanonis & Metode Analisis Kanonis.
     * Menggunakan kode template resmi (KIM-01 s/d KIM-12) dan status confidence level.
     * 
     * @param string $kanonis_param
     * @param string $kanonis_metode
     * @param string $bidang 'Kimia' / 'Mikrobiologi'
     * @param array $tpl_by_code [kode_template => id]
     * @return array [template_id, status, alasan]
     */
    public static function cari_kandidat_lcp($kanonis_param, $kanonis_metode, $bidang, $tpl_by_code)
    {
        if ($bidang === 'Kimia') {
            // 1. Pemerian
            if ($kanonis_param === 'Pemerian') {
                return array(
                    'template_id' => $tpl_by_code['KIM-10'] ?? NULL,
                    'status'      => 'OTOMATIS',
                    'alasan'      => 'Sesuai Form KIM-10: Pemerian (Organoleptis) [F.01A.25]'
                );
            }

            // 2. pH
            if ($kanonis_param === 'pH') {
                return array(
                    'template_id' => $tpl_by_code['KIM-11'] ?? NULL,
                    'status'      => 'OTOMATIS',
                    'alasan'      => 'Sesuai Form KIM-11: Penetapan pH [F.01A.27]'
                );
            }

            // 3. Waktu Hancur
            if ($kanonis_param === 'Waktu Hancur') {
                return array(
                    'template_id' => $tpl_by_code['KIM-04'] ?? NULL,
                    'status'      => 'OTOMATIS',
                    'alasan'      => 'Sesuai Form KIM-04: Uji Waktu Hancur Tablet [F.01A.04]'
                );
            }

            // 4. Bobot Jenis
            if ($kanonis_param === 'Bobot Jenis') {
                return array(
                    'template_id' => $tpl_by_code['KIM-12'] ?? NULL,
                    'status'      => 'OTOMATIS',
                    'alasan'      => 'Sesuai Form KIM-12: Penetapan Bobot Jenis Piknometer [F.01A.28]'
                );
            }

            // 5. Keragaman Bobot
            if ($kanonis_param === 'Keragaman Bobot') {
                return array(
                    'template_id' => $tpl_by_code['KIM-02'] ?? NULL,
                    'status'      => 'OTOMATIS',
                    'alasan'      => 'Sesuai Form KIM-02: Uji Keragaman Bobot [F.01A.02]'
                );
            }

            // 6. Keseragaman Kandungan
            if ($kanonis_param === 'Keseragaman Kandungan') {
                return array(
                    'template_id' => $tpl_by_code['KIM-03'] ?? NULL,
                    'status'      => 'OTOMATIS',
                    'alasan'      => 'Sesuai Form KIM-03: Uji Keseragaman Kandungan [F.01A.03]'
                );
            }

            // 7. Keseragaman Sediaan
            if ($kanonis_param === 'Keseragaman Sediaan') {
                if (in_array($kanonis_metode, array('KCKT / HPLC', 'Spektrofotometri UV-Vis', 'Spektrofotometri IR'))) {
                    return array(
                        'template_id' => $tpl_by_code['KIM-03'] ?? NULL,
                        'status'      => 'OTOMATIS',
                        'alasan'      => 'Keseragaman Sediaan Instrumental (' . $kanonis_metode . ') -> Form KIM-03 Keseragaman Kandungan'
                    );
                } else {
                    return array(
                        'template_id' => $tpl_by_code['KIM-02'] ?? NULL,
                        'status'      => 'OTOMATIS',
                        'alasan'      => 'Keseragaman Sediaan Gravimetri/Bobot -> Form KIM-02 Keragaman Bobot'
                    );
                }
            }

            // 8. Disolusi
            if ($kanonis_param === 'Disolusi') {
                return array(
                    'template_id' => $tpl_by_code['KIM-06'] ?? NULL,
                    'status'      => 'OTOMATIS',
                    'alasan'      => 'Sesuai Form KIM-06: Uji Disolusi Bejana [F.01A.08]'
                );
            }

            // 9. Penetapan Kadar
            if ($kanonis_param === 'Penetapan Kadar') {
                if ($kanonis_metode === 'KCKT / HPLC') {
                    return array(
                        'template_id' => $tpl_by_code['KIM-08'] ?? NULL,
                        'status'      => 'OTOMATIS',
                        'alasan'      => 'Penetapan Kadar KCKT -> Form KIM-08 KCKT/HPLC [F.01A.12]'
                    );
                }
                if (in_array($kanonis_metode, array('Spektrofotometri UV-Vis', 'Spektrofotometri IR'))) {
                    return array(
                        'template_id' => $tpl_by_code['KIM-05'] ?? NULL,
                        'status'      => 'OTOMATIS',
                        'alasan'      => 'Penetapan Kadar Spektro -> Form KIM-05 Spektrofotometri [F.01A.06]'
                    );
                }
                if ($kanonis_metode === 'Kromatografi Gas (GC-MS)') {
                    return array(
                        'template_id' => $tpl_by_code['KIM-09'] ?? NULL,
                        'status'      => 'OTOMATIS',
                        'alasan'      => 'Penetapan Kadar GC-MS -> Form KIM-09 GC-MS [F.01A.17]'
                    );
                }
                if ($kanonis_metode === 'Titrimetri') {
                    return array(
                        'template_id' => NULL,
                        'status'      => 'PERLU_KONFIRMASI',
                        'alasan'      => 'Metode Titrimetri: Belum ada form khusus titrimetri di katalog 12 LCP Kimia saat ini'
                    );
                }
                return array(
                    'template_id' => NULL,
                    'status'      => 'PERLU_KONFIRMASI',
                    'alasan'      => 'Metode ' . $kanonis_metode . ' memerlukan pemilihan form LCP alternatif'
                );
            }

            // 10. Identifikasi
            if ($kanonis_param === 'Identifikasi') {
                if ($kanonis_metode === 'Kromatografi Lapis Tipis (KLT)') {
                    return array(
                        'template_id' => $tpl_by_code['KIM-07'] ?? NULL,
                        'status'      => 'OTOMATIS',
                        'alasan'      => 'Identifikasi KLT -> Form KIM-07 Kromatografi Lapis Tipis [F.01A.10]'
                    );
                }
                if ($kanonis_metode === 'Reaksi Kimia / Pengendapan') {
                    return array(
                        'template_id' => $tpl_by_code['KIM-01'] ?? NULL,
                        'status'      => 'OTOMATIS',
                        'alasan'      => 'Identifikasi Reaksi Kimia -> Form KIM-01 Reaksi Warna/Pengendapan [F.01A.01]'
                    );
                }
                if ($kanonis_metode === 'KCKT / HPLC') {
                    return array(
                        'template_id' => $tpl_by_code['KIM-08'] ?? NULL,
                        'status'      => 'OTOMATIS',
                        'alasan'      => 'Identifikasi KCKT (Waktu Retensi) -> Form KIM-08 KCKT/HPLC'
                    );
                }
                if (in_array($kanonis_metode, array('Spektrofotometri UV-Vis', 'Spektrofotometri IR'))) {
                    return array(
                        'template_id' => $tpl_by_code['KIM-05'] ?? NULL,
                        'status'      => 'OTOMATIS',
                        'alasan'      => 'Identifikasi Spektrofotometri -> Form KIM-05 Spektrofotometri'
                    );
                }
                if ($kanonis_metode === 'Kromatografi Gas (GC-MS)') {
                    return array(
                        'template_id' => $tpl_by_code['KIM-09'] ?? NULL,
                        'status'      => 'OTOMATIS',
                        'alasan'      => 'Identifikasi GC-MS -> Form KIM-09 GC-MS'
                    );
                }
                return array(
                    'template_id' => $tpl_by_code['KIM-01'] ?? NULL,
                    'status'      => 'PERLU_KONFIRMASI',
                    'alasan'      => 'Identifikasi Umum: Dapat dirujuk ke Form KIM-01 (Kualitatif) atau form instrumen terkait'
                );
            }
        } elseif ($bidang === 'Mikrobiologi') {
            // 1. Endotoksin Bakteri
            if ($kanonis_param === 'Endotoksin Bakteri') {
                if (stripos($kanonis_metode, 'kepekaan') !== false || stripos($kanonis_param, 'kepekaan') !== false) {
                    return array(
                        'template_id' => $tpl_by_code['MIK-06'] ?? NULL,
                        'status'      => 'OTOMATIS',
                        'alasan'      => 'Sesuai Form MIK-06: Konfirmasi Kepekaan LAL Semi Kuantitatif'
                    );
                }
                return array(
                    'template_id' => $tpl_by_code['MIK-05'] ?? NULL,
                    'status'      => 'OTOMATIS',
                    'alasan'      => 'Sesuai Form MIK-05: Uji Endotoksin Bakteri Semi Kuantitatif'
                );
            }

            // 2. Potensi Antibiotik
            if ($kanonis_param === 'Potensi Antibiotik') {
                return array(
                    'template_id' => $tpl_by_code['MIK-03'] ?? NULL,
                    'status'      => 'OTOMATIS',
                    'alasan'      => 'Sesuai Form MIK-03: Potensi Antibiotika Metode Lempeng-Silinder 5 Dosis'
                );
            }

            // 3. Batas Mikroba
            if ($kanonis_param === 'Batas Mikroba') {
                if (stripos($kanonis_metode, 'akk') !== false || stripos($kanonis_metode, 'kapang') !== false || stripos($kanonis_metode, 'khamir') !== false) {
                    return array(
                        'template_id' => $tpl_by_code['MIK-02'] ?? NULL,
                        'status'      => 'OTOMATIS',
                        'alasan'      => 'Sesuai Form MIK-02: Uji Angka Kapang Khamir (AKK) Sediaan Farmasi'
                    );
                }
                return array(
                    'template_id' => $tpl_by_code['MIK-01'] ?? NULL,
                    'status'      => 'OTOMATIS',
                    'alasan'      => 'Sesuai Form MIK-01: Uji Angka Lempeng Total (ALT) Sediaan Farmasi'
                );
            }

            // 4. Mikroba Spesifik / Cemaran Mikroba Kualitatif
            if ($kanonis_param === 'Mikroba Spesifik') {
                return array(
                    'template_id' => $tpl_by_code['MIK-04'] ?? NULL,
                    'status'      => 'OTOMATIS',
                    'alasan'      => 'Sesuai Form MIK-04: Uji Cemaran Mikroba Kualitatif Sediaan Farmasi'
                );
            }

            // 5. Sterilitas
            if ($kanonis_param === 'Sterilitas') {
                return array(
                    'template_id' => NULL,
                    'status'      => 'PERLU_KONFIRMASI',
                    'alasan'      => 'Uji Sterilitas: Menunggu penerbitan Form LCP Uji Sterilitas khusus'
                );
            }
        }

        return array(
            'template_id' => NULL,
            'status'      => 'PERLU_KONFIRMASI',
            'alasan'      => 'Parameter/Metode belum terdaftar di katalog LCP resmi'
        );
    }

    /**
     * Menganalisis seluruh monografi aktif dan mengelompokkan ke pasangan parameter + metode analisa kanonis.
     * Tidak mengubah data di database (Safe Preview / Read-Only).
     * 
     * @return array
     */
    public function analisis_automap()
    {
        // 1. Ambil seluruh template aktif
        $this->db->where('status', 'Aktif');
        $tpl_raw = $this->db->get('form_templates')->result_array();
        $templates_by_id = array();
        $templates_by_code = array();
        foreach ($tpl_raw as $t) {
            $templates_by_id[$t['id']] = $t;
            $templates_by_code[$t['kode_template']] = (int)$t['id'];
        }

        // 2. Ambil seluruh monografi aktif
        $this->db->select('id, kode_metode, nama_zat_aktif, parameter_uji, bidang_pengujian, metode_analisa, template_id');
        $this->db->where('status', 'Aktif');
        $methods = $this->db->get($this->tabel)->result_array();

        $stats = array(
            'total_monografi'         => count($methods),
            'sudah_terpetakan'        => 0,
            'belum_terpetakan'        => 0,
            'kandidat_otomatis_valid' => 0,
            'perlu_konfirmasi'        => 0,
            'kelompok_kanonis'        => array()
        );

        foreach ($methods as $m) {
            $is_mapped = (!empty($m['template_id']) && (int)$m['template_id'] > 0);
            if ($is_mapped) {
                $stats['sudah_terpetakan']++;
            } else {
                $stats['belum_terpetakan']++;
            }

            $kanonis_param  = self::normalisasi_parameter($m['parameter_uji']);
            $kanonis_metode = self::normalisasi_metode($m['metode_analisa']);
            $kandidat       = self::cari_kandidat_lcp($kanonis_param, $kanonis_metode, $m['bidang_pengujian'], $templates_by_code);

            $group_key = $m['bidang_pengujian'] . '||' . $kanonis_param . '||' . $kanonis_metode;
            if (!isset($stats['kelompok_kanonis'][$group_key])) {
                $target_tpl = ($kandidat['template_id'] && isset($templates_by_id[$kandidat['template_id']]))
                    ? $templates_by_id[$kandidat['template_id']]
                    : NULL;

                $stats['kelompok_kanonis'][$group_key] = array(
                    'key'                  => $group_key,
                    'bidang'               => $m['bidang_pengujian'],
                    'kanonis'              => $kanonis_param,
                    'kanonis_param'        => $kanonis_param,
                    'kanonis_metode'       => $kanonis_metode,
                    'kandidat_template_id' => $kandidat['template_id'],
                    'target_kode'          => $target_tpl ? $target_tpl['kode_template'] : '-',
                    'target_nama'          => $target_tpl ? $target_tpl['nama_template'] : '-',
                    'target_kategori'      => $target_tpl ? $target_tpl['kategori'] : '-',
                    'status_kandidat'      => $kandidat['status'],
                    'alasan'               => $kandidat['alasan'],
                    'total_monografi'      => 0,
                    'terpetakan_count'     => 0,
                    'belum_map_count'      => 0,
                    'variasi_asli'         => array(),
                    'metode_analisa'       => array(),
                    'method_ids'           => array(),
                    'unmapped_method_ids'  => array()
                );
            }

            $stats['kelompok_kanonis'][$group_key]['total_monografi']++;
            $stats['kelompok_kanonis'][$group_key]['method_ids'][] = $m['id'];
            if ($is_mapped) {
                $stats['kelompok_kanonis'][$group_key]['terpetakan_count']++;
            } else {
                $stats['kelompok_kanonis'][$group_key]['belum_map_count']++;
                $stats['kelompok_kanonis'][$group_key]['unmapped_method_ids'][] = $m['id'];
            }

            if (!in_array($m['parameter_uji'], $stats['kelompok_kanonis'][$group_key]['variasi_asli'])) {
                $stats['kelompok_kanonis'][$group_key]['variasi_asli'][] = $m['parameter_uji'];
            }
            if (!empty($m['metode_analisa']) && !in_array($m['metode_analisa'], $stats['kelompok_kanonis'][$group_key]['metode_analisa'])) {
                $stats['kelompok_kanonis'][$group_key]['metode_analisa'][] = $m['metode_analisa'];
            }
        }

        // Urutkan kelompok: OTOMATIS terlebih dahulu, kemudian jumlah monografi terbanyak
        uasort($stats['kelompok_kanonis'], function($a, $b) {
            if ($a['status_kandidat'] === $b['status_kandidat']) {
                return $b['total_monografi'] <=> $a['total_monografi'];
            }
            return ($a['status_kandidat'] === 'OTOMATIS') ? -1 : 1;
        });

        // Hitung total kandidat otomatis vs perlu konfirmasi
        foreach ($stats['kelompok_kanonis'] as $grp) {
            if ($grp['status_kandidat'] === 'OTOMATIS') {
                $stats['kandidat_otomatis_valid'] += $grp['total_monografi'];
            } else {
                $stats['perlu_konfirmasi'] += $grp['total_monografi'];
            }
        }

        return $stats;
    }

    /**
     * Menerapkan Pemetaan Massal untuk Kelompok Parameter yang Disetujui Admin TI.
     * Menggunakan transaksi aman.
     * Aturan perlindungan: Secara default TIDAK menimpa baris yang sudah dipetakan secara manual,
     * kecuali jika opsi $overwrite_manual = TRUE disetujui secara eksplisit oleh Admin TI.
     * 
     * @param string $group_key Format: 'Kimia||Penetapan Kadar||KCKT / HPLC'
     * @param int $template_id ID Form LCP target
     * @param bool $overwrite_manual Apakah menimpa mapping manual yang sudah ada
     * @return array [sukses, updated_count, error]
     */
    public function terapkan_automap_kelompok($group_key, $template_id, $overwrite_manual = FALSE)
    {
        $parts = explode('||', $group_key);
        if (count($parts) < 2) {
            return array('sukses' => FALSE, 'updated_count' => 0, 'error' => 'Format kunci kelompok tidak valid.');
        }

        $bidang         = $parts[0];
        $kanonis_param  = $parts[1];
        $kanonis_metode = $parts[2] ?? NULL;

        // Validasi Template Target: Wajib aktif dan kategori cocok
        $template = $this->db->get_where('form_templates', array('id' => $template_id, 'status' => 'Aktif'))->row_array();
        if (!$template) {
            return array('sukses' => FALSE, 'updated_count' => 0, 'error' => 'Template LCP tujuan tidak ditemukan atau tidak aktif.');
        }

        if ($template['kategori'] !== $bidang) {
            return array('sukses' => FALSE, 'updated_count' => 0, 'error' => 'Kategori template (' . $template['kategori'] . ') tidak sesuai dengan bidang pengujian (' . $bidang . ').');
        }

        // Ambil semua metode aktif pada bidang ini
        $this->db->select('id, parameter_uji, metode_analisa, template_id');
        $this->db->where('bidang_pengujian', $bidang);
        $this->db->where('status', 'Aktif');
        $methods = $this->db->get($this->tabel)->result_array();

        $target_ids = array();
        foreach ($methods as $m) {
            $match_param = (self::normalisasi_parameter($m['parameter_uji']) === $kanonis_param);
            $match_metode = ($kanonis_metode === NULL || self::normalisasi_metode($m['metode_analisa']) === $kanonis_metode);

            if ($match_param && $match_metode) {
                if (!empty($m['template_id']) && !$overwrite_manual) {
                    continue;
                }
                $target_ids[] = $m['id'];
            }
        }

        if (empty($target_ids)) {
            return array('sukses' => TRUE, 'updated_count' => 0, 'pesan' => 'Tidak ada baris monografi yang perlu diperbarui (sudah terpetakan atau tidak cocok).');
        }

        $this->db->trans_start();

        $chunks = array_chunk($target_ids, 200);
        $updated_count = 0;
        foreach ($chunks as $chunk) {
            $this->db->where_in('id', $chunk);
            $this->db->update($this->tabel, array(
                'template_id' => $template_id,
                'updated_at'  => date('Y-m-d H:i:s')
            ));
            $updated_count += count($chunk);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return array('sukses' => FALSE, 'updated_count' => 0, 'error' => 'Gagal menerapkan transaksi pemetaan massal ke database.');
        }

        return array('sukses' => TRUE, 'updated_count' => $updated_count, 'error' => NULL);
    }

    /**
     * Menerapkan Pemetaan Massal Otomatis Sekali Klik (1-Click Batch Auto-Mapping)
     * Untuk seluruh kelompok yang berstatus 'OTOMATIS' dan memiliki kandidat LCP resmi valid.
     * Menjalankan seluruh pemetaan dalam 1 transaksi database yang sangat cepat dan aman.
     * 
     * @param bool $overwrite_manual Apakah menimpa baris yang sudah dipetakan manual
     * @return array [sukses, updated_count, group_count, error]
     */
    public function terapkan_automap_semua_otomatis($overwrite_manual = FALSE)
    {
        $analysis = $this->analisis_automap();
        if (empty($analysis['kelompok_kanonis'])) {
            return array('sukses' => FALSE, 'updated_count' => 0, 'group_count' => 0, 'error' => 'Tidak ada kelompok parameter yang ditemukan.');
        }

        $mapping_tasks = array(); // [template_id => array(method_ids)]
        $applied_groups = 0;

        foreach ($analysis['kelompok_kanonis'] as $grp) {
            if ($grp['status_kandidat'] === 'OTOMATIS' && !empty($grp['kandidat_template_id'])) {
                $target_tpl_id = (int)$grp['kandidat_template_id'];
                $ids = $overwrite_manual ? $grp['method_ids'] : $grp['unmapped_method_ids'];

                if (!empty($ids)) {
                    if (!isset($mapping_tasks[$target_tpl_id])) {
                        $mapping_tasks[$target_tpl_id] = array();
                    }
                    $mapping_tasks[$target_tpl_id] = array_merge($mapping_tasks[$target_tpl_id], $ids);
                    $applied_groups++;
                }
            }
        }

        if (empty($mapping_tasks)) {
            return array(
                'sukses'        => TRUE,
                'updated_count' => 0,
                'group_count'   => 0,
                'pesan'         => 'Seluruh monografi yang memenuhi kriteria otomatis sudah terpetakan sebelumnya.'
            );
        }

        $this->db->trans_start();

        $total_updated = 0;
        foreach ($mapping_tasks as $tpl_id => $ids) {
            $unique_ids = array_unique($ids);
            $chunks = array_chunk($unique_ids, 200);
            foreach ($chunks as $chunk) {
                $this->db->where_in('id', $chunk);
                $this->db->update($this->tabel, array(
                    'template_id' => $tpl_id,
                    'updated_at'  => date('Y-m-d H:i:s')
                ));
                $total_updated += count($chunk);
            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return array('sukses' => FALSE, 'updated_count' => 0, 'group_count' => 0, 'error' => 'Transaksi database gagal dijalankan.');
        }

        return array(
            'sukses'        => TRUE,
            'updated_count' => $total_updated,
            'group_count'   => $applied_groups,
            'error'         => NULL
        );
    }
}

