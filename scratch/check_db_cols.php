<?php
define('BASEPATH', 'dummy');
require 'application/config/database.php';
 = ['default'];
 = new PDO('mysql:host='.['hostname'].';dbname='.['database'], ['username'], ['password']);
 = ->query('DESCRIBE samples')->fetchAll(PDO::FETCH_COLUMN);
echo 'Columns in samples: ' . implode(', ', ) . PHP_EOL;
