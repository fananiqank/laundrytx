<?php
require('../code128.php');

$pdf=new PDF_Code128('L','mm',array(85.60,53.98));
$pdf->AddPage();
$pdf->SetFont('Arial','',10);

//A set
$code='343429';
$pdf->Code128(4,45,$code,20,5);
// $pdf->SetXY(20,10);
// // $pdf->Write(5,'A set: "'.$code.'"');
$pdf->ln(10);
$pdf->SetFont('Arial','B',8);

$pdf->Cell(0,10,'Hello World!',1,1,'R');

$pdf->Output();
?>
