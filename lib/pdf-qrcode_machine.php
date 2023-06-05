<?php
//include the libraries
require_once("../funlibs.php");
$con = new Database;
session_start();

require_once("fpdf/fpdf.php");
require_once("phpqrcode/qrlib.php");

$datano = base64_decode($_GET['c']);

foreach ($con->select("laundry_master_machine","machine_name,machine_category,machine_id","machine_code = '$datano'") as $cmt) {}

$title = $cmt['machine_name'];

$selconmcn = $con->selectcount("laundry_master_process a JOIN laundry_master_machine_dtl b ON a.master_process_id=b.master_process_id","a.master_process_name","b.machine_id = '$cmt[machine_id]'");

$seldtlmcn = $con->select("laundry_master_process a JOIN laundry_master_machine_dtl b ON a.master_process_id=b.master_process_id","a.master_process_name","b.machine_id = '$cmt[machine_id]'");

foreach ($seldtlmcn as $mcn) {
    $ise[$mcn['master_process_name']] = $mcn['master_process_name'].",";  
}
$dta = implode(" ",$ise);
//$stra = explode(",", $dta);

class pdf extends FPDF
{

    function letak($gambar)
    {
        //memasukkan gambar untuk header
        $this->Image($gambar, 1, 2, 16, 16, "png");
        //menggeser posisi sekarang
    }

    function judul($teks1, $teks2, $teks3, $teks4, $teks5, $teks6, $teks7,$teks8)
    {
        //$this->SetMargins(10, 0, 0);
        $this->SetXY(18, 5);
        $this->SetFont('Times', 'B', '9');
        $this->Cell(0, 1, $teks1, 0, 1, 'L');
        $this->SetXY(18, 9);
        $this->SetFont('Times', 'B', '8');
        $this->Cell(0, 2, $teks2, 0, 1, 'L');
        $this->SetXY(18, 12);
        $this->SetFont('Times', 'B', '8');
        $this->Cell(0, 3, $teks3, 0, 1, 'L');
        $this->SetXY(2, 17);
        $this->SetFont('Times', 'B', '5');
        $this->Cell(0, 2, $teks4, 0, 'L', false);
    }
   
}
//create a QR Code and save it as a png image file named test.png
//QRcode::png("coded number here", "test.png");



//$sta = str_replace(" ", "", $dta);
//echo $sta;
$isigambar = "";
//pdf stuff
$pdf = new pdf('L', 'mm', array(20, 60));
$pdf->AddPage();
$pdf->SetAutoPageBreak(false);
$pdf->Line(1, 15, 1, 4);
$pdf->letak("http://qrcode.eratex.co.id/laundry/lib/qr_generator.php?code=".$datano);
$pdf->judul($title, $datano, $cmt['machine_category'],$dta);
$pdf->Output();
