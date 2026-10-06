<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="fw-bold mb-0 text-dark">Registrasi Sampel Manual</h5>
    </div>
    <div class="card-body">
        <?php if (validation_errors()): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo validation_errors('<div>', '</div>'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form action="<?php echo site_url('sampel/tambah'); ?>" method="post">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

            <!-- Section 1: Identitas Utama Sampel -->
            <h6 class="fw-bold text-primary mb-3 border-bottom pb-2">1. Identitas Utama Sampel</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <label for="no" class="form-label fw-semibold">No.</label>
                    <input type="text" class="form-control" id="no" name="no" value="<?php echo set_value('no'); ?>" placeholder="Nomor urut/registrasi">
                </div>
                <div class="col-md-4">
                    <label for="kode_sampel_manual" class="form-label fw-semibold">Kode Sampel Manual</label>
                    <input type="text" class="form-control" id="kode_sampel_manual" name="kode_sampel_manual" value="<?php echo set_value('kode_sampel_manual'); ?>" placeholder="Contoh: K-001/BPOM/2026">
                </div>
                <div class="col-md-5">
                    <label for="nama_sampel" class="form-label fw-semibold">Nama Sampel <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="nama_sampel" name="nama_sampel" value="<?php echo set_value('nama_sampel'); ?>" placeholder="Masukkan nama obat/produk sampel" required>
                </div>
                <div class="col-md-4">
                    <label for="kategori_sampel" class="form-label fw-semibold">Kategori Sampel</label>
                    <input type="text" class="form-control" id="kategori_sampel" name="kategori_sampel" value="<?php echo set_value('kategori_sampel'); ?>" placeholder="Obat / Makanan / Kosmetik">
                </div>
                <div class="col-md-4">
                    <label for="sub_kategori" class="form-label fw-semibold">Sub Kategori</label>
                    <input type="text" class="form-control" id="sub_kategori" name="sub_kategori" value="<?php echo set_value('sub_kategori'); ?>">
                </div>
                <div class="col-md-4">
                    <label for="jenis_kelas_terapi" class="form-label fw-semibold">Jenis / Kelas Terapi</label>
                    <input type="text" class="form-control" id="jenis_kelas_terapi" name="jenis_kelas_terapi" value="<?php echo set_value('jenis_kelas_terapi'); ?>">
                </div>
            </div>

            <!-- Section 2: Asal & Sarana Sampling -->
            <h6 class="fw-bold text-primary mb-3 border-bottom pb-2">2. Asal &amp; Sarana Sampling</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label for="kategori_sarana" class="form-label fw-semibold">Kategori Sarana</label>
                    <input type="text" class="form-control" id="kategori_sarana" name="kategori_sarana" value="<?php echo set_value('kategori_sarana'); ?>">
                </div>
                <div class="col-md-4">
                    <label for="nama_sarana" class="form-label fw-semibold">Nama Sarana</label>
                    <input type="text" class="form-control" id="nama_sarana" name="nama_sarana" value="<?php echo set_value('nama_sarana'); ?>">
                </div>
                <div class="col-md-4">
                    <label for="kabupaten_kota" class="form-label fw-semibold">Kabupaten / Kota</label>
                    <input type="text" class="form-control" id="kabupaten_kota" name="kabupaten_kota" value="<?php echo set_value('kabupaten_kota'); ?>">
                </div>
                <div class="col-md-4">
                    <label for="tanggal_sampling" class="form-label fw-semibold">Tanggal Sampling</label>
                    <input type="text" class="form-control" id="tanggal_sampling" name="tanggal_sampling" value="<?php echo set_value('tanggal_sampling'); ?>" placeholder="YYYY-MM-DD">
                </div>
                <div class="col-md-4">
                    <label for="no_sipt" class="form-label fw-semibold">NO SIPT</label>
                    <input type="text" class="form-control" id="no_sipt" name="no_sipt" value="<?php echo set_value('no_sipt'); ?>">
                </div>
                <div class="col-md-4">
                    <label for="surtug" class="form-label fw-semibold">Surat Tugas (Surtug)</label>
                    <input type="text" class="form-control" id="surtug" name="surtug" value="<?php echo set_value('surtug'); ?>">
                </div>
            </div>

            <!-- Section 3: Detail Produk & Kemasan -->
            <h6 class="fw-bold text-primary mb-3 border-bottom pb-2">3. Detail Produk &amp; Kemasan</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label for="nomor_izin_edar" class="form-label fw-semibold">Nomor Izin Edar (NIE)</label>
                    <input type="text" class="form-control" id="nomor_izin_edar" name="nomor_izin_edar" value="<?php echo set_value('nomor_izin_edar'); ?>">
                </div>
                <div class="col-md-4">
                    <label for="no_bets" class="form-label fw-semibold">No. Bets</label>
                    <input type="text" class="form-control" id="no_bets" name="no_bets" value="<?php echo set_value('no_bets'); ?>">
                </div>
                <div class="col-md-4">
                    <label for="kedaluwarsa" class="form-label fw-semibold">Kedaluwarsa (Exp Date)</label>
                    <input type="text" class="form-control" id="kedaluwarsa" name="kedaluwarsa" value="<?php echo set_value('kedaluwarsa'); ?>">
                </div>
                <div class="col-md-4">
                    <label for="kondisi_produk" class="form-label fw-semibold">Kondisi Produk</label>
                    <input type="text" class="form-control" id="kondisi_produk" name="kondisi_produk" value="<?php echo set_value('kondisi_produk'); ?>" placeholder="Baik / Rusak / Segel Utuh">
                </div>
                <div class="col-md-4">
                    <label for="kemasan" class="form-label fw-semibold">Kemasan</label>
                    <input type="text" class="form-control" id="kemasan" name="kemasan" value="<?php echo set_value('kemasan'); ?>">
                </div>
                <div class="col-md-4">
                    <label for="penyimpanan" class="form-label fw-semibold">Penyimpanan</label>
                    <input type="text" class="form-control" id="penyimpanan" name="penyimpanan" value="<?php echo set_value('penyimpanan'); ?>" placeholder="Suhu Kamar / Dingin (2-8 C)">
                </div>
                <div class="col-md-6">
                    <label for="nama_alamat_perusahaan" class="form-label fw-semibold">Nama dan Alamat Perusahaan</label>
                    <textarea class="form-control" id="nama_alamat_perusahaan" name="nama_alamat_perusahaan" rows="2"><?php echo set_value('nama_alamat_perusahaan'); ?></textarea>
                </div>
                <div class="col-md-6">
                    <label for="komposisi" class="form-label fw-semibold">Komposisi</label>
                    <textarea class="form-control" id="komposisi" name="komposisi" rows="2"><?php echo set_value('komposisi'); ?></textarea>
                </div>
            </div>

            <!-- Section 4: Jumlah Sampel & Alokasi -->
            <h6 class="fw-bold text-primary mb-3 border-bottom pb-2">4. Jumlah Sampel &amp; Alokasi</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-2">
                    <label for="jumlah_kimia" class="form-label fw-semibold">Jumlah Kimia</label>
                    <input type="text" class="form-control" id="jumlah_kimia" name="jumlah_kimia" value="<?php echo set_value('jumlah_kimia'); ?>">
                </div>
                <div class="col-md-2">
                    <label for="jumlah_mikro" class="form-label fw-semibold">Jumlah Mikro</label>
                    <input type="text" class="form-control" id="jumlah_mikro" name="jumlah_mikro" value="<?php echo set_value('jumlah_mikro'); ?>">
                </div>
                <div class="col-md-2">
                    <label for="jumlah_arsip" class="form-label fw-semibold">Jumlah Arsip</label>
                    <input type="text" class="form-control" id="jumlah_arsip" name="jumlah_arsip" value="<?php echo set_value('jumlah_arsip'); ?>">
                </div>
                <div class="col-md-3">
                    <label for="jumlah_penandaan" class="form-label fw-semibold">Jumlah Penandaan</label>
                    <input type="text" class="form-control" id="jumlah_penandaan" name="jumlah_penandaan" value="<?php echo set_value('jumlah_penandaan'); ?>">
                </div>
                <div class="col-md-3">
                    <label for="jumlah_total" class="form-label fw-semibold">Jumlah Total</label>
                    <input type="text" class="form-control" id="jumlah_total" name="jumlah_total" value="<?php echo set_value('jumlah_total'); ?>">
                </div>
            </div>

            <!-- Section 5: Evaluasi & Administrasi -->
            <h6 class="fw-bold text-primary mb-3 border-bottom pb-2">5. Evaluasi &amp; Administrasi</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label for="penandaan" class="form-label fw-semibold">Penandaan (Field Utama)</label>
                    <textarea class="form-control" id="penandaan" name="penandaan" rows="2" placeholder="Catatan evaluasi penandaan produk"><?php echo set_value('penandaan'); ?></textarea>
                </div>
                <div class="col-md-3">
                    <label for="harga" class="form-label fw-semibold">Harga</label>
                    <input type="text" class="form-control" id="harga" name="harga" value="<?php echo set_value('harga'); ?>">
                </div>
                <div class="col-md-3">
                    <label for="tie" class="form-label fw-semibold">TIE</label>
                    <input type="text" class="form-control" id="tie" name="tie" value="<?php echo set_value('tie'); ?>">
                </div>
                <div class="col-md-3">
                    <label for="mk" class="form-label fw-semibold">MK</label>
                    <input type="text" class="form-control" id="mk" name="mk" value="<?php echo set_value('mk'); ?>">
                </div>
                <div class="col-md-3">
                    <label for="tmk" class="form-label fw-semibold">TMK</label>
                    <input type="text" class="form-control" id="tmk" name="tmk" value="<?php echo set_value('tmk'); ?>">
                </div>
                <div class="col-md-6">
                    <label for="balai_penguji" class="form-label fw-semibold">Balai Penguji</label>
                    <input type="text" class="form-control" id="balai_penguji" name="balai_penguji" value="<?php echo set_value('balai_penguji', 'Balai Besar POM di Bandar Lampung'); ?>">
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                <a href="<?php echo site_url('sampel'); ?>" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary fw-semibold">Simpan Registrasi Sampel</button>
            </div>
        </form>
    </div>
</div>
