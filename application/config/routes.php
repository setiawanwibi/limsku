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

// Master Metode (Phase 3)
$route['metode']                    = 'metode/index';
$route['metode/tambah']             = 'metode/tambah';
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
$route['template/preset']           = 'template/inisialisasi_preset';
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
$route['pengujian/verifikasi']               = 'pengujian/verifikasi';
$route['pengujian/verifikasi_sesi/(:num)']   = 'pengujian/verifikasi_sesi/$1';
$route['pengujian/tolak_sesi/(:num)']        = 'pengujian/tolak_sesi/$1';
$route['pengujian/approve_sesi/(:num)']      = 'pengujian/approve_sesi/$1';
$route['pengujian/verifikasi/setujui/(:num)'] = 'pengujian/proses_verifikasi/$1';
$route['pengujian/verifikasi/tolak/(:num)']   = 'pengujian/proses_penolakan/$1';
$route['pengujian/revisi/(:num)']             = 'pengujian/revisi/$1';
$route['pengujian/approval']                  = 'pengujian/approval';
$route['pengujian/proses_approval/(:num)']    = 'pengujian/proses_approval/$1';

// Audit Trail
$route['audit']                     = 'audit/index';
