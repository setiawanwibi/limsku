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

<!-- Section Pekerjaan Sedang Diuji Oleh Anda -->
<?php if (!empty($sedang_diuji)): ?>
    <div class="card border-0 shadow-sm mb-4 border-start border-warning border-4">
        <div class="card-header bg-white py-3">
            <h6 class="fw-bold mb-0 text-dark">Pekerjaan Pengujian Sedang Berlangsung (Klaim Anda)</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Kode / No</th>
                            <th>Nama Sampel</th>
                            <th>Kategori</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($sedang_diuji as $sd): ?>
                            <tr>
                                <td><strong class="text-primary font-monospace"><?php echo html_escape($sd['kode_sampel_manual'] ?: ($sd['no'] ? 'NO-' . $sd['no'] : 'SMP-' . $sd['id'])); ?></strong></td>
                                <td><strong class="text-dark"><?php echo html_escape($sd['nama_sampel']); ?></strong></td>
                                <td><?php echo html_escape($sd['kategori_sampel'] ?: '-'); ?></td>
                                <td><span class="badge bg-warning text-dark"><?php echo html_escape($sd['status']); ?></span></td>
                                <td>
                                    <a href="<?php echo site_url('pengujian/proses/' . $sd['id']); ?>" class="btn btn-warning btn-sm fw-semibold">
                                        Lanjutkan Pengujian &rarr;
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Section Antrean Sampel (Menunggu Pengujian) -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
        <h5 class="fw-bold mb-0 text-dark">Antrean Sampel (Status: Menunggu Pengujian)</h5>
        <a href="<?php echo site_url('pengujian/riwayat'); ?>" class="btn btn-outline-secondary btn-sm">
            Riwayat Hasil Pengujian
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="tabel-antrean" class="table table-hover align-middle w-100">
                <thead class="table-light">
                    <tr>
                        <th width="5%">#</th>
                        <th>Kode / No</th>
                        <th>Nama Sampel</th>
                        <th>Kategori</th>
                        <th>Sarana</th>
                        <th>Petugas Input</th>
                        <th width="15%">Aksi Self-Assignment</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($antrean_sampel)): ?>
                        <?php $no = 1; foreach ($antrean_sampel as $s): ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td>
                                    <strong class="text-primary font-monospace">
                                        <?php echo html_escape($s['kode_sampel_manual'] ?: ($s['no'] ? 'NO-' . $s['no'] : 'SMP-' . $s['id'])); ?>
                                    </strong>
                                </td>
                                <td><strong class="text-dark"><?php echo html_escape($s['nama_sampel']); ?></strong></td>
                                <td><?php echo html_escape($s['kategori_sampel'] ?: '-'); ?></td>
                                <td><?php echo html_escape($s['nama_sarana'] ?: '-'); ?></td>
                                <td><small class="text-muted"><?php echo html_escape($s['nama_pembuat'] ?: 'Sistem'); ?></small></td>
                                <td>
                                    <a href="<?php echo site_url('pengujian/klaim/' . $s['id']); ?>" class="btn btn-primary btn-sm fw-semibold" onclick="return confirm('Klaim dan ambil pekerjaan pengujian sampel ini?');">
                                        Ambil &amp; Uji Sampel
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Belum ada sampel baru dalam antrean "Menunggu Pengujian".</td>
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
        $('#tabel-antrean').DataTable({
            language: {
                url: '<?php echo base_url('assets/js/dataTables.indonesian.json'); ?>'
            }
        });
    }
});
</script>
