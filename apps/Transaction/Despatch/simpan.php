<?php 
session_start();
include "../../../funlibs.php";
$con = new Database;
//error_reporting(0);
$date = date("Y-m-d H:i:s");
$date2 = date("Y-m-d_G-i-s");
$tabel = "laundry_lot_number";

if($_SESSION['ID_LOGIN'] == ''){
	$userlogin = '835';
} else {
	$userlogin = $_SESSION['ID_LOGIN'];
}

//shift 
foreach($con->select("laundry_master_shift","shift_id,shift_time_start,shift_time_end,shift_status","TO_CHAR(now(), 'HH24:MI') between TO_CHAR(shift_time_start, 'HH24:MI') and TO_CHAR(shift_time_end, 'HH24:MI') and shift_status = 1") as $shift){}
//end shift
	
if ($_POST['type_lot'] == 'A'){
		$jenis = 1;
} else {
		$jenis = 2;
}

$expdata = explode('_',$_POST['data_process']);
$role_wo_master_id = $expdata[0];
$role_wo_id  = $expdata[1];

$wo_master_dtl_proc_id = $expdata[2];
if ($expdata[3] == ''){
$master_process_id = '0';
}else {
$master_process_id = $expdata[3];
}
if ($expdata[4] == ''){
$master_process_usemachine = 0;
} else {
$master_process_usemachine = $expdata[4];	
}

$rolewomasterid = $_POST['role-wo-master-id'];

//cek di laundry process sudah ada apa belum 
$selectprocess = $con->select("laundry_process","count(process_id) jmlprocessid","role_wo_master_id = '$role_wo_master_id' and role_wo_id = '$role_wo_id' and wo_master_dtl_proc_id = '$wo_master_dtl_proc_id'");
foreach ($selectprocess as $process) {}
	
//dapatkan qty reject terakhir di laundry_lot_number
foreach ($con->select("laundry_lot_number","lot_id,lot_qty_reject_upd","lot_no = '$_POST[lot_no_process]'") as $qtyrej){}
foreach ($con->select("laundry_role_wo_master","role_wo_master_rev","role_wo_master_id = '$rolewomasterid'") as $rolerev){}

//select master_type_lot_id	
foreach($con->select("laundry_master_type_lot","master_type_lot_id","master_type_lot_initial = '$_POST[type_lot]'") as $mtlot){}

// simpan dari create lot
if ($_POST['process-status'] == '3'){
	$expdata = explode('_',$_POST['data_process']);
	$role_wo_master_id = $expdata[0];
	$role_wo_id  = $expdata[1];
	$wo_master_dtl_proc_id = $expdata[2];
	$seqlot = $expdata[5];
	$rolechildid = $expdata[6];

	if ($_POST['type_lot'] == 'A'){
			$jenis = 1;
			$datarec = array(
						 'rec_break_status' => 0,
						);
			//var_dump($datarec);
			$execrec = $con->update("laundry_receive",$datarec,"rec_no = '".$_POST['lot_no_process']."'");
			//var_dump($execrec);
			foreach($con->select("laundry_receive","rec_id as lotid","rec_no = '".$_POST['lot_no']."'") as $lotid){}
			$mastertypelotid = "0";
	} else {
			$jenis = 2;
			$datarec = array(
						 'lot_status' => 2,
						);
			$execrec = $con->update("laundry_lot_number",$datarec,"lot_no = '".$_POST['lot_no_process']."'");
			foreach($con->select("laundry_lot_number","lot_id as lotid,master_type_lot_id","lot_no = '".$_POST['lot_no_process']."'") as $lotid){}
			$mastertypelotid = $lotid['master_type_lot_id'];
	}
	$idprocess = $con->idurut("laundry_process","process_id");
	$dataprocess = array(
						 'process_id' => $idprocess,
						 'wo_master_dtl_proc_id' => $_POST['wo-master-dtl-proc-id'],
						 'lot_type' => $jenis,
						 'master_process_id' => 0,
						 'process_type' => 4,
						 'wo_no' => $_POST['wo_no_process'],
						 'garment_colors' => $_POST['garment_colors_process'],
						 'lot_no' => $_POST['lot_no_process'],
						 'master_type_process_id' => $_POST['mastertypeprocessid'],
						 'master_type_lot_id' => $mastertypelotid,
						 'role_wo_master_id' => $rolewomasterid,
						 'role_wo_id' => $_POST['rolewoid'],
						 'role_dtl_wo_id' => 0,
						 'process_createdate' => $date,
						 'process_createdby' => $userlogin,
						 'process_status' => 1,
						 'machine_id' => 0,
						 'process_qty_good' => $_POST['qty_process'],
						 'process_qty_reject' => 0,
						 'process_qty_repair' => 0, 
						 'process_qty_total' => $_POST['qty_process'],
						 'role_wo_master_rev' => $rolerev['role_wo_master_rev'],
						 'shift_id' => $shift['shift_id'],
						 'user_code'=> $_POST['usercode'],
						 'role_wo_name_seq' => $_POST['rolewonameseq'],
						 'operator' => $_POST['operator'],
						 'change_process' => 0
		);
	$execprocess= $con->insert("laundry_process", $dataprocess);

	// input master data QC
	$idgood = $con->idurut("laundry_wo_master_dtl_despatch","wo_master_dtl_desp_id");
	$datagood = array(
						'wo_master_dtl_desp_id' => $idgood,
						'lot_no' => $_POST['lot_no_process'],
						'wo_master_dtl_desp_createdate' => $date,
						'wo_master_dtl_desp_createdby' => $userlogin,
						'wo_master_dtl_desp_status' => 1,
						'wo_master_dtl_desp_qty' => $_POST['qty_process'], 
						'wo_master_dtl_proc_id' => $_POST['wo-master-dtl-proc-id'],
						'shift_id' => $shift['shift_id'],
						'user_code' => $_POST['usercode'],
						'operator' => $_POST['operator']
					);
	//var_dump($datagood);	
	$execgood= $con->insert("laundry_wo_master_dtl_despatch", $datagood);
	// ==================
	
	$datarwnd = array(
					 'lot_status' => 2,
				);
	$execrwnd = $con->update("laundry_role_child_master",$datarwnd,"lot_no = '".$_POST['lot_no_process']."'");
	
	$datainchild = array(
						'role_child_status'  => 1,
						'role_child_modifydate' => $date,
					 	'role_child_modifyby' => $userlogin,
					 	'role_child_dateprocess' => $date,
					);
	$execinchild = $con->update("laundry_role_child",$datainchild,"role_child_id = '".$rolechildid."'");
	
	//input event Lot
		$idevent = $con->idurut("laundry_lot_event","event_id");
		$dataevent = array(
						 'event_id' => $idevent,
						 'lot_id' => $qtyrej['lot_id'],
						 'lot_no' => $_POST['lot_no_process'],
						 'event_type' => 1,
						 'event_status' => 1,
						 'event_createdby' => $userlogin,
						 'event_createdate' => $date,
						 'master_type_process_id' => 6,
						 'master_type_lot_id' => $mastertypelotid,
						 'shift_id' => $shift['shift_id'], 
						 'lot_type' => $jenis,
						 'user_code' => $_POST['usercode'],
		); 
		$execevent= $con->insert("laundry_lot_event", $dataevent);
	//end event lot
}
?>
