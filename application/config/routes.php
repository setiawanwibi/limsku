<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING LIMSKU
| -------------------------------------------------------------------------
*/
$route['default_controller'] = 'autentikasi';
$route['404_override']        = '';
$route['translate_uri_dashes'] = FALSE;

// Autentikasi
$route['masuk']  = 'autentikasi/masuk';
$route['keluar'] = 'autentikasi/keluar';

// Portal Pemilih Modul Laboratorium
$route['portal']                  = 'portal/index';
$route['portal/pilih/(:any)']     = 'portal/pilih/$1';

// Dasbor
$route['dasbor']                = 'dasbor/index';
$route['dasbor/api_ringkasan']  = 'dasbor/api_ringkasan';

// Pengguna (User Management)
$route['pengguna']                  = 'pengguna/index';
$route['pengguna/tambah']           = 'pengguna/tambah';
$route['pengguna/edit/(:num)']      = 'pengguna/edit/$1';
$route['pengguna/status/(:num)']    = 'pengguna/ubah_status/$1';
$route['pengguna/password/(:num)']  = 'pengguna/ubah_password/$1';
$route['pengguna/hapus/(:num)']     = 'pengguna/hapus/$1';

// Profil & Akun User Logged-In
$route['akun']                      = 'akun/index';
$route['akun/upload_ttd']           = 'akun/upload_ttd';
$route['akun/hapus_ttd']            = 'akun/hapus_ttd';

// Peran (Role Management & Permission)
$route['peran']                     = 'peran/index';
$route['peran/tambah']              = 'peran/tambah';
$route['peran/edit/(:num)']         = 'peran/edit/$1';
$route['peran/hak-akses/(:num)']    = 'peran/hak_akses/$1';
$route['peran/permission/tambah']   = 'peran/tambah_permission';
$route['peran/hapus/(:num)']        = 'peran/hapus/$1';

// Laboratorium (Laboratory Management)
$route['laboratorium']              = 'laboratorium/index';
$route['laboratorium/tambah']       = 'laboratorium/tambah';
$route['laboratorium/edit/(:num)']  = 'laboratorium/edit/$1';
$route['laboratorium/status/(:num)'] = 'laboratorium/ubah_status/$1';
$route['laboratorium/hapus/(:num)'] = 'laboratorium/hapus/$1';

// Sampel (Sample Management & Import Excel)
$route['sampel']                    = 'sampel/index';
$route['sampel/tambah']             = 'sampel/tambah';
$route['sampel/import']             = 'sampel/import';
$route['sampel/detail/(:num)']      = 'sampel/detail/$1';
$route['sampel/edit/(:num)']        = 'sampel/edit/$1';
$route['sampel/hapus/(:num)']       = 'sampel/hapus/$1';

// Master Alat Laboratorium (Phase 3 & Inventory)
$route['alat']                      = 'alat/index';
$route['alat/tambah']               = 'alat/tambah';
$route['alat/import']               = 'alat/import';
$route['alat/konfirmasi_import']    = 'alat/konfirmasi_import';
$route['alat/detail/(:num)']        = 'alat/detail/$1';
$route['alat/edit/(:num)']          = 'alat/edit/$1';
$route['alat/status/(:num)']        = 'alat/status/$1';
// Master Reagen (Phase 3 & Inventory)
$route['reagen']                    = 'reagen/index';
$route['reagen/tambah']             = 'reagen/tambah';
$route['reagen/detail/(:num)']      = 'reagen/detail/$1';
$route['reagen/edit/(:num)']        = 'reagen/edit/$1';
$route['reagen/status/(:num)']      = 'reagen/status/$1';
$route['reagen/hapus/(:num)']       = 'reagen/hapus/$1';

// Master Kelas Terapi (Admin TI)
$route['kelas-terapi']              = 'kelas_terapi/index';
$route['kelas-terapi/tambah']       = 'kelas_terapi/tambah';
$route['kelas-terapi/edit/(:num)']  = 'kelas_terapi/edit/$1';
$route['kelas-terapi/status/(:num)']= 'kelas_terapi/status/$1';
$route['kelas-terapi/hapus/(:num)'] = 'kelas_terapi/hapus/$1';

// Master Metode (Phase 3 & Monografi)
$route['metode']                    = 'metode/index';
$route['metode/tambah']             = 'metode/tambah';
$route['metode/import']             = 'metode/import';
$route['metode/konfirmasi_import']  = 'metode/konfirmasi_import';
$route['metode/mapping/(:num)']     = 'metode/mapping/$1';
$route['metode/edit/(:num)']        = 'metode/edit/$1';
$route['metode/status/(:num)']      = 'metode/status/$1';
$route['metode/hapus/(:num)']       = 'metode/hapus/$1';

// Master Parameter (Phase 3)
$route['parameter']                 = 'parameter/index';
$route['parameter/tambah']          = 'parameter/tambah';
$route['parameter/edit/(:num)']     = 'parameter/edit/$1';
$route['parameter/status/(:num)']   = 'parameter/status/$1';
$route['parameter/hapus/(:num)']    = 'parameter/hapus/$1';

// Master Template Form (Phase 3)
$route['template']                  = 'template/index';
$route['template/tambah']           = 'template/tambah';
// $route['template/preset']           = 'template/inisialisasi_preset'; // Dinonaktifkan: Preset prototype 17 form sudah direset
$route['template/edit/(:num)']      = 'template/edit/$1';
$route['template/status/(:num)']    = 'template/status/$1';
$route['template/hapus/(:num)']     = 'template/hapus/$1';

// Pengujian Laboratorium (Phase 3 & Phase 4)
$route['pengujian']                         = 'pengujian/antrean';
$route['pengujian/antrean']                 = 'pengujian/antrean';
$route['pengujian/klaim/(:num)']            = 'pengujian/klaim/$1';
$route['pengujian/pilih_jenis/(:num)']      = 'pengujian/pilih_jenis/$1';
$route['pengujian/pilih_form/(:num)']       = 'pengujian/pilih_form/$1';
$route['pengujian/mulai_sesi/(:num)']       = 'pengujian/mulai_sesi/$1';
$route['pengujian/proses_sesi/(:num)']      = 'pengujian/proses_sesi/$1';
$route['pengujian/revisi_sesi/(:num)']      = 'pengujian/revisi_sesi/$1';
$route['pengujian/detail_sesi/(:num)']      = 'pengujian/detail_sesi/$1';
$route['pengujian/proses/(:num)']           = 'pengujian/proses/$1';
$route['pengujian/riwayat']                 = 'pengujian/riwayat';
$route['pengujian/detail/(:num)']           = 'pengujian/detail/$1';
$route['pengujian/pdf/(:num)']              = 'pengujian/download_pdf/$1';
$route['pengujian/pdf_sesi/(:num)']         = 'pengujian/download_pdf_sesi/$1';
$route['pengujian/cp_mikro/(:num)']         = 'pengujian/cp_mikro/$1';
$route['pengujian/cp_mikro/(:num)/(:any)']  = 'pengujian/cp_mikro/$1/$2';
$route['pengujian/cp_mikro_pdf/(:num)']     = 'pengujian/cp_mikro_pdf/$1';
$route['pengujian/cp_mikro_pdf/(:num)/(:any)'] = 'pengujian/cp_mikro_pdf/$1/$2';
$route['pengujian/cp_kimia/(:num)']         = 'pengujian/cp_kimia/$1';
$route['pengujian/cp_kimia/(:num)/(:any)']  = 'pengujian/cp_kimia/$1/$2';
$route['pengujian/cp_kimia_pdf/(:num)']     = 'pengujian/cp_kimia_pdf/$1';
$route['pengujian/cp_kimia_pdf/(:num)/(:any)'] = 'pengujian/cp_kimia_pdf/$1/$2';
$route['pengujian/lcp_pdf/(:num)']          = 'pengujian/lcp_pdf/$1';
$route['pengujian/lcp_pdf/(:num)/(:any)']   = 'pengujian/lcp_pdf/$1/$2';
$route['pengujian/verifikasi']               = 'pengujian/verifikasi';
$route['pengujian/verifikasi_sesi/(:num)']   = 'pengujian/verifikasi_sesi/$1';
$route['pengujian/tolak_sesi/(:num)']        = 'pengujian/tolak_sesi/$1';
$route['pengujian/approve_sesi/(:num)']      = 'pengujian/approve_sesi/$1';
$route['pengujian/verifikasi/setujui/(:num)'] = 'pengujian/proses_verifikasi/$1';
$route['pengujian/verifikasi/tolak/(:num)']   = 'pengujian/proses_penolakan/$1';
$route['pengujian/revisi/(:num)']             = 'pengujian/revisi/$1';
$route['pengujian/approval']                  = 'pengujian/approval';
$route['pengujian/tinjauan_lhu/(:num)']        = 'pengujian/tinjauan_lhu/$1';
$route['pengujian/approve_sampel/(:num)']     = 'pengujian/proses_approval_sampel/$1';
$route['pengujian/tolak_sampel/(:num)']       = 'pengujian/proses_penolakan_sampel/$1';
$route['pengujian/proses_approval/(:num)']    = 'pengujian/proses_approval/$1';
$route['pengujian/lhu/(:num)']               = 'pengujian/lhu/$1';
$route['pengujian/cetak_lhu/(:num)']         = 'pengujian/lhu/$1';
$route['pengujian/pdf_sampel/(:num)']         = 'pengujian/download_pdf_sampel/$1';
$route['pengujian/pdf_sampel/(:num)/(:any)']  = 'pengujian/download_pdf_sampel/$1/$2';

// Audit Trail
$route['audit']                     = 'audit/index';
