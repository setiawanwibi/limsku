<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Kelas Terapi LIMSKU
 * 
 * Menangani Master Data Kelas Terapi Obat untuk pengelompokan monografi dan sampel.
 */
class Model_Kelas_Terapi extends CI_Model
{
    protected $tabel = 'therapeutic_classes';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Mengambil semua daftar kelas terapi
     * 
     * @param string|null $status
     * @return array
     */
    public function ambil_semua($status = NULL)
    {
        if ($status) {
            $this->db->where('status', $status);
        }
        $this->db->order_by('nama_kelas_terapi', 'ASC');
        return $this->db->get($this->tabel)->result_array();
    }

    /**
     * Mengambil kelas terapi yang aktif
     * 
     * @return array
     */
    public function ambil_aktif()
    {
        return $this->ambil_semua('Aktif');
    }

    /**
     * Mengambil data berdasarkan ID
     * 
     * @param int $id
     * @return array|null
     */
    public function ambil_by_id($id)
    {
        return $this->db->get_where($this->tabel, array('id' => $id))->row_array();
    }

    /**
     * Mengambil data berdasarkan Nama (case-insensitive & spasi ternormalisasi)
     * 
     * @param string $nama
     * @return array|null
     */
    public function ambil_by_nama($nama)
    {
        $nama_clean = trim(preg_replace('/\s+/', ' ', (string)$nama));
        $this->db->where('LOWER(nama_kelas_terapi)', strtolower($nama_clean));
        return $this->db->get($this->tabel)->row_array();
    }

    /**
     * Menambah kelas terapi baru
     * 
     * @param array $data
     * @return int|bool
     */
    public function tambah($data)
    {
        if (isset($data['nama_kelas_terapi'])) {
            $data['nama_kelas_terapi'] = trim(preg_replace('/\s+/', ' ', (string)$data['nama_kelas_terapi']));
        }
        $this->db->insert($this->tabel, $data);
        return $this->db->insert_id();
    }

    /**
     * Mengubah data kelas terapi
     * 
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function ubah($id, $data)
    {
        if (isset($data['nama_kelas_terapi'])) {
            $data['nama_kelas_terapi'] = trim(preg_replace('/\s+/', ' ', (string)$data['nama_kelas_terapi']));
        }
        $this->db->where('id', $id);
        return $this->db->update($this->tabel, $data);
    }

    /**
     * Mengubah status aktif/nonaktif
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
     * Mengecek apakah kelas terapi sedang digunakan oleh data Master Metode
     * 
     * @param int $id
     * @return int Jumlah referensi di tabel methods
     */
    public function hitung_penggunaan($id)
    {
        $this->db->where('kelas_terapi_id', $id);
        return $this->db->count_all_results('methods');
    }

    /**
     * Hapus permanen jika belum pernah direferensikan
     * 
     * @param int $id
     * @return bool
     */
    public function hapus($id)
    {
        if ($this->hitung_penggunaan($id) > 0) {
            return FALSE; // Proteksi referensi data
        }
        $this->db->where('id', $id);
        return $this->db->delete($this->tabel);
    }

    /**
     * Resolusi cepat ID kelas terapi (Ambil ID jika sudah ada, atau buat baru jika belum ada)
     * Menggunakan sanitasi nama tanpa penomoran awal (misal "1. KELAS TERAPI..." -> "KELAS TERAPI...")
     * 
     * @param string $raw_nama
     * @return int|null ID kelas terapi
     */
    public function dapatkan_atau_buat($raw_nama)
    {
        $nama_clean = self::bersihkan_nama_header($raw_nama);
        if (empty($nama_clean)) {
            return NULL;
        }

        $existing = $this->ambil_by_nama($nama_clean);
        if ($existing) {
            return (int)$existing['id'];
        }

        // Buat record baru
        $new_id = $this->tambah(array(
            'nama_kelas_terapi' => $nama_clean,
            'status'            => 'Aktif'
        ));

        return $new_id ? (int)$new_id : NULL;
    }

    /**
     * Membersihkan string header kelas terapi (hapus nomor urut awal, titik, strip, spasi berlebih)
     * 
     * @param string $str
     * @return string
     */
    public static function bersihkan_nama_header($str)
    {
        $str = trim((string)$str);
        if ($str === '') {
            return '';
        }

        // Hapus penomoran di awal string (contoh: "1. ", "12. ", "I. ", "A. ", "1) ")
        $str = preg_replace('/^([0-9]+|[ivxlcdm]+|[a-z])[\.\)]\s*/iu', '', $str);

        // Normalisasi multiple spasi
        $str = preg_replace('/\s+/', ' ', $str);

        return trim($str);
    }
}
