<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
// Evaluasi permission user yang sedang login dari session role_id
$role_id = $this->session->userdata('role_id');

// Permissions
$bisa_sampel             = $this->Model_Hak_Akses->memiliki_akses($role_id, 'sampel_view');
$bisa_pengujian_antrean  = $this->Model_Hak_Akses->memiliki_akses($role_id, 'pengujian_view');
$bisa_verifikasi         = $this->Model_Hak_Akses->memiliki_akses($role_id, 'pengujian_verify');
$bisa_approval           = $this->Model_Hak_Akses->memiliki_akses($role_id, 'pengujian_approve');
$bisa_laporan            = $this->Model_Hak_Akses->memiliki_akses($role_id, 'laporan_view');

$bisa_metode             = $this->Model_Hak_Akses->memiliki_akses($role_id, 'metode_view');
$bisa_parameter          = $this->Model_Hak_Akses->memiliki_akses($role_id, 'parameter_view');
$bisa_template           = $this->Model_Hak_Akses->memiliki_akses($role_id, 'template_view');
$bisa_pengguna           = $this->Model_Hak_Akses->memiliki_akses($role_id, 'pengguna_view');
$bisa_peran              = $this->Model_Hak_Akses->memiliki_akses($role_id, 'peran_view');
$bisa_laboratorium       = $this->Model_Hak_Akses->memiliki_akses($role_id, 'laboratorium_view');

$bisa_audit              = $this->Model_Hak_Akses->memiliki_akses($role_id, 'audit_view');

// Parent Header Visibility Flags
$ada_menu_registrasi = $bisa_sampel;
$ada_menu_pengujian  = ($bisa_pengujian_antrean || $bisa_verifikasi || $bisa_approval || $bisa_laporan);
$ada_menu_master     = ($bisa_metode || $bisa_parameter || $bisa_template || $bisa_pengguna || $bisa_peran || $bisa_laboratorium);
$ada_menu_sistem     = $bisa_audit;
?>
<nav id="lims-sidebar">
    <div class="sidebar-header p-3 border-bottom">
        <h5 class="fw-bold mb-0">LIMSKU</h5>
        <small>BBPOM Bandar Lampung</small>
    </div>

    <ul class="components list-unstyled px-2 py-3">
        <!-- Dasbor: Tampil untuk semua authenticated user -->
        <li class="mb-1 <?php echo ($halaman_aktif === 'dasbor') ? 'active' : ''; ?>">
            <a href="<?php echo site_url('dasbor'); ?>" class="nav-link px-3 py-2 rounded">
                <span>Dasbor Utama</span>
            </a>
        </li>

        <!-- Registrasi & Sampel -->
        <?php if ($ada_menu_registrasi): ?>
            <li class="nav-header px-3 pt-3 pb-1 text-uppercase text-muted fw-bold" style="font-size: 0.75rem;">
                Registrasi &amp; Sampel
            </li>

            <?php if ($bisa_sampel): ?>
                <li class="mb-1 <?php echo ($halaman_aktif === 'sampel') ? 'active' : ''; ?>">
                    <a href="<?php echo site_url('sampel'); ?>" class="nav-link px-3 py-2 rounded">
                        <span>Data Sampel</span>
                    </a>
                </li>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Pengujian Sampel & Laporan -->
        <?php if ($ada_menu_pengujian): ?>
            <li class="nav-header px-3 pt-3 pb-1 text-uppercase text-muted fw-bold" style="font-size: 0.75rem;">
                Pengujian Sampel
            </li>

            <?php if ($bisa_pengujian_antrean): ?>
                <li class="mb-1 <?php echo ($halaman_aktif === 'pengujian_antrean') ? 'active' : ''; ?>">
                    <a href="<?php echo site_url('pengujian/antrean'); ?>" class="nav-link px-3 py-2 rounded">
                        <span>Antrean Pengujian</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if ($bisa_verifikasi): ?>
                <li class="mb-1 <?php echo ($halaman_aktif === 'pengujian_verifikasi') ? 'active' : ''; ?>">
                    <a href="<?php echo site_url('pengujian/verifikasi'); ?>" class="nav-link px-3 py-2 rounded">
                        <span>Verifikasi Laporan</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if ($bisa_approval): ?>
                <li class="mb-1 <?php echo ($halaman_aktif === 'pengujian_approval') ? 'active' : ''; ?>">
                    <a href="<?php echo site_url('pengujian/approval'); ?>" class="nav-link px-3 py-2 rounded">
                        <span>Approval Laporan</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if ($bisa_laporan): ?>
                <li class="mb-1 <?php echo ($halaman_aktif === 'pengujian_riwayat') ? 'active' : ''; ?>">
                    <a href="<?php echo site_url('pengujian/riwayat'); ?>" class="nav-link px-3 py-2 rounded">
                        <span>Riwayat Hasil Uji / Laporan</span>
                    </a>
                </li>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Master Data -->
        <?php if ($ada_menu_master): ?>
            <li class="nav-header px-3 pt-3 pb-1 text-uppercase text-muted fw-bold" style="font-size: 0.75rem;">
                Master Data
            </li>

            <?php if ($bisa_metode): ?>
                <li class="mb-1 <?php echo ($halaman_aktif === 'metode') ? 'active' : ''; ?>">
                    <a href="<?php echo site_url('metode'); ?>" class="nav-link px-3 py-2 rounded">
                        <span>Master Metode</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if ($bisa_parameter): ?>
                <li class="mb-1 <?php echo ($halaman_aktif === 'parameter') ? 'active' : ''; ?>">
                    <a href="<?php echo site_url('parameter'); ?>" class="nav-link px-3 py-2 rounded">
                        <span>Master Parameter</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if ($bisa_template): ?>
                <li class="mb-1 <?php echo ($halaman_aktif === 'template') ? 'active' : ''; ?>">
                    <a href="<?php echo site_url('template'); ?>" class="nav-link px-3 py-2 rounded">
                        <span>Form Template</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if ($bisa_pengguna): ?>
                <li class="mb-1 <?php echo ($halaman_aktif === 'pengguna') ? 'active' : ''; ?>">
                    <a href="<?php echo site_url('pengguna'); ?>" class="nav-link px-3 py-2 rounded">
                        <span>Manajemen Pengguna</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if ($bisa_peran): ?>
                <li class="mb-1 <?php echo ($halaman_aktif === 'peran') ? 'active' : ''; ?>">
                    <a href="<?php echo site_url('peran'); ?>" class="nav-link px-3 py-2 rounded">
                        <span>Peran &amp; Hak Akses</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if ($bisa_laboratorium): ?>
                <li class="mb-1 <?php echo ($halaman_aktif === 'laboratorium') ? 'active' : ''; ?>">
                    <a href="<?php echo site_url('laboratorium'); ?>" class="nav-link px-3 py-2 rounded">
                        <span>Laboratorium</span>
                    </a>
                </li>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Sistem & Keamanan -->
        <?php if ($ada_menu_sistem): ?>
            <li class="nav-header px-3 pt-3 pb-1 text-uppercase text-muted fw-bold" style="font-size: 0.75rem;">
                Sistem &amp; Keamanan
            </li>

            <?php if ($bisa_audit): ?>
                <li class="mb-1 <?php echo ($halaman_aktif === 'audit') ? 'active' : ''; ?>">
                    <a href="<?php echo site_url('audit'); ?>" class="nav-link px-3 py-2 rounded">
                        <span>Audit Trail</span>
                    </a>
                </li>
            <?php endif; ?>
        <?php endif; ?>
    </ul>
</nav>
