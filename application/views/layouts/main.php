<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo isset($judul_halaman) && !empty($judul_halaman) ? html_escape($judul_halaman) . ' - ' : ''; ?><?php echo html_escape($nama_aplikasi); ?> | <?php echo html_escape($instansi); ?></title>

    <!-- Google Fonts Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- DataTables Bootstrap 5 CSS -->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/dataTables.bootstrap5.min.css'); ?>">
    <!-- Custom LIMSKU CSS -->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/limsku.css?v=' . filemtime(FCPATH . 'assets/css/limsku.css')); ?>">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<div class="lims-wrapper">
    <!-- Sidebar -->
    <?php $this->load->view('partials/sidebar'); ?>

    <!-- Page Content -->
    <div id="lims-content">
        <!-- Topbar -->
        <?php $this->load->view('partials/topbar'); ?>

        <!-- Main Body -->
        <main class="lims-main-body">
            <!-- Breadcrumbs -->
            <?php if (!isset($hide_breadcrumb) || !$hide_breadcrumb): ?>
    <?php $this->load->view('partials/breadcrumb'); ?>
<?php endif; ?>

            <!-- Konten Utama -->
            <?php if (isset($konten_utama)): ?>
                <?php echo $konten_utama; ?>
            <?php endif; ?>
        </main>

        <!-- Footer -->
        <?php $this->load->view('partials/footer'); ?>
    </div>
</div>

<!-- jQuery JS -->
<script src="<?php echo base_url('assets/js/jquery.min.js'); ?>"></script>
<!-- Bootstrap 5 Bundle JS -->
<script src="<?php echo base_url('assets/js/bootstrap.bundle.min.js'); ?>"></script>
<!-- DataTables JS -->
<script src="<?php echo base_url('assets/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/dataTables.bootstrap5.min.js'); ?>"></script>

<!-- Modal Konfirmasi Global LIMSKU -->
<div class="modal fade" id="globalConfirmModal" tabindex="-1" aria-labelledby="globalConfirmModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 430px;">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-body p-4 text-center">
                <div id="globalConfirmIconBg" class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle" style="width: 64px; height: 64px; background-color: #eff6ff;">
                    <i id="globalConfirmIcon" class="bi bi-question-circle-fill text-primary display-6"></i>
                </div>
                <h5 class="fw-bold mb-2 text-dark" id="globalConfirmTitle">Konfirmasi Tindakan</h5>
                <p class="text-secondary small mb-0 px-2" id="globalConfirmMessage">Apakah Anda yakin ingin melanjutkan tindakan ini?</p>
            </div>
            <div class="modal-footer border-0 bg-light px-4 py-3 d-flex justify-content-between gap-2">
                <button type="button" class="btn btn-light border text-secondary fw-semibold rounded-pill px-4 flex-fill" data-bs-dismiss="modal">
                    Batal
                </button>
                <button type="button" id="globalConfirmSubmitBtn" class="btn btn-primary fw-bold rounded-pill px-4 flex-fill shadow-sm">
                    <i class="bi bi-check-lg me-1"></i> Ya, Lanjutkan
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Global Confirmation Helper
    window.limsConfirm = function(config) {
        var title = config.title || 'Konfirmasi Tindakan';
        var message = config.message || 'Apakah Anda yakin ingin melanjutkan tindakan ini?';
        var type = config.type || 'primary';
        var confirmText = config.confirmText || 'Ya, Lanjutkan';
        var onConfirm = config.onConfirm || function(){};

        var modalEl = document.getElementById('globalConfirmModal');
        if (!modalEl) {
            if (confirm(message)) onConfirm();
            return;
        }

        var iconBg = document.getElementById('globalConfirmIconBg');
        var iconEl = document.getElementById('globalConfirmIcon');
        var titleEl = document.getElementById('globalConfirmTitle');
        var msgEl = document.getElementById('globalConfirmMessage');
        var submitBtn = document.getElementById('globalConfirmSubmitBtn');

        titleEl.textContent = title;
        msgEl.textContent = message;
        submitBtn.innerHTML = '<i class="bi bi-check-lg me-1"></i> ' + confirmText;

        if (type === 'danger') {
            iconBg.style.backgroundColor = '#fef2f2';
            iconEl.className = 'bi bi-exclamation-triangle-fill text-danger display-6';
            submitBtn.className = 'btn btn-danger fw-bold rounded-pill px-4 flex-fill shadow-sm';
        } else if (type === 'warning') {
            iconBg.style.backgroundColor = '#fffbe6';
            iconEl.className = 'bi bi-exclamation-circle-fill text-warning display-6';
            submitBtn.className = 'btn btn-warning text-dark fw-bold rounded-pill px-4 flex-fill shadow-sm';
        } else if (type === 'success') {
            iconBg.style.backgroundColor = '#f0fdf4';
            iconEl.className = 'bi bi-check-circle-fill text-success display-6';
            submitBtn.className = 'btn btn-success fw-bold rounded-pill px-4 flex-fill shadow-sm';
        } else {
            iconBg.style.backgroundColor = '#eff6ff';
            iconEl.className = 'bi bi-question-circle-fill text-primary display-6';
            submitBtn.className = 'btn btn-primary fw-bold rounded-pill px-4 flex-fill shadow-sm';
        }

        var bsModal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);

        submitBtn.onclick = function() {
            bsModal.hide();
            onConfirm();
        };

        bsModal.show();
    };

    // Intercept clicks on links or submit buttons with inline confirm(...) or data-confirm
    document.addEventListener('click', function(e) {
        var target = e.target.closest('a, button, input[type="submit"]');
        if (!target) return;

        var confirmMsg = target.getAttribute('data-confirm');
        var onclickAttr = target.getAttribute('onclick');
        
        if (!confirmMsg && onclickAttr && onclickAttr.indexOf('confirm(') !== -1) {
            var match = onclickAttr.match(/confirm\s*\(\s*['"](.*?)['"]\s*\)/);
            if (match && match[1]) {
                confirmMsg = match[1];
            }
        }

        if (confirmMsg) {
            e.preventDefault();
            e.stopPropagation();

            var form = target.closest('form');
            var href = target.getAttribute('href');
            var isDanger = /hapus|delete|keluar|tolak|batal/i.test(confirmMsg + ' ' + (href||'') + ' ' + (target.className||''));
            var isSuccess = /setuju|approve|terima|klaim|simpan|selesai|proses|inisialisasi/i.test(confirmMsg + ' ' + (href||'') + ' ' + (target.className||''));

            var type = isDanger ? 'danger' : (isSuccess ? 'success' : 'primary');
            var title = isDanger ? 'Konfirmasi Hapus / Batal' : (isSuccess ? 'Konfirmasi Persetujuan' : 'Konfirmasi Tindakan');
            var confirmBtnText = isDanger ? 'Ya, Lanjutkan' : 'Ya, Setuju';

            window.limsConfirm({
                title: title,
                message: confirmMsg,
                type: type,
                confirmText: confirmBtnText,
                onConfirm: function() {
                    if (target.tagName === 'A' && href && href !== '#') {
                        window.location.href = href;
                    } else if (form) {
                        form.dataset.confirmed = 'true';
                        form.submit();
                    }
                }
            });
            return false;
        }
    }, true);

    // Intercept form submit containing confirm(...) or data-confirm
    document.addEventListener('submit', function(e) {
        var form = e.target;
        var confirmMsg = form.getAttribute('data-confirm');
        var onsubmitAttr = form.getAttribute('onsubmit');

        if (!confirmMsg && onsubmitAttr && onsubmitAttr.indexOf('confirm(') !== -1) {
            var match = onsubmitAttr.match(/confirm\s*\(\s*['"](.*?)['"]\s*\)/);
            if (match && match[1]) {
                confirmMsg = match[1];
            }
        }

        if (confirmMsg && !form.dataset.confirmed) {
            e.preventDefault();
            e.stopPropagation();

            var isDanger = /hapus|delete|keluar|tolak/i.test(confirmMsg + ' ' + (form.action||''));
            var isSuccess = /setuju|approve|terima|klaim|simpan|verifikasi/i.test(confirmMsg + ' ' + (form.action||''));

            var type = isDanger ? 'danger' : (isSuccess ? 'success' : 'primary');
            var title = isDanger ? 'Konfirmasi Penolakan / Hapus' : (isSuccess ? 'Konfirmasi Persetujuan' : 'Konfirmasi Tindakan');

            window.limsConfirm({
                title: title,
                message: confirmMsg,
                type: type,
                confirmText: 'Ya, Lanjutkan',
                onConfirm: function() {
                    form.dataset.confirmed = 'true';
                    form.submit();
                }
            });
            return false;
        }
    }, true);
});
</script>
</body>
</html>
