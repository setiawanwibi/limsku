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

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
        <h5 class="fw-bold mb-0 text-dark">Daftar Peran (Roles)</h5>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-secondary btn-sm fw-semibold" data-bs-toggle="modal" data-bs-target="#modalTambahPermission">
                + Master Permission
            </button>
            <a href="<?php echo site_url('peran/tambah'); ?>" class="btn btn-primary btn-sm fw-semibold">
                + Tambah Peran
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="5%">#</th>
                        <th>Nama Peran</th>
                        <th>Keterangan</th>
                        <th>Pengguna Terkait</th>
                        <th width="25%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($daftar_peran)): ?>
                        <?php $no = 1; foreach ($daftar_peran as $r): ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><strong class="text-dark"><?php echo html_escape($r['nama_role']); ?></strong></td>
                                <td><?php echo html_escape($r['keterangan'] ?: '-'); ?></td>
                                <td>
                                    <span class="badge bg-secondary"><?php echo $r['total_pengguna']; ?> Pengguna</span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="<?php echo site_url('peran/hak-akses/' . $r['id']); ?>" class="btn btn-outline-success" title="Pengaturan Hak Akses">Hak Akses</a>
                                        <a href="<?php echo site_url('peran/edit/' . $r['id']); ?>" class="btn btn-outline-primary" title="Edit Peran">Edit</a>
                                        <?php if ($r['total_pengguna'] == 0): ?>
                                            <a href="<?php echo site_url('peran/hapus/' . $r['id']); ?>" class="btn btn-outline-danger" onclick="return confirm('Yakin ingin menghapus peran ini?');" title="Hapus">Hapus</a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Belum ada data peran.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Master Permission -->
<div class="modal fade" id="modalTambahPermission" tabindex="-1" aria-labelledby="labelModalPermission" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?php echo site_url('peran/permission/tambah'); ?>" method="post">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="labelModalPermission">Tambah Master Permission</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nama_permission" class="form-label fw-semibold">Kode / Nama Permission <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama_permission" name="nama_permission" placeholder="contoh: pengguna_view, sampel_import" required>
                        <small class="text-muted">Gunakan huruf kecil dan garis bawah (snake_case).</small>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label fw-semibold">Deskripsi <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="deskripsi" name="deskripsi" placeholder="Melihat daftar pengguna" required>
                    </div>
                    <div class="mb-3">
                        <label for="kategori" class="form-label fw-semibold">Kategori Modul</label>
                        <input type="text" class="form-control" id="kategori" name="kategori" placeholder="contoh: Pengguna, Sampel, Pengujian">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Permission</button>
                </div>
            </form>
        </div>
    </div>
</div>
