<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<nav id="lims-sidebar">
    <div class="sidebar-header p-3 border-bottom">
        <h5 class="fw-bold mb-0">LIMSKU</h5>
        <small>BBPOM Bandar Lampung</small>
    </div>

    <ul class="components list-unstyled px-2 py-3">
        <li class="mb-1 <?php echo ($halaman_aktif === 'dasbor') ? 'active' : ''; ?>">
            <a href="<?php echo site_url('dasbor'); ?>" class="nav-link px-3 py-2 rounded">
                <span>Dasbor Utama</span>
            </a>
        </li>

        <li class="nav-header px-3 pt-3 pb-1 text-uppercase text-muted fw-bold" style="font-size: 0.75rem;">
            Registrasi &amp; Sampel
        </li>

        <li class="mb-1 <?php echo ($halaman_aktif === 'sampel') ? 'active' : ''; ?>">
            <a href="<?php echo site_url('sampel'); ?>" class="nav-link px-3 py-2 rounded">
                <span>Data Sampel</span>
            </a>
        </li>

        <li class="nav-header px-3 pt-3 pb-1 text-uppercase text-muted fw-bold" style="font-size: 0.75rem;">
            Pengujian Sampel
        </li>

        <li class="mb-1 <?php echo ($halaman_aktif === 'pengujian_antrean') ? 'active' : ''; ?>">
            <a href="<?php echo site_url('pengujian/antrean'); ?>" class="nav-link px-3 py-2 rounded">
                <span>Antrean Pengujian</span>
            </a>
        </li>

        <?php if ($this->Model_Hak_Akses->memiliki_akses($this->session->userdata('role_id'), 'pengujian_verify')): ?>
            <li class="mb-1 <?php echo ($halaman_aktif === 'pengujian_verifikasi') ? 'active' : ''; ?>">
                <a href="<?php echo site_url('pengujian/verifikasi'); ?>" class="nav-link px-3 py-2 rounded">
                    <span>Verifikasi Laporan</span>
                </a>
            </li>
        <?php endif; ?>

        <?php if ($this->Model_Hak_Akses->memiliki_akses($this->session->userdata('role_id'), 'pengujian_approve')): ?>
            <li class="mb-1 <?php echo ($halaman_aktif === 'pengujian_approval') ? 'active' : ''; ?>">
                <a href="<?php echo site_url('pengujian/approval'); ?>" class="nav-link px-3 py-2 rounded">
                    <span>Approval Laporan</span>
                </a>
            </li>
        <?php endif; ?>

        <li class="mb-1 <?php echo ($halaman_aktif === 'pengujian_riwayat') ? 'active' : ''; ?>">
            <a href="<?php echo site_url('pengujian/riwayat'); ?>" class="nav-link px-3 py-2 rounded">
                <span>Riwayat Hasil Uji</span>
            </a>
        </li>

        <li class="nav-header px-3 pt-3 pb-1 text-uppercase text-muted fw-bold" style="font-size: 0.75rem;">
            Master Data
        </li>

        <li class="mb-1 <?php echo ($halaman_aktif === 'metode') ? 'active' : ''; ?>">
            <a href="<?php echo site_url('metode'); ?>" class="nav-link px-3 py-2 rounded">
                <span>Master Metode</span>
            </a>
        </li>

        <li class="mb-1 <?php echo ($halaman_aktif === 'parameter') ? 'active' : ''; ?>">
            <a href="<?php echo site_url('parameter'); ?>" class="nav-link px-3 py-2 rounded">
                <span>Master Parameter</span>
            </a>
        </li>

        <li class="mb-1 <?php echo ($halaman_aktif === 'template') ? 'active' : ''; ?>">
            <a href="<?php echo site_url('template'); ?>" class="nav-link px-3 py-2 rounded">
                <span>Form Template</span>
            </a>
        </li>

        <li class="mb-1 <?php echo ($halaman_aktif === 'pengguna') ? 'active' : ''; ?>">
            <a href="<?php echo site_url('pengguna'); ?>" class="nav-link px-3 py-2 rounded">
                <span>Manajemen Pengguna</span>
            </a>
        </li>

        <li class="mb-1 <?php echo ($halaman_aktif === 'peran') ? 'active' : ''; ?>">
            <a href="<?php echo site_url('peran'); ?>" class="nav-link px-3 py-2 rounded">
                <span>Peran &amp; Hak Akses</span>
            </a>
        </li>

        <li class="mb-1 <?php echo ($halaman_aktif === 'laboratorium') ? 'active' : ''; ?>">
            <a href="<?php echo site_url('laboratorium'); ?>" class="nav-link px-3 py-2 rounded">
                <span>Laboratorium</span>
            </a>
        </li>

        <li class="nav-header px-3 pt-3 pb-1 text-uppercase text-muted fw-bold" style="font-size: 0.75rem;">
            Sistem &amp; Keamanan
        </li>

        <li class="mb-1 <?php echo ($halaman_aktif === 'audit') ? 'active' : ''; ?>">
            <a href="<?php echo site_url('audit'); ?>" class="nav-link px-3 py-2 rounded">
                <span>Audit Trail</span>
            </a>
        </li>
    </ul>
</nav>
