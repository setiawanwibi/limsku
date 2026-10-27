<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Modul Laboratorium - LIMSKU | BBPOM di Bandar Lampung</title>

    <!-- Google Fonts Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --lims-teal: #0d838a;
            --lims-teal-dark: #085a5f;
            --lims-bg: #f8fafc;
            --lims-border: #e2e8f0;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: var(--lims-bg);
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Top Navbar */
        .portal-nav {
            background: #ffffff;
            border-bottom: 1px solid var(--lims-border);
            padding: 14px 0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        .portal-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: inherit;
        }

        .portal-brand-text h1 {
            font-size: 1.15rem;
            font-weight: 800;
            margin: 0;
            color: #0f172a;
            letter-spacing: -0.3px;
        }

        .portal-brand-text p {
            font-size: 0.72rem;
            font-weight: 500;
            margin: 0;
            color: #64748b;
        }

        /* Hero Header */
        .portal-hero {
            padding: 40px 0 25px;
            text-align: center;
        }

        .portal-badge-welcome {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #e6f7f8;
            color: var(--lims-teal);
            font-size: 0.78rem;
            font-weight: 700;
            padding: 4px 14px;
            border-radius: 999px;
            margin-bottom: 12px;
        }

        .portal-hero h2 {
            font-size: 1.85rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.5px;
            margin-bottom: 8px;
        }

        .portal-hero p {
            color: #64748b;
            font-size: 0.92rem;
            max-width: 620px;
            margin: 0 auto;
        }

        /* Module Grid Cards */
        .portal-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
            gap: 22px;
            margin-bottom: 50px;
        }

        .module-card {
            background: #ffffff;
            border: 1px solid var(--lims-border);
            border-radius: 14px;
            padding: 24px;
            position: relative;
            display: flex;
            flex-direction: column;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            text-decoration: none;
            color: inherit;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
            overflow: hidden;
        }

        .module-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--card-color, var(--lims-teal));
        }

        .module-card.can-access:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px -8px rgba(0, 0, 0, 0.08);
            border-color: #cbd5e1;
        }

        .module-card.disabled-module {
            opacity: 0.72;
            background: #fdfdfe;
            cursor: not-allowed;
        }

        .module-icon-box {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: #fff;
            margin-bottom: 16px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .module-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .module-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 4px;
        }

        .module-sub {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--card-color, var(--lims-teal));
            margin-bottom: 12px;
            display: block;
        }

        .module-desc {
            font-size: 0.81rem;
            line-height: 1.55;
            color: #64748b;
            margin-bottom: 20px;
            flex-grow: 1;
        }

        .module-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 14px;
            border-top: 1px solid #f1f5f9;
        }

        .btn-enter {
            font-size: 0.8rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--card-color, var(--lims-teal));
        }

        .badge-status {
            font-size: 0.68rem;
            font-weight: 700;
            padding: 3px 9px;
            border-radius: 6px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .badge-ready {
            background: #dcfce7;
            color: #15803d;
        }

        .badge-coming {
            background: #f1f5f9;
            color: #64748b;
            border: 1px solid #e2e8f0;
        }

        .badge-locked {
            background: #fee2e2;
            color: #b91c1c;
        }

        .portal-footer {
            margin-top: auto;
            background: #ffffff;
            border-top: 1px solid var(--lims-border);
            padding: 18px 0;
            text-align: center;
            font-size: 0.78rem;
            color: #94a3b8;
        }
    </style>
</head>
<body>

    <!-- Portal Topbar -->
    <nav class="portal-nav">
        <div class="container d-flex align-items-center justify-content-between">
            <a href="<?php echo site_url('portal'); ?>" class="portal-brand">
                <img src="<?php echo base_url('assets/img/bpom_logo_excel.png'); ?>" alt="Logo BPOM" style="height: 38px; width: auto;">
                <div class="portal-brand-text">
                    <h1>LIMSKU PORTAL</h1>
                    <p>Balai Besar Pengawas Obat dan Makanan di Bandar Lampung</p>
                </div>
            </a>

            <div class="d-flex align-items-center gap-3">
                <div class="text-end d-none d-sm-block">
                    <span class="d-block fw-bold text-dark small leading-none"><?php echo html_escape($this->session->userdata('nama_lengkap')); ?></span>
                    <span class="badge bg-light text-secondary border mt-1" style="font-size: 0.68rem;"><?php echo html_escape($this->session->userdata('role_name')); ?></span>
                </div>
                <a href="<?php echo site_url('keluar'); ?>" class="btn btn-sm btn-outline-danger fw-semibold px-3 rounded-pill" onclick="return confirm('Keluar dari sistem LIMSKU?');">
                    <i class="bi bi-box-arrow-right me-1"></i> Keluar
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <main class="container py-4 flex-grow-1">
        
        <!-- Alerts -->
        <?php if ($this->session->flashdata('pesan_sukses')): ?>
            <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm border-0 mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> <?php echo $this->session->flashdata('pesan_sukses'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('pesan_gagal')): ?>
            <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm border-0 mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> <?php echo $this->session->flashdata('pesan_gagal'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Hero Header -->
        <div class="portal-hero">
            <div class="portal-badge-welcome">
                <i class="bi bi-grid-fill"></i> Multi-Commodity Testing Platform
            </div>
            <h2>Pilih Ruang Kerja Laboratorium</h2>
            <p>Silakan pilih modul laboratorium atau sistem kerja sesuai penugasan pengujian Anda di BBPOM di Bandar Lampung.</p>
        </div>

        <!-- Module Grid Cards -->
        <div class="portal-grid">
            <?php foreach ($daftar_modul as $modul): 
                $card_color = $modul['color'];
                $is_ready = ($modul['status'] === 'ready');
                $can_access = !empty($modul['can_access']);
                $card_class = ($is_ready && $can_access) ? 'can-access' : 'disabled-module';
                $href = ($is_ready && $can_access) ? $modul['url'] : 'javascript:void(0)';
            ?>
                <a href="<?php echo $href; ?>" class="module-card <?php echo $card_class; ?>" style="--card-color: <?php echo $card_color; ?>;">
                    <div class="module-header">
                        <div class="module-icon-box" style="background: <?php echo $modul['bg_gradient']; ?>;">
                            <i class="bi <?php echo $modul['icon']; ?>"></i>
                        </div>
                        <div>
                            <?php if (!$can_access): ?>
                                <span class="badge-status badge-locked"><i class="bi bi-lock-fill me-1"></i> Tidak Ada Akses</span>
                            <?php elseif (!$is_ready): ?>
                                <span class="badge-status badge-coming"><i class="bi bi-clock-history me-1"></i> Segera Hadir</span>
                            <?php else: ?>
                                <span class="badge-status badge-ready"><i class="bi bi-check2-circle me-1"></i> Aktif</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <h3 class="module-title"><?php echo html_escape($modul['nama']); ?></h3>
                    <span class="module-sub"><?php echo html_escape($modul['subjudul']); ?></span>
                    <p class="module-desc"><?php echo html_escape($modul['deskripsi']); ?></p>

                    <div class="module-footer">
                        <span class="text-muted small fw-semibold font-monospace"><?php echo html_escape($modul['kode']); ?></span>
                        <?php if ($is_ready && $can_access): ?>
                            <span class="btn-enter">
                                Masuk Workspace <i class="bi bi-arrow-right"></i>
                            </span>
                        <?php elseif (!$can_access): ?>
                            <span class="text-danger small fw-semibold">
                                <i class="bi bi-slash-circle me-1"></i> Akses Terbatas
                            </span>
                        <?php else: ?>
                            <span class="text-muted small fw-semibold">
                                <i class="bi bi-tools me-1"></i> Tahap Desain
                            </span>
                        <?php endif; ?>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>

    </main>

    <!-- Footer -->
    <footer class="portal-footer">
        <div class="container">
            <div>&copy; <?php echo date('Y'); ?> <strong>LIMSKU Enterprise</strong> &bull; BBPOM di Bandar Lampung &bull; Versi Multi-Komoditi 2.0</div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="<?php echo base_url('assets/js/bootstrap.bundle.min.js'); ?>"></script>
</body>
</html>
