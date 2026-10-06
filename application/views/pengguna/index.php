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
        <h5 class="fw-bold mb-0 text-dark">Daftar Pengguna</h5>
        <a href="<?php echo site_url('pengguna/tambah'); ?>" class="btn btn-primary btn-sm fw-semibold">
            + Tambah Pengguna
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="tabel-pengguna" class="table table-hover align-middle w-100">
                <thead class="table-light">
                    <tr>
                        <th width="5%">#</th>
                        <th>Nama Pengguna</th>
                        <th>NIP</th>
                        <th>Nama Lengkap</th>
                        <th>Role / Peran</th>
                        <th>Laboratorium</th>
                        <th>Status</th>
                        <th width="18%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($daftar_pengguna)): ?>
                        <?php $no = 1; foreach ($daftar_pengguna as $u): ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><strong class="text-dark"><?php echo html_escape($u['username']); ?></strong></td>
                                <td><?php echo html_escape($u['nip'] ?: '-'); ?></td>
                                <td><?php echo html_escape($u['nama_lengkap']); ?></td>
                                <td><span class="badge bg-info text-dark"><?php echo html_escape($u['nama_role'] ?: '-'); ?></span></td>
                                <td><?php echo html_escape($u['nama_lab'] ?: 'Seluruh Lab / Non-Lab'); ?></td>
                                <td>
                                    <?php if ($u['status'] === 'Aktif'): ?>
                                        <span class="badge bg-success">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="<?php echo site_url('pengguna/edit/' . $u['id']); ?>" class="btn btn-outline-primary" title="Edit Data">Edit</a>
                                        <a href="<?php echo site_url('pengguna/password/' . $u['id']); ?>" class="btn btn-outline-warning" title="Ubah Password">Password</a>
                                        <a href="<?php echo site_url('pengguna/status/' . $u['id']); ?>" class="btn btn-outline-secondary" title="Ubah Status">Status</a>
                                        <?php if ($u['id'] != $this->session->userdata('user_id')): ?>
                                            <a href="<?php echo site_url('pengguna/hapus/' . $u['id']); ?>" class="btn btn-outline-danger" onclick="return confirm('Yakin ingin menghapus pengguna ini?');" title="Hapus">Hapus</a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">Belum ada data pengguna.</td>
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
        $('#tabel-pengguna').DataTable({
            language: {
                url: '<?php echo base_url('assets/js/dataTables.indonesian.json'); ?>'
            }
        });
    }
});
</script>
