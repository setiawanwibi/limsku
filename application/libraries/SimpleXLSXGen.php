<?php
/**
 * SimpleXLSXGen - Simple XLSX Generator in PHP
 * Generates valid Microsoft Excel (.xlsx) files without external dependencies.
 */
class SimpleXLSXGen
{
    protected $sheets = [];
    protected $template = [];
    protected $defaultFont = 'Calibri';
    protected $defaultFontSize = 11;

    public function __construct()
    {
        $this->sheets[] = [
            'name' => 'Sheet1',
            'rows' => []
        ];
    }

    public static function fromArray(array $rows, $sheetName = null)
    {
        $xlsx = new self();
        if ($sheetName) {
            $xlsx->sheets[0]['name'] = $sheetName;
        }
        $xlsx->sheets[0]['rows'] = $rows;
        return $xlsx;
    }

    public function addSheet(array $rows, $name = null)
    {
        $this->sheets[] = [
            'name' => $name ?: 'Sheet' . (count($this->sheets) + 1),
            'rows' => $rows
        ];
        return $this;
    }

    public function downloadAs($filename)
    {
        $content = (string) $this;
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . strlen($content));
        header('Cache-Control: max-age=0');
        header('Pragma: public');
        echo $content;
        exit;
    }

    public function __toString()
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'xlsx_');
        $zip = new ZipArchive();
        if ($zip->open($tempFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return '';
        }

        // Shared strings & styles
        $sharedStrings = [];
        $styles = [
            // Default style (index 0)
            ['font' => 0, 'fill' => 0, 'border' => 0, 'align' => 'general']
        ];
        $fonts = [
            ['name' => $this->defaultFont, 'size' => $this->defaultFontSize, 'bold' => false, 'color' => '000000']
        ];
        $fills = [
            ['type' => 'none'],
            ['type' => 'gray125']
        ];
        $borders = [
            ['left' => false, 'right' => false, 'top' => false, 'bottom' => false]
        ];

        // 1. Process Sheets
        $sheetXMLs = [];
        foreach ($this->sheets as $sheetIndex => $sheet) {
            $sheetXML = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";
            $sheetXML .= '<worksheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">' . "\n";
            $sheetXML .= '<sheetData>' . "\n";

            foreach ($sheet['rows'] as $rIdx => $row) {
                $rowNum = $rIdx + 1;
                $sheetXML .= '<row r="' . $rowNum . '">' . "\n";

                $cIdx = 0;
                foreach ($row as $cellVal) {
                    $colLetter = $this->num2col($cIdx);
                    $cellRef = $colLetter . $rowNum;

                    // Header styling (Baris 1)
                    $styleIdx = 0;
                    if ($rIdx === 0) {
                        // Header style: Bold, Hijau BPOM background, Putih text, border
                        $styleIdx = 1;
                    } else {
                        // Data style: Border tipis
                        $styleIdx = 2;
                    }

                    if (is_numeric($cellVal) && !preg_match('/^0[0-9]/', (string)$cellVal)) {
                        $sheetXML .= '<c r="' . $cellRef . '" s="' . $styleIdx . '"><v>' . $cellVal . '</v></c>';
                    } else {
                        $strVal = (string) $cellVal;
                        $sIdx = array_search($strVal, $sharedStrings, true);
                        if ($sIdx === false) {
                            $sharedStrings[] = $strVal;
                            $sIdx = count($sharedStrings) - 1;
                        }
                        $sheetXML .= '<c r="' . $cellRef . '" t="s" s="' . $styleIdx . '"><v>' . $sIdx . '</v></c>';
                    }
                    $cIdx++;
                }

                $sheetXML .= '</row>' . "\n";
            }

            $sheetXML .= '</sheetData>' . "\n";
            $sheetXML .= '</worksheet>';
            $sheetXMLs[$sheetIndex] = $sheetXML;
            $zip->addFromString('xl/worksheets/sheet' . ($sheetIndex + 1) . '.xml', $sheetXML);
        }

        // 2. Shared Strings XML
        $ssXML = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";
        $ssXML .= '<sst xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" count="' . count($sharedStrings) . '" uniqueCount="' . count($sharedStrings) . '">' . "\n";
        foreach ($sharedStrings as $str) {
            $clean = htmlspecialchars($str, ENT_XML1, 'UTF-8');
            $ssXML .= '<si><t>' . $clean . '</t></si>' . "\n";
        }
        $ssXML .= '</sst>';
        $zip->addFromString('xl/sharedStrings.xml', $ssXML);

        // 3. Styles XML (Header Hijau Toska BPOM, Border Rapi)
        $styleXML = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";
        $styleXML .= '<styleSheet xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main">' . "\n";
        $styleXML .= '  <fonts count="2">' . "\n";
        $styleXML .= '    <font><sz val="11"/><name val="Calibri"/></font>' . "\n";
        $styleXML .= '    <font><b/><sz val="11"/><color rgb="FFFFFFFF"/><name val="Calibri"/></font>' . "\n";
        $styleXML .= '  </fonts>' . "\n";
        $styleXML .= '  <fills count="3">' . "\n";
        $styleXML .= '    <fill><patternFill patternType="none"/></fill>' . "\n";
        $styleXML .= '    <fill><patternFill patternType="gray125"/></fill>' . "\n";
        $styleXML .= '    <fill><patternFill patternType="solid"><fgColor rgb="FF159B98"/></patternFill></fill>' . "\n";
        $styleXML .= '  </fills>' . "\n";
        $styleXML .= '  <borders count="2">' . "\n";
        $styleXML .= '    <border><left/><right/><top/><bottom/></border>' . "\n";
        $styleXML .= '    <border>' . "\n";
        $styleXML .= '      <left style="thin"><color rgb="FFD0D7DE"/></left>' . "\n";
        $styleXML .= '      <right style="thin"><color rgb="FFD0D7DE"/></right>' . "\n";
        $styleXML .= '      <top style="thin"><color rgb="FFD0D7DE"/></top>' . "\n";
        $styleXML .= '      <bottom style="thin"><color rgb="FFD0D7DE"/></bottom>' . "\n";
        $styleXML .= '    </border>' . "\n";
        $styleXML .= '  </borders>' . "\n";
        $styleXML .= '  <cellXfs count="3">' . "\n";
        $styleXML .= '    <xf numFmtId="0" fontId="0" fillId="0" borderId="0"/>' . "\n";
        $styleXML .= '    <xf numFmtId="0" fontId="1" fillId="2" borderId="1" applyFont="1" applyFill="1" applyBorder="1" applyAlignment="1"><alignment horizontal="center" vertical="center"/></xf>' . "\n";
        $styleXML .= '    <xf numFmtId="0" fontId="0" fillId="0" borderId="1" applyBorder="1"/>' . "\n";
        $styleXML .= '  </cellXfs>' . "\n";
        $styleXML .= '</styleSheet>';
        $zip->addFromString('xl/styles.xml', $styleXML);

        // 4. Workbook XML
        $wbXML = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";
        $wbXML .= '<workbook xmlns="http://schemas.openxmlformats.org/spreadsheetml/2006/main" xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">' . "\n";
        $wbXML .= '  <sheets>' . "\n";
        foreach ($this->sheets as $idx => $s) {
            $wbXML .= '    <sheet name="' . htmlspecialchars($s['name'], ENT_XML1, 'UTF-8') . '" sheetId="' . ($idx + 1) . '" r:id="rId' . ($idx + 1) . '"/>' . "\n";
        }
        $wbXML .= '  </sheets>' . "\n";
        $wbXML .= '</workbook>';
        $zip->addFromString('xl/workbook.xml', $wbXML);

        // 5. Workbook Rels
        $wbRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";
        $wbRels .= '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">' . "\n";
        foreach ($this->sheets as $idx => $s) {
            $wbRels .= '  <Relationship Id="rId' . ($idx + 1) . '" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/worksheet" Target="worksheets/sheet' . ($idx + 1) . '.xml"/>' . "\n";
        }
        $wbRels .= '  <Relationship Id="rId' . (count($this->sheets) + 1) . '" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>' . "\n";
        $wbRels .= '  <Relationship Id="rId' . (count($this->sheets) + 2) . '" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/sharedStrings" Target="sharedStrings.xml"/>' . "\n";
        $wbRels .= '</Relationships>';
        $zip->addFromString('xl/_rels/workbook.xml.rels', $wbRels);

        // 6. Root Rels
        $rels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";
        $rels .= '<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">' . "\n";
        $rels .= '  <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="xl/workbook.xml"/>' . "\n";
        $rels .= '</Relationships>';
        $zip->addFromString('_rels/.rels', $rels);

        // 7. [Content_Types].xml
        $ctXML = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>' . "\n";
        $ctXML .= '<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">' . "\n";
        $ctXML .= '  <Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>' . "\n";
        $ctXML .= '  <Default Extension="xml" ContentType="application/xml"/>' . "\n";
        $ctXML .= '  <Override PartName="/xl/workbook.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet.main+xml"/>' . "\n";
        $ctXML .= '  <Override PartName="/xl/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.styles+xml"/>' . "\n";
        $ctXML .= '  <Override PartName="/xl/sharedStrings.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sharedStrings+xml"/>' . "\n";
        foreach ($this->sheets as $idx => $s) {
            $ctXML .= '  <Override PartName="/xl/worksheets/sheet' . ($idx + 1) . '.xml" ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.worksheet+xml"/>' . "\n";
        }
        $ctXML .= '</Types>';
        $zip->addFromString('[Content_Types].xml', $ctXML);

        $zip->close();
        $content = file_get_contents($tempFile);
        @unlink($tempFile);
        return $content;
    }

    protected function num2col($num)
    {
        $num = (int)$num;
        $col = '';
        while ($num >= 0) {
            $col = chr(($num % 26) + 65) . $col;
            $num = intdiv($num, 26) - 1;
        }
        return $col;
    }
}
