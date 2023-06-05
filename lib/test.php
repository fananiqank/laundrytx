<?php
require_once("fpdf/fpdf.php");

$pdf = new FPDF('P', 'mm', 'A4');

$pdf->AddPage();


//$pdf->Image("http://localhost:7777/laundry/lib/qr_generator.php?code=1234567", 10, 10, 20, 20, "png");

$pdf->Output();
