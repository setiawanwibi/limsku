<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
    .tinjauan-lhu-page {
        color: #294257;
        font-size: .88rem;
    }

    .tinjauan-card {
        background: #fff;
        border: 1px solid #e0e8ed;
        border-radius: 11px;
        box-shadow: 0 5px 18px rgba(35, 58, 76, .06);
        overflow: hidden;
        margin-bottom: 20px;
    }

    .tinjauan-header {
        padding: 18px 22px;
        background: #fff;
        border-bottom: 1px solid #e7edf0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        flex-wrap: wrap;
    }

    .tinjauan-header-main {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .tinjauan-header-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        background: #e4f6f4;
        color: #159b98;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .tinjauan-title {
        margin: 0;
        color: #294257;
        font-size: 1.05rem;
        font-weight: 700;
    }

    .tinjauan-subtitle {
        margin: 3px 0 0;
        color: #7a8d9a;
        font-size: .75rem;
    }

    .tinjauan-body {
        padding: 22px;
    }

    .meta-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 22px;
    }

    .meta-label {
        font-size: .70rem;
        text-transform: uppercase;
        color: #64748b;
        font-weight: 700;
        margin-bottom: 2px;
    }

    .meta-val {
        font-size: .84rem;
        color: #1e293b;
        font-weight: 600;
    }

    .sesi-section {
        border: 1px solid #e2e8f0;
        border-radius: 9px;
        overflow: hidden;
        margin-bottom: 24px;
    }

    .sesi-section-header {
        background: #f1f5f9;
        padding: 12px 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid #e2e8f0;
    }

    .sesi-section-title {
        font-size: .88rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .sesi-table {
        margin: 0;
        font-size: .78rem;
    }

    .sesi-table thead th {
        background: #f8fafc;
        padding: 9px 14px;
        border-bottom: 1px solid #e2e8f0;
        font-size: .68rem;
        text-transform: uppercase;
        color: #64748b;
    }

    .sesi-table tbody td {
        padding: 11px 14px;
        vertical-align: middle;
        border-color: #f1f5f9;
    }
</style>

<div class="tinjauan-lhu-page">

    <!-- Flash Messages -->
    <?php if ($this->session->flashdata('pesan_sukses')): ?>
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            <?php echo html_escape($this->session->flashdata('pesan_sukses')); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('pesan_gagal')): ?>
        <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <?php echo html_escape($this->session->flashdata('pesan_gagal')); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Main Card -->
    <div class="tinjauan-card">

        <!-- Header -->
        <div class="tinjauan-header">
            <div class="tinjauan-header-main">
                <div class="tinjauan-header-icon">
                    <i class="bi bi-file-earmark-check"></i>
                </div>
                <div>
                    <h5 class="tinjauan-title">Tinjauan LHU Komprehensif (Lintas Sesi)</h5>
                    <p class="tinjauan-subtitle">Pemeriksaan hasil seluruh sesi pengujian (Kimia & Mikrobiologi) sebelum pengesahan Manajer Teknis</p>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                <?php if ($sampel['status'] === 'Approved / Final'): ?>
                    <a href="<?php echo site_url('pengujian/pdf_sampel/' . $sampel['id']); ?>" class="btn btn-danger btn-sm" target="_blank">
                        <i class="bi bi-file-earmark-pdf-fill me-1"></i> Unduh LHU PDF Final
                    </a>
                    <a href="<?php echo site_url('pengujian/riwayat_approval'); ?>" class="btn btn-success btn-sm">
                        <i class="bi bi-file-earmark-check me-1"></i> Ke Riwayat Approval
                    </a>
                <?php endif; ?>
                <a href="<?php echo site_url('pengujian/approval'); ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Antrean
                </a>
            </div>
        </div>

        <!-- Body -->
        <div class="tinjauan-body">

            <!-- Banner Status Kesiapan -->
            <div class="alert <?php echo (!empty($kelengkapan['siap_mt']) ? 'alert-success' : 'alert-warning'); ?> d-flex align-items-center mb-4" role="alert">
                <i class="bi <?php echo (!empty($kelengkapan['siap_mt']) ? 'bi-check-circle-fill text-success' : 'bi-exclamation-triangle-fill text-warning'); ?> fs-4 me-3"></i>
                <div>
                    <strong>Status Kesiapan LHU MT:</strong> <?php echo html_escape($kelengkapan['status_agregat']); ?><br>
                    <small class="text-muted"><?php echo html_escape($kelengkapan['alasan']); ?></small>
                </div>
            </div>

            <!-- Identitas Sampel & Kebutuhan Pengujian -->
            <div class="meta-box">
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="meta-label">Kode Sampel</div>
                        <div class="meta-val text-primary">
                            <?php echo html_escape($sampel['kode_sampel_manual'] ?: (!empty($sampel['no']) ? 'NO-' . $sampel['no'] : 'SMP-' . $sampel['id'])); ?>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="meta-label">Nama Sampel</div>
                        <div class="meta-val"><?php echo html_escape($sampel['nama_sampel']); ?></div>
                    </div>
                    <div class="col-md-2">
                        <div class="meta-label">Pengujian Kimia</div>
                        <div class="meta-val">
                            <?php if (!empty($kelengkapan['status_sesi']['Kimia']['sesi_id'])): ?>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                                    <i class="bi bi-check-circle me-1"></i><?php echo html_escape($kelengkapan['status_sesi']['Kimia']['status']); ?>
                                </span>
                            <?php elseif (!empty($kelengkapan['wajib_kimia'])): ?>
                                <span class="badge bg-secondary-subtle text-secondary border">
                                    Alokasi: <?php echo html_escape($sampel['jumlah_kimia']); ?> (Belum Ada Sesi)
                                </span>
                            <?php else: ?>
                                <span class="text-muted">- (Tidak Ada)</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="meta-label">Pengujian Mikrobiologi</div>
                        <div class="meta-val">
                            <?php if (!empty($kelengkapan['status_sesi']['Mikrobiologi']['sesi_id'])): ?>
                                <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle">
                                    <i class="bi bi-check-circle me-1"></i><?php echo html_escape($kelengkapan['status_sesi']['Mikrobiologi']['status']); ?>
                                </span>
                            <?php elseif (!empty($kelengkapan['wajib_mikro'])): ?>
                                <span class="badge bg-secondary-subtle text-secondary border">
                                    Alokasi: <?php echo html_escape($sampel['jumlah_mikro']); ?> (Belum Ada Sesi)
                                </span>
                            <?php else: ?>
                                <span class="text-muted">- (Tidak Ada)</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rincian Sesi-Sesi Pengujian -->
            <h6 class="fw-bold mb-3 text-secondary">
                <i class="bi bi-collection me-1"></i> Rincian Seluruh Sesi Pengujian Terkait Sampel
            </h6>

            <?php if (!empty($sesi_list)): ?>
                <?php foreach ($sesi_list as $sesi): ?>
                    <div class="sesi-section">
                        <div class="sesi-section-header">
                            <div class="sesi-section-title">
                                <span class="badge <?php echo ($sesi['jenis_pengujian'] === 'Mikrobiologi' ? 'bg-info text-white' : 'bg-primary text-white'); ?>">
                                    <?php echo html_escape($sesi['jenis_pengujian']); ?>
                                </span>
                                <span>Sesi #<?php echo $sesi['id']; ?> (<?php echo html_escape(!empty($sesi['kode_sesi']) ? $sesi['kode_sesi'] : 'SESI-' . $sesi['id']); ?>)</span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-secondary-subtle text-secondary border">
                                    Penguji: <?php echo html_escape(!empty($sesi['nama_penguji']) ? $sesi['nama_penguji'] : '-'); ?>
                                </span>
                                <span class="badge bg-secondary-subtle text-secondary border">
                                    Penyelia: <?php echo html_escape(!empty($sesi['nama_verifier']) ? $sesi['nama_verifier'] : '-'); ?>
                                </span>
                                <span class="badge <?php echo ($sesi['status'] === 'Menunggu Approval' ? 'bg-success' : 'bg-warning text-dark'); ?>">
                                    <?php echo html_escape($sesi['status']); ?>
                                </span>
                                <a href="<?php echo site_url('pengujian/detail_sesi/' . $sesi['id']); ?>" class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size: 0.72rem;" target="_blank">
                                    <i class="bi bi-box-arrow-up-right me-1"></i>Detail Sesi
                                </a>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle sesi-table">
                                <thead>
                                    <tr>
                                        <th width="5%">#</th>
                                        <th>Parameter Uji</th>
                                        <th>Metode / Pustaka</th>
                                        <th>Syarat Keberterimaan</th>
                                        <th>Hasil Analisis</th>
                                        <th>Kesimpulan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($sesi['forms'])): ?>
                                        <?php $no_f = 1; foreach ($sesi['forms'] as $form): ?>
                                            <tr>
                                                <td><?php echo $no_f++; ?></td>
                                                <td>
                                                    <strong><?php echo html_escape(!empty($form['parameter_uji']) ? $form['parameter_uji'] : (!empty($form['nama_parameter']) ? $form['nama_parameter'] : '-')); ?></strong>
                                                    <div class="text-muted" style="font-size: 0.68rem;">
                                                        Form: <?php echo html_escape(!empty($form['nama_template']) ? $form['nama_template'] : 'Form LCP'); ?>
                                                    </div>
                                                </td>
                                                <td>
                                                    <?php echo html_escape(!empty($form['metode_analisa']) ? $form['metode_analisa'] : (!empty($form['nama_metode']) ? $form['nama_metode'] : '-')); ?>
                                                    <div class="text-muted" style="font-size: 0.68rem;">
                                                        <?php echo html_escape(!empty($form['pustaka_acuan']) ? $form['pustaka_acuan'] : '-'); ?>
                                                    </div>
                                                </td>
                                                <td><?php echo html_escape(!empty($form['persyaratan']) ? $form['persyaratan'] : '-'); ?></td>
                                                <td>
                                                    <?php
                                                        $raw_data = !empty($form['data_hasil']) ? json_decode($form['data_hasil'], true) : [];
                                                        $ringkasan_hasil = [];
                                                        if (is_array($raw_data)) {
                                                            foreach ($raw_data as $k => $val) {
                                                                if (is_scalar($val) && $val !== '' && $k !== 'csrf_token') {
                                                                    $ringkasan_hasil[] = html_escape($k) . ': <strong>' . html_escape((string)$val) . '</strong>';
                                                                }
                                                            }
                                                        }
                                                        echo !empty($ringkasan_hasil) ? implode(', ', array_slice($ringkasan_hasil, 0, 3)) : '-';
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                        $kes = !empty($form['kesimpulan']) ? $form['kesimpulan'] : '';
                                                        $cls = 'secondary';
                                                        if (stripos($kes, 'memenuhi') !== false && stripos($kes, 'tidak') === false) {
                                                            $cls = 'success';
                                                        } elseif (stripos($kes, 'tidak memenuhi') !== false) {
                                                            $cls = 'danger';
                                                        }
                                                    ?>
                                                    <span class="badge bg-<?php echo $cls; ?>-subtle text-<?php echo $cls; ?> border border-<?php echo $cls; ?>-subtle">
                                                        <?php echo html_escape($kes ?: '-'); ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-3">
                                                Belum ada form parameter pada sesi ini.
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="alert alert-secondary text-center py-4">
                    Belum ada sesi pengujian yang tercatat untuk sampel ini.
                </div>
            <?php endif; ?>

            <!-- Panel Keputusan Manajer Teknis (MT) Tingkat Sampel -->
            <div class="card border-0 shadow-sm mt-4" style="background: #f8fafc; border: 1px solid #e2e8f0 !important; border-radius: 9px;">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-2 text-dark">
                        <i class="bi bi-shield-check me-1 text-primary"></i> Keputusan Manajer Teknis (Tingkat Sampel)
                    </h6>
                    <p class="text-muted small mb-3">
                        Persetujuan atau penolakan diproses sekaligus untuk seluruh sesi pengujian wajib sampel ini.
                    </p>

                    <?php if ($sampel['status'] === 'Approved / Final'): ?>
                        <div class="d-flex align-items-center justify-content-between p-3 rounded border border-success flex-wrap gap-2" style="background: #f0fdf4;">
                            <div class="text-success small">
                                <i class="bi bi-patch-check-fill me-1 fs-6"></i>
                                <strong>LHU Sampel Telah Disahkan:</strong> Seluruh sesi pengujian telah disetujui Manajer Teknis dan berkas LHU PDF final resmi telah diterbitkan. Laporan tersimpan di <strong>Riwayat Approval</strong>.
                            </div>
                            <div class="d-flex gap-2">
                                <a href="<?php echo site_url('pengujian/pdf_sampel/' . $sampel['id']); ?>" class="btn btn-danger btn-sm px-3" target="_blank">
                                    <i class="bi bi-file-earmark-pdf-fill me-1"></i> Buka LHU PDF
                                </a>
                                <a href="<?php echo site_url('pengujian/riwayat_approval'); ?>" class="btn btn-success btn-sm px-3">
                                    <i class="bi bi-file-earmark-check me-1"></i> Buka Riwayat Approval
                                </a>
                            </div>
                        </div>
                    <?php elseif (!empty($kelengkapan['siap_mt']) && $this->Model_Hak_Akses->memiliki_akses($this->session->userdata('role_id'), 'pengujian_approve')): ?>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <!-- Form Setujui Sampel -->
                            <form action="<?php echo site_url('pengujian/approve_sampel/' . $sampel['id']); ?>"
                                  method="post"
                                  class="d-inline"
                                  onsubmit="return confirm('Apakah Anda yakin ingin menyetujui seluruh hasil pengujian sesi wajib untuk sampel ini? LHU PDF final resmi akan diterbitkan dengan tanda tangan digital.');">
                                <input type="hidden"
                                       name="<?php echo $this->security->get_csrf_token_name(); ?>"
                                       value="<?php echo $this->security->get_csrf_hash(); ?>">
                                <button type="submit" class="btn btn-success px-4">
                                    <i class="bi bi-check-circle me-1"></i> Setujui Seluruh Hasil Uji Sampel
                                </button>
                            </form>

                            <!-- Tombol Tolak Sampel (Buka Modal) -->
                            <button type="button"
                                    class="btn btn-outline-danger px-4"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalTolakSampel">
                                <i class="bi bi-x-circle me-1"></i> Tolak Pengujian Sampel
                            </button>
                        </div>
                    <?php else: ?>
                        <div class="d-flex align-items-center justify-content-between p-3 rounded" style="background: #f1f5f9;">
                            <div class="text-muted small">
                                <i class="bi bi-info-circle me-1"></i>
                                <?php if (empty($kelengkapan['siap_mt'])): ?>
                                    Tombol persetujuan dinonaktifkan karena seluruh sesi wajib belum lengkap atau terdapat konflik administratif.
                                <?php else: ?>
                                    Anda tidak memiliki hak akses (role) untuk mengesahkan keputusan Manajer Teknis.
                                <?php endif; ?>
                            </div>
                            <?php if ($this->Model_Hak_Akses->memiliki_akses($this->session->userdata('role_id'), 'pengujian_approve')): ?>
                                <!-- Tetap sediakan tombol tolak jika ada sesi yang perlu dikembalikan untuk revisi -->
                                <button type="button"
                                        class="btn btn-outline-danger btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalTolakSampel">
                                    <i class="bi bi-x-circle me-1"></i> Tolak Pengujian Sampel
                                </button>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>

</div>

<!-- Modal Penolakan MT Tingkat Sampel -->
<div class="modal fade" id="modalTolakSampel" tabindex="-1" aria-labelledby="modalTolakSampelLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form action="<?php echo site_url('pengujian/tolak_sampel/' . $sampel['id']); ?>" method="post">
                <input type="hidden"
                       name="<?php echo $this->security->get_csrf_token_name(); ?>"
                       value="<?php echo $this->security->get_csrf_hash(); ?>">

                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title text-danger fw-bold fs-6" id="modalTolakSampelLabel">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i> Penolakan Pengujian oleh MT
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body py-3">
                    <p class="text-muted small mb-3">
                        Seluruh sesi pengujian aktif pada sampel ini akan dikembalikan dengan status <strong>Ditolak</strong> untuk direvisi oleh analis dan penyelia.
                    </p>

                    <div class="mb-3">
                        <label for="alasan_penolakan" class="form-label small fw-bold text-secondary">
                            Alasan Penolakan <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control form-control-sm"
                                  id="alasan_penolakan"
                                  name="alasan_penolakan"
                                  rows="4"
                                  placeholder="Tuliskan catatan teknis dan alasan penolakan secara jelas (minimal 5 karakter)..."
                                  required></textarea>
                    </div>
                </div>

                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger btn-sm px-3">
                        <i class="bi bi-send-x me-1"></i> Kirim Penolakan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

