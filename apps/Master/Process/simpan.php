<?php	
session_start();
include "../../../funlibs.php";
$con = new Database;
//error_reporting(0);
$date = date("Y-m-d H:i:s");
$date2 = date("Y-m-d");

if ($_POST['usemachine'] == ''){
	$usemachine = '0';
} else {
	$usemachine = $_POST['usemachine'];
}

if ($_POST['usemultimachine'] == ''){
	$usemultimachine = '0';
}else {
	$usemultimachine = $_POST['usemultimachine'];
}

if ($_POST['usesplit'] == ''){
	$usesplit = '0';
}else {
	$usesplit = $_POST['usesplit'];
}

if ($_POST['usecombine'] == ''){
	$usecombine = '0';
}else {
	$usecombine = $_POST['usecombine'];
}

if ($_POST['status_process'] == ''){
	$status = '0';
}else {
	$status = $_POST['status_process'];
}

if ($_POST['jenis'] == 'edit'){
		$dataprocess = array( 
								 'master_process_name' => $_POST['name'],
								 'master_type_process' => $_POST['typeprocess'],
								 'master_process_usemachine' => $usemachine,
								 'master_process_usemultimachine' => $usemultimachine,
								 'master_process_split_lot' => $usesplit,
								 'master_process_combine_lot' => $usecombine,
								 'master_process_status' => $status,
								 'master_process_modifydate' => $date,
								 'master_process_modifyby' => $_SESSION['ID_LOGIN'],
						);
		
		$execprocess = $con->update("laundry_master_process",$dataprocess,"master_process_id = '$_POST[kode]'"); 
		//var_dump($execprocess);
} else {
		$idprocess = $con->idurut("laundry_master_process","master_process_id");
		$dataprocess = array( 
								 'master_process_id' => $idprocess,
								 'master_process_name' => $_POST['name'],
								 'master_type_process' => $_POST['typeprocess'],
								 'master_process_usemachine' => $usemachine,
								 'master_process_usemultimachine' => $usemultimachine,
								 'master_process_split_lot' => $usesplit,
								 'master_process_combine_lot' => $usecombine,
								 'master_process_status' => 1,
								 'master_process_createdate' => $date,
								 'master_process_createdby' => $_SESSION['ID_LOGIN'],
						);
		//var_dump($dataprocess);
		$execprocess = $con->insert("laundry_master_process",$dataprocess); 
		//var_dump($execprocess);
}

		


