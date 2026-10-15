<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Skema Form Deklaratif Resmi LCP Cemaran Mikroba Kualitatif Sediaan Farmasi
 * Kode Dokumen Resmi: POM-06.02/CFM.01/SOP.01/F.01B.13
 * Sumber Dokumen: POM-06.02_CFM.01_SOP.01_F.01B.13_Cemaran Mikroba Kualitatif Sediaan Farmasi_.xlsx
 * Sesuai Dokumen Spesifikasi: docs/SPEC_LCP_CEMARAN_MIKROBA_KUALITATIF.md
 * 
 * Prinsip:
 * 1. Tanpa nilai hasil default palsu (nilai default penimbangan/pelarut disimpan sebagai placeholder/hint bukan hasil paksa)
 * 2. Seluruh checkbox default false / unchecked
 * 3. Tidak menggunakan eval()
 * 4. Kompatibel dengan renderer PHP array CodeIgniter 3
 */

function dapatkan_skema_lcp_cemaran_mikroba_kualitatif()
{
    return array(
        'kode_dokumen' => 'POM-06.02/CFM.01/SOP.01/F.01B.13',
        'judul'        => 'Cemaran Mikroba Kualitatif Sediaan Farmasi',
        'kategori'     => 'Mikrobiologi',
        'sections'     => array(
            // Bagian 1: Identitas Dokumen & Target Mikroba Uji (Baris 5-7)
            array(
                'id'          => 'target_mikroba',
                'title'       => 'Target Cemaran Mikroba yang Diuji',
                'description' => 'Pilih satu atau lebih target cemaran mikroba sesuai acuan pengujian sampel.',
                'fields'      => array(
                    array(
                        'id'          => 'target_staphylococcus',
                        'label'       => 'Staphylococcus aures',
                        'type'        => 'checkbox',
                        'default'     => false,
                        'keterangan'  => 'Ejaan dokumen resmi: Staphylococcus aures'
                    ),
                    array(
                        'id'          => 'target_pseudomonas',
                        'label'       => 'Pseudomonas aeruginosa',
                        'type'        => 'checkbox',
                        'default'     => false
                    ),
                    array(
                        'id'          => 'target_candida',
                        'label'       => 'Candida albicans',
                        'type'        => 'checkbox',
                        'default'     => false
                    ),
                    array(
                        'id'          => 'target_shigella',
                        'label'       => 'Shigella sp',
                        'type'        => 'checkbox',
                        'default'     => false
                    ),
                    array(
                        'id'          => 'target_salmonella',
                        'label'       => 'Salmonella sp',
                        'type'        => 'checkbox',
                        'default'     => false
                    ),
                    array(
                        'id'          => 'target_ecoli',
                        'label'       => 'E.coli',
                        'type'        => 'checkbox',
                        'default'     => false
                    ),
                    array(
                        'id'          => 'target_lainnya_check',
                        'label'       => 'Lainnya',
                        'type'        => 'checkbox',
                        'default'     => false
                    ),
                    array(
                        'id'          => 'target_lainnya_text',
                        'label'       => 'Mikroba Lainnya',
                        'type'        => 'text',
                        'placeholder' => 'Sebutkan mikroba target lainnya...',
                        'depends_on'  => 'target_lainnya_check'
                    ),
                    array(
                        'id'          => 'lab_lokasi',
                        'label'       => 'Laboratorium Penguji',
                        'type'        => 'static_text',
                        'value'       => 'Laboratorium Mikrobiologi Balai Besar POM di Bandar Lampung'
                    )
                )
            ),

            // Bagian 2: Hasil Uji Kesesuaian Metode (Baris 15-19)
            array(
                'id'          => 'kesesuaian_metode',
                'title'       => 'Hasil Uji Kesesuaian Metode',
                'description' => 'Kontrol kesesuaian metode pelarut dan galur baku pembanding.',
                'fields'      => array(
                    array(
                        'id'          => 'pelarut_kesesuaian',
                        'label'       => 'Pelarut yang Sesuai',
                        'type'        => 'radio',
                        'options'     => array(
                            'MLB',
                            'TSB',
                            'TSB + 30 g/L polysorbate 80',
                            'TSB + 30 g/L polysorbate 80 + 3 g/L Lesitin',
                            'Lainnya'
                        )
                    ),
                    array(
                        'id'          => 'pelarut_kesesuaian_lainnya',
                        'label'       => 'Pelarut Lainnya',
                        'type'        => 'text',
                        'placeholder' => 'Sebutkan pelarut lainnya...',
                        'depends_on'  => 'pelarut_kesesuaian=Lainnya'
                    ),
                    array(
                        'id'          => 'baku_staphylococcus',
                        'label'       => 'Staphylococcus aures ATCC / WDCM',
                        'type'        => 'checkbox',
                        'default'     => false
                    ),
                    array(
                        'id'          => 'baku_staphylococcus_no',
                        'label'       => 'No. ATCC/WDCM S. aureus',
                        'type'        => 'text',
                        'placeholder' => 'Misal: ATCC 6538'
                    ),
                    array(
                        'id'          => 'baku_pseudomonas',
                        'label'       => 'Pseudomonas aeruginosa ATCC / WDCM',
                        'type'        => 'checkbox',
                        'default'     => false
                    ),
                    array(
                        'id'          => 'baku_pseudomonas_no',
                        'label'       => 'No. ATCC/WDCM P. aeruginosa',
                        'type'        => 'text',
                        'placeholder' => 'Misal: ATCC 9027'
                    ),
                    array(
                        'id'          => 'baku_candida',
                        'label'       => 'Candida albicans ATCC / WDCM',
                        'type'        => 'checkbox',
                        'default'     => false
                    ),
                    array(
                        'id'          => 'baku_candida_no',
                        'label'       => 'No. ATCC/WDCM C. albicans',
                        'type'        => 'text',
                        'placeholder' => 'Misal: ATCC 10231'
                    ),
                    array(
                        'id'          => 'baku_lainnya',
                        'label'       => 'Mikroba Baku Lainnya',
                        'type'        => 'text',
                        'placeholder' => 'Galur baku ATCC/WDCM lainnya...'
                    )
                )
            ),

            // Bagian 3: Homogenisasi Awal (Baris 22-27)
            array(
                'id'          => 'homogenisasi_awal',
                'title'       => 'Homogenisasi Awal',
                'description' => 'Pencatatan penimbangan sampel dan volume pelarut suspensi.',
                'fields'      => array(
                    array(
                        'id'          => 'bobot_sampel',
                        'label'       => 'Penimbangan/Pemipetan Sampel',
                        'type'        => 'number',
                        'placeholder' => '10.0 (acuan SOP)',
                        'unit'        => 'g atau mL',
                        'required'    => true
                    ),
                    array(
                        'id'          => 'volume_pelarut',
                        'label'       => 'Pelarut',
                        'type'        => 'number',
                        'placeholder' => '90.0 (acuan SOP)',
                        'unit'        => 'mL',
                        'required'    => true
                    ),
                    array(
                        'id'          => 'jenis_pelarut_homogenisasi',
                        'label'       => 'Pilihan Pelarut Homogenisasi',
                        'type'        => 'radio',
                        'options'     => array(
                            'MLB',
                            'TSB',
                            'TSB + 30 g/L polysorbate 80',
                            'TSB + 30 g/L polysorbate 80 + 3 g/L Lesitin',
                            'Lainnya'
                        )
                    ),
                    array(
                        'id'          => 'pelarut_homogenisasi_lainnya',
                        'label'       => 'Pelarut Homogenisasi Lainnya',
                        'type'        => 'text',
                        'placeholder' => 'Sebutkan jenis pelarut lainnya...',
                        'depends_on'  => 'jenis_pelarut_homogenisasi=Lainnya'
                    )
                )
            ),

            // Bagian 4: Matriks Tahapan Pengujian (Baris 31-65)
            array(
                'id'          => 'tahapan_pengujian',
                'title'       => 'Matriks Tahapan Pengujian',
                'description' => 'Alur bertahap dari pra-pengayaan, pengayaan, seleksi, isolasi, hingga konfirmasi.',
                'fields'      => array(
                    array(
                        'id'          => 'inokulum_tingkat',
                        'label'       => 'Tingkat Inokulum',
                        'type'        => 'radio',
                        'options'     => array(
                            '1 g atau mL',
                            '0,1 g atau mL',
                            'Lainnya'
                        )
                    ),
                    array(
                        'id'          => 'tabel_tahapan',
                        'label'       => 'Tabel Pengamatan Tahap Uji',
                        'type'        => 'table',
                        'columns'     => array(
                            array('id' => 'tahap', 'label' => 'Tahapan Uji', 'type' => 'text', 'readonly' => true),
                            array('id' => 'tanggal', 'label' => 'Tanggal Uji', 'type' => 'date'),
                            array('id' => 'media', 'label' => 'Media', 'type' => 'text'),
                            array('id' => 'suhu', 'label' => 'Suhu (°C)', 'type' => 'text'),
                            array('id' => 'waktu', 'label' => 'Waktu (Jam)', 'type' => 'text'),
                            array('id' => 'sampel_spike', 'label' => 'Sampel + Spike', 'type' => 'text'),
                            array('id' => 'media_spike', 'label' => 'Media + Spike (Kontrol Positif)', 'type' => 'text'),
                            array('id' => 'kontrol_media', 'label' => 'Kontrol Media (Sterilitas)', 'type' => 'text'),
                            array('id' => 'sampel_uji', 'label' => 'Sampel Uji', 'type' => 'text')
                        ),
                        'default_rows' => array(
                            array('tahap' => 'Pra Pengayaan'),
                            array('tahap' => 'Pengayaan / Pengayaan Selektif'),
                            array('tahap' => 'Seleksi'),
                            array('tahap' => 'Sub Kultur / Isolasi'),
                            array('tahap' => 'Konfirmasi')
                        )
                    )
                )
            ),

            // Bagian 5: Pedoman Kriteria Koloni Spesifik (Referensi Visual Read-Only, Baris 75-82)
            array(
                'id'          => 'pedoman_koloni',
                'title'       => 'Pedoman Kriteria Koloni Spesifik (Referensi Visual)',
                'description' => 'Acuan visual morfologi koloni pada media isolasi agar.',
                'type'        => 'reference_guide',
                'items'       => array(
                    array(
                        'mikroba'  => 'Staphylococcus aureus',
                        'kriteria' => array(
                            'BPA' => 'Koloni cembung, warna hitam mengkilat dikelilingi zona bening.',
                            'VJA' => 'Koloni cembung, warna hitam mengkilat.',
                            'MSA' => 'Koloni cembung, warna kuning.'
                        )
                    ),
                    array(
                        'mikroba'  => 'Pseudomonas aeruginosa',
                        'kriteria' => array(
                            'CETA' => 'Koloni berwarna kuning kehijauan dengan fluoresensi di bawah lampu UV.'
                        )
                    ),
                    array(
                        'mikroba'  => 'Candida albicans',
                        'kriteria' => array(
                            'SDA' => 'Koloni berwarna putih sampai krem, permukaan cembung dan licin.'
                        )
                    ),
                    array(
                        'mikroba'  => 'Salmonella spp',
                        'kriteria' => array(
                            'BGA' => 'Koloni kecil, tidak berwarna / merah muda translusen dgn lingkaran merah.',
                            'XLD' => 'Koloni berwarna merah dengan atau tanpa bintik hitam di tengah.'
                        )
                    ),
                    array(
                        'mikroba'  => 'Shigella spp',
                        'kriteria' => array(
                            'XLD' => 'Koloni bulat putih-merah muda, media berubah menjadi merah.',
                            'MCA' => 'Koloni bergerigi warna putih oranye, media berubah menjadi oranye.'
                        )
                    ),
                    array(
                        'mikroba'  => 'Escherichia coli',
                        'kriteria' => array(
                            'MCA' => 'Koloni merah muda hingga merah dikelilingi zona presipitasi merah gelap.'
                        )
                    )
                )
            ),

            // Bagian 6: Hasil Uji, Spesifikasi, & Kesimpulan (Baris 84-89)
            array(
                'id'          => 'hasil_dan_kesimpulan',
                'title'       => 'Hasil Uji, Spesifikasi, & Kesimpulan',
                'description' => 'Ringkasan eksekutif pengujian untuk integrasi CP-SIPT dan LHU.',
                'fields'      => array(
                    array(
                        'id'          => 'hasil_uji',
                        'label'       => 'Hasil Uji',
                        'type'        => 'textarea',
                        'placeholder' => 'Misal: Negatif per 10 g / Tidak ditemukan Salmonella sp',
                        'required'    => true
                    ),
                    array(
                        'id'          => 'pustaka_metode',
                        'label'       => 'Pustaka/Metode',
                        'type'        => 'text',
                        'placeholder' => 'Misal: Farmakope Indonesia Edisi VI (2020)',
                        'required'    => true
                    ),
                    array(
                        'id'          => 'syarat',
                        'label'       => 'Syarat',
                        'type'        => 'text',
                        'placeholder' => 'Misal: Negatif / Harus bebas dari E. coli',
                        'required'    => true
                    ),
                    array(
                        'id'          => 'kesimpulan_lcp',
                        'label'       => 'Kesimpulan LCP',
                        'type'        => 'select',
                        'options'     => array(
                            'Memenuhi Syarat (MS)',
                            'Tidak Memenuhi Syarat (TMS)',
                            'Hasil Pengujian Seperti Tersebut (HPST)'
                        ),
                        'required'    => true
                    )
                )
            ),

            // Bagian 7: Pengesahan & Tanda Tangan (Metadata Verifikasi Mutu, Baris 91-95)
            array(
                'id'          => 'metadata_pengesahan',
                'title'       => 'Pengesahan & Identitas Dokumen',
                'description' => 'Metadata tanda tangan berjenjang sesuai alur mutu laboratorium.',
                'fields'      => array(
                    array(
                        'id'          => 'nomor_formulir',
                        'label'       => 'Kode Dokumen Formulir',
                        'type'        => 'static_text',
                        'value'       => 'POM-06.02/CFM.01/SOP.01/F.01B.13'
                    ),
                    array(
                        'id'          => 'penguji_nama',
                        'label'       => 'Tanda Tangan Penguji',
                        'type'        => 'display_bound',
                        'source'      => 'users.nama_lengkap'
                    ),
                    array(
                        'id'          => 'penguji_tanggal',
                        'label'       => 'Tanggal Selesai Pengujian',
                        'type'        => 'display_bound',
                        'source'      => 'testing_sessions.waktu_selesai'
                    ),
                    array(
                        'id'          => 'penyelia_nama',
                        'label'       => 'Diperiksa oleh (Penyelia)',
                        'type'        => 'display_bound',
                        'source'      => 'users.nama_lengkap'
                    ),
                    array(
                        'id'          => 'penyelia_tanggal',
                        'label'       => 'Tanggal Verifikasi Penyelia',
                        'type'        => 'display_bound',
                        'source'      => 'testing_sessions.waktu_verifikasi'
                    )
                )
            )
        )
    );
}
