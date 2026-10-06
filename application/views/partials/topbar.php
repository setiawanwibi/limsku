<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<nav class="navbar navbar-expand-lg lims-topbar bg-white border-bottom px-3 py-2">
    <div class="container-fluid p-0">
        <span class="navbar-text fw-semibold text-dark fs-6">
            <?php echo isset($judul_halaman) && !empty($judul_halaman) ? html_escape($judul_halaman) : 'Laboratory Information Management System'; ?>
        </span>

        <div class="d-flex align-items-center ms-auto">
            <?php if ($this->session->userdata('logged_in')): ?>
                <div class="me-3 text-end">
                    <span class="d-block fw-bold text-dark small">
                        <?php echo html_escape($this->session->userdata('nama_lengkap')); ?>
                    </span>
                    <span class="badge bg-primary" style="font-size: 0.7rem;">
                        <?php echo html_escape($this->session->userdata('role_name')); ?>
                    </span>
                </div>
                <a href="<?php echo site_url('keluar'); ?>" class="btn btn-sm btn-outline-danger fw-semibold" onclick="return confirm('Apakah Anda yakin ingin keluar dari sistem?');">
                    Keluar
                </a>
            <?php else: ?>
                <a href="<?php echo site_url('masuk'); ?>" class="btn btn-sm btn-primary fw-semibold">
                    Masuk
                </a>
            <?php endif; ?>
        </div>
    </div>
</nav>
