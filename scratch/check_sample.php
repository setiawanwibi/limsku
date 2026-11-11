<?php
$conn = @new mysqli('127.0.0.1', 'root', '', 'limsku');
$res = $conn->query("SELECT * FROM samples WHERE id = 52");
if ($row = $res->fetch_assoc()) {
    echo "Sample 52: " . json_encode($row) . "\n";
} else {
    echo "Sample 52 not found\n";
}
