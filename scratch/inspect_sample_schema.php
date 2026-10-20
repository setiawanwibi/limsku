<?php
$conn = new mysqli('localhost', 'root', '', 'limsku');
echo "=== COLUMNS IN SAMPLES ===" . PHP_EOL;
$res = $conn->query("DESCRIBE samples");
while ($r = $res->fetch_assoc()) {
    echo $r['Field'] . " | ";
}
echo PHP_EOL . PHP_EOL . "=== SAMPLE DATA (ID 4) ===" . PHP_EOL;
$res = $conn->query("SELECT * FROM samples WHERE id = 4");
if ($row = $res->fetch_assoc()) {
    foreach ($row as $k => $v) {
        echo "$k: $v" . PHP_EOL;
    }
}
