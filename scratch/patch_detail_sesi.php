<?php
$f = file_get_contents('application/views/pengujian/detail_sesi.php');
$search = "<?php if (isset(\$sesi['jenis_pengujian']) && \$sesi['jenis_pengujian'] === 'Mikrobiologi' && in_array(\$sesi['status'], array('Menunggu Approval', 'Approved / Final'), TRUE)): ?>
                            <a href=\"<?php echo site_url('pengujian/cp_mikro/' . \$sesi['id']); ?>\"
                               class=\"btn btn-info text-white\"
                               target=\"_blank\">
                                <i class=\"bi bi-journal-check me-1\"></i>
                                Unduh CP Mikrobiologi
                            </a>
                            <a href=\"#daftar-lcp\"
                               class=\"btn btn-outline-primary\">
                                <i class=\"bi bi-file-earmark-ruled me-1\"></i>
                                Lembar LCP Form (<?php echo count(\$forms); ?>)
                            </a>
                        <?php endif; ?>";

$replace = "<?php if (in_array(\$sesi['status'], array('Menunggu Approval', 'Approved / Final'), TRUE)): ?>
                            <?php if (isset(\$sesi['jenis_pengujian']) && \$sesi['jenis_pengujian'] === 'Mikrobiologi'): ?>
                                <a href=\"<?php echo site_url('pengujian/cp_mikro/' . \$sesi['id']); ?>\"
                                   class=\"btn btn-info text-white\"
                                   target=\"_blank\">
                                    <i class=\"bi bi-journal-check me-1\"></i>
                                    Unduh CP Mikrobiologi
                                </a>
                            <?php endif; ?>
                            <a href=\"#daftar-lcp\"
                               class=\"btn btn-outline-primary\">
                                <i class=\"bi bi-file-earmark-ruled me-1\"></i>
                                Lembar LCP Form (<?php echo count(\$forms); ?>)
                            </a>
                        <?php endif; ?>";

$f_norm = str_replace("\r\n", "\n", $f);
$search_norm = str_replace("\r\n", "\n", $search);
$replace_norm = str_replace("\r\n", "\n", $replace);

if (strpos($f_norm, $search_norm) !== false) {
    $f_norm = str_replace($search_norm, $replace_norm, $f_norm);
    if (strpos($f, "\r\n") !== false) {
        $f_norm = str_replace("\n", "\r\n", $f_norm);
    }
    file_put_contents('application/views/pengujian/detail_sesi.php', $f_norm);
    echo "SUCCESS: Updated detail_sesi.php\n";
} else {
    echo "Pattern not found!\n";
}
