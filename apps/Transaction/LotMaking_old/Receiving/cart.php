<?php 

session_start();
//echo $_SESSION['ID_PEG'];
include "../../../funlibs.php";
$con = new Database();


$selwipcart = $con->select("wip_dtl","count(wip_dtl_id) as jmlwip","wip_dtl_status = 9 and wip_dtl_createdby = '$_SESSION[ID_LOGIN]'");
foreach ($selwipcart as $cart) {}

echo $cart['jmlwip'];
?>