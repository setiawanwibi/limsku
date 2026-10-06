<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Custom Workspace Styling matching Design Mockup -->
<style>
.workspace-title { font-size: 1.5rem; font-weight: 700; color: #1e293b; }
.workspace-subtitle { font-size: 0.875rem; color: #64748b; }
.card-sidebar { border-radius: 12px; border: 1px solid #e2e8f0; }
.list-queue-item { border-left: 4px solid transparent; transition: all 0.2s ease; cursor: pointer; }
.list-queue-item.active { border-left-color: #8b5cf6; background-color: #f5f3ff !important; }
.list-queue-item:hover { background-color: #f8fafc; }
.badge-status-verifikasi { background-color: #ede9fe; color: #7c3aed; font-weight: 600; border-radius: 20px; padding: 4px 12px; }
.badge-status-revisi { background-color: #dbeafe; color: #2563eb; font-weight: 600; border-radius: 20px; padding: 2px 8px; font-size: 0.75rem; }
.badge-status-baru { background-color: #f3e8ff; color: #9333ea; font-weight: 600; border-radius: 20px; padding: 2px 8px; font-size: 0.75rem; }
.metric-box { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px; }
.metric-box-warning { background: #fffbe6; border: 1px solid #ffe58f; border-radius: 10px; padding: 14px; }
.btn-keputusan-terima { border: 2px solid #e2e8f0; border-radius: 10px; padding: 12px; transition: all 0.2s; cursor: pointer; }
.btn-keputusan-terima:hover, input[type="radio"]:checked + .btn-keputusan-terima { border-color: #10b981; background-color: #f0fdf4; }
.btn-keputusan-tolak { border: 2px solid #e2e8f0; border-radius: 10px; padding: 12px; transition: all 0.2s; cursor: pointer; }
.btn-keputusan-tolak:hover, input[type="radio"]:checked + .btn-keputusan-tolak { border-color: #ef4444; background-color: #fef2f2; }
</style>

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

<!-- Header Workspace Penyelia -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <span class="text-uppercase font-monospace fw-bold text-success small tracking-wider">WORKSPACE PENYELIA</span>
        <h2 class="workspace-title mb-1">Verifikasi Laporan</h2>
        <p class="workspace-subtitle mb-0">Periksa kelengkapan metode, hasil uji, dan kesimpulan sebelum diteruskan ke MT.</p>
    </div>
    <div class="d-flex align-items-center gap-3">
        <div class="position-relative" style="width: 280px;">
            <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
            <input type="text" class="form-control form-control-sm ps-5 rounded-pill bg-light" placeholder="Cari nomor sampel... ⌘ K">
        </div>
        <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill"><?php echo date('d M Y'); ?></span>
    </div>
</div>

<div class="row g-4">
    
    <!-- LEFT SIDEBAR: Daftar Menunggu Verifikasi -->
    <div class="col-lg-3">
        <div class="card card-sidebar shadow-sm">
            <div class="card-header bg-white py-3 border-bottom border-light">
                <h6 class="fw-bold mb-2 text-dark">Menunggu Verifikasi</h6>
                <div class="d-flex gap-2">
                    <span class="badge bg-light text-dark border px-3 py-1 fw-normal">Semua</span>
                    <span class="badge bg-light text-muted border px-2 py-1 fw-normal">Revisi <span class="badge bg-secondary rounded-pill">0</span></span>
                </div>
            </div>
            <div class="list-group list-group-flush">
                <?php if (!empty($antrean_verifikasi)): ?>
                    <?php foreach ($antrean_verifikasi as $item): ?>
                        <?php 
                            $is_active = ($selected_item && $selected_item['id'] == $item['id']);
                            $kode = $item['kode_sampel_manual'] ?: ($item['no_sampel'] ? 'NO-' . $item['no_sampel'] : 'SMP-' . $item['sample_id']);
                        ?>
                        <a href="<?php echo site_url('pengujian/verifikasi?session_id=' . $item['id']); ?>" class="list-group-item list-group-item-action p-3 list-queue-item <?php echo ($is_active ? 'active' : ''); ?>">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="font-monospace fw-semibold text-muted small"><?php echo html_escape($kode); ?></span>
                                <small class="text-muted" style="font-size: 0.75rem;">
                                    <?php echo html_escape($item['waktu_selesai'] ? date('H.i', strtotime($item['waktu_selesai'])) : '-'); ?>
                                </small>
                            </div>
                            <h6 class="fw-bold mb-1 text-dark text-truncate" style="max-width: 180px;"><?php echo html_escape($item['nama_sampel']); ?></h6>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <small class="text-muted"><?php echo html_escape($item['jenis_pengujian'] ?: 'Pengujian'); ?></small>
                                <span class="badge-status-baru">Baru</span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="p-4 text-center text-muted small">Tidak ada antrean laporan yang menunggu verifikasi.</div>
                <?php endif; ?>
            </div>
            <div class="card-footer bg-white text-center py-2 border-top border-light">
                <small class="text-muted fw-semibold">Total: <?php echo count($antrean_verifikasi); ?> Laporan</small>
            </div>
        </div>
    </div>

    <!-- MAIN & RIGHT PANEL -->
    <?php if ($selected_item): ?>
        <div class="col-lg-6">
            
            <!-- Header Sample Card -->
            <div class="card border-0 shadow-sm mb-4 rounded-3 p-3">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="font-monospace fw-bold text-muted"><?php echo html_escape($selected_item['kode_sampel_manual'] ?: 'SMP-' . $selected_item['sample_id']); ?></span>
                            <span class="badge-status-verifikasi">• MENUNGGU VERIFIKASI</span>
                        </div>
                        <h4 class="fw-bold mb-1 text-dark"><?php echo html_escape($selected_item['nama_sampel']); ?></h4>
                        <small class="text-muted">
                            Penguji: <strong><?php echo html_escape($selected_item['nama_penguji'] ?: 'Analis'); ?></strong> · 
                            Selesai: <?php echo html_escape($selected_item['waktu_selesai'] ? date('d M Y, H.i', strtotime($selected_item['waktu_selesai'])) : '-'); ?>
                        </small>
                    </div>
                    <div>
                        <a href="<?php echo site_url('pengujian/detail_sesi/' . $selected_item['id']); ?>" class="btn btn-outline-secondary btn-sm fw-semibold rounded-pill px-3">
                            <i class="bi bi-folder2-open me-1"></i> Buka Lampiran
                        </a>
                    </div>
                </div>
            </div>

            <!-- Status Check Cards (Kelengkapan, Metode, Worksheet, Kesimpulan) -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <div class="metric-box">
                        <div class="text-success mb-1"><i class="bi bi-box-seam fs-5"></i></div>
                        <small class="text-muted d-block" style="font-size: 0.75rem;">Kelengkapan Sampel</small>
                        <strong class="text-dark">Lengkap</strong>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="metric-box">
                        <div class="text-success mb-1"><i class="bi bi-check-square fs-5"></i></div>
                        <small class="text-muted d-block" style="font-size: 0.75rem;">Metode &amp; IK</small>
                        <strong class="text-dark">Sesuai</strong>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="metric-box">
                        <div class="text-success mb-1"><i class="bi bi-paperclip fs-5"></i></div>
                        <small class="text-muted d-block" style="font-size: 0.75rem;">Worksheet</small>
                        <strong class="text-dark"><?php echo count($selected_forms); ?> Form</strong>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="metric-box-warning">
                        <div class="text-warning mb-1"><i class="bi bi-exclamation-circle fs-5"></i></div>
                        <small class="text-muted d-block" style="font-size: 0.75rem;">Kesimpulan</small>
                        <strong class="text-dark">Perlu ditinjau</strong>
                    </div>
                </div>
            </div>

            <!-- Ringkasan Laporan Hasil Uji (Table of Forms/Parameters) -->
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="fw-bold mb-0 text-dark">Ringkasan Laporan Hasil Uji</h6>
                        <small class="text-muted font-monospace">Sesi ID: #<?php echo $selected_item['id']; ?> (<?php echo html_escape($selected_item['jenis_pengujian']); ?>)</small>
                    </div>
                    <div>
                        <a href="<?php echo site_url('pengujian/detail_sesi/' . $selected_item['id']); ?>" class="btn btn-light border btn-sm rounded-pill px-3">
                            <i class="bi bi-file-earmark-pdf me-1"></i> Preview PDF
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="p-3 bg-light border-bottom d-flex justify-content-between small text-muted">
                        <span><strong>METODE:</strong> <?php echo html_escape($selected_item['nama_metode'] ?: 'Standar Pengujian BBPOM'); ?></span>
                        <span><strong>JENIS:</strong> <?php echo html_escape($selected_item['jenis_pengujian']); ?></span>
                        <span><strong>TANGGAL UJI:</strong> <?php echo html_escape($selected_item['waktu_selesai'] ? date('d M Y', strtotime($selected_item['waktu_selesai'])) : date('d M Y')); ?></span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light small text-uppercase text-muted">
                                <tr>
                                    <th class="ps-3">Parameter / Form</th>
                                    <th>Hasil</th>
                                    <th>Persyaratan</th>
                                    <th class="pe-3">Kesimpulan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($selected_forms)): ?>
                                    <?php foreach ($selected_forms as $form): ?>
                                        <tr>
                                            <td class="ps-3">
                                                <strong class="text-dark d-block"><?php echo html_escape($form['nama_template']); ?></strong>
                                                <small class="text-muted font-monospace"><?php echo html_escape($form['kode_template']); ?></small>
                                            </td>
                                            <td>
                                                <small class="fw-semibold text-dark">
                                                    <?php 
                                                        $dh = !empty($form['data_hasil']) ? json_decode($form['data_hasil'], true) : array();
                                                        if (!empty($dh) && is_array($dh)) {
                                                            echo html_escape(implode(', ', array_slice($dh, 0, 2)));
                                                        } else {
                                                            echo 'Tercatat';
                                                        }
                                                    ?>
                                                </small>
                                            </td>
                                            <td><small class="text-muted">Sesuai Syarat</small></td>
                                            <td class="pe-3">
                                                <?php 
                                                $kes = $form['kesimpulan'];
                                                $b_cls = 'bg-secondary';
                                                if (stripos($kes, 'memenuhi') !== false && stripos($kes, 'tidak') === false) {
                                                    $b_cls = 'bg-success-subtle text-success border border-success-subtle';
                                                } elseif (stripos($kes, 'tidak memenuhi') !== false) {
                                                    $b_cls = 'bg-danger-subtle text-danger border border-danger-subtle';
                                                }
                                                ?>
                                                <span class="badge <?php echo $b_cls; ?> rounded-pill px-3 py-1">
                                                    ● <?php echo html_escape($kes); ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">Belum ada form pengujian terdaftar.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Kesimpulan Penguji Alert -->
                    <div class="p-3 bg-danger-subtle border-top border-danger-subtle text-danger small">
                        <strong class="d-block mb-1 text-uppercase font-monospace" style="font-size: 0.75rem;">KESIMPULAN PENGUJI</strong>
                        <span><?php echo html_escape($selected_item['kesimpulan'] ?: 'Pengujian telah diselesaikan oleh penguji dan siap diverifikasi.'); ?></span>
                    </div>

                </div>
            </div>

        </div>

        <!-- RIGHT SIDEBAR: Keputusan Penyelia -->
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm rounded-3 p-3">
                <h6 class="fw-bold mb-1 text-dark">Keputusan Penyelia</h6>
                <small class="text-muted d-block mb-3">Pilih keputusan setelah seluruh data diperiksa.</small>

                <!-- Form Decision Submission -->
                <form id="formKeputusanPenyelia" action="<?php echo site_url('pengujian/verifikasi_sesi/' . $selected_item['id']); ?>" method="post">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

                    <div class="mb-3">
                        <div class="form-check p-0 mb-2">
                            <input class="btn-check" type="radio" name="keputusan" id="keputusanTerima" value="terima" checked onchange="toggleKeputusan('terima')">
                            <label class="btn-keputusan-terima d-flex justify-content-between align-items-center w-100" for="keputusanTerima">
                                <div>
                                    <strong class="d-block text-dark small">Terima laporan</strong>
                                    <small class="text-muted" style="font-size: 0.75rem;">Kirim ke MT untuk approval</small>
                                </div>
                                <i class="bi bi-check-circle-fill text-success fs-5"></i>
                            </label>
                        </div>

                        <div class="form-check p-0">
                            <input class="btn-check" type="radio" name="keputusan" id="keputusanTolak" value="tolak" onchange="toggleKeputusan('tolak')">
                            <label class="btn-keputusan-tolak d-flex justify-content-between align-items-center w-100" for="keputusanTolak">
                                <div>
                                    <strong class="d-block text-danger small">Tolak &amp; kembalikan</strong>
                                    <small class="text-muted" style="font-size: 0.75rem;">Kembali ke Penguji untuk revisi</small>
                                </div>
                                <i class="bi bi-x-circle-fill text-danger fs-5"></i>
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="alasan_penolakan" class="form-label fw-bold small text-dark mb-1">
                            Komentar <span id="reqKomentar" class="text-danger d-none">*</span>
                        </label>
                        <textarea name="alasan_penolakan" id="alasan_penolakan" class="form-control form-control-sm" rows="4" placeholder="Tuliskan catatan atau masukan untuk penguji..."></textarea>
                        <small id="textHelpKomentar" class="text-muted d-block mt-1" style="font-size: 0.75rem;">Komentar wajib jika memilih keputusan tolak.</small>
                    </div>

                    <button type="submit" id="btnSubmitDecision" class="btn btn-success w-100 fw-bold py-2">
                        &check; Terima &amp; Kirim ke MT
                    </button>
                </form>

            </div>
        </div>
    <?php else: ?>
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm p-5 text-center text-muted">
                <i class="bi bi-inbox fs-1 mb-2"></i>
                <h5>Tidak ada laporan pengujian yang dipilih.</h5>
            </div>
        </div>
    <?php endif; ?>

</div>

<script>
function toggleKeputusan(type) {
    const form = document.getElementById('formKeputusanPenyelia');
    const reqKomentar = document.getElementById('reqKomentar');
    const textHelpKomentar = document.getElementById('textHelpKomentar');
    const btnSubmit = document.getElementById('btnSubmitDecision');
    const txtArea = document.getElementById('alasan_penolakan');

    if (type === 'tolak') {
        form.action = '<?php echo site_url('pengujian/tolak_sesi/' . ($selected_item ? $selected_item['id'] : 0)); ?>';
        reqKomentar.classList.remove('d-none');
        textHelpKomentar.classList.add('text-danger');
        textHelpKomentar.classList.remove('text-muted');
        btnSubmit.className = 'btn btn-danger w-100 fw-bold py-2';
        btnSubmit.innerHTML = '<i class="bi bi-arrow-return-left me-1"></i> Tolak &amp; Kirim Revisi';
        txtArea.required = true;
    } else {
        form.action = '<?php echo site_url('pengujian/verifikasi_sesi/' . ($selected_item ? $selected_item['id'] : 0)); ?>';
        reqKomentar.classList.add('d-none');
        textHelpKomentar.classList.remove('text-danger');
        textHelpKomentar.classList.add('text-muted');
        btnSubmit.className = 'btn btn-success w-100 fw-bold py-2';
        btnSubmit.innerHTML = '&check; Terima &amp; Kirim ke MT';
        txtArea.required = false;
    }
}
</script>
