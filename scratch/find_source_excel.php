<?php
// Cari berkas Excel sumber reagen
$dirs_to_check = [
    'C:/xampp/htdocs/LIMSKU/uploads',
    'C:/xampp/htdocs/LIMSKU/scratch',
    'C:/Users/ASUS/Downloads',
    'C:/Users/ASUS/OneDrive/Dokumen/Magang BPOM',
    'C:/Users/ASUS/Documents'
];

echo "=== MENCARI FILE EXCEL SUMBER INVENTARIS REAGEN ===\n\n";

foreach ($dirs_to_check as $d) {
    if (!is_dir($d)) continue;
    echo "Scanning: $d ...\n";
    try {
        $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($d, FilesystemIterator::SKIP_DOTS));
        foreach ($it as $file) {
            if ($file->isFile() && in_array(strtolower($file->getExtension()), ['xlsx', 'xls'])) {
                $name = $file->getFilename();
                $path = $file->getPathname();
                $size = $file->getSize();
                // Filter relevan
                if (stripos($name, 'reagen') !== false || stripos($name, 'baku') !== false || stripos($name, 'inventaris') !== false || stripos($name, 'media') !== false || stripos($name, 'barang') !== false || stripos($name, 'master') !== false || stripos($name, 'alat') !== false) {
                    echo "  -> DITEMUKAN: {$name} ({$size} bytes) di [{$path}]\n";
                }
            }
        }
    } catch (Exception $e) {
        echo "  [Warning/Skipped]: " . $e->getMessage() . "\n";
    }
}
