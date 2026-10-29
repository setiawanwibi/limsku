<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Master Baku Pembanding (Data Baku) LIMSKU
 */
class Model_Baku extends CI_Model
{
    protected $tabel = 'master_baku';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Ambil semua data baku dengan filter lokasi dan kata kunci
     */
    public function ambil_semua($lokasi = NULL, $keyword = NULL, $limit = NULL, $offset = 0)
    {
        if (!empty($lokasi)) {
            $this->db->where('lokasi_penyimpanan', $lokasi);
        }

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('nama_baku', $keyword);
            $this->db->or_like('no_kontrol', $keyword);
            $this->db->or_like('kode_posisi', $keyword);
            $this->db->or_like('kemurnian_raw', $keyword);
            $this->db->group_end();
        }

        $this->db->order_by('nama_baku', 'ASC');
        $this->db->order_by('no_kontrol', 'ASC');

        if ($limit !== NULL) {
            $this->db->limit($limit, $offset);
        }

        return $this->db->get($this->tabel)->result_array();
    }

    /**
     * Hitung total data baku untuk pagination
     */
    public function hitung_semua($lokasi = NULL, $keyword = NULL)
    {
        if (!empty($lokasi)) {
            $this->db->where('lokasi_penyimpanan', $lokasi);
        }

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('nama_baku', $keyword);
            $this->db->or_like('no_kontrol', $keyword);
            $this->db->or_like('kode_posisi', $keyword);
            $this->db->or_like('kemurnian_raw', $keyword);
            $this->db->group_end();
        }

        return $this->db->count_all_results($this->tabel);
    }

    /**
     * Ambil data baku berdasarkan ID
     */
    public function ambil_by_id($id)
    {
        return $this->db->get_where($this->tabel, array('id' => $id))->row_array();
    }

    /**
     * Ambil data baku berdasarkan No Kontrol
     */
    public function ambil_by_no_kontrol($no_kontrol)
    {
        return $this->db->get_where($this->tabel, array('no_kontrol' => $no_kontrol))->row_array();
    }

    /**
     * Cari baku pembanding untuk autocomplete pengujian
     */
    public function cari_baku($term, $limit = 25)
    {
        $this->db->select('id, nama_baku, no_kontrol, kode_posisi, kadar_air, kadar_baku, lokasi_penyimpanan, kemurnian_raw, penyimpanan');
        $this->db->group_start();
        $this->db->like('nama_baku', $term);
        $this->db->or_like('no_kontrol', $term);
        $this->db->group_end();
        $this->db->order_by('nama_baku', 'ASC');
        $this->db->limit($limit);
        return $this->db->get($this->tabel)->result_array();
    }

    /**
     * Ambil daftar lokasi unik
     */
    public function ambil_daftar_lokasi()
    {
        $this->db->distinct();
        $this->db->select('lokasi_penyimpanan');
        $this->db->where('lokasi_penyimpanan IS NOT NULL', NULL, FALSE);
        $this->db->where("lokasi_penyimpanan != ''", NULL, FALSE);
        $this->db->order_by('lokasi_penyimpanan', 'ASC');
        $res = $this->db->get($this->tabel)->result_array();
        return array_column($res, 'lokasi_penyimpanan');
    }

    /**
     * Tambah data baku
     */
    public function tambah($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        if ($this->db->insert($this->tabel, $data)) {
            return $this->db->insert_id();
        }
        return FALSE;
    }

    /**
     * Ubah data baku
     */
    public function ubah($id, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->where('id', $id);
        return $this->db->update($this->tabel, $data);
    }

    /**
     * Hapus data baku
     */
    public function hapus($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete($this->tabel);
    }

    /**
     * Simpan batch dari Excel Impor
     */
    public function simpan_batch($dataBatch)
    {
        if (empty($dataBatch)) return 0;
        return $this->db->insert_batch($this->tabel, $dataBatch);
    }

    /**
     * Normalisasi teks Kemurnian menjadi kadar_air dan kadar_baku
     */
    public static function normalisasi_kemurnian($text)
    {
        $kadar_air = NULL;
        $kadar_baku = NULL;

        if (empty($text) || trim($text) === '-') {
            return array('kadar_air' => NULL, 'kadar_baku' => NULL);
        }

        $cleaned = str_replace(array("\r\n", "\r", "\n"), " [SEP] ", $text);

        // Cari Kadar air: contoh "Kadar air = 8,33%" atau "Kadar air : 5,41%"
        if (preg_match('/Kadar\s*air\s*[:=]\s*([\d]+[.,][\d]+|\d+)\s*%/i', $cleaned, $mAir)) {
            $kadar_air = floatval(str_replace(',', '.', $mAir[1]));
        }

        // Cari KCKT kadar baku: contoh "KCKT = 97,60%" atau "KCKT = 98,64%" atau "KCKT = 100,15"
        if (preg_match('/KCKT\s*[:=]\s*([\d]+[.,][\d]+|\d+)\s*(?:%|±|\+-)/i', $cleaned, $mKckt)) {
            $kadar_baku = floatval(str_replace(',', '.', $mKckt[1]));
        }

        return array('kadar_air' => $kadar_air, 'kadar_baku' => $kadar_baku);
    }
}
