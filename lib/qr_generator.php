<?php
require_once("phpqrcode/qrlib.php");
require('../funlibs.php');

$datano = ($_GET['code']);
//select data
QRcode::png($datano);
