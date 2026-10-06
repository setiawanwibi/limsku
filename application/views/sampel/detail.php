<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
        <h5 class="fw-bold mb-0 text-dark">
            Detail Sampel: <span class="text-primary"><?php echo html_escape($sampel['nama_sampel']); ?></span>
        </h5>
        <div class="d-flex gap-2">
            <a href="<?php echo site_url('sampel'); ?>" class="btn btn-outline-secondary btn-sm">
                &larr; Kembali ke Daftar
            </a>
            <a href="<?php echo site_url('sampel/edit/' . $sampel['id']); ?>" class="btn btn-primary btn-sm">
                Edit Sampel
            </a>
        </div>
    </div>
    <div class="card-body p-4">

        <div class="mb-4 d-flex align-items-center justify-content-between p-3 bg-light rounded border">
            <div>
                <span class="text-muted small d-block">Status Alur Sampel:</span>
                <span class="badge bg-warning text-dark fs-6"><?php echo html_escape($sampel['status']); ?></span>
            </div>
            <div class="text-end">
                <span class="text-muted small d-block">Kode Sampel:</span>
                <strong class="font-monospace fs-6 text-dark"><?php echo html_escape($sampel['kode_sampel_manual'] ?: ($sampel['no'] ? 'NO-' . $sampel['no'] : 'SMP-' . $sampel['id'])); ?></strong>
            </div>
        </div>

        <div class="row g-4">
            <!-- Col 1 -->
            <div class="col-md-6">
                <h6 class="fw-bold text-dark border-bottom pb-2">Identitas &amp; Sampling</h6>
                <table class="table table-sm table-borderless">
                    <tr>
                        <td width="35%" class="text-muted">No. Urut</td>
                        <td>: <strong><?php echo html_escape($sampel['no'] ?: '-'); ?></strong></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Kode Manual</td>
                        <td>: <?php echo html_escape($sampel['kode_sampel_manual'] ?: '-'); ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Kategori Sampel</td>
                        <td>: <?php echo html_escape($sampel['kategori_sampel'] ?: '-'); ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Sub Kategori</td>
                        <td>: <?php echo html_escape($sampel['sub_kategori'] ?: '-'); ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Jenis/Kelas Terapi</td>
                        <td>: <?php echo html_escape($sampel['jenis_kelas_terapi'] ?: '-'); ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Kategori Sarana</td>
                        <td>: <?php echo html_escape($sampel['kategori_sarana'] ?: '-'); ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Nama Sarana</td>
                        <td>: <?php echo html_escape($sampel['nama_sarana'] ?: '-'); ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Kabupaten / Kota</td>
                        <td>: <?php echo html_escape($sampel['kabupaten_kota'] ?: '-'); ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Tanggal Sampling</td>
                        <td>: <?php echo html_escape($sampel['tanggal_sampling'] ?: '-'); ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">NO SIPT</td>
                        <td>: <?php echo html_escape($sampel['no_sipt'] ?: '-'); ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Surat Tugas (Surtug)</td>
                        <td>: <?php echo html_escape($sampel['surtug'] ?: '-'); ?></td>
                    </tr>
                </table>
            </div>

            <!-- Col 2 -->
            <div class="col-md-6">
                <h6 class="fw-bold text-dark border-bottom pb-2">Detail Produk &amp; Alokasi</h6>
                <table class="table table-sm table-borderless">
                    <tr>
                        <td width="35%" class="text-muted">NIE (Izin Edar)</td>
                        <td>: <?php echo html_escape($sampel['nomor_izin_edar'] ?: '-'); ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">No. Bets</td>
                        <td>: <?php echo html_escape($sampel['no_bets'] ?: '-'); ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Kedaluwarsa</td>
                        <td>: <?php echo html_escape($sampel['kedaluwarsa'] ?: '-'); ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Kondisi Produk</td>
                        <td>: <?php echo html_escape($sampel['kondisi_produk'] ?: '-'); ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Kemasan</td>
                        <td>: <?php echo html_escape($sampel['kemasan'] ?: '-'); ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Penyimpanan</td>
                        <td>: <?php echo html_escape($sampel['penyimpanan'] ?: '-'); ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Jumlah Kimia</td>
                        <td>: <?php echo html_escape($sampel['jumlah_kimia'] ?: '-'); ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Jumlah Mikro</td>
                        <td>: <?php echo html_escape($sampel['jumlah_mikro'] ?: '-'); ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Jumlah Arsip</td>
                        <td>: <?php echo html_escape($sampel['jumlah_arsip'] ?: '-'); ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Jumlah Penandaan</td>
                        <td>: <?php echo html_escape($sampel['jumlah_penandaan'] ?: '-'); ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Jumlah Total</td>
                        <td>: <strong><?php echo html_escape($sampel['jumlah_total'] ?: '-'); ?></strong></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="row g-4 mt-2">
            <div class="col-md-6">
                <h6 class="fw-bold text-dark border-bottom pb-2">Perusahaan &amp; Komposisi</h6>
                <div class="mb-3">
                    <small class="text-muted d-block">Nama &amp; Alamat Perusahaan:</small>
                    <div class="p-2 bg-light rounded border small"><?php echo nl2br(html_escape($sampel['nama_alamat_perusahaan'] ?: '-')); ?></div>
                </div>
                <div>
                    <small class="text-muted d-block">Komposisi:</small>
                    <div class="p-2 bg-light rounded border small"><?php echo nl2br(html_escape($sampel['komposisi'] ?: '-')); ?></div>
                </div>
            </div>
            <div class="col-md-6">
                <h6 class="fw-bold text-dark border-bottom pb-2">Evaluasi Administrasi &amp; Status</h6>
                <div class="mb-3">
                    <small class="text-muted d-block">Penandaan (Field Utama):</small>
                    <div class="p-2 bg-light rounded border small"><?php echo nl2br(html_escape($sampel['penandaan'] ?: '-')); ?></div>
                </div>
                <div class="row g-2">
                    <div class="col-6"><small class="text-muted">Harga:</small> <strong><?php echo html_escape($sampel['harga'] ?: '-'); ?></strong></div>
                    <div class="col-6"><small class="text-muted">TIE:</small> <strong><?php echo html_escape($sampel['tie'] ?: '-'); ?></strong></div>
                    <div class="col-6"><small class="text-muted">MK:</small> <strong><?php echo html_escape($sampel['mk'] ?: '-'); ?></strong></div>
                    <div class="col-6"><small class="text-muted">TMK:</small> <strong><?php echo html_escape($sampel['tmk'] ?: '-'); ?></strong></div>
                </div>
                <div class="mt-3 pt-2 border-top">
                    <small class="text-muted d-block">Balai Penguji: <?php echo html_escape($sampel['balai_penguji'] ?: '-'); ?></small>
                    <small class="text-muted d-block">Petugas Registrasi: <?php echo html_escape($sampel['nama_pembuat'] ?: 'Sistem'); ?> (<?php echo html_escape($sampel['created_at']); ?>)</small>
                </div>
            </div>
        </div>

    </div>
</div>
