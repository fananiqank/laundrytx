<?php
require('../fpdf.php');


$pdf = new FPDF('L','mm',array(85.60,53.98));
$pdf->AddPage();
$pdf->SetFont('Arial','B',8);
$pdf->Cell(0,20,'Hello World!',0,0,'R');
$pdf->Output();
?>
