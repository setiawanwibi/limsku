<?php
$f = fopen('C:\\xampp\\backups\\LIMSKU\\limsku_backup_pre_batch03_recovery_20261015_092500.sql', 'r');
for ($i = 0; $i < 40; $i++) {
    $line = fgets($f);
    echo $line;
}
fclose($f);
