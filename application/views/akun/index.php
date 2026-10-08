<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row">
    <!-- Card Informasi Pengguna -->
    <div class="col-lg-5 mb-4">
        <div class="card shadow-sm border-0 rounded-3 h-100">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="card-title text-dark fw-bold mb-0">
                    <i class="bi bi-person-badge text-primary me-2"></i>Informasi Profil Saya
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 70px; height: 70px; font-size: 1.8rem; font-weight: 700;">
                        <?php echo strtoupper(substr($user['nama_lengkap'] ?: 'U', 0, 1)); ?>
                    </div>
                    <h5 class="fw-bold mb-1"><?php echo html_escape($user['nama_lengkap']); ?></h5>
                    <span class="badge bg-light text-primary border px-3 py-1 fw-semibold">
                        <?php echo html_escape($user['nama_role']); ?>
                    </span>
                </div>

                <div class="table-responsive">
                    <table class="table table-borderless table-sm mb-0">
                        <tr>
                            <td class="text-muted" style="width: 40%;">Username</td>
                            <td class="fw-semibold">: <?php echo html_escape($user['username']); ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">NIP</td>
                            <td class="fw-semibold">: <?php echo html_escape($user['nip'] ?: '-'); ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Email</td>
                            <td class="fw-semibold">: <?php echo html_escape($user['email'] ?: '-'); ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Laboratorium</td>
                            <td class="fw-semibold">: <?php echo html_escape($user['nama_lab'] ?: 'Seluruh Lab'); ?></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Status Akun</td>
                            <td>: <span class="badge bg-success-subtle text-success border border-success-subtle"><?php echo html_escape($user['status']); ?></span></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Card Tanda Tangan Digital (Khusus Role Penguji, Penyelia, MT) -->
    <div class="col-lg-7 mb-4">
        <div class="card shadow-sm border-0 rounded-3 h-100">
            <div class="card-header bg-white border-bottom py-3">
                <h5 class="card-title text-dark fw-bold mb-0">
                    <i class="bi bi-pen-fill text-primary me-2"></i>Tanda Tangan Digital (TTD)
                </h5>
            </div>
            <div class="card-body">
                <?php if ($boleh_ttd): ?>
                    <p class="text-secondary small mb-4">
                        Tanda tangan digital ini terhubung dengan akun pengguna Anda dan digunakan secara otomatis dalam dokumen dan laporan pengujian resmi sesuai wewenang role Anda.
                    </p>

                    <!-- Flash Notification -->
                    <?php if ($this->session->flashdata('pesan_sukses')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle-fill me-2"></i><?php echo $this->session->flashdata('pesan_sukses'); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php if ($this->session->flashdata('pesan_gagal')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i><?php echo $this->session->flashdata('pesan_gagal'); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Visual Preview & Action -->
                    <?php if (!empty($user_signature) && file_exists(FCPATH . $user_signature['signature_file'])): ?>
                        <div class="card border bg-light mb-4">
                            <div class="card-body text-center py-4">
                                <div class="badge bg-success-subtle text-success border border-success mb-3 px-3 py-1">
                                    <i class="bi bi-check-circle-fill me-1"></i> Tanda Tangan Digital Tersimpan
                                </div>
                                <div class="p-3 bg-white d-inline-block rounded border shadow-sm mb-2">
                                    <img src="<?php echo base_url($user_signature['signature_file']); ?>" alt="TTD Saya" class="img-fluid" style="max-height: 120px; object-fit: contain;">
                                </div>
                                <div class="small text-muted mt-2">
                                    Terakhir diperbarui: <?php echo date('d M Y H:i', strtotime($user_signature['updated_at'])); ?> WIB
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning border-warning d-flex align-items-center mb-4" role="alert">
                            <i class="bi bi-exclamation-circle-fill fs-4 me-3 text-warning"></i>
                            <div>
                                <strong>Belum ada Tanda Tangan Digital.</strong><br>
                                <span class="small">Silakan unggah gambar tanda tangan Anda di bawah ini agar siap digunakan pada dokumen.</span>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Form Unggah / Ganti TTD -->
                    <div class="border rounded p-3 bg-white">
                        <h6 class="fw-bold mb-3">
                            <?php echo (!empty($user_signature)) ? 'Ganti Tanda Tangan Digital' : 'Unggah Tanda Tangan Digital Baru'; ?>
                        </h6>
                        <?php echo form_open_multipart('akun/upload_ttd'); ?>
                            <div class="mb-3">
                                <label for="file_ttd" class="form-label small text-muted">Pilih File Gambar TTD (PNG / JPG / JPEG, Max 2MB)</label>
                                <input class="form-control" type="file" id="file_ttd" name="file_ttd" accept="image/png, image/jpeg, image/jpg" required>
                                <div class="form-text text-muted">Disarankan menggunakan gambar TTD transparan berformat PNG.</div>
                            </div>
                            <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                                <button type="submit" class="btn btn-primary fw-semibold">
                                    <i class="bi bi-upload me-1"></i> <?php echo (!empty($user_signature)) ? 'Simpan TTD Baru' : 'Unggah TTD'; ?>
                                </button>
                        <?php echo form_close(); ?>

                        <?php if (!empty($user_signature)): ?>
                            <?php echo form_open('akun/hapus_ttd', array('class' => 'd-inline')); ?>
                                <button type="submit" class="btn btn-outline-danger btn-sm fw-semibold" onclick="return confirm('Apakah Anda yakin ingin nonaktifkan TTD digital ini dari akun Anda?');">
                                    <i class="bi bi-trash me-1"></i> Nonaktifkan TTD
                                </button>
                            <?php echo form_close(); ?>
                        <?php endif; ?>
                            </div>
                    </div>

                <?php else: ?>
                    <div class="alert alert-secondary border d-flex align-items-center my-3" role="alert">
                        <i class="bi bi-info-circle-fill fs-4 me-3 text-secondary"></i>
                        <div>
                            <strong>Fitur TTD Tidak Tersedia untuk Role Anda.</strong><br>
                            <span class="small">Fitur Tanda Tangan Digital khusus diperuntukkan bagi Penguji, Penyelia, dan Manajer Teknis.</span>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
