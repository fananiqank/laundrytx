<?php 

session_start();
//echo $_SESSION['ID_PEG'];
include "../../../funlibs.php";
$con = new Database();


$cart = $con->selectcount("laundry_wo_master_dtl_despatch","wo_master_dtl_desp_id","wo_master_dtl_desp_status = 9 and lot_no = '$_GET[lot]'");
echo $cart;
?>