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

//edit machine 
if ($_POST['jenis'] == 'edit'){
		$dataprocess = array( 
							'machine_name' => $_POST['name'],
							'machine_category' => $_POST['category'],
							'machine_status' => $status,
							'machine_createdate' => $date,
							'machine_createdby' => $_SESSION['ID_LOGIN'],
							'machine_code' => $_POST['machine_code'],
							'machine_type_use' => $_POST['machine_type_use']
						);
		//var_dump($dataprocess);
		$execprocess = $con->update("laundry_master_machine",$dataprocess,"machine_id = '$_POST[kode]'"); 
		//var_dump($execprocess);
} 
//input process pada machine
else if ($_POST['confprocess'] == '1'){
	$count = $_POST['counting'];
	foreach ($_POST['process'] as $key => $value){
		$expvalue = explode("_", $value);
		$idmachinedtl = $con->idurut("laundry_master_machine_dtl","machine_dtl_id");
		$dataprocess = array( 
							'machine_dtl_id' => $idmachinedtl,
							'machine_id' => $_POST['machineid'],
							'master_process_id' => $expvalue[0],
							'machine_dtl_status' => 1,
							'machine_dtl_createdate' => $date,
							'machine_dtl_createdby' => $_SESSION['ID_LOGIN']
						);
	
		$execprocess = $con->insert("laundry_master_machine_dtl",$dataprocess); 
	
	}
} 
//Delete process pada machine
else if ($_POST['confprocess'] == '2'){

		$idmachinedtl = $con->idurut("laundry_master_machine_dtl","machine_dtl_id");
		$dataprocess = array( 
							'machine_dtl_status' => 0,
							'machine_dtl_modifydate' => $date,
							'machine_dtl_modifyby' => $_SESSION['ID_LOGIN'],
						);
		
		$execprocess = $con->update("laundry_master_machine_dtl",$dataprocess,"machine_dtl_id = '$_GET[idd]'"); 
		
} 
//input machine baru
else {
		$idprocess = $con->idurut("laundry_master_machine","machine_id");
		$dataprocess = array( 
							'machine_id' => $idprocess,
							'machine_name' => $_POST['name'],
							'machine_category' => $_POST['category'],
							'machine_status' => 1,
							'machine_createdate' => $date,
							'machine_createdby' => $_SESSION['ID_LOGIN'],
							'machine_code' => $_POST['machine_code'],
							'machine_type_use' => $_POST['machine_type_use']
						);
		//var_dump($dataprocess);
		$execprocess = $con->insert("laundry_master_machine",$dataprocess); 
		//var_dump($execprocess);
}

		


