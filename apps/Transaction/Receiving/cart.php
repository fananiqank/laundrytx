<?php 

session_start();
//echo $_SESSION['ID_PEG'];
include "../../../funlibs.php";
$con = new Database();


$selwipcart = $con->select("laundry_receive_manualdtl_tmp","count(*) as jmlwip","userid = '$_SESSION[ID_LOGIN]'");
foreach ($selwipcart as $cart) {}

echo $cart['jmlwip'];
?>