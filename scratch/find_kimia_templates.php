<?php
$dirs = [
    'd:/xampp/htdocs/limsku',
    'C:/Users/heris/Downloads',
    'C:/Users/heris/Documents',
    'C:/Users/heris/Desktop'
];

foreach ($dirs as $d) {
    if (!is_dir($d)) continue;
    echo "Scanning: $d ...\n";
    try {
        $it = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($d, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );
        $it->setMaxDepth(3); // limit depth to avoid long scans
        foreach ($it as $f) {
            if ($f->isFile() && preg_match('/\.(xlsx|xls|pdf|docx|png|jpg)$/i', $f->getFilename())) {
                $name = $f->getFilename();
                if (stripos($name, 'reaksi') !== false || stripos($name, 'warna') !== false || stripos($name, 'pengendapan') !== false || stripos($name, 'lcp') !== false || stripos($name, 'kimia') !== false || stripos($name, 'template') !== false || stripos($name, 'form') !== false) {
                    echo "  -> " . $f->getPathname() . " (" . $f->getSize() . " bytes)\n";
                }
            }
        }
    } catch (Exception $e) {
        echo "Error scanning $d: " . $e->getMessage() . "\n";
    }
}
