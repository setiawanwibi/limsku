<?php
$f = file_get_contents('application/views/pengujian/proses_sesi.php');
$target = "                                                                <?php else: ?>\n                                                                    <div class=\"col-md-6\">\n                                                                        <label for=\"f_<?php echo \$f['id']; ?>_<?php echo \$fld_id; ?>\" class=\"form-label\">";

$pos = strpos($f, "<?php else: ?>\n                                                                    <div class=\"col-md-6\">\n                                                                        <label for=\"f_<?php echo \$f['id']; ?>_<?php echo \$fld_id; ?>\"");
if ($pos === false) {
    // Try \r\n
    $pos = strpos($f, "<?php else: ?>\r\n                                                                    <div class=\"col-md-6\">\r\n                                                                        <label for=\"f_<?php echo \$f['id']; ?>_<?php echo \$fld_id; ?>\"");
    $crlf = true;
} else {
    $crlf = false;
}

echo "Found at: " . ($pos !== false ? $pos : 'NO') . " (CRLF: " . ($crlf ? 'yes' : 'no') . ")\n";

if ($pos !== false) {
    $replacement = "<?php elseif (\$fld_type === 'file'): ?>
                                                                    <div class=\"col-md-6\">
                                                                        <label for=\"f_<?php echo \$f['id']; ?>_<?php echo \$fld_id; ?>\" class=\"form-label\">
                                                                            <?php echo html_escape(\$fld_label); ?>
                                                                            <?php if (\$fld_req): ?><span class=\"text-danger\">*</span><?php endif; ?>
                                                                        </label>
                                                                        <input
                                                                            type=\"file\"
                                                                            class=\"form-control\"
                                                                            id=\"f_<?php echo \$f['id']; ?>_<?php echo \$fld_id; ?>\"
                                                                            name=\"file_<?php echo \$f['id']; ?>_<?php echo \$fld_id; ?>\"
                                                                            accept=\"<?php echo html_escape(isset(\$fld['accept']) ? \$fld['accept'] : 'image/*'); ?>\"
                                                                        >
                                                                        <input
                                                                            type=\"hidden\"
                                                                            name=\"forms[<?php echo \$f['id']; ?>][hasil][<?php echo \$fld_id; ?>]\"
                                                                            value=\"<?php echo html_escape(\$val_text); ?>\"
                                                                        >
                                                                        <?php if (!empty(\$val_text)): ?>
                                                                            <div class=\"mt-2 small d-flex align-items-center gap-2\">
                                                                                <span class=\"text-success\"><i class=\"bi bi-check-circle-fill me-1\"></i> File:</span>
                                                                                <a href=\"<?php echo base_url(\$val_text); ?>\" target=\"_blank\" class=\"text-primary text-decoration-underline\"><?php echo html_escape(basename(\$val_text)); ?></a>
                                                                            </div>
                                                                        <?php endif; ?>
                                                                        <?php if (!empty(\$fld['keterangan'])): ?>
                                                                            <small class=\"text-muted d-block mt-1\"><?php echo html_escape(\$fld['keterangan']); ?></small>
                                                                        <?php endif; ?>
                                                                    </div>\n\n                                                                <?php else: ?>";

    if ($crlf) {
        $replacement = str_replace("\n", "\r\n", $replacement);
        $search = "<?php else: ?>";
    } else {
        $search = "<?php else: ?>";
    }
    
    // Replace only that occurrence
    $f_new = substr_replace($f, $replacement, $pos, strlen("<?php else: ?>"));
    file_put_contents('application/views/pengujian/proses_sesi.php', $f_new);
    echo "SUCCESS: Replaced file branch in proses_sesi.php\n";
}
