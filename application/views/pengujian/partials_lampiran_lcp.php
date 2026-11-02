<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Partial View Halaman Lampiran Hasil Alat Laboratorium (LCP BPOM)
 * Tampilan full 1 halaman penuh untuk berkas PDF atau gambar hasil alat laboratorium.
 * 
 * Menggunakan PDF.js untuk rendering halaman PDF ke Canvas HTML5
 * agar 100% kompatibel saat dicetak / disimpan via browser (window.print).
 */

if (empty($lampiran_alat)) {
    return;
}

$file_ext = strtolower(pathinfo($lampiran_alat, PATHINFO_EXTENSION));
$is_pdf   = ($file_ext === 'pdf');
$is_image = in_array($file_ext, array('jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'));

$file_url  = base_url($lampiran_alat);
$file_name = basename($lampiran_alat);
?>

<!-- STYLE KHUSUS HALAMAN LAMPIRAN ALAT FULL HALAMAN -->
<style>
    .page-break-lampiran {
        page-break-before: always !important;
        break-before: page !important;
    }

    .lampiran-wrapper-full {
        width: 210mm;
        min-height: 297mm;
        margin: 20px auto;
        padding: 0;
        background: #fff;
        box-shadow: 0 0 10px rgba(0,0,0,0.3);
        box-sizing: border-box;
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .lampiran-full-img {
        width: 100%;
        height: 100%;
        max-width: 210mm;
        max-height: 297mm;
        object-fit: contain;
        display: block;
        margin: 0 auto;
    }

    .lampiran-canvas-page {
        width: 100% !important;
        max-width: 210mm !important;
        height: auto !important;
        max-height: 297mm !important;
        display: block !important;
        margin: 0 auto !important;
    }

    @media print {
        .page-break-lampiran {
            page-break-before: always !important;
            break-before: page !important;
        }

        .lampiran-wrapper-full {
            margin: 0 !important;
            padding: 0 !important;
            box-shadow: none !important;
            width: 210mm !important;
            min-height: 297mm !important;
            height: auto !important;
            page-break-inside: avoid !important;
            page-break-after: auto !important;
            overflow: visible !important;
        }

        .lampiran-full-img {
            width: 100% !important;
            height: 100% !important;
            max-width: 210mm !important;
            max-height: 297mm !important;
            object-fit: contain !important;
        }

        .lampiran-canvas-page {
            width: 100% !important;
            max-width: 210mm !important;
            height: auto !important;
            max-height: 297mm !important;
            display: block !important;
            page-break-inside: avoid !important;
        }
    }
</style>

<!-- HALAMAN LAMPIRAN HASIL ALAT LABORATORIUM PENUH (FULL PAGE) -->
<div class="page-break-lampiran"></div>

<div class="lampiran-wrapper-full" id="lampiranWrapper">
    <?php if ($is_image): ?>
        <img src="<?php echo $file_url; ?>" alt="Lampiran Hasil Alat" class="lampiran-full-img">
    <?php elseif ($is_pdf): ?>
        <div id="pdf-render-container" style="width: 100%; text-align: center;">
            <div id="pdf-loading-msg" class="p-4 text-muted small">
                <span class="spinner-border spinner-border-sm me-1" role="status"></span>
                Memuat pratinjau lampiran PDF alat...
            </div>
        </div>

        <script src="<?php echo base_url('assets/js/pdf.min.js'); ?>"></script>
        <script>
        (function() {
            if (typeof pdfjsLib === 'undefined') return;
            pdfjsLib.GlobalWorkerOptions.workerSrc = '<?php echo base_url('assets/js/pdf.worker.min.js'); ?>';

            var url = '<?php echo $file_url; ?>';
            var container = document.getElementById('pdf-render-container');
            var loadingMsg = document.getElementById('pdf-loading-msg');

            pdfjsLib.getDocument(url).promise.then(function(pdfDoc) {
                if (loadingMsg) loadingMsg.remove();
                
                // Render setiap halaman PDF sebagai canvas gambar murni yang siap print
                for (var pageNum = 1; pageNum <= pdfDoc.numPages; pageNum++) {
                    (function(num) {
                        pdfDoc.getPage(num).then(function(page) {
                            var scale = 2.0; // Kualitas tinggi
                            var viewport = page.getViewport({ scale: scale });

                            var canvas = document.createElement('canvas');
                            canvas.className = 'lampiran-canvas-page' + (num > 1 ? ' page-break-lampiran' : '');
                            var context = canvas.getContext('2d');
                            canvas.height = viewport.height;
                            canvas.width = viewport.width;

                            var renderContext = {
                                canvasContext: context,
                                viewport: viewport
                            };
                            page.render(renderContext);
                            container.appendChild(canvas);
                        });
                    })(pageNum);
                }
            }).catch(function(err) {
                if (loadingMsg) {
                    loadingMsg.innerHTML = '<span class="text-danger">Gagal memuat pratinjau lampiran PDF. <a href="' + url + '" target="_blank">Klik untuk buka file</a></span>';
                }
            });
        })();
        </script>
    <?php else: ?>
        <div style="padding: 20px; text-align: center; font-family: Arial, sans-serif;">
            <p>Berkas lampiran: <strong><?php echo html_escape($file_name); ?></strong></p>
            <a href="<?php echo $file_url; ?>" target="_blank">Buka Berkas &rarr;</a>
        </div>
    <?php endif; ?>
</div>
