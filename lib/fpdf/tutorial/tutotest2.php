<?php
require('../code128.php');

$pdf=new PDF_Code128('L','mm',array(85.60,50.98));
$pdf->AddPage();
$pdf->SetAutoPageBreak(false);
$pdf->SetMargins(0,0,5); 
//A set
$code='343429';
$pdf->SetFont('Arial','B',16);
$pdf->SetXY(30,32);
$pdf->Cell(0,5,$code,0,0,'R');
$pdf->SetFont('Arial','B',12);
$pdf->SetXY(30,37);
$pdf->Cell(0,5,'Semaon Aliman Kartosoewirjo',0,0,'R'); //max 35
$pdf->SetFont('Arial','',8);
$pdf->SetXY(30,42);
$pdf->Cell(0,5,'23-09-1965',0,0,'R');
$pdf->SetFont('Arial','',10);
$pdf->SetXY(30,45);
$pdf->Cell(0,5,'Klayatan III - Bandungrejosari',0,0,'R'); // max 35 Char
$pdf->Code128(5,43,$code,20,5);



$pdf->Output();
?>
