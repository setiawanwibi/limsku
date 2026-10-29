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

<?php 
// Periksa apakah sampel atau salah satu sesi telah ditolak (baik oleh MT maupun Penyelia)
$is_ditolak = ($sampel['status'] === 'Ditolak' || $sampel['status'] === 'Ditolak MT');
$alasan_penolakan_list = [];
if (!empty($sesi_list)) {
    foreach ($sesi_list as $s_item) {
        if (in_array($s_item['status'], ['Ditolak', 'Ditolak MT', 'Ditolak Penyelia'], TRUE)) {
            $is_ditolak = TRUE;
        }
        if (!empty($s_item['alasan_penolakan'])) {
            $alasan_penolakan_list[] = $s_item['alasan_penolakan'];
        }
    }
}
?>

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
                    <a href="<?php echo site_url('pengujian/lhu/' . $sampel['id']); ?>" class="btn btn-primary btn-sm" target="_blank">
                        <i class="bi bi-printer me-1"></i> Cetak LHU Final
                    </a>
                    <?php if (!empty($is_mt)): ?>
                        <a href="<?php echo site_url('pengujian/riwayat_approval'); ?>" class="btn btn-success btn-sm">
                            <i class="bi bi-file-earmark-check me-1"></i> Ke Riwayat Approval
                        </a>
                    <?php else: ?>
                        <a href="<?php echo site_url('pengujian/riwayat'); ?>" class="btn btn-success btn-sm">
                            <i class="bi bi-clock-history me-1"></i> Ke Riwayat Pengujian
                        </a>
                    <?php endif; ?>
                <?php elseif ($is_ditolak && !empty($is_mt)): ?>
                    <a href="<?php echo site_url('pengujian/riwayat_approval'); ?>" class="btn btn-outline-danger btn-sm">
                        <i class="bi bi-file-earmark-check me-1"></i> Ke Riwayat Approval
                    </a>
                <?php endif; ?>
                <a href="<?php echo !empty($is_mt) ? site_url('pengujian/approval') : site_url('pengujian/riwayat'); ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> <?php echo !empty($is_mt) ? 'Kembali ke Antrean' : 'Kembali ke Riwayat'; ?>
                </a>
            </div>
        </div>

        <!-- Body -->
        <div class="tinjauan-body">

            <!-- Banner Status Kesiapan -->
            <?php if ($is_ditolak): ?>
                <div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
                    <i class="bi bi-x-circle-fill text-danger fs-4 me-3"></i>
                    <div>
                        <strong>Status LHU: Pengujian Ditolak</strong><br>
                        <small>Sampel atau sesi pengujian telah ditolak dan dikembalikan untuk perbaikan. Keputusan pengesahan dinonaktifkan.</small>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert <?php echo (!empty($kelengkapan['siap_mt']) ? 'alert-success' : 'alert-warning'); ?> d-flex align-items-center mb-4" role="alert">
                    <i class="bi <?php echo (!empty($kelengkapan['siap_mt']) ? 'bi-check-circle-fill text-success' : 'bi-exclamation-triangle-fill text-warning'); ?> fs-4 me-3"></i>
                    <div>
                        <strong>Status Kesiapan LHU MT:</strong> <?php echo html_escape($kelengkapan['status_agregat']); ?><br>
                        <small class="text-muted"><?php echo html_escape($kelengkapan['alasan']); ?></small>
                    </div>
                </div>
            <?php endif; ?>

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

            <!-- ========================================================================= -->
            <!-- TABEL LHU FINAL GABUNGAN (SATU SAMPEL SATU LHU RESMI)                     -->
            <!-- ========================================================================= -->
            <div class="card border-0 shadow-sm mb-4" style="border: 1px solid #cbd5e1 !important; border-radius: 9px; overflow: hidden;">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-file-earmark-ruled-fill text-primary fs-5"></i>
                        <div>
                            <h6 class="fw-bold mb-0 text-dark">Tabel LHU Final Gabungan (Satu Kode Sampel = Satu LHU)</h6>
                            <small class="text-muted">Data seluruh pengujian Kimia & Mikrobiologi digabung menjadi satu laporan hasil uji resmi untuk disahkan MT</small>
                        </div>
                    </div>
                    <div>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2">
                            <i class="bi bi-layers-fill me-1"></i> <?php echo count($sesi_list); ?> Sesi Digabung
                        </span>
                    </div>
                </div>

                <div class="card-body p-4">
                    <?php
                    // Kelompokkan seluruh sesi berdasarkan jenis_pengujian (Kimia & Mikrobiologi)
                    $sesi_by_jenis = [];
                    foreach ($sesi_list as $s) {
                        $j = !empty($s['jenis_pengujian']) ? trim($s['jenis_pengujian']) : 'Umum';
                        $sesi_by_jenis[$j][] = $s;
                    }

                    // Urutan: Kimia lebih dahulu, lalu Mikrobiologi
                    $urutan_jenis = ['Kimia', 'Mikrobiologi'];
                    foreach (array_keys($sesi_by_jenis) as $jk) {
                        if (!in_array($jk, $urutan_jenis, true)) {
                            $urutan_jenis[] = $jk;
                        }
                    }

                    $total_param_count = 0;
                    $ada_tms = false;
                    $param_belum_disimpulkan = [];
                    ?>

                    <?php foreach ($urutan_jenis as $jenis): ?>
                        <?php if (empty($sesi_by_jenis[$jenis])) continue; ?>
                        <?php
                            $judul_seksi = ($jenis === 'Mikrobiologi') ? 'PENGUJIAN MIKRO' : 'PENGUJIAN ' . strtoupper($jenis);
                            $badge_class = ($jenis === 'Mikrobiologi') ? 'bg-info' : 'bg-primary';
                        ?>

                        <div class="d-flex align-items-center gap-2 mt-3 mb-2">
                            <span class="badge <?php echo $badge_class; ?> px-2 py-1" style="font-size: .75rem;">
                                <?php echo html_escape($jenis); ?>
                            </span>
                            <span class="fw-bold text-dark" style="font-size: .95rem; letter-spacing: .5px;">
                                <?php echo html_escape($judul_seksi); ?>
                            </span>
                        </div>

                        <div class="table-responsive mb-4">
                            <table class="table table-bordered align-middle mb-0" style="font-size: .82rem; border-color: #cbd5e1;">
                                <thead style="background: #f8fafc;">
                                    <tr>
                                        <th colspan="6" class="py-2 px-3 fw-bold text-secondary" style="background: #f1f5f9; border-bottom: 2px solid #cbd5e1;">
                                            Hasil Pengujian Balai Regional :
                                        </th>
                                    </tr>
                                    <tr class="text-uppercase text-secondary" style="font-size: .74rem;">
                                        <th style="width: 24%;">Uji yang dilakukan</th>
                                        <th style="width: 22%;">Hasil</th>
                                        <th style="width: 20%;">Syarat</th>
                                        <th style="width: 12%;">Pustaka</th>
                                        <th style="width: 10%;">Metode</th>
                                        <th style="width: 12%;">Balai Penguji</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $ada_item_jenis = false;
                                    foreach ($sesi_by_jenis[$jenis] as $sesi_item):
                                        $forms = !empty($sesi_item['forms']) ? $sesi_item['forms'] : [];

                                        foreach ($forms as $f):
                                            $ada_item_jenis = true;
                                            $total_param_count++;

                                            $param_nama = !empty($f['parameter_uji']) ? $f['parameter_uji'] : (!empty($f['nama_template']) ? $f['nama_template'] : 'Parameter');
                                            $kesimpulan_item = !empty($f['kesimpulan']) ? $f['kesimpulan'] : '-';

                                            $is_ms = (stripos($kesimpulan_item, 'tidak memenuhi') === false && stripos($kesimpulan_item, 'memenuhi') !== false);
                                            $is_tms = (stripos($kesimpulan_item, 'tidak memenuhi') !== false);

                                            if ($is_tms) {
                                                $ada_tms = true;
                                                $badge_kes_class = 'danger';
                                                $tag_kes = 'TMS';
                                            } elseif ($is_ms) {
                                                $badge_kes_class = 'success';
                                                $tag_kes = 'MS';
                                            } else {
                                                $badge_kes_class = 'secondary';
                                                $tag_kes = $kesimpulan_item;
                                                // Catat form parameter yang belum disimpulkan
                                                $param_belum_disimpulkan[] = [
                                                    'nama'        => $param_nama,
                                                    'jenis'       => $jenis,
                                                    'sesi_id'     => $sesi_item['id'],
                                                    'kesimpulan'  => $kesimpulan_item
                                                ];
                                            }

                                            $pustaka_item = !empty($f['pustaka_acuan']) ? $f['pustaka_acuan'] : (!empty($f['pustaka_metode']) ? $f['pustaka_metode'] : '-');
                                            $metode_item  = !empty($f['metode_analisa']) ? $f['metode_analisa'] : (!empty($f['nama_metode']) ? $f['nama_metode'] : '-');
                                            $syarat_item  = !empty($f['persyaratan']) ? $f['persyaratan'] : '-';
                                            $balai_item   = !empty($sampel['balai_penguji']) ? 'Balai Besar POM di ' . ucwords(strtolower($sampel['balai_penguji'])) : 'Balai Besar POM di Bandar Lampung';

                                            // Ringkasan data hasil
                                            $raw_data = !empty($f['data_hasil']) ? json_decode($f['data_hasil'], true) : [];
                                            $ringkasan_hasil = [];
                                            if (is_array($raw_data)) {
                                                foreach ($raw_data as $k => $val) {
                                                    if (is_scalar($val) && $val !== '' && $k !== 'csrf_token') {
                                                        $ringkasan_hasil[] = html_escape($k) . ': ' . html_escape((string)$val);
                                                    }
                                                }
                                            }
                                            $teks_hasil = !empty($ringkasan_hasil) ? implode(', ', array_slice($ringkasan_hasil, 0, 2)) : '-';
                                    ?>
                                        <tr>
                                            <td>
                                                <strong class="text-dark"><?php echo html_escape($param_nama); ?></strong>
                                                <div class="text-muted" style="font-size: .70rem;">
                                                    Form: <?php echo html_escape(!empty($f['nama_template']) ? $f['nama_template'] : 'Form LCP'); ?> (Sesi #<?php echo $sesi_item['id']; ?>)
                                                </div>
                                            </td>
                                            <td>
                                                <div><?php echo $teks_hasil; ?></div>
                                                <span class="badge bg-<?php echo $badge_kes_class; ?>-subtle text-<?php echo $badge_kes_class; ?> border border-<?php echo $badge_kes_class; ?>-subtle mt-1" style="font-size: .70rem;">
                                                    <?php echo html_escape($kesimpulan_item); ?>
                                                </span>
                                            </td>
                                            <td><?php echo nl2br(html_escape($syarat_item)); ?></td>
                                            <td><?php echo html_escape($pustaka_item); ?></td>
                                            <td><?php echo html_escape($metode_item); ?></td>
                                            <td style="font-size: .74rem;"><?php echo html_escape($balai_item); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <?php endforeach; ?>

                                    <?php if (!$ada_item_jenis): ?>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-3">
                                                Belum ada form parameter pada pengujian <?php echo html_escape($jenis); ?>.
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endforeach; ?>

                    <?php if (empty($sesi_list)): ?>
                        <div class="alert alert-secondary text-center py-4">
                            Belum ada sesi pengujian yang tercatat untuk sampel ini.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Rincian CP / LCP Sesi Pengujian (Detail Analis & Penyelia) -->
            <div class="card border-0 shadow-sm mb-4" style="border: 1px solid #e2e8f0 !important; border-radius: 9px;">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <h6 class="fw-bold mb-0 text-secondary">
                        <i class="bi bi-collection me-1"></i> Rincian CP / LCP Sesi Pengujian & Tanda Tangan Digital
                    </h6>
                    <small class="text-muted">Klik Detail Sesi untuk melihat data pengamatan, foto, atau pustaka acuan tiap form</small>
                </div>
                <div class="card-body p-4">
                    <?php if (!empty($sesi_list)): ?>
                        <div class="row g-3">
                            <?php foreach ($sesi_list as $sesi): ?>
                                <div class="col-md-6">
                                    <div class="p-3 border rounded" style="background: #f8fafc; border-color: #e2e8f0 !important;">
                                        <div class="d-flex align-items-center justify-content-between mb-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="badge <?php echo ($sesi['jenis_pengujian'] === 'Mikrobiologi' ? 'bg-info text-white' : 'bg-primary text-white'); ?>">
                                                    <?php echo html_escape($sesi['jenis_pengujian']); ?>
                                                </span>
                                                <strong class="text-dark">Sesi #<?php echo $sesi['id']; ?></strong>
                                            </div>
                                            <span class="badge <?php echo ($sesi['status'] === 'Menunggu Approval' ? 'bg-success' : 'bg-warning text-dark'); ?>">
                                                <?php echo html_escape($sesi['status']); ?>
                                            </span>
                                        </div>

                                        <div class="small text-muted mb-2">
                                            <div><strong>Penguji:</strong> <?php echo html_escape(!empty($sesi['nama_penguji']) ? $sesi['nama_penguji'] : '-'); ?></div>
                                            <div><strong>Penyelia:</strong> <?php echo html_escape(!empty($sesi['nama_verifier']) ? $sesi['nama_verifier'] : '-'); ?></div>
                                            <div><strong>Jumlah Form:</strong> <?php echo count(!empty($sesi['forms']) ? $sesi['forms'] : []); ?> form LCP</div>
                                        </div>

                                        <div class="d-flex align-items-center justify-content-between pt-2 border-top">
                                            <span class="text-muted small">
                                                <?php echo !empty($sesi['kode_sesi']) ? html_escape($sesi['kode_sesi']) : 'SESI-' . $sesi['id']; ?>
                                            </span>
                                            <a href="<?php echo site_url('pengujian/detail_sesi/' . $sesi['id']); ?>" class="btn btn-sm btn-outline-primary py-0 px-2" style="font-size: 0.75rem;" target="_blank">
                                                <i class="bi bi-box-arrow-up-right me-1"></i>Detail Sesi & CP
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Panel Keputusan Manajer Teknis (MT) Tingkat Sampel -->
            <div class="card border-0 shadow-sm mt-4" style="background: #f8fafc; border: 1px solid #e2e8f0 !important; border-radius: 9px;">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-2 text-dark">
                        <i class="bi bi-shield-check me-1 text-primary"></i> Keputusan Manajer Teknis (Tingkat Sampel)
                    </h6>
                    <p class="text-muted small mb-3">
                        Persetujuan atau penolakan diproses sekaligus untuk seluruh sesi pengujian wajib sampel ini.
                    </p>

                    <?php 
                    // Periksa apakah sampel atau salah satu sesi telah ditolak (baik oleh MT maupun Penyelia)
                    $is_ditolak = ($sampel['status'] === 'Ditolak' || $sampel['status'] === 'Ditolak MT');
                    $alasan_penolakan_list = [];
                    if (!empty($sesi_list)) {
                        foreach ($sesi_list as $s_item) {
                            if (in_array($s_item['status'], ['Ditolak', 'Ditolak MT', 'Ditolak Penyelia'], TRUE)) {
                                $is_ditolak = TRUE;
                            }
                            if (!empty($s_item['alasan_penolakan'])) {
                                $alasan_penolakan_list[] = $s_item['alasan_penolakan'];
                            }
                        }
                    }
                    ?>

                    <?php if ($sampel['status'] === 'Approved / Final'): ?>
                        <div class="d-flex align-items-center justify-content-between p-3 rounded border border-success flex-wrap gap-2" style="background: #f0fdf4;">
                            <div class="text-success small">
                                <i class="bi bi-patch-check-fill me-1 fs-6"></i>
                                <strong>LHU Sampel Telah Disahkan:</strong> Seluruh sesi pengujian telah disetujui Manajer Teknis dan berkas LHU PDF final resmi telah diterbitkan. Laporan tersimpan di <strong>Riwayat Approval</strong>.
                            </div>
                            <div class="d-flex gap-2">
                                <a href="<?php echo site_url('pengujian/lhu/' . $sampel['id']); ?>" class="btn btn-primary btn-sm px-3" target="_blank">
                                    <i class="bi bi-printer me-1"></i> Cetak LHU Final
                                </a>
                                <?php if (!empty($is_mt)): ?>
                                    <a href="<?php echo site_url('pengujian/riwayat_approval'); ?>" class="btn btn-success btn-sm px-3">
                                        <i class="bi bi-file-earmark-check me-1"></i> Buka Riwayat Approval
                                    </a>
                                <?php else: ?>
                                    <a href="<?php echo site_url('pengujian/riwayat'); ?>" class="btn btn-success btn-sm px-3">
                                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Riwayat
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php elseif ($is_ditolak): ?>
                        <div class="p-3 rounded border border-danger-subtle" style="background: #fef2f2;">
                            <div class="d-flex align-items-start justify-content-between flex-wrap gap-3">
                                <div>
                                    <div class="text-danger fw-bold mb-1">
                                        <i class="bi bi-x-circle-fill me-1 fs-6"></i>
                                        Pengujian Sampel Telah Ditolak
                                    </div>
                                    <p class="text-muted small mb-2">
                                        Sampel ini telah ditolak oleh Manajer Teknis / Penyelia dan telah dikembalikan ke analis/penyelia terkait untuk dilakukan perbaikan atau pengujian ulang. Tidak ada tindakan persetujuan lebih lanjut yang dapat dilakukan saat ini.
                                    </p>
                                    <?php if (!empty($alasan_penolakan_list)): ?>
                                        <div class="p-2 rounded bg-white border border-danger-subtle small mt-2">
                                            <div class="fw-bold text-danger mb-1"><i class="bi bi-chat-left-text me-1"></i>Catatan / Alasan Penolakan:</div>
                                            <div class="text-secondary" style="white-space: pre-line;"><?php echo html_escape(implode("\n---\n", array_unique($alasan_penolakan_list))); ?></div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="d-flex gap-2 align-self-start">
                                    <?php if (!empty($is_mt)): ?>
                                        <a href="<?php echo site_url('pengujian/riwayat_approval'); ?>" class="btn btn-outline-danger btn-sm px-3">
                                            <i class="bi bi-file-earmark-check me-1"></i> Riwayat Approval
                                        </a>
                                        <a href="<?php echo site_url('pengujian/approval'); ?>" class="btn btn-secondary btn-sm px-3">
                                            <i class="bi bi-arrow-left me-1"></i> Kembali ke Antrean
                                        </a>
                                    <?php else: ?>
                                        <a href="<?php echo site_url('pengujian/riwayat'); ?>" class="btn btn-secondary btn-sm px-3">
                                            <i class="bi bi-arrow-left me-1"></i> Kembali ke Riwayat
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php elseif (!empty($kelengkapan['siap_mt']) && $this->Model_Hak_Akses->memiliki_akses($this->session->userdata('role_id'), 'pengujian_approve')): ?>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <!-- Form Setujui Sampel -->
                            <form id="formApproveSampel"
                                  action="<?php echo site_url('pengujian/approve_sampel/' . $sampel['id']); ?>"
                                  method="post"
                                  class="d-inline">
                                <input type="hidden"
                                       name="<?php echo $this->security->get_csrf_token_name(); ?>"
                                       value="<?php echo $this->security->get_csrf_hash(); ?>">

                                <?php if (!empty($param_belum_disimpulkan)): ?>
                                    <!-- Jika ada parameter yang belum disimpulkan, tombol buka pop-up peringatan terlebih dahulu -->
                                    <button type="button" class="btn btn-success px-4" data-bs-toggle="modal" data-bs-target="#modalPeringatanBelumDisimpulkan">
                                        <i class="bi bi-check-circle me-1"></i> Setujui Seluruh Hasil Uji Sampel
                                    </button>
                                <?php else: ?>
                                    <!-- Jika seluruh parameter sudah ada hasil (MS/TMS), alur standar langsung konfirmasi -->
                                    <button type="submit" class="btn btn-success px-4" onclick="return confirm('Apakah Anda yakin ingin menyetujui seluruh hasil pengujian untuk sampel ini? LHU PDF final resmi akan diterbitkan dengan tanda tangan digital.');">
                                        <i class="bi bi-check-circle me-1"></i> Setujui Seluruh Hasil Uji Sampel
                                    </button>
                                <?php endif; ?>
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

<?php if (!empty($param_belum_disimpulkan)): ?>
<!-- Modal Peringatan: Ada Pengujian yang Belum Disimpulkan -->
<div class="modal fade" id="modalPeringatanBelumDisimpulkan" tabindex="-1" aria-labelledby="modalPeringatanBelumDisimpulkanLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-warning-subtle text-warning-emphasis border-bottom-0">
                <h5 class="modal-title fw-bold fs-6" id="modalPeringatanBelumDisimpulkanLabel">
                    <i class="bi bi-exclamation-triangle-fill text-warning me-2 fs-5"></i>
                    Peringatan: Hasil Uji Belum Disimpulkan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body py-3">
                <div class="alert alert-warning border border-warning-subtle small mb-3">
                    <i class="bi bi-info-circle-fill me-1"></i>
                    Terdapat <strong><?php echo count($param_belum_disimpulkan); ?> parameter pengujian</strong> yang kesimpulannya masih <strong>"Belum Disimpulkan"</strong> (bukan Memenuhi Syarat [MS] / Tidak Memenuhi Syarat [TMS]).
                </div>

                <p class="small text-muted mb-2">Daftar parameter yang belum disimpulkan:</p>
                <div class="list-group list-group-flush border rounded mb-3" style="max-height: 180px; overflow-y: auto;">
                    <?php foreach ($param_belum_disimpulkan as $pbd): ?>
                        <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-2 px-3 small">
                            <div>
                                <strong class="text-dark"><?php echo html_escape($pbd['nama']); ?></strong>
                                <div class="text-muted" style="font-size: 0.72rem;">
                                    <?php echo html_escape($pbd['jenis']); ?> (Sesi #<?php echo $pbd['sesi_id']; ?>)
                                </div>
                            </div>
                            <span class="badge bg-secondary-subtle text-secondary border">
                                <?php echo html_escape($pbd['kesimpulan']); ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>

                <p class="small text-dark fw-semibold mb-0">
                    Apakah Anda sebagai Manajer Teknis (MT) ingin <u>tetap menyetujui</u> dan mengesahkan seluruh hasil pengujian sampel ini?
                </p>
                <small class="text-muted" style="font-size: 0.75rem;">
                    *Jika disetujui, LHU PDF final resmi akan tetap diterbitkan dengan tanda tangan digital Anda.
                </small>
            </div>

            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-light btn-sm px-3" data-bs-dismiss="modal">
                    Batal / Tinjau Kembali
                </button>
                <button type="button" class="btn btn-success btn-sm px-3" id="btnKonfirmasiTetapSetujui">
                    <i class="bi bi-check-circle me-1"></i> Ya, Tetap Setujui Laporan
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnTetapSetujui = document.getElementById('btnKonfirmasiTetapSetujui');
    const formApprove = document.getElementById('formApproveSampel');

    if (btnTetapSetujui && formApprove) {
        btnTetapSetujui.addEventListener('click', function() {
            btnTetapSetujui.disabled = true;
            btnTetapSetujui.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Memproses Approval...';
            formApprove.submit();
        });
    }
});
</script>
<?php endif; ?>


