<?php
// scratch/batch03_schema_audit.php
$mysqli = new mysqli('localhost', 'root', '', 'limsku');
if ($mysqli->connect_error) die('DB connect failed: ' . $mysqli->connect_error);

echo "=== COLUMNS ===\n";
$res = $mysqli->query('SHOW FULL COLUMNS FROM reagents');
while ($row = $res->fetch_assoc()) {
    echo sprintf(
        "%-15s | %-20s | Null:%-3s | Key:%-3s | Default:%-10s | Extra:%s\n", 
        $row['Field'], $row['Type'], $row['Null'], $row['Key'], var_export($row['Default'], true), $row['Extra']
    );
}

echo "\n=== TABLE STATUS ===\n";
$res = $mysqli->query("SHOW TABLE STATUS WHERE Name = 'reagents'");
$status = $res->fetch_assoc();
echo 'Engine: ' . $status['Engine'] . "\n";
echo 'Rows: ' . $status['Rows'] . "\n";
echo 'Collation: ' . $status['Collation'] . "\n";

echo "\n=== INDEXES ===\n";
$res = $mysqli->query('SHOW INDEX FROM reagents');
while ($row = $res->fetch_assoc()) {
    echo sprintf("Index: %-20s | Non_unique: %d | Column: %-15s\n", $row['Key_name'], $row['Non_unique'], $row['Column_name']);
}

echo "\n=== TRIGGERS ===\n";
$res = $mysqli->query("SHOW TRIGGERS WHERE `Table` = 'reagents'");
if ($res->num_rows === 0) {
    echo "No triggers on reagents table.\n";
} else {
    while ($row = $res->fetch_assoc()) {
        echo "Trigger: " . $row['Trigger'] . " | Event: " . $row['Event'] . "\n";
    }
}

$mysqli->close();
