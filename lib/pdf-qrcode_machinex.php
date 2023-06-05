<?php
//include the libraries
require_once("../funlibs.php");
$con = new Database;
session_start();

require_once("fpdf/fpdf.php");
require_once("phpqrcode/qrlib.php");

$datano = base64_decode($_GET['c']);



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
// $pdf = new pdf('L', 'mm', array(20, 60));
$pdf = new pdf('P','mm','A4');
 $pdf->AddPage();
 $pdf->SetAutoPageBreak(false);
 $col=1;
 $row=1;
 $p1=2;
 $p2=5;
 $p3=9;
 $p4=12;
 $p5=32;
 $tp=1;
foreach ($con->select("laundry_master_machine a LEFT JOIN laundry_master_machine_dtl b ON a.machine_id= b.machine_id
LEFT JOIN laundry_master_process c ON c.master_process_id=b.master_process_id group by a.machine_id order by machine_id ","a.machine_id, machine_code, machine_name, machine_category, string_agg(master_process_name,', ') as prosesname","") as $cmt) {

$title = $cmt['machine_name'];
// echo $cmt['machine_code'];
// $selconmcn = $con->selectcount("laundry_master_process a JOIN laundry_master_machine_dtl b ON a.master_process_id=b.master_process_id","a.master_process_name","b.machine_id = '$cmt[machine_id]'");

// $seldtlmcn = $con->select("laundry_master_process a JOIN laundry_master_machine_dtl b ON a.master_process_id=b.master_process_id","a.master_process_name","b.machine_id = '$cmt[machine_id]'");

// foreach ($seldtlmcn as $mcn) {
//     // echo "disini";
//     $ise[$mcn['master_process_name']] = $mcn['master_process_name'].",";  

    
// }
    $dta = implode(" ",$ise);
    // $pdf->SetAutoPageBreak(false);
   
       
    if($col==1){
         // $pdf->AddPage();
        // $pdf->SetAutoPageBreak(false);
        // $pdf->Line(100, 15, 1, 4);
        $pdf->Image("http://qrcode.eratex.co.id/laundry/lib/qr_generator.php?code=".$cmt['machine_code'], 2, $p1, 34, 34, "png");
        //$pdf->SetMargins(10, 0, 0);
        $pdf->SetXY(37, $p2);
        $pdf->SetFont('Times', 'B', '18');
        $pdf->Cell(0, 10, $title, 0, 1, 'L');
        $pdf->SetXY(37, $p3);
        $pdf->SetFont('Times', 'B', '16');
        $pdf->Cell(0, 20, $cmt['machine_code'], 0, 1, 'L');
        $pdf->SetXY(37, $p4);
        $pdf->SetFont('Times', 'B', '16');
        $pdf->Cell(0, 30, $cmt['machine_category'], 0, 1, 'L');
        $pdf->SetXY(37, $p5);
        $pdf->SetFont('Times', 'B', '10');
        $pdf->MultiCell(73, 6, $cmt['prosesname'], 0, 'L', false);
        // $pdf->letak("http://qrcode.eratex.co.id/laundry/lib/qr_generator.php?code=".$cmt['machine_code']);
        // $pdf->judul($title, $datano, $cmt['machine_category'],$dta);

     $col++;
    } else if($col==2){
        $pdf->Image("http://qrcode.eratex.co.id/laundry/lib/qr_generator.php?code=".$cmt['machine_code'], 106, $p1, 34, 34, "png");
        //$pdf->SetMargins(10, 0, 0);
        $pdf->SetXY(139, $p2);
        $pdf->SetFont('Times', 'B', '18');
        $pdf->Cell(0, 10, $title, 0, 1, 'L');
        $pdf->SetXY(139, $p3);
        $pdf->SetFont('Times', 'B', '16');
        $pdf->Cell(0, 20, $cmt['machine_code'], 0, 1, 'L');
        $pdf->SetXY(139, $p4);
        $pdf->SetFont('Times', 'B', '16');
        $pdf->Cell(0, 30, $cmt['machine_category'], 0, 1, 'L');
        $pdf->SetXY(139, $p5);
        $pdf->SetFont('Times', 'B', '10');
        $pdf->MultiCell(73, 6, $cmt['prosesname'], 0, 'L', false);

        $col=1;
        $p1+=60;
        $p2+=60;
        $p3+=60;
        $p4+=60;
        $p5+=60;
        $tp++;
    }
    
    if($tp==5){
        $pdf->AddPage(); 
        $p1=2;
        $p2=5;
        $p3=9;
        $p4=12;
        $p5=32;
        $tp=1;
    }
}


$pdf->Output();
