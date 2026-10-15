<?php
/**
 * Test Suite Non-Database untuk Parser SimpleXLSX (Tahap 3)
 * 
 * Pengujian Terisolasi:
 * 1. Konversi Kolom A..Z dan multi-huruf (AA, AB, AZ, BA, ZZ)
 * 2. Sel A terisi, B & C kosong, D terisi (tidak ada pergeseran)
 * 3. Katalog kosong tidak menggeser Nama Barang ke kolom Katalog
 * 4. Stok dan satuan tetap berada pada kolom yang tepat
 * 5. Kolom AA/AB dipetakan dengan benar
 * 6. Baris kosong dan sel kosong di akhir baris tidak error
 * 7. File rusak / bukan XLSX ditangani dengan error yang dapat dibaca (no fatal error)
 * 8. SimpleXLSX::parseError() aman dipanggil secara statis
 */

define('BASEPATH', 'defined');
require_once __DIR__ . '/../application/libraries/SimpleXLSX.php';

echo "=== TEST SUITE SIMPLEXLSX PARSER (NON-DATABASE) ===\n\n";

$all_passed = true;
$tests_run = 0;

function run_assert($condition, $test_name, $details = '') {
    global $all_passed, $tests_run;
    $tests_run++;
    if ($condition) {
        echo "[PASS] {$test_name}\n";
        if ($details) echo "       {$details}\n";
    } else {
        $all_passed = false;
        echo "[FAIL] {$test_name}\n";
        if ($details) echo "       ERROR: {$details}\n";
    }
}

// -------------------------------------------------------------
// HELPER: Buat File XLSX Mini di sys_get_temp_dir()
// -------------------------------------------------------------
function buat_fixture_xlsx($sheetXmlContent, $sharedStrings = array()) {
    $tempFile = sys_get_temp_dir() . '/test_fixture_' . uniqid() . '.xlsx';
    $zip = new ZipArchive();
    if ($zip->open($tempFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
        die("Gagal membuat fixture XLSX temporer di {$tempFile}\n");
    }

    $zip->addFromString('[Content_Types].xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">
  <Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>
  <Default Extension="xml" ContentType="application/xml"/>
  <Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>
  <Override PartName="/xl/worksheets/sheet1.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>
  <Override PartName="/xl/sharedStrings.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sharedStrings+xml"/>
</Types>');

    $zip->addFromString('_rels/.rels', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>
</Relationships>');

    $zip->addFromString('xl/_rels/workbook.xml.rels', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet1.xml"/>
  <Relationship Id="rId2" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/sharedStrings" Target="sharedStrings.xml"/>
</Relationships>');

    $zip->addFromString('xl/workbook.xml', '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">
  <sheets>
    <sheet name="Sheet1" sheetId="1" r:id="rId1"/>
  </sheets>
</workbook>');

    // Shared Strings
    $sstXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";
    $sstXml .= '<sst xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" count="' . count($sharedStrings) . '" uniqueCount="' . count($sharedStrings) . '">';
    foreach ($sharedStrings as $s) {
        $sstXml .= '<si><t>' . htmlspecialchars($s, ENT_XML1, 'UTF-8') . '</t></si>';
    }
    $sstXml .= '</sst>';
    $zip->addFromString('xl/sharedStrings.xml', $sstXml);

    // Worksheet
    $wsXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";
    $wsXml .= '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main"><sheetData>';
    $wsXml .= $sheetXmlContent;
    $wsXml .= '</sheetData></worksheet>';
    $zip->addFromString('xl/worksheets/sheet1.xml', $wsXml);

    $zip->close();
    return $tempFile;
}

// =============================================================
// SKENARIO 1: Pengujian Unit Konversi Kolom (A..Z dan AA..ZZ)
// =============================================================
echo "--- Skenario 1: Konversi Huruf Kolom ke Indeks Numerik ---\n";
run_assert(SimpleXLSX::columnIndex('A') === 0, "Kolom A -> 0", "Dapat: " . SimpleXLSX::columnIndex('A'));
run_assert(SimpleXLSX::columnIndex('B') === 1, "Kolom B -> 1", "Dapat: " . SimpleXLSX::columnIndex('B'));
run_assert(SimpleXLSX::columnIndex('C') === 2, "Kolom C -> 2", "Dapat: " . SimpleXLSX::columnIndex('C'));
run_assert(SimpleXLSX::columnIndex('D') === 3, "Kolom D -> 3", "Dapat: " . SimpleXLSX::columnIndex('D'));
run_assert(SimpleXLSX::columnIndex('Z') === 25, "Kolom Z -> 25", "Dapat: " . SimpleXLSX::columnIndex('Z'));
run_assert(SimpleXLSX::columnIndex('AA') === 26, "Kolom AA -> 26", "Dapat: " . SimpleXLSX::columnIndex('AA'));
run_assert(SimpleXLSX::columnIndex('AB') === 27, "Kolom AB -> 27", "Dapat: " . SimpleXLSX::columnIndex('AB'));
run_assert(SimpleXLSX::columnIndex('AZ') === 51, "Kolom AZ -> 51", "Dapat: " . SimpleXLSX::columnIndex('AZ'));
run_assert(SimpleXLSX::columnIndex('BA') === 52, "Kolom BA -> 52", "Dapat: " . SimpleXLSX::columnIndex('BA'));
run_assert(SimpleXLSX::columnIndex('ZZ') === 701, "Kolom ZZ -> 701", "Dapat: " . SimpleXLSX::columnIndex('ZZ'));

// =============================================================
// SKENARIO 2 & 3 & 4: Sel Kosong di Tengah (Sparse Cells)
// Baris 1: Header (No, Kode Barang, Katalog, Nama Barang, Merk, Stok, Jenis Barang, Expired Date)
// Baris 2: Sel A=1, B=KOD-001, C=(KOSONG/OMITTED), D="Asam Klorida HCl 37%", E="Merck", F="3 PAC", G="Reagen Cair", H="2028-12-31"
// =============================================================
echo "\n--- Skenario 2, 3, 4: Penanganan Sparse Cells (Katalog Kosong Tidak Menggeser Nama Barang & Stok) ---\n";
$sst = array(
    'No', 'Kode Barang', 'Katalog', 'Nama Barang', 'Merk', 'Stok', 'Jenis Barang', 'Expired Date',
    'KOD-001', 'Asam Klorida HCl 37%', 'Merck', '3 PAC', 'Reagen Cair', '2028-12-31'
);

$sheetData = '';
// Row 1 (Header: A1..H1)
$sheetData .= '<row r="1">';
$sheetData .= '<c r="A1" t="s"><v>0</v></c>'; // No
$sheetData .= '<c r="B1" t="s"><v>1</v></c>'; // Kode Barang
$sheetData .= '<c r="C1" t="s"><v>2</v></c>'; // Katalog
$sheetData .= '<c r="D1" t="s"><v>3</v></c>'; // Nama Barang
$sheetData .= '<c r="E1" t="s"><v>4</v></c>'; // Merk
$sheetData .= '<c r="F1" t="s"><v>5</v></c>'; // Stok
$sheetData .= '<c r="G1" t="s"><v>6</v></c>'; // Jenis Barang
$sheetData .= '<c r="H1" t="s"><v>7</v></c>'; // Expired Date
$sheetData .= '</row>';

// Row 2: Data dengan kolom C (Katalog) ditiadakan oleh Excel (sparse cell)
$sheetData .= '<row r="2">';
$sheetData .= '<c r="A2"><v>1</v></c>';
$sheetData .= '<c r="B2" t="s"><v>8</v></c>';  // KOD-001 (Indeks 1)
// C2 ditiadakan!
$sheetData .= '<c r="D2" t="s"><v>9</v></c>';  // Asam Klorida (Harus di Indeks 3)
$sheetData .= '<c r="E2" t="s"><v>10</v></c>'; // Merck (Harus di Indeks 4)
$sheetData .= '<c r="F2" t="s"><v>11</v></c>'; // 3 PAC (Harus di Indeks 5)
$sheetData .= '<c r="G2" t="s"><v>12</v></c>'; // Reagen Cair (Harus di Indeks 6)
$sheetData .= '<c r="H2" t="s"><v>13</v></c>'; // 2028-12-31 (Harus di Indeks 7)
$sheetData .= '</row>';

$fixture1 = buat_fixture_xlsx($sheetData, $sst);
$xlsx = SimpleXLSX::parse($fixture1);

run_assert($xlsx !== false, "Fixture 1 berhasil diparsing");
$rows = $xlsx->rows();

run_assert(count($rows) === 2, "Jumlah baris terbaca = 2");
$r2 = $rows[1];

run_assert(isset($r2[0]) && $r2[0] === '1', "Indeks 0 (No) = '1'");
run_assert(isset($r2[1]) && $r2[1] === 'KOD-001', "Indeks 1 (Kode Barang) = 'KOD-001'");
run_assert(isset($r2[2]) && $r2[2] === '', "Indeks 2 (Katalog) = '' (Kosong, TIDAK tergeser)");
run_assert(isset($r2[3]) && $r2[3] === 'Asam Klorida HCl 37%', "Indeks 3 (Nama Barang) = 'Asam Klorida HCl 37%' (TETAP pada posisi kolom D)");
run_assert(isset($r2[4]) && $r2[4] === 'Merck', "Indeks 4 (Merk) = 'Merck'");
run_assert(isset($r2[5]) && $r2[5] === '3 PAC', "Indeks 5 (Stok) = '3 PAC' (TETAP pada posisi kolom F)");
run_assert(isset($r2[6]) && $r2[6] === 'Reagen Cair', "Indeks 6 (Jenis Barang) = 'Reagen Cair'");
run_assert(isset($r2[7]) && $r2[7] === '2028-12-31', "Indeks 7 (Expired Date) = '2028-12-31'");

unlink($fixture1);

// =============================================================
// SKENARIO 5: Baris Kosong & Sel Kosong di Akhir Baris
// =============================================================
echo "\n--- Skenario 5: Baris Kosong & Sel Kosong di Akhir Baris ---\n";
$sheetData2 = '';
// Row 1: A1..C1
$sheetData2 .= '<row r="1"><c r="A1"><v>H1</v></c><c r="B1"><v>H2</v></c><c r="C1"><v>H3</v></c></row>';
// Row 2: Kosong total
$sheetData2 .= '<row r="2"/>';
// Row 3: Hanya A3 terisi, B3 dan C3 di akhir baris kosong
$sheetData2 .= '<row r="3"><c r="A3"><v>Data A3</v></c></row>';

$fixture2 = buat_fixture_xlsx($sheetData2);
$xlsx2 = SimpleXLSX::parse($fixture2);
$rows2 = $xlsx2->rows();

run_assert(count($rows2) === 3, "3 baris terdeteksi (termasuk row kosong)");
run_assert($rows2[0] === array('H1', 'H2', 'H3'), "Baris 1 lengkap");
run_assert($rows2[1] === array(), "Baris 2 kosong adalah array kosong tanpa fatal error");
run_assert($rows2[2][0] === 'Data A3', "Baris 3 indeks 0 terisi dengan aman");

unlink($fixture2);

// =============================================================
// SKENARIO 6: Kolom Setelah Z (AA dan AB)
// =============================================================
echo "\n--- Skenario 6: Kolom Multi-Huruf (AA dan AB) ---\n";
$sheetData3 = '';
$sheetData3 .= '<row r="1">';
$sheetData3 .= '<c r="A1"><v>A_VAL</v></c>';
$sheetData3 .= '<c r="Z1"><v>Z_VAL</v></c>';
$sheetData3 .= '<c r="AA1"><v>AA_VAL</v></c>';
$sheetData3 .= '<c r="AB1"><v>AB_VAL</v></c>';
$sheetData3 .= '</row>';

$fixture3 = buat_fixture_xlsx($sheetData3);
$xlsx3 = SimpleXLSX::parse($fixture3);
$rows3 = $xlsx3->rows();
$r_multi = $rows3[0];

run_assert($r_multi[0] === 'A_VAL', "Indeks 0 = A_VAL");
run_assert($r_multi[25] === 'Z_VAL', "Indeks 25 = Z_VAL");
run_assert($r_multi[26] === 'AA_VAL', "Indeks 26 = AA_VAL (Kolom AA)");
run_assert($r_multi[27] === 'AB_VAL', "Indeks 27 = AB_VAL (Kolom AB)");
run_assert($r_multi[1] === '', "Indeks 1 (B) kosong = ''");

unlink($fixture3);

// =============================================================
// SKENARIO 7 & 8: Error Handling & Pemanggilan SimpleXLSX::parseError()
// =============================================================
echo "\n--- Skenario 7 & 8: Penanganan File Rusak & Static parseError() ---\n";

// 1. File tidak ada
$res_nonexistent = SimpleXLSX::parse('path/tidak/ada/file.xlsx');
run_assert($res_nonexistent === false, "Parse file tidak ada mengembalikan false");
$err1 = SimpleXLSX::parseError();
run_assert(!empty($err1), "SimpleXLSX::parseError() mengembalikan pesan string", "Pesan: '{$err1}'");

// 2. File corrupt (bukan zip)
$corruptFile = sys_get_temp_dir() . '/corrupt_test_' . uniqid() . '.xlsx';
file_put_contents($corruptFile, 'INI BUKAN FILE ZIP SAMA SEKALI');

$res_corrupt = SimpleXLSX::parse($corruptFile);
run_assert($res_corrupt === false, "Parse file corrupt mengembalikan false tanpa fatal error");
$err2 = SimpleXLSX::parseError();
run_assert(!empty($err2), "SimpleXLSX::parseError() mengembalikan pesan zip corrupt", "Pesan: '{$err2}'");
unlink($corruptFile);

// 3. Verifikasi getError() pada instance yang valid (mengembalikan string kosong saat sukses)
$fixture_ok = buat_fixture_xlsx('<row r="1"><c r="A1"><v>OK</v></c></row>');
$xlsx_ok = SimpleXLSX::parse($fixture_ok);
run_assert($xlsx_ok !== false, "Parse file normal sukses");
run_assert($xlsx_ok->getError() === '', "getError() pada parsing sukses bernilai string kosong");
unlink($fixture_ok);

echo "\n=======================================================\n";
echo "RINGKASAN: {$tests_run} tes dijalankan. Status: " . ($all_passed ? "SEMUA TES BERHASIL (100% PASS)" : "ADA TES GAGAL") . "\n";
echo "=======================================================\n";

exit($all_passed ? 0 : 1);
