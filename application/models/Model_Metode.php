<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Metode LIMSKU
 * 
 * Menangani Master Data Metode Pengujian Laboratorium (Kimia & Mikrobiologi).
 */
class Model_Metode extends CI_Model
{
    protected $tabel = 'methods';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Mengambil daftar metode
     * 
     * @param string|null $kategori Filter kategori ('Kimia' / 'Mikrobiologi')
     * @param string|null $status Filter status ('Aktif' / 'Nonaktif')
     * @return array
     */
    public function ambil_semua($kategori = NULL, $status = NULL)
    {
        if ($kategori) {
            $this->db->where('kategori', $kategori);
        }
        if ($status) {
            $this->db->where('status', $status);
        }
        $this->db->order_by('nama_metode', 'ASC');
        return $this->db->get($this->tabel)->result_array();
    }

    /**
     * Mengambil metode yang berstatus Aktif
     * 
     * @param string|null $kategori
     * @return array
     */
    public function ambil_aktif($kategori = NULL)
    {
        return $this->ambil_semua($kategori, 'Aktif');
    }

    /**
     * Mengambil data metode berdasarkan ID
     * 
     * @param int $id
     * @return array|null
     */
    public function ambil_by_id($id)
    {
        return $this->db->get_where($this->tabel, array('id' => $id))->row_array();
    }

    /**
     * Menambah metode baru
     * 
     * @param array $data
     * @return int|bool
     */
    public function tambah($data)
    {
        if ($this->db->insert($this->tabel, $data)) {
            return $this->db->insert_id();
        }
        return FALSE;
    }

    /**
     * Mengubah data metode
     * 
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function ubah($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->tabel, $data);
    }

    /**
     * Mengubah status metode (Aktif / Nonaktif)
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
     * Menghapus metode
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
     * Menghitung total metode
     * 
     * @return int
     */
    public function hitung_semua()
    {
        return $this->db->count_all($this->tabel);
    }
}
