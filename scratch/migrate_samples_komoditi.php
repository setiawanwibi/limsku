<?php
// Migration Script: Tambah kolom komoditi dan atribut_spesifik di tabel samples
$mysqli = new mysqli("localhost", "root", "", "limsku");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error . "\n");
}

// 1. Tambah kolom `komoditi` jika belum ada
$check1 = $mysqli->query("SHOW COLUMNS FROM `samples` LIKE 'komoditi'");
if ($check1->num_rows == 0) {
    $sql1 = "ALTER TABLE `samples` ADD COLUMN `komoditi` ENUM('obat', 'pangan', 'herbal', 'kosmetik') NOT NULL DEFAULT 'obat' AFTER `kategori_sampel`";
    if ($mysqli->query($sql1) === TRUE) {
        echo "✅ Kolom `komoditi` berhasil ditambahkan ke tabel `samples`.\n";
    } else {
        echo "❌ Gagal menambah kolom komoditi: " . $mysqli->error . "\n";
    }
} else {
    echo "ℹ️ Kolom `komoditi` sudah ada di tabel `samples`.\n";
}

// 2. Tambah kolom `atribut_spesifik` jika belum ada
$check2 = $mysqli->query("SHOW COLUMNS FROM `samples` LIKE 'atribut_spesifik'");
if ($check2->num_rows == 0) {
    $sql2 = "ALTER TABLE `samples` ADD COLUMN `atribut_spesifik` TEXT NULL COMMENT 'JSON data spesifik per komoditi' AFTER `komoditi`";
    if ($mysqli->query($sql2) === TRUE) {
        echo "✅ Kolom `atribut_spesifik` berhasil ditambahkan ke tabel `samples`.\n";
    } else {
        echo "❌ Gagal menambah kolom atribut_spesifik: " . $mysqli->error . "\n";
    }
} else {
    echo "ℹ️ Kolom `atribut_spesifik` sudah ada di tabel `samples`.\n";
}

// 3. Pastikan seluruh sampel yang ada saat ini diset ke komoditi 'obat'
$mysqli->query("UPDATE `samples` SET `komoditi` = 'obat' WHERE `komoditi` IS NULL OR `komoditi` = ''");
$affected = $mysqli->affected_rows;
echo "✅ $affected data sampel lama dipastikan berstatus komoditi: 'obat'.\n";

$mysqli->close();
