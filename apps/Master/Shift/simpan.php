<?php	
session_start();
include "../../../funlibs.php";
$con = new Database;
//error_reporting(0);
$date = date("Y-m-d H:i:s");
$date2 = date("Y-m-d");


if ($_POST['status_process'] == ''){
	$status = '0';
}else {
	$status = $_POST['status_process'];
}

if ($_POST['jenis'] == 'edit'){
		$dataprocess = array( 
							'shift_name' => $_POST['name'],
							'shift_time_start' => $_POST['starttime'],
							'shift_time_end' => $_POST['endtime'],
							'shift_status' => $status,
							'shift_modifydate' => $date,
							'shift_modifyby' => $_SESSION['ID_LOGIN'],
						);
		//var_dump($dataprocess);
		$execprocess = $con->update("laundry_master_shift",$dataprocess,"shift_id = '$_POST[kode]'"); 
		//var_dump($execprocess);
} else if($_GET['del'] == 1){
		$dataprocess = array( 
							'shift_status' => 2,
							'shift_modifydate' => $date,
							'shift_modifyby' => $_SESSION['ID_LOGIN'],
						);
		$execprocess = $con->update("laundry_master_shift",$dataprocess,"shift_id = '$_GET[id]'"); 
}else {
		$idprocess = $con->idurut("laundry_master_shift","shift_id");
		$dataprocess = array( 
							'shift_id' => $idprocess,
							'shift_name' => $_POST['name'],
							'shift_time_start' => $_POST['starttime'],
							'shift_time_end' => $_POST['endtime'],
							'shift_status' => 1,
							'shift_createdby' => $_SESSION['ID_LOGIN'],
							'shift_createdate' => $date,
						);
		var_dump($dataprocess);
		$execprocess = $con->insert("laundry_master_shift",$dataprocess); 
		var_dump($execprocess);
}

		


