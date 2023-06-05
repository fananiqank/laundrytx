<?php
require_once("fpdf.php");

$pdf = new FPDF('P', 'mm', 'A4');

$pdf->AddPage();

$pdf->Image("http://192.168.51.26/laundry/lib/phpqrcode/index.php", 10, 10, 20, 20, "png");

$pdf->Output();
