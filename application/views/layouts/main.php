<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo isset($judul_halaman) && !empty($judul_halaman) ? html_escape($judul_halaman) . ' - ' : ''; ?><?php echo html_escape($nama_aplikasi); ?> | <?php echo html_escape($instansi); ?></title>

    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>">
    <!-- DataTables Bootstrap 5 CSS -->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/dataTables.bootstrap5.min.css'); ?>">
    <!-- Custom LIMSKU CSS -->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/limsku.css'); ?>">
</head>
<body>

<div class="lims-wrapper">
    <!-- Sidebar -->
    <?php $this->load->view('partials/sidebar'); ?>

    <!-- Page Content -->
    <div id="lims-content">
        <!-- Topbar -->
        <?php $this->load->view('partials/topbar'); ?>

        <!-- Main Body -->
        <main class="lims-main-body">
            <!-- Breadcrumbs -->
            <?php $this->load->view('partials/breadcrumb'); ?>

            <!-- Konten Utama -->
            <?php if (isset($konten_utama)): ?>
                <?php echo $konten_utama; ?>
            <?php endif; ?>
        </main>

        <!-- Footer -->
        <?php $this->load->view('partials/footer'); ?>
    </div>
</div>

<!-- jQuery JS -->
<script src="<?php echo base_url('assets/js/jquery.min.js'); ?>"></script>
<!-- Bootstrap 5 Bundle JS -->
<script src="<?php echo base_url('assets/js/bootstrap.bundle.min.js'); ?>"></script>
<!-- DataTables JS -->
<script src="<?php echo base_url('assets/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/dataTables.bootstrap5.min.js'); ?>"></script>

</body>
</html>
