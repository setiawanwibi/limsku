<?php
$f = file_get_contents('application/views/pengujian/pilih_form.php');
$search = "<?php echo (\$tpl_b['kode_template'] === 'MIK-04' ? 'selected' : ''); ?>";
$replace = "<?php echo ((\$jenis_pengujian === 'Mikrobiologi' && \$tpl_b['kode_template'] === 'MIK-04') || (\$jenis_pengujian === 'Kimia' && \$tpl_b['kode_template'] === 'KIM-01')) ? 'selected' : ''; ?>";

if (strpos($f, $search) !== false) {
    $f = str_replace($search, $replace, $f);
    file_put_contents('application/views/pengujian/pilih_form.php', $f);
    echo "SUCCESS: Updated pilih_form.php\n";
} else {
    echo "Pattern not found!\n";
}
