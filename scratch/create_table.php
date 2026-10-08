<?php
$mysqli = new mysqli('localhost', 'root', '', 'limsku');
if ($mysqli->connect_error) {
    echo "CONNECT_ERROR: " . $mysqli->connect_error . "\n";
    exit(1);
}
$sql = file_get_contents(__DIR__ . '/../database/schema_user_signatures.sql');
if ($mysqli->query($sql) === TRUE) {
    echo "TABLE_CREATED_SUCCESS\n";
} else {
    echo "QUERY_ERROR: " . $mysqli->error . "\n";
}
