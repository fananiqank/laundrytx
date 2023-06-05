<?php 

session_start();
include "../../../funlibs.php";
$con = new Database;
//error_reporting(0);
$date = date("Y-m-d H:i:s");
$date2 = date("Y-m-d_G-i-s");

if ($_POST['type-lot'] == 'A'){
		$jenis = 1;
} else {
		$jenis = 2;
}

//Input Process IN
if ($_POST['process-status'] == '1'){
	$idprocess = $con->idurut("laundry_process","process_id");
	$dataprocess = array(
					 'process_id' => $idprocess,
					 'wo_master_dtl_proc_id' => $_POST['wo-master-dtl-proc-id'],
					 'lot_type' => $jenis,
					 'master_process_id' => $_POST['masterprocessid'],
					 'process_type' => 1,
					 'wo_no' => $_POST['wo-no'],
					 'garment_colors' => $_POST['colors'],
					 'lot_no' => $_POST['lot-no'],
					 'master_type_process_id' => $_POST['mastertypeprocessid'],
					 'master_type_lot_id' => 0,
					 'role_wo_master_id' => $_POST['role-wo-master-id'],
					 'role_wo_id' => $_POST['rolewoid'],
					 'role_dtl_wo_id' => $_POST['roledtlwoid'],
					 'process_createdate' => $date,
					 'process_createdby' => $_POST['user-id'],
					 'process_status' => 1,
					 'machine_id' => 0,
	); 
	$execprocess= $con->insert("laundry_process", $dataprocess);

//process start
} else if ($_POST['process-status'] == '2'){

	$idprocess = $con->idurut("laundry_process","process_id");
	$dataprocess = array(
					 'process_id' => $idprocess,
					 'wo_master_dtl_proc_id' => $_POST['wo-master-dtl-proc-id'],
					 'lot_type' => $jenis,
					 'master_process_id' => $_POST['masterprocessid'],
					 'process_type' => 2,
					 'wo_no' => $_POST['wo-no'],
					 'garment_colors' => $_POST['colors'],
					 'lot_no' => $_POST['lot-no'],
					 'master_type_process_id' => $_POST['mastertypeprocessid'],
					 'master_type_lot_id' => 0,
					 'role_wo_master_id' => $_POST['role-wo-master-id'],
					 'role_wo_id' => $_POST['rolewoid'],
					 'role_dtl_wo_id' => $_POST['roledtlwoid'],
					 'process_createdate' => $date,
					 'process_createdby' => $_POST['user-id'],
					 'process_status' => 1,
					 'machine_id' => $_GET['machine'],
	); 
	$execprocess= $con->insert("laundry_process", $dataprocess);

	$dataprocessmachine = array(
					 'process_machine_onprogress' => 2,
					 );
	$execprocessmachine = $con->update("laundry_process_machine",$dataprocessmachine,"lot_no = '$_GET[lot]' and machine_id = '$_GET[machine]'");
//process End
} else if ($_POST['process-status'] == '3'){
	
	$qtyreject = $_POST['qtygoodori']-$_POST['qtygood'];

	$idprocess = $con->idurut("laundry_process","process_id");
	$dataprocess = array(
					 'process_id' => $idprocess,
					 'wo_master_dtl_proc_id' => $_POST['wo-master-dtl-proc-id'],
					 'lot_type' => $jenis,
					 'master_process_id' => $_POST['masterprocessid'],
					 'process_type' => 3,
					 'wo_no' => $_POST['wo-no'],
					 'garment_colors' => $_POST['colors'],
					 'lot_no' => $_POST['lot-no'],
					 'master_type_process_id' => $_POST['mastertypeprocessid'],
					 'master_type_lot_id' => 0,
					 'role_wo_master_id' => $_POST['role-wo-master-id'],
					 'role_wo_id' => $_POST['rolewoid'],
					 'role_dtl_wo_id' => $_POST['roledtlwoid'],
					 'process_createdate' => $date,
					 'process_createdby' => $_POST['user-id'],
					 'process_status' => 1,
					 'machine_id' => $_GET['machine'],
					 'process_qty_good' => $_POST['qtygood'],
					 'process_qty_reject' => $qtyreject,
					 'process_qty_total' => $_POST['qtytotal'],
					 'process_remark' => $_POST['remarkmachine'],
	);
	$execprocess= $con->insert("laundry_process", $dataprocess);

	$dataprocessmachine = array(	
					 'process_machine_qty_good' => $_POST['qtygood'],
					 'process_machine_qty_reject' => $_POST['qtyreject'],
					 'process_machine_qty_total' => $_POST['qtytotal'],
					 'process_machine_remark' => $_POST['remarkmachine'],
					 'process_machine_onprogress' => 3,
					 );
	$execprocessmachine = $con->update("laundry_process_machine",$dataprocessmachine,"lot_no = '$_GET[lot]' and machine_id = '$_GET[machine]'");

//process Out
} else if ($_POST['process-status'] == '4'){
	
	if ($_GET['machine'] == ''){
		$getmachine = 0;
	} else {
		$getmachine = $_GET['machine'];
	}

	$qtyreject = $_POST['qtygoodori']-$_POST['qtygood'];
	
	$idprocess = $con->idurut("laundry_process","process_id");
	$dataprocess = array(
					 'process_id' => $idprocess,
					 'wo_master_dtl_proc_id' => $_POST['wo-master-dtl-proc-id'],
					 'lot_type' => $jenis,
					 'master_process_id' => $_POST['masterprocessid'],
					 'process_type' => 4,
					 'wo_no' => $_POST['wo-no'],
					 'garment_colors' => $_POST['colors'],
					 'lot_no' => $_POST['lot-no'],
					 'master_type_process_id' => $_POST['mastertypeprocessid'],
					 'master_type_lot_id' => 0,
					 'role_wo_master_id' => $_POST['role-wo-master-id'],
					 'role_wo_id' => $_POST['rolewoid'],
					 'role_dtl_wo_id' => $_POST['roledtlwoid'],
					 'process_createdate' => $date,
					 'process_createdby' => $_POST['user-id'],
					 'process_status' => 1,
					 'machine_id' => $getmachine,
					 'process_qty_good' => $_POST['qtygood'],
					 'process_qty_reject' => $qtyreject,
					 'process_qty_total' => $_POST['qtytotal'],
					 'process_remark' => $_POST['remarkmachine'],
	); 
	//var_dump($datalog);
	$execprocess= $con->insert("laundry_process", $dataprocess);
	//var_dump($execlog);

} 
//input machine from first page
else if ($_POST['machine-input'] == '1'){
	
	$idprocmachine = $con->idurut("laundry_process_machine","process_machine_id");
	$idseqmachine = $con->idseq("laundry_process_machine","process_machine_sequence","lot_no","lot_no");
	$dataprocmachine = array(
					 'process_machine_id' => $idprocmachine,
					 'master_process_id' => $_POST['master_process_id'],
					 'machine_id' => $_POST['machineid'],
					 'process_machine_createdby' => $_POST['userid'],
					 'process_machine_status' => 1,
					 'process_machine_createdate' => $date,
					 'lot_no' => $_POST['lot_no'],
					 'lot_type' => $jenis,
					 'process_machine_sequence' => $idseqmachine,
					 'process_machine_onprogress' => 1,
					 'role_wo_id' => $_POST['role_wo_id'],
					); 
	//var_dump($dataprocmachine);
	$execprocmachine= $con->insert("laundry_process_machine", $dataprocmachine);
	//var_dump($execprocmachine);

} 
//hapus machine
else if ($_POST['machine-input'] == '2'){
	//echo $_POST[machine_id];
	$dataprocmachine = array(
					 'process_machine_status' => 0,
					); 
	$execprocmachine= $con->update("laundry_process_machine", $dataprocmachine, "process_machine_id = '$_POST[machine_id]'");

}

//input machine time
else if ($_POST['machine-input'] == '3'){
	foreach ($_POST['timemac'] as $key => $value) {
		$postmachineid = "time_id_".$_GET['no'];
		$machineid = $_POST[$postmachineid];
		$dataprocmachine = array(
						 'process_machine_time' => $value,
						); 
		$execprocmachine= $con->update("laundry_process_machine", $dataprocmachine, 
						               "process_machine_id = '$machineid'");
	}	
}
//input machine from process
else if ($_POST['machine-input'] == '4'){
	foreach ($_POST['machine'] as $key => $value) {
		$expisi = explode("_", $_POST['machine_isi']);
		$master_process_id = $expisi[0];
		$lotno = $expisi[1];
		$user = $expisi[2];
		$timemachine = "timemachine_".$value;
		$idprocmachine = $con->idurut("laundry_process_machine","process_machine_id");
		$idseqmachine = $con->idseq("laundry_process_machine","process_machine_sequence","lot_no","lot_no");
		$dataprocmachine = array(
						 'process_machine_id' => $idprocmachine,
						 'master_process_id' => $master_process_id,
						 'machine_id' => $value,
						 'process_createdby' => $user,
						 'process_machine_status' => 1,
						 'process_machine_createdate' => $date,
						 'lot_no' => $lotno,
						 'lot_type' => $jenis,
						 'process_machine_sequence' => $idseqmachine,
						 'process_machine_onprogress' => 1,
						 'role_wo_id' => $_POST['role_wo_id_mod'],
						 'process_machine_time' => $_POST[$timemachine],
						); 
		var_dump($dataprocmachine);
		$execprocmachine= $con->insert("laundry_process_machine", $dataprocmachine);
		var_dump($execprocmachine);
	}

} 

?>
