<?php
//include the libraries
require_once("../funlibs.php");
$con = new Database;
session_start();
$date = date("Y-m-d H:i:s");

require_once("fpdf/fpdf.php");
require_once("phpqrcode/qrlib.php");

class pdf extends FPDF
{

    function letak($gambar)
    {
        //memasukkan gambar untuk header
        $this->Image($gambar, 1, 2, 16, 16, "png");
        //menggeser posisi sekarang
    }

    function judul($teks1, $teks2, $teks3, $teks4, $teks5, $teks6, $teks7,$teks8,$teks9,$teks10)
    {
        //$this->SetMargins(10, 0, 0);
        $this->SetXY(16, 4);
        $this->SetFont('Times', 'B', '7');
        $this->Cell(0, 1, $teks1, 0, 1, 'L');
        $this->SetXY(16, 7);
        $this->SetFont('Times', 'B', '7');
        $this->Cell(0, 2, $teks2, 0, 1, 'L');
        $this->SetXY(16, 9);
        $this->SetFont('Times', 'B', '6');
        $this->Cell(0, 3, $teks3, 0, 1, 'L');
        $this->SetXY(16, 12);
        $this->SetFont('Times', 'B', '5');
        $this->Cell(0, 2, $teks4, 0, 'L', false);
        $this->SetXY(16, 17);
        $this->SetFont('Times', 'B', '6');
        $this->Cell(0, 1, $teks5, 0, 1, 'L');
        $this->SetXY(35, 17);
        $this->SetFont('Times', 'B', '6');
        $this->Cell(0, 1, $teks6, 0, 1, 'L');
        $this->SetXY(43, 7);
        $this->SetFont('Times', 'B', '6');
        $this->Cell(0, 1, $teks7, 0, 1, 'L');
        $this->SetXY(48, 15);
        $this->SetFont('Times', 'B', '6');
        $this->Cell(0, 1, $teks8, 0, 1, 'L');
        $this->SetXY(16, 18);
        $this->SetFont('Times', 'B', '5');
        $this->Cell(0, 1, $teks9, 0, 1, 'L');
        $this->SetXY(37, 18);
        $this->SetFont('Times', 'B', '5');
        $this->Cell(0, 1, $teks10, 0, 1, 'L');
    }
}
//create a QR Code and save it as a png image file named test.png
//QRcode::png("coded number here", "test.png");

$datano = base64_decode($_GET['c']);

foreach ($con->select("laundry_label_container","*","label_no = '$datano'") as $cmt) {}
// echo "select lotno,qty,status,id,wo_master_dtl_proc_id,wo_no,garment_colors,shift_id,ex_fty_date,buyer_id,lot_createdate
// from (select lot_no as lotno, lot_qty as qty, lot_status as status, lot_id as id,a.wo_master_dtl_proc_id,
// c.wo_no,c.garment_colors,shift_id,TO_CHAR( C.ex_fty_date, 'DD-MM-YYYY' ) AS ex_fty_date,C.buyer_id,TO_CHAR( a.lot_createdate, 'DD-MM-YYYY' ) AS lot_createdate 
// from laundry_lot_number a 
// JOIN laundry_wo_master_dtl_proc c ON a.wo_master_dtl_proc_id=c.wo_master_dtl_proc_id 
// where lot_no = '$datano' 
//         UNION select rec_no as lotno, rec_qty as qty, rec_status as status, rec_id as id,d.wo_master_dtl_proc_id,
//             f.wo_no,f.garment_colors,shift_id,TO_CHAR( f.ex_fty_date, 'DD-MM-YYYY' ) AS ex_fty_date,f.buyer_id,TO_CHAR( d.rec_createdate, 'DD-MM-YYYY' ) AS lot_createdate 
//             from laundry_receive d 
//             JOIN laundry_wo_master_dtl_proc f ON d.wo_master_dtl_proc_id=f.wo_master_dtl_proc_id 
//             where rec_no = '$datano') as a";

$expdatano = explode("-", $datano);
$typelot = substr($expdatano[0], -4, 1);

$title = 'Label Container';
$ref = '';


$isigambar = "";
//pdf stuff
$pdf = new pdf('L', 'mm', array(20, 60));
$pdf->AddPage();
$pdf->SetAutoPageBreak(false);
$pdf->Line(1, 15, 1, 4);
$pdf->letak("http://qrcode.eratex.co.id/laundry/lib/qr_generator.php?code=".$datano);
$pdf->judul($title, $datano, $cmt['wo_no'], $cmt['garment_colors'], $cmt['label_createdate'],'| '.$cmt['usercode'], 'Qty: '.$cmt['label_qty']);
$pdf->Output();
