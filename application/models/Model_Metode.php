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
        $this->db->distinct();
        $this->db->select('nama_zat_aktif, jenis_sediaan, bentuk_sediaan');
        $this->db->group_start();
        $this->db->where('bidang_pengujian', $bidang);
        $this->db->or_where('kategori', $bidang);
        $this->db->group_end();
        $this->db->where('status', 'Aktif');
        $this->db->where("nama_zat_aktif IS NOT NULL AND nama_zat_aktif != ''", NULL, FALSE);
        $this->db->order_by('nama_zat_aktif', 'ASC');
        return $this->db->get($this->tabel)->result_array();
    }

    /**
     * Ambil parameter monografi berdasarkan zat aktif terpilih beserta relasi LCP dan kelas terapi
     * 
     * @param string $zat_aktif
     * @param string $bidang
     * @return array
     */
    public function ambil_parameter_by_zat_aktif($zat_aktif, $bidang = 'Kimia')
    {
        $this->db->select('m.*, tc.nama_kelas_terapi, ft.kode_template, ft.nama_template, ft.kategori as kategori_template, ft.skema_form');
        $this->db->from($this->tabel . ' m');
        $this->db->join('therapeutic_classes tc', 'tc.id = m.kelas_terapi_id', 'left');
        $this->db->join('form_templates ft', 'ft.id = m.template_id', 'left');
        $this->db->where('m.nama_zat_aktif', $zat_aktif);
        $this->db->group_start();
        $this->db->where('m.bidang_pengujian', $bidang);
        $this->db->or_where('m.kategori', $bidang);
        $this->db->group_end();
        $this->db->where('m.status', 'Aktif');
        $this->db->order_by('m.parameter_uji', 'ASC');
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
        if (preg_match('/^(keseragaman sediaan|keseragaman kandungan|keragaman bobot)\b/i', $lower)) {
            return 'Keseragaman Sediaan';
        }
        if (preg_match('/^ph\b/i', $lower)) {
            return 'pH';
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
     * Menentukan kandidat LCP resmi aktif berdasarkan karakteristik metode dan bidang.
     * Menolak penetapan jika bidang tidak cocok atau metode ambigu.
     * 
     * @param string $kanonis
     * @param string $bidang 'Kimia' / 'Mikrobiologi'
     * @param string|null $metode_analisa
     * @param array $templates_by_id
     * @return array [template_id, status, alasan]
     */
    public static function cari_kandidat_lcp($kanonis, $bidang, $metode_analisa, $templates_by_id)
    {
        if ($bidang === 'Kimia') {
            if ($kanonis === 'Penetapan Kadar' && isset($templates_by_id[1])) {
                return array('template_id' => 1, 'status' => 'OTOMATIS', 'alasan' => 'Sesuai Form 1: Penetapan Kadar Obat (Kimia)');
            }
            if ($kanonis === 'Disolusi' && isset($templates_by_id[2])) {
                return array('template_id' => 2, 'status' => 'OTOMATIS', 'alasan' => 'Sesuai Form 2: Uji Disolusi (Kimia)');
            }
            if ($kanonis === 'Keseragaman Sediaan' && isset($templates_by_id[3])) {
                return array('template_id' => 3, 'status' => 'OTOMATIS', 'alasan' => 'Sesuai Form 3: Uji Keseragaman Bobot / Ukuran (Kimia)');
            }
            if ($kanonis === 'pH' && isset($templates_by_id[4])) {
                return array('template_id' => 4, 'status' => 'OTOMATIS', 'alasan' => 'Sesuai Form 4: Uji pH / Derajat Keasaman (Kimia)');
            }
            if ($kanonis === 'Identifikasi' && isset($templates_by_id[7])) {
                return array('template_id' => 7, 'status' => 'OTOMATIS', 'alasan' => 'Sesuai Form 7: Uji Identifikasi Kualitatif (Kimia)');
            }
            if ($kanonis === 'Waktu Hancur' && isset($templates_by_id[9])) {
                return array('template_id' => 9, 'status' => 'OTOMATIS', 'alasan' => 'Sesuai Form 9: Uji Waktu Hancur Tablet (Kimia)');
            }
            if ($kanonis === 'Pemerian') {
                return array('template_id' => 5, 'status' => 'PERLU_KONFIRMASI', 'alasan' => 'Pemerian fisik/organoleptis dapat menggunakan Form 5 (Kejernihan & Warna) atau Form Khusus Fisik');
            }
        } elseif ($bidang === 'Mikrobiologi') {
            if ($kanonis === 'Sterilitas' && isset($templates_by_id[13])) {
                return array('template_id' => 13, 'status' => 'OTOMATIS', 'alasan' => 'Sesuai Form 13: Uji Sterilitas (Mikrobiologi)');
            }
            if ($kanonis === 'Batas Mikroba' && isset($templates_by_id[14])) {
                return array('template_id' => 14, 'status' => 'OTOMATIS', 'alasan' => 'Sesuai Form 14: Uji Batas Mikroba / ALT & AKK (Mikrobiologi)');
            }
            if ($kanonis === 'Potensi Antibiotik' && isset($templates_by_id[15])) {
                return array('template_id' => 15, 'status' => 'OTOMATIS', 'alasan' => 'Sesuai Form 15: Uji Potensi Antibiotik (Mikrobiologi)');
            }
            if ($kanonis === 'Endotoksin Bakteri' && isset($templates_by_id[17])) {
                return array('template_id' => 17, 'status' => 'OTOMATIS', 'alasan' => 'Sesuai Form 17: Uji Endotoksin Bakteri / LAL Test (Mikrobiologi)');
            }
            if ($kanonis === 'Mikroba Spesifik') {
                return array('template_id' => 14, 'status' => 'PERLU_KONFIRMASI', 'alasan' => 'Uji mikroba spesifik dapat dirujuk ke Form 14 Batas Mikroba atau Form Khusus');
            }
        }

        return array('template_id' => NULL, 'status' => 'PERLU_KONFIRMASI', 'alasan' => 'Belum ada katalog LCP aktif yang cocok dengan parameter ini');
    }

    /**
     * Menganalisis seluruh monografi aktif dan mengelompokkan ke parameter kanonis & kandidat LCP.
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
        foreach ($tpl_raw as $t) {
            $templates_by_id[$t['id']] = $t;
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
            $is_mapped = (!empty($m['template_id']));
            if ($is_mapped) {
                $stats['sudah_terpetakan']++;
            } else {
                $stats['belum_terpetakan']++;
            }

            $kanonis = self::normalisasi_parameter($m['parameter_uji']);
            $kandidat = self::cari_kandidat_lcp($kanonis, $m['bidang_pengujian'], $m['metode_analisa'], $templates_by_id);

            $group_key = $m['bidang_pengujian'] . '||' . $kanonis;
            if (!isset($stats['kelompok_kanonis'][$group_key])) {
                $target_tpl = ($kandidat['template_id'] && isset($templates_by_id[$kandidat['template_id']]))
                    ? $templates_by_id[$kandidat['template_id']]
                    : NULL;

                $stats['kelompok_kanonis'][$group_key] = array(
                    'key'                  => $group_key,
                    'bidang'               => $m['bidang_pengujian'],
                    'kanonis'              => $kanonis,
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
                    'method_ids'           => array()
                );
            }

            $stats['kelompok_kanonis'][$group_key]['total_monografi']++;
            $stats['kelompok_kanonis'][$group_key]['method_ids'][] = $m['id'];
            if ($is_mapped) {
                $stats['kelompok_kanonis'][$group_key]['terpetakan_count']++;
            } else {
                $stats['kelompok_kanonis'][$group_key]['belum_map_count']++;
            }

            if (!in_array($m['parameter_uji'], $stats['kelompok_kanonis'][$group_key]['variasi_asli'])) {
                $stats['kelompok_kanonis'][$group_key]['variasi_asli'][] = $m['parameter_uji'];
            }
            if (!empty($m['metode_analisa']) && !in_array($m['metode_analisa'], $stats['kelompok_kanonis'][$group_key]['metode_analisa'])) {
                $stats['kelompok_kanonis'][$group_key]['metode_analisa'][] = $m['metode_analisa'];
            }
        }

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
     * Aturan perlindungan: Secara default TIDAK menimpa baris yang sudah dipetakan secara manual (template_id IS NULL),
     * kecuali jika opsi $overwrite_manual = TRUE disetujui secara eksplisit oleh Admin TI.
     * 
     * @param string $group_key Format: 'Kimia||Penetapan Kadar'
     * @param int $template_id ID Form LCP target
     * @param bool $overwrite_manual Apakah menimpa mapping manual yang sudah ada
     * @return array [sukses, updated_count, error]
     */
    public function terapkan_automap_kelompok($group_key, $template_id, $overwrite_manual = FALSE)
    {
        $parts = explode('||', $group_key);
        if (count($parts) !== 2) {
            return array('sukses' => FALSE, 'updated_count' => 0, 'error' => 'Format kunci kelompok tidak valid.');
        }

        $bidang  = $parts[0];
        $kanonis = $parts[1];

        // Validasi Template Target: Wajib aktif dan kategori cocok
        $template = $this->db->get_where('form_templates', array('id' => $template_id, 'status' => 'Aktif'))->row_array();
        if (!$template) {
            return array('sukses' => FALSE, 'updated_count' => 0, 'error' => 'Template LCP tujuan tidak ditemukan atau tidak aktif.');
        }

        if ($template['kategori'] !== $bidang) {
            return array('sukses' => FALSE, 'updated_count' => 0, 'error' => 'Kategori template (' . $template['kategori'] . ') tidak sesuai dengan bidang pengujian (' . $bidang . ').');
        }

        // Ambil semua metode aktif pada bidang ini
        $this->db->select('id, parameter_uji, template_id');
        $this->db->where('bidang_pengujian', $bidang);
        $this->db->where('status', 'Aktif');
        $methods = $this->db->get($this->tabel)->result_array();

        $target_ids = array();
        foreach ($methods as $m) {
            if (self::normalisasi_parameter($m['parameter_uji']) === $kanonis) {
                // Perlindungan mapping manual: lewati jika sudah memiliki template_id dan overwrite_manual = FALSE
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

        // Terapkan mapping massal dalam chunks
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
}

