<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<nav class="navbar navbar-expand-lg lims-topbar bg-white border-bottom px-2 px-md-3 py-2">
    <div class="container-fluid p-0 d-flex align-items-center justify-content-between flex-nowrap">
        <div class="d-flex align-items-center overflow-hidden me-2">
            <button type="button" id="sidebar-toggle-open" class="sidebar-toggle-btn me-2 me-md-3 flex-shrink-0" title="Buka Sidebar" aria-label="Buka Sidebar">
                <i class="bi bi-list fs-4"></i>
            </button>

            <span class="navbar-text fw-semibold text-dark fs-6 text-truncate topbar-title" title="<?php echo isset($judul_halaman) && !empty($judul_halaman) ? html_escape($judul_halaman) : 'Laboratory Information Management System'; ?>">
                <?php echo isset($judul_halaman) && !empty($judul_halaman) ? html_escape($judul_halaman) : 'Laboratory Information Management System'; ?>
            </span>
        </div>

        <div class="d-flex align-items-center ms-auto flex-shrink-0">
            <?php if ($this->session->userdata('logged_in')): ?>
                <!-- Tombol Portal Cepat (Desktop & Tablet) -->
                <a href="<?php echo site_url('portal'); ?>" class="btn btn-sm btn-outline-primary me-2 fw-semibold d-none d-md-inline-flex align-items-center" title="Beralih Portal Modul Laboratorium">
                    <i class="bi bi-grid-fill me-1"></i> <span>Portal</span>
                </a>

                <!-- User Dropdown Menu (Universal & Mobile Friendly) -->
                <div class="dropdown">
                    <button class="btn btn-sm p-1 d-flex align-items-center border-0 text-decoration-none shadow-none" type="button" id="userMenuDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 35px; height: 35px; font-weight: 700; font-size: 0.85rem;">
                            <?php echo strtoupper(substr($this->session->userdata('nama_lengkap') ?: 'U', 0, 1)); ?>
                        </div>
                        <div class="text-start ms-2 d-none d-lg-block">
                            <span class="d-block fw-bold text-dark small leading-none text-truncate" style="max-width: 140px;">
                                <?php echo html_escape($this->session->userdata('nama_lengkap')); ?>
                            </span>
                            <span class="badge bg-light text-secondary border mt-1" style="font-size: 0.65rem;">
                                <?php echo html_escape($this->session->userdata('role_name')); ?>
                            </span>
                        </div>
                        <i class="bi bi-chevron-down ms-1 text-muted small d-none d-sm-inline"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border py-2 mt-2" aria-labelledby="userMenuDropdown" style="min-width: 210px; border-radius: 10px;">
                        <li class="px-3 py-2 border-bottom d-lg-none">
                            <div class="fw-bold text-dark small text-truncate">
                                <?php echo html_escape($this->session->userdata('nama_lengkap')); ?>
                            </div>
                            <span class="badge bg-light text-secondary border mt-1" style="font-size: 0.65rem;">
                                <?php echo html_escape($this->session->userdata('role_name')); ?>
                            </span>
                        </li>
                        <li>
                            <a class="dropdown-item py-2 d-flex align-items-center gap-2 small text-dark d-md-none" href="<?php echo site_url('portal'); ?>">
                                <i class="bi bi-grid-fill text-primary"></i> Beralih Portal Lab
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-2 d-flex align-items-center gap-2 small text-dark" href="<?php echo site_url('akun'); ?>">
                                <i class="bi bi-person-gear text-secondary"></i> Profil &amp; TTD Digital
                            </a>
                        </li>
                        <li><hr class="dropdown-divider my-1"></li>
                        <li>
                            <a class="dropdown-item py-2 d-flex align-items-center gap-2 small text-danger fw-semibold" href="<?php echo site_url('keluar'); ?>" onclick="return confirm('Apakah Anda yakin ingin keluar dari sistem?');">
                                <i class="bi bi-box-arrow-right text-danger"></i> Keluar
                            </a>
                        </li>
                    </ul>
                </div>
            <?php else: ?>
                <a href="<?php echo site_url('masuk'); ?>" class="btn btn-sm btn-primary fw-semibold">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
                </a>
            <?php endif; ?>
        </div>
    </div>
</nav>
