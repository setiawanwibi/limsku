<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Alat Laboratorium (Instruments) LIMSKU
 * 
 * Menangani transaksi dan pengelolaan inventaris alat laboratorium.
 */
class Model_Alat extends CI_Model
{
    protected $tabel = 'instruments';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Mengambil daftar alat laboratorium dengan filter opsional
     * 
     * @param string|null $status Filter status ('Aktif' / 'Nonaktif')
     * @return array
     */
    public function ambil_semua($status = NULL)
    {
        if ($status) {
            $this->db->where('status', $status);
        }
        $this->db->order_by('id', 'DESC');
        return $this->db->get($this->tabel)->result_array();
    }

    /**
     * Mengambil alat laboratorium yang berstatus Aktif
     * 
     * @return array
     */
    public function ambil_aktif()
    {
        return $this->ambil_semua('Aktif');
    }

    /**
     * Mengambil data alat berdasarkan ID
     * 
     * @param int $id
     * @return array|null
     */
    public function ambil_by_id($id)
    {
        return $this->db->get_where($this->tabel, array('id' => $id))->row_array();
    }

    /**
     * Mengambil data alat berdasarkan Kode Barang
     * 
     * @param string $kode_barang
     * @return array|null
     */
    public function ambil_by_kode($kode_barang)
    {
        return $this->db->get_where($this->tabel, array('kode_barang' => $kode_barang))->row_array();
    }

    /**
     * Generate Kode Barang unik berurutan dengan format ALT-0001
     * 
     * @return string
     */
    public function generate_kode_barang()
    {
        // Mengambil nomor tertinggi dari kode_barang yang ada (termasuk yang nonaktif)
        $this->db->select("MAX(CAST(SUBSTRING(kode_barang, 5) AS UNSIGNED)) as max_num");
        $this->db->like('kode_barang', 'ALT-', 'after');
        $row = $this->db->get($this->tabel)->row_array();

        $next_num = (!empty($row['max_num'])) ? (int)$row['max_num'] + 1 : 1;
        return sprintf('ALT-%04d', $next_num);
    }

    /**
     * Menambah alat laboratorium baru
     * Menggunakan strategi retry jika terjadi collision pada kode barang akibat konkurensi
     * 
     * @param array $data
     * @return int|bool
     */
    public function tambah($data)
    {
        $max_attempts = 5;
        $attempt = 0;

        while ($attempt < $max_attempts) {
            $attempt++;

            // Jika kode_barang kosong atau terindikasi perlu auto-generate
            if (empty($data['kode_barang'])) {
                $data['kode_barang'] = $this->generate_kode_barang();
            }

            // Coba insert
            $this->db->db_debug = FALSE; // Tangani error secara terprogram
            $result = $this->db->insert($this->tabel, $data);
            $error = $this->db->error();
            $this->db->db_debug = (ENVIRONMENT !== 'production');

            if ($result) {
                return $this->db->insert_id();
            }

            // Jika duplicate entry error (Error code 1062 pada MySQL), generate ulang nomor berikutnya
            if (isset($error['code']) && $error['code'] == 1062) {
                $data['kode_barang'] = $this->generate_kode_barang();
                continue;
            }

            // Error lain yang bukan duplikasi kode
            log_message('error', 'Gagal insert alat laboratorium: ' . json_encode($error));
            return FALSE;
        }

        return FALSE;
    }

    /**
     * Mengubah data alat laboratorium
     * Catatan: kode_barang diproteksi agar tidak berubah saat edit
     * 
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function ubah($id, $data)
    {
        // Pastikan kode_barang tidak ditimpa saat update
        if (isset($data['kode_barang'])) {
            unset($data['kode_barang']);
        }

        $this->db->where('id', $id);
        return $this->db->update($this->tabel, $data);
    }

    /**
     * Mengubah status alat (Aktif / Nonaktif)
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
     * Menghapus alat laboratorium secara aman
     * 
     * @param int $id
     * @return bool
     */
    public function hapus($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete($this->tabel);
    }

    /**
     * Menghitung total alat laboratorium
     * 
     * @param string|null $status
     * @return int
     */
    public function hitung_semua($status = NULL)
    {
        if ($status) {
            $this->db->where('status', $status);
        }
        return $this->db->count_all_results($this->tabel);
    }

    /**
     * Mengambil daftar ruangan unik yang sudah ada di inventaris alat
     * Berguna untuk autocomplete/pilihan input ruangan
     * 
     * @return array
     */
    public function ambil_daftar_ruangan()
    {
        $this->db->distinct();
        $this->db->select('kode_ruangan, nama_ruangan');
        $this->db->where('kode_ruangan IS NOT NULL', NULL, FALSE);
        $this->db->where("kode_ruangan != ''", NULL, FALSE);
        $this->db->order_by('kode_ruangan', 'ASC');
        return $this->db->get($this->tabel)->result_array();
    }

    /**
     * Import batch data alat laboratorium dengan transaksi database,
     * penomoran kode barang berurutan dari nomor terbesar,
     * dan penanganan bentrokan data duplikat secara aman.
     * 
     * @param array $items Array data alat yang sudah tervalidasi
     * @return array Status hasil transaksi ['sukses' => bool, 'imported' => int, 'skipped' => int, 'errors' => array]
     */
    public function import_batch($items)
    {
        $result = array(
            'sukses'   => FALSE,
            'imported' => 0,
            'skipped'  => 0,
            'errors'   => array()
        );

        if (empty($items)) {
            $result['errors'][] = 'Tidak ada item data valid untuk di-import.';
            return $result;
        }

        $this->db->trans_start();

        // 1. Dapatkan nomor urut awal dari kode terbesar yang pernah tercatat
        $this->db->select("MAX(CAST(SUBSTRING(kode_barang, 5) AS UNSIGNED)) as max_num");
        $this->db->like('kode_barang', 'ALT-', 'after');
        $row = $this->db->get($this->tabel)->row_array();
        $current_num = (!empty($row['max_num'])) ? (int)$row['max_num'] : 0;

        $imported_count = 0;
        $skipped_count  = 0;

        foreach ($items as $idx => $item) {
            $current_num++;
            $item_kode = sprintf('ALT-%04d', $current_num);

            $data_insert = array(
                'kode_barang'       => $item_kode,
                'nama_barang'       => $item['nama_barang'],
                'merk_tipe'         => !empty($item['merk_tipe']) ? $item['merk_tipe'] : NULL,
                'perangkat_lunak'   => !empty($item['perangkat_lunak']) ? $item['perangkat_lunak'] : NULL,
                'detektor'          => !empty($item['detektor']) ? $item['detektor'] : NULL,
                'tanggal_perolehan' => !empty($item['tanggal_perolehan']) ? $item['tanggal_perolehan'] : NULL,
                'kondisi_adm'       => !empty($item['kondisi_adm']) ? $item['kondisi_adm'] : 'Baik',
                'kode_ruangan'      => !empty($item['kode_ruangan']) ? $item['kode_ruangan'] : NULL,
                'nama_ruangan'      => !empty($item['nama_ruangan']) ? $item['nama_ruangan'] : NULL,
                'status'            => !empty($item['status']) ? $item['status'] : 'Aktif',
                'created_at'        => date('Y-m-d H:i:s'),
                'updated_at'        => date('Y-m-d H:i:s')
            );

            // Coba insert
            $this->db->db_debug = FALSE;
            $ins = $this->db->insert($this->tabel, $data_insert);
            $db_error = $this->db->error();
            $this->db->db_debug = (ENVIRONMENT !== 'production');

            if ($ins) {
                $imported_count++;
            } else {
                // Jika bentrok unique key kode_barang karena konkurensi
                if (isset($db_error['code']) && $db_error['code'] == 1062) {
                    // Cari lagi max number terkini
                    $this->db->select("MAX(CAST(SUBSTRING(kode_barang, 5) AS UNSIGNED)) as max_num");
                    $this->db->like('kode_barang', 'ALT-', 'after');
                    $row_retry = $this->db->get($this->tabel)->row_array();
                    $current_num = (!empty($row_retry['max_num'])) ? (int)$row_retry['max_num'] + 1 : $current_num + 1;
                    $data_insert['kode_barang'] = sprintf('ALT-%04d', $current_num);

                    $this->db->db_debug = FALSE;
                    $retry_ins = $this->db->insert($this->tabel, $data_insert);
                    $this->db->db_debug = (ENVIRONMENT !== 'production');

                    if ($retry_ins) {
                        $imported_count++;
                    } else {
                        $skipped_count++;
                        $result['errors'][] = 'Baris ' . ($item['excel_row'] ?? ($idx + 1)) . ': Gagal menyimpan data (' . $data_insert['nama_barang'] . ').';
                    }
                } else {
                    $skipped_count++;
                    $result['errors'][] = 'Baris ' . ($item['excel_row'] ?? ($idx + 1)) . ': ' . ($db_error['message'] ?? 'Error database');
                }
            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $result['sukses'] = FALSE;
            $result['errors'][] = 'Transaksi database gagal dan telah di-rollback.';
            return $result;
        }

        $result['sukses']   = TRUE;
        $result['imported'] = $imported_count;
        $result['skipped']  = $skipped_count;
        return $result;
    }
}
