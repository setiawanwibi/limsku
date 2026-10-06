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
        <div>
            <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-shield-check text-primary me-2"></i> Queue Approval Laporan Pengujian</h5>
            <small class="text-muted">Daftar laporan pengujian sampel yang menantikan approval oleh Manajer Teknis (Status: Menunggu Approval)</small>
        </div>
        <a href="<?php echo site_url('pengujian/riwayat'); ?>" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-clock-history me-1"></i> Riwayat Semua Pengujian
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="tabel-approval" class="table table-hover align-middle w-100">
                <thead class="table-light">
                    <tr>
                        <th width="5%">#</th>
                        <th>Kode Sampel</th>
                        <th>Nama Sampel</th>
                        <th>Penguji / Analis</th>
                        <th>Penyelia (Verifikator)</th>
                        <th>Kesimpulan</th>
                        <th width="12%">Aksi Approval</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($antrean)): ?>
                        <?php $no = 1; foreach ($antrean as $v): ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td>
                                    <strong class="text-primary font-monospace">
                                        <?php echo html_escape($v['kode_sampel_manual'] ?: ($v['no_sampel'] ? 'NO-' . $v['no_sampel'] : 'SMP-' . $v['sample_id'])); ?>
                                    </strong>
                                </td>
                                <td><strong class="text-dark"><?php echo html_escape($v['nama_sampel']); ?></strong></td>
                                <td><small class="text-dark fw-semibold"><?php echo html_escape($v['nama_penguji'] ?: '-'); ?></small></td>
                                <td><small class="text-info fw-semibold"><?php echo html_escape($v['nama_verifier'] ?: '-'); ?></small></td>
                                <td>
                                    <?php 
                                    $kes = $v['kesimpulan'];
                                    $b_cls = 'bg-secondary';
                                    if (stripos($kes, 'memenuhi') !== false && stripos($kes, 'tidak') === false) {
                                        $b_cls = 'bg-success';
                                    } elseif (stripos($kes, 'tidak memenuhi') !== false) {
                                        $b_cls = 'bg-danger';
                                    }
                                    ?>
                                    <span class="badge <?php echo $b_cls; ?> px-2 py-1">
                                        <?php echo html_escape($kes); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="<?php echo site_url('pengujian/detail/' . $v['id']); ?>" class="btn btn-primary btn-sm fw-semibold">
                                        <i class="bi bi-search me-1"></i> Periksa &amp; Approve
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Belum ada laporan pengujian dalam antrean "Menunggu Approval".</td>
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
        $('#tabel-approval').DataTable({
            language: {
                url: '<?php echo base_url('assets/js/dataTables.indonesian.json'); ?>'
            }
        });
    }
});
</script>
