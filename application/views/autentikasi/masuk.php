<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo isset($judul_halaman) ? html_escape($judul_halaman) . ' - ' : ''; ?><?php echo html_escape($nama_aplikasi); ?> | <?php echo html_escape($instansi); ?></title>

    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>">
    <!-- Custom LIMSKU CSS -->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/limsku.css'); ?>">
</head>
<body class="bg-light">

<div class="lims-login-wrapper d-flex align-items-center justify-content-center min-vh-100 py-5">
    <div class="card shadow-sm border-0 lims-login-card p-4" style="max-width: 420px; width: 100%; border-radius: 12px;">
        <div class="card-body p-2">
            <div class="text-center mb-4">
                <h3 class="fw-bold text-dark mb-1"><?php echo html_escape($nama_aplikasi); ?></h3>
                <p class="text-muted small mb-0"><?php echo html_escape($judul_panjang); ?></p>
                <span class="badge bg-primary mt-2"><?php echo html_escape($instansi); ?></span>
            </div>

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

            <?php if (validation_errors()): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo validation_errors('<div>', '</div>'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form action="<?php echo site_url('masuk'); ?>" method="post">
                <!-- CSRF Token -->
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

                <div class="mb-3">
                    <label for="nama_pengguna" class="form-label fw-semibold">Nama Pengguna / NIP</label>
                    <input type="text" class="form-control form-control-lg" id="nama_pengguna" name="nama_pengguna" value="<?php echo set_value('nama_pengguna'); ?>" placeholder="Masukkan nama pengguna / NIP" autocomplete="off" required autofocus>
                </div>

                <div class="mb-4">
                    <label for="kata_sandi" class="form-label fw-semibold">Kata Sandi</label>
                    <input type="password" class="form-control form-control-lg" id="kata_sandi" name="kata_sandi" placeholder="Masukkan kata sandi" required>
                </div>

                <div class="d-grid mb-3">
                    <button type="submit" class="btn btn-primary btn-lg fw-semibold">
                        Masuk
                    </button>
                </div>

                <div class="text-center mt-3">
                    <small class="text-muted">LIMSKU — BBPOM Bandar Lampung &copy; <?php echo date('Y'); ?></small>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- jQuery JS -->
<script src="<?php echo base_url('assets/js/jquery.min.js'); ?>"></script>
<!-- Bootstrap 5 Bundle JS -->
<script src="<?php echo base_url('assets/js/bootstrap.bundle.min.js'); ?>"></script>

</body>
</html>
