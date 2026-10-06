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
    <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">Detail Sesi Pengujian #<?php echo $sesi['id']; ?></h5>
        <div>
            <a href="<?php echo site_url('pengujian/antrean'); ?>" class="btn btn-light btn-sm fw-semibold me-2">&larr; Kembali ke Antrean</a>
            <?php if ($sesi['penguji_id'] == $this->session->userdata('user_id') && $sesi['status'] === 'Sedang Diuji'): ?>
                <a href="<?php echo site_url('pengujian/proses_sesi/' . $sesi['id']); ?>" class="btn btn-warning btn-sm fw-semibold">Lanjutkan Pengujian</a>
            <?php endif; ?>

            <?php if ($sesi['status'] === 'Menunggu Verifikasi' && $this->Model_Hak_Akses->memiliki_akses($this->session->userdata('role_id'), 'pengujian_verify')): ?>
                <form action="<?php echo site_url('pengujian/verifikasi_sesi/' . $sesi['id']); ?>" method="post" class="d-inline me-1" onsubmit="return confirm('Apakah Anda yakin ingin memverifikasi dan menyetujui seluruh hasil pengujian sesi ini?');">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <button type="submit" class="btn btn-success btn-sm fw-bold">
                        <i class="bi bi-check-circle me-1"></i> Verifikasi Sesi
                    </button>
                </form>
                <button type="button" class="btn btn-danger btn-sm fw-bold" data-bs-toggle="modal" data-bs-target="#modalTolakSesi">
                    <i class="bi bi-x-circle me-1"></i> Tolak Sesi
                </button>
            <?php endif; ?>

            <?php if ($sesi['status'] === 'Menunggu Approval' && $this->Model_Hak_Akses->memiliki_akses($this->session->userdata('role_id'), 'pengujian_approve')): ?>
                <form action="<?php echo site_url('pengujian/approve_sesi/' . $sesi['id']); ?>" method="post" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin melakukan Approval final pada seluruh hasil pengujian sesi ini?');">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <button type="submit" class="btn btn-success btn-sm fw-bold">
                        <i class="bi bi-shield-check me-1"></i> Approve Final Sesi
                    </button>
                </form>
            <?php endif; ?>
            <?php if ($sesi['penguji_id'] == $this->session->userdata('user_id') && $sesi['status'] === 'Ditolak'): ?>
                <a href="<?php echo site_url('pengujian/revisi_sesi/' . $sesi['id']); ?>" class="btn btn-warning btn-sm fw-bold">
                    <i class="bi bi-pencil-square me-1"></i> Perbaiki &amp; Revisi Hasil Sesi
                </a>
            <?php endif; ?>
        </div>
    </div>
    
    <?php if ($sesi['status'] === 'Ditolak' && !empty($sesi['alasan_penolakan'])): ?>
        <div class="card-body bg-danger-subtle border-bottom border-danger">
            <div class="d-flex align-items-start text-danger">
                <i class="bi bi-exclamation-octagon-fill fs-4 me-2"></i>
                <div>
                    <strong class="d-block">Sesi Pengujian Ditolak oleh Penyelia:</strong>
                    <div class="bg-white p-2 rounded border border-danger text-dark font-monospace mt-1">
                        <?php echo nl2br(html_escape($sesi['alasan_penolakan'])); ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <table class="table table-sm table-borderless">
                    <tr>
                        <td width="35%" class="text-muted">Nama Sampel:</td>
                        <td><strong class="text-dark"><?php echo html_escape($sesi['nama_sampel']); ?></strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Kode / No Sampel:</td>
                        <td><span class="font-monospace text-primary"><?php echo html_escape($sesi['kode_sampel_manual'] ?: ($sesi['no'] ? 'NO-' . $sesi['no'] : 'SMP-' . $sesi['sample_id'])); ?></span></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Jenis Pengujian:</td>
                        <td>
                            <span class="badge bg-info text-dark fw-bold">
                                <?php echo html_escape($sesi['jenis_pengujian']); ?>
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-sm table-borderless">
                    <tr>
                        <td width="35%" class="text-muted">Status Sesi:</td>
                        <td>
                            <?php
                            $badge_cls = 'bg-secondary';
                            if ($sesi['status'] === 'Sedang Diuji') $badge_cls = 'bg-warning text-dark';
                            elseif ($sesi['status'] === 'Menunggu Verifikasi') $badge_cls = 'bg-info text-dark';
                            elseif ($sesi['status'] === 'Menunggu Approval') $badge_cls = 'bg-primary';
                            elseif ($sesi['status'] === 'Approved / Final') $badge_cls = 'bg-success';
                            elseif ($sesi['status'] === 'Ditolak') $badge_cls = 'bg-danger';
                            ?>
                            <span class="badge <?php echo $badge_cls; ?>"><?php echo html_escape($sesi['status']); ?></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Penguji:</td>
                        <td><strong><?php echo html_escape($sesi['nama_penguji'] ?: 'Penguji ID: ' . $sesi['penguji_id']); ?></strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Waktu Mulai:</td>
                        <td><small><?php echo html_escape($sesi['waktu_mulai'] ?: '-'); ?></small></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h6 class="fw-bold mb-0 text-dark">Form & Parameter Terdaftar (<?php echo count($forms); ?> Form)</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="5%">#</th>
                        <th>Kode Template</th>
                        <th>Nama Parameter / Template</th>
                        <th>Kategori</th>
                        <th>Status Hasil</th>
                        <th>Kesimpulan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($forms)): ?>
                        <?php $no = 1; foreach ($forms as $f): ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><span class="font-monospace fw-bold text-primary"><?php echo html_escape($f['kode_template']); ?></span></td>
                                <td><strong class="text-dark"><?php echo html_escape($f['nama_template']); ?></strong></td>
                                <td><span class="badge bg-light text-dark border"><?php echo html_escape($f['kategori']); ?></span></td>
                                <td>
                                    <?php if (!empty($f['data_hasil'])): ?>
                                        <span class="badge bg-success">Sudah Diisi</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Belum Diisi</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo html_escape($f['kesimpulan'] ?: '-'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-3">Belum ada form yang didaftarkan pada sesi ini.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Penolakan Sesi -->
<?php if ($sesi['status'] === 'Menunggu Verifikasi' && $this->Model_Hak_Akses->memiliki_akses($this->session->userdata('role_id'), 'pengujian_verify')): ?>
    <div class="modal fade" id="modalTolakSesi" tabindex="-1" aria-labelledby="modalTolakSesiLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="<?php echo site_url('pengujian/tolak_sesi/' . $sesi['id']); ?>" method="post">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title fw-bold" id="modalTolakSesiLabel">
                            <i class="bi bi-exclamation-octagon me-2"></i> Penolakan Sesi Pengujian
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted small">
                            Penolakan akan mengembalikan status sesi pengujian menjadi <strong>"Ditolak"</strong> dan mengirimkannya kembali ke penguji untuk diperbaiki.
                        </p>
                        <div class="mb-3">
                            <label for="alasan_penolakan" class="form-label fw-bold">Alasan Penolakan <span class="text-danger">*</span></label>
                            <textarea name="alasan_penolakan" id="alasan_penolakan" class="form-control" rows="4" placeholder="Tuliskan catatan perbaikan atau alasan penolakan secara jelas..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger fw-bold">Konfirmasi Penolakan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>
