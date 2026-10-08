<?php
$mysqli = new mysqli('localhost', 'root', '', 'limsku');
if ($mysqli->connect_error) {
    echo "ERROR: " . $mysqli->connect_error . "\n";
    exit(1);
}
$res = $mysqli->query("SELECT p.id, p.nama_permission, p.deskripsi, rp.role_id, r.nama_role FROM permissions p LEFT JOIN role_permissions rp ON p.id = rp.permission_id LEFT JOIN roles r ON r.id = rp.role_id WHERE p.nama_permission LIKE 'sampel%'");
while ($row = $res->fetch_assoc()) {
    echo "Role: " . ($row['nama_role'] ?? 'None') . " (ID: " . ($row['role_id'] ?? '-') . ") -> Permission: " . $row['nama_permission'] . " (ID: " . $row['id'] . ")\n";
}
