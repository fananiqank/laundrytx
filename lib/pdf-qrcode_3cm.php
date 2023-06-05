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
        $this->Image($gambar, 1, 3, 16, 16, "png");
        //menggeser posisi sekarang
    }

    function judul($teks1, $teks2, $teks3, $teks4, $teks5, $teks6, $teks7,$teks8,$teks9,$teks10)
    {
        //$this->SetMargins(10, 0, 0);
        $this->SetXY(16, 6);
        $this->SetFont('Arial', 'B', '9');
        $this->Cell(0, 1, $teks1, 0, 1, 'L');
        $this->SetXY(16, 9);
        $this->SetFont('Arial', 'B', '8');
        $this->Cell(0, 2, $teks2, 0, 1, 'L');
        $this->SetXY(16, 12);
        $this->SetFont('Arial', 'B', '6');
        $this->Cell(0, 3, $teks3, 0, 1, 'L');
        $this->SetXY(16, 15);
        $this->SetFont('Arial', 'B', '5');
        $this->Cell(0, 2, $teks4, 0, 'L', false);
        $this->SetXY(16, 18);
        $this->SetFont('Arial', 'B', '6');
        $this->Cell(0, 1, $teks5, 0, 1, 'L');
        $this->SetXY(39, 6);
        $this->SetFont('Arial', 'B', '6');
        $this->Cell(0, 1, $teks6, 0, 1, 'L');
        $this->SetXY(45, 9);
        $this->SetFont('Arial', 'B', '6');
        $this->Cell(0, 1, $teks7, 0, 1, 'L');
        $this->SetXY(48, 18);
        $this->SetFont('Arial', 'B', '6');
        $this->Cell(0, 1, $teks8, 0, 1, 'L');
        $this->SetXY(16, 23);
        $this->SetFont('Arial', 'B', '5');
        $this->Cell(0, 1, $teks9, 0, 1, 'L');
        $this->SetXY(37, 23);
        $this->SetFont('Arial', 'B', '5');
        $this->Cell(0, 1, $teks10, 0, 1, 'L');
    }
}
//create a QR Code and save it as a png image file named test.png
//QRcode::png("coded number here", "test.png");

$datano = base64_decode($_GET['c']);

foreach ($con->select("(select lot_no as lotno, 
                        CASE 
                        WHEN COALESCE(a.lot_qty_good_upd,0) != 0
                        THEN COALESCE(a.lot_qty_good_upd,0)
                        ELSE lot_qty
                    END as qty, lot_status as status, lot_id as id,a.wo_master_dtl_proc_id,a.role_wo_name_seq as role_wo_name_seq,
c.wo_no,c.garment_colors,c.type_source,shift_id,TO_CHAR( C.ex_fty_date, 'DD-MM-YYYY' ) AS ex_fty_date,C.buyer_id,a.lot_createdate 
from laundry_lot_number a 
JOIN laundry_wo_master_dtl_proc c ON a.wo_master_dtl_proc_id=c.wo_master_dtl_proc_id 
where lot_no = '$datano' 
        UNION select rec_no as lotno, rec_qty as qty, rec_status as status, rec_id as id,d.wo_master_dtl_proc_id,'1' as role_wo_name_seq,
            f.wo_no,f.garment_colors,f.type_source,shift_id,TO_CHAR( f.ex_fty_date, 'DD-MM-YYYY' ) AS ex_fty_date,f.buyer_id,d.rec_createdate 
            from laundry_receive d 
            JOIN laundry_wo_master_dtl_proc f ON d.wo_master_dtl_proc_id=f.wo_master_dtl_proc_id 
            where rec_no = '$datano') as a",
"lotno,qty,status,id,wo_master_dtl_proc_id,wo_no,garment_colors,shift_id,ex_fty_date,buyer_id,lot_createdate,role_wo_name_seq,CASE WHEN type_source = 3 THEN 'QRCODE' ELSE 'MANUAL' END as type_source") as $cmt) {}
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
if ($typelot == 'Q') {
    $title = 'QA Sample';
    $ref = '';
} else if ($typelot == 'R') {
    $title = 'Reject Lot';
    $ref = '';
} else if ($typelot == 'N') {
    $title = 'Normal Prod';
    $ref = '';
} else if ($typelot == 'C') {
    $title = 'Combine Lot';
    $ref = '<tr>
              <td width="30%">Ref. Lot No</td>
              <td width="70%">: ' . $datano . '</td>
          </tr>';
} else if ($typelot == 'B') {
    $title = 'Split Lot';
    $ref = '<tr>
              <td width="30%">Ref. Lot No</td>
              <td width="70%">: ' . $datano . '</td>
          </tr>';
} else if ($typelot == 'P') {
    $title = 'Pre Bulk Lot';
    $ref = '';
} else if ($typelot == 'F') {
    $title = 'First Bulk Lot';
    $ref = '';
} else if ($typelot == 'W') {
    $title = 'Rework QA Lot';
    $ref = '';
} else if ($typelot == 'M') {
    $title = 'Rework Lot';
    $ref = '';
} else if ($typelot == 'S') {
    $title = 'Scrap Lot';
    $ref = '';
} else if ($typelot == 'T') {
    $title = 'Standart Lot';
    $ref = '';
}
else {
    $title = 'Receive Lot';
    $ref = '';
}

$isigambar = "";
//pdf stuff
$pdf = new pdf('L', 'mm', array(30, 60));
$pdf->AddPage();
$pdf->SetAutoPageBreak(false);
$pdf->Line(1, 16, 1, 6);
$pdf->letak("http://qrcode.eratex.co.id/laundry/lib/qr_generator.php?code=".$datano);
$pdf->judul($title, $datano, $cmt['wo_no'], $cmt['garment_colors'], $cmt['buyer_id'].' | efd: '.$cmt['ex_fty_date'], $cmt['lot_createdate'], 'Qty: '.$cmt['qty'], $cmt['shift_id'].' | '.$cmt['role_wo_name_seq'],$cmt['type_source'],'cetak: '.$date);
$pdf->Output();
