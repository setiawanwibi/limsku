<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="card border-0 shadow-sm" style="max-width: 800px;">
    <div class="card-header bg-white py-3">
        <h5 class="fw-bold mb-0 text-dark">Import Sampel via File Excel (.xlsx / .csv)</h5>
    </div>
    <div class="card-body">
        <!-- Flash Messages -->
        <?php if ($this->session->flashdata('pesan_sukses')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo html_escape($this->session->flashdata('pesan_sukses')); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('pesan_gagal')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Gagal Import:</strong> <?php echo html_escape($this->session->flashdata('pesan_gagal')); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Import Error Details -->
        <?php if ($this->session->flashdata('import_errors')): ?>
            <div class="alert alert-warning border-warning" role="alert">
                <h6 class="fw-bold text-dark mb-2">Detail Error Validasi Baris Data:</h6>
                <ul class="mb-0 ps-3 small">
                    <?php foreach ($this->session->flashdata('import_errors') as $err): ?>
                        <li><?php echo html_escape($err); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="alert alert-info border-info mb-4" role="alert">
            <h6 class="fw-bold mb-1">Ketentuan Upload Template Excel:</h6>
            <ol class="mb-0 ps-3 small">
                <li>File harus berformat <strong>.xlsx</strong> atau <strong>.csv</strong>.</li>
                <li>Baris pertama (Row 1) wajib berisi Header Kolom (31 kolom baku).</li>
                <li>Status sampel otomatis di-set menjadi <strong>Menunggu Pengujian</strong>.</li>
                <li>Dua konteks <em>Penandaan</em> pada Excel dipisahkan secara otomatis (Penandaan jumlah vs Penandaan evaluasi).</li>
            </ol>
        </div>

        <form action="<?php echo site_url('sampel/import'); ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

            <div class="mb-4">
                <label for="file_excel" class="form-label fw-semibold">Pilih File Excel (.xlsx / .csv) <span class="text-danger">*</span></label>
                <input type="file" class="form-control form-control-lg" id="file_excel" name="file_excel" accept=".xlsx,.csv" required>
            </div>

            <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                <a href="<?php echo site_url('sampel'); ?>" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-success fw-semibold">
                    &uarr; Unggah &amp; Proses Import
                </button>
            </div>
        </form>
    </div>
</div>
