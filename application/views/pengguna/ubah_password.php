<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
    .password-edit-page {
        width: 100%;
        padding: 4px 0 20px;
        color: #294257;
        font-size: .9rem;
    }

    .password-edit-card {
        width: 100%;
        max-width: 600px;
        margin: 0 auto;
        overflow: hidden;
        background: #fff;
        border: 1px solid #e0e8ed;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(41, 66, 87, .05);
    }

    .password-edit-header {
        display: flex;
        align-items: center;
        gap: 13px;
        padding: 18px 22px;
        background: #fff;
        border-bottom: 1px solid #e8eff2;
    }

    .password-edit-header-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 42px;
        width: 42px;
        height: 42px;
        color: #159b98;
        background: #e5f6f4;
        border-radius: 10px;
        font-size: 1.2rem;
    }

    .password-edit-title {
        margin: 0;
        color: #294257;
        font-size: 1.08rem;
        font-weight: 700;
        line-height: 1.4;
        overflow-wrap: anywhere;
    }

    .password-edit-subtitle {
        margin: 3px 0 0;
        color: #8495a3;
        font-size: .8rem;
        line-height: 1.5;
    }

    .password-edit-body {
        padding: 23px 22px 21px;
    }

    .password-edit-info {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 22px;
        padding: 12px 14px;
        color: #426b78;
        background: #f0f9f8;
        border: 1px solid #d9efed;
        border-radius: 8px;
        font-size: .82rem;
        line-height: 1.6;
    }

    .password-edit-info i {
        margin-top: 2px;
        color: #159b98;
        font-size: 1rem;
    }

    .password-edit-page .form-label {
        display: block;
        margin-bottom: 7px;
        color: #40596d;
        font-size: .85rem;
        font-weight: 600;
    }

    .password-edit-page .required-mark {
        color: #dc5454;
    }

    .password-edit-page .form-control {
        min-height: 42px;
        padding: 9px 12px;
        color: #294257;
        background: #fff;
        border: 1px solid #dce5ea;
        border-radius: 7px;
        font-size: .86rem;
        box-shadow: none;
        transition: border-color .2s ease, box-shadow .2s ease;
    }

    .password-edit-page .form-control::placeholder {
        color: #a3b0ba;
    }

    .password-edit-page .form-control:focus {
        border-color: #159b98;
        box-shadow: 0 0 0 3px rgba(21, 155, 152, .11);
    }

    .password-edit-page .field-hint {
        display: block;
        margin-top: 6px;
        color: #8999a5;
        font-size: .76rem;
        line-height: 1.5;
    }

    .password-edit-page .alert {
        margin-bottom: 20px;
        padding: 12px 15px;
        border-radius: 8px;
        font-size: .84rem;
        line-height: 1.6;
    }

    .password-edit-page .alert-danger {
        color: #9c3434;
        background: #fff5f5;
        border: 1px solid #f4d6d6;
    }

    .password-edit-page .form-footer {
        display: flex;
        justify-content: flex-end;
        flex-wrap: wrap;
        gap: 9px;
        padding-top: 21px;
        margin-top: 24px;
        border-top: 1px solid #e8eff2;
    }

    .password-edit-page .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 39px;
        padding: 9px 15px;
        border-radius: 7px;
        font-size: .83rem;
        font-weight: 600;
        text-decoration: none;
        transition: all .2s ease;
    }

    .password-edit-page .btn-cancel {
        color: #536777;
        background: #fff;
        border: 1px solid #dce5ea;
    }

    .password-edit-page .btn-cancel:hover {
        color: #294257;
        background: #f5f8fa;
        border-color: #cbd8df;
    }

    .password-edit-page .btn-reset {
        color: #fff;
        background: #159b98;
        border: 1px solid #159b98;
    }

    .password-edit-page .btn-reset:hover {
        color: #fff;
        background: #118582;
        border-color: #118582;
        box-shadow: 0 3px 8px rgba(21, 155, 152, .18);
    }

    @media (max-width: 575.98px) {
        .password-edit-page {
            padding-top: 0;
        }

        .password-edit-header {
            padding: 16px;
            gap: 10px;
        }

        .password-edit-header-icon {
            flex-basis: 38px;
            width: 38px;
            height: 38px;
            font-size: 1.05rem;
        }

        .password-edit-title {
            font-size: 1rem;
        }

        .password-edit-subtitle {
            font-size: .76rem;
        }

        .password-edit-body {
            padding: 19px 16px;
        }

        .password-edit-page .form-footer {
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        .password-edit-page .form-footer .btn {
            width: 100%;
            padding-right: 8px;
            padding-left: 8px;
            font-size: .8rem;
        }
    }
</style>

<div class="password-edit-page">
    <div class="password-edit-card">

        <!-- Header -->
        <div class="password-edit-header">
            <div class="password-edit-header-icon">
                <i class="bi bi-shield-lock"></i>
            </div>

            <div>
                <h5 class="password-edit-title">
                    Ubah Kata Sandi
                </h5>
                <p class="password-edit-subtitle">
                    Akun: <?php echo html_escape($user['username']); ?>
                </p>
            </div>
        </div>

        <!-- Form -->
        <div class="password-edit-body">

            <div class="password-edit-info">
                <i class="bi bi-info-circle-fill"></i>
                <div>
                    Buat kata sandi baru untuk akun ini. Pastikan kata sandi baru
                    minimal 6 karakter dan masukkan kembali pada kolom konfirmasi.
                </div>
            </div>

            <?php if (validation_errors()): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-start gap-2">
                        <i class="bi bi-exclamation-circle-fill mt-1"></i>
                        <div>
                            <strong>Periksa kembali kata sandi yang dimasukkan.</strong>
                            <div class="mt-1">
                                <?php echo validation_errors('<div>', '</div>'); ?>
                            </div>
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

            <form
                action="<?php echo site_url('pengguna/password/' . $user['id']); ?>"
                method="post"
            >

                <input
                    type="hidden"
                    name="<?php echo $this->security->get_csrf_token_name(); ?>"
                    value="<?php echo $this->security->get_csrf_hash(); ?>"
                >

                <!-- Kata Sandi Baru -->
                <div class="mb-4">
                    <label for="password_baru" class="form-label">
                        Kata Sandi Baru
                        <span class="required-mark">*</span>
                    </label>

                    <input
                        type="password"
                        class="form-control"
                        id="password_baru"
                        name="password_baru"
                        placeholder="Masukkan kata sandi baru"
                        required
                    >

                    <small class="field-hint">
                        Gunakan kata sandi minimal 6 karakter.
                    </small>
                </div>

                <!-- Konfirmasi Kata Sandi -->
                <div class="mb-3">
                    <label for="konfirmasi_password" class="form-label">
                        Konfirmasi Kata Sandi Baru
                        <span class="required-mark">*</span>
                    </label>

                    <input
                        type="password"
                        class="form-control"
                        id="konfirmasi_password"
                        name="konfirmasi_password"
                        placeholder="Ulangi kata sandi baru"
                        required
                    >
                </div>

                <!-- Tombol Aksi -->
                <div class="form-footer">
                    <a
                        href="<?php echo site_url('pengguna'); ?>"
                        class="btn btn-cancel"
                    >
                        <i class="bi bi-arrow-left"></i>
                        Batal
                    </a>

                    <button type="submit" class="btn btn-reset">
                        <i class="bi bi-shield-check"></i>
                        Reset Kata Sandi
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>