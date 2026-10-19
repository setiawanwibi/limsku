<?php
$zip = new ZipArchive();
$filePath = 'C:/Users/ASUS/OneDrive/Dokumen/Magang BPOM/Data BPOM/Format Parameter Pengujian/Mikrobiologi/CP mikro.xlsx';
if ($zip->open($filePath) === true) {
    $sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml');
    $stylesXml = $zip->getFromName('xl/styles.xml');
    $sharedStringsXml = $zip->getFromName('xl/sharedStrings.xml');

    // Parse shared strings
    $sst = [];
    if (preg_match_all('/<si>(.*?)<\/si>/s', $sharedStringsXml, $siMatches)) {
        foreach ($siMatches[1] as $si) {
            preg_match_all('/<t[^>]*>(.*?)<\/t>/s', $si, $tMatches);
            $sst[] = implode('', $tMatches[1]);
        }
    }

    // Parse cell styles (borders, fills, fonts)
    preg_match_all('/<cellXfs[^>]*>(.*?)<\/cellXfs>/s', $stylesXml, $cxfs);
    $xfs = [];
    if (!empty($cxfs[1])) {
        preg_match_all('/<xf ([^>]+)\/>/s', $cxfs[1][0], $xfList);
        foreach ($xfList[1] as $idx => $xfAttr) {
            $xfs[$idx] = $xfAttr;
        }
    }

    // Inspect cells in Row 2 to 37
    echo "=== DETAILED CELL COORDINATES & MERGES ===\n";
    $merges = [];
    if (preg_match_all('/<mergeCell ref="([^"]+)"\/>/', $sheetXml, $mMatches)) {
        $merges = $mMatches[1];
    }
    echo "Total Merges: " . count($merges) . "\n";
    foreach ($merges as $m) {
        echo $m . " ";
    }
    echo "\n";

    $zip->close();
}
