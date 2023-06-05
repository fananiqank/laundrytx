	<?php
	session_start();
	include "../../../funlibs.php";
	$con = new Database;
	//error_reporting(0);
	$date = date("Y-m-d H:i:s");
	$date2 = date("Y-m-d_G-i-s");
	$datetok = date('dmy');

	$datarolemaster = array(
							"wo_no"=>trim($_POST[cmt_no]),
							"garment_colors"=>$_POST[color_no],
							"ex_fty_date"=>$_POST[ex_fty_date_asli],
							"recdtl_inseam"=>$_POST[inseam],
							"recdtl_size"=>$_POST[sz],
							"recdtl_qty"=>$_POST[rcv_qty],
							"userid"=>$_SESSION[ID_LOGIN],
							"washcolor"=>$_POST[color_wash]
						);
	// var_dump($datarolemaster);
	$execrolemaster = $con->insert("laundry_receive_manualdtl_tmp", $datarolemaster);
	echo $execrolemaster;
	?>
