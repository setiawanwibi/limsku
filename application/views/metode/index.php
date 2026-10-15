<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
    .metode-page { width: 100%; padding: 4px 0 24px; color: #294257; }
    .metode-card { width: 100%; background: #ffffff; border: 1px solid #e0e8ed; border-radius: 12px; box-shadow: 0 4px 16px rgba(41, 66, 87, 0.05); overflow: hidden; }
    .metode-header { display: flex; align-items: center; justify-content: space-between; gap: 16px; padding: 18px 24px; background: #ffffff; border-bottom: 1px solid #e8eef1; }
    .metode-heading { display: flex; align-items: center; gap: 12px; }
    .metode-heading-icon { width: 42px; height: 42px; border-radius: 10px; background: #e6f7ff; color: #1890ff; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0; }
    .metode-title { margin: 0; color: #294257; font-size: 1.1rem; font-weight: 700; }
    .metode-subtitle { margin: 4px 0 0; color: #7b8b98; font-size: 0.82rem; }
    .btn-import-excel { display: inline-flex; align-items: center; gap: 8px; padding: 9px 16px; background: #ffffff; color: #2b6cb0; border: 1px solid #cbd5e0; border-radius: 8px; font-size: 0.84rem; font-weight: 600; text-decoration: none; transition: all 0.2s; }
    .btn-import-excel:hover { background: #ebf8ff; border-color: #3182ce; color: #2b6cb0; }
    .btn-add-metode { display: inline-flex; align-items: center; gap: 8px; padding: 9px 16px; background: #1890ff; color: #ffffff; border: 1px solid #1890ff; border-radius: 8px; font-size: 0.84rem; font-weight: 600; text-decoration: none; box-shadow: 0 2px 6px rgba(24, 144, 255, 0.2); }
    .btn-add-metode:hover { background: #096dd9; color: #ffffff; }
    .metode-body { padding: 20px 24px; }
    .metode-table thead th { background: #f7fafc; color: #40596d; font-size: 0.76rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; padding: 12px 14px; border-bottom: 1px solid #e0e8ed; white-space: nowrap; }
    .metode-table tbody td { padding: 12px 14px; font-size: 0.83rem; color: #334e68; border-bottom: 1px solid #edf2f5; vertical-align: middle; }
    .badge-lcp-mapped { background: #e6ffed; color: #22863a; border: 1px solid #b7eb8f; padding: 3px 8px; border-radius: 6px; font-size: 0.74rem; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; }
    .badge-lcp-unmapped { background: #fffbe6; color: #d48806; border: 1px solid #ffe58f; padding: 3px 8px; border-radius: 6px; font-size: 0.74rem; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; }
    .action-btn-group { display: inline-flex; gap: 5px; }
    .action-btn { display: inline-flex; align-items: center; justify-content: center; width: 30px; height: 30px; border-radius: 6px; font-size: 0.82rem; border: 1px solid transparent; }
</style>

<div class="metode-page">

    <!-- Flash Messages -->
    <?php if ($this->session->flashdata('pesan_sukses')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            <?php echo html_escape($this->session->flashdata('pesan_sukses')); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('pesan_gagal')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <?php echo html_escape($this->session->flashdata('pesan_gagal')); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="metode-card">
        <div class="metode-header">
            <div class="metode-heading">
                <div class="metode-heading-icon">
                    <i class="bi bi-journal-bookmark"></i>
                </div>
                <div>
                    <h5 class="metode-title">Master Metode Pengujian (Monografi Obat)</h5>
                    <p class="metode-subtitle">
                        Kelola data monografi zat aktif, parameter uji kritis, spesifikasi, dan pemetaan ke Lembar Catatan Pengujian (LCP).
                    </p>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                <a href="<?php echo site_url('metode/automap'); ?>" class="btn btn-outline-primary d-inline-flex align-items-center gap-2 fw-semibold px-3 py-2" style="border-radius: 8px; font-size: 0.84rem;">
                    <i class="bi bi-diagram-3-fill text-primary"></i>
                    Auto-Mapping LCP
                </a>
                <a href="<?php echo site_url('metode/import'); ?>" class="btn-import-excel">
                    <i class="bi bi-file-earmark-excel"></i>
                    Import Excel
                </a>
                <a href="<?php echo site_url('metode/tambah'); ?>" class="btn-add-metode">
                    <i class="bi bi-plus-lg"></i>
                    Tambah Metode
                </a>
            </div>
        </div>

        <div class="metode-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle metode-table" id="tabel-metode">
                    <thead>
                        <tr>
                            <th width="3%">NO</th>
                            <th>NAMA ZAT AKTIF</th>
                            <th>JENIS SEDIAAN</th>
                            <th>BENTUK SEDIAAN</th>
                            <th>PUSTAKA ACUAN</th>
                            <th>PARAMETER UJI KRITIS SESUAI MONOGRAFI</th>
                            <th>METODE ANALISA</th>
                            <th>PERSYARATAN</th>
                            <th>BIDANG PENGUJIAN</th>
                            <th>KELAS TERAPI</th>
                            <th width="10%">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($daftar_metode)): ?>
                            <tr>
                                <td colspan="11" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox fs-3 d-block mb-2 text-muted"></i>
                                    Belum ada data monografi metode. Gunakan tombol <strong>Import Excel</strong> untuk mengunggah ribuan baris data monografi secara instan.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php $no = 1; foreach ($daftar_metode as $m): ?>
                                <tr>
                                    <td class="text-center"><?php echo $no++; ?></td>
                                    <td>
                                        <strong class="text-dark d-block"><?php echo html_escape($m['nama_zat_aktif'] ?: $m['nama_metode']); ?></strong>
                                        <small class="text-muted"><code><?php echo html_escape($m['kode_metode']); ?></code></small>
                                    </td>
                                    <td><?php echo html_escape($m['jenis_sediaan'] ?: '-'); ?></td>
                                    <td><?php echo html_escape($m['bentuk_sediaan'] ?: '-'); ?></td>
                                    <td><?php echo html_escape($m['pustaka_acuan'] ?: '-'); ?></td>
                                    <td>
                                        <span class="fw-semibold text-primary d-block"><?php echo html_escape($m['parameter_uji'] ?: '-'); ?></span>
                                        <?php if (!empty($m['template_id'])): ?>
                                            <span class="badge-lcp-mapped mt-1" title="<?php echo html_escape($m['nama_template']); ?>">
                                                <i class="bi bi-check-circle-fill"></i> LCP: <?php echo html_escape($m['kode_template']); ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="badge-lcp-unmapped mt-1" role="button" data-bs-toggle="modal" data-bs-target="#modalMapLcp<?php echo $m['id']; ?>">
                                                <i class="bi bi-exclamation-triangle-fill"></i> Belum Dipetakan LCP
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo html_escape($m['metode_analisa'] ?: '-'); ?></td>
                                    <td><small class="text-secondary"><?php echo html_escape($m['persyaratan'] ?: '-'); ?></small></td>
                                    <td>
                                        <span class="badge bg-<?php echo ($m['bidang_pengujian'] === 'Kimia' ? 'info text-dark' : 'success'); ?>">
                                            <?php echo html_escape($m['bidang_pengujian']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <small class="fw-semibold text-dark"><?php echo html_escape($m['nama_kelas_terapi'] ?: '-'); ?></small>
                                    </td>
                                    <td>
                                        <div class="action-btn-group">
                                            <button type="button" class="action-btn btn-sm btn-outline-primary" title="Petakan LCP" data-bs-toggle="modal" data-bs-target="#modalMapLcp<?php echo $m['id']; ?>">
                                                <i class="bi bi-link-45deg"></i>
                                            </button>
                                            <a href="<?php echo site_url('metode/edit/' . $m['id']); ?>" class="action-btn btn-sm btn-outline-secondary" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="<?php echo site_url('metode/status/' . $m['id']); ?>" class="action-btn btn-sm btn-outline-warning" title="Ubah Status">
                                                <i class="bi bi-power"></i>
                                            </a>
                                            <a href="<?php echo site_url('metode/hapus/' . $m['id']); ?>" class="action-btn btn-sm btn-outline-danger" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus metode ini?');">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </div>

                                        <!-- Modal Pemetaan LCP -->
                                        <div class="modal fade" id="modalMapLcp<?php echo $m['id']; ?>" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content border-0 shadow">
                                                    <?php echo form_open('metode/mapping/' . $m['id']); ?>
                                                        <div class="modal-header border-bottom">
                                                            <h6 class="modal-title fw-bold">Petakan Parameter ke Lembar Catatan Pengujian (LCP)</h6>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body py-3">
                                                            <div class="mb-3">
                                                                <small class="text-muted d-block">Nama Parameter Uji:</small>
                                                                <strong><?php echo html_escape($m['parameter_uji']); ?></strong> (Zat: <?php echo html_escape($m['nama_zat_aktif']); ?>)
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="template_id_<?php echo $m['id']; ?>" class="form-label small fw-bold">Pilih Form LCP Resmi:</label>
                                                                <select name="template_id" id="template_id_<?php echo $m['id']; ?>" class="form-select form-select-sm">
                                                                    <option value="">-- Tandai Belum Dipetakan --</option>
                                                                    <?php foreach ($daftar_lcp as $lcp): ?>
                                                                        <?php if ($lcp['kategori'] === $m['bidang_pengujian']): ?>
                                                                            <option value="<?php echo $lcp['id']; ?>" <?php echo ($m['template_id'] == $lcp['id']) ? 'selected' : ''; ?>>
                                                                                [<?php echo html_escape($lcp['kode_template']); ?>] <?php echo html_escape($lcp['nama_template']); ?>
                                                                            </option>
                                                                        <?php endif; ?>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                                <small class="text-muted d-block mt-1">Pemetaan ini harus eksplisit dan diverifikasi oleh analis laboratorium.</small>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer border-top">
                                                            <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">Tutup</button>
                                                            <button type="submit" class="btn btn-sm btn-primary">Simpan Pemetaan LCP</button>
                                                        </div>
                                                    <?php echo form_close(); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof jQuery !== 'undefined' && $.fn.DataTable) {
            $('#tabel-metode').DataTable({
                language: {
                    search: "Cari Monografi:",
                    lengthMenu: "Tampilkan _MENU_ baris",
                    info: "Menampilkan _START_ s/d _END_ dari _TOTAL_ data monografi",
                    infoEmpty: "Menampilkan 0 data",
                    zeroRecords: "Tidak ada monografi yang cocok",
                    paginate: { first: "Awal", last: "Akhir", next: "Berikutnya", previous: "Sebelumnya" }
                },
                columnDefs: [ { orderable: false, targets: [0, 10] } ],
                order: [[1, 'asc'], [5, 'asc']],
                pageLength: 15,
                lengthMenu: [[15, 30, 50, 100, -1], [15, 30, 50, 100, "Semua"]],
                responsive: true
            });
        }
    });
</script>