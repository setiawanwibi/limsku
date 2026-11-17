<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo isset($judul_halaman) ? html_escape($judul_halaman) . ' - ' : ''; ?><?php echo html_escape($nama_aplikasi); ?> | <?php echo html_escape($instansi); ?></title>

    <!-- Google Fonts Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Custom LIMSKU CSS -->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/limsku.css'); ?>">

    <style>
        :root {
            --lims-teal: #0d838a;
            --lims-teal-dark: #09686e;
            --lims-teal-light: #e6f7f8;
            --lims-text-heading: #1e293b;
            --lims-text-body: #64748b;
        }

        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #ffffff;
            color: #334155;
        }

        .login-container-split {
            min-height: 100vh;
            display: flex;
        }

        /* Left Hero Section */
        .login-hero-pane {
            flex: 1.15;
            position: relative;
            background: linear-gradient(135deg, rgba(7, 30, 51, 0.82) 0%, rgba(12, 51, 77, 0.78) 50%, rgba(10, 68, 92, 0.85) 100%), 
                        url('<?php echo base_url('assets/img/lab-analis-login.jpg'); ?>') center center / cover no-repeat;
            padding: 3.5rem 4rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
        }

        /* Ambient Glowing Accents for High-End Institutional Look */
        .login-hero-pane::before {
            content: '';
            position: absolute;
            top: -20%;
            left: -10%;
            width: 450px;
            height: 450px;
            background: radial-gradient(circle, rgba(13, 148, 136, 0.28) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .login-hero-pane::after {
            content: '';
            position: absolute;
            bottom: -15%;
            right: -10%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(2, 132, 199, 0.3) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }

        .hero-top, .hero-center, .hero-bottom {
            position: relative;
            z-index: 2;
        }

        .hero-brand {
            display: flex;
            align-items: center;
            gap: 0.85rem;
        }

        .hero-logo-box {
            width: 48px;
            height: 48px;
            background: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.4);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 6px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .hero-logo-box img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .hero-brand-text h4 {
            margin: 0;
            font-weight: 700;
            font-size: 1.15rem;
            letter-spacing: 0.5px;
            color: #ffffff;
        }

        .hero-brand-text span {
            font-size: 0.78rem;
            color: #cbd5e1;
            display: block;
        }

        .badge-system {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.35rem 0.85rem;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 50px;
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: #cbd5e1;
            margin-bottom: 1.75rem;
            backdrop-filter: blur(4px);
        }

        .badge-system i {
            color: #2dd4bf;
            font-size: 0.85rem;
        }

        .hero-title {
            font-size: 2.5rem;
            font-weight: 700;
            line-height: 1.25;
            color: #ffffff;
            margin-bottom: 1.25rem;
            max-width: 540px;
        }

        .hero-desc {
            font-size: 0.95rem;
            line-height: 1.6;
            color: #cbd5e1;
            max-width: 500px;
            margin-bottom: 2.5rem;
        }

        /* Hero Cards Workflow */
        .hero-cards-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            max-width: 580px;
        }

        .hero-step-card {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 12px;
            padding: 1rem;
            backdrop-filter: blur(10px);
            transition: all 0.2s ease;
        }

        .hero-step-card:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.25);
            transform: translateY(-2px);
        }

        .hero-step-number {
            font-size: 0.75rem;
            font-weight: 600;
            color: #94a3b8;
            margin-bottom: 0.35rem;
        }

        .hero-step-title {
            font-size: 0.9rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 0.2rem;
        }

        .hero-step-subtitle {
            font-size: 0.72rem;
            color: #94a3b8;
            line-height: 1.3;
        }

        .hero-footer-text {
            font-size: 0.78rem;
            color: #64748b;
        }

        /* Right Form Section */
        .login-form-pane {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem 3.5rem;
            background-color: #f8fafc;
        }

        .login-card {
            width: 100%;
            max-width: 440px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 2.25rem 2.25rem 2rem;
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.05);
        }

        .login-card-header {
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .login-card-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 0.25rem 0;
            letter-spacing: -0.01em;
        }

        .login-card-subtitle {
            font-size: 0.8rem;
            color: #64748b;
            margin: 0;
        }

        .form-content-box {
            width: 100%;
        }

        .portal-label {
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--lims-teal);
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 0.6rem;
            display: block;
        }

        .form-main-title {
            font-size: 2rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 0.5rem;
            letter-spacing: -0.02em;
        }

        .form-sub-title {
            font-size: 0.9rem;
            color: #64748b;
            line-height: 1.5;
            margin-bottom: 2rem;
        }

        /* Custom Input Styling */
        .form-group-custom {
            margin-bottom: 1.35rem;
        }

        .form-group-custom label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.45rem;
        }

        .input-wrapper-custom {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-wrapper-custom .input-icon-left {
            position: absolute;
            left: 1rem;
            color: #64748b;
            font-size: 1.1rem;
            pointer-events: none;
        }

        .input-wrapper-custom .input-custom {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.85rem;
            font-size: 0.92rem;
            border: 1px solid #cbd5e1;
            border-radius: 10px;
            background-color: #ffffff;
            color: #1e293b;
            transition: all 0.2s ease;
            outline: none;
        }

        .input-wrapper-custom .input-custom:focus {
            border-color: var(--lims-teal);
            box-shadow: 0 0 0 3px rgba(13, 131, 138, 0.12);
        }

        .input-wrapper-custom .input-icon-right {
            position: absolute;
            right: 1rem;
            color: #64748b;
            font-size: 1.15rem;
            cursor: pointer;
            background: none;
            border: none;
            padding: 0;
            display: flex;
            align-items: center;
        }

        .input-wrapper-custom .input-icon-right:hover {
            color: #1e293b;
        }

        /* Options row */
        .login-options-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            font-size: 0.85rem;
        }

        .custom-checkbox-wrap {
            display: flex;
            align-items: center;
            gap: 0.45rem;
            color: #475569;
            cursor: pointer;
            user-select: none;
        }

        .custom-checkbox-wrap input {
            accent-color: var(--lims-teal);
            width: 16px;
            height: 16px;
            border-radius: 4px;
            cursor: pointer;
        }

        .forgot-link {
            color: var(--lims-teal);
            font-weight: 600;
            text-decoration: none;
            font-size: 0.85rem;
        }

        .forgot-link:hover {
            color: var(--lims-teal-dark);
            text-decoration: underline;
        }

        /* Submit Button */
        .btn-lims-submit {
            width: 100%;
            padding: 0.82rem 1.5rem;
            background-color: var(--lims-teal);
            border: none;
            border-radius: 10px;
            color: #ffffff;
            font-weight: 600;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.2s ease;
            cursor: pointer;
            margin-bottom: 1.5rem;
        }

        .btn-lims-submit:hover {
            background-color: var(--lims-teal-dark);
            box-shadow: 0 4px 12px rgba(13, 131, 138, 0.25);
            transform: translateY(-1px);
            color: #ffffff;
        }

        .btn-lims-submit i {
            font-size: 1.1rem;
            transition: transform 0.2s ease;
        }

        .btn-lims-submit:hover i {
            transform: translateX(3px);
        }

        /* Info Alert Box */
        .audit-info-box {
            background-color: #f0fdfa;
            border: 1px solid #ccfbf1;
            border-radius: 10px;
            padding: 0.85rem 1rem;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            font-size: 0.78rem;
            color: #0f766e;
            line-height: 1.45;
        }

        .audit-info-box i {
            font-size: 1.05rem;
            color: var(--lims-teal);
            flex-shrink: 0;
            margin-top: 1px;
        }

        @media (max-width: 991.98px) {
            .login-hero-pane {
                display: none;
            }
            .login-form-pane {
                padding: 2.5rem 1.5rem;
            }
        }
    </style>
</head>
<body>

<div class="login-container-split">
    <!-- Left Hero Section -->
    <div class="login-hero-pane">
        <!-- Top: Logo & App Title -->
        <div class="hero-top">
            <div class="hero-brand">
                <div class="hero-logo-box">
                    <img src="<?php echo base_url('assets/img/limsku-logo.png'); ?>" alt="Logo LIMSKU" class="limsku-brand-logo">
                </div>
                <div class="hero-brand-text">
                    <h4><?php echo html_escape($nama_aplikasi); ?></h4>
                    <span><?php echo html_escape($instansi); ?></span>
                </div>
            </div>
        </div>

        <!-- Center: Greeting & Portal Label -->
        <div class="hero-center my-auto py-4">
            <div class="badge-system">
                <i class="bi bi-shield-check"></i>
                PORTAL INTERNAL BBPOM
            </div>
            
            <h1 class="hero-title" style="font-size: 2.35rem; line-height: 1.25;">
                Selamat datang kembali
            </h1>

            <p class="hero-desc mb-0" style="font-size: 1rem; max-width: 460px;">
                Masuk menggunakan akun yang telah terdaftar untuk melanjutkan ke ruang kerja Anda.
            </p>
        </div>

        <!-- Bottom: Copyright -->
        <div class="hero-bottom">
            <div class="hero-footer-text">
                &copy; <?php echo date('Y'); ?> Balai Besar POM di Bandar Lampung
            </div>
        </div>
    </div>

    <!-- Right Form Section -->
    <div class="login-form-pane">
        <div class="login-card">

            <!-- Flash Messages -->
            <?php if ($this->session->flashdata('pesan_sukses')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    <?php echo html_escape($this->session->flashdata('pesan_sukses')); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('pesan_gagal')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <?php echo html_escape($this->session->flashdata('pesan_gagal')); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (validation_errors()): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo validation_errors('<div class="d-flex align-items-center mb-1"><i class="bi bi-exclamation-circle me-2"></i>', '</div>'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form action="<?php echo site_url('masuk'); ?>" method="post">
                <!-- CSRF Token -->
                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

                <!-- Identifier Input -->
                <div class="form-group-custom">
                    <label for="nama_pengguna">Username atau NIP</label>
                    <div class="input-wrapper-custom">
                        <i class="bi bi-person input-icon-left"></i>
                        <input type="text" 
                               class="input-custom" 
                               id="nama_pengguna" 
                               name="nama_pengguna" 
                               value="<?php echo set_value('nama_pengguna'); ?>" 
                               placeholder="nadia.anindita@pom.go.id" 
                               autocomplete="off" 
                               required 
                               autofocus>
                    </div>
                </div>

                <!-- Password Input -->
                <div class="form-group-custom">
                    <label for="kata_sandi">Kata sandi</label>
                    <div class="input-wrapper-custom">
                        <i class="bi bi-lock input-icon-left"></i>
                        <input type="password" 
                               class="input-custom" 
                               id="kata_sandi" 
                               name="kata_sandi" 
                               placeholder="••••••••••••" 
                               required>
                        <button type="button" class="input-icon-right" id="togglePasswordBtn" title="Tampilkan / Sembunyikan Sandi">
                            <i class="bi bi-eye-slash" id="togglePasswordIcon"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="login-options-row">
                    <label class="custom-checkbox-wrap">
                        <input type="checkbox" name="ingat_saya" value="1" checked>
                        <span>Ingat saya</span>
                    </label>
                    <a href="javascript:void(0)" class="forgot-link" onclick="alert('Silakan hubungi Administrator IT BBPOM untuk reset kata sandi Anda.')">Lupa kata sandi?</a>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-lims-submit">
                    <span>Masuk ke LIMSKU</span>
                    <i class="bi bi-arrow-right"></i>
                </button>

                <!-- Audit Log Notice Box -->
                <div class="audit-info-box">
                    <i class="bi bi-info-circle"></i>
                    <div>
                        Akses tercatat dalam audit log. Jangan membagikan kredensial Anda kepada pihak lain.
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- jQuery JS -->
<script src="<?php echo base_url('assets/js/jquery.min.js'); ?>"></script>
<!-- Bootstrap 5 Bundle JS -->
<script src="<?php echo base_url('assets/js/bootstrap.bundle.min.js'); ?>"></script>

<script>
    // Password visibility toggle
    document.addEventListener('DOMContentLoaded', function () {
        const toggleBtn = document.getElementById('togglePasswordBtn');
        const passwordInput = document.getElementById('kata_sandi');
        const toggleIcon = document.getElementById('togglePasswordIcon');

        if (toggleBtn && passwordInput && toggleIcon) {
            toggleBtn.addEventListener('click', function () {
                const isPassword = passwordInput.getAttribute('type') === 'password';
                passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                
                if (isPassword) {
                    toggleIcon.classList.remove('bi-eye-slash');
                    toggleIcon.classList.add('bi-eye');
                } else {
                    toggleIcon.classList.remove('bi-eye');
                    toggleIcon.classList.add('bi-eye-slash');
                }
            });
        }
    });
</script>

</body>
</html>

