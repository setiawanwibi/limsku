<?php
// Migration Script: Tambah kolom komoditi_akses pada tabel users
$mysqli = new mysqli("localhost", "root", "", "limsku");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error . "\n");
}

// 1. Cek apakah kolom komoditi_akses sudah ada
$check = $mysqli->query("SHOW COLUMNS FROM `users` LIKE 'komoditi_akses'");
if ($check->num_rows == 0) {
    $sql = "ALTER TABLE `users` ADD COLUMN `komoditi_akses` TEXT NULL COMMENT 'JSON array atau string akses modul komoditi' AFTER `role_id`";
    if ($mysqli->query($sql) === TRUE) {
        echo "✅ Kolom `komoditi_akses` berhasil ditambahkan ke tabel `users`.\n";
    } else {
        echo "❌ Gagal menambah kolom: " . $mysqli->error . "\n";
    }
} else {
    echo "ℹ️ Kolom `komoditi_akses` sudah ada di tabel `users`.\n";
}

// 2. Set default nilai berdasarkan peran pengguna yang ada
// Admin (role 1) -> ["all"] atau ["admin", "obat", "pangan", "herbal", "kosmetik", "rekap"]
// Manajer Teknis (role 2) -> ["all"]
// Penyelia (role 3) -> ["obat"]
// Penguji (role 4) -> ["obat"]
// Petugas Sampel (role 5 / role_name Petugas Sampel) -> ["all_sampel"]

$users = $mysqli->query("SELECT u.id, u.username, u.role_id, r.nama_role FROM users u LEFT JOIN roles r ON r.id = u.role_id");
while ($row = $users->fetch_assoc()) {
    $uid = $row['id'];
    $roleName = strtolower($row['nama_role'] ?? '');
    $roleId = (int)$row['role_id'];

    if (stripos($roleName, 'admin') !== false || $roleId === 1) {
        $akses = json_encode(['all']);
    } elseif (stripos($roleName, 'manajer teknis') !== false || stripos($roleName, 'mt') !== false || $roleId === 2) {
        $akses = json_encode(['all']);
    } elseif (stripos($roleName, 'sampel') !== false || $roleId === 5) {
        $akses = json_encode(['all_sampel']);
    } elseif (stripos($roleName, 'mikro') !== false) {
        $akses = json_encode(['obat', 'pangan', 'herbal', 'kosmetik']);
    } else {
        // Default penguji / penyelia kimia saat ini difokuskan ke obat
        $akses = json_encode(['obat']);
    }

    $escaped = $mysqli->real_escape_string($akses);
    $mysqli->query("UPDATE `users` SET `komoditi_akses` = '$escaped' WHERE `id` = $uid");
    echo "Pengguna #$uid ({$row['username']} - {$row['nama_role']}) diset akses: $akses\n";
}

echo "✅ Migrasi data pengguna selesai 100%.\n";
$mysqli->close();
