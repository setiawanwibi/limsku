<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
// Evaluasi permission user yang sedang login dari session role_id
$role_id = $this->session->userdata('role_id');

// Permissions
$bisa_sampel             = $this->Model_Hak_Akses->memiliki_akses($role_id, 'sampel_view');
$bisa_pengujian_antrean  = $this->Model_Hak_Akses->memiliki_akses($role_id, 'pengujian_view');
$bisa_verifikasi         = $this->Model_Hak_Akses->memiliki_akses($role_id, 'pengujian_verify');
$bisa_approval           = $this->Model_Hak_Akses->memiliki_akses($role_id, 'pengujian_approve');
$bisa_laporan            = $this->Model_Hak_Akses->memiliki_akses($role_id, 'laporan_view');
$bisa_riwayat_saya       = in_array((int)$role_id, array(1, 3, 4, 5), true) || $this->Model_Hak_Akses->memiliki_akses($role_id, 'pengujian_input');
$bisa_monitoring_rekap   = in_array((int)$role_id, array(1, 4, 5), true); // Hanya Admin TI, Penyelia, dan Manajer Teknis

$bisa_metode             = $this->Model_Hak_Akses->memiliki_akses($role_id, 'metode_view');
$bisa_kelas_terapi       = $this->Model_Hak_Akses->memiliki_akses($role_id, 'kelas_terapi_view');
$bisa_template           = $this->Model_Hak_Akses->memiliki_akses($role_id, 'template_view');
$bisa_alat               = $this->Model_Hak_Akses->memiliki_akses($role_id, 'alat_view') || $this->Model_Hak_Akses->memiliki_akses($role_id, 'laboratorium_view');
$bisa_reagen             = $this->Model_Hak_Akses->memiliki_akses($role_id, 'reagen_view');
$bisa_baku               = $this->Model_Hak_Akses->memiliki_akses($role_id, 'baku_view');
$bisa_pengguna           = $this->Model_Hak_Akses->memiliki_akses($role_id, 'pengguna_view');
$bisa_peran              = $this->Model_Hak_Akses->memiliki_akses($role_id, 'peran_view');
$bisa_laboratorium       = $this->Model_Hak_Akses->memiliki_akses($role_id, 'laboratorium_view');

$bisa_audit              = $this->Model_Hak_Akses->memiliki_akses($role_id, 'audit_view');

// Parent Header Visibility Flags
$ada_menu_registrasi = $bisa_sampel;
$ada_menu_pengujian  = ($bisa_pengujian_antrean || $bisa_verifikasi || $bisa_approval || $bisa_laporan || $bisa_riwayat_saya);
$ada_menu_master     = ($bisa_metode || $bisa_kelas_terapi || $bisa_template || $bisa_alat || $bisa_reagen || $bisa_baku || $bisa_pengguna || $bisa_peran);
$ada_menu_sistem     = $bisa_audit;

// Ambil badge notifikasi antrean & workflow laboratorium
if (!isset($badges)) {
    $ci =& get_instance();
    $ci->load->model('Model_Notifikasi');
    $badges = $ci->Model_Notifikasi->ambil_semua_badge();
}
?>
<nav id="lims-sidebar">
    <div class="sidebar-header border-bottom">
        <a href="<?php echo site_url('dasbor'); ?>" class="limsku-brand">
            <img src="<?php echo base_url('assets/img/limsku-logo.png'); ?>" alt="Logo LIMSKU" class="limsku-brand-logo">
            <div>
                <h5 class="fw-bold mb-0">LIMSKU</h5>
                <small>BBPOM Bandar Lampung</small>
            </div>
        </a>
        <button type="button" id="sidebar-toggle-close" class="sidebar-toggle-btn ms-1" title="Tutup Sidebar" aria-label="Tutup Sidebar">
            <i class="bi bi-list fs-4"></i>
        </button>
    </div>

    <!-- Active Workspace Bar -->
    <div class="px-3 py-2 bg-light border-bottom d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2 overflow-hidden">
            <span class="badge bg-primary px-2 py-1" style="font-size: 0.65rem;">
                <i class="bi bi-capsule me-1"></i> LAB OBAT
            </span>
            <span class="text-muted small text-truncate" style="font-size: 0.72rem;">Farmasi & Uji</span>
        </div>
        <a href="<?php echo site_url('portal'); ?>" class="btn btn-xs btn-outline-secondary py-0 px-2" style="font-size: 0.68rem;" title="Beralih Modul">
            Ganti
        </a>
    </div>

    <ul class="components list-unstyled px-2 py-3">
        <!-- Dasbor: Tampil untuk semua authenticated user -->
        <li class="mb-1 <?php echo ($halaman_aktif === 'dasbor') ? 'active' : ''; ?>">
            <a href="<?php echo site_url('dasbor'); ?>" class="nav-link px-3 py-2 rounded">
                <i class="bi bi-grid-1x2 me-2"></i>
                <span>Dasbor Utama</span>
            </a>
        </li>
        <li class="mb-1 <?php echo ($halaman_aktif === 'akun') ? 'active' : ''; ?>">
            <a href="<?php echo site_url('akun'); ?>" class="nav-link px-3 py-2 rounded">
                <i class="bi bi-person-circle me-2"></i>
                <span><?php echo in_array((int)$role_id, array(2, 3, 4, 5)) ? 'Profil &amp; TTD Digital' : 'Profil &amp; Keamanan Akun'; ?></span>
                <?php if (!empty($badges['akun']) && $badges['akun'] > 0): ?>
                    <span class="sidebar-badge-notif" title="<?php echo html_escape($badges['akun_label']); ?>">!</span>
                <?php endif; ?>
            </a>
        </li>

        <!-- Registrasi & Sampel -->
        <?php if ($ada_menu_registrasi): ?>
            <li class="nav-header px-3 pt-3 pb-1 text-uppercase text-muted fw-bold">
                Registrasi &amp; Sampel
            </li>

            <?php if ($bisa_sampel): ?>
                <li class="mb-1 <?php echo ($halaman_aktif === 'sampel') ? 'active' : ''; ?>">
                    <a href="<?php echo site_url('sampel'); ?>" class="nav-link px-3 py-2 rounded">
                        <i class="bi bi-box-seam me-2"></i>
                        <span>Data Sampel</span>
                        <?php if (!empty($badges['sampel']) && $badges['sampel'] > 0): ?>
                            <span class="sidebar-badge-notif" title="<?php echo html_escape($badges['sampel_label']); ?>">
                                <?php echo $badges['sampel']; ?>
                            </span>
                        <?php endif; ?>
                    </a>
                </li>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Pengujian Sampel & Laporan -->
        <?php if ($ada_menu_pengujian): ?>
            <li class="nav-header px-3 pt-3 pb-1 text-uppercase text-muted fw-bold">
                Pengujian Sampel
            </li>

            <?php if ($bisa_pengujian_antrean): ?>
                <li class="mb-1 <?php echo ($halaman_aktif === 'pengujian_antrean') ? 'active' : ''; ?>">
                    <a href="<?php echo site_url('pengujian/antrean'); ?>" class="nav-link px-3 py-2 rounded">
                        <i class="bi bi-list-task me-2"></i>
                        <span>Antrean Pengujian</span>
                        <?php if (!empty($badges['pengujian_antrean']) && $badges['pengujian_antrean'] > 0): ?>
                            <span class="sidebar-badge-notif" title="<?php echo html_escape($badges['pengujian_antrean_label']); ?>">
                                <?php echo $badges['pengujian_antrean']; ?>
                            </span>
                        <?php endif; ?>
                    </a>
                </li>
            <?php endif; ?>

            <?php if ($bisa_verifikasi): ?>
                <li class="mb-1 <?php echo ($halaman_aktif === 'pengujian_verifikasi') ? 'active' : ''; ?>">
                    <a href="<?php echo site_url('pengujian/verifikasi'); ?>" class="nav-link px-3 py-2 rounded">
                        <i class="bi bi-check2-square me-2"></i>
                        <span>Verifikasi Laporan</span>
                        <?php if (!empty($badges['pengujian_verifikasi']) && $badges['pengujian_verifikasi'] > 0): ?>
                            <span class="sidebar-badge-notif" title="<?php echo html_escape($badges['pengujian_verifikasi_label']); ?>">
                                <?php echo $badges['pengujian_verifikasi']; ?>
                            </span>
                        <?php endif; ?>
                    </a>
                </li>
                <li class="mb-1 <?php echo ($halaman_aktif === 'pengujian_riwayat_verifikasi') ? 'active' : ''; ?>">
                    <a href="<?php echo site_url('pengujian/riwayat_verifikasi'); ?>" class="nav-link px-3 py-2 rounded ps-4 small">
                        <i class="bi bi-clock-history me-2"></i>
                        <span>Riwayat Verifikasi</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if ($bisa_approval): ?>
                <li class="mb-1 <?php echo ($halaman_aktif === 'pengujian_approval') ? 'active' : ''; ?>">
                    <a href="<?php echo site_url('pengujian/approval'); ?>" class="nav-link px-3 py-2 rounded">
                        <i class="bi bi-shield-check me-2"></i>
                        <span>Approval Laporan</span>
                        <?php if (!empty($badges['pengujian_approval']) && $badges['pengujian_approval'] > 0): ?>
                            <span class="sidebar-badge-notif" title="<?php echo html_escape($badges['pengujian_approval_label']); ?>">
                                <?php echo $badges['pengujian_approval']; ?>
                            </span>
                        <?php endif; ?>
                    </a>
                </li>
                <li class="mb-1 <?php echo ($halaman_aktif === 'pengujian_riwayat_approval') ? 'active' : ''; ?>">
                    <a href="<?php echo site_url('pengujian/riwayat_approval'); ?>" class="nav-link px-3 py-2 rounded ps-4 small">
                        <i class="bi bi-file-earmark-check me-2"></i>
                        <span>Riwayat Approval</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if ($bisa_riwayat_saya): ?>
                <li class="mb-1 <?php echo ($halaman_aktif === 'pengujian_riwayat_saya') ? 'active' : ''; ?>">
                    <a href="<?php echo site_url('pengujian/riwayat_saya'); ?>" class="nav-link px-3 py-2 rounded">
                        <i class="bi bi-clock-history me-2 text-primary"></i>
                        <span>Historical Pengujian Saya</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if ($bisa_laporan): ?>
                <li class="mb-1 <?php echo ($halaman_aktif === 'pengujian_riwayat') ? 'active' : ''; ?>">
                    <a href="<?php echo site_url('pengujian/riwayat'); ?>" class="nav-link px-3 py-2 rounded">
                        <i class="bi bi-file-earmark-text me-2"></i>
                        <span>Riwayat Hasil Uji</span>
                        <?php if (!empty($badges['pengujian_riwayat']) && $badges['pengujian_riwayat'] > 0): ?>
                            <span class="sidebar-badge-notif" title="<?php echo html_escape($badges['pengujian_riwayat_label']); ?>">
                                <?php echo $badges['pengujian_riwayat']; ?>
                            </span>
                        <?php endif; ?>
                    </a>
                </li>
            <?php endif; ?>

            <?php if ($bisa_monitoring_rekap): ?>
                <li class="mb-1 <?php echo ($halaman_aktif === 'laporan_monitoring') ? 'active' : ''; ?>">
                    <a href="<?php echo site_url('laporan'); ?>" class="nav-link px-3 py-2 rounded">
                        <i class="bi bi-graph-up-arrow me-2"></i>
                        <span>Monitoring &amp; Rekap</span>
                    </a>
                </li>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Master Data -->
        <?php if ($ada_menu_master): ?>
            <li class="nav-header px-3 pt-3 pb-1 text-uppercase text-muted fw-bold">
                Master Data
            </li>

            <?php if ($bisa_metode): ?>
                <li class="mb-1 <?php echo ($halaman_aktif === 'metode') ? 'active' : ''; ?>">
                    <a href="<?php echo site_url('metode'); ?>" class="nav-link px-3 py-2 rounded">
                        <i class="bi bi-journal-bookmark me-2"></i>
                        <span>Master Metode</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if ($bisa_kelas_terapi): ?>
                <li class="mb-1 <?php echo ($halaman_aktif === 'kelas_terapi') ? 'active' : ''; ?>">
                    <a href="<?php echo site_url('kelas-terapi'); ?>" class="nav-link px-3 py-2 rounded">
                        <i class="bi bi-diagram-3 me-2"></i>
                        <span>Master Kelas Terapi</span>
                    </a>
                </li>
            <?php endif; ?>



            <?php if ($bisa_template): ?>
                <li class="mb-1 <?php echo ($halaman_aktif === 'template') ? 'active' : ''; ?>">
                    <a href="<?php echo site_url('template'); ?>" class="nav-link px-3 py-2 rounded">
                        <i class="bi bi-file-earmark-code me-2"></i>
                        <span>Form Template</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if ($bisa_pengguna): ?>
                <li class="mb-1 <?php echo ($halaman_aktif === 'pengguna') ? 'active' : ''; ?>">
                    <a href="<?php echo site_url('pengguna'); ?>" class="nav-link px-3 py-2 rounded">
                        <i class="bi bi-people me-2"></i>
                        <span>Manajemen Pengguna</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if ($bisa_peran): ?>
                <li class="mb-1 <?php echo ($halaman_aktif === 'peran') ? 'active' : ''; ?>">
                    <a href="<?php echo site_url('peran'); ?>" class="nav-link px-3 py-2 rounded">
                        <i class="bi bi-shield-lock me-2"></i>
                        <span>Peran &amp; Hak Akses</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if ($bisa_alat): ?>
                <li class="mb-1 <?php echo ($halaman_aktif === 'alat') ? 'active' : ''; ?>">
                    <a href="<?php echo site_url('alat'); ?>" class="nav-link px-3 py-2 rounded">
                        <i class="bi bi-tools me-2"></i>
                        <span>Alat Laboratorium</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if ($bisa_reagen): ?>
                <li class="mb-1 <?php echo ($halaman_aktif === 'reagen') ? 'active' : ''; ?>">
                    <a href="<?php echo site_url('reagen'); ?>" class="nav-link px-3 py-2 rounded">
                        <i class="bi bi-eyedropper me-2"></i>
                        <span>Master Reagen</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if ($bisa_baku): ?>
                <li class="mb-1 <?php echo ($halaman_aktif === 'baku') ? 'active' : ''; ?>">
                    <a href="<?php echo site_url('baku'); ?>" class="nav-link px-3 py-2 rounded">
                        <i class="bi bi-droplet-half me-2"></i>
                        <span>Data Baku</span>
                    </a>
                </li>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Sistem & Keamanan -->
        <?php if ($ada_menu_sistem): ?>
            <li class="nav-header px-3 pt-3 pb-1 text-uppercase text-muted fw-bold">
                Sistem &amp; Keamanan
            </li>

            <?php if ($bisa_audit): ?>
                <li class="mb-1 <?php echo ($halaman_aktif === 'audit') ? 'active' : ''; ?>">
                    <a href="<?php echo site_url('audit'); ?>" class="nav-link px-3 py-2 rounded">
                        <i class="bi bi-activity me-2"></i>
                        <span>Audit Trail</span>
                    </a>
                </li>
            <?php endif; ?>
        <?php endif; ?>
    </ul>
</nav>
