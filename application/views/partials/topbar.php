<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
// Calculate notification badge count based on user role using Model_Notifikasi
$notif_count = 0;
$notif_target = site_url('dasbor');
if ($this->session->userdata('logged_in')) {
    if (!isset($badges)) {
        $ci =& get_instance();
        $ci->load->model('Model_Notifikasi');
        $badges = $ci->Model_Notifikasi->ambil_semua_badge();
    }
    $notif_count = !empty($badges['total_tindakan']) ? $badges['total_tindakan'] : 0;
    
    // Tentukan shortcut target jika tombol notifikasi topbar diklik
    if (!empty($badges['pengujian_verifikasi'])) {
        $notif_target = site_url('pengujian/verifikasi');
    } elseif (!empty($badges['pengujian_approval'])) {
        $notif_target = site_url('pengujian/approval');
    } elseif (!empty($badges['pengujian_antrean'])) {
        $notif_target = site_url('pengujian/antrean');
    } elseif (!empty($badges['sampel'])) {
        $notif_target = site_url('sampel');
    } elseif (!empty($badges['pengujian_riwayat'])) {
        $notif_target = site_url('pengujian/riwayat');
    } elseif (!empty($badges['akun'])) {
        $notif_target = site_url('akun');
    }
}
?>
<nav class="navbar navbar-expand-lg lims-topbar bg-white border-bottom px-3 py-2">
    <div class="container-fluid p-0 d-flex align-items-center">
        <button type="button" id="sidebar-toggle-open" class="sidebar-toggle-btn me-3" title="Buka Sidebar" aria-label="Buka Sidebar">
            <i class="bi bi-list fs-4"></i>
        </button>

        <span class="navbar-text fw-semibold text-dark fs-6">
            <?php echo isset($judul_halaman) && !empty($judul_halaman) ? html_escape($judul_halaman) : 'Laboratory Information Management System'; ?>
        </span>

        <div class="d-flex align-items-center ms-auto">
            <?php if ($this->session->userdata('logged_in')): ?>
                <!-- Notifikasi "Belum dibaca" Badge -->
                <a href="<?php echo $notif_target; ?>" class="d-inline-flex align-items-center text-decoration-none me-4 px-3 py-1 rounded-pill" style="background-color: #f0f7ff; border: 1px solid #d0e4ff; transition: all 0.2s;">
                    <span class="text-secondary fw-semibold small me-2" style="color: #475569 !important;">Belum dibaca</span>
                    <span class="badge rounded-pill fw-bold px-2 py-1" style="background: linear-gradient(135deg, #67b0f8 0%, #4690e8 100%); color: #ffffff !important; font-size: 0.78rem; box-shadow: 0 1px 3px rgba(70, 144, 232, 0.35);">
                        <?php echo $notif_count; ?>
                    </span>
                </a>

                <a href="<?php echo site_url('akun'); ?>" class="d-flex align-items-center me-3 text-decoration-none" title="Kelola Profil & TTD Digital">
                    <div class="bg-light text-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 36px; height: 36px; font-weight: 700;">
                        <?php echo strtoupper(substr($this->session->userdata('nama_lengkap') ?: 'U', 0, 1)); ?>
                    </div>
                    <div class="text-end me-1">
                        <span class="d-block fw-bold text-dark small leading-none">
                            <?php echo html_escape($this->session->userdata('nama_lengkap')); ?>
                        </span>
                        <span class="badge bg-light text-secondary border mt-1" style="font-size: 0.68rem;">
                            <?php echo html_escape($this->session->userdata('role_name')); ?>
                        </span>
                    </div>
                </a>
                <a href="<?php echo site_url('akun'); ?>" class="btn btn-sm btn-outline-secondary me-2 fw-semibold" title="Profil & TTD Digital">
                    <i class="bi bi-person-gear"></i>
                </a>
                <a href="<?php echo site_url('keluar'); ?>" class="btn btn-sm btn-outline-danger fw-semibold" onclick="return confirm('Apakah Anda yakin ingin keluar dari sistem?');">
                    <i class="bi bi-box-arrow-right me-1"></i> Keluar
                </a>
            <?php else: ?>
                <a href="<?php echo site_url('masuk'); ?>" class="btn btn-sm btn-primary fw-semibold">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
                </a>
            <?php endif; ?>
        </div>
    </div>
</nav>
