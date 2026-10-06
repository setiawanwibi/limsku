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

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
        <h5 class="fw-bold mb-0 text-dark">Riwayat Hasil Pengujian Laboratorium</h5>
        <a href="<?php echo site_url('pengujian/antrean'); ?>" class="btn btn-primary btn-sm fw-semibold">
            + Antrean Pengujian
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="tabel-riwayat" class="table table-hover align-middle w-100">
                <thead class="table-light">
                    <tr>
                        <th width="5%">#</th>
                        <th>Kode Sampel</th>
                        <th>Nama Sampel</th>
                        <th>Jenis &amp; Form Template</th>
                        <th>Penguji</th>
                        <th>Kesimpulan</th>
                        <th>Status</th>
                        <th width="20%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($daftar_hasil)): ?>
                        <?php $no = 1; foreach ($daftar_hasil as $h): ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><strong class="text-primary font-monospace"><?php echo html_escape($h['kode_sampel_manual'] ?: ($h['no_sampel'] ? 'NO-' . $h['no_sampel'] : 'SMP-' . $h['sample_id'])); ?></strong></td>
                                <td><strong class="text-dark"><?php echo html_escape($h['nama_sampel']); ?></strong></td>
                                <td>
                                    <?php if (!empty($h['jenis_pengujian'])): ?>
                                        <span class="badge bg-light text-dark border mb-1"><?php echo html_escape($h['jenis_pengujian']); ?></span><br>
                                    <?php endif; ?>
                                    <small class="text-muted"><?php echo html_escape($h['nama_template'] ?: '-'); ?></small>
                                </td>
                                <td><small class="text-muted"><?php echo html_escape($h['nama_penguji'] ?: '-'); ?></small></td>
                                <td>
                                    <?php 
                                    $kes = $h['kesimpulan'];
                                    $b_cls = 'bg-secondary';
                                    if (stripos($kes, 'memenuhi') !== false && stripos($kes, 'tidak') === false) {
                                        $b_cls = 'bg-success';
                                    } elseif (stripos($kes, 'tidak memenuhi') !== false) {
                                        $b_cls = 'bg-danger';
                                    }
                                    ?>
                                    <span class="badge <?php echo $b_cls; ?> px-2 py-1"><?php echo html_escape($kes ?: 'Belum Disimpulkan'); ?></span>
                                </td>
                                <td>
                                    <?php
                                    $st = $h['status'];
                                    $st_cls = 'bg-secondary';
                                    if ($st === 'Sedang Diuji') $st_cls = 'bg-warning text-dark';
                                    elseif ($st === 'Menunggu Verifikasi') $st_cls = 'bg-info text-dark';
                                    elseif ($st === 'Menunggu Approval') $st_cls = 'bg-primary';
                                    elseif ($st === 'Approved / Final') $st_cls = 'bg-success';
                                    elseif ($st === 'Ditolak') $st_cls = 'bg-danger';
                                    ?>
                                    <span class="badge <?php echo $st_cls; ?>"><?php echo html_escape($st); ?></span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <?php if (!empty($h['jenis_pengujian'])): ?>
                                            <a href="<?php echo site_url('pengujian/detail_sesi/' . $h['id']); ?>" class="btn btn-outline-primary" title="Detail Sesi">Detail Sesi</a>
                                            <?php if ($h['status'] === 'Ditolak' && $h['penguji_id'] == $this->session->userdata('user_id')): ?>
                                                <a href="<?php echo site_url('pengujian/revisi_sesi/' . $h['id']); ?>" class="btn btn-warning fw-bold" title="Revisi Hasil">
                                                    <i class="bi bi-pencil-square me-1"></i> Revisi Hasil
                                                </a>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <a href="<?php echo site_url('pengujian/detail/' . $h['id']); ?>" class="btn btn-outline-primary" title="Detail Result">Detail</a>
                                            <?php if ($h['status'] === 'Ditolak' && $h['penguji_id'] == $this->session->userdata('user_id')): ?>
                                                <a href="<?php echo site_url('pengujian/revisi/' . $h['id']); ?>" class="btn btn-warning fw-bold" title="Revisi Hasil">Revisi</a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">Belum ada riwayat hasil pengujian yang disimpan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof $.fn.DataTable !== 'undefined') {
        $('#tabel-riwayat').DataTable({
            order: [[0, 'asc']],
            language: {
                url: '<?php echo base_url('assets/js/dataTables.indonesian.json'); ?>'
            }
        });
    }
});
</script>
