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
    <div class="card-header bg-white py-3">
        <h5 class="fw-bold mb-0 text-dark">Audit Trail Aktivitas Sistem</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="tabel-audit" class="table table-hover align-middle w-100">
                <thead class="table-light">
                    <tr>
                        <th width="5%">#</th>
                        <th>Waktu</th>
                        <th>Pengguna</th>
                        <th>Modul</th>
                        <th>Aksi</th>
                        <th>IP Address</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($daftar_log)): ?>
                        <?php $no = 1; foreach ($daftar_log as $log): ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><small class="text-muted font-monospace"><?php echo html_escape($log['created_at']); ?></small></td>
                                <td><strong><?php echo html_escape($log['username'] ?: 'Sistem / Anonim'); ?></strong></td>
                                <td><span class="badge bg-secondary"><?php echo html_escape($log['modul']); ?></span></td>
                                <td>
                                    <?php 
                                        $badge_class = 'bg-info text-dark';
                                        if (strpos($log['aksi'], 'CREATE') !== FALSE) $badge_class = 'bg-success';
                                        elseif (strpos($log['aksi'], 'UPDATE') !== FALSE) $badge_class = 'bg-warning text-dark';
                                        elseif (strpos($log['aksi'], 'DELETE') !== FALSE) $badge_class = 'bg-danger';
                                        elseif (strpos($log['aksi'], 'LOGIN_SUKSES') !== FALSE) $badge_class = 'bg-primary';
                                        elseif (strpos($log['aksi'], 'LOGIN_GAGAL') !== FALSE) $badge_class = 'bg-danger';
                                    ?>
                                    <span class="badge <?php echo $badge_class; ?>"><?php echo html_escape($log['aksi']); ?></span>
                                </td>
                                <td><small class="text-muted font-monospace"><?php echo html_escape($log['ip_address'] ?: '-'); ?></small></td>
                                <td>
                                    <small class="text-break">
                                        <?php echo html_escape($log['detail'] ?: '-'); ?>
                                    </small>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Belum ada catatan log aktivitas.</td>
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
        $('#tabel-audit').DataTable({
            order: [[0, 'asc']],
            language: {
                url: '<?php echo base_url('assets/js/dataTables.indonesian.json'); ?>'
            }
        });
    }
});
</script>
