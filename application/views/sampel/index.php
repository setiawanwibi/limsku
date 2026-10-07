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
        <h5 class="fw-bold mb-0 text-dark">Daftar Sampel Laboratorium</h5>
        <div class="d-flex gap-2">
            <a href="<?php echo site_url('sampel/import'); ?>" class="btn btn-outline-success btn-sm fw-semibold">
                &uarr; Import Excel
            </a>
            <a href="<?php echo site_url('sampel/tambah'); ?>" class="btn btn-primary btn-sm fw-semibold">
                + Registrasi Manual
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="tabel-sampel" class="table table-hover align-middle w-100">
                <thead class="table-light">
                    <tr>
                        <th width="5%">#</th>
                        <th>Kode / No</th>
                        <th>Nama Sampel</th>
                        <th>Kategori</th>
                        <th>Sarana</th>
                        <th>Status</th>
                        <th>Petugas Input</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($daftar_sampel)): ?>
                        <?php $no = 1; foreach ($daftar_sampel as $s): ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td>
                                    <strong class="text-primary font-monospace">
                                        <?php echo html_escape($s['kode_sampel_manual'] ?: ($s['no'] ? 'NO-' . $s['no'] : 'SMP-' . $s['id'])); ?>
                                    </strong>
                                </td>
                                <td>
                                    <a href="<?php echo site_url('sampel/detail/' . $s['id']); ?>" class="fw-semibold text-dark text-decoration-none">
                                        <?php echo html_escape($s['nama_sampel']); ?>
                                    </a>
                                </td>
                                <td>
                                    <small class="d-block text-dark"><?php echo html_escape($s['kategori_sampel'] ?: '-'); ?></small>
                                    <small class="text-muted"><?php echo html_escape($s['sub_kategori'] ?: ''); ?></small>
                                </td>
                                <td><?php echo html_escape($s['nama_sarana'] ?: '-'); ?></td>
                                <td>
                                    <?php
                                        $st = $s['status'];
                                        $b_class = 'badge-menunggu-pengujian';
                                        if ($st === 'Sedang Diuji') $b_class = 'badge-sedang-diuji';
                                        elseif ($st === 'Menunggu Verifikasi') $b_class = 'badge-menunggu-verifikasi';
                                        elseif ($st === 'Menunggu Approval') $b_class = 'badge-menunggu-approval';
                                        elseif ($st === 'Approved / Final') $b_class = 'badge-approved-final';
                                        elseif ($st === 'Ditolak') $b_class = 'badge-ditolak';
                                    ?>
                                    <span class="badge-status <?php echo $b_class; ?>"><?php echo html_escape($st); ?></span>
                                </td>
                                <td>
                                    <small class="text-muted"><?php echo html_escape($s['nama_pembuat'] ?: 'Sistem'); ?></small>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="<?php echo site_url('sampel/detail/' . $s['id']); ?>" class="btn btn-outline-info" title="Detail Sampel">Detail</a>
                                        <a href="<?php echo site_url('sampel/edit/' . $s['id']); ?>" class="btn btn-outline-primary" title="Edit Sampel">Edit</a>
                                        <a href="<?php echo site_url('sampel/hapus/' . $s['id']); ?>" class="btn btn-outline-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data sampel ini?');" title="Hapus Sampel">Hapus</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">Belum ada data sampel yang terdaftar.</td>
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
        $('#tabel-sampel').DataTable({
            order: [[0, 'asc']],
            language: {
                url: '<?php echo base_url('assets/js/dataTables.indonesian.json'); ?>'
            }
        });
    }
});
</script>
