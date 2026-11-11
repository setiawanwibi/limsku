<?php
// Simulate CodeIgniter call or test the function
$_SERVER['CI_ENV'] = 'development';
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
$_GET['jenis'] = 'Mikrobiologi';
$_GET['zat_aktif'] = 'PIROXICAM';

// Let's inspect application/controllers/Pengujian.php and application/views/pengujian/pilih_form.php
$pilih_form_view = file_get_contents(__DIR__ . '/../application/views/pengujian/pilih_form.php');
if (strpos($pilih_form_view, '$sesi') !== false) {
    echo "FOUND \$sesi in pilih_form.php!\n";
    // Find lines
    $lines = explode("\n", $pilih_form_view);
    foreach ($lines as $i => $l) {
        if (strpos($l, '$sesi') !== false) {
            echo "Line " . ($i+1) . ": " . trim($l) . "\n";
        }
    }
} else {
    echo "NO \$sesi in pilih_form.php\n";
}

$pengujian_ctrl = file_get_contents(__DIR__ . '/../application/controllers/Pengujian.php');
// Search for undefined $sesi in pilih_form method
preg_match('/public function pilih_form\(.*?\n    \}/s', $pengujian_ctrl, $matches);
if (!empty($matches[0])) {
    echo "pilih_form code:\n" . $matches[0] . "\n";
} else {
    echo "Could not extract pilih_form\n";
}

