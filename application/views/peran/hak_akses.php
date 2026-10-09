<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<style>
    /* =========================================================
       PENGATURAN HAK AKSES
       Konsisten dengan halaman Edit Peran
       Perubahan tampilan saja
       ========================================================= */

    .role-access-page {
        color: #294257;
        font-size: .9rem;
    }

    .role-access-card {
        width: 100%;
        overflow: hidden;
        background: #fff;
        border: 1px solid #e0e8ed;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(35, 58, 76, .05);
    }

    .role-access-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        padding: 18px 22px;
        border-bottom: 1px solid #e7edf0;
        background: #fff;
    }

    .role-access-heading {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
    }

    .role-access-header-icon {
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

    .role-access-title {
        margin: 0;
        color: #294257;
        font-size: 1.08rem;
        font-weight: 700;
        line-height: 1.4;
    }

    .role-access-role-name {
        color: #159b98;
    }

    .role-access-subtitle {
        margin: 4px 0 0;
        color: #8797a1;
        font-size: .8rem;
        line-height: 1.5;
    }

    .role-access-body {
        padding: 23px 22px 21px;
    }

    .role-access-page .btn {
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
    }

    .role-access-btn-back {
        flex-shrink: 0;
        border: 1px solid #d8e1e6;
        background: #fff;
        color: #657987;
    }

    .role-access-btn-back:hover {
        border-color: #c5d3da;
        background: #f7f9fa;
        color: #435d6e;
    }

    /* Kartu kategori permission */
    .role-access-category {
        height: 100%;
        overflow: hidden;
        border: 1px solid #e0e8ed;
        border-radius: 9px;
        background: #fff;
        transition: border-color .18s ease, box-shadow .18s ease;
    }

    .role-access-category:hover {
        border-color: #cbdedc;
        box-shadow: 0 3px 10px rgba(35, 58, 76, .04);
    }

    .role-access-category-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        min-height: 49px;
        padding: 11px 14px;
        border-bottom: 1px solid #e7edf0;
        background: #f5faf9;
    }

    .role-access-category-title {
        margin: 0;
        color: #294257;
        font-size: .9rem;
        font-weight: 700;
        line-height: 1.4;
        overflow-wrap: anywhere;
    }

    .role-access-page .select-all-btn {
        min-height: auto;
        padding: 3px 0;
        border: 0;
        background: transparent;
        color: #159b98;
        font-size: .78rem;
        font-weight: 700;
        white-space: nowrap;
        text-decoration: none;
    }

    .role-access-page .select-all-btn:hover {
        color: #118b88;
        text-decoration: underline;
    }

    .role-access-category-body {
        padding: 15px;
    }

    /* Item permission */
    .role-access-page .form-check {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 13px;
        padding-left: 0;
    }

    .role-access-page .form-check:last-child {
        margin-bottom: 0;
    }

    .role-access-page .form-check-input {
        float: none;
        flex-shrink: 0;
        width: 17px;
        height: 17px;
        margin: 3px 0 0;
        border-color: #bdcbd2;
        cursor: pointer;
    }

    .role-access-page .form-check-input:checked {
        border-color: #159b98;
        background-color: #159b98;
    }

    .role-access-page .form-check-input:focus {
        border-color: #8fcac6;
        box-shadow: 0 0 0 3px rgba(21, 155, 152, .1);
    }

    .role-access-page .form-check-label {
        min-width: 0;
        color: #40596a;
        font-size: .84rem;
        line-height: 1.5;
        cursor: pointer;
        overflow-wrap: anywhere;
    }

    .role-access-page .permission-description {
        display: block;
        margin-bottom: 3px;
        color: #294257;
        font-size: .86rem;
        font-weight: 600;
    }

    .role-access-page .permission-code {
        display: block;
        color: #8797a1;
        font-family: var(--bs-font-monospace);
        font-size: .74rem;
        line-height: 1.5;
        overflow-wrap: anywhere;
    }

    /* Notifikasi jika belum ada permission */
    .role-access-page .alert {
        margin-bottom: 0;
        padding: 14px 16px;
        border-radius: 8px;
        font-size: .85rem;
        line-height: 1.6;
    }

    /* Bagian tombol bawah */
    .role-access-footer {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 24px;
        padding-top: 18px;
        border-top: 1px solid #edf1f3;
    }

    .role-access-btn-cancel {
        border: 1px solid #d8e1e6;
        background: #fff;
        color: #657987;
    }

    .role-access-btn-cancel:hover {
        border-color: #c5d3da;
        background: #f7f9fa;
        color: #435d6e;
    }

    .role-access-btn-save {
        border: 1px solid #159b98;
        background: #159b98;
        color: #fff;
    }

    .role-access-btn-save:hover {
        border-color: #118b88;
        background: #118b88;
        color: #fff;
        box-shadow: 0 3px 8px rgba(21, 155, 152, .15);
    }

    @media (max-width: 767.98px) {
        .role-access-header {
            align-items: flex-start;
            flex-direction: column;
            padding: 16px;
        }

        .role-access-btn-back {
            align-self: flex-start;
        }

        .role-access-body {
            padding: 19px 16px;
        }
    }

    @media (max-width: 575.98px) {
        .role-access-heading {
            gap: 10px;
        }

        .role-access-header-icon {
            width: 36px;
            height: 36px;
            font-size: 1rem;
        }

        .role-access-title {
            font-size: 1rem;
        }

        .role-access-subtitle {
            font-size: .76rem;
        }

        .role-access-category-header {
            padding: 10px 12px;
        }

        .role-access-category-title {
            font-size: .85rem;
        }

        .role-access-category-body {
            padding: 13px;
        }

        .role-access-page .form-check-label {
            font-size: .82rem;
        }

        .role-access-page .permission-description {
            font-size: .84rem;
        }

        .role-access-page .permission-code {
            font-size: .72rem;
        }

        .role-access-footer {
            gap: 8px;
        }

        .role-access-footer .btn {
            flex: 1;
            padding-right: 9px;
            padding-left: 9px;
            font-size: .8rem;
        }
    }
</style>

<div class="role-access-page">

    <div class="role-access-card">

        <!-- Header -->
        <div class="role-access-header">

            <div class="role-access-heading">
                <div class="role-access-header-icon">
                    <i class="bi bi-shield-lock"></i>
                </div>

                <div>
                    <h5 class="role-access-title">
                        Pengaturan Hak Akses:
                        <span class="role-access-role-name">
                            <?php echo html_escape($role['nama_role']); ?>
                        </span>
                    </h5>

                    <p class="role-access-subtitle">
                        Atur izin akses yang dimiliki oleh peran ini.
                    </p>
                </div>
            </div>

            <a
                href="<?php echo site_url('peran'); ?>"
                class="btn role-access-btn-back"
            >
                <i class="bi bi-arrow-left"></i>
                Kembali ke Daftar Peran
            </a>

        </div>

        <!-- Content -->
        <div class="role-access-body">

            <form action="<?php echo site_url('peran/hak-akses/' . $role['id']); ?>" method="post">

                <input
                    type="hidden"
                    name="<?php echo $this->security->get_csrf_token_name(); ?>"
                    value="<?php echo $this->security->get_csrf_hash(); ?>"
                >

                <?php if (!empty($permissions_by_kategori)): ?>

                    <div class="row g-4">

                        <?php foreach ($permissions_by_kategori as $kategori => $items): ?>

                            <div class="col-md-6 col-lg-4">

                                <div class="role-access-category">

                                    <!-- Category Header -->
                                    <div class="role-access-category-header">

                                        <h6 class="role-access-category-title">
                                            <?php echo html_escape($kategori); ?>
                                        </h6>

                                        <button
                                            type="button"
                                            class="btn btn-link select-all-btn"
                                            data-target="cat-<?php echo url_title($kategori, '-', TRUE); ?>"
                                        >
                                            Pilih Semua
                                        </button>

                                    </div>

                                    <!-- Permission List -->
                                    <div class="role-access-category-body">

                                        <?php foreach ($items as $p): ?>

                                            <?php $checked = in_array($p['id'], $assigned_ids) ? 'checked' : ''; ?>

                                            <div class="form-check">

                                                <input
                                                    class="form-check-input cat-<?php echo url_title($kategori, '-', TRUE); ?>"
                                                    type="checkbox"
                                                    name="permissions[]"
                                                    value="<?php echo $p['id']; ?>"
                                                    id="perm_<?php echo $p['id']; ?>"
                                                    <?php echo $checked; ?>
                                                >

                                                <label
                                                    class="form-check-label"
                                                    for="perm_<?php echo $p['id']; ?>"
                                                >
                                                    <strong class="permission-description">
                                                        <?php echo html_escape($p['deskripsi']); ?>
                                                    </strong>

                                                    <small class="permission-code">
                                                        <?php echo html_escape($p['nama_permission']); ?>
                                                    </small>
                                                </label>

                                            </div>

                                        <?php endforeach; ?>

                                    </div>

                                </div>

                            </div>

                        <?php endforeach; ?>

                    </div>

                <?php else: ?>

                    <div class="alert alert-warning" role="alert">
                        <div class="d-flex align-items-start gap-2">
                            <i class="bi bi-exclamation-triangle-fill mt-1"></i>

                            <div>
                                Belum ada master permission di sistem. Silakan tambahkan Master Permission terlebih dahulu melalui menu Peran.
                            </div>
                        </div>
                    </div>

                <?php endif; ?>

                <!-- Actions -->
                <div class="role-access-footer">

                    <a
                        href="<?php echo site_url('peran'); ?>"
                        class="btn role-access-btn-cancel"
                    >
                        <i class="bi bi-arrow-left"></i>
                        Batal
                    </a>

                    <button
                        type="submit"
                        class="btn role-access-btn-save"
                    >
                        <i class="bi bi-check2-circle"></i>
                        Simpan Hak Akses
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.select-all-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var targetClass = this.getAttribute('data-target');
            var checkboxes = document.querySelectorAll('.' + targetClass);
            var allChecked = Array.from(checkboxes).every(cb => cb.checked);

            checkboxes.forEach(function(cb) {
                cb.checked = !allChecked;
            });

            this.textContent = allChecked ? 'Pilih Semua' : 'Batal Semua';
        });
    });
});
</script>