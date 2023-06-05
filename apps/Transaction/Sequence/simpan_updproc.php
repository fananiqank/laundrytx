<?php
session_start();
include "../../../funlibs.php";
$con = new Database;
//error_reporting(0);
$date = date("Y-m-d H:i:s");
$date2 = date("Y-m-d_G-i-s");
$datetok = date('dmy');

	//echo "a";
	$datapr = array(
		'ex_fty_date' => $_GET['exdate'],
		'wo_master_dtl_proc_modifyby' => $_SESSION['ID_LOGIN'],
		'wo_master_dtl_proc_modifydate' => $date
	);

	$execpr = $con->update("laundry_wo_master_dtl_proc", $datapr,"wo_master_dtl_proc_id = $_GET[procid]");
	
?>
