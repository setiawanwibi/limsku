<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Template Form Pengujian LIMSKU
 * 
 * Menangani Master Data Template Form Pengujian Dinamis (Kimia & Mikrobiologi).
 */
class Model_Template extends CI_Model
{
    protected $tabel = 'form_templates';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Mengambil daftar template form pengujian
     * 
     * @param string|null $kategori Filter kategori ('Kimia' / 'Mikrobiologi')
     * @param string|null $status Filter status ('Aktif' / 'Nonaktif')
     * @return array
     */
    public function ambil_semua($kategori = NULL, $status = NULL)
    {
        $this->db->select('t.*, m.nama_metode, m.kode_metode, p.nama_parameter, p.kode_parameter');
        $this->db->from($this->tabel . ' t');
        $this->db->join('methods m', 'm.id = t.method_id', 'left');
        $this->db->join('parameters p', 'p.id = t.parameter_id', 'left');

        if ($kategori) {
            $this->db->where('t.kategori', $kategori);
        }
        if ($status) {
            $this->db->where('t.status', $status);
        }
        $this->db->order_by('t.nama_template', 'ASC');
        return $this->db->get()->result_array();
    }

    /**
     * Mengambil template form yang berstatus Aktif
     * 
     * @param string|null $kategori
     * @return array
     */
    public function ambil_aktif($kategori = NULL)
    {
        return $this->ambil_semua($kategori, 'Aktif');
    }

    /**
     * Mengambil template form berdasarkan ID
     * 
     * @param int $id
     * @return array|null
     */
    public function ambil_by_id($id)
    {
        $this->db->select('t.*, m.nama_metode, m.kode_metode, p.nama_parameter, p.kode_parameter');
        $this->db->from($this->tabel . ' t');
        $this->db->join('methods m', 'm.id = t.method_id', 'left');
        $this->db->join('parameters p', 'p.id = t.parameter_id', 'left');
        $this->db->where('t.id', $id);
        return $this->db->get()->row_array();
    }

    /**
     * Mengambil template form berdasarkan ID Metode
     * 
     * @param int $method_id
     * @return array|null
     */
    public function ambil_by_method($method_id)
    {
        $this->db->select('t.*, m.nama_metode, m.kode_metode, p.nama_parameter, p.kode_parameter');
        $this->db->from($this->tabel . ' t');
        $this->db->join('methods m', 'm.id = t.method_id', 'left');
        $this->db->join('parameters p', 'p.id = t.parameter_id', 'left');
        $this->db->where('t.method_id', $method_id);
        $this->db->where('t.status', 'Aktif');
        return $this->db->get()->row_array();
    }

    /**
     * Menambah template form baru
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
     * Mengubah data template form
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
     * Mengubah status template (Aktif / Nonaktif)
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
     * Menghapus template
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
     * Menghitung total template
     * 
     * @return int
     */
    public function hitung_semua()
    {
        return $this->db->count_all($this->tabel);
    }
}
