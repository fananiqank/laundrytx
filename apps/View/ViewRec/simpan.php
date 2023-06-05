<?php
session_start();
include "../../../funlibs.php";
$con = new Database;
//error_reporting(0);
$date = date("Y-m-d H:i:s");
$date2 = date("Y-m-d_G-i-s");
$datetok = date('dmy');

//shift 
foreach ($con->select("laundry_master_shift", "shift_id,shift_time_start,shift_time_end,shift_status", "TO_CHAR(now(), 'HH24:MI') between TO_CHAR(shift_time_start, 'HH24:MI') and TO_CHAR(shift_time_end, 'HH24:MI') and shift_status = 1") as $shift) {
}
//end shift
if($_POST['type_cancel'] == '1'){
	//echo "a";
	$datacancel = array(
		'lot_no' => $_POST['lot_no'],
		'cancel_createdby' => $_SESSION['ID_LOGIN'],
		'cancel_createdate' => $date,
		'user_code' => $_POST['usercode'],
		'wo_master_dtl_proc_id' => $_POST['wo_master_dtl_proc_id'],
		'lot_type' => 1,
		'cancel_remark' => $_POST['cancelremark'],
		'cancel_lot_qty' => $_POST['lot_qty']
	);

	$execcancel = $con->insert("laundry_cancel_lot", $datacancel);
	//die();
	$where1 = array('rec_no' => $_POST['lot_no']);
	$exec1 = $con->delete("laundry_receive",$where1);

	$where2 = array('log_lot_tr' => $_POST['lot_no']);
	$exec2 = $con->delete("laundry_log",$where2);

	$where3 = array('lot_no' => $_POST['lot_no']);
	$exec3 = $con->delete("laundry_lot_event",$where3);

	$where4 = array('lot_no' => $_POST['lot_no']);
	$exec4 = $con->delete("laundry_process",$where4);

	$where5 = array('lot_no' => $_POST['lot_no']);
	$exec5 = $con->delete("laundry_role_child_master",$where5);

	$where6 = array('lot_no' => $_POST['lot_no']);
	$exec6 = $con->delete("laundry_role_child",$where6);

	$where7 = array('lot_no' => $_POST['lot_no']);
	$exec7 = $con->delete("laundry_process_machine_progress",$where7);

	if($_POST['lotep'] == 1){
		$where8 = array('rec_id' => $_POST['lotid']);
		$exec8 = $con->delete("laundry_qrcodebatch",$where8);
	}

	$selscan = $con->select("laundry_scan_qrcode","scan_id","lot_no = '$_POST[lot_no]'");
	foreach($selscan as $scan) {
		$datascanqrcode = array(
			'lot_no' => 'null',
			'scan_status' => '0'
		);

		$execscanqrcode = $con->update("laundry_scan_qrcode", $datascanqrcode,"scan_type = 1 and scan_id = '$scan[scan_id]'");
	}
} else {
	$datacancel = array(
		'lot_no' => $_POST['lot_no'],
		'cancel_createdby' => $_SESSION['ID_LOGIN'],
		'cancel_createdate' => $date,
		'user_code' => $_POST['usercode'],
		'wo_master_dtl_proc_id' => $_POST['wo_master_dtl_proc_id'],
		'lot_type' => 2,
		'cancel_remark' => $_POST['cancelremark'],
		'cancel_lot_qty' => $_POST['lot_qty']
	);

	$execcancel = $con->insert("laundry_cancel_lot", $datacancel);
	//die();
	$where1 = array('lot_no' => $_POST['lot_no']);
	$exec1 = $con->delete("laundry_lot_number",$where1);

	$where2 = array('log_lot_tr' => $_POST['lot_no']);
	$exec2 = $con->delete("laundry_log",$where2);

	$where3 = array('lot_no' => $_POST['lot_no']);
	$exec3 = $con->delete("laundry_lot_event",$where3);

	$where4 = array('lot_no' => $_POST['lot_no']);
	$exec4 = $con->delete("laundry_process",$where4);

	$where5 = array('lot_no' => $_POST['lot_no']);
	$exec5 = $con->delete("laundry_role_child_master",$where5);

	$where6 = array('lot_no' => $_POST['lot_no']);
	$exec6 = $con->delete("laundry_role_child",$where6);

	$where7 = array('lot_no' => $_POST['lot_no']);
	$exec7 = $con->delete("laundry_process_machine",$where7);

}

?>
