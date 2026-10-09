<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
    /* =========================================================
       EDIT PERAN
       Konsisten dengan halaman Tambah Peran Baru
       Perubahan tampilan saja
       ========================================================= */

    .role-edit-page {
        width: 100%;
        padding: 4px 0 20px;
        color: #294257;
        font-size: .9rem;
    }

    .role-edit-card {
        width: 100%;
        max-width: 700px;
        margin: 0 auto;
        overflow: hidden;
        background: #fff;
        border: 1px solid #e0e8ed;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(35, 58, 76, .05);
    }

    .role-edit-header {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 18px 22px;
        border-bottom: 1px solid #e7edf0;
        background: #fff;
    }

    .role-edit-header-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        flex-shrink: 0;
        border-radius: 8px;
        background: #e5f6f4;
        color: #159b98;
        font-size: 1.15rem;
    }

    .role-edit-title {
        margin: 0;
        color: #294257;
        font-size: 1.08rem;
        font-weight: 700;
        line-height: 1.4;
    }

    .role-edit-subtitle {
        margin: 4px 0 0;
        color: #8797a1;
        font-size: .8rem;
        line-height: 1.5;
    }

    .role-edit-body {
        padding: 23px 22px 21px;
    }

    .role-edit-page .alert {
        margin-bottom: 20px;
        padding: 12px 15px;
        border-radius: 7px;
        font-size: .85rem;
        line-height: 1.6;
    }

    .role-edit-page .form-label {
        display: block;
        margin-bottom: 7px;
        color: #435d6e;
        font-size: .88rem;
        font-weight: 700;
    }

    .role-edit-page .form-control {
        min-height: 42px;
        padding: 10px 12px;
        border: 1px solid #dce5e9;
        border-radius: 7px;
        background-color: #fff;
        color: #40596a;
        font-size: .86rem;
        box-shadow: none;
        transition: border-color .18s ease, box-shadow .18s ease;
    }

    .role-edit-page .form-control::placeholder {
        color: #a0adb5;
        font-size: .83rem;
    }

    .role-edit-page .form-control:focus {
        border-color: #8fcac6;
        box-shadow: 0 0 0 3px rgba(21, 155, 152, .08);
    }

    .role-edit-page textarea.form-control {
        min-height: 105px;
        resize: vertical;
        line-height: 1.6;
    }

    .role-edit-required {
        color: #dc6262;
    }

    .role-edit-footer {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 24px;
        padding-top: 18px;
        border-top: 1px solid #edf1f3;
    }

    .role-edit-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 39px;
        padding: 9px 16px;
        border-radius: 7px;
        font-size: .83rem;
        font-weight: 700;
        text-decoration: none;
        transition: .18s ease;
        cursor: pointer;
    }

    .role-edit-btn-cancel {
        border: 1px solid #d8e1e6;
        background: #fff;
        color: #657987;
    }

    .role-edit-btn-cancel:hover {
        border-color: #c5d3da;
        background: #f7f9fa;
        color: #435d6e;
    }

    .role-edit-btn-submit {
        border: 1px solid #159b98;
        background: #159b98;
        color: #fff;
    }

    .role-edit-btn-submit:hover {
        border-color: #118b88;
        background: #118b88;
        color: #fff;
        box-shadow: 0 3px 8px rgba(21, 155, 152, .15);
    }

    @media (max-width: 575.98px) {
        .role-edit-card {
            max-width: 100%;
        }

        .role-edit-header {
            padding: 16px;
            gap: 10px;
        }

        .role-edit-header-icon {
            width: 36px;
            height: 36px;
            font-size: 1rem;
        }

        .role-edit-title {
            font-size: 1rem;
        }

        .role-edit-subtitle {
            font-size: .76rem;
        }

        .role-edit-body {
            padding: 19px 16px;
        }

        .role-edit-page .form-label {
            font-size: .85rem;
        }

        .role-edit-page .form-control {
            font-size: .83rem;
        }

        .role-edit-footer {
            gap: 8px;
        }

        .role-edit-btn {
            flex: 1;
            padding-right: 9px;
            padding-left: 9px;
            font-size: .8rem;
        }
    }
</style>

<div class="role-edit-page">

    <div class="role-edit-card">

        <!-- Header -->
        <div class="role-edit-header">
            <div class="role-edit-header-icon">
                <i class="bi bi-pencil-square"></i>
            </div>

            <div>
                <h5 class="role-edit-title">Edit Peran</h5>
                <p class="role-edit-subtitle">
                    Perbarui nama peran dan keterangan tanggung jawab dalam sistem.
                </p>
            </div>
        </div>

        <!-- Form -->
        <div class="role-edit-body">

            <?php if (validation_errors()): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-start gap-2">
                        <i class="bi bi-exclamation-circle-fill mt-1"></i>
                        <div>
                            <?php echo validation_errors('<div>', '</div>'); ?>
                        </div>
                    </div>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Close"
                    ></button>
                </div>
            <?php endif; ?>

            <form action="<?php echo site_url('peran/edit/' . $role['id']); ?>" method="post">

                <input
                    type="hidden"
                    name="<?php echo $this->security->get_csrf_token_name(); ?>"
                    value="<?php echo $this->security->get_csrf_hash(); ?>"
                >

                <!-- Nama Peran -->
                <div class="mb-4">
                    <label for="nama_role" class="form-label">
                        Nama Peran / Role
                        <span class="role-edit-required">*</span>
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        id="nama_role"
                        name="nama_role"
                        value="<?php echo set_value('nama_role', $role['nama_role']); ?>"
                        placeholder="Contoh: Admin TI, Penguji Kimia, Penyelia"
                        required
                    >
                </div>

                <!-- Keterangan -->
                <div class="mb-3">
                    <label for="keterangan" class="form-label">
                        Keterangan / Deskripsi
                        <span class="text-muted fw-normal">(Opsional)</span>
                    </label>

                    <textarea
                        class="form-control"
                        id="keterangan"
                        name="keterangan"
                        rows="3"
                        placeholder="Penjelasan tugas dan tanggung jawab peran"
                    ><?php echo set_value('keterangan', $role['keterangan']); ?></textarea>
                </div>

                <!-- Actions -->
                <div class="role-edit-footer">
                    <a
                        href="<?php echo site_url('peran'); ?>"
                        class="role-edit-btn role-edit-btn-cancel"
                    >
                        <i class="bi bi-arrow-left"></i>
                        Batal
                    </a>

                    <button
                        type="submit"
                        class="role-edit-btn role-edit-btn-submit"
                    >
                        <i class="bi bi-check2-circle"></i>
                        Simpan Perubahan
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>