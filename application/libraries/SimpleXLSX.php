<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * SimpleXLSX Parser
 * 
 * Class ringan standalone untuk membaca file Excel .xlsx 
 * menggunakan extension ZipArchive dan SimpleXML bawaan PHP 7.4.
 */
class SimpleXLSX
{
    private $sheets = array();
    private $sharedstrings = array();
    private $error = '';
    private static $lastError = '';

    public static function parse($filename)
    {
        $xlsx = new self();
        if ($xlsx->_parse($filename)) {
            return $xlsx;
        }
        self::$lastError = $xlsx->getError();
        return false;
    }

    public static function parseError()
    {
        return self::$lastError;
    }

    public function getError()
    {
        return $this->error;
    }

    /**
     * Konversi huruf kolom Excel (A, B, ..., Z, AA, AB, ...) menjadi indeks numerik 0-based
     *
     * @param string $col
     * @return int
     */
    public static function columnIndex($col)
    {
        $col = strtoupper(trim((string)$col));
        $len = strlen($col);
        if ($len === 0) {
            return 0;
        }
        $idx = 0;
        for ($i = 0; $i < $len; $i++) {
            $c = ord($col[$i]);
            if ($c >= 65 && $c <= 90) { // A-Z
                $idx = $idx * 26 + ($c - 64);
            }
        }
        return max(0, $idx - 1);
    }

    public function rows($sheetIndex = 0)
    {
        if (!isset($this->sheets[$sheetIndex])) {
            return array();
        }

        $xml = simplexml_load_string($this->sheets[$sheetIndex]);
        if (!$xml || !isset($xml->sheetData)) {
            return array();
        }

        $rows = array();
        foreach ($xml->sheetData->row as $row) {
            $rowCells = array();
            $maxCol   = -1;
            $curCol   = 0;

            if (isset($row->c)) {
                foreach ($row->c as $cell) {
                    $colIdx = $curCol;
                    if (isset($cell['r']) && preg_match('/^([A-Z]+)\d*$/i', (string)$cell['r'], $matches)) {
                        $colIdx = self::columnIndex($matches[1]);
                    }

                    $v    = (string) $cell->v;
                    $type = isset($cell['t']) ? (string) $cell['t'] : '';

                    if ($type === 's') { // shared string
                        $v = isset($this->sharedstrings[(int)$v]) ? $this->sharedstrings[(int)$v] : $v;
                    } elseif ($type === 'b') { // boolean
                        $v = ($v === '1') ? 'TRUE' : 'FALSE';
                    } elseif ($type === 'inlineStr' && isset($cell->is)) {
                        if (isset($cell->is->t)) {
                            $v = (string) $cell->is->t;
                        } elseif (isset($cell->is->r)) {
                            $str = '';
                            foreach ($cell->is->r as $rPart) {
                                $str .= (string) $rPart->t;
                            }
                            $v = $str;
                        }
                    }

                    $rowCells[$colIdx] = trim((string)$v);
                    if ($colIdx > $maxCol) {
                        $maxCol = $colIdx;
                    }
                    $curCol = $colIdx + 1;
                }
            }

            if ($maxCol >= 0) {
                $r = array();
                for ($c = 0; $c <= $maxCol; $c++) {
                    $r[$c] = isset($rowCells[$c]) ? $rowCells[$c] : '';
                }
                $rows[] = $r;
            } else {
                $rows[] = array();
            }
        }

        return $rows;
    }

    private function _parse($filename)
    {
        if (!file_exists($filename) || !is_readable($filename)) {
            $this->error = 'File tidak ditemukan atau tidak dapat dibaca.';
            return false;
        }

        $zip = new ZipArchive();
        if ($zip->open($filename) !== true) {
            $this->error = 'Gagal membuka file XLSX (format zip corrupt/invalid).';
            return false;
        }

        // Baca Shared Strings jika ada
        if (($index = $zip->locateName('xl/sharedStrings.xml')) !== false) {
            $xmlStr = $zip->getFromIndex($index);
            $xml = simplexml_load_string($xmlStr);
            if ($xml) {
                foreach ($xml->si as $val) {
                    if (isset($val->t)) {
                        $this->sharedstrings[] = (string) $val->t;
                    } elseif (isset($val->r)) {
                        $str = '';
                        foreach ($val->r as $r) {
                            $str .= (string) $r->t;
                        }
                        $this->sharedstrings[] = $str;
                    } else {
                        $this->sharedstrings[] = '';
                    }
                }
            }
        }

        // Baca Workbook untuk memetakan nama sheet dan ID jika ada
        if (($wbIndex = $zip->locateName('xl/workbook.xml')) !== false) {
            $wbXml = simplexml_load_string($zip->getFromIndex($wbIndex));
            if ($wbXml && isset($wbXml->sheets->sheet)) {
                $idx = 0;
                foreach ($wbXml->sheets->sheet as $s) {
                    $sheetName = (string)$s['name'];
                    $rId = (string)$s['r:id'];
                    $this->sheetNames[$idx] = $sheetName;
                    $idx++;
                }
            }
        }

        // Baca Worksheet files
        for ($i = 1; $i <= 30; $i++) {
            $sheetPath = "xl/worksheets/sheet{$i}.xml";
            if (($index = $zip->locateName($sheetPath)) !== false) {
                $this->sheets[] = $zip->getFromIndex($index);
            }
        }

        $zip->close();

        if (empty($this->sheets)) {
            $this->error = 'Tidak ada worksheet yang ditemukan pada file Excel ini.';
            return false;
        }

        return true;
    }

    public function sheetNames()
    {
        return !empty($this->sheetNames) ? $this->sheetNames : array_keys($this->sheets);
    }
}
