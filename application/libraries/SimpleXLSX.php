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

    public static function parse($filename)
    {
        $xlsx = new self();
        if ($xlsx->_parse($filename)) {
            return $xlsx;
        }
        return false;
    }

    public function getError()
    {
        return $this->error;
    }

    public function rows($sheetIndex = 0)
    {
        if (!isset($this->sheets[$sheetIndex])) {
            return array();
        }

        $xml = simplexml_load_string($this->sheets[$sheetIndex]);
        if (!$xml) {
            return array();
        }

        $rows = array();
        foreach ($xml->sheetData->row as $row) {
            $r = array();
            foreach ($row->c as $cell) {
                $v = (string) $cell->v;
                $t = (string) $cell['r']; // cell reference e.g. A1
                $type = (string) $cell['t'];

                if ($type === 's') { // shared string
                    $v = isset($this->sharedstrings[(int)$v]) ? $this->sharedstrings[(int)$v] : $v;
                } elseif ($type === 'b') { // boolean
                    $v = ($v === '1') ? 'TRUE' : 'FALSE';
                }

                $r[] = trim($v);
            }
            $rows[] = $r;
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

        // Baca Sheet1
        for ($i = 1; $i <= 10; $i++) {
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
}
