<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Flash Messages -->
<?php if ($this->session->flashdata('pesan_sukses')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo html_escape($this->session->flashdata('pesan_sukses')); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if ($this->session->flashdata('pesan_gagal')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo html_escape($this->session->flashdata('pesan_gagal')); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <h4 class="fw-bold mb-2">Selamat Datang di LIMSKU</h4>
        <p class="text-muted mb-0">
            Sistem Informasi Manajemen Laboratorium Balai Besar POM di Bandar Lampung. Anda masuk sebagai <strong><?php echo html_escape($this->session->userdata('nama_lengkap')); ?></strong> (<?php echo html_escape($this->session->userdata('role_name')); ?>).
        </p>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h6 class="text-muted fw-semibold">Manajemen Pengguna</h6>
                <p class="small text-muted">Kelola data pengguna, status akun, dan reset kata sandi.</p>
                <a href="<?php echo site_url('pengguna'); ?>" class="btn btn-sm btn-outline-primary fw-semibold">Buka Pengguna &rarr;</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h6 class="text-muted fw-semibold">Peran &amp; Hak Akses</h6>
                <p class="small text-muted">Atur peran pengguna dan permission di tingkat server.</p>
                <a href="<?php echo site_url('peran'); ?>" class="btn btn-sm btn-outline-primary fw-semibold">Buka Peran &rarr;</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h6 class="text-muted fw-semibold">Laboratorium</h6>
                <p class="small text-muted">Kelola unit laboratorium pelaksana pengujian.</p>
                <a href="<?php echo site_url('laboratorium'); ?>" class="btn btn-sm btn-outline-primary fw-semibold">Buka Laboratorium &rarr;</a>
            </div>
        </div>
    </div>
</div>
