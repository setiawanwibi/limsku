<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
    .reagent-form-page {
        width: 100%;
        padding: 4px 0 24px;
        color: #294257;
    }

    .reagent-form-card {
        width: 100%;
        max-width: 860px;
        background: #ffffff;
        border: 1px solid #e0e8ed;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(41, 66, 87, 0.05);
        overflow: hidden;
    }

    .reagent-form-header {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 18px 24px;
        border-bottom: 1px solid #e8eef1;
        background: #ffffff;
    }

    .reagent-form-header-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        background: #e6f7ff;
        color: #096dd9;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .reagent-form-title {
        margin: 0;
        color: #294257;
        font-size: 1.1rem;
        font-weight: 700;
    }

    .reagent-form-subtitle {
        margin: 4px 0 0;
        color: #7b8b98;
        font-size: 0.82rem;
    }

    .reagent-form-body {
        padding: 24px 26px;
    }

    .form-group-custom {
        margin-bottom: 18px;
    }

    .form-label-custom {
        font-size: 0.84rem;
        font-weight: 600;
        color: #334e68;
        margin-bottom: 6px;
        display: block;
    }

    .form-label-custom .required-mark {
        color: #e53e3e;
        margin-left: 2px;
    }

    .form-control-custom, .form-select-custom {
        border: 1px solid #cbd5e0;
        border-radius: 8px;
        padding: 9px 12px;
        font-size: 0.86rem;
        color: #2d3748;
        width: 100%;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .form-control-custom:focus, .form-select-custom:focus {
        border-color: #096dd9;
        box-shadow: 0 0 0 3px rgba(9, 109, 217, 0.15);
        outline: none;
    }

    .field-hint {
        font-size: 0.77rem;
        color: #718096;
        margin-top: 5px;
    }

    .form-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 26px;
        padding-top: 18px;
        border-top: 1px solid #edf2f7;
    }

    .btn-cancel {
        padding: 8px 18px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #4a5568;
        background: #edf2f7;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-cancel:hover {
        background: #e2e8f0;
        color: #2d3748;
    }

    .btn-submit {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 22px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #ffffff;
        background: #096dd9;
        border: 1px solid #096dd9;
        border-radius: 8px;
        box-shadow: 0 2px 6px rgba(9, 109, 217, 0.25);
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-submit:hover {
        background: #085fc4;
        border-color: #085fc4;
    }
</style>

<div class="reagent-form-page">

    <div class="reagent-form-card">
        <div class="reagent-form-header">
            <div class="reagent-form-header-icon">
                <i class="bi bi-eyedropper"></i>
            </div>
            <div>
                <h5 class="reagent-form-title">Tambah Master Reagen</h5>
                <p class="reagent-form-subtitle">
                    Lengkapi informasi barang/reagen sesuai kolom inventaris resmi laboratorium BBPOM.
                </p>
            </div>
        </div>

        <div class="reagent-form-body">

            <?php if (validation_errors()): ?>
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <h6 class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i> Terjadi Kesalahan Input:</h6>
                    <?php echo validation_errors('<div class="small">- ', '</div>'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php echo form_open('reagen/tambah', array('id' => 'form-tambah-reagen')); ?>

                <div class="row">
                    <!-- 1. Kode Barang -->
                    <div class="col-md-6 form-group-custom">
                        <label for="kode_barang" class="form-label-custom">
                            Kode Barang (Inventaris)
                        </label>
                        <input type="text" name="kode_barang" id="kode_barang" class="form-control-custom"
                               value="<?php echo set_value('kode_barang'); ?>"
                               placeholder="Contoh: BRG-0001, 101010101" maxlength="50">
                        <div class="field-hint">Kode inventaris barang internal (opsional).</div>
                    </div>

                    <!-- 2. Katalog -->
                    <div class="col-md-6 form-group-custom">
                        <label for="katalog" class="form-label-custom">
                            Nomor Katalog
                        </label>
                        <input type="text" name="katalog" id="katalog" class="form-control-custom"
                               value="<?php echo set_value('katalog'); ?>"
                               placeholder="Contoh: 1.00983.1000, KAT-002" maxlength="100">
                        <div class="field-hint">Nomor katalog produk resmi dari produsen.</div>
                    </div>
                </div>

                <!-- 3. Nama Barang (Wajib) -->
                <div class="form-group-custom">
                    <label for="nama_barang" class="form-label-custom">
                        Nama Barang / Reagen <span class="required-mark">*</span>
                    </label>
                    <input type="text" name="nama_barang" id="nama_barang" class="form-control-custom"
                           value="<?php echo set_value('nama_barang'); ?>"
                           placeholder="Contoh: Asam Sulfat H2SO4 p.a 98%, Nutrient Agar (NA)" maxlength="255" required>
                    <div class="field-hint">Nama lengkap barang atau reagen laboratorium.</div>
                </div>

                <div class="row">
                    <!-- 4. Merk -->
                    <div class="col-md-6 form-group-custom">
                        <label for="merk" class="form-label-custom">
                            Merk / Produsen
                        </label>
                        <input type="text" name="merk" id="merk" class="form-control-custom"
                               value="<?php echo set_value('merk'); ?>"
                               placeholder="Contoh: Merck, Smart-Lab, Oxoid, Sigma-Aldrich" maxlength="150">
                    </div>

                    <!-- 5. Jenis Barang -->
                    <div class="col-md-6 form-group-custom">
                        <label for="jenis_barang" class="form-label-custom">
                            Jenis Barang (Kategori)
                        </label>
                        <input type="text" name="jenis_barang" id="jenis_barang" class="form-control-custom"
                               value="<?php echo set_value('jenis_barang', 'Reagen Cair'); ?>"
                               placeholder="Contoh: Reagen Cair, Media Mikrobiologi, Baku Pembanding" maxlength="100">
                    </div>
                </div>

                <div class="row">
                    <!-- 6. Stok (Kuantitas & Satuan) -->
                    <div class="col-md-6 form-group-custom">
                        <label class="form-label-custom">
                            Stok (Jumlah &amp; Satuan)
                        </label>
                        <div class="input-group">
                            <input type="number" name="stok_jumlah" id="stok_jumlah" class="form-control"
                                   value="<?php echo set_value('stok_jumlah', '0'); ?>" min="0" step="1" placeholder="0">
                            <input type="text" name="stok_satuan" id="stok_satuan" class="form-control"
                                   value="<?php echo set_value('stok_satuan', 'BOTOL'); ?>" placeholder="PAC, BOTOL, PCS, KTK">
                        </div>
                        <div class="field-hint">Contoh: 1 PAC, 3 PAC, 0 BOTOL, 0 PCS, 0 KTK.</div>
                    </div>

                    <!-- 7. Expired Date -->
                    <div class="col-md-6 form-group-custom">
                        <label for="expired_date" class="form-label-custom">
                            Expired Date (Kedaluwarsa)
                        </label>
                        <input type="date" name="expired_date" id="expired_date" class="form-control-custom"
                               value="<?php echo set_value('expired_date'); ?>">
                        <div class="field-hint">Format YYYY-MM-DD. Boleh dikosongkan jika tidak ada.</div>
                    </div>
                </div>

                <!-- 8. Status -->
                <div class="form-group-custom">
                    <label for="status" class="form-label-custom">
                        Status Ketersediaan
                    </label>
                    <select name="status" id="status" class="form-select-custom">
                        <option value="Aktif" <?php echo set_select('status', 'Aktif', TRUE); ?>>Aktif (Dapat Digunakan di Pengujian)</option>
                        <option value="Nonaktif" <?php echo set_select('status', 'Nonaktif'); ?>>Nonaktif</option>
                    </select>
                </div>

                <!-- Actions -->
                <div class="form-actions">
                    <a href="<?php echo site_url('reagen'); ?>" class="btn-cancel">
                        Batal
                    </a>
                    <button type="submit" class="btn-submit">
                        <i class="bi bi-save"></i>
                        Simpan Reagen
                    </button>
                </div>

            <?php echo form_close(); ?>

        </div>
    </div>

</div>
