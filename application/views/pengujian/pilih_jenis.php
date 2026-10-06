<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Flash Messages -->
<?php if ($this->session->flashdata('pesan_gagal')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo html_escape($this->session->flashdata('pesan_gagal')); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card border-0 shadow-sm mx-auto" style="max-width: 650px;">
    <div class="card-header bg-white py-3 border-bottom">
        <h5 class="fw-bold mb-0 text-dark">Mulai Sesi Pengujian Sampel</h5>
        <small class="text-muted">Sampel: <strong><?php echo html_escape($sampel['nama_sampel']); ?></strong> (Kode: <?php echo html_escape($sampel['kode_sampel_manual'] ?: 'SMP-' . $sampel['id']); ?>)</small>
    </div>
    <div class="card-body p-4">
        <form action="<?php echo site_url('pengujian/pilih_jenis/' . $sampel['id']); ?>" method="post">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

            <div class="mb-4">
                <label class="form-label fw-bold text-dark fs-6 mb-3">Pilih Jenis Pengujian <span class="text-danger">*</span></label>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-check border rounded p-3 text-center h-100 position-relative shadow-sm bg-light">
                            <input class="form-check-input position-absolute top-0 end-0 m-3" type="radio" name="jenis_pengujian" id="jenis_kimia" value="Kimia" required>
                            <label class="form-check-label w-100 cursor-pointer pt-2" for="jenis_kimia">
                                <div class="fs-1 text-primary mb-2">🧪</div>
                                <strong class="d-block text-dark fs-6">Uji Kimia</strong>
                                <small class="text-muted d-block mt-1">Pengujian Parameter Fisika &amp; Kimia Obat / Sediaan</small>
                            </label>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-check border rounded p-3 text-center h-100 position-relative shadow-sm bg-light">
                            <input class="form-check-input position-absolute top-0 end-0 m-3" type="radio" name="jenis_pengujian" id="jenis_mikro" value="Mikrobiologi" required>
                            <label class="form-check-label w-100 cursor-pointer pt-2" for="jenis_mikro">
                                <div class="fs-1 text-success mb-2">🧫</div>
                                <strong class="d-block text-dark fs-6">Uji Mikrobiologi</strong>
                                <small class="text-muted d-block mt-1">Pengujian Sterilitas, ALT, AKK &amp; Patogen</small>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="alert alert-info border-info small mb-4" role="alert">
                <strong>Ketentuan Pengujian:</strong> Satu sesi pengujian hanya boleh memilih 1 jenis pengujian (Kimia <u>atau</u> Mikrobiologi). Pengujian Kimia dan Mikrobiologi tidak diperbolehkan dicampur dalam satu sesi.
            </div>

            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                <a href="<?php echo site_url('pengujian/antrean'); ?>" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary fw-semibold px-4">
                    Lanjutkan ke Pemilihan Form &rarr;
                </button>
            </div>
        </form>
    </div>
</div>
