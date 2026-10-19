<?php
define('BASEPATH', '1');
require_once 'C:/xampp/htdocs/LIMSKU/application/libraries/FPDF.php';

$sig_file = 'C:/xampp/htdocs/LIMSKU/uploads/signatures/test_proof.jpg';
$dir = dirname($sig_file);
if (!is_dir($dir)) mkdir($dir, 0777, true);

$im = imagecreatetruecolor(100, 50);
imagefilledrectangle($im, 0, 0, 100, 50, imagecolorallocate($im, 255, 255, 255));
imageline($im, 5, 25, 95, 25, imagecolorallocate($im, 0, 0, 128));
imagejpeg($im, $sig_file, 90);
imagedestroy($im);

class PDFWithImage extends FPDF {
    public function Image($file, $x=null, $y=null, $w=0, $h=0) {
        if(!isset($this->images[$file])) {
            $a = @getimagesize($file);
            if(!$a) return false;
            // Convert PNG or other image formats to JPEG in-memory/temp if needed, or parse JPEG directly
            if($a[2] == IMAGETYPE_PNG) {
                $im_temp = imagecreatefrompng($file);
                $tmp_jpg = sys_get_temp_dir() . '/sig_tmp_' . md5($file) . '.jpg';
                imagejpeg($im_temp, $tmp_jpg, 95);
                imagedestroy($im_temp);
                $file = $tmp_jpg;
                $a = getimagesize($file);
            }
            if($a[2] != IMAGETYPE_JPEG) return false;

            $colspace = 'DeviceRGB';
            if(isset($a['channels'])) {
                if($a['channels']==4) $colspace = 'DeviceCMYK';
                elseif($a['channels']==1) $colspace = 'DeviceGray';
            }
            $bpc = isset($a['bits']) ? $a['bits'] : 8;
            $data = file_get_contents($file);
            $info = array('w'=>$a[0], 'h'=>$a[1], 'cs'=>$colspace, 'bpc'=>$bpc, 'f'=>'DCTDecode', 'data'=>$data);
            $info['i'] = count($this->images)+1;
            $this->images[$file] = $info;
        } else {
            $info = $this->images[$file];
        }

        if($w==0 && $h==0) {
            $w = -96;
            $h = -96;
        }
        if($w<0) $w = -$info['w']*72/$w/$this->k;
        if($h<0) $h = -$info['h']*72/$h/$this->k;
        if($w==0) $w = $h*$info['w']/$info['h'];
        if($h==0) $h = $w*$info['h']/$info['w'];

        if($x===null) $x = $this->x;
        if($y===null) $y = $this->y;

        $this->_out(sprintf('q %.2F 0 0 %.2F %.2F %.2F cm /I%d Do Q', $w*$this->k, $h*$this->k, $x*$this->k, ($this->h-($y+$h))*$this->k, $info['i']));
        return true;
    }

    protected function _putimages() {
        if(empty($this->images)) return;
        foreach($this->images as $file=>$info) {
            $this->_newobj();
            $this->images[$file]['n'] = $this->n;
            $this->_put('<</Type /XObject');
            $this->_put('/Subtype /Image');
            $this->_put('/Width '.$info['w']);
            $this->_put('/Height '.$info['h']);
            $this->_put('/ColorSpace /'.$info['cs']);
            if($info['cs']=='DeviceCMYK')
                $this->_put('/Decode [1 0 1 0 1 0 1 0]');
            $this->_put('/BitsPerComponent '.$info['bpc']);
            $this->_put('/Filter /'.$info['f']);
            $this->_put('/Length '.strlen($info['data']).'>>');
            $this->_putstream($info['data']);
            $this->_put('endobj');
        }
    }

    protected function _putresourcedict() {
        parent::_putresourcedict();
        if(!empty($this->images)) {
            $this->_put('/XObject <<');
            foreach($this->images as $image)
                $this->_put('/I'.$image['i'].' '.$image['n'].' 0 R');
            $this->_put('>>');
        }
    }

    protected function _putresources() {
        $this->_putimages();
        parent::_putresources();
    }
}

$pdf = new PDFWithImage();
$pdf->AddPage();
$pdf->SetFont('helvetica', 'B', 14);
$pdf->Cell(0, 10, 'Test Image Support');
$pdf->Image($sig_file, 50, 40, 40, 20);
$out_file = 'C:/xampp/htdocs/LIMSKU/uploads/test_signature_render.pdf';
$pdf->Output('F', $out_file);

if (file_exists($out_file) && filesize($out_file) > 1000) {
    echo "SUCCESS_EMBEDDED: " . filesize($out_file) . " bytes\n";
} else {
    echo "FAILED_EMBEDDING\n";
}
