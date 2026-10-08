<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php
// Permission flags — digunakan untuk kontrol tombol aksi
$bisa_pengujian_operasional = $this->Model_Hak_Akses->memiliki_akses($this->session->userdata('role_id'), 'pengujian_view');
$bisa_pengujian_input       = $this->Model_Hak_Akses->memiliki_akses($this->session->userdata('role_id'), 'pengujian_input');
$user_id_login              = $this->session->userdata('user_id');
?>

<!-- Flash Messages -->
<?php if ($this->session->flashdata('pesan_sukses')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>
        <?php echo html_escape($this->session->flashdata('pesan_sukses')); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if ($this->session->flashdata('pesan_gagal')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-circle me-2"></i>
        <?php echo html_escape($this->session->flashdata('pesan_gagal')); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- Page Header -->
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-1 text-dark">Riwayat Hasil Uji</h4>
        <p class="text-muted small mb-0">Melihat riwayat dan hasil pengujian sampel yang telah diproses.</p>
    </div>
    <?php if ($bisa_pengujian_operasional): ?>
        <a href="<?php echo site_url('pengujian/antrean'); ?>" class="btn btn-primary btn-sm fw-semibold">
            <i class="bi bi-list-task me-1"></i> Antrean Pengujian
        </a>
    <?php endif; ?>
</div>

<!-- Filter Bar -->
<?php if (!empty($daftar_hasil)): ?>
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-2 px-3">
        <div class="row g-2 align-items-center">
            <div class="col-auto">
                <label class="form-label mb-0 text-muted small fw-semibold">Filter:</label>
            </div>
            <div class="col-auto">
                <select id="filter-jenis" class="form-select form-select-sm">
                    <option value="">Semua Jenis</option>
                    <option value="Kimia">Kimia</option>
                    <option value="Mikrobiologi">Mikrobiologi</option>
                </select>
            </div>
            <div class="col-auto">
                <select id="filter-status" class="form-select form-select-sm">
                    <option value="">Semua Status</option>
                    <option value="Sedang Diuji">Sedang Diuji</option>
                    <option value="Menunggu Verifikasi">Menunggu Verifikasi</option>
                    <option value="Menunggu Approval">Menunggu Approval</option>
                    <option value="Approved / Final">Approved / Final</option>
                    <option value="Ditolak">Ditolak</option>
                </select>
            </div>
            <div class="col-auto ms-auto">
                <span class="text-muted small">
                    Total: <strong id="total-rows"><?php echo count($daftar_hasil); ?></strong> sesi
                </span>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Main Table Card -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <?php if (!empty($daftar_hasil)): ?>
            <div class="table-responsive">
                <table id="tabel-riwayat" class="table table-hover align-middle mb-0" style="width:100%">
                    <thead class="table-light border-bottom">
                        <tr>
                            <th class="px-3" width="4%">#</th>
                            <th>No. / Kode Sampel</th>
                            <th>Nama Sampel</th>
                            <th>Jenis Pengujian</th>
                            <th>Penguji</th>
                            <th>Penyelia</th>
                            <th>Kesimpulan</th>
                            <th>Status</th>
                            <th class="text-center" width="18%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($daftar_hasil as $h): ?>
                            <?php
                            // Status badge class
                            $st     = isset($h['status']) ? $h['status'] : '-';
                            $st_cls = 'badge-menunggu-pengujian';
                            if ($st === 'Sedang Diuji')           $st_cls = 'badge-sedang-diuji';
                            elseif ($st === 'Menunggu Verifikasi') $st_cls = 'badge-menunggu-verifikasi';
                            elseif ($st === 'Menunggu Approval')   $st_cls = 'badge-menunggu-approval';
                            elseif ($st === 'Approved / Final')    $st_cls = 'badge-approved-final';
                            elseif ($st === 'Ditolak')             $st_cls = 'badge-ditolak';

                            // Kesimpulan badge
                            $kes   = isset($h['kesimpulan']) ? $h['kesimpulan'] : '';
                            $k_cls = 'bg-secondary';
                            if (stripos($kes, 'memenuhi') !== false && stripos($kes, 'tidak') === false) {
                                $k_cls = 'bg-success';
                            } elseif (stripos($kes, 'tidak memenuhi') !== false) {
                                $k_cls = 'bg-danger';
                            } elseif (stripos($kes, 'belum') !== false) {
                                $k_cls = 'text-bg-light border';
                            }

                            $kode_sampel = $h['kode_sampel_manual']
                                ?: ($h['no_sampel'] ? 'NO-' . $h['no_sampel'] : 'SMP-' . $h['sample_id']);

                            // Apakah baris ini sesi (punya jenis_pengujian) atau single form legacy
                            $is_sesi = !empty($h['jenis_pengujian']);

                            // Jenis untuk filter
                            $jenis_val  = $is_sesi ? html_escape($h['jenis_pengujian']) : 'Legacy';
                            ?>
                            <tr data-jenis="<?php echo $jenis_val; ?>" data-status="<?php echo html_escape($st); ?>">
                                <td class="px-3 text-muted small"><?php echo $no++; ?></td>
                                <td>
                                    <span class="font-monospace fw-bold text-primary small">
                                        <?php echo html_escape($kode_sampel); ?>
                                    </span>
                                </td>
                                <td>
                                    <strong class="text-dark d-block"><?php echo html_escape($h['nama_sampel']); ?></strong>
                                    <?php if (!empty($h['nama_template']) && !$is_sesi): ?>
                                        <small class="text-muted"><?php echo html_escape($h['nama_template']); ?></small>
                                    <?php elseif ($is_sesi && !empty($h['jumlah_form'])): ?>
                                        <small class="text-muted"><?php echo (int)$h['jumlah_form']; ?> form</small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($is_sesi): ?>
                                        <span class="badge bg-light text-dark border fw-semibold">
                                            <?php echo html_escape($h['jenis_pengujian']); ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="badge text-bg-light border text-muted">Single Form</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="text-dark small"><?php echo html_escape($h['nama_penguji'] ?: '-'); ?></span>
                                </td>
                                <td>
                                    <span class="text-dark small"><?php echo html_escape(isset($h['nama_verifier']) && $h['nama_verifier'] ? $h['nama_verifier'] : '-'); ?></span>
                                </td>
                                <td>
                                    <span class="badge <?php echo $k_cls; ?> px-2 py-1 small">
                                        <?php echo html_escape($kes ?: 'Belum Disimpulkan'); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge-status <?php echo $st_cls; ?>">
                                        <?php echo html_escape($st); ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex gap-1 justify-content-center flex-wrap">
                                        <?php if ($is_sesi): ?>
                                            <a href="<?php echo site_url('pengujian/detail_sesi/' . $h['id']); ?>"
                                               class="btn btn-outline-primary btn-sm"
                                               title="Lihat Detail Sesi">
                                                <i class="bi bi-eye me-1"></i>Detail
                                            </a>
                                        <?php else: ?>
                                            <a href="<?php echo site_url('pengujian/detail/' . $h['id']); ?>"
                                               class="btn btn-outline-primary btn-sm"
                                               title="Lihat Detail">
                                                <i class="bi bi-eye me-1"></i>Detail
                                            </a>
                                        <?php endif; ?>

                                        <?php
                                        // Download PDF — jika status Approved / Final atau file_laporan tersedia
                                        $has_pdf = ($st === 'Approved / Final' || !empty($h['file_laporan']));
                                        if ($has_pdf):
                                            $pdf_url = $is_sesi ? site_url('pengujian/pdf_sesi/' . $h['id']) : site_url('pengujian/download_pdf/' . $h['id']);
                                        ?>
                                            <a href="<?php echo $pdf_url; ?>"
                                               class="btn btn-outline-danger btn-sm"
                                               title="Unduh PDF Laporan" target="_blank">
                                                <i class="bi bi-file-earmark-pdf me-1"></i>PDF
                                            </a>
                                        <?php endif; ?>

                                        <?php
                                        // Tombol Revisi — hanya untuk penguji pemilik, status Ditolak
                                        if ($bisa_pengujian_input && $st === 'Ditolak' && isset($h['penguji_id']) && $h['penguji_id'] == $user_id_login):
                                        ?>
                                            <?php if ($is_sesi): ?>
                                                <a href="<?php echo site_url('pengujian/revisi_sesi/' . $h['id']); ?>"
                                                   class="btn btn-warning btn-sm fw-semibold"
                                                   title="Revisi Hasil Sesi">
                                                    <i class="bi bi-pencil-square me-1"></i>Revisi
                                                </a>
                                            <?php else: ?>
                                                <a href="<?php echo site_url('pengujian/revisi/' . $h['id']); ?>"
                                                   class="btn btn-warning btn-sm fw-semibold"
                                                   title="Revisi Hasil">
                                                    <i class="bi bi-pencil-square me-1"></i>Revisi
                                                </a>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <!-- Empty State -->
            <div class="text-center py-5 px-3">
                <div class="mb-3">
                    <i class="bi bi-file-earmark-text text-muted" style="font-size: 3rem; opacity: 0.4;"></i>
                </div>
                <h6 class="fw-bold text-dark mb-1">Belum Ada Hasil Pengujian</h6>
                <p class="text-muted small mb-0">
                    Belum terdapat hasil pengujian yang dapat ditampilkan.
                </p>
                <?php if ($bisa_pengujian_operasional): ?>
                    <a href="<?php echo site_url('pengujian/antrean'); ?>" class="btn btn-outline-primary btn-sm mt-3">
                        <i class="bi bi-list-task me-1"></i> Lihat Antrean Pengujian
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var table = null;

    if (typeof $.fn.DataTable !== 'undefined' && $('#tabel-riwayat').length) {
        table = $('#tabel-riwayat').DataTable({
            order: [[0, 'asc']],
            pageLength: 25,
            dom: '<"d-flex align-items-center justify-content-between mb-3"lf>rt<"d-flex align-items-center justify-content-between mt-3"ip>',
            language: {
                url: '<?php echo base_url('assets/js/dataTables.indonesian.json'); ?>',
                search: 'Cari:',
                lengthMenu: 'Tampilkan _MENU_ data',
                info: 'Menampilkan _START_–_END_ dari _TOTAL_ data',
                infoEmpty: 'Tidak ada data',
                paginate: { previous: '&laquo;', next: '&raquo;' }
            },
            columnDefs: [
                { orderable: false, targets: [-1] }
            ]
        });
    }

    // Filter Jenis Pengujian
    var filterJenis  = document.getElementById('filter-jenis');
    var filterStatus = document.getElementById('filter-status');

    function applyFilter() {
        var jenis  = filterJenis  ? filterJenis.value  : '';
        var status = filterStatus ? filterStatus.value : '';

        if (table) {
            // Custom filter via DataTables
            $.fn.dataTable.ext.search.length = 0; // reset existing custom search
            if (jenis || status) {
                $.fn.dataTable.ext.search.push(function (settings, data, dataIndex) {
                    var rowNode  = table.row(dataIndex).node();
                    var rowJenis = rowNode ? rowNode.getAttribute('data-jenis')  : '';
                    var rowSt    = rowNode ? rowNode.getAttribute('data-status') : '';

                    var okJenis  = !jenis  || rowJenis === jenis;
                    var okStatus = !status || rowSt    === status;
                    return okJenis && okStatus;
                });
            }
            table.draw();
            document.getElementById('total-rows').textContent = table.rows({ filter: 'applied' }).count();
        } else {
            // Fallback: no DataTable — filter via DOM
            var rows = document.querySelectorAll('#tabel-riwayat tbody tr');
            var visible = 0;
            rows.forEach(function (row) {
                var rowJenis  = row.getAttribute('data-jenis')  || '';
                var rowStatus = row.getAttribute('data-status') || '';
                var show = (!jenis || rowJenis === jenis) && (!status || rowStatus === status);
                row.style.display = show ? '' : 'none';
                if (show) visible++;
            });
            var totalEl = document.getElementById('total-rows');
            if (totalEl) totalEl.textContent = visible;
        }
    }

    if (filterJenis)  filterJenis.addEventListener('change', applyFilter);
    if (filterStatus) filterStatus.addEventListener('change', applyFilter);
});
</script>
