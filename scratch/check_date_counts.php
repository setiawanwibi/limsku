<?php
$mysqli = new mysqli('localhost', 'root', '', 'limsku');
$res = $mysqli->query('SELECT COUNT(*) as c FROM reagents WHERE expired_date IS NULL');
echo 'NULL count: ' . $res->fetch_assoc()['c'] . PHP_EOL;
$res = $mysqli->query('SELECT COUNT(*) as c FROM reagents WHERE expired_date = "0000-00-00"');
echo '0000-00-00 count: ' . $res->fetch_assoc()['c'] . PHP_EOL;
$res = $mysqli->query('SELECT COUNT(*) as c FROM reagents WHERE expired_date IS NOT NULL AND expired_date != "0000-00-00"');
echo 'Valid date count: ' . $res->fetch_assoc()['c'] . PHP_EOL;
$res = $mysqli->query('SELECT COUNT(*) as c FROM reagents');
echo 'Total: ' . $res->fetch_assoc()['c'] . PHP_EOL;
