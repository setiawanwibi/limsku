<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
// Calculate notification badge count based on user role
$notif_count = 0;
$notif_target = '#';
if ($this->session->userdata('logged_in')) {
    $role_id_topbar = $this->session->userdata('role_id');
    $user_id_topbar = $this->session->userdata('user_id');
    $ci =& get_instance();
    
    if ($ci->Model_Hak_Akses->memiliki_akses($role_id_topbar, 'pengujian_input') 
        && !$ci->Model_Hak_Akses->memiliki_akses($role_id_topbar, 'pengujian_verify')) {
        // Penguji: count antrean sampel + pengujian ditolak yang perlu revisi
        $ci->db->where('status', 'Menunggu Pengujian');
        $c_antrean = $ci->db->count_all_results('samples');
        
        $ci->db->where('penguji_id', $user_id_topbar);
        $ci->db->where('status', 'Ditolak');
        $c_ditolak = $ci->db->count_all_results('testing_sessions');
        
        $notif_count = $c_antrean + $c_ditolak;
        $notif_target = site_url('pengujian/antrean');
    } elseif ($ci->Model_Hak_Akses->memiliki_akses($role_id_topbar, 'pengujian_verify')) {
        // Penyelia: count antrean verifikasi
        $ci->db->where('status', 'Menunggu Verifikasi');
        $notif_count = $ci->db->count_all_results('testing_sessions');
        $notif_target = site_url('pengujian/verifikasi');
    } elseif ($ci->Model_Hak_Akses->memiliki_akses($role_id_topbar, 'pengujian_approve')) {
        // Manajer Teknis: count antrean approval
        $ci->db->where('status', 'Menunggu Approval');
        $notif_count = $ci->db->count_all_results('testing_sessions');
        $notif_target = site_url('pengujian/approval');
    } elseif ($ci->Model_Hak_Akses->memiliki_akses($role_id_topbar, 'sampel_view')) {
        // Petugas Penerima Sampel: count sampel baru
        $ci->db->where('status', 'Menunggu Pengujian');
        $notif_count = $ci->db->count_all_results('samples');
        $notif_target = site_url('sampel');
    }
}
?>
<nav class="navbar navbar-expand-lg lims-topbar bg-white border-bottom px-3 py-2">
    <div class="container-fluid p-0">
        <span class="navbar-text fw-semibold text-dark fs-6">
            <?php echo isset($judul_halaman) && !empty($judul_halaman) ? html_escape($judul_halaman) : 'Laboratory Information Management System'; ?>
        </span>

        <div class="d-flex align-items-center ms-auto">
            <?php if ($this->session->userdata('logged_in')): ?>
                <!-- Notifikasi "Belum dibaca" Badge -->
                <a href="<?php echo $notif_target; ?>" class="d-inline-flex align-items-center text-decoration-none me-4 px-3 py-1 rounded-pill" style="background-color: #f8fafc; border: 1px solid #e2e8f0; transition: all 0.2s;">
                    <span class="text-secondary fw-semibold small me-2" style="color: #64748b !important;">Belum dibaca</span>
                    <span class="badge rounded-pill fw-bold text-danger px-2 py-1" style="background-color: #fef2f2; color: #dc2626 !important; font-size: 0.8rem;">
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
