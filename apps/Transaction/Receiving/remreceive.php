	<?php
	session_start();
	include "../../../funlibs.php";
	$con = new Database;
	//error_reporting(0);
	$date = date("Y-m-d H:i:s");
	$date2 = date("Y-m-d_G-i-s");
	$datetok = date('dmy');

	$datarolemaster = array(
							"recdtl_id"=>trim($_GET[id])
						);
	// var_dump($datarolemaster);
	$execrolemaster = $con->delete("laundry_receive_manualdtl_tmp", $datarolemaster);
	echo $execrolemaster;
	?>
