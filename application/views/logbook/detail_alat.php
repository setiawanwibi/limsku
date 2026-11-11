<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style>
    .logbook-alat-page {
        color: #1e293b;
        font-size: 0.88rem;
    }
    .instrument-card-box {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 22px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.03);
    }
    .logbook-detail-table th {
        background-color: #f8fafc;
        color: #475569;
        font-weight: 700;
        font-size: 0.76rem;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        border-bottom: 2px solid #e2e8f0;
        padding: 14px 16px;
        white-space: nowrap;
    }
    .logbook-detail-table td {
        font-size: 0.85rem;
        vertical-align: middle;
        padding: 14px 16px;
        border-bottom: 1px solid #f1f5f9;
    }
    .logbook-detail-table tbody tr {
        transition: background-color 0.15s ease-in-out;
    }
    .logbook-detail-table tbody tr:hover {
        background-color: #f8fafc;
    }
    .avatar-user-badge-sm {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.80rem;
        flex-shrink: 0;
        background: #e0e7ff;
        color: #4338ca;
        border: 1px solid #c7d2fe;
    }
    .index-pill {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background: #f1f5f9;
        color: #64748b;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.76rem;
        font-weight: 700;
    }
    .badge-ruangan-pill {
        background: #eff6ff;
        color: #1e40af;
        border: 1px solid #bfdbfe;
        font-size: 0.74rem;
        padding: 4px 10px;
        border-radius: 50rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
    }
    .badge-serial {
        background: #f8fafc;
        color: #0f172a;
        border: 1px solid #cbd5e1;
        font-family: 'JetBrains Mono', Consolas, SFMono-Regular, monospace;
        font-size: 0.78rem;
        font-weight: 600;
        padding: 3px 8px;
        border-radius: 6px;
    }
</style>

<div class="logbook-alat-page">

    <!-- Bar Navigasi Atas: Kembali (Kiri) & Cetak Lembar Logbook (Kanan) -->
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
        <a href="<?php echo site_url('logbook'); ?>" class="btn btn-outline-secondary btn-sm py-1 px-3 rounded-pill d-inline-flex align-items-center gap-1 shadow-sm fw-semibold" title="Kembali ke Daftar Logbook">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali ke Logbook</span>
        </a>

        <a href="<?php echo site_url('logbook/cetak?instrument_id=' . $alat['id']); ?>" 
           target="_blank" 
           class="btn btn-outline-primary btn-sm px-3 shadow-sm rounded-pill d-inline-flex align-items-center gap-2 fw-semibold">
            <i class="bi bi-printer-fill"></i>
            <span>Cetak Lembar Logbook Ini</span>
        </a>
    </div>

    <!-- Spesifikasi Instrumen (Card Detail) -->
    <div class="instrument-card-box shadow-sm mb-4">
        <div class="row g-3">
            <div class="col-md-3">
                <div class="text-muted small fw-semibold text-uppercase" style="font-size: 0.70rem; letter-spacing: 0.5px;">Kode Inventaris / BMN</div>
                <div class="fw-bold text-primary fs-6 font-monospace mt-1">
                    <i class="bi bi-hash me-1"></i><?php echo html_escape($alat['kode_barang']); ?>
                </div>
            </div>
            <div class="col-md-3">
                <div class="text-muted small fw-semibold text-uppercase" style="font-size: 0.70rem; letter-spacing: 0.5px;">Nomor Seri Pabrikan (SN)</div>
                <div class="mt-1">
                    <span class="badge-serial">
                        <i class="bi bi-upc-scan me-1 text-muted"></i><?php echo html_escape(!empty($alat['nomor_seri']) ? $alat['nomor_seri'] : '-'); ?>
                    </span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="text-muted small fw-semibold text-uppercase" style="font-size: 0.70rem; letter-spacing: 0.5px;">Merk / Tipe Model</div>
                <div class="fw-bold text-dark fs-6 mt-1">
                    <i class="bi bi-cpu me-1 text-secondary"></i><?php echo html_escape($alat['merk_tipe'] ?: '-'); ?>
                </div>
            </div>
            <div class="col-md-3">
                <div class="text-muted small fw-semibold text-uppercase" style="font-size: 0.70rem; letter-spacing: 0.5px;">Lokasi Ruangan Penempatan</div>
                <div class="mt-1">
                    <span class="badge-ruangan-pill">
                        <i class="bi bi-geo-alt-fill me-1 text-primary"></i><?php echo html_escape($alat['nama_ruangan'] ?: '-'); ?>
                    </span>
                </div>
            </div>

            <div class="col-md-3">
                <div class="text-muted small fw-semibold text-uppercase" style="font-size: 0.70rem; letter-spacing: 0.5px;">Perangkat Lunak (Software)</div>
                <div class="text-secondary small mt-1"><?php echo html_escape($alat['perangkat_lunak'] ?: '-'); ?></div>
            </div>
            <div class="col-md-3">
                <div class="text-muted small fw-semibold text-uppercase" style="font-size: 0.70rem; letter-spacing: 0.5px;">Detektor / Komponen Kunci</div>
                <div class="text-secondary small mt-1"><?php echo html_escape($alat['detektor'] ?: '-'); ?></div>
            </div>
            <div class="col-md-3">
                <div class="text-muted small fw-semibold text-uppercase" style="font-size: 0.70rem; letter-spacing: 0.5px;">Kondisi Alat Saat Ini</div>
                <div class="mt-1">
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded-pill small">
                        <i class="bi bi-check-circle me-1"></i><?php echo html_escape($alat['kondisi_adm'] ?: 'Baik / Operasional'); ?>
                    </span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="text-muted small fw-semibold text-uppercase" style="font-size: 0.70rem; letter-spacing: 0.5px;">Total Riwayat Penggunaan</div>
                <div class="mt-1">
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1 rounded-pill fw-bold">
                        <i class="bi bi-bar-chart-fill me-1"></i><?php echo count($daftar_logbook); ?> Kali Penggunaan
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Rentang Tanggal Khusus Alat Ini -->
    <div class="card border-0 shadow-sm mb-4" style="border: 1px solid #e2e8f0 !important; border-radius: 12px;">
        <div class="card-body p-3">
            <form method="get" action="<?php echo site_url('logbook/alat/' . $alat['id']); ?>" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-secondary mb-1">
                        <i class="bi bi-calendar3 me-1 text-primary"></i> Dari Tanggal
                    </label>
                    <input type="date" class="form-control form-control-sm" name="tanggal_mulai" value="<?php echo html_escape(!empty($filter['tanggal_mulai']) ? $filter['tanggal_mulai'] : ''); ?>">
                </div>

                <div class="col-md-3">
                    <label class="form-label small fw-bold text-secondary mb-1">
                        <i class="bi bi-calendar3 me-1 text-primary"></i> Sampai Tanggal
                    </label>
                    <input type="date" class="form-control form-control-sm" name="tanggal_selesai" value="<?php echo html_escape(!empty($filter['tanggal_selesai']) ? $filter['tanggal_selesai'] : ''); ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label small fw-bold text-secondary mb-1">
                        <i class="bi bi-search me-1 text-primary"></i> Cari Keterangan / Sampel / Analis
                    </label>
                    <input type="text" class="form-control form-control-sm" name="q" placeholder="Kode sampel, nama analis, parameter..." value="<?php echo html_escape(!empty($filter['q']) ? $filter['q'] : ''); ?>">
                </div>

                <div class="col-md-2 d-flex gap-1">
                    <button type="submit" class="btn btn-primary btn-sm w-100 fw-semibold">
                        <i class="bi bi-filter me-1"></i> Filter
                    </button>
                    <a href="<?php echo site_url('logbook/alat/' . $alat['id']); ?>" class="btn btn-outline-secondary btn-sm" title="Reset Filter">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Buku Log Khusus Alat Ini -->
    <div class="card border-0 shadow-sm" style="border: 1px solid #e2e8f0 !important; border-radius: 12px; overflow: hidden;">
        <div class="card-header bg-white py-3 px-4 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <div class="bg-primary-subtle text-primary p-2 rounded-2 d-inline-flex">
                    <i class="bi bi-journal-text"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0 text-dark">Lembar Riwayat Penggunaan Instrumen</h6>
                    <small class="text-muted">Diurutkan kronologis dari pemakaian paling mutakhir</small>
                </div>
            </div>
            <span class="badge bg-secondary-subtle text-secondary border px-3 py-1 rounded-pill fw-semibold">
                Total: <?php echo count($daftar_logbook); ?> Catatan
            </span>
        </div>

        <div class="table-responsive">
            <table class="table align-middle mb-0 logbook-detail-table">
                <thead>
                    <tr>
                        <th class="ps-4" style="width: 5%;">No</th>
                        <th style="width: 18%;">Tanggal &amp; Waktu</th>
                        <th style="width: 25%;">Keterangan &amp; Parameter Uji</th>
                        <th style="width: 20%;">Nama &amp; Akun Pengguna</th>
                        <th style="width: 22%;">Sampel yang Diuji</th>
                        <th class="pe-4 text-center" style="width: 10%;">Tautan Sesi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($daftar_logbook)): ?>
                        <?php $no = 1; foreach ($daftar_logbook as $r): ?>
                            <tr>
                                <td class="ps-4">
                                    <span class="index-pill"><?php echo $no++; ?></span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="text-primary fs-5">
                                            <i class="bi bi-calendar-event"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark" style="font-size: 0.84rem;">
                                                <?php echo date('d M Y', strtotime($r['tanggal_penggunaan'])); ?>
                                            </div>
                                            <div class="text-muted small" style="font-size: 0.72rem;">
                                                <i class="bi bi-clock me-1"></i>Pukul <?php echo date('H:i', strtotime($r['waktu_penggunaan'])); ?> WIB
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-dark small fw-semibold" style="line-height: 1.4;">
                                        <?php echo html_escape($r['parameter_uji'] ?: ($r['keterangan'] ?: '-')); ?>
                                    </div>
                                    <div class="d-flex align-items-center gap-1 mt-1 flex-wrap">
                                        <?php if (!empty($r['jenis_pengujian'])): ?>
                                            <span class="badge bg-light text-secondary border px-2 py-0" style="font-size: 0.68rem;">
                                                <?php echo html_escape($r['jenis_pengujian']); ?>
                                            </span>
                                        <?php endif; ?>
                                        <?php if (!empty($r['kesimpulan']) && $r['kesimpulan'] !== 'Belum Disimpulkan'): ?>
                                            <?php $is_ms = in_array(strtoupper(trim($r['kesimpulan'])), array('MS', 'MEMENUHI SYARAT')); ?>
                                            <span class="badge <?php echo $is_ms ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-danger-subtle text-danger border border-danger-subtle'; ?> px-2 py-0" style="font-size: 0.68rem;">
                                                <?php echo html_escape($r['kesimpulan']); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-user-badge-sm">
                                            <?php 
                                                $nama_u = !empty($r['nama_pengguna']) ? $r['nama_pengguna'] : ($r['username'] ?: 'P');
                                                echo strtoupper(substr(trim($nama_u), 0, 1));
                                            ?>
                                        </div>
                                        <div class="d-flex flex-column" style="min-width: 0;">
                                            <span class="fw-bold text-dark text-truncate" style="font-size: 0.82rem; max-width: 150px;" title="<?php echo html_escape($nama_u); ?>">
                                                <?php echo html_escape($nama_u); ?>
                                            </span>
                                            <span class="text-muted small text-truncate" style="font-size: 0.70rem; max-width: 150px;">
                                                <?php echo !empty($r['nip_pengguna']) ? 'NIP: ' . html_escape($r['nip_pengguna']) : '@' . html_escape($r['username']); ?>
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column" style="max-width: 200px;">
                                        <a href="<?php echo site_url('sampel/detail/' . $r['sample_id']); ?>" 
                                           class="fw-bold text-primary text-decoration-none font-monospace d-inline-flex align-items-center gap-1" 
                                           target="_blank" 
                                           style="font-size: 0.82rem;"
                                           title="Buka Detail Sampel">
                                            <i class="bi bi-box-seam"></i>
                                            <span><?php echo html_escape($r['kode_sampel_manual'] ?: ($r['no_sipt'] ?: 'SMP-' . $r['sample_id'])); ?></span>
                                        </a>
                                        <small class="text-muted text-truncate mt-1" style="font-size: 0.72rem;" title="<?php echo html_escape($r['nama_sampel']); ?>">
                                            <?php echo html_escape($r['nama_sampel']); ?>
                                        </small>
                                    </div>
                                </td>
                                <td class="pe-4 text-center">
                                    <?php if (!empty($r['session_id'])): ?>
                                        <a href="<?php echo site_url('pengujian/detail_sesi/' . $r['session_id']); ?>" 
                                           class="btn btn-xs btn-outline-primary py-1 px-2 rounded-pill fw-semibold shadow-sm" 
                                           style="font-size: 0.72rem;" 
                                           target="_blank">
                                            <i class="bi bi-box-arrow-up-right me-1"></i>#<?php echo $r['session_id']; ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="py-4">
                                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px;">
                                        <i class="bi bi-journal-x fs-1 text-muted"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1">Belum Ada Riwayat Penggunaan</h6>
                                    <p class="text-muted small mb-0" style="max-width: 400px; margin: 0 auto;">
                                        Instrumen ini belum pernah digunakan dalam pengujian sampel yang dikirim ke penyelia.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
