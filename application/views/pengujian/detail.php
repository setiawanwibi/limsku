<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Flash Messages -->
<?php if ($this->session->flashdata('pesan_sukses')): ?>
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> <?php echo html_escape($this->session->flashdata('pesan_sukses')); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if ($this->session->flashdata('pesan_gagal')): ?>
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo html_escape($this->session->flashdata('pesan_gagal')); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- Box Status Penolakan jika Status = Ditolak -->
<?php if ($hasil['status'] === 'Ditolak'): ?>
    <div class="alert alert-danger border-2 border-danger shadow-sm mb-4">
        <div class="d-flex align-items-center mb-2">
            <i class="bi bi-x-circle-fill text-danger fs-3 me-2"></i>
            <h5 class="fw-bold mb-0 text-danger">Laporan Hasil Pengujian Ditolak oleh Penyelia</h5>
        </div>
        <hr class="my-2">
        <p class="mb-1"><strong>Diverifikasi / Ditolak Oleh:</strong> <?php echo html_escape($hasil['nama_verifier'] ?: 'Penyelia'); ?></p>
        <p class="mb-1"><strong>Waktu Penolakan:</strong> <?php echo html_escape($hasil['waktu_verifikasi'] ? date('d-m-Y H:i:s', strtotime($hasil['waktu_verifikasi'])) : '-'); ?></p>
        <p class="mb-0 text-danger fw-semibold"><strong>Alasan Penolakan:</strong></p>
        <div class="bg-white p-3 rounded border text-dark font-monospace mt-1">
            <?php echo nl2br(html_escape($hasil['alasan_penolakan'])); ?>
        </div>

        <?php if ($this->Model_Hak_Akses->memiliki_akses($this->session->userdata('role_id'), 'pengujian_input')): ?>
            <div class="mt-3">
                <a href="<?php echo site_url('pengujian/revisi/' . $hasil['id']); ?>" class="btn btn-warning fw-bold">
                    <i class="bi bi-pencil-square me-1"></i> Perbaiki &amp; Revisi Hasil Pengujian
                </a>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>

<div class="row mb-4">
    <div class="col-md-7">
        <h1 class="h3 text-gray-800 fw-bold"><?php echo html_escape($judul_halaman); ?></h1>
        <p class="text-muted">Rincian hasil pengujian laboratorium dan status dokumen dalam workflow.</p>
    </div>
    <div class="col-md-5 text-end d-flex align-items-center justify-content-end gap-2">
        <a href="<?php echo site_url('pengujian/riwayat'); ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Riwayat
        </a>
        
        <?php if (!empty($hasil['file_laporan'])): ?>
            <a href="<?php echo site_url('pengujian/pdf/' . $hasil['id']); ?>" class="btn btn-danger" target="_blank">
                <i class="bi bi-file-earmark-pdf"></i> Unduh LHU PDF
            </a>
        <?php endif; ?>

        <!-- Tombol Aksi Verifikasi Penyelia jika status = Menunggu Verifikasi & user punya hak pengujian_verify -->
        <?php if ($hasil['status'] === 'Menunggu Verifikasi' && $this->Model_Hak_Akses->memiliki_akses($this->session->userdata('role_id'), 'pengujian_verify')): ?>
            <form action="<?php echo site_url('pengujian/verifikasi/setujui/' . $hasil['id']); ?>" method="post" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin memverifikasi dan menyetujui laporan hasil pengujian ini?');">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <button type="submit" class="btn btn-success fw-bold">
                    <i class="bi bi-check-circle me-1"></i> Verifikasi (Setujui)
                </button>
            </form>
            
            <button type="button" class="btn btn-outline-danger fw-bold" data-bs-toggle="modal" data-bs-target="#modalTolak">
                <i class="bi bi-x-circle me-1"></i> Tolak Laporan
            </button>
        <?php endif; ?>

        <!-- Tombol Aksi Approval Manajer Teknis jika status = Menunggu Approval & user punya hak pengujian_approve -->
        <?php if ($hasil['status'] === 'Menunggu Approval' && $this->Model_Hak_Akses->memiliki_akses($this->session->userdata('role_id'), 'pengujian_approve')): ?>
            <form action="<?php echo site_url('pengujian/proses_approval/' . $hasil['id']); ?>" method="post" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin melakukan Approval final pada laporan hasil pengujian ini? Laporan yang di-approve akan menjadi status Final.');">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                <button type="submit" class="btn btn-primary fw-bold">
                    <i class="bi bi-shield-check me-1"></i> Approve &amp; Finalize
                </button>
            </form>
        <?php endif; ?>
    </div>
</div>

<div class="row">
    <!-- Informasi Sampel -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-primary text-white font-weight-bold">
                <i class="bi bi-box-seam me-2"></i> Informasi Sampel
            </div>
            <div class="card-body">
                <table class="table table-borderless table-sm">
                    <tr>
                        <th style="width: 40%;">Kode Sampel</th>
                        <td>: <span class="badge bg-dark font-monospace"><?php echo html_escape($hasil['kode_sampel_manual'] ?: ($hasil['no_sampel'] ? 'NO-' . $hasil['no_sampel'] : 'SMP-' . $hasil['sample_id'])); ?></span></td>
                    </tr>
                    <tr>
                        <th>Nama Sampel</th>
                        <td>: <strong><?php echo html_escape($hasil['nama_sampel']); ?></strong></td>
                    </tr>
                    <tr>
                        <th>Kategori Sampel</th>
                        <td>: <?php echo html_escape($hasil['kategori_sampel'] ?: '-'); ?></td>
                    </tr>
                    <tr>
                        <th>Laboratorium</th>
                        <td>: <?php echo html_escape($hasil['nama_lab'] ?: '-'); ?></td>
                    </tr>
                    <tr>
                        <th>Status Sampel / Laporan</th>
                        <td>: 
                            <?php 
                            $s_cls = 'bg-secondary';
                            if ($hasil['status'] === 'Menunggu Verifikasi') $s_cls = 'bg-info text-dark';
                            elseif ($hasil['status'] === 'Menunggu Approval') $s_cls = 'bg-warning text-dark';
                            elseif ($hasil['status'] === 'Approved / Final') $s_cls = 'bg-success';
                            elseif ($hasil['status'] === 'Ditolak') $s_cls = 'bg-danger';
                            ?>
                            <span class="badge <?php echo $s_cls; ?> px-2 py-1">
                                <?php echo html_escape($hasil['status']); ?>
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Ringkasan Pengujian & Verifikasi -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-info text-white font-weight-bold">
                <i class="bi bi-card-checklist me-2"></i> Ringkasan Pengujian &amp; Verifikasi
            </div>
            <div class="card-body">
                <table class="table table-borderless table-sm">
                    <tr>
                        <th style="width: 40%;">Penguji / Analis</th>
                        <td>: <?php echo html_escape($hasil['nama_penguji'] ?: '-'); ?></td>
                    </tr>
                    <tr>
                        <th>Metode Digunakan</th>
                        <td>: <?php echo html_escape($hasil['nama_metode'] ? $hasil['kode_metode'] . ' - ' . $hasil['nama_metode'] : '-'); ?></td>
                    </tr>
                    <tr>
                        <th>Form Template</th>
                        <td>: <?php echo html_escape($hasil['nama_template'] ?: '-'); ?></td>
                    </tr>
                    <tr>
                        <th>Waktu Pelaksanaan</th>
                        <td>: <?php echo html_escape($hasil['waktu_mulai'] ? date('d-m-Y H:i:s', strtotime($hasil['waktu_mulai'])) : '-'); ?></td>
                    </tr>
                    <tr>
                        <th>Penyelia Verifikator</th>
                        <td>: <?php echo html_escape($hasil['nama_verifier'] ?: '-'); ?></td>
                    </tr>
                    <tr>
                        <th>Waktu Verifikasi</th>
                        <td>: <?php echo html_escape($hasil['waktu_verifikasi'] ? date('d-m-Y H:i:s', strtotime($hasil['waktu_verifikasi'])) : '-'); ?></td>
                    </tr>
                    <tr>
                        <th>Manajer Teknis (Approver)</th>
                        <td>: <?php echo html_escape($hasil['nama_approver'] ?: '-'); ?></td>
                    </tr>
                    <tr>
                        <th>Waktu Approval</th>
                        <td>: <?php echo html_escape($hasil['waktu_approval'] ? date('d-m-Y H:i:s', strtotime($hasil['waktu_approval'])) : '-'); ?></td>
                    </tr>
                    <tr>
                        <th>Kesimpulan Uji</th>
                        <td>: 
                            <?php 
                            $kes = $hasil['kesimpulan'];
                            $badge_cls = 'bg-secondary';
                            if (stripos($kes, 'memenuhi') !== false && stripos($kes, 'tidak') === false) {
                                $badge_cls = 'bg-success';
                            } elseif (stripos($kes, 'tidak memenuhi') !== false) {
                                $badge_cls = 'bg-danger';
                            }
                            ?>
                            <span class="badge <?php echo $badge_cls; ?> px-2 py-1">
                                <?php echo html_escape($kes); ?>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Catatan Penguji</th>
                        <td>: <?php echo nl2br(html_escape($hasil['catatan'] ?: '-')); ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Hasil Parameter Pengujian (Dynamic JSON Data) -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-dark text-white font-weight-bold">
        <i class="bi bi-clipboard-data me-2"></i> Data Hasil Parameter Pengujian
    </div>
    <div class="card-body">
        <?php 
        $data_hasil = json_decode($hasil['data_hasil'], true);
        if (!empty($data_hasil) && is_array($data_hasil)):
        ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;" class="text-center">No</th>
                            <th>Parameter / Field Pengujian</th>
                            <th>Nilai / Hasil Pengamatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($data_hasil as $key => $val): ?>
                            <tr>
                                <td class="text-center"><?php echo $no++; ?></td>
                                <td><strong><?php echo html_escape(ucwords(str_replace('_', ' ', $key))); ?></strong></td>
                                <td>
                                    <?php 
                                    if (is_array($val)) {
                                        echo html_escape(json_encode($val));
                                    } else {
                                        echo html_escape($val);
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-muted mb-0">Tidak ada data hasil uji yang terekam.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Penolakan Laporan Hasil Pengujian -->
<?php if ($hasil['status'] === 'Menunggu Verifikasi' && $this->Model_Hak_Akses->memiliki_akses($this->session->userdata('role_id'), 'pengujian_verify')): ?>
    <div class="modal fade" id="modalTolak" tabindex="-1" aria-labelledby="modalTolakLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="<?php echo site_url('pengujian/verifikasi/tolak/' . $hasil['id']); ?>" method="post">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title fw-bold" id="modalTolakLabel">
                            <i class="bi bi-exclamation-octagon me-2"></i> Penolakan Hasil Pengujian
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted small">
                            Penolakan akan mengembalikan status laporan menjadi <strong>"Ditolak"</strong> dan mengirimkannya kembali ke penguji untuk diperbaiki.
                        </p>
                        <div class="mb-3">
                            <label for="alasan_penolakan" class="form-label fw-bold">Alasan Penolakan <span class="text-danger">*</span></label>
                            <textarea name="alasan_penolakan" id="alasan_penolakan" class="form-control" rows="4" placeholder="Tuliskan catatan perbaikan atau alasan penolakan secara jelas..." required></textarea>
                            <div class="form-text">Alasan penolakan wajib diisi untuk memberi kejelasan revisi bagi Penguji.</div>
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
