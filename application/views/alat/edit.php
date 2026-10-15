<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
    .instrument-form-page {
        width: 100%;
        padding: 4px 0 22px;
        color: #294257;
    }

    .instrument-form-card {
        width: 100%;
        max-width: 900px;
        margin: 0 auto;
        background: #ffffff;
        border: 1px solid #e0e8ed;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(41, 66, 87, 0.05);
        overflow: hidden;
    }

    .instrument-form-header {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 18px 22px;
        background: #ffffff;
        border-bottom: 1px solid #e8eef1;
    }

    .instrument-form-header-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        width: 42px;
        height: 42px;
        border-radius: 10px;
        background: #e5f6f4;
        color: #159b98;
        font-size: 20px;
    }

    .instrument-form-title {
        margin: 0;
        color: #294257;
        font-size: 1.08rem;
        font-weight: 700;
    }

    .instrument-form-subtitle {
        margin: 4px 0 0;
        color: #7b8b98;
        font-size: 0.8rem;
    }

    .instrument-form-body {
        padding: 22px 24px;
    }

    .instrument-section-title {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 16px;
        padding-bottom: 8px;
        border-bottom: 1px solid #edf2f5;
        color: #159b98;
        font-size: 0.9rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }

    .instrument-form-footer {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
        padding-top: 18px;
        margin-top: 24px;
        border-top: 1px solid #edf2f5;
    }

    .btn-save-instrument {
        background: #159b98;
        color: #ffffff;
        border: 1px solid #159b98;
        padding: 9px 20px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.86rem;
        transition: all 0.2s ease;
    }
    .btn-save-instrument:hover {
        background: #118380;
        border-color: #118380;
        color: #ffffff;
    }

    .required-mark {
        color: #e53e3e;
    }
</style>

<div class="instrument-form-page">
    <div class="instrument-form-card">

        <div class="instrument-form-header">
            <div class="instrument-form-header-icon">
                <i class="bi bi-pencil-square"></i>
            </div>
            <div>
                <h5 class="instrument-form-title">Edit Alat Laboratorium</h5>
                <p class="instrument-form-subtitle">Perbarui data inventaris alat <?php echo html_escape($alat['kode_barang']); ?>.</p>
            </div>
        </div>

        <div class="instrument-form-body">

            <?php if (validation_errors()): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <strong>Terdapat kesalahan input:</strong>
                    <div class="mt-1 small">
                        <?php echo validation_errors('<div>• ', '</div>'); ?>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form action="<?php echo site_url('alat/edit/' . $alat['id']); ?>" method="post">
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

                <!-- SECTION 1: IDENTITAS ALAT -->
                <div class="instrument-section-title">
                    <i class="bi bi-card-heading"></i> Identitas &amp; Spesifikasi Alat
                </div>

                <div class="row g-3 mb-4">
                    <!-- 1. Kode Barang (Terkunci / Read-Only) -->
                    <div class="col-md-4">
                        <label for="kode_barang" class="form-label fw-semibold small">
                            Kode Barang <span class="badge bg-secondary-subtle text-secondary ms-1">Terkunci</span>
                        </label>
                        <input type="text"
                               class="form-control bg-light font-monospace fw-bold"
                               id="kode_barang"
                               name="kode_barang_display"
                               value="<?php echo html_escape($alat['kode_barang']); ?>"
                               readonly
                               title="Kode barang bersifat permanen dan tidak dapat diubah">
                        <div class="form-text small">Kode barang tidak dapat diubah saat edit.</div>
                    </div>

                    <!-- 2. Nama Barang -->
                    <div class="col-md-8">
                        <label for="nama_barang" class="form-label fw-semibold small">
                            Nama Barang <span class="required-mark">*</span>
                        </label>
                        <input type="text"
                               class="form-control"
                               id="nama_barang"
                               name="nama_barang"
                               value="<?php echo set_value('nama_barang', $alat['nama_barang']); ?>"
                               placeholder="Contoh: KCKT / HPLC System, Spektrofotometer UV-Vis"
                               required>
                    </div>

                    <!-- 3. Merk/Tipe -->
                    <div class="col-md-4">
                        <label for="merk_tipe" class="form-label fw-semibold small">Merk/Tipe</label>
                        <input type="text"
                               class="form-control"
                               id="merk_tipe"
                               name="merk_tipe"
                               value="<?php echo set_value('merk_tipe', $alat['merk_tipe']); ?>"
                               placeholder="Contoh: Shimadzu Prominence LC-20AD">
                    </div>

                    <!-- 4. Perangkat Lunak -->
                    <div class="col-md-4">
                        <label for="perangkat_lunak" class="form-label fw-semibold small">Perangkat Lunak</label>
                        <input type="text"
                               class="form-control"
                               id="perangkat_lunak"
                               name="perangkat_lunak"
                               value="<?php echo set_value('perangkat_lunak', $alat['perangkat_lunak']); ?>"
                               placeholder="Contoh: LabSolutions v5.8">
                    </div>

                    <!-- 5. Detektor -->
                    <div class="col-md-4">
                        <label for="detektor" class="form-label fw-semibold small">Detektor</label>
                        <input type="text"
                               class="form-control"
                               id="detektor"
                               name="detektor"
                               value="<?php echo set_value('detektor', $alat['detektor']); ?>"
                               placeholder="Contoh: PDA / UV-Vis Detector">
                    </div>
                </div>

                <!-- SECTION 2: PEROLEHAN & KONDISI -->
                <div class="instrument-section-title">
                    <i class="bi bi-calendar-check"></i> Perolehan &amp; Kondisi
                </div>

                <div class="row g-3 mb-4">
                    <!-- 6. Tanggal Perolehan -->
                    <div class="col-md-6">
                        <label for="tanggal_perolehan" class="form-label fw-semibold small">Tanggal Perolehan</label>
                        <input type="date"
                               class="form-control"
                               id="tanggal_perolehan"
                               name="tanggal_perolehan"
                               value="<?php echo set_value('tanggal_perolehan', $alat['tanggal_perolehan']); ?>">
                    </div>

                    <!-- 7. Kondisi ADM -->
                    <div class="col-md-6">
                        <label for="kondisi_adm" class="form-label fw-semibold small">Kondisi ADM</label>
                        <input type="text"
                               class="form-control"
                               id="kondisi_adm"
                               name="kondisi_adm"
                               value="<?php echo set_value('kondisi_adm', $alat['kondisi_adm']); ?>"
                               placeholder="Contoh: Baik, Rusak Ringan, Rusak Berat">
                    </div>
                </div>

                <!-- SECTION 3: LOKASI RUANGAN & STATUS -->
                <div class="instrument-section-title">
                    <i class="bi bi-geo-alt"></i> Penempatan Ruangan &amp; Status
                </div>

                <div class="row g-3 mb-4">
                    <!-- 8. Kode Ruangan -->
                    <div class="col-md-4">
                        <label for="kode_ruangan" class="form-label fw-semibold small">Kode Ruangan</label>
                        <input type="text"
                               class="form-control"
                               id="kode_ruangan"
                               name="kode_ruangan"
                               value="<?php echo set_value('kode_ruangan', $alat['kode_ruangan']); ?>"
                               placeholder="Contoh: R-KIM-01"
                               list="list-kode-ruangan">
                        <datalist id="list-kode-ruangan">
                            <?php if (!empty($daftar_ruangan)): ?>
                                <?php foreach ($daftar_ruangan as $rng): ?>
                                    <option value="<?php echo html_escape($rng['kode_ruangan']); ?>"><?php echo html_escape($rng['nama_ruangan']); ?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </datalist>
                    </div>

                    <!-- 9. Nama Ruangan -->
                    <div class="col-md-5">
                        <label for="nama_ruangan" class="form-label fw-semibold small">Nama Ruangan</label>
                        <input type="text"
                               class="form-control"
                               id="nama_ruangan"
                               name="nama_ruangan"
                               value="<?php echo set_value('nama_ruangan', $alat['nama_ruangan']); ?>"
                               placeholder="Contoh: Ruang Instrumen Kimia">
                    </div>

                    <!-- 10. Status -->
                    <div class="col-md-3">
                        <label for="status" class="form-label fw-semibold small">Status Alat</label>
                        <select class="form-select" id="status" name="status">
                            <option value="Aktif" <?php echo set_select('status', 'Aktif', ($alat['status'] === 'Aktif')); ?>>Aktif</option>
                            <option value="Nonaktif" <?php echo set_select('status', 'Nonaktif', ($alat['status'] === 'Nonaktif')); ?>>Nonaktif</option>
                        </select>
                    </div>
                </div>

                <div class="instrument-form-footer">
                    <a href="<?php echo site_url('alat'); ?>" class="btn btn-outline-secondary px-3">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-save-instrument">
                        <i class="bi bi-save me-1"></i> Simpan Perubahan
                    </button>
                </div>

            </form>

        </div>

    </div>
</div>
