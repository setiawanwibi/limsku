<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
    .user-create-page {
        width: 100%;
        padding: 4px 0 20px;
        color: #294257;
        font-size: .9rem;
    }

    .user-create-card {
        width: 100%;
        max-width: 700px;
        margin: 0 auto;
        background: #fff;
        border: 1px solid #e0e8ed;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 16px rgba(41, 66, 87, .05);
    }

    .user-create-header {
        display: flex;
        align-items: center;
        gap: 13px;
        padding: 18px 22px;
        background: #fff;
        border-bottom: 1px solid #e8eff2;
    }

    .user-create-header-icon {
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

    .user-create-title {
        margin: 0;
        color: #294257;
        font-size: 1.08rem;
        font-weight: 700;
        line-height: 1.4;
    }

    .user-create-subtitle {
        margin: 3px 0 0;
        color: #8495a3;
        font-size: .8rem;
        line-height: 1.5;
    }

    .user-create-body {
        padding: 23px 22px 21px;
    }

    .user-create-section-title {
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 0 0 16px;
        color: #294257;
        font-size: .91rem;
        font-weight: 700;
    }

    .user-create-section-title i {
        color: #159b98;
        font-size: 1rem;
    }

    .user-create-divider {
        height: 1px;
        margin: 21px 0;
        background: #e8eff2;
        border: 0;
    }

    .user-create-page .form-label {
        display: block;
        margin-bottom: 7px;
        color: #40596d;
        font-size: .85rem;
        font-weight: 600;
    }

    .user-create-page .required-mark {
        color: #dc5454;
    }

    .user-create-page .form-control,
    .user-create-page .form-select {
        min-height: 42px;
        padding: 9px 12px;
        color: #294257;
        background-color: #fff;
        border: 1px solid #dce5ea;
        border-radius: 7px;
        font-size: .86rem;
        box-shadow: none;
        transition: border-color .2s ease, box-shadow .2s ease;
    }

    .user-create-page .form-control::placeholder {
        color: #a3b0ba;
    }

    .user-create-page .form-control:focus,
    .user-create-page .form-select:focus {
        border-color: #159b98;
        box-shadow: 0 0 0 3px rgba(21, 155, 152, .11);
    }

    .user-create-page .form-text {
        margin-top: 5px;
        color: #8999a5;
        font-size: .76rem;
    }

    .user-create-page .alert {
        margin-bottom: 20px;
        padding: 12px 15px;
        border-radius: 8px;
        font-size: .84rem;
    }

    .user-create-page .alert-danger {
        color: #9c3434;
        background: #fff5f5;
        border: 1px solid #f4d6d6;
    }

    .user-create-page .alert-danger div + div {
        margin-top: 3px;
    }

    .user-create-page .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 39px;
        padding: 9px 15px;
        border-radius: 7px;
        font-size: .83rem;
        font-weight: 600;
        transition: all .2s ease;
    }

    .user-create-page .btn-cancel {
        color: #536777;
        background: #fff;
        border: 1px solid #dce5ea;
    }

    .user-create-page .btn-cancel:hover {
        color: #294257;
        background: #f5f8fa;
        border-color: #cbd8df;
    }

    .user-create-page .btn-save {
        color: #fff;
        background: #159b98;
        border: 1px solid #159b98;
    }

    .user-create-page .btn-save:hover {
        color: #fff;
        background: #118582;
        border-color: #118582;
        box-shadow: 0 3px 8px rgba(21, 155, 152, .18);
    }

    .user-create-page .form-footer {
        display: flex;
        justify-content: flex-end;
        flex-wrap: wrap;
        gap: 9px;
        padding-top: 21px;
        margin-top: 23px;
        border-top: 1px solid #e8eff2;
    }

    .user-create-page .field-hint {
        display: block;
        margin-top: 5px;
        color: #8999a5;
        font-size: .76rem;
    }

    @media (max-width: 575px) {
        .user-create-page {
            padding-top: 0;
        }

        .user-create-header {
            padding: 16px;
        }

        .user-create-header-icon {
            flex-basis: 38px;
            width: 38px;
            height: 38px;
        }

        .user-create-body {
            padding: 19px 16px;
        }

        .user-create-page .form-footer {
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        .user-create-page .form-footer .btn {
            width: 100%;
        }
    }
</style>

<div class="user-create-page">
    <div class="user-create-card">

        <div class="user-create-header">
            <div class="user-create-header-icon">
                <i class="bi bi-person-plus"></i>
            </div>

            <div>
                <h5 class="user-create-title">Tambah Pengguna Baru</h5>
                <p class="user-create-subtitle">
                    Tambahkan akun pengguna dan atur peran serta akses laboratoriumnya.
                </p>
            </div>
        </div>

        <div class="user-create-body">

            <?php if (validation_errors()): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-start gap-2">
                        <i class="bi bi-exclamation-circle-fill mt-1"></i>
                        <div>
                            <strong>Periksa kembali data yang dimasukkan.</strong>
                            <div class="mt-1">
                                <?php echo validation_errors('<div>', '</div>'); ?>
                            </div>
                        </div>
                    </div>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form action="<?php echo site_url('pengguna/tambah'); ?>" method="post">

                <input type="hidden"
                       name="<?php echo $this->security->get_csrf_token_name(); ?>"
                       value="<?php echo $this->security->get_csrf_hash(); ?>">

                <!-- Informasi Pengguna -->
                <div class="user-create-section-title">
                    <i class="bi bi-person-vcard"></i>
                    <span>Informasi Pengguna</span>
                </div>

                <div class="row g-3">

                    <div class="col-md-6">
                        <label for="username" class="form-label">
                            Nama Pengguna / Username
                            <span class="required-mark">*</span>
                        </label>

                        <input type="text"
                               class="form-control"
                               id="username"
                               name="username"
                               value="<?php echo set_value('username'); ?>"
                               placeholder="Contoh: analis_kimia1"
                               required>
                    </div>

                    <div class="col-md-6">
                        <label for="nip" class="form-label">
                            NIP <span class="text-muted fw-normal">(Opsional)</span>
                        </label>

                        <input type="text"
                               class="form-control"
                               id="nip"
                               name="nip"
                               value="<?php echo set_value('nip'); ?>"
                               placeholder="Masukkan NIP jika ada">
                    </div>

                    <div class="col-md-6">
                        <label for="nama_lengkap" class="form-label">
                            Nama Lengkap
                            <span class="required-mark">*</span>
                        </label>

                        <input type="text"
                               class="form-control"
                               id="nama_lengkap"
                               name="nama_lengkap"
                               value="<?php echo set_value('nama_lengkap'); ?>"
                               placeholder="Masukkan nama lengkap beserta gelar"
                               required>
                    </div>

                    <div class="col-md-6">
                        <label for="email" class="form-label">
                            Email <span class="text-muted fw-normal">(Opsional)</span>
                        </label>

                        <input type="email"
                               class="form-control"
                               id="email"
                               name="email"
                               value="<?php echo set_value('email'); ?>"
                               placeholder="nama@bpom.go.id">
                    </div>

                </div>

                <hr class="user-create-divider">

                <!-- Pengaturan Akun -->
                <div class="user-create-section-title">
                    <i class="bi bi-shield-lock"></i>
                    <span>Pengaturan Akun</span>
                </div>

                <div class="row g-3">

                    <div class="col-md-6">
                        <label for="password" class="form-label">
                            Kata Sandi
                            <span class="required-mark">*</span>
                        </label>

                        <input type="password"
                               class="form-control"
                               id="password"
                               name="password"
                               placeholder="Minimal 6 karakter"
                               required>

                        <small class="field-hint">
                            Gunakan kata sandi minimal 6 karakter.
                        </small>
                    </div>

                    <div class="col-md-6">
                        <label for="role_id" class="form-label">
                            Peran / Role
                            <span class="required-mark">*</span>
                        </label>

                        <select class="form-select" id="role_id" name="role_id" required>
                            <option value="">-- Pilih Peran --</option>

                            <?php if (!empty($daftar_role)): ?>
                                <?php foreach ($daftar_role as $r): ?>
                                    <option value="<?php echo $r['id']; ?>"
                                        <?php echo set_select('role_id', $r['id']); ?>>
                                        <?php echo html_escape($r['nama_role']); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="laboratory_id" class="form-label">
                            Laboratorium
                            <span class="text-muted fw-normal">(Opsional)</span>
                        </label>

                        <select class="form-select"
                                id="laboratory_id"
                                name="laboratory_id">
                            <option value="">-- Seluruh Lab / Non-Lab --</option>

                            <?php if (!empty($daftar_laboratorium)): ?>
                                <?php foreach ($daftar_laboratorium as $lab): ?>
                                    <option value="<?php echo $lab['id']; ?>"
                                        <?php echo set_select('laboratory_id', $lab['id']); ?>>
                                        <?php echo html_escape($lab['nama_lab']); ?>
                                        (<?php echo html_escape($lab['kode_lab']); ?>)
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="status" class="form-label">Status Akun</label>

                        <select class="form-select" id="status" name="status">
                            <option value="Aktif"
                                <?php echo set_select('status', 'Aktif', TRUE); ?>>
                                Aktif
                            </option>

                            <option value="Nonaktif"
                                <?php echo set_select('status', 'Nonaktif'); ?>>
                                Nonaktif
                            </option>
                        </select>
                    </div>

                </div>

                <!-- Tombol Aksi -->
                <div class="form-footer">
                    <a href="<?php echo site_url('pengguna'); ?>"
                       class="btn btn-cancel">
                        <i class="bi bi-arrow-left"></i>
                        Batal
                    </a>

                    <button type="submit" class="btn btn-save">
                        <i class="bi bi-check2-circle"></i>
                        Simpan Pengguna
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>