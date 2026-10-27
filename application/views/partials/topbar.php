<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<nav class="navbar navbar-expand-lg lims-topbar bg-white border-bottom px-3 py-2">
    <div class="container-fluid p-0 d-flex align-items-center">
        <button type="button" id="sidebar-toggle-open" class="sidebar-toggle-btn me-3" title="Buka Sidebar" aria-label="Buka Sidebar">
            <i class="bi bi-list fs-4"></i>
        </button>

        <span class="navbar-text fw-semibold text-dark fs-6">
            <?php echo isset($judul_halaman) && !empty($judul_halaman) ? html_escape($judul_halaman) : 'Laboratory Information Management System'; ?>
        </span>

        <div class="d-flex align-items-center ms-auto">
            <?php if ($this->session->userdata('logged_in')): ?>
                <a href="<?php echo site_url('akun'); ?>" class="d-flex align-items-center me-3 text-decoration-none" title="Kelola Profil & TTD Digital">
                    <div class="bg-light text-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 36px; height: 36px; font-weight: 700;">
                        <?php echo strtoupper(substr($this->session->userdata('nama_lengkap') ?: 'U', 0, 1)); ?>
                    </div>
                    <div class="text-end me-1">
                        <span class="d-block fw-bold text-dark small leading-none">
                            <?php echo html_escape($this->session->userdata('nama_lengkap')); ?>
                        </span>
                        <span class="badge bg-light text-secondary border mt-1" style="font-size: 0.68rem;">
                            <?php echo html_escape($this->session->userdata('role_name')); ?>
                        </span>
                    </div>
                </a>
                <a href="<?php echo site_url('portal'); ?>" class="btn btn-sm btn-outline-primary me-2 fw-semibold" title="Beralih Portal Modul Laboratorium">
                    <i class="bi bi-grid-fill me-1"></i> Portal
                </a>
                <a href="<?php echo site_url('akun'); ?>" class="btn btn-sm btn-outline-secondary me-2 fw-semibold" title="Profil & TTD Digital">
                    <i class="bi bi-person-gear"></i>
                </a>
                <a href="<?php echo site_url('keluar'); ?>" class="btn btn-sm btn-outline-danger fw-semibold" onclick="return confirm('Apakah Anda yakin ingin keluar dari sistem?');">
                    <i class="bi bi-box-arrow-right me-1"></i> Keluar
                </a>
            <?php else: ?>
                <a href="<?php echo site_url('masuk'); ?>" class="btn btn-sm btn-primary fw-semibold">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
                </a>
            <?php endif; ?>
        </div>
    </div>
</nav>
