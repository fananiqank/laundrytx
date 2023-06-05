<?php
error_reporting(0);
require('fpdf/code128.php');

$pdf=new PDF_Code128('L','mm',array(85.60,50.98));
// $pdf=new PDF_Code128('L','mm',array(50,20));
$pdf->AddPage();
$pdf->SetAutoPageBreak(false);
$pdf->SetMargins(0,0,5); 
//A set
$dtk=explode(";",$_GET[rm]);
$pdf->SetFont('Arial','',8);
$pdf->SetXY(50,15);
$pdf->Cell(0,5,$dtk[3],0,0,'L');
$pdf->SetFont('Arial','',8);
$pdf->SetXY(50,12);
$pdf->Cell(0,5,ucwords(strtolower(str_replace("_"," ",$dtk[1]))),0,0,'L'); //max 35


$pdf->SetFont('Arial','',8);
$pdf->SetXY(50,18);
if(strlen($dtk[5])<=35){
	$pdf->Cell(0,5,str_replace("_", " ",ucwords(strtolower( $dtk[5]))),0,0,'L'); // max 35 Char
} else {
	$pdf->Cell(0,5,ucwords(strtolower(str_replace("_", " ", substr($dtk[5],0,30)))),0,0,'L'); // max 35 Char
}
$pdf->Code128(50,43,$dtk[3],25,5);



$pdf->Output();
