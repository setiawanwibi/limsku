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
        <h5 class="fw-bold mb-0 text-dark">Daftar Master Metode Pengujian</h5>
        <a href="<?php echo site_url('metode/tambah'); ?>" class="btn btn-primary btn-sm fw-semibold">
            + Tambah Metode
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="tabel-metode" class="table table-hover align-middle w-100">
                <thead class="table-light">
                    <tr>
                        <th width="5%">#</th>
                        <th>Kode Metode</th>
                        <th>Nama Metode</th>
                        <th>Kategori</th>
                        <th>Versi</th>
                        <th>Status</th>
                        <th width="18%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($daftar_metode)): ?>
                        <?php $no = 1; foreach ($daftar_metode as $m): ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><span class="badge bg-primary font-monospace"><?php echo html_escape($m['kode_metode']); ?></span></td>
                                <td><strong class="text-dark"><?php echo html_escape($m['nama_metode']); ?></strong></td>
                                <td>
                                    <span class="badge <?php echo $m['kategori'] === 'Kimia' ? 'bg-info text-dark' : 'bg-warning text-dark'; ?>">
                                        <?php echo html_escape($m['kategori']); ?>
                                    </span>
                                </td>
                                <td><?php echo html_escape($m['versi'] ?: '-'); ?></td>
                                <td>
                                    <?php if ($m['status'] === 'Aktif'): ?>
                                        <span class="badge bg-success">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="<?php echo site_url('metode/edit/' . $m['id']); ?>" class="btn btn-outline-primary" title="Edit">Edit</a>
                                        <a href="<?php echo site_url('metode/status/' . $m['id']); ?>" class="btn btn-outline-secondary" title="Ubah Status">Status</a>
                                        <a href="<?php echo site_url('metode/hapus/' . $m['id']); ?>" class="btn btn-outline-danger" onclick="return confirm('Yakin ingin menghapus metode ini?');" title="Hapus">Hapus</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Belum ada data metode pengujian.</td>
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
        $('#tabel-metode').DataTable({
            language: {
                url: '<?php echo base_url('assets/js/dataTables.indonesian.json'); ?>'
            }
        });
    }
});
</script>
