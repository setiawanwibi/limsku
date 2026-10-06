<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="card border-0 shadow-sm" style="max-width: 800px;">
    <div class="card-header bg-white py-3">
        <h5 class="fw-bold mb-0 text-dark">Edit Pengguna</h5>
    </div>
    <div class="card-body">
        <?php if (validation_errors()): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo validation_errors('<div>', '</div>'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <form action="<?php echo site_url('pengguna/edit/' . $user['id']); ?>" method="post">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="username" class="form-label fw-semibold">Nama Pengguna / Username <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="username" name="username" value="<?php echo set_value('username', $user['username']); ?>" required>
                </div>

                <div class="col-md-6">
                    <label for="nip" class="form-label fw-semibold">NIP (Opsional)</label>
                    <input type="text" class="form-control" id="nip" name="nip" value="<?php echo set_value('nip', $user['nip']); ?>">
                </div>

                <div class="col-md-6">
                    <label for="nama_lengkap" class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="<?php echo set_value('nama_lengkap', $user['nama_lengkap']); ?>" required>
                </div>

                <div class="col-md-6">
                    <label for="email" class="form-label fw-semibold">Email (Opsional)</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo set_value('email', $user['email']); ?>">
                </div>

                <div class="col-md-6">
                    <label for="password" class="form-label fw-semibold">Kata Sandi Baru (Kosongkan jika tidak diubah)</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Isi hanya jika ingin mengubah">
                </div>

                <div class="col-md-6">
                    <label for="role_id" class="form-label fw-semibold">Peran / Role <span class="text-danger">*</span></label>
                    <select class="form-select" id="role_id" name="role_id" required>
                        <option value="">-- Pilih Peran --</option>
                        <?php if (!empty($daftar_role)): ?>
                            <?php foreach ($daftar_role as $r): ?>
                                <option value="<?php echo $r['id']; ?>" <?php echo set_select('role_id', $r['id'], ($user['role_id'] == $r['id'])); ?>>
                                    <?php echo html_escape($r['nama_role']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="laboratory_id" class="form-label fw-semibold">Laboratorium (Opsional)</label>
                    <select class="form-select" id="laboratory_id" name="laboratory_id">
                        <option value="">-- Seluruh Lab / Non-Lab --</option>
                        <?php if (!empty($daftar_laboratorium)): ?>
                            <?php foreach ($daftar_laboratorium as $lab): ?>
                                <option value="<?php echo $lab['id']; ?>" <?php echo set_select('laboratory_id', $lab['id'], ($user['laboratory_id'] == $lab['id'])); ?>>
                                    <?php echo html_escape($lab['nama_lab']); ?> (<?php echo html_escape($lab['kode_lab']); ?>)
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="status" class="form-label fw-semibold">Status Akun</label>
                    <select class="form-select" id="status" name="status">
                        <option value="Aktif" <?php echo set_select('status', 'Aktif', ($user['status'] === 'Aktif')); ?>>Aktif</option>
                        <option value="Nonaktif" <?php echo set_select('status', 'Nonaktif', ($user['status'] === 'Nonaktif')); ?>>Nonaktif</option>
                    </select>
                </div>
            </div>

            <div class="mt-4 d-flex justify-content-end gap-2">
                <a href="<?php echo site_url('pengguna'); ?>" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
