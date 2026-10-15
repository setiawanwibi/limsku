<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Reagen (Reagents) LIMSKU
 * 
 * Menangani transaksi dan pengelolaan Master Data Reagen Laboratorium.
 * Mendukung 8 kolom standar: No, Kode Barang, Katalog, Nama Barang, Merk,
 * Stok (kuantitas + satuan), Jenis Barang, Expired Date.
 */
class Model_Reagen extends CI_Model
{
    protected $tabel = 'reagents';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Mengambil daftar master reagen dengan filter opsional (status, jenis_barang, search)
     * 
     * @param string|null $status Filter status ('Aktif' / 'Nonaktif')
     * @param string|null $jenis Filter kategori/jenis barang
     * @param string|null $keyword Pencarian nama barang, kode, katalog, merk
     * @return array
     */
    public function ambil_semua($status = NULL, $jenis = NULL, $keyword = NULL)
    {
        if (!empty($status)) {
            $this->db->where('status', $status);
        }
        if (!empty($jenis)) {
            $this->db->where('jenis_barang', $jenis);
        }
        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('nama_barang', $keyword);
            $this->db->or_like('nama_reagen', $keyword);
            $this->db->or_like('kode_barang', $keyword);
            $this->db->or_like('katalog', $keyword);
            $this->db->or_like('merk', $keyword);
            $this->db->group_end();
        }

        $this->db->order_by('nama_barang', 'ASC');
        return $this->db->get($this->tabel)->result_array();
    }

    /**
     * Mengambil master reagen yang berstatus Aktif
     * Berguna untuk referensi dropdown pada Form Pengujian nantinya
     * 
     * @return array
     */
    public function ambil_aktif()
    {
        return $this->ambil_semua('Aktif');
    }

    /**
     * Mengambil data reagen berdasarkan ID
     * 
     * @param int $id
     * @return array|null
     */
    public function ambil_by_id($id)
    {
        return $this->db->get_where($this->tabel, array('id' => $id))->row_array();
    }

    /**
     * Mengambil daftar unik jenis barang yang terdaftar
     * 
     * @return array
     */
    public function ambil_daftar_jenis()
    {
        $this->db->distinct();
        $this->db->select('jenis_barang');
        $this->db->where('jenis_barang IS NOT NULL', NULL, FALSE);
        $this->db->where("jenis_barang != ''", NULL, FALSE);
        $this->db->order_by('jenis_barang', 'ASC');
        $res = $this->db->get($this->tabel)->result_array();
        return array_column($res, 'jenis_barang');
    }

    /**
     * Ambil lookup duplikasi untuk perbandingan data impor
     * 
     * @return array
     */
    public function ambil_lookup_duplikasi()
    {
        $this->db->select('id, kode_barang, katalog, nama_barang, nama_reagen, merk, stok, jenis_barang, expired_date');
        $items = $this->db->get($this->tabel)->result_array();

        $lookup = array(
            'by_kode'      => array(),
            'by_katalog'   => array(),
            'by_composite' => array()
        );

        foreach ($items as $it) {
            $id = $it['id'];
            if (!empty($it['kode_barang'])) {
                $lookup['by_kode'][strtoupper(trim($it['kode_barang']))] = $id;
            }
            if (!empty($it['katalog'])) {
                $lookup['by_katalog'][strtoupper(trim($it['katalog']))] = $id;
            }
            $nama = !empty($it['nama_barang']) ? $it['nama_barang'] : $it['nama_reagen'];
            $comp_key = strtoupper(trim($nama)) . '|' . strtoupper(trim((string)$it['merk']));
            $lookup['by_composite'][$comp_key] = $id;
        }

        return $lookup;
    }

    /**
     * Menambah master reagen baru
     * 
     * @param array $data
     * @return int|bool ID baris baru atau FALSE jika gagal
     */
    public function tambah($data)
    {
        // Pastikan sinkronisasi nama_reagen dan nama_barang untuk backward compatibility
        if (empty($data['nama_barang']) && !empty($data['nama_reagen'])) {
            $data['nama_barang'] = $data['nama_reagen'];
        } elseif (!empty($data['nama_barang']) && empty($data['nama_reagen'])) {
            $data['nama_reagen'] = substr($data['nama_barang'], 0, 150);
        }

        $this->db->insert($this->tabel, $data);
        return $this->db->insert_id();
    }

    /**
     * Mengubah data master reagen
     * 
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function ubah($id, $data)
    {
        // Pastikan sinkronisasi nama_reagen dan nama_barang
        if (isset($data['nama_barang']) && !isset($data['nama_reagen'])) {
            $data['nama_reagen'] = substr($data['nama_barang'], 0, 150);
        } elseif (isset($data['nama_reagen']) && !isset($data['nama_barang'])) {
            $data['nama_barang'] = $data['nama_reagen'];
        }

        $this->db->where('id', $id);
        return $this->db->update($this->tabel, $data);
    }

    /**
     * Mengubah status reagen (Aktif / Nonaktif)
     * Mengamankan referensi historis agar tidak merusak data pengujian
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
     * Menghapus master reagen secara permanen
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
     * Menghitung total reagen
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
     * Import batch data master reagen dari file Excel
     * Mendukung opsi duplikasi: 'skip' (lewati) atau 'update' (perbarui data)
     * Menggunakan transaksi database agar tidak terjadi import setengah jalan.
     * 
     * @param array $items
     * @param string $duplicate_action 'skip' atau 'update'
     * @return array
     */
    public function import_batch($items, $duplicate_action = 'skip')
    {
        $result = array(
            'sukses'   => FALSE,
            'imported' => 0,
            'updated'  => 0,
            'skipped'  => 0,
            'errors'   => array()
        );

        if (empty($items)) {
            $result['errors'][] = 'Tidak ada baris data valid untuk diimpor.';
            return $result;
        }

        $this->db->trans_start();

        $lookup = $this->ambil_lookup_duplikasi();

        foreach ($items as $item) {
            $row_num   = $item['excel_row'];
            $kode      = !empty($item['kode_barang']) ? strtoupper(trim($item['kode_barang'])) : '';
            $katalog   = !empty($item['katalog']) ? strtoupper(trim($item['katalog'])) : '';
            $nama      = trim($item['nama_barang']);
            $merk      = !empty($item['merk']) ? trim($item['merk']) : '';
            $comp_key  = strtoupper($nama) . '|' . strtoupper($merk);

            $existing_id = NULL;

            // Cek duplikasi berurutan: kode_barang -> katalog -> (nama + merk)
            if ($kode !== '' && isset($lookup['by_kode'][$kode])) {
                $existing_id = $lookup['by_kode'][$kode];
            } elseif ($katalog !== '' && isset($lookup['by_katalog'][$katalog])) {
                $existing_id = $lookup['by_katalog'][$katalog];
            } elseif (isset($lookup['by_composite'][$comp_key])) {
                $existing_id = $lookup['by_composite'][$comp_key];
            }

            if ($existing_id !== NULL) {
                if ($duplicate_action === 'update') {
                    // Update data lama secara eksplisit
                    $update_data = array(
                        'kode_barang'  => !empty($item['kode_barang']) ? $item['kode_barang'] : NULL,
                        'katalog'      => !empty($item['katalog']) ? $item['katalog'] : NULL,
                        'nama_barang'  => $nama,
                        'nama_reagen'  => substr($nama, 0, 150),
                        'merk'         => !empty($merk) ? $merk : NULL,
                        'stok'         => !empty($item['stok']) ? $item['stok'] : '0 BOTOL',
                        'jenis_barang' => !empty($item['jenis_barang']) ? $item['jenis_barang'] : 'Reagen Cair',
                        'expired_date' => (!empty($item['expired_date']) && $item['expired_date'] !== '0000-00-00') ? $item['expired_date'] : NULL,
                        'status'       => 'Aktif',
                        'updated_at'   => date('Y-m-d H:i:s')
                    );
                    $this->db->where('id', $existing_id);
                    $this->db->update($this->tabel, $update_data);
                    $result['updated']++;
                } else {
                    // Lewati duplikat
                    $result['skipped']++;
                }
            } else {
                // Insert baru
                $insert_data = array(
                    'kode_barang'  => !empty($item['kode_barang']) ? $item['kode_barang'] : NULL,
                    'katalog'      => !empty($item['katalog']) ? $item['katalog'] : NULL,
                    'nama_barang'  => $nama,
                    'nama_reagen'  => substr($nama, 0, 150),
                    'merk'         => !empty($merk) ? $merk : NULL,
                    'stok'         => !empty($item['stok']) ? $item['stok'] : '0 BOTOL',
                    'jenis_barang' => !empty($item['jenis_barang']) ? $item['jenis_barang'] : 'Reagen Cair',
                    'expired_date' => (!empty($item['expired_date']) && $item['expired_date'] !== '0000-00-00') ? $item['expired_date'] : NULL,
                    'status'       => 'Aktif',
                    'created_at'   => date('Y-m-d H:i:s'),
                    'updated_at'   => date('Y-m-d H:i:s')
                );
                $this->db->insert($this->tabel, $insert_data);
                $new_id = $this->db->insert_id();

                // Daftarkan ke lookup in-memory agar duplikat dalam 1 file yang sama juga terdeteksi
                if ($kode !== '') {
                    $lookup['by_kode'][$kode] = $new_id;
                }
                if ($katalog !== '') {
                    $lookup['by_katalog'][$katalog] = $new_id;
                }
                $lookup['by_composite'][$comp_key] = $new_id;

                $result['imported']++;
            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === TRUE) {
            $result['sukses'] = TRUE;
        } else {
            $result['sukses']   = FALSE;
            $result['errors'][] = 'Transaksi database gagal atau di-rollback.';
        }

        return $result;
    }
}
