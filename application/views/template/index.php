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
        <h5 class="fw-bold mb-0 text-dark">Master Template Form Pengujian (17 Form Dinamis)</h5>
        <div class="d-flex gap-2">
            <a href="<?php echo site_url('template/preset'); ?>" class="btn btn-outline-info btn-sm fw-semibold" onclick="return confirm('Inisialisasi 17 Preset Form Pengujian (12 Kimia + 5 Mikrobiologi)?');">
                &starf; Inisialisasi 17 Preset Form
            </a>
            <a href="<?php echo site_url('template/tambah'); ?>" class="btn btn-primary btn-sm fw-semibold">
                + Tambah Template
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="tabel-template" class="table table-hover align-middle w-100">
                <thead class="table-light">
                    <tr>
                        <th width="5%">#</th>
                        <th>Kode Template</th>
                        <th>Nama Form Template</th>
                        <th>Kategori</th>
                        <th>Metode Acuan</th>
                        <th>Status</th>
                        <th width="18%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($daftar_template)): ?>
                        <?php $no = 1; foreach ($daftar_template as $t): ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><span class="badge bg-primary font-monospace"><?php echo html_escape($t['kode_template']); ?></span></td>
                                <td><strong class="text-dark"><?php echo html_escape($t['nama_template']); ?></strong></td>
                                <td>
                                    <span class="badge <?php echo $t['kategori'] === 'Kimia' ? 'bg-info text-dark' : 'bg-warning text-dark'; ?>">
                                        <?php echo html_escape($t['kategori']); ?>
                                    </span>
                                </td>
                                <td><?php echo html_escape($t['nama_metode'] ?: 'Dinamis / Pilihan Analis'); ?></td>
                                <td>
                                    <?php if ($t['status'] === 'Aktif'): ?>
                                        <span class="badge bg-success">Aktif</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="<?php echo site_url('template/edit/' . $t['id']); ?>" class="btn btn-outline-primary" title="Edit Skema">Edit Skema</a>
                                        <a href="<?php echo site_url('template/status/' . $t['id']); ?>" class="btn btn-outline-secondary" title="Ubah Status">Status</a>
                                        <a href="<?php echo site_url('template/hapus/' . $t['id']); ?>" class="btn btn-outline-danger" onclick="return confirm('Yakin ingin menghapus template ini?');" title="Hapus">Hapus</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                Belum ada template form pengujian. Klik <strong>"Inisialisasi 17 Preset Form"</strong> untuk mengisikan 12 Form Kimia &amp; 5 Form Mikrobiologi secara otomatis.
                            </td>
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
        $('#tabel-template').DataTable({
            language: {
                url: '<?php echo base_url('assets/js/dataTables.indonesian.json'); ?>'
            }
        });
    }
});
</script>
