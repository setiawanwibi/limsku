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
        <h5 class="fw-bold mb-0 text-dark">Daftar Laboratorium / Unit Pelaksana</h5>
        <a href="<?php echo site_url('laboratorium/tambah'); ?>" class="btn btn-primary btn-sm fw-semibold">
            + Tambah Laboratorium
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="5%">#</th>
                        <th>Kode Lab</th>
                        <th>Nama Laboratorium</th>
                        <th>Deskripsi</th>
                        <th>Status</th>
                        <th>Pengguna Terkait</th>
                        <th width="20%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($daftar_laboratorium)): ?>
                        <?php $no = 1; foreach ($daftar_laboratorium as $lab): ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><span class="badge bg-primary"><?php echo html_escape($lab['kode_lab']); ?></span></td>
                                <td><strong class="text-dark"><?php echo html_escape($lab['nama_lab']); ?></strong></td>
                                <td><?php echo html_escape($lab['deskripsi'] ?: '-'); ?></td>
                                <td>
                                    <?php if ($lab['status'] === 'Aktif'): ?>
                                        <span class="badge bg-success">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    <?php endif; ?>
                                </td>
                                <td><span class="badge bg-secondary"><?php echo $lab['total_pengguna']; ?> Pengguna</span></td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="<?php echo site_url('laboratorium/edit/' . $lab['id']); ?>" class="btn btn-outline-primary" title="Edit">Edit</a>
                                        <a href="<?php echo site_url('laboratorium/status/' . $lab['id']); ?>" class="btn btn-outline-secondary" title="Ubah Status">Status</a>
                                        <?php if ($lab['total_pengguna'] == 0): ?>
                                            <a href="<?php echo site_url('laboratorium/hapus/' . $lab['id']); ?>" class="btn btn-outline-danger" onclick="return confirm('Yakin ingin menghapus laboratorium ini?');" title="Hapus">Hapus</a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Belum ada data laboratorium.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
